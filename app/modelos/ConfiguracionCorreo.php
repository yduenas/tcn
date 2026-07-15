<?php

/** Fila unica (id=1) con el nombre de remitente (FromName) usado por todos los correos
 * enviados por el sistema -- separado de la cuenta SMTP real (CORREO_ENVIO_CORREO en
 * config), para que el remitente visible pueda ser distinto al buzon tecnico. **/
class ConfiguracionCorreo{

		private $db;

			public function __construct(){
				$this->db = new Base3;
			}

			public function obtener(){
				$this->db->query('SELECT * FROM configuracion_correo WHERE id = 1');
				return $this->db->registro();
			}

			public function actualizarRemitente($nombre){
				$this->db->query('UPDATE configuracion_correo SET remitente_nombre = :nombre WHERE id = 1');
				$this->db->bind(':nombre', $nombre);
				return $this->db->execute();
			}

 }

?>
