<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function ocultar($posicion) {
	global $pdf;

	switch ($posicion) {
		case 1:
			$pdf->SetY(9.4);
			$pdf->SetX(9);
			break;
		case 2:
			$pdf->SetY(98.4);
			$pdf->SetX(9);
			break;
		case 3:
			$pdf->SetY(187.6);
			$pdf->SetX(9);
			break;
	}

	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->Rect($pdf->GetX() - 2, $pdf->GetY() - 4, 200, 88, "DF");
}

function setDatos($posicion, $row) {
	global $pdf;

	switch ($posicion) {
		case 1:
			$pdf->SetY(9.4);
			$pdf->SetX(9);
			break;
		case 2:
			$pdf->SetY(98.4);
			$pdf->SetX(9);
			break;
		case 3:
			$pdf->SetY(187.6);
			$pdf->SetX(9);
			break;
	}

	$pdf->SetFont("Arial", "B", 14);
	$pdf->Ln(3);
	$pdf->Cell(128);
	$pdf->Cell(0, 0, $row["CUIT"]);

	$pdf->SetFont("Arial", "", 10);
	$pdf->Ln(12.6);
	$pdf->Cell(90);
	$pdf->Cell(104, 0, $row["DESTINATARIO"]);

	$pdf->Ln(10);
	$pdf->Cell(90);
	$pdf->Cell(104, 0, $row["DOMICDESTINATARIO"]);

	$pdf->SetFont("Arial", "B", 14);
	$pdf->Ln(48.4);
	$pdf->Cell(28);
	$pdf->Cell(16, 0, $row["PER_MES"], 0, 0, "C");

	$pdf->Cell(4);
	$pdf->Cell(16, 0, $row["PER_ANO"], 0, 0, "C");

	$pdf->Cell(66);
	$pdf->Cell(64, 0, $row["TOTAL"], 0, 0, "C");
}


validarSesion(isset($_SESSION["isAgenteComercial"]));

try {
	SetDateFormatOracle("DD/MM/YYYY");

	$id = intval(substr($_REQUEST["id"], 1));
	$modulo = strtoupper(substr($_REQUEST["id"], 0, 1));

	$params = array(":id" => $id);
	$sql =
		"SELECT utiles.armar_cuit(sa_cuit) cuit,
						sa_nombre destinatario,
						utiles.armar_domicilio(sa_calle, sa_numero, sa_piso, sa_departamento) || ' - ' || utiles.armar_localidad(sa_cpostal, sa_cpostala, sa_localidad) || ' - ' || pv_descripcion domicdestinatario,
						SUBSTR(ti_periodo, 1, 4) per_ano,
						SUBSTR(ti_periodo, 5, 2) per_mes,
						TRIM(TO_CHAR(NVL(ti_deuda, 0), '$9,999,999,990.00')) total
			 FROM asa_solicitudafiliacion, ati_traspasoingreso, cpv_provincias
			WHERE sa_idformulario = ti_idformulario
				AND sa_provincia = pv_codigo(+)
				AND ".(($modulo == "C")?"sa_idsolicitudcotizacion":"sa_idrevisionprecio")." = :id
	 ORDER BY ti_id DESC";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt, false);


	//	*******  INICIO - Armado del reporte..  *******
	$pdf = new FPDI();
	$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/estado_cuenta/templates/formulario_817.pdf");
	$pdf->AddPage();
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx);

	setDatos(1, $row);
	ocultar(2);
	ocultar(3);

	$pdf->Output("F817.pdf", "I");
	//	*******  FIN - Armado del reporte..  *******
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>