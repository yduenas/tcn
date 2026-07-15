<?php

class Competencia{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			/** Listado completo para el panel de configuracion (incluye anuladas) y para el picker
			 * del formulario de vacante (que tambien debe poder mostrar una anulada ya asignada) **/
			public function listar(){
				$this->db->query('SELECT id, nombre, activo FROM competencias ORDER BY activo DESC, id');
				return $this->db->registros();
			}

			/** Solo activas -- fallback de Entrevistas::detalle() cuando la vacante no tiene
			 * competencias asignadas explicitamente (se muestran todas las activas) **/
			public function listarActivas(){
				$this->db->query('SELECT id, nombre, activo FROM competencias WHERE activo = 1 ORDER BY id');
				return $this->db->registros();
			}

			public function crear($nombre){
				$this->db->query('INSERT INTO competencias (nombre) VALUES (:nombre)');
				$this->db->bind(':nombre', $nombre);
				return $this->db->execute();
			}

			public function actualizar($id, $nombre){
				$this->db->query('UPDATE competencias SET nombre = :nombre WHERE id = :id');
				$this->db->bind(':nombre', $nombre);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			public function actualizarEstado($id, $activo){
				$this->db->query('UPDATE competencias SET activo = :activo WHERE id = :id');
				$this->db->bind(':activo', $activo);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

 }

?>
