<?php

/** Visor/ejecutor de base de datos (seccion 1.4): para mantenimiento en hosting compartido
 * (cPanel) donde no hay acceso a un cliente SQLite externo. Conexion propia en vez de Base3
 * porque necesita ejecutar SQL arbitrario, no solo consultas parametrizadas. **/
class SqlConsola{

		private $db;

			public function __construct(){
				$this->db = new PDO('sqlite:'.DB_SQLITE);
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->db->exec('PRAGMA busy_timeout = 5000');
			}

			/** Tablas de usuario (sin las internas de sqlite), con su cantidad de filas **/
			public function listarTablas(){
				$nombres = $this->db->query("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name")
					->fetchAll(PDO::FETCH_COLUMN);

				$tablas = [];
				foreach($nombres as $nombre){
					$total = (int) $this->db->query('SELECT COUNT(*) FROM "'.$nombre.'"')->fetchColumn();
					$tablas[] = ['nombre' => $nombre, 'filas' => $total];
				}
				return $tablas;
			}

			/** Whitelist: confirma que el nombre corresponde a una tabla real antes de interpolarlo en SQL **/
			public function existeTabla($nombre){
				$stmt = $this->db->prepare("SELECT 1 FROM sqlite_master WHERE type = 'table' AND name = :nombre");
				$stmt->execute([':nombre' => $nombre]);
				return (bool) $stmt->fetchColumn();
			}

			public function columnas($tabla){
				return $this->db->query('PRAGMA table_info("'.$tabla.'")')->fetchAll(PDO::FETCH_ASSOC);
			}

			public function totalFilas($tabla){
				return (int) $this->db->query('SELECT COUNT(*) FROM "'.$tabla.'"')->fetchColumn();
			}

			public function filas($tabla, $limite, $offset){
				$stmt = $this->db->prepare('SELECT * FROM "'.$tabla.'" LIMIT :limite OFFSET :offset');
				$stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
				$stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			}

			/** Todas las filas de la tabla, sin paginar (para exportar a Excel/CSV) **/
			public function todasLasFilas($tabla){
				return $this->db->query('SELECT * FROM "'.$tabla.'"')->fetchAll(PDO::FETCH_ASSOC);
			}

			/** true si la sentencia es de solo lectura (SELECT/PRAGMA/EXPLAIN) **/
			public function esConsultaLectura($sql){
				return (bool) preg_match('/^\s*(SELECT|PRAGMA|EXPLAIN)\b/i', $sql);
			}

			/** Ejecuta la sentencia SQL que escribio el administrador.
			 * SELECT/PRAGMA/EXPLAIN devuelven filas; el resto (INSERT/UPDATE/DELETE/ALTER/etc.) devuelve filas afectadas. **/
			public function ejecutar($sql){
				if($this->esConsultaLectura($sql)){
					$filas = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
					return ['tipo' => 'filas', 'filas' => $filas];
				}
				$afectadas = $this->db->exec($sql);
				return ['tipo' => 'afectadas', 'afectadas' => $afectadas];
			}

 }

?>
