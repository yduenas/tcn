<?php
	session_start();

	class Postulantes extends Controlador{

		public function __construct(){

			requiereLogin();

			$this->candidatoModelo = $this->modelo('Candidato');

		}

		/** Una postulación = una fila (Candidato, DNI, Correo, Teléfono, Empresa, Puesto, Estado, fecha del
		 * último cambio de estado) -- seccion 1.4, pedido de Ytalo 2026-07-14: "ver la relación de registros
		 * y a qué vacantes postularon y el estado"; 2026-07-15: Empresa/Puesto/DNI/fecha como columnas reales
		 * del DataTable en vez de texto agrupado. Mismo permiso que el pipeline por vacante (ver_postulantes),
		 * sin restricción por seleccionador -- consistente con Postulaciones::vacante(), que tampoco la tiene;
		 * solo mover el estado (Postulaciones::moverEstado()) está restringido al dueño de la vacante. **/
		public function index(){

			requierePermiso('ver_postulantes');

			$datos = [
				'filas' => $this->candidatoModelo->listarConPostulaciones(),
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('postulantes/listado', $datos);
			$this->vista('inc/foot', $datos);

		}

	}

?>
