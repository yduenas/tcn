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
			/** Arranca el reloj de verdad -- unico llamador es Evaluaciones::iniciar() (controlador),
			 * que ya valida estado='pendiente' antes de llamar esto (disparado solo por el POST real
			 * del boton "Comenzar ahora", nunca por una simple carga de pagina, ver Evaluaciones::tomar()).
			 * Por eso NO lleva guardado "WHERE fecha_inicio IS NULL": ese guardado (pensado para una
			 * epoca en que esto se llamaba automaticamente en cada GET) quedo bloqueando reinicios
			 * legitimos si la fila ya traia una fecha_inicio vieja (de un intento anterior, forzado o
			 * no) -- el candidato quedaba en un limbo real donde "Comenzar ahora" no hacia nada y
			 * tampoco aparecia "Reintentar" para el reclutador. Bug real reportado por Ytalo, 2026-07-16. **/
			public function iniciar($id){
				$this->db->query("UPDATE postulacion_evaluacion SET estado = 'en_progreso', fecha_inicio = CURRENT_TIMESTAMP WHERE id = :id");
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Reapunta a un nuevo intento (candidato_evaluacion) - usado al forzar/reintentar una evaluacion (seccion 3.3).
			 * fecha_inicio SIEMPRE se limpia a NULL aqui -- si quedaba la fecha del intento anterior (vencido o no),
			 * iniciar() (guardado por "WHERE fecha_inicio IS NULL") nunca volvia a arrancar el reloj: el boton
			 * "Comenzar ahora" quedaba sin efecto (o, antes de la pantalla de confirmacion, el candidato caia
			 * directo en "se agoto el tiempo" calculado sobre la fecha vieja). Bug real reportado por Ytalo, 2026-07-16. **/
			public function reasignarCandidatoEvaluacion($id, $candidato_evaluacion_id){
				$this->db->query("UPDATE postulacion_evaluacion SET candidato_evaluacion_id = :ce, estado = 'pendiente', fecha_inicio = NULL WHERE id = :id");
				$this->db->bind(':ce', $candidato_evaluacion_id);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

 }

?>
