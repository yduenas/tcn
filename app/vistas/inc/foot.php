
<script> </script>

	<?php	
		if( CADUCA_SESSION == 'S'){	caducidadSESSION(10,CONTROLADOR_LOGOUT.'/'.METODO_LOGOUT); }
	//	if( CADUCA_SESSION == 'S'){	require_once '../app/helpers/session_helper.php'; }
		if(LIB_CSS_JS_FOTE =='S'){ librerias_CSS_JS('FOOTER'); } 
		if(JAVA_SCRIPTS_PUBLICS =='S'){ publics_JavaScripts(); } 
	?>

</body>

</html>
