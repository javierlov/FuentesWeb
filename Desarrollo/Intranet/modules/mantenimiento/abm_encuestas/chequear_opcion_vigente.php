<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
?>
<script type="text/javascript">
<?
$params = array(":idopcion" => $_REQUEST["opcionid"]);
$sql =
	"SELECT COUNT(*)
		 FROM rrhh.rrp_respuestaspreguntas
		WHERE rp_idopcion = :idopcion";
$tot = valorSql($sql, 0, $params);
if ($tot == 0)
	echo "var eliminar = true;";
else {
	if ($tot == 1)
		$msg = "1 usuario";
	else
		$msg = $tot." usuarios";
	echo "var eliminar = confirm('Esta opción ya ha sido votada por ".$msg.". ¿ Desea eliminarla de todas formas ?');";
}
?>
	if (eliminar)
		with (window.parent.document) {
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>Label').style.color = '#f00';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>').readOnly = true;
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>').style.backgroundColor = '#ccc';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>').style.color = '#f00';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>Baja').value = 'T';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>BtnBaja').style.display = 'none'
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>PO').style.display = 'none'
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>PS').style.display = 'none'
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>PI').style.display = 'none'
		}
</script>