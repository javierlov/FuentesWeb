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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


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
	$rowCabecera = DBGetQuery($stmt, 1, false);

	$sqlBase =
		"SELECT rc_contrato contrato,
						rc_periodo periodo,
						rc_importereclamado importereclamado,
						deuda.get_tasaacumulada(deuda.get_vencimientocuota(SUBSTR(em_cuit, 11, 1), rc_periodo) + 1, SYSDATE) tasainteres,
						(rc_devengadocuota + rc_devengadofondo + rc_devengadootros) - (rc_pagocuota + rc_pagofondo + rc_pagootros + rc_recuperocuota + rc_recuperofondo) - rc_importereclamado - rc_montorefinanciado deuda,
						cobranza.getsaldointereses(rc_contrato, rc_periodo) saldointereses
			 FROM aem_empresa, aco_contrato, zrc_resumencobranza
			WHERE rc_contrato = co_contrato
				AND rc_prescripto = 'N'
				AND co_idempresa = em_id
				AND rc_periodo >= deuda.get_primerperiodoconsiddeuda(rc_contrato)
				AND rc_contrato = :contrato";
	$whereBase = " WHERE ROUND(deuda * tasainteres / 100, 2) + saldointereses > 0 AND tasainteres > 0";

	$ultimoPeriodo = "";
	$params = array(":contrato" => $_REQUEST["id"]);
	$sql =
		"SELECT periodo
			 FROM (".$sqlBase.") ".$whereBase."
	 ORDER BY periodo DESC";
	$stmt = DBExecSql($conn, $sql, $params);
	 if ($row = DBGetQuery($stmt, 1, false)) {
		$ultimoPeriodo = $row["PERIODO"];
		if ($row = DBGetQuery($stmt, 1, false))		// Tomo el anteúltimo período..
			$ultimoPeriodo = $row["PERIODO"];
	 }


	$params = array(":contrato" => $_REQUEST["id"]);
	$sql =
		"SELECT SUBSTR('".$ultimoPeriodo."', 5, 2) per_mes,
						SUBSTR('".$ultimoPeriodo."', 1, 4) per_ano,
						SUM(ROUND(deuda * tasainteres / 100, 2) + saldointereses) total,
						TO_CHAR(SUM(ROUND(deuda * tasainteres / 100, 2) + saldointereses), '$9,999,999,990.00') totalformateado
			 FROM (".$sqlBase.")
						".$whereBase."
	 ORDER BY periodo";
	$stmt = DBExecSql($conn, $sql, $params);

	//	*******  INICIO - Armado del reporte..  *******
	$pdf = new FPDI("L", "mm", array(222, 280));
	$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/estado_cuenta/templates/formulario_801c.pdf");

	while ($rowPeriodos = DBGetQuery($stmt, 1, false)) {
		$pdf->AddPage("L");
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx);

		$pdf->SetFont("Arial", "B", 14);
		$pdf->Ln(6.6);
		$pdf->Cell(176);
		$pdf->Cell(0, 0, $rowCabecera["CUIT"]);

		$pdf->SetFont("Arial", "", 10);
		$pdf->Ln(20);
		$pdf->Cell(91);
		$pdf->Cell(170, 0, $rowCabecera["DESTINATARIO"]);

		$pdf->Ln(16);
		$pdf->Cell(91);
		$pdf->Cell(170, 0, $rowCabecera["DOMICDESTINATARIO"]);

		$pdf->Ln(149);
		$pdf->Cell(-2);
		$pdf->Cell(9, 0, $rowPeriodos["PER_MES"], 0, 0, "C");

		$pdf->Cell(-0.4);
		$pdf->Cell(11, 0, $rowPeriodos["PER_ANO"], 0, 0, "C");

		$pdf->SetFont("Arial", "B", 14);
		$pdf->Cell(10);
		$pdf->Cell(85, 0, $rowPeriodos["TOTALFORMATEADO"], 0, 0, "C");

		$pdf->SetY(196.4);
		$texto = strtoupper(numerosALetras(str_replace(",", ".", $rowPeriodos["TOTAL"]), 2, true));
		$pdf->WordWrap($texto, 145);
		$texto = explode("\n", $texto);
		for ($i=0; $i<count($texto); $i++) {
			$str = trim($texto[$i]);

			$pdf->Cell(116);
			$pdf->Cell(0, 0, $str);
			$pdf->Ln(5);
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