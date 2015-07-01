<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


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
					art.afiliacion.get_clausulapenal clausulapenal,
					cac1.ac_codigo codigoactividad1,
					cac2.ac_codigo codigoactividad2,
					cac3.ac_codigo codigoactividad3,
					NVL(sa_cpostala, sa_cpostal) codigopostal,
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
					TO_CHAR(sa_fechavigenciadesde + 30, 'DD/MON/YYYY') limitepresentacion,
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
					art.afiliacion.get_clausulapenal clausulapenal,
					cac1.ac_codigo codigoactividad1,
					cac2.ac_codigo codigoactividad2,
					cac3.ac_codigo codigoactividad3,
					NVL(sa_cpostala, sa_cpostal) codigopostal,
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
					TO_CHAR(sa_fechavigenciadesde + 30, 'DD/MON/YYYY') limitepresentacion,
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
	$pdf = new PDF_AutoPrint("P", "mm", array(216, 280));
else
	$pdf = new FPDI("P", "mm", array(216, 280));


$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/templates/solicitud_afiliacion_pcp.pdf");

//Página 1..
$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "", 8);

if (($row2["SA_MOTIVOALTA"] == "03") or ($row2["SA_MOTIVOALTA"] == "04") or ($row2["SA_MOTIVOALTA"] == "05")) {
	$pdf->Ln(-1.6);
	$pdf->Cell(-4.8);
	$pdf->Cell(0, 0, "X");

	$pdf->Ln(8.2);
	$pdf->Cell(-5);
}
else {
	$pdf->Ln(2.6);
	$pdf->Cell(-4.8);
	$pdf->Cell(0, 0, "X");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Ln(4);
	$pdf->Cell(-5);
	$pdf->Cell(48, 0, $row2["AR_NOMBRE"]);
}

// Tapo el N° de solicitud que está en el pdf original..
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(80, 19, 48, 4, "F");
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);

$pdf->SetFont("Arial", "", 10);
$pdf->Ln(4.4);
$pdf->Cell(190, 0, setNumeroSolicitud($row2["CUITSUSCRIPCION"], $row2["FO_FORMULARIO"]), 0, 0, "C");

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(38.6);
$pdf->Cell(24);
$pdf->Cell(176, 0, $row2["SC_RAZONSOCIAL"]);

$pdf->Ln(5);
$pdf->Cell(10);
$pdf->Cell(48, 0, ponerGuiones($row2["SC_CUIT"]));

$pdf->Cell(126);
$pdf->Cell(0, 0, $row2["SA_FEINICACTIV"]);

$pdf->Ln(5);
$pdf->Cell(4);
$pdf->Cell(20, 0, $row2["CODIGOACTIVIDAD1"]);

$pdf->Cell(4);
$pdf->Cell(172, 0, $row2["AC_DESCRIPCION"]);

$pdf->Ln(10);
$pdf->Cell(2);
$pdf->Cell(144, 0, $row2["SA_CALLE"]);

$pdf->Cell(6);
$pdf->Cell(14, 0, $row2["SA_NUMERO"]);

$pdf->Cell(9);
$pdf->Cell(8, 0, $row2["SA_PISO"]);

$pdf->Cell(11);
$pdf->Cell(8, 0, $row2["SA_DEPARTAMENTO"]);

$pdf->Ln(4.8);
$pdf->Cell(7);
$pdf->Cell(58, 0, $row2["SA_LOCALIDAD"]);

$pdf->Cell(16);
$pdf->Cell(50, 0, $row2["PV_DESCRIPCION"]);

$pdf->Cell(35);
$pdf->Cell(32, 0, $row2["CODIGOPOSTAL"]);

$pdf->Ln(5);
$pdf->Cell(6);
$pdf->Cell(124, 0, $row2["EMAIL"]);

$pdf->Cell(24);
$pdf->Cell(44, 0, $row2["TELEFONO"]);

// Tapo las barras que están en el pdf original..
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(20, 102, 40, 4, "F");
$pdf->Rect(76, 102, 24, 4, "F");
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);

$pdf->Ln(15);
$pdf->Cell(11);
$pdf->Cell(24, 0, $row2["SA_FECHAVIGENCIADESDE"]);

$pdf->Cell(34);
$pdf->Cell(24, 0, $row2["SA_FECHAVIGENCIAHASTA"]);

$pdf->Ln(15.2);
$pdf->Cell(66);
$pdf->Cell(0, 0, $row2["LIMITEPRESENTACION"]);


// Cuadro alícuota PCP..
$pdf->Ln(10.8);
$params = array(":idsolicitud" => $row2["SA_ID"]);
$sql =
	"SELECT NVL((SELECT ap_alicuota
								 FROM afi.aap_alicuotas_pcp
								WHERE ap_idparametro_pcp = pp_id
									AND ap_fechabaja IS NULL
									AND ap_idsolicitud = :idsolicitud), 0) alicuota,
					TO_CHAR(NVL((SELECT ap_alicuota
												 FROM afi.aap_alicuotas_pcp
												WHERE ap_idparametro_pcp = pp_id
													AND ap_fechabaja IS NULL
													AND ap_idsolicitud = :idsolicitud), 0), '$99,999,990.00') alicuotaformateada,
					NVL((SELECT ap_canttrabajador
								 FROM afi.aap_alicuotas_pcp
								WHERE ap_idparametro_pcp = pp_id
									AND ap_fechabaja IS NULL
									AND ap_idsolicitud = :idsolicitud), 0) canttrabajador
		 FROM afi.app_parametro_pcp
		WHERE art.actualdate BETWEEN pp_fechadesde AND pp_fechahasta
			AND pp_fechabaja IS NULL
 ORDER BY pp_renglon";
$stmt = DBExecSql($conn, $sql, $params);
$totalAlicuota = 0;
$totalTrabajadores = 0;
while ($rowPCP = DBGetQuery($stmt, 1, false)) {
	$totalAlicuota+= ($rowPCP["ALICUOTA"]);
	$totalTrabajadores+= $rowPCP["CANTTRABAJADOR"];

	$pdf->Ln(7);
	$pdf->Cell(76);
	$pdf->Cell(24, 0, $rowPCP["CANTTRABAJADOR"], 0, 0, "R");

	$pdf->Cell(48);
	$pdf->Cell(24, 0, ($rowPCP["CANTTRABAJADOR"] == 0)?"-":$rowPCP["ALICUOTAFORMATEADA"], 0, 0, "R");
}

$pdf->Ln(7);
$pdf->Cell(76);
$pdf->Cell(24, 0, $totalTrabajadores, 0, 0, "R");

$pdf->Cell(48);
$pdf->Cell(24, 0, ($totalAlicuota == 0)?"-":number_format($totalAlicuota, 2), 0, 0, "R");


$params = array(":idsolicitud" => $row2["SA_ID"]);
$sql =
	"SELECT lt_cpostal codigopostal, pv_descripcion provincia, lt_calle, lt_departamento, '-' lt_empleados, lt_localidad, lt_numero, lt_piso,
					lt_telefonos /*art.afi.get_telefonos('asf_solicitudtelefonoestableci', lt_id, NULL, NULL)*/ telefonos
		 FROM afi.alt_lugartrabajo_pcp, cpv_provincias
		WHERE lt_provincia = pv_codigo(+)
			AND lt_fechabaja IS NULL
			AND lt_idsolicitud = :idsolicitud
 ORDER BY lt_nrolugartrabajo";
$stmt = DBExecSql($conn, $sql, $params);

if ($rowTelefonos = DBGetQuery($stmt, 1, false)) {
	$pdf->Ln(26.6);
	$pdf->Cell(4);
	$pdf->Cell(140, 0, $rowTelefonos["LT_CALLE"]);

	$pdf->Cell(8);
	$pdf->Cell(16, 0, $rowTelefonos["LT_NUMERO"]);

	$pdf->Cell(7);
	$pdf->Cell(8, 0, $rowTelefonos["LT_PISO"]);

	$pdf->Cell(10);
	$pdf->Cell(8, 0, $rowTelefonos["LT_DEPARTAMENTO"]);

	$pdf->Ln(4.6);
	$pdf->Cell(7);
	$pdf->Cell(59, 0, $rowTelefonos["LT_LOCALIDAD"]);

	$pdf->Cell(14);
	$pdf->Cell(52, 0, $rowTelefonos["PROVINCIA"]);

	$pdf->Cell(36);
	$pdf->Cell(32, 0, $rowTelefonos["CODIGOPOSTAL"]);

	$pdf->Ln(5);
	$pdf->Cell(30);
	$pdf->Cell(32, 0, $rowTelefonos["LT_EMPLEADOS"]);

	$pdf->Cell(22);
	$pdf->Cell(116, 0, $rowTelefonos["TELEFONOS"]);
}

if ($rowTelefonos = DBGetQuery($stmt, 1, false)) {
	$pdf->Ln(11);
	$pdf->Cell(4);
	$pdf->Cell(140, 0, $rowTelefonos["LT_CALLE"]);

	$pdf->Cell(8);
	$pdf->Cell(16, 0, $rowTelefonos["LT_NUMERO"]);

	$pdf->Cell(7);
	$pdf->Cell(8, 0, $rowTelefonos["LT_PISO"]);

	$pdf->Cell(10);
	$pdf->Cell(8, 0, $rowTelefonos["LT_DEPARTAMENTO"]);

	$pdf->Ln(4.8);
	$pdf->Cell(7);
	$pdf->Cell(59, 0, $rowTelefonos["LT_LOCALIDAD"]);

	$pdf->Cell(14);
	$pdf->Cell(52, 0, $rowTelefonos["PROVINCIA"]);

	$pdf->Cell(36);
	$pdf->Cell(32, 0, $rowTelefonos["CODIGOPOSTAL"]);

	$pdf->Ln(5);
	$pdf->Cell(30);
	$pdf->Cell(32, 0, $rowTelefonos["LT_EMPLEADOS"]);

	$pdf->Cell(22);
	$pdf->Cell(116, 0, $rowTelefonos["TELEFONOS"]);
}

$pdf->SetY(226);
$pdf->Cell(93);
$pdf->Cell(28, 0, number_format($row2["CLAUSULAPENAL"], 0, ",", "."), 0, 0, "C");

$pdf->Cell(12);
$pdf->Cell(62, 0, numerosALetras($row2["CLAUSULAPENAL"]));



//Página 2..
$pdf->AddPage();
$tplIdx = $pdf->importPage(2);
$pdf->useTemplate($tplIdx);

// Tapo el N° de solicitud que está en el pdf original..
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(80, 19, 48, 4, "F");
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);

$pdf->SetFont("Arial", "", 10);
$pdf->Ln(11);
$pdf->Cell(190, 0, setNumeroSolicitud($row2["CUITSUSCRIPCION"], $row2["FO_FORMULARIO"]), 0, 0, "C");


$params = array(":idsolicitud" => $row2["SA_ID"]);
$sql =
	"SELECT *
		 FROM afi.arp_riesgo_pcp
		WHERE rp_fechabaja IS NULL
			AND rp_idsolicitud = :idsolicitud";
$stmt = DBExecSql($conn, $sql, $params);
$rowRiesgo = DBGetQuery($stmt, 1, false);

$pdf->Ln(15);
$pdf->Cell(30);
$texto = $rowRiesgo["RP_DESCRIPCION"];
$pdf->WordWrap($texto, 168);
$texto = explode("\n", $texto);
for ($i=0; $i<count($texto); $i++) {
	if ($i > 1)
		break;

	$str = trim($texto[$i]);

	$pdf->Cell(2);
	$pdf->Cell(0, 0, $str);
	$pdf->Ln(4.8);
}

$pdf->SetY(51);
if ($rowRiesgo["RP_ELECTRICO"] == "S") {
	$pdf->Cell(103.4);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_ELECTRICO"] == "N") {
	$pdf->Cell(115.4);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(11);
if ($rowRiesgo["RP_INCENDIO"] == "S") {
	$pdf->Cell(103.4);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_INCENDIO"] == "N") {
	$pdf->Cell(115.4);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(5);
if ($rowRiesgo["RP_EXTINTOR"] == 1) {
	$pdf->Cell(48);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_EXTINTOR"] == 2) {
	$pdf->Cell(94.6);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_EXTINTOR"] == 3) {
	$pdf->Cell(125.2);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(-0.2);
$pdf->Cell(144);
$pdf->Cell(56, 0, $rowRiesgo["RP_EXTINTOR_CUAL"]);

$pdf->Ln(14);
if ($rowRiesgo["RP_INSECTICIDA"] == "S") {
	$pdf->Cell(41.6);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_INSECTICIDA"] == "N") {
	$pdf->Cell(53.6);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(-0.2);
$pdf->Cell(74);
$pdf->Cell(124, 0, $rowRiesgo["RP_INSECTICIDA_CUAL"]);

$pdf->Ln(4);
if ($rowRiesgo["RP_BENCINA"] == "S") {
	$pdf->Cell(41.6);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_BENCINA"] == "N") {
	$pdf->Cell(53.6);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(4);
if ($rowRiesgo["RP_RATICIDA"] == "S") {
	$pdf->Cell(41.6);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_RATICIDA"] == "N") {
	$pdf->Cell(53.6);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(-0.2);
$pdf->Cell(74);
$pdf->Cell(124, 0, $rowRiesgo["RP_RATICIDA_CUAL"]);

$pdf->Ln(4.4);
if ($rowRiesgo["RP_DESINFECTANTES"] == "S") {
	$pdf->Cell(41.6);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_DESINFECTANTES"] == "N") {
	$pdf->Cell(53.6);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(4);
if ($rowRiesgo["RP_DETERGENTES"] == "S") {
	$pdf->Cell(41.6);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_DETERGENTES"] == "N") {
	$pdf->Cell(53.6);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(4);
if ($rowRiesgo["RP_SODACAUSTICA"] == "S") {
	$pdf->Cell(41.6);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_SODACAUSTICA"] == "N") {
	$pdf->Cell(53.6);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(4);
if ($rowRiesgo["RP_DESENGRASANTE"] == "S") {
	$pdf->Cell(41.6);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_DESENGRASANTE"] == "N") {
	$pdf->Cell(53.6);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(4);
if ($rowRiesgo["RP_HIPOCLORITODESODIO"] == "S") {
	$pdf->Cell(41.6);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_HIPOCLORITODESODIO"] == "N") {
	$pdf->Cell(53.6);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(4);
if ($rowRiesgo["RP_AMONIACO"] == "S") {
	$pdf->Cell(41.6);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_AMONIACO"] == "N") {
	$pdf->Cell(53.6);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(4);
if ($rowRiesgo["RP_ACIDOMURIATICO"] == "S") {
	$pdf->Cell(41.6);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_ACIDOMURIATICO"] == "N") {
	$pdf->Cell(53.6);
	$pdf->Cell(0, 0, "X");
}


$pdf->Ln(4);
$pdf->Cell(4);
$texto = $rowRiesgo["RP_OTRORIESGOQUIMICO"];
$pdf->WordWrap($texto, 192);
$texto = explode("\n", $texto);
for ($i=0; $i<count($texto); $i++) {
	if ($i > 1)
		break;

	$str = trim($texto[$i]);

	$pdf->Cell(1);
	$pdf->Cell(0, 0, $str);
	$pdf->Ln(4.2);
}

$pdf->SetY(141.6);
if ($rowRiesgo["RP_PROTECCIONBALCONES"] == "S") {
	$pdf->Cell(78);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_PROTECCIONBALCONES"] == "N") {
	$pdf->Cell(90);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(4.2);
if ($rowRiesgo["RP_INTERIORALTURA"] == "S") {
	$pdf->Cell(78);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_INTERIORALTURA"] == "N") {
	$pdf->Cell(90);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(-0.2);
$pdf->Cell(114);
$pdf->Cell(84, 0, $rowRiesgo["RP_INTERIORALTURA_CUAL"]);

$pdf->Ln(4.4);
if ($rowRiesgo["RP_EXTERIORALTURA"] == "S") {
	$pdf->Cell(78);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_EXTERIORALTURA"] == "N") {
	$pdf->Cell(90);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(-0.2);
$pdf->Cell(114);
$pdf->Cell(84, 0, $rowRiesgo["RP_EXTERIORALTURA_CUAL"]);

$pdf->Ln(8.6);
if ($rowRiesgo["RP_ESCALERABARANDA"] == "S") {
	$pdf->Cell(78);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_ESCALERABARANDA"] == "N") {
	$pdf->Cell(90);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(14);
if ($rowRiesgo["RP_INDUMENTARIA"] == "S") {
	$pdf->Cell(78);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_INDUMENTARIA"] == "N") {
	$pdf->Cell(90);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(-0.2);
$pdf->Cell(114);
$pdf->Cell(84, 0, $rowRiesgo["RP_INDUMENTARIA_CUAL"]);

$pdf->Ln(7.6);
if ($rowRiesgo["RP_PROTECCIONPERSONAL"] == "S") {
	$pdf->Cell(78);
	$pdf->Cell(0, 0, "X");
}
elseif ($rowRiesgo["RP_PROTECCIONPERSONAL"] == "N") {
	$pdf->Cell(90);
	$pdf->Cell(0, 0, "X");
}

$pdf->Ln(-0.2);
$pdf->Cell(114);
$pdf->Cell(84, 0, $rowRiesgo["RP_PROTECCIONPERSONAL_CUAL"]);

$pdf->Ln(12.6);
$pdf->Cell(52);
$pdf->Cell(57, 0, $row2["SA_LUGARSUSCRIPCION"]);

$pdf->Cell(10);
$pdf->Cell(8, 0, $row2["DIASUSCRIPCION"]);

$pdf->Cell(21);
$pdf->Cell(27, 0, $row2["MESSUSCRIPCION"], 0, 0, "C");

$pdf->Cell(6);
$pdf->Cell(16, 0, $row2["ANOSUSCRIPCION"]);

$pdf->Ln(11.4);
$pdf->Cell(20);
$pdf->Cell(72, 0, $row2["NOMBRECOMERCIALIZADOR"]);

$pdf->Cell(30);
$pdf->Cell(76, 0, $row2["SA_TITULAR"]);

$pdf->Ln(6.2);
$pdf->Cell(20);
$pdf->Cell(14, 0, $row2["ENTIDAD"]);

$pdf->Cell(18);
$pdf->Cell(36, 0, $row2["VE_VENDEDOR"]);

$pdf->Cell(26);
$pdf->Cell(40, 0, $row2["CARGO"]);

$pdf->Cell(12);
$pdf->Cell(32, 0, $row2["SA_DOCUMENTO_TITULAR"], 0, 0, "C");

if (!(($_SESSION["canal"] == 323) or ($_SESSION["entidad"] == 400))) {
	$pdf->SetFillColor(255, 255, 255);
	$pdf->Rect(2, 255, 208, 8, "F");
}


//Página 3..
$pdf->AddPage();
$tplIdx = $pdf->importPage(3);
$pdf->useTemplate($tplIdx);

// Tapo el N° de solicitud que está en el pdf original..
$pdf->SetDrawColor(255, 255, 255);
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(80, 19, 48, 4, "F");
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(0, 0, 0);

$pdf->SetFont("Arial", "", 10);
$pdf->Ln(11);
$pdf->Cell(190, 0, setNumeroSolicitud($row2["CUITSUSCRIPCION"], $row2["FO_FORMULARIO"]), 0, 0, "C");

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(164.6);
$pdf->Cell(166);
$pdf->Cell(0, 0, ponerGuiones($row2["SC_CUIT"]));

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(60.2);
$pdf->Cell(166);
$pdf->Cell(0, 0, ponerGuiones($row2["SC_CUIT"]));



if ($autoPrint)
	$pdf->AutoPrint(false);
$pdf->Output();
?>