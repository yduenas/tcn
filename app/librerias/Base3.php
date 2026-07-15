<?php
// Clase para conectarse a SQLite y ejecutar consultas PDO

class Base3 {

    // Ruta al archivo SQLite
    private $archivo = DB_SQLITE;   // Ejemplo: APPROOT . '/database/database.sqlite'

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {

        try {

            // Conexión SQLite
            $this->dbh = new PDO("sqlite:" . $this->archivo);

            // Configuración
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            // Activar claves foráneas
            $this->dbh->exec("PRAGMA foreign_keys = ON");

            // Esperar en vez de fallar de inmediato ante escrituras concurrentes (SQLite es single-writer)
            $this->dbh->exec("PRAGMA busy_timeout = 5000");

            // WAL: permite lectores concurrentes mientras hay un escritor, en vez de bloquear todo el archivo
            $this->dbh->exec("PRAGMA journal_mode = WAL");

        } catch (PDOException $e) {

            $this->error = $e->getMessage();
            echo $this->error;

        }

    }

    // Preparar consulta
    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind de parámetros
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

    // Ejecutar consulta
    public function execute(){
        return $this->stmt->execute();
    }

    // Obtener todos los registros
    public function registros(){
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Obtener un registro
    public function registro(){
        $this->execute();
        return $this->stmt->fetch();
    }

    // Cantidad de filas afectadas
    public function rowCount(){
        return $this->stmt->rowCount();
    }

    // Id autoincremental del ultimo INSERT
    public function ultimoId(){
        return $this->dbh->lastInsertId();
    }

}

?>