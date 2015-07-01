<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once("cambiar_empresa_combos.php");
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('empleado').parentNode.innerHTML = '<?= $comboEmpleado->draw();?>';
		getElementById('referenteRrhh').parentNode.innerHTML = '<?= $comboReferenteRrhh->draw();?>';
		getElementById('respondeA').parentNode.innerHTML = '<?= $comboRespondeA->draw();?>';
	}
</script>