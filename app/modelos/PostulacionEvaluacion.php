<?php

class PostulacionEvaluacion{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			public function crear($postulacion_id, $candidato_evaluacion_id, $estado){
				$this->db->query('
					INSERT INTO postulacion_evaluacion (postulacion_id, candidato_evaluacion_id, estado)
					VALUES (:postulacion_id, :candidato_evaluacion_id, :estado)
				');
				$this->db->bind(':postulacion_id', $postulacion_id);
				$this->db->bind(':candidato_evaluacion_id', $candidato_evaluacion_id);
				$this->db->bind(':estado', $estado);
				$this->db->execute();
				return (int) $this->db->ultimoId();
			}

			/** Listado para el link del candidato (seccion 3.4) y para el pipeline del seleccionador **/
			public function listarPorPostulacion($postulacion_id){
				$this->db->query('
					SELECT pe.id, pe.estado, pe.candidato_evaluacion_id,
					       ce.fecha_realizada, ce.resultado_json, ce.estado AS candidato_evaluacion_estado,
					       ev.id AS evaluacion_id, ev.nombre AS evaluacion_nombre, ev.tipo, ev.tiempo_limite_min
					FROM postulacion_evaluacion pe
					INNER JOIN candidato_evaluacion ce ON ce.id = pe.candidato_evaluacion_id
					INNER JOIN evaluaciones ev ON ev.id = ce.evaluacion_id
					WHERE pe.postulacion_id = :postulacion_id
					ORDER BY ev.nombre
				');
				$this->db->bind(':postulacion_id', $postulacion_id);
				return $this->db->registros();
			}

			public function obtener($id){
				$this->db->query('
					SELECT pe.id, pe.postulacion_id, pe.estado, pe.candidato_evaluacion_id, pe.fecha_inicio,
					       ce.candidato_id, ce.evaluacion_id,
					       ev.nombre AS evaluacion_nombre, ev.tipo, ev.tiempo_limite_min, ev.instrucciones, ev.vigencia_meses
					FROM postulacion_evaluacion pe
					INNER JOIN candidato_evaluacion ce ON ce.id = pe.candidato_evaluacion_id
					INNER JOIN evaluaciones ev ON ev.id = ce.evaluacion_id
					WHERE pe.id = :id
				');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			public function marcarEstado($id, $estado){
				$this->db->query('UPDATE postulacion_evaluacion SET estado = :estado WHERE id = :id');
				$this->db->bind(':estado', $estado);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Marca el inicio del intento (para el temporizador), solo la primera vez **/
			public function iniciar($id){
				$this->db->query("
					UPDATE postulacion_evaluacion SET estado = 'en_progreso', fecha_inicio = CURRENT_TIMESTAMP
					WHERE id = :id AND fecha_inicio IS NULL
				");
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Reapunta a un nuevo intento (candidato_evaluacion) - usado al forzar una evaluacion (seccion 3.3) **/
			public function reasignarCandidatoEvaluacion($id, $candidato_evaluacion_id){
				$this->db->query("UPDATE postulacion_evaluacion SET candidato_evaluacion_id = :ce, estado = 'pendiente' WHERE id = :id");
				$this->db->bind(':ce', $candidato_evaluacion_id);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

 }

?>
