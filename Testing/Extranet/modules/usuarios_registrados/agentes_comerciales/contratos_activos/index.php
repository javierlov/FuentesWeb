<?
validarSesion(isset($_SESSION["isAgenteComercial"]));

if (!isset($_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]))
	$_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"] = array("buscar" => "N",
																									"carteraMorosa" => "",
																									"contrato" => "",
																									"cuit" => "",
																									"fechaVigenciaDesde" => "",
																									"holding" => "",
																									"ob" => "2",
																									"pagina" => 1,
																									"razonSocial" => "",
																									"sb" => false,
																									"sector" => -1,
																									"solicitudTraspaso" => "");
?>
<script src="/modules/usuarios_registrados/agentes_comerciales/contratos_activos/js/contratos_activos.js" type="text/javascript"></script>
<script type="text/javascript">
	function submitForm() {
		resultado = chequearDatosVacios();
		if (resultado) {
			if ((document.getElementById('fechaVigenciaDesde').value != '') && (!ValidarFecha(document.getElementById('fechaVigenciaDesde').value))) {
				alert('La Fecha de Vigencia Desde es inválida!');
				document.getElementById('fechaVigenciaDesde').focus();
				return false;
			}
		}
		if (resultado) {
			document.getElementById('divContentGrid').style.display = 'none';
			document.getElementById('divProcesando').style.display = 'block';
		}
		return resultado;
	}
</script>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/agentes_comerciales/contratos_activos/index_busqueda.php" id="formBuscarContrato" method="post" name="formBuscarContrato" target="iframeProcesando" onSubmit="return submitForm()">
	<div class="TituloSeccion" style="display:block; width:730px;">Contratos Activos</div>
	<div class="ContenidoSeccion" style="margin-left:-16px; margin-top:25px;">
		<div style="margin-left:75px;">
			<label class="ContenidoSeccion">Contrato</label>
			<input id="contrato" maxlength="8" name="contrato" style="width:56px;" type="text" value="<?= $_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["contrato"]?>">
		</div>
		<div style="margin-left:80px; margin-top:4px;">
			<label class="ContenidoSeccion">C.U.I.T.</label>
			<input id="cuit" maxlength="13" name="cuit" style="width:80px;" type="text" value="<?= $_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["cuit"]?>">
		</div>
		<div style="margin-left:52px; margin-top:4px;">
			<label class="ContenidoSeccion">Razón Social</label>
			<input id="razonSocial" maxlength="200" name="razonSocial" style="text-transform:uppercase; width:320px;" type="text" value="<?= $_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["razonSocial"]?>">
		</div>
		<div style="margin-top:4px;">
			<label class="ContenidoSeccion" id="labelFechaVigenciaDesde">Fecha Vigencia Desde</label>
				<input id="fechaVigenciaDesde" maxlength="10" name="fechaVigenciaDesde" style="width:64px;" type="text" value="<?= $_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["fechaVigenciaDesde"]?>">
				<input class="botonFecha" id="btnFechaVigenciaDesde" name="btnFechaVigenciaDesde" style="vertical-align:-5px;" type="button" value="">
				<i class="ContenidoSeccion">(dd/mm/aaaa)</i>
		</div>
		<div style="margin-left:86px; margin-top:4px;">
			<label class="ContenidoSeccion">Sector</label>
			<select id="sector" name="sector">
				<option <?= ($_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["sector"]=="-1")?"selected":""?> value="-1">- Indistinto -</option>
				<option <?= ($_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["sector"]=="pr")?"selected":""?> value="pr">Privado</option>
				<option <?= ($_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["sector"]=="pu")?"selected":""?> value="pu">Público</option>
			</select>
		</div>
		<div style="margin-left:83px; margin-top:4px;">
			<label class="ContenidoSeccion">Holding</label>
			<input id="holding" maxlength="200" name="holding" style="text-transform:uppercase; width:320px;" type="text" value="<?= $_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["holding"]?>">
		</div>
		<div style="margin-left:150px; margin-top:4px;">
			<input <?= ($_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["carteraMorosa"])?"checked":""?> id="carteraMorosa" name="carteraMorosa" type="checkbox" value="t">
			<label class="ContenidoSeccion">Cartera Morosa</label>
			<input <?= ($_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["solicitudTraspaso"])?"checked":""?> id="solicitudTraspaso" name="solicitudTraspaso" style="margin-left:40px;" type="checkbox" value="t">
			<label class="ContenidoSeccion">Con Solicitud de Traspaso (últimos 3 meses)</label>
		</div>
		<div class="ContenidoSeccion" style="margin-top:20px;">Presione buscar para ver la totalidad de los contratos de su cartera activa.</div>
		<p style="margin-left:12px; margin-top:4px;">
			<input class="btnBuscar" type="submit" value="" />
			<input class="btnExportar" id="btnExportar" style="display:none; margin-left:40px;" title="Exportar grilla a Excel" type="button" value="" onClick="exportarGrilla()" />
		</p>
		<div id="divContentGrid" name="divContentGrid" style="margin-top:8px;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	</div>
</form>
<script type="text/javascript">
	Calendar.setup (
		{
			inputField: "fechaVigenciaDesde",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaVigenciaDesde"
		}
	);

<?
if ($_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["buscar"] == "S") {
?>
	if (submitForm())
		document.getElementById('formBuscarContrato').submit();
<?
}
?>

	document.getElementById('contrato').focus();
</script>