<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");

@session_start(); 

function getGrid($nroorden){	

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;
		
	$sql = ObtenerEventosCYQ();
	$params = array(":nroorden" => $nroorden);
	
	$grilla = new GridDos(10, 10);
		
	$grilla->addColumn(new Column("Nro. Evento", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Descripcion", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Fecha", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Observaciones", 0, true, false, -1, "", "", "", -1, false -1));	
			
	//CAMBIO PAG 112=119
	$grilla->addColumn(new Column("Modif.", 0, true, false, -1, "btnEditItem","/index.php?pageid=119&nroorden=".$nroorden, "", -1, true, -1, "Modifica"));	
	
	//CAMBIO PAG 111=118 
	$columna = new Column("Elim.", 0, true, false, -1, "btnDeleteItem","/index.php?pageid=118&DELETE&nroorden=".$nroorden, "", -1, true, -1, "Elimina");
	$grilla->addColumn($columna);	
	$grilla->DefaultConfiguration();			
	
	$grilla->ADDQuestionJS("MostrarVentanaQuery", 6);		
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);	
		
	return $grilla->Draw(false);
}


function ObtenerEventosCYQ(){

	global $conn;      
	$sql ="SELECT CE_NROEVENTO ¿CE_NROEVENTO?, 
				TB_DESCRIPCION ¿TB_DESCRIPCION?, 
				CE_FECHA ¿CE_FECHA?, 
				CE_OBSERVACIONES ¿CE_OBSERVACIONES?, 
				CE_NROEVENTO ¿EDITAR?, 
				CE_NROEVENTO ¿ELIMINAR? 
			FROM ctb_tablas, lce_eventocyq 
			WHERE tb_codigo = ce_codevento 
			AND tb_clave = 'EVCYQ' 
			AND ce_nroevento > 0 
			AND ce_nroorden = :nroorden ";

	return $sql;
}