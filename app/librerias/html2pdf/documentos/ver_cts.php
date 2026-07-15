<?php //session_start();


//error_reporting(E_ALL ^ E_NOTICE);





    $boleta=explode('*', $_GET['cod_periodo']);
    $anno_1=$boleta[0];
    $peri_1=$boleta[1];
    $empresa_1=$boleta[2];


    $co_trab=str_pad($_GET['dni'], 8, "0", STR_PAD_LEFT);
    $co_emp=$empresa_1;
    $anno=$anno_1;
    $peri=$peri_1;
    //$id_factura= intval($_GET['id_factura']);
   
   
	require_once(dirname(__FILE__).'/../html2pdf.class.php');
    // get the HTML
     ob_start();
     include(dirname('__FILE__').'/res/ver_cts_descargar.php');
    $content = ob_get_clean();

    try
    {
        // init HTML2PDF
        $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', array(0, 0, 0, 0));
        // display the full page
        $html2pdf->pdf->SetDisplayMode('fullwidth');
        // convert
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        // send the PDF
        $html2pdf->Output('Boleta.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
