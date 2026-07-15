<?php 
session_start();
if(!isset($_SESSION['LoggedIn'])) {header("Location: ../../../../../index.php");}
/*	define('RAIZ',$_SERVER['DOCUMENT_ROOT']);	*/
include("../../../../includes/conectar_bd2.php");
/*	colocar query2s	*/
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
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;
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
 /** He colocado la variable en duro**/       
        $co_emp = '01';
        if ($co_emp=='01') {
        	$razon_social='NOMBRE DE COMPAÑIA S.A.';
        	$razon_social_DIR='JR. TU-DIRECCION N° 153  URB. MIRAFLORES';
        	$razon_social_RUC=' RUC 20100000000';
        //	   	$razon_social='NOMBRE DE COMPAÑIA S.A.'.' </span><br>'.$razon_social_DIR='JR. TU-DIRECCION N° 153  URB. MIRAFLORES'.'<br>'.$razon_social_RUC=' RUC 20100000000';
	   //   	$razon_social='TAI LOY S.A. </span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20100049181';
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
                <span style="color: black;font-size:14px;font-weight:bold"><?php echo $razon_social ?> </span><br><?php echo $razon_social_DIR ?><br><?php echo $razon_social_RUC ?>
            </td>
			<td style="width: 25%; color: #444444;">

            </td>
			<td style="width: 25%;text-align:right">
				 <?php if ($co_emp=='01') { ?>
			 		<img src='../../../../img/tulogoaqui.png' style='    max-width: 70px;
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
        </tr>
    </table>

    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 11pt;">
        
		<tr>
			<td style="width:25%;" ><b>Año:</b> 
			<?php 
					$anno= '2020';
					$peri= '01';
					echo $anno ?><br><b>Periodo:</b> <?php echo $peri 
			?>
			</td>
           <td style="width:50%;" >
			
		   </td>
		   <td style="width:25%;" >
			</td>
        </tr>
    </table>
     <br>
	<table cellspacing="0" style="width: 100%; text-align: center; font-size: 7.7pt; vertical-align: middle" >
	        <tr>
	           	<th style="width:8%; height: 2%; border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CODIGO</th>
			  	<th style="width:45%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">APELLIDOS Y NOMBRES</th>
			   	<th style="width:15%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CALIFICACION</th>
			   	<th style="width:13%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">FECHA ING.</th>
			   	<th style="width:13%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">FECHA CESE</th>
			   	<th style="width:9%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">
			   	SUELDO</th>
	        </tr>
			<tr>
	           	<td style="width:8%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $co_trab ?></td>
			  	<td style="width:45%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $nom ?></td>
			   	<td style="width:15%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $cali ?></td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $fec_in ?></td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $fec_ce ?></td>
			   	<td style="width:9%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php //echo $salario22; ?></td>
	        </tr>
       </table>
       <table cellspacing="0" style="width: 100%; text-align: center; font-size: 7.7pt;">
			<tr>
	           	<th style="width:18%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DOC. IDEN.</th>
			   	<th style="width:27%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">REGIMEN PENSIONARIO</th>
			   	<th style="width:28%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">SEDE</th>
			   	<th style="width:30%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">
			   	CUENTA BANCARIA</th>
	        </tr>
			<tr>
	           	<td style="width:18%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $doc_iden ?></td>
			   	<td style="width:27%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $de_afp.'-'.$num_afp ?></td>
			   	<td style="width:28%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $sede ?></td>
			   	<td style="width:30%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php //echo $nom_banco.'-'.$num_cuenta ?></td>
	        </tr>
   		</table>

   		<table cellspacing="0" style="width: 100%; text-align: center; font-size: 7.7pt;">
			<tr>
	           	<th style="width:34%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CENTRO DE COSTO (PRINCIPAL)</th>
			  	<th style="width:25%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">AREA</th>
			   	<th style="width:27%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CARGO</th>
			   	<th style="width:17%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">
			   	CATEGORIA</th>
	        </tr>
			<tr>
	           	<td style="width:34%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $num_costo.'-'.$nom_costo ?></td>
			  	<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $area ?></td>
			   	<td style="width:27%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $puesto ?></td>
			   	<?php 
			   		if ($anno >='2018') { ?>
			   			<td style="width:17%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php //echo $cate ?></td>
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
			   	<td style="font-size: 8pt; width:15%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">0.00</td>
			  	<td style="font-size: 8pt; width:20%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $dias_no_trabajados ?></td>
			   	<td style="font-size: 8pt; width:10%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $dias ?></td>
			   	<td style="font-size: 8pt; width:11%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $hora ?></td>
			   	<td style="font-size: 8pt; width:17%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $hora25 ?></td>
			   	<td style="font-size: 8pt; width:17%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $hora35 ?></td>
			   	<td style="font-size: 8pt; width:13%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php //echo $horado ?></td>
			   	
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
	           	<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $peri_vaca1?></td>
			  	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $inicio_vac1 ?></td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $fin_vac1 ?></td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $inicio_vac2 ?></td>
			   	<td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $fin_vac2 ?></td>
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
			  	           		<tr>
				           			<td style="padding-left: 30px" align="left"><?php //echo $desingre ?></td>
				           			
				           			<td style="padding-left: 3px" align="left"><?php /*if ($desingre=='VACACIONES') {
				           				echo $dias_vacaciones;
				           			}elseif($desingre=='HABER BASICO'){
				           				echo $dias;
				           			}else{
				           				echo '';
				           			} */?></td>
				           			<td style="padding-left: 45px;" align="right"><?php //echo $numingre ?></td>
				           		</tr>
				           		
				           	</table>
			           </td>
					  	<td style="width:33.33333333%;">
					  		<table cellspacing="0" style="width: 100%;">
			           			
				           		<tr>
				           			<td style="padding-right: : 70px" align="left"><?php //echo $desdedu ?></td>
				           			
				           			<td style="padding-left: 45px;" align="right"><?php // echo $numdedu ?></td>
				           		</tr>
				           		
				           	</table>
					  	</td>
					   	<td style="width:33.33333333%;">
					   		<table>

				           		<tr>
				           			<td style="padding-right: : 70px" align="left"><?php /*echo $desaporta*/ ?></td>
				           			
				           			<td style="padding-left: 110px;" align="right"><?php /*echo $numaporta*/ ?></td>
				           		</tr>
				          
				           	</table>
					   	</td>
					   	
			        </tr>
			       
		   		</table>
   			</div>

		   <br><br><br><br><br><br>
	   		<table cellspacing="0" style="width: 95%; text-align: center; font-size: 8pt;">
			
		         <tr>
		           	<td style="width:20%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left">TOTAL INGRESOS</td>
				  	<td style="width:2%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="left">&nbsp;</td>
				  	<td style="width:1%; border-top: 1px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000 " align="left">&nbsp;</td>
				   	<td style="width:7%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="right"><?php //echo $sumaingre ?></td>

					<td style="width:20%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left">TOTAL DESCUENTOS</td>
				  	<td style="width:2%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="left">&nbsp;</td>
				  	<td style="width:3%; border-top: 1px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-left: 1px solid #000000 " align="left">&nbsp;</td>
				   	<td style="width:7%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="right"><?php //echo $sumadedu ?></td>

				   	<td style="width:20%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left">TOTAL APORTACION</td>
				  	<td style="width:2%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="left">&nbsp;</td>
				  	<td style="width:5%; border-top: 1px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000 " align="left">&nbsp;</td>
				   	<td style="width:7%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="right"><?php //echo $sumaapor ?></td>
				   	
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
			   	<td style="width:5%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="right"><?php //echo $sum_total ?></td>
	        </tr>
  		</table>
 
	<br><br><br>
   		<table cellspacing="0" style="width: 102%; text-align: center; font-size: 8pt;">
   			<?php  
   			if ($co_emp=='01') {
   			//	 $razon_social;
			//  $razon_social='TAI LOY S.A.';
			}elseif ($co_emp=='02') {
			  $razon_social='COMERCIAL LUCIANO AREQUIPA S.A.C.';
			}elseif ($co_emp=='03') {
			  $razon_social='COPY VENTAS S.R.L.';
			}else{
			  $razon_social='SUPLACORP S.A. C.';
			}

			if ($co_emp=='01') {
			  $repre='NOMBRE DE GERENTE DE RRHH';
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
	           	<th style="width:51%;"><img src="../../../../img/firma-ytalo.png"></th>
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
			   	<th style="width:51%;">RECIBI CONFORME: <?php //echo $nom ?></th>
			   	
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

    <br><br><br><img src="../../images/icon/tijera.png" style="height: 15px;width: 15px">---------------------------------------------------------------------------------------------------------------------------------
	 <table cellspacing="0" style="width: 100%; text-align: center; font-size: 11pt;">
    	<tr>
			<td style="width:25%;" >	</td>
            <td style="width:50%;" >	<b>DÍAS DE AUSENCIA</b>			</td>
			<td style="width:25%;" >	</td>
		</tr>
	</table>
		<br>
		<table cellspacing="0" style="width: 95%; text-align: center; font-size: 9pt;">
			<tr>
	           	<th style="width:20%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;border-left: 1px solid #000000" >AÑO</th>
			  	<th style="width:13%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " >PERIODO</th>
			  	<th style="width:40%; border-top: 1px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000 " >FECHA</th>
			   	<th style="width:35%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000;" >OBSERVACIÓN</th>
	        </tr>

	        <tr>
	           	<td style="width:20%;  border-bottom: 1px solid #000000; border-right: 1px solid #000000;border-left: 1px solid #000000"><?php //echo $ANNO_AUSENCIA_2 ?></td>
			  	<td style="width:13%;  border-bottom: 1px solid #000000; "><?php //echo $PERI_AUSENCIA_2 ?></td>
			  	<td style="width:40%;  border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000 "><?php //echo $DIA_AUSENCIA_2?></td>
			   	<td style="width:35%;  border-bottom: 1px solid #000000; border-right: 1px solid #000000;" >AUSENCIA / DÍAS SIN MARCACIÓN</td>
	        </tr>
		</table>

<br><br><br>

</page>
