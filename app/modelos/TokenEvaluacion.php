<?php

class TokenEvaluacion{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			public function obtenerPorPostulacion($postulacion_id){
				$this->db->query('SELECT * FROM token_evaluacion WHERE postulacion_id = :id');
				$this->db->bind(':id', $postulacion_id);
				return $this->db->registro();
			}

			/** Un token unico por postulacion (seccion 3.4); si ya existe, se reutiliza **/
			public function generarOObtener($postulacion_id){
				$existente = $this->obtenerPorPostulacion($postulacion_id);
				if($existente){
					return $existente->token;
				}

				$token = bin2hex(random_bytes(16));
				$this->db->query('INSERT INTO token_evaluacion (postulacion_id, token) VALUES (:postulacion_id, :token)');
				$this->db->bind(':postulacion_id', $postulacion_id);
				$this->db->bind(':token', $token);
				$this->db->execute();

				return $token;
			}

			public function buscarPorToken($token){
				$this->db->query('SELECT * FROM token_evaluacion WHERE token = :token');
				$this->db->bind(':token', $token);
				return $this->db->registro();
			}

 }

?>
