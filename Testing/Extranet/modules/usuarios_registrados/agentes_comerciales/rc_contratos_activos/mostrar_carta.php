<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion(($_SESSION["entidad"] != 400));
validarSesion((validarContrato($_REQUEST["c"])) or (($_SESSION["canal"] == 321) and ($_REQUEST["ec"] == 400)));
validarSesion(validarEntero($_REQUEST["c"]));


SetDateFormatOracle("DD/MM/YYYY");


$params = array(":contrato" => $_REQUEST["c"]);
$sql =
	"SELECT ac_codigo,
				  ac_descripcion,
				  TO_CHAR(art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), NVL(co_masatotalactual, co_masatotal), tc_porcmasa, tc_sumafija, zg_id, NULL, 250000, 0), '9,999,999,990.00') || '%' alicuota250,
				  TO_CHAR(art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), NVL(co_masatotalactual, co_masatotal), tc_porcmasa, tc_sumafija, zg_id, NULL, 500000, 0), '9,999,999,990.00') || '%' alicuota500,
				  TO_CHAR(art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), NVL(co_masatotalactual, co_masatotal), tc_porcmasa, tc_sumafija, zg_id, NULL, 1000000, 0), '9,999,999,990.00') || '%' alicuota1000,
				  art.utiles.armar_cuit(em_cuit) cuit,
				  TO_CHAR(NVL(co_masatotalactual, co_masatotal) * art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), NVL(co_masatotalactual, co_masatotal), tc_porcmasa, tc_sumafija, zg_id, NULL, 250000, 0) / 100, '$9,999,999,990.00') cuotainicialrc250,
				  TO_CHAR(NVL(co_masatotalactual, co_masatotal) * art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), NVL(co_masatotalactual, co_masatotal), tc_porcmasa, tc_sumafija, zg_id, NULL, 500000, 0) / 100, '$9,999,999,990.00') cuotainicialrc500,
				  TO_CHAR(NVL(co_masatotalactual, co_masatotal) * art.cotizacion.get_valor_rc(em_cuit, NVL(co_totempleadosactual, co_totempleados), NVL(co_masatotalactual, co_masatotal), tc_porcmasa, tc_sumafija, zg_id, NULL, 1000000, 0) / 100, '$9,999,999,990.00') cuotainicialrc1000,
				  em_nombre,
				  TO_CHAR(NVL(co_masatotalactual, co_masatotal), '$9,999,999,990.00') masasalarial,
				  art.utiles.armar_periodo(co_ultimoperiodocobranza) periodo,
				  TO_CHAR(".$_REQUEST["sa"]."000, '$9,999,999,990') sumaasegurada,
				  NVL(co_totempleadosactual, co_totempleados) totempleados
		FROM aco_contrato, aem_empresa, atc_tarifariocontrato, cac_actividad, adc_domiciliocontrato, afi.azg_zonasgeograficas
	 WHERE co_idempresa = em_id
		  AND co_contrato = tc_contrato
		  AND co_idactividad = ac_id
		  AND co_contrato = dc_contrato
		  AND dc_tipo = 'L'
		  AND dc_provincia = zg_idprovincia
		  AND co_contrato = :contrato";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt, 1, false);

$pdf = new FPDI();
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/agentes_comerciales/rc_contratos_activos/templates/cotizacion_responsabilidad_civil_patronal.pdf");

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "B", 9);

$pdf->Ln(36);
$pdf->Cell(20);
$pdf->Cell(0, 0, date("d/m/Y"));

$pdf->Ln(42);
$pdf->Cell(16);
$pdf->Cell(0, 0, $row["CUIT"]);

$pdf->Ln(5.2);
$pdf->Cell(32);
$pdf->Cell(160, 0, $row["EM_NOMBRE"]);

$pdf->Ln(5.7);
$pdf->Cell(108);
$pdf->Cell(0, 0, $row["AC_CODIGO"]);

$pdf->Ln(5.7);
$pdf->Cell(36);
$pdf->Cell(156, 0, $row["AC_DESCRIPCION"]);

$pdf->Ln(5.7);
$pdf->Cell(42);
$pdf->Cell(0, 0, $row["TOTEMPLEADOS"]);

$pdf->Ln(5.5);
$pdf->Cell(40);
$pdf->Cell(40, 0, $row["MASASALARIAL"]);

$pdf->Cell(24);
$pdf->Cell(0, 0, $row["PERIODO"]);

$pdf->Ln(23);
$pdf->Cell(28);
$pdf->Cell(24, 0, $row["ALICUOTA".$_REQUEST["sa"]], 0, 0, "R");

$pdf->Ln(5.7);
$pdf->Cell(28);
$pdf->Cell(24, 0, $row["CUOTAINICIALRC".$_REQUEST["sa"]], 0, 0, "R");

$pdf->Ln(5.7);
$pdf->Cell(28);
$pdf->Cell(24, 0, $row["SUMAASEGURADA"], 0, 0, "R");

$pdf->Ln(31.5);
$pdf->Cell(42);
$pdf->Cell(0, 0, $row["SUMAASEGURADA"]);

$pdf->Output();
?>