
<?php session_start();

if(!isset($_SESSION["serviciolinea"])) {header("Location: index.php");}

define('RAIZ',$_SERVER['DOCUMENT_ROOT']);

include_once(RAIZ."/servicio-online/bin/Bin.php");
include_once(RAIZ."/servicio-online/bin/Libreria/Conexion.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionOfiplan.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionDashboard.php");

$VDIRECTORIO_RENOVACION = Bin::Factory("Neg","VDIRECTORIO_RENOVACION");
$DAVDIRECTORIO_RENOVACION=Bin::Factory("Mod","DAVDIRECTORIO_RENOVACION");



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


<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
        <page_footer>
        <table >
            <tr>

                <td style="width: 50%; text-align: left">
                  
                </td>
                
            </tr>
        </table>
    </page_footer>
    
	<table cellspacing="0" style="width: 100%;">
        <tr>
        <?php  



        if ($co_emp=='01') {
	      	$razon_social='TAI LOY S.A. </span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20100049181';
	    }elseif ($co_emp=='02') {
	      	$razon_social='COMERCIAL LUCIANO AREQUIPA S.A.C. </span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20555146583';
	    }elseif ($co_emp=='03') {
	      	$razon_social='COPY VENTAS S.R.L. </span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20132051322';
	    }elseif($co_emp=='04'){
	      	$razon_social='SUPLACORP S.A.C. </span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20465062356';
	    }elseif($co_emp=='05'){
	    	$razon_social='INMOBILIARIA CASCAJAL S.A.C.</span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20465062356';
	    }else{
	    	$razon_social='LIBRERIA BAZAR SANTA MARIA E.I.R.L.</span><br> CAL. REAL 307<br> RUC 20208752759';
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
    

    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 11pt;">
        
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

}

$conexion = odbc_connect('SELECCION','OFISIS','CQ5001'); if ($conexion == FALSE){ echo ('Error en la conexion'); exit(); }
$query = "SELECT 
	CO_EMPR,
	NU_ANNO,
	NU_PERI,
	CO_TRAB,
	APELLIDOS_Y_NOMBRES,
	TI_DOCU_IDEN,
	NU_DOCU_IDEN,
	FE_INGR_PLAN,
	FE_CESE_PLAN,
	BANCO,
	N_CUENTA_CTS,
	MONEDA_CTS_CTS,
	CO_PLAN,
	REMUNERACION_FIJA,
	REMUNERACION_VARIABLE,
	REMUNERACION_DIAS_DESCUENTO_CTS,
	REMUNERACION_SUELDO_BASICO,
	REMUNERACION_ASIG_FAMILIAR,
	REMUNERACION_SEXTO_GRAT,
	REMUNERACION_PROM_BON_NOC,
	REMUNERACION_PROM_GEST,
	REMUNERACION_PROM_HORAS_EXT,
	REMUNERACION_PROM_BON_REG,
	REMUNERACION_PROM_COMIS,
	REMUNERACION_PROM_BONO_RESPONS,
	REMUNERACION_PROM_MOVIL_BOLETA,
	REMUNERACION_INCREMENTO_SNP_3_3,
	REMUNERACION_PROM_REINTEGRO,
	REMUNERACION_CTS_DIAS_INGRESO,
	REMUNERACION_CTS_INC_AFP_3,
	REMUNERACION_TOTAL_IMPORTE,
	REMUNERACION_MESES_SERVICIO,
	REMUNERACION_DIAS_SERVICIO,
	REMUNERACION_DIAS_DCTO,
	REMUNERACION_Mes_Efectivos,
	REMUNERACION_DIAS_efectivos,
	REMUNERACION_CALCULO_CTS_MANUAL_MESES,
	REMUNERACION_CALCULO_CTS_MANUAL_DIAS,
	REMUNERACION_CALCULO_CTS_TOTAL,
	REMUNERACION_DESCUENTOS_JUDICIALES,
	REMUNERACION_Importe_CTS_MN,
	REMUNERACION_Ultimas_4_remuneraciones,
	REMUNERACION_TIPO_DE_CAMBIO,
	REMUNERACION_Importe_CTS_DOLARES,
	REMUNERACION_EN_DOLARES_TEXTO,
	[DATE],
	NU_OPERACION_BANCARIO,
	AREA,
	PERIODO_A_LIQUIDAR,
	UBIGEO_SEDE
FROM VTMHIST_CTS_CALC
";
$result = odbc_exec($conexion,$query);
$contar=1;

while($registro=odbc_fetch_array($result))
{
    	$co_trab=trim($Item['CO_TRAB']);
    	$nom=utf8_encode(trim($Item['APELLIDOS_Y_NOMBRES']));
    	$cali=trim($Item['CALIFICACION_TRABAJADOR']);
    	//$fec_in=trim($Item['FE_INGR_EMPR']);
    	$ANNOINGRESO = substr(trim($Item["FE_INGR_EMPR"]),0,4);
		$DIAINGRESO = substr(trim($Item["FE_INGR_EMPR"]),-2); 
		$MESINGRESO = substr(trim($Item["FE_INGR_EMPR"]),-5,2); 
		$fec_in=$DIAINGRESO.'/'.$MESINGRESO.'/'.$ANNOINGRESO;


    	$doc_iden=trim($Item['DOCUMENTO_IDENTIDAD']);
    	
    	$de_afp=trim($Item['DE_AFPS']);
    	$num_afp=utf8_encode(trim($Item['NUMERO_AFP']));
    	$sede=trim($Item['SEDE']);
    	$num_costo=trim($Item['NU_CENTRO_COSTO']);
    	$nom_costo=trim($Item['NO_CENTRO_COSTO']);
    	$area=trim($Item['AREA']);
    	$puesto=trim($Item['PUES_TRAB']);
    	$cate=trim($Item['CATEGORIA_TRABAJADOR']);
    	$nom_banco=trim($Item['NO_CORT_BANC']);
    	$num_cuenta=trim($Item['NU_CNTA_SUEL']);

    }
    ?>
    
       <br>
		<table cellspacing="0" style="width: 100%; text-align: center; font-size: 7.7pt; vertical-align: middle" >
	        <tr>
	           	<th style="width:8%; height: 2%; border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CODIGO</th>
			  	<th style="width:45%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">APELLIDOS Y NOMBRES</th>
			   	<th style="width:15%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CALIFICACION</th>
			   	<th style="width:13%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">FECHA ING.</th>
			   	<th style="width:13%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">FECHA CESE</th>
			   	<th style="width:9%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">SUELDO</th>
	        </tr>
			<tr>
	           	<td style="width:8%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $co_trab ?></td>
			  	<td style="width:45%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $nom ?></td>
			   	<td style="width:15%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $cali ?></td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $fec_in ?></td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"></td>
			   	<?php  

			    $arregloEmp = $DAvBOLETA3->SeleccionarSueldo(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
			    foreach($arregloEmp as $Item){
			    	$salario22=trim($Item['NU_DATO_CALC']);
			    }
			    ?>
			   	<td style="width:9%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php echo $salario22; ?></td>
	        </tr>
       </table>
       <table cellspacing="0" style="width: 100%; text-align: center; font-size: 7.7pt;">
			<tr>
	           	<th style="width:18%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DOC. IDEN.</th>
			   	<th style="width:27%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">REGIMEN PENSIONARIO</th>
			   	<th style="width:28%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">SEDE</th>
			   	<th style="width:30%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">CUENTA BANCARIA</th>
	        </tr>
			<tr>
	           	<td style="width:18%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $doc_iden ?></td>
			   	<td style="width:27%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $de_afp.'-'.$num_afp ?></td>
			   	<td style="width:28%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $sede ?></td>
			   	<td style="width:30%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php echo $nom_banco.'-'.$num_cuenta ?></td>
	        </tr>
   		</table>

   		<table cellspacing="0" style="width: 100%; text-align: center; font-size: 7.7pt;">
			<tr>
	           	<th style="width:34%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CENTRO DE COSTO (PRINCIPAL)</th>
			  	<th style="width:25%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">AREA</th>
			   	<th style="width:27%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CARGO</th>
			   	<th style="width:17%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">CATEGORIA</th>
	        </tr>
			<tr>
	           	<td style="width:34%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $num_costo.'-'.$nom_costo ?></td>
			  	<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $area ?></td>
			   	<td style="width:27%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $puesto ?></td>
			   	<?php 
			   		if ($anno >='2018') { ?>
			   			<td style="width:17%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php echo $cate ?></td>
			   		<?php }else{ ?>
			   			<td style="width:17%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"></td>
			   		<?php } 

			   	?>
			   	
	        </tr>
   		</table>

   		<table cellspacing="0" style="width: 100%; text-align: center; ">
			<tr>
	           	<th style="width:15%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DIAS SUBSIDIADOS</th>
			  	<th style="width:20%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DIAS NO TRAB./NO SUBS.</th>
			   	<th style="width:10%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DIAS TRAB.</th>
			   	<th style="width:11%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">HORAS TRAB</th>
			   	<th style="width:17%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">HRS. EXT. SIMP. 25%</th>
			   	<th style="width:17%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">HRS. EXT. SIMP. 35%</th>
			   	<th style="width:13%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">HRS. EXT. DOBLES</th>
	        </tr>
			<tr>
				<?php 
				$arregloEmp = $DAvBOLETA3->SeleccionarDias(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
                foreach($arregloEmp as $Item){
                	$dias=trim($Item['NU_DATO_CALC']);
                }

                $arregloFalta = $DAvBOLETA3->SeleccionarDiasFalta(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
                foreach($arregloFalta as $Item){
                	$diasFALTA=trim($Item['NU_DATO_CALC']);
                	
                }


                $arregloEmp = $DAvBOLETA3->SeleccionarHoras(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
                foreach($arregloEmp as $Item){
                	$hora=trim($Item['NU_DATO_CALC']);
                }

                $arregloEmp = $DAvBOLETA3->SeleccionarHoras25(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
                foreach($arregloEmp as $Item){
                	$hora25=trim($Item['NU_DATO_CALC']);
                }


                $arregloEmp = $DAvBOLETA3->SeleccionarHoras35(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
                foreach($arregloEmp as $Item){
                	$hora35=trim($Item['NU_DATO_CALC']);
                }


                $arregloEmp = $DAvBOLETA3->SeleccionarHorasDobles(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
                foreach($arregloEmp as $Item){
                	$horado=trim($Item['NU_DATO_CALC']);
                }



                $arregloEmp = $DAvBOLETA2->ListarDiasVacaciones(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
			    foreach($arregloEmp as $Item){

			    	$suma_vaca=$Item['NU_DIAS'];
			    }
					if ($diasFALTA=='0') {
                		$dias_no_trabajados='0.00';
                	}else{
                		$dias_no_trabajados=$diasFALTA;
                	}

				?>
	           	<td style="font-size: 8pt; width:15%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">0.00</td>
			  	<td style="font-size: 8pt; width:20%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $dias_no_trabajados ?></td>
			   	<td style="font-size: 8pt; width:10%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $dias ?></td>
			   	<td style="font-size: 8pt; width:11%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $hora ?></td>
			   	<td style="font-size: 8pt; width:17%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $hora25 ?></td>
			   	<td style="font-size: 8pt; width:17%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $hora35 ?></td>
			   	<td style="font-size: 8pt; width:13%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php echo $horado ?></td>
			   	
	        </tr>
   		</table>

   		<table cellspacing="0" style="width: 100%; text-align: center; font-size: 8pt;">
			<tr>
	           	<th style="width:25%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">PERIODO VACACIONAL</th>
			  	<th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">INICIO VAC.</th>
			   	<th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">FIN DE VACAC.</th>
			   	<th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">INICIO VAC.</th>
			   	<th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">FIN DE VACAC.</th>
			   	<th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">INICIO VAC.</th>
			   	<th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">FIN DE VACAC.</th>
	        </tr>

			<tr>
				<?php 
				$arregloEmp = $DAvBOLETA2->ListarVacaciones1(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
			    foreach($arregloEmp as $Item){
			    	

			    	$peri_vaca1=substr(trim($Item['PE_VACA']), 0,4);
			    	$peri_vaca2=substr(trim($Item['PE_VACA']), 4);
			    	$peri_vaca1=$peri_vaca1.'-'.$peri_vaca2;


			    	$ANNOINVAC = substr(trim($Item["INICIO_VAC"]),0,4);
  					$DIAINVAC = substr(trim($Item["INICIO_VAC"]),-2); 
  					$MESINVAC = substr(trim($Item["INICIO_VAC"]),-5,2); 
  					$inicio_vac1=$DIAINVAC.'/'.$MESINVAC.'/'.$ANNOINVAC;

				$ANNOFINVAC= substr(trim($Item["FIN_VAC"]),0,4);
  					$DIAFINVAC= substr(trim($Item["FIN_VAC"]),-2); 
  					$MESFINVAC= substr(trim($Item["FIN_VAC"]),-5,2); 
  					$fin_vac1=$DIAFINVAC.'/'.$MESFINVAC.'/'.$ANNOFINVAC;
			    	}


			    $arregloEmp = $DAvBOLETA2->ListarVacaciones2(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
			    foreach($arregloEmp as $Item){
			    	

			    	$ANNOINVAC2 = substr(trim($Item["INICIO_VAC"]),0,4);
  					$DIAINVAC2 = substr(trim($Item["INICIO_VAC"]),-2); 
  					$MESINVAC2 = substr(trim($Item["INICIO_VAC"]),-5,2); 
  					

  					if ($DIAINVAC.'/'.$MESINVAC.'/'.$ANNOINVAC==$DIAINVAC2.'/'.$MESINVAC2.'/'.$ANNOINVAC2) {
  						$inicio_vac2='';
  					}else{
  						$inicio_vac2=$DIAINVAC2.'/'.$MESINVAC2.'/'.$ANNOINVAC2;
  					}
  					

					$ANNOFINVAC2= substr(trim($Item["FIN_VAC"]),0,4);
  					$DIAFINVAC2= substr(trim($Item["FIN_VAC"]),-2); 
  					$MESFINVAC2= substr(trim($Item["FIN_VAC"]),-5,2); 
  					//$fin_vac2=$DIAFINVAC2.'/'.$MESFINVAC2.'/'.$ANNOFINVAC2;

  					if ($DIAFINVAC.'/'.$MESFINVAC.'/'.$ANNOFINVAC==$DIAFINVAC2.'/'.$MESFINVAC2.'/'.$ANNOFINVAC2) {
  						$fin_vac2='';
  					}else{
  						$fin_vac2=$DIAFINVAC2.'/'.$MESFINVAC2.'/'.$ANNOFINVAC2;
  					}
			    	}
				$arregloEmp = $DAvBOLETA3->SeleccionarDiasVacaciones(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
                foreach($arregloEmp as $Item){
                	$dias_vacaciones=trim($Item['NU_DATO_CALC']);
                }
				?>
	           	<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $peri_vaca1?></td>
			  	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $inicio_vac1 ?></td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $fin_vac1 ?></td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $inicio_vac2 ?></td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $fin_vac2 ?></td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">&nbsp;</td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">&nbsp;</td>
			   	
	        </tr>
   		</table>

   		<br><br>

   		
   			<div style="padding-top: -25px; width:100%">
		   		<table cellspacing="0" style="width: 100%; text-align: center; font-size: 8pt; ">
					<tr>
			           	<th style="width:33.33333333%;">INGRESOS</th>
					  	<th style="width:33.33333333%;">DEDUCCIONES</th>
					   	<th style="width:33.33333333%;">APORTACIONES DEL EMPLEADOR</th>
					   	
			        </tr>
			       
		   		</table>
   			</div>

   		<br>
   		<div style="padding-top: -25px; width:100% ;">
		   		<table cellspacing="0" style="width: 100%; font-size: 8pt; ">
					<tr>
			           	<td style="width:33.33333333%;">
			           		<table cellspacing="0" style="width: 100%; ">
			           			<?php  

							    $arregloEmp = $DAvBOLETA3->SeleccionarIngresos(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
							    foreach($arregloEmp as $Item){
							    	$desingre=trim($Item['DE_CPTO']);
							    	$numingre=trim($Item['NU_DATO_CALC']);
							    	$dias_in=trim($Item['NU_DATO_CALCS']);
							    ?>
				           		<tr>
				           			<td style="padding-left: 30px" align="left"><?php echo $desingre ?></td>
				           			
				           			<td style="padding-left: 3px" align="left"><?php if ($desingre=='VACACIONES') {
				           				echo $dias_vacaciones;
				           			}elseif($desingre=='HABER BASICO'){
				           				echo $dias;
				           			}else{
				           				echo '';
				           			} ?></td>
				           			<td style="padding-left: 45px;" align="right"><?php echo $numingre ?></td>
				           		</tr>
				           		<?php  
								}
								?>
				           	</table>
			           </td>
					  	<td style="width:33.33333333%;">
					  		<table cellspacing="0" style="width: 100%;">
			           			<?php  

							    $arregloEmp = $DAvBOLETA3->SeleccionarDeducciones(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
							    foreach($arregloEmp as $Item){
							    	$desdedu=trim($Item['DE_CPTO']);
							    	$numdedu=trim($Item['NU_DATO_CALC']);
							    ?>
				           		<tr>
				           			<td style="padding-right: : 70px" align="left"><?php echo $desdedu ?></td>
				           			
				           			<td style="padding-left: 45px;" align="right"><?php echo $numdedu ?></td>
				           		</tr>
				           		<?php  
								}
								?>
				           	</table>
					  	</td>
					   	<td style="width:33.33333333%;">
					   		<table>
			           			<?php  

							    $arregloEmp = $DAvBOLETA3->SeleccionarAportaciones(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
							    foreach($arregloEmp as $Item){
							    	$desaporta=trim($Item['DE_CPTO']);
							    	$numaporta=trim($Item['NU_DATO_CALC']);
							    ?>
				           		<tr>
				           			<td style="padding-right: : 70px" align="left"><?php echo $desaporta ?></td>
				           			
				           			<td style="padding-left: 110px;" align="right"><?php echo $numaporta ?></td>
				           		</tr>
				           		<?php  
								}
								?>
				           	</table>
					   	</td>
					   	
			        </tr>
			       
		   		</table>
   			</div><!--
   			<hr>
			
   			<br>
	   		<div style="padding-top: -20px; ">
		   		<table cellspacing="0" style="width: 34%; font-size: 8pt;text-align: center;">
					<?php  
					

				    $arregloEmp = $DAvBOLETA3->SeleccionarIngresos(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
				    foreach($arregloEmp as $Item){
				    	$desingre=trim($Item['DE_CPTO']);
				    	$numingre=trim($Item['NU_DATO_CALC']);
				    	$dias_in=trim($Item['NU_DATO_CALCS']);
				    ?>
			        <tr>
			           
					   	<td style="width:61%;" align="left"><?php echo $desingre ?></td>
					  	<td style="width:4%;" align="right"><?php echo $dias_in ?></td>
					  	<td style="width:4%;" align="right">&nbsp;</td>
					   	<td style="width:25%;" align="right"><?php echo $numingre ?></td>
					
			        </tr>
			       <?php  
					}
					?>
			       
		   		</table>
	   		</div>
	   		<div style="padding-top: -40px; padding-left: 250px;">
		   		<table cellspacing="0" style="width: 34%; text-align: center; font-size: 8pt;  ">
					<?php  
					

				    $arregloEmp = $DAvBOLETA3->SeleccionarDeducciones(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
				    foreach($arregloEmp as $Item){
				    	$desdedu=trim($Item['DE_CPTO']);
				    	$numdedu=trim($Item['NU_DATO_CALC']);
				    ?>
				    
			        <tr>
			           	<td style="width:91%;" align="left"><?php echo $desdedu ?></td>
					  	<td style="width:11%;" align="right">&nbsp;</td>
					  	<td style="width:11%;" align="right">&nbsp;</td>
					   	<td style="width:25%;" align="right"><?php echo $numdedu ?></td>
					
			        </tr>
			       <?php  
					}
					?>
			       
		   		</table>
		   	</div>
   			<div style="padding-top: -67px; padding-left: 480px;">
		   		<table cellspacing="0" style="width: 34%; text-align: center; font-size: 8pt;  ">
					<?php  
					

				    $arregloEmp = $DAvBOLETA3->SeleccionarAportaciones(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
				    foreach($arregloEmp as $Item){
				    	$desaporta=trim($Item['DE_CPTO']);
				    	$numaporta=trim($Item['NU_DATO_CALC']);
				    ?>
				    
			        <tr>
			           	<td style="width:91%;" align="left"><?php echo $desaporta ?></td>
					  	<td style="width:27%;" align="right">&nbsp;</td>
					  	<td style="width:27%;" align="right">&nbsp;</td>
					   	<td style="width:25%;" align="right"><?php echo $numaporta ?></td>
					
			        </tr>
			       <?php  
					}
					?>
			       
		   		</table>
		   	</div>-->
		   <br><br><br><br><br><br>
	   		<table cellspacing="0" style="width: 95%; text-align: center; font-size: 8pt;">
			<?php  
			 
		    $arregloEmp = $DAvBOLETA3->SeleccionarDeduccionesSuma(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
		    foreach($arregloEmp as $Item){
		    	$sumadedu=trim($Item['SUM']);
		    }
		   
		    $arregloEmp = $DAvBOLETA3->SeleccionarIngresosSuma(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
		    foreach($arregloEmp as $Item){
		    	$sumaingre=trim($Item['SUM']);
		    }

		    $arregloEmp = $DAvBOLETA3->SeleccionarAportacionesSuma(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
		    foreach($arregloEmp as $Item){
		    	$sumaapor=trim($Item['SUM']);
		    }


		    $arregloEmp = $DAvBOLETA3->SeleccionarTotalBoleta(str_pad($co_trab, 8, "0", STR_PAD_LEFT),$anno,$peri);
		    foreach($arregloEmp as $Item){
		    	$sumatotal=trim($Item['SUMATOT']);
		    }

		    $total=$sumaingre-$sumadedu;

		    $sum_total=number_format($total, 2, '.', '');

			?>
		         <tr>
		           	<td style="width:20%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left">TOTAL INGRESOS</td>
				  	<td style="width:2%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="left">&nbsp;</td>
				  	<td style="width:1%; border-top: 1px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000 " align="left">&nbsp;</td>
				   	<td style="width:7%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="right"><?php echo $sumaingre ?></td>

					<td style="width:20%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left">TOTAL DESCUENTOS</td>
				  	<td style="width:2%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="left">&nbsp;</td>
				  	<td style="width:3%; border-top: 1px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-left: 1px solid #000000 " align="left">&nbsp;</td>
				   	<td style="width:7%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="right"><?php echo $sumadedu ?></td>

				   	<td style="width:20%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left">TOTAL APORTACION</td>
				  	<td style="width:2%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="left">&nbsp;</td>
				  	<td style="width:5%; border-top: 1px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000 " align="left">&nbsp;</td>
				   	<td style="width:7%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="right"><?php echo $sumaapor ?></td>
				   	
		        </tr>

	        <tr>
	           	<td style="width:20%; " align="left">&nbsp;</td>
			  	<td style="width:5%; " align="left">&nbsp;</td>
			  	<td style="width:2%; " align="left">&nbsp;</td>
			   	<td style="width:5%; " align="right">&nbsp;</td>

				<td style="width:20%; " align="left">&nbsp;</td>
			  	<td style="width:5%; " align="left">&nbsp;</td>
			  	<td style="width:4%;" align="left">&nbsp;</td>
			   	<td style="width:5%; " align="right">&nbsp;</td>

			   	<td style="width:20%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left">NETO A PAGAR</td>
			  	<td style="width:5%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; " align="left">&nbsp;</td>
			  	<td style="width:6%; border-top: 0px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000 " align="left">&nbsp;</td>
			   	<td style="width:5%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="right"><?php echo $sum_total ?></td>
			   	
	        </tr>
	       
	       
	   		</table>
 
	<br><br><br>
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
			  $pue_repre='GERENTE DE RECURSOS HUMANOS';
			}else{
			  $pue_repre='GERENTE CENTRAL DE ADMINISTRACION Y FINANZAS';
			}
   			if ($co_emp=='01') {   			
   			?>
   			<tr>
	           	<th style="width:51%;"><img src="../../inc/img/firma-ytalo.png"></th>
			   	<th style="width:51%;"></th>
			   	
	        </tr>
		    <?php }else{ 

			?>
			<tr>
	           	<th style="width:51%;"><img src="../../inc/img/firma-huarcaya.png"></th>
			   	<th style="width:51%;"></th>
			</tr>
			<?php 
			}
			?>
			<tr>
	           	<th style="width:51%;">____________________________________________</th>
			   	<th style="width:51%;">____________________________________________________</th>
			   	
	        </tr>
	        <tr>
	           	<th style="width:51%;"><?php echo $razon_social ?></th>
			   	<th style="width:51%;">RECIBI CONFORME: <?php echo $nom ?></th>
			   	
	        </tr>
	        <tr>
	           	<th style="width:51%;"><?php echo $repre ?></th>
			   	<th style="width:51%;">&nbsp;</th>
			   	
	        </tr>
	        <tr>
	           	<th style="width:51%;"><?php echo $pue_repre ?></th>
			   	<th style="width:51%;">&nbsp;</th>
			   	
	        </tr>
	    
			
			   	
	        
	       
   		</table>

<br><br><br>
	
	
	
	
	  

</page>

