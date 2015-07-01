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


function getComputerAndUserName() {
	return strtoupper(gethostbyaddr($_SERVER['REMOTE_ADDR'])."/".$_SESSION["usuario"]);
}


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 70));


// Acciones a seguir al selecccionar un item..
if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT de_calle, de_codigopostal, de_departamento, de_idprovincia, de_localidad, de_numero, de_piso, de_razonsocial
			 FROM web.wde_datosempresascomitentes
			WHERE de_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('calle').value = '<?= $row["DE_CALLE"]?>';
		getElementById('codigoPostal').value = '<?= $row["DE_CODIGOPOSTAL"]?>';
		getElementById('departamento').value = '<?= $row["DE_DEPARTAMENTO"]?>';
		getElementById('idprovincia').value = '<?= $row["DE_IDPROVINCIA"]?>';
		getElementById('localidad').value = '<?= $row["DE_LOCALIDAD"]?>';
		getElementById('numero').value = '<?= $row["DE_NUMERO"]?>';
		getElementById('piso').value = '<?= $row["DE_PISO"]?>';
		getElementById('razonSocial').value = '<?= $row["DE_RAZONSOCIAL"]?>';
		window.parent.divWin.close();
	}
</script>
<?
	exit;
}


// Acciones a seguir al dar de baja un item..
if ((isset($_REQUEST["b"])) and ($_REQUEST["b"] == "t")) {
	$params = array(":id" => $_REQUEST["id"], ":usubaja" => substr($_SESSION["usuario"], 0, 20));
	$sql =
		"UPDATE web.wde_datosempresascomitentes
				SET de_fechabaja = SYSDATE,
						de_usubaja = :usubaja
			WHERE de_id = :id";
	DBExecSql($conn, $sql, $params);
?>
<script type="text/javascript">
	window.parent.history.back();
</script>
<?
	exit;
}


$showProcessMsg = false;

$razonSocial = "";
if (isset($_REQUEST["razonSocial"]))
	$razonSocial = $_REQUEST["razonSocial"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "3";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$params = array(":idempresa" => $_SESSION["idEmpresa"]);
$sql =
	"SELECT ¿de_id?, de_id ¿id2?, ¿de_razonsocial?
		 FROM web.wde_datosempresascomitentes
		WHERE de_fechabaja IS NULL
			AND de_idempresa = :idempresa";
$where = "";

if ($razonSocial != "") {
	$params[":razonsocial"] = "%".$razonSocial."%";
	$where.= " AND UPPER(de_razonsocial) LIKE UPPER(:razonsocial)";
}

$grilla = new Grid(5, 10);
$grilla->addColumn(new Column("S", 1, true, false, -1, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t", ""));
$grilla->addColumn(new Column("E", 1, true, false, -1, "btnQuitar", $_SERVER["PHP_SELF"]."?b=t", ""));
$grilla->addColumn(new Column("Razón Social"));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
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