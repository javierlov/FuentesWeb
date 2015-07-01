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

$RCfield = "reporta";
$RCparams = array(":empresa" => $_REQUEST["idempresa"]);
$RCquery =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = $_REQUEST["idreporta"];
FillCombo();

$RCfield = "referenteRrhh";
$RCparams = array(":empresa" => $_REQUEST["idempresa"], ":id" => $_REQUEST["idempleado"]);
$RCquery =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_id <> :id
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = $_REQUEST["referenterrhh"];
FillCombo();
?>
	window.parent.cambiarReporta();
</script>