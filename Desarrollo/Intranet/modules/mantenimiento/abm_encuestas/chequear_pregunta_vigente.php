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
$params = array(":idpregunta" => $_REQUEST["preguntaid"]);
$sql =
	"SELECT COUNT(*)
		 FROM rrhh.rrp_respuestaspreguntas
		WHERE rp_idpregunta = :idpregunta";
$tot = valorSql($sql, 0, $params);
if ($tot == 0) {
	echo "var eliminar = true;";
}
else {
	if ($tot == 1)
		$msg = "1 usuario";
	else
		$msg = $tot." usuarios";
	echo "var eliminar = confirm('Esta pregunta ya ha sido votada por ".$msg.". ¿ Desea eliminarla de todas formas ?');";
}
?>
	if (eliminar)
		with (window.parent.document) {
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Label').style.color = '#f00';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>').readOnly = true;
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>').style.backgroundColor = '#ccc';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>').style.color = '#f00';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Baja').value = 'T';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>BtnBaja').style.display = 'none';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>BtnAgregar').style.display = 'none';
			getElementById('divPreguntas<?= $_REQUEST["numeropregunta"]?>').style.display = 'none';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>BtnAgregar').style.display = 'none';

			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>MultiLabel').style.display = 'none';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Multi').style.display = 'none';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>LibreLabel').style.display = 'none';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Libre').style.display = 'none';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>ValidarCheckLabel').style.display = 'none';
			getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>ValidarCheck').style.display = 'none';
		}
</script>