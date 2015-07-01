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

$contrato = $_REQUEST["contrato"];
$sql =
	"SELECT es_id id, es_nroestableci || ' - ' || es_nombre detalle
		 FROM aes_establecimiento
		WHERE es_fechabaja IS NULL
			AND es_contrato = :contrato
 ORDER BY 2";
$comboEstablecimiento = new Combo($sql, "establecimiento");
$comboEstablecimiento->addParam(":contrato", $contrato);


$params = array(":contrato" => $contrato);
$sql = 
	" SELECT em_nombre, em_cuit 
		FROM afi.aem_empresa, afi.aco_contrato 
	   WHERE co_idempresa = em_id 
	        AND  co_contrato = :contrato";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	window.parent.document.getElementById('cuit').value = '<?= $row["EM_CUIT"];?>';
	window.parent.document.getElementById('razonSocial').value = '<?= $row["EM_NOMBRE"];?>';
	window.parent.document.getElementById('establecimiento').parentNode.innerHTML = '<?= $comboEstablecimiento->draw();?>';
</script>