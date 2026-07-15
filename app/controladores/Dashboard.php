<?php
	session_start();

	class Dashboard extends Controlador{

		public function __construct(){

			requiereLogin();

			$this->dashboardModelo = $this->modelo('Metricas');
			$this->postulacionModelo = $this->modelo('Postulacion');

		}

		public function index(){

			if($_SESSION['perfil_nombre'] === 'Empresa'){
				return $this->empresa();
			}

			$datos = [
				'kpis' => $this->dashboardModelo->kpisGlobales(),
				'postulacionesPorEstado' => $this->dashboardModelo->postulacionesPorEstado(),
				'vacantesRecientes' => $this->dashboardModelo->vacantesRecientes(),
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('dashboard/general', $datos);
			$this->vista('inc/foot', $datos);

		}

		/**
		 * Dashboard de la empresa cliente (seccion 1.3): avance por vacante e historico.
		 * Rediseño 2026-07-14: la Empresa ya no depende de un envío explícito de terna --
		 * ve directamente a los candidatos que llegaron a "Terna final"/"Contratado" en
		 * el funnel de cada una de sus vacantes (Postulacion::listarVisiblesParaEmpresa()),
		 * agrupados aquí por vacante para mantener la misma presentación de antes.
		 **/
		private function empresa(){

			$empresa_id = $_SESSION['empresa_id'];

			$candidatosVisibles = $this->postulacionModelo->listarVisiblesParaEmpresa($empresa_id);
			$grupos = [];
			foreach($candidatosVisibles as $c){
				if(!isset($grupos[$c->vacante_id])){
					$grupos[$c->vacante_id] = (object) ['vacante_titulo' => $c->vacante_titulo, 'candidatos' => []];
				}
				$grupos[$c->vacante_id]->candidatos[] = $c;
			}

			$datos = [
				'kpis' => $this->dashboardModelo->kpisEmpresa($empresa_id),
				'vacantes' => $this->dashboardModelo->vacantesConAvance($empresa_id),
				'cerradas' => $this->dashboardModelo->vacantesCerradas($empresa_id),
				'grupos' => array_values($grupos),
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('dashboard/empresa', $datos);
			$this->vista('inc/foot', $datos);

		}

	}

?>
