<script>
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


$sql =
	"SELECT COUNT(*)
		FROM rrhh.rrp_respuestaspreguntas
	 WHERE rp_idpregunta = :idpregunta";
$params = array(":idpregunta" => $_REQUEST["preguntaid"]);
$tot = ValorSql($sql, 0, $params);
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
	if (eliminar) {
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Label').style.color = 'f00';
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>').readOnly = true;
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>').style.color = 'f00';
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Baja').value = 'T';
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>BtnBaja').style.display = 'none';
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>BtnAgregar').style.display = 'none';
		window.parent.document.getElementById('divPreguntas<?= $_REQUEST["numeropregunta"]?>').style.display = 'none';
	}
</script>