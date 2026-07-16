<?php

date_default_timezone_set('America/Lima');

/** SQLite guarda CURRENT_TIMESTAMP en UTC (no en la zona horaria de PHP) -- cualquier fecha
 * cruda leida de la BD (fecha_creacion, fecha_ultimo_cambio, fecha_postulacion, etc.) debe
 * pasar por aqui antes de mostrarse, o se ve 5 horas adelantada respecto a la hora real de
 * Lima. Mismo criterio ya usado en Evaluaciones::segundosRestantes() para el cronometro,
 * aplicado ahora tambien a la simple visualizacion. Pedido de Ytalo, 2026-07-15. **/
function fechaLocal($fechaUtc, $formato = 'd/m/Y H:i'){
	if(empty($fechaUtc)){ return $fechaUtc; }
	$dt = new DateTime($fechaUtc, new DateTimeZone('UTC'));
	$dt->setTimezone(new DateTimeZone('America/Lima'));
	return $dt->format($formato);
}

/** Formatea un monto como soles peruanos con separador de miles (coma) -- pedido de
 * Ytalo, 2026-07-15, para la Pretensión salarial del candidato. **/
function formatearSoles($monto){
	if($monto === null || $monto === ''){ return null; }
	return 'S/ '.number_format((float) $monto, 2, '.', ',');
}

/** Primera fuente TTF real que exista en el servidor -- necesaria para dibujar los
 * nombres de competencia (con tildes/ñ) en el radar con imagettftext(). GD no trae
 * ninguna TTF propia y este proyecto no vendoriza ninguna (sin Composer, mismo
 * criterio de siempre), asi que se prueba primero la fuente de este entorno de
 * desarrollo (Windows) y luego las rutas mas comunes de una fuente Sans en hosting
 * compartido Linux, por si el proyecto se despliega ahi. Si no aparece ninguna,
 * generarRadarCompetencias() cae de vuelta a numeros (bitmap font nativo de GD,
 * siempre disponible) en vez de fallar. **/
function rutaFuenteChart(){
	$candidatos = [
		'C:/Windows/Fonts/arial.ttf',
		'/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
		'/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
		'/usr/share/fonts/dejavu/DejaVuSans.ttf',
		'/usr/share/fonts/liberation-sans/LiberationSans-Regular.ttf',
	];
	foreach($candidatos as $ruta){
		if(file_exists($ruta)){ return $ruta; }
	}
	return null;
}

/** Genera un grafico radar (poligono) en PNG puro con GD para las competencias
 * calificadas en una entrevista (seccion 4 del CLAUDE.md pedia un radar de 10
 * competencias fijas de la evaluacion SJT, pero esas ya se muestran como barras --
 * pedido de Ytalo, 2026-07-16: el radar real es solo para las competencias de LA
 * ENTREVISTA, cuya cantidad varia segun cuantas tenga asignadas/calificadas cada
 * vacante, no un numero fijo). html2pdf/TCPDF (esta version vendorizada) no soporta
 * graficos tipo Chart.js, asi que se dibuja con GD y se embebe como <img> (mismo
 * mecanismo ya usado para el logo de la empresa en el reporte).
 *
 * Cada eje se etiqueta con el nombre real de la competencia (imagettftext(), soporta
 * UTF-8 nativo -- a diferencia del bitmap font de GD, que no maneja bien tildes/ñ)
 * si hay una fuente TTF real disponible (ver rutaFuenteChart()); si no, cae a un
 * numero (1..N) por eje como respaldo, para no dejar el grafico sin dibujar solo por
 * falta de una fuente en el servidor.
 *
 * $calificaciones: array de objetos con ->competencia_nombre y ->calificacion (1-5).
 * Requiere al menos 3 para que un poligono tenga sentido visual. **/
function generarRadarCompetencias($calificaciones, $rutaSalida){
	$n = count($calificaciones);
	if($n < 3){ return false; }

	$fuente = rutaFuenteChart();
	$tamanoFuente = 12;

	// Canvas grande a proposito, dejando bastante margen entre el circulo y el borde --
	// los nombres de competencia en los ejes izquierdo/derecho (horizontales) necesitan
	// mucho mas espacio que los de arriba/abajo, y no hay una forma simple de calcular
	// colisiones exactas para un numero variable de ejes, asi que se prefirio margen
	// generoso de sobra antes que arriesgar texto cortado contra el borde del PNG.
	$size = 900;
	$cx = $size / 2;
	$cy = $size / 2;
	$maxR = $size * 0.28;

	$img = imagecreatetruecolor($size, $size);
	imagealphablending($img, true);
	$transparente = imagecolorallocatealpha($img, 0, 0, 0, 127);
	imagefill($img, 0, 0, $transparente);

	$gris = imagecolorallocatealpha($img, 91, 107, 124, 95);
	$azul = imagecolorallocate($img, 27, 76, 145);
	$tealRelleno = imagecolorallocatealpha($img, 62, 202, 181, 75);
	$textoGris = imagecolorallocate($img, 91, 107, 124);

	// Anillos de nivel 1..5 (grilla de fondo)
	for($nivel = 1; $nivel <= 5; $nivel++){
		$r = $maxR * ($nivel / 5);
		$puntos = [];
		for($i = 0; $i < $n; $i++){
			$angulo = deg2rad(-90 + ($i * 360 / $n));
			$puntos[] = $cx + $r * cos($angulo);
			$puntos[] = $cy + $r * sin($angulo);
		}
		imagepolygon($img, $puntos, $n, $gris);
	}

	// Ejes + nombre de competencia (o numero 1..N si no hay fuente TTF disponible)
	$calificacionesLista = array_values($calificaciones);
	for($i = 0; $i < $n; $i++){
		$angulo = deg2rad(-90 + ($i * 360 / $n));
		$cosA = cos($angulo);
		$sinA = sin($angulo);
		imageline($img, $cx, $cy, $cx + $maxR * $cosA, $cy + $maxR * $sinA, $gris);

		$xLabel = $cx + ($maxR + 16) * $cosA;
		$yLabel = $cy + ($maxR + 16) * $sinA;

		if($fuente){
			$nombre = $calificacionesLista[$i]->competencia_nombre ?? (string) ($i + 1);
			if(mb_strlen($nombre) > 22){
				$nombre = mb_substr($nombre, 0, 21).'…';
			}
			$bbox = imagettfbbox($tamanoFuente, 0, $fuente, $nombre);
			$anchoTexto = abs($bbox[2] - $bbox[0]);
			$altoTexto = abs($bbox[7] - $bbox[1]);

			if(abs($cosA) < 0.15){
				$x = $xLabel - $anchoTexto / 2;
			}elseif($cosA > 0){
				$x = $xLabel;
			}else{
				$x = $xLabel - $anchoTexto;
			}
			$y = $yLabel + $altoTexto / 2;

			imagettftext($img, $tamanoFuente, 0, (int) round($x), (int) round($y), $textoGris, $fuente, $nombre);
		}else{
			$etiqueta = (string) ($i + 1);
			$ancho = imagefontwidth(4) * strlen($etiqueta);
			$alto = imagefontheight(4);
			imagestring($img, 4, (int) round($xLabel - $ancho / 2), (int) round($yLabel - $alto / 2), $etiqueta, $textoGris);
		}
	}

	// Poligono con las calificaciones reales (1-5 por eje)
	$puntosDato = [];
	foreach($calificacionesLista as $i => $c){
		$angulo = deg2rad(-90 + ($i * 360 / $n));
		$r = $maxR * (((int) $c->calificacion) / 5);
		$puntosDato[] = $cx + $r * cos($angulo);
		$puntosDato[] = $cy + $r * sin($angulo);
	}
	imagefilledpolygon($img, $puntosDato, $n, $tealRelleno);
	imagepolygon($img, $puntosDato, $n, $azul);
	for($i = 0; $i < $n; $i++){
		imagefilledellipse($img, (int) round($puntosDato[$i * 2]), (int) round($puntosDato[$i * 2 + 1]), 10, 10, $azul);
	}

	imagealphablending($img, false);
	imagesavealpha($img, true);
	imagepng($img, $rutaSalida);
	imagedestroy($img);

	return true;
}

/** Sanitizador HTML basico (allowlist de tags de formato + remocion de atributos on* y
 * esquemas javascript:/data: en href/src) -- sin libreria externa (no hay HTML Purifier
 * vendorizado). Usado en cualquier campo editado con el editor rich-text (.rich-editor)
 * que termine mostrandose sin autenticacion (portal publico, correos). Extraido de
 * Vacantes::sanitizarHtmlBasico() (2026-07-15) a helper global para reutilizarse tambien
 * en Plantillas de correo (2026-07-16), evitando dos sanitizadores que puedan divergir. **/
function sanitizarHtmlBasico($html){
	$html = strip_tags($html, '<p><br><strong><b><em><i><u><ul><ol><li><a>');
	$html = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $html);
	$html = preg_replace('/(href)\s*=\s*(["\'])\s*(javascript|data):[^"\']*\2/i', '$1=$2#$2', $html);
	return trim($html);
}

/** Reemplaza tokens {nombre}, {vacante}, etc. en el asunto/cuerpo de una plantilla de
 * correo (Plantillas de correo, 2026-07-16). $valores usa las llaves ya con el formato
 * "{token}" para poder usar strtr() directo. **/
function reemplazarPlaceholders($texto, array $valores){
	return strtr($texto, $valores);
}

/** Envio de correo compartido por las 3 categorias de Plantillas de correo (recuperacion
 * de contrasena, postulacion recibida, cambio de etapa) -- mismo patron PHPMailer/SMTP ya
 * usado en Logins::enviarCorreoRecuperacion(), pero con el remitente (FromName) ahora
 * configurable desde el panel (configuracion_correo.remitente_nombre) en vez de fijo en
 * codigo -- pedido de Ytalo, 2026-07-16: el buzon tecnico (CORREO_ENVIO_CORREO) sigue
 * siendo el mismo, pero el nombre visible del remitente puede ser distinto. Errores de
 * envio se ignoran a proposito (no debe romper el flujo que dispara el correo), igual
 * que el criterio ya establecido en Logins.php. **/
function enviarCorreoPlantilla($destino, $asunto, $cuerpoHtml, $remitenteNombre, $cc = null){
	$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
	try {
		$mail->SMTPDebug = 0;
		$mail->isSMTP();
		$mail->Host = CORREO_ENVIO_SERVIDOR;
		$mail->SMTPAuth = true;
		$mail->CharSet = 'UTF-8';
		$mail->Username = CORREO_ENVIO_CORREO;
		$mail->Password = CORREO_ENVIO_PASS;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;

		$mail->setFrom(CORREO_ENVIO_CORREO, $remitenteNombre);
		$mail->addAddress($destino);
		if(!empty($cc)){
			$mail->addCC($cc);
		}
		$mail->isHTML(true);
		$mail->Subject = $asunto;
		$mail->Body = $cuerpoHtml;

		return $mail->send();
	} catch (\Exception $e) {
		return false;
	}
}

function listaFunciones(){
  $arr = get_defined_functions();
  return $arr["user"];
}

//para redireccionar la Pagina)
function redireccionar($pagina){
	header('location: '.RUTA_URL.$pagina);
}

function redireccionar2($pagina){
	echo '<script>
			window.location.href="'.RUTA_URL.$pagina.'";
		  </script>';
}

function redirect($url){
    if (headers_sent()){
      die('<script type="text/javascript">
      			window.location="'.RUTA_URL.$url.'";
      		</script‌​>');
    }else{
      header('Location: '.RUTA_URL. $url);
      die();
    }
}

function redirecionarinactividad($title = 'Estimado Colaborador', $text = 'Su sesion a caducado por inactividad X', $type = 'success', $minu_inac = MINU_INAC ,$controlador_metodo = CONTROLADOR_LOGOUT.'/'.METODO_LOGOUT){
  // 1000 = 1 segundo  //1 second = 1000 milliseconds.
    $milisegundos = $minu_inac*1000*60; //1segundo, 1 minuto , 10 minutos
    $segundos = $milisegundos / 1000; //1200
    // https://sweetalert.js.org/guides/#getting-started
    //"warning", "error", "success" and "info".

    echo '<script type="text/javascript">
            function redireccionarPagina() {
                        swal({   
                        title: "'.$title.'",   
                        text: "'.$text.'",   
                        type: "'.$type.'",   
                        showCancelButton: false,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "OK",     
                        closeOnConfirm: true,  
                        timer: 10000 }, 

                        function(isConfirm){   
                            if (isConfirm) {     
                              location.href="'.RUTA_URL.$controlador_metodo.'/";
                            } 
                        });        
            }
            setTimeout("redireccionarPagina()", "'.$milisegundos.'");
          </script>
          ';

}

function redireccionarSWEET_ALERT($title = 'Etimado ', $text = 'Su sesion ha caducado Y' , $type = 'success',$controlador_metodo = CONTROLADOR_LOGOUT.'/'.METODO_LOGOUT){
    echo '
        <script type="text/javascript">
        swal({   
                    title: "'.$title.'",   
                    text: "'.$text.'",   
                    type: "'.$type.'",   
                    showCancelButton: false,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "OK",     
                    closeOnConfirm: true,  
                    timer: 10000 }, 

                    function(isConfirm){   
                        if (isConfirm) {     
                          location.href="'.RUTA_URL.$controlador_metodo.'/";
                        } 
                    });  
        </script>';
}

function caducidadSESSION($minu_inac = MINU_INAC, $controlador_metodo = CONTROLADOR_LOGOUT.'/'.METODO_LOGOUT){

  $milisegundos = $minu_inac*1000*60; //MINUTOS MILISEGUNDOS HORA //1segundo, 1 minuto , 10 minutos
  $segundos = $milisegundos / 1000; 

  redirecionarinactividad('Estimado Colaborador','Su sesion a caducado por inactividad A', 'success', $minu_inac, $controlador_metodo);

  if(!isset($_SESSION['LoggedIn']) !="0"){

  redireccionarSWEET_ALERT('Estimado Colaborador','Su sesion a caducado por inactividad B', 'success',$controlador_metodo);

  }elseif(isset($_SESSION["autentificado"]) == "SI"){
    
    $fechaGuardada = $_SESSION["ultimoAcceso"];
    $ahora = date("Y-n-j H:i:s");
    //$ahora = date("Y-m-d H:i:s");
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
    //comparamos el tiempo transcurrido
    if($tiempo_transcurrido >= $segundos) {

    redireccionarSWEET_ALERT('Estimado Colaborador','Su sesion a caducado por inactividad C', 'success',$controlador_metodo);

    }else{

    $_SESSION["ultimoAcceso"] = $ahora;

    }

  }

}


function reniec($dni){
      
  $curl = curl_init();

  //NUESTRO SERVIDOR NO ACEPTABA LA FUNCION CURL, CON ESTA LÍNEA YA LO ACEPTA
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  //PASAMOS LOS PARAMETROS NECESARIOS PARA PODER CONSULTAR AL API
  curl_setopt_array($curl, array(
    CURLOPT_URL => RUTA_API_RENIEC.$dni,
  //  CURLOPT_URL => 'https://consulta.api-peru.com/api/dni/25793692',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING     => '',
    CURLOPT_MAXREDIRS    => 10,
    CURLOPT_TIMEOUT      => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST  => 'GET',
    CURLOPT_HTTPHEADER   => array(
      'Authorization: Bearer '.TOKE_API_RENIEC, //ESTE ES EL PIN QUE NOS DIERON LOS DEL API
      'Content-Type: application/json',
      'Cookie: __cfduid=d38b4627761adfae82b7d2ba30055cec81609520625'
    ),
  ));

  //DECLARAMOS UNA VARIABLE CON LA RESPUESTA, ESTA RESPUESTA VIENE COMO TEXTO, PUEDES LEERLO CON UN ECHO
  $pre_response = curl_exec($curl);

  //CERRAMOS EL CURL QUE INICIAMOS ARRIBA
  curl_close($curl);

  //NUESTRO SERVIDOR NO DETECTABA LAS Ñ, LO PONE CON CARACTERES UNICODE, CON ESTO LO CORREGÍ
  $response1 = str_replace('\u00d1', 'Ñ', $pre_response);
  $response2 = str_replace('\u00dc', 'Ü', $response1);
  $response3 = str_replace('\u00C1', 'A', $response2);
  $response4 = str_replace('\u00C9', 'E', $response3);
  $response5 = str_replace('\u00CD', 'I', $response4);
  $response6 = str_replace('\u00D3', 'O', $response5);
  $response7 = str_replace('\u00DA', 'U', $response6);
  $response8 = str_replace('\u00DC', 'Ü', $response7);
  $response  = str_replace('\u00D1', 'Ñ', $response8);

  //CON ESTO PUEDES CONVERTIR A ARRAY LA RESPUESTA
  $array_response = json_decode($response, true); 

  if($array_response['success']){

    foreach($array_response as $value){

      $reniec = [
      'success' => true,
      'numero'     => $dni, //$value['numero'],
      'nombre_completo'  => $value['nombre_completo'],
      'nombres'     => $value['nombres'],
      'apellido_paterno' => $value['apellido_paterno'],
      'apellido_materno' => $value['apellido_materno'],
      'sexo'        => $value['sexo'],
      'fecha_nacimiento' => $value['fecha_nacimiento'],
      'estado_civil' => $value['estado_civil'] ,      
      'direccion' => $value['direccion'] ,
      'direccion_completa' => $value['direccion_completa'] ,
      'departamento' => $value['departamento'] ,
      'provincia' => $value['provincia'] ,
      'distrito' => $value['distrito'] ,
      'codigo_verificacion' => $value['codigo_verificacion'] ,
      'ubigeo_reniec' => $value['ubigeo_reniec'] ,
      'ubigeo_depa' => $value['ubigeo'][0] ,
      'ubigeo_prov' => $value['ubigeo'][1] ,
      'ubigeo_dist' => $value['ubigeo'][2],
      'nombre_o_razon_social' => $value['nombre_o_razon_social'] ,
      'photo' => null, //$value['photo'] ,
      'fe_regi_cons' => date('Y-m-d H:i:s'),
      ];      
             
      }

  }else{

    $reniec = [
      'success' => false,
      'numero'     => $dni, //$value['numero'],
      'fe_regi_cons' => date('Y-m-d H:i:s'),
    ];

  }
    
//  return $response;
//  return $array_response['success'];
  return $reniec;

  }

  function email($CORREO_ENVIO, $correo_destino, $correo_copia, $asunto, $mensaje){

        include '../app/librerias/Mailer/class.phpmailer.php';
        include '../app/librerias/Mailer/class.smtp.php';
        include '../app/librerias/Mailer/PHPMailerAutoload.php';

        $mail = new PHPMailer(true);
        $mail->isSMTP();

        /*santa maria */
        $mail->SMTPDebug  = 0; 
        //$mail->Host       = 'mail.libreriasantamaria.com.pe';
        //$mail->Host       = 'mail.complementhrm.com';
        $mail->Host       = CORREO_ENVIO_SERVIDOR;
        $mail->Port       = 465; //465;
        //$mail->SMTPSecure = 'SMTP';
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "ssl";
        //$mail->Mailer = 'SMTP';
        //$mail->SMTPSecure = 'tls';
        //$mail->Username = 'comunicandonos@libreriasantamaria.com.pe';
        //$mail->Password = 'Tailoy2020';
        //$mail->Username = 'support@complementhrm.com';
        //$mail->Password = 'SupportComplementhrm2020';
        $mail->Username = CORREO_ENVIO_CORREO;
        $mail->Password = CORREO_ENVIO_PASS;
        $mail->From     = $CORREO_ENVIO; //'comunicandonos@tailoy.com.pe';//$CORREO_ENVIO;
        $mail->FromName = $CORREO_ENVIO; //'comunicandonos';

        $mail->AddAddress($correo_destino);
        $mail->AddCC($correo_copia);
      //  $mail->AddBCC('LGARCIA@TAILOY.COM.PE');
      //  $mail->AddBCC("yduenas@complementHRM.com");

        $mail->IsHTML(true);

        $mail->Subject = $asunto;//'Here is the subject LULU';
        $mail->Body    = $mensaje;//'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {

            echo 'Mensaje NO ENVIADO.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;

        } else {
          // echo 'Mensaje enviado';
            $_SESSION['resultado'] = "Enviado";
            echo 'Mensaje ENVIADO.';
          //  header('Location: http://localhost/hrc6/login.php');
          //  header('Location: '.RUTA_URL.'inicios/index/enviado');
        }
          /*FIN CORREO*/
  }

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require '../app/librerias/PHPMailer/vendor/autoload.php';
   // require 'vendor/autoload.php';

  function emailPHP8($CORREO_ENVIO, $correo_destino, $correo_copia, $asunto, $mensaje){

    $mail = new PHPMailer(true);

      try {
          $mail->SMTPDebug = 0;
          $mail->isSMTP();

          $mail->Host = CORREO_ENVIO_SERVIDOR;
          $mail->SMTPAuth = true;

          $mail->CharSet = 'UTF-8';

          $mail->Username = CORREO_ENVIO_CORREO;
          $mail->Password = CORREO_ENVIO_PASS;

          $mail->SMTPSecure = 'ssl';
          $mail->Port = 465;

          ## MENSAJE A ENVIAR

          $mail->setFrom($CORREO_ENVIO, "El remitente");
          $mail->addAddress($correo_destino);
          $mail->AddCC($correo_copia);
          //$mail->addAddress('xxxx@gmail.com');

          $mail->isHTML(true);
          $mail->Subject = $asunto;
          $mail->Body = $mensaje;

          $mail->send();

      } catch (Exception $exception) {
          echo 'Algo salio mal', $exception->getMessage();
      }

    }

    function mensajeactualizado($titulo, $texto, $script, $redirec){

      if($script == 'S') { echo '<script type="text/javascript">';}
      echo 'swal({   
                    title: "'.$titulo.'",   
                    text: "'.$texto.'",   
                    type: "success",   
                    showCancelButton: false,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "OK",     
                    closeOnConfirm: true,  
                    timer: 10000 
                  }'; 
      if($redirec <> ''){
       echo   ',  function(isConfirm){   
                        if (isConfirm) {     
                          location.href="'.RUTA_URL.$redirec.'";
                        } 
                    }';
                  }
       echo   ');';


      if($script == 'S') { echo '</script>'; }
    }

    function enviarSMS($celular, $mensaje) { 

        $hora_adelantada=date('H:i:s', strtotime(date('H:i:s').'+ 1 minute'));
        
        $curl = curl_init();

        //NUESTRO SERVIDOR NO ACEPTABA LA FUNCION CURL, CON ESTA LÍNEA YA LO ACEPTA
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        //PASAMOS LOS PARAMETROS NECESARIOS PARA PODER CONSULTAR AL API
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://sms.intico.com.pe:8181/api/sms_bulk',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
                                "api_key": "'.api_key_SMS.'",
                                "data" : {
                                 "messages" :[
                                                {
                                                "correlative" : 1,
                                                "phone" : "'.$celular.'" ,
                                                "message" : "'.$mensaje.'",
                                                "rut": "1"
                                                }],
                                                 "smslargo" : 0,
                                                 "hour" : "'.$hora_adelantada.'",
                                                 "delivery_date" : "'.date('d/m/Y').'",
                                                 "delivery_type" : 1,
                                                "user" : {
                                                 "user_id" : '.User_SMS.'
                                                 }
                                          }
                                }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Apikey: '.api_key_SMS.'',
            'User: '.User_SMS.''
          ),
        ));

        //DECLARAMOS UNA VARIABLE CON LA RESPUESTA, ESTA RESPUESTA VIENE COMO TEXTO, PUEDES LEERLO CON UN ECHO
        $response = curl_exec($curl);

        //CERRAMOS EL CURL QUE INICIAMOS ARRIBA
        curl_close($curl);

   //   echo $response;

        //CON ESTO PUEDES CONVERTIR A ARRAY LA RESPUESTA
        $array_response = json_decode($response, true); 

        return $array_response;
      // var_dump($array_response);die();

    }

    function librerias_CSS_JS($TIPO){
      
      require '../app/librerias/librerias.php';

        if($TIPO == 'CABECERA'){

          $librerias = $libreriasCABE;

        }elseif($TIPO == 'FOOTER'){

          $librerias = $libreriasFOTE;

        }

        foreach($librerias as $key => $v1){
          if(strpos($v1['url'], "http") !== false){
            $RUTA = '';
          }
          else{
            $RUTA = RUTA_URL;
          }
          if($v1['tipo'] == 'CSS' && $v1['estado'] == 'ACT'){
            echo "<link href='".$RUTA.$v1['url']."' rel='stylesheet' rel='stylesheet' type='text/css'>";
          }
          elseif($v1['tipo'] == 'JS' && $v1['estado'] == 'ACT'){
            echo "<script src='".$RUTA.$v1['url']."' ></script>";
          }
          
        }
        
    }

    function publics_JavaScripts($carpeta = 'public/js/'){

      $thefolderJS = RUTA_DOCUMENTO.$carpeta;

      if ($handlerJS = opendir($thefolderJS)) {
          while (false !== ($fileJS = readdir($handlerJS))) {
              if($fileJS != '.' && $fileJS != '..'){
                  echo "<script>";

                  $textJS = file_get_contents($thefolderJS.$fileJS);
            echo $textJS;
            echo "</script>";

                  }
          }
          closedir($handlerJS);
      }

    }

    function ver_Contenido_Carpeta($ruta = 'public/documentos', $extension_tipo = null){ //'app/modelos'

      $thefolder = RUTA_DOCUMENTO.$ruta;
      $archivos = [];
      if ($handler = opendir($thefolder)) {
        while (false !== ($file = readdir($handler))) {
          if($file != '.' && $file != '..'){
            if($extension_tipo == null){
              array_push($archivos ,$file);
            }elseif(pathinfo($file, PATHINFO_EXTENSION) == $extension_tipo ){
              array_push($archivos ,$file);
            }
          }
        }
        closedir($handler);
        return $archivos;
      }

    }

    function ver_Contenido_Documento($ruta = '/', $archivo = 'readme.md'){

      $text = file_get_contents(RUTA_DOCUMENTO.$ruta.$archivo);
      return $text;

    }

    function ver_Contenido_Documento_Linea($ruta, $archivo, $ini, $fin){

      $fp = fopen(RUTA_DOCUMENTO.$ruta.$archivo, "r") or die("Unable to open file!");

      $num = $ini ;

      
      while ($linea = fgets($fp)) {

        if($num >= $ini && $num <= $fin ){
      //  echo $num.' - '.$linea.'<br/>';
        $aux[] = $linea;

        $num ++;
        }

      }

      $salida = array_slice($aux, $ini-1, $fin);
 


      fclose($fp);
      return $salida;

    }

    function parametros(){
      /** clase[0] / metodo | funcion[1] / parametro 1[2] / parametro 2[3] / parametro n[n] **/
      if(empty($_GET['url'])){
          $url = [CONTROLADOR_ACTUAL,METODO_ACTUAL];
      }else{
        //  echo 'existe';
          $url = explode('/',$_GET['url']);
      }
      return $url;
    }

    function parametroEspecifico($num = 1){
      /** clase[0] / metodo | funcion[1] / parametro 1[2] / parametro 2[3] / parametro n[n] **/
      if(empty($_GET['url'])){
          $url = [CONTROLADOR_ACTUAL,METODO_ACTUAL];
      }else{
        //  echo 'existe';
          $url = explode('/',$_GET['url']);

          if(empty($url[$num])){
            $url[$num] = 'index'; //null;
          }

      }
      return $url[$num];
    }
   
    function pagina404($clase, $metodo = 'index'){

      //$params = parametros($clase,$metodo);
      if(!method_exists($clase,$metodo)){
        header('location: '.RUTA_URL.CONTROLADOR_ERROR.'/'.METODO_ERROR.'/');
        echo 'no existe'; die();
      }else{
      //  header('location: '.RUTA_URL.'inc/error/');
      //  echo 'existe';die();
      }

      /**
      $params = parametros();
      if(!method_exists(get_class($this),$params[1])){
        echo 'no existe'; die();
      }else{
        echo 'existe';die();
      }
      **/


    }

    function filter_array($array,$campo,$term){
        $matches = array();
        foreach($array as $a){
            if($a[$campo] == $term)
                $matches[]=$a;
        }
        return $matches;
    }

    function buscarJSON($nombre_archivo ,$ruta = 'migracionDB.json'){

     // $edit_id = $id;
      //get json data
    //  $edit_id = 0 ;
      $data = file_get_contents(RUTA_DOCUMENTO.$ruta);
      $data_array = json_decode($data, true);
      //var_dump($data_array);die();
      
      foreach ($data_array as $key => $dato) :

        if($dato['nombre_archivo'] == $nombre_archivo){
          $resultado = $dato ;
        }else{
          $resultado['nombre_archivo'] = 'Migracion no ejecutada';
        }

      endforeach;

      // var_dump($resultado);die();
      /*
      if(empty($data_array[$edit_id])){
        
        $row = 'No ejecutado';
      
      }elseif($data_array[$edit_id]["nombre_archivo"] == $nombre_archivo){
      // $row = 'con datos';
        $row = $data_array[$edit_id];
      //$row = $data_array[1];
      //var_dump($row);die();
      }
      */
      
      return $resultado;

    }

    function diahora($tipo = 'DH'){
      $tipo = strtoupper($tipo);
      if($tipo == 'DH'){
        $tiempo = date('Y-m-d H:i:s');
      }elseif($tipo == 'D'){
        $tiempo = date('Y-m-d');
      }elseif($tipo == 'H'){
        $tiempo = date('H:i:s');
      }else{
        $tiempo = date('Y-m-d H:i:s');
      }

      return $tiempo;
    }

    function alertsuccess($tipo = 'success', $mensaje = 'mensaje', $mensaje2 = ''){

      $esultado = '<div class="alert alert-'.$tipo.'" role="alert">';
      $esultado .='Realizado con Exito la Migracion del archivo '.$mensaje2 ;
      $esultado .='</div>';

      echo $esultado;
    }

    function filter_arrayXX($array, $campo ,$term){
      $matches = array();
      foreach($array as $a){
        if($a->$campo == $term)
          $matches[]=$a;
      }
      return $matches;
    }
    //  $new_array = filter_array($menu,'CO_MODULO','CLI');


    function quitar_duplicados_menu($datos){
        $modulos = array();
        foreach ($datos  as $co_modu):

             $modulo =  [ 
                    'CO_MODULO' => $co_modu->CO_MODULO,
                    'DE_MODULO' => $co_modu->DE_MODULO,
                    'HREF' => $co_modu->HREF,
                    'ICONO' => $co_modu->ICONO,
                    ];  
                
        array_push($modulos ,$modulo);

        endforeach;

       $modulos = array_unique($modulos, SORT_REGULAR);
       return $modulos;
    }

    /** RBAC: helpers de sesion (modulo Perfiles/Permisos, seccion 2 del CLAUDE.md) **/

    function estaAutenticado(){
      return isset($_SESSION['usuario_id']);
    }

    function tienePermiso($codigo_permiso){
      return estaAutenticado() && in_array($codigo_permiso, $_SESSION['permisos'] ?? [], true);
    }

    function requiereLogin(){
      if(!estaAutenticado()){
        redirect(CONTROLADOR_LOGOUT.'/index');
      }
    }

    function requierePermiso($codigo_permiso){
      requiereLogin();
      if(!tienePermiso($codigo_permiso)){
        redirect(CONTROLADOR_ERROR.'/'.METODO_ERROR);
      }
    }

    function quitar_duplicados_menu_sub($datos){
      $modulos_sub = array();
      foreach ($datos  as $co_modu_sub):

           $modulo_sub = [ 
                  'CO_MODULO' => $co_modu_sub->CO_MODULO,
                  'CO_MODULO_SUB' => $co_modu_sub->CO_MODULO_SUB,
                  'DE_MODULO_SUB' => $co_modu_sub->DE_MODULO_SUB,
                  'HREF' => $co_modu_sub->HREF,
                  ];  

      array_push($modulos_sub ,$modulo_sub);

      endforeach;

     $modulos_sub = array_unique($modulos_sub, SORT_REGULAR);
  // var_dump($modulos_sub);die();
     return $modulos_sub;

    }

?>
