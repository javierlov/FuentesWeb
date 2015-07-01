<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isPreventor"]));

$showProcessMsg = false;

$ob = "3";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$sb = false;
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "T")
		$sb = true;
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="" id="formFormulariosBlanco" method="get" name="formFormulariosBlanco">
	<div align="center" class="TituloSeccion">Formularios en Blanco</div>
	<div align="center" id="divContent" name="divContent" style="height:360px; left:0px; top:40px; width:736px;">
<?
$params = array();
$sql =
	"SELECT ¿tf_id?, tf_id ¿id2?, ¿tf_nombre?
		 FROM hys.htf_tipoformulario
		WHERE tf_servicio = 'N'
			AND tf_fechabaja IS NULL";
$grilla = new Grid(10, 30);
$grilla->addColumn(new Column("I", 0, true, false, -1, "btnImprimir", "/modules/usuarios_registrados/preventores/mandar_pdf_a_impresora.php?b=s", "", -1, true, -1, "Imprimir"));
$grilla->addColumn(new Column("V", 0, true, false, -1, "btnPdf", "/modules/usuarios_registrados/preventores/mostrar_pdf.php?b=s", "", -1, true, -1, "Ver"));
$grilla->addColumn(new Column("Descripción"));
$grilla->setColsSeparator(true);
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRefreshIntoWindow(true);
$grilla->setRowsSeparator(true);
$grilla->setRowsSeparatorColor("#c0c0c0");
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->setUseTmpIframe(true);
$grilla->Draw();
?>
	</div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
</form>
<p style="clear:both; position:relative; top:372px;">
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
</p>
<script type="text/javascript">
	function CopyContent() {
		try {
			window.parent.document.getElementById('divContentGrid').innerHTML = document.getElementById('divContentGrid').innerHTML;
		}
		catch(err) {
			//
		}
	}

	CopyContent();
</script>