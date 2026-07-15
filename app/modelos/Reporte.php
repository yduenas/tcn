<?php

class Reporte{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			/** Cantidad de postulaciones por vacante y etapa del embudo, para el reporte de publicaciones (seccion 1.4) **/
			public function conteoPostulantesPorVacanteYEstado(){
				$this->db->query('
					SELECT p.vacante_id, ep.codigo AS estado_codigo, COUNT(*) AS cantidad
					FROM postulaciones p
					INNER JOIN estados_postulacion ep ON ep.id = p.estado_id
					GROUP BY p.vacante_id, ep.codigo
				');
				return $this->db->registros();
			}

 }

?>
