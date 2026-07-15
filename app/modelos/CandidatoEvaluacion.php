<?php

class CandidatoEvaluacion{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			/** Evaluacion vigente del candidato, si tiene una (seccion 3.3: no debe repetirla) **/
			public function obtenerVigente($candidato_id, $evaluacion_id){
				$this->db->query("
					SELECT * FROM candidato_evaluacion
					WHERE candidato_id = :candidato_id AND evaluacion_id = :evaluacion_id
					  AND estado = 'vigente'
					  AND (fecha_vencimiento IS NULL OR fecha_vencimiento > CURRENT_TIMESTAMP)
					ORDER BY fecha_realizada DESC LIMIT 1
				");
				$this->db->bind(':candidato_id', $candidato_id);
				$this->db->bind(':evaluacion_id', $evaluacion_id);
				return $this->db->registro();
			}

			public function crearPendiente($candidato_id, $evaluacion_id, $forzada_por_postulacion_id = null){
				$this->db->query('
					INSERT INTO candidato_evaluacion (candidato_id, evaluacion_id, estado, forzada_por_postulacion_id)
					VALUES (:candidato_id, :evaluacion_id, \'pendiente\', :forzada)
				');
				$this->db->bind(':candidato_id', $candidato_id);
				$this->db->bind(':evaluacion_id', $evaluacion_id);
				$this->db->bind(':forzada', $forzada_por_postulacion_id);
				$this->db->execute();
				return (int) $this->db->ultimoId();
			}

			/** Se agotó el tiempo: cierra el intento sin resultado valido (seccion 3, temporizador) **/
			public function marcarVencida($id){
				$this->db->query("UPDATE candidato_evaluacion SET estado = 'vencida', fecha_realizada = CURRENT_TIMESTAMP WHERE id = :id");
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			public function obtener($id){
				$this->db->query('SELECT * FROM candidato_evaluacion WHERE id = :id');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			public function guardarRespuesta($candidato_evaluacion_id, $pregunta_id, $opcion_id, $rol){
				$this->db->query('
					INSERT INTO respuestas_candidato (candidato_evaluacion_id, pregunta_id, opcion_id, rol)
					VALUES (:candidato_evaluacion_id, :pregunta_id, :opcion_id, :rol)
				');
				$this->db->bind(':candidato_evaluacion_id', $candidato_evaluacion_id);
				$this->db->bind(':pregunta_id', $pregunta_id);
				$this->db->bind(':opcion_id', $opcion_id);
				$this->db->bind(':rol', $rol);
				$this->db->execute();
			}

			public function marcarCompletada($id, $resultado, $vigencia_meses){
				$this->db->query("
					UPDATE candidato_evaluacion SET
						fecha_realizada = CURRENT_TIMESTAMP,
						fecha_vencimiento = " . ($vigencia_meses ? "datetime(CURRENT_TIMESTAMP, '+".(int)$vigencia_meses." months')" : "NULL") . ",
						resultado_json = :resultado,
						estado = 'vigente'
					WHERE id = :id
				");
				$this->db->bind(':resultado', json_encode($resultado, JSON_UNESCAPED_UNICODE));
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Nivel cualitativo obligatorio junto al %, nunca el numero solo (seccion 3.3) **/
			public static function nivel($porcentaje){
				if($porcentaje <= 40) return 'Bajo';
				if($porcentaje <= 70) return 'Medio';
				return 'Alto';
			}

			/**
			 * Califica una evaluacion completada. $respuestas: pregunta_id => ['unica'=>opcion_id] | ['mas'=>opcion_id,'menos'=>opcion_id]
			 * $preguntas: salida de Evaluacion::preguntasCompletas() (trae opciones con puntaje/etiqueta y competencia_nombre)
			 */
			public function calificar($tipoEvaluacion, array $preguntas, array $respuestas){

				if($tipoEvaluacion === 'opcion_unica'){
					return $this->calificarOpcionUnica($preguntas, $respuestas);
				}
				if($tipoEvaluacion === 'disc'){
					return $this->calificarDisc($preguntas, $respuestas);
				}
				if($tipoEvaluacion === 'sjt'){
					return $this->calificarSjt($preguntas, $respuestas);
				}

				return ['tipo' => $tipoEvaluacion];
			}

			private function calificarOpcionUnica($preguntas, $respuestas){
				$correctas = 0;
				$total = count($preguntas);

				foreach($preguntas as $pregunta){
					$opcion_id = $respuestas[$pregunta->id]['unica'] ?? null;
					foreach($pregunta->opciones as $opcion){
						if($opcion->id == $opcion_id && $opcion->puntaje > 0){
							$correctas++;
						}
					}
				}

				$porcentaje = $total > 0 ? round(($correctas / $total) * 100, 1) : 0;

				return [
					'tipo' => 'opcion_unica',
					'correctas' => $correctas,
					'total' => $total,
					'porcentaje' => $porcentaje,
					'nivel' => self::nivel($porcentaje),
				];
			}

			private function calificarDisc($preguntas, $respuestas){
				$dimensiones = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];
				$menos = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];
				$total = count($preguntas);

				foreach($preguntas as $pregunta){
					$masId = $respuestas[$pregunta->id]['mas'] ?? null;
					$menosId = $respuestas[$pregunta->id]['menos'] ?? null;
					foreach($pregunta->opciones as $opcion){
						if($opcion->id == $masId && isset($dimensiones[$opcion->etiqueta])){
							$dimensiones[$opcion->etiqueta]++;
						}
						if($opcion->id == $menosId && isset($menos[$opcion->etiqueta])){
							$menos[$opcion->etiqueta]++;
						}
					}
				}

				$resultado = ['tipo' => 'disc', 'dimensiones' => []];
				$dominante = null;
				$maxMas = -1;

				foreach($dimensiones as $letra => $mas){
					$porcentaje = $total > 0 ? round(($mas / $total) * 100, 1) : 0;
					$resultado['dimensiones'][$letra] = [
						'mas' => $mas,
						'menos' => $menos[$letra],
						'porcentaje' => $porcentaje,
					];
					if($mas > $maxMas){
						$maxMas = $mas;
						$dominante = $letra;
					}
				}
				$resultado['perfil_dominante'] = $dominante;

				return $resultado;
			}

			private function calificarSjt($preguntas, $respuestas){
				$porCompetencia = [];

				foreach($preguntas as $pregunta){
					$competencia = $pregunta->competencia_nombre ?? 'General';
					$masId = $respuestas[$pregunta->id]['mas'] ?? null;
					$menosId = $respuestas[$pregunta->id]['menos'] ?? null;

					$puntajeMas = 0;
					$puntajeMenos = 0;
					foreach($pregunta->opciones as $opcion){
						if($opcion->id == $masId) $puntajeMas = $opcion->puntaje;
						if($opcion->id == $menosId) $puntajeMenos = $opcion->puntaje;
					}

					// Maximo posible por pregunta: elegir la mejor como "mas" (3) y la peor como "menos" (0 -> aporta 3)
					$puntajePregunta = $puntajeMas + (3 - $puntajeMenos);

					if(!isset($porCompetencia[$competencia])){
						$porCompetencia[$competencia] = ['puntaje' => 0, 'maximo' => 0];
					}
					$porCompetencia[$competencia]['puntaje'] += $puntajePregunta;
					$porCompetencia[$competencia]['maximo'] += 6;
				}

				$resultado = ['tipo' => 'sjt', 'competencias' => []];
				$puntajeTotal = 0;
				$maximoTotal = 0;

				foreach($porCompetencia as $nombre => $datos){
					$porcentaje = $datos['maximo'] > 0 ? round(($datos['puntaje'] / $datos['maximo']) * 100, 1) : 0;
					$resultado['competencias'][$nombre] = [
						'puntaje' => $datos['puntaje'],
						'maximo' => $datos['maximo'],
						'porcentaje' => $porcentaje,
						'nivel' => self::nivel($porcentaje),
					];
					$puntajeTotal += $datos['puntaje'];
					$maximoTotal += $datos['maximo'];
				}

				$porcentajeGeneral = $maximoTotal > 0 ? round(($puntajeTotal / $maximoTotal) * 100, 1) : 0;
				$resultado['porcentaje_general'] = $porcentajeGeneral;
				$resultado['nivel_general'] = self::nivel($porcentajeGeneral);

				return $resultado;
			}

 }

?>
