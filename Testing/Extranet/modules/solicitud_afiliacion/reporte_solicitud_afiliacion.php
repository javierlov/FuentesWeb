<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


function setNumeroSolicitud($cuit, $numeroFormulario) {
	return "Nº 00051-".$cuit."-".$numeroFormulario;
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

$sqlRevisionYAfiliacion =
	"SELECT cac1.ac_descripcion,
					TO_CHAR(sa_fechaafiliacion, 'yyyy') anosuscripcion,
					ar_nombre,
					cargo.tb_descripcion cargo,
					cargo2.tb_descripcion cargo2,
					cac1.ac_codigo codigoactividad1,
					cac2.ac_codigo codigoactividad2,
					cac3.ac_codigo codigoactividad3,
					NVL(sa_condicionanteafip, 'Empleador') condicionanteafip,
					NVL(fo_cuit, uw_cuitsuscripcion) cuitsuscripcion,
					DECODE(NVL(sr_canttrabajadores, 0), 0, sa_totempleados, sr_canttrabajadores) * sr_costofinalcotizado cuotamensual,
					TO_CHAR(sa_fechaafiliacion, 'dd') diasuscripcion,
					sa_mail_legal email,
					(SELECT en_codbanco
						 FROM xen_entidad
						WHERE NVL(ev_identidad, uw_identidad) = en_id) entidad,
					NVL(sa_establecimientos, sr_establecimientos) establecimientos,
					TO_CHAR(sr_fechanotificacioncomercial + CASE WHEN ca_tipo = 'B' THEN 60 ELSE 30 END, 'DD/MON/YYYY') fecha,
					fo_formulario,
					fjuri.tb_descripcion formajuridica,
					TO_CHAR(NVL(sa_masatotal, sr_masasalarial), '$9,999,999,990.00') masasalarial,
					art.utiles.nombredemes(TO_NUMBER(TO_CHAR(sa_fechaafiliacion, 'mm'))) messuscripcion,
					NVL(sa_nombre_vendedor, ve_nombre) nombrecomercializador,
					NVL(sa_periodo, sr_periodo) periodo,
					pv_descripcion,
					NVL(sa_nombre, em_nombre) sc_razonsocial,
					sa_calle,
					sa_cargo_titular,
					sa_clausulasadicionales,
					sa_contacto,
					sa_cpostal,
					sa_departamento,
					sa_direlectronica_cont,
					sa_documento_titular,
					sa_fechaafiliacion,
					TO_CHAR(sa_fechavigenciadesde, 'DD/MON/YYYY') sa_fechavigenciadesde,
					TO_CHAR(sa_fechavigenciahasta, 'DD/MON/YYYY') sa_fechavigenciahasta,
					sa_feinicactiv,
					sa_id,
					sa_localidad,
					sa_lugarsuscripcion,
					sa_motivoalta,
					sa_nivel,
					sa_numero,
					sa_observaciones,
					sa_piso,
					sa_presentorgrl,
					sa_provincia,
					sa_telefonos_cont,
					sa_titular,
					sr_idcanal sc_canal,
					NVL(sa_totempleados, sr_canttrabajadores) sc_canttrabajador,
					sr_cuit sc_cuit,
					sr_identidad sc_identidad,
					sr_idsucursal sc_idsucursal,
					sr_idvendedor sc_idvendedor,
					sr_nrosolicitud sc_nrosolicitud,
					sr_statussrt sc_statussrt,
					TO_CHAR(sr_costofijocotizado - 0.6, '$9,990.00') sr_costofijocotizado,
					sr_porcentajevariablecotizado,
					CASE
						WHEN (SELECT en_codbanco
										FROM xen_entidad
									 WHERE NVL(ev_identidad, uw_identidad) = en_id) IN (400, 9003)
						THEN DECODE(su_codsucursal, NULL, NULL, '(' || su_codsucursal || ') ') || su_descripcion
						ELSE su_descripcion
					END su_descripcion,
					NVL(art.afi.get_telefonos('ATS_TELEFONOSOLICITUD', sa_id, 'L'), sr_telefono) telefono,
					sa_nombre_vendedor vendedor,
					ve_vendedor
		 FROM asr_solicitudreafiliacion, asa_solicitudafiliacion, afo_formulario, aem_empresa, cac_actividad cac1,
					cac_actividad cac2, cac_actividad cac3, xev_entidadvendedor, xve_vendedor, asu_sucursal, cpv_provincias,
					ctb_tablas cargo, afi.auw_usuarioweb, ctb_tablas fjuri, ctb_tablas cargo2, aar_art, aca_canal
		WHERE sr_idformulario = sa_idformulario(+)
			AND sr_idformulario = fo_id(+)
			AND sr_cuit = em_cuit
			AND sa_idactividad = cac1.ac_id
			AND sa_idactividad2 = cac2.ac_id(+)
			AND sa_idactividad3 = cac3.ac_id(+)
			AND sa_identidadvendedor = ev_id(+)
			AND ev_idvendedor = ve_id(+)
			AND sa_idsucursal = su_id(+)
			AND sa_provincia = pv_codigo(+)
			AND sa_cargo_titular = cargo.tb_codigo(+)
			AND cargo.tb_clave(+) = 'CARGO'
			AND cargo.tb_especial2(+) = 'SOLO_FIRMANTE'
			AND cargo.tb_fechabaja(+) IS NULL
			AND sa_formaj = fjuri.tb_codigo(+)
			AND fjuri.tb_clave(+) = 'FJURI'
			AND fjuri.tb_fechabaja(+) IS NULL
			AND sa_cargo = cargo2.tb_codigo(+)
			AND cargo2.tb_clave(+) = 'CARGO'
			AND cargo2.tb_fechabaja(+) IS NULL
			AND sr_usualta = uw_usuario(+)
			AND sr_idartanterior = ar_id(+)
			AND sr_idcanal = ca_id(+)
			AND sr_id = :id";

$sqlSolicitudYAfiliacion =
	"SELECT cac1.ac_descripcion,
					TO_CHAR(sa_fechaafiliacion, 'yyyy') anosuscripcion,
					ar_nombre,
					cargo.tb_descripcion cargo,
					cargo2.tb_descripcion cargo2,
					cac1.ac_codigo codigoactividad1,
					cac2.ac_codigo codigoactividad2,
					cac3.ac_codigo codigoactividad3,
					NVL(sa_condicionanteafip, 'Empleador') condicionanteafip,
					NVL(fo_cuit, uw_cuitsuscripcion) cuitsuscripcion,
					TO_CHAR(sa_fechaafiliacion, 'dd') diasuscripcion,
					sa_mail_legal email,
					(SELECT en_codbanco
						 FROM xen_entidad
						WHERE NVL(ev_identidad, uw_identidad) = en_id) entidad,
					NVL(sa_establecimientos, sc_establecimientos) establecimientos,
					TO_CHAR(sc_fechavigencia + CASE WHEN ca_tipo = 'B' THEN 60 ELSE 30 END, 'DD/MON/YYYY') fecha,
					fo_formulario,
					fjuri.tb_descripcion formajuridica,
					TO_CHAR(NVL(sa_masatotal, sc_masasalarial), '$9,999,999,990.00') masasalarial,
					art.utiles.nombredemes(TO_NUMBER(TO_CHAR(sa_fechaafiliacion, 'mm'))) messuscripcion,
					NVL(sa_nombre_vendedor, ve_nombre) nombrecomercializador,
					NVL(sa_periodo, sc_periodo) periodo,
					pv_descripcion,
					NVL(sa_nombre, NVL(co_razonsocial, sc_razonsocial)) sc_razonsocial,
					sa_calle,
					sa_cargo_titular,
					sa_clausulasadicionales,
					sa_contacto,
					sa_cpostal,
					sa_departamento,
					sa_direlectronica_cont,
					sa_documento_titular,
					sa_fechaafiliacion,
					TO_CHAR(sa_fechavigenciadesde, 'DD/MON/YYYY') sa_fechavigenciadesde,
					TO_CHAR(sa_fechavigenciahasta, 'DD/MON/YYYY') sa_fechavigenciahasta,
					sa_feinicactiv,
					sa_id,
					sa_localidad,
					sa_lugarsuscripcion,
					sa_motivoalta,
					sa_nivel,
					sa_numero,
					sa_observaciones,
					sa_piso,
					sa_presentorgrl,
					sa_provincia,
					sa_telefonos_cont,
					sa_titular,
					sc_canal,
					NVL(sa_totempleados, sc_canttrabajador) sc_canttrabajador,
					sc_cuit,
					sc_identidad,
					sc_idsucursal,
					sc_idvendedor,
					sc_nrosolicitud,
					sc_statussrt,
					CASE
						WHEN (SELECT en_codbanco
										FROM xen_entidad
									 WHERE NVL(ev_identidad, uw_identidad) = en_id) IN (400, 9003)
						THEN DECODE(su_codsucursal, NULL, NULL, '(' || su_codsucursal || ') ') || su_descripcion
						ELSE su_descripcion
					END su_descripcion,
					NVL(art.afi.get_telefonos('ATS_TELEFONOSOLICITUD', sa_id, 'L'), sc_telefono) telefono,
					sa_nombre_vendedor vendedor,
					ve_vendedor
		 FROM asc_solicitudcotizacion, asa_solicitudafiliacion, aco_cotizacion, afo_formulario, cac_actividad cac1,
					cac_actividad cac2, cac_actividad cac3, xev_entidadvendedor, xve_vendedor, asu_sucursal, cpv_provincias,
					ctb_tablas cargo, afi.auw_usuarioweb, ctb_tablas fjuri, ctb_tablas cargo2, aar_art, aca_canal
		WHERE sc_idformulario = sa_idformulario(+)
			AND sc_idformulario = fo_id (+)
			AND sc_idcotizacion = co_id(+)
			AND sa_idactividad = cac1.ac_id
			AND sa_idactividad2 = cac2.ac_id(+)
			AND sa_idactividad3 = cac3.ac_id(+)
			AND sa_identidadvendedor = ev_id(+)
			AND ev_idvendedor = ve_id(+)
			AND sa_idsucursal = su_id(+)
			AND sa_provincia = pv_codigo(+)
			AND sa_cargo_titular = cargo.tb_codigo(+)
			AND cargo.tb_clave(+) = 'CARGO'
			AND cargo.tb_especial2(+) = 'SOLO_FIRMANTE'
			AND cargo.tb_fechabaja(+) IS NULL
			AND sa_formaj = fjuri.tb_codigo(+)
			AND fjuri.tb_clave(+) = 'FJURI'
			AND fjuri.tb_fechabaja(+) IS NULL
			AND sa_cargo = cargo2.tb_codigo(+)
			AND cargo2.tb_clave(+) = 'CARGO'
			AND cargo2.tb_fechabaja(+) IS NULL
			AND sc_usuariosolicitud = uw_usuario(+)
			AND sc_idartanterior = ar_id(+)
			AND sc_canal = ca_id(+)
			AND sc_id = :id";

$params = array(":id" => $id);
$sql = (($modulo == "R")?$sqlRevisionYAfiliacion:$sqlSolicitudYAfiliacion);
$stmt = DBExecSql($conn, $sql, $params);
$row2 = DBGetQuery($stmt, 1, false);

if ($row2["PERIODO"] != "")
	$row2["PERIODO"] = substr($row2["PERIODO"], 0, 4)."/".substr($row2["PERIODO"], 4, 2);

if ($modulo == "R") {
	$cuotaInicial = $row2["CUOTAMENSUAL"];
	$porcentajeVariable = $row2["SR_PORCENTAJEVARIABLECOTIZADO"];
	$sumaFija = $row2["SR_COSTOFIJOCOTIZADO"];
}
else {
	$params = array(":id" => $id);
	$sql = "SELECT sc_nrosolicitud FROM asc_solicitudcotizacion WHERE sc_id = :id";
	$numeroSolicitud = ValorSql($sql, 0, $params);

	$curs = null;
	$params = array(":nrosolicitud" => $numeroSolicitud);
	$sql = "BEGIN art.cotizacion.get_valor_carta(:nrosolicitud, :data); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$rowValorFinal = DBGetSP($curs, false);

	$cuotaInicial = $rowValorFinal["COSTOMENSUAL"];
	$porcentajeVariable = $rowValorFinal["PORCVARIABLE"];
	$sumaFija = $rowValorFinal["SUMAFIJA"];
}

if ($autoPrint)
	$pdf = new PDF_AutoPrint();
else
	$pdf = new FPDI();

$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/templates/solicitud_afiliacion.pdf");

//Página 1..
$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "", 8);

if (($row2["SA_MOTIVOALTA"] == "03") or ($row2["SA_MOTIVOALTA"] == "04") or ($row2["SA_MOTIVOALTA"] == "05")) {
	$pdf->Ln(6);
	$pdf->Cell(-0.4);
	$pdf->Cell(0, 0, "X");

	$pdf->Ln(8.2);
	$pdf->Cell(1);
}
else {
	$pdf->Ln(10.2);
	$pdf->Cell(-0.4);
	$pdf->Cell(0, 0, "X");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Ln(4);
	$pdf->Cell(1);
	$pdf->Cell(45, 0, $row2["AR_NOMBRE"]);
}

$pdf->SetFont("Arial", "", 10);
$pdf->Ln(1);
$pdf->Cell(196, 0, setNumeroSolicitud($row2["CUITSUSCRIPCION"], $row2["FO_FORMULARIO"]), 0, 0, "C");

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(39.6);
$pdf->Cell(30);
$pdf->Cell(164, 0, $row2["SC_RAZONSOCIAL"]);

$pdf->Ln(4.4);
$pdf->Cell(16);
$pdf->Cell(40, 0, ponerGuiones($row2["SC_CUIT"]));

$pdf->Cell(28);
$pdf->Cell(42, 0, $row2["FORMAJURIDICA"]);

$pdf->Cell(35);
$pdf->Cell(34, 0, $row2["CONDICIONANTEAFIP"]);

$pdf->Ln(4);
$pdf->Cell(26);
$pdf->Cell(104, 0, $row2["AC_DESCRIPCION"]);

$pdf->Cell(42);
$pdf->Cell(0, 0, $row2["SA_FEINICACTIV"]);

$pdf->Ln(8.6);
$pdf->Cell(8);
$pdf->Cell(32, 0, $row2["CODIGOACTIVIDAD1"]);

$pdf->Cell(16);
$pdf->Cell(138, 0, $row2["AC_DESCRIPCION"]);

$otrasActividades = $row2["CODIGOACTIVIDAD2"];
if ($row2["CODIGOACTIVIDAD3"] != "")
	if ($otrasActividades == "")
		$otrasActividades = $row2["CODIGOACTIVIDAD3"];
	else
		$otrasActividades.= " ,  ".$row2["CODIGOACTIVIDAD3"];
$pdf->Ln(4.4);
$pdf->Cell(40);
$pdf->Cell(0, 0, $otrasActividades);

$pdf->Ln(8.4);
$pdf->Cell(8);
$pdf->Cell(130, 0, $row2["SA_CALLE"]);

$pdf->Cell(6);
$pdf->Cell(14, 0, $row2["SA_NUMERO"]);

$pdf->Cell(7);
$pdf->Cell(10, 0, $row2["SA_PISO"]);

$pdf->Cell(10);
$pdf->Cell(10, 0, $row2["SA_DEPARTAMENTO"]);

$pdf->Ln(4);
$pdf->Cell(13);
$pdf->Cell(56, 0, $row2["SA_LOCALIDAD"]);

$pdf->Cell(16);
$pdf->Cell(55, 0, $row2["PV_DESCRIPCION"]);

$pdf->Cell(35);
$pdf->Cell(20, 0, $row2["SA_CPOSTAL"]);

$pdf->Ln(4.4);
$pdf->Cell(9);
$pdf->Cell(128, 0, $row2["EMAIL"]);

$pdf->Cell(14);
$pdf->Cell(41, 0, $row2["TELEFONO"]);

$pdf->Ln(4.4);
$pdf->Cell(40);
$pdf->Cell(16, 0, $row2["ESTABLECIMIENTOS"]);


$pdf->Ln(2.4);
$pdf->SetFont("Arial", "", 7);
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(0, 135, 196);
switch ($row2["SA_NIVEL"]) {
	case 1:
		$pdf->Cell(66);
		$pdf->Rect($pdf->GetX(), $pdf->GetY(), 4.2, 3.4, "F");
		$pdf->Ln(2);
		$pdf->Cell(66.6);
		$pdf->Cell(0, 0, "I");
		$pdf->Ln(-2);
		break;
	case 2:
		$pdf->Cell(72.4);
		$pdf->Rect($pdf->GetX(), $pdf->GetY(), 4, 3.4, "F");
		$pdf->Ln(2);
		$pdf->Cell(72.6);
		$pdf->Cell(0, 0, "II");
		$pdf->Ln(-2);
		break;
	case 3:
		$pdf->Cell(78.6);
		$pdf->Rect($pdf->GetX(), $pdf->GetY(), 4.2, 3.4, "F");
		$pdf->Ln(2);
		$pdf->Cell(78.8);
		$pdf->Cell(0, 0, "III");
		$pdf->Ln(-2);
		break;
	case 4:
		$pdf->Cell(85);
		$pdf->Rect($pdf->GetX(), $pdf->GetY(), 4.2, 3.4, "F");
		$pdf->Ln(2);
		$pdf->Cell(85);
		$pdf->Cell(0, 0, "IV");
		$pdf->Ln(-2);
		break;
}
$pdf->SetDrawColor(0, 0, 0);


$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(6);
$pdf->Cell(34);
$pdf->Cell(160, 0, $row2["SA_CONTACTO"]);

$pdf->Ln(4);
$pdf->Cell(10);
$pdf->Cell(42, 0, $row2["CARGO2"]);

$pdf->Cell(18);
$pdf->Cell(40, 0, $row2["SA_TELEFONOS_CONT"]);

$pdf->Cell(12);
$pdf->Cell(72, 0, $row2["SA_DIRELECTRONICA_CONT"]);

$pdf->Ln(16);
$pdf->Cell(16);
$pdf->Cell(24, 0, $row2["SA_FECHAVIGENCIADESDE"]);

$pdf->Cell(20);
$pdf->Cell(24, 0, $row2["SA_FECHAVIGENCIAHASTA"]);

$pdf->Ln(7.4);
if ($row2["SA_PRESENTORGRL"] == "T") {
	$pdf->Cell(126.4);
	$pdf->Cell(0, 0, "X");
}
else {
	$pdf->Cell(137.6);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(4);
if ($row2["SA_CLAUSULASADICIONALES"] == "S") {
	$pdf->Cell(126.4);
	$pdf->Cell(0, 0, "X");
}
else {
	$pdf->Cell(137.6);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(28);
$pdf->Cell(0.2);
$pdf->Cell(20, 0, $row2["SC_CANTTRABAJADOR"], 0, 0, "C");

$pdf->Cell(0.4);
$pdf->Cell(32.4, 0, $row2["MASASALARIAL"], 0, 0, "C");

$pdf->Cell(0.4);
$pdf->Cell(20, 0, $row2["PERIODO"], 0, 0, "C");

if (substr($porcentajeVariable, 0, 1) == ",")
	$porcentajeVariable = "0".$porcentajeVariable;
$pdf->Cell(2.6);
$pdf->Cell(39, 0, $porcentajeVariable, 0, 0, "C");

$pdf->Cell(21, 0, $sumaFija, 0, 0, "C");

$pdf->Cell(13.4, 0, "$ 0,60", 0, 0, "C");

$pdf->Cell(47, 0, $cuotaInicial, 0, 0, "C");

$pdf->SetFont("Arial", "BU", 7);
$pdf->Ln(12.6);
$pdf->Cell(140.8);
$pdf->Cell(0, 0, $row2["FECHA"]);


$pdf->SetFont("Arial", "", 9);
if (($modulo == "C") and (($id == 311053) or ($id == 311228) or ($id == 311229))) {		// Pedido por EVila por e-mail del 6.6.2012..
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->Rect(112, 206, 40, 6, "F");

	$pdf->Ln(25.2);
	$pdf->Cell(102);
	$pdf->Cell(0, 0, "$2.500.- (dos mil quinientos pesos)");
}
elseif (($modulo == "C") and (($id == 328723) or ($id == 334890))) {		// Harcodeado por ticket 41756..
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->Rect(112, 206, 40, 6, "F");

	$pdf->Ln(25.2);
	$pdf->Cell(102);
	$pdf->Cell(0, 0, "$2.000.- (dos mil)");
}
else {
	$pdf->Ln(25.2);
}


$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(11.8);
$pdf->Cell(20);
$texto = $row2["SA_OBSERVACIONES"];
$pdf->WordWrap($texto, 176);
$texto = explode("\n", $texto);
for ($i=0; $i<count($texto); $i++) {
	if ($i > 1)
		break;

	$str = trim($texto[$i]);

	$pdf->Cell(1);
	$pdf->Cell(0, 0, $str);
	$pdf->Ln(4.2);
}

$pdf->SetY(235.4);
$pdf->SetX(76);
$pdf->Cell(44, 0, $row2["SA_LUGARSUSCRIPCION"]);

$pdf->Cell(10);
$pdf->Cell(8, 0, $row2["DIASUSCRIPCION"]);

$pdf->Cell(22);
$pdf->Cell(27, 0, $row2["MESSUSCRIPCION"], 0, 0, "C");

$pdf->Cell(6);
$pdf->Cell(10, 0, $row2["ANOSUSCRIPCION"]);

$pdf->Ln(9.4);
$pdf->Cell(24);
$pdf->Cell(71, 0, $row2["NOMBRECOMERCIALIZADOR"]);

$pdf->Cell(28);
$pdf->Cell(72, 0, $row2["SA_TITULAR"]);

$pdf->Ln(4.2);
$pdf->Cell(10);
$pdf->Cell(37, 0, $row2["ENTIDAD"]);

$pdf->Cell(15);
$pdf->Cell(33, 0, $row2["VE_VENDEDOR"]);

$pdf->Cell(27);
$pdf->Cell(32, 0, $row2["CARGO"]);

$pdf->Cell(10);
$pdf->Cell(28, 0, $row2["SA_DOCUMENTO_TITULAR"]);

$pdf->Ln(4.2);
$pdf->Cell(12);
$pdf->Cell(84, 0, $row2["SU_DESCRIPCION"]);

$pdf->Ln(11);
$pdf->Cell(14);
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect($pdf->GetX(), $pdf->GetY(), 176, 4.4, "F");

$pdf->SetFont("Arial", "", 8);
$pdf->Ln(1);
$pdf->Cell(14);
$pdf->Cell(84, 0, "Firma y aclaración comercializador Provincia ART");
$pdf->Cell(26);
$pdf->Cell(0, 0, "Firma y aclaración empleador");


//Página 2..
$pdf->AddPage();
$tplIdx = $pdf->importPage(2);
$pdf->useTemplate($tplIdx);

$pdf->SetFont("Arial", "", 10);
$pdf->Ln(15);
$pdf->Cell(196, 0, setNumeroSolicitud($row2["CUITSUSCRIPCION"], $row2["FO_FORMULARIO"]), 0, 0, "C");

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(240.4);
$pdf->Cell(144);
$pdf->Cell(0, 0, ponerGuiones($row2["SC_CUIT"]));


//Página 3..
$pdf->AddPage();
$tplIdx = $pdf->importPage(3);
$pdf->useTemplate($tplIdx);

$pdf->SetFont("Arial", "", 10);
$pdf->Ln(15);
$pdf->Cell(196, 0, setNumeroSolicitud($row2["CUITSUSCRIPCION"], $row2["FO_FORMULARIO"]), 0, 0, "C");

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(177.6);
$pdf->Cell(148);
$pdf->Cell(0, 0, ponerGuiones($row2["SC_CUIT"]));


if ($autoPrint)
	$pdf->AutoPrint(false);
$pdf->Output();
?>