<?php
if(!isset($_SESSION)) { session_start(); } 

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");

//ConcursoyQuiebrasWebForm.php
function getGridCyQ($NroOrden, $cmbRSocial, $Cuil, $estudio) {	

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;
		
	$sql = ObtenerConcursosyQuiebras($NroOrden, $cmbRSocial, $Cuil, $estudio);
	$params = array();
	
	$grilla = new GridDos(10, 7);
		
	$grilla->addColumn(new Column("Nro. Orden", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Cuit", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Nombre", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("F. Concurso", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("F. Quiebra", 0, true, false, -1, "", "", "", -1, false -1));	
	 //CAMBIO PAG 110=117
	$ConcursosyQuiebras = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=117";
	
	$grilla->addColumn(new Column("Seleccion", 0, true, false, -1, "btnBuscarItem",$ConcursosyQuiebras, "", -1, true, -1, "Seleccion"));	
		
	$grilla->DefaultConfiguration();			
	
	$grilla->setPageNumber($pagina);
	$grilla->setSql($sql);	
	$grilla->setParams($params);
	
	
	return $grilla->Draw(false);
}

function ObtenerConcursosyQuiebras($NroOrden, $cmbRSocial, $Cuil, $estudio){
        
    $sql = "SELECT 
				CQ_NROORDEN ¿CQ_NROORDEN?, 
				CQ_CUIT ¿CQ_CUIT?, 
				EM_NOMBRE ¿EM_NOMBRE?, 
				CQ_FECHACONCURSO ¿CQ_FECHACONCURSO?, 
				CQ_FECHAQUIEBRA ¿CQ_FECHAQUIEBRA?, 
				CQ_NROORDEN ¿SELECCION? 
	FROM AFI.AEM_EMPRESA, ART.LCQ_CONCYQUIEBRA, legales.lbo_abogado 
	WHERE EM_CUIT = CQ_CUIT AND cq_abogado = bo_id 
	AND bo_idestudiojuridico =  $estudio";
	
	if (trim($NroOrden) != ''){
		$sql .= " AND CQ_NROORDEN = $NroOrden";		
	}
  
	if (trim($cmbRSocial) != ''){
		$sql .= " AND EM_CUIT= '$cmbRSocial'";		
	}
	
	if(trim($Cuil) != ''){
		$sql .= " AND CQ_CUIT= '$Cuil'";		
	}
	
	return $sql;	
	
}