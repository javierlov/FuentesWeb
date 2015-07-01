<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
?>
<html>
	<head>
		<script type="text/javascript">
			parent.parent.abrirVentanaTelefono('<?= $_REQUEST["s"]?>', '<?= $_REQUEST["idModulo"]?>', <?= $_REQUEST["id"]?>, <?= $_REQUEST["idTablaPadre"]?>, '<?= $_REQUEST["tablaTel"]?>', '<?= $_REQUEST["campoClave"]?>', '<?= $_REQUEST["prefijo"]?>', '<?= $_REQUEST["tipo"]?>');
		</script>
	</head>
</html>