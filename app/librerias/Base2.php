<?php
	//Clse para conectarse a la base de datos y ejecutar consultas PDO

		class Base2{
		private $host = DB_HOST2;
		private $usuario = DB_USUARIO2;
		private $password= DB_PASSWORD2;
		private $nombre_base = DB_NOMBRE2;
		private $driver ='{SQL Server}';
		private $host2 = '172.16.0.130';


		private $dbh;
		private $stmt;
		private $error;

		public function __construct(){
			//configurar la conexion
			$dsn = 'odbc:Driver='.$this->driver.';Server='.$this->host2.';Database='.$this->nombre_base; //';ClientCharset={UTF-8}';
		  //$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->nombre_base;
			/*
			$opciones = array(
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		//		,PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
			);
			*/
			$opciones = [
						PDO::ATTR_CASE => PDO::CASE_UPPER,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING,
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
					];

			//Crear una instancia de PDO
			try {
				$this->dbh =new PDO($dsn, $this->usuario, $this->password, $opciones);
			//	$this->dbh->exec('set names utf8');
			//	$this->dbh->exec('SET NAMES utf8');
				$this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			//	$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//	echo 'hola';
			} catch(PDOException $e){
				$this->error = $e->getMessage();
				echo $this->error;
			//	echo 'no hola';
			}
		}
		//Preparamos la consulta
		public function query($sql){
			$this->stmt = $this->dbh->prepare($sql);
		}
		//Vinculamos la consulta con bind
		public function bind($parametro, $valor, $tipo = null){
			if(is_null($tipo)){
				switch(true){
					case is_int($valor):
						$tipo = PDO::PARAM_INT;
					break;
					case is_bool($valor):
						$tipo = PDO::PARAM_BOOL;
					break;
					case is_null($valor):
						$tipo = PDO::PARAM_NULL;
					break;
					default:
						$tipo = PDO::PARAM_STR;
					break;
				}
			}
			$this->stmt->bindValue($parametro, $valor, $tipo);
		}
		//Ejecuta la consula
		public function execute(){
			return $this->stmt->execute();
		}

		//Obtener los registros
		public function registros(){
			$this->execute();
			return $this->stmt->fetchAll(PDO::FETCH_OBJ);
		}
		//Obtener un solo registro
		public function registro(){
			$this->execute();
			return $this->stmt->fetch(PDO::FETCH_OBJ);
		}
		//Obtener cantidad de filas con el metoso rowCount
		public function rowCount(){
			return $this->stmt->rowCount();
		}
	}

 ?>
