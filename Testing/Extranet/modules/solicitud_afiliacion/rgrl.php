<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function dibujarPlanilla($idTipoFormaAnexo, $id, $width) {
	global $conn;

	$params = array(":id" => $idTipoFormaAnexo);
	$sql =
		"SELECT 'PLANILLA ' || DECODE(ta_id, 1, 'A', 2, 'B', 3, 'C') || ' | ' || ta_descripcion
			 FROM hys.hta_tipoanexo
			WHERE ta_id = :id";
	$titulo = ValorSql($sql, "", $params);
	$planillaC = (substr($titulo, 0, 10) == "PLANILLA C");
?>
	<div class="SubtituloSeccion" id="divPlanilla_<?= $id?>" style="display:none; margin-bottom:16px; margin-top:-16px;">
		<table cellpadding="3" cellspacing="1" style="width:<?= $width?>px;">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="TituloFndCeleste" style="padding-left:4px;"><?= $titulo?></td>
			</tr>
			<tr>
				<td>
					<table cellpadding="3" cellspacing="1" width="100%">
						<tr>
							<td class="TituloFndGrisNegrita">DESCRIPCIÓN</td>
<?
	if ($planillaC) {
?>
							<td class="TituloFndGrisNegrita" width="120">CANTIDAD UMBRAL (TN)</td>
<?
	}
?>
							<td class="TituloFndGrisNegrita">SÍ</td>
							<td class="TituloFndGrisNegrita">NO</td>
						</tr>
<?
	$params = array(":idsolicitudestablecimiento" => $_REQUEST["idEstablecimiento"],
									":idtipoanexo" => $idTipoFormaAnexo,
									":idtipoanexo2" => $idTipoFormaAnexo);
	$sql =
		"SELECT 1
			 FROM hys.hit_itemtipoanexo, hys.hsi_solicituditemsplanillafgrl, hys.hsp_solicitudplanillafgrl, hys.hsf_solicitudfgrl
			WHERE si_iditemtipoanexo = it_id
				AND si_idsolicitudplanillafgrl = sp_id
				AND sp_idsolicitudfgrl = sf_id
				AND it_idtipoanexo = :idtipoanexo
				AND sp_idtipoanexo = :idtipoanexo2
				AND sf_idsolicitudestablecimiento = :idsolicitudestablecimiento";

	if (ExisteSql($sql, $params)) {
		$params = array(":idsolicitudestablecimiento" => $_REQUEST["idEstablecimiento"], ":idtipoanexo" => $idTipoFormaAnexo);
		$sql =
			"SELECT sp_id
				 FROM hys.hsp_solicitudplanillafgrl, hys.hsf_solicitudfgrl
				WHERE sp_idsolicitudfgrl = sf_id
					AND sp_idtipoanexo = :idtipoanexo
					AND sf_idsolicitudestablecimiento = :idsolicitudestablecimiento";
		$idSolicitudPlanilla = ValorSql($sql, "", $params);

		$params = array(":idsolicitudplanillafgrl" => $idSolicitudPlanilla, ":idtipoanexo" => $idTipoFormaAnexo);
		$sql =
			"SELECT it_id, it_descripcion, it_masdatos, si_cumplimiento
				 FROM hys.hit_itemtipoanexo, hys.hsi_solicituditemsplanillafgrl
				WHERE it_id = si_iditemtipoanexo(+)
					AND si_idsolicitudplanillafgrl(+) = :idsolicitudplanillafgrl
					AND it_idtipoanexo = :idtipoanexo
		 ORDER BY it_orden";
	}
	else {
		$params = array(":idtipoanexo" => $idTipoFormaAnexo);
		$sql =
			"SELECT it_id, it_descripcion, it_masdatos, NULL si_cumplimiento
				 FROM hys.hit_itemtipoanexo
				WHERE it_idtipoanexo = :idtipoanexo
		 ORDER BY it_orden";
	}
	$stmt3 = DBExecSql($conn, $sql, $params);
	while ($row3 = DBGetQuery($stmt3)) {
?>
						<input id="Hextra_<?= $row3["IT_ID"]?>_pregunta_<?= $id?>" name="Hextra_<?= $row3["IT_ID"]?>_pregunta_<?= $id?>" type="hidden" value="<?= $row3["IT_ID"]?>" />
						<tr>
							<td class="TxtPregunta"><?= $row3["IT_DESCRIPCION"]?></font></td>
<?
		if ($planillaC) {
?>
							<td class="TxtPregunta"><?= $row3["IT_MASDATOS"]?></font></td>
<?
		}
?>
							<td class="Pregunta"><input <?= ($row3["SI_CUMPLIMIENTO"] == "S")?"checked":""?> id="extra_<?= $row3["IT_ID"]?>" name="extra_<?= $row3["IT_ID"]?>" type="radio" value="S"></td>
							<td class="Pregunta"><input <?= ($row3["SI_CUMPLIMIENTO"] == "N")?"checked":""?> id="extra_<?= $row3["IT_ID"]?>" name="extra_<?= $row3["IT_ID"]?>" type="radio" value="N"></td>
						</tr>
<?
	}
?>
					</table>
				</td>
			</tr>
		</table>
	</div>
<?
}


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idModulo"]);

$sql = "SELECT art.hys.get_idresolucion463(".$_REQUEST["idEstablecimiento"].") FROM DUAL";
$params = array();
if (ValorSql($sql, "", $params) == "") {
	echo 'A este establecimiento no corresponde que se le carguen datos sobre el RGRL.<p><a href="#" onClick="window.parent.parent.divWin.close();">Cerrar</a></p>';
	exit;
}

SetDateFormatOracle("DD/MM/YYYY");

$params = array(":id" => $_REQUEST["idEstablecimiento"]);
$sql =
	"SELECT 1
		 FROM comunes.cac_actividad, hys.hpa_preguntaadicional hpa, ase_solicitudestablecimiento
		WHERE SUBSTR(ac_relacion, 1, LENGTH(pa_ciiuviejo)) = pa_ciiuviejo
			AND ac_id = se_idactividad
			AND pa_idresolucion = art.hys.get_idresolucion463(se_id)
			AND se_id = :id";
$mostrarPreguntasAdicionales = ExisteSql($sql, $params);

if ($mostrarPreguntasAdicionales) {
	$params = array(":idsolicitudestablecimiento" => $_REQUEST["idEstablecimiento"]);
	$sql =
		"SELECT 1
			 FROM hys.hra_respuestaadicional
			WHERE ra_idsolicitudestablecimiento = :idsolicitudestablecimiento";
	$mostrarPreguntasAdicionales = (!ExisteSql($sql, $params));
}

// Traigo los datos de la cabecera..
$params = array(":id" => $_REQUEST["idEstablecimiento"]);
$sql =
	"SELECT cac2.ac_codigo ciiuempresa, cac1.ac_codigo ciiuestablecimiento, art.utiles.armar_cuit(sa_cuit) cuit,
					art.utiles.armar_domicilio(se_calle, se_numero, se_piso, se_departamento, NULL) domicilio, pv_descripcion, sa_nombre, se_cpostala, se_descripcionactividad, se_empleados,
					se_idactividad, se_localidad, se_nroestableci, se_superficie, art.utiles.armar_telefono(se_codareatelefonos, NULL, se_telefonos) telefono
		 FROM asa_solicitudafiliacion, ase_solicitudestablecimiento, cac_actividad cac1, cac_actividad cac2, cpv_provincias
		WHERE sa_id = se_idsolicitud
			AND se_idactividad = cac1.ac_id
			AND sa_idactividad = cac2.ac_id
			AND se_provincia = pv_codigo(+)
			AND se_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<html>
	<head>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<link rel="stylesheet" href="/styles/design.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<style type="text/css"> 
			* {
				margin: 0;
				padding: 0;
			}

			html, body {
				background-color: #FFF;
				overflow: auto;
				text-align: left;
			}

			.fecha {
				border-bottom-style: solid; 
				border-bottom-width: 1px; 
				border-color: #BCBCBC;
				padding-bottom: 1px;	
				padding-left: 4px; 
				padding-right: 4px; 
				padding-top: 1px; 
				text-align: center;
				width: 120px;
			}
		</style>
		<script src="/js/functions.js?rnd=20131113" type="text/javascript"></script>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script language="JavaScript" src="/modules/solicitud_afiliacion/js/afiliacion.js?rnd=20131113"></script>

		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/solicitud_afiliacion/procesar_rgrl.php" id="formRGRL" method="post" name="formRGRL" target="iframeProcesando">
			<input id="idEstablecimiento" name="idEstablecimiento" type="hidden" value="<?= $_REQUEST["idEstablecimiento"]?>">
			<input id="idModulo" name="idModulo" type="hidden" value="<?= $_REQUEST["idModulo"]?>">
			<input id="preguntasAdicionales" name="preguntasAdicionales" type="hidden" value="<?= ($mostrarPreguntasAdicionales)?"t":"f"?>">
			<div id="divContentIn">
				<table cellpadding="4" cellspacing="0" style="margin-bottom:4px; margin-top:8px;">
					<tr>
						<td class="TituloFndCeleste" style="height:18px; padding-left:4px;">DATOS GENERALES DEL ESTABLECIMIENTO</td>
					</tr>
					<tr>
						<td class="item"><b>Nombre de la empresa: </b><i><?= $row["SA_NOMBRE"]?></i><span style="margin-left: 10%;"><b>C.U.I.T./C.U.I.P. Nº: </b><i><?= $row["CUIT"]?></i></span></td>
					</tr>
					<tr>
						<td class="item">Nº de establecimiento: <i><?= $row["SE_NROESTABLECI"]?></i><span style="margin-left: 5%;">C.I.I.U. (Actividad económica Revisión 3) </span><i><?= $row["CIIUESTABLECIMIENTO"]?></i></td>
					</tr>
					<tr>
						<td class="item">Superficie del establecimiento en m<sup>2</sup>: </span><i><?= $row["SE_SUPERFICIE"]?></i></td>
					</tr>
					<tr>
						<td class="item">Código de actividad según Clasificador de Actividades Económicas (CLAE) - Formulario Nº 883 (Resolución A.F.I.P. Nº 3537): <i><?= $row["CIIUEMPRESA"]?></i><span style="margin-left: 5%;">Cantidad de trabajadores: </span><i><?= $row["SE_EMPLEADOS"]?></i></td>
					</tr>
					<tr>
						<td class="item">Breve descripción de la actividad: <i><?= $row["SE_DESCRIPCIONACTIVIDAD"]?></i></td>
					</tr>
					<tr>
						<td class="item">Domicilio: <i><?= $row["DOMICILIO"]?></i></td>
					</tr>
					<tr>
						<td class="item">Provincia: <i><?= $row["PV_DESCRIPCION"]?></i><span style="margin-left: 5%;">Código Postal Argentino: </span><i><?= $row["SE_CPOSTALA"]?></i><span style="margin-left: 5%;">Localidad: </span><i><?= $row["SE_LOCALIDAD"]?></i><span style="margin-left: 5%;">Teléfono: </span><i><?= $row["TELEFONO"]?></i></td>
					</tr>
					<tr>
						<td class="pieTabla">x</td>
					</tr>
				</table>
			</div>
<?
if ($mostrarPreguntasAdicionales) {
	$params = array(":idestablecimiento" => $_REQUEST["idEstablecimiento"], ":idactividad" => $row["SE_IDACTIVIDAD"]);
	$sql =
		"SELECT pa_id, pa_idtipoformanexo, pa_pregunta
			 FROM comunes.cac_actividad, hys.hpa_preguntaadicional hpa
			WHERE pa_idresolucion = art.hys.get_idresolucion463(:idestablecimiento)
				AND SUBSTR(ac_relacion, 1, LENGTH(pa_ciiuviejo)) = pa_ciiuviejo
				AND ac_id = :idactividad";
	$stmt2 = DBExecSql($conn, $sql, $params);
	while ($row2 = DBGetQuery($stmt2)) {
?>
		<p class="SubtituloSeccion">
			<input id="Hpregunta_<?= $row2["PA_ID"]?>" name="Hpregunta_<?= $row2["PA_ID"]?>" type="hidden" value="<?= $row2["PA_ID"]?>" />
			<table cellpadding="3" cellspacing="1" style="width:590px;">
				<tr>
					<td colspan="3" height="10"></td>
				</tr>
				<tr>
<?
		if ($row2["PA_IDTIPOFORMANEXO"] == "")
			echo "<td></td>";
		else {
?>
			<input id="Hplanilla_pregunta_<?= $row2["PA_ID"]?>" name="Hplanilla_pregunta_<?= $row2["PA_ID"]?>" type="hidden" value="<?= $row2["PA_ID"]?>" />
			<td><span id="btnExpandir_<?= $row2["PA_ID"]?>" style="background-color:#c2d560; cursor:hand; display:none; font-size:12px; width:64px;" onClick="expandirPlanilla(<?= $row2["PA_ID"]?>)">Expandir</span></td>
<?
		}
?>
					<td class="TituloFndGrisNegrita">SÍ</td>
					<td class="TituloFndGrisNegrita">NO</td>
				</tr>
				<tr>
					<td class="TxtPregunta"><?= $row2["PA_PREGUNTA"]?></font></td>
					<td class="Pregunta"><input id="pregunta_<?= $row2["PA_ID"]?>" name="pregunta_<?= $row2["PA_ID"]?>" type="radio" value="S" onClick="clicPregunta(<?= $row2["PA_ID"]?>, 'si')"></td>
					<td class="Pregunta"><input id="pregunta_<?= $row2["PA_ID"]?>" name="pregunta_<?= $row2["PA_ID"]?>" type="radio" value="N" onClick="clicPregunta(<?= $row2["PA_ID"]?>, 'no')"></td>
				</tr>
			</table>
		</p>
<?
		if ($row2["PA_IDTIPOFORMANEXO"] != "")
			dibujarPlanilla($row2["PA_IDTIPOFORMANEXO"], $row2["PA_ID"], 590);
	}
}
else {
	$params = array(":idestablecimiento" => $_REQUEST["idEstablecimiento"]);
	$sql =
		"SELECT ra_titulo, ra_descripcion, ra_header
			 FROM hys.hra_resolucionanexo
			WHERE ra_fechabaja IS NULL
				AND ra_id = art.hys.get_idresolucion463(:idestablecimiento)";
	$stmt2 = DBExecSql($conn, $sql, $params);
	$row2 = DBGetQuery($stmt2);
?>
<p class="SubtituloSeccion">
<table cellpadding="3" cellspacing="1" style="width:590px;">
	<tr>
		<td colspan="6" height="10"></td>
	</tr>
	<tr>
		<td colspan="6" class="TituloGris"><?= $row2["RA_TITULO"]?></td>
	</tr>
	<tr>
		<td colspan="6" class="TituloGris">ESTADO DE CUMPLIMIENTO EN EL ESTABLECIMIENTO DE LA NORMATIVA VIGENTE (<?= $row2["RA_DESCRIPCION"]?>)</td>
	</tr>
	<tr>
		<td class="TituloFndGrisNegrita">Nº</td>
		<td class="TituloFndGrisNegrita"><?= $row2["RA_HEADER"]?>: CONDICIONES A CUMPLIR</td>
		<td class="TituloFndGrisNegrita">SÍ</td>
		<td class="TituloFndGrisNegrita">NO</td>
		<td class="TituloFndGrisNegrita">NO <br>APLICA</td>
		<td class="TituloFndGrisNegrita">FECHA <br>(regularización)</td>
	</tr>
<?
	$params = array(":idsolicitudestablecimiento" => $_REQUEST["idEstablecimiento"]);
	$sql =
		"SELECT 1
			 FROM hys.hst_solicituditemsfgrl, hys.hsf_solicitudfgrl
			WHERE st_idsolicitudfgrl = sf_id
				AND sf_idsolicitudestablecimiento = :idsolicitudestablecimiento";
	$itemsCargados = ExisteSql($sql, $params);

	$params = array(":idestablecimiento" => $_REQUEST["idEstablecimiento"]);
	$sql =
		"SELECT ta_id, ta_nrotitulo, ta_descripcion
			 FROM hys.hta_titulosanexo
			WHERE ta_idresolucionanexo = art.hys.get_idresolucion463(:idestablecimiento)
				AND ta_fechabaja IS NULL
	 ORDER BY ta_nrotitulo";
	$stmt2 = DBExecSql($conn, $sql, $params);
	while ($row2 = DBGetQuery($stmt2)) {
?>
	<tr>
		<td class="TituloFndGris"></td>
		<td class="TituloFndGris" colspan="5"><?= $row2["TA_DESCRIPCION"]?></td>
	</tr>
<?
		if ($itemsCargados) {
			$params = array(":idsolicitudestablecimiento" => $_REQUEST["idEstablecimiento"], ":idtituloanexo" => $row2["TA_ID"]);
			$sql =
				"SELECT ia_id, ia_nrodescripcion, ia_descripcion, ia_idtipoformanexo, st_cumplimiento, st_fecharegularizacion
					 FROM hys.hia_itemanexo, hys.hst_solicituditemsfgrl, hys.hsf_solicitudfgrl
					WHERE ia_id = st_iditem(+)
						AND st_idsolicitudfgrl = sf_id(+)
						AND ia_idtituloanexo = :idtituloanexo
						AND ia_fechabaja IS NULL
						AND NVL(ia_sololectura, 'N') <> 'S'
						AND sf_idsolicitudestablecimiento = :idsolicitudestablecimiento
			 ORDER BY ia_nrodescripcion";
		}
		else {
			$params = array(":idtituloanexo" => $row2["TA_ID"]);
			$sql =
				"SELECT ia_id, ia_nrodescripcion, ia_descripcion, ia_idtipoformanexo, NULL st_cumplimiento, NULL st_fecharegularizacion
					 FROM hys.hia_itemanexo
					WHERE ia_idtituloanexo = :idtituloanexo
						AND ia_fechabaja IS NULL
						AND NVL(ia_sololectura, 'N') <> 'S'
			 ORDER BY ia_nrodescripcion";
		}

		$stmt3 = DBExecSql($conn, $sql, $params);
		while ($row3 = DBGetQuery($stmt3)) {
			$valorItem = $row3["ST_CUMPLIMIENTO"];
			if ($valorItem == "") {
				$params = array(":idestablecimiento" => $_REQUEST["idEstablecimiento"], ":iditem" => $row3["IA_ID"]);
				$valorItem = ValorSql("SELECT art.hys.get_marcaitemdefault(:idestablecimiento, :iditem) FROM DUAL", "", $params);
			}

			$fecha = "";
			if ($valorItem == "N") {
				$fecha = $row3["ST_FECHAREGULARIZACION"];
				if ($fecha == "") {
					$params = array(":idestablecimiento" => $_REQUEST["idEstablecimiento"], ":iditem" => $row3["IA_ID"]);
					$fecha = ValorSql("SELECT art.hys.get_fregularizaciondefault(:idestablecimiento, :iditem) FROM DUAL", "", $params);
				}
			}
?>
	<input id="Hpregunta_<?= $row3["IA_ID"]?>" name="Hpregunta_<?= $row3["IA_ID"]?>" type="hidden" value="<?= $row3["IA_ID"]?>" />
	<tr>
		<td class="Pregunta">
<?
			if ($row3["IA_IDTIPOFORMANEXO"] != "") {
?>
			<input id="Hplanilla_pregunta_<?= $row3["IA_ID"]?>" name="Hplanilla_pregunta_<?= $row3["IA_ID"]?>" type="hidden" value="<?= $row3["IA_ID"]?>" />
			<span id="btnExpandir_<?= $row3["IA_ID"]?>" style="background-color:#c2d560; cursor:hand; display:none; font-size:12px; width:64px;" onClick="expandirPlanilla(<?= $row3["IA_ID"]?>)">Expandir</span>
<?
			}
?>
		<?= $row3["IA_NRODESCRIPCION"]?></td>
		<td class="TxtPregunta"><?= $row3["IA_DESCRIPCION"]?></font></td>
		<td class="Pregunta"><input <?= ($valorItem == "S")?"checked":""?> id="pregunta_<?= $row3["IA_ID"]?>" name="pregunta_<?= $row3["IA_ID"]?>" type="radio" value="S" onClick="clicItemPregunta(<?= $_REQUEST["idEstablecimiento"]?>, <?= $row3["IA_ID"]?>, this.value, true)"></td>
		<td class="Pregunta"><input <?= ($valorItem == "N")?"checked":""?> id="pregunta_<?= $row3["IA_ID"]?>" name="pregunta_<?= $row3["IA_ID"]?>" type="radio" value="N" onClick="clicItemPregunta(<?= $_REQUEST["idEstablecimiento"]?>, <?= $row3["IA_ID"]?>, this.value, true)"></td>
		<td class="Pregunta"><input <?= ($valorItem == "X")?"checked":""?> id="pregunta_<?= $row3["IA_ID"]?>" name="pregunta_<?= $row3["IA_ID"]?>" type="radio" value="X" onClick="clicItemPregunta(<?= $_REQUEST["idEstablecimiento"]?>, <?= $row3["IA_ID"]?>, this.value, true)"></td>
		<td class="Fecha">
<?
			if ($valorItem == "N") {
?>
			<input id="fecha_<?= $row3["IA_ID"]?>" maxlength="10" name="fecha_<?= $row3["IA_ID"]?>" style="width:76px;" type="text" value="<?= $fecha?>">
			<input class="botonFecha" id="btnFecha<?= $row3["IA_ID"]?>" name="btnFecha<?= $row3["IA_ID"]?>" style="vertical-align:-4px;" type="button" value="">
			<input id="fechaD_<?= $row3["IA_ID"]?>" maxlength="10" name="fechaD_<?= $row3["IA_ID"]?>" readonly style="background-color:#ccc; display:none; width:76px;" type="text" value="">
			<input class="botonFechaDeshabilitado" id="btnFechaD<?= $row3["IA_ID"]?>" name="btnFechaD<?= $row3["IA_ID"]?>" style="display:none; vertical-align:-4px;" type="button" value="">
<?
			}
			else {
?>
			<input id="fecha_<?= $row3["IA_ID"]?>" maxlength="10" name="fecha_<?= $row3["IA_ID"]?>" style="display:none; width:76px;" type="text" value="<?= $fecha?>">
			<input class="botonFecha" id="btnFecha<?= $row3["IA_ID"]?>" name="btnFecha<?= $row3["IA_ID"]?>" style="display:none; vertical-align:-4px;" type="button" value="">
			<input id="fechaD_<?= $row3["IA_ID"]?>" maxlength="10" name="fechaD_<?= $row3["IA_ID"]?>" readonly style="background-color:#ccc; width:76px;" type="text" value="">
			<input class="botonFechaDeshabilitado" id="btnFechaD<?= $row3["IA_ID"]?>" name="btnFechaD<?= $row3["IA_ID"]?>" style="vertical-align:-4px;" type="button" value="">
<?
			}
?>
		</td>
	</tr>
	<script type="text/javascript">
		Calendar.setup (
			{
				inputField: "fecha_<?= $row3["IA_ID"]?>",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFecha<?= $row3["IA_ID"]?>"
			}
		);
	</script>
<?
			if ($row3["IA_IDTIPOFORMANEXO"] != "") {
				echo '<tr><td colspan="6">';
				dibujarPlanilla($row3["IA_IDTIPOFORMANEXO"], $row3["IA_ID"], 544);
				echo "</td></tr>";

				if ($valorItem == "S")
					echo "<script type='text/javascript'>clicItemPregunta(".$_REQUEST["idEstablecimiento"].", ".$row3["IA_ID"].", 'S', false);</script>";
			}
		}
	}
?>
</table>
</p>
<?
}
?>
			<div style="margin-bottom:8px; margin-left:16px; margin-top:16px;">
				<input class="btnGrabar" id="btnGrabar" type="submit" value="" onClick="document.getElementById('btnGrabar').style.display = 'none'; document.getElementById('spanProcesando').style.display = 'block';" />
				<span id="spanProcesando" style="color:#0087c4; display:none; font-size:12px; margin-bottom:16px;">Procesando, aguarde un instante por favor...</span>
			</div>
		</form>
		<p id="guardadoOk" style="background:#0f539c; color:#fff; display:none; margin-left:16px; margin-top:8px; padding:2px; width:280px;">&nbsp;Datos guardados exitosamente.</p>
		<div id="divErrores" style="display:none;">
			<table border="1" bordercolor="#ff0000" align="center" cellpadding="6" cellspacing="0">
				<tr>
					<td>
						<table cellpadding="4" cellspacing="0">
							<tr>
								<td><img border="0" src="/images/atencion.jpg"></td>
								<td class="ContenidoSeccion">
									<font color="#000000">
										No es posible continuar mientras no se corrijan los siguientes errores:<br /><br />
										<span id="errores"></span>
									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<input id="foco" name="foco" readonly style="height:1px; width:1px;" type="checkbox" />
	</body>
</html>