<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/PDF_tabla.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
//
//require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CommonFunctions.php");
//Common\miscellaneous\cuit.php
//
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/FuncionesEstablecimientos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/RAR_funcionesValidacion.php");//
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/NominaPersonalExpuesto.Grid.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/Reportes/ObtenerDatosReportes.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");

@session_start();

class PDFReport extends PDF_Tabla{
	
	//Contrato Vigencia desde Vigencia hasta Estado Fecha de baja Motivo de baja

	public $margenDerecho = 5;
	private $arrayTitulos = array('Contrato', 
								'Vigencia desde', 
								'Vigencia hasta', 
								'Estado', 
								'Fecha de baja', 
								'Motivo de baja'
									);
	
   function Header(){
		$this->Print_Logo();
		
		$this->SetDrawColor(0);
		$this->SetLineWidth(0);

		$this->SetXY($this->margenDerecho,10);
		$this->SetFont('Arial' , 'B', 16);
		$this->Cell(0,0, 'Datos de la Empresa' ,0,0,'C');
	
		$psiniestro =  $_SESSION["ReportesSiniestros"]["ID"];	
		$porden =  $_SESSION["ReportesSiniestros"]["ORDEN"];	
			
		$this->SetFont('Arial' , "I", 6);
		//$ahora = ["FECHASERVER"];
		date_default_timezone_set('UTC');
		$this->SetXY(5, 5);
		$ahora =  date("d/m/Y");
		$this->Cell(0,0, $ahora."  " ,0,0,'L');
		
		$this->Ln(15);
		$this->SetX($this->margenDerecho);									

		$this->RellenaFondoLinea(192, 192, 192);
/*
		$this->SetFillColor(192, 192, 192);
		$x=$this->GetX();
		$y=$this->GetY();
		$anchofijo = $this->w - 15;
		$this->Rect($x, $y+0.2, $anchofijo, 5, "F");
		$this->SetFillColor(0, 0, 0);
	*/	
		$this->SetTextColor(0, 0, 0); 	
		$this->SetFontAlignGeneral();	
		$Newrow = array_values($this->arrayTitulos);			
		$this->Row($Newrow);	
		// $this->LineaSepara();			
		$this->Ln(5);
   }
   
   function Footer(){
		
		$this->SetXY(10, -10);
		$this->SetFillColor(254,254,254);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial' , '', 7);		
		$this->Cell(0,0, 'Página '.$this->PageNo()."  " ,0,0,'L');		
   }
      
   public function SetFontAlignGeneral(){		
		$this->SetFont('Arial' ,'I', 8);		
		$this->SetWidths(array(30,25,25,50,25,40));							   
		$this->SetAligns(array('L','L','L','L','L'));		
   }
}

//-----------------------------------------------------------------------
try{	
	
	if( !isset($_SESSION["usuario"]) ){
		validarSesion(false);
		exit;
	}
	$UsuarioNombre = $_SESSION["usuario"];
	
	if( !isset($_SESSION['ReportesSiniestros']["ReporteDatosdelaEmpresa"]) ){
		header("Location: /JuiciosParteDemandada");		
		// CIERRA LA PAGINA
		// echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		exit;
	}
	
global $conn;


SetDateFormatOracle("DD/MM/YYYY");
$params = array();
	
$pdf=new PDFReport();
	$pdf->SetTitle("ReporteDatosdelaEmpresa");
	$pdf->SetAuthor("JLovatto");
	$pdf->SetCreator("ReporteDatosdelaEmpresa");
	$pdf->SetSubject("REPORTE WEB LEGALES");
	
$sql =  ObtenerSQL_DatosdelaEmpresa();

$params[":siniestro"] =  $_SESSION["ReportesSiniestros"]["ID"];	
$params[":orden"] =  $_SESSION["ReportesSiniestros"]["ORDEN"];	

$stmt = DBExecSql($conn, $sql, $params);
//-----------------------------------------------------------------------
if (DBGetRecordCount($stmt) == 0) {
	echo ("La consulta no devolviò datos.");
	exit;
}

$rowCabecera = DBGetQuery($stmt, 1, false);
//-----------------------------------------------------------------------
// $pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/varios/templates/ListadoJuiciosVerticalBlanco.pdf");
$stmt = DBExecSql($conn, $sql, $params);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage('P', 'Legal');

	while ($row = DBGetQuery($stmt, 1, false)) {
		$pdf->SetX($pdf->margenDerecho);
		$pdf->SetTextColor(0, 0, 0); 	
		$pdf->SetFontAlignGeneral();	
		$Newrow = array_values($row);	
		
		$pdf->Row($Newrow);	
				
		$pdf->LineaSepara();
	}
	
	unset($_SESSION['ReportesSiniestros']["ReporteDatosdelaEmpresa"]);	
	
	// enviamos cabezales http para no tener problemas
	header('Content-Type: text/html; charset=UTF-8'); 
	//header('Content-Type: text/html; charset=iso-8859-1');
	header("Content-Transfer-Encoding", "binary");
	//header('Cache-Control: maxage=3600');
	header('Cache-Control: private, max-age=0, must-revalidate');
	header('Pragma: public');

	$pdf->Output('ReporteDatosdelaEmpresa.pdf', 'I');
	
	// DBCommit($conn);							
}catch (Exception $e){
	DBRollback($conn);		
	EscribirLogTxt1("ReporteDatosdelaEmpresa.php", $e->getMessage() );
	echo 'ERROR: '.$e->getMessage();
}


