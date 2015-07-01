<?php
header('Content-Type: text/plain; charset=utf-8');

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");
@session_start(); 

/*	
	foreach($_REQUEST as $k=>$v )
		print "parametros $k = $v <p> \n";
		
	foreach($_POST as $k=>$v )
		print "post parametros $k = $v <p> \n";
*/	

if(isset($_REQUEST['ADJUNTAPERICIA'] )){
	$_SESSION['ERRORESSUBIRARCHIVO']  = '';
	$partes_ruta = pathinfo($_FILES["uploadedfilePericia"]['name']);

	
		try{			
			$filename = NexValOracle('legales.SEQ_LAP_ID');
			$contratoID = $_SESSION["NUMEROJUICIO"];
			$id = $_REQUEST['id'];
			$filenameServer = '';
			
			// $pathServerPericias = DATA_IMAGE_PATH.'/PERICIAS/';
			$pathServerPericias = Get_Lpa_Parametro('DIRECTORIOARCHIVOSPERICIA');
			
			if( !ValidarControlInput('textdescripcion', 'Error : Debe completar la descripcion <p>') or 
				!ValidarControlFile('uploadedfilePericia', 'Error : Debe seleccionar un archivo para Asociar a la Pericia') ){
				header ("Location: /index.php?pageid=135&id=".$id);
				return false;
			}
			
			//1000000 = 1mb = 10000kb == 1048576bytes = 1mb 
			if (!subirArchivo($_FILES["uploadedfilePericia"], $pathServerPericias, $filename, '', MAX_FILE_UPLOAD, $contratoID, $filenameServer, $nomArchivo)) {
				$_SESSION['ERRORESSUBIRARCHIVO'] =  "Error : ".$_SESSION['ERRORESSUBIRARCHIVO'];
					//$error.'<p>';		
			}else{
								
				$ea_id = $filename; 
				$ea_descripcion = $_REQUEST['textdescripcion']; 				
				$idjuicio = $contratoID; 				
				$ea_ideventojuicioentramite = $id;
				
				InsertAdjuntoPericia($ea_id, $ea_descripcion, $idjuicio, $nomArchivo, $ea_ideventojuicioentramite);

				$_SESSION['ERRORESSUBIRARCHIVO'] .=  "Archivo : ".$partes_ruta['basename']." ( ".redondeo_tamano_archivo(filesize($_FILES["uploadedfilePericia"]['tmp_name']))." ) fue subido correctamente <p>";
			}

			header ("Location: /index.php?pageid=135&id=".$id);
			
			return true;
			
		}catch (Exception $e) {
			echo $e->getMessage();
			return false;
		} 
}
// --------------------------------------------------------------------------------------------------
if(isset($_REQUEST['ADJUNTAEVENTO'] )){
	$_SESSION['ERRORESSUBIRARCHIVO']  = '';
	$partes_ruta = pathinfo($_FILES["uploadedfileEvento"]['name']);

		try{			
			$filename = NexValOracle('legales.SEQ_LEA_ID');
			$contratoID = $_SESSION["NUMEROJUICIO"];
			$id = $_REQUEST['id'];
			$filenameServer = '';
			
			// $pathServerEventos = DATA_IMAGE_RAIZ_PATH.'\juicios\\';
			$pathServerEventos = Get_Lpa_Parametro('DIRECTORIOARCHIVOS');
			
			if( !ValidarControlInput('textdescripcion', "Error : Debe completar la descripcion <p>") or 
				!ValidarControlFile('uploadedfileEvento', "Error : Debe seleccionar un archivo para Asociar al Evento <p>") ){
				header ("Location: /index.php?pageid=134&id=".$id);
				return false;
			}
			
			//1000000 = 1mb = 10000kb == 1048576bytes = 1mb 
			if (!subirArchivo($_FILES["uploadedfileEvento"], $pathServerEventos, $filename, '', MAX_FILE_UPLOAD, $contratoID, $filenameServer, $nomArchivo)) {
				$_SESSION['ERRORESSUBIRARCHIVO'] =  "Error : ".$_SESSION['ERRORESSUBIRARCHIVO'].'<p>';						
			}else{
								
				$ea_id = $filename; 
				$ea_descripcion = $_REQUEST['textdescripcion']; 
				//$ea_patharchivo = $filenameServer;
				$idjuicio = $contratoID; 
				// $nomArchivo  SE OBTIENE DE LA FUNCION subirArchivo
				$ea_ideventojuicioentramite = $id;
				
				InsertAdjuntoEvento($ea_id, $ea_descripcion, $idjuicio, $nomArchivo, $ea_ideventojuicioentramite);

				$_SESSION['ERRORESSUBIRARCHIVO'] .=  "Archivo : ".$partes_ruta['basename']." ( ".redondeo_tamano_archivo(filesize($_FILES["uploadedfileEvento"]['tmp_name']))." ) fue subido correctamente <p>";
			}

			//echo $_SESSION['ERRORESSUBIRARCHIVO'];
			 header ("Location: /index.php?pageid=134&id=".$id);
			return true;
			
		}catch (Exception $e) {
			echo $e->getMessage();
			return false;
		} 
}
// --------------------------------------------------------------------------------------------------
echo "Verifique sus datos ... Errores de direccionamiento........";
// --------------------------------------------------------------------------------------------------

function ValidarControlFile($controlName, $mensaje){
	
	if(isset($_FILES[$controlName])){
		$nomArchi = $_FILES[$controlName]['name'];
		if( trim($nomArchi) =='' ){
			$_SESSION['ERRORESSUBIRARCHIVO'] =  $mensaje;										
			return false;
		}
	}else{
		$_SESSION['ERRORESSUBIRARCHIVO'] =  $mensaje."...";						
		return false;
	}
	return true;			
}

function ValidarControlInput($controlname, $mensaje){
	if(isset($_REQUEST[$controlname])){
			if( trim($_REQUEST[$controlname]) =='' ){
				$_SESSION['ERRORESSUBIRARCHIVO'] =  $mensaje;										
				return false;
			}
	}else{
		$_SESSION['ERRORESSUBIRARCHIVO'] =  $mensaje."...";						
		return false;
	}
	return true;			
}


function CalculaKB_MB($fileSize) {
	if ($fileSize < 1024)
		return $fileSize." bytes";
	elseif ($fileSize < 1048576)
		return ($fileSize / 1024)." KB";
	else
		return ($fileSize / 1024 / 1024)." MB";
}

function tamanoArchivo($fileName) {
	//recibe como parametro un archivo/path-nombre ($arch["tmp_name"]) retorna el tamaño en bytes
	return filesize($fileName);
}

function redondeo_tamano_archivo($peso , $decimales = 2 ) {
	//retorna el tamaño de un archivo redondeado a la unidad correspondiente
	//parametro path-nombre del archivo ($arch["tmp_name"])
	$clase = array(" Bytes", " KB", " MB", " GB", " TB"); 
	return round($peso/pow(1024,($i = floor(log($peso, 1024)))),$decimales ).$clase[$i];
}

function subirArchivo($arch, $folder, $filename, $extensionesPermitidas, $maxFileSize, $contratoID, &$finalFilename, &$nomArchivo) {
	/*Parametros
		$arch = array file 
		$folder = constante path del servidor donde guardamos las imgenes
		$filename = nombre del archivo en el servidor es el id del sequencer obtenido
		$extensionesPermitidas = extenciones permitidoas si es '' vacia se permiten todas
		$maxFileSize = tamaño maximo en bytes
		$contratoID = contrato del evento actual se usa como nombre de directorio para subir imagenes
		&$finalFilename = valor de retornao path y filename creados en el server
		&$nomArchivo = el nombre el archivo creado en el servidor... solo nombre y extencion sin el path
	*/

	$tmpfile = $arch["tmp_name"];
	$partes_ruta = pathinfo(strtolower($arch["name"]));
	$finalFilename = '';
	$error = '';

	if($extensionesPermitidas != ''){
		if (!in_array($partes_ruta["extension"], $extensionesPermitidas)) {
			$error = "El archivo debe tener alguna de las siguientes extensiones: ".implode(" o ", $extensionesPermitidas).".";
			$_SESSION['ERRORESSUBIRARCHIVO'] .= $error.'<p>';;
			return false;
		}
	}

	$filename = $filename.".".$partes_ruta["extension"];
	$nomArchivo = $filename;
	
	$pathServer = $folder.'\\'.$contratoID;
	
	if( !file_exists($pathServer) )
		mkdir($pathServer, 0700);
	
	$finalFilename = $pathServer.'\\'.$filename;
	
	//$_SESSION['ERRORESSUBIRARCHIVO'] .= 'Archivo '.$filename.'<p>';
	
	if (!is_uploaded_file($tmpfile)) {
		$error = "El archivo no subió correctamente.";
		$_SESSION['ERRORESSUBIRARCHIVO'] .= $error.'<p>';
		return false;
	}
			
	if (tamanoArchivo($tmpfile) > $maxFileSize) {
		$error = "El archivo no puede ser mayor a ".CalculaKB_MB($maxFileSize).".<p>";
		$error .= "Usted esta intentando adjuntar un archivo de ".redondeo_tamano_archivo(filesize($tmpfile)).".<p>";
		$_SESSION['ERRORESSUBIRARCHIVO'] .= $error.'<p>';
		return false;
	}
	
	if (!move_uploaded_file($tmpfile, $finalFilename)) {
		$error = "El archivo no pudo ser guardado.";
		$_SESSION['ERRORESSUBIRARCHIVO'] .= $error.'<p>';
		return false;
	}

	return true;
}

