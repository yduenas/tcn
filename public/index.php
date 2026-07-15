<?php
	//Cargamos el iniciador.php de la carpeta app
	require_once '../app/iniciador.php';
	//manejo de errores
	if(STATUS_PROYECTO == 'PRD'){
		error_reporting(0);
	}
	//Insanciamos la clase controlador
	$iniciar = new Core;
?>