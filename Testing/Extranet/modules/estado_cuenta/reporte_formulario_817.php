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

function setDatos($posicion, $row, $rowPeriodos) {
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
	$pdf->Cell(16, 0, $rowPeriodos["PER_MES"], 0, 0, "C");

	$pdf->Cell(4);
	$pdf->Cell(16, 0, $rowPeriodos["PER_ANO"], 0, 0, "C");

	$pdf->Cell(66);
	$pdf->Cell(64, 0, $rowPeriodos["TOTAL"], 0, 0, "C");
}


$_REQUEST["id"] = intval($_REQUEST["id"]);

validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion((validarContrato($_REQUEST["id"])));

try {
	SetDateFormatOracle("DD/MM/YYYY");

	$params = array(":contrato" => $_REQUEST["id"]);
	$sql =
		"SELECT utiles.armar_cuit(em_cuit) cuit,
						em_nombre destinatario,
						utiles.armar_domicilio(dc_calle, dc_numero, dc_piso, dc_departamento) || ' - ' || utiles.armar_localidad(dc_cpostal, dc_cpostala, dc_localidad) || ' - ' || pv_descripcion domicdestinatario
			 FROM cpv_provincias, adc_domiciliocontrato, aem_empresa, aco_contrato
			WHERE co_contrato = :contrato
				AND co_idempresa = em_id
				AND co_contrato = dc_contrato
				AND dc_tipo = 'L'
				AND dc_provincia = pv_codigo";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt, 1, false);

	$params = array(":contrato" => $_REQUEST["id"]);
	$sql =
		"SELECT SUBSTR(period, 5, 2) per_mes, SUBSTR(period, 1, 4) per_ano, TO_CHAR(deuda, '$9,999,999,990.00') total
			 FROM (SELECT rc_contrato contrato,
										rc_periodo period,
										rc_importereclamado importereclamado,
										(rc_devengadocuota + rc_devengadofondo + rc_devengadootros) - (rc_pagocuota + rc_pagofondo + rc_pagootros + rc_recuperocuota + rc_recuperofondo) - rc_montorefinanciado - rc_importereclamado deuda
							 FROM zrc_resumencobranza
							WHERE rc_contrato = :contrato
								AND rc_prescripto = 'N')
			WHERE deuda > 0
				AND period >= art.deuda.get_primerperiodoconsiddeuda(contrato)
				AND cobranza.is_periodochequesrechazados(contrato, period) = 'N'
	 ORDER BY period";
	$stmt = DBExecSql($conn, $sql, $params);

	//	*******  INICIO - Armado del reporte..  *******
	$pdf = new FPDI();
	$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/estado_cuenta/templates/formulario_817.pdf");

	while ($rowPeriodos = DBGetQuery($stmt, 1, false)) {
		$pdf->AddPage();
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx);

		setDatos(1, $row, $rowPeriodos);

		if ($rowPeriodos = DBGetQuery($stmt, 1, false))
			setDatos(2, $row, $rowPeriodos);
		else {
			ocultar(2);
			ocultar(3);
			break;
		}

		if ($rowPeriodos = DBGetQuery($stmt, 1, false))
			setDatos(3, $row, $rowPeriodos);
		else {
			ocultar(3);
			break;
		}
	}

	$pdf->Output("EC_Formulario_817.pdf", "I");
	//	*******  FIN - Armado del reporte..  *******
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>