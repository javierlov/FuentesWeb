<?php
function JSjqueryUIVersion(){
	/*retorna el jquery a utilizar dependiendo de la version de navegador*/
	$info = DetectaVersion();
	$version = intval($info["version"]);
	$resultado = '';

	if($info["browser"] == 'MSIE' &&  $version < 9)				
		$resultado = "<script src='/js/rar/jq/jquery-ui.min.1.9.1.js'></script>";		
	else
		$resultado = "<script src='/js/rar/jq/jquery-ui-custom.js'></script>";		
	return $resultado;
}

function JSjqueryVersion(){
	/*retorna el jquery a utilizar dependiendo de la version de navegador*/
	$info = DetectaVersion();
	$version = intval($info["version"]);
	$resultado = '';

	if($info["browser"] == 'MSIE' &&  $version < 9)				
		$resultado = "<script type='text/javascript' src='/js/rar/jq/jquery-1.7.2.min.js'></script>";
	else
		$resultado = "<script type='text/javascript' src='/js/rar/jq/jquery.js'></script>";		
	return $resultado;
}

function DetectaVersion()
{
	$browser=array("MSIE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME", "RV" );
	$os=array("WIN","MAC","LINUX","UBUNTU");
	
	# definimos unos valores por defecto para el navegador y el sistema operativo
	$info['browser'] = "OTHER";
	$info['os'] = "OTHER";
	$info['version'] = "";
	
	# buscamos el navegador con su sistema operativo
	foreach($browser as $parent)
	{
		$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
		$f = $s + strlen($parent);
		$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
		$version = preg_replace('/[^0-9,.]/','',$version);
		if ($s)
		{
			if(strtoupper($parent) == 'RV')
				$info['browser'] = "MSIE";
			else
				$info['browser'] = $parent;
				
			$info['version'] = $version;
		}
	}
	
	# obtenemos el sistema operativo
	foreach($os as $val)
	{
		if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
			$info['os'] = $val;
	}
		
	return $info;
}

function RandonNumberParameter(){
	/*retorna un valor randon (usar como parametro)*/
	return "?".rand(111, 999999);
}
function RetronaXML($mensaje){			
	/*mensaje de retorno formateado para ajax */
	$xml="<?xml version=\"1.0\"?>\n";
	$xml .= "<result>".$mensaje."</result>";			
	header('Content-Type: text/xml');
	echo utf8_decode($xml);
}

function CuitExtractGuion($cuit){
	/*le quita los guiones(-) al cuit 
		esto se usa asi en algunos lugares del sistema para guardar los datos 
		en la base de datos*/
	return str_replace('-', '', $cuit); 
}

function ReemplazaCorchetesQRY($sql){
	/*Reemplaza los corchetes ( [] ) por simbolos de pregunta ( ¿? )
		esto se usa en las funciones de querys DB */
	$sqlResult = $sql;
	$sqlResult = str_replace('[','¿',$sqlResult);	
	$sqlResult = str_replace(']','?',$sqlResult);		
	return $sqlResult;	
}

function ReemplazaBracesQRY($sql){
	/*Reemplaza las llaves o curly braces o brackets ( {} ) por simbolos de pregunta ( ¿? )
		esto se usa en las funciones de querys DB */
	$sqlResult = $sql;
	$sqlResult = str_replace('{','¿',$sqlResult);	
	$sqlResult = str_replace('}','?',$sqlResult);		
	return $sqlResult;	
}

function ReemplazaAngleBracketsQRY($sql){
	/*Reemplaza los angle brackets ( <> ) por simbolos de pregunta ( ¿? )
		esto se usa en las funciones de querys DB */
	$sqlResult = $sql;
	$sqlResult = str_replace('<','¿',$sqlResult);	
	$sqlResult = str_replace('>','?',$sqlResult);		
	return $sqlResult;	
}

function ReemplazaCarateres($texto, $charOrig, $charReemplaza ){
	/*Reemplaza las llaves o curly braces o brackets ( {} ) por simbolos de pregunta ( ¿? )
		esto se usa en las funciones de querys DB */
	$sqlResult = $texto;
	$sqlResult = str_replace($charOrig, $charReemplaza,$sqlResult);		
	return $sqlResult;	
}

function ReemplazaCaracterStr($texto, $caratActual, $caratReemp ){
	/*Reemplaza los corchetes ( [] ) por simbolos de pregunta ( ¿? )
		esto se usa en las funciones de querys DB */
	$sqlResult = $texto;
	$sqlResult = str_replace($caratActual,$caratReemp,$sqlResult);	
		
	return $sqlResult;	
}

function StringToArray($string){
	//convierte un string separado por comas en un array
	$porciones = explode(",", $string);
	return $porciones;
}


function ExtractChar($texto, $char){
	/*Le quita un character a un texto*/
	return str_replace($char, '', $texto); 
}


function BuscaSubStr($textoCompleto, $textoaBuscar){
	/*Busca un string dentro de otro string retorna true si lo encontro*/
	$textoCompleto = strtoupper($textoCompleto);
	$textoaBuscar = strtoupper($textoaBuscar);

	$domain = strstr($textoCompleto, $textoaBuscar);
	if($domain)
		return true;
	else 
		return false;
}

function formatDateSeparador($formatoSalida, $fechaEntrada, $Separador = '-' ) {
	//convierte una fecha separada por guiones a una fecha con formato formatoSalida
	$arrFecha = explode($Separador, $fechaEntrada);
	
	$dia = $arrFecha[0];
	$mes = $arrFecha[1];
	$ano = $arrFecha[2];
	
	return date($formatoSalida, mktime(0, 0, 0, $mes, $dia, $ano));
}

function Detecta_SistemaOperativo(){
	echo PHP_OS;
}
function Info_SistemaOperativo($modo = ''){
/*
mode es un caracter simple que define qué información es devuelta:

'a': Elegida por defecto. Contiene todos los modos en la secuencia "s n r v m".
's': Nombre del sistema operativo. ej. FreeBSD.
'n': Nombre del Host. ej. localhost.example.com.
'r': Nombre de la versión liberada. ej. 5.1.2-RELEASE.
'v': Información de la versión. Varia mucho entre los sistemas operativos.
'm': Tipo de máquina. ej. i386.
*/
	if($modo == '')
		return php_uname();
	else
		return php_uname($modo);
}

	
function limpiarString($string)   {
		//función para limpiar strings
      $string = strip_tags($string);      
	  $string = htmlentities($string, ENT_QUOTES | ENT_HTML401, 'UTF-8');
	  $string = stripslashes($string);  
      return $string;
   }
   
function stripAccents($String){
		//reemplaza caractes especiales por la e
		$String = preg_replace("[éèêë]","e",$String);
		return $String;
	}

function toMoney($value, $symbol='$ ',$decimals =2){
    return $symbol.($value < 0 ? '-' : '') . number_format(abs($value), $decimals, ',', '.');

}

function RandomNumber($random = true){
	if( strtoupper(Info_SistemaOperativo('n')) == 'NTWEBART3')	$random = false;	
	//$random = true;	
	
	if( $random )
		return rand(111111, 999999).date('Ymd');
	else
		return 111111;
}

