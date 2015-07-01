<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


$email = "";
if (isset($_POST["e"])) {
	$email = $_POST["e"];
	$_POST["e"] = strtolower($_POST["e"]);
}

$errores = false;
if (isset($_POST["e"])) {
	$params = array(":email" => $_POST["e"]);
	$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
	$errores = (valorSql($sql, "", $params) != "S");
}

if ((isset($_POST["e"])) and (!$errores)) {
	$params = array(":usuario" => $_POST["e"]);
	$sql =
		"SELECT 1
			 FROM web.wue_usuariosextranet
			WHERE ue_estado IN('A')
				AND ue_usuario = :usuario";
	if (existeSql($sql, $params)) {		// Solo se actualiza la clave y se envía el e-mail si el usuario no está deshabilitado..
		$params = array();
		$sql = "SELECT art.webart.get_cuit_encriptado(TO_CHAR(SYSDATE, 'SSMIHH24DDMMYYYY'), 'N') FROM DUAL";
		$pass = valorSql($sql, "", $params);

		$params = array(":claveprovisoria" => $pass, ":usuario" => $_POST["e"]);
		$sql =
			"UPDATE web.wue_usuariosextranet
					SET ue_claveprovisoria = art.utiles.md5(:claveprovisoria),
							ue_fechavencclaveprovisoria = SYSDATE + 3
				WHERE ue_estado IN('A')
					AND ue_usuario = :usuario";
		DBExecSql($conn, $sql, $params);

		// Le envío el e-mail al usuario..
		$body =
			"Una nueva contraseña se ha generado, la misma será válida por los siguientes 3 días. \n".
			"Nueva Contraseña: ".$pass."\n".
			"En caso que Usted no haya requerido el cambio de contraseña, ignore el mensaje. En caso de persistir el inconveniente, notifíquenos a <a href=\"mailto:info@provinciart.com.ar\">info@provinciart.com.ar</a>";
		$subject = "Recordatorio de contraseña";
		sendEmail($body, "Provincia ART", $subject, array($email), array(), array(), 'H');
	}
}
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<div class="TituloSeccion" style="width:608px;">Contraseña olvidada</div>
<form action="<?= $_SERVER["REQUEST_URI"]?>" id="formOlvideClave" method="post" name="formOlvideClave">
	<p class="ContenidoSeccion" style="margin-left:24px; margin-top:16px;">
		Si usted olvidó su contraseña, ingrese su nombre de usuario (e-mail) y, en breve, le enviaremos<br />
		un correo con el link para activar una nueva contraseña.
	</p>
	<div style="margin-left:24px; margin-top:24px;">
		<label class="ContenidoSeccion" for="e">e-Mail</label>
		<input autofocus id="e" name="e" style="width:400px;" type="text" value="<?= $email?>" onKeyPress="keyPress(event)">
		<input class="btnEnviar" style="cursor:pointer; margin-left:8px; position:relative; top:3px;" type="submit" value="">
	</div>
</form>
<?
if (isset($_POST["e"])) {
	if ($errores) {
?>
<div class="ContenidoSeccion" id="divError" style="margin-left:24px; margin-top:8px;">
	<img src="/modules/usuarios_registrados/images/atencion.jpg" style="height:21px; position:relative; top:4px; width:19px;" />
	<span style="color:#f00;">Por favor, ingrese un e-mail válido.</span>
</div>
<?
	}
	else {
?>
<div class="ContenidoSeccion" id="divError" style="border:1px solid; left:36px; margin-top:12px; position:relative; width:500px;">
	<img src="/images/seleccionar.png" style="position:relative; top:24px;" />
	<div style="color:#008000; left:24px; position:relative; top:-8px;">
		El link para generar una nueva contraseña ha sido enviado al e-mail informado. Por favor chequee su casilla de correo en algunos minutos.<br />
		Si tiene dudas o consultas, envíenos un e-mail a <a class="linkSubrayado" href="mailto:info@provart.com.ar">info@provart.com.ar</a> y uno de nuestros representantes se comunicará con Usted a la brevedad.
	</div>
</div>
<?
	}
}
?>
<div class="ContenidoSeccion" style="margin-left:24px; margin-top:16px;">
	En caso de no estar registrado, por favor haga clic <a class="linkSubrayado" href="/registrese-2">aquí</a>.
</div>

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
	<a href="http://www.incentiba.com.ar" target="_blank"><img src="../../../images/banner3.jpg" /></a>
</div>