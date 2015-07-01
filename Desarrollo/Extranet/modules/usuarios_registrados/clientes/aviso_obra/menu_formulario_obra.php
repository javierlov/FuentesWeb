<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 95));
?>
<style>
	#items {font-size:11px; text-align:left;}
</style>
<script src="/modules/usuarios_registrados/agentes_comerciales/comisiones/js/comisiones.js" type="text/javascript"></script>
<script type="text/javascript">
	function clickNuevoAvisoObra() {
		with (document.getElementById('items'))
			if (style.display == 'none')
				style.display = 'inline';
			else
				style.display = 'none';
	}
</script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div class="TituloSeccion" style="display:block; width:730px;">Aviso de Obra - Formulario de Obra</div>

<div class="ContenidoSeccion" style="margin-top:10px;">
	Seleccione la acción que desea ejecutar, teniendo en cuenta que:
	<ul>
		<li>Deberá declarar todas las actividades de obra con una antelación de cinco (5) días al inicio de las actividades.</li>
		<li>Aquellas obras que hayan finalizado no podrán ser extendidas o suspendidas.</li>
		<li>Una suspensión definitiva no podrá ser reiniciada.</li>
		<li>Solo se aceptarán modificaciones de direcciones y errores en la declaración de los Avisos de Obra, previo contacto con el personal de construcción.</li>
	</ul>
</div>
<div class="ContenidoSeccion" style="margin-left:8px; margin-top:32px;">
	<table cellpadding="0" cellspacing="7">
		<tr>
			<td width="3%"><a href="#" onClick="clickNuevoAvisoObra()"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg"></a></td>
			<td width="97%"><a href="#" onClick="clickNuevoAvisoObra()"><font color="#00539B"><b>NUEVO AVISO DE OBRA</b></font></a></td>
		</tr>
		<tr>
			<td colspan="2" style="padding-left:40px; width:600px;">
				<div id="items" style="display:none;">
					<div><a href="/aviso-obra/nuevo/n/2">Resolución 35/1998 (Contratista principal y Comitente)</a></div>
					<div><a href="/aviso-obra/nuevo/n/3">Resolución 51/1997 (Subcontratista)</a></div>
					<!-- <div><a href="/aviso-obra/nuevo/n/1">Resolución 319/1999 (Tareas con un máximo de 7 días corridos)</a></div> -->
				</div>
			</td>
		</tr>
		<tr>
			<td width="3%"><a href="/aviso-obra/consultar-presentaciones/e"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg"></a></td>
			<td width="97%"><a href="/aviso-obra/consultar-presentaciones/e"><font color="#00539B"><b>EXTENDER UNA OBRA</b></font></a></td>
		</tr>
		<tr>
			<td width="3%"><a href="/aviso-obra/consultar-presentaciones/s"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg"></a></td>
			<td width="97%"><a href="/aviso-obra/consultar-presentaciones/s"><font color="#00539B"><b>SUSPENDER UNA OBRA</b></font></a></td>
		</tr>
		<tr>
			<td width="3%"><a href="/aviso-obra/consultar-presentaciones/sd"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg"></a></td>
			<td width="97%"><a href="/aviso-obra/consultar-presentaciones/sd"><font color="#00539B"><b>SUSPENSIÓN DEFINITIVA DE OBRA</b></font></a></td>
		</tr>
		<!--<tr>
			<td width="3%"><a href="/aviso-obra/consultar-presentaciones/m"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg"></a></td>
			<td width="97%"><a href="/aviso-obra/consultar-presentaciones/m"><font color="#00539B"><b>MODIFICAR UN AVISO DE OBRA</b></font></a></td>
		</tr> -->
	</table>
</div>
<div style="left:680px; position:absolute; top:408px;">
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
</div>