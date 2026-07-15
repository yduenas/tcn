<?php
	session_start();

	class Pruebas extends Controlador{

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
			echo 'hola';
			/**
			 * Html2Pdf Library - example
			 *
			 * HTML => PDF converter
			 * distributed under the OSL-3.0 License
			 *
			 * @package   Html2pdf
			 * @author    Laurent MINGUET <webmaster@html2pdf.fr>
			 * @copyright 2023 Laurent MINGUET
			 */
			require_once '../app/librerias/html2pdf/html2pdf.class.php';

			ob_start();

			//$this->vista('beneficios/cpresentacion'); //,$datos);
			echo 'soy una contancia';

			$html = ob_get_clean();

 			$pdf = new HTML2PDF('P','A4','fr','UTF-8');
 			$pdf->writeHTML($html);
			//$pdf->SetProtection();
			$permissions = array('print', 'copy');
			$userPass = 'mi_password';
			$ownerPass = null;
			$mode = 0;
			$pubkeys = null;
			$pdf->pdf->SetProtection($permissions, $userPass, $ownerPass, $mode, $pubkeys);
			//$pdf->pdf->SetProtection(array('print', 'copy'), 'mi_password', null, 0, null);

 			ob_end_clean();
 			$pdf->output('cpresentacion.pdf');


		}

	}

?>
