<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php");

@session_start(); 

if (isset($_REQUEST['FUNCION'])){
    if ($_REQUEST['FUNCION']=="ValidarMotivoInstancia"){
		
	}
}

function ValidarMotivoInstancia($nrojuicio, $NuevaInstancia){
	$idinstancia = ObtenerInstanciaaCambiar($nrojuicio);	
	$result = ObtenerInstanciaSeleccionada($jurisdiccion, $fuero, $juzgado);
}

function ValidaChequeReimpreso($NUMERO){	
	//si retorna 0 no fue reeimpreso, mayor a 0 es el num de cheque reeimpreso..
	try{		
		global $conn;	
		$params = array(":NUMERO" => $NUMERO );	
		$sql ="	SELECT   NVL(max(CE_ID), 0) ID  FROM   rce_chequeemitido	 WHERE   ce_idchequereemp IS NOT NULL   AND ce_ordenpago = :NUMERO ";	
		$result = ValorSql($sql, "", $params);				
		return $result;
		
	}catch (Exception $e){				
		return false;						
	}
}

function RetornaChequeNum($NUMERO){	
	try{		
		global $conn;	
		$params = array(":NUMERO" => $NUMERO );	
		$sql ="	SELECT   NVL(MAX(CE_IDCHEQUEREEMP), 0) ID  FROM   rce_chequeemitido	 WHERE   ce_idchequereemp IS NOT NULL   AND ce_ordenpago = :NUMERO ";	
		$result = ValorSql($sql, "", $params);				
		return $result;
		
	}catch (Exception $e){				
		return false;						
	}
}
