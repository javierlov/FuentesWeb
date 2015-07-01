		<link href="Estilos/legales.css" type="text/css" rel="stylesheet">
    <script language="javascript" src="Behaviors/date.htc"></script>

	<form name="_ctl0" method="post" action="EventosABMWebForm.aspx?Nro_Juicio=498" id="_ctl0">
<input type="hidden" name="__EVENTTARGET" value="" />
<input type="hidden" name="__EVENTARGUMENT" value="" />

	  	<table cellspacing="0" cellpadding="0" width="90%" align="center" bgcolor="#ffffff" class="body_border">
		<tr>
			<td>
		<table cellspacing="0" width="100%" border="0" cellpadding="0" align="center">
		<tr>
			<td><>
	<head>
		<title>Seguimiento de Juicios y Concursos</title>
		<link href="Estilos/formularios.css" type="text/css" rel="stylesheet">
		<link href="Estilos/textos.css" type="text/css" rel="stylesheet">
	</head>
  
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
		</table>
		<table cellspacing="0" width="98%" border="0" cellpadding="0" align="center">
		<tr>
			<td>

<table datasrc="#oXMLESTUDIO" cellspacing="0" cellpadding="0" width="98%" border="0" align="center">
    <tr>
        <td height="16" colspan="4" bgcolor="#808080">
			<b><font face="Verdana" style="FONT-SIZE: 8pt" color="#ffffff">&nbsp;Datos del Juicio</font></b>
		</td>
      </tr>
      <tr>
        <td height="16" width="11%" bgcolor="#e7e7e7" align="left">
        	<font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">&nbsp;Nro. Carpeta:</font>
        </td>
        <td height="16" bgcolor="#e7e7e7" style="width: 31%">
          <p align="left">
          <span id="UserControl1_txtNroCarpeta"><b><font face="Arial" color="DarkBlue" size="1">498</font></b></span>
        </td>
        <td height="16" width="6%" bgcolor="#e7e7e7" align="right">
			<font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">Carátula:</font>
		</td>
        <td height="16" width="60%" bgcolor="#e7e7e7" align="left">
          &nbsp;<span id="UserControl1_txtCaratula"><b><font face="Arial" color="DarkBlue" size="1">AGUIRRE LEOPOLDO C/ ADM.PARQUES Y ZOO ENFERMEDAD-ACCIDENTE</font></b></span>
        </td>
	  </tr>
</table>
<table datasrc="#oXMLESTUDIO" cellspacing="0" cellpadding="2" width="100%" border="0" align="center">
      <tr>
		<td HEIGHT="2"></td>
      </tr>
</table>
</td>
		</tr>
		<tr>
			<td>
				
			</td>
		</tr>
		<tr>
			<td class="title_NegroFndGrisClaro">&nbsp;Eventos</td>
		</tr>
		<tr>
			<td>
				<table cellspacing="0" cellpadding="1" width="100%" align="left" border="0" id="table1">
				<tr>
					<td height="4" class="item_grisClaro" colspan="4"></td>
				</tr>
				<tr>
					<td width="103" class="item_grisClaro">&nbsp;Fecha:</td>
					<td width="325" class="item_grisClaro">
						<input name="txtFecha" type="text" maxlength="10" id="txtFecha" class="input_text" onkeyup="javascript: DateFormat(txtFecha, txtFecha.value, window.event, false,'3');" />
						<input type="image" name="btnFecha" onclick="javascript: window.open(&quot;CalendarWebForm.aspx?fn=DarFecha&quot;, &quot;myWin&quot;, &quot;toolbar=no, directories=no, location=no, status=no, menubar=no,  resizable=no, scrollbars=no, width=250, height=255&quot;); if (typeof(Page_ClientValidate) == 'function') Page_ClientValidate(); " language="javascript" id="btnFecha" src="Imagenes/boton_fecha.jpg" alt="" border="0" />
						&nbsp;
					</td>
					<td width="103" class="item_grisClaro">F. Vencimiento:</td>
					<td width="307" class="item_grisClaro">
						<input name="txtFechaVencimiento" type="text" maxlength="10" id="txtFechaVencimiento" class="input_text" onkeyup="javascript: DateFormat(txtFechaVencimiento, txtFechaVencimiento.value, window.event, false,'3');" />
						<input type="image" name="btnFechaVencimiento" onclick="javascript: window.open(&quot;CalendarWebForm.aspx?fn=DarFechaVencimiento&quot;, &quot;myWin&quot;, &quot;toolbar=no, directories=no, location=no, status=no, menubar=no,  resizable=no, scrollbars=no, width=250, height=255&quot;); if (typeof(Page_ClientValidate) == 'function') Page_ClientValidate(); " language="javascript" id="btnFechaVencimiento" src="Imagenes/boton_fecha.jpg" alt="" border="0" />
						&nbsp;
					</td>
				</tr>
				<tr>
					<td width="103" class="item_grisClaro">&nbsp;Evento:</td>
					<td colspan="3" align="left" class="item_grisClaro">
						<select name="cmbEventos" id="cmbEventos" class="combo">
	<option value="113">ACLARATORIA</option>
	<option value="643">ACREDITA PAGOS</option>
	<option value="20">ACUERDO CONCILIATORIO</option>
	<option value="109">ALEGA</option>
	<option value="19">APERTURA DE SINIESTRO</option>
	<option value="681">AUDIENCIA</option>
	<option value="110">AUTOS PARA SENTENCIA</option>
	<option value="16">BANCO - SUCURSAL - CUENTA JUDICIAL N&#186;</option>
	<option value="641">CONTESTA TRASLADO</option>
	<option value="422">DEMANDA CONTESTADA</option>
	<option value="15">DESISTIMIENTO</option>
	<option value="438">EMBARGO EJECUTIVO</option>
	<option value="437">EMBARGO PREVENTIVO</option>
	<option value="721">EXHORTO</option>
	<option value="112">EXPRESA AGRAVIOS</option>
	<option value="24">FACTURACION PENDIENTE HONORARIOS FINALES - AP</option>
	<option value="604">FALTA DEMANDA</option>
	<option value="21">GESTION</option>
	<option value="361">HECHO NUEVO</option>
	<option value="642">IMPUGNA DICTAMEN DEL CUERPO MEDICO FORENSE</option>
	<option value="23">JUICIO EN CONDICIONES DE FINALIZADO</option>
	<option value="105">JUICIO NO RECONOCIDO</option>
	<option value="114">OTROS RECURSOS</option>
	<option value="401">PARALIZADO</option>
	<option value="644">PRESENTACIONES VARIAS</option>
	<option value="821">PROXIMA ACCION</option>
	<option value="501">REBELDIA </option>
	<option value="562">RECEPCION DE DEMANDA POR EL ESTUDIO</option>
	<option value="425">RECONDUCCI&#211;N</option>
	<option value="426">RECURSO DE ACLARATORIA</option>
	<option value="423">RECURSO DE APELACI&#211;N</option>
	<option value="430">RECURSO DE NULIDAD</option>
	<option value="431">RECURSO DE QUEJA</option>
	<option value="427">RECURSO DE REVOCATORIA</option>
	<option value="428">RECURSO EXTRAORDINARIO FEDERAL</option>
	<option value="429">RECURSO INAPLICABILIDAD DE LA LEY</option>
	<option value="101">REPORTE ACTUAL DEL ESTUDIO JUR&#205;DICO</option>
	<option value="22">RESULTADO PROBABLE</option>
	<option value="441">SENTENCIA ABONADA</option>
	<option value="442">SENTENCIA ABONADA PARCIALMENTE</option>
	<option value="282">SENTENCIA CORTE SUPREMA NACIONAL</option>
	<option value="217">SENTENCIA DE PRIMERA INSTANCIA</option>
	<option value="432">SENTENCIA DE TRIBUNAL DEL TRABAJO</option>
	<option value="481">SENTENCIA INTERLOCUTORIA</option>
	<option value="222">SENTENCIA SEGUNDA INSTANCIA</option>
	<option value="281">SENTENCIA SUPREMA CORTE PROVINCIAL</option>
	<option value="106">SOLICITA INSTRUCCIONES</option>

</select>
					</td>
				</tr>
				<tr>
					<td width="103" valign="top" class="item_grisClaro">&nbsp;Observaciones:</td>
					<td colspan="3" class="item_grisClaro" height="150px">
						<textarea name="txtObservaciones" rows="2" id="txtObservaciones" class="text_area"></textarea>
					</td>
				</tr>
				<tr>
					<td height="13" class="item_grisClaro" colspan="4"></td>
				</tr>
				<tr>
					<td colspan="4" height="21"></td>
				</tr>
				<tr>
					<td colspan="4">
						<input type="submit" name="btnAceptar" value="Aceptar" id="btnAceptar" class="submit" />                                
						<input type="submit" name="btnCancelar" value="Cancelar" id="btnCancelar" class="submit" />
					</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center" height="50" colspan="4">
						<a id="btnVolver" class="volver_link" href="javascript:__doPostBack('btnVolver','')">&lt;&lt; VOLVER</a>
					</td>
				</tr>					
				<tr>
					<td class="TDGrisOscuro_pie_pagina" colspan="4"></td>
				</tr>
				<tr>
					<td class="TDGrisClaro_pie_pagina" colspan="4"></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</form>
