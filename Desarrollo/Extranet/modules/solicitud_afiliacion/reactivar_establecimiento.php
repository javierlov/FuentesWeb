<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idModulo"]);


if ((isset($_REQUEST["r"])) and ($_REQUEST["r"] == "t")) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"UPDATE ase_solicitudestablecimiento
				SET se_fechabaja = NULL,
						se_tipoestablecimiento = NVL(se_tipoestablecimiento, 'P'),
						se_usubaja = NULL
		  WHERE se_id = :id";
	DBExecSql($conn, $sql, $params);
}
?>
<html>
	<head>
		<script type="text/javascript">

<?
if ((isset($_REQUEST["r"])) and ($_REQUEST["r"] == "t")) {
?>
			function refrescar() {
				window.parent.parent.document.getElementById('iframeEstablecimientos').contentWindow.location.reload(true);
				window.parent.parent.divWin.close();
			}

			alert('El establecimiento ha sido reactivado.');
			setTimeout('refrescar()', 1500);
<?
}
else {
?>
			if (confirm('¿ Realmente desea reactivar este establecimiento ?'))
				document.location.href = '<?= $_SERVER["PHP_SELF"]?>' + '?id=<?= $_REQUEST["id"]?>&idModulo=<?= $_REQUEST["idModulo"]?>&r=t';
<?
}
?>
		</script>
	</head>
</html>