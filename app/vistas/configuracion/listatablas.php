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

  <h2>Lista de Tablas [Base de datos - Tabla]</h2>

  <h3 class="p-3 mb-2 bg-success text-white"><?= 'La base conectada es: ' . $datos['baseDatos']->SCHEMA_NAME; ?></h3>

  <?php
    foreach ($datos['tablas'] as $tabla) :

      echo '<h3 class="p-3 mb-2 bg-info text-white">'.$tabla->TABLE_SCHEMA.' - '.$tabla->TABLE_NAME.'</h3>';
  ?>

  <table class="table">
    <thead>
      <tr class="table-primary">
        <th scope="col">#</th>
        <th scope="col">Field</th>
        <th scope="col">Type</th>
        <th scope="col">Null</th>
        <th scope="col">Key</th>
        <th scope="col">Default</th>
        <th scope="col">Extra</th>
      </tr>
    </thead>
    <tbody>
  <?php

      $campos = $this->estructuraModelo->mostrarCamposTablas($tabla->TABLE_NAME);
        //var_dump($campos);die();
      foreach ($campos as $key => $campo) :
        echo '<tr>';
        echo '<td>'.$key.'</td>';
        echo '<td>'.$campo->Field.'</td>';
        echo '<td>'.$campo->Type.'</td>';
        echo '<td>'.$campo->Null.'</td>';
        echo '<td>'.$campo->Key.'</td>';
        echo '<td>'.$campo->Default.'</td>';
        echo '<td>'.$campo->Extra.'</td>';
        echo '</tr>';
        
      endforeach;
  ?>
    </tbody>
  </table>
  <br>
  <div style="height:35px; width:100%; border-width:0; color:#FFFFFF; background-color:#10599E">
    &nbsp&nbsp&nbsp&nbsp Tabla - 
  <?= $tabla->TABLE_SCHEMA.' - '.$tabla->TABLE_NAME; ?>
  </div>

  <br>

  <?php
    endforeach;
  ?>

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


	