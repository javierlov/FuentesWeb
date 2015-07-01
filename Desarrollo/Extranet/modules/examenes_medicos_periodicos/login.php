<?
if (!isset($_SESSION["fieldError"]))
	$_SESSION["fieldError"] = "";
if (!isset($_SESSION["msgError"]))
	$_SESSION["msgError"] = "";

$cuit = "";
if (isset($_REQUEST["cuit"]))
	$cuit = $_REQUEST["cuit"];
?>
<form action="/modules/examenes_medicos_periodicos/validar_login.php" method="post">
	<table cellspacing="0" cellpadding="0">
		<tr>
			<td height="19" class="TituloSeccion" colspan="2">Medicina Laboral</td>
		</tr>
		<tr>
			<td height="22" class="SubtituloSeccion" colspan="2">EXÁMENES MÉDICOS PERIÓDICOS</td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" valign="top" colspan="2">
				<p>Con el objeto de dar cumplimiento a la normativa vigente en materia de Medicina Laboral, Provincia ART realiza exámenes médicos periódicos, según lo establece la Resolución S.R.T. 37/2010.</p>
				<p>Si usted recibió una comunicación (por carta o e-mail) referida a este tema, en la que se indica el número de lote y el prestador asignado para la realización de los exámenes médicos periódicos, consulte aquí el listado del personal a evaluar.</p>
				<p>&nbsp;</p>	
			</td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" valign="top" colspan="2">
				<table border="0" cellspacing="0" cellpadding="0" width="100%" height="50">
					<tr>
						<td background="/modules/examenes_medicos_periodicos/images/fondo1.jpg"><p align="left" style="margin: 0 10px; ">
							<span style="font-family: Trebuchet MS"><font color="#807F84" style="font-size: 8pt">Ingrese con su número de C.U.I.T., sin puntos ni guiones, y su número de contrato de afiliación (ambos constan en su contrato de afiliación).</font></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" valign="top" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td align="right" class="ContenidoSeccion"><b>Nº C.U.I.T.</b></td>
			<td class="ContenidoSeccion" valign="top"><input id="cuit" maxlength="13" name="cuit" type="text" value="<?= $cuit?>" /></td>
		</tr>
		<tr>
			<td align="right" class="ContenidoSeccion"><b>Nº Contrato</b></td>
			<td class="ContenidoSeccion" valign="top"><input id="contrato" maxlength="10" name="contrato" type="text" /></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" valign="top">&nbsp;</td>
			<td valign="top"><p align="left"><font face="Neo Sans" style="font-size: 9pt"><?= $_SESSION["msgError"]?></font></p></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" valign="top">&nbsp;</td>
			<td class="ContenidoSeccion" valign="top"><input class="btnIngresar" name="btnIngresar" type="submit" value=""></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	obj = document.getElementById('<?= $_SESSION["fieldError"]?>');
	if (obj != null) {
		obj.style.borderColor = '#f00';
		obj.focus();
	}
	else
		document.getElementById('cuit').focus();
</script>