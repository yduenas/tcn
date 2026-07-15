<?php

class Entrevista{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			public function obtenerPorPostulacion($postulacion_id){
				$this->db->query('SELECT * FROM entrevistas WHERE postulacion_id = :id ORDER BY id DESC LIMIT 1');
				$this->db->bind(':id', $postulacion_id);
				return $this->db->registro();
			}

			public function crear($postulacion_id, $entrevistador_usuario_id, $fecha_agendada){
				$this->db->query('
					INSERT INTO entrevistas (postulacion_id, entrevistador_usuario_id, fecha_agendada)
					VALUES (:postulacion_id, :entrevistador_usuario_id, :fecha_agendada)
				');
				$this->db->bind(':postulacion_id', $postulacion_id);
				$this->db->bind(':entrevistador_usuario_id', $entrevistador_usuario_id);
				$this->db->bind(':fecha_agendada', $fecha_agendada);
				$this->db->execute();
				return (int) $this->db->ultimoId();
			}

			/** Incluye vacante_id (para filtrar competencias por vacante) y el estado_codigo/es_final
			 * de la postulacion (para bloquear la edicion una vez el proceso llega a un estado
			 * terminal: Contratado, Descartado o Desertó -- pedido de Ytalo, 2026-07-15). **/
			public function obtener($id){
				$this->db->query('
					SELECT e.*, c.nombres AS candidato_nombres, c.apellidos AS candidato_apellidos,
					       v.id AS vacante_id, v.titulo AS vacante_titulo,
					       u.nombres AS entrevistador_nombres, u.apellidos AS entrevistador_apellidos,
					       ep.codigo AS estado_codigo, ep.nombre AS estado_nombre, ep.es_final
					FROM entrevistas e
					INNER JOIN postulaciones p ON p.id = e.postulacion_id
					INNER JOIN candidatos c ON c.id = p.candidato_id
					INNER JOIN vacantes v ON v.id = p.vacante_id
					INNER JOIN usuarios u ON u.id = e.entrevistador_usuario_id
					INNER JOIN estados_postulacion ep ON ep.id = p.estado_id
					WHERE e.id = :id
				');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			public function guardarNotasYRecomendacion($id, $notas, $recomendacion){
				$this->db->query("
					UPDATE entrevistas SET notas = :notas, recomendacion = :recomendacion, fecha_realizada = CURRENT_TIMESTAMP
					WHERE id = :id
				");
				$this->db->bind(':notas', $notas);
				$this->db->bind(':recomendacion', $recomendacion);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			public function listarCalificaciones($entrevista_id){
				$this->db->query('
					SELECT ecc.id, ecc.competencia_id, ecc.calificacion, ecc.comentario, c.nombre AS competencia_nombre
					FROM entrevista_calificacion_competencia ecc
					INNER JOIN competencias c ON c.id = ecc.competencia_id
					WHERE ecc.entrevista_id = :id
				');
				$this->db->bind(':id', $entrevista_id);
				return $this->db->registros();
			}

			/** Reemplaza las calificaciones por competencia de la entrevista **/
			public function guardarCalificaciones($entrevista_id, array $calificaciones){
				$this->db->query('DELETE FROM entrevista_calificacion_competencia WHERE entrevista_id = :id');
				$this->db->bind(':id', $entrevista_id);
				$this->db->execute();

				foreach($calificaciones as $competencia_id => $datos){
					if(empty($datos['calificacion'])) continue;
					$this->db->query('
						INSERT INTO entrevista_calificacion_competencia (entrevista_id, competencia_id, calificacion, comentario)
						VALUES (:entrevista_id, :competencia_id, :calificacion, :comentario)
					');
					$this->db->bind(':entrevista_id', $entrevista_id);
					$this->db->bind(':competencia_id', $competencia_id);
					$this->db->bind(':calificacion', $datos['calificacion']);
					$this->db->bind(':comentario', $datos['comentario'] ?? '');
					$this->db->execute();
				}
			}

 }

?>
