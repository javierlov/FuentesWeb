<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


if (!isset($_SESSION["contrato"])) {
	header("Location: login2.php");
	exit;
}

$params = array(":contrato" => $_SESSION["contrato"]);
$sql =
	"SELECT TO_CHAR(co_vigenciahasta, 'dd-mm-yyyy')
		 FROM aco_contrato
		WHERE co_contrato = :contrato";
$fechaVigencia = valorSql($sql, "", $params);
$fechaIncumplimiento = date("01-m-Y", strtotime($fechaVigencia." +6month +5day"));
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Pagina nueva 1</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			body {scrollbar-3dlight-color:#eee; scrollbar-arrow-color:#eee; scrollbar-darkshadow-color:#fff; scrollbar-face-color:#aaa; scrollbar-highlight-color:#aaa;
						scrollbar-shadow-color:#aaa; scrollbar-track-color:#e3e3e3;}
			#divProcesando {background-color:#00539b; cursor:wait; display:none; filter:alpha(opacity = 10); height:720px; left:0; opacity:.1; position:absolute; top:0; width:100%;}
			#divProcesandoTexto {background-color:#fff; border:1px solid #808080; color:#000; cursor:wait; display:none; font-family:Trebuchet MS; left:240px; padding:5px; position:absolute;
													 top:160px;}
		</style>
		<script type="text/javascript">
			function descargarPdf() {
				document.getElementById('divProcesando').style.display = 'block';
				document.getElementById('divProcesandoTexto').style.display = 'block';
				window.location.href = 'formulario.php';
			}

			function inicial() {
				if (document.getElementById('capas').value == "76711059") {
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

	<body onLoad="inicial()" topmargin="5" link="#00539B" vlink="#00539B" alink="#00539B" bottommargin="5">
	<table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="2"><b><font face="Trebuchet MS" color="#ff0000">&gt; INFORMACIÓN IMPORTANTE</font></b></td>
		</tr>
		<tr>
		<td colspan="2">
			<p style="margin-top: 0; margin-bottom: 0">
			<font face="Trebuchet MS" color="#999999" style="font-size: 8pt">Antes 
			de la fecha en la que opera la renovación de su contrato de afiliación <font color="#FF0000">(<?= $fechaVigencia?>)</font>,
			usted debe enviar a Provincia ART el <b>Formulario de Relevamiento de Riesgos
			Laborales</b>, por cada uno de sus establecimientos y en función de la actividad de cada uno de ellos.<br>
			<b>EL INCUMPLIMIENTO DE ESTA OBLIGACIÓN LE IMPEDIRÁ TRASPASARSE DE ART Y GENERARÁ UN RECARGO MENSUAL A SU TARIFA A PARTIR DEL 
			<?= $fechaIncumplimiento?>.</b></font><p class="MsoBodyText" style="line-height: 12.0pt; border: medium none; margin-left: 0cm; margin-right: 0cm; margin-top: 0cm; margin-bottom: 6.0pt; padding: 0cm" align="left">
			<font color="#999999">
			<span style="font-size: 8pt; font-family: Trebuchet MS">Si usted cuenta 
			con más de 50 establecimientos, podrá solicitar la ampliación del plazo 
			para su presentación, para lo cual deberá adjuntar el cronograma de 
			tareas con el que dará cumplimiento a sus obligaciones. Dicha solicitud 
			se remitirá a la SRT para su consideración.</span></font></p>
			<p class="MsoBodyText" style="line-height: 12.0pt; border: medium none; margin-left: 0cm; margin-right: 0cm; margin-top: 0cm; margin-bottom: 6.0pt; padding: 0cm" align="left">
			&nbsp;</p>
			</td>
		</tr>
		<tr>
			<td width="16%" height="83" align="center" style="border-bottom: 1px dotted #999999; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
			<a href="#" onClick="descargarPdf()"><img border="0" src="images/1.jpg" width="84" height="70"></a></td>
		<td width="84%" height="83" style="border-bottom: 1px dotted #999999; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
			<p style="margin:0 10px; ">
			<font face="Trebuchet MS" color="#999999" style="font-size: 8pt">
			Consulte los establecimientos declarados ante Provincia ART. </font>
			<p style="margin:0 10px; ">
			<font face="Trebuchet MS" color="#999999" style="font-size: 8pt">
			Si fuera necesario, actualice los datos de su empresa y establecimientos, siguiendo las 
			instrucciones indicadas en el Formulario de Rectificación de Datos y Establecimientos.
			<a target="_self" href="javascript:mostrar('capa1')" onclick="mostrar('capa1');ocultar('capa2')" ondblclick="ocultar('capa1')" id="capas" onChange="inicial()">
			Ver más</a></font>		
			<div id='capa1'>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
					<p style="margin:0 10px; ">
					<font face="Trebuchet MS" color="#999999" style="font-size: 8pt">
					Puede enviar el formulario a Provincia ART Casa Central (Carlos Pellegrini 91 4º Afiliaciones - C1009ABA - Ciudad de Buenos Aires) o a nuestras delegaciones del interior.
					</font>
					<p style="margin:0 10px; ">
					<font face="Trebuchet MS" color="#999999" style="font-size: 8pt">
					Para descargar el formulario haga <u><a href="#" onClick="descargarPdf()">clic aqui.</a></u><br>
					Nota: en caso de dar de alta obras, recuerde presentar los Avisos de Obra y 
					Programas de Seguridad correspondientes.<br>
					<u><br>
					</u></font>
					<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#00539B">
					<a href="javascript:window.open('/modules/formulario_establecimientos/pop_up.php','_blank','width=480,height=250,top=100,left=25,scrollbars=yes');void(0);" shape="rect" coords="105, 241, 119, 255">
					[¿QUÉ ES UN ESTABLECIMIENTO?]</a></font><p style="margin:0 10px; ">
					&nbsp;
					</td>
				</tr>
			</table>
			</div>
					
			</td>
		</tr>
		<tr>
			<td width="100%" height="10" align="center" style="border-bottom: 1px dotted #999999; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" colspan="2"></td>
		</tr>
		<tr>
			<td width="16%" align="center" style="border-bottom: 1px dotted #999999; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><img border="0" src="images/2.jpg" width="84" height="70"></td>
			<td width="84%" height="80" style="border-bottom:1px dotted #999999; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
				<p style="margin-left: 10px; margin-right: 10px">
					<font face="Trebuchet MS" color="#999999" style="font-size: 8pt">
						Completar y enviar el <b>Formulario de Relevamiento de Riesgos Laborales</b> es fundamental para la correcta realización de las acciones de prevención durante la vigencia del contrato, así como también para evitar multas y recargos por incumplimientos.
						<a target="_self" href="javascript:mostrar('capa2')" onclick="mostrar('capa2');ocultar('capa1')" ondblclick="ocultar('capa2')" id="capas" onChange="inicial()">Ver más</a>
					</font>
					<div id='capa2'>
						<table border="0" width="564" cellspacing="0" cellpadding="0" id="table2" height="70">
							<tr>
								<td rowspan="3" width="91">
									<a target="_blank" href="/modules/formulario_establecimientos/descargables/FormularioA.pdf">
										<img border="0" src="/modules/formulario_establecimientos/images/a.jpg" width="70" height="65" align="right">
									</a>
								</td>
								<td rowspan="3" width="85">
									<p align="center">
										<a target="_blank" href="/modules/formulario_establecimientos/descargables/FormularioB.pdf">
											<img border="0" src="/modules/formulario_establecimientos/images/b.jpg" width="70" height="65">
										</a>
								</td>
								<td rowspan="3" width="71">
									<a target="_blank" href="/modules/formulario_establecimientos/descargables/FormularioC.pdf">
										<img border="0" src="/modules/formulario_establecimientos/images/c.jpg" width="65" height="65" align="left">
									</a>
								</td>
								<td rowspan="3" width="17">&nbsp;</td>
								<td height="5" width="300"></td>
							</tr>
							<tr>
								<td width="300">
									<p align="left" style="margin: 0 5px">
										<a href="establecimientos.php"><img border="0" src="/modules/formulario_establecimientos/images/consulte.jpg" width="130" height="65" align="left"></a>
								</td>
							</tr>
				<tr>
					<td height="5" width="300"></td>
				</tr>
			
		<tr>
			<td rowspan="4" colspan="5" height="60" valign="top">
			<p style="margin-left: 10px; margin-top:0; margin-bottom:0">
					<span class="307224914-12062009"><b><span style="font-size: 8pt">
					<font color="#00539B" face="Trebuchet MS">&gt;</font><font face="Trebuchet MS" color="#0000ff"> </font></span>
					<font face="Trebuchet MS" color="#808080" style="font-size: 8pt">
					CONSEJO DE IMPRESIÓN:</font></b><span style="font-size: 8pt"><font color="#808080" face="Trebuchet MS"> 
					Al elegir la opción imprimir, ajuste la orientación del papel a 
					vertical y elija como tamaño de papel Oficio/Legal.</font></span></span><p style="margin-left: 10px; margin-top:0; margin-bottom:0">
			<font face="Trebuchet MS" color="#999999" style="font-size: 8pt">
			<u>
			<a href="javascript:window.open('/modules/formulario_establecimientos/instructivo.pdf','_blank','width=480,height=350,top=100,left=25,scrollbars=yes');void(0);" shape="rect" coords="105, 241, 119, 255">
			<b>[VER INSTRUCTIVO CÓMO COMPLETAR Y PRESENTAR EL FORMULARIO]</b></a></u></font></td>
		</tr>
	</table>
	</div>
	</td>
		</tr>
		<tr>
			<td width="16%" align="center" style="border-bottom: 1px dotted #999999; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
			<img border="0" src="images/3.jpg" width="84" height="70"></td>
			<td width="84%" height="83" style="border-bottom: 1px dotted #999999; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
			<p style="margin: 0 10px">
			<font face="Trebuchet MS" style="font-size: 8pt" color="#999999">
			Para conocer las nuevas condiciones de su contrato de afiliación en virtud de las Resoluciones SRT 463/09 y 529/09,<a href="Descargables/Contrato_de_Afiliacion.pdf" target="_blank"> haga clic aquí.</a>
	 <br>
			</font>
			</td>
		</tr>
		<tr>
			<td width="100%" align="center" colspan="2" height="35">&nbsp;<p style="margin-top:0; margin-bottom:0" align="left"><font color="#999999">
			<font face="Trebuchet MS"><span style="font-size: 8pt">&gt;
			</span></font><font face="Trebuchet MS" style="font-size: 8pt"><u>
			<a href="Descargables/Res_SRT_463-09.pdf" target="_blank">
			Ver Resolución SRT 463/09 publicada en el Boletín Oficial el 15 de Mayo de 2009.</u></a>  (1.06 MB)</font><br>
			<font face="Trebuchet MS">
			<span style="font-size: 8pt">&gt; </span></font>
			<font face="Trebuchet MS" style="font-size: 8pt">
			<a href="Descargables/Res_SRT_529-09.pdf" target="_blank">
			<u>Ver Resolución SRT 529/09 publicada en el Boletín Oficial el 15 y 26 de Mayo de 2009.</u></a>  (873 KB)</font></font></p>
			<p style="margin-top:0; margin-bottom:0" align="left">
			<font color="#999999" style="font-size: 8pt">
			<span style="font-family: Trebuchet MS; background-image: none; background-repeat: repeat; background-attachment: scroll; background-position: 0% 0%">
			&gt; </span></font>
			<span style="font-family: Trebuchet MS; background-image: none; background-repeat: repeat; background-attachment: scroll; background-position: 0% 0%">
			<font color="#00539B"><u><span style="font-size: 8pt"><a href="Descargables/Res_SRT_771-09.pdf" target="_blank">Ver Resolución 
			S.R.T. 771/09 publicada en el Boletín Oficial el 31 de Julio de 2009.</a></span></u></font><font color="#999999"><span style="font-size: 8pt"> 
			(14.6 KB)</span></font></span></p>
			<p style="margin: 0 10px" align="left">&nbsp;</p>
			</td>
		</tr>
		<tr>
			<td width="100%" align="center" colspan="2">
			<p align="left"><font face="Trebuchet MS" color="#00539B"><b>&gt; DUDAS, CONSULTAS, SOLICITUD DE INFORMACIÓN</b></font></td>
		</tr>
		<tr>
			<td width="100%" align="center" colspan="2">
			<p align="left">
			<font color="#999999" face="Trebuchet MS" style="font-size: 8pt">Para 
			recibir por e-mail los formularios o la información acerca de cómo 
			completarlos o para obtener asesoramiento personalizado, usted puede 
			escribir a <a href="mailto:info@provart.com.ar">
			info@provart.com.ar</a> o comunicarse en forma gratuita al 
			Centro de Atención al Cliente 0800-333-1278 de Lunes a Viernes de 10 a 
			18 hs. mencionando como motivo de su llamado “Resolución SRT 463/09 y 529/09”. </font></td>
		</tr>
		</table>
		<div id="divProcesando">&nbsp;</div>
	<div id="divProcesandoTexto">Abriendo PDF, aguarde unos instantes por favor...</div>
	</body>
</html>