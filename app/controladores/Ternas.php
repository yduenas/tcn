<?php
	session_start();

	class Ternas extends Controlador{

		public function __construct(){

			requiereLogin();

			$this->postulacionModelo = $this->modelo('Postulacion');
			$this->postulacionEvaluacionModelo = $this->modelo('PostulacionEvaluacion');
			$this->entrevistaModelo = $this->modelo('Entrevista');
			$this->vacanteModelo = $this->modelo('Vacante');

		}

		/**
		 * El router (Core.php) cae en index() como fallback cuando la URL no
		 * coincide con ningun metodo real del controlador -- sin esto, cualquier
		 * link viejo a "ternas/comparador" (pantalla eliminada en el rediseño
		 * 2026-07-14) mostraria un Warning de PHP en vez de una redireccion limpia.
		 **/
		public function index(){
			redirect('vacantes/index');
		}

		/** Reporte PDF del candidato (seccion 4): visible para quien tiene exportar_pdf Y es dueño
		 * de la vacante (Administrador/Seleccionador propio/Empresa propia -- 2026-07-17). Fallback
		 * para Empresa SIN exportar_pdf: solo ve una vez que el candidato llega a Terna final/
		 * Contratado (rediseño 2026-07-14, ver Postulacion::visibleParaEmpresa()). */
		public function reporte($postulacion_id){

			$postulacion = $this->postulacionModelo->obtenerCompleta($postulacion_id);
			if(!$postulacion){
				redirect('dashboard/index');
			}

			if(tienePermiso('exportar_pdf')){
				requiereDuenoDeVacante($this->vacanteModelo->obtener($postulacion->vacante_id));
			}elseif($_SESSION['perfil_nombre'] === 'Empresa'){
				$autorizacion = $this->postulacionModelo->visibleParaEmpresa($postulacion_id);
				if(!$autorizacion || $autorizacion->empresa_id != $_SESSION['empresa_id']){
					redirect('inicios/error');
				}
			}else{
				redirect(CONTROLADOR_ERROR.'/'.METODO_ERROR);
			}

			$evaluaciones = $this->postulacionEvaluacionModelo->listarPorPostulacion($postulacion_id);
			$entrevista = $this->entrevistaModelo->obtenerPorPostulacion($postulacion_id);
			$calificacionesEntrevista = $entrevista ? $this->entrevistaModelo->listarCalificaciones($entrevista->id) : [];
			// Competencias que la vacante marco como obligatorias (seccion 1.2), para el promedio
			// aparte de solo-obligatorias en el reporte -- pedido de Ytalo, 2026-07-15.
			$obligatorias = array_column($this->vacanteModelo->competenciasAsignadas($postulacion->vacante_id), 'competencia_id');

			// Radar de las competencias de la entrevista (2026-07-16) -- a diferencia del radar de
			// 10 competencias fijas de la evaluacion SJT (ese sigue como barras, html2pdf no soporta
			// graficos reales), esta cantidad varia segun cuantas competencias tenga la vacante, asi
			// que se genera con GD como PNG a un archivo temporal (ver generarRadarCompetencias()) y
			// se borra automaticamente al terminar el request, incluso si HTML2PDF::output() corta la
			// ejecucion antes de llegar a un unlink() normal.
			$rutaRadarEntrevista = null;
			if(count($calificacionesEntrevista) >= 3){
				$rutaRadarEntrevista = sys_get_temp_dir().'/tcn_radar_'.uniqid().'.png';
				if(!generarRadarCompetencias($calificacionesEntrevista, $rutaRadarEntrevista)){
					$rutaRadarEntrevista = null;
				}else{
					register_shutdown_function(function() use ($rutaRadarEntrevista){
						if(file_exists($rutaRadarEntrevista)){ unlink($rutaRadarEntrevista); }
					});
				}
			}

			$datos = [
				'postulacion' => $postulacion,
				'evaluaciones' => $evaluaciones,
				'entrevista' => $entrevista,
				'calificacionesEntrevista' => $calificacionesEntrevista,
				'obligatorias' => $obligatorias,
				'rutaRadarEntrevista' => $rutaRadarEntrevista,
			];

			ob_start();
			$this->vista('ternas/reporte_pdf', $datos);
			$html = ob_get_clean();

			require_once '../app/librerias/html2pdf/html2pdf.class.php';

			$pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8');
			$pdf->writeHTML($html);
			$pdf->output('reporte-'.preg_replace('/[^a-z0-9]+/i', '-', $postulacion->nombres.'-'.$postulacion->apellidos).'.pdf');

		}

	}

?>
