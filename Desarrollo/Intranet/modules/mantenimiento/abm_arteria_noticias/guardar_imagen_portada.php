<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$fileOrigen = IMAGES_EDICION_PATH.$_REQUEST["imgName"];
$partes_ruta = pathinfo($_REQUEST["imgName"]);
$fileDest = IMAGES_ARTERIA_PATH."portada/".$_REQUEST["id"].".".$partes_ruta["extension"];

unlink($fileDest);
if (!rename($fileOrigen, $fileDest))
	unlink($fileOrigen);

try {
	$params = array(":extensionimagen" => $partes_ruta["extension"], ":id" => $_REQUEST["id"]);
	$sql =
		"UPDATE rrhh.rba_boletinesarteria
				SET ba_extensionimagen = :extensionimagen
		  WHERE ba_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script>
	parent.document.getElementById('imgPortada').src = '<?= "/functions/get_image.php?rnd=".date("YmdHni")."&file=".base64_encode($fileDest)?>';
</script>