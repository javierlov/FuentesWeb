<?php
/*
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();
*/
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");


function MostrarPreguntasAdicionales($contrato, $id){
	global $conn;
	SetDateFormatOracle("DD/MM/YYYY");
	$mostrarPreguntasAdicionales = '';

	$params = array(":id" => $_REQUEST["ide"]);
	$sql =
		"SELECT 1
			 FROM comunes.cac_actividad, hys.hpa_preguntaadicional hpa, aes_establecimiento
			WHERE SUBSTR(art.hys.get_codactividadrevdos(ac_id), 1, LENGTH(pa_ciiuviejo)) = pa_ciiuviejo
				AND ac_id = es_idactividad
				AND pa_idresolucion = art.hys.get_idresolucion463(es_id, 'C')
				AND es_id = :id";
	$mostrarPreguntasAdicionales = ExisteSql($sql, $params);

	if ($mostrarPreguntasAdicionales) {
		$params = array(":idestablecimiento" => $_REQUEST["ide"]);
		$sql =
			"SELECT 1
				 FROM hys.hra_respuestaadicional
				WHERE ra_idestablecimiento = :idestablecimiento";
		$mostrarPreguntasAdicionales = (!ExisteSql($sql, $params));
	}
	
	return $mostrarPreguntasAdicionales;
}

function ValidaRGL($contrato, $id){
	global $conn;
	
	$params = array(":contrato" => $_SESSION["contrato"], ":id" => $_REQUEST["ide"]);
	$sql ="SELECT 1
		 FROM aes_establecimiento
		WHERE es_contrato = :contrato
			AND es_id = :id";
			
	validarSesion(ExisteSql($sql, $params));
}

function GetIDEstableci($estableci, $cuit, $annoBusqueda){
	global $conn;
	
	$menosUno = ' '.ANNOANTERIOR.' ';
	if( $annoBusqueda == 'ACTUAL' ) 	$menosUno = '  ';		
	
	$params = array(":estableci" => $estableci, ":cuit" => $cuit );
	
	$sql ="	SELECT   ew_id
			  FROM   hys.hew_establecimientoweb hew
			 WHERE   ew_estableci = :estableci
				 AND ew_cuit = :cuit
				 AND TO_CHAR (ew_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy')  ".$menosUno."  ";
						
	try{		
		$result = ValorSql($sql, "", $params);		
		return $result;
		
	}catch (Exception $e){				
		return 0;						
	}
}

function DatosGenEstableciAnnoAnteriorSinRiesgo($IDCABECERA){	
		global $conn;
	
	$params = array(":IDCABECERA" => $IDCABECERA);
	
	$sql =" SELECT   sr_id,
				TO_CHAR( sysdate, 'DD-MM-YYYY HH24:MI') FECHASERVER, 
				 DECODE (ew_tipoestab || ew_tiponomina, 'OS', 'CONEXPUESTO', 'SINEXPUESTO') tipoestablecimiento,
				 es_nroestableci || ' - ' || es_nombre AS razonsocial,
				 art.utiles.armar_cuit (em_cuit) cuit,
				 es_nombre nombre,
				 cac.ac_codigo ciiuempresa,
				 ac_id actividad,
				 es_empleados cantidadempleados,
					art.utiles.armar_domicilio (es_calle,
												es_numero,
												es_piso,
												es_departamento,
												NULL)
				 || '  '
				 || DECODE (es_cpostala, NULL, NULL, 'CP: ' || es_cpostala)
				   domicilio,
				 es_localidad localidad,
				 pv_descripcion provincia,
				 art.utiles.armar_telefono (es_codareatelefonos, NULL, es_telefonos) telefono,
				 art.utiles.armar_telefono (es_codareafax, NULL, es_fax) fax,
				 ew_versionnomina versionnomina,
				 TO_CHAR (ew_fechaimpresionnomina, 'DD-MM-YYYY HH24:MI') fechaimpresionnomina,
				 TO_CHAR( ew_fechaalta, 'DD-MM-YYYY HH24:MI') EW_FECHAALTA
				 
		  FROM       art.psr_sinriesgo
		  LEFT JOIN  hys.hew_establecimientoweb hew  ON hew.ew_cuit = sr_cuit  AND hew.ew_estableci = sr_estableci
		  INNER JOIN  afi.aes_establecimiento afi  ON afi.es_nroestableci = sr_estableci
		  INNER JOIN  afi.aco_contrato  ON es_contrato = co_contrato
		  INNER JOIN  cac_actividad cac  ON cac.ac_id = co_idactividad
		  INNER JOIN  afi.aem_empresa  ON em_cuit = sr_cuit  AND em_id = co_idempresa
		  RIGHT JOIN  cpv_provincias  ON es_provincia = pv_codigo
		 WHERE   sr_id = :IDCABECERA ";
				
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);	
	return $row;	
}

function DatosGenEstablecimientoCabeceraNomina($IDCABECERA, $annoACTUAL){	
	global $conn;
	
	$menosUno = ' '.ANNOANTERIOR.' ';
	if( $annoACTUAL == 'ACTUAL' ) 	$menosUno = '  ';		
		
	$sql =" SELECT   CN_ID, CN_CUIT, CN_ESTABLECI, 
				 TO_CHAR( sysdate, 'DD-MM-YYYY HH24:MI') FECHASERVER, 
				 DECODE (ew_tipoestab || ew_tiponomina, 'OS', 'CONEXPUESTO', 'SINEXPUESTO') TIPOESTABLECIMIENTO,
				 es_nroestableci || ' - ' || es_nombre AS RAZONSOCIAL,
				 art.utiles.armar_cuit (em_cuit) CUIT,
				 es_nombre NOMBRE,
				 cac.ac_codigo CIIUEMPRESA,
				 ac_id ACTIVIDAD,
				 es_empleados CANTIDADEMPLEADOS,
				 art.utiles.armar_domicilio (es_calle,es_numero,es_piso,es_departamento,NULL) || '  '  || DECODE (es_cpostala, NULL, NULL, 'CP: ' || es_cpostala) DOMICILIO,
				 es_localidad LOCALIDAD,
				 pv_descripcion PROVINCIA,
				 art.utiles.armar_telefono (es_codareatelefonos, NULL, es_telefonos) TELEFONO,
				 art.utiles.armar_telefono (es_codareafax, NULL, es_fax) FAX,
				 ew_versionnomina VERSIONNOMINA,
				 TO_CHAR (ew_fechaimpresionnomina, 'DD-MM-YYYY HH24:MI') FECHAIMPRESIONNOMINA,				 
				 ART.CTB_TABLAS.TB_DESCRIPCION  HYS_CARGO,
				 TO_CHAR( ew_fechaalta, 'DD-MM-YYYY HH24:MI') EW_FECHAALTA
				 
		  FROM        hys.hcn_cabeceranomina
		  INNER JOIN  afi.aes_establecimiento afi  ON afi.es_nroestableci = cn_estableci
		  INNER JOIN  afi.aco_contrato  ON es_contrato = co_contrato
		  INNER JOIN  cac_actividad cac  ON cac.ac_id = co_idactividad
		  INNER JOIN  afi.aem_empresa  ON em_cuit = cn_cuit  AND em_id = co_idempresa
		  RIGHT JOIN  cpv_provincias  ON es_provincia = pv_codigo
		  
		  LEFT JOIN   hys.hew_establecimientoweb hew  ON hew.ew_cuit = cn_cuit  AND hew.ew_estableci = cn_estableci
					AND TO_CHAR (ew_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy')  ".$menosUno."
		  
		  LEFT JOIN   HYS.HRW_RESPONSABLENOMINAWEB hrwr ON hrwr.RW_IDRELEVNOMINA = hew.ew_id AND hrwr.rw_tiporesp = 'R' 
		  
		  LEFT JOIN   (select * from HYS.HRW_RESPONSABLENOMINAWEB where rw_tiporesp = 'H' )hrwh  on hrwh.RW_IDRELEVNOMINA = hew.ew_id 
		  LEFT JOIN   ART.CTB_TABLAS ON TB_CODIGO = hrwh.RW_CARGO AND   TB_CLAVE = 'CARGO'  ";
		  
	
	$sql .= " WHERE   cn_id = :IDCABECERA ";		
	$params = array(":IDCABECERA" => $IDCABECERA);

	$sql .= "  	AND TO_CHAR (CN_FECHARELEVAMIENTO, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$menosUno."
				AND CN_IDESTADO NOT IN (3, 6)
				AND rownum = 1 ";		
		
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);	
	return $row;	
}

function DatosGenEstablecimientoWEB($IDESTABLECIMIENTOWEB){	
	global $conn;

	$params = array(":IDESTABLECIMIENTOWEB" => $IDESTABLECIMIENTOWEB);
	
	$sql =" SELECT   
			TO_CHAR( sysdate, 'DD-MM-YYYY HH24:MI') FECHASERVER, 
			DECODE (ew_tipoestab || ew_tiponomina, 'OS', 'CONEXPUESTO', 'SINEXPUESTO') TIPOESTABLECIMIENTO,
			es_nroestableci || ' - ' || es_nombre AS RAZONSOCIAL,
			es_nroestableci NROESTABLECI, 
			art.utiles.armar_cuit (em_cuit) CUIT,
			es_nombre NOMBRE,
			cac.ac_codigo  CIIUEMPRESA, 
			ew_idactividad ACTIVIDAD, 
			es_empleados CANTIDADEMPLEADOS,
			art.utiles.armar_domicilio (es_calle,
                                        es_numero,
                                        es_piso,
                                        es_departamento,
                                        NULL)
				 || '  '
				 || DECODE (es_cpostala, NULL, NULL, 'CP: ' || es_cpostala)           DOMICILIO,
			es_localidad LOCALIDAD,
			pv_descripcion PROVINCIA,
			art.utiles.armar_telefono (es_codareatelefonos, NULL, es_telefonos) TELEFONO,
			art.utiles.armar_telefono (es_codareafax, NULL, es_fax) FAX,
			EW_VERSIONNOMINA VERSIONNOMINA,
			TO_CHAR( EW_FECHAIMPRESIONNOMINA, 'DD-MM-YYYY HH24:MI') FECHAIMPRESIONNOMINA,
			TO_CHAR( ew_fechaalta, 'DD-MM-YYYY HH24:MI') EW_FECHAALTA
	FROM hys.hew_establecimientoweb hew	
	INNER JOIN afi.aes_establecimiento afi	ON afi.es_nroestableci = hew.ew_estableci
	INNER JOIN afi.aco_contrato	ON es_contrato = co_contrato
	INNER JOIN afi.aem_empresa	ON em_cuit = hew.ew_cuit	AND em_id = co_idempresa	
	LEFT JOIN cac_actividad cac	ON cac.ac_id = hew.ew_idactividad
	RIGHT JOIN	cpv_provincias	ON es_provincia = pv_codigo
	WHERE   hew.ew_id = :IDESTABLECIMIENTOWEB ";
				
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);	
	
	if( !isset($row['NROESTABLECI']) || $row['NROESTABLECI'] == '' ){	
		$row['NROESTABLECI'] = '';
		$row['TIPOESTABLECIMIENTO'] = '';
		$row['CUIT '] = '';
		$row['FECHASERVER'] = '';
		$row['CUIT'] = '';
		$row['RAZONSOCIAL'] = '';
		$row['CIIUEMPRESA'] = '';
		$row['ACTIVIDAD'] = '';
		$row['CANTIDADEMPLEADOS'] = '';
		$row['DOMICILIO'] = '';
		$row['LOCALIDAD'] = '';
		$row['PROVINCIA'] = '';
		$row['TELEFONO'] = '';
		$row['FAX'] = '';
		$row['EW_FECHAALTA'] = '';
	}
	return $row;
}

function DatosGenEstablecimiento($es_id){	
	global $conn;
	
	$params = array(":id" => $es_id);
	$sql ="SELECT cac2.ac_codigo ciiuempresa, cac1.ac_codigo ciiuestablecimiento, art.utiles.armar_cuit(em_cuit) cuit,
						art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, NULL) domicilio, 
						pv_descripcion, em_nombre, es_cpostala, 
						es_descripcionactividad, es_empleados,
						es_idactividad, es_localidad, es_nroestableci, es_superficie, 
						art.utiles.armar_telefono(es_codareatelefonos, NULL, es_telefonos) telefono,
						pv_descripcion PROVINCIA, ES_FAX
						
			 FROM aco_contrato, aem_empresa, aes_establecimiento, cac_actividad cac1, cac_actividad cac2, cpv_provincias
			WHERE co_idempresa = em_id
				AND co_contrato = es_contrato
				AND es_idactividad = cac1.ac_id
				AND co_idactividad = cac2.ac_id
				AND es_provincia = pv_codigo(+)
				AND es_id = :id";
				
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);	
	return $row;
}

function ValidFieldTelef($field){
	$arrayFields = array("e", "tipoTelDescrip", "area", "numero", "interno");
	if (in_array($field, $arrayFields)) {
		return true;
	}
	return false;
}

function NameHeaderTelef($field){
	$arrayFields = array("e"=>"Edit", "tipoTelDescrip"=>"Tipo de Telefono", "area"=>"Area", "numero"=>"Numero", "interno"=>"Interno");
	if (array_key_exists($field, $arrayFields)) {
		foreach($arrayFields as $key=>$value){
			if($key==$field) return $value;
		}
	}
	return '';
}

function GetTempTableTelefonos($jsonTelefonos){
	$jsonTelefonos = urldecode($jsonTelefonos);	
	
	if( !isset($jsonTelefonos) ) return "<b style='color:red; font: Arial 14;' >No se encontraron Telefonos.<b>";
	if( trim($jsonTelefonos) == '' ) return "<b style='color:red; font: Arial 14;' >Lista de Telefonos vacia.<b>";
			
	$arrayTelefonos = json_decode($jsonTelefonos, true);	
		
	try{	
	$table = "<table width='100%' class='GridTableCiiu'> ";
	$cont=0;
	
	foreach($arrayTelefonos as $rows){			
		if($cont==0){//header
			$table .= "
			<tr> 
			";
			foreach($rows as $key=>$value){
				if( ValidFieldTelef($key) )
				$table .= "					 
					 <td align='center' class='gridHeader' ><a class='gridTitle' style='text-decoration: none; color:rgb(255,255,255);' >".NameHeaderTelef($key)."</a></td>
					";
			}
			$table .= "
			</tr>
			";
		}
		
		$table .= "<tr class='gridFondoOnMouseOver gridRow1' >
		";
		foreach($rows as $key=>$value){
			if( ValidFieldTelef($key) ){
				if($key == 'e')
					$table .= "
					 <td align='center' class='gridColAlignLeft gridText' >
						<div>
							<input type='button' class='btnEditar' onclick='Edit_Telefono($value)' />				
						</div>
					 </td>";
				else
					$table .= "
					 <td align='left' class='gridColAlignLeft gridText' >
						<div>".$value."</div>
					 </td>";
			}
		}
		$cont++;
		$table .= "</tr>";		
	}
	$table .= "</table>";
	return $table;
	
	}catch (Exception $e){			
		return "<b style='color:red;' >fallo GetTempTableTelefonos: </b>".$e->getMessage();
	}
}

/******** Personal Expuesto ***********/
function GetHeaderPersonalExpuesto(){
	$arrayFields = array("Seleccionar"=>"Seleccionar",
					"CUIL"=>"C.U.I.L.",
					"NombreApellido"=>"Nombre y Apellido",
					"FecIngreso"=>"Fec. de ingreso a la empresa",
					"FecInicio"=>"Fec. de inicio de la exposicion",
					"SectorTrabajo"=>"Sector de Trabajo",
					"PuestoTrabajo"=>"Puesto de Trabajo",
					"IdentificacionRiesgo"=>"Identificacion de riesgo segun codigo ESOP");

	return $arrayFields;
}

function NameHeaderPersonalExpuesto($field){

	$arrayFields = GetHeaderPersonalExpuesto();
	
	if (array_key_exists($field, $arrayFields)) {
		foreach($arrayFields as $key=>$value){
			if($key==$field) return $value;
		}
	}
	return '';
}

function GetTempTablePersonalExpuesto($jsonPersonalExpuesto){
		
	try{	
		$table = "<table width='100%' class='GridTableCiiu'> ";
		
		$table .= "		<tr> 		";
		$rows = GetHeaderPersonalExpuesto();
		foreach($rows as $key=>$value){				
			$table .= "					 
				 <td align='center' class='gridHeader' ><a class='gridTitle' style='text-decoration: none; color:rgb(255,255,255);' >".NameHeaderPersonalExpuesto($key)."</a></td>
				";
		}
		$table .= "		</tr>		";
		
		$table .= AddRowsTablePersonalExpuesto($jsonPersonalExpuesto);
		
		$jsonEdit = '[{"Seleccionar":"edit", "CUIL":"0111", "NombreApellido":"00111", "FecIngreso":"0011", "FecInicio":"0011", "SectorTrabajo":"00011", "PuestoTrabajo":"11200", "IdentificacionRiesgo":"12000"}]';
		
		$table .= AddRowsTablePersonalExpuesto($jsonEdit);

		$table .= "</table>";
		return $table;
	
	}catch (Exception $e){			
		return "<b style='color:red;' >fallo GetTempTablePersonalExpuesto: </b>".$e->getMessage();
	}
}

function AddRowsTablePersonalExpuesto($jsonPersonalExpuesto){
	try{
		$cont=0;
		$table = '';
		
		$jsonPersonalExpuesto = urldecode($jsonPersonalExpuesto);	
		
		if( !isset($jsonPersonalExpuesto) ) 	return '';			
		if( trim($jsonPersonalExpuesto) == '' ) return '';
				
		$arrayPersonalExpuesto = json_decode($jsonPersonalExpuesto, true);	
	
		foreach($arrayPersonalExpuesto as $rows){					
			
			$table .= "<tr class='gridFondoOnMouseOver gridRow1' >	";
			
			foreach($rows as $key=>$value){			
				if($value == 'v')
					$table .= " <td class='gridColAlignCenter gridText' > <div>.</div> </td>";
				else if($key == 'Seleccionar')
					$table .= " <td align='center' > <input type='button' class='btnNuevoItem' /> </td>";
				else if($key == 'CUIL')
					$table .= " <td align='left' > <input type='text' style='width:68px;' value='44223335550' /> </td>";
				else if($key == 'NombreApellido')
					$table .= " <td align='left' > <input type='text' style='width:150px;' value='44223335550' /> </td>";
				else if($key == 'FecIngreso')
					$table .= " <td align='left' > <input type='text' style='width:90px;' value='44223335550' /> </td>";
				else if($key == 'FecInicio')
					$table .= " <td align='left' > <input type='text' style='width:90px;' value='44223335550' /> </td>";
				else if($key == 'SectorTrabajo')
					$table .= " <td align='left' > <input type='text' style='width:100px;' value='44223335550' /> </td>";
				else if($key == 'PuestoTrabajo')
					$table .= " <td align='left' > <input type='text' style='width:100px;' value='44223335550' /> </td>";
				else if($key == 'IdentificacionRiesgo')
					$table .= " <td align='left' > <input type='text' style='width:150px;' value='44223335550' /> </td>";
				else
					$table .= " <td class='gridColAlignCenter gridText' > <div>".$value."</div> </td>";		
			}
			$cont++;
			$table .= "</tr>";		
		}
		return $table;
		
	}catch (Exception $e){			
		return "<b style='color:red;' >fallo GetTempTablePersonalExpuesto: </b>".$e->getMessage();
	}
}
