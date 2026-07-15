<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->

    <!-- Sidebar scroll-->
    <div >
        <!-- Sidebar navigation-->
        <nav >
            <ul >
                <li >--- PERSONAL</li>
            <?php
                $modulos = quitar_duplicados_menu($datos);                
                foreach($modulos as $co_modu) :
            ?>
                <li> 
                    <a  href="#" aria-expanded="false"><!--icon-Car-Wheel-->
                        <i class="<?php echo $co_modu['ICONO']; ?>"></i>
                        <span ><?php echo $co_modu['DE_MODULO']; ?>
                        
                        </span>
                    </a>
                        <ul aria-expanded="true" >
                        <?php
                            $modulos_sub = quitar_duplicados_menu_sub($datos);
                            foreach($modulos_sub as $co_modu_sub) :
                                if($co_modu_sub['CO_MODULO'] == $co_modu['CO_MODULO']){
                            ?>
                                <li> 
                                    <a href="<?php echo RUTA_URL.$co_modu_sub['HREF']; ?>">
                                    <?php echo $co_modu_sub['DE_MODULO_SUB']; ?>                                    
                                    </a>
                                </li>
                                
                            <?php
                                }
                            endforeach
                            
                            ?>
                        </ul>
                </li>
            <?php
            endforeach
            ?> 
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->

<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->