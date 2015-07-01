<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
?>
<script type="text/javascript">
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window.parent";

$RCfield = "entidad";
$RCparams = array(":idcanal" => $_REQUEST["idcanal"]);
$RCquery =
	"SELECT DISTINCT en_id id, en_codbanco || ' - ' || en_nombre detalle
							FROM xen_entidad
						 WHERE en_idcanal = :idcanal
							 AND en_fechabaja IS NULL
					ORDER BY 2";
$RCselectedItem = -1;
FillCombo();
?>
	setTimeout('window.parent.cambiaEntidad(<?= $_REQUEST["idcanal"]?>);', 700);
</script>