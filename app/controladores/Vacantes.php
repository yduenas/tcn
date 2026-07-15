<?php
	session_start();

	class Vacantes extends Controlador{

		public function __construct(){

			requiereLogin();

			$this->vacanteModelo = $this->modelo('Vacante');
			$this->empresaModelo = $this->modelo('Empresa');
			$this->cargoCategoriaModelo = $this->modelo('CargoCategoria');
			$this->modalidadTrabajoModelo = $this->modelo('ModalidadTrabajo');
			$this->evaluacionModelo = $this->modelo('Evaluacion');
			$this->usuarioModelo = $this->modelo('Usuario');
			$this->competenciaModelo = $this->modelo('Competencia');

		}

		public function index(){

			requierePermiso('crear_vacante');

			$datos = [
				'vacantes' => $this->vacanteModelo->listar()
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('vacantes/listado', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function nuevo(){

			requierePermiso('crear_vacante');

			$datos = [
				'vacante' => null,
				'empresas' => $this->empresaModelo->listarActivas(),
				'cargos' => $this->cargoCategoriaModelo->listarActivas(),
				'modalidades' => $this->modalidadTrabajoModelo->listarActivas(),
				'evaluaciones' => $this->evaluacionModelo->listar(),
				'evaluaciones_asignadas' => [],
				'competencias' => $this->competenciaModelo->listar(),
				'competencias_asignadas' => [],
				'seleccionadores' => $this->usuarioModelo->listarSeleccionadoresActivos(),
				'error' => $_SESSION['vacante_error'] ?? null
			];
			unset($_SESSION['vacante_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('vacantes/formulario', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function ver($id){

			requierePermiso('crear_vacante');

			$vacante = $this->vacanteModelo->obtener($id);
			if(!$vacante){
				redirect('vacantes/index');
			}

			$datos = ['vacante' => $vacante];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('vacantes/detalle', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function editar($id){

			requierePermiso('editar_vacante');

			$vacante = $this->vacanteModelo->obtener($id);
			if(!$vacante){
				redirect('vacantes/index');
			}

			$datos = [
				'vacante' => $vacante,
				'empresas' => $this->empresaModelo->listarActivas(),
				'cargos' => $this->cargoCategoriaModelo->listarActivas($vacante->cargo_categoria_id),
				'modalidades' => $this->modalidadTrabajoModelo->listarActivas($vacante->modalidad_id),
				'evaluaciones' => $this->evaluacionModelo->listar(),
				'evaluaciones_asignadas' => array_column($this->vacanteModelo->evaluacionesAsignadas($id), 'evaluacion_id'),
				'competencias' => $this->competenciaModelo->listar(),
				'competencias_asignadas' => array_column($this->vacanteModelo->competenciasAsignadas($id), 'competencia_id'),
				'seleccionadores' => $this->usuarioModelo->listarSeleccionadoresActivos(),
				'error' => $_SESSION['vacante_error'] ?? null
			];
			unset($_SESSION['vacante_error']);

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('vacantes/formulario', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function guardar(){

			requierePermiso('crear_vacante');

			$datos = $this->datosDesdePost();

			if($error = $this->validar($datos)){
				$_SESSION['vacante_error'] = $error;
				redirect('vacantes/nuevo');
			}

			$vacante_id = $this->vacanteModelo->crear($datos);
			$this->vacanteModelo->asignarEvaluaciones($vacante_id, $_POST['evaluaciones'] ?? []);
			$this->vacanteModelo->asignarCompetencias($vacante_id, $_POST['competencias'] ?? []);

			redirect('vacantes/index');

		}

		public function actualizar($id){

			requierePermiso('editar_vacante');

			$datos = $this->datosDesdePost();

			if($error = $this->validar($datos)){
				$_SESSION['vacante_error'] = $error;
				redirect('vacantes/editar/'.$id);
			}

			$this->vacanteModelo->actualizar($id, $datos);
			$this->vacanteModelo->asignarEvaluaciones($id, $_POST['evaluaciones'] ?? []);
			$this->vacanteModelo->asignarCompetencias($id, $_POST['competencias'] ?? []);

			redirect('vacantes/index');

		}

		public function publicar($id){

			requierePermiso('publicar_vacante');

			$vacante = $this->vacanteModelo->obtener($id);
			if($vacante && $vacante->empresa_estado === 'activa'){
				$this->vacanteModelo->publicar($id);
			}

			redirect('vacantes/index');

		}

		public function despublicar($id){

			requierePermiso('publicar_vacante');

			$this->vacanteModelo->despublicar($id);

			redirect('vacantes/index');

		}

		public function cerrar($id){

			requierePermiso('publicar_vacante');

			$this->vacanteModelo->cerrar($id);

			redirect('vacantes/index');

		}

		public function reabrir($id){

			requierePermiso('publicar_vacante');

			$this->vacanteModelo->reabrir($id);

			redirect('vacantes/index');

		}

		private function datosDesdePost(){
			return [
				'empresa_id' => $_POST['empresa_id'] ?? null,
				'titulo' => trim($_POST['titulo'] ?? ''),
				'objetivo_puesto' => sanitizarHtmlBasico(trim($_POST['objetivo_puesto'] ?? '')),
				'funciones' => sanitizarHtmlBasico(trim($_POST['funciones'] ?? '')),
				'cargo_categoria_id' => $_POST['cargo_categoria_id'] ?? null,
				'modalidad_id' => $_POST['modalidad_id'] ?? null,
				'ubicacion' => trim($_POST['ubicacion'] ?? ''),
				'salario_min' => $_POST['salario_min'] !== '' ? $_POST['salario_min'] : null,
				'salario_max' => $_POST['salario_max'] !== '' ? $_POST['salario_max'] : null,
				'es_anonima' => isset($_POST['es_anonima']),
				'ocultar_salario' => isset($_POST['ocultar_salario']),
				'seleccionador_id' => $_POST['seleccionador_id'] ?? null,
			];
		}

		/** Toda vacante debe quedar asignada a un usuario con perfil "Seleccionador" -- pedido de Ytalo,
		 *  2026-07-14. Antes se forzaba en silencio a quien la creaba (podia ser un Administrador,
		 *  que no es Seleccionador) -- ahora es un <select> explicito en el formulario, validado aqui
		 *  contra la BD (no basta con que el <select> lo ofrezca, por si llega un id ajeno a mano). **/
		private function validar($datos){
			if(!$datos['empresa_id'] || $datos['titulo'] === '' || !$datos['cargo_categoria_id'] || !$datos['modalidad_id']){
				return 'Empresa, título, categoría de cargo y modalidad son obligatorios.';
			}
			if(!$datos['seleccionador_id']){
				return 'Debes asignar un Seleccionador responsable de la vacante.';
			}
			if(!$this->usuarioModelo->esSeleccionadorActivo($datos['seleccionador_id'])){
				return 'El Seleccionador elegido no es válido (debe ser un usuario activo con perfil Seleccionador).';
			}
			return null;
		}

	}

?>
