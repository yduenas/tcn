<?php

class Permiso{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			/** Catalogo completo de permisos, agrupado por categoria **/
			public function listar(){
				$this->db->query('SELECT id, codigo, descripcion, categoria FROM permisos ORDER BY categoria, descripcion');
				return $this->db->registros();
			}

 }

?>
