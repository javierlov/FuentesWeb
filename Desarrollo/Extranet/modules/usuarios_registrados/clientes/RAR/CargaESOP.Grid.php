<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridAjax.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");
@session_start(); 

function getGridDatosESOP($pagina = -1, $codactividad = 0, $codigo=0, $descripcion = '', $inFiltroRiesgos = '' ) {	
		
	validarSessionServer(isset($_SESSION["isCliente"]));	
		
	if($pagina == -1 ) 
		$pagina = 1;
		
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = '';
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];		
		
	$showProcessMsg = false;
	$result = '';	

	if($inFiltroRiesgos != ''){
		$inFiltroRiesgos = StringToArray($inFiltroRiesgos);
		foreach($inFiltroRiesgos as $key=>$value ){
			if($result != '' )$result .= ", ";
				$result .= " '".$value."' ";
		}
	}
	
	$sql = ObtenerDatosESOP($descripcion, $codigo, $result);

	$params = array();	
	if( isset($_SESSION["IDACTIVIDAD"]) and $_SESSION["IDACTIVIDAD"] != ''  )
		$params[":codactividad"] = $_SESSION["IDACTIVIDAD"];
	else
		$params[":codactividad"] = 0;
	
		
	if( $codigo != '' ){	$params[":ESOP"] = '%'.$codigo.'%';	}
	else if($descripcion != ''){ $params[":descripcion"] = '%'.$descripcion.'%';  		}
	
	$grilla = new gridAjax(10, 10);				
/*
$title; $width = 0; $visible = true; 
$deletedRow = false; $colHint = -1; 
$buttonClass = ""; $actionButton = ""; 
$cellClass = ""; $maxChars = -1; 
$useStyleForTitle = true; $numCellHide = -1; 
$titleHint = ""; $mostrarEspera = false;	$msgEspera = ""; 
$inputType = "button"; $colChecked = -1; $colButtonClass = -1;
*/
	$grilla->addColumn(new columnAjax("Seleccionar", 90, true, false, -1, "XbtnSeleccionar", "", "gridColAlignCenter", -1, false, -1, "", false, "", "checkbox", 2));
	$grilla->addColumn(new columnAjax("Codigo", 75, true, false, -1, "", "", "gridColAlignCenter", -1, false, -1, "", false, ""));
	$grilla->addColumn(new columnAjax("Descripcion", 300, true, false, -1, "", "", "gridColAlignLeft", -1, false));
	$grilla->addColumn(new columnAjax("Grupo", 150, true, false, -1, "", "", "gridColAlignLeft", -1, false));
	//-----------------------------
	$columnDetalle = new columnAjax("Detalle", 0, true, false, -1, "btnDetalleRiesgo", "", "gridColAlignLeft", -1, false);
	$columnDetalle->setFunctionAjax("showDetalleRiesgo");
	$grilla->addColumn($columnDetalle);
	//-----------------------------			
		
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setShowProcessMessage(true);
	$grilla->setShowTotalRegistros(true);
	$grilla->setSql($sql);
	$grilla->setTableStyle("GridTableCiiu");
	$grilla->setUseTmpIframe(false);
	//funciones gridajax	
	$grilla->setFuncionAjaxJS("BuscarGridDatosESOP");		
			
	$arrayCodigos = Array_DatosESOPsoloActivos($_SESSION["IDACTIVIDAD"]);
	$grilla->setBackGroundColumns($arrayCodigos, '#3F4C6B', 1);		
	$grilla->Draw();			
}

function Array_DatosESOPsoloActivos($codactividad = 0){
	try{		
		global $conn;
		$params = array();	
		if(is_null($codactividad) or $codactividad == '') $codactividad = 0;	
		
		$sql = ObtenerDatosESOPsoloActivos($codactividad);		
		$result = array();		
		$stmt = DBExecSql($conn, $sql, $params);	
    				   
		while ($row = DBGetQuery($stmt)){					
			$result[] = $row['CODIGO'];		
		}
		
		return $result;
		
	}catch (Exception $e){				
		return array();						
	}	
}

function ObtenerDatosESOPsoloActivos($codactividad = 0){                         

	$ReturnSQL = " SELECT  DECODE (rg_sufijoesop, '', r.rg_esop, r.rg_esop || ' ' || rg_sufijoesop) CODIGO
					  FROM     hys.hec_esopporciiu ec
				INNER JOIN	prg_riesgos r ON ec.ec_idesop = r.rg_id
					 WHERE   ec_idactividad = ".$codactividad."
						 AND r.rg_fechabaja IS NULL
						 AND ec.ec_fechabaja IS NULL ";
						 
	return $ReturnSQL;
}

function ObtenerDatosESOP($descripcion = '', $codigo = '', $inFiltroRiesgos = ''){

	$ReturnSQL = " SELECT   	Seleccionar ¿Seleccionar?,
							 Codigo ¿Codigo?,
							 Descripcion ¿Descripcion?,							 
							  DECODE(upper(TRIM(d.dr_Grupo)), upper('TyO'), 'Agentes Termohidromètricos y Otros',
                                             upper('Q'), 'Agentes Químicos',
                                             upper('F'), 'Agentes Físicos',
                                             upper('B'), 'Agentes Biológicos',
                                             'Sin Grupo') ¿Grupo?,							 
							 d.DR_ID ¿Detalle?
				 FROM(
							SELECT   '1' Seleccionar,
									 DECODE (rg_sufijoesop, '', rg_esop, rg_esop || ' ' || rg_sufijoesop) Codigo,
									 rg_descripcion Descripcion
							  FROM     hys.hec_esopporciiu ec
						INNER JOIN  prg_riesgos r  ON ec.ec_idesop = r.rg_id
							 WHERE   ec_idactividad = :codactividad
								 AND r.rg_fechabaja IS NULL
								 AND ec.ec_fechabaja IS NULL
								  AND r.RG_VISIBLEWEB = 'S'
								 
							UNION ALL
							
							SELECT   '0' Seleccionar,
									 DECODE (rg_sufijoesop, '', rg_esop, rg_esop || ' ' || rg_sufijoesop) Codigo,
									 rg_descripcion Descripcion
							  FROM   prg_riesgos							  
							 WHERE   rg_fechabaja IS NULL
							   AND 	 RG_VISIBLEWEB = 'S'
							 
							MINUS
							
							SELECT   '0' Seleccionar,
									 DECODE (rg_sufijoesop, '', rg_esop, rg_esop || ' ' || rg_sufijoesop) Codigo,
									 rg_descripcion Descripcion
							  FROM     hys.hec_esopporciiu ec
						INNER JOIN prg_riesgos r ON ec.ec_idesop = r.rg_id
							 WHERE   ec_idactividad = :codactividad
								 AND r.rg_fechabaja IS NULL
								 AND ec.ec_fechabaja IS NULL
								 AND 	r.RG_VISIBLEWEB = 'S'
						 )a
                         INNER JOIN hys.hdr_detalleriesgoesop d ON d.DR_RIESGOESOP =  a.Codigo 		 ";

	
	if($inFiltroRiesgos != '')
	{
		$ReturnSQL .= " WHERE CODIGO IN ( ".$inFiltroRiesgos." ) ";						
	}else{
		if( $codigo != '' ){
			$ReturnSQL .= " WHERE CODIGO LIKE TRIM(:ESOP) ";						
		}
		else
			if($descripcion != ''){
				$ReturnSQL .= " WHERE UPPER(DESCRIPCION) LIKE UPPER(:DESCRIPCION) ";	
			}
	}
	
	$ReturnSQL .= " ORDER BY SELECCIONAR DESC, CODIGO  ";	
	return $ReturnSQL;
}
