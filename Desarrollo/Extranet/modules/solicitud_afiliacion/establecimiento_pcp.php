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


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idModulo"]);

setDateFormatOracle("DD/MM/YYYY");

$isAlta = ((!isset($_REQUEST["id"])) or ($_REQUEST["id"] < 1));

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT lt_calle, lt_cpostal, lt_departamento, lt_localidad, lt_numero, lt_piso, lt_provincia, pv_descripcion
			 FROM afi.alt_lugartrabajo_pcp, cpv_provincias
			WHERE lt_provincia = pv_codigo(+)
				AND lt_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}

$hayDatos = ((!$isAlta) and ($row["LT_CALLE"] != ""));
?>
<html>
	<head>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<link rel="stylesheet" href="/styles/design.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<style type="text/css">
			* {margin:0; padding:0;}

			html, body {overflow: hidden; text-align: left;}
		</style>
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script language="JavaScript" src="/modules/solicitud_afiliacion/js/afiliacion.js?rnd=<?= date("Ymdhmi")?>"></script>
		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/solicitud_afiliacion/procesar_establecimiento_pcp.php" id="formEstablecimiento" method="post" name="formEstablecimiento" target="iframeProcesando">
			<input id="esDomicilioLegal" name="esDomicilioLegal" type="hidden" value="F" />
			<input id="id" name="id" type="hidden" value="<?= (!$isAlta)?$_REQUEST["id"]:""?>" />
			<input id="idModulo" name="idModulo" type="hidden" value="<?= $_REQUEST["idModulo"]?>" />
			<input id="idProvincia" name="idProvincia" type="hidden" value="<?= (!$isAlta)?$row["LT_PROVINCIA"]:""?>" />
			<input id="idSolicitud" name="idSolicitud" type="hidden" value="<?= $_REQUEST["idSolicitud"]?>" />
			<input id="tipoOp" name="tipoOp" type="hidden" value="<?= ($_REQUEST["id"] == -1)?"A":"M"?>" />
			<div class="TituloFndCeleste" style="height:18px; padding-left:8px; padding-top:2px; width:680px;">DOMICILIO (*)</div>
			<div style="margin-top:8px;">
				<div class="ContenidoSeccion" id="divDatosDomicilio">
					<p style="margin-left:88px; margin-top:4px;">
						<label for="calle">Calle</label>
						<input id="calle" maxlength="60" name="calle" readonly style="background-color:#ccc; width:440px;" type="text" value="<?= (!$isAlta)?$row["LT_CALLE"]:""?>">
					</p>
					<p style="margin-left:72px; margin-top:4px;">
						<label for="numero">Número</label>
						<input id="numero" maxlength="6" name="numero" style="width:76px;" type="text" value="<?= (!$isAlta)?$row["LT_NUMERO"]:""?>">
						<label for="piso" style="margin-left:16px;">Piso</label>
						<input id="piso" maxlength="6" name="piso" style="width:76px;" type="text" value="<?= (!$isAlta)?$row["LT_PISO"]:""?>">
						<label for="departamento" style="margin-left:16px;">Departamento</label>
						<input id="departamento" maxlength="6" name="departamento" style="width:76px;" type="text" value="<?= (!$isAlta)?$row["LT_DEPARTAMENTO"]:""?>">
					</p>
					<p style="margin-left:64px; margin-top:4px;">
						<label for="localidad">Localidad</label>
						<input id="localidad" maxlength="60" name="localidad" readonly style="background-color:#ccc; width:270px;" type="text" value="<?= (!$isAlta)?$row["LT_LOCALIDAD"]:""?>">
						<label for="codigoPostal" style="margin-left:16px;">Código Postal</label>
						<input id="codigoPostal" maxlength="5" name="codigoPostal" readonly style="background-color:#ccc; width:67px;" type="text" value="<?= (!$isAlta)?$row["LT_CPOSTAL"]:""?>">
					</p>
					<p style="margin-left:65px; margin-top:4px;">
						<label for="provincia">Provincia</label>
						<input id="provincia" name="provincia" readonly style="background-color:#ccc; width:440px;" type="text" value="<?= (!$isAlta)?$row["PV_DESCRIPCION"]:""?>">
					</p>
				</div>
				<p style="margin-left:133px; margin-top:8px;">
					<img src="/modules/usuarios_registrados/images/boton_<?= ($hayDatos)?"modificar":"agregar"?>_domicilio.jpg" style="cursor:pointer;" onClick="parent.document.getElementById('divBoxEstablecimiento').firstChild.nextSibling.style.height = '560px'; buscarDomicilio(true, 'pSinDatosconocidos', 'divDatosDomicilio', 'idProvincia', 'provincia', 'localidad', '', 'codigoPostal', 'calle', 'numero', 'piso', 'departamento', '', 416, 680, 1, 8);" />
				</p>
			</div>
			<div style="margin-bottom:8px; margin-top:16px;">
				<input class="btnGrabar" style="margin-left:16px; margin-top:24px;" type="submit" value="">
<?
if (!$isAlta) {
?>
				<input class="btnDarDeBaja" style="margin-left:16px;" type="button" value="" onClick="eliminarEstablecimientoPCP(<?= $_REQUEST["id"]?>)">
<?
}
?>
			</div>
		</form>
		<p id="guardadoOk" style="background:#0f539c; color:#fff; display:none; margin-left:16px; margin-top:8px; padding:2px; width:280px;">&nbsp;Datos guardados exitosamente.</p>
		<p id="borradoOk" style="background:#0f539c; color:#fff; display:none; margin-left:16px; margin-top:8px; padding:2px; width:440px;">&nbsp;El establecimiento fue dado de baja exitosamente.</p>
		<div id="divErrores" style="display:none;">
			<table bordercolor="#ff0000" align="center" cellpadding="6" cellspacing="0">
				<tr>
					<td>
						<table cellpadding="4" cellspacing="0">
							<tr>
								<td><img src="/images/atencion.jpg" /></td>
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
			<input id="foco" name="foco" readonly style="height:1px; width:1px;" type="checkbox" />
		</div>
	</body>
</html>