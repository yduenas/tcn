<?php
	session_start();

	/** Configuracion general (seccion 1.4): catalogos administrables desde el panel, nada hardcodeado. **/
	class ConfiguracionGeneral extends Controlador{

		public function __construct(){

			requiereLogin();
			requierePermiso('configurar_catalogos');

			$this->cargoCategoriaModelo = $this->modelo('CargoCategoria');
			$this->modalidadTrabajoModelo = $this->modelo('ModalidadTrabajo');
			$this->competenciaModelo = $this->modelo('Competencia');

		}

		public function index(){

			$datos = [
				'cargos' => $this->cargoCategoriaModelo->listar(),
				'modalidades' => $this->modalidadTrabajoModelo->listar(),
				'competencias' => $this->competenciaModelo->listar(),
				'error' => $_SESSION['config_error'] ?? null,
			];
			unset($_SESSION['config_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('configuracionGeneral/index', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function agregarCargo(){

			$nombre = trim($_POST['nombre'] ?? '');
			if($nombre === ''){
				$_SESSION['config_error'] = 'El nombre de la categoría de cargo es obligatorio.';
			}else{
				$this->cargoCategoriaModelo->crear($nombre);
			}

			redirect('configuracionGeneral/index');

		}

		public function agregarModalidad(){

			$nombre = trim($_POST['nombre'] ?? '');
			if($nombre === ''){
				$_SESSION['config_error'] = 'El nombre de la modalidad es obligatorio.';
			}else{
				$this->modalidadTrabajoModelo->crear($nombre);
			}

			redirect('configuracionGeneral/index');

		}

		public function editarCargo($id){

			$nombre = trim($_POST['nombre'] ?? '');
			if($nombre === ''){
				$_SESSION['config_error'] = 'El nombre de la categoría de cargo es obligatorio.';
			}else{
				$this->cargoCategoriaModelo->actualizar($id, $nombre);
			}

			redirect('configuracionGeneral/index');

		}

		public function anularCargo($id){
			$this->cargoCategoriaModelo->actualizarEstado($id, 0);
			redirect('configuracionGeneral/index');
		}

		public function reactivarCargo($id){
			$this->cargoCategoriaModelo->actualizarEstado($id, 1);
			redirect('configuracionGeneral/index');
		}

		public function editarModalidad($id){

			$nombre = trim($_POST['nombre'] ?? '');
			if($nombre === ''){
				$_SESSION['config_error'] = 'El nombre de la modalidad es obligatorio.';
			}else{
				$this->modalidadTrabajoModelo->actualizar($id, $nombre);
			}

			redirect('configuracionGeneral/index');

		}

		public function anularModalidad($id){
			$this->modalidadTrabajoModelo->actualizarEstado($id, 0);
			redirect('configuracionGeneral/index');
		}

		public function reactivarModalidad($id){
			$this->modalidadTrabajoModelo->actualizarEstado($id, 1);
			redirect('configuracionGeneral/index');
		}

		public function agregarCompetencia(){

			$nombre = trim($_POST['nombre'] ?? '');
			if($nombre === ''){
				$_SESSION['config_error'] = 'El nombre de la competencia es obligatorio.';
			}else{
				$this->competenciaModelo->crear($nombre);
			}

			redirect('configuracionGeneral/index');

		}

		public function editarCompetencia($id){

			$nombre = trim($_POST['nombre'] ?? '');
			if($nombre === ''){
				$_SESSION['config_error'] = 'El nombre de la competencia es obligatorio.';
			}else{
				$this->competenciaModelo->actualizar($id, $nombre);
			}

			redirect('configuracionGeneral/index');

		}

		public function anularCompetencia($id){
			$this->competenciaModelo->actualizarEstado($id, 0);
			redirect('configuracionGeneral/index');
		}

		public function reactivarCompetencia($id){
			$this->competenciaModelo->actualizarEstado($id, 1);
			redirect('configuracionGeneral/index');
		}

	}

?>
