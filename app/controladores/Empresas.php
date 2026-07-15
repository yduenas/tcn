<?php
	session_start();

	class Empresas extends Controlador{

		private $mimesPermitidos = [
			'image/jpeg' => 'jpg',
			'image/png' => 'png',
		];
		private $pesoMaximo = 1048576; // 1 MB

		public function __construct(){

			requiereLogin();

			$this->empresaModelo = $this->modelo('Empresa');
			$this->vacanteModelo = $this->modelo('Vacante');
			$this->auditoriaModelo = $this->modelo('Auditoria');

		}

		public function index(){

			requierePermiso('crear_empresa');

			$datos = [
				'empresas' => $this->empresaModelo->listar()
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('empresas/listado', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function nuevo(){

			requierePermiso('crear_empresa');

			$datos = [
				'error' => $_SESSION['empresa_error'] ?? null,
				'empresa' => null
			];
			unset($_SESSION['empresa_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('empresas/formulario', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function editar($id){

			requierePermiso('editar_empresa');

			$empresa = $this->empresaModelo->obtener($id);
			if(!$empresa){
				redirect('empresas/index');
			}

			$datos = [
				'error' => $_SESSION['empresa_error'] ?? null,
				'empresa' => $empresa
			];
			unset($_SESSION['empresa_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('empresas/formulario', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function guardar(){

			requierePermiso('crear_empresa');

			$nombre = trim($_POST['nombre'] ?? '');
			$ruc = trim($_POST['ruc'] ?? '');
			$contacto = $this->leerDatosContacto();

			if($nombre === ''){
				$_SESSION['empresa_error'] = 'El nombre de la empresa es obligatorio.';
				redirect('empresas/nuevo');
			}
			if($contacto === null){
				redirect('empresas/nuevo');
			}

			$logo_path = $this->procesarLogo($_FILES['logo'] ?? null, true);

			$this->empresaModelo->crear(array_merge([
				'nombre' => $nombre,
				'ruc' => $ruc ?: null,
				'logo_path' => $logo_path,
			], $contacto));

			redirect('empresas/index');

		}

		public function actualizar($id){

			requierePermiso('editar_empresa');

			$nombre = trim($_POST['nombre'] ?? '');
			$ruc = trim($_POST['ruc'] ?? '');
			$contacto = $this->leerDatosContacto();

			if($nombre === ''){
				$_SESSION['empresa_error'] = 'El nombre de la empresa es obligatorio.';
				redirect('empresas/editar/'.$id);
			}
			if($contacto === null){
				redirect('empresas/editar/'.$id);
			}

			$logo_path = $this->procesarLogo($_FILES['logo'] ?? null, false);

			$this->empresaModelo->actualizar($id, array_merge([
				'nombre' => $nombre,
				'ruc' => $ruc ?: null,
				'logo_path' => $logo_path,
			], $contacto));

			redirect('empresas/index');

		}

		/** Datos de contacto de la empresa: todos opcionales, salvo validar el formato del correo si se ingresa **/
		private function leerDatosContacto(){

			$contacto_email = trim($_POST['contacto_email'] ?? '');
			if($contacto_email !== '' && !filter_var($contacto_email, FILTER_VALIDATE_EMAIL)){
				$_SESSION['empresa_error'] = 'El correo de contacto no tiene un formato válido.';
				return null;
			}

			return [
				'contacto_nombre' => trim($_POST['contacto_nombre'] ?? '') ?: null,
				'contacto_telefono' => trim($_POST['contacto_telefono'] ?? '') ?: null,
				'contacto_email' => $contacto_email ?: null,
				'direccion' => trim($_POST['direccion'] ?? '') ?: null,
				'sitio_web' => trim($_POST['sitio_web'] ?? '') ?: null,
				'linkedin' => trim($_POST['linkedin'] ?? '') ?: null,
			];

		}

		public function baja($id){

			requierePermiso('dar_baja_empresa');

			$this->empresaModelo->darDeBaja($id);
			$this->vacanteModelo->despublicarActivasPorEmpresa($id);
			$this->auditoriaModelo->registrar($_SESSION['usuario_id'], 'baja_empresa', 'empresas', $id, 'Baja lógica y despublicación en cascada de vacantes activas');

			redirect('empresas/index');

		}

		/** Reactiva una empresa; las vacantes quedan despublicadas, el seleccionador decide cuales republicar **/
		public function reactivar($id){

			requierePermiso('dar_baja_empresa');

			$this->empresaModelo->reactivar($id);
			$this->auditoriaModelo->registrar($_SESSION['usuario_id'], 'reactivar_empresa', 'empresas', $id, 'Reactivación; vacantes quedan despublicadas para revisión manual');

			redirect('empresas/index');

		}

		/**
		 * Valida y mueve el logo subido. $obligatorio = true en alta (seccion 1.3: campo obligatorio),
		 * false en edicion (si no se sube uno nuevo, se conserva el actual).
		 * Valida extension y MIME real del archivo, no solo el nombre, para evitar archivos renombrados.
		 */
		private function procesarLogo($archivo, $obligatorio){

			if(!$archivo || $archivo['error'] === UPLOAD_ERR_NO_FILE){
				if($obligatorio){
					$_SESSION['empresa_error'] = 'El logo de la empresa es obligatorio.';
					redirect('empresas/nuevo');
				}
				return null;
			}

			if($archivo['error'] !== UPLOAD_ERR_OK){
				$_SESSION['empresa_error'] = 'Ocurrio un error al subir el logo.';
				redirect($obligatorio ? 'empresas/nuevo' : 'empresas/index');
			}

			if($archivo['size'] > $this->pesoMaximo){
				$_SESSION['empresa_error'] = 'El logo no debe pesar mas de 1 MB.';
				redirect($obligatorio ? 'empresas/nuevo' : 'empresas/index');
			}

			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $archivo['tmp_name']);
			finfo_close($finfo);

			if(!isset($this->mimesPermitidos[$mime])){
				$_SESSION['empresa_error'] = 'El logo debe ser una imagen JPG o PNG.';
				redirect($obligatorio ? 'empresas/nuevo' : 'empresas/index');
			}

			$nombreArchivo = 'empresa_'.bin2hex(random_bytes(8)).'.'.$this->mimesPermitidos[$mime];
			$rutaRelativa = 'public/uploads/empresas/'.$nombreArchivo;
			$rutaDestino = RUTA_DOCUMENTO.$rutaRelativa;

			move_uploaded_file($archivo['tmp_name'], $rutaDestino);

			return $rutaRelativa;

		}

	}

?>
