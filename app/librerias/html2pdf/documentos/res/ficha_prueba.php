
<?php session_start();
 header("Content-Type: text/html; charset=iso-8859-1 ");
if(!isset($_SESSION["serviciolinea"])) {header("Location: index.php");}

define('RAIZ',$_SERVER['DOCUMENT_ROOT']);

include_once(RAIZ."/servicio-online/bin/Bin.php");
include_once(RAIZ."/servicio-online/bin/Libreria/Conexion.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionOfiplan.php");
include_once(RAIZ."/servicio-online/bin/Libreria/ConexionDashboard.php");

$VDIRECTORIO_RENOVACION = Bin::Factory("Neg","VDIRECTORIO_RENOVACION");
$DAVDIRECTORIO_RENOVACION=Bin::Factory("Mod","DAVDIRECTORIO_RENOVACION");

$BASE=Bin::Factory("Lib","Base");

include ('../../numero-a-letras-master/src/NumeroALetras.php');


$conexion = odbc_connect('SELECCION','OFISIS','CQ5001'); 
if ($conexion == FALSE){ 
echo ('Error en la conexion'); 
exit(); 
}


$queryEmpresa = "SELECT 
	TM_POSTULANTE_EMPR.CO_EMPR, 
	T_PUESTOS.DE_EMPR,
	TM_POSTULANTE_POST.TI_DOCU_IDEN,
	T_TIPO_DOC_IDEN.No_Tipo_Doc_Iden,
	TM_POSTULANTE_POST.NU_DOCU_IDEN, 
	TM_POSTULANTE_POST.NO_APEL_PATE+' '+TM_POSTULANTE_POST.NO_APEL_MATE+' '+TM_POSTULANTE_POST.NO_TRAB AS POSTULANTE,
	TM_POSTULANTE_POST.NO_APEL_PATE,
	TM_POSTULANTE_POST.NO_APEL_MATE,
	TM_POSTULANTE_POST.NO_TRAB, 
	T_GENERO.No_Genero,
	CONVERT(CHAR(10), CONVERT(date, TM_POSTULANTE_POST.FE_NACI_TRAB, 112), 103) AS FECHA_NACIMIENTO,
	T_ESTADO_CIVIL.No_Estado_Civil,
	T_PUESTOS.DE_AREA,
	TM_POSTULANTE_POST.NO_DIRE_MAI1,
	T_PUESTOS.DE_PUEST_CORP,
	T_G_SANGUINEO.No_G_Sanguineo,
	TM_POSTULANTE_EMPR.NU_SUEL,
	TM_POSTULANTE_EMPR.FE_FIN_CONT,
	TM_POSTULANTE_POST.NU_TLF1, 
	TM_POSTULANTE_POST.NU_TLF2,
	TM_POSTULANTE_EMPR.CUSSP,
	CONVERT(DATE,FE_INGR_EMPR) AS FE_INGR_EMPR,
	TM_POSTULANTE_EMPR.PERI_PRUEBA,
	T_AFPS.DE_AFPS,
	T_VIAS.CO_TIPO_VIAS, 
    T_VIAS.DE_TIPO_VIAS,
    TM_POSTULANTE_POST.TIPO_PROCESO as CO_PROCESO,
    TM_POSTULANTE_DIRE.NO_DIRE_TRAB, 
    TM_POSTULANTE_DIRE.NU_CASA, 
    TM_POSTULANTE_DIRE.NU_INTE, 
    TM_POSTULANTE_DIRE.NO_DPTO, 
    TM_POSTULANTE_DIRE.NO_MANZ, 
    TM_POSTULANTE_DIRE.NO_LOTE, 
    TM_POSTULANTE_DIRE.NO_KMLT, 
    TM_POSTULANTE_DIRE.NO_BLOC, 
    TM_POSTULANTE_DIRE.NO_ETAP, 
    T_ZONA.CO_TIPO_ZONA, 
    T_ZONA.DE_TIPO_ZONA,
    TM_POSTULANTE_DIRE.NO_ZONA, 
    TM_POSTULANTE_DIRE.CO_UBIC_GEOG, 
    TM_POSTULANTE_DIRE.DE_REFE AS REFERENCIA, 
   	VSEDES_OFIPLAN.DE_REFE,
    T_UBIGEO.NO_DEPARTAMENTO,
    T_UBIGEO.NO_PROVINCIA,
    TM_POSTULANTE_POST.TI_DOCU_IDEN,
    T_UBIGEO.NO_DISTRITO,
    vTTTIPO_CASA.DE_TIPO_CASA,
	TM_POSTULANTE_DIRE.Mat_Vivienda,
	TM_POSTULANTE_POST.RUTA_FOTO,
	CASE WHEN Serv_Agua='S' THEN 'SI' ELSE 'NO' END AS AGUA,
	CASE WHEN Serv_Desague='S' THEN 'SI' ELSE 'NO' END AS DESAGUE,
	CASE WHEN Serv_Luz='S' THEN 'SI' ELSE 'NO' END AS LUZ,
	CASE WHEN Serv_Internet='S' THEN 'SI' ELSE 'NO' END AS INTERNET,
	CASE WHEN Serv_Cable='S' THEN 'SI' ELSE 'NO' END AS CABLE,

	CASE WHEN accidentes='S' THEN 'SI' ELSE 'NO' END AS ACCIDENTE,
	TM_POSTULANTE_SALU.accidentes_obs,
	CASE WHEN enfermedad='S' THEN 'SI' ELSE 'NO' END AS ENFERMEDAD,
	TM_POSTULANTE_SALU.enfermedad_obs,
	CASE WHEN operaciones='S' THEN 'SI' ELSE 'NO' END AS OPERACION,
	TM_POSTULANTE_SALU.operaciones_obs,
	CASE WHEN alergia='S' THEN 'SI' ELSE 'NO' END AS ALERGIA,
	TM_POSTULANTE_SALU.alergia_obs,
	CASE WHEN columna='S' THEN 'SI' ELSE 'NO' END AS COLUMNA,
	TM_POSTULANTE_SALU.columna_obs,
	CASE WHEN deporte='S' THEN 'SI' ELSE 'NO' END AS DEPORTE,
	TM_POSTULANTE_SALU.deporte_obs,
	CASE WHEN ginecologico='S' THEN 'SI' ELSE 'NO' END AS PROBLEMA_GINECOLOGICO,
	TM_POSTULANTE_SALU.ginecologico_obs,
	CASE WHEN Pato_ginecologico='S' THEN 'SI' ELSE 'NO' END AS PATOLOGIA_GINECOLOGICA,
	TM_POSTULANTE_SALU.Pato_ginecologico_obs,
	CASE WHEN ANTE_POLICIAL='S' THEN 'SI' ELSE 'NO' END AS ANTE_POLICIAL,
	TM_POSTULANTE_SALU.ANTE_POLICIAL_OBS,
	TM_POSTULANTE_EMPR.TIPO_AFP,
	CASE 
		WHEN T_PUESTOS.DE_PUEST_CORP LIKE'%GERENTE%' THEN 'GER' 
		WHEN T_PUESTOS.DE_PUEST_CORP LIKE'%JEFE%' THEN 'JEF' 
	ELSE 'NUE' END AS EMP

FROM TM_POSTULANTE_EMPR 

LEFT JOIN TM_POSTULANTE_POST ON TM_POSTULANTE_POST.TI_DOCU_IDEN=TM_POSTULANTE_EMPR.TI_DOCU_IDEN 
	AND TM_POSTULANTE_POST.NU_DOCU_IDEN=TM_POSTULANTE_EMPR.NU_DOCU_IDEN 
	AND TM_POSTULANTE_POST.CORR_POSTU=TM_POSTULANTE_EMPR.NU_CORR_POSTU 

LEFT JOIN TM_POSTULANTE_DIRE ON TM_POSTULANTE_DIRE.TI_DOCU_IDEN=TM_POSTULANTE_EMPR.TI_DOCU_IDEN 
	AND TM_POSTULANTE_DIRE.CO_TRAB=TM_POSTULANTE_EMPR.NU_DOCU_IDEN 
	AND TM_POSTULANTE_DIRE.CORR_POSTU=TM_POSTULANTE_EMPR.NU_CORR_POSTU 	

LEFT JOIN VSEDES_OFIPLAN ON VSEDES_OFIPLAN.CO_EMPR=TM_POSTULANTE_EMPR.CO_EMPR
	AND VSEDES_OFIPLAN.CO_DEPA=TM_POSTULANTE_EMPR.CO_DEPA
	AND VSEDES_OFIPLAN.CO_AREA=TM_POSTULANTE_EMPR.CO_AREA
	
LEFT JOIN SELECCION.DBO.T_VIAS
    ON T_VIAS.CO_TIPO_VIAS=TM_POSTULANTE_DIRE.CO_TIPO_VIAS

LEFT JOIN SELECCION.DBO.T_ZONA
    ON T_ZONA.CO_TIPO_ZONA=TM_POSTULANTE_DIRE.CO_TIPO_ZONA

LEFT JOIN SELECCION.DBO.T_UBIGEO
    ON T_UBIGEO.ID_UBIGEO=TM_POSTULANTE_DIRE.CO_UBIC_GEOG

LEFT JOIN T_TIPO_DOC_IDEN ON T_TIPO_DOC_IDEN.Id_Tipo_Doc_Iden=TM_POSTULANTE_EMPR.TI_DOCU_IDEN

LEFT JOIN T_PUESTOS ON T_PUESTOS.CO_EMPR=TM_POSTULANTE_EMPR.CO_EMPR
	AND T_PUESTOS.CO_DEPA=TM_POSTULANTE_EMPR.CO_DEPA
	AND T_PUESTOS.CO_AREA=TM_POSTULANTE_EMPR.CO_AREA
	AND T_PUESTOS.CO_SECC=TM_POSTULANTE_EMPR.CO_SECC
	AND T_PUESTOS.CO_PUES_CORP=TM_POSTULANTE_EMPR.CO_PUES_TRAB

LEFT JOIN T_GENERO ON T_GENERO.Id_Genero=TM_POSTULANTE_POST.ST_SEXO

LEFT JOIN vTM_PUBLICACIONES2 ON vTM_PUBLICACIONES2.CO_PUES_CORP=TM_POSTULANTE_EMPR.CO_PUES_TRAB

LEFT JOIN T_ESTADO_CIVIL ON T_ESTADO_CIVIL.Id_Estado_Civil=TM_POSTULANTE_POST.CO_ESTA_CIVI

LEFT JOIN T_AFPS ON T_AFPS.CO_AFPS=TM_POSTULANTE_EMPR.TIPO_AFP

LEFT JOIN TM_POSTULANTE_SALU ON TM_POSTULANTE_SALU.TI_DOCU_IDEN=TM_POSTULANTE_POST.TI_DOCU_IDEN
	AND TM_POSTULANTE_SALU.NU_DOCU_IDEN=TM_POSTULANTE_POST.NU_DOCU_IDEN
	AND TM_POSTULANTE_SALU.NU_CORR_POSTU=TM_POSTULANTE_POST.CORR_POSTU
	
LEFT JOIN T_G_SANGUINEO ON TM_POSTULANTE_SALU.NO_GRUP_SANG=T_G_SANGUINEO.Id_G_Sanguineo

LEFT JOIN vTTTIPO_CASA ON vTTTIPO_CASA.CO_TIPO_CASA=TM_POSTULANTE_DIRE.CO_TIPO_CASA

  WHERE TM_POSTULANTE_EMPR.NU_DOCU_IDEN LIKE'%".$dni."%'
    AND TM_POSTULANTE_EMPR.NU_CORR_POSTU='".$corre."'
 ";


$resultEmpresa = odbc_exec($conexion,$queryEmpresa);
//echo $queryEmpresa;
while($registroEmpresa=odbc_fetch_array($resultEmpresa)) {
    $co_emp=$registroEmpresa['CO_EMPR'];
    $des_emp=$registroEmpresa['DE_EMPR'];
    $dni_postulante=$registroEmpresa["NU_DOCU_IDEN"];
    $tipo_documento=$registroEmpresa["No_Tipo_Doc_Iden"];
    $codigo_tipo_documento=$registroEmpresa["TI_DOCU_IDEN"];
	$nom_postu=utf8_encode($registroEmpresa["POSTULANTE"]);
	$area_postu=utf8_encode($registroEmpresa["DE_AREA"]);
	$puesto_postu=utf8_encode($registroEmpresa["DE_PUEST_CORP"]);
	$fec_ingreso_postu=utf8_encode($registroEmpresa["FE_INGR_EMPR"]);
	$fec_fin_postu=utf8_encode($registroEmpresa["FE_FIN_CONT"]);
	$direccion_tienda_postu=utf8_encode($registroEmpresa["DE_REFE"]);
	$salario_postu=($registroEmpresa["NU_SUEL"]);
	$correo_postu=($registroEmpresa["NO_DIRE_MAI1"]);
	$proceso_postu=($registroEmpresa["CO_PROCESO"]);
	$apellido_paterno_postu=utf8_encode($registroEmpresa["NO_APEL_PATE"]);
	$apellido_materno_postu=utf8_encode($registroEmpresa["NO_APEL_MATE"]);
	$nombre_completo_postu=utf8_encode($registroEmpresa["NO_TRAB"]);
	$numero_cussp_postu=utf8_encode($registroEmpresa["CUSSP"]);
	$celular_postu=utf8_encode($registroEmpresa["NU_TLF1"]);
	$fijo_postu=utf8_encode($registroEmpresa["NU_TLF2"]);
	$grupo_sanguineo_postu=utf8_encode($registroEmpresa["No_G_Sanguineo"]);
	$periodo_prueba=($registroEmpresa["PERI_PRUEBA"]);
	$foto_postulante=$registroEmpresa['RUTA_FOTO'];

	$afp_postu=utf8_encode($registroEmpresa["DE_AFPS"]);

	$nombre_departamento_postu=utf8_encode($registroEmpresa["NO_DEPARTAMENTO"]);
	$nombre_provincia_postu=utf8_encode($registroEmpresa["NO_PROVINCIA"]);
	$nombre_distrito_postu=utf8_encode($registroEmpresa["NO_DISTRITO"]);

	$genero_postu=($registroEmpresa["No_Genero"]);
	$fecha_nacimiento_postu=($registroEmpresa["FECHA_NACIMIENTO"]);
	
	$estado_civil_postu=($registroEmpresa["No_Estado_Civil"]);

	$tipo_casa_postu=($registroEmpresa["DE_TIPO_CASA"]);
	$material_vivienda_postu=($registroEmpresa["Mat_Vivienda"]);
	$agua_postu=($registroEmpresa["AGUA"]);
	$desague_postu=($registroEmpresa["DESAGUE"]);
	$luz_postu=($registroEmpresa["LUZ"]);
	$internet_postu=($registroEmpresa["INTERNET"]);
	$cable_postu=($registroEmpresa["CABLE"]);

	$accidente_postu=($registroEmpresa["ACCIDENTE"]);
	$observacion_accidente_postu=utf8_encode($registroEmpresa["accidentes_obs"]);
	$enfermedad_postu=($registroEmpresa["ENFERMEDAD"]);
	$observacion_enfermedad_postu=utf8_encode($registroEmpresa["enfermedad_obs"]);
	$operacion_postu=($registroEmpresa["OPERACION"]);
	$observacion_operacion_postu=utf8_encode($registroEmpresa["operaciones_obs"]);
	$alergia_postu=($registroEmpresa["ALERGIA"]);
	$observacion_alergia_postu=utf8_encode($registroEmpresa["alergia_obs"]);
	$columna_postu=($registroEmpresa["COLUMNA"]);
	$observacion_columna_postu=utf8_encode($registroEmpresa["columna_obs"]);
	$deporte_postu=($registroEmpresa["DEPORTE"]);
	$observacion_deporte_postu=utf8_encode($registroEmpresa["deporte_obs"]);
	$ginecologico_postu=($registroEmpresa["PROBLEMA_GINECOLOGICO"]);
	$observacion_ginecologico_postu=utf8_encode($registroEmpresa["ginecologico_obs"]);
	$patologia_ginecologica_postu=($registroEmpresa["PATOLOGIA_GINECOLOGICA"]);
	$observacion_patologia_ginecologica_postu=utf8_encode($registroEmpresa["Pato_ginecologico_obs"]);

	$antecedente_policial_postu=utf8_encode($registroEmpresa["ANTE_POLICIAL"]);
	$observacion_antecedente_policial_postu=utf8_encode($registroEmpresa["ANTE_POLICIAL_OBS"]);

	$numero_casa=$registroEmpresa['NU_CASA'];
	$tipo_afp=$registroEmpresa['TIPO_AFP'];

	$tipo_contrato_trabajo=$registroEmpresa['EMP'];

	
	$salario_letras_postu = NumeroALetras::convertir($salario_postu, 'CON 00/100 Soles');

	if ((trim($registroEmpresa['DE_TIPO_VIAS'])=='') OR (trim($registroEmpresa['DE_TIPO_VIAS'])=='NO APLICABLE')OR (trim($registroEmpresa['DE_TIPO_VIAS'])=='OTROS')) {
    $des_via='';
  }else{
    $des_via=' '.trim($registroEmpresa['DE_TIPO_VIAS']).' ';
  }


  if (trim($registroEmpresa['NO_DIRE_TRAB'])=='') {
    $num_via='';
  }elseif (trim($registroEmpresa['NO_DIRE_TRAB'])!='' AND $registroEmpresa['NU_CASA']!='') {

    $num_via=' '.utf8_encode(trim($registroEmpresa['NO_DIRE_TRAB'])).' ';
  }else{
    $num_via=' '.trim($registroEmpresa['NO_DIRE_TRAB']).' ';
  }

  if (trim($registroEmpresa['NO_ZONA'])=='') {
    $nom_zona='';
  }else{
    $nom_zona=' '.utf8_encode(trim($registroEmpresa['NO_ZONA'])).' ';
  }

  if ($registroEmpresa['NO_DPTO']=='') {
    $depa='';
  }else{
    $depa=' Dpto.'.$registroEmpresa['NO_DPTO'].' ';
  }


  if ($registroEmpresa['NO_MANZ']=='') {
    $mz='';
  }else{
    $mz=' MZ.'.$registroEmpresa['NO_MANZ'].' ';
  }


  if ($registroEmpresa['NO_LOTE']=='') {
    $lote='';
  }else{
    $lote=' LT.'.$registroEmpresa['NO_LOTE'].' ';
  }

  if ($registroEmpresa['NO_KMLT']=='') {
    $km='';
  }else{
    $km=' KM.'.$registroEmpresa['NO_KMLT'].' ';
  }


  if ($registroEmpresa['NU_INTE']=='') {
    $nro_interior='';
  }else{
    $nro_interior=' Interior '.utf8_encode($registroEmpresa['NU_INTE']).' ';
  }


  if ($registroEmpresa['NO_ETAP']=='') {
    $nro_etapa='';
  }else{
    $nro_etapa=' Etapa. '.$registroEmpresa['NO_ETAP'].' ';
  }


  if ($registroEmpresa['NO_BLOC']=='') {
    $nro_bloque='';
  }else{
    $nro_bloque=' Bloque. '.$registroEmpresa['NO_BLOC'].' ';
  }


  if (trim($registroEmpresa['DE_TIPO_ZONA'])=='' OR trim($registroEmpresa['DE_TIPO_ZONA'])=='NO APLICABLE' ) {
    $des_zona='';
  }else{
    $des_zona=' '.trim($registroEmpresa['DE_TIPO_ZONA']);
  }

  //$direccion_postu=$des_via.''.$num_via.''.$numero_casa.''.$nro_interior.''.$depa.''.$km.''.$mz.''.$lote.''.$nro_etapa.''.$nro_bloque.''.$des_zona.''.$nom_zona;

$direccion_postu=utf8_encode($registroEmpresa['REFERENCIA']);

$distrito_postu='Distrito de '.$registroEmpresa['NO_DISTRITO'];
$provincia_postu='Provincia de '.$registroEmpresa['NO_PROVINCIA'];
    
}

if ($co_emp=='01') {
  	$razon_social='TAI LOY S.A. </span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20100049181';
}elseif ($co_emp=='02') {
  	$razon_social='COMERCIAL LUCIANO AREQUIPA S.A.C. </span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20555146583';
}elseif ($co_emp=='03') {
  	$razon_social='COPY VENTAS S.R.L. </span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20132051322';
}elseif($co_emp=='04'){
  	$razon_social='SUPLACORP S.A.C. </span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20465062356';
}elseif($co_emp=='05'){
  	$razon_social='INMOBILIARIA CASCAJAL S.A.C.</span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20538004830';
}else{
	$razon_social='LIBRERIA BAZAR SANTA MARIA E.I.R.L.</span><br> CAL. REAL 307 <br> RUC 20208752759';
}

if ($co_emp=='01') {
  $empresa_ruc='TAI LOY S.A. con RUC N° 20100049181, (en adelante LA EMPRESA), declaro lo siguiente:';
}elseif ($co_emp=='02') {
  $empresa_ruc='COMERCIAL LUCIANO AREQUIPA S.A.C. con RUC Nº 20555146583, (en adelante LA EMPRESA), declaro lo siguiente:';
}elseif ($co_emp=='03') {
  $empresa_ruc='COPY VENTAS S.R.L. con RUC Nº 20132051322, (en adelante LA EMPRESA), declaro lo siguiente:';
}elseif ($co_emp=='04') {
  $empresa_ruc='SUPLACORP S.A.C., con R.U.C. No 20465062356 (en adelante LA EMPRESA), declaro lo siguiente:';
}elseif ($co_emp=='05') {
  $empresa_ruc='INMOBILIARIA CASCAJAL S.A.C. con RUC Nº 20538004830, (en adelante LA EMPRESA), declaro lo siguiente:';
}else{
  $empresa_ruc='LIBRERIA BAZAR SANTA MARIA E.I.R.L., con R.U.C. No 20208752759 (en adelante LA EMPRESA), declaro lo siguiente:';
}


if ($co_emp=='01') {
  $nombre_empresa='TAI LOY S.A.';
}elseif ($co_emp=='02') {
  $nombre_empresa='COMERCIAL LUCIANO AREQUIPA S.A.C.';
}elseif ($co_emp=='03') {
  $nombre_empresa='COPY VENTAS S.R.L.';
}else{
  $nombre_empresa='SUPLACORP S.A.C.';
}

if ($co_emp=='01') {
  	$direccion_empresa='JR. MARIANO ODICIO N° 153  URB. MIRAFLORES';
}elseif ($co_emp=='02') {
  	$direccion_empresa='JR. MARIANO ODICIO N° 153  URB. MIRAFLORES';
}elseif ($co_emp=='03') {
  	$direccion_empresa='JR. MARIANO ODICIO N° 153  URB. MIRAFLORES';
}elseif($co_emp=='04'){
  	$direccion_empresa='JR. MARIANO ODICIO N° 153  URB. MIRAFLORES';
}else{
	$direccion_empresa='JR. MARIANO ODICIO N° 153  URB. MIRAFLORES';
}


if ($co_emp=='01') {
  $numero_ruc='20100049181';
}elseif ($co_emp=='02') {
  $numero_ruc='20555146583';
}elseif ($co_emp=='03') {
  $numero_ruc='20132051322';
}else{
  $numero_ruc='20465062356';
}


$aniogen = substr($fec_ingreso_postu,0,4);		
$diagen = substr($fec_ingreso_postu,-2);
$mesgen = substr($fec_ingreso_postu,5,2);


$aniofin = substr($fec_fin_postu,0,4);		
$diafin = substr($fec_fin_postu,-2);
$mesfin = substr($fec_fin_postu,5,2);

$timestamp1 = mktime(0,0,0,$mesgen,$diagen,$aniogen); 
$timestamp2 = mktime(0,0,0,$mesfin,$diafin,$aniofin);

$diasContrato=$timestamp2-$timestamp1;

$meses_decimal=($diasContrato/(60*60*720));

$meses_entero = explode('.',$meses_decimal);
$meses=$meses_entero[0] ;

$dias=($diasContrato/(60*60*24)-(30*$meses))+1;

if ($dias=='30') {
    $tiempo_contrato=($meses+1).' meses';
}else{
  	$tiempo_contrato=$meses.' meses con '.$dias.' días';
}

if($mesgen == '01'){
	$mesg = 'Enero';
}elseif($mesgen == '02'){
	$mesg = 'Febrero';
}elseif($mesgen == '03'){
	$mesg = 'Marzo';
}elseif($mesgen == '04'){
	$mesg = 'Abril';
}elseif($mesgen == '05'){
	$mesg = 'Mayo';
}elseif($mesgen == '06'){
	$mesg = 'Junio';
}elseif($mesgen == '07'){
	$mesg = 'Julio';
}elseif($mesgen == '08'){
	$mesg = 'Agosto';
}elseif($mesgen == '09'){
	$mesg = 'Setiembre';
}elseif($mesgen == '10'){
	$mesg = 'Octubre';
}elseif($mesgen == '11'){
	$mesg = 'Noviembre';
}elseif($mesgen == '12'){
	$mesg = 'Diciembre';
}

$fecha_ingreso_numero=$diagen.'/'.$mesgen.'/'.$aniogen;
$fecha_ingreso_letras=$diagen.' de '.$mesg.' de '.$aniogen;

if($mesfin == '01'){
	$mesf = 'Enero';
}elseif($mesfin == '02'){
	$mesf = 'Febrero';
}elseif($mesfin == '03'){
	$mesf = 'Marzo';
}elseif($mesfin == '04'){
	$mesf = 'Abril';
}elseif($mesfin == '05'){
	$mesf = 'Mayo';
}elseif($mesfin == '06'){
	$mesf = 'Junio';
}elseif($mesfin == '07'){
	$mesf = 'Julio';
}elseif($mesfin == '08'){
	$mesf = 'Agosto';
}elseif($mesfin == '09'){
	$mesf = 'Setiembre';
}elseif($mesfin == '10'){
	$mesf = 'Octubre';
}elseif($mesfin == '11'){
	$mesf = 'Noviembre';
}elseif($mesfin == '12'){
	$mesf = 'Diciembre';
}		


$fecha_fin_numero=$diafin.'/'.$mesfin.'/'.$aniofin;
$fecha_fin_letras=$diafin.' de '.$mesf.' de '.$aniofin;

?>



<style type="text/css">
<!--



.midnight-blue{
	background:#2c3e50;
	padding: 4px 4px 4px;
	color:white;
	font-weight:bold;
	font-size:12px;
}
.silver{
	background:white;
	padding: 3px 4px 3px;
}
.clouds{
	background:#ecf0f1;
	padding: 3px 4px 3px;
}
.border-top{
	border-top: solid 1px #bdc3c7;
	
}
.border-left{
	border-left: solid 1px #bdc3c7;
}
.border-right{
	border-right: solid 1px #bdc3c7;
}
.border-bottom{
	border-bottom: solid 1px #bdc3c7;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
}
-->


</style>

<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 7pt; font-family: arial" >

	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 100%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;height: 2%;">
               PERSONAL NUEVO
            </td>
		</tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 20%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;padding-left: 5px">
                RAZON SOCIAL  
            </td>
            <td style="width: 80%; color: black;font-size:9px;text-align:middle; text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px">
                <?php echo $nombre_empresa ?> 
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 20%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;padding-left: 5px">
                RUC 
            </td>
            <td style="width: 80%; color: black;font-size:9px;text-align:middle; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px">
                <?php echo $numero_ruc ?> 
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 20%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;padding-left: 5px">
                DIRECCIÓN 
            </td>
            <td style="width: 80%; color: black;font-size:9px;text-align:middle; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px">
                <?php echo $direccion_empresa ?> 
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 20%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;padding-left: 5px">
                REP. LEGAL 
            </td>
            <td style="width: 80%; color: black;font-size:9px;text-align:middle; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px">
                MANUEL ENRIQUE HUARCAYA SILVA 
            </td>
		</tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left">
                DATOS DEL COLABORADOR:
            </td>
            <td style="width: 70%; color: black;font-size:9px;text-align:middle; text-align: left;">
                <?php echo $nom_postu ?> 
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left">
                DNI:
            </td>
            <td style="width: 70%; color: black;font-size:9px;text-align:middle; text-align: left;">
                <?php echo $dni_postulante ?> 
            </td>
		</tr>

	</table>
	<!--<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left">
                DNI:
            </td>
            <td style="width: 70%; color: black;font-size:9px;text-align:middle; text-align: left;">
                <?php echo $dni_postulante ?> 
            </td>
		</tr>

	</table>-->
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; background-color: #e2efda">
                DOCUMENTOS PARA LA CONTRATACION	 
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 11%; color: black;font-size:9px;text-align:middle; text-align: center;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px">
                EMPLEADO
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                1. CONTRATO DE TRABAJO
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                2. CONVENIO DE PRACTICAS
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/sinnada.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                3. PLAN DE PRACTICAS
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/sinnada.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                4. CERTIFICADO DE ESTUDIOS
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                5. CARTA DE PRESENTACION
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/sinnada.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                6. CARTA DE EGRESADO 
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/sinnada.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                7. REGLAMENTO INTERNO DE TRABAJO (RIT)
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                8. AUTORIZ. INFORMACION POR CORREO ELECTRONICO
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                9. CONTRATO PRESTACION ALIMENTARIA PROVIS
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                10. FICHA DE INGRESO DEL PERSONAL 
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                11. FOTO 
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                12. DDJJ PARENTESCO 
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                13. DDJJ DOMICILIO
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                14. RECIBO DE LUZ O AGUA DE SU DOMICILIO( SOLO DE 01 MES DE ANTIGÜEDAD)
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                15. COPIA DNI COLABORADOR
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                16. COPIA DNI HIJOS
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                17. COPIA DNI CONYUGUE/CONVIVIENTE 
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                18. PARTIDA MATRIMONIO 
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                19. UNION DE HECHO -DOC NOTARIAL
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                20. ABTEC. POLICIALES
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                21. DDJJ DSCTO TRAMITE
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                22. RECOMENDACIÓN SALUD Y SEGURIDAD EN EL TRABAJO
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                23. ELECCION SISTEMA PENSIONARIO 
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                24. FORMATO SBS - CUSPP (Impreso en caso AFP)
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                25. CV. DOCUMENTADO
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siconequis.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                26. HOJA CHECK LIST DE DOCUMENTOS ENTREGADOS
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                7. CARTA OFERTA  DE TRABAJO 
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                (*) Documentos Subsanables sin opción a reintegro 
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				<img src="../../inc/img/siyno.png" style="height: 18px">
            </td>
		</tr>

	</table>
	<br>

	<?php  

	$conexion2 = odbc_connect('OFIPLAN','OFISIS','CQ5001'); 
	if ($conexion2 == FALSE){ 
	echo ('Error en la conexion'); 
	exit(); 
	}


	$querySeleccionador = "SELECT  
	TMTRAB_PERS.NO_APEL_PATE+' '+TMTRAB_PERS.NO_APEL_MATE+' '+TMTRAB_PERS.NO_TRAB AS SELECCIONADOR
	FROM SELECCION.DBO.TM_POSTULANTE_POST
	LEFT JOIN OFIPLAN.DBO.TMTRAB_PERS 
		ON TMTRAB_PERS.CO_TRAB=TM_POSTULANTE_POST.CO_SELECCIONADOR
	WHERE TM_POSTULANTE_POST.NU_DOCU_IDEN='".$dni."'
		AND TM_POSTULANTE_POST.CORR_POSTU='".$corre."'
 ";


	$resultSeleccionador = odbc_exec($conexion2,$querySeleccionador);
	//echo $conexion;
	while($registroSeleccionador=odbc_fetch_array($resultSeleccionador)) {
	    $nombre_seleccionador=utf8_encode($registroSeleccionador['SELECCIONADOR']);
	}
	?>

	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                ENTREGADO POR: <?php echo $nombre_seleccionador ?>
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				
            </td>
		</tr>

	</table>

	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                FECHA RECEPCIÓN: <?php echo $fecha_ingreso_numero ?>
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				
            </td>
		</tr>

	</table>

	<?php  

	$conexion2 = odbc_connect('OFIPLAN','OFISIS','CQ5001'); 
	if ($conexion2 == FALSE){ 
	echo ('Error en la conexion'); 
	exit(); 
	}


	$queryEncargado = "SELECT  
	TMTRAB_PERS.NO_APEL_PATE+' '+TMTRAB_PERS.NO_APEL_MATE+' '+TMTRAB_PERS.NO_TRAB AS ENCARGADO
	FROM SELECCION.DBO.TM_POSTULANTE_EMPR
	LEFT JOIN OFIPLAN.DBO.TMTRAB_PERS 
		ON TMTRAB_PERS.CO_TRAB=TM_POSTULANTE_EMPR.CO_TRAB_LIDE
	WHERE TM_POSTULANTE_EMPR.NU_DOCU_IDEN='".$dni."'
		AND TM_POSTULANTE_EMPR.NU_CORR_POSTU='".$corre."'
 ";


	$resultEncargado = odbc_exec($conexion2,$queryEncargado);
	//echo $conexion;
	while($registroEncargado=odbc_fetch_array($resultEncargado)) {
	    $nombre_encargado=utf8_encode($registroEncargado['ENCARGADO']);
	}
	?>

	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 55%; color: black;font-size:9px;text-align:middle;font-weight:bold; text-align: left;border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;padding-left: 5px; ">
                 REVISADO POR: <?php echo $nombre_encargado ?>
            </td>
            <td style="width: 10%;">
                &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 10%; ">
				
            </td>
		</tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
            <td style="width: 100%; ">
				<img src="../../inc/img/observacion.png" style="height: 15%;width: 100%">
            </td>
		</tr>

	</table>
</page>

<?php  
if ($tipo_contrato_trabajo=='NUE' AND $co_emp=='01') {
?>
<!--CONSTANCIA DE RECEPCION DE CONTRATO NUEVO-->
<page backtop="40mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;">CONSTANCIA DE RECEPCIÓN DE CONTRATO DE TRABAJO
                </span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Declaro haber recibido un ejemplar del Contrato de Trabajo de Naturaleza Temporal por Necesidades del Mercado suscrito al  con la Empresa <b>TAI LOY S.A.</b>, el mismo que consta de 21 cláusulas y es por <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>. </span>

           	</td>
           	
        </tr>

	</table>

	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Asimismo, como trabajador de TAI LOY S.A., me comprometo a cumplir frente a la empresa, a mis superiores jerárquicos y mis compañeros de trabajo, las obligaciones establecidas en el contrato antes referido y las disposiciones que las leyes laborales establecen.</span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br>

	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;  ">________________________________</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 27%; color: black;font-size:12px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:20px;font-weight:bold;font-size:12px;">FIRMA DEL TRABAJADOR</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">APELLIDOS Y NOMBRES : <?php echo $nom_postu ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;"><?php echo $tipo_documento ?> NRO. :<?php echo $dni_postulante ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">FECHA DE RECEPCIÓN: <?php echo $fecha_ingreso_numero ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>

	
	
</page>

<!--CONTRATO DE TRABAJO-->
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="15mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 17%; color: black;font-size:10px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-weight:bold;text-decoration: underline black;">CONTRATO DE TRABAJO TEMPORAL POR NECESIDADES DEL MERCADO</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Conste por el presente documento, que se extiende por triplicado, el Contrato de Trabajo de Naturaleza Temporal  por Necesidades del Mercado  que de  conformidad con lo dispuesto en el  artículo 58º y 73º del Decreto Supremo No.003-97-TR, Ley de Productividad y Competitividad Laboral, T.U.O. del Decreto Legislativo Nº 728, celebran:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De una parte, como LA EMPRESA: <br>TAI LOY S.A., con RUC Nº 20100049181, con domicilio en JR. MARIANO ODICIO NRO. 153 URB. MIRAFLORES (MZ L, LOTE 144, SUB LOTE A) Distrito de SURQUILLO, Provincia y Departamento de Lima, debidamente representado por el Representante Legal Huarcaya Silva Manuel Enrique con DNI. Nº 06225424, con poderes inscritos en la Partida Electrónica N° 03028398 del Registro de Personas Jurídicas de Lima;  y,	</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De la otra parte, como EL TRABAJADOR:<br> El (la) señor(a) <?php echo $nom_postu ?>, identificado(a) con <?php echo $tipo_documento ?> NRO. <?php echo $dni_postulante ?> con domicilio en <?php echo $direccion_postu?>, <?php echo $nombre_distrito_postu ?>. En los términos y condiciones establecidos en las cláusulas siguientes:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>PRIMERA:</u>ANTECEDENTES Y CAUSA OBJETIVA DE CONTRATACIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA es una persona jurídica de derecho privado, dedicada a realizar los servicios de tiene como actividad principal la importación y venta de útiles de escritorio, oficina, librería, papeles, cartones, juguetería, perfumería, artículos de limpieza y otros; así como la importación y exportación  de las mismas. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La apertura del Mercado como consecuencia de la libre importación de productos de nuestro giro por parte de Empresas existentes y nuevas Empresas ha dado lugar a una mayor gama y diversidad de estos, y a una mayor competencia. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La situación descrita ha originado la existencia de variaciones sustanciales de la demanda, lo que obliga a un incremento coyuntural en la comercialización de LA EMPRESA en el Mercado y en particular de determinado tipo de artículos, que no pueden ser satisfechas con el personal permanente. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La causa objetiva invocada está justificada entonces, por el incremento temporal e imprevisible de la actividad comercial para satisfacer las nuevas exigencias coyunturales del Mercado.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Por su parte, EL TRABAJADOR es una persona natural que reúne las condiciones propias y el perfil deseado por LA EMPRESA para que desempeñe sus labores en calidad de <?php echo $puesto_postu ?> en el centro de trabajo ubicado en la tienda de la empresa sito en<?php echo $direccion_tienda_postu ?>.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">No obstante, ello, EL TRABAJADOR declara estar de acuerdo y acatar voluntariamente los destaques a filiales o sucursales de LA EMPRESA que se realicen por necesidades del servicio y/o condición de trabajo, de acuerdo a la facultad directriz del empleador establecida en el artículo 9 del Decreto Supremo No.003-97-TR, respetándose su remuneración, categoría ocupacional y porque tal situación no perjudica sus derechos laborales adquiridos.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>SEGUNDA:</u>CONTRATACIÓN</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En virtud del presente documento y al amparo de lo dispuesto por el artículo 58 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, LA EMPRESA contrata bajo la modalidad de necesidades del mercado los servicios de EL TRABAJADOR para que realice labores como <?php echo $puesto_postu ?> y otras que son inherentes a su puesto; debido a que ha existido incremento coyuntural de la producción por variaciones sustanciales de la demanda en el mercado de acuerdo a lo indicado en el segundo párrafo de la cláusula primera de este contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes acuerdan voluntariamente que la prestación del servicio que EL TRABAJADOR va a desarrollar en virtud del presente Contrato se extiende mientras dure su vínculo laboral a favor de cualquier otra compañía presente o futura que se encuentre vinculada económicamente y  empresarialmente en forma directa o indirecta con LA EMPRESA y/o con las demás empresas mencionadas en el párrafo anterior, sea porque desarrollan actividades similares como la venta de juguetería, artículos de librería o afines, etc.; o porque su objeto social es importante para coadyuvar al desarrollo de la actividad empresarial de LA EMPRESA y sus vinculadas.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, son funciones generales y específicas propias del cargo de <?php echo $puesto_postu ?> a favor de LA EMPRESA y sus compañías vinculadas, las funciones a cargo de EL TRABAJADOR que se detallan en el Manual de Organización de Funciones (MOF) que son de conocimiento de EL TRABAJADOR.</span>

           	</td>

        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En tal sentido, las partes acuerdan que el cargo de EL TRABAJADOR se desarrollará a plazo fijo y bajo subordinación a cambio de la remuneración convenida en la cláusula sexta de este contrato.  </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>TERCERA:</u>		PLAZO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">El plazo de vigencia del presente contrato es de  <?php echo $tiempo_contrato ?>, siendo su fecha de inicio el <?php echo $fecha_ingreso_letras ?>,debiendo concluir el <?php echo $fecha_fin_letras ?>, fecha en que quedará extinguido el presente contrato, de no mediar acuerdo para la renovación del mismo entre las partes.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Al término del contrato EL TRABAJADOR gozará de todos los derechos y beneficios que contempla el artículo 79 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>CUARTA:</u>	PERIODO DE PRUEBA.</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De acuerdo a lo dispuesto por los artículos 10 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, el presente contrato estará sometido al periodo de prueba de 03 MESES de acuerdo a ley. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>QUINTA:</u>		DE LA PRÓRROGA O RENOVACIÓN DEL CONTRATO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes podrán prorrogar o renovar el presente contrato hasta alcanzar el máximo legal de cinco (05) años previsto en el artículo 74º del Decreto Supremo No.003-97-TR, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>SEXTA:</u>		SUBORDINACIÓN</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Ambas partes acuerdan que, mientras dure la relación laboral derivada del presente contrato, EL TRABAJADOR se encuentra obligado a prestar los servicios descritos en la cláusula segunda, bajo dirección y subordinación de la Gerencia General de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Para este efecto, EL TRABAJADOR se obliga a cumplir con las normas propias del Centro de Trabajo, así como las contenidas en el Manual de Organización y Funciones, y/o en el Reglamento de Seguridad y Salud en el Trabajo de ser el caso y aquellas que se impartan por necesidades del servicio en ejercicio de las facultades de Administración que LA EMPRESA tiene de acuerdo a lo establecido por el artículo 9 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
</page>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="15mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>SÉTIMA:</u>		REMUNERACIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA abonará a EL TRABAJADOR por sus servicios prestados una remuneración mensual S/. <?php echo $salario_postu ?>.00( <?php echo $salario_letras_postu ?>). </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, EL TRABAJADOR podrá percibir también comisiones y/o bonificaciones por la venta efectivamente realizada de los productos de LA EMPRESA debido a su gestión, conforme a los términos y condiciones que establezca LA EMPRESA en cada oportunidad. Las partes acuerdan que las comisiones y/o bonificaciones que pudiesen cancelarse en cada oportunidad se devengarán con periodicidad mensual.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">A las comisiones y/o bonificaciones que pueda percibir EL TRABAJADOR le serán de aplicación los descuentos y deducciones por aportaciones y contribuciones sociales establecidas por ley.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes acuerdan que las comisiones y/o bonificaciones que pueda percibir EL TRABAJADOR serán canceladas por LA EMPRESA siempre y cuando EL TRABAJADOR forme parte del personal que pertenezca a los puestos que participen en la política de comisiones y/o bonificaciones que LA EMPRESA haya establecido expresamente con antelación a la percepción de dicho beneficio.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En caso EL TRABAJADOR no llegue a la Remuneración Mínima Vital (RMV) LA EMPRESA completará el saldo hasta llegar a dicho monto.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, queda expresamente establecido que las ausencias injustificadas por parte de EL TRABAJADOR implican la pérdida de la remuneración proporcionalmente a la duración de dicha ausencia, sin perjuicio del ejercicio de las facultades disciplinarias propias de LA EMPRESA previstas en sus normas internas, así como en la legislación laboral vigente.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>OCTAVA:</u>		JORNADA DE TRABAJO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De conformidad con lo establecido en el artículo 1 del Decreto Supremo No. 007-2002-TR, EL TRABAJADOR prestará sus servicios dentro de la jornada laboral semanal de cuarenta y ocho (48) horas semanales en los horarios y turnos que oportunamente se le informen en atención a las necesidades del servicio y actividades comerciales.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Sin embargo, si LA EMPRESA lo considera puede permitir que EL TRABAJADOR labore menos de la jornada laboral referida en el párrafo anterior sin menoscabo de su remuneración; sin que ello signifique reducción, cambio o modificación de la jornada laboral de cuarenta y ocho (48) horas semanales pactada por ambas partes.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En tal sentido, de requerirlo LA EMPRESA, en caso de necesidades del servicio puede solicitar a EL TRABAJADOR el cumplimiento de la jornada laboral de cuarenta y ocho (48) horas semanales pactada en el presente contrato, aun cuando EL TRABAJADOR se encuentre laborando en una jornada inferior.   Dicha extensión no originará el pago de horas extras o compensación remunerativa adicional alguna.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Queda establecido que el tiempo destinado por EL TRABAJADOR para su refrigerio no forma parte de la jornada de trabajo.Asimismo, EL TRABAJADOR y LA EMPRESA acuerdan que en caso se desarrollen labores en sobre tiempo, estas podrán ser pagadas con la sobre tasa de ley o en su defecto y por decisión de LA EMPRESA, compensadas con horas o días de descanso; ocurriendo lo mismo en caso EL TRABAJADOR desarrolle labores en su día de descanso semanal obligatorio y en feriados no laborables.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">No obstante el horario indicado en la presente cláusula, de conformidad con lo dispuesto por la Ley No.27671, que modifica la Ley de Jornada de Trabajo, Horario y Trabajo en Sobretiempo, queda expresamente establecido que EL CONTRATADO deberá prestar servicios en horas extras de manera obligatoria, en los casos en que su labor resulte indispensable a consecuencia de un hecho fortuito o fuerza mayor que ponga en peligro inminente a las personas o los bienes del centro de trabajo o la continuidad de la actividad productiva.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>NOVENA:</u>		SUSPENSIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La suspensión del presente contrato, por alguna de las causas previstas en el artículo 12 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, no modificará ni alterará el plazo de vigencia del mismo.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMA:</u>		CONCLUSIÓN DEL CONTRATO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Queda entendido que LA EMPRESA no está obligada a dar aviso adicional referente al término del presente contrato, operando su vencimiento en forma automática en la fecha señalada para tal efecto en la cláusula tercera, oportunidad en la cual se abonará a EL TRABAJADOR los beneficios sociales que pudieran corresponderle.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, el contrato podrá concluir en cualquiera de los supuestos señalados en los artículos 23 y 24 del Decreto Supremo No. 003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Finalmente, las partes acuerdan que el presente contrato se extinguirá también en caso de renuncia del contratado o mutuo disenso laboral entre las partes.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO PRIMERA:</u>		DECLARACIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR declara expresamente a la suscripción del presente contrato que conoce el contenido del Reglamento Interno de Trabajo así como del Manual de Organización y Funciones que rige en la empresa y aquellos implementos y artículos que deberá usar obligatoriamente en el desempeño de su labor.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Igualmente, EL TRABAJADOR declara con la suscripción de este contrato que autoriza a LA EMPRESA a efectuar el descuento respectivo de sus haberes y/o liquidación de beneficios sociales que le pueda corresponder, por los perjuicios económicos, daño a la imagen de la empresa, y cualquier otra contingencia que sufra la empresa producto del actuar negligente y/o imprudente de EL TRABAJADOR en el desempeño de sus funciones.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO SEGUNDA:</u>	CUMPLIMIENTO DE LAS OBLIGACIONES</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR deberá cumplir durante el tiempo que dure el presente contrato, con todas y cada una de las obligaciones para las cuales fue contratado, según las necesidades de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR se obliga, del mismo modo, a mantener en secreto toda información que llegue a su conocimiento en relación a los negocios, actividades y recursos de LA EMPRESA, sus asociados, proveedores y/o clientes. Esta obligación subsistirá aun después de terminada la relación laboral y su incumplimiento origina la correspondiente responsabilidad por daños y perjuicios, sin perjuicio de la persecución penal por el delito de violación del secreto profesional previsto en el artículo 165 del actual Código Penal.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">a)	Cumplir estrictamente con cada una de las recomendaciones y disposiciones señaladas en el Reglamento Interno de Trabajo, Manual de Organización y Funciones, Código de ética y/o en el Reglamento de Seguridad y Salud en el Trabajo de ser el caso, que le han sido entregados por LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>

	</page>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="15mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">b)	Usar obligatoriamente con diligencia y responsabilidad los equipos, útiles e implementos para la realización de su labor que le son entregados por LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>

	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">c)	Declarar bajo su propia responsabilidad a LA EMPRESA si ha pertenecido anteriormente la ONP o AFP, así como indicar expresamente cual son sus derechohabientes y/o asegurados, firmando una declaración jurada para tal efecto. Cualquier error, impresión, falsedad u omisión en dichas declaraciones es de entera responsabilidad de EL TRABAJADOR.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">d)	Someterse obligatoriamente a pasar los exámenes médicos correspondientes que señale la ley, así como aquellos que sean necesarios para su seguridad a solicitud de EL TRABAJADOR, y que puedan ser realizadas por entidades aseguradoras privadas, además de aquellos exámenes médicos que se necesiten al culminar la relación laboral, bajo pena de extinción del contrato de trabajo y/o la obligación de resarcimiento del daño económico que pueda sufrir LA EMPRESA por dichas omisiones. <br>Asimismo, ambas partes declaran que LA EMPRESA se exonerará de responsabilidad en caso EL TRABAJADOR no cumpla, de ser necesario, con someterse a los exámenes médicos correspondientes al finalizar su relación laboral a pesar del requerimiento de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">e)	Suscribir y firmar las boletas de pago en las fechas programadas por LA EMPRESA para la percepción de sus haberes.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">f)	Acatar los destaques a filiales o sucursales de LA EMPRESA que se realicen por necesidades del servicio y/o condición de trabajo, de acuerdo a la facultad directriz del empleador establecida en el artículo 9 del Decreto Supremo No.003-97-TR.</span>

           	</td>

           	
        </tr>

	</table>
	

	
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO TERCERA:</u>	RÉGIMEN LABORAL</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR estará sujeto al régimen laboral de la actividad privada dentro de los alcances y efectos que determina el Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, su Reglamento aprobado por Decreto Supremo No.001-96-TR, y las demás normas modificatorias o ampliatorias para los trabajadores sujetos a contratos bajo modalidad.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO CUARTA:</u>	COMUNICACIONES</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Los domicilios de las partes serán los que se indican en la introducción de este documento, acordándose que estos solo podrán variarse, previa comunicación escrita cursada a la otra parte con una anticipación de cinco (05) días útiles, a la fecha de la variación efectiva. Al domicilio indicado deberán cursarse todas las comunicaciones relacionadas al presente contrato. En caso no exista comunicación alguna respecto al cambio de domicilio o dicho cambio se notifica a la otra parte sin la anticipación indicada en esta cláusula, se entiende por válidas las comunicaciones cursadas al domicilio inicialmente consignado entre las mismas.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO QUINTA:</u>	CONOCIMIENTO Y REGISTRO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">El presente contrato será presentado para su conocimiento y registro ante la Autoridad Administrativa de Trabajo, de acuerdo a lo dispuesto por el artículo 73 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO SEXTA:</u>	JURISDICCIÓN Y COMPETENCIA</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Ambas partes contratantes renuncian expresamente al fuero de sus domicilios y se someten a la Jurisdicción y Competencia de los Jueces y Tribunales de Lima respecto a los términos y condiciones derivados del presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO SETIMA:</u>     		PROTECCIÓN DE DATOS PERSONALES</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR otorga a LA EMPRESA, su consentimiento libre, previo, expreso, inequívoco e informado para que pueda recopilar, registrar, organizar, almacenar, conservar, elaborar, modificar, bloquear, suprimir, extraer, consultar, utilizar, transferir, exportar, importar o tratar de cualquier otra forma conforme a Ley (por sí mismo o a través de terceros) sus datos personales, los cuales serán incluidos en Bancos de Datos Personales de titularidad y responsabilidad de LA EMPRESA, de conformidad con lo dispuesto en la Ley N° 29733, Ley de Protección de Datos Personales y sus normas reglamentarias, complementarias y modificatorias</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR autoriza a LA EMPRESA, el uso y/o registro en el Banco de datos personales de la siguiente información, ya sea por cualquier medio físico o digital a nivel nacional o extranjero (Estados Unidos) en relación al flujo transfronterizo:</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">•	Al tratamiento de mis datos Personales que corresponden a información de carácter identificativo, tales como: nombres y apellidos; documentos de identidad; domicilio; teléfonos y correo electrónico. </span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">•	Al tratamiento de mis datos Sensibles que corresponden a información de firmas; estado civil; fecha de nacimiento; nacionalidad; profesión, ocupación; edad; datos académicos; datos de derechohabientes; datos relativos a la salud; datos financieros para efectos del pago y obligaciones de seguridad social; biométricos (por ejemplo: audio, video y/o fotografía); las imágenes de las que soy parte y que se registraron en las fotos y/o videos efectuados por mi empleador, conforme a lo señalado en el artículo 15° de Código Civil Peruano.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Estos datos personales los puede proporcionar EL TRABAJADOR directamente o LA EMPRESA, los puede generar u obtener a través de terceros para ser tratados con la finalidad de:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">i)	Realizar las actividades relacionadas con la prestación de sus servicios, y la ejecución de sus labores y/o</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">ii)	Enviarle ofertas comerciales, publicidad e información en general de LA EMPRESA y/o terceras vinculadas o no vinculadas; y/o cualquier otra empresa que pertenezca o que pueda pertenecer en el futuro al GRUPO TAI LOY, ya sea domiciliada o no en el país, y/o</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">iii)	Ofrecerle productos y/o servicios en forma directa, a través de terceros y/o mediante asociaciones comerciales</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">iv)	Obtener información estadística y/o histórica para LA EMPRESA y/o cualquier otra empresa que pertenezca o que pueda pertenecer en el futuro al GRUPO TAI LOY.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">v)	Utilizar sus datos personales en materiales de carácter institucional de LA EMPRESA., tales como fotos, videos, material impreso, entre otros.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Esta autorización es indefinida y estará vigente inclusive después del vencimiento del presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA. se reserva el derecho de poder compartir y/o usar y/o almacenar en su servidor, así como en archivos físicos o digitales y/o transferir la información a terceras personas vinculadas o no, sean estos socios comerciales o no de LA EMPRESA., nacionales o extranjeros, públicos o privados con el objeto de realizar actividades relacionadas al cumplimiento de las finalidades indicadas anteriormente.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA declara que ha adoptado los niveles de seguridad apropiados para el resguardo de la información, respetando las medidas de seguridad técnica aplicables a cada categoría y tipo de tratamiento de Bancos de Datos personales; asimismo, declara que respeta los principios de legalidad, consentimiento, finalidad, proporcionalidad, calidad, disposición de recurso, nivel de protección adecuado, conforme a las disposiciones de la Ley de Protección de Datos Personales vigente en Perú.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR se obliga a cumplir con las políticas y los lineamientos dictados por LA EMPRESA en el marco de lo dispuesto por la Ley de Protección de Datos Personales, su reglamento y demás normas conexas.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR, declara haber sido informado de que en caso no otorgue este consentimiento, sus datos personales solo serán utilizados y/o tratados específicamente para el cumplimiento de los fines vinculados con la prestación de sus servicios y la ejecución de sus labores, en el marco del presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR declara haber sido informado que podrá ejercer en cualquier momento sus derechos de información, acceso, rectificación, cancelación y oposición de sus datos de acuerdo a lo dispuesto por la Ley de Protección de Datos Personales vigente y su reglamento. Para ello efectuará su solicitud en <u>protecciondedatos@tailoy.com.pe.</u></span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO OCTAVA:</u>           PREVENCIÓN DE LAVADO DE ACTIVOS, DELITOS DE COHECHO Y FINANCIACIÓN DEL TERRORISMO</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes declaran que, de conformidad con lo dispuesto en la Ley N° 30424 y su modificatoria prevista en el Decreto Legislativo N° 1352 (ambas leyes que regulan la responsabilidad administrativa de las personas jurídicas frente a los delitos de Cohecho Activo (Genérico, Especifico e Internacional), Lavado de Activos y Financiamiento del terrorismo); en concordancia con lo previsto en el Artículo 17º.- Control y supervisión del cumplimiento de normas del Decreto Supremo Nº 018-2006-JUS, Reglamento de la Ley 27693, sobre Normas Complementarias para la Prevención del Lavado de Activos y del Financiamiento del Terrorismo; y, la Ley Nº 27765 (Ley Penal Contra el Lavado de Activos), en carácter de Declaración Jurada: que los recursos, fondos, dineros, activos, bienes o servicios relacionados y movilizados para el presente contrato provienen de actividades licitas y no están vinculados con el lavado de activos ni con ninguno de sus delitos fuente, además que el destino de los recursos, fondos, dineros, activos, bienes o servicios que se generen del presente contrato celebrado no van a ser destinados ni movilizados para la financiación del terrorismo ni con ninguno de sus delitos fuente, o cualquier otra conducta delictiva como el cohecho activo en cualquiera de sus modalidades, acorde a las normas penales peruanas vigentes.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO NOVENA:</u>            POLÍTICA ANTISOBORNO, FRAUDE Y ANTICORRUPCIÓN</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">1)	LA EMPRESA tiene como política empresarial contratar únicamente con personas naturales y jurídicas que cumplan con las leyes, reglamentos y requisitos administrativos aplicables al presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">2)	En virtud de lo anterior, LA EMPRESA exige que tanto las personas naturales como jurídicas tengan los más altos niveles éticos, en las etapas de suscripción y ejecución del presente contrato. Por lo que, en forma expresa EL TRABAJADOR se obliga:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(i)	A no participar en actos de corrupción y/o soborno que puedan involucrar a LA EMPRESA o que puedan ser considerados que brindan un beneficio ilegítimo a LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(ii)	A no realizar pagos de facilitación por encargo o cuyo beneficio sea a favor de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(iii)	A informar cualquier conducta desleal o propuesta por parte de algún colaborador de LA EMPRESA que no se encuentre alineado a la presente política.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
       <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(iv)	Acepta que en caso se demuestre que ha incurrido en una conducta impropia o que haya incumplido normas aplicables respecto a anticorrupción, LA EMPRESA podrá resolver el contrato unilateralmente sin necesidad de aviso previo.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(v)	Asegura no ser un funcionario público ni estar relacionado con alguno hasta el segundo grado de consanguineidad; en caso se encuentre en alguno de estos casos, deberá informarlo inmediatamente a LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(vi)	Acepta que en caso realice algún acto en contra de lo dispuesto en las normas contra la corrupción y soborno cumplirá con pagar la indemnización correspondiente.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La intención de las partes es que no se efectúen pagos ni se realicen entregas de objetos de valor que puedan tener la finalidad o el efecto de cohechar a un funcionario público o sobornar a una empresa privada o aceptar o permitir exacciones ilegales, comisiones indebidas u otros medios ilícitos o irregulares de obtener negocios.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En cualquier caso, quedará a salvo el pago de los honorarios mensuales o proporcionales pendientes por la asesoría brindada a la empresa hasta el momento de la resolución.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>VIGESIMO:</u>     	         AUTORIZACIÓN DE ACCESO A HERRAMIENTAS DE TRABAJO</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La presente Cláusula tiene por objeto establecer la autorización y consentimiento previo, informado, expreso e incondicional de EL TRABAJADOR a favor de LA EMPRESA para poder acceder a toda y cualquier información como correos electrónicos, páginas de acceso virtual y telefónica que se encuentre en las herramientas o equipos de trabajo y demás información de carácter personal o sensible que se encuentren en equipos o herramientas de trabajo sea computadoras, laptops, celulares, móviles u otros entregados por LA EMPRESA  a  EL TRABAJADOR para el desarrollo de sus labores, acciones comerciales, incluyendo la remisión, directa o por intermedio de terceros (vía medio físico, electrónico o telefónico) de publicidad, información, obsequios, ofertas y/o promociones (personalizadas o generales) de productos y/o servicios de LA EMPRESA y/o de otras compañías vinculadas, así como en el portal <u>http://www1.tailoy.com.pe/servicio-online/index.php</u></span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>VIGESIMO PRIMERO:</u>            SISTEMAS DE INFORMACIÓN E INTRANET</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR reconoce que los sistemas relacionados con la Intranet, Internet, Software o aplicaciones, Sistemas Operativos, medios de almacenamiento y Correo Electrónico a las cuales accede en el ejercicio de su labor, son de propiedad exclusiva de  LA EMPRESA y/o de sus clientes, razón por la cual se compromete a conocer y realizar el acceso respectivo y utilizar dichos sistemas con fines exclusivamente laborales y para servir a los intereses de LA EMPRESA, conforme lo establecen las normas vigentes que forman parte del presente contrato y que EL TRABAJADOR se compromete a cumplir.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En señal de conformidad y aceptación, ambas partes firman el presente documento en tres (02) ejemplares de igual tenor y valor <?php echo $diagen.' días del mes de '.$mesg.' de '.$aniogen ?>.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br><br><br><br><br>
	<br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>HUARCAYA SILVA MANUEL ENRIQUE</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $nom_postu ?></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>DNI. N° 06225424</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $tipo_documento ?>. N° <?php echo $dni_postulante ?></span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>EL TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>
</page>

<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="15mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 17%; color: black;font-size:10px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-weight:bold;text-decoration: underline black;">CONTRATO DE TRABAJO TEMPORAL POR NECESIDADES DEL MERCADO</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Conste por el presente documento, que se extiende por triplicado, el Contrato de Trabajo de Naturaleza Temporal  por Necesidades del Mercado  que de  conformidad con lo dispuesto en el  artículo 58º y 73º del Decreto Supremo No.003-97-TR, Ley de Productividad y Competitividad Laboral, T.U.O. del Decreto Legislativo Nº 728, celebran:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De una parte, como LA EMPRESA: <br>TAI LOY S.A., con RUC Nº 20100049181, con domicilio en JR. MARIANO ODICIO NRO. 153 URB. MIRAFLORES (MZ L, LOTE 144, SUB LOTE A) Distrito de SURQUILLO, Provincia y Departamento de Lima, debidamente representado por el Representante Legal Huarcaya Silva Manuel Enrique con DNI. Nº 06225424, con poderes inscritos en la Partida Electrónica N° 03028398 del Registro de Personas Jurídicas de Lima;  y,	</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De la otra parte, como EL TRABAJADOR:<br> El (la) señor(a) <?php echo $nom_postu ?>, identificado(a) con <?php echo $tipo_documento ?> NRO. <?php echo $dni_postulante ?> con domicilio en <?php echo $direccion_postu?>, <?php echo $nombre_distrito_postu ?>. En los términos y condiciones establecidos en las cláusulas siguientes:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>PRIMERA:</u>ANTECEDENTES Y CAUSA OBJETIVA DE CONTRATACIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA es una persona jurídica de derecho privado, dedicada a realizar los servicios de tiene como actividad principal la importación y venta de útiles de escritorio, oficina, librería, papeles, cartones, juguetería, perfumería, artículos de limpieza y otros; así como la importación y exportación  de las mismas. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La apertura del Mercado como consecuencia de la libre importación de productos de nuestro giro por parte de Empresas existentes y nuevas Empresas ha dado lugar a una mayor gama y diversidad de estos, y a una mayor competencia. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La situación descrita ha originado la existencia de variaciones sustanciales de la demanda, lo que obliga a un incremento coyuntural en la comercialización de LA EMPRESA en el Mercado y en particular de determinado tipo de artículos, que no pueden ser satisfechas con el personal permanente. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La causa objetiva invocada está justificada entonces, por el incremento temporal e imprevisible de la actividad comercial para satisfacer las nuevas exigencias coyunturales del Mercado.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Por su parte, EL TRABAJADOR es una persona natural que reúne las condiciones propias y el perfil deseado por LA EMPRESA para que desempeñe sus labores en calidad de <?php echo $puesto_postu ?> en el centro de trabajo ubicado en la tienda de la empresa sito en<?php echo $direccion_tienda_postu ?>.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">No obstante, ello, EL TRABAJADOR declara estar de acuerdo y acatar voluntariamente los destaques a filiales o sucursales de LA EMPRESA que se realicen por necesidades del servicio y/o condición de trabajo, de acuerdo a la facultad directriz del empleador establecida en el artículo 9 del Decreto Supremo No.003-97-TR, respetándose su remuneración, categoría ocupacional y porque tal situación no perjudica sus derechos laborales adquiridos.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>SEGUNDA:</u>CONTRATACIÓN</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En virtud del presente documento y al amparo de lo dispuesto por el artículo 58 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, LA EMPRESA contrata bajo la modalidad de necesidades del mercado los servicios de EL TRABAJADOR para que realice labores como <?php echo $puesto_postu ?> y otras que son inherentes a su puesto; debido a que ha existido incremento coyuntural de la producción por variaciones sustanciales de la demanda en el mercado de acuerdo a lo indicado en el segundo párrafo de la cláusula primera de este contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes acuerdan voluntariamente que la prestación del servicio que EL TRABAJADOR va a desarrollar en virtud del presente Contrato se extiende mientras dure su vínculo laboral a favor de cualquier otra compañía presente o futura que se encuentre vinculada económicamente y  empresarialmente en forma directa o indirecta con LA EMPRESA y/o con las demás empresas mencionadas en el párrafo anterior, sea porque desarrollan actividades similares como la venta de juguetería, artículos de librería o afines, etc.; o porque su objeto social es importante para coadyuvar al desarrollo de la actividad empresarial de LA EMPRESA y sus vinculadas.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, son funciones generales y específicas propias del cargo de <?php echo $puesto_postu ?> a favor de LA EMPRESA y sus compañías vinculadas, las funciones a cargo de EL TRABAJADOR que se detallan en el Manual de Organización de Funciones (MOF) que son de conocimiento de EL TRABAJADOR.</span>

           	</td>

        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En tal sentido, las partes acuerdan que el cargo de EL TRABAJADOR se desarrollará a plazo fijo y bajo subordinación a cambio de la remuneración convenida en la cláusula sexta de este contrato.  </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>TERCERA:</u>		PLAZO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">El plazo de vigencia del presente contrato es de  <?php echo $tiempo_contrato ?>, siendo su fecha de inicio el <?php echo $fecha_ingreso_letras ?>,debiendo concluir el <?php echo $fecha_fin_letras ?>, fecha en que quedará extinguido el presente contrato, de no mediar acuerdo para la renovación del mismo entre las partes.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Al término del contrato EL TRABAJADOR gozará de todos los derechos y beneficios que contempla el artículo 79 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>CUARTA:</u>	PERIODO DE PRUEBA.</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De acuerdo a lo dispuesto por los artículos 10 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, el presente contrato estará sometido al periodo de prueba de 03 MESES de acuerdo a ley. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>QUINTA:</u>		DE LA PRÓRROGA O RENOVACIÓN DEL CONTRATO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes podrán prorrogar o renovar el presente contrato hasta alcanzar el máximo legal de cinco (05) años previsto en el artículo 74º del Decreto Supremo No.003-97-TR, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>SEXTA:</u>		SUBORDINACIÓN</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Ambas partes acuerdan que, mientras dure la relación laboral derivada del presente contrato, EL TRABAJADOR se encuentra obligado a prestar los servicios descritos en la cláusula segunda, bajo dirección y subordinación de la Gerencia General de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Para este efecto, EL TRABAJADOR se obliga a cumplir con las normas propias del Centro de Trabajo, así como las contenidas en el Manual de Organización y Funciones, y/o en el Reglamento de Seguridad y Salud en el Trabajo de ser el caso y aquellas que se impartan por necesidades del servicio en ejercicio de las facultades de Administración que LA EMPRESA tiene de acuerdo a lo establecido por el artículo 9 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
</page>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="15mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>SÉTIMA:</u>		REMUNERACIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA abonará a EL TRABAJADOR por sus servicios prestados una remuneración mensual S/. <?php echo $salario_postu ?>.00( <?php echo $salario_letras_postu ?>). </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, EL TRABAJADOR podrá percibir también comisiones y/o bonificaciones por la venta efectivamente realizada de los productos de LA EMPRESA debido a su gestión, conforme a los términos y condiciones que establezca LA EMPRESA en cada oportunidad. Las partes acuerdan que las comisiones y/o bonificaciones que pudiesen cancelarse en cada oportunidad se devengarán con periodicidad mensual.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">A las comisiones y/o bonificaciones que pueda percibir EL TRABAJADOR le serán de aplicación los descuentos y deducciones por aportaciones y contribuciones sociales establecidas por ley.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes acuerdan que las comisiones y/o bonificaciones que pueda percibir EL TRABAJADOR serán canceladas por LA EMPRESA siempre y cuando EL TRABAJADOR forme parte del personal que pertenezca a los puestos que participen en la política de comisiones y/o bonificaciones que LA EMPRESA haya establecido expresamente con antelación a la percepción de dicho beneficio.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En caso EL TRABAJADOR no llegue a la Remuneración Mínima Vital (RMV) LA EMPRESA completará el saldo hasta llegar a dicho monto.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, queda expresamente establecido que las ausencias injustificadas por parte de EL TRABAJADOR implican la pérdida de la remuneración proporcionalmente a la duración de dicha ausencia, sin perjuicio del ejercicio de las facultades disciplinarias propias de LA EMPRESA previstas en sus normas internas, así como en la legislación laboral vigente.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>OCTAVA:</u>		JORNADA DE TRABAJO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De conformidad con lo establecido en el artículo 1 del Decreto Supremo No. 007-2002-TR, EL TRABAJADOR prestará sus servicios dentro de la jornada laboral semanal de cuarenta y ocho (48) horas semanales en los horarios y turnos que oportunamente se le informen en atención a las necesidades del servicio y actividades comerciales.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Sin embargo, si LA EMPRESA lo considera puede permitir que EL TRABAJADOR labore menos de la jornada laboral referida en el párrafo anterior sin menoscabo de su remuneración; sin que ello signifique reducción, cambio o modificación de la jornada laboral de cuarenta y ocho (48) horas semanales pactada por ambas partes.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En tal sentido, de requerirlo LA EMPRESA, en caso de necesidades del servicio puede solicitar a EL TRABAJADOR el cumplimiento de la jornada laboral de cuarenta y ocho (48) horas semanales pactada en el presente contrato, aun cuando EL TRABAJADOR se encuentre laborando en una jornada inferior.   Dicha extensión no originará el pago de horas extras o compensación remunerativa adicional alguna.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Queda establecido que el tiempo destinado por EL TRABAJADOR para su refrigerio no forma parte de la jornada de trabajo.Asimismo, EL TRABAJADOR y LA EMPRESA acuerdan que en caso se desarrollen labores en sobre tiempo, estas podrán ser pagadas con la sobre tasa de ley o en su defecto y por decisión de LA EMPRESA, compensadas con horas o días de descanso; ocurriendo lo mismo en caso EL TRABAJADOR desarrolle labores en su día de descanso semanal obligatorio y en feriados no laborables.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">No obstante el horario indicado en la presente cláusula, de conformidad con lo dispuesto por la Ley No.27671, que modifica la Ley de Jornada de Trabajo, Horario y Trabajo en Sobretiempo, queda expresamente establecido que EL CONTRATADO deberá prestar servicios en horas extras de manera obligatoria, en los casos en que su labor resulte indispensable a consecuencia de un hecho fortuito o fuerza mayor que ponga en peligro inminente a las personas o los bienes del centro de trabajo o la continuidad de la actividad productiva.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>NOVENA:</u>		SUSPENSIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La suspensión del presente contrato, por alguna de las causas previstas en el artículo 12 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, no modificará ni alterará el plazo de vigencia del mismo.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMA:</u>		CONCLUSIÓN DEL CONTRATO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Queda entendido que LA EMPRESA no está obligada a dar aviso adicional referente al término del presente contrato, operando su vencimiento en forma automática en la fecha señalada para tal efecto en la cláusula tercera, oportunidad en la cual se abonará a EL TRABAJADOR los beneficios sociales que pudieran corresponderle.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, el contrato podrá concluir en cualquiera de los supuestos señalados en los artículos 23 y 24 del Decreto Supremo No. 003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Finalmente, las partes acuerdan que el presente contrato se extinguirá también en caso de renuncia del contratado o mutuo disenso laboral entre las partes.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO PRIMERA:</u>		DECLARACIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR declara expresamente a la suscripción del presente contrato que conoce el contenido del Reglamento Interno de Trabajo así como del Manual de Organización y Funciones que rige en la empresa y aquellos implementos y artículos que deberá usar obligatoriamente en el desempeño de su labor.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Igualmente, EL TRABAJADOR declara con la suscripción de este contrato que autoriza a LA EMPRESA a efectuar el descuento respectivo de sus haberes y/o liquidación de beneficios sociales que le pueda corresponder, por los perjuicios económicos, daño a la imagen de la empresa, y cualquier otra contingencia que sufra la empresa producto del actuar negligente y/o imprudente de EL TRABAJADOR en el desempeño de sus funciones.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO SEGUNDA:</u>	CUMPLIMIENTO DE LAS OBLIGACIONES</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR deberá cumplir durante el tiempo que dure el presente contrato, con todas y cada una de las obligaciones para las cuales fue contratado, según las necesidades de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR se obliga, del mismo modo, a mantener en secreto toda información que llegue a su conocimiento en relación a los negocios, actividades y recursos de LA EMPRESA, sus asociados, proveedores y/o clientes. Esta obligación subsistirá aun después de terminada la relación laboral y su incumplimiento origina la correspondiente responsabilidad por daños y perjuicios, sin perjuicio de la persecución penal por el delito de violación del secreto profesional previsto en el artículo 165 del actual Código Penal.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">a)	Cumplir estrictamente con cada una de las recomendaciones y disposiciones señaladas en el Reglamento Interno de Trabajo, Manual de Organización y Funciones, Código de ética y/o en el Reglamento de Seguridad y Salud en el Trabajo de ser el caso, que le han sido entregados por LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>

	</page>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="15mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">b)	Usar obligatoriamente con diligencia y responsabilidad los equipos, útiles e implementos para la realización de su labor que le son entregados por LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>

	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">c)	Declarar bajo su propia responsabilidad a LA EMPRESA si ha pertenecido anteriormente la ONP o AFP, así como indicar expresamente cual son sus derechohabientes y/o asegurados, firmando una declaración jurada para tal efecto. Cualquier error, impresión, falsedad u omisión en dichas declaraciones es de entera responsabilidad de EL TRABAJADOR.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">d)	Someterse obligatoriamente a pasar los exámenes médicos correspondientes que señale la ley, así como aquellos que sean necesarios para su seguridad a solicitud de EL TRABAJADOR, y que puedan ser realizadas por entidades aseguradoras privadas, además de aquellos exámenes médicos que se necesiten al culminar la relación laboral, bajo pena de extinción del contrato de trabajo y/o la obligación de resarcimiento del daño económico que pueda sufrir LA EMPRESA por dichas omisiones. <br>Asimismo, ambas partes declaran que LA EMPRESA se exonerará de responsabilidad en caso EL TRABAJADOR no cumpla, de ser necesario, con someterse a los exámenes médicos correspondientes al finalizar su relación laboral a pesar del requerimiento de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">e)	Suscribir y firmar las boletas de pago en las fechas programadas por LA EMPRESA para la percepción de sus haberes.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">f)	Acatar los destaques a filiales o sucursales de LA EMPRESA que se realicen por necesidades del servicio y/o condición de trabajo, de acuerdo a la facultad directriz del empleador establecida en el artículo 9 del Decreto Supremo No.003-97-TR.</span>

           	</td>

           	
        </tr>

	</table>
	

	
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO TERCERA:</u>	RÉGIMEN LABORAL</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR estará sujeto al régimen laboral de la actividad privada dentro de los alcances y efectos que determina el Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, su Reglamento aprobado por Decreto Supremo No.001-96-TR, y las demás normas modificatorias o ampliatorias para los trabajadores sujetos a contratos bajo modalidad.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO CUARTA:</u>	COMUNICACIONES</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Los domicilios de las partes serán los que se indican en la introducción de este documento, acordándose que estos solo podrán variarse, previa comunicación escrita cursada a la otra parte con una anticipación de cinco (05) días útiles, a la fecha de la variación efectiva. Al domicilio indicado deberán cursarse todas las comunicaciones relacionadas al presente contrato. En caso no exista comunicación alguna respecto al cambio de domicilio o dicho cambio se notifica a la otra parte sin la anticipación indicada en esta cláusula, se entiende por válidas las comunicaciones cursadas al domicilio inicialmente consignado entre las mismas.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO QUINTA:</u>	CONOCIMIENTO Y REGISTRO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">El presente contrato será presentado para su conocimiento y registro ante la Autoridad Administrativa de Trabajo, de acuerdo a lo dispuesto por el artículo 73 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO SEXTA:</u>	JURISDICCIÓN Y COMPETENCIA</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Ambas partes contratantes renuncian expresamente al fuero de sus domicilios y se someten a la Jurisdicción y Competencia de los Jueces y Tribunales de Lima respecto a los términos y condiciones derivados del presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO SETIMA:</u>     		PROTECCIÓN DE DATOS PERSONALES</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR otorga a LA EMPRESA, su consentimiento libre, previo, expreso, inequívoco e informado para que pueda recopilar, registrar, organizar, almacenar, conservar, elaborar, modificar, bloquear, suprimir, extraer, consultar, utilizar, transferir, exportar, importar o tratar de cualquier otra forma conforme a Ley (por sí mismo o a través de terceros) sus datos personales, los cuales serán incluidos en Bancos de Datos Personales de titularidad y responsabilidad de LA EMPRESA, de conformidad con lo dispuesto en la Ley N° 29733, Ley de Protección de Datos Personales y sus normas reglamentarias, complementarias y modificatorias</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR autoriza a LA EMPRESA, el uso y/o registro en el Banco de datos personales de la siguiente información, ya sea por cualquier medio físico o digital a nivel nacional o extranjero (Estados Unidos) en relación al flujo transfronterizo:</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">•	Al tratamiento de mis datos Personales que corresponden a información de carácter identificativo, tales como: nombres y apellidos; documentos de identidad; domicilio; teléfonos y correo electrónico. </span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">•	Al tratamiento de mis datos Sensibles que corresponden a información de firmas; estado civil; fecha de nacimiento; nacionalidad; profesión, ocupación; edad; datos académicos; datos de derechohabientes; datos relativos a la salud; datos financieros para efectos del pago y obligaciones de seguridad social; biométricos (por ejemplo: audio, video y/o fotografía); las imágenes de las que soy parte y que se registraron en las fotos y/o videos efectuados por mi empleador, conforme a lo señalado en el artículo 15° de Código Civil Peruano.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Estos datos personales los puede proporcionar EL TRABAJADOR directamente o LA EMPRESA, los puede generar u obtener a través de terceros para ser tratados con la finalidad de:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">i)	Realizar las actividades relacionadas con la prestación de sus servicios, y la ejecución de sus labores y/o</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">ii)	Enviarle ofertas comerciales, publicidad e información en general de LA EMPRESA y/o terceras vinculadas o no vinculadas; y/o cualquier otra empresa que pertenezca o que pueda pertenecer en el futuro al GRUPO TAI LOY, ya sea domiciliada o no en el país, y/o</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">iii)	Ofrecerle productos y/o servicios en forma directa, a través de terceros y/o mediante asociaciones comerciales</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">iv)	Obtener información estadística y/o histórica para LA EMPRESA y/o cualquier otra empresa que pertenezca o que pueda pertenecer en el futuro al GRUPO TAI LOY.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">v)	Utilizar sus datos personales en materiales de carácter institucional de LA EMPRESA., tales como fotos, videos, material impreso, entre otros.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Esta autorización es indefinida y estará vigente inclusive después del vencimiento del presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA. se reserva el derecho de poder compartir y/o usar y/o almacenar en su servidor, así como en archivos físicos o digitales y/o transferir la información a terceras personas vinculadas o no, sean estos socios comerciales o no de LA EMPRESA., nacionales o extranjeros, públicos o privados con el objeto de realizar actividades relacionadas al cumplimiento de las finalidades indicadas anteriormente.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA declara que ha adoptado los niveles de seguridad apropiados para el resguardo de la información, respetando las medidas de seguridad técnica aplicables a cada categoría y tipo de tratamiento de Bancos de Datos personales; asimismo, declara que respeta los principios de legalidad, consentimiento, finalidad, proporcionalidad, calidad, disposición de recurso, nivel de protección adecuado, conforme a las disposiciones de la Ley de Protección de Datos Personales vigente en Perú.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR se obliga a cumplir con las políticas y los lineamientos dictados por LA EMPRESA en el marco de lo dispuesto por la Ley de Protección de Datos Personales, su reglamento y demás normas conexas.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR, declara haber sido informado de que en caso no otorgue este consentimiento, sus datos personales solo serán utilizados y/o tratados específicamente para el cumplimiento de los fines vinculados con la prestación de sus servicios y la ejecución de sus labores, en el marco del presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR declara haber sido informado que podrá ejercer en cualquier momento sus derechos de información, acceso, rectificación, cancelación y oposición de sus datos de acuerdo a lo dispuesto por la Ley de Protección de Datos Personales vigente y su reglamento. Para ello efectuará su solicitud en <u>protecciondedatos@tailoy.com.pe.</u></span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO OCTAVA:</u>           PREVENCIÓN DE LAVADO DE ACTIVOS, DELITOS DE COHECHO Y FINANCIACIÓN DEL TERRORISMO</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes declaran que, de conformidad con lo dispuesto en la Ley N° 30424 y su modificatoria prevista en el Decreto Legislativo N° 1352 (ambas leyes que regulan la responsabilidad administrativa de las personas jurídicas frente a los delitos de Cohecho Activo (Genérico, Especifico e Internacional), Lavado de Activos y Financiamiento del terrorismo); en concordancia con lo previsto en el Artículo 17º.- Control y supervisión del cumplimiento de normas del Decreto Supremo Nº 018-2006-JUS, Reglamento de la Ley 27693, sobre Normas Complementarias para la Prevención del Lavado de Activos y del Financiamiento del Terrorismo; y, la Ley Nº 27765 (Ley Penal Contra el Lavado de Activos), en carácter de Declaración Jurada: que los recursos, fondos, dineros, activos, bienes o servicios relacionados y movilizados para el presente contrato provienen de actividades licitas y no están vinculados con el lavado de activos ni con ninguno de sus delitos fuente, además que el destino de los recursos, fondos, dineros, activos, bienes o servicios que se generen del presente contrato celebrado no van a ser destinados ni movilizados para la financiación del terrorismo ni con ninguno de sus delitos fuente, o cualquier otra conducta delictiva como el cohecho activo en cualquiera de sus modalidades, acorde a las normas penales peruanas vigentes.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>DÉCIMO NOVENA:</u>            POLÍTICA ANTISOBORNO, FRAUDE Y ANTICORRUPCIÓN</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">1)	LA EMPRESA tiene como política empresarial contratar únicamente con personas naturales y jurídicas que cumplan con las leyes, reglamentos y requisitos administrativos aplicables al presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">2)	En virtud de lo anterior, LA EMPRESA exige que tanto las personas naturales como jurídicas tengan los más altos niveles éticos, en las etapas de suscripción y ejecución del presente contrato. Por lo que, en forma expresa EL TRABAJADOR se obliga:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(i)	A no participar en actos de corrupción y/o soborno que puedan involucrar a LA EMPRESA o que puedan ser considerados que brindan un beneficio ilegítimo a LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(ii)	A no realizar pagos de facilitación por encargo o cuyo beneficio sea a favor de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(iii)	A informar cualquier conducta desleal o propuesta por parte de algún colaborador de LA EMPRESA que no se encuentre alineado a la presente política.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
       <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(iv)	Acepta que en caso se demuestre que ha incurrido en una conducta impropia o que haya incumplido normas aplicables respecto a anticorrupción, LA EMPRESA podrá resolver el contrato unilateralmente sin necesidad de aviso previo.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(v)	Asegura no ser un funcionario público ni estar relacionado con alguno hasta el segundo grado de consanguineidad; en caso se encuentre en alguno de estos casos, deberá informarlo inmediatamente a LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(vi)	Acepta que en caso realice algún acto en contra de lo dispuesto en las normas contra la corrupción y soborno cumplirá con pagar la indemnización correspondiente.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La intención de las partes es que no se efectúen pagos ni se realicen entregas de objetos de valor que puedan tener la finalidad o el efecto de cohechar a un funcionario público o sobornar a una empresa privada o aceptar o permitir exacciones ilegales, comisiones indebidas u otros medios ilícitos o irregulares de obtener negocios.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En cualquier caso, quedará a salvo el pago de los honorarios mensuales o proporcionales pendientes por la asesoría brindada a la empresa hasta el momento de la resolución.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>VIGESIMO:</u>     	         AUTORIZACIÓN DE ACCESO A HERRAMIENTAS DE TRABAJO</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La presente Cláusula tiene por objeto establecer la autorización y consentimiento previo, informado, expreso e incondicional de EL TRABAJADOR a favor de LA EMPRESA para poder acceder a toda y cualquier información como correos electrónicos, páginas de acceso virtual y telefónica que se encuentre en las herramientas o equipos de trabajo y demás información de carácter personal o sensible que se encuentren en equipos o herramientas de trabajo sea computadoras, laptops, celulares, móviles u otros entregados por LA EMPRESA  a  EL TRABAJADOR para el desarrollo de sus labores, acciones comerciales, incluyendo la remisión, directa o por intermedio de terceros (vía medio físico, electrónico o telefónico) de publicidad, información, obsequios, ofertas y/o promociones (personalizadas o generales) de productos y/o servicios de LA EMPRESA y/o de otras compañías vinculadas, así como en el portal <u>http://www1.tailoy.com.pe/servicio-online/index.php</u></span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;"><u>VIGESIMO PRIMERO:</u>            SISTEMAS DE INFORMACIÓN E INTRANET</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR reconoce que los sistemas relacionados con la Intranet, Internet, Software o aplicaciones, Sistemas Operativos, medios de almacenamiento y Correo Electrónico a las cuales accede en el ejercicio de su labor, son de propiedad exclusiva de  LA EMPRESA y/o de sus clientes, razón por la cual se compromete a conocer y realizar el acceso respectivo y utilizar dichos sistemas con fines exclusivamente laborales y para servir a los intereses de LA EMPRESA, conforme lo establecen las normas vigentes que forman parte del presente contrato y que EL TRABAJADOR se compromete a cumplir.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En señal de conformidad y aceptación, ambas partes firman el presente documento en tres (02) ejemplares de igual tenor y valor <?php echo $diagen.' días del mes de '.$mesg.' de '.$aniogen ?>.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br><br><br><br><br>
	<br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>HUARCAYA SILVA MANUEL ENRIQUE</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $nom_postu ?></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>DNI. N° 06225424</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $tipo_documento ?>. N° <?php echo $dni_postulante ?></span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>EL TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>
</page>
<?php }elseif ($tipo_contrato_trabajo=='JEF' AND $co_emp=='01') { ?>
<page backtop="40mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;">CONSTANCIA DE RECEPCIÓN DE CONTRATO DE TRABAJO
                </span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Declaro haber recibido un ejemplar del Contrato de Trabajo de Naturaleza Temporal por Necesidades del Mercado suscrito al  con la Empresa TAI LOY S.A., el mismo que consta de 20 cláusulas y es por <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>. </span>

           	</td>
           	
        </tr>

	</table>

	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Asimismo, como trabajador de <b>TAI LOY S.A.</b>, me comprometo a cumplir frente a la empresa, a mis superiores jerárquicos y mis compañeros de trabajo, las obligaciones establecidas en el contrato antes referido y las disposiciones que las leyes laborales establecen.</span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br>

	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;  ">________________________________</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 27%; color: black;font-size:12px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:20px;font-weight:bold;font-size:12px;">FIRMA DEL TRABAJADOR</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">APELLIDOS Y NOMBRES : <?php echo $nom_postu ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;"><?php echo $tipo_documento ?> NRO. :<?php echo $dni_postulante ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">FECHA DE RECEPCIÓN: <?php echo $fecha_ingreso_numero ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>

	
	
</page>
<?php }elseif ($co_emp=='02') { ?>

<!--LUCIANO-->
<page backtop="40mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;">CONSTANCIA DE RECEPCIÓN DE CONTRATO DE TRABAJO
                </span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Declaro haber recibido un ejemplar del Contrato de Trabajo de Naturaleza Temporal por servicios específicos suscrito al con la Empresa COMERCIAL LUCIANO AREQUIPA S.A.C. el mismo que consta de 8 cláusulas  por <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>.</span>

           	</td>
           	
        </tr>

	</table>

	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Asimismo, como trabajador de COMERCIAL LUCIANO AREQUIPA S.A.C., me comprometo a cumplir frente a la empresa, a mis superiores jerárquicos y mis compañeros de trabajo, las obligaciones establecidas en el contrato antes referido y las disposiciones que las leyes laborales establecen.</span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br>

	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;  ">________________________________</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 27%; color: black;font-size:12px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:20px;font-weight:bold;font-size:12px;">FIRMA DEL TRABAJADOR</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">APELLIDOS Y NOMBRES : <?php echo $nom_postu ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;"><?php echo $tipo_documento ?> NRO. :<?php echo $dni_postulante ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">FECHA DE RECEPCIÓN: <?php echo $fecha_ingreso_numero ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>

</page>


<page backtop="10mm" backbottom="15mm" backleft="10mm" backright="10mm" style="font-size: 7pt; font-family: arial" >
	
			
    <page_footer>
       
    </page_footer>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
        <tr>
           	<td>
           		<img src="../../inc/img/cabecera-constancia-luciano.png" style='width: 750px; height: 180px'>
           	</td>
           	
        </tr>
	</table>
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 30%; color: black;font-size:10px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-weight:bold;text-decoration: underline black;">CONTRATO A PLAZO FIJO</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>

           		<span style="line-height:15px;">Conste por el presente documento el Contrato Individual de Trabajo a Plazo Fijo que celebran de una parte COMERCIAL LUCIANO AREQUIPA S.A.C. con RUC Nº 20555146583, con domicilio fiscal en Calle Peru N° 216- Arequipa y domicilio comercial en Jr. Mariano Odicio Nº 153, Urb. Miraflores- Surquillo, debidamente representado por su representante legal Manuel Enrique Huarcaya Silva con DNI Nº 06225424, en adelante "LA EMPRESA" y de la otra parte el señor(a) <?php echo $nom_postu ?> con <?php echo $tipo_documento ?> NRO. <?php echo $dni_postulante ?>, con domicilio en <?php echo $direccion_postu?>, distrito de <?php echo $nombre_distrito_postu ?>, en delante "EL TRABAJADOR", en los términos y condiciones siguientes: </span>
           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>PRIMERO.- </b>"LA EMPRESA" tiene como actividad principal la comercialización de toda clase de mercaderías así como la importación y exportación de las mismas. </span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>SEGUNDO.- </b>La apertura del Mercado como consecuencia de la libre importación de productos de nuestro giro por parte de Empresas existentes y nuevas Empresas ha dado lugar a una mayor gama y diversidad de estos, y a una mayor competencia. La situación descrita obliga a un incremento coyuntural en la comercialización de la Empresa por la variación sustancial de la demanda en el Mercado y en particular de determinado tipo de artículos, que no pueden ser  satisfechas con el personal permanente. La causa objetiva invocada está justificada entonces, por el incremento temporal e imprevisible de la actividad comercial para satisfacer las nuevas exigencias coyunturales del Mercado.</span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>TERCERO.- </b> "LA EMPRESA" en virtud de la cláusula precedente contrata a Plazo Fijo a "EL TRABAJADOR" para que labore como <?php echo $puesto_postu ?> siendo este Contrato de Naturaleza Temporal, en la modalidad de Necesidades del Mercado previsto en el Art. 58 del D.S. 003-97-TR T.U.O del Decreto Legislativo Nº 728.</span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>CUARTO.- </b> "EL TRABAJADOR" realizará su labor sujetándose al horario corrido y/o partido, y turnos de trabajo fijos y/o rotativos que la Empresa le asigne, pudiendo la Empresa unilateralmente variarlos en el tiempo según sus necesidades. </span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>QUINTO.- </b>  "LA EMPRESA" abonará a "EL TRABAJADOR" por sus servicios prestados una remuneración mensual de S/. <?php echo $salario_postu ?>.00( <?php echo $salario_letras_postu ?>)</span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>SEXTO.- </b>  "EL TRABAJADOR" realizará las labores que líneas abajo se indican, no siendo estas limitativas, pues LA EMPRESA podrá asignarle otras acorde con la naturaleza del puesto.</span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>SETIMO.- </b> La duración del presente Contrato será de <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>, que comienza automáticamente, sin necesidad de requerimiento alguno, sin embargo, las partes pueden acordar su prórroga antes del vencimiento del plazo. Las partes dejan claramente establecido que operando su vencimiento la suspensión del Contrato de Trabajo por alguna de las causas previstas en el Art.12 del D.S. 003-97-TR, no interrumpirá el plazo de duración del presente contrato.</span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>OCTAVO.- </b>"LA EMPRESA" y "EL TRABAJADOR" expresan su conformidad a los términos y condiciones establecidas en este contrato, obligándose la Empresa a ponerlo en conocimiento de la Autoridad de Trabajo. </span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Hecho y firmado en dos ejemplares de unos mismos tenores, en Arequipa, a los <?php echo $fecha_ingreso_letras ?>.</span>
           	</td>
        </tr>
	</table>
	<br><br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>HUARCAYA SILVA MANUEL ENRIQUE</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $nom_postu ?></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>DNI. 06225424</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $tipo_documento ?>. <?php echo $dni_postulante ?></span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>EL TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>

</page>
<page backtop="10mm" backbottom="15mm" backleft="10mm" backright="10mm" style="font-size: 7pt; font-family: arial" >
	
			
    <page_footer>
       
    </page_footer>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
        <tr>
           	<td>
           		<img src="../../inc/img/cabecera-constancia-luciano.png" style='width: 750px; height: 180px'>
           	</td>
           	
        </tr>
	</table>
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 30%; color: black;font-size:10px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-weight:bold;text-decoration: underline black;">CONTRATO A PLAZO FIJO</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>

           		<span style="line-height:15px;">Conste por el presente documento el Contrato Individual de Trabajo a Plazo Fijo que celebran de una parte COMERCIAL LUCIANO AREQUIPA S.A.C. con RUC Nº 20555146583, con domicilio fiscal en Calle Peru N° 216- Arequipa y domicilio comercial en Jr. Mariano Odicio Nº 153, Urb. Miraflores- Surquillo, debidamente representado por su representante legal Manuel Enrique Huarcaya Silva con DNI Nº 06225424, en adelante "LA EMPRESA" y de la otra parte el señor(a) <?php echo $nom_postu ?> con <?php echo $tipo_documento ?> NRO. <?php echo $dni_postulante ?>, con domicilio en <?php echo $direccion_postu?>, distrito de <?php echo $nombre_distrito_postu ?>, en delante "EL TRABAJADOR", en los términos y condiciones siguientes: </span>
           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>PRIMERO.- </b>"LA EMPRESA" tiene como actividad principal la comercialización de toda clase de mercaderías así como la importación y exportación de las mismas. </span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>SEGUNDO.- </b>La apertura del Mercado como consecuencia de la libre importación de productos de nuestro giro por parte de Empresas existentes y nuevas Empresas ha dado lugar a una mayor gama y diversidad de estos, y a una mayor competencia. La situación descrita obliga a un incremento coyuntural en la comercialización de la Empresa por la variación sustancial de la demanda en el Mercado y en particular de determinado tipo de artículos, que no pueden ser  satisfechas con el personal permanente. La causa objetiva invocada está justificada entonces, por el incremento temporal e imprevisible de la actividad comercial para satisfacer las nuevas exigencias coyunturales del Mercado.</span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>TERCERO.- </b> "LA EMPRESA" en virtud de la cláusula precedente contrata a Plazo Fijo a "EL TRABAJADOR" para que labore como <?php echo $puesto_postu ?> siendo este Contrato de Naturaleza Temporal, en la modalidad de Necesidades del Mercado previsto en el Art. 58 del D.S. 003-97-TR T.U.O del Decreto Legislativo Nº 728.</span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>CUARTO.- </b> "EL TRABAJADOR" realizará su labor sujetándose al horario corrido y/o partido, y turnos de trabajo fijos y/o rotativos que la Empresa le asigne, pudiendo la Empresa unilateralmente variarlos en el tiempo según sus necesidades. </span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>QUINTO.- </b>  "LA EMPRESA" abonará a "EL TRABAJADOR" por sus servicios prestados una remuneración mensual de S/. <?php echo $salario_postu ?>.00( <?php echo $salario_letras_postu ?>)</span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>SEXTO.- </b>  "EL TRABAJADOR" realizará las labores que líneas abajo se indican, no siendo estas limitativas, pues LA EMPRESA podrá asignarle otras acorde con la naturaleza del puesto.</span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>SETIMO.- </b> La duración del presente Contrato será de <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>, que comienza automáticamente, sin necesidad de requerimiento alguno, sin embargo, las partes pueden acordar su prórroga antes del vencimiento del plazo. Las partes dejan claramente establecido que operando su vencimiento la suspensión del Contrato de Trabajo por alguna de las causas previstas en el Art.12 del D.S. 003-97-TR, no interrumpirá el plazo de duración del presente contrato.</span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>OCTAVO.- </b>"LA EMPRESA" y "EL TRABAJADOR" expresan su conformidad a los términos y condiciones establecidas en este contrato, obligándose la Empresa a ponerlo en conocimiento de la Autoridad de Trabajo. </span>
           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Hecho y firmado en dos ejemplares de unos mismos tenores, en Arequipa, a los <?php echo $fecha_ingreso_letras ?>.</span>
           	</td>
        </tr>
	</table>
	<br><br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>HUARCAYA SILVA MANUEL ENRIQUE</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $nom_postu ?></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>DNI. 06225424</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $tipo_documento ?>. <?php echo $dni_postulante ?></span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>EL TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>

</page>
<?php }elseif ($co_emp=='03') { ?>
<page backtop="40mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;">CONSTANCIA DE RECEPCIÓN DE CONTRATO DE TRABAJO
                </span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Declaro haber recibido un ejemplar del Contrato de Trabajo de Naturaleza Temporal por servicios específicos suscrito al con la Empresa COPY VENTAS S.R.L., el mismo que consta de 10 cláusulas, de <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>.</span>

           	</td>
           	
        </tr>

	</table>

	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Asimismo, como trabajador de COPY VENTAS S.R.L., me comprometo a cumplir frente a la empresa, a mis superiores jerárquicos y mis compañeros de trabajo, las obligaciones establecidas en el contrato antes referido y las disposiciones que las leyes laborales establecen.</span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br>

	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;  ">________________________________</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 27%; color: black;font-size:12px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:20px;font-weight:bold;font-size:12px;">FIRMA DEL TRABAJADOR</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">APELLIDOS Y NOMBRES : <?php echo $nom_postu ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;"><?php echo $tipo_documento ?> NRO. :<?php echo $dni_postulante ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">FECHA DE RECEPCIÓN: <?php echo $fecha_ingreso_numero ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>

</page>


<page backtop="10mm" backbottom="15mm" backleft="10mm" backright="10mm" style="font-size: 7pt; font-family: arial; " backimg="../../images/icon/copy_fondo.png">
	
			
    <page_footer>
        <table >
            <tr>
                <td>
           		</td>
            </tr>
        </table>
    </page_footer>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
        <tr>
           	<td>
           		<img src="../../inc/img/cabecera-constancia-copy.png" style='width: 750px; height: 180px'>
           	</td>
           	
        </tr>
       
	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 30%; color: black;font-size:10px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-weight:bold;text-decoration: underline black;">CONTRATO DE TRABAJO SUJETO A MODALIDAD</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Conste por el presente documento que se extiende por triplicado el contrato Individual de Trabajo a Plazo fijo para servicios específicos que se celebra de conformidad con el art. 56, inc. a) y art. 63 del D.S. Nro. 003-97TR, de una parte COPY VENTAS S.R.L. con RUC Nº 20132051322, con domicilio fiscal en Jr. Mariano Odicio N° 153, Interior 601, Lado B Urb. Miraflores, Surquillo, Lima, de ésta ciudad a la que en adelante se le denominará LA EMPRESA representada por HUARCAYA SILVA MANUEL ENRIQUE, identificado con D.N.I. Nro 06225424, en su calidad de REPRESENTANTE LEGAL y de la otra parte Don(ña) <?php echo $nom_postu ?> al que en lo sucesivo se le denominará EL TRABAJADOR identificado con <?php echo $tipo_documento ?> NRO. <?php echo $dni_postulante ?>, con domicilio en <?php echo $direccion_postu?> - <?php echo $nombre_distrito_postu ?> en los términos y condiciones siguientes.</span>
           	</td>
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>PRIMERO.-</b> LA EMPRESA requiere cubrir los requerimientos de Recursos Humanos por la necesidad de incrementar sus ventas y todo lo que implique ésta actividad propia del giro de la empresa en los productos que  comercializa, lo cual justifica la labor a desempeñar por EL TRABAJADOR, en el puesto y cargo descrito en la cláusula siguiente. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>SEGUNDO.-</b>En virtud del presente documento, LA EMPRESA contrata a plazo fijo bajo la modalidad indicada en el encabezamiento de este documento, los servicios de EL TRABAJADOR, para que realice las labores propias y complementarias del puesto de <?php echo $puesto_postu ?>, en razón de las causas objetivas precisadas en la cláusula anterior.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>TERCERO.-</b>El plazo de vigencia del presente contrato es de <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>, tiempo estimado para cubrir las necesidades a que se hace referencia en la cláusula primera.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>CUARTO.-</b> De conformidad con lo establecido en el artículo 1 del Decreto Supremo No. 007-2002-TR, EL TRABAJADOR prestará sus servicios dentro de la jornada laboral semanal de cuarenta y ocho (48) horas semanales en los horarios y turnos que oportunamente se le informen en atención a las necesidades del servicio y actividades comerciales.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>QUINTO.-</b>EL TRABAJADOR deberá cumplir con las normas propias del centro de trabajo contenidas en el Reglamento Interno y de Seguridad y las que imparten por necesidades de servicios en el ejercicio de las facultades de Administración de la Empresa.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>SEXTO.-</b>EL TRABAJADOR percibirá una retribución mensual por el trabajo que realice en ejecución del presente contrato, la suma de S/. <?php echo $salario_postu ?>.00( <?php echo $salario_letras_postu ?>).</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>

		    <td>
           		<span style="line-height:15px;"><b>SETIMO.-</b>Queda entendido que la empresa no esta obligada a dar aviso alguno adicional referente al término del presente contrato, operando su vencimiento en la fecha señalada en la cláusula TERCERA oportunidad en la cual se abonará al TRABAJADOR los beneficios sociales que por ley pudieran corresponderle.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>

		    <td>
           		<span style="line-height:15px;"><b>OCTAVO.-</b> Queda entendido que tratándose de una labor para un trabajo determinado y por así exigirle la modalidad del servicio específico que se va a prestar, tiene la característica de ser a Plazo Fijo.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>

		    <td>
           		<span style="line-height:15px;"><b>NOVENO.-</b>Rigen para el presente contrato las normas previstas en el D.S. 003-97TR.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>

		    <td>
           		<span style="line-height:15px;"><b>DECIMO.-</b>El incumplimiento por parte del TRABAJADOR de las obligaciones señaladas anteriormente y especialmente de las cláusulas CUARTA Y QUINTA, así como en caso en que incurriese en la comisión de la falta grave prevista en la legislación laboral vigente, LA EMPRESA procederá a la rescisión del presente contrato. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>

		    <td>
           		<span style="line-height:15px;">Hecho en tres ejemplares de un mismo tenor y para un solo efecto que se firman en señal de conformidad por las partes a los <?php echo $fecha_ingreso_letras ?>.</span>

           	</td>

           	
        </tr>

	</table>
	<br><br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>HUARCAYA SILVA MANUEL ENRIQUE</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $nom_postu ?></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>DNI. 06225424</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $tipo_documento ?>. <?php echo $dni_postulante ?></span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>EL TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>

</page>
<page backtop="10mm" backbottom="15mm" backleft="10mm" backright="10mm" style="font-size: 7pt; font-family: arial; " backimg="../../images/icon/copy_fondo.png">
	
			
    <page_footer>
        <table >
            <tr>
                <td>
           		</td>
            </tr>
        </table>
    </page_footer>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
        <tr>
           	<td>
           		<img src="../../inc/img/cabecera-constancia-copy.png" style='width: 750px; height: 180px'>
           	</td>
           	
        </tr>
       
	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 30%; color: black;font-size:10px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-weight:bold;text-decoration: underline black;">CONTRATO DE TRABAJO SUJETO A MODALIDAD</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Conste por el presente documento que se extiende por triplicado el contrato Individual de Trabajo a Plazo fijo para servicios específicos que se celebra de conformidad con el art. 56, inc. a) y art. 63 del D.S. Nro. 003-97TR, de una parte COPY VENTAS S.R.L. con RUC Nº 20132051322, con domicilio fiscal en Jr. Mariano Odicio N° 153, Interior 601, Lado B Urb. Miraflores, Surquillo, Lima, de ésta ciudad a la que en adelante se le denominará LA EMPRESA representada por HUARCAYA SILVA MANUEL ENRIQUE, identificado con D.N.I. Nro 06225424, en su calidad de REPRESENTANTE LEGAL y de la otra parte Don(ña) <?php echo $nom_postu ?> al que en lo sucesivo se le denominará EL TRABAJADOR identificado con <?php echo $tipo_documento ?> NRO. <?php echo $dni_postulante ?>, con domicilio en <?php echo $direccion_postu?> - <?php echo $nombre_distrito_postu ?> en los términos y condiciones siguientes.</span>
           	</td>
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>PRIMERO.-</b> LA EMPRESA requiere cubrir los requerimientos de Recursos Humanos por la necesidad de incrementar sus ventas y todo lo que implique ésta actividad propia del giro de la empresa en los productos que  comercializa, lo cual justifica la labor a desempeñar por EL TRABAJADOR, en el puesto y cargo descrito en la cláusula siguiente. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>SEGUNDO.-</b>En virtud del presente documento, LA EMPRESA contrata a plazo fijo bajo la modalidad indicada en el encabezamiento de este documento, los servicios de EL TRABAJADOR, para que realice las labores propias y complementarias del puesto de <?php echo $puesto_postu ?>, en razón de las causas objetivas precisadas en la cláusula anterior.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>TERCERO.-</b>El plazo de vigencia del presente contrato es de <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>, tiempo estimado para cubrir las necesidades a que se hace referencia en la cláusula primera.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>CUARTO.-</b> De conformidad con lo establecido en el artículo 1 del Decreto Supremo No. 007-2002-TR, EL TRABAJADOR prestará sus servicios dentro de la jornada laboral semanal de cuarenta y ocho (48) horas semanales en los horarios y turnos que oportunamente se le informen en atención a las necesidades del servicio y actividades comerciales.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>QUINTO.-</b>EL TRABAJADOR deberá cumplir con las normas propias del centro de trabajo contenidas en el Reglamento Interno y de Seguridad y las que imparten por necesidades de servicios en el ejercicio de las facultades de Administración de la Empresa.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"><b>SEXTO.-</b>EL TRABAJADOR percibirá una retribución mensual por el trabajo que realice en ejecución del presente contrato, la suma de S/. <?php echo $salario_postu ?>.00( <?php echo $salario_letras_postu ?>).</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>

		    <td>
           		<span style="line-height:15px;"><b>SETIMO.-</b>Queda entendido que la empresa no esta obligada a dar aviso alguno adicional referente al término del presente contrato, operando su vencimiento en la fecha señalada en la cláusula TERCERA oportunidad en la cual se abonará al TRABAJADOR los beneficios sociales que por ley pudieran corresponderle.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>

		    <td>
           		<span style="line-height:15px;"><b>OCTAVO.-</b> Queda entendido que tratándose de una labor para un trabajo determinado y por así exigirle la modalidad del servicio específico que se va a prestar, tiene la característica de ser a Plazo Fijo.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>

		    <td>
           		<span style="line-height:15px;"><b>NOVENO.-</b>Rigen para el presente contrato las normas previstas en el D.S. 003-97TR.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>

		    <td>
           		<span style="line-height:15px;"><b>DECIMO.-</b>El incumplimiento por parte del TRABAJADOR de las obligaciones señaladas anteriormente y especialmente de las cláusulas CUARTA Y QUINTA, así como en caso en que incurriese en la comisión de la falta grave prevista en la legislación laboral vigente, LA EMPRESA procederá a la rescisión del presente contrato. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>

		    <td>
           		<span style="line-height:15px;">Hecho en tres ejemplares de un mismo tenor y para un solo efecto que se firman en señal de conformidad por las partes a los <?php echo $fecha_ingreso_letras ?>.</span>

           	</td>

           	
        </tr>

	</table>
	<br><br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>HUARCAYA SILVA MANUEL ENRIQUE</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $nom_postu ?></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>DNI. 06225424</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $tipo_documento ?>. <?php echo $dni_postulante ?></span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>EL TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>

</page>
<?php }elseif ($co_emp=='04') { ?>
<!--CONTRATO SUPLACORP-->
<page backtop="40mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;">CONSTANCIA DE RECEPCIÓN DE CONTRATO DE TRABAJO
                </span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Declaro haber recibido un ejemplar del Contrato de Trabajo de Naturaleza Temporal por Necesidades del Mercado suscrito al con la Empresa SUPLACORP S.A.C., el mismo que consta de 9 cláusulas por <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>.</span>

           	</td>
           	
        </tr>

	</table>

	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Asimismo, como trabajador de SUPLACORP S.A.C., me comprometo a cumplir frente a la empresa, a mis superiores jerárquicos y mis compañeros de trabajo, las obligaciones establecidas en el contrato antes referido y las disposiciones que las leyes laborales establecen.</span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br>

	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;  ">________________________________</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 27%; color: black;font-size:12px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:20px;font-weight:bold;font-size:12px;">FIRMA DEL TRABAJADOR</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">APELLIDOS Y NOMBRES : <?php echo $nom_postu ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;"><?php echo $tipo_documento ?> NRO. :<?php echo $dni_postulante ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">FECHA DE RECEPCIÓN: <?php echo $fecha_ingreso_numero ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>

</page>

<page backtop="10mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 7pt; font-family: arial" >
	
			
    <page_footer>
        <table >
            <tr>
                <td>
           			<img src="../../inc/img/footer-constancia-suplacorp.png" style='width: 850px; height: 120px'>
           		</td>
            </tr>
        </table>
    </page_footer>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
        <tr>
           	<td>
           		<img src="../../inc/img/cabecera-constancia-suplacorp.png" style='width: 750px; height: 180px'>
           	</td>
           	
        </tr>
	</table>
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 30%; color: black;font-size:10px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-weight:bold;text-decoration: underline black;">CONTRATO DE TRABAJO SUJETO A MODALIDAD</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Conste por el presente documento, que se suscribe por triplicado con igual tenor y valor, el contrato de trabajo sujeto a modalidad que al amparo del Texto Único Ordenado del Decreto Legislativo No 728, Decreto Supremo No 00397TR, Ley de Productividad y Competitividad Laboral y normas complementarias, que celebran de una parte SUPLACORP S.A.C., con R.U.C. No 20465062356 y domicilio real en Jr. Mariano Odicio Nº 153, Urb. Miraflores- Surquillo, debidamente representada por el Señor HUARCAYA SILVA MANUEL ENRIQUE , con DNI. Nº06225424, a quien en adelante se le denominará EL EMPLEADOR, y de la otra parte, don(ña) <?php echo $nom_postu ?> con <?php echo $tipo_documento ?> NRO. <?php echo $dni_postulante ?>, domiciliado en <?php echo $direccion_postu?>,a quien en adelante se le denominará EL TRABAJADOR, en los términos y condiciones siguientes:</span>
           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">1. 	EL EMPLEADOR es una Empresa Jurídica, cuyo objeto social es la Comercialización de Útiles y Suministros de Cómputo en General y que ha sido debidamente inscrita en la Partida No 11162824 del Registro de Personas Jurídicas de Lima, que requiere de los servicios del TRABAJADOR en forma Para Obra o Servicio, para la que resulte necesaria.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">2. 	Por el presente contrato, EL TRABAJADOR se obliga a prestar sus servicios al EMPLEADOR en el siguiente puesto: <?php echo $puesto_postu ?>, debiendo someterse al cumplimiento estricto de la labor y responsabilidades del cargo para el cual ha sido contratado, bajo las directivas de sus jefes o instructores, y las que se impartan por necesidades del servicio en ejercicio de las facultades de administración y dirección de la empresa, de conformidad con el artículo 9º del Texto Único Ordenado de la Ley de Productividad y Competitividad Laboral, aprobado por Decreto Supremo No 00397TR.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">3. 	La duración del presente contrato es de <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">4. 	En contraprestación a los servicios del TRABAJADOR, el EMPLEADOR se obliga a pagar una remuneración bruta mensual de S/. <?php echo $salario_postu ?>.00( <?php echo $salario_letras_postu ?>). Igualmente se obliga a facilitar al trabajador los materiales necesarios para que desarrolle sus actividades, y a otorgarle los beneficios que por ley, pacto o costumbre tuvieran los trabajadores del centro de trabajo contratados a plazo indeterminado.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">5. La Jornada Laboral es de ocho (08) horas diarias o 48 horas semanales que deberá cumplir EL TRABAJADOR en los horarios que determine SUPLACORP S.A.C., teniendo un refrigerio de 45 (minutos).</span>

           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">6. 	EL EMPLEADOR, se obliga a inscribir al TRABAJADOR en el Libro de Planillas de Remuneraciones, así como poner a conocimiento de la Autoridad Administrativa de Trabajo el presente contrato, para su conocimiento y registro, en cumplimiento de lo dispuesto por artículo 73º del Texto Único ordenado del Decreto Legislativo No 728, Ley de Productividad y Competitividad laboral, aprobado mediante Decreto Supremo No 00397TR.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">7. 	Queda entendido que EL EMPLEADOR no está obligado a dar aviso alguno adicional referente al término del presente contrato, operando su extinción en la fecha de su vencimiento, conforme a la cláusula tercera, oportunidad en la cual se abonará al TRABAJADOR los beneficios sociales, que le pudieran corresponder de acuerdo a Ley.</span>

           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">8. 	En todo lo no previsto por el presente contrato, se estará a las disposiciones laborales que regulan los contratos de trabajo sujeto a modalidad, contenidos en el Texto Único Ordenado del Decreto Legislativo No 728 aprobado por el Decreto Supremo No 00397TR, Ley de Productividad y Competitividad Laboral.</span>

           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">9. 	Las partes contratantes renuncian expresamente al fuero judicial de sus domicilios y se someten a la jurisdicción de los jueces de Lima para resolver cualquier controversia que el cumplimiento del presente contrato pudiera originar. </span>

           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Lima, a los <?php echo $fecha_ingreso_letras ?></span>

           	</td>
        </tr>
	</table>
	<br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>HUARCAYA SILVA MANUEL ENRIQUE</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $nom_postu ?></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>DNI. 06225424</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $tipo_documento ?>. <?php echo $dni_postulante ?></span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>EL TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>
</page>

<page backtop="10mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 7pt; font-family: arial" >
	
			
    <page_footer>
        <table >
            <tr>
                <td>
           			<img src="../../inc/img/footer-constancia-suplacorp.png" style='width: 850px; height: 120px'>
           		</td>
            </tr>
        </table>
    </page_footer>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
        <tr>
           	<td>
           		<img src="../../inc/img/cabecera-constancia-suplacorp.png" style='width: 750px; height: 180px'>
           	</td>
           	
        </tr>
	</table>
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 30%; color: black;font-size:10px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-weight:bold;text-decoration: underline black;">CONTRATO DE TRABAJO SUJETO A MODALIDAD</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Conste por el presente documento, que se suscribe por triplicado con igual tenor y valor, el contrato de trabajo sujeto a modalidad que al amparo del Texto Único Ordenado del Decreto Legislativo No 728, Decreto Supremo No 00397TR, Ley de Productividad y Competitividad Laboral y normas complementarias, que celebran de una parte SUPLACORP S.A.C., con R.U.C. No 20465062356 y domicilio real en Jr. Mariano Odicio Nº 153, Urb. Miraflores- Surquillo, debidamente representada por el Señor HUARCAYA SILVA MANUEL ENRIQUE , con DNI. Nº06225424, a quien en adelante se le denominará EL EMPLEADOR, y de la otra parte, don(ña) <?php echo $nom_postu ?> con <?php echo $tipo_documento ?> NRO. <?php echo $dni_postulante ?>, domiciliado en <?php echo $direccion_postu?>,a quien en adelante se le denominará EL TRABAJADOR, en los términos y condiciones siguientes:</span>
           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">1. 	EL EMPLEADOR es una Empresa Jurídica, cuyo objeto social es la Comercialización de Útiles y Suministros de Cómputo en General y que ha sido debidamente inscrita en la Partida No 11162824 del Registro de Personas Jurídicas de Lima, que requiere de los servicios del TRABAJADOR en forma Para Obra o Servicio, para la que resulte necesaria.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">2. 	Por el presente contrato, EL TRABAJADOR se obliga a prestar sus servicios al EMPLEADOR en el siguiente puesto: <?php echo $puesto_postu ?>, debiendo someterse al cumplimiento estricto de la labor y responsabilidades del cargo para el cual ha sido contratado, bajo las directivas de sus jefes o instructores, y las que se impartan por necesidades del servicio en ejercicio de las facultades de administración y dirección de la empresa, de conformidad con el artículo 9º del Texto Único Ordenado de la Ley de Productividad y Competitividad Laboral, aprobado por Decreto Supremo No 00397TR.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">3. 	La duración del presente contrato es de <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">4. 	En contraprestación a los servicios del TRABAJADOR, el EMPLEADOR se obliga a pagar una remuneración bruta mensual de S/. <?php echo $salario_postu ?>.00( <?php echo $salario_letras_postu ?>). Igualmente se obliga a facilitar al trabajador los materiales necesarios para que desarrolle sus actividades, y a otorgarle los beneficios que por ley, pacto o costumbre tuvieran los trabajadores del centro de trabajo contratados a plazo indeterminado.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">5. La Jornada Laboral es de ocho (08) horas diarias o 48 horas semanales que deberá cumplir EL TRABAJADOR en los horarios que determine SUPLACORP S.A.C., teniendo un refrigerio de 45 (minutos).</span>

           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">6. 	EL EMPLEADOR, se obliga a inscribir al TRABAJADOR en el Libro de Planillas de Remuneraciones, así como poner a conocimiento de la Autoridad Administrativa de Trabajo el presente contrato, para su conocimiento y registro, en cumplimiento de lo dispuesto por artículo 73º del Texto Único ordenado del Decreto Legislativo No 728, Ley de Productividad y Competitividad laboral, aprobado mediante Decreto Supremo No 00397TR.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">7. 	Queda entendido que EL EMPLEADOR no está obligado a dar aviso alguno adicional referente al término del presente contrato, operando su extinción en la fecha de su vencimiento, conforme a la cláusula tercera, oportunidad en la cual se abonará al TRABAJADOR los beneficios sociales, que le pudieran corresponder de acuerdo a Ley.</span>

           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">8. 	En todo lo no previsto por el presente contrato, se estará a las disposiciones laborales que regulan los contratos de trabajo sujeto a modalidad, contenidos en el Texto Único Ordenado del Decreto Legislativo No 728 aprobado por el Decreto Supremo No 00397TR, Ley de Productividad y Competitividad Laboral.</span>

           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">9. 	Las partes contratantes renuncian expresamente al fuero judicial de sus domicilios y se someten a la jurisdicción de los jueces de Lima para resolver cualquier controversia que el cumplimiento del presente contrato pudiera originar. </span>

           	</td>
        </tr>
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Lima, a los <?php echo $fecha_ingreso_letras ?></span>

           	</td>
        </tr>
	</table>
	<br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>HUARCAYA SILVA MANUEL ENRIQUE</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $nom_postu ?></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>DNI. 06225424</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $tipo_documento ?>. <?php echo $dni_postulante ?></span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>EL TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>
</page>


<?php }elseif ($co_emp=='06') { ?>

<!--CONSTANCIA DE RECEPCION DE CONTRATO-->
<page backtop="40mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;">CONSTANCIA DE RECEPCIÓN DE CONTRATO DE TRABAJO
                </span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Declaro haber recibido un ejemplar del contrato de trabajo de naturaleza temporal por incremento de actividades suscrito al <?php echo $fecha_ingreso_letras ?> con la Empresa LIBRERIA BAZAR SANTA MARIA EIRL., el mismo que consta de 22 cláusulas y es por <?php echo $tiempo_contrato ?>, del <?php echo $fecha_ingreso_letras ?> hasta <?php echo $fecha_fin_letras ?>. </span>

           	</td>
           	
        </tr>

	</table>

	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Asimismo, como trabajador de LIBRERIA BAZAR SANTA MARIA EIRL., me comprometo a cumplir frente a la empresa, a mis superiores jerárquicos y mis compañeros de trabajo, las obligaciones establecidas en el contrato antes referido y las disposiciones que las leyes laborales establecen.</span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br>

	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;  ">________________________________</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 27%; color: black;font-size:12px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:20px;font-weight:bold;font-size:12px;">FIRMA DEL TRABAJADOR</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">APELLIDOS Y NOMBRES : <?php echo $nom_postu ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;"><?php echo $tipo_documento ?> NRO. :<?php echo $dni_postulante ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-weight:bold;font-size:12px;">FECHA DE RECEPCIÓN: <?php echo $fecha_ingreso_numero ?></span>

           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>

	
	
</page>

<!--CONTRATO DE TRABAJO-->
<page backtop="15mm" backbottom="15mm" backleft="10mm" backright="10mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 17%; color: black;font-size:10px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-weight:bold;text-decoration: underline black;">CONTRATO DE TRABAJO TEMPORAL POR INCREMENTO DE ACTIVIDADES</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Conste en el presente documento, el CONTRATO DE TRABAJO DE NATURALEZA TEMPORAL POR INCREMENTO DE ACTIVIDADES que de conformidad con lo dispuesto en el artículo 57º del TUO del Decreto Legislativo Nº 728º, celebran:<br>De una parte, como LA EMPRESA:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LIBRERIA BAZAR SANTA MARIA EIRL, con R.U.C. No 20208752759, con domicilio en CAL.REAL NRO. 307 (ENTRE 2 DE MAYO Y 13 DE NOVIEMBRE) EL TAMBO, Provincia Huancayo y Departamento de Junín, debidamente representado por el Representante Legal Sr. Manuel Enrique Huarcaya Silva, identificado con DNI N° 06225424</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De la otra parte, como EL TRABAJADOR:<br> El (la) señor(a) <?php echo $nom_postu ?>, identificado(a) con <?php echo $tipo_documento ?> NRO. <?php echo $dni_postulante ?> con domicilio en <?php echo $direccion_postu?>, <?php echo $distrito_postu ?>.  En los términos y condiciones establecidos en las cláusulas siguientes:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">PRIMERA: 		ANTECEDENTES Y CAUSA OBJETIVA DE CONTRATACIÓN</span>

           	</td>

           	
        </tr>

	</table>
	


    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA es una persona jurídica de derecho privado, dedicada a realizar los servicios de tiene como actividad la venta de papeles pre impresos para computo, suministro de computadoras, accesorios, equipos diversos y servicios profesionales de mantenimiento, reparación relacionados con la línea informática, así como brindar servicios de fotocopiadora en general. 
</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR, reúne las condiciones, la experiencia necesaria y el perfil deseado por LA EMPRESA para que desempeñe sus labores en calidad de <?php echo $puesto_postu ?>.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">SEGUNDA:  CAUSA OBJETIVA QUE JUSTIFICA LA CONTRATACIÓN</span>

           	</td>

           	
        </tr>

	</table>
  <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">La causa objetiva que justifica la presente contratación es la adquisición de la empresa con fecha 16 DE SETIEMBRE 2019  por parte del GRUPO TAI LOY con un aporte de capital importante que se refleja en sus estados financieros de segundo semestre del año 2019 ha originado el incremento de sus actividades empresariales, debido al crecimiento comercial de la empresa.  En tal sentido, como consecuencia de dicha adquisición e incremento empresarial, así como del volumen de ventas a futuro se origina a su vez la necesidad de contratación, temporal, puesto que las actividades requeridas no pueden ser atendida con personal actual de LA EMPRESA. </span>

            </td>

            
        </tr>

  </table>
  <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">En virtud de lo indicado, se requiere cubrir temporalmente las necesidades de recursos humanos originadas por el incremento de sus actividades indicadas, las cuales deben ser atendidas de forma inmediata para su ordenado y adecuado funcionamiento y desarrollo, por lo que se configura el supuesto previsto en el artículo 57 del Decreto Supremo No.003-97-TR, Ley de Productividad y Competitividad Laboral.</span>

            </td>

            
        </tr>

  </table>
  
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">TERCERA:   DE LOS SERVICIOS CONTRATADOS POR INCREMENTO DE LA ACTIVIDAD</span>

           	</td>

           	
        </tr>

	</table>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">En consideración a las causa objetiva de contratación señalada en la cláusula precedente y al amparo de lo dispuesto por el artículo 57 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, se contrata a plazo fijo bajo la modalidad de incremento de la actividad empresarial los servicios de EL TRABAJADOR para que en calidad de empleado ocupe el cargo de <?php echo $puesto_postu ?> en el centro de trabajo de la empresa ubicado en <?php echo $direccion_tienda_postu ?>, estando obligado a desempeñar las labores propias de su cargo y las que oportunamente se le indiquen, de acuerdo a las normas y lineamientos establecidos en el Reglamento Interno de Trabajo y Directivas que imparta LA EMPRESA.</span>

            </td>

            
        </tr>

  </table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">Las partes acuerdan voluntariamente que la prestación del servicio que EL TRABAJADOR va a desarrollar en virtud del presente Contrato se extiende mientras dure su vínculo laboral a favor de cualquier otra compañía presente o futura que se encuentre vinculada económicamente y  empresarialmente en forma directa o indirecta con LA EMPRESA y/o con las demás empresas mencionadas en el párrafo anterior, sea porque desarrollan actividades similares como la venta de juguetería, artículos de librería o afines, etc.; o porque su objeto social es importante para coadyuvar al desarrollo de la actividad empresarial de LA EMPRESA y sus vinculadas.</span>

            </td>

            
        </tr>

  </table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">Asimismo, son funciones generales y específicas propias del cargo de <?php echo $puesto_postu ?>  a favor de LA EMPRESA y sus compañías vinculadas, las funciones a cargo de EL TRABAJADOR que se detallan en el Manual de Organización de Funciones (MOF) que son de conocimiento de EL TRABAJADOR.</span>

            </td>

            
        </tr>

  </table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">En tal sentido, las partes acuerdan que el cargo de EL TRABAJADOR se desarrollará a plazo fijo y bajo subordinación a cambio de la remuneración convenida en la cláusula sexta de este contrato.  </span>

            </td>

            
        </tr>

  </table>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;font-weight:bold;text-decoration: underline black;">CUARTA:   PLAZO</span>

            </td>

            
        </tr>

  </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">El plazo de vigencia del presente contrato es de  <?php echo $tiempo_contrato ?>, siendo su fecha de <?php echo $fecha_ingreso_letras ?> debiendo concluir el <?php echo $fecha_fin_letras ?>, fecha en que quedará extinguido el presente contrato, de no mediar acuerdo para la renovación del mismo entre las partes.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Al término del contrato EL TRABAJADOR gozará de todos los derechos y beneficios que contempla el artículo 79 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">QUINTA:		PERIODO DE PRUEBA.</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De acuerdo a lo dispuesto por los artículos 10 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, el presente contrato estará sometido al periodo de prueba de <?php echo $periodo_prueba ?> MESES de acuerdo a ley. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">SEXTA:   DE LA PRÓRROGA O RENOVACIÓN DEL CONTRATO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes podrán prorrogar o renovar el presente contrato hasta alcanzar el máximo legal de cinco (05) años previsto en el artículo 74º del Decreto Supremo No.003-97-TR, Ley de Productividad y Competitividad Laboral. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">SÉTIMA:    SUBORDINACIÓN</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Ambas partes acuerdan que, mientras dure la relación laboral derivada del presente contrato, EL TRABAJADOR se encuentra obligado a prestar los servicios descritos en la cláusula segunda, bajo dirección y subordinación de la Gerencia General de LA EMPRESA. </span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Para este efecto, EL TRABAJADOR se obliga a cumplir con las normas propias del Centro de Trabajo, así como las contenidas en el Manual de Organización y Funciones, y/o en el Reglamento de Seguridad y Salud en el Trabajo de ser el caso y aquellas que se impartan por necesidades del servicio en ejercicio de las facultades de Administración que LA EMPRESA tiene de acuerdo a lo establecido por el artículo 9 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
</page>
<page backtop="15mm" backbottom="15mm" backleft="10mm" backright="10mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">OCTAVA:		REMUNERACIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA abonará a EL TRABAJADOR por sus servicios prestados una remuneración mensual de S/. <?php echo $salario_postu ?>.00( <?php echo $salario_letras_postu ?>). </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, EL TRABAJADOR podrá percibir también comisiones y/o bonificaciones por la venta efectivamente realizada de los productos de LA EMPRESA debido a su gestión, conforme a los términos y condiciones que establezca LA EMPRESA en cada oportunidad. Las partes acuerdan que las comisiones y/o bonificaciones que pudiesen cancelarse en cada oportunidad se devengarán con periodicidad mensual.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">A las comisiones y/o bonificaciones que pueda percibir EL TRABAJADOR le serán de aplicación los descuentos y deducciones por aportaciones y contribuciones sociales establecidas por ley.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes acuerdan que las comisiones y/o bonificaciones que pueda percibir EL TRABAJADOR serán canceladas por LA EMPRESA siempre y cuando EL TRABAJADOR forme parte del personal que pertenezca a los puestos que participen en la política de comisiones y/o bonificaciones que LA EMPRESA haya establecido expresamente con antelación a la percepción de dicho beneficio. </span>

           	</td>

           	
        </tr>

	</table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">En caso EL TRABAJADOR no llegue a la Remuneración Mínima Vital (RMV) LA EMPRESA completará el saldo hasta llegar a dicho monto. </span>

            </td>

            
        </tr>

  </table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, queda expresamente establecido que las ausencias injustificadas por parte de EL TRABAJADOR implican la pérdida de la remuneración proporcionalmente a la duración de dicha ausencia, sin perjuicio del ejercicio de las facultades disciplinarias propias de LA EMPRESA previstas en sus normas internas, así como en la legislación laboral vigente.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">NOVENA:		JORNADA DE TRABAJO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De conformidad con lo establecido en el artículo 1 del Decreto Supremo No. 007-2002-TR, EL TRABAJADOR prestará sus servicios dentro de la jornada laboral semanal de cuarenta y ocho (48) horas semanales en los horarios y turnos que oportunamente se le informen en atención a las necesidades del servicio y actividades comerciales.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Sin embargo, si LA EMPRESA lo considera puede permitir que EL TRABAJADOR labore menos de la jornada laboral referida en el párrafo anterior sin menoscabo de su remuneración; sin que ello signifique reducción, cambio o modificación de la jornada laboral de cuarenta y ocho (48) horas semanales pactada por ambas partes.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En tal sentido, de requerirlo LA EMPRESA, en caso de necesidades del servicio puede solicitar a EL TRABAJADOR el cumplimiento de la jornada laboral de cuarenta y ocho (48) horas semanales pactada en el presente contrato, aun cuando EL TRABAJADOR se encuentre laborando en una jornada inferior.   Dicha extensión no originará el pago de horas extras o compensación remunerativa adicional alguna. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Queda establecido que el tiempo destinado por EL TRABAJADOR para su refrigerio no forma parte de la jornada de trabajo.Asimismo, EL TRABAJADOR y LA EMPRESA acuerdan que en caso se desarrollen labores en sobre tiempo, estas podrán ser pagadas con la sobre tasa de ley o en su defecto y por decisión de LA EMPRESA, compensadas con horas o días de descanso; ocurriendo lo mismo en caso EL TRABAJADOR desarrolle labores en su día de descanso semanal obligatorio y en feriados no laborables.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">No obstante el horario indicado en la presente cláusula, de conformidad con lo dispuesto por la Ley No.27671, que modifica la Ley de Jornada de Trabajo, Horario y Trabajo en Sobretiempo, queda expresamente establecido que EL CONTRATADO deberá prestar servicios en horas extras de manera obligatoria, en los casos en que su labor resulte indispensable a consecuencia de un hecho fortuito o fuerza mayor que ponga en peligro inminente a las personas o los bienes del centro de trabajo o la continuidad de la actividad productiva.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMA:		SUSPENSIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La suspensión del presente contrato, por alguna de las causas previstas en el artículo 12 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, no modificará ni alterará el plazo de vigencia del mismo.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO PRIMERA:   CONCLUSIÓN DEL CONTRATO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Queda entendido que LA EMPRESA no está obligada a dar aviso adicional referente al término del presente contrato, operando su vencimiento en forma automática en la fecha señalada para tal efecto en la cláusula tercera, oportunidad en la cual se abonará a EL TRABAJADOR los beneficios sociales que pudieran corresponderle.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, el contrato podrá concluir en cualquiera de los supuestos señalados en los artículos 23 y 24 del Decreto Supremo No. 003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Finalmente, las partes acuerdan que el presente contrato se extinguirá también en caso de renuncia del contratado o mutuo disenso laboral entre las partes. </span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO SEGUNDA:    DECLARACIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR declara expresamente a la suscripción del presente contrato que conoce el contenido del Reglamento Interno de Trabajo, así como del Manual de Organización y Funciones que rige en la empresa y aquellos implementos y artículos que deberá usar obligatoriamente en el desempeño de su labor.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Igualmente, EL TRABAJADOR declara con la suscripción de este contrato que autoriza a LA EMPRESA a efectuar el descuento respectivo de sus haberes y/o liquidación de beneficios sociales que le pueda corresponder, por los perjuicios económicos, daño a la imagen de la empresa, y cualquier otra contingencia que sufra la empresa producto del actuar negligente y/o imprudente de EL TRABAJADOR en el desempeño de sus funciones.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO TERCERA:    CUMPLIMIENTO DE LAS OBLIGACIONES</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR deberá cumplir durante el tiempo que dure el presente contrato, con todas y cada una de las obligaciones para las cuales fue contratado, según las necesidades de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR se obliga, del mismo modo, a mantener en secreto toda información que llegue a su conocimiento en relación a los negocios, actividades y recursos de LA EMPRESA, sus asociados, proveedores y/o clientes. Esta obligación subsistirá aun después de terminada la relación laboral y su incumplimiento origina la correspondiente responsabilidad por daños y perjuicios, sin perjuicio de la persecución penal por el delito de violación del secreto profesional previsto en el artículo 165 del actual Código Penal. <br>Igualmente, las partes acuerdan que son obligaciones de EL TRABAJADOR las señaladas a continuación:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">a) Cumplir estrictamente con cada una de las recomendaciones y disposiciones señaladas en el Reglamento Interno de Trabajo, Manual de Organización y Funciones y/o en el Reglamento de Seguridad y Salud en el Trabajo de ser el caso, que le han sido entregados por LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>

	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">b) Usar obligatoriamente con diligencia y responsabilidad los equipos, útiles e implementos para la realización de su labor que le son entregados por LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	</page>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="15mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">c) Declarar bajo su propia responsabilidad a LA EMPRESA si ha pertenecido anteriormente la ONP o AFP, así como indicar expresamente cual son sus derechohabientes y/o asegurados, firmando una declaración jurada para tal efecto. Cualquier error, impresión, falsedad u omisión en dichas declaraciones es de entera responsabilidad de EL TRABAJADOR.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">d) Someterse obligatoriamente a pasar los exámenes médicos correspondientes que señale la ley, así como aquellos que sean necesarios para su seguridad a solicitud de EL TRABAJADOR, y que puedan ser realizadas por entidades aseguradoras privadas, además de aquellos exámenes médicos que se necesiten al culminar la relación laboral, bajo pena de extinción del contrato de trabajo y/o la obligación de resarcimiento del daño económico que pueda sufrir LA EMPRESA por dichas omisiones.Asimismo, ambas partes declaran que LA EMPRESA se exonerará de responsabilidad en caso EL TRABAJADOR no cumpla, de ser necesario, con someterse a los exámenes médicos correspondientes al finalizar su relación laboral a pesar del requerimiento de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">e) Suscribir y firmar las boletas de pago en las fechas programadas por LA EMPRESA para la percepción de sus haberes.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">f) Acatar los destaques a filiales o sucursales de LA EMPRESA que se realicen por necesidades del servicio y/o condición de trabajo, de acuerdo a la facultad directriz del empleador establecida en el artículo 9 del Decreto Supremo No.003-97-TR.</span>

           	</td>

           	
        </tr>

	</table>
	

	
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO CUARTA:   RÉGIMEN LABORAL</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR estará sujeto al régimen laboral de la actividad privada dentro de los alcances y efectos que determina el Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, su Reglamento aprobado por Decreto Supremo No.001-96-TR, y las demás normas modificatorias o ampliatorias para los trabajadores sujetos a contratos bajo modalidad.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO QUINTA:   COMUNICACIONES</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Los domicilios de las partes serán los que se indican en la introducción de este documento, acordándose que estos solo podrán variarse, previa comunicación escrita cursada a la otra parte con una anticipación de cinco (05) días útiles, a la fecha de la variación efectiva. Al domicilio indicado deberán cursarse todas las comunicaciones relacionadas al presente contrato. En caso no exista comunicación alguna respecto al cambio de domicilio o dicho cambio se notifica a la otra parte sin la anticipación indicada en esta cláusula, se entiende por válidas las comunicaciones cursadas al domicilio inicialmente consignado entre las mismas.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO SEXTA:    CONOCIMIENTO Y REGISTRO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">El presente contrato será presentado para su conocimiento y registro ante la Autoridad Administrativa de Trabajo, de acuerdo a lo dispuesto por el artículo 73 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO SÉTIMA:   JURISDICCIÓN Y COMPETENCIA</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Ambas partes contratantes renuncian expresamente al fuero de sus domicilios y se someten a la Jurisdicción y Competencia de los Jueces y Tribunales de Lima respecto a los términos y condiciones derivados del presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO OCTAVA:         PROTECCIÓN DE DATOS PERSONALES</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR otorga a LA EMPRESA, su consentimiento libre, previo, expreso, inequívoco e informado para que pueda recopilar, registrar, organizar, almacenar, conservar, elaborar, modificar, bloquear, suprimir, extraer, consultar, utilizar, transferir, exportar, importar o tratar de cualquier otra forma conforme a Ley (por sí mismo o a través de terceros) sus datos personales, los cuales serán incluidos en Bancos de Datos Personales de titularidad y responsabilidad de LA EMPRESA.<br>Estos datos personales los puede proporcionar EL TRABAJADOR directamente o LA EMPRESA, los puede generar u obtener a través de terceros para ser tratados con la finalidad de:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">i) Realizar las actividades relacionadas con la prestación de sus servicios, y la ejecución de sus labores y/o</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">ii)  Enviarle ofertas comerciales, publicidad e información en general de LA EMPRESA y/o terceras vinculadas o no vinculadas; y/o cualquier otra empresa que pertenezca o que pueda pertenecer en el futuro al GRUPO TAI LOY, ya sea domiciliada o no en el país, y/o</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">iii) Ofrecerle productos y/o servicios en forma directa, a través de terceros y/o mediante asociaciones comerciales</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">iv)  Obtener información estadística y/o histórica para LA EMPRESA y/o cualquier otra empresa que pertenezca o que pueda pertenecer en el futuro al GRUPO TAI LOY.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">v) Utilizar sus datos personales en materiales de carácter institucional de LA EMPRESA., tales como videos, material impreso, entre otros.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Esta autorización es indefinida y estará vigente inclusive después del vencimiento del presente contrato. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA. se reserva el derecho de poder compartir y/o usar y/o almacenar y/o transferir la información a terceras personas vinculadas o no, sean estos socios comerciales o no de LA EMPRESA., nacionales o extranjeros, públicos o privados con el objeto de realizar actividades relacionadas al cumplimiento de las finalidades indicadas anteriormente.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA declara que ha adoptado los niveles de seguridad apropiados para el resguardo de la información, respetando las medidas de seguridad técnica aplicables a cada categoría y tipo de tratamiento de Bancos de Datos personales; asimismo, declara que respeta los principios de legalidad, consentimiento, finalidad, proporcionalidad, calidad, disposición de recurso, nivel de protección adecuado, conforme a las disposiciones de la Ley de Protección de Datos Personales vigente en Perú.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR se obliga a cumplir con las políticas y los lineamientos dictados por LA EMPRESA en el marco de lo dispuesto por la Ley de Protección de Datos Personales, su reglamento y demás normas conexas. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR, declara haber sido informado de que en caso no otorgue este consentimiento, sus datos personales solo serán utilizados y/o tratados específicamente para el cumplimiento de los fines vinculados con la prestación de sus servicios y la ejecución de sus labores, en el marco del presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR declara haber sido informado que podrá ejercer en cualquier momento sus derechos de información, acceso, rectificación, cancelación y oposición de sus datos de acuerdo a lo dispuesto por la Ley de Protección de Datos Personales vigente y su reglamento. Para ello efectuará su solicitud en protecciondedatos@tailoy.com.pe.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO NOVENA:           POLÍTICA ANTISOBORNO, FRAUDE Y ANTICORRUPCIÓN</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes declaran que, de conformidad con lo dispuesto en la Ley N° 30424 y su modificatoria prevista en el Decreto Legislativo N° 1352 (ambas leyes que regulan la responsabilidad administrativa de las personas jurídicas frente a los delitos de Cohecho Activo (Genérico, Especifico e Internacional), Lavado de Activos y Financiamiento del terrorismo); en concordancia con lo previsto en el Artículo 17º.- Control y supervisión del cumplimiento de normas del Decreto Supremo Nº 018-2006-JUS, Reglamento de la Ley 27693, sobre Normas Complementarias para la Prevención del Lavado de Activos y del Financiamiento del Terrorismo; y, la Ley Nº 27765 (Ley Penal Contra el Lavado de Activos), en carácter de Declaración Jurada: que los recursos, fondos, dineros, activos, bienes o servicios relacionados y movilizados para el presente contrato provienen de actividades licitas y no están vinculados con el lavado de activos ni con ninguno de sus delitos fuente, además que el destino de los recursos, fondos, dineros, activos, bienes o servicios que se generen del presente contrato celebrado no van a ser destinados ni movilizados para la financiación del terrorismo ni con ninguno de sus delitos fuente, o cualquier otra conducta delictiva como el cohecho activo en cualquiera de sus modalidades, acorde a las normas penales peruanas vigentes.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">VIGÉSIMA:            POLÍTICA ANTISOBORNO, FRAUDE Y ANTICORRUPCIÓN</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA tiene como política empresarial contratar únicamente con personas naturales y jurídicas que cumplan con las leyes, reglamentos y     requisitos administrativos aplicables al presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En virtud de lo anterior, LA EMPRESA exige que tanto las personas naturales como jurídicas tengan los más altos niveles éticos, en las etapas de suscripción y ejecución del presente contrato. Por lo que, en forma expresa EL TRABAJADOR se obliga:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(i)  A no participar en actos de corrupción y/o soborno que puedan involucrar a LA EMPRESA o que puedan ser considerados que brindan un beneficio ilegítimo a LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(ii)  A no realizar pagos de facilitación por encargo </span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(iii)  A informar cualquier conducta desleal o propuesta por parte de algún colaborador de LA EMPRESA que no se encuentre alineado a la presente política.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
       <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(iv) Acepta que en caso se demuestre que ha incurrido en una conducta impropia o que haya incumplido normas aplicables respecto a anticorrupción, LA EMPRESA podrá resolver el contrato unilateralmente sin necesidad de aviso previo.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(v)  Asegura no ser un funcionario público ni estar relacionado con alguno hasta el segundo grado de consanguineidad; en caso se encuentre en alguno de estos casos, deberá informarlo inmediatamente a LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(vi) Acepta que en caso realice algún acto en contra de lo dispuesto en las normas contra la corrupción y soborno cumplirá con pagar la indemnización correspondiente.</span>

           	</td>

           	
        </tr>

	</table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">La intención de las partes es que no se efectúen pagos ni se realicen entregas de objetos de valor que puedan tener la finalidad o el efecto de cohechar a un funcionario público o sobornar a una empresa privada o aceptar o permitir exacciones ilegales, comisiones indebidas u otros medios ilícitos o irregulares de obtener negocios.</span>

            </td>

            
        </tr>

  </table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">En cualquier caso, quedará a salvo el pago de los honorarios mensuales o proporcionales pendientes por la asesoría brindada a la empresa hasta el momento de la resolución.</span>

            </td>

            
        </tr>

  </table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">VIGESIMO PRIMERA:               AUTORIZACIÓN DE ACCESO A HERRAMIENTAS DE TRABAJO</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La presente Cláusula tiene por objeto establecer la autorización y consentimiento previo, informado, expreso e incondicional de EL TRABAJADOR a favor de LA EMPRESA para poder acceder a toda y cualquier información como correos electrónicos, páginas de acceso virtual y telefónica que se encuentre en las herramientas o equipos de trabajo y demás información de carácter personal o sensible que se encuentren en equipos o herramientas de trabajo sea computadoras, laptops, celulares, móviles u otros entregados por LA EMPRESA  a  EL TRABAJADOR para el desarrollo de sus labores, acciones comerciales, incluyendo la remisión, directa o por intermedio de terceros (vía medio físico, electrónico o telefónico) de publicidad, información, obsequios, ofertas y/o promociones (personalizadas o generales) de productos y/o servicios de LA EMPRESA y/o de otras compañías vinculadas, así como en el portal http://www1.tailoy.com.pe/servicio-online/index.php</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">VIGESIMO SEGUNDA:            SISTEMAS DE INFORMACIÓN E INTRANET </span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR reconoce que los sistemas relacionados con la Intranet, Internet, Software o aplicaciones, Sistemas Operativos, medios de almacenamiento y Correo Electrónico a las cuales accede en el ejercicio de su labor, son de propiedad exclusiva de  LA EMPRESA y/o de sus clientes, razón por la cual se compromete a conocer y realizar el acceso respectivo y utilizar dichos sistemas con fines exclusivamente laborales y para servir a los intereses de LA EMPRESA, conforme lo establecen las normas vigentes que forman parte del presente contrato y que EL TRABAJADOR se compromete a cumplir.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"> En señal de conformidad y aceptación, ambas partes firman el presente documento en dos (02) ejemplares de igual tenor y valor a los <?php echo $diagen.' días del mes de '.$mesg.' de '.$aniogen ?>.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br><br><br><br><br>
	<br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>HUARCAYA SILVA MANUEL ENRIQUE</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $nom_postu ?></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>DNI. 06225424</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $tipo_documento ?>. <?php echo $dni_postulante ?></span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>EL TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>
</page>

<!--CONTRATO DE TRABAJO-->
<page backtop="15mm" backbottom="15mm" backleft="10mm" backright="10mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 17%; color: black;font-size:10px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-weight:bold;text-decoration: underline black;">CONTRATO DE TRABAJO TEMPORAL POR INCREMENTO DE ACTIVIDADES</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Conste en el presente documento, el CONTRATO DE TRABAJO DE NATURALEZA TEMPORAL POR INCREMENTO DE ACTIVIDADES que de conformidad con lo dispuesto en el artículo 57º del TUO del Decreto Legislativo Nº 728º, celebran:<br>De una parte, como LA EMPRESA:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LIBRERIA BAZAR SANTA MARIA EIRL, con R.U.C. No 20208752759, con domicilio en CAL.REAL NRO. 307 (ENTRE 2 DE MAYO Y 13 DE NOVIEMBRE) EL TAMBO, Provincia Huancayo y Departamento de Junín, debidamente representado por el Representante Legal Sr. Manuel Enrique Huarcaya Silva, identificado con DNI N° 06225424</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De la otra parte, como EL TRABAJADOR:<br> El (la) señor(a) <?php echo $nom_postu ?>, identificado(a) con <?php echo $tipo_documento ?> NRO. <?php echo $dni_postulante ?> con domicilio en <?php echo $direccion_postu?>, <?php echo $distrito_postu ?>.  En los términos y condiciones establecidos en las cláusulas siguientes:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">PRIMERA: 		ANTECEDENTES Y CAUSA OBJETIVA DE CONTRATACIÓN</span>

           	</td>

           	
        </tr>

	</table>
	


    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA es una persona jurídica de derecho privado, dedicada a realizar los servicios de tiene como actividad la venta de papeles pre impresos para computo, suministro de computadoras, accesorios, equipos diversos y servicios profesionales de mantenimiento, reparación relacionados con la línea informática, así como brindar servicios de fotocopiadora en general. 
</span>

           	</td>

           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR, reúne las condiciones, la experiencia necesaria y el perfil deseado por LA EMPRESA para que desempeñe sus labores en calidad de <?php echo $puesto_postu ?>.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">SEGUNDA:  CAUSA OBJETIVA QUE JUSTIFICA LA CONTRATACIÓN</span>

           	</td>

           	
        </tr>

	</table>
  <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">La causa objetiva que justifica la presente contratación es la adquisición de la empresa con fecha 16 DE SETIEMBRE 2019  por parte del GRUPO TAI LOY con un aporte de capital importante que se refleja en sus estados financieros de segundo semestre del año 2019 ha originado el incremento de sus actividades empresariales, debido al crecimiento comercial de la empresa.  En tal sentido, como consecuencia de dicha adquisición e incremento empresarial, así como del volumen de ventas a futuro se origina a su vez la necesidad de contratación, temporal, puesto que las actividades requeridas no pueden ser atendida con personal actual de LA EMPRESA. </span>

            </td>

            
        </tr>

  </table>
  <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">En virtud de lo indicado, se requiere cubrir temporalmente las necesidades de recursos humanos originadas por el incremento de sus actividades indicadas, las cuales deben ser atendidas de forma inmediata para su ordenado y adecuado funcionamiento y desarrollo, por lo que se configura el supuesto previsto en el artículo 57 del Decreto Supremo No.003-97-TR, Ley de Productividad y Competitividad Laboral.</span>

            </td>

            
        </tr>

  </table>
  
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">TERCERA:   DE LOS SERVICIOS CONTRATADOS POR INCREMENTO DE LA ACTIVIDAD</span>

           	</td>

           	
        </tr>

	</table>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">En consideración a las causa objetiva de contratación señalada en la cláusula precedente y al amparo de lo dispuesto por el artículo 57 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, se contrata a plazo fijo bajo la modalidad de incremento de la actividad empresarial los servicios de EL TRABAJADOR para que en calidad de empleado ocupe el cargo de <?php echo $puesto_postu ?> en el centro de trabajo de la empresa ubicado en <?php echo $direccion_tienda_postu ?>, estando obligado a desempeñar las labores propias de su cargo y las que oportunamente se le indiquen, de acuerdo a las normas y lineamientos establecidos en el Reglamento Interno de Trabajo y Directivas que imparta LA EMPRESA.</span>

            </td>

            
        </tr>

  </table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">Las partes acuerdan voluntariamente que la prestación del servicio que EL TRABAJADOR va a desarrollar en virtud del presente Contrato se extiende mientras dure su vínculo laboral a favor de cualquier otra compañía presente o futura que se encuentre vinculada económicamente y  empresarialmente en forma directa o indirecta con LA EMPRESA y/o con las demás empresas mencionadas en el párrafo anterior, sea porque desarrollan actividades similares como la venta de juguetería, artículos de librería o afines, etc.; o porque su objeto social es importante para coadyuvar al desarrollo de la actividad empresarial de LA EMPRESA y sus vinculadas.</span>

            </td>

            
        </tr>

  </table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">Asimismo, son funciones generales y específicas propias del cargo de <?php echo $puesto_postu ?>  a favor de LA EMPRESA y sus compañías vinculadas, las funciones a cargo de EL TRABAJADOR que se detallan en el Manual de Organización de Funciones (MOF) que son de conocimiento de EL TRABAJADOR.</span>

            </td>

            
        </tr>

  </table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">En tal sentido, las partes acuerdan que el cargo de EL TRABAJADOR se desarrollará a plazo fijo y bajo subordinación a cambio de la remuneración convenida en la cláusula sexta de este contrato.  </span>

            </td>

            
        </tr>

  </table>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;font-weight:bold;text-decoration: underline black;">CUARTA:   PLAZO</span>

            </td>

            
        </tr>

  </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">El plazo de vigencia del presente contrato es de  <?php echo $tiempo_contrato ?>, siendo su fecha de <?php echo $fecha_ingreso_letras ?> debiendo concluir el <?php echo $fecha_fin_letras ?>, fecha en que quedará extinguido el presente contrato, de no mediar acuerdo para la renovación del mismo entre las partes.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Al término del contrato EL TRABAJADOR gozará de todos los derechos y beneficios que contempla el artículo 79 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">QUINTA:		PERIODO DE PRUEBA.</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De acuerdo a lo dispuesto por los artículos 10 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, el presente contrato estará sometido al periodo de prueba de <?php echo $periodo_prueba ?> MESES de acuerdo a ley. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">SEXTA:   DE LA PRÓRROGA O RENOVACIÓN DEL CONTRATO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes podrán prorrogar o renovar el presente contrato hasta alcanzar el máximo legal de cinco (05) años previsto en el artículo 74º del Decreto Supremo No.003-97-TR, Ley de Productividad y Competitividad Laboral. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">SÉTIMA:    SUBORDINACIÓN</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Ambas partes acuerdan que, mientras dure la relación laboral derivada del presente contrato, EL TRABAJADOR se encuentra obligado a prestar los servicios descritos en la cláusula segunda, bajo dirección y subordinación de la Gerencia General de LA EMPRESA. </span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Para este efecto, EL TRABAJADOR se obliga a cumplir con las normas propias del Centro de Trabajo, así como las contenidas en el Manual de Organización y Funciones, y/o en el Reglamento de Seguridad y Salud en el Trabajo de ser el caso y aquellas que se impartan por necesidades del servicio en ejercicio de las facultades de Administración que LA EMPRESA tiene de acuerdo a lo establecido por el artículo 9 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
</page>
<page backtop="15mm" backbottom="15mm" backleft="10mm" backright="10mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">OCTAVA:		REMUNERACIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA abonará a EL TRABAJADOR por sus servicios prestados una remuneración mensual de S/. <?php echo $salario_postu ?>.00( <?php echo $salario_letras_postu ?>). </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, EL TRABAJADOR podrá percibir también comisiones y/o bonificaciones por la venta efectivamente realizada de los productos de LA EMPRESA debido a su gestión, conforme a los términos y condiciones que establezca LA EMPRESA en cada oportunidad. Las partes acuerdan que las comisiones y/o bonificaciones que pudiesen cancelarse en cada oportunidad se devengarán con periodicidad mensual.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">A las comisiones y/o bonificaciones que pueda percibir EL TRABAJADOR le serán de aplicación los descuentos y deducciones por aportaciones y contribuciones sociales establecidas por ley.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes acuerdan que las comisiones y/o bonificaciones que pueda percibir EL TRABAJADOR serán canceladas por LA EMPRESA siempre y cuando EL TRABAJADOR forme parte del personal que pertenezca a los puestos que participen en la política de comisiones y/o bonificaciones que LA EMPRESA haya establecido expresamente con antelación a la percepción de dicho beneficio. </span>

           	</td>

           	
        </tr>

	</table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">En caso EL TRABAJADOR no llegue a la Remuneración Mínima Vital (RMV) LA EMPRESA completará el saldo hasta llegar a dicho monto. </span>

            </td>

            
        </tr>

  </table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, queda expresamente establecido que las ausencias injustificadas por parte de EL TRABAJADOR implican la pérdida de la remuneración proporcionalmente a la duración de dicha ausencia, sin perjuicio del ejercicio de las facultades disciplinarias propias de LA EMPRESA previstas en sus normas internas, así como en la legislación laboral vigente.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">NOVENA:		JORNADA DE TRABAJO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">De conformidad con lo establecido en el artículo 1 del Decreto Supremo No. 007-2002-TR, EL TRABAJADOR prestará sus servicios dentro de la jornada laboral semanal de cuarenta y ocho (48) horas semanales en los horarios y turnos que oportunamente se le informen en atención a las necesidades del servicio y actividades comerciales.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Sin embargo, si LA EMPRESA lo considera puede permitir que EL TRABAJADOR labore menos de la jornada laboral referida en el párrafo anterior sin menoscabo de su remuneración; sin que ello signifique reducción, cambio o modificación de la jornada laboral de cuarenta y ocho (48) horas semanales pactada por ambas partes.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En tal sentido, de requerirlo LA EMPRESA, en caso de necesidades del servicio puede solicitar a EL TRABAJADOR el cumplimiento de la jornada laboral de cuarenta y ocho (48) horas semanales pactada en el presente contrato, aun cuando EL TRABAJADOR se encuentre laborando en una jornada inferior.   Dicha extensión no originará el pago de horas extras o compensación remunerativa adicional alguna. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Queda establecido que el tiempo destinado por EL TRABAJADOR para su refrigerio no forma parte de la jornada de trabajo.Asimismo, EL TRABAJADOR y LA EMPRESA acuerdan que en caso se desarrollen labores en sobre tiempo, estas podrán ser pagadas con la sobre tasa de ley o en su defecto y por decisión de LA EMPRESA, compensadas con horas o días de descanso; ocurriendo lo mismo en caso EL TRABAJADOR desarrolle labores en su día de descanso semanal obligatorio y en feriados no laborables.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">No obstante el horario indicado en la presente cláusula, de conformidad con lo dispuesto por la Ley No.27671, que modifica la Ley de Jornada de Trabajo, Horario y Trabajo en Sobretiempo, queda expresamente establecido que EL CONTRATADO deberá prestar servicios en horas extras de manera obligatoria, en los casos en que su labor resulte indispensable a consecuencia de un hecho fortuito o fuerza mayor que ponga en peligro inminente a las personas o los bienes del centro de trabajo o la continuidad de la actividad productiva.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMA:		SUSPENSIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La suspensión del presente contrato, por alguna de las causas previstas en el artículo 12 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, no modificará ni alterará el plazo de vigencia del mismo.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO PRIMERA:   CONCLUSIÓN DEL CONTRATO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Queda entendido que LA EMPRESA no está obligada a dar aviso adicional referente al término del presente contrato, operando su vencimiento en forma automática en la fecha señalada para tal efecto en la cláusula tercera, oportunidad en la cual se abonará a EL TRABAJADOR los beneficios sociales que pudieran corresponderle.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Asimismo, el contrato podrá concluir en cualquiera de los supuestos señalados en los artículos 23 y 24 del Decreto Supremo No. 003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Finalmente, las partes acuerdan que el presente contrato se extinguirá también en caso de renuncia del contratado o mutuo disenso laboral entre las partes. </span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO SEGUNDA:    DECLARACIÓN</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR declara expresamente a la suscripción del presente contrato que conoce el contenido del Reglamento Interno de Trabajo, así como del Manual de Organización y Funciones que rige en la empresa y aquellos implementos y artículos que deberá usar obligatoriamente en el desempeño de su labor.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Igualmente, EL TRABAJADOR declara con la suscripción de este contrato que autoriza a LA EMPRESA a efectuar el descuento respectivo de sus haberes y/o liquidación de beneficios sociales que le pueda corresponder, por los perjuicios económicos, daño a la imagen de la empresa, y cualquier otra contingencia que sufra la empresa producto del actuar negligente y/o imprudente de EL TRABAJADOR en el desempeño de sus funciones.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO TERCERA:    CUMPLIMIENTO DE LAS OBLIGACIONES</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR deberá cumplir durante el tiempo que dure el presente contrato, con todas y cada una de las obligaciones para las cuales fue contratado, según las necesidades de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR se obliga, del mismo modo, a mantener en secreto toda información que llegue a su conocimiento en relación a los negocios, actividades y recursos de LA EMPRESA, sus asociados, proveedores y/o clientes. Esta obligación subsistirá aun después de terminada la relación laboral y su incumplimiento origina la correspondiente responsabilidad por daños y perjuicios, sin perjuicio de la persecución penal por el delito de violación del secreto profesional previsto en el artículo 165 del actual Código Penal. <br>Igualmente, las partes acuerdan que son obligaciones de EL TRABAJADOR las señaladas a continuación:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">a) Cumplir estrictamente con cada una de las recomendaciones y disposiciones señaladas en el Reglamento Interno de Trabajo, Manual de Organización y Funciones y/o en el Reglamento de Seguridad y Salud en el Trabajo de ser el caso, que le han sido entregados por LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>

	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">b) Usar obligatoriamente con diligencia y responsabilidad los equipos, útiles e implementos para la realización de su labor que le son entregados por LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	</page>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="15mm" style="font-size: 7pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">c) Declarar bajo su propia responsabilidad a LA EMPRESA si ha pertenecido anteriormente la ONP o AFP, así como indicar expresamente cual son sus derechohabientes y/o asegurados, firmando una declaración jurada para tal efecto. Cualquier error, impresión, falsedad u omisión en dichas declaraciones es de entera responsabilidad de EL TRABAJADOR.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">d) Someterse obligatoriamente a pasar los exámenes médicos correspondientes que señale la ley, así como aquellos que sean necesarios para su seguridad a solicitud de EL TRABAJADOR, y que puedan ser realizadas por entidades aseguradoras privadas, además de aquellos exámenes médicos que se necesiten al culminar la relación laboral, bajo pena de extinción del contrato de trabajo y/o la obligación de resarcimiento del daño económico que pueda sufrir LA EMPRESA por dichas omisiones.Asimismo, ambas partes declaran que LA EMPRESA se exonerará de responsabilidad en caso EL TRABAJADOR no cumpla, de ser necesario, con someterse a los exámenes médicos correspondientes al finalizar su relación laboral a pesar del requerimiento de LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">e) Suscribir y firmar las boletas de pago en las fechas programadas por LA EMPRESA para la percepción de sus haberes.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">f) Acatar los destaques a filiales o sucursales de LA EMPRESA que se realicen por necesidades del servicio y/o condición de trabajo, de acuerdo a la facultad directriz del empleador establecida en el artículo 9 del Decreto Supremo No.003-97-TR.</span>

           	</td>

           	
        </tr>

	</table>
	

	
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO CUARTA:   RÉGIMEN LABORAL</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR estará sujeto al régimen laboral de la actividad privada dentro de los alcances y efectos que determina el Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral, su Reglamento aprobado por Decreto Supremo No.001-96-TR, y las demás normas modificatorias o ampliatorias para los trabajadores sujetos a contratos bajo modalidad.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO QUINTA:   COMUNICACIONES</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Los domicilios de las partes serán los que se indican en la introducción de este documento, acordándose que estos solo podrán variarse, previa comunicación escrita cursada a la otra parte con una anticipación de cinco (05) días útiles, a la fecha de la variación efectiva. Al domicilio indicado deberán cursarse todas las comunicaciones relacionadas al presente contrato. En caso no exista comunicación alguna respecto al cambio de domicilio o dicho cambio se notifica a la otra parte sin la anticipación indicada en esta cláusula, se entiende por válidas las comunicaciones cursadas al domicilio inicialmente consignado entre las mismas.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO SEXTA:    CONOCIMIENTO Y REGISTRO</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">El presente contrato será presentado para su conocimiento y registro ante la Autoridad Administrativa de Trabajo, de acuerdo a lo dispuesto por el artículo 73 del Decreto Supremo No.003-97-TR, Texto Único Ordenado del Decreto Legislativo No.728, Ley de Productividad y Competitividad Laboral.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO SÉTIMA:   JURISDICCIÓN Y COMPETENCIA</span>

           	</td>

           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Ambas partes contratantes renuncian expresamente al fuero de sus domicilios y se someten a la Jurisdicción y Competencia de los Jueces y Tribunales de Lima respecto a los términos y condiciones derivados del presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO OCTAVA:         PROTECCIÓN DE DATOS PERSONALES</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR otorga a LA EMPRESA, su consentimiento libre, previo, expreso, inequívoco e informado para que pueda recopilar, registrar, organizar, almacenar, conservar, elaborar, modificar, bloquear, suprimir, extraer, consultar, utilizar, transferir, exportar, importar o tratar de cualquier otra forma conforme a Ley (por sí mismo o a través de terceros) sus datos personales, los cuales serán incluidos en Bancos de Datos Personales de titularidad y responsabilidad de LA EMPRESA.<br>Estos datos personales los puede proporcionar EL TRABAJADOR directamente o LA EMPRESA, los puede generar u obtener a través de terceros para ser tratados con la finalidad de:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">i) Realizar las actividades relacionadas con la prestación de sus servicios, y la ejecución de sus labores y/o</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">ii)  Enviarle ofertas comerciales, publicidad e información en general de LA EMPRESA y/o terceras vinculadas o no vinculadas; y/o cualquier otra empresa que pertenezca o que pueda pertenecer en el futuro al GRUPO TAI LOY, ya sea domiciliada o no en el país, y/o</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">iii) Ofrecerle productos y/o servicios en forma directa, a través de terceros y/o mediante asociaciones comerciales</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">iv)  Obtener información estadística y/o histórica para LA EMPRESA y/o cualquier otra empresa que pertenezca o que pueda pertenecer en el futuro al GRUPO TAI LOY.</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">v) Utilizar sus datos personales en materiales de carácter institucional de LA EMPRESA., tales como videos, material impreso, entre otros.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Esta autorización es indefinida y estará vigente inclusive después del vencimiento del presente contrato. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA. se reserva el derecho de poder compartir y/o usar y/o almacenar y/o transferir la información a terceras personas vinculadas o no, sean estos socios comerciales o no de LA EMPRESA., nacionales o extranjeros, públicos o privados con el objeto de realizar actividades relacionadas al cumplimiento de las finalidades indicadas anteriormente.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA declara que ha adoptado los niveles de seguridad apropiados para el resguardo de la información, respetando las medidas de seguridad técnica aplicables a cada categoría y tipo de tratamiento de Bancos de Datos personales; asimismo, declara que respeta los principios de legalidad, consentimiento, finalidad, proporcionalidad, calidad, disposición de recurso, nivel de protección adecuado, conforme a las disposiciones de la Ley de Protección de Datos Personales vigente en Perú.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR se obliga a cumplir con las políticas y los lineamientos dictados por LA EMPRESA en el marco de lo dispuesto por la Ley de Protección de Datos Personales, su reglamento y demás normas conexas. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR, declara haber sido informado de que en caso no otorgue este consentimiento, sus datos personales solo serán utilizados y/o tratados específicamente para el cumplimiento de los fines vinculados con la prestación de sus servicios y la ejecución de sus labores, en el marco del presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR declara haber sido informado que podrá ejercer en cualquier momento sus derechos de información, acceso, rectificación, cancelación y oposición de sus datos de acuerdo a lo dispuesto por la Ley de Protección de Datos Personales vigente y su reglamento. Para ello efectuará su solicitud en protecciondedatos@tailoy.com.pe.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">DÉCIMO NOVENA:           PREVENCIÓN DE LAVADO DE ACTIVOS, DELITOS DE COHECHO Y FINANCIACIÓN DEL TERRORISMO</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">Las partes declaran que, de conformidad con lo dispuesto en la Ley N° 30424 y su modificatoria prevista en el Decreto Legislativo N° 1352 (ambas leyes que regulan la responsabilidad administrativa de las personas jurídicas frente a los delitos de Cohecho Activo (Genérico, Especifico e Internacional), Lavado de Activos y Financiamiento del terrorismo); en concordancia con lo previsto en el Artículo 17º.- Control y supervisión del cumplimiento de normas del Decreto Supremo Nº 018-2006-JUS, Reglamento de la Ley 27693, sobre Normas Complementarias para la Prevención del Lavado de Activos y del Financiamiento del Terrorismo; y, la Ley Nº 27765 (Ley Penal Contra el Lavado de Activos), en carácter de Declaración Jurada: que los recursos, fondos, dineros, activos, bienes o servicios relacionados y movilizados para el presente contrato provienen de actividades licitas y no están vinculados con el lavado de activos ni con ninguno de sus delitos fuente, además que el destino de los recursos, fondos, dineros, activos, bienes o servicios que se generen del presente contrato celebrado no van a ser destinados ni movilizados para la financiación del terrorismo ni con ninguno de sus delitos fuente, o cualquier otra conducta delictiva como el cohecho activo en cualquiera de sus modalidades, acorde a las normas penales peruanas vigentes.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">VIGÉSIMA:            POLÍTICA ANTISOBORNO, FRAUDE Y ANTICORRUPCIÓN</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">LA EMPRESA tiene como política empresarial contratar únicamente con personas naturales y jurídicas que cumplan con las leyes, reglamentos y     requisitos administrativos aplicables al presente contrato.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">En virtud de lo anterior, LA EMPRESA exige que tanto las personas naturales como jurídicas tengan los más altos niveles éticos, en las etapas de suscripción y ejecución del presente contrato. Por lo que, en forma expresa EL TRABAJADOR se obliga:</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(i)  A no participar en actos de corrupción y/o soborno que puedan involucrar a LA EMPRESA o que puedan ser considerados que brindan un beneficio ilegítimo a LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(ii)  A no realizar pagos de facilitación por encargo </span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(iii)  A informar cualquier conducta desleal o propuesta por parte de algún colaborador de LA EMPRESA que no se encuentre alineado a la presente política.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
       <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(iv) Acepta que en caso se demuestre que ha incurrido en una conducta impropia o que haya incumplido normas aplicables respecto a anticorrupción, LA EMPRESA podrá resolver el contrato unilateralmente sin necesidad de aviso previo.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(v)  Asegura no ser un funcionario público ni estar relacionado con alguno hasta el segundo grado de consanguineidad; en caso se encuentre en alguno de estos casos, deberá informarlo inmediatamente a LA EMPRESA.</span>

           	</td>

           	
        </tr>

	</table>
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		   	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;">(vi) Acepta que en caso realice algún acto en contra de lo dispuesto en las normas contra la corrupción y soborno cumplirá con pagar la indemnización correspondiente.</span>

           	</td>

           	
        </tr>

	</table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">La intención de las partes es que no se efectúen pagos ni se realicen entregas de objetos de valor que puedan tener la finalidad o el efecto de cohechar a un funcionario público o sobornar a una empresa privada o aceptar o permitir exacciones ilegales, comisiones indebidas u otros medios ilícitos o irregulares de obtener negocios.</span>

            </td>

            
        </tr>

  </table>
  <br>
  <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        <td>
              <span style="line-height:15px;">En cualquier caso, quedará a salvo el pago de los honorarios mensuales o proporcionales pendientes por la asesoría brindada a la empresa hasta el momento de la resolución.</span>

            </td>

            
        </tr>

  </table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">VIGESIMO PRIMERA:               AUTORIZACIÓN DE ACCESO A HERRAMIENTAS DE TRABAJO</span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">La presente Cláusula tiene por objeto establecer la autorización y consentimiento previo, informado, expreso e incondicional de EL TRABAJADOR a favor de LA EMPRESA para poder acceder a toda y cualquier información como correos electrónicos, páginas de acceso virtual y telefónica que se encuentre en las herramientas o equipos de trabajo y demás información de carácter personal o sensible que se encuentren en equipos o herramientas de trabajo sea computadoras, laptops, celulares, móviles u otros entregados por LA EMPRESA  a  EL TRABAJADOR para el desarrollo de sus labores, acciones comerciales, incluyendo la remisión, directa o por intermedio de terceros (vía medio físico, electrónico o telefónico) de publicidad, información, obsequios, ofertas y/o promociones (personalizadas o generales) de productos y/o servicios de LA EMPRESA y/o de otras compañías vinculadas, así como en el portal http://www1.tailoy.com.pe/servicio-online/index.php</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;">VIGESIMO SEGUNDA:            SISTEMAS DE INFORMACIÓN E INTRANET </span>

           	</td>

           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;">EL TRABAJADOR reconoce que los sistemas relacionados con la Intranet, Internet, Software o aplicaciones, Sistemas Operativos, medios de almacenamiento y Correo Electrónico a las cuales accede en el ejercicio de su labor, son de propiedad exclusiva de  LA EMPRESA y/o de sus clientes, razón por la cual se compromete a conocer y realizar el acceso respectivo y utilizar dichos sistemas con fines exclusivamente laborales y para servir a los intereses de LA EMPRESA, conforme lo establecen las normas vigentes que forman parte del presente contrato y que EL TRABAJADOR se compromete a cumplir.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;"> En señal de conformidad y aceptación, ambas partes firman el presente documento en dos (02) ejemplares de igual tenor y valor a los <?php echo $diagen.' días del mes de '.$mesg.' de '.$aniogen ?>.</span>

           	</td>

           	
        </tr>

	</table>
	
	<br><br><br><br><br>
	<br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>HUARCAYA SILVA MANUEL ENRIQUE</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $nom_postu ?></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>DNI. 06225424</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span><?php echo $tipo_documento ?>. <?php echo $dni_postulante ?></span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
                <span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:9px;text-align:middle;font-weight:bold;">
				<span>EL TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>
</page>

<?php  
}
?>
<!--CONSTANCIA DE RECEPCION DE CONTRATO-->
<page backtop="20mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 30%; color: black;font-size:15px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;">CARTA DE AUTORIZACIÓN </span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:25px;font-size:12px;">Yo, <?php echo $nom_postu ?>, identifiicado con <?php echo $tipo_documento ?>. <?php echo $dni_postulante ?>, con domicilio en <?php echo $direccion_postu ?>, <?php echo $distrito_postu ?>, <?php echo $provincia_postu ?> desempeñándome como trabajador en el cargo de <?php echo $puesto_postu ?> del área de <?php echo $area_postu ?> de la empresa <?php echo $empresa_ruc ?> <br><br>Que en virtud del presente documento, autorizo voluntaria y expresamente a LA EMPRESA a remitir a mi correo personal <?php echo $correo_postu ?> la documentación referida a mis boletas de pago de remuneración mensual, remuneración vacacional, gratificaciones legales y demás información relevante de carácter laboral durante todo el tiempo que resulte necesario, mientras mantenga mi vínculo de trabajo vigente con LA EMPRESA. <br><br>Asimismo, declaro que he sido debidamente informado y capacitado por LA EMPRESA para poder acceder y visualizar toda la información señalada en el párrafo anterior en el link http://smartnet.tailoy.com.pe/SMARTnet_Web (a través del navegador internet Explorer) o http://www1.tailoy.com.pe/servicio-online/, teniendo mi propia clave de acceso virtual.<br><br>Finalmente, manifiesto en forma expresa e irrevocable que estoy conforme con todos y cada uno de los términos de la presente autorización, la que he realizado voluntariamente y sin que medie en su redacción, aceptación y/o suscripción acto alguno que obligue, contraríe, limite y/o restrinja mi libre manifestación de voluntad, por lo que expreso mi renuncia a cualquier acción que tenga o pudiera tener contra terceros y/o LA EMPRESA sobre todo lo que es materia del presente documento; pues el mismo no vulnera derechos adquiridos.</span>

           	</td>
           	
        </tr>

	</table>

	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>Firma y huella del trabajador</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span></span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span><?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?></span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span></span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>Recibido: <?php echo $fecha_ingreso_numero ?></span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span></span>
				

			</td>
		   
           	
        </tr>

	</table>
	 <br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante.'-'.$fecha_ingreso_numero.'-'.$fecha_fin_numero; ?>" label="label" style="width:120mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>
</page>
<?php  
if ($co_emp=='01') {
?>
<!--PROVIS-->
<page backtop="20mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 5%; color: black;font-size:15px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;">CONVENIO INDIVIDUAL PARA OTORGAMIENTO DE PRESTACIONES ALIMENTARIAS</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">Conste por el presente documento, el Convenio Individual para el Otorgamiento de Prestaciones Alimentarias (en adelante, el "CONVENIO") que celebran: <br>TAI LOY S.A. con Registro Único de Contribuyente 20100049181, con domicilio en Jr. Mariano Odicio N° 153, Urb. Miraflores, Surquillo, representado por Huarcaya Silva Manuel Enrique, con Documento Nacional de Identidad 06225424 (en adelante, "El EMPLEADOR") y, <?php echo $nom_postu ?>, con <?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?>, con domicilio en <?php echo $direccion_postu ?>, <?php echo $distrito_postu ?> (en adelante, "EL BENEFICIARIO").<br> Conforme a los términos y condiciones que se establecen a continuación:</span>

           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">PRIMERO: ANTECEDENTES Y LEY APLICABLE</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">Según la política de EL EMPLEADOR de preservar un clima laboral armonioso y de calidad para con sus trabajadores y considerando las necesidades de los mismos, EL EMPLEADOR ha convenido en contar con un programa de beneficio de alimentos y comidas, consistente en otorgar vales de alimentación o documentos análogos a sus trabajadores a través de una empresa administradora de prestaciones alimentarias. <br> El presente CONVENIO se ajusta a lo previsto la Ley 28051, Ley de Prestaciones Alimentarias en Beneficio de los Trabajadores sujetos al Régimen Laboral de la Actividad privada, y su Reglamento emitido mediante Decreto Supremo 013-2003-TR, las cuales, junto a las demás leyes de la República del Perú, se aplican de manera supletoria en todo lo no regulado en el presente documento.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">SEGUNDO: OBJETO DEL CONVENIO</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:11px;">En virtud del presente CONVENIO, EL EMPLEADOR y EL BENEFICIARIO convienen voluntariamente que el primero de los nombrados entregará en favor del segundo prestaciones alimentarias bajo la modalidad de suministro indirecto. <br>En cumplimiento de lo acordado por el presente documento, EL EMPLEADOR entregará a nombre del Trabajador, una Tarjeta Provis Alimentación (en adelante, la "TARJETA") emitida por SERVITEBCA PERÚ, SERVICIO DE TRANSFERENCIA ELECTRÓNICA DE BENEFICIOS Y PAGOS S.A., la cual podrá ser utilizada por EL BENEFICIARIO en establecimientos afiliados a la red de Provis Alimentación a nivel nacional, únicamente para la adquisición de alimentos y comidas, encontrándose prohibido su canje por dinero en efectivo. El presente beneficio se encuentra regulado en el segundo artículo, literal b.1) de la Ley 28501. <br>Cabe indicar que dicho beneficio de prestación alimentaria bajo la modalidad de suministro indirecto que otorga EL EMPLEADOR a favor de EL BENEFICIARIO no constituye remuneración alguna, por lo que no será computable para el pago de beneficios sociales, conforme a ley. <br>En tal sentido, el beneficio de prestación alimentaria otorgada por EL EMPLEADOR no podrá sustituir la remuneración básica, mixta (comisiones) u ordinaria que perciba EL BENEFICIARIO, conforme a los términos y condiciones pactados en su contrato de trabajo y al puesto de trabajo que desempeñe. <br>Las partes acuerdan que el beneficio de prestación alimentaria se otorgará a EL BENEFICIARIO, únicamente en caso EL BENEFICIARIO cumpla con todas y cada una de las políticas o directivas implementadas por EL EMPLEADOR, referidas al cumplimiento de metas colectivas o individuales, asistencia, puntualidad, permanencia y otras correspondientes a su respectivo cargo o puesto de trabajo. <br>Para tal efecto, EL BENEFICIARIO declara conocer todas y cada una de las políticas o directivas señaladas en el párrafo anterior, vinculadas al desempeño de su cargo o puesto de trabajo. <br> En caso de no cumplimiento de las metas establecidas, EL BENEFICIARIO no obtendrá el beneficio materia del presente convenio, salvo decisión en contrario de EL EMPLEADOR en cada oportunidad de otorgamiento. <br> Asimismo, EL EMPLEADOR podrá otorgar el beneficio de prestación alimentaria bajo la modalidad de suministro indirecto en sustitución de cualquier aumento remunerativo que considere otorgar voluntariamente a EL BENEFICIARIO, sin más limitaciones que las establecidas por ley.</span>

           	</td>

           	
        </tr>

	</table>


	
</page>
<page backtop="20mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">TERCERO: LA TARJETA Y SU USO
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">La TARJETA será recargable; en tal sentido, EL EMPLEADOR efectuará recargas de la TARJETA con una periodicidad mensual, donde la suma será variable, pero no será superior al 20% de la remuneración ordinaria del trabajador o superior a 2 RMV. Junto con la TARJETA, EL BENEFICIARIO recibirá todas las condiciones de uso de la TARJETA. <br>Conforme se encuentra previsto en la Ley 28501, el importe materia de recarga en la TARJETA no podrá ser exceder el 20% (veinte por ciento) de la remuneración ordinaria de EL BENEFICIARIO ni un importe equivalente a 2 (dos) remuneraciones mínimas vitales (RMV) vigentes al momento de realizar la recarga del beneficio. <br>De conformidad con lo establecido en el artículo 7° de la Ley 28051, por los alimentos y comidas adquiridos utilizando la TARJETA como medio de pago, EL BENEFICIARIO deberá solicitar únicamente boletas de venta, vales o cintas de máquinas registradoras que no permitan ejercer el derecho al crédito fiscal ni se utilizados para sustentar costo y/o gasto para efectos tributarios.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">CUARTO: VIGENCIA DEL CONVENIO
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">1. La vigencia del presente CONVENIO será de doce (12) meses, renovables automáticamente, salvo que las partes acuerden por escrito lo contrario. En caso de cese del vínculo laboral existente entre EL EMPLEADOR y EL BENEFICIARIO, el presente CONVENIO quedará sin efecto. <br> 2. EL EMPLEADOR eliminará el otorgamiento del beneficio materia del CONVENIO en caso quede comprobado que EL BENEFICIARIO ha incurrido en un uso indebido de la TARJETA, lo que adicionalmente será considerado falta grave laboral. <br>3. En el momento que las partes lo acuerden expresamente y por escrito, el beneficio materia de CONVENIO pasará a formar parte de la remuneración computable de EL BENEFICIARIO. De no producirse tal acuerdo, dicho beneficio seguirá teniendo el carácter de remuneración no computable. <br>4. EL EMPLEADOR podrá resolver unilateralmente el presente convenio en cualquier momento, comunicando su decisión a EL BENEFICIARIO en el plazo de veinticuatro (24) horas de adoptada tal decisión. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">QUINTO: NORMATIVIDAD APLICABLE
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">En todo lo no previsto en el presente convenio, resultará de aplicación lo establecido en las disposiciones legales que regulan las prestaciones alimentarias.
</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">SEXTO: PRESENTACIÓN DEL CONVENIO A LA AUTORIDAD ADMINISTRATIVA DE TRABAJO
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">El presente CONVENIO será puesto en conocimiento de la Autoridad Administrativa de Trabajo en el plazo de quince días de su suscripción. <br> En señal de conformidad, las partes suscriben el presente documento por triplicado en la ciudad de  Lima , el <?php echo $fecha_ingreso_letras ?>.</span>

           	</td>

           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>___________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>___________________________________</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>EMPLEADOR</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 40%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante; ?>" label="label" style="width:60mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>
	
</page>

<page backtop="20mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 5%; color: black;font-size:15px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;">CONVENIO INDIVIDUAL PARA OTORGAMIENTO DE PRESTACIONES ALIMENTARIAS</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">Conste por el presente documento, el Convenio Individual para el Otorgamiento de Prestaciones Alimentarias (en adelante, el "CONVENIO") que celebran: <br>TAI LOY S.A. con Registro Único de Contribuyente 20100049181, con domicilio en Jr. Mariano Odicio N° 153, Urb. Miraflores, Surquillo, representado por Huarcaya Silva Manuel Enrique, con Documento Nacional de Identidad 06225424 (en adelante, "El EMPLEADOR") y, <?php echo $nom_postu ?>, con <?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?>, con domicilio en <?php echo $direccion_postu ?>, <?php echo $distrito_postu ?> (en adelante, "EL BENEFICIARIO").<br> Conforme a los términos y condiciones que se establecen a continuación:</span>

           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">PRIMERO: ANTECEDENTES Y LEY APLICABLE</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">Según la política de EL EMPLEADOR de preservar un clima laboral armonioso y de calidad para con sus trabajadores y considerando las necesidades de los mismos, EL EMPLEADOR ha convenido en contar con un programa de beneficio de alimentos y comidas, consistente en otorgar vales de alimentación o documentos análogos a sus trabajadores a través de una empresa administradora de prestaciones alimentarias. <br> El presente CONVENIO se ajusta a lo previsto la Ley 28051, Ley de Prestaciones Alimentarias en Beneficio de los Trabajadores sujetos al Régimen Laboral de la Actividad privada, y su Reglamento emitido mediante Decreto Supremo 013-2003-TR, las cuales, junto a las demás leyes de la República del Perú, se aplican de manera supletoria en todo lo no regulado en el presente documento.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">SEGUNDO: OBJETO DEL CONVENIO</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:11px;">En virtud del presente CONVENIO, EL EMPLEADOR y EL BENEFICIARIO convienen voluntariamente que el primero de los nombrados entregará en favor del segundo prestaciones alimentarias bajo la modalidad de suministro indirecto. <br>En cumplimiento de lo acordado por el presente documento, EL EMPLEADOR entregará a nombre del Trabajador, una Tarjeta Provis Alimentación (en adelante, la "TARJETA") emitida por SERVITEBCA PERÚ, SERVICIO DE TRANSFERENCIA ELECTRÓNICA DE BENEFICIOS Y PAGOS S.A., la cual podrá ser utilizada por EL BENEFICIARIO en establecimientos afiliados a la red de Provis Alimentación a nivel nacional, únicamente para la adquisición de alimentos y comidas, encontrándose prohibido su canje por dinero en efectivo. El presente beneficio se encuentra regulado en el segundo artículo, literal b.1) de la Ley 28501. <br>Cabe indicar que dicho beneficio de prestación alimentaria bajo la modalidad de suministro indirecto que otorga EL EMPLEADOR a favor de EL BENEFICIARIO no constituye remuneración alguna, por lo que no será computable para el pago de beneficios sociales, conforme a ley. <br>En tal sentido, el beneficio de prestación alimentaria otorgada por EL EMPLEADOR no podrá sustituir la remuneración básica, mixta (comisiones) u ordinaria que perciba EL BENEFICIARIO, conforme a los términos y condiciones pactados en su contrato de trabajo y al puesto de trabajo que desempeñe. <br>Las partes acuerdan que el beneficio de prestación alimentaria se otorgará a EL BENEFICIARIO, únicamente en caso EL BENEFICIARIO cumpla con todas y cada una de las políticas o directivas implementadas por EL EMPLEADOR, referidas al cumplimiento de metas colectivas o individuales, asistencia, puntualidad, permanencia y otras correspondientes a su respectivo cargo o puesto de trabajo. <br>Para tal efecto, EL BENEFICIARIO declara conocer todas y cada una de las políticas o directivas señaladas en el párrafo anterior, vinculadas al desempeño de su cargo o puesto de trabajo. <br> En caso de no cumplimiento de las metas establecidas, EL BENEFICIARIO no obtendrá el beneficio materia del presente convenio, salvo decisión en contrario de EL EMPLEADOR en cada oportunidad de otorgamiento. <br> Asimismo, EL EMPLEADOR podrá otorgar el beneficio de prestación alimentaria bajo la modalidad de suministro indirecto en sustitución de cualquier aumento remunerativo que considere otorgar voluntariamente a EL BENEFICIARIO, sin más limitaciones que las establecidas por ley.</span>

           	</td>

           	
        </tr>

	</table>


	
</page>
<page backtop="20mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">TERCERO: LA TARJETA Y SU USO
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">La TARJETA será recargable; en tal sentido, EL EMPLEADOR efectuará recargas de la TARJETA con una periodicidad mensual, donde la suma será variable, pero no será superior al 20% de la remuneración ordinaria del trabajador o superior a 2 RMV. Junto con la TARJETA, EL BENEFICIARIO recibirá todas las condiciones de uso de la TARJETA. <br>Conforme se encuentra previsto en la Ley 28501, el importe materia de recarga en la TARJETA no podrá ser exceder el 20% (veinte por ciento) de la remuneración ordinaria de EL BENEFICIARIO ni un importe equivalente a 2 (dos) remuneraciones mínimas vitales (RMV) vigentes al momento de realizar la recarga del beneficio. <br>De conformidad con lo establecido en el artículo 7° de la Ley 28051, por los alimentos y comidas adquiridos utilizando la TARJETA como medio de pago, EL BENEFICIARIO deberá solicitar únicamente boletas de venta, vales o cintas de máquinas registradoras que no permitan ejercer el derecho al crédito fiscal ni se utilizados para sustentar costo y/o gasto para efectos tributarios.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">CUARTO: VIGENCIA DEL CONVENIO
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">1. La vigencia del presente CONVENIO será de doce (12) meses, renovables automáticamente, salvo que las partes acuerden por escrito lo contrario. En caso de cese del vínculo laboral existente entre EL EMPLEADOR y EL BENEFICIARIO, el presente CONVENIO quedará sin efecto. <br> 2. EL EMPLEADOR eliminará el otorgamiento del beneficio materia del CONVENIO en caso quede comprobado que EL BENEFICIARIO ha incurrido en un uso indebido de la TARJETA, lo que adicionalmente será considerado falta grave laboral. <br>3. En el momento que las partes lo acuerden expresamente y por escrito, el beneficio materia de CONVENIO pasará a formar parte de la remuneración computable de EL BENEFICIARIO. De no producirse tal acuerdo, dicho beneficio seguirá teniendo el carácter de remuneración no computable. <br>4. EL EMPLEADOR podrá resolver unilateralmente el presente convenio en cualquier momento, comunicando su decisión a EL BENEFICIARIO en el plazo de veinticuatro (24) horas de adoptada tal decisión. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">QUINTO: NORMATIVIDAD APLICABLE
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">En todo lo no previsto en el presente convenio, resultará de aplicación lo establecido en las disposiciones legales que regulan las prestaciones alimentarias.
</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">SEXTO: PRESENTACIÓN DEL CONVENIO A LA AUTORIDAD ADMINISTRATIVA DE TRABAJO
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">El presente CONVENIO será puesto en conocimiento de la Autoridad Administrativa de Trabajo en el plazo de quince días de su suscripción. <br> En señal de conformidad, las partes suscriben el presente documento por triplicado en la ciudad de  Lima , el <?php echo $fecha_ingreso_letras ?>.</span>

           	</td>

           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>___________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>___________________________________</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>EMPLEADOR</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 40%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante; ?>" label="label" style="width:60mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>
	
</page>
<page backtop="20mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 5%; color: black;font-size:15px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;">CONVENIO INDIVIDUAL PARA OTORGAMIENTO DE PRESTACIONES ALIMENTARIAS</span>
            </td>
			<td style="width: 5%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">Conste por el presente documento, el Convenio Individual para el Otorgamiento de Prestaciones Alimentarias (en adelante, el "CONVENIO") que celebran: <br>TAI LOY S.A. con Registro Único de Contribuyente 20100049181, con domicilio en Jr. Mariano Odicio N° 153, Urb. Miraflores, Surquillo, representado por Huarcaya Silva Manuel Enrique, con Documento Nacional de Identidad 06225424 (en adelante, "El EMPLEADOR") y, <?php echo $nom_postu ?>, con <?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?>, con domicilio en <?php echo $direccion_postu ?>, <?php echo $distrito_postu ?> (en adelante, "EL BENEFICIARIO").<br> Conforme a los términos y condiciones que se establecen a continuación:</span>

           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">PRIMERO: ANTECEDENTES Y LEY APLICABLE</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">Según la política de EL EMPLEADOR de preservar un clima laboral armonioso y de calidad para con sus trabajadores y considerando las necesidades de los mismos, EL EMPLEADOR ha convenido en contar con un programa de beneficio de alimentos y comidas, consistente en otorgar vales de alimentación o documentos análogos a sus trabajadores a través de una empresa administradora de prestaciones alimentarias. <br> El presente CONVENIO se ajusta a lo previsto la Ley 28051, Ley de Prestaciones Alimentarias en Beneficio de los Trabajadores sujetos al Régimen Laboral de la Actividad privada, y su Reglamento emitido mediante Decreto Supremo 013-2003-TR, las cuales, junto a las demás leyes de la República del Perú, se aplican de manera supletoria en todo lo no regulado en el presente documento.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">SEGUNDO: OBJETO DEL CONVENIO</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:11px;">En virtud del presente CONVENIO, EL EMPLEADOR y EL BENEFICIARIO convienen voluntariamente que el primero de los nombrados entregará en favor del segundo prestaciones alimentarias bajo la modalidad de suministro indirecto. <br>En cumplimiento de lo acordado por el presente documento, EL EMPLEADOR entregará a nombre del Trabajador, una Tarjeta Provis Alimentación (en adelante, la "TARJETA") emitida por SERVITEBCA PERÚ, SERVICIO DE TRANSFERENCIA ELECTRÓNICA DE BENEFICIOS Y PAGOS S.A., la cual podrá ser utilizada por EL BENEFICIARIO en establecimientos afiliados a la red de Provis Alimentación a nivel nacional, únicamente para la adquisición de alimentos y comidas, encontrándose prohibido su canje por dinero en efectivo. El presente beneficio se encuentra regulado en el segundo artículo, literal b.1) de la Ley 28501. <br>Cabe indicar que dicho beneficio de prestación alimentaria bajo la modalidad de suministro indirecto que otorga EL EMPLEADOR a favor de EL BENEFICIARIO no constituye remuneración alguna, por lo que no será computable para el pago de beneficios sociales, conforme a ley. <br>En tal sentido, el beneficio de prestación alimentaria otorgada por EL EMPLEADOR no podrá sustituir la remuneración básica, mixta (comisiones) u ordinaria que perciba EL BENEFICIARIO, conforme a los términos y condiciones pactados en su contrato de trabajo y al puesto de trabajo que desempeñe. <br>Las partes acuerdan que el beneficio de prestación alimentaria se otorgará a EL BENEFICIARIO, únicamente en caso EL BENEFICIARIO cumpla con todas y cada una de las políticas o directivas implementadas por EL EMPLEADOR, referidas al cumplimiento de metas colectivas o individuales, asistencia, puntualidad, permanencia y otras correspondientes a su respectivo cargo o puesto de trabajo. <br>Para tal efecto, EL BENEFICIARIO declara conocer todas y cada una de las políticas o directivas señaladas en el párrafo anterior, vinculadas al desempeño de su cargo o puesto de trabajo. <br> En caso de no cumplimiento de las metas establecidas, EL BENEFICIARIO no obtendrá el beneficio materia del presente convenio, salvo decisión en contrario de EL EMPLEADOR en cada oportunidad de otorgamiento. <br> Asimismo, EL EMPLEADOR podrá otorgar el beneficio de prestación alimentaria bajo la modalidad de suministro indirecto en sustitución de cualquier aumento remunerativo que considere otorgar voluntariamente a EL BENEFICIARIO, sin más limitaciones que las establecidas por ley.</span>

           	</td>

           	
        </tr>

	</table>


	
</page>
<page backtop="20mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">TERCERO: LA TARJETA Y SU USO
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">La TARJETA será recargable; en tal sentido, EL EMPLEADOR efectuará recargas de la TARJETA con una periodicidad mensual, donde la suma será variable, pero no será superior al 20% de la remuneración ordinaria del trabajador o superior a 2 RMV. Junto con la TARJETA, EL BENEFICIARIO recibirá todas las condiciones de uso de la TARJETA. <br>Conforme se encuentra previsto en la Ley 28501, el importe materia de recarga en la TARJETA no podrá ser exceder el 20% (veinte por ciento) de la remuneración ordinaria de EL BENEFICIARIO ni un importe equivalente a 2 (dos) remuneraciones mínimas vitales (RMV) vigentes al momento de realizar la recarga del beneficio. <br>De conformidad con lo establecido en el artículo 7° de la Ley 28051, por los alimentos y comidas adquiridos utilizando la TARJETA como medio de pago, EL BENEFICIARIO deberá solicitar únicamente boletas de venta, vales o cintas de máquinas registradoras que no permitan ejercer el derecho al crédito fiscal ni se utilizados para sustentar costo y/o gasto para efectos tributarios.</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">CUARTO: VIGENCIA DEL CONVENIO
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">1. La vigencia del presente CONVENIO será de doce (12) meses, renovables automáticamente, salvo que las partes acuerden por escrito lo contrario. En caso de cese del vínculo laboral existente entre EL EMPLEADOR y EL BENEFICIARIO, el presente CONVENIO quedará sin efecto. <br> 2. EL EMPLEADOR eliminará el otorgamiento del beneficio materia del CONVENIO en caso quede comprobado que EL BENEFICIARIO ha incurrido en un uso indebido de la TARJETA, lo que adicionalmente será considerado falta grave laboral. <br>3. En el momento que las partes lo acuerden expresamente y por escrito, el beneficio materia de CONVENIO pasará a formar parte de la remuneración computable de EL BENEFICIARIO. De no producirse tal acuerdo, dicho beneficio seguirá teniendo el carácter de remuneración no computable. <br>4. EL EMPLEADOR podrá resolver unilateralmente el presente convenio en cualquier momento, comunicando su decisión a EL BENEFICIARIO en el plazo de veinticuatro (24) horas de adoptada tal decisión. </span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">QUINTO: NORMATIVIDAD APLICABLE
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">En todo lo no previsto en el presente convenio, resultará de aplicación lo establecido en las disposiciones legales que regulan las prestaciones alimentarias.
</span>

           	</td>

           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-weight:bold;text-decoration: underline black;font-size:12px;">SEXTO: PRESENTACIÓN DEL CONVENIO A LA AUTORIDAD ADMINISTRATIVA DE TRABAJO
				</span>

           	</td>

           	
        </tr>

	</table>
	
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:20px;font-size:12px;">El presente CONVENIO será puesto en conocimiento de la Autoridad Administrativa de Trabajo en el plazo de quince días de su suscripción. <br> En señal de conformidad, las partes suscriben el presente documento por triplicado en la ciudad de  Lima , el <?php echo $fecha_ingreso_letras ?>.</span>

           	</td>

           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>___________________________________</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>___________________________________</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>EMPLEADOR</span>
            </td>
			<td style="width: 40%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>TRABAJADOR</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<br><br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 40%; color: black;font-size:15px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 10%; color: #444444;">
            </td>
			<td style="width: 50%;text-align:right">
				<barcode dimension="1D" type="C39" value="<?php echo $dni_postulante; ?>" label="label" style="width:60mm; height:10mm;  font-size: 3mm"></barcode>
				
			</td>
		   
           	
        </tr>

	</table>
	
</page>
<?php  
}
?>

<?php if ($proceso_postu=='003'){
	  
?>
	<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
		<?php 
			if ($co_emp=='04') {
        ?>
			
        <page_footer>
	        <table >
	            <tr>
	                <td>
	           			<img src="../../inc/img/footer-constancia-suplacorp.png" style='width: 850px; height: 120px'>
	           		</td>
	            </tr>
	        </table>
	    </page_footer>
	    <?php  
       		}
		?>

	    <table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
	        <tr>
	        	<?php  
			    	if ($co_emp=='01') {
			    ?>
			    <td>
	           		<img src="../../inc/img/cabecera-constancia-tailoy.png" style='width: 700px; height: 180px'>
	           	</td>
	           	<?php  
	           		}elseif ($co_emp=='04') {
	           	?>

	           	<td>
	           		<img src="../../inc/img/cabecera-constancia-suplacorp.png" style='width: 750px; height: 180px'>
	           	</td>
	           	<?php  
	           		}
				?>
	        </tr>
		</table>
	    <br><br>
	    <table cellspacing="0" style="width: 100%;">
	        <tr>
	            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
	                
	            </td>
				<td style="width: 80%; color: #444444;">
	                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;  ">CARGO DE ENTREGA DE FOTOCHECK Y TARJETA DE ACCESOS</span>
	            </td>
				<td style="width: 10%;text-align:right">
					
				</td>
				
	        </tr>
	    </table>

	    <br><br>
	    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
	        <tr>
	        	
			    <td>
	           		<span style="line-height:25px;font-size:13px;">Yo <?php echo $nom_postu ?> identificado con <?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?> trabajador del area de <?php echo $area_postu ?>. He recibido como herramienta de trabajo mi fotocheck personalizados con mis datos, así como una tarjeta de accesos a las diferentes oficinas según mi posición y política de la empresa. El costo es de S/ 30.00 soles.</span>
	           	</td>
	           	
	        </tr>

		</table>
		<br><br>
		<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
	        <tr>
	        	
			    <td>
	           		<span style="line-height:25px;font-size:13px;font-weight:bold;text-decoration: underline black;">Consideraciones:</span>
	           	</td>
	           	
	        </tr>

		</table>
		<br><br>
	    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
	        <tr>
	        	
			    <td>
	           		<span style="line-height:25px;font-size:13px;">La pérdida o robo del fotocheck y la tarjeta de accesos, es asumido por el trabajador, para lo cual deberá informar de manera inmediata a su jefe directo y al área de RRHH para la reposición y descuento respectivo hasta en cuatro cuotas quincenales. Al término de la relación laboral deberá de devolver los Instrumentos mencionados en el presente documentos a su jefe directo o RRHH Del mismo modo, autorizo a mi empleador que en caso que dejara de laborar en la empresa puedan descontar el monto de las herramientas de mi liquidación y demás beneficios laborales que me correspondan en el caso de no haberlo devuelto o de encontrarse deteriorado.</span>
	           	</td>
	           	
	        </tr>

		</table>
		<br><br><br><br><br><br><br><br><br><br>
		<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
	        <tr>
	        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
	                <span>___________________________________</span>
	            </td>
				<td style="width: 35%; color: #444444;">
	            </td>
				<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
					<span>___________________________________</span>
					
				</td>
			   
	           	
	        </tr>

		</table>
		<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
	        <tr>
	        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
	                <span>FIRMA DEL TRABAJADOR</span>
	            </td>
				<td style="width: 35%; color: #444444;">
	            </td>
				<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
					<span>FECHA</span>
					

				</td>
			   
	           	
	        </tr>

		</table>
	</page>
<?php 
	}
 ?>

<page backtop="15mm" backbottom="15mm" backleft="25mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                
                <?php if ($co_emp=='001') { ?>
			 	<img src='../../images/icon/logo-tailoy.png' style='    max-width: 70px;
				    height: 70px;
				    float: left;'  />
				 <?php }elseif($co_emp=='02'){ ?>

				 	<img src="../../images/icon/logo-luciano.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />
				 <?php }elseif($co_emp=='03') {?>

				 	<img src="../../images/icon/logo-copy.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />
				 <?php }elseif($co_emp=='04') {?>
				 
				 	<img src="../../images/icon/logo-supla.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />
				 <?php }elseif($co_emp=='05'){ ?>

				 	<img src="../../images/icon/logo-casca.png" style='    max-width: 100px;
				    height: auto;
				    float: left;'  />
				<?php }elseif($co_emp=='06'){ ?>

				 	<img src="../../assets/images/tailoy-bg/06.png" style='  max-width: 80px;
				    height: 80px;
				    float: left;'  />
				 <?php }?>
            </td>
			<td style="width: 15%; color: #444444;">
            </td>
			<td style="width: 55%; color: black;font-size:11px;text-align:left;">
				<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
					<tr>
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">N° DE DOCUMENTO</td>
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;"><?php echo $dni_postulante ?></td>
					</tr>
					<tr>
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">PUESTO</td>
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;"><?php echo $puesto_postu ?></td>
					</tr>
					<tr>
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">C. COSTO</td>
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;"><?php echo $area_postu ?></td>
					</tr>
					<tr>
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">ÁREA TRABAJO</td>
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;"><?php echo $area_postu ?></td>
					</tr>
					<tr>
						<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">F. DE INGRESO</td>
						<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;"><?php echo $fecha_ingreso_letras ?></td>
					</tr>
				</table>
				

			</td>
		   
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 100%; color: black;font-size:12px;text-align:middle;font-weight:bold; color: white; background-color: black;text-align: center;">
                DECLARACIÓN JURADA DE DATOS PERSONALES
            </td>
		</tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle" >
		<tr>
            <td style="width: 70%;">
            	<table  cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
	            	<tr>
	            		<td style="width: 100%;font-size:11px;text-align: left;">
			                <span>Instrucciones:</span>
			            </td>
	            	</tr>
	            	<tr>
			        	<td style="width: 100%;font-size:11px;text-align: left;">
			                <span>a) Lea cuidadosamente la Declaración Jurada de Datos del Personal, antes de llenar los espacios en blanco.</span>
			            </td>
					</tr>
					<br>
					<tr>
			        	<td style="width: 100%;font-size:11px;text-align: left;">
			                <span>b) Llene la ficha, en caso de alguna duda consulte con el Dpto. de Recursos Humanos.</span>
			            </td>
					</tr>
					<br>
					<tr>
			        	<td style="width: 100%;font-size:11px;text-align: left;">
			                <span>c) No deje ninguna pregunta sin contestar.</span>
			            </td>
					</tr>
	            </table>
            </td>
            <td style="width: 8%;">
            </td>
            <td style="width: 30%;">
            	<table  cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
	            	<tr>
	            		<td >
			               
			               <?php  
			              	if ($foto_postulante!='') {
			              	
			               	echo "<img src='http://www1.tailoy.com.pe/TRABAJA_EN_TAILOY/TRABAJA_TAI/FOTO/".$foto_postulante."' style='width: 142px; height: 185px'>";
			               	}else{
			               		echo '<img src="../../inc/img/foto.png" style="width: 150px; height: 185px">';
			               	}
			               ?>
			               
			              
			               
			            </td>
	            	</tr>
	            	
	            </table>
            </td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle;font-size:11px;" >
		<tr>
			<td>
				<span style="font-weight:bold; ">I. DATOS PERSONALES</span>
			</td>
		</tr>
		<tr>
			<td>
				<span>En esta sección deberá de consignar sus datos</span>
			</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle;font-size:11px;" >
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">APELLIDO PATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">APELLIDO MATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">NOMBRES</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%; height: 2%"><?php echo $apellido_paterno_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $apellido_materno_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $nombre_completo_postu ?></td>
		</tr>
		
		
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle;font-size:11px;" >
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%;font-weight:bold;background-color: #C0C0C0">NACIONALIDAD</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 65%;font-weight:bold;background-color: #C0C0C0" colspan="4">NACIMIENTO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 15%;font-weight:bold;background-color: #C0C0C0" rowspan="2">SEXO</td>
		</tr>
		<tr style="vertical-align: middle;font-size:10px;">
			<td style='border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; vertical-align: middle;' rowspan="2">PERU</td>
			<td style='border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000; vertical-align: middle;font-weight:bold;background-color: #C0C0C0'>DEPARTAMENTO</td>
			<td style='border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; vertical-align: middle;font-weight:bold;background-color: #C0C0C0'>PROVINCIA</td>
			<td style='border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; vertical-align: middle;font-weight:bold;background-color: #C0C0C0'>DISTRITO</td>
			<td style='border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; vertical-align: middle;font-weight:bold;background-color: #C0C0C0;'>FECHA DE NACIMIENTO</td>
		</tr>
		<tr style="vertical-align: middle; text-align: center;">
			<td style='border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000; vertical-align: middle;font-size:10px;' rowspan="2"><?php echo $nombre_departamento_postu ?></td>
			<td style='border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;vertical-align: middle; ;height: 1%;'><?php echo $nombre_provincia_postu ?></td>
			<td style='border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;vertical-align: middle;height: 1%;font-size:10px;'><?php echo $nombre_distrito_postu ?></td>
			<td style='border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;vertical-align: middle;height: 1%;font-size:12px;'><?php echo $fecha_nacimiento_postu ?></td>
			<td style='border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; vertical-align: middle;height: 1%;font-size:10px;'><?php echo $genero_postu ?></td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle;font-size:11px;" >
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">TIPO DE DOCUMENTO DE IDENTIDAD</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">N° DOCUMENTO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">ESTADO CIVIL</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%; height: 2%"><?php echo $tipo_documento ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $dni_postulante ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $estado_civil_postu ?></td>
		</tr>
		
		
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle;font-size:11px;" >
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%;font-weight:bold;background-color: #C0C0C0">ESTATURA</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;font-weight:bold;background-color: #C0C0C0">PESO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;font-weight:bold;background-color: #C0C0C0">TALLA POLO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 20%;font-weight:bold;background-color: #C0C0C0">AFILIADO AFP</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%;font-weight:bold;background-color: #C0C0C0">CUSSP</td>
		</tr>
<?php  
$queryEstatura = "SELECT 
	TTTIPO_TALL.CO_TIPO_TALL,
	TTTIPO_TALL.DE_TIPO_TALL,
	TM_POSTULANTE_TALL.NU_TAME+' Mts.' AS NU_TAME
FROM TM_POSTULANTE_TALL
LEFT JOIN OFIPLAN.DBO.TTTIPO_TALL ON TTTIPO_TALL.CO_TIPO_TALL=TM_POSTULANTE_TALL.DE_OBSE
WHERE TM_POSTULANTE_TALL.CO_TRAB  LIKE'%".$dni_postulante."%'
AND TM_POSTULANTE_TALL.TI_DOCU_IDEN='".$codigo_tipo_documento."'
AND CO_TIPO_TALL='025'

";

$resultEstatura = odbc_exec($conexion,$queryEstatura);
//echo $conexion;
while($registroEstatura=odbc_fetch_array($resultEstatura)) {
    $estatura_postu=$registroEstatura['NU_TAME'];

}

$queryPeso = "SELECT 
	TTTIPO_TALL.CO_TIPO_TALL,
	TTTIPO_TALL.DE_TIPO_TALL,
	TM_POSTULANTE_TALL.NU_TAME+' Kg.' AS NU_TAME
FROM TM_POSTULANTE_TALL
LEFT JOIN OFIPLAN.DBO.TTTIPO_TALL ON TTTIPO_TALL.CO_TIPO_TALL=TM_POSTULANTE_TALL.DE_OBSE
WHERE TM_POSTULANTE_TALL.CO_TRAB  LIKE'%".$dni_postulante."%'
AND TM_POSTULANTE_TALL.TI_DOCU_IDEN='".$codigo_tipo_documento."'
AND CO_TIPO_TALL='026'


";
$resultPeso = odbc_exec($conexion,$queryPeso);
//echo $conexion;
while($registroPeso=odbc_fetch_array($resultPeso)) {
    $peso_postu=$registroPeso['NU_TAME'];
}

$queryTallaPolo = "SELECT 
	TTTIPO_TALL.CO_TIPO_TALL,
	TTTIPO_TALL.DE_TIPO_TALL,
	TM_POSTULANTE_TALL.NU_TAME
FROM TM_POSTULANTE_TALL
LEFT JOIN OFIPLAN.DBO.TTTIPO_TALL ON TTTIPO_TALL.CO_TIPO_TALL=TM_POSTULANTE_TALL.DE_OBSE
WHERE TM_POSTULANTE_TALL.CO_TRAB  LIKE'%".$dni_postulante."%'
AND TM_POSTULANTE_TALL.TI_DOCU_IDEN='".$codigo_tipo_documento."'
AND CO_TIPO_TALL='003'


";
$resultTallaPolo = odbc_exec($conexion,$queryTallaPolo);
//echo $conexion;
while($registroTallaPolo=odbc_fetch_array($resultTallaPolo)) {
    $talla_polo_postu=$registroTallaPolo['NU_TAME'];
}
?>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%; height: 2%"><?php echo $estatura_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;height: 2%"><?php echo $peso_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;height: 2%"><?php echo $talla_polo_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 20%;height: 2%"><?php echo $afp_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%;height: 2%"><?php echo $numero_cussp_postu ?></td>
		</tr>
		
		
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle;font-size:10px;" >
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%;font-weight:bold;background-color: #C0C0C0">GRUPO SANGUÍNEO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 15%;font-weight:bold;background-color: #C0C0C0">TELEF. FIJO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 15%;font-weight:bold;background-color: #C0C0C0">TELEF. CEL.</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%;font-weight:bold;background-color: #C0C0C0">CORREO ELECTRONICO</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%; height: 2%"><?php echo $grupo_sanguineo_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 15%;height: 2%"><?php echo $fijo_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 15%;height: 2%"><?php echo $celular_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%;height: 2%"><?php echo $correo_postu ?></td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle;font-size:10px;" >
		<tr>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 45%;font-weight:bold;background-color: #C0C0C0">NOMBRE CONTACTO DE EMERGENCIA</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 20%;font-weight:bold;background-color: #C0C0C0">TELEFONO CONTACTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">PARENTESCO</td>
		</tr>
		<?php  
		$queryReferencia = "SELECT 
			NO_REFE_PERS, 
			NU_TLFN_REF1,
			vTTTIPO_PARI.DE_PARI

			FROM TM_POSTULANTE_REFE
			LEFT JOIN vTTTIPO_PARI ON vTTTIPO_PARI.TI_PARI=TM_POSTULANTE_REFE.DE_VINC_REFE

		WHERE TM_POSTULANTE_REFE.CO_TRAB  LIKE'%".$dni_postulante."%'
		AND TM_POSTULANTE_REFE.TI_DOCU_IDEN='".$codigo_tipo_documento."'
	


		";
		$resultReferencia = odbc_exec($conexion,$queryReferencia);
		//echo $conexion;
		while($registroReferencia=odbc_fetch_array($resultReferencia)) {
		   
		?>
		<tr>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 45%; height: 2%"><?php echo $registroReferencia['NO_REFE_PERS'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 20%;height: 2%"><?php echo $registroReferencia['NU_TLFN_REF1'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $registroReferencia['DE_PARI'] ?></td>
		</tr>
		
		<?php  
		}
		?>
		
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle;font-size:11px;" >
		<tr>
			<td>
				<span style="font-weight:bold; ">II. FORMACIÓN PROFESIONAL</span>
			</td>
		</tr>
		<tr>
			<td>
				<span>Indicar el Centro de EStudios y nivel académico alcanzado.</span>
			</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle;font-size:10px;" >
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 80%;font-weight:bold;background-color: #C0C0C0">CENTRO EDUCATIVO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 10%;font-weight:bold;background-color: #C0C0C0">INICIO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 10%;font-weight:bold;background-color: #C0C0C0">FIN</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle;font-size:10px;" >
		
		<?php  
		$queryReferencia = "SELECT 
		T_SITIACION_EDUCATIVA.Co_Nivel_Educativo_Resumen,
		TM_POSTULANTE_PROF.De_Institiucion,
		TM_POSTULANTE_PROF.Anno_Ingreso, 
		TM_POSTULANTE_PROF.Anno_Egreso
		FROM TM_POSTULANTE_PROF
		LEFT JOIN T_SITIACION_EDUCATIVA ON T_SITIACION_EDUCATIVA.Co_Nivel_Educativo=TM_POSTULANTE_PROF.Co_Nivel_educativo
		WHERE NU_CORR_PROF IN('1','2')
		AND	TM_POSTULANTE_PROF.CO_TRAB  LIKE'%".$dni_postulante."%'
		AND TM_POSTULANTE_PROF.TI_DOCU_IDEN='".$codigo_tipo_documento."'
		ORDER BY NU_CORR_PROF ASC


		";
		$resultReferencia = odbc_exec($conexion,$queryReferencia);
		//echo $conexion;
		while($registroReferencia=odbc_fetch_array($resultReferencia)) {
		   
		?>
		<tr>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 35%; height: 2%;font-weight:bold;background-color: #C0C0C0"><?php echo $registroReferencia['Co_Nivel_Educativo_Resumen'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 45%;height: 2%"><?php echo utf8_encode($registroReferencia['De_Institiucion']) ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 10%;height: 2%"><?php echo $registroReferencia['Anno_Ingreso'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 10%;height: 2%"><?php echo $registroReferencia['Anno_Egreso'] ?></td>
		</tr>
		
		<?php  
		}
		?>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle;font-size:10px;" >
		<tr>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%;font-weight:bold;background-color: #C0C0C0">CENTRO EDUCATIVO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 9%;font-weight:bold;background-color: #C0C0C0">INICIO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 9%;font-weight:bold;background-color: #C0C0C0">FIN</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 15%;font-weight:bold;background-color: #C0C0C0">ESPECIALIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 17%;font-weight:bold;background-color: #C0C0C0">NIVEL ALCANZADO</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle;font-size:10px;" >
		
		<?php  
		$queryReferencia = "SELECT
			T_SITIACION_EDUCATIVA.Co_Nivel_Educativo_Resumen,
			T_INST_EDUCATIVA.De_Institucion,
			TM_POSTULANTE_PROF.Anno_Ingreso, 
			TM_POSTULANTE_PROF.Anno_Egreso,
			T_INST_EDUCATIVA.De_Carrera,
			T_INST_EDUCATIVA.NIVEL_EDUCATIVO
		FROM TM_POSTULANTE_PROF
		LEFT JOIN T_SITIACION_EDUCATIVA ON T_SITIACION_EDUCATIVA.Co_Nivel_Educativo=TM_POSTULANTE_PROF.Co_Nivel_educativo
		LEFT JOIN T_INST_EDUCATIVA ON T_INST_EDUCATIVA.Co_Regimen=TM_POSTULANTE_PROF.Co_Regimen_Insiticucion
			AND T_INST_EDUCATIVA.Co_Tipo_Institucion=TM_POSTULANTE_PROF.Co_Tipo_Institiucion
			AND T_INST_EDUCATIVA.Co_Carrera=TM_POSTULANTE_PROF.Co_Carrera
			AND T_INST_EDUCATIVA.Co_Institucion=TM_POSTULANTE_PROF.De_Institiucion
			
		WHERE NU_CORR_PROF NOT IN('1','2')
			AND	TM_POSTULANTE_PROF.CO_TRAB  LIKE'%".$dni_postulante."%'
			AND TM_POSTULANTE_PROF.TI_DOCU_IDEN='".$codigo_tipo_documento."'
			ORDER BY NU_CORR_PROF ASC


		";
		$resultReferencia = odbc_exec($conexion,$queryReferencia);
		//echo $conexion;
		while($registroReferencia=odbc_fetch_array($resultReferencia)) {
		   
		?>
		<tr>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 15%; height: 2%;font-weight:bold;background-color: #C0C0C0"><?php echo $registroReferencia['Co_Nivel_Educativo_Resumen'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 35%;height: 2%"><?php echo utf8_encode($registroReferencia['De_Institucion']) ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 9%;height: 2%"><?php echo $registroReferencia['Anno_Ingreso'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 9%;height: 2%"><?php echo $registroReferencia['Anno_Egreso'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 15%;height: 2%"><?php echo utf8_encode($registroReferencia['De_Carrera']) ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 17%;height: 2%"><?php echo $registroReferencia['NIVEL_EDUCATIVO'] ?></td>
		</tr>
		
		<?php  
		}
		?>
	</table>
	
</page>
<page backtop="15mm" backbottom="15mm" backleft="25mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle;font-size:11px;" >
		<tr>
			<td>
				<span style="font-weight:bold; ">III. EXPERIENCIA LABORAL</span>
			</td>
		</tr>
		<tr>
			<td>
				<span>Indicar la experiencia laboral obtenida iniciando por la última.</span>
			</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle;font-size:10px;" >
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">EMPRESA / GIRO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 21%;font-weight:bold;background-color: #C0C0C0">CARGO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 9%;font-weight:bold;background-color: #C0C0C0">DESDE</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 9%;font-weight:bold;background-color: #C0C0C0">HASTA</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 11%;font-weight:bold;background-color: #C0C0C0">SUELDO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%;font-weight:bold;background-color: #C0C0C0">MOTIVO DE CAMBIO</td>
		</tr>
		<?php  
		$queryExperiencia = "SELECT 
		TM_POSTULANTE_EMAN.NO_EMPR_ANTE,
		vPUESTOS_AUX.DE_PUES_AUXI,
		CONVERT(CHAR(10), CONVERT(date, FE_INGR_EMAN, 112), 103) AS DESDE,
		CONVERT(CHAR(10), CONVERT(date, FE_CESE_EMAN, 112), 103) AS HASTA,
		CONVERT(INT,TM_POSTULANTE_EMAN.IM_ULTI_SUEL) AS SUELDO,
		vTTMOTI_SEPA.DE_MOTI_SEPA
		FROM TM_POSTULANTE_EMAN
		LEFT JOIN vPUESTOS_AUX ON vPUESTOS_AUX.CO_PUES_AUXI=TM_POSTULANTE_EMAN.CO_PUES_AUXI
		LEFT JOIN vTTMOTI_SEPA ON vTTMOTI_SEPA.CO_MOTI_SEPA=TM_POSTULANTE_EMAN.CO_MOTI_SEPA
		WHERE TM_POSTULANTE_EMAN.CO_TRAB  LIKE'%".$dni_postulante."%'
		AND TM_POSTULANTE_EMAN.TI_DOCU_IDEN='".$codigo_tipo_documento."'
	
		ORDER BY FE_CESE_EMAN DESC

		";
		$resultExperiencia = odbc_exec($conexion,$queryExperiencia);
		//echo $queryExperiencia;
		while($registroExperiencia=odbc_fetch_array($resultExperiencia)) {
		   
		?>
		<tr>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 30%; height: 2%"><?php echo $registroExperiencia['NO_EMPR_ANTE'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 21%;height: 2%"><?php echo utf8_encode($registroExperiencia['DE_PUES_AUXI']) ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 9%;height: 2%"><?php echo $registroExperiencia['DESDE'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 9%;height: 2%"><?php echo $registroExperiencia['HASTA'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 11%;height: 2%">S/ <?php echo $registroExperiencia['SUELDO'] ?>.00</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%;height: 2%"><?php echo utf8_encode($registroExperiencia['DE_MOTI_SEPA']) ?></td>
		</tr>
		
		<?php  
		}
		?>
		
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle;font-size:11px;">
		<tr>
			<td>
				<span style="font-weight:bold; ">IV. DATOS DERECHOHABIENTES</span>
			</td>
		</tr>
		<tr>
			<td>
				<span>Deberá indicar los datos principales de sus derechohabientes: Esposo (a) / Conviviente / Hijos menores de 18 años.</span>
			</td>
		</tr>
		
	</table>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		<tr>
			<td>
				<span style="font-weight:bold; text-align: left;">ESPOSO(A)</span>
			</td>
		</tr>	
		<tr style="text-align: center; ">
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">APELLIDO PATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">APELLIDO MATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">NOMBRES</td>
		</tr>



<?php  
  
		$queryEsposo = "SELECT
		TM_POSTULANTE_PARI.TI_DOCU_IDEN_PARI,
		T_TIPO_DOC_IDEN.No_Tipo_Doc_Iden,
		TM_POSTULANTE_PARI.NU_DOCU_IDEN_PARI, 
		TM_POSTULANTE_PARI.NO_APEL_PATE,
		TM_POSTULANTE_PARI.NO_APEL_MATE,
		TM_POSTULANTE_PARI.NO_PARI,
		T_GENERO.No_Genero,
		CONVERT(CHAR(10), CONVERT(date, TM_POSTULANTE_PARI.FE_NACI_PARI, 112), 103) AS FECHA_NACIMIENTO,
		T_ESTADO_CIVIL.No_Estado_Civil,
		T_UBIGEO.NO_DEPARTAMENTO,
		T_UBIGEO.NO_PROVINCIA+', '+T_UBIGEO.NO_DISTRITO AS LUGAR_NACIMIENTO,
		vPAIS.NO_PAIS

		FROM TM_POSTULANTE_PARI

		LEFT JOIN T_TIPO_DOC_IDEN ON T_TIPO_DOC_IDEN.Id_Tipo_Doc_Iden=TM_POSTULANTE_PARI.TI_DOCU_IDEN_PARI
		LEFT JOIN T_GENERO ON T_GENERO.Id_Genero=TM_POSTULANTE_PARI.ST_SEXO_PARI
		LEFT JOIN T_ESTADO_CIVIL ON T_ESTADO_CIVIL.Id_Estado_Civil=TM_POSTULANTE_PARI.CO_ESTA_CIVI
		LEFT JOIN SELECCION.DBO.T_UBIGEO ON T_UBIGEO.ID_UBIGEO=TM_POSTULANTE_PARI.CO_LUGA_NACI
		LEFT JOIN SELECCION.DBO.vPAIS ON vPAIS.CO_PAIS=TM_POSTULANTE_PARI.CO_PAIS_NCIO

		WHERE TM_POSTULANTE_PARI.NU_DOCU_IDEN  LIKE'%".$dni_postulante."%'
		AND TM_POSTULANTE_PARI.TI_DOCU_IDEN='".$codigo_tipo_documento."'
		AND TM_POSTULANTE_PARI.NU_CORR_PARI='1'
	
		";
		$resultEsposo = odbc_exec($conexion,$queryEsposo);
		//echo $queryEsposo;
		while($registroEsposo=odbc_fetch_array($resultEsposo)) {
			$apellido_paterno_esposo=utf8_encode($registroEsposo['NO_APEL_PATE']);
			$apellido_materno_esposo=utf8_encode($registroEsposo['NO_APEL_MATE']);
			$nombre_esposo=utf8_encode($registroEsposo['NO_PARI']);
			$tipo_documento_esposo=$registroEsposo['No_Tipo_Doc_Iden'];
			$nro_documento_esposo=$registroEsposo['NU_DOCU_IDEN_PARI'];
			$estado_civil_esposo=$registroEsposo['No_Estado_Civil'];

			$nacionalidad_esposo=$registroEsposo['NO_PAIS'];
			$lugar_nacimiento_esposo=$registroEsposo['LUGAR_NACIMIENTO'];
			$fecha_nacimiento_esposo=$registroEsposo['FECHA_NACIMIENTO'];
			$genero_esposo=$registroEsposo['No_Genero'];
		}
?>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $apellido_paterno_esposo ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $apellido_materno_esposo ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $nombre_esposo ?></td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">TIPO DOCUMENTO DE IDENTIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">N° DOCUMENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">ESTADO CIVIL</td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $tipo_documento_esposo ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $nro_documento_esposo ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $estado_civil_esposo ?></td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">NACIONALIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">LUGAR DE NACIMIENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 20%;font-weight:bold;background-color: #C0C0C0">FECHA DE NACIMIENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%;font-weight:bold;background-color: #C0C0C0">SEXO</td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 30%; height: 2%"><?php echo $nacionalidad_esposo ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 30%; height: 2%"><?php echo $lugar_nacimiento_esposo ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 20%; height: 2%"><?php echo $fecha_nacimiento_esposo ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%; height: 2%"><?php echo $genero_esposo ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		<tr>
			<td>
				<span style="font-weight:bold; text-align: left;">HIJO(A) 1</span>
			</td>
		</tr>	
		<tr style="text-align: center; ">
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">APELLIDO PATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">APELLIDO MATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">NOMBRES</td>
		</tr>



<?php  
  
		$queryHijo1 = "SELECT
		TM_POSTULANTE_PARI.TI_DOCU_IDEN_PARI,
		T_TIPO_DOC_IDEN.No_Tipo_Doc_Iden,
		TM_POSTULANTE_PARI.NU_DOCU_IDEN_PARI, 
		TM_POSTULANTE_PARI.NO_APEL_PATE,
		TM_POSTULANTE_PARI.NO_APEL_MATE,
		TM_POSTULANTE_PARI.NO_PARI,
		T_GENERO.No_Genero,
		CONVERT(CHAR(10), CONVERT(date, TM_POSTULANTE_PARI.FE_NACI_PARI, 112), 103) AS FECHA_NACIMIENTO,
		T_ESTADO_CIVIL.No_Estado_Civil,
		T_UBIGEO.NO_DEPARTAMENTO,
		T_UBIGEO.NO_PROVINCIA+', '+T_UBIGEO.NO_DISTRITO AS LUGAR_NACIMIENTO,
		vPAIS.NO_PAIS

		FROM TM_POSTULANTE_PARI

		LEFT JOIN T_TIPO_DOC_IDEN ON T_TIPO_DOC_IDEN.Id_Tipo_Doc_Iden=TM_POSTULANTE_PARI.TI_DOCU_IDEN_PARI
		LEFT JOIN T_GENERO ON T_GENERO.Id_Genero=TM_POSTULANTE_PARI.ST_SEXO_PARI
		LEFT JOIN T_ESTADO_CIVIL ON T_ESTADO_CIVIL.Id_Estado_Civil=TM_POSTULANTE_PARI.CO_ESTA_CIVI
		LEFT JOIN SELECCION.DBO.T_UBIGEO ON T_UBIGEO.ID_UBIGEO=TM_POSTULANTE_PARI.CO_LUGA_NACI
		LEFT JOIN SELECCION.DBO.vPAIS ON vPAIS.CO_PAIS=TM_POSTULANTE_PARI.CO_PAIS_NCIO

		WHERE TM_POSTULANTE_PARI.NU_DOCU_IDEN  LIKE'%".$dni_postulante."%'
		AND TM_POSTULANTE_PARI.TI_DOCU_IDEN='".$codigo_tipo_documento."'
		AND TM_POSTULANTE_PARI.NU_CORR_PARI='2'
	
		";
		$resultHijo1 = odbc_exec($conexion,$queryHijo1);
		//echo $queryHijo1;
		while($registroHijo1=odbc_fetch_array($resultHijo1)) {
			$apellido_paterno_hijo1=utf8_encode($registroHijo1['NO_APEL_PATE']);
			$apellido_materno_hijo1=utf8_encode($registroHijo1['NO_APEL_MATE']);
			$nombre_hijo1=utf8_encode($registroHijo1['NO_PARI']);
			$tipo_documento_hijo1=$registroHijo1['No_Tipo_Doc_Iden'];
			$nro_documento_hijo1=$registroHijo1['NU_DOCU_IDEN_PARI'];
			$estado_civil_hijo1=$registroHijo1['No_Estado_Civil'];

			$nacionalidad_hijo1=$registroHijo1['NO_PAIS'];
			$lugar_nacimiento_hijo1=$registroHijo1['LUGAR_NACIMIENTO'];
			$fecha_nacimiento_hijo1=$registroHijo1['FECHA_NACIMIENTO'];
			$genero_hijo1=$registroHijo1['No_Genero'];
		}
?>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $apellido_paterno_hijo1 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $apellido_materno_hijo1 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $nombre_hijo1 ?></td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">TIPO DOCUMENTO DE IDENTIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">N° DOCUMENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">FECHA DE NACIMIENTO</td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $tipo_documento_hijo1 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $nro_documento_hijo1 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $fecha_nacimiento_hijo1 ?></td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">NACIONALIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">LUGAR DE NACIMIENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">SEXO</td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%; height: 2%"><?php echo $nacionalidad_hijo1 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%; height: 2%"><?php echo $lugar_nacimiento_hijo1 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%; height: 2%"><?php echo $genero_hijo1 ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		<tr>
			<td>
				<span style="font-weight:bold; text-align: left;">HIJO(A) 2</span>
			</td>
		</tr>	
		<tr style="text-align: center; ">
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">APELLIDO PATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">APELLIDO MATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">NOMBRES</td>
		</tr>



<?php  
  
		$queryHijo2 = "SELECT
		TM_POSTULANTE_PARI.TI_DOCU_IDEN_PARI,
		T_TIPO_DOC_IDEN.No_Tipo_Doc_Iden,
		TM_POSTULANTE_PARI.NU_DOCU_IDEN_PARI, 
		TM_POSTULANTE_PARI.NO_APEL_PATE,
		TM_POSTULANTE_PARI.NO_APEL_MATE,
		TM_POSTULANTE_PARI.NO_PARI,
		T_GENERO.No_Genero,
		CONVERT(CHAR(10), CONVERT(date, TM_POSTULANTE_PARI.FE_NACI_PARI, 112), 103) AS FECHA_NACIMIENTO,
		T_ESTADO_CIVIL.No_Estado_Civil,
		T_UBIGEO.NO_DEPARTAMENTO,
		T_UBIGEO.NO_PROVINCIA+', '+T_UBIGEO.NO_DISTRITO AS LUGAR_NACIMIENTO,
		vPAIS.NO_PAIS

		FROM TM_POSTULANTE_PARI

		LEFT JOIN T_TIPO_DOC_IDEN ON T_TIPO_DOC_IDEN.Id_Tipo_Doc_Iden=TM_POSTULANTE_PARI.TI_DOCU_IDEN_PARI
		LEFT JOIN T_GENERO ON T_GENERO.Id_Genero=TM_POSTULANTE_PARI.ST_SEXO_PARI
		LEFT JOIN T_ESTADO_CIVIL ON T_ESTADO_CIVIL.Id_Estado_Civil=TM_POSTULANTE_PARI.CO_ESTA_CIVI
		LEFT JOIN SELECCION.DBO.T_UBIGEO ON T_UBIGEO.ID_UBIGEO=TM_POSTULANTE_PARI.CO_LUGA_NACI
		LEFT JOIN SELECCION.DBO.vPAIS ON vPAIS.CO_PAIS=TM_POSTULANTE_PARI.CO_PAIS_NCIO

		WHERE TM_POSTULANTE_PARI.NU_DOCU_IDEN  LIKE'%".$dni_postulante."%'
		AND TM_POSTULANTE_PARI.TI_DOCU_IDEN='".$codigo_tipo_documento."'
		AND TM_POSTULANTE_PARI.NU_CORR_PARI='3'
	
		";
		$resultHijo2 = odbc_exec($conexion,$queryHijo2);
		//echo $queryHijo2;
		while($registroHijo2=odbc_fetch_array($resultHijo2)) {
			$apellido_paterno_hijo2=utf8_encode($registroHijo2['NO_APEL_PATE']);
			$apellido_materno_hijo2=utf8_encode($registroHijo2['NO_APEL_MATE']);
			$nombre_hijo2=utf8_encode($registroHijo2['NO_PARI']);
			$tipo_documento_hijo2=$registroHijo2['No_Tipo_Doc_Iden'];
			$nro_documento_hijo2=$registroHijo2['NU_DOCU_IDEN_PARI'];
			$estado_civil_hijo2=$registroHijo2['No_Estado_Civil'];

			$nacionalidad_hijo2=$registroHijo2['NO_PAIS'];
			$lugar_nacimiento_hijo2=$registroHijo2['LUGAR_NACIMIENTO'];
			$fecha_nacimiento_hijo2=$registroHijo2['FECHA_NACIMIENTO'];
			$genero_hijo2=$registroHijo2['No_Genero'];
		}
?>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $apellido_paterno_hijo2 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $apellido_materno_hijo2 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $nombre_hijo2 ?></td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">TIPO DOCUMENTO DE IDENTIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">N° DOCUMENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">FECHA DE NACIMIENTO</td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $tipo_documento_hijo2 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $nro_documento_hijo2 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $fecha_nacimiento_hijo2 ?></td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">NACIONALIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">LUGAR DE NACIMIENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">SEXO</td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%; height: 2%"><?php echo $nacionalidad_hijo2 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%; height: 2%"><?php echo $lugar_nacimiento_hijo2 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%; height: 2%"><?php echo $genero_hijo2 ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		<tr>
			<td>
				<span style="font-weight:bold; text-align: left;">HIJO(A) 3</span>
			</td>
		</tr>	
		<tr style="text-align: center; ">
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">APELLIDO PATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">APELLIDO MATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">NOMBRES</td>
		</tr>



<?php  
  
		$queryHijo3 = "SELECT
		TM_POSTULANTE_PARI.TI_DOCU_IDEN_PARI,
		T_TIPO_DOC_IDEN.No_Tipo_Doc_Iden,
		TM_POSTULANTE_PARI.NU_DOCU_IDEN_PARI, 
		TM_POSTULANTE_PARI.NO_APEL_PATE,
		TM_POSTULANTE_PARI.NO_APEL_MATE,
		TM_POSTULANTE_PARI.NO_PARI,
		T_GENERO.No_Genero,
		CONVERT(CHAR(10), CONVERT(date, TM_POSTULANTE_PARI.FE_NACI_PARI, 112), 103) AS FECHA_NACIMIENTO,
		T_ESTADO_CIVIL.No_Estado_Civil,
		T_UBIGEO.NO_DEPARTAMENTO,
		T_UBIGEO.NO_PROVINCIA+', '+T_UBIGEO.NO_DISTRITO AS LUGAR_NACIMIENTO,
		vPAIS.NO_PAIS

		FROM TM_POSTULANTE_PARI

		LEFT JOIN T_TIPO_DOC_IDEN ON T_TIPO_DOC_IDEN.Id_Tipo_Doc_Iden=TM_POSTULANTE_PARI.TI_DOCU_IDEN_PARI
		LEFT JOIN T_GENERO ON T_GENERO.Id_Genero=TM_POSTULANTE_PARI.ST_SEXO_PARI
		LEFT JOIN T_ESTADO_CIVIL ON T_ESTADO_CIVIL.Id_Estado_Civil=TM_POSTULANTE_PARI.CO_ESTA_CIVI
		LEFT JOIN SELECCION.DBO.T_UBIGEO ON T_UBIGEO.ID_UBIGEO=TM_POSTULANTE_PARI.CO_LUGA_NACI
		LEFT JOIN SELECCION.DBO.vPAIS ON vPAIS.CO_PAIS=TM_POSTULANTE_PARI.CO_PAIS_NCIO

		WHERE TM_POSTULANTE_PARI.NU_DOCU_IDEN  LIKE'%".$dni_postulante."%'
		AND TM_POSTULANTE_PARI.TI_DOCU_IDEN='".$codigo_tipo_documento."'
		AND TM_POSTULANTE_PARI.NU_CORR_PARI='4'
	
		";
		$resultHijo3 = odbc_exec($conexion,$queryHijo3);
		//echo $queryHijo3;
		while($registroHijo3=odbc_fetch_array($resultHijo3)) {
			$apellido_paterno_hijo3=utf8_encode($registroHijo3['NO_APEL_PATE']);
			$apellido_materno_hijo3=utf8_encode($registroHijo3['NO_APEL_MATE']);
			$nombre_hijo3=utf8_encode($registroHijo3['NO_PARI']);
			$tipo_documento_hijo3=$registroHijo3['No_Tipo_Doc_Iden'];
			$nro_documento_hijo3=$registroHijo3['NU_DOCU_IDEN_PARI'];
			$estado_civil_hijo3=$registroHijo3['No_Estado_Civil'];

			$nacionalidad_hijo3=$registroHijo3['NO_PAIS'];
			$lugar_nacimiento_hijo3=$registroHijo3['LUGAR_NACIMIENTO'];
			$fecha_nacimiento_hijo3=$registroHijo3['FECHA_NACIMIENTO'];
			$genero_hijo3=$registroHijo3['No_Genero'];
		}
?>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $apellido_paterno_hijo3 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $apellido_materno_hijo3 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $nombre_hijo3 ?></td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">TIPO DOCUMENTO DE IDENTIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">N° DOCUMENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">FECHA DE NACIMIENTO</td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $tipo_documento_hijo3 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $nro_documento_hijo3 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $fecha_nacimiento_hijo3 ?></td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">NACIONALIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">LUGAR DE NACIMIENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">SEXO</td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%; height: 2%"><?php echo $nacionalidad_hijo3 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%; height: 2%"><?php echo $lugar_nacimiento_hijo3 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%; height: 2%"><?php echo $genero_hijo3 ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		<tr>
			<td>
				<span style="font-weight:bold; text-align: left;">HIJO(A) 4</span>
			</td>
		</tr>	
		<tr style="text-align: center; ">
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">APELLIDO PATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">APELLIDO MATERNO</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">NOMBRES</td>
		</tr>



<?php  
  
		$queryHijo4 = "SELECT
		TM_POSTULANTE_PARI.TI_DOCU_IDEN_PARI,
		T_TIPO_DOC_IDEN.No_Tipo_Doc_Iden,
		TM_POSTULANTE_PARI.NU_DOCU_IDEN_PARI, 
		TM_POSTULANTE_PARI.NO_APEL_PATE,
		TM_POSTULANTE_PARI.NO_APEL_MATE,
		TM_POSTULANTE_PARI.NO_PARI,
		T_GENERO.No_Genero,
		CONVERT(CHAR(10), CONVERT(date, TM_POSTULANTE_PARI.FE_NACI_PARI, 112), 103) AS FECHA_NACIMIENTO,
		T_ESTADO_CIVIL.No_Estado_Civil,
		T_UBIGEO.NO_DEPARTAMENTO,
		T_UBIGEO.NO_PROVINCIA+', '+T_UBIGEO.NO_DISTRITO AS LUGAR_NACIMIENTO,
		vPAIS.NO_PAIS

		FROM TM_POSTULANTE_PARI

		LEFT JOIN T_TIPO_DOC_IDEN ON T_TIPO_DOC_IDEN.Id_Tipo_Doc_Iden=TM_POSTULANTE_PARI.TI_DOCU_IDEN_PARI
		LEFT JOIN T_GENERO ON T_GENERO.Id_Genero=TM_POSTULANTE_PARI.ST_SEXO_PARI
		LEFT JOIN T_ESTADO_CIVIL ON T_ESTADO_CIVIL.Id_Estado_Civil=TM_POSTULANTE_PARI.CO_ESTA_CIVI
		LEFT JOIN SELECCION.DBO.T_UBIGEO ON T_UBIGEO.ID_UBIGEO=TM_POSTULANTE_PARI.CO_LUGA_NACI
		LEFT JOIN SELECCION.DBO.vPAIS ON vPAIS.CO_PAIS=TM_POSTULANTE_PARI.CO_PAIS_NCIO

		WHERE TM_POSTULANTE_PARI.NU_DOCU_IDEN  LIKE'%".$dni_postulante."%'
		AND TM_POSTULANTE_PARI.TI_DOCU_IDEN='".$codigo_tipo_documento."'
		AND TM_POSTULANTE_PARI.NU_CORR_PARI='5'
	
		";
		$resultHijo4 = odbc_exec($conexion,$queryHijo4);
		//echo $queryHijo4;
		while($registroHijo4=odbc_fetch_array($resultHijo4)) {
			$apellido_paterno_hijo4=utf8_encode($registroHijo4['NO_APEL_PATE']);
			$apellido_materno_hijo4=utf8_encode($registroHijo4['NO_APEL_MATE']);
			$nombre_hijo4=utf8_encode($registroHijo4['NO_PARI']);
			$tipo_documento_hijo4=$registroHijo4['No_Tipo_Doc_Iden'];
			$nro_documento_hijo4=$registroHijo4['NU_DOCU_IDEN_PARI'];
			$estado_civil_hijo4=$registroHijo4['No_Estado_Civil'];

			$nacionalidad_hijo4=$registroHijo4['NO_PAIS'];
			$lugar_nacimiento_hijo4=$registroHijo4['LUGAR_NACIMIENTO'];
			$fecha_nacimiento_hijo4=$registroHijo4['FECHA_NACIMIENTO'];
			$genero_hijo4=$registroHijo4['No_Genero'];
		}
?>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $apellido_paterno_hijo4 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $apellido_materno_hijo4 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $nombre_hijo4 ?></td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">TIPO DOCUMENTO DE IDENTIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">N° DOCUMENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">FECHA DE NACIMIENTO</td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $tipo_documento_hijo4 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;height: 2%"><?php echo $nro_documento_hijo4 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;height: 2%"><?php echo $fecha_nacimiento_hijo4 ?></td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">NACIONALIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%;font-weight:bold;background-color: #C0C0C0">LUGAR DE NACIMIENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-weight:bold;background-color: #C0C0C0">SEXO</td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%; height: 2%"><?php echo $nacionalidad_hijo4 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 30%; height: 2%"><?php echo $lugar_nacimiento_hijo4 ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%; height: 2%"><?php echo $genero_hijo4 ?></td>
		</tr>
	</table>
</page>
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle;font-size:11px;">
		<tr>
			<td>
				<span style="font-weight:bold; ">V. DATOS DE VIVIENDA</span>
			</td>
		</tr>
		<tr>
			<td>
				<span>Indicar su domicilio actual y los siguientes datos de su vivienda.</span>
			</td>
		</tr>
		
	</table>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
	
		<tr style="text-align: center; ">
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-weight:bold;background-color: #C0C0C0">DIRECCIÓN</td>
			
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%; height: 2%"><?php echo $direccion_postu.' - '.$nombre_distrito_postu ?></td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
	
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 25%;font-weight:bold;background-color: #C0C0C0">TIPO DE CASA</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 25%;font-weight:bold;background-color: #C0C0C0">MATERIAL DE VIVIENDA</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 10%;font-weight:bold;background-color: #C0C0C0">SERVICIO DE AGUA</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 10%;font-weight:bold;background-color: #C0C0C0">SERVICIO DE DESAGÜE</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 10%;font-weight:bold;background-color: #C0C0C0">SERVICIO DE LUZ</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 10%;font-weight:bold;background-color: #C0C0C0">SERVICIO DE INTERNET</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 10%;font-weight:bold;background-color: #C0C0C0">SERVICIO DE CABLE</td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 25%; height: 2%"><?php echo $tipo_casa_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 25%; height: 2%"><?php echo $material_vivienda_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 10%; height: 2%"><?php echo $agua_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 10%; height: 2%"><?php echo $desague_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 10%; height: 2%"><?php echo $luz_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 10%; height: 2%"><?php echo $internet_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 10%; height: 2%"><?php echo $cable_postu ?></td>
			
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle;font-size:11px;">
		<tr>
			<td>
				<span style="font-weight:bold; ">VI. DATOS DE SALUD</span>
			</td>
		</tr>
		<tr>
			<td>
				<span>Indicar su estado de salud.</span>
			</td>
		</tr>
		
	</table>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
	
		<tr style="text-align: center; ">
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0">A SUFRIDO ACCIDENTES:</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;"><?php echo $accidente_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0">ENFERMEDAD:</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;"><?php echo $enfermedad_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0">OPERACIONES:</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 21%;"><?php echo $operacion_postu ?></td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0">ESPECIFIQUE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;height: 2%"><?php echo $observacion_accidente_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0">ESPECIFIQUE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;height: 2%"><?php echo $observacion_enfermedad_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0">ESPECIFIQUE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 21%;height: 2%"><?php echo $observacion_operacion_postu ?></td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0">ES ALÉRGICO:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;"><?php echo $alergia_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0">SUFRE DE LA COLUMNA:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;"><?php echo $columna_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0">PRACTICA ALGUN DEPORTE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 21%;"><?php echo $deporte_postu ?></td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0;height: 2%">ESPECIFIQUE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;height: 2%"><?php echo $observacion_alergia_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0;height: 2%">ESPECIFIQUE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;height: 2%"><?php echo $observacion_columna_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0;height: 2%">ESPECIFIQUE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 21%;height: 2%"><?php echo $observacion_deporte_postu ?></td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle;font-size:11px;">
		<tr>
			<td>
				<span>En caso de ser del sexo femenino</span>
			</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle;font-size:11px;">
		<tr style="text-align: center; ">
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 25%;font-weight:bold;background-color: #C0C0C0">DURANTE LOS ÚLTIMOS 12 MESES, USTED SE HA ATENDIDO POR PROBLEMAS GINECOLÓGICOS:</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 25%;"><?php echo $ginecologico_postu ?></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 25%;font-weight:bold;background-color: #C0C0C0">TIENE ALGUNA PATOLOGIA GINECOLÓGICA:</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 25%;"><?php echo $patologia_ginecologica_postu ?></td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 25%;font-weight:bold;background-color: #C0C0C0;height: 2%">ESPECIFIQUE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 25%;height: 2%"><?php echo $observacion_ginecologico_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 25%;font-weight:bold;background-color: #C0C0C0;height: 2%">ESPECIFIQUE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 25%;height: 2%"><?php echo $observacion_patologia_ginecologica_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle;font-size:11px;">
		<tr>
			<td>
				<span style="font-weight:bold; ">VII. DATOS DE ANTECEDENTES</span>
			</td>
		</tr>
		
	</table>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		<tr style="text-align: center; ">
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%;font-weight:bold;background-color: #C0C0C0">Presenta antecedentes policiales y/o judiciales por denuncia de procesos judiciales formulados en su contra, ante las autoridades policiales, fiscales y judiciale</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%;"><?php echo $antecedente_policial_postu ?></td>
		</tr>
		<tr style="text-align: center; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%;font-weight:bold;background-color: #C0C0C0;height: 2%">ESPECIFIQUE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%;height: 2%"><?php echo $observacion_antecedente_policial_postu ?></td>
		</tr>
	</table>
</page>

<page backtop="20mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 35%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold; ">DECLARACIÓN JURADA</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle" >
		<tr>

            <td style="width: 70%;">
            	<table  cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
	            	<tr>
	            		<td style="width: 100%;font-size:11px;text-align: left;">
			                <span>Declaro bajo juramento que la información aquí consignada es verdadera, en caso contrario podrá considerarse causal de rescisión del vinculo laboral.</span>
			            </td>
	            	</tr>
	            	<tr>
			        	<td style="width: 100%;font-size:11px;text-align: left;">
			                <span>Declaro asimismo tener pleno conocimiento que <?php echo $des_emp ?> en uso de su facultad de administración, señalará el lugar donde realizaré mis labores, el mismo que puede ser modificado sin previa consulta conforme a ley.</span>
			            </td>
					</tr>
					<br>
					<tr>
			        	<td style="width: 100%;font-size:11px;text-align: left;">
			                <span><?php echo $des_emp ?> se reserva el derecho de validar todos los datos incluidos en el presente documento, siendo de su potestad la designación de la persona o empresa, para que realice dicha verificación.</span>
			            </td>
					</tr>
					
					
				
					
	            </table>
            </td>
			<td style="width: 30%;">
            	<table  cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
	            	<tr>
	            		<td >
			               <img src="../../inc/img/huella.png" style='width: 125px; height: 185px'>
			            </td>
	            	</tr>
	            	
	            </table>
            </td>
		</tr>
	</table>
	<br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle" >
		<tr>
        	<td style="width: 100%;font-size:11px;text-align: left;">
                <span>___________________________________________</span>
            </td>
		</tr>
		<tr>
        	<td style="width: 100%;font-size:11px;text-align: left;">
                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FIRMA DEL COLABORADOR</span>
            </td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle" >
		<tr>
        	<td style="width: 100%;font-size:11px;text-align: left;">
                <span style="font-weight:bold;">* Todo cambio de dirección deberá ser notificado al empleador, de manera obligatoria y por escrito en un plazo máximo de 15 días naturales, caso contrario será considerado como motivo de sanción.</span>
            </td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
		<tr>
        	<td style="width: 100%; color: black;font-size:12px;text-align:middle;font-weight:bold; color: white; background-color: black;text-align: center;height: 2px">DECLARACIÓN JURADA DE DATOS PERSONALES</td>
		</tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%;">
        <tr>
			<td >
                <span style="color: black;font-size:12px; ">Especificar tipo de contratación:</span>
            </td>
        </tr>
    </table>
	<br>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		<tr style="text-align: center; ">
			<td style="width: 50%;">
				<table>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONTRATO A PLAZO INDETERMINADO</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONTRATO A TIEMPO PARCIAL</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONTRATO POR INICIO O INCREM. ACTIVIDAD</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONTRATO POR NECESIDAD DE MERCADO</span>
						</td>
					</tr>

					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONTRATO POR RECONVERSION EMPRESARIAL</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONTRATO OCASIONAL</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONTRATO DE SUPLENCIA</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONTRATO EMERGENCIA</span>
						</td>
					</tr>
				</table>
			</td>
			<td style="width: 50%;">
				<table>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONT. OBRA DETERMINADA O SERV. ESPEC</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONTRATO INTERMITENTE</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONTRATO TEMPORADA</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>TRABAJADOR EMP. CONTRATISTA</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONVENIO CAPACITACIÓN LABORAL JUVENIL</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONVENIO PRACTICAS PRE PROFESIONALES</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONVENIO PRACTICAS PROFESIONALES</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; ">
							<img src="../../inc/img/foto.png" style='width: 15px; height: 10px'>
						</td>
						<td>
							<span>CONTRATO DE LOCACION SERVICIO</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%;">
        <tr>
			<td >
                <span style="color: black;font-size:12px; ">Especificar ocupación ( Ver Tabla)</span>
            </td>
        </tr>
    </table>
    <br>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		<tr style="text-align: center; ">
			<td style="width: 50%;">
				<table cellspacing="0" style="width: 100%;">
			        <tr>
						<td >
			                <span style="color: black;font-size:12px; ">Especificar tipo de horario:</span>
			            </td>
			        </tr>
			    </table>
				<table>
					
					<tr style="text-align: left; ">
						<td>
							<img src="../../inc/img/foto.png" style='width: 20px; height: 20px'>
						</td>
						<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; font-weight:bold;background-color: #C0C0C0;height: 2%">
							<span>HORARIO ROTATIVO</span>
						</td>
					</tr>
					<tr style="text-align: left; ">
						<td>
							<img src="../../inc/img/foto.png" style='width: 20px; height: 20px'>
						</td>
						<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; font-weight:bold;background-color: #C0C0C0;height: 2%">
							<span>HORARIO FIJO</span>
						</td>
					</tr>
				</table>
				<br>
				<table cellspacing="0" style="width: 100%;">
			        <tr>
						<td >
			                <span style="color: black;font-size:12px; ">Especificar forma de pago:___________</span>
			            </td>
			        </tr>
			    </table>
			</td>
			<td style="width: 50%;">
				<table cellspacing="0" style="width: 100%;">
			        <tr>
						<td >
			                <span style="color: black;font-size:12px; ">Especificar Nro de Horas:</span>
			            </td>
			        </tr>
			    </table>
				<table>
					
					<tr style="text-align: left; ">
						<td>
							<img src="../../inc/img/foto.png" style='width: 30px; height: 30px'>
						</td>
						
					</tr>
				</table>
				<br><br>
				<table cellspacing="0" style="width: 100%;">
			        <tr>
						<td >
			                <span style="color: black;font-size:12px; ">Remuneración acordada:___________</span>
			            </td>
			        </tr>
			    </table>
			</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%;  vertical-align: middle;font-size:11px;">
		<tr style="width: 100%; text-align: center" >
			<td >
				<span>Domiciliado:</span>
			</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000; height: 4px;width: 20px">
				<span>SI</span>
			</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; height: 4px;width: 20px">
				<span>NO</span>
			</td>
		</tr>

	</table>
</page>
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
        <page_footer>
        <table >
            <tr>

                <td style="width: 50%; text-align: left">
                  
                </td>
                
            </tr>
        </table>
    </page_footer>

    <table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
        <tr>
        	<?php  
		    	if ($co_emp=='01') {
		    ?>
		    <td>
           		<img src="../../inc/img/cabecera-constancia-tailoy.png" style='width: 700px; height: 180px'>
           	</td>
           	<?php  
           		}elseif ($co_emp=='02') {
           	?>
           	<td>
           		<img src="../../inc/img/cabecera-constancia-luciano.png" style='width: 750px; height: 180px'>
           	</td>
           	<?php  
           		}elseif ($co_emp=='03') {
           	?>
           	<td>
           		<img src="../../inc/img/cabecera-constancia-copy.png" style='width: 750px; height: 180px'>
           	</td>
           	<?php  
           		}elseif ($co_emp=='04') {
           	?>

           	<td>
           		<img src="../../inc/img/cabecera-constancia-suplacorp.png" style='width: 750px; height: 180px'>
           	</td>
           	<?php  
           		}elseif ($co_emp=='06') {
           	?>

           	<td>
           		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../../assets/images/tailoy-bg/06.png" style='  max-width: 80px; height: 80px; float: right;'  />
           		<br><br><br><br>
           	</td>
           	<?php  
           		}
			?>
        </tr>
	</table>
    <br><br>
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 35%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;text-decoration: underline black;">DECLARACIÓN JURADA DE PARENTESCO</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%;">
    	<tr>
            <td style="width: 45%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;">(ley N° 26771)</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>

    <br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:12px;">Por la presente, el que suscribe <?php echo $nom_postu ?> identificado con N° de <?php echo $tipo_documento ?> <?php echo $dni_postulante ?>, declaro bajo juramento que no me une parentezco hasta de tercer grado con algún personal de la Empresa <?php echo $des_emp ?></span>
           	</td>
           	
        </tr>

	</table>
	<br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">Ratifico la veracidad de lo declarado, por lo cual firmo e imprimo mi huella digital en el presente documento.</span>
           	</td>
           	
        </tr>

	</table>
	<br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Lima, <?php echo $fecha_ingreso_letras ?></span>
           	</td>
           	
        </tr>

	</table>
	
	<br><br><br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Firma ______________________________</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;"><?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?></span>
           	</td>
           	
        </tr>

	</table>
</page>

<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
        <page_footer>
        <table >
            <tr>

                <td style="width: 50%; text-align: left">
                  
                </td>
                
            </tr>
        </table>
    </page_footer>

    <table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle" >
        <tr>
        	<?php  
		    	if ($co_emp=='01') {
		    ?>
		    <td>
           		<img src="../../inc/img/cabecera-constancia-tailoy.png" style='width: 700px; height: 180px'>
           	</td>
           	<?php  
           		}elseif ($co_emp=='02') {
           	?>
           	<td>
           		<img src="../../inc/img/cabecera-constancia-luciano.png" style='width: 750px; height: 180px'>
           	</td>
           	<?php  
           		}elseif ($co_emp=='03') {
           	?>
           	<td>
           		<img src="../../inc/img/cabecera-constancia-copy.png" style='width: 750px; height: 180px'>
           	</td>
           	<?php  
           		}elseif ($co_emp=='04') {
           	?>

           	<td>
           		<img src="../../inc/img/cabecera-constancia-suplacorp.png" style='width: 750px; height: 180px'>
           	</td>
           	<?php  
           		}elseif ($co_emp=='06') {
           	?>

           	<td>
           		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../../assets/images/tailoy-bg/06.png" style='  max-width: 80px; height: 80px; float: right;'  />

           	</td>
           	<?php  
           		}
			?>
        </tr>
	</table>
    <br><br>
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 35%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;text-decoration: underline black;">DECLARACIÓN JURADA DE DOMICILIO</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:12px;">Por la presente, el que suscribe <?php echo $nom_postu ?> identificado con Nº <?php echo $tipo_documento ?> <?php echo $dni_postulante ?>, declaro bajo juramento que, en la actualidad mi domicilio real es <?php echo $direccion_postu ?> - <?php echo $nombre_distrito_postu ?> para los efectos de acreditar mi residencia en el procedimiento administrativo de Certificación domiciliaria que sigo ante la Empresa <?php echo $des_emp ?></span>
           	</td>
           	
        </tr>

	</table>
	<br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">Me afirmo y me ratifico en lo expresado, en señal de lo cual firmo e imprimo mi huella digital en el presente documento.</span>
           	</td>
           	
        </tr>

	</table>
	<br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Lima, <?php echo $fecha_ingreso_letras ?></span>
           	</td>
           	
        </tr>

	</table>
	
	<br><br><br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Firma ______________________________</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;"><?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?></span>
           	</td>
           	
        </tr>

	</table>
</page>

<!--
001--TIENDAS
002--ALMACEN
003--OFICINA

-->

<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 11pt; font-family: arial" >
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 35%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">ANEXO AL CONTRATO DE TRABAJO</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%;">
    	<tr>
            <td style="width: 45%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">(TIENDAS)</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%;">
    	<tr>
         
            <td style="width: 10%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 90%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">RECOMENDACIONES Y FUNCIONES EN MATERIA DE SEGURIDAD Y SALUD EN EL TRABAJO</span>
            </td>
			
			
        </tr>
    </table>
    <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">1. Recomendaciones de carácter general</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			- El trabajador debe leer, interiorizar y cumplir con el Reglamento Interno de Seguridad y Salud en el Trabajo de la Empresa, así como acatar las normas internas y políticas en materia de seguridad y salud en el trabajo.
					<br>- Reportar de modo inmediato accidentes de trabajo, incidentes peligrosos o cualquier otro tipo de situación que altere o
					ponga en riesgo la vida, integridad física y psicológica de los trabajadores suscitados en el ámbito laboral, según los
					mecanismos previstos en la Empresa.
					<br>- Comunicar de modo inmediato la pérdida o deterioro de los Equipos de Protección Personal correspondientes.
					<br>- No ingresar a zonas y áreas no autorizadas.
					<br>- Conocer la ubicación de los extintores y asegurarse de saber utilizarlos.
					<br>- De estar presente en una activación del sistema de alarmas, deberá conservar la calma y esperar instrucciones.
					<br>- Ejecutar sus actividades diarias de trabajo según lo estipulado en los procedimientos de trabajo seguro del Sistema de
					Gestión de Seguridad y Salud en el Trabajo de Tai Loy S.A.
					<br>- Trabaje a una velocidad normal, sin apuros peligrosos y este siempre alerta. Los apuramientos como correr en los pasillos, escaleras y otros ambientes son riesgosos y son causa de accidentes.
					<br>- Respete los letreros, señales o indicadores de prevención, éstos se encuentran para resaltar partes peligros o riesgos
					potenciales que pueden causar accidentes de trabajo.

           		</span>
           	</td>
        </tr>
    </table>
    <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">2.- Riesgos laborales del puesto de trabajo:</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			- Contractura muscular cervical por malas posturas.
					<br>- Fatiga visual por sobreesfuerzo visual.
					<br>- Estrés por exceso de trabajo y público agresivo.
					<br>- Golpes, contusiones, caídas por espacios estrechos y escaleras manuales.
					<br>- Lumbalgia, várices por trabajo de pie por tiempo prolongado.
					<br>- Traumatismos craneales por objetos inestables en altura.
					<br>- Fracturas, laceraciones. Lesiones osteomusculares por uso de carretillas manuales.
					<br>- Heridas punzo cortantes por uso de herramientas de corte.
					<br>- Contractura muscular, lumbalgias y cervicalgias por movimientos repetidos.
					<br>- Golpes, contusiones y esguinces por resbalones en piso húmedo, espacio desordenado y estrecho.

           		</span>
           	</td>
        </tr>
    </table>
     <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">3.- Recomendaciones en prevención de accidentes de trabajo, incidentes de trabajo y enfermedades ocupacionales.</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			- No deberá usar escaleras con peldaños, o largueros laterales rotos, partidos o con otro desperfecto.
					<br>- No inclinarse sobre los costados o tratar de estirarse mientras se está subido en una escalera esta acción es peligrosa, y esta mala práctica puede originar accidentes.
					<br>- Cuando se utilice una escalera tipo tijera, ábrala completamente, no hacer uso de la misma como escalera simple.
					<br>- No se debe dejar herramientas o cargas encima de las escaleras, estas pueden caerse y ocasionar accidentes. Colocar
					estas en lugares firmes y o en cajas especiales que están sujetas a las escaleras.
					<br>- Cuando suba o baje una escalera, hágalo de frente a la misma.
					<br>- No debe improvisarse en la construcción o mantenimiento de las escaleras.
					<br>- Siempre debe usarse una escalera para subir a algún lado. Las sillas, cajas y otros sustitutos pueden causarle una caída.
					<br>- Cuando suba una escalera preste atención a fin de estar seguro que no se golpeará la cabeza.
					<br>- Contractura muscular, lumbalgias y cervicalgias por movimientos repetidos.
					<br>- Para un levantamiento de carga correcto límpiese las manos de sustancias grasos antes de levantar una carga, Párese
					firmemente y levante el objeto con un movimiento suave y parejo, mantenga sus brazos y espalda tan derechos como le sea posible, doble sus rodillas y luego haga fuerza con los músculos de las piernas.
					<br>- Cuando levante un objeto pesado, mueva éste hacia su cuerpo a la altura del pecho hasta que esté en posición de levantar
					derecho.
					<br>- No levante nunca una carga en posición torcida.
					<br>- Si necesita levantar un objeto ubicado sobre un banco, estante, etc., coloque éste tan cerca de su cuerpo como sea posible, cójalo firmemente, mantenga su espalda derecha y levante con sus piernas.
					<br>- Respete el límite de peso máximo de carga por persona (25 Kg. para los hombres y 15 Kg. Para las mujeres).
					<br>- Cuando trabaje con máquinas o equipos debe estar atento a los peatones, estos son los más vulnerables a sufrir un
					accidente de trabajo.
					<br>- Antes de realizar cualquier mantenimiento o limpieza, las maquinas o equipos deben estar apagados y debe ser realizado
					por personal calificado.
					<br>- No alterar o dañar ninguna parte de las maquinas o equipos, esto puede afectar el funcionamiento normal de estos y puede ocasionar accidentes.


           		</span>
           	</td>
        </tr>
    </table>
</page>
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 11pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			- Informar al jefe inmediato o al supervisor de SST si observa maquinas o equipos que tengan ruedas en mal estado, mangos
					rotos u otros defectos.
					<br>- Cargar las máquinas o equipos de forma tal que se pueda ver sobre y alrededor de la carga.
					<br>- Antes de usar el equipo, revisar los frenos, la dirección, la corneta, los cauchos y el mecanismos de levantamiento. Dicha revisión quedará documentada en el formato de inspección diaria de montacargas.
					<br>- Siempre, abrochar el cinturón de seguridad, éstos reducen grandes daños.
					<br>- Usar el casco de seguridad.
					<br>- Manejar en posición erguida y mirando al frente.
					<br>- Observar las señales de tránsito.
					<br>- Respetar la velocidad máxima reglamentada para la conducción de montacargas.
					<br>- Aproximarse despacio a las esquinas "ciegas", manteniéndose en su derecha y sonando el claxon.
					<br>- Manipular solo bultos dentro del rango de la capacidad del equipo, tal como se muestra en la placa de identificación.
					<br>- Estacionar el montacargas en lugar adecuado y diseñado para ello, de manera que no interfiera con el paso de otros
					vehículos o personas.
					<br>- Mantenga los pasillos y lugares de trabajo limpios; las herramientas, equipos, escaleras y materiales en buen estado,
					apilados y colocados en forma segura de manera que el personal que transite no se lesione.
					<br>- Guarde los desperdicios, trapos engrasados y otros materiales inflamables en recipientes metálicos y con tapa destinados a este fin.
					<br>- Los recipientes para la basura no deben ser llenados en exceso, cuando estos se encuentres llenos hasta dos tercios de su
					capacidad máxima, deben ser limpiados.
					<br>- Mantenga las salidas despejadas en todo momento.
					<br>- Mantenga las escaleras y descansos de las mismas libres de materiales.
					<br>- No acumule materiales sobre los rociadores o equipos de lucha contra el fuego, estos deben estar siempre visibles y en
					lugares de libre acceso.
					<br>- No deberá permitirse la acumulación de los desperdicios sobre el piso. Deben ser limpiados regularmente.
					<br>- Cuando haya agua, aceite u otras sustancias derramadas sobre el piso debe ser limpiadas inmediatamente para evitar
					resbalones.
					<br>- En caso no se pueda limpiar inmediatamente el piso se debe utilizar aserrín u otro material similar absorbente sobre el
					derrame hasta que el mismo pueda ser limpiado.
					<br>- No intente reparar o ajustar ningún equipo electrónico, esto será hecho únicamente por personal calificado y autorizado por la empresa.
					<br>- No sobrecargue los tomacorrientes.
					<br>- Al ingresar a su lugar de trabajo debe familiarizarse con los elementos de lucha contra incendios que se encuentran
					distribuidos en el lugar de trabajo.
					<br>- Al detectar un amago de incendio (principio de incendio) si es posible debe extinguirlo, caso contrario dará la alarma y/o comunicara inmediatamente a la brigada de lucha contra incendios, después de realizar lo anterior procederá a abandonar las instalaciones hacia una zona seguro hasta que el jefe o supervisor de SST informe que es seguro ingresar nuevamente al
					lugar de trabajo.
					<br>- No se almacenarán trapos de limpieza u otros materiales inflamables o combustibles cerca de fuentes de ignición o energía, ni se guardarán trapos sucios con aceites, pinturas, etc. En cajas de herramientas, equipos o vehículos.
					<br>- No se almacenarán papeles, plásticos, cartones, cualquier material inflamable cerca de fuentes de ignición o energía.
					<br>- No se emplearan nafta, gas-oíl o solventes inflamables para la limpieza de piezas, herramientas, equipos, vehículos, ropas,
					etc.


           		</span>
           	</td>
        </tr>
    </table>
     <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">4. Funciones en Seguridad y Salud</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			- Cumplir las instrucciones de seguridad aplicables al puesto de trabajo.
					<br>- Incentivar a través de su participación activa, el cumplimiento de los estándares de las actividades programadas en materia de seguridad y salud en el trabajo y efectuar las correcciones que resulten necesarias.
					<br>- Participar en las capacitaciones de SST programadas Reportar incidentes/accidentes, Reportar acciones correctivas/
					preventivas. Participar en los simulacros programados.
					<br>- Aplicar y respetar lo dispuesto en el Reglamento Interno de Seguridad y Salud en el Trabajo (RISST).
					<br>- Reportar prontamente cualquier situación que afecte la Seguridad y Salud en el Trabajo.




           		</span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Apellidos y Nombres: <?php echo $nom_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;"><?php echo $tipo_documento ?> Nro.: <?php echo $dni_postulante ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Fecha: Lima, <?php echo $fecha_ingreso_letras ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Cargo: <?php echo $puesto_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Firma ______________________________</span>
           	</td>
           	
        </tr>

	</table>
</page>

<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 11pt; font-family: arial" >
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 35%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">ANEXO AL CONTRATO DE TRABAJO</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%;">
    	<tr>
            <td style="width: 45%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">(TIENDAS)</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%;">
    	<tr>
         
            <td style="width: 10%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 90%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">RECOMENDACIONES Y FUNCIONES EN MATERIA DE SEGURIDAD Y SALUD EN EL TRABAJO</span>
            </td>
			
			
        </tr>
    </table>
    <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">1. Recomendaciones de carácter general</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			-	El trabajador debe leer, interiorizar y cumplir con el Reglamento Interno de Seguridad y Salud en el Trabajo de la Empresa, así como acatar las normas internas y políticas en materia de seguridad y salud en el trabajo.
					<br>-	Reportar de modo inmediato accidentes de trabajo, incidentes peligrosos o cualquier otro tipo de situación que altere o ponga en riesgo la vida, integridad física y psicológica de los trabajadores suscitados en el ámbito laboral, según los mecanismos previstos en la Empresa.
					<br>-	Comunicar de modo inmediato la pérdida o deterioro de los Equipos de Protección Personal correspondientes.
					<br>-	No ingresar a zonas y áreas no autorizadas.
					<br>-	Conocer la ubicación de los extintores y asegurarse de saber utilizarlos.
					<br>-	De estar presente en una activación del sistema de alarmas, deberá conservar la calma y esperar instrucciones.
					<br>-	Ejecutar sus actividades diarias de trabajo según lo estipulado en los procedimientos de trabajo seguro del Sistema de Gestión de Seguridad y Salud en el Trabajo de Librería Bazar Santa Maria EIRL
					<br>-	Trabaje a una velocidad normal, sin apuros peligrosos y este siempre alerta. Los apuramientos como correr en los pasillos, escaleras y otros ambientes son riesgosos y son causa de accidentes.
					<br>-	Respete los letreros, señales o indicadores de prevención, éstos se encuentran para resaltar partes peligros o riesgos potenciales que pueden causar accidentes de trabajo.


           		</span>
           	</td>
        </tr>
    </table>
    <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">2.- Riesgos laborales del puesto de trabajo:</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			-	Contractura muscular cervical por malas posturas.
					<br>-	Fatiga visual por sobreesfuerzo visual.
					<br>-	Estrés por exceso de trabajo y público agresivo.
					<br>-	Golpes, contusiones, caídas por espacios estrechos y escaleras manuales.
					<br>-	Lumbalgia, várices por trabajo de pie por tiempo prolongado.
					<br>-	Traumatismos craneales por objetos inestables en altura.
					<br>-	Fracturas, laceraciones. Lesiones osteomusculares por uso de carretillas manuales.
					<br>-	Heridas punzo cortantes por uso de herramientas de corte.
					<br>-	Contractura muscular, lumbalgias y cervicalgias por movimientos repetidos.
					<br>-	Golpes, contusiones y esguinces por resbalones en piso húmedo, espacio desordenado y estrecho.

           		</span>
           	</td>
        </tr>
    </table>
     <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">3.- Recomendaciones en prevención de accidentes de trabajo, incidentes de trabajo y enfermedades ocupacionales:</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			-	No deberá usar escaleras con peldaños, o largueros laterales rotos, partidos o con otro desperfecto.
					<br>-	No inclinarse sobre los costados o tratar de estirarse mientras se está subido en una escalera esta acción es peligrosa, y esta mala práctica puede originar accidentes.
					<br>-	Cuando se utilice una escalera tipo tijera, ábrala completamente, no hacer uso de la misma como escalera simple.
					<br>-	No se debe dejar herramientas o cargas encima de las escaleras, estas pueden caerse y ocasionar accidentes. Colocar estas en lugares firmes y o en cajas especiales que están sujetas a las escaleras.
					<br>-	Cuando suba o baje una escalera, hágalo de frente a la misma.
					<br>-	No debe improvisarse en la construcción o mantenimiento de las escaleras.
					<br>-	Siempre debe usarse una escalera para subir a algún lado. Las sillas, cajas y otros sustitutos pueden causarle una caída.
					<br>-	Cuando suba una escalera preste atención a fin de estar seguro que no se golpeará la cabeza.
					<br>-	Para un levantamiento de carga correcto límpiese las manos de sustancias grasos antes de levantar una carga, Párese firmemente y levante el objeto con un movimiento suave y parejo, mantenga sus brazos y espalda tan derechos como le sea posible, doble sus rodillas y luego haga fuerza con los músculos de las piernas.
					<br>-	Cuando levante un objeto pesado, mueva éste hacia su cuerpo a la altura del pecho hasta que esté en posición de levantar derecho.
					<br>-	No levante nunca una carga en posición torcida.
					<br>-	Si necesita levantar un objeto ubicado sobre un banco, estante, etc., coloque éste tan cerca de su cuerpo como sea posible, cójalo firmemente, mantenga su espalda derecha y levante con sus piernas.
					<br>-	Respete el límite de peso máximo de carga por persona (25 Kg. para los hombres y 15 Kg. Para las mujeres).
					<br>-	Cuando trabaje con máquinas o equipos debe estar atento a los peatones, estos son los más vulnerables a sufrir un accidente de trabajo.
					<br>-	Antes de realizar cualquier mantenimiento o limpieza, las maquinas o equipos deben estar apagados y debe ser realizado por personal calificado.
					<br>-	No alterar o dañar ninguna parte de las maquinas o equipos, esto puede afectar el funcionamiento normal de estos y puede ocasionar accidentes.
					



           		</span>
           	</td>
        </tr>
    </table>
</page>
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 11pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			
           			-	Informar al jefe inmediato o al supervisor de SST si observa maquinas o equipos que tengan ruedas en mal estado, mangos rotos u otros defectos.
					<br>-	Cargar las máquinas o equipos de forma tal que se pueda ver sobre y alrededor de la carga.
					<br>-	Antes de usar el equipo, revisar los frenos, la dirección, la corneta, los cauchos y el mecanismos de levantamiento. Dicha revisión quedará documentada en el formato de inspección diaria de montacargas.
					<br>-	Siempre, abrochar el cinturón de seguridad, éstos reducen grandes daños.
					<br>-	Usar el casco de seguridad.
					<br>-	Manejar en posición erguida y mirando al frente.
					<br>-	Observar las señales de tránsito.
					<br>-	Respetar la velocidad máxima reglamentada para la conducción de montacargas.
           			<br>-	Aproximarse despacio a las esquinas “ciegas”, manteniéndose en su derecha y sonando el claxon.
					<br>-	Manipular solo bultos dentro del rango de la capacidad del equipo, tal como se muestra en la placa de identificación.
					<br>-	Estacionar el montacargas en lugar adecuado y diseñado para ello, de manera que no interfiera con el paso de otros vehículos o personas.
					<br>-	Mantenga los pasillos y lugares de trabajo limpios; las herramientas, equipos, escaleras y materiales en buen estado, apilados y colocados en forma segura de manera que el personal que transite no se lesione.
					<br>-	Guarde los desperdicios, trapos engrasados y otros materiales inflamables en recipientes metálicos y con tapa destinados a este fin.
					<br>-	Los recipientes para la basura no deben ser llenados en exceso, cuando estos se encuentres llenos hasta dos tercios de su capacidad máxima, deben ser limpiados.
					<br>-	Mantenga las salidas despejadas en todo momento.
					<br>-	Mantenga las escaleras y descansos de las mismas libres de materiales.
					<br>-	No acumule materiales sobre los rociadores o equipos de lucha contra el fuego, estos deben estar siempre visibles y en lugares de libre acceso.
					<br>-	No deberá permitirse la acumulación de los desperdicios sobre el piso. Deben ser limpiados regularmente.
					<br>-	Cuando haya agua, aceite u otras sustancias derramadas sobre el piso debe ser limpiadas inmediatamente para evitar resbalones. 
					<br>-	En caso no se pueda limpiar inmediatamente el piso se debe utilizar aserrín u otro material similar absorbente sobre el derrame hasta que el mismo pueda ser limpiado.
					<br>-	No intente reparar o ajustar ningún equipo electrónico, esto será hecho únicamente por personal calificado y autorizado por la empresa.
					<br>-	No sobrecargue los tomacorrientes.
					<br>-	Al ingresar a su lugar de trabajo debe familiarizarse con los elementos de lucha contra incendios que se encuentran distribuidos en el lugar de trabajo.
					<br>-	Al detectar un amago de incendio (principio de incendio) si es posible debe extinguirlo, caso contrario dará la alarma y/o comunicara inmediatamente a la brigada de lucha contra incendios, después de realizar lo anterior procederá a abandonar las instalaciones hacia una zona seguro hasta que el jefe o supervisor de SST informe que es seguro ingresar nuevamente al lugar de trabajo.
					<br>-	No se almacenarán trapos de limpieza u otros materiales inflamables o combustibles cerca de fuentes de ignición o energía, ni se guardarán trapos sucios con aceites, pinturas, etc. En cajas de herramientas, equipos o vehículos.
					<br>-	No se almacenarán papeles, plásticos, cartones, cualquier material inflamable cerca de fuentes de ignición o energía.
					<br>-	No se emplearan nafta, gas-oíl o solventes inflamables para la limpieza de piezas, herramientas, equipos, vehículos, ropas, etc.

           		</span>
           	</td>
        </tr>
    </table>
     <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">4. Funciones en Seguridad y Salud</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			-	Cumplir las instrucciones de seguridad aplicables al puesto de trabajo.
					<br>-	Incentivar a través de su participación activa, el cumplimiento de los estándares de las actividades programadas en materia de seguridad y salud en el trabajo y efectuar las correcciones que resulten necesarias. 
					<br>-	Participar en las capacitaciones de SST programadas Reportar incidentes/accidentes, Reportar acciones correctivas/ preventivas. Participar en los simulacros programados.
					<br>-	Aplicar y respetar lo dispuesto en el Reglamento Interno de Seguridad y Salud en el Trabajo (RISST).
					<br>-	Reportar prontamente cualquier situación que afecte la Seguridad y Salud en el Trabajo.





           		</span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Apellidos y Nombres: <?php echo $nom_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;"><?php echo $tipo_documento ?> Nro.: <?php echo $dni_postulante ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Fecha: Lima, <?php echo $fecha_ingreso_letras ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Cargo: <?php echo $puesto_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Firma ______________________________</span>
           	</td>
           	
        </tr>

	</table>
</page>

<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 11pt; font-family: arial" >
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 35%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">ANEXO AL CONTRATO DE TRABAJO</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%;">
    	<tr>
            <td style="width: 45%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">(ALMACÉN)</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%;">
    	<tr>
         
            <td style="width: 10%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 90%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">RECOMENDACIONES Y FUNCIONES EN MATERIA DE SEGURIDAD Y SALUD EN EL TRABAJO</span>
            </td>
			
			
        </tr>
    </table>
    <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">1. Recomendaciones de carácter general</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:13px;font-size: 9pt;">
           			- El trabajador debe leer, interiorizar y cumplir con el Reglamento Interno de Seguridad y Salud en el Trabajo de la Empresa, así como acatar las normas internas y políticas en materia de seguridad y salud en el trabajo.
					<br>- Reportar de modo inmediato accidentes de trabajo, incidentes peligrosos o cualquier otro tipo de situación que altere o ponga en riesgo la vida, integridad física y psicológica de los trabajadores suscitados en el ámbito laboral, según los mecanismos previstos en la Empresa.
					<br>- Comunicar de modo inmediato la pérdida o deterioro de los Equipos de Protección Personal correspondientes.
					<br>- No ingresar a zonas y áreas no autorizadas.
					<br>- Conocer la ubicación de los extintores y asegurarse de saber utilizarlos.
					<br>- De estar presente en una activación del sistema de alarmas, deberá conservar la calma y esperar instrucciones.
					<br>- Ejecutar sus actividades diarias de trabajo según lo estipulado en los procedimientos de trabajo seguro del Sistema de Gestión de Seguridad y Salud en el Trabajo de Tai Loy S.A.
					<br>- Trabaje a una velocidad normal, sin apuros peligrosos y este siempre alerta. Los apuramientos como correr en los  pasillos, escaleras y otros ambientes son riesgosos y son causa de accidentes.
					<br>- Respete los letreros, señales o indicadores de prevención, éstos se encuentran para resaltar partes peligros o riesgos potenciales que pueden causar accidentes de trabajo.


           		</span>
           	</td>
        </tr>
    </table>
    <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:13px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">2.- Riesgos laborales del puesto de trabajo:</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:13px;font-size: 9pt;">
           			- Lumbalgia por malas posturas.
					<br>- Contractura muscular, cervicalgia por trabjao de pie por tiempo prolongado.
					<br>- Electrocución y quemaduras por cables sueltos.
					<br>- Fatiga visual por sobre esfuerzo visual con iluminación deficiente.
					<br>- Contusiones en cabeza por objetos inestables por encima de 1.8m.
					<br>- Estrés por sobrecarga de trabajo.
					<br>- Fracturas, laceraciones, lesiones osteo musculares, por uso de estocas, accesos obstaculizados.
					<br>- Lesiones punzo cortantes por uso de herramientas de corte.
					<br>- Traumatismo craneal por caídas en trabajos de altura.
					<br>- Contusiones y esguinces, traumatismos por manipulación manual de carga, apilamiento de parihuelas.
					<br>- Dermatitis, irritación de piel por manejo de productos químicos en personal de limpieza.Enfermedades infecto contagiosas por limpieza de servicios higiénicos y recojo de residuos sólidos.
					<br>- Deshidratación por el calor generado por el termo sellador.

           		</span>
           	</td>
        </tr>
    </table>
     <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:13px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">3.- Recomendaciones en prevención de accidentes de trabajo, incidentes de trabajo y enfermedades ocupacionales.</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:13px;font-size: 9pt;">
           			- No deberá usar escaleras con peldaños, o largueros laterales rotos, partidos o con otro desperfecto.
					<br>- No inclinarse sobre los costados o tratar de estirarse mientras se está subido en una escalera esta acción es peligrosa, y esta mala práctica puede originar accidentes.
					<br>- Cuando se utilice una escalera tipo tijera, ábrala completamente, no hacer uso de la misma como escalera simple.
					<br>- No se debe dejar herramientas o cargas encima de las escaleras, estas pueden caerse y ocasionar accidentes. Colocar estas en lugares firmes y/o en cajas especiales que están sujetas a las escaleras.
					<br>- Cuando suba o baje una escalera, hágalo de frente a la misma.
					<br>- No debe improvisarse en la construcción o mantenimiento de las escaleras.
					<br>- Siempre debe usarse una escalera para subir a algún lado. Las sillas, cajas y otros sustitutos pueden causarle una caída.
					<br>- Cuando suba una escalera preste atención a fin de estar seguro que no se golpeará la cabeza.
					<br>- Para un levantamiento de carga correcto límpiese las manos de sustancias grasos antes de levantar una carga. Párese firmemente y levante el objeto con un movimiento suave y parejo, mantenga sus brazos y espalda tan derechos como le sea posible, doble sus rodillas y luego haga fuerza con los músculos de las piernas.
					<br>- Cuando levante un objeto pesado, mueva éste hacia su cuerpo a la altura del pecho hasta que esté en posición de levantar derecho. No levante nunca una carga en posición torcida.
					<br>- Si necesita levantar un objeto ubicado sobre un banco, estante, etc., coloque éste tan cerca de su cuerpo como sea posible, cójalo
					firmemente, mantenga su espalda derecha y levante con sus piernas.
					<br>- Respete el límite de peso máximo de carga por persona (25 Kg. para los hombres y 15 Kg. Para las mujeres).
					<br>- Cuando trabaje con máquinas o equipos debe estar atento a los peatones, estos son los más vulnerables a sufrir un accidente de trabajo.
					<br>- Antes de realizar cualquier mantenimiento o limpieza, las maquinas o equipos deben estar apagados y debe ser realizado por personal calificado.
					<br>- No alterar o dañar ninguna parte de las maquinas o equipos, esto puede afectar el funcionamiento normal de estos y puede ocasionar accidentes.


           		</span>
           	</td>
        </tr>
    </table>
</page>
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 11pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:13px;font-size: 9pt;">
           			- Informar al jefe inmediato o al supervisor de SST si observa maquinas o equipos que tengan ruedas en mal estado, mangos rotos u otros defectos.
					<br>- Cargar las máquinas o equipos de forma tal que se pueda ver sobre y alrededor de la carga.
					<br>- Antes de usar el equipo, revisar los frenos, la dirección, la corneta, los cauchos y el mecanismos de levantamiento.  Dicha revisión quedará documentada en el formato de inspección diaria de montacargas.
					<br>- Siempre, abrochar el cinturón de seguridad, éstos reducen grandes daños.
					<br>- Usar el casco de seguridad.
					<br>- Manejar en posición erguida y mirando al frente.
					<br>- Observar las señales de tránsito.
					<br>- Respetar la velocidad máxima reglamentada para la conducción de montacargas.
					<br>- Aproximarse despacio a las esquinas "ciegas", manteniéndose en su derecha y sonando el claxon.
					<br>- Manipular solo bultos dentro del rango de la capacidad del equipo, tal como se muestra en la placa de identificación.
					<br>- Estacionar el montacargas en lugar adecuado y diseñado para ello, de manera que no interfiera con el paso de otros vehículos o personas.
					<br>- Mantenga los pasillos y lugares de trabajo limpios; las herramientas, equipos, escaleras y materiales en buen estado, apilados y colocados en forma segura de manera que el personal que transite no se lesione.
					<br>- Guarde los desperdicios, trapos engrasados y otros materiales inflamables en recipientes metálicos y con tapa destinados a este fin.
					<br>- Los recipientes para la basura no deben ser llenados en exceso, cuando estos se encuentres llenos hasta dos tercios de su capacidad máxima, deben ser limpiados.
					<br>- Mantenga las salidas despejadas en todo momento.
					<br>- Mantenga las escaleras y descansos de las mismas libres de materiales.
					<br>- No acumule materiales sobre los rociadores o equipos de lucha contra el fuego, estos deben estar siempre visibles y en lugares de libre acceso.
					<br>- No deberá permitirse la acumulación de los desperdicios sobre el piso. Deben ser limpiados regularmente.
					<br>- Cuando haya agua, aceite u otras sustancias derramadas sobre el piso debe ser limpiadas inmediatamente para evitar resbalones.
					<br>- En caso no se pueda limpiar inmediatamente el piso se debe utilizar aserrín u otro material similar absorbente sobre el derrame hasta que el mismo pueda ser limpiado.
					<br>- No intente reparar o ajustar ningún equipo electrónico, esto será hecho únicamente por personal calificado y autorizado por la empresa.
					<br>- No sobrecargue los tomacorrientes.
					<br>- Al ingresar a su lugar de trabajo debe familiarizarse con los elementos de lucha contra incendios que se encuentran distribuidos en el lugar de trabajo.
					<br>- Al detectar un amago de incendio (principio de incendio) si es posible debe extinguirlo, caso contrario dará la alarma y/o comunicara inmediatamente a la brigada de lucha contra incendios, después de realizar lo anterior procederá a abandonar las instalaciones hacia una zona seguro hasta que el jefe o supervisor de SST informe que es seguro ingresar nuevamente al lugar de trabajo.
					<br>- No se almacenarán trapos de limpieza u otros materiales inflamables o combustibles cerca de fuentes de ignición o energía, ni se guardarán trapos sucios con aceites, pinturas, etc. En cajas de herramientas, equipos o vehículos.
					<br>- No se almacenarán papeles, plásticos, cartones, cualquier material inflamable cerca de fuentes de ignición o energía.
					<br>- No se emplearan nafta, gas-oíl o solventes inflamables para la limpieza de piezas, herramientas, equipos, vehículos, ropas, etc.
					<br>- Poner atención a la capacitación de ergonomía en almacenes para tener buenas posturas ergonómicas.
					<br>- Poner atención en la señalización y mantener con orden y limpieza su espacio de trabajo.
					<br>- Usar los Equipos de Protección Personal y tener la seguridad que las herramientas hechizas son inspeccionadas frecuentemente.


           		</span>
           	</td>
        </tr>
    </table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:13px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">4. Funciones en Seguridad y Salud</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:13px;font-size: 9pt;">
           			- Cumplir las instrucciones de seguridad aplicables al puesto de trabajo.
					<br>- Incentivar a través de su participación activa, el cumplimiento de los estándares de las actividades programadas en materia de seguridad y salud en el trabajo y efectuar las correcciones que resulten necesarias.
					<br>- Participar en las capacitaciones de SST programadas Reportar incidentes/accidentes, Reportar acciones correctivas/ preventivas. Participar en los simulacros programados.
					<br>- Aplicar y respetar lo dispuesto en el Reglamento Interno de Seguridad y Salud en el Trabajo (RISST).
					<br>- Reportar prontamente cualquier situación que afecte la Seguridad y Salud en el Trabajo.
           		</span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Apellidos y Nombres: <?php echo $nom_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;"><?php echo $tipo_documento ?> Nro.: <?php echo $dni_postulante ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Fecha: Lima, <?php echo $fecha_ingreso_letras ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Cargo: <?php echo $puesto_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Firma ______________________________</span>
           	</td>
           	
        </tr>

	</table>
</page>

<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 11pt; font-family: arial" >
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 35%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">ANEXO AL CONTRATO DE TRABAJO</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%;">
    	<tr>
            <td style="width: 37%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 50%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">(TRABAJOS ADMINISTRATIVOS)</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%;">
    	<tr>
         
            <td style="width: 10%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 90%; color: #444444;">
                <span style="color: black;font-size:12px;font-weight:bold;">RECOMENDACIONES Y FUNCIONES EN MATERIA DE SEGURIDAD Y SALUD EN EL TRABAJO</span>
            </td>
			
			
        </tr>
    </table>
    <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">1. Recomendaciones de carácter general</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			- El trabajador debe leer, interiorizar y cumplir con el Reglamento Interno de Seguridad y Salud en el Trabajo de la Empresa, así como acatar las normas internas y políticas en materia de seguridad y salud en el trabajo.
					<br>- Reportar de modo inmediato accidentes de trabajo, incidentes peligrosos o cualquier otro tipo de situación que altere o ponga en riesgo la vida, integridad física y psicológica de los trabajadores suscitados en el ámbito laboral, según los mecanismos previstos en la Empresa.
					<br>- Comunicar de modo inmediato la pérdida o deterioro de los Equipos de Protección Personal correspondientes.
					<br>- No ingresar a zonas y áreas no autorizadas.
					<br>- Conocer la ubicación de los extintores y asegurarse de saber utilizarlos.
					<br>- De estar presente en una activación del sistema de alarmas, deberá conservar la calma y esperar instrucciones.
					<br>- Ejecutar sus actividades diarias de trabajo según lo estipulado en los procedimientos de trabajo seguro del Sistema de Gestión de Seguridad y Salud en el Trabajo de Tai Loy S.A.
					<br>- Trabaje a una velocidad normal, sin apuros peligrosos y este siempre alerta. Los apuramientos como correr en los pasillos, escaleras y otros ambientes son riesgosos y son causa de accidentes.
					<br>- Respete los letreros, señales o indicadores de prevención, éstos se encuentran para resaltar partes peligros o riesgos potenciales que pueden causar accidentes de trabajo.

           		</span>
           	</td>
        </tr>
    </table>
    <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">2.- Riesgos laborales del puesto de trabajo:</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			- Contractura muscular cervical por malas posturas.
					<br>- Electrocución y quemaduras por cables sueltos.
					<br>- Golpes y Tropiezos por espacio reducido.
					<br>- Caídas y resbalones por falta de orden.
					<br>- Fatiga visual por uso prolongado de computadores.
					<br>- Estrés por carga de trabajo acumulada.
					<br>- Cervicalgia por malas posturas.
					<br>- Insolación a los colaboradores operativos de compras, cobros, mensajería y otros.
					<br>- Enfermedades respiratorias, gastroenterológicas por contagio.

           		</span>
           	</td>
        </tr>
    </table>
     <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">3.- Recomendaciones en prevención de accidentes de trabajo, incidentes de trabajo y enfermedades ocupacionales.</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			- Efectuar correctamente su trabajo, si desconoce o no entiende algún procedimiento o tarea solicitara ayuda del jefe inmediato y/o a su supervisor, con la finalidad de evitar incidentes o accidentes en el centro de trabajo.
					<br>- Respete los letreros, señales o indicadores de prevención, éstos se encuentran para resaltar avisos, peligros o riesgos potenciales que pueden causar accidentes de trabajo.
					<br>- Ponga todo lo que usa en su lugar apropiado; el desorden dificulta la identificación de los peligros, puede resultar en pérdida de tiempo, energía, material y puede causar lesiones. Mantenga su área limpia y ordenada.
					<br>- No obstaculizar las vías de tránsito peatonal ni colocar objetos que obstaculicen las vías de escape.
					<br>- Poner atención a la capacitación de ergonomía en la oficina para tener buenas posturas ergonómicas.
					<br>- Poner atención en la señalización y mantener con orden y limpieza su espacio de trabajo.


           		</span>
           	</td>
        </tr>
    </table>
     <br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;font-weight:bold;text-decoration: underline black;">4. Funciones en Seguridad y Salud</span>

           	</td>

           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">
           			- Cumplir las instrucciones de seguridad aplicables al puesto de trabajo.
					<br>- Incentivar a través de su participación activa, el cumplimiento de los estándares de las actividades programadas en materia de seguridad y salud en el trabajo y efectuar las correcciones que resulten necesarias.
					<br>- Participar en las capacitaciones de SST programadas Reportar incidentes/accidentes, Reportar acciones correctivas/ preventivas. Participar en los simulacros programados.
					<br>- Aplicar y respetar lo dispuesto en el Reglamento Interno de Seguridad y Salud en el Trabajo (RISST).
					<br>- Reportar prontamente cualquier situación que afecte la Seguridad y Salud en el Trabajo.



           		</span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Apellidos y Nombres: <?php echo $nom_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;"><?php echo $tipo_documento ?> Nro.: <?php echo $dni_postulante ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Fecha: Lima, <?php echo $fecha_ingreso_letras ?></span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;font-weight:bold;">Cargo: <?php echo $puesto_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Firma ______________________________</span>
           	</td>
           	
        </tr>

	</table>
</page>


<page backtop="25mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 11pt; font-family: arial" >
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 60%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;">FORMATO DE ELECCIÓN DEL SISTEMA PENSIONARIO</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-weight:bold;text-decoration: underline black; height: 20px">I. DATOS DEL TRABAJADOR</td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px">1. APELLIDO PATERNO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><?php echo $apellido_paterno_postu ?></td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px">2. APELLIDO MATERNO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><?php echo $apellido_materno_postu ?></td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px">3. NOMBRES</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><?php echo $nombre_completo_postu ?></td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px">4. DOCUMENTO DE IDENTIDAD</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><?php echo $tipo_documento ?>: <?php echo $dni_postulante ?></td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px">5. SEXO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><?php echo $genero_postu ?></td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px">6. FECHA DE NACIMIENTO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><b>Día</b> <?php echo substr($fecha_nacimiento_postu, 0,2) ?> <b>Mes</b> <?php echo substr($fecha_nacimiento_postu, 3,2) ?> <b>Año</b> <?php echo substr($fecha_nacimiento_postu, 6,4) ?></td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; " >
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px" rowspan="4">7. DOMICILIO</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><?php echo $direccion_postu?></td>
		</tr>
		
		<tr style="text-align: left; ">
			
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><b>Distrito: </b><?php echo $nombre_distrito_postu ?></td>
		</tr>
		<tr style="text-align: left; ">
			
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><b>Provincia: </b><?php echo $nombre_provincia_postu ?></td>
		</tr>
		<tr style="text-align: left; ">
			
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><b>Departamento: </b><?php echo $nombre_departamento_postu ?></td>
		</tr>
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-weight:bold;text-decoration: underline black; height: 20px">II. DATOS DE LA ENTIDAD EMPLEADORA</td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px">1. NOMBRE O RAZÓN SOCIAL</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><?php echo $des_emp ?></td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px">2. RUC</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><?php echo $numero_ruc ?></td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px"> 3. DPTO. DEL DOMICILIO FISCAL</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px">LIMA</td>
		</tr>
		
    </table>
  	<table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-weight:bold;text-decoration: underline black; height: 20px">III. DATOS DEL VÍNCULO LABORAL</td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px">1. FECHA DE INCIO DE LA RELACION LABORAL</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px"><b>Día</b> <?php echo substr($fecha_ingreso_numero, 0,2) ?> <b>Mes</b> <?php echo substr($fecha_ingreso_numero, 3,2) ?> <b>Año</b> <?php echo substr($fecha_ingreso_numero, 6,4) ?></td>
		</tr>
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px">2. REMUNERACIÓN</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px">S/. <?php echo $salario_postu ?>.00</td>
		</tr>
		
    </table>
    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-weight:bold;text-decoration: underline black; height: 20px">IV. ELECCIÓN DEL SISTEMA PENSIONARIO</td>
		</tr>
		
    </table>

    <table cellspacing="0" style="width: 100%;font-size: 10pt;">
    	<tr style="text-align: left; ">
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 0px solid #000000;width: 50%; height: 20px">
				<table cellspacing="0" style="width: 100%;font-size: 10pt;">
					<tr style="text-align: left; ">
						<td>
							<span>1. SISTEMA NACIONAL DE PENSIONES (ONP)</span>
						</td>
						<td>
							<?php if ($tipo_afp=='99' or $tipo_afp=='NA') { ?>

							<img src='../../images/icon/equis.png' style='    max-width: 30px;
						    	height: 30px;
						    	float: left;'>

							<?php }elseif ($tipo_afp!='99' or $tipo_afp!='NA'){ ?>
							<img src='../../images/icon/sinequis.png' style='    max-width: 30px;
						    	height: 30px;
						    	float: left;'>
							<?php } ?>
						</td>
					</tr>
				</table>
			</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 50%; height: 20px">
				<table cellspacing="0" style="width: 100%;font-size: 10pt;">
					<tr style="text-align: left; ">
						<td>
							<span>2. SISTEMA PRIVADO DE PENSIONES (AFP)</span>
						</td>
						<td>
							<?php if ($tipo_afp=='99' or $tipo_afp=='NA') { ?>

							<img src='../../images/icon/sinequis.png' style='    max-width: 30px;
						    	height: 30px;
						    	float: left;'>

							<?php }elseif ($tipo_afp!='99' or $tipo_afp!='NA'){ ?>
							<img src='../../images/icon/equis.png' style='    max-width: 30px;
						    	height: 30px;
						    	float: left;'>
							<?php } ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
    </table>
    <br><br><br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Firma del trabajador ______________________________</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Ciudad de Lima, <?php echo $fecha_ingreso_letras ?></span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Teléfono (Fijo/Celular): <?php echo $celular_postu ?> / <?php echo $fijo_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Correo electrónico: <?php echo $correo_postu ?></span>
           	</td>
           	
        </tr>

	</table>
</page>
<page backtop="25mm" backbottom="25mm" backleft="25mm" backright="25mm" style="font-size: 11pt; font-family: arial" >
    <table cellspacing="0" style="width: 100%;text-align: center;">
        <tr>
			<td style="width: 100%; color: #444444;text-align: center;">
                <span style="color: black;font-size:15px;font-weight:bold; text-align: center;">CONSTANCIA DE ENTREGA DEL BOLETÍN INFORMATIVO ACERCA DE LAS CARACTERÍSTICAS DEL SISTEMA PRIVADO DE PENSIONES (SPP) Y DEL SISTEMA NACIONAL DE PENSIONES (SNP)</span>
            </td>
		
			
        </tr>
    </table>
  
    <br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">Por medio del presente documento dejo constancia de:</span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">1. Haber recibido de parte de mi empleador <?php echo $des_emp ?> con RUC <?php echo $numero_ruc ?> los siguientes documentos:</span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">a. El Boletín Informativo acerca de las características del Sistema Privado de Pensiones (SPP) y del Sistema Nacional de Pensiones (SNP).</span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">b. El Formato de Elección del Sistema Pensionario, mediante el cual podré elegir el sistema de pensiones al cual deseo afiliarme.</span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">2. Conocer que en el caso de estar iniciando labores en esta empresa:</span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">a. Debo entregar a mi empleador el Formato de Elección del Sistema Pensionario manifestando mi decisión en un plazo máximo de 10 días calendarios, contados a partir de hoy.</span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">b. De no entregar a mi empleador el Formato de Elección del Sistema Pensionario manifestando mi decisión en el plazo de 10 días calendarios, contados a partir de hoy, seré afiliado por el mismo al Sistema Privado de Pensiones bajo las condiciones indicadas en el Boletín informativo que me ha sido entregado.</span>
           	</td>
        </tr>
    </table>
    <br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt; text-decoration: underline;">Datos del trabajador</span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">Apellidos y Nombres: <?php echo $nom_postu ?></span>
           	</td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size: 9pt;">Tipo y número de documento de identidad: <?php echo $tipo_documento ?> <?php echo $dni_postulante ?></span>
           	</td>
        </tr>
    </table>
    <br><br><br><br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Firma y huella digital ______________________________</span>
           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br><br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Ciudad de Lima, <?php echo $fecha_ingreso_letras ?></span>
           	</td>
           	
        </tr>

	</table>
</page>


<?php  
if ($co_emp=='04') {
?>

<page backtop="25mm" backbottom="15mm" backleft="20mm" backright="20mm" style="font-size: 11pt; font-family: arial" >
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 60%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;"><b><u>CARGO DE ENTREGA DEL POLITICA DE CALIDAD</u></b></span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:12px;">Por la presente, yo <?php echo $nom_postu ?> con N° de <?php echo $tipo_documento ?> <?php echo $dni_postulante ?>Trabajador(a) de la empresa SUPLACORP S.A.C en el cargo de <?php echo $puesto_postu ?> del Área de <?php echo $area_postu ?>, en pleno uso de mis facultades,  </span>
           	</td>
           	
        </tr>

	</table>
	<br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">DECLARO:</span>
           	</td>
           	
        </tr>
        <br>
        
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:12px;">Haber recibido en el día de la fecha, un ejemplar de la Política de Calidad de la empresa SUPLACORP S.A.C y haber sido informado que puedo encontrarla de manera virtual en la página web de la empresa en el siguiente link:  www.suplacorp.com.pe </span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:12px;">Asimismo, declaro haber tomado conocimiento de las disposiciones de la política en mención, las cuales aplicaré durante el normal desempeño de mis labores desde la fecha de mi ingreso a laborar en SUPLACORP S.A.C </span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:12px;">Sin perjuicio de lo indicado, asumo el compromiso de leer y estudiar la presente Política; además de cumplir y hacer cumplir, con mi mejor esfuerzo, las disposiciones y normas emanadas de éste, sin restricción o impedimento alguno, así como no difundir, copiar o plagiar en beneficio propio o de terceros la información contenida en la política, para lo cual firmo este documento, en señal de conformidad.</span>
           	</td>
           	
        </tr>

	</table>
	
		<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
	        <tr>
	        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
	                <span>FECHA: <?php echo $fecha_ingreso_letras ?><br><br><?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?></span>
	            </td>
				<td style="width: 35%; color: #444444;">
	            </td>
				<td style="width: 30%;">
	            	<table  cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		            	<tr>
		            		<td >
				               <img src="../../inc/img/huella.png" style='width: 125px; height: 185px'>
				            </td>
		            	</tr>
		            	
		            </table>
	            </td>
			   
	           	
	        </tr>

		</table>
	
		<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
	        <tr>
	        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
	                <span>___________________________________</span>
	            </td>
				
	           	
	        </tr>

		</table>
		<br><br><br>
		<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;"><u>NOTA:</u></span>
           	</td>
           	
        </tr>
        <br>
        
	</table>
	    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
	        <tr>
	        	
			    <td>
	           		<span style="line-height:20px;font-size:12px;">Esta hoja de cargo, deberá ser llenada y firmada por el (la) interesado(a) y devuelta al Área de Recursos Humanos, para su Archivo en el Legajo Personal correspondiente.</span>
	           	</td>
	           	
	        </tr>

		</table>
</page>

<page backtop="25mm" backbottom="15mm" backleft="20mm" backright="20mm" style="font-size: 11pt; font-family: arial" >
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 60%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;"><b><u>CARGO DE ENTREGA DEL POLITICA DE CALIDAD</u></b></span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:12px;">Por la presente, yo <?php echo $nom_postu ?> con N° de <?php echo $tipo_documento ?> <?php echo $dni_postulante ?>Trabajador(a) de la empresa SUPLACORP S.A.C en el cargo de <?php echo $puesto_postu ?> del Área de <?php echo $area_postu ?>, en pleno uso de mis facultades,  </span>
           	</td>
           	
        </tr>

	</table>
	<br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">DECLARO:</span>
           	</td>
           	
        </tr>
        <br>
        
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:12px;">Haber recibido en el día de la fecha, un ejemplar de la Política de Calidad de la empresa SUPLACORP S.A.C y haber sido informado que puedo encontrarla de manera virtual en la página web de la empresa en el siguiente link:  www.suplacorp.com.pe </span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:12px;">Asimismo, declaro haber tomado conocimiento de las disposiciones de la política en mención, las cuales aplicaré durante el normal desempeño de mis labores desde la fecha de mi ingreso a laborar en SUPLACORP S.A.C </span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:12px;">Sin perjuicio de lo indicado, asumo el compromiso de leer y estudiar la presente Política; además de cumplir y hacer cumplir, con mi mejor esfuerzo, las disposiciones y normas emanadas de éste, sin restricción o impedimento alguno, así como no difundir, copiar o plagiar en beneficio propio o de terceros la información contenida en la política, para lo cual firmo este documento, en señal de conformidad.</span>
           	</td>
           	
        </tr>

	</table>
	
		<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
	        <tr>
	        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
	                <span>FECHA: <?php echo $fecha_ingreso_letras ?><br><br><?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?></span>
	            </td>
				<td style="width: 35%; color: #444444;">
	            </td>
				<td style="width: 30%;">
	            	<table  cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		            	<tr>
		            		<td >
				               <img src="../../inc/img/huella.png" style='width: 125px; height: 185px'>
				            </td>
		            	</tr>
		            	
		            </table>
	            </td>
			   
	           	
	        </tr>

		</table>
	
		<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
	        <tr>
	        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
	                <span>___________________________________</span>
	            </td>
				
	           	
	        </tr>

		</table>
		<br><br><br>
		<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;"><u>NOTA:</u></span>
           	</td>
           	
        </tr>
        <br>
        
	</table>
	    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
	        <tr>
	        	
			    <td>
	           		<span style="line-height:20px;font-size:12px;">Esta hoja de cargo, deberá ser llenada y firmada por el (la) interesado(a) y devuelta al Área de Recursos Humanos, para su Archivo en el Legajo Personal correspondiente.</span>
	           	</td>
	           	
	        </tr>

		</table>
</page>
<?php } ?>




