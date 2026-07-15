<?php
	session_start();

	/**
	 * Arranque inicial: crea el primer usuario Administrador cuando la tabla `usuarios`
	 * esta vacia. Se auto-deshabilita en cuanto exista al menos un usuario, para poder
	 * dejarlo en el codigo sin riesgo de reutilizarlo despues del primer arranque.
	 */
	class Instalador extends Controlador{

		public function __construct(){

			$this->usuarioModelo = $this->modelo('Usuario');
			$this->perfilModelo = $this->modelo('Perfil');

		}

		public function index(){

			if($this->usuarioModelo->existeAlguno()){
				redirect('logins/index');
			}

			$datos = [
				'error' => $_SESSION['instalador_error'] ?? null
			];
			unset($_SESSION['instalador_error']);

			$this->vista('inc/head', $datos);
			$this->vista('instalador/index', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function crear(){

			if($this->usuarioModelo->existeAlguno()){
				redirect('logins/index');
			}

			$nombres = trim($_POST['nombres'] ?? '');
			$apellidos = trim($_POST['apellidos'] ?? '');
			$email = trim($_POST['email'] ?? '');
			$password = $_POST['password'] ?? '';
			$confirmar = $_POST['confirmar_password'] ?? '';

			if($nombres === '' || $apellidos === '' || $email === '' || $password === ''){
				$_SESSION['instalador_error'] = 'Todos los campos son obligatorios.';
				redirect('instalador/index');
			}

			if(strlen($password) < 8){
				$_SESSION['instalador_error'] = 'La contraseña debe tener al menos 8 caracteres.';
				redirect('instalador/index');
			}

			if($password !== $confirmar){
				$_SESSION['instalador_error'] = 'Las contraseñas no coinciden.';
				redirect('instalador/index');
			}

			$administrador = $this->buscarPerfilAdministrador();
			if(!$administrador){
				$_SESSION['instalador_error'] = 'No se encontró el perfil Administrador. Corre primero la migración 004_rbac.sql.';
				redirect('instalador/index');
			}

			$this->usuarioModelo->crear([
				'nombres' => $nombres,
				'apellidos' => $apellidos,
				'email' => $email,
				'password' => $password,
				'perfil_id' => $administrador->id,
				'empresa_id' => null,
				'estado' => 'activo',
			]);

			redirect('logins/index');

		}

		private function buscarPerfilAdministrador(){
			foreach($this->perfilModelo->listar() as $perfil){
				if($perfil->nombre === 'Administrador'){
					return $perfil;
				}
			}
			return null;
		}

	}

?>
