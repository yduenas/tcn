<?php
	session_start();

	class Logins extends Controlador{

		public function __construct(){

					$this->usuarioModelo = $this->modelo('Usuario');
					$this->passwordResetModelo = $this->modelo('PasswordReset');
					$this->plantillaCorreoModelo = $this->modelo('PlantillaCorreo');
					$this->configuracionCorreoModelo = $this->modelo('ConfiguracionCorreo');
					$this->auditoriaModelo = $this->modelo('Auditoria');

		}

		public function index(){

			if(estaAutenticado()){
				redirect('dashboard/index');
			}

			$datos = [
				'error' => $_SESSION['login_error'] ?? null
			];
			unset($_SESSION['login_error']);

			$this->vista('inc/head', $datos);
			$this->vista('logins/index', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function autenticar(){

			$email = trim($_POST['email'] ?? '');
			$password = $_POST['password'] ?? '';

			$usuario = $email ? $this->usuarioModelo->buscarPorEmail($email) : null;

			if(!$usuario || $usuario->estado !== 'activo' || !password_verify($password, $usuario->password_hash)){
				$_SESSION['login_error'] = 'Correo o contraseña incorrectos.';
				redirect('logins/index');
			}

			session_regenerate_id(true);

			$_SESSION['usuario_id'] = $usuario->id;
			$_SESSION['usuario_nombre'] = $usuario->nombres.' '.$usuario->apellidos;
			$_SESSION['usuario_email'] = $usuario->email;
			$_SESSION['perfil_id'] = $usuario->perfil_id;
			$_SESSION['perfil_nombre'] = $usuario->perfil_nombre;
			$_SESSION['empresa_id'] = $usuario->empresa_id;
			$_SESSION['empresa_nombre'] = $usuario->empresa_nombre;
			$_SESSION['permisos'] = $this->usuarioModelo->listarPermisos($usuario->id);
			$_SESSION['ultimoAcceso'] = date('Y-n-j H:i:s');

			redirect('dashboard/index');

		}

		public function logout(){

				session_destroy();
				redirect('logins/index');
		}

		/** Cambio de contraseña self-service (2026-07-16) -- distinto del reset por token
		 * de correo (recuperar/restablecer) y del "Nueva contraseña" que un Administrador
		 * asigna a OTRO usuario (Usuarios::nuevaContrasena) -- este exige la contraseña
		 * actual porque lo hace el propio dueño de la cuenta, ya logueado. **/
		public function cambiarPassword(){

			requiereLogin();

			$datos = [
				'error' => $_SESSION['cambiar_password_error'] ?? null,
				'mensaje' => $_SESSION['cambiar_password_mensaje'] ?? null,
			];
			unset($_SESSION['cambiar_password_error'], $_SESSION['cambiar_password_mensaje']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('logins/cambiarPassword', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function actualizarPasswordPropia(){

			requiereLogin();

			$actual = $_POST['password_actual'] ?? '';
			$nueva = $_POST['password_nueva'] ?? '';
			$confirmar = $_POST['confirmar_password'] ?? '';

			if(!$this->usuarioModelo->verificarPassword($_SESSION['usuario_id'], $actual)){
				$_SESSION['cambiar_password_error'] = 'La contraseña actual no es correcta.';
				redirect('logins/cambiarPassword');
			}
			if(strlen($nueva) < 8){
				$_SESSION['cambiar_password_error'] = 'La nueva contraseña debe tener al menos 8 caracteres.';
				redirect('logins/cambiarPassword');
			}
			if($nueva !== $confirmar){
				$_SESSION['cambiar_password_error'] = 'Las contraseñas nuevas no coinciden.';
				redirect('logins/cambiarPassword');
			}

			$this->usuarioModelo->actualizarPassword($_SESSION['usuario_id'], $nueva);
			$this->auditoriaModelo->registrar($_SESSION['usuario_id'], 'cambiar_password_propia', 'usuarios', $_SESSION['usuario_id'], 'El usuario cambió su propia contraseña');

			$_SESSION['cambiar_password_mensaje'] = 'Contraseña actualizada correctamente.';
			redirect('logins/cambiarPassword');

		}

		public function recuperar(){

			$datos = [
				'mensaje' => $_SESSION['recuperar_mensaje'] ?? null,
			];
			unset($_SESSION['recuperar_mensaje']);

			$this->vista('inc/head', $datos);
			$this->vista('logins/recuperar', $datos);
			$this->vista('inc/foot', $datos);

		}

		/** Siempre responde con el mismo mensaje generico, exista o no ese correo (evita enumerar usuarios) **/
		public function enviarRecuperacion(){

			$email = trim($_POST['email'] ?? '');
			$usuario = $email ? $this->usuarioModelo->buscarPorEmail($email) : null;

			if($usuario && $usuario->estado === 'activo'){
				$token = $this->passwordResetModelo->crear($usuario->id);
				$this->enviarCorreoRecuperacion($usuario->email, $usuario->nombres, $token);
			}

			$_SESSION['recuperar_mensaje'] = 'Si el correo está registrado, te enviamos un enlace para restablecer tu contraseña. Revisa tu bandeja de entrada.';
			redirect('logins/recuperar');

		}

		public function restablecer($token){

			$fila = $this->passwordResetModelo->buscarValido($token);

			$datos = [
				'token' => $token,
				'valido' => (bool) $fila,
				'error' => $_SESSION['restablecer_error'] ?? null,
			];
			unset($_SESSION['restablecer_error']);

			$this->vista('inc/head', $datos);
			$this->vista('logins/restablecer', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function actualizarPassword($token){

			$fila = $this->passwordResetModelo->buscarValido($token);
			if(!$fila){
				redirect('logins/restablecer/'.$token);
			}

			$password = $_POST['password'] ?? '';
			$confirmar = $_POST['confirmar_password'] ?? '';

			if(strlen($password) < 8){
				$_SESSION['restablecer_error'] = 'La contraseña debe tener al menos 8 caracteres.';
				redirect('logins/restablecer/'.$token);
			}
			if($password !== $confirmar){
				$_SESSION['restablecer_error'] = 'Las contraseñas no coinciden.';
				redirect('logins/restablecer/'.$token);
			}

			$this->usuarioModelo->actualizarPassword($fila->usuario_id, $password);
			$this->passwordResetModelo->marcarUsado($fila->id);

			$_SESSION['login_error'] = null;
			$_SESSION['recuperar_mensaje'] = null;
			redirect('logins/index');

		}

		/** Contenido editable desde Plantillas de correo (tipo = recuperacion_password, 2026-07-16)
		 * -- antes asunto/cuerpo estaban fijos en este metodo. Esta plantilla siempre se envia,
		 * no tiene el check de activo que si tienen postulacion_recibida/cambio_estado. **/
		private function enviarCorreoRecuperacion($destino, $nombres, $token){

			$plantilla = $this->plantillaCorreoModelo->obtenerPorTipo('recuperacion_password');
			if(!$plantilla){
				return;
			}

			$link = RUTA_URL.'logins/restablecer/'.$token;
			$valores = [
				'{nombre}' => htmlspecialchars($nombres),
				'{link}' => $link,
			];

			$remitente = $this->configuracionCorreoModelo->obtener();

			enviarCorreoPlantilla(
				$destino,
				reemplazarPlaceholders($plantilla->asunto, $valores),
				reemplazarPlaceholders($plantilla->cuerpo_html, $valores),
				$remitente->remitente_nombre ?? 'TCN Complement'
			);

		}

	}

?>
