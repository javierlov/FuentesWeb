<?
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


if (!isset($_SESSION["permisosControlGestion"][$_REQUEST["usuario"]]))
	$_SESSION["permisosControlGestion"][$_REQUEST["usuario"]] = array("N", 0, "N", "N", "N");
?>
<script>
	with (window.parent.document) {
		getElementById('guardadoOk').style.display = 'none';

		getElementById('ejecutiva').checked = <?= ($_SESSION["permisosControlGestion"][$_REQUEST["usuario"]][0] == "S")?"true":"false"?>;
		getElementById('gestion').checked = <?= ($_SESSION["permisosControlGestion"][$_REQUEST["usuario"]][2] == "S")?"true":"false"?>;
		getElementById('informesGestion').checked = <?= ($_SESSION["permisosControlGestion"][$_REQUEST["usuario"]][4] == "S")?"true":"false"?>;
		getElementById('nivel').value = <?= $_SESSION["permisosControlGestion"][$_REQUEST["usuario"]][1]?>;
		getElementById('operativa').checked = <?= ($_SESSION["permisosControlGestion"][$_REQUEST["usuario"]][3] == "S")?"true":"false"?>;
	}
</script>