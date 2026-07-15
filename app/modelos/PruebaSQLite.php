<?php

date_default_timezone_set('America/Lima');

class PruebaSQLite {

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

      		/** Registro unico **/
			public function getUsuario($dato){
				$this->db->query('
                SELECT * FROM usuarios
				');
			//	$this->db->bind(':CAMPO', $datos['parametros']);
				$resultados = $this->db->registros();
        		return $resultados;
			}

			/** Registro multiples **/
			public function getUsuarios($datos){

				$this->db->query("

				");
			//	$this->db->bind(':CAMPO', $datos['parametros']);
				$resultados = $this->db->registros();
				return $resultados;

			}

			/** INSERT | UPDATE | DELETE **/
			public function Insert_Update_Delete_Usuario($datos){

				$this->db->query('

				');

			//	$this->db->bind(':CAMPO', $datos['parametros']);
				if($this->db->execute()){
					return true;

				} else{
					return false;
				}

		  	}

 }

?>