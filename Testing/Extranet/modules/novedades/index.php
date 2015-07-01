<?
$folder = "/modules/novedades/";
if (isset($_REQUEST["pg"])) {
	$file = $_SERVER["DOCUMENT_ROOT"].$folder."noticia".$_REQUEST["pg"].".html";
	if (file_exists($file)) {
		echo "<div class='TituloSeccion'>Novedades</div>";
		require_once($_SERVER["DOCUMENT_ROOT"].$folder."noticia".$_REQUEST["pg"].".html");
		echo "<p class='SubtituloSeccion'><span style='margin-left:8px;'><font color='#00539b'>&gt;</font><a href='/novedades'> Menú Novedades</a></span></p>";
	}
	else
		echo "Noticia inexistente.";
}
else {
	$arr = array(92 => array("PROVINCIA ART Y LA FUNDACIÓN UOCRA JUNTOS POR LA SALUD", "Diciembre 2013"),
		91 => array("JORNADAS DE CAPACITACIÓN PARA LA PREVENCIÓN DE RIESGOS EN LAS LOCALIDADES COSTERAS", "Diciembre 2013"),
		90 => array("EL BANCO DE CHUBUT Y EL GRUPO PROVINCIA AMPLÍAN SERVICIOS CONJUNTOS", "Noviembre 2013"),
		89 => array("JUNTO AL GOBIERNO DE MENDOZA, POR MÁS SEGURIDAD Y SALUD EN EL TRABAJO", "Octubre 2013"),
		88 => array("NUEVAS OFICINAS EN NEUQUÉN", "Octubre 2013"),
		87 => array("JUNTO A LOS MUNICIPIOS EN LA PREVENCIÓN DE ACCIDENTES", "Septiembre 2013"),
		86 => array("JORNADAS DE CONDUCCIÓN SEGURA", "Septiembre 2013"),
		23 => array("TOPES MÁXIMOS Y MÍNIMOS", "Septiembre 2013"),
		85 => array("GRAN CANTIDAD DE PARTICIPANTES EN EL STAND DE PROVINCIA", "Agosto 2013"),
		84 => array("PROVINCIA ART CONTINÚA PROMOVIENDO LA CULTURA DE LA PREVENCIÓN", "Julio 2013"),
		82 => array("PROVINCIA ART EN EL FORO NACIONAL DEL SEGURO", "Junio 2013"),
		81 => array("EN PROVINCIA ART LA PROFESIONALIDAD ESTÁ GARANTIZADA", "Junio 2013"),
		80 => array("PROVINCIA ART APUESTA AL FUTURO", "Junio 2013"),
		79 => array("PROVINCIA ART JUNTO A ORGANIZACIONES DEL TERCER SECTOR", "Mayo 2013"),
		78 => array("PROVINCIA ART PARTICIPARÁ DEL CONGRESO REGIONAL DE SEGUROS EN MENDOZA", "Mayo 2013"),
		77 => array("PROVINCIA ART RECONOCIDA POR SU LABOR EN LA PREVENCIÓN DE ACCIDENTES", "Abril 2013"),
		75 => array("SANTIAGO MONTOYA Y NICOLÁS SCIOLI PRESENTARON LA NUEVA IDENTIDAD", "Marzo 2013"),
		74 => array("PROVINCIA ART PRESENTE EN EXPOAGRO", "Marzo 2013"));
?>

<table cellspacing="0" cellpadding="0">
	<tr>
		<td class="TituloSeccion" height="19">Novedades</td>
	</tr>
	<tr>
		<td class="ContenidoSeccion" valign="top" height="75"><br>
			<table cellpadding="0" cellspacing="0" bgcolor="#E1E2E2">
				<tr>
					<td rowspan="6" width="10">&nbsp;</td>
					<td rowspan="6"><img src="/modules/novedades/images/principal.jpg"></td>
					<td class="SubtituloSeccion">&nbsp;</td>
				</tr>
				<tr>
					<td class="SubtituloSeccion"><a href="/novedades/83">CLAVES PARA EL DESARROLLO DE LOS SECTORES PRODUCTIVOS</a></td>
				</tr>
				<tr>
					<td class="ContenidoSeccion">Provincia ART presentó en Córdoba la jornada de actualización “Claves para el desarrollo de los sectores productivos”. En el encuentro organizado junto al Grupo Provincia y con el apoyo de la Bolsa de Comercio de Córdoba.<br><br></td>
				</tr>
				<tr>
					<td class="ContenidoSeccion">&nbsp;</td>
				</tr>
			</table><br>
		</td>
	</tr>
	<tr>
		<td height="25" class="SubtituloSeccion">Ultimas Noticias</td>
	</tr>
	<tr>
		<td width="742" class="ContenidoSeccion" valign="top">
<?
foreach ($arr as $key => $val) {
?>
	<p style="margin-bottom:0; margin-top:0;"><a href="/novedades/<?= $key?>"><font color="#00539b">&gt;</font> <?= $val[0]?> - <?= $val[1]?></a></p>
<?
}
?>
			<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
			<p style="margin-top: 0; margin-bottom: 0"><a href="/noticias-anteriores">Ver Noticias Anteriores</a></p>		
		</td>
	</tr>
</table>
<?
}
?>