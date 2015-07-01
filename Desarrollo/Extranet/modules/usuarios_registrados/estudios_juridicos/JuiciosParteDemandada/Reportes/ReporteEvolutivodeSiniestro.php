<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/PDF_tabla.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");
//
//require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CommonFunctions.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/FuncionesEstablecimientos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/RAR_funcionesValidacion.php");//
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/NominaPersonalExpuesto.Grid.php");

///// S:\Extranet\modules\usuarios_registrados\estudios_juridicos\JuiciosParteDemandada\Reportes\ObtenerDatosReportes.php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/Reportes/ObtenerDatosReportes.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");

@session_start();

class PDFReport extends PDF_Tabla{
	
	public $muestraImporte = false; 
	public $sumaImporte = 0;
	public $datosEncabezado = array();
	
	private $arrayTitulos = array('Recaida', 
									'Numero', 
									'Fecha Accidente/Recaida', 
									'Estado', 
									'Diagnostico', 
									'Detalle', 
									'Observaciones', 
									'Forma de Pago', 
									'G.Trabajo', 
									'F.Aprob.', 
									'F.Carga',
									'Importe'
									);
	
	private $listaCampos = array( 'EV_RECAIDA', 
									'EV_NUMERO', 
									'EV_PRESTADOR', 
									'EV_ESTADO', 
									'EV_DIAGNOSTICO', 
									'EV_DETALLE', 
									'EV_OBSERVACIONES', 
									'EV_FORMAPAGO', 
									'GTRABAJOGEST', 
									'EV_PROXIMOCONTROL', 
									'EV_FECHAALTA',
									'EV_IMPORTE'
									);
	
	public function Listado_ReporteEvolutivodeSiniestro(   ){
		$ReturnSQL = ObtenerSQL_ReporteEvolutivodeSiniestro(  );
		return utf8_decode($ReturnSQL);
	}
	
	
   function Header(){
		$this->Print_Logo();
		
		$this->SetDrawColor(0);
		$this->SetLineWidth(0);

		$this->SetXY(10,10);
		$this->SetFont('Arial' , 'I', 20);
		$this->Cell(0,0, 'Evolución de Siniestro' ,0,0,'C');
		
		$this->SetFont('Arial' , "I", 8);
		//$ahora = $rowEstableci["FECHASERVER"];
		date_default_timezone_set('UTC');
		$ahora =  date("d/m/Y");

		$this->SetXY(5, 5);
		$this->Cell(0,0, $ahora."  " ,0,0,'L');
		
		//---------------------------------------------------------
		$texto = $this->datosEncabezado['SINIESTRO'].'  ';
		$this->SetFont('Arial' , '', 16);
		$this->SetXY(5, 20);
		$this->Cell(0,0, $texto."  " ,0,0,'L');
		
		$this->SetFont('Arial' , 'B', 16);
		$this->SetXY(100, 20);
		$this->Cell(0,0, "Datos del Trabajador"."  " ,0,0,'L');
		
		$this->SetFont('Arial' , 'I', 11);
		$this->SetXY(100, 26);
		$texto = $this->datosEncabezado['CUIL'].'  ';
		$this->Cell(0,0, $texto."  " ,0,0,'L');
		
		$this->SetXY(100, 31);
		$texto = $this->datosEncabezado['TJ_NOMBRE'].'  ';
		$this->Cell(0,0, $texto."  " ,0,0,'L');
	
		//---------------------------------------------------------
		$texto = $this->datosEncabezado['SINIESTRO'].'  ';
		$this->SetFont('Arial' , '', 16);
		$this->SetXY(5, 20);
		$this->Cell(0,0, $texto."  " ,0,0,'L');
		
		$this->SetFont('Arial' , 'B', 16);
		$this->SetXY(200, 20);
		$this->Cell(0,0, "Datos de la Empresa"."  " ,0,0,'L');
		
		$this->SetFont('Arial' , 'I', 11);
		$this->SetXY(200, 26);
		$texto = $this->datosEncabezado['CUIT'].'  ';
		$this->Cell(0,0, $texto."  " ,0,0,'L');
		
		$this->SetXY(200, 31);
		$texto = $this->datosEncabezado['MP_NOMBRE'].'  ';
		$this->Cell(0,0, $texto."  " ,0,0,'L');

		$this->Ln(5);
		$this->LineaSepara();
		
   }
   
   function Footer(){
		
		$this->SetY(-10);
		$this->SetFillColor(254,254,254);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial' , '', 8);		
		$formato = 'Usuario:  %s          Página  %d ';		
		$usuario = (trim($_SESSION["usuarionombre"]) == '' ? $_SESSION["usuario"] : $_SESSION["usuarionombre"]);
		
		$this->Cell(0,0, sprintf($formato, $usuario, $this->PageNo())."  " ,0,0,'R');
		//$this->Cell(0,0, 'Página '.$this->PageNo()."  " ,0,0,'R');
		
   }
      
   function PrintDatosReport($posX1, $posY1, $datos){
		$this->SetFillColor(254,254,254);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial' , 'B', 8);
			
		/*linea 1*/
		foreach($datos as $key  => $value){ 
			$this->ImprimeLineaTexto($posX1,$posY1,'',$value ,0, '');  				
			$posX1 += 10;
			$posY1 += 5;
		}					
   }
   
   function ArmarLineas($rowData, $TipoRow = 0){	   
	   $arrayDatos = array();	  
		
		switch ($TipoRow) {				
			case 0:{
				$arrayDatos[] = array($rowData['EV_DOCU']);			
				$textos = $this->GetArrayColumnTitle($rowData['EV_DOCU']);
				$arrayDatos[] = $textos;
						
				break;				
			}
			case 1:{$arrayDatos[] = array( 	$rowData['EV_RECAIDA'], $rowData['EV_NUMERO'], 		$rowData['EV_PRESTADOR'], 
								$rowData['EV_ESTADO'], 	$rowData['EV_DIAGNOSTICO'], $rowData['EV_DETALLE'], 
								$rowData['EV_OBSERVACIONES'], $rowData['EV_FORMAPAGO'], $rowData['GTRABAJOGEST'],
								$rowData['EV_PROXIMOCONTROL'], $rowData['EV_FECHAALTA'], $rowData['EV_IMPORTE']);
								break;}
			case 2:{$arrayDatos[] = array( 	$rowData['EV_RECAIDA'], $rowData['EV_NUMERO'], 		$rowData['EV_PRESTADOR'], 
								$rowData['EV_ESTADO'], 	$rowData['EV_DIAGNOSTICO'], $rowData['EV_DETALLE'], 
								$rowData['EV_OBSERVACIONES'], $rowData['EV_FORMAPAGO'], $rowData['GTRABAJOGEST'],
								$rowData['EV_PROXIMOCONTROL'], $rowData['EV_FECHAALTA']);
								break;}
			case 3:{
				$arrayDatos[] = array( 	'', '', '', 
								'', '', '', 
								'', '', '', 
								'', 'Total', $this->sumaImporte );								
				break;				
			}
		}
		
	   return $arrayDatos;
   }
   
   function SetFontAlignGeneral($tipo=0, $headtitle = ''){						
		if($tipo == 1){
			$this->SetAligns(array('L'));		
			$this->SetWidths(array(150));								   
		}
		else{
			$this->SetAligns(array('L','L','L','L','L', 'L',
								   'L','L','L','L','L', 'R'));		
			$this->SetWidths(array( 12, 14, 35, 25, 25, 25, 
								   100, 25, 25, 20, 15, 20));								   
		}		
   }
   
   public function ComparaTituloPos($titulo){
	   //dado un texto retorna la posicion en el array si no existe retorna -1
	   $arrayPos = array('CABECERA', 'P.INGRESO', 'P.EGRESO', 'P.EVOLUTIVO', 'AUTORIZACIÓN', 'LIQ. ILT/ILP', 'LIQ. OTROS PAGOS', 'LIQ. PRES. MED.', 'P.AUDITORIA');
		//$clave = array_search($Header, $arrayPos);
		$Header = strtoupper($titulo);
		$Header = strtoupper(substr($Header,0, 8));
		$clave = -1;
		foreach($arrayPos as $k => $v){
			$valor = strtoupper(substr($v,0, 8));	
			if($Header == $valor )
				$clave = $k;			
		}
		
		return $clave;
   }
   
   function GetArrayColumnTitle($Header){
		$Header = strtoupper($Header);
	    $this->muestraImporte = false;
		$clave = $this->ComparaTituloPos($Header);
		//$numColumn = 0;
		
		$arrayResultados = array();
		foreach($this->arrayTitulos as $k => $v){
			$columnTitle = $v; //$this->arrayTitulos[$numColumn];			
				
			switch ($k) {				
				case 1:{
					if($clave == 0 or $clave == 2 )
						$columnTitle = '';
					break;
				}
				case 2:{
					$columnTitle = 'Prestador'; 
					if($clave == 0 ) $columnTitle =  'Fecha Accidente/Recaída'; 
					if($clave == 5 ) $columnTitle =  'Concepto'; 
					if($clave == 6 ) $columnTitle =  'Beneficiario'; 
					break;
					
				}
				case 3:{
					$columnTitle = ''; 
					if($clave == 0 or $clave == 4 or $clave == 6 or $clave == 7 ) $columnTitle =  'Estado'; 
					if($clave == 2 ) $columnTitle =  'T.Egreso'; 					
					break;					
				}
				case 4:{
					$columnTitle = ''; 
					if($clave == 0 or $clave == 1 or $clave == 2 or $clave == 3 or $clave == 8 ) $columnTitle =  'Diagnóstico'; 					
					break;					
				}
				case 9:{
					$columnTitle = ''; 
					if($clave == 3 or $clave == 8 ) $columnTitle =  'PróxControl'; 					
					if($clave == 5 or $clave == 6 or $clave == 7 ) $columnTitle =  'F.Aprob.'; 					
					break;					
				}
				case 11:{
					$columnTitle = ''; 					
					if($clave == 4 or $clave == 5 or $clave == 6 or $clave == 7 ) $columnTitle =  'Importe';
					if($columnTitle != '') {
						$this->muestraImporte = true;						
					}
					break;					
				}
			}
			
			$arrayResultados[$k] = $columnTitle;
			//$numColumn++;
		}
			return $arrayResultados;
	}
}

//-----------------------------------------------------------------------
try{	
		
	if( !isset($_SESSION["usuario"]) ){
		validarSesion(false);
		exit;
	}
	$UsuarioNombre = $_SESSION["usuario"];
	
	if( !isset($_SESSION['ReportesSiniestros']["ReporteEvolutivodeSiniestro"]) ){
		header("Location: /JuiciosParteDemandada");		
		// CIERRA LA PAGINA ReporteEvolutivodeSiniestro.php
		// echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		exit;
	}
	
	global $conn;
	$params = array();
	SetDateFormatOracle("DD/MM/YYYY");
	
	//-------------------------------------------
	$pdf = new PDFReport('L','mm','legal');		
	$pdf->SetTitle('ReporteEvolutivodeSiniestro');
	$pdf->SetAuthor("JLovatto");
	$pdf->SetCreator('ReporteEvolutivodeSiniestro');
	$pdf->SetSubject("REPORTE WEB LEGALES");	

			
	$params[":siniestro"] =  $_SESSION["ReportesSiniestros"]["ID"];	
	$params[":orden"] =  $_SESSION["ReportesSiniestros"]["ORDEN"];	
	
	$pdf->datosEncabezado = Encabezado_ReporteEvolutivodeSiniestro($_SESSION["ReportesSiniestros"]["ID"], $_SESSION["ReportesSiniestros"]["ORDEN"]);	
	$sql =  $pdf->Listado_ReporteEvolutivodeSiniestro();		
	$stmt = DBExecSql($conn, $sql, $params);
	//-----------------------------------------------------------------------
	if (DBGetRecordCount($stmt) == 0) {
		echo utf8_decode("La consulta no devolvio datos.");		
		exit;
	}
	//-----------------------------------------------------------------------				
	$pdf->AddPage('L', 'legal');		
	$pdf->SetTitle('ReporteEvolutivodeSiniestro');
	
	// $pdf->SetXY(5,30);
	
	$rowLines = array();	
	$currentHead = '';
		
	while ($row = DBGetQuery($stmt, 1, false)) {		
		
		if($currentHead != $row['EV_DOCU']){
			
			if($pdf->muestraImporte){
				$rowLines[] = $pdf->ArmarLineas($row, 3);				
				$pdf->sumaImporte =0;
			}
			
			$rowLines[] = $pdf->ArmarLineas($row, 0);
			$currentHead = $row['EV_DOCU'];
		}
		
		if($pdf->muestraImporte){
			$rowLines[] = $pdf->ArmarLineas($row, 1);				
			$pdf->sumaImporte += $row['EV_IMPORTE'];
		}
		else{
			$rowLines[] = $pdf->ArmarLineas($row, 2);				 
		}
	}
	
	if($pdf->muestraImporte){
		$rowLines[] = $pdf->ArmarLineas($row, 3);				
		$pdf->sumaImporte =0;
	}

	$lineSelect = 0;
	$pdf->SetAltoLinea(6);										
	$iline = 0;
	
	foreach($rowLines as $rown) {		
		
		//$pdf->SetAutoPageBreak(true, 30);
		//$pdf->SetTopMargin(40);
		
		$lineSelect = 0;	
		$count = count($rown);
		if($count == 1) $lineSelect = 2;
		$changeColor = 0;	
		$pdf->SetFormatCell( array() );
		
		foreach($rown as $val) {
				
			$pdf->SetAligns('L');			
			$pdf->SetDibujaFondoTexto(false);				
			
			if($lineSelect == 0){
				$pdf->SetFontAlignGeneral(1);			
				$pdf->SetDibujaFondo(true);								
				$pdf->SetFont('Arial' , 'I', 14);										
				$pdf->SetAltoLinea(7);										
							
				$pdf->SetX(5);
				$pdf->SetFillColor(192, 192, 192);				
				$pdf->SetTextColor(0, 0, 0); 	
				$changeColor = 0;
				$iline = 0;
			}		
			if($lineSelect == 1){

				$pdf->SetX(5);
				$pdf->SetFontAlignGeneral(2);			
				$pdf->SetAltoLinea(5);										
				
				$pdf->SetFillColor(192, 192, 192);
				$pdf->SetTextColor(255, 255, 255); 		
				$pdf->SetFont('Arial' , 'B', 7);										
				
			}
			if($lineSelect >= 2){

				$pdf->SetX(5);
				$pdf->SetFontAlignGeneral(2);			
				$pdf->SetDibujaFondo(false);					
				$pdf->SetFormatCell( array(11) );
				$pdf->SetAltoLinea(5);
				$pdf->SetFont('Arial' , 'B', 5);										
				
				if($changeColor == 0){
					$pdf->SetX(10);
					$pdf->SetFillColor(255, 255, 255); 		
					$changeColor = 1;
				}
				else{
					$pdf->SetFillColor(176,176,176);
					$changeColor = 0;					
				}
				
				if($iline > 0){	$pdf->SetX(10); $pdf->LineaSepara();}
				$iline++;
				
				$pdf->SetTextColor(5, 0, 0); 	
				$pdf->SetFont('Arial' , 'I', 7);															
			}
			
			$pdf->SetX(5);
			$Newrow = array_values($val);						
			$pdf->Row($Newrow);			
			$lineSelect++;
		}
	}
		
	unset($_SESSION['ReportesSiniestros']["ReporteEvolutivodeSiniestro"]);
	
	//enviamos cabezales http para no tener problemas
	header('Content-Type: text/html; charset=UTF-8'); 
	//header('Content-Type: text/html; charset=iso-8859-1');
	header("Content-Transfer-Encoding", "binary");
	//header('Cache-Control: maxage=3600');
	header('Cache-Control: private, max-age=0, must-revalidate');
	header('Pragma: public');

	$pdf->Output();
	
	// DBCommit($conn);							
}catch (Exception $e){
	DBRollback($conn);		
	EscribirLogTxt1("ReporteEvolutivodeSiniestro.php", $e->getMessage() );
	echo 'ERROR: '.$e->getMessage();
}


