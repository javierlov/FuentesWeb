<?
validarSesion(isset($_SESSION["isOrganismoPublico"]));
?>
<script type="text/javascript">
	window.parent.location.href = '/carga-nomina-personal/paso-3/<?= $_REQUEST["id"]?>';
</script>