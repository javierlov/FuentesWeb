<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/jpgraph/jpgraph.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/jpgraph/jpgraph_bar.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/jpgraph/jpgraph_pie.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/jpgraph/jpgraph_pie3d.php");


$params = array(":id" => $_REQUEST["preguntaid"]);
$sql =
	"SELECT pe_pregunta
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_id = :id";
$titulo = valorSql($sql, "", $params);

$params = array(":idpregunta" => $_REQUEST["preguntaid"]);
$sql =
	"SELECT op_id, op_opcion, COUNT(rp_idopcion) tot
		 FROM rrhh.rop_opcionespreguntas, rrhh.rrp_respuestaspreguntas
		WHERE op_id = rp_idopcion(+)
			AND op_fechabaja IS NULL
			AND op_idpregunta = :idpregunta
 GROUP BY op_id, op_opcion
 ORDER BY 3 DESC";
$stmt = DBExecSql($conn, $sql, $params);
$maxChars = 0;
$maxValue = 0;
while ($row = DBGetQuery($stmt)) {
	$datax[] = $row["TOT"];
	$datay[] = $row["OP_OPCION"];

	if (strlen($row["OP_OPCION"]) > $maxChars)
		$maxChars = strlen($row["OP_OPCION"]);

	if ($row["TOT"] > $maxValue)
		$maxValue = $row["TOT"];
}


switch ($_REQUEST["tipografico"]) {
	case "B":
		$graph = new Graph(600, 200 + ($maxChars * 6), "auto");
		$graph->SetScale("textint", 0, $maxValue + 2);
		$graph->SetShadow();

		$graph->img->SetMargin(40, 30, 20, 40);
		$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD, 10);
		$graph->xaxis->SetLabelAlign("center","bottom");
		$graph->xaxis->SetLabelAngle(90);
		$graph->xaxis->SetLabelMargin(-24);
		$graph->xaxis->SetTickLabels($datay);
		$graph->yaxis->scale->SetGrace(20);

		$bplot = new BarPlot($datax);
		$bplot->SetFillColor("orange");
		$bplot->SetFillGradient("#00a4e4", "#00a4e4", GRAD_VER);
		$bplot->SetShadow();
		$bplot->SetValuePos("bottom");
		$bplot->value->Show();
		$bplot->value->SetFont(FF_ARIAL,FS_BOLD, 10);
		$bplot->value->SetAngle(2);
		$bplot->value->SetFormat("%0.0f");
		$graph->Add($bplot);
		break;
	case "T":
		$textWidth = 56 + ($maxChars * 6);
		$width = 600;
		if ($textWidth > $width)
			$width = $textWidth;

		$graph = new PieGraph($width, 200 + (count($datax) * 32), "auto");
		$graph->SetScale("textlin");
		$graph->SetShadow();

		$graph->legend->SetFont(FF_ARIAL, FS_NORMAL, 9);
		$graph->legend->SetLayout(LEGEND_VERT);
		$graph->legend->Pos(0.03, 0.95, "left", "bottom");

		$p1 = new PiePlot3D($datax);
		$p1->SetAngle(30);
		$p1->SetCenter(0.5, 0.3);
		$p1->SetSize(0.4);
		$p1->SetTheme("pastel");
		$p1->SetLegends($datay);
		$p1->value->Show();
		$p1->value->SetFormat("%2.2f%%");

		$graph->Add($p1);
		break;
}

$graph->footer->right->Set("Provincia ART");
$graph->footer->right->SetColor("#0f539c");
$graph->footer->right->SetFont(FF_FONT1, FS_BOLD);

$graph->title->Set($titulo);
$graph->title->SetFont(FF_ARIAL, FS_BOLD, 10);
$graph->Stroke();
?>