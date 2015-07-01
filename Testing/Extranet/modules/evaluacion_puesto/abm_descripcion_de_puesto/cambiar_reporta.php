<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


$params = array(":id" => $_REQUEST["idreporta"]);
$sql =
	"SELECT gr_detalle
		 FROM rrhh.dpl_login, rrhh.rgr_grupos
		WHERE pl_idgrupo = gr_id
			AND pl_id = :id";
?>
<script type="text/javascript">
	if (window.parent.document.getElementById('grupoJefe') != null)
		window.parent.document.getElementById('grupoJefe').innerHTML = '<?= ValorSql($sql, "", $params)?>';
</script>