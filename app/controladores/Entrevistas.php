<?php
	session_start();

	class Entrevistas extends Controlador{

		public function __construct(){

			requiereLogin();

			$this->entrevistaModelo = $this->modelo('Entrevista');
			$this->postulacionModelo = $this->modelo('Postulacion');
			$this->competenciaModelo = $this->modelo('Competencia');
			$this->vacanteModelo = $this->modelo('Vacante');

		}

		public function agendar($postulacion_id){

			requierePermiso('agendar_entrevista');

			$postulacion = $this->postulacionModelo->obtener($postulacion_id);
			if(!$postulacion){
				redirect('vacantes/index');
			}
			requiereDuenoDeVacante($this->vacanteModelo->obtener($postulacion->vacante_id));

			$datos = [
				'postulacion_id' => $postulacion_id,
				'error' => $_SESSION['entrevista_error'] ?? null,
			];
			unset($_SESSION['entrevista_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('entrevistas/agendar', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function guardarAgenda($postulacion_id){

			requierePermiso('agendar_entrevista');

			$postulacion = $this->postulacionModelo->obtener($postulacion_id);
			if(!$postulacion){
				redirect('vacantes/index');
			}
			requiereDuenoDeVacante($this->vacanteModelo->obtener($postulacion->vacante_id));

			$fecha = $_POST['fecha_agendada'] ?? '';
			if($fecha === ''){
				$_SESSION['entrevista_error'] = 'La fecha y hora son obligatorias.';
				redirect('entrevistas/agendar/'.$postulacion_id);
			}

			$entrevista_id = $this->entrevistaModelo->crear($postulacion_id, $_SESSION['usuario_id'], $fecha);

			redirect('entrevistas/detalle/'.$entrevista_id);

		}

		public function detalle($id){

			requierePermiso('agendar_entrevista');

			$entrevista = $this->entrevistaModelo->obtener($id);
			if(!$entrevista){
				redirect('vacantes/index');
			}
			requiereDuenoDeVacante($this->vacanteModelo->obtener($entrevista->vacante_id));

			$calificaciones = [];
			foreach($this->entrevistaModelo->listarCalificaciones($id) as $c){
				$calificaciones[$c->competencia_id] = $c;
			}

			// Siempre se muestran TODAS las competencias activas -- confirmado con Ytalo, 2026-07-15:
			// nunca se ocultan, solo se resaltan como "Obligatoria" las que la vacante marco al
			// crearla/editarla (Vacantes::guardar()/actualizar() -> asignarCompetencias()). Si hay al
			// menos una obligatoria, notas y recomendacion final tambien pasan a ser requeridas
			// (validado en guardarCalificacion()).
			$competencias = $this->competenciaModelo->listarActivas();
			$obligatorias = array_column($this->vacanteModelo->competenciasAsignadas($entrevista->vacante_id), 'competencia_id');

			// Bloqueada = la postulacion ya llego a un estado terminal (Contratado/Descartado/Desertó).
			// Administrador puede seguir editando; el resto de perfiles queda solo-lectura, mismo criterio
			// ya usado esta sesion para mover postulantes (Postulaciones::moverEstado()) -- confirmado con
			// Ytalo, 2026-07-15.
			$bloqueada = $entrevista->es_final && $_SESSION['perfil_nombre'] !== 'Administrador';

			$datos = [
				'entrevista' => $entrevista,
				'competencias' => $competencias,
				'obligatorias' => $obligatorias,
				'calificaciones' => $calificaciones,
				'bloqueada' => $bloqueada,
				'error' => $_SESSION['entrevista_error'] ?? null,
			];
			unset($_SESSION['entrevista_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('entrevistas/detalle', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function guardarCalificacion($id){

			requierePermiso('calificar_entrevista');

			$entrevista = $this->entrevistaModelo->obtener($id);
			if(!$entrevista){
				redirect('vacantes/index');
			}
			requiereDuenoDeVacante($this->vacanteModelo->obtener($entrevista->vacante_id));
			if($entrevista->es_final && $_SESSION['perfil_nombre'] !== 'Administrador'){
				redirect('entrevistas/detalle/'.$id);
			}

			$notas = trim($_POST['notas'] ?? '');
			// recomendacion tiene un CHECK en BD que solo acepta los 3 valores del <select> o NULL --
			// '' (nada seleccionado) no es un valor valido y rompia el guardado con un error SQL crudo.
			$recomendacion = $_POST['recomendacion'] ?? '';
			$recomendacion = $recomendacion !== '' ? $recomendacion : null;

			// Si la vacante marco alguna competencia como obligatoria, calificarla deja de ser opcional,
			// y notas + recomendacion final tambien se vuelven obligatorias -- pedido de Ytalo, 2026-07-15.
			// Sin obligatorias asignadas, se mantiene el comportamiento original: todo opcional, se puede
			// guardar parcialmente y volver despues.
			$obligatorias = array_column($this->vacanteModelo->competenciasAsignadas($entrevista->vacante_id), 'competencia_id');
			if(!empty($obligatorias)){
				$faltaAlgo = $notas === '' || $recomendacion === null;
				foreach($obligatorias as $competencia_id){
					if(empty($_POST['calificacion'][$competencia_id])){
						$faltaAlgo = true;
						break;
					}
				}
				if($faltaAlgo){
					$_SESSION['entrevista_error'] = 'Esta vacante marcó competencias obligatorias: debes calificarlas todas y completar notas y recomendación final antes de guardar.';
					redirect('entrevistas/detalle/'.$id);
				}
			}

			$this->entrevistaModelo->guardarNotasYRecomendacion($id, $notas, $recomendacion);

			$calificaciones = [];
			foreach($_POST['calificacion'] ?? [] as $competencia_id => $valor){
				$calificaciones[$competencia_id] = [
					'calificacion' => $valor !== '' ? (int) $valor : null,
					'comentario' => trim($_POST['comentario'][$competencia_id] ?? ''),
				];
			}
			$this->entrevistaModelo->guardarCalificaciones($id, $calificaciones);

			redirect('entrevistas/detalle/'.$id);

		}

	}

?>
