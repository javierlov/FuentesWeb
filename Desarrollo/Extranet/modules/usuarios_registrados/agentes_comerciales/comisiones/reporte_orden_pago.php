<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


function dibujarCabecera($row) {
	global $pdf;

	$pdf->AddPage();
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx);

	$pdf->SetFont("Arial", "", 18);
	$pdf->Ln(6);
	$pdf->Cell(2);
	$pdf->Cell(0, 0, "ORDEN DE PAGO Nº ".$row["ORDEN_DE_PAGO"]);

	$pdf->SetFont("Arial", "", 12);
	$pdf->Ln(6);
	$pdf->Cell(1);
	$pdf->Cell(144, 0, " Beneficiario: ".$row["BENEFICIARIO"]);

	$pdf->SetFont("Arial", "", 8);
	$pdf->Ln(10);
	$pdf->Cell(11.4);
	$pdf->Cell(96, 0, "Proveedor: ".$row["PROVEEDOR"]);

	$pdf->Cell(14);
	$pdf->Cell(72, 0, "CUIT: 30-68825409-0");

	$pdf->Ln(4);
	$pdf->Cell(13);
	$pdf->Cell(92, 0, "Domicilio: ".$row["DOMICILIO1"]);

	$pdf->Cell(18.4);
	$pdf->Cell(72, 0, "IVA: RESPONSABLE INSCRIPTO");

	$pdf->Ln(4);
	$pdf->Cell(26);
	$pdf->Cell(80, 0, $row["DOMICILIO2"]);

	$pdf->Cell(2.4);
	$pdf->Cell(72, 0, "Ingresos Brutos: C.M.901-183258-4");

	$pdf->Ln(4);
	$pdf->Cell(26);
	$pdf->Cell(80, 0, $row["DOMICILIO3"]);

	$pdf->Cell(14.4);
	$pdf->Cell(72, 0, "Fecha: ".$row["FECHA"]);

	$pdf->Ln(4);
	$pdf->Cell(26);
	$pdf->Cell(80, 0, $row["LOCALIDAD"]);

	$pdf->Cell(4.6);
	$pdf->Cell(72, 0, "Nº de Cheque: ".$row["NRO_DE_CHEQUE"]);

	$pdf->Ln(4);
	$pdf->Cell(19.6);
	$pdf->Cell(84, 0, "CP: ".$row["CP"]);

	$pdf->Cell(3.9);
	$pdf->Cell(72, 0, "Método de Pago: ".$row["METODO_DE_PAGO"]);

	$pdf->Ln(8);
	$pdf->Rect($pdf->GetX() - 5, $pdf->GetY(), 200, 0.2, "F");

	$pdf->Ln(2.6);
	$pdf->Cell(-5);
	$pdf->Cell(32, 0, "Tipo", 0, 0, "C");
	$pdf->Cell(20, 0, "Fecha", 0, 0, "C");
	$pdf->Cell(32, 0, "Número", 0, 0, "C");
	$pdf->Cell(84, 0, "Concepto / Descripción", 0, 0, "C");
	$pdf->Cell(32, 0, "Monto", 0, 0, "R");

	$pdf->Ln(2);
	$pdf->Rect($pdf->GetX() - 5, $pdf->GetY(), 200, 0.2, "F");
	$pdf->Ln(-1);
}


SetDateFormatOracle("DD/MM/YYYY");

$params = array(":check_id" => $_REQUEST["id"]);
$sql =
	"SELECT ch.check_id orden_de_pago,
					provart_beneficiary_fn@realfcl(ch.vendor_id, ch.checkrun_name, ch.check_id) beneficiario,
					ch.vendor_name proveedor, ch.address_line1 domicilio1, ch.address_line2 domicilio2,
					ch.address_line3 domicilio3, ch.city localidad, ch.state || ' ' || ch.zip || ' ' || ch.country cp,
					ch.check_date fecha, ch.check_number nro_de_cheque, chs.attribute2 metodo_de_pago
		 FROM ap_checks_all@realfcl ch, ap_check_stocks_all@realfcl chs, fnd_user@realfcl u,
					ap_bank_accounts_all@realfcl ba, ap_bank_branches@realfcl bb, ap_inv_selection_criteria_all@realfcl aisc
		WHERE ch.bank_account_id = ba.bank_account_id
			AND ba.bank_branch_id = bb.bank_branch_id
			AND ch.checkrun_name = aisc.checkrun_name
			AND ch.last_updated_by = u.user_id
			AND ch.check_stock_id = chs.check_stock_id
			AND ch.check_id = :check_id";
$stmt = DBExecSql($conn, $sql, $params);
$rowCabecera = DBGetQuery($stmt);

$params = array(":orden_de_pago" => $_REQUEST["id"]);
$sql =
	"SELECT *
		 FROM (SELECT ip.check_id orden_de_pago,
									DECODE(iv.invoice_type_lookup_code,
												 'STANDARD', 'FACTURA',
												 'CREDIT', 'NOTA DE CREDITO',
												 'DEBIT', 'NOTA DE DEBITO',
												 'MIXED', 'COMPROBANTE INTERNO') tipo,
									iv.invoice_date fecha, iv.invoice_num numero, iv.description descripcion, iv.invoice_amount monto,
									TO_CHAR(iv.invoice_amount, '$9,999,999,990.00') montoformateado
						 FROM ap_invoice_payments_all@realfcl ip, ap_invoices_all@realfcl iv
						WHERE ip.invoice_id = iv.invoice_id
				UNION ALL
					 SELECT ac.check_id orden_de_pago, 'CERT. DE RETENCION' tipo, jc.awt_date fecha, jc.attribute1 numero,
									jc.awt_type_code descripcion, jc.withholding_amount monto,
									TO_CHAR(jc.withholding_amount, '$9,999,999,990.00') montoformateado
						 FROM ap_checks_all@realfcl ac, jl_ar_ap_awt_certif_all@realfcl jc
						WHERE jc.status <> 'VOID'
							AND jc.check_number = ac.check_number
							AND jc.checkrun_name = ac.checkrun_name)
		WHERE orden_de_pago = :orden_de_pago";
$stmt = DBExecSql($conn, $sql, $params);

$pdf = new FPDI();
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/agentes_comerciales/comisiones/templates/orden_pago.pdf");

define("MAX_REGISTROS_POR_HOJA", 52);

dibujarCabecera($rowCabecera);

$i = 1;
$netoAPagar = 0;
while ($row = DBGetQuery($stmt)) {
	if (($i % MAX_REGISTROS_POR_HOJA) == 0)
		dibujarCabecera();

	$pdf->Ln(4);
	$pdf->Cell(-5);
	$pdf->Cell(32, 0, $row["TIPO"], 0, 0, "C");
	$pdf->Cell(20, 0, $row["FECHA"], 0, 0, "C");
	$pdf->Cell(32, 0, $row["NUMERO"], 0, 0, "C");
	$pdf->Cell(84, 0, $row["DESCRIPCION"], 0, 0, "C");
	$pdf->Cell(32, 0, $row["MONTOFORMATEADO"], 0, 0, "R");
	$netoAPagar+= str_replace(",", ".", $row["MONTO"]);
	$i++;
}

$pdf->SetY(272);
$pdf->Ln(1);
$pdf->Rect($pdf->GetX() - 5, $pdf->GetY(), 200, 0.2, "F");

$sql = "SELECT TO_CHAR(".$netoAPagar.", '$9,999,999,990.00') montoformateado FROM DUAL";

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(2);
$pdf->Cell(132);
$pdf->Cell(20, 0, "Neto a Pagar:");
$pdf->Cell(43, 0, ValorSql($sql, 0, array()), 0, 0, "R");

$pdf->Output();
?>