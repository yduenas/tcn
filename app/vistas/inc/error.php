

<body class="fix-header card-no-border fix-sidebar">
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper" class="error-page">
        <div class="error-box">
            <div class="error-body text-center">
                <h1 class="text-info">400</h1>
                <h3 class="text-uppercase">Pagina no encontrada !</h3>
                <p class="text-muted m-t-30 m-b-30">PARECE QUE ESTÁS TRATANDO DE ENCONTRAR SU CAMINO A CASA</p>
                <a href="<?= RUTA_URL; ?>" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Back to home</a> </div>
            
            <footer class="footer text-center">
		        <span>Copyright © <?php echo date('Y'); ?> Designed by 
		           <a href="http://complementhrm.net/" 
		              target="_blank" 
		              title="<?= FRAMEWORK_NAME ;?>">
		              <strong>
		              	<?= strtoupper(MARCA_PATENTE) ;?>
		              </strong>
		           </a>. 
		           <br>
		           All rights reserved. Versión <?= VERSION ?>          
		        </span>
			</footer>
        </div>
    </section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/node_modules/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/bootstrap/js/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
</body>
