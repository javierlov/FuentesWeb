<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 95));
?>
<script src="/modules/usuarios_registrados/agentes_comerciales/comisiones/js/comisiones.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div class="TituloSeccion" style="display:block; width:730px;">Aviso de Obra</div>
<div class="ContenidoSeccion" align="right" style="margin-top:5px;"><!--<i>>> <a href="/">Términos y Condiciones de uso</a></i>--></div>
<div class="ContenidoSeccion" style="margin-top:10px;">
<p>La carga del formulario permite enviar fácilmente la información de las obras que deben declarar ante Provincia ART. El servicio de aviso de obra web también permite gestionar eficazmente la documentación que presentan respecto de sus actividades de construcción, con información fehaciente y actualizada.</p>
<p>Seleccionando <b>NUEVO FORMULARIO</b> podrá dar de alta, extender, suspender y/o modificar Avisos de Obra, siempre dentro del marco legal establecido por el Decreto 911, las Res. SRT 51/97 – 35/98 – 319/99, y la Res. SRT 552/01. En cambio para informarse del estado en el que se encuentra lo que ha declarado, deberá acceder a <b>CONSULTAR PRESENTACIONES</b>.</p>
</div>

<div class="ContenidoSeccion" style="margin-left:8px; margin-top:32px;">
	<table cellpadding="0" cellspacing="7">
		<tr>
			<td width="3%"><a href="/aviso-obra/formulario-obra"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg"></a></td>
			<td width="97%"><a href="/aviso-obra/formulario-obra"><font color="#00539B"><b>NUEVO FORMULARIO</b></font></a></td>
		</tr>
		<tr>
			<td width="3%"><a href="/aviso-obra/consultar-presentaciones/p"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg"></a></td>
			<td width="97%"><a href="/aviso-obra/consultar-presentaciones/p"><font color="#00539B"><b>CONSULTAR PRESENTACIONES</b></font></a></td>
		</tr>
	</table>
</div>
<div style="margin-top:208px;">
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
</div>