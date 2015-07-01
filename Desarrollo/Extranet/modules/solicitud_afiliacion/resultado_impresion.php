<?
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function generarFormulario($id, $modulo, &$idFormulario) {
	global $conn;

	if ($modulo == "C")
		$sql =
			"SELECT sa_idformulario, sa_usualta, uw_cuitsuscripcion
				 FROM asa_solicitudafiliacion, afi.auw_usuarioweb
				WHERE DECODE(SUBSTR(sa_usualta, 1, 2), 'W_', SUBSTR(sa_usualta, 3), sa_usualta) = uw_usuario
					AND sa_idsolicitudcotizacion = :id
		 ORDER BY sa_idformulario";
	if ($modulo == "R")
		$sql =
			"SELECT sa_idformulario, sa_usualta, uw_cuitsuscripcion
				 FROM asa_solicitudafiliacion, afi.auw_usuarioweb
				WHERE DECODE(SUBSTR(sa_usualta, 1, 2), 'W_', SUBSTR(sa_usualta, 3), sa_usualta) = uw_usuario
					AND sa_idrevisionprecio = :id
		 ORDER BY sa_idformulario";

	$params = array(":id" => $id);
	$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	$row = DBGetQuery($stmt);

	$cuit = $row["UW_CUITSUSCRIPCION"];
	$idFormulario = $row["SA_IDFORMULARIO"];
	$usuAlta = $row["SA_USUALTA"];

	if (($idFormulario == 0) or ($idFormulario == "")) {		// Si no existe el formulario, lo creo..
		$sql = "SELECT afi.seq_afo_id.NEXTVAL FROM DUAL";
		$idFormulario = valorSql($sql, 0, array(), 0);

		$params = array();
		$sql = "SELECT MAX(fo_formulario) + 1 FROM afo_formulario WHERE fo_cuit IS NOT NULL";
		$nroFormulario = valorSql($sql, 0, array(), 0);

		$params = array(":cuit" => nullIsEmpty($cuit),
										":id" => $idFormulario,
										":formulario" => $nroFormulario,
										":usualta" => $usuAlta);
		$sql =
			"INSERT INTO afo_formulario (fo_cuit, fo_id, fo_formulario, fo_usualta)
													 VALUES (:cuit, :id, :formulario, :usualta)";
		@DBExecSql($conn, $sql, $params, OCI_DEFAULT);		// Oculto el warning porque ocurre con cierta frecuencia..

		if ($modulo == "C") {
			$params = array(":id" => $id, ":idformulario" => $idFormulario);
			$sql =
				"UPDATE asc_solicitudcotizacion
						SET sc_idformulario = :idformulario
					WHERE sc_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			actualizarRankingBNA($id, 0);

			$params = array(":idformulario" => $idFormulario, ":idsolicitud" => $id);
			$sql =
				"UPDATE asa_solicitudafiliacion
						SET sa_idformulario = :idformulario
					WHERE sa_idsolicitudcotizacion = :idsolicitud";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			$params = array(":idformulario" => $idFormulario, ":idsolicitud" => $_REQUEST["idsa"]);
			$sql =
				"UPDATE afi.arp_riesgo_pcp
						SET rp_idformulario = :idformulario
					WHERE rp_idsolicitud = :idsolicitud";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			$params = array(":idformulario" => $idFormulario, ":idsolicitud" => $_REQUEST["idsa"]);
			$sql =
				"UPDATE afi.aap_alicuotas_pcp
						SET ap_idformulario = :idformulario
					WHERE ap_idsolicitud = :idsolicitud";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			$params = array(":idformulario" => $idFormulario, ":idsolicitud" => $_REQUEST["idsa"]);
			$sql =
				"UPDATE afi.alt_lugartrabajo_pcp
						SET lt_idformulario = :idformulario
					WHERE lt_idsolicitud = :idsolicitud";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}

		if ($modulo == "R") {
			$params = array(":idformulario" => $idFormulario, ":id" => $id);
			$sql =
				"UPDATE asr_solicitudreafiliacion
						SET sr_idformulario = :idformulario
					WHERE sr_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			$params = array(":idformulario" => $idFormulario, ":idrevision" => $id);
			$sql =
				"UPDATE asa_solicitudafiliacion
						SET sa_idformulario = :idformulario
					WHERE sa_idrevisionprecio = :idrevision";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
	}

	// Actualizo el idformulario por las dudas..
	if ($modulo == "C") {
		$params = array(":idformulario" => $idFormulario, ":idsolicitudcotizacion" => $id);
		$sql =
			"UPDATE art.apr_polizarc
					SET pr_idformulario = :idformulario
				WHERE pr_idsolicitudafi = (SELECT sa_id
																		 FROM asa_solicitudafiliacion
																		WHERE sa_idsolicitudcotizacion = :idsolicitudcotizacion)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
}

function guardarRegistrosImpresion($tipoPdf, $cantidadHojas, $idEstablecimiento = 0, $establecimientoDeBaja = false) {
	global $conn;

	$params = array(":idestablecimiento" => $idEstablecimiento,
									":idsolicitudafiliacion" => $_REQUEST["idsa"],
									":idtipopdf" => $tipoPdf);
	$sql =
		"SELECT ir_id
			 FROM web.wir_impresionesrgrl
			WHERE ir_idsolicitudafiliacion = :idsolicitudafiliacion
				AND ir_idtipopdf = :idtipopdf
				AND NVL(ir_idestablecimiento, 0) = :idestablecimiento";
	$idTmp = valorSql($sql, 0, $params, 0);

	if ($idTmp < 1) {		// Si es un alta..
		$params = array(":cantidadhojas" => $cantidadHojas,
										":idestablecimiento" => nullIfCero($idEstablecimiento),
										":idsolicitudafiliacion" => $_REQUEST["idsa"],
										":idtipopdf" => $tipoPdf,
										":usualta" => substr($_SESSION["usuario"], 0, 20));
		$sql =
			"INSERT INTO web.wir_impresionesrgrl
									 (ir_cantidadhojas, ir_fechaalta, ir_id, ir_idestablecimiento, ir_idsolicitudafiliacion, ir_idtipopdf, ir_usualta)
						VALUES (:cantidadhojas, SYSDATE, 1, :idestablecimiento, :idsolicitudafiliacion, :idtipopdf, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
	else {
		if ($establecimientoDeBaja) {		// Si el establecimiento está dado de baja..
			$params = array(":id" => $idTmp);
			$sql =
				"SELECT DECODE(ir_fechabaja, NULL, 0, 1)
					 FROM web.wir_impresionesrgrl
					WHERE ir_id = :id";

			// Si el registro de impresión NO está dado de baja..
			if (valorSql($sql, 0, $params, 0) == 0) {
				$params = array(":id" => $idTmp, ":usubaja" => substr($_SESSION["usuario"], 0, 20));
				$sql =
					"UPDATE web.wir_impresionesrgrl
							SET ir_fechabaja = SYSDATE,
									ir_usubaja = :usubaja
						WHERE ir_id = :id";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
		}
		else {		// Sino, es una modificación..
			$params = array(":cantidadhojas" => $cantidadHojas,
											":id" => $idTmp,
											":usumodif" => substr($_SESSION["usuario"], 0, 20));
			$sql =
				"UPDATE web.wir_impresionesrgrl
						SET ir_cantidadhojas = :cantidadhojas,
								ir_fechamodif = SYSDATE,
								ir_usumodif = :usumodif
					WHERE ir_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
	}
}


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idModulo"]);

$id = substr($_REQUEST["idModulo"], 1);
$modulo = substr($_REQUEST["idModulo"], 0, 1);

try {
	$params = array(":id" => $id);
	$sql =
		"SELECT 1
			 FROM asc_solicitudcotizacion
			WHERE sc_cotizacion_pcp = 'S'
				AND sc_id = :id";
	$isSoloPCP = existeSql($sql, $params, 0);

	$idFormulario = 0;
	generarFormulario($id, $modulo, $idFormulario);

	// Solicitud de Afiliación..
	guardarRegistrosImpresion(1, 3);

	// Ubicación de Riesgo..
	if (!$isSoloPCP)
		guardarRegistrosImpresion(2, 1);

	// RGRL..
	if (!$isSoloPCP) {
		$params = array(":idformulario" => $idFormulario);
		$sql =
			"SELECT DISTINCT art.hys.get_idresolucion463(se_id) idres463, se_fechabaja, se_id, se_nroestableci
									FROM asa_solicitudafiliacion, ase_solicitudestablecimiento, hys.hsf_solicitudfgrl, hys.hst_solicituditemsfgrl
								 WHERE sa_id = se_idsolicitud
									 AND se_id = sf_idsolicitudestablecimiento
									 AND sf_id = st_idsolicitudfgrl
									 AND se_fechabaja IS NULL
									 AND sf_fechabaja IS NULL
									 AND st_fechabaja IS NULL
									 AND se_fechabaja IS NULL
									 AND sa_idformulario = :idformulario";
		$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		while ($row = DBGetQuery($stmt)) {
			switch ($row["IDRES463"]) {
				case 1:
					$cantidadHojas = 4;
					break;
				case 2:
					$cantidadHojas = 5;
					break;
				case 3:
					$cantidadHojas = 4;
					break;
			}

			guardarRegistrosImpresion(3, $cantidadHojas, $row["SE_ID"], ($row["SE_FECHABAJA"] != ""));
		}
	}

	// Addenda..
	if ($modulo == "C") {
		$params = array(":idsolicitud" => $id);
		$sql = "SELECT art.cotizacion.get_addenda_bonificacion(:idsolicitud) FROM DUAL";
//	if (ValorSql($sql, 0, $params, 0) == 1)
		if ((valorSql($sql, 0, $params, 0) == 1) or ($id == 323407) or ($id == 327607))
			guardarRegistrosImpresion(4, 1);
	}

	// Responsabilidad Civil..
	$params = array(":idsolicitudafi" => $_REQUEST["idsa"]);
	$sql =
		"SELECT 1
			 FROM art.apr_polizarc
			WHERE pr_poliza = 'S'
				AND pr_idsolicitudafi = :idsolicitudafi";
	$generarResponsabilidadCivil = existeSql($sql, $params, 0);
	$generarResponsabilidadCivil = (($generarResponsabilidadCivil) and ($_SESSION["entidad"] != 400) and ($_SESSION["entidad"] != 10891));		// Si no es del Banco Nación, ni CPCECABA..
	if ($generarResponsabilidadCivil)
		guardarRegistrosImpresion(5, 1);

	// PEPs..
	guardarRegistrosImpresion(6, 1);

	// Exposición Riesgos Químicos..
	if (!$isSoloPCP) {
		$params = array(":idformulario" => $idFormulario);
		$sql =
			"SELECT DISTINCT se_fechabaja, se_id, se_nroestableci
									FROM asa_solicitudafiliacion, ase_solicitudestablecimiento
								 WHERE sa_id = se_idsolicitud
									 AND se_fechabaja IS NULL
									 AND se_fechabaja IS NULL
									 AND sa_idformulario = :idformulario";
		$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		while ($row = DBGetQuery($stmt)) {
			guardarRegistrosImpresion(7, 1, $row["SE_ID"], ($row["SE_FECHABAJA"] != ""));
		}
	}

	// Ventanilla Electrónica..
//***  Se comenta por pedido hecho en ticket 33082..  ***
//	guardarRegistrosImpresion(8, 1);

	// Nómina Personal Expuesto..
	if (!$isSoloPCP)
		guardarRegistrosImpresion(9, 1);


	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
	<script type="text/javascript">
		function mostrarBoton() {
			document.getElementById('btnReintentarImpresion').style.visibility = 'visible';
		}

		setTimeout('mostrarBoton()', 10000);
	</script>
	<div style="margin-bottom:16px; margin-top:8px;">En estos momentos el servidor se encuentra congestionado y la solicitud de afiliación no puede ser impresa, por favor intente en unos minutos.</div>
	<input class="btnReintentarImpresion" id="btnReintentarImpresion" style="visibility:hidden; margin-top:16px;" type="button" value="" onClick="window.location.href = window.location.href;">
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
	<script type="text/javascript">
		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	</script>
<?
	exit;
}
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/solicitud_afiliacion/resultado_impresion_busqueda.php" id="formResultadoImpresion" method="post" name="formResultadoImpresion" target="iframeProcesando">
	<input id="idModulo" name="idModulo" type="hidden" value="<?= $_REQUEST["idModulo"]?>" />
	<input id="idsa" name="idsa" type="hidden" value="<?= $_REQUEST["idsa"]?>" />
	<div align="center" class="TituloSeccion">Solicitud de Afiliación - Listado de PDFs a imprimir</div>
	<div>
		<div align="center" id="divContentGrid" name="divContentGrid" style="height:360px; left:0px; top:40px; width:736px;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<div style="color:#676767; font-size:8pt; margin-left:8px;">
			<span>Los archivos deben visualizarse utilizando el Acrobat Reader.</span>
			<br />
			<span>Haga clic sobre el ícono de Adobe para descargar el programa.</span>
		</div>
		<div style="margin-left:400px; margin-top:-30px;">
			<a href="http://get.adobe.com/es/reader/otherversions/" target="_blank"><img border="0" src="/images/get_acrobat.bmp"></a>
		</div>
		<p style="margin-left:656px; margin-top:4px;">
			<input class="btnVolver" type="button" value="" onClick="window.location.href='/solicitud-afiliacion/<?= $_REQUEST["idModulo"]?>'" />
		</p>
	</div>
</form>
<script type="text/javascript">
	document.getElementById('formResultadoImpresion').submit();
</script>