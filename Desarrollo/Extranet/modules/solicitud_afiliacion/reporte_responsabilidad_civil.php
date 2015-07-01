<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


function updateFechaImpresion($idFormulario) {
	global $conn;

	if (isset($_REQUEST["c"])) {		// Se entra desde responsabilidad civil..
		$params = array(":contrato" => $_REQUEST["c"]);
		$sql =
			"UPDATE art.apr_polizarc
					SET pr_fechaimpresion = SYSDATE
			  WHERE pr_idendoso = (SELECT MAX(en_id)
														FROM aen_endoso
													 WHERE en_contrato = :contrato)";
	}
	else {
		$params = array(":idformulario" => $idFormulario);
		$sql =
			"UPDATE art.apr_polizarc
					SET pr_fechaimpresion = SYSDATE
			  WHERE pr_idformulario = (SELECT sa_idformulario
																	 FROM asa_solicitudafiliacion
																	WHERE sa_idformulario = :idformulario)";
	}
	DBExecSql($conn, $sql, $params);
}

SetDateFormatOracle("DD/MM/YYYY");

if (!isset($_REQUEST["ap"]))
	$autoPrint = false;
else
	$autoPrint = ($_REQUEST["ap"] == "t");

if (isset($_REQUEST["c"])) {		// Se entra desde responsabilidad civil..
	$params = array(":contrato" => $_REQUEST["c"]);
	$sql =
		"SELECT ac.ac_descripcion actividad_principal,
					  DECODE(SUBSTR(cac2.ac_codigo, 1, 1), '1', 'X', ' ') actividadagropecuaria,
					  DECODE(SUBSTR(cac2.ac_codigo, 1, 1), '6', 'X', ' ') actividadcomercial,
					  DECODE(ac.ac_financiera, 'S', 'X', ' ') actividadfinanciera,
					  DECODE(SUBSTR(cac2.ac_codigo, 1, 1), '3', 'X', ' ') actividadindustrial,
					  DECODE(SUBSTR(cac2.ac_codigo, 1, 1), '9', 'X', ' ') actividadservicios,
					  CASE WHEN pr_medio_pago = 'B' THEN 'X' ELSE '' END boleta,
					  dc_calle calle,
					  ac.ac_codigo cod_actividad_afip,
					  dc_cpostal cod_postal,
					  (SELECT CASE WHEN en_idcanal = 321
												 THEN '15925'
												 ELSE NVL(ve_provinciaseguros, NVL(en_cuit, ve_cuit))
										END
							 FROM xve_vendedor, xen_entidad, xev_entidadvendedor, avc_vendedorcontrato
							WHERE en_id = ev_identidad
								AND ev_id = vc_identidadvend
								AND ve_id = ev_idvendedor
								AND vc_id = art.comision.get_ultidentidadvendcontrato(co_contrato)) cod_productor,
						art.utiles.armar_cuit(em_cuit) cuit,
						(SELECT art.utiles.armar_cuit(ve_cuit)
							 FROM xve_vendedor, xev_entidadvendedor, avc_vendedorcontrato
							WHERE ve_id = ev_idvendedor
								AND ev_id = vc_identidadvend
								AND vc_id = ART.COMISION.GET_ULTIDENTIDADVENDCONTRATO(vc_contrato)
								AND vc_contrato = co_contrato) cuitvendedor,
						TO_CHAR(NVL(co_masatotalmayorcero, co_masatotal) * pr_valor_rc / 100, '$9,999,999,990.00') cuotainicialrc,
						CASE WHEN pr_medio_pago IN ('DA', 'TC') THEN 'X' ELSE '' END debito,
						dc_departamento departamento,
						dc_mail email,
						(SELECT COUNT(*) FROM aes_establecimiento WHERE es_contrato = 125125 AND es_fechabaja IS NULL) establecimientos,
						em_feinicactiv f_inicio_actividad,
						SYSDATE fechavigenciadesde,
						SYSDATE + 365 fechavigenciahasta,
						fj.tb_descripcion forma_juridica,
						CASE WHEN pr_sumaasegurada = 250000 THEN 'X' ELSE '' END AS hasta250000,
						CASE WHEN pr_sumaasegurada = 500000 THEN 'X' ELSE '' END AS hasta500000,
						CASE WHEN pr_sumaasegurada = 1000000 THEN 'X' ELSE '' END AS hasta1000000,
						co_idformulario idformulario,
						dc_localidad localidad,
					  (SELECT CASE WHEN en_idcanal = 321
												 THEN 'Venta Directa'
												 ELSE ve_nombre
										END
							 FROM xve_vendedor, xen_entidad, xev_entidadvendedor, avc_vendedorcontrato
							WHERE en_id = ev_identidad
								AND ev_id = vc_identidadvend
								AND ve_id = ev_idvendedor
								AND vc_id = art.comision.get_ultidentidadvendcontrato(co_contrato, NULL, 'N', TO_CHAR(SYSDATE, 'YYYYMM'))) nom_productor,
						co_idformulario nro_cliente,
						dc_numero numero,
						dc_piso piso,
						pr_mail,
						pr_valor_rc,
						pv.pv_descripcion provincia,
						em_nombre razon_social,
						art.afi.get_telefonos('ATO_TELEFONOCONTRATO', co_contrato, 'L') telefonos
			 FROM aco_contrato
			 JOIN aem_empresa ON co_idempresa = em_id
			 JOIN aen_endoso ON en_contrato = co_contrato
	LEFT JOIN adc_domiciliocontrato ON dc_contrato = co_contrato AND dc_tipo = 'L'
	LEFT JOIN art.apr_polizarc rc ON pr_idendoso = en_id
	LEFT JOIN art.ctb_tablas fj ON fj.tb_clave = 'FJURI' AND fj.tb_codigo = em_formaj
			 JOIN cac_actividad ac ON ac.ac_id = co_idactividad
	LEFT JOIN cac_actividad cac2 ON ac.ac_relacion = cac2.ac_codigo
			 JOIN cpv_provincias pv ON pv.pv_codigo = dc_provincia
			WHERE co_contrato = :contrato
	 ORDER BY pr_idformulario, en_id DESC";
}
else {		// Se entra desde la impresión de la solicitud de afiliación..
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
		"SELECT ac.ac_descripcion actividad_principal,
						DECODE(SUBSTR(cac2.ac_codigo, 1, 1), '1', 'X', ' ') actividadagropecuaria,
						DECODE(SUBSTR(cac2.ac_codigo, 1, 1), '6', 'X', ' ') actividadcomercial,
						DECODE(ac.ac_financiera, 'S', 'X', ' ') actividadfinanciera,
						DECODE(SUBSTR(cac2.ac_codigo, 1, 1), '3', 'X', ' ') actividadindustrial,
						DECODE(SUBSTR(cac2.ac_codigo, 1, 1), '9', 'X', ' ') actividadservicios,
						CASE WHEN rc.pr_medio_pago = 'B' THEN 'X' ELSE '' END boleta,
						sa.sa_calle calle,
						ac.ac_codigo cod_actividad_afip,
						sa.sa_cpostal cod_postal,
						CASE WHEN en_idcanal = 321
								 THEN '15925'
								 ELSE NVL(ve_provinciaseguros, NVL(en_cuit, ve_cuit))
						END cod_productor,
						art.utiles.armar_cuit(sa.sa_cuit) cuit,
						art.utiles.armar_cuit(ve_cuit) cuitvendedor,
						TO_CHAR(sa_masatotal * pr_valor_rc / 100, '$9,999,999,990.00') cuotainicialrc,
						CASE WHEN rc.pr_medio_pago IN ('DA', 'TC') THEN 'X' ELSE '' END debito,
						sa.sa_departamento departamento,
						sa_mail_legal email,
						sa_establecimientos establecimientos,
						sa.sa_fecharevision f_inicio_actividad,
						sa.sa_fechavigenciadesde fechavigenciadesde,
						sa.sa_fechavigenciahasta fechavigenciahasta,
						fj.tb_descripcion forma_juridica,
						CASE WHEN rc.pr_sumaasegurada = 250000 THEN 'X' ELSE '' END AS hasta250000,
						CASE WHEN rc.pr_sumaasegurada = 500000 THEN 'X' ELSE '' END AS hasta500000,
						CASE WHEN rc.pr_sumaasegurada = 1000000 THEN 'X' ELSE '' END AS hasta1000000,
						sa_idformulario idformulario,
						sa.sa_localidad localidad,
						CASE WHEN en_idcanal = 321 THEN 'Venta Directa' ELSE ve_nombre END nom_productor,
						sa.sa_idformulario nro_cliente,
						sa.sa_numero numero,
						sa.sa_piso piso,
						pr_mail,
						pr_valor_rc,
						pv.pv_descripcion provincia,
						sa.sa_nombre razon_social,
						art.afi.get_telefonos('ATS_TELEFONOSOLICITUD', sa_id, 'L') telefonos
			 FROM asa_solicitudafiliacion sa 
	LEFT JOIN xve_vendedor ve ON ve.ve_id = sa.sa_idvendedor
	LEFT JOIN xev_entidadvendedor ON ev_idvendedor = ve_id AND ev_fechabaja is null
	LEFT JOIN xen_entidad on en_id = ev_identidad
	LEFT JOIN art.apr_polizarc rc ON rc.pr_idformulario = sa.sa_idformulario
	LEFT JOIN art.ctb_tablas fj ON fj.tb_clave = 'FJURI' AND fj.tb_codigo = sa.sa_formaj
			 JOIN cac_actividad ac ON ac.ac_id = sa.sa_idactividad
	LEFT JOIN cac_actividad cac2 ON ac.ac_relacion = cac2.ac_codigo
			 JOIN cpv_provincias pv ON pv.pv_codigo = sa.sa_provincia
			WHERE sa_idformulario = :idformulario";
}
$stmt = DBExecSql($conn, $sql, $params);

$msgSqlVacio = "";
if (DBGetRecordCount($stmt) == 0) {
	if (isset($_REQUEST["c"]))
		$msgSqlVacio = "No se encontraron datos.";
	else
		$msgSqlVacio = "Esta C.U.I.T. no tiene cargada la solicitud de afiliación.";
}

$row2 = DBGetQuery($stmt, 1, false);


if ($autoPrint)
	$pdf = new PDF_AutoPrint();
else
	$pdf = new FPDI();


if ($msgSqlVacio == "") {
	$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/templates/responsabilidad_civil.pdf");
	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);

	$pdf->AddPage();
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx);
	$pdf->SetFont("Arial", "B", 9);

	$pdf->Ln(30);
	$pdf->Cell(168);
	$pdf->Cell(0, 0, "PCI".$row2["IDFORMULARIO"]);

	$pdf->Ln(5);
	$pdf->SetDrawColor(0, 0, 0);
	if ($row2["BOLETA"] == "") {
		$pdf->Cell(46);
		$pdf->Cell(7, 0, "", 1, 0, "C");
	}
	if ($row2["DEBITO"] == "") {
		$pdf->Ln(0.1);
		$pdf->Cell(57);
		$pdf->Cell(18.6, 0, "", 1, 0, "C");
	}
	$pdf->SetDrawColor(255, 255, 255);

	$pdf->Ln(3);
	$pdf->Cell(20);
	$pdf->Cell(24, 0, $row2["COD_PRODUCTOR"]);

	$pdf->Cell(4);
	$pdf->Cell(32, 0, $row2["CUITVENDEDOR"]);

	$pdf->Rect($pdf->GetX() + 2, $pdf->GetY() - 2, 12, 2.8, "DF");

	$pdf->Ln(4);
	$pdf->Cell(20);
	$pdf->Cell(80, 0, $row2["NOM_PRODUCTOR"]);
	$pdf->Ln(-4);

	$pdf->Ln(10.4);
	$pdf->Cell(16);
	$pdf->Cell(36, 0, $row2["FECHAVIGENCIADESDE"]);

	$pdf->Cell(15);
	$pdf->Cell(30, 0, $row2["FECHAVIGENCIAHASTA"]);


	// DATOS DEL EMPLEADOR..
	$pdf->Ln(10.4);
	$pdf->Cell(29);
	$pdf->Cell(76, 0, $row2["RAZON_SOCIAL"]);

	$pdf->Cell(28);
	$pdf->Cell(60, 0, $row2["NRO_CLIENTE"]);

	$pdf->Ln(4);
	$pdf->Cell(14);
	$pdf->Cell(26, 0, $row2["CUIT"]);

	$pdf->Cell(42);
	$pdf->Cell(12, 0, $row2["ESTABLECIMIENTOS"]);

	$pdf->Cell(20);
	$pdf->Cell(8, 0, $row2["FORMA_JURIDICA"]);

	$pdf->Ln(4.2);
	$pdf->Cell(26);
	$pdf->Cell(104, 0, $row2["ACTIVIDAD_PRINCIPAL"]);

	$pdf->Cell(42);
	$pdf->Cell(22, 0, $row2["F_INICIO_ACTIVIDAD"]);

	$pdf->Ln(9.5);
	$pdf->Cell(8);
	$pdf->Cell(28, 0, $row2["COD_ACTIVIDAD_AFIP"]);

	$pdf->Cell(20);
	$pdf->Cell(132, 0, $row2["ACTIVIDAD_PRINCIPAL"]);

	$pdf->Ln(10);
	$pdf->Cell(8);
	$pdf->Cell(130, 0, $row2["CALLE"]);

	$pdf->Cell(8);
	$pdf->Cell(10, 0, $row2["NUMERO"]);

	$pdf->Cell(10);
	$pdf->Cell(7, 0, $row2["PISO"]);

	$pdf->Cell(14);
	$pdf->Cell(8, 0, $row2["DEPARTAMENTO"]);

	$pdf->Ln(5);
	$pdf->Cell(14);
	$pdf->Cell(56, 0, $row2["LOCALIDAD"]);

	$pdf->Cell(16);
	$pdf->Cell(52, 0, $row2["PROVINCIA"]);

	$pdf->Cell(37);
	$pdf->Cell(20, 0, $row2["COD_POSTAL"]);

	$pdf->Ln(4.5);
	$pdf->Cell(10);
	$pdf->Cell(126, 0, $row2["EMAIL"]);

	$pdf->Cell(16);
	$pdf->Cell(42, 0, substr($row2["TELEFONOS"], 0, 20));

	$pdf->Ln(5);
	$pdf->Cell(160, 0, substr($row2["TELEFONOS"], 20));


	// ACTIVIDAD DE LA EMPRESA
	$pdf->Ln(20.6);
	$pdf->Cell(19.7);
	$pdf->Cell(8, 0, $row2["ACTIVIDADAGROPECUARIA"]);

	$pdf->Cell(49.2);
	$pdf->Cell(8, 0, $row2["ACTIVIDADINDUSTRIAL"]);

	$pdf->Cell(41.8);
	$pdf->Cell(8, 0, $row2["ACTIVIDADFINANCIERA"]);

	$pdf->Cell(19.4);
	$pdf->Cell(8, 0, $row2["ACTIVIDADCOMERCIAL"]);

	$pdf->Cell(15.2);
	$pdf->Cell(8, 0, $row2["ACTIVIDADSERVICIOS"]);

	$pdf->Ln(6.3);
	$pdf->Cell(10);
	if (($row2["ACTIVIDADAGROPECUARIA"] == "") and ($row2["ACTIVIDADINDUSTRIAL"] == "") and ($row2["ACTIVIDADFINANCIERA"] == "") and ($row2["ACTIVIDADCOMERCIAL"] == "") and ($row2["ACTIVIDADSERVICIOS"] == ""))
		$pdf->Cell(60, 0, $row2["ACTIVIDAD_PRINCIPAL"]);
	else
		$pdf->Cell(60, 0, " ");

	$pdf->Ln(10.4);
	$pdf->Cell(36);
	$pdf->Cell(8, 0, $row2["HASTA250000"]);

	$pdf->Ln(5);
	$pdf->Cell(36);
	$pdf->Cell(8, 0, $row2["HASTA500000"]);

	$pdf->Ln(5.4);
	$pdf->Cell(36);
	$pdf->Cell(8, 0, $row2["HASTA1000000"]);

	$pdf->Ln(0);
	$pdf->Cell(104);
	$pdf->Cell(20, 0, $row2["PR_VALOR_RC"]);

	$pdf->Cell(16);
	$pdf->Cell(0, 0, $row2["CUOTAINICIALRC"]);

	$pdf->SetFont("Arial", "", 8);
	$pdf->Ln(8);
	$pdf->Cell(-2);
	$pdf->Cell(0, 0, "La cuota se calculará mensualmente de acuerdo a la cantidad de trabajadores y a la masa salarial bruta del período de cobertura.");
	$pdf->SetFont("Arial", "B", 9);

	$pdf->Ln(59.8);
	$pdf->Cell(56);
	$pdf->Cell(120, 0, $row2["PR_MAIL"]);

	// Muestro el texto de arriba de las firmas..
	$pdf->SetFont("Arial", "", 9);
	$pdf->Ln(4);
	$texto = explode("\n", "Por medio de la presente ".$row2["RAZON_SOCIAL"]." autoriza a Provincia ART S.A. a entregar a Provincia Seguros S.A. la información sobre la nómina (datos de Empleados, Masa Salarial, C.U.I.L., Nombre, etc.) e información complementaria que a criterio de la aseguradora permita tener un conocimiento de la actividad y del comportamiento del riesgo inherente a la cobertura.");
	for ($i=0; $i<count($texto); $i++) {
		$str = trim($texto[$i]);

		$pdf->WordWrap($str, 188);
		$pdf->Write(4, $str);

		$pdf->Ln(2);
	}

	$pdf->SetX(8);
	$pdf->SetY(250);
	$pdf->Cell(120, 0, "Buenos Aires, ".date("d")." de ".GetMonthName(date("m"))." de ".date("Y"));

	updateFechaImpresion((isset($idFormulario)?$idFormulario:0));
}
else {
	$pdf->AddPage();
	$pdf->SetTextColor(255, 0, 0);
	$pdf->SetFont("Arial", "B", 14);

	$pdf->Ln(30);
	$pdf->Cell(0, 0, $msgSqlVacio, 0, 0, "C");
}

if ($autoPrint)
	$pdf->AutoPrint(false);

if (isset($_REQUEST["f"]))		// Ese parámetro se pasa cuando la llamada se hace desde Delphi..
	$pdf->Output(DATA_REPORTE_RESPONSABILIDAD_CIVIL.$_REQUEST["f"], "F");
else
	$pdf->Output();
?>