<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


function dibujarEstablecimiento() {
	global $pdf;
	global $rowEstablecimientos;

	$pdf->Ln(4);
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->Rect($pdf->GetX(), $pdf->GetY(), 196.6, 5.4, "F");

	$pdf->Ln(1.8);
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->Rect($pdf->GetX(), $pdf->GetY(), 196.6, 28, "D");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Ln(1);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->Cell(0.8);
	$pdf->Cell(104, 0, "ESTABLECIMIENTO ".$rowEstablecimientos["SE_NROESTABLECI"]);
	$pdf->SetTextColor(0, 0, 0);

	// Linea 1..
	$pdf->SetFont("Arial", "", 8);
	$pdf->Ln(6);
	$pdf->Cell(0.8);
	$pdf->Cell(164, 0, "Código de actividad según CLasificador de Actividades Económicas (CLAE) - Formulario Nº 883 (Resolución A.F.I.P. Nº 3537)");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(0, 0, $rowEstablecimientos["AC_CODIGO"]);

	$pdf->Ln(1.8);
	$pdf->Line($pdf->GetX() + 1.4, $pdf->GetY(), $pdf->GetX() + 195.2, $pdf->GetY());

	// Linea 2..
	$pdf->SetFont("Arial", "", 8);
	$pdf->Ln(3);
	$pdf->Cell(0.8);
	$pdf->Cell(44, 0, "Breve descripción de la actividad");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(148, 0, $rowEstablecimientos["AC_DESCRIPCION"]);

	$pdf->Ln(1.8);
	$pdf->Line($pdf->GetX() + 1.4, $pdf->GetY(), $pdf->GetX() + 195.2, $pdf->GetY());

	// Linea 3..
	$pdf->SetFont("Arial", "", 8);
	$pdf->Ln(3);
	$pdf->Cell(0.8);
	$pdf->Cell(40, 0, "Ubicación / domicilio completo");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(152, 0, $rowEstablecimientos["DOMICILIO"]);

	$pdf->Ln(1.8);
	$pdf->Line($pdf->GetX() + 1.4, $pdf->GetY(), $pdf->GetX() + 195.2, $pdf->GetY());

	// Linea 4..
	$pdf->SetFont("Arial", "", 8);
	$pdf->Ln(3);
	$pdf->Cell(0.8);
	$pdf->Cell(14, 0, "Localidad");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(42, 0, $rowEstablecimientos["SE_LOCALIDAD"]);

	$pdf->Cell(0.8);
	$pdf->Cell(14, 0, "Provincia");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(38, 0, $rowEstablecimientos["PROVINCIA"]);

	$pdf->Cell(0.8);
	$pdf->Cell(20, 0, "Código postal");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(16, 0, $rowEstablecimientos["SE_CPOSTAL"]);

	$pdf->Cell(0.8);
	$pdf->Cell(14, 0, "Teléfono");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(32, 0, $rowEstablecimientos["TELEFONO"]);

	$pdf->Ln(1.8);
	$pdf->Line($pdf->GetX() + 1.4, $pdf->GetY(), $pdf->GetX() + 195.2, $pdf->GetY());

	// Linea 5..
	$pdf->SetFont("Arial", "", 8);
	$pdf->Ln(3);
	$pdf->Cell(0.8);
	$pdf->Cell(11, 0, "e-Mail");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(84, 0, $rowEstablecimientos["EMAIL"]);

	$pdf->Cell(0.8);
	$pdf->Cell(38, 0, "Cantidad de trabajadores");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(16, 0, $rowEstablecimientos["SE_EMPLEADOS"]);

	$pdf->Cell(0.8);
	$pdf->Cell(20, 0, "Masa salarial");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(22, 0, $rowEstablecimientos["MASASALARIAL"]);
}

function ocultarParteInferior() {
	global $pdf;

	$pdf->SetX(1);
	$pdf->SetY(256);

	$pdf->Rect($pdf->GetX(), $pdf->GetY(), 196, 8, "F");
}

function ocultarParteSuperior() {
	global $pdf;

	$pdf->SetX(1);
	$pdf->SetY(26);

	$pdf->SetFillColor(255, 255, 255);
	$pdf->Rect($pdf->GetX(), $pdf->GetY(), 198, 60, "F");
}

function setNumeroSolicitud($cuit, $numeroFormulario) {
	global $pdf;

	$pdf->SetY(20.8);
	$pdf->SetX(109);

	$pdf->SetFont("Arial", "", 10);
	$pdf->Cell(0, 0, "00051-".$cuit."-".$numeroFormulario);
}


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idmodulo"]);


SetDateFormatOracle("DD/MM/YYYY");

if (!isset($_REQUEST["ap"]))
	$autoPrint = false;
else
	$autoPrint = ($_REQUEST["ap"] == "t");

$id = substr($_REQUEST["idmodulo"], 1);
$modulo = substr($_REQUEST["idmodulo"], 0, 1);

if ($modulo == "R") {
	$params = array(":id" => $id);
	$sql = "SELECT sr_idformulario FROM asr_solicitudreafiliacion WHERE sr_id = :id";
	$idFormulario = ValorSql($sql, 0, $params);
}
else {
	$params = array(":id" => $id);
	$sql = "SELECT sc_idformulario FROM asc_solicitudcotizacion WHERE sc_id = :id";
	$idFormulario = ValorSql($sql, 0, $params);
}

$params = array(":idformulario" => $idFormulario);
$sql =
	"SELECT ac_codigo,
					ac_descripcion,
					sa_cuit cuit,
					fo_formulario,
					pv_descripcion provincia,
					sa_nombre razonsocial,
					sa_calle,
					sa_cpostala,
					sa_departamento,
					sa_establecimientos,
					sa_id,
					sa_localidad,
					sa_mail_legal,
					sa_numero,
					sa_piso,
					sa_telefonos
		 FROM asa_solicitudafiliacion, afo_formulario, cac_actividad, cpv_provincias
		WHERE sa_idformulario = fo_id
			AND sa_idactividad = ac_id
			AND sa_provincia = pv_codigo(+)
			AND sa_idformulario = :idformulario";
$stmt = DBExecSql($conn, $sql, $params);
$row2 = DBGetQuery($stmt, 1, false);

if ($autoPrint)
	$pdf = new PDF_AutoPrint();
else
	$pdf = new FPDI();

$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/templates/ubicacion_riesgo.pdf");

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);

setNumeroSolicitud($row2["CUIT"], $row2["FO_FORMULARIO"]);

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(15.6);
$pdf->Cell(31);
$pdf->Cell(110, 0, $row2["RAZONSOCIAL"]);

$pdf->Cell(16);
$pdf->Cell(0, 0, ponerGuiones($row2["CUIT"]));

$pdf->Ln(4.4);
$pdf->Cell(28);
$pdf->Cell(168, 0, $row2["AC_DESCRIPCION"]);

$pdf->Ln(4);
$pdf->Cell(128);
$pdf->Cell(16, 0, $row2["AC_CODIGO"]);

$pdf->Cell(44);
$pdf->Cell(12, 0, $row2["SA_ESTABLECIMIENTOS"]);

$pdf->Ln(4);
$pdf->Cell(45);
$pdf->Cell(68, 0, $row2["SA_CALLE"]);

$pdf->Cell(8);
$pdf->Cell(16, 0, $row2["SA_NUMERO"]);

$pdf->Cell(12);
$pdf->Cell(16, 0, $row2["SA_PISO"]);

$pdf->Cell(12);
$pdf->Cell(16, 0, $row2["SA_DEPARTAMENTO"]);

$pdf->Ln(4.2);
$pdf->Cell(16);
$pdf->Cell(72, 0, $row2["SA_LOCALIDAD"]);

$pdf->Cell(16);
$pdf->Cell(34, 0, $row2["PROVINCIA"]);

$pdf->Cell(35);
$pdf->Cell(22, 0, $row2["SA_CPOSTALA"]);

$pdf->Ln(4.2);
$pdf->Cell(15);
$pdf->Cell(72, 0, $row2["SA_TELEFONOS"]);

$pdf->Cell(29);
$pdf->Cell(78, 0, $row2["SA_MAIL_LEGAL"]);


// Muestro los establecimientos..
$params = array(":idsolicitud" => $row2["SA_ID"]);
$sql =
	"SELECT ac_codigo,
					ac_descripcion,
					art.utiles.armar_domicilio(se_calle, se_numero, se_piso, se_departamento, NULL) domicilio,
					NULL email,
					TO_CHAR(se_masa, '$9,999,999,990.00') masasalarial,
					pv_descripcion provincia,
					se_cpostal,
					se_empleados,
					se_localidad,
					se_nroestableci,
					art.afi.get_telefonos('asf_solicitudtelefonoestableci', se_id, NULL, NULL) telefono
		 FROM ase_solicitudestablecimiento, cac_actividad, cpv_provincias
		WHERE se_idactividad = ac_id
			AND se_provincia = pv_codigo
			AND se_fechabaja IS NULL
			AND se_idsolicitud = :idsolicitud
 ORDER BY se_nroestableci";
$stmt = DBExecSql($conn, $sql, $params);

$i = 8;
$pdf->Ln(26);
while ($rowEstablecimientos = DBGetQuery($stmt, 1, false)) {
	if (($i%7) == 6) {
		ocultarParteInferior();

		$pdf->AddPage();
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx);

		ocultarParteSuperior();
		setNumeroSolicitud($row2["CUIT"], $row2["FO_FORMULARIO"]);
		$pdf->Ln(2);
	}

	dibujarEstablecimiento();

	$i++;
}


if ($autoPrint)
	$pdf->AutoPrint(false);
$pdf->Output();
?>