<?
if (!isset($_SESSION["idUsuario"])) {
?>
	<script type="text/javascript">
		window.location.href = '/modules/admin_users_web/login.php';
	</script>
<?
	exit;
}

$esAlta = ($_REQUEST["id"] == -1);

if (!$esAlta) {
	$params = array(":idusuarioextranet" => $_REQUEST["id"]);
	$sql =
		"SELECT uc_email, uc_nombre
			 FROM web.wuc_usuariosclientes
			WHERE uc_idusuarioextranet = :idusuarioextranet";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}
?>
<link href="/modules/admin_users_web/css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="/js/validations.js"></script>
<script type="text/javascript">
	function darBaja() {
		if (confirm('¿ Realmente desea dar de baja a este usuario ?'))
			with (document) {
				getElementById('accion').value = 'B';
				getElementById('formUsuario').submit();
			}
	}
</script>
<form action="/modules/admin_users_web/procesar_usuario.php" id="formUsuario" method="post" name="formUsuario" target="_self">
	<input id="accion" name="accion" type="hidden" value="<?= ($esAlta)?"A":"M"?>" />
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div align="center" class="title" colspan="2"><b><?= ($esAlta)?"Alta":"Modificación"?> de Usuario</b></div>
		<table class="table">
			<tr>
				<td><label>Usuario</label></td>
				<td><input autofocus id="usuario" name="usuario" type="text" value="<?= (!$esAlta)?$row["UC_NOMBRE"]:""?>" /></td>
			</tr>
			<tr>
				<td><label>e-Mail</label></td>
				<td><input <?= ($esAlta)?"":"disabled"?> id="email" name="email" style="<?= ($esAlta)?"":"background-color:#ccc;"?>" type="text" value="<?= (!$esAlta)?$row["UC_EMAIL"]:""?>" /></td>
			</tr>
		</table>
<?
if ((isset($_REQUEST["g"])) and ($_REQUEST["g"] == "o")) {
?>
		<div>Datos guardados correctamente</div>
<?
}
?>
		<div class="botones">
			<input type="submit" value="Guardar" name="btnBuscar" />
<?
if (!$esAlta) {
?>
			<input name="btnNuevoUsuario0" type="button" value="Dar de Baja" onClick="darBaja()" />
<?
}
?>
			<input id="btnVolver" name="btnVolver" type="button" value="Volver" onClick="window.location.href = '/modules/admin_users_web/'" />
		</div>
	</div>
</form>