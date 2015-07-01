<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridAjax.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");
@session_start(); 

function getGridSeleccionaEstablecimieto($contrato, $idEstablecimiento, $EstablecimientoNombre, $calle, $CPostal, $Localidad, $Provincia){	
	validarSessionServer(isset($_SESSION["isCliente"]));
	
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = "1";
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];		
		
	$showProcessMsg = false;
	
	$params = array(":contrato" => $contrato);
	
	$FilterEstab = false;
	if($idEstablecimiento !=''){
		$params[":idEstablecimiento"] = $idEstablecimiento;
		$FilterEstab = true;	
	}
	
	$FilterEstabNombre = false;
	if($EstablecimientoNombre !=''){
		$params[":EstablecimientoNombre"] = '%'.$EstablecimientoNombre.'%';
		$FilterEstabNombre = true;	
	}
	
	$FilterCalle = false;
	if($calle !=''){
		$params[":calle"] = '%'.$calle.'%';
		$FilterCalle = true;	
	}
	
	$FilterCPostal = false;
	if($CPostal != ''){
		$params[":CPostal"] = '%'.$CPostal.'%';
		$FilterCPostal = true;	
	}
	
	$FilterLocalidad = false;
	if($Localidad != ''){
		$params[":Localidad"] = '%'.$Localidad.'%';
		$FilterLocalidad = true;	
	}
	
	$FilterProvincia = false;
	if($Provincia > 0){
		$params[":Provincia"] = $Provincia;
		$FilterProvincia = true;	
	}
	
	$sql = ObtenerEstablecimientos($FilterEstab, $FilterEstabNombre, $FilterEstabNombre, $FilterCalle, $FilterCPostal, $FilterLocalidad, $FilterProvincia);	
 
	$sql = ReemplazaCorchetesQRY($sql);	
	
	$grilla = new gridAjax(10, 10);
	
	$grilla->SetArrayColTitle( SetarrayCols() );
		
	$grilla->addColumn(new columnAjax("Número", 104, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new columnAjax("Nombre"));
	$grilla->addColumn(new columnAjax("Domicilio"));	
	
	//-----------------------------------------
	/*
	$urlRedirect = "/modules/usuarios_registrados/clientes/RAR/redirect.php?pageid=126";	
	*/
	$ColumnButton = new columnAjax("Nueva Presentacion", 0, true, false, -1, "btnPdf", "", "", -1, true, -1, "Nueva Presentacion");
	
	$arrayLinks = array("CARGADA"=>"BTNRGRLOK", "NOGENERADA"=>"BTNRGRL", "PRESENTADA"=>"btnPdf");			
	$ColumnButton->setArrayLinks( $arrayLinks );
	$ColumnButton->setFunctionAjax('redirectNuevaPresentacion');			
	$grilla->addColumn($ColumnButton);	
	//-----------------------------------------	
	$arrayBotones = array('0'=>"NO PRESENTADA", 'KEYBTNPDF'=>"btnPdf");
	$ColAnnAnterior = new columnAjax("Año Anterior", 0, true, false, -1, "btnPdf", "", "gridColAlignCenter", -1, true, -1, "", false, "", "button", -1);
	$ColAnnAnterior->setFunctionAjax('imprimeListadoAnnoAnterior');		
	$ColAnnAnterior->setArrayLinks( $arrayBotones );
	$grilla->addColumn($ColAnnAnterior);		
	
	//-----------------------------------------		
	$arrayBotonesAA = array('0'=>"NO PRESENTADA", 'RECHAZADA_LINK_'=>"RECHAZADA", 'KEYBTNPDF'=>"btnPdf");
	$ColAnnActual = new columnAjax("Año Actual", 0, true, false, -1, "btnX", "", "gridColAlignCenter", -1, true, -1, "", false, "", "button", -1);
	$ColAnnActual->setFunctionAjax('AsignaAccion_NominaActual');		
	$ColAnnActual->setArrayLinks( $arrayBotonesAA );
	$grilla->addColumn($ColAnnActual);
	
	//-----------------------------------------	
		
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setShowProcessMessage(true);
	$grilla->setShowTotalRegistros(true);
	$grilla->setSql($sql);
	$grilla->setTableStyle("GridTableCiiu");
	$grilla->setUseTmpIframe(false);
	//funciones gridajax	
	$grilla->setFuncionAjaxJS("BuscarGrillaEstablecimientos");		
	$grilla->setFuncionAjaxOrderByJS("BuscarGrillaEstabOrderBy");		
	
	$grilla->Draw();	
		
}

function SetarrayCols(){
		
	$col2rows = new columnAjax(utf8_encode("Número"), 70);
	$col2rows->setRowspan(2);	
	$col2rows->setCanSort(true);	
	$arrayRows[] = ($col2rows);
	
	$col2rows = new columnAjax("Nombre", 120);
	$col2rows->setRowspan(2);	
	$col2rows->setCanSort(true);	
	$arrayRows[] = ($col2rows);
	
	$col2rows = new columnAjax("Domicilio",200);
	$col2rows->setRowspan(2);	
	$col2rows->setCanSort(true);	
	$arrayRows[] = ($col2rows);
	
	$col2rows = new columnAjax(utf8_encode("Estado Nueva Presentación"), 100);
	$col2rows->setRowspan(2);		
	$arrayRows[] = ($col2rows);
		
	$col2cols = new columnAjax("Presentado en la ART");
	$col2cols->setColspan(2);
	$arrayRows[] = ($col2cols);	
	
	$arrayCols[] = $arrayRows;	
	unset($arrayRows);	
	$arrayRows[] = (new columnAjax( utf8_encode("Año Anterior") ));	
	$arrayRows[] = (new columnAjax( utf8_encode("Año Actual") ));	
	
	$arrayCols[] = $arrayRows;
	
	return $arrayCols;
}

function ObtenerEstablecimientos($FilterEstab=false, $FilterEstabNombre=false, $FilterEstabNombre=false, 
	$FilterCalle=false, $FilterCPostal=false, $FilterLocalidad=false, $FilterProvincia=false){

        $ReturnSQL = " SELECT 
                       ES_NROESTABLECI [ES_NROESTABLECI],
                        ES_NOMBRE [ES_NOMBRE],
                        ART.UTILES.ARMAR_DOMICILIO (ES_CALLE,
                                                      ES_NUMERO,
                                                      ES_PISO,
                                                      ES_DEPARTAMENTO,
                                                      NULL,
                                                      NULL)
                       || ' '
                       || ART.UTILES.ARMAR_LOCALIDAD (ES_CPOSTAL,
                                                      NULL,
                                                      ES_LOCALIDAD,
                                                      ES_PROVINCIA) [DOMICILIO],";     													  
        
		$ReturnSQL .= "  ".obtenerColumna_NuevaPresentacion()." [NUEVAPRESENTACION], ";		
		$ReturnSQL .= "  (".obtener_AnnoAnterior().") [ANTERIOR],  ";		
		$ReturnSQL .= "  (".obtener_AnnoActual().") [ACTUAL]  ";
		
		$ReturnSQL .= " FROM AFI.AES_ESTABLECIMIENTO
						INNER JOIN AFI.ACO_CONTRATO ON ES_CONTRATO = CO_CONTRATO
						INNER JOIN AFI.AEM_EMPRESA ON EM_ID = CO_IDEMPRESA
												
						WHERE   ES_CONTRATO = :CONTRATO						
						AND ES_FECHABAJA IS NULL
						AND NOT EXISTS (SELECT   1
							FROM HYS.PEV_ESTABEVENTUAL
							WHERE EV_ID = ES_IDESTABEVENTUAL
							AND EV_ASIGNABLE = 'N') ";
		
		if($FilterEstab)
			$ReturnSQL .= " AND es_nroestableci = :idEstablecimiento ";
				
		if($FilterEstabNombre)
			$ReturnSQL .= " AND upper(es_nombre) like upper(:EstablecimientoNombre) ";
			
		if($FilterCalle)
			$ReturnSQL .= " AND upper(es_calle) like upper(:calle) ";
			
		if($FilterCPostal)
			$ReturnSQL .= " AND upper(es_cpostal) like upper(:cpostal) ";
			
		if($FilterLocalidad)
			$ReturnSQL .= " AND upper(es_localidad) like upper(:Localidad) ";
			
		if($FilterProvincia)
			$ReturnSQL .= " AND es_provincia  = :Provincia ";
		
		$ReturnSQL .= " ORDER BY   es_nroestableci ";
			
	return $ReturnSQL;
}

function obtener_AnnoActual(){

	return " SELECT   
		CASE	
			WHEN  NVL(hys_rarweb.get_idpresentacionactual (em_cuit, es_nroestableci), 0)= 0	
		THEN
			CASE
				WHEN NVL (hys_rarweb.get_idpresentacionactual (em_cuit, es_nroestableci), 0) = 0
				THEN
					CASE
						WHEN NVL (hys_rarweb.get_idestablecirechazo (em_cuit, es_nroestableci), 0) = 0	THEN	'0'
					ELSE
						' ''' || 'RECHAZADA_LINK_' || ''' ' || ',' || NVL (hys_rarweb.get_idestablecirechazo (em_cuit, es_nroestableci), 0) || ',' || ' ''' || 'ACTUAL' || ''' ' 
					END
				ELSE
					' ''' || 'KEYBTNPDF' || ''' ' || ','	|| NVL (hys_rarweb.get_idpresentacionactual (em_cuit, es_nroestableci), 0) || ',' || ' ''' || 'ACTUAL' || ''' ' 
				END
			ELSE
				' ''' || 'KEYBTNPDF'	|| ''' '	|| ',' || NVL (hys_rarweb.get_idpresentacionactual (em_cuit, es_nroestableci), 0) || ',' || ' ''' || 'ACTUAL' || ''' ' 
			END
		FROM   DUAL ";
	
}

function obtener_AnnoAnterior(){
	return " SELECT  				 
				CASE  
				WHEN NVL(HYS_RARWEB.get_idpresentacionanterior(EM_CUIT, ES_NROESTABLECI), 0) = 0 THEN 
					'0'				
				ELSE 
					' ''' || 'KEYBTNPDF' || ''' ' 					
					|| ',' || 									
					NVL(HYS_RARWEB.get_idpresentacionanterior(EM_CUIT, ES_NROESTABLECI), 0)
					|| ',' || 									
					' ''' || 'ANTERIOR' || ''' ' 					

				END
				 ANTERIOR 
			FROM DUAL ";
}

function obtenerIDEstableciWeb(){
			
	return " 
SELECT   MAX (NVL (ew_id, 0)) id	
FROM   hys.hew_establecimientoweb
WHERE   ew_estableci = ES_NROESTABLECI
AND ew_cuit = EM_CUIT
AND TO_CHAR (ew_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ";

}

function obtenerColumna_NuevaPresentacion(){

	return " '126, ' || ES_ID 
		|| ', ' || ES_NROESTABLECI 
		|| ', '  ||
			NVL (hys_rarweb.get_idpresentacionactual (em_cuit, es_nroestableci), 0)			
		|| ', ' || 	
			( SELECT NVL( MAX(EW_ID), 0) ID
				FROM HYS.HEW_ESTABLECIMIENTOWEB E 
				WHERE E.EW_CUIT = EM_CUIT 
				AND E.EW_ESTABLECI = ES_NROESTABLECI 		
				AND UPPER (NVL(ew_estado, 'X')) <> 'R'
				AND E.EW_FECHABAJA IS NULL 
				AND TO_CHAR (E.EW_FECHAALTA, 'YYYY') = TO_CHAR (SYSDATE, 'YYYY')  )			
		|| ', ''' || 		
			(	SELECT 
					CASE  
					WHEN NVL( HYS_RARWEB.get_idnuevapresentacion(EM_CUIT, ES_NROESTABLECI) , 0) = 0 THEN 'NOGENERADA'				
					ELSE 'CARGADA' END
				ESTADO 
				FROM DUAL)  || ''' ' ";
}

function obtenerColumna_NominaAnnoActualAnterior($actualYear = true){
	$strMenosUno = '';
	if($actualYear)
		$strMenosUno = ' -1 ';
			
	$returnSQL = "NVL( (SELECT CASE (
		SELECT EW_ESTADO estado 
			FROM HYS.HEW_ESTABLECIMIENTOWEB 
			INNER JOIN HYS.HMR_MOTIVORECHAZONOMINA ON ew_idmotivorechazo = mr_id 
			INNER JOIN HYS.HCW_CABECERANOMINAWEB ON ew_id = cw_idestablecimientoweb 
			WHERE CW_IDRELEVASOCIADOCONRIESGO = r.sr_id) 
			WHEN 'A' THEN 'APROBADO' 
			WHEN 'R' THEN 'RECHAZADO' 
			WHEN 'C' THEN 'CARGADO' 
			WHEN NULL THEN 'SIN NOMINA' 
			ELSE 'NO ES NOMINA WEB' 
			END Estado 
			FROM art.psr_sinriesgo r 
			WHERE     r.sr_cuit = em_cuit 
			AND r.sr_estableci = es_nroestableci 
			AND TO_CHAR (r.SR_FECHA, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$strMenosUno." 
			AND NOT EXISTS (
				SELECT CN_FECHARELEVAMIENTO fecha, cn_id idrelev 
				FROM hys.hcn_cabeceranomina 
				WHERE cn_cuit = em_cuit 
				AND cn_estableci = es_nroestableci 
				AND CN_IDESTADO NOT IN (3, 6) 
				AND TO_CHAR (CN_FECHARELEVAMIENTO, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$strMenosUno." 
				AND sr_fecha < CN_FECHARELEVAMIENTO)  ";
				
	$returnSQL .= " UNION ";

//		--con riesgo 

	$returnSQL .= " SELECT CASE (
			SELECT EW_ESTADO estado 
			FROM HYS.HEW_ESTABLECIMIENTOWEB 
			INNER JOIN HYS.HMR_MOTIVORECHAZONOMINA ON ew_idmotivorechazo = mr_id 
			INNER JOIN HYS.HCW_CABECERANOMINAWEB ON ew_id = cw_idestablecimientoweb 
			WHERE CW_IDRELEVASOCIADOCONRIESGO = c.cn_id) 
			WHEN 'A' THEN 'APROBADO' 
			WHEN 'R' THEN 'RECHAZADO' 
			WHEN 'C' THEN 'CARGADO' 
			WHEN NULL THEN 'SIN NOMINA' 
			ELSE 'NO ES NOMINA WEB' 
			END Estado 
			FROM hys.hcn_cabeceranomina c 
			WHERE c.cn_cuit = em_cuit 
			AND c.cn_estableci = es_nroestableci 
			AND c.CN_IDESTADO NOT IN (3, 6) 
			AND TO_CHAR (c.CN_FECHARELEVAMIENTO, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$strMenosUno." 
			AND NOT EXISTS (
				SELECT sr_fecha fecha, sr_id idrelev 
				FROM art.psr_sinriesgo 
				WHERE sr_cuit = em_cuit 
				AND sr_estableci = es_nroestableci 
				AND TO_CHAR (SR_FECHA, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$strMenosUno." 
				AND sr_fecha > CN_FECHARELEVAMIENTO)  )";

	$returnSQL .= "  , 'NO PRESENTADA' ) ";
	return $returnSQL;
}


