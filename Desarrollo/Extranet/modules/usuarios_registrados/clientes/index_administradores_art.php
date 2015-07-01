<div class="TituloSeccion" style="display:block; width:730px;">Bienvenido Cliente</div>
<div align="right" class="ContenidoSeccion" style="margin-top:5px;">
	<b><i>Usuario:</i></b> <?= $_SESSION["usuario"]?> | <b><i>Empresa:</i></b> <span id="empresa" name="empresa" style="margin-right:4px;"><?= $_SESSION["empresa"]?></span>
</div>
<div class="ContenidoSeccion" style="margin-top:25px;">
	<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
	<table cellpadding="0" cellspacing="0">
<?
if (!$servidorContingenciaActivo) {
?>
		<tr>
			<td width="3%"><a href="/administracion-usuarios"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg" /></a></td>
			<td width="97%"><a href="/administracion-usuarios"><font color="#00539B"><b>ADMINISTRACIÓN DE USUARIOS</b></font></a></td>
		</tr>
		<tr>
			<td colspan="2" height="25">Alta, baja y modificación de usuarios del website.</td>
		</tr>
<?
}
?>
		<tr>
			<td colspan="2" height="20">&nbsp;</td>
		</tr>
		<tr>
			<td width="3%"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg" /></td>
			<td style="cursor:default;"><font color="#00539B"><b>CAPTURA DEL CLIENTE</b></font></td>
		</tr>
		<tr>
			<td colspan="2" height="25">Acceda al sistema como cliente ingresando el contrato, cuit o nombre de la empresa.</td>
		</tr>
		<tr>
			<td height="40" colspan="2">
				<form action="/modules/usuarios_registrados/clientes/capturar_cliente.php" id="formCapturarCliente" method="post" name="formCapturarCliente" target="iframeProcesando">
					<table>
						<tr>
							<td><input autofocus id="valor" maxlength="50" name="valor" style="width:200px;" type="text" value="" /></td>
							<td><img border="0" src="/modules/usuarios_registrados/images/boton_capturar.jpg" style="cursor:pointer;" onClick="document.getElementById('formCapturarCliente').submit();" /></td>
						</tr>
						<tr>
							<td colspan="2"><span id="msgCaptura" name="msgCaptura" style="border:1px; border-style:none;"></span></td>
						</tr>
					</table>
				</form>
			</td>
		</tr>
		<tr id="trCentralServicios1" style="visibility:<?= ($_SESSION["idEmpresa"] == -1)?"hidden":"visible"?>">
			<td width="3%"><a href="/acceso-clientes"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg" /></a></td>
			<td><a href="/acceso-clientes"><font color="#00539B"><b>CENTRAL DE SERVICIOS</b></font></a></td>
		</tr>
		<tr id="trCentralServicios2" style="visibility:<?= ($_SESSION["idEmpresa"] == -1)?"hidden":"visible"?>">
			<td colspan="2" height="25">Acceda al sistema de clientes.</td>
		</tr>
	</table>
</div>

<div id="banner1HomePage" style="height:128px; left:0; position:absolute; top:304px; width:240px; z-index:0;">
	<object border="0" classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" height="128" name="obj1" width="240">
		<param name="movie" value="/images/banner1.swf" />
		<param name="quality" value="High" />
		<param name="wmode" value="transparent" />
		<embed height="128" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner1.swf" type="application/x-shockwave-flash" width="240">
	</object>
</div>
<div id="banner2HomePage" style="height:128px; left:246px; position:absolute; top:304px; width:240px; z-index:0;">
	<object border="0" classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" height="128" name="obj2" width="240">
		<param name="movie" value="/images/banner2.swf" />
		<param name="quality" value="High" />
		<param name="wmode" value="transparent" />
		<embed height="128" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner2.swf" type="application/x-shockwave-flash" width="240">
	</object>
</div>
<div id="divBanner3HomePage" style="left:508px; position:absolute; top:320px;">
	<embed height="110" name="obj3" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner3.swf" type="application/x-shockwave-flash" width="240" />
</div>