<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/jpgraph/jpgraph.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/jpgraph/jpgraph_bar.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/jpgraph/jpgraph_pie.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/jpgraph/jpgraph_pie3d.php");


if (!isset($_REQUEST["id"]))
	exit;


$_REQUEST["actual"] = intval($_REQUEST["actual"]);
$_REQUEST["provart"] = intval($_REQUEST["provart"]);

$min = round($_REQUEST["provart"] * 0.7 / 100) * 100;
$max = round($_REQUEST["actual"] * 1.1 / 100) * 100;

$leyenda = false;
if (($_REQUEST["actual"] > 1000000) and ($_REQUEST["provart"] > 1000000)) {
	$leyenda = true;

	$min = intval($min / 1000);
	$max = intval($max / 1000);

	$_REQUEST["actual"] = $_REQUEST["actual"] / 1000;
	$_REQUEST["provart"] = $_REQUEST["provart"] / 1000;
}

$min = round($min / 10) * 10;
$max = round($max / 10) * 10;


$datax[] = $_REQUEST["actual"];
$datay[] = "Prima Actual";

$datax[] = $_REQUEST["provart"];
$datay[] = "Prima Provincia ART";

$graph = new Graph(400, 256, "auto");
$graph->SetScale("textlin", $min, $max);
$graph->SetShadow();

$graph->img->SetMargin(40, 30, 20, 40);
$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD, 10);
$graph->xaxis->SetLabelAlign("center", "bottom");
$graph->xaxis->SetLabelAngle(0);
$graph->xaxis->SetLabelMargin(16);
$graph->xaxis->SetTickLabels($datay);

$graph->yaxis->SetLabelMargin(1);

if ($leyenda) {
	$txt = new Text(" (En miles)");
	$txt->SetColor("red");
	$graph->AddText($txt);
}

$bplot = new BarPlot($datax);
//$bplot->SetColor("gray");
//$bplot->SetFillColor("orange");
$bplot->SetFillGradient("#00a4e4", "#00a4e4", GRAD_VER);
$bplot->SetShadow("black", 8, 4);
$bplot->SetValuePos("bottom");
$bplot->SetWidth(0.3);
$graph->Add($bplot);

$graph->title->SetFont(FF_ARIAL, FS_BOLD, 10);
$graph->Stroke(DATA_SUSCRIPCIONES_CARTA_SOLICITUD_COTIZACION.$_REQUEST["id"]);
//$graph->Stroke();
?>