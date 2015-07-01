<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");

@session_start(); 
//--------------------------------------------------------------
if (isset($_REQUEST['FUNCION'])){

    if ($_REQUEST['FUNCION']=="CargarEstadosJuicio"){
		
		if (isset($_REQUEST["seleccionado"])) {$seleccionado = $_REQUEST["seleccionado"];  }                		

        $datos = CargarEstado($seleccionado, true);		
        $result = utf8_encode($datos);		
        echo $result;
		
	}

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

    if ($_REQUEST['FUNCION']=="CargarJuzgado"){
        
        $idjurisdiccion = '0';
        $idFuero = '0';
        $idJuzgado = '0';
		
        if (isset($_REQUEST["jurisdiccion"])){$idjurisdiccion = $_REQUEST["jurisdiccion"];}        
        if (isset($_REQUEST["Fuero"])) {$idFuero= $_REQUEST["Fuero"];}        
        if (isset($_REQUEST["Juzgado"])) {$idJuzgado= $_REQUEST["Juzgado"];}
               
        $result = utf8_encode(CargarJuzgado($idjurisdiccion, $idFuero, $idJuzgado, FALSE));         
        echo $result;
    }    

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
	
	if ($_REQUEST['FUNCION']=="CargarRazonSocial"){
        
        $texto= '';
        if (isset($_REQUEST["texto"])) {
            $texto= $_REQUEST["texto"];    
        }                       
        $datos = CargarRSocial($texto);		
        $result = utf8_encode($datos);
        echo $result;
    }    
}
//--------------------------------------------------------------
function CargarTipoResultadoSentencia($cmbSentencia){
	global $conn;			
	
	$sql = "SELECT TR_ID, TR_DESCRIPCION, TR_ETAPA 
			   FROM legales.ltr_tiporesultadosentencia 
			  WHERE tr_etapa LIKE '%J%'
			  AND ( tr_id = :seleccionado or  tr_fechabaja is null)";			  	
	
	$params = array(":seleccionado" => $cmbSentencia);

	$stmt = DBExecSql($conn, $sql, $params);
	
	if($cmbSentencia == 0)
		$result = FL_AddComboOption('0', '', true);		
	else
		$result = FL_AddComboOption('0', '', false);		
	
	while ($row = DBGetQuery($stmt, 1, false)) {
        $result .= CargarComboOpt($row["TR_ID"], $row["TR_DESCRIPCION"], $cmbSentencia, true);
	}	

	return $result; 		 
}

function CargarEstado($seleccionado, $selectid=true){

	global $conn;			
	
	$sql = "SELECT EJ_ID, EJ_DESCRIPCION 
				FROM legales.lej_estadojuicio 
				WHERE ej_etapa LIKE '%J%' 
				AND EJ_ACTIVOWEB = 'S' 
				AND (ej_id = :seleccionado OR ej_fechabaja IS NULL)
				ORDER BY ej_descripcion";			  	
	
	$params = array(":seleccionado" => $seleccionado);	
	$stmt = DBExecSql($conn, $sql, $params);	
	$result = FL_AddComboOption('0', '', false);		
	
	while ($row = DBGetQuery($stmt, 1, false)) {
        $result .= CargarComboOpt($row["EJ_ID"], $row["EJ_DESCRIPCION"], $seleccionado, true);		
	}	
	
	return $result; 	
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
		
	$result = FL_AddComboOption('0', '', !$selectid);		
	
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
		
	$result = FL_AddComboOption('0', '', !$selectid);		
	
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
		
	$result = FL_AddComboOption('0', '', !$selectid);		
	
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
				
	$result = FL_AddComboOption('0', '', true);		
	
	$params = array(":idJuzgado" => $idJuzgado,
					":idSecretaria" => $idSecretaria); 	
					
	$stmt = DBExecSql($conn, $sql, $params);
	$selectid = true;

	while ($row = DBGetQuery($stmt, 1, false)){
	    $result .= CargarComboOpt($row["SC_ID"], $row["SC_DESCRIPCION"], $idSecretaria, $selectid);							
	}	
	return $result; 		 
}

function SqlMotivos(){
	$sql = "SELECT MC_ID, MC_DESCRIPCION, MC_RELACIONNUEVOJUZGADO 
		  FROM legales.lmc_motivocambiojuzgado 
		 WHERE mc_fechabaja IS NULL 
		   AND mc_id > 1 
		   AND mc_etapa LIKE '%J%' 
	  ORDER BY mc_descripcion";
	  
	return $sql;
}

function CargarMotivo(){
    global $conn;           
    $sql = SqlMotivos();
        
    $result = FL_AddComboOption('0', '', true);        
    
    $params = array();  
    $stmt = DBExecSql($conn, $sql, $params);
    $selectid = FALSE;
    
    while ($row = DBGetQuery($stmt, 1, false)) {
        $result .= CargarComboOpt($row["MC_ID"], $row["MC_DESCRIPCION"], 0, $selectid);
    }                        
    return $result;              
}


function CargarRSocial($texto){
	global $conn;           
    $sql = "SELECT EM_CUIT, EM_NOMBRE 
			  FROM AFI.AEM_EMPRESA 
			 WHERE EM_NOMBRE like UPPER(:texto)
		  ORDER BY EM_NOMBRE";
        
    $result = FL_AddComboOption('0', '', true);        
    
    $params = array(":texto" => "%".$texto."%");  
	//$params = array();  

    @$stmt = DBExecSql($conn, $sql, $params);
    $selectid = true;
    
    while ($row = DBGetQuery($stmt, 1, false)) {
        $result .= CargarComboOpt($row["EM_CUIT"], $row["EM_NOMBRE"], 0, $selectid);           
    }                        
    return $result;           
}

function CargarFueroCYQ($fuero){
	global $conn;           
	$sql = "SELECT TB_CODIGO, TB_DESCRIPCION 
			   FROM ctb_tablas 
			  WHERE tb_especial1 IS NOT NULL 
				AND tb_codigo <> '0' 
				AND tb_fechabaja IS NULL 
				AND tb_clave = 'FUERO' 
			ORDER BY tb_descripcion ";
			
	$result = FL_AddComboOption('0', '', true);            
    $params = array();  	
    @$stmt = DBExecSql($conn, $sql, $params);
        
    while ($row = DBGetQuery($stmt, 1, false)) {
        $result .= CargarComboOpt($row["TB_CODIGO"], $row["TB_DESCRIPCION"], $fuero, false);           
    }                        
    return $result;         			
}

function CargarJurisdiccionCYQ($jurisdiccion = 0){
	global $conn;           
	$sql = "SELECT TB_CODIGO, TB_DESCRIPCION
		   FROM ctb_tablas 
		  WHERE tb_codigo <> '0' AND tb_fechabaja IS NULL AND tb_clave = 'JURIS' 
		 ORDER BY tb_descripcion";
			
	$result = FL_AddComboOption('0', '', true);            
    $params = array();  	
    @$stmt = DBExecSql($conn, $sql, $params);
        
    while ($row = DBGetQuery($stmt, 1, false)) {
        $result .= CargarComboOpt($row["TB_CODIGO"], $row["TB_DESCRIPCION"], $jurisdiccion, true);           
    }                        
    return $result;         			
}

function CargarEventosCYQ($CODEVENTO = '0'){
	global $conn;           
	$sql = "SELECT TB_CODIGO, TB_DESCRIPCION 
			  FROM ctb_tablas 
			 WHERE tb_codigo <> '0' AND tb_fechabaja IS NULL AND tb_clave = 'EVCYQ' 
			ORDER BY tb_descripcion ";
			
	$result = FL_AddComboOption('0', '', true);            
    $params = array();  	
    @$stmt = DBExecSql($conn, $sql, $params);
        
    while ($row = DBGetQuery($stmt, 1, false)) {
        $result .= CargarComboOpt($row["TB_CODIGO"], $row["TB_DESCRIPCION"], $CODEVENTO, true);           
    }                        
    return $result;         			
}

function CargarTipoFiltro($IdItem = 0){
	global $conn;           
	$sql = "SELECT TB_CODIGO, TB_DESCRIPCION 
			  FROM ctb_tablas 
			 WHERE tb_fechabaja IS NULL 
			   AND tb_clave = 'TACYQ' 
			   AND (tb_especial1 = 'Q' OR tb_especial1 = 'C') 
			 ORDER BY tb_descripcion ";
			
	$result = FL_AddComboOption('0', '', true);            
    $params = array();  	
    @$stmt = DBExecSql($conn, $sql, $params);
        
    while ($row = DBGetQuery($stmt, 1, false)) {
        $result .= CargarComboOpt($row["TB_CODIGO"], $row["TB_DESCRIPCION"], $IdItem, true);           
    }                        
    return $result;         			
}


function CargarTipo($concurso=true, $value='', $IdItem=0 ) {  
	global $conn;           
	
	$sql = "SELECT TB_CODIGO, TB_DESCRIPCION 
	  FROM ctb_tablas 
	 WHERE tb_fechabaja IS NULL 
	   AND tb_clave = 'TACYQ' ";

	if($concurso == true){
		$sql .= " AND( tb_especial1 = 'Q' ";
	}
	else{
		$sql .= " AND( tb_especial1 = 'C' ";
	}

	if($value <> ''){
		$sql .= " OR tb_codigo = ' +SqlValue(value) .' ) ";
	}
	else{	
		$sql .= " ) ";
	}
	
	$sql .= " ORDER BY TB_DESCRIPCION ";
  
  	$result = FL_AddComboOption('0', '', true);            
    $params = array(); 
 	
    @$stmt = DBExecSql($conn, $sql, $params);
        
    while ($row = DBGetQuery($stmt, 1, false)) {
        $result .= CargarComboOpt($row["TB_CODIGO"], $row["TB_DESCRIPCION"], $IdItem, true);           
    }                        
    return $result;
  
}