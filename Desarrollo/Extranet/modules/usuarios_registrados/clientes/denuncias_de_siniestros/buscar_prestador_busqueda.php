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
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));

if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {		// Si es true le paso el prestador a la ventana de la denuncia..
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT ca_nombrefanta, ca_telefono, art.utiles.armar_domicilio(ca_calle, ca_numero, ca_pisocalle, ca_departamento, ca_localidad) || ' - ' || pv_descripcion domicilio
			 FROM art.cpr_prestador, cpv_provincias
			WHERE ca_provincia = pv_codigo
				AND ca_identificador = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	with (parent.document) {
		getElementById('domicilioPrestador').value = '<?= $row["DOMICILIO"]?>';
		getElementById('idPrestador').value = '<?= $_REQUEST["id"]?>';
		getElementById('razonSocialPrestador').value = '<?= $row["CA_NOMBREFANTA"]?>';
		getElementById('telefonoPrestador').value = '<?= $row["CA_TELEFONO"]?>';

		getElementById('domicilioPrestador').readOnly = true;
		getElementById('razonSocialPrestador').readOnly = true;
		getElementById('telefonoPrestador').readOnly = true;

		getElementById('spanDomicilioPrestador').innerHTML		 = getElementById('domicilioPrestador').value;
		getElementById('spanPrestador').innerHTML					 = getElementById('razonSocialPrestador').value;
		getElementById('spanRazonSocialPrestador').innerHTML	 = getElementById('razonSocialPrestador').value;
		getElementById('spanTelefonoPrestador').innerHTML		 = getElementById('telefonoPrestador').value;
	}
	parent.divWin.close();
</script>
<?
	exit;
}


$domicilio = "";
if (isset($_REQUEST["domicilio"]))
	$domicilio = $_REQUEST["domicilio"];

$localidad = "";
if (isset($_REQUEST["localidad"]))
	$localidad = $_REQUEST["localidad"];

$nombre = "";
if (isset($_REQUEST["nombre"]))
	$nombre = $_REQUEST["nombre"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$params = array();
$where = "";

if ($domicilio != "") {
	$params[":calle"] = $domicilio."%";
	$where.= " AND UPPER(ca_calle) LIKE UPPER(:calle)";
}

if ($localidad != "") {
	$params[":localidad"] = $localidad."%";
	$where.= " AND UPPER(ca_localidad) LIKE UPPER(:localidad)";
}

if ($nombre != "") {
	$params[":nombre"] = $nombre."%";
	$where.= " AND UPPER(ca_nombrefanta) LIKE UPPER(:nombre)";
}


$sql =
	"SELECT ¿ca_identificador?,
					ca_nombrefanta ¿prestador?,
					tp_descripcion ¿tipo?,
					¿ca_localidad?,
					pv_descripcion ¿provincia?,
					art.utiles.armar_domicilio(ca_calle, ca_numero, ca_pisocalle, ca_departamento, NULL) ¿domicilio?
		 FROM art.cpr_prestador, art.mtp_tipoprestador, cpv_provincias
		WHERE tp_codigo = ca_especialidad
			AND ca_provincia = pv_codigo
			AND ca_cartillaweb IN('S', 'A')
			AND NVL(ca_visible, 'S') = 'S'
			AND ca_fechabaja IS NULL _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("", 8, true, false, -1, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t", ""));
$grilla->addColumn(new Column("Prestador"));
$grilla->addColumn(new Column("Tipo"));
$grilla->addColumn(new Column("Localidad"));
$grilla->addColumn(new Column("Provincia"));
$grilla->addColumn(new Column("Domicilio"));
$grilla->setColsSeparator(true);
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
$grilla->setShowProcessMessage(true);
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