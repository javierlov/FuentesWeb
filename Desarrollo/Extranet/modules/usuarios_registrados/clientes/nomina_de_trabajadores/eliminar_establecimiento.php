<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));
?>
<script type="text/javascript">
	var establecimientos = window.parent.parent.document.getElementById('establecimientos').value;
	window.parent.parent.document.getElementById('establecimientos').value = establecimientos.replace(',<?= $_REQUEST["id"]?>', '');
	window.parent.parent.document.getElementById('iframeEstablecimientos').src = '/modules/usuarios_registrados/clientes/nomina_de_trabajadores/establecimientos.php?rl=<?= $_REQUEST["rl"]?>&e=' + window.parent.parent.document.getElementById('establecimientos').value;
</script>