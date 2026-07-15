<?php

 $libreriasCABE = [
 	/*** Fontawesome ***/
  	"Libreria1" => [    "nombre" => 'font-awesome', "tipo" => 'CSS',"estado" => 'ACT',
					  	"url" => 'public/lib/font-awesome/css/font-awesome.min.css' ],
	/** Bootstrap **/				  	
	"Libreria2" => [    "nombre" => 'Bootstrap', "tipo" => 'CSS',"estado" => 'ACT',
					  	"url" => 'public/lib/bootstrap/css/bootstrap.min.css' ],
	/** Sweet Alert **/
	"Libreria3" => [    "nombre" => 'Sweet-Alert', "tipo" => 'CSS',"estado" => 'ACT',
					  	"url" => 'public/lib/sweet-alert/sweetalert.css' ],
	"Libreria4" => [    "nombre" => 'Sweet-Alert', "tipo" => 'JS',"estado" => 'ACT',
					  	"url" => 'public/lib/sweet-alert/sweetalert.min.js' ],
	/** select2 **/					  	
	"Libreria5" => [    "nombre" => 'select2', "tipo" => 'CSS',"estado" => 'ACT',
						  	"url" => 'public/lib/select2/select2.min.css' ],
	"Libreria6" => [    "nombre" => 'select2 JS', "tipo" => 'JS',"estado" => 'ANU',
						  	"url" => 'public/lib/select2/select2.min.js' ],
	/** jquery data tables **/
	"Libreria7" => [    "nombre" => 'jquery datatables', "tipo" => 'CSS',"estado" => 'ACT',
						  	"url" => 'public/lib/datatables/jquery.dataTables.min.css' ],
	/** Summernote (editor de texto enriquecido para Objetivo del puesto/Funciones de Vacantes) **/
	"Libreria8" => [    "nombre" => 'summernote-bs4', "tipo" => 'CSS',"estado" => 'ACT',
						  	"url" => 'public/lib/summernote/summernote-bs4.min.css' ],

	/** Others **/
	/*				  	
	"Libreria14" => [    "nombre" => '', "tipo" => 'CSS',"estado" => 'ANU',
						  	"url" => '' ],
	"Libreria15" => [    "nombre" => '', "tipo" => 'JS',"estado" => 'ANU',
					  	"url" => '' ],
	*/
	];

/*
	<link rel="icon" href="<?php echo RUTA_URL;?>public/img/Category-Salary.png" sizes="250x250" type="img/png" />
	<!----------------------------------------------------------->
	<!-- Custom fonts for this template-->
	<link href="<?php echo RUTA_URL;?>public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<!-- Custom styles for this template-->
	<link href="<?php echo RUTA_URL;?>public/css/sb-admin-2.css" rel="stylesheet">
	<!----------------------------------------------------------->
	<!--Sweet Alert -->
	<link href="<?php echo RUTA_URL?>public/vendor/sweet-alert/sweetalert.css" rel="stylesheet">
	<!-- Sweet Alert Script -->
	<script src="<?php echo RUTA_URL?>public/vendor/sweet-alert/sweetalert.min.js"></script>
  <!----------------------------------------------------------->
	<!-- Jquery -->
	<script src="<?php echo RUTA_URL;?>public/vendor/node_modules/jquery/jquery.min.js"></script>
  <!----------------------------------------------------------->
	<!-- Data Tables & Data Table Scripts -->
	<script src="<?php echo RUTA_URL;?>public/vendor/node_modules/datatables/jquery.dataTables.min.js"></script>
	<link href="<?php echo RUTA_URL;?>public/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo RUTA_URL?>public/css/estilos-buscador.css">
  <!----------------------------------------------------------->
	<!-- Personalized CSS & Others -->
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet"/>
	<link href="<?php echo RUTA_URL;?>public/css/styledash.css" rel="stylesheet" />
	<link href="<?php echo RUTA_URL;?>public/css/stylegeneral.css" rel="stylesheet" />
	<link	href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" type="text/css"/>

	<link href="<?php echo RUTA_URL;?>public/vendor/select2/select2.min.css" rel="stylesheet"/>
	<script src="<?php echo RUTA_URL;?>public/vendor/select2/select2.min.js"></script>
*/

 $libreriasFOTE = [
 	/** Jquery **/		
 	"Libreria1" => [    "nombre" => 'Jquery', "tipo" => 'JS',"estado" => 'ACT',
					  	"url" => 'public/lib/jquery/jquery.min.js' ],
	/** Bootstrap **/		
	"Libreria2" => [    "nombre" => 'Bootstrap', "tipo" => 'JS',"estado" => 'ACT',
					  	"url" => 'public/lib/bootstrap/js/popper.min.js' ],
	"Libreria3" => [    "nombre" => 'Bootstrap', "tipo" => 'JS',"estado" => 'ACT',
					  	"url" => 'public/lib/bootstrap/js/bootstrap.min.js' ],
	/** select2 -- claves renumeradas 2026-07-15: "Libreria14"/"Libreria15" colisionaban con las
	 * claves reales de jquery.dataTables mas abajo (pdfmake.min.js/vfs_fonts.js), que las
	 * sobreescribian silenciosamente (un array PHP no permite claves de string duplicadas) --
	 * por eso el JS de select2 nunca cargaba pese a estar marcado ACT. **/
	"Libreria20" => [    "nombre" => 'select2', "tipo" => 'CSS',"estado" => 'ANU',
						  	"url" => 'public/lib/select2/select2.min.css' ],
	"Libreria21" => [    "nombre" => 'select2 JS', "tipo" => 'JS',"estado" => 'ACT',
						  	"url" => 'public/lib/select2/select2.min.js' ],

	/** jquery.easing **/
	"Libreria4" => [    "nombre" => 'jquery.easing', "tipo" => 'JS',"estado" => 'ANU',
					  	"url" => 'public/vendor/jquery-easing/jquery.easing.min.js' ],
	/** sb-admin-2 **/				  			
	"Libreria5" => [    "nombre" => 'sb-admin-2', "tipo" => 'JS',"estado" => 'ANU',
					  	"url" => 'public/js/sb-admin-2.min.js' ],
	/** CHAR JS **/
	"Libreria6" => [    "nombre" => 'Chart.min.js', "tipo" => 'JS',"estado" => 'ANU',
					  	"url" => 'public/vendor/chart.js/Chart.min.js' ],				  	
	"Libreria7" => [    "nombre" => 'Chart', "tipo" => 'JS',"estado" => 'ANU',
						"url" => 'public/js/demo/chart-area-demo.js' ],
	"Libreria8" => [    "nombre" => 'Chart', "tipo" => 'JS',"estado" => 'ANU',
						"url" => 'public/js/demo/chart-pie-demo.js' ],
	"Libreria9" => [    "nombre" => 'Chart', "tipo" => 'JS',"estado" => 'ANU',
					  	"url" => 'public/js/demo/chart-bar-demo.js' ],
	/** jquery.dataTables **/					  	
	"Libreria10" => [    "nombre" => 'jquery.dataTables', "tipo" => 'JS',"estado" => 'ACT',
						  	"url" => 'public/lib/datatables/jquery.dataTables.min.js' ],
	"Libreria11" => [    "nombre" => 'jquery.dataTables', "tipo" => 'JS',"estado" => 'ACT',
						  	"url" => 'public/lib/datatables/dataTables.buttons.min.js' ],
	"Libreria12" => [    "nombre" => 'jquery.dataTables', "tipo" => 'JS',"estado" => 'ACT',
						  	"url" => 'public/lib/datatables/buttons.flash.min.js' ],
	"Libreria13" => [    "nombre" => 'jquery.dataTables', "tipo" => 'JS',"estado" => 'ACT',
						  	"url" => 'public/lib/datatables/jszip.min.js' ],
	"Libreria14" => [    "nombre" => 'jquery.dataTables', "tipo" => 'JS',"estado" => 'ACT',
						  	"url" => 'public/lib/datatables/pdfmake.min.js' ],
	"Libreria15" => [    "nombre" => 'jquery.dataTables', "tipo" => 'JS',"estado" => 'ACT',
						  	"url" => 'public/lib/datatables/vfs_fonts.js' ],
	"Libreria16" => [    "nombre" => 'jquery.dataTables', "tipo" => 'JS',"estado" => 'ACT',
						  	"url" => 'public/lib/datatables/buttons.html5.min.js' ],
	"Libreria17" => [    "nombre" => 'jquery.dataTables', "tipo" => 'JS',"estado" => 'ACT',
						  	"url" => 'public/lib/datatables/buttons.print.min.js' ],
	/** BUSCADOR **/
	"Libreria18" => [    "nombre" => 'Buscador', "tipo" => 'JS',"estado" => 'ACT',
						  	"url" => 'public/js/buscador.js' ],
	/** Summernote (editor de texto enriquecido, requiere jQuery+Bootstrap JS, ya ACT arriba) **/
	"Libreria19" => [    "nombre" => 'summernote-bs4', "tipo" => 'JS',"estado" => 'ACT',
						  	"url" => 'public/lib/summernote/summernote-bs4.min.js' ],


	/** dataTables **/					  	
	
	/** Others **/			
	/*		  	
	"Libreria13" => [    "nombre" => '', "tipo" => 'CSS',"estado" => 'ANU',
						  	"url" => '' ],
	"Libreria14" => [    "nombre" => '', "tipo" => 'JS',"estado" => 'ANU',
					  	"url" => '' ],
					  	*/
	];
/*
	<!-- Bootstrap core JavaScript -->
	<script src="<?php echo RUTA_URL;?>public/vendor/jquery/jquery.min.js"></script>
	<script src="<?php echo RUTA_URL;?>public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!----------------------------------------------------------->
	<!-- Core plugin JavaScript-->
	<script src="<?php echo RUTA_URL;?>public/vendor/jquery-easing/jquery.easing.min.js"></script>
	<!----------------------------------------------------------->
	<!-- Custom scripts for all pages-->
	<script src="<?php echo RUTA_URL;?>public/js/sb-admin-2.min.js"></script>
	<!----------------------------------------------------------->
	<!-- Page level plugins
	<script src="<?php echo RUTA_URL;?>public/vendor/chart.js/Chart.min.js"></script>
	-->
	<!-- Page level custom scripts
	<script src="<?php echo RUTA_URL;?>public/js/demo/chart-area-demo.js"></script>
	<script src="<?php echo RUTA_URL;?>public/js/demo/chart-pie-demo.js"></script>
	<script src="<?php echo RUTA_URL;?>public/js/demo/chart-bar-demo.js"></script>
	-->
	<!----------------------------------------------------------->
	<!-- Page level plugins -->
	<script src="<?php echo RUTA_URL;?>public/vendor/datatables/jquery.dataTables.min.js"></script>
	<!-- Data Tables
	<script src="<?php echo RUTA_URL;?>public/vendor/node_modules/datatables/jquery.dataTables.min.js"></script>
	-->
	<script src="<?php echo RUTA_URL;?>public/vendor/datatables/dataTables.bootstrap4.min.js"></script>
	<!----------------------------------------------------------->
	<!-- Page level custom scripts -->
	<script src="<?php echo RUTA_URL;?>public/js/demo/datatables-demo.js"></script>
*/

?>
