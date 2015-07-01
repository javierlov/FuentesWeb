<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getEntidadComercial($contrato) {
	global $conn;

	$params = array(":contrato" => $contrato);
	$sql =
		"SELECT en_nombre atencioncomercial
			 FROM xen_entidad, xev_entidadvendedor, avc_vendedorcontrato
			WHERE en_id = ev_identidad
				AND ev_id = vc_identidadvend
				AND vc_vigenciahasta IS NULL
				AND vc_contrato = :contrato
				AND vc_id = art.comision.get_ultidentidadvendcontrato(vc_contrato, NULL, 'S', TO_CHAR(SYSDATE, 'YYYYMM'))";
	return valorSql($sql, "", $params);
}

function getEntidadVendedor($contrato, &$entidad, &$vendedor) {
	global $conn;

	$params = array(":contrato" => $contrato);
	$sql =
		"SELECT DECODE(en_codbanco, '172', en_codbanco, en_nombre) en_nombre, DECODE(en_codbanco, '172', ve_vendedor, ve_nombre) ve_nombre
			 FROM xen_entidad, xve_vendedor, xev_entidadvendedor, avc_vendedorcontrato
			WHERE ev_identidad = en_id
				AND vc_identidadvend = ev_id
				AND ev_idvendedor = ve_id
				AND vc_contrato = :contrato
				AND vc_id = art.comision.get_ultidentidadvendcontrato(vc_contrato, NULL, 'N', TO_CHAR(SYSDATE, 'YYYYMM'))";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt, 1, false);

	$entidad = $row["EN_NOMBRE"];
	$vendedor = $row["VE_NOMBRE"];
}

function getGestorEjecutivoEstudio($contrato, &$gestor, &$ejecutivo, &$estudio) {
	global $conn;

	$params = array(":contrato" => $contrato);
	$sql =
		"SELECT gestor.gc_nombre gestornombre, ec_nombre ejecutivonombre, estudio.gc_nombre estudionombre
			 FROM aec_ejecutivocuenta, agc_gestorcuenta gestor, agc_gestorcuenta estudio, aco_contrato
			WHERE co_idgestor = gestor.gc_id(+)
				AND co_idejecutivo = ec_id(+)
				AND co_idestudio = estudio.gc_id(+)
				AND co_contrato = :contrato";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt, 1, false);

	$ejecutivo = $row["EJECUTIVONOMBRE"];
	$estudio = $row["ESTUDIONOMBRE"];
	$gestor = $row["GESTORNOMBRE"];
}

function getJuicioParteActoraDescripcion($contrato) {
	global $conn;

	$params = array(":contrato" => $contrato);
	$sql =
		"SELECT 'JA Nº ' || jt_numerocarpeta || ' - ' || ej_descripcion || ' - ' || jt_demandante || ' C/ ' || jt_demandado || ' - JUZGADO: ' || jz_descripcion
			 FROM aco_contrato, legales.ljt_juicioentramite, legales.lej_estadojuicio, legales.ljz_juzgado, legales.lod_origendemanda
			WHERE jt_estadomediacion LIKE '%A%'
				AND jt_idestado = ej_id
				AND jt_idjuzgado = jz_id
				AND jt_id = od_idjuicioentramite
				AND od_fechabaja IS NULL
				AND od_contrato = co_contrato
				AND co_motivobaja = '08'
				AND od_contrato = :contrato
	 ORDER BY jt_id DESC";
	$result = valorSql($sql, "", $params);

	if ($result != "")
		$result.= "\n";

	return $result;
}

function getLeyendaFinal($contrato) {
	global $conn;

	$params = array(":contrato" => $contrato);
	$sql =
		"SELECT DECODE(deuda, 0, NULL, 'Valores pendientes de acreditar por $' || REPLACE(TO_CHAR(deuda, 'FM9999999999.00'), '.', ','))
			 FROM (SELECT NVL(SUM(va_importe), 0) deuda
							 FROM art.ctb_tablas, zva_valor
							WHERE va_idcontrato = :contrato
								AND va_fechabaja IS NULL
								AND tb_clave = 'ESVAL'
								AND tb_codigo = va_estado
								AND tb_especial1 = 'N'
								AND va_fecharechazo IS NULL)";
	$result = valorSql($sql, "", $params);

	if ($result != "")
		$result.= "\n";

	$params = array(":contrato" => $contrato);
	$sql =
		"SELECT DECODE(monto, NULL, NULL, 'Valores rechazados por $ ' || REPLACE(TO_CHAR(monto, 'FM9999999999.00'), '.', ','))
			 FROM (SELECT SUM(va_importe) monto
							 FROM zva_valor
							WHERE va_estado = '03'
								AND va_idcontrato = :contrato)";
	$tmp = valorSql($sql, "", $params);

	if ($tmp != "")
		$result.= $tmp."\n";

	$params = array(":contrato" => $contrato);
	$sql =
		"SELECT DECODE(monto, 0, NULL, 'Valores pendientes de entrega por $' || REPLACE(TO_CHAR(monto, 'FM9999999999.00'), '.', ','))
			 FROM (SELECT NVL(SUM(pc_amortizacion + pc_interesfinanc) - art.deuda.get_valoresplan(pp_id), 0) monto
							 FROM art.ctb_tablas, zpc_plancuota, zpp_planpago
							WHERE pc_idplanpago = pp_id
								AND pp_estado = tb_codigo
								AND tb_clave = 'ESPLA'
								AND tb_especial1 = 'S'
								AND tb_especial2 <> 'A'
								AND pp_contrato = :contrato
					 GROUP BY pp_id)";
	$result.= valorSql($sql, "", $params);

	return $result;
}

function getSqlDetalle($row, $sqlDatos) {
	$params = array(":cuit" => sacarGuiones($row["CUIT"]));
	$sql = "SELECT cobranza.get_maxfechaconcquiebra(:cuit) FROM DUAL";
	$maximaFechaConcursoQuiebra = valorSql($sql, "", $params);

	$params = array(":cuit" => sacarGuiones($row["CUIT"]));
	$sql = "SELECT art.legales.get_periodoconcoquiebra(:cuit) FROM DUAL";
	$maximoPeriodoConcursoQuiebra = valorSql($sql, "", $params);

	$params = array(":contrato" => $_REQUEST["id"]);
	$sql = "SELECT deuda.get_primerperiodoconsiddeuda(:contrato, 'N') FROM DUAL";
	$primerPeriodoDeuda = valorSql($sql, "", $params);

	// ******* INICIO - SQL DE LOS INTERESES.. *******
	// Obtengo la fecha de vencimiento de la cuota a la que pertenece el periodo..
	$sqlVencimientoCuota = "deuda.get_vencimientocuotacontrato(rc_contrato, rc_periodo)";

	if (date("Ymd") <= formatDate("Ymd", $maximaFechaConcursoQuiebra))
		$sqlTasaInteresFinal = "0 tasainteres, ";
	else {
		// Obtengo la tasa de interes hasta la fecha de interés hasta..
		$sqlTasaInteresHasta = "deuda.get_tasaacumulada(".$sqlVencimientoCuota." + 1, TO_DATE(SYSDATE, 'DD/MM/YYYY'))";

		if (!isFechaValida($maximaFechaConcursoQuiebra))
			$sqlTasaInteresFinal = $sqlTasaInteresHasta." tasainteres, ";
		else {		// En concurso/quiebra..
			// La tasa de interes se aplica si el periodo no esta en concurso o quiebra..
			$sqlTasaInteresCQ = "0";

			$sqlTasaInteresFinal = "TO_NUMBER(UTILES.IIF_COMPARA('>', ".$maximoPeriodoConcursoQuiebra.", rc_periodo, ".$sqlTasaInteresCQ.", ".$sqlTasaInteresHasta." )) tasainteres, ";
		}
	}

	// Si el importe reclamado es <> 0 entonces la tasa de interes es cero..
	$sSqlInteresPositivo = "DECODE(importereclamoafip, 0, tasainteres, GREATEST(tasainteres, 0))";
	// ******* FIN - SQL DE LOS INTERESES.. *******

	$sql = str_replace("#PRIMER_PERIODO_DEUDA#", $primerPeriodoDeuda, $sqlDatos).
		" FROM (SELECT ".$sqlVencimientoCuota." vencimiento,
									 rc_periodo periodo,
									 rc_contratoprincipal contrato,
									 rc_codtiporegimen codreg,
									 afiliacion.get_tarifavigentetexto(rc_contrato, rc_periodo) fija_variable,
									 rc_prescripto prescripto,
									 DECODE(rc_gestionable, 'N', 'SI', 'NO') ddjj,
									 rc_empleados empleados,
									 rc_masasalarial masa,
									 rc_importereclamado importereclamoafip,
									 rc_devengadocuota + rc_devengadofondo + rc_devengadointeres + rc_devengadootros devengados,
									 rc_devengadocuota cuota,
									 rc_devengadointeres + rc_devengadootros interes_otros,
									 rc_devengadofondo fondo,
									 rc_pagocuota + rc_pagofondo + rc_pagointeres + rc_pagootros + rc_recuperocuota + rc_recuperofondo + rc_recuperointeres pagos,
									 (rc_devengadocuota + rc_devengadofondo + rc_devengadointeres + rc_devengadootros) - (rc_pagocuota + rc_pagofondo + rc_pagointeres + rc_pagootros + rc_recuperocuota + rc_recuperofondo + rc_recuperointeres) - rc_importereclamado - rc_montorefinanciado - cobranza.getsaldointereses(rc_contrato, rc_periodo) deuda,
									 rc_montorefinanciado montorefinanciado,
									 cobranza.is_periodochequesrechazados(rc_contrato, rc_periodo) per_chequesrechazados,
									 cobranza.getsaldointereses(rc_contrato, rc_periodo) saldointereses,
									 DECODE(emi.utiles.get_topeaplicado(rc_contrato, rc_periodo), 1, 'N', 2, 'X', 3, 'T', NULL) topeoemision,
									 rc_contrato,
									 0 extrajudicial,".$sqlTasaInteresFinal;
	if ($maximoPeriodoConcursoQuiebra == "")
		$sql.= "'N' esconcursoquiebra";
	else
		$sql.= " UTILES.IIF_COMPARA('<=', RC_PERIODO, '".$maximoPeriodoConcursoQuiebra."', 'S', 'N') esconcursoquiebra";

	$sql.= " FROM zrc_resumencobranza_ext
					WHERE rc_contratoprincipal = :contrato";
	$sql.= ") WHERE devengados - pagos - importereclamoafip > 0
							AND cobranza.is_nomostrarreclamoafip(devengados, pagos, importereclamoafip, periodo) = 'S'
							AND esconcursoquiebra <> 'S'
							AND prescripto <> 'S'
				 ORDER BY periodo, codreg DESC";

	return $sql;
}

function setDatosEstaticos(&$pdf, $row, $pagina, $entidad, $vendedor, $ejecutivo, $estudio, $gestor) {
	$pdf->addPage("L");
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx);
	$pdf->SetFont("Arial", "", 8);

	
	// Datos de la cabecera..
	$pdf->Ln(24);
	$pdf->Cell(0.2);
	$pdf->Cell(128, 0, $row["EM_NOMBRE"]);

	$pdf->Ln(4);
	$pdf->Cell(0.2);
	$pdf->Cell(128, 0, $row["DOM_POSTAL"]);

	$pdf->Ln(4);
	$pdf->Cell(0.2);
	$pdf->Cell(128, 0, "Tel. ".$row["TELEFONOS_POSTAL"]);

	$pdf->Ln(4.6);
	$pdf->Cell(0.2);
	$pdf->Cell(128, 0, $row["ACTIVIDAD"]);

	$pdf->Ln(7.6);
	$pdf->Cell(0.2);
	$pdf->Cell(86, 0, $row["ESTADO"]);

	$pdf->Cell(0.2);
	$pdf->Cell(44, 0, getEntidadComercial($_REQUEST["id"]));

	$pdf->Ln(8);
	$pdf->Cell(0.2);
	$pdf->Cell(44, 0, $gestor);

	$pdf->Cell(43, 0, $ejecutivo);

	$pdf->Cell(43, 0, $estudio);


	$pdf->Ln(-26.6);
	$pdf->Cell(132);
	$pdf->Cell(15, 0, $_REQUEST["id"], 0, 0, "C");

	$pdf->Cell(-2);
	$pdf->Cell(24, 0, $row["CUIT"], 0, 0, "C");

	$pdf->Ln(-1.8);
	$pdf->Cell(167.4);
	$pdf->Cell(91.6, 0, $entidad);

	$pdf->Ln(2.8);
	$pdf->Cell(167.4);
	$pdf->Cell(91.6, 0, $vendedor);

	$pdf->Ln(8.8);
	$pdf->Cell(131.4);
	$pdf->Cell(18, 0, $row["CO_VIGENCIADESDE"], 0, 0, "C");

	$pdf->Cell(1);
	$pdf->Cell(20, 0, $row["CO_VIGENCIAHASTA"]);

	$pdf->Cell(-2);
	$pdf->Cell(29, 0, $row["CONCURSO"]);

	$pdf->Cell(2);
	$pdf->Cell(30, 0, $row["QUIEBRA"]);

	$pdf->Cell(2);
	$pdf->Cell(23, 0, $row["FIJA_VARIABLE"], 0, 0, "R");

	$pdf->Ln(9);
	$texto = $row["MOTIVO_BAJA"];
	$pdf->wordWrap($texto, 126);
	$texto = explode("\n", $texto);
	for ($i=0; $i<count($texto); $i++) {
		if ($i > 1)
			break;

		$str = trim($texto[$i]);

		$pdf->Cell(131);
		$pdf->Cell(0, 0, $str);
		$pdf->Ln(3);
	}

	$descripcionJuicioParteActora = getJuicioParteActoraDescripcion($_REQUEST["id"]);
	$pdf->wordWrap($descripcionJuicioParteActora, 260);
	$descripcionJuicioParteActora = explode("\n", $descripcionJuicioParteActora);

	$pdf->Ln(9.8);
	for ($i=0; $i<count($descripcionJuicioParteActora); $i++) {
		if ($i > 2)
			break;

		$str = trim($descripcionJuicioParteActora[$i]);

		$pdf->Cell(0.2);
		$pdf->Cell(0, 0, $str);
		$pdf->Ln(3.6);
	}


	$pdf->Ln(7.6);
	$pdf->Cell(205.4);
	$pdf->SetFont("Arial", "B", 7);
	$pdf->Cell(0, 0, date("d/m/Y"));

	$pdf->Ln(121.1);
	$pdf->Cell(8.8);
	$pdf->Cell(120, 0, $pagina);

	$pdf->Cell(0, 0, date("d/m/Y"), 0, 0, "R");
}


$_REQUEST["id"] = intval($_REQUEST["id"]);

validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion((validarContrato($_REQUEST["id"])));

try {
	define("FILAS_POR_HOJAS", 29);

	$sqlFilas =
		"SELECT vencimiento,
						art.utiles.armar_periodo(periodo) periodo,
						fija_variable,
						ddjj,
						empleados,
						TO_CHAR(masa, '9,999,999,990.00') masa,
						extrajudicial,
						devengados,
						TO_CHAR(fondo, '999,999,990.00') fondo,
						TO_CHAR(cuota, '999,999,990.00') cuota,
						TO_CHAR(interes_otros, '999,999,990.00') interes_otros,
						TO_CHAR(pagos, '999,999,990.00') pagos,
						TO_CHAR(devengados - pagos - saldointereses, '999,999,990.00') deuda_total,
						TO_CHAR(TO_NUMBER(utiles.iif_compara('<=', deuda, 0, 0, ROUND(deuda * (DECODE(importereclamoafip, 0, tasainteres, GREATEST(tasainteres, 0)) / 100), 2))) + saldointereses, '999,999,990.00') interes,
						TO_CHAR(montorefinanciado, '999,999,990.00') montorefinanciado,
						TO_CHAR(ROUND(TO_NUMBER(utiles.iif_compara('<', periodo, #PRIMER_PERIODO_DEUDA#, 0, deuda + TO_NUMBER(utiles.iif_compara('<=', deuda, 0, 0, ROUND(deuda * (DECODE(importereclamoafip, 0, tasainteres, GREATEST(tasainteres, 0)) / 100), 2))))), 2) + saldointereses, '999,999,990.00') deuda_consolidada,
						TO_CHAR(importereclamoafip, '999,999,990.00') importereclamoafip,
						esconcursoquiebra,
						contrato,
						prescripto,
						DECODE(esconcursoquiebra, 'S', 'Q') || DECODE(montorefinanciado, 0, '', 'R') || DECODE(importereclamoafip, 0, '', 'A') || DECODE(per_chequesrechazados, 'S', 'Z') || topeoemision ref,
						codreg";
	$sqlTotales =
		"SELECT TO_CHAR(SUM(cuota), '9,999,999,990.00') cuota,
						TO_CHAR(SUM(fondo), '9,999,999,990.00') fondo,
						TO_CHAR(SUM(interes_otros), '9,999,999,990.00') interes_otros,
						TO_CHAR(SUM(pagos), '9,999,999,990.00') pagos,
						TO_CHAR(SUM(devengados - pagos - saldointereses), '9,999,999,990.00') deuda_total,
						TO_CHAR(SUM(montorefinanciado), '9,999,999,990.00') montorefinanciado,
						TO_CHAR(SUM(importereclamoafip), '9,999,999,990.00') importereclamoafip,
						TO_CHAR(SUM(TO_NUMBER(utiles.iif_compara('<=', deuda, 0, 0, ROUND(deuda * (DECODE(importereclamoafip, 0, tasainteres, GREATEST(tasainteres, 0)) / 100), 2))) + saldointereses), '9,999,999,990.00') interes,
						TO_CHAR(SUM(ROUND(TO_NUMBER(utiles.iif_compara('<', periodo, #PRIMER_PERIODO_DEUDA#, 0, deuda + TO_NUMBER(utiles.iif_compara('<=', deuda, 0, 0, ROUND(deuda * (DECODE(importereclamoafip, 0, tasainteres, GREATEST(tasainteres, 0)) / 100), 2))))), 2) + saldointereses), '9,999,999,990.00') deuda_consolidada";

	$entidad = "";
	$vendedor = "";
	getEntidadVendedor($_REQUEST["id"], $entidad, $vendedor);

	$ejecutivo = "";
	$estudio = "";
	$gestor = "";
	getGestorEjecutivoEstudio($_REQUEST["id"], $gestor, $ejecutivo, $estudio);

	$params = array(":contrato" => $_REQUEST["id"]);
	$sql =
		"SELECT ac_codigo || ' ' || ac_descripcion actividad,
						co_contrato,
						co_vigenciadesde,
						co_vigenciahasta,
						legales.get_fechaconcurso(em_cuit) concurso,
						art.utiles.armar_cuit(em_cuit) cuit,
						art.utiles.armar_domicilio(dc_calle, dc_numero, dc_piso, dc_departamento, NULL) || ' ' || art.utiles.armar_localidad(dc_cpostal, NULL, dc_localidad, dc_provincia) dom_postal,
						em_cuit,
						em_nombre,
						afest.tb_codigo || ' ' || afest.tb_descripcion estado,
						afiliacion.get_tarifavigentetexto(co_contrato, ".date("Ym").") fija_variable,
						co_fechabaja || ' ' || baj.tb_descripcion motivo_baja,
						utiles.periodo_ponerbarra(legales.get_periodoconcurso(em_cuit)) perconcurso,
						utiles.periodo_ponerbarra(legales.get_periodoquiebra(em_cuit)) perquiebra,
						legales.get_fechaquiebra(em_cuit) quiebra,
						LTRIM(dc_codareatelefonos || ' ' || dc_telefonos) telefonos_postal
			 FROM aem_empresa, aco_contrato, adc_domiciliocontrato, cac_actividad, ctb_tablas baj, ctb_tablas afest
			WHERE em_id = co_idempresa
				AND co_contrato = dc_contrato
				AND dc_tipo = 'L'
				AND co_idactividad = ac_id
				AND baj.tb_clave(+) = 'MOTIB'
				AND co_motivobaja = baj.tb_codigo(+)
				AND afest.tb_clave = 'AFEST'
				AND afest.tb_codigo = co_estado
				AND co_contrato = :contrato";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt, 1, false);

	if ($row["PERCONCURSO"] != "")
		$row["CONCURSO"] = $row["CONCURSO"]." (".$row["PERCONCURSO"].")";
	if ($row["PERQUIEBRA"] != "")
		$row["QUIEBRA"] = $row["QUIEBRA"]." (".$row["PERQUIEBRA"].")";

	// Traigo los registros del detalle para saber cuantas hojas voy a generar..
	$params = array(":contrato" => $_REQUEST["id"]);
	$sql = getSqlDetalle($row, $sqlFilas);
	$stmt = DBExecSql($conn, $sql, $params);


	//	*******  INICIO - Armado del reporte..  *******
	$pdf = new FPDI("L", "mm", array(232, 280));

	$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/estado_cuenta/templates/estado_cuenta.pdf");

	$pdf->SetFont("Arial", "", 7);
	$textoLeyendaFinal = getLeyendaFinal($_REQUEST["id"]);
	$pdf->wordWrap($textoLeyendaFinal, 196);
	$textoLeyendaFinal = explode("\n", $textoLeyendaFinal);

	$fila = 1;
	$pagina = 1;

	while ($rowDetalle = DBGetQuery($stmt, 1, false)) {
		if ($fila == 1)
			setDatosEstaticos($pdf, $row, $pagina, $entidad, $vendedor, $ejecutivo, $estudio, $gestor);

		// Muestro el detalle..
		$pdf->SetFont("Arial", "", 7);
		$pdf->SetX(0);
		$pdf->SetY(79 + ($fila * 4));
		$pdf->Cell(0.2);
		$pdf->Cell(13, 0, $rowDetalle["PERIODO"]);

		$pdf->Cell(-2.4);
		$pdf->Cell(15, 0, $rowDetalle["VENCIMIENTO"]);

		$pdf->Cell(-2);
		$pdf->Cell(20, 0, $rowDetalle["FIJA_VARIABLE"], 0, 0, "R");

		$pdf->Cell(9, 0, $rowDetalle["DDJJ"], 0, 0, "C");

		$pdf->Cell(-2);
		$pdf->Cell(12, 0, $rowDetalle["EMPLEADOS"], 0, 0, "R");

		$pdf->Cell(-4);
		$pdf->Cell(22, 0, $rowDetalle["MASA"], 0, 0, "R");

		$pdf->Cell(-1);
		$pdf->Cell(18, 0, $rowDetalle["CUOTA"], 0, 0, "R");

		$pdf->Cell(16, 0, $rowDetalle["FONDO"], 0, 0, "R");

		$pdf->Cell(-3);
		$pdf->Cell(20, 0, $rowDetalle["INTERES_OTROS"], 0, 0, "R");

		$pdf->Cell(-2);
		$pdf->Cell(20, 0, $rowDetalle["PAGOS"], 0, 0, "R");

		$pdf->Cell(20, 0, $rowDetalle["DEUDA_TOTAL"], 0, 0, "R");

		$pdf->Cell(-2);
		$pdf->Cell(18, 0, $rowDetalle["MONTOREFINANCIADO"], 0, 0, "R");

		$pdf->Cell(15, 0, $rowDetalle["IMPORTERECLAMOAFIP"], 0, 0, "R");

		$pdf->Cell(19, 0, $rowDetalle["INTERES"], 0, 0, "R");

		$pdf->Cell(19, 0, $rowDetalle["DEUDA_CONSOLIDADA"], 0, 0, "R");

		$pdf->Cell(3.5);
		$pdf->Cell(8, 0, $rowDetalle["REF"]);

		$pdf->Cell(9, 0, $rowDetalle["CODREG"]);


		$fila++;
		if ($fila == FILAS_POR_HOJAS) {
			$fila = 1;
			$pagina++;
		}
	}

	if ((($fila + count($textoLeyendaFinal)) >= FILAS_POR_HOJAS) or ($fila == 1)) {		// Si la leyenda final no entra o si la última linea se imprimió..
		$fila = 1;
		setDatosEstaticos($pdf, $row, $pagina, $entidad, $vendedor, $ejecutivo, $estudio, $gestor);
	}


	// Muestro los totales..
	$params = array(":contrato" => $_REQUEST["id"]);
	$sql = getSqlDetalle($row, $sqlTotales);
	$stmt = DBExecSql($conn, $sql, $params);
	$rowTotales = DBGetQuery($stmt, 1, false);

	$pdf->SetY(78.6 + ($fila * 4));
	$pdf->SetX(70);

	$pdf->SetFont("Arial", "B", 7);
	$pdf->Cell(16, 0, "TOTALES");

	$pdf->SetFont("Arial", "", 7);
	$pdf->SetLineWidth(0.1);
	$pdf->Line($pdf->GetX() + 4, $pdf->GetY() - 2, 260, $pdf->GetY() - 2);

	$pdf->Cell(22, 0, $rowTotales["CUOTA"], 0, 0, "R");
	$pdf->Cell(-2);
	$pdf->Cell(18, 0, $rowTotales["FONDO"], 0, 0, "R");
	$pdf->Cell(-3);
	$pdf->Cell(20, 0, $rowTotales["INTERES_OTROS"], 0, 0, "R");
	$pdf->Cell(-4);
	$pdf->Cell(22, 0, $rowTotales["PAGOS"], 0, 0, "R");
	$pdf->Cell(20, 0, $rowTotales["DEUDA_TOTAL"], 0, 0, "R");
	$pdf->Cell(-2);
	$pdf->Cell(18, 0, $rowTotales["MONTOREFINANCIADO"], 0, 0, "R");
	$pdf->Cell(-2);
	$pdf->Cell(17, 0, $rowTotales["IMPORTERECLAMOAFIP"], 0, 0, "R");
	$pdf->Cell(-1);
	$pdf->Cell(20, 0, $rowTotales["INTERES"], 0, 0, "R");
	$pdf->Cell(-1);
	$pdf->Cell(20, 0, $rowTotales["DEUDA_CONSOLIDADA"], 0, 0, "R");

	$pdf->Ln(4);
	for ($i=0; $i<count($textoLeyendaFinal); $i++) {
		if ($i > 4)
			break;

		$str = trim($textoLeyendaFinal[$i]);

		$pdf->Cell(60);
		$pdf->Cell(0, 0, $str);
		$pdf->Ln(3);
	}


	$pdf->Output("Estado_de_Cuenta.pdf", "I");
	//	*******  FIN - Armado del reporte..  *******
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>