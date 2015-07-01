<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
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
	$titulo = valorSql($sql, "", $params);
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
	$params = array(":idestablecimiento" => $_REQUEST["ide"],
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
				AND sf_idestablecimiento = :idestablecimiento";

	if (existeSql($sql, $params)) {
		$params = array(":idestablecimiento" => $_REQUEST["ide"], ":idtipoanexo" => $idTipoFormaAnexo);
		$sql =
			"SELECT sp_id
				 FROM hys.hsp_solicitudplanillafgrl, hys.hsf_solicitudfgrl
				WHERE sp_idsolicitudfgrl = sf_id
					AND sp_idtipoanexo = :idtipoanexo
					AND sf_idestablecimiento = :idestablecimiento";
		$idSolicitudPlanilla = valorSql($sql, "", $params);

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
						<input id="Hextra_<?= $row3["IT_ID"]?>_pregunta_<?= $id?>_planilla" name="Hextra_<?= $row3["IT_ID"]?>_pregunta_<?= $id?>_planilla" type="hidden" value="<?= substr($titulo, 9, 1)?>" />
						<tr>
							<td class="TxtPregunta"><?= $row3["IT_DESCRIPCION"]?></font></td>
<?
		if ($planillaC) {
?>
							<td class="TxtPregunta"><?= $row3["IT_MASDATOS"]?></font></td>
<?
		}
?>
							<td class="Pregunta"><input <?= ($row3["SI_CUMPLIMIENTO"] == "S")?"checked":""?> id="extra_<?= $row3["IT_ID"]?>" name="extra_<?= $row3["IT_ID"]?>" type="radio" value="S" /></td>
							<td class="Pregunta"><input <?= ($row3["SI_CUMPLIMIENTO"] == "N")?"checked":""?> id="extra_<?= $row3["IT_ID"]?>" name="extra_<?= $row3["IT_ID"]?>" type="radio" value="N" /></td>
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


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 100));

// Valido que el rgrl sea del establecimiento relacionado al contrato del usuario..
$params = array(":contrato" => $_SESSION["contrato"], ":id" => $_REQUEST["ide"]);
$sql =
	"SELECT 1
		 FROM aes_establecimiento
		WHERE es_contrato = :contrato
			AND es_id = :id";
validarSesion(existeSql($sql, $params));


$sql = "SELECT art.hys.get_idresolucion463(:id, 'C') FROM DUAL";
$params = array(":id" => $_REQUEST["ide"]);
if (valorSql($sql, "", $params) == "") {
	echo 'A este establecimiento no corresponde que se le carguen datos sobre el RGRL.<p><a href="#" onClick="window.parent.parent.divWin.close();">Cerrar</a></p>';
	exit;
}

$params = array(":id" => $_REQUEST["ide"]);
$sql =
	"SELECT 1
		 FROM comunes.cac_actividad, hys.hpa_preguntaadicional hpa, aes_establecimiento
		WHERE SUBSTR(art.hys.get_codactividadrevdos(ac_id), 1, LENGTH(pa_ciiuviejo)) = pa_ciiuviejo
			AND ac_id = es_idactividad
			AND pa_idresolucion = art.hys.get_idresolucion463(es_id, 'C')
			AND es_id = :id";
$mostrarPreguntasAdicionales = existeSql($sql, $params);

if ($mostrarPreguntasAdicionales) {
	$params = array(":idestablecimiento" => $_REQUEST["ide"]);
	$sql =
		"SELECT 1
			 FROM hys.hra_respuestaadicional
			WHERE ra_idestablecimiento = :idestablecimiento";
	$mostrarPreguntasAdicionales = (!existeSql($sql, $params));
}

// Traigo los datos de la cabecera..
$params = array(":id" => $_REQUEST["ide"]);
$sql =
	"SELECT cac2.ac_codigo ciiuempresa, cac1.ac_codigo ciiuestablecimiento, art.utiles.armar_cuit(em_cuit) cuit,
					art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, NULL) domicilio, pv_descripcion, em_nombre, es_cpostala, es_descripcionactividad, es_empleados,
					es_idactividad, es_localidad, es_nroestableci, es_superficie, art.utiles.armar_telefono(es_codareatelefonos, NULL, es_telefonos) telefono
		 FROM aco_contrato, aem_empresa, aes_establecimiento, cac_actividad cac1, cac_actividad cac2, cpv_provincias
		WHERE co_idempresa = em_id
			AND co_contrato = es_contrato
			AND es_idactividad = cac1.ac_id
			AND co_idactividad = cac2.ac_id
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
			* {margin:0; padding:0;}
			html, body {background-color:#fff; overflow:auto; text-align:left;}
			.btnGrillaFondo {background-color:#fff; margin-left:8px;}
			.fecha {border-bottom-style:solid; border-bottom-width:1px; border-color:#bcbcbc; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; text-align:center; width:120px;}
			.inputGrilla {width:100%;}
			.spanGrilla {color:#000; cursor:default;}
			#contratistas {margin-left:16px; vertical-align:-3px;}
			#delegadosGremiales {margin-left:16px; vertical-align:-3px;}

			#divBtnAgregarProfesional {margin-top:8px;}
			#divBtnAgregarResponsable {margin-top:8px;}

			#divDatosContratistas {margin-left:12px; margin-right:4px; margin-top:24px;}
			#divDatosContratistasDatos {border:1px solid #00539b;}
			#divDatosContratistasDatosPregunta {color:#000; font-size:8pt; font-weight:bold; margin-top:4px;}
			#divDatosContratistasDatosTitulo {background-color:#0070c0; color:#fff; font-size:7pt; font-weight:bold; margin-bottom:8px; margin-top:8px; padding:2px;}
			#divDatosContratistasTitulo {padding:2px;}

			#divDatosGremiales {margin-left:12px; margin-right:4px; margin-top:24px;}
			#divDatosGremialesDatos {border:1px solid #00539b;}
			#divDatosGremialesDatosPregunta {color:#000; font-size:8pt; font-weight:bold; margin-top:4px;}
			#divDatosGremialesDatosTitulo {background-color:#0070c0; color:#fff; font-size:7pt; font-weight:bold; margin-bottom:8px; margin-top:8px; padding:2px;}
			#divDatosGremialesTitulo {padding:2px;}

			#divProfesionalesDatos {border:1px solid #00539b;}

			#divResponsable1 {display:none; padding-top:8px;}
			#divResponsable1Titulo {height:16px; margin-top:24px;}
			#divResponsable2 {display:none; margin-top:8px; padding-top:8px;}
			#divResponsable2Titulo {height:16px; margin-top:8px;}
			#divResponsable3 {display:none; margin-top:8px; padding-top:8px;}
			#divResponsable3Titulo {height:16px; margin-top:8px;}

			#divResponsablesDatos {margin-left:12px; margin-right:4px; margin-top:24px;}
			#divResponsablesDatos2 {border:1px solid #00539b;}
			#divResponsablesDatosTitulo {padding:2px;}

			#cuit1 {width:120px;}
			#cuit2 {width:120px;}
			#cuit3 {width:120px;}
			#entidad1 {width:360px;}
			#entidad2 {width:360px;}
			#entidad3 {width:360px;}
			#matricula1 {width:360px;}
			#matricula2 {width:360px;}
			#matricula3 {width:360px;}
			#nombre1 {width:360px;}
			#nombre2 {width:360px;}
			#nombre3 {width:360px;}
			#titulo1 {width:360px;}
			#titulo2 {width:360px;}
			#titulo3 {width:360px;}

			#tableDatosContratistas {border:1px solid #ccc; border-collapse:collapse; font-size:8pt; margin-top:8px; width:100%;}
			#tableDatosContratistas td {border:1px solid #ccc; padding:2px;}
			#tableDatosContratistasCabecera {color:#fff; font-weight:bold;}

			#tableDatosGremiales {border:1px solid #ccc; border-collapse:collapse; font-size:8pt; margin-top:8px; width:100%;}
			#tableDatosGremiales td {border:1px solid #ccc; padding:2px;}
			#tableDatosGremialesCabecera {color:#fff; font-weight:bold;}

			#tableProfesionalesDatos {border:1px solid #ccc; border-collapse:collapse; font-size:8pt; margin-top:8px; width:100%;}
			#tableProfesionalesDatos td {padding-left:8px; padding-right:8px;}
			#tableProfesionalesDatos th {padding-left:8px; padding-right:8px;}
			#tableProfesionalesDatosCabecera {color:#fff; font-weight:bold;}

			#tableResponsablesDatos {border:1px solid #ccc; border-collapse:collapse; font-size:8pt; margin-top:8px; width:100%;}
			#tableResponsablesDatos td {padding-left:8px; padding-right:8px;}
			#tableResponsablesDatos th {padding-left:8px; padding-right:8px;}
			#tableResponsablesDatosCabecera {color:#fff; font-weight:bold;}
		</style>
		<script src="/js/functions.js?rnd=<?= date("Ymd")?>" type="text/javascript"></script>
		<script src="/js/validations.js?rnd=<?= date("Ymd")?>" type="text/javascript"></script>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script src="/modules/usuarios_registrados/clientes/js/rgrl.js" type="text/javascript"></script>

		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/usuarios_registrados/clientes/rgrl/procesar_rgrl.php" id="formRGRL" method="post" name="formRGRL" target="iframeProcesando">
			<input id="bajasContratistas" name="bajasContratistas" type="hidden" value="-1" />
			<input id="bajasGremiales" name="bajasGremiales" type="hidden" value="-1" />
			<input id="idEstablecimiento" name="idEstablecimiento" type="hidden" value="<?= $_REQUEST["ide"]?>" />
			<input id="mostrarAviso" name="mostrarAviso" type="hidden" value="t" />
			<input id="preguntasAdicionales" name="preguntasAdicionales" type="hidden" value="<?= ($mostrarPreguntasAdicionales)?"t":"f"?>" />
			<input id="preguntasQueValidaHyS" name="preguntasQueValidaHyS" type="hidden" value="" />
			<input id="preguntasQueValidaML" name="preguntasQueValidaML" type="hidden" value="" />
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
						<td class="item">
						
							Código de actividad según Clasificador de Actividades Económicas (CLAE) - Formulario Nº 883 (Resolución A.F.I.P. Nº 3537):
							<i><?= $row["CIIUEMPRESA"]?></i>
<?
if ($mostrarPreguntasAdicionales) {
?>
							<span style="margin-left: 5%;">Cantidad de trabajadores: </span>
							<i><?= $row["ES_EMPLEADOS"]?></i>
<?
}
else {
	$params = array(":idestablecimiento" => $_REQUEST["ide"]);
	$sql =
		"SELECT sf_empleados
			 FROM hys.hsf_solicitudfgrl
			WHERE sf_idestablecimiento = :idestablecimiento";
	$empleados = valorSql($sql, "", $params);
?>
							<span style="margin-left: 5%;"><b>Cantidad de trabajadores</b>: </span>
							<input autofocus id="cantidadTrabajadores" maxlength="8" name="cantidadTrabajadores" style="width:80px;" type="text" value="<?= $empleados?>" />
<?
}
?>
						</td>
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
if ($mostrarPreguntasAdicionales) {
	$params = array(":idestablecimiento" => $_REQUEST["ide"], ":idactividad" => $row["ES_IDACTIVIDAD"]);
	$sql =
		"SELECT pa_id, pa_idtipoformanexo, pa_pregunta
			 FROM comunes.cac_actividad, hys.hpa_preguntaadicional hpa
			WHERE pa_idresolucion = art.hys.get_idresolucion463(:idestablecimiento, 'C')
				AND SUBSTR(art.hys.get_codactividadrevdos(ac_id), 1, LENGTH(pa_ciiuviejo)) = pa_ciiuviejo
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
					<td class="Pregunta"><input id="pregunta_<?= $row2["PA_ID"]?>" name="pregunta_<?= $row2["PA_ID"]?>" type="radio" value="S" onClick="clicPregunta(<?= $row2["PA_ID"]?>, 'si')" /></td>
					<td class="Pregunta"><input id="pregunta_<?= $row2["PA_ID"]?>" name="pregunta_<?= $row2["PA_ID"]?>" type="radio" value="N" onClick="clicPregunta(<?= $row2["PA_ID"]?>, 'no')" /></td>
				</tr>
			</table>
		</p>
<?
		if ($row2["PA_IDTIPOFORMANEXO"] != "")
			dibujarPlanilla($row2["PA_IDTIPOFORMANEXO"], $row2["PA_ID"], 590);
	}
}
else {
	$params = array(":idestablecimiento" => $_REQUEST["ide"]);
	$sql =
		"SELECT ra_titulo, ra_descripcion, ra_header
			 FROM hys.hra_resolucionanexo
			WHERE ra_fechabaja IS NULL
				AND ra_id = art.hys.get_idresolucion463(:idestablecimiento, 'C')";
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
	$params = array(":idestablecimiento" => $_REQUEST["ide"]);
	$sql =
		"SELECT 1
			 FROM hys.hst_solicituditemsfgrl, hys.hsf_solicitudfgrl
			WHERE st_idsolicitudfgrl = sf_id
				AND sf_fechapasaje IS NULL
				AND sf_idresolucionanexo = art.hys.get_idresolucion463 (sf_idestablecimiento, 'C')
				AND sf_idestablecimiento = :idestablecimiento";
	$itemsCargados = existeSql($sql, $params);

	$params = array(":idestablecimiento" => $_REQUEST["ide"]);
	$sql =
		"SELECT ta_id, ta_nrotitulo, ta_descripcion
			 FROM hys.hta_titulosanexo
			WHERE ta_idresolucionanexo = art.hys.get_idresolucion463(:idestablecimiento, 'C')
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
			$params = array(":idestablecimiento" => $_REQUEST["ide"], ":idtituloanexo" => $row2["TA_ID"]);
			$sql =
				"SELECT ia_id, ia_nrodescripcion, ia_descripcion, ia_idtipoformanexo, st_cumplimiento, st_fecharegularizacion, ia_cargorepresentante
					 FROM hys.hia_itemanexo, hys.hst_solicituditemsfgrl, hys.hsf_solicitudfgrl
					WHERE ia_id = st_iditem(+)
						AND st_idsolicitudfgrl = sf_id(+)
						AND ia_idtituloanexo = :idtituloanexo
						AND ia_fechabaja IS NULL
						AND NVL(ia_sololectura, 'N') <> 'S'
						AND sf_idestablecimiento = :idestablecimiento
			 ORDER BY ia_nrodescripcion";
		}
		else {
			$params = array(":idtituloanexo" => $row2["TA_ID"]);
			$sql =
				"SELECT ia_id, ia_nrodescripcion, ia_descripcion, ia_idtipoformanexo, NULL st_cumplimiento, NULL st_fecharegularizacion, ia_cargorepresentante
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
				$params = array(":idestablecimiento" => $_REQUEST["ide"], ":iditem" => $row3["IA_ID"]);
				$valorItem = valorSql("SELECT art.hys.get_marcaitemdefault(:idestablecimiento, :iditem, 'C') FROM DUAL", "", $params);
			}

			$fecha = "";
			if ($valorItem == "N") {
				$fecha = $row3["ST_FECHAREGULARIZACION"];
				if ($fecha == "") {
					$params = array(":idestablecimiento" => $_REQUEST["ide"], ":iditem" => $row3["IA_ID"]);
					$fecha = valorSql("SELECT art.hys.get_fregularizaciondefault(:idestablecimiento, :iditem, 'C') FROM DUAL", "", $params);
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
				<input id="Hdeshabilitar_fecha_<?= $row3["IA_ID"]?>" name="Hdeshabilitar_fecha_<?= $row3["IA_ID"]?>" type="hidden" value="s" />
				<span id="btnExpandir_<?= $row3["IA_ID"]?>" style="background-color:#c2d560; cursor:hand; display:none; font-size:12px; width:64px;" onClick="expandirPlanilla(<?= $row3["IA_ID"]?>)">Expandir</span>
<?
			}
			else {
?>
				<input id="Hdeshabilitar_fecha_<?= $row3["IA_ID"]?>" name="Hdeshabilitar_fecha_<?= $row3["IA_ID"]?>" type="hidden" value="n" />
<?
			}
?>
		<?= $row3["IA_NRODESCRIPCION"]?></td>
		<td class="TxtPregunta"><?= $row3["IA_DESCRIPCION"]?></font></td>
		<td class="Pregunta"><input <?= ($valorItem == "S")?"checked":""?> id="pregunta_<?= $row3["IA_ID"]?>" name="pregunta_<?= $row3["IA_ID"]?>" type="radio" value="S" onClick="clicItemPregunta(<?= $_REQUEST["ide"]?>, <?= $row3["IA_ID"]?>, this.value, true)" /></td>
		<td class="Pregunta"><input <?= ($valorItem == "N")?"checked":""?> id="pregunta_<?= $row3["IA_ID"]?>" name="pregunta_<?= $row3["IA_ID"]?>" type="radio" value="N" onClick="clicItemPregunta(<?= $_REQUEST["ide"]?>, <?= $row3["IA_ID"]?>, this.value, true)" /></td>
		<td class="Pregunta"><input <?= ($valorItem == "X")?"checked":""?> id="pregunta_<?= $row3["IA_ID"]?>" name="pregunta_<?= $row3["IA_ID"]?>" type="radio" value="X" onClick="clicItemPregunta(<?= $_REQUEST["ide"]?>, <?= $row3["IA_ID"]?>, this.value, true)" /></td>
		<td class="Fecha">
<?
			if (($valorItem == "N") and ($row3["IA_IDTIPOFORMANEXO"] == "")) {
?>
			<input id="fecha_<?= $row3["IA_ID"]?>" maxlength="10" name="fecha_<?= $row3["IA_ID"]?>" style="width:76px;" type="text" value="<?= $fecha?>" />
			<input class="botonFecha" id="btnFecha<?= $row3["IA_ID"]?>" name="btnFecha<?= $row3["IA_ID"]?>" style="vertical-align:-4px;" type="button" value="" />
			<input id="fechaD_<?= $row3["IA_ID"]?>" maxlength="10" name="fechaD_<?= $row3["IA_ID"]?>" readonly style="background-color:#ccc; display:none; width:76px;" type="text" value="" />
			<input class="botonFechaDeshabilitado" id="btnFechaD<?= $row3["IA_ID"]?>" name="btnFechaD<?= $row3["IA_ID"]?>" style="display:none; vertical-align:-4px;" type="button" value="" />
<?
			}
			else {
?>
			<input id="fecha_<?= $row3["IA_ID"]?>" maxlength="10" name="fecha_<?= $row3["IA_ID"]?>" style="display:none; width:76px;" type="text" value="<?= $fecha?>" />
			<input class="botonFecha" id="btnFecha<?= $row3["IA_ID"]?>" name="btnFecha<?= $row3["IA_ID"]?>" style="display:none; vertical-align:-4px;" type="button" value="" />
			<input id="fechaD_<?= $row3["IA_ID"]?>" maxlength="10" name="fechaD_<?= $row3["IA_ID"]?>" readonly style="background-color:#ccc; width:76px;" type="text" value="" />
			<input class="botonFechaDeshabilitado" id="btnFechaD<?= $row3["IA_ID"]?>" name="btnFechaD<?= $row3["IA_ID"]?>" style="vertical-align:-4px;" type="button" value="" />
<?
			}
?>
		</td>
	</tr>
	<script type="text/javascript">
		Calendar.setup ({
			inputField: "fecha_<?= $row3["IA_ID"]?>",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFecha<?= $row3["IA_ID"]?>"
		});

		with (document) {
<?
			if ($row3["IA_CARGOREPRESENTANTE"] == "H") {
?>
				getElementById('preguntasQueValidaHyS').value = '<?= $row3["IA_ID"]?>';
<?
			}
			if ($row3["IA_CARGOREPRESENTANTE"] == "M") {
?>
				getElementById('preguntasQueValidaML').value = '<?= $row3["IA_ID"]?>';
<?
			}
?>
		}
	</script>
<?
			if ($row3["IA_IDTIPOFORMANEXO"] != "") {
				echo '<tr><td colspan="6">';
				dibujarPlanilla($row3["IA_IDTIPOFORMANEXO"], $row3["IA_ID"], 544);
				echo "</td></tr>";

				if ($valorItem == "S")
					echo "<script type='text/javascript'>clicItemPregunta(".$_REQUEST["ide"].", ".$row3["IA_ID"].", 'S', false);</script>";
			}
		}
	}
?>
</table>

<div id="divDatosGremiales">
	<div class="TituloFndCeleste" id="divDatosGremialesTitulo">
		<span>DATOS GREMIALES</span>
		<img align="right" border="0" src="/images/minus16.png" style="cursor:hand; position:relative; top:-2px;" title="Contraer" onClick="showHideDiv(this)" />
	</div>
	<div id="divDatosGremialesDatos">
		<div id="divDatosGremialesDatosPregunta">
			<span>¿ El establecimiento cuenta con delegados gremiales ?</span>
			<input id="delegadosGremiales" name="delegadosGremiales" type="radio" value="S" onClick="clicDelegadosGremiales(this)" />
			<label for="delegadosGremiales">Sí</label>
			<input id="delegadosGremiales" name="delegadosGremiales" type="radio" value="N" onClick="clicDelegadosGremiales(this)" />
			<label for="delegadosGremiales">No</label>
		</div>
		<div id="divDatosGremialesDatosTitulo">LEGAJO CONFORME A LA INSCRIPCIÓN EN EL MINISTERIO DE TRABAJO, EMPLEO Y SEGURIDAD SOCIAL</div>
		<div><input class="btnAgregarDelegadoGremial" id="btnAgregarDelegadoGremial" type="button" value="" onClick="agregarDelegadoGremial(-1, '', '', true)" /></div>
		<div>
			<table id="tableDatosGremiales">
				<tr class="gridRow1" id="tableDatosGremialesCabecera">
					<th align="center">ACCIONES</th>
					<th>Nº LEGAJO</th>
					<th>NOMBRE DEL GREMIO</th>
				</tr>
<?
/*
				<tr id="trDelegadoGremialOff_<?= $iLoop?>">
					<td align="center">
						<input id="idDelegadoGremial_<?= $iLoop?>" name="idDelegadoGremial_<?= $iLoop?>" type="hidden" value="<?= $row["RW_ID"]?>" />
						<input class="btnEditar btnGrillaFondo" id="btnEditar_<?= $iLoop?>" name="btnEditar_<?= $iLoop?>" title="Editar" type="button" onClick="editarDelegadoGremial(this)" />
						<input class="btnQuitar btnGrillaFondo" id="btnQuitar_<?= $iLoop?>" name="btnQuitar_<?= $iLoop?>" title="Quitar" type="button" onClick="quitarDelegadoGremial(this)" />
					</td>
					<td><span class="spanGrilla" id="spanNumeroLegajo_<?= $iLoop?>"><?= $row["RW_NROLEGAJO"]?></span></td>
					<td><span class="spanGrilla" id="spanNombre_<?= $iLoop?>"><?= $row["RW_NOMBREGREMIO"]?></span></td>
				</tr>
				<tr id="trDelegadoGremialOn_<?= $iLoop?>">
					<td align="center" style="display:none;">
						<input class="btnAceptar btnGrillaFondo" id="btnAceptar_<?= $iLoop?>" name="btnAceptar_<?= $iLoop?>" title="Aceptar" type="button" onClick="aceptarDelegadoGremial(this)" />
						<input class="btnCancelarChico btnGrillaFondo" id="btnCancelar_<?= $iLoop?>" name="btnCancelar_<?= $iLoop?>" title="Cancelar" type="button" onClick="cancelarDelegadoGremial(this)" />
					</td>
					<td style="display:none;"><input class="inputGrilla" id="numeroLegajo_<?= $iLoop?>" name="numeroLegajo_<?= $iLoop?>" type="text" value="<?= $row["RW_NROLEGAJO"]?>" /></td>
					<td style="display:none;"><input class="inputGrilla" id="nombre_<?= $iLoop?>" name="nombre_<?= $iLoop?>" type="text" value="<?= $row["RW_NOMBREGREMIO"]?>" /></td>
				</tr>
*/
?>
			</table>
		</div>
	</div>
</div>

<div id="divDatosContratistas">
	<div class="TituloFndCeleste" id="divDatosContratistasTitulo">
		<span>DATOS DE CONTRATISTAS</span>
		<img align="right" border="0" src="/images/minus16.png" style="cursor:hand; position:relative; top:-2px;" title="Contraer" onClick="showHideDiv(this)" />
	</div>
	<div id="divDatosContratistasDatos">
		<div id="divDatosContratistasDatosPregunta">
			<span>¿ El establecimiento encomienda tareas a contratistas ?</span>
			<input id="contratistas" name="contratistas" type="radio" value="S" onClick="clicContratistas(this)" />
			<label for="contratistas">Sí</label>
			<input id="contratistas" name="contratistas" type="radio" value="N" onClick="clicContratistas(this)" />
			<label for="contratistas">No</label>
		</div>
		<div id="divDatosContratistasDatosTitulo">CONTRATISTAS</div>
		<div><input class="btnAgregarContratista" id="btnAgregarContratista" type="button" value="" onClick="agregarContratista(-1, '', true)" /></div>
		<div>
			<table id="tableDatosContratistas">
				<tr class="gridRow1" id="tableDatosContratistasCabecera">
					<th align="center">ACCIONES</th>
					<th>Nº C.U.I.T.</th>
				</tr>
<?
/*
				<tr id="trContratistaOff_<?= $iLoop?>">
					<td align="center">
						<input id="idContratista_<?= $iLoop?>" name="idContratista_<?= $iLoop?>" type="hidden" value="<?= $row["RW_ID"]?>" />
						<input class="btnEditar btnGrillaFondo" id="btnEditar_<?= $iLoop?>" name="btnEditar_<?= $iLoop?>" title="Editar" type="button" onClick="editarContratista(this)" />
						<input class="btnQuitar btnGrillaFondo" id="btnQuitar_<?= $iLoop?>" name="btnQuitar_<?= $iLoop?>" title="Quitar" type="button" onClick="quitarContratista(this)" />
					</td>
					<td><span class="spanGrilla" id="spanCuit_<?= $iLoop?>"><?= $row["RW_CUIT"]?></span></td>
				</tr>
				<tr id="trContratistaOn_<?= $iLoop?>">
					<td align="center" style="display:none;">
						<input class="btnAceptar btnGrillaFondo" id="btnAceptar_<?= $iLoop?>" name="btnAceptar_<?= $iLoop?>" title="Aceptar" type="button" onClick="aceptarContratista(this)" />
						<input class="btnCancelarChico btnGrillaFondo" id="btnCancelar_<?= $iLoop?>" name="btnCancelar_<?= $iLoop?>" title="Cancelar" type="button" onClick="cancelarContratista(this)" />
					</td>
					<td style="display:none;"><input class="inputGrilla" id="cuit_<?= $iLoop?>" name="cuit_<?= $iLoop?>" type="text" value="<?= $row["RW_CUIT"]?>" /></td>
				</tr>
*/
?>				
			</table>
		</div>
	</div>
</div>

<div id="divResponsablesDatos">
	<div class="TituloFndCeleste" id="divResponsablesDatosTitulo">
		<span>DATOS DE LOS PROFESIONALES QUE PRESTAN SERVICIO DE HYS EN EL TRABAJO, MEDICINA LABORAL Y RESPONSABLE DE LOS DATOS DEL FORMULARIO</span>
	</div>
	<div id="divResponsablesDatos2">
		<div class="TituloFndCeleste" id="divResponsable1Titulo">
			<span>RESPONSABLE DE LOS DATOS DEL FORMULARIO</span>
			<img align="right" border="0" src="/images/add16.png" style="cursor:hand; margin-right:4px;" title="Desplegar" onClick="showHideDiv(this)" />
		</div>
<?
$params = array(":idestablecimiento" => $_REQUEST["ide"]);
$sql =
	"SELECT rw_cuitcuil, rw_entidad, rw_id, rw_idrepresentacion, rw_matricula, rw_nombre, rw_relacion, rw_responsableform, rw_titulo, rw_usualta
		 FROM hys.hrw_responsablerelevweb, hys.hsf_solicitudfgrl, hys.hrs_relevrepresentacion
		WHERE rw_idsolicitudfgrl = sf_id
			AND rw_idrepresentacion = rs_id(+)
			AND rw_fechabaja IS NULL
			AND rw_cargo = 'R'
			AND sf_idestablecimiento = :idestablecimiento";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$idRepresentacion1 = -1;
if ($row["RW_IDREPRESENTACION"] != "")
	$idRepresentacion1 = $row["RW_IDREPRESENTACION"];
require("rgrl_combos.php");
?>
		<div id="divResponsable1">
			<input id="idResponsable1" name="idResponsable1" type="hidden" value="<?= $row["RW_ID"]?>" />
			<div class="item" style="border-left-width:0px; margin-left:105px;">
				<label>CUIT / CUIL / CUIP</label>
				<input id="cuit1" maxlength="13" name="cuit1" type="text" value="<?= $row["RW_CUITCUIL"]?>" />
			</div>
			<div class="item" style="border-left-width:0px; margin-left:112px;">
				<label>Nombre y Apellido</label>
				<input id="nombre1" maxlength="100" name="nombre1" type="text" value="<?= $row["RW_NOMBRE"]?>" />
			</div>
			<div class="item" style="border-left-width:0px; margin-left:182px;">
				<label>Cargo</label>
				<span><b>RESPONSABLE DE LOS DATOS DEL FORMULARIO</b></span>
			</div>
			<div class="item" style="border-left-width:0px; margin-left:129px;">
				<label>Representación</label>
				<?= $comboRepresentacion1->draw();?>
			</div>
		</div>

		<div class="TituloFndCeleste" id="divResponsable1Titulo">
			<span>PROFESIONAL DE HIGIENE Y SEGURIDAD EN EL TRABAJO</span>
			<img align="right" border="0" src="/images/add16.png" style="cursor:hand; margin-right:4px;" title="Desplegar" onClick="showHideDiv(this)" />
		</div>
<?
$params = array(":idestablecimiento" => $_REQUEST["ide"]);
$sql =
	"SELECT rw_cuitcuil, rw_entidad, rw_id, rw_idrepresentacion, rw_matricula, rw_nombre, rw_relacion, rw_responsableform, rw_titulo, rw_usualta
		 FROM hys.hrw_responsablerelevweb, hys.hsf_solicitudfgrl, hys.hrs_relevrepresentacion
		WHERE rw_idsolicitudfgrl = sf_id
			AND rw_idrepresentacion = rs_id(+)
			AND rw_fechabaja IS NULL
			AND rw_cargo = 'H'
			AND sf_idestablecimiento = :idestablecimiento";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$idRepresentacion2 = -1;
if ($row["RW_IDREPRESENTACION"] != "")
	$idRepresentacion2 = $row["RW_IDREPRESENTACION"];

$tipo2 = -1;
if ($row["RW_RELACION"] != "")
	$tipo2 = $row["RW_RELACION"];
require("rgrl_combos.php");
?>
		<div id="divResponsable2">
			<input id="idResponsable2" name="idResponsable2" type="hidden" value="<?= $row["RW_ID"]?>" />
			<div class="item" style="border-left-width:0px; margin-left:105px;">
				<label>CUIT / CUIL / CUIP</label>
				<input id="cuit2" maxlength="13" name="cuit2" type="text" value="<?= $row["RW_CUITCUIL"]?>" />
			</div>
			<div class="item" style="border-left-width:0px; margin-left:112px;">
				<label>Nombre y Apellido</label>
				<input id="nombre2" maxlength="100" name="nombre2" type="text" value="<?= $row["RW_NOMBRE"]?>" />
			</div>
			<div class="item" style="border-left-width:0px; margin-left:182px;">
				<label>Cargo</label>
				<span><b>PROFESIONAL DE HIGIENE Y SEGURIDAD EN EL TRABAJO</b></span>
			</div>
			<div class="item" style="border-left-width:0px; margin-left:129px;">
				<label>Representación</label>
				<?= $comboRepresentacion2->draw();?>
			</div>
			<div class="item" style="border-left-width:0px; margin-left:193px;">
				<label>Tipo</label>
				<?= $comboTipo2->draw();?>
			</div>
			<div class="item" style="border-left-width:0px; margin-left:122px;">
				<label>Título Habilitante</label>
				<input id="titulo2" maxlength="50" name="titulo2" type="text" value="<?= $row["RW_TITULO"]?>" />
			</div>
			<div class="item" style="border-left-width:0px; margin-left:148px;">
				<label>Nº Matrícula</label>
				<input id="matricula2" maxlength="40" name="matricula2" type="text" value="<?= $row["RW_MATRICULA"]?>" />
			</div>
			<div class="item" style="border-left-width:0px;">
				<label>Entidad que otorgó el título habilitante</label>
				<input id="entidad2" maxlength="40" name="entidad2" type="text" value="<?= $row["RW_ENTIDAD"]?>" />
			</div>
		</div>

		<div class="TituloFndCeleste" id="divResponsable1Titulo">
			<span>PROFESIONAL DE MEDICINA LABORAL</span>
			<img align="right" border="0" src="/images/add16.png" style="cursor:hand; margin-right:4px;" title="Desplegar" onClick="showHideDiv(this)" />
		</div>
<?
$params = array(":idestablecimiento" => $_REQUEST["ide"]);
$sql =
	"SELECT rw_cuitcuil, rw_entidad, rw_id, rw_idrepresentacion, rw_matricula, rw_nombre, rw_relacion, rw_responsableform, rw_titulo, rw_usualta
		 FROM hys.hrw_responsablerelevweb, hys.hsf_solicitudfgrl, hys.hrs_relevrepresentacion
		WHERE rw_idsolicitudfgrl = sf_id
			AND rw_idrepresentacion = rs_id(+)
			AND rw_fechabaja IS NULL
			AND rw_cargo = 'M'
			AND sf_idestablecimiento = :idestablecimiento";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$idRepresentacion3 = -1;
if ($row["RW_IDREPRESENTACION"] != "")
	$idRepresentacion3 = $row["RW_IDREPRESENTACION"];

$tipo3 = -1;
if ($row["RW_RELACION"] != "")
	$tipo3 = $row["RW_RELACION"];
require("rgrl_combos.php");
?>
		<div id="divResponsable3">
			<input id="idResponsable3" name="idResponsable3" type="hidden" value="<?= $row["RW_ID"]?>" />
			<div class="item" style="border-left-width:0px; margin-left:105px;">
				<label>CUIT / CUIL / CUIP</label>
				<input id="cuit3" maxlength="13" name="cuit3" type="text" value="<?= $row["RW_CUITCUIL"]?>" />
			</div>
			<div class="item" style="border-left-width:0px; margin-left:112px;">
				<label>Nombre y Apellido</label>
				<input id="nombre3" maxlength="100" name="nombre3" type="text" value="<?= $row["RW_NOMBRE"]?>" />
			</div>
			<div class="item" style="border-left-width:0px; margin-left:182px;">
				<label>Cargo</label>
				<span><b>PROFESIONAL DE MEDICINA LABORAL</b></span>
			</div>
			<div class="item" style="border-left-width:0px; margin-left:129px;">
				<label>Representación</label>
				<?= $comboRepresentacion3->draw();?>
			</div>
			<div class="item" style="border-left-width:0px; margin-left:193px;">
				<label>Tipo</label>
				<?= $comboTipo3->draw();?>
			</div>
			<div class="item" style="border-left-width:0px; margin-left:122px;">
				<label>Título Habilitante</label>
				<input id="titulo3" maxlength="50" name="titulo3" type="text" value="<?= $row["RW_TITULO"]?>" />
			</div>
			<div class="item" style="border-left-width:0px; margin-left:148px;">
				<label>Nº Matrícula</label>
				<input id="matricula3" maxlength="40" name="matricula3" type="text" value="<?= $row["RW_MATRICULA"]?>" />
			</div>
			<div class="item" style="border-left-width:0px;">
				<label>Entidad que otorgó el título habilitante</label>
				<input id="entidad3" maxlength="40" name="entidad3" type="text" value="<?= $row["RW_ENTIDAD"]?>" />
			</div>
		</div>
	</div>
	</div>
</div>
</p>
<?
}
?>
			<div style="margin-bottom:8px; margin-left:16px; margin-top:16px;">
				<input class="btnGrabar" id="btnGrabar" type="submit" value="" onClick="document.getElementById('btnGrabar').style.display = 'none'; document.getElementById('spanProcesando').style.display = 'block';" />
				<span id="spanProcesando" style="color:#00539b; display:none; font-size:12px; margin-bottom:16px;">Procesando, aguarde un instante por favor...</span>
			</div>
		</form>
		<p id="guardadoOk" style="background:#0f539c; color:#fff; display:none; margin-left:16px; margin-top:8px; padding:2px; width:576px;"></p>
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
		<select id="tmpRepresentacion" name="tmpRepresentacion" style="display:none;"></select>
		<script>
			var contratistas = 'N';
			var delegadosGremiales = 'N';
<?
// Grilla delegados gremiales..
$params = array(":idestablecimiento" => $_REQUEST["ide"]);
$sql =
	"SELECT rw_id, rw_nombregremio, rw_nrolegajo
		 FROM hys.hrw_relevgremialistaweb, hys.hsf_solicitudfgrl
		WHERE rw_idsolicitudfgrl = sf_id
			AND sf_idestablecimiento = :idestablecimiento
			AND rw_fechabaja IS NULL";
$stmt = DBExecSql($conn, $sql, $params);
$iLoop = 0;
while ($row = DBGetQuery($stmt)) {
	$iLoop++;
?>
	delegadosGremiales = 'S';
	agregarDelegadoGremial(<?= $row["RW_ID"]?>, '<?= $row["RW_NROLEGAJO"]?>', '<?= $row["RW_NOMBREGREMIO"]?>', false);
<?
}

// Grilla contratistas..
$params = array(":idestablecimiento" => $_REQUEST["ide"]);
$sql =
	"SELECT rw_cuit, rw_id
		 FROM hys.hrw_relevcontratistaweb, hys.hsf_solicitudfgrl
		WHERE rw_idsolicitudfgrl = sf_id
			AND sf_idestablecimiento = :idestablecimiento
			AND rw_fechabaja IS NULL";
$stmt = DBExecSql($conn, $sql, $params);
$iLoop = 0;
while ($row = DBGetQuery($stmt)) {
	$iLoop++;
?>
	contratistas = 'S';
	agregarContratista(<?= $row["RW_ID"]?>, '<?= $row["RW_CUIT"]?>', false);
<?
}
?>
			// Tildo los delegados gremiales..
			document.getElementById('delegadosGremiales').value = delegadosGremiales;
			document.getElementById('formRGRL').delegadosGremiales[0].checked = (delegadosGremiales == 'S');
			document.getElementById('formRGRL').delegadosGremiales[1].checked = (delegadosGremiales == 'N');
			clicDelegadosGremiales(document.getElementById('delegadosGremiales'));

			// Tildo los contratistas..
			document.getElementById('contratistas').value = contratistas;
			document.getElementById('formRGRL').contratistas[0].checked = (contratistas == 'S');
			document.getElementById('formRGRL').contratistas[1].checked = (contratistas == 'N');
			clicContratistas(document.getElementById('contratistas'));
		</script>
	</body>
</html>