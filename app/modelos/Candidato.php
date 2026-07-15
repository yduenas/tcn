<?php

class Candidato{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			public function buscarPorEmail($email){
				$this->db->query('SELECT * FROM candidatos WHERE email = :email');
				$this->db->bind(':email', $email);
				return $this->db->registro();
			}

			/** DNI también debe ser único (2026-07-15) -- usado para rechazar antes de guardar si el DNI ya pertenece a OTRO correo. **/
			public function buscarPorDni($dni){
				if($dni === '' || $dni === null){ return null; }
				$this->db->query('SELECT * FROM candidatos WHERE dni = :dni');
				$this->db->bind(':dni', $dni);
				return $this->db->registro();
			}

			public function obtener($id){
				$this->db->query('SELECT * FROM candidatos WHERE id = :id');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			/** Una fila por postulacion (candidato + vacante + empresa + estado), para el modulo Postulantes
			 * (seccion 1.4) -- pedido de Ytalo 2026-07-15: Empresa/Puesto/DNI/fecha del ultimo estado como
			 * columnas reales del DataTable, no como texto agrupado dentro de una sola celda. LEFT JOIN desde
			 * candidatos para que un candidato sin ninguna postulacion todavia aparezca en el listado. **/
			public function listarConPostulaciones(){
				$this->db->query('
					SELECT c.id AS candidato_id, c.nombres, c.apellidos, c.email, c.telefono, c.dni,
					       p.id AS postulacion_id, p.fecha_ultimo_cambio,
					       v.titulo AS vacante_titulo,
					       e.nombre AS empresa_nombre,
					       ep.codigo AS estado_codigo, ep.nombre AS estado_nombre
					FROM candidatos c
					LEFT JOIN postulaciones p ON p.candidato_id = c.id
					LEFT JOIN vacantes v ON v.id = p.vacante_id
					LEFT JOIN empresas e ON e.id = v.empresa_id
					LEFT JOIN estados_postulacion ep ON ep.id = p.estado_id
					ORDER BY c.apellidos, c.nombres, p.fecha_postulacion DESC
				');
				return $this->db->registros();
			}

			/** Alta o actualizacion del perfil (seccion 6: el candidato reutiliza y actualiza su perfil, no lo duplica) **/
			public function guardarPerfil($datos){
				$existente = $this->buscarPorEmail($datos['email']);

				if($existente){
					$this->db->query('
						UPDATE candidatos SET nombres = :nombres, apellidos = :apellidos, telefono = :telefono,
						       dni = :dni, pretension_salarial = :pretension_salarial, disponibilidad = :disponibilidad
						WHERE id = :id
					');
					$this->bindPerfil($datos);
					$this->db->bind(':id', $existente->id);
					$this->db->execute();
					return $existente->id;
				}

				$this->db->query('
					INSERT INTO candidatos (nombres, apellidos, email, telefono, dni, pretension_salarial, disponibilidad)
					VALUES (:nombres, :apellidos, :email, :telefono, :dni, :pretension_salarial, :disponibilidad)
				');
				$this->bindPerfil($datos);
				$this->db->bind(':email', $datos['email']);
				$this->db->execute();
				return (int) $this->db->ultimoId();
			}

			private function bindPerfil($datos){
				$this->db->bind(':nombres', $datos['nombres']);
				$this->db->bind(':apellidos', $datos['apellidos']);
				$this->db->bind(':telefono', $datos['telefono']);
				// DNI vacio se guarda como NULL, no '' -- el indice UNIQUE de dni
				// (migracion 022) permite multiples NULL, pero trataria '' como un
				// valor real que solo un candidato podria tener sin DNI.
				$this->db->bind(':dni', $datos['dni'] !== '' ? $datos['dni'] : null);
				$this->db->bind(':pretension_salarial', $datos['pretension_salarial']);
				$this->db->bind(':disponibilidad', $datos['disponibilidad']);
			}

			public function listarExperiencia($candidato_id){
				$this->db->query('SELECT * FROM candidato_experiencia WHERE candidato_id = :id ORDER BY id DESC');
				$this->db->bind(':id', $candidato_id);
				return $this->db->registros();
			}

			public function listarEducacion($candidato_id){
				$this->db->query('SELECT * FROM candidato_educacion WHERE candidato_id = :id ORDER BY id DESC');
				$this->db->bind(':id', $candidato_id);
				return $this->db->registros();
			}

			/** Reemplaza la experiencia/educacion registrada por la version mas reciente que trajo el candidato **/
			public function reemplazarExperiencia($candidato_id, array $filas){
				$this->db->query('DELETE FROM candidato_experiencia WHERE candidato_id = :id');
				$this->db->bind(':id', $candidato_id);
				$this->db->execute();

				foreach($filas as $fila){
					if(trim($fila['empresa'] ?? '') === '') continue;
					$this->db->query('
						INSERT INTO candidato_experiencia (candidato_id, empresa, cargo, fecha_inicio, fecha_fin, actualidad, descripcion)
						VALUES (:candidato_id, :empresa, :cargo, :fecha_inicio, :fecha_fin, :actualidad, :descripcion)
					');
					$this->db->bind(':candidato_id', $candidato_id);
					$this->db->bind(':empresa', $fila['empresa']);
					$this->db->bind(':cargo', $fila['cargo']);
					$this->db->bind(':fecha_inicio', $fila['fecha_inicio'] ?: null);
					$this->db->bind(':fecha_fin', $fila['actualidad'] ? null : ($fila['fecha_fin'] ?: null));
					$this->db->bind(':actualidad', $fila['actualidad'] ? 1 : 0);
					$this->db->bind(':descripcion', $fila['descripcion'] ?? '');
					$this->db->execute();
				}
			}

			public function reemplazarEducacion($candidato_id, array $filas){
				$this->db->query('DELETE FROM candidato_educacion WHERE candidato_id = :id');
				$this->db->bind(':id', $candidato_id);
				$this->db->execute();

				foreach($filas as $fila){
					if(trim($fila['institucion'] ?? '') === '') continue;
					$this->db->query('
						INSERT INTO candidato_educacion (candidato_id, institucion, grado, campo_estudio, fecha_inicio, fecha_fin)
						VALUES (:candidato_id, :institucion, :grado, :campo_estudio, :fecha_inicio, :fecha_fin)
					');
					$this->db->bind(':candidato_id', $candidato_id);
					$this->db->bind(':institucion', $fila['institucion']);
					$this->db->bind(':grado', $fila['grado'] ?? '');
					$this->db->bind(':campo_estudio', $fila['campo_estudio'] ?? '');
					$this->db->bind(':fecha_inicio', $fila['fecha_inicio'] ?: null);
					$this->db->bind(':fecha_fin', $fila['fecha_fin'] ?: null);
					$this->db->execute();
				}
			}

			public function registrarCV($candidato_id, $archivo_path, $peso_kb){
				$this->db->query('
					INSERT INTO postulante_cv (candidato_id, archivo_path, peso_kb)
					VALUES (:candidato_id, :archivo_path, :peso_kb)
				');
				$this->db->bind(':candidato_id', $candidato_id);
				$this->db->bind(':archivo_path', $archivo_path);
				$this->db->bind(':peso_kb', $peso_kb);
				return $this->db->execute();
			}

			public function ultimoCV($candidato_id){
				$this->db->query('SELECT * FROM postulante_cv WHERE candidato_id = :id ORDER BY id DESC LIMIT 1');
				$this->db->bind(':id', $candidato_id);
				return $this->db->registro();
			}

			public function registrarConsentimiento($candidato_id, $texto_version, $ip){
				$this->db->query('
					INSERT INTO consentimiento_datos (candidato_id, texto_version, ip_registro)
					VALUES (:candidato_id, :texto_version, :ip)
				');
				$this->db->bind(':candidato_id', $candidato_id);
				$this->db->bind(':texto_version', $texto_version);
				$this->db->bind(':ip', $ip);
				return $this->db->execute();
			}

			/** Declaracion de veracidad del CV (seccion 6.4) - distinta del consentimiento de proteccion de datos **/
			public function registrarDeclaracion($candidato_id, $texto_version, $ip){
				$this->db->query('
					INSERT INTO postulante_declaracion (candidato_id, texto_version, ip_registro)
					VALUES (:candidato_id, :texto_version, :ip)
				');
				$this->db->bind(':candidato_id', $candidato_id);
				$this->db->bind(':texto_version', $texto_version);
				$this->db->bind(':ip', $ip);
				return $this->db->execute();
			}

			public function listarHabilidades($candidato_id){
				$this->db->query('SELECT * FROM candidato_habilidad WHERE candidato_id = :id ORDER BY id');
				$this->db->bind(':id', $candidato_id);
				return $this->db->registros();
			}

			public function reemplazarHabilidades($candidato_id, array $nombres){
				$this->db->query('DELETE FROM candidato_habilidad WHERE candidato_id = :id');
				$this->db->bind(':id', $candidato_id);
				$this->db->execute();

				foreach($nombres as $nombre){
					$nombre = trim($nombre);
					if($nombre === '') continue;
					$this->db->query('INSERT INTO candidato_habilidad (candidato_id, nombre) VALUES (:candidato_id, :nombre)');
					$this->db->bind(':candidato_id', $candidato_id);
					$this->db->bind(':nombre', $nombre);
					$this->db->execute();
				}
			}

 }

?>
