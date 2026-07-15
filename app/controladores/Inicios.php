<?php
	session_start();

	class Inicios extends Controlador{

		public function __construct(){

			$this->dato = $dato = ['Lulu','Ytalo','Martin'];			
			$this->clase = $clase = get_class($this);
			$this->metodo = $metodo = parametroEspecifico(1);
			$this->loginsModelo = $this->modelo('Usuario');
			
			$this->menuModelo = $this->modelo('Menu');
			$this->menu = $menu = $this->menuModelo->menu2();
			//$this->menu = $menu = $_SESSION['menu'];
			
			pagina404($clase,$metodo);
			
		}

		public function index(){

		//	$corporaciones = $this->loginsModelo->corporaciones();
		//	var_dump($this->dato);die();
		//	echo diaHora();die();

			$datos=[
			
			];
					
		//	var_dump($datos['listaT_TIPO_DOC_IDEN']);die();
			$this->vista('inc/head',$datos);
			$this->vista('inicio/bienvenido',$datos);
		
			$this->vista('inc/foot',$datos);

		}		

		public function error(){

			$datos=[

			];

			$this->vista('inc/head',$datos);
			$this->vista('inc/error',$datos);
			$this->vista('inc/foot',$datos);

			
		}

		public function listaFunciones(){

			$métodos_clase = get_class_methods(get_class($this));
			// o
			// $métodos_clase = get_class_methods('Inicios');
			// o
			//$métodos_clase = get_class_methods(new miclase());

			foreach ($métodos_clase as $nombre_método) {
			    echo "$nombre_método";
			    echo '<br>';
			}
		}

		public function menu(){

	

			$datos=[

			];

			$this->vista('inc/head',$datos);
			$this->vista('inc/menu',$this->menu);
			$this->vista('inc/foot',$datos);

		}
	
	}

?>
