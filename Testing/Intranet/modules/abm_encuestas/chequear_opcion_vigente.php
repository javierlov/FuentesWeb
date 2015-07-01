<script>
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


$sql =
	"SELECT COUNT(*)
		FROM rrhh.rrp_respuestaspreguntas
	 WHERE rp_idopcion = :idopcion";
$params = array(":idopcion" => $_REQUEST["opcionid"]);
$tot = ValorSql($sql, 0, $params);
if ($tot == 0) {
	echo "var eliminar = true;";
}
else {
	if ($tot == 1)
		$msg = "1 usuario";
	else
		$msg = $tot." usuarios";
	echo "var eliminar = confirm('Esta opción ya ha sido votada por ".$msg.". ¿ Desea eliminarla de todas formas ?');";
}
?>
	if (eliminar) {
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>Label').style.color = 'f00';
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>').readOnly = true;
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>').style.color = 'f00';
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>Baja').value = 'T';
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>BtnBaja').style.display = 'none'
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>PO').style.display = 'none'
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>PS').style.display = 'none'
		window.parent.document.getElementById('pregunta<?= $_REQUEST["numeropregunta"]?>Opcion<?= $_REQUEST["numeroopcion"]?>PI').style.display = 'none'
	}
</script>