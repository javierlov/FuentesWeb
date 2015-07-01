<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 58));

try {
	// Armo el sql principal..
	$curs = null;
	$params = array(":ncontrato" => $_SESSION["contrato"], ":nperiodo" => $_REQUEST["periodo"]);
	$sql = "BEGIN ART.WEBART.get_cab_cuentacorriente(:data, :ncontrato, :nperiodo); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs, false);

	$totalCredito = 0;
	$totalDebito = 0;
	$saldo = str_replace(",", ".", $row["SALDO"]);


	//	*******  INICIO - Armado del reporte..  *******
	$pdf = new FPDF();

	$pdf->AddPage();
	$pdf->SetFont("Arial", "", 8);

	$pdf->Image("http://www.provinciart.com.ar/images/provart_blanco.png", 6, NULL, 50, 17);

	$pdf->Ln(-4);
	$pdf->Cell(64);
	$pdf->SetFont("Arial", "B", 14);
	$pdf->Cell(68, 0, "Estado de Situación de Pagos");

	$pdf->Cell(12);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(28, 0, "Cuenta Corriente al");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(0, 0, $row["CTACTE_AL"]);

	$pdf->Ln(3);
	$pdf->Line($pdf->GetX(), $pdf->GetY(), 200, $pdf->GetY());

	$pdf->Ln(2);
	$pdf->Cell(0, 0, "Según resolución SRT 441/06", 0, 0, "C");

	// Datos del cliente..
	$pdf->Ln(8);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(0, 0, "Datos del Cliente");

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(20, 0, "C.U.I.T.");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(168, 0, $row["CUIT"]);

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(20, 0, "Razón Social");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(168, 0, $row["RAZON_SOCIAL"]);

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(20, 0, "Dirección");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(168, 0, $row["DOMICILIO"]);

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(20, 0, "Teléfono");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(168, 0, $row["TELEFONOS"]);

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(20, 0, "Actividad");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(16, 0, $row["COD_ACTIVIDAD"]);
	$pdf->Cell(154, 0, $row["DESC_ACTIVIDAD"]);

	// Datos del contrato..
	$pdf->Ln(8);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(0, 0, "Datos del Contrato");

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(28, 0, "Número");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(68, 0, $row["CONTRATO"]);

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(16, 0, "Vigencia");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(18, 0, $row["VIGENCIADESDE"]);
	$pdf->Cell(6, 0, "al");
	$pdf->Cell(0, 0, $row["VIGENCIAHASTA"]);

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(28, 0, "Gestor de Cuenta");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(160, 0, $row["GESTOR_CUENTA"]);

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(28, 0, "Ejecutivo");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(68, 0, $row["EJECUTIVO"]);

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(16, 0, "Consultas");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(0, 0, "0-800-333-1278");

	// Datos de cobertura..
	$pdf->Ln(8);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(44, 0, "Datos del Período de Cobertura");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(72, 0, $_REQUEST["periodo"]);

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(24, 0, "Vencimiento");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(72, 0, $row["VENCIMIENTO"]);

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(48, 0, "Trabajadores declarados ".$row["PERIODO2"]);

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(0, 0, $row["TRAB_DECLARADO"], 0, 0, "R");

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(24, 0, "DDJJ");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(72, 0, $row["DDJJ"]);

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(48, 0, "Masa salarial declarada ".$row["PERIODO2"]);

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(0, 0, $row["MASA_DECLARADO"], 0, 0, "R");

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(48, 0, "Alícuota fija declarada por trabajador");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(48, 0, $row["ALICUOTA_FIJA_T"]);

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(68, 0, "Alícuota variable declarada sobre la masa salarial");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(26, 0, "% ".$row["ALICUOTA_VAR_T"], 0, 0, "R");

	$pdf->SetDrawColor(0, 0, 0);
	$pdf->Rect(10, 34, 190, 4);
	$pdf->Rect(10, 38, 190, 16);
	$pdf->Rect(10, 54, 190, 4);
	$pdf->Rect(10, 62, 190, 4);
	$pdf->Rect(10, 66, 190, 12);
	$pdf->Rect(10, 82, 190, 4);
	$pdf->Rect(10, 86, 190, 12);


	$pdf->Ln(6);
	$pdf->Cell(-1);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(80, 0, "Concepto");
	$pdf->Cell(40, 0, "Débito");
	$pdf->Cell(40, 0, "Crédito");
	$pdf->Cell(0, 0, "Saldo");

	$pdf->Ln(4);
	$pdf->Cell(-1);
	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(130, 0, "Saldo al ".$row["SALDO_DESDE"]);
	$pdf->Cell(40, 0, "$".number_format($saldo, 2), 0, 0, "R");
	$pdf->Ln(1.4);
	$pdf->Line($pdf->GetX(), $pdf->GetY(), 179, $pdf->GetY());


	$params = array(":contrato" => $_SESSION["contrato"], ":periodo" => $_REQUEST["periodo"]);
	$sql =
		"SELECT dc_concepto concepto, dc_debito debito, dc_credito credito
			 FROM v_wdc_detalleestadocuenta
			WHERE dc_contrato = :contrato
				AND dc_periodo = :periodo";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row2 = DBGetQuery($stmt, 1, false)) {
		$credito = str_replace(",", ".", $row2["CREDITO"]);
		$debito = str_replace(",", ".", $row2["DEBITO"]);

		$totalCredito = $totalCredito + $credito;
		$totalDebito = $totalDebito + $debito;
		$saldo = $saldo + $debito - $credito;


		$pdf->Ln(2);
		$pdf->Cell(-1);
		$pdf->SetFont("Arial", "", 8);
		$pdf->Cell(68, 0, $row2["CONCEPTO"]);
		$pdf->Cell(23, 0, ($debito=="0.00")?"":"$".number_format($debito, 2), 0, 0, "R");
		$pdf->Cell(41, 0, ($credito=="0.00")?"":"$".number_format($credito, 2), 0, 0, "R");
		$pdf->Cell(38, 0, "$".number_format($saldo, 2), 0, 0, "R");
		$pdf->Ln(1.4);
		$pdf->Line($pdf->GetX(), $pdf->GetY(), 179, $pdf->GetY());
	}

	$pdf->SetDrawColor(128, 128, 128);
	$pdf->SetFillColor(200, 200, 200);
	$pdf->Rect($pdf->GetX() + 60, $pdf->GetY() + 1, 80, 3, "F");
	$pdf->SetDrawColor(0, 0, 0);

	$pdf->Ln(2);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(90, 0, "$".number_format($totalDebito, 2), 0, 0, "R");
	$pdf->Cell(41, 0, "$".number_format($totalCredito, 2), 0, 0, "R");

	$maxDiasMes = cal_days_in_month(CAL_GREGORIAN, substr($_REQUEST["periodo"], 4, 2), substr($_REQUEST["periodo"], 0, 4));
	$pdf->Ln(4);
	$pdf->Cell(-1);
	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(130, 0, "Saldo al ".$maxDiasMes."/".substr($_REQUEST["periodo"], 4, 2)."/".substr($_REQUEST["periodo"], 0, 4));
	$pdf->Cell(40, 0, "$".number_format($saldo, 2), 0, 0, "R");
	$pdf->Ln(1.4);
	$pdf->Line($pdf->GetX(), $pdf->GetY(), 179, $pdf->GetY());

	$pdf->Ln(2);
	$pdf->Cell(-1);
	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(130, 0, "Menos reclamos AFIP pendientes de acreditación");
	$pdf->Cell(40, 0, "$".number_format($row["RECLAMO_AFIP"], 2), 0, 0, "R");
	$pdf->Ln(1.4);
	$pdf->Line($pdf->GetX(), $pdf->GetY(), 179, $pdf->GetY());
	$saldo = $saldo + str_replace(",", ".", $row["RECLAMO_AFIP"]);

	$pdf->Ln(2);
	$pdf->Cell(-1);
	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(130, 0, "Menos períodos refinanciados no exigibles incluidos en el saldo anterior");
	$pdf->Cell(40, 0, "$".number_format($row["REFIN_NO_EXIGIBLE"], 2), 0, 0, "R");
	$pdf->Ln(1.4);
	$pdf->Line($pdf->GetX(), $pdf->GetY(), 179, $pdf->GetY());
	$saldo = $saldo + str_replace(",", ".", $row["REFIN_NO_EXIGIBLE"]);

	$pdf->Ln(2);
	$pdf->Cell(-1);
	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(130, 0, "Mas intereses por mora al cierre del período");
	$pdf->Cell(40, 0, "$".number_format($row["INTERES_X_MORA"], 2), 0, 0, "R");
	$pdf->Ln(1.4);
	$pdf->Line($pdf->GetX(), $pdf->GetY(), 179, $pdf->GetY());
	$saldo = $saldo + str_replace(",", ".", $row["INTERES_X_MORA"]);

	$pdf->Ln(2);
	$pdf->Cell(-1);
	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(130, 0, "Saldo neto exigible al ".$maxDiasMes."/".substr($_REQUEST["periodo"], 4, 2)."/".substr($_REQUEST["periodo"], 0, 4));
	$pdf->Cell(40, 0, "$".number_format($saldo, 2), 0, 0, "R");
	$pdf->Ln(1.4);
	$pdf->Line($pdf->GetX(), $pdf->GetY(), 179, $pdf->GetY());

	// Datos de deudas declaradas..
	$pdf->Ln(6);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(0, 0, "Deudas declaradas en concurso o quiebra");

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(20, 0, "Fecha");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(40, 0, $row["FECHACONCURSO"]);

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(20, 0, "Importe");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(0, 0, ($row["CONCURSO"]==0)?"":"$".number_format($row["CONCURSO"], 2));

	$pdf->Ln(6);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(20, 0, "Nota");

	$pdf->Ln(2);
	$pdf->SetFont("Arial", "", 8);
	$pdf->WordWrap($row["OBSERVACION"], 168);
	$pdf->Write(3, $row["OBSERVACION"]);

	// Último cuadro..
	$pdf->Ln(8);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(48, 0, "Alícuota Fija Vigente por Trabajador");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(40, 0, $row["ALICUOTA_FIJA"]);

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(68, 0, "Alícuota Variable Vigente sobre la Masa Salarial");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(0, 0, "%".$row["ALICUOTA_VARIABLE"]);

	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(48, 0, "Fondo Fiduciario Enf. Profesionales");

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(0, 0, $row["FONDO_FIJO"]);

	$pdf->Rect($pdf->GetX() - 190, $pdf->GetY() - 26, 190, 4);
	$pdf->Rect($pdf->GetX() - 190, $pdf->GetY() - 22, 190, 4);
	$pdf->Rect($pdf->GetX() - 190, $pdf->GetY() - 6, 190, 8);


	$pdf->Output("Estado_de_Situación_de_Pagos.pdf", "I");
	//	*******  FIN - Armado del reporte..  *******
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>