<?php
if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/functions/CrearLog.php");

function CargarTipoPericia($idpericia = 0){
	global $conn;		

	$sql ="           
		   SELECT TP_ID, TP_DESCRIPCION
			 FROM legales.ltp_tipopericia 
			WHERE tp_fechabaja IS NULL 
		 ORDER BY tp_descripcion ";

	$params = array();	
	$stmt = DBExecSql($conn, $sql, $params);
	
	$result = " <select name='cmbTipoPericia' id='cmbTipoPericia' class='input_text'>";
	$result .=  ' <option value=0></option> ';	
	
	while ($row = DBGetQuery($stmt, 1, false)) {				
		$result .=  ' <option ';			
		
		//por defecto se seleccion el item 1
		if($row["TP_ID"] == $idpericia){	$result .= ' selected="selected" '; }
		
		$result .= 'value='.$row["TP_ID"].'> ';
		$result .=  $row["TP_DESCRIPCION"]; 
		$result .= ' </option> ';
	}
	
	$result .= ' </select> ';		
	return $result; 		
		 
}		 

function ObtenerPeritajesABM($PeritajeID){
	global $conn;		
	
	$sql ="
  	 	SELECT 	PJ_ID, 
  	 			TO_DATE(PJ_FECHAPERITAJE, 'DD/MM/YYYY') PJ_FECHAPERITAJE, 
  	 			PJ_IDJUICIOENTRAMITE, 
  	 			TRIM(PJ_RESULTADOPERITAJE) PJ_RESULTADOPERITAJE, 
  	 			TO_DATE(PJ_FECHANOTIFICACION, 'DD/MM/YYYY') PJ_FECHANOTIFICACION,   	 			 
		       	PJ_IDTIPOPERICIA, 
		       	TO_DATE(PJ_FECHAVENCIMPUGNACION, 'DD/MM/YYYY') PJ_FECHAVENCIMPUGNACION,   	 			 		       	 
		       	TO_NUMBER(PJ_INCAPACIDADDEMANDA) PJ_INCAPACIDADDEMANDA, 
		       	PJ_USUALTA, 
		      	TO_NUMBER(PJ_INCAPACIDADPERITOMEDICO) PJ_INCAPACIDADPERITOMEDICO, 
		      	TO_NUMBER(PJ_IBMART) PJ_IBMART, 
		      	TO_NUMBER(PJ_IBMPERICIAL) PJ_IBMPERICIAL, 
		      	PJ_IMPUGNACION, 
		       	DECODE(PJ_IMPUGNACION, 'S', 0, 'N', 1, -1) AS IMPUGNACION, 
		       	PJ_IDPERITO, 
		       	PE_NOMBRE, 
		       	PE_NOMBREINDIVIDUAL, 
		       	PE_APELLIDO 
		  FROM legales.lpj_peritajejuicio,legales.lpe_perito 
		 WHERE pj_idperito = pe_id
		   AND pj_id = :PeritajeID";
		
	if( is_numeric($PeritajeID) and ($PeritajeID> '0') ) {
		$params = array(":PeritajeID" => $PeritajeID);
		$stmt = DBExecSql($conn, $sql, $params);		
	   	$row = DBGetQuery($stmt);
		return array($row['PJ_ID'], 
						$row['PJ_FECHAPERITAJE'], 
						$row['PJ_IDJUICIOENTRAMITE'], 
						$row['PJ_RESULTADOPERITAJE'], 
						$row['PJ_FECHANOTIFICACION'], 
						$row['PJ_IDTIPOPERICIA'], 
						$row['PJ_FECHAVENCIMPUGNACION'], 
						$row['PJ_INCAPACIDADDEMANDA'], 
						$row['PJ_USUALTA'], 
						$row['PJ_INCAPACIDADPERITOMEDICO'], 
						$row['PJ_IBMART'], 
						$row['PJ_IBMPERICIAL'], 
						$row['PJ_IMPUGNACION'], 
						$row['IMPUGNACION'], 
						$row['PJ_IDPERITO'], 
						$row['PE_NOMBRE'], 
						$row['PE_NOMBREINDIVIDUAL'], 
						$row['PE_APELLIDO']);
	}
	else
		return array('','','','','','','','','','','','','','','','','','');

}

function InsertarPeritajeNuevo($txtFechaAsignacion, $txtFechaPericia, $txtFVencImpug, 
	$cmbPericia, $txtResultados, $nrojuicio, $usuario,
	$incapacidadDemanda, $incapacidadPeritoMedico, 
	$ibmArt, $ibmPericial, $impugnacion, $idperito){	

//print_r($_REQUEST);

	try{
	
	global $conn;

	$peritajeid = GetSecNextValOracle('legales.seq_lpj_id');
	
	$montodemanda= $incapacidadDemanda;	 
	$montomedico = $incapacidadPeritoMedico;
	$montoart = $ibmArt;
	$montopericial = $ibmPericial;

	$sqlInsert = " INSERT INTO legales.lpj_peritajejuicio 
             (pj_id, PJ_IDPERITO, pj_fechanotificacion, 
              pj_fechaperitaje, pj_fechavencimpugnacion, 
              pj_resultadoperitaje, pj_fechaalta, 
              pj_usualta, pj_idjuicioentramite, 
              pj_idtipopericia, pj_incapacidaddemanda, 
              pj_incapacidadperitomedico, pj_ibmart, 
              pj_ibmpericial, pj_impugnacion 
             ) 
      VALUES (	:pj_id, 
      			:pj_IDPERITO,
      			TO_DATE(:pj_fechanotificacion, 'DD/MM/YYYY'), 
      			TO_DATE(:pj_fechaperitaje, 'DD/MM/YYYY'), 
      			TO_DATE(:pj_fechavencimpugnacion, 'DD/MM/YYYY'), 
	            TRIM(:pj_resultadoperitaje), 
	            SYSDATE, 
	            :pj_usualta, 
	            :pj_idjuicioentramite, 
				TO_NUMBER(:pj_idtipopericia), 
				TO_NUMBER(:pj_incapacidaddemanda), 
				TO_NUMBER(:pj_incapacidadperitomedico), 
				TO_NUMBER(:pj_ibmart), 
				TO_NUMBER(:pj_ibmpericial), 
				:pj_impugnacion )";
				
 	
	$txtFechaAsignacion = GetStrToDate($txtFechaAsignacion);
	$txtFechaPericia = GetStrToDate($txtFechaPericia);	
 	$txtFVencImpug = GetStrToDate($txtFVencImpug);
	
 	$cmbPericia = (int) $cmbPericia;
 	
 	$montodemanda = Getfloat($montodemanda);
 	$montomedico = Getfloat($montomedico);
 	$montoart = Getfloat($montoart);
 	$montopericial = Getfloat($montopericial);
 	
 	$params = array(":pj_id" => $peritajeid,					
 					":pj_IDPERITO" => $idperito,					
 					":pj_fechanotificacion" => $txtFechaAsignacion,					
 					":pj_fechaperitaje" => $txtFechaPericia,					
 					":pj_fechavencimpugnacion" => $txtFVencImpug,					
 					":pj_resultadoperitaje" => TRIM($txtResultados),					
 					":pj_usualta" => $usuario,					
 					":pj_idjuicioentramite" => $nrojuicio,					
 					":pj_idtipopericia" => $cmbPericia,					
 					":pj_incapacidaddemanda" => $montodemanda,					
 					":pj_incapacidadperitomedico" => $montomedico,					
 					":pj_ibmart" => $montoart,					
 					":pj_ibmpericial" => $montopericial,					
					":pj_impugnacion" => $impugnacion
					);	  
 	
 	DBExecSql($conn, $sqlInsert , $params);				  
			  		
   //----------------------------------------------------------------------            
    DBCommit($conn);
   
	return true;
    }
    catch (Exception $e) {
        DBRollback($conn);        
        return false;
    }

}    

function UpdatePeritajesABM($txtFechaAsignacion, $txtFechaPericia,
	$txtFVencImpug, $cmbPericia, $txtResultados, $pj_id, $usuario,
	$incapacidadDemanda, $incapacidadPeritoMedico, $ibmArt, $ibmPericial, 
	$impugnacion, $idperito){

	try{
	
	global $conn;
	
	$id = intval($pj_id);
	$txtResultados = trim($txtResultados);
	
	$sqlUpdate = "UPDATE legales.lpj_peritajejuicio 
   		             SET
					    pj_fechanotificacion = TO_DATE(:txtFechaAsignacion, 'DD/MM/YYYY'), 
					    pj_fechaperitaje = TO_DATE(:txtFechaPericia, 'DD/MM/YYYY'), 
					    pj_fechavencimpugnacion = TO_DATE(:txtFVencImpug, 'DD/MM/YYYY'), 
					    pj_resultadoperitaje = TRIM(:txtResultados),
					    pj_fechamodif = SYSDATE,
					    pj_usumodif =  :usuario, 
						pj_fechabaja = NULL, 
		 				pj_usubaja = NULL, 
		 				pj_idperito = :idperito,
				 		pj_idtipopericia = :cmbPericia,
		 				pj_incapacidaddemanda = TO_NUMBER(:montodemanda),
		 				pj_incapacidadperitomedico = TO_NUMBER(:montomedico),
		 				pj_ibmart =  TO_NUMBER(:montoart),
			 			pj_ibmpericial = TO_NUMBER(:montopericial),
		 				pj_impugnacion = :impugnacion,
		 				pj_completaestudio = 'N' 
   				  WHERE pj_id = :id ";	

	$txtFechaAsignacion = GetStrToDate($txtFechaAsignacion);
	$txtFechaPericia = GetStrToDate($txtFechaPericia);	
 	$txtFVencImpug = GetStrToDate($txtFVencImpug);
	
 	$cmbPericia = intval($cmbPericia);
 	$txtResultados = trim($txtResultados);
 	
 	$montodemanda = Getfloat($incapacidadDemanda);
 	$montomedico = Getfloat($incapacidadPeritoMedico);
 	$montoart = Getfloat($ibmArt);
 	$montopericial = Getfloat($ibmPericial);
 	
 	$params = array(":txtFechaAsignacion" => $txtFechaAsignacion,  
 					":txtFechaPericia" => $txtFechaPericia,
 					":txtFVencImpug" => $txtFVencImpug,
 					":txtResultados" => $txtResultados,
 					":usuario" => $usuario,
 					":idperito" => $idperito,
 					":cmbPericia" => $cmbPericia,
 					":montodemanda" => $montodemanda,
 					":montomedico" => $montomedico,
 					":montoart" => $montoart,
 					":montopericial" => $montopericial,
 					":impugnacion" => $impugnacion,
 					":id" => $id 			
					);	  
					
EscribirLogTxt1('ok UpdatePeritajesABM', implode(",",$params));			     	
EscribirLogTxt1('idperito UpdatePeritajesABM', $idperito);			     	

 	DBExecSql($conn, $sqlUpdate , $params);				  
			  		
    DBCommit($conn);
   
	return true;
    }
    catch (Exception $e) {
EscribirLogTxt1('Error UpdatePeritajesABM', var_dump($e) );			    
        DBRollback($conn);        
        return false;
    }
				
}


