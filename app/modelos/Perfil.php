<?php

class Perfil{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			/** Listado de perfiles **/
			public function listar(){
				$this->db->query('SELECT id, nombre, descripcion, es_predefinido FROM perfiles ORDER BY nombre');
				return $this->db->registros();
			}

			/** Un perfil puntual **/
			public function obtener($id){
				$this->db->query('SELECT id, nombre, descripcion, es_predefinido FROM perfiles WHERE id = :id');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			/** Codigos de permiso asignados a un perfil **/
			public function permisos($perfil_id){
				$this->db->query('
					SELECT pm.id, pm.codigo, pm.descripcion, pm.categoria
					FROM perfil_permiso pp
					INNER JOIN permisos pm ON pm.id = pp.permiso_id
					WHERE pp.perfil_id = :perfil_id
					ORDER BY pm.categoria, pm.descripcion
				');
				$this->db->bind(':perfil_id', $perfil_id);
				return $this->db->registros();
			}

			/** Crear un perfil nuevo (siempre es_predefinido = 0, esos son de fabrica) **/
			public function crear($nombre, $descripcion){
				$this->db->query('INSERT INTO perfiles (nombre, descripcion, es_predefinido) VALUES (:nombre, :descripcion, 0)');
				$this->db->bind(':nombre', $nombre);
				$this->db->bind(':descripcion', $descripcion);
				$this->db->execute();
				return (int) $this->db->ultimoId();
			}

			public function actualizar($id, $nombre, $descripcion){
				$this->db->query('UPDATE perfiles SET nombre = :nombre, descripcion = :descripcion WHERE id = :id');
				$this->db->bind(':nombre', $nombre);
				$this->db->bind(':descripcion', $descripcion);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Cuantos usuarios tienen asignado este perfil (para no dejarlo sin permisos activos por error) **/
			public function contarUsuarios($perfil_id){
				$this->db->query('SELECT COUNT(*) AS total FROM usuarios WHERE perfil_id = :id');
				$this->db->bind(':id', $perfil_id);
				return (int) $this->db->registro()->total;
			}

			/** Reemplaza el conjunto de permisos de un perfil por la lista de ids dada **/
			public function guardarPermisos($perfil_id, array $permiso_ids){
				$this->db->query('DELETE FROM perfil_permiso WHERE perfil_id = :perfil_id');
				$this->db->bind(':perfil_id', $perfil_id);
				$this->db->execute();

				foreach($permiso_ids as $permiso_id){
					$this->db->query('INSERT INTO perfil_permiso (perfil_id, permiso_id) VALUES (:perfil_id, :permiso_id)');
					$this->db->bind(':perfil_id', $perfil_id);
					$this->db->bind(':permiso_id', $permiso_id);
					$this->db->execute();
				}
				return true;
			}

 }

?>
