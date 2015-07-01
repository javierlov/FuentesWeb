<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/PDF_tabla.php");

@session_start(); 

class PDFReport extends PDF_Tabla{
	
	private $FiltrosActivos;
	
	public function ListadoJuiciosSQL($Activos, $Terminados){
	
		$sql = "SELECT 
				 NVL(TO_CHAR(JT_NUMEROCARPETA), '') JT_NUMEROCARPETATXT,
				 NVL(TO_CHAR(JT_FECHANOTIFICACIONJUICIO), '') JT_FECHANOTIFICACIONJUICIOTXT,
				 NVL(TO_CHAR(EJ_DESCRIPCION), '') EJ_DESCRIPCIONTXT,
				 NVL(TO_CHAR(JT_DEMANDANTE), '') JT_DEMANDANTETXT,
				 NVL(TO_CHAR(JT_DEMANDADO), '') JT_DEMANDADOTXT,
				 NVL(TO_CHAR(JT_NROEXPEDIENTE), '') JT_NROEXPEDIENTETXT,
				 NVL(TO_CHAR(JT_ANIOEXPEDIENTE), '') JT_ANIOEXPEDIENTETXT,
				 NVL(TO_CHAR(JU_DESCRIPCION), '') JU_DESCRIPCIONTXT,
				 NVL(TO_CHAR(FU_DESCRIPCION), '') FU_DESCRIPCIONTXT,
				 NVL(TO_CHAR(JZ_DESCRIPCION), '') JZ_DESCRIPCIONTXT,
				 NVL(TO_CHAR(SC_DESCRIPCION), '') SC_DESCRIPCIONTXT,
				 NVL(TO_CHAR(JT_FECHAFINJUICIO), '') JT_FECHAFINJUICIOTXT,				 
				 NVL(TO_CHAR(BO_APELLIDO), '') || ' ' || NVL(TO_CHAR(BO_NOMBREINDIVIDUAL), '') 	NOMBREAPELLIDOTXT
		 
			FROM legales.ljt_juicioentramite, legales.lej_estadojuicio, legales.lju_jurisdiccion, legales.lfu_fuero, 
				 legales.ljz_juzgado, legales.lsc_secretaria, legales.lbo_abogado, legales.lnu_nivelusuario 
		   WHERE jt_idestado = ej_id 
			 AND jt_idjurisdiccion = ju_id 
			 AND (jt_idabogado = nu_idabogado OR nu_usuariogenerico = 'S') 
			 AND jt_idfuero = fu_id 
			 AND jt_idjuzgado = jz_id 
			 AND jt_idsecretaria = sc_id 
			 AND jt_idabogado = bo_id 
			 AND jt_idestado <> 3 
			 AND jt_estadomediacion = 'J' 
			 AND ROWNUM <= 2400
			 AND nu_usuario = :nu_usuario		 
			 ";
			 
			$strqry = "";		
			
			$this->FiltrosActivos = " (Todos)";
			if((!$Terminados) and ($Activos)){
				$strqry = ' AND jt_idestado <> 2';				
				$this->FiltrosActivos = " (Solo Activos)";
			}
			else if(($Terminados) and (!$Activos)){
				$strqry = ' AND jt_idestado = 2';
				$this->FiltrosActivos = " (Solo Terminados)";
			}
				
			$sqlFinal = $sql.$strqry." ORDER BY jt_numerocarpeta";
									
			return $sqlFinal;
	}
   
   function Header(){      
      $this->EncabezadoReport();
   }
   
   function Footer(){
		$this->SetY(-10);
		$this->SetFont('Arial' , "I", 8);		
		$this->Cell(0,10, utf8_decode('Página ').$this->PageNo(),0,0,'C');
   }
   
   function SetFontAlignGeneral(){		
		$this->SetFont('Arial' ,'I', 7);		
		$this->SetWidths(array(9,20,30,30,35,10,8,25,40,15, 16 ,15,30));
		$this->SetAligns(array('C','C','C','C','C','C','C','C','C','C','C','C','C'));		
   }
   
   function trimUTF8($Value){		
		return trim(utf8_decode($Value));
   }
   
   function DibujaLineaSepara(){
		$longline = 290;
		$posY = $this->GetY();
		//$this->Rect($this->GetX() - 4.6, $this->GetY(), $longline, 0.2, "F");
		$this->line($this->GetX() - 4.6, $posY, $longline, $posY);
   }
   
   function EncabezadoReport(){
   		
		$this->Image($_SERVER["DOCUMENT_ROOT"]."/images/estudios_juridicos/logoProvinciaART.jpg",230, 3);
		$this->SetXY(5,3);
		$this->SetFont('Arial' , 'B', 20);
		$this->Write(20,"Listado de Juicios  ");
		
		$posy = 17;
		$this->line(5, $posy, 100 , $posy);
		
		$this->SetFont('Arial' , "I", 6);
		$ahora = date("Y-m-d H:i");	        	 
		$this->SetXY(5, 20);	 	  
		$this->Cell(0,0, $ahora.$this->FiltrosActivos." Este Listado esta acotado a 2400 registros." ,0,0,'L');	 	  

		$tplIdx = $this->importPage(1);
		$this->useTemplate($tplIdx);
		$this->SetXY(5,25);
		
		$this->SetFontAlignGeneral();
		$this->SetFont('Arial' , 'B', 8);		
		
		$this->SetX(10);
		$this->DibujaLineaSepara();		
		$this->Row(array(
			$this->trimUTF8("JD Nº"),
			$this->trimUTF8("Fecha Notificación"),
			$this->trimUTF8("Estado"),
			$this->trimUTF8("Demandante"),
			$this->trimUTF8("Demandado"),
			$this->trimUTF8("Expte"),
			$this->trimUTF8("Año Nº"),
			$this->trimUTF8("Jurisdicción"),
			$this->trimUTF8("Fuero"),
			$this->trimUTF8("Juzgado Nº"),
			$this->trimUTF8("Secretaría"),
			$this->trimUTF8("Fecha Fin"),
			$this->trimUTF8("Estudio") ) );
		
		$this->SetXY(10,35);
		$this->DibujaLineaSepara();
   }      
}

//-----------------------------------------------------------------------
global $conn;

$UsuarioNombre = $_SESSION["usuario"];
$params = array();

$params[":nu_usuario"] = $UsuarioNombre;
SetDateFormatOracle("DD/MM/YYYY");

$Activos = false;
$Terminado = false;
	
if(isset($_REQUEST["Activos"])){
	$Activos = true;
	}
if(isset($_REQUEST["Terminado"])){
	$Terminado = true;
}
	
$pdf=new PDFReport();
$sql =  $pdf->ListadoJuiciosSQL($Activos, $Terminado);

$stmt = DBExecSql($conn, $sql, $params);
//-----------------------------------------------------------------------
if (DBGetRecordCount($stmt) == 0) {
	echo utf8_decode("La consulta no devolvió datos.");
	exit;
}

$rowCabecera = DBGetQuery($stmt, 1, false);

//-----------------------------------------------------------------------
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/varios/templates/ListadoJuiciosVerticalBlanco.pdf");
$stmt = DBExecSql($conn, $sql, $params);
$pdf->AddPage('L', 'Legal');

while ($row = DBGetQuery($stmt, 1, false)) {
	$pdf->SetTextColor(0, 0, 0); 
	//$pdf->SetX(5);
	$pdf->SetFontAlignGeneral();
	$Newrow = array_values($row);
	
	$pdf->Row($Newrow);	
	$pdf->DibujaLineaSepara();
}
//enviamos cabezales http para no tener problemas
header("Content-Transfer-Encoding", "binary");
header('Cache-Control: maxage=3600'); 
header('Pragma: public');

$pdf->Output();


