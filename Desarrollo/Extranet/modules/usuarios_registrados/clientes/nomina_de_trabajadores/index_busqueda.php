<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));


set_time_limit(120);

$pagina = $_SESSION["BUSQUEDA_NOMINA_TRABAJADORES"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_NOMINA_TRABAJADORES"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$_SESSION["BUSQUEDA_NOMINA_TRABAJADORES"] = array("buscar" => "S",
																									"cuil" => $_REQUEST["cuil"],
																									"establecimiento" => $_REQUEST["establecimiento"],
																									"nombre" => $_REQUEST["nombre"],
																									"ob" => $ob,
																									"pagina" => $pagina);

$params = array(":contrato" => $_SESSION["contrato"]);
$where = "";

if ($_REQUEST["cuil"] != "") {
	$params[":cuil"] = str_replace("-", "", $_REQUEST["cuil"]);
	$where.= " AND tj_cuil = :cuil";
}

if ($_REQUEST["nombre"] != "") {
	$params[":nombre"] = "%".$_REQUEST["nombre"]."%";
	$where.= " AND UPPER(tj_nombre) LIKE UPPER(:nombre)";
}

if ($_REQUEST["establecimiento"] != -1) {
	$params[":idestablecimiento"] = $_REQUEST["establecimiento"];
	$where.= " AND es_id = :idestablecimiento";
}

$sql =
	"SELECT DISTINCT ¿tj_id?, tj_id ¿id2?, ¿tj_nombre?, art.utiles.armar_cuit(tj_cuil) ¿cuil?
							FROM ctj_trabajador, crl_relacionlaboral, cre_relacionestablecimiento, aes_establecimiento
						 WHERE tj_id = rl_idtrabajador
							 AND rl_id = re_idrelacionlaboral(+)
							 AND re_idestablecimiento = es_id(+)
							 AND rl_contrato = :contrato _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("E", 0, true, false, -1, "btnEditar", "/nomina-trabajadores/modificacion-trabajador", "", -1, true, -1, "Editar", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("B", 0, true, false, -1, "btnQuitar", "/nomina-trabajadores/baja-trabajador", "", -1, true, -1, "Dar de Baja", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("Trabajador"));
$grilla->addColumn(new Column("C.U.I.L."));
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowProcessMessage(true);
$grilla->setShowTotalRegistros(true);
$grilla->setSql($sql);
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