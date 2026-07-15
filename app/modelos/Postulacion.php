<?php

class Postulacion{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			public function yaPostulo($candidato_id, $vacante_id){
				$this->db->query('SELECT 1 FROM postulaciones WHERE candidato_id = :candidato_id AND vacante_id = :vacante_id');
				$this->db->bind(':candidato_id', $candidato_id);
				$this->db->bind(':vacante_id', $vacante_id);
				return (bool) $this->db->registro();
			}

			/** Crea la postulacion en el estado inicial (Recibida) y dEja registro en el historial **/
			public function crear($candidato_id, $vacante_id){
				$this->db->query("SELECT id FROM estados_postulacion WHERE codigo = 'recibida'");
				$estadoInicial = $this->db->registro()->id;

				$this->db->query('
					INSERT INTO postulaciones (candidato_id, vacante_id, estado_id)
					VALUES (:candidato_id, :vacante_id, :estado_id)
				');
				$this->db->bind(':candidato_id', $candidato_id);
				$this->db->bind(':vacante_id', $vacante_id);
				$this->db->bind(':estado_id', $estadoInicial);
				$this->db->execute();
				$postulacion_id = (int) $this->db->ultimoId();

				$this->registrarHistorial($postulacion_id, $estadoInicial, null, 'Postulación recibida');

				return $postulacion_id;
			}

			private $seleccionVacante = '
				SELECT p.id, p.estado_id, p.fecha_postulacion, p.fecha_ultimo_cambio,
				       v.id AS vacante_id, v.titulo AS vacante_titulo,
				       ep.codigo AS estado_codigo, ep.nombre AS estado_nombre, ep.es_final,
				       te.token AS token_evaluacion
				FROM postulaciones p
				INNER JOIN vacantes v ON v.id = p.vacante_id
				INNER JOIN estados_postulacion ep ON ep.id = p.estado_id
				LEFT JOIN token_evaluacion te ON te.postulacion_id = p.id
			';

			/** Postulaciones de un candidato, para que consulte su avance (seccion 1.1) **/
			public function listarPorEmail($email){
				$this->db->query($this->seleccionVacante.'
					INNER JOIN candidatos c ON c.id = p.candidato_id
					WHERE c.email = :email
					ORDER BY p.fecha_postulacion DESC
				');
				$this->db->bind(':email', $email);
				return $this->db->registros();
			}

			/** Postulaciones de una vacante, para el pipeline del seleccionador (seccion 1.2) **/
			public function listarPorVacante($vacante_id){
				$this->db->query('
					SELECT p.id, p.estado_id, p.fecha_postulacion, p.fecha_ultimo_cambio,
					       c.id AS candidato_id, c.nombres, c.apellidos, c.email, c.telefono, c.dni, c.pretension_salarial, c.disponibilidad,
					       ep.codigo AS estado_codigo, ep.nombre AS estado_nombre, ep.orden AS estado_orden, ep.es_final
					FROM postulaciones p
					INNER JOIN candidatos c ON c.id = p.candidato_id
					INNER JOIN estados_postulacion ep ON ep.id = p.estado_id
					WHERE p.vacante_id = :vacante_id
					ORDER BY ep.orden, p.fecha_postulacion
				');
				$this->db->bind(':vacante_id', $vacante_id);
				return $this->db->registros();
			}

			public function obtener($id){
				$this->db->query('SELECT * FROM postulaciones WHERE id = :id');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			/** Datos completos para el reporte PDF (seccion 4): candidato, vacante y empresa **/
			public function obtenerCompleta($id){
				$this->db->query('
					SELECT p.id, p.fecha_postulacion,
					       c.id AS candidato_id, c.nombres, c.apellidos, c.email, c.telefono,
					       v.id AS vacante_id, v.titulo AS vacante_titulo, v.es_anonima,
					       e.nombre AS empresa_nombre, e.logo_path AS empresa_logo,
					       u.nombres AS seleccionador_nombres, u.apellidos AS seleccionador_apellidos, u.email AS seleccionador_email
					FROM postulaciones p
					INNER JOIN candidatos c ON c.id = p.candidato_id
					INNER JOIN vacantes v ON v.id = p.vacante_id
					INNER JOIN empresas e ON e.id = v.empresa_id
					INNER JOIN usuarios u ON u.id = v.seleccionador_id
					WHERE p.id = :id
				');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			public function cambiarEstado($postulacion_id, $estado_id, $usuario_id, $comentario = null){
				$this->db->query('UPDATE postulaciones SET estado_id = :estado_id, fecha_ultimo_cambio = CURRENT_TIMESTAMP WHERE id = :id');
				$this->db->bind(':estado_id', $estado_id);
				$this->db->bind(':id', $postulacion_id);
				$this->db->execute();

				$this->registrarHistorial($postulacion_id, $estado_id, $usuario_id, $comentario);
			}

			/**
			 * Rediseño 2026-07-14: ya no hay un paso separado de "armar y enviar
			 * terna" -- la Empresa ve directamente a los candidatos cuya postulación
			 * llegó a "Terna final" o "Contratado" en el funnel, apenas el
			 * Seleccionador/Administrador mueve el estado (Postulaciones::moverEstado()).
			 * Descartados NO se muestran a la Empresa (confirmado con Ytalo).
			 * 2026-07-15: agregado "Pre-contratado" (etapa nueva entre Terna final y
			 * Contratado) a la lista visible -- confirmado con Ytalo: la Empresa debe
			 * seguir viendo al candidato de forma continua desde Terna final en
			 * adelante, no perderlo de vista en el paso intermedio.
			 **/
			public function visibleParaEmpresa($postulacion_id){
				$this->db->query("
					SELECT p.id, v.empresa_id
					FROM postulaciones p
					INNER JOIN vacantes v ON v.id = p.vacante_id
					INNER JOIN estados_postulacion ep ON ep.id = p.estado_id
					WHERE p.id = :postulacion_id AND ep.codigo IN ('terna_final', 'pre_contratado', 'contratado')
				");
				$this->db->bind(':postulacion_id', $postulacion_id);
				return $this->db->registro();
			}

			/** Candidatos visibles para el dashboard de Empresa (mismo criterio que visibleParaEmpresa), agrupables por vacante. **/
			public function listarVisiblesParaEmpresa($empresa_id){
				$this->db->query("
					SELECT p.id AS postulacion_id, p.fecha_ultimo_cambio,
					       c.nombres, c.apellidos,
					       v.id AS vacante_id, v.titulo AS vacante_titulo,
					       ep.codigo AS estado_codigo, ep.nombre AS estado_nombre
					FROM postulaciones p
					INNER JOIN candidatos c ON c.id = p.candidato_id
					INNER JOIN vacantes v ON v.id = p.vacante_id
					INNER JOIN estados_postulacion ep ON ep.id = p.estado_id
					WHERE v.empresa_id = :empresa_id AND ep.codigo IN ('terna_final', 'pre_contratado', 'contratado')
					ORDER BY v.titulo, ep.orden, c.nombres
				");
				$this->db->bind(':empresa_id', $empresa_id);
				return $this->db->registros();
			}

			private function registrarHistorial($postulacion_id, $estado_id, $usuario_id, $comentario){
				$this->db->query('
					INSERT INTO postulacion_historial_estado (postulacion_id, estado_id, usuario_id, comentario)
					VALUES (:postulacion_id, :estado_id, :usuario_id, :comentario)
				');
				$this->db->bind(':postulacion_id', $postulacion_id);
				$this->db->bind(':estado_id', $estado_id);
				$this->db->bind(':usuario_id', $usuario_id);
				$this->db->bind(':comentario', $comentario);
				$this->db->execute();
			}

 }

?>
