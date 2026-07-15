<?php

/** Modulo "Cron" (2026-07-16): el hosting real de este proyecto no permite crear cron
 * jobs, asi que en vez de un cron de verdad este modulo agrupa tareas de mantenimiento
 * periodico para ejecutarlas a mano desde el panel. Primera tarea: limpiar CVs
 * huerfanos -- "Autocompletar desde mi CV" (Portal::extraerCV()) guarda el archivo
 * fisico en public/uploads/cv/ de inmediato, pero solo queda registrado en
 * postulante_cv si el candidato termina de enviar la postulacion (Portal::enviar()).
 * Si nunca la termina, el archivo queda huerfano en disco para siempre.
 * Nombrado "TareaCron" (no "Cron") porque el controlador ya se llama Cron -- este
 * framework no usa namespaces, asi que 2 clases iguales en archivos distintos
 * revientan con un fatal error de PHP al cargar ambas en el mismo request. **/
class TareaCron{

		private $db;
		private $carpetaCv;

			public function __construct(){
				$this->db = new Base3;
				$this->carpetaCv = RUTA_DOCUMENTO.'public/uploads/cv/';
			}

			private function archivosCv(){
				$archivos = glob($this->carpetaCv.'*.pdf');
				return $archivos ?: [];
			}

			/** Rutas relativas (mismo formato que postulante_cv.archivo_path) ya registradas **/
			private function rutasRegistradas(){
				$this->db->query('SELECT archivo_path FROM postulante_cv');
				$registradas = [];
				foreach($this->db->registros() as $fila){
					$registradas[$fila->archivo_path] = true;
				}
				return $registradas;
			}

			/** Archivos en la carpeta de CVs que no aparecen en ninguna fila de postulante_cv **/
			private function archivosHuerfanos(){
				$registradas = $this->rutasRegistradas();
				$huerfanos = [];
				foreach($this->archivosCv() as $rutaAbsoluta){
					$rutaRelativa = 'public/uploads/cv/'.basename($rutaAbsoluta);
					if(!isset($registradas[$rutaRelativa])){
						$huerfanos[] = $rutaAbsoluta;
					}
				}
				return $huerfanos;
			}

			/** Resumen para el panel: total de huerfanos vs. cuantos ya superan las $horas
			 * (los unicos que la limpieza realmente borra -- un huerfano reciente puede ser
			 * un candidato que todavia esta llenando el formulario, no se toca). **/
			public function resumenCvHuerfanos($horas = 24){
				$limite = time() - $horas * 3600;
				$total = 0;
				$vencidos = 0;
				foreach($this->archivosHuerfanos() as $ruta){
					$total++;
					if(filemtime($ruta) <= $limite){
						$vencidos++;
					}
				}
				return ['total' => $total, 'vencidos' => $vencidos, 'horas' => $horas];
			}

			/** Borra solo los huerfanos con $horas o mas de antiguedad. Devuelve cuantos borro. **/
			public function limpiarCvHuerfanos($horas = 24){
				$limite = time() - $horas * 3600;
				$borrados = 0;
				foreach($this->archivosHuerfanos() as $ruta){
					if(filemtime($ruta) <= $limite){
						if(@unlink($ruta)){
							$borrados++;
						}
					}
				}
				return $borrados;
			}

			/** Candidatos cuyas postulaciones estan TODAS en un estado terminal negativo
			 * (descartado/desertado) -- ningun proceso activo en curso en ninguna vacante --
			 * y cuyo ultimo movimiento ya paso los $dias dias. Segunda tarea del modulo
			 * Cron (2026-07-16): a diferencia de los huerfanos (archivo sin fila en BD),
			 * aca el CV SI esta registrado -- se borra solo porque el candidato ya no
			 * tiene ningun proceso vivo y paso suficiente tiempo. Si vuelve a postular
			 * sin CV en archivo, Portal::enviar() se lo vuelve a pedir con normalidad,
			 * no requiere ningun cambio ahi -- ya maneja ese caso hoy. **/
			private function candidatosCvDescartadoPurgable($dias){
				$this->db->query("
					SELECT DISTINCT c.id
					FROM candidatos c
					INNER JOIN postulante_cv pc ON pc.candidato_id = c.id
					WHERE c.id IN (
						SELECT p.candidato_id FROM postulaciones p
						INNER JOIN estados_postulacion ep ON ep.id = p.estado_id
						WHERE ep.codigo IN ('descartado', 'desertado')
					)
					AND c.id NOT IN (
						SELECT p.candidato_id FROM postulaciones p
						INNER JOIN estados_postulacion ep ON ep.id = p.estado_id
						WHERE ep.codigo NOT IN ('descartado', 'desertado')
					)
					AND (SELECT MAX(p2.fecha_ultimo_cambio) FROM postulaciones p2 WHERE p2.candidato_id = c.id) <= datetime('now', :offset)
				");
				$this->db->bind(':offset', '-'.((int) $dias).' days');
				return array_column($this->db->registros(), 'id');
			}

			public function resumenCvDescartados($dias = 15){
				return ['total' => count($this->candidatosCvDescartadoPurgable($dias)), 'dias' => $dias];
			}

			/** Borra todos los archivos (y filas de postulante_cv) de los candidatos elegibles. Devuelve cuantos candidatos se procesaron. **/
			public function limpiarCvDescartados($dias = 15){
				$candidatoIds = $this->candidatosCvDescartadoPurgable($dias);

				foreach($candidatoIds as $candidatoId){
					$this->db->query('SELECT archivo_path FROM postulante_cv WHERE candidato_id = :id');
					$this->db->bind(':id', $candidatoId);
					foreach($this->db->registros() as $fila){
						$ruta = RUTA_DOCUMENTO.$fila->archivo_path;
						if(file_exists($ruta)){
							@unlink($ruta);
						}
					}

					$this->db->query('DELETE FROM postulante_cv WHERE candidato_id = :id');
					$this->db->bind(':id', $candidatoId);
					$this->db->execute();
				}

				return count($candidatoIds);
			}

 }

?>
