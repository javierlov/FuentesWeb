<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


SetDateFormatOracle("DD/MM/YYYY HH24:MI:SS");

$params = array(":idusuario" => GetUserID());
$sql =
	"SELECT no_codigoetica
		FROM rrhh.rno_notificaciones
	 WHERE no_idusuario = :idusuario";
$fechaAceptacion = ValorSql($sql, "", $params);
?>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns="http://www.w3.org/TR/REC-html40">
	<head>
		<title>..:: C�DIGO DE VALORES Y CONDUCTA | PROVINCIA A.R.T. ::..</title>
		<script language="JavaScript">
			function disableselect(e) 
			{ 
			   return false 
			} 
			function reEnable() 
			{ 
			   return true 
			} 
			//if IE4+ 
			document.onselectstart=new Function ("return false") 
			//if NS6 
			if (window.sidebar) 
				{ 
			   document.onmousedown=disableselect 
			   document.onclick=reEnable 
			} 

			function aceptar() {
				with (document) {
					if (getElementById('check').checked)
						getElementById('iframeCodigo').src = '/modules/normas_y_manuales/corporativa/codigos/aceptar.php';
				}
			}

			function chequear(valor) {
				with (document) {
					if (valor) {
						getElementById('btnAceptar').src = '/modules/normas_y_manuales/corporativa/codigos/images/aceptar.jpg';
						getElementById('btnAceptar').style.cursor = 'hand';
					}
					else {
						getElementById('btnAceptar').src = '/modules/normas_y_manuales/corporativa/codigos/images/aceptar_deshabilitado.jpg';
						getElementById('btnAceptar').style.cursor = 'pointer';
					}
				}
			}

			function recargar() {
				if ( (document.getElementById('notificado') != null) && (window.location.href != '/modules/normas_y_manuales/corporativa/codigos/codigo.php#notificado'))
					window.location.href = '/modules/normas_y_manuales/corporativa/codigos/codigo.php#notificado';
			}

			setTimeout("recargar()");
		</script>
		<style type="text/css"> 
			body,html {
				scrollbar-face-color: #aaaaaa;
				scrollbar-highlight-color: #aaaaaa;
				scrollbar-shadow-color: #aaaaaa;
				scrollbar-3dlight-color: #eeeeee;
				scrollbar-arrow-color: #eeeeee;
				scrollbar-track-color: #e3e3e3;
				scrollbar-darkshadow-color: ffffff;
			}
		</style>
	</head>

<body>
	<iframe id="iframeCodigo" name="iframeCodigo" src="" style="display:none;"></iframe>
	<div align="center">
	<table border="0" cellpadding="2" cellspacing="0" width="770">
		<tr>
			<td colspan="2"><img src="images/top.jpg"></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td bgcolor="#808080" colspan="2">
			<span style="font-family: Neo Sans; color: white"><b><font size="2">1.</font></b><span style="font-style: normal; font-variant: normal; font-weight: 700; "><font size="2">&nbsp;</font></span><b><font size="2">ASPECTOS 
			GENERALES</font></b></span><b> </b></td>
		</tr>
		<tr>
			<td colspan="2">
			<h2 style="text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">&nbsp;
			</h2>
			<h2 style="text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">
			<span style="font-family: Neo Sans; color: #00539B"><font size="2">1.1.</font><span style="font-style: normal; font-variant: normal; "><font size="2">
			</font>
			</span><font size="2">OBJETIVOS Y ALCANCE</font></span></h2>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los 
			valores y conductas que orientan nuestra actuaci�n rigen nuestra 
			convivencia y nos permiten un desempe�o arm�nico del trabajo. 
			Asimismo, fundamentan nuestra imagen de Compa��a s�lida y confiable.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Es 
			prop�sito del presente C�digo:</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Establecer los valores y las pautas generales que deben regir la 
			conducta de Provincia ART en el cumplimiento de sus funciones y en 
			sus relaciones comerciales y profesionales, actuando de acuerdo con 
			la legislaci�n vigente en cada jurisdicci�n donde participe, y 
			considerando los recursos y caracter�sticas de cada regi�n.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Establecer criterios b�sicos para normar el comportamiento de todo 
			el personal que trabaja para Provincia ART.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Compartir estos valores con todos aquellos interesados en conocer 
			y/o relacionarse con Provincia ART.</span></p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Se�alar las sanciones que puedan aplicarse a quienes infringen las 
			disposiciones del presente C�digo.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Este C�digo es aplicable a todos los miembros del Directorio, 
			S�ndicos, Gerente General, Gerentes de �reas y dem�s empleados de la 
			Compa��a. Asimismo, se procurar� que los prestadores, proveedores, 
			productores, brokers, asesores y consultores de la Compa��a acepten 
			los valores y conductas descriptos en este C�digo, a cuyo efecto se 
			les entregar� un documento resumido del que acusar�n recibo.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-family: Neo Sans; color: #00539B"><b>
			<font size="2">1.2.</font></b><span style="font-style: normal; font-variant: normal; font-weight: 700; "><font size="2">&nbsp;</font></span><b><font size="2">VALORES 
			Y PRINCIPIOS DE CONDUCTA</font></b></span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Nuestra <b>misi�n:</b> Brindar soluciones adecuadas de protecci�n a 
			empleadores y trabajadores contra los riesgos laborales, 
			contribuyendo a generar un ambiente de trabajo sano, seguro y 
			productivo, facilitando el desarrollo de nuestros colaboradores y 
			asegurando la sustentabilidad econ�mica de la Compa��a.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Nuestra <b>visi�n:</b> Ser la aseguradora l�der en riesgos del 
			trabajo medida por sus primas y por los trabajadores cubiertos, 
			recomendada por los clientes por su propuesta de valor y reconocida 
			por el desarrollo de las mejores pr�cticas de la actividad, 
			asegurando un resultado t�cnico equilibrado.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los
			<b>valores y principios de conducta</b> deben constituir la base del 
			comportamiento laboral y profesional de las personas alcanzadas por 
			este C�digo.</span></p>
			<h2 style="text-align: justify; text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">&nbsp;
			</h2>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<div align="center">
			<table border="1" cellpadding="3" cellspacing="1" width="90%">
				<tr>
					<td width="334" align="center" bgcolor="#0F6FC6"><b>
					<font face="Neo Sans" size="2" color="#FFFFFF">VALORES</font></b></td>
					<td width="335" align="center" bgcolor="#0F6FC6"><b>
					<font face="Neo Sans" size="2" color="#FFFFFF">PRINCIPIOS 
					B�SICOS</font></b></td>
				</tr>
				<tr>
					<td valign="top" width="334">
						<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2"><b>Integridad:</b> 
						Mantener un comportamiento alineado con la lealtad, la 
						diligencia y la honestidad. Promover la coherencia entre 
						las pr�cticas corporativas y nuestros valores.
						</font>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2"><b>Transparencia:</b> Difundir informaci�n adecuada y fiel de nuestra gesti�n, veraz y contrastable. Una comunicaci�n clara, tanto interna como externamente.
						</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2"><b>Responsabilidad:</b> Asumir nuestras responsabilidades y actuar conforme a ellas, comprometiendo todas nuestras capacidades para cumplir con el objetivo propuesto.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2"><b>Seguridad:</b> Brindar condiciones de trabajo apropiadas en cuanto a salubridad y seguridad. Exigir un alto nivel de seguridad en los procesos, instalaciones y servicios, prestando especial atenci�n a la protecci�n de los empleados, prestadores, proveedores, asegurados y entorno local, transmitiendo este principio de actuaci�n a toda la organizaci�n.
						Respeto: Tratar con dignidad y respeto a todas las personas como queremos que nos traten a nosotros.</font>		
					</td>
					<td width="335">
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Reconocer la dignidad de las personas y respetar su libertad y privacidad.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Nadie debe ser discriminado por razones de sexo, estado civil, edad, religi�n, raza, capacidad f�sica, preferencia pol�tica, preferencia sexual, clase social.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Todo el personal que de alguna manera forme parte de la organizaci�n est� obligado a cumplir con la legislaci�n que rige o limita su �rea de responsabilidad. Asimismo,  con las normas y procedimientos de control interno que establezca la Compa��a. En todo el personal se debe observar una conducta leal, respetuosa, diligente y honesta.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Quienes tengan a su cargo personas que les reporten tienen la obligaci�n moral de respetarlas y protegerlas en lo pertinente.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Se proh�be, condena y debe ser denunciado el acoso sexual por el da�o moral que causa a quienes lo experimentan.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Los directivos y empleados deben abstenerse de hacer comentarios, sea en medios familiares o sociales, sobre actividades que llevan a cabo dentro de la misma, que vayan en detrimento de �sta o de los dem�s directivos o empleados.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Ning�n directivo o empleado puede utilizar el nombre de Provincia ART, as� como el resto de sus recursos, en actividades para su beneficio personal.</font></p>
					</td>
				</tr>
			</table>
			<p></div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<h2 style="text-align: justify; text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">
			<span style="font-family: Neo Sans; color: #00539B"><font size="2">1.3. ANTICORRUPCI�N</font></span><font size="2" color="#00539B"><span style="font-family: Neo Sans"> 
			Y LAVADO DE ACTIVOS</span></font></h2>
			<p class="MsoNormal" style="text-align: justify; eight: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Directivos y empleados de Provincia ART deben cumplir cabalmente con 
			la normativa aplicable y las pol�ticas y procedimientos relacionados 
			con la lucha contra la corrupci�n, la prevenci�n del lavado de 
			activos y financiamiento de actividades terroristas, y del tr�fico 
			de estupefacientes.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Con 
			excepci�n de la compensaci�n pagada por comisiones u otras 
			compensaciones normales pagadas a productores, agentes u otro 
			intermediario en el curso ordinario de los negocios y registrados 
			como tal en los libros de la Compa��a, se proh�be al personal de 
			Provincia ART realizar pago alguno a cualquier persona o entidad, 
			con el objetivo de buscar o de retener negocios para la misma.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Bajo ninguna circunstancia la Compa��a pagar� comisiones 
			clandestinas, sobornos u otros pagos (excepto por la compensaci�n 
			normal) de manera alguna, ya sea o no que dicho pago sea secreto o 
			ilegal, para obtener un beneficio para Provincia ART, sus asegurados 
			o empleados.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">No 
			est� permitido ofrecer o hacer pagos directa o indirectamente a 
			funcionarios del gobierno, incluyendo empleados de empresas del 
			Estado o cualquier otro ente con participaci�n estatal con el objeto 
			de obtener un beneficio.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h2 style="text-align: justify; text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">
			<span style="font-family: Neo Sans; color: #00539B"><font size="2">1.4.</font><span style="font-style: normal; font-variant: normal; "><font size="2">
			</font>
			</span><font size="2">INCUMPLIMIENTO DEL C�DIGO</font></span></h2>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Todo acto que viole la normativa vigente o este C�digo, deber� ser 
			denunciado inmediatamente. Las denuncias en general deber�n 
			presentarse ante el superior inmediato y/o ante la Gerencia de 
			Recursos Humanos. La Gerencia de Recursos Humanos evaluar� la 
			situaci�n, elaborar� un dictamen con las acciones a seguir y lo 
			elevar� al Comit� de Conducta, quien resolver� las medidas a 
			adoptar, o si lo estima pertinente, lo eleve al Directorio para que</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			�ste resuelva.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Las 
			denuncias podr�n hacerse por escrito, en forma personal, por 
			tel�fono llamando a las Gerencias de Recursos Humanos o Auditor�a 
			Interna o por correo electr�nico. La Compa��a har� sus mejores 
			esfuerzos para mantener la confidencialidad del caso, salvo cuando 
			resulte contrario a la ley o a los procedimientos jur�dicos 
			aplicables.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">A 
			modo de ejemplo, se consideran violaciones al C�digo, las siguientes 
			acciones:</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Incumplir disposiciones legales que generen sanciones por parte de 
			las autoridades, da�o patrimonial, o contingencias futuras para la 
			Compa��a.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Cometer acoso sexual entre personal de la Compa��a.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Evidenciar intoxicaci�n por droga o alcohol y/o tener conductas 
			inmorales en las instalaciones de la Compa��a.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Discriminar, intimidar u hostigar a otra persona por causa de raza, 
			color, sexo, edad, origen, creencias, preferencia sexual, pol�tica o 
			capacidad f�sica.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Incumplir normas de seguridad que pongan en riesgo la vida del 
			personal o los bienes de la Compa��a.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Consumir, distribuir, transportar, vender y poseer cualquier tipo de 
			droga prohibida dentro de las instalaciones.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Comprometer legalmente a la Compa��a sin tener autorizaci�n para 
			tales fines.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Omitir o no informar con oportunidad sobre violaciones al presente 
			C�digo.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Distorsionar los registros contables y financieros.</span></p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Falsificar o alterar comprobantes.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Las 
			violaciones a las disposiciones del C�digo ser�n objeto de 
			sanciones. La severidad de las mismas estar� en funci�n de la 
			gravedad de la falta cometida.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			</td>
		</tr>
		<tr>
			<td bgcolor="#808080" colspan="2">
			<span style="font-family: Neo Sans; color: white"><b><font size="2">2.</font></b><span style="font-style: normal; font-variant: normal; font-weight: 700; "><font size="2">&nbsp;</font></span><b><font size="2">ASPECTOS 
			PARTICULARES</font></b></span><b> </b>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<h2 style="text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">&nbsp;
			</h2>
			<h2 style="text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">
			<span style="font-family: Neo Sans; color: #00539B; ">
			<font size="2">2.1. REGLAS DE CONVIVENCIA INTERNA</font></span></h2>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-family: Neo Sans; color: #00539B"><font size="2">&nbsp;</font></span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400">
			<font size="2">Integridad 
			profesional y personal</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los 
			integrantes de Provincia ART deben:</span></p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Emplear en el ejercicio de sus funciones, la misma actitud que 
			cualquier persona honrada y de car�cter �ntegro emplear�a en la 
			relaci�n con otras personas y en la administraci�n de sus propios 
			negocios.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Actuar siempre en defensa de los mejores intereses de la Compa��a, 
			manteniendo sigilo sobre los negocios y operaciones de la misma, as� 
			como sobre los negocios e informaciones de sus clientes. Es 
			fundamental que sus actitudes y comportamientos sean un reflejo de 
			su integridad personal y profesional y no coloquen en riesgo su 
			seguridad financiera y patrimonial, o la de la Compa��a.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Evaluar cuidadosamente situaciones que pueden caracterizar un 
			conflicto entre sus intereses y los de la Compa��a, y/o conducta no 
			aceptable desde el punto de vista �tico (aunque no causen p�rdidas 
			concretas a la organizaci�n). En particular, NO resultan aceptables 
			las siguientes conductas:</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: #0F6FC6; font-weight:700">
			*</span><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">&nbsp;</font></span><span style="font-size: 10.0pt; font-family: Neo Sans">Mantener relaciones comerciales, en la condici�n de representante de 
			la Compa��a, con entidades en que empleados o personas de sus 
			relaciones familiares o personales, tengan inter�s o participaci�n 
			(directa o indirectamente), sin autorizaci�n del superior 
			jer�rquico, en el nivel m�nimo de Gerente.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<font size="2" color="#0F6FC6">
			<span style="font-family: Neo Sans; font-weight:700">*</span></font><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">&nbsp;</font></span><span style="font-size: 10.0pt; font-family: Neo Sans">Mantener relaciones comerciales particulares, de car�cter habitual, 
			con clientes o proveedores. Las relaciones eventuales con clientes o 
			proveedores no est�n prohibidas, pero las mismas se deber�n 
			comunicar previamente, por escrito, al superior jer�rquico 
			inmediato.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: #0F6FC6; font-weight:700">
			*</span><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">&nbsp;</font></span><span style="font-size: 10.0pt; font-family: Neo Sans">Usar el cargo, funci�n o informaciones sobre negocios y asuntos de 
			la compa��a o de sus clientes, para influir en las decisiones que 
			puedan favorecer a intereses propios y terceras partes.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">* </font>
			</span>
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Aceptar u ofrecer, en forma directa o indirecta, favores o regalos 
			de car�cter personal, que sean el resultado de relaciones con la 
			Compa��a y que puedan influir en las decisiones, facilitar negocios 
			o beneficiar a terceras partes.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			<span style="color: #0F6FC6; font-weight:700">* </span>Cualquier actitud que discrimine a las personas con quienes 
			mantenemos contacto profesional, en funci�n de color, sexo, 
			religi�n, origen, clase social, edad, preferencia pol�tica o 
			incapacidad f�sica.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<font size="2" color="#0F6FC6">
			<span style="font-family: Neo Sans; font-weight:700">*</span></font><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">&nbsp;</font></span><span style="font-size: 10.0pt; font-family: Neo Sans">Contratar parientes proveedores/prestadores sin autorizaci�n del 
			superior inmediato; influir para la contrataci�n, directa o a trav�s 
			de terceros, de parientes, sin informar el hecho al responsable por 
			la contrataci�n.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: #0F6FC6; font-weight:700">
			*</span><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">
			</font>
			</span>
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Usar equipos u otros recursos de la Compa��a para fines particulares 
			no autorizados.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<font size="2" color="#0F6FC6">
			<span style="font-family: Neo Sans; font-weight:700">*</span></font><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">&nbsp;</font></span><span style="font-size: 10.0pt; font-family: Neo Sans">Usar para fines particulares o transferir a terceras partes las 
			tecnolog�as, metodolog�as, conocimientos y otras informaciones que 
			pertenezcan a la Compa��a, o que han sido desarrolladas u obtenidas 
			por la misma.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: #0F6FC6; font-weight:700">
			*</span><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">
			</font>
			</span>
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Involucrarse en actividades particulares que interfieran con el 
			tiempo de trabajo dedicado a la Compa��a.</span></p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: #0F6FC6; font-weight:700">
			*</span><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">
			</font>
			</span>
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Manifestarse en nombre de la Compa��a sin estar autorizado o 
			calificado para ello.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Son 
			ejemplos de conducta esperada y compatible con los valores de la 
			Compa��a y la b�squeda de resultados:</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Reconocer honestamente los errores cometidos y comunicarlos 
			inmediatamente al superior jer�rquico.</span></p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Cuestionar las orientaciones contrarias a los principios y valores 
			de la Compa��a.</span></p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Presentar sugerencias y cr�ticas constructivas teniendo en la mira 
			la mejora de la calidad del trabajo.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Igualdad de 
			oportunidades, no discriminaci�n y no acoso</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los 
			integrantes de la Compa��a deber�n interactuar con respeto, 
			propiciando un ambiente de trabajo c�modo, saludable y seguro.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Salud, seguridad 
			y protecci�n ambiental</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Provincia ART considera que la salud y la seguridad son tan 
			importantes como cualquier otra funci�n y objetivo de la Compa��a. 
			Por ello, los responsables de las diferentes �reas deben tomar las 
			acciones necesarias para asegurar que se cumplan los siguientes 
			objetivos de salud y seguridad:</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Proveer y mantener lugares de trabajo seguros y saludables.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Disponer y mantener un medio ambiente de trabajo adecuado.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Desarrollar conciencia de seguridad entre el personal.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Responsabilidad 
			e integridad</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Todo empleado de Provincia ART debe mantener la confidencialidad de 
			la informaci�n comercial de la compa��a tanto durante como despu�s 
			de su relaci�n laboral con Provincia ART.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Regalos, 
			invitaciones, viajes o similares</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Todo regalo que sea ofrecido al personal de la Compa��a en raz�n del 
			cargo que ocupa, deber� ser rechazado o restituido cuando su 
			aceptaci�n dificulte la toma de decisiones o no permita cumplir con 
			el trabajo en forma eficiente, objetiva o �tica. El personal de la 
			Compa��a no podr� aceptar ning�n obsequio, agasajo u otro favor de 
			cualquier persona o entidad siempre que por las caracter�sticas del 
			mismo pueda desvirtuar o comprometer una relaci�n comercial o 
			administrativa.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Uso y protecci�n 
			de activos</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los 
			activos de la Compa��a deber�n utilizarse �nicamente para llevar a 
			cabo las actividades propias de la entidad y con arreglo a la 
			normativa interna vigente.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h2 style="text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">
			<span style="font-family: Neo Sans; color: #00539B; ">
			<font size="2">2.2.</font><span style="font-style: normal; font-variant: normal; "><font size="2">&nbsp;</font></span><font size="2">MANEJO 
			DE INFORMACI�N</font></span></h2>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans"><font size="2">&nbsp;</font></span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Calidad de la 
			informaci�n p�blica</font></span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: windowtext; font-weight: normal">
			Provincia ART tiene la responsabilidad de establecer una 
			comunicaci�n efectiva con todos sus accionistas, de modo que �stos 
			dispongan de informaci�n veraz, completa, precisa, oportuna y 
			f�cilmente comprensible sobre todos los aspectos sustanciales 
			relativos a la situaci�n financiera, los resultados de sus 
			operaciones y todos los hechos relevantes que afecten o puedan 
			afectar a la Compa��a.</span></h3>
			<p class="MsoNormal" style="line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Registros 
			Contables de la Compa��a</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Es 
			prop�sito de la Compa��a observar las mejores pr�cticas en todas las 
			cuestiones relativas a contabilidad, controles financieros, 
			informaci�n interna y tributaci�n. No podr� haber ninguna operaci�n 
			realizada por Provincia ART que no est� debidamente y oportunamente 
			contabilizada.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Protecci�n de la 
			informaci�n confidencial</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">La 
			informaci�n confidencial de Provincia ART, relativa a sus 
			actividades, constituye un activo valioso. La protecci�n de esta 
			informaci�n resulta vital para el crecimiento de la Compa��a y su 
			posibilidad de competir, y toda la informaci�n de propiedad de la 
			misma debe mantenerse en estricta confidencialidad.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Contenido de la 
			Correspondencia y Conservaci�n de los Registros</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Todos los registros y comunicaciones de Provincia ART deber�n ser 
			claros, veraces, completos y exactos. </span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Respecto al correcto uso de Internet, Intranet y el correo 
			electr�nico, se deber�n respetar las pautas y la normativa interna 
			vigente, prohibi�ndose las cadenas de salutaci�n, acceso y 
			distribuci�n de material obsceno o de mal gusto, o cualquier uso que 
			pudiera resultar violatorio a los principios y valores contemplados 
			en el presente C�digo.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h2 style="text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">
			<span style="font-family: Neo Sans; color: #00539B; ">
			<font size="2">2.3.</font><span style="font-style: normal; font-variant: normal; "><font size="2">
			</font>
			</span><font size="2">R</font></span><font size="2" color="#00539B"><span style="font-family: Neo Sans">ELACI�N 
			CON TERCEROS</span></font></h2>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans"><font size="2">&nbsp;</font></span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Afiliados</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Todo personal de la Compa��a deber�:</span></p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Brindar a sus clientes una atenci�n caracterizada por la cortes�a y 
			eficiencia, ofreciendo informaci�n clara, precisa y transparente.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Evitar dar tratamiento preferente por inter�s o sentimiento personal 
			a un cliente.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Evitar hacer comparaciones falsas o enga�osas con productos y/o 
			servicios de la competencia.</span></p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">En 
			las transacciones comerciales con clientes actuales o potenciales se 
			actuar� sin favoritismos o discriminaci�n ante igualdad de 
			condiciones.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Prestadores / 
			Proveedores</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los 
			procesos de selecci�n de prestadores y proveedores de Provincia ART 
			se desarrollar�n con homogeneidad, transparencia, imparcialidad y 
			objetividad, para lo cual los integrantes de la Compa��a deber�n 
			aplicar criterios de calidad, rentabilidad y servicio en dichos 
			procesos, evitando la colisi�n de intereses personales con los de la 
			Compa��a. El personal de la Compa��a se abstendr�n de comentar con 
			un prestador/proveedor o con otras personas ajenas a Provincia ART, 
			los problemas o debilidades observadas en otro prestador/proveedor.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Entes 
			reguladores</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Todo el personal de Provincia ART debe colaborar en todo momento con 
			las autoridades competentes para el pleno ejercicio de sus 
			facultades y actuar conforme a derecho en defensa de los leg�timos 
			intereses de la Compa��a. Todos los tratos, tr�mites y relaciones 
			que en representaci�n de la Compa��a se mantengan con dependencias o 
			funcionarios gubernamentales, deber�n llevarse a cabo en 
			concordancia con las leyes aplicables.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Accionistas</font></span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: windowtext; font-weight: normal">
			Las relaciones con los accionistas de la Compa��a deber�n basarse en 
			la comunicaci�n exacta, transparente y oportuna de informaciones que 
			les permitan acompa�ar las actividades y el desempe�o de la misma, 
			as� como en la b�squeda de resultados que produzcan impactos 
			positivos.</span></h3>
			<p class="MsoNormal" style="line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Comunidad en 
			general</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Provincia ART est� comprometida a conducir sus actividades con 
			honestidad e integridad y dando cumplimiento a toda la normativa 
			aplicable. En consecuencia, su personal no deber�, por ning�n 
			motivo, realizar actos il�citos o anti�ticos, o instruir a otros 
			para hacerlo. La Compa��a apoya activamente las comunidades donde 
			opera. A trav�s de la Fundaci�n Provincia ART provee apoyo y 
			capacitaci�n a trav�s de diferentes programas comunitarios.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Competencia</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Provincia ART nunca usar� m�todos ilegales o no �ticos para obtener 
			informaci�n sobre la competencia. Se proh�be apropiarse de 
			informaci�n de terceros, poseer informaci�n secreta obtenida sin el 
			consentimiento del due�o, o a causar tales divulgaciones por parte 
			de empleados actuales o pasados de otras compa��as.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h2 style="text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">
			<span style="font-family: Neo Sans; color: #00539B; ">
			<font size="2">2.4.</font><span style="font-style: normal; font-variant: normal; "><font size="2">&nbsp;</font></span><font size="2">CONFLICTO 
			DE INTERESES</font></span></h2>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">El 
			personal de Provincia ART tiene la obligaci�n de conducirse de modo 
			�tico, y de actuar a favor de los intereses de la Compa��a, 
			intentando evitar situaciones que presenten un conflicto real o 
			potencial entre sus intereses privados y los de la misma. Los 
			directivos y empleados de la Compa��a deben abstenerse de tener 
			intereses o inversiones que les permita tener una influencia 
			significativa en negocios de competidores, prestadores o proveedores 
			de bienes y/o servicios. </span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Provincia ART deber� abstenerse de hacer operaciones de compra venta 
			(y contrataci�n de servicios personales y de consultor�a) con 
			compa��as que sean de propiedad de familiares en primero y segundo 
			grado sangu�neo o pol�tico (c�nyuge, padres, hijos, hermanos, primos 
			hermanos, sobrinos, etc.) de directivos y/o empleados.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los 
			directivos y empleados de Provincia ART deben abstenerse de tener 
			trabajando bajo su dependencia a parientes o familiares, salvo en 
			los casos autorizados por escrito por la Gerencia de Recursos 
			Humanos. Asimismo, los empleados no podr�n realizar tareas, trabajos 
			o prestar servicios en beneficio de empresas del sector o que 
			desarrollen actividades susceptibles de competir directa o 
			indirectamente o que pueden llegar a hacerlo con las actividades de 
			Provincia ART.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			</td>
		</tr>
<?
if ($fechaAceptacion != "") {
?>
			<tr>
				<td width="418"><p align="right"><font face="Neo Sans">&nbsp;<input checked disabled id="check" name="check" type="checkbox" value="ON"><font size="2"> Me doy por notificado</font></font>.</td>
				<td width="348"><img id="btnAceptar" name="btnAceptar" src="images/aceptar_deshabilitado.jpg"></td>
			</tr>
			<tr>
				<td align="center" colspan="2" id="notificado" style="background:#00539B; color:#FFFFFF; font-family:Neo Sans; font-size:13px;">Usted se ha notificado el <?= $fechaAceptacion?></td>
			</tr>
<?
}
else {
?>
			<tr>
				<td width="418"><p align="right"><font face="Neo Sans">&nbsp;<input id="check" name="check" type="checkbox" value="ON" onClick="chequear(this.checked)"><font size="2"> Me doy por notificado</font></font>.</td>
				<td width="348"><img id="btnAceptar" name="btnAceptar" src="images/aceptar_deshabilitado.jpg" onClick="aceptar()"></td>
			</tr>
<?
}
?>
		</table>
		</div>
	</body>
</html>