<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ac_codigo, ac_descripcion, ac_descripcionok
		 FROM cac_actividad
		WHERE ac_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('<?= $_REQUEST["trgt"]?>').value = '<?= $row["AC_CODIGO"]?>';
		getElementById('<?= $_REQUEST["trgt"]?>Descripcion').innerHTML = '<?= $row["AC_DESCRIPCION"]?>';
		if ('<?= $_REQUEST["trgt"]?>' == 'ciiu1') {
			if ('<?= $row["AC_DESCRIPCIONOK"]?>' == 'N')
				getElementById('actividadReal').value = '';
			else
				getElementById('actividadReal').value = '<?= $row["AC_DESCRIPCION"]?>';
		}
	}
	window.parent.divWin.close();
</script>