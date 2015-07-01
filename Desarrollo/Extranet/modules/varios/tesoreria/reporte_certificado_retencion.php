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

$_REQUEST["op"] = intval($_REQUEST["op"]);

$params = array(":op" => $_REQUEST["op"]);
$sql =
	"SELECT   jzat.certificate_header tipo_retencion, NVL(jaac.attribute1, jaac.certificate_number) nro_certificado,
         
         /* AGENTE DE RETENCION */
         hr.global_attribute8 nombre_ar,
         TRIM(hr.address_line_1 || ' ' || hr.address_line_2 || ' ' || hr.address_line_3) domicilio_l1_ar,
         hr.country || '-' || hr.postal_code || '-' || hr.town_or_city domicilio_l2_ar,
         SUBSTR(REPLACE(hr.global_attribute11, '-'), 1, 2) || '-' || SUBSTR(REPLACE(hr.global_attribute11, '-'), 3)
         || '-' || hr.global_attribute12 cuit_ar,
         (SELECT jgea_c.id_number
            FROM jg_zz_entity_assoc@realfcl jgea_c
           WHERE jgea_c.primary_id_number = REPLACE(hr.global_attribute11, '-')
             AND jgea_c.associated_entity_id = jzat.tax_authority_id
             AND jgea_c.id_type = jcat.tax_authority_type) nro_agente_ar,
         
         /* PROVEEDOR */
         REPLACE(pv.vendor_name, 'ART- ') nombre_pr,
         TRIM(pvs.address_line1 || ' ' || pvs.address_line2 || ' ' || pvs.address_line3) domicilio_l1_pr,
         pvs.city || ' - ' || pvs.zip || ' - ' || pvs.country domicilio_l2_pr, pv.vat_registration_num cuit_pr,
         
         /* RETENCION */
         jaac.check_number cheque, ac.check_id orden_pago, atc.description reg_impuesto,
         TO_CHAR(jaac.awt_date, 'DD/MM/YYYY') fecha_retencion, 
		 
		 TO_CHAR( ABS(jaac.taxable_base_amount) , '".DB_FORMATMONEY."') importe_sujeto_ret,
		 		 		 
         TO_CHAR( (jaac.withholding_amount *(-1) + NVL(jaac.credit_amount, 0)) , '".DB_FORMATMONEY."') importe_ret
		 
    FROM ap_checks_all@realfcl ac, jl_zz_ap_supp_awt_types@realfcl jsat, po_vendor_sites_all@realfcl pvs, po_vendors@realfcl pv, jl_zz_ap_awt_types@realfcl jzat,
         jl_zz_ap_comp_awt_types@realfcl jcat, hr_locations_all@realfcl hr, ap_tax_codes_all@realfcl atc, jl_ar_ap_awt_certif_all@realfcl jaac
   WHERE jaac.status <> 'VOID'
     AND atc.NAME = jaac.tax_name
     AND hr.location_id = jaac.location_id
     AND jcat.location_id = hr.location_id
     AND jaac.awt_type_code = jzat.awt_type_code
     AND jcat.awt_type_code = jzat.awt_type_code
     AND pvs.global_attribute17 = 'Y'
     AND pvs.vendor_id = pv.vendor_id
     AND jsat.awt_type_code = jzat.awt_type_code
     AND jsat.vendor_id = pv.vendor_id
     AND pv.vendor_id = jaac.vendor_id
     AND jaac.checkrun_name = ac.checkrun_name
     AND jaac.check_number = ac.check_number
     AND ac.check_id = :op
ORDER BY pv.vendor_name, jzat.awt_type_code, jaac.certificate_number";
$stmt = DBExecSql($conn, $sql, $params);

if (DBGetRecordCount($stmt) == 0) {
	echo "La consulta no devolvió datos.";
	exit;
}

$sql =
	"SELECT fi_firmante firmante, fi_caracter cargo
		 FROM cfi_firma
		WHERE fi_codusuario = 'LFOLINA'";
$stmt2 = DBExecSql($conn, $sql, array());
$rowFirmante = DBGetQuery($stmt2, 1, false);

$pdf = new FPDI();
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/varios/tesoreria/templates/certificado_retencion.pdf");

while ($row = DBGetQuery($stmt, 1, false)) {
	$pdf->AddPage();
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx);

	$pdf->SetFont("Arial", "B", 9);
	$pdf->Ln(31);
	$pdf->Cell(1.4);
	$pdf->Cell(0, 0, $row["TIPO_RETENCION"]);

	$pdf->SetFont("Arial", "", 9);
	$pdf->Ln(4);
	$pdf->Cell(28);
	$pdf->Cell(108, 0, $row["NRO_CERTIFICADO"]);

	$pdf->Ln(25.4);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["NOMBRE_AR"]);

	$pdf->Ln(6.4);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["DOMICILIO_L1_AR"]);

	$pdf->Ln(6.2);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["DOMICILIO_L2_AR"]);

	$pdf->Ln(6);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["CUIT_AR"]);

	$pdf->Ln(6.4);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["NRO_AGENTE_AR"]);

	$pdf->Ln(17.8);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["NOMBRE_PR"]);

	$pdf->Ln(6.4);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["DOMICILIO_L1_PR"]);

	$pdf->Ln(6);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["DOMICILIO_L2_PR"]);

	$pdf->Ln(6.2);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["CUIT_PR"]);

	$pdf->Ln(18);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["CHEQUE"]);

	$pdf->Ln(6.4);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["ORDEN_PAGO"]);

	$pdf->Ln(6.4);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["REG_IMPUESTO"]);

	$pdf->Ln(6.2);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, $row["FECHA_RETENCION"]);

	$pdf->Ln(5.8);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, trim($row["IMPORTE_SUJETO_RET"]));

	$pdf->Ln(5.8);
	$pdf->Cell(54);
	$pdf->Cell(140, 0, trim($row["IMPORTE_RET"]));

	$pdf->Ln(17.2);
	$pdf->Cell(40);
	$pdf->Cell(0, 0, $rowFirmante["FIRMANTE"]);

	$pdf->Ln(6.6);
	$pdf->Cell(40);
	$pdf->Cell(0, 0, $rowFirmante["CARGO"]);

	//JLOVATTO 2015-Marzo-05: se modifica la ruta absoluta de la imagen por esta ...
	$pdf->Image( $_SERVER["DOCUMENT_ROOT"]."/modules/varios/tesoreria/images/firma_lfolina.jpg", 160, 204, 19, 35); //76, 140
}

$pdf->Output();
?>