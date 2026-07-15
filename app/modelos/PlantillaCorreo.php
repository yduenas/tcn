<?php

class PlantillaCorreo{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			/** Listado completo para el panel de Plantillas de correo, con el nombre/orden
			 * de la etapa cuando aplica (tipo = cambio_estado) para poder agrupar la vista. **/
			public function listar(){
				$this->db->query('
					SELECT pc.id, pc.tipo, pc.estado_id, pc.asunto, pc.cuerpo_html, pc.activo, pc.cc_seleccionador,
					       ep.nombre AS estado_nombre, ep.orden AS estado_orden
					FROM plantillas_correo pc
					LEFT JOIN estados_postulacion ep ON ep.id = pc.estado_id
					ORDER BY
					    CASE pc.tipo WHEN \'recuperacion_password\' THEN 1 WHEN \'postulacion_recibida\' THEN 2 ELSE 3 END,
					    ep.orden
				');
				return $this->db->registros();
			}

			/** Usado por el boton "Enviar prueba" del panel (2026-07-16) **/
			public function obtener($id){
				$this->db->query('SELECT * FROM plantillas_correo WHERE id = :id');
				$this->db->bind(':id', $id);
				return $this->db->registro();
			}

			/** Usado por los puntos de envio (recuperacion_password / postulacion_recibida, estado_id NULL) **/
			public function obtenerPorTipo($tipo){
				$this->db->query('SELECT * FROM plantillas_correo WHERE tipo = :tipo AND estado_id IS NULL');
				$this->db->bind(':tipo', $tipo);
				return $this->db->registro();
			}

			/** Usado por Postulacion::cambiarEstado() -- una plantilla por cada estado_id **/
			public function obtenerPorEstado($estado_id){
				$this->db->query("SELECT * FROM plantillas_correo WHERE tipo = 'cambio_estado' AND estado_id = :estado_id");
				$this->db->bind(':estado_id', $estado_id);
				return $this->db->registro();
			}

			public function actualizar($id, $asunto, $cuerpoHtml, $ccSeleccionador){
				$this->db->query('UPDATE plantillas_correo SET asunto = :asunto, cuerpo_html = :cuerpo_html, cc_seleccionador = :cc WHERE id = :id');
				$this->db->bind(':asunto', $asunto);
				$this->db->bind(':cuerpo_html', $cuerpoHtml);
				$this->db->bind(':cc', $ccSeleccionador ? 1 : 0);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

			/** El check "activo" (habilitar/deshabilitar envio) solo tiene sentido para
			 * postulacion_recibida/cambio_estado -- el controlador no expone esta accion
			 * para la fila de recuperacion_password (siempre se envia). **/
			public function actualizarEstado($id, $activo){
				$this->db->query('UPDATE plantillas_correo SET activo = :activo WHERE id = :id');
				$this->db->bind(':activo', $activo);
				$this->db->bind(':id', $id);
				return $this->db->execute();
			}

 }

?>
