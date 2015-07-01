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
?>
<script type="text/javascript">
<?
	// FillCombos..
	$excludeHtml = true;
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

	$RCwindow = "window.parent";

	$RCfield = "establecimiento";
	$RCparams = array(":contrato" => $_REQUEST["c"]);
	$RCquery =
		"SELECT es_id id, es_nroestableci || ' - ' || es_nombre detalle
			 FROM aes_establecimiento
			WHERE es_fechabaja IS NULL
				AND es_contrato = :contrato
	 ORDER BY 2";
	 $RCselectedItem = -1;
	FillCombo();
?>
</script>