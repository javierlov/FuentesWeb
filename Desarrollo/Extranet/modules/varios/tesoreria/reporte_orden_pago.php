<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

if (!isset($_REQUEST["op"])) {
	echo "La consulta no devolvió datos.";
	exit;
}

SetDateFormatOracle("DD/MM/YYYY");

define("MAX_LINEAS_POR_HOJA", 17);

$_REQUEST["op"] = intval($_REQUEST["op"]);

$params = array(":op" => $_REQUEST["op"]);
$sql =
	"SELECT ch.check_id orden_de_pago, provart_beneficiary_fn@realfcl(ch.vendor_id, ch.checkrun_name, ch.check_id) beneficiario, ch.vendor_name proveedor, ch.address_line1 domicilio_l1,
					ch.address_line2 domicilio_l2, ch.address_line3 domicilio_l3, ch.city domicilio_l4, ch.state || ' ' || ch.zip || ' ' || ch.country cp, aisc.check_date fecha,
					ch.check_number nro_de_cheque, chs.attribute2 metodo_de_pago, u.description confecciono
		 FROM ap_checks_all@realfcl ch, ap_check_stocks_all@realfcl chs, fnd_user@realfcl u, ap_bank_accounts_all@realfcl ba, ap_bank_branches@realfcl bb,
					ap_inv_selection_criteria_all@realfcl aisc
		WHERE ch.bank_account_id = ba.bank_account_id
			AND ch.check_id = :op
			AND ba.bank_branch_id = bb.bank_branch_id
			AND ch.checkrun_name = aisc.checkrun_name
			AND ch.last_updated_by = u.user_id
			AND ch.check_stock_id = chs.check_stock_id
 ORDER BY ch.check_number";
$stmt = DBExecSql($conn, $sql, $params);
$rowCabecera = DBGetQuery($stmt, 1, false);

$params = array(":op" => $_REQUEST["op"]);
$sql = "SELECT   DECODE(iv.invoice_type_lookup_code,
                'STANDARD', 'FACTURA',
                'CREDIT', 'NOTA DE CREDITO',
                'DEBIT', 'NOTA DE DEBITO',
                'MIXED', 'COMPROBANTE INTERNO') tipo,
				 iv.invoice_date fecha, iv.invoice_num numero, iv.description descripcion, 
				 
				 TO_CHAR(iv.invoice_amount, '".DB_FORMATMONEY."')  MONTOFORMAT,
				 iv.invoice_amount  MONTO
						 
			FROM ap_invoice_payments_all@realfcl ip, ap_invoices_all@realfcl iv
		   WHERE ip.invoice_id = iv.invoice_id
			 AND ip.check_id = :op
		UNION ALL
		SELECT   'CERT. DE RETENCION',
				 jc.awt_date,
				 jc.attribute1,
				 jc.awt_type_code,
				 TO_CHAR (jc.withholding_amount, '".DB_FORMATMONEY."') MONTOFORMAT,
				 jc.withholding_amount MONTO
				 
			FROM ap_checks_all@realfcl ac, jl_ar_ap_awt_certif_all@realfcl jc
		   WHERE jc.status <> 'VOID'
			 AND jc.check_number = ac.check_number
			 AND jc.checkrun_name = ac.checkrun_name
			 AND ac.check_id = :op
		ORDER BY fecha";

$stmt = DBExecSql($conn, $sql, $params);

$pdf = new FPDI();
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/varios/tesoreria/templates/orden_pago.pdf");

$i = 1;
$netoAPagar = 0;
while ($row = DBGetQuery($stmt, 1, false)) {
	if ($i == 1) {
		$pdf->AddPage();
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx);

		// Dibujo la cabecera..
		$pdf->SetFont("Arial", "", 16);
		$pdf->Ln(3);
		$pdf->Cell(54);
		$pdf->Cell(0, 0, $rowCabecera["ORDEN_DE_PAGO"]);

		$pdf->SetFont("Arial", "", 11);
		$pdf->Ln(7.2);
		$pdf->Cell(20);
		$pdf->Cell(136, 0, $rowCabecera["BENEFICIARIO"]);

		$pdf->SetFont("Arial", "", 7);
		$pdf->Ln(14.8);
		$pdf->Cell(22);
		$pdf->Cell(102, 0, $rowCabecera["PROVEEDOR"]);

		$pdf->Ln(5.4);
		$pdf->Cell(22);
		$pdf->Cell(102, 0, $rowCabecera["DOMICILIO_L1"]);

		$pdf->Ln(5.4);
		$pdf->Cell(22);
		$pdf->Cell(88, 0, $rowCabecera["DOMICILIO_L2"]);

		$pdf->Ln(4.6);
		$pdf->Cell(22);
		$pdf->Cell(88, 0, $rowCabecera["DOMICILIO_L3"]);

		$pdf->Cell(25.2);
		$pdf->Cell(60, 0, $rowCabecera["FECHA"]);

		$pdf->Ln(5);
		$pdf->Cell(22);
		$pdf->Cell(88, 0, $rowCabecera["DOMICILIO_L4"]);

		$pdf->Cell(25.2);
		$pdf->Cell(60, 0, $rowCabecera["NRO_DE_CHEQUE"]);

		$pdf->Ln(4.8);
		$pdf->Cell(22);
		$pdf->Cell(88, 0, $rowCabecera["CP"]);

		$pdf->Cell(25.2);
		$pdf->Cell(60, 0, $rowCabecera["METODO_DE_PAGO"]);

		$pdf->Ln(116);
		$pdf->Cell(2);
		$pdf->Cell(44, 0, $rowCabecera["CONFECCIONO"], 0, 0, "C");

		$pdf->Ln(-99);
	}

	$pdf->SetFont("Arial", "", 7);
	$pdf->Ln(3);
	$pdf->Cell(-5);
	$pdf->Cell(37.4, 0, $row["TIPO"]);

	$pdf->Cell(2);
	$pdf->Cell(16, 0, $row["FECHA"]);

	$pdf->Cell(0.4);
	$pdf->Cell(30, 0, $row["NUMERO"]);

	$pdf->Cell(1.4);
	$pdf->Cell(95, 0, $row["DESCRIPCION"]);

	$pdf->SetFont("Arial", "", 8);
	$pdf->Cell(2);
	$pdf->Cell(17, 0, trim($row["MONTOFORMAT"]), 0, 0, "R");
	$pdf->SetFont("Arial", "", 7);
	
	$pdf->Ln(2);
	$pdf->Rect($pdf->GetX() - 4.6, $pdf->GetY(), 204, 0.2, "F");

	$i++;
	if ($i > MAX_LINEAS_POR_HOJA) {
		$i = 1;
	}
	$netoAPagar+= str_replace(",", ".", $row["MONTO"]);
}

$sql = "SELECT TO_CHAR(".$netoAPagar.", '".DB_FORMATMONEY."') montoformateado FROM DUAL";

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(4);
$pdf->Cell(132);
$pdf->Cell(20, 0, "Neto a Pagar:");
$pdf->Cell(44, 0, ValorSql($sql, 0, array()), 0, 0, "R");

$pdf->Output();
?>