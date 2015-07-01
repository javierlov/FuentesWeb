<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idModulo"]);

$id = substr($_REQUEST["idModulo"], 1);
$modulo = substr($_REQUEST["idModulo"], 0, 1);

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


$params = array(":idsolicitudafiliacion" => $_REQUEST["idsa"]);
$where = "";

$sql =
	"SELECT ¿ir_id?,
					ir_id ¿id2?,
					DECODE(ir_idtipopdf, 1, '1. Solicitud de Afiliación',
					DECODE(ir_idtipopdf, 2, '2. Ubicación de Riesgo',
					DECODE(ir_idtipopdf, 3, '3. RGRL Establecimiento Nº ' || LPAD(se_nroestableci, 5, ' '),
					DECODE(ir_idtipopdf, 4, '4. Addenda',
					DECODE(ir_idtipopdf, 5, '5. Responsabilidad Civil',
					DECODE(ir_idtipopdf, 6, '6. Personas Expuestas Politicamente',
					DECODE(ir_idtipopdf, 7, '7. Exposición Riesgos Químicos Estab. Nº ' || LPAD(se_nroestableci, 5, ' '),
					DECODE(ir_idtipopdf, 8, '8. Ventanilla Electrónica',
					DECODE(ir_idtipopdf, 9, '9. Nómina Personal Expuesto', NULL))))))))) ¿descripcion?,
					¿ir_cantidadhojas?,
					DECODE(ir_estado, 'I', 'Impreso', 'No impreso') ¿estado?
		 FROM web.wir_impresionesrgrl, ase_solicitudestablecimiento
		WHERE ir_idestablecimiento = se_id(+)
			AND ir_fechabaja IS NULL
			AND se_fechabaja IS NULL
			AND ir_idsolicitudafiliacion = :idsolicitudafiliacion";
$grilla = new Grid();
$grilla->addColumn(new Column("I", 0, true, false, -1, "btnImprimirChico", "/modules/solicitud_afiliacion/mandar_pdf_a_impresora.php?idmodulo=".$_REQUEST["idModulo"], "", -1, true, -1, "Imprimir"));
$grilla->addColumn(new Column("V", 0, true, false, -1, "btnPdf", "/modules/solicitud_afiliacion/mostrar_pdf.php?idmodulo=".$_REQUEST["idModulo"], "", -1, true, -1, "Ver"));
$grilla->addColumn(new Column("Descripción"));
$grilla->addColumn(new Column("Cant. Hojas (est.)"));
$grilla->addColumn(new Column("Estado"));
$grilla->setColsSeparator(true);
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
$grilla->setRowsSeparatorColor("#c0c0c0");
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->setUseTmpIframe(true);
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>