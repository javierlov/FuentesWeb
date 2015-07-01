<?
if (session_status() != PHP_SESSION_ACTIVE)
	session_start();

if ((isset($_SESSION["isAgenteComercial"])) and ($_SESSION["isAgenteComercial"])) {
	echo "<script type='text/javascript'>window.location.href = '/bienvenida'</script>";
	exit;
}

if (isset($_REQUEST["login"]))
	require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/agentes_comerciales/validar_login.php");
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
<script src="/js/md5.js" type="text/javascript"></script>
<script src="/modules/usuarios_registrados/agentes_comerciales/js/clientes.js" type="text/javascript"></script>
<div class="TituloSeccion" style="width:608px;">Acceso exclusivo para agentes comerciales</div>
<form action="/acceso-exclusivo-agentes-comerciales/t" id="formLogin" method="post" name="formLogin">
	<input id="cc" name="cc" type="hidden" value="" />
	<p class="ContenidoSeccion" style="margin-left:24px; margin-top:16px;">
		Acceso exclusivo para agentes comerciales registrados.<br />
		Ingrese su nombre de usuario y contraseña para comenzar a operar.
	</p>
	<div style="border-bottom: 1px dotted #807F83; margin-left:48px; margin-top:16px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:420px;">
		<img border="0" src="/modules/usuarios_registrados/images/agentes_comerciales.jpg" />
	</div>
<?
if ($servidorContingenciaActivo) {
?>
	<div id="sesionInvalidMsg" style="margin-left:64px; margin-top:24px; width:368px;">Este módulo está momentaneamente deshabilitado, por favor reintente en unos minutos.</div>
<?
}
else {
?>
	<div style="margin-left:64px; margin-top:24px;">
		<label class="ContenidoSeccion" for="sr">Usuario</label>
		<input id="sr" name="sr" style="margin-left:56px; width:184px;" type="text" value="<?= $usuario?>" onKeyPress="keyPress(event)" />
	</div>
	<div style="margin-left:64px;">
		<label class="ContenidoSeccion" for="ps">Contraseña</label>
		<input id="ps" name="ps" style="margin-left:37px; width:184px;" type="password" value="" onKeyUp="keyPress(event)" />
	</div>
<?
}

if ($_SESSION["cambiarPassword"]) {
?>
	<div style="margin-left:64px;">
		<label class="ContenidoSeccion" for="psn">Contraseña Nueva</label>
		<input id="psn" name="psn" style="width:184px;" type="password" value="" onKeyPress="keyPress(event)" />
	</div>
	<div style="margin-left:64px;">
		<label class="ContenidoSeccion" for="cnf">Confirmación</label>
		<input id="cnf" name="cnf" style="margin-left:27px; width:184px;" type="password" value="" onKeyPress="keyPress(event)" />
	</div>
<?
}

if ($_SESSION["intentosLogin"] >= 3) {
?>
	<div style="margin-left:64px;">
		<label class="ContenidoSeccion" for="captcha">Captcha</label>
		<input id="captcha" maxlength="12" name="captcha" style="margin-left:54px; width:80px;" type="text" value="" onKeyPress="keyPress(event)" />
		<img id="imgCaptcha" src="/functions/captcha.php" style="margin-left:4px; vertical-align:-8px;" />
		<img src="/images/reload.png" style="cursor:pointer; margin-left:2px; vertical-align:-6px;" title="Recargar captcha" onClick="recargarCaptcha(document.getElementById('imgCaptcha'))" />
	</div>
<?
}

if (!$servidorContingenciaActivo) {
?>
	<div><input class="btnIngresar" name="btnIngresar" style="margin-left:408px; position:relative; top:-35px;" type="button" value="" onClick="enviarForm()" /></div>
<?
}
?>
	<div class="SubtituloSeccion" style="color:#f00; margin-left:80px; margin-top:16px;"><?= $_SESSION["msgError"]?></div>
	<div class="ContenidoSeccion" style="margin-left:44px; margin-top:16px;">
		> Si usted no se encuentra registrado aún o si olvidó su contraseña,<br />
		&nbsp;&nbsp;&nbsp;por favor contáctese con su Ejecutivo de Cuentas.
	</div>
</form>

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