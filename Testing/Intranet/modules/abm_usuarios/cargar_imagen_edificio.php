<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


$params = array(":id" => $_REQUEST["e"]);
$sql =
	"SELECT es_imagenintranet
		 FROM art.des_delegacionsede
		WHERE es_id = :id";
?>
<script>
	var mapa = '<?= ValorSql($sql, "", $params);?>';

	with (parent.document.getElementById('Mapa')) {
		if (mapa == '')
			style.display = 'none';
		else {
			style.display = 'block';
			src = '/Images/Mapas/<?= ValorSql($sql, "", $params);?>';
		}
	}
</script>