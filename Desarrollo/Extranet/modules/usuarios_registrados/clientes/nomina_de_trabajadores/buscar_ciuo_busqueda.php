<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));

if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {		// Si es true le paso el ciuo a la ventana del trabajador..
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT ci_codigo, ci_codigo || ' - ' || ci_descripcion descripcion
			 FROM cci_ciuo
			WHERE ci_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	with (parent.document) {
		getElementById('ciuo').innerHTML = '<?= $row["DESCRIPCION"]?>';
		getElementById('idCiuo').value = '<?= $row["CI_CODIGO"]?>';
		getElementById('imgQuitarCiuo').style.visibility = 'visible';
	}
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


$params = array();
$where = "";

if ($_REQUEST["codigo"] != "") {
	$params[":codigo"] = $_REQUEST["codigo"];
	$where.= " AND ci_codigo = :codigo";
}

if ($_REQUEST["descripcion"] != "") {
	$params[":descripcion"] = "%".strtoupper($_REQUEST["descripcion"])."%";
	$where.= " AND UPPER(ci_descripcion) LIKE :descripcion";
}

$sql =
	"SELECT ¿ci_id?, ¿ci_codigo?, ¿ci_descripcion?
		 FROM cci_ciuo
		WHERE 1 = 1 _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("", 8, true, false, -1, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t", ""));
$grilla->addColumn(new Column("Código", 16));
$grilla->addColumn(new Column("Descripción"));
$grilla->setColsSeparator(true);
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
$grilla->setSql($sql);
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>