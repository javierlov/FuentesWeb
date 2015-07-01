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
	$params = array(":contrato" => $_REQUEST["contrato"],
									":idsolicitudestablecimiento" => $_REQUEST["nro"],
									":idtipoanexo" => $idTipoFormaAnexo,
									":idtipoanexo2" => $idTipoFormaAnexo);
	$sql =
		"SELECT 1
			 FROM hys.hit_itemtipoanexo, hys.hif_itemsformulariorelev, hys.hfr_formulariorelev, hys.hrl_relevriesgolaboral
			WHERE if_iditemtipoanexo = it_id
				AND if_idformulariorelev = fr_id
				AND fr_idrelevriesgolaboral = rl_id
				AND it_idtipoanexo = :idtipoanexo
				AND fr_idtipoanexo = :idtipoanexo2
				AND rl_contrato = :contrato
				AND rl_idsolicitudestablecimiento = :idsolicitudestablecimiento";

	if (ExisteSql($sql, $params)) {
		$params = array(":contrato" => $_REQUEST["contrato"],
										":estableci" => $_REQUEST["nro"],
										":idtipoanexo" => $idTipoFormaAnexo);
		$sql =
			"SELECT fr_id
				 FROM hys.hfr_formulariorelev, hys.hrl_relevriesgolaboral
				WHERE fr_idrelevriesgolaboral = rl_id
					AND fr_idtipoanexo = :idtipoanexo
					AND rl_contrato = :contrato
					AND rl_estableci = :estableci";
		$idSolicitudPlanilla = ValorSql($sql, "", $params);

		$params = array(":idformulariorelev" => $idSolicitudPlanilla, ":idtipoanexo" => $idTipoFormaAnexo);
		$sql =
			"SELECT it_id, it_descripcion, it_masdatos, if_cumplimiento
				 FROM hys.hit_itemtipoanexo, hys.hif_itemsformulariorelev
				WHERE it_id = if_iditemtipoanexo(+)
					AND if_idformulariorelev(+) = :idformulariorelev
					AND it_idtipoanexo = :idtipoanexo
		 ORDER BY it_orden";
	}
	else {
		$params = array(":idtipoanexo" => $idTipoFormaAnexo);
		$sql =
			"SELECT it_id, it_descripcion, it_masdatos, NULL if_cumplimiento
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
							<td class="Pregunta"><input <?= ($row3["IF_CUMPLIMIENTO"] == "S")?"checked":""?> id="extra_<?= $row3["IT_ID"]?>" name="extra_<?= $row3["IT_ID"]?>" type="radio" value="S"></td>
							<td class="Pregunta"><input <?= ($row3["IF_CUMPLIMIENTO"] == "N")?"checked":""?> id="extra_<?= $row3["IT_ID"]?>" name="extra_<?= $row3["IT_ID"]?>" type="radio" value="N"></td>
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
validarSesion((validarContrato($_REQUEST["contrato"])));

SetDateFormatOracle("DD/MM/YYYY");

$params = array(":id" => $_REQUEST["nro"]);
$sql = "SELECT es_nroestableci FROM aes_establecimiento WHERE es_id = :id";
$numeroEstablecimiento = ValorSql($sql, "", $params);

// Traigo los datos de la cabecera..
$params = array(":id" => $_REQUEST["nro"]);
$sql =
	"SELECT cac2.ac_codigo ciiuempresa, cac1.ac_codigo ciiuestablecimiento, art.utiles.armar_cuit(em_cuit) cuit,
					art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, NULL) domicilio, em_nombre, es_cpostala, es_descripcionactividad, es_empleados, es_idactividad,
					es_localidad, es_nroestableci, es_superficie, pv_descripcion, art.utiles.armar_telefono(es_codareatelefonos, NULL, es_telefonos) telefono
		 FROM aco_contrato, aem_empresa, aes_establecimiento, cac_actividad cac1, cac_actividad cac2, cpv_provincias
		WHERE co_idempresa = em_id
			AND co_contrato = es_contrato
		  AND es_idactividad = cac1.ac_id
		  AND es_idactividad = cac2.ac_id
		  AND es_provincia = pv_codigo(+)
		  AND es_id = :id";
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
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script language="JavaScript" src="/modules/usuarios_registrados/agentes_comerciales/contratos_activos/js/contratos_activos.js"></script>

		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/usuarios_registrados/agentes_comerciales/contratos_activos/procesar_rgrl.php" id="formRGRL" method="post" name="formRGRL" target="iframeProcesando">
			<input id="nro" name="nro" type="hidden" value="<?= $_REQUEST["nro"]?>">
			<input id="contrato" name="contrato" type="hidden" value="<?= $_REQUEST["contrato"]?>">
			<div id="divContentIn">
				<table cellpadding="4" cellspacing="0" style="margin-bottom:4px; margin-top:8px;">
					<tr>
						<td class="TituloFndCeleste" style="height:18px; padding-left:4px;">DATOS GENERALES DEL ESTABLECIMIENTO</td>
					</tr>
					<tr>
						<td class="item"><b>Nombre de la empresa: </b><i><?= $row["EM_NOMBRE"]?></i><span style="margin-left: 10%;"><b>C.U.I.T./C.U.I.P. Nº: </b><i><?= $row["CUIT"]?></i></span></td>
					</tr>
					<tr>
						<td class="item">Nº de establecimiento: <i><?= $row["ES_NROESTABLECI"]?></i><span style="margin-left: 5%;">C.I.I.U. (Actividad económica Revisión 3) </span><i><?= $row["CIIUESTABLECIMIENTO"]?></i></td>
					</tr>
					<tr>
						<td class="item">Superficie del establecimiento en m<sup>2</sup>: </span><i><?= $row["ES_SUPERFICIE"]?></i></td>
					</tr>
					<tr>
						<td class="item">Código de actividad según Clasificador de Actividades Económicas (CLAE) - Formulario Nº 883 (Resolución A.F.I.P. Nº 3537) <i><?= $row["CIIUEMPRESA"]?></i><span style="margin-left: 5%;">Cantidad de trabajadores: </span><i><?= $row["ES_EMPLEADOS"]?></i></td>
					</tr>
					<tr>
						<td class="item">Breve descripción de la actividad: <i><?= $row["ES_DESCRIPCIONACTIVIDAD"]?></i></td>
					</tr>
					<tr>
						<td class="item">Domicilio: <i><?= $row["DOMICILIO"]?></i></td>
					</tr>
					<tr>
						<td class="item">Provincia: <i><?= $row["PV_DESCRIPCION"]?></i><span style="margin-left: 5%;">Código Postal Argentino: </span><i><?= $row["ES_CPOSTALA"]?></i><span style="margin-left: 5%;">Localidad: </span><i><?= $row["ES_LOCALIDAD"]?></i><span style="margin-left: 5%;">Teléfono: </span><i><?= $row["TELEFONO"]?></i></td>
					</tr>
					<tr>
						<td class="pieTabla">x</td>
					</tr>
				</table>
			</div>
<?
$params = array(":nro" => $_REQUEST["nro"]);
$sql =
	"SELECT ra_titulo, ra_descripcion, ra_header
		 FROM hys.hra_resolucionanexo
		WHERE ra_fechabaja IS NULL
			AND ra_id = art.hys.get_idresolucion463(:nro)";
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
$params = array(":contrato" => $_REQUEST["contrato"], ":estableci" => $numeroEstablecimiento);
$sql =
	"SELECT 1
		 FROM hys.hil_itemsriesgolaboral, hys.hrl_relevriesgolaboral
		WHERE il_idrelevriesgolaboral = rl_id
			AND rl_contrato = :contrato
			AND rl_estableci = :estableci";
$itemsCargados = ExisteSql($sql, $params);

$params = array(":nro" => $_REQUEST["nro"]);
$sql =
	"SELECT ta_id, ta_nrotitulo, ta_descripcion
		 FROM hys.hta_titulosanexo
		WHERE ta_idresolucionanexo = art.hys.get_idresolucion463(:nro)
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
		$params = array(":contrato" => $_REQUEST["contrato"],
										":estableci" => $numeroEstablecimiento,
										":idtituloanexo" => $row2["TA_ID"]);
		$sql =
			"SELECT ia_id, ia_nrodescripcion, ia_descripcion, ia_idtipoformanexo, il_cumplimiento, il_fecharegularizacion
				 FROM hys.hia_itemanexo, hys.hil_itemsriesgolaboral, hys.hrl_relevriesgolaboral
				WHERE ia_id = il_iditemanexo(+)
					AND il_idrelevriesgolaboral = rl_id(+)
					AND ia_idtituloanexo = :idtituloanexo
					AND ia_fechabaja IS NULL
					AND NVL(ia_sololectura, 'N') <> 'S'
					AND rl_contrato = :contrato
					AND rl_estableci = :estableci
		 ORDER BY ia_nrodescripcion";
	}
	else {
		$params = array(":idtituloanexo" => $row2["TA_ID"]);
		$sql =
			"SELECT ia_id, ia_nrodescripcion, ia_descripcion, ia_idtipoformanexo, NULL il_cumplimiento, NULL il_fecharegularizacion
				 FROM hys.hia_itemanexo
				WHERE ia_idtituloanexo = :idtituloanexo
					AND ia_fechabaja IS NULL
					AND NVL(ia_sololectura, 'N') <> 'S'
		 ORDER BY ia_nrodescripcion";
	}

	$stmt3 = DBExecSql($conn, $sql, $params);
	while ($row3 = DBGetQuery($stmt3)) {
		$valorItem = $row3["IL_CUMPLIMIENTO"];
		if ($valorItem == "") {
			$params = array(":nro" => $_REQUEST["nro"], ":iditem" => $row3["IA_ID"]);
			$valorItem = ValorSql("SELECT art.hys.get_marcaitemdefault(:nro, :iditem) FROM DUAL", "", $params);
		}

		$fecha = "";
		if ($valorItem == "N") {
			$fecha = $row3["IL_FECHAREGULARIZACION"];
			if ($fecha == "") {
				$params = array(":nro" => $_REQUEST["nro"], ":iditem" => $row3["IA_ID"]);
				$fecha = ValorSql("SELECT art.hys.get_fregularizaciondefault(:nro, :iditem) FROM DUAL", "", $params);
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
		<td class="Pregunta"><input <?= ($valorItem == "S")?"checked":""?> id="pregunta_<?= $row3["IA_ID"]?>" name="pregunta_<?= $row3["IA_ID"]?>" type="radio" value="S" onClick="clicItemPregunta(<?= $_REQUEST["nro"]?>, <?= $row3["IA_ID"]?>, this.value, true)"></td>
		<td class="Pregunta"><input <?= ($valorItem == "N")?"checked":""?> id="pregunta_<?= $row3["IA_ID"]?>" name="pregunta_<?= $row3["IA_ID"]?>" type="radio" value="N" onClick="clicItemPregunta(<?= $_REQUEST["nro"]?>, <?= $row3["IA_ID"]?>, this.value, true)"></td>
		<td class="Pregunta"><input <?= ($valorItem == "X")?"checked":""?> id="pregunta_<?= $row3["IA_ID"]?>" name="pregunta_<?= $row3["IA_ID"]?>" type="radio" value="X" onClick="clicItemPregunta(<?= $_REQUEST["nro"]?>, <?= $row3["IA_ID"]?>, this.value, true)"></td>
		<td class="fecha">
<?
		if ($valorItem == "N") {
?>
			<input id="fecha_<?= $row3["IA_ID"]?>" maxlength="10" name="fecha_<?= $row3["IA_ID"]?>" style="width:80px;" type="text" value="<?= $fecha?>">
			<input class="botonFecha" id="btnFecha<?= $row3["IA_ID"]?>" name="btnFecha<?= $row3["IA_ID"]?>" type="button" value="">
			<input id="fechaD_<?= $row3["IA_ID"]?>" maxlength="10" name="fechaD_<?= $row3["IA_ID"]?>" readonly style="background-color:#ccc; display:none; width:80px;" type="text" value="">
			<input class="botonFechaDeshabilitado" id="btnFechaD<?= $row3["IA_ID"]?>" name="btnFechaD<?= $row3["IA_ID"]?>" style="display:none;" type="button" value="">
<?
		}
		else {
?>
			<input id="fecha_<?= $row3["IA_ID"]?>" maxlength="10" name="fecha_<?= $row3["IA_ID"]?>" style="display:none; width:80px;" type="text" value="<?= $fecha?>">
			<input class="botonFecha" id="btnFecha<?= $row3["IA_ID"]?>" name="btnFecha<?= $row3["IA_ID"]?>" style="display:none;" type="button" value="">
			<input id="fechaD_<?= $row3["IA_ID"]?>" maxlength="10" name="fechaD_<?= $row3["IA_ID"]?>" readonly style="background-color:#ccc; width:80px;" type="text" value="">
			<input class="botonFechaDeshabilitado" id="btnFechaD<?= $row3["IA_ID"]?>" name="btnFechaD<?= $row3["IA_ID"]?>" type="button" value="">
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
				echo "<script type='text/javascript'>clicItemPregunta(".$_REQUEST["nro"].", ".$row3["IA_ID"].", 'S', false);</script>";
		}
	}
}
?>
</table>
</p>
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