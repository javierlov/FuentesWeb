<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

function getGridSiniestros() {	

	$od = "0";	
	if (isset($_REQUEST["OrigenDemanda"]))
		$od = $_REQUEST["OrigenDemanda"];

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];
    
	$params[":od"] = $od;
	$where = "";
	$ob = "";
	
	$arrayFields = array("Orden","Recaida","Contrato","CUIT","Nombre","CUIL","Trabajador","Diagnóstico","F. Accidente","Baja Med.","F. Recaida","Alta Med.","T. Siniestro","Pago ILT");

	$sql = ObtenerSiniestrosGrid();

	$grilla = new GridDos(10, 10);
	
	$pageReportes =  "/modules/usuarios_registrados/estudios_juridicos/redirect.php?ReportesSiniestros=EDIT";		
	$columnaBoton = new ColumnDos("Siniestro", 0, true, false, -1, "", "", "", -1, true, -1, "Siniestro");	
	$columnaBoton->SetButtonCell("btnReportes", $pageReportes, '16',"Ir a Reporte de Siniestros"); //REPORTE	

	$grilla->addColumn($columnaBoton);
	
	foreach($arrayFields as $field)
		$grilla->addColumn(new Column($field, 0, true, false, -1, "", "", "", -1, false, -1));	
	
	$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, false -1));	
	//Version anterior $grilla->addColumn(new Column("Reporte", 0, true, false, -1, "btnReportes", $pageReportes, "", -1, false, -1));	
	
		
	$grilla->DefaultConfiguration();
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);	
	
	return $grilla->Draw(false);
}

function ObtenerSiniestrosGrid()
{ 
/*
TB_DESCRIPCION ¿TB_DESCRIPCION?
SJ_IDORIGENDEMANDA  ¿SJ_IDORIGENDEMANDA?, 
SJ_FECHAALTA ¿SJ_FECHAALTA?, 
SJ_FECHAMODIF ¿SJ_FECHAMODIF?, 
SJ_FECHABAJA ¿SJ_FECHABAJA?,    	
TB_DESCRIPCION ¿TB_DESCRIPCION?

*/
   	
  $strqry= "SELECT   	
		EX_SINIESTRO  ¿EX_SINIESTRO?, 
		EX_ORDEN  ¿EX_ORDEN?, 
		EX_RECAIDA  ¿EX_RECAIDA?, 
		MP_CONTRATO  ¿MP_CONTRATO?, 
		MP_CUIT  ¿MP_CUIT?, 
		MP_NOMBRE  ¿MP_NOMBRE?,  
		TJ_CUIL  ¿TJ_CUIL?, 
		TJ_NOMBRE  ¿TJ_NOMBRE?,  
		EX_DIAGNOSTICO  ¿EX_DIAGNOSTICO?, 
		EX_FECHAACCIDENTE  ¿EX_FECHAACCIDENTE?, 
		EX_BAJAMEDICA  ¿EX_BAJAMEDICA?, 
		EX_FECHARECAIDA  ¿EX_FECHARECAIDA?, 
		EX_ALTAMEDICA ¿EX_ALTAMEDICA?,                                
		TB_DESCRIPCION  ¿TB_DESCRIPCION?,
		LIQ.GET_IMPORTELIQUIDADOILT(EX_SINIESTRO,EX_ORDEN,EX_RECAIDA)  ¿IMPORTELIQUIDADO?,
		EX_SINIESTRO || '&ORDEN=' || EX_ORDEN ¿REPORTE? 
		
	FROM art.ctb_tablas, 
		ART.CTJ_TRABAJADORES, 
		ART.CMP_EMPRESAS, 
		ART.SEX_EXPEDIENTES, 
		LEGALES.LSJ_SINIESTROSJUICIOENTRAMITE 
	WHERE NVL (EX_TIPO, '1') = TB_CODIGO(+) 
	   AND TB_CLAVE = 'STIPO' 
	   AND MP_CUIT = EX_CUIT 
	   AND TJ_CUIL = EX_CUIL 
	   AND EX_SINIESTRO = SJ_SINIESTRO 
	   AND EX_RECAIDA = SJ_RECAIDA 
	   AND EX_ORDEN = SJ_ORDEN 
	   AND SJ_IDORIGENDEMANDA =  :od";
      
	return $strqry;  
  
}

function LimpiarCaracteresInvalidos($String){		
	$invalidos = array("Â","@");
	$String = str_replace($invalidos, " ", $String);
    return $String;
}