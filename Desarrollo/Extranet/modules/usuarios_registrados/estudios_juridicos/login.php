<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");

/*$servidorContingenciaActivo: Esta variable cambia el mensjae de estado de la pagina.
				"Este módulo está momentaneamente deshabilitado, por favor reintente en unos minutos"*/
global $servidorContingenciaActivo;
$servidorContingenciaActivo = false;

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

if ((isset($_SESSION["isAgenteComercial"])) and ($_SESSION["isAgenteComercial"])) {
	echo "<script type='text/javascript'>window.location.href = '/acceso-exclusivo-estudios-juridicos'</script>";
	exit;
}

if (isset($_COOKIE))
	foreach ($_COOKIE as $cookiename => $cookievalue)
		unset($_COOKIE[$cookiename]);

if (isset($_REQUEST["login"]))
	require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/validar_login.php");
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
<script src="/modules/usuarios_registrados/estudios_juridicos/js/clientesEstudios.js?rnd=<?php echo RandomNumber();?>" type="text/javascript"></script>

<div class="TituloSeccion" style="width:90%;">Acceso exclusivo Estudio Jurídico</div>

<form action="/acceso-exclusivo-estudios-juridicos-2" id="formLogin" method="post" name="formLogin" >
	<input id="cc" name="cc" type="hidden" value="" />
	<p class="ContenidoSeccion" style="margin-left:24px; margin-top:16px;">
		Acceso exclusivo para Estudio Jurídico.<br />
		Ingrese su nombre de usuario y contraseña para comenzar a operar.
	</p>
	<div style="border-bottom: 1px dotted #807F83; margin-left:48px; margin-top:16px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:485px;">
		<img border="0" src="/modules/usuarios_registrados/images/estudios_juridicos.jpg" />
	</div>
<?php

if ($servidorContingenciaActivo) {
?>
	<div id="sesionInvalidMsg" style="margin-left:64px; margin-top:24px; width:90%;">Este módulo está momentaneamente deshabilitado, por favor reintente en unos minutos.</div>
<?php
}
else {
?>
	<table style="margin-top:20px;">
		<tr>
			<td><div style="margin-left:64px;"><label class="ContenidoSeccion" for="sr">Usuario</label></div></td>
			<td><input id="sr" name="sr" style="margin-left:5px; width:184px;" type="text" value="<?= $usuario?>" onKeyPress="keyPress(event)" /></td>
		</tr>	
		<tr>
			<td style="width:200px;"><div style="margin-left:64px;"><label class="ContenidoSeccion" for="ps">Contraseña</label></div></td>
			<td><input id="ps" name="ps" style="margin-left:5px; width:184px;" type="password" value="" onKeyUp="keyPress(event)" /></td>
		</tr>		
	</table>
<?
}

if ($_SESSION["cambiarPassword"]) {
?>
	<table>
		<tr>
			<td style="width:200px;"><div style="margin-left:64px;"><label class="ContenidoSeccion" style="width:100px;" for="psn">Contraseña Nueva</label></div></td>
			<td><input id="psn" name="psn" style="margin-left:5px; width:184px;" type="password" value="" onKeyPress="keyPress(event)" /></td>
		</tr>
		<tr>
			<td style="width:200px;"><div style="margin-left:64px;"><label class="ContenidoSeccion" for="cnf">Confirmación</label></div></td>
			<td><input id="cnf" name="cnf" style="margin-left:5px; width:184px;" type="password" value="" onKeyPress="keyPress(event)" /></td>
		</tr>
	</table>
<?
}

if ($_SESSION["intentosLogin"] >= 3) {
?>
	<div style="margin-left:64px;">
		<label class="ContenidoSeccion" for="captcha">Captcha</label>
		<input id="captcha" maxlength="12" name="captcha" style="margin-left:73px; width:80px;" type="text" value="" onKeyPress="keyPress(event)" />
		<img id="imgCaptcha" src="/functions/captcha.php" style="margin-left:4px; vertical-align:-8px;" />
		<img src="/images/reload.png" style="cursor:pointer; margin-left:2px; vertical-align:-6px;" title="Recargar captcha" onClick="recargarCaptcha(document.getElementById('imgCaptcha'))" />
	</div>
<?
}

if (!$servidorContingenciaActivo) {
?>
	<div><input class="btnIngresar" name="btnIngresar" style="margin-left:425px; position:relative; top:-25px;" type="button" value=""onClick="enviarForm();" /></div>
<?
}
?>
<br />
<div class="SubtituloSeccion" style="color:#f00; margin-left:80px; margin-top:6px;"><?= $_SESSION["msgError"]?></div>
<div class="ContenidoSeccion" style="margin-left:44px; margin-top:6px;">
	> Si usted no se encuentra registrado aún o si olvidó su contraseña,<br />
	&nbsp;&nbsp;&nbsp;  por favor contáctese con el sector de Administración de la Gerencia Legales.
	<p />
<?
preg_match("/MSIE (.*?);/", $_SERVER['HTTP_USER_AGENT'], $matches);
//echo "version ".$_SERVER['HTTP_USER_AGENT'];
if (count($matches) > 1) {
	//Then we're using IE
	$version = $matches[1];

	switch(true) {
		case ($version<=9):
			//IE 9 or under!
?>
			<span style="color:#f00; font-size:11px;">
				Usted está usando Internet Explorer <?= $version?>.<br />
				Para navegar correctamente por el sitio web debe tener instalado Internet Explorer 10 o superior.<br />
				Los navegadores recomendados son Chrome (descargar <a class="linkSubrayado" href="https://www.google.com/chrome/browser" target="_blank">aquí</a>) o
				Firefox (descargar <a class="linkSubrayado" href="https://www.mozilla.org/es-AR/firefox/new/" target="_blank">aquí</a>).
			</span>
<?
			break;
	}
}
?>
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
<div id="banner3HomePage" style="height:110px; left:508px; position:absolute; top:320px; width:240px;">
	<a href="http://www.incentiba.com.ar" target="_blank"><img border="0" src="../../../images/banner3.jpg"></a>
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