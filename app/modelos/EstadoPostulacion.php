<?php

class EstadoPostulacion{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			public function listar(){
				$this->db->query('SELECT id, codigo, nombre, orden, es_final FROM estados_postulacion ORDER BY orden');
				return $this->db->registros();
			}

			/** Usado al armar el placeholder {etapa} de Plantillas de correo (2026-07-16) **/
			public function obtener($id){
				$this->db->query('SELECT id, codigo, nombre, orden, es_final FROM estados_postulacion WHERE id = :id');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

 }

?>
