<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


validarParametro(isset($_SESSION["pageLoadOk"]));

if ($_SESSION["pageLoadOk"])
	exit;

validarSesion(isset($_SESSION["isAgenteComercial"]));
?>
<style>
	.texto {
		font-family: Trebuchet MS;
		font-size: 8pt;
	}
</style>
<?
if ((isset($_POST["sf"])) and ($_POST["sf"] = "t")) {
	$id = substr($_REQUEST["id"], 1);
	$modulo = substr($_REQUEST["id"], 0, 1);

	$params = array(":id" => $id);
	$sql =
		"SELECT sc_nrosolicitud
			 FROM asc_solicitudcotizacion
			WHERE sc_id = :id";
	$nroSolicitud = ValorSql($sql, "", $params);

	// Actualizo el registro para que no genere el pdf..
	$params = array(":idmodulo" => (($modulo == "C")?1:4), ":idtabla" => $id);
	$sql =
		"UPDATE web.wag_archivosgenerados
				SET ag_generar = 'X'
			WHERE ag_idmodulo = :idmodulo
				AND ag_idtabla = :idtabla
				AND ag_generar = 'T'";
	DBExecSql($conn, $sql, $params);

	// Envío el e-mail..
	$body = "<html><body>No se pudo generar la carta de cotización de la solicitud Nº ".$nroSolicitud.", por favor enviarla a la siguiente dirección de e-mail: ".$_REQUEST["email"]."</body></html>";
	$emailTo = array("jlovatto@provart.com.ar,".$_SESSION["emailAvisoArt"]);
	$subject = "Generación errónea de carta de cotización.";
	SendEmail($body, "Web", $subject, $emailTo, array(), array(), "H");
?>
<div class="texto">Muchas gracias.<br />A la brevedad le será remitida la carta de cotización.</div>
<div class="texto"><input class="btnVolver" type="button" value="" onClick="history.go(-2);" /></div>
<?
	exit;
}
?>
<form action="<?= $_SERVER["REQUEST_URI"]?>" id="formSolicitudEnvioCotizacion" method="post" name="formSolicitudEnvioCotizacion" onSubmit="return ValidarForm(formSolicitudEnvioCotizacion)">
	<input id="sf" name="sf" type="hidden" value="t" />
	<div class="texto" style="color:#f00">No se pudo generar la carta de cotización por un error interno.<br />Si desea que se la enviemos, por favor indique la dirección de e-mail donde quiera recibir el archivo adjunto.</div>
	<br />
	<label class="texto" for="email">e-Mail</label>
	<input id="email" maxlength="100" name="email" title="e-Mail" type="text" validar="true" validarEmail="true" />
	<input class="btnEnviar" type="submit" value="" />
</form>
<script type="text/javascript">
	document.getElementById('email').focus();
</script>