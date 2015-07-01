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
<?= GetHead("Sistema de Evaluación de Desempeño 2008", array("style.css?today=".date("Ymd")))?>
<script language="JavaScript" src="js/eventos.js"></script>
</head>
<body bgcolor="#AFC2BE" bottommargin="5" leftmargin="5" rightmargin="5" style="margin-top: 5px;">
<form action="procesar_evento.php" enctype="multipart/form-data" id="formEvento" method="post" name="formEvento" target="_self" onSubmit="return validarFormEvento(formEvento)">
<input id="FormularioId" name="FormularioId" type="hidden" value="<?= $_REQUEST["formularioid"]?>">
<table bgcolor="#FFFFFF" align="center" width="544" border="2" bordercolor="#808080" id="table23" style="border-style: solid; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
	<tr>
		<td>
			<table style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bgcolor="#FFFFFF" align="center" cellspacing="0" width="586" id="table24" height="76">
				<tr>
					<td height="40" valign="top">
						<table border="0" width="100%" cellspacing="0" id="table25">
							<tr>
								<td style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><p style="margin-top: 0; margin-bottom: 0"><b><font face="Verdana" style="font-size: 8pt" color="#336699">AGREGAR EVENTO</font></b></td>
								<td width="115" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><p align="right"><img border="0" src="images/eventos.jpg" width="27" height="30"></td>
							</tr>
							<tr>
								<td style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" colspan="2" height="5"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="584" height="36" valign="top">
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
			<table cellspacing="0" id="tableTipoEvento" name="tableTipoEvento" width="577">
				<tr>
					<td width="10">&nbsp;</td>
					<td bgcolor="#C0C0C0" width="109" style="padding-left: 4px; padding-right: 4px"><p style="margin-left:4px; margin-top:0; margin-bottom:0"><span style="font-family: Verdana; font-size: 8pt; font-weight: 700">Tipo de evento:</span></p></td>
					<td bgcolor="#C0C0C0" width="20" style="padding-left: 4px; padding-right: 4px"><font face="Verdana"><span style="font-size: 8pt"><input type="radio" id="TipoEvento" name="TipoEvento" value="P"></span></font></td>
					<td bgcolor="#C0C0C0" width="61" style="padding-left: 4px; padding-right: 4px"><font face="Verdana" style="font-size: 8pt">Positivo</font></td>
					<td bgcolor="#C0C0C0" width="20" style="padding-left: 4px; padding-right: 4px"><font face="Verdana"><span style="font-size: 8pt"><input type="radio" id="TipoEvento" name="TipoEvento" value="N"></span></font></td>
					<td bgcolor="#C0C0C0" style="padding-left: 4px; padding-right: 4px"><font face="Verdana" style="font-size: 8pt">Negativo</font></td>
					<td bgcolor="#C0C0C0" style="padding-left: 4px; padding-right: 4px"><p style="margin-left: 8px" align="right"><span style="font-family: Verdana; font-weight: 700; font-size: 8pt">Fecha</span><span style="font-family: Verdana; font-size: 8pt; font-weight: 700">:</span></td>
					<td bgcolor="#C0C0C0" width="176" style="padding-left: 4px; padding-right: 4px"><font face="Verdana" style="font-size: 8pt" color="#336699">&nbsp;<?= date("d/m/Y")?></font></td>
				</tr>
			</table>
			<table border="0" cellspacing="0" id="tableDescripcion" name="tableDescripcion" width="100%">
				<tr>
					<td colspan="3" height="6"></td>
				</tr>
				<tr>
					<td width="10">&nbsp;</td>
					<td width="18%"><p style="margin-left: 4px; margin-top: 0; margin-bottom: 0" align="right"><span style="font-family: Verdana; font-weight: 700; font-size: 8pt">Descripción:</span></p><p>&nbsp;</td>
					<td width="79%"><p style="margin-left: 6px"><textarea cols="71" id="Descripcion" name="Descripcion" rows="4" validar="true" title="Descripción" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
				</tr>
				<tr>
					<td width="100%" colspan="3" height="10"></td>
				</tr>
				<tr>
					<td width="100%" colspan="3" height="10"><p style="margin-right: 10px"><input id="btnGuardar" name="btnGuardar" type="button" value="Guardar" style="color: #808080; font-size: 8pt; font-family: Verdana; font-weight: bold; float: right; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF" onClick="enviarForm()"></td>
				</tr>
				<tr>
					<td width="100%" colspan="3" height="5"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>