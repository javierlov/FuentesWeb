<?php
/*ESTOS LOG SALEN POR CONSOLA
	Agrege a su codigo esta linea:
	require_once($_SERVER["DOCUMENT_ROOT"]."/functions/CrearLog.php");
*/
$GLOBALS["mostrarlog"] = false;
$GLOBALS["mostrarlog2"] = false;
$GLOBALS["mostrarlog3"] = false;
$GLOBALS["mostrarlogtxt"] = false;
$GLOBALS["mostrarlogtxt1"] = true;

$GLOBALS["pathLogs"] = $_SERVER["DOCUMENT_ROOT"]."/../logs";

function EscribirTxt($funcion, $texto) {		
		$hoy = date("Ymd");  
		$hora = date("H:i:s");		
		$ar=fopen($GLOBALS["pathLogs"]."/".$hoy."datos.txt","a") or die("Problemas en la creacion");
						
		fputs($ar,$funcion);	
		fputs($ar,'.... '.$hoy.'-'.$hora);	
		fputs($ar,"\n");
		fputs($ar,$texto);
		fputs($ar,"\n");
		fputs($ar,"-----------  -----------  -----------  -----------  -----------");
		fputs($ar,"\n");
		fclose($ar);
		//fputs($ar,"\n");
		//echo "Los datos se cargaron correctamente.";
}

function EscribirLogTxt($funcion, $texto) {		
	if ($GLOBALS["mostrarlogtxt"]) {	
		EscribirTxt($funcion, $texto);
	}
}

function EscribirLogTxt1($funcion, $texto) {		
	if ($GLOBALS["mostrarlogtxt1"]) {	
		EscribirTxt($funcion, $texto);
	}
}

function EscribirLogNOPROFILE($texto) {		
	if ($GLOBALS["mostrarlog"]) {	
	echo "<script type='text/javascript'>console.log('".$texto."');</script>";}
}
function EscribirLogUSERMENSAJE($texto, $mensaje) {	
	if ($GLOBALS["mostrarlog"]) {	
	echo "<script type='text/javascript'>console.log('".$texto.",".$mensaje."');</script>";}
}
function EscribirLogWARN($texto) {	
	if (($GLOBALS["mostrarlog"]) || ($GLOBALS["mostrarlog2"])) {	
	echo "<script type='text/javascript'>console.warn('".$texto."');</script>";}
}
function EscribirLogERROR($texto) {	
	if ($GLOBALS["mostrarlog"]) {	
	echo "<script type='text/javascript'>console.error('".$texto."');</script>";}
}

function EscribirLogERROR3($texto) {	
	if ($GLOBALS["mostrarlog3"]) {	
	echo "<script type='text/javascript'>console.error('".$texto."');</script>";}
}

function EscribirLogTIMESTART($texto) {	
	if ($GLOBALS["mostrarlog"]) {	
	echo "<script type='text/javascript'>console.time('".($texto)."');</script>";}
}

function EscribirLogTIMEEND($texto) {	
	if ($GLOBALS["mostrarlog"]) {	
	echo "<script type='text/javascript'>console.timeEnd('".($texto)."');</script>";}
}

function EscribirLogTRACE() {	
	if (($GLOBALS["mostrarlog"]) || ($GLOBALS["mostrarlog2"])) {	
	echo "<script type='text/javascript'>console.trace();</script>";}
}
function EscribirLogCLEAR() {	
	if ($GLOBALS["mostrarlog"]) {	
	echo "<script type='text/javascript'>console.clear();</script>";}
}
function EscribirLogDIR() {	
	if ($GLOBALS["mostrarlog"]) {	
	echo "<script type='text/javascript'>console.dir(document.body);</script>";}
}
function EscribirLogDIRXML() {	
	if ($GLOBALS["mostrarlog"]) {	
	echo "<script type='text/javascript'>var list = document.querySelector('#myList');
			console.dirxml();</script>";}			
}
function EscribirLogTIMEARRAY() {	
	if ($GLOBALS["mostrarlog"]) {	
	echo "<script type='text/javascript'>
		console.time('Array initialize');
		var array= new Array(1000000);
		for (var i = array.length - 1; i >= 0; i--) {
			array[i] = new Object();
		};
		console.timeEnd('Array initialize')</script>";
	}
}

function EscribirLogFirebugPHP($funcion, $texto) {
	//echo "el Log es ".$texto;
	//echo "<script type='text/javascript' src='EscribirLog.js'></script>";
	if ($GLOBALS["mostrarlog"]) {	
	echo "<script type='text/javascript'>	
		console.profile('funcionlog');		
		console.log('".$funcion."'); console.log('".$texto."');				
		console.profileEnd('funcionlog');</script>";
		}
}


/*
EJEMPLO
EscribirLogTIMESTART('SI');
EscribirLogWARN('PODRIA PONER UN TEXTO');
EscribirLogUSERMENSAJE("JAVIER", "IMPORTA");
EscribirLogTRACE();
EscribirLogTIMEEND('SI');
*/

?>