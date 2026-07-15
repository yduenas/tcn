<?php /*session_start();

if(!isset($_SESSION["serviciolinea"])) {header("Location: index.php");}

define('RAIZ',$_SERVER['DOCUMENT_ROOT']);
*/
/*
include_once(RAIZ."/servicio-online/bin/Bin.php");
include_once(RAIZ."/servicio-online/bin/Libreria/Conexion.php");
*/

//error_reporting(E_ALL ^ E_NOTICE);





    $boleta=explode('*', $_GET['boleta']);
    $anno_1=$boleta[0];
    $peri_1=$boleta[1];



    $co_trab=$_GET['dni'];
    $co_emp=$_GET['empr'];
    $anno=$anno_1;
    $peri=$peri_1;
    $boleta=$_GET['boleta'];
    //$id_factura= intval($_GET['id_factura']);
   
   
	require_once(dirname(__FILE__).'/../html2pdf.class.php');
    // get the HTML
     ob_start();
     include(dirname('__FILE__').'/res/ver_factura_html.php');
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
        $html2pdf->Output('Boleta.pdf');

     
        
/*
        $img_name = time().'.jpg';

        exec('/usr/bin/convert -density 300  /home/usuario/public_html/creador/temp2.pdf -quality 100 -resize 10% /home/usuario/public_html/creador/'.$img_name.'');
        ?>

        <img src="<? echo $img_name ?>"/> <?php*/
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
