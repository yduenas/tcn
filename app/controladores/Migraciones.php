<?php
	session_start();

	/** Modulo de Migraciones (pedido de Ytalo, 2026-07-16): ver que migraciones ya
	 * corrieron (migracionDB.json) vs. cuales siguen pendientes (archivos .sql en
	 * app/migraciones/ sin registrar) y poder ejecutarlas desde el panel, sin depender
	 * de un script suelto por PHP CLI cada vez. **/
	class Migraciones extends Controlador{

		public function __construct(){

			requiereLogin();
			requierePermiso('administrar_migraciones');

			$this->migracionModelo = $this->modelo('Migracion');
			$this->auditoriaModelo = $this->modelo('Auditoria');

		}

		public function index(){

			$datos = [
				'migraciones' => $this->migracionModelo->listar(),
				'error' => $_SESSION['migraciones_error'] ?? null,
				'mensaje' => $_SESSION['migraciones_mensaje'] ?? null,
			];
			unset($_SESSION['migraciones_error'], $_SESSION['migraciones_mensaje']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('migraciones/index', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function ver($nombreArchivo){

			$contenido = $this->migracionModelo->contenido(basename($nombreArchivo));

			$datos = [
				'nombre_archivo' => basename($nombreArchivo),
				'contenido' => $contenido,
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('migraciones/ver', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function ejecutar($nombreArchivo){

			$nombreArchivo = basename($nombreArchivo);

			if(!$this->migracionModelo->esEjecutable($nombreArchivo)){
				$_SESSION['migraciones_error'] = 'Esa migración no existe o ya fue ejecutada.';
				redirect('migraciones/index');
			}

			$resultado = $this->migracionModelo->ejecutar($nombreArchivo);

			$this->auditoriaModelo->registrar(
				$_SESSION['usuario_id'], 'ejecutar_migracion', 'migracion', null,
				$nombreArchivo.' -- '.($resultado['ok'] ? 'exitosa' : 'fallida: '.$resultado['error'])
			);

			if($resultado['ok']){
				$_SESSION['migraciones_mensaje'] = 'Migración "'.$nombreArchivo.'" ejecutada correctamente.';
			}else{
				$_SESSION['migraciones_error'] = 'Error al ejecutar "'.$nombreArchivo.'": '.$resultado['error'];
			}

			redirect('migraciones/index');

		}

	}

?>
