<?php

date_default_timezone_set('America/Lima');

class Usuario{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			/** Buscar usuario por email (para login) **/
			public function buscarPorEmail($email){
				$this->db->query('
					SELECT u.id, u.nombres, u.apellidos, u.email, u.password_hash,
					       u.perfil_id, u.empresa_id, u.estado, p.nombre AS perfil_nombre, e.nombre AS empresa_nombre
					FROM usuarios u
					INNER JOIN perfiles p ON p.id = u.perfil_id
					LEFT JOIN empresas e ON e.id = u.empresa_id
					WHERE u.email = :email
				');
				$this->db->bind(':email', $email);
				return $this->db->registro();
			}

			/** Obtener un usuario por id **/
			public function obtener($id){
				$this->db->query('
					SELECT u.id, u.nombres, u.apellidos, u.email,
					       u.perfil_id, u.empresa_id, u.estado, u.fecha_creacion, p.nombre AS perfil_nombre
					FROM usuarios u
					INNER JOIN perfiles p ON p.id = u.perfil_id
					WHERE u.id = :id
				');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			/** Para el instalador: si ya existe al menos un usuario, el arranque inicial ya se hizo **/
			public function existeAlguno(){
				$this->db->query('SELECT COUNT(*) AS total FROM usuarios');
				return (int) $this->db->registro()->total > 0;
			}

			/** Usuarios activos con perfil "Seleccionador" -- para asignar responsable a una vacante (toda vacante debe tener uno, seccion 1.2 del CLAUDE.md).
			 * $empresa_id = null (default) -> solo seleccionadores INTERNOS de Complement (usuarios.empresa_id IS NULL) -- lista que ya usaban
			 * Administrador/Seleccionador desde siempre. $empresa_id = un id -> solo seleccionadores PROPIOS de esa empresa cliente (concepto
			 * nuevo, 2026-07-17: un usuario Empresa ahora puede tener su propio equipo de seleccionadores internos, scopeados a su empresa). **/
			public function listarSeleccionadoresActivos($empresa_id = null){
				$sql = '
					SELECT u.id, u.nombres, u.apellidos
					FROM usuarios u
					INNER JOIN perfiles p ON p.id = u.perfil_id
					WHERE p.nombre = \'Seleccionador\' AND u.estado = \'activo\'
				';
				$sql .= $empresa_id !== null ? ' AND u.empresa_id = :empresa_id' : ' AND u.empresa_id IS NULL';
				$sql .= ' ORDER BY u.nombres, u.apellidos';
				$this->db->query($sql);
				if($empresa_id !== null){ $this->db->bind(':empresa_id', $empresa_id); }
				return $this->db->registros();
			}

			/** true si el usuario dado esta activo y su perfil es "Seleccionador" -- validacion de fondo, no solo el <select> del formulario.
			 * $empresa_id sigue el mismo criterio que listarSeleccionadoresActivos(): null exige un seleccionador interno (sin empresa),
			 * un id exige que pertenezca exactamente a esa empresa -- evita que una Empresa asigne un seleccionador de otra empresa a mano. **/
			public function esSeleccionadorActivo($id, $empresa_id = null){
				$sql = '
					SELECT 1
					FROM usuarios u
					INNER JOIN perfiles p ON p.id = u.perfil_id
					WHERE u.id = :id AND p.nombre = \'Seleccionador\' AND u.estado = \'activo\'
				';
				$sql .= $empresa_id !== null ? ' AND u.empresa_id = :empresa_id' : ' AND u.empresa_id IS NULL';
				$this->db->query($sql);
				$this->db->bind(':id', $id);
				if($empresa_id !== null){ $this->db->bind(':empresa_id', $empresa_id); }
				return (bool) $this->db->registro();
			}

			/** Listado completo de usuarios -- solo Administrador (autoservicio de Empresa usa listarPorEmpresa()). **/
			public function listar(){
				$this->db->query('
					SELECT u.id, u.nombres, u.apellidos, u.email,
					       u.perfil_id, u.empresa_id, u.estado, u.fecha_creacion, p.nombre AS perfil_nombre, e.nombre AS empresa_nombre
					FROM usuarios u
					INNER JOIN perfiles p ON p.id = u.perfil_id
					LEFT JOIN empresas e ON e.id = u.empresa_id
					ORDER BY u.nombres, u.apellidos
				');
				return $this->db->registros();
			}

			/** Usuarios de una empresa cliente (autoservicio de Empresa, 2026-07-17) -- siempre sus propios
			 * seleccionadores + su(s) propio(s) usuario(s) Empresa, nunca usuarios de otras empresas. **/
			public function listarPorEmpresa($empresa_id){
				$this->db->query('
					SELECT u.id, u.nombres, u.apellidos, u.email,
					       u.perfil_id, u.empresa_id, u.estado, u.fecha_creacion, p.nombre AS perfil_nombre, e.nombre AS empresa_nombre
					FROM usuarios u
					INNER JOIN perfiles p ON p.id = u.perfil_id
					LEFT JOIN empresas e ON e.id = u.empresa_id
					WHERE u.empresa_id = :empresa_id
					ORDER BY u.nombres, u.apellidos
				');
				$this->db->bind(':empresa_id', $empresa_id);
				return $this->db->registros();
			}

			/** Crear usuario nuevo **/
			public function crear($datos){
				$this->db->query('
					INSERT INTO usuarios (nombres, apellidos, email, password_hash, perfil_id, empresa_id, estado)
					VALUES (:nombres, :apellidos, :email, :password_hash, :perfil_id, :empresa_id, :estado)
				');
				$this->db->bind(':nombres', $datos['nombres']);
				$this->db->bind(':apellidos', $datos['apellidos']);
				$this->db->bind(':email', $datos['email']);
				$this->db->bind(':password_hash', password_hash($datos['password'], PASSWORD_DEFAULT));
				$this->db->bind(':perfil_id', $datos['perfil_id']);
				$this->db->bind(':empresa_id', $datos['empresa_id'] ?? null);
				$this->db->bind(':estado', $datos['estado'] ?? 'activo');
				return $this->db->execute();
			}

			/** Edita datos basicos de un usuario existente (nombres/apellidos/correo) -- 2026-07-16.
			 * Perfil, empresa y contraseña tienen sus propias acciones (cambiarPerfil/nuevaContrasena). **/
			public function actualizar($id, $datos){
				$this->db->query('UPDATE usuarios SET nombres = :nombres, apellidos = :apellidos, email = :email WHERE id = :id');
				$this->db->bind(':nombres', $datos['nombres']);
				$this->db->bind(':apellidos', $datos['apellidos']);
				$this->db->bind(':email', $datos['email']);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Verifica la contraseña actual de un usuario (cambio de contraseña self-service, 2026-07-16) **/
			public function verificarPassword($id, $passwordPlano){
				$this->db->query('SELECT password_hash FROM usuarios WHERE id = :id');
				$this->db->bind(':id', $id);
				$fila = $this->db->registro();
				return $fila && password_verify($passwordPlano, $fila->password_hash);
			}

			public function actualizarPassword($id, $passwordPlano){
				$this->db->query('UPDATE usuarios SET password_hash = :password_hash WHERE id = :id');
				$this->db->bind(':password_hash', password_hash($passwordPlano, PASSWORD_DEFAULT));
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Cambiar el estado de un usuario (activo / inactivo) **/
			public function actualizarEstado($id, $estado){
				$this->db->query('UPDATE usuarios SET estado = :estado WHERE id = :id');
				$this->db->bind(':estado', $estado);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Reasignar el perfil de un usuario **/
			public function actualizarPerfil($id, $perfil_id){
				$this->db->query('UPDATE usuarios SET perfil_id = :perfil_id WHERE id = :id');
				$this->db->bind(':perfil_id', $perfil_id);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** Codigos de permiso que tiene un usuario, via su perfil **/
			public function listarPermisos($usuario_id){
				$this->db->query('
					SELECT pm.codigo
					FROM usuarios u
					INNER JOIN perfil_permiso pp ON pp.perfil_id = u.perfil_id
					INNER JOIN permisos pm ON pm.id = pp.permiso_id
					WHERE u.id = :usuario_id
				');
				$this->db->bind(':usuario_id', $usuario_id);
				$filas = $this->db->registros();
				return array_map(function($fila){ return $fila->codigo; }, $filas);
			}

			/** Verifica en BD si un usuario tiene un permiso puntual (uso ocasional; en controladores usar el helper de sesion tienePermiso()) **/
			public function tienePermiso($usuario_id, $codigo_permiso){
				$this->db->query('
					SELECT 1
					FROM usuarios u
					INNER JOIN perfil_permiso pp ON pp.perfil_id = u.perfil_id
					INNER JOIN permisos pm ON pm.id = pp.permiso_id
					WHERE u.id = :usuario_id AND pm.codigo = :codigo
				');
				$this->db->bind(':usuario_id', $usuario_id);
				$this->db->bind(':codigo', $codigo_permiso);
				return (bool) $this->db->registro();
			}

 }

?>
