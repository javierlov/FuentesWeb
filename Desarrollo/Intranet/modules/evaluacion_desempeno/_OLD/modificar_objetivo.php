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
		<?= getHead("Sistema de Evaluación de Desempeño ".$_REQUEST["ano"], array("style.css?today=".date("Ymd")))?>
		<script language="JavaScript" src="js/objetivos.js"></script>
	</head>
	<body bgcolor="#AFC2BE" bottommargin="5" leftmargin="5" rightmargin="5" style="margin-top:5px;">
		<form action="procesar_objetivos.php" enctype="multipart/form-data" id="formObjetivos" method="post" name="formObjetivos" target="_self" onSubmit="return validarFormObjetivos(formObjetivos)">
			<input id="FormularioId" name="FormularioId" type="hidden" value="<?= $_REQUEST["formularioid"]?>">
			<input id="Id" name="Id" type="hidden" value="<?= $_REQUEST["id"]?>">
			<input id="Num" name="Num" type="hidden" value="<?= $_REQUEST["num"]?>">
			<table align="center" bgcolor="#ffffff" border="2" bordercolor="#808080" id="table23" style="border-style:solid; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" width="544">
				<tr>
					<td>
						<table align="center" bgcolor="#fff" cellspacing="0" height="76" id="table24" style="padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" width="586">
							<tr>
								<td height="40" valign="top">
									<table border="0" cellspacing="0" id="table25" width="100%">
										<tr>
											<td style="border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px;">
												<p style="margin-bottom:0; margin-top:0;"><b><font color="#336699" face="Verdana" style="font-size:8pt;">MODIFICACIÓN DE OBJETIVOS</font></b></p>
											</td>
											<td style="border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px;" width="115">
												<p align="right"><img border="0" height="30" src="images/modificar_objetivos.jpg" width="27" />
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
											<td><p align="center"><img border="0" height="28" src="images/user.jpg" width="26" /></td>
											<td><font color="#808080" face="Verdana" style="font-size:8pt; font-weight:700;">Usuario Actual:</font></td>
											<td><font color="#336699" face="Verdana" style="font-size:8pt;">&nbsp;<?= getUserName() ?></font></td>
										</tr>
									</table>	
								</td>
							</tr>
						</table>
						<table id="table37">
							<tr>
								<td width="752">
									<p style="margin-bottom:0; margin-left:4px; margin-right:8px; margin-top:0;">
										<span style="font-family:Verdana; font-size:8pt;">Detallá el nuevo objetivo</span>
										<font face="Verdana" size="2"><span style="font-size:8pt; font-family:Verdana;"> completando todos los campos de la tabla.</span></font>
									</p>
								</td>
							</tr>
							<tr>
								<td height="4" width="752"></td>
							</tr>
							<tr>
								<td align="left" width="752">
									<table border="0" cellspacing="1" id="tableDatos" name="tableDatos" width="578">
										<tr>
											<td align="center" bgcolor="#6e96bc" bordercolor="#808080" colspan="2" style="border-style:solid; border-width:1px; padding-left:4px; padding-right:4px;">
												<span style="font-weight:700;"><font color="#ffffff" face="Verdana" size="1">DEFINICIÓN (<?= $_REQUEST["ano"]?>)</font></span>
											</td>
										</tr>
										<tr>
											<td align="center" bgcolor="#808080" bordercolor="#808080" colspan="2" style="border-style:solid; border-width:1px; padding-left:4px; padding-right:4px">
												<p align="left"><span style="font-weight:700;"><font face="Verdana" style="font-size:8pt;" color="#ffffff">Objetivo <?= $_REQUEST["num"]?></font></span>
											</td>
										</tr>
										<tr>
											<td align="left" style="padding-left:4px; padding-right:4px" width="181">
												<font face="Verdana" style="font-size:8pt; font-weight:700;">Descripción del objetivo</font>
											</td>
											<td align="left" style="padding-left:4px; padding-right:4px" width="378">
												<textarea cols="60" id="Descripcion" name="Descripcion" rows="3" style="border:1px solid #808080; color:#808080; font-family:Verdana; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" title="Descripción del objetivo" validar="true"></textarea>
											</td>
										</tr>
										<tr>
											<td align="left" style="padding-left:4px; padding-right:4px;" width="181">
												<font face="Verdana" style="font-size:8pt; font-weight:700;">Resultado a obtener</font>
											</td>
											<td align="left" style="padding-left:4px; padding-right:4px;" width="378"><textarea cols="60" id="Resultado" name="Resultado" rows="3" title="Resultado a obtener" validar="true" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
										</tr>
										<tr>
											<td align="left" style="padding-left:4px; padding-right:4px;" width="181"><font face="Verdana" style="font-size:8pt; font-weight:700;">Indicador</font></td>
											<td align="left" style="padding-left:4px; padding-right:4px;" width="378">
												<textarea cols="60" id="Indicador" name="Indicador" rows="3" style="border:1px solid #808080; color:#808080; font-family:Verdana; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" title="Indicador" validar="true"></textarea>
											</td>
										</tr>
										<tr>
											<td align="left" style="padding-left:4px; padding-right:4px;" width="181"><font face="Verdana" style="font-size:8pt; font-weight:700;">Plazo de ejecución</font></td>
											<td align="left" style="padding-left:4px; padding-right:4px;" width="378">
												<textarea cols="60" id="PlazoEjecucion" name="PlazoEjecucion" rows="3" style="border:1px solid #808080; color:#808080; font-family:Verdana; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" title="Plazo de ejecución" validar="true"></textarea>
											</td>
										</tr>
										<tr>
											<td align="left" colspan="2" height="5" style="padding-left:4px; padding-right:4px" width="568"></td>
										</tr>
										<tr>
											<td align="left" bgcolor="#6e96bc" bordercolor="#808080" colspan="2" style="border-style:solid; border-width:1px; padding-left:4px; padding-right:4px;" width="566">
												<p><font color="#ffffff" face="Verdana" size="1" style="font-weight:700;">MOTIVO DEL CAMBIO DE OBJETIVO</font></p>
											</td>
										</tr>
										<tr>
											<td align="left" colspan="2" style="padding-left:4px; padding-right:4px;" width="568">
												<table border="0" cellspacing="0"id="table39" width="100%">
													<tr>
														<td width="3%"><font face="Verdana"><span style="font-size:8pt;"><input id="MotivoCambio" name="MotivoCambio" type="radio" value="1" /></span></font></td>
														<td width="31%"><font face="Verdana" style="font-size:8pt;">Ingreso</font></td>
														<td width="4%"><p align="right"><font face="Verdana"><span style="font-size:8pt;"><input id="MotivoCambio" name="MotivoCambio" type="radio" value="2" /></span></font></p></td>
														<td width="26%"><font face="Verdana" style="font-size:8pt;">Cambio de Tarea</font></td>
														<td width="4%"><p align="right"><font face="Verdana"><span style="font-size:8pt;"><input id="MotivoCambio" name="MotivoCambio" type="radio" value="3" /></span></font></p></td>
														<td width="30%"><font face="Verdana" style="font-size:8pt;">Cambio de Sector</font></td>
													</tr>
													<tr>
														<td width="3%"><font face="Verdana"><span style="font-size:8pt;"><input id="MotivoCambio" name="MotivoCambio" type="radio" value="4" /></span></font></td>
														<td><font face="Verdana" style="font-size:8pt;">Modificación de prioridades</font></td>
														<td><font face="Verdana"><span style="font-size:8pt;"><input id="MotivoCambio" name="MotivoCambio" type="radio" value="5"></span></font></td>
														<td colspan="3"><font face="Verdana" style="font-size:8pt;">Otros (detallar)</font></td>
													</tr>
													<tr>
														<td colspan="6"><p align="right"><textarea cols="90" id="MotivoCambioOtros" name="MotivoCambioOtros" rows="3" style="border:1px solid #808080; color:#808080; font-family:Verdana; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;"></textarea></td>
													</tr>
													<tr>
														<td colspan="6" height="3"></td>
													</tr>
													<tr>
														<td colspan="6"><input id="btnGuardar" name="btnGuardar" style="background-color:#fff; border:1px solid #808080; color:#808080; float:right; font-family:Verdana; font-size:8pt; font-weight:bold; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" type="button" value="Guardar" onClick="enviarForm()"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>