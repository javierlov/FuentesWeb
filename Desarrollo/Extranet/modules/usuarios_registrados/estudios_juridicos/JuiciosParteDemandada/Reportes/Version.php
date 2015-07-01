<?php

//foreach($_REQUEST as $key  => $value){ echo "$key  = $value <p>"; }	

$info = array_search('info', $_REQUEST);
$show = array_search('show', $_REQUEST);
$all = array_search('all', $_REQUEST);
$version = array_search('version', $_REQUEST);
$what = array_search('what', $_REQUEST);

$maps = array_search('maps', $_REQUEST);
$city = array_search('city', $_REQUEST);

if( array_search('server', $_REQUEST)  ) foreach($_SERVER as $key  => $value){ echo "$key  = $value <p>"; }	
if( array_search('request', $_REQUEST)  ) foreach($_REQUEST as $key  => $value){ echo "$key  = $value <p>"; }	

if($info != '' and $show != '' and  $all != '' ) phpinfo();
if($info != '' and $show != '' and  $version != '' ) echo 'php version = '.phpversion();
if($info != '' and $what != ''  ){ 
	
	if(isset($_REQUEST['param3']) and trim($_REQUEST['param3']) != '' ){
		$what = $_REQUEST['param3'];
		echo 'opcion '.$what;
		phpinfo(INFO_ENVIRONMENT);
	}else{
		phpinfo();
	}	
	
}

if($maps != '' and $city != '' ){
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
	header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
	header("Pragma: no-cache"); // HTTP/1.0
	
	$name = $_REQUEST['param3'];
	$strmaps = "https://www.google.com.ar/maps/place/".$name;
	header("Location: ".$strmaps);	
}

//S:\Extranet\modules\usuarios_registrados\estudios_juridicos\JuiciosParteDemandada\Reportes\Version.php