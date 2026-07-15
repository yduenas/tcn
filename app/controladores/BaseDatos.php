<?php
	session_start();

	/** Visor y ejecutor de base de datos (seccion 1.4): pensado para mantenimiento en hosting
	 * compartido (cPanel), donde no hay un cliente SQLite externo a mano. Solo Administrador
	 * por defecto (permiso administrar_bd) porque ejecutar SQL directo es una accion delicada;
	 * cada sentencia ejecutada queda en auditoria, exitosa o no. **/
	class BaseDatos extends Controlador{

		public function __construct(){

			requiereLogin();
			requierePermiso('administrar_bd');

			$this->sqlModelo = $this->modelo('SqlConsola');
			$this->auditoriaModelo = $this->modelo('Auditoria');

		}

		public function index(){

			$datos = ['tablas' => $this->sqlModelo->listarTablas()];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('baseDatos/index', $datos);
			$this->vista('inc/foot', $datos);

		}

		public function tabla($nombre){

			if(!$this->sqlModelo->existeTabla($nombre)){
				redirect('baseDatos/index');
			}

			$porPagina = 100;
			$pagina = max(1, (int) ($_GET['pagina'] ?? 1));

			$datos = [
				'nombre' => $nombre,
				'columnas' => $this->sqlModelo->columnas($nombre),
				'filas' => $this->sqlModelo->filas($nombre, $porPagina, ($pagina - 1) * $porPagina),
				'total' => $this->sqlModelo->totalFilas($nombre),
				'pagina' => $pagina,
				'porPagina' => $porPagina,
			];

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('baseDatos/tabla', $datos);
			$this->vista('inc/foot', $datos);

		}

		/** Una sola accion para mostrar el formulario (GET) y ejecutar (POST):
		 * el resultado se muestra en la misma respuesta, sin pasar por sesion. **/
		public function consola(){

			$datos = [
				'sql' => '',
				'columnas' => null,
				'filas' => null,
				'mensaje' => null,
				'error' => $_SESSION['sql_export_error'] ?? null,
			];
			unset($_SESSION['sql_export_error']);

			if($_SERVER['REQUEST_METHOD'] === 'POST'){

				$sql = trim($_POST['sql'] ?? '');
				$datos['sql'] = $sql;

				if($sql === ''){
					$datos['error'] = 'Escribe una sentencia SQL.';
				}else{
					try{

						$resultado = $this->sqlModelo->ejecutar($sql);

						$this->auditoriaModelo->registrar(
							$_SESSION['usuario_id'], 'ejecutar_sql', 'base_datos', null, mb_substr($sql, 0, 2000)
						);

						if($resultado['tipo'] === 'filas'){
							$datos['filas'] = $resultado['filas'];
							$datos['columnas'] = $resultado['filas'] ? array_keys($resultado['filas'][0]) : [];
							$datos['mensaje'] = count($resultado['filas']).' fila(s) devueltas.';
						}else{
							$datos['mensaje'] = $resultado['afectadas'].' fila(s) afectadas.';
						}

					}catch(\Exception $e){
						$this->auditoriaModelo->registrar(
							$_SESSION['usuario_id'], 'ejecutar_sql_error', 'base_datos', null,
							mb_substr($sql, 0, 2000).' — Error: '.$e->getMessage()
						);
						$datos['error'] = 'Error SQL: '.$e->getMessage();
					}
				}
			}

			$this->vista('inc/head', $datos);
			$this->vista('inc/appnav', $datos);
			$this->vista('baseDatos/consola', $datos);
			$this->vista('inc/foot', $datos);

		}

		/** Exporta la tabla completa (sin paginar) a CSV, abrible directo en Excel **/
		public function exportarTabla($nombre){

			if(!$this->sqlModelo->existeTabla($nombre)){
				redirect('baseDatos/index');
			}

			$filas = $this->sqlModelo->todasLasFilas($nombre);
			$columnas = array_column($this->sqlModelo->columnas($nombre), 'name');

			$this->auditoriaModelo->registrar($_SESSION['usuario_id'], 'exportar_tabla', 'base_datos', null, $nombre);

			$this->descargarCSV($nombre, $columnas, $filas);

		}

		/** Exporta el resultado de una consulta (solo lectura) a CSV **/
		public function exportarConsulta(){

			$sql = trim($_POST['sql'] ?? '');

			if($sql === '' || !$this->sqlModelo->esConsultaLectura($sql)){
				$_SESSION['sql_export_error'] = 'Solo se puede exportar el resultado de un SELECT/PRAGMA/EXPLAIN. Ejecuta la consulta primero y ajústala si hace falta.';
				redirect('baseDatos/consola');
			}

			try{
				$resultado = $this->sqlModelo->ejecutar($sql);
				$this->auditoriaModelo->registrar($_SESSION['usuario_id'], 'exportar_consulta', 'base_datos', null, mb_substr($sql, 0, 2000));

				$columnas = $resultado['filas'] ? array_keys($resultado['filas'][0]) : [];
				$this->descargarCSV('consulta', $columnas, $resultado['filas']);

			}catch(\Exception $e){
				$_SESSION['sql_export_error'] = 'Error SQL: '.$e->getMessage();
				redirect('baseDatos/consola');
			}

		}

		/** Escribe filas como CSV (UTF-8 con BOM para que Excel muestre bien los acentos) y termina la respuesta **/
		private function descargarCSV($nombreBase, $columnas, $filas){

			header('Content-Type: text/csv; charset=UTF-8');
			header('Content-Disposition: attachment; filename="'.preg_replace('/[^a-z0-9_-]+/i', '_', $nombreBase).'.csv"');

			echo "\xEF\xBB\xBF";
			$out = fopen('php://output', 'w');
			fputcsv($out, $columnas);
			foreach($filas as $fila){
				fputcsv($out, $fila);
			}
			fclose($out);
			exit;

		}

	}

?>
