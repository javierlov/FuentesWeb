<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid_columnAjax.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/gridAjax.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CommonFunctions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/SeleccionarEstablecimiento.Grid.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/FuncionesEstablecimientos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/CargaESOP.Grid.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/NominaPersonalExpuesto.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/RAR_funcionesValidacion.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");

@session_start(); 

define("ANNOANTERIOR", -1);

if(isset($_REQUEST["funcion"])){
	
	if($_REQUEST["funcion"] == "GetJSArrayEmpleados" ){
		
		try{			
			$ArrayName = $_REQUEST["ArrayName"];
			$EMPRESA = $_REQUEST["EMPRESA"];					
			$NOMBRE = '';
			
			if( isset($_REQUEST["NOMBRE"]) )
				$NOMBRE = $_REQUEST["NOMBRE"];						
			
			$result = GetJS_ArrayEmpleadosNombre($ArrayName, $EMPRESA, $NOMBRE);			
			echo $result;
			
		}catch (Exception $e){							
			RetronaXML($e->getMessage());						
		}
	}
	
	if($_REQUEST["funcion"] == "SetResponsableDefault" ){	
		try{
			$CODIGOEWID = $_REQUEST["CODIGOEWID"];						
			echo SetResponsableDefault($CODIGOEWID);			
						
		}catch (Exception $e){							
			RetronaXML($e->getMessage());						
		}
	}
  
	if( isset($_REQUEST["CODIGOACTIVIDAD"]) ){
		$_SESSION["IDACTIVIDAD"] = $_REQUEST["CODIGOACTIVIDAD"];
		header("Location: /NominaPersonalExpuesto");
	}
	
	if( isset($_REQUEST["InsertNominaNuevadeAnterior"]) ){
		try{
			$InsertNominaNuevadeAnterior = false;
			
			if(isset($_REQUEST["InsertNominaNuevadeAnterior"])) 
				$InsertNominaNuevadeAnterior = true;
				
			GrabarFormularioNomina($InsertNominaNuevadeAnterior);
			
		}catch (Exception $e){							
			SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		}
	}
	
	if($_REQUEST["funcion"] == "BuscarTrabajadorJSON"){
		try{
			
			$CONTRATO = GetParametroDecode("CONTRATO");				
			$CUIT = GetParametroDecode("CUIT");				
			$CUILEMPRESA = GetParametroDecode("CUILEMPRESA");
												
			$ARRAY_ROW = BuscarTrabajador($CONTRATO, $CUIT, $CUILEMPRESA);
			$json_ARRAY_ROW = json_encode($ARRAY_ROW);		
			
			echo $json_ARRAY_ROW;			
			
		}catch (Exception $e){			
			//echo "<b>fallo: </b>".$e->getMessage();
			RetronaXML($e->getMessage());						
		}
	}
	
	if($_REQUEST["funcion"] == "BuscarDetalleESOPJSON"){
		try{
			
			$RiesgoESOP = GetParametroDecode("RiesgoESOP");				
												
			$ARRAY_ROW = BuscarDetalleESOP($RiesgoESOP);
			$json_ARRAY_ROW = json_encode( $ARRAY_ROW );		
			
			echo $json_ARRAY_ROW;			
			
		}catch (Exception $e){						
			RetronaXML($e->getMessage());						
		}
	}
	
	if($_REQUEST["funcion"] == "SaveNominaPrimerosDatos"){
		try{
			
			$jsonPrimerosDatos = GetParametroDecode("jsonPrimerosDatos");				
			$debug =false;
			if (strtoupper(GetParametroDecode("debug")) == 'TRUE' ) $debug = true;				
			
			if (UpdateNominaPrimerosDatos($jsonPrimerosDatos, $debug) ) 
						RetronaXML("OK");		
			else    	RetronaXML("FALLO");		
			
		}catch (Exception $e){						
			RetronaXML($e->getMessage());						
		}
	}
	
	if($_REQUEST["funcion"] == "GetTempTableTelefonos"){
		try{
			$jsonTelefonos = GetParametroDecode("jsonTelefonos");				
			echo GetTempTableTelefonos($jsonTelefonos);						
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}
	}
	
	
	if($_REQUEST["funcion"] == "ValidaExistenTrabajadoresAsignados"){
			try{
			$id = GetParametroDecode("id");							
			$cantTrabajadores = Valida_ExistenTrabajadoresAsignados($id);
			
			echo $cantTrabajadores;
			
		}catch (Exception $e){			
			echo "<b>fallo ValidaExistenTrabajadoresAsignados: </b>".$e->getMessage();
		}
	}
	
	if($_REQUEST["funcion"] == "ValidoTrabajadorNomina"){
		/*
			1 - ingresado en otra nomina web
			2 - ingresado en una nomina ya aprobada
			3 - repetido en la nomina actual
		*/
		try{
			$cuilTrabajador = GetParametroDecode("cuilTrabajador");				
			$cuitEmpresa = GetParametroDecode("cuitEmpresa");				
			$establecimiento = GetParametroDecode("establecimiento");	
			
			$resValido = Valida_TrabajadorEnOtraNomina($cuilTrabajador, $cuitEmpresa, $establecimiento);
			
			echo $resValido;
			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}
	}
	
	if($_REQUEST["funcion"] == "GetTempTablePersonalExpuesto"){
		try{
			$jsonPersonalExpuesto = GetParametroDecode("jsonPersonalExpuesto");				
			echo GetTempTablePersonalExpuesto($jsonPersonalExpuesto);						
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}
	}
	
	if($_REQUEST["funcion"] == "GuardarNominaEstablecimiento"){
		try{
			$IDESTABLECIWEB = GetParametroDecode("IDESTABLECIWEB");								
			$resValido = '';
			$resValido = Confirma_NominaWeb($IDESTABLECIWEB);			
			echo $resValido;			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}
	}
	
	if($_REQUEST["funcion"] == "MensajesNominaEstablecimiento"){
		try{
			$IDESTABLECIWEB = GetParametroDecode("IDESTABLECIWEB");						
			$json_resValido = '';
			$resValido = Questions_ConfirmaNomina($IDESTABLECIWEB);				
			$json_resValido = json_encode($resValido);				
			echo $json_resValido;			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}
	}
	
	if($_REQUEST["funcion"] == "getGridDatosNominaWeb"){
		try{
			
			$page = GetParametroDecode("page");				
			$idEstablecimiento = GetParametroDecode("idEstablecimiento");				
			
			$buscaNombre = utf8_decode(GetParametroDecode("buscaNombre"));				
			$buscaCuil = GetParametroDecode("buscaCuil");				
			
			$grilla = getGridDatosNominaWeb($page, $idEstablecimiento, $buscaNombre, $buscaCuil);						
			
			//echo utf8_encode($grilla);						
			echo $grilla;						
			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}
	}
	
	if($_REQUEST["funcion"] == "GrillaEstablecimientos"){
		try{			
			$contrato = GetParametroDecode("contrato");	
			$idEstablecimiento = GetParametroDecode("idEstablecimiento");	
			$EstablecimientoNombre = GetParametroDecode("EstablecimientoNombre");	
			$calle = GetParametroDecode("calle");	
			$CPostal = GetParametroDecode("CPostal");	
			$Localidad = GetParametroDecode("Localidad");	
			$Provincia = GetParametroDecode("Provincia");	
			
			echo getGridSeleccionaEstablecimieto($contrato, $idEstablecimiento, $EstablecimientoNombre, $calle, $CPostal, $Localidad, $Provincia);			
			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}
	}
	
	if($_REQUEST["funcion"] == "ValidarNomina"){
		try{	
			$pagina = GetParametroDecode("idEstablecimiento", '0');							
			ValidarNominaWeb($idEstablecimiento );
			return true;
			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
			return false;
		}		
	}
	
	if($_REQUEST["funcion"] == "GridDatosESOP"){
		try{
			$pagina = GetParametroDecode("pagina", '1');							
			$codactividad = GetParametroDecode("codactividad", '0');							
			$codigo = GetParametroDecode("codigo", '0');										
			$descripcion = GetParametroDecode("descripcion", '');	
			$inFiltroRiesgos = GetParametroDecode("inFiltroRiesgos", '');	

			echo getGridDatosESOP($pagina, $codactividad, $codigo, $descripcion, $inFiltroRiesgos );
			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}
	}
	
	if($_REQUEST["funcion"] == "EliminarNominaWEB"){
		try{
			$idNomina = GetParametroDecode("idNomina", '0');										
			EliminarNominaWEB($idNomina);			
			echo 'Eliminado.......';
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}	
	}
	
	if($_REQUEST["funcion"] == "NominaWEBRechazoMotivo"){
		try{
			$idNomina = GetParametroDecode("idNomina", '0');										
			echo NominaWEBRechazoMotivo($idNomina);			
			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}	
	}
	
	if($_REQUEST["funcion"] == "InsertNominaActualdeNominaAnterior"){
		try{			
			$idEstablecimiento = GetParametroDecode("idEstablecimiento", '0');						 			
			$cuitEmpresa = GetParametroDecode("cuitEmpresa", '');												
			$usualta = GetParametroDecode("usualta", '');												
						
			echo Insert_NominaActualdeNominaAnterior($idEstablecimiento, $cuitEmpresa, $usualta);
			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}	
	}
	
	if($_REQUEST["funcion"] == "GrabarNominaConfirmada"){
		try{

			$idEstablecimiento = GetParametroDecode("idEstablecimiento", '');							
						
			echo GrabarEstadoNomina($idEstablecimiento, 'L', true, null);
			return true;
			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
			return false;
		}
	}
	
	if($_REQUEST["funcion"] == "BuscarPuestoTrab"){
		try{			
			$id = GetParametroDecode("id");							
			$idpuesto = BuscarPuestoTrab($id);						
			echo $idpuesto;			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}	
	}
	
	if($_REQUEST["funcion"] == "GrabarRegistroNomina"){
		try{

			$idRow = GetParametroDecode("idRow", '');							
			$idEstablecimiento = GetParametroDecode("idEstablecimiento", '');							
			$cuil = GetParametroDecode("cuil", '');				
			$nombre = urldecode(GetParametroDecode("nombre", ''));				
			$fechaingreso = urldecode(GetParametroDecode("fechaingreso", ''));				
			$fechainiexpo = urldecode(GetParametroDecode("fechainiexpo", ''));				
			$sectortrab = urldecode(GetParametroDecode("sectortrab", ''));				
			$puestotrab = urldecode(GetParametroDecode("puestotrab", ''));				
			$arrayRiesgos = urldecode(GetParametroDecode("arrayRiesgos", ''));				
			
			echo GrabarRegistroNomina($idRow, $idEstablecimiento,  $cuil, $nombre, $fechaingreso, $fechainiexpo, $sectortrab, $puestotrab, $arrayRiesgos);
			return true;			
		}catch (Exception $e){			
			echo "<b>fallo: </b>".$e->getMessage();
		}
	}
		
}

function GetGrillaTelefonosRespHYS(){


	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = "1";
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];		
		
	$showProcessMsg = false;
	
	$params = array(":contrato" => $contrato);
	
	$sql = ObtenerTelefonosResp();
}

function ObtenerTelefonosResp(){
	$sql = "SELECT RW_ID,RW_IDRELEVNOMINA,RW_TIPORESP,RW_NOMBRE,
					RW_APELLIDO,RW_CARGO,RW_CODAREA,RW_TELEFONO,
					RW_TIPOTELEFONO,RW_INTERNO,RW_PRINCIPAL,RW_OBSERVACIONES,
					RW_EMAIL,RW_TIPODOCUMENTO,RW_NUMERODOCUMENTO,RW_SEXO  
			FROM HYS.HRW_RESPONSABLENOMINAWEB
			WHERE";
			
}

function GetParametro($nombreparam, $default = ''){
	$valor = $default;
	if(isset($_REQUEST[$nombreparam]))
		$valor = $_REQUEST[$nombreparam];
		
	return $valor;
	//return utf8_decode($valor);
}

function GetParametroDecode($nombreparam, $default = ''){
	$valor = $default;
	if(isset($_REQUEST[$nombreparam]))
		$valor = $_REQUEST[$nombreparam];
	
	return urldecode($valor);
}

function GetParametroArray($arrayParam, $default = ''){
	$valor = $default;
	if(isset($arrayParam))
		$valor = $arrayParam;
		
	return $valor;	
}

function GetSelectProvincias(){
	/*retorna un combo HTML con todas las Provincias para la busqueda por descripcion*/
	$default=0;
	
	$sql = "SELECT pv_codigo id, pv_descripcion descripcion
			FROM cpv_provincias
		 WHERE pv_fechabaja IS NULL
		ORDER BY pv_descripcion";
		
	$result = CreateSelectHTML($sql, 'Provincia', 'width:100%;', $default);

	return $result; 		 
}

function GetSelectCIUU($idDiv, $styleSelect){
	/*retorna un combo HTML con todos los CIIU para la busqueda por descripcion*/
	$default=0;
	
	$sql = "SELECT AC_ID ID, AC_CODIGO CODIGO, AC_DESCRIPCION DESCRIPCION
			FROM COMUNES.CAC_ACTIVIDAD
			 WHERE AC_FECHABAJA IS NULL
			 AND AC_REVISION = 3";
	
	$fields['value'] = "ID";
	$fields['label'] = "DESCRIPCION";
		
	$result = CreateSelectHTML($sql, $idDiv, $styleSelect, $default);

	return $result; 		 
}

function GetJS_ArrayCargo($ArrayName){
	/*retorna un array js con todos los Cargos para la busqueda por descripcion */		
	$sql = " SELECT   tb_codigo ID, tb_descripcion DESCRIPCION
			  FROM   art.ctb_tablas
		     WHERE   tb_clave = 'CARGO'
  		       AND	 tb_fechabaja IS NULL
		  ORDER BY   tb_descripcion ";
			
	$fields['value'] = "ID";
	$fields['label'] = "DESCRIPCION";
	
	$result = CreateArrayJSUI($sql, $ArrayName, $fields);
	
	return $result; 		 
}

function GetJS_ArrayCIUU($ArrayName){
	/*retorna un array js con todos los CIIU para la busqueda por descripcion*/
	$default=0;
	
	$sql = "SELECT AC_ID CODIGO, AC_CODIGO ID, AC_DESCRIPCION DESCRIPCION
			FROM COMUNES.CAC_ACTIVIDAD
			 WHERE AC_FECHABAJA IS NULL
			 AND AC_REVISION = 3
			 ORDER BY AC_DESCRIPCION";
			 
	$fields['value'] = "ID";
	$fields['label'] = "DESCRIPCION";
	$fields['codigo'] = "CODIGO";
	
	$result = CreateArrayJSUI($sql, $ArrayName, $fields);
	
	return $result; 		 
}

function GetJS_ArrayCIUUCodigos($ArrayName){
	/*retorna un array js con todos los CIIU para la busqueda por codigo */
	$default=0;
	
	$sql = "SELECT AC_ID CODIGO, AC_CODIGO DESCRIPCION, AC_DESCRIPCION ID
			FROM COMUNES.CAC_ACTIVIDAD
			 WHERE AC_FECHABAJA IS NULL
			 AND AC_REVISION = 3
			 ORDER BY AC_CODIGO ";
		
	$fields['value'] = "ID";
	$fields['label'] = "DESCRIPCION";
	$fields['codigo'] = "CODIGO";
	
	$result = CreateArrayJSUI($sql, $ArrayName, $fields);
	
	return $result; 		 
}

function GetArrayTipoDocumentos($ArrayName){
	/*retorna un array js con todos los Tipos de Documentos para la busqueda por descripcion */
	$default=0;
	
	$sql = " SELECT tb_codigo id, tb_descripcion descripcion
			 FROM ctb_tablas
			WHERE tb_clave = 'TDOC'
				AND tb_codigo <> '0'
				AND tb_fechabaja IS NULL
			ORDER BY tb_descripcion;";
		
	$result = CreateArrayJSUI($sql, $ArrayName);
	
	return $result; 		 
}

function GetSelectSexos($id, $style, $default='0'){
	
	$selected_M = ' ';
	$selected_F = ' ';
	if($default == 'M')	$selected_M = ' selected="selected" ';
	if($default == 'F')	$selected_F = ' selected="selected" ';

	$resultado = '<select type="text" style="'.$style.'" id="'.$id.'"  name="'.$id.'" >
					  <option value=""></option> 
					  <option value="M" '.$selected_M.' >Masculino</option> 
					  <option value="F" '.$selected_F.' >Femenino</option> 
					  </select> ';
					
    return $resultado;
}

function GetSelectTipoDocumentos($idDiv, $styleDiv, $default = 0){
	/*retorna un combo HTML con todas los Tipos de Documentos para la busqueda por descripcion*/
		
	$sql = " SELECT TB_CODIGO ID, TB_DESCRIPCION DESCRIPCION, TB_ID CODIGO
			  FROM ctb_tablas
			 WHERE tb_clave = 'TDOC'
				AND tb_codigo <> '0'
				AND tb_fechabaja IS NULL
			ORDER BY tb_descripcion ";
		//
	$result = CreateSelectHTML($sql, $idDiv, $styleDiv, $default);
	
	return $result; 		 
}

function GetSelectTipoTelefono($idDiv, $styleDiv, $default='0'){
	/*retorna un combo HTML con todas los Tipos de Telefonos para la busqueda por descripcion*/
		
	$sql = "SELECT tt_id ID, tt_descripcion DESCRIPCION
				FROM att_tipotelefono
			ORDER BY 2";
		//
	$result = CreateSelectHTML($sql, $idDiv, $styleDiv, $default);

	return $result; 		 
}

function GetTelefonosJSON($ID){		
	global $conn;
	$jsonGroup = '';
	
	$params = array(":ID" => $ID);	
	
	$sql = "SELECT   RW_ID, 
					 RW_TIPORESP,
					 RW_IDRELEVNOMINA,
					 RW_TIPOTELEFONO,
					 ATT_TIPOTELEFONO.TT_DESCRIPCION,
					 RW_CODAREA,
					 RW_TELEFONO,
					 RW_INTERNO,
					 RW_PRINCIPAL,
					 RW_OBSERVACIONES					 
			  FROM   HYS.HRW_RESPONSABLENOMINAWEB
	    INNER JOIN 	 ATT_TIPOTELEFONO ON ATT_TIPOTELEFONO.TT_ID = HYS.HRW_RESPONSABLENOMINAWEB.RW_TIPOTELEFONO
			 WHERE   RW_TIPORESP = 'H'
			   AND 	 RW_IDRELEVNOMINA = :ID 
			   AND 	 ROWNUM <= 3  
		  ORDER BY	 RW_IDRELEVNOMINA";
			
		@$stmt = DBExecSql($conn, $sql, $params);	
		
		$i = 0;				
		while ($row = DBGetQuery($stmt)){										
			$jsonRow = '';
					 
			$jsonRow .= '{ "e":"'.$i.'",';
			$jsonRow .= ' "ID":"'.$row['RW_ID'].'",';
			$jsonRow .= ' "TIPORESP":"'.$row['RW_TIPORESP'].'",';
			$jsonRow .= ' "tipoTelefono":"'.$row['RW_TIPOTELEFONO'].'",';
			$jsonRow .= ' "tipoTelDescrip":"'.$row['TT_DESCRIPCION'].'",';
			$jsonRow .= ' "area":"'.$row['RW_CODAREA'].'",';
			$jsonRow .= ' "numero":"'.$row['RW_TELEFONO'].'",';
			$jsonRow .= ' "interno":"'.$row['RW_INTERNO'].'",';
			$jsonRow .= ' "principal":"'.$row['RW_PRINCIPAL'].'",';
			$jsonRow .= ' "observaciones":"'.$row['RW_OBSERVACIONES'].'"}';	
			
			if($jsonGroup != '') $jsonGroup .= ', ';
			$jsonGroup .= $jsonRow;
			$i++;
		}
		
		return $jsonGroup;
}

function GetDatosNominaWebResponsable($idEstablecimiento, $tipoResp, $idEWEstableci = '', $idEWCUIT = '', $annoActual = 'ACTUAL' ){
	/*retorna los datos para las secciones de responsables*/
	/*Tipo de responsable - H: Responsable HyS  R: Responsable de la empresa  C: Contacto*/
	
	try{
		global $conn;
		
		$sql = "SELECT   RW_ID,
						 RW_IDRELEVNOMINA,
						 RW_TIPORESP,
						 RW_NOMBRE,
						 RW_APELLIDO,
						 RW_CARGO,
						 TB_DESCRIPCION CARGO_DESCRIPCION,
						 RW_CODAREA,
						 RW_TELEFONO,
						 RW_TIPOTELEFONO,
						 RW_INTERNO,
						 RW_PRINCIPAL,
						 RW_OBSERVACIONES,
						 RW_EMAIL,
						 RW_TIPODOCUMENTO,
						 RW_NUMERODOCUMENTO,
						 RW_SEXO,
						 RW_IGUALARESP
				  FROM   HYS.HRW_RESPONSABLENOMINAWEB
			 INNER JOIN HYS.HEW_ESTABLECIMIENTOWEB ON EW_ID = RW_IDRELEVNOMINA
		     LEFT JOIN   ART.CTB_TABLAS ON TB_CODIGO = RW_CARGO
				   AND   TB_CLAVE = 'CARGO' ";		
		
		if($idEWEstableci == '' || $idEWEstableci == '0' ){			
			$params = array(":IDRELEVNOMINA" => $idEstablecimiento, ":TIPORESP" => $tipoResp);
			
			$sql .= "  WHERE   RW_IDRELEVNOMINA = :IDRELEVNOMINA
				   AND 	 RW_TIPORESP = :TIPORESP ";
		}else{
			$params = array(":idEWEstableci" => $idEWEstableci, ":idEWCUIT" => $idEWCUIT, ":TIPORESP" => $tipoResp );	
			
				$sql .= "  WHERE   ew_estableci = :idEWEstableci
					   AND EW_CUIT = TRIM(:idEWCUIT)
					   AND UPPER(RW_TIPORESP) = UPPER(:TIPORESP) 	";
					   
			if($annoActual == 'ACTUAL'){
				$sql .= " AND TO_CHAR (ew_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ";
			}ELSE{
				$sql .= " AND TO_CHAR (ew_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') -1 ";
			}
		}		
		
		$sql .= "  AND   ROWNUM = 1 ";
						
		@$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){					
			return $row;		
		}
		
		$row = array("RW_ID" => '0',
					"RW_IDRELEVNOMINA" => '',
					"RW_TIPORESP" => '',
					"RW_NOMBRE" => '',
					"RW_APELLIDO" => '',
					"RW_CARGO" => '',
					"CARGO_DESCRIPCION" => '',
					"RW_CODAREA" => '',
					"RW_TELEFONO" => '',
					"RW_TIPOTELEFONO" => '',
					"RW_INTERNO" => '',
					"RW_PRINCIPAL" => '',
					"RW_OBSERVACIONES" => '',
					"RW_EMAIL" => '',
					"RW_TIPODOCUMENTO" => '',
					"RW_NUMERODOCUMENTO" => '',
					"RW_SEXO" => '',
					"RW_IGUALARESP" => '');
		
		return $row;		
		
	}catch (Exception $e) {		
		throw new Exception("Error GetDatosNominaWebResponsable : ".$e->getMessage() ); 
	}							
}

function GetDatosNominaWebEstablecimiento($idEstablecimiento){
	/*retorna los datos para la seccion seleccion tipo establecimiento*/
	try{
		global $conn;
		$params = array(":ESTABLEC" => $idEstablecimiento);	
		
		$sql = " SELECT   EW_ID,
						 EW_CUIT,
						 EW_ESTABLECI,
						 EW_TIPOESTAB,
						 EW_TIPONOMINA,
						 EW_IDACTIVIDAD,
						 EW_DESCRIPCIONESTAB,
						 EW_ESTADO,
						 EW_IDMOTIVORECHAZO,
						 EW_USUALTA,
						 EW_FECHAALTA,
						 EW_USUMODIF,
						 EW_FECHAMODIF,
						 EW_USUBAJA,
						 EW_FECHABAJA,						 
						 AC_CODIGO, 
						 AC_DESCRIPCION
				  FROM   hys.hew_establecimientoweb
			 LEFT JOIN 	 comunes.cac_actividad on ac_id = ew_idactividad
				 WHERE   EW_ID = :ESTABLEC 						   				   
				   AND	 ROWNUM = 1 ";	
				
		@$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){		
			return $row;		
		}
		
		$row = array("EW_ID" => '',
					"EW_CUIT" => '',
					"EW_ESTABLECI" => '',
					"EW_TIPOESTAB" => '',
					"EW_TIPONOMINA" => '',
					"EW_IDACTIVIDAD" => '',
					"EW_DESCRIPCIONESTAB" => '',
					"EW_ESTADO" => '',
					"EW_IDMOTIVORECHAZO" => '',
					"EW_USUALTA" => '',
					"EW_FECHAALTA" => '',
					"EW_USUMODIF" => '',
					"EW_FECHAMODIF" => '',
					"EW_USUBAJA" => '',
					"EW_FECHABAJA" => '',
					"AC_CODIGO" => '', 
					"AC_DESCRIPCION" => '');
		return $row;		
		
	}catch (Exception $e) {		
		throw new Exception("Error ".$e->getMessage() ); 
	}							
}

function Insert_EstablecimientoWEB($conn, $CUIT, $ESTABLECI, $TIPOESTAB, $TIPONOMINA, $IDACTIVIDAD, $DESCRIPCIONESTAB, $USUALTA){
	try{		
		$ID = Existe_EstablecimientoWEB($ESTABLECI, $CUIT, true );
		if($ID > 0){			
			return $ID;
		}
			
		$SEQ_HEW_ID = ValorSql("SELECT hys.seq_hew_id.NEXTVAL FROM DUAL");		
		
		$params = array(":ID" => $SEQ_HEW_ID,
						":CUIT" => $CUIT,
						":ESTABLECI" => $ESTABLECI,
						":TIPOESTAB" => $TIPOESTAB,
						":TIPONOMINA" => $TIPONOMINA,
						":IDACTIVIDAD" => $IDACTIVIDAD,
						":DESCRIPCIONESTAB" => $DESCRIPCIONESTAB,
						":USUALTA" => $USUALTA);
		
		$sql = "INSERT INTO 
				HYS.HEW_ESTABLECIMIENTOWEB(
				EW_ID, 
				EW_CUIT, 
				EW_ESTABLECI, 
				EW_TIPOESTAB, 
				EW_TIPONOMINA, 
				EW_IDACTIVIDAD, 
				EW_DESCRIPCIONESTAB, 
				EW_USUALTA, 
				EW_FECHAALTA) ";
		
		$sql .= " VALUES(:ID, 
						:CUIT, 
						:ESTABLECI, 
						:TIPOESTAB, 
						:TIPONOMINA, 
						:IDACTIVIDAD, 
						:DESCRIPCIONESTAB, 
						:USUALTA, 
						SYSDATE) ";	//FALLA....
		
		DBExecSql($conn, $sql, $params);	
		
		return $SEQ_HEW_ID; 
	}
	catch (Exception $e) {	
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		throw new Exception("Error ".$e->getMessage() ); 
	}						
}

function Update_EstablecimientoWEB($conn, $CUIT, $ESTABLECI, $TIPOESTAB, $TIPONOMINA, $IDACTIVIDAD, $DESCRIPCIONESTAB, $USUALTA, $ID){

	try{		
		if($TIPOESTAB != 'O' and $TIPONOMINA != 'S' ) $TIPONOMINA = 'L';
		
		$params = array(":ID" => $ID,
						":CUIT" => $CUIT,
						":ESTABLECI" => $ESTABLECI,
						":TIPOESTAB" => $TIPOESTAB,
						":TIPONOMINA" => $TIPONOMINA,
						":IDACTIVIDAD" => $IDACTIVIDAD,
						":DESCRIPCIONESTAB" => $DESCRIPCIONESTAB,
						":USUALTA" => $USUALTA);
		
		$sql = "UPDATE HYS.HEW_ESTABLECIMIENTOWEB			
				SET
									EW_CUIT = :CUIT, 
									EW_ESTABLECI = :ESTABLECI, 
									EW_TIPOESTAB = :TIPOESTAB, 
									EW_TIPONOMINA = :TIPONOMINA, 
									EW_IDACTIVIDAD = :IDACTIVIDAD, 
									EW_DESCRIPCIONESTAB = :DESCRIPCIONESTAB, 			
									EW_USUMODIF = :USUALTA, 
									EW_FECHAMODIF = SYSDATE
								WHERE EW_ID = :ID ";		
				
		DBExecSql($conn, $sql, $params);	
		
		return true; 
	}
	catch (Exception $e) {		
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		throw new Exception("Error ".$e->getMessage() ); 
		return false;
	}						
}

function Insert_ResponsableNominaWEB($conn, $IDRELEVNOMINA,$TIPORESP,$NOMBRE,$APELLIDO,$CARGO,$CODAREA,$TELEFONO,$TIPOTELEFONO,$INTERNO,$PRINCIPAL,$OBSERVACIONES,$EMAIL,$TIPODOCUMENTO,$NUMERODOCUMENTO,$SEXO){
	try 
	{
		$params = array(":IDRELEVNOMINA" => $IDRELEVNOMINA,
						":TIPORESP" => $TIPORESP,
						":NOMBRE" => $NOMBRE,
						":APELLIDO" => $APELLIDO,
						":CARGO" => $CARGO,
						":CODAREA" => $CODAREA,
						":TELEFONO" => $TELEFONO,
						":TIPOTELEFONO" => $TIPOTELEFONO,
						":INTERNO" => $INTERNO,
						":PRINCIPAL" => $PRINCIPAL,
						":OBSERVACIONES" => $OBSERVACIONES,
						":EMAIL" => $EMAIL,
						":TIPODOCUMENTO" => $TIPODOCUMENTO,
						":NUMERODOCUMENTO" => $NUMERODOCUMENTO,
						":SEXO" => $SEXO);			
		
		$sql = "insert into HYS.HRW_RESPONSABLENOMINAWEB(
					RW_ID,RW_IDRELEVNOMINA,RW_TIPORESP, RW_NOMBRE,
					RW_APELLIDO,
					RW_CARGO,
					RW_CODAREA,
					RW_TELEFONO,
					RW_TIPOTELEFONO,
					RW_INTERNO,
					RW_PRINCIPAL,RW_OBSERVACIONES,
					RW_EMAIL,RW_TIPODOCUMENTO,RW_NUMERODOCUMENTO,RW_SEXO ) 
				VALUES(HYS.SEQ_HRW_IDRESPONSABLENOMINA.NEXTVAL, 
					:IDRELEVNOMINA,:TIPORESP, :NOMBRE,
					:APELLIDO,:CARGO,:CODAREA,:TELEFONO,
					:TIPOTELEFONO,:INTERNO,:PRINCIPAL,:OBSERVACIONES,
					:EMAIL,:TIPODOCUMENTO,:NUMERODOCUMENTO,:SEXO)";
		
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);			
		return true; 				
	}
	catch (Exception $e) {		
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		throw new Exception("Error ".$e->getMessage() ); 
		
	}						
}

function Update_ResponsableNominaWEB($conn, $IDRELEVNOMINA, $TIPORESP, $NOMBRE, $APELLIDO, $CARGO, $CODAREA, $TELEFONO,$TIPOTELEFONO,$INTERNO,$PRINCIPAL,$OBSERVACIONES,$EMAIL,$TIPODOCUMENTO,$NUMERODOCUMENTO,$SEXO, $IGUALARESP, $ID){
	try 
	{
		
		$params = array(":IDRELEVNOMINA" => $IDRELEVNOMINA,
						":TIPORESP" => $TIPORESP,
						":NOMBRE" => $NOMBRE,
						":APELLIDO" => $APELLIDO,
						":CARGO" => $CARGO,
						":CODAREA" => $CODAREA,
						":TELEFONO" => $TELEFONO,
						":TIPOTELEFONO" => $TIPOTELEFONO,
						":INTERNO" => $INTERNO,
						":PRINCIPAL" => $PRINCIPAL,
						":OBSERVACIONES" => $OBSERVACIONES,
						":EMAIL" => $EMAIL,
						":TIPODOCUMENTO" => $TIPODOCUMENTO,
						":NUMERODOCUMENTO" => $NUMERODOCUMENTO,
						":SEXO" => $SEXO,			
						":IGUALARESP" => $IGUALARESP,			
						":ID" => $ID);			
		
		$sql = "UPDATE HYS.HRW_RESPONSABLENOMINAWEB					
				SET
							RW_NOMBRE = :NOMBRE,
							RW_APELLIDO = :APELLIDO,
							RW_CARGO = :CARGO,
							RW_CODAREA = :CODAREA,
							RW_TELEFONO = :TELEFONO,
							RW_TIPOTELEFONO = :TIPOTELEFONO,
							RW_INTERNO = :INTERNO,
							RW_PRINCIPAL = :PRINCIPAL,
							RW_OBSERVACIONES = :OBSERVACIONES,
							RW_EMAIL = :EMAIL,
							RW_TIPODOCUMENTO = :TIPODOCUMENTO,
							RW_NUMERODOCUMENTO = :NUMERODOCUMENTO,
							RW_SEXO = :SEXO,
							RW_IGUALARESP = :IGUALARESP
				WHERE 	RW_IDRELEVNOMINA = :IDRELEVNOMINA
				  AND 	RW_ID = :ID
				  AND 	RW_TIPORESP = :TIPORESP ";

				  $text = " ";
				  foreach($params as $key=>$value){							
					$text .= "(".$key." => ".$value.") ";	
				 }
				 
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);			
		return true; 				
	}
	catch (Exception $e) {		
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		throw new Exception("Error ".$e->getMessage() ); 
		
	}						
}

function recursive($array){
	 EscribirLogTxt1("entro recursive", "array ".count($array));
	foreach($array as $key=>$value){							
		if( is_array($value) ) recursive($value);
			EscribirLogTxt1("recursive" , "clave ".$key.' = '.$value[$key]);
	}
}
 
function UpdateNominaPrimerosDatos($jsonPrimerosDatos, $debug = false){
		
	$jsonPrimerosDatos = urldecode($jsonPrimerosDatos);	
	$jsonPrimerosDatos = str_replace('"[','[',$jsonPrimerosDatos);	
	$jsonPrimerosDatos = str_replace(']"',']',$jsonPrimerosDatos);	

	if( !isset($jsonPrimerosDatos) ) return "<b style='color:red; font: Arial 14;' >No se encontraron Datos.<b>";
	if( trim($jsonPrimerosDatos) == '' ) return "<b style='color:red; font: Arial 14;' >Lista de Datos esta vacia.<b>";
				
	$arrayPrimerosDatos = json_decode($jsonPrimerosDatos, true);	
	//recursive($arrayPrimerosDatos);
	$stringShow = '';
	$stringLog = '';
	
	$rowPanel = '';
	$ID = 0;
	$IDRELEVNOMINA = 0;
	
	try{	
		global $conn;
		
		$ID = $arrayPrimerosDatos[0]['Establecimiento']['CODIGOEWID'];		
		$idEstableci = $arrayPrimerosDatos[0]['Establecimiento']['ESTABLECI'];
		
		$IDRELEVNOMINA =  Array_EstablecimientoWEB($conn, $arrayPrimerosDatos[0]['Establecimiento']); 
				
		if( isset($arrayPrimerosDatos[0]['ResponsableHYS'] )  ){ 												
			$ResponsableHYS = $arrayPrimerosDatos[0]['ResponsableHYS'];
			$Telefonos = $arrayPrimerosDatos[0]['ResponsableHYS']['Telefonos'];
			
			foreach($Telefonos as $key=>$RowTelefono){												
				Array_ResponsableNominaWEBTelefono($conn, $IDRELEVNOMINA, $ResponsableHYS, $RowTelefono, 'H'); 					
			}
		}						
				
		if( isset($arrayPrimerosDatos[0]['ResponsableEmpresa'] )  ){					
			Array_ResponsableNominaWEBRespEmpresa($conn, $IDRELEVNOMINA, $arrayPrimerosDatos[0]['ResponsableEmpresa'], 'R'); 
		}				
		
		if( isset($arrayPrimerosDatos[0]['Contacto'] )  ){ 									
			Array_ResponsableNominaWEBContacto($conn, $IDRELEVNOMINA, $arrayPrimerosDatos[0]['Contacto'], 'C'); 
		}		
		
		$EW_ID = $arrayPrimerosDatos[0]['Establecimiento']['CODIGOEWID'];		
		
		$TipoEstablecimiento = $arrayPrimerosDatos[0]['Establecimiento']['TipoEstablecimiento'];
		$TipoNomina = $arrayPrimerosDatos[0]['Establecimiento']['TipoNomina'];
		
		$EstadoNomina = 'C';
		if($TipoEstablecimiento != 'O' and $TipoNomina != 'S')
			$EstadoNomina = 'L';
		
		GrabarEstadoNomina($EW_ID, $EstadoNomina, false, $conn);		
		
		if($TipoEstablecimiento != 'O' or $TipoNomina != 'S'){

			$paramsBusca = array(":EW_ID" => $EW_ID);	

			$idCabecera = ValorSql(" SELECT   hys.hcw_cabeceranominaweb.cw_id
								  FROM   hys.hew_establecimientoweb 
								  INNER JOIN hys.hcw_cabeceranominaweb ON EW_ID = CW_IDESTABLECIMIENTOWEB
								  where ew_id = :EW_ID ", "", $paramsBusca);	
								  
			EliminarNominaWEBCompleta($conn, $idCabecera);
		}
		
		DBCommit($conn);							
		return true;
				
	}catch (Exception $e) {
		DBRollback($conn);		
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
		return false;
	}	
	
	if($debug) echo $stringShow;
	if($debug) echo "<p>".$stringLog;
	
	return true;
		
}

function Array_ResponsableNominaWEBContacto($conn, $IDRELEVNOMINA, $ResponsableArray,  $TIPORESP = '' ){
	
	$IDRELEVNOMINA = $IDRELEVNOMINA; 
	$TIPORESP = $TIPORESP; 
	//$CARGO = $ResponsableArray['RespCargo']; //NO TIENE CARGO
	$CARGO = '';
	
	$NOMBRE = utf8_decode($ResponsableArray['ContactoNombre']); 
	$APELLIDO = utf8_decode($ResponsableArray['ContactoApellido']); 
	$EMAIL = utf8_decode($ResponsableArray['ContactoEMail']); 
	$SEXO = ''; 	
	$TIPODOCUMENTO = ''; 
	$NUMERODOCUMENTO = ''; 
		
	$CODAREA = $ResponsableArray['ContactoCodArea']; 
	$TELEFONO = $ResponsableArray['ContactoTelefono']; 
	$TIPOTELEFONO = $ResponsableArray['ContactoTipoTelefono']; 
	$INTERNO = ''; 
	$PRINCIPAL = ''; 
	$OBSERVACIONES = ''; 
	$IGUALARESP = $ResponsableArray['ContactoIgualaResp']; 
	
	InsUpd_ResponsableNominaWEB($conn, $IDRELEVNOMINA, $TIPORESP, $NOMBRE, $APELLIDO, $CARGO, $CODAREA, $TELEFONO, $TIPOTELEFONO, $INTERNO, $PRINCIPAL, $OBSERVACIONES, $EMAIL, 	$TIPODOCUMENTO, $NUMERODOCUMENTO, $SEXO, $IGUALARESP);
	
}

function InsUpd_ResponsableNominaWEB($conn, $IDRELEVNOMINA, $TIPORESP, $NOMBRE, $APELLIDO, $CARGO, $CODAREA, $TELEFONO, $TIPOTELEFONO, $INTERNO, $PRINCIPAL, $OBSERVACIONES, $EMAIL, 	$TIPODOCUMENTO, $NUMERODOCUMENTO, $SEXO, $IGUALARESP, $IDTelefono = 0){
		
	if($TIPORESP == 'H'){
	
		$sqlValida = "  SELECT   NVL(MAX(RW_ID), 0) ID  FROM   hys.hrw_responsablenominaweb
					     WHERE   rw_idrelevnomina = :IDRELEVNOMINA
						   AND rw_tiporesp = :TIPORESP 
						   AND NVL(rw_tipotelefono, '') = :TIPOTELEFONO  ";
						
		$params = array(":IDRELEVNOMINA" => $IDRELEVNOMINA, 
							":TIPORESP" => $TIPORESP , 
							":TIPOTELEFONO" => $TIPOTELEFONO );
							
	}else{
		$sqlValida = "  SELECT   NVL(MAX(RW_ID), 0) ID  
						  FROM   hys.hrw_responsablenominaweb
						 WHERE   rw_idrelevnomina = :IDRELEVNOMINA
						   AND   rw_tiporesp = :TIPORESP ";
						
		$params = array(":IDRELEVNOMINA" => $IDRELEVNOMINA, ":TIPORESP" => $TIPORESP );
		
	}
						
	$ID = ValorSql($sqlValida, "", $params);		
		
	if($ID == 0){				
		return Insert_ResponsableNominaWEB($conn, $IDRELEVNOMINA, $TIPORESP, $NOMBRE, $APELLIDO, $CARGO, $CODAREA, $TELEFONO, $TIPOTELEFONO, $INTERNO, $PRINCIPAL, $OBSERVACIONES, $EMAIL, $TIPODOCUMENTO, $NUMERODOCUMENTO, $SEXO);	
	}else{				
		return Update_ResponsableNominaWEB($conn, $IDRELEVNOMINA, $TIPORESP, $NOMBRE, $APELLIDO, $CARGO, $CODAREA, $TELEFONO, $TIPOTELEFONO, $INTERNO, $PRINCIPAL, $OBSERVACIONES, $EMAIL, $TIPODOCUMENTO, $NUMERODOCUMENTO, $SEXO, $IGUALARESP, $ID);		
	}

}

function Array_ResponsableNominaWEBRespEmpresa($conn, $IDRELEVNOMINA, $ResponsableArray, $TIPORESP = ''){
	
	$IDRELEVNOMINA = $IDRELEVNOMINA; 	 
	//$CARGO = $ResponsableArray['RespCargo']; //NO se usa $CARGO en esta seccion
	$CARGO = '';
	
	$NOMBRE = utf8_decode($ResponsableArray['RespEmpNombre']); 
	$APELLIDO = utf8_decode($ResponsableArray['RespEmpApellido']);

	$EMAIL = utf8_decode($ResponsableArray['RespEmpEMail']); 
	$SEXO = $ResponsableArray['RespEmpTiposexo']; 	
	$TIPODOCUMENTO = $ResponsableArray['TipoDocRespEmpresa']; 
	$NUMERODOCUMENTO = $ResponsableArray['RespEmpNumDoc']; 
		
	$CODAREA = $ResponsableArray['RespEmpCodArea']; 
	$TELEFONO = $ResponsableArray['RespEmpTelefono']; 
	$TIPOTELEFONO = $ResponsableArray['ResptipoTelefono']; 
	$INTERNO = ''; 
	$PRINCIPAL = ''; 
	$OBSERVACIONES = ''; 
	$IGUALARESP = '';
	
	InsUpd_ResponsableNominaWEB($conn, $IDRELEVNOMINA, $TIPORESP, $NOMBRE, $APELLIDO, $CARGO, $CODAREA, $TELEFONO, $TIPOTELEFONO, $INTERNO, $PRINCIPAL, $OBSERVACIONES, $EMAIL, 	$TIPODOCUMENTO, $NUMERODOCUMENTO, $SEXO, $IGUALARESP);
	
}

function Array_ResponsableNominaWEBTelefono($conn, $IDRELEVNOMINA, $ResponsableArray, $TelefonoArray, $TIPORESP = ''){
	
	$IDRELEVNOMINA = $IDRELEVNOMINA; 	 
		
	$CARGO = $ResponsableArray['RespCargo']; 
			
	$NOMBRE = utf8_decode($ResponsableArray['RespNombre']); 
	$APELLIDO = utf8_decode($ResponsableArray['RespApellido']); 
	$EMAIL = utf8_decode($ResponsableArray['RespEMail']); 
	$SEXO = $ResponsableArray['RespTiposexo']; 		
	$TIPODOCUMENTO = $ResponsableArray['tipoDocRespHYS']; 
	$NUMERODOCUMENTO = $ResponsableArray['RespNumDoc']; 
		
	$IDTelefono = 0;
	if(isset($TelefonoArray['ID']))
		$IDTelefono = $TelefonoArray['ID']; 
	
	$CODAREA = $TelefonoArray['area']; 
	$TELEFONO = $TelefonoArray['numero']; 
	$TIPOTELEFONO = $TelefonoArray['tipoTelefono']; 
	$INTERNO = $TelefonoArray['interno']; 
	$PRINCIPAL = $TelefonoArray['principal']; 
	$OBSERVACIONES = $TelefonoArray['observaciones']; 
	$IGUALARESP = '';
	
	InsUpd_ResponsableNominaWEB($conn, $IDRELEVNOMINA, $TIPORESP, $NOMBRE, $APELLIDO, $CARGO, $CODAREA, $TELEFONO, $TIPOTELEFONO, $INTERNO, $PRINCIPAL, $OBSERVACIONES, $EMAIL, 	$TIPODOCUMENTO, $NUMERODOCUMENTO, $SEXO, $IGUALARESP, $IDTelefono);

}

function Array_EstablecimientoWEB($conn, $sections ){	

	$accion = ''; 
	$ID = 0;
		
	$CUIT = CuitExtractGuion( $sections['CUIT'] ); 
	$ESTABLECI = $sections['ESTABLECI']; 
	
	$TIPOESTAB = $sections['TipoEstablecimiento']; 
	
	$TIPONOMINA = 'N';
	$IDACTIVIDAD = '';
	$DESCRIPCIONESTAB = '';
	
	if( $TIPOESTAB == 'O' ){
		
		$TIPONOMINA = GetParametroArray($sections['TipoNomina'], ''); 
		
		if($TIPONOMINA == 'S'){
			$IDACTIVIDAD = GetParametroArray($sections['CODIGOCIUU'], ''); 
			//$DESCRIPCIONESTAB =  utf8_encode(GetParametroArray($sections['idTexActividad'], '') ); 			
			$DESCRIPCIONESTAB =  utf8_decode($sections['idTexActividad'] ); 			
		}
	}
	
	if(is_null($TIPONOMINA)) $TIPONOMINA = 'N';
	
	$USUALTA = $sections['USUARIO']; 
	$ID = Existe_EstablecimientoWEB($ESTABLECI, $CUIT );
	
	if($ID > 0) $accion = 'UPDATE';
	else $accion = 'INSERT';
	
	if($accion == 'INSERT'){			
		return Insert_EstablecimientoWEB($conn, $CUIT, $ESTABLECI, $TIPOESTAB, $TIPONOMINA, $IDACTIVIDAD, $DESCRIPCIONESTAB, $USUALTA);
	}
	
	if($accion ==  'UPDATE'){		
		//$ID = GetParametroArray($sections['CODIGOEWID'], ''); 		
		Update_EstablecimientoWEB($conn, $CUIT, $ESTABLECI, $TIPOESTAB, $TIPONOMINA, $IDACTIVIDAD, $DESCRIPCIONESTAB, $USUALTA, $ID);
		return $ID;
	}
}

function Existe_EstablecimientoWEB($idEstablecimiento, $CuitEmpresa, $filtraRechazo = false ){

	$CuitEmpresa = CuitExtractGuion($CuitEmpresa);
	$params = array(":NROESTABLECIVISTA" => $idEstablecimiento, ":CUIT" => $CuitEmpresa );
	
	$sql = "  SELECT   NVL ( EW_ID , 0) EXISTE
				FROM   hys.hew_establecimientoweb
			   WHERE   ew_cuit = :CUIT
   			     AND   ew_estableci = :NROESTABLECIVISTA
				 AND   TO_CHAR (EW_FECHAALTA, 'YYYY') = TO_CHAR (SYSDATE, 'YYYY')       
				 AND   UPPER ( NVL(ew_estado, 'X'))  <> 'R'
				 AND   ROWNUM = 1 ";
				 
	if($filtraRechazo)
		$sql .= " and NVL(ew_estado, 'X') <> 'R' ";
    
	$sql .= " ORDER BY   ew_fechaalta DESC ";
	
	try{		
		$result = ValorSql($sql, "", $params);		
		return $result;
		
	}catch (Exception $e){				
		return false;						
	}	
}
	
function GrabarFormularioNomina( $InsertNominaNuevadeAnterior = false){	
	try{
			
		$Establecimiento["CODIGOEWID"] = GetParametroDecode('hiddenCODIGOEWID');
		
		$Establecimiento["USUARIO"] = substr($_SESSION["usuario"], 0, 20);
		$Establecimiento["CUIT"] = GetParametroDecode('hiddenCUIT');
		$Establecimiento["ESTABLECI"] = GetParametroDecode('hiddenES_NROESTABLECI');
		$Establecimiento["CODIGOCIUU"] = GetParametroDecode('hiddenCODIGOCIUU');
		
		$Establecimiento["TipoEstablecimiento"] = GetParametroDecode('TipoEstablecimiento');
		$Establecimiento["TipoNomina"] = GetParametroDecode('TipoNomina', 'N');
		$Establecimiento["idTexActividad"] = GetParametroDecode('idTexActividad', 'X');
		
		$resultado["Establecimiento"] = $Establecimiento;

		if(GetParametroDecode('RespNumDoc', '') != '' and GetParametroDecode('RespNumDoc', '') != ''){
			$ResponsableHYS["tipoDocRespHYS"] = GetParametroDecode('tipoDocRespHYS', '');		
			$ResponsableHYS["RespNumDoc"] = GetParametroDecode('RespNumDoc', '');
			$ResponsableHYS["RespTiposexo"] = GetParametroDecode('RespTiposexo', '');
			$ResponsableHYS["RespNombre"] = GetParametroDecode('RespNombre', '');
			$ResponsableHYS["RespApellido"] = GetParametroDecode('RespApellido', '');
	
			$ResponsableHYS["RespCargo"] = GetParametroDecode('InsertNominaNuevadeAnterior', '');
			$ResponsableHYS["RespEMail"] = GetParametroDecode('RespEMail', '');
			
			$Telefonos = GetParametroDecode('hiddenArrayTelefonos', '');		
			$Telefonos = rawurlencode($Telefonos); //decodifico los caracteres de escape..								
			$ResponsableHYS["Telefonos"] = $Telefonos;
						
			$resultado["ResponsableHYS"] = $ResponsableHYS;
		}
		
		if(GetParametroDecode('RespEmpNombre', '') != '' and GetParametroDecode('RespEmpApellido', '') != ''){
			$ResponsableEmpresa["TipoDocRespEmpresa"] =  GetParametroDecode('TipoDocRespEmpresa', '');
			$ResponsableEmpresa["RespEmpNumDoc"] =  GetParametroDecode('RespEmpNumDoc', '');
			$ResponsableEmpresa["RespEmpTiposexo"] =  GetParametroDecode('RespEmpTiposexo', '');
			$ResponsableEmpresa["RespEmpNombre"] =  GetParametroDecode('RespEmpNombre', '');
			$ResponsableEmpresa["RespEmpApellido"] =  GetParametroDecode('RespEmpApellido', '');
			$ResponsableEmpresa["RespEmpCodArea"] =  GetParametroDecode('RespEmpCodArea', '');
			$ResponsableEmpresa["RespEmpTelefono"] =  GetParametroDecode('RespEmpTelefono', '');
			$ResponsableEmpresa["ResptipoTelefono"] =  GetParametroDecode('ResptipoTelefono', '');
			$ResponsableEmpresa["RespEmpEMail"] =  GetParametroDecode('RespEmpEMail', '');
			
			$ResponsableEmpresa["ID"] =  GetParametroDecode('hidden_R_RW_ID', '');
			$ResponsableEmpresa["IDRELEVNOMINA"] = GetParametroDecode('hidden_R_RW_IDRELEVNOMINA', '');
			
			$resultado["ResponsableEmpresa"] = $ResponsableEmpresa;		
		}
		
		if(GetParametroDecode('ContactoNombre', '') != '' and GetParametroDecode('ContactoApellido', '') != ''){
			$Contacto["ContactoNombre"] =  GetParametroDecode('ContactoNombre', '');
			$Contacto["ContactoApellido"] =  GetParametroDecode('ContactoApellido', '');
			$Contacto["ContactoCodArea"] =  GetParametroDecode('ContactoCodArea', '');
			$Contacto["ContactoTelefono"] =  GetParametroDecode('ContactoTelefono', '');
			$Contacto["ContactoTipoTelefono"] =  GetParametroDecode('ContactoTipoTelefono', '');
			$Contacto["ContactoEMail"] =  GetParametroDecode('ContactoEMail', '');
			$Contacto["ContactoIgualaResp"] =  GetParametroDecode('ContactoIgualaResp', '');
			
			$Contacto["ID"] = GetParametroDecode('hidden_C_RW_ID');
			$Contacto["IDRELEVNOMINA"] = GetParametroDecode('hidden_C_RW_IDRELEVNOMINA', '');
					
			$resultado["Contacto"] = $Contacto;
		}
		
		$resjson = $jsonresultado[] =  json_encode($resultado);
		$resjson = "[  ".$resjson."  ]";
		$ACCION = GetParametroDecode('hiddenACCION', '');
			
		if($ACCION == 'EDIT'){
			if ( !UpdateNominaPrimerosDatos($resjson, false) ){				
				echo "<script>
					 alert('ERROR actualizando... revise los datos. ' );			 
				</script>";
				return false;
			}
		}
		
		if($ACCION == 'INSERT'){
			if ( !UpdateNominaPrimerosDatos($resjson, false) ){			//ex SaveNominaPrimerosDatos
				echo "<script>
					 alert('Error insertando... revise los datos ' );			 
				</script>";
				return false;
			}
		}
		
		unset($_SESSION['NOMINAPERSONALEXPUESTO']);
		
		if($InsertNominaNuevadeAnterior){
			SetSessionVarNPE(GetParametroDecode('hiddenCODIGOEWID'), GetParametroDecode('hiddenCUIT'), 'NominaPersonalExpuesto');
			
					 ///window.location.assign('/FormulariosNomina');			 
			echo "<script>		
					 window.location.assign('/NominaPersonalExpuesto');			 
				</script>";
		}else{		
			$_SESSION['REDIRECT']['NOMINAPERSONALEXPUESTO'] = 'NominaPersonalExpuesto';

			// window.location.assign('/FormulariosNomina');			 
			echo "<script>		
					 window.location.assign('/NominaPersonalExpuesto');		
					 
				</script>";
		}
		return true;
			
	}catch (Exception $e){				
		 EscribirLogTxt1('Error grabando', implode(",", $e) )	;	
		 return false;
	}			
}

function SetSessionVarNPE($idEstablecimiento, $CUITEmpresa, $redirect = 'NominaPersonalExpuesto'){
	$_SESSION['NOMINAPERSONALEXPUESTO']['IDESTABLECIMIENTO'] = $idEstablecimiento;
	$_SESSION['NOMINAPERSONALEXPUESTO']['CUITEMPRESA'] = $CUITEmpresa;
	$_SESSION['NOMINAPERSONALEXPUESTO']['USUALTA'] = substr($_SESSION["usuario"], 0, 20);
	$_SESSION['REDIRECT']['NOMINAPERSONALEXPUESTO'] = $redirect;
}

/********* DATOS GRILLA NOMINA PERSONAL ***************/
function GetSelectPuestosNomina($idDiv, $styleDiv, $default='0'){
	/*retorna un combo HTML con todas los Puestos nomina para la busqueda por descripcion*/
		
	$sql = "SELECT PN_ID ID, PN_DESCRIPCION DESCRIPCION
				FROM HYS.HPN_PUESTONOMINA
			ORDER BY 2";
		//
	$result = CreateSelectHTML($sql, $idDiv, $styleDiv, $default);

	return $result; 		 
}

function GetJS_ArrayEmpleadosNombre($ArrayName, $EMPRESA, $NOMBRE=''){
	try{
		$sql = " SELECT DISTINCT tj_cuil ID, trim(tj_nombre) NOMBRE
					FROM   ctj_trabajador,
						   aem_empresa,
						   aco_contrato,
						   aes_establecimiento,
						   cre_relacionestablecimiento,
						   crl_relacionlaboral
				   WHERE   co_idempresa = em_id
					   AND rl_contrato = co_contrato
					   AND es_id = re_idestablecimiento
					   AND re_idrelacionlaboral = rl_id
					   AND tj_id = rl_idtrabajador
					   AND em_id = :EMPRESA
					   AND UPPER(TJ_NOMBRE) LIKE UPPER(:NOMBRE)
				ORDER BY  nombre";
					 
			
		$params = array(":EMPRESA"=>$EMPRESA, ":NOMBRE"=>'%'.$NOMBRE.'%' );
		$result = array();
		
		global $conn;
		@$stmt = DBExecSql($conn, $sql, $params);		
		while ($row = DBGetQuery($stmt)){	
		
			$ID = utf8_encode($row['ID']);
			$NOMBRE = utf8_encode($row['NOMBRE']);
				
			$row = array("value" => $ID, "label" => $NOMBRE);
			$result[] = $row;				
		}
		return json_encode($result);		
	}catch (Exception $e){						
		echo  $e->getMessage();
		return false;
	}
}

function GetJS_ArrayEmpleados($ArrayName, $EMPRESA, $opcion = 'CUIL'){
	/*retorna un array js con todos los Puestos nomina  para la busqueda por descripcion*/
	$default=0;
	
	$sql = " SELECT DISTINCT tj_cuil ID, tj_cuil DESCRIPCION, trim(tj_nombre) NOMBRE
				FROM   ctj_trabajador,
					   aem_empresa,
					   aco_contrato,
					   aes_establecimiento,
					   cre_relacionestablecimiento,
					   crl_relacionlaboral
			   WHERE   co_idempresa = em_id
				   AND rl_contrato = co_contrato
				   AND es_id = re_idestablecimiento
				   AND re_idrelacionlaboral = rl_id
				   AND tj_id = rl_idtrabajador
				   AND em_id = :EMPRESA
			ORDER BY   tj_cuil";
				 
	$fields['value'] = "ID";
	
	if($opcion == 'CUIL')
		$fields['label'] = "DESCRIPCION";
	if($opcion == 'NOMBRE')
		$fields['label'] = "NOMBRE";
		
	$params = array(":EMPRESA"=>$EMPRESA);
	//127212
	$result = CreateArrayJSUI($sql, $ArrayName, $fields, $params);
	
	return $result; 		 
}

function GetJS_ArrayPuestosNomina($ArrayName){
	/*retorna un array js con todos los Puestos nomina  para la busqueda por descripcion*/
	$default=0;
	
	$sql = "SELECT PN_ID ID, PN_DESCRIPCION DESCRIPCION
				FROM HYS.HPN_PUESTONOMINA
			ORDER BY 2";
			 
	$fields['value'] = "ID";
	$fields['label'] = "DESCRIPCION";
		
	$result = CreateArrayJSUI($sql, $ArrayName, $fields);	
	return $result; 		 
}

//----------------------
function BuscarPuestoTrab($id){
	$params = array(":id" => $id);	
	$sql = "  select dw_puestotrab from  hys.hdw_detallenominaweb where dw_id = :id ";					 
	try{
		global $conn;
		$result = ValorSql($sql, "", $params);				
		if($result == '') $result = 0;		
		return $result;
	}catch (Exception $e){						
		echo  $e->getMessage();
		return false;
	}
}
//----------------------
function BuscarTrabajador($CONTRATO, $CUIT, $CUILEMPRESA){

	$params = array(":CONTRATO" => $CONTRATO, ":CUIT" => $CUIT, ":CUILEMPRESA" => $CUILEMPRESA);
	
	$sql = "  SELECT DISTINCT 
					TJ_ID AS ID,
					TJ_CUIL AS CUIL,
					TJ_NOMBRE AS NOMBRE,
					RL_FECHAINGRESO FECHA_INGRESO,
					-- RL_FECHARECEPCION FECHA_INI,
					
					RL_SECTOR SECTOR,
					
					(SELECT distinct dw_puestotrab
						FROM HYS.HDW_DETALLENOMINAWEB
						WHERE dw_id = (SELECT MAX (dw_id)
												FROM HYS.HDW_DETALLENOMINAWEB
												WHERE dw_cuil = TJ_CUIL 
												AND dw_fechabaja IS NULL)) PUESTO

				FROM CTJ_TRABAJADOR,
					 AEM_EMPRESA,
					 ACO_CONTRATO,
					 CRL_RELACIONLABORAL,
					 AES_ESTABLECIMIENTO
			   WHERE     CO_IDEMPRESA = EM_ID			   
					 AND EM_CUIT = :CUILEMPRESA
					 AND RL_CONTRATO = CO_CONTRATO
					 AND TJ_ID = RL_IDTRABAJADOR
					 AND ES_CONTRATO = CO_CONTRATO
					 AND TJ_CUIL = :CUIT
					 AND CO_CONTRATO = :CONTRATO ";
					 
	global $conn;
	try{
		@$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){		
			return $row;		
		}
		
		$row = array("ID" => '0',
					"CUIL" => '',
					"NOMBRE" => '',
					"FECHA_INGRESO" => '',
					"FECHA_INI" => '',
					"SECTOR" => '',
					"PUESTO" => '');
		return $row;		
		
	}catch (Exception $e){				
		//RetronaXML($e->getMessage());						
		echo  $e->getMessage();
	}	
}

function GetJS_ArrayESOPsoloActivos($ArrayName, $codActividad = 0){
	try{
		/*retorna un array js con todos los ESOP para la busqueda por descripcion*/		
		$default='0';	
		if($codActividad > 0) $default=$codActividad;	
		
		$sql = ObtenerDatosESOPsoloActivos($default);					
		$result = CreateArrayJScript($sql, $ArrayName, 'CODIGO');			
		return $result; 		 
		
	}catch (Exception $e){				
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		return false;		 
	}	
}

/**************************************** ****************************************/
function Validar_NominaWebAnual($idEstablecimiento = 0){
	
	$params = array(":idEstablecimiento" => $idEstablecimiento);
	
	$sql = " SELECT   NVL(CW_ID, 0) AS RESULTADO
			  FROM   hys.hcw_cabeceranominaweb
			 WHERE   cw_idestablecimientoweb = :idEstablecimiento
				 AND TO_CHAR (cw_fechaalta, 'YYYY') = TO_CHAR (SYSDATE, 'YYYY') ";
    				   
	try{		
		$result = ValorSql($sql, "", $params);				
		if($result == '') $result = 0;		
		return $result;
		
	}catch (Exception $e){				
		return 0;						
	}
	
}

function GrabarRegistroNomina($idRow, $idEstablecimiento, $cuil, $nombre, $fechaingreso, $fechainiexpo, $sectortrab, $puestotrab, $arrayRiesgos){

	global $conn;
	try{
		$usualta = substr($_SESSION["usuario"], 0, 20);		
		
		$idcabeceranomina = Validar_NominaWebAnual($idEstablecimiento);
		$cantidad = 0;
		$idrelevasociadoconriesgo = ''; //CN_ID de la tabla HYS.HCN_CABECERANOMINA - Se carga cuando la nómina es importada y aprobada
		$idrelevasociadosinriesgo = ''; //ID de la tabla ART.PSR_SINRIESGO

		GrabarEstadoNomina($idEstablecimiento, 'C', false, $conn);
		
		if($idcabeceranomina == 0 or $idRow == 0){
			$idcabeceranomina = Insert_CabeceraNominaWEB($conn, $idEstablecimiento, $cantidad, $usualta, 
				$idrelevasociadoconriesgo, $idrelevasociadosinriesgo);
				
		}else{
			$idcabeceranomina = $idRow;
		}

		$sqlValidaItem = "SELECT   NVL(DW_ID, 0)  
							FROM   hys.hdw_detallenominaweb 
						   WHERE   dw_id = :idcabeceranomina     
						     AND   dw_cuil = :cuil ";
							 
		$paramsItem = array("idcabeceranomina" => $idcabeceranomina, "cuil" => $cuil);
		$itemValido = ValorSql($sqlValidaItem, "", $paramsItem);		

		if($itemValido == 0){			
			$iddetallenomina = Insert_DetalleNominaWEB($conn, $idcabeceranomina, $cuil, $nombre, $fechaingreso, $sectortrab, $puestotrab, $usualta, $fechainiexpo);
		}else{
			$iddetallenomina = $itemValido;
			Update_DetalleNominaWEB($conn, $iddetallenomina, $nombre, $fechaingreso, $fechainiexpo, $sectortrab, $puestotrab, $usualta);
		}
		
		$NotDeleteIn = '';
		
		$DarrayRiesgos = json_decode($arrayRiesgos, true);
		if($arrayRiesgos != ''){
			
			foreach($DarrayRiesgos as $clave=>$idriesgoESOP ){
			
				$sqlValidoRiesgo = "SELECT  NVL(RG_ID, 0)  
									FROM   PRG_RIESGOS  
									WHERE   DECODE (rg_sufijoesop, '', rg_esop, rg_esop || ' ' || rg_sufijoesop) = :ESOP ";			
									
				$paramsItem = array(":ESOP" => $idriesgoESOP);
				$idRiesgo = ValorSql($sqlValidoRiesgo, "", $paramsItem);		
				
				if($idRiesgo > 0){
					
					if($NotDeleteIn != '') $NotDeleteIn .= ', ';
					$NotDeleteIn .= $idRiesgo;
					
					//VALIDO QUE NO ESTE YA INGRESADO EN LA TABLA
					$sqlValidoRiesgo = "SELECT   NVL (rt_id, 0)   
										FROM   hys.hrt_riestrabweb 
										WHERE   rt_iddetallenomina = :iddetallenomina  
										AND rt_idriesgo = :idriesgo ";
										
					$paramsItem = array("iddetallenomina" => $iddetallenomina, "idriesgo" => $idRiesgo);
					$itemRiesgoValido = ValorSql($sqlValidoRiesgo, "", $paramsItem);		
					
					if($itemRiesgoValido == 0 or $itemRiesgoValido == ''){
						Insert_RiesTraWEB($conn, $iddetallenomina, $idRiesgo, $usualta);
					}
				}
			}			
		}		
		//ELIMINA TODOS LOS QUE SE DESELECCIONARON....
		DeleteNotIn_RiesTraWEB($conn, $NotDeleteIn, $iddetallenomina);				
		DBCommit($conn);		
		return true;
	}
	catch (Exception $e) {		
		DBRollback($conn);		
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		throw new Exception("Error ".$e->getMessage() ); 
		return false;
	}		
}

function Update_DetalleNominaWEB($conn, $id, $nombre, $fechaingreso, $fechainiexpo, $sectortrab, $puestotrab, $usumodif){
	try{
		
		$params = array(":id" => $id,						
						":nombre" => $nombre,		
						":fechaingreso" => $fechaingreso,
						":fechainiexpo" => $fechainiexpo,
						":sectortrab" => utf8_decode($sectortrab),						
						":usumodif" => $usumodif);
		
		$sql = "UPDATE hys.hdw_detallenominaweb 
				SET 				  				  
				  dw_nombre = :nombre,
				  dw_fechaingreso = :fechaingreso,
				  dw_fechainiexpo = :fechainiexpo,
				  dw_sectortrab = :sectortrab,				  
				  dw_fechamodif = sysdate,
				  dw_usumodif = :usumodif ";
		
		if($puestotrab >= 0){
			$params[":puestotrab"] = $puestotrab;
			$sql .= ", dw_puestotrab = :puestotrab ";
		}
		
		$sql .= " WHERE dw_id =  :id ";		
							
		DBExecSql($conn, $sql, $params);	
				
		return $id; 
	}
	catch (Exception $e) {		
		throw new Exception("Error Update_DetalleNominaWEB ".$e->getMessage()." SQL = ".$sql ); 
		return false;
	}						
}

function Insert_DetalleNominaWEB($conn, $idcabeceranomina, $cuil, $nombre, $fechaingreso, $sectortrab, $puestotrab, $usualta, $fechainiexpo = ''){
	try{
		
		$SEQ_HDW_ID = ValorSql("SELECT hys.SEQ_HDW_IDWEB.NEXTVAL FROM DUAL");
		
		$params = array(":id" => $SEQ_HDW_ID,
						":idcabeceranomina" => $idcabeceranomina,
						":cuil" => $cuil,
						":nombre" => $nombre,		
						":fechaingreso" => $fechaingreso,						
						":sectortrab" => $sectortrab,
						":puestotrab" => $puestotrab,
						":usualta" => $usualta	);
		
		if($fechainiexpo == '')
			$params[':fechainiexpo'] = '';
		else
			$params[':fechainiexpo'] = $fechainiexpo;
		
		$sql = " INSERT INTO hys.hdw_detallenominaweb (dw_id,
                                      dw_idcabeceranomina,
                                      dw_cuil,
                                      dw_nombre,
                                      dw_fechaingreso, 
									  dw_fechainiexpo,
									  dw_sectortrab,
                                      dw_puestotrab,
                                      dw_usualta)";
									  
		$sql .= " VALUES   (:id,
							:idcabeceranomina,
							:cuil,
							:nombre,
							:fechaingreso,
							:fechainiexpo,
							:sectortrab,
							:puestotrab,
							:usualta)";		
							
		DBExecSql($conn, $sql, $params);	
				
		return $SEQ_HDW_ID; 
	}
	catch (Exception $e) {		
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		throw new Exception("Error Insert_DetalleNominaWEB ".$e->getMessage() ); 
		return false;
	}						
}

function Update_CantidadCabeceraNominaWEB($conn, $idcabeceranomina, $cantidad, $usualta){
	
	$sql  = "UPDATE   hys.hcw_cabeceranominaweb
			   SET   cw_cantidad = :CANTIDAD
			   WHERE   cw_id = :ID";

	try{				
		
		$params = array(":ID" => $idcabeceranomina, ":CANTIDAD" => $cantidad);
		DBExecSql($conn, $sql, $params);	
		
	}catch (Exception $e) {		
		throw new Exception("Error ".$e->getMessage() ); 		
		return false;
	}								
}

function Insert_CabeceraNominaWEB($conn, $idestablecimientoweb, $cantidad, $usualta, $idrelevasociadoconriesgo='', $idrelevasociadosinriesgo=''){
	try{		
		$id = Validar_NominaWebAnual($idestablecimientoweb);
		if( $id > 0 ){			
			return $id;
		}
		
		$SEQ_HCW_ID = ValorSql("SELECT hys.SEQ_HCW_IDWEB.NEXTVAL FROM DUAL");
		
		$params = array(":id" => $SEQ_HCW_ID,
						":idestablecimientoweb" => $idestablecimientoweb,
						":cantidad" => $cantidad,
						":usualta" => $usualta);
		
		/* 	parametros eliminados ":idrelevasociadoconriesgo" => $idrelevasociadoconriesgo ,":idrelevasociadosinriesgo" => $idrelevasociadosinriesgo
			
			Se eleimino el campo cw_idrelevasociadosinriesgo de la tabla hys.hcw_cabeceranominaweb 
				tambien se elimino el campo cw_idrelevasociadoconriesgo */
		
		$sql = "INSERT INTO hys.hcw_cabeceranominaweb (cw_id,
                                       cw_idestablecimientoweb,
                                       cw_cantidad,
                                       cw_usualta,
                                       cw_fechaalta)
				  VALUES   (:id,
							:idestablecimientoweb,
							:cantidad,
							:usualta,
							SYSDATE)";		
		
		DBExecSql($conn, $sql, $params);	
		
		return $SEQ_HCW_ID; 
	}
	catch (Exception $e) {		
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		throw new Exception("Error ".$e->getMessage() ); 		
		return false;
	}						
}

function DeleteNotIn_RiesTraWEB($conn, $riesgo, $iddetallenomina){

	try{				
		if($riesgo == '') {
			$params = array(":iddetallenomina" => $iddetallenomina);
			$sql = " DELETE hys.hrt_riestrabweb 					
					  WHERE rt_iddetallenomina = :iddetallenomina ";		
		}else{
			$params = array(":iddetallenomina" => $iddetallenomina);
			$sql = " DELETE hys.hrt_riestrabweb 					
					  WHERE rt_iddetallenomina = :iddetallenomina 
						AND rt_idriesgo NOT IN (".$riesgo.") ";		
		}	
				
		DBExecSql($conn, $sql, $params);			
		return true; 
	}
	catch (Exception $e) {		
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		throw new Exception("Error DeleteNotIn_RiesTraWEB ".$e->getMessage() ); 		
		return false;
	}						
}

function Delete_RiesTraWEB($conn, $riesgo, $iddetallenomina, $usubaja){
	try{		
		$params = array(":iddetallenomina" => $iddetallenomina, 
						":riesgo" => $riesgo, 
						":usubaja" => $usubaja);
		
		$sql = "UPDATE hys.hrt_riestrabweb 
					SET rt_usubaja = :usubaja,
						rt_fechabaja = sysdate
					WHERE rt_iddetallenomina = :iddetallenomina 
					  AND rt_idriesgo = :riesgo";		
							
		DBExecSql($conn, $sql, $params);	
		
		return true; 
	}
	catch (Exception $e) {		
		throw new Exception("Error ".$e->getMessage() ); 		
		return false;
	}						
}

function Insert_RiesTraWEB($conn, $iddetallenomina, $idriesgo, $usualta){

	try{		
		$SEQ_HRT_ID = ValorSql("SELECT hys.SEQ_HRT_IDWEB.NEXTVAL FROM DUAL");
		
		$params = array(":id" => $SEQ_HRT_ID,
						":iddetallenomina" => $iddetallenomina,
						":idriesgo" => $idriesgo,
						":usualta" => $usualta,
						);
		
		$sql = "INSERT INTO hys.hrt_riestrabweb (rt_id,
                                 rt_iddetallenomina,
                                 rt_idriesgo,
                                 rt_usualta)
					VALUES   (:id,
							:iddetallenomina,
							:idriesgo,
							:usualta)";		
							
		DBExecSql($conn, $sql, $params);	
		
		return $SEQ_HRT_ID; 
	}
	catch (Exception $e) {		
		throw new Exception("Error ".$e->getMessage() ); 		
		return false;
	}						
}

function EliminarNominaWEBCompleta($conn, $idCabecera){
	try{		
		//Eliminar todos los riesgos asignados 
		$sqlDeleteRiesgos = " DELETE   hys.hrt_riestrabweb  WHERE rt_iddetallenomina IN ( SELECT DW_ID FROM   hys.hdw_detallenominaweb where DW_IDCABECERANOMINA = :idCabecera)";
		$paramsItem = array(":idCabecera" => $idCabecera);
		DBExecSql($conn, $sqlDeleteRiesgos, $paramsItem);	
		
		//Eliminar Detalle seleccionado
		$sqlDeleteDetalle = " DELETE   hys.hdw_detallenominaweb  WHERE   DW_IDCABECERANOMINA = :idCabecera";
		$paramsItem = array(":idCabecera" => $idCabecera);
		DBExecSql($conn, $sqlDeleteDetalle, $paramsItem);	
		
		return true;
		
	}catch (Exception $e) {		
		DBRollback($conn);		
		throw new Exception("Error ".$e->getMessage() ); 		
		return false;
	}								
}

function EliminarNominaWEB($idNomina){
	try{
		global $conn;		
		
		//Busco el id de cabecera para el detalle a eliminar
		$sqlidCabecera = " SELECT   dw_idcabeceranomina    FROM   hys.hdw_detallenominaweb   WHERE   dw_id = :idNomina ";			
		$paramsItem = array(":idNomina" => $idNomina);
		$idCabecera = ValorSql($sqlidCabecera, "", $paramsItem);		
		
		//Si queda solo un registro elimina la cabecera
		$sqlidCabeceraCount = " SELECT   COUNT (dw_idcabeceranomina)   FROM   hys.hdw_detallenominaweb   WHERE   dw_idcabeceranomina = :idCabecera GROUP BY   dw_idcabeceranomina";
		$paramsItem = array(":idCabecera" => $idCabecera);
		$idCabeceraCount = ValorSql($sqlidCabeceraCount, "", $paramsItem);		
		
		//Eliminar todos los riesgos asignados 
		$sqlDeleteRiesgos = " DELETE   hys.hrt_riestrabweb  WHERE   rt_iddetallenomina = :idNomina";
		$paramsItem = array(":idNomina" => $idNomina);
		DBExecSql($conn, $sqlDeleteRiesgos, $paramsItem);	

		//Eliminar Detalle seleccionado
		$sqlDeleteDetalle = " DELETE   hys.hdw_detallenominaweb  WHERE   dw_id = :idNomina";
		$paramsItem = array(":idNomina" => $idNomina);
		DBExecSql($conn, $sqlDeleteDetalle, $paramsItem);	
		
		//Eliminar cabecera si solo queda un item
		if($idCabeceraCount <= 1){
			$sqlDeleteCabecera = " DELETE   hys.hcw_cabeceranominaweb  WHERE  cw_id = :idCabecera";
			$paramsItem = array(":idCabecera" => $idCabecera);
			DBExecSql($conn, $sqlDeleteCabecera, $paramsItem);	
		}
				
		DBCommit($conn);
		return true;
		
	}catch (Exception $e) {		
		DBRollback($conn);		
		throw new Exception("Error ".$e->getMessage() ); 		
		return false;
	}								
}

function NominaWEBRechazoMotivo($idNomina){
	/*busca el motivo de rechazo de una nomina web*/
	try{
		global $conn;
		
		$params = array(":IDNOMINA" => $idNomina);	
		
		$sql = "SELECT   r.mr_descripcion, e.ew_observacionesrechazo
				  FROM   hys.hew_establecimientoweb e
		    INNER JOIN 	 hys.hmr_motivorechazonomina r ON e.ew_idmotivorechazo = r.mr_id
				 WHERE   e.ew_id = :IDNOMINA";
		

		@$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){	
		
			$desc = utf8_encode($row['MR_DESCRIPCION']);
			$obser = utf8_encode($row['EW_OBSERVACIONESRECHAZO']);
				
			$row1 = array("MR_DESCRIPCION" => $desc, 
							"EW_OBSERVACIONESRECHAZO" =>   $obser);
							
			return json_encode($row1);		
		}
				
		return  json_encode(array("MR_DESCRIPCION" => "SIN DATOS", "EW_OBSERVACIONESRECHAZO" => "SIN DATOS" ));	
		
	}catch (Exception $e) {		
		throw new Exception("Error ".$e->getMessage() ); 
	}	
}			

function Existe_NominaWebAnualEnProceso($idEstablecimiento, $CuitEmpresa){
	
	$CuitEmpresa = CuitExtractGuion($CuitEmpresa);
	$params = array(":NROESTABLECI" => $idEstablecimiento, ":CUIT" => $CuitEmpresa );
	
	/* verifica si existe una nomina web para el establecimiento seleccionado y que no este rechazada */
	$sql = "SELECT NVL(COUNT (ew_id), 0)  EXISTE 
			FROM HYS.HEW_ESTABLECIMIENTOWEB C 			
			INNER JOIN hys.hcw_cabeceranominaweb ON EW_ID = CW_IDESTABLECIMIENTOWEB
			INNER JOIN hys.hdw_detallenominaweb ON CW_ID = DW_IDCABECERANOMINA  			
			WHERE   C.EW_ESTABLECI = :NROESTABLECI
			AND EW_CUIT = :CUIT
			AND nvl(ew_idmotivorechazo, 0) = 0
			AND TO_CHAR (EW_FECHAALTA, 'YYYY') = TO_CHAR (SYSDATE, 'YYYY') 
			AND c.ew_fechabaja IS NULL ";
    				   
	try{		
		$result = ValorSql($sql, "", $params);		
		return $result;
		
	}catch (Exception $e){				
		return false;						
	}	
}

function Get_NominaWEB_AnnoActual($idEstablecimiento, $CuitEmpresa){
	
	//$CuitEmpresa = CuitExtractGuion($CuitEmpresa);
	$params = array(":NROESTABLECI" => $idEstablecimiento, ":CUIT" => $CuitEmpresa );	
	$sql = "  SELECT EW_ID, EW_TIPONOMINA
			    FROM HYS.HEW_ESTABLECIMIENTOWEB
			   WHERE EW_CUIT = :CUIT     
				 AND EW_ESTABLECI = :NROESTABLECI
				 AND TO_CHAR (EW_FECHAALTA, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy')
				 AND UPPER ( NVL(EW_ESTADO, 'X'))  <> 'R'
				 AND ROWNUM = 1 ";
			   
	try{		
		global $conn;
		@$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){		
			return $row;		
		}
		
		return $result;
		
	}catch (Exception $e){				
		return false;						
	}
}

function Get_IDRELEV_AnnoActual($idEstablecimiento, $CuitEmpresa){
	
	$CuitEmpresa = CuitExtractGuion($CuitEmpresa);
	$params = array(":NROESTABLECI" => $idEstablecimiento, ":CUIT" => $CuitEmpresa );
	
	$sql = " SELECT nvl(HYS_RARWEB.get_idpresentacionactual( :CUIT, :NROESTABLECI), 0) annoanterior from dual ";
			   
	try{		
		$result = ValorSql($sql, "", $params);		
		return $result;
		
	}catch (Exception $e){				
		return false;						
	}
}

function Get_IDRELEV_AnnoAnterior($idEstablecimiento, $CuitEmpresa){
	
	$CuitEmpresa = CuitExtractGuion($CuitEmpresa);
	$params = array(":NROESTABLECI" => $idEstablecimiento, ":CUIT" => $CuitEmpresa );
	
	$sql = " SELECT nvl(HYS_RARWEB.get_idpresentacionanterior( :CUIT, :NROESTABLECI), 0) annoanterior from dual ";
			   
	try{		
		$result = ValorSql($sql, "", $params);		
		return $result;
		
	}catch (Exception $e){				
		return false;						
	}
}

function Existe_NominaWebAnnoAnterior($idEstablecimiento, $CuitEmpresa){
	
	$CuitEmpresa = CuitExtractGuion($CuitEmpresa);
	$params = array(":NROESTABLECI" => $idEstablecimiento, ":CUIT" => $CuitEmpresa );
	
	$sql = " SELECT nvl(HYS_RARWEB.get_idpresentacionanterior( :CUIT, :NROESTABLECI), 0) annoanterior from dual ";
			   
	try{		
		$result = ValorSql($sql, "", $params);		
		return ($result > 0);
		
	}catch (Exception $e){				
		return false;						
	}
}

function obtener_NominaAnnoAnterior(){
	
	$sql = " SELECT   DISTINCT
         d.rt_cuil CUIL,
         d.rt_nombre NOMBRE,
         rl_fechaingreso FECHAINGRESO,
         d.rt_fechainiexpo FECHAINIEXPO,
         rl_sector SECTOR,
		 
         (SELECT   DISTINCT dw_puestotrab
                       FROM   hys.hdw_detallenominaweb
                      WHERE   dw_fechaalta = (SELECT   MAX (dw_fechaalta)
                                                FROM   hys.hdw_detallenominaweb
                                               WHERE   dw_cuil = c.cn_cuit
                                                   AND dw_fechabaja IS NULL)) PUESTO,
         rl_tarea PUESTODESC,
		 
         (SELECT   listagg (rg_esop || ' ' || rg_sufijoesop, ',') WITHIN GROUP (ORDER BY rg_esop, rg_sufijoesop)
            FROM   art.prg_riesgos, art.prt_riestrab
           WHERE   rg_id = rt_idrg
               AND rg_fechabaja IS NULL
               AND rt_idcabeceranomina = c.cn_id
               AND rt_cuil = d.rt_cuil)
           ESOP
		   
		  FROM  hys.hcn_cabeceranomina c
	INNER JOIN	prt_riestrab d	ON c.cn_id = d.rt_idcabeceranomina
	INNER JOIN	ctj_trabajador t	ON t.tj_cuil = rt_cuil
	INNER JOIN	crl_relacionlaboral r	ON t.tj_id = r.rl_idtrabajador

	WHERE  c.cn_cuit = :cuit
                 AND c.cn_estableci = :estableci
      
     AND c.cn_idestado NOT IN (3, 6)
     AND TO_CHAR (c.cn_fecharelevamiento, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') - 1
     AND NOT EXISTS (SELECT   sr_fecha fecha, sr_id idrelev
                       FROM   art.psr_sinriesgo
                      WHERE   sr_cuit = c.cn_cuit
                          AND sr_estableci = c.cn_estableci
                          AND TO_CHAR (sr_fecha, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') - 1
                          AND sr_fecha > cn_fecharelevamiento)		
			ORDER BY   rt_nombre ";
			
		return $sql;
}

function Insert_NominaActualdeNominaAnterior($idEstablecimiento, $cuitEmpresa, $usualta){
	
	$cuitEmpresa = CuitExtractGuion($cuitEmpresa);
	$sql = obtener_NominaAnnoAnterior();

	try{		
		global $conn;	
		$paramsBusca = array(":IDESTABWEB" => $idEstablecimiento,
							":CUIT" => $cuitEmpresa);	
							
		$IDestableciWeb = ValorSql("SELECT  NVL(ew_id, 0) 
									  FROM  hys.hew_establecimientoweb  
									 WHERE  ew_estableci = :IDESTABWEB
									   AND  ew_cuit = :CUIT 
									   AND nvl(ew_idmotivorechazo, 0) = 0 ", "", $paramsBusca);	
	
		$cantidad  = 0;
		$lista_resultados = '';
		
		if($IDestableciWeb == 0 OR $IDestableciWeb == '' ){
		
			$rowEstableci = BuscarDetalleEstableci($idEstablecimiento, $cuitEmpresa);			
			$CUIT = $cuitEmpresa; 
			$ESTABLECI = $idEstablecimiento; 
			$TIPOESTAB = 'O';
			$TIPONOMINA = 'S'; 
			$IDACTIVIDAD = $rowEstableci['ES_IDACTIVIDAD'];
			$DESCRIPCIONESTAB = $rowEstableci['AC_DESCRIPCION'];
			$USUALTA = $usualta;
				
			$IDestableciWeb = Insert_EstablecimientoWEB($conn, $CUIT, $ESTABLECI, $TIPOESTAB, $TIPONOMINA, $IDACTIVIDAD, $DESCRIPCIONESTAB, $USUALTA);
		}

		$idcabeceranomina = Insert_CabeceraNominaWEB($conn, $IDestableciWeb, $cantidad, $usualta);			
		$params = array(":cuit" => $cuitEmpresa, ":estableci" => $idEstablecimiento);		
		$stmt = DBExecSql($conn, $sql, $params);	
   
		while ($row = DBGetQuery($stmt)){
		
			$cuilTrabajador = $row["CUIL"];
			$nombre = $row["NOMBRE"];
			$fechaingreso = $row["FECHAINGRESO"];
			$fechainiexpo = $row["FECHAINIEXPO"];
			$sectortrab = $row["SECTOR"];
			$puestotrab = $row["PUESTO"];
			
			$resultValida = Valida_TrabajadorEnOtraNomina($cuilTrabajador, $cuitEmpresa, $idEstablecimiento);
			
			if ($resultValida == 0){				
				Insert_DetalleNominaWEB($conn, $idcabeceranomina, $cuilTrabajador, $nombre, $fechaingreso, $sectortrab, $puestotrab, $usualta);
				$cantidad ++;
				
			}else{
				
				$showtext = ' - <b>'.$nombre.'</b> <i>cuil ('.$cuilTrabajador.')</i> '; 
				
				switch ($resultValida){
					case 1: $lista_resultados .= $showtext.' fue presentado en una nomina web <p>'; break;
					case 2: $lista_resultados .= $showtext.' esta declarado en una nomina ya aprobada <p>'; break;
					case 3: $lista_resultados .= $showtext.' ya esta ingresado en una nomina web <p>'; break;
				}				
			}
		}		
		
		Update_CantidadCabeceraNominaWEB($conn, $idcabeceranomina, $cantidad, $usualta);		
		
		SetSessionVarNPE($IDestableciWeb, $cuitEmpresa, 'NominaPersonalExpuesto');
		
		DBCommit($conn);				
		return $lista_resultados;		
		
	}catch (Exception $e) {		
		DBRollback($conn);				
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		return RetronaXML($e->getMessage());						
	}
	
}

function Valida_TrabajadorRepetidoNomina($cuilTrabajador, $cuitEmpresa, $establecimiento){

	$params = array(":CUIL" => $cuilTrabajador, 
						":CUIT" => $cuitEmpresa, 
						":ESTABLECI" => $establecimiento );
						
	//valida si un trabajador fue ingresado a otra nomina WEB de la empresa
	$sqlValida = " SELECT   NVL (COUNT ( * ), 0) existe
						FROM  hys.hdw_detallenominaweb hdw
						INNER JOIN hys.hcw_cabeceranominaweb hcw ON hcw.cw_id = hdw.dw_idcabeceranomina
						INNER JOIN hys.hew_establecimientoweb hew ON hew.ew_id = hcw.cw_idestablecimientoweb
						WHERE   hdw.dw_cuil = :CUIL						
						 AND hew.ew_cuit = :CUIT
						 AND hew.ew_estableci = :ESTABLECI			
						 
						 AND UPPER ( NVL(hew.ew_estado, 'X')) <> 'R'          
						 AND TO_CHAR (hcw.cw_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ";			
						
	$existeTrabajador = ValorSql($sqlValida, "", $params);		
	
	if($existeTrabajador > 0){
		return 1;
	}
	
	return 0;
	
}

function Valida_ExistenTrabajadoresAsignados( $id ){

	try{
		$params = array(":id" => $id );

		//valida si hay trabajadores asignados a un establecimiento
		$sqlValida = " SELECT   NVL (COUNT ( * ), 0) existe	
						FROM  hys.hdw_detallenominaweb hdw
						INNER JOIN  hys.hcw_cabeceranominaweb hcw  ON hcw.cw_id = hdw.dw_idcabeceranomina
						INNER JOIN  hys.hew_establecimientoweb hew  ON hew.ew_id = hcw.cw_idestablecimientoweb
						where hew.ew_id = :id ";
						
		$existeTrabajador = ValorSql($sqlValida, "", $params);
		
		if($existeTrabajador > 0){
			return $existeTrabajador;
		}
	
		return 0;

	}catch (Exception $e) {				
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		return RetronaXML($e->getMessage());						
	}	
       
}

function Valida_TrabajadorEnOtraNomina($cuilTrabajador, $cuitEmpresa, $establecimiento){
	/*
		1 - ingresado en otra nomina web
		2 - ingresado en una nomina ya aprobada
		3 - repetido en la nomina actual
	*/
	$params = array(":CUIL" => $cuilTrabajador, 
						":CUIT" => $cuitEmpresa,
						":ESTABLECI" => $establecimiento );

	//valida si un trabajador fue ingresado a otra nomina WEB de la empresa
	$sqlValida = " SELECT   NVL (COUNT ( * ), 0) existe
						FROM  hys.hdw_detallenominaweb hdw
						INNER JOIN hys.hcw_cabeceranominaweb hcw ON hcw.cw_id = hdw.dw_idcabeceranomina
						INNER JOIN hys.hew_establecimientoweb hew ON hew.ew_id = hcw.cw_idestablecimientoweb
						
						WHERE   hdw.dw_cuil = :CUIL						
						AND hew.ew_cuit = :CUIT						 
						AND hew.ew_estableci <> :ESTABLECI 
						 
						 AND   UPPER ( NVL(ew_estado, 'X')) <> 'R'          
						 AND TO_CHAR (hcw.cw_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy')      ";			
							 
						
	$existeTrabajador = ValorSql($sqlValida, "", $params);		
	
	if($existeTrabajador > 0){
		return 1;
	}
	
	//valida si un trabajador fue ingresado a una nomina ya aprovada para este año en algun establecimiento de la empresa
	$sqlValida = " SELECT   NVL (COUNT ( * ), 0) existe
					  FROM   prt_riestrab
					 WHERE   rt_cuit = :CUIT
						 AND rt_cuil = :CUIL
						 AND rt_estableci <> :ESTABLECI			
						 
						 AND TO_CHAR (rt_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ";			
							
	$existeTrabajador = ValorSql($sqlValida, "", $params);		
	
	if($existeTrabajador > 0){
		return 2;
	}
	
	if (Valida_TrabajadorRepetidoNomina($cuilTrabajador, $cuitEmpresa, $establecimiento) > 0 ){
		return 3;
	}
	
	return 0;
	
}


function BuscarDetalleESOP($RiesgoESOP){
/* BUSCA LOS DATOS PARA MOSTRAR LOS DETALLES DEL RIESGO */

	$params = array(":IDRIESGOESOP" => $RiesgoESOP);
	
	$sql = " SELECT DR_RIESGOESOP RIESGOESOP, 
				r.rg_descripcion  AGENTERIESGO,   
				DECODE(upper(TRIM(dr_Grupo)), upper('TyO'), 'Agentes Termohidromètricos y Otros',
                                             upper('Q'), 'Agentes Químicos',
                                             upper('F'), 'Agentes Físicos',
                                             upper('B'), 'Agentes Biológicos',
                                             'Sin Grupo')  GRUPO,       
				DR_CRITERIO1 CRITERIO1,    
				DR_CRITERIO2 CRITERIO2,    
				DR_OBSERVACIONES OBSERVACIONES,
				DR_LIMIT LIMIT,        
				DR_ACGIH  ACGIH
				FROM hys.hdr_detalleriesgoesop
				INNER JOIN   art.prg_riesgos  r ON dr_riesgoesop = DECODE (rg_sufijoesop, '', r.rg_esop, r.rg_esop || ' ' || rg_sufijoesop)
				WHERE DR_ID = :IDRIESGOESOP ";
					
	try{
		global $conn;
		
		$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){		
			$row = array("RIESGOESOP" => $row['RIESGOESOP'],
					"AGENTERIESGO" => utf8_encode($row['AGENTERIESGO']),
					"GRUPO" => utf8_encode($row['GRUPO']),
					"CRITERIO1" => utf8_encode($row['CRITERIO1']),
					"CRITERIO2" => utf8_encode($row['CRITERIO2']),
					"OBSERVACIONES" => utf8_encode($row['OBSERVACIONES']),
					"LIMIT" => utf8_encode($row['LIMIT']),
					"ACGIH" => utf8_encode($row['ACGIH'])  );
				
			return $row;		
			// return utf8_encode($row);		
		}
		
		$row = array("RIESGOESOP" => '0',
					"AGENTERIESGO" => '',
					"GRUPO" => '',
					"CRITERIO1" => '',
					"CRITERIO2" => '',
					"OBSERVACIONES" => '',
					"LIMIT" => '',
					"ACGIH" => '');
					
		return $row;		
		
	}catch (Exception $e){				
		RetronaXML($e->getMessage());						
	}
	
}

function BuscarDetalleEstableci($idEstablecimiento, $cuit){
/* BUSCA DATOS DE UN ESTABLECIMIENTO */
	$params = array(":IDESTABLECIMIENTO" => $idEstablecimiento, ":CUIT" => $cuit );
	
	$sql = " SELECT   ES_NOMBRE, ES_IDACTIVIDAD, AC_DESCRIPCION
			   FROM       afi.aes_establecimiento
			  INNER JOIN  afi.aco_contrato  ON es_contrato = co_contrato
			  INNER JOIN  afi.aem_empresa  ON em_id = co_idempresa
			  INNER JOIN cac_actividad cac ON cac.ac_id = ES_IDACTIVIDAD
			  WHERE   em_cuit = :CUIT
				 AND es_nroestableci = :IDESTABLECIMIENTO ";
					
	try{
		global $conn;
		
		$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){		
			return $row;		
		}
		
		$row = array("ES_NOMBRE" => '0',
					"ES_IDACTIVIDAD" => '',
					"AC_DESCRIPCION" => '');
					
		return $row;		
		
	}catch (Exception $e){				
		RetronaXML($e->getMessage());						
	}
	
}

function descargarXLS($archivo, $downloadfilename = null) {

    if (file_exists($archivo)) {
        $downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $downloadfilename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($archivo));

        ob_clean();
        flush();
        readfile($archivo);
        exit;
    }

}

function validarSessionServer($isValid) {
	if (!$isValid) {
		echo "<script src=\"/js/functions.js?rnd=20130802\" type=\"text/javascript\"></script>";
		echo "<script>if (!isTopLevel(window)) alert('Se sesión ha expirado.\\nPor favor logueese nuevamente para continuar.');</script>";
		echo "<span id=\"sesionInvalidData\">".$_SERVER["REMOTE_ADDR"]." (".gethostbyaddr($_SERVER['REMOTE_ADDR']).")</span><br />";
		echo utf8_encode("<span id=\"sesionInvalidMsg\">Ha expirado la sesión, por favor ingrese nuevamente sus datos.");
		exit;
	}
}

function SetResponsableDefault($CODIGOEWID){
	try{	
		
		extract(GetDatosNominaWebResponsable($CODIGOEWID, 'H')  , EXTR_PREFIX_ALL, "DNWR_H");	
		extract(GetDatosNominaWebResponsable($CODIGOEWID, 'R')  , EXTR_PREFIX_ALL, "DNWR_R");	
		extract(GetDatosNominaWebResponsable($CODIGOEWID, 'C')  , EXTR_PREFIX_ALL, "DNWR_C");		

		$_SESSION['Responsable']['CODIGOEWID'] = $CODIGOEWID;

		$_SESSION['Responsable']['DNWR_H_RW_CARGO'] = $DNWR_H_RW_CARGO;
		$_SESSION['Responsable']['DNWR_H_RW_TIPODOCUMENTO'] = $DNWR_H_RW_TIPODOCUMENTO;
		$_SESSION['Responsable']['DNWR_H_RW_NUMERODOCUMENTO'] = $DNWR_H_RW_NUMERODOCUMENTO;
		$_SESSION['Responsable']['DNWR_H_RW_SEXO'] = $DNWR_H_RW_SEXO;
		$_SESSION['Responsable']['DNWR_H_RW_NOMBRE'] = $DNWR_H_RW_NOMBRE;
		$_SESSION['Responsable']['DNWR_H_RW_APELLIDO'] = $DNWR_H_RW_APELLIDO;
		$_SESSION['Responsable']['DNWR_H_CARGO_DESCRIPCION'] = $DNWR_H_CARGO_DESCRIPCION;
		$_SESSION['Responsable']['DNWR_H_RW_EMAIL'] = $DNWR_H_RW_EMAIL;

		$_SESSION['Responsable']['DNWR_R_RW_ID'] = $DNWR_R_RW_ID;
		$_SESSION['Responsable']['DNWR_R_RW_IDRELEVNOMINA'] = $DNWR_R_RW_IDRELEVNOMINA;
		$_SESSION['Responsable']['DNWR_R_RW_TIPODOCUMENTO'] = $DNWR_R_RW_TIPODOCUMENTO;
		$_SESSION['Responsable']['DNWR_R_RW_NUMERODOCUMENTO'] = $DNWR_R_RW_NUMERODOCUMENTO;
		$_SESSION['Responsable']['DNWR_R_RW_SEXO'] = $DNWR_R_RW_SEXO;
		$_SESSION['Responsable']['DNWR_R_RW_NOMBRE'] = $DNWR_R_RW_NOMBRE;
		$_SESSION['Responsable']['DNWR_R_RW_APELLIDO'] = $DNWR_R_RW_APELLIDO;
		$_SESSION['Responsable']['DNWR_R_RW_CODAREA'] = $DNWR_R_RW_CODAREA;
		$_SESSION['Responsable']['DNWR_R_RW_TELEFONO'] = $DNWR_R_RW_TELEFONO;
		$_SESSION['Responsable']['DNWR_R_RW_TIPOTELEFONO'] = $DNWR_R_RW_TIPOTELEFONO;
		$_SESSION['Responsable']['DNWR_R_RW_EMAIL'] = $DNWR_R_RW_EMAIL;

		$_SESSION['Responsable']['DNWR_C_RW_ID'] = $DNWR_C_RW_ID;
		$_SESSION['Responsable']['DNWR_C_RW_IDRELEVNOMINA'] = $DNWR_C_RW_IDRELEVNOMINA;
		$_SESSION['Responsable']['DNWR_C_RW_NOMBRE'] = $DNWR_C_RW_NOMBRE;
		$_SESSION['Responsable']['DNWR_C_RW_APELLIDO'] = $DNWR_C_RW_APELLIDO;
		$_SESSION['Responsable']['DNWR_C_RW_CODAREA'] = $DNWR_C_RW_CODAREA;
		$_SESSION['Responsable']['DNWR_C_RW_TELEFONO'] = $DNWR_C_RW_TELEFONO;
		$_SESSION['Responsable']['DNWR_C_RW_TIPOTELEFONO'] = $DNWR_C_RW_TIPOTELEFONO;
		$_SESSION['Responsable']['DNWR_C_RW_EMAIL'] = $DNWR_C_RW_EMAIL;
		$_SESSION['Responsable']['DNWR_C_RW_IGUALARESP'] = $DNWR_C_RW_IGUALARESP;
		
		return 'OK';
	}catch (Exception $e){							
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		return $e->getMessage();
	}
}