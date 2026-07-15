<?php

date_default_timezone_set('America/Lima');

class Menu{

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

		  	/** Registro multiples **/
			public function menu(){

				$this->db->query("

					SELECT DISTINCT 
                TMUSUA.CO_USUA, TMUSUA.CO_PERFIL, TMPERMISOS2.CO_MODULO,
                TMMODULOS.DE_MODULO, TMMODULOS.HREF, TMMODULOS.ORDEN,
                TMMODULOS.ICONO, TMMODULOS.ICONO2, TMMODULOS.NIVEL
            FROM hrc.tmpermisos2 TMPERMISOS2
            LEFT JOIN hrc.tmusua TMUSUA
            ON TMUSUA.CO_PERFIL = TMPERMISOS2.CO_PERFIL
            LEFT JOIN hrc.tmmodulos TMMODULOS 
            ON TMPERMISOS2.CO_MODULO = TMMODULOS.CO_MODULO
            LEFT JOIN hrc.tmmodulos_sub TMMODULOS_SUB 
            ON TMPERMISOS2.CO_MODULO = TMMODULOS_SUB.CO_MODULO 
            AND TMPERMISOS2.CO_MODULO_SUB = TMMODULOS_SUB.CO_MODULO_SUB
            WHERE TMUSUA.CO_USUA = '25793692'
            AND TMMODULOS.TI_SITU = 'ACT'
            AND TMUSUA.CO_PERFIL = 'ADM'
         -- AND TMMODULOS.NIVEL = '1'
			AND TMMODULOS_SUB.TI_SITU = 'ACT'
            AND TMPERMISOS2.TI_SITU = 'ACT'
            ORDER BY TMMODULOS.NIVEL, TMMODULOS.ORDEN , TMMODULOS.DE_MODULO

				");
			//	$this->db->bind(':CAMPO', $datos['parametros']);
				$resultados = $this->db->registros();
				return $resultados;

			}
			public function menu2(){

				$this->db->query("

			SELECT DISTINCT 
		       TMPERMISOS2.CO_PERFIL, 
		       TMPERMISOS2.CO_MODULO,     TMMODULOS.DE_MODULO,
		       TMMODULOS.ICONO2,
		       TMPERMISOS2.CO_MODULO_SUB, TMMODULOS_SUB.DE_MODULO_SUB,
		       TMMODULOS_SUB.HREF,        TMMODULOS_SUB.HREF2,
		       TMMODULOS_SUB.ICONO,
		       TMMODULOS.ORDEN, TMMODULOS_SUB.ORDEN
		    FROM HRC.tmpermisos2 TMPERMISOS2
		    LEFT JOIN HRC.tmusua TMUSUA    ON TMPERMISOS2.CO_PERFIL = TMUSUA.CO_PERFIL
		    LEFT JOIN HRC.tmmodulos TMMODULOS ON TMPERMISOS2.CO_MODULO = TMMODULOS.CO_MODULO
		    LEFT JOIN HRC.tmmodulos_sub TMMODULOS_SUB 
		    ON  TMPERMISOS2.CO_MODULO = TMMODULOS_SUB.CO_MODULO 
		    AND TMPERMISOS2.CO_MODULO_SUB = TMMODULOS_SUB.CO_MODULO_SUB
		    WHERE TMPERMISOS2.CO_PERFIL = 'ADM'
		    AND TMUSUA.CO_USUA = '25793692'
		--    AND TMMODULOS.CO_MODULO = 'COT'
		    AND TMMODULOS.TI_SITU = 'ACT'
		    AND TMMODULOS_SUB.TI_SITU = 'ACT'
		    AND TMPERMISOS2.TI_SITU = 'ACT'
		    ORDER BY TMMODULOS.ORDEN, TMMODULOS_SUB.ORDEN 

				");
			//	$this->db->bind(':CAMPO', $datos['parametros']);
				$resultados = $this->db->registros();
				return $resultados;

			}

 }

?>