<?php
// defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo RUTA_URL;?>public/img/COMPLEMENT_logo.png">
	<title>Welcome to <?= FRAMEWORK_NAME ?></title>

	<style type="text/css">

	::selection { background-color: #f07746; color: #fff; }
	::-moz-selection { background-color: #f07746; color: #fff; }

	body {
		background-color: #fff;
		margin: 40px auto;
		max-width: 1024px;
		font: 16px/24px normal "Helvetica Neue",Helvetica,Arial,sans-serif;
		color: #808080;
	}

	a {
		color: #10599E; /*dd4814*/
		background-color: transparent;
		font-weight: normal;
		text-decoration: none;
	}

	a:hover {
		color: #97310e;
	}

	h1 {
		color: #fff;
		background-color: #10599E;
		border-bottom: 1px solid #d0d0d0;
		font-size: 22px;
		font-weight: bold;
		margin: 0 0 14px 0;
		padding: 5px 10px;
		line-height: 40px;
	}

	h1 img {
		display: block;
	}

	h2 {
		color:#404040;
		margin:0;
		padding:0 0 10px 0;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 13px;
		background-color: #f5f5f5;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
		min-height: 96px;
	}

	p {
		margin: 0 0 10px;
		padding:0;
	}

	p.footer {
		text-align: right;
		font-size: 12px;
		border-top: 1px solid #d0d0d0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
		background:#8ba8af;
		color:#fff;
	}

	#container {
		margin: 10px;
		border: 1px solid #d0d0d0;
		box-shadow: 0 0 8px #d0d0d0;
		border-radius: 4px;
	}

	img {
	 max-width: 10%;
	 max-height: 10%;
	/*opacity: 0.5;*/
	}

	footer {
		text-align: right;
		font-size: 12px;
		border-top: 1px solid #d0d0d0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
		background:#8ba8af;
		color:#fff;
	}

	</style>
</head>
<body>
	<div id="container">
		<h1>
			<a href="<?= RUTA_URL; ?>">
		        <img alt="Complement" src="<?= RUTA_URL; ?>public/img/COMPLEMENT_framework.jpg"/>
		    </a>
		</h1>

		<div id="body">
			<p><strong><?= FRAMEWORK_NAME ;?> PHP </strong>  
			usted se encuetra en elmodulode configuracion.</p>

			<p>En donde encontrara acceso a las funciones predeterminadaas del sistema.</p>
			
			<code><a href="<?= RUTA_URL; ?>configuraciones/index"><?= RUTA_URL; ?>configuraciones/index</a></code>
			<!--
			<p>El controlador correspondiente para esta página se encuentra en:</p>
			<code>app/controladores/inicios.php</code>
			-->
			<p>Para iniciar en la implementacion de <strong>Complement PHP FRAMEWORK</strong>  por primera vez, debe comenzar leyendo las configuraciones y ayudas al usuario . <a href="<?= RUTA_URL ?>configuraciones/listaMetodos">Guía de configuracion</a>.</p>
		</div>

		<footer class="footer">
		        <span>Copyright © <?php echo date('Y'); ?> Designed by 
		           <a href="http://complementhrm.net/" 
		              target="_blank" 
		              title="<?= FRAMEWORK_NAME ;?>">
		              <strong>
		              	<?= strtoupper(MARCA_PATENTE) ;?>
		              </strong>
		           </a>. All rights reserved. Versión <?= VERSION ?>          
		        </span>
		</footer>
	</div>
	
</body>
</html>