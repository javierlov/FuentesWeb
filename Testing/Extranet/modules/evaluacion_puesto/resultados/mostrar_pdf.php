<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");

$file = DATA_SISTEMA_GESTION_RRHH.basename($_REQUEST["a"]);
?>
<script type="text/javascript">
	window.parent.document.getElementById('divLoading').style.display = 'none';
	window.parent.document.getElementById('iframeProcesando').style.display = 'block';
	window.open('<?= getFile($file)?>', 'iframeProcesando', 'location=0');
</script>