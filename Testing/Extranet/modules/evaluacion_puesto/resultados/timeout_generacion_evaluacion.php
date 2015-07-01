<?
if ($_SESSION["pageLoadOk"]) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/resultado_impresion.php");
	exit;
}


require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
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
	$id = substr($_REQUEST["idModulo"], 1);
	$modulo = substr($_REQUEST["idModulo"], 0, 1);

	if ($modulo == "C")
		$sql =
			"SELECT sc_nrosolicitud
				 FROM asc_solicitudcotizacion
				WHERE sc_id = :id";
	else
		$sql =
			"SELECT sr_nrosolicitud
				 FROM asr_solicitudreafiliacion
				WHERE sr_id = :id";
	$params = array(":id" => $id);
	$nroSolicitud = ValorSql($sql, "", $params);

	// Actualizo el registro para que no genere el pdf..
	$params = array(":idmodulo" => (($modulo == "C")?2:3), ":idtabla" => $id);
	$sql =
		"UPDATE web.wag_archivosgenerados
				SET ag_generar = 'X'
			WHERE ag_idmodulo = :idmodulo
				AND ag_idtabla = :idtabla
				AND ag_generar = 'T'";
	DBExecSql($conn, $sql, $params);

	// Envío el e-mail..
	$body = "<html>";
	$body.= "<body>";
	$body.= "<div>No se pudo generar la solicitud de afiliación de la solicitud de ".(($modulo == "C")?"cotización":"revisión")." Nº ".$nroSolicitud.", por favor enviarla a la siguiente dirección de e-mail: ".$_REQUEST["email"]."</div>";

	if ($nroSolicitud == "")
		$body.= "<div>ERROR: Nº de solicitud vacía!&nbsp;&nbsp;&nbsp;Módulo: ".$_REQUEST["idModulo"]."</div>";

	$params = array(":id" => $_SESSION["canal"]);
	$sql = "SELECT ca_codigo || ' - ' || ca_descripcion FROM aca_canal WHERE ca_id = :id";
	$body.= "<div>CANAL: ".ValorSql($sql, "", $params)."</div>";

	$params = array(":id" => $_SESSION["entidad"]);
	$sql = "SELECT en_codbanco || ' - ' || en_nombre FROM xen_entidad WHERE en_id = :id";
	$body.= "<div>ENTIDAD: ".ValorSql($sql, "", $params)."</div>";

	if ($_SESSION["sucursal"] != "") {
		$params = array(":id" => $_SESSION["sucursal"]);
		$sql = "SELECT su_codsucursal || ' - ' || su_descripcion FROM asu_sucursal WHERE su_id = :id";
		$body.= "<div>SUCURSAL: ".ValorSql($sql, "", $params)."</div>";
	}

	if ($_SESSION["vendedor"] != "") {
		$params = array(":id" => $_SESSION["vendedor"]);
		$sql = "SELECT ve_vendedor || ' - ' || ve_nombre FROM xve_vendedor WHERE ve_id = :id";
		$body.= "<div>VENDEDOR: ".ValorSql($sql, "", $params)."</div>";
	}

	$body.= "</body></html>";
	$emailTo = array("jlovatto@provart.com.ar,".$_SESSION["emailAvisoArt"]);
	$subject = "Generación errónea de solicitud de afiliación.";
	SendEmail($body, "Web", $subject, $emailTo, array(), array(), "H", (($modulo == "C")?"ASC":"ASR"), $id, $_SESSION["email"]);
?>
<div class="texto">Muchas gracias.<br />A la brevedad le será remitida la solicitud de afiliación.<br /></div>
<div class="texto"><a href="#" onClick="window.location.href='/index.php?pageid=30&id=<?= $_REQUEST["idModulo"]?>'">Volver</a></div>
<?
	exit;
}
?>
<form action="<?= $_SERVER["REQUEST_URI"]?>" id="formSolicitudEnvioSolicitudAfiliacion" method="post" name="formSolicitudEnvioSolicitudAfiliacion" onSubmit="return ValidarForm(formSolicitudEnvioSolicitudAfiliacion)">
	<input id="sf" name="sf" type="hidden" value="t" />
	<div class="texto" style="color:#f00">No se pudo generar la solicitud de afiliación por un error interno.<br />Si desea que se la enviemos, por favor indique la dirección de e-mail donde quiera recibir el archivo adjunto.</div>
	<br />
	<label class="texto" for="email">e-Mail</label>
	<input class="input" id="email" maxlength="100" name="email" size="50" title="e-Mail" type="text" validar="true" validarEmail="true" />
	<input class="btnEnviar" type="submit" value="" />
</form>
<script type="text/javascript">
	document.getElementById('email').focus();
</script>