
<?php session_start();

if(!isset($_SESSION["serviciolinea"])) {header("Location: index.php");}

define('RAIZ',$_SERVER['DOCUMENT_ROOT']);

include_once(RAIZ."/servicio-online/bin/Bin.php");
include_once(RAIZ."/servicio-online/bin/Libreria/Conexion.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionOfiplan.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionDashboard.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionRetributivo.php");


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

$vTM_BSC_CUAT = Bin::Factory("Neg","vTM_BSC_CUAT");
$DAvTM_BSC_CUAT=Bin::Factory("Mod","DAvTM_BSC_CUAT");

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
    <style type="text/css">
        <?php //TAILOY
            if($co_emp=='01') { ?>
                #color1{background-color: rgb(7,167,103); 
            }
    <?php }
            //LUCIANO
            elseif ($co_emp=='02') {?>
                #color1{background-color: rgb(93, 178,225);
            }
    <?php }
            //COPYVENTAS
            elseif($co_emp=='03'){?>
                #color1{background-color: rgb(245, 153, 153);
            }
    <?php }
            //SUPLACORP
            elseif($co_emp=='04'){?>
                #color1{background-color: rgb(44, 118, 179);
            }
    <?php }
            //CASCAJAL
            elseif($co_emp=='05'){?>
                #color1{background-color: rgb(144, 148, 152);
            }
    <?php }?>
    </style>
	<table cellspacing="0" style="width: 100%; " id="color1">
        <tr>

            <td style="width: 75%; color: white;font-size:13px;text-align:center">
                <span style="color: white;font-size:20px;font-weight:bold">Resultados  Evaluación BSC <?php echo $anno ?></span>
            </td>
			
			<td style="width: 25%;text-align:right">
				<?php if ($co_emp=='001') { ?>
			 	<img src='../../images/icon/logo-tailoy.png' style='    max-width:50px;
				    height: 50px;
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
    <table cellspacing="0" style="width: 100%; background: black">
		<tr>
            <td style="width: 100%; color: white;font-size:15px;text-align:left;padding-left: 5px">
                <span style="color: white;font-size:14px;font-weight:bold">Periodo / Tienda</span>
            </td>
		</tr>
    </table>
  
    <?php  


    $vTM_BSC_CUAT->CO_AREA=$co_area;
	$vTM_BSC_CUAT->CO_EMPR=$co_emp;
	$vTM_BSC_CUAT->CO_DEPA=$co_depa;

	
    $arregloEmp = $DAvTM_BSC_CUAT->ListarBSC_CuatriAnual($vTM_BSC_CUAT,$anno);

    foreach($arregloEmp as $Item){

    	$des_area=trim($Item['DE_AREA']);
    	

    	$peri_des=$peri_eva.' Anual '.$anno;
  
    	
    	$real='S/. '.number_format(trim($Item['VENT_ANUAL']), 2, ".", ",");  
    	$ppto='S/. '.number_format(trim($Item['CUOT_ANUAL']), 2, ".", ",");  
    	$vs=round((trim($Item['VAR_ALC_VEN_ANUAL']))*100,2).'%';



    	$ratio_venta=trim($Item['RATIO_VENTA']);
    	$rango_venta=number_format((trim($Item['RANGO_VENTA']))*100, 2, ".", " ").'%';
    	$score_venta=number_format((trim($Item['SCORE_REAL_VENTA']))*100, 2, ".", " ").'%';

    	$obj_util_pe=number_format((trim($Item['PROM_CLUSTER_ALC_UTOP_ANUAL']))*100, 2, ".", " ").'%';
    	$real_util_ope='S/. '.trim($Item['UTOP_ANUAL']);  
    	$ppto_util_ope='S/. '.trim($Item['UTOP_PPTo']);  
    	$vs_util_ope=number_format((trim($Item['VAR_ALC_CLUSTER_ALC_UTOP_ANUAL']))*100, 2, ".", " ").'%';
    	$ratio_util_ope=trim($Item['RATIO_UTIL_OPE']);
    	$rango_util_ope=number_format((trim($Item['RANGO_UTIL_OPE']))*100, 2, ".", " ").'%';
    	$score_util_ope=number_format((trim($Item['SCORE_REAL_UTIL_OPE']))*100, 2, ".", " ").'%';

    	$obj_inven=number_format((trim($Item['PPTo_Inv']))*100, 4, ".", " ").'%';
    	$real_inven='S/. '.trim($Item['INVE_ANUAL']);  
    	$ppto_inven='S/. '.number_format(trim($Item['PPTo_INVE_ANUAL']), 2, ".", ",");  
    	$vs_inven=number_format((trim($Item['VAR_ALC_INVE_ANUAL']))*100, 2, ".", " ").'%';
    	$ratio_inven=trim($Item['RATIO_INVENTARIO']);
    	$rango_inven=number_format((trim($Item['RANGO_INVENTARIO']))*100, 2, ".", " ").'%';
    	$score_inven=number_format((trim($Item['SCORE_REAL_INVENTARIO']))*100, 2, ".", " ").'%';

 

    	$obj_merma=number_format((trim($Item['PPTo_MERM']))*100, 4, ".", " ").'%';
    	$real_merma='S/. '.trim($Item['MERM_ANUAL']);  
    	$ppto_merma='S/. '.number_format(trim($Item['PPTo_MERM_ANUAL']), 2, ".", ",");  
    	$vs_merma=number_format((trim($Item['VAR_ALC_MERM_ANUAL']))*100, 2, ".", " ").'%';
    	$ratio_merma=trim($Item['RATIO_MERMA']);
    	$rango_merma=number_format((trim($Item['RANGO_MERMA']))*100, 2, ".", " ").'%';
    	$score_merma=number_format((trim($Item['SCORE_REAL_MERMA']))*100, 2, ".", " ").'%';


    	$real_clima=number_format((trim($Item['PROM_CLIMA_ANUAL']))*100, 2, ".", " ").'%';
    	$ppto_clima='';  
    	$vs_clima='';
    	$ratio_clima=trim($Item['RATIO_CLIMA']);
    	$rango_clima=number_format((trim($Item['RANGO_CLIMA']))*100, 2, ".", " ").'%';
    	$score_clima=number_format((trim($Item['SCORE_REAL_CLIMA']))*100, 2, ".", " ").'%';



    	$real_indi_ser=number_format((trim($Item['PROM_MSHOP_ANUAL']))*100, 2, ".", " ").'%';
    	$ratio_indi_serv=trim($Item['RATIO_INDICE_SERVICIO']);
    	$rango_indi_serv=number_format((trim($Item['RANGO_INDICE_SERVICIO']))*100, 2, ".", " ").'%';
    	$score_indi_serv=number_format((trim($Item['SCORE_REAL_INDICE_SERVICIO']))*100, 2, ".", " ").'%';


    	$real_ao_ventas=number_format((trim($Item['PROM_AUD_VENT_ANUAL']))*100, 2, ".", " ").'%';
    	$ratio_ao_ventas=trim($Item['RATIO_AO_VENTAS']);
    	$rango_ao_ventas=number_format((trim($Item['RANGO_AO_VENTAS']))*100, 2, ".", " ").'%';
    	$score_ao_ventas=number_format((trim($Item['SCORE_REAL_AO_VENTAS']))*100, 2, ".", " ").'%';


    	$real_ao_almacen=number_format((trim($Item['PROM_AUD_ALM_ANUAL']))*100, 2, ".", " ").'%';
    	$ratio_ao_almacen=trim($Item['RATIO_AO_ALMACEN']);
    	$rango_ao_almacen=number_format((trim($Item['RANGO_AO_ALMACEN']))*100, 2, ".", " ").'%';
    	$score_ao_almacen=number_format((trim($Item['SCORE_REAL_AO_ALMACEN']))*100, 2, ".", " ").'%';


    	$real_rotacion=number_format((trim($Item['ROT7_ANUAL']))*100, 2, ".", " ").'%';
    	$ratio_rotacion=trim($Item['RATIO_ROTACION']);
    	$rango_rotacion=number_format((trim($Item['RANGO_ROTACION']))*100, 2, ".", " ").'%';
    	$score_rotacion=number_format((trim($Item['SCORE_REAL_ROTACION']))*100, 2, ".", " ").'%';

    	$suma_score=number_format($score_venta+$score_util_ope+$score_inven+$score_merma+$score_clima+$score_indi_serv+$score_ao_ventas+$score_ao_almacen+$score_rotacion, 2, ".", " ").'%';

    	if ($suma_score>0 && $suma_score<95) {
    		$ratio_suma_score='1';
            $rango_suma_score='MDO';

    	}elseif ($suma_score>=95 && $suma_score<100) {
    		$ratio_suma_score='2';
            $rango_suma_score='DO';
    	}elseif ($suma_score>=100 && $suma_score<110) {
    		$ratio_suma_score='3';
            $rango_suma_score='EO';
    	}elseif ($suma_score>=110 && $suma_score<120) {
    		$ratio_suma_score='4';
            $rango_suma_score='SO';
    	}elseif ($suma_score>=120) {
    		$ratio_suma_score='5';
            $rango_suma_score='MSO';
    	}
    	

    	$sub_dimension=trim($Item['SUB_DIMENSION']);
    	$descripcion=(trim($Item['DESCRIPCION']));

    	$peso=(trim($Item['PESO']));

    	$mdo=trim($Item['MDO']);

    	$do=trim($Item['DO']);
    	$eo=trim($Item['EO']);

    	$so=trim($Item['SO']);
    	$mso=trim($Item['MSO']);

    	$porcen_cuota=number_format((trim($Item['PORCEN_CUOTA']))*100, 0, ".", " ").'%';

    	//$real='S/. '.number_format(trim($Item['VENT_ANUAL']), 2, ".", ",");  
    	//valor, cant_decimales, separador_decimal, separador_miles

    }
    ?>
    
<table style="width: 100%;  text-align: center; font-size: 9.43pt; vertical-align: middle; padding-top: 7px" cellspacing="0">
        
       	<tr>
            <th rowspan="2" style="width: 80px ;color: white; height: 2%; padding-left: 5px; background: #808080; text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">Anual</th>
            <th style=" width: 205px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; "><?php echo $peri_des ?></th>
        	<th style=" width: 60px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; background: #D9D9D9"  >BASE</th>
           	<th style=" width: 65px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; background: #F2F2F2"  >1 CUATR.</th>
           	<th style=" width: 65px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; background: #F2F2F2"  >2 CUATR.</th>
           	<th style=" width: 65px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; background: #F2F2F2"  >3 CUATR.</th>
           	<th style=" width: 65px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; background: #F2F2F2"  >AÑO</th>
        </tr>
        <tr>
            <td style=" height: 2%; padding-left: 5px;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; "><?php echo $anno ?></td>
           <td rowspan="2" style=" width: 60px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; background: #F2F2F2">% de Salario Basico</td>
           <td rowspan="2" style=" width: 65px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; ">0.6667</td>
           <td rowspan="2" style=" width: 65px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; ">0.6667</td>
           <td rowspan="2" style=" width: 65px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; ">0.6667</td>
           <td rowspan="2" style=" width: 65px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">2.5000</td>

        </tr>
      	<tr>
            <th style="width: 80px ;color: white; height: 2%; padding-left: 5px; background: #808080; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">Tienda</th>
            <th style=" width: 205px ;height: 2%; padding-left: 5px;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; "><?php echo $des_area ?></th>
        	
        </tr>

    </table><br>
		<table cellspacing="0" style="width: 100%; background: black;  ">
			<tr>
	            <td style="width: 100%; color: white;font-size:15px;text-align:left;padding-left: 5px">
	                <span style="color: white;font-size:14px;font-weight:bold">Escala</span>
	            </td>
			</tr>
	    </table>				         
		<br>
		<table cellspacing="0" style="width: 100%; text-align: center; font-size: 9.45pt; vertical-align: middle;" >
      		<tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan="5">RANGO</th>
	        </tr>

      		<tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >MDO</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >DO</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >EO</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >SO</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >MSO</th>
	        </tr>

	        <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >PESO</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >SUB-DIMENSION</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >DESCRIPCION</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >1.00</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >2.00</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >3.00</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >4.00</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >5.00</th>
	        </tr>
	        <?php
					$arregloEmp = $DAvTM_BSC_CUAT->ListarBSC_CuatriAnual($vTM_BSC_CUAT,$anno);

				    foreach($arregloEmp as $Item){
						$sub_dimension=trim($Item['SUB_DIMENSION']);
				    	$descripcion=(trim($Item['DESCRIPCION']));

				    	$peso=number_format((trim($Item['PESO']))*100, 1, ".", " ").'%';

				    	$mdo=number_format((trim($Item['MDO']))*100,0 , ".", " ").'%';

				    	$do=number_format((trim($Item['DO']))*100, 0, ".", " ").'%';
				    	$eo=number_format((trim($Item['EO']))*100, 0, ".", " ").'%';

				    	$so=number_format((trim($Item['SO']))*100, 0, ".", " ").'%';
				    	$mso=number_format((trim($Item['MSO']))*100, 0, ".", " ").'%';

				    	//$real='S/. '.number_format(trim($Item['VENT_ANUAL']), 2, ".", ",");  
				    	//valor, cant_decimales, separador_decimal, separador_miles

				    
				    ?>
	        <tr>
	        	
	           	<th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $peso ?></th>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $sub_dimension ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $descripcion ?></td>
	           <th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $mdo ?></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $do ?></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $eo ?></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $so ?></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $mso ?></th>
	        </tr>
	        <?php } ?>
	        <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >0%</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >95%</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >100%</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >110%</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >120%</th>
	        </tr>
	       
	      
       </table>
   		<br>
        <table cellspacing="0" style="width: 100%; background: black">
			<tr>
	            <td style="width: 100%; color: white;font-size:15px;text-align:left;padding-left: 5px">
	                <span style="color: white;font-size:14px;font-weight:bold">Calculo</span>
	            </td>
			</tr>
	    </table>				         
		<br>
		<table cellspacing="0" style="width: 100%; text-align: center; font-size: 12pt; vertical-align: middle;" >
      		
      		<tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	           <th style=" color: black; height: 2%; padding-left: 5px; " ></th>
	          

	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Real</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Ppto</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >vs</th>
	           <th style=" color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >Cumplimiento</th>
	        </tr>

	        <tr>
	           	<th style="width: 80px ;color: white; height: 2%; padding-left: 5px; background: #808080;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Condicion</th>
	           <th style=" font-size: 9.45pt;color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >% Cuotas Ventas</th>
	           <th style=" font-size: 9.45pt;color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $porcen_cuota ?></th>

               <?php if (trim($Item['VENT_ANUAL'])<0){ ?>
               <th style=" font-size: 9.45pt;color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $real ?></th>
               <?php }else{ ?>

                <th style=" font-size: 9.45pt;color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $real ?></th>

               <?php } ?>

                <th style=" font-size: 9.45pt;color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ppto ?></th>

             
	           <?php if (trim($Item['VAR_ALC_VEN_ANUAL'])<0){ ?>
               <th style=" font-size: 9.45pt;color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $vs ?></th>
               <?php }else{ ?>

                <th style=" font-size: 9.45pt;color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $vs ?></th>

               <?php } ?>

               <?php 
               $negativo=substr($vs, 0,1);
               if ($negativo=='-' OR $negativo=='' OR $negativo==NULL) {
                   $condicion='NO ENTRA AL CALCULO';
               }else{
                    $condicion='CUMPLE CONDICION';
               } ?>
	           <th style="width: 205px ;font-size: 12pt; color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $condicion ?></th>
	        </tr>
	        
       </table>	
     <br>
       <table cellspacing="0" style="width: 100%; text-align: center; font-size: 9.3pt; vertical-align: middle;" >
		<tr>
	           	<th style=" color: white; height: 2%; padding-left: 5px; background: #808080;text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >PESO</th>
	           <th style=" color: white; height: 2%; padding-left: 5px; background: #808080;text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >SUB-DIMENSION</th>
	           <th style=" color: white; height: 2%; padding-left: 5px; background: #808080;text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >OBJETIVOS</th>
	           <th style=" color: black; height: 2%; background: #D9D9D9; padding-left: 5px; text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Real</th>
	           <th style=" color: black; height: 2%; background: #D9D9D9; padding-left: 5px; text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Ppto</th>
	           <th style=" color: black; height: 2%; background: #D9D9D9; padding-left: 5px; text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >vs</th>
	           <th style=" color: black; height: 2%; background: #D9D9D9; padding-left: 5px; text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Ratio</th>
	           <th style=" color: black; height: 2%; background: #D9D9D9; padding-left: 5px; text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Rango</th>
	           <th style=" color: black; height: 2%; background: #D9D9D9; padding-left: 5px; text-align: center;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >Score</th>
	        </tr>
	        <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >30.0%</th>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >VENTA TOTAL TIENDA</td>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: right;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >100%</td>

	           <?php if (trim($Item['VENT_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $real ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $real ?></td>

               <?php } ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ppto ?></td>


               <?php if (trim($Item['VAR_ALC_VEN_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $vs ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $vs ?></td>
           <?php } ?>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ratio_venta?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $rango_venta ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $score_venta  ?></td>
	        </tr>

	        <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >10.0%</th>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >UTILIDAD OPERATIVA</td>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $obj_util_pe ?></td>

                <?php if (trim($Item['UTOP_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $real_util_ope ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $real_util_ope ?></td>

               <?php } ?>


	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ppto_util_ope ?></td>
	        

                <?php if (trim($Item['VAR_ALC_CLUSTER_ALC_UTOP_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $vs_util_ope ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $vs_util_ope ?></td>

               <?php } ?>

	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ratio_util_ope ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $rango_util_ope ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" > <?php echo $score_util_ope ?></td>
	        </tr>
	        <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >15.0%</th>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >INVENTARIO</td>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $obj_inven ?></td>
               <?php if (trim($Item['INVE_ANUAL'])<0){ ?>
                <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $real_inven ?></td>
               <?php }else{ ?>

                <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;" ><?php echo $real_inven ?></td>

               <?php } ?>
	           
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ppto_inven ?></td>
	            <?php if (trim($Item['VAR_ALC_INVE_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $vs_inven ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $vs_inven ?></td>

               <?php } ?>

	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ratio_inven ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $rango_inven ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $score_inven ?></td>
	        </tr>

	        <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >10.0%</th>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >MERMA</td>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $obj_merma ?></td>
	       
              <?php if (trim($Item['MERM_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $real_merma ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $real_merma ?></td>

               <?php } ?>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ppto_merma ?></td>
	            <?php if (trim($Item['VAR_ALC_MERM_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $vs_merma ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $vs_merma ?></td>

               <?php } ?>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ratio_merma ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $rango_merma ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $score_merma ?></td>
	        </tr>

	        

	        <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >7.0%</th>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >INDICE DE SERVICIO</td>
               <?php
                    $arregloEmp = $DAvTM_BSC_CUAT->ListarBSC_AnualEOIndice($vTM_BSC_CUAT,$anno);

                    foreach($arregloEmp as $Item){
                        $EO_indice=round($Item['EO'],0);
                    }
                ?>
      
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $EO_indice ?>%</td>
	      
               <?php if (trim($Item['PROM_MSHOP_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $real_indi_ser ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $real_indi_ser ?></td>

               <?php } ?>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ratio_indi_serv ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $rango_indi_serv ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $score_indi_serv ?></td>
	        </tr>
      <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >7.0%</th>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >AO. VENTAS</td>
               <?php
               $arregloEmp = $DAvTM_BSC_CUAT->ListarBSC_AnualEOVentas($vTM_BSC_CUAT,$anno);

                    foreach($arregloEmp as $Item){
                        $EO_ventas=round($Item['EO'],0);
                    }

                
                    
                ?>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $EO_ventas ?>%</td>
	         

                <?php if (trim($Item['PROM_AUD_VENT_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $real_ao_ventas ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $real_ao_ventas ?></td>

               <?php } ?>

	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ratio_ao_ventas ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $rango_ao_ventas ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $score_ao_ventas ?></td>
	        </tr>

	        <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >7.0%</th>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >AO. ALMACEN</td>
               <?php  
               $arregloEmp = $DAvTM_BSC_CUAT->ListarBSC_AnualEOAlmacen($vTM_BSC_CUAT,$anno);

                    foreach($arregloEmp as $Item){
                        $EO_almacen=round($Item['EO'],0);
                    }
               ?>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $EO_almacen ?>%</td>
	          

               <?php if (trim($Item['PROM_AUD_ALM_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $real_ao_almacen ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $real_ao_almacen ?></td>

               <?php } ?>

	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ratio_ao_almacen ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $rango_ao_almacen ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $score_ao_almacen ?></td>
	        </tr>

	        <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >7.0%</th>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >ROTACION</td>
               <?php  

               $arregloEmp = $DAvTM_BSC_CUAT->ListarBSC_AnualEORotacionMayor($vTM_BSC_CUAT,$anno);

                    foreach($arregloEmp as $Item){
                        $EO_Rotacion_M=round($Item['EO'],0);
                    }

                    $arregloEmp = $DAvTM_BSC_CUAT->ListarBSC_AnualEORotacionMenor($vTM_BSC_CUAT,$anno);

                    foreach($arregloEmp as $Item){
                        $EO_Rotacion_R=round($Item['EO'],0);
                    }


                if ($co_depa=='11') {
                    $EO_ROTA=$EO_Rotacion_M;
                }elseif ($co_depa=='12') {
                    $EO_ROTA=$EO_Rotacion_R;
                }
               ?>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $EO_ROTA ?>%</td>
	         
              <?php if (trim($Item['ROT7_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $real_rotacion ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $real_rotacion ?></td>

               <?php } ?>

	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ratio_rotacion ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $rango_rotacion ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $score_rotacion ?></td>
	        </tr>

	        <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >7.0%</th>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >CLIMA LABORAL</td>
               <?php  
               $arregloEmp = $DAvTM_BSC_CUAT->ListarBSC_AnualEOClima($vTM_BSC_CUAT,$anno);

                    foreach($arregloEmp as $Item){
                        $EO_clima=round($Item['EO'],0);
                    }
               ?>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $EO_clima ?>%</td>
	           <?php if (trim($Item['PROM_CLIMA_ANUAL'])<0){ ?>
              <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;color:red" ><?php echo $real_clima ?></td>
               <?php }else{ ?>

               <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $real_clima ?></td>

               <?php } ?>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ratio_clima ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $rango_clima ?></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $score_clima ?></td>
	        </tr>

	       
	        <tr>
	           	<th style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ></th>
	           <td style=" color: black; height: 2%; padding-left: 5px; background: #F2F2F2;text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" colspan="2">RANKING TOTAL</td>
	         
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #BFBFBF;" ></td>
	           <td style=" color: black; height: 2%; background: #BFBFBF; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ratio_suma_score ?></td>
	           <td style=" color: black; height: 2%; background: #BFBFBF; padding-left: 5px; text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; font-size: 15pt; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $rango_suma_score ?></td>
	           <td style=" color: black; height: 2%; font-size: 15pt;background: #BFBFBF; padding-left: 5px; text-align: right;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $suma_score ?></td>
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

