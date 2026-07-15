<?php 
session_start();
//if(!isset($_SESSION['LoggedIn'])) {header("Location: ../../../../../index.php");}
/*	define('RAIZ',$_SERVER['DOCUMENT_ROOT']);	*/
//include("../../../includes/conectar_bd2.php");
/*	colocar query2s	*/
//include("../../assets/includes/funciones.php");

include($_SERVER['DOCUMENT_ROOT']."/hrc/assets/includes/conectar_bd2.php");
include($_SERVER['DOCUMENT_ROOT']."/hrc/modulos/boletas/funciones_boleta.php");

?>

<link href='../../../assets/css/boleta.css' rel='stylesheet'>

<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >

<?php 
      
//$_GET["saludo"]

//echo $CO_TRAB;

$resultado=vBoleta1($CO_TRAB,$NU_ANNO ,$NU_PERI);

//echo $CO_TRAB; //,$NU_ANNO,$NU_PERI;
//$resultado=vBoleta1('25793692',2020,2);


                        while($fila = odbc_fetch_array($resultado))
                            {
                              echo '<table cellspacing="0" style="width: 100%;">';
                              echo '<tr>';
                              echo '<td>'.$fila['CO_TRAB'].'</td><br>';
                                //  NO_APEL_PATE utf8_encode($fila['NO_APEL_PATE'])&
                              echo '<td>'.utf8_encode($fila['NOMBRES_Y_APELLIDOS']).'</td><br>';
                              echo '<td>'.$fila['NU_ANNO'].'</td><br>';
                              echo '<td>'.$fila['NU_PERI'].'</td><br>';
                              echo '<td>'.$fila['DE_PUES_TRAB'].'</td><br>';
                            //  echo '<td>'.$fila['NU_TELE_EMPR'].'</td>';
                             // echo '<td>'.$fila['NO_DIRE_MAI2'].'</td>';
                          
                              echo '</tr><br></table>';
                             //     $_SESSION['CO_TRAB'] = $fila['CO_TRAB'];
                            }



 ?>
 </page>


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
                <span style="color: black;font-size:14px;font-weight:bold"><?php echo $razon_social ?> </span><br><?php echo $razon_social_DIR ?><br><?php echo $razon_social_RUC ?><br>



            </td>
			<td style="width: 25%; color: #444444;">
                asdf
            </td>
			<td style="width: 25%;text-align:right"> asdf
				 <?php if ($co_emp=='01') { ?>
			 		
				 
				 <?php }?>
			</td>
        </tr>
    </table>

</page>

