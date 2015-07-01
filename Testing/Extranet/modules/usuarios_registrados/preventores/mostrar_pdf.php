<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isPreventor"]));

$params = array(":id" => $_REQUEST["id"]);
if ((isset($_REQUEST["b"])) and ($_REQUEST["b"] == "s"))		// Es un formulario en blanco..
	$sql = "SELECT tf_patharchivoenblanco FROM hys.htf_tipoformulario WHERE tf_id = :id";
else		// Es un formulario asociado a un contrato..
	$sql = "SELECT fg_archivo FROM hys.hfg_formulariogenerado WHERE fg_id = :id";
$file = DATA_PREVENCION.ValorSql($sql, "", $params);
?>
<script type="text/javascript">
<?
if ((is_file($file)) and (file_exists($file))) {
?>
	window.open('<?= getFile($file)?>', 'extranetWindow');
<?
}
else {
?>
	alert('El archivo solicitado no está disponible.');
<?
}
?>
</script>