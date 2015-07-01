<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


try {
	$params = array(":legajorrhh" => $_POST["legajo"]);
	$sql =
		"SELECT se_nombre
			 FROM use_usuarios
			WHERE se_legajorrhh = :legajorrhh";
	$result = ValorSql($sql, "Legajo libre.", $params);
}
catch (Exception $e) {
?>
	<script>
		alert(unescape('El legajo debe ser numérico. <?= rawurlencode($e->getMessage())?>'));
	</script>
<?
	exit;
}
?>
<script>
	window.parent.document.getElementById('resultadoLegajo').innerText = '<?= $result?>';
</script>