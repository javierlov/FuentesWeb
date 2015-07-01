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
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 60));

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "1";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$params = array();
$where = "";

if ($_REQUEST["localidad"] != -1) {
	$params[":localidad"] = $_REQUEST["localidad"];
	$where.= " AND ca_localidad = :localidad";
}

if ($_REQUEST["provincia"] != -1) {
	$params[":provincia"] = $_REQUEST["provincia"];
	$where.= " AND ca_provincia = :provincia";
}

if ($_REQUEST["tipoPrestacion"] != -1) {
	$params[":tipoprestacion"] = $_REQUEST["tipoPrestacion"];
	$where.= " AND ca_especialidad = :tipoprestacion";
}

$sql =
	"SELECT cpr.ca_nombrefanta ¿prestador?,
					mtp.tp_descripcion ¿tipo_prestador?,
					art.utiles.armar_domicilio(cpr.ca_calle, cpr.ca_numero, cpr.ca_pisocalle, cpr.ca_departamento, NULL) ¿domicilio?,
					ca_localidad ¿localidad?,
					cpr.ca_telefono ¿telefono?,
					cpr.ca_fax ¿fax?
		 FROM art.cpr_prestador cpr, art.mtp_tipoprestador mtp
		WHERE cpr.ca_cartillaweb IN('S', 'A')
			AND mtp.tp_codigo = cpr.ca_especialidad
			AND NVL(cpr.ca_visible, 'S') = 'S'
			AND cpr.ca_fechabaja IS NULL _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("Prestador"));
$grilla->addColumn(new Column("Tipo de Prestación"));
$grilla->addColumn(new Column("Domicilio"));
$grilla->addColumn(new Column("Localidad"));
$grilla->addColumn(new Column("Teléfono"));
$grilla->addColumn(new Column("Fax"));
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->Draw();

$_SESSION["sqlCartillaPrestadores"] = $grilla->getSqlFinal(true);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('btnExportar').style.display = 'inline';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>