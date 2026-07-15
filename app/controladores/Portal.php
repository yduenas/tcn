<?php
	session_start();

	class Portal extends Controlador{

		const TEXTO_CONSENTIMIENTO = 'v1 - Autorizo el tratamiento de mis datos personales para fines de selección de personal.';
		const TEXTO_DECLARACION = 'v1 - Declaro que la información en este formulario es correcta y soy responsable de su exactitud.';
		private $pesoMaximoCV = 307200; // 300 KB -- pedido de Ytalo, 2026-07-16: el CV es 1 solo archivo por candidato (no por postulacion), asi que se pide reducido y de una hoja, no un CV documentado (certificados/diplomas escaneados)

		public function __construct(){

			$this->vacanteModelo = $this->modelo('Vacante');
			$this->cargoCategoriaModelo = $this->modelo('CargoCategoria');
			$this->modalidadTrabajoModelo = $this->modelo('ModalidadTrabajo');
			$this->candidatoModelo = $this->modelo('Candidato');
			$this->postulacionModelo = $this->modelo('Postulacion');
			$this->candidatoEvaluacionModelo = $this->modelo('CandidatoEvaluacion');
			$this->postulacionEvaluacionModelo = $this->modelo('PostulacionEvaluacion');
			$this->tokenEvaluacionModelo = $this->modelo('TokenEvaluacion');
			$this->plantillaCorreoModelo = $this->modelo('PlantillaCorreo');
			$this->configuracionCorreoModelo = $this->modelo('ConfiguracionCorreo');

		}

		public function index(){

			$filtros = [
				'cargo_categoria_id' => $_GET['cargo_categoria_id'] ?? null,
				'modalidad_id' => $_GET['modalidad_id'] ?? null,
				'ubicacion' => trim($_GET['ubicacion'] ?? ''),
			];

			$datos = [
				'vacantes' => $this->vacanteModelo->listarPublicas($filtros),
				'cargos' => $this->cargoCategoriaModelo->listarActivas(),
				'modalidades' => $this->modalidadTrabajoModelo->listarActivas(),
				'ubicaciones' => $this->vacanteModelo->listarUbicacionesPublicadas(),
				'filtros' => $filtros,
			];

			$this->vista('inc/head', $datos);
			$this->vista('portal/nav', $datos);
			$this->vista('portal/index', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function vacante($id){

			$vacante = $this->vacanteModelo->obtenerPublica($id);
			if(!$vacante){
				redirect('portal/index');
			}

			$datos = ['vacante' => $vacante];

			$this->vista('inc/head', $datos);
			$this->vista('portal/nav', $datos);
			$this->vista('portal/detalle', $datos);
			$this->vista('inc/foot', $datos);

		}

		/**
		 * Flujo en 2 pasos (pedido de Ytalo, 2026-07-16 -- lo primero que se pide es
		 * correo+DNI, no el formulario completo ni el CV): mientras no se haya intentado
		 * cargar datos previos NI elegido explicitamente continuar sin ellos, esta pantalla
		 * solo muestra el mini-formulario de correo+DNI. Recien despues (candidato_draft
		 * cargado, o decidio continuar de cero) se muestra el CV + formulario completo.
		 **/
		public function postular($id){

			$vacante = $this->vacanteModelo->obtenerPublica($id);
			if(!$vacante){
				redirect('portal/index');
			}

			$datos = [
				'vacante' => $vacante,
				'error' => $_SESSION['postular_error'] ?? null,
				'aviso' => $_SESSION['postular_aviso'] ?? null,
				'cvDraft' => $_SESSION['cv_draft'] ?? null,
				'candidatoDraft' => $_SESSION['candidato_draft'] ?? null,
				'fase2' => !empty($_SESSION['postular_fase2_'.$id]),
			];
			unset($_SESSION['postular_error'], $_SESSION['postular_aviso']);

			$this->vista('inc/head', $datos);
			$this->vista('portal/nav', $datos);
			$this->vista('portal/postular', $datos);
			$this->vista('inc/foot', $datos);

		}

		/** El candidato elige explicitamente no cargar datos previos (primera postulacion,
		 * o no tiene a mano el DNI con el que postulo antes) -- pasa igual al formulario
		 * completo, vacio. **/
		public function continuarSinDatos($id){
			$_SESSION['postular_fase2_'.$id] = true;
			redirect('portal/postular/'.$id);
		}

		/** Recupera los datos de un candidato ya registrado (correo + DNI, seccion 6) para
		 * que un postulante que vuelve a aplicar no tenga que llenar todo el formulario de
		 * nuevo -- pedido de Ytalo, 2026-07-16. Se exige que AMBOS coincidan (no solo el
		 * correo, que ya se usa pasivamente para reutilizar el CV en enviar()) porque esto
		 * muestra datos personales (telefono, experiencia, educacion) ANTES de cualquier
		 * consentimiento/declaracion -- el correo solo no alcanza como verificacion aqui.
		 * Mensaje deliberadamente generico si no encuentra coincidencia (no dice si fallo
		 * el correo o el DNI, ni si el candidato simplemente no tiene DNI registrado) --
		 * mismo criterio anti-enumeracion ya usado en Logins::enviarRecuperacion(). Un
		 * intento fallido igual deja pasar al formulario completo (vacio), no es un muro
		 * de seguridad, es solo una comodidad para quien ya postulo antes. **/
		public function cargarDatos($id){

			$vacante = $this->vacanteModelo->obtenerPublica($id);
			if(!$vacante){
				redirect('portal/index');
			}

			$email = trim($_POST['correo_previo'] ?? '');
			$dni = trim($_POST['dni_previo'] ?? '');

			$candidato = $email !== '' ? $this->candidatoModelo->buscarPorEmail($email) : null;

			if(!$candidato || empty($candidato->dni) || $dni === '' || $candidato->dni !== $dni){
				$_SESSION['postular_fase2_'.$id] = true;
				$_SESSION['postular_aviso'] = 'No encontramos una postulación previa con esos datos. Completa el formulario a continuación.';
				redirect('portal/postular/'.$id);
			}

			if($this->postulacionModelo->yaPostulo($candidato->id, $id)){
				$this->rebotar($id, 'Ya tienes una postulación registrada para esta vacante. Puedes revisar su estado en "Consultar mi postulación".');
			}

			$experiencia = $this->candidatoModelo->listarExperiencia($candidato->id);
			$educacion = $this->candidatoModelo->listarEducacion($candidato->id);
			$habilidades = $this->candidatoModelo->listarHabilidades($candidato->id);

			$_SESSION['candidato_draft'] = [
				'nombres' => $candidato->nombres,
				'apellidos' => $candidato->apellidos,
				'email' => $candidato->email,
				'telefono' => $candidato->telefono,
				'dni' => $candidato->dni,
				'pretension_salarial' => $candidato->pretension_salarial,
				'disponibilidad' => $candidato->disponibilidad,
				'habilidades' => array_column($habilidades, 'nombre'),
				'experiencia_filas' => array_map(function($e){
					return [
						'empresa' => $e->empresa, 'cargo' => $e->cargo,
						'fecha_inicio' => $e->fecha_inicio, 'fecha_fin' => $e->fecha_fin,
						'actualidad' => (bool) $e->actualidad, 'descripcion' => $e->descripcion,
					];
				}, $experiencia),
				'educacion_filas' => array_map(function($e){
					return [
						'institucion' => $e->institucion, 'grado' => $e->grado,
						'campo_estudio' => $e->campo_estudio,
						'fecha_inicio' => $e->fecha_inicio, 'fecha_fin' => $e->fecha_fin,
					];
				}, $educacion),
			];

			$_SESSION['postular_fase2_'.$id] = true;
			redirect('portal/postular/'.$id);

		}

		/** Autocompletar desde el CV (secciones 6.2/6.3): extrae un borrador, sin guardar nada en BD todavia **/
		public function extraerCV($id){

			$vacante = $this->vacanteModelo->obtenerPublica($id);
			if(!$vacante){
				redirect('portal/index');
			}

			$cv = $this->procesarCV($_FILES['cv'] ?? null, $id);
			$texto = ExtractorCV::extraerTexto(RUTA_DOCUMENTO.$cv['ruta']);
			$borrador = ExtractorCV::extraerDatos($texto);

			$_SESSION['cv_draft'] = [
				'archivo_path' => $cv['ruta'],
				'peso_kb' => $cv['peso_kb'],
				'nombre_archivo_original' => $_FILES['cv']['name'] ?? 'cv.pdf',
				'datos' => $borrador,
			];

			redirect('portal/postular/'.$id);

		}

		public function enviar($id){

			$vacante = $this->vacanteModelo->obtenerPublica($id);
			if(!$vacante){
				redirect('portal/index');
			}

			$nombres = trim($_POST['nombres'] ?? '');
			$apellidos = trim($_POST['apellidos'] ?? '');
			$email = trim($_POST['email'] ?? '');
			$dni = trim($_POST['dni'] ?? '');

			if($nombres === '' || $apellidos === '' || $email === '' || $dni === ''){
				$this->rebotar($id, 'Nombres, apellidos, correo y DNI son obligatorios.');
			}

			if(empty($_POST['consentimiento'])){
				$this->rebotar($id, 'Debes aceptar el consentimiento de protección de datos personales para postular.');
			}

			if(empty($_POST['declaracion_veracidad'])){
				$this->rebotar($id, 'Debes declarar que la información del formulario es correcta para postular.');
			}

			$ip = $_SERVER['REMOTE_ADDR'] ?? '';

			// resolverCV() necesita saber si YA existe un CV para este correo, para
			// no exigir uno nuevo (seccion 6.4 del CLAUDE.md). Se consulta ANTES de
			// tocar guardarPerfil() a proposito -- si el candidato termina siendo
			// rechazado por no tener CV, no debe quedar un perfil a medias creado en
			// la base de datos (bug real encontrado probando: con el orden anterior,
			// un candidato genuinamente nuevo que fallaba el paso del CV igual
			// quedaba con una fila en "candidatos", sin ninguna postulacion real).
			$existente = $this->candidatoModelo->buscarPorEmail($email);

			// Email y DNI deben ser unicos (2026-07-15, migracion 022; DNI obligatorio
			// desde 2026-07-16). Si el DNI ya pertenece a OTRO candidato (correo distinto),
			// se rechaza con un mensaje claro -- no se fusiona automaticamente, para no
			// mezclar por error los datos de dos personas distintas si alguien tipeo mal
			// un DNI ajeno (decision confirmada con Ytalo).
			$porDni = $this->candidatoModelo->buscarPorDni($dni);
			if($porDni && (!$existente || $porDni->id !== $existente->id)){
				$this->rebotar($id, 'Este DNI ya está registrado con otro correo. Si es tuyo, usa el mismo correo de tu postulación anterior.');
			}

			$tieneCVExistente = $existente && $this->candidatoModelo->ultimoCV($existente->id);
			$cv = $this->resolverCV($id, $tieneCVExistente);

			$candidato_id = $this->candidatoModelo->guardarPerfil([
				'nombres' => $nombres,
				'apellidos' => $apellidos,
				'email' => $email,
				'telefono' => trim($_POST['telefono'] ?? ''),
				'dni' => $dni,
				'pretension_salarial' => $_POST['pretension_salarial'] !== '' ? $_POST['pretension_salarial'] : null,
				'disponibilidad' => trim($_POST['disponibilidad'] ?? ''),
			]);

			$this->candidatoModelo->reemplazarExperiencia($candidato_id, $this->filasExperiencia());
			$this->candidatoModelo->reemplazarEducacion($candidato_id, $this->filasEducacion());
			$this->candidatoModelo->reemplazarHabilidades($candidato_id, $this->habilidadesPost());
			if($cv){
				$this->candidatoModelo->registrarCV($candidato_id, $cv['ruta'], $cv['peso_kb']);
			}
			$this->candidatoModelo->registrarConsentimiento($candidato_id, self::TEXTO_CONSENTIMIENTO, $ip);
			$this->candidatoModelo->registrarDeclaracion($candidato_id, self::TEXTO_DECLARACION, $ip);

			unset($_SESSION['cv_draft'], $_SESSION['candidato_draft'], $_SESSION['postular_fase2_'.$id]);

			if($this->postulacionModelo->yaPostulo($candidato_id, $id)){
				$_SESSION['postulacion_mensaje'] = 'Ya tienes una postulación registrada para esta vacante. Puedes revisar su estado en "Consultar mi postulación".';
				redirect('portal/estado');
			}

			$postulacion_id = $this->postulacionModelo->crear($candidato_id, $id);
			$token = $this->asignarEvaluaciones($candidato_id, $id, $postulacion_id);
			$this->enviarCorreoPostulacionRecibida($email, $nombres, $vacante, $token);

			$_SESSION['postulacion_mensaje'] = $token
				? '¡Postulación enviada con éxito! Tienes evaluaciones pendientes — accede con tu link personal: '.RUTA_URL.'evaluaciones/'.$token
				: '¡Postulación enviada con éxito! Puedes consultar su estado en cualquier momento con tu correo y DNI.';
			redirect('portal/estado');

		}

		/** Asigna al candidato las evaluaciones que pide la vacante (seccion 3.3): reutiliza una vigente o crea una nueva pendiente **/
		private function asignarEvaluaciones($candidato_id, $vacante_id, $postulacion_id){

			$evaluaciones = $this->vacanteModelo->evaluacionesAsignadas($vacante_id);
			if(empty($evaluaciones)){
				return null;
			}

			foreach($evaluaciones as $evaluacion){
				$vigente = $this->candidatoEvaluacionModelo->obtenerVigente($candidato_id, $evaluacion->evaluacion_id);

				if($vigente){
					$this->postulacionEvaluacionModelo->crear($postulacion_id, $vigente->id, 'reutilizada');
				}else{
					$candidato_evaluacion_id = $this->candidatoEvaluacionModelo->crearPendiente($candidato_id, $evaluacion->evaluacion_id);
					$this->postulacionEvaluacionModelo->crear($postulacion_id, $candidato_evaluacion_id, 'pendiente');
				}
			}

			return $this->tokenEvaluacionModelo->generarOObtener($postulacion_id);

		}

		/** Plantillas de correo (2026-07-16), tipo postulacion_recibida -- respeta el
		 * check "activo" (a diferencia de recuperacion_password, este si se puede
		 * deshabilitar desde el panel). **/
		private function enviarCorreoPostulacionRecibida($destino, $nombres, $vacante, $token){

			$plantilla = $this->plantillaCorreoModelo->obtenerPorTipo('postulacion_recibida');
			if(!$plantilla || !$plantilla->activo){
				return;
			}

			$valores = [
				'{nombre}' => htmlspecialchars($nombres),
				'{vacante}' => htmlspecialchars($vacante->titulo),
				// Modo anonimo (seccion 1.3.1): si la vacante oculta la empresa en el portal
				// publico, tambien se oculta aqui -- mismo criterio, nunca revelarla al candidato.
				'{empresa}' => $vacante->es_anonima ? 'una empresa confidencial' : htmlspecialchars($vacante->empresa_nombre),
				'{seleccionador_nombre}' => htmlspecialchars(trim($vacante->seleccionador_nombres.' '.$vacante->seleccionador_apellidos)),
				'{seleccionador_correo}' => htmlspecialchars($vacante->seleccionador_email),
				// Si la vacante no pide ninguna evaluacion, {link_evaluaciones} se reemplaza
				// por texto vacio (pedido de Ytalo, 2026-07-16) -- el template NO envuelve
				// este token en su propio <p>, la sustitucion trae su propio bloque cuando
				// aplica, asi que sin evaluaciones no queda un parrafo vacio ni un link roto.
				'{link_evaluaciones}' => $token
					? '<p>Tienes evaluaciones pendientes — accede con tu link personal: <a href="'.RUTA_URL.'evaluaciones/'.$token.'">'.RUTA_URL.'evaluaciones/'.$token.'</a></p>'
					: '',
				'{link_estado}' => RUTA_URL.'portal/estado',
			];

			$remitente = $this->configuracionCorreoModelo->obtener();

			enviarCorreoPlantilla(
				$destino,
				reemplazarPlaceholders($plantilla->asunto, $valores),
				reemplazarPlaceholders($plantilla->cuerpo_html, $valores),
				$remitente->remitente_nombre ?? 'TCN Complement',
				$plantilla->cc_seleccionador ? $vacante->seleccionador_email : null
			);

		}

		public function estado(){

			$datos = [
				'mensaje' => $_SESSION['postulacion_mensaje'] ?? null,
				'postulaciones' => null,
				'email_consultado' => null,
				'dni_consultado' => null,
			];
			unset($_SESSION['postulacion_mensaje']);

			$this->vista('inc/head', $datos);
			$this->vista('portal/nav', $datos);
			$this->vista('portal/estado', $datos);
			$this->vista('inc/foot', $datos);

		}

		/** Consultar el estado de mis postulaciones (seccion 1.1) -- exige correo Y DNI,
		 * no solo el correo (pedido de Ytalo, 2026-07-16: con solo el correo, cualquiera
		 * que lo supiera/adivinara podia ver en que vacantes postulo alguien, su estado
		 * en el proceso, y llegar al link de "Ver mis evaluaciones" -- bastante mas
		 * sensible que el gap ya corregido en Portal::cargarDatos()). Mismo criterio de
		 * mensaje generico ya establecido: si no coincide, no se distingue por que. **/
		public function consultarEstado(){

			$email = trim($_POST['email'] ?? '');
			$dni = trim($_POST['dni'] ?? '');

			$candidato = $email !== '' ? $this->candidatoModelo->buscarPorEmail($email) : null;
			$coincide = $candidato && !empty($candidato->dni) && $dni !== '' && $candidato->dni === $dni;

			$datos = [
				'mensaje' => null,
				'postulaciones' => $coincide ? $this->postulacionModelo->listarPorEmail($email) : [],
				'email_consultado' => $email,
				'dni_consultado' => $dni,
			];

			$this->vista('inc/head', $datos);
			$this->vista('portal/nav', $datos);
			$this->vista('portal/estado', $datos);
			$this->vista('inc/foot', $datos);

		}

		private function rebotar($vacante_id, $mensaje){
			$_SESSION['postular_error'] = $mensaje;
			redirect('portal/postular/'.$vacante_id);
		}

		private function filasExperiencia(){
			$empresas = $_POST['experiencia_empresa'] ?? [];
			$filas = [];
			foreach($empresas as $i => $empresa){
				$filas[] = [
					'empresa' => trim($empresa),
					'cargo' => trim($_POST['experiencia_cargo'][$i] ?? ''),
					'fecha_inicio' => $_POST['experiencia_inicio'][$i] ?? null,
					'fecha_fin' => $_POST['experiencia_fin'][$i] ?? null,
					'actualidad' => isset($_POST['experiencia_actual'][$i]),
					'descripcion' => trim($_POST['experiencia_descripcion'][$i] ?? ''),
				];
			}
			return $filas;
		}

		private function filasEducacion(){
			$instituciones = $_POST['educacion_institucion'] ?? [];
			$filas = [];
			foreach($instituciones as $i => $institucion){
				$filas[] = [
					'institucion' => trim($institucion),
					'grado' => trim($_POST['educacion_grado'][$i] ?? ''),
					'campo_estudio' => trim($_POST['educacion_campo'][$i] ?? ''),
					'fecha_inicio' => $_POST['educacion_inicio'][$i] ?? null,
					'fecha_fin' => $_POST['educacion_fin'][$i] ?? null,
				];
			}
			return $filas;
		}

		/** Valida y guarda el CV (seccion 6.1): solo PDF, maximo 300 KB, valida extension y MIME real **/
		private function procesarCV($archivo, $vacante_id){

			if(!$archivo || $archivo['error'] === UPLOAD_ERR_NO_FILE){
				$this->rebotar($vacante_id, 'Debes adjuntar tu CV en formato PDF.');
			}

			if($archivo['error'] !== UPLOAD_ERR_OK){
				$this->rebotar($vacante_id, 'Ocurrió un error al subir tu CV. Inténtalo nuevamente.');
			}

			if($archivo['size'] > $this->pesoMaximoCV){
				$this->rebotar($vacante_id, 'Tu CV no debe pesar más de 300 KB. Sube una versión reducida, de una sola hoja.');
			}

			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $archivo['tmp_name']);
			finfo_close($finfo);

			if($mime !== 'application/pdf'){
				$this->rebotar($vacante_id, 'Tu CV debe estar en formato PDF.');
			}

			$nombreArchivo = 'cv_'.bin2hex(random_bytes(8)).'.pdf';
			$rutaRelativa = 'public/uploads/cv/'.$nombreArchivo;
			move_uploaded_file($archivo['tmp_name'], RUTA_DOCUMENTO.$rutaRelativa);

			return ['ruta' => $rutaRelativa, 'peso_kb' => (int) round($archivo['size'] / 1024)];

		}

		/**
		 * Resuelve el CV a guardar en la postulacion final: si se subio un archivo nuevo
		 * en este envio, lo valida y usa; si no, reutiliza el que ya se proceso al
		 * autocompletar (extraerCV), para no obligar a subirlo dos veces.
		 */
		/**
		 * Rediseño 2026-07-15 (seccion 6.4 del CLAUDE.md: "si ya subió su CV...
		 * no debe repetir el proceso en cada nueva postulación, solo actualizarlo
		 * si quiere"). Antes SIEMPRE se exigia subir un CV, aunque el candidato
		 * (mismo correo) ya tuviera uno registrado de una postulacion anterior --
		 * cada postulacion nueva insertaba una fila mas en postulante_cv (y un
		 * archivo fisico mas en disco) para la misma persona. Ahora, si no sube
		 * nada nuevo Y ya tiene un CV en su perfil, se reutiliza el existente sin
		 * duplicar nada -- retorna null como señal de "nada que registrar, ya
		 * tiene uno". Solo se exige subir uno si es realmente su primera vez.
		 **/
		private function resolverCV($vacante_id, $tieneCVExistente){

			$archivo = $_FILES['cv'] ?? null;
			if($archivo && $archivo['error'] !== UPLOAD_ERR_NO_FILE){
				return $this->procesarCV($archivo, $vacante_id);
			}

			if(!empty($_SESSION['cv_draft']['archivo_path'])){
				return [
					'ruta' => $_SESSION['cv_draft']['archivo_path'],
					'peso_kb' => $_SESSION['cv_draft']['peso_kb'],
				];
			}

			if($tieneCVExistente){
				return null;
			}

			$this->rebotar($vacante_id, 'Debes adjuntar tu CV en formato PDF.');

		}

		private function habilidadesPost(){
			$texto = trim($_POST['habilidades'] ?? '');
			if($texto === '') return [];
			return array_map('trim', explode(',', $texto));
		}

	}

?>
