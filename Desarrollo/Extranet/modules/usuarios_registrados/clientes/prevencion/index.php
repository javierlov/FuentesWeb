<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 68));
?>
<div class="TituloSeccion" style="display:block; width:730px;">Prevención</div>
<div class="ContenidoSeccion" style="margin-top:20px;">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td>Provincia ART está activamente comprometido con la prevención en su empresa.</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td class="SubtituloSeccionAzul">MANUALES DE PREVENCIÓN</td>
	</tr>	
	<tr>
		<td height="5"></td>
	</tr>	
	<tr>
		<td class="ContenidoSeccion">A continuación se listan los Manuales de Prevención para cada una de las áreas.</td>
	</tr>	
	<tr>
		<td>&nbsp;</td>
	</tr>	
	<tr>
		<td class="ContenidoSeccion">
			<table border="0" cellpadding="0" cellspacing="0" bgcolor="#E9F0F6">
				<tr>
					<td><img border="0" src="/modules/usuarios_registrados/images/educacion.jpg"></td>
					<td><img border="0" src="/modules/usuarios_registrados/images/oficinas.jpg"></td>
					<td><img border="0" src="/modules/usuarios_registrados/images/agro.jpg"></td>
				</tr>
<!--
				<tr>
					<td align="right">901 KB <img border="0" src="/modules/usuarios_registrados/images/ver_pdf.jpg" style="cursor:pointer;"></td>
					<td align="right">860 KB <img border="0" src="/modules/usuarios_registrados/images/ver_pdf.jpg" style="cursor:pointer;"></td>
					<td align="right">1.72 MB <img border="0" src="/modules/usuarios_registrados/images/ver_pdf.jpg" style="cursor:pointer;"></td>
				</tr>
-->
				<tr>
					<td colspan="3" height="15"></td>
				</tr>
				<tr>
					<td><img border="0" src="/modules/usuarios_registrados/images/primeros_auxilios.jpg"></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
<!--
				<tr>
					<td align="right">1.94 MB <img border="0" src="/modules/usuarios_registrados/images/ver_pdf.jpg" style="cursor:pointer;"></td>
					<td colspan="2"><p align="right">Si aún no tiene el Adobe Acrobat Reader,<br>descárguelo en forma gratuita haciendo <a target="_blank" href="http://www.adobe.com/products/acrobat/readstep2.html">click aquí</a>.</td>
				</tr>
-->
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>	
	<tr>
		<td height="20"></td>
	</tr>	
	<tr>
		<td class="ContenidoSeccion">
			<table border="0" cellpadding="0" cellspacing="0" width="585">
				<tr>
					<td><img border="0" src="/modules/usuarios_registrados/images/normativas_grales.jpg" style="cursor:pointer;" onclick="window.location.href='/prevencion/normativas'" /></td>
					<td><img border="0" src="/modules/usuarios_registrados/images/afiches.jpg" style="cursor:pointer;" onclick="window.location.href='/prevencion/afiches'" /></td>
					<td><img border="0" src="/modules/usuarios_registrados/images/senialetica.jpg" style="cursor:pointer;" onclick="window.location.href='/prevencion/senaletica'" /></td>
				</tr>
			</table>
		</td>
	</tr>	
</table>
</div>