<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT eu_idactividad, eu_idlocalidad, eu_idtipoactividad, eu_idzonageografica, eu_trabajadores
		 FROM afi.aeu_establecimientos
		WHERE eu_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$params = array(":id" => nullIsEmpty($row["EU_IDZONAGEOGRAFICA"]));
$sql =
	"SELECT zg_idprovincia
		 FROM afi.azg_zonasgeograficas
		WHERE zg_id = :id";
$idProvincia = ValorSql($sql, "", $params);
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Establecimientos</title>
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
		<script type="text/javascript">
			function cambiaProvincia(idprovincia) {
				document.getElementById('imgLoadingProvincia').style.visibility = 'visible';
				document.getElementById('iframeProcesando').src = '/modules/solicitud_cotizacion/cambia_provincia_establecimiento.php?id=' + idprovincia;
			}

			function eliminar() {
				if (confirm('¿ Realmente desea eliminar este establecimiento ?'))
					with (document) {
						getElementById('tipoOp').value = 'B';
						getElementById('formEstablecimiento').submit();
					}
			}
		</script>
	</head>

	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/solicitud_cotizacion/procesar_establecimiento.php" id="formEstablecimiento" method="post" name="formEstablecimiento" target="iframeProcesando" onSubmit="return ValidarForm(formEstablecimiento)">
			<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>">
			<input id="idsolicitud" name="idsolicitud" type="hidden" value="<?= $_REQUEST["idsolicitud"]?>">
			<input id="tipoOp" name="tipoOp" type="hidden" value="<?= ($_REQUEST["id"] == -1)?"A":"M"?>">
			<p style="margin-top:8px;">
				<label class="ContenidoSeccion" for="provincia">Provincia</label>
				<select id="provincia" name="provincia" style="margin-left:37px;" title="Provincia" validar="true" onChange="cambiaProvincia(this.value)"></select>
				<img id="imgLoadingProvincia" src="/images/loading.gif" style="margin-left:8px; visibility:hidden;" title="Cargando localidades..." />
			</p>
			<p style="margin-top:-8px;">
				<label class="ContenidoSeccion" for="localidad">Localidad</label>
				<select id="localidad" name="localidad" style="margin-left:37px;" title="Localidad" validar="true"></select>
			</p>
			<p style="margin-top:-8px;">
				<label class="ContenidoSeccion" for="actividad">Actividad</label>
				<select id="actividad" name="actividad" style="margin-left:37px;" title="Actividad" validar="true"></select>
			</p>
			<p style="margin-top:-8px;">
				<label class="ContenidoSeccion" for="ciiu">CIIU</label>
				<select id="ciiu" name="ciiu" style="margin-left:62px; width:440px;"></select>
			</p>
			<p style="margin-top:-8px;">
				<label class="ContenidoSeccion" for="trabajadores"># Trabajadores</label>
				<input id="trabajadores" maxlength="8" name="trabajadores" style="width:80px:" title="# Trabajadores" type="text" validarEntero="true" value="<?= ($_REQUEST["id"] == -1)?"":$row["EU_TRABAJADORES"]?>">
			</p>
			<p align="right" style="margin-right:16px; margin-top:-4px;">
				<input class="btnGrabar" type="submit" value="" />
<?
if ($_REQUEST["id"] != -1) {
?>
				<input class="btnDarDeBaja" style="margin-left:8px;" type="button" value="" onClick="eliminar()">
<?
}
?>
			</p>
		</form>
		<script type="text/javascript">
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "provincia";
$RCparams = array();
$RCquery =
	"SELECT zg_id id, zg_descripcion detalle
		 FROM afi.azg_zonasgeograficas
		WHERE zg_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = ($_REQUEST["id"] == -1)?-1:$row["EU_IDZONAGEOGRAFICA"];
FillCombo();

$RCfield = "localidad";
$RCselectedItem = ($_REQUEST["id"] == -1)?-1:$row["EU_IDLOCALIDAD"];
if ($row["EU_IDZONAGEOGRAFICA"] == 2) {
	$RCparams = array();
	$RCquery =
		"SELECT 0 id, 'Capital Federal' detalle
			 FROM DUAL";
	FillCombo(false);
}
else {
	$RCparams = array(":provincia" => nullIsEmpty($idProvincia));
	$RCquery =
		"SELECT cp_id id, cp_localidadcap detalle
			 FROM art.ccp_codigopostal
			WHERE cp_fechabaja IS NULL
				AND cp_provincia = :provincia
	 ORDER BY 2";
	FillCombo();
}

$RCfield = "actividad";
$RCparams = array();
$RCquery =
	"SELECT ta_id id, ta_detalle detalle
		 FROM afi.ata_tipoactividad
		WHERE ta_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = ($_REQUEST["id"] == -1)?-1:$row["EU_IDTIPOACTIVIDAD"];
FillCombo();

$RCfield = "ciiu";
$RCparams = array();
$RCquery =
	"SELECT ac_id id, ac_codigo || ' - ' || UPPER(ac_descripcion) detalle
		 FROM cac_actividad
		WHERE LENGTH(ac_codigo) = 6
 ORDER BY 2";
$RCselectedItem = ($_REQUEST["id"] == -1)?-1:$row["EU_IDACTIVIDAD"];
FillCombo();
?>
			document.getElementById('provincia').focus();
		</script>
	</body>
</html>