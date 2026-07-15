<?php
/**
 * Autoloader minimo (PSR-0) para smalot/pdfparser, vendorizado sin Composer
 * (mismo criterio que PHPMailer/html2pdf en este proyecto).
 */
spl_autoload_register(function($clase){
	$prefijo = 'Smalot\\PdfParser\\';
	if(strncmp($prefijo, $clase, strlen($prefijo)) !== 0){
		return;
	}
	$relativa = substr($clase, strlen($prefijo));
	$archivo = __DIR__.'/src/Smalot/PdfParser/'.str_replace('\\', '/', $relativa).'.php';
	if(file_exists($archivo)){
		require $archivo;
	}
});
