<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT hd_autorizado, hd_direccion, hd_nombre, hd_telefono, hd_url
		 FROM rrhh.rhd_delivery
		WHERE hd_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('btnDarBaja').style.display = 'block';
		getElementById('divTitulo').innerHTML = 'EDITAR ESTABLECIMIENTO';
		getElementById('autorizado').checked = ('<?= $row["HD_AUTORIZADO"]?>' == 'S');
		getElementById('direccion').value = '<?= $row["HD_DIRECCION"]?>';
		getElementById('id').value = '<?= $_REQUEST["id"]?>';
		getElementById('link').value = '<?= $row["HD_URL"]?>';
		getElementById('nombre').value = '<?= $row["HD_NOMBRE"]?>';
		getElementById('telefono').value = '<?= $row["HD_TELEFONO"]?>';
		getElementById('divFormAgregarLocal').style.display = 'block';
		getElementById('nombre').focus();
	}
</script>