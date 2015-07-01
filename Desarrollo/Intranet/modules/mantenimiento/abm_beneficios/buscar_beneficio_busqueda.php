<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


set_time_limit(120);

$pagina = $_SESSION["BUSQUEDA_BENEFICIO_BUSQUEDA"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_BENEFICIO_BUSQUEDA"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$sb = $_SESSION["BUSQUEDA_BENEFICIO_BUSQUEDA"]["sb"];
if (isset($_REQUEST["sb"]))
	$sb = ($_REQUEST["sb"] == "T");

$_SESSION["BUSQUEDA_BENEFICIO_BUSQUEDA"] = array("ob" => $ob,
																								 "nombre" => $_REQUEST["nombreBusqueda"],
																								 "pagina" => $pagina,
																								 "sb" => $sb);

$params = array();
$where = "";

if ($_REQUEST["id"] != 0) {
	$params[":id"] = $_REQUEST["id"];
	$where.= " AND bn_id = :id";
}

if ($_REQUEST["nombreBusqueda"] != "") {
	$params[":nombre"] = "%".$_REQUEST["nombreBusqueda"]."%";
	$where.= " AND UPPER(bn_nombre) LIKE UPPER(:nombre)";
}

$sql =
	"SELECT ¿bn_id?,
					¿bn_nombre?,
					¿bn_fechabaja?
		 FROM rrhh.rbn_beneficios
		WHERE 1 = 1 _EXC1_";
$grilla = new Grid(15, 20);
$grilla->addColumn(new Column(".", 40, true, false, -1, "gridBtnEditar", "/beneficios-abm/", "", -1, true, -1, "Editar"));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("", 0, false, true));
$grilla->setBaja("bn_fechabaja", $sb, true);
$grilla->setExtraConditions(array($where));
$grilla->setFieldBaja("bn_fechabaja");
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowProcessMessage(true);
$grilla->setShowTotalRegistros(true);
$grilla->setSql($sql);
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('id').value = 0;		// Limpio el filtro por id, ya que solo se tiene que mostrar al guardar..
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>