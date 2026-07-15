<?php

ob_start();/* session_start();
ob_start();
  error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION["serviciolinea"])) {header("Location: index.php");}

define('RAIZ',$_SERVER['DOCUMENT_ROOT']);

include_once(RAIZ."/servicio-online/bin/Bin.php");
include_once(RAIZ."/servicio-online/bin/Libreria/Conexion.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionDashboard.php");

$VDIRECTORIO_RENOVACION = Bin::Factory("Neg","VDIRECTORIO_RENOVACION");
$DAVDIRECTORIO_RENOVACION=Bin::Factory("Mod","DAVDIRECTORIO_RENOVACION");*/
//error_reporting(E_ALL ^ E_NOTICE);

	$dni= $_GET['dni'];
    





    $anno2=$_GET['anno2'];
    $unmail = explode("*", $anno2); 

    $anno = $unmail[0]; 
    $peri = $unmail[1]; 
   
    $anno2=$_GET['anno2'];
    //$dni= intval($_GET['dni']);
   
   
	require_once(dirname(__FILE__).'/../html2pdf.class.php');
    // get the HTML
     
     include(dirname('__FILE__').'/res/ver_cuatri.php');
    $content = ob_get_clean();

    try
    {
        // init HTML2PDF
        $html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
        // display the full page
        $html2pdf->pdf->SetDisplayMode('fullpage');
        // convert
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        // send the PDF
         ob_end_clean();
        $html2pdf->Output('cuatrimestral.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
