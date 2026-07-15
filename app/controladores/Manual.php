<?php
	session_start();

	/**
	 * Manual de usuario: describe el proceso de la plataforma para cada perfil.
	 * index() es para el staff/empresa ya logueado: las secciones que se muestran
	 * dependen de los permisos del usuario (nunca de comparar el nombre del perfil),
	 * asi que funciona igual para los 3 perfiles de fabrica y para cualquier perfil
	 * personalizado que el admin cree despues. postulante() es la guia publica del
	 * portal (seccion 1.1 del CLAUDE.md), sin necesidad de iniciar sesion.
	 */
	class Manual extends Controlador{

		public function index(){

			requiereLogin();

			$datos = [];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('manual/index', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function postulante(){

			$datos = [];

			$this->vista('inc/head', $datos);
			$this->vista('portal/nav', $datos);
			$this->vista('manual/postulante', $datos);
			$this->vista('inc/foot', $datos);

		}

	}

?>
