<?php
	session_start();

	class Configuraciones extends Controlador{

		public function __construct(){

			session_regenerate_id();
			$_SESSION['CO_CORP'] = '01';
			//var_dump($_SESSION);die();
			//unset($_SESSION);
			//session_unset();
			//session_destroy();
			//var_dump($_SESSION);die();

			$this->dato = $dato = ['Lulu','Ytalo','Martin'];
			$this->clase = $clase = get_class($this);
			$this->metodo = $metodo = parametroEspecifico(1);
			pagina404($clase,$metodo);
			$this->estructuraModelo = $this->modelo('EstructuraDB');
			$this->loginsModelo = $this->modelo('Usuario');			
		
		}

		public function index(){

			//redireccionar('configuraciones/listaMetodos');die();
			//echo 'configuraciones index';die();

			

			$datos=[
			
			];
					
			$this->vista('inc/head',$datos);			
			$this->vista('configuracion/inicio',$datos);
			$this->vista('inc/foot',$datos);

		}

		public function lecturaArchivo(){

			$archivos = ver_Contenido_Carpeta(null,'md');

			$datos=[
				'archivos' => $archivos
			];

			$this->vista('inc/head',$datos);
			$this->vista('configuracion/listaarchivos',$datos);
			$this->vista('inc/foot',$datos);
			die();
			echo '<h1>GUIA</h1>';
			echo '<table class="table table-striped table-bordered">';
			echo '<thead>';
			echo '<th scope="col">#</th><th scope="col">nombre</th><th scope="col">contenido</th>';	
			echo '</thead>';
			echo '<tbody>';
			$nu = 1 ;
			$archivos = ver_Contenido_Carpeta(null,'md');
			foreach($archivos as $archivo):

				echo '<tr>';
				echo '<td>';
				echo $nu;
				//echo '<br>';
				echo '</td>';
				echo '<td>';
				echo $archivo;
				//echo '<br>';
				echo '</td>';
				echo '<td>';
				echo $info = ver_Contenido_Documento('/',$archivo);
				echo '</td>';
				echo '</tr>';
				$nu ++ ;
			endforeach;
			echo '</tr>';
			echo '</tbody>';
			echo '</table>';			

			$this->vista('inc/foot',$datos);

		}

		public function iconos(){
			$datos=[				

			];
			$this->vista('inc/head',$datos);
			$this->vista('inc/iconos',$datos);
			$this->vista('inc/foot',$datos);
		}

		public function tablas(){
			$datos=[				

			];
			$this->vista('inc/head',$datos);
			$this->vista('inc/tables',$datos);
			$this->vista('inc/foot',$datos);
		}

		public function migracion(){			
		
			$archivos = ver_Contenido_Carpeta('app/migraciones','sql');
		//	var_dump($archivos);die();

			$data = file_get_contents(RUTA_DOCUMENTO.'app/migraciones/migracionDB.json');
            $data_array = json_decode($data, true);


			$datos = [
				'archivos' => $archivos,
				'migracionDBJSON' => $data_array
			];

			$this->vista('inc/head',$datos);
			$this->vista('configuracion/tablamigraciones',$datos);
			$this->vista('inc/foot',$datos);

		}

		public function migracionEjecutar(){
			
			$archivos = ver_Contenido_Carpeta('app/migraciones','sql');
			//var_dump($archivos);die();
			if(parametroEspecifico(2) =='index'){
				echo 'Esta queriendo ejecutar una migracion inexistente'; ;
			
				$datos = [
				
				];
				$this->vista('inc/head',$datos);
				redireccionarSWEET_ALERT('Etimado ', 'Esta querinedo ejecutar una migracion inexistente' , 'success','configuraciones/index');
				$this->vista('inc/foot',$datos);
			}else{

				foreach ($archivos as $archivo) {
			//	echo base64_encode($archivo);
					if(parametroEspecifico(2) == base64_encode($archivo)){
						
						echo 'Ejecucion en proceso';
						echo '<br>';

						echo base64_decode(parametroEspecifico(2));
						echo '<br>';

						$archivo = base64_decode(parametroEspecifico(2));			
						$contenido = ver_Contenido_Documento('app/migraciones/',$archivo);
						// echo $contenido;
						if($this->estructuraModelo->ejecucionArchivoSQL($contenido)){
							echo $archivo.' - Ejecucion del archivo exitosa';
							echo '<br>';


						$data = file_get_contents(RUTA_DOCUMENTO.'app/migraciones/migracionDB.json');
						$data = json_decode($data, true);
						$add_arr = array(
						'nombre_archivo' => $archivo,
						'fecha' => date('Y-m-d H:i:s')
						);
						$data[] = $add_arr;
						 
						$data = json_encode($data, JSON_PRETTY_PRINT);
						file_put_contents(RUTA_DOCUMENTO.'app/migraciones/migracionDB.json', $data);
						//header('location: index.php');

						$resultado = 'Migracion Exitosa';
			            $_SESSION['resultado'] = $resultado;
			            $_SESSION['archivo'] = $archivo;

			            redireccionar2('configuraciones/migracion');

						}else{
							echo $archivo.' - Error en la ejecucion del archivo';
							echo '<br>';
							$resultado = 'Migracion No Ejecutada';
				            $_SESSION['resultado'] = $resultado;
				            $_SESSION['archivo'] = $archivo;
						}

					}else{
					//	echo $archivo. ' no ejcutado';
					//	echo '<br>';
					}
				}

			}
		
		}

		public function error(){

			$datos = [

			];

			$this->vista('inc/head',$datos);
			$this->vista('inc/error',$datos);
			$this->vista('inc/foot',$datos);
			
		}

		public function listaTablas(){	

			$baseDatos = $this->estructuraModelo->validarBD();
			//var_dump($baseDatos);die();
			$tablas = $this->estructuraModelo->mostrarTablas();

			$datos=[
				'baseDatos' => $baseDatos,
				'tablas' => $tablas
			];
			//var_dump($datos);die();

			$this->vista('inc/head',$datos);
			$this->vista('configuracion/listatablas',$datos);
			$this->vista('inc/foot',$datos);
			
		}

		public function leerJSON(){

			$jsonfile = file_get_contents(RUTA_DOCUMENTO."public/documentos/guia.json");
			//var_dump($data);die();

			$jsonfile =str_replace('},]',"}]",$jsonfile);
			//var_dump($jsonfile);die();

			$jsonencode = utf8_encode($jsonfile);
			//var_dump($jsonencode);die();
			$documentos = json_decode($jsonencode);
			// echo $documentos;die();
			// var_dump($documentos);die();
			foreach ($documentos as $documento) :
			    echo '<pre>';
			    echo $documento->tema;
			    echo '</pre>';
			    echo '<pre>';
			    echo $documento->descripcion;
			    echo '</pre>';
			endforeach;

		}

		public function listaHelpers(){
			/*
			$helpers = listaFunciones();
			echo('<pre>');
			//var_dump($helpers);
			print_r($helpers);
			echo('<pre>');
			*/
			$helpers = listaFunciones();
			$datos = [
				'helpers' => $helpers
			];
			// var_dump($datos);die();
			$this->vista('inc/head',$datos);
			$this->vista('configuracion/listahelpers',$datos);
			$this->vista('inc/foot',$datos);



		}

		public function sweetAlertMessage(){

			$datos=[

			];
			$this->vista('inc/head',$datos);
			//echo 'hola';
		//	redireccionarSWEET_ALERT('Etimado ', 'Su sesion ha caducado Y' , 'success', CONTROLADOR_LOGOUT.'/'.METODO_LOGOUT);
			$title = 'hola';
			$text = 'hola2';
			$type = 'success';
			$controlador_metodo  = 'configuraciones/sweetAlertMessage';
			    echo '
			    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		        <script type="text/javascript">
		        swal("A wild Pikachu appeared! What do you want to do?", {
		        //	title: "'.$title.'",   
                //  text: "'.$text.'",   

					buttons: {
					    cancel: "Run away!",
					    catch: {
					      text: "Throw Pokéball!",
					      value: "catch",
					    },
					defeat: true,
				  },
				})
				.then((value) => {
				  switch (value) {
				 
				    case "defeat":
				      swal("Pikachu fainted! You gained 500 XP!");
				      break;
				 
				    case "catch":
				      swal("Gotcha!", "Pikachu was caught!", "success");
				      break;
				 
				    default:
				      swal("Got away safely!");
				  }
				});

		        /*
		        swal({   
                    title: "'.$title.'",   
                    text: "'.$text.'",   
                    type: "'.$type.'",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "OK",     
                    closeOnConfirm: true,  
                    timer: 10000 },

                    

                    function(isConfirm){   
                        if (isConfirm) {     
                     //     location.href="'.RUTA_URL.'/'.$controlador_metodo.'/"; 
                       swal("Hello world!");
                       alert("ggg");
 					  

                        } 
                        
                    });  

                    */

        		</script>';
			
			$this->vista('inc/foot',$datos);
		}

		public function listaMetodos(){

			$datos=[

			];

			$datos = get_class_methods(get_class($this));
			
			$this->vista('inc/head',$datos);
			$this->vista('configuracion/listametodos',$datos);
			$this->vista('inc/foot',$datos);

		}

	
	}

?>
