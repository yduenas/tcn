<?php
	session_start();

	/** Modulo "Cron" (pedido de Ytalo, 2026-07-16): el servidor real no permite crear
	 * cron jobs, asi que este modulo agrupa tareas de mantenimiento periodico para
	 * ejecutarlas manualmente desde el panel. Se espera que crezca con mas tareas. **/
	class Cron extends Controlador{

		const HORAS_HUERFANO = 24;
		const DIAS_CV_DESCARTADO = 15;

		public function __construct(){

			requiereLogin();
			requierePermiso('administrar_cron');

			$this->cronModelo = $this->modelo('TareaCron');
			$this->auditoriaModelo = $this->modelo('Auditoria');

		}

		public function index(){

			$datos = [
				'resumenCv' => $this->cronModelo->resumenCvHuerfanos(self::HORAS_HUERFANO),
				'resumenCvDescartados' => $this->cronModelo->resumenCvDescartados(self::DIAS_CV_DESCARTADO),
				'mensaje' => $_SESSION['cron_mensaje'] ?? null,
			];
			unset($_SESSION['cron_mensaje']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('cron/index', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function limpiarCvHuerfanos(){

			$borrados = $this->cronModelo->limpiarCvHuerfanos(self::HORAS_HUERFANO);

			$this->auditoriaModelo->registrar(
				$_SESSION['usuario_id'], 'cron_limpiar_cv_huerfanos', 'cron', null,
				$borrados.' archivo(s) borrado(s) (huérfanos con '.self::HORAS_HUERFANO.'h o más)'
			);

			$_SESSION['cron_mensaje'] = $borrados > 0
				? $borrados.' CV(s) huérfano(s) eliminado(s).'
				: 'No había CVs huérfanos con '.self::HORAS_HUERFANO.' horas o más para eliminar.';

			redirect('cron/index');

		}

		/** 2da tarea (2026-07-16): borra el CV de candidatos sin ningun proceso activo
		 * (todas sus postulaciones en Descartado/Desertó) hace mas de DIAS_CV_DESCARTADO
		 * dias. Si vuelven a postular sin CV en archivo, se les vuelve a pedir con
		 * normalidad (Portal::enviar() ya maneja ese caso). **/
		public function limpiarCvDescartados(){

			$candidatos = $this->cronModelo->limpiarCvDescartados(self::DIAS_CV_DESCARTADO);

			$this->auditoriaModelo->registrar(
				$_SESSION['usuario_id'], 'cron_limpiar_cv_descartados', 'cron', null,
				$candidatos.' candidato(s) sin proceso activo, CV eliminado (Descartado/Desertó con '.self::DIAS_CV_DESCARTADO.' días o más)'
			);

			$_SESSION['cron_mensaje'] = $candidatos > 0
				? 'CV eliminado de '.$candidatos.' candidato(s) sin proceso activo.'
				: 'No había candidatos descartados/desistidos con '.self::DIAS_CV_DESCARTADO.' días o más para limpiar.';

			redirect('cron/index');

		}

	}

?>
