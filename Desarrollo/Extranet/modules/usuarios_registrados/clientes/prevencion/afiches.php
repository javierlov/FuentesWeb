<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 68));
?>
<div class="TituloSeccion" style="display:block; width:730px;">Afiches</div>
<div class="ContenidoSeccion" style="margin-top:20px;">
	<table cellpadding="0" cellspacing="4">
		<tr>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_andamio_chico.jpg"></td>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_cargas_chico.jpg"></td>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_columna_chico.jpg"></td>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_punzante_chico.jpg"></td>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_orden_chico.jpg"></td>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_limpieza_chico.jpg"></td>
		</tr>
		<tr>
			<td align="center">Andamios</td>
			<td align="center">Cargas</td>
			<td align="center">Columna</td>
			<td align="center">Punzante</td>
			<td align="center">Orden</td>
			<td align="center">Limpieza</td>
		</tr>
		<tr>
			<td colspan="6" height="20"></td>
		</tr>
		<tr>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_electricidad_chico.jpg"></td>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_proteccion_chico.jpg"></td>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_escaleras_chico.jpg"></td>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_instalaciones_chico.jpg"></td>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_maquinarias_chico.jpg"></td>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_matafuegos_chico.jpg"></td>
		</tr>
		<tr>
			<td align="center">Electricidad</td>
			<td align="center">Protección</td>
			<td align="center">Escaleras</td>
			<td align="center">Instalaciones</td>
			<td align="center">Maquinarías</td>
			<td align="center">Matafuegos</td>
		</tr>
		<tr>
			<td colspan="6" height="20"></td>
		</tr>
		<tr>
			<td><img src="/modules/usuarios_registrados/images/afiches/afiche_no-cortopunzantes_chico.jpg"></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="center">No-Corto Punzantes</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6" height="15"></td>
		</tr>
		<tr>
			<td colspan="6"><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></td>
		</tr>
	</table>
</div>