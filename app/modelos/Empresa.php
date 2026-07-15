<?php

class Empresa{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			/** Listado completo, incluye inactivas (baja logica) **/
			public function listar(){
				$this->db->query('SELECT id, nombre, ruc, logo_path, estado, fecha_baja, fecha_creacion,
				                          contacto_nombre, contacto_telefono, contacto_email, direccion, sitio_web, linkedin
				                   FROM empresas ORDER BY nombre');
				return $this->db->registros();
			}

			/** Solo empresas activas, para selects de otros modulos (vacantes, etc.) **/
			public function listarActivas(){
				$this->db->query("SELECT id, nombre, logo_path FROM empresas WHERE estado = 'activa' ORDER BY nombre");
				return $this->db->registros();
			}

			public function obtener($id){
				$this->db->query('SELECT id, nombre, ruc, logo_path, estado, fecha_baja, fecha_creacion,
				                          contacto_nombre, contacto_telefono, contacto_email, direccion, sitio_web, linkedin
				                   FROM empresas WHERE id = :id');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			public function crear($datos){
				$this->db->query('
					INSERT INTO empresas (nombre, ruc, logo_path, estado, contacto_nombre, contacto_telefono, contacto_email, direccion, sitio_web, linkedin)
					VALUES (:nombre, :ruc, :logo_path, :estado, :contacto_nombre, :contacto_telefono, :contacto_email, :direccion, :sitio_web, :linkedin)
				');
				$this->db->bind(':nombre', $datos['nombre']);
				$this->db->bind(':ruc', $datos['ruc']);
				$this->db->bind(':logo_path', $datos['logo_path']);
				$this->db->bind(':estado', 'activa');
				$this->db->bind(':contacto_nombre', $datos['contacto_nombre']);
				$this->db->bind(':contacto_telefono', $datos['contacto_telefono']);
				$this->db->bind(':contacto_email', $datos['contacto_email']);
				$this->db->bind(':direccion', $datos['direccion']);
				$this->db->bind(':sitio_web', $datos['sitio_web']);
				$this->db->bind(':linkedin', $datos['linkedin']);
				return $this->db->execute();
			}

			/** Edicion de datos; logo_path es opcional (solo se actualiza si se subio uno nuevo) **/
			public function actualizar($id, $datos){
				if(!empty($datos['logo_path'])){
					$this->db->query('UPDATE empresas SET nombre = :nombre, ruc = :ruc, logo_path = :logo_path,
					                                        contacto_nombre = :contacto_nombre, contacto_telefono = :contacto_telefono,
					                                        contacto_email = :contacto_email, direccion = :direccion,
					                                        sitio_web = :sitio_web, linkedin = :linkedin
					                   WHERE id = :id');
					$this->db->bind(':logo_path', $datos['logo_path']);
				}else{
					$this->db->query('UPDATE empresas SET nombre = :nombre, ruc = :ruc,
					                                        contacto_nombre = :contacto_nombre, contacto_telefono = :contacto_telefono,
					                                        contacto_email = :contacto_email, direccion = :direccion,
					                                        sitio_web = :sitio_web, linkedin = :linkedin
					                   WHERE id = :id');
				}
				$this->db->bind(':nombre', $datos['nombre']);
				$this->db->bind(':ruc', $datos['ruc']);
				$this->db->bind(':contacto_nombre', $datos['contacto_nombre']);
				$this->db->bind(':contacto_telefono', $datos['contacto_telefono']);
				$this->db->bind(':contacto_email', $datos['contacto_email']);
				$this->db->bind(':direccion', $datos['direccion']);
				$this->db->bind(':sitio_web', $datos['sitio_web']);
				$this->db->bind(':linkedin', $datos['linkedin']);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Baja logica: nunca se borra fisicamente (seccion 1.4).
			 * La cascada de despublicacion de vacantes activas la orquesta
			 * el controlador (Empresas::baja), llamando a Vacante::despublicarActivasPorEmpresa(). */
			public function darDeBaja($id){
				$this->db->query("UPDATE empresas SET estado = 'inactiva', fecha_baja = CURRENT_TIMESTAMP WHERE id = :id");
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Reactiva una empresa dada de baja. Las vacantes quedan despublicadas:
			 * el seleccionador decide manualmente cuales republicar. **/
			public function reactivar($id){
				$this->db->query("UPDATE empresas SET estado = 'activa', fecha_baja = NULL WHERE id = :id");
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

 }

?>
