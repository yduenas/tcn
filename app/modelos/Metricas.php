<?php

class Metricas{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			/** KPIs globales para Administrador/Seleccionador **/
			public function kpisGlobales(){
				return (object) [
					'empresas_activas' => $this->contar("SELECT COUNT(*) AS total FROM empresas WHERE estado = 'activa'"),
					'vacantes_publicadas' => $this->contar("SELECT COUNT(*) AS total FROM vacantes WHERE estado = 'publicada'"),
					'vacantes_totales' => $this->contar("SELECT COUNT(*) AS total FROM vacantes"),
					'postulaciones_totales' => $this->contar("SELECT COUNT(*) AS total FROM postulaciones"),
					'usuarios_activos' => $this->contar("SELECT COUNT(*) AS total FROM usuarios WHERE estado = 'activo'"),
				];
			}

			public function postulacionesPorEstado(){
				$this->db->query('
					SELECT ep.nombre AS estado_nombre, COUNT(p.id) AS cantidad
					FROM estados_postulacion ep
					LEFT JOIN postulaciones p ON p.estado_id = ep.id
					GROUP BY ep.id
					ORDER BY ep.orden
				');
				return $this->db->registros();
			}

			public function vacantesRecientes($limite = 5){
				$this->db->query('
					SELECT v.id, v.titulo, v.estado, v.fecha_creacion, e.nombre AS empresa_nombre
					FROM vacantes v
					INNER JOIN empresas e ON e.id = v.empresa_id
					ORDER BY v.fecha_creacion DESC
					LIMIT :limite
				');
				$this->db->bind(':limite', $limite);
				return $this->db->registros();
			}

			/** KPIs de una empresa cliente (seccion 1.3: dashboard por vacante, historico y metricas basicas) **/
			public function kpisEmpresa($empresa_id){
				return (object) [
					'vacantes_publicadas' => $this->contar("SELECT COUNT(*) AS total FROM vacantes WHERE empresa_id = :e AND estado = 'publicada'", [':e' => $empresa_id]),
					'vacantes_cerradas' => $this->contar("SELECT COUNT(*) AS total FROM vacantes WHERE empresa_id = :e AND estado = 'cerrada'", [':e' => $empresa_id]),
					'postulantes_totales' => $this->contar("
						SELECT COUNT(*) AS total FROM postulaciones p
						INNER JOIN vacantes v ON v.id = p.vacante_id
						WHERE v.empresa_id = :e
					", [':e' => $empresa_id]),
				];
			}

			/** Vacantes activas de la empresa con avance por etapa **/
			public function vacantesConAvance($empresa_id){
				$this->db->query("
					SELECT id, titulo, estado FROM vacantes
					WHERE empresa_id = :empresa_id AND estado IN ('publicada', 'despublicada', 'borrador')
					ORDER BY fecha_creacion DESC
				");
				$this->db->bind(':empresa_id', $empresa_id);
				$vacantes = $this->db->registros();

				foreach($vacantes as $vacante){
					$this->db->query('
						SELECT ep.nombre AS estado_nombre, COUNT(p.id) AS cantidad
						FROM estados_postulacion ep
						LEFT JOIN postulaciones p ON p.estado_id = ep.id AND p.vacante_id = :vacante_id
						GROUP BY ep.id
						ORDER BY ep.orden
					');
					$this->db->bind(':vacante_id', $vacante->id);
					$vacante->avance = $this->db->registros();
					$vacante->total_postulantes = array_sum(array_column($vacante->avance, 'cantidad'));
				}

				return $vacantes;
			}

			/** Historico de vacantes cerradas de la empresa **/
			public function vacantesCerradas($empresa_id){
				$this->db->query("
					SELECT v.id, v.titulo, v.fecha_cierre,
					       (SELECT COUNT(*) FROM postulaciones p WHERE p.vacante_id = v.id) AS total_postulantes
					FROM vacantes v
					WHERE v.empresa_id = :empresa_id AND v.estado = 'cerrada'
					ORDER BY v.fecha_cierre DESC
				");
				$this->db->bind(':empresa_id', $empresa_id);
				return $this->db->registros();
			}

			private function contar($sql, $params = []){
				$this->db->query($sql);
				foreach($params as $clave => $valor){
					$this->db->bind($clave, $valor);
				}
				return (int) $this->db->registro()->total;
			}

 }

?>
