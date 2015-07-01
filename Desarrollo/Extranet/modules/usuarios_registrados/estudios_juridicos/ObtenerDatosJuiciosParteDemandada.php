<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CommonFunctions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
@session_start(); 

$PageBase = new PageBase(false);
		
if (isset($_REQUEST['FUNCION'])){    

	if ($_REQUEST['FUNCION']=="BuscarPeritosListado"){
		
		$CuitCuil = '';
		$TipoPerito = 0;
		$Apellido = '';
		$Nombre = '';
		$idPerito = 0;
		
		if (isset($_REQUEST["cuit"])) {  $CuitCuil = $_REQUEST["cuit"];  }        
		if (isset($_REQUEST["tipoPerito"])) {  $TipoPerito = $_REQUEST["tipoPerito"];  }        
		
		if (isset($_REQUEST["Apellido"])) {  $Apellido = utf8_decode($_REQUEST["Apellido"]);  }        
		if (isset($_REQUEST["Nombre"])) {  $Nombre = utf8_decode($_REQUEST["Nombre"]);  }        
		
		if (isset($_REQUEST["idPerito"])) {  $idPerito = utf8_decode($_REQUEST["idPerito"]);  }        
						
		$datos = BuscarPeritosListado($CuitCuil, $TipoPerito, $Apellido, $Nombre, $idPerito);
		
		//$result = utf8_encode($datos);
		$result = $datos;
        echo $result;
	}
	
	if ($_REQUEST['FUNCION']=="UpdateEvento"){
		$id = 0;
		$usuario = '';
		if (isset($_REQUEST["id"])) {  $id = $_REQUEST["id"];  }        
		if (isset($_REQUEST["usuario"])) {  $usuario = $_REQUEST["usuario"];  }        

		$result = UpdateEvento($id, $usuario);
		
		if($result == 0)
			echo "<script type='text/javascript'> 								
					alert('No se puede Eliminar el evento, ya que usted no dio origen al registro.') ;
					window.location.href = '/EventosWebForm';
				</script>";			
		else
			echo "<script type='text/javascript'> 		
					alert('Evento eliminado');
					window.location.href = '/EventosWebForm';
				</script>";			
	}
	
	if ($_REQUEST['FUNCION']=="ObtenerPeritos"){
        //ObtenerPeritosNombre($Nombre, $Apellido , $tipoPericia ){
        $Nombre = '';
        $Apellido  = '';
        $tipoPericia = '';
         
        if (isset($_REQUEST["Nombre"])) {
            $Nombre = utf8_decode($_REQUEST["Nombre"]);    
        }        

        if (isset($_REQUEST["Apellido"])) {
            $Apellido = utf8_decode($_REQUEST["Apellido"]);
        }         
        
        if (isset($_REQUEST["tipoPericia"])) {
            $tipoPericia = $_REQUEST["tipoPericia"];
        }                        
       
        $datos = ObtenerPeritosNombre($Nombre, $Apellido , $tipoPericia);                 
        $result = utf8_encode($datos);
        echo $result;
    }    
	
	if ($_REQUEST['FUNCION']=="ObtenerInstanciaSeleccionada"){        
        $jurisdiccion = 0;
		$fuero = 0;
		$juzgado = 0;
		$RetornaID = false;
		$campo = "IN_DESCRIPCION";		
         
        if (isset($_REQUEST["jurisdiccion"])) {$jurisdiccion = $_REQUEST["jurisdiccion"]; }        
        if (isset($_REQUEST["fuero"])) {$fuero = $_REQUEST["fuero"]; }        
        if (isset($_REQUEST["juzgado"])) {$juzgado = $_REQUEST["juzgado"]; }        
		
        if (isset($_REQUEST["CampoID"])) {$campo = "ID"; }        

        $result = ObtenerInstanciaSeleccionada($jurisdiccion, $fuero, $juzgado);
		
		///retorna el valor del campo IN_DESCRIPCION		
		if(isset($result[$campo]) )			
			echo utf8_encode($result[$campo]);
		else
			echo "";			
    }  

	if ($_REQUEST['FUNCION']=="BuscarMotivoJuzgado"){
		try{			
			if (isset($_REQUEST["MotivoID"])) {$MotivoID = $_REQUEST["MotivoID"]; }        
			$EnumMotivos = EnumMotivos();
			
			foreach($EnumMotivos as $key  => $value){					
				if($key == $MotivoID)
					echo $value;
			}			
			
		}catch (Exception $e){    
			echo "";			
		}      
	}
	
	if ($_REQUEST['FUNCION']=="ObtenerAnioValidoExpediente"){
		try{			
			$anioExpediente = '0';
			if (isset($_REQUEST["anioExpediente"])){
				$anioExpediente = $_REQUEST["anioExpediente"]; }        
			
			if($anioExpediente == '0'){
				echo "Error";
				return false;
			}
			
			$resultado = ObtenerAnioValidoExpediente($anioExpediente);
			echo $resultado;
			return true;
			
		}catch (Exception $e){    
			echo "Except";			
			return false;
		}      
	}
	
	if ($_REQUEST['FUNCION']=="ValidarExpedienteNroYearSecretaria"){
		
		$nroInstancia = 0;
		$NroExpediente = '';
		$AnioExpediente = '';
		$Secretaria = 0;
		
		try{
			
			if (isset($_REQUEST["nroInstancia"])) {$nroInstancia = $_REQUEST["nroInstancia"]; }        
			if (isset($_REQUEST["NroExpediente"])) {$NroExpediente = $_REQUEST["NroExpediente"]; }        
			if (isset($_REQUEST["AnioExpediente"])) {$AnioExpediente = $_REQUEST["AnioExpediente"]; }        
			if (isset($_REQUEST["Secretaria"])) {$Secretaria = $_REQUEST["Secretaria"]; }        
			
			if(ValidarExpedienteNroYearSecretaria($nroInstancia, $NroExpediente, $AnioExpediente, $Secretaria) ){					
				echo "";
			}
			
		}catch (Exception $e){    
			echo utf8_encode("Expediente ya existente.");
			return false;
		}      
    }
}

function formatofecha(){
    try{
		global $conn;       
		
		$sql = "select parameter, value from NLS_SESSION_PARAMETERS";
		$params = array();
				
		$stmt = DBExecSql($conn, $sql, $params);
		$result = '';
		
		while ($row = DBGetQuery($stmt)) {		
			foreach($row as $key  => $value){					
				$result .= "<p>".$key." = ".$value;		
				}
		}				
		
		return "<div>".$result."</div>";
		
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     	
	
}
///////Funciones generales BD
function ObtenerDatosDeJuicio($nrojuicio, $idestudio, $usuarioweb){
	try{
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
				--NVL2(JT_FECHAASIGN, TO_DATE(JT_FECHAASIGN, 'DD/MM/YYYY'), JT_FECHAASIGN) JT_FECHAASIGN, 
				NVL2(JT_FECHAASIGN, JT_FECHAASIGN, JT_FECHAASIGN) JT_FECHAASIGN, 
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
				FROM
					legales.ljz_juzgado,
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
						"TIPOJUICIO" => '',
						"JT_NUMEROCARPETA" => '',
						"JT_FECHAINGRESORAJ" => '',			   
						"JT_CONDICIONDENOSEGURO" => '',
						"JT_NUMEROORDENRAJ" => '',
						"EJ_ID" => '',
						"IJ_ID" => '');	
						
			return $row;		
		}
	}catch (Exception $e) {
        DBRollback($conn);  		
		ErrorConeccionDatos($e->getMessage());		
		return false;
    }     						
}

function GuardarInstancia($NroJuicio, $cmbJurisdiccion, 
		$cmbFuero, $cmbJuzgadoNro, $cmbSecretaria, $txtNroExp, $txtAnioExp){
	try{		
		//$Nroinstancia = $NroJuicio;
		$estadoMediacion = ObtenerEstadoMediacion($NroJuicio);
		extract(ObtenerinstanciaSeleccionada($cmbJurisdiccion, $cmbFuero, $cmbJuzgadoNro),EXTR_PREFIX_ALL, "OIS" );
			
		$JuicioEnTramite = $NroJuicio; 
		$Jurisdiccion = $cmbJurisdiccion;
		$Fuero = $cmbFuero; 
		$Juzgado = $cmbJuzgadoNro;
		$Secretaria = $cmbSecretaria;
		$Instancia = $OIS_JZ_IDINSTANCIA; 
		$NroExpediente = $txtNroExp;
		$AnioExpediente = $txtAnioExp;
		$LoginName = $_SESSION["usuario"];
		$nroInstancia = $NroJuicio;//IJ_ID
		$EstadoMediacion = 'J';  
		
		$resultado = UpdateInstanciaJuicio($JuicioEnTramite, $Jurisdiccion, $Fuero, $Juzgado,
		$Secretaria, $Instancia, $NroExpediente, $AnioExpediente, 
		$LoginName, $nroInstancia,$EstadoMediacion);
		
		return $resultado;
	}catch (Exception $e) {    
		return false;
    }                            		
}

function ObtenerInstanciaSeleccionada($idjurisdiccion, $idfuero, $idjuzgado){
	//version original	
	try{
		global $conn;			
		$sql = "SELECT  JZ_ID AS ID, 
					JZ_ID AS CODIGO, 
					JZ_DESCRIPCION AS DESCRIPCION, 
					JZ_FECHABAJA AS BAJA, 
					JZ_IDINSTANCIA, 
					IN_DESCRIPCION, 
					NVL(JZ_DIRECCION, '') AS JZ_DIRECCION
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
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function DatosJuicio($juicio){
	try{
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
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function ObtenerInstanciaModificar( $nroInstancia ){
    try{
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
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function ObtenerEstadoMediacion($NroJuicio){
    try{
		global $conn;  
		
		$sql = "SELECT JT_ESTADOMEDIACION 
				FROM legales.ljt_juicioentramite 
				WHERE jt_id= :NroJuicio";
				
		if (isset($_REQUEST["NroJuicio"]))     $juicio = $_REQUEST["NroJuicio"]; 
			
		$params = array(":NroJuicio" => $NroJuicio);
		
		$result = ValorSql($sql, "", $params);
		
		return $result;
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function ValidarExpedienteNroYearSecretaria($nroInstancia, $NroExpediente, $AnioExpediente, $Secretaria){
	/*
		$nroInstancia = el valor de este parametro es el campo nrojuicio
	*/
    try{
		global $conn;      
		if(!isset($NroExpediente)){$NroExpediente = '';} 
		if(!isset($AnioExpediente)){$AnioExpediente = '';}
		
		$sql = "SELECT 1
					FROM legales.ljt_juicioentramite
					WHERE jt_estadomediacion = 'J'
					AND jt_idestado <> 3
					AND NVL2 (jt_anioexpediente,jt_nroexpediente || '/' || jt_anioexpediente,jt_nroexpediente) = 
					trim(:NroExpediente || '/' || :AnioExpediente)
					AND jt_idsecretaria = :Secretaria
					AND JT_ID <> :nroInstancia
					AND ROWNUM = 1";

		$params = array(
					":nroInstancia" => $nroInstancia,
					":Secretaria" => $Secretaria,
					":NroExpediente" => $NroExpediente,
					":AnioExpediente" => $AnioExpediente);
		
		$result = ValorSql($sql, "", $params);
		//$result = 1;
		
		if($result == 1){
			//throw new Exception("Nro Expediente ".$NroExpediente."/".$AnioExpediente.", ya existe en la Secretaria ".$Secretaria.".");
			throw new Exception("Expediente ya existente. ".$nroInstancia);
			return false;
		}		
		
		return true;
    }catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function EnumMotivos(){
	try{
		global $conn;       
		
		$sql = SqlMotivos();
		
		$params = array();  
		$stmt = DBExecSql($conn, $sql, $params);    
		$ArrayMotivos = array();
		
		while ($row = DBGetQuery($stmt, 1, false)) {      
			$ArrayMotivos[$row["MC_ID"]] = $row["MC_RELACIONNUEVOJUZGADO"];        
		}    
		return $ArrayMotivos;       
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function UpdateInstanciaAbmMod($JuicioEnTramite, $Jurisdiccion, $Fuero, $Juzgado, $Secretaria, 
    $Instancia, $NroExpediente, $AnioExpediente, $Motivo, 
    $Detalle, $LoginName, $nroInstancia, $EstadoMediacion, $FechaIngreso){
	
    try{
		global $conn;      		
		
		//---------------------------------------------------
		$info=ObtenerInstanciaSeleccionada($Jurisdiccion, $Fuero, $Juzgado);
		$Instancia = $info["JZ_IDINSTANCIA"]; //Este valor se obtine de la funcion ObtenerInstanciaSeleccionada		
		$EstadoMediacion = ObtenerEstadoMediacion($JuicioEnTramite); //Este valor se calcula 
		//---------------------------------------------------
		ValidarExpedienteNroYearSecretaria($JuicioEnTramite, $NroExpediente, $AnioExpediente, $Secretaria);
		
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
		return true;
    }catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());		
		return false;
    }     				                            
}

function UpdateInstanciaABMAlta($JuicioEnTramite, $Jurisdiccion, $Fuero, $Juzgado, $Secretaria, $Instancia, 
	$NroExpediente, $AnioExpediente, $Motivo, $Detalle, $LoginName, $EstadoMediacion, $FechaIngreso){
    try{
		global $conn;      	
		//---------------------------------------------------
		$info=ObtenerInstanciaSeleccionada($Jurisdiccion, $Fuero, $Juzgado);
		$Instancia = $info["JZ_IDINSTANCIA"]; //Este valor se obtine de la funcion ObtenerInstanciaSeleccionada		
		$EstadoMediacion = ObtenerEstadoMediacion($JuicioEnTramite); //Este valor se calcula 
		//---------------------------------------------------
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
		return true;
    }catch (Exception $e) {
        DBRollback($conn);    	
		//ErrorConeccionDatos($e->getMessage());
		
		throw new Exception($e->getMessage());		
		return false;
    }     				
}

function ObtenerMasDatosJuicios($nrojuicio){
	try{
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
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
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
	  
		if ($Nombre > '' ){
	    	$sql .= " AND UPPER(pe_nombreindividual) LIKE UPPER(:Nombre)";		
			$params[":Nombre"] = '%'.$Nombre.'%';	
		}
		
		if ($Apellido > '' ){
	    	$sql .= " AND UPPER(pe_apellido) LIKE UPPER(:Apellido)";		
			$params[":Apellido"] = '%'.$Apellido.'%';
		}
		
		if ( is_numeric($tipoPericia) and ($tipoPericia > '0' ))
	    	$sql .= " AND pe_idtipoperito = ".$tipoPericia;
	
		$sql .= ' ORDER BY 1 ';	
		$result = '';			
		
		@$stmt = DBExecSql($conn, $sql, $params);
				
		while ($row = DBGetQuery($stmt, 1, false)) {
	        $result .= CargarComboOpt($row["ID"], $row["NOMBRE"], $idfuero, $selectid);
		}	
		
		return $result; 		
	}catch (Exception $e) {        
		//ErrorConeccionDatos($e->getMessage());
		return $e->getMessage();
    }     				
}

function ExistePeritaje($id, $usuario){
	try{
	    global $conn;       

		$sqlCheck = "SELECT 1  
				FROM legales.lpj_peritajejuicio  
				WHERE pj_fechabaja IS NULL  
				 AND pj_id =  :ID
				 AND pj_usualta = :usuario";


		$params = array(":id" => $id, ":usuario" => $usuario);

		$result = ValorSql($sqlCheck, "", $params);

		return $result;
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function UpdatePeritajes($id, $usuario){

	try{	
		global $conn;	
	    //Baja logica de peritaje

	    	$sqlUpdate = "UPDATE legales.lpj_peritajejuicio  
			       SET pj_fechabaja = SYSDATE,  
			           pj_usubaja = :usuario		     
			       WHERE pj_fechabaja IS NULL 
			       AND pj_id = :id ";
			       
			$params = array(":id" => $id, ":usuario" => $usuario);
			
		 	DBExecSql($conn, $sqlUpdate , $params);				  			    	
		    DBCommit($conn);    
	
		return true;
    }catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				    
}


function ObtenerNroCarpeta($juicio){
	try{
		global $conn;		
		$_SESSION["JUICIOTERMINADO"] = false;

		$sql =" SELECT JT_NUMEROCARPETA, 
						NVL(JT_DEMANDANTE, '') || ' C/ ' || NVL(JT_DEMANDADO, '') || ' ' || JT_CARATULA AS DESCRIPCARATULA, 
						EJ_DESCRIPCION,
						DECODE(JT_IDESTADO, 2, 'T', '')	ESTADO
					FROM legales.ljt_juicioentramite, legales.lej_estadojuicio 
					WHERE jt_idestado = ej_id 
					AND jt_id = :juicio";
			
		if( is_numeric($juicio) and ($juicio > '0') ) {
			$params = array(":juicio" => $juicio );
			$stmt = DBExecSql($conn, $sql, $params);		
			$row = DBGetQuery($stmt);
			
			$_SESSION["JUICIOTERMINADO"] = (strtoupper(trim($row['ESTADO'])) == "T");
			
			$_SESSION["NUMEROCARPETA"] = $row['JT_NUMEROCARPETA']; 
			$_SESSION["DESCRIPCARATULA"] = $row['DESCRIPCARATULA'];
			$_SESSION["ESTADO_DESCRIPCION"] = $row['EJ_DESCRIPCION'];

			return array( $row['JT_NUMEROCARPETA'], $row['DESCRIPCARATULA'], $row['EJ_DESCRIPCION']);
		}
		else
			return array('','','');
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}
///continuar

function UpdatePerito($nombre, $apellido, $cuil, $tipoperito, $parteoficio, $usuario, $direccion, $email, $telefono, $id){
	    global $conn;      
    try{
						
		$sqlInsert_Perito = "UPDATE LEGALES.LPE_PERITO 
					SET 
					PE_FECHAMODIF = SYSDATE,
					PE_USUMODIF = :usuario,
					PE_IDTIPOPERITO = :tipoperito, 
					PE_PARTEOFICIO = :parteoficio, 
					PE_NOMBREINDIVIDUAL = UPPER(:nombre), 
					PE_APELLIDO = UPPER(:apellido), 
					PE_CUITCUIL = :cuil, 
					PE_DIRECCION = :direccion, 
					PE_DIRECCIONELECTRONICA = :email, 
					PE_TELEFONO = :telefono 
					WHERE PE_ID = :id";
				 
	 	$params = array(":id" => $id,
	 				":usuario" => $usuario,	            
	 				":tipoperito" => $tipoperito,	            
	 				":parteoficio" => $parteoficio,	            
	 				":nombre" => $nombre,	            
					":apellido" => $apellido,
	 				":apellidonombre" => $apellido." ".$nombre,	            
	 				":cuil" => $cuil,	            
	 				":direccion" => $direccion,	            
					":email" => $email,
					":telefono" => $telefono);			 
		
	 	@DBExecSql($conn, $sqlInsert_Perito, $params);				  
				  
	    DBCommit($conn);
	    return $id;
    }catch (Exception $e) {    	
        DBRollback($conn);		
		throw new Exception($e->getMessage());
        //return 0;        
    }           
}

function InsertarPeritoNuevo($nombre, $apellido, $cuil, $tipoperito, $parteoficio, $usuario, $direccion, $email, $telefono){
    global $conn;      
    try{
	
		$secuencia = ValorSql("SELECT legales.seq_lpe_id.NEXTVAL FROM DUAL");
		
		$sqlInsert_Perito = "INSERT INTO legales.lpe_perito 
	            (PE_ID, PE_NOMBRE, PE_CUITCUIL, PE_IDTIPOPERITO, 
	             PE_PARTEOFICIO, PE_USUALTA, PE_FECHAALTA,PE_DIRECCION,  
	             PE_NOMBREINDIVIDUAL, PE_APELLIDO,
				 PE_DIRECCIONELECTRONICA, PE_TELEFONO) 
	     VALUES (:id, 
				 upper(:apellidonombre), 
				 :cuil, 
				 :tipoperito,
				 :parteoficio, 
				 :usuario, 
				 SYSDATE,
				 :direccion,
				 upper(:nombre),
				 upper(:apellido),
				 :email,
				 :telefono)";
				 
	 	$params = array(":id" => $secuencia,
	 				":apellidonombre" => $apellido." ".$nombre,	            
	 				":cuil" => $cuil,	            
	 				":tipoperito" => $tipoperito,	            
	 				":parteoficio" => $parteoficio,	            
	 				":usuario" => $usuario,	            
	 				":direccion" => $direccion,	            
	 				":nombre" => $nombre,	            
					":apellido" => $apellido,
					":email" => $email,
					":telefono" => $telefono);			 
		
	 	DBExecSql($conn, $sqlInsert_Perito, $params);				  
				  
	    DBCommit($conn);
	    return $secuencia;
    }catch (Exception $e) {    	
        DBRollback($conn);		
        return 0;        
    }           
} 
//----------Baja logica Evento ------------
function ExisteEvento($id, $usuario){
	try{
		global $conn;       
		
		$sqlCheck = "SELECT 1 
			  FROM legales.let_eventojuicioentramite 
			 WHERE et_fechabaja IS NULL 
			   AND et_id = :ID
			   AND upper(et_usualta) = upper(:usuario)";
		
		$params = array(":id" => $id, ":usuario" => $usuario);
		
		$result = ValorSql($sqlCheck, "", $params);

		return (bool)$result;
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function UpdateEvento($id, $usuario){
	try{	
		global $conn;	
	    //Baja logica de evento
	    if(ExisteEvento($id, $usuario)) {
	    	$sqlUpdate = "UPDATE legales.let_eventojuicioentramite
			       SET et_fechabaja = SYSDATE,  
			           et_usubaja =  :usuario		     
			       WHERE et_fechabaja IS NULL AND et_id = :id ";
			       
			$params = array(":id" => $id, ":usuario" => $usuario);
	
		 	DBExecSql($conn, $sqlUpdate , $params);				  			    	
		    DBCommit($conn);    
		    
		    return true;
	    } 	       
		return false;		
		
	}catch (Exception $e) {
	    DBRollback($conn);        
		throw new Exception($e->getMessage());
	    return false;
	}    
}

//----------Baja logica Evento ------------

function CargarEventosActora($seleccionado){
	try{
		global $conn;			
		$sql = "  SELECT   te_id, te_descripcion, te_etapa
					FROM   legales.lte_tipoevento
				   WHERE   te_fechabaja IS NULL
					   AND te_etapa LIKE '%A%'
					   AND te_visibleweb = 'S'
					   AND te_id <> 1
				ORDER BY   te_descripcion";
			
		$result = AddComboOption($seleccionado, '', false);		

		$params = array(); 	
						
		$stmt = DBExecSql($conn, $sql, $params);

		while ($row = DBGetQuery($stmt, 1, false)) {
			$result .= CargarComboOpt($row["TE_ID"], $row["TE_DESCRIPCION"], $seleccionado, true);
		}	

		return $result; 
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
} 

function CargarEventos($seleccionado){
	try{
		global $conn;			
		$sql = "  SELECT   te_id, te_descripcion
					FROM   legales.lte_tipoevento
				   WHERE   (te_fechabaja IS NULL 
					   AND te_etapa LIKE '%J%'
					   AND te_id <> 1
					   AND te_visibleweb = 'S') or (te_id = $seleccionado)
				ORDER BY   te_descripcion ";
			
		$result = AddComboOption('0', '', false);		

		$params = array(); 	
						
		$stmt = DBExecSql($conn, $sql, $params);

		while ($row = DBGetQuery($stmt, 1, false)) {
			$result .= CargarComboOpt($row["TE_ID"], $row["TE_DESCRIPCION"], $seleccionado, true);
		}	

		return $result; 
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
} 

function ObtenerEventosABM($id){
	try{
		global $conn;		

		$sql =" SELECT ET_FECHAVENCIMIENTO, 
					ET_FECHAEVENTO, 
					ET_IDTIPOEVENTO, 
					nvl(ET_OBSERVACIONES, '') ET_OBSERVACIONES, 
					ET_IDJUICIOENTRAMITE,
					ET_USUALTA 
		   FROM legales.let_eventojuicioentramite 
		  WHERE et_id = :id";

		if( is_numeric($id) and ($id> '0') ) {
			$params = array(":id" => $id);

			$stmt = DBExecSql($conn, $sql, $params);		

			$row = DBGetQuery($stmt);

			if( trim($row['ET_OBSERVACIONES'] != '')){
				return array( $row['ET_FECHAVENCIMIENTO'], 
					$row['ET_FECHAEVENTO'], 
					$row['ET_IDTIPOEVENTO'],
					$row['ET_OBSERVACIONES']->load(),						
					$row['ET_IDJUICIOENTRAMITE'],
					$row['ET_USUALTA']);
			}
			else{
				return array( $row['ET_FECHAVENCIMIENTO'], 
					$row['ET_FECHAEVENTO'], 
					$row['ET_IDTIPOEVENTO'],
					'',						
					$row['ET_IDJUICIOENTRAMITE'],
					$row['ET_USUALTA']);
			}
		}
		else 
			return array('','','','','','');
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				

}

function InsertarEventoNuevo($txtfecha, $txtfechavencimiento, $txtobservaciones , $nrojuicio, $usuario, $cmbEventos){
    try{
		global $conn;      		
		$fechavencimiento = 'NULL';
		
		if($txtfechavencimiento != ""){
			$fechavencimiento = SqlDate($txtfechavencimiento);
		}
		
		$idEvento = ValorSql("SELECT legales.seq_let_id.NEXTVAL FROM DUAL");

		$blobParamName = "the_clob";
		$sqlInsertEvento = "			
			INSERT INTO legales.let_eventojuicioentramite  
			      (et_id, et_fechaevento, et_fechavencimiento,  
			       et_idjuicioentramite, et_fechaalta, 
			       et_usualta, et_fechamodif,  
			       et_usumodif, et_fechabaja, 
			       et_usubaja,
			       et_observaciones, 
			       et_idtipoevento)  
			VALUES (".$idEvento.",  
				".SqlDate($txtfecha).",
				".$fechavencimiento.",
				".$nrojuicio.",
				SYSDATE,
				".addQuotes($usuario).",
				NULL,  NULL, NULL, NULL,EMPTY_CLOB(),
				".$cmbEventos.")
				RETURNING et_observaciones INTO :".$blobParamName;	
	
		@DBSaveLob($conn, $sqlInsertEvento, $blobParamName, $txtobservaciones, OCI_B_CLOB);										  
	    DBCommit($conn);
	    return $idEvento;
    }catch (Exception $e) {
        DBRollback($conn);          		
		ErrorConeccionDatos($e->getMessage());
		return 0;
    }     						
}


function UpdateEventosABM($txtfecha, $txtfechavencimiento, $etid, $txtobservaciones, $usuario, $cmbEventos){
    try{	
		global $conn;      
    	$blobParamName = "the_clob";
    	
		$sqlUpdateReclamo = "UPDATE legales.let_eventojuicioentramite
		    SET et_fechaevento = TO_DATE(".SqlDate($txtfecha).",'DD/MM/YYYY'), ";
		
		if(trim($txtfechavencimiento) != ''){
			$sqlUpdateReclamo .= "et_fechavencimiento = TO_DATE(".SqlDate($txtfechavencimiento).",'DD/MM/YYYY'), ";
		}
				
		$sqlUpdateReclamo .= "et_fechamodif = SYSDATE, 
				et_usumodif = ".addQuotes($usuario).",
				et_fechabaja = NULL,
				et_usubaja = NULL,
				et_observaciones = EMPTY_CLOB(),
				et_idtipoevento = ".$cmbEventos."
			WHERE et_id = ".$etid."
			RETURNING et_observaciones INTO :".$blobParamName." ";
		
		EscribirLogTxt1("UpdateEventosABM", $txtobservaciones);
		@DBSaveLob($conn, $sqlUpdateReclamo , $blobParamName, $txtobservaciones, OCI_B_CLOB);	
	    DBCommit($conn);
	    return true;
		
    }catch (Exception $e) {
        DBRollback($conn);                		
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}
/////////////////Sentencias Web Form //////////////////////////////////////////
function ObtenerSentencia($nrojuicio){
	try{
		global $conn;       
		
		$sql = "SELECT JTR.JT_ID, 
						JT_IDTIPORESULTADOSENTENCIA, 
						JTR.JT_FECHASENTENCIA, 
						JT_FECHARECEPSENTENCIA, 					
						JTR.JT_IMPORTEDEMANDADO,
						JT_IMPORTECAPITAL, 
						JT_IMPORTETASAJUSTICIA, 
						JTR.JT_IMPORTESENTENCIA, 
						JTR.JT_IMPORTEHONORARIOS, 
						nvl(JT_DETALLESENTENCIA, '') JT_DETALLESENTENCIA, 
						JT_INTERESESSENTENCIA, 
						JT_MONTOCONDENA,
						JT_PORCENTAJEINCAPACIDAD 
				   FROM legales.ljt_juicioentramite jtr 
				  WHERE jtr.jt_fechabaja IS NULL 
				  AND jtr.jt_id = :nrojuicio";
		
		$params = array(":nrojuicio" => $nrojuicio);    

		$stmt = DBExecSql($conn, $sql, $params);	

		while ($row = DBGetQuery($stmt)) {		
			
				if( trim($row['JT_DETALLESENTENCIA'] != '')){
					$fieldJT_DETALLESENTENCIA = $row['JT_DETALLESENTENCIA']->load();}
				else{
					$fieldJT_DETALLESENTENCIA = '';}
					
				return array( $row['JT_ID'], 
					$row['JT_IDTIPORESULTADOSENTENCIA'], 
					$row['JT_FECHASENTENCIA'], 
					$row['JT_FECHARECEPSENTENCIA'], 
					$row['JT_IMPORTEDEMANDADO'], 
					$row['JT_IMPORTECAPITAL'], 
					$row['JT_IMPORTETASAJUSTICIA'], 
					$row['JT_IMPORTESENTENCIA'], 
					$row['JT_IMPORTEHONORARIOS'], 
					$fieldJT_DETALLESENTENCIA,
					$row['JT_INTERESESSENTENCIA'], 
					$row['JT_MONTOCONDENA'], 
					$row['JT_PORCENTAJEINCAPACIDAD']);
		}
		
		if(!isset($row)){
			$row = array("JT_ID" => '',
						"JT_IDTIPORESULTADOSENTENCIA" => '',
						"JT_FECHASENTENCIA" => '',
						"JT_FECHARECEPSENTENCIA" => '',
						"JT_IMPORTEDEMANDADO" => '',
						"JT_IMPORTECAPITAL" => '',
						"JT_IMPORTETASAJUSTICIA" => '',
						"JT_IMPORTESENTENCIA" => '',
						"JT_IMPORTEHONORARIOS" => '',
						"JT_DETALLESENTENCIA" => '',
						"JT_INTERESESSENTENCIA" => '',
						"JT_MONTOCONDENA" => '',
						"JT_PORCENTAJEINCAPACIDAD" => '');	
			return $row;		
		}    
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function ObtenerTipoResultadoSentencia($seleccionado){
	try{
		//Esta funcion se utiliza en la carga del combo TipoResultadoSentencia
		global $conn;       

		$sql = "SELECT TR_ID, TR_DESCRIPCION, TR_ETAPA 
			   FROM legales.ltr_tiporesultadosentencia 
			  WHERE tr_etapa LIKE '%J%'
			  AND ( tr_id = :seleccionado or  tr_fechabaja is null)";			  

		$params = array(":seleccionado " => $seleccionado);
		$stmt = DBExecSql($conn, $sql, $params);
		return DBGetQuery($stmt);		
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     							  
}

function ObtenerIncapacidadVisible($idjuicio){
	try{
		global $conn;       
		
		$sql = "select nvl((SELECT DISTINCT 'S' 
				  FROM legales.lrc_reclamo, legales.lrt_reclamojuicioentramite 
				 WHERE rc_reclamaincapacidad = 'S' 
				   AND rt_idreclamo = rc_id 
				   AND rt_idjuicioentramite = :idjuicio), 'N') VISIBLE FROM DUAL";
		
		$params = array(":idjuicio" => $idjuicio);    
		$result = ValorSql($sql, "", $params);    
		return $result;			   
		
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function ObtenerInstanciaParaSentencia($pj_idjuicioentramite){
	try{
		global $conn;       

		$sql = "SELECT IJ_ID, 
					ART.WEBLEGALES.GET_TIPOSENTENCIA(IJ_IDTIPORESULTADOSENTENCIA) AS TIPOSENTENCIA 
					FROM legales.lij_instanciajuicioentramite
					WHERE ij_idjuicioentramite = :pj_idjuicioentramite
					AND ROWNUM = 1
					ORDER BY ij_id DESC ";

		$params = array(":pj_idjuicioentramite" => $pj_idjuicioentramite);    

		$stmt = DBExecSql($conn, $sql, $params);	

		while ($row = DBGetQuery($stmt)) {		
			return $row;		
		}

		if(!isset($row)){
			$row = array("IJ_ID" => '',
					"TIPOSENTENCIA" => '');	
			return $row;		
		} 
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				    
}

function sumaCapital($nrojuicio, $instancia){
	try{
		global $conn;       
		
		$sql = "SELECT SUM (IR_IMPORTESENTENCIA) AS IR_IMPORTESENTENCIA
				FROM legales.lir_importesreguladosjuicio 
				WHERE ir_idjuicioentramite = :nrojuicio
				AND ir_idinstancia= :instancia 
				AND ir_aplicacion = 'C' 
				AND ir_fechabaja IS NULL";
		
		$params = array(":nrojuicio" => $nrojuicio, ":instancia" => $instancia );    
		$result = ValorSql($sql, "", $params);    
		return $result;			   
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function sumaHonorarios($nrojuicio, $instancia){
	try{
		global $conn;       
		
		$sql = "SELECT SUM (IR_IMPORTESENTENCIA) AS IR_IMPORTESENTENCIA
				  FROM legales.lir_importesreguladosjuicio 
				 WHERE ir_idjuicioentramite = :nrojuicio
				 AND ir_idinstancia= :instancia
				 AND ir_aplicacion = 'H'
				 AND ir_fechabaja IS NULL";
		
		$params = array(":nrojuicio" => $nrojuicio, ":instancia" => $instancia );    
		$result = ValorSql($sql, "", $params);    
		return $result;			   
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function sumaIntereses($nrojuicio, $instancia){
	try{
		global $conn;       
		
		$sql = "SELECT SUM (IR_IMPORTESENTENCIA) AS IR_IMPORTESENTENCIA
				 FROM legales.lir_importesreguladosjuicio			 
				 WHERE ir_idjuicioentramite = :nrojuicio
				 AND ir_idinstancia = :instancia			 
				 AND ir_aplicacion = 'I'			 
				 AND ir_fechabaja IS NULL";
		
		$params = array(":nrojuicio" => $nrojuicio, ":instancia" => $instancia );    
		$result = ValorSql($sql, "", $params);    
		return $result;			   
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function sumaTasas($nrojuicio, $instancia){
	try{
		global $conn;       
		
		$sql = "SELECT SUM (IR_IMPORTESENTENCIA) AS IR_IMPORTESENTENCIA
				 FROM legales.lir_importesreguladosjuicio			 
				 WHERE ir_idjuicioentramite = :nrojuicio
				 AND ir_idinstancia = :instancia			 
				 AND ir_aplicacion = 'T'			 
				 AND ir_fechabaja IS NULL";
		
		$params = array(":nrojuicio" => $nrojuicio, ":instancia" => $instancia );    
		$result = ValorSql($sql, "", $params);    
		return $result;			   
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function sumaSentencia($nrojuicio, $instancia){
	try{
		global $conn;       
		
		$sql = "SELECT SUM (IR_IMPORTESENTENCIA) AS IR_IMPORTESENTENCIA
				FROM legales.lir_importesreguladosjuicio 
				WHERE ir_idjuicioentramite = :nrojuicio
				AND ir_idinstancia = :instancia
				AND ir_fechabaja IS NULL";
		
		$params = array(":nrojuicio" => $nrojuicio, ":instancia" => $instancia );    
		$result = ValorSql($sql, "", $params);    
		return $result;			   
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function ObtenerReclamos($nrojuicio){
	try{
		global $conn;       
		
		$sql = "SELECT LRC_RECLAMO.RC_DESCRIPCION, 
					   LRT_RECLAMOJUICIOENTRAMITE.RT_ID, 
					   LRT_RECLAMOJUICIOENTRAMITE.RT_IDJUICIOENTRAMITE, 
					   LRT_RECLAMOJUICIOENTRAMITE.RT_IDRECLAMO, 
					   LRT_RECLAMOJUICIOENTRAMITE.RT_MONTODEMANDADO, 
					   LRT_RECLAMOJUICIOENTRAMITE.RT_MONTOSENTENCIA, 
					   LRT_RECLAMOJUICIOENTRAMITE.RT_PORCENTAJESENTENCIA, 
					   LRT_RECLAMOJUICIOENTRAMITE.RT_IMPORTENOMINAL, 
					   LRT_RECLAMOJUICIOENTRAMITE.RT_INTERESES, 
					   LRT_RECLAMOJUICIOENTRAMITE.RT_PORCENTAJEINCAPACIDAD 
				  FROM legales.lrt_reclamojuicioentramite, legales.lrc_reclamo 
				 WHERE lrc_reclamo.rc_id = lrt_reclamojuicioentramite.rt_idreclamo 
				   AND lrt_reclamojuicioentramite.rt_fechabaja IS NULL 
				   AND rt_idjuicioentramite =  :nro";
		
		$params = array(":nro" => $nrojuicio);    

		$stmt = DBExecSql($conn, $sql, $params);	

		while ($row = DBGetQuery($stmt)) {		
			return $row;		
		}
		
		if(!isset($row)){
			$row = array("RC_DESCRIPCION" => '',
						"RT_ID" => '',
						"RT_IDJUICIOENTRAMITE" => '',
						"RT_IDRECLAMO" => '',
						"RT_MONTODEMANDADO" => '',
						"RT_MONTOSENTENCIA" => '',
						"RT_PORCENTAJESENTENCIA" => '',
						"RT_IMPORTENOMINAL" => '',
						"RT_INTERESES" => '',
						"RT_PORCENTAJEINCAPACIDAD" => '');	
			return $row;					
		} 
	}catch (Exception $e){
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function UpdateSentencia($txtfechasentencia, $txtfecharecep, $jtsentencia, 
	$cmbsentencia,  $usuario, $jt_id, $txtimportehonorarios, $txtimporteintereses, $txtimportetasajusticia, 
	$instancia, $txtMontoCondena, $txtPorcentajeIncapacidad){
	try{
		global $conn;      		

		extract(ObtenerInstanciaParaSentencia($_SESSION["NroJuicio"]) ,EXTR_PREFIX_ALL, "OIPS");					
		$instancia = $OIPS_IJ_ID;
		
		$blobParamName = "the_clob";
		
		//$txtPorcentajeIncapacidad = floatval($txtPorcentajeIncapacidad);
		$txtPorcentajeIncapacidad = Getfloat($txtPorcentajeIncapacidad);
		$txtMontoCondena = Getfloat($txtMontoCondena);
		
		if(empty($txtfecharecep))
			$localfecharecep = 'NULL';
		else
			$localfecharecep = SqlDate($txtfecharecep);
		
		$sqlUpdate = "UPDATE LEGALES.LJT_JUICIOENTRAMITE  
				     SET JT_IDTIPORESULTADOSENTENCIA = ".$cmbsentencia.",  
				         JT_FECHASENTENCIA = ".SqlDate($txtfechasentencia).",
				         JT_DETALLESENTENCIA = EMPTY_CLOB(), 
				         JT_MONTOCONDENA = NVL(".addQuotes($txtMontoCondena).", NULL),  
				         JT_PORCENTAJEINCAPACIDAD = NVL(".addQuotes($txtPorcentajeIncapacidad).", NULL),  
				         JT_USUMODIF = ".addQuotes($usuario).",  
				         JT_FECHAMODIF = SYSDATE, 
				         JT_FECHARECEPSENTENCIA = ".$localfecharecep."
				   WHERE JT_ID = $jt_id
				   RETURNING JT_DETALLESENTENCIA INTO :".$blobParamName;	
				   
		DBSaveLob($conn, $sqlUpdate, $blobParamName, $jtsentencia, OCI_B_CLOB);						
    //------------------------------------------------------------------------------
		
	    $sqlUpdate = "  UPDATE legales.lij_instanciajuicioentramite  
					     SET ij_idtiporesultadosentencia = ".$cmbsentencia." ,  
					         ij_fechasentencia = ".SqlDate($txtfechasentencia).",
					         ij_MONTOCONDENA = NVL(".addQuotes($txtMontoCondena).", NULL),  
					         ij_PORCENTAJEINCAPACIDAD = NVL(".addQuotes($txtPorcentajeIncapacidad).", NULL),  
					         ij_usumodif = ".addQuotes($usuario).",  
					         ij_fechamodif = SYSDATE, 
					         ij_detallesentencia = EMPTY_CLOB(),  
					         ij_fecharecepsentencia = ".$localfecharecep."
					   WHERE ij_idjuicioentramite = ".$jt_id."
					     AND ij_id = ".$instancia."
					   RETURNING ij_detallesentencia INTO :".$blobParamName;	

		DBSaveLob($conn, $sqlUpdate, $blobParamName, $jtsentencia, OCI_B_CLOB);						
    //------------------------------------------------------------------------------
		DBCommit($conn);
		return true;
    }catch (Exception $e) {
        DBRollback($conn);   		
		//ErrorConeccionDatos($e->getMessage());
		//return false;
		throw new Exception($e->getMessage());
    }     				   	
}

//Instancia juicio
function UpdateInstanciaJuicio($JuicioEnTramite, $Jurisdiccion, $Fuero, $Juzgado,
	$Secretaria, $Instancia, $NroExpediente, $AnioExpediente, 
	$LoginName, $nroInstancia,$EstadoMediacion){
        
    try{	
		global $conn;      
		
		ValidarExpedienteNroYearSecretaria($nroInstancia, $NroExpediente, $AnioExpediente, $Secretaria);
				
	   $sqlUpdate ="UPDATE legales.lij_instanciajuicioentramite
					SET ij_nroexpediente =  :NroExpediente,
						ij_anioexpediente = :AnioExpediente,
						ij_usumodif =  :LoginName,
						ij_idjurisdiccion = :Jurisdiccion,
						ij_idfuero =  :Fuero,
						ij_idjuzgado =  :Juzgado,
						ij_idsecretaria =  :Secretaria,					
						ij_fechamodif = SYSDATE
				  WHERE ij_idinstancia =  :Instancia
					AND ij_idjuicioentramite = :JuicioEnTramite";                  
					
	   $params =  array(":NroExpediente" => $NroExpediente,
					":AnioExpediente" => $AnioExpediente,
					":LoginName" => $LoginName,
					":Jurisdiccion" => $Jurisdiccion,
					":Fuero" => $Fuero,
					":Juzgado" => $Juzgado,
					":Secretaria" => $Secretaria,
					":Instancia" => $Instancia,				
					":JuicioEnTramite" => $JuicioEnTramite );

		DBExecSql($conn, $sqlUpdate, $params);
	   //----------------------------------------------------------------------
	   $sqlUpdate ="UPDATE legales.ljt_juicioentramite 
					SET jt_idjurisdiccion = :Jurisdiccion, 
						jt_idfuero = :Fuero, 
						jt_idjuzgado = :Juzgado, 
						jt_idsecretaria = :Secretaria, 
						jt_nroexpediente = :NroExpediente, 
						jt_anioexpediente = :AnioExpediente, 
						jt_usumodif = :LoginName, 
						jt_estadomediacion =  :EstadoMediacion, 
						jt_fechamodif = Sysdate 
				  WHERE jt_id =  :JuicioEnTramite";                  
				  
	   $params = array(":Jurisdiccion" => $Jurisdiccion,
					":Fuero" => $Fuero,
					":Juzgado" => $Juzgado,
					":Secretaria" => $Secretaria,				
					":NroExpediente" => $NroExpediente,
					":AnioExpediente" => $AnioExpediente,
					":LoginName" => $LoginName,
					":EstadoMediacion" => $EstadoMediacion,
					":JuicioEnTramite" => $JuicioEnTramite );
	
		DBExecSql($conn, $sqlUpdate, $params);                  
	   //----------------------------------------------------------------------    
		DBCommit($conn);
		return true;
    }catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function UpdateResultado($jt_id, $resultado, $cmbEstado, $usuario){
////.addQuotes($usuario).
	try{
		global $conn; 

		$sqlExecuteSP = "BEGIN art.Legales.Set_CambioEstado(:jt_id, :idestado, SYSDATE, :usuario); END;";

		$curs = null;
		$params = array(":jt_id" => $jt_id, ":idestado" => $cmbEstado, ":usuario" => $usuario);		
		DBExecSP($conn, $curs, $sqlExecuteSP, $params, false);	

		$sqlUpdate = "UPDATE legales.ljt_juicioentramite SET jt_resultado = :resultado, jt_fechamodif = SYSDATE, jt_idestado = :idEstado WHERE jt_id =  :id";
				  
		$params = array(":resultado" => $resultado,
						":idEstado" => $cmbEstado, 
						":id" => $jt_id);		
				
		@DBExecSql($conn, $sqlUpdate, $params);                  

		$sqlInsert="INSERT INTO legales.lhr_historicoresprobable(hr_id, hr_resultado, hr_usualta, hr_fechaalta,hr_idjuicioentramite ) VALUES (legales.seq_lhp_id.NEXTVAL, :resultado,  :usuario, SYSDATE,:id)";
		
		$params = array(":resultado" => $resultado,  
						":usuario" => $usuario, 
						":id" => $jt_id);		
						
		DBExecSql($conn, $sqlInsert, $params);                  
		
		DBCommit($conn);
		return true;
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function ObtenerPermisosMenu($idusuario){
	try{
		global $conn;       
		
		$sql = "SELECT UM_IDMENUWEB
				  FROM legales.lum_usuariomenu
				WHERE  um_idusuario = :idusuario
				   AND um_fechabaja IS NULL";
		
		$params = array(":idusuario" => $idusuario );    
						
		$stmt = DBExecSql($conn, $sql, $params);
			
		while ($row = DBGetQuery($stmt)) {		
			$result[]= $row ['UM_IDMENUWEB'] ;		
		}
		if(!isset($result))
			$result[]= 0;		
			
		return $result;		
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function  ObtenerInstanciaaCambiar($nrojuicio){
	try{
		global $conn;       
		
		$sql = " SELECT jz_idinstancia 
				   FROM legales.ljt_juicioentramite, 
						legales.lfu_fuero, 
						legales.ljz_juzgado, 
						legales.lin_instancia 
				  WHERE ( (ljt_juicioentramite.jt_idfuero = lfu_fuero.fu_id)
					AND (ljt_juicioentramite.jt_idjuzgado = ljz_juzgado.jz_id)
					AND (lin_instancia.in_id = ljz_juzgado.jz_idinstancia)
						)
					AND ljt_juicioentramite.jt_id = :nrojuicio";
		
		$params = array(":nrojuicio" => $nrojuicio );    
						
		$result = ValorSql($sql, "", $params);    
		return $result;			   
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function  ObtenerAnioValidoExpediente($anioExpediente){
	try{
		global $conn;       
		$sqlAnio = "SELECT nvl((
					SELECT 'S' valido   
					  FROM DUAL 
					 WHERE TO_NUMBER(TO_CHAR(art.actualdate, 'YY')) >= TO_NUMBER(:anioExpediente) 
						OR TO_NUMBER(:anioExpediente) > 96),'N') 
				VALIDO FROM DUAL";

		$params = array(":anioExpediente" => $anioExpediente );    					
		$result = ValorSql($sqlAnio, "", $params);    	
		
		return $result;	
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}

function ObtenerFechadeNotificacion($nrojuicio) {
	try{
		global $conn;       
		$sql = "SELECT jt_fechanotificacionjuicio 
				  FROM legales.ljt_juicioentramite 
				 WHERE jt_id = :nrojuicio";

		$params = array(":nrojuicio" => $nrojuicio );    					
		$result = ValorSql($sql, "", $params);    			
		return $result;			   	
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function ObtenerMontoDemandadoObligatorio($idreclamo){
	try{
		global $conn;       
		$sql = "SELECT tr_id, NVL(tr_requiereimporte, 'N') requiereimporte
				FROM legales.ltr_tiporesultadosentencia 
				WHERE tr_id = :idreclamo";

		$params = array(":idreclamo" => $idreclamo );    					
		$result = ValorSql($sql, "", $params);    	
		
		return $result;			   
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function ObtenerMontoDemandadoObligatorioLista(){
	try{
		global $conn;       
		$sql = "SELECT TR_ID, NVL(tr_requiereimporte, 'N') REQUIEREIMPORTE
				FROM legales.ltr_tiporesultadosentencia 
				ORDER BY tr_id";

		$params = array();    					
		$stmt = DBExecSql($conn, $sql, $params);
		$result = "";
		
		while ($row = DBGetQuery($stmt)) {		
			$result .= " listaMontoDemandado[".$row['TR_ID']."] = '".$row['REQUIEREIMPORTE']."'; ";		
		}
		
		return "listaMontoDemandado = new Array(); ".$result;			   
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function ObtenerEsFederal($idjuicio){
	try{
		global $conn;       
		$sql = "SELECT jt_federal FEDERAL
				  FROM legales.ljt_juicioentramite 
				 WHERE jt_id = :idjuicio";

		$params = array(":idjuicio" => $idjuicio );    					
		$result = ValorSql($sql, "", $params);    	
		
		return $result;			   
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     				
}


function BuscarPeritosListado($CuitCuil, $TipoPerito, $Apellido='', $Nombre='', $id = 0){
	try{				
		global $conn;				
		
		$sql ="SELECT 
				PE_ID ID, 
				PE_APELLIDO || ' ' || PE_NOMBREINDIVIDUAL NOMBRE, 
				PE_CUITCUIL CUIT, 
				PE_NOMBRE, 
				PE_APELLIDO,
				PE_IDTIPOPERITO, 
				PE_PARTEOFICIO, 
				PE_USUALTA, 
				PE_FECHAALTA,
				PE_DIRECCION,  
				PE_NOMBREINDIVIDUAL,
				PE_DIRECCIONELECTRONICA EMAIL, 
				PE_TELEFONO TELEFONO
			  FROM LEGALES.LPE_PERITO 
			  WHERE (PE_FECHABAJA IS NULL)";
			  
		$params = array();
		$AddTipoPerito = false;
		
		if( trim($CuitCuil) > '' ){
			$sql .=" AND PE_CUITCUIL = :CUITCUIL";
			$params['CUITCUIL'] = trim($CuitCuil);
		}
		if( trim($Apellido) > '' ){
			$sql .=" AND UPPER(PE_APELLIDO) LIKE :apellido";
			$params['apellido'] = "%".strtoupper(trim($Apellido))."%";
			$AddTipoPerito = true;
		}
		if( trim($Nombre) > '' ){
			$sql .=" AND UPPER(PE_NOMBREINDIVIDUAL) LIKE :nombre";
			$params['nombre'] = "%".strtoupper(trim($Nombre))."%";
			$AddTipoPerito = true;
		}
		
		if( $AddTipoPerito ){
			$sql .=" AND PE_IDTIPOPERITO = :TIPOPERITO ";
			$params['TIPOPERITO'] = $TipoPerito;
		}
		
		if( $id > 0 ){
			$sql .=" AND PE_ID = :id ";
			$params['id'] = $id;
		}
		
		$sql .=" AND ROWNUM < 100 ORDER BY PE_APELLIDO, PE_NOMBREINDIVIDUAL";		
			
		@$stmt = DBExecSql($conn, $sql, $params);
		$i = 0;
		$result = '[';		
		
		while ($row = DBGetQuery($stmt, 1, false)) {
	        
				if($i == 0){
					$result .= '{ "id": "'.$row['ID'].'", "cuit": "'.$row['CUIT'].'", "cuitnombre" : "'.$row['CUIT'].' '.RemplaceComillas($row['PE_APELLIDO']).' '.RemplaceComillas($row['PE_NOMBREINDIVIDUAL']).'", ';				
				}
				else
					$result .= ',{ "id": "'.$row['ID'].'", "cuit": "'.$row['CUIT'].'", "cuitnombre" : "'.$row['CUIT'].' '.RemplaceComillas($row['PE_APELLIDO']).' '.RemplaceComillas($row['PE_NOMBREINDIVIDUAL']).'", ';				
					
				$result .= '"nombreNOUSAR": "'.RemplaceComillas($row['PE_NOMBRE']).'", ';
				$result .= '"apellido": "'.RemplaceComillas($row['PE_APELLIDO']).'", ';
				$result .= '"idtipoperito": "'.$row['PE_IDTIPOPERITO'].'", ';
				$result .= '"parteoficio": "'.$row['PE_PARTEOFICIO'].'", ';
				$result .= '"usualta": "'.$row['PE_USUALTA'].'", ';
				$result .= '"fechaalta": "'.$row['PE_FECHAALTA'].'", ';
				$result .= '"direccion": "'.RemplaceComillas($row['PE_DIRECCION']).'", ';
				$result .= '"nombreindividual": "'.RemplaceComillas($row['PE_NOMBREINDIVIDUAL']).'", ';
				$result .= '"email": "'.RemplaceComillas($row['EMAIL']).'", ';
				$result .= '"telefono": "'.RemplaceComillas($row['TELEFONO']).'"} ';
			
			$i++;
			
		}	
		$result .= "]";
		
		return utf8_encode($result); 		
		//return trim($result); 		
		
	}catch (Exception $e) {                  		
		return $e->getMessage();
    }     				
}  

function ObtenerNivelUsuario($usuario, $clave){
	try{		
		global $conn;       
		$sql = "SELECT   NU_ID,
						 BO_IDESTUDIOJURIDICO,
						 NU_FORZARCLAVE,
						 NU_USUARIO,
						 EJ_NOMBREESTUDIO,
						 BO_NOMBRE
				  FROM   legales.lnu_nivelusuario, legales.lbo_abogado, legales.lej_estudiojuridico
				 WHERE   nu_usuario = upper(:usuario)
					 AND nu_claveweb = :clave
					 AND nu_idabogado = bo_id
					 AND bo_idestudiojuridico = ej_id";

		$params = array(":usuario" => $usuario, ":clave" => $clave );    					
						
		$stmt = DBExecSql($conn, $sql, $params);		
		
		while ($row = DBGetQuery($stmt)){		
			return $row;		
		}
		
		return false;
		
	}catch (Exception $e) {
        DBRollback($conn);                		
		ErrorConeccionDatos($e->getMessage());
		return false;
    }   
}

function DatosEventoJuicioTramite($idEvento){
	try{
		global $conn;      
		//1225820
		$sql =" SELECT  ET_FECHAEVENTO FECHAEVENTO,
						LTE_TIPOEVENTO.TE_DESCRIPCION DESCRIPCION      
				FROM   	legales.let_eventojuicioentramite, legales.lte_tipoevento 
				WHERE   (et_idtipoevento = lte_tipoevento.te_id)
				AND 	et_id =   :idEvento ";
							 
		if (isset($_REQUEST["idEvento"]))
			$idEvento = $_REQUEST["idEvento"];				
			
			$params = array(":idEvento" => $idEvento );
			$stmt = DBExecSql($conn, $sql, $params);
		
		while ($row = DBGetQuery($stmt)) {		
			return $row;		
		}
		
		if(!isset($row)){
			$row = array("FECHAEVENTO" => '',
						"DESCRIPCION" => '');		
			return $row;		
		}
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function DeleteArchivoEventoJuicioTramite($EventoID, $eaid){
	
	try{	
		global $conn;	
	    //Baja logica de un archivo adjunto	    
		$usuario = $_SESSION["usuario"];

		$sqlUpdate = "UPDATE   legales.lea_eventoarchivoasociado
					   SET   ea_usubaja = :usuario, ea_fechabaja = SYSDATE
					 WHERE   ea_id = :eaid ";
			   
		$params = array(":eaid" => $eaid, ":usuario" => $usuario);

		DBExecSql($conn, $sqlUpdate , $params);				  			    	
		DBCommit($conn);    
		
		return true;
	
	}catch (Exception $e) {
	    DBRollback($conn);  
		throw new Exception($e->getMessage());
	    return false;
	}    
}
/*---------------------------------------------------------------------*/
function PathLocalAdjuntoPercia($idAdjuntoPericia){
	/*dado un id de la tabla "lea_eventoarchivoasociado" Retorna el path local del server de un Adjunto */
	try{
		global $conn;      				
		/*
		Esto apuntaria al disco local "D:" para la busqueda de imagenes en produccion aun no se puede usar esto fallaria
		las imagenes estan en un server distinto..
		
		$sql =" SELECT  LOWER(REPLACE (REPLACE (UPPER ( TRIM(PA_PATHARCHIVO)),  UPPER ('".STORAGE_DATA_RAIZ_SERVER."'), UPPER ('D:')),'/','\')) 						
				  FROM   legales.lpa_periciaarchivoasociado 
				 WHERE   PA_ID = :idAdjuntoPericia   ";
		*/
		$sql =" SELECT  TRIM(PA_PATHARCHIVO)
				  FROM   legales.lpa_periciaarchivoasociado 
				 WHERE   PA_ID = :idAdjuntoPericia   ";
				 
		$params = array(":idAdjuntoPericia" => $idAdjuntoPericia);
		$pathLocal = ValorSql($sql, '', $params);
		
		return $pathLocal;
		
    }catch (Exception $e) {
        DBRollback($conn);          		
		ErrorConeccionDatos($e->getMessage());
		return false;
    }    	
}
function PathLocalAdjuntoEvento($idAdjuntoEvento){
	/*dado un id de la tabla "lea_eventoarchivoasociado" Retorna el path local del server de un Adjunto */
	try{
		global $conn;      		
		/*
		ulizo la ruta 
		$sql =" SELECT  LOWER(REPLACE (REPLACE (UPPER ( TRIM(EA_PATHARCHIVO)),  UPPER ('".STORAGE_DATA_RAIZ_SERVER."'), UPPER ('D:')),'/','\')) 						
				  FROM   legales.lea_eventoarchivoasociado 
				 WHERE   EA_ID = :idAdjuntoEvento   ";		
		*/	 
		$sql =" SELECT  TRIM(EA_PATHARCHIVO)
				  FROM   legales.lea_eventoarchivoasociado 
				 WHERE   EA_ID = :idAdjuntoEvento   ";
				 
		$params = array(":idAdjuntoEvento" => $idAdjuntoEvento);
		$pathLocal = ValorSql($sql, '', $params);
		
		return $pathLocal;
		
    }catch (Exception $e) {
        DBRollback($conn);          		
		ErrorConeccionDatos($e->getMessage());
		return false;
    }    	
}

/*---------------------------------------------------------------------*/

function DatosArchivosEventoJuicioTramite($idEvento, $bloquearEvento){
	/*
		Arma parte (registro y celda) de la tabla 
	*/
	try{
		global $conn;      
		$contarAdjuntos = 0;
		$pathArchivosServer = '';
		
		$sql =" SELECT  EA_DESCRIPCION DESCRIPCION, 
						TRIM(EA_PATHARCHIVO) PATHARCHIVO, 
						EA_ID ID,		
						EA_FECHAALTA FECHAALTA,
						LOWER(REPLACE (REPLACE (UPPER ( TRIM(EA_PATHARCHIVO)), UPPER ('".STORAGE_DATA_RAIZ."\LEGALES'), UPPER ('STORAGE_LEGALES')),'\','/')) PATHARCHIVOFORMAT
				  FROM   legales.lea_eventoarchivoasociado 
				 WHERE   ea_ideventojuicioentraMITE = :IDEVENTO
					AND EA_FECHABAJA IS NULL  
					ORDER BY EA_FECHAALTA ASC ";
							 
		$params = array(":idEvento" => $idEvento );
		$stmt = DBExecSql($conn, $sql, $params);
	

		$resultado = "<tr><td class='title_NegroFndAzul' >Archivos Adjuntos:</td></tr>";			

		$extensionesShowInBrowser = array("JPG", "JPEG", "PNG", "GIF", "BMP", "PDF");		
		$extensiones = $extensionesShowInBrowser;
		
		
		while ($row = DBGetQuery($stmt)) {					
			
			$pathArchivosLocal = TRIM($row["PATHARCHIVO"]);												
			$IDARCHIVO = TRIM($row["ID"]);												
			
			$patharchivo = TRIM($row["PATHARCHIVOFORMAT"]);												
			$patharchivo = str_replace('storage_legales','Storage_Legales',$patharchivo);
			
			$fExt = ExtToFile($pathArchivosLocal);
			$fExt = strtoupper($fExt);
			$extencion = ' extencion='.$fExt.' ';
			
			$resultado .= "<tr>
							<td colspan='1' class='item_Blanco' style='padding-left:35px' >
								<b><font class='TextoTablaEJ' > ";
			
			$fileTitle = "Fecha: ".$row["FECHAALTA"];
		
			$fileEncode = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php?archivodescarga=".base64_encode($IDARCHIVO)."&evento=d";
			$resultado .= "<a class='enlaces' href='".trim($fileEncode)."'  title='".$fileTitle."'  > ".$row["DESCRIPCION"]." </a>";
		
			
	/** EVENTOS **/
/*	
	if(in_array($fExt, $extensiones)){ 
		$textreemp = ReemplazaDataStorage($pathArchivosLocal);

		$linkref = getFile($textreemp);		
		//$resultado .= "<p> ".$pathArchivosLocal;  
		//$resultado .= "<p> ".$textreemp;  
		$resultado .= "<a href='".$linkref."' target='_blank' title='".$fileTitle." + ' style='padding-right:5px' >".$row["DESCRIPCION"]."</a> ";
	}
	else{				
		$fileEncode = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php?archivodescarga=".base64_encode($IDARCHIVO)."&evento=d";
		$resultado .= "<a class='enlaces' href='".trim($fileEncode)."'  title='".$fileTitle."'  > ".$row["DESCRIPCION"]." </a>";			
	}
*/
		
			if(!$bloquearEvento){
				$resultado .= "	<input class='btnElimTransp' id='btneliminar1' name='btneliminar' type='button' value='' 
									onclick='ElimianarAdjuntosEvento(".$idEvento.",".$row["ID"].")' >	";
			}			
			
			$resultado .= " </font></b>	
							</td></tr> ";	
							
			$contarAdjuntos++;
		}
		
		if($contarAdjuntos > 0){
			//$resultado .= "<tr><td class='celdaFondoTituloEJ'><b><font class='TextoTituloTablaEJ' >Archivos Asociados ".$contarAdjuntos."</font></b></td></tr>";
			//$resultado .= "<tr><td class='celdaFondoTituloEJ' style='height:2px;'></td></tr>";			
			$resultado .= "<tr><td colspan='1' class='item_Blanco'><b><font class='TextoTablaEJ' ></font></b></td></tr>";				
		}else{			
			$resultado .= "	<tr><td colspan='1' class='item_Blanco' style='padding-left:35px' ><b><font class='TextoTablaEJ' >No existen archivos adjuntos </font></b></td></tr>
						<tr><td colspan='1' class='item_Blanco'><b><font class='TextoTablaEJ' ></font></b></td></tr>";			
		}
		
		return $resultado;			
		
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function GetNumJuicioPorCarpeta($numcarpeta){
		// Dado un numero de carpeta obtiene el juicio
	try{
		global $conn;      		
		
		$sql = "SELECT   ljt_juicioentramite.jt_id  
				FROM   legales.ljt_juicioentramite 
				WHERE   jt_numerocarpeta = :numcarpeta 
				 AND (ljt_juicioentramite.jt_estadomediacion LIKE '%J%') ";
				 
		$params = array(":numcarpeta" => $numcarpeta);
		$numjuicio = ValorSql($sql, '', $params);
		
		return $numjuicio;
		
    }catch (Exception $e) {
        DBRollback($conn);          		
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     							
}

function NexValOracle($tablaSeqId){
	//Retorna el proximo id dado un sequencer Ej:'legales.seq_lpj_id'
	return	GetSecNextValOracle($tablaSeqId);
}

function InsertAdjuntoEvento($ea_id, $ea_descripcion, $idjuicio, $nomArchivo, $ea_ideventojuicioentramite){
	
	try{
		global $conn;      		
		
		//$sql = "select pa_valor from legales.lpa_parametro where pa_clave =  'DIRECTORIOARCHIVOS' ";
		//$directoriodestino = ValorSql($sql);
		$directoriodestino = Get_Lpa_Parametro('DIRECTORIOARCHIVOS');
			
		$ea_patharchivo = $directoriodestino.'\\'.$idjuicio.'\\'.$nomArchivo;
		$ea_usualta = $_SESSION["usuario"];
				
		/*
		$sqlInsertAdjunto = "INSERT INTO legales.lea_eventoarchivoasociado (ea_id,
					   ea_descripcion,
					   ea_patharchivo,
					   ea_ideventojuicioentramite,
					   ea_usualta,
					   ea_fechaalta)
			  values   (ea_id,
						ea_descripcion,
						ea_patharchivo,
						ea_ideventojuicioentramite,
						ea_usualta,
						SYSDATE) ";
			*/
			
			$sqlInsertAdjunto = "INSERT INTO legales.lea_eventoarchivoasociado (ea_id,
					   ea_descripcion,
					   ea_patharchivo,
					   ea_ideventojuicioentramite,
					   ea_usualta,
					   ea_fechaalta)
			  values   (".$ea_id.",
						UPPER('".$ea_descripcion."'),
						'".$ea_patharchivo."',
						".$ea_ideventojuicioentramite.",
						UPPER('".$ea_usualta."'),
						SYSDATE) ";
	
			$params = array();
		/*
		$params = array(":ea_id" => $ea_id,
						":ea_descripcion" => $ea_descripcion,	            
						":ea_patharchivo" => $ea_patharchivo,	            
						":ea_ideventojuicioentramite" => $ea_ideventojuicioentramite,	            
						":ea_usualta" => $ea_usualta);		
		*/
		DBExecSql($conn, $sqlInsertAdjunto, $params);				  
	    DBCommit($conn);
	    return true;
    }catch (Exception $e) {
        DBRollback($conn);          		
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}


function DatosTipoPeritaje($idPeritaje){
	try{
		global $conn;      
		//798837
		$sql =" SELECT   ltp.tp_descripcion DESCRIPCION, 
						pj_fechanotificacion FECHAPERITAJE
				  FROM   legales.lpj_peritajejuicio lpj, legales.ltp_tipopericia ltp
				 WHERE   lpj.pj_idtipopericia = ltp.tp_id(+)
					 AND pj_id = :idPeritaje ";
							 
		if (isset($_REQUEST["idPeritaje"]))	$idPeritaje = $_REQUEST["idPeritaje"];				
			
			$params = array(":idPeritaje" => $idPeritaje );
			$stmt = DBExecSql($conn, $sql, $params);
		
		while ($row = DBGetQuery($stmt)) {		
			return $row;		
		}
		
		if(!isset($row)){
			$row = array("FECHAPERITAJE" => '',
						"DESCRIPCION" => '');		
			return $row;		
		}
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}

function DatosArchivosAdjPeritje($idPeritaje, $bloquearPeritaje){
	/*
		Arma parte (registro y celda) de la tabla 
	*/
	try{
		global $conn;      
		$contarAdjuntos = 0;
		
		$sql =" SELECT  PA_DESCRIPCION DESCRIPCION, 
						TRIM(PA_PATHARCHIVO) PATHARCHIVO, 
						PA_ID ID,		
						PA_FECHAALTA FECHAALTA,						
						LOWER(REPLACE (REPLACE (UPPER ( TRIM(PA_PATHARCHIVO)), UPPER ('".STORAGE_DATA_RAIZ."\LEGALES'), UPPER ('STORAGE_LEGALES')),'\','/')) PATHARCHIVOFORMAT
																
				  FROM   legales.lpa_periciaarchivoasociado
				 WHERE   pa_idpericias = :idPeritaje
					 AND pa_fechabaja IS NULL 
					 ORDER BY PA_FECHAALTA ASC ";
							 
		$params = array(":idPeritaje" => $idPeritaje );
		$stmt = DBExecSql($conn, $sql, $params);
						
		$resultado = "<tr><td class='title_NegroFndAzul' >Archivos Adjuntos:</td></tr>";			
		$extensionesShowInBrowser = array("JPG", "JPEG", "PNG", "GIF", "BMP", "PDF");
		$extensiones = $extensionesShowInBrowser;
		
		while ($row = DBGetQuery($stmt)) {					
/*
			$patharchivo = TRIM($row["PATHARCHIVOFORMAT"]);		
			$patharchivo = str_replace('storage_legales','Storage_Legales',$patharchivo);
			$patharchivo = str_replace('pericias','PERICIAS',$patharchivo);
*/			
			$patharchivo = TRIM($row["PATHARCHIVO"]);		
			/*------------------------------------------------*/
			$pathArchivosLocal = TRIM($row["PATHARCHIVO"]);												
			$IDARCHIVO = TRIM($row["ID"]);															
			$fExt = ExtToFile($pathArchivosLocal);
			$fExt = strtoupper($fExt);
			/*------------------------------------------------*/
			
			$resultado .= "<tr>
							<td colspan='1' class='item_Blanco' style='padding-left:35px' >
								<b><font class='TextoTablaEJ' >";
			
			$fileTitle = "Fecha: ".$row["FECHAALTA"];
			
			$fileEncode = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php?archivodescarga=".base64_encode($IDARCHIVO)."&pericia=d";
			$resultado .= "<a class='enlaces' href='".trim($fileEncode)."'  title='".$fileTitle."' > ".$row["DESCRIPCION"]." </a>";				 			
			
	/** PERITAJE **/		
/*	
	if(in_array($fExt, $extensiones)){ 
		$textreemp = ReemplazaDataStorage($pathArchivosLocal);
		$linkref = getFile($textreemp);		
		//$resultado .= "<p> ".$pathArchivosLocal;  
		//$resultado .= "<p> ".$textreemp;  
		
		$resultado .= "<a href='".$linkref."' target='_blank' title='".$fileTitle." + ' style='padding-right:5px' >".$row["DESCRIPCION"]."</a> ";
	}
	else{				
		//$resultado .= "<p> ".$IDARCHIVO;  
		$fileEncode = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php?archivodescarga=".base64_encode($IDARCHIVO)."&evento=d";
		$resultado .= "<a class='enlaces' href='".trim($fileEncode)."'  title='".$fileTitle."'  > ".$row["DESCRIPCION"]." </a>";			
	}
*/	
			/*
			if(in_array($fExt, $extensiones)){ 
				$resultado .= "<a href='".trim($patharchivo)."' target='_blank'  title='".$fileTitle."' style='padding-right:5px'>".Trim($row["DESCRIPCION"])."</a> ";
			}else{				
				$fileEncode = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php?archivodescarga=".base64_encode($IDARCHIVO)."&pericia=d";
				$resultado .= "<a class='enlaces' href='".trim($fileEncode)."'  title='".$fileTitle."' > ".$row["DESCRIPCION"]." </a>";				 			
			}
			*/
			if(!$bloquearPeritaje){
				$resultado .= "	<input class='btnElimTransp' id='btneliminar1' name='btneliminar' type='button' value='' 
									onclick='ElimianarAdjuntosPericia(".$idPeritaje.",".$row["ID"].")' >	";
			}
			
			$resultado .= " </font></b>	
							</td></tr> ";	
							
			$contarAdjuntos++;
		}
		if($contarAdjuntos > 0){						
			$resultado .= "<tr><td colspan='1' class='item_Blanco'><b><font class='TextoTablaEJ' ></font></b></td></tr>";			
		}else{
			
			$resultado .= "	<tr><td colspan='1' class='item_Blanco' style='padding-left:35px'><b><font class='TextoTablaEJ' >No existen archivos adjuntos </font></b></td></tr>
						<tr><td colspan='1' class='item_Blanco'><b><font class='TextoTablaEJ' ></font></b></td></tr>";			
		}
		
		return $resultado;			
		
	}catch (Exception $e) {
        DBRollback($conn);                
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}


function DeleteArchivoPericia($EventoID, $eaid){
	
	try{	
		global $conn;	
	    //Baja logica de un archivo adjunto	    
		$usuario = $_SESSION["usuario"];

		$sqlUpdate = "UPDATE   legales.lpa_periciaarchivoasociado
					   SET   PA_USUBAJA = :usuario, PA_FECHABAJA = SYSDATE
					 WHERE   pa_id = :eaid ";
			   
		$params = array(":eaid" => $eaid, ":usuario" => $usuario);

		DBExecSql($conn, $sqlUpdate , $params);				  			    	
		DBCommit($conn);    
		
		return true;
	
	}catch (Exception $e) {
	    DBRollback($conn);  
		throw new Exception($e->getMessage());
	    return false;
	}    
}

function InsertAdjuntoPericia($pa_id, $pa_descripcion, $idjuicio, $nomArchivo, $pa_idPericia){
	
	try{
		global $conn;      		
		
		// $sql = "select pa_valor from legales.lpa_parametro where pa_clave =  'DIRECTORIOARCHIVOSPERICIA' ";
		// $directoriodestino = ValorSql($sql);
		$directoriodestino = Get_Lpa_Parametro('DIRECTORIOARCHIVOSPERICIA');
			
		$pa_patharchivo = $directoriodestino.'\\'.$idjuicio.'\\'.$nomArchivo;
		$pa_usualta = $_SESSION["usuario"];
		
			
			$sqlInsertAdjunto = "INSERT INTO legales.lpa_periciaarchivoasociado (
									PA_ID, 
									PA_DESCRIPCION, 
									PA_PATHARCHIVO,   
									PA_IDPERICIAS, 
									PA_USUALTA, 
									PA_FECHAALTA)
			  values   (".$pa_id.",
						UPPER('".$pa_descripcion."'),
						'".$pa_patharchivo."',
						".$pa_idPericia.",
						UPPER('".$pa_usualta."'),
						SYSDATE) ";
	
			$params = array();

		DBExecSql($conn, $sqlInsertAdjunto, $params);				  
	    DBCommit($conn);
	    return true;
    }catch (Exception $e) {
        DBRollback($conn);          		
		ErrorConeccionDatos($e->getMessage());
		return false;
    }     						
}