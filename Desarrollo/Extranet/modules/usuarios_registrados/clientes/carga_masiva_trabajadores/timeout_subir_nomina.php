<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 55));

if (!$_SESSION["pageLoadOk"]) {
?>
<script type="text/javascript">
	alert('Ocurri� un error al intentar subir la n�mina. [Servicio bajo]');
</script>
<?
}
?>