<?
$id = "";
if (isset($_REQUEST["id"]))
	$id = $_REQUEST["id"];

$email = "";
if (isset($_REQUEST["email"]))
	$email = $_REQUEST["email"];

$usuario = "";
if (isset($_REQUEST["usuario"]))
	$usuario = $_REQUEST["usuario"];
?>
<script type="text/javascript">
function ajustarTamanoIframe(iFrame) {
	var code = iFrame.contentWindow.document.body.innerHTML;
	var cant = 0;

	while (code.indexOf('GridFondoOnMouseOver') != -1) {
		cant++;
		code = code.substr(code.indexOf('GridFondoOnMouseOver') + 2);
	}

	if (cant == 0)
		iFrame.height = 64;
	else
		iFrame.height = 64 + (cant * 20);
}
</script>
<form action="<?= $_SERVER["PHP_SELF"]?>" id="formBuscarUsuarios" method="get" name="formBuscarUsuarios" target="_self">
	<input id="buscar" name="buscar" type="hidden" value="yes" />
	<div align="center">
		<table border="0" cellpadding="0" cellspacing="0" width="95%">
			<tr>
				<td colspan="2"><p style="margin-top: 0; margin-bottom: 0"><b><font face="Neo Sans" color="#1CA3DB">Administración de Usuarios</font></b></td>
			</tr>
			<tr>
				<td colspan="2" height="8">&nbsp;</td>
			</tr>
			<tr>
				<td width="9%"><p style="margin-left: 50px"><font color="#666666" face="Neo Sans" size="2">Usuario</font></td>
				<td width="91%">
					<p style="margin-left: 5px">
						<font face="Trebuchet MS" style="font-size: 10pt; font-weight:700" color="#807F84">
							<input id="usuario" name="usuario" size="40" type="text" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080" value="<?= $usuario?>">
						</font>
					</p>
				</td>
			</tr>
			<tr>
				<td width="9%"><p style="margin-left: 50px" align="right"><font face="Neo Sans" size="2" color="#666666">e-Mail</font></td>
				<td width="91%">
					<p style="margin-left: 5px">
						<font face="Trebuchet MS" style="font-size: 10pt; font-weight:700" color="#807F84">
							<input id="email" name="email" size="40" type="text" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080" value="<?= $email?>">
						</font>
					</p>
				</td>
			</tr>
			<tr>
				<td width="9%">&nbsp;</td>
				<td width="91%">&nbsp;</td>
			</tr>
			<tr>
				<td width="9%">&nbsp;</td>
				<td width="91%">
					<p style="margin-left: 5px">
						<input type="submit" value="Buscar" name="btnBuscar" style="font-family: Trebuchet MS; color: #0492DE; font-size: 10pt; border: 1px solid #00A3E4; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #ccc">&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" value="Nuevo Usuario" name="btnNuevoUsuario" style="font-family: Trebuchet MS; color: #0492DE; font-size: 10pt; border: 1px solid #00A3E4; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #CCCCCC" onClick="window.location.href = 'index.php?pageid=2&id=-1'">
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
?>
	<iframe frameborder="no" height="0" id="iframeGrilla" name="iframeGrilla" scrolling="no" src="buscar.php?usuario=<?= $usuario?>&email=<?= $email?>&id=<?= $id?>" width="712" onLoad="ajustarTamanoIframe(this)"></iframe>
<?
}
?>
				</td>
			</tr>
		</table>
	</div>
</form>