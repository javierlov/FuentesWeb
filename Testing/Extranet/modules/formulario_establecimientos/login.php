<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


if (!isset($_SESSION["fieldError"]))
	$_SESSION["fieldError"] = "";
if (!isset($_SESSION["msgError"]))
	$_SESSION["msgError"] = "";

$cuit = "";
if (isset($_REQUEST["cuit"]))
	$cuit = substr($_REQUEST["cuit"], 0, 11);
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Provincia ART ::..</title>
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<link rel="shortcut icon" type="image/x-icon" href="../../favicoon.ico" />	
	</head>

	<body topmargin="10" background="images/fnd.jpg" link="#00539B" vlink="#00539B" alink="#00539B">
		<form action="/modules/formulario_establecimientos/validar_login.php" id="formLogin" method="post" name="formLogin">
			<table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
				<tr>
					<td align="center" valign="top">
<table border="0" width="755" height="422" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
	<tr>
		<td colspan="4" height="86" width="755">
			<p align="center"><font face="Neo Sans" style="font-size: 9pt">
			<img border="0" src="images/top.jpg"></font></td>
	</tr>
		<tr>
		<td height="10" colspan="4">
		</td>
	</tr>
	<tr>
		<td width="755" colspan="4">
			<p style="margin: 0 25px; "><span style="FONT-SIZE: 8pt">
			<font face="Trebuchet MS" color="#00539B"><b>&gt;</b> </font>
			<font face="Trebuchet MS" color="#807F84">La Resolución S.R.T. 
			463/09 modifica la Solicitud de Afiliación, el Contrato Tipo de 
			Afiliación y crea el Registro de Cumplimiento de Normas de Salud, 
			Higiene y Seguridad en el Trabajo.</font></span></p>
			<p style="margin: 0 25px; "><span style="FONT-SIZE: 8pt"><b>
			<font face="Trebuchet MS" color="#00539B">&gt;</font></b><font face="Trebuchet MS" color="#807F84"> 
			La Resolución S.R.T. 529/09 sustituye los artículos 10, 11 y 12 de 
			la Resolución S.R.T. 463/09 y establece que todo contrato con fecha 
			de inicio de vigencia o que se renueve a partir del 1 de agosto de 
			2009 debe cumplir con este nuevo marco normativo.</font></span></p>
			<p style="margin: 0 25px; ">&nbsp;</p>
			<p style="margin: 0 25px; "><font face="Trebuchet MS" color="#807F84">
			<span style="FONT-SIZE: 8pt">Las empresas deberán completar el 
			Relevamiento General de Riesgo Laborales y, en caso de corresponder, 
			el plan de regularización de los incumplimientos denunciados. Estas 
			presentaciones tienen frecuencia anual antes de la fecha de 
			renovación de la vigencia del contrato.</span></font></p>
			<p style="margin: 0 25px; "><font face="Trebuchet MS" color="#807F84">
			<span style="FONT-SIZE: 8pt"><br>
			</span></font>
			<font face="Trebuchet MS" style="font-size: 8pt" color="#807F84">En 
			caso de que el empleador no cumpliera en tiempo y forma con este 
			requerimiento, el mismo deberá ser denunciado a la Superintendencia 
			de Riesgos del Trabajo, Además, la empresa que incumpla no podrá 
			traspasarse a otra ART.<br>
			Si el empleador cuenta con más de 50 establecimientos, podrá 
			solicitar la ampliación del plazo para su presentación, para lo cual 
			deberá adjuntar el cronograma de tareas con el que dará cumplimiento 
			a sus obligaciones. Dicha solicitud se remitirá a la SRT para su 
			consideración.</font></p>
			<p style="margin: 0 25px; "><font face="Trebuchet MS" color="#807F84">
			<span style="FONT-SIZE: 8pt"><br>
			&gt; <u>
			<a target="_blank" href="./Descargables/Res_SRT_463-09.pdf">
			Ver Resolución S.R.T. 463/09 publicada en el Boletín Oficial el 15
			de Mayo de 2009.</a></u> (1.06 MB)<br>
			&gt; <u>
			<a target="_blank" href="./Descargables/Res_SRT_529-09.pdf">
			Ver Resolución S.R.T. 529/09 publicada en el Boletín Oficial el 
			26 de Mayo de 2009.</a></u> (873 KB)</span></font><p style="border: medium none; margin-left: 25px; margin-right: 25px; margin-top: 0cm; margin-bottom: .0001pt; padding: 0cm">
			<span style="font-size: 8pt; font-family: Trebuchet MS; background-image: none; background-repeat: repeat; background-attachment: scroll; background-position: 0% 0%">
			<font color="#807F84">
			&gt; </font><font color="#00539B"><u>
			<a target="_blank" href="./Descargables/Res_SRT_771-09.pdf">
			Ver Resolución S.R.T. 771/09 publicada en el Boletín Oficial el 31 de Julio de 2009.</a></u></font><font color="#807F84"> 
			(14.6 KB)</font></span></p>
			<p style="border: medium none; margin-left: 25px; margin-right: 25px; margin-top: 0cm; margin-bottom: .0001pt; padding: 0cm">
			<span style="font-size: 8pt; font-family: Trebuchet MS; background-image: none; background-repeat: repeat; background-attachment: scroll; background-position: 0% 0%">
			<font color="#807F84">
			&gt; </font><font color="#00539B"><u>
			<a target="_blank" href="./Descargables/Res_SRT_1735-09.pdf">
			Ver Resolución S.R.T. 1735/09 publicada en el Boletín Oficial el 31 de Diciembre de 2009.</a></u></font><font color="#807F84"> 
			(20 KB)</font></span></p></td>
	</tr>
	<tr>
		<td height="12" colspan="4"></td>
	</tr>
	<tr>
		<td width="753" colspan="4" height="97" align="center">
		<div id="container">
			<div class="pkg" id="container-inner">
				<div id="pagebody">
					<div class="pkg" id="pagebody-inner">
						<div id="alpha">
							<div class="pkg" id="alpha-inner">
								<div class="entry" id="post-112376792196068538">
									<div class="entry-content">
										<div class="entry-body">
												<table border="0" cellspacing="0" cellpadding="0" id="table3" width="700" height="97">
													<tr>
														<td height="18">
														<p style="margin-left: 10px"><b>
														<font face="Trebuchet MS" color="#00539B">
														&gt; RELEVAMIENTO DE 
														RIESGOS LABORALES - 
														(Acceso Exclusivo a Clientes)</font></b></td>
													</tr>
													<tr>
														<td background="images/fondo1.jpg">
														<p align="left" style="margin: 0 10px; ">
												<font color="#807F84">
												<span style="font-family: Trebuchet MS; font-size: 8pt">
												I</span></font><span style="font-family: Trebuchet MS"><font color="#807F84" style="font-size: 8pt">ngrese con su número de C.U.I.T., 
												sin puntos ni guiones, y su 
												número de contrato de afiliación 
												(ambos constan en su contrato de 
												afiliación y en la carta 
												documento de notificación) para 
												acceder al detalle de sus 
												establecimientos, el Formulario 
												de Relevamiento de Riesgos 
												Laborales y las nuevas 
												condiciones contractuales 
												reglamentadas por la 
												Superintendencia de Riesgos del 
												Trabajo en las Resolución 463/09 y 
												529/09.</font></span></td>
													</tr>
												</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</td>
	</tr>
	<tr>
		<td width="755" height="15" colspan="4">
		</td>
	</tr>
	<tr>
		<td height="20" width="354">
			<p align="right">
				<font face="Trebuchet MS" style="font-size: 10pt; font-weight:700" color="#807F84">Nº C.U.I.T.&nbsp;</font>
			</p>
		</td>
		<td width="381" height="20">
				<font face="Trebuchet MS" style="font-size: 10pt; font-weight:700" color="#807F84">
					<input id="cuit" maxlength="11" name="cuit" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080" type="text" value="<?= $cuit?>"></font></td>
		<td colspan="2" height="20">
		&nbsp;</td>
	</tr>
	<tr>
		<td height="20" width="354">
			<p align="right">
				<font face="Trebuchet MS" style="font-size: 10pt; font-weight:700" color="#807F84">Nº Contrato&nbsp; </font>
			</p>
		</td>
		<td width="381" height="25"><input id="contrato" maxlength="10" name="contrato" type="text" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080"></td>
		<td colspan="2" height="25">
		&nbsp;</td>
	</tr>
	<tr>
	<td colspan="4" valign="bottom">
			<p align="center"><font face="Neo Sans" style="font-size: 9pt"><?= $_SESSION["msgError"]?></font></p>
		</td>
	</tr>
	<tr>
		<td width="755" height="6" colspan="4">
		</td>
	</tr>
	<tr>
		<td width="354" height="28">&nbsp;</td>
		<td width="210" height="28" colspan="2"><input class="btnIngresar" name="btnIngresar" type="submit" value="" /></td>
		<td width="7" height="28">&nbsp;</td>
	</tr>
	<tr>
		<td width="755" height="14" colspan="4">
		</td>
	</tr>
	<tr>
		<td width="755" colspan="4" height="60">
		<map name="FPMap0">
		<area target="_blank" href="http://www.provinciart.com.ar/" shape="rect" coords="618, 13, 717, 47">
		</map>
		<img border="0" src="images/bottom.jpg" usemap="#FPMap0"></td>
	</tr>
	</table>
		</td>
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
</body>
</html>