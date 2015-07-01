<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));

$params = array(":codigo" => $_REQUEST["codigo"]);
$sql =
	"SELECT ac_descripcion, ac_descripcionok
		 FROM cac_actividad
		WHERE ac_codigo = :codigo
			AND ac_fechabaja IS NULL";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	window.parent.document.getElementById('<?= $_REQUEST["target"]?>').innerHTML = '<?= $row["AC_DESCRIPCION"]?>';
	if ('<?= $_REQUEST["target"]?>' == 'ciiu1Descripcion') {
		if ('<?= $row["AC_DESCRIPCIONOK"]?>' == 'N')
			window.parent.document.getElementById('actividadReal').value = '';
		else
			window.parent.document.getElementById('actividadReal').value = '<?= $row["AC_DESCRIPCION"]?>';
	}
</script>