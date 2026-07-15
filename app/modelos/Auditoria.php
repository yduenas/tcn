<?php

class Auditoria{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			public function registrar($usuario_id, $accion, $entidad, $entidad_id, $detalle = null){
				$this->db->query('
					INSERT INTO auditoria_log (usuario_id, accion, entidad, entidad_id, detalle)
					VALUES (:usuario_id, :accion, :entidad, :entidad_id, :detalle)
				');
				$this->db->bind(':usuario_id', $usuario_id);
				$this->db->bind(':accion', $accion);
				$this->db->bind(':entidad', $entidad);
				$this->db->bind(':entidad_id', $entidad_id);
				$this->db->bind(':detalle', $detalle);
				return $this->db->execute();
			}

			public function listar($limite = 200){
				$this->db->query('
					SELECT a.id, a.accion, a.entidad, a.entidad_id, a.detalle, a.fecha,
					       u.nombres, u.apellidos
					FROM auditoria_log a
					LEFT JOIN usuarios u ON u.id = a.usuario_id
					ORDER BY a.fecha DESC
					LIMIT :limite
				');
				$this->db->bind(':limite', $limite);
				return $this->db->registros();
			}

 }

?>
