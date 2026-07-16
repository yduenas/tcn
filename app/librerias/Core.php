<?php
/*
Mapear la URL Ingresada en el navegador 
1.- Controlador
2.- Metodo/Funcion
3.- Parametro
Ejemplo: /articulo/actualizar/4
*/
// error_reporting(0);

	class Core{
		protected $controladorActual = CONTROLADOR_ACTUAL; //'Inicios'; // Logins
		protected $metodoActual = METODO_ACTUAL; //'index';
		protected $parametros = [];

		//constructor
		public function __construct(){
			//print_r($this->getUrl());
			$url = $this->getUrl();

			//Buscar en controladores si el controlador existe
			if (isset($url[0]) && file_exists('../app/controladores/' .ucwords($url[0]).'.php')){
				//si existe se setea como controlador por defecto
				$this->controladorActual = ucwords($url[0]);
				//unset indice
				unset($url[0]);
			}

			//requerir el controlador
			require_once '../app/controladores/' . $this->controladorActual . '.php';
			$this->controladorActual = new $this->controladorActual;

			//chequear/verificar la segunda parte de la url que seria el metodo
			if (isset($url[1])) {
				if (method_exists($this->controladorActual, $url[1])) {
					//hequqeams el metodo
					$this->metodoActual = $url[1];
					//unset indice
					unset($url[1]);
				}
			}
			
			//para probar traer metodo
			//echo $this->metodoActual;

			//obtener los parametros
			$this->parametros = $url ? array_values($url) : [];

			//Lllamar callback con parametros array
			call_user_func_array([$this->controladorActual, $this->metodoActual], $this->parametros);
		}

		public function getUrl(){
			//echo $_GET['url'];

			if(isset($_GET['url'])){
				$url = rtrim($_GET['url'], '/');
				$url = filter_var($url, FILTER_SANITIZE_URL);
				$url = explode('/', $url);
				return $url;

			}

			return [];

		}

	}

?>