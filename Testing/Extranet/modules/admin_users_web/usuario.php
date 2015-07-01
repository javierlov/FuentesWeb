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
<form action="/modules/admin_users_web/procesar_usuario.php" id="formUsuario" method="post" name="formUsuario" target="_self" onSubmit="return ValidarForm(formUsuario)">
	<input id="accion" name="accion" type="hidden" value="<?= ($esAlta)?"A":"M"?>" />
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div align="center">
		<table border="0" cellpadding="0" cellspacing="0" width="95%">
			<tr>
				<td colspan="2">
					<p style="margin-top: 0; margin-bottom: 0">
						<b><font face="Neo Sans" color="#1CA3DB"><?= ($esAlta)?"Alta":"Modificación"?> de Usuario</font></b>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="8">&nbsp;</td>
			</tr>
			<tr>
				<td width="9%"><p style="margin-left: 50px"><font color="#666666" face="Neo Sans" size="2">Usuario</font></td>
				<td width="91%">
					<p style="margin-left: 5px">
						<font face="Trebuchet MS" style="font-size: 10pt; font-weight:700" color="#807F84">
							<input id="usuario" name="usuario" size="40" title="Usuario" type="text" validar="true" value="<?= (!$esAlta)?$row["UC_NOMBRE"]:""?>" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080">
						</font>
					</p>
				</td>
			</tr>
			<tr>
				<td width="9%"><p style="margin-left: 50px" align="right"><font face="Neo Sans" size="2" color="#666666">e-Mail</font></td>
				<td width="91%">
					<p style="margin-left: 5px">
						<font face="Trebuchet MS" style="font-size: 10pt; font-weight:700" color="#807F84">
							<input <?= ($esAlta)?"":"disabled"?> id="email" name="email" size="40" style="<?= ($esAlta)?"":"background-color:#ccc;"?>" title="e-Mail" type="text" validar="true" validarEmail="true" value="<?= (!$esAlta)?$row["UC_EMAIL"]:""?>" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080">
						</font>
					</p>
				</td>
			</tr>
<?
if ((isset($_REQUEST["g"])) and ($_REQUEST["g"] == "o")) {
?>
			<tr>
				<td width="9%">&nbsp;</td>
				<td width="91%">Datos guardados correctamente</td>
<?
}
?>
			</tr>
			<tr>
				<td width="9%">&nbsp;</td>
				<td width="91%">&nbsp;</td>
			</tr>
			<tr>
				<td width="9%">&nbsp;</td>
				<td width="91%">
					<p style="margin-left: 5px">
						<input type="submit" value="Guardar" name="btnBuscar" style="font-family: Trebuchet MS; color: #0492DE; font-size: 10pt; border: 1px solid #00A3E4; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #CCCCCC">
<?
if (!$esAlta) {
?>
							<input name="btnNuevoUsuario0" type="button" value="Dar de Baja" style="background-color:#ccc; border:1px solid #00A3E4; color:#0492DE; font-family:Trebuchet MS; font-size:10pt; margin-left:16px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" onClick="darBaja()">
<?
}
?>
						<input id="btnVolver" name="btnVolver" style="background-color:#ccc; border:1px solid #00A3E4; color:#0492de; font-family:Trebuchet MS; font-size:10pt; margin-left:16px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" type="button" value="Volver" onClick="window.location.href = '/modules/admin_users_web/'">
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</table>
	</div>
</form>
<script type="text/javascript">
	document.getElementById('usuario').focus();
</script>