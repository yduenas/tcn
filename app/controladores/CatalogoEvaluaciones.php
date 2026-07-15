<?php
	session_start();

	/** Administracion del catalogo de evaluaciones (seccion 1.4 y 3): preguntas, opciones y vigencia, desde el panel. **/
	class CatalogoEvaluaciones extends Controlador{

		public function __construct(){

			requiereLogin();
			requierePermiso('configurar_evaluaciones');

			$this->evaluacionModelo = $this->modelo('Evaluacion');
			$this->competenciaModelo = $this->modelo('Competencia');

		}

		public function index(){

			$evaluaciones = $this->evaluacionModelo->listar();
			foreach($evaluaciones as $ev){
				$ev->total_preguntas = $this->evaluacionModelo->contarPreguntas($ev->id);
			}

			$datos = ['evaluaciones' => $evaluaciones];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('catalogoEvaluaciones/index', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function actualizarVigencia($evaluacion_id){

			$vigencia = $_POST['vigencia_meses'] !== '' ? (int) $_POST['vigencia_meses'] : null;
			$this->evaluacionModelo->actualizarVigencia($evaluacion_id, $vigencia);

			redirect('catalogoEvaluaciones/index');

		}

		public function preguntas($evaluacion_id){

			$evaluacion = $this->evaluacionModelo->obtener($evaluacion_id);
			if(!$evaluacion){
				redirect('catalogoEvaluaciones/index');
			}

			$datos = [
				'evaluacion' => $evaluacion,
				'preguntas' => $this->evaluacionModelo->preguntasCompletas($evaluacion_id),
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('catalogoEvaluaciones/preguntas', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function nuevaPregunta($evaluacion_id){

			$evaluacion = $this->evaluacionModelo->obtener($evaluacion_id);
			if(!$evaluacion){
				redirect('catalogoEvaluaciones/index');
			}

			$datos = [
				'evaluacion' => $evaluacion,
				'pregunta' => null,
				'competencias' => $this->competenciaModelo->listar(),
				'error' => $_SESSION['pregunta_error'] ?? null,
			];
			unset($_SESSION['pregunta_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('catalogoEvaluaciones/formularioPregunta', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function editarPregunta($pregunta_id){

			$pregunta = $this->evaluacionModelo->obtenerPregunta($pregunta_id);
			if(!$pregunta){
				redirect('catalogoEvaluaciones/index');
			}
			$evaluacion = $this->evaluacionModelo->obtener($pregunta->evaluacion_id);

			$datos = [
				'evaluacion' => $evaluacion,
				'pregunta' => $pregunta,
				'competencias' => $this->competenciaModelo->listar(),
				'error' => $_SESSION['pregunta_error'] ?? null,
			];
			unset($_SESSION['pregunta_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('catalogoEvaluaciones/formularioPregunta', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function guardarPregunta($evaluacion_id){

			$evaluacion = $this->evaluacionModelo->obtener($evaluacion_id);
			$enunciado = trim($_POST['enunciado'] ?? '');
			$opciones = $this->leerOpcionesPost();

			if($enunciado === '' || count($opciones) < 2){
				$_SESSION['pregunta_error'] = 'Escribe el enunciado y al menos 2 opciones.';
				redirect('catalogoEvaluaciones/nuevaPregunta/'.$evaluacion_id);
			}

			$tipoPregunta = $evaluacion->tipo === 'disc' ? 'forzada' : ($evaluacion->tipo === 'sjt' ? 'sjt' : 'opcion_unica');
			$competencia = $evaluacion->tipo === 'sjt' ? trim($_POST['competencia_nombre'] ?? '') : null;

			$this->evaluacionModelo->crearPregunta($evaluacion_id, $enunciado, $tipoPregunta, $opciones, $competencia);

			redirect('catalogoEvaluaciones/preguntas/'.$evaluacion_id);

		}

		public function actualizarPregunta($pregunta_id){

			$pregunta = $this->evaluacionModelo->obtenerPregunta($pregunta_id);
			if(!$pregunta){
				redirect('catalogoEvaluaciones/index');
			}
			$evaluacion = $this->evaluacionModelo->obtener($pregunta->evaluacion_id);

			$enunciado = trim($_POST['enunciado'] ?? '');
			$opciones = $this->leerOpcionesPost();

			if($enunciado === '' || count($opciones) < 2){
				$_SESSION['pregunta_error'] = 'Escribe el enunciado y al menos 2 opciones.';
				redirect('catalogoEvaluaciones/editarPregunta/'.$pregunta_id);
			}

			$competencia = $evaluacion->tipo === 'sjt' ? trim($_POST['competencia_nombre'] ?? '') : null;

			$this->evaluacionModelo->actualizarPregunta($pregunta_id, $enunciado, $opciones, $competencia);

			redirect('catalogoEvaluaciones/preguntas/'.$evaluacion->id);

		}

		private function leerOpcionesPost(){
			$textos = $_POST['opcion_texto'] ?? [];
			$puntajes = $_POST['opcion_puntaje'] ?? [];
			$etiquetas = $_POST['opcion_etiqueta'] ?? [];

			$opciones = [];
			foreach($textos as $i => $texto){
				if(trim($texto) === '') continue;
				$opciones[] = [
					'texto' => trim($texto),
					'puntaje' => $puntajes[$i] !== '' ? (int) $puntajes[$i] : 0,
					'etiqueta' => $etiquetas[$i] ?? null,
				];
			}
			return $opciones;
		}

	}

?>
