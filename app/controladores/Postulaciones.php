<?php
	session_start();

	class Postulaciones extends Controlador{

		public function __construct(){

			requiereLogin();

			$this->postulacionModelo = $this->modelo('Postulacion');
			$this->vacanteModelo = $this->modelo('Vacante');
			$this->estadoPostulacionModelo = $this->modelo('EstadoPostulacion');
			$this->postulacionEvaluacionModelo = $this->modelo('PostulacionEvaluacion');
			$this->entrevistaModelo = $this->modelo('Entrevista');
			$this->candidatoEvaluacionModelo = $this->modelo('CandidatoEvaluacion');
			$this->auditoriaModelo = $this->modelo('Auditoria');
			$this->candidatoModelo = $this->modelo('Candidato');
			$this->competenciaModelo = $this->modelo('Competencia');
			$this->plantillaCorreoModelo = $this->modelo('PlantillaCorreo');
			$this->configuracionCorreoModelo = $this->modelo('ConfiguracionCorreo');

		}

		/** Perfil completo del postulante: datos, experiencia, educacion, habilidades y su CV (seccion 1.2).
		 * Visible para quien tiene ver_postulantes Y es dueño de la vacante (Administrador/Seleccionador
		 * propio/Empresa propia -- 2026-07-17, ahora que Empresa tambien puede tener ver_postulantes).
		 * Fallback para Empresa SIN ver_postulantes (o mirando la vacante de otra empresa, imposible hoy
		 * pero se deja como defensa): solo ve una vez que el candidato llega a Terna final/Contratado,
		 * mismo criterio ya establecido 2026-07-14 (Postulacion::visibleParaEmpresa()). **/
		public function perfil($postulacion_id){

			$postulacion = $this->postulacionModelo->obtenerCompleta($postulacion_id);
			if(!$postulacion){
				redirect('vacantes/index');
			}

			if(tienePermiso('ver_postulantes')){
				requiereDuenoDeVacante($this->vacanteModelo->obtener($postulacion->vacante_id));
			}elseif($_SESSION['perfil_nombre'] === 'Empresa'){
				$autorizacion = $this->postulacionModelo->visibleParaEmpresa($postulacion_id);
				if(!$autorizacion || $autorizacion->empresa_id != $_SESSION['empresa_id']){
					redirect('inicios/error');
				}
			}else{
				redirect(CONTROLADOR_ERROR.'/'.METODO_ERROR);
			}

			$datos = [
				'postulacion' => $postulacion,
				'candidato' => $this->candidatoModelo->obtener($postulacion->candidato_id),
				'experiencia' => $this->candidatoModelo->listarExperiencia($postulacion->candidato_id),
				'educacion' => $this->candidatoModelo->listarEducacion($postulacion->candidato_id),
				'habilidades' => $this->candidatoModelo->listarHabilidades($postulacion->candidato_id),
				'cv' => $this->candidatoModelo->ultimoCV($postulacion->candidato_id),
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('postulaciones/perfil', $datos);
			$this->vista('inc/foot', $datos);

		}

		/** Pipeline de postulantes de una vacante (seccion 1.2). 2026-07-17: antes solo exigia el
		 * permiso generico, sin verificar dueño -- cualquier Seleccionador (o, si se le daba el
		 * permiso, cualquier Empresa) podia ver el pipeline de una vacante ajena. **/
		public function vacante($vacante_id){

			requierePermiso('ver_postulantes');

			$vacante = $this->vacanteModelo->obtener($vacante_id);
			if(!$vacante){
				redirect('vacantes/index');
			}
			requiereDuenoDeVacante($vacante);

			$postulaciones = $this->postulacionModelo->listarPorVacante($vacante_id);
			foreach($postulaciones as $p){
				$p->evaluaciones = $this->postulacionEvaluacionModelo->listarPorPostulacion($p->id);
				$p->entrevista = $this->entrevistaModelo->obtenerPorPostulacion($p->id);
			}

			$datos = [
				'vacante' => $vacante,
				'postulaciones' => $postulaciones,
				'estados' => $this->estadoPostulacionModelo->listar(),
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('postulaciones/pipeline', $datos);
			$this->vista('inc/foot', $datos);

		}

		/** Detalle de resultados de evaluacion de un postulante (seccion 3.3: % + nivel, nunca el numero solo).
		 * 2026-07-17: no verificaba dueño de la vacante -- cualquiera con el permiso podia ver resultados
		 * de cualquier vacante ajena. **/
		public function resultados($postulacion_id){

			requierePermiso('ver_datos_sensibles_evaluacion');

			$postulacion = $this->postulacionModelo->obtenerCompleta($postulacion_id);
			if(!$postulacion){
				redirect('vacantes/index');
			}
			requiereDuenoDeVacante($this->vacanteModelo->obtener($postulacion->vacante_id));

			$entrevista = $this->entrevistaModelo->obtenerPorPostulacion($postulacion_id);

			// Calificaciones ya registradas por competencia (para marcar "completado"), y cuales
			// competencias marco la vacante como obligatorias -- pedido de Ytalo, 2026-07-15.
			$calificaciones = [];
			if($entrevista){
				foreach($this->entrevistaModelo->listarCalificaciones($entrevista->id) as $c){
					$calificaciones[$c->competencia_id] = $c;
				}
			}

			$datos = [
				'postulacion_id' => $postulacion_id,
				'postulacion' => $postulacion,
				'evaluaciones' => $this->postulacionEvaluacionModelo->listarPorPostulacion($postulacion_id),
				'entrevista' => $entrevista,
				'competencias' => $this->competenciaModelo->listarActivas(),
				'obligatorias' => array_column($this->vacanteModelo->competenciasAsignadas($postulacion->vacante_id), 'competencia_id'),
				'calificaciones' => $calificaciones,
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('postulaciones/resultados', $datos);
			$this->vista('inc/foot', $datos);

		}

		/**
		 * Mover el estado de un postulante. Rediseño 2026-07-14: Administrador puede
		 * mover postulantes de CUALQUIER vacante; Seleccionador solo de las vacantes
		 * que tiene asignadas como responsable -- antes cualquiera con el permiso
		 * ver_postulantes podía mover el estado de cualquier vacante, sin importar
		 * quién la creó. 2026-07-17: la verificación se centralizó en
		 * requiereDuenoDeVacante() (mismo criterio, ahora también cubre Empresa).
		 **/
		public function moverEstado($postulacion_id){

			requierePermiso('ver_postulantes');

			$postulacion = $this->postulacionModelo->obtener($postulacion_id);
			if(!$postulacion){
				redirect('vacantes/index');
			}
			requiereDuenoDeVacante($this->vacanteModelo->obtener($postulacion->vacante_id));

			$estado_id = $_POST['estado_id'] ?? null;
			if($estado_id){
				$this->postulacionModelo->cambiarEstado($postulacion_id, $estado_id, $_SESSION['usuario_id']);
				$this->enviarCorreoCambioEstado($postulacion_id, $estado_id);
			}

			redirect('postulaciones/vacante/'.$postulacion->vacante_id);

		}

		/** Plantillas de correo (2026-07-16), tipo cambio_estado -- una por cada estados_postulacion,
		 * cada una con su propio check "activo" para poder deshabilitar el envio de una etapa puntual
		 * (pedido explicito de Ytalo: "podria deshabilitar algunas etapas en el futuro"). **/
		private function enviarCorreoCambioEstado($postulacion_id, $estado_id){

			$plantilla = $this->plantillaCorreoModelo->obtenerPorEstado($estado_id);
			if(!$plantilla || !$plantilla->activo){
				return;
			}

			// moverEstado() solo tiene la fila cruda de postulaciones (Postulacion::obtener()) --
			// nombres/email/vacante_titulo vienen de la version con joins, obtenerCompleta().
			$postulacion = $this->postulacionModelo->obtenerCompleta($postulacion_id);
			if(!$postulacion){
				return;
			}

			$estado = $this->estadoPostulacionModelo->obtener($estado_id);

			$valores = [
				'{nombre}' => htmlspecialchars($postulacion->nombres),
				'{vacante}' => htmlspecialchars($postulacion->vacante_titulo),
				// Modo anonimo (seccion 1.3.1): mismo criterio que Portal::enviarCorreoPostulacionRecibida() --
				// si la vacante oculta la empresa, tambien se oculta en los correos de cambio de etapa.
				'{empresa}' => $postulacion->es_anonima ? 'una empresa confidencial' : htmlspecialchars($postulacion->empresa_nombre),
				'{seleccionador_nombre}' => htmlspecialchars(trim($postulacion->seleccionador_nombres.' '.$postulacion->seleccionador_apellidos)),
				'{seleccionador_correo}' => htmlspecialchars($postulacion->seleccionador_email),
				'{etapa}' => htmlspecialchars($estado->nombre ?? ''),
				'{link_estado}' => RUTA_URL.'portal/estado',
			];

			$remitente = $this->configuracionCorreoModelo->obtener();

			enviarCorreoPlantilla(
				$postulacion->email,
				reemplazarPlaceholders($plantilla->asunto, $valores),
				reemplazarPlaceholders($plantilla->cuerpo_html, $valores),
				$remitente->remitente_nombre ?? 'TCN Complement',
				$plantilla->cc_seleccionador ? $postulacion->seleccionador_email : null
			);

		}

		/** Fuerza una nueva evaluacion aunque el candidato tenga una vigente reutilizada (seccion 3.3), queda en auditoria **/
		public function forzarEvaluacion($postulacion_evaluacion_id){

			requierePermiso('configurar_evaluaciones');

			$pe = $this->postulacionEvaluacionModelo->obtener($postulacion_evaluacion_id);
			if(!$pe){
				redirect('vacantes/index');
			}

			$nuevaCandidatoEvaluacionId = $this->candidatoEvaluacionModelo->crearPendiente($pe->candidato_id, $pe->evaluacion_id, $pe->postulacion_id);
			$this->postulacionEvaluacionModelo->reasignarCandidatoEvaluacion($postulacion_evaluacion_id, $nuevaCandidatoEvaluacionId);

			$this->auditoriaModelo->registrar(
				$_SESSION['usuario_id'], 'forzar_evaluacion', 'postulacion_evaluacion', $postulacion_evaluacion_id,
				'Evaluación "'.$pe->evaluacion_nombre.'" forzada nuevamente para el candidato'
			);

			$postulacion = $this->postulacionModelo->obtener($pe->postulacion_id);
			redirect('postulaciones/vacante/'.$postulacion->vacante_id);

		}

	}

?>
