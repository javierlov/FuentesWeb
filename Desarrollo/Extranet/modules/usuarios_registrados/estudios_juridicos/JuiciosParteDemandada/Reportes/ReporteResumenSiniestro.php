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


require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf.php");

@session_start();

class PDFReport extends PDF_Tabla{

	private $SiniestroNumero = '';
	private $IdExpediente = 0;
	private $margenDerecho = 5;

	private $arrayTitulosLiquidacion = array('Número', 
										'Descripción', 
										'Origen', 
										'Desde', 
										'Hasta', 
										'Importe', 
										'Proceso', 
										'Emisión',
										'Aprobado'
								);
		
	public function SetSiniestroNumero($numero){
		$this->SiniestroNumero = $numero;
	}
	
	public function SetIdExpediente($idExpediente){
		$this->IdExpediente = $idExpediente;
	}
	
	public function Listado_ReporteResumenSiniestro(){
		$ReturnSQL = ObtenerSQL_ReporteResumenSiniestro();
		return utf8_decode($ReturnSQL);
	}	
	
   function Header(){		
		$this->Print_Logo();
		
		$this->SetDrawColor(0);
		$this->SetLineWidth(0);

		$this->SetXY(75,10);
		$this->SetFont('Arial' , 'B', 16);
		$this->Cell(0,0, 'Resumen de Siniestro' ,0,0,'L');
		
		$this->SetFont('Arial' , "I", 6);
		//$ahora = $rowEstableci["FECHASERVER"];
		date_default_timezone_set('UTC');
		$ahora =  date("d/m/Y");

		$this->SetXY(5, 5);
		$this->Cell(0,0, $ahora."  " ,0,0,'L');
		
		$this->Ln(15);
   }
   
   function Footer(){
		
		$this->SetY(-10);
		$this->SetFillColor(254,254,254);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial' , '', 7);
		
		$this->Cell(0,0, 'Página '.$this->PageNo()."  " ,0,0,'L');
		$this->Cell(0,0, 'Siniestro '.$this->SiniestroNumero."  " ,0,0,'R');		
   }
      
   function PrintDatosReport($datos){
		$this->SetFillColor(254,254,254);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Arial' , 'B', 8);
	
		$posX1 = 12;
		$posY1 = 25;
		$sumY1 = 6;
		/*linea 1*/
		$this->ImprimeLineaTexto($posX1,$posY1,'Siniestro ',$datos['SINIESTRO'] ,0, '');  		
		
		$posX1 = $posX1+50;
		$this->ImprimeLineaTexto($posX1,$posY1,'Ocurrencias  ',$datos['OCURRENCIAS'],0, '');  
		$posX1 = $posX1+50;
		$this->ImprimeLineaTexto($posX1,$posY1,'Estado  ',$datos['ESTADO'],0, '');  
		
		/*linea 2*/
		$posY1 += $sumY1;
		$posX1 = 12;
		$posX1 = $posX1+50;
		$this->ImprimeLineaTextoCheck($posX1,$posY1,'Siniestros Múltiples ',$datos['HAY_MULTIPLES'],0);  		
		$posX1 = $posX1+50;
		$this->ImprimeLineaTextoCheck($posX1,$posY1,'Recaídas ',$datos['HAY_RECAIDAS'],0);  		
		$posX1 = $posX1+50;
		$this->ImprimeLineaTextoCheck($posX1,$posY1,'Pluriempleo ',$datos['ES_PLURIEMPLEO'],0);  
		
		/*linea 3*/
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->ImprimeLineaTexto($posX1,$posY1,'Ocurrido el ',$datos['EX_FECHAACCIDENTE'],0, '');  		
		
		$posX1 = $posX1+40;
		$this->ImprimeLineaTexto($posX1,$posY1,' a las ',$datos['EX_HORAACCIDENTE'],0, '');  		
		
		$posX1 = $posX1+40;
		$this->ImprimeLineaTexto($posX1,$posY1,'Fecha de inicio Derecho ',$datos['EX_BAJAMEDICA'],0, '');  		
		
		/*linea 4*/
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->ImprimeLineaTexto($posX1,$posY1,'Tipo de Denuncia ',$datos['TIPO'],0, '');  		
		$posX1 = $posX1+100;
		$this->ImprimeLineaTexto($posX1,$posY1,'Juicio Nº ',$datos['JUICIO'],0, '');  		
		
		/*linea 5*/
		$posY1 += $sumY1;
		$posX1 = 12;				
		$posX1 = $posX1+100;
		$this->ImprimeLineaTexto($posX1,$posY1,'Mediaciones ',$datos['MEDIACIONES'],0, '');  		
				
		/*seccion 1* linea */
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 		
		$this->LineaSepara();
		
		/*seccion 1 linea 1 */		
		$tipoSiniestro = Is_SiniestroDeGobernacion( $this->IdExpediente );
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,$tipoSiniestro.'  ',$datos['EMPRESA_NOMBRE'],0, true, 80);  		
		$posX1 = $posX1+120;
		$this->ImprimeLineaTexto($posX1,$posY1,'C.U.I.T. ',$datos['EX_CUIT'],0, 'I');  		
		/*seccion 1 linea 2 */		
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Domicilio ',$datos['EMPRESA_DOMICILIO'],0, false);  		
		/*seccion 1 linea 3 */		
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Teléfono ',$datos['EMPRESA_TELEFONOS'],0, false);  		
		/*seccion 1 linea 4 */		
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Localidad ',$datos['EMPRESA_LOCALIDAD'],0, false);  		
		$posX1 = $posX1+110;
		$this->ImprimeLineaTexto($posX1,$posY1,'Código Postal ',$datos['EMPRESA_CPOSTAL'],0, 'I');  		
		/*seccion 1 linea 5 */		
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Provincia ',$datos['EMPRESA_PROVINCIA'],0, false);  		
	
		/*seccion 2* linea */
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 		
		$this->LineaSepara();		
		/*seccion 2 linea 1 */		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Trabajador ',$datos['TJ_NOMBRE'],0, true);  		
		$posX1 = $posX1+120;
		$this->ImprimeLineaTexto($posX1,$posY1,'C.U.I.L. ',$datos['EX_CUIL'],0, 'I');  			
		/*seccion 2 linea 2 */		
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Domicilio ',$datos['TRABAJADOR_DOMICILIO'],0, false);  		
		/*seccion 2 linea 3 */		
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Teléfono ',$datos['TELEFONO'],0, false);  		
		/*seccion 2 linea 4 */		
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Localidad ',$datos['TJ_LOCALIDAD'],0, false);  		
		$posX1 = $posX1+110;
		$this->ImprimeLineaTexto($posX1,$posY1,'Código Postal ',$datos['TJ_CPOSTAL'],0, 'I');  		
		/*seccion 2 linea 5 */		
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Provincia ',$datos['TRABAJADOR_PROVINCIA'],0, false);  		
				
		/*seccion 3* linea */
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 		
		$this->LineaSepara();		
		/*seccion 3 linea 1 */		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Ubicación de la denuncia ',$datos['DENUNCIA_NOMBRE'],0, true);  		
		/*seccion 3 linea 2 */		
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Domicilio ',$datos['DENUNCIA_DOMICILIO'],0, false);  		
		/*seccion 3 linea 3 */		
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Teléfono ',$datos['DENUNCIA_TELEFONOS'],0, false); 
		
		/*seccion 4* linea */
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 		
		$this->LineaSepara();		
		/*seccion 4 linea 1 */		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Descripción ',$datos['EX_BREVEDESCRIPCION'],0, true);  		
		/*seccion 4 linea 2 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Observaciones ',$datos['EX_OBSERVACIONES'],0, true);  		
		/*seccion 4 linea 3 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Causa de cierre ',$datos['CAUSAFIN'],0, true);  		
		
		/*seccion 6 * linea */
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 		
		$this->LineaSepara();		
		/*seccion 6  linea 1 */		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Forma ',$datos['FORMA'],0, true);  		
		/*seccion 6  linea 2 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Naturaleza ',$datos['NATURALEZA'],0, true);  		
		/*seccion 6  linea 3 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Agente ',$datos['AGENTE'],0, true);  			
		/*seccion 6  linea 4 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Zona ',$datos['ZONA'],0, true);  		
		/*seccion 6  linea 5 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Gravedad ',$datos['GRAVEDAD'],0, true);  		
		/*seccion 6  linea 6 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Baja Médica ',$datos['EX_BAJAMEDICA'],0, true);  				
	//----------------------------------------------------------------------------------------------------------------------------
		$posX1 = $posX1+110;		
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Días Previstos ',$datos['PI_DIASBAJAPREVISTOS'],0, true);  			
		$posX1 = 12;		
	//----------------------------------------------------------------------------------------------------------------------------
		/*seccion 6  linea 7 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Fecha de egreso ',$datos['EX_ALTAMEDICA'],0, true);  			
	//----------------------------------------------------------------------------------------------------------------------------
		$posX1 = $posX1+110;		
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Días de baja Total ',$datos['DIASBAJA'],0, true);  			
		$posX1 = 12;		
	//----------------------------------------------------------------------------------------------------------------------------
		/*seccion 6  linea 8 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Diagnóstico de ingreso ',$datos['PI_DIAGNOSTICO'],0, true);  		
		/*seccion 6  linea 9 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Diagnóstico de egreso ',$datos['PE_DIAGNOSTICO'],0, true);  		
		/*seccion 6  linea 10 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'OMS CIE 10 ',$datos['EX_DIAGNOSTICOOMS'].'  '.$datos['DG_DESCRIPCION'],0, true);  		
		/*seccion 6  linea 11 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Días ',$datos['DG_DIASLEVE'].'      '.$datos['DG_DIASMODERADO'].'      '.$datos['DG_DIASGRAVE'],0, true);  		
	//----------------------------------------------------------------------------------------------------------------------------
		$posX1 = $posX1+110;		
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Incapacidad ',$datos['DG_INCAPACIDADLEVE'].'      '.$datos['DG_INCAPACIDADMODERADO'].'      '.$datos['DG_INCAPACIDADGRAVE'],0, true);  			
		$posX1 = 12;		
	//----------------------------------------------------------------------------------------------------------------------------		
	
		/*seccion 7 * linea */
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 		
		$this->LineaSepara();		
		/*seccion 7  linea 1 */		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Prestador ',$datos['PRESTADOR_NOMBRE'],0, true);  		
		/*seccion 7  linea 2 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Domicilio ',$datos['PRESTADOR_DOMICILIO'],0, false);  		
		/*seccion 7  linea 3 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Teléfono ',$datos['PRESTADOR_TELEFONO'],0, false);  
		
		/*seccion 8 * linea */
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 		
		$this->LineaSepara();		
		/*seccion 8  linea 1 */		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Incapacidades ','',0, true);  		
		/*seccion 8  linea 2 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Permanente ',$datos['EXISTEINCAPACIDAD'],0, false);  		
		/*seccion 8  linea 3 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Grado ',$datos['GRADO'],0, false);  	
	//----------------------------------------------------------------------------------------------------------------------------
		$posX1 = $posX1+110;		
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Carácter ',$datos['CARACTER'],0, false);  			
		$posX1 = 12;		
	//----------------------------------------------------------------------------------------------------------------------------				
		/*seccion 8  linea 4 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Gran Invalidez ',$datos['GRANINVALIDEZ'],0, false);  
	//----------------------------------------------------------------------------------------------------------------------------
		$posX1 = $posX1+110;		
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'% Provisorio ',$datos['SI_PORCPROVI'],0, false);  			
		$posX1 = 12;		
	//----------------------------------------------------------------------------------------------------------------------------				
	
		/*seccion 8  linea 5 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'% Definitivo ',$datos['SI_PORCDEF'],0, false);  
			
	//----------------------------------------------------------------------------------------------------------------------------
		$posX1 = $posX1+110;		
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Homologación ',$datos['FECHAHOMOLOGADO'],0, false);  			
		$posX1 = 12;		
	//----------------------------------------------------------------------------------------------------------------------------						
$this->AddPage('P', 'A4');
$posX1 = 12;
$posY1 = 25;	

		/*seccion 9 * linea */
		//$posY1 += $sumY1;				
		$this->SetXY($posX1,$posY1); 		
		$this->LineaSepara();		
		/*seccion 9  linea 1 */		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Documentación recibida ','',0, true);  		
		/*seccion 9  linea 2 */		
				///////$this->ImprimeLineaTexto($posX1,$posY1,'Siniestro ',$datos['SINIESTRO'],0, '');  		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTexto($posX1,$posY1,'Expediente       ',$datos['DOC_EXPEDIENTE'],0, '');  		
	//----------------------------------------------------------------------------------------------------------------------------
		$posX1 = $posX1+50;		
		$this->ImprimeLineaTexto($posX1,$posY1,'Denuncia         ',$datos['DOC_DENUNCIA'],0, '');  			
		$posX1 = $posX1+50;		
		$this->ImprimeLineaTexto($posX1,$posY1,'Parte de Ingreso ',$datos['DOC_INGRESO'],0, '');  			
		$posX1 = 12;		
	//----------------------------------------------------------------------------------------------------------------------------				
		/*seccion 9  linea 3 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTexto($posX1,$posY1,'Parte de Egreso  ',$datos['DOC_EGRESO'],0, '');  
	//----------------------------------------------------------------------------------------------------------------------------
		$posX1 = $posX1+50;		
		$this->ImprimeLineaTexto($posX1,$posY1,'Otros            ',$datos['DOC_OTROS'],0, '');  			
		$posX1 = $posX1+50;		
		$this->ImprimeLineaTexto($posX1,$posY1,'Denuncia Grave   ',$datos['DOC_DENUNCIAGRAVE'],0, '');  			
		$posX1 = $posX1+50;		
		$this->ImprimeLineaTexto($posX1,$posY1,'Parte Evolutivo  ',$datos['DOC_EVOLUTIVO'],0, '');  														   
		$posX1 = 12;		
	//----------------------------------------------------------------------------------------------------------------------------				
	
		/*seccion 10 * linea */
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 		
		$this->LineaSepara();		
		/*seccion 10  linea 1 */		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Estado de Cobranzas ','',0, true);  		
		/*seccion 10  linea 2 */		
		$posY1 += $sumY1;
		$this->SetXY($posX1,$posY1); 		
		$this->ImprimeLineaTexto($posX1,$posY1,'Cuota promedio   ',$datos['CUOTAPROMEDIO'],0, '');  
	//----------------------------------------------------------------------------------------------------------------------------
		$posX1 = $posX1+60;		
		$this->ImprimeLineaTexto($posX1,$posY1,'Deuda admitida   ',$datos['DEUDAADMITIDA'],0, '');  			
		$posX1 = $posX1+60;		
		$this->ImprimeLineaTexto($posX1,$posY1,'Deuda a vencer   ',$datos['DEUDA'],0, '');  					
		$posX1 = 12;		
	//----------------------------------------------------------------------------------------------------------------------------				
		
		/*seccion 11 * linea */
		$posY1 += $sumY1;
		$posX1 = 12;		
		$this->SetXY($posX1,$posY1); 		
		$this->LineaSepara();		
		/*seccion 11  linea 1 */		
		$this->SetXY($posX1,$posY1); 
		$this->ImprimeLineaTextoColumna($posX1,$posY1,'Liquidaciones ','',0, true);  		

		// $posY1 += $sumY1;
		/*seccion 11  Imprime liquidaciones */				
		$this->Iprimir_Liquidaciones($posY1, $sumY1, $datos['EX_ID'] ); 
				
   }

    public function SetFontAlignGeneralLiquidaciones($alignLeft = false){				
		$this->SetWidths(array( 20, 40, 20, 18, 18,  20, 25, 20, 20));		
		if($alignLeft) 
			$this->SetAligns(array('L','L','L','L','L', 'L','L','L','L'));		
		else
			$this->SetAligns(array('C','L','L','L','L', 'R','C','C','C'));		
    }
	
   	private function Iprimir_Liquidaciones($posY1, $sumY1, $EXID){

		$ReturnSQL = ObtenerSQL_ReporteLiquidaciones();
		$ReturnSQL = utf8_decode($ReturnSQL);		
	
		$params[":EXID"] =  $EXID;	
		$sql =  $ReturnSQL;
		
		global $conn;
		$stmt = DBExecSql($conn, $sql, $params);		
		$rowLiqCount = DBGetRecordCount($stmt);		
		
		if($rowLiqCount > 0) {
			
			$posX1 = 12;		
			$this->SetX($this->margenDerecho);
			$this->SetTextColor(0, 0, 0); 	
			$this->SetFontAlignGeneralLiquidaciones();	
			$this->SetFont('Arial' ,'B', 9);		
			$Newrow = array_values($this->arrayTitulosLiquidacion);				
			$this->Row($Newrow);	
			$this->LineaSepara();		
						
			$sumaImporte = 0;			
			
			while ($row = DBGetQuery($stmt, 1, false)) {			
				
				// EscribirLogTxt1("datos row text",  htmlentities(implode(',' , $row) , ENT_QUOTES) );
				
				$this->SetX($this->margenDerecho);
				$this->SetTextColor(0, 0, 0); 	
				$this->SetFontAlignGeneralLiquidaciones();	
				$this->SetFont('Arial' ,'', 6);		
				$this->SetFormatUTF8(true);		
				
				$this->SetFormatCell(array(5), 2, '$ ');
				
				$Newrow = array_values($row);	
				$this->Row($Newrow);	
				$this->LineaSepara();
				$sumaImporte = $sumaImporte + floatval($row['IMPORTE']);
			}
			
			$posY1=$this->GetY();				
			//$posY1 += $sumY1;
			$this->SetXY($posX1,$posY1); 		
									
			$posX1 = 100;
			$this->ImprimeCelda($posX1,$posY1,'Total','',0, 1, 0, 9);
			
			$posX1 = 125;												
			$psumaImporte = toMoney($sumaImporte);				
			
			$this->SetXY($posX1,$posY1);  
			$this->SetFont('Arial' ,'I', 8);		
			$this->Cell(100,5, $psumaImporte.'   ',0,0,'L');		
		}
	}
}

//-----------------------------------------------------------------------
try{	
		
	if( !isset($_SESSION["usuario"]) ){
		validarSesion(false);
		exit;
	}
	$UsuarioNombre = $_SESSION["usuario"];
	
	if( !isset($_SESSION['ReportesSiniestros']["ReporteResumenSiniestro"]) ){
		header("Location: /JuiciosParteDemandada");		
		// CIERRA LA PAGINA
		// echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		exit;
	}
	
	global $conn;
	$params = array();
	SetDateFormatOracle("DD/MM/YYYY");
	
	//-------------------------------------------
	$pdf = new PDFReport();
	$pdf->SetTitle("ResumenSiniestro");
	$pdf->SetAuthor("JLovatto");
	$pdf->SetCreator("REPORTEWEBLEGALES");
	$pdf->SetSubject("REPORTE WEB LEGALES");
			
	$params[":id"] =  $_SESSION["ReportesSiniestros"]["ID"];	
	$sql =  $pdf->Listado_ReporteResumenSiniestro();
	
		//EscribirLogTxt1("Listado_ReporteResumenSiniestro", $sql);
		EscribirLogTxt1("Listado_ReporteResumenSiniestro params", implode(',',$params) );
	
	
	$stmt = DBExecSql($conn, $sql, $params);
	//-----------------------------------------------------------------------
	if (DBGetRecordCount($stmt) == 0) {
		echo utf8_decode("La consulta no devolvio datos.");		
		exit;
	}
	//-----------------------------------------------------------------------	
	$pdf->SetAutoPageBreak(true, 20);
	$pdf->AddPage('P', 'A4');
		
	$row = DBGetQuery($stmt, 1, false);
	$pdf->SetSiniestroNumero($row['SINIESTRO']);
	$pdf->SetIdExpediente($_SESSION["ReportesSiniestros"]["ID"]);
	
	$pdf->PrintDatosReport($row);
			
	unset($_SESSION['ReportesSiniestros']["ReporteResumenSiniestro"]);
/*	
	header('Content-Type: text/html; charset=UTF-8'); 	
	header("Content-Transfer-Encoding", "binary");	
	header('Cache-Control: private, max-age=0, must-revalidate');
	header('Pragma: public');
*/		
	$pdf->Output('ReporteResumenSiniestro.pdf', 'I');
		
}catch (Exception $e){
	DBRollback($conn);		
	EscribirLogTxt1("ReporteResumenSiniestro.php", $e->getMessage() );
	echo 'ERROR: '.$e->getMessage();
}


