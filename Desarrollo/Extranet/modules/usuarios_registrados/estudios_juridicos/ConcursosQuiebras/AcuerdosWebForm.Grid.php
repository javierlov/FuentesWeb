<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");

@session_start(); 

//AcuerdosWebForm.Grid.php
function getGridAcuerdos($nroorden, $filtroTipo='0') {	

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;
		
	$sql = ObtenerAcuerdosGrid($filtroTipo);
	$params = array(":nroorden" => $nroorden);
	
	$grilla = new GridDos(10, 7);
		
	$grilla->addColumn(new Column("Nro. Pago", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Monto", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("F. Pago", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("F. Vto", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Tipo", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("	F. Caducidad del acuerdo", 0, true, false, -1, "", "", "", -1, false -1));	
	
	//CAMBIO PAG 114=121
	$AcuerdosABMWeb = "/modules/usuarios_registrados/estudios_juridicos/redirect.php?pageid=121&nroorden=".$nroorden;
	$grilla->addColumn(new Column("Modif.", 0, true, false, -1, "btnEditItem", $AcuerdosABMWeb, "", -1, true, -1, "Modificar"));
	
	//CAMBIO PAG 113=120
	$AcuerdosDelete = "/index.php?pageid=120&DELETE&nroorden=".$nroorden;
	$grilla->addColumn(new Column("Elim.", 0, true, false, -1, "btnDeleteItem" ,$AcuerdosDelete , "", -1, true, -1, "Eliminar"));	
	
	$grilla->DefaultConfiguration();			
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);	
	
	$grilla->setNameParamGET("NroPago");	
	
	return $grilla->Draw(false);
}

function ObtenerAcuerdosGrid($filtroTipo = '0'){
        
    $sql = "SELECT 
				CA_NROPAGO ¿CA_NROPAGO?, 
				CA_MONTO ¿CA_MONTO?, 
				CA_FECHAPAGO ¿CA_FECHAPAGO?,  
				CA_FECHAVENC ¿CA_FECHAVENC?, 
				TB_DESCRIPCION ¿TB_DESCRIPCION?, 
				CA_FECHAEXTINCION ¿CA_FECHAEXTINCION?,
				CA_NROPAGO ¿EDIT?,				
				CA_NROPAGO ¿DEL?				
		   FROM lca_acuerdocyq, CTB_TABLAS
		  WHERE TB_CODIGO = CA_TIPO 
			AND ca_nropago > 0 			 
			AND ca_nroorden = :nroorden
			AND TB_CLAVE = 'TACYQ' ";
	
	if(intval($filtroTipo) > 0 ) 
		$sql .= " AND CA_TIPO =  $filtroTipo";
	
	return $sql;	
	
}