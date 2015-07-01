<?
$arrGenerales = array(
	"Anexo Sistema de Ventanilla Electr�nica (Resoluci�n S.R.T. 365/2009)" => array("descargables_web/AnexoSistemaVentanillaElectronica.pdf", ""),
	"Rectificaci�n de datos y establecimientos" => array("descargables_web/Provincia_ART_Rect_datos.pdf", ""),
	"Declaraci�n de actividad" => array("descargables_web/Provincia_ART_Declaracion_Actividad.pdf", ""),
	"Ubicaci�n de riesgo � Detalle de Establecimientos" => array("descargables_web/Provincia_ART_Ubicacion_riesgo.pdf", ""),
	"Declaraci�n jurada de Personas Expuestas Pol�ticamente (PEP)" => array("descargables_web/Provincia_ART_TE-02-F005__DDJJ_PEPs.pdf", ""),
	"Solicitud de cobertura al exterior" => array("descargables_web/Provincia_ART_Form_Sol_cobertura_exterior.pdf", ""),
	"Credencial y Bolet�n informativo" => array("descargables_web/P_ART_flyer_y_credencial.pdf", ""));

$arrPrestaciones = array(
	"Denuncia de accidente de trabajo o enfermedad profesional<br />(incluye tabla de codificaci�n seg�n Resoluci�n 1601 y 1604/07)" => array("descargables_web/Provincia_ART_denuncia.pdf", ""),
	"Solicitud de asistencia m�dica" => array("descargables_web/Provincia_ART_Solicitud_asist_medica.pdf", ""),
	"Gu�a para la Liquidaci�n de Prestaciones Dinerarias (ILT)" => array("descargables_web/Provincia_ART_Liquidacion_ILT_.pdf", ""));

$arrPrevencion = array(
	"Relevamiento General de Riesgos Laborales � Formulario A (Res. S.R.T. 463/2009)<br />Decreto 351/79: Actividades no vinculadas al agro y construcci�n" => array("descargables_web/Provincia_ART_RGRL_Formulario_A.pdf", "pdf"),
	"Relevamiento General de Riesgos Laborales � Formulario B (Res. S.R.T. 463/2009)<br />Decreto 911/96: Construcci�n" => array("descargables_web/Provincia_ART_RGRL_Formulario_B.pdf", "pdf"),
	"Relevamiento General de Riesgos Laborales � Formulario C (Res. S.R.T. 463/2009)<br />Decreto 617/97: Agro" => array("descargables_web/Provincia_ART_RGRL_Formulario_C.pdf", "pdf"),
	"Aviso de obra" => array("descargables_web/Provincia_ART_Aviso_obra.pdf", "pdf"),
	"Exposici�n a Riesgos Qu�micos, F�sicos y/o Biol�gicos" => array("descargables_web/Provincia_ART_Form_Exp_a_riesgos.pdf", "pdf"),
	"N�mina de Personal Expuesto.<br /><i>Se solicita remitir este formulario en formato digital</i>" => array("descargables_web/ProvinciaART_Nomina_personal_expuesto.xls", "xls"));
?>
<style type="text/css">
	.pieTablaDescargaFormularios {border-bottom:1px solid #676767; margin-bottom:40px; margin-top:8px;}
	.tituloDescargaFormularios {background-color:#d0d0d0; border-bottom:1px solid #676767; border-top:1px solid #676767; color:#676767; font-weight:700; padding-bottom:1px; padding-left:4px;
															padding-top:1px;}
</style>
<div class="TituloSeccion">Descarga de formularios</div>
<div class="ContenidoSeccion">
	<p style="margin-bottom:24px;">Aqu� podr� descargar e imprimir los formularios correspondientes a la gesti�n de la cobertura de Riesgos del Trabajo.</p>
	<div style="width:600px;">
		<div class="tituloDescargaFormularios">GENERALES</div>
<?
$i = 0;
foreach ($arrGenerales as $key => $val) {
	$css = "";
	if (($i % 2) != 0)
		$css = "background-color:#eaeaea;";

	$cssTexto = "";
	if (!strpos($key, "<br />"))
		$cssTexto = "position:relative; top:4px;";
?>
	<div style="<?= $css?> min-height:28px;">
		<div style="float:right; position:relative; top:6px;"><a target="_blank" href="<?= getFile(STORAGE_EXTRANET.$val[0])?>"><img align="right" border="0" src="/images/pdficon_small.gif"></a></div>
		<span style="<?= $cssTexto?>"><?= $key?></span>
	</div>
<?
	$i++;
}
?>
		<div class="pieTablaDescargaFormularios"></div>
		<div style="margin-bottom:-8px; margin-top:-40px;">
			<i>
				En forma gratuita, usted puede enviar los formularios a nuestra casilla postal del Correo Argentino<br />
				-Apartado especial N�4, Suc. N�1 Avenida de Mayo CP 1084- en un sobre de tama�o m�ximo 15x23 cm.
			</i>
		</div>
		<div class="pieTablaDescargaFormularios"></div>
		<div class="tituloDescargaFormularios">PRESTACIONES</div>
<?
$i = 0;
foreach ($arrPrestaciones as $key => $val) {
	$css = "";
	if (($i % 2) != 0)
		$css = "background-color:#eaeaea;";

	$cssTexto = "";
	if (!strpos($key, "<br />"))
		$cssTexto = "position:relative; top:4px;";
?>
	<div style="<?= $css?> min-height:28px;">
		<div style="float:right; position:relative; top:6px;"><a target="_blank" href="<?= getFile(STORAGE_EXTRANET.$val[0])?>"><img align="right" border="0" src="/images/pdficon_small.gif"></a></div>
		<span style="<?= $cssTexto?>"><?= $key?></span>
	</div>
<?
	$i++;
}
?>
		<div class="pieTablaDescargaFormularios"></div>
		<div class="tituloDescargaFormularios">PREVENCI�N</div>
<?
$i = 0;
foreach ($arrPrevencion as $key => $val) {
	$css = "";
	if (($i % 2) != 0)
		$css = "background-color:#eaeaea;";

	$cssTexto = "";
	if (!strpos($key, "<br />"))
		$cssTexto = "position:relative; top:4px;";

	if ($val[1] == "pdf")
		$img = "pdficon_small.gif";
	if ($val[1] == "xls")
		$img = "excel.png";
?>
	<div style="<?= $css?> min-height:28px;">
		<div style="float:right; position:relative; top:6px;"><a target="_blank" href="<?= getFile(STORAGE_EXTRANET.$val[0])?>"><img align="right" border="0" src="/images/<?= $img?>"></a></div>
		<span style="<?= $cssTexto?>"><?= $key?></span>
	</div>
<?
	$i++;
}
?>
		<div class="pieTablaDescargaFormularios"></div>
		<div>
			<div style="float:right; margin-right:80px; position:relative; top:-4px;">
				<a href="http://get.adobe.com/es/reader/otherversions/" target="_blank"><img border="0" src="/images/get_acrobat.bmp"></a>
			</div>
			<p style="margin-top: 0; margin-bottom: 0">Los archivos deben visualizarse utilizando el Acrobat Reader.</p>
			<p style="margin-top: 0; margin-bottom: 0">Haga clic sobre el �cono de Adobe para descargar el programa.</p>
		</div>
	</div>
</div>
<div style="margin-top:24px;">
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
</div>