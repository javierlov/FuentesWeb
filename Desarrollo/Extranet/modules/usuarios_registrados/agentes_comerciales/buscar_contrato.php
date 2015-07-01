<?
function getTitulo($destino) {
	switch ($destino) {
		case "cc":
			return "Certificados de Cobertura";
		case "ds":
			return "Denuncias de Siniestros";
		case "nt":
			return "Nómina de Trabajadores";
		case "rc":
			return "Responsabilidad Civil";
	}
}


$destino = "rc";
if (isset($_REQUEST["d"]))
	$destino = $_REQUEST["d"];

validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion((($destino == "rc") and ($_SESSION["entidad"] != 400)) or ($destino != "rc"));

if (!isset($_SESSION["BUSQUEDA_BUSCAR_CONTRATO"]))
	$_SESSION["BUSQUEDA_BUSCAR_CONTRATO"] = array("buscar" => "N",
																								"contrato" => "",
																								"cuit" => "",
																								"ob" => "2",
																								"pagina" => 1,
																								"sb" => false);
?>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
	}
</script>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/agentes_comerciales/buscar_contrato_busqueda.php" id="formBuscarContrato" method="post" name="formBuscarContrato" target="iframeProcesando" onSubmit="submitForm()">
	<input id="d" name="d" type="hidden" value="<?= $destino?>">
	<div class="TituloSeccion" style="display:block; width:730px;"><?= getTitulo($destino)?></div>
	<div class="ContenidoSeccion" style="margin-left:-16px; margin-top:25px;">
		<div>
			<label class="ContenidoSeccion">Contrato</label>
			<input id="contrato" name="contrato" style="width:88px;" type="text" value="">
		</div>
		<div style="margin-left:5px; margin-top:4px;">
			<label class="ContenidoSeccion">C.U.I.T.</label>
			<input id="cuit" maxlength="13" name="cuit" style="width:88px;" type="text" value="">
		</div>
		<p style="margin-left:12px; margin-top:20px;">
			<input class="btnBuscar" type="submit" value="" />
		</p>
		<div id="divContentGrid" name="divContentGrid" style="margin-left:8px; margin-top:8px;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
	</div>
</form>
<script type="text/javascript">
<?
if ($_SESSION["BUSQUEDA_BUSCAR_CONTRATO"]["buscar"] == "S") {
?>
	submitForm();
	document.getElementById('formBuscarContrato').submit();
<?
}
?>
	document.getElementById('contrato').focus();
</script>