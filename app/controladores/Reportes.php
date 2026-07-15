<?php
	session_start();

	class Reportes extends Controlador{

		public function __construct(){

			requiereLogin();

			$this->vacanteModelo = $this->modelo('Vacante');
			$this->estadoPostulacionModelo = $this->modelo('EstadoPostulacion');
			$this->reporteModelo = $this->modelo('Reporte');

		}

		/** Reporte de publicaciones (vacantes) y su estatus, con conteo de postulantes por etapa del embudo
		 * (seccion 1.4, pedido de Ytalo 2026-07-14). Empresa solo ve sus propias vacantes -- ver_reportes_globales
		 * tambien lo tiene el perfil Empresa (migracion 004), pero este reporte cruza todas las empresas, asi que
		 * se filtra por empresa_id de sesion para no exponer datos de otros clientes. **/
		public function index(){

			requierePermiso('ver_reportes_globales');

			$estados = $this->estadoPostulacionModelo->listar();
			$vacantes = $this->vacanteModelo->listar();

			if($_SESSION['perfil_nombre'] === 'Empresa'){
				$vacantes = array_values(array_filter($vacantes, function($v){
					return $v->empresa_id == $_SESSION['empresa_id'];
				}));
			}

			$conteos = $this->reporteModelo->conteoPostulantesPorVacanteYEstado();
			$conteosPorVacante = [];
			foreach($conteos as $fila){
				$conteosPorVacante[$fila->vacante_id][$fila->estado_codigo] = (int) $fila->cantidad;
			}

			foreach($vacantes as $vacante){
				$vacante->conteo_total = 0;
				$vacante->conteo_por_estado = [];
				foreach($estados as $estado){
					$cantidad = $conteosPorVacante[$vacante->id][$estado->codigo] ?? 0;
					$vacante->conteo_por_estado[$estado->codigo] = $cantidad;
					$vacante->conteo_total += $cantidad;
				}
			}

			$datos = [
				'vacantes' => $vacantes,
				'estados' => $estados,
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('reportes/publicaciones', $datos);
			$this->vista('inc/foot', $datos);

		}

	}

?>
