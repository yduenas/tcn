<?php 
//session_start();
//if(!isset($_SESSION['LoggedIn'])) {header("Location: ../../../../../index.php");}
/*	define('RAIZ',$_SERVER['DOCUMENT_ROOT']);	*/
//include("../../../includes/conectar_bd2.php");
/*	colocar query2s	*/
//include("../../assets/includes/funciones.php");
//include(RUTA_APP2."/assets/includes/configurar.php");

//require_once("../../assets/includes/configurar.php");
//include(RUTA_APP2."/assets/includes/conectar_bd3.php");
//include("../../assets/includes/conectar_bd3.php");

//include(RUTA_APP2."/modulos/boletas/funciones_boleta.php");

include(RUTA_APP2."/modulos/boletas/funciones_boleta.php");
//include($_SERVER['DOCUMENT_ROOT']."/hrc6/modulos/boletas/funciones_boleta.php");
//include("../../modulos/boletas/funciones_boleta.php");

/*  
include($_SERVER['DOCUMENT_ROOT']."/hrc/assets/includes/conectar_bd2.php");
include($_SERVER['DOCUMENT_ROOT']."/hrc/modulos/boletas/funciones_boleta.php");


              define ('RUTA_APP', dirname(dirname(__FILE__)));
              define ('RUTA_URL', 'http://localhost/hrc'); 
              define ('NOMBRESITIO', 'HRC - HUMAN RESOURCE COMPLEMENT');
*/
?>

<link href='../../../assets/css/boleta.css' rel='stylesheet'>

<?php 
//<page>
//     $_GET['CO_TRAB'] = $CO_TRAB;
//     $_GET['NU_ANNO'] = $NU_ANNO;
//     $_GET['NU_PERI'] = $NU_PERI; 
//     $_GET["saludo"]

/*   
    $resultado2=vBoleta2($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                  while($fila = odbc_fetch_array($resultado2))
                            {
                              echo '<tr>';
                              echo '<td>'.$fila['PE_VACA'].'</td>';
                              echo '<td>'.$fila['FE_INIC_VACA'].'</td>';
                              echo '<td>'.$fila['FE_FINA_VACA'].'</td>';
                              echo '<td>'.$fila['NU_DIAS'].'</td>';
                              echo '<td>'.$fila['ORDEN'].'</td>';
                              echo '</tr><br>';
                              }   
  */
/*
                        
  $resultado3=vBoleta3($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                        while($fila = odbc_fetch_array($resultado3))
                            {
                              echo $fila['DE_CPTO'].'<br>';
                             // echo number_format($fila['DE_CPTO'],0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
                        
*/

$resultado=vBoleta1($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);


//echo $CO_TRAB; //,$NU_ANNO,$NU_PERI;
//$resultado=vBoleta1('25793692',2020,2);

                           

                        //while($fila = odbc_fetch_array($resultado))
                           while($fila = $resultado->fetch() )
                            {
                              $DE_NOMB = $fila->DE_NOMB;
                              $DE_DIRE = $fila->DE_DIRE;
                              $NU_RUCS = $fila->NU_RUCS;
                              $NO_REPR_LEGA = $fila->NO_REPR_LEGA;
                              $TI_DOID_REPR = $fila->TI_DOID_REPR;
                              $NU_DOID_REPR = $fila->NU_DOID_REPR; 

                              $CO_TRAB = $fila->CO_TRAB; 
                              $NYA = $fila->NOMBRES_Y_APELLIDOS;
                              $DE_CALI_TRAB = $fila->DE_CALI_TRAB;
                              $FE_INGR_PLAN =  date("d/m/Y", strtotime($fila->FE_INGR_PLAN));

//    if(!empty($var) && strlen($var) >3){ echo "exito"; }else{ echo "fallo";  }
                              if(!empty($fila->FE_CESE_TRAB)){
                                  $FE_CESE_TRAB = date("d/m/Y", strtotime($fila->FE_CESE_TRAB));
                                }else{
                                  $FE_CESE_TRAB = "- - -";
                                }

 //       $FE_CESE_TRAB = date("d/m/Y", strtotime($fila['FE_CESE_TRAB']));

                              $NU_SUEL_PERI = $fila->NU_SUEL_PERI;
                              // $DTRAB = $fila['DTRAB'];
                               if(!empty($fila->DTRAB)){
                                  $DTRAB = $fila->DTRAB;
                                }else{
                                  $DTRAB = 0;
                                }

                              // $DSUBS = $fila['DSUBS'];
                               if(!empty($fila->DSUBS)){
                                  $DSUBS = $fila->DSUBS;
                                }else{
                                  $DSUBS = 0;
                                }

                                //$HTRABA = $fila['HTRABA']
                                if(!empty($fila->HTRABA)){
                                  $HTRABA = $fila->HTRABA;
                                }else{
                                  $HTRABA = 0;
                                }
                                //$HNHE25 = $fila['HNHE25']
                                if(!empty($fila->HNHE25)){
                                  $HNHE25 = $fila->HNHE25;
                                }else{
                                  $HNHE25 = 0;
                                }
                                //$HNHE35 = $fila['HNHE35']
                                if(!empty($fila->HNHE35)){
                                  $HNHE35 = $fila->HNHE35;
                                }else{
                                  $HNHE35 = 0;
                                }
                                //$HNHEDO = $fila['HNHEDO'] 
                                if(!empty($fila->HNHEDO)){
                                  $HNHEDO = $fila->HNHEDO;
                                }else{
                                  $HNHEDO = 0;
                                }      
                             



                              $TI_DOCU_IDEN = $fila->TI_DOCU_IDEN;
                              $NU_DOCU_IDEN = $fila->NU_DOCU_IDEN;
                              $NUME_AFPS    = $fila->NUME_AFPS;
                              $CO_AFPS      = $fila->CO_AFPS;
                              $DE_SEDE      = $fila->DE_SEDE; 
                              $CO_BANC_SUEL = $fila->CO_BANC_SUEL;
                              $NU_CNTA_SUEL = $fila->NU_CNTA_SUEL;
                              $CO_CENT_COST = $fila->CO_CENT_COST;
                              $DE_CENT_COST = $fila->DE_CENT_COST;
                              $DE_AREA      = $fila->DE_AREA;
                              $DE_PUES_TRAB = $fila->DE_PUES_TRAB;
                              $DE_CATE_TRAB = $fila->DE_CATE_TRAB;


                              
                              /*
                              echo '<tr>';
                              echo '<td>'.$fila['DE_NOMB'].'</td>';
                              echo '<td>'.$fila['CO_TRAB'].'</td>';
                                //  NO_APEL_PATE utf8_encode($fila['NO_APEL_PATE'])&
                              echo '<td>'.utf8_encode($fila['NOMBRES_Y_APELLIDOS']).'</td>';
                              echo '<td>'.$fila['NU_ANNO'].'</td>';
                              echo '<td>'.$fila['NU_PERI'].'</td>';
                              echo '<td>'.$fila['DE_PUES_TRAB'].'</td>';
                            //  echo '<td>'.$fila['NU_TELE_EMPR'].'</td>';
                             // echo '<td>'.$fila['NO_DIRE_MAI2'].'</td>';
                              echo '<td>'.$DE_NOMB.'</td>';
                              echo '</tr><br>';
                             //     $_SESSION['CO_TRAB'] = $fila['CO_TRAB'];
                             */
                            }
 //</page>
/*
              <head>
              <base href="<?php echo RUTA_URL?>/">
              <title><?php echo NOMBRESITIO; ?></title>
              <link rel="shortcut icon" href="assets/img/favicon.ico">
              </head>
*/
 ?>
             

<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
    <page_footer>
        <table >
            <tr>
                <td style="width: 50%; text-align: left">
                </td>
            </tr>
        </table>
    </page_footer>

    <table cellspacing="0" style="width: 100%;">
        <tr>
  
            <td style="width: 50%; color: black;font-size:12px;text-align:left">
                <span style="color: black;font-size:14px;font-weight:bold"><?php echo $DE_NOMB?> </span><br><?php echo 'DIRECCION: '.$DE_DIRE ?><br><?php echo 'RUC: '.$NU_RUCS?><br>
            </td>
			      <td style="width: 25%; color: #444444;"> 
              <?php  ?>
            </td>
      			<td style="width: 25%;text-align:right"> 

              
              
              <img <?php 

              //echo 'src="'.RUTA_URL.'/assets/img/tulogoaqui.png"' 
             // echo 'src="assets/img/tulogoaqui.png"' 
             // echo 'src=http://localhost/hrc/assets/img/tulogoaqui.png' 
             // echo 'src='.$_SERVER['DOCUMENT_ROOT'].'/hrc/assets/img/tulogoaqui.png' 
               echo 'src='.RUTA_APP2.'/assets/img/tulogoaqui.png' 


              ?> style='max-width: 70px; height: 70px; float: left;'  />
      				 
      			</td>
        </tr>
    </table>

    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 11pt;">
      <tr>
        <td style="width:25%;" ><b>Año:</b> 
        <?php 
            $CO_TRAB = $_GET['CO_TRAB'];
            $NU_ANNO = $_GET['NU_ANNO'];
            $NU_PERI = $_GET['NU_PERI'];
            echo $NU_ANNO ?><br><b>Periodo:</b> <?php echo $NU_PERI 
        ?>
        </td>
        <td style="width:50%;" >
        </td>
        <td style="width:25%;" >
        </td>
      </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 7.7pt; vertical-align: middle" >
        <tr>
          <th style="width:8%; height: 2%; border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CODIGO</th>
          <th style="width:45%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">APELLIDOS Y NOMBRES</th>
          <th style="width:15%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CALIFICACION</th>
          <th style="width:13%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">FECHA ING.</th>
          <th style="width:13%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">FECHA CESE</th>
          <th style="width:9%;height: 2%;border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">
          SUELDO</th>
        </tr>
        <tr>
          <td style="width:8%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $CO_TRAB ?></td>
          <td style="width:45%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $NYA; ?></td>
          <td style="width:15%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $DE_CALI_TRAB  ?></td>
          <td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $FE_INGR_PLAN  ?></td>
          <td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $FE_CESE_TRAB ?></td>
          <td style="width:9%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php echo number_format($NU_SUEL_PERI,2); ?></td>
        </tr>
    </table>

     <table cellspacing="0" style="width: 100%; text-align: center; font-size: 7.7pt;">
       <tr>
        <th style="width:18%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DOC. IDEN.</th>
        <th style="width:27%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">REGIMEN PENSIONARIO</th>
        <th style="width:28%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">SEDE</th>
        <th style="width:30%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">
          CUENTA BANCARIA</th>
       </tr>
       <tr>
         <td style="width:18%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $TI_DOCU_IDEN.' - '.$NU_DOCU_IDEN ?></td>
         <td style="width:27%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $CO_AFPS.'-'.$NUME_AFPS ?></td>
         <td style="width:28%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $DE_SEDE ?></td>
         <td style="width:30%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php echo $CO_BANC_SUEL.'-'.$NU_CNTA_SUEL ?></td>
       </tr>
     </table>

     <table cellspacing="0" style="width: 100%; text-align: center; font-size: 7.7pt;">
       <tr>
        <th style="width:34%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CENTRO DE COSTO (PRINCIPAL)</th>
        <th style="width:25%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">AREA</th>
        <th style="width:27%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">CARGO</th>
        <th style="width:17%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">
          CATEGORIA</th>
       </tr>
       <tr>
        <td style="width:34%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $DE_CENT_COST.'-'.$CO_CENT_COST ?></td>
        <td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $DE_AREA ?></td>
        <td style="width:27%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $DE_PUES_TRAB ?></td>
        <td style="width:17%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php echo $DE_CATE_TRAB ?></td>
       </tr>
     </table>

    <table cellspacing="0" style="width: 100%; text-align: center; ">
      <tr>
        <th style="width:15%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DIAS SUBSIDIADOS</th>
        <th style="width:20%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DIAS NO TRAB./NO SUBS.</th>
        <th style="width:10%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DIAS TRAB.</th>
        <th style="width:11%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">HORAS TRAB</th>
        <th style="width:17%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">HRS. EXT. SIMP. 25%</th>
        <th style="width:17%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">HRS. EXT. SIMP. 35%</th>
        <th style="width:13%; font-size: 7pt; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">HRS. EXT. DOBLES</th>
      </tr>

      <tr>
        <td style="font-size: 8pt; width:15%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo number_format($DSUBS,0) ?></td>
        <td style="font-size: 8pt; width:20%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo 15-$DSUBS-$DTRAB ?></td>
        <td style="font-size: 8pt; width:10%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo number_format($DTRAB,0) ?></td>
        <td style="font-size: 8pt; width:11%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo number_format($HTRABA,0) ?></td>
        <td style="font-size: 8pt; width:17%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $HNHE25 ?></td>
        <td style="font-size: 8pt; width:17%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php echo $HNHE35 ?></td>
        <td style="font-size: 8pt; width:13%;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000"><?php echo $HNHEDO ?></td>
          
        </tr>
      </table>

    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 8pt;">
      <tr>
        <th style="width:25%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">PERIODO VACACIONAL</th>
        <th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">INICIO VAC.</th>
        <th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">FIN DE VACAC.</th>
        <th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000">DIAS VAC.</th>
        <th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000"> --- </th>
        <th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000"> --- </th>
        <th style="width:13%; height: 2%;border-top: 0px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">--- </th>
      </tr>

      <tr>        
        <td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">
          <?php 
            $resultado2=vBoleta2($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                        while($fila = $resultado2->fetch() )
                  //      while($fila = odbc_fetch_array($resultado2))
                            {
                              echo $fila->PE_VACA.'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
        ?>          
        </td>
        <td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">
          <?php 
            $resultado2=vBoleta2($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                        while($fila = $resultado2->fetch() )
                   //     while($fila = odbc_fetch_array($resultado2))
                            {
                              echo date("d/m/Y", strtotime($fila->FE_INIC_VACA)).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
        ?>  
        </td>
        <td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">
          <?php 
            $resultado2=vBoleta2($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                         while($fila = $resultado2->fetch() )
                   //  while($fila = odbc_fetch_array($resultado2))
                            {
                              echo date("d/m/Y", strtotime($fila->FE_FINA_VACA)).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
        ?>  
        </td>
        <td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">
          <?php 
            $resultado2=vBoleta2($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                         while($fila = $resultado2->fetch() )
                   //  while($fila = odbc_fetch_array($resultado2))
                            {
                              echo number_format($fila->NU_DIAS,0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
        ?> 
        </td>
        <td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000"><?php //echo $fin_vac2 ?>
                    <?php 
            $resultado2=vBoleta2($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                         while($fila = $resultado2->fetch() )
                   //  while($fila = odbc_fetch_array($resultado2))
                            {
                              echo number_format($fila->NU_DIAS,0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
        ?> 

        </td>
        <td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">&nbsp;</td>
        <td style="width:13%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000">&nbsp;</td>         
      </tr>
   </table>

  <br><br> <div style="padding-top: -25px; width:100%">

   <table cellspacing="0" style="width: 100%; text-align: center; font-size: 8pt; ">
      <tr>
              <th style="width:33.33333333%;">INGRESOS</th>
              <th style="width:33.33333333%;">DEDUCCIONES</th>
              <th style="width:33.33333333%;">APORTACIONES DEL EMPLEADOR</th>
      </tr>
   </table>   </div> <br>

   <div style="padding-top: -25px; width:100% ;">
   <table cellspacing="0" style="width: 100%; font-size: 8pt; ">
      <tr>
              <td style="width:33.33333333%;">
                    <table cellspacing="0" style="width: 100%; ">
                        <tr>
                        <td style="padding-left: 3px; font-size: 8pt;" align="left">

                        <?php 
            $resultado3=vBoleta3($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                         while($fila = $resultado3->fetch() )
                   //  while($fila = odbc_fetch_array($resultado3))
                            { 
                              echo $fila->DE_CPTO.'<br>';
                             // echo number_format($fila['DE_CPTO'],0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
                        ?> 

                        </td>
                        
                        <td style="padding-left: 3px; font-size: 8pt;" align="right">

                        <?php 
            $resultado3=vBoleta3($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                         while($fila = $resultado3->fetch() )
                   //  while($fila = odbc_fetch_array($resultado3))
                            { 
                              echo number_format($fila->NU_DATO_CALC,2).'<br>';
                             // echo number_format($fila['DE_CPTO'],0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
                        ?> 

                        </td>
                        <td style="padding-left: 45px; font-size: 8pt;" align="right"><?php //echo $numingre ?></td>
                      </tr>
                      
                    </table>
                 </td>
              <td style="width:33.33333333%;">
                <table cellspacing="0" style="width: 100%;">
                      
                      <tr>
                        <td style="padding-right: : 70px; font-size: 8pt;" align="left">
                        <?php 
            $resultado4=vBoleta4($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                         while($fila = $resultado4->fetch() )
                   //  while($fila = odbc_fetch_array($resultado4))
                            { 
                              echo $fila->DE_CPTO.'<br>';
                             // echo number_format($fila['DE_CPTO'],0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
                        ?> 

                        </td>
                        
                        <td style="padding-left: 45px; font-size: 8pt;" align="right">

                        <?php 
            $resultado4=vBoleta4($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                         while($fila = $resultado4->fetch() )
                   //  while($fila = odbc_fetch_array($resultado4))
                            { 
                              echo number_format($fila->NU_DATO_CALC,2).'<br>';
                             // echo number_format($fila['DE_CPTO'],0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
                        ?> 


                        </td>
                      </tr>
                      
                    </table>
              </td>
              <td style="width:33.33333333%;">
                <table>

                      <tr>
                        <td style="padding-right: : 70px; font-size: 8pt;" align="left">

                        <?php 
            $resultado5=vBoleta5($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                         while($fila = $resultado5->fetch() )
                   //  while($fila = odbc_fetch_array($resultado5))
                            { 
                              echo $fila->DE_CPTO.'<br>';
                             // echo number_format($fila['DE_CPTO'],0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
                        ?> 

                        </td>
                        
                        <td style="padding-left: 80px; font-size: 8pt;" align="right">
                        <?php 
            $resultado5=vBoleta5($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                         while($fila = $resultado5->fetch() )
                   //  while($fila = odbc_fetch_array($resultado5))
                            { 
                              echo number_format($fila->NU_DATO_CALC,2).'<br>';
                             // echo number_format($fila['DE_CPTO'],0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             } 
                        ?> 
                        </td>
                      </tr>
                  
                    </table>
              </td>
              
              </tr>
             
          </table>
        </div>

       <br><br><br><br><br><br>

              <table cellspacing="0" style="width: 95%; text-align: center; font-size: 8pt;">
      
             <tr>
                <td style="width:20%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left">TOTAL INGRESOS</td>
            <td style="width:1%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="left">&nbsp;</td>
            <td style="width:1%; border-top: 1px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000 " align="left">&nbsp;</td>
            <td style="width:8%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="right"><?php //echo $sumaingre ?>
              
            <?php 
            $resultado3=vBoleta3($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                        $total3 = 0; // total declarado antes del bucle
                         while($fila = $resultado3->fetch() )
                   //  while($fila = odbc_fetch_array($resultado3))
                            { 
                           //   echo number_format($fila['NU_DATO_CALC'],2).'<br>';
                             // echo number_format($fila['DE_CPTO'],0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             $total3 = $total3 + $fila->NU_DATO_CALC; // Sumar variable $total + resultado de la consulta
                            }
                            echo number_format($total3,2); // Se imprime $total y se realiza la suma 
                        ?> 


            </td>

          <td style="width:20%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left">TOTAL DESCUENTOS</td>
            <td style="width:2%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="left">&nbsp;</td>
            <td style="width:3%; border-top: 1px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-left: 1px solid #000000 " align="left">&nbsp;</td>
            <td style="width:7%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="right"><?php //echo $sumadedu ?>
                          <?php 
            $resultado4=vBoleta4($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                        $total4 = 0; // total declarado antes del bucle
                         while($fila = $resultado4->fetch() )
                   //  while($fila = odbc_fetch_array($resultado4))
                            { 
                           //   echo number_format($fila['NU_DATO_CALC'],2).'<br>';
                             // echo number_format($fila['DE_CPTO'],0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             $total4 = $total4 + $fila->NU_DATO_CALC; // Sumar variable $total + resultado de la consulta
                            }
                            echo number_format($total4,2); // Se imprime $total y se realiza la suma 
                        ?> 

            </td>

            <td style="width:20%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left">TOTAL APORTACION</td>
            <td style="width:2%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; " align="left">&nbsp;</td>
            <td style="width:5%; border-top: 1px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000 " align="left">&nbsp;</td>
            <td style="width:7%; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="right"><?php //echo $sumaapor ?>
            <?php 
            $resultado5=vBoleta5($_GET['CO_TRAB'],$_GET['NU_ANNO'] ,$_GET['NU_PERI']);
                        $total5 = 0; // total declarado antes del bucle
                         while($fila = $resultado5->fetch() )
                   //  while($fila = odbc_fetch_array($resultado5))
                            { 
                           //   echo number_format($fila['NU_DATO_CALC'],2).'<br>';
                             // echo number_format($fila['DE_CPTO'],0).'<br>';
                             // echo '<tr>';
                             // echo '<td style="width:25%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000">'.$fila['PE_VACA'].'</td>';
                             // echo '</tr>';
                             $total5 = $total5 + $fila->NU_DATO_CALC; // Sumar variable $total + resultado de la consulta
                            }
                            echo number_format($total5,2); // Se imprime $total y se realiza la suma 
                        ?> 
            </td>
            
          </tr>

          <tr>
              <td style="width:20%; " align="left">&nbsp;</td>
              <td style="width:5%; " align="left">&nbsp;</td>
              <td style="width:2%; " align="left">&nbsp;</td>
              <td style="width:5%; " align="right">&nbsp;</td>

              <td style="width:20%; " align="left">&nbsp;</td>
              <td style="width:5%; " align="left">&nbsp;</td>
              <td style="width:4%;" align="left">&nbsp;</td>
              <td style="width:5%; " align="right">&nbsp;</td>

          <td style="width:20%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left">NETO A PAGAR</td>
          <td style="width:5%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; " align="left">&nbsp;</td>
          <td style="width:6%; border-top: 0px solid #000000; border-bottom: 1px solid #000000;border-left: 1px solid #000000 " align="left">&nbsp;</td>
          <td style="width:5%; border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="right"><?php //echo $sum_total

            echo $total3-$total4; //+$total5

           ?></td>
          </tr>
      </table>
 
  <br><br><br>
    <table cellspacing="0" style="width: 102%; text-align: center; font-size: 8pt;">
     
      <tr>
          <th style="width:51%;">
          <img <?php 
      //    echo 'src='.$_SERVER['DOCUMENT_ROOT'].'/hrc/assets/img/tufirmaaqui.png' ;
          echo 'src='.RUTA_APP2.'/assets/img/tufirmaaqui.png' ;
          ?> ></th>
          <th style="width:51%;"></th>
      </tr>
      <tr>
          <th style="width:51%;">
 <!-- <img <?php 
     //     echo 'src='.$_SERVER['DOCUMENT_ROOT'].'/hrc/assets/img/firma.png' ;
            echo 'src='.RUTA_APP2.'/assets/img/firma.png';
          ?> > -->
          </th>
          <th style="width:51%;"></th>
      </tr>
      <tr>
          <th style="width:51%;">____________________________________________________</th>
          <th style="width:51%;">____________________________________________________</th>
      </tr>
      <tr>
          <th style="width:51%;"><?php echo $DE_NOMB ?></th>
          <th style="width:51%;">&nbsp;</th>
          <th style="width:51%;">&nbsp;</th>
          
      </tr>
      <tr>
          <th style="width:51%;">REPRES. LEGAL : <?php echo $NO_REPR_LEGA ?></th>
          <th style="width:51%;">RECIBI CONFORME: <?php echo $NYA ?></th>
          <th style="width:51%;">&nbsp;</th>
      </tr>
      <tr>
          <th style="width:51%;">&nbsp;</th>
          <th style="width:51%;">&nbsp;</th>
          <th style="width:51%;">&nbsp;</th>
      </tr>
      <tr>
          <th style="width:51%;"><?php echo $TI_DOID_REPR.' - '.$NU_DOID_REPR ?></th>
          <th style="width:51%;"><?php echo $TI_DOCU_IDEN.' - '.$NU_DOCU_IDEN ?></th>      
          <th style="width:51%;">&nbsp;</th>
      </tr>
    </table>

    <br><br><br><img 
    <?php 
    //echo 'src='.$_SERVER['DOCUMENT_ROOT'].'/hrc/assets/img/tijera.jpg' 
      echo 'src='.RUTA_APP2.'/assets/img/tijera.jpg' ;
    ?> 
    style="height: 15px;width: 15px">---------------------------------------------------------------------------------------------------------------------------------




</page>
