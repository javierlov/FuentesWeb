<?
if ((isset($_SESSION["isPreventor"])) and ($_SESSION["isPreventor"])) {
	echo '<meta http-equiv="refresh" content="0; url=/bienvenida-preventores">';
	exit;
}

if (isset($_REQUEST["login"]))
	require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/preventores/validar_login.php");
else {
	$_SESSION["cambiarPassword"] = false;
	$_SESSION["fieldError"] = "";
	$_SESSION["msgError"] = "";
}

if (!isset($_SESSION["msgError"]))
	$_SESSION["msgError"] = "";

$usuario = "";
if (isset($_REQUEST["sr"]))
	$usuario = $_REQUEST["sr"];
?>
<table cellspacing="0" cellpadding="0">
	<tr>
		<td class="TituloSeccion" colspan="2" height="22">Acceso exclusivo para preventores</td>
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
				<form action="/acceso-exclusivo-preventores/t" id="formLogin" method="post" name="formLogin">
					<table border="0" cellpadding="0" cellspacing="0" width="583" id="table1">
						<tr>
							<td class="ContenidoSeccion" colspan="6">
								<p style="margin-top: 0; margin-bottom: 0">Acceso exclusivo para preventores.
								<p style="margin-top: 0; margin-bottom: 0">Ingrese su nombre de usuario y contraseña para comenzar a operar.
							</td>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" colspan="6">&nbsp;</td>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="12" rowspan="6">&nbsp;</td>
							<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="418" colspan="4">
								<img border="0" src="/modules/usuarios_registrados/images/preventores.jpg">
							</td>
							<td class="SubtituloSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="107">&nbsp;</td>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20">&nbsp;</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20">&nbsp;</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="110">Usuario</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="144">
								<input autofocus id="sr" name="sr" style="width:200px;" type="text" value="<?= $usuario?>">
							</td>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20">&nbsp;</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="110">Contraseña</td>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="144">
								<input id="ps" name="ps" style="width:200px;" type="password" value="">
							</td>
<?							
if ($_SESSION["cambiarPassword"]) {
?>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20">&nbsp;</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="110">Contraseña Nueva</td>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="144"><input id="psn" name="psn" style="width:200px;" type="password" value="" /></td>
						</tr>
						<tr>
							<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20">&nbsp;</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="110">Confirmación</td>
							<td  style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="144"><input id="cnf" name="cnf" style="width:200px;" type="password" value="" /></td>
<?
}
?>						
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="144" rowspan="2">
								<input class="btnIngresar" style="position:relative; top:-10px;" type="submit" value="" />
							</td>
							<td class="ContenidoSeccion" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="107" rowspan="2">&nbsp;</td>
						</tr>
					</table>
<?
if ($_SESSION["msgError"] != "") {
?>
					<div class="SubtituloSeccion" style="color:#f00; margin-left:38px; margin-top:8px;"><?= $_SESSION["msgError"]?></div>
<?
}
?>
					<p class="ContenidoSeccion" style="margin-left:8px;">
						> Si usted no se encuentra registrado aún o si olvidó su contraseña, por favor<br />
						&nbsp;&nbsp;&nbsp;comuníquese con el sector de prevención.
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
	<embed height="110" name="obj3" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner3.swf" type="application/x-shockwave-flash" width="240">
</div>