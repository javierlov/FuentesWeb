<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");

//PATRON RPG
@session_start(); 

if( isset($_REQUEST["pageid"]) ){
	
	if($_REQUEST["pageid"] == "125" ){		
		header("Location: /SeleccionarEstablecimiento");
	}
	
	if($_REQUEST["pageid"] == "126" ){
		ConvertGetToSession("FormulariosNomina", $_REQUEST);
		header("Location: /FormulariosNomina");
	}	
	
}

if( isset($_REQUEST["pagename"]) and $_REQUEST["pagename"] == "ListadoPersonalExpuesto" ){		

	GetParamPersonalExp('NominaConfirmada');
	GetParamPersonalExp('idrelev');
	GetParamPersonalExp('idEstablecimiento');
	GetParamPersonalExp('ACTUAL');
	GetParamPersonalExp('empresaESTABLECI');
	GetParamPersonalExp('empresaCUITSINGUION');
			
	
	if( isset($_REQUEST['idEstablecimiento']) && $_REQUEST['idEstablecimiento'] > 0) {				
		$idEstablecimiento = $_REQUEST['idEstablecimiento'];
		$rowEstableci = GetDatosNominaWebEstablecimiento($idEstablecimiento);
		
		$idrelev = $rowEstableci['EW_ID'];							
		$tiponomina = $rowEstableci['EW_TIPONOMINA'];	
		
		$_SESSION['ListadoPersonalExpuesto']['tiponomina'] = $tiponomina;		
		$_SESSION['ListadoPersonalExpuesto']['idrelev'] = $idrelev;				
	}
	
	if( isset($_REQUEST['empresaESTABLECI']) && $_REQUEST['idrelev'] == 0 ){
	
		$empresaESTABLECI = $_REQUEST['empresaESTABLECI'];
		$empresaCUITSINGUION = $_REQUEST['empresaCUITSINGUION'];
		
		$rowDatosWeb = Get_NominaWEB_AnnoActual($empresaESTABLECI, $empresaCUITSINGUION);					
		
		$idrelev = $rowDatosWeb['EW_ID'];							
		$tiponomina = $rowDatosWeb['EW_TIPONOMINA'];	
		
		$_SESSION['ListadoPersonalExpuesto']['tiponomina'] = $tiponomina;		
		$_SESSION['ListadoPersonalExpuesto']['idrelev'] = $idrelev;		
	}
	
	header("Location: /ListadoPersonalExpuesto");
}	

function GetParamPersonalExp($name){
	if( isset($_REQUEST[$name]) ){
		$_SESSION['ListadoPersonalExpuesto'][$name] = $_REQUEST[$name];				
	}else
		unset($_SESSION['ListadoPersonalExpuesto'][$name]);			
}
	
function ConvertGetToSession($sessionName, $parameters){
	//FormulariosNomina	
	unset($_SESSION[$sessionName]);
	foreach($parameters as $key=>$value){
		$_SESSION[$sessionName][$key] = $value;
	}
}
