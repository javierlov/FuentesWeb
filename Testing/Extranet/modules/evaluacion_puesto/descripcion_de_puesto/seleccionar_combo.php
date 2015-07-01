<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<link href="/modules/evaluacion_puesto/css/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div align="center" id="divContent" name="divContent">
<?
$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];
$pagina = 1;
$showProcessMsg = false;

//$RCselectedItem = $_REQUEST["is"];

$params = array(":clave" => $_REQUEST["sfc"]);
$sql =
	"SELECT -1 ¿id?, '- Sin determinar -' ¿detalle?
		 FROM DUAL
UNION ALL
	 SELECT pi_id, pi_descripcion
		 FROM rrhh.dpi_itemconocimiento
		WHERE pi_clave = :clave";
$grilla = new Grid(1, 100);
$grilla->addColumn(new Column("", 8, true, false, -1, "btnAceptar", "procesar_seleccionar_combo.php?sfc=".$_REQUEST["sfc"], "GridFirstColumn"));
$grilla->addColumn(new Column("Opción"));
$grilla->setColsSeparator(true);
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
$grilla->setShowFooter(false);
$grilla->setTableStyle("gridSeleccionCombo");
$grilla->setSql($sql);
$grilla->setUseTmpIframe(true);
$grilla->Draw();
?>
		</div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<span style="color:#807F84; font-family:Trebuchet MS; font-size:9pt; font-style:italic; left:52px; position:relative; top:8px;">(Seleccione la opción deseada)</span>
	</body>
</html>