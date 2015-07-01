<?
validarSesion(isset($_SESSION["isPreventor"]));

if (!isset($_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]))
	$_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"] = array("buscar" => "N",
																											"codigoPostal" => "",
																											"contrato" => "",
																											"cuit" => "",
																											"establecimiento" => -1,
																											"fechaDesde" => "",
																											"fechaHasta" => "",
																											"idEstablecimiento" => -1,
																											"idPreventor" => $_SESSION["idUsuario"],
																											"idProvincia" => -1,
																											"ob" => "1",
																											"pagina" => 1,
																											"preventor" => $_SESSION["idUsuario"],
																											"prioridad1" => "",
																											"prioridad2" => "",
																											"prioridad3" => "",
																											"prioridad4" => "",
																											"prioridad5" => "",
																											"prioridad6" => "",
																											"prioridad7" => "",
																											"provincia" => -1,
																											"razonSocial" => "");
require_once("buscar_formulario_combos.php");
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<style>
	#establecimiento {width:400px;}
</style>
<script type="text/javascript">
	function buscarEmpresa() {
		var height = 368;
		var width = 640;
		var left = (screen.width - width) / 2;
		var top = ((screen.height - height) / 2) - window.screenTop;

		divWinEmpresa = null;
		divWinEmpresa = dhtmlwindow.open('divBoxEmpresa', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
		divWinEmpresa.load('iframe', '/functions/buscar_empresa.php?ce=t', 'Buscar Empresa');
		divWinEmpresa.show();
	}

	function cargarEstablecimientos(contrato) {
		document.getElementById('iframe2').src = '/modules/usuarios_registrados/preventores/cambia_empresa.php?c=' + contrato;
	}

	function imprimir() {
		if (document.getElementById('totRegSelec').value == 0)
			alert('Debe seleccionar algún registro antes de imprimir.');
		else
			window.location.href = '/index.php?pageid=91';
	}

	function limpiar() {
		with (document) {
			getElementById('codigoPostal').value = '';
			getElementById('contrato').value = '';
			getElementById('cuit').value = '';
			getElementById('establecimiento').value = -1;
			getElementById('fechaDesde').value = '';
			getElementById('fechaHasta').value = '';
			getElementById('idEstablecimiento').value = -1;
			getElementById('idPreventor').value = '<?= $_SESSION["idUsuario"]?>';
			getElementById('idProvincia').value = -1;
			getElementById('preventor').value = '<?= $_SESSION["idUsuario"]?>';
			getElementById('prioridad1').checked = false;
			getElementById('prioridad2').checked = false;
			getElementById('prioridad3').checked = false;
			getElementById('prioridad4').checked = false;
			getElementById('prioridad5').checked = false;
			getElementById('prioridad6').checked = false;
			getElementById('prioridad7').checked = false;
			getElementById('provincia').value = -1;
			getElementById('razonSocial').value = '';
			getElementById('totRegSelec').value = 0;
			getElementById('divContentGrid').style.display = 'none';
			getElementById('iframe2').src = '/modules/usuarios_registrados/preventores/limpiar_seleccion.php';
		}
	}

	function submitForm(primeraVez) {
		resultado = ValidarForm(formImpresionFormularios);
		if (resultado) {
			if (primeraVez)
				document.getElementById('iframe2').src = '/modules/usuarios_registrados/preventores/limpiar_seleccion.php';
			document.getElementById('divContentGrid').style.display = 'none';
			document.getElementById('divProcesando').style.display = 'block';
		}
		return resultado;
	}
</script>
<iframe id="iframe2" name="iframe2" src="" style="display:none;"></iframe>
<div class="TituloSeccion" style="display:block; width:730px;">Impresión de Formularios</div>
<div class="ContenidoSeccion" style="margin-top:15px;">
	<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
	<form action="/modules/usuarios_registrados/preventores/buscar_formulario_busqueda.php" id="formImpresionFormularios" method="post" name="formImpresionFormularios" target="iframeProcesando" onSubmit="return submitForm(true)">
		<input id="idEstablecimiento" name="idEstablecimiento" type="hidden" value="-1" />
		<input id="idPreventor" name="idPreventor" type="hidden" value="<?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["idPreventor"]?>" />
		<input id="idProvincia" name="idProvincia" type="hidden" value="-1" />
		<input id="totRegSelec" name="totRegSelec" type="hidden" value="<?= count($_SESSION["preventores"]["empresas"])?>" />
		<div>
			<label style="margin-left:39px;">Empresa</label>
			<input id="cuit" name="cuit" readonly style="background-color:#ddd; cursor:default; width:76px;" type="text" value="<?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["cuit"]?>" />
			<input id="razonSocial" name="razonSocial" readonly style="background-color:#ddd; cursor:default; width:418px;" type="text" value="<?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["razonSocial"]?>" />
			<input id="contrato" name="contrato" readonly style="background-color:#ddd; cursor:default; width:40px;" type="text" value="<?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["contrato"]?>" />
			<img border="0" src="/images/lupa.gif" style="cursor:pointer; vertical-align:-7px;" title="Buscar Empresa" onClick="buscarEmpresa()" />
		</div>
		<div style="margin-top:4px;">
			<label>Establecimiento</label>
			<?= $comboEstablecimiento->draw();?>
		</div>
		<div style="margin-top:4px;">
			<label style="margin-left:16px;">Fecha Desde</label>
			<input id="fechaDesde" maxlength="10" name="fechaDesde" style="width:64px;" title="Fecha Desde" type="text" validarFecha="true" value="<?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["fechaDesde"]?>">
			<input class="botonFecha" id="btnFechaDesde" name="btnFechaDesde" style="vertical-align:-5px;" type="button" value="">
			<label style="margin-left:16px;">Fecha Hasta</label>
			<input id="fechaHasta" maxlength="10" name="fechaHasta" style="width:64px;" title="Fecha Hasta" type="text" validarFecha="true" value="<?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["fechaHasta"]?>">
			<input class="botonFecha" id="btnFechaHasta" name="btnFechaHasta" style="vertical-align:-5px;" type="button" value="">
		</div>
		<div style="margin-top:4px;">
			<label style="margin-left:33px;">Preventor</label>
			<?= $comboPreventor->draw();?>
		</div>
		<div style="margin-top:4px;">
			<label style="margin-left:11px;">Código Postal</label>
			<input id="codigoPostal" maxlength="5" name="codigoPostal" style="text-transform:uppercase; width:76px;" type="text" value="<?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["codigoPostal"]?>" />
			<label style="margin-left:16px;">Provincia</label>
			<?= $comboProvincia->draw();?>
		</div>
		<div style="border:1px solid; left:520px; position:relative; top:-85px; width:178px;">
			<div>
				<input <?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["prioridad1"]?> id="prioridad1" name="prioridad1" style="vertical-align:-2px;" type="checkbox" value="t" />
				<label>Prioridad 1</label>
				<input <?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["prioridad5"]?> id="prioridad5" name="prioridad5" style="vertical-align:-2px;" type="checkbox" value="t" />
				<label>Prioridad 5</label>
			</div>
			<div style="margin-top:3px;">
				<input <?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["prioridad2"]?> id="prioridad2" name="prioridad2" style="vertical-align:-2px;" type="checkbox" value="t" />
				<label>Prioridad 2</label>
				<input <?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["prioridad6"]?> id="prioridad6" name="prioridad6" style="vertical-align:-2px;" type="checkbox" value="t" />
				<label>Prioridad 6</label>
			</div>
			<div style="margin-top:3px;">
				<input <?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["prioridad3"]?> id="prioridad3" name="prioridad3" style="vertical-align:-2px;" type="checkbox" value="t" />
				<label>Prioridad 3</label>
				<input <?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["prioridad7"]?> id="prioridad7" name="prioridad7" style="vertical-align:-2px;" type="checkbox" value="t" />
				<label>Prioridad 7</label>
			</div>
			<div style="margin-top:3px;">
				<input <?= $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["prioridad4"]?> id="prioridad4" name="prioridad4" style="vertical-align:-2px;" type="checkbox" value="t" />
				<label>Prioridad 4</label>
			</div>
		</div>
		<div style="margin-bottom:8px; margin-left:16px; margin-top:-72px;">
			<input class="btnBuscar" id="btnBuscar" name="btnBuscar" type="submit" value="" />
			<input class="btnLimpiar" id="btnLimpiar" name="btnLimpiar" style="margin-left:16px;" type="button" onClick="limpiar()" />
			<input class="btnImprimir" id="btnImprimir" name="btnImprimir" style="margin-left:16px;" type="button" onClick="imprimir()" />
			<input class="btnFormulariosBlanco" id="btnFormulariosBlanco" name="btnFormulariosBlanco" style="margin-left:16px;" type="button" onClick="window.location.href='/index.php?pageid=92';" />
		</div>
	</form>
	<form id="form" name="form">
		<div align="center" id="divContentGrid" name="divContentGrid"></div>
	</form>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
</div>
<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaDesde",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaDesde"
	});
	Calendar.setup ({
		inputField: "fechaHasta",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaHasta"
	});

<?
if ($_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["buscar"] == "S") {
?>
	if (submitForm(false))
		document.getElementById('formImpresionFormularios').submit();
<?
}
?>

	document.getElementById('iframe2').src = '/modules/usuarios_registrados/preventores/check_grid.php';
</script>