<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 94));

require_once("index_combos.php");

if (!isset($_SESSION["BUSQUEDA_INFORMES_INGENIERIA_SINIESTRALIDAD"]))
	$_SESSION["BUSQUEDA_INFORMES_INGENIERIA_SINIESTRALIDAD"] = array("buscar" => "N",
																																	 "periodo" => -1,
																																	 "ob" => "2",
																																	 "pagina" => 1);
?>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
		document.getElementById('formInformeIyS').submit();
	}
</script>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/informe_ingenieria_siniestralidad/index_busqueda.php" id="formInformeIyS" method="post" name="formInformeIyS" target="iframeProcesando">
	<div class="TituloSeccion" style="display:block; width:736px;">Informes de Ingeniería y Siniestralidad</div>
	<div class="ContenidoSeccion" align="right" style="margin-top:5px;"><!--<i>>> <a href="/">Términos y Condiciones de uso</a></i>--></div>
	<div class="ContenidoSeccion" style="margin-top:12px;">
		<div>Seleccione el período y obtenga el Informe de Ingeniería y Siniestralidad.</div>
		<div style="margin-left:42px; margin-top:20px;">
			<label>Período</label>
			<?= $comboPeriodo->draw();?>
		</div>
	</div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:10px; margin-top:8px; overflow:auto; width:720px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	<div style="margin-top:8px;"><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></div>
</form>