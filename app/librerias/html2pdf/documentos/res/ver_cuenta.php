
<?php session_start();

if(!isset($_SESSION["serviciolinea"])) {header("Location: index.php");}

define('RAIZ',$_SERVER['DOCUMENT_ROOT']);

include_once(RAIZ."/servicio-online/bin/Bin.php");
include_once(RAIZ."/servicio-online/bin/Libreria/Conexion.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionOfiplan.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionDashboard.php");

$BASE=Bin::Factory("Lib","Base");

$vBOLETA1 = Bin::Factory("Neg","vBOLETA1");
$DAvBOLETA1=Bin::Factory("Mod","DAvBOLETA1");

$vBOLETA2 = Bin::Factory("Neg","vBOLETA2");
$DAvBOLETA2=Bin::Factory("Mod","DAvBOLETA2");


$vBOLETA3 = Bin::Factory("Neg","vBOLETA3");
$DAvBOLETA3=Bin::Factory("Mod","DAvBOLETA3");


$VTM_PERI = Bin::Factory("Neg","VTM_PERI");
$DAVTM_PERI=Bin::Factory("Mod","DAVTM_PERI");


$vTM_EVAL_CUAT = Bin::Factory("Neg","vTM_EVAL_CUAT");
$DAvTM_EVAL_CUAT=Bin::Factory("Mod","DAvTM_EVAL_CUAT");


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
	    }else{
	    	$razon_social='INMOBILIARIA CASCAJAL S.A.C.</span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20465062356';
	    }


        ?>

            <td style="width: 50%; color: black;font-size:12px;text-align:left">
                <span style="color: black;font-size:14px;font-weight:bold"><?php echo $razon_social ?>
            </td>
			<td style="width: 25%; color: #444444;">
                
                
            </td>
			<td style="width: 25%;text-align:right">
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
				 <?php }?>
				
			</td>
			
        </tr>
    </table>
    
    <br>
    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 11pt; background-color: #F0EDEC;" >
        
		<tr>
			
           <td style="width:100%; height: 20px" >
			<?php 
			$arregloEmp = $DAVTM_PERI->ListarPeriodo($co_emp,$anno,$peri);
                foreach($arregloEmp as $Item){
                	
            		$ANNOIN = substr(trim($Item["FEC_INICIO"]),0,4);
					$DIAIN = substr(trim($Item["FEC_INICIO"]),-2); 
					$MESIN = substr(trim($Item["FEC_INICIO"]),-5,2); 
  					$inicio_peri=$DIAIN.'/'.$MESIN.'/'.$ANNOIN;

                	
                	$ANNOFIN = substr(trim($Item["FEC_FIN"]),0,4);
  					$DIAFIN = substr(trim($Item["FEC_FIN"]),-2); 
  					$MESFIN = substr(trim($Item["FEC_FIN"]),-5,2); 
  					$fin_peri=$DIAFIN.'/'.$MESFIN.'/'.$ANNOFIN;

                }

			        $periodo="<b>CATEGORIZACIÓN CUATRIMESTRAL ALMACENERO Y CHEQUEADOR</b>";
			      
				echo $periodo;
				
			?>
			
		   </td>
		  
        </tr>
        
   
    </table>
    <?php  


    $arregloEmp = $DAvTM_EVAL_CUAT->ListarCate_Cuatri($co_trab,$anno,$peri);
    foreach($arregloEmp as $Item){

    	$co_trab=trim($Item['CO_TRAB']);
    	$nom=utf8_encode(trim($Item['NOMBRES_Y_APELLIDOS']));
    	$peri_eva=trim($Item['DE_PERI_CUAT']);
  


    	
    	$puesto=trim($Item['DE_PUES_TRAB']);
    	$area=utf8_encode(trim($Item['DE_AREA']));

    	$inventario=round((trim($Item['NU_EVA_INV']))*100);
    	$administrador=round((trim($Item['NU_EVA_ADM']))*100);
    	$rotativos=round((trim($Item['NU_EVA_ROT']))*100);
    	$productividad=round((trim($Item['NU_EVA_PRO']))*100);
    	$asistencia=(trim($Item['NU_EVA_ASI']))*100;
    	$ocurrencias='0';
    	$tot_evaluacion=round((trim($Item['TOTAL_EVALUACION']))*100,2);

    	$cate_tienda=trim($Item['NU_CAT_TIE']);
    	$rango=(trim($Item['NU_RANGO_2']));

    	$co_cali=(trim($Item['NU_RANGO']));

    	$peri_eva_sig=trim($Item['DE_PERI_CUAT_SIG']);

    	$sueldo=trim($Item['NU_SUEL_ACT']);
    	$imp_bono=trim($Item['NU_IMP_CUAT']);

    	$sueldo_mas_bono=number_format($sueldo+$imp_bono, 2, ".", " ");  
    	//valor, cant_decimales, separador_decimal, separador_miles

    }
    ?>
    
       <br>
		<table cellspacing="0" style="width: 100%; text-align: left; font-size: 9pt; vertical-align: middle;" >
	        <tr>
	           	<th style="width:20%; height: 2%; padding-left: 5px">Periodo de Evaluación:</th>
			  	<th style="width:45%;height: 2%; padding-left: 5px; padding-top: -10px"><?php echo $peri_eva ?></th>

	        </tr>
			<tr>
	           	<th style="width:20%; padding-left: 5px">DNI:</th>
			  	<td style="width:45%;padding-left: 5px; background-color: #F0EDEC;"><?php echo $co_trab ?></td>

	        </tr>
	        <tr>
	           	<th style="width:20%; padding-left: 5px">Colaborador:</th>
			  	<td style="width:45%;padding-left: 5px; background-color: #F0EDEC;"><?php echo $nom ?></td>

	        </tr>
	        <tr>
	           	<th style="width:20%; padding-left: 5px">Puesto:</th>
			  	<td style="width:45%;padding-left: 5px; background-color: #F0EDEC;"><?php echo $puesto ?></td>

	        </tr>
	        <tr>
	           	<th style="width:20%; padding-left: 5px">Tienda:</th>
			  	<td style="width:45%;padding-left: 5px; background-color: #F0EDEC;"><?php echo $area ?></td>

	        </tr>
       </table>
       <p style="font-size: 9pt;">Para la categorización cuatrimestral se tomaron en cuenta los siguientes criterios de evaluación, las cuales pasamos a detallar:</p>
      
       <table cellspacing="0" style="width: 100%; text-align: left; font-size: 9pt;">
			<tr>
	           	<th style="width:27%; height: 2%;"></th>
			 
			   	<th style="width:19%; height: 2%;text-align: center;">CALIFICACIÓN</th>
			   	<th style="width:19%; height: 2%;text-align: center;">PESO</th>
	        </tr>
			<tr>
	           	<td style="width:27%; ">1. Evaluación de Inventarios:</td>
			   	
			   	<td style="width:19%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $inventario.'%' ?></td>
			   	<td style="width:19%; padding-left: 5px;text-align: right;">30%</td>
	        </tr>
	        <tr>
	           	<td style="width:27%; ">2. Evaluación del Administrador:</td>
			   	
			   	<td style="width:19%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $administrador.'%' ?></td>
			   	<td style="width:19%; padding-left: 5px;text-align: right;">20%</td>
	        </tr>
	        <tr>
	           	<td style="width:27%; ">3. Uso de Rotativos:</td>
			   	
			   	<td style="width:19%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $rotativos.'%' ?></td>
			   	<td style="width:19%; padding-left: 5px;text-align: right;">20%</td>
	        </tr>
	        <tr>
	           	<td style="width:27%; ">4. Productividad:</td>
			   	
			   	<td style="width:19%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $productividad.'%' ?></td>
			   	<td style="width:19%; padding-left: 5px;text-align: right;">10%</td>
	        </tr>
	        <tr>
	           	<td style="width:27%; ">5. Asistencias:</td>
			   	
			   	<td style="width:19%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $asistencia.'%' ?></td>
			   	<td style="width:19%; padding-left: 5px;text-align: right;">20%</td>
	        </tr>
	        <tr>
	           	<td style="width:25%; ">6. Ocurrencias:</td>
			   	
			   	<td style="width:19%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $ocurrencias.'%' ?></td>
			   	<td style="width:19%; padding-left: 5px;text-align: right;">Todo o nada</td>
	        </tr>
	        <tr>
	           	<th style="width:25%; ">TOTAL EVALUACIÓN:</th>
			   	
			   	<th style="width:19%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $tot_evaluacion.'%' ?></th>
			   	<td style="width:19%; padding-left: 5px;text-align: right;"></td>
	        </tr>
   		</table>
   		<br>
   		<table cellspacing="0" style="width: 100%; text-align: left; font-size: 9pt;">
			<tr>
			    <th style="width:46%; height: 2%;text-align: left;">Categoría Tienda:</th>
			   	<th style="width:19%; height: 2%;text-align: center;background-color: #F0EDEC;"><?php echo $cate_tienda ?></th>
	        </tr>

	        <tr>
			    <th style="width:46%; height: 2%;text-align: left;">Categorización cuatrimestral:</th>
			   	<th style="width:19%; height: 2%;text-align: center;"></th>
	        </tr>

	        <tr>
			    <th style="width:5%; height: 2%;text-align: right;padding-right: 40px">Rango:</th>
			   	<th style="width:19%; height: 2%;text-align: center;background-color: #F0EDEC;"><?php echo $rango ?></th>
	        </tr>
	        <tr>
			    <th style="width:5%; height: 2%;text-align: right;padding-right: 10px">Calificación:</th>
			   	<th style="width:19%; height: 2%;text-align: center;background-color: #F0EDEC;"><?php echo $co_cali ?></th>
	        </tr>
	        <tr>
			    <th style="width:46%; height: 2%;text-align: left;">Bono mensual a recibir solo durante el siguiente cuatrimestre:</th>
			   	<th style="width:19%; height: 2%;text-align: center;background-color: #F0EDEC; font-size: 7.5pt;"><?php echo $peri_eva_sig ?></th>
	        </tr>


		</table>
		<br>
		<br>
   		<table cellspacing="0" style="width: 100%; text-align: right; font-size: 9pt;">
			<tr>
			    <th style="width:46%; height: 2%;text-align: left;">Sueldo actual:</th>
			   	<th style="width:19%; height: 2%;text-align: right;background-color: #F0EDEC;"><?php echo 'S/. '.$sueldo ?></th>
	        </tr>
	        <tr>
			    <th style="width:46%; height: 2%;text-align: left;">Importe Bono Cuatrimestral a recibir:</th>
			   	<th style="width:19%; height: 2%;text-align: right;background-color: #F0EDEC;"><?php echo 'S/. '.$imp_bono ?></th>
	        </tr>
	        <tr>
			    <th style="width:46%; height: 2%;text-align: left;"></th>
			   	<th style="width:19%; height: 2%;text-align: right;background-color: #F0EDEC;"></th>
	        </tr>
	        <tr>
			    <th style="width:46%; height: 2%;text-align: left;">Sueldo Actual + Bono cuatrimestral a recibir:</th>
			   	<th style="width:19%; height: 2%;text-align: right;background-color: #F0EDEC;"><?php echo 'S/. '.$sueldo_mas_bono ?></th>
	        </tr>
		</table>
		<br><br>
		 <p style="font-size: 9pt;"><b>Queda constancia de haber recibido este documento.</b></p>
		 <br><br><br><br><br><br><br><br><br>

   		<table cellspacing="0" style="width: 102%; text-align: left; font-size: 9pt;">
   			
			<tr>
	           	<th style="width:100%; padding-left: 200px">____________________________________________________________________</th>
			   	
	        </tr>
	        <tr>
			   	<th style="width:100%;text-align: left; padding-left: 200px">NOMBRE: <?php echo $nom ?></th>
			   	
	        </tr>
	        <tr>
			   	<th style="width:100%;text-align: left;padding-left: 200px">DNI: <?php echo $co_trab ?></th>
			   	
	        </tr>
	        
	    
			
			   	
	        
	       
   		</table>
 <!--
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
			  	<th style="width:19%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DIAS NO TRAB./NO SUBS.</th>
			   	<th style="width:10%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DIAS TRAB.</th>
			   	<th style="width:11%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">HORAS TRAB</th>
			   	<th style="width:17%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">HRS. EXT. SIMP. 25%</th>
			   	<th style="width:17%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">HRS. EXT. SIMP. 35%</th>
			   	<th style="width:13%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">HRS. EXT. DOBLES</th>
	        </tr>
			<tr>
				<?php 
				$arregloEmp = $DAvBOLETA3->SeleccionarDias($co_trab,$anno,$peri);
                foreach($arregloEmp as $Item){
                	$dias=trim($Item['NU_DATO_CALC']);
                }

                $arregloEmp = $DAvBOLETA3->SeleccionarHoras($co_trab,$anno,$peri);
                foreach($arregloEmp as $Item){
                	$hora=trim($Item['NU_DATO_CALC']);
                }

                $arregloEmp = $DAvBOLETA3->SeleccionarHoras25($co_trab,$anno,$peri);
                foreach($arregloEmp as $Item){
                	$hora25=trim($Item['NU_DATO_CALC']);
                }


                $arregloEmp = $DAvBOLETA3->SeleccionarHoras35($co_trab,$anno,$peri);
                foreach($arregloEmp as $Item){
                	$hora35=trim($Item['NU_DATO_CALC']);
                }


                $arregloEmp = $DAvBOLETA3->SeleccionarHorasDobles($co_trab,$anno,$peri);
                foreach($arregloEmp as $Item){
                	$horado=trim($Item['NU_DATO_CALC']);
                }
				?>
	           	<td style="font-size: 8pt; width:15%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">0.00</td>
			  	<td style="font-size: 8pt; width:20%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo 15-$dias ?></td>
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
				$arregloEmp = $DAvBOLETA2->ListarVacaciones1($co_trab,$anno,$peri);
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


			    $arregloEmp = $DAvBOLETA2->ListarVacaciones2($co_trab,$anno,$peri);
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

							    $arregloEmp = $DAvBOLETA3->SeleccionarIngresos($co_trab,$anno,$peri);
							    foreach($arregloEmp as $Item){
							    	$desingre=trim($Item['DE_CPTO']);
							    	$numingre=trim($Item['NU_DATO_CALC']);
							    	$dias_in=trim($Item['NU_DATO_CALCS']);
							    ?>
				           		<tr>
				           			<td style="padding-left: 30px" align="left"><?php echo $desingre ?></td>
				           			<td style="padding-left: 65px;" align="right"><?php echo $numingre ?></td>
				           		</tr>
				           		<?php  
								}
								?>
				           	</table>
			           </td>
					  	<td style="width:33.33333333%;">
					  		<table cellspacing="0" style="width: 100%;">
			           			<?php  

							    $arregloEmp = $DAvBOLETA3->SeleccionarDeducciones($co_trab,$anno,$peri);
							    foreach($arregloEmp as $Item){
							    	$desdedu=trim($Item['DE_CPTO']);
							    	$numdedu=trim($Item['NU_DATO_CALC']);
							    ?>
				           		<tr>
				           			<td style="padding-right: : 70px" align="left"><?php echo $desdedu ?></td>
				           			<td style="padding-left: 60px;" align="right"><?php echo $numdedu ?></td>
				           		</tr>
				           		<?php  
								}
								?>
				           	</table>
					  	</td>
					   	<td style="width:33.33333333%;">
					   		<table>
			           			<?php  

							    $arregloEmp = $DAvBOLETA3->SeleccionarAportaciones($co_trab,$anno,$peri);
							    foreach($arregloEmp as $Item){
							    	$desaporta=trim($Item['DE_CPTO']);
							    	$numaporta=trim($Item['NU_DATO_CALC']);
							    ?>
				           		<tr>
				           			<td style="padding-right: : 70px" align="left"><?php echo $desaporta ?></td>
				           			<td style="padding-left: 115px;" align="right"><?php echo $numaporta ?></td>
				           		</tr>
				           		<?php  
								}
								?>
				           	</table>
					   	</td>
					   	
			        </tr>
			       
		   		</table>
   			</div>--><!--
   			<hr>
			
   			<br>
	   		<div style="padding-top: -20px; ">
		   		<table cellspacing="0" style="width: 34%; font-size: 8pt;text-align: center;">
					<?php  
					

				    $arregloEmp = $DAvBOLETA3->SeleccionarIngresos($co_trab,$anno,$peri);
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
					

				    $arregloEmp = $DAvBOLETA3->SeleccionarDeducciones($co_trab,$anno,$peri);
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
					

				    $arregloEmp = $DAvBOLETA3->SeleccionarAportaciones($co_trab,$anno,$peri);
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
		   	</div>--><!--
		   <br><br><br><br><br><br>
   		<table cellspacing="0" style="width: 95%; text-align: center; font-size: 8pt;">
			<?php  
			 
		    $arregloEmp = $DAvBOLETA3->SeleccionarDeduccionesSuma($co_trab,$anno,$peri);
		    foreach($arregloEmp as $Item){
		    	$sumadedu=trim($Item['SUM']);
		    }
		   
		    $arregloEmp = $DAvBOLETA3->SeleccionarIngresosSuma($co_trab,$anno,$peri);
		    foreach($arregloEmp as $Item){
		    	$sumaingre=trim($Item['SUM']);
		    }

		    $arregloEmp = $DAvBOLETA3->SeleccionarAportacionesSuma($co_trab,$anno,$peri);
		    foreach($arregloEmp as $Item){
		    	$sumaapor=trim($Item['SUM']);
		    }


		    $arregloEmp = $DAvBOLETA3->SeleccionarTotalBoleta($co_trab,$anno,$peri);
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
 -->
	
  
    
	
	
	
	
	  

</page>

