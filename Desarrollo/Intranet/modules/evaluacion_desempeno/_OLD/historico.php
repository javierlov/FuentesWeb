<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
?>
<html>
<head>
<?= GetHead("Sistema de Evaluación de Desempeño", array("style.css?today=".date("Ymd")))?>
<script language="JavaScript" src="js/historico_objetivos.js"></script>
</head>
<body bgcolor="#AFC2BE" bottommargin="5" leftmargin="5" rightmargin="5" style="margin-top: 5px;">
<iframe height="0" id="iframeHistorico" name="iframeHistorico" src="" width="0"></iframe>
<table bgcolor="#FFFFFF" align="center" width="544" border="2" bordercolor="#808080" id="table23" style="border-style: solid; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
	<tr>
		<td>
			<table style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bgcolor="#FFFFFF" align="center" cellspacing="0" width="586" id="table24" height="74">
				<tr>
					<td height="40" valign="top">
						<table border="0" width="100%" cellspacing="0" id="table25">
							<tr>
								<td style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><p style="margin-top: 0; margin-bottom: 0"><b><font face="Verdana" style="font-size: 8pt" color="#336699">HISTÓRICO</font></b></td>
								<td width="115" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><p align="right"><img border="0" src="images/historico.jpg" width="27" height="30"></td>
							</tr>
							<tr>
								<td style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" colspan="2" height="5"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="584" height="34" valign="top">
						<table border="0" id="table26" bgcolor="#FFFFFF" cellspacing="0">
							<tr>
								<td width="10"></td>
								<td><p align="center"><img border="0" src="images/user.jpg" width="26" height="28"></td>
								<td><font face="Verdana" style="font-size: 8pt; font-weight: 700" color="#808080">Usuario Actual:</font></td>
								<td><font face="Verdana" style="font-size: 8pt" color="#336699">&nbsp;<?= GetUserName() ?></font></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table id="table37">
				<tr>
					<td width="752" align="left">
						<table border="0" cellspacing="0" width="100%">
							<tr>
								<td align="center" bgcolor="#6E96BC" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080"><span style="display:none; font-weight:700;"><font face="Verdana" color="#FFFFFF" size="1">DEFINICIÓN (<span id="ano">2008</span>)</font></span></td>
							</tr>
							<tr>
								<td align="center" bgcolor="#FFFFFF" style="padding-left: 4px; padding-right: 4px" height="1"></td>
							</tr>
						</table>
						<table border="0" cellspacing="0" width="100%">
							<tr>
								<td align="center" bgcolor="#808080" style="padding-left: 4px; padding-right: 4px" width="140"><p align="left"><span style="font-weight: 700"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">Objetivo <?= $_REQUEST["num"]?></font></span></td>
								<td align="center" bgcolor="#808080" valign="middle" width="17"><img alt="Anterior" border="0" id="btnAnterior" name="btnAnterior" src="images/anterior.jpg" style="cursor:hand; display:none" onClick="mostrar('A')" /></td>
								<td bgcolor="#808080" width="3"></td>
								<td align="center" bgcolor="#808080" valign="middle" width="17"><img alt="Posterior" border="0" id="btnPosterior" name="btnPosterior" src="images/posterior.jpg" style="cursor:hand; display:none" onClick="mostrar('P')" /></td>
								<td align="center" bgcolor="#808080" style="padding-left: 4px; padding-right: 4px">
									<div align="right">
										<table cellspacing="0" cellpadding="0">
											<tr>
												<td align="left" style="padding-left: 4px"><font face="Verdana" style="font-size: 8pt">Modificado por:</font></td>
												<td align="left" style="padding-left: 4px; padding-right: 4px"><font color="#FFFFFF" face="Verdana" style="font-size: 8pt"><span id="UsuModif" name="UsuModif">-</span></font></td>
												<td align="left" style="padding-left: 4px; padding-right: 4px"><p align="right"><font face="Verdana" style="font-size: 8pt">el día:</font></td>
												<td align="left" style="padding-right: 4px"><font color="#FFFFFF" face="Verdana" style="font-size: 8pt"><span id="FechaModif" name="FechaModif">-</span></font></td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="181"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Motivo del cambio:</font></td>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="378"><font face="Verdana" style="font-size: 8pt; " color="#336699">Otros (detallar)</font></td>
							</tr>
							<tr>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="181">&nbsp;</td>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="378"><textarea cols="60" id="MotivoCambio" name="MotivoCambio" readonly="true" rows="3" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
							</tr>
							<tr>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="181"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Descripción del objetivo</font></td>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="378"><textarea cols="60" id="Descripcion" name="Descripcion" readonly="true" rows="3" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
							</tr>
							<tr>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="181"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Resultado a obtener</font></td>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="378"><textarea cols="60" id="Resultado" name="Resultado" readonly="true" rows="3" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
							</tr>
							<tr>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="181"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Indicador</font></td>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="378"><textarea cols="60" id="Indicador" name="Indicador" readonly="true" rows="3" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
							</tr>
							<tr>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="181"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Plazo de ejecución</font></td>
								<td align="left" style="padding-left: 4px; padding-right: 4px" width="378"><textarea cols="60" id="PlazoEjecucion" name="PlazoEjecucion" readonly="true" rows="3" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</form>
</table>
<script>
	fillArray(<?= $_REQUEST["formularioid"]?>, <?= $_REQUEST["num"]?>);
</script>
</body>
</html>