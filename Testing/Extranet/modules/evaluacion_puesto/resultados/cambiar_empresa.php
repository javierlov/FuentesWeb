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

$RCfield = "referenteRrhh";
$RCparams = array(":empresa" => $_REQUEST["idempresa"]);
$RCquery =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "empleado";
$RCparams = array(":empresa" => $_REQUEST["idempresa"]);
$RCquery =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "respondeA";
$RCparams = array(":empresa" => $_REQUEST["idempresa"]);
$RCquery =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login a
		WHERE pl_empresa = :empresa
			AND EXISTS(SELECT 1
									 FROM rrhh.dpl_login b
									WHERE a.pl_id = b.pl_jefe)
			AND pl_fechabaja IS NULL
ORDER BY 2";
$RCselectedItem = -1;
FillCombo();
?>
</script>