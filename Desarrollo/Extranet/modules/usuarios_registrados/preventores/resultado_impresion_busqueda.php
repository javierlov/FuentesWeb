<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isPreventor"]));

$ob = "3";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];


$params = array();
$from =
	"SELECT NULL id, NULL razonsocial, NULL numeroestablecimiento, NULL tipoformulario
		 FROM DUAL
		WHERE ROWNUM = 0";

$i = 1;
foreach ($_SESSION["preventores"]["empresas"] as $value) {
	$arr = explode("-", $value);

	$params[":idempresa".$i] = $arr[0];
	$params[":nroestab".$i] = $arr[1];

	$from.=
		" UNION ALL
				 SELECT fg_id, em_nombre, fg_nroestab, tf_nombre
					 FROM hys.htf_tipoformulario, hys.hfg_formulariogenerado, aem_empresa
					WHERE tf_id = fg_idformulario
						AND fg_idempresa = em_id
						AND fg_idempresa = :idempresa".$i."
						AND fg_nroestab = :nroestab".$i."
						AND tf_fechabaja IS NULL
						AND fg_fechabaja IS NULL
						AND fg_estado = 'T'";

	$i++;
}

$sql =
	"SELECT id ¿id?,
					¿razonsocial?,
					numeroestablecimiento?,
					tipoformulario?
		 FROM (".$from.")";
$grilla = new Grid();
//$grilla->addColumn(new Column("I", 0, true, false, -1, "btnImprimir", "/modules/usuarios_registrados/preventores/mandar_pdf_a_impresora.php", "", -1, true, -1, "Imprimir"));
$grilla->addColumn(new Column("V", 0, true, false, -1, "btnPdf", "/modules/usuarios_registrados/preventores/mostrar_pdf.php", "", -1, true, -1, "Ver"));
$grilla->addColumn(new Column("Razón Social"));
$grilla->addColumn(new Column("Nº Estab."));
$grilla->addColumn(new Column("Descripción"));
$grilla->setColsSeparator(true);
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