<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/PDF_tabla.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CommonFunctions.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/FuncionesEstablecimientos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/RAR_funcionesValidacion.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/NominaPersonalExpuesto.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf.php");

@session_start();

class PDFReport extends PDF_Tabla{
	private $FiltrosActivos;		
	public $idrelev = 0;
	public $NominaConfirmada = 'NO';
	public $TieneExpuestos = '';
	public $AnnoProcesar = 'ACTUAL';
	private $DatosEstablecimiento = array();
	
	public function ListadoPersonalExpAnnoAntSQL( $annoACTUAL ){
		$ReturnSQL = ObtenerSQL_AnnoAnteriorActual( $annoACTUAL );
		$ReturnSQL = ReemplazaCaracterStr($ReturnSQL, '?', '' );
		$ReturnSQL = ReemplazaCaracterStr($ReturnSQL, '¿', '' );
		return utf8_decode($ReturnSQL);
	}
	public function ListadoPersonalExpSQL($idEstablecimiento){
		$ReturnSQL = ObtenerDatosNominaWeb();
		$ReturnSQL = ReemplazaCaracterStr($ReturnSQL, '?', '' );
		$ReturnSQL = ReemplazaCaracterStr($ReturnSQL, '¿', '' );
		return utf8_decode($ReturnSQL);
	}
   public function SetIdEstablecimiento($value){
	$this->idrelev = $value;
   }
   public function SetNominaConfirmada($value){
	$this->NominaConfirmada = $value;
   }
   
   function Header(){
      $this->EncabezadoReport();
   }
   
   function Footer(){
		$datosEstableci = $this->DatosEstablecimiento;
		$this->SetY(-50);
		$this->SetFillColor(254,254,254);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial' , '', 7);
		//---------- columna 1 
		//$this->SetLineWidth(0.3);
		$posy = 180;
		$posy1 = -30;
		$posxCol1 = 15;
		$this->SetXY($posxCol1, $posy1);
		$this->line($posxCol1, $posy, $posxCol1+80 , $posy);
		$this->SetXY($posxCol1,-28);
		$this->Write(2,"Firma y aclaración del responsable de HyS " );
		$this->SetXY($posxCol1,-23);
		$this->Write(2,"Nombre : ".$datosEstableci['HYS_APELLIDO'].' '.$datosEstableci['HYS_NOMBRE'] );
		$this->SetXY($posxCol1,-18);
		$this->Write(2,"DNI/CUIL : ".$datosEstableci['HYS_NUMERODOCUMENTO']." .." );
		$this->SetXY($posxCol1,-13);
		$this->Write(2,"Puesto : ".$datosEstableci['HYS_CARGO'] );
		//---------- columna 2 
		$posxCol1 = 105;
		$this->line($posxCol1, $posy, $posxCol1+80 , $posy);
		$this->SetXY($posxCol1,-28);
		$this->Write(2,"Firma y aclaración del responsable de la empresa " );
		$this->SetXY($posxCol1,-23);
		$this->Write(2,"Nombre : ".$datosEstableci['RESPEMP_APELLIDO'].' '.$datosEstableci['RESPEMP_NOMBRE'] );
		$this->SetXY($posxCol1,-18);
		$this->Write(2,"DNI/CUIL : ".$datosEstableci['RESPEMP_NUMERODOCUMENTO']." .."  );

		//---------- columna 3 
		$posxCol1 = 225;
		$this->SetXY($posxCol1-45 ,-40);
		//$this->Write(2, $datosEstableci['EW_FECHAALTA']."     " );
		$this->Cell(100,16, $datosEstableci['EW_FECHAALTA'],0,0,'C');
		$this->line($posxCol1-20, $posy, $posxCol1+40 , $posy);
		$this->SetXY($posxCol1,-28);
		$this->Write(2,"Fecha " );
		$this->SetY(-9);
		$this->SetFont('Arial' , '', 8);
		$this->Cell(0,10, utf8_decode('PV-E-01-F005'),0,0,'L');
		$this->Cell(0,10, utf8_decode('Pagina ').$this->PageNo(),0,0,'R');
		$this->SetXY(0, -9);
		$this->SetFont('Arial' , '', 7);
		$this->Cell(0,10, 'Nota: Imprimir este formulario y presentarlo en Provincia ART firmado en original por al menos uno de los responsables indicados al pie de la página. ',0,0,'C');
		
   }
   function SetFontAlignGeneral(){
		$this->SetFont('Arial' ,'I', 7);
		$this->SetWidths(array(20,30,30,35,30,50,80));
		$this->SetAligns(array('C','C','C','C','C','C','L'));
   }
   function trimUTF8($Value){
		//return trim(utf8_decode($Value));
		return trim($Value);
   }
   function DibujaLineaSepara(){
		$longline = 290;
		$posY = $this->GetY();
		//$this->Rect($this->GetX() - 4.6, $this->GetY(), $longline, 0.2, "F");
		$this->line($this->GetX() - 4.6, $posY, $longline, $posY);
   }
   public function BuscaDatorGeneralReporte($annoACTUAL, $idEstablecimiento){
		$ewid = 0;
		$idEWEstableci = '';
		$idEWCUIT = '';
		
		if($this->NominaConfirmada == 'NO'){		
			$this->DatosEstablecimiento = DatosGenEstablecimientoWEB($this->idrelev);						
			$pdf->TieneExpuestos =  $this->DatosEstablecimiento["TIPOESTABLECIMIENTO"];
			$ewid = $this->idrelev;
		
		}else{
			
			if($this->TieneExpuestos == 'SINEXPUESTO'){
				$this->DatosEstablecimiento = DatosGenEstableciAnnoAnteriorSinRiesgo($this->idrelev, $this->AnnoProcesar);			
			}else{
				$this->DatosEstablecimiento = DatosGenEstablecimientoCabeceraNomina($this->idrelev, $this->AnnoProcesar);			
				
				$idEWCUIT = $this->DatosEstablecimiento['CN_CUIT'];
				$idEWEstableci = $this->DatosEstablecimiento['CN_ESTABLECI'];					
			}			
			$ewid = '';			
		}

		$respHYS = GetDatosNominaWebResponsable($ewid, 'H', $idEWEstableci, $idEWCUIT, $this->AnnoProcesar);
		$respEMPRESA = GetDatosNominaWebResponsable($ewid, 'R', $idEWEstableci, $idEWCUIT, $this->AnnoProcesar);
						
		$this->DatosEstablecimiento['HYS_NOMBRE'] = $respHYS['RW_NOMBRE'];
		$this->DatosEstablecimiento['HYS_APELLIDO'] = $respHYS['RW_APELLIDO'];
		$this->DatosEstablecimiento['HYS_NUMERODOCUMENTO'] = $respHYS['RW_NUMERODOCUMENTO'];		
		$this->DatosEstablecimiento['HYS_CARGO'] = $respHYS['CARGO_DESCRIPCION'];
		
		$this->DatosEstablecimiento['RESPEMP_NOMBRE'] = $respEMPRESA['RW_NOMBRE'];
		$this->DatosEstablecimiento['RESPEMP_APELLIDO'] = $respEMPRESA['RW_APELLIDO'];
		$this->DatosEstablecimiento['RESPEMP_NUMERODOCUMENTO'] = $respEMPRESA['RW_NUMERODOCUMENTO'];	
		
   }
   
   public function EncabezadoReport(){
		$Contrato = 0;
		$rowEstableci = $this->DatosEstablecimiento;
		
		$this->filtroCUIL = CuitExtractGuion( $rowEstableci["CUIT"] );
		if( isset($_SESSION['contrato']) )
			$Contrato = $_SESSION['contrato'];
		$this->Image($_SERVER["DOCUMENT_ROOT"]."/images/logoProvARTmed.jpg",260, 3);
		if( $this->TieneExpuestos == 'SINEXPUESTO'){
			$this->Image($_SERVER["DOCUMENT_ROOT"]."/images/sinexpuestos.png",10, 100, 280, 29, 'PNG');
			
			$this->SetDrawColor(195);
			$this->SetLineWidth(1);
			$this->Line(10, 60, 285, 180, 'PNG');
						
		}
		$this->SetDrawColor(0);
		$this->SetLineWidth(0);
		
		$this->SetXY(105,10);
		$this->SetFont('Arial' , 'B', 16);
		$this->Cell(0,0, 'NÓMINA DE PERSONAL EXPUESTO ' ,0,0,'L');
		$this->SetFont('Arial' , "I", 6);
		$ahora = $rowEstableci["FECHASERVER"];
		$this->SetXY(5, 5);
		$this->Cell(0,0, 'Fecha Impresión : '.$ahora."  " ,0,0,'L');
		/*
		if($this->TieneExpuestos == 'CONEXPUESTO' and $this->AnnoProcesar == 'ACTUAL' ){
			$this->SetXY(5, 8);
			$this->Write(1, 'Versión : '.$rowEstableci["VERSIONNOMINA"] );
			$this->SetXY(5, 10);
			$this->Write(1, 'Fecha : '.$rowEstableci["FECHAIMPRESIONNOMINA"] );
		}
		*/
/**************************************************************/
		$this->SetFillColor(0,0,0);
		$this->Rect(3,18,290, 6, 'F');
		$this->SetXY(5,21);
		$this->SetTextColor(254,254,254);
		$this->SetFont('Arial' , 'B', 10);
		$this->Cell(0,0, "DATOS DE LA EMPRESA" ,0,0,'L');
		$this->SetFillColor(254,254,254);
		$this->SetTextColor(0,0,0);
/******** RENGLON 1 ******************************************************/
		$SizeFont = 8;
		$posy1 = 23;
		$this->SetXY(5, $posy1);
		$this->SetFont('Arial' , '', $SizeFont);
		$this->Write(10,"Razón Social : ".$rowEstableci["NOMBRE"] );
		$this->SetXY(175, $posy1);
		$this->SetFont('Arial' , '', $SizeFont);
		$this->Write(10,"C.U.I.T. .: ".$rowEstableci["CUIT"]."   ");
		$this->SetXY(245, $posy1);
		$this->SetFont('Arial' , '', $SizeFont);
		$this->Write(10,"Contrato nº : ".$Contrato);
		$posy = 30;
		$this->line(3, $posy, 290 , $posy);
/******** RENGLON 2 ******************************************************/
		$posy2 = 29;
		$this->SetXY(5, $posy2);
		$this->SetFont('Arial' , '', $SizeFont);
		$this->Write(10,"Establecimiento (nº y nombre) : ".$rowEstableci["RAZONSOCIAL"] );
		$this->SetXY(145, $posy2);
		$this->SetFont('Arial' , '', $SizeFont);
		//$this->Write(10,"CIIU _".$rowEstableci["CIIUEMPRESA"]);
		$this->Cell(0,10, utf8_decode("CIIU : ".$rowEstableci["CIIUEMPRESA"]),0,0,'L');
		$this->SetXY(195, $posy2);
		$this->SetFont('Arial' , '', $SizeFont);
		$this->Write(10,"Actividad :".$rowEstableci["ACTIVIDAD"] );
		$this->SetXY(235, $posy2);
		$this->SetFont('Arial' , '', $SizeFont);
		$this->Write(10,"Cantidad de trabajadores : ".$rowEstableci["CANTIDADEMPLEADOS"]);
		$posy = 36;
		$this->line(3, $posy, 290 , $posy);
/******** RENGLON 3 ******************************************************/
		$posy3 = 35;
		$this->SetXY(5, $posy3);
		$this->SetFont('Arial' , '', $SizeFont);
		$this->Cell(65, 10,"Dirección : ".$rowEstableci["DOMICILIO"], 0,0, 'L');
		$this->SetXY(75, $posy3);
		$this->SetFont('Arial' , '', $SizeFont);
		$this->Cell(50, 10,"Localidad : ".$rowEstableci["LOCALIDAD"], 0,0, 'L');
		$this->SetXY(125, $posy3);
		$this->SetFont('Arial' , '', $SizeFont);
		$this->Write(10,"Provincia : ".$rowEstableci["PROVINCIA"] );
		$this->SetXY(175, $posy3);
		$this->SetFont('Arial' , '', $SizeFont);
		$this->Write(10,"Teléfono : ".$rowEstableci["TELEFONO"] );
		$this->SetXY(250, $posy3);
		$this->SetFont('Arial' , '', $SizeFont);
		$this->Write(10,"Fax :".$rowEstableci["FAX"] );
		$posy = 42;
		$this->line(3, $posy, 290 , $posy);
/**************************************************************/
		$tplIdx = $this->importPage(1);
		$this->useTemplate($tplIdx);
		$this->SetXY(5,50);
		$this->SetFontAlignGeneral();
		//$this->SetFont('Arial' , 'B', 8);
		$this->SetFillColor(0,0,0);
		$this->SetTextColor(254,254,254);
		$this->SetFont('Arial' , 'B', 8);
		$this->Rect(3,47,290, 12, 'F');
		$this->SetX(10);
		//$this->DibujaLineaSepara();
		$this->Row(array(
			$this->trimUTF8("C.U.I.L."),
			$this->trimUTF8("Nombre Apellido"),
			$this->trimUTF8("Fecha de Ingreso a la Empresa"),
			$this->trimUTF8("Fecha de Inicio de la Exposición"),
			$this->trimUTF8("Sector de Trabajo"),
			$this->trimUTF8("Puesto de Trabajo"),
			$this->trimUTF8("Identificación de riesgo ESOP")
			) );
		$this->SetFillColor(254,254,254);
		$this->SetTextColor(0,0,0);
   }
}
//-----------------------------------------------------------------------
try{
	if( !isset($_SESSION["usuario"]) ){
		validarSesion(false);
		exit;
	}
	$UsuarioNombre = $_SESSION["usuario"];
		
	global $conn;
	$params = array();
	SetDateFormatOracle("DD/MM/YYYY");
	
	//-------------------------------------------
	$pdf = new PDFReport();
	$idEstablecimiento = 0;
	
	$annoACTUAL = 'ANTERIOR';//= anno anterior
	$pdf->TieneExpuestos = 'SINEXPUESTO';
	$pdf->NominaConfirmada = 'NO';
	
	if(isset( $_SESSION['ListadoPersonalExpuesto']['ACTUAL'] ) ){
		$annoACTUAL = 'ACTUAL';//= anno actual
	}
	$pdf->AnnoProcesar = $annoACTUAL;
	//-------------------------------------------
	if(isset( $_SESSION['ListadoPersonalExpuesto']['idrelev'] ) ){
		//anno anterior
		$idEstablecimiento = $_SESSION['ListadoPersonalExpuesto']['idrelev'];		
		$pdf->SetIdEstablecimiento( $_SESSION['ListadoPersonalExpuesto']['idrelev'] );
		$pdf->SetNominaConfirmada( $_SESSION['ListadoPersonalExpuesto']['NominaConfirmada'] );		
	}
	
	if($pdf->NominaConfirmada == 'SI'){				
		$row = Obtener_ID_TABLA($pdf->idrelev, $annoACTUAL);
		if( BuscaSubStr($row["TABLA"], 'SINRIESGO') ){
			$pdf->TieneExpuestos = 'SINEXPUESTO';
		}
		if( BuscaSubStr($row["TABLA"], 'CABECERANOMINA') ){
			$pdf->TieneExpuestos = 'CONEXPUESTO';
		}		
		$params[":IDCABECERA"] = $pdf->idrelev;
		$sql =  $pdf->ListadoPersonalExpAnnoAntSQL($annoACTUAL);
		$pdf->BuscaDatorGeneralReporte($annoACTUAL, $idEstablecimiento);
		
	}else{
		
		if( isset($_SESSION['ListadoPersonalExpuesto']['tiponomina']) and $_SESSION['ListadoPersonalExpuesto']['tiponomina'] == 'S'){		
			$pdf->TieneExpuestos = 'CONEXPUESTO';
			$sql =  $pdf->ListadoPersonalExpSQL($idEstablecimiento);			
		}else{			
			$pdf->TieneExpuestos = 'SINEXPUESTO';
				
			$idEWEstableci = $_SESSION['ListadoPersonalExpuesto']['empresaESTABLECI'];
			$idEWCUIT = $_SESSION['ListadoPersonalExpuesto']['empresaCUITSINGUION'];						
		}			
		$params[":IDCABECERANOMINA"] = $idEstablecimiento;
		$pdf->BuscaDatorGeneralReporte($annoACTUAL, $idEstablecimiento);			
	}
		
	if($pdf->TieneExpuestos == 'SINEXPUESTO' ){
		$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/varios/templates/ListadoJuiciosVerticalBlanco.pdf");
		$pdf->SetAutoPageBreak(true, 50);
		$pdf->AddPage('L', 'Legal');
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFontAlignGeneral();
		$Newrow = array("0"=>'', "1"=>'', "2"=>'', "3"=>'', "4"=>'', "5"=>'', "6"=>'');
		$pdf->Row($Newrow);
	}
	
	if($pdf->TieneExpuestos == 'CONEXPUESTO' ){
		$stmt = DBExecSql($conn, $sql, $params);
		//-----------------------------------------------------------------------
		if (DBGetRecordCount($stmt) == 0) {
			echo utf8_decode("La consulta no devolvio datos.");
			exit;
		}
		//-----------------------------------------------------------------------
		$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/varios/templates/ListadoJuiciosVerticalBlanco.pdf");
		$pdf->SetAutoPageBreak(true, 50);
		$pdf->AddPage('L', 'Legal');
		$stmt = DBExecSql($conn, $sql, $params);
		while ($row = DBGetQuery($stmt, 1, false)) {
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFontAlignGeneral();
			$row = array_slice($row, 1);
			$Newrow = array_values($row);
			$pdf->Row($Newrow);
			$pdf->DibujaLineaSepara();
		}
	}
	unset($_SESSION['ListadoPersonalExpuesto']);
	//enviamos cabezales http para no tener problemas
	header("Content-Transfer-Encoding", "binary");
	header('Cache-Control: maxage=3600');
	header('Pragma: public');
	$pdf->Output();
	
	// DBCommit($conn);							
}catch (Exception $e){
	DBRollback($conn);		
	SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__, $e->getMessage() );
	echo 'ERROR: '.$e->getMessage();
}