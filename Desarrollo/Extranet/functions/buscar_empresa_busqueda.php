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


validarSesion(isset($_SESSION["isPreventor"]));

set_time_limit(60);

if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {		// Si es true guardo la fila seleccionada en los campos pasados como parámetro en la ventana padre..
	$params = array(":contrato" => $_REQUEST["id"]);
	$sql =
		"SELECT art.utiles.armar_cuit(em_cuit) cuit, em_nombre
			 FROM aem_empresa, aco_contrato
			WHERE em_id = co_idempresa
				AND co_contrato = :contrato";
	$stmt = DBExecSql($conn, $sql, 	$params);
	$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	with (parent.document) {
		getElementById('contrato').value = '<?= $_REQUEST["id"]?>';
		getElementById('cuit').value = '<?= $row["CUIT"]?>';
		getElementById('nombre').value = '<?= $row["EM_NOMBRE"]?>';
<?
		if ((isset($_REQUEST["ce"])) and ($_REQUEST["ce"] == "t")) {		// Si hay que recagar los establecimientos del módulo de preventores..
?>
			getElementById('iframeProcesando').src = '/modules/usuarios_registrados/preventores/cambia_empresa.php?c=' + <?= $_REQUEST["id"]?>;	
<?
		}
?>
	}
	parent.divWinEmpresa.close();
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
$sql =
	"SELECT co_contrato ¿id?,
					art.utiles.armar_cuit(em_cuit) ¿cuit?,
					¿em_nombre?,
					¿co_contrato?
		 FROM aem_empresa, aco_contrato
		WHERE em_id = co_idempresa
			AND co_contrato = art.get_vultcontrato(em_cuit)";
$where = "";

if ($_REQUEST["contrato"] != "") {
	$where.= " AND co_contrato = :contrato";
	$params[":contrato"] = intval($_REQUEST["contrato"]);
}

if ($_REQUEST["cuit"] != "") {
	$where.= " AND em_cuit = :cuit";
	$params[":cuit"] = sacarGuiones($_REQUEST["cuit"]);
}

if ($_REQUEST["nombre"] != "") {
	$where.= " AND em_nombre LIKE :nombre";
	$params[":nombre"] = "%".strtoupper($_REQUEST["nombre"])."%";
}

$grilla = new Grid(10, 10);
$grilla->addColumn(new Column(" ", 1, true, false, -1, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t&ce=".$_REQUEST["ce"], ""));
$grilla->addColumn(new Column("C.U.I.T.", 60));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("Contrato"));
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