<?php
if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/CrearLog.php");

if (isset($_REQUEST['FUNCION'])){
    if ($_REQUEST['FUNCION']=="ObtenerPeritos"){
        //ObtenerPeritosNombre($Nombre, $Apellido , $tipoPericia ){
        $Nombre = '';
        $Apellido  = '';
        $tipoPericia = '';
         
        if (isset($_REQUEST["Nombre "])) {
            $Nombre = $_REQUEST["Nombre "];    
        }        

        if (isset($_REQUEST["Apellido"])) {
            $Apellido = $_REQUEST["Apellido"];
        }         
        
        if (isset($_REQUEST["tipoPericia"])) {
            $tipoPericia = $_REQUEST["tipoPericia"];
        }                        
       
        $datos = ObtenerPeritosNombre($Nombre, $Apellido , $tipoPericia);                 
        $result = utf8_encode($datos);
        echo $result;
    }    
}


if (isset($_REQUEST['FUNCION'])){
    if ($_REQUEST['FUNCION']=="CargarFuero"){
        //CargarFuero($JT_IDJURISDICCION, $JT_IDFUERO
        $idfuero = '0';
        if (isset($_REQUEST["Fuero"])) {
            $idfuero = $_REQUEST["Fuero"];    
        }        
        $jurisdiccion = '0';
        if (isset($_REQUEST["Juridiccion"])) {
            $jurisdiccion = $_REQUEST["Juridiccion"];
        }                
       
        $datos = CargarFuero($jurisdiccion, $idfuero, FALSE);                 
        $result = utf8_encode($datos);
        echo $result;
    }    
}

if (isset($_REQUEST['FUNCION'])){
    if ($_REQUEST['FUNCION']=="CargarJuzgado"){
        
        $idjurisdiccion = '0';
        if (isset($_REQUEST["jurisdiccion"])) {
            $idjurisdiccion = $_REQUEST["jurisdiccion"];
        }
        
        $idFuero = '0';
        if (isset($_REQUEST["Fuero"])) {
            $idFuero= $_REQUEST["Fuero"];
        }
        
        $idJuzgado = '0';
        if (isset($_REQUEST["Juzgado"])) {
            $idJuzgado= $_REQUEST["Juzgado"];    
        }
       
        //CargarJuzgado($JT_IDJURISDICCION, $JT_IDFUERO, $JT_IDJUZGADO, FALSE);        
        $result = utf8_encode(CargarJuzgado($idjurisdiccion, $idFuero, $idJuzgado, FALSE));         
        echo $result;
    }    
} 


if (isset($_REQUEST['FUNCION'])){
    if ($_REQUEST['FUNCION']=="CargarSecretaria"){
        
        $idJuzgado= '0';
        if (isset($_REQUEST["Juzgado"])) {
            $idJuzgado= $_REQUEST["Juzgado"];    
        }        
        
        $idSecretaria = '0';
        if (isset($_REQUEST["Secretaria"])) {
            $idSecretaria = $_REQUEST["Secretaria"];
        }                
       //CargarSecretaria($idJuzgado, $idSecretaria)
        $datos = CargarSecretaria($idJuzgado, $idSecretaria, FALSE);                 
        $result = utf8_encode($datos);
        echo $result;
    }    
}


function ObtenerDatosDeJuicio($nrojuicio, $idestudio, $usuarioweb){
	global $conn;		
	
	$sql = "SELECT 
			JU_DESCRIPCION, 
			FU_DESCRIPCION, 
			JZ_DESCRIPCION, 
			JZ_IDINSTANCIA, 
			SC_DESCRIPCION,
			IN_DESCRIPCION, 
			EJ_DESCRIPCION, 
			BO_NOMBRE, 
			JT_CARATULA, 
			JT_IDFUERO,
			JT_IDJUZGADO, 
			JT_IDSECRETARIA, 
			JT_DEMINTERRUPTIVA, 
			JT_IDJURISDICCION,
			JT_IDABOGADO, 
			JT_IDESTADO, 
			JT_FECHAINIJUICIO, 
			JT_REGISTRACION,
			JT_IDTIPO, 
			JT_FECHAFINJUICIO, 
			JT_RESULTADO, 			   
			NVL2(JT_NROEXPEDIENTE,JT_NROEXPEDIENTE || '/' || JT_ANIOEXPEDIENTE,'') JT_EXPEDIENTE,
			JT_NROEXPEDIENTE,
			JT_ANIOEXPEDIENTE,
			JT_FECHAASIGN, 
			JT_FECHANOTIFICACIONJUICIO, 
			JT_DESCRIPCION,
			JT_FECHAALTA, 
			JT_USUALTA, 
			JT_FECHAMODIF, 
			JT_USUMODIF, 
			JT_FECHABAJA,
			JT_USUBAJA, 
			JT_FECHAINIJUICIO, 
			JT_ID, 
			JT_BLOQUEADO,
			TJ_DESCRIPCION AS TIPOJUICIO, 
			JT_NUMEROCARPETA, 
			JT_FECHAINGRESORAJ,
			JT_CONDICIONDENOSEGURO, 
			JT_NUMEROORDENRAJ, 
			EJ_ID,
			NVL (JT_DEMANDANTE,'')||'C/'|| NVL (JT_DEMANDADO,'') ||' ' || JT_CARATULA AS DESCRIPCARATULA,
				(SELECT MAX (IJ_ID)  FROM LEGALES.LIJ_INSTANCIAJUICIOENTRAMITE  WHERE IJ_IDJUICIOENTRAMITE = JT_ID) IJ_ID  
			FROM legales.ljz_juzgado,
			legales.lju_jurisdiccion,
			legales.lin_instancia,
			legales.lfu_fuero,
			legales.lsc_secretaria,
			legales.ljt_juicioentramite,
			legales.lej_estadojuicio,
			legales.lbo_abogado,
			legales.ltj_tipojuicio  
			WHERE jz_idfuero = fu_id  
			AND jz_idjurisdiccion = ju_id  
			AND jz_idinstancia = in_id  
			AND jz_id = sc_idjuzgado  
			AND ju_id = jt_idjurisdiccion  
			AND fu_id = jt_idfuero  
			AND sc_id = jt_idsecretaria  
			AND jt_idestado = ej_id  
			AND jt_idabogado = bo_id  
			AND tj_id = jt_idtipo  
			AND jt_fechabaja IS NULL
			AND (art.weblegales.pertencealestudio (jt_idabogado,:idestudio,:usuarioweb)='S')
			AND (jt_estadomediacion LIKE'%J%' OR jt_estadomediacion LIKE'%A%')  
			AND jt_id = :nrojuicio ";

	//nrojuicio, idestudio, usuarioweb	
	$params = array(":nrojuicio" => $nrojuicio, 
					":idestudio" => $idestudio, 
					":usuarioweb" => $usuarioweb);
					
	$stmt = DBExecSql($conn, $sql, $params);
	
	while ($row = DBGetQuery($stmt)) {		
		return $row;		
	}
	
		
	if(!isset($row)){
		$row = array("JU_DESCRIPCION" => '',
					"FU_DESCRIPCION" => '',
					"JZ_DESCRIPCION" => '',
					"JZ_IDINSTANCIA" => '',
					"SC_DESCRIPCION" => '',			   
					"IN_DESCRIPCION" => '',
					"EJ_DESCRIPCION" => '',
					"BO_NOMBRE" => '',
					"JT_CARATULA" => '',
					"JT_IDFUERO" => '',
					"JT_IDJUZGADO" => '',
					"JT_IDSECRETARIA" => '',
					"JT_DEMINTERRUPTIVA" => '',
					"JT_IDJURISDICCION" => '',
					"JT_IDABOGADO" => '',
					"JT_IDESTADO" => '',
					"JT_FECHAINIJUICIO" => '',
					"JT_REGISTRACION" => '',
					"JT_IDTIPO" => '',
					"JT_FECHAFINJUICIO" => '',
					"JT_RESULTADO" => '',			   
					"JT_EXPEDIENTE" => '',			   
					"JT_NROEXPEDIENTE" => '',			   
					"JT_ANIOEXPEDIENTE" => '',			   
					"JT_FECHAASIGN" => '',
					"JT_FECHANOTIFICACIONJUICIO" => '',
					"JT_DESCRIPCION" => '',			   
					"JT_FECHAALTA" => '',
					"JT_USUALTA" => '',
					"JT_FECHAMODIF" => '',
					"JT_USUMODIF" => '',
					"JT_FECHABAJA" => '',			   
					"JT_USUBAJA" => '',
					"JT_FECHAINIJUICIO" => '',
					"JT_ID" => '',
					"JT_BLOQUEADO" => '',			   
					"TJ_DESCRIPCION AS TIPOJUICIO" => '',
					"JT_NUMEROCARPETA" => '',
					"JT_FECHAINGRESORAJ" => '',			   
					"JT_CONDICIONDENOSEGURO" => '',
					"JT_NUMEROORDENRAJ" => '',
					"EJ_ID" => '',
					"IJ_ID" => '');	
					
		return $row;		
	}
		
}


function CargarFuero($jurisdiccion, $idfuero, $selectid=true){
	global $conn;			
	$sql = "SELECT FU_ID, FU_DESCRIPCION
			   FROM legales.lfu_fuero 
			   WHERE ( fu_fechabaja IS NULL OR fu_id = :idfuero)
			   AND fu_id IN (SELECT jz_idfuero 
								 FROM legales.ljz_juzgado 
								WHERE jz_idjurisdiccion = :jurisdiccion)
			 ORDER BY fu_descripcion";
		
	$result = AddComboOption('0', '', !$selectid);		
	
	$params = array(":idfuero" => $idfuero,
					":jurisdiccion" => $jurisdiccion); 	
					
	$stmt = DBExecSql($conn, $sql, $params);

	while ($row = DBGetQuery($stmt, 1, false)) {
        $result .= CargarComboOpt($row["FU_ID"], $row["FU_DESCRIPCION"], $idfuero, $selectid);
	}	

	return $result; 		 
}


function CargarJurisdiccion( $idjurisdiccion, $selectid=true){
	global $conn;			
	$sql = " SELECT JU_ID, JU_DESCRIPCION 
				FROM legales.lju_jurisdiccion 
				WHERE ju_fechabaja is null OR ju_id = :idjurisdiccion
				ORDER BY ju_descripcion";
		
	$result = AddComboOption('0', '', !$selectid);		
	
	$params = array(":idjurisdiccion" => $idjurisdiccion); 	
	$stmt = DBExecSql($conn, $sql, $params);

	while ($row = DBGetQuery($stmt, 1, false)) {
	    $result .= CargarComboOpt($row["JU_ID"], $row["JU_DESCRIPCION"], $idjurisdiccion, $selectid);			
	}				 		 
	return $result; 		 
	
}

function CargarJuzgado($idjurisdiccion, $idfuero, $idJuzgado, $selectid=true){
	global $conn;			
	$sql = "SELECT JZ_ID, JZ_DESCRIPCION 
			   FROM legales.ljz_juzgado 
			  WHERE (jz_fechabaja IS NULL OR jz_id = :idJuzgado)
				AND jz_idjurisdiccion = :idjurisdiccion
				AND jz_idfuero = :idfuero
			 ORDER BY jz_descripcion";
		
	$result = AddComboOption('0', '', !$selectid);		
	
	$params = array(":idJuzgado" => $idJuzgado,
					":idjurisdiccion" => $idjurisdiccion,
					":idfuero" => $idfuero); 	
					
	$stmt = DBExecSql($conn, $sql, $params);

	while ($row = DBGetQuery($stmt, 1, false)){
		$result .= CargarComboOpt($row["JZ_ID"], $row["JZ_DESCRIPCION"], $idJuzgado, $selectid);							
	}
    	
	return $result; 		 	
} 

function CargarSecretaria($idJuzgado, $idSecretaria){
	global $conn;
	
	$sql = " SELECT SC_ID, SC_DESCRIPCION 
			   FROM legales.lsc_secretaria 
			  WHERE (sc_fechabaja IS NULL OR sc_id = :idSecretaria)
				AND sc_idjuzgado =  :idJuzgado
			 ORDER BY sc_descripcion ";
				
	$result = AddComboOption('0', '', true);		
	
	$params = array(":idJuzgado" => $idJuzgado,
					":idSecretaria" => $idSecretaria); 	
					
	$stmt = DBExecSql($conn, $sql, $params);
	$selectid = FALSE;

	while ($row = DBGetQuery($stmt, 1, false)){
	    $result .= CargarComboOpt($row["SC_ID"], $row["SC_DESCRIPCION"], $idSecretaria, $selectid);							
	}	
	return $result; 		 
}

function CargarMotivo(){
    global $conn;           
    $sql = "SELECT MC_ID, MC_DESCRIPCION, MC_RELACIONNUEVOJUZGADO 
              FROM legales.lmc_motivocambiojuzgado 
             WHERE mc_fechabaja IS NULL AND mc_id > 1 AND mc_etapa LIKE '%J%' 
          ORDER BY mc_descripcion  ";
        
    $result = AddComboOption('0', '', true);        
    
    $params = array();  
    $stmt = DBExecSql($conn, $sql, $params);
    $selectid = FALSE;
    
    while ($row = DBGetQuery($stmt, 1, false)) {
        $result .= CargarComboOpt($row["MC_ID"], $row["MC_DESCRIPCION"], 0, $selectid);           
                    
    }                        
    return $result;          
    
}

function ObtenerInstanciaSeleccionada($idjurisdiccion, $idfuero, $idjuzgado){
	//version original
	global $conn;			
	$sql = "SELECT  JZ_ID AS ID, 
				JZ_ID AS CODIGO, 
				JZ_DESCRIPCION AS DESCRIPCION, 
				JZ_FECHABAJA AS BAJA, 
				JZ_IDINSTANCIA, 
				IN_DESCRIPCION, 
				NVL(JZ_DIRECCION, '') 
			 FROM legales.ljz_juzgado, legales.lin_instancia 
			WHERE (in_id = jz_idinstancia) 
			  AND jz_idjurisdiccion = :idjurisdiccion
			  AND jz_idfuero =  :idfuero      
			  AND jz_id =  :idjuzgado
		ORDER BY descripcion ";
		
	$params = array(":idjurisdiccion" => $idjurisdiccion, 
					":idfuero" => $idfuero, 
					":idjuzgado" => $idjuzgado);
					
	$stmt = DBExecSql($conn, $sql, $params);
	
	while ($row = DBGetQuery($stmt)) {      	
		return $row;		
	}			
	
	if(!isset($row)){
		$row = array("ID" => '',
					"CODIGO" => '',
					"DESCRIPCION" => '',
					"BAJA" => '',
					"JZ_IDINSTANCIA" => '',
					"IN_DESCRIPCION" => '',
					"JZ_DIRECCION" => '');		
		return $row;		
	}
	
}

function DatosJuicio($juicio){
	global $conn;      

	$sql =" SELECT JT_NUMEROCARPETA, 
					NVL(JT_DEMANDANTE, '') || ' C/ ' || NVL(JT_DEMANDADO, '') || ' ' || JT_CARATULA AS DESCRIPCARATULA, 
					EJ_DESCRIPCION 
				FROM legales.ljt_juicioentramite, legales.lej_estadojuicio 
				WHERE jt_idestado = ej_id 
				AND jt_id = :juicio";
                         
	if (isset($_REQUEST["NroJuicio"]))
		$juicio = $_REQUEST["NroJuicio"];				
		
		$params = array(":juicio" => $juicio );
		$stmt = DBExecSql($conn, $sql, $params);
	
	while ($row = DBGetQuery($stmt)) {		
		return $row;		
	}
	
		if(!isset($row)){
		$row = array("JT_NUMEROCARPETA" => '',
					"DESCRIPCARATULA" => '',
					"EJ_DESCRIPCION" => '');		
		return $row;		
	}
	
}

function ObtenerInstanciaModificar( $nroInstancia ){
    global $conn;       
    
    $sql = "SELECT 
        IJ_IDJURISDICCION OIM_IJ_IDJURISDICCION, 
        JU_DESCRIPCION OIM_JU_DESCRIPCION, 
        IJ_IDFUERO OIM_IJ_IDFUERO, 
        FU_DESCRIPCION OIM_FU_DESCRIPCION, 
        IJ_IDJUZGADO OIM_IJ_IDJUZGADO, 
        JZ_DESCRIPCION OIM_JZ_DESCRIPCION, 
        IJ_IDSECRETARIA OIM_IJ_IDSECRETARIA, 
        SC_DESCRIPCION OIM_SC_DESCRIPCION, 
        IJ_IDMOTIVOCAMBIOJUZGADO OIM_IJ_IDMOTIVOCAMBIOJUZGADO, 
        MC_DESCRIPCION OIM_MC_DESCRIPCION, 
        IJ_FECHATRASPASO OIM_IJ_FECHATRASPASO, 
        IJ_OBSERVACIONES OIM_IJ_OBSERVACIONES, 
        IJ_NROEXPEDIENTE OIM_IJ_NROEXPEDIENTE,
        IJ_ANIOEXPEDIENTE OIM_IJ_ANIOEXPEDIENTE, 
        IN_DESCRIPCION OIM_IN_DESCRIPCION
   FROM legales.lij_instanciajuicioentramite, 
        legales.lin_instancia, 
        legales.lfu_fuero, 
        legales.ljz_juzgado, 
        legales.lju_jurisdiccion, 
        legales.lmc_motivocambiojuzgado, 
        legales.lsc_secretaria 
  WHERE in_id = ij_idinstancia 
    AND ij_idfuero = fu_id 
    AND ij_idjuzgado = jz_id 
    AND ij_idjurisdiccion = ju_id 
    AND ij_idsecretaria = sc_id 
    AND ij_idmotivocambiojuzgado = mc_id 
    AND ij_id =  :nroInstancia ";
 
                             
    if (isset($_REQUEST["nroInstancia"]))
        $juicio = $_REQUEST["nroInstancia"];               
        
    $params = array(":nroInstancia" => $nroInstancia );
    $stmt = DBExecSql($conn, $sql, $params);

    while ($row = DBGetQuery($stmt)) {      
        return $row;        
    }
    
    if(!isset($row)){
        $row = array("OIM_IJ_IDJURISDICCION" => '', 
                    "OIM_JU_DESCRIPCION" => '', 
                    "OIM_IJ_IDFUERO" => '', 
                    "OIM_FU_DESCRIPCION" => '', 
                    "OIM_IJ_IDJUZGADO" => '', 
                    "OIM_JZ_DESCRIPCION" => '', 
                    "OIM_IJ_IDSECRETARIA" => '', 
                    "OIM_SC_DESCRIPCION" => '', 
                    "OIM_IJ_IDMOTIVOCAMBIOJUZGADO" => '', 
                    "OIM_MC_DESCRIPCION" => '', 
                    "OIM_IJ_FECHATRASPASO" => '', 
                    "OIM_IJ_OBSERVACIONES" => '', 
                    "OIM_IJ_NROEXPEDIENTE" => '',
                    "OIM_IJ_ANIOEXPEDIENTE" => '', 
                    "OIM_IN_DESCRIPCION" => '');        
                    
        return $row;        
    }
}


function ObtenerEstadoMediacion($NroJuicio){
    global $conn;       
    
    $sql = "SELECT JT_ESTADOMEDIACION 
            FROM legales.ljt_juicioentramite 
            WHERE jt_id= :NroJuicio";
            
    if (isset($_REQUEST["NroJuicio"]))
        $juicio = $_REQUEST["NroJuicio"];               
        
    $params = array(":NroJuicio" => $NroJuicio);
    
    $result = ValorSql($sql, "", $params);
    
    return $result;
}


function UpdateInstanciaAbmMod($JuicioEnTramite, $Jurisdiccion, $Fuero, $Juzgado, $Secretaria, 
    $Instancia, $NroExpediente, $AnioExpediente, $Motivo, 
    $Detalle, $LoginName, $nroInstancia, $EstadoMediacion, $FechaIngreso){
        
    global $conn;      
    try{

   $sqlUpdate_lij ="UPDATE legales.lij_instanciajuicioentramite
                    SET ij_nroexpediente = :NroExpediente,
                        ij_anioexpediente = :AnioExpediente,
                        ij_fechatraspaso = :FechaIngreso,
                        ij_usumodif = :LoginName,
                        ij_idjurisdiccion = :Jurisdiccion,
                        ij_idfuero = :Fuero,
                        ij_idjuzgado = :Juzgado,
                        ij_idsecretaria = :Secretaria,
                        ij_observaciones = :Detalle, 
                        ij_idmotivocambiojuzgado = :Motivo, 
                        ij_idinstancia = :Instancia, 
                        ij_fechamodif = sysdate
                  WHERE ij_id = :nroInstancia";
                  
   $params = array(":NroExpediente" => $NroExpediente,
                ":AnioExpediente" => $AnioExpediente,
                ":FechaIngreso" => $FechaIngreso,
                ":LoginName" => $LoginName,
                ":Jurisdiccion" => $Jurisdiccion,
                ":Fuero" => $Fuero,
                ":Juzgado" => $Juzgado,
                ":Secretaria" => $Secretaria,
                ":Detalle" => $Detalle,
                ":Motivo" => $Motivo,
                ":Instancia" => $Instancia,
                ":nroInstancia" => $nroInstancia );
    
    DBExecSql($conn, $sqlUpdate_lij, $params);
   //----------------------------------------------------------------------
   $sqlUpdate_ltj = "UPDATE legales.ljt_juicioentramite 
                    SET jt_idjurisdiccion = :Jurisdiccion,
                        jt_idfuero = :Fuero,
                        jt_idjuzgado = :Juzgado, 
                        jt_idsecretaria = :Secretaria, 
                        jt_nroexpediente = :NroExpediente, 
                        jt_anioexpediente = :AnioExpediente, 
                        jt_fechaingreso = :FechaIngreso, 
                        jt_usumodif = :LoginName, 
                        jt_estadomediacion = :EstadoMediacion, 
                        jt_fechamodif = Sysdate 
                  WHERE jt_id = :JuicioEnTramite";
                  
   $params = array(":Jurisdiccion" => $Jurisdiccion,
                ":Fuero" => $Fuero,
                ":Juzgado" => $Juzgado,
                ":Secretaria" => $Secretaria,
                ":NroExpediente" => $NroExpediente,
                ":AnioExpediente" => $AnioExpediente,
                ":FechaIngreso" => $FechaIngreso,
                ":LoginName" => $LoginName,
                ":EstadoMediacion" => $EstadoMediacion,
                ":JuicioEnTramite" => $JuicioEnTramite );
    
    DBExecSql($conn, $sqlUpdate_ltj, $params);                  
   //----------------------------------------------------------------------    
    DBCommit($conn);
    }
    catch (Exception $e) {
        DBRollback($conn);        
         throw new Exception($e->getMessage());
    }                            
}

function UpdateInstanciaABMAlta($JuicioEnTramite, $Jurisdiccion, $Fuero, $Juzgado, $Secretaria, $Instancia, 
	$NroExpediente, $AnioExpediente, $Motivo, $Detalle, $LoginName, $EstadoMediacion, $FechaIngreso){

    global $conn;      
    try{

	$sqlInsert_lij = "INSERT INTO legales.lij_instanciajuicioentramite (
			               ij_id, 
			               ij_idjuicioentramite, ij_idjurisdiccion,  
			               ij_idfuero, ij_idjuzgado, ij_idsecretaria, ij_idinstancia, 
			               ij_nroexpediente, ij_anioexpediente, ij_fechatraspaso, 
			               ij_idmotivocambiojuzgado, ij_observaciones, ij_usualta,    
			               ij_fechaalta)  
			      VALUES (LEGALES.SEQ_LIJ_ID.NEXTVAL, 
							:JuicioEnTramite,
							:Jurisdiccion,
							:Fuero,
							:Juzgado,
							:Secretaria,
							:Instancia,
							:NroExpediente,
							:AnioExpediente,
							:FechaIngreso,
							:Motivo,
							:Detalle,
							:LoginName,
							Sysdate)";                 

 	$params = array(":JuicioEnTramite" => $JuicioEnTramite,
	            ":Jurisdiccion" => $Jurisdiccion,
				":Fuero" => $Fuero,
				":Juzgado" => $Juzgado,
				":Secretaria" => $Secretaria,
				":Instancia" => $Instancia,
				":NroExpediente" => $NroExpediente,
				":AnioExpediente" => $AnioExpediente,
				":FechaIngreso" => $FechaIngreso,
				":Motivo" => $Motivo,
				":Detalle" => $Detalle,
				":LoginName" => $LoginName);	
				
	DBExecSql($conn, $sqlInsert_lij, $params);
							
   //----------------------------------------------------------------------    
   
	$sqlUpdJuicioTramite="UPDATE legales.ljt_juicioentramite ljt 
	    SET jt_importesentencia = NULL, 
	        jt_importecapital = NULL, 
	        jt_importetasajusticia = NULL, 
	        jt_importehonorarios = NULL, 
	        jt_idtiporesultadosentencia = NULL, 
	        jt_fechasentencia = NULL, 
	        jt_fecharecepsentencia = NULL, 
	        jt_detallesentencia = NULL, 
	        jt_interesesSentencia = NULL 
	  WHERE ljt.jt_id =  :JuicioEnTramite";
	  
 	$params = array(":JuicioEnTramite" => $JuicioEnTramite);	  
 	
 	DBExecSql($conn, $sqlUpdJuicioTramite, $params);
   //----------------------------------------------------------------------      
   $sqlUpdateReclamo = " UPDATE legales.lrt_reclamojuicioentramite 
				    SET rt_montosentencia = NULL, 
				        rt_porcentajesentencia = NULL, 
				        rt_usumodif =  :LoginName, 
				        rt_fechamodif = SysDate 
				  WHERE rt_idjuicioentramite =  :JuicioEnTramite";

 	$params = array(":LoginName" => $LoginName,
				 	":JuicioEnTramite" => $JuicioEnTramite);	  
 	
 	DBExecSql($conn, $sqlUpdateReclamo , $params);				  
   //----------------------------------------------------------------------         
   $sqlUpdateJuiTramite="UPDATE legales.ljt_juicioentramite 
			    SET jt_idjurisdiccion = :Jurisdiccion, 
			        jt_idfuero = :Fuero, 
			        jt_idjuzgado = :Juzgado, 
			        jt_idsecretaria = :Secretaria, 
			        JT_NROEXPEDIENTE = :NroExpediente, 
			        JT_ANIOEXPEDIENTE = :AnioExpediente, 
			        jt_fechaingreso = :FechaIngreso, 
			        jt_usumodif = :LoginName, 
			        jt_estadomediacion = :EstadoMediacion, 
			        jt_fechamodif = Sysdate 
			  WHERE jt_id = :JuicioEnTramite" ;
			  
 	$params = array(":Jurisdiccion" => $Jurisdiccion,
					":Fuero" => $Fuero,
					":Juzgado" => $Juzgado,
					":Secretaria" => $Secretaria,
					":NroExpediente" => $NroExpediente,
					":AnioExpediente" => $AnioExpediente,
					":FechaIngreso" => $FechaIngreso, 
					":LoginName" => $LoginName,
					":EstadoMediacion" => $EstadoMediacion,
					":JuicioEnTramite" => $JuicioEnTramite);	  
 	
 	DBExecSql($conn, $sqlUpdateJuiTramite, $params);				  
			  
    DBCommit($conn);
    }
    catch (Exception $e) {
        DBRollback($conn);        
    }                          
					
}



function ObtenerMasDatosJuicios($nrojuicio){
	global $conn;		

	$sql ="SELECT 
				LJZ_JUZGADO.JZ_DESCRIPCION, 
				LJU_JURISDICCION.JU_DESCRIPCION, 
				LJZ_JUZGADO.JZ_DIRECCION, 
				LJZ_JUZGADO.JZ_TELEFONO, 
				LFU_FUERO.FU_DESCRIPCION, 
				LSC_SECRETARIA.SC_DESCRIPCION, 
				LJZ_JUZGADO.JZ_FAX, 
				LJZ_JUZGADO.JZ_EMAIL
		   FROM legales.ljt_juicioentramite a, 
				legales.lju_jurisdiccion, 
				legales.ljz_juzgado, 
				legales.lfu_fuero, 
				legales.lsc_secretaria 
		  WHERE lju_jurisdiccion.ju_id = a.jt_idjurisdiccion 
			AND ljz_juzgado.jz_id = a.jt_idjuzgado 
			AND lfu_fuero.fu_id = a.jt_idfuero 
			AND lsc_secretaria.sc_id = a.jt_idsecretaria 
			AND a.jt_id = :nrojuicio ";

	$params = array(":nrojuicio" => $nrojuicio);					
		
	$stmt = DBExecSql($conn, $sql, $params);	
	while ($row = DBGetQuery($stmt)) {		
		return $row;		
	}
	
	if(!isset($row)){
		$row = array("JZ_DESCRIPCION" => '',
				"JU_DESCRIPCION" => '',
				"JZ_DIRECCION" => '',
				"JZ_TELEFONO" => '',
				"FU_DESCRIPCION" => '', 
				"SC_DESCRIPCION" => '',
				"JZ_FAX" => '',
				"JZ_EMAIL" => '');	
		return $row;		
	}		
}	
   
function ObtenerPeritosNombre($Nombre, $Apellido , $tipoPericia ){
	try{		
		global $conn;		
		
		$params = array();
		
		$sql ="SELECT pe_nombreindividual || ' ' || pe_apellido NOMBRE, pe_id ID 
			  FROM legales.lpe_perito 
			  WHERE pe_fechabaja IS NULL 
			  AND pe_apellido IS NOT NULL";
	  
		if ($Nombre <> '' ){
	    	$sql .= " AND UPPER(pe_nombreindividual) LIKE UPPER(:Nombre)";		
			$params = array(":Nombre" => $Nombre.'%');
	
		}
		
		if ($Apellido <> '' ){
	    	$sql .= " AND UPPER(pe_apellido) LIKE UPPER(:Apellido)";		
			$params = array(":Apellido" => $Apellido.'%');
		}
		
		if ( is_numeric($tipoPericia) and ($tipoPericia > '0' ))
	    	$sql .= " AND pe_idtipoperito = ".$tipoPericia;
	
		$sql .= ' ORDER BY 1 ';
	
		$result = '';		
	
		$stmt = DBExecSql($conn, $sql, $params);
	
		while ($row = DBGetQuery($stmt, 1, false)) {
	        $result .= CargarComboOpt($row["ID"], $row["NOMBRE"], $idfuero, $selectid);
		}	
		
		return $result; 		
	}
	catch(Exception $e) {			
		return $e->getMessage(); 		
	}
}

function ExistePeritaje($id, $usuario){
	    global $conn;       
    
    $sqlCheck = "SELECT 1  
					FROM legales.lpj_peritajejuicio  
					WHERE pj_fechabaja IS NULL  
					 AND pj_id =  :ID
					 AND pj_usualta = :usuario";
	        
       
    $params = array(":id" => $id, ":usuario" => $usuario);
    
    $result = ValorSql($sqlCheck, "", $params);
    
    return $result;

}

function UpdatePeritajes($id, $usuario){
	try{	
		global $conn;	
	    //Baja logica de peritaje
	    if( ExistePeritaje($id, $usuario) ) {
	    	$sqlUpdate = "UPDATE legales.lpj_peritajejuicio  
			       SET pj_fechabaja = SYSDATE,  
			           pj_usubaja = :usuario		     
			       WHERE pj_fechabaja IS NULL 
			       AND pj_id = :id ";
			       
			$params = array(":id" => $id, ":usuario" => $usuario);
	
		 	DBExecSql($conn, $sqlUpdate , $params);				  			    	
		    DBCommit($conn);    
	    }    
	
		return true;
    }
    catch (Exception $e) {
        DBRollback($conn);        
        return false;
    }
    
}


function ObtenerNroCarpeta($juicio){
	global $conn;		

	$sql =" SELECT JT_NUMEROCARPETA, 
					NVL(JT_DEMANDANTE, '') || ' C/ ' || NVL(JT_DEMANDADO, '') || ' ' || JT_CARATULA AS DESCRIPCARATULA, 
					EJ_DESCRIPCION 
				FROM legales.ljt_juicioentramite, legales.lej_estadojuicio 
				WHERE jt_idestado = ej_id 
				AND jt_id = :juicio";
		
	if( is_numeric($juicio) and ($juicio > '0') ) {
		$params = array(":juicio" => $juicio );
		$stmt = DBExecSql($conn, $sql, $params);		
	   	$row = DBGetQuery($stmt);
	   	
		return array( $row['JT_NUMEROCARPETA'], $row['DESCRIPCARATULA'], $row['EJ_DESCRIPCION']);
	}
	else
		return array('','','');

}


function InsertarPeritoNuevo($nombre, $apellido, $cuil, $tipoperito, $parteoficio, $usuario, $direccion){
    global $conn;      
    try{
	
		$secuencia = ValorSql("SELECT legales.seq_lpe_id.NEXTVAL FROM DUAL");
		
		$sqlInsert_Perito = "INSERT INTO legales.lpe_perito 
	            (PE_ID, PE_NOMBRE, PE_CUITCUIL, PE_IDTIPOPERITO, 
	             PE_PARTEOFICIO, PE_USUALTA, PE_FECHAALTA,PE_DIRECCION,  
	             PE_NOMBREINDIVIDUAL, PE_APELLIDO ) 
	     VALUES (:id, 
				 upper(:apellidonombre), 
				 :cuil, 
				 :tipoperito,
				 :parteoficio, 
				 :usuario, 
				 SYSDATE,
				 :direccion,
				 upper(:nombre),
				 upper(:apellido) )";
				 
	 	$params = array(":id" => $secuencia,
	 				":apellidonombre" => $apellido." ".$nombre,	            
	 				":cuil" => $cuil,	            
	 				":tipoperito" => $tipoperito,	            
	 				":parteoficio" => $parteoficio,	            
	 				":usuario" => $usuario,	            
	 				":direccion" => $direccion,	            
	 				":nombre" => $nombre,	            
					":apellido" => $apellido);			 
		
	 	DBExecSql($conn, $sqlInsert_Perito, $params);				  
				  
	    DBCommit($conn);
	    return $secuencia;
    }
    catch (Exception $e) {    	
        DBRollback($conn);
        return 0;        
    }                          
				
} 