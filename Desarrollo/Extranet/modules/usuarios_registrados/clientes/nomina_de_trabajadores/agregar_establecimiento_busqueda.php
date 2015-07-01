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

if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {		// Si es true le paso la empresa a la ventana del usuario..
?>
<script type="text/javascript">
	with (parent.document) {
		getElementById('establecimientos').value = getElementById('establecimientos').value + ',<?= $_REQUEST["id"]?>';
		getElementById('iframeEstablecimientos').src = '/modules/usuarios_registrados/clientes/nomina_de_trabajadores/establecimientos.php?rl=<?= $_REQUEST["rl"]?>&e=' + getElementById('establecimientos').value;
	}
	parent.divWin.close();
</script>
<?
	exit;
}


$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$params = array(":contrato" => $_SESSION["contrato"]);
$where = "";

if ($_REQUEST["nombre"] != "") {
	$params[":nombre"] = "%".$_REQUEST["nombre"]."%";
	$where.= " AND UPPER(es_nombre) LIKE UPPER(:nombre)";
}

$sql =
	"SELECT ¿es_id?,
					¿es_nombre?,
					art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, NULL) || art.utiles.armar_localidad(es_cpostal, NULL, es_localidad, es_provincia) ¿domicilio?
		 FROM aes_establecimiento
		WHERE es_fechabaja IS NULL
			AND es_contrato = :contrato _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("", 8, true, false, -1, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t&rl=".$_REQUEST["rl"], ""));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("Domicilio"));
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