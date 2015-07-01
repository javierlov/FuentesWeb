<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


if (!isset($_SESSION["identidad"]))
	$_SESSION["identidad"] = getWindowsLoginName(true);

$ano = date("Y");
$user = $_SESSION["identidad"];

$params = array(":evaluador" => $user, ":ano" => $ano);
$sql =
	"SELECT 1
		 FROM rrhh.hue_usuarioevaluacion
		WHERE ue_evaluador = UPPER(:evaluador)
			AND ue_anoevaluacion = :ano";
$esEvaluador = (valorSql($sql, -1, $params) == 1);

$permisoCambioIdentidad = array("ALAPACO", "GLANCHA", "VDOMINGUEZ", "VLOPEZ");
?>
<html>
	<head>
		<?= getHead("Sistema de Evaluación de Desempeño", array("style.css?today=".date("Ymd")))?>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script language="JavaScript" src="js/hint_config.js"></script>
		<script language="JavaScript" src="js/evaluacion.js?rnd=<?= time()?>"></script>
		<script>
			function cambiarAno() {
				cambiarUsuarioAEvaluar('<?= $user?>', document.getElementById('Ano').value);
			}
		</script>
		<style>
			.hintText {background-color:#ffc; color:#ooo; font-family:tahoma, verdana, arial; font-size:12px; padding:5px;}
		</style>
	</head>
	<body alink="#336699" bgcolor="#cccccc" link="#336699" style="margin-top:15px;" vlink="#336699">		
		<iframe id="iframeEvaluacion" name="iframeEvaluacion" src="" style="display:none;"></iframe>
		<form action="procesar_formulario.php" enctype="multipart/form-data" id="formEvaluacion" method="post" name="formEvaluacion" target="iframeEvaluacion" onSubmit="return validarFormEvaluacion(formEvaluacion)">
			<input id="CerrarEvaluacion" name="CerrarEvaluacion" type="hidden" />
			<input id="estadoObjetivo1Tmp" name="estadoObjetivo1Tmp" type="hidden" />
			<input id="estadoObjetivo2Tmp" name="estadoObjetivo2Tmp" type="hidden" />
			<input id="Evaluado" name="Evaluado" type="hidden" />
			<input id="Evaluador" name="Evaluador" type="hidden" />
			<input id="FormularioId" name="FormularioId" type="hidden" />
			<input id="Objetivo1Id" name="Objetivo1Id" type="hidden" />
			<input id="Objetivo2Id" name="Objetivo2Id" type="hidden" />
			<input id="Supervisor" name="Supervisor" type="hidden" />
			<input id="ValidarCompetenciasConduccion" name="ValidarCompetenciasConduccion" type="hidden" />
			<table align="center" bgcolor="#ffffff" border="2" bordercolor="#808080" id="table1" style="border-style:solid; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" width="755">
				<tr>
					<td>
						<table align="center" bgcolor="#ffffff" cellspacing="0" style="padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" width="754">
							<tr>
								<td colspan="3" height="51">
									<table border="0" cellspacing="0" width="100%">
										<tr>
											<td style="border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px;">
												<p style="margin-bottom:0; margin-top:0;"><b><font face="Verdana" style="font-size:8pt;">SISTEMA DE EVALUACIÓN DEL DESEMPEÑO</font></b></p>
											</td>
											<td style="border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px;" width="115"><img border="0" height="26"src="images/logoART.jpg" width="115" /></td>
										</tr>
										<tr>
											<td colspan="2" height="13" style="border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px;"></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height="50" rowspan="2" valign="top" width="377">
									<table bgcolor="#ffffff" border="0" cellspacing="0" id="table3">
										<tr>
											<td width="10"></td>
											<td><p align="center"><img border="0" height="28" src="images/user.jpg" width="26" /></td>
											<td><font color="#808080" face="Verdana" style="font-size:8pt; font-weight:700;">Usuario Actual:</font></td>
											<td><font color="#336699" face="Verdana" style="font-size:8pt;">&nbsp;<?= getUserName($_SESSION["identidad"]) ?></font></td>
										</tr>
									</table>
								</td>
								<td align="left" height="21" valign="top" width="293">
									<table bgcolor="#ffffff" border="0" cellspacing="0" width="293">
										<tr>
											<td bgcolor="#c0c0c0" bordercolor="#808080" style="border-bottom-style:solid; border-bottom-width:0px; border-left-style:solid; border-left-width:1px; border-top-style:solid; border-top-width:1px; padding-left:4px; padding-right:4px; width:120px;">
												<font color="#ffffff" face="Verdana" style="font-size:8pt; font-weight:700;">Año:</font>
											</td>
											<td bgcolor="#c0c0c0" bordercolor="#808080" style="border-bottom-style:solid; border-bottom-width:0px; border-right-style:solid; border-right-width:1px border-top-style:solid; border-top-width:1px; padding-left:4px; padding-right:4px;">
												<select id="Ano" name="Ano" size="1" style="border:1px solid #808080; color:#808080; font-family:Verdana; font-size:8pt; font-weight:bold; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" title="Año" validar="true" onChange="cambiarAno()"></select>
											</td>
										</tr>
									</table>
									<table bgcolor="#ffffff" border="0" cellspacing="0" id="tableUsuariosAEvaluar" name="tableUsuariosAEvaluar" width="293">
										<tr>
											<td bgcolor="#c0c0c0" bordercolor="#808080" style="border-bottom-style:solid; border-bottom-width:1px; border-left-style:solid; border-left-width:1px; border-top-style:solid; border-top-width:1px; padding-left:4px; padding-right:4px; width:120px;">
												<font color="#ffffff" face="Verdana" style="font-size:8pt; font-weight:700;">Usuario a evaluar:</font>
											</td>
											<td bgcolor="#c0c0c0" bordercolor="#808080" style="border-bottom-style:solid; border-bottom-width:1px; border-right-style:solid; border-right-width:1px; border-top-style:solid; border-top-width:1px; padding-left:4px; padding-right:4px;">
												<select id="UsuarioAEvaluar" name="UsuarioAEvaluar" size="1" style="border:1px solid #808080; color:#808080; font-family:Verdana; font-size:8pt; font-weight:bold; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" title="Usuario a evaluar" validar="true" onChange="cambiarUsuarioAEvaluar(document.getElementById('UsuarioAEvaluar').value, document.getElementById('Ano').value)"></select>
											</td>
										</tr>
									</table>
								</td>
								<td align="center" height="21" valign="middle" width="86">
<?
if (in_array(getWindowsLoginName(true), $permisoCambioIdentidad)) {
?>
	<img border="0" src="images/cambiar_identidad.png" style="cursor:pointer;" title="Cambiar Identidad" onClick="cambiarIdentidad()" />
	<br/>
<?
}
?>
									<img border="0" src="images/resultados.png" style="cursor:pointer;" title="Ver Resultados" onClick="window.location.href='/modules/evaluacion_desempeno/resultados/'" />
									&nbsp;&nbsp;
									<img border="0" src="images/imprimir.jpg" style="cursor:pointer;" title="Imprimir evaluación" onClick="imprimirEvaluacion()" />
								</td>
							</tr>
							<tr>
								<td align="left" height="21" valign="top" width="293"><p align="center"><div face="Verdana" id="divPeriodo" name="divPeriodo" style="font-size:8pt;">Período: ---</div></td>
								<td align="left" height="21" valign="top" width="86">&nbsp;</td>
							</tr>
							<tr>
								<td height="67" width="377">
									<table border="0" cellspacing="0" id="table6" width="292">
										<tr>
											<td rowspan="5" style="padding-left:4px; padding-right:4px;" width="7">&nbsp;</td>
											<td bgcolor="#6e96bc" bordercolor="#808080" colspan="2" style="border-style:solid; border-width:1px; padding-left:4px; padding-right:4px;">
												<span style="font-weight:700;"><font color="#ffffff" face="Verdana" size="1">DATOS EVALUADOR</font></span>
											</td>
										</tr>
										<tr>
											<td style="border-bottom:1px dotted #ccc; padding-left:4px; padding-right:4px;" width="127">
												<span style="font-weight:700;"><font color="#808080" face="Verdana" style="font-size:8pt;">Nombre</font></span>
												<font color="#808080" face="Verdana" style="font-size:8pt; font-weight:700;"> y Apellido:</font>
											</td>
											<td style="border-bottom:1px dotted #ccc; padding-left:4px; padding-right:4px;" width="134">
												<span face="Verdana" id="NombreEvaluador" name="NombreEvaluador" style="font-size:8pt;">&nbsp;</span>
											</td>
										</tr>
										<tr>
											<td style="border-bottom:1px dotted #ccc; padding-left:4px; padding-right:4px;" width="127">
												<span style="font-weight:700;"><font color="#808080" face="Verdana" style="font-size:8pt;">Puesto</font></span>
												<font color="#808080" face="Verdana" style="font-size:8pt; font-weight:700;">:</font>
											</td>
											<td style="border-bottom:1px dotted #ccc; padding-left:4px; padding-right:4px;" width="134">
												<span face="Verdana" id="PuestoEvaluador" name="PuestoEvaluador" style="font-size:8pt;">&nbsp;</span>
											</td>
										</tr>
										<tr>
											<td style="border-bottom:1px dotted #ccc; padding-left:4px; padding-right:4px;" width="127">
												<font color="#808080" face="Verdana" style="font-size:8pt; font-weight:700;">Área/Sector:</font>
											</td>
											<td style="border-bottom:1px dotted #ccc; padding-left:4px; padding-right:4px;" width="134">
												<span face="Verdana" id="SectorEvaluador" name="SectorEvaluador" style="font-size:8pt;">&nbsp;</span>
											</td>
										</tr>
										<tr>
											<td style="border-bottom:1px dotted #ccc; padding-left:4px; padding-right:4px;" width="127">
												<b><font color="#808080" face="Verdana" style="font-size:8pt;">Gerencia:</font></b>
											</td>
											<td style="border-bottom:1px dotted #ccc; padding-left:4px; padding-right:4px;" width="134">
												<span face="Verdana" id="GerenciaEvaluador" name="GerenciaEvaluador" style="font-size:8pt;">&nbsp;</span>
											</td>
										</tr>
									</table>
								</td>
								<td align="left" colspan="2" valign="top" width="375">
									<table border="0" cellspacing="0" id="table7">
										<tr>
											<td bgcolor="#6e96bc" bordercolor="#808080" colspan="2" style="border-style:solid; border-width:1px; padding-left:4px; padding-right:4px;">
												<span style="font-weight:700;"><font color="#ffffff" face="Verdana" size="1">DATOS EVALUADO</font></span>
											</td>
										</tr>
										<tr>
											<td style="border-bottom:1px dotted #c0c0c0; padding-left:4px; padding-right:4px;">
												<span style="font-weight:700;"><font color="#808080" face="Verdana" style="font-size:8pt;">Nombre</font></span>
												<font color="#808080" face="Verdana" style="font-size:8pt; font-weight:700;"> y Apellido:</font>
											</td>
											<td style="border-bottom:1px dotted #c0c0c0; padding-left:4px; padding-right:4px;">
												<span face="Verdana" id="NombreEvaluado" name="NombreEvaluado" style="font-size:8pt;">&nbsp;</span>
											</td>
										</tr>
										<tr>
											<td style="border-bottom:1px dotted #c0c0c0; padding-left:4px; padding-right:4px;">
												<span style="font-weight:700;"><font color="#808080" face="Verdana" style="font-size:8pt;">Puesto</font></span>
												<font color="#808080" face="Verdana" style="font-size:8pt; font-weight:700;">:</font>
											</td>
											<td style="border-bottom:1px dotted #c0c0c0; padding-left:4px; padding-right:4px;">
												<span face="Verdana" id="PuestoEvaluado" name="PuestoEvaluado" style="font-size:8pt;">&nbsp;</span>
											</td>
										</tr>
										<tr>
											<td style="border-bottom:1px dotted #c0c0c0; padding-left:4px; padding-right:4px;">
												<font color="#808080" face="Verdana" style="font-size:8pt; font-weight:700;">Área/Sector:</font>
											</td>
											<td style="border-bottom:1px dotted #c0c0c0; padding-left:4px; padding-right:4px;">
												<span face="Verdana" id="SectorEvaluado" name="SectorEvaluado" style="font-size:8pt;">&nbsp;</span>
											</td>
										</tr>
										<tr>
											<td style="border-bottom:1px dotted #c0c0c0; padding-left:4px; padding-right:4px;">
												<b><font color="#808080" face="Verdana" style="font-size:8pt;">Gerencia:</font></b>
											</td>
											<td style="border-bottom:1px dotted #c0c0c0; padding-left:4px; padding-right:4px;">
												<font face="Verdana" id="GerenciaEvaluado" name="GerenciaEvaluado" style="font-size:8pt;">&nbsp;</span>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="3" height="20" style="padding-left:4px; padding-right:4px;" width="752"></td>
							</tr>
						</table>
						<div align="center" class="FormLabelRojo" id="divDatosNoCargados" name="divDatosNoCargados" style="display:none;">
							<img border="0" src="images/warning.gif" />
							<br />
							Su evaluación aún no está disponible.
							<br />
						</div>
						<div align="center" id="divDatos" name="divDatos">
							<div align="center">
								<table cellpadding="0" cellspacing="0" width="740">
									<tr>
										<td bgcolor="#afc2be" bordercolor="#808080" colspan="2" style="border-style:solid; border-width:1px; padding-left:4px; padding-right:4px;" width="752">
											<p style="margin-bottom:0; margin-left:6px; margin-top:0;">
												<b>
													<font face="Verdana" style="font-size:8pt;">1.</font>
													<font color="#336699" face="Verdana" style="font-size:8pt;">
														<a href="#" onClick="mostrarSeccion('divCompetencias');"><span style="text-decoration:none;"> [+] </span></a>
													</font>
													<font face="Verdana" style="font-size:8pt;">Competencias</font>
												</b>
											</p>
										</td>
									</tr>
								</table>
							</div>
							<div id="divCompetencias" name="divCompetencias" style="display:none;">
								<table>
									<tr>
										<td height="5" width="752"></td>
									</tr>
									<tr>
										<td width="752"><p style="margin-left:8px;"><font face="Verdana" style="font-size:8pt; font-weight:700;">EVALUACIÓN DE COMPETENCIAS</font></p></td>
									</tr>
									<tr>
										<td width="752">
											<div class="Section1">
												<p align="justify" style="margin:0 8px; text-indent:0cm;"><font face="Verdana" size="2"><span style="font-family:Verdana; font-size:8pt;">Teniendo en cuenta los requerimientos del puesto y los comportamientos observados, indicá para cada competencia el nivel en el que se encuentra el evaluado marcando en la casilla correspondiente.</span></font></p>
												<p align="justify" style="margin:0 8px; text-indent:0cm;"><span style="font-family:Verdana; font-size:8pt;"><font color="#336699" face="Verdana"><b>Para el nivel A detallá con ejemplos</b></font><font face="Verdana" size="2">.</font></span><p align="justify" style="margin:0 8px; text-indent:0cm;">&nbsp;</p></p>
											</div>
										</td>
									</tr>
									<tr>
										<td width="752">
											<div align="center">
												<table border="0" cellspacing="1" id="table9" width="740">
													<tr>
														<td align="center" bgcolor="#6e96bc" bordercolor="#808080" style="border-style:solid; border-width:1px; padding-left:4px; padding-right:4px;" width="267">
															<font color="#ffffff" face="Verdana" size="1" style="font-weight:700;">COMPETENCIAS</font>
														</td>
														<td align="center" bgcolor="#6e96bc" bordercolor="#808080" colspan="5" style="border-style:solid; border-width:1px; padding-left:4px; padding-right:4px;">
															<font color="#ffffff" face="Verdana" size="1" style="font-weight:700;">NIVELES</font>
														</td>
														<td align="center" bgcolor="#6e96bc" bordercolor="#808080" style="border-style:solid; border-width:1px; padding-left:4px; padding-right:4px;">
															<font color="#ffffff" face="Verdana" size="1" style="font-weight:700;">EJEMPLOS</font>
														</td>
													</tr>
													<tr>
														<td align="center" bgcolor="#808080" width="275"><p style="margin:0 5px;"><font color="#ffffff" face="Verdana" style="font-size:8pt;">¿Qué se esperó del evaluado durante <span id="labelAno1">año</span>?</font></td>
														<td align="center" bgcolor="#808080" colspan="5" width="160"><p style="margin:0 5px;"><font color="#ffffff" face="Verdana" style="font-size:8pt;">¿Cómo lo hizo durante <span id="labelAno2">año</span>?</font></td>
														<td align="center" bgcolor="#808080" width="280"><p style="margin: 0 5px"><font color="#ffffff" face="Verdana" style="font-size:8pt;">¿Qué comportamientos concretos </font></p><p style="margin: 0 5px"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">podemos observar? (solo nivel A)</font></td>
													</tr>
													<tr>
														<td style="border-bottom-style:dotted; border-bottom-width:1px; padding-left:4px; padding-right:4px;" width="269">
															<table>
																<tr>
																	<td colspan="11"><font face="Verdana" style="cursor:help; font-size:8pt; font-weight:700;" onMouseOver="myHint.show(0, this)" onMouseOut="myHint.hide()">Orientación a los resultados</font></td>
																</tr>
																<tr class="FormLabelNegroSinNegrita11" id="trOrientacionEsp" style="display:none;">
																	<td style="cursor:help;" valign="middle" width="4" onMouseOver="myHint.show(1, this)" onMouseOut="myHint.hide()">A</td>
																	<td width="32"><input id="OrientacionEsp" name="OrientacionEsp" type="radio" value="A" /></td>
																	<td style="cursor:help;" valign="middle" width="4" onMouseOver="myHint.show(2, this)" onMouseOut="myHint.hide()">B</td>
																	<td width="32"><input id="OrientacionEsp" name="OrientacionEsp" type="radio" value="B"/ ></td>
																	<td style="cursor:help;" valign="middle" width="4" onMouseOver="myHint.show(3, this)" onMouseOut="myHint.hide()">C</td>
																	<td width="32"><input id="OrientacionEsp" name="OrientacionEsp" type="radio" value="C" /></td>
																	<td style="cursor:help;" valign="middle" width="4" onMouseOver="myHint.show(4, this)" onMouseOut="myHint.hide()">D</td>
																	<td width="32"><input id="OrientacionEsp" name="OrientacionEsp" type="radio" value="D" /></td>
																	<td style="cursor:help;" valign="middle" width="4" onMouseOver="myHint.show(5, this)" onMouseOut="myHint.hide()">E</td>
																	<td width="32"><input id="OrientacionEsp" name="OrientacionEsp" type="radio" value="E" /></td>
																	<td>&nbsp;</td>
																</tr>
															</table>
														</td>
														<td style="border-bottom-style:dotted; border-bottom-width:1px; padding-left:4px; padding-right:4px"><p style="margin-bottom:0; margin-top:0;"><font face="Verdana"><input id="Orientacion" name="Orientacion" type="radio" value="A" /></font></p><p style="margin-bottom:0; margin-top:0;" align="center"><font face="Verdana" style="cursor:help; font-size:8pt;" onMouseOver="myHint.show(1, this)" onMouseOut="myHint.hide()">A</font></p></td>
														<td align="center" style="border-bottom-style:dotted; border-bottom-width:1px; padding-left:4px; padding-right:4px"><p style="margin-bottom:0; margin-top:0;"><font face="Verdana"><span style="font-size:8pt;"><input id="Orientacion" name="Orientacion" type="radio" value="B" /></span></font></p><p style="margin-bottom:0; margin-top:0;"><font face="Verdana" style="cursor:help; font-size:8pt;" onMouseOver="myHint.show(2, this)" onMouseOut="myHint.hide()">B</font></td>
														<td align="center" style="border-bottom-style:dotted; border-bottom-width:1px; padding-left:4px; padding-right:4px"><p style="margin-bottom:0; margin-top:0;"><font face="Verdana"><span style="font-size:8pt;"><input id="Orientacion" name="Orientacion" type="radio" value="C" /></span></font></p><p style="margin-bottom:0; margin-top:0;"><font face="Verdana" style="cursor:help; font-size:8pt;" onMouseOver="myHint.show(3, this)" onMouseOut="myHint.hide()">C</font></td>
														<td align="center" style="border-bottom-style:dotted; border-bottom-width:1px; padding-left:4px; padding-right:4px"><p style="margin-bottom:0; margin-top:0;"><font face="Verdana"><span style="font-size:8pt;"><input id="Orientacion" name="Orientacion" type="radio" value="D" /></span></font></p><p style="margin-bottom:0; margin-top:0;"><font face="Verdana" style="cursor:help; font-size:8pt;" onMouseOver="myHint.show(4, this)" onMouseOut="myHint.hide()">D</font></td>
														<td align="center" style="border-bottom-style:dotted; border-bottom-width:1px; padding-left:4px; padding-right:4px"><p style="margin-bottom:0; margin-top:0;"><font face="Verdana"><span style="font-size:8pt;"><input id="Orientacion" name="Orientacion" type="radio" value="E" /></span></font></p><p style="margin-bottom:0; margin-top:0;"><font face="Verdana" style="cursor:help; font-size:8pt;" onMouseOver="myHint.show(5, this)" onMouseOut="myHint.hide()">E</font></td>
														<td style="border-bottom-style:dotted; border-bottom-width:1px; padding-left:4px; padding-right:4px" width="280"><p style="margin-bottom:0; margin-top:0;"><textarea cols="45" id="OrientacionObservaciones" name="OrientacionObservaciones" rows="3" style="border:1px solid #808080; color:#808080; font-family:Verdana; font-size:8pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></p></td>
													</tr>
													<tr>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="269">
															<table>
																<tr>
																	<td colspan="11"><font face="Verdana" style="font-size: 8pt; font-weight: 700; cursor:help" onmouseover="myHint.show(6, this)" onmouseout="myHint.hide()">Adaptabilidad al cambio</font></td>
																</tr>
																<tr class="FormLabelNegroSinNegrita11" id="trAdaptabilidadEsp" style="display:none">
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(7, this)" onmouseout="myHint.hide()">A</td>
																	<td width="32"><input id="AdaptabilidadEsp" name="AdaptabilidadEsp" type="radio" value="A"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(8, this)" onmouseout="myHint.hide()">B</td>
																	<td width="32"><input id="AdaptabilidadEsp" name="AdaptabilidadEsp" type="radio" value="B"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(9, this)" onmouseout="myHint.hide()">C</td>
																	<td width="32"><input id="AdaptabilidadEsp" name="AdaptabilidadEsp" type="radio" value="C"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(10, this)" onmouseout="myHint.hide()">D</td>
																	<td width="32"><input id="AdaptabilidadEsp" name="AdaptabilidadEsp" type="radio" value="D"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(11, this)" onmouseout="myHint.hide()">E</td>
																	<td width="32"><input id="AdaptabilidadEsp" name="AdaptabilidadEsp" type="radio" value="E"></td>
																	<td>&nbsp;</td>
																</tr>
															</table>
														</td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><input id="Adaptabilidad" name="Adaptabilidad" type="radio" value="A"></font></p><p style="margin-top: 0; margin-bottom: 0" align="center"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(7, this)" onmouseout="myHint.hide()">A</font></p></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="Adaptabilidad" name="Adaptabilidad" type="radio" value="B"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(8, this)" onmouseout="myHint.hide()">B</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="Adaptabilidad" name="Adaptabilidad" type="radio" value="C"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(9, this)" onmouseout="myHint.hide()">C</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="Adaptabilidad" name="Adaptabilidad" type="radio" value="D"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(10, this)" onmouseout="myHint.hide()">D</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="Adaptabilidad" name="Adaptabilidad" type="radio" value="E"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(11, this)" onmouseout="myHint.hide()">E</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><textarea cols="45" id="AdaptabilidadObservaciones" name="AdaptabilidadObservaciones" rows="3" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
													</tr>
													<tr>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="269">
															<table>
																<tr>
																	<td colspan="11"><font face="Verdana" style="font-size: 8pt; font-weight: 700; cursor:help" onmouseover="myHint.show(12, this)" onmouseout="myHint.hide()">Trabajo en equipo</font></td>
																</tr>
																<tr class="FormLabelNegroSinNegrita11" id="trTrabajoEnEquipoEsp" style="display:none">
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(13, this)" onmouseout="myHint.hide()">A</td>
																	<td width="32"><input id="TrabajoEnEquipoEsp" name="TrabajoEnEquipoEsp" type="radio" value="A"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(14, this)" onmouseout="myHint.hide()">B</td>
																	<td width="32"><input id="TrabajoEnEquipoEsp" name="TrabajoEnEquipoEsp" type="radio" value="B"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(15, this)" onmouseout="myHint.hide()">C</td>
																	<td width="32"><input id="TrabajoEnEquipoEsp" name="TrabajoEnEquipoEsp" type="radio" value="C"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(16, this)" onmouseout="myHint.hide()">D</td>
																	<td width="32"><input id="TrabajoEnEquipoEsp" name="TrabajoEnEquipoEsp" type="radio" value="D"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(17, this)" onmouseout="myHint.hide()">E</td>
																	<td width="32"><input id="TrabajoEnEquipoEsp" name="TrabajoEnEquipoEsp" type="radio" value="E"></td>
																	<td>&nbsp;</td>
																</tr>
															</table>
														</td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><input id="TrabajoEnEquipo" name="TrabajoEnEquipo" type="radio" value="A"></font></p><p style="margin-top: 0; margin-bottom: 0" align="center"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(13, this)" onmouseout="myHint.hide()">A</font></p></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="TrabajoEnEquipo" name="TrabajoEnEquipo" type="radio" value="B"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(14, this)" onmouseout="myHint.hide()">B</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="TrabajoEnEquipo" name="TrabajoEnEquipo" type="radio" value="C"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(15, this)" onmouseout="myHint.hide()">C</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="TrabajoEnEquipo" name="TrabajoEnEquipo" type="radio" value="D"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(16, this)" onmouseout="myHint.hide()">D</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="TrabajoEnEquipo" name="TrabajoEnEquipo" type="radio" value="E"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(17, this)" onmouseout="myHint.hide()">E</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><textarea id="TrabajoEnEquipoObservaciones" name="TrabajoEnEquipoObservaciones" cols="45" rows="3" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
													</tr>
													<tr>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="269">
															<table>
																<tr>
																	<td colspan="11"><font face="Verdana" style="font-size: 8pt; font-weight: 700; cursor:help" onmouseover="myHint.show(18, this)" onmouseout="myHint.hide()">Orientación al cliente interno y externo</font></td>
																</tr>
																<tr class="FormLabelNegroSinNegrita11" id="trOrientacionAlClienteEsp" style="display:none">
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(19, this)" onmouseout="myHint.hide()">A</td>
																	<td width="32"><input id="OrientacionAlClienteEsp" name="OrientacionAlClienteEsp" type="radio" value="A"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(20, this)" onmouseout="myHint.hide()">B</td>
																	<td width="32"><input id="OrientacionAlClienteEsp" name="OrientacionAlClienteEsp" type="radio" value="B"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(21, this)" onmouseout="myHint.hide()">C</td>
																	<td width="32"><input id="OrientacionAlClienteEsp" name="OrientacionAlClienteEsp" type="radio" value="C"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(22, this)" onmouseout="myHint.hide()">D</td>
																	<td width="32"><input id="OrientacionAlClienteEsp" name="OrientacionAlClienteEsp" type="radio" value="D"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(23, this)" onmouseout="myHint.hide()">E</td>
																	<td width="32"><input id="OrientacionAlClienteEsp" name="OrientacionAlClienteEsp" type="radio" value="E"></td>
																	<td width="32">&nbsp;</td>
																</tr>
															</table>
														</td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><input id="OrientacionAlCliente" name="OrientacionAlCliente" type="radio" value="A"></font></p><p style="margin-top: 0; margin-bottom: 0" align="center"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(19, this)" onmouseout="myHint.hide()">A</font></p></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="OrientacionAlCliente" name="OrientacionAlCliente" type="radio" value="B"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(20, this)" onmouseout="myHint.hide()">B</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="OrientacionAlCliente" name="OrientacionAlCliente" type="radio" value="C"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(21, this)" onmouseout="myHint.hide()">C</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="OrientacionAlCliente" name="OrientacionAlCliente" type="radio" value="D"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(22, this)" onmouseout="myHint.hide()">D</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="OrientacionAlCliente" name="OrientacionAlCliente" type="radio" value="E"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(23, this)" onmouseout="myHint.hide()">E</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><textarea cols="45" id="OrientacionAlClienteObservaciones" name="OrientacionAlClienteObservaciones" rows="3" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td width="752">&nbsp;</td>
									</tr>
									<tr>
										<td width="752">
											<div align="center" id="divCompetenciasConduccion" name="divCompetenciasConduccion">
												<table border="0" id="table10" cellspacing="1" width="740">
													<tr>
														<td align="center" bgcolor="#6E96BC" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" width="268"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">COMPETENCIAS DE CONDUCCIÓN</font></td>
														<td align="center" bgcolor="#6E96BC" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" colspan="5"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">NIVELES</font></td>
														<td align="center" bgcolor="#6E96BC" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" width="286"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">EJEMPLOS</font></td>
													</tr>
													<tr>
														<td bgcolor="#808080" align="center" width="276"><p style="margin: 0 5px"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">Sólo para empleados con </font></p><p style="margin: 0 5px"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">personal a cargo.</font></td>
														<td bgcolor="#808080" align="center" colspan="5" width="160"><p style="margin: 0 5px"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">¿Cómo lo está haciendo?</font></td>
														<td bgcolor="#808080" align="center" width="280"><p style="margin: 0 5px"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">¿Qué comportamientos concretos </font></p><p style="margin: 0 5px"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">podemos observar? (solo nivel A)</font></td>
													</tr>
													<tr>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="270">
															<table>
																<tr>
																	<td colspan="11"><span style="font-weight: 700"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(24, this)" onmouseout="myHint.hide()">Liderazgo</font></span></td>
																</tr>
																<tr class="FormLabelNegroSinNegrita11" id="trLiderazgoEsp" style="display:none">
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(25, this)" onmouseout="myHint.hide()">A</td>
																	<td width="32"><input id="LiderazgoEsp" name="LiderazgoEsp" type="radio" value="A"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(26, this)" onmouseout="myHint.hide()">B</td>
																	<td width="32"><input id="LiderazgoEsp" name="LiderazgoEsp" type="radio" value="B"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(27, this)" onmouseout="myHint.hide()">C</td>
																	<td width="32"><input id="LiderazgoEsp" name="LiderazgoEsp" type="radio" value="C"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(28, this)" onmouseout="myHint.hide()">D</td>
																	<td width="32"><input id="LiderazgoEsp" name="LiderazgoEsp" type="radio" value="D"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(29, this)" onmouseout="myHint.hide()">E</td>
																	<td width="32"><input id="LiderazgoEsp" name="LiderazgoEsp" type="radio" value="E"></td>
																	<td>&nbsp;</td>
																</tr>
															</table>
														</td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><input id="Liderazgo" name="Liderazgo" type="radio" value="A"></font></p><p style="margin-top: 0; margin-bottom: 0" align="center"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(25, this)" onmouseout="myHint.hide()">A</font></p></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="Liderazgo" name="Liderazgo" type="radio" value="B"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(26, this)" onmouseout="myHint.hide()">B</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="Liderazgo" name="Liderazgo" type="radio" value="C"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(27, this)" onmouseout="myHint.hide()">C</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="Liderazgo" name="Liderazgo" type="radio" value="D"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(28, this)" onmouseout="myHint.hide()">D</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="Liderazgo" name="Liderazgo" type="radio" value="E"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(29, this)" onmouseout="myHint.hide()">E</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="280"><p style="margin-top: 0; margin-bottom: 0"><textarea cols="45" id="LiderazgoObservaciones" name="LiderazgoObservaciones" rows="3" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></p></td>
													</tr>
													<tr>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="270">
															<table>
																<tr>
																	<td colspan="11"><font face="Verdana" style="font-size: 8pt; font-weight: 700; cursor:help" onmouseover="myHint.show(30, this)" onmouseout="myHint.hide()">Capacidad de Planificación y organización</font></td>
																</tr>
																<tr class="FormLabelNegroSinNegrita11" id="trCapacidadPlanificacionEsp" style="display:none">
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(31, this)" onmouseout="myHint.hide()">A</td>
																	<td width="32"><input id="CapacidadPlanificacionEsp" name="CapacidadPlanificacionEsp" type="radio" value="A"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(32, this)" onmouseout="myHint.hide()">B</td>
																	<td width="32"><input id="CapacidadPlanificacionEsp" name="CapacidadPlanificacionEsp" type="radio" value="B"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(33, this)" onmouseout="myHint.hide()">C</td>
																	<td width="32"><input id="CapacidadPlanificacionEsp" name="CapacidadPlanificacionEsp" type="radio" value="C"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(34, this)" onmouseout="myHint.hide()">D</td>
																	<td width="32"><input id="CapacidadPlanificacionEsp" name="CapacidadPlanificacionEsp" type="radio" value="D"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(35, this)" onmouseout="myHint.hide()">E</td>
																	<td width="32"><input id="CapacidadPlanificacionEsp" name="CapacidadPlanificacionEsp" type="radio" value="E"></td>
																	<td width="32">&nbsp;</td>
																</tr>
															</table>
														</td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><input id="CapacidadPlanificacion" name="CapacidadPlanificacion" type="radio" value="A"></font></p><p style="margin-top: 0; margin-bottom: 0" align="center"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(31, this)" onmouseout="myHint.hide()">A</font></p></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="CapacidadPlanificacion" name="CapacidadPlanificacion" type="radio" value="B"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(32, this)" onmouseout="myHint.hide()">B</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="CapacidadPlanificacion" name="CapacidadPlanificacion" type="radio" value="C"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(33, this)" onmouseout="myHint.hide()">C</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="CapacidadPlanificacion" name="CapacidadPlanificacion" type="radio" value="D"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(34, this)" onmouseout="myHint.hide()">D</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="CapacidadPlanificacion" name="CapacidadPlanificacion" type="radio" value="E"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(35, this)" onmouseout="myHint.hide()">E</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="288"><textarea cols="45" id="CapacidadPlanificacionObservaciones" name="CapacidadPlanificacionObservaciones" rows="3" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
													</tr>
													<tr>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="270">
															<table>
																<tr>
																	<td colspan="11"><span style="font-weight: 700"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(36, this)" onmouseout="myHint.hide()">Pensamiento analítico</font></span></td>
																</tr>
																<tr class="FormLabelNegroSinNegrita11" id="trPensamientoAnaliticoEsp" style="display:none">
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(37, this)" onmouseout="myHint.hide()">A</td>
																	<td width="32"><input id="PensamientoAnaliticoEsp" name="PensamientoAnaliticoEsp" type="radio" value="A"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(38, this)" onmouseout="myHint.hide()">B</td>
																	<td width="32"><input id="PensamientoAnaliticoEsp" name="PensamientoAnaliticoEsp" type="radio" value="B"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(39, this)" onmouseout="myHint.hide()">C</td>
																	<td width="32"><input id="PensamientoAnaliticoEsp" name="PensamientoAnaliticoEsp" type="radio" value="C"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(40, this)" onmouseout="myHint.hide()">D</td>
																	<td width="32"><input id="PensamientoAnaliticoEsp" name="PensamientoAnaliticoEsp" type="radio" value="D"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(41, this)" onmouseout="myHint.hide()">E</td>
																	<td width="32"><input id="PensamientoAnaliticoEsp" name="PensamientoAnaliticoEsp" type="radio" value="E"></td>
																	<td>&nbsp;</td>
																</tr>
															</table>
														</td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><input id="PensamientoAnalitico" name="PensamientoAnalitico" type="radio" value="A"></font></p><p style="margin-top: 0; margin-bottom: 0" align="center"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(37, this)" onmouseout="myHint.hide()">A</font></p></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="PensamientoAnalitico" name="PensamientoAnalitico" type="radio" value="B"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(38, this)" onmouseout="myHint.hide()">B</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="PensamientoAnalitico" name="PensamientoAnalitico" type="radio" value="C"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(39, this)" onmouseout="myHint.hide()">C</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="PensamientoAnalitico" name="PensamientoAnalitico" type="radio" value="D"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(40, this)" onmouseout="myHint.hide()">D</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" align="center"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><span style="font-size: 8pt"><input id="PensamientoAnalitico" name="PensamientoAnalitico" type="radio" value="E"></span></font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(41, this)" onmouseout="myHint.hide()">E</font></td>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="288"><textarea cols="45" id="PensamientoAnaliticoObservaciones" name="PensamientoAnaliticoObservaciones" rows="3" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td width="752">&nbsp;</td>
									</tr>
									<tr>
										<td width="752">
											<div align="center">
												<table border="0" id="table11" cellspacing="1" width="740">
													<tr>
														<td align="center" bgcolor="#808080" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" width="252"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; font-weight: 700" color="#FFFFFF">EVALUACIÓN INTEGRADORA</font></p><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt; font-weight: 700" color="#FFFFFF">DE COMPETENCIAS <span id="labelAno3">año</span></font></td>
														<td align="center" bgcolor="#FFFFFF" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080">
															<table border="0" width="100%" id="table12" cellspacing="0">
																<tr>
																	<td width="20"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><input id="Competencias" name="Competencias" type="radio" value="1"></font></td>
																	<td><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt">El desarrollo de sus competencias es superior a lo requerido por el puesto</font></td>
																</tr>
																<tr>
																	<td width="20"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><input id="Competencias" name="Competencias" type="radio" value="2"></font></td>
																	<td><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt">Presenta el nivel de desarrollo de las competencias requerido para el puesto</font></td>
																</tr>
																<tr>
																	<td width="20"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana"><input id="Competencias" name="Competencias" type="radio" value="3"></font></td>
																	<td><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt">Falta desarrollar competencias para el nivel requerido para el puesto</font></td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td><hr style="background-color:#369; border:0; height:8px;"></td>
									</tr>
									<tr>
										<td>
											<table border="0" cellspacing="1" width="740">
												<tr>
													<td align="center" bgcolor="#008000" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" width="267"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">DEFINICIÓN DE COMPETENCIAS PARA <span id="labelAnoSiguiente1">año</span>.</font></td>
												</tr>
												<tr>
													<td bgcolor="#808080" align="center"><p style="margin: 0 5px"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">Teniendo en cuenta los requerimientos de puesto para el período Nov. <span id="labelAno4">año</span> - Nov. <span id="labelAnoSiguiente2">año</span>, definí el nivel de competencias que se esperará que alcance el colaborador.</font></td>
												</tr>
												<tr>
													<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="269">
														<table>
															<tr>
																<td colspan="11"><font face="Verdana" style="font-size: 8pt; font-weight: 700; cursor:help" onmouseover="myHint.show(0, this)" onmouseout="myHint.hide()">Orientación a los resultados</font></td>
															</tr>
															<tr class="FormLabelNegroSinNegrita11" id="trOrientacionFuturo" style="display:none">
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(1, this)" onmouseout="myHint.hide()">A</td>
																<td width="32"><input id="OrientacionFuturo" name="OrientacionFuturo" type="radio" value="A"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(2, this)" onmouseout="myHint.hide()">B</td>
																<td width="32"><input id="OrientacionFuturo" name="OrientacionFuturo" type="radio" value="B"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(3, this)" onmouseout="myHint.hide()">C</td>
																<td width="32"><input id="OrientacionFuturo" name="OrientacionFuturo" type="radio" value="C"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(4, this)" onmouseout="myHint.hide()">D</td>
																<td width="32"><input id="OrientacionFuturo" name="OrientacionFuturo" type="radio" value="D"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(5, this)" onmouseout="myHint.hide()">E</td>
																<td width="32"><input id="OrientacionFuturo" name="OrientacionFuturo" type="radio" value="E"></td>
																<td>&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="269">
														<table>
															<tr>
																<td colspan="11"><font face="Verdana" style="font-size: 8pt; font-weight: 700; cursor:help" onmouseover="myHint.show(6, this)" onmouseout="myHint.hide()">Adaptabilidad al cambio</font></td>
															</tr>
															<tr class="FormLabelNegroSinNegrita11" id="trAdaptabilidadFuturo" style="display:none">
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(7, this)" onmouseout="myHint.hide()">A</td>
																<td width="32"><input id="AdaptabilidadFuturo" name="AdaptabilidadFuturo" type="radio" value="A"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(8, this)" onmouseout="myHint.hide()">B</td>
																<td width="32"><input id="AdaptabilidadFuturo" name="AdaptabilidadFuturo" type="radio" value="B"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(9, this)" onmouseout="myHint.hide()">C</td>
																<td width="32"><input id="AdaptabilidadFuturo" name="AdaptabilidadFuturo" type="radio" value="C"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(10, this)" onmouseout="myHint.hide()">D</td>
																<td width="32"><input id="AdaptabilidadFuturo" name="AdaptabilidadFuturo" type="radio" value="D"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(11, this)" onmouseout="myHint.hide()">E</td>
																<td width="32"><input id="AdaptabilidadFuturo" name="AdaptabilidadFuturo" type="radio" value="E"></td>
																<td>&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="269">
														<table>
															<tr>
																<td colspan="11"><font face="Verdana" style="font-size: 8pt; font-weight: 700; cursor:help" onmouseover="myHint.show(12, this)" onmouseout="myHint.hide()">Trabajo en equipo</font></td>
															</tr>
															<tr class="FormLabelNegroSinNegrita11" id="trTrabajoEnEquipoFuturo" style="display:none">
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(13, this)" onmouseout="myHint.hide()">A</td>
																<td width="32"><input id="TrabajoEnEquipoFuturo" name="TrabajoEnEquipoFuturo" type="radio" value="A"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(14, this)" onmouseout="myHint.hide()">B</td>
																<td width="32"><input id="TrabajoEnEquipoFuturo" name="TrabajoEnEquipoFuturo" type="radio" value="B"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(15, this)" onmouseout="myHint.hide()">C</td>
																<td width="32"><input id="TrabajoEnEquipoFuturo" name="TrabajoEnEquipoFuturo" type="radio" value="C"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(16, this)" onmouseout="myHint.hide()">D</td>
																<td width="32"><input id="TrabajoEnEquipoFuturo" name="TrabajoEnEquipoFuturo" type="radio" value="D"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(17, this)" onmouseout="myHint.hide()">E</td>
																<td width="32"><input id="TrabajoEnEquipoFuturo" name="TrabajoEnEquipoFuturo" type="radio" value="E"></td>
																<td>&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="269">
														<table>
															<tr>
																<td colspan="11"><font face="Verdana" style="font-size: 8pt; font-weight: 700; cursor:help" onmouseover="myHint.show(18, this)" onmouseout="myHint.hide()">Orientación al cliente interno y externo</font></td>
															</tr>
															<tr class="FormLabelNegroSinNegrita11" id="trOrientacionAlClienteFuturo" style="display:none">
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(19, this)" onmouseout="myHint.hide()">A</td>
																<td width="32"><input id="OrientacionAlClienteFuturo" name="OrientacionAlClienteFuturo" type="radio" value="A"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(20, this)" onmouseout="myHint.hide()">B</td>
																<td width="32"><input id="OrientacionAlClienteFuturo" name="OrientacionAlClienteFuturo" type="radio" value="B"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(21, this)" onmouseout="myHint.hide()">C</td>
																<td width="32"><input id="OrientacionAlClienteFuturo" name="OrientacionAlClienteFuturo" type="radio" value="C"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(22, this)" onmouseout="myHint.hide()">D</td>
																<td width="32"><input id="OrientacionAlClienteFuturo" name="OrientacionAlClienteFuturo" type="radio" value="D"></td>
																<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(23, this)" onmouseout="myHint.hide()">E</td>
																<td width="32"><input id="OrientacionAlClienteFuturo" name="OrientacionAlClienteFuturo" type="radio" value="E"></td>
																<td width="32">&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
											<div align="center" id="divCompetenciasConduccionFuturo" name="divCompetenciasConduccionFuturo">
												<table border="0" cellspacing="1" width="740">
													<tr>
														<td align="center" bgcolor="#008000" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" width="268"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">COMPETENCIAS DE CONDUCCIÓN</font></td>
													</tr>
													<tr>
														<td bgcolor="#808080" align="center" width="276"><p style="margin: 0 5px"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">Sólo para empleados con </font></p><p style="margin: 0 5px"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">personal a cargo.</font></td>
													</tr>
													<tr>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="270">
															<table>
																<tr>
																	<td colspan="11"><span style="font-weight: 700"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(24, this)" onmouseout="myHint.hide()">Liderazgo</font></span></td>
																</tr>
																<tr class="FormLabelNegroSinNegrita11" id="trLiderazgoFuturo" style="display:none">
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(25, this)" onmouseout="myHint.hide()">A</td>
																	<td width="32"><input id="LiderazgoFuturo" name="LiderazgoFuturo" type="radio" value="A"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(26, this)" onmouseout="myHint.hide()">B</td>
																	<td width="32"><input id="LiderazgoFuturo" name="LiderazgoFuturo" type="radio" value="B"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(27, this)" onmouseout="myHint.hide()">C</td>
																	<td width="32"><input id="LiderazgoFuturo" name="LiderazgoFuturo" type="radio" value="C"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(28, this)" onmouseout="myHint.hide()">D</td>
																	<td width="32"><input id="LiderazgoFuturo" name="LiderazgoFuturo" type="radio" value="D"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(29, this)" onmouseout="myHint.hide()">E</td>
																	<td width="32"><input id="LiderazgoFuturo" name="LiderazgoFuturo" type="radio" value="E"></td>
																	<td>&nbsp;</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="270">
															<table>
																<tr>
																	<td colspan="11"><font face="Verdana" style="font-size: 8pt; font-weight: 700; cursor:help" onmouseover="myHint.show(30, this)" onmouseout="myHint.hide()">Capacidad de Planificación y organización</font></td>
																</tr>
																<tr class="FormLabelNegroSinNegrita11" id="trCapacidadPlanificacionFuturo" style="display:none">
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(31, this)" onmouseout="myHint.hide()">A</td>
																	<td width="32"><input id="CapacidadPlanificacionFuturo" name="CapacidadPlanificacionFuturo" type="radio" value="A"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(32, this)" onmouseout="myHint.hide()">B</td>
																	<td width="32"><input id="CapacidadPlanificacionFuturo" name="CapacidadPlanificacionFuturo" type="radio" value="B"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(33, this)" onmouseout="myHint.hide()">C</td>
																	<td width="32"><input id="CapacidadPlanificacionFuturo" name="CapacidadPlanificacionFuturo" type="radio" value="C"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(34, this)" onmouseout="myHint.hide()">D</td>
																	<td width="32"><input id="CapacidadPlanificacionFuturo" name="CapacidadPlanificacionFuturo" type="radio" value="D"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(35, this)" onmouseout="myHint.hide()">E</td>
																	<td width="32"><input id="CapacidadPlanificacionFuturo" name="CapacidadPlanificacionFuturo" type="radio" value="E"></td>
																	<td width="32">&nbsp;</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px" width="270">
															<table>
																<tr>
																	<td colspan="11"><span style="font-weight: 700"><font face="Verdana" style="font-size: 8pt; cursor:help" onmouseover="myHint.show(36, this)" onmouseout="myHint.hide()">Pensamiento analítico</font></span></td>
																</tr>
																<tr class="FormLabelNegroSinNegrita11" id="trPensamientoAnaliticoFuturo" style="display:none">
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(37, this)" onmouseout="myHint.hide()">A</td>
																	<td width="32"><input id="PensamientoAnaliticoFuturo" name="PensamientoAnaliticoFuturo" type="radio" value="A"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(38, this)" onmouseout="myHint.hide()">B</td>
																	<td width="32"><input id="PensamientoAnaliticoFuturo" name="PensamientoAnaliticoFuturo" type="radio" value="B"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(39, this)" onmouseout="myHint.hide()">C</td>
																	<td width="32"><input id="PensamientoAnaliticoFuturo" name="PensamientoAnaliticoFuturo" type="radio" value="C"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(40, this)" onmouseout="myHint.hide()">D</td>
																	<td width="32"><input id="PensamientoAnaliticoFuturo" name="PensamientoAnaliticoFuturo" type="radio" value="D"></td>
																	<td valign="middle" width="4" style="cursor:help" onmouseover="myHint.show(41, this)" onmouseout="myHint.hide()">E</td>
																	<td width="32"><input id="PensamientoAnaliticoFuturo" name="PensamientoAnaliticoFuturo" type="radio" value="E"></td>
																	<td>&nbsp;</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div align="center">
								<table cellspacing="0" cellpadding="0" width="740">
									<tr>
										<td width="752" style="padding-left: 4px; padding-right: 4px" height="5"></td>
									</tr>
									<tr>
										<td width="752" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#AFC2BE" bordercolor="#808080"><p style="margin-left: 6px; margin-top:0; margin-bottom:0"><b><font face="Verdana" style="font-size: 8pt">2.</font><font face="Verdana" style="font-size: 8pt" color="#336699"><a href="#" onclick="mostrarSeccion('divObjetivos');"><span style="text-decoration: none"> [+] </span></a></font><font face="Verdana" style="font-size: 8pt">Objetivos</font></b></td>
									</tr>
								</table>
							</div>
							<div id="divObjetivos" name="divObjetivos" style="display:none">
								<table>
									<tr>
										<td width="752"><p style="margin-left: 8px"><font face="Verdana" style="font-size: 8pt; font-weight: 700">DEFINICIÓN Y EVALUACIÓN DE OBJETIVOS</font></td>
									</tr>
									<tr>
										<td width="752"><p style="margin: 0 8px"><span style="font-family: Verdana; font-size: 8pt">Detallá 2 objetivos </span><font face="Verdana" size="2"><span style="font-size: 8pt; font-family: Verdana">completando todos los campos de la tabla.</span></font></p><p style="margin: 0 8px">&nbsp;</p></td>
									</tr>
									<tr>
										<td width="752">
											<div align="center">
												<table border="0" id="table13" cellspacing="1" width="740">
													<tr>
														<td align="center" bgcolor="#6E96BC" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" colspan="2"><span style="font-weight: 700"><font face="Verdana" color="#FFFFFF" size="1">DEFINICIÓN</font></span></td>
														<td align="center" bgcolor="#6E96BC" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" width="286"><p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">EVALUACIÓN</font></p></td>
													</tr>
													<tr>
														<td align="center" bgcolor="#808080" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" colspan="3"><p align="left"><span style="font-weight: 700"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">Objetivo 1</font></span></td>
													</tr>
													<tr>
														<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Descripción del objetivo</font></td>
														<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo1Descripcion" name="Objetivo1Descripcion" rows="3" validar="true" title="Descripción del objetivo" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
														<td align="center" rowspan="4" valign="top" style="padding-left: 4px; padding-right: 4px">
															<table border="0" cellpadding="0" cellspacing="1" width="292">
																<tr>
																	<td align="center" bgcolor="#008000" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="103"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">% de cumplimiento</font></td>
																	<td align="center" colspan="3" bgcolor="#008000" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">Estado</font></td>
																</tr>
																<tr>
																	<td rowspan="4" style="border-left-style: dotted; border-left-width: 1px; border-right-style: dotted; border-right-width: 1px; border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="101"><p align="center"><font face="Verdana" style="font-size: 10pt; font-weight: 700" color="#336699"><input id="porcentajeCumplimiento1" name="porcentajeCumplimiento1" size="4" title="Porcentaje de cumplimiento" type="text" validarEntero="true" /></font></td>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20"><font face="Verdana"><span style="font-size: 8pt"><input id="Objetivo1Estado" name="Objetivo1Estado" type="radio" value="A"></span></font></td>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="141" colspan="2"><font face="Verdana" style="font-size: 8pt">Alcanzado</font></td>
																</tr>
																<tr>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20"><font face="Verdana"><span style="font-size: 8pt"><input id="Objetivo1Estado" name="Objetivo1Estado" type="radio" value="B"></span></font></td>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="141" colspan="2"><font face="Verdana" style="font-size: 8pt">No alcanzado</font></td>
																</tr>
																<tr>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20"><font face="Verdana"><span style="font-size: 8pt"><input id="Objetivo1Estado" name="Objetivo1Estado" type="radio" value="C"></span></font></td>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="141" colspan="2"><font face="Verdana" style="font-size: 8pt">En proceso</font></td>
																</tr>
																<tr>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20"><font face="Verdana"><span style="font-size: 8pt"><input id="Objetivo1Estado" name="Objetivo1Estado" type="radio" value="D"></span></font></td>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="141" colspan="2"><font face="Verdana" style="font-size: 8pt">Suspendido/reformulado</font></td>
																</tr>
																<tr>
																	<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" height="10" width="264" colspan="4"></td>
																</tr>
																<tr>
																	<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="123"><a href="#" id="btnGuardarObjetivo1" name="btnGuardarObjetivo1" onClick="guardarObjetivo(1)"><img border="0" src="images/guardar_objetivo.jpg" title="Guardar Objetivo"></a></td>
																	<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="123">&nbsp;</td>
																	<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="104"><p align="right"><a href="#" id="btnModificarObjetivo1" name="btnModificarObjetivo1" onClick="modificarObjetivo(1)"><img border="0" src="images/modificar_objetivos.jpg" title="Modificar Objetivos" width="27" height="30"></a></td>
																	<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="33"><a href="#" onClick="verHistorico(1)"><img border="0" src="images/historico.jpg" title="Histórico" width="27" height="30" align="right"></a></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Resultado a obtener</font></td>
														<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo1ResultadoAObtener" name="Objetivo1ResultadoAObtener" rows="3" validar="true" title="Resultado a obtener" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
													</tr>
													<tr>
														<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Indicador</font></td>
														<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo1Indicador" name="Objetivo1Indicador" rows="3" validar="true" title="Indicador" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
													</tr>
													<tr>
														<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Plazo de ejecución</font></td>
														<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo1PlazoEjecucion" name="Objetivo1PlazoEjecucion" rows="3" validar="true" title="Plazo de ejecución" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td width="752">
											<div align="center">
												<table border="0" id="table17" cellspacing="1" width="740">
													<tr>
														<td align="center" bgcolor="#808080" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" colspan="3"><p align="left"><span style="font-weight: 700"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">Objetivo 2</font></span></td>
													</tr>
													<tr>
														<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Descripción del objetivo</font></td>
														<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo2Descripcion" name="Objetivo2Descripcion" rows="3" validar="true" title="Descripción del objetivo" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
														<td align="center" rowspan="4" valign="top" style="padding-left: 4px; padding-right: 4px;">
															<table border="0" cellpadding="0" cellspacing="1" width="292">
																<tr>
																	<td align="center" bgcolor="#008000" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="103"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">% de cumplimiento</font></td>
																	<td align="center" colspan="3" bgcolor="#008000" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">Estado</font></td>
																</tr>
																<tr>
																	<td rowspan="4" style="border-left-style: dotted; border-left-width: 1px; border-right-style: dotted; border-right-width: 1px; border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="101"><p align="center"><font face="Verdana" style="font-size: 10pt; font-weight: 700" color="#336699"><input id="porcentajeCumplimiento2" name="porcentajeCumplimiento2" size="4" title="Porcentaje de cumplimiento" type="text" validarEntero="true" /></font></td>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20"><font face="Verdana"><span style="font-size: 8pt"><input id="Objetivo2Estado" name="Objetivo2Estado" type="radio" value="A"></span></font></td>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="141" colspan="2"><font face="Verdana" style="font-size: 8pt">Alcanzado</font></td>
																</tr>
																<tr>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20"><font face="Verdana"><span style="font-size: 8pt"><input id="Objetivo2Estado" name="Objetivo2Estado" type="radio" value="B"></span></font></td>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="141" colspan="2"><font face="Verdana" style="font-size: 8pt">No alcanzado</font></td>
																</tr>
																<tr>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20"><font face="Verdana"><span style="font-size: 8pt"><input id="Objetivo2Estado" name="Objetivo2Estado" type="radio" value="C"></span></font></td>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="141" colspan="2"><font face="Verdana" style="font-size: 8pt">En proceso</font></td>
																</tr>
																<tr>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="20"><font face="Verdana"><span style="font-size: 8pt"><input id="Objetivo2Estado" name="Objetivo2Estado" type="radio" value="D"></span></font></td>
																	<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="141" colspan="2"><font face="Verdana" style="font-size: 8pt">Suspendido/reformulado</font></td>
																</tr>
																<tr>
																	<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" height="10" width="264" colspan="4"></td>
																</tr>
																<tr>
																	<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="123"><a href="#" id="btnGuardarObjetivo2" name="btnGuardarObjetivo2" onClick="guardarObjetivo(2)"><img border="0" src="images/guardar_objetivo.jpg" title="Guardar Objetivo"></a></td>
																	<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="123">&nbsp;</td>
																	<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="104"><p align="right"><a href="#" id="btnModificarObjetivo2" name="btnModificarObjetivo2" onClick="modificarObjetivo(2)"><img border="0" src="images/modificar_objetivos.jpg" title="Modificar Objetivos" width="27" height="30"></td>
																	<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="33"><a href="#" onClick="verHistorico(2)"><img border="0" src="images/historico.jpg" title="Histórico" width="27" height="30" align="right"></a></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Resultado a obtener</font></td>
														<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo2ResultadoAObtener" name="Objetivo2ResultadoAObtener" rows="3" validar="true" title="Resultado a obtener" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
													</tr>
													<tr>
														<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Indicador</font></td>
														<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo2Indicador" name="Objetivo2Indicador" rows="3" validar="true" title="Indicador" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
													</tr>
													<tr>
														<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Plazo de ejecución</font></td>
														<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo2PlazoEjecucion" name="Objetivo2PlazoEjecucion" rows="3" validar="true" title="Plazo de ejecución" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td width="752">
											<div align="center">
												<table border="0" width="740" id="table19" cellspacing="1">
													<tr>
														<td width="435" bgcolor="#808080"><p align="center"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">EVALUACIÓN INTEGRADORA DE OBJETIVOS </font><font face="Verdana" color="#FFFFFF" size="1">(PROMEDIO)</font></td>
														<td width="4">&nbsp;</td>
														<td width="102" style="border-style: dotted; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#999999"><p align="center"><font face="Verdana" style="font-size: 10pt; font-weight: 700" color="#336699"><input id="promedioEvaluacionIntegradora" maxlength="3" name="promedioEvaluacionIntegradora" size="4" title="EVALUACIÓN INTEGRADORA DE OBJETIVOS (PROMEDIO)" type="text" validarEntero="true" />&nbsp;%</font></td>
														<td>&nbsp;</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<table border="0" width="752">
												<tr>
													<td bgcolor="#008000"><font face="Verdana" style="font-size: 10pt; font-weight: 700;" color="#FFFFFF">DEFINICIÓN DE NUEVOS OBJETIVOS PARA <span id="labelAnoSiguiente3">año</span></font></td>
												</tr>
											</table>
											<table border="0" cellspacing="0" width="740">
												<tr>
													<td width="50%">
														<table border="0" cellspacing="1">
															<tr>
																<td align="center" bgcolor="#008000" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" colspan="2"><p align="left"><span style="font-weight: 700"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">Objetivo 1</font></span></td>
															</tr>
															<tr>
																<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Descripción del objetivo</font></td>
																<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo1DescripcionFuturo" name="Objetivo1DescripcionFuturo" rows="3" validar="true" title="Descripción del objetivo" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
															</tr>
															<tr>
																<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Resultado a obtener</font></td>
																<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo1ResultadoAObtenerFuturo" name="Objetivo1ResultadoAObtenerFuturo" rows="3" validar="true" title="Resultado a obtener" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
															</tr>
															<tr>
																<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Indicador</font></td>
																<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo1IndicadorFuturo" name="Objetivo1IndicadorFuturo" rows="3" validar="true" title="Indicador" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
															</tr>
															<tr>
																<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Plazo de ejecución</font></td>
																<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo1PlazoEjecucionFuturo" name="Objetivo1PlazoEjecucionFuturo" rows="3" validar="true" title="Plazo de ejecución" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
															</tr>
														</table>
													</td>
													<td width="50%">
														<table border="0" cellspacing="1">
															<tr>
																<td align="center" bgcolor="#008000" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080" colspan="2"><p align="left"><span style="font-weight: 700"><font face="Verdana" style="font-size: 8pt" color="#FFFFFF">Objetivo 2</font></span></td>
															</tr>
															<tr>
																<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Descripción del objetivo</font></td>
																<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo2DescripcionFuturo" name="Objetivo2DescripcionFuturo" rows="3" validar="true" title="Descripción del objetivo" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
															</tr>
															<tr>
																<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Resultado a obtener</font></td>
																<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo2ResultadoAObtenerFuturo" name="Objetivo2ResultadoAObtenerFuturo" rows="3" validar="true" title="Resultado a obtener" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
															</tr>
															<tr>
																<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Indicador</font></td>
																<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo2IndicadorFuturo" name="Objetivo2IndicadorFuturo" rows="3" validar="true" title="Indicador" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
															</tr>
															<tr>
																<td align="left" style="padding-left: 4px; padding-right: 4px" width="162"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Plazo de ejecución</font></td>
																<td align="center" style="padding-left: 4px; padding-right: 4px" width="258"><textarea cols="40" id="Objetivo2PlazoEjecucionFuturo" name="Objetivo2PlazoEjecucionFuturo" rows="3" validar="true" title="Plazo de ejecución" parentDiv="divObjetivos" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td width="752"></td>
									</tr>
								</table>
							</div>
							<div align="center">
								<table cellspacing="0" cellpadding="0" width="740">
									<tr>
										<td width="752" style="padding-left: 4px; padding-right: 4px" height="5"></td>
									</tr>
									<tr>
										<td width="752" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#AFC2BE" bordercolor="#808080"><p style="margin-left: 6px; margin-top:0; margin-bottom:0"><b><font face="Verdana" style="font-size: 8pt">3.</font><font face="Verdana" style="font-size: 8pt" color="#336699"><a href="#" onclick="mostrarSeccion('divCompromisosMejora');"><span style="text-decoration: none"> [+] </span></a></font><font face="Verdana" style="font-size: 8pt">Compromisos de Mejora</font></b></td>
									</tr>
								</table>
							</div>
							<div id="divCompromisosMejora" name="divCompromisosMejora" style="display:none">
								<table>
									<tr>
										<td width="752" colspan="2"><p style="margin: 0 8px" align="justify"><font face="Verdana" size="2"><span style="font-size: 8pt; font-family: Verdana">Consensuá con el evaluado un plan de trabajo para lograr mejoras en el desempeño. Incluí las actividades de coaching del jefe, autodesarrollo, capacitación, etc. que pueden facilitar su desarrollo.</span></font></p><p style="margin: 0 8px" align="justify">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="2">
											<tbody id="tableCompromisosMejora" name="tableCompromisosMejora" totItems="3">
												<tr id="trCompromisosMejora1"  name="trCompromisosMejora1">
													<td width="48" style="margin-left: 8; margin-right: 8" align="right"><span face="Verdana" style="font-size: 8pt; font-weight: 700">1.</span></td>
													<td width="696" style="margin-left: 8; margin-right: 8"><p><input id="CompromisoMejoraId1" name="CompromisoMejoraId1" type="hidden"><input id="CompromisoMejora1" name="CompromisoMejora1" size="92" type="text" value="" style="color: #808080; font-family: Verdana; font-size: 8pt; font-weight: bold; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF"></p></td>
												</tr>
												<tr id="trCompromisosMejora2"  name="trCompromisosMejora2">
													<td align="right" style="margin-left: 8; margin-right: 8"><span face="Verdana" style="font-size: 8pt; font-weight: 700">2.</span></td>
													<td style="margin-left: 8; margin-right: 8"><input id="CompromisoMejoraId2" name="CompromisoMejoraId2" type="hidden"><input id="CompromisoMejora2" name="CompromisoMejora2" size="92" type="text" value="" style="color: #808080; font-family: Verdana; font-size: 8pt; font-weight: bold; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF"></td>
												</tr>
												<tr id="trCompromisosMejora3"  name="trCompromisosMejora3">
													<td align="right" style="margin-left: 8; margin-right: 8"><span face="Verdana" style="font-size: 8pt; font-weight: 700">3.</span></td>
													<td style="margin-left: 8; margin-right: 8"><input id="CompromisoMejoraId3" name="CompromisoMejoraId3" type="hidden"><input id="CompromisoMejora3" name="CompromisoMejora3" size="92" type="text" value="" style="color: #808080; font-family: Verdana; font-size: 8pt; font-weight: bold; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF"></td>
												</tr>
											</tbody>
										</td>
									<tr>
										<td width="48" style="margin-left: 8; margin-right: 8" align="right">&nbsp;</td>
										<td width="696" style="margin-left: 8; margin-right: 8"><p><input id="btnAgregarActividad" name="btnAgregarActividad" type="button" value="Agregar Actividad" style="color: #808080; font-family: Verdana; font-size: 8pt; font-weight: bold; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF" onClick="agregarCompromisoMejora(document, -1, document.getElementById('CompromisoMejoraNuevoItem').value, true); document.getElementById('CompromisoMejoraNuevoItem').value = '';"></p></td>
									</tr>
									<tr>
										<td width="48" style="margin-left: 8; margin-right: 8" align="right">&nbsp;</td>
										<td width="696" style="margin-left: 8; margin-right: 8"><input id="CompromisoMejoraIdNuevo" name="CompromisoMejoraIdNuevo" type="hidden" value="-1"><input id="CompromisoMejoraNuevoItem" name="CompromisoMejoraNuevoItem" size="92" type="text" style="color: #808080; font-family: Verdana; font-size: 8pt; font-weight: bold; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF"></td>
									</tr>
									<tr>
										<td width="752" colspan="2"></td>
									</tr>
								</table>
							</div>
							<div align="center" id="divSeguimientoTitulo" mostrar="" name="divSeguimientoTitulo">
								<table cellspacing="0" cellpadding="0" width="740">
									<tr>
										<td width="752" style="padding-left: 4px; padding-right: 4px" height="5"></td>
									</tr>
									<tr>
										<td width="752" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#AFC2BE" bordercolor="#808080"><p style="margin-left: 6px; margin-top:0; margin-bottom:0"><b><font face="Verdana" style="font-size: 8pt">4.</font><font face="Verdana" style="font-size: 8pt" color="#336699"><a href="#" onclick="mostrarSeccion('divSeguimiento');"><span style="text-decoration: none"> [+] </span></a></font><font face="Verdana" style="font-size: 8pt">Seguimiento</font></b></td>
									</tr>
								</table>
							</div>
							<div id="divSeguimiento" name="divSeguimiento" style="display:none">
								<table>
									<tr>
										<td width="752" height="5"></td>
									</tr>
									<tr>
										<td width="752"><p style="margin-left: 8px"><font face="Verdana" style="font-size: 8pt; font-weight: 700">EVENTOS Y HECHOS ESPECIALES</font></td>
									</tr>
									<tr>
										<td width="752" height="21"><p style="margin: 0 8px"><font face="Verdana" style="font-size: 8pt">Utiliza esta planilla para ir registrando eventos y hechos especiales, tanto positivos como negativos, que te ayuden a realizar la evaluación.</font></p></td>
									</tr>
									<tr>
										<td width="752" height="5"></td>
									</tr>
									<tr>
										<td width="752" height="21">
											<div align="center">
												<table border="0" cellspacing="0" width="730">
													<tr>
														<td align="center" width="320">
															<table border="0" cellspacing="1" width="100%">
																<tr>
																	<td align="center" bgcolor="#6E96BC" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">EVENTOS POSITIVOS</font></td>
																	<td align="center" bgcolor="#6E96BC" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">FECHA</font></td>
																</tr>
																<tr>
																	<td colspan="2">
																		<tbody id="tableEventosP" name="tableEventosP">
																			<tr id="trEventoPEjemplo" name="trEventoPEjemplo" style="display:none">
																				<td style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080"><span face="Verdana" style="font-size: 8pt">111</span></td>
																				<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080"><span face="Verdana" style="font-size: 8pt">14/08/2008</span></td>
																			</tr>
																		</tbody>
																	</td>
																</tr>
															</table>
														</td>
														<td align="center"><a href="#" id="btnInsertarEvento" name="btnInsertarEvento" onClick="insertarEvento()"><img border="0" src="images/eventos.jpg" title="Insertar Evento" width="27" height="30"></td>
														<td align="center" width="320">
															<table border="0" cellspacing="1" width="100%">
																<tr>
																	<td align="center" bgcolor="#6E96BC" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">EVENTOS NEGATIVOS</font></td>
																	<td align="center" bgcolor="#6E96BC" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080"><font face="Verdana" style="font-weight: 700" color="#FFFFFF" size="1">FECHA</font></td>
																</tr>
																<tr>
																	<td colspan="2">
																		<tbody id="tableEventosN" name="tableEventosN">
																			<tr id="trEventoNEjemplo" name="trEventoNEjemplo" style="display:none">
																				<td style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080"><span face="Verdana" style="font-size: 8pt">222</span></td>
																				<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#808080"><span face="Verdana" style="font-size: 8pt">14/08/2008</span></td>
																			</tr>
																		</tbody>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<table>
								<tr>
									<td width="752" height="15" colspan="2"></td>
								</tr>
								<tr>
									<td width="744" colspan="2"><p style="margin-left: 45px"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Comentarios Evaluado</font></td>
								</tr>
								<tr>
									<td width="744" height="21" colspan="2"><p style="margin-left: 45px"><textarea cols="110" id="ComentariosEvaluado" name="ComentariosEvaluado" rows="4" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
								</tr>
								<tr>
									<td width="744" colspan="2"><p style="margin-left: 45px"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Comentarios Evaluador</font></td>
								</tr>
								<tr>
									<td width="744" height="21" colspan="2"><p style="margin-left: 45px"><textarea cols="110" id="ComentariosEvaluador" name="ComentariosEvaluador" rows="4" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
								</tr>
								<tr>
									<td width="744" colspan="2"><p style="margin-left: 45px"><font face="Verdana" style="font-size: 8pt; font-weight: 700">Comentarios Supervisor</font></td>
								</tr>
								<tr>
									<td width="744" height="21" colspan="2"><p style="margin-left: 45px"><textarea cols="110" id="ComentariosSupervisor" name="ComentariosSupervisor" rows="4" style="color: #808080; font-family: Verdana; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;" onKeyUp="resizeTextarea(this)" onMouseUp="resizeTextarea(this)"></textarea></td>
								</tr>
								<tr>
									<td width="752" height="21"><p style="margin-left: 45px"><input id="btnGuardar" name="btnGuardar" type="button" value="Guardar" style="color: #FFFFFF; font-family: Verdana; font-size: 8pt; font-weight: bold; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #C0C0C0" onClick="guardarEvaluacion()"><input id="btnMeNotifique" name="btnMeNotifique"  type="button" value="Me Notifiqué" style="color: #FFFFFF; font-family: Verdana; font-size: 8pt; font-weight: bold; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #C0C0C0" onClick="notificarEvaluacion()"></td>
									<td align="right" width="752" height="21"><p style="margin-right: 24px"><input id="btnEnviarEvaluacion" name="btnEnviarEvaluacion"  type="button" value="Enviar Evaluación" style="color: #FFFFFF; font-family: Verdana; font-size: 8pt; font-weight: bold; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #C0C0C0" onClick="enviarEvaluacion()"></td>
								</tr>
								<tr>
									<td width="180" height="21"><p style="margin-left: 45px"><img border="0" src="images/imprimir.jpg" style="cursor:pointer" title="Imprimir evaluación" onClick="imprimirEvaluacion()" /></td>
									<td width="564" height="21">&nbsp;</td>
								</tr>
								<tr>
									<td width="752" colspan="2"></td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</form>
		<script>
		<?
		// FillCombos..
		$excludeHtml = true;
		require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

		$RCwindow = "window";

		$RCfield = "Ano";
		$RCparams = array();
		$RCquery =
			"SELECT 2008 id, 2008 detalle
				 FROM DUAL
		UNION ALL
			 SELECT 2009, 2009
				 FROM DUAL
		UNION ALL
			 SELECT 2010, 2010
				 FROM DUAL
		 ORDER BY 2";
		$RCselectedItem = $ano;
		FillCombo(false);

		$RCfield = "UsuarioAEvaluar";
		$RCparams = array(":usuario" => $user, ":ano" => $ano);
		$RCquery =
			"SELECT ue_evaluado id, ue_evaluado detalle
				 FROM rrhh.hue_usuarioevaluacion
				WHERE ue_evaluado = UPPER(:usuario)
					AND ue_anoevaluacion = :ano
					AND ue_fechabaja IS NULL
		UNION ALL
			 SELECT ue_evaluado, ue_evaluado
				 FROM rrhh.hue_usuarioevaluacion
				WHERE ue_evaluador = UPPER(:usuario)
					AND ue_anoevaluacion = :ano
					AND ue_fechabaja IS NULL
		UNION ALL
			 SELECT ue_evaluado ID, ue_evaluado detalle
				 FROM rrhh.hue_usuarioevaluacion
				WHERE ue_supervisor = UPPER(:usuario)
					AND ue_anoevaluacion = :ano
					AND ue_fechabaja IS NULL
		UNION ALL
			 SELECT ue_evaluado ID, ue_evaluado detalle
				 FROM rrhh.hue_usuarioevaluacion
				WHERE SUBSTR(ue_notificacion, 1, INSTR(ue_notificacion, ';') - 1) = UPPER(:usuario)
					AND ue_anoevaluacion = :ano
					AND ue_fechabaja IS NULL
		UNION ALL
			 SELECT ue_evaluado ID, ue_evaluado detalle
				 FROM rrhh.hue_usuarioevaluacion
				WHERE SUBSTR(ue_notificacion, INSTR(ue_notificacion, ';') + 1, LENGTH(ue_notificacion)) = UPPER(:usuario)
					AND ue_anoevaluacion = :ano
					AND ue_fechabaja IS NULL
		 ORDER BY 2";
		$RCselectedItem = $user;
		FillCombo();

		if (!$esEvaluador) {
		?>
			document.getElementById('tableUsuariosAEvaluar').style.display = 'none';
		<?
		}
		?>
		cambiarUsuarioAEvaluar('<?= $user?>', <?= $ano?>);
		</script>
		<div id="msgOk" name="msgOk" style="display:none">
			<p align="center"><br>&nbsp;<br><b>Los datos se guardaron correctamente.</b></p>
		</div>
	</body>
</html>