<?php
	session_start();

	class Auditorias extends Controlador{

		public function __construct(){

			requiereLogin();
			requierePermiso('ver_auditoria');

			$this->auditoriaModelo = $this->modelo('Auditoria');

		}

		public function index(){

			$datos = [
				'registros' => $this->auditoriaModelo->listar(),
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('auditoria/listado', $datos);
			$this->vista('inc/foot', $datos);

		}

	}

?>
