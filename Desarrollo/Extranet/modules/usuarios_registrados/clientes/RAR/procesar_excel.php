<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/excel/excel_reader2.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CommonFunctions.php");
//--------------
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php");
//--------------
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");

function formatNumber($valor) {
	return "0".trim(str_replace(array(",", "$"), array(".", ""), $valor));
}

function insertarRegistroError($seqTrans, $row, $error) {
	//escribe en un array los errores....
}

function RedirectPage(){
	echo "<script type='text/javascript'>window.parent.location.href = '/NominaPersonalExpuesto';</script>"; 
	//echo " ahora hacer un redirect..........";
}

function addTextReport($text){
	if( isset($_SESSION['arrayXLSReport']) )
		$_SESSION['arrayXLSReport'] .= $text;			
	else
		$_SESSION['arrayXLSReport'] = $text;			
}

try {
	
	setDateFormatOracle("DD/MM/YYYY");
	ini_set("memory_limit", "256M");
	set_time_limit(1800);
	
	$nombre_fichero = $_FILES["archivoxls"]["tmp_name"];
	
	$errores = '';
	unset($_SESSION['arrayXLSReport']);
	unset($_SESSION['arrayXLSReportOK']);
	
	if ( trim($nombre_fichero) == '' ) {		
		$errores = 'Seleccione un archivo para procesar <p>';		
		addTextReport(" <div>  ".$errores."  </div> <p/>");			
		RedirectPage();
		exit;
	}
	
	if ( !is_readable($nombre_fichero)) {		
		$errores = 'El archivo no es legible '.$_FILES["archivoxls"]["tmp_name"].'<p>';
		addTextReport(" <div>  ".$errores."  </div> <p/>");			
		RedirectPage();
		exit;
	}

		error_reporting(E_ALL ^ E_NOTICE);		
		$excel = new Spreadsheet_Excel_Reader($_FILES["archivoxls"]["tmp_name"]);		
		
		// var_dump($_SESSION);		
		// validar encabezado archivo	
			$row=1;
			$cols = array();
			for ($col=65; $col<=75; $col++)
				$cols[chr($col)] = trim($excel->val($row, chr($col)));			
			
			$errores = '';
			if (trim($cols["A"]) != "C.U.I.L.")	$errores .= "Columna A debe ser (C.U.I.L.) <p> ";
			if (trim($cols["B"]) != "Nombre y apellido")	$errores .= "Columna B, debe ser (Nombre y apellido) <p> ";
			if (trim($cols["C"]) != "Fecha de ingreso a la empresa")	$errores .= "Columna C, debe ser (Fecha de ingreso a la empresa) <p> ";
			if (trim($cols["D"]) != "Fecha de inicio de la exposición")	$errores .= "Columna D, debe ser (Fecha de inicio de la exposición) <p> ";
			if (trim($cols["E"]) != "Sector de trabajo")	$errores .= "Columna E, debe ser (Sector de trabajo) <p> ";
			
			if($errores != ''){					
				//$_SESSION['arrayXLSReport'] .= " <div>  Encabezado invalido ".utf8_encode($errores)."  </div> <p/>";							
				addTextReport(" <div>  Encabezado invalido ".trim($errores)."  </div> <p/>");							
				RedirectPage();
				exit;
			}
			
		$CountRegInsert = 0;
		$CountRegImport = 0;
		
		if($excel->rowcount() == 0){			
			$errores .= 'Archivo vacio. Sin trabajadores cantidad (rowcount)';
			addTextReport(" <div>  ".$errores."  </div> <p/>");		
			RedirectPage();			
			exit;
		}
			
		for($row=2; $row <= $excel->rowcount(); $row++) {		// columna 1 tiene la cabecera..
			
			$cols = array();
			for ($col=65; $col<=75; $col++)
				$cols[chr($col)] = trim($excel->val($row, chr($col)));			
			
			$existeValor = false;
			foreach ($cols as $key => $value)
				if ($value != "")
					$existeValor = true;
					
			if (!$existeValor){	break;}
			else { $CountRegInsert++; }
						
			$errores = "";
			$cuilTrabajador = $cols["A"];
			$fechaIng = true;
			$fechaExpo = true;
			$cuilValido = true;			
			
			if ( strlen(trim($cols["A"])) != 11 ){
					$errores .= "* Cuil Incompleto ";
					$cuilValido = false;
			}else{
				if (!validarCuit(trim($cols["A"]))){
					$errores .= "* Cuil Invalido ";
					$cuilValido = false;	
				}
			}
				
			if( !ValidaSoloNumerosRExp( trim($cols["A"]) ) )
				$errores .= "* Cuil Contiene caracteres invalidos ";
				
			if ($cols["B"] == "")
				$errores .= "* Apellido nombre vacio ";
			
			if ( !ValidaSoloLetrasEspRExp($cols["B"]) )
				$errores .= "* Apellido Nombre contiene caracteres invalidos ";

			try {
				if (!isFechaValida($cols["C"])) {
					$errores .= "* Fecha ingreso invalida  ".$cols["C"]." ";
					$fechaIng = false;
				}else{
					if(dateDiff( date('d/m/Y'), $cols["C"] ) > 0){
						$errores .= "* Fecha ingreso Debe ser menor/igual a la fecha actual  ".$cols["C"]." ";
						$fechaIng = false;
					}
				}				
			}
			catch (Exception $e) {				
				$errores .= "* Error fecha ingreso invalida ".$cols["C"]." ";
				$fechaIng = false;
			}
			
			try {
				if (!isFechaValida($cols["D"])) {
					$errores .= "* Fecha exposición invalida ".$cols["D"]." ";
					$fechaExpo = false;
				}				
			}
			catch (Exception $e) {
				$errores .= "* Error fecha exposición invalida ".$cols["D"]." ";
				$fechaExpo = false;
			}
			
			if($fechaExpo and $fechaIng){
				if(dateDiff( $cols["D"], $cols["C"] ) > 0){
					$errores .= "* Fecha exposición (".$cols["D"].") Debe ser mayor/igual a la Fecha de Ingreso (".$cols["C"].") a la empresa  ";				
				}
			}
			
			if ($cols["E"] == "")
				$errores .= "Sector vacio";

			$cuitEmpresa = $_SESSION['cuit'];
			$establecimiento = $_SESSION['FormulariosNomina']['NROESTABLECI'];			
			$nombre = '';
			$fechaingreso = '';
			$sectortrab = '';			
			$puestotrab = '';			
			
			if($cuilValido ){
				$valido = Valida_TrabajadorEnOtraNomina($cuilTrabajador, $cuitEmpresa, $establecimiento);
				if( $valido > 0 ){				
					switch($valido){
						case 1: $errores .= '* Cuil ya fue presentado en una nomina web <p>'; break;
						case 2: $errores .= '* Cuil esta declarado en una nomina ya aprobada <p>'; break;
						case 3: $errores .= '* Ya esta ingresado en esta nomina web <p>'; break;
					}				
				}						
				
				$CONTRATO =  $_SESSION["contrato"];			
									
				$rowTrabajador = BuscarTrabajador($CONTRATO, $cuilTrabajador, $cuitEmpresa);
				if( $rowTrabajador['ID'] == 0 ){
					$errores .=  '* Cuil no se encuentra en afiliaciones  ';
				}else{
					$nombre = $rowTrabajador['NOMBRE'];
					$fechaingreso = $rowTrabajador['FECHA_INGRESO'];
					$sectortrab = $rowTrabajador['SECTOR'];
					$puestotrab = $rowTrabajador['PUESTO'];
				}	
			}

			if($errores != ''){				
				addTextReport(" <div> CUIL ".$cuilTrabajador.":  ".$errores."  </div> <p/>");			
			}else{	
				
				 $idEstablecimiento = $_SESSION["FormulariosNomina"] ["CODIGOEWID"];
				 if($nombre == '') $nombre = trim($cols["B"]);
				 if($fechaingreso == '') $fechaingreso = $cols["C"]; 
				 $fechainiexpo = $cols["D"] ;
				 if($sectortrab == '') $sectortrab = trim($cols["E"]);
				 if($puestotrab == '') $puestotrab = '';
				 $arrayRiesgos = '';
	
				GrabarRegistroNomina(0, $idEstablecimiento, $cuilTrabajador, $nombre, $fechaingreso, $fechainiexpo, $sectortrab, $puestotrab, $arrayRiesgos);
				$_SESSION['arrayXLSReportOK'] .= $cuilTrabajador.' <p/>';		
				$CountRegImport++;
			}	
		}
		
		if($CountRegInsert == 0){
			//echo 'Archivo vacio. Sin trabajadores (recorrido)';			
			$errores = 'Archivo vacio, sin trabajadores (debe completar el archivo '.$nombre_fichero.')';		
			addTextReport(" <div> ".$errores."  </div> <p/>");						
			RedirectPage();
		}
		
		if($CountRegImport > 0)
			$_SESSION['arrayXLSReportOK'] = 'Cantidad importados: '.$CountRegImport.'<p>';				
			
		RedirectPage();
	
} catch (Exception $e) {
	DBRollback($conn);
	SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__, $e->getMessage() );
	addTextReport(" <div> ".utf8_encode( $e->getMessage() )."  </div> <p/>");						
	RedirectPage();	
	exit;
}

