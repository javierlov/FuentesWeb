<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");

set_include_path('/usr/lib/pear');

@session_start(); 

function CreateSelectHTML($sql, $idCombo, $ComboStyle='', $defaultValue=0){
	global $conn;			
	
	$params = array();
	$stmt = DBExecSql($conn, $sql, $params);
	
	$result = '<select id="'.$idCombo.'"  name="'.$idCombo.'"  style="'.$ComboStyle.'" >';
	
	if($defaultValue == 0)	$result .= AddComboOption('0', '', true);		
	else					$result .= AddComboOption('0', '', false);		
	
	while ($row = DBGetQuery($stmt, 1, false)) {
		$selectOpt = false;
		
		if(is_numeric($defaultValue)){
			$selectOpt = ComparaInt($row['ID'], $defaultValue);
		}else{		
			if($defaultValue != '0'){
				if(!$selectOpt) $selectOpt = ComparaStr($row['ID'], $defaultValue);			
			}
		}
		
		$result .= AddComboOption(" \"".$row['ID']."\" ", $row['DESCRIPCION'], $selectOpt);		
	}	

	$result .=  '</select>';	
	return $result; 		 
}

function CreateArrayJSUI($sql, $ArrayName, $fields='', $params = array() ){
	/*CreateArrayJS: convierte los datos retornados de la base en un array js (value label)*/
	global $conn;			
	/*si no se pasa un array de fields se usa este por defecto*/	
	if($fields == ''){
		$fields['value'] = "ID";
		$fields['label'] = "DESCRIPCION";
	}
	
	//$params = array();
	$stmt = DBExecSql($conn, $sql, $params);
	$i=0;
	$result = 'var '.$ArrayName.' = [';	
	
	while ($row = DBGetQuery($stmt, 1, false)) {
		if($i>0) $result .= ", ";
		$x=0;
	    
		$result .= "{ ";
		foreach($fields as $key=>$value){
			if($x>0) $result .= ", ";
			$result .= $key.": '".$row[$value]."' ";
			$x++;
		}
		$result .= "} ";
		$i++;
	}	

	$result .=  ' ]';	
	return $result; 		 
}

function CreateArrayJScript($sql, $ArrayName, $fieldName){
	/*CreateArrayJScript: convierte los datos retornados de la base en un array js (solo un campo para aramar el array)*/
	try{	
		global $conn;			
		$params = array();
		$stmt = DBExecSql($conn, $sql, $params);
		
		$i=0;
		
		$result = 'var '.$ArrayName.' = [';	
		
		while ($row = DBGetQuery($stmt, 1, false)) {
			if($i>0) $result .= ", ";		
			$result .= " '".$row[$fieldName]."' ";		
			$i++;
		}	

		$result .=  ' ]';	
		return $result; 		 
		
	}catch (Exception $e){				
		EscribirLogTxt1('Error CreateArrayJScript', $e->getMessage() );	
		return false;		 
	}		
}

function AddComboOption($clave, $texto, $isSelected=false){
/*
 * AddComboOption: Agrega items a un combo 
 */
	$result ='<option value='.$clave;	
	if($isSelected == true){			
		$result .= " selected='selected' ";	
	}
	$result .= '>'.$texto.'</option> ';
	return $result;
}

function ComparaInt($int1, $int2){						
/*
 * ComparaInt: compara dos int y retorna true si son iguales
 */ 
	$result = FALSE;	
	try	{			
		if( (is_numeric($int1)) and (is_numeric($int2)) ) {
			if( intval($int1) == intval($int2)){
				$result = TRUE;								
			}				
		}			
		return $result;
	} 
	catch(Exception $e) {			
		return FALSE;
	}
}	

function ComparaStr($str1, $str2){		
/*
 * ComparaStr: compara dos strings y retorna true si son iguales
 */ 
	$str1 = strtoupper(trim($str1));
	$str2 = strtoupper(trim($str2));
	$result = (strcmp($str1, $str2) == 0);				 
	
	return $result;
}

function RemplaceComillas($texto){
	$texto = trim($texto);
	$texto = str_replace('"', '\'', $texto);
	return $texto;
}

function ValidaSoloNumerosRExp($texto){
	//valida que solo contenga numeros 
	$texto = trim($texto);
	$regex = '/^[0-9]+$/';
	
	if( preg_match($regex, $texto) == 1 )
		return true;
	else
		return false;
}

function ValidaSoloLetrasEspRExp($texto){
	// valida que solo tenga letras de a-z ρ y acentuadas
	$texto = trim($texto);
	$regex = '/^([a-z ραινσϊ]{2,60})$/i';
	
	if( preg_match($regex, $texto) == 1 )
		return true;
	else
		return false;
}
