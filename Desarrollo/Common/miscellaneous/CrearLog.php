<?php
/*ESTOS LOG SALEN POR CONSOLA
	Agrege a su codigo esta linea:
	require_once($_SERVER["DOCUMENT_ROOT"]."/functions/CrearLog.php");
*/

$GLOBALS["mostrarlog"] = false;
$GLOBALS["mostrarlog2"] = false;
$GLOBALS["mostrarlog3"] = false;
$GLOBALS["mostrarlogtxt"] = false;
$GLOBALS["mostrarlogtxtLogin"] = false;
$GLOBALS["mostrarlogtxt1"] = true;

$GLOBALS["pathLogs"] = $_SERVER["DOCUMENT_ROOT"]."/../logs";

function EscribirTxt($funcion, $texto) {		
		$hoy = date("Ymd");  
		$hora = date("H:i:s");		
		$ar=fopen($GLOBALS["pathLogs"]."/".$hoy."Logs.txt","a") or die("Problemas en la creacion");
						
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

function EscribirLogTxtLogin($user) {		
	if ($GLOBALS["mostrarlogtxtLogin"]) {	
		$hoy = date("Ymd");  
		$hora = date("H:i:s");		
		$ar=fopen($GLOBALS["pathLogs"]."/".$hoy."LoginTest.log","a") or die("Problemas en la creacion");
				
		fputs($ar, $hoy.'-'.$hora);	
		fputs($ar,"\n");
		
		fputs($ar,'Usuario: '.$user);
		fputs($ar,"\n");
		
		$texto = 'SESSION: ';
		foreach($_SESSION as $key => $value){		
			if( !is_array($value) )
				$texto .= ' '.$key.' = '.$value.'; ';
		}	
		/*
		$texto .= 'contrato: '.$_SESSION['contrato'].'; ';
		$texto .= 'cuit: '.$_SESSION['cuit'].'; ';
		$texto .= 'idUsuario: '.$_SESSION['idUsuario'].'; ';
		$texto .= 'idEmpresa: '.$_SESSION['idEmpresa'].'; ';
		$texto .= 'empresa: '.$_SESSION['empresa'].'; ';
		$texto .= 'contratoVigente: '.$_SESSION['contratoVigente'].'. ';
		*/
		fputs($ar,$texto);
		fputs($ar,"\n");
		
		$texto = ' ';
		//$texto = 'REMOTE_HOST: '.$_SERVER['REMOTE_HOST'];
		$texto .= ', REMOTE_ADDR: '.$_SERVER['REMOTE_ADDR'];
		$texto .= ', REMOTE_PORT: '.$_SERVER['REMOTE_PORT'];
		$texto .= ', REMOTE_USER: '.$_SERVER['REMOTE_USER'];
		$texto .= ', VERSION: '.$_SERVER['HTTP_USER_AGENT'];
		fputs($ar,$texto);
		fputs($ar,"\n");
		
		$texto = 'NAVEGADOR AGENT: '.$_SERVER['HTTP_USER_AGENT'].' ';
		fputs($ar,$texto);
		fputs($ar,"\n");
				
		$texto = 'BROWSER: ';
		foreach(DetectaVersionBrowser() as $key => $value){		
			$texto .= ' '.$key.' = '.$value.'; ';
		}				
		fputs($ar,$texto);
		fputs($ar,"\n");
		
		fputs($ar,"-----------  -----------  -----------  -----------  -----------");
		fputs($ar,"\n");
		fclose($ar);
	}
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

function SalvarErrorTxt($file, $function, $line, $mensaje){
	/*	pasar como parametros  __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage()  */
	EscribirLogTxt1($file.'_'.$function.'_'.$line , $mensaje );	
}

function DetectaVersionBrowser(){
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

/*
EJEMPLO
EscribirLogTIMESTART('SI');
EscribirLogWARN('PODRIA PONER UN TEXTO');
EscribirLogUSERMENSAJE("JAVIER", "IMPORTA");
EscribirLogTRACE();
EscribirLogTIMEEND('SI');
*/

?>