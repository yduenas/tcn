
<?php //session_start();


define('RAIZ',$_SERVER['DOCUMENT_ROOT']);

include_once(RAIZ."/servicio-online/bin/Bin.php");
include_once(RAIZ."/servicio-online/bin/Libreria/Conexion.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionOfiplan.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionDashboard.php");

$VDIRECTORIO_RENOVACION = Bin::Factory("Neg","VDIRECTORIO_RENOVACION");
$DAVDIRECTORIO_RENOVACION=Bin::Factory("Mod","DAVDIRECTORIO_RENOVACION");

include ('../../numero-a-letras-master/src/NumeroALetras.php');


$BASE=Bin::Factory("Lib","Base");

$vBOLETA1 = Bin::Factory("Neg","vBOLETA1");
$DAvBOLETA1=Bin::Factory("Mod","DAvBOLETA1");

$vBOLETA2 = Bin::Factory("Neg","vBOLETA2");
$DAvBOLETA2=Bin::Factory("Mod","DAvBOLETA2");


$vBOLETA3 = Bin::Factory("Neg","vBOLETA3");
$DAvBOLETA3=Bin::Factory("Mod","DAvBOLETA3");


$VTM_PERI = Bin::Factory("Neg","VTM_PERI");
$DAVTM_PERI=Bin::Factory("Mod","DAVTM_PERI");





?>



<style type="text/css">
<!--



.midnight-blue{
	background:#2c3e50;
	padding: 4px 4px 4px;
	color:white;
	font-weight:bold;
	font-size:12px;
}
.silver{
	background:white;
	padding: 3px 4px 3px;
}
.clouds{
	background:#ecf0f1;
	padding: 3px 4px 3px;
}
.border-top{
	border-top: solid 1px #bdc3c7;
	
}
.border-left{
	border-left: solid 1px #bdc3c7;
}
.border-right{
	border-right: solid 1px #bdc3c7;
}
.border-bottom{
	border-bottom: solid 1px #bdc3c7;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
}
-->
</style>


<page backtop="5mm" backbottom="5mm" backleft="15mm" backright="10mm" style="font-size: 12pt; font-family: arial" >
       
    
	<table cellspacing="0" style="width: 100%;">
        <tr>
        <?php  



        if ($co_emp=='01') {
	      	$direccion_empresa='JR. MARIANO ODICIO N° 153  URB. MIRAFLORES';
	    }elseif ($co_emp=='02') {
	      	$direccion_empresa='JR. MARIANO ODICIO N° 153  URB. MIRAFLORES';
	    }elseif ($co_emp=='03') {
	      	$direccion_empresa='JR. MARIANO ODICIO N° 153  URB. MIRAFLORES';
	    }elseif($co_emp=='04'){
	      	$direccion_empresa='JR. MARIANO ODICIO N° 153  URB. MIRAFLORES';
	    }elseif($co_emp=='05'){
	    	$direccion_empresa='JR. MARIANO ODICIO N° 153  URB. MIRAFLORES';
	    }else{
	    	$direccion_empresa='CAL. REAL 307';
	    }


	    if ($co_emp=='01') {
		  $Nombre_empresa='TAI LOY S.A.';
		}elseif ($co_emp=='02') {
		  $Nombre_empresa='COMERCIAL LUCIANO AREQUIPA S.A.C.';
		}elseif ($co_emp=='03') {
		  $Nombre_empresa='COPY VENTAS S.R.L.';
		}elseif($co_emp=='04'){
		  $Nombre_empresa='SUPLACORP S.A. C.';
		}elseif($co_emp=='05') {
		  $Nombre_empresa='INMOBILIARIA CASCAJAL S.A.C.';
		}else{
		  $Nombre_empresa='INMOBILIARIA CASCAJAL S.A.C.';
		}


		if ($co_emp=='01') {
		  $numero_ruc='20100049181';
		}elseif ($co_emp=='02') {
		  $numero_ruc='20555146583';
		}elseif ($co_emp=='03') {
		  $numero_ruc='20132051322';
		}elseif ($co_emp=='04') {
		  $numero_ruc='20132051322';
		}elseif ($co_emp=='05') {
		  $numero_ruc='20538004830';
		}else{
		  $numero_ruc='20208752759';
		}


        ?>

            <td style="width: 50%; color: black;font-size:12px;text-align:left">
                <?php if ($co_emp=='001') { ?>
			 	<img src='../../images/icon/logo-tailoy.png' style='    max-width: 70px;
				    height: 70px;
				    float: left;'  />
				 <?php }elseif($co_emp=='02'){ ?>

				 	<img src="../../images/icon/logo-luciano.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />
				 <?php }elseif($co_emp=='03') {?>

				 	<img src="../../images/icon/logo-copy.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />
				 <?php }elseif($co_emp=='04') {?>
				 
				 	<img src="../../images/icon/logo-supla.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />
				 <?php }elseif($co_emp=='05'){ ?>

				 	<img src="../../images/icon/logo-casca.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />

				 <?php }elseif($co_emp=='06'){ ?>

				 	<img src="../../assets/images/tailoy-bg/06.png" style='    max-width: 70px;
				    height: 70px;
				    float: left;'  />
				 <?php }?>
            </td>
			<td style="width: 25%; color: #444444;">
                
                
            </td>
			<td style="width: 25%;text-align:right">
				
				
			</td>
			
        </tr>
    </table>
    

    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 10pt;">
        
		<tr>
			<td style="width:25%;" >
			</td>
           <td style="width:50%;" ><b>COMPENSACION POR TIEMPO DE SERVICIO<br>T.U.O. D.LEG. N° 650</b></td>
		   <td style="width:25%;" >
			</td>
        </tr>
        
   
    </table>
    <?php  

    $arraylider = $DAVDIRECTORIO_RENOVACION->CO_TRAB(str_pad($co_trab, 8, "0", STR_PAD_LEFT));
    foreach($arraylider as $Item){  
    $co_trab_original=str_pad($Item['CO_TRAB'], 8, "0", STR_PAD_LEFT);
    $NU_DOCU_IDEN=$Item['NU_DOCU_IDEN'];
    $DE_SEDE=utf8_encode($Item['DE_SEDE']);
    $DE_AREA=utf8_encode($Item['DE_AREA']);

}

$conexion = odbc_connect('OFIPLAN','OFISIS','CQ5001'); if ($conexion == FALSE){ echo ('Error en la conexion'); exit(); }
$query = "SELECT 
	CO_EMPR,
	NU_ANNO,
	CASE WHEN NU_PERI='11' THEN 'NOVIEMBRE' ELSE 'MAYO' END NU_PERI2,
	CASE WHEN NU_PERI='11' THEN 'OCTUBRE' ELSE 'ABRIL' END NU_PERI3,
	NU_PERI,
	CO_TRAB,
	APELLIDOS_Y_NOMBRES,
	TI_DOCU_IDEN,
	NU_DOCU_IDEN,
	CONVERT(CHAR(10), CONVERT(date, FE_INGR_PLAN, 112), 103) AS FE_INGR_PLAN,
	CONVERT(CHAR(10), CONVERT(date, FE_CESE_PLAN, 112), 103) AS FE_CESE_PLAN,
	BANCO,
	N_CUENTA_CTS,
	CASE WHEN MONEDA_CTS_CTS='DOL' THEN 'DOLARES AMERICANOS' ELSE 'SOLES' END MONEDA_CTS_CTS,
	MONEDA_CTS_CTS AS MONEDA_CTS_CTS2,
	CO_PLAN,
	REMUNERACION_FIJA,
	REMUNERACION_VARIABLE,
	REMUNERACION_DIAS_DESCUENTO_CTS,
	CONVERT(DECIMAL(16,2),REMUNERACION_SUELDO_BASICO) AS REMUNERACION_SUELDO_BASICO,
	CONVERT(DECIMAL(16,2),REMUNERACION_ASIG_FAMILIAR) AS REMUNERACION_ASIG_FAMILIAR,
	CONVERT(DECIMAL(16,2),REMUNERACION_SEXTO_GRAT) AS REMUNERACION_SEXTO_GRAT,
	CONVERT(DECIMAL(16,2),REMUNERACION_PROM_BON_NOC) AS REMUNERACION_PROM_BON_NOC,
	CONVERT(DECIMAL(16,2),REMUNERACION_PROM_GEST) AS REMUNERACION_PROM_GEST,
	CONVERT(DECIMAL(16,2),REMUNERACION_PROM_HORAS_EXT) AS REMUNERACION_PROM_HORAS_EXT,
	CONVERT(DECIMAL(16,2),REMUNERACION_PROM_BON_REG) AS REMUNERACION_PROM_BON_REG,
	CONVERT(DECIMAL(16,2),REMUNERACION_PROM_COMIS) AS REMUNERACION_PROM_COMIS,
	CONVERT(DECIMAL(16,2),REMUNERACION_PROM_BONO_RESPONS) AS REMUNERACION_PROM_BONO_RESPONS,
	CONVERT(DECIMAL(16,2),REMUNERACION_PROM_MOVIL_BOLETA) AS REMUNERACION_PROM_MOVIL_BOLETA,
	CONVERT(DECIMAL(16,2),REMUNERACION_INCREMENTO_SNP_3_3) AS REMUNERACION_INCREMENTO_SNP_3_3,
	CONVERT(DECIMAL(16,2),REMUNERACION_PROM_REINTEGRO) AS REMUNERACION_PROM_REINTEGRO,
	REMUNERACION_CTS_DIAS_INGRESO,
	CONVERT(DECIMAL(16,2),REMUNERACION_CTS_INC_AFP_3) AS REMUNERACION_CTS_INC_AFP_3,
	CONVERT(DECIMAL(16,2),REMUNERACION_TOTAL_IMPORTE) AS REMUNERACION_TOTAL_IMPORTE,
	REMUNERACION_MESES_SERVICIO,
	REMUNERACION_DIAS_SERVICIO,
	CONVERT(INT,REMUNERACION_DIAS_DCTO) AS REMUNERACION_DIAS_DCTO,
	CONVERT(INT,REMUNERACION_Mes_Efectivos) REMUNERACION_Mes_Efectivos,
	CONVERT(INT,REMUNERACION_DIAS_efectivos) REMUNERACION_DIAS_efectivos,	REMUNERACION_CALCULO_CTS_MANUAL_MESES,
	REMUNERACION_CALCULO_CTS_MANUAL_DIAS,
	CONVERT(DECIMAL(16,2),REMUNERACION_CALCULO_CTS_TOTAL) AS REMUNERACION_CALCULO_CTS_TOTAL,
	CONVERT(DECIMAL(16,2),REMUNERACION_DESCUENTOS_JUDICIALES) AS REMUNERACION_DESCUENTOS_JUDICIALES,
	CONVERT(DECIMAL(16,2),REMUNERACION_Importe_CTS_MN) AS REMUNERACION_Importe_CTS_MN,
	REMUNERACION_Ultimas_4_remuneraciones,
	REMUNERACION_TIPO_DE_CAMBIO,
	CONVERT(DECIMAL(16,2),REMUNERACION_Importe_CTS_DOLARES) AS REMUNERACION_Importe_CTS_DOLARES,
	REMUNERACION_EN_DOLARES_TEXTO,
	CONVERT(CHAR(10), CONVERT(date, [DATE], 112), 103) AS FE_PAGO,
	NU_OPERACION_BANCARIO,
	AREA,
	PERIODO_A_LIQUIDAR,
	UBIGEO_SEDE
FROM VTMHIST_CTS_CALC
WHERE CO_EMPR='".$co_emp."'
	AND CO_TRAB='".$co_trab."'
	AND NU_ANNO='".$anno."'
	AND NU_PERI='".$peri."'
";
$result = odbc_exec($conexion,$query);
$contar=1;

while($Item=odbc_fetch_array($result))
{
    	$co_trab=trim($Item['CO_TRAB']);
    	$nom=utf8_encode(trim($Item['APELLIDOS_Y_NOMBRES']));
    	$BANCO=trim($Item['BANCO']);
    	$N_CUENTA_CTS=trim($Item['N_CUENTA_CTS']);
    	$MONEDA_CTS_CTS=trim($Item['MONEDA_CTS_CTS']);
    	$TI_DOCU_IDEN=trim($Item['TI_DOCU_IDEN']);
    	$NU_DOCU_IDEN=trim($Item['NU_DOCU_IDEN']);
    	$FE_INGR_PLAN=trim($Item['FE_INGR_PLAN']);
    	$NU_OPERACION_BANCARIO=utf8_encode($Item['NU_OPERACION_BANCARIO']);
    	$FE_PAGO=trim($Item['FE_PAGO']);

    	$AREA=utf8_encode(trim($Item['AREA']));
    	$NU_PERI2=utf8_encode(trim($Item['NU_PERI2']));
    	$REMUNERACION_SUELDO_BASICO=trim($Item['REMUNERACION_SUELDO_BASICO']);
		$REMUNERACION_ASIG_FAMILIAR=trim($Item['REMUNERACION_ASIG_FAMILIAR']);
		$REMUNERACION_SEXTO_GRAT=trim($Item['REMUNERACION_SEXTO_GRAT']);
		$REMUNERACION_PROM_BON_NOC=trim($Item['REMUNERACION_PROM_BON_NOC']);
		$REMUNERACION_PROM_GEST=trim($Item['REMUNERACION_PROM_GEST']);
		$REMUNERACION_PROM_HORAS_EXT=trim($Item['REMUNERACION_PROM_HORAS_EXT']);
		$REMUNERACION_PROM_BON_REG=trim($Item['REMUNERACION_PROM_BON_REG']);
		$REMUNERACION_PROM_COMIS=trim($Item['REMUNERACION_PROM_COMIS']);
		$REMUNERACION_PROM_BONO_RESPONS=trim($Item['REMUNERACION_PROM_BONO_RESPONS']);
		$REMUNERACION_PROM_MOVIL_BOLETA=trim($Item['REMUNERACION_PROM_MOVIL_BOLETA']);
		$REMUNERACION_INCREMENTO_SNP_3_3=trim($Item['REMUNERACION_INCREMENTO_SNP_3_3']);
		$REMUNERACION_PROM_REINTEGRO=trim($Item['REMUNERACION_PROM_REINTEGRO']);
		$REMUNERACION_CTS_INC_AFP_3=trim($Item['REMUNERACION_CTS_INC_AFP_3']);
		$REMUNERACION_TOTAL_IMPORTE=trim($Item['REMUNERACION_TOTAL_IMPORTE']);
		$REMUNERACION_DIAS_DCTO=trim($Item['REMUNERACION_DIAS_DCTO']);
		$PERIODO_A_LIQUIDAR=utf8_encode(trim($Item['PERIODO_A_LIQUIDAR']));
		$TIEMPO_EFECTIVO_LIQUIDAR=$Item['REMUNERACION_Mes_Efectivos'].' MES(ES) '.$Item['REMUNERACION_DIAS_efectivos'].' DIA(S)';
		$TOTAL_DIAS_EFECTIVOS=($Item['REMUNERACION_Mes_Efectivos']*30)+ $Item['REMUNERACION_DIAS_efectivos'];
		$REMUNERACION_CALCULO_CTS_TOTAL=trim($Item['REMUNERACION_CALCULO_CTS_TOTAL']);
		$MONEDA_CTS_CTS2=trim($Item['MONEDA_CTS_CTS2']);
		$REMUNERACION_TIPO_DE_CAMBIO=trim($Item['REMUNERACION_TIPO_DE_CAMBIO']);
		$REMUNERACION_Importe_CTS_DOLARES=trim($Item['REMUNERACION_Importe_CTS_DOLARES']);
		$UBIGEO_SEDE=trim($Item['UBIGEO_SEDE']);
		$REMUNERACION_Importe_CTS_MN=trim($Item['REMUNERACION_Importe_CTS_MN']);
		$NU_PERI3=trim($Item['NU_PERI3']);

		

		if ($Item['REMUNERACION_DESCUENTOS_JUDICIALES']<1) {
			$REMUNERACION_DESCUENTOS_JUDICIALES='0';
		}else{
			$REMUNERACION_DESCUENTOS_JUDICIALES=trim($Item['REMUNERACION_DESCUENTOS_JUDICIALES']);
		}
		



    }
    ?>
    
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        
		<tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>COMPAÑIA</b></td>
           <td style="width:45%;"><b>:</b> <?php echo $Nombre_empresa ?></td>
		   
        </tr>
        <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>RUC</b></td>
           <td style="width:55%;"><b>:</b> <?php echo $numero_ruc ?></td>
		   
        </tr>
        <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>DOMICILIO</b></td>
           <td style="width:55%;"><b>:</b> <?php echo $direccion_empresa ?></td>
		   
        </tr>

        <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>ÁREA</b></td>
           <td style="width:45%;"><b>:</b> <?php echo $AREA ?></td>
		   
        </tr>
    </table>
    <hr>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
    	<br>
        <tr>
			<td style="width:5%;"></td>
           <td style="width:45%;"><b><u>DATOS DEL PERSONAL</u></b></td>
           
		   <td style="width:45%;"></td>
        </tr>


        
   
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">

        <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>COD. TRABAJADOR</b></td>
           <td style="width:45%;"><b>:</b> <?php echo $co_trab ?></td>
		   
        </tr>

        <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>APELLIDOS Y NOMBRES</b></td>
           <td style="width:45%;"><b>:</b> <?php echo $nom ?></td>
		   
        </tr>

        <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>TIPO Y N° DOCUMENTO  IDENTIDAD</b></td>
           <td style="width:45%;"><b>:</b> <?php echo $TI_DOCU_IDEN.' - '.$NU_DOCU_IDEN ?></td>
		   
        </tr>

        <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>FECHA DE INGRESO</b></td>
           <td style="width:45%;"><b>:</b> <?php echo $FE_INGR_PLAN ?></td>
		   
        </tr>

        <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>ENTIDAD FINANCIERA DEL DEPOSITO</b></td>
           <td style="width:45%;"><b>:</b> <?php echo $BANCO ?></td>
		   
        </tr>

        <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>N° CUENTA ABONO CTS</b></td>
           <td style="width:45%;"><b>:</b> <?php echo $N_CUENTA_CTS ?></td>
		   
        </tr>

         <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>MONEDA DEL ABONO</b></td>
           <td style="width:45%;"><b>:</b> <?php echo $MONEDA_CTS_CTS ?></td>
		   
        </tr>

        <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>NÚMERO (DETALLE DE LA OPERACIÓN)</b></td>
           <td style="width:45%;"><b>:</b> <?php echo $NU_OPERACION_BANCARIO ?></td>
		   
        </tr>



      	<tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;"><b>FECHA DE LA OPERACIÓN</b></td>
           <td style="width:45%;"><b>:</b> <?php echo $FE_PAGO ?></td>
		   
        </tr>
        

    </table>
    <hr>


  


    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">

        <tr>
        	<td style="width:5%;"><b></b></td>
			<td style="width:100%;"><b><u>REMUNERACION COMPUTABLE AL MES DE <?php echo $NU_PERI3 ?> DE <?php echo $anno ?></u></b></td>
           <td style="width:45%;"></td>
		   
        </tr>
    </table>
    <br>
    <?php  
	    if ($REMUNERACION_SUELDO_BASICO>0) {
	    
	    ?>
     <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        
        <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:45%;">SUELDO BASICO</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_SUELDO_BASICO ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>
    </table>
    <?php 
        }
        ?>

     <?php  
	    if ($REMUNERACION_ASIG_FAMILIAR>0) {
	    
	    ?>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
       
        <tr>

           <td style="width:5%;"></td>
           <td style="width:45%;">ASIGNACIÓN FAMILIAR</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_ASIG_FAMILIAR ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>
    </table>
        <?php 
        }
        ?>

    

    <?php  
    if ($REMUNERACION_SEXTO_GRAT>0) {
    
    ?>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>
           <td style="width:5%;"></td>
           <td style="width:45%;">SEXTO GRATIFICACIÓN</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_SEXTO_GRAT ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>
     </table>
        <?php 
        }
        ?>
    
	
	<?php  
	    if ($REMUNERACION_PROM_BON_NOC>0) {
	    
	    ?>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        
        <tr>

           <td style="width:5%;"></td>
           <td style="width:45%;">PROMEDIO BONO NOCTURNO</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_PROM_BON_NOC ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>
    </table>
        <?php 
        }
        ?>

        <?php  
	    if ($REMUNERACION_PROM_GEST>0) {
	    
	    ?>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>

           <td style="width:5%;"></td>
           <td style="width:45%;">PROMEDIO BONO DE GESTIÓN</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_PROM_GEST ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>
    </table>
        <?php 
        }
        ?>

        <?php  
	    if ($REMUNERACION_PROM_HORAS_EXT>0) {
	    
	    ?>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>

          	<td style="width:5%;"></td>
           <td style="width:45%;">PROMEDIO HORAS EXTRAS</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_PROM_HORAS_EXT ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>
    </table>
        <?php 
        }
        ?>

        <?php  
	    if ($REMUNERACION_PROM_BON_REG>0) {
	    
	    ?>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>
           <td style="width:5%;"></td>
           <td style="width:45%;">PROMEDIO BONIFICACIÓN REGULAR</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_PROM_BON_REG ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>

    </table>
        <?php 
        }
        ?>

        <?php  
	    if ($REMUNERACION_PROM_COMIS>0) {
	    
	    ?>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>

           <td style="width:5%;"></td>
           <td style="width:45%;">PROMEDIO COMISIÓN</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_PROM_COMIS ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>
    </table>
        <?php 
        }
        ?>

        <?php  
	    if ($REMUNERACION_PROM_BONO_RESPONS>0) {
	    
	    ?>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>

           <td style="width:5%;"></td>
           <td style="width:45%;">PROMEDIO BONO DE RESPONSABILIDAD</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_PROM_BONO_RESPONS ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>

    </table>
        <?php 
        }
        ?>

        <?php  
	    if ($REMUNERACION_PROM_MOVIL_BOLETA>0) {
	    
	    ?>

	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>

           <td style="width:5%;"></td>
           <td style="width:45%;">PROMEDIO MOVILDAD</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_PROM_MOVIL_BOLETA ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>
    </table>
        <?php 
        }
        ?>

        <?php  
	    if ($REMUNERACION_INCREMENTO_SNP_3_3>0) {
	    
	    ?>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>
           <td style="width:5%;"></td>
           <td style="width:45%;">INCREMENTO SNP 3.3</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_INCREMENTO_SNP_3_3 ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>

    </table>
        <?php 
        }
        ?>

        <?php  
	    if ($REMUNERACION_PROM_REINTEGRO>0) {
	    
	    ?>

	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>
           <td style="width:5%;"></td>
           <td style="width:45%;">PROMEDIO REINTEGRO</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_PROM_REINTEGRO ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>
    </table>
        <?php 
        }
        ?>

        <?php  
	    if ($REMUNERACION_CTS_INC_AFP_3>0) {
	    
	    ?>

	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>
            <td style="width:5%;"></td>
           <td style="width:45%;">CTS INC AFP 3</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_CTS_INC_AFP_3 ?></td>
           <td style="width:45%; color: white">TOTAL REMUNERACION COMPUTABLE</td>
        </tr>
        
    </table>
    <?php 
        }
        ?>
     <?php  
	    if ($REMUNERACION_TOTAL_IMPORTE>0) {
	    
	    ?>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        
        <tr>
            <td style="width:5%;"></td>
           <td style="width:45%;">TOTAL REMUNERACION COMPUTABLE</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;border-top: 1px solid #000000;"> <?php echo $REMUNERACION_TOTAL_IMPORTE ?></td>
           <td style="width:45%; color: white;">TOTAL REMUNERACION COMPUTABLE</td> 

        </tr>
        
    </table>
   <?php 
        }
        ?>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>
           <td style="width:5%;"></td>
           <td style="width:45%;"></td>
           <td style="width:5%;"></td>
           <td style="width:15%;text-align: right;border-top: 3px double"></td>
           <td style="width:45%; color: white;">TOTAL REMUNERACION COMPUTABLE</td> 
        </tr>
        

    </table>
<hr>

	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
		<tr>
			<td style="width:5%;"></td>
           <td style="width:45%;"><b><u>LIQUIDACION PERIODO : <?php echo $anno ?> - <?php echo $peri ?></u></b></td>
           <td style="width:45%;">
           </td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>
			<td style="width:5%;"></td>
           <td style="width:45%;">Periodo de Servicio a Liquidar</td>
           <td style="width:45%;">
           	: <?php echo $PERIODO_A_LIQUIDAR ?>

           </td>
        </tr>
        <tr>
			<td style="width:5%;"></td>
           <td style="width:45%;">Tiempo No Computable</td>
           <td style="width:45%;">
           	: <?php echo $REMUNERACION_DIAS_DCTO ?> DIA(S)

           </td>
        </tr>
        <tr>
			<td style="width:5%;"></td>
           <td style="width:45%;">Tiempo Efectivo a Liquidar</td>
           <td style="width:45%;">
           	: <?php echo $TIEMPO_EFECTIVO_LIQUIDAR ?>

           </td>
        </tr>

        <tr>
			<td style="width:5%;"></td>
           <td style="width:45%;">Total Días Efectivos</td>
           <td style="width:45%;">
           	: <?php echo $TOTAL_DIAS_EFECTIVOS ?> DIA(S)

           </td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
    	<br>
        <tr>
			<td style="width:5%;"></td>
           <td style="width:45%;"><b>CÁLCULO TOTAL:</b></td>
           
		   <td style="width:45%;"></td>
        </tr>

    </table><!--
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>
			<td style="width:5%;"></td>
           <td style="width:45%;"><?php echo $REMUNERACION_TOTAL_IMPORTE.' / 360*'.$TOTAL_DIAS_EFECTIVOS ?> 	= <?php echo $REMUNERACION_CALCULO_CTS_TOTAL ?></td>
           <td style="width:45%;">
           </td>
        </tr>
     </table>-->

     <table cellspacing="0" style="width: 100%; font-size: 8pt;">
        <tr>
           <td style="width:10%; color: white;">TOTALREMUNERACION</td> 
           <td style="width:40%;text-align: left;">REMUNERACION COMPUTABLE</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;"> <?php echo $REMUNERACION_TOTAL_IMPORTE ?></td>
           <td style="width:45%; color: white;">TOTAL REMUNERACION COMPUTABLE</td> 

        </tr>
   
    </table>
    <table cellspacing="0" style="width: 100%; font-size: 8pt;">
        <tr>
           <td style="width:10%; color: white;">TOTALREMUNERACION</td> 
           <td style="width:40%;text-align: left;">(÷) 360 DIAS =</td>
           <td style="width:5%;">:  </td>
           <td style="width:15%;text-align: right;"> <?php echo round($REMUNERACION_TOTAL_IMPORTE/360,2) ?></td>
           <td style="width:45%; color: white;">TOTAL REMUNERACION COMPUTABLE</td> 

        </tr>
   
    </table>
    <table cellspacing="0" style="width: 100%; font-size: 8pt;">
        <tr>
           <td style="width:10%;"></td>
           <td style="width:40%;text-align: left;">(x) DIAS EFECTIVOS</td>
           <td style="width:5%;">:  </td>
           <td style="width:15%;text-align: right;">(x) <?php echo $TOTAL_DIAS_EFECTIVOS ?> DIA(S)</td>
           <td style="width:45%; color: white;">TOTAL REMUNERACION COMPUTABLE</td> 

        </tr>


   
    </table>

    <table cellspacing="0" style="width: 100%; font-size: 8pt;">
        <tr>
           <td style="width:10%; color: white;">TOTALREMUNERACION</td> 
           <td style="width:40%;text-align: left;">= TOTAL CALCULADO</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;border-top: 1px solid #000000;"> <?php echo $REMUNERACION_CALCULO_CTS_TOTAL ?></td>
           <td style="width:45%; color: white;">TOTAL REMUNERACION COMPUTABLE</td> 

        </tr>
   
    </table>

    <table cellspacing="0" style="width: 100%; font-size: 8pt;">
        <tr>
           <td style="width:10%; color: white;">TOTALREMUNERACION</td> 
           <td style="width:40%;text-align: left;">(-) DESCUENTO JUDICIAL</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;border-top: 1px solid #000000;"> (-) <?php echo $REMUNERACION_DESCUENTOS_JUDICIALES ?></td>
           <td style="width:45%; color: white;">TOTAL REMUNERACION COMPUTABLE</td> 

        </tr>
   
    </table>

    <table cellspacing="0" style="width: 100%; font-size: 8pt;">
        <tr>
           <td style="width:10%; color: white;">TOTALREMUNERACION</td> 
           <td style="width:40%;text-align: left;">= TOTAL A PAGAR</td>
           <td style="width:5%;">: S/ </td>
           <td style="width:15%;text-align: right;border-top: 1px solid #000000;border-bottom: 3px double"> <?php echo $REMUNERACION_Importe_CTS_MN ?></td>
           <td style="width:45%; color: white;">TOTAL REMUNERACION COMPUTABLE</td> 

        </tr>
   
    </table>

    
    
<br>

<?php if ($MONEDA_CTS_CTS2=='DOL') { ?>
    <?php $letras_dolares = NumeroALetras::convertir($REMUNERACION_Importe_CTS_DOLARES,'' ); ?>
   
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
		<tr>
           <td style="width:5%;"><b></b></td>
           <td style="width:95%;"><b>TOTAL DEPOSITADO : </b>US$ <?php echo $REMUNERACION_Importe_CTS_DOLARES ?> (<?php echo $letras_dolares ?> DOLARES AMERICANOS)</td>
        </tr>
    </table>
     <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <?php $letras_soles = NumeroALetras::convertir($REMUNERACION_Importe_CTS_MN, ''); ?>
         <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:95%;"><b>Al tipo de cambio <?php echo $REMUNERACION_TIPO_DE_CAMBIO ?> :</b>S/ <?php echo $REMUNERACION_Importe_CTS_MN ?> (<?php echo $letras_soles ?> SOLES)</td>
          
        </tr>

    </table>
    <?php }else{ ?>
         <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <?php $letras_soles = NumeroALetras::convertir($REMUNERACION_Importe_CTS_MN, ''); ?>
         <tr>
			<td style="width:5%;"><b></b></td>
           <td style="width:95%;"><b>TOTAL DEPOSITADO : </b>S/ <?php echo $REMUNERACION_Importe_CTS_MN ?> (<?php echo $letras_soles ?> SOLES)</td>
          
        </tr>

    </table>
    
    <?php } ?>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
    	<br>
        <tr>
			<td style="width:5%;"></td>
           <td style="width:45%;"><?php echo $UBIGEO_SEDE ?>, 15 DE <?php echo $NU_PERI2 ?> DE <?php echo $anno ?></td>
           
		   <td style="width:45%;"></td>
        </tr>

    </table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <tr>
			<td style="width:5%;"></td>
           <td style="width:45%;">RECIBI CONFORME:</td>
           
		   <td style="width:45%;"></td>
        </tr>



        
   
    </table>		


   		<table cellspacing="0" style="width: 102%; text-align: center; font-size: 8pt;">
   			<?php  
   			if ($co_emp=='01') {
			  $razon_social='TAI LOY S.A.';
			}elseif ($co_emp=='02') {
			  $razon_social='COMERCIAL LUCIANO AREQUIPA S.A.C.';
			}elseif ($co_emp=='03') {
			  $razon_social='COPY VENTAS S.R.L.';
			}else{
			  $razon_social='SUPLACORP S.A. C.';
			}


			if ($co_emp=='01') {
			  $repre='DUEÑAS MARTINO YTALO FRANCO';
			}else{
			  $repre='HUARCAYA SILVA MANUEL ENRIQUE';
			}


			if ($co_emp=='01') {
			  $pue_repre='REPRESENTANTE LEGAL';
			}else{
			  $pue_repre='REPRESENTANTE LEGAL';
			}
   			if ($co_emp=='01') {   			
   			?>
   			<tr>
	           	<th style="width:51%;"></th>
			   	<th style="width:51%;"><img src="../../inc/img/firma-ytalo.png"></th>
			   	
	        </tr>
		    <?php }else{ 

			?>
			<tr>
	           	<th style="width:51%;"></th>
			   	<th style="width:51%;"><img src="../../inc/img/firma-huarcaya.png" style="width:50px;height: 50px"></th>
			</tr>
			<?php 
			}
			?>
   			<tr>
	           	<th style="width:51%;">____________________________________________</th>
			   	<th style="width:51%;">____________________________________________________</th>
			   	
	        </tr>
	        <tr>
	           	<th style="width:51%;"><?php echo $nom ?></th>
			   	<th style="width:51%;"><?php echo $razon_social ?></th>
			   	
	        </tr>
	        <tr>
	           	<th style="width:51%;"><?php echo $TI_DOCU_IDEN.' : '.$NU_DOCU_IDEN ?></th>
			   	<th style="width:51%;"><?php echo $repre ?></th>
			   	
	        </tr>
	        <tr>
	           	<th style="width:51%;">&nbsp;</th>
			   	<th style="width:51%;"><?php echo $pue_repre ?></th>
			   	
	        </tr>
	       
   		</table>
   		<br><br>
		<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $co_trab.'-'.$anno.'-'.$peri; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>


	
	
	
	
	  

</page>

