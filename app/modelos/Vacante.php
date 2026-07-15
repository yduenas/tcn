<?php

class Vacante{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			private $seleccion = '
				SELECT v.id, v.titulo, v.objetivo_puesto, v.funciones, v.ubicacion, v.salario_min, v.salario_max,
				       v.es_anonima, v.ocultar_salario, v.estado, v.fecha_publicacion, v.fecha_cierre, v.fecha_creacion,
				       v.empresa_id, e.nombre AS empresa_nombre, e.estado AS empresa_estado,
				       v.cargo_categoria_id, cc.nombre AS cargo_nombre,
				       v.modalidad_id, mt.nombre AS modalidad_nombre,
				       v.seleccionador_id, u.nombres AS seleccionador_nombres, u.apellidos AS seleccionador_apellidos, u.email AS seleccionador_email
				FROM vacantes v
				INNER JOIN empresas e ON e.id = v.empresa_id
				INNER JOIN cargo_categorias cc ON cc.id = v.cargo_categoria_id
				INNER JOIN modalidades_trabajo mt ON mt.id = v.modalidad_id
				INNER JOIN usuarios u ON u.id = v.seleccionador_id
			';

			/** Listado completo para el panel del seleccionador/administrador **/
			public function listar(){
				$this->db->query($this->seleccion.' ORDER BY v.fecha_creacion DESC');
				return $this->db->registros();
			}

			private $seleccionPublica = '
				SELECT v.id, v.titulo, v.objetivo_puesto, v.funciones, v.ubicacion, v.salario_min, v.salario_max,
				       v.es_anonima, v.ocultar_salario, v.fecha_publicacion,
				       e.nombre AS empresa_nombre, e.logo_path AS empresa_logo,
				       cc.id AS cargo_categoria_id, cc.nombre AS cargo_nombre,
				       mt.id AS modalidad_id, mt.nombre AS modalidad_nombre,
				       u.nombres AS seleccionador_nombres, u.apellidos AS seleccionador_apellidos, u.email AS seleccionador_email
				FROM vacantes v
				INNER JOIN empresas e ON e.id = v.empresa_id
				INNER JOIN cargo_categorias cc ON cc.id = v.cargo_categoria_id
				INNER JOIN modalidades_trabajo mt ON mt.id = v.modalidad_id
				INNER JOIN usuarios u ON u.id = v.seleccionador_id
				WHERE v.estado = \'publicada\'
			';

			/** Portal publico (seccion 1.1): solo vacantes publicadas, con filtros opcionales **/
			public function listarPublicas($filtros = []){
				$sql = $this->seleccionPublica;
				$params = [];

				if(!empty($filtros['cargo_categoria_id'])){
					$sql .= ' AND v.cargo_categoria_id = :cargo_categoria_id';
					$params[':cargo_categoria_id'] = $filtros['cargo_categoria_id'];
				}
				if(!empty($filtros['modalidad_id'])){
					$sql .= ' AND v.modalidad_id = :modalidad_id';
					$params[':modalidad_id'] = $filtros['modalidad_id'];
				}
				if(!empty($filtros['ubicacion'])){
					$sql .= ' AND v.ubicacion LIKE :ubicacion';
					$params[':ubicacion'] = '%'.$filtros['ubicacion'].'%';
				}
				$sql .= ' ORDER BY v.fecha_publicacion DESC';

				$this->db->query($sql);
				foreach($params as $clave => $valor){
					$this->db->bind($clave, $valor);
				}
				return $this->db->registros();
			}

			/** Ubicaciones distintas entre las vacantes publicadas, para el select con busqueda (Select2)
			 * del filtro de ubicacion en el portal publico (seccion 1.1) **/
			public function listarUbicacionesPublicadas(){
				$this->db->query("
					SELECT DISTINCT ubicacion FROM vacantes
					WHERE estado = 'publicada' AND ubicacion IS NOT NULL AND TRIM(ubicacion) != ''
					ORDER BY ubicacion
				");
				return $this->db->registros();
			}

			/** Detalle publico de una vacante; null si no existe o no esta publicada **/
			public function obtenerPublica($id){
				$this->db->query($this->seleccionPublica.' AND v.id = :id');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			public function obtener($id){
				$this->db->query($this->seleccion.' WHERE v.id = :id');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			public function crear($datos){
				$this->db->query('
					INSERT INTO vacantes (
						empresa_id, titulo, objetivo_puesto, funciones, cargo_categoria_id, modalidad_id, ubicacion,
						salario_min, salario_max, es_anonima, ocultar_salario, estado, seleccionador_id
					) VALUES (
						:empresa_id, :titulo, :objetivo_puesto, :funciones, :cargo_categoria_id, :modalidad_id, :ubicacion,
						:salario_min, :salario_max, :es_anonima, :ocultar_salario, \'borrador\', :seleccionador_id
					)
				');
				$this->bindDatos($datos);
				$this->db->execute();
				return (int) $this->db->ultimoId();
			}

			public function actualizar($id, $datos){
				$this->db->query('
					UPDATE vacantes SET
						empresa_id = :empresa_id, titulo = :titulo,
						objetivo_puesto = :objetivo_puesto, funciones = :funciones,
						cargo_categoria_id = :cargo_categoria_id, modalidad_id = :modalidad_id, ubicacion = :ubicacion,
						salario_min = :salario_min, salario_max = :salario_max,
						es_anonima = :es_anonima, ocultar_salario = :ocultar_salario, seleccionador_id = :seleccionador_id
					WHERE id = :id
				');
				$this->bindDatos($datos);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			private function bindDatos($datos){
				$this->db->bind(':empresa_id', $datos['empresa_id']);
				$this->db->bind(':titulo', $datos['titulo']);
				$this->db->bind(':objetivo_puesto', $datos['objetivo_puesto']);
				$this->db->bind(':funciones', $datos['funciones']);
				$this->db->bind(':cargo_categoria_id', $datos['cargo_categoria_id']);
				$this->db->bind(':modalidad_id', $datos['modalidad_id']);
				$this->db->bind(':ubicacion', $datos['ubicacion']);
				$this->db->bind(':salario_min', $datos['salario_min']);
				$this->db->bind(':salario_max', $datos['salario_max']);
				$this->db->bind(':es_anonima', $datos['es_anonima'] ? 1 : 0);
				$this->db->bind(':ocultar_salario', $datos['ocultar_salario'] ? 1 : 0);
				$this->db->bind(':seleccionador_id', $datos['seleccionador_id']);
			}

			/** Publicar/despublicar (seccion 2: mismo permiso 'publicar_vacante' cubre ambas acciones) **/
			public function publicar($id){
				$this->db->query("UPDATE vacantes SET estado = 'publicada', fecha_publicacion = CURRENT_TIMESTAMP WHERE id = :id");
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			public function despublicar($id){
				$this->db->query("UPDATE vacantes SET estado = 'despublicada' WHERE id = :id");
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			public function cerrar($id){
				$this->db->query("UPDATE vacantes SET estado = 'cerrada', fecha_cierre = CURRENT_TIMESTAMP WHERE id = :id");
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Reabre una vacante cerrada; queda despublicada (el seleccionador decide cuando publicarla de nuevo) **/
			public function reabrir($id){
				$this->db->query("UPDATE vacantes SET estado = 'despublicada', fecha_cierre = NULL WHERE id = :id");
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Baja logica de empresa (seccion 1.4): despublica en cascada sus vacantes publicadas **/
			public function despublicarActivasPorEmpresa($empresa_id){
				$this->db->query("UPDATE vacantes SET estado = 'despublicada' WHERE empresa_id = :empresa_id AND estado = 'publicada'");
				$this->db->bind(':empresa_id', $empresa_id);
				return $this->db->execute();
			}

			/** Evaluaciones asignadas a la vacante (seccion 1.2: al crear la vacante se elige que evaluaciones asignar) **/
			public function evaluacionesAsignadas($vacante_id){
				$this->db->query('
					SELECT ve.evaluacion_id, ve.obligatoria, ev.nombre, ev.tipo
					FROM vacante_evaluacion ve
					INNER JOIN evaluaciones ev ON ev.id = ve.evaluacion_id
					WHERE ve.vacante_id = :vacante_id
				');
				$this->db->bind(':vacante_id', $vacante_id);
				return $this->db->registros();
			}

			/** Reemplaza el conjunto de evaluaciones asignadas por la lista de ids dada **/
			public function asignarEvaluaciones($vacante_id, array $evaluacion_ids){
				$this->db->query('DELETE FROM vacante_evaluacion WHERE vacante_id = :vacante_id');
				$this->db->bind(':vacante_id', $vacante_id);
				$this->db->execute();

				foreach($evaluacion_ids as $evaluacion_id){
					$this->db->query('INSERT INTO vacante_evaluacion (vacante_id, evaluacion_id, obligatoria) VALUES (:vacante_id, :evaluacion_id, 1)');
					$this->db->bind(':vacante_id', $vacante_id);
					$this->db->bind(':evaluacion_id', $evaluacion_id);
					$this->db->execute();
				}
			}

			/** Competencias que se evaluan en la entrevista para esta vacante (seccion 1.2, pedido de
			 * Ytalo 2026-07-15: "así como seleccionas las pruebas, deberías seleccionar las competencias") **/
			public function competenciasAsignadas($vacante_id){
				$this->db->query('
					SELECT vc.competencia_id, c.nombre, c.activo
					FROM vacante_competencia vc
					INNER JOIN competencias c ON c.id = vc.competencia_id
					WHERE vc.vacante_id = :vacante_id
				');
				$this->db->bind(':vacante_id', $vacante_id);
				return $this->db->registros();
			}

			/** Reemplaza el conjunto de competencias asignadas por la lista de ids dada **/
			public function asignarCompetencias($vacante_id, array $competencia_ids){
				$this->db->query('DELETE FROM vacante_competencia WHERE vacante_id = :vacante_id');
				$this->db->bind(':vacante_id', $vacante_id);
				$this->db->execute();

				foreach($competencia_ids as $competencia_id){
					$this->db->query('INSERT INTO vacante_competencia (vacante_id, competencia_id) VALUES (:vacante_id, :competencia_id)');
					$this->db->bind(':vacante_id', $vacante_id);
					$this->db->bind(':competencia_id', $competencia_id);
					$this->db->execute();
				}
			}

 }

?>
