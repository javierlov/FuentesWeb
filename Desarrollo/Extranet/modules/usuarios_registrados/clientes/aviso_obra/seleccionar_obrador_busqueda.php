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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));

set_time_limit(120);

if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, es_localidad) direccion, es_nombre nombre
			 FROM afi.aes_establecimiento
			WHERE es_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	with (parent.document) {
		getElementById('direccionObrador').value = '<?= $row["DIRECCION"]?>';
		getElementById('idObrador').value = <?= $_REQUEST["id"]?>;
		getElementById('nombreObrador').value = '<?= $row["NOMBRE"]?>';
	}
	window.parent.divWin.close();
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
$sql =
	"SELECT ¿es_id?,
					es_nroestableci ¿id?,
					¿domicilio?,
					¿es_localidad?,
					¿es_cpostal?,
					¿pv_descripcion?,
					¿descripcion_obra?
		 FROM (SELECT es_id,
					es_nroestableci,
					art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento)¿domicilio,
					es_localidad,
					es_cpostal,
					pv_descripcion,
					(SELECT ps_descobras
						 FROM hys.hps_programaseguridad
						WHERE ps_cuit = em_cuit
							AND ps_establecimiento = es_nroestableci) descripcion_obra
		 FROM art.cpv_provincias, afi.aem_empresa, afi.aco_contrato, afi.aes_establecimiento
		WHERE es_contrato = co_contrato
			AND em_id = co_idempresa
			AND co_contrato = art.get_vultcontrato(em_cuit)
			AND pv_codigo = es_provincia
			AND es_eventual = 'S'
			AND es_idestabeventual = 2
			AND es_fechabaja IS NULL
			AND es_contrato = :contrato)";
$grilla = new Grid(10, 12);
$grilla->addColumn(new Column("S", 1, true, false, -1, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t", "", -1, true, -1, "Seleccionar"));
$grilla->addColumn(new Column("Nº Establecimiento", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Domicilio"));
$grilla->addColumn(new Column("Localidad"));
$grilla->addColumn(new Column("Código Postal", 152, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Provincia"));
$grilla->addColumn(new Column("Descripción Obra"));
$grilla->addColumn(new Column("", 0, false));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowProcessMessage(true);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->Draw();
?>
<script type="text/javascript">
	window.parent.document.getElementById('divProcesando').style.display = 'none';
	window.parent.document.getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
	window.parent.document.getElementById('divContentGrid').style.display = 'block';
</script>