<?php

date_default_timezone_set('America/Lima');

class EstructuraDB{

	private $db;

	public function __construct(){
		$this->db = new Base;
	}

		/** Registro unico **/
	public function getUsuario($dato){
		$this->db->query('

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

  	  	/** Listar Tablas **/
	public function validarBD(){

		$this->db->query("
			-- SHOW DATABASES like 'dbname';
			select SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".DB_NOMBRE."'

		");
	//	$this->db->bind(':CAMPO', $datos['parametros']);
		$resultado = $this->db->registro();
		return $resultado;

	}

  	/** Listar Tablas **/
	public function mostrarTablas(){

		$this->db->query("
			-- show tables
			SELECT * FROM INFORMATION_SCHEMA.tables WHERE TABLE_SCHEMA='".DB_NOMBRE."'

		");
	//	$this->db->bind(':CAMPO', $datos['parametros']);
		$resultados = $this->db->registros();
		return $resultados;

	}

	/** Mostrar capos de tablas **/
	public function mostrarCamposTablas($tabla){

		$this->db->query("
			SHOW COLUMNS FROM ".$tabla."
		");
	//	$this->db->bind(':CAMPO', $datos['parametros']);
		$resultados = $this->db->registros();
		return $resultados;

	}

	/** INSERT | UPDATE | DELETE **/
	public function ejecucionArchivoSQL($datos){

		$this->db->query($datos);

	//	$this->db->bind(':CAMPO', $datos['parametros']);
		if($this->db->execute()){
			return true;
		} else{
			return false;
		}

  	}

	

 }

?>