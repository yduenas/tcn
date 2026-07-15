<?php 
session_start()

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo 'hola como estas' ?></title>
</head>
<body>
		<?php echo 'hola como estas' ?>
		<br>
		<?php echo 'hola como estas'.  utf8_encode($_SESSION['no_apel_pate']). ' ' . 
                                                       utf8_encode($_SESSION['no_apel_mate']). ' ' . 
                                                       utf8_encode($_SESSION['no_trab']) .' hola' ?>
</body>
</html>