<?php

class ModalidadTrabajo{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			/** Listado completo para el panel de configuracion (incluye anuladas) **/
			public function listar(){
				$this->db->query('SELECT id, nombre, activo FROM modalidades_trabajo ORDER BY activo DESC, id');
				return $this->db->registros();
			}

			/** Para selects de formulario (crear vacante, filtros): solo activas,
			 * salvo que se pida incluir explicitamente la actualmente asignada aunque este anulada **/
			public function listarActivas($incluirId = null){
				$this->db->query('SELECT id, nombre, activo FROM modalidades_trabajo WHERE activo = 1 OR id = :incluir_id ORDER BY id');
				$this->db->bind(':incluir_id', $incluirId);
				return $this->db->registros();
			}

			public function crear($nombre){
				$this->db->query('INSERT INTO modalidades_trabajo (nombre) VALUES (:nombre)');
				$this->db->bind(':nombre', $nombre);
				return $this->db->execute();
			}

			public function actualizar($id, $nombre){
				$this->db->query('UPDATE modalidades_trabajo SET nombre = :nombre WHERE id = :id');
				$this->db->bind(':nombre', $nombre);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			public function actualizarEstado($id, $activo){
				$this->db->query('UPDATE modalidades_trabajo SET activo = :activo WHERE id = :id');
				$this->db->bind(':activo', $activo);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

 }

?>
