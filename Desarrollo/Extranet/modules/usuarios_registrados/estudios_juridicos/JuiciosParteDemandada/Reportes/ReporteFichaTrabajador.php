<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/PDF_tabla.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
//
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
	
	private $arrayDatosEncabezado;
	private $arrayDatosTrabajador;
	private $arrayDatosEmpresa;
	private $tipoContratacion ='';
	private $CIUO ='';
	
	private $idtrabajador = 0;
	private $margenDerecho = 5;
	private $arrayTitulosHistorico = array('CUIT', 
											'Contrato', 
											'Razón Social', 
											'Tarea', 
											'CIUO', 
											'Categoría', 
											'Sueldo', 
											'Sector'
									);
		
	function __construct() {
		//create
       parent::__construct();       
	   $this->SetArrayDatosEncabezado();	
	}
	
	public function SetArrayDatosEncabezado(){
			
			global $conn;
			$params = array();
			SetDateFormatOracle("DD/MM/YYYY");
	
			$params[":exid"] =  $_SESSION["ReportesSiniestros"]["ID"];	
			$sql =  utf8_decode(ObtenerSQL_ReporteFichaTrabajador());				
			$stmt = DBExecSql($conn, $sql, $params);
						
			//-----------------------------------------------------------------------
			if (DBGetRecordCount($stmt) == 0) {
				echo utf8_decode("La consulta no devolvio datos. ".$_SESSION["ReportesSiniestros"]["ID"] );		
				exit;
			}
			//-----------------------------------------------------------------------	
			$row = DBGetQuery($stmt, 1, false);

			$this->arrayDatosEncabezado = $row;

			$this->SetArrayDatosTrabajador($row['ID']);
			
			//-----------DATOS EMPRESA------------------------------------------------------------	
			if( $this->arrayDatosTrabajador['ML_IDRELACIONLABORAL'] <> '' )
				$this->SetArrayDatosEmpresa($row['CONTRATO']);
			else
				$this->SetArrayDatosEmpresa(0);
			//-----------TIPO CONTRATACION------------------------------------------------------------	
			$sql = ObtenerSQL_TipoContrato();
			$params = array();
			$params[":id"] =  $this->arrayDatosTrabajador["ML_IDMODALIDADCONTRATACION"];	
			$rowTipo = Retorna_DatosQuery($sql, $params);
			if( isset($rowTipo) ) 
				$this->tipoContratacion = $rowTipo['DESCRIPCION'];
			//-----------CIUO------------------------------------------------------------	
			$sql = ObtenerSQL_CIUO();
			$params = array();
			$params[":CODIGO"] =  $this->arrayDatosTrabajador["ML_CIUO"];	
			$rowCIUO = Retorna_DatosQuery($sql, $params);
			if( isset($rowCIUO) ) 
				$this->CIUO = $rowCIUO['DESCRIPCION'];
			//-----------------------------------------------------------------------	

	}
	
	public function SetArrayDatosTrabajador($idtrabajador){
			
			global $conn;
			$params = array();
			SetDateFormatOracle("DD/MM/YYYY");
	
			$params[":idtrabajador"] =  $idtrabajador;	
			$this->idtrabajador =  $idtrabajador;	
			
			$sql =  utf8_decode(ObtenerSQL_DetalleDatosTrabajador());				
			$stmt = DBExecSql($conn, $sql, $params);
	
			//-----------------------------------------------------------------------
			if (DBGetRecordCount($stmt) == 0) {				
				echo utf8_decode("La consulta no devolvio datos del trabajador. (".$idtrabajador.") (".$_SESSION["ReportesSiniestros"]["ID"].")... ");		
				exit;
			}
			//-----------------------------------------------------------------------	
			$row = DBGetQuery($stmt, 1, false);

			$this->arrayDatosTrabajador = $row;		
	}	
	
	public function SetArrayDatosEmpresa($contrato){

			global $conn;
			$params = array();
			SetDateFormatOracle("DD/MM/YYYY");
	
			$params[":contrato"] =  $contrato;	
			$sql =  utf8_decode(ObtenerSQL_DatosEmpresaTrabajador());	
			$stmt = DBExecSql($conn, $sql, $params);
			//-----------------------------------------------------------------------			
			if((DBGetRecordCount($stmt) == 0) or ($contrato == 0)) {
				//echo utf8_decode("La consulta no devolvio datos de la empresa.");		
				//exit;
				$row = array();
				$row['ID'] = '';
				$row['CUIT'] = '';
				$row['NOMBRE'] = '';
				$row['CONTRATO'] = '';
				$row['CODREG'] = '';
				$row['CONTRATOEXT'] = '';
				$row['IDTIPOREGIMEN_ORIG'] = '';
				$row['VIP'] = '';
				$row['CHECKCOBERTURA'] = '';
				$row['ORDENESTADO'] = '';
				$row['FECHA_BAJA'] = '';
			//-----------------------------------------------------------------------	
			}else{
				$row = DBGetQuery($stmt, 1, false);				
			}

			$this->arrayDatosEmpresa = $row;		
	}
	
   function Header(){
		$this->Print_Logo();
		
		$this->SetDrawColor(0);
		$this->SetLineWidth(0);

		$this->SetXY(10,10);
		$this->SetFont('Arial' , 'B', 16);
		$this->Cell(0,0, 'Contrato de Trabajadores (FICHA)' ,0,0,'C');
		
		$this->SetFont('Arial' , "I", 6);
		$borde = 0;
		//$ahora = $rowEstableci["FECHASERVER"];
		date_default_timezone_set('UTC');
		$ahora =  date("d/m/Y");

		$this->SetXY(5, 5);
		$this->Cell(0,0, $ahora."  " ,0,0,'L');
		//-----------------------------------------------------------		
		$this->SetFont('Arial' , 'B', 9);
		$x=15;$y=15;$lny=5;
			
		$vararray = $this->arrayDatosEncabezado;
		$vararrayTrabajador = $this->arrayDatosTrabajador;
		$vararrayEmpresa = $this->arrayDatosEmpresa;
		
		//------ Linea 1 ------ 
		$this->SetXY($x,$y); $y+=$lny;		
		$tCuil= str_pad(ponerGuiones($vararray["CUIL"]), 20);
		$tNombre= str_pad($vararray["NOMBRE"], 30);		
		$this->ImprimeLineaTextoMaxW($x, $y, "C.U.I.L.: ", $tCuil, 35,  $borde, '');
		$this->ImprimeLineaTexto($x+50, $y, " Nombre:  ", $tNombre, $borde, '');
		
		//------ Linea 2 ------ 		
		$this->SetXY($x,$y); $y+=$lny;		
		$tSexo= trim($vararrayTrabajador["SEXOS_DESCRIPCION"]);
		$tNacionalidad= trim($vararrayTrabajador["NACIONALIDAD_DESCRIPCION"]);
		$tNacimiento= trim($vararrayTrabajador["MT_FNACIMIENTO"]);		
		$this->ImprimeLineaTextoMaxW($x, $y, "Sexo:   ", $tSexo, 30, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+50, $y, " Nacionalidad: ", $tNacionalidad, 40, $borde, '');
		$this->ImprimeLineaTexto($x+120, $y, " F. Nacimiento: ", $tNacimiento, $borde, '');
		
		//------ Linea 3 ------ 		
		$this->SetXY($x,$y); $y+=$lny;
		$tEstadoCivil= trim($vararrayTrabajador["ESTAD_DESCRIPCION"]);
		$tDominante= trim($vararrayTrabajador["LATDO_DESCRIPCION"]);		
		$this->ImprimeLineaTextoMaxW($x, $y, "Est. Civil: ", $tEstadoCivil, 20, $borde, '');
		$this->ImprimeLineaTexto($x+50, $y, " L. Dominante: ", $tDominante, $borde, '');
		
		//------ Linea 4 ------ 		
		$this->SetXY($x,$y); $y+=$lny;
		$tMail= str_pad($vararrayTrabajador["MT_MAIL"],15);		
		$this->ImprimeLineaTexto($x, $y, "E-Mail: ", $tMail, $borde, '');
		
		//------ Linea 5 ------ 		
		$this->SetXY($x,$y); $y+=$lny;		
		$tDomicilio= trim($vararrayTrabajador["MT_CALLE"]);		
		$tNro= trim($vararrayTrabajador["MT_NUMERO"]);		
		$tPiso= trim($vararrayTrabajador["MT_PISO"]);		
		$tDto= trim($vararrayTrabajador["MT_DEPARTAMENTO"]);		
		
		$this->ImprimeLineaTextoMaxW($x, $y, "Domicilio: ", $tDomicilio, 60, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+80, $y, " Nro: ", $tNro, 30, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+120, $y, " Piso: ", $tPiso, 8, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+140, $y, " Dto: ", $tDto, 20, $borde, '');
		
		//------ Linea 6 ------ 		
		$this->SetXY($x,$y); $y+=$lny;
		$tCPostal= trim($vararrayTrabajador["MT_CPOSTAL"]);		
		$tCPA= trim($vararrayTrabajador["MT_CPOSTALA"]);		
		$tLocalidad= trim($vararrayTrabajador["MT_LOCALIDAD"]);		
		$tProvincia= trim($vararrayTrabajador["PV_DESCRIPCION"]);				
		$this->ImprimeLineaTextoMaxW($x, $y, "C. Postal: ", $tCPostal, 30, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+30, $y, " CPA:  ", $tCPA, 20, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+50, $y, " Localidad: ", $tLocalidad, 50, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+120, $y, " Provincia: ", $tProvincia, 100, $borde, '');
		
		//------ Linea 7 ------ 				
		$this->SetXY($x,$y); $y+=$lny;
		$tEdificio= trim($vararrayTrabajador["MT_EDIFICIO"]);		
		$this->ImprimeLineaTextoMaxW($x, $y, "Desc. Edif.: ", $tEdificio, 140, $borde, '');
		
		//------ Linea 8 ------ 				
		$this->SetXY($x,$y); $y+=$lny;
		$tTelefono= trim($vararrayTrabajador["TELEFONO_TRABAJADOR"]);		
		$this->ImprimeLineaTextoMaxW($x, $y, "Tel.: ", $tTelefono, 130, $borde, '');
		
		$this->SetXY($x,$y); $y+=$lny;
		//------ Linea 9 ------ 				
		$this->SetXY($x,$y); $y+=$lny;
		$tRazonSocial= trim($vararrayEmpresa["NOMBRE"]);				
		if($tRazonSocial == '')
			$tCUIT= '';		
		else
			$tCUIT= ponerGuiones($vararrayEmpresa["CUIT"]);		
		$this->ImprimeLineaTextoMaxW($x, $y, "C.U.I.T. Empresa: ", $tCUIT, 50, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+60, $y, "Razón Social: ", $tRazonSocial, 100, $borde, '');
		
		//------ Linea 10 ------ 				
		$this->SetXY($x,$y); $y+=$lny;
		$tContrato= trim($vararrayEmpresa["CONTRATO"]);		
		$tTipoContrato= trim($this->tipoContratacion);				
		$this->ImprimeLineaTextoMaxW($x, $y, "Contrato: ", $tContrato, 50, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+60, $y, "Tipo de Contrato: ", $tTipoContrato, 150, $borde, '');
		
		//------ Linea 11 ------ 				
		$this->SetXY($x,$y); $y+=$lny;
		$tFechaIngreso= trim($vararrayTrabajador["ML_FECHAINGRESO"]);		
		$tFechaRecepcion= trim($vararrayTrabajador["ML_FECHARECEPCION"]);				
		$this->ImprimeLineaTextoMaxW($x, $y, "Fecha Ingreso: ", $tFechaIngreso, 60, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+60, $y, "Fecha Recepción: ", $tFechaRecepcion, 60, $borde, '');
		
		//------ Linea 13 ------ 				
		$this->SetXY($x,$y); $y+=$lny;
		$tTarea= trim($vararrayTrabajador["ML_TAREA"]);				
		$this->ImprimeLineaTextoMaxW($x, $y, "Tarea:  ", $tTarea, 160, $borde, '');

		//------ Linea 14 ------ 				
		$this->SetXY($x,$y); $y+=$lny;
		$tCIUO= trim($this->CIUO);		
		$this->ImprimeLineaTextoMaxW($x, $y, "CIUO:  ", $tCIUO, 160, $borde, '');
		
		//------ Linea 15 ------ 				
		$this->SetXY($x,$y); $y+=$lny;
		$tSector= trim($vararrayTrabajador["ML_SECTOR"]);		
		$this->ImprimeLineaTextoMaxW($x, $y, "Sector:  ", $tSector, 160, $borde, '');
		
		//------ Linea 16 ------ 				
		$this->SetXY($x,$y); $y+=$lny;
		$tUltNomina= trim($vararrayTrabajador["ML_ULTIMANOMINA"]);		
		$tSueldo= trim($vararrayTrabajador["ML_SUELDO"]);		
		$tCategorIa= trim($vararrayTrabajador["ML_CATEGORIA"]);		
		
		$this->ImprimeLineaTextoMaxW($x, $y, "Ult. Nómina:  ", $tUltNomina, 40, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+50, $y, "Sueldo:  ", $tSueldo, 40, $borde, '');
		$this->ImprimeLineaTextoMaxW($x+100, $y, "Categoría:  ", $tCategorIa, 160, $borde, '');
		
		/*		*/
		$this->Ln(5);		

    }
   
    public function SetFontAlignGeneralHistorico(){				
		$this->SetWidths(array(20,20,35,50,20,20,20,20));							   
		$this->SetAligns(array('L','L','L','L','L',
								'R','R','L'));		
    }
   
    function HistoricoLaboral(){
		$ReturnSQL = ObtenerSQL_HistoricoLaboral();
		$ReturnSQL = utf8_decode($ReturnSQL);

		$params[":idtrabajador"] =  $this->idtrabajador;	
		$sql =  $ReturnSQL;

		global $conn;
		$stmt = DBExecSql($conn, $sql, $params);		
		$rowLiqCount = DBGetRecordCount($stmt);		
		
		if($rowLiqCount > 0) {
			$this->SetX($this->margenDerecho);
			$this->SetTextColor(0, 0, 0); 	
			$this->SetFontAlignGeneralHistorico();	
			$this->SetFont('Arial' ,'B', 10);		
			
			$Newrow = array_values($this->arrayTitulosHistorico);			
			$this->Row($Newrow);				
			$this->LineaSepara();
			$this->Ln(2);
			
			while ($row = DBGetQuery($stmt, 1, false)) {
				$this->SetX($this->margenDerecho);
				$this->SetTextColor(0, 0, 0); 	
				
				$this->SetFormatCell( array(6), 2, '$ ');
				
				$this->SetFontAlignGeneralHistorico();	
				$this->SetFont('Arial' ,'I', 7);		
				$Newrow = array_values($row);	
				$this->Row($Newrow);	
				$this->LineaSepara();
			}
		}
    }
   
   function Footer(){
		
		$this->SetY(-10);
		$this->SetFillColor(254,254,254);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial' , '', 7);
		
		$this->Cell(0,0, 'Página '.$this->PageNo()."  " ,0,0,'L');		
   }
   
}

//-----------------------------------------------------------------------
try{	
		
	if( !isset($_SESSION["usuario"]) ){
		validarSesion(false);
		exit;
	}
	$UsuarioNombre = $_SESSION["usuario"];
	
	if( !isset($_SESSION['ReportesSiniestros']["ReporteFichaTrabajador"]) ){
		header("Location: /JuiciosParteDemandada");		
		// CIERRA LA PAGINA
		// echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		exit;
	}
	
	//-------------------------------------------
	$pdf = new PDFReport();
	$pdf->SetTitle("ReporteFichaTrabajador");
	$pdf->SetAuthor("JLovatto");
	$pdf->SetCreator("ReporteFichaTrabajador");
	$pdf->SetSubject("REPORTE WEB LEGALES");
	
	$pdf->SetAutoPageBreak(true, 20);
	$pdf->AddPage('P', 'A4');
	$pdf->SetTitle('ReporteFichaTrabajador');	

	$pdf->HistoricoLaboral();			
	
	unset($_SESSION['ReportesSiniestros']["ReporteFichaTrabajador"]);
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
	EscribirLogTxt1("ReporteFichaTrabajador.php", $e->getMessage() );
	echo 'ERROR: '.$e->getMessage();
}


