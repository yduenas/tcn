
<?php session_start();

if(!isset($_SESSION["serviciolinea"])) {header("Location: index.php");}

define('RAIZ',$_SERVER['DOCUMENT_ROOT']);

include_once(RAIZ."/servicio-online/bin/Bin.php");
include_once(RAIZ."/servicio-online/bin/Libreria/Conexion.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionOfiplan.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionRRHH2.php");

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
<style type="text/css">
   
    <?php //TAILOY
            if($cem=='01') { ?>
                #color1{background-color: rgb(7,167,103); 
            }
    <?php }
            //LUCIANO
            elseif ($cem=='02') {?>
                #color1{background-color: rgb(93, 178,225);
            }
    <?php }
            //COPYVENTAS
            elseif($cem=='03'){?>
                #color1{background-color: rgb(245, 153, 153);
            }
    <?php }
            //SUPLACORP
            elseif($cem=='04'){?>
                #color1{background-color: rgb(44, 118, 179);
            }
    <?php }
            //CASCAJAL
            elseif($cem=='05'){?>
                #color1{background-color: rgb(144, 148, 152);
            }
    <?php }?>


   
    #sinborde{
        border-top: 1px solid #FFFFFF; 
        border-bottom: 1px solid #FFFFFF; 
        border-left: 1px solid #FFFFFF; 
        border-right: 1px solid #FFFFFF; 
    }

    #conbottom{
        border-bottom: 1px solid #000000; 
        border-left: 1px solid #FFFFFF; 
        border-top: 1px solid #FFFFFF;
        border-right: 1px solid #FFFFFF;
       
    }

  .showbox {
    float: left;
    margin: 4em 1em;
    width: 100px;
    height: 60px;
    border: 2px solid green;
    background-color: #fff;
    line-height: 60px;
    text-align: center;
  }
  
    
  .btnexm {
    background: url(http://www1.tailoy.com.pe/inc/css/img/trabajanosotros/examinar.jpg);
    width: 84px;
    height: 22px;
	/*
    float: left;
    margin-left: 232px;*/
    /*margin-top: -30px;*/
}
  
  
  .TaiLoy-Liqui {
    color: #000;
    /* float: left; */
    /* width: 71%; */
    height: auto;
    text-align: center;
    margin-top: 8%;
    margin-bottom: 8%;
}
 
 
#TaiLoy-Buscar-Liqui {
    text-transform: initial;
    padding: 6px 12px;
    background: #fdcc0d;
    color: #000;
    float: right;
    text-align: center;
    width: 40%;
    margin: 0 auto;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    position: relative;
    /* padding: 8px 30px; */
    font-size: 16px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0;
    will-change: box-shadow,transform;
}




      

        a.comment-indicator:hover + comment { 
            background:#ffd; 
            position:absolute; 
            display:block; 
            border:1px solid black; 
            padding:0.5em;  } 

        a.comment-indicator { 
            background:red; 
            display:inline-block; 
            border:1px solid black; 
            width:0.5em; height:0.5em;  } 

        comment { display:none;  } 
        
        .menu_doc li{
            float:left;
            margin-right:20px;  
        }
        .menu_doc li a{
            font-size:10px;
        }
        iframe{
            overflow:hidden !important;
            
        }
        
        
        

        
    




 
@media print
{
  table { page-break-after:auto }
  tr    { page-break-inside:avoid; page-break-after:auto }
  td    { page-break-inside:avoid; page-break-after:auto }

}
   
   




</style>
<?php 

        $TM_PUESTO = Bin::Factory("Neg","TM_PUESTO");
        $DATM_PUESTO=Bin::Factory("Mod","DATM_PUESTO");



        $TraerPuestoMof = $DATM_PUESTO->ListarPuestoMof($cem,$puesto);

            foreach($TraerPuestoMof as $ItemPM){    
                                 
                $codemp=trim($ItemPM['CO_EMPR']);
                $nomemp=trim($ItemPM['NOMBRE_EMP']);
                $dpuestrab=trim($ItemPM['DE_PUES_TRAB']);
                $nomdepa=trim($ItemPM['NOMBRE_DEPA']);
                $nomarea=trim($ItemPM['NOMBRE_AREA']);
                $nomlugar=trim($ItemPM['NO_LUGAR']);
                $desmisi=trim($ItemPM['DE_MISI']);
                $nomfuncion=(trim($ItemPM['NO_FUNCION']));
                $nomfrecu=trim($ItemPM['NOMBRE_FRECUENCIA']);
                $nomconse=trim($ItemPM['NOMBRE_CONSECUENCIA']);
                $nomcomple=trim($ItemPM['NOMBRE_COMPLEJIDAD']);
                $frecveces=trim($ItemPM['FRECUENCIA_VECES']);
                $frechoras=trim($ItemPM['FRECUENCIA_HORAS']);
                $version=trim($ItemPM['VERSION']);
                $viaje=trim($ItemPM['VIAJE']);
                $obsviaje=trim($ItemPM['OBSERVACVIOB_VIAJE']);
                $movi=trim($ItemPM['MOVILIZACION']);
                $obsmovi=trim($ItemPM['OBSERVACION_MOVILIZACION']);
                $nomela=trim($ItemPM['NOMBRE_ELABORADOR']);
                $puesela=trim($ItemPM['PUESTO_ELABORO']);
                $fechaela=trim($ItemPM['FECHA_ELABORACION']);
                $obsela=trim($ItemPM['OBSERVACION_ELABORO']);
                $nomapro=trim($ItemPM['NOMBRE_APROBADOR']);
                $puesapro=trim($ItemPM['PUESTO_APROBACION']);
                $fechaapro=trim($ItemPM['FECHA_APROBACION']);
                $obsapro=trim($ItemPM['OBSERVACION_APROBACION']);
                $genero=trim($ItemPM['GENERO']);

            }      
        ?>
<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table>
			<tr>
				<td style="width: 50%; color: black;font-size:12px;text-align:left">
                <?php if ($cem=='001') { ?>
			 	<img src='../../images/icon/logo-tailoy.png' style='    max-width: 70px;
				    height: 70px;
				    float: left;'  />
				 <?php }elseif($cem=='02'){ ?>

				 	<img src="../../images/icon/logo-luciano.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />
				 <?php }elseif($cem=='03') {?>

				 	<img src="../../images/icon/logo-copy.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />
				 <?php }elseif($cem=='04') {?>
				 
				 	<img src="../../images/icon/logo-supla.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />
				 <?php }elseif($cem=='05'){ ?>

				 	<img src="../../images/icon/logo-casca.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />
				 <?php }?>
            	</td>
            <?php  

            if ($cem=='01') {
            	$espacio='padding-left: 160px;';
            }else{
            	$espacio='padding-left: 250px;';
            }
            ?>
			<td >
				<table cellspacing="0" style="width: 100%; padding-left: 165px; vertical-align: middle">
					<tr>
						<th align="left" id="color1" style=" padding-left: 5px;text-align: left;width: 250px; height: 25px;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; padding-right: 10px" id="color1">FECHA DE VISUALIZACIÓN:</th>
				        <td align="left"  bgcolor="#FFFFFF" style="text-align: center;width: 70px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000"><?php echo date('d/m/y') ?>
				        </td>
					</tr>
					<tr>
						<th align="left"  id="color1" style=" padding-left: 5px;text-align: left;width: 250px; height: 25px;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; padding-right: 10px " id="color1">FECHA DE VERSIÓN:</th>
				        <td align="left"  bgcolor="#FFFFFF" style="text-align: center;width: 70px;border-top: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000"><?php echo $version ?>
				        </td>
					</tr>
				</table>
			</td>
			<td>
			</td>
			</tr>
		</table>
	<br> 
	<table cellspacing="0" style="width: 100%; ">
		<tr>
            <td style="width: 100%; color: white;font-size:15px;text-align:center;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000">
                <span style="color: black;font-size:14px;font-weight:bold">DESCRIPCIÓN DE FUNCIONES Y PERFIL DE PUESTO</span>
            </td>
		</tr>
    </table>

    <br>
    <table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 0px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">1. IDENTIFICACIÓN</span>
            </td>
		</tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 9.45pt; vertical-align: middle;" >
		
		<tr>
           	<td style="width: 30%;color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; padding-left: 5px" >COMPAÑIA:</td>
           <td style=" width: 70%;color: black; height: 2%; padding-left: 5px; text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; padding-left: 5px" ><?php echo $nomemp; ?></td>
        </tr>
         <tr>
	        	
           	<td style=" color: black; height: 2%; padding-left: 5px; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; padding-left: 5px;background: #D9D9D9;" >GERENCIA:</td>
           
           <td style=" color: black; height: 2%; padding-left: 5px; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; padding-left: 5px"><?php echo $nomdepa; ?></td>
        </tr>
	</table>

    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 9.45pt; vertical-align: middle;" >
		
		<tr>
           	<td style="width: 30%;color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; padding-left: 5px" >ÁREA:</td>
           <td style=" width: 30%;color: black; height: 2%; padding-left: 5px; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; padding-left: 5px" ><?php echo $nomarea; ?></td>
           <td style="width: 25%;color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; padding-left: 5px" >LUGAR DE TRABAJO:</td>
           <td style=" width: 15%;color: black; height: 2%; padding-left: 5px; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; padding-left: 5px" ><?php echo $nomlugar; ?></td>
        </tr>
        <tr>
           	<td style="width: 30%;color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; padding-left: 5px" >NOMBRE DEL PUESTO:</td>
           <td style=" width: 30%;color: black; height: 2%; padding-left: 5px; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; padding-left: 5px" ><?php echo $dpuestrab; ?></td>
           <td style="width: 25%;color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; padding-left: 5px" >GÉNERO DEL PUESTO:</td>

           <td style=" width: 15%;color: black; height: 2%; padding-left: 5px; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; padding-left: 5px" ><?php echo $genero; ?></td>
        </tr>
        
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center; font-size: 9.45pt; vertical-align: middle;" >
		
		<tr>
           	<td style="width: 30%;color: black; height: 2%; padding-left: 5px; background: #D9D9D9;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; padding-left: 5px" >PUESTO JEFE INMEDIATO:</td>
           	<?php 

	        $TraerPuestoMof = $DATM_PUESTO->ListarJefe($cem,$puesto);

	            foreach($TraerPuestoMof as $ItemPM){    
	                                 
	                $nomjefe=trim($ItemPM['DE_PUES_TRAB']);
	               
	            }
	        ?>
           <td style=" width: 70%;color: black; height: 2%; padding-left: 5px; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; padding-left: 5px" ><?php echo $nomjefe; ?></td>
        </tr>
   
	</table>

	<br>
    <table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 0px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">2. MISIÓN O PROPÓSITO DEL PUESTO: En breve definción responder la esencia del puesto: <br>Qué es lo que hace? / Sobre qué funciones y/o procesos? / De acuerdo con qué guía o referencia? / Para qué se hace?</span>
            </td>
		</tr>
		<tr>
            <td style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold"><?php echo $desmisi ?></span>
            </td>
		</tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">3. FUNCIONES:Cada puesto está compuesto de funciones principales, que en su conjunto llevan al logro de la misión del puesto como tal. En esta parte se trata de identificar estas funciones principales (se recomienda entre 6 y 8); así como el objetivo de cada una de ellas, en pos del cumplimiento de la misión.</span>
            </td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; font-size: 12px">
		 <tr>
	        	
	           	<td id="color1" style=" width: 39%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: justify;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Qué es lo qué hace? / Sobre qué funciones y/o procesos? / En base a qué pauta? / Para qué se hace?</td>
	           <td id="color1" style=" width: 13%;color: black; height: 2%;  text-align: justify;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >FRECUENCIA</td>
	           <td id="color1" style=" width: 15%;color: black; height: 2%;  text-align: justify;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >CONSECUENCIA</td>
	           <td id="color1" style=" width: 13%;color: black; height: 2%;  text-align: justify;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >COMPLEJIDAD</td>
	           <td id="color1" style=" width: 8%;color: black; height: 2%;  text-align: justify;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ># VECES</td>
	           
	           <td id="color1" style=" width: 12%;color: black; height: 2%;  text-align: justify;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ># HORAS INVERTIDAS</td>
	        </tr>
	        <?php 

        

        $TraerPuestoMof = $DATM_PUESTO->ListarPuestoMof($cem,$puesto);

            foreach($TraerPuestoMof as $ItemPM){    
                                 
                $nomfuncion=utf8_encode(trim($ItemPM['NO_FUNCION']));
                $nomfrecu=trim($ItemPM['NOMBRE_FRECUENCIA']);
                $nomconse=trim($ItemPM['NOMBRE_CONSECUENCIA']);
                $nomcomple=trim($ItemPM['NOMBRE_COMPLEJIDAD']);
                $frecveces=trim($ItemPM['FRECUENCIA_VECES']);
                $frechoras=trim($ItemPM['FRECUENCIA_HORAS']);
                
        ?>
	        <tr>
	        	
	           	<td style=" width: 39%;color: black; height: 2%; padding-left: 5px;padding-right: 5px; text-align: justify;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $nomfuncion ?></td>
	           <td style=" width: 13%;color: black; height: 2%;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $nomfrecu ?></td>
	           <td style=" width: 15%;color: black; height: 2%;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $nomconse ?></td>
	           <td style=" width: 13%;color: black; height: 2%;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $nomcomple ?></td>
	           <td style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $frecveces ?></td>
	           
	           <td style=" width: 12%;color: black; height: 2%;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $frechoras ?></td>
	        </tr>
	         <?php 
        }
    ?>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">4. COMPETENCIAS: Describir las destrezas interpersonales necesarias para la ejecución del puesto</span>
            </td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 30%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >COMPETENCIAS BÁSICAS</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >NIVEL</td>
          
           	<td id="color1" style=" width: 60%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >DESCRIPTOR</td>
	    </tr>
	    <?php 

        $TTTCONOCIMIENTO_NIVEL = Bin::Factory("Neg","TTTCONOCIMIENTO_NIVEL");
        $DATTTCONOCIMIENTO_NIVEL=Bin::Factory("Mod","DATTTCONOCIMIENTO_NIVEL");
        
        $TraerCompeMof = $DATTTCONOCIMIENTO_NIVEL->ListarCompetenciaBasicaMof($cem,$puesto);

            foreach($TraerCompeMof as $ItemCM){    
                                 
                $nomcompe=trim($ItemCM['NO_COMPETENCIA']);
                $cocompeni=trim($ItemCM['CO_COMPETENCIA_NIVEL']);
                $nomcompedes=trim($ItemCM['NO_COMPETENCIA_DESCRIPTOR']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 30%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($nomcompe) ?></td>
           	<td  style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $cocompeni ?></td>
          
           	<td  style=" width:60%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($nomcompedes) ?></td>
	    </tr>
	    <?php 
        }
    ?>

    <tr>
	        	
           	<td id="color1" style=" width: 30%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >COMPETENCIAS DE SOLUCIÓN DE PROBLEMAS</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >NIVEL</td>
          
           	<td id="color1" style=" width: 60%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >DESCRIPTOR</td>
	    </tr>
	    <?php 

        $TTTCONOCIMIENTO_NIVEL = Bin::Factory("Neg","TTTCONOCIMIENTO_NIVEL");
        $DATTTCONOCIMIENTO_NIVEL=Bin::Factory("Mod","DATTTCONOCIMIENTO_NIVEL");
        
        $TraerCompeMof = $DATTTCONOCIMIENTO_NIVEL-> ListarCompetenciaNecesarianMof($cem,$puesto);

            foreach($TraerCompeMof as $ItemCM){    
                                 
                $nomcompe=trim($ItemCM['NO_COMPETENCIA']);
                $cocompeni=trim($ItemCM['CO_COMPETENCIA_NIVEL']);
                $nomcompedes=trim($ItemCM['NO_COMPETENCIA_DESCRIPTOR']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 30%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($nomcompe) ?></td>
           	<td  style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $cocompeni ?></td>
          
           	<td  style=" width:60%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($nomcompedes) ?></td>
	    </tr>
	    <?php 
        }
    ?>

    	<tr>
	        	
           	<td id="color1" style=" width: 30%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >COMPETENCIAS NECESARIAS</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >NIVEL</td>
          
           	<td id="color1" style=" width: 60%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >DESCRIPTOR</td>
	    </tr>
	    <?php 

        $TTTCONOCIMIENTO_NIVEL = Bin::Factory("Neg","TTTCONOCIMIENTO_NIVEL");
        $DATTTCONOCIMIENTO_NIVEL=Bin::Factory("Mod","DATTTCONOCIMIENTO_NIVEL");
        
        $TraerCompeMof = $DATTTCONOCIMIENTO_NIVEL->ListarCompetenciaSolucionMof($cem,$puesto);

            foreach($TraerCompeMof as $ItemCM){    
                                 
                $nomcompe=trim($ItemCM['NO_COMPETENCIA']);
                $cocompeni=trim($ItemCM['CO_COMPETENCIA_NIVEL']);
                $nomcompedes=trim($ItemCM['NO_COMPETENCIA_DESCRIPTOR']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 30%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($nomcompe) ?></td>
           	<td  style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $cocompeni ?></td>
          
           	<td  style=" width:60%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($nomcompedes) ?></td>
	    </tr>
	    <?php 
        }
    ?>

	</table>

	<br>
	<table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">5. RESPONSABILIDAD DE SUPERVISIÓN:<br>Está comprendido por el nivel de complejidad al tener o no personas a cargo, siendo estos:</span>
            </td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 30%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Competencias AMBITO DE SUPERVISIÓN</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >NIVEL</td>
          
           	<td id="color1" style=" width: 60%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >DESCRIPTOR</td>
	    </tr>
	    <?php 

        $TTTCONOCIMIENTO_NIVEL = Bin::Factory("Neg","TTTCONOCIMIENTO_NIVEL");
        $DATTTCONOCIMIENTO_NIVEL=Bin::Factory("Mod","DATTTCONOCIMIENTO_NIVEL");
        
        $TraerCompeMof = $DATTTCONOCIMIENTO_NIVEL->ListarCompetenciaSupervisionMof($cem,$puesto);

            foreach($TraerCompeMof as $ItemCM){    
                                 
                $nomcompe=trim($ItemCM['NO_COMPETENCIA']);
                $cocompeni=trim($ItemCM['CO_COMPETENCIA_NIVEL']);
                $nomcompedes=trim($ItemCM['NO_COMPETENCIA_DESCRIPTOR']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 30%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($nomcompe) ?></td>
           	<td  style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $cocompeni ?></td>
          
           	<td  style=" width:60%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($nomcompedes) ?></td>
	    </tr>
	    <?php 
        }
    ?>
</table>
<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 60%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >PUESTO</td>
           	<td id="color1" style=" width: 20%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >TIPO DE SUPERVISIÓN</td>
          
           	<td id="color1" style=" width: 20%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ># DE OCUPANTES</td>
	    </tr>
	    <?php 

        $TM_EXIGENCIA = Bin::Factory("Neg","TM_EXIGENCIA");
        $DATM_EXIGENCIA=Bin::Factory("Mod","DATM_EXIGENCIA");
        
        $TraerSupervisionMof = $DATM_EXIGENCIA->ListarSupervision($_SESSION["serviciolinea"]["CEM"],$_SESSION["serviciolinea"]["CPT"]);

            foreach($TraerSupervisionMof as $ItemFM){  



                $nomcarrera=trim($ItemFM['DE_PUES_TRAB']);                 
                $nomforma=trim($ItemFM['NO_SUPERVISION_TIPO']);
                $desforma=trim($ItemFM['NUM_SUPERVSADOS']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 60%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($nomcarrera) ?></td>
           	<td  style=" width: 20%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $nomforma ?></td>
          
           	<td  style=" width:20%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($desforma) ?></td>
	    </tr>
	    <?php 
        }
    ?>
</table>

<br>
	<table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">6. FORMACIÓN, EXIGENCIAS Y REQUERIMIENTOS (Mínimos e Indispensables) <br>Formación básica y/o carrera sugerida y/o a fin</span>
            </td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 25%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >CARRERA</td>
           	<td id="color1" style=" width: 45%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >GRADO DE INSTRUCCIÓN</td>
          
           	<td id="color1" style=" width: 30%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >OBSERVACIÓN</td>
	    </tr>
	    <?php 

        $TM_FORMACION = Bin::Factory("Neg","TM_FORMACION");
        $DATM_FORMACION=Bin::Factory("Mod","DATM_FORMACION");
        
        $TraerFormacionMof = $DATM_FORMACION->ListarFormacionMof($cem,$puesto);

            foreach($TraerFormacionMof as $ItemFM){  

                $nomcarrera=trim($ItemFM['NO_CARRERA']);                 
                $nomforma=trim($ItemFM['NO_FORMACION']);
                $desforma=trim($ItemFM['DE_FORMACION']);
                $obsforma=trim($ItemFM['OBSERVACION_FORMACION']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 25%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($nomcarrera) ?></td>
           	<td  style=" width: 45%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($nomforma).'-'.utf8_encode($desforma) ?></td>
          
           	<td  style=" width:30%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($obsforma) ?></td>
	    </tr>
	    <?php 
        }
    ?>
</table>

<br>

<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 25%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >EXPERIENCIA PREVIA</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >AÑOS</td>
           	<td id="color1" style=" width: 15%;color: black; height: 2%;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >PERSONAS A CARGO</td>
          
           	<td id="color1" style=" width: 50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >OBSERVACIÓN</td>
	    </tr>
<?php 


                $conexion = odbc_connect('MOF','OFISIS','CQ5001'); 
          if ($conexion == FALSE){ 
            echo ('Error en la conexion'); 
            exit(); 
          }
         $queryPostu = "SELECT 
                vPUESTOS.DE_PUES_TRAB,
                CASE WHEN TTEXPERIENCIA.NI_EXPERIENCIA='BAJA' THEN '< = 1 año' 
                    WHEN TTEXPERIENCIA.NI_EXPERIENCIA='ALTA' THEN 'Más de 3 años'
                    WHEN TTEXPERIENCIA.NI_EXPERIENCIA='MEDIA' THEN 'De 1 - 3 años'
                    ELSE '0 años' END ANNOS,
                TTEXPERIENCIA.NI_EXPERIENCIA,
                TM_EXPERIENCIA.NU_PERSONAS_A_CARGO,
                TM_EXPERIENCIA.OBSERVACION_EXPERIENCIA

            FROM TM_EXPERIENCIA

            LEFT JOIN vPUESTOS ON vPUESTOS.CO_PUES_TRAB=TM_EXPERIENCIA.CO_PUESTO_EXPERIENCIA 
                AND vPUESTOS.CO_EMPR=TM_EXPERIENCIA.CO_EMPR
            LEFT JOIN TTEXPERIENCIA ON TTEXPERIENCIA.CO_EPERIENCIA=TM_EXPERIENCIA.CO_EXPERIENCIA

            WHERE TM_EXPERIENCIA.CO_EMPR='".$cem."' AND TM_EXPERIENCIA.CO_PUES_TRAB='".$puesto."'
            ORDER BY CORR_EXPERIENCIA
           ";

    $result = odbc_exec($conexion,$queryPostu);

    while($Item=odbc_fetch_array($result))
    {
       
            $PUESTO_EXPE = utf8_encode($Item['DE_PUES_TRAB']);
            $TIEMPO_EXPE = ($Item['ANNOS']);
            $CARGO_EXPE = utf8_encode($Item['NU_PERSONAS_A_CARGO']);
            $OBSER_EXPE = utf8_decode($Item['OBSERVACION_EXPERIENCIA']);
        
            if ($Item['NI_EXPERIENCIA']=='ALTA') {
                $ANNIOS='Más de 3 años';
            }elseif($Item['NI_EXPERIENCIA']=='BAJA') {
                $ANNIOS='&lt; = 1 año';
            }elseif($Item['NI_EXPERIENCIA']=='MEDIA') {
                $ANNIOS='De 1 - 3 años';
            }else{
                $ANNIOS='0 años';
            }
    
        ?>
	    <tr>
	        	
           	<td  style=" width: 25%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Puesto ocupado antes: <?php echo ($PUESTO_EXPE) ?></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $ANNIOS ?></td>
           	<td  style=" width: 15%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($CARGO_EXPE) ?></td>
          
           	<td  style=" width:50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $OBSER_EXPE?></td>
	    </tr>
	    <?php 
        }
    ?>
</table>
<br>
<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >IDIOMA</td>
           	<td id="color1" style=" width: 20%;color: black; height: 2%;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >NIVEL</td>
           
          
           	<td id="color1" style=" width: 40%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >OBSERVACIÓN</td>
	    </tr>
<?php 

        $TM_CONOCIMIENTO = Bin::Factory("Neg","TM_CONOCIMIENTO");
        $DATM_CONOCIMIENTO=Bin::Factory("Mod","DATM_CONOCIMIENTO");
        
        $TraerIdiomaMof = $DATM_CONOCIMIENTO->ListarConocimientoIdioma($cem,$puesto);

            foreach($TraerIdiomaMof as $ItemCM){    
                                 
                $descono=trim($ItemCM['DE_CONOCIMIENTO']);
                $cononivel=trim($ItemCM['NO_CONOCIMIENTO_NIVEL']);
                $obscono=trim($ItemCM['OBSERVACION_CONOCIMIENTO']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($descono) ?></td>
           	<td  style=" width: 20%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($cononivel) ?></td>
           	<td  style=" width:40%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($obscono) ?></td>
	    </tr>
	    <?php 
        }
    ?>
</table>

<br>
<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >INFORMÁTICA</td>
           	<td id="color1" style=" width: 20%;color: black; height: 2%;  text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >NIVEL</td>
           
          
           	<td id="color1" style=" width: 40%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >OBSERVACIÓN</td>
	    </tr>
<?php 

        $TM_CONOCIMIENTO = Bin::Factory("Neg","TM_CONOCIMIENTO");
        $DATM_CONOCIMIENTO=Bin::Factory("Mod","DATM_CONOCIMIENTO");
        
        $TraerIdiomaMof = $DATM_CONOCIMIENTO->ListarConocimientoInformatica($cem,$puesto);

            foreach($TraerIdiomaMof as $ItemCM){    
                                 
                $descono=trim($ItemCM['DE_CONOCIMIENTO']);
                $cononivel=trim($ItemCM['NO_CONOCIMIENTO_NIVEL']);
                $obscono=trim($ItemCM['OBSERVACION_CONOCIMIENTO']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($descono) ?></td>
           	<td  style=" width: 20%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($cononivel) ?></td>
           	<td  style=" width:40%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($obscono) ?></td>
	    </tr>
	    <?php 
        }
    ?>
</table>

<br>
<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 50%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >OTRO TIPO DE CONOCIMIENTO</td>
         
           	<td id="color1" style=" width: 50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >OBSERVACIÓN</td>
	    </tr>
<?php 

        $TM_CONOCIMIENTO = Bin::Factory("Neg","TM_CONOCIMIENTO");
        $DATM_CONOCIMIENTO=Bin::Factory("Mod","DATM_CONOCIMIENTO");
        
        $TraerIdiomaMof = $DATM_CONOCIMIENTO->ListarConocimientoOtro($cem,$puesto);

            foreach($TraerIdiomaMof as $ItemCM){    
                                 
                $descono=trim($ItemCM['DE_CONOCIMIENTO']);
                $cononivel=trim($ItemCM['NO_CONOCIMIENTO_NIVEL']);
                $obscono=trim($ItemCM['OBSERVACION_CONOCIMIENTO']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 50%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($descono) ?></td>
        
           	<td  style=" width:50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($obscono) ?></td>
	    </tr>
	    <?php 
        }
    ?>
</table>
<br>

	<table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">7. EXIGENCIAS Y REQUERIMIENTOS (Necesidades internas de Capacitación)</span>
            </td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 35%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >LUGAR:</td>
           	<td id="color1" style=" width: 35%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >ENTRENAMIENTO:</td>
          
           	<td id="color1" style=" width: 30%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >SI/NO</td>
	    </tr>
	    <?php 

        $TM_EXIGENCIA = Bin::Factory("Neg","TM_EXIGENCIA");
        $DATM_EXIGENCIA=Bin::Factory("Mod","DATM_EXIGENCIA");
        
        $TraerIdiomaMof = $DATM_EXIGENCIA->ListarExigenciaMof($cem,$puesto);

            foreach($TraerIdiomaMof as $ItemIM){    
                                 
                $descono=trim($ItemIM['UB_LUGAR']);
                $cononivel=trim($ItemIM['ENTRENAMIENTO']);
                $obscono=trim($ItemIM['REQUISITO']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 35%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($descono) ?></td>
           	<td  style=" width: 35%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($cononivel)?></td>
          
           	<td  style=" width:30%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($obscono) ?></td>
	    </tr>
	    <?php 
        }
    ?>
</table>

<br>
	<table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">8. CONDICIONES <br>(Son los términos y condiciones bajo los que el puesto se va desarrollar)</span>
            </td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >MOBILIZACIÓN</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >SI/NO</td>
          
           	<td id="color1" style=" width: 50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >OBSERVACIÓN</td>
	    </tr>
	    
	    <tr>
	        	
           	<td  style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >¿El puesto realiza viajes por motivos de trabajo?</td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $viaje ?></td>
          
           	<td  style=" width:50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $obsviaje ?></td>
	    </tr>
	     <tr>
	        	
           	<td  style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >¿El puesto requiere movilizarse de su ambiente de trabajo cotidiano?</td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $movi ?></td>
          
           	<td  style=" width:50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $obsmovi ?></td>
	    </tr>
	    <tr>
	        	
           	<td id="color1" style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >RIESGOS</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >SI/NO</td>
          
           	<td id="color1" style=" width: 50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >DESCRIPTOR</td>
	    </tr>
	    <?php 

        $TTTCONOCIMIENTO_NIVEL = Bin::Factory("Neg","TTTCONOCIMIENTO_NIVEL");
        $DATTTCONOCIMIENTO_NIVEL=Bin::Factory("Mod","DATTTCONOCIMIENTO_NIVEL");
        
        $TraerCompeMof = $DATTTCONOCIMIENTO_NIVEL->ListarCompetenciaRiesgosMof($cem,$puesto);

            foreach($TraerCompeMof as $ItemCM){    
                                 
                $nomcompe=trim($ItemCM['NO_COMPETENCIA']);
                $cocompeni=trim($ItemCM['CO_COMPETENCIA_NIVEL']);
                $nomcompedes=trim($ItemCM['NO_COMPETENCIA_DESCRIPTOR']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;" ><?php echo utf8_encode($nomcompe) ?></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($cocompeni) ?></td>
          
           	<td  style=" width:50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($nomcompedes) ?></td>
	    </tr>
	    <?php 
        }
    ?>	
    <tr>
	        	
           	<td id="color1" style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >ESFUERZO</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >SI/NO</td>
          
           	<td id="color1" style=" width: 50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >DESCRIPTOR</td>
	    </tr>
	    <?php 

        $TTTCONOCIMIENTO_NIVEL = Bin::Factory("Neg","TTTCONOCIMIENTO_NIVEL");
        $DATTTCONOCIMIENTO_NIVEL=Bin::Factory("Mod","DATTTCONOCIMIENTO_NIVEL");
        
        $TraerCompeMof = $DATTTCONOCIMIENTO_NIVEL->ListarCompetenciaEsfuerzoMof($cem,$puesto);

            foreach($TraerCompeMof as $ItemCM){    
                                 
                $nomcompe=trim($ItemCM['NO_COMPETENCIA']);
                $cocompeni=trim($ItemCM['CO_COMPETENCIA_NIVEL']);
                $nomcompedes=trim($ItemCM['NO_COMPETENCIA_DESCRIPTOR']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;" ><?php echo utf8_encode($nomcompe) ?></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo utf8_encode($cocompeni) ?></td>
          
           	<td  style=" width:50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo utf8_encode($nomcompedes) ?></td>
	    </tr>
	    <?php 
        }
    ?>
	   
</table>

<br>
	<table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">9. HERRAMIENTA <br>(Son los materiales/bienes de la organización que necesita la posición para realizar sus funciones)</span>
            </td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 30%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >HERRAMIENTA</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >SI/NO</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >CANTIDAD</td>
          
           	<td id="color1" style=" width: 50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >OBSERVACIÓN</td>
	    </tr>
	    <?php 

        $TM_EXIGENCIA = Bin::Factory("Neg","TM_EXIGENCIA");
        $DATM_EXIGENCIA=Bin::Factory("Mod","DATM_EXIGENCIA");
        
        $TraerHerramientaMof = $DATM_EXIGENCIA->ListarHerramientaMof($_SESSION["serviciolinea"]["CEM"],$_SESSION["serviciolinea"]["CPT"]);

            foreach($TraerHerramientaMof as $ItemFM){  

                $nomcarrera=trim($ItemFM['NO_HERRAMIENTA']);                 
                $nomforma=trim($ItemFM['REQUISITO']);
                $desforma=trim($ItemFM['CANTIDAD']);
                $obsforma=trim($ItemFM['OBSERVACION_HERRAMIENTA']);

        ?>
	    <tr>
	        	
           	<td  style=" width: 30%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;" ><?php echo $nomcarrera ?></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $nomforma ?></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $desforma ?></td>
          
           	<td  style=" width:50%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $obsforma ?></td>
	    </tr>
	    <?php } ?>
</table>

<br>
	<table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">10. CONSIDERACIONES LABORALES <br>(Son los materiales/bienes de la organización que necesita la posición para realizar sus funciones)</span>
            </td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" colspan="2" style=" width: 40%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Labor:</td>
           	<td id="color1" style=" width: 20%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Incentivo:</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >SI/NO:</td>
          
           	<td id="color1" style=" width: 30%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >OBSERVACIÓN:</td>
	    </tr>
	    <?php 

        $TM_LABORAL = Bin::Factory("Neg","TM_LABORAL");
        $DATM_LABORAL=Bin::Factory("Mod","DATM_LABORAL");
        
        $TraerLaboralMof = $DATM_LABORAL->ListarLaboralMof($_SESSION["serviciolinea"]["CEM"],$_SESSION["serviciolinea"]["CPT"]);

            foreach($TraerLaboralMof as $ItemLM){    
                                 
                $diastrabsema=trim($ItemLM['DIAS_TRAB_SEMA']);
                $diasdessema=trim($ItemLM['DIAS_DESC_SEMA']);
                $horastrabsema=trim($ItemLM['HORAS_TRAB_SEM']);
                $horasrefrisema=trim($ItemLM['HORAS_REFRI_SEM']);
                $diasematrab=trim($ItemLM['DIAS_SEM_TRAB']);
                $horateorico=trim($ItemLM['HORARIO_TEORICO']);
                $individual=trim($ItemLM['I_INDIVIDUAL']);
                $gestion=trim($ItemLM['I_GESTION']);
                $colectivo=trim($ItemLM['I_COLECTIVO']);
                $comisiones=trim($ItemLM['I_COMISIONES']);
                $movilidad=trim($ItemLM['I_MOVILIDAD']);
                $trimestral=trim($ItemLM['I_TRIMESTRAL']);
                $cuatrimestral=trim($ItemLM['I_CUATRIMESTRAL']);
                $anual=trim($ItemLM['I_ANUAL']);
                $obslaboral=trim($ItemLM['OBSERVACION_LABORAL']);
            }

      ?>
	    <tr>
	        	
           	<td  style=" width: 20%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >Días de Trabajo Semanales:</td>
           	<td  style=" width: 20%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $diastrabsema ?></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >INDIVIDUAL:</td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $individual ?></td>
          
           	<td rowspan="8" style=" width:30%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $obslaboral ?></td>
	    </tr>
	    <tr>
	        	
           	<td  style=" width: 20%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >Días de Descanso Semanales:</td>
           	<td  style=" width: 20%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $diasdessema ?></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >GESTIÓN:</td>
          
           	<td  style=" width:10%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $gestion ?></td>
	    </tr>

	     <tr>
	        	
           	<td  style=" width: 20%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >Horas de Trabajo a la Semana:</td>
           	<td  style=" width: 20%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $horastrabsema ?></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >COLECTIVO:</td>
          
           	<td  style=" width:10%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $colectivo ?></td>
	    </tr>
	    <tr>
	        	
           	<td  style=" width: 20%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >Horas de Refrigerio Semanal:</td>
           	<td  style=" width: 20%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $horasrefrisema ?></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >COMISIONES:</td>
          
           	<td  style=" width:10%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $comisiones ?></td>
	    </tr>

	    <tr>
	        	
           	<td  style=" width: 20%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >Días de la Semana de Trabajo:</td>
           	<td  style=" width: 20%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $diasematrab ?></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >MOVILIDAD:</td>
          
           	<td  style=" width:10%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $movilidad ?></td>
	    </tr>

	    <tr>
	        	
           	<td  style=" width: 20%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >Horario Teórico Semanal:</td>
           	<td  style=" width: 20%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $horateorico ?></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >TRIMESTRAL:</td>
          
           	<td  style=" width:10%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $trimestral ?></td>
	    </tr>

	    <tr>
	        	
           	<td  style=" width: 20%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;" ></td>
           	<td  style=" width: 20%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;" ></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >CUATRIMESTRAL:</td>
          
           	<td  style=" width:10%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $cuatrimestral ?></td>
	    </tr>
	    <tr>
	        	
           	<td  style=" width: 20%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;" ></td>
           	<td  style=" width: 20%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;" ></td>
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >ANUAL:</td>
          
           	<td  style=" width:10%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $anual ?></td>
	    </tr>
	  
</table>

<br>
	<table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">11. RESPONSABILIDAD ANTE RESULTADOS</span>
            </td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 45%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >COMPETENCIAS DE RESPONSABILIDAD ANTE RESULTADOS</td>
           	<td id="color1" style=" width: 10%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >NIVEL</td>
          
          
           	<td id="color1" style=" width: 45%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >DESCRIPTOR</td>
	    </tr>
	    <?php 

        $TTTCONOCIMIENTO_NIVEL = Bin::Factory("Neg","TTTCONOCIMIENTO_NIVEL");
        $DATTTCONOCIMIENTO_NIVEL=Bin::Factory("Mod","DATTTCONOCIMIENTO_NIVEL");
        
        $TraerCompeMof = $DATTTCONOCIMIENTO_NIVEL->ListarCompetenciaResultadosMof($cem,$puesto);

            foreach($TraerCompeMof as $ItemCM){    
                                 
                $nomcompe=trim($ItemCM['NO_COMPETENCIA']);
                $cocompeni=trim($ItemCM['CO_COMPETENCIA_NIVEL']);
                $nomcompedes=trim($ItemCM['NO_COMPETENCIA_DESCRIPTOR']);
        ?>
	    <tr>
	        	
           	<td  style=" width: 45%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;" ><?php echo $nomcompe ?></td>
           	
           	<td  style=" width: 10%;padding-left: 5px; padding-right: 5px;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $cocompeni ?></td>
          
           	<td  style=" width:45%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $nomcompedes ?></td>
	    </tr>
	    <?php } ?>
</table>

<br>

<table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">INDICADORES DE GESTIÓN: <br>Indicadores principales de Gestión del Puesto.</span>
            </td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 25%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Objetivo</td>
           	<td id="color1" style=" width: 15%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Indicador</td>
          	<td id="color1" style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Mínimo</td>
          	<td id="color1" style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Meta</td>
          	<td id="color1" style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Máximo</td>
          	<td id="color1" style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Peso</td>
          	<td id="color1" style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Periodo</td>
          
           	<td id="color1" style=" width: 20%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >Observación</td>
	    </tr>
	    <?php 

        $TM_EXIGENCIA = Bin::Factory("Neg","TM_EXIGENCIA");
        $DATM_EXIGENCIA=Bin::Factory("Mod","DATM_EXIGENCIA");
        
        $TraerIndicadorMof = $DATM_EXIGENCIA->ListarIndicadorMof($cem,$puesto);

            foreach($TraerIndicadorMof as $ItemIM){    
                                 
                $objetivoindi=utf8_encode(trim($ItemIM['OBJETIVO_INDICADOR']));
                $nomindi=utf8_encode(trim($ItemIM['NO_INDICADOR']));
                $min=trim($ItemIM['MIN']);
                $meta=trim($ItemIM['META']);
                $max=trim($ItemIM['MAX']);
                $peso=trim($ItemIM['PESO']);
                $periodo=trim($ItemIM['PERIODO']);
                $obsindi=utf8_encode(trim($ItemIM['OBSERVACION_INDICADOR']));
                $suma=$suma+trim($ItemIM['PESO']);
                
        ?>
	    <tr>
	        	
           	<td style=" width: 25%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $objetivoindi ?></td>
           	<td style=" width: 15%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $nomindi ?></td>
          	<td style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $min ?></td>
          	<td style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $meta ?></td>
          	<td style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $max ?></td>
          	<td style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $peso.'%' ?></td>
          	<td style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $periodo ?></td>
          
           	<td style=" width: 20%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $obsindi ?></td>
	    </tr>
	   
	    <?php } ?>

	    <tr>
	        	
           	<td style=" width: 25%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;" ></td>
           	<td style=" width: 15%;color: black; height: 2%;  text-align: center;" ></td>
          	<td style=" width: 8%;color: black; height: 2%;  text-align: center;" ></td>
          	<td style=" width: 8%;color: black; height: 2%;  text-align: center;" ></td>
          	<td style=" width: 8%;color: black; height: 2%;  text-align: right;" >Total:</td>
          	<td style=" width: 8%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; " ><?php echo $suma.'%' ?></td>
          	<td style=" width: 8%;color: black; height: 2%;  text-align: center;border-left: 1px solid #000000" ></td>
          
           	<td style=" width: 20%;padding-left: 5px;color: black; height: 2%;  text-align: left;" >
           	</td>
           </tr>
</table>

<br>

<table cellspacing="0" style="width: 100%; ">
		<tr>
            <td id="color1"  style="width: 100%; color: white;font-size:15px;text-align: left;padding-left: 5px;border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;border-bottom: 1px solid #000000; padding-left: 5px">
                <span style="color: black;font-size:12px;font-weight:bold">12. ELABORACIÓN Y VALIDACIÓN: <br>Personas que elaboran y validan el documento.</span>
            </td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; font-size: 12px">
		<tr>
	        	
           	<td id="color1" style=" width: 25%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Objetivo</td>
           	<td id="color1" style=" width: 20%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Nombre y Apellidos</td>
          	<td id="color1" style=" width: 20%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Puesto</td>
          	<td id="color1" style=" width: 15%;color: black; height: 2%;  text-align: center;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" >Fecha</td>
          	
          
           	<td id="color1" style=" width: 20%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" >Observación</td>
	    </tr>
	    
	   <tr>
	        	
           	<td style=" width: 25%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >Elaborado por:</td>
           	<td style=" width: 20%;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $nomela ?></td>
          	<td style=" width: 20%;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $puesela ?></td>
          	<td style=" width: 15%;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $fechaela ?></td>
          	
          
           	<td style=" width: 20%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $obsela ?></td>
	    </tr>
	 	<tr>
	        	
           	<td style=" width: 25%;color: black; height: 2%; padding-left: 5px; padding-right: 5px;text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;background: #F2F2F2;" >Validado y Aprobado por:</td>
           	<td style=" width: 20%;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $nomapro ?></td>
          	<td style=" width: 20%;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $puesapro ?></td>
          	<td style=" width: 15%;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000" ><?php echo $fechaapro ?></td>
          	
          
           	<td style=" width: 20%;padding-left: 5px;color: black; height: 2%;  text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ><?php echo $obsapro ?></td>
	    </tr>
</table>

        <page_footer>
        <table >
            <tr>

                <td style="width: 50%; text-align: left">
                  
                </td>
                
            </tr>
        </table>


    </page_footer>
    
    <!--

    
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

			    $arregloEmp = $DAvBOLETA3->SeleccionarSueldo($co_trab,$anno,$peri);
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
 
	<br><br><br><br><br><br><br><br><br>

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
  
    
	
	
	
	
	  
-->
</page>

