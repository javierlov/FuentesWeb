<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


try {
	$valor = $_REQUEST["campo"];

	$params = array(":valor" => $valor, ":id" => $_REQUEST["id"]);
	$sql =
		"UPDATE rrhh.rba_boletinesarteria
				SET ba_".$_REQUEST["tipo"]." = ".IIF(($_REQUEST["tipo"] == "fecha"), "TO_DATE(:valor, 'dd/mm/yyyy')", ":valor")."
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
<?
	switch ($_REQUEST["tipo"]) {
		case "ano":
			$valor = "Año ".decimalToRomana($_REQUEST["campo"]);
			break;
		case "fecha":
			$vals = split("/", $_REQUEST["campo"]);
			$valor = GetDayName(date("N", strtotime($vals[2]."-".$vals[1]."-".$vals[0])))." ".$vals[0]." de ".GetMonthName($vals[1])." de ".$vals[2];
			break;
		case "numero":
			$valor = "Número ".decimalToRomana($_REQUEST["campo"]);
			break;
	}

	if ($_REQUEST["tipo"] != "emailsContacto") {
?>
	parent.document.getElementById('<?= $_REQUEST["tipo"]?>').innerText = '<?= $valor?>';
<?
}
?>
	parent.divWin.close();
</script>