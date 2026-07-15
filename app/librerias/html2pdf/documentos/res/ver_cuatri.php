
<?php session_start();

if(!isset($_SESSION["serviciolinea"])) {header("Location: index.php");}

define('RAIZ',$_SERVER['DOCUMENT_ROOT']);

include_once(RAIZ."/servicio-online/bin/Bin.php");
include_once(RAIZ."/servicio-online/bin/Libreria/Conexion.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionOfiplan.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionDashboard.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionRetributivo.php");


$BASE=Bin::Factory("Lib","Base");

$VDIRECTORIO_RENOVACION = Bin::Factory("Neg","VDIRECTORIO_RENOVACION");
$DAVDIRECTORIO_RENOVACION=Bin::Factory("Mod","DAVDIRECTORIO_RENOVACION");

$arraylider = $DAVDIRECTORIO_RENOVACION->CO_TRAB($_SESSION["serviciolinea"]["DNI"]);
    foreach($arraylider as $Item){  
    
   
        $co_trab_original=$Item['CO_TRAB'];
    
 
    $codiho_empresa=trim($Item['CO_EMPR']);
    $CO_PUES_TRAB=trim($Item['CO_PUES_TRAB']);
}

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
     <?php
    $arregloEmp = $DAvTM_EVAL_CUAT->ListarCate_Cuatri($dni,$anno,$peri);
    foreach($arregloEmp as $Item){

        $co_trab=trim($Item['CO_TRAB']);
        $nom=utf8_encode(trim($Item['NOMBRES_Y_APELLIDOS']));
        $peri_eva=trim($Item['DE_PERI_CUAT']);
    
        $co_emp=trim($Item['CO_EMPR']);

        
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

        $inventario_peso=(trim($Item['NU_EVA_INV_PESO']))*100;
        $administrador_peso=(trim($Item['NU_EVA_ADM_PESO']))*100;
        $rotativos_peso=(trim($Item['NU_EVA_ROT_PESO']))*100;
        $productividad_peso=(trim($Item['NU_EVA_PRO_PESO']))*100;
        $asistencia_peso=(trim($Item['NU_EVA_ASI_PESO']))*100;

        




        
    }?>
    
   
      <?php if ($co_trab!='' or $co_trab!=NULL) { ?>
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
                <span style="color: white;font-size:20px;font-weight:bold">CATEGORIZACIÓN CUATRIMESTRAL ALMACENERO Y CHEQUEADOR <?php echo $peri ?>- <?php echo $anno ?></span>
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
       <br>
        <table cellspacing="0" style="width: 100%; text-align: left; font-size: 12pt; vertical-align: middle;" >
            <tr>
                <th style="width:40%; height: 2%; padding-left: 5px">Periodo de Evaluaci&oacute;n:</th>
                <th style="width:60%;height: 2%; padding-left: 5px; padding-top: -10px"><?php echo $peri_eva ?></th>

            </tr>
            <tr>
                <th style="width:40%; padding-left: 5px">DNI:</th>
                <td style="width:60%;padding-left: 5px; background-color: #F0EDEC;"><?php echo $co_trab ?></td>

            </tr>
            <tr>
                <th style="width:40%; padding-left: 5px">Colaborador:</th>
                <td style="width:60%;padding-left: 5px; background-color: #F0EDEC;"><?php echo $nom ?></td>

            </tr>
            <tr>
                <th style="width:40%; padding-left: 5px">Puesto:</th>
                <td style="width:60%;padding-left: 5px; background-color: #F0EDEC;"><?php echo $puesto ?></td>

            </tr>
            <tr>
                <th style="width:40%; padding-left: 5px">Tienda:</th>
                <td style="width:60%;padding-left: 5px; background-color: #F0EDEC;"><?php echo $area ?></td>

            </tr>
       </table>
       <p style="font-size: 12pt;">Para la categorizaci&oacute;n cuatrimestral se tomaron en cuenta los siguientes criterios de evaluaci&oacute;n, las cuales pasamos a detallar:</p>
      
       <table cellspacing="0" style="width: 100%; text-align: left; font-size: 12pt;">
            <tr>
                <th style="width:35%; height: 2%;"></th>
             
                <th style="width:25%; height: 2%;text-align: center;">CALIFICACI&Oacute;N</th>
                <th style="width:30%; height: 2%;text-align: center;">PESO</th>
            </tr>
            <tr>
                <td style="width:35%; ">1. Auditoria Control Inventarios:</td>
                
                <td style="width:25%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $inventario.'%' ?></td>
                <td style="width:30%; padding-left: 5px;text-align: right;"><?php echo $inventario_peso ?>%</td>
            </tr>
            <tr>
                <td style="width:35%; ">2. Evaluaci&oacute;n del Administrador:</td>
                
                <td style="width:25%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $administrador.'%' ?></td>
                <td style="width:30%; padding-left: 5px;text-align: right;"><?php echo $administrador_peso ?>%</td>
            </tr>
            <tr>
                <td style="width:35%; ">3. Uso de Rotativos:</td>
                
                <td style="width:25%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $rotativos.'%' ?></td>
                <td style="width:30%; padding-left: 5px;text-align: right;"><?php echo $rotativos_peso ?>%</td>
            </tr>
            <tr>
                <td style="width:35%; ">4. Productividad:</td>
                
                <td style="width:25%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $productividad.'%' ?></td>
                <td style="width:30%; padding-left: 5px;text-align: right;"><?php echo $productividad_peso ?>%</td>
            </tr>
            <tr>
                <td style="width:35%; ">5. Asistencias:</td>
                
                <td style="width:25%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $asistencia.'%' ?></td>
                <td style="width:30%; padding-left: 5px;text-align: right;"><?php echo $asistencia_peso ?>%</td>
            </tr>
            <tr>
                <td style="width:35%; ">6. Ocurrencias:</td>
                
                <td style="width:25%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $ocurrencias.'%' ?></td>
                <td style="width:30%; padding-left: 5px;text-align: right;">Todo o nada</td>
            </tr>
            <tr>
                <th style="width:35%; ">TOTAL EVALUACI&oacute;N:</th>
                
                <th style="width:25%; padding-left: 5px;text-align: right;background-color: #F0EDEC;"><?php echo $tot_evaluacion.'%' ?></th>
                <td style="width:30%; padding-left: 5px;text-align: right;"></td>
            </tr>
        </table>
        <br>
        <table cellspacing="0" style="width: 100%; text-align: left; font-size: 12pt;">
            <tr>
                <th style="width:60%; height: 2%;text-align: left;">Categor&iacute;a Tienda:</th>
                <th style="width:30%; height: 2%;text-align: center;background-color: #F0EDEC;"><?php echo $cate_tienda ?></th>
            </tr>

            <tr>
                <th style="width:60%; height: 2%;text-align: left;">Categorizaci&oacute;n cuatrimestral:</th>
                <th style="width:30%; height: 2%;text-align: center;"></th>
            </tr>

            <tr>
                <th style="width:5%; height: 2%;text-align: right;padding-right: 40px">Rango:</th>
                <th style="width:19%; height: 2%;text-align: center;background-color: #F0EDEC;"><?php echo $rango ?></th>
            </tr>
            <tr>
                <th style="width:5%; height: 2%;text-align: right;padding-right: 10px">Calificaci&oacute;n:</th>
                <th style="width:19%; height: 2%;text-align: center;background-color: #F0EDEC;"><?php echo $co_cali ?></th>
            </tr>
            <tr>
                <th style="width:60%; height: 2%;text-align: left;">Bono mensual a recibir solo durante el siguiente cuatrimestre:</th>
                <th style="width:30%; height: 2%;text-align: center;background-color: #F0EDEC; font-size: 12pt;"><?php echo $peri_eva_sig ?></th>
            </tr>


        </table>
        <br>
        <br>
        <table cellspacing="0" style="width: 100%; text-align: right; font-size: 12pt;">
            <tr>
                <th style="width:60%; height: 2%;text-align: left;">Sueldo actual:</th>
                <th style="width:30%; height: 2%;text-align: right;background-color: #F0EDEC;"><?php echo 'S/. '.$sueldo ?></th>
            </tr>
            <tr>
                <th style="width:60%; height: 2%;text-align: left;">Importe Bono Cuatrimestral a recibir:</th>
                <th style="width:30%; height: 2%;text-align: right;background-color: #F0EDEC;"><?php echo 'S/. '.$imp_bono ?></th>
            </tr>
            <tr>
                <th style="width:60%; height: 2%;text-align: left;"></th>
                <th style="width:30%; height: 2%;text-align: right;background-color: #F0EDEC;"></th>
            </tr>
            <tr>
                <th style="width:60%; height: 2%;text-align: left;">Sueldo Actual + Bono cuatrimestral a recibir:</th>
                <th style="width:30%; height: 2%;text-align: right;background-color: #F0EDEC;"><?php echo 'S/. '.$sueldo_mas_bono ?></th>
            </tr>
        </table>
        <br><br>
         <p style="font-size: 12pt;"><b>Queda constancia de haber recibido este documento.</b></p>
         <br><br><br><br>

        <table cellspacing="0" style="width: 102%; text-align: center; font-size: 12pt;">
            
            <tr>
                <th style="width:100%; ">____________________________________________</th>
                
            </tr>
            <tr>
                <th style="width:100%;text-align: center; ">NOMBRE: <?php echo $nom ?></th>
                
            </tr>
            <tr>
                <th style="width:100%;text-align: center;">DNI: <?php echo $co_trab ?></th>
                
            </tr>
            
        
            
               
            
           
        </table>
    <?php }else{
        ?>

        <style type="text/css">
        <?php //TAILOY
            if($codiho_empresa=='01') { ?>
                #color1{background-color: rgb(7,167,103); 
            }
    <?php }
            //LUCIANO
            elseif ($codiho_empresa=='02') {?>
                #color1{background-color: rgb(93, 178,225);
            }
    <?php }
            //COPYVENTAS
            elseif($codiho_empresa=='03'){?>
                #color1{background-color: rgb(245, 153, 153);
            }
    <?php }
            //SUPLACORP
            elseif($codiho_empresa=='04'){?>
                #color1{background-color: rgb(44, 118, 179);
            }
    <?php }
            //CASCAJAL
            elseif($codiho_empresa=='05'){?>
                #color1{background-color: rgb(144, 148, 152);
            }
    <?php }?>
    </style>
    <table cellspacing="0" style="width: 100%; " id="color1">
        <tr>

            <td style="width: 75%; color: white;font-size:13px;text-align:center">
                <span style="color: white;font-size:20px;font-weight:bold">CATEGORIZACIÓN CUATRIMESTRAL ALMACENERO Y CHEQUEADOR <?php echo $peri ?>- <?php echo $anno ?></span>
            </td>
            
            <td style="width: 25%;text-align:right">
                <?php if ($codiho_empresa=='001') { ?>
                <img src='../../images/icon/logo-tailoy.png' style='    max-width:50px;
                    height: 50px;
                    float: left;'  />
                 <?php }elseif($codiho_empresa=='02'){ ?>

                    <img src="../../images/icon/logo-luciano.png" style='    max-width: 100px;
                    height: auto;
                    float: left;'  />
                 <?php }elseif($codiho_empresa=='03') {?>

                    <img src="../../images/icon/logo-copy.png" style='    max-width: 100px;
                    height: auto;
                    float: left;'  />
                 <?php }elseif($codiho_empresa=='04') {?>
                 
                    <img src="../../images/icon/logo-supla.png" style='    max-width: 100px;
                    height: auto;
                    float: left;'  />
                 <?php }elseif($codiho_empresa=='05'){ ?>

                    <img src="../../images/icon/logo-casca.png" style='    max-width: 100px;
                    height: auto;
                    float: left;'  />
                 <?php }?>
                
            </td>
            
        </tr>
    </table><?php
        echo '<p>El colaborador no tiene datos para mostrar en la evaluaci&oacute;n '.$anno.' - '.$peri.'</p>';
    } ?>
		

</page>

