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
		 * del DataTable en vez de texto agrupado. Mismo permiso que el pipeline por vacante (ver_postulantes).
		 * 2026-07-17: ahora SÍ se scopea (antes mostraba TODAS las postulaciones de TODAS las empresas sin
		 * restricción alguna -- gap real que se vuelve critico en cuanto Empresa tambien tiene este permiso,
		 * ya que este listado es global por diseño, no "por vacante" como Postulaciones::vacante()). **/
		public function index(){

			requierePermiso('ver_postulantes');

			$empresa_id = $_SESSION['perfil_nombre'] === 'Empresa' ? $_SESSION['empresa_id'] : null;
			$seleccionador_id = $_SESSION['perfil_nombre'] === 'Seleccionador' ? $_SESSION['usuario_id'] : null;

			$datos = [
				'filas' => $this->candidatoModelo->listarConPostulaciones($empresa_id, $seleccionador_id),
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('postulantes/listado', $datos);
			$this->vista('inc/foot', $datos);

		}

	}

?>
