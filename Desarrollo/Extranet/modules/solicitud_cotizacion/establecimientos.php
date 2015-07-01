<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Establecimientos</title>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
	</head>

	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<?
if ($_REQUEST["idsolicitud"] == -1) {
?>
	<p>
		<input class="btnAgregar" type="button" value="" onClick="parent.showEstablecimientoWindow(<?= $_REQUEST["idsolicitud"]?>, -1);">
	</p>
<?
}
?>
		<div align="center" id="divContentEstablecimientos" name="divContentEstablecimientos">
<?
$showProcessMsg = false;

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

if ($_REQUEST["idsolicitud"] == -1) {
	$params = array(":idsolicitud" => $_REQUEST["idsolicitud"],
									":tiposolicitud" => $_REQUEST["tiposolicitud"],
									":usualta" => $_REQUEST["usualta"]);
	$sql =
		"SELECT eu_id ¿id?, eu_id ¿id2?, ¿zg_descripcion?, CASE WHEN zg_id = 2 THEN 'Capital Federal' ELSE cp_localidadcap END ¿localidad?, ¿ta_detalle?,
						ac_codigo || ' - ' || ac_descripcion ¿ciiu?, ¿eu_trabajadores?
			 FROM afi.aeu_establecimientos, afi.azg_zonasgeograficas, art.ccp_codigopostal, afi.ata_tipoactividad, cac_actividad
			WHERE eu_idzonageografica = zg_id(+)
				AND eu_idlocalidad = cp_id(+)
				AND eu_idtipoactividad = ta_id(+)
				AND eu_idactividad = ac_id(+)
				AND eu_idsolicitud = :idsolicitud
				AND eu_tiposolicitud = :tiposolicitud
				AND eu_usualta = :usualta
				AND eu_fechabaja IS NULL
	 ORDER BY eu_id";
}
else {
	$params = array(":idsolicitud" => $_REQUEST["idsolicitud"],
									":tiposolicitud" => $_REQUEST["tiposolicitud"]);
	$sql =
		"SELECT eu_id ¿id?, eu_id ¿id2?, ¿zg_descripcion?, CASE WHEN zg_id = 2 THEN 'Capital Federal' ELSE cp_localidadcap END ¿localidad?, ¿ta_detalle?,
						ac_codigo || ' - ' || ac_descripcion ¿ciiu?, ¿eu_trabajadores?
			 FROM afi.aeu_establecimientos, afi.azg_zonasgeograficas, art.ccp_codigopostal, afi.ata_tipoactividad, cac_actividad
			WHERE eu_idzonageografica = zg_id(+)
				AND eu_idlocalidad = cp_id(+)
				AND eu_idtipoactividad = ta_id(+)
				AND eu_idactividad = ac_id(+)
				AND eu_idsolicitud = :idsolicitud
				AND eu_tiposolicitud = :tiposolicitud
				AND eu_fechabaja IS NULL
	 ORDER BY eu_id";
}

$grilla = new Grid(15, 5);
if ($_REQUEST["idsolicitud"] == -1)		// Si es un alta muestro el botón, sino no..
	$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "/modules/solicitud_cotizacion/editar_establecimiento.php?idsolicitud=".$_REQUEST["idsolicitud"], "gridFirstColumn"));
else
	$grilla->addColumn(new Column("", 8, false));
$grilla->addColumn(new Column("", -1, false));
$grilla->addColumn(new Column("Provincia"));
$grilla->addColumn(new Column("Localidad"));
$grilla->addColumn(new Column("Actividad"));
$grilla->addColumn(new Column("CIIU"));
$grilla->addColumn(new Column("# Trabajadores"));
$grilla->setColsSeparator(true);
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
$grilla->setSql($sql);
$grilla->Draw();
?>
		</div>
		<br />
		<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<script type="text/javascript">
			function CopyContent() {
				try {
					window.parent.document.getElementById('divContentEstablecimientos').innerHTML = document.getElementById('divContentEstablecimientos').innerHTML;
				}
				catch(err) {
					//
				}
			}

			CopyContent();
		</script>
	</body>
</html>