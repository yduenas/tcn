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

<?php



// var_dump($new_array); die();
?>

<div class="container">
  <h1>
    <a href="<?= RUTA_URL; ?>">
      <img alt="Complement" src="<?= RUTA_URL ?>public/img/COMPLEMENT_framework.jpg"/>
    </a>
  </h1>

  <h2>Lista de Metodos</h2>

  <?php
     if(isset($_SESSION['resultado'])) // isset significa: ¿Esta definido?
     {
          switch ($_SESSION['resultado']) {              
              
               case 'Migracion Exitosa':
              # code...
                echo '<div class="alert alert-success" role="alert">';
                echo 'Realizado con Exito la Migracion del archivo '.$_SESSION['archivo'] ;
                echo '</div>';
              break;
              
              case 'Migracion No Ejecutada':
              # code...
                echo '<div class="alert alert-danger" role="alert">';
                echo 'La Migracion no se Ejecuto' ;
                echo '</div>';
              break;

              case 'Se aprobo contrato':
              # code...
                echo '<div class="alert alert-success" role="alert">';
                echo 'Realizado con Exito' ;
                echo '</div>';
              break;
              
              case 'Wrong Username or Password':
              # code...
                echo '<div class="alert alert-danger" role="alert">';
                echo 'Usuario o contraseña incorrectos' ;
                echo '</div>';
              break;

            case 'TRIAL Vencio':
              # code...
                echo '<div class="alert alert-danger" role="alert">';
                echo 'Permisos de ingreso vencidos' ;
                echo '</div>';
              break;
            
            default:
            case 'ocurrio un error':
              # code...
                 echo '<div class="alert alert-danger" role="alert">';
                 echo 'Ocurrio un error';
                 echo '</div>';
             break;

          }
          unset($_SESSION['resultado']);//unset deshace la variable que se le d como parametro
     }
    ?>

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nombre Migracion</th>
      <th scope="col">Contenido</th>
      <th scope="col">Ejecucion</th>
      <th scope="col">Accion</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $num = 1;
      foreach ($datos['archivos'] as $archivo) :      
    ?>
    <tr>
      <th scope="row"><?= $num; ?></th>
      <td><?= $archivo; ?></td>
      <td>
      	<?php
      		$contenidos = ver_Contenido_Documento_Linea('app/migraciones/',$archivo, 2, 5);
  				foreach($contenidos as $contenido):
  				echo $contenido.'<br>';
  				endforeach;
      	?>
      </td>
      <td>
        <?php

          $migracion = filter_array($datos['migracionDBJSON'],'nombre_archivo',$archivo);
          //var_dump($migracion);die();
          foreach ($migracion  as $key => $value) {
            $migracion = $value;
          }

          if(isset($migracion['nombre_archivo'])){
            echo 'Migracion ejecutada el <br>'.$migracion['fecha'];
          }else{
            echo 'Migracion no EJECUTADA <br>';
            $migracion['nombre_archivo'] = '';
          }
       
        ?>
      </td>
      <td> 
        <?php
        if($archivo == $migracion['nombre_archivo']){
        ?>
        <a class="btn btn-danger btn-lg btn-block" href='javascript:void(0)' style="background-color: red; pointer-events: none;" disabled ='true'>Ejecutado</a>
        <?php  
        }else{
        ?>
        <a class="btn btn-primary btn-lg btn-block" href="<?= RUTA_URL?>configuraciones/migracionEjecutar/<?= base64_encode($archivo)?>">Ejecutar</a>
        <?php
        }
        ?>
      </td>
    </tr>
    <?php
        $num ++;        
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
