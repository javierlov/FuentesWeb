<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(($_SESSION["isAdminTotal"]) or (validarPermisoClienteXModulo($_SESSION["idUsuario"], 66)));
?>
<script type="text/javascript">
	var contratos = window.parent.parent.document.getElementById('contratos').value;
	window.parent.parent.document.getElementById('contratos').value = contratos.replace(',<?= $_REQUEST["id"]?>', '');
	window.parent.parent.document.getElementById('iframeEmpresas').src = '/modules/usuarios_registrados/clientes/administracion_responsables_contrato/empresas.php?c=' + window.parent.parent.document.getElementById('contratos').value;
</script>