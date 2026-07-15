<?php

/** Modulo de Migraciones (2026-07-16): compara los archivos .sql en app/migraciones/
 * contra migracionDB.json (registro de lo ya ejecutado) para mostrar pendientes, y
 * permite ejecutarlas desde el panel. Conexion propia (no Base3) porque un archivo de
 * migracion puede traer varias sentencias -- Base3::query() usa PDO::prepare(), que
 * en SQLite NO ejecuta mas de una sentencia por llamada (misma limitacion ya
 * documentada para EstructuraDB::ejecucionArchivoSQL() del framework generico, y la
 * razon por la que toda migracion multi-sentencia de este proyecto se aplico hasta
 * ahora con un script PHP suelto). PDO::exec() si soporta multi-sentencia en SQLite. **/
class Migracion{

		private $carpeta;
		private $archivoRegistro;

			public function __construct(){
				$this->carpeta = RUTA_DOCUMENTO.'app/migraciones/';
				$this->archivoRegistro = $this->carpeta.'migracionDB.json';
			}

			private function leerRegistro(){
				$json = file_get_contents($this->archivoRegistro);
				return json_decode($json, true) ?: [];
			}

			private function guardarRegistro($registro){
				file_put_contents($this->archivoRegistro, json_encode($registro, JSON_PRETTY_PRINT));
			}

			/** Todos los .sql de la carpeta (ordenados), cada uno con su estado (ejecutada/pendiente) y fecha si aplica **/
			public function listar(){
				$registro = $this->leerRegistro();
				$fechasPorArchivo = [];
				foreach($registro as $fila){
					$fechasPorArchivo[$fila['nombre_archivo']] = $fila['fecha'];
				}

				$archivos = glob($this->carpeta.'*.sql');
				$nombres = array_map('basename', $archivos);
				sort($nombres);

				$resultado = [];
				foreach($nombres as $nombre){
					$resultado[] = (object) [
						'nombre_archivo' => $nombre,
						'ejecutada' => isset($fechasPorArchivo[$nombre]),
						'fecha' => $fechasPorArchivo[$nombre] ?? null,
					];
				}
				return $resultado;
			}

			public function contenido($nombreArchivo){
				$ruta = $this->carpeta.$nombreArchivo;
				return file_exists($ruta) ? file_get_contents($ruta) : null;
			}

			/** Valida que el nombre sea realmente un archivo de la carpeta de migraciones (nunca
			 * lo que llegue por URL directamente) y que no este ya registrado como ejecutado. **/
			public function esEjecutable($nombreArchivo){
				if(!preg_match('/^[A-Za-z0-9_\-]+\.sql$/', $nombreArchivo)){
					return false;
				}
				if(!file_exists($this->carpeta.$nombreArchivo)){
					return false;
				}
				foreach($this->leerRegistro() as $fila){
					if($fila['nombre_archivo'] === $nombreArchivo){
						return false;
					}
				}
				return true;
			}

			/** Ejecuta el archivo completo (multi-sentencia, ver nota de clase) y, si sale bien,
			 * lo agrega a migracionDB.json con la fecha real de ejecucion. **/
			public function ejecutar($nombreArchivo){
				$sql = $this->contenido($nombreArchivo);
				if($sql === null){
					return ['ok' => false, 'error' => 'El archivo no existe.'];
				}

				$db = new PDO('sqlite:'.DB_SQLITE);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$db->exec('PRAGMA busy_timeout = 5000');

				try{
					$db->exec($sql);
				}catch(PDOException $e){
					return ['ok' => false, 'error' => $e->getMessage()];
				}

				$registro = $this->leerRegistro();
				$registro[] = [
					'nombre_archivo' => $nombreArchivo,
					'fecha' => date('Y-m-d H:i:s'),
				];
				$this->guardarRegistro($registro);

				return ['ok' => true, 'error' => null];
			}

 }

?>
