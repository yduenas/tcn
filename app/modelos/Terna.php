<?php

class Terna{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			public function obtenerPorVacante($vacante_id){
				$this->db->query('SELECT * FROM ternas WHERE vacante_id = :vacante_id');
				$this->db->bind(':vacante_id', $vacante_id);
				return $this->db->registro();
			}

			public function obtener($id){
				$this->db->query('
					SELECT t.*, v.titulo AS vacante_titulo, v.empresa_id
					FROM ternas t
					INNER JOIN vacantes v ON v.id = t.vacante_id
					WHERE t.id = :id
				');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			/** Crea la terna de la vacante si no existe, y reemplaza sus candidatos por la seleccion dada **/
			public function guardarSeleccion($vacante_id, array $postulacion_ids){
				$terna = $this->obtenerPorVacante($vacante_id);

				if(!$terna){
					$this->db->query('INSERT INTO ternas (vacante_id) VALUES (:vacante_id)');
					$this->db->bind(':vacante_id', $vacante_id);
					$this->db->execute();
					$terna_id = (int) $this->db->ultimoId();
				}else{
					$terna_id = $terna->id;
				}

				$this->db->query('DELETE FROM terna_candidato WHERE terna_id = :terna_id');
				$this->db->bind(':terna_id', $terna_id);
				$this->db->execute();

				foreach(array_values($postulacion_ids) as $orden => $postulacion_id){
					$this->db->query('INSERT INTO terna_candidato (terna_id, postulacion_id, orden) VALUES (:terna_id, :postulacion_id, :orden)');
					$this->db->bind(':terna_id', $terna_id);
					$this->db->bind(':postulacion_id', $postulacion_id);
					$this->db->bind(':orden', $orden);
					$this->db->execute();
				}

				return $terna_id;
			}

			/** Envio a la empresa cliente (seccion 1.2), protegido por el permiso aprobar_terna en el controlador **/
			public function enviar($terna_id, $usuario_id){
				$this->db->query('UPDATE ternas SET fecha_envio = CURRENT_TIMESTAMP, enviado_por_usuario_id = :usuario_id WHERE id = :id');
				$this->db->bind(':usuario_id', $usuario_id);
				$this->db->bind(':id', $terna_id);
				return $this->db->execute();
			}

			public function candidatos($terna_id){
				$this->db->query('
					SELECT tc.id, tc.orden, tc.postulacion_id,
					       c.id AS candidato_id, c.nombres, c.apellidos, c.email
					FROM terna_candidato tc
					INNER JOIN postulaciones p ON p.id = tc.postulacion_id
					INNER JOIN candidatos c ON c.id = p.candidato_id
					WHERE tc.terna_id = :terna_id
					ORDER BY tc.orden
				');
				$this->db->bind(':terna_id', $terna_id);
				return $this->db->registros();
			}

			/** Para autorizar el reporte PDF: la postulacion debe pertenecer a una terna YA enviada **/
			public function postulacionEnTernaEnviada($postulacion_id){
				$this->db->query('
					SELECT t.id, v.empresa_id
					FROM terna_candidato tc
					INNER JOIN ternas t ON t.id = tc.terna_id
					INNER JOIN vacantes v ON v.id = t.vacante_id
					WHERE tc.postulacion_id = :postulacion_id AND t.fecha_envio IS NOT NULL
				');
				$this->db->bind(':postulacion_id', $postulacion_id);
				return $this->db->registro();
			}

			/** Ternas ya enviadas de las vacantes de una empresa (seccion 1.3: dashboard de la empresa) **/
			public function listarEnviadasPorEmpresa($empresa_id){
				$this->db->query("
					SELECT t.id, t.fecha_envio, v.id AS vacante_id, v.titulo AS vacante_titulo
					FROM ternas t
					INNER JOIN vacantes v ON v.id = t.vacante_id
					WHERE v.empresa_id = :empresa_id AND t.fecha_envio IS NOT NULL
					ORDER BY t.fecha_envio DESC
				");
				$this->db->bind(':empresa_id', $empresa_id);
				return $this->db->registros();
			}

 }

?>
