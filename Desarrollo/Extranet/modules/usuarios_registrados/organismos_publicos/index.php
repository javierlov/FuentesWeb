<?
validarSesion(isset($_SESSION["isOrganismoPublico"]));
?>
<script src="/modules/usuarios_registrados/organismos_publicos/js/organismos_publicos.js" type="text/javascript"></script>
<?
if (isset($_REQUEST["page"]))
	require_once($_REQUEST["page"]);
else {
?>
<div class="TituloSeccion" style="display:block; width:730px;">Acceso exclusivo organismos p�blicos</div>
<div class="SubtituloSeccion" style="margin-top:8px;">Declaraci�n Jurada de personal</div>
<div class="ContenidoSeccion" style="margin-top:25px;">
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="2%"><img border="0" src="/modules/usuarios_registrados/images/flecha.gif"></td>
			<td width="98%"><font color="#676767"><b>CARGA DE N�MINA DE PERSONAL.</b></font></td>
		</tr>
		<tr>
			<td colspan="2">Si Usted desea generar el Resumen no Suss de un per�odo determinado, aqu� puede cargar el detalle de personal.</td>
		</tr>
		<tr>
			<td height="40" align="right" colspan="2"><a href="/carga-nomina-personal"><img border="0" src="/modules/usuarios_registrados/images/nomina_de_personal.jpg" /></a></td>
		</tr>	
		<tr>
			<td colspan="2" height="20">&nbsp;</td>
		</tr>
		<tr>
			<td><img border="0" src="/modules/usuarios_registrados/images/flecha.gif" /></td>
			<td><font color="#676767"><b>IMPRESI&Oacute;N DE RES&Uacute;MENES NO SUSS PARA REMITIR A LA ASEGURADORA.</b></font></td>
		</tr>
		<tr>
			<td colspan="2">
				Si Usted recientemente recibi� un email a su casilla de contacto, aqu� podr� visualizar el Resumen no Suss para imprimirlo y enviarlo al Sector Emisi�n de Provincia ART, firmado de manera original, sin enmiendas ni tachaduras.<br />
				Tambi�n en esta secci�n puede visualizar e imprimir per�odos anteriores y exportar detalles de personal cargados.
			</td>
		</tr>
		<tr>
			<td height="40" align="right" colspan="2"><a href="/impresion-resumenes"><img border="0" src="/modules/usuarios_registrados/images/impresion_de_resumenes.jpg" /></a></td>
		</tr>
		<tr>
			<td><img border="0" src="/modules/usuarios_registrados/images/flecha.gif" /></td>
			<td><font color="#676767"><b>GLOSARIO Y MANUAL DE USO.</b></font></td>
		</tr>
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td align="right" colspan="2" height="40">
				<a href="/download/P_ART_Instructivo_extranet.pdf" target="_blank"><img border="0" src="/modules/usuarios_registrados/images/manual_de_uso.jpg" /></a>
				<a href="/download/P_ART_Glosario_Errores.pdf" style="margin-left:14px;" target="_blank"><img border="0" src="/modules/usuarios_registrados/images/glosario.jpg" /></a>
			</td>
		</tr>
	</table>
</div>
<?
}
?>