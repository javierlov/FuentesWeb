<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
?>
<html>
	<head>
		<?= getHead("Sistema de Evaluación de Desempeño ".date("Y"), array("style.css?today=".date("Ymd")))?>
		<script language="JavaScript" src="js/eventos.js"></script>
	</head>
	<body bgcolor="#AFC2BE" bottommargin="5" leftmargin="5" rightmargin="5" style="margin-top:5px;">
		<form action="procesar_evento.php" enctype="multipart/form-data" id="formEvento" method="post" name="formEvento" target="_self" onSubmit="return validarFormEvento(formEvento)">
			<input id="FormularioId" name="FormularioId" type="hidden" value="<?= $_REQUEST["formularioid"]?>" />
			<table align="center" bgcolor="#FFFFFF" border="2" bordercolor="#808080" id="table23" style="border-style:solid; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" width="544">
				<tr>
					<td>
						<table align="center" bgcolor="#ffffff" cellspacing="0" height="76" id="table24" style="padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" width="586">
							<tr>
								<td height="40" valign="top">
									<table border="0" cellspacing="0" id="table25" width="100%">
										<tr>
											<td style="border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px;">
												<p style="margin-bottom:0; margin-top:0;"><b><font face="Verdana" style="font-size:8pt;" color="#336699">AGREGAR EVENTO</font></b></p>
											</td>
											<td style="border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px;" width="115">
												<p align="right"><img border="0" height="30" src="images/eventos.jpg" width="27" /></p>
											</td>
										</tr>
										<tr>
											<td colspan="2" height="5" style="border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px;"></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height="36" valign="top" width="584">
									<table bgcolor="#ffffff" border="0" cellspacing="0" id="table26">
										<tr>
											<td width="10"></td>
											<td><p align="center"><img border="0" height="28" src="images/user.jpg" width="26" /></p></td>
											<td><font color="#808080" face="Verdana" style="font-size:8pt; font-weight:700;">Usuario Actual:</font></td>
											<td><font color="#336699" face="Verdana" style="font-size:8pt;">&nbsp;<?= getUserName() ?></font></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table cellspacing="0" id="tableTipoEvento" name="tableTipoEvento" width="577">
							<tr>
								<td width="10">&nbsp;</td>
								<td bgcolor="#c0c0c0" style="padding-left:4px; padding-right:4px;" width="109"><p style="margin-bottom:0; margin-left:4px; margin-top:0;"><span style="font-family:Verdana; font-size:8pt; font-weight:700;">Tipo de evento:</span></p></td>
								<td bgcolor="#c0c0c0" style="padding-left:4px; padding-right:4px;" width="20"><font face="Verdana"><span style="font-size:8pt;"><input id="TipoEvento" name="TipoEvento" type="radio" value="P" /></span></font></td>
								<td bgcolor="#c0c0c0" style="padding-left:4px; padding-right:4px;" width="61"><font face="Verdana" style="font-size:8pt;">Positivo</font></td>
								<td bgcolor="#c0c0c0" style="padding-left:4px; padding-right:4px;" width="20"><font face="Verdana"><span style="font-size:8pt;"><input id="TipoEvento" name="TipoEvento" type="radio" value="N" /></span></font></td>
								<td bgcolor="#c0c0c0" style="padding-left:4px; padding-right:4px;"><font face="Verdana" style="font-size:8pt;">Negativo</font></td>
								<td bgcolor="#c0c0c0" style="padding-left:4px; padding-right:4px;"><p align="right" style="margin-left:8px;"><span style="font-family:Verdana; font-size:8pt; font-weight:700;">Fecha</span><span style="font-family:Verdana; font-size:8pt; font-weight:700;">:</span></td>
								<td bgcolor="#c0c0c0" style="padding-left:4px; padding-right:4px;" width="176"><font color="#336699" face="Verdana" style="font-size:8pt;">&nbsp;<?= date("d/m/Y")?></font></td>
							</tr>
						</table>
						<table border="0" cellspacing="0" id="tableDescripcion" name="tableDescripcion" width="100%">
							<tr>
								<td colspan="3" height="6"></td>
							</tr>
							<tr>
								<td width="10">&nbsp;</td>
								<td width="18%"><p align="right" style="margin-bottom:0; margin-left:4px; margin-top:0;"><span style="font-family:Verdana; font-size:8pt; font-weight:700;">Descripción:</span></p><p>&nbsp;</p></td>
								<td width="79%"><p style="margin-left:6px;"><textarea cols="71" id="Descripcion" name="Descripcion" rows="4" title="Descripción" validar="true" style="border:1px solid #808080; color:#808080; font-family:Verdana; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;"></textarea></td>
							</tr>
							<tr>
								<td colspan="3" height="10" width="100%"></td>
							</tr>
							<tr>
								<td colspan="3" height="10" width="100%"><p style="margin-right:10px;"><input id="btnGuardar" name="btnGuardar" type="button" value="Guardar" style="background-color:#fff; border:1px solid #808080; color:#808080; float:right; font-family:Verdana; font-size:8pt; font-weight:bold; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" onClick="enviarForm()" /></td>
							</tr>
							<tr>
								<td colspan="3" height="5" width="100%"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>