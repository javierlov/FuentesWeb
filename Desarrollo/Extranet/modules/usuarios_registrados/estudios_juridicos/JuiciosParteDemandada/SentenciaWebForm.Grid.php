<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

@session_start(); 

function getGridSentencia($NroJuicio) {	

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;
	
	$params[":NroJuicio"] = $NroJuicio;	
	$sql = ObtenerReclamosGrid();
	
	$grilla = new GridDos(10, 10);
		
	$grilla->addColumn(new Column("Reclamo", 	0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Monto Demanda", 	0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("% Inc. Demanda", 		0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Monto Sentencia", 	0, true, false, -1, "", "", "", -1, false -1));		
	$grilla->addColumn(new Column("% Sentencia",	0, true, false, -1, "", "", "", -1, false -1));		
/*
	$grilla->setRefreshIntoWindow(true);			
	$grilla->setColsSeparator(true);
	*/
	$grilla->DefaultConfiguration();			
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);	
		
	return $grilla->Draw(false);
}

function ObtenerReclamosGrid(){ 
	
	$strqry = "SELECT 
			LRC_RECLAMO.RC_DESCRIPCION ¿DESCRIPCION?, 
			NVL2(LRT_RECLAMOJUICIOENTRAMITE.RT_MONTODEMANDADO, 
					TO_CHAR(LRT_RECLAMOJUICIOENTRAMITE.RT_MONTODEMANDADO, '".DB_FORMATMONEY."'),	'' ) ¿MONTODEMANDADO?, 
				
			NVL2(lrt_reclamojuicioentramite.RT_IDRECLAMO,
				concat(TO_CHAR(lrt_reclamojuicioentramite.RT_porcentajeincapacidad, '".DB_FORMATPERCENT."'), ' %'), '' ) ¿IDRECLAMO?,
			
			NVL2(LRT_RECLAMOJUICIOENTRAMITE.RT_MONTOSENTENCIA, 
					TO_CHAR(LRT_RECLAMOJUICIOENTRAMITE.RT_MONTOSENTENCIA, '".DB_FORMATMONEY."'), '' ) ¿MONTOSENTENCIA?, 
					
			NVL2(LRT_RECLAMOJUICIOENTRAMITE.RT_PORCENTAJESENTENCIA,
					'%' || LRT_RECLAMOJUICIOENTRAMITE.RT_PORCENTAJESENTENCIA, '') ¿PORCENTAJESENTENCIA?
	  FROM legales.lrt_reclamojuicioentramite, legales.lrc_reclamo 
	 WHERE lrc_reclamo.rc_id = lrt_reclamojuicioentramite.rt_idreclamo 
	   AND lrt_reclamojuicioentramite.rt_fechabaja IS NULL 
	   AND rt_idjuicioentramite =  :NroJuicio";
	  
  return $strqry;
}
