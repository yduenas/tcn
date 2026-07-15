<?php
//session_start();
/*
header("Cache-control: private");
header("Cache-control: no-cache, must-revalidate");
header("Pragma: no-cache");
*/
//$tiempo = 1000*60*20; //1segundo, 1 minuto , 10 minutos
$milisegundos = MINU_INAC*1000*60; //MINUTOS MILISEGUNDOS HORA //1segundo, 1 minuto , 10 minutos
$segundos = $milisegundos / 1000; //1200 SEGUNDOS = 20 MINUTOS


echo '<script type="text/javascript">
        function redireccionarPagina() {
                    swal({
                    title: "Estimado Colaborador ",
                    text: "Su sesion a caducado por inactividad 1",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "OK",
                    closeOnConfirm: true,
                    timer: 10000 },

                    function(isConfirm){
                        if (isConfirm) {
                          location.href="'.RUTA_URL.CONTROLADOR_LOGOUT.'/'.METODO_LOGOUT.'/";
                        }
                    });
        }
        setTimeout("redireccionarPagina()", "'.$milisegundos.'");
      </script>
      ';

      //window.location = "'.RUTA_URL.'login/index/";

if(!isset($_SESSION['LoggedIn']) !="0") {
   // echo 'aca '.$_SESSION['LoggedIn']; die();
  //  header('Location: '.RUTA_URL.'logins/logout/');
    echo '
        <script type="text/javascript">
     /*   jQuery(function(){ */

        swal({
                    title: "Estimado Colaborador ",
                    text: "Su sesion a caducado por inactividad 2",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "OK",
                    closeOnConfirm: true,
                    timer: 10000 },

                    function(isConfirm){
                        if (isConfirm) {
                          location.href="'.RUTA_URL.CONTROLADOR_LOGOUT.'/'.METODO_LOGOUT.'/";
                        }
                    });
   /*     }); */
        </script>';

}elseif(isset($_SESSION["autentificado"]) == "SI") {
    //echo 'aqui'; die();
    //sino, calculamos el tiempo transcurrido
    $fechaGuardada = $_SESSION["ultimoAcceso"];
    $ahora = date("Y-n-j H:i:s");
    //$ahora = date("Y-m-d H:i:s");
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
    //comparamos el tiempo transcurrido
    if($tiempo_transcurrido >= $segundos) {
    //si pasaron 1200 segundos = 20 minutos o más
    echo '
        <script type="text/javascript">
        jQuery(function(){

        swal({
                    title: "Estimado Colaborador ",
                    text: "Su sesion a caducado por inactividad 3",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "OK",
                    closeOnConfirm: true,
                    timer: 10000 },

                    function(isConfirm){
                        if (isConfirm) {
                          location.href="'.RUTA_URL.CONTROLADOR_LOGOUT.'/'.METODO_LOGOUT.'/";
                        }
                    });
    	});
    	</script>';

 //   session_destroy(); // destruyo la sesión

   //header("Location: assets/includes/cerrar_sesion3.php"); //envío al usuario a la pag. de autenticación
   //sino, actualizo la fecha de la sesión
    }else{

    $_SESSION["ultimoAcceso"] = $ahora;

   }

}

?>
