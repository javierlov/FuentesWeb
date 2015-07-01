<?
validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 60));

require_once("index_combos.php");
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<script src="/modules/usuarios_registrados/clientes/js/cartilla_prestadores.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/cartilla_de_prestadores/index_busqueda.php" id="formCartillaPrestadores" method="post" name="formCartillaPrestadores" target="iframeProcesando">
	<div class="TituloSeccion" style="display:block; width:730px;">Cartilla de Prestadores</div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<span>Consulte nuestra amplia red de más de 1.200 prestadores en todo el país. Para iniciar la búsqueda seleccione una provincia y luego, una localidad y <a href="/cartilla-prestadores/tipo-prestacion">tipo de prestación</a>.</span>
		<div style="margin-left:51px; margin-top:16px;">
			<label>Provincia</label>
			<?= $comboProvincia->draw();?>
		</div>
		<div style="margin-left:49px; margin-top:8px;">
			<label>Localidad</label>
			<?= $comboLocalidad->draw();?>
		</div>
		<div style="margin-top:8px;">
			<label>Tipo de Prestación</label>
			<?= $comboTipoPrestacion->draw();?>
		</div>
		<p>
			<input class="btnBuscar" type="submit" value="" />
			<input class="btnExcel" id="btnExportar" style="display:none; margin-left:40px;" title="Exportar grilla a Excel" type="button" value="" onClick="exportarGrilla()" />
		</p>
	</div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-top:8px; overflow:auto; width:720px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..." /></div>
</form>