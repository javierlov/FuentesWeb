<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

function UpdateMasDatosJuicios($Domicilio, $Telefonos, $Fax, $Email, $usuario, $idJuicio){
	try{
		global $conn;
		//throw new Exception(" UpdateMasDatosJuicios jajaja");		
		//----------------------------------------------------------------------			
		$sqlJuzgado = "SELECT JT_IDJUZGADO 
							FROM legales.ljt_juicioentramite   
							WHERE jt_id = :idJuicio";
		
		$Jparams = array(":IdJuicio" => $idJuicio);
		$idJuzgado = ValorSql($sqlJuzgado, '0', $Jparams);		
		//----------------------------------------------------------------------	
		$sqlUpdate = "UPDATE legales.ljz_juzgado  
						SET jz_usumodif =  :usuario,
							jz_fechamodif = SYSDATE,  
							jz_direccion =  :Domicilio, 
							jz_telefono =  :Telefonos, 
							jz_fax =  :Fax, 
							jz_email =  :Email
					  WHERE jz_id =  :idJuzgado ";
						
		$params = array(":usuario" => $usuario,
						":Domicilio" => $Domicilio,
						":Telefonos" => $Telefonos,
						":Fax" => $Fax,
						":Email" => $Email,
						":idJuzgado" => $idJuzgado );

		DBExecSql($conn, $sqlUpdate, $params);		
		//----------------------------------------------------------------------	
		DBCommit($conn);
		
		return true;
	}
	catch (Exception $e) {
		DBRollback($conn);
		throw new Exception($e->getMessage());		
		return false;
	}

}
