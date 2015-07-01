<?
validarSesion(isset($_SESSION["isOrganismoPublico"]));
?>
<script type="text/javascript">
	window.parent.location.href = '/index.php?pageid=46&page=paso3.php&to=t&id=<?= $_REQUEST["id"]?>';
</script>