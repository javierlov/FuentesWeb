<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Sistema de Gestión de RR.HH. ::..</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			body {
				scrollbar-face-color: #aaaaaa;
				scrollbar-highlight-color: #aaaaaa;
				scrollbar-shadow-color: #aaaaaa;
				scrollbar-3dlight-color: #eeeeee;
				scrollbar-arrow-color: #eeeeee;
				scrollbar-track-color: #e3e3e3;
				scrollbar-darkshadow-color: ffffff;
			}
		</style>
		<script type="text/javascript">
			function inicial() {
				if (document.getElementById('pepemac').value == "76711059") {
					mostrar('capa1');
					mostrar('capa2');
					mostrar('capa3');
				}
				else {
					ocultar('capa1');
					ocultar('capa2');
					ocultar('capa3');
				}
			}

			function mostrar(nombreCapa) {
				document.getElementById(nombreCapa).style.display = "block";
			}

			function ocultar(nombreCapa) {
				document.getElementById(nombreCapa).style.display = "none";
			}


			window.parent.document.getElementById('volver').style.display = 'block';
		</script>
	</head>

	<body onLoad="inicial()" link="#336699" vlink="#336699" alink="#336699" topmargin="3" bottommargin="3" leftmargin="0" rightmargin="0">
		<form action="" method="post">
			<table bgcolor="#FFFFFF" align="center" width="700">
				<tr>
					<td>
						<table bgcolor="#FFFFFF" align="center" cellspacing="0" width="700" id="table11" height="167">
							<tr>
								<td width="377" height="25">
									<table border="0" bgcolor="#FFFFFF" cellspacing="0" width="293">
										<tr>
											<td style="padding-left: 4px; padding-right: 4px" width="8">&nbsp;</td>
											<td style="border-left:1px solid #C0C0C0; border-top:1px solid #C0C0C0; border-bottom:1px solid #C0C0C0; padding-left: 4px; padding-right: 4px" bgcolor="#807F84" width="112">
												<p align="left">
													<span style="font-weight: 700"><font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">USUARIO REFERENTE:</font></span>
												</p>
											</td>
											<td style="border-right:1px solid #C0C0C0; border-top:1px solid #C0C0C0; border-bottom:1px solid #C0C0C0; padding-left: 4px; padding-right: 4px; " bgcolor="#807F84">
												<select id="UsuarioAEvaluar" name="UsuarioAEvaluar" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
													<option>-- SELECCIONAR --</option>
												</select><font face="Trebuchet MS"> </font>
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
							<tr>
								<td width="752" height="10" colspan="2"></td>
							</tr>
							<tr>
								<td width="377" height="124" valign="top">
									<table border="0" cellspacing="0" width="292">
										<tr>
											<td style="padding-left: 4px; padding-right: 4px" rowspan="5" width="7">&nbsp;</td>
											<td colspan="2" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#C0C0C0" bgcolor="#807F84">
												<span style="font-weight: 700"><font face="Trebuchet MS" color="#FFFFFF" style="font-size: 8pt">EMPRESA</font></span>
											</td>
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
									</table>
								</td>
								<td align="left" width="375" valign="top">
									<table border="0" cellspacing="0" width="295">
										<tr>
											<td colspan="2" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#C0C0C0" bgcolor="#807F84"><span style="font-weight: 700"><font face="Trebuchet MS" color="#FFFFFF" style="font-size: 8pt">DATOS DEL JEFE</font></span></td>
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

									<div align="center">
										<table cellspacing="0" cellpadding="0" width="700">
<tr>
	<td width="700" style="padding-left: 4px; padding-right: 4px" height="5"></td>
	</tr>
<tr>
	<td width="700" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#808080" bordercolor="#808080">
<p style="margin-left: 6px; margin-top:0; margin-bottom:0"><b><font face="Trebuchet MS" style="font-size: 9pt"><font color="#FFFFFF">1.</font>
</font><font face="Trebuchet MS" style="font-size: 9pt" color="#336699"><a target="_self" href="javascript:mostrar('capa1')" onclick="mostrar('capa1');ocultar('capa2');ocultar('capa3')" ondblclick="ocultar('capa1')" id="pepemac" onChange="inicial()">
<span style="text-decoration: none"><font color="#05459C">[+]</font></span></a></font><font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF"> COMPETENCIAS GENERALES</font></b></td>
	</tr>
</table>

</div>

<div id='capa1'>
<table>	
	<tr>
	<td width="700">
<table border="0" width="694" cellspacing="0" cellpadding="0" id="table23">
	<tr colspan="3">
		<td width="775" height="5" colspan="3"></td>
	</tr>
	<tr>
		<td width="749" align="right" colspan="3">

<p class="MsoNormal" style="margin: 0 12px" align="left">
<span style="font-family: Trebuchet MS" lang="ES-AR">
<font style="font-size: 8pt" color="#807F84">De acuerdo a la descripción del puesto de trabajo 
que actualmente ocupas, y teniendo en cuenta los niveles definidos en conjunto 
con tu superior, nos interesa conocer que tipo de formación necesitas para 
desempeñar tus funciones en la organización.</font></span></p>
		<p class="MsoNormal" style="margin: 0 12px" align="left">
&nbsp;</p>
<p class="MsoNormal" style="margin: 0 12px" align="left">
<span style="font-family: Trebuchet MS" lang="ES-AR">
<font style="font-size: 8pt" color="#807F84">Favor de definir en la siguiente tabla, cuales son
<b>las competencias</b> que necesitas desarrollar y que requieren algún tipo de 
capacitación, y ampliar la información en el cuadro siguiente.</font></span></p>
		<p class="MsoNormal" style="margin: 0 12px" align="left">
&nbsp;</p>
<p class="MsoNormal" style="margin: 0 12px" align="left">
<span style="font-family: Trebuchet MS" lang="ES-AR">
<font style="font-size: 8pt" color="#807F84">Tu superior inmediato validará la misma y 
establecerá la prioridad.</font></span></p>
		<p class="MsoNormal" style="margin: 0 12px" align="left">
&nbsp;</p>
<p class="MsoNormal" style="margin: 0 12px" align="left">
<span style="font-family: Trebuchet MS" lang="es-ar">
<font style="font-size: 8pt" color="#807F84">Elegí dos opciones. En el caso de elegir otros, por 
favor ampliar la información en el recuadro.</font></span><p class="MsoNormal" style="margin: 0 12px" align="left">
&nbsp;</td>
	</tr>
	<tr>
		<td width="749" align="right" colspan="3">
		<p align="left" style="margin-top: 0; margin-bottom: 0; margin-left:15px">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#05459C">
		DESCRIPCIÓN DEL PUESTO:</font></p>
		<p align="left" style="margin-top: 0; margin-bottom: 0; margin-left:15px">
		<span style="font-family: Trebuchet MS; font-size: 8pt"><b>
		<font color="#05459C">&gt; </font></b><font color="#807F84">Competencia: </font>Alcance de la responsabilidad. </span>
		</p>
		<p align="left" style="margin-top: 0; margin-bottom: 0; margin-left:15px">
		<span style="font-family: Trebuchet MS; font-size: 8pt">
		<font color="#05459C"><b>&gt; </b> </font><font color="#807F84">Factor: </font></span><font face="Trebuchet MS" style="font-size: 8pt">Orientación a los resultados.</font></p>
		<p align="left" style="margin-top: 0; margin-bottom: 0; margin-left:15px">
		<span style="font-family: Trebuchet MS; font-size: 8pt">
		<font color="#05459C"><b>&gt; </b> </font><font color="#807F84">
		Respuesta: </font></span><font face="Trebuchet MS" style="font-size: 8pt">Alta: cumple con eficiencia los objetivos esperados, realiza análisis de resultados y establece planes de mejora.</font></td>
	</tr>
	<tr>
		<td width="749" height="15"colspan="3"></td>
	</tr>
	<tr>
		<td width="20" align="right" rowspan="2">
		<img border="0" src="../images/opcion1.jpg" width="20" height="133"></td>
		<td width="350" align="right" bgcolor="#E8E8E8">
		<p align="left"><font face="Trebuchet MS" color="#E8E8E8" size="1">
		<span style="font-weight: 700">J</span></font></td>
		<td width="347" align="right" bgcolor="#E8E8E8" valign="bottom">
		<p align="left"><span style="font-weight: 700">
		<font face="Trebuchet MS" color="#05459C" size="1">
		JEFE</font></span></td>
	</tr>
	<tr>
		<td width="350" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table25">
	<tr>
		<td valign="bottom">
		<p style="margin-left: 5px"><font face="Trebuchet MS" style="font-weight: 700" size="1" color="#05459C">ACTIVIDAD 1</font></td>
	</tr>
	<tr>
		<td valign="top" height="15">
			<p style="margin-left: 5px">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D2">
				<option>-- SELECCIONAR --</option>
				<option>Administración de Proyectos</option>
				<option>Gestión por Objetivos</option>
				<option>Árbol de Causas</option>
				<option>Tablero de Comando</option>
				<option>Otros</option>
			</select></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<font face="Trebuchet MS" size="1" color="#05459C">Aclaración</font></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="S1"></textarea></td>
	</tr>
	</table>
		</div>
		</td>
		<td width="347" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table26">
	<tr>
		<td valign="top" colspan="4"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">¿Valida la formación solicitada?</font></td>
		<td width="125" valign="bottom"><font face="Trebuchet MS" style="font-size: 8pt">¿Cuál es la prioridad?</font></td>
	</tr>
	<tr>
		<td width="20" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R2" value="V5" style="font-weight: 700"></span></font></td>
		<td width="62" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">SI</font></td>
		<td width="22" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R1" value="V3" style="font-weight: 700"></span></font></td>
		<td width="75" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">NO</font></td>
		<td width="125" height="15" valign="top">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D3">
				<option>-- SELECCIONAR --</option>
				<option>Alta</option>
				<option>Media</option>
				<option>Baja</option>
			</select></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
		<font face="Trebuchet MS" size="1" color="#05459C">Observaciones/ Comentarios</font></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
			<textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="S2"></textarea></td>
	</tr>
	</table>
		</div>
		</td>
	</tr>
	<tr>
		<td width="717" align="right" colspan="3" height="10"></td>
	</tr>
	<tr>
		<td width="20" align="right" rowspan="2">
		<img border="0" src="../images/opcion2.jpg" width="20" height="133"></td>
		<td width="350" align="right" bgcolor="#E8E8E8">
		<p align="left"><font face="Trebuchet MS" color="#E8E8E8" size="1">
		<span style="font-weight: 700">J</span></font></td>
		<td width="347" align="right" bgcolor="#E8E8E8" valign="bottom">
		<p align="left"><span style="font-weight: 700">
		<font face="Trebuchet MS" color="#05459C" size="1">
		JEFE</font></span></td>
	</tr>
	<tr>
		<td width="350" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table29">
	<tr>
		<td valign="bottom">
		<p style="margin-left: 5px"><font face="Trebuchet MS" style="font-weight: 700" size="1" color="#05459C">ACTIVIDAD 1</font></td>
	</tr>
	<tr>
		<td valign="top" height="15">
			<p style="margin-left: 5px">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D4">
				<option>-- SELECCIONAR --</option>
				<option>Administración de Proyectos</option>
				<option>Gestión por Objetivos</option>
				<option>Árbol de Causas</option>
				<option>Tablero de Comando</option>
				<option>Otros</option>
			</select></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<font face="Trebuchet MS" size="1" color="#05459C">Aclaración</font></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="S3"></textarea></td>
	</tr>
	</table>
		</div>
		</td>
		<td width="347" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table30">
	<tr>
		<td valign="top" colspan="4"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">¿Valida la formación solicitada?</font></td>
		<td width="125" valign="bottom"><font face="Trebuchet MS" style="font-size: 8pt">¿Cuál es la prioridad?</font></td>
	</tr>
	<tr>
		<td width="20" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R2" value="V6" style="font-weight: 700"></span></font></td>
		<td width="62" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">SI</font></td>
		<td width="22" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R1" value="V4" style="font-weight: 700"></span></font></td>
		<td width="75" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">NO</font></td>
		<td width="125" height="15" valign="top">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D5">
				<option>-- SELECCIONAR --</option>
				<option>Alta</option>
				<option>Media</option>
				<option>Baja</option>
			</select></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
		<font face="Trebuchet MS" size="1" color="#05459C">Observaciones/ Comentarios</font></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
			<textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="S4"></textarea></td>
	</tr>
	</table>
		</div>
		</td>
	</tr>
	<tr>
		<td width="20" align="right">&nbsp;</td>
		<td width="350" align="right">&nbsp;</td>
		<td width="347" align="right">&nbsp;</td>
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
						<a target="_self" href="javascript:mostrar('capa2')" onclick="mostrar('capa2');ocultar('capa1');ocultar('capa3')" ondblclick="ocultar('capa2')" id="pepemac" onChange="inicial()">
							<span style="text-decoration: none">
								<font color="#05459C">[+]</font>
							</span>
						</a>
					</font>
					<font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF">
				CONOCIMIENTOS NECESARIOS</font></b></p>
		</td>
	</tr>
</table>
</div>

<div id='capa2'>
<table>
			<tr>
	<td width="700" height="5"></td>
	</tr>
	</table>
<table border="0" width="694" cellspacing="0" cellpadding="0" id="table23">
	<tr colspan="3">
		<td width="775" height="5" colspan="3"></td>
	</tr>
	<tr>
		<td width="749" align="right" colspan="3">

<p class="MsoNormal" align="left" style="margin: 0 12px">
<span lang="ES-AR" style="font-family: Trebuchet MS">
<font color="#807F84" style="font-size: 8pt">Favor de definir en la siguiente 
tabla, cuales son <b>los conocimientos</b> <b>relacionados con tu puesto de 
trabajo</b> que necesitas incorporar y que requieren algún tipo de capacitación, 
y ampliar la información en el cuadro siguiente.</font></span></p>
<p class="MsoNormal" align="left" style="margin: 0 12px">&nbsp;</p>
<p class="MsoNormal" align="left" style="margin: 0 12px">
<span lang="ES-AR" style="font-family: Trebuchet MS">
<font color="#807F84" style="font-size: 8pt">Tu superior inmediato validará la 
misma y establecerá la prioridad.</font></span></p>
<p class="MsoNormal" align="left" style="margin: 0 12px">&nbsp;</td>
	</tr>
	<tr>
		<td width="749" align="right" colspan="3">
		<p align="left" style="margin-top: 0; margin-bottom: 0; margin-left:20px">
		<span style="font-weight: 700">
		<font face="Trebuchet MS" style="font-size: 8pt" color="#05459C">&lt;C</font></span><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#05459C">ONOCIMIENTO&gt;:</font></p>
		</td>
	</tr>
	<tr>
		<td width="20" align="right" rowspan="2">
		<img border="0" src="../images/opcion1.jpg" width="20" height="133"></td>
		<td width="350" align="right" bgcolor="#E8E8E8">
		<p align="left"><font face="Trebuchet MS" color="#E8E8E8" size="1">
		<span style="font-weight: 700">J</span></font></td>
		<td width="347" align="right" bgcolor="#E8E8E8" valign="bottom">
		<p align="left"><span style="font-weight: 700">
		<font face="Trebuchet MS" color="#05459C" size="1">
		JEFE</font></span></td>
	</tr>
	<tr>
		<td width="350" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table25">
	<tr>
		<td valign="bottom">
		<p style="margin-left: 5px"><font face="Trebuchet MS" style="font-weight: 700" size="1" color="#05459C">ACTIVIDAD 1</font></td>
	</tr>
	<tr>
		<td valign="top" height="15">
			<p style="margin-left: 5px">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D2">
				<option>-- SELECCIONAR --</option>
			</select></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<font face="Trebuchet MS" size="1" color="#05459C">Aclaración</font></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="S1"></textarea></td>
	</tr>
	</table>
		</div>
		</td>
		<td width="347" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table26">
	<tr>
		<td valign="top" colspan="4"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">¿Valida la formación solicitada?</font></td>
		<td width="125" valign="bottom"><font face="Trebuchet MS" style="font-size: 8pt">¿Cuál es la prioridad?</font></td>
	</tr>
	<tr>
		<td width="20" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R2" value="V5" style="font-weight: 700"></span></font></td>
		<td width="62" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">SI</font></td>
		<td width="22" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R1" value="V3" style="font-weight: 700"></span></font></td>
		<td width="75" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">NO</font></td>
		<td width="125" height="15" valign="top">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D3">
				<option>-- SELECCIONAR --</option>
				<option>Alta</option>
				<option>Media</option>
				<option>Baja</option>
			</select></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
		<font face="Trebuchet MS" size="1" color="#05459C">Observaciones/ Comentarios</font></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
			<textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="S2"></textarea></td>
	</tr>
	</table>
		</div>
		</td>
	</tr>
	<tr>
		<td width="717" align="right" colspan="3" height="10"></td>
	</tr>
	<tr>
		<td width="20" align="right" rowspan="2">
		<img border="0" src="../images/opcion2.jpg" width="20" height="133"></td>
		<td width="350" align="right" bgcolor="#E8E8E8">
		<p align="left"><font face="Trebuchet MS" color="#E8E8E8" size="1">
		<span style="font-weight: 700">J</span></font></td>
		<td width="347" align="right" bgcolor="#E8E8E8" valign="bottom">
		<p align="left"><span style="font-weight: 700">
		<font face="Trebuchet MS" color="#05459C" size="1">
		JEFE</font></span></td>
	</tr>
	<tr>
		<td width="350" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table29">
	<tr>
		<td valign="bottom">
		<p style="margin-left: 5px"><font face="Trebuchet MS" style="font-weight: 700" size="1" color="#05459C">ACTIVIDAD 1</font></td>
	</tr>
	<tr>
		<td valign="top" height="15">
			<p style="margin-left: 5px">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D4">
				<option>-- SELECCIONAR --</option>
			</select></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<font face="Trebuchet MS" size="1" color="#05459C">Aclaración</font></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="S3"></textarea></td>
	</tr>
	</table>
		</div>
		</td>
		<td width="347" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table30">
	<tr>
		<td valign="top" colspan="4"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">¿Valida la formación solicitada?</font></td>
		<td width="125" valign="bottom"><font face="Trebuchet MS" style="font-size: 8pt">¿Cuál es la prioridad?</font></td>
	</tr>
	<tr>
		<td width="20" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R2" value="V6" style="font-weight: 700"></span></font></td>
		<td width="62" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">SI</font></td>
		<td width="22" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R1" value="V4" style="font-weight: 700"></span></font></td>
		<td width="75" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">NO</font></td>
		<td width="125" height="15" valign="top">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D5">
				<option>-- SELECCIONAR --</option>
				<option>Alta</option>
				<option>Media</option>
				<option>Baja</option>
			</select></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
		<font face="Trebuchet MS" size="1" color="#05459C">Observaciones/ Comentarios</font></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
			<textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="S4"></textarea></td>
	</tr>
	</table>
		</div>
		</td>
	</tr>
	<tr>
		<td width="717" align="right" colspan="3" style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">&nbsp;</td>
	</tr>
	<tr>
		<td width="749" align="right" colspan="3" height="5"></td>
	</tr>
	<tr>
		<td width="749" align="right" colspan="3">
		<p align="left" style="margin-top: 0; margin-bottom: 0; margin-left:20px">
		<span style="font-weight: 700">
		<font face="Trebuchet MS" style="font-size: 8pt" color="#05459C">
		HERRAMIENTAS</font></span><font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#05459C">
		DE OFFICE:</font></p>
		</td>
	</tr>
	<tr>
		<td width="20" align="right" rowspan="2">
		<img border="0" src="../images/opcion1.jpg" width="20" height="133"></td>
		<td width="350" align="right" bgcolor="#E8E8E8">
		<p align="left"><font face="Trebuchet MS" color="#E8E8E8" size="1">
		<span style="font-weight: 700">J</span></font></td>
		<td width="347" align="right" bgcolor="#E8E8E8" valign="bottom">
		<p align="left"><span style="font-weight: 700">
		<font face="Trebuchet MS" color="#05459C" size="1">
		JEFE</font></span></td>
	</tr>
	<tr>
		<td width="350" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table33">
	<tr>
		<td valign="bottom">
		<p style="margin-left: 5px"><font face="Trebuchet MS" style="font-weight: 700" size="1" color="#05459C">ACTIVIDAD 1</font></td>
	</tr>
	<tr>
		<td valign="top" height="15">
			<p style="margin-left: 5px">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D6">
				<option>-- SELECCIONAR --</option>
			</select></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<font face="Trebuchet MS" size="1" color="#05459C">Aclaración</font></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<input type="text" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="T1" size="50"></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<font face="Trebuchet MS" size="1" color="#05459C">Nivel</font></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D10">
				<option>-- SELECCIONAR --</option>
				<option>Básico</option>
				<option>Intermedio</option>
				<option>Avanzado</option>
			</select></td>
	</tr>
	</table>
		</div>
		</td>
		<td width="347" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table34">
	<tr>
		<td valign="top" colspan="4"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">¿Valida la formación solicitada?</font></td>
		<td width="125" valign="bottom"><font face="Trebuchet MS" style="font-size: 8pt">¿Cuál es la prioridad?</font></td>
	</tr>
	<tr>
		<td width="20" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R2" value="V7" style="font-weight: 700"></span></font></td>
		<td width="62" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">SI</font></td>
		<td width="22" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R1" value="V5" style="font-weight: 700"></span></font></td>
		<td width="75" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">NO</font></td>
		<td width="125" height="15" valign="top">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D7">
				<option>-- SELECCIONAR --</option>
				<option>Alta</option>
				<option>Media</option>
				<option>Baja</option>
			</select></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
		<font face="Trebuchet MS" size="1" color="#05459C">Observaciones/ Comentarios</font></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
			<textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="S6"></textarea></td>
	</tr>
	</table>
		</div>
		</td>
	</tr>
	<tr>
		<td width="717" align="right" colspan="3" height="10"></td>
	</tr>
	<tr>
		<td width="20" align="right" rowspan="2">
		<img border="0" src="../images/opcion2.jpg" width="20" height="133"></td>
		<td width="350" align="right" bgcolor="#E8E8E8">
		<p align="left"><font face="Trebuchet MS" color="#E8E8E8" size="1">
		<span style="font-weight: 700">J</span></font></td>
		<td width="347" align="right" bgcolor="#E8E8E8" valign="bottom">
		<p align="left"><span style="font-weight: 700">
		<font face="Trebuchet MS" color="#05459C" size="1">
		JEFE</font></span></td>
	</tr>
	<tr>
		<td width="350" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table37">
	<tr>
		<td valign="bottom">
		<p style="margin-left: 5px"><font face="Trebuchet MS" style="font-weight: 700" size="1" color="#05459C">ACTIVIDAD 1</font></td>
	</tr>
	<tr>
		<td valign="top" height="15">
			<p style="margin-left: 5px">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D8">
				<option>-- SELECCIONAR --</option>
			</select></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<font face="Trebuchet MS" size="1" color="#05459C">Aclaración</font></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<input type="text" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="T2" size="50"></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<font face="Trebuchet MS" size="1" color="#05459C">Nivel</font></td>
	</tr>
	<tr>
		<td valign="top" height="14">
			<p style="margin-left: 5px">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D11">
				<option>-- SELECCIONAR --</option>
				<option>Básico</option>
				<option>Intermedio</option>
				<option>Avanzado</option>
			</select></td>
	</tr>
	</table>
		</div>
		</td>
		<td width="347" align="right" bgcolor="#E8E8E8" height="116" valign="top">
		<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table38">
	<tr>
		<td valign="top" colspan="4"><p style="margin-right: 12px; margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" style="font-size: 8pt">¿Valida la formación solicitada?</font></td>
		<td width="125" valign="bottom"><font face="Trebuchet MS" style="font-size: 8pt">¿Cuál es la prioridad?</font></td>
	</tr>
	<tr>
		<td width="20" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R2" value="V8" style="font-weight: 700"></span></font></td>
		<td width="62" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">SI</font></td>
		<td width="22" height="15">
		<font size="1" face="Trebuchet MS"><span style="font-size: 8pt">
		<input type="radio" name="R1" value="V6" style="font-weight: 700"></span></font></td>
		<td width="75" height="15">
		<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">NO</font></td>
		<td width="125" height="15" valign="top">
			<select size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="D9">
				<option>-- SELECCIONAR --</option>
				<option>Alta</option>
				<option>Media</option>
				<option>Baja</option>
			</select></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
		<font face="Trebuchet MS" size="1" color="#05459C">Observaciones/ Comentarios</font></td>
	</tr>
	<tr>
		<td colspan="5" height="14" valign="top">
			<textarea cols="50" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" name="S8"></textarea></td>
	</tr>
	</table>
		</div>
		</td>
	</tr>
	<tr>
		<td width="20" align="right">&nbsp;</td>
		<td width="350" align="right">&nbsp;</td>
		<td width="347" align="right">&nbsp;</td>
	</tr>
	</table>


</div>
</td>
</tr>
	<tr>
		<td width="700" height="20">
<div align="center">
<table cellspacing="0" cellpadding="0" width="700" id="table24">
	<tr>
		<td width="700" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#808080" bordercolor="#808080">
			<p style="margin-left: 6px; margin-top:0; margin-bottom:0">
				<b>
				<font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF">
				3</font><font face="Trebuchet MS" style="font-size: 9pt"><font color="#FFFFFF">.</font>
					</font>
					<font face="Trebuchet MS" style="font-size: 9pt" color="#336699">
						<a target="_self" href="javascript:mostrar('capa3')" onclick="mostrar('capa3');ocultar('capa1');ocultar('capa2')" ondblclick="ocultar('capa3')" id="pepemac" onChange="inicial()">
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

<div id='capa3'>
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
		<td width="700" height="21">
			<p style="margin-left: 45px">
				<font face="Trebuchet MS">
				&nbsp;</font><input type="button" value="ENVIAR" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF"><font face="Trebuchet MS">
				</font>
				<font face="Trebuchet MS">
				&nbsp;</font></p>
		</td>
	</tr>
</table>
</form>
</body>
</html>