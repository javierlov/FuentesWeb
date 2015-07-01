<link href="Estilos/legales.css" type="text/css" rel="stylesheet">
<script language="javascript" src="Behaviors/date.htc"></script>

<form name="_ctl0" method="post" action="SentenciaWebForm.aspx?Nro_Juicio=498" id="_ctl0">


  	<table cellspacing="0" cellpadding="0" width="90%" align="center" bgcolor="#ffffff" class="body_border">
	<tr>
		<td>	
			<table cellspacing="0" cellpadding="0" width="100%" border="0" align="center">
			<tr>
				<td><>
	<head>
		<title>Seguimiento de Juicios y Concursos</title>
		<link href="Estilos/formularios.css" type="text/css" rel="stylesheet">
		<link href="Estilos/textos.css" type="text/css" rel="stylesheet">
	</head>


</>
</td>
			</tr>
		    <tr>
	   			<td>

<table datasrc="#oXMLESTUDIO" cellspacing="0" cellpadding="2" width="98%" border="0" align="center">
	<tr>
		<td height="2px" colspan="4"></td>
	</tr>
	<tr>
        <td colspan="4" bgcolor="#808080" align="left">
			<b><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">&nbsp;Datos del Estudio Jurídico</font></b></td>
      </tr>
	<tr>
		<td width="6%" bgcolor="#E7E7E7" align="left">
			<font color="#808080" face="Verdana" style="font-size: 8pt">&nbsp;Usuario:</font></td>
        <td width="33%" bgcolor="#E7E7E7" align="left">
			<font face="Verdana" style="font-size: 8pt">
			<span id="DatosEstudioUserControl_txtUsuario"><b><font face="Arial" color="DarkBlue" size="1">JSEARA</font></b></span></font>	
		</td>
        <td width="13%" bgcolor="#E7E7E7" align="right">
			<font face="Verdana" style="font-size: 8pt; " color="#808080">Estudio Jurídico:</font></td>
        <td width="65%" bgcolor="#E7E7E7" align="left">
			<font face="Verdana" style="font-size: 8pt">
			<span id="DatosEstudioUserControl_txtEstudio"><b><font face="Arial" color="DarkBlue" size="1">LIVELLARA  CARLOS ALBERTO</font></b></span></font>
		</td>
	</tr>
	<tr>
		<td height="2px" colspan="4"></td>
	</tr>
</table></td>
			</tr>
			<tr>
			  <td>
				<table datasrc="#oXMLESTUDIO" cellspacing="0" cellpadding="0" width="98%" border="0" align="center">
				<tr>
				  <td height="16" colspan="6" bgcolor="#808080">
					<b><font face="Verdana" style="FONT-SIZE: 8pt" color="#ffffff">&nbsp;Datos del Juicio</font></b>
				  </td>
				</tr>
				<tr>
				  <td height="16" width="11%" bgcolor="#e7e7e7" align="left">
					<font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">&nbsp;Nro. Carpeta:</font>
				  </td>
				  <td height="16" width="6%" bgcolor="#e7e7e7">
					<p align="left">
					<span id="txtNroCarpeta"><b><font face="Arial" color="DarkBlue" size="1">498</font></b></span>
				  </td>
				  <td height="16" width="5%" bgcolor="#e7e7e7" align="right">
					<font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">Carátula:</font>
				  </td>
				  <td height="16" width="45%" bgcolor="#e7e7e7" align="left">
					&nbsp;<span id="txtCaratula"><b><font face="Arial" color="DarkBlue" size="1">AGUIRRE LEOPOLDO C/ ADM.PARQUES Y ZOO ENFERMEDAD-ACCIDENTE</font></b></span>
				  </td>
				  <td height="16" width="5%" bgcolor="#e7e7e7" align="right">
					<font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">Estado:</font>
				  </td>
				  <td height="16" width="28%" bgcolor="#e7e7e7" align="left">
					&nbsp;<span id="txtEstado"><b><font face="Arial" color="DarkBlue" size="1">SENTENCIA DE CÁMARA FIRME</font></b></span>
				  </td>
				</tr>
				<tr>
					<td height="3" colspan="6"></td>
				</tr>
				</table>
			  </td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="0" width="98%" border="0" align="center">
			<tr>
				<td colspan="6" align="right">
					
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<table width="100%" align="left" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="2" class="title_BlancoFndGrisOscuro">&nbsp;Sentencia</td>
   					</tr>
					<tr>
						<td height="5" colspan="2"></td>
					</tr>
	      			<tr>
			        	<td colspan="2" class="title_NegroFndGrisClaro">&nbsp;Datos Generales</td>
					</tr>
					<tr>
						<td class="item_grisClaro" colspan="2" height="5"></td>
					</tr>
					<tr>
						<td width="163" align="left" class="item_grisClaro">&nbsp;Sentencia:</td>
						<td width="734" align="left" class="item_grisClaro">
							<select name="cmbSentencia" id="cmbSentencia" class="combo">
	<option value=""></option>
	<option selected="selected" value="6">SENTENCIA NO CONDENATORIA</option>
	<option value="7">SENTENCIA CONDENATORIA</option>
	<option value="8">SENTENCIA CONDENATORIA QUE EXCEDE LOS L&#205;MITES DEL SEGURO</option>
	<option value="10">SENTENCIA CONDENATORIA EN LOS L&#205;MITES DEL SEGURO</option>
	<option value="9">SENTENCIA HOMOLOGATORIA DE ACUERDO CONCILIATORIO</option>
	<option value="15">ACUERDO JUDICIAL PROVISORIA Y DEFINITIVA</option>
	<option value="16">SENTENCIA QUE HACE LUGAR A LA DEMANDA PARCIALMENTE</option>

</select>
						</td>
					</tr>
					<tr>
						<td align="left" class="item_grisClaro">&nbsp;Fecha Sentencia:</td>
						<td align="left" class="item_grisClaro">
							<input name="txtFecha" type="text" value="30/06/2003" maxlength="10" id="txtFecha" class="input_text" onkeyup="javascript: DateFormat(txtFecha, txtFecha.value, window.event, false,'3');" />
							<input type="image" name="btnFecha" onclick="javascript: window.open(&quot;CalendarWebForm.aspx?fn=DarFecha&quot;, &quot;myWin&quot;, &quot;toolbar=no, directories=no, location=no, status=no, menubar=no,  resizable=no, scrollbars=no, width=250, height=255&quot;); if (typeof(Page_ClientValidate) == 'function') Page_ClientValidate(); " language="javascript" id="btnFecha" src="Imagenes/boton_fecha.jpg" alt="" border="0" />
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align="left" class="item_grisClaro">&nbsp;Fecha Notificación:</td>
						<td align="left" class="item_grisClaro">
							<input name="txtFechaRecep" type="text" value="30/06/2003" maxlength="10" id="txtFechaRecep" class="input_text" onkeyup="javascript: DateFormat(txtFechaRecep, txtFechaRecep.value, window.event, false,'3');" />
							<input type="image" name="btnFechaRecep" onclick="javascript: window.open(&quot;CalendarWebForm.aspx?fn=DarFechaRecep&quot;, &quot;myWin&quot;, &quot;toolbar=no, directories=no, location=no, status=no, menubar=no,  resizable=no, scrollbars=no, width=250, height=255&quot;); if (typeof(Page_ClientValidate) == 'function') Page_ClientValidate(); " language="javascript" id="btnFechaRecep" src="Imagenes/boton_fecha.jpg" alt="" border="0" />
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align="left" class="item_grisClaro">&nbsp;Importe Demandado:</td>
						<td align="left" class="item_grisClaro">
							<span id="txtImporteDemandado" class="valor_azulOscuro">13080,49</span>
						</td>
					</tr>
					<tr>
						<td align="left" class="item_grisClaro">&nbsp;Importe Capital:</td>
						<td align="left" class="item_grisClaro">
							<span id="txtImporteCapital" class="valor_azulOscuro">0,00</span>
						</td>
					</tr>
					<tr>
						<td align="left" class="item_grisClaro">&nbsp;Honorarios:</td>
						<td align="left" class="item_grisClaro">
							<span id="txtImporteHonorarios" class="valor_azulOscuro">1206,54</span>
							
						</td>
					</tr>
					<tr>
						<td align="left" class="item_grisClaro">&nbsp;Importe Intereses:</td>
						<td align="left" class="item_grisClaro">
							<span id="txtImporteIntereses" class="valor_azulOscuro">0,00</span>
							
						</td>
					</tr>
					<tr>
						<td align="left" class="item_grisClaro">&nbsp;Importe Tasa de Justicia:</td>
						<td align="left" class="item_grisClaro">
							<span id="txtImporteTasaJusticia" class="valor_azulOscuro">0,00</span>
							
						</td>
					</tr>
					<tr>
						<td align="left" class="item_grisClaro">&nbsp;Importe Sentencia:</td>
						<td align="left" class="item_grisClaro">
							<span id="txtImporteSentencia" class="valor_azulOscuro">1206,54</span>
						</td>	
					</tr>    
					<tr>
						<td align="left" class="item_grisClaro">&nbsp;Monto de Condena:</td>
						<td align="left" class="item_grisClaro">
							<span class="item_grisClaro">
							<input name="txtMontoCondenaSentencia" type="text" value="0,00" id="txtMontoCondenaSentencia" class="numerico" />
							&nbsp;
							</span>
						</td>
					</tr>   
					<tr>
						<td align="left" class="item_grisClaro">
                              </td>
						<td align="left" class="item_grisClaro">
							
                              
							&nbsp;
						</td>	
					</tr>
					<tr>
						<td class="item_grisClaro" colspan="2" height="5"></td>
					</tr>                              
					<tr>
						<td height="5" colspan="2"></td>
					</tr>
					<tr>
				    	<td colspan="2" class="title_NegroFndGrisClaro">&nbsp;Detalle</td>
					</tr>
					<tr>
						<td height="5" colspan="2"></td>
					</tr>
					<tr>
				 		<td colspan="2" height="251">
							<textarea name="txtDetalleSentencia" id="txtDetalleSentencia" title="Detalle Sentencia" class="text_area">Rechazar la demanda entablada...costas a la actora.</textarea>
						</td>
					</tr>
					<tr>
						<td height="9" colspan="2"></td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="6" class="title_NegroFndGrisClaro">&nbsp;Sentencia a Reclamos</td>
			</tr>
			<tr>
				<td height="5" colspan="6"></td>
			</tr>	
			<tr>
				<td colspan="6">
					<table cellspacing="0" rules="all" border="1" id="dbgReclamo" width="100%">
	<tr class="tableHeader">
		<td>Reclamo</td><td align="Center" width="90">Monto Demanda</td><td align="Center" width="90">% Inc. Demanda</td><td align="Center" width="90">Monto Sentencia</td><td align="Center" width="65">% Sentencia</td>
	</tr><tr class="innerTable">
		<td>RECLAMO POR ARTICULO 1113 C.C. CON PLANTEO DE INCONSTITUCIONALIDAD</td><td align="Right">$ 13.080,49</td><td align="Right">30,00%</td><td align="Right">$ 0,00</td><td align="Right">&nbsp;</td>
	</tr>
</table>
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<table cellspacing="0" cellpadding="0" width="100%" align="left" border="0">
					<tr>
						<td align="right">&nbsp;</td>
					</tr>
					<tr>
						<td align="left">
							<input type="submit" name="btnAceptar" value="Aceptar" onclick="if (typeof(Page_ClientValidate) == 'function') Page_ClientValidate(); " language="javascript" id="btnAceptar" class="submit" />	
							<input type="submit" name="btnCancelar" value="Cancelar" id="btnCancelar" class="submit" />
						</td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center" height="50" colspan="2">
					<a id="btnVolver" class="volver_link" href="javascript:{if (typeof(Page_ClientValidate) != 'function' ||  Page_ClientValidate()) __doPostBack('btnVolver','')} ">&lt;&lt; VOLVER</a>
				</td>
			</tr>
		   	<tr>
				<td class="TDGrisOscuro_pie_pagina" colspan="2"></td>
			</tr>
			<tr>
				<td class="TDGrisClaro_pie_pagina" colspan="2"></td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
	</form>
