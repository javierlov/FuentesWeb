<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion($_SESSION["isAdminTotal"]);


if (!isset($_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"]))
	$_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"] = array("buscar" => "N",
																																		 "contrato" => "",
																																		 "cuit" => "",
																																		 "email" => "",
																																		 "empresa" => "",
																																		 "estado2" => -1,
																																		 "nombre" => "",
																																		 "ob" => "2",
																																		 "pagina" => 1,
																																		 "sb" => false);

require_once("index_combos.php");
?>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
	}
</script>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/administracion_responsables_contrato/index_busqueda.php" id="formBuscarUsuario" method="post" name="formBuscarUsuario" target="iframeProcesando" onSubmit="submitForm()">
	<div class="TituloSeccion" style="display:block; width:730px;">Administración de Usuarios</div>
	<div class="ContenidoSeccion" style="margin-left:-16px; margin-top:25px;">
		<p style="margin-bottom:16px; margin-left:12px;">
			<img border="0" src="/modules/usuarios_registrados/images/nuevo_usuario.jpg" style="cursor:pointer;" onclick="window.location.href='/alta-usuario'" />
		</p>
		<p>
			<span class="SubtituloSeccionAzul">BÚSQUEDA DE USUARIOS</span>
			<span class="ContenidoSeccion" style="margin-left:-8px;">(Cuando un usuario aparece duplicado significa que está asociado a mas de una empresa)</span>
		</p>
		<div style="margin-left:5px;">
			<label class="ContenidoSeccion">Nombre</label>
			<input autofocus id="nombre" name="nombre" style="width:240px;" type="text" value="<?= $_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"]["nombre"]?>">
			<label class="ContenidoSeccion">Contrato</label>
			<input id="contrato" maxlength="10" name="contrato" style="width:96px;" type="text" value="<?= $_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"]["contrato"]?>">
		</div>
		<div style="margin-left:16px; margin-top:4px;">
			<label class="ContenidoSeccion">e-Mail</label>
			<input id="email" name="email" style="width:240px;" type="text" value="<?= $_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"]["email"]?>">
			<label class="ContenidoSeccion" style="margin-left:5px;">C.U.I.T.</label>
			<input id="cuit" maxlength="13" name="cuit" style="width:96px;" type="text" value="<?= $_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"]["cuit"]?>">
		</div>
		<div style="margin-top:4px;">
			<label class="ContenidoSeccion">Empresa</label>
			<input id="empresa" name="empresa" style="width:240px;" type="text" value="<?= $_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"]["empresa"]?>">
		</div>
		<div style="margin-left:10px; margin-top:4px;">
			<label class="ContenidoSeccion">Estado</label>
			<?= $comboEstado2->draw();?>
		</div>
		<p style="margin-left:12px; margin-top:20px;">
			<input class="btnBuscar" type="submit" value="" />
			<a id="linkToExcel" href="" style="visibility:hidden;" target="_blank">
				<img border="0" src="/modules/usuarios_registrados/images/exportar_xls.jpg" style="cursor:pointer; margin-left:16px;" title="Exportar Grilla a Excel">
			</a>
		</p>
		<div id="divContentGrid" name="divContentGrid" style="margin-top:8px;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<input class="btnVolver" type="button" value="" onClick="window.location.href = '/bienvenida-cliente'" />
	</div>
</form>
<script type="text/javascript">
<?
if ($_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"]["buscar"] == "S") {
?>
	submitForm();
	document.getElementById('formBuscarUsuario').submit();
<?
}
?>
</script>