<?php
	session_start();

	class Perfiles extends Controlador{

		public function __construct(){

			requiereLogin();
			requierePermiso('configurar_perfiles');

			$this->perfilModelo = $this->modelo('Perfil');
			$this->permisoModelo = $this->modelo('Permiso');

		}

		public function index(){

			$perfiles = $this->perfilModelo->listar();
			foreach($perfiles as $perfil){
				$perfil->total_permisos = count($this->perfilModelo->permisos($perfil->id));
				$perfil->total_usuarios = $this->perfilModelo->contarUsuarios($perfil->id);
			}

			$datos = ['perfiles' => $perfiles];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('perfiles/listado', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function nuevo(){

			$datos = [
				'perfil' => null,
				'permisosPorCategoria' => $this->agruparPorCategoria($this->permisoModelo->listar()),
				'asignados' => [],
				'error' => $_SESSION['perfil_error'] ?? null,
			];
			unset($_SESSION['perfil_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('perfiles/formulario', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function editar($id){

			$perfil = $this->perfilModelo->obtener($id);
			if(!$perfil){
				redirect('perfiles/index');
			}

			$datos = [
				'perfil' => $perfil,
				'permisosPorCategoria' => $this->agruparPorCategoria($this->permisoModelo->listar()),
				'asignados' => array_column($this->perfilModelo->permisos($id), 'id'),
				'error' => $_SESSION['perfil_error'] ?? null,
			];
			unset($_SESSION['perfil_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('perfiles/formulario', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function guardar(){

			$nombre = trim($_POST['nombre'] ?? '');
			if($nombre === ''){
				$_SESSION['perfil_error'] = 'El nombre del perfil es obligatorio.';
				redirect('perfiles/nuevo');
			}

			$perfil_id = $this->perfilModelo->crear($nombre, trim($_POST['descripcion'] ?? ''));
			$this->perfilModelo->guardarPermisos($perfil_id, $_POST['permisos'] ?? []);

			redirect('perfiles/index');

		}

		public function actualizar($id){

			$nombre = trim($_POST['nombre'] ?? '');
			if($nombre === ''){
				$_SESSION['perfil_error'] = 'El nombre del perfil es obligatorio.';
				redirect('perfiles/editar/'.$id);
			}

			$this->perfilModelo->actualizar($id, $nombre, trim($_POST['descripcion'] ?? ''));
			$this->perfilModelo->guardarPermisos($id, $_POST['permisos'] ?? []);

			redirect('perfiles/index');

		}

		private function agruparPorCategoria($permisos){
			$grupos = [];
			foreach($permisos as $permiso){
				$grupos[$permiso->categoria][] = $permiso;
			}
			return $grupos;
		}

	}

?>
