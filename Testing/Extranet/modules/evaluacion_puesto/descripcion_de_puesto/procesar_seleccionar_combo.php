<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT pi_descripcion
		 FROM rrhh.dpi_itemconocimiento
		WHERE pi_id = :id";
$itemDescripcion = ValorSql($sql, "", $params);
?>
<html>
	<head>
		<script type="text/javascript">
			with (window.parent.parent) {
				document.getElementById('idCombo<?= $_REQUEST["sfc"]?>').value = <?= $_REQUEST["id"]?>;

				with (document.getElementById('combo<?= $_REQUEST["sfc"]?>')) {
<?
if ($_REQUEST["id"] != -1) {
?>
					style.backgroundColor = '';
					style.fontStyle = '';
<?
} else {
?>
				style.backgroundColor = '#fee';
				style.fontStyle = 'italic';

<?
}
?>
					value = '<?= $itemDescripcion?>';
				}

				divWin3.close();
			}
		</script>
	</head>
</html>