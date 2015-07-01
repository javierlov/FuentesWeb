<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));

set_time_limit(60);

if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {		// Si es true, le asigno el valor al combo..
?>
<script type="text/javascript">
	parent.document.getElementById('entidad').value = '<?= $_REQUEST["id"]?>';
	if (parent.document.getElementById('entidad').value == '')
		parent.document.getElementById('entidad').value = -1;
	parent.divWin.close();
</script>
<?
	exit;
}


$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "3";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$params = array(":idcanal" => $_REQUEST["c"]);
$sql =
	"SELECT ¿en_id?, ¿en_codbanco?, ¿en_nombre?
		 FROM xen_entidad
		WHERE en_idcanal = :idcanal
			AND en_fechabaja IS NULL";

$where = "";

if ($_REQUEST["codigo"] != "") {
	$where.= " AND en_codbanco = :codbanco";
	$params[":codbanco"] = $_REQUEST["codigo"];
}

if ($_REQUEST["nombre"] != "") {
	$where.= " AND en_nombre LIKE :nombre";
	$params[":nombre"] = "%".strtoupper($_REQUEST["nombre"])."%";
}

$grilla = new Grid(10, 10);
$grilla->addColumn(new Column(" ", 1, true, false, -1, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t&ce=".$_REQUEST["ce"], ""));
$grilla->addColumn(new Column("Código", 60));
$grilla->addColumn(new Column("Nombre"));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowProcessMessage(true);
$grilla->setSql($sql.$where);
$grilla->setTableStyle("GridTableCiiu");
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>