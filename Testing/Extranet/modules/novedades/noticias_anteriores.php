<?
$folder = "/modules/novedades/";
if (isset($_REQUEST["pg"])) {
	$file = $_SERVER["DOCUMENT_ROOT"].$folder."noticia".$_REQUEST["pg"].".html";
	if (file_exists($file)) {
		require_once($_SERVER["DOCUMENT_ROOT"].$folder."noticia".$_REQUEST["pg"].".html");
		echo "<input class='btnVolver' type='button' value='' onClick='history.back(-1);' />";
	}
	else
		echo "Noticia inexistente.";
}
else {
	$arr = array(73 => array("LA PROVINCIA PONE EL ACENTO EN LA PREVENCIÓN DE ACCIDENTES LABORALES", "Diciembre 2012"),
		72 => array("COMPROMISO CON LA INCLUSIÓN LABORAL", "Noviembre 2012"),
		71 => array("PROVINCIA ART EN EL CONGRESO REGIONAL DE SEGUROS DE SANTA FE", "Noviembre 2012"),
		70 => array("NUEVO MARCO NORMATIVO DE RIESGOS DEL TRABAJO", "Octubre 2012"),
		69 => array("PROVINCIA ART SE RENUEVA EN BAHÍA BLANCA PARA SEGUIR CRECIENDO", "Octubre 2012"),
		68 => array("PROVINCIA ART, CON EL ÉNFASIS EN LA PREVENCIÓN", "Octubre 2012"),
		67 => array("NUEVO PUNTO DE ATENCIÓN EN SANTA FE", "Octubre 2012"),
		66 => array("COMPROMISO Y GESTIÓN 2012", "Octubre 2012"),
		64 => array("APOYO AL DESARROLLO DE LOS PAS DE CUYO", "Agosto 2012"),
		65 => array("PROVINCIA ART EN EXPOESTRATEGAS 2012", "Agosto 2012"),
		58 => array("PROVINCIA ART IMPULSA LA IMPLEMENTACIÓN DEL TELETRABAJO", "Julio 2012"),
		60 => array("CAPACITACIÓN ESPECIAL PARA FRIGORÍFICOS", "Julio 2012"),
		63 => array("ENCUENTRO CON INTENDENTES DEL CORREDOR PRODUCTIVO", "Julio 2012"),
		62 => array("PROVINCIA ART PRESENTÓ SU OFERTA DE SERVICIOS EN BARILOCHE", "Junio 2012"),
		59 => array("PROVINCIA ART Y PROVINCIA SEGUROS EN EL IIIº CONGRESO FEDERAL DE SEGUROS Y RESPONSABILIDAD PROFESIONAL", "Junio 2012"),
		57 => array("PROVINCIA ART COMPROMETIDA CON LA PREVENCION DE ACCIDENTES Y RIESGOS EN EL TRABAJO", "Junio 2012"),
		53 => array("LOS SERVICIOS DE PROVINCIA ART, TAMBIÉN PARA EMPRESARIOS Y PRODUCTORES CORDOBESES", "Mayo 2012"),
		56 => array("PROVINCIA ART PRESENTÓ SUS SERVICIOS EN EL 5º CONGRESO PROVINCIAL DE ATENCIÓN PRIMARIA DE LA SALUD Y 3º ENCUENTRO NACIONAL DE APS", "Mayo 2012"),
		55 => array("PROVINCIA ART PROMUEVE EL CRECIMIENTO Y LA CAPACITACIÓN DE LOS PRODUCTORES ASESORES DE SEGUROS", "Mayo 2012"),
		54 => array("PROVINCIA ART ACERCA SUS HERRAMIENTAS A LA GESTIÓN PÚBLICA", "Mayo 2012"),
		49 => array("NUEVO ESPACIO EN TANDIL", "Marzo 2012"),
		51 => array("PROVINCIA ART OFRECE SU PROPUESTA EN MENDOZA", "Marzo 2012"),
		50 => array("PROVINCIA ART ACERCA SUS SERVICIOS AL AGRO", "Marzo 2012"),
		48 => array("PROVINCIA ART EN EXPOAGRO 2012", "Marzo 2012"),
		47 => array("PROVINCIA ART AHORA TAMBIÉN EN MORÓN", "Marzo 2012"),
		46 => array("CAMBIO DE NUMERACIÓN TELEFÓNICA", "Enero 2012"),
		43 => array("NUEVA OFICINA DE ATENCIÓN COMERCIAL", "Noviembre 2011"),
		44 => array("15as. JORNADAS DE SALUD OCUPACIONAL", "Noviembre 2011"),
		41 => array("PROVINCIA ART EN EXPOESTRATEGAS 2011", "Agosto 2011"),
		34 => array("PROVINCIA ART EN LA RURAL 2011", "Julio 2011"),
		39 => array("COBERTURA DE RC PATRONAL", "Julio 2011"),
		38 => array("DONACIONES PARA VILLA LA ANGOSTURA", "Julio 2011"),
		37 => array("PROVINCIA ART CUMPLE 15 AÑOS", "Julio 2011"),
		36 => array("NUEVA HERRAMIENTA PARA ORGANISMOS PÚBLICOS", "Mayo 2011"),
		40 => array("PROVINCIA ART PRESENTE EN FISA 2011", "Marzo 2011"),
		33 => array("PRESENCIA EN EXPOAGRO 2011", "Marzo 2011"),
		35 => array("DONACIONES A LA FUNDACIÓN BANCO PROVINCIA", "Febrero 2011"),
		45 => array("PERSONAS EXPUESTAS POLÍTICAMENTE", "Enero 2011"),
		32 => array("PRESENCIA INSTITUCIONAL EN LA COSTA BONAERENSE", "Enero 2011"),
		31 => array("INAUGURACIÓN DE OFICINAS EN CÓRDOBA", "Noviembre 2010"),
		30 => array("SALUD OCUPACIONAL EN EL BICENTENARIO", "Noviembre 2010"),
		28 => array("JORNADAS DE CAPACITACIÓN PARA AGENTES INSTITORIOS", "Octubre 2010"),
		29 => array("CAPACITACIÓN SOBRE PREVENCIÓN DE RIESGOS EN TUCUMÁN", "Septiembre 2010"),
		27 => array("NUEVA OFICINA EN CÓRDOBA", "Septiembre 2010"),
		26 => array("PROVINCIA ART EN LA 7ma. EDICIÓN DEL FORO NACIONAL DEL SEGURO", "Septiembre 2010"),
		25 => array("PROVINCIA ART EN EXPOESTRATEGAS 2010", "Agosto 2010"),
		24 => array("CAPACITACIÓN EN BANCO NACIÓN", "Julio 2010"),
		21 => array("CAPACITACIÓN A AGENTES COMERCIALES", "Julio 2010"),
		22 => array("PROVINCIA ART EN CONFERENCIART.10", "Julio 2010"),
		20 => array("CONCURSO BIALET MASSÉ 2010: PRIMER PREMIO PARA PROVINCIA ART", "Abril 2010"),
		19 => array("SEMANA DE LA SALUD Y SEGURIDAD EN EL TRABAJO", "Abril 2010"),
		18 => array("EXÁMENES MÉDICOS PERIÓDICOS", "Abril 2010"),
		17 => array("PROVINCIA ART ESTUVO PRESENTE EN EXPOAGRO 2010", "Marzo 2010"),
		15 => array("PROVINCIA ART EN EXPOAGRO 2010", "Marzo 2010"),
		 1 => array("DECRETO 1694/09: MODIFICACIONES AL SISTEMA", "Noviembre 2009"),
		 2 => array("MANPOWER DISTINGUE A PROVINCIA ART", "Noviembre 2009"),
		 3 => array("FORO NACIONAL DEL SEGURO", "Septiembre 2009"),
		 4 => array("ESTRATEGAS 2009", "Agosto 2009"),
		 5 => array("“NUESTROS JUGUETES PARA NUESTROS CHICOS”", "Agosto 2009"),
		 6 => array("DONACIÓN DE MATERIAL INFORMÁTICO", "Agosto 2009"),
		 7 => array("RELEVAMIENTO DE RIESGOS LABORALES", "Julio 2009"),
		 8 => array("PROVINCIA ART EN LA TV POR CABLE", "Junio 2009"),
		 9 => array("JORNADA PARA PRODUCTORES PATAGÓNICOS", "Junio 2009"),
		10 => array("CAPACITACIÓN EN PROMOCIÓN DE LA SALUD", "Junio 2009"),
		11 => array("APOYO A TALLER PROTEGIDO", "Junio 2009"),
		12 => array("PROVINCIA ART EXPOFARMACIA", "Junio 2009"),
		13 => array("DONACIONES A UNA ESCUELA", "Junio 2009"),
		16 => array("VENTANILLA ELECTRÓNICA", "Mayo 2009"),
		14 => array("INVESTIGACIÓN EN HIGIENE INDUSTRIAL", "Abril 2009"));

?>
<table cellspacing="0" cellpadding="0">
	<tr>
		<td class="TituloSeccion" height="19">Histórico</td>
	</tr>
	<tr>
		<td height="25" class="SubtituloSeccion">Noticias Anteriores</td>
	</tr>
	<tr>
		<td width="742" class="ContenidoSeccion" valign="top">
<?
foreach ($arr as $key => $val) {
?>
	<p style="margin-bottom:0; margin-top:0;"><a href="/noticias-anteriores/<?= $key?>"><font color="#00539B">&gt;</font> <?= $val[0]?> - <?= $val[1]?></a></p>
<?
}
?>
			<p><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></p>
		</td>
	</tr>
</table>
<?
}
?>