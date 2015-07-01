<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT ca_localidad id, ca_localidad detalle
		 FROM (SELECT DISTINCT cpr.ca_localidad
											FROM art.cpr_prestador cpr, art.cpv_provincias
										 WHERE cpr.ca_cartillaweb IN('S', 'A')
											 AND cpr.ca_provincia = pv_codigo
											 AND NVL(cpr.ca_visible, 'S') = 'S'
											 AND cpr.ca_fechabaja IS NULL
											 AND ca_provincia = :provincia)
 ORDER BY 2";
$comboLocalidad = new Combo($sql, "localidad");
$comboLocalidad->addParam(":provincia", $_REQUEST["provincia"]);
$comboLocalidad->setFirstItem("- TODAS -");
?>
<script type="text/javascript">
	window.parent.document.getElementById('localidad').parentNode.innerHTML = '<?= $comboLocalidad->draw();?>';
</script>