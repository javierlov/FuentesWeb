<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Common/Clases/Tabla.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

@session_start(); 

define("DB_FORMATMONEY", "$9,999,999,990.00");
define("DB_FORMATPERCENT", "990.00");
	
	function clearString($text) { 
		$result = preg_replace('([^A-Za-z0-9])', '', $text); 
		return $result; 
	}

	function clearNumber($text){ 
		$result = preg_replace('([^0-9])', '', $text); 
		return $result; 
	}
	
	function ValidarAlfaNum($text){ 
		$result = clearString($text); 
		return ($result == $text); 
	}
	
	function ValidarNumerico($text){ 
		$result = clearNumber($text); 
		return ($result == $text); 
	}
	
	function LimpiarConstPeritajes(){		
		if(isset($_SESSION["PeritajesABMWebForm"]))
		
		unset($_SESSION["PeritajesABMWebForm"]["IdPeritoEdit"]); 
		unset($_SESSION["PeritajesABMWebForm"]["Apellido"]); 
		unset($_SESSION["PeritajesABMWebForm"]["Nombre"]); 
		unset($_SESSION["PeritajesABMWebForm"]["Accion"]);
		unset($_SESSION["PeritajesABMWebForm"]["cmbTipoPericia"]);
		unset($_SESSION["PeritajesABMWebForm"]["idperito"]); 		
		
		unset($_SESSION["PeritajesABMWebForm"]["FechaAsignacion"]); 		
		unset($_SESSION["PeritajesABMWebForm"]["FechaPericia"]); 		
		unset($_SESSION["PeritajesABMWebForm"]["FVencImpugnacion"]); 		
		unset($_SESSION["PeritajesABMWebForm"]["IncapacidadDemandada"]); 		
		unset($_SESSION["PeritajesABMWebForm"]["IncapacidadPerMedico"]); 		
		
		unset($_SESSION["PeritajesABMWebForm"]["IBMArt"]); 		
		unset($_SESSION["PeritajesABMWebForm"]["IBMPericial"]); 		
		
		unset($_SESSION["PeritajesABMWebForm"]["chkImpugnacion"]); 		
		unset($_SESSION["PeritajesABMWebForm"]["txtResultados"]); 		
	}

	function ValidarCampos($CodCaratula, $NroExpediente, $NroCarpeta, $tipoJuicio)
	{
		$result = (ValidarAlfaNum($CodCaratula) 
			and ValidarNumerico($NroExpediente) 
			and ValidarNumerico($NroCarpeta) 
			and  ValidarNumerico($tipoJuicio));
			
		return $result; 
	}

	/*
     * ValidarUserSession: validacion general para determinar si el user es valido para estudio juridico
     */
	function ValidarUserSession(){	
		
		if ((!isset($_SESSION["isAbogado"])) and (!isset($_SESSION["idUsuario"])) ) {			
			
			//validarSesion(false); 	
			echo "<script src=\"/js/functions.js?rnd=".RandomNumber()."\" type=\"text/javascript\"></script>";
			echo "<script>if (!isTopLevel(window)) alert('Se sesión ha expirado.\\nPor favor logueese nuevamente para continuar.');</script>";
			echo "<span id=\"sesionInvalidData\">".$_SERVER["REMOTE_ADDR"]." (".gethostbyaddr($_SERVER['REMOTE_ADDR']).")</span><br />";
			echo "<span id=\"sesionInvalidMsg\"> ".Trim("Ha expirado la sesión, por favor ingrese nuevamente sus datos.");
					
			exit;			
		}	
		
		ValidarJS();
	}
	
	function VariablesSinSeteo($mensaje){		
		echo '<span id="sesionInvalidData">'.$_SERVER["REMOTE_ADDR"].' ('.gethostbyaddr($_SERVER['REMOTE_ADDR']).')</span><br />';
		echo '<span id="sesionInvalidMsg">Error: '.utf8_decode($mensaje).'.</span>';
		//echo "<p><a class='linkSubrayado' href='/EstudioJuridico' target='_blank'>clic aquí para ir al inicio.</a>";
		echo "<p><a class='linkSubrayado' href='/EstudioJuridico' target='_blank'>".utf8_encode("clic aquí para ir al inicio.")."</a>";
		exit;		
	}
	
	function ValidarVariablesSession($arrayvariables, $retornaExeption = true){
		foreach($arrayvariables as $nom){
			if(!isset($_SESSION[$nom])){		
				if($retornaExeption)
					//throw new Exception("Variables sin seteo ".$nom);
					VariablesSinSeteo("Variables sin seteo ");
				else {
					return false;
				}
			}		
		}
		return true;
	}
	
	function ValidarJS(){
		$habilitejavascript = "<noscript>
					<span style='color:#f00; font-size:11px;'>
						Usted tiene JavaScript desactivado.<br />
						Para navegar correctamente por el sitio web debe tener activado JavaScript.<br />
						Haga <a class='linkSubrayado' href='/javascript' target='_blank'>clic aquí</a> para conocer mas.</span>						
				</noscript>";				
		
		echo $habilitejavascript;		
	}		
	
	function BloqueaControlesJS(){
		if($_SESSION["JUICIOTERMINADO"] ){
			echo "<script type='text/javascript'>
				var BloqueaControles = true;				
			</script> ";
		}else{
			echo "<script type='text/javascript'>
				var BloqueaControles = false;				
			</script> ";
		}
		
	}
	
	function BloqueoPorJuicioTerminado($ETUSUALTA){
		// retorna true si un juicio esta terminado y debe ser bloqueado para un usuario sin permisos sobre el mismo
		if( strtoupper($_SESSION["usuario"]) == "GESTIONINTERNA" ){
				return false;
		}
			
		if(!$_SESSION["JUICIOTERMINADO"] ) { 		
			if( strtoupper($ETUSUALTA) == strtoupper($_SESSION["usuario"]) ){
				return false;
			}		
		}
		return true;
	}
	
	/*
     * FL_ComparaInt: compara dos int y retorna true si son iguales
     */ 
	function FL_ComparaInt($int1, $int2){						
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
	
    /*
     * FL_ComparaStr: compara dos strings y retorna true si son iguales
     */ 
	function FL_ComparaStr($str1, $str2){		
		$str1 = strtoupper(trim($str1));
		$str2 = strtoupper(trim($str2));

		$result = strcmp($str1, $str2);				 

		return $result;
	}
	
    /*
     * FL_AddComboOption: Agrega items a un combo 
     */
	function FL_AddComboOption($clave, $texto, $isSelected=false){
		$result ='<option value='.$clave;	
		if($isSelected == true){			
			$result .= " selected='selected' ";	
		}
		$result .= '>'.$texto.'</option> ';
		return $result;
	}
	
    /*
     * CargarComboOpt: Define cual es el item seleccionado y
     *  Agrega items al combo y selecciona si corresponde 
     */
    function CargarComboOpt($id, $value, $idComp, $selectedId){
    /*Parametros (funcion arma option de un select)
    	$id,  el id del option
    	$value,  el valor del option
    	$idComp,  el id que debe aparecer seleccionado
    	$selectedId si es true muestra el id seleccionado
    */
		/*
        $selectOpt = FALSE;                             
        if($selectedId) {            
			$selectOpt = FL_ComparaInt($id, $idComp);
        }           
		
		Si envia un codigo ese se usa como el default
		*/
		$selectOpt = false;
		
		if(is_numeric($idComp)){
			$selectOpt = FL_ComparaInt($id, $idComp);	
		}else{			
			//si no son enteros compara como string las claves
			if( strcmp(strtoupper($id), strtoupper($idComp) ) ){$selectOpt = true;}			
		}
				
        $result = FL_AddComboOption($id, $value, $selectOpt);    
        return $result;
        
    }
    
	
	function SelectArrayOptions($tipoJuicio){
		$result = " <select name='cmbTipoJuicio' id='cmbTipoJuicio' class='input_text'>";
	
		$options = array(
			 '0' => '',
			 '1' => 'CONTENCIOSO ADMINISTRATIVO',
			 '9' => 'CRIMINAL Y CORRECCIONAL',
			 '8' => 'FEDERAL',
			 '2' => 'FEDERAL CIVIL Y COMERCIAL',
			 '3' => 'NACIONAL CIVIL',
			 '5' => 'NACIONAL COMERCIAL',
			 '4' => 'NACIONAL LABORAL',
			 '7' => 'PROVINCIAL CIVIL',
			 '6' => 'PROVINCIAL LABORAL'
			); 

		if (isset($tipoJuicio)){
			$optselected = $tipoJuicio;
		}
		
		foreach ($options as $value=>$text){ 		
			$result .=  ' <option ';
			
			if($value == $optselected){
				$result .= ' selected="selected" '; 
			}
			$result .= 'value='.$value.'> ';
			$result .=  $text; 
			$result .= ' </option> ';
		}	
		$result .= ' </select> ';
		
		return $result;
	}
	
	
	function TablaDatosUsuario($usuario, $align='left'){		
		$NombreEstudio = '';
		if( isset($_SESSION["NOMBREESTUDIO"]) ) {	
			$NombreEstudio = $_SESSION["NOMBREESTUDIO"];}
		
		$resultado = "<table class='table_General' align='".$align."' >
			<tr>			
				<td  colspan='4'  class='celdaFondoTituloEJ' >
					<b><font class='TextoTituloTablaEJ' >Datos del Estudio Jurídico</font></b></td>
			</tr>
			
			<tr>
				<td  width='6%' class='item_Blanco' align='left' </td>
					<font class='celda_titulogrisClaroFndBlanco'>Usuario:</font></td>
				<td  width='33%' class='item_Blanco' align='left' >
					<font face='Verdana' style='font-size: 8pt'>			
						<span id='DatosEstudioUserControl_txtUsuario'><b>
						<font class='TextoTablaEJ' >".$usuario."</font></b>
						</span></font></td>
				<td width='14%' class='item_Blanco' align='right'>
					<font face='Verdana' style='font-size: 8pt; ' color='#808080'>Estudio Jurídico:</font></td>
				<td width='65%' class='item_Blanco' align='left'>
						<font face='Verdana' style='font-size: 8pt'>
						<span id='DatosEstudioUserControl_txtEstudio'>
						<b>
						<font class='TextoTablaEJ' >".$NombreEstudio."</font></b></span></font></td>
			</tr>			
			</table>";
			
			return trim($resultado);
	}
	
	function TablaDatosJuicio($NUMEROCARPETA, $DESCRIPCARATULA, $align='left'){		

		$resultado = "<table class='table_General' align='".$align."' >
			<tr>
				<td colspan='4' class='celdaFondoTituloEJ'>
					<b><font class='TextoTituloTablaEJ' >Datos del Juicio</font></b>	</td>   </tr>
			<tr>
				<td height='16' class='item_Blanco' style='width:90px;' align='left'>
					<font class='celda_titulogrisClaroFndBlanco' >Nro. Carpeta :</font></td>";
		$resultado .= "			
				<td height='16' class='item_Blanco' >				  
				  <span id='UserControl1_txtNroCarpeta'><b><font class='TextoTablaEJ' >".$NUMEROCARPETA."</font></b></span>   </td>
				<td height='16' width='6%' class='item_Blanco' align='right'>
					<font class='celda_titulogrisClaroFndBlanco' >Carátula:</font></td>
				<td height='16' width='60%' class='item_Blanco' align='left'>
				  <span id='UserControl1_txtCaratula'><b><font class='TextoTablaEJ' >".$DESCRIPCARATULA."</font></b></span></td></tr></table>";
		
		return $resultado;
	}
	
	
	function TablaDatosJuicioEstado(){				
	
		list($JT_NUMEROCARPETA, $DESCRIPCARATULA, $EJ_DESCRIPCION) = ObtenerNroCarpeta($_SESSION["NroJuicio"]);
		
		$NUMEROCARPETA = $_SESSION["NUMEROCARPETA"]; 
		$DESCRIPCARATULA = $_SESSION["DESCRIPCARATULA"];
		$ESTADO_DESCRIPCION = $_SESSION["ESTADO_DESCRIPCION"];
		/*
		if($NRO_JUICIO == $_SESSION["NroJuicio"]){			 
			$NUMEROCARPETA = $_SESSION["NUMEROCARPETA"]; 
			$DESCRIPCARATULA = $_SESSION["DESCRIPCARATULA"];
			$ESTADO_DESCRIPCION = $_SESSION["ESTADO_DESCRIPCION"];
		}else{
			list($NUMEROCARPETA, $DESCRIPCARATULA, $ESTADO_DESCRIPCION) = ObtenerNroCarpeta($NRO_JUICIO);					
		}
		*/
		$resultado = "<table class='table_General' align='left'  >
					<tr>
						<td colspan='6' class='celdaFondoTituloEJ'>
							<b><font class='TextoTituloTablaEJ' >Datos del Juicio</font></b></td></tr>
					<tr>
						<td width='11%' class='item_Blanco' align='left' style='height: 16px;'>
							<font class='celda_titulogrisClaroFndBlanco' >Nro. Carpeta:</font></td>";		
							
		$resultado .= "<td width='6%' class='item_Blanco' style='height: 16px;'>	<span id='txtNroCarpeta1'>
							<b><font class='TextoTablaEJ'  style='vertical-align: text-bottom;' >".trim($NUMEROCARPETA)."</font></b></span>
							</td>";
						
		$resultado .= "<td width='5%' class='item_Blanco' align='right' style='height: 16px'>
							<font class='celda_titulogrisClaroFndBlanco' >Carátula:</font></td>
						<td width='25%' class='item_Blanco' align='left' style='height:auto'>
							<span id='txtCaratula'><b><font class='TextoTablaEJ' >".$DESCRIPCARATULA."</font></b></span></td>
						<td width='5%' class='item_Blanco' align='right' style='height: 16px'>
							<font class='celda_titulogrisClaroFndBlanco' >Estado:</font></td>
						<td width='28%' class='item_Blanco' align='left' style='height: 16px'>
							<span id='txtCaratula'><b>
							<font class='TextoTablaEJ' >".$ESTADO_DESCRIPCION."</font></b></span></td>		</tr>
					<tr>
						<td height='3' colspan='6'></td>
				
						</tr></table>";
						
		return $resultado;
	}
	
	
	function GetStrToDate($strdate){
	 	if (trim($strdate) == '') 	
	 		$strdate= NULL;
		else 
			$strdate = date( "d-m-Y", strtotime($strdate) );	
			
		return $strdate;
	}
	
	function Getfloat($str) {
	  //Esta funcion se usa para formatear valores y guardarlos en la base de datos..	  
	  if(is_null($str) )
	  	$str = '0';
	 
	  if(strstr($str, ",")) {
	    $str = str_replace(".", "", $str); 
	    $str = str_replace(",", ".", $str); 
	  }

	  if(!is_numeric($str) )
	  	$str = '0';
	 
	  if(preg_match("#([0-9\.]+)#", $str, $match)) { 
	    $return=floatval($match[0]);
	  }else{
	    $return=floatval($str); 
	  }
	  
	  if($return == '') $return='0.00';	  	
	  return $return;	   	
	}
	
	function SoloformatearDinero($valor){		
		//esta funcion se usa para mostrar valores por pantalla
		//no se debe usar para grabar valores  en la base ..
		//no agrega el simbolo pesos $
		return number_format(floatval($valor), 2, ',', '.');
	}
	
	function formatearDinero($valor){		
		//esta funcion se usa para mostrar valores por pantalla
		//no se debe usar para grabar valores  en la base ..
		return "$ ".number_format(floatval($valor), 2, ',', '.');
	}

	function AsignarNroJuicioSession(){		
		if(isset($_REQUEST["NroJuicio"])) { //NroJuicio			
			$_SESSION["NroJuicio"] = $_REQUEST["NroJuicio"];	
		}
	}
	
	function HeaderExpires(){
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
		header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
		header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
		header("Pragma: no-cache"); // HTTP/1.0
	}
	
	function DIVProcesandoOculto(){
		
		$resultado = "<div id='VentanaFondo' class='VentanaOverlay' style='display:none'></div>
					<div align='center' id='divProcesando' name='divProcesando' 
						class='DIVProcesando' style='display:none;'>
						<img border='0' src='/images/loading.gif' title='Espere por favor...'>
					</div>";
					
		return $resultado;
	}
	
	function ErrorConeccionDatos($msj) {		
		echo '<span id="sesionInvalidData">'.$_SERVER["REMOTE_ADDR"].' ('.gethostbyaddr($_SERVER['REMOTE_ADDR']).')</span><br />';
		echo '<span id="sesionInvalidMsg">Error: '.trim($msj).'</span">';
		echo "<br><input class='btnVolver' type='button' value='' onClick='history.back(-1);'/>";
		//throw new Exception($msj);		
		exit;	
	}
	
	function ValorParametroRequest($Parametro){
		if(isset($_REQUEST[$Parametro])){
			return $_REQUEST[$Parametro];
		}		
		return '';
	}

function ExtToFile($file){
	/*Dado un archivo retorna su extencion*/
	$ftmp = explode(".",$file); 
    $fExt = strtolower($ftmp[count($ftmp)-1]); 
	return $fExt;
}

function DescargarArchivoAdjunto($archivo, $Tipo) {

	if($Tipo == 'EVENTO'){		
		$f = PathLocalAdjuntoEvento($archivo);		
	}
	
	if($Tipo == 'PERICIA'){
		$f = PathLocalAdjuntoPercia($archivo);		
	}
	
	$serverlocal = strtolower(Info_SistemaOperativo('n'));
	$f =  strtolower($f);
	
	if (!file_exists($f)) {			
		$f = ReemplazaCaracter($f, 'ntintraweb',  $serverlocal);
		
		if (!file_exists($f)) {									
			echo "1. El archivo [ $f ] no existe o no tiene permisos";
			exit;
			
		}
	}
			
	DescargarAdjunto($f);		
	exit;
	
}

function DescargarAdjunto($f){
	$hoy = date("Ymd");  
	$hora = date("H:i:s");	
	$hora = ReemplazaCaracter($hora, ":", ''); 
	$filenamedownload = $hoy.$hora.'F'.basename($f);
	
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=' . $filenamedownload );
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: '.filesize($f));

	ob_clean();
	flush();
	readfile($f);
	return true;
}

function MuestraArchivoEnBrowser($f){
	
	$extensionesWeb = array("jpg", "pdf", "png");
	$ftmp = explode(".",$f); 
    $fExt = strtolower($ftmp[count($ftmp)-1]); 
	
	
	if(in_array($fExt, $extensionesWeb)){ 
		EscribirLogTxt1("MuestraArchivoEnBrowser ext in array", $f);
		
		if (($fExt == ".jpeg") or ($fExt == ".jpg"))
			header("Content-type: image/jpeg");
		
		if ($fExt == ".pdf")
			header("Content-type: application/pdf");
		
		if ($fExt == ".png")
			header("Content-Type: image/png");
		
		$tamano = filesize($f);
		header('Content-Length: '.strlen($tamano));				
		header('Content-Disposition: inline; filename="'.$f.'"');
		
		//header("Content-Disposition: attachment; filename=\"$f\"\n"); 
		//header('Cache-Control: private, max-age=0, must-revalidate');
		//header('Pragma: public');				
				
		//$fp=fopen("$f", "r");  		fpassthru($fp); 	
		readfile($f);
		
		return true;
	}	
	
	EscribirLogTxt1("MuestraArchivoEnBrowser no se muestra ext", $fExt);
	return false;	
}

function DescargarAdjuntoForzar($archivo) {
		/*
		$size = filesize($path);
		 if (function_exists('mime_content_type')) {
			$type = mime_content_type($path);
		 } else if (function_exists('finfo_file')) {
			$info = finfo_open(FILEINFO_MIME);
			$type = finfo_file($info, $path);
			finfo_close($info);
		 }
		 */
		 /*
		 $type = 'false';
		 if ($type == '') {
			$type = "application/force-download";
		 }
		
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $archivo);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($archivo));

        ob_clean();
        flush();
        readfile($archivo);
		*/
		
		$tam = filesize($archivo); 
		header("Content-type: application/force-download"); 
		header("Content-Disposition: attachment; filename=".basename($archivo) ); 
		header("Content-Transfer-Encoding: binary"); 
		header("Content-Length: $tam");  
		readfile($archivo); 

		exit;
}

function ReemplazaCaracter($texto, $charOriginal, $charReemp){
	/*Reemplaza los / el caracter enviado $charOriginal, por el reemplazante $charReemp */	
	$texto = str_replace($charOriginal, $charReemp,$texto);		
	return $texto;	
}


function ReemplazaDataStorage($pathArchivosLocal){
	$serverlocal = strtolower(Info_SistemaOperativo('n'));	
	$textreemp = ReemplazaCaracter($pathArchivosLocal, "\\\\".$serverlocal."\Storage_Data", STORAGE_DATA_PATH);
	$textreemp = ReemplazaCaracter($textreemp, '\\', '/');
	return $textreemp; 
}

