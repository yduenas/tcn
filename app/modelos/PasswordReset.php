<?php

class PasswordReset{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			/** Crea un token de un solo uso, valido por 1 hora **/
			public function crear($usuario_id){
				$token = bin2hex(random_bytes(24));
				$this->db->query("
					INSERT INTO password_reset_tokens (usuario_id, token, fecha_expiracion)
					VALUES (:usuario_id, :token, datetime(CURRENT_TIMESTAMP, '+1 hour'))
				");
				$this->db->bind(':usuario_id', $usuario_id);
				$this->db->bind(':token', $token);
				$this->db->execute();
				return $token;
			}

			/** Token vigente (no usado, no vencido), con el email del usuario **/
			public function buscarValido($token){
				$this->db->query("
					SELECT prt.id, prt.usuario_id, prt.token, u.email
					FROM password_reset_tokens prt
					INNER JOIN usuarios u ON u.id = prt.usuario_id
					WHERE prt.token = :token AND prt.usado = 0 AND prt.fecha_expiracion > CURRENT_TIMESTAMP
				");
				$this->db->bind(':token', $token);
				return $this->db->registro();
			}

			public function marcarUsado($id){
				$this->db->query('UPDATE password_reset_tokens SET usado = 1 WHERE id = :id');
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

 }

?>
