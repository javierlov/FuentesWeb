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
		<title>..:: CÓDIGO DE VALORES Y CONDUCTA | PROVINCIA A.R.T. ::..</title>
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
			valores y conductas que orientan nuestra actuación rigen nuestra 
			convivencia y nos permiten un desempeño armónico del trabajo. 
			Asimismo, fundamentan nuestra imagen de Compañía sólida y confiable.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Es 
			propósito del presente Código:</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Establecer los valores y las pautas generales que deben regir la 
			conducta de Provincia ART en el cumplimiento de sus funciones y en 
			sus relaciones comerciales y profesionales, actuando de acuerdo con 
			la legislación vigente en cada jurisdicción donde participe, y 
			considerando los recursos y características de cada región.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Establecer criterios básicos para normar el comportamiento de todo 
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
			Señalar las sanciones que puedan aplicarse a quienes infringen las 
			disposiciones del presente Código.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Este Código es aplicable a todos los miembros del Directorio, 
			Síndicos, Gerente General, Gerentes de áreas y demás empleados de la 
			Compañía. Asimismo, se procurará que los prestadores, proveedores, 
			productores, brokers, asesores y consultores de la Compañía acepten 
			los valores y conductas descriptos en este Código, a cuyo efecto se 
			les entregará un documento resumido del que acusarán recibo.</span></p>
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
			Nuestra <b>misión:</b> Brindar soluciones adecuadas de protección a 
			empleadores y trabajadores contra los riesgos laborales, 
			contribuyendo a generar un ambiente de trabajo sano, seguro y 
			productivo, facilitando el desarrollo de nuestros colaboradores y 
			asegurando la sustentabilidad económica de la Compañía.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Nuestra <b>visión:</b> Ser la aseguradora líder en riesgos del 
			trabajo medida por sus primas y por los trabajadores cubiertos, 
			recomendada por los clientes por su propuesta de valor y reconocida 
			por el desarrollo de las mejores prácticas de la actividad, 
			asegurando un resultado técnico equilibrado.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los
			<b>valores y principios de conducta</b> deben constituir la base del 
			comportamiento laboral y profesional de las personas alcanzadas por 
			este Código.</span></p>
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
					BÁSICOS</font></b></td>
				</tr>
				<tr>
					<td valign="top" width="334">
						<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2"><b>Integridad:</b> 
						Mantener un comportamiento alineado con la lealtad, la 
						diligencia y la honestidad. Promover la coherencia entre 
						las prácticas corporativas y nuestros valores.
						</font>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2"><b>Transparencia:</b> Difundir información adecuada y fiel de nuestra gestión, veraz y contrastable. Una comunicación clara, tanto interna como externamente.
						</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2"><b>Responsabilidad:</b> Asumir nuestras responsabilidades y actuar conforme a ellas, comprometiendo todas nuestras capacidades para cumplir con el objetivo propuesto.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2"><b>Seguridad:</b> Brindar condiciones de trabajo apropiadas en cuanto a salubridad y seguridad. Exigir un alto nivel de seguridad en los procesos, instalaciones y servicios, prestando especial atención a la protección de los empleados, prestadores, proveedores, asegurados y entorno local, transmitiendo este principio de actuación a toda la organización.
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
			</span><font face="Neo Sans" size="2">Nadie debe ser discriminado por razones de sexo, estado civil, edad, religión, raza, capacidad física, preferencia política, preferencia sexual, clase social.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Todo el personal que de alguna manera forme parte de la organización está obligado a cumplir con la legislación que rige o limita su área de responsabilidad. Asimismo,  con las normas y procedimientos de control interno que establezca la Compañía. En todo el personal se debe observar una conducta leal, respetuosa, diligente y honesta.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Quienes tengan a su cargo personas que les reporten tienen la obligación moral de respetarlas y protegerlas en lo pertinente.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Se prohíbe, condena y debe ser denunciado el acoso sexual por el daño moral que causa a quienes lo experimentan.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Los directivos y empleados deben abstenerse de hacer comentarios, sea en medios familiares o sociales, sobre actividades que llevan a cabo dentro de la misma, que vayan en detrimento de ésta o de los demás directivos o empleados.</font></p>
						<p>
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><font face="Neo Sans" size="2">Ningún directivo o empleado puede utilizar el nombre de Provincia ART, así como el resto de sus recursos, en actividades para su beneficio personal.</font></p>
					</td>
				</tr>
			</table>
			<p></div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<h2 style="text-align: justify; text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">
			<span style="font-family: Neo Sans; color: #00539B"><font size="2">1.3. ANTICORRUPCIÓN</font></span><font size="2" color="#00539B"><span style="font-family: Neo Sans"> 
			Y LAVADO DE ACTIVOS</span></font></h2>
			<p class="MsoNormal" style="text-align: justify; eight: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Directivos y empleados de Provincia ART deben cumplir cabalmente con 
			la normativa aplicable y las políticas y procedimientos relacionados 
			con la lucha contra la corrupción, la prevención del lavado de 
			activos y financiamiento de actividades terroristas, y del tráfico 
			de estupefacientes.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Con 
			excepción de la compensación pagada por comisiones u otras 
			compensaciones normales pagadas a productores, agentes u otro 
			intermediario en el curso ordinario de los negocios y registrados 
			como tal en los libros de la Compañía, se prohíbe al personal de 
			Provincia ART realizar pago alguno a cualquier persona o entidad, 
			con el objetivo de buscar o de retener negocios para la misma.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Bajo ninguna circunstancia la Compañía pagará comisiones 
			clandestinas, sobornos u otros pagos (excepto por la compensación 
			normal) de manera alguna, ya sea o no que dicho pago sea secreto o 
			ilegal, para obtener un beneficio para Provincia ART, sus asegurados 
			o empleados.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">No 
			está permitido ofrecer o hacer pagos directa o indirectamente a 
			funcionarios del gobierno, incluyendo empleados de empresas del 
			Estado o cualquier otro ente con participación estatal con el objeto 
			de obtener un beneficio.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h2 style="text-align: justify; text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">
			<span style="font-family: Neo Sans; color: #00539B"><font size="2">1.4.</font><span style="font-style: normal; font-variant: normal; "><font size="2">
			</font>
			</span><font size="2">INCUMPLIMIENTO DEL CÓDIGO</font></span></h2>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Todo acto que viole la normativa vigente o este Código, deberá ser 
			denunciado inmediatamente. Las denuncias en general deberán 
			presentarse ante el superior inmediato y/o ante la Gerencia de 
			Recursos Humanos. La Gerencia de Recursos Humanos evaluará la 
			situación, elaborará un dictamen con las acciones a seguir y lo 
			elevará al Comité de Conducta, quien resolverá las medidas a 
			adoptar, o si lo estima pertinente, lo eleve al Directorio para que</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			éste resuelva.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Las 
			denuncias podrán hacerse por escrito, en forma personal, por 
			teléfono llamando a las Gerencias de Recursos Humanos o Auditoría 
			Interna o por correo electrónico. La Compañía hará sus mejores 
			esfuerzos para mantener la confidencialidad del caso, salvo cuando 
			resulte contrario a la ley o a los procedimientos jurídicos 
			aplicables.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">A 
			modo de ejemplo, se consideran violaciones al Código, las siguientes 
			acciones:</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Incumplir disposiciones legales que generen sanciones por parte de 
			las autoridades, daño patrimonial, o contingencias futuras para la 
			Compañía.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Cometer acoso sexual entre personal de la Compañía.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Evidenciar intoxicación por droga o alcohol y/o tener conductas 
			inmorales en las instalaciones de la Compañía.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Discriminar, intimidar u hostigar a otra persona por causa de raza, 
			color, sexo, edad, origen, creencias, preferencia sexual, política o 
			capacidad física.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Incumplir normas de seguridad que pongan en riesgo la vida del 
			personal o los bienes de la Compañía.</span></p>
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
			Comprometer legalmente a la Compañía sin tener autorización para 
			tales fines.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Omitir o no informar con oportunidad sobre violaciones al presente 
			Código.</span></p>
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
			violaciones a las disposiciones del Código serán objeto de 
			sanciones. La severidad de las mismas estará en función de la 
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
			cualquier persona honrada y de carácter íntegro emplearía en la 
			relación con otras personas y en la administración de sus propios 
			negocios.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Actuar siempre en defensa de los mejores intereses de la Compañía, 
			manteniendo sigilo sobre los negocios y operaciones de la misma, así 
			como sobre los negocios e informaciones de sus clientes. Es 
			fundamental que sus actitudes y comportamientos sean un reflejo de 
			su integridad personal y profesional y no coloquen en riesgo su 
			seguridad financiera y patrimonial, o la de la Compañía.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Evaluar cuidadosamente situaciones que pueden caracterizar un 
			conflicto entre sus intereses y los de la Compañía, y/o conducta no 
			aceptable desde el punto de vista ético (aunque no causen pérdidas 
			concretas a la organización). En particular, NO resultan aceptables 
			las siguientes conductas:</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: #0F6FC6; font-weight:700">
			*</span><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">&nbsp;</font></span><span style="font-size: 10.0pt; font-family: Neo Sans">Mantener relaciones comerciales, en la condición de representante de 
			la Compañía, con entidades en que empleados o personas de sus 
			relaciones familiares o personales, tengan interés o participación 
			(directa o indirectamente), sin autorización del superior 
			jerárquico, en el nivel mínimo de Gerente.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<font size="2" color="#0F6FC6">
			<span style="font-family: Neo Sans; font-weight:700">*</span></font><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">&nbsp;</font></span><span style="font-size: 10.0pt; font-family: Neo Sans">Mantener relaciones comerciales particulares, de carácter habitual, 
			con clientes o proveedores. Las relaciones eventuales con clientes o 
			proveedores no están prohibidas, pero las mismas se deberán 
			comunicar previamente, por escrito, al superior jerárquico 
			inmediato.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: #0F6FC6; font-weight:700">
			*</span><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">&nbsp;</font></span><span style="font-size: 10.0pt; font-family: Neo Sans">Usar el cargo, función o informaciones sobre negocios y asuntos de 
			la compañía o de sus clientes, para influir en las decisiones que 
			puedan favorecer a intereses propios y terceras partes.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">* </font>
			</span>
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Aceptar u ofrecer, en forma directa o indirecta, favores o regalos 
			de carácter personal, que sean el resultado de relaciones con la 
			Compañía y que puedan influir en las decisiones, facilitar negocios 
			o beneficiar a terceras partes.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			<span style="color: #0F6FC6; font-weight:700">* </span>Cualquier actitud que discrimine a las personas con quienes 
			mantenemos contacto profesional, en función de color, sexo, 
			religión, origen, clase social, edad, preferencia política o 
			incapacidad física.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<font size="2" color="#0F6FC6">
			<span style="font-family: Neo Sans; font-weight:700">*</span></font><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">&nbsp;</font></span><span style="font-size: 10.0pt; font-family: Neo Sans">Contratar parientes proveedores/prestadores sin autorización del 
			superior inmediato; influir para la contratación, directa o a través 
			de terceros, de parientes, sin informar el hecho al responsable por 
			la contratación.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: #0F6FC6; font-weight:700">
			*</span><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">
			</font>
			</span>
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Usar equipos u otros recursos de la Compañía para fines particulares 
			no autorizados.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<font size="2" color="#0F6FC6">
			<span style="font-family: Neo Sans; font-weight:700">*</span></font><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">&nbsp;</font></span><span style="font-size: 10.0pt; font-family: Neo Sans">Usar para fines particulares o transferir a terceras partes las 
			tecnologías, metodologías, conocimientos y otras informaciones que 
			pertenezcan a la Compañía, o que han sido desarrolladas u obtenidas 
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
			tiempo de trabajo dedicado a la Compañía.</span></p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom: 0; margin-left:40px">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: #0F6FC6; font-weight:700">
			*</span><span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6"><font size="2">
			</font>
			</span>
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Manifestarse en nombre de la Compañía sin estar autorizado o 
			calificado para ello.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Son 
			ejemplos de conducta esperada y compatible con los valores de la 
			Compañía y la búsqueda de resultados:</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Reconocer honestamente los errores cometidos y comunicarlos 
			inmediatamente al superior jerárquico.</span></p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Cuestionar las orientaciones contrarias a los principios y valores 
			de la Compañía.</span></p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Presentar sugerencias y críticas constructivas teniendo en la mira 
			la mejora de la calidad del trabajo.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Igualdad de 
			oportunidades, no discriminación y no acoso</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los 
			integrantes de la Compañía deberán interactuar con respeto, 
			propiciando un ambiente de trabajo cómodo, saludable y seguro.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Salud, seguridad 
			y protección ambiental</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Provincia ART considera que la salud y la seguridad son tan 
			importantes como cualquier otra función y objetivo de la Compañía. 
			Por ello, los responsables de las diferentes áreas deben tomar las 
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
			la información comercial de la compañía tanto durante como después 
			de su relación laboral con Provincia ART.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Regalos, 
			invitaciones, viajes o similares</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Todo regalo que sea ofrecido al personal de la Compañía en razón del 
			cargo que ocupa, deberá ser rechazado o restituido cuando su 
			aceptación dificulte la toma de decisiones o no permita cumplir con 
			el trabajo en forma eficiente, objetiva o ética. El personal de la 
			Compañía no podrá aceptar ningún obsequio, agasajo u otro favor de 
			cualquier persona o entidad siempre que por las características del 
			mismo pueda desvirtuar o comprometer una relación comercial o 
			administrativa.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Uso y protección 
			de activos</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los 
			activos de la Compañía deberán utilizarse únicamente para llevar a 
			cabo las actividades propias de la entidad y con arreglo a la 
			normativa interna vigente.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h2 style="text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">
			<span style="font-family: Neo Sans; color: #00539B; ">
			<font size="2">2.2.</font><span style="font-style: normal; font-variant: normal; "><font size="2">&nbsp;</font></span><font size="2">MANEJO 
			DE INFORMACIÓN</font></span></h2>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans"><font size="2">&nbsp;</font></span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Calidad de la 
			información pública</font></span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: windowtext; font-weight: normal">
			Provincia ART tiene la responsabilidad de establecer una 
			comunicación efectiva con todos sus accionistas, de modo que éstos 
			dispongan de información veraz, completa, precisa, oportuna y 
			fácilmente comprensible sobre todos los aspectos sustanciales 
			relativos a la situación financiera, los resultados de sus 
			operaciones y todos los hechos relevantes que afecten o puedan 
			afectar a la Compañía.</span></h3>
			<p class="MsoNormal" style="line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Registros 
			Contables de la Compañía</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Es 
			propósito de la Compañía observar las mejores prácticas en todas las 
			cuestiones relativas a contabilidad, controles financieros, 
			información interna y tributación. No podrá haber ninguna operación 
			realizada por Provincia ART que no esté debidamente y oportunamente 
			contabilizada.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Protección de la 
			información confidencial</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">La 
			información confidencial de Provincia ART, relativa a sus 
			actividades, constituye un activo valioso. La protección de esta 
			información resulta vital para el crecimiento de la Compañía y su 
			posibilidad de competir, y toda la información de propiedad de la 
			misma debe mantenerse en estricta confidencialidad.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Contenido de la 
			Correspondencia y Conservación de los Registros</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Todos los registros y comunicaciones de Provincia ART deberán ser 
			claros, veraces, completos y exactos. </span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Respecto al correcto uso de Internet, Intranet y el correo 
			electrónico, se deberán respetar las pautas y la normativa interna 
			vigente, prohibiéndose las cadenas de salutación, acceso y 
			distribución de material obsceno o de mal gusto, o cualquier uso que 
			pudiera resultar violatorio a los principios y valores contemplados 
			en el presente Código.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h2 style="text-indent: 0cm; line-height: normal; margin-top: 0; margin-bottom: 0">
			<span style="font-family: Neo Sans; color: #00539B; ">
			<font size="2">2.3.</font><span style="font-style: normal; font-variant: normal; "><font size="2">
			</font>
			</span><font size="2">R</font></span><font size="2" color="#00539B"><span style="font-family: Neo Sans">ELACIÓN 
			CON TERCEROS</span></font></h2>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans"><font size="2">&nbsp;</font></span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Afiliados</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Todo personal de la Compañía deberá:</span></p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpFirst" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Brindar a sus clientes una atención caracterizada por la cortesía y 
			eficiencia, ofreciendo información clara, precisa y transparente.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Evitar dar tratamiento preferente por interés o sentimiento personal 
			a un cliente.</span></p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpMiddle" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">
			Evitar hacer comparaciones falsas o engañosas con productos y/o 
			servicios de la competencia.</span></p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">&nbsp;
			</p>
			<p class="MsoListParagraphCxSpLast" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0; margin-left:20px">
			<span style="font-style: normal; font-variant: normal; font-weight: 700; font-family: Neo Sans; color:#0F6FC6">
			<font size="2">&gt; </font>
			</span><span style="font-size: 10.0pt; font-family: Neo Sans">En 
			las transacciones comerciales con clientes actuales o potenciales se 
			actuará sin favoritismos o discriminación ante igualdad de 
			condiciones.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Prestadores / 
			Proveedores</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los 
			procesos de selección de prestadores y proveedores de Provincia ART 
			se desarrollarán con homogeneidad, transparencia, imparcialidad y 
			objetividad, para lo cual los integrantes de la Compañía deberán 
			aplicar criterios de calidad, rentabilidad y servicio en dichos 
			procesos, evitando la colisión de intereses personales con los de la 
			Compañía. El personal de la Compañía se abstendrán de comentar con 
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
			facultades y actuar conforme a derecho en defensa de los legítimos 
			intereses de la Compañía. Todos los tratos, trámites y relaciones 
			que en representación de la Compañía se mantengan con dependencias o 
			funcionarios gubernamentales, deberán llevarse a cabo en 
			concordancia con las leyes aplicables.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Accionistas</font></span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans; color: windowtext; font-weight: normal">
			Las relaciones con los accionistas de la Compañía deberán basarse en 
			la comunicación exacta, transparente y oportuna de informaciones que 
			les permitan acompañar las actividades y el desempeño de la misma, 
			así como en la búsqueda de resultados que produzcan impactos 
			positivos.</span></h3>
			<p class="MsoNormal" style="line-height: normal; margin-bottom: 0; margin-top:0">&nbsp;
			</p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Comunidad en 
			general</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Provincia ART está comprometida a conducir sus actividades con 
			honestidad e integridad y dando cumplimiento a toda la normativa 
			aplicable. En consecuencia, su personal no deberá, por ningún 
			motivo, realizar actos ilícitos o antiéticos, o instruir a otros 
			para hacerlo. La Compañía apoya activamente las comunidades donde 
			opera. A través de la Fundación Provincia ART provee apoyo y 
			capacitación a través de diferentes programas comunitarios.</span></p>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></h3>
			<h3 style="text-align: justify; line-height: normal; margin-top: 0; margin-bottom:0">
			<span style="font-family: Neo Sans; color: #00539B; font-weight:400"><font size="2">Competencia</font></span></h3>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Provincia ART nunca usará métodos ilegales o no éticos para obtener 
			información sobre la competencia. Se prohíbe apropiarse de 
			información de terceros, poseer información secreta obtenida sin el 
			consentimiento del dueño, o a causar tales divulgaciones por parte 
			de empleados actuales o pasados de otras compañías.</span></p>
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
			personal de Provincia ART tiene la obligación de conducirse de modo 
			ético, y de actuar a favor de los intereses de la Compañía, 
			intentando evitar situaciones que presenten un conflicto real o 
			potencial entre sus intereses privados y los de la misma. Los 
			directivos y empleados de la Compañía deben abstenerse de tener 
			intereses o inversiones que les permita tener una influencia 
			significativa en negocios de competidores, prestadores o proveedores 
			de bienes y/o servicios. </span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">
			Provincia ART deberá abstenerse de hacer operaciones de compra venta 
			(y contratación de servicios personales y de consultoría) con 
			compañías que sean de propiedad de familiares en primero y segundo 
			grado sanguíneo o político (cónyuge, padres, hijos, hermanos, primos 
			hermanos, sobrinos, etc.) de directivos y/o empleados.</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">&nbsp;</span></p>
			<p class="MsoNormal" style="text-align: justify; line-height: normal; margin-bottom: 0; margin-top:0">
			<span style="font-size: 10.0pt; font-family: Neo Sans">Los 
			directivos y empleados de Provincia ART deben abstenerse de tener 
			trabajando bajo su dependencia a parientes o familiares, salvo en 
			los casos autorizados por escrito por la Gerencia de Recursos 
			Humanos. Asimismo, los empleados no podrán realizar tareas, trabajos 
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