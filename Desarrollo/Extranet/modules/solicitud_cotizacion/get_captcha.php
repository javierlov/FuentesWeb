<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


$params = array(":cuit1" => $_REQUEST["cuit"]."\\", ":cuit2" => $_REQUEST["cuit"]);
$sql =
	"SELECT :cuit1 || os_nombreimagen
		 FROM web.wos_obtenerstatusbcra
		WHERE os_cuit = :cuit2";
$img = "/functions/get_image.php?file=".base64_encode(IMAGENES_STATUS_BCRA.ValorSql($sql, "", $params));
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<script type="text/javascript">
			function refreshCaptcha() {
				with (parent.document.getElementById('iframeProcesando'))
					src = '/modules/solicitud_cotizacion/import_from_bcra.php?cuit=<?= $_REQUEST["cuit"]?>&refresh=true';
			}
		</script>
	</head>
	<body>
		<form action="/modules/solicitud_cotizacion/guardar_codigo_captcha.php" method="post" target="iframeProcesando">
			<input id="cuit" name="cuit" type="hidden" value="<?= $_REQUEST["cuit"]?>" />
			<label for="codigo"><font face="Trebuchet MS" style="font-size: 8pt">Ingrese el código</font></label>
			<input id="codigo" maxlength="10" name="codigo" type="text" />
			<input class="botonGris" type="submit" value="ACEPTAR" />
			<br />
			<br />
<?
if ($_REQUEST["error"] == "t") {
?>
	<span style="color:red; font-size:12px; font-weight:bold;">Código erróneo.</span>
<?
}
else {
?>
			<br />
<?
}
?>
		</form>
		<img src="<?= $img?>" style="margin-bottom:4px;" />
		<br />
		<input class="botonGris" type="button" value="REFRESCAR IMAGEN" onClick="refreshCaptcha()" />
	</body>
	<script type="text/javascript">
		document.getElementById('codigo').focus();
	</script>
</html>