<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, shrink-to-fit=no"
		/>
		<meta name="description" content="" />
		<meta name="author" content="" />
		<!-- Title -->
		<title> <?php echo NOMBRESITIO;?></title>
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo RUTA_URL;?>public/img/COMPLEMENT_logo.png">

		<!-- Tipografia de marca (seccion 5 del CLAUDE.md) -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

		<!-- Librerias (Bootstrap, etc.) -->
		<?php if(LIB_CSS_JS_CABE =='S'){ librerias_CSS_JS('CABECERA'); }	?>

		<!-- Sistema de diseño Complement, se carga despues de Bootstrap para ganar la cascada -->
		<link href="<?php echo RUTA_URL;?>public/css/complement.css" rel="stylesheet">

		<!-- RUTA_URL como global JS -- public/js/app.js se inyecta via file_get_contents()
		     (publics_JavaScripts(), url_helper.php) y NO se procesa como PHP, asi que
		     cualquier URL que necesite ahi debe armarse a partir de esta variable. -->
		<script>var RUTA_URL = "<?php echo RUTA_URL; ?>";</script>

</head>
<body id="page-top">
		<!-- Page Wrapper -->
		<div id= wrapper >
     <div id="content-wrapper" class="macro">
				<div id="content">
						<!-- Start of Nav  -->



