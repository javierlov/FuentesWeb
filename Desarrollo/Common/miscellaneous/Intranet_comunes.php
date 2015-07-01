<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");

function Intranet_JSjqueryUI(){
	echo Intranet_JSjqueryVersion();
	echo Intranet_JSjqueryUIVersion();		
}

function Intranet_JSjqueryUIVersion(){
	/*retorna el jquery a utilizar dependiendo de la version de navegador*/
	$info = DetectaVersion();
	$version = intval($info["version"]);
	$resultado = '';

	if($info["browser"] == 'MSIE' &&  $version < 9)				
		$resultado = "<script type='text/javascript' src='/js/jq/jquery-ui.min.1.9.1.js'></script>  ";		
	else
		$resultado = "<script type='text/javascript' src='/js/jq/jquery-ui-custom.js'></script>  ";		
	
	$resultado .= " <link href='/styles/rar/jquery-ui-custom.css?sid=".date('YmdHis')."' rel='stylesheet'>		";
	
	return $resultado;
}

function Intranet_JSjqueryVersion(){
	/*retorna el jquery a utilizar dependiendo de la version de navegador*/
	$info = DetectaVersion();
	$version = intval($info["version"]);
	$resultado = '';

	if($info["browser"] == 'MSIE' &&  $version < 9)				
		$resultado = "<script type='text/javascript' src='/js/jq/jquery-1.7.2.min.js'></script>  ";
	else
		$resultado = "<script type='text/javascript' src='/js/jq/jquery.js'></script>  ";		
	return $resultado;
}
