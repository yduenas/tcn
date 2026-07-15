<?php
	session_start();

	class Evaluaciones extends Controlador{

		public function __construct(){

			$this->tokenEvaluacionModelo = $this->modelo('TokenEvaluacion');
			$this->postulacionEvaluacionModelo = $this->modelo('PostulacionEvaluacion');
			$this->candidatoEvaluacionModelo = $this->modelo('CandidatoEvaluacion');
			$this->evaluacionModelo = $this->modelo('Evaluacion');

		}

		/** Listado de evaluaciones pendientes/completadas de la postulacion (seccion 3.4) **/
		public function index($token){

			$tokenFila = $this->validarToken($token);

			$datos = [
				'token' => $token,
				'pendientes' => $this->postulacionEvaluacionModelo->listarPorPostulacion($tokenFila->postulacion_id),
				'mensaje' => $_SESSION['evaluacion_mensaje'] ?? null,
			];
			unset($_SESSION['evaluacion_mensaje']);

			$this->vista('inc/head', $datos);
			$this->vista('portal/nav', $datos);
			$this->vista('evaluaciones/index', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function tomar($token, $postulacion_evaluacion_id){

			$tokenFila = $this->validarToken($token);
			$pe = $this->validarPostulacionEvaluacion($tokenFila, $postulacion_evaluacion_id);

			if($pe->estado === 'completada' || $pe->estado === 'reutilizada'){
				redirect('evaluaciones/'.$token);
			}

			if($pe->estado === 'pendiente'){
				$this->postulacionEvaluacionModelo->iniciar($postulacion_evaluacion_id);
				$pe = $this->postulacionEvaluacionModelo->obtener($postulacion_evaluacion_id);
			}

			$segundosRestantes = $this->segundosRestantes($pe);
			if($segundosRestantes <= 0){
				$this->vencerIntento($pe);
				$_SESSION['evaluacion_mensaje'] = 'Se agotó el tiempo para "'.$pe->evaluacion_nombre.'"; no pudiste completarla a tiempo.';
				redirect('evaluaciones/'.$token);
			}

			$datos = [
				'token' => $token,
				'postulacion_evaluacion' => $pe,
				'preguntas' => $this->evaluacionModelo->preguntasCompletas($pe->evaluacion_id),
				'segundosRestantes' => $segundosRestantes,
			];

			$this->vista('inc/head', $datos);
			$this->vista('portal/nav', $datos);
			$this->vista('evaluaciones/tomar', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function enviarRespuestas($token, $postulacion_evaluacion_id){

			$tokenFila = $this->validarToken($token);
			$pe = $this->validarPostulacionEvaluacion($tokenFila, $postulacion_evaluacion_id);

			if($pe->estado === 'completada' || $pe->estado === 'reutilizada'){
				redirect('evaluaciones/'.$token);
			}

			if($this->segundosRestantes($pe) <= 0){
				$this->vencerIntento($pe);
				$_SESSION['evaluacion_mensaje'] = 'Se agotó el tiempo para "'.$pe->evaluacion_nombre.'"; no se pudo registrar tu respuesta.';
				redirect('evaluaciones/'.$token);
			}

			$preguntas = $this->evaluacionModelo->preguntasCompletas($pe->evaluacion_id);
			$respuestas = $this->leerRespuestasPost($pe->tipo);

			foreach($preguntas as $pregunta){
				foreach($respuestas[$pregunta->id] ?? [] as $rol => $opcion_id){
					$this->candidatoEvaluacionModelo->guardarRespuesta($pe->candidato_evaluacion_id, $pregunta->id, $opcion_id, $rol);
				}
			}

			$resultado = $this->candidatoEvaluacionModelo->calificar($pe->tipo, $preguntas, $respuestas);
			$this->candidatoEvaluacionModelo->marcarCompletada($pe->candidato_evaluacion_id, $resultado, $pe->vigencia_meses);
			$this->postulacionEvaluacionModelo->marcarEstado($postulacion_evaluacion_id, 'completada');

			redirect('evaluaciones/'.$token);

		}

		/** Segundos que quedan segun tiempo_limite_min y fecha_inicio; null/sin iniciar => tiempo completo **/
		private function segundosRestantes($pe){
			if(!$pe->fecha_inicio || !$pe->tiempo_limite_min){
				return ((int) $pe->tiempo_limite_min) * 60;
			}
			// SQLite CURRENT_TIMESTAMP guarda en UTC; hay que interpretarlo como UTC (no como la zona horaria local de PHP)
			$limite = strtotime($pe->fecha_inicio.' UTC') + ((int) $pe->tiempo_limite_min * 60);
			return $limite - time();
		}

		private function vencerIntento($pe){
			$this->candidatoEvaluacionModelo->marcarVencida($pe->candidato_evaluacion_id);
			$this->postulacionEvaluacionModelo->marcarEstado($pe->id, 'completada');
		}

		/** POST[pregunta_id][rol] = opcion_id, segun el tipo de evaluacion **/
		private function leerRespuestasPost($tipo){
			$respuestas = [];

			if($tipo === 'opcion_unica'){
				foreach($_POST['respuesta'] ?? [] as $pregunta_id => $opcion_id){
					$respuestas[$pregunta_id] = ['unica' => $opcion_id];
				}
			}else{
				foreach($_POST['mas'] ?? [] as $pregunta_id => $opcion_id){
					$respuestas[$pregunta_id]['mas'] = $opcion_id;
				}
				foreach($_POST['menos'] ?? [] as $pregunta_id => $opcion_id){
					$respuestas[$pregunta_id]['menos'] = $opcion_id;
				}
			}

			return $respuestas;
		}

		private function validarToken($token){
			$fila = $this->tokenEvaluacionModelo->buscarPorToken($token);
			if(!$fila){
				die('Link de evaluación no válido.');
			}
			return $fila;
		}

		private function validarPostulacionEvaluacion($tokenFila, $postulacion_evaluacion_id){
			$pe = $this->postulacionEvaluacionModelo->obtener($postulacion_evaluacion_id);
			if(!$pe || $pe->postulacion_id != $tokenFila->postulacion_id){
				die('Evaluación no encontrada para este link.');
			}
			return $pe;
		}

	}

?>
