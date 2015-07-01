<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

$cuit = str_replace("-", "", $_REQUEST["cuit"]);
$sql =
	"SELECT es_id id, es_nroestableci || ' - ' || es_nombre detalle
		 FROM afi.aes_establecimiento, afi.aco_contrato, afi.aem_empresa
		WHERE es_fechabaja IS NULL
		  AND em_id = co_idempresa
		  AND co_contrato = es_contrato 
		  AND co_contrato = art.afiliacion.get_ultcontrato (em_cuit)
		  AND em_cuit = :cuit
 ORDER BY 2";
$comboEstablecimiento = new Combo($sql, "establecimiento");
$comboEstablecimiento->addParam(":cuit", $cuit);


$params = array(":cuit" => $cuit);
$sql = 
	" SELECT em_nombre, co_contrato 
		FROM afi.aes_establecimiento, afi.aco_contrato, afi.aem_empresa
	   WHERE es_fechabaja IS NULL
		 AND em_id = co_idempresa
		 AND co_contrato = es_contrato 
		 AND co_contrato = art.afiliacion.get_ultcontrato (em_cuit)
		 AND em_cuit = :cuit";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	window.parent.document.getElementById('razonSocial').value = '<?= $row["EM_NOMBRE"];?>';
	window.parent.document.getElementById('contrato').value = '<?= $row["CO_CONTRATO"];?>';
	window.parent.document.getElementById('establecimiento').parentNode.innerHTML = '<?= $comboEstablecimiento->draw();?>';
</script>