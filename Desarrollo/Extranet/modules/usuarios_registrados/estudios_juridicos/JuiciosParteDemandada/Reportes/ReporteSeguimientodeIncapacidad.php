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
	
	
   function Header(){
		$this->Print_Logo();
		
		$this->SetDrawColor(0);
		$this->SetLineWidth(0);

		$this->SetXY(5,10);
		$this->SetFont('Arial' , 'B', 16);
		$this->Cell(0,0, 'Listado de Seguimiento de Incapacidades' ,0,0,'C');
	
		$psiniestro =  $_SESSION["ReportesSiniestros"]["ID"];	
		$porden =  $_SESSION["ReportesSiniestros"]["ORDEN"];	
		$datosEncabezado = Encabezado_SeguimientoIncapacidades($psiniestro, $porden);
			
		$this->SetFont('Arial' , 'B', 10);
		$this->SetY(15);
		$this->Cell(0,0, 'Siniestro: '.$datosEncabezado['SINIESTRO'] ,0,0,'C');
		$this->SetY(20);
		$this->Cell(0,0, 'CUIL: '.ponerGuiones($datosEncabezado['CUIL']).' - Apellido y nombre: '.$datosEncabezado['NOMBRE'] ,0,0,'C');
		$this->SetY(25);
		$this->Cell(0,0, 'Empresa: '.$datosEncabezado['EM_NOMBRE'] ,0,0,'C');
				
		$this->SetFont('Arial' , "I", 6);
		//$ahora = $rowEstableci["FECHASERVER"];
		date_default_timezone_set('UTC');
		$ahora =  date("d/m/Y");

		$this->SetXY(5, 5);
		$this->Cell(0,0, $ahora."  " ,0,0,'L');
		
		$this->EncabezadoReporte();
		$this->Ln(6);
   }
   
   
   function EncabezadoReporte(){
		$this->SetFontAlignGeneral();
		$this->SetFont('Arial' , 'B', 8);		
		
		$this->SetXY(10,30);
		$this->LineaSepara();		
		$this->SetXY(5,30);
		
		$this->Row(array(
			"Cod.Ev.",
			"Evento",
			"F.Evento",
			"Porc.Inc",
			"Grado",
			"Caracter",
			"Comisión",
			"Exped. Incap.",
			"Motivo",
			"Médico",
			"Fecha",
			"Hora",
			"Fecha Accidente", 
			"F. Alta Médica" 
			) );
		
		$this->SetXY(10,40);
		$this->LineaSepara();
   }
   
   function Footer(){		
		$this->SetXY(10, -10);
		$this->SetFillColor(254,254,254);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial' , '', 7);		
		$this->Cell(0,0, 'Página '.$this->PageNo()."  " ,0,0,'L');		
   }
      
   public function SetFontAlignGeneral(){		
		$this->SetFont('Arial' ,'I', 7);		
		$this->SetWidths(array(10,60,15,15,15, 
							   18,19,22,30,28, 
							   16,10,16,20 ));
							   
		$this->SetAligns(array('L','L','C','C','C',
							   'L','L','L','L','L',
							   'L','L','L','L'));		
   }
}

//-----------------------------------------------------------------------
try{	
	
	if( !isset($_SESSION["usuario"]) ){
		validarSesion(false);
		exit;
	}
	$UsuarioNombre = $_SESSION["usuario"];
	
	if( !isset($_SESSION['ReportesSiniestros']["ReporteSeguimientodeIncapacidad"]) ){
		header("Location: /JuiciosParteDemandada");		
		// CIERRA LA PAGINA
		// echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		exit;
	}
	
global $conn;


SetDateFormatOracle("DD/MM/YYYY");
$params = array();
	
$pdf=new PDFReport();
/*
$pdf->SetTitle("ReporteSeguimientodeIncapacidad");
$pdf->SetAuthor("JLovatto");
$pdf->SetCreator("ReporteSeguimientodeIncapacidad");
$pdf->SetSubject("REPORTE WEB LEGALES");
*/

$sql =  ObtenerSQL_SeguimientoIncapacidades();

$params[":psiniestro"] =  $_SESSION["ReportesSiniestros"]["ID"];	
$params[":porden"] =  $_SESSION["ReportesSiniestros"]["ORDEN"];	

$stmt = DBExecSql($conn, $sql, $params);
//-----------------------------------------------------------------------
if (DBGetRecordCount($stmt) == 0) {
	echo ("La consulta no devolviò datos.");
	exit;
}

$rowCabecera = DBGetQuery($stmt, 1, false);
//-----------------------------------------------------------------------
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/varios/templates/ListadoJuiciosVerticalBlanco.pdf");
$stmt = DBExecSql($conn, $sql, $params);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage('L', 'Legal');

	while ($row = DBGetQuery($stmt, 1, false)) {
		$pdf->SetX(5);
		$pdf->SetTextColor(0, 0, 0); 	
		$pdf->SetFontAlignGeneral();	
		$Newrow = array_values($row);	
		
		$pdf->Row($Newrow);	
		$pdf->LineaSepara();
	}
	
	unset($_SESSION['ReportesSiniestros']["ReporteSeguimientodeIncapacidad"]);	
	
	// enviamos cabezales http para no tener problemas
	//header('Content-Type: text/html; charset=UTF-8'); 
	//header('Cache-Control: maxage=3600');
	/*
	header('Content-Type: text/html; charset=iso-8859-1');
	header("Content-Transfer-Encoding", "binary");
	header('Cache-Control: private, max-age=0, must-revalidate');
	header('Pragma: public');
*/
	$pdf->Output();
	
	// DBCommit($conn);							
}catch (Exception $e){
	DBRollback($conn);		
	EscribirLogTxt1("ReporteSeguimientodeIncapacidad.php", $e->getMessage() );
	echo 'ERROR: '.$e->getMessage();
}


