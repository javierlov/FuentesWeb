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

$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT zg_idprovincia
		 FROM afi.azg_zonasgeograficas
		WHERE zg_id = :id";
$idProvincia = ValorSql($sql, "", $params);
?>
<script type="text/javascript">
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window.parent";
$RCfield = "localidad";
$RCselectedItem = -1;

if ($_REQUEST["id"] == 2) {
	$RCparams = array();
	$RCquery =
		"SELECT 0 id, 'Capital Federal' detalle
			 FROM DUAL";
	FillCombo(false);
}
else {
	$RCparams = array(":provincia" => $idProvincia);
	$RCquery =
		"SELECT cp_id id, cp_localidadcap detalle
			 FROM art.ccp_codigopostal
			WHERE cp_fechabaja IS NULL
				AND cp_provincia = :provincia
	 ORDER BY 2";
	FillCombo();
}
?>
	window.parent.document.getElementById('imgLoadingProvincia').style.visibility = 'hidden';
</script>