<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$params = array();
$where = "";

if ($_REQUEST["codigo"] != "") {
	$params[":codigo"] = "%".$_REQUEST["codigo"]."%";
	$where.= " AND ac_codigo LIKE :codigo";
}
if ($_REQUEST["descripcion"] != "") {
	$params[":descripcion"] = "%".RemoveAccents($_REQUEST["descripcion"])."%";
	$where.= " AND UPPER(ART.UTILES.reemplazar_acentos(ac_descripcion)) LIKE UPPER(:descripcion)";
}

$sql =
	"SELECT ac_id ¿id?, ac_codigo ¿codigo?, ac_descripcion ¿descripcion?
		 FROM cac_actividad
		WHERE ac_fechabaja IS NULL
			AND LENGTH(ac_codigo) = 6 _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("", -1, true, false, -1, "btnSeleccionar", "/modules/solicitud_cotizacion/seleccionar_ciiu.php?trgt=".$_REQUEST["trgt"], ""));
$grilla->addColumn(new Column("Código", 40));
$grilla->addColumn(new Column("Descripción"));
$grilla->setColsSeparator(true);
$grilla->setColsSeparatorColor("#c0c0c0");
$grilla->setExtraConditions(array($where, $where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
$grilla->setRowsSeparatorColor("#c0c0c0");
$grilla->setShowProcessMessage(true);
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