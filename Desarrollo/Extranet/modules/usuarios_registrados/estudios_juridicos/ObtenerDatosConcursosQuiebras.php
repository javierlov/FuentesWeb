<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

@session_start(); 

if (isset($_REQUEST['FUNCION'])){    
    if ($_REQUEST['FUNCION']=="ObtenerValidacionFechaCuotas"){
		
		$fechavenc = '';
		$nroorden = '';
		$cantcuotas = '';
		$tiempo = '';
				
        if (isset($_REQUEST["fechavenc"])) {  $fechavenc = utf8_decode($_REQUEST["fechavenc"]); }
        if (isset($_REQUEST["nroorden"])) {  $nroorden = utf8_decode($_REQUEST["nroorden"]); }
        if (isset($_REQUEST["cantcuotas"])) {  $cantcuotas = utf8_decode($_REQUEST["cantcuotas"]); }
        if (isset($_REQUEST["tiempo"])) {  $tiempo = utf8_decode($_REQUEST["tiempo"]); }
		
		$result = '';
		
		if( ($nroorden != '') and ($fechavenc != '') and ($cantcuotas != '') and ($tiempo != '') ){
			$datos = ObtenerValidacionFechaCuotas($fechavenc,$cantcuotas, $tiempo, $nroorden);
			$result = trim($datos);
		}
        echo $result;
		
	}
	
    if ($_REQUEST['FUNCION']=="ObtenerValidacionFechaAcuerdoModif"){
		
		$fechavenc = '';
		$nroorden = '';
		$nropago = '';
		
        if (isset($_REQUEST["fechavenc"])) {  $fechavenc = utf8_decode($_REQUEST["fechavenc"]); }
        if (isset($_REQUEST["nroorden"])) {  $nroorden = utf8_decode($_REQUEST["nroorden"]); }
        if (isset($_REQUEST["nropago"])) {  $nropago = utf8_decode($_REQUEST["nropago"]); }
		
		$result = '';
		
		if( ($nroorden != '') and ($nropago != '') ){
			$datos = ObtenerValidacionFechaAcuerdoModif($fechavenc, $nroorden, $nropago);
			$result = trim($datos);
		}
        echo $result;		
	}
}

function ObtenerDatosCYQ($nroorden){
    try{
		global $conn;       
		
		$sql = " SELECT LCQ.CQ_NROORDEN, 
						LCQ.CQ_CUIT, 
						CMP.MP_NOMBRE, 
						LCQ.CQ_DEUDANOMINAL, 
						LCQ.CQ_DEUDATOTAL, 
						LCQ.CQ_FECHAVERIFICACIONCREDITO, 
						LCQ.CQ_DEUDAVERIFICADA, 
						LCQ.CQ_FECHACONCURSO AS FECHACONCURSO, 
						LCQ.CQ_FECHAQUIEBRA AS FECHAQUIEBRA, 
						CQ_FECHAASIGN, 
						CQ_FECHAVTOART32, 
						CQ_FECHAVTOART200, 
						CQ_FECHATOMACONCONCURSO, 
						CQ_FECHATOMACONQUIEBRA, 
						LCQ.CQ_MONTOPRIVILEGIO, 
						LCQ.CQ_MONTOQUIROG, 
						LCQ.CQ_SINDICO, 
						LCQ.CQ_DIRECCIONSIND, 
						LCQ.CQ_LOCALIDADSIND, 
						LCQ.CQ_TELEFONOSIND, 
						LCQ.CQ_JUZGADO, 
						LCQ.CQ_SECRETARIA, 
						LCQ.CQ_FUERO, 
						FUE.TB_DESCRIPCION FUE_DESCRIPCION, 
						LCQ.CQ_JURISDICCION, 
						JU_DESCRIPCION JUR_DESCRIPCION, 
						LCQ.CQ_ABOGADO, 
						BO_NOMBRE, 
						LCQ.CQ_MONTOHOMOLOG, 
						LCQ.CQ_ESTADO, 
						LCQ.CQ_AUTORIZACION, 
						LCQ.CQ_ULTPERCONCURSO, 
						LCQ.CQ_ULTPERQUIEBRA, 
						LCQ.CQ_LEGAJO 	
	   FROM ART.CTB_TABLAS FUE, LEGALES.LJU_JURISDICCION, ART.CMP_EMPRESAS CMP, 
			LEGALES.LBO_ABOGADO LBO, ART.LCQ_CONCYQUIEBRA LCQ         
	  WHERE LCQ.CQ_FUERO = FUE.TB_CODIGO 
		AND LCQ.CQ_JURISDICCION = JU_ID 
		AND LCQ.CQ_CUIT = CMP.MP_CUIT 
		AND LCQ.CQ_ABOGADO = LBO.BO_ID      
		AND FUE.TB_CLAVE  = 'FUERO' 
		AND LCQ.CQ_NROORDEN =  :nroorden ";
		
		$params = array(":nroorden" => $nroorden);			

		@$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){		
			return $row;		
		}

		if(!isset($row)){
			$row = array("CQ_NROORDEN" => '', "CQ_CUIT" => '', 
						"MP_NOMBRE" => '', 
						"CQ_DEUDANOMINAL" => '', 
						"CQ_DEUDATOTAL" => '', 
						"CQ_FECHAVERIFICACIONCREDITO" => '', 
						"CQ_DEUDAVERIFICADA" => '', 
						"CQ_FECHACONCURSO as FECHACONCURSO" => '', 
						"CQ_FECHAQUIEBRA as FECHAQUIEBRA" => '', 
						"CQ_FECHAASIGN" => '', 
						"CQ_FECHAVTOART32" => '', 
						"CQ_FECHAVTOART200" => '', 
						"CQ_FECHATOMACONCONCURSO" => '', 
						"CQ_FECHATOMACONQUIEBRA" => '', 
						"CQ_MONTOPRIVILEGIO" => '', 
						"CQ_MONTOQUIROG" => '', 
						"CQ_SINDICO" => '', 
						"CQ_DIRECCIONSIND" => '', 
						"CQ_LOCALIDADSIND" => '', 
						"CQ_TELEFONOSIND" => '', 
						"CQ_JUZGADO" => '', 
						"CQ_SECRETARIA" => '', 
						"CQ_FUERO" => '', 
						"TB_DESCRIPCION FUE_DESCRIPCION" => '', 
						"CQ_JURISDICCION" => '', 
						"JUR_DESCRIPCION" => '', 
						"CQ_ABOGADO" => '', 
						"BO_NOMBRE" => '', 
						"CQ_MONTOHOMOLOG" => '', 
						"CQ_ESTADO" => '', 
						"CQ_AUTORIZACION" => '', 
						"CQ_ULTPERCONCURSO" => '', 
						"CQ_ULTPERQUIEBRA" => '', 
						"CQ_LEGAJO" => '');	
			return $row;		
		}		
	}catch (Exception $e) {
        DBRollback($conn);                
		//ErrorConeccionDatos($e->getMessage());		//return false;
		throw new Exception($e->getMessage());
    }     						
}

function ObtenerMontosCYQ($nroorden){
	try{
		global $conn;      
		
		$sql = "SELECT LCQ.CQ_NROORDEN, LCQ.CQ_CUIT, CMP.MP_NOMBRE, LCQ.CQ_DEUDANOMINAL, LCQ.CQ_DEUDATOTAL, 
					LCQ.CQ_DEUDAVERIFICADA, NVL(LCQ.CQ_FECHACONCURSO, MP_FECHACONCURSO) FECHACONCURSO, 
					NVL(LCQ.CQ_FECHAQUIEBRA, MP_FECHAQUIEBRA) FECHAQUIEBRA, CQ_FECHAASIGN, CQ_FECHAVTOART32, CQ_FECHAVTOART200, 
					CQ_FECHATOMACONCONCURSO, CQ_FECHATOMACONQUIEBRA, LCQ.CQ_MONTOPRIVILEGIO, LCQ.CQ_MONTOQUIROG, LCQ.CQ_SINDICO, 
					LCQ.CQ_DIRECCIONSIND, LCQ.CQ_LOCALIDADSIND, LCQ.CQ_TELEFONOSIND, LCQ.CQ_JUZGADO, LCQ.CQ_SECRETARIA, 
					LCQ.CQ_FUERO, FUE.TB_DESCRIPCION FUE_DESCRIPCION, LCQ.CQ_JURISDICCION, JUR.TB_DESCRIPCION JUR_DESCRIPCION, 
					LCQ.CQ_ABOGADO, BO_NOMBRE, LCQ.CQ_MONTOHOMOLOG, LCQ.CQ_ESTADO, LCQ.CQ_AUTORIZACION, LCQ.CQ_ULTPERCONCURSO, 
					LCQ.CQ_ULTPERQUIEBRA, LCQ.CQ_LEGAJO 
			   FROM ART.CTB_TABLAS FUE, ART.CTB_TABLAS JUR, ART.CMP_EMPRESAS CMP, LEGALES.LBO_ABOGADO LBO, ART.LCQ_CONCYQUIEBRA LCQ 
			  WHERE LCQ.CQ_FUERO = FUE.TB_CODIGO  
				AND LCQ.CQ_JURISDICCION = JUR.TB_CODIGO  
				AND LCQ.CQ_CUIT = CMP.MP_CUIT 
				AND LCQ.CQ_ABOGADO = LBO.BO_ID  
				AND FUE.TB_CLAVE  = 'FUERO' 
				AND JUR.TB_CLAVE  = 'JURIS' 
				AND LCQ.CQ_NROORDEN =  :nroorden";
				
		$params = array(":nroorden" => $nroorden );
		@$stmt = DBExecSql($conn, $sql, $params);

		while ($row = DBGetQuery($stmt)) {		
			return $row;		
		}
		
			if(!isset($row)){
			$row = array("CQ_NROORDEN" => '',
							"CQ_CUIT" => '',
							"MP_NOMBRE" => '',
							"CQ_DEUDANOMINAL" => '',
							"CQ_DEUDATOTAL" => '', 
							"CQ_DEUDAVERIFICADA" => '',
							"FECHACONCURSO" => '', 
							"FECHAQUIEBRA" => '',
							"CQ_FECHAASIGN" => '',
							"CQ_FECHAVTOART32" => '',
							"CQ_FECHAVTOART200" => '', 
							"CQ_FECHATOMACONCONCURSO" => '',
							"CQ_FECHATOMACONQUIEBRA" => '',
							"CQ_MONTOPRIVILEGIO" => '',
							"CQ_MONTOQUIROG" => '',
							"CQ_SINDICO" => '', 
							"CQ_DIRECCIONSIND" => '',
							"CQ_LOCALIDADSIND" => '',
							"CQ_TELEFONOSIND" => '',
							"CQ_JUZGADO" => '',
							"CQ_SECRETARIA" => '', 
							"CQ_FUERO" => '',
							"TB_DESCRIPCION FUE_DESCRIPCION" => '',
							"CQ_JURISDICCION" => '',
							"TB_DESCRIPCION JUR_DESCRIPCION" => '', 
							"CQ_ABOGADO" => '',
							"BO_NOMBRE" => '',
							"CQ_MONTOHOMOLOG" => '',
							"CQ_ESTADO" => '',
							"CQ_AUTORIZACION" => '',
							"CQ_ULTPERCONCURSO" => '', 
							"CQ_ULTPERQUIEBRA" => '',
							"CQ_LEGAJO" => '');		
			return $row;		
		}
	}catch (Exception $e) {
        DBRollback($conn);                
		//ErrorConeccionDatos($e->getMessage());		//return false;
		throw new Exception($e->getMessage());
    }     						
}

function ObtenerEmpresa($Cuil){
	try{
		global $conn;      

		$sql ="SELECT EM_ID AS ID, 
					EM_CUIT AS CUIT, 
					EM_NOMBRE AS NOMBRE, 
					CO_CONTRATO AS CONTRATO, 
					ART.AFILIACION.CHECK_COBERTURA (CO_CONTRATO) AS CHECKCOBERTURA, 
					DECODE (ART.AFILIACION.CHECK_COBERTURA (CO_CONTRATO), 1, 1,2 ) AS ORDENESTADO, 
					CO_FECHABAJA AS FECHA_BAJA, 
					EM_FECHACONCURSO, 
					EM_FECHAQUIEBRA 
				 FROM aem_empresa, aco_contrato 
				WHERE em_id = co_idempresa 
				AND em_cuit =  :Cuil
			 ORDER BY ORDENESTADO, CONTRATO DESC, NOMBRE ";
						 
			$params = array(":Cuil" => $Cuil );
			@$stmt = DBExecSql($conn, $sql, $params);
		
		while ($row = DBGetQuery($stmt)) {		
			return $row;		
		}
		
			if(!isset($row)){
			$row = array("ID" => '', 
						"CUIT" => '', 
						"NOMBRE" => '', 
						"CONTRATO" => '', 
						"CHECKCOBERTURA" => '', 
						"ORDENESTADO" => '', 
						"FECHA_BAJA" => '', 
						"EM_FECHACONCURSO" => '', 
						"EM_FECHAQUIEBRA" => '');		
			return $row;		
		}
	}catch (Exception $e) {
        DBRollback($conn);                
		//ErrorConeccionDatos($e->getMessage());		//return false;
		throw new Exception($e->getMessage());
    }     						
}

function ObtenerEstado($codigo){	
	try{
		global $conn;      
		$sql =" SELECT TB_CODIGO AS ID, 
						TB_CODIGO AS CODIGO, 
						TB_DESCRIPCION AS DESCRIPCION, 
						TB_FECHABAJA AS BAJA, 
						TB_CLAVE, 
						TB_ESPECIAL1, 
						TB_ESPECIAL2 
				   FROM ctb_tablas 
				  WHERE tb_codigo <> '0' 
					AND tb_fechabaja IS NULL 
					AND tb_clave = 'ESTCQ' 
					AND ctb_tablas.tb_codigo = 1";

			$params = array(":codigo" => $codigo);
			@$stmt = DBExecSql($conn, $sql, $params);
		
		while ($row = DBGetQuery($stmt)) {		
			return $row;		
		}
		
			if(!isset($row)){
			$row = array("ID" => '',  
						"CODIGO" => '',  
						"DESCRIPCION" => '',  
						"BAJA" => '',  
						"TB_CLAVE" => '',  
						"TB_ESPECIAL1" => '',  
						"TB_ESPECIAL2"=> '');		
						
			return $row;		
		}				
	}catch (Exception $e) {
        DBRollback($conn);                
		//ErrorConeccionDatos($e->getMessage());		return false;
		throw new Exception($e->getMessage());
    }     						
}

function InsertarEventoCYQNuevo($txtfecha, $txtobservaciones, $usuario, $cmbEventos, $nroorden){	
    try{
		global $conn;      

		$sqlNro = "SELECT iif_compara ('<=', NVL (MAX (ce_nroevento), 0), 0, 1, NVL (MAX (ce_nroevento), 0) + 1 ) 
					FROM lce_eventocyq    WHERE ce_nroorden = :nroorden";
		
		$params = array(":nroorden" => $nroorden);    
		$nroevento = ValorSql($sqlNro, "", $params);
		//
		$sqlInsert = "INSERT INTO lce_eventocyq 
						(ce_id, ce_nroorden, ce_nroevento, ce_usualta, ce_fechaalta, ce_codevento, ce_fecha, ce_observaciones) 
					VALUES (ART.SEQ_LCE_ID.NEXTVAL, 
						:nroorden, 
						:nroevento, 
						:usualta, 
						SYSDATE, 
						:codevento, 
						:fecha, 
						:observaciones) ";

		$params = array(":nroorden" => intval($nroorden),
	            ":nroevento" => intval($nroevento),
	            ":usualta" => $usuario,
	            ":codevento" => intval($cmbEventos),
	            ":fecha" => $txtfecha,
	            ":observaciones" => $txtobservaciones );
				
		DBExecSql($conn, $sqlInsert, $params);	
   //----------------------------------------------------------------------    
		DBCommit($conn);
		return true;
    }catch (Exception $e) {
        DBRollback($conn);                
		//ErrorConeccionDatos($e->getMessage());		return false;
		throw new Exception($e->getMessage());
    }     				
}

function UpdateEventosCYQABM($txtfecha, $txtobservaciones, $usuario, $cmbEventos, $nroorden, $nroevento){
	try{
		global $conn;      
		$sqlUpdate = "UPDATE lce_eventocyq 
						SET ce_usumodif = :usuario, 
							ce_fechamodif = SYSDATE, 
							ce_codevento = :cmbEventos, 
							ce_fecha = :txtfecha, 
							ce_observaciones = :txtobservaciones
					  WHERE ce_nroorden = :nroorden
						AND ce_nroevento =  :nroevento";
					  
	   $params = array(":usuario" => $usuario,
					":cmbEventos" => $cmbEventos,
					":txtfecha" => $txtfecha,
					":txtobservaciones" => $txtobservaciones,
					":nroorden" => $nroorden,
					":nroevento" => $nroevento);
		
		DBExecSql($conn, $sqlUpdate, $params);                  
	   //----------------------------------------------------------------------    
		DBCommit($conn);
		return true;
    }catch (Exception $e) {
        DBRollback($conn);        
        //ErrorConeccionDatos($e->getMessage());		return false;
		throw new Exception($e->getMessage());
    }   
} 

function UpdateEventosCYQ($nroorden, $nroevento, $usuario){
	//Baja logica de datos
    try{
		global $conn;      

		$sqlNro = "SELECT iif_compara ('>', NVL(Min( CE_NROEVENTO ), 0), 0, -1, NVL(Min( CE_NROEVENTO ), 0)-1 ) 
					FROM LCE_EVENTOCYQ 
					WHERE  ce_nroorden = :nroorden";
		
		$params = array(":nroorden" => $nroorden);    
		$nro_evento = ValorSql($sqlNro, "", $params);
		//---------------------------------------------------
		$sqlUpdate = " UPDATE lce_eventocyq 
						SET ce_nroevento = :nro_evento, 
							ce_usumodif = :usuario, 
							ce_fechamodif = SYSDATE 
					  WHERE ce_nroorden = :nroorden
						AND ce_nroevento = :nroevento";
						
		$params = array(":nro_evento" => $nro_evento,
						":usuario" => $usuario,
						":nroorden" => $nroorden,
						":nroevento" => $nroevento);
		
		DBExecSql($conn, $sqlUpdate, $params);                  
		//-----------------------------------------------------
		DBCommit($conn);
		return true;
    }catch (Exception $e) {
        DBRollback($conn);        
		//ErrorConeccionDatos($e->getMessage());		return false;
		throw new Exception($e->getMessage());
    }   
} 

function ObtenerEventosCYQABM($nroorden, $nroevento){
	try{
		global $conn;      
		$sql =" SELECT CE_CODEVENTO, CE_FECHA, CE_OBSERVACIONES 
				   FROM lce_eventocyq 
				  WHERE ce_nroorden = :nroorden
					AND ce_nroevento =  :nroevento";

			$params = array(":nroorden" => $nroorden,
							":nroevento" => $nroevento);
							
			@$stmt = DBExecSql($conn, $sql, $params);
		
		while ($row = DBGetQuery($stmt)) {		
			return $row;		
		}
		
			if(!isset($row)){
			$row = array("CE_CODEVENTO" => '',  
						"CE_FECHA" => '',  
						"CE_OBSERVACIONES" => '');		
						
			return $row;		
		}				
	}catch (Exception $e) {
        DBRollback($conn);                
		//ErrorConeccionDatos($e->getMessage());		return false;
		throw new Exception($e->getMessage());
    }     						
} 

function InsertarAcuerdoNuevo($txtfechavenc, $txtmonto, $txtfechapago, $txtobservaciones, 
			$usuario, $nroorden, $txtFechaExtincion, $cmbTipo){	
    try{
		global $conn;      

		$sqlNro = "SELECT IIF_Compara('<=', NVL(Max(CA_NROPAGO), 0), 0, 1, NVL(Max(CA_NROPAGO), 0) + 1) 
					FROM LCA_ACUERDOCYQ WHERE CA_NROORDEN = :nroorden";
					

		$params = array(":nroorden" => $nroorden);    
		$nropago = @ValorSql($sqlNro, "", $params);
		//
		$sqlInsert = "INSERT INTO LCA_ACUERDOCYQ 
						   (CA_NROORDEN, CA_NROPAGO, CA_USUALTA, 
							CA_FECHAALTA, CA_FECHAPAGO, CA_FECHAVENC, CA_MONTO, 
							CA_OBSERVACIONES, CA_FECHAEXTINCION, CA_TIPO) 
					VALUES (:nroorden, :nropago, :usuario, SYSDATE, ";
					
		if( trim($txtfechapago) <> ''  && trim($txtfechapago) > '0' ){
		  $sqlInsert .= SqlDate($txtfechapago).", ";		
		}
		else{
		  $sqlInsert .= " NULL , ";		  
		}
								
		$sqlInsert .= " ".SqlDate($txtfechavenc).", '".Getfloat($txtmonto)."', '".trim($txtobservaciones)."', ";
		
		if( trim($txtFechaExtincion) <> '' && trim($txtFechaExtincion) > '0'){
		  $sqlInsert .= SqlDate($txtFechaExtincion).", ";
		 } 
		else{
		  $sqlInsert .=" NULL ,";
		}
		  
		$sqlInsert .= "'".trim($cmbTipo)."') ";
	
		$params = array(":nroorden" => intval($nroorden),
	            ":nropago" => intval($nropago),
	            ":usuario" => $usuario);

		@DBExecSql($conn, $sqlInsert, $params);	
   //----------------------------------------------------------------------    
		DBCommit($conn);
		return true;
    }catch (Exception $e) {
        DBRollback($conn);        
		//ErrorConeccionDatos($e->getMessage());	//return false;
		throw new Exception($e->getMessage());
    }     	
}

function UpdateAcuerdosABM ($txtfechavenc, $txtmonto, $txtfechapago, 
		$txtobservaciones, $usuario,
		$nroorden, $nropago, $txtFechaExtincion, $cmbtipo){
		
	try{
		global $conn;  
		
		$txtmonto = Getfloat($txtmonto);				
		
		$sqlUpdate = "UPDATE lca_acuerdocyq 
			SET ca_usumodif = :usuario, 
			ca_fechamodif = SYSDATE, ";

		if($txtfechapago != ''){
			$sqlUpdate .= " ca_fechapago = ".SqlDate($txtfechapago)." , ";
		}else{
			$sqlUpdate  .= "ca_fechapago = NULL , ";
		}

		$sqlUpdate .= "ca_fechavenc = ".SqlDate($txtfechavenc).", ca_monto = :monto, ";

		if($txtFechaExtincion != ''){
			$sqlUpdate .= " CA_FECHAEXTINCION = ".SqlDate($txtFechaExtincion)." , ";
		}
		else{
			$sqlUpdate .= " CA_FECHAEXTINCION = NULL , ";
		}

		$sqlUpdate  .= "CA_TIPO = :cmbtipo, 
						ca_observaciones = :txtobservaciones 
					WHERE ca_nroorden = :nroorden     
					AND ca_nropago = :nropago ";		

			  
		$params = array(":usuario" => $usuario,
				":monto" => $txtmonto,
				":cmbtipo" => $cmbtipo,
				":txtobservaciones" => $txtobservaciones,
				":nroorden" => $nroorden,
				":nropago" => $nropago);

		DBExecSql($conn, $sqlUpdate, $params);                  
		//----------------------------------------------------------------------    
		DBCommit($conn);
		return true;
	}catch (Exception $e) {
		DBRollback($conn);        
		//ErrorConeccionDatos($e->getMessage());		return false;
		throw new Exception($e->getMessage());
	}   
}

function ObtenerAcuerdosABM($nroorden, $nropago){
	try{
		global $conn;      
		
		$sql = "SELECT CA_MONTO, CA_FECHAVENC, CA_FECHAPAGO, CA_OBSERVACIONES, CA_TIPO, CA_FECHAEXTINCION  
					FROM lca_acuerdocyq  
					WHERE ca_nroorden =  :nroorden
					 AND ca_nropago =  :nropago ";
					 
		$params = array(":nroorden" => $nroorden,
						":nropago" => $nropago);			

		@$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){		
			return $row;		
		}

		if(!isset($row)){
			$row = array("CA_MONTO" => '', 				 
						"CA_FECHAVENC" => '', 				 
						"CA_FECHAPAGO" => '', 				 
						"CA_OBSERVACIONES" => '', 				 
						"CA_TIPO" => '', 				 
						"CA_FECHAEXTINCION" => '');
		}
		return $row;		
	}catch (Exception $e) {
        DBRollback($conn);                
		//ErrorConeccionDatos($e->getMessage()); 		return false;
		throw new Exception($e->getMessage());
    }     						
}

function InsertarCuotas($txtfecha1, $cantcuota, $tiempo, $txtmonto, $usuario, $nroorden, $cmbTipo){
    try{
		global $conn;      
		$monto = Getfloat($txtmonto);		
		/*
		$sqlSP = "Begin ART.LEGALES.Do_PlanCyQ(:txtfecha1,:cantcuota,:tiempo,:monto,:nroorden,:usuario,:cmbTipo); End;";
		
		$params = array(":txtfecha1" => SqlDate($txtfecha1),
				":cantcuota" => $cantcuota,
				":tiempo" => $tiempo,
				":monto" => $monto,
				":nroorden" => $nroorden,
				":usuario" => $usuario,
				":cmbTipo" => $cmbTipo );
		*/
		
		$sqlSP="Begin ART.LEGALES.Do_PlanCyQ(".SqlDate($txtfecha1).",	$cantcuota, $tiempo, $monto, $nroorden, '$usuario', '$cmbTipo'); End;";		
		$params=array();
		$curs=null;
		DBExecSP($conn, $curs, $sqlSP, $params, false);		
   //----------------------------------------------------------------------    
		DBCommit($conn);
		return true;
    }catch (Exception $e) {
        DBRollback($conn);                
		//ErrorConeccionDatos($e->getMessage());
		throw new Exception($e->getMessage());				
    }   
}

function UpdateAcuerdos($nroorden, $nropago, $usuario){
	try{
		global $conn;      
		$sqlNro = "SELECT IIF_Compara( '>', NVL(Min(CA_NROPAGO), 0), 0, -1, NVL(Min(CA_NROPAGO), 0) - 1) 
				FROM LCA_ACUERDOCYQ 
				WHERE CA_NROORDEN = :nroorden ";
				
		$params = array(":nroorden" => $nroorden);		
		$nro_pago = ValorSql($sqlNro, "", $params);
		////////////		
		$sqlUpdate = "UPDATE lca_acuerdocyq 
						SET ca_nropago = :nro_pago, 
							ca_usumodif = :usuario, 
							ca_fechamodif = SYSDATE 
					  WHERE ca_nroorden = :nroorden
						AND ca_nropago = :nropago";
					  
	   $params = array(":nro_pago" => $nro_pago,
					":usuario" => $usuario,
					":nroorden" => $nroorden,
					":nropago" => $nropago);
		
		DBExecSql($conn, $sqlUpdate, $params);                  
	   //----------------------------------------------------------------------    
		DBCommit($conn);
		return true;
    }catch (Exception $e) {
        DBRollback($conn);        
		//ErrorConeccionDatos($e->getMessage());		return false;
		throw new Exception($e->getMessage());
    }   
}

function UpdateConcursoyquiebras($txtsindico, 
	$txtdireccion, $txtlocaclidad, $txtfuero, 
	$txttelefono, $txtjurisdiccion, $txtjuzgado, 
	$txtsecretaria, $fechaconcurso, $fechaquiebra, 
	$fechaart32, $fechaart200, $fverificacioncredito, 
	$usuario, $nroorden, $montoprivilegio, $montoquirografario){
	
	try{
		global $conn;      
		
		$montoprivilegio = Getfloat($montoprivilegio);
		$montoquirografario = Getfloat($montoquirografario);
		
		$sqlUpdate ="UPDATE art.lcq_concyquiebra 
					SET cq_sindico =  :txtsindico,
						cq_direccionsind =  :txtdireccion,
						cq_localidadsind =  :txtlocaclidad,
						cq_telefonosind =  :txttelefono,
						cq_fuero =  :txtfuero,
						cq_jurisdiccion =  :txtjurisdiccion,
						cq_juzgado =  :txtjuzgado,
						cq_montoprivilegio =  :montoprivilegio, 
						cq_montoquirog =  :montoquirografario, 
						cq_secretaria =  :txtsecretaria, ";
						
		if( trim($fechaconcurso) != '' ) {
		  $sqlUpdate .= " cq_fechaconcurso = ".SqlDate($fechaconcurso) .", "; }
		else{
		  $sqlUpdate .= " cq_fechaconcurso = NULL , ";}
		  
		if( trim($fechaquiebra) != '' ) {
		  $sqlUpdate .= " cq_fechaquiebra = ".SqlDate($fechaquiebra)." , ";}
		else{
		  $sqlUpdate .= " cq_fechaquiebra = NULL , ";}
		  
		if( trim($fechaart32) != '' ) {
		  $sqlUpdate .= " cq_fechavtoart32 = ".SqlDate($fechaart32)." , ";}
		else{
		  $sqlUpdate .= " cq_fechavtoart32 = NULL , ";}
		  
		if( trim($fechaart200) != '' ) {
		  $sqlUpdate .= " cq_fechavtoart200 = ".SqlDate($fechaart200)." , ";}
		else{
		  $sqlUpdate .= " cq_fechavtoart200 = NULL , ";}
		  
		if( trim($fverificacioncredito) != '' ) {
		  $sqlUpdate .= " cq_fechaverificacioncredito = ".SqlDate($fverificacioncredito)." , ";}
		else
		  $sqlUpdate .= " cq_fechaverificacioncredito = NULL , ";
		  
		$sqlUpdate .= "cq_usumodif = :usuario , 
						cq_fechamodif = SYSDATE  
						WHERE cq_nroorden = :nroorden";	
						
	    $params = array(":txtsindico" => $txtsindico,
				":txtdireccion" => $txtdireccion,
				":txtlocaclidad" => $txtlocaclidad,
				":txttelefono" => $txttelefono,
				":txtfuero" => $txtfuero,
				":txtjurisdiccion" => $txtjurisdiccion,
				":txtjuzgado" => $txtjuzgado,
				":montoprivilegio" =>  $montoprivilegio, 
				":montoquirografario" => $montoquirografario,
				":txtsecretaria" => $txtsecretaria,
				":usuario" => $usuario,
				":nroorden" => $nroorden);				
				
		@DBExecSql($conn, $sqlUpdate, $params);                  
	   //----------------------------------------------------------------------    
		DBCommit($conn);
		return true;
    }catch (Exception $e) {
        DBRollback($conn);        		
		//ErrorConeccionDatos($e->getMessage());		//return false;
		throw new Exception($e->getMessage());
    } 
}

function  ObtenerValidacionFechaAcuerdoModif($fechavenc, $nroorden, $nropago){ 
	try{
		global $conn;      
		
		$sql = "SELECT DISTINCT CA_NROORDEN
				  FROM lca_acuerdocyq 
				 WHERE ca_nroorden = :nroorden
				   AND TO_CHAR(:fechavenc) 
						IN ( SELECT ca_fechavenc 
							  FROM lca_acuerdocyq 
							 WHERE ca_nroorden = :nroorden
							   AND ca_nropago > 0 
							   AND ca_nropago <> :nropago )";
					 
		$params = array(":fechavenc" => $fechavenc,
						":nroorden" => $nroorden,
						":nropago" => $nropago);			

		$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){		
			 return $row["CA_NROORDEN"];
		}	
		
		if(!isset($row)){return ''; }			
	}catch (Exception $e) {
        DBRollback($conn);                
		//ErrorConeccionDatos($e->getMessage());		return false;
		throw new Exception($e->getMessage());
    }     						
}

function ObtenerValidacionFechaAcuerdo($fechavenc, $nroorden){
	try{
		global $conn;      
		
		$sql = "SELECT distinct CA_NROORDEN 
			   FROM lca_acuerdocyq 
			  WHERE ca_nroorden = :nroorden
				AND :fechavenc IN (SELECT ca_fechavenc 
									 FROM lca_acuerdocyq 
									WHERE ca_nroorden = :nroorden
									  AND ca_nropago >0 )";
												 
		$params = array(":fechavenc" => $fechavenc,
						":nroorden" => $nroorden);			
						
		$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){		
			return $row;		
		}

		if(!isset($row)){
			$row = array("CA_NROORDEN" => '');
		}
		return $row;		
	}catch (Exception $e) {
        DBRollback($conn);                
		//ErrorConeccionDatos($e->getMessage());		return false;
		throw new Exception($e->getMessage());
    }     						
} 

function ObtenerValidacionFechaCuotas($fechavenc,$cantcuotas, $tiempo, $nroorden){
	try{
		global $conn;      
				
		$sql= "SELECT art.legales.do_validar_cuotas( ".
				 SqlDate($fechavenc).', '.
				 $cantcuotas.', '.
				 $tiempo.', '.
				 $nroorden.' ) valor '.
				" FROM dual";

		$params = array();									
		$resultado = ValorSql($sql, "", $params);	
		return $resultado;			
	}catch (Exception $e) {              		
		//ErrorConeccionDatos($e->getMessage());
		throw new Exception($e->getMessage());
    } 
} 