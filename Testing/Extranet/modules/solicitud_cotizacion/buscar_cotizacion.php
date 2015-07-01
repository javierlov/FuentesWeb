<?
validarSesion(isset($_SESSION["isAgenteComercial"]));

$hoyMenosunMes = time() - (30 * 24 * 60 * 60);
$fechaDesde = date("d/m/Y", $hoyMenosunMes);

if (!isset($_SESSION["BUSQUEDA_COTIZACIONES_AFILIACIONES"]))
	$_SESSION["BUSQUEDA_COTIZACIONES_AFILIACIONES"] = array("buscar" => "N",
																													"cuit" => "",
																													"fechaDesde" => $fechaDesde,
																													"fechaHasta" => "",
																													"numero" => "",
																													"ob" => "2_D_",
																													"pagina" => 1,
																													"razonSocial" => "");
?>
<link rel="stylesheet" href="/modules/solicitud_cotizacion/css/grid.css" type="text/css" />
<script type="text/javascript">
	function closeMsgOk() {
		if (document.getElementById('msgOk') != null)
			document.getElementById('msgOk').style.display = 'none';
	}

	function submitForm() {
		resultado = ValidarForm(formBuscarSolicitudCotizacion);
		if (resultado)
			with (document) {
				getElementById('divMsg').style.display = 'none';
				getElementById('divContentGrid').style.display = 'none';
				getElementById('divProcesando').style.display = 'block';
			}
		return resultado;
	}
</script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/solicitud_cotizacion/buscar_cotizacion_busqueda.php" id="formBuscarSolicitudCotizacion" method="post" name="formBuscarSolicitudCotizacion" target="iframeProcesando" onSubmit="return submitForm()">
	<div class="TituloSeccion">Cotizaciones y Afiliaciones</div>
	<div class="ContenidoSeccion">
		<div style="margin-left:10px; margin-top:8px;">
			<label>Número de Solicitud</label>
			<input id="numero" maxlength="8" name="numero" style="width:72px;" title="Número de Solicitud" type="text" validarEntero="true" value="<?= $_SESSION["BUSQUEDA_COTIZACIONES_AFILIACIONES"]["numero"]?>" />
		</div>
		<div style="margin-top:4px;">
			<label>Fecha Solicitud Desde</label>
			<input id="fechaDesde" maxlength="10" name="fechaDesde" style="width:72px;" title="Fecha Solicitud Desde" type="text" validarFecha="true" value="<?= $_SESSION["BUSQUEDA_COTIZACIONES_AFILIACIONES"]["fechaDesde"]?>" />
			<input class="botonFecha" id="btnFechaDesde" name="btnFechaDesde" style="vertical-align:-5px;" type="button" value="" />
			<i>(dd/mm/aaaa)</i>
		</div>
		<div style="margin-left:4px; margin-top:4px;">
			<label>Fecha Solicitud Hasta</label>
			<input id="fechaHasta" maxlength="10" name="fechaHasta" style="width:72px;" title="Fecha Solicitud Hasta" type="text" validarFecha="true" value="<?= $_SESSION["BUSQUEDA_COTIZACIONES_AFILIACIONES"]["fechaHasta"]?>" />
			<input class="botonFecha" id="btnFechaHasta" name="btnFechaHasta" style="vertical-align:-5px;" type="button" value="" />
			<i>(dd/mm/aaaa)</i>
		</div>
		<div style="margin-left:80px; margin-top:4px;">
			<label>C.U.I.T.</label>
			<input id="cuit" maxlength="13" name="cuit" style="width:106px;" title="CUIT" type="text" validarCuit="true" value="<?= $_SESSION["BUSQUEDA_COTIZACIONES_AFILIACIONES"]["cuit"]?>" />
		</div>
		<div style="margin-left:52px; margin-top:4px;">
			<label>Razón Social</label>
			<input id="razonSocial" maxlength="60" name="razonSocial" style="width:440px;" type="text" value="<?= $_SESSION["BUSQUEDA_COTIZACIONES_AFILIACIONES"]["razonSocial"]?>" />
		</div>
		<div align="right" style="margin-right:16px; margin-top:4px;">
			<input class="btnBuscar" type="submit" value="" />
		</div>
		<div style="margin-top:24px;">Utilice el Número de Solicitud, Fecha Desde/Hasta, C.U.I.T. o Razón Social para buscar en el listado de sus solicitudes de cotización. Si no especifica ningún filtro, la búsqueda traerá la lista completa.</div>
	</div>
<?
if ($_SESSION["altaCotizaciones"]) {
?>
		<div style="margin-top:24px;">
			<input class="btnNuevaCotizacion" type="button" value="" onClick="window.location.href='/index.php?pageid=28'" />
		</div>
<?
}
?>
</form>
<div id="divMsg" style="background-color:#f0f0f0; margin-top:24px; padding:12px">
	<div style="color:#f00; font-size:8pt; font-weight:bold; padding-left: 12px">IMPORTANTE!</div>
	<div class="ContenidoSeccion" style="padding-top: 4px">Le informamos que usted puede obtener <b>respuesta inmediata a sus cotizaciones para cuentas de hasta 50 cápitas, con hasta un 25% de rebaja sobre la alícuota de nuestro tarifario</b> (*).</div>
	<div class="ContenidoSeccion">Para iniciar el proceso de afiliación, es necesario completar y presentar la documentación requerida por normativa de la Superintendencia de Riesgos del Trabajo (S.R.T.) [<a class="linkSubrayado" href="/requisitos-afiliarse">Más info</a>]</div>
	<div class="ContenidoSeccion">En caso de dudas o consulta, comuniquese con su <a class="linkSubrayado" href="mailto:<?= $_SESSION["emailAvisoArt"]?>">Ejecutivo de Cuenta</a>.</div>
	<div style="font-size:7.5pt; font-style:italic; padding-top: 5px; padding-left: 12px; padding-right: 12px; color:#676767">(*) Haga <a class="linkSubrayado" href="/download/PART_CIIUS.pdf" target="_blank">click aquí</a> para ver el listado de CIIUs considerados para cotizaciones automáticas.</div>
</div>
<div align="center" id="divContentGrid" name="divContentGrid" style="margin-top:12px;"></div>
<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
<script type="text/javascript">
	Calendar.setup (
		{
			inputField: "fechaDesde",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaDesde"
		}
	);
	Calendar.setup (
		{
			inputField: "fechaHasta",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaHasta"
		}
	);

	setTimeout('closeMsgOk()', 2000);

<?
if ($_SESSION["BUSQUEDA_COTIZACIONES_AFILIACIONES"]["buscar"] == "S") {
?>
	submitForm();
	document.getElementById('formBuscarSolicitudCotizacion').submit();
<?
}
?>

	document.getElementById('numero').focus();
</script>
<?
if ((isset($_REQUEST["i"])) and ($_REQUEST["i"] == "k")) {
?>
<div id="msgOk" name="msgOk">
	<span style="background-color:#000; border: 3px solid #000;cursor:pointer; color:#fff; position:relative; right:-188px;" onClick="document.getElementById('msgOk').style.display = 'none'"><b>&nbsp;X&nbsp;</b></span>
	<p align="center" style="cursor:default; margin-bottom:32px; margin-top:24px;"><b>Esta cotización será analizada por el departamento de suscripciones de Provincia ART.</b></p>
</div>
<?
}
$_SESSION["paginaAnterior"] = $_SERVER["REQUEST_URI"];
?>