<?
if ((isset($_SESSION["isOrganismoPublico"])) and ($_SESSION["isOrganismoPublico"])) {
	echo '<meta http-equiv="refresh" content="0; url=/bienvenida-organismos-publicos">';
	exit;
}

if (isset($_REQUEST["login"]))
	require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/organismos_publicos/validar_login.php");
else {
	$_SESSION["cambiarPassword"] = false;
	$_SESSION["fieldError"] = "";
	$_SESSION["msgError"] = "";
}

if (!isset($_SESSION["fieldError"]))
	$_SESSION["fieldError"] = "";
if (!isset($_SESSION["msgError"]))
	$_SESSION["msgError"] = "";
if (!isset($_SESSION["intentosLogin"]))
	$_SESSION["intentosLogin"] = 0;


$usuario = "";
if (isset($_REQUEST["sr"]))
	$usuario = $_REQUEST["sr"];
?>
<table cellspacing="0" cellpadding="0">
	<tr>
		<td class="TituloSeccion" colspan="2" height="22">Acceso exclusivo para organismos públicos</td>
	</tr>
	<tr>
		<td height="5" colspan="2"></td>
	</tr>
	<tr>
		<td class="ContenidoSeccion" colspan="2"><p style="margin-top: 0; margin-bottom: 0"></td>
	</tr>
	<tr>
		<td width="5%">&nbsp;</td>
		<td height="5" width="95%">
			<div align="left">
				<form action="/acceso-exclusivo-organismos-publicos/t" id="formLogin" method="post" name="formLogin">
					<table border="0" cellpadding="0" cellspacing="0" width="583" id="table1">
						<tr>
							<td class="ContenidoSeccion" colspan="6">
								<p style="margin-top: 0; margin-bottom: 0">Acceso exclusivo para organismos públicos.
								<p style="margin-top: 0; margin-bottom: 0">Ingrese su nombre de usuario y contraseña para comenzar a operar.
							</td>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" colspan="6">&nbsp;</td>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="12" rowspan="6">&nbsp;</td>
							<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="418" colspan="4">
								<img border="0" src="/modules/usuarios_registrados/images/organismos_publicos.jpg" />
							</td>
							<td class="SubtituloSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="107">&nbsp;</td>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20">&nbsp;</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" colspan="4">&nbsp;</td>
						</tr>
<?
if ($servidorContingenciaActivo) {
?>
						<tr>
							<td colspan="2"><div id="sesionInvalidMsg" style="margin-left:64px; margin-top:24px; width:368px;">Este módulo está momentaneamente deshabilitado, por favor reintente en unos minutos.</div></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
<?
}
else {
?>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20">&nbsp;</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="110">Usuario</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="144">
								<input id="sr" name="sr" style="width:200px;" type="text" value="<?= $usuario?>">
							</td>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20">&nbsp;</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="110">Contraseña</td>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="144">
								<input id="ps" name="ps" style="width:200px;" type="password" value="" />
							</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="144" rowspan="2">
								<input class="btnIngresar" style="position:relative; top:-10px;" type="submit" value="" />
							</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="107" rowspan="2">&nbsp;</td>
						</tr>
<?
}
if ($_SESSION["cambiarPassword"]) {
?>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20">&nbsp;</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="110">Contraseña Nueva</td>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="144">
								<input id="psn" name="psn" style="width:200px;" type="password" value="" />
							</td>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20">&nbsp;</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="110">Confirmación</td>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="144">
								<input id="cnf" name="cnf" style="width:200px;" type="password" value="" />
							</td>
						</tr>
<?
}

if ($_SESSION["intentosLogin"] >= 3) {
?>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20">&nbsp;</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="110">Captcha</td>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="144">
								<input id="captcha" maxlength="12" name="captcha" style="width:88px;" type="text" value="" onKeyPress="keyPress(event)" />
								<img id="imgCaptcha" src="/functions/captcha.php" style="margin-left:4px; vertical-align:-8px;" />
								<img src="/images/reload.png" style="cursor:pointer; margin-left:2px; vertical-align:-6px;" title="Recargar captcha" onClick="recargarCaptcha(document.getElementById('imgCaptcha'))" />
							</td>
						</tr>
<?
}
?>
					</table>
<?
if ($_SESSION["fieldError"] != "") {
?>
					<div class="SubtituloSeccion" style="color:#f00; margin-left:8px; margin-top:8px;"><?= $_SESSION["msgError"]?></div>
<?
}
?>
					<p class="ContenidoSeccion" style="margin-left:8px; margin-top:16px;">
						> Si usted no se encuentra registrado aún o si olvidó su contraseña, por favor<br />
						&nbsp;&nbsp;&nbsp;presione <a class="linkSubrayado" href="mailto:emision@provart.com.ar?subject=Consulta desde la Web">aquí</a> para comunicarse con nosotros.
					</p>
				</form>
				<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
				<p style="margin-top: 0; margin-bottom: 0"></p>
			</div>
		</td>
	</tr>
</table>

<div id="banner1HomePage" style="height:110px; left:0px; position:absolute; top:320px; width:240px; z-index:0;">
	<object border="0" classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" height="110" name="obj1" width="240">
		<param name="movie" value="/images/banner1.swf" />
		<param name="quality" value="High" />
		<param name="wmode" value="transparent" />
		<embed height="110" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner1.swf" type="application/x-shockwave-flash" width="240">
	</object>
</div>
<div id="banner2HomePage" style="height:110px; left:246px; position:absolute; top:320px; width:240px; z-index:0;">
	<object border="0" classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" height="110" name="obj2" width="240">
		<param name="movie" value="/images/banner2.swf" />
		<param name="quality" value="High" />
		<param name="wmode" value="transparent" />
		<embed height="110" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner2.swf" type="application/x-shockwave-flash" width="240">
	</object>
</div>
<div id="divBanner3HomePage" style="left:508px; position:absolute; top:320px;">
	<embed height="110" name="obj3" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner3.swf" type="application/x-shockwave-flash" width="240" />
</div>
<script type="text/javascript">
	obj = document.getElementById('<?= $_SESSION["fieldError"]?>');
	if (obj != null) {
		obj.style.borderColor = '#f00';
		obj.focus();
	}
	else
		document.getElementById('sr').focus();
</script>