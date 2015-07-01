<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ge_descripcion
		 FROM age_grupoeconomico
		WHERE ge_id = :id";
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('idHolding').value = '<?= $_REQUEST["id"]?>';
		getElementById('holding').value = '<?= ValorSql($sql, "", $params)?>';
	}
	window.parent.divWin.close();
</script>