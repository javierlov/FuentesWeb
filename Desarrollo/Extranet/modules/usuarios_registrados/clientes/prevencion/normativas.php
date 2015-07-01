<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 68));
?>
<div class="TituloSeccion" style="display:block; width:730px;">Normativas</div>
<div class="ContenidoSeccion" style="margin-top:20px;">
	<table cellpadding="0" cellspacing="4">
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
		<tr>
			<td><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></td>
		</tr>
	</table>
</div>