<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Sistema de Gestión de RR.HH. ::..</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			body {
				scrollbar-face-color: #aaa;
				scrollbar-highlight-color: #aaa;
				scrollbar-shadow-color: #aaa;
				scrollbar-3dlight-color: #eee;
				scrollbar-arrow-color: #eee;
				scrollbar-track-color: #e3e3e3;
				scrollbar-darkshadow-color: #fff;
			}
		</style>
		<script type="text/javascript">
			function inicial() {
				if (document.getElementById('pepemac').value == "76711059") {
					mostrar('capa1');
					mostrar('capa2');
				}
				else {
					ocultar('capa1');
					ocultar('capa2');
				}
			}

			function mostrar(nombreCapa) {
				document.getElementById(nombreCapa).style.display = "block";
			}

			function ocultar(nombreCapa) {
				document.getElementById(nombreCapa).style.display = "none";
			}
		</script>
	</head>

	<body onLoad="inicial()" link="#336699" vlink="#336699" alink="#336699" topmargin="3" bottommargin="3" leftmargin="0" rightmargin="0">
		<form action="" method="post">
			<table bgcolor="#FFFFFF" align="center" width="700">
				<tr>
					<td>
						<table bgcolor="#FFFFFF" align="center" cellspacing="0" width="700" id="table11">
							<tr>
								<td width="377" height="25">
									<table border="0" bgcolor="#FFFFFF" cellspacing="0" width="293">
										<tr>
											<td style="padding-left: 4px; padding-right: 4px" width="8">&nbsp;</td>
											<td style="border-left:1px solid #C0C0C0; border-top:1px solid #C0C0C0; border-bottom:1px solid #C0C0C0; padding-left: 4px; padding-right: 4px" bgcolor="#807F84" width="112">
												<p align="left"><span style="font-weight: 700">
													<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">USUARIO REFERENTE:</font></span><font face="Trebuchet MS"></font>
												</p>
											</td>
											<td style="border-right:1px solid #C0C0C0; border-top:1px solid #C0C0C0; border-bottom:1px solid #C0C0C0; padding-left: 4px; padding-right: 4px; " bgcolor="#807F84">
												<select id="UsuarioAEvaluar" name="UsuarioAEvaluar" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
													<option>-- SELECCIONAR --</option>
												</select>
											</td>
										</tr>
									</table>
								</td>
								<td align="left" width="375">
									<p>
										<font face="Trebuchet MS" style="font-size: 9pt" color="#807F84">Período desde: </font>
										<font face="Trebuchet MS" style="font-size: 9pt">X</font>
										<font face="Trebuchet MS" style="font-size: 9pt" color="#807F84">&nbsp; hasta: </font>
										<font face="Trebuchet MS" style="font-size: 9pt">X</font>
									</p>
								</td>
							</tr>
						</table>
					</td>
					<td width="752" height="10" colspan="2"></td>
	<tr>
	<td width="377" height="132" valign="top">

<table border="0" cellspacing="0" width="292">
	<tr>
		<td style="padding-left: 4px; padding-right: 4px" rowspan="6" width="7">&nbsp;</td>
		<td colspan="2" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#C0C0C0" bgcolor="#807F84">
		<span style="font-weight: 700"><font face="Trebuchet MS" color="#FFFFFF" style="font-size: 8pt">EMPRESA</font></span></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="97"><font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Nombre del puesto:</font></td>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="164"><font face="Trebuchet MS" style="font-size: 8pt">X</font></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="97"><font face="Trebuchet MS" color="#808080" style="font-size: 8pt">Ocupante:</font></td>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="164"><font face="Trebuchet MS" style="font-size: 8pt">X</font></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="97"><font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Gerencia:</font></td>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="164"><font face="Trebuchet MS" style="font-size: 8pt">X</font></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="97"><font face="Trebuchet MS" color="#808080" style="font-size: 8pt">Depto / Oficina:</font></td>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="164"><font face="Trebuchet MS" style="font-size: 8pt">X</font></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="97"><font face="Trebuchet MS" color="#808080" style="font-size: 8pt">Fecha:</font></td>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="164"><font face="Trebuchet MS" style="font-size: 8pt" color="#807F84">¿Qué fecha?</font></td>
	</tr>
</table>
</td>

<td align="left" width="375" valign="top">
<table border="0" cellspacing="0" width="295">
	<tr>
		<td colspan="2" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#C0C0C0" bgcolor="#807F84">
		<span style="font-weight: 700"><font face="Trebuchet MS" color="#FFFFFF" style="font-size: 8pt">DATOS DEL JEFE</font></span></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="94"><font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Nombre y Apellido:</font></td>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="185"><font face="Trebuchet MS" style="font-size: 8pt">X</font></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="94"><font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Puesto:</font></td>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="185"><font face="Trebuchet MS" style="font-size: 8pt">X</font></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="94"><font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Área/Sector:</font></td>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="185"><font face="Trebuchet MS" style="font-size: 8pt">X</font></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="94"><font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Gerencia:</font></td>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="185"><font face="Trebuchet MS" style="font-size: 8pt">X</font></td>
	</tr>
</table>
</table>	

<div align="center">
<table cellspacing="0" cellpadding="0" width="700">
<tr>
	<td width="700" style="padding-left: 4px; padding-right: 4px" height="5"></td>
	</tr>
<tr>
	<td width="700" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#808080" bordercolor="#808080">
<p style="margin-left: 6px; margin-top:0; margin-bottom:0"><b><font face="Trebuchet MS" style="font-size: 9pt"><font color="#FFFFFF">1.</font>
</font><font face="Trebuchet MS" style="font-size: 9pt" color="#336699"><a target="_self" href="javascript:mostrar('capa1')" onclick="mostrar('capa1');ocultar('capa2')" ondblclick="ocultar('capa1')" id="pepemac" onChange="inicial()">
<span style="text-decoration: none"><font color="#05459C">[+]</font></span></a></font><font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF">COMPETENCIAS GENERALES</font></b></td>
	</tr>
</table>

</div>

<div id='capa1'>
<table>	
	<tr>
	<td width="700">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table23">
	<tr colspan="3">
		<td width="700" height="5" colspan="7"></td>
	</tr>
	<tr>
		<td width="18%" align="right"><span style="font-family: Trebuchet MS; font-size: 8pt"><b><font color="#05459C">&gt; </font></b><font color="#807F84">Competencia:&nbsp;</font></span></td>
		<td width="82%" colspan="6"><span style="font-family: Trebuchet MS; font-size: 8pt">Alcance de la responsabilidad.</span></td>
	</tr>
	<tr>
		<td width="18%" align="right"><p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt"><font color="#808080">Factor: </font><b>&nbsp;</b></font></td>
		<td width="82%" colspan="6"><font face="Trebuchet MS" style="font-size: 8pt">Orientación a los resultados.</font></td>
	</tr>
	<tr>
		<td valign="top" width="18%" align="right"><p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt"><font color="#808080">Respuesta: </font><b> &nbsp;</b></font></td>
		<td width="82%" colspan="6"><p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">Alta: cumple con eficiencia los objetivos esperados, realiza análisis de resultados y establece planes de mejora.</font></td>
	</tr>
	<tr>
		<td valign="top" width="100%" align="right" colspan="7" height="5"></td>
	</tr>
	<tr>
		<td valign="top" width="18%" align="right"><font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Formación Requerida:&nbsp;</font></td>
		<td width="25%" valign="bottom"><font face="Trebuchet MS" style="font-weight: 700" size="1" color="#05459C">ACTIVIDAD 1</font></td>
		<td width="59%" valign="bottom" colspan="5"><font face="Trebuchet MS" size="1" color="#05459C">Aclaración</font></td>
	</tr>
	<tr>
		<td valign="top" width="18%" align="right" height="57">&nbsp;</td>
		<td width="25%" valign="top" height="57">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D2">
				<option>-- SELECCIONAR --</option>
				<option>Administración de Proyectos</option>
				<option>Gestión por Objetivos</option>
				<option>Árbol de Causas</option>
				<option>Tablero de Comando</option>
				<option>Otros</option>
			</select></td>
		<td width="59%" colspan="5" height="57" valign="top"><textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
	</tr>
	<tr>
		<td valign="top" width="68%" colspan="7" height="5"></td>
	</tr>
	<tr>
		<td width="41%" align="right" colspan="2" height="21"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">¿Valida la formación solicitada?</font></td>
		<td width="3%" height="21"><p align="right"><font face="Trebuchet MS"><span style="font-size: 8pt"><input type="radio" value="V5" name="R1"></span></font></td>
		<td width="3%" height="21"><font face="Trebuchet MS" style="font-size: 8pt">SI</font></td>
		<td width="3%" height="21">&nbsp;</td>
		<td width="3%" height="21"><font face="Trebuchet MS"><span style="font-size: 8pt"><input type="radio" value="V4" name="R1"></span></font></td>
		<td width="53%" height="21"><font face="Trebuchet MS" style="font-size: 8pt">NO</font></td>
	</tr>
	<tr>
		<td width="41%" align="right" colspan="2" height="26"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">¿Cuál es la prioridad?</font></td>
		<td width="59%" colspan="5" height="26"><p style="margin-top: 0; margin-bottom: 0">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
				<option>-- SELECCIONAR --</option>
				<option>Alta</option>
				<option>Media</option>
				<option>Baja</option>
			</select></td>
	</tr>
	<tr>
		<td valign="top" width="41%" align="right" colspan="2"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">Observaciones/ Comentarios</font></td>
		<td width="59%" colspan="5"><p><textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
	</tr>
	<tr>
		<td width="100%" colspan="7" height="20"></td>
	</tr>
	<tr>
		<td valign="top" width="18%" align="right"><font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Formación Requerida:&nbsp;</font></td>
		<td width="25%" valign="bottom"><font face="Trebuchet MS" style="font-weight: 700" size="1" color="#05459C">ACTIVIDAD 2</font></td>
		<td width="59%" valign="bottom" colspan="5"><font face="Trebuchet MS" size="1" color="#05459C">Aclaración</font></td>
	</tr>
	<tr>
		<td valign="top" width="18%" align="right" height="57">
			&nbsp;</td>
		<td width="25%" valign="top" height="57">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D1">
				<option>-- SELECCIONAR --</option>
				<option>Administración de Proyectos</option>
				<option>Gestión por Objetivos</option>
				<option>Árbol de Causas</option>
				<option>Tablero de Comando</option>
			</select></td>
		<td width="59%" colspan="5" height="57" valign="top">
			<textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
	</tr>
	<tr>
		<td width="100%" colspan="7" height="8"></td>
	</tr>
	<tr>
		<td valign="top" width="41%" align="right" colspan="2"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">¿Valida la formación solicitada?</font></td>
		<td width="3%" height="21"><p align="right"><font face="Trebuchet MS"><span style="font-size: 8pt"><input type="radio" value="V6" name="R1"></span></font></td>
		<td width="3%" height="21"><font face="Trebuchet MS" style="font-size: 8pt">SI</font></td>
		<td width="3%" height="21">&nbsp;</td>
		<td width="3%" height="21"><font face="Trebuchet MS"><span style="font-size: 8pt"><input type="radio" value="V7" name="R1"></span></font></td>
		<td width="53%" height="21"><font face="Trebuchet MS" style="font-size: 8pt">NO</font></td>
	</tr>
	<tr>
		<td width="41%" align="right" colspan="2" height="26"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">¿Cuál es la prioridad?</font></td>
		<td width="59%" colspan="5" height="26"><p style="margin-top: 0; margin-bottom: 0">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
				<option>-- SELECCIONAR --</option>
				<option>Alta</option>
				<option>Media</option>
				<option>Baja</option>
			</select></td>
	</tr>
	<tr>
		<td valign="top" width="41%" align="right" colspan="2"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">Observaciones/ Comentarios</font></td>
		<td width="59%" colspan="5"><textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
		</tr>
	<tr>
		<td width="100%" colspan="7" style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">&nbsp;</td>
	</tr>
	</table>
	</td>
	</tr>
</table>
	</div>	
<div align="center">
<table cellspacing="0" cellpadding="0" width="700" id="table24">
	<tr>
		<td width="700" style="padding-left: 4px; padding-right: 4px" height="5"></td>
	</tr>
	<tr>
		<td width="700" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#808080" bordercolor="#808080">
			<p style="margin-left: 6px; margin-top:0; margin-bottom:0">
				<b>
				<font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF">
				2</font><font face="Trebuchet MS" style="font-size: 9pt"><font color="#FFFFFF">.</font>
					</font>
					<font face="Trebuchet MS" style="font-size: 9pt" color="#336699">
						<a target="_self" href="javascript:mostrar('capa2')" onclick="mostrar('capa2');ocultar('capa1')" ondblclick="ocultar('capa2')" id="pepemac" onChange="inicial()">
							<span style="text-decoration: none">
								<font color="#05459C">[+]</font>
							</span>
						</a>
					</font>
					<font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF">OTRAS INFORMACIONES</font></b></p>
		</td>
	</tr>
</table>
</div>

<div id='capa2'>
<table>
			<tr>
	<td width="700" height="5" colspan="5"></td>
	</tr>
	<tr>
	<td width="700" colspan="5">

<p style="margin: 0 8px" align="justify">
	<font color="#807F84">
	<span style="font-family: Trebuchet MS; font-style: italic; font-size: 8pt">
	Favor responder a las siguientes consultas</span></font><font face="Verdana" style="font-size: 8pt" color="#807F84"><span style="font-family: Trebuchet MS; font-style:italic">:</span></font></p></td>
	</tr>
	<tr>
	<td width="449">

<p style="margin-left: 20px">
<font face="Trebuchet MS" style="font-size: 8pt">A. ¿Disctaste algún tipo de capacitación dentro o fuera de la compañía?</font></td>
	<td width="20">

<font face="Trebuchet MS"><span style="font-size: 8pt">
<input type="radio" value="V1" name="R1"></span></font></td>
	<td width="38">

<font face="Trebuchet MS" style="font-size: 8pt">SI</font></td>
	<td width="20"><font face="Trebuchet MS"><span style="font-size: 8pt"><input type="radio" value="V2" name="R1"></span></font></td>
	<td width="156"><font face="Trebuchet MS" style="font-size: 8pt">NO</font></td>
	</tr>
	<tr>
	<td width="449"><p style="margin-left: 20px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">B. ¿Estarías dispuesto a ser instructor interno?</font></td>
	<td width="20"><font face="Trebuchet MS"><span style="font-size: 8pt"><input type="radio" value="V3" name="R2"></span></font></td>
	<td width="38"><font face="Trebuchet MS" style="font-size: 8pt">SI</font></td>
	<td width="20"><font face="Trebuchet MS"><span style="font-size: 8pt"><input type="radio" value="V4" name="R2"></span></font></td>
	<td width="156"><font face="Trebuchet MS" style="font-size: 8pt">NO</font></td>
	</tr>
	<tr><td width="449"><p style="margin-left: 20px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">C. ¿Sobre que temática podrías capacitar?</font></td>
	<td width="20">&nbsp;</td>
	<td width="38">&nbsp;</td>
	<td width="20">&nbsp;</td>
	<td width="156">&nbsp;</td>
	</tr>
	<tr>
	<td width="683" colspan="5"><p style="margin-left: 20px"><textarea cols="103" rows="4" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
	</tr>
	<tr>
	<td width="683" colspan="5"><p style="margin-left: 20px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">D. ¿Alguna otra información que quieras agregar?</font></td>
	</tr>
	<tr>
	<td width="683" colspan="5"><p style="margin-left: 20px"><textarea cols="103" rows="4" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea></td>
	</tr>
</table>
<table>	
<tr>
	<td width="700"><p style="margin-left: 20px"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">COMENTARIOS OCUPANTE:</font></p></td>
</tr>
<tr>
	<td width="700" height="21">
		<p style="margin-left: 20px">
			<textarea cols="103" rows="4" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea><font face="Trebuchet MS">
			</font>
		</p>
	</td>
</tr>
<tr>
<td width="700" height="5"></td>
	</tr>
<tr>
	<td width="700">
		<p style="margin-left: 20px"><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">COMENTARIOS SUPERIOR:</font></p>
	</td>
</tr>
<tr>
	<td width="700" height="21">
		<p style="margin-left: 20px">
			<textarea cols="103" rows="4" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></textarea><font face="Trebuchet MS">
			</font>
		</p>
	</td>
</tr>
</table>
</div>
</td>
</tr>
	<tr>
		<td width="700" height="20"></td>
	</tr>
	<tr>
		<td width="700" height="21">
			<p style="margin-left: 45px">
				<font face="Trebuchet MS">
				&nbsp;</font><input type="button" value="ENVIAR" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #C0C0C0"><font face="Trebuchet MS">
				</font>
				<font face="Trebuchet MS">
				&nbsp;</font></p>
		</td>
	</tr>
</table>
</form>
</body>
</html>