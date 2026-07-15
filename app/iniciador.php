<?php
//Cargamos librerias
require_once 'config/configurar.php';
require_once 'helpers/url_helper.php';
//require_once 'librerias/Base.php';
//require_once 'librerias/Controlador.php';
//require_once 'librerias/Core.php';
//Autoload php
spl_autoload_register(function($nombreClase){
	// __DIR__ (no una ruta relativa a CWD) para que la comprobacion de existencia
	// coincida exactamente con lo que require_once terminaria resolviendo
	$archivo = __DIR__.'/librerias/'.$nombreClase.'.php';
	if(file_exists($archivo)){
		require_once $archivo;
	}
	// si no existe, se deja pasar para que otro autoloader registrado (p.ej. librerias con namespace) lo resuelva
});

?>