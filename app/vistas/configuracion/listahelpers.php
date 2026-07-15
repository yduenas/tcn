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

  #body {
    margin: 0 15px 0 15px;
    min-height: 96px;
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

  .btn{
    color:#FFFFFF;
    background-color: #10599E;
    border-color: none;
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
<div class="container">
  <h1>
      <a href="<?= RUTA_URL; ?>">
        <img alt="Complement" src="<?= RUTA_URL; ?>public/img/COMPLEMENT_framework.jpg"/>
      </a>
  </h1>

  <h2>Lista de Helpers</h2>

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Helper</th>
      <th scope="col">Observacion</th>
      <th scope="col">Accion</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $num = 1;
     // var_dump($datos);die();
      foreach ($datos['helpers'] as $helper) :
        if($helper != '__construct' && $helper != 'modelo' && $helper != 'vista' && $helper != 'migracionEjecutar' && $helper != 'composer\autoload\includefile'){
      //    var_dump($helper);die();
    ?>
        <tr>
          <th scope="row"><?= $num; ?></th>
          <td><?= $helper ?> ( )</td>
          <td>
            <?php 
                $help = buscarJSON($helper , 'app/helpers/helpers.json');
               // var_dump($help);
                if(isset($help['descripcion'])){ 
                  echo $help['descripcion'];
                }else{
                  echo 'No existe descripcion';
                }
                //  echo $help['descripcion'];                               
            ?>            
          </td>
          <td> 
            <!--
            <a class="btn btn-primary btn-lg btn-block" href="<?= RUTA_URL?>configuraciones/<?= $helper ?>">ir</a>
            -->
          </td>
        </tr>

    <?php
        $num ++;
        }
       endforeach ;
    ?>
    
  </tbody>
</table>

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