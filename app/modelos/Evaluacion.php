<?php

class Evaluacion{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			public function listar(){
				$this->db->query('SELECT id, nombre, tipo, tiempo_limite_min, instrucciones, vigencia_meses FROM evaluaciones ORDER BY nombre');
				return $this->db->registros();
			}

			public function obtener($id){
				$this->db->query('SELECT id, nombre, tipo, tiempo_limite_min, instrucciones, vigencia_meses FROM evaluaciones WHERE id = :id');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			/** Preguntas + opciones + competencia (si aplica), listas para renderizar o calificar la prueba **/
			public function preguntasCompletas($evaluacion_id){
				$this->db->query('SELECT id, enunciado, tipo_pregunta, orden FROM evaluacion_preguntas WHERE evaluacion_id = :id ORDER BY orden');
				$this->db->bind(':id', $evaluacion_id);
				$preguntas = $this->db->registros();

				foreach($preguntas as $pregunta){
					$this->db->query('SELECT id, texto, puntaje, etiqueta, orden FROM evaluacion_opciones WHERE pregunta_id = :id ORDER BY orden');
					$this->db->bind(':id', $pregunta->id);
					$pregunta->opciones = $this->db->registros();

					$this->db->query('SELECT competencia_nombre FROM evaluacion_competencias WHERE pregunta_id = :id');
					$this->db->bind(':id', $pregunta->id);
					$competencia = $this->db->registro();
					$pregunta->competencia_nombre = $competencia->competencia_nombre ?? null;
				}

				return $preguntas;
			}

			/** Una pregunta puntual con sus opciones y competencia (para editarla en el panel) **/
			public function obtenerPregunta($pregunta_id){
				$this->db->query('SELECT id, evaluacion_id, enunciado, tipo_pregunta, orden FROM evaluacion_preguntas WHERE id = :id');
				$this->db->bind(':id', $pregunta_id);
				$pregunta = $this->db->registro();
				if(!$pregunta) return null;

				$this->db->query('SELECT id, texto, puntaje, etiqueta, orden FROM evaluacion_opciones WHERE pregunta_id = :id ORDER BY orden');
				$this->db->bind(':id', $pregunta_id);
				$pregunta->opciones = $this->db->registros();

				$this->db->query('SELECT competencia_nombre FROM evaluacion_competencias WHERE pregunta_id = :id');
				$this->db->bind(':id', $pregunta_id);
				$competencia = $this->db->registro();
				$pregunta->competencia_nombre = $competencia->competencia_nombre ?? null;

				return $pregunta;
			}

			public function contarPreguntas($evaluacion_id){
				$this->db->query('SELECT COUNT(*) AS total FROM evaluacion_preguntas WHERE evaluacion_id = :id');
				$this->db->bind(':id', $evaluacion_id);
				return (int) $this->db->registro()->total;
			}

			/** Crea una pregunta con sus opciones (y competencia, si aplica). $opciones: [['texto'=>, 'puntaje'=>, 'etiqueta'=>], ...] **/
			public function crearPregunta($evaluacion_id, $enunciado, $tipo_pregunta, array $opciones, $competencia_nombre = null){
				$orden = $this->contarPreguntas($evaluacion_id) + 1;

				$this->db->query('INSERT INTO evaluacion_preguntas (evaluacion_id, enunciado, tipo_pregunta, orden) VALUES (:evaluacion_id, :enunciado, :tipo, :orden)');
				$this->db->bind(':evaluacion_id', $evaluacion_id);
				$this->db->bind(':enunciado', $enunciado);
				$this->db->bind(':tipo', $tipo_pregunta);
				$this->db->bind(':orden', $orden);
				$this->db->execute();
				$pregunta_id = (int) $this->db->ultimoId();

				$this->guardarOpciones($pregunta_id, $opciones);

				if($competencia_nombre){
					$this->db->query('INSERT INTO evaluacion_competencias (pregunta_id, competencia_nombre) VALUES (:pregunta_id, :competencia)');
					$this->db->bind(':pregunta_id', $pregunta_id);
					$this->db->bind(':competencia', $competencia_nombre);
					$this->db->execute();
				}

				return $pregunta_id;
			}

			public function actualizarPregunta($pregunta_id, $enunciado, array $opciones, $competencia_nombre = null){
				$this->db->query('UPDATE evaluacion_preguntas SET enunciado = :enunciado WHERE id = :id');
				$this->db->bind(':enunciado', $enunciado);
				$this->db->bind(':id', $pregunta_id);
				$this->db->execute();

				$this->db->query('DELETE FROM evaluacion_opciones WHERE pregunta_id = :id');
				$this->db->bind(':id', $pregunta_id);
				$this->db->execute();
				$this->guardarOpciones($pregunta_id, $opciones);

				if($competencia_nombre){
					$this->db->query('DELETE FROM evaluacion_competencias WHERE pregunta_id = :id');
					$this->db->bind(':id', $pregunta_id);
					$this->db->execute();
					$this->db->query('INSERT INTO evaluacion_competencias (pregunta_id, competencia_nombre) VALUES (:pregunta_id, :competencia)');
					$this->db->bind(':pregunta_id', $pregunta_id);
					$this->db->bind(':competencia', $competencia_nombre);
					$this->db->execute();
				}
			}

			private function guardarOpciones($pregunta_id, array $opciones){
				foreach(array_values($opciones) as $i => $opcion){
					if(trim($opcion['texto'] ?? '') === '') continue;
					$this->db->query('
						INSERT INTO evaluacion_opciones (pregunta_id, texto, puntaje, etiqueta, orden)
						VALUES (:pregunta_id, :texto, :puntaje, :etiqueta, :orden)
					');
					$this->db->bind(':pregunta_id', $pregunta_id);
					$this->db->bind(':texto', $opcion['texto']);
					$this->db->bind(':puntaje', $opcion['puntaje'] ?? 0);
					$this->db->bind(':etiqueta', $opcion['etiqueta'] ?? null);
					$this->db->bind(':orden', $i + 1);
					$this->db->execute();
				}
			}

			/** Vigencia editable desde el panel (seccion 1.4: configuracion general) **/
			public function actualizarVigencia($evaluacion_id, $vigencia_meses){
				$this->db->query('UPDATE evaluaciones SET vigencia_meses = :vigencia WHERE id = :id');
				$this->db->bind(':vigencia', $vigencia_meses ?: null);
				$this->db->bind(':id', $evaluacion_id);
				return $this->db->execute();
			}

 }

?>
