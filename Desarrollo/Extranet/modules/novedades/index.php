<?
$folder = "/modules/novedades/";
if (isset($_REQUEST["pg"])) {
	$file = $_SERVER["DOCUMENT_ROOT"].$folder."noticia".$_REQUEST["pg"].".html";
	if (file_exists($file)) {
		echo "<div class='TituloSeccion'>Novedades</div>";
		require_once($_SERVER["DOCUMENT_ROOT"].$folder."noticia".$_REQUEST["pg"].".html");
		echo "<p class='SubtituloSeccion'><span style='margin-left:8px;'><font color='#00539b'>&gt;</font><a href='/novedades'> Men� Novedades</a></span></p>";
	}
	else
		echo "Noticia inexistente.";
}
else {
		$arr = array(118 => array("FORO NACIONAL DEL SEGURO 2015", "Junio 2015"),
		116 => array("PROVINCIA ART PARTICIP� DEL CONGRESO REGIONAL DE SEGUROS", "Mayo 2015"),
		117 => array("PROVINCIA ART JUNTO A APAS NORPATAGONIA EN SUS ENCUENTROS 2015", "Mayo 2015"),
		115 => array("LA UART ABRE UN NUEVO CURSO VIRTUAL DE SU PROGRAMA PREVENIR", "Mayo 2015"),
		114 => array("PROVINCIA ART PARTICIPA DE LA 12� SEMANA DE LA SALUD Y LA SEGURIDAD EN EL TRABAJO", "Mayo 2015"),
		110 => array("PROVINCIA ART ACOMPA�� EL CRECIMIENTO DE LOS SECTORES RURALES", "Abril 2015"),
		112 => array("PROVINCIA ART CAPACIT� A SUS PREVENTORES", "Abril 2015"),
		109 => array("CICLO DE CAPACITACI�N EN LA COSTA ATL�NTICA", "Diciembre 2014"),
		108 => array("PROVINCIA ART JUNTO A ALUMNOS DEL INSTITUTO UOCRA", "Noviembre 2014"),
		107 => array("JORNADA DE PREVENCI�N PARA LA GENDARMER�A", "Octubre 2014"),
		106 => array("BASE IMPONIBLE PARA EL PAGO DE LA CUOTA DE ART", "Septiembre 2014"),
		105 => array("PROVINCIA ART EN IMPULSO PYME", "Agosto 2014"),
		104 => array("PROVINCIA ART JUNTO AL GOBIERNO DE LA PROVINCIA POR M�S SALUD EN TIGRE", "Agosto 2014"),
		103 => array("PROVINCIA ART DIJO PRESENTE EN EXPOESTRATEGAS 2014", "Agosto 2014"),
		102 => array("PROVINCIA ART JUNTO A LOS AGENTES DE RECOLECCI�N", "Julio 2014"),
		101 => array("AVANZAN LAS ACCIONES CONJUNTAS DE PROVINCIA ART Y LA UOCRA", "Julio 2014"),
		99 => array("PROVINCIA ART Y EL MINISTERIO DE PRODUCCI�N DE LA PROVINCIA ACUERDAN  IMPORTANTE BENEFICIOS PARA LAS PYMES", "Junio 2014"),
		98 => array("PROVINCIA ART EN EL FORO NACIONAL DEL SEGURO", "Junio 2014"),
		97 => array("EL GRUPO PROVINCIA ABRI� UNA NUEVA DELEGACI�N EN LA RIOJA", "Mayo 2014"),
		100 => array("PROVINCIA ART PARTICIP� DEL III� CONGRESO REGIONAL DE SEGUROS", "Mayo 2014"),	
		96 => array("PROVINCIA ART DIJO PRESENTE EN EXPOAGRO", "Marzo 2014"),
		95 => array("PROVINCIA ART REFUERZA SU PRESENCIA EN MENDOZA", "Marzo 2014"),
		94 => array("EXPOAGRO 2014: PROVINCIA ART AUSPICIA EL CAMPEONATO NACIONAL DE MOTOSIERRISTAS", "Marzo 2014"),
		93 => array("PROVINCIA ART JUNTO A LA CULTURA EN MENDOZA", "Febrero 2014"));
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
					<td class="SubtituloSeccion"><a href="/novedades/111">LA FUNDACI�N PROVINCIA ART FOMENTA LA PREVENCI�N DE LAS ADICCIONES</a></td>
				</tr>
				<tr>
					<td class="ContenidoSeccion">Con el objetivo de abordar las adicciones en el �mbito laboral, la Fundaci�n Provincia ART firm� un convenio de participaci�n conjunta con la Subsecretar�a de Salud Mental y Atenci�n a las Adicciones (SADA) de la Provincia de Buenos Aires.<br><br></td>
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