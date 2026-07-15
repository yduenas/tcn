
<?php session_start();

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
	T_AFPS.DE_AFPS,
	T_VIAS.CO_TIPO_VIAS, 
    T_VIAS.DE_TIPO_VIAS,
    vTM_PUBLICACIONES2.CO_PROCESO,
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
	vPAIS.NO_PAIS,
	TM_POSTULANTE_EMPR.CO_TIPO_CONT,
	TM_POSTULANTE_EMPR.RUC_UNI, 
	TM_POSTULANTE_EMPR.NOM_UNI, 
	TM_POSTULANTE_EMPR.DIR_UNI, 
	TM_POSTULANTE_EMPR.REPRE_UNI, 
	TM_POSTULANTE_EMPR.DNI_REPRE, 
	TM_POSTULANTE_EMPR.RESP_PRA, 
	TM_POSTULANTE_EMPR.MISI_UNI, 
	TM_POSTULANTE_EMPR.FUNC_UNI, 
	TM_POSTULANTE_EMPR.CARR_UNI,
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
	TM_POSTULANTE_SALU.ANTE_POLICIAL_OBS
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

LEFT JOIN vPAIS ON vPAIS.CO_PAIS=TM_POSTULANTE_POST.CO_PAIS_NCIO

  WHERE TM_POSTULANTE_EMPR.NU_DOCU_IDEN='".$dni."'
    AND TM_POSTULANTE_EMPR.NU_CORR_POSTU='".$corre."'
 ";


$resultEmpresa = odbc_exec($conexion,$queryEmpresa);
//echo $conexion;
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
	    $dias_convenio=($meses+1).' meses';
	}else{
	  	$dias_convenio=$meses.' meses con '.$dias.' días';
	}

	$nacionalidad_postu=utf8_encode($registroEmpresa["NO_PAIS"]);

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

	$ruc_universidad_postu=$registroEmpresa['RUC_UNI'];
	$nombre_universidad_postu=utf8_encode($registroEmpresa['NOM_UNI']);
	$direccion_universidad_postu=utf8_encode($registroEmpresa['DIR_UNI']);
	$representante_universidad_postu=utf8_encode($registroEmpresa['REPRE_UNI']);
	$dni_representante_universidad_postu=$registroEmpresa['DNI_REPRE'];
	$responsable_practica_postu=utf8_encode($registroEmpresa['RESP_PRA']);
	$mision_postu=utf8_encode($registroEmpresa['MISI_UNI']);
	$funciones_postu=utf8_encode($registroEmpresa['FUNC_UNI']);
	$carrera_uni=utf8_encode($registroEmpresa['CARR_UNI']);


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
	
	if ($puesto_postu=='CAJERO' OR $puesto_postu=='GONDOLERO' OR $puesto_postu=='GONDOLERO DE TECNOLOGIA' OR $puesto_postu=='VENDEDOR DE MOSTRADOR')  {
		$ocupacion_convenio='APRENDIZ DE ASISTENTE EN VENTA AL DETALLE';
	}elseif ($puesto_postu=='SACADOR') {
		$ocupacion_convenio='APRENDIZ DE ASISTENTE EN LOGISTICA Y PRODUCCION';
	}



	if ($puesto_postu=='CAJERO' OR $puesto_postu=='GONDOLERO' OR $puesto_postu=='GONDOLERO DE TECNOLOGIA' OR $puesto_postu=='VENDEDOR DE MOSTRADOR') {
		$especialidad_convenio='ASISTENTE EN VENTA AL DETALLE';
	}elseif ($puesto_postu=='SACADOR') {
		$especialidad_convenio='ASISTENTE EN LOGISTICA Y PRODUCCION';
	}

	if ($puesto_postu=='SACADOR') {
    	$objetivos='a) Complementar la formación específica adquirida en el Centro de Formación <br>
		b) Consolidar el desarrollo de habilidades sociales y personales relacionadas al ámbito laboral';
    }elseif(($puesto_postu=='CAJERO') OR ($puesto_postu=='GONDOLERO') OR ($puesto_postu=='GONDOLERO DE TECNOLOGIA') OR ($puesto_postu=='VENDEDOR DE MOSTRADOR')){
    	$objetivos='a) Complementar la formación específica adquirida en el Centro de Formación según las modalidades especificadas en la Ley General de Educación<br>
		b) Consolidar el desarrollo de habilidades sociales y personales relacionadas al ámbito laboral';
    }


    if ($puesto_postu=='SACADOR') {
    	$funcion='Distribución de materiales en Almacén';
    }elseif(($puesto_postu=='CAJERO') OR ($puesto_postu=='GONDOLERO') OR ($puesto_postu=='GONDOLERO DE TECNOLOGIA') OR ($puesto_postu=='VENDEDOR DE MOSTRADOR')){
    	$funcion='Atención al cliente';

    }



    if ($puesto_postu=='SACADOR') {
    	$actividades='a) Almacenamiento <br>
			b) Control de Inventarios <br>
			c) Control de Stock';
    }elseif(($puesto_postu=='CAJERO') OR ($puesto_postu=='GONDOLERO') OR ($puesto_postu=='GONDOLERO DE TECNOLOGIA') OR ($puesto_postu=='VENDEDOR DE MOSTRADOR')){
    	$actividades='a) Asesoría y consultoría de servicios y/o productos <br>
			b) Satisfacción de necesidades específicas de sus clientes<br>
			c) Conocimiento específico de las ofertas';
    }


    if ($puesto_postu=='SACADOR') {
    	$competencia_ecpecifica_uno='1. Conocer y aplicar los sistemas de embalaje y distribución';
    	$indicador_logro_especifico_uno='1.1 Dominio de los sistemas de embalaje y distribución';
    }elseif(($puesto_postu=='CAJERO') OR ($puesto_postu=='GONDOLERO') OR ($puesto_postu=='GONDOLERO DE TECNOLOGIA') OR ($puesto_postu=='VENDEDOR DE MOSTRADOR')){
    	$competencia_ecpecifica_uno='1. Conocer y aplicar los sistemas de atención y servicios al cliente';
    	$indicador_logro_especifico_uno='1.1 Dominio de los sistemas de trabajo y servicios al cliente';
    }



	$competencia_ecpecifica_dos='2. Compromiso de aseguramiento de la calidad y mejoramiento continuo';
	$indicador_logro_especifico_dos='2.1 Sinergia proactiva';
 
	if ($puesto_postu=='SACADOR') {
    	$competencia_ecpecifica_tres='3. Capacidad de manejo de inventarios';
    	$indicador_logro_especifico_tres='3.1 Ejecución de un efectivo inventario de almacén';
    }else{
    	$competencia_ecpecifica_tres='3. Capacidad de manejo de ofertas y promociones, negociación';
    	$indicador_logro_especifico_tres='3.1 Ejecución de un efectivo cierre de ventas y servicio postventa';
    }



	if ($puesto_postu=='SACADOR') {
    	$competencia_ecpecifica_cuatro='4. Manejo de técnicas de exhibición y control de Stock';
    	$indicador_logro_especifico_cuatro='4.1 Dominio efectivo de las variables que se manejan en exhibición y stock';
    }else{
    	$competencia_ecpecifica_cuatro='4. Manejo de técnicas de exhibición';
    	$indicador_logro_especifico_cuatro='4.1 Dominio efectivo de las variables que se manejan para transmitir la propuesta comercial';
    }







    if ($puesto_postu=='SACADOR') {
    	$competencia_generica_uno='1. Adecuada capacidad numérica';
    	$indicador_logro_generica_uno='1.1 Rapidez de análisis y calculo numérico';
    }elseif(($puesto_postu=='CAJERO') OR ($puesto_postu=='GONDOLERO') OR ($puesto_postu=='GONDOLERO DE TECNOLOGIA') OR ($puesto_postu=='VENDEDOR DE MOSTRADOR')){
    	$competencia_generica_uno='1. Adecuada comunicación interpersonal';
    	$indicador_logro_generica_uno='1.1 comunicación fluida';
    }

    if ($puesto_postu=='SACADOR') {
    	$competencia_generica_dos='2. Desarrollo integral humano, organización';
    	$indicador_logro_generica_dos='2.1 Buen trato interpersonal, orden';
    }elseif(($puesto_postu=='CAJERO') OR ($puesto_postu=='GONDOLERO') OR ($puesto_postu=='GONDOLERO DE TECNOLOGIA') OR ($puesto_postu=='VENDEDOR DE MOSTRADOR')){
    	$competencia_generica_dos='2. Desarrolo integral humano, motivación';
    	$indicador_logro_generica_dos='2.1 Buen trato interpersonal, manejo de emociones';
    }

	$competencia_generica_tres='3. Trabajo en equipo';
	$indicador_logro_generica_tres='3.1 Adquirir liderazgo en equipos de trabajo, respeto a las normas';


	$competencia_generica_cuatro='4. Autoestima';
	$indicador_logro_generica_cuatro='4.1 Sinergia proactiva, respeto por si mismo y por los demás';
    	

	$numero_casa=$registroEmpresa['NU_CASA'];



	
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
}else{
	$razon_social='INMOBILIARIA CASCAJAL S.A.C.</span><br> JR. MARIANO ODICIO N° 153  URB. MIRAFLORES <br> RUC 20465062356';
}

if ($co_emp=='01') {
  $empresa_ruc='TAI LOY S.A. con RUC N° 20100049181, (en adelante LA EMPRESA), declaro lo siguiente:';
}elseif ($co_emp=='02') {
  $empresa_ruc='COMERCIAL LUCIANO AREQUIPA S.A.C. con RUC Nº 20555146583, (en adelante LA EMPRESA), declaro lo siguiente:';
}elseif ($co_emp=='03') {
  $empresa_ruc='COPY VENTAS S.R.L. con RUC Nº 20132051322, (en adelante LA EMPRESA), declaro lo siguiente:';
}else{
  $empresa_ruc='SUPLACORP S.A.C., con R.U.C. No 20465062356 (en adelante LA EMPRESA), declaro lo siguiente:';
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


<?php 
if ($dni_postulante=='74810183') { ?>
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
                PRACTICANTE PROFESIONAL
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
				<img src="../../inc/img/sinnada.png" style="height: 18px">
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
				<img src="../../inc/img/siconequis.png" style="height: 18px">
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
				<img src="../../inc/img/siyno.png" style="height: 18px">
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
				<img src="../../inc/img/sinnada.png" style="height: 18px">
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
				<img src="../../inc/img/sinnada.png" style="height: 18px">
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
				<img src="../../inc/img/sinnada.png" style="height: 18px">
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
				<img src="../../inc/img/sinnada.png" style="height: 18px">
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
				<img src="../../inc/img/sinnada.png" style="height: 18px">
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
				<img src="../../inc/img/sinnada.png" style="height: 18px">
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
				<img src="../../inc/img/sinnada.png" style="height: 18px">
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
				<img src="../../inc/img/sinnada.png" style="height: 18px">
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
				<img src="../../inc/img/sinnada.png" style="height: 18px">
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
<?php } ?>

<page backtop="60mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
        
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
        	<?php  
		    	if ($co_emp=='01') {
		    ?>
		    <td>
           		<!--<img src="../../inc/img/cabecera-constancia-tailoy.png" style='width: 700px; height: 180px'>-->
           	</td>
           	<?php  
           		}elseif ($co_emp=='02') {
           	?>
           	<td>
           		<!--<img src="../../inc/img/cabecera-constancia-luciano.png" style='width: 750px; height: 180px'>-->
           	</td>
           	<?php  
           		}elseif ($co_emp=='03') {
           	?>
           	<td>
           		<!--<img src="../../inc/img/cabecera-constancia-copy.png" style='width: 750px; height: 180px'>-->
           	</td>
           	<?php  
           		}elseif ($co_emp=='04') {
           	?>

           	<td>
           		<!--<img src="../../inc/img/cabecera-constancia-suplacorp.png" style='width: 750px; height: 180px'>-->
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
                <span style="color: black;font-size:12px;font-weight:bold;text-decoration: underline black;  ">DECLARACIÓN JURADA</span>
            </td>
			<td style="width: 25%;text-align:right">
				
			</td>
			
        </tr>
    </table>

    <br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:25px;font-size:12px;">Yo, <?php echo $nom_postu ?>. Identificado con <?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?>, postulante para el puesto <?php echo $puesto_postu ?>. Para la empresa <?php echo $des_emp ?>.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">DECLARO BAJO JURAMENTO</span>
           	</td>
           	
        </tr>
        <br>
        
	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">Acepto se me descuente S/ 10.50 soles de mi salario por trámite de antecedentes policiales.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">Dichos documentos son indispensables para el inicio de labores y la firma de contrato.</span>
           	</td>
           	
        </tr>

	</table>
    <br><br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Lima, <?php echo $fecha_ingreso_letras ?></span>
           	</td>
           	
        </tr>
        <br>
        
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

<!--CONVENIO-->
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 80%; color: #444444;">
                <span style="color: black;font-size:17px;font-weight:bold;text-decoration: underline black;">CONVENIO DE PRÁCTICAS PROFESIONALES</span>
            </td>
			<td style="width: 10%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:13px;">Conste por el presente documento que se firma por triplicado, el Convenio de Practicas Profesionales, celebrado de conformidad con el artículo 13º y siguientes de la Ley Nº 28518, Ley sobre Modalidades Formativas Laborales, y su Reglamento, aprobado mediante el Decreto Supremo N° 007-2005-TR, que celebran entre LA EMPRESA y EL (LA) EGRESADO (A), identificados en este documento, de acuerdo a los términos y condiciones siguientes:</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">A. LA EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<br>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">RAZON SOCIAL </span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: TAI LOY S.A.</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">RUC</span>
           	</td>
           	<td style="width: 55%;">
           		<span style="l65e-height:20px;font-size:11px;">: 20100049181</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DOMICILIO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: JR. MARIANO ODICIO Nº 153 URB MIRAFLORES - SURQUILLO</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">ACTIVIDAD ECONOMICA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: VENTA DE ARTICULOS DE OFICINA Y ESCOLARES</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">REPRESENTANTE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: MANUEL HUARCAYA SILVA</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DOC. DE IDENTIDAD DEL REPRESENTANTE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: 06225424</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">B. EL(LA) EGRESADO(A)</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">NOMBRE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $nom_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">TIPO Y NUMERO DE IDENTIDAD</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">NACIONALIDAD</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $nacionalidad_postu ?></span>
           	</td>
           	
        </tr>
    </table>



    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">FECHA DE NACIMIENTO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $fecha_nacimiento_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">SEXO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $genero_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">ESTADO CIVIL</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $estado_civil_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DOMICILIO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $direccion_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">OCUPACION MATERIA DE LA CAPACITACION</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $puesto_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">CONDICIÓN</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: EGRESADO</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">PROFESIÓN UNIVERSITARIA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;"></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">O PROFESIÓN TÉCNICA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $carrera_uni ?></span>
           	</td>
           	
        </tr>
    </table>

    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">C. CONDICIONES DEL CONVENIO</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">PLAZO DE DURACIÓN</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $dias_convenio ?> desde el <?php echo $fecha_ingreso_letras ?> hasta el <?php echo $fecha_fin_letras ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DÍAS DE LAS PRÁCTICAS</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: Lunes a Sábado</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">HORARIO DE LAS PRÁCTICAS</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: Lunes a Viernes de 9:00 a.m a 18:00 p.m</span>
           	</td>
           	
        </tr>
    </table>
    
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;"></span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">  Refrigerio de 13:00 p.m a 14:00 p.m</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;"></span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">  Sábados: de 9:00 a.m a 14:00 p.m</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">SUBVENCIÓN ECONÓMICA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: S/. <?php echo $salario_postu ?>.00 (<?php echo $salario_letras_postu ?>)</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">ÁREA DONDE SE REALIZA EL APRENDIZAJE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $area_postu ?></span>
           	</td>
           	
        </tr>
    </table>
</page>

<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;text-decoration: underline;">CLÁUSULAS DEL CONVENIO</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">PRIMERO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;"><?php echo $nombre_universidad_postu ?> que presenta a EL(LA) EGRESADO(A) mediante comunicación de fecha <?php echo $fecha_ingreso_letras ?> presenta a EL (LA) EGRESADO (A) para que se le permita realizar sus Prácticas Profesionales en LA EMPRESA.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">SEGUNDO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">EL(LA) EGRESADO(A) manifiesta su interés en desarrollar sus Prácticas Profesionales para consolidar los aprendizajes adquiridos a lo largo de su formación profesional, así como ejecutar su desempeño en una situación real de trabajo y con los fines de obtener el grado (o título) correspondiente. Por su parte, LA EMPRESA acepta colaborar, tanto con el indicado Centro de Formación Profesional como con EL(LA) EGRESADO(A) en su tarea formativa, permitiéndole que realice su Práctica Profesional.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">TERCERO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">EL (LA) EGRESADO (A) desempeñará las actividades formativas como de <?php echo $puesto_postu ?> en el área de <?php echo $area_postu ?> en el domicilio de la empresa ubicado en <?php echo $direccion_tienda_postu ?> de acuerdo a las condiciones generales señalados en el literal c).</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">CUARTO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Para efectos del presente convenio LA EMPRESA, se obliga a:
<br>1) Brindar orientación y capacitación técnica y profesional a EL(LA) EGRESADO(A) dentro de su área de formación académica, así
como evaluar sus prácticas.
<br>2) Emitir los informes que requiera el Centro de Formación Profesional en relación con las prácticas de EL(LA) EGRESADO(A).
<br>3) No cobrar suma alguna por la Formación otorgada.
<br>4) Pagar puntualmente a EL(LA) EGRESADO(A) una subvención económica convenida.
<br>5) Otorgar EL (LA) EGRESADO (A) una subvención adicional equivalente a media subvención económica mensual cada seis meses
de duración continua de las prácticas.
<br>6) Otorgar un descanso de quince (15) días debidamente subvencionados cuando la duración de las prácticas sea superior a doce
(12) meses teniendo en cuenta la acumulación de los periodos intermitentes que hubiera realizado el practicante.
<br>7) Cubrir los riesgos de enfermedad y accidentes de EL(LA) EGRESADO(A), a través de un seguro privado con una cobertura equivalente a
catorce (14) subvenciones mensuales en caso de
enfermedad y treinta (30) por accidente.
<br>8) Expedir la certificación de Prácticas Profesionales correspondiente</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">QUINTO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Para efectos del presente convenio EL(LA) EGRESADO(A), se obliga a:
				<br>1) Suscribir el convenio de Prácticas con LA EMPRESA acatando las disposiciones formativas que se asigne.
				<br>2) Desarrollar sus Prácticas Profesionales con disciplina y responsabilidad.
				<br>3) Cumplir con el desarrollo del Plan de Prácticas que aplique LA EMPRESA;
				<br>4) Sujetarse a las disposiciones administrativas internas que le señale LA EMPRESA.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">SEXTO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">LA EMPRESA ha contratado el seguro MAPFRE PERU COMPAÑÍA DE SEGUROS Y REASEGUROS para cubrir los riesgos de enfermedad y accidentes de EL(LA) EGRESADO(A) .</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">SÉPTIMO:</span>
           	</td>
           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">LA EMPRESA concederá a EL(LA) EGRESADO(A) una subvención económica mensual de S/. <?php echo $salario_postu ?>.00 (<?php echo $salario_letras_postu ?>). <br>De conformidad con el artículo 47° de la Ley N° 28518, esta subvención económica mensual no tiene carácter remunerativo y no está afecta al pago del Impuesto a la Renta, otros impuestos,contribuciones ni aportaciones de ningún tipo a cargo de LA EMPRESA. <br>La subvención económica mensual no está sujeta a ningún tipo de retención a cargo de EL(LA) EGRESADO(A), salvo afiliación facultativa por parte de éste a un sistema pensionario.</span>
           	</td>
           	
        </tr>

	</table>
</page>

<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">OCTAVO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Las partes acuerdan la aplicación de las causas de modificación, suspensión y terminación del convenio, que se detallan a continuación: <br>Son causas de modificación del convenio:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">a) Por acuerdo entre EL(LA) PRACTICANTE y LA EMPRESA <br>Son causas de suspensión del convenio:
				<br>a) La enfermedad y el accidente comprobados, sin perjuicio de lo establecido en el numeral 7) de la cláusula cuarta del presente convenio.
				<br>b) Por descanso físico subvencionado en caso que el convenio se prorrogue a un plazo mayor de doce meses.
				<br>c) El permiso concedido por la empresa.
				<br>d) La sanción disciplinaria.
				<br>e) El caso fortuito o fuerza mayor.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Son causas de terminación del convenio:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">a) El cumplimiento del plazo estipulado en el literal C, Condiciones del Convenio, de las Condiciones Generales.
				<br>b) El mutuo disenso entre EL(LA) PRACTICANTE y LA EMPRESA.
				<br>c) El fallecimiento de EL(LA) PRACTICANTE.
				<br>d) La invalidez absoluta permanente.
				<br>e) No guardar reserva de toda la información y/o documentación que EL(LA) PRACTICANTE conozca durante el desarrollo de la práctica.
				<br>f) El incumplimiento de cualquiera de las obligaciones por parte de EL(LA) PRACTICANTE y específicamente las contempladas en la cláusula quinta del presente convenio.
				<br>g) Por renuncia o retiro voluntario por parte de EL(LA) PRACTICANTE, mediante aviso a LA EMPRESA con antelación de 10 días hábiles.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">NOVENO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">EL(LA) EGRESADO(A) declara conocer la naturaleza del presente convenio, el cual no tiene carácter laboral, de tal modo que sólo genera para las partes, los derechos y obligaciones específicamente previsto en el mismo y en el texto de la Ley N° 28518 y el Decreto Supremo N° 007-2005-TR.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">DÉCIMO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Para todos los efectos relacionados con el presente convenio, las partes señalan como su domicilio el que aparece consignado en la parte introductoria de éste, los cuales se tendrán por válidos en tanto la variación no haya sido comunicada por escrito a la otra parte. <br> Las partes, después de leído el presente convenio, se ratifican en su contenido y lo suscriben en señal de conformidad en tres ejemplares; el primero para LA EMPRESA, el segundo para EL(LA) EGRESADO(A), el tercero será puesto en conocimiento y registrado ante la Autoridad Administrativa de Trabajo dentro de los quince (15) días naturales de la suscripción, de lo que damos fe.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11px;">Suscrito en la ciudad de Lima, a los <?php echo $fecha_ingreso_letras ?>.</span>
           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span><?php echo $nom_postu ?></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span><?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>HUARCAYA SILVA MANUEL ENRIQUE</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>DNI NRO. 06225424</span>
				
			</td>
		   
           	
        </tr>

	</table>
	
	<br><br>
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

<!--CONVENIO-->
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 80%; color: #444444;">
                <span style="color: black;font-size:17px;font-weight:bold;text-decoration: underline black;">CONVENIO DE PRÁCTICAS PROFESIONALES</span>
            </td>
			<td style="width: 10%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:13px;">Conste por el presente documento que se firma por triplicado, el Convenio de Practicas Profesionales, celebrado de conformidad con el artículo 13º y siguientes de la Ley Nº 28518, Ley sobre Modalidades Formativas Laborales, y su Reglamento, aprobado mediante el Decreto Supremo N° 007-2005-TR, que celebran entre LA EMPRESA y EL (LA) EGRESADO (A), identificados en este documento, de acuerdo a los términos y condiciones siguientes:</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">A. LA EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<br>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">RAZON SOCIAL </span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: TAI LOY S.A.</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">RUC</span>
           	</td>
           	<td style="width: 55%;">
           		<span style="l65e-height:20px;font-size:11px;">: 20100049181</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DOMICILIO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: JR. MARIANO ODICIO Nº 153 URB MIRAFLORES - SURQUILLO</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">ACTIVIDAD ECONOMICA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: VENTA DE ARTICULOS DE OFICINA Y ESCOLARES</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">REPRESENTANTE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: MANUEL HUARCAYA SILVA</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DOC. DE IDENTIDAD DEL REPRESENTANTE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: 06225424</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">B. EL(LA) EGRESADO(A)</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">NOMBRE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $nom_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">TIPO Y NUMERO DE IDENTIDAD</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">NACIONALIDAD</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $nacionalidad_postu ?></span>
           	</td>
           	
        </tr>
    </table>



    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">FECHA DE NACIMIENTO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $fecha_nacimiento_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">SEXO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $genero_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">ESTADO CIVIL</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $estado_civil_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DOMICILIO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $direccion_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">OCUPACION MATERIA DE LA CAPACITACION</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $puesto_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">CONDICIÓN</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: EGRESADO</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">PROFESIÓN UNIVERSITARIA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;"></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">O PROFESIÓN TÉCNICA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $carrera_uni ?></span>
           	</td>
           	
        </tr>
    </table>

    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">C. CONDICIONES DEL CONVENIO</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">PLAZO DE DURACIÓN</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $dias_convenio ?> desde el <?php echo $fecha_ingreso_letras ?> hasta el <?php echo $fecha_fin_letras ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DÍAS DE LAS PRÁCTICAS</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: Lunes a Sábado</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">HORARIO DE LAS PRÁCTICAS</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: Lunes a Viernes de 9:00 a.m a 18:00 p.m</span>
           	</td>
           	
        </tr>
    </table>
    
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;"></span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">  Refrigerio de 13:00 p.m a 14:00 p.m</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;"></span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">  Sábados: de 9:00 a.m a 14:00 p.m</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">SUBVENCIÓN ECONÓMICA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: S/. <?php echo $salario_postu ?>.00 (<?php echo $salario_letras_postu ?>)</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">ÁREA DONDE SE REALIZA EL APRENDIZAJE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $area_postu ?></span>
           	</td>
           	
        </tr>
    </table>
</page>

<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;text-decoration: underline;">CLÁUSULAS DEL CONVENIO</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">PRIMERO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;"><?php echo $nombre_universidad_postu ?> que presenta a EL(LA) EGRESADO(A) mediante comunicación de fecha <?php echo $fecha_ingreso_letras ?> presenta a EL (LA) EGRESADO (A) para que se le permita realizar sus Prácticas Profesionales en LA EMPRESA.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">SEGUNDO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">EL(LA) EGRESADO(A) manifiesta su interés en desarrollar sus Prácticas Profesionales para consolidar los aprendizajes adquiridos a lo largo de su formación profesional, así como ejecutar su desempeño en una situación real de trabajo y con los fines de obtener el grado (o título) correspondiente. Por su parte, LA EMPRESA acepta colaborar, tanto con el indicado Centro de Formación Profesional como con EL(LA) EGRESADO(A) en su tarea formativa, permitiéndole que realice su Práctica Profesional.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">TERCERO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">EL (LA) EGRESADO (A) desempeñará las actividades formativas como de <?php echo $puesto_postu ?> en el área de <?php echo $area_postu ?> en el domicilio de la empresa ubicado en <?php echo $direccion_tienda_postu ?> de acuerdo a las condiciones generales señalados en el literal c).</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">CUARTO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Para efectos del presente convenio LA EMPRESA, se obliga a:
<br>1) Brindar orientación y capacitación técnica y profesional a EL(LA) EGRESADO(A) dentro de su área de formación académica, así
como evaluar sus prácticas.
<br>2) Emitir los informes que requiera el Centro de Formación Profesional en relación con las prácticas de EL(LA) EGRESADO(A).
<br>3) No cobrar suma alguna por la Formación otorgada.
<br>4) Pagar puntualmente a EL(LA) EGRESADO(A) una subvención económica convenida.
<br>5) Otorgar EL (LA) EGRESADO (A) una subvención adicional equivalente a media subvención económica mensual cada seis meses
de duración continua de las prácticas.
<br>6) Otorgar un descanso de quince (15) días debidamente subvencionados cuando la duración de las prácticas sea superior a doce
(12) meses teniendo en cuenta la acumulación de los periodos intermitentes que hubiera realizado el practicante.
<br>7) Cubrir los riesgos de enfermedad y accidentes de EL(LA) EGRESADO(A), a través de un seguro privado con una cobertura equivalente a
catorce (14) subvenciones mensuales en caso de
enfermedad y treinta (30) por accidente.
<br>8) Expedir la certificación de Prácticas Profesionales correspondiente</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">QUINTO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Para efectos del presente convenio EL(LA) EGRESADO(A), se obliga a:
				<br>1) Suscribir el convenio de Prácticas con LA EMPRESA acatando las disposiciones formativas que se asigne.
				<br>2) Desarrollar sus Prácticas Profesionales con disciplina y responsabilidad.
				<br>3) Cumplir con el desarrollo del Plan de Prácticas que aplique LA EMPRESA;
				<br>4) Sujetarse a las disposiciones administrativas internas que le señale LA EMPRESA.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">SEXTO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">LA EMPRESA ha contratado el seguro MAPFRE PERU COMPAÑÍA DE SEGUROS Y REASEGUROS para cubrir los riesgos de enfermedad y accidentes de EL(LA) EGRESADO(A) .</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">SÉPTIMO:</span>
           	</td>
           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">LA EMPRESA concederá a EL(LA) EGRESADO(A) una subvención económica mensual de S/. <?php echo $salario_postu ?>.00 (<?php echo $salario_letras_postu ?>). <br>De conformidad con el artículo 47° de la Ley N° 28518, esta subvención económica mensual no tiene carácter remunerativo y no está afecta al pago del Impuesto a la Renta, otros impuestos,contribuciones ni aportaciones de ningún tipo a cargo de LA EMPRESA. <br>La subvención económica mensual no está sujeta a ningún tipo de retención a cargo de EL(LA) EGRESADO(A), salvo afiliación facultativa por parte de éste a un sistema pensionario.</span>
           	</td>
           	
        </tr>

	</table>
</page>

<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">OCTAVO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Las partes acuerdan la aplicación de las causas de modificación, suspensión y terminación del convenio, que se detallan a continuación: <br>Son causas de modificación del convenio:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">a) Por acuerdo entre EL(LA) PRACTICANTE y LA EMPRESA <br>Son causas de suspensión del convenio:
				<br>a) La enfermedad y el accidente comprobados, sin perjuicio de lo establecido en el numeral 7) de la cláusula cuarta del presente convenio.
				<br>b) Por descanso físico subvencionado en caso que el convenio se prorrogue a un plazo mayor de doce meses.
				<br>c) El permiso concedido por la empresa.
				<br>d) La sanción disciplinaria.
				<br>e) El caso fortuito o fuerza mayor.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Son causas de terminación del convenio:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">a) El cumplimiento del plazo estipulado en el literal C, Condiciones del Convenio, de las Condiciones Generales.
				<br>b) El mutuo disenso entre EL(LA) PRACTICANTE y LA EMPRESA.
				<br>c) El fallecimiento de EL(LA) PRACTICANTE.
				<br>d) La invalidez absoluta permanente.
				<br>e) No guardar reserva de toda la información y/o documentación que EL(LA) PRACTICANTE conozca durante el desarrollo de la práctica.
				<br>f) El incumplimiento de cualquiera de las obligaciones por parte de EL(LA) PRACTICANTE y específicamente las contempladas en la cláusula quinta del presente convenio.
				<br>g) Por renuncia o retiro voluntario por parte de EL(LA) PRACTICANTE, mediante aviso a LA EMPRESA con antelación de 10 días hábiles.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">NOVENO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">EL(LA) EGRESADO(A) declara conocer la naturaleza del presente convenio, el cual no tiene carácter laboral, de tal modo que sólo genera para las partes, los derechos y obligaciones específicamente previsto en el mismo y en el texto de la Ley N° 28518 y el Decreto Supremo N° 007-2005-TR.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">DÉCIMO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Para todos los efectos relacionados con el presente convenio, las partes señalan como su domicilio el que aparece consignado en la parte introductoria de éste, los cuales se tendrán por válidos en tanto la variación no haya sido comunicada por escrito a la otra parte. <br> Las partes, después de leído el presente convenio, se ratifican en su contenido y lo suscriben en señal de conformidad en tres ejemplares; el primero para LA EMPRESA, el segundo para EL(LA) EGRESADO(A), el tercero será puesto en conocimiento y registrado ante la Autoridad Administrativa de Trabajo dentro de los quince (15) días naturales de la suscripción, de lo que damos fe.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11px;">Suscrito en la ciudad de Lima, a los <?php echo $fecha_ingreso_letras ?>.</span>
           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span><?php echo $nom_postu ?></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span><?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>HUARCAYA SILVA MANUEL ENRIQUE</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>DNI NRO. 06225424</span>
				
			</td>
		   
           	
        </tr>

	</table>
	
	<br><br>
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
<!--CONVENIO-->
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 20%; color: black;font-size:15px;text-align:middle">
                
            </td>
			<td style="width: 80%; color: #444444;">
                <span style="color: black;font-size:17px;font-weight:bold;text-decoration: underline black;">CONVENIO DE PRÁCTICAS PROFESIONALES</span>
            </td>
			<td style="width: 10%;text-align:right">
				
			</td>
			
        </tr>
    </table>
    <br><br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:20px;font-size:13px;">Conste por el presente documento que se firma por triplicado, el Convenio de Practicas Profesionales, celebrado de conformidad con el artículo 13º y siguientes de la Ley Nº 28518, Ley sobre Modalidades Formativas Laborales, y su Reglamento, aprobado mediante el Decreto Supremo N° 007-2005-TR, que celebran entre LA EMPRESA y EL (LA) EGRESADO (A), identificados en este documento, de acuerdo a los términos y condiciones siguientes:</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">A. LA EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<br>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">RAZON SOCIAL </span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: TAI LOY S.A.</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">RUC</span>
           	</td>
           	<td style="width: 55%;">
           		<span style="l65e-height:20px;font-size:11px;">: 20100049181</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DOMICILIO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: JR. MARIANO ODICIO Nº 153 URB MIRAFLORES - SURQUILLO</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">ACTIVIDAD ECONOMICA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: VENTA DE ARTICULOS DE OFICINA Y ESCOLARES</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">REPRESENTANTE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: MANUEL HUARCAYA SILVA</span>
           	</td>
           	
        </tr>
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DOC. DE IDENTIDAD DEL REPRESENTANTE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: 06225424</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">B. EL(LA) EGRESADO(A)</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">NOMBRE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $nom_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">TIPO Y NUMERO DE IDENTIDAD</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">NACIONALIDAD</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $nacionalidad_postu ?></span>
           	</td>
           	
        </tr>
    </table>



    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">FECHA DE NACIMIENTO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $fecha_nacimiento_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">SEXO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $genero_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">ESTADO CIVIL</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $estado_civil_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DOMICILIO</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $direccion_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">OCUPACION MATERIA DE LA CAPACITACION</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $puesto_postu ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">CONDICIÓN</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: EGRESADO</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">PROFESIÓN UNIVERSITARIA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;"></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">O PROFESIÓN TÉCNICA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $carrera_uni ?></span>
           	</td>
           	
        </tr>
    </table>

    <br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">C. CONDICIONES DEL CONVENIO</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">PLAZO DE DURACIÓN</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $dias_convenio ?> desde el <?php echo $fecha_ingreso_letras ?> hasta el <?php echo $fecha_fin_letras ?></span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">DÍAS DE LAS PRÁCTICAS</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: Lunes a Sábado</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">HORARIO DE LAS PRÁCTICAS</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: Lunes a Viernes de 9:00 a.m a 18:00 p.m</span>
           	</td>
           	
        </tr>
    </table>
    
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;"></span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">  Refrigerio de 13:00 p.m a 14:00 p.m</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;"></span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">  Sábados: de 9:00 a.m a 14:00 p.m</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">SUBVENCIÓN ECONÓMICA</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: S/. <?php echo $salario_postu ?>.00 (<?php echo $salario_letras_postu ?>)</span>
           	</td>
           	
        </tr>
    </table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td style="width: 35%;">
           		<span style="line-height:30px;font-size:11px;">ÁREA DONDE SE REALIZA EL APRENDIZAJE</span>
           	</td>
           	<td style="width: 65%;">
           		<span style="line-height:30px;font-size:11px;">: <?php echo $area_postu ?></span>
           	</td>
           	
        </tr>
    </table>
</page>

<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;text-decoration: underline;">CLÁUSULAS DEL CONVENIO</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">PRIMERO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;"><?php echo $nombre_universidad_postu ?> que presenta a EL(LA) EGRESADO(A) mediante comunicación de fecha <?php echo $fecha_ingreso_letras ?> presenta a EL (LA) EGRESADO (A) para que se le permita realizar sus Prácticas Profesionales en LA EMPRESA.</span>
           	</td>
           	
        </tr>
EGRESADO(A)
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">SEGUNDO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">EL(LA) EGRESADO(A) manifiesta su interés en desarrollar sus Prácticas Profesionales para consolidar los aprendizajes adquiridos a lo largo de su formación profesional, así como ejecutar su desempeño en una situación real de trabajo y con los fines de obtener el grado (o título) correspondiente. Por su parte, LA EMPRESA acepta colaborar, tanto con el indicado Centro de Formación Profesional como con EL(LA) EGRESADO(A) en su tarea formativa, permitiéndole que realice su Práctica Profesional.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">TERCERO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">EL (LA) EGRESADO (A) desempeñará las actividades formativas como de <?php echo $puesto_postu ?> en el área de <?php echo $area_postu ?> en el domicilio de la empresa ubicado en <?php echo $direccion_tienda_postu ?> de acuerdo a las condiciones generales señalados en el literal c).</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">CUARTO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Para efectos del presente convenio LA EMPRESA, se obliga a:
<br>1) Brindar orientación y capacitación técnica y profesional a EL(LA) EGRESADO(A) dentro de su área de formación académica, así
como evaluar sus prácticas.
<br>2) Emitir los informes que requiera el Centro de Formación Profesional en relación con las prácticas de EL(LA) EGRESADO(A).
<br>3) No cobrar suma alguna por la Formación otorgada.
<br>4) Pagar puntualmente a EL(LA) EGRESADO(A) una subvención económica convenida.
<br>5) Otorgar EL (LA) EGRESADO (A) una subvención adicional equivalente a media subvención económica mensual cada seis meses
de duración continua de las prácticas.
<br>6) Otorgar un descanso de quince (15) días debidamente subvencionados cuando la duración de las prácticas sea superior a doce
(12) meses teniendo en cuenta la acumulación de los periodos intermitentes que hubiera realizado el practicante.
<br>7) Cubrir los riesgos de enfermedad y accidentes de EL(LA) EGRESADO(A), a través de un seguro privado con una cobertura equivalente a
catorce (14) subvenciones mensuales en caso de
enfermedad y treinta (30) por accidente.
<br>8) Expedir la certificación de Prácticas Profesionales correspondiente</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">QUINTO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Para efectos del presente convenio EL(LA) EGRESADO(A), se obliga a:
				<br>1) Suscribir el convenio de Prácticas con LA EMPRESA acatando las disposiciones formativas que se asigne.
				<br>2) Desarrollar sus Prácticas Profesionales con disciplina y responsabilidad.
				<br>3) Cumplir con el desarrollo del Plan de Prácticas que aplique LA EMPRESA;
				<br>4) Sujetarse a las disposiciones administrativas internas que le señale LA EMPRESA.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">SEXTO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">LA EMPRESA ha contratado el seguro MAPFRE PERU COMPAÑÍA DE SEGUROS Y REASEGUROS para cubrir los riesgos de enfermedad y accidentes de EL(LA) EGRESADO(A) .</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">SÉPTIMO:</span>
           	</td>
           	
        </tr>

	</table>

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">LA EMPRESA concederá a EL(LA) EGRESADO(A) una subvención económica mensual de S/. <?php echo $salario_postu ?>.00 (<?php echo $salario_letras_postu ?>). <br>De conformidad con el artículo 47° de la Ley N° 28518, esta subvención económica mensual no tiene carácter remunerativo y no está afecta al pago del Impuesto a la Renta, otros impuestos,contribuciones ni aportaciones de ningún tipo a cargo de LA EMPRESA. <br>La subvención económica mensual no está sujeta a ningún tipo de retención a cargo de EL(LA) EGRESADO(A), salvo afiliación facultativa por parte de éste a un sistema pensionario.</span>
           	</td>
           	
        </tr>

	</table>
</page>

<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">OCTAVO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Las partes acuerdan la aplicación de las causas de modificación, suspensión y terminación del convenio, que se detallan a continuación: <br>Son causas de modificación del convenio:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">a) Por acuerdo entre EL(LA) PRACTICANTE y LA EMPRESA <br>Son causas de suspensión del convenio:
				<br>a) La enfermedad y el accidente comprobados, sin perjuicio de lo establecido en el numeral 7) de la cláusula cuarta del presente convenio.
				<br>b) Por descanso físico subvencionado en caso que el convenio se prorrogue a un plazo mayor de doce meses.
				<br>c) El permiso concedido por la empresa.
				<br>d) La sanción disciplinaria.
				<br>e) El caso fortuito o fuerza mayor.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Son causas de terminación del convenio:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;</td>
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">a) El cumplimiento del plazo estipulado en el literal C, Condiciones del Convenio, de las Condiciones Generales.
				<br>b) El mutuo disenso entre EL(LA) PRACTICANTE y LA EMPRESA.
				<br>c) El fallecimiento de EL(LA) PRACTICANTE.
				<br>d) La invalidez absoluta permanente.
				<br>e) No guardar reserva de toda la información y/o documentación que EL(LA) PRACTICANTE conozca durante el desarrollo de la práctica.
				<br>f) El incumplimiento de cualquiera de las obligaciones por parte de EL(LA) PRACTICANTE y específicamente las contempladas en la cláusula quinta del presente convenio.
				<br>g) Por renuncia o retiro voluntario por parte de EL(LA) PRACTICANTE, mediante aviso a LA EMPRESA con antelación de 10 días hábiles.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">NOVENO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">EL(LA) EGRESADO(A) declara conocer la naturaleza del presente convenio, el cual no tiene carácter laboral, de tal modo que sólo genera para las partes, los derechos y obligaciones específicamente previsto en el mismo y en el texto de la Ley N° 28518 y el Decreto Supremo N° 007-2005-TR.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:11.5px;text-decoration: underline;">DÉCIMO:</span>
           	</td>
           	
        </tr>

	</table>
    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11.5px;">Para todos los efectos relacionados con el presente convenio, las partes señalan como su domicilio el que aparece consignado en la parte introductoria de éste, los cuales se tendrán por válidos en tanto la variación no haya sido comunicada por escrito a la otra parte. <br> Las partes, después de leído el presente convenio, se ratifican en su contenido y lo suscriben en señal de conformidad en tres ejemplares; el primero para LA EMPRESA, el segundo para EL(LA) EGRESADO(A), el tercero será puesto en conocimiento y registrado ante la Autoridad Administrativa de Trabajo dentro de los quince (15) días naturales de la suscripción, de lo que damos fe.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="line-height:15px;font-size:11px;">Suscrito en la ciudad de Lima, a los <?php echo $fecha_ingreso_letras ?>.</span>
           	</td>
           	
        </tr>

	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span><?php echo $nom_postu ?></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>REPRESENTANTE LEGAL DE LA EMPRESA</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span><?php echo $tipo_documento ?> Nro. <?php echo $dni_postulante ?></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>HUARCAYA SILVA MANUEL ENRIQUE</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>DNI NRO. 06225424</span>
				
			</td>
		   
           	
        </tr>

	</table>
	
	<br><br>
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


<!--PLAN DE CAPACITACION-->
<!--
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%">
        		
        	</td>
		    <td style="width: 60%">
           		<span style="font-size:13px;">1-	Anexo del convenio de Modalidades Formativas Laborales</span>
           	</td>
           	<td style="width: 20%">
        		
        	</td>
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%">
        		
        	</td>
		    <td style="width: 40%">
           		<span style="font-weight:bold;font-size:13px;text-decoration: underline;">PLAN DE CAPACITACIÓN</span>
           	</td>
           	<td style="width: 30%">
        		
        	</td>
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Denominación del Plan de Capacitación (Marque con una X):</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/sinequis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;">Plan Específico de Aprendizaje con predominio en la Empresa.</span>
           	</td>
           	
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/equis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;"> Plan Específico de Aprendizaje con predominio en el Centro de Formación Profesional: Prácticas Profesionales.</span>
           	</td>
           	
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/sinequis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;">Plan de Específico de Pasantía en la Empresa.</span>
           	</td>
           	
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/sinequis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;">Plan/Itinerario de Pasantía de Docentes y Catedráticos.</span>
           	</td>
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">I.	DATOS GENERALES</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">DE LA EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.1	Razón Social de la Empresa</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px">TAI LOY S.A.</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.2	Actividad Económica</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px">VENTA DE ARTICULOS DE OFICINA POR MAYOR Y MENOR</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.3 Nombre del puesto de trabajo u ocupación en la que realizará el beneficiario su actividad formativa.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $puesto_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">DEL CENTRO DE FORMACIÓN PROFESIONAL</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.4 Nombre del Centro de Formación Profesional</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $nombre_universidad_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.5 Nombre de la persona responsable de la formación del beneficiarios en la empresa</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $representante_universidad_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">DEL BENEFICIARIO</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.6 Apellidos y Nombres del beneficiario</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $nom_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.7 Condiciones pactadas entre el Beneficiario, la Empresa y el Centro de Formación Profesional</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Monto de la subvención</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">S/ <?php echo $salario_postu ?>.00 (<?php echo $salario_letras_postu ?>)</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Tipo de seguro y cobertura</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Seguro médico MAPFRE PERÚ COMPAÑÍA DE SEGUROS Y REASEGUROS - coberturas de acuerdo a ley.</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Horario</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">06 días a la semana (Lunes a Viernes) en el Horario de 9:00 am a 6:00pm y Sábado de 9:00 am a 02:00pm (La empresa otorgara al (Beneficiario 01 hora de refrigerio)</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Ocupación o Puesto de Trabajo donde se desarrollará la actividad formativa.</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;"><?php echo $puesto_postu ?></td>
		</tr>
	</table>
</page>


<page backtop="15mm" backbottom="15mm" backleft="25mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">II.	OBJETIVO DEL PLAN</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Señala la información básica pertinente del proceso que el beneficiario seguirá a través de la modalidad materia del Convenio.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">2.1	Objetivos que debe lograr el beneficiario al término de su formación en la empresa (tomar como referencia los objetivos planteados para cada modalidad en la Ley Nº 28518).</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;">Objetivos: <br>a) Complementar la formación específica adquirida en su Centro de Estudios <br>
b) Consolidar el desarrollo de habilidades sociales y personales relacionadas al ámbito laboral</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">III.	ACTIVIDADES FORMATIVAS EN LA EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.1 Función principal del puesto de trabajo u ocupación donde se realizará la actividad formativa laboral.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;"><?php echo $mision_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.2 Actividades/tareas principales que se desprenden de la función del puesto de trabajo u ocupación.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;"><?php 
							$funciones_mof = array_filter(explode ("-", $funciones_postu));
							foreach ($funciones_mof as $cola2) {
							echo "&nbsp;".utf8_encode($cola2)."<br>";
						}
				?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.3. Competencias</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Señala la información básica de los logros formativos que obtendrá el beneficiario en su modalidad.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.3.1 Competencias específicas</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Son las relacionadas con aspectos técnicos directamente relacionados a la ocupación en él.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Competencias específicas</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Indicador de logro</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">1. Ejecutar Actividades de apoyo analíticas con la capacidad para apoyo en las decisiones</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">1.1 Poseer Panorama Analítico</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">2. Emitir y Dar soluciones integrales ante cualquier problema (capacidad para solucionar problemas).</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">2.1 Abocarse a soluciones integrales</td>
		</tr>
	</table>

	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.3.2 Competencias genéricas o transversales</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Relacionadas a los comportamientos y actitudes laborales propios que el beneficiario desarrollará en la actividad formativa laboral. Por ejemplo: Trabajo en equipo, comunicación, etc.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Competencias genéricas/transversales</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Indicador de logro</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">1. Autocontrol y confianza en sí mismo</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">1.1 Actuar con suma seguridad personal</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">2. Capaz de guardar información confidencial</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">2.1 Elevado enfoque de reserva y confiabilidad</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">3. Capaz de trabajo en equipo y liderazgo</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">3.1 Alto interés en colaboración y dirección</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">4. Diseño, organización y planificación</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">4.1 Buen criterio y proyección en el cumplimiento de sus funciones</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">IV.	DURACIÓN</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">4.1 Inicio y término</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Fecha de inicio: </td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;"><?php echo $fecha_ingreso_numero ?></td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Fecha de término: </td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;"><?php echo $fecha_fin_numero ?></td>
		</tr>
	</table>
</page>


<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">V.	CONTEXTO FORMATIVO</span>
           	</td>
           	
        </tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">INFRAESTRUCTURA Y AMBIENTE</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Se ha designado a una oficina, un escritorio con un ambiente cómodo.</td>
		</tr>

		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">MAQUINARIAS/EQUIPOS</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">	Computadora, fotocopiadora, faz y scanner.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">HERRAMIENTAS</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">No se aplica.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">INSUMOS</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Útiles de Oficina.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">EQUIPO PERSONAL</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">No se aplica.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">CONDICIONES DE SEGURIDAD</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Las condiciones de seguridad son: <br>
Existen, extintores de PQS, H2O, CO2 ubicados estratégicamente, señales de evacuación, puntos de reunión en caso de sismo, luces de emergencia para evacuación, disposición adecuada de máquinas, etc.; así como simulacros periódicos de evacuación en caso de incendios, sismos, entre otros; además de contar con controles administrativos adecuados para seguridad tales como certificado de capacitación, planos de señalización y evacuación y plan de emergencia.</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">VI.	MAPA DE RECORRIDO EN EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">Relación de áreas o departamentos donde rotará el/los beneficiarios, con la actividad formativa. Área o departamento</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">1.	<?php echo $area_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">VII.	MONITOREO Y EVALUACION</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
			<td>
           		<span style="font-size:12px;">Pautas que puedan ser consideradas para el proceso de evaluación del beneficiario durante y al término del proceso formativo:</span>
           	</td>
           	
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">1.	Evaluación personal del beneficiario en relación a los logros alcanzados a nivel de competencias específicas y competencias genéricas / transversales.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">2.	Observación de las actividades formativas realizadas por el/los beneficiarios en la empresa:</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">• Calidad de la actividad formativa</span>
           	</td>
           	
        </tr>
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">• Pertinencia de la actividad formativa</span>
           	</td>
           	
        </tr>
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">• Resultados de la actividad formativa</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">3.	Aportes realizados a la Empresa.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">4.	Otros.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">Este Anexo contiene información fidedigna, que compromete en su ejecución a los <?php echo $fecha_ingreso_letras ?>, firmantes:</span>
           	</td>
           	
        </tr>
	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>EL(LA) PRACTICANTE</span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>TAI LOY S.A.</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span><?php echo $nom_postu ?></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>HUARCAYA SILVA MANUEL ENRIQUE</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 40%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
            </td>
			<td style="width: 40%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span><?php echo $nombre_universidad_postu ?></span>
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 40%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span><?php echo $representante_universidad_postu ?></span>
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">				
			</td>
		   
           	
        </tr>

	</table>
	<br><br>
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
-->
<!--PLAN DE CAPACITACION-->
<!--
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%">
        		
        	</td>
		    <td style="width: 60%">
           		<span style="font-size:13px;">1-	Anexo del convenio de Modalidades Formativas Laborales</span>
           	</td>
           	<td style="width: 20%">
        		
        	</td>
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%">
        		
        	</td>
		    <td style="width: 40%">
           		<span style="font-weight:bold;font-size:13px;text-decoration: underline;">PLAN DE CAPACITACIÓN</span>
           	</td>
           	<td style="width: 30%">
        		
        	</td>
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Denominación del Plan de Capacitación (Marque con una X):</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/sinequis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;">Plan Específico de Aprendizaje con predominio en la Empresa.</span>
           	</td>
           	
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/equis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;"> Plan Específico de Aprendizaje con predominio en el Centro de Formación Profesional: Prácticas Profesionales.</span>
           	</td>
           	
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/sinequis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;">Plan de Específico de Pasantía en la Empresa.</span>
           	</td>
           	
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/sinequis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;">Plan/Itinerario de Pasantía de Docentes y Catedráticos.</span>
           	</td>
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">I.	DATOS GENERALES</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">DE LA EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.1	Razón Social de la Empresa</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px">TAI LOY S.A.</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.2	Actividad Económica</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px">VENTA DE ARTICULOS DE OFICINA POR MAYOR Y MENOR</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.3 Nombre del puesto de trabajo u ocupación en la que realizará el beneficiario su actividad formativa.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $puesto_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">DEL CENTRO DE FORMACIÓN PROFESIONAL</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.4 Nombre del Centro de Formación Profesional</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $nombre_universidad_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.5 Nombre de la persona responsable de la formación del beneficiarios en la empresa</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $representante_universidad_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">DEL BENEFICIARIO</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.6 Apellidos y Nombres del beneficiario</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $nom_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.7 Condiciones pactadas entre el Beneficiario, la Empresa y el Centro de Formación Profesional</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Monto de la subvención</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">S/ <?php echo $salario_postu ?>.00 (<?php echo $salario_letras_postu ?>)</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Tipo de seguro y cobertura</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Seguro médico MAPFRE PERÚ COMPAÑÍA DE SEGUROS Y REASEGUROS - coberturas de acuerdo a ley.</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Horario</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">06 días a la semana (Lunes a Viernes) en el Horario de 9:00 am a 6:00pm y Sábado de 9:00 am a 02:00pm (La empresa otorgara al (Beneficiario 01 hora de refrigerio)</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Ocupación o Puesto de Trabajo donde se desarrollará la actividad formativa.</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;"><?php echo $puesto_postu ?></td>
		</tr>
	</table>
</page>


<page backtop="15mm" backbottom="15mm" backleft="25mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">II.	OBJETIVO DEL PLAN</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Señala la información básica pertinente del proceso que el beneficiario seguirá a través de la modalidad materia del Convenio.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">2.1	Objetivos que debe lograr el beneficiario al término de su formación en la empresa (tomar como referencia los objetivos planteados para cada modalidad en la Ley Nº 28518).</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;">Objetivos: <br>a) Complementar la formación específica adquirida en su Centro de Estudios <br>
b) Consolidar el desarrollo de habilidades sociales y personales relacionadas al ámbito laboral</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">III.	ACTIVIDADES FORMATIVAS EN LA EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.1 Función principal del puesto de trabajo u ocupación donde se realizará la actividad formativa laboral.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;"><?php echo $mision_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.2 Actividades/tareas principales que se desprenden de la función del puesto de trabajo u ocupación.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;"><?php 
							$funciones_mof = array_filter(explode ("-", $funciones_postu));
							foreach ($funciones_mof as $cola2) {
							echo "&nbsp;".utf8_encode($cola2)."<br>";
						}
				?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.3. Competencias</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Señala la información básica de los logros formativos que obtendrá el beneficiario en su modalidad.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.3.1 Competencias específicas</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Son las relacionadas con aspectos técnicos directamente relacionados a la ocupación en él.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Competencias específicas</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Indicador de logro</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">1. Ejecutar Actividades de apoyo analíticas con la capacidad para apoyo en las decisiones</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">1.1 Poseer Panorama Analítico</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">2. Emitir y Dar soluciones integrales ante cualquier problema (capacidad para solucionar problemas).</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">2.1 Abocarse a soluciones integrales</td>
		</tr>
	</table>

	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.3.2 Competencias genéricas o transversales</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Relacionadas a los comportamientos y actitudes laborales propios que el beneficiario desarrollará en la actividad formativa laboral. Por ejemplo: Trabajo en equipo, comunicación, etc.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Competencias genéricas/transversales</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Indicador de logro</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">1. Autocontrol y confianza en sí mismo</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">1.1 Actuar con suma seguridad personal</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">2. Capaz de guardar información confidencial</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">2.1 Elevado enfoque de reserva y confiabilidad</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">3. Capaz de trabajo en equipo y liderazgo</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">3.1 Alto interés en colaboración y dirección</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">4. Diseño, organización y planificación</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">4.1 Buen criterio y proyección en el cumplimiento de sus funciones</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">IV.	DURACIÓN</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">4.1 Inicio y término</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Fecha de inicio: </td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;"><?php echo $fecha_ingreso_numero ?></td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Fecha de término: </td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;"><?php echo $fecha_fin_numero ?></td>
		</tr>
	</table>
</page>


<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">V.	CONTEXTO FORMATIVO</span>
           	</td>
           	
        </tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">INFRAESTRUCTURA Y AMBIENTE</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Se ha designado a una oficina, un escritorio con un ambiente cómodo.</td>
		</tr>

		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">MAQUINARIAS/EQUIPOS</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">	Computadora, fotocopiadora, faz y scanner.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">HERRAMIENTAS</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">No se aplica.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">INSUMOS</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Útiles de Oficina.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">EQUIPO PERSONAL</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">No se aplica.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">CONDICIONES DE SEGURIDAD</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Las condiciones de seguridad son: <br>
Existen, extintores de PQS, H2O, CO2 ubicados estratégicamente, señales de evacuación, puntos de reunión en caso de sismo, luces de emergencia para evacuación, disposición adecuada de máquinas, etc.; así como simulacros periódicos de evacuación en caso de incendios, sismos, entre otros; además de contar con controles administrativos adecuados para seguridad tales como certificado de capacitación, planos de señalización y evacuación y plan de emergencia.</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">VI.	MAPA DE RECORRIDO EN EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">Relación de áreas o departamentos donde rotará el/los beneficiarios, con la actividad formativa. Área o departamento</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">1.	<?php echo $area_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">VII.	MONITOREO Y EVALUACION</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
			<td>
           		<span style="font-size:12px;">Pautas que puedan ser consideradas para el proceso de evaluación del beneficiario durante y al término del proceso formativo:</span>
           	</td>
           	
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">1.	Evaluación personal del beneficiario en relación a los logros alcanzados a nivel de competencias específicas y competencias genéricas / transversales.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">2.	Observación de las actividades formativas realizadas por el/los beneficiarios en la empresa:</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">• Calidad de la actividad formativa</span>
           	</td>
           	
        </tr>
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">• Pertinencia de la actividad formativa</span>
           	</td>
           	
        </tr>
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">• Resultados de la actividad formativa</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">3.	Aportes realizados a la Empresa.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">4.	Otros.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">Este Anexo contiene información fidedigna, que compromete en su ejecución a los <?php echo $fecha_ingreso_letras ?>, firmantes:</span>
           	</td>
           	
        </tr>
	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>EL(LA) PRACTICANTE</span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>TAI LOY S.A.</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span><?php echo $nom_postu ?></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>HUARCAYA SILVA MANUEL ENRIQUE</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 40%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
            </td>
			<td style="width: 40%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span><?php echo $nombre_universidad_postu ?></span>
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 40%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span><?php echo $representante_universidad_postu ?></span>
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">				
			</td>
		   
           	
        </tr>

	</table>
	<br><br>
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

</page>-->
<!--PLAN DE CAPACITACION-->
<!--
<page backtop="15mm" backbottom="15mm" backleft="17mm" backright="15mm" style="font-size: 12pt; font-family: arial" >

    <table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 20%">
        		
        	</td>
		    <td style="width: 60%">
           		<span style="font-size:13px;">1-	Anexo del convenio de Modalidades Formativas Laborales</span>
           	</td>
           	<td style="width: 20%">
        		
        	</td>
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%">
        		
        	</td>
		    <td style="width: 40%">
           		<span style="font-weight:bold;font-size:13px;text-decoration: underline;">PLAN DE CAPACITACIÓN</span>
           	</td>
           	<td style="width: 30%">
        		
        	</td>
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">Denominación del Plan de Capacitación (Marque con una X):</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/sinequis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;">Plan Específico de Aprendizaje con predominio en la Empresa.</span>
           	</td>
           	
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/equis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;"> Plan Específico de Aprendizaje con predominio en el Centro de Formación Profesional: Prácticas Profesionales.</span>
           	</td>
           	
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/sinequis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;">Plan de Específico de Pasantía en la Empresa.</span>
           	</td>
           	
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td style="width: 10%;">
           		<img src='../../images/icon/sinequis.png' style='    max-width: 30px;
				    height: 30px;
				    float: left;'  />
           	</td>
		    <td style="width: 90%;">
           		<span style="font-size:12px;">Plan/Itinerario de Pasantía de Docentes y Catedráticos.</span>
           	</td>
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">I.	DATOS GENERALES</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">DE LA EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.1	Razón Social de la Empresa</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px">TAI LOY S.A.</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.2	Actividad Económica</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px">VENTA DE ARTICULOS DE OFICINA POR MAYOR Y MENOR</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.3 Nombre del puesto de trabajo u ocupación en la que realizará el beneficiario su actividad formativa.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $puesto_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">DEL CENTRO DE FORMACIÓN PROFESIONAL</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.4 Nombre del Centro de Formación Profesional</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $nombre_universidad_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.5 Nombre de la persona responsable de la formación del beneficiarios en la empresa</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $representante_universidad_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">DEL BENEFICIARIO</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.6 Apellidos y Nombres del beneficiario</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;height: 13px"><?php echo $nom_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">1.7 Condiciones pactadas entre el Beneficiario, la Empresa y el Centro de Formación Profesional</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Monto de la subvención</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">S/ <?php echo $salario_postu ?>.00 (<?php echo $salario_letras_postu ?>)</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Tipo de seguro y cobertura</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Seguro médico MAPFRE PERÚ COMPAÑÍA DE SEGUROS Y REASEGUROS - coberturas de acuerdo a ley.</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Horario</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">06 días a la semana (Lunes a Viernes) en el Horario de 9:00 am a 6:00pm y Sábado de 9:00 am a 02:00pm (La empresa otorgara al (Beneficiario 01 hora de refrigerio)</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Ocupación o Puesto de Trabajo donde se desarrollará la actividad formativa.</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;"><?php echo $puesto_postu ?></td>
		</tr>
	</table>
</page>


<page backtop="15mm" backbottom="15mm" backleft="25mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">II.	OBJETIVO DEL PLAN</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Señala la información básica pertinente del proceso que el beneficiario seguirá a través de la modalidad materia del Convenio.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">2.1	Objetivos que debe lograr el beneficiario al término de su formación en la empresa (tomar como referencia los objetivos planteados para cada modalidad en la Ley Nº 28518).</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;">Objetivos: <br>a) Complementar la formación específica adquirida en su Centro de Estudios <br>
b) Consolidar el desarrollo de habilidades sociales y personales relacionadas al ámbito laboral</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">III.	ACTIVIDADES FORMATIVAS EN LA EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.1 Función principal del puesto de trabajo u ocupación donde se realizará la actividad formativa laboral.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;"><?php echo $mision_postu ?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.2 Actividades/tareas principales que se desprenden de la función del puesto de trabajo u ocupación.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: left;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 100%;font-size:12px;"><?php 
							$funciones_mof = array_filter(explode ("-", $funciones_postu));
							foreach ($funciones_mof as $cola2) {
							echo "&nbsp;".utf8_encode($cola2)."<br>";
						}
				?></td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.3. Competencias</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Señala la información básica de los logros formativos que obtendrá el beneficiario en su modalidad.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.3.1 Competencias específicas</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Son las relacionadas con aspectos técnicos directamente relacionados a la ocupación en él.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Competencias específicas</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Indicador de logro</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">1. Ejecutar Actividades de apoyo analíticas con la capacidad para apoyo en las decisiones</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">1.1 Poseer Panorama Analítico</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">2. Emitir y Dar soluciones integrales ante cualquier problema (capacidad para solucionar problemas).</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">2.1 Abocarse a soluciones integrales</td>
		</tr>
	</table>

	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">3.3.2 Competencias genéricas o transversales</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px; text-decoration: underline;">Relacionadas a los comportamientos y actitudes laborales propios que el beneficiario desarrollará en la actividad formativa laboral. Por ejemplo: Trabajo en equipo, comunicación, etc.</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Competencias genéricas/transversales</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">Indicador de logro</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">1. Autocontrol y confianza en sí mismo</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">1.1 Actuar con suma seguridad personal</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">2. Capaz de guardar información confidencial</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">2.1 Elevado enfoque de reserva y confiabilidad</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">3. Capaz de trabajo en equipo y liderazgo</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">3.1 Alto interés en colaboración y dirección</td>
		</tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">4. Diseño, organización y planificación</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">4.1 Buen criterio y proyección en el cumplimiento de sus funciones</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">IV.	DURACIÓN</span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-size:12px;">4.1 Inicio y término</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Fecha de inicio: </td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;"><?php echo $fecha_ingreso_numero ?></td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Fecha de término: </td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;"><?php echo $fecha_fin_numero ?></td>
		</tr>
	</table>
</page>


<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">V.	CONTEXTO FORMATIVO</span>
           	</td>
           	
        </tr>
	</table>
	<table cellspacing="0" style="width: 100%; text-align: center;  vertical-align: middle">
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">INFRAESTRUCTURA Y AMBIENTE</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Se ha designado a una oficina, un escritorio con un ambiente cómodo.</td>
		</tr>

		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">MAQUINARIAS/EQUIPOS</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">	Computadora, fotocopiadora, faz y scanner.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">HERRAMIENTAS</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">No se aplica.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">INSUMOS</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Útiles de Oficina.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">EQUIPO PERSONAL</td>
			<td style="border-top: 1px solid #000000; border-bottom: 0px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">No se aplica.</td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 35%;font-size:12px;">CONDICIONES DE SEGURIDAD</td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 65%;font-size:12px;">Las condiciones de seguridad son: <br>
Existen, extintores de PQS, H2O, CO2 ubicados estratégicamente, señales de evacuación, puntos de reunión en caso de sismo, luces de emergencia para evacuación, disposición adecuada de máquinas, etc.; así como simulacros periódicos de evacuación en caso de incendios, sismos, entre otros; además de contar con controles administrativos adecuados para seguridad tales como certificado de capacitación, planos de señalización y evacuación y plan de emergencia.</td>
		</tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">VI.	MAPA DE RECORRIDO EN EMPRESA</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">Relación de áreas o departamentos donde rotará el/los beneficiarios, con la actividad formativa. Área o departamento</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">1.	<?php echo $area_postu ?></span>
           	</td>
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	
		    <td>
           		<span style="font-weight:bold;font-size:12px;">VII.	MONITOREO Y EVALUACION</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
			<td>
           		<span style="font-size:12px;">Pautas que puedan ser consideradas para el proceso de evaluación del beneficiario durante y al término del proceso formativo:</span>
           	</td>
           	
        </tr>
	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">1.	Evaluación personal del beneficiario en relación a los logros alcanzados a nivel de competencias específicas y competencias genéricas / transversales.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">2.	Observación de las actividades formativas realizadas por el/los beneficiarios en la empresa:</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">• Calidad de la actividad formativa</span>
           	</td>
           	
        </tr>
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">• Pertinencia de la actividad formativa</span>
           	</td>
           	
        </tr>
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">• Resultados de la actividad formativa</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">3.	Aportes realizados a la Empresa.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">4.	Otros.</span>
           	</td>
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: justify;  vertical-align: middle" >
        <tr>
        	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		    <td>
           		<span style="font-size:12px;">Este Anexo contiene información fidedigna, que compromete en su ejecución a los <?php echo $fecha_ingreso_letras ?>, firmantes:</span>
           	</td>
           	
        </tr>
	</table>
	<br><br><br><br><br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>________________________________________</span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span>EL(LA) PRACTICANTE</span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>TAI LOY S.A.</span>
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                <span><?php echo $nom_postu ?></span>
            </td>
			<td style="width: 30%; color: #444444;">
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>HUARCAYA SILVA MANUEL ENRIQUE</span>
				
			</td>
		   
           	
        </tr>

	</table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 40%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span>________________________________________</span>
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				
			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
            </td>
			<td style="width: 40%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span><?php echo $nombre_universidad_postu ?></span>
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				

			</td>
		   
           	
        </tr>

	</table>
	<table cellspacing="0" style="width: 100%; text-align: right;  vertical-align: middle" >
        <tr>
        	<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
                
            </td>
			<td style="width: 40%; color: black;font-size:12px;text-align:middle;font-weight:bold;">
				<span><?php echo $representante_universidad_postu ?></span>
            </td>
			<td style="width: 30%; color: black;font-size:12px;text-align:middle;font-weight:bold;">				
			</td>
		   
           	
        </tr>

	</table>
	<br><br>
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
-->
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
	<page backtop="20mm" backbottom="20mm" backleft="20mm" backright="20mm" style="font-size: 12pt; font-family: arial" >
	
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 30%; color: black;font-size:15px;text-align:justify">
                
            </td>
			<td style="width: 100%; color: #444444;">
                <span style="color: black;font-size:15px;font-weight:bold;text-decoration: underline black;">CARTA DE AUTORIZACIÓN                </span>
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
			               <img src="../../inc/img/foto.png" style='width: 150px; height: 185px'>
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
WHERE TM_POSTULANTE_TALL.CO_TRAB='".$dni_postulante."'
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
WHERE TM_POSTULANTE_TALL.CO_TRAB='".$dni_postulante."'
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
WHERE TM_POSTULANTE_TALL.CO_TRAB='".$dni_postulante."'
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

		WHERE TM_POSTULANTE_REFE.CO_TRAB='".$dni_postulante."'
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
		AND	TM_POSTULANTE_PROF.CO_TRAB='".$dni_postulante."'
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
			AND	TM_POSTULANTE_PROF.CO_TRAB='".$dni_postulante."'
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
		WHERE TM_POSTULANTE_EMAN.CO_TRAB='".$dni_postulante."'
		AND TM_POSTULANTE_EMAN.TI_DOCU_IDEN='".$codigo_tipo_documento."'
	
		ORDER BY FE_CESE_EMAN DESC

		";
		$resultExperiencia = odbc_exec($conexion,$queryExperiencia);
		//echo $queryExperiencia;
		while($registroExperiencia=odbc_fetch_array($resultExperiencia)) {
		   
		?>
		<tr>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 30%; height: 2%"><?php echo $registroExperiencia['NO_EMPR_ANTE'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 21%;height: 2%"><?php echo $registroExperiencia['DE_PUES_AUXI'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 9%;height: 2%"><?php echo $registroExperiencia['DESDE'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 9%;height: 2%"><?php echo $registroExperiencia['HASTA'] ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 11%;height: 2%">S/ <?php echo $registroExperiencia['SUELDO'] ?>.00</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 20%;height: 2%"><?php echo $registroExperiencia['DE_MOTI_SEPA'] ?></td>
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

		WHERE TM_POSTULANTE_PARI.NU_DOCU_IDEN='".$dni_postulante."'
		AND TM_POSTULANTE_PARI.TI_DOCU_IDEN='".$codigo_tipo_documento."'
		AND TM_POSTULANTE_PARI.NU_CORR_PARI='1'
	
		";
		$resultEsposo = odbc_exec($conexion,$queryEsposo);
		//echo $queryEsposo;
		while($registroEsposo=odbc_fetch_array($resultEsposo)) {
			$apellido_paterno_esposo=$registroEsposo['NO_APEL_PATE'];
			$apellido_materno_esposo=$registroEsposo['NO_APEL_MATE'];
			$nombre_esposo=$registroEsposo['NO_PARI'];
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

		WHERE TM_POSTULANTE_PARI.NU_DOCU_IDEN='".$dni_postulante."'
		AND TM_POSTULANTE_PARI.TI_DOCU_IDEN='".$codigo_tipo_documento."'
		AND TM_POSTULANTE_PARI.NU_CORR_PARI='2'
	
		";
		$resultHijo1 = odbc_exec($conexion,$queryHijo1);
		//echo $queryHijo1;
		while($registroHijo1=odbc_fetch_array($resultHijo1)) {
			$apellido_paterno_hijo1=$registroHijo1['NO_APEL_PATE'];
			$apellido_materno_hijo1=$registroHijo1['NO_APEL_MATE'];
			$nombre_hijo1=$registroHijo1['NO_PARI'];
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

		WHERE TM_POSTULANTE_PARI.NU_DOCU_IDEN='".$dni_postulante."'
		AND TM_POSTULANTE_PARI.TI_DOCU_IDEN='".$codigo_tipo_documento."'
		AND TM_POSTULANTE_PARI.NU_CORR_PARI='3'
	
		";
		$resultHijo2 = odbc_exec($conexion,$queryHijo2);
		//echo $queryHijo2;
		while($registroHijo2=odbc_fetch_array($resultHijo2)) {
			$apellido_paterno_hijo2=$registroHijo2['NO_APEL_PATE'];
			$apellido_materno_hijo2=$registroHijo2['NO_APEL_MATE'];
			$nombre_hijo2=$registroHijo2['NO_PARI'];
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

		WHERE TM_POSTULANTE_PARI.NU_DOCU_IDEN='".$dni_postulante."'
		AND TM_POSTULANTE_PARI.TI_DOCU_IDEN='".$codigo_tipo_documento."'
		AND TM_POSTULANTE_PARI.NU_CORR_PARI='4'
	
		";
		$resultHijo3 = odbc_exec($conexion,$queryHijo3);
		//echo $queryHijo3;
		while($registroHijo3=odbc_fetch_array($resultHijo3)) {
			$apellido_paterno_hijo3=$registroHijo3['NO_APEL_PATE'];
			$apellido_materno_hijo3=$registroHijo3['NO_APEL_MATE'];
			$nombre_hijo3=$registroHijo3['NO_PARI'];
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

		WHERE TM_POSTULANTE_PARI.NU_DOCU_IDEN='".$dni_postulante."'
		AND TM_POSTULANTE_PARI.TI_DOCU_IDEN='".$codigo_tipo_documento."'
		AND TM_POSTULANTE_PARI.NU_CORR_PARI='5'
	
		";
		$resultHijo4 = odbc_exec($conexion,$queryHijo4);
		//echo $queryHijo4;
		while($registroHijo4=odbc_fetch_array($resultHijo4)) {
			$apellido_paterno_hijo4=$registroHijo4['NO_APEL_PATE'];
			$apellido_materno_hijo4=$registroHijo4['NO_APEL_MATE'];
			$nombre_hijo4=$registroHijo4['NO_PARI'];
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
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;"><?php echo $observacion_accidente_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0">ESPECIFIQUE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 1px solid #000000;width: 20%;"><?php echo $observacion_enfermedad_postu ?></td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 0px solid #000000; border-right: 0px solid #000000;width: 13%;font-weight:bold;background-color: #C0C0C0">ESPECIFIQUE:</td>
			<td style="border-top: 0px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;width: 21%;"><?php echo $observacion_operacion_postu ?></td>
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



<page backtop="0mm" backbottom="0mm" backleft="10mm" backright="0mm" style="font-size: 12pt; font-family: arial" >
	<img src='../../images/icon/PAG1.png' style='    max-width: 100%;
				    height: 100%;
				    float: left;'  />
</page>
<page backtop="0mm" backbottom="0mm" backleft="10mm" backright="0mm" style="font-size: 12pt; font-family: arial" >
	<img src='../../images/icon/PAG2.png' style='    max-width: 100%;
				    height: 100%;
				    float: left;'  />
</page>
<page backtop="0mm" backbottom="0mm" backleft="10mm" backright="0mm" style="font-size: 12pt; font-family: arial" >
	<img src='../../images/icon/PAG3.png' style='    max-width: 100%;
				    height: 100%;
				    float: left;'  />
</page>
<page backtop="0mm" backbottom="0mm" backleft="10mm" backright="0mm" style="font-size: 12pt; font-family: arial" >
	<img src='../../images/icon/PAG4.png' style='    max-width: 100%;
				    height: 100%;
				    float: left;'  />
</page>
<page backtop="0mm" backbottom="0mm" backleft="10mm" backright="0mm" style="font-size: 12pt; font-family: arial" >
	<img src='../../images/icon/PAG5.png' style='    max-width: 100%;
				    height: 100%;
				    float: left;'  />
</page>
<page backtop="0mm" backbottom="0mm" backleft="10mm" backright="0mm" style="font-size: 12pt; font-family: arial" >
	<img src='../../images/icon/PAG6.png' style='    max-width: 100%;
				    height: 100%;
				    float: left;'  />
</page>
<page backtop="0mm" backbottom="0mm" backleft="10mm" backright="0mm" style="font-size: 12pt; font-family: arial" >
	<img src='../../images/icon/PAG7.png' style='    max-width: 100%;
				    height: 100%;
				    float: left;'  />
</page>
<page backtop="0mm" backbottom="0mm" backleft="10mm" backright="0mm" style="font-size: 12pt; font-family: arial" >
	<img src='../../images/icon/PAG8.png' style='    max-width: 100%;
				    height: 100%;
				    float: left;'  />
</page>
<page backtop="0mm" backbottom="0mm" backleft="10mm" backright="0mm" style="font-size: 12pt; font-family: arial" >
	<img src='../../images/icon/PAG9.png' style='    max-width: 100%;
				    height: 100%;
				    float: left;'  />
</page>
<page backtop="0mm" backbottom="0mm" backleft="10mm" backright="0mm" style="font-size: 12pt; font-family: arial" >
	<img src='../../images/icon/PAG10.png' style='    max-width: 100%;
				    height: 100%;
				    float: left;'  />
</page>