<?php
	session_start();

	class Usuarios extends Controlador{

		public function __construct(){

			requiereLogin();

			$this->usuarioModelo = $this->modelo('Usuario');
			$this->perfilModelo = $this->modelo('Perfil');
			$this->auditoriaModelo = $this->modelo('Auditoria');
			$this->empresaModelo = $this->modelo('Empresa');

		}

		public function index(){

			requierePermiso('crear_usuario');

			$datos = [
				'usuarios' => $this->usuarioModelo->listar(),
				'perfiles' => $this->perfilModelo->listar(),
				'error' => $_SESSION['usuario_estado_error'] ?? null,
			];
			unset($_SESSION['usuario_estado_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('usuarios/listado', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function nuevo(){

			requierePermiso('crear_usuario');

			$datos = [
				'perfiles' => $this->perfilModelo->listar(),
				'empresas' => $this->empresaModelo->listarActivas(),
				'error' => $_SESSION['usuario_error'] ?? null
			];
			unset($_SESSION['usuario_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('usuarios/formulario', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function guardar(){

			requierePermiso('crear_usuario');

			$datos = [
				'nombres' => trim($_POST['nombres'] ?? ''),
				'apellidos' => trim($_POST['apellidos'] ?? ''),
				'email' => trim($_POST['email'] ?? ''),
				'password' => $_POST['password'] ?? '',
				'perfil_id' => $_POST['perfil_id'] ?? null,
				'empresa_id' => !empty($_POST['empresa_id']) ? $_POST['empresa_id'] : null,
				'estado' => 'activo'
			];

			if($datos['nombres'] === '' || $datos['apellidos'] === '' || $datos['email'] === '' || $datos['password'] === '' || !$datos['perfil_id']){
				$_SESSION['usuario_error'] = 'Todos los campos son obligatorios.';
				redirect('usuarios/nuevo');
			}

			if($this->usuarioModelo->buscarPorEmail($datos['email'])){
				$_SESSION['usuario_error'] = 'Ya existe un usuario con ese correo.';
				redirect('usuarios/nuevo');
			}

			$this->usuarioModelo->crear($datos);

			redirect('usuarios/index');

		}

		/** Editar datos basicos (nombres/apellidos/correo) de un usuario existente -- 2026-07-16.
		 * Perfil/empresa/contraseña siguen teniendo sus propias acciones dedicadas. **/
		public function editar($id){

			requierePermiso('crear_usuario');

			$usuario = $this->usuarioModelo->obtener($id);
			if(!$usuario){
				redirect('usuarios/index');
			}

			$datos = [
				'usuario' => $usuario,
				'error' => $_SESSION['usuario_editar_error'] ?? null
			];
			unset($_SESSION['usuario_editar_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('usuarios/editar', $datos);
			$this->vista('inc/foot', $datos);

		}

		/** Accion sensible (cambia el correo, que es el identificador de login): queda en auditoria,
		 * mismo criterio que cambiarPerfil/nuevaContrasena. **/
		public function actualizar($id){

			requierePermiso('crear_usuario');

			$usuario = $this->usuarioModelo->obtener($id);
			if(!$usuario){
				redirect('usuarios/index');
			}

			$datos = [
				'nombres' => trim($_POST['nombres'] ?? ''),
				'apellidos' => trim($_POST['apellidos'] ?? ''),
				'email' => trim($_POST['email'] ?? ''),
			];

			if($datos['nombres'] === '' || $datos['apellidos'] === '' || $datos['email'] === ''){
				$_SESSION['usuario_editar_error'] = 'Todos los campos son obligatorios.';
				redirect('usuarios/editar/'.$id);
			}

			$existente = $this->usuarioModelo->buscarPorEmail($datos['email']);
			if($existente && $existente->id != $id){
				$_SESSION['usuario_editar_error'] = 'Ya existe otro usuario con ese correo.';
				redirect('usuarios/editar/'.$id);
			}

			$this->usuarioModelo->actualizar($id, $datos);
			$this->auditoriaModelo->registrar(
				$_SESSION['usuario_id'], 'editar_usuario', 'usuarios', $id,
				'Datos actualizados: '.$usuario->nombres.' '.$usuario->apellidos.' <'.$usuario->email.'> -> '.$datos['nombres'].' '.$datos['apellidos'].' <'.$datos['email'].'>'
			);

			redirect('usuarios/index');

		}

		/** Cambio de perfil de un usuario existente: accion sensible, queda en auditoria (seccion 1.4) **/
		public function cambiarPerfil($id){

			requierePermiso('crear_usuario');

			$usuarioActual = $this->usuarioModelo->obtener($id);
			$nuevoPerfilId = $_POST['perfil_id'] ?? null;

			if($usuarioActual && $nuevoPerfilId && $nuevoPerfilId != $usuarioActual->perfil_id){
				$this->usuarioModelo->actualizarPerfil($id, $nuevoPerfilId);
				$this->auditoriaModelo->registrar(
					$_SESSION['usuario_id'], 'cambio_perfil', 'usuarios', $id,
					'De "'.$usuarioActual->perfil_nombre.'" a perfil_id='.$nuevoPerfilId
				);
			}

			redirect('usuarios/index');

		}

		public function desactivar($id){

			requierePermiso('crear_usuario');

			if($id == $_SESSION['usuario_id']){
				$_SESSION['usuario_estado_error'] = 'No puedes desactivar tu propio usuario.';
				redirect('usuarios/index');
			}

			$this->usuarioModelo->actualizarEstado($id, 'inactivo');
			$this->auditoriaModelo->registrar($_SESSION['usuario_id'], 'desactivar_usuario', 'usuarios', $id, 'Usuario desactivado');

			redirect('usuarios/index');

		}

		public function activar($id){

			requierePermiso('crear_usuario');

			$this->usuarioModelo->actualizarEstado($id, 'activo');
			$this->auditoriaModelo->registrar($_SESSION['usuario_id'], 'activar_usuario', 'usuarios', $id, 'Usuario reactivado');

			redirect('usuarios/index');

		}

		/** Asignar contraseña nueva a un usuario desde el panel (sin pasar por el flujo de correo) -- pedido de Ytalo, 2026-07-14. **/
		public function nuevaContrasena($id){

			requierePermiso('crear_usuario');

			$usuario = $this->usuarioModelo->obtener($id);
			if(!$usuario){
				redirect('usuarios/index');
			}

			$datos = [
				'usuario' => $usuario,
				'error' => $_SESSION['nueva_contrasena_error'] ?? null
			];
			unset($_SESSION['nueva_contrasena_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('usuarios/nuevaContrasena', $datos);
			$this->vista('inc/foot', $datos);

		}

		/** Accion sensible: queda en auditoria, mismo criterio que cambiarPerfil/desactivar/activar. **/
		public function guardarContrasena($id){

			requierePermiso('crear_usuario');

			$usuario = $this->usuarioModelo->obtener($id);
			if(!$usuario){
				redirect('usuarios/index');
			}

			$password = $_POST['password'] ?? '';
			$confirmar = $_POST['confirmar_password'] ?? '';

			if(strlen($password) < 8){
				$_SESSION['nueva_contrasena_error'] = 'La contraseña debe tener al menos 8 caracteres.';
				redirect('usuarios/nuevaContrasena/'.$id);
			}
			if($password !== $confirmar){
				$_SESSION['nueva_contrasena_error'] = 'Las contraseñas no coinciden.';
				redirect('usuarios/nuevaContrasena/'.$id);
			}

			$this->usuarioModelo->actualizarPassword($id, $password);
			$this->auditoriaModelo->registrar($_SESSION['usuario_id'], 'nueva_contrasena', 'usuarios', $id, 'Contraseña asignada manualmente desde el panel');

			redirect('usuarios/index');

		}

	}

?>
