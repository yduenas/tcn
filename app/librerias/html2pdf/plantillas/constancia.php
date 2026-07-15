<!--EMPLEADOS-->
<?php session_start(); ?>

<style type="text/css">


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
<!--DECLARACION JURADA DE DOMICILIO-->
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
    
    <!--ESTO ES PARA EL PIE DE PAGINA POR EJEMPLO PAGINA 1/20-->
    <page_footer>
        <!--<table class="page_footer" style="padding-bottom: 50px;display:none">
            <tr>

                <td style="width: 50%; text-align: left;padding-left:40px">
                    P&aacute;gina [[page_cu]]/[[page_nb]]
                </td>
                <td style="width: 50%; text-align: left">
                    <?php echo $dni_postulante.' - '.$nom_postu; ?>
                </td>
            </tr>
        </table>-->
    </page_footer>

    <table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
        <tr>
        	<td>
 <!--          		<img src="http://localhost/hrc/assets/img/cabecera-constancia-tailoy.png" style='width: 600px; height: 180px'> -->
              <img src="http://localhost/hrc6/assets/img/logos/logoTE.jpg" style='width: 600px; height: 180px'>
           	</td>
        </tr>
	</table>
    <br><br>
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 35%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:18px;font-weight:bold;text-decoration: underline black;">CONSTANCIA DE TRABAJO</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:15px;">Mediante el presente CERTIFICAMOS que el(la) señor(a) <?php echo utf8_encode($_SESSION['no_trab']).' '.utf8_encode($_SESSION['no_apel_pate']).' '.utf8_encode($_SESSION['no_apel_mate']) ?> con DNI Nº: <?php echo utf8_encode($_SESSION['co_trab']) ?> labora en nuestra empresa,desempeñando el cargo de <?php echo utf8_encode($_SESSION['de_pues_trab']) ?> desde el 
                <?php echo date("d/m/Y", strtotime($_SESSION['fe_ingr_plan'])); ?> hasta la actualidad.</span>
           	</td>
           	
        </tr>

	</table>
	<br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:15px;">Se expide el presente documento para los fines que estime conveniente.</span>
           	</td>
           	
        </tr>

	</table>
	<br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:15px;">Lima, 
                <?php 

                date_default_timezone_set("America/Lima");
                //date_default_timezone_set('UTC');
                      $arrayMeses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 
                                          'Junio', 'Julio', 'Agosto', 'Septiembre', 
                                          'Octubre', 'Noviembre', 'Diciembre');
 
                      $arrayDias = array( 'Domingo', 'Lunes', 'Martes',
                                          'Miercoles', 'Jueves', 'Viernes', 'Sabado');
     
    echo $arrayDias[date('w')].", ".date('d')." de ".$arrayMeses[date('m')-1]." de ".date('Y'); 
                ?>
  
              </span>
           	</td>
           	
        </tr>

	</table>
	<br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:15px;">Atentamente,</span>
           	</td>
           	
        </tr>

	</table>
	
	<br><br><br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:15px;">______________________________</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:15px;"><?php echo utf8_encode($_SESSION['no_trab']).' '.utf8_encode($_SESSION['no_apel_pate']).' '.utf8_encode($_SESSION['no_apel_mate']) ?><br><?php echo utf8_encode($_SESSION['de_pues_trab']) ?><br><?php echo utf8_encode($_SESSION['de_area']) ?></span>
           	</td>
           	
        </tr>

	</table>
</page>