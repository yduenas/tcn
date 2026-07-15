<?php
	session_start();

	/** Modulo de Plantillas de correo (pedido de Ytalo, 2026-07-16): 3 categorias --
	 * recuperacion de contrasena (siempre se envia, sin check de activo), postulacion
	 * recibida y una por cada etapa del pipeline (ambas con check "activo" para poder
	 * deshabilitar el envio de esa etapa/evento puntual sin tocar codigo). Tambien
	 * incluye el nombre de remitente (FromName), configurable por separado de la
	 * cuenta SMTP real. **/
	class PlantillasCorreo extends Controlador{

		public function __construct(){

			requiereLogin();
			requierePermiso('configurar_plantillas_correo');

			$this->plantillaModelo = $this->modelo('PlantillaCorreo');
			$this->configuracionCorreoModelo = $this->modelo('ConfiguracionCorreo');
			$this->usuarioModelo = $this->modelo('Usuario');

		}

		public function index(){

			$datos = [
				'plantillas' => $this->plantillaModelo->listar(),
				'configuracion' => $this->configuracionCorreoModelo->obtener(),
				'error' => $_SESSION['plantillas_error'] ?? null,
				'mensaje' => $_SESSION['plantillas_mensaje'] ?? null,
			];
			unset($_SESSION['plantillas_error'], $_SESSION['plantillas_mensaje']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('plantillasCorreo/index', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function actualizar($id){

			$asunto = trim($_POST['asunto'] ?? '');
			$cuerpo = sanitizarHtmlBasico(trim($_POST['cuerpo_html'] ?? ''));
			$ccSeleccionador = isset($_POST['cc_seleccionador']);

			if($asunto === '' || $cuerpo === ''){
				$_SESSION['plantillas_error'] = 'El asunto y el cuerpo del correo son obligatorios.';
			}else{
				$this->plantillaModelo->actualizar($id, $asunto, $cuerpo, $ccSeleccionador);
				$_SESSION['plantillas_mensaje'] = 'Plantilla actualizada.';
			}

			redirect('plantillasCorreo/index');

		}

		/** El check de activo/inactivo no aplica a la plantilla de recuperacion de
		 * contrasena -- esa siempre se envia, no se expone esta accion para ella en la
		 * vista, pero igual se valida aqui por si llega un id ajeno a mano. **/
		public function activar($id){
			$this->cambiarActivo($id, 1);
			redirect('plantillasCorreo/index');
		}

		public function desactivar($id){
			$this->cambiarActivo($id, 0);
			redirect('plantillasCorreo/index');
		}

		private function cambiarActivo($id, $activo){
			foreach($this->plantillaModelo->listar() as $plantilla){
				if($plantilla->id == $id && $plantilla->tipo !== 'recuperacion_password'){
					$this->plantillaModelo->actualizarEstado($id, $activo);
					return;
				}
			}
		}

		/** Envia la plantilla al correo del usuario logueado, con datos de ejemplo en
		 * los placeholders (no depende de una postulacion/vacante real) -- pedido de
		 * Ytalo, 2026-07-16, para poder ver como se ve/llega cada plantilla sin tener
		 * que provocar un evento real del pipeline. **/
		public function probar($id){

			$plantilla = $this->plantillaModelo->obtener($id);
			$usuario = $this->usuarioModelo->obtener($_SESSION['usuario_id']);

			if(!$plantilla || !$usuario){
				redirect('plantillasCorreo/index');
			}

			$valores = [
				'{nombre}' => 'Juan Pérez',
				'{vacante}' => 'Analista de Marketing (vacante de ejemplo)',
				'{empresa}' => 'Empresa Ejemplo S.A. (empresa de ejemplo)',
				'{seleccionador_nombre}' => htmlspecialchars(trim($usuario->nombres.' '.$usuario->apellidos)),
				'{seleccionador_correo}' => htmlspecialchars($usuario->email),
				'{etapa}' => 'Entrevista (etapa de ejemplo)',
				'{link_estado}' => RUTA_URL.'portal/estado',
				'{link}' => RUTA_URL.'logins/recuperar',
			];

			$remitente = $this->configuracionCorreoModelo->obtener();

			$enviado = enviarCorreoPlantilla(
				$usuario->email,
				'[PRUEBA] '.reemplazarPlaceholders($plantilla->asunto, $valores),
				reemplazarPlaceholders($plantilla->cuerpo_html, $valores),
				$remitente->remitente_nombre ?? 'TCN Complement'
			);

			$_SESSION['plantillas_mensaje'] = $enviado
				? 'Correo de prueba enviado a '.$usuario->email.'.'
				: null;
			if(!$enviado){
				$_SESSION['plantillas_error'] = 'No se pudo enviar el correo de prueba. Revisa la configuración de SMTP.';
			}

			redirect('plantillasCorreo/index');

		}

		public function actualizarRemitente(){

			$nombre = trim($_POST['remitente_nombre'] ?? '');
			if($nombre === ''){
				$_SESSION['plantillas_error'] = 'El nombre de remitente es obligatorio.';
			}else{
				$this->configuracionCorreoModelo->actualizarRemitente($nombre);
				$_SESSION['plantillas_mensaje'] = 'Remitente actualizado.';
			}

			redirect('plantillasCorreo/index');

		}

	}

?>
