<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");

set_time_limit(300);

function agregarPagina($tipoCabecera, $dibujarTitulo) {
	global $pagina;
	global $pdf;

	$pagina++;
	$pdf->AddPage();
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx);

	dibujarCabecera($tipoCabecera, $dibujarTitulo);
	dibujarPie($pagina);
}

function dibujarCabecera($tipoCabecera, $dibujarTitulo) {
	global $pdf;
	global $row;

	setNumeroSolicitud($row["CUIT"], $row["FO_FORMULARIO"]);

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Ln(-7.2);
	$pdf->Cell(4);
	$pdf->Cell(0, 0, "21/12/2012");

	$pdf->SetFont("Arial", "B", 9);
	$pdf->Ln(3);
	$pdf->Cell(192, 0, $row["TITULO"], 0, 0, "C");

	$pdf->SetFont("Arial", "B", 8);
	$pdf->Ln(8);
	$pdf->Cell(-6);
	$pdf->Cell(48, 0, "C.U.I.T./C.U.I.P. N� ".ponerGuiones($row["CUIT"]));
	$pdf->Cell(0, 0, "N� Establecimiento ".$row["NROESTABLECIMIENTO"]);
	$pdf->Ln(12);

	$pdf->SetFillColor(255, 255, 255);
	$pdf->Rect(2, 22, 210, 80, "F");
	$pdf->SetFillColor(0, 0, 0);

	if ($tipoCabecera == 1) {
		$pdf->Rect(5, 22, 5.4, 4, "F");
		$pdf->Rect(11.0, 22, 116.4, 4, "F");
		$pdf->Rect(127.8, 22, 3.2, 4, "F");
		$pdf->Rect(131.4, 22, 4, 4, "F");
		$pdf->Rect(135.8, 22, 8.8, 4, "F");
		$pdf->Rect(145.2, 22, 12.6, 4, "F");
		$pdf->Rect(158.4, 22, 52.2, 4, "F");

		$pdf->SetTextColor(255, 255, 255);
		$pdf->Ln(-8.4);
		$pdf->SetFont("Arial", "B", 6);
		$pdf->Cell(87);
		$pdf->Cell(0, 0, "FECHA", 0, 0, "C");

		$pdf->Ln(0.4);
		$pdf->SetFont("Arial", "B", 7);
		$pdf->Cell(126.2);
		$pdf->Cell(8, 0, "NO", 0, 0, "C");

		$pdf->Ln(0.4);
		$pdf->Cell(-4.4);
		$pdf->Cell(6, 0, "N�");
		$pdf->Cell(115.6, 0, "-");
		$pdf->Cell(4.8, 0, "S�");
		$pdf->Cell(-1.4);
		$pdf->Cell(39, 0, "NO");
		$pdf->Cell(53, 0, "NORMATIVAVIGENTE");

		$pdf->Ln(1.2);
		$pdf->SetFont("Arial", "B", 6);
		$pdf->Cell(125.4);
		$pdf->Cell(0, 0, "APLICA");

		$pdf->Ln(0);
		$pdf->SetFont("Arial", "B", 4);
		$pdf->Cell(134.2);
		$pdf->Cell(0, 0, "REGULARIZACI�N");
		$pdf->Ln(2);
		$pdf->SetTextColor(0, 0, 0);
	}

	if ($tipoCabecera == 2) {		// Cancer�genos..
		$pdf->Ln(-9.6);
		$pdf->SetFillColor(0, 0, 0);
		$pdf->Rect($pdf->GetX() - 5, $pdf->GetY(), 51, 4, "F");
		$pdf->Rect($pdf->GetX() + 46.4, $pdf->GetY(), 51, 4, "F");
		$pdf->Rect($pdf->GetX() + 98, $pdf->GetY(), 51, 4, "F");
		$pdf->Rect($pdf->GetX() + 149.4, $pdf->GetY(), 51, 4, "F");

		$pdf->SetTextColor(255, 255, 255);
		$pdf->Ln(2);
		$pdf->SetFont("Arial", "B", 6);
		$pdf->Cell(-5);
		$pdf->Cell(39, 0, "DESCRIPCI�N");
		$pdf->Cell(6, 0, "S�");
		$pdf->Cell(7, 0, "NO");

		$pdf->Cell(38, 0, "DESCRIPCI�N");
		$pdf->Cell(6, 0, "S�");
		$pdf->Cell(7.6, 0, "NO");

		$pdf->Cell(38, 0, "DESCRIPCI�N");
		$pdf->Cell(6, 0, "S�");
		$pdf->Cell(7.6, 0, "NO");

		$pdf->Cell(38, 0, "DESCRIPCI�N");
		$pdf->Cell(6, 0, "S�");
		$pdf->Cell(7, 0, "NO");
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Ln(-15);
	}

	if ($tipoCabecera == 3) {		// Difenilos..
		$pdf->Ln(-9.6);
		$pdf->SetFillColor(0, 0, 0);
		$pdf->Rect($pdf->GetX() - 5, $pdf->GetY(), 51, 4, "F");
		$pdf->Rect($pdf->GetX() + 46.4, $pdf->GetY(), 51, 4, "F");
		$pdf->Rect($pdf->GetX() + 98, $pdf->GetY(), 51, 4, "F");
		$pdf->Rect($pdf->GetX() + 149.4, $pdf->GetY(), 51, 4, "F");

		$pdf->SetTextColor(255, 255, 255);
		$pdf->Ln(2);
		$pdf->SetFont("Arial", "B", 6);
		$pdf->Cell(-5);
		$pdf->Cell(39, 0, "DESCRIPCI�N");
		$pdf->Cell(6, 0, "S�");
		$pdf->Cell(7, 0, "NO");

		$pdf->Cell(38, 0, "DESCRIPCI�N");
		$pdf->Cell(6, 0, "S�");
		$pdf->Cell(7.6, 0, "NO");

		$pdf->Cell(38, 0, "DESCRIPCI�N");
		$pdf->Cell(6, 0, "S�");
		$pdf->Cell(7.6, 0, "NO");

		$pdf->Cell(38, 0, "DESCRIPCI�N");
		$pdf->Cell(6, 0, "S�");
		$pdf->Cell(7, 0, "NO");
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Ln(-15);
	}

	if ($tipoCabecera == 4) {		// Qu�micas..
		$pdf->Ln(-9);

		if ($dibujarTitulo) {
			$pdf->Ln(4);
			$pdf->SetFont("Arial", "B", 8);
			$pdf->Cell(-4);
			$pdf->Cell(0, 0, "PLANILLA C | SUSTANCIAS QU�MICAS A DECLARAR");
			$pdf->Ln(4);
		}

		$pdf->SetFillColor(0, 0, 0);
		$pdf->Rect($pdf->GetX() - 5, $pdf->GetY(), 102.4, 4, "F");
		$pdf->Rect($pdf->GetX() + 98, $pdf->GetY(), 102.4, 4, "F");

		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont("Arial", "B", 6);
		$pdf->Ln(1.2);
		$pdf->Cell(79.6, 0, "CANTIDAD", 0, 0, "R");
		$pdf->Cell(103, 0, "CANTIDAD", 0, 0, "R");
		$pdf->Ln(1.8);
		$pdf->Cell(-5);
		$pdf->Cell(70, 0, "DESCRIPCI�N", 0, 0, "C");
		$pdf->Cell(16, 0, "UMBRAL(TN)", 0, 0, "C");
		$pdf->Cell(8, 0, "S�", 0, 0, "C");
		$pdf->Cell(8, 0, "NO", 0, 0, "C");

		$pdf->Cell(71, 0, "DESCRIPCI�N", 0, 0, "C");
		$pdf->Cell(16, 0, "UMBRAL(TN)", 0, 0, "C");
		$pdf->Cell(8, 0, "S�", 0, 0, "C");
		$pdf->Cell(8, 0, "NO", 0, 0, "C");
		$pdf->SetTextColor(0, 0, 0);
	}
}

function dibujarCancerigenas($arrCancerigenas) {
	global $pagina;
	global $pdf;

	if ($pdf->GetY() > 280)
		agregarPagina(0, false);

	// Dibujo la cabecera..
	$pdf->Ln(4);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(-4);
	$pdf->Cell(0, 0, "PLANILLA A | LISTADO DE SUSTANCIAS Y AGENTES CANCER�GENOS");

	$pdf->Ln(3);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->Rect($pdf->GetX() - 5, $pdf->GetY(), 102.4, 4, "F");
	$pdf->Rect($pdf->GetX() + 98, $pdf->GetY(), 102.4, 4, "F");

	$pdf->SetTextColor(255, 255, 255);
	$pdf->Ln(2);
	$pdf->SetFont("Arial", "B", 6);
	$pdf->Cell(-5);
	$pdf->Cell(86, 0, "DESCRIPCI�N", 0, 0, "C");
	$pdf->Cell(8, 0, "S�", 0, 0, "C");
	$pdf->Cell(8, 0, "NO", 0, 0, "C");

	$pdf->Cell(87, 0, "DESCRIPCI�N", 0, 0, "C");
	$pdf->Cell(8, 0, "S�", 0, 0, "C");
	$pdf->Cell(8, 0, "NO", 0, 0, "C");
	$pdf->SetTextColor(0, 0, 0);

	// Dibujo los items..
	for ($i=0; $i<count($arrCancerigenas); $i+=2) {
		if ($pdf->GetY() > 280)
			agregarPagina(2, false);

		$indColumna2 = $i + 1;
		$tope = $pdf->GetY() + 3.2;

		// Calculo la cantidad de lineas que va a ocupar el item..
		$texto = $arrCancerigenas[$i]["IT_DESCRIPCION"];
		$pdf->SetY($tope + 0.6);
		$pdf->WordWrap($texto, 84);
		$texto = explode("\n", $texto);
		for ($j=0; $j<count($texto); $j++)
			$pdf->SetY($tope + 2);

		// Dibujo el fondo..
		$pdf->SetDrawColor(191, 191, 191);
		$pdf->SetFillColor(202, 202, 202);
		$pdf->Rect(5, $tope - 1, 102.4, 4, "DF");
		if (isset($arrCancerigenas[$indColumna2]))
			$pdf->Rect(108, $tope - 1, 102.4, 4, "DF");

		// Dibujo la linea horizontal..
		$pdf->SetDrawColor(191, 191, 191);
		$pdf->Line(5, $tope - 1, 102.4, $tope - 1);
		if (isset($arrCancerigenas[$indColumna2]))
			$pdf->Line(108, $tope - 1, 204.8, $tope - 1);

		// Dibujo las lineas verticales..
		$pdf->Line(91, $tope - 1, 91, $tope + 2.6);
		$pdf->Line(99, $tope - 1, 99, $tope + 2.6);
		if (isset($arrCancerigenas[$indColumna2])) {
			$pdf->Line(194, $tope - 1, 194, $tope + 2.6);
			$pdf->Line(202, $tope - 1, 202, $tope + 2.6);
		}

		// Dibujo el texto para saber cuantas lineas va a tener el item..
		$texto = $arrCancerigenas[$i]["IT_DESCRIPCION"];
		$pdf->SetFont("Arial", "", 6);
		$pdf->SetY($tope + 0.6);
		$pdf->WordWrap($texto, 84);
		$texto = explode("\n", $texto);
		for ($j=0; $j<count($texto); $j++) {
			$str = trim($texto[$j]);

			$pdf->Cell(-4);
			$pdf->Cell(84, 0, $str);
			$pdf->SetY($tope + 2);
		}

		// Dibujo el SI o NO..
		$pdf->SetY($tope + 1);
		$pdf->Cell(81);
		$pdf->Cell(8, 0, $arrCancerigenas[$i]["CUMP_SI"], 0, 0, "C");
		$pdf->Cell(8, 0, $arrCancerigenas[$i]["CUMP_NO"], 0, 0, "C");

		// Dibujo el texto para saber cuantas lineas va a tener el item..
		if (isset($arrCancerigenas[$indColumna2])) {
			$texto = $arrCancerigenas[$indColumna2]["IT_DESCRIPCION"];
			$pdf->SetFont("Arial", "", 6);
			$pdf->SetY($tope + 0.6);
			$pdf->WordWrap($texto, 84);
			$texto = explode("\n", $texto);
			for ($j=0; $j<count($texto); $j++) {
				$str = trim($texto[$j]);

				$pdf->Cell(100);
				$pdf->Cell(84, 0, $str);
				$pdf->SetY($tope + 2);
			}

			// Dibujo el SI o NO..
			$pdf->SetY($tope + 1);
			$pdf->Cell(184);
			$pdf->Cell(8, 0, $arrCancerigenas[$indColumna2]["CUMP_SI"], 0, 0, "C");
			$pdf->Cell(8, 0, $arrCancerigenas[$indColumna2]["CUMP_NO"], 0, 0, "C");
		}
	}

	$pdf->Ln(4.4);
	$pdf->SetFont("Arial", "", 6);
	$pdf->Cell(1);
	$pdf->Cell(0, 0, "La codificaci�n aqu� representada corresponde al listado de c�digos de agentes de riesgo normado en la Disposici�n G.P. y C. N� 005 de fecha de 10 de Mayo de 2005.");
}

function dibujarDifenilos($arrDifenilos) {
	global $pagina;
	global $pdf;

	if ($pdf->GetY() > 272)
		agregarPagina(0, false);

	// Dibujo la cabecera..
	$pdf->Ln(8);
	$pdf->SetFont("Arial", "B", 8);
	$pdf->Cell(-4);
	$pdf->Cell(0, 0, "PLANILLA B | DIFENILOS POLICLORADOS");

	$pdf->Ln(3);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->Rect($pdf->GetX() - 5, $pdf->GetY(), 51, 4, "F");
	$pdf->Rect($pdf->GetX() + 46.4, $pdf->GetY(), 51, 4, "F");
	$pdf->Rect($pdf->GetX() + 98, $pdf->GetY(), 51, 4, "F");
	$pdf->Rect($pdf->GetX() + 149.4, $pdf->GetY(), 51, 4, "F");

	$pdf->SetTextColor(255, 255, 255);
	$pdf->Ln(2);
	$pdf->SetFont("Arial", "B", 6);
	$pdf->Cell(-5);
	$pdf->Cell(39, 0, "DESCRIPCI�N");
	$pdf->Cell(6, 0, "S�");
	$pdf->Cell(7, 0, "NO");

	$pdf->Cell(38, 0, "DESCRIPCI�N");
	$pdf->Cell(6, 0, "S�");
	$pdf->Cell(7.6, 0, "NO");

	$pdf->Cell(38, 0, "DESCRIPCI�N");
	$pdf->Cell(6, 0, "S�");
	$pdf->Cell(7.6, 0, "NO");

	$pdf->Cell(38, 0, "DESCRIPCI�N");
	$pdf->Cell(6, 0, "S�");
	$pdf->Cell(7, 0, "NO");
	$pdf->SetTextColor(0, 0, 0);

	// Dibujo los items..
	for ($i=0; $i<count($arrDifenilos); $i+=4) {
		if ($pdf->GetY() > 280) {
			agregarPagina(3, false);
			$pdf->Ln(-3.2);
		}

		$indColumna2 = $i + 1;
		$indColumna3 = $i + 2;
		$indColumna4 = $i + 3;
		$tope = $pdf->GetY() + 3.2;

		// Dibujo el fondo..
		$pdf->SetDrawColor(191, 191, 191);
		$pdf->SetFillColor(202, 202, 202);
		$pdf->Rect(5, $tope - 1, 51, 4, "DF");
		if (isset($arrDifenilos[$indColumna2]))
			$pdf->Rect(56.4, $tope - 1, 51, 4, "DF");
		if (isset($arrDifenilos[$indColumna3]))
			$pdf->Rect(108, $tope - 1, 51, 4, "DF");
		if (isset($arrDifenilos[$indColumna4]))
			$pdf->Rect(159.4, $tope - 1, 51, 4, "DF");

		// Dibujo la linea horizontal..
		$pdf->SetDrawColor(191, 191, 191);
		$pdf->Line(5, $tope - 1, 51, $tope - 1);
		if (isset($arrDifenilos[$indColumna2]))
			$pdf->Line(56.4, $tope - 1, 51, $tope - 1);
		if (isset($arrDifenilos[$indColumna3]))
			$pdf->Line(108, $tope - 1, 51, $tope - 1);
		if (isset($arrDifenilos[$indColumna4]))
			$pdf->Line(159.4, $tope - 1, 51, $tope - 1);

		// Dibujo las lineas verticales..
		$pdf->Line(43, $tope - 1, 43, $tope + 2.6);
		$pdf->Line(49.4, $tope - 1, 49.4, $tope + 2.6);
		if (isset($arrDifenilos[$indColumna2])) {
			$pdf->Line(94, $tope - 1, 94, $tope + 2.6);
			$pdf->Line(100.4, $tope - 1, 100.4, $tope + 2.6);
		}
		if (isset($arrDifenilos[$indColumna3])) {
			$pdf->Line(144.8, $tope - 1, 144.8, $tope + 2.6);
			$pdf->Line(152.4, $tope - 1, 152.4, $tope + 2.6);
		}
		if (isset($arrDifenilos[$indColumna4])) {
			$pdf->Line(197.2, $tope - 1, 197.2, $tope + 2.6);
			$pdf->Line(203.6, $tope - 1, 203.6, $tope + 2.6);
		}

		// Dibujo el texto..
		$pdf->SetFont("Arial", "", 6);
		$pdf->SetY($tope + 1.2);
		$pdf->Cell(-5);
		$pdf->Cell(36, 0, $arrDifenilos[$i]["IT_DESCRIPCION"]);

		// Dibujo el SI o NO..
		$pdf->Cell(2);
		$pdf->Cell(6.2, 0, $arrDifenilos[$i]["CUMP_SI"], 0, 0, "C");
		$pdf->Cell(7, 0, $arrDifenilos[$i]["CUMP_NO"], 0, 0, "C");

		if (isset($arrDifenilos[$indColumna2])) {
			// Dibujo el texto..
			$pdf->SetFont("Arial", "", 6);
			$pdf->Cell(0.2);
			$pdf->Cell(36, 0, $arrDifenilos[$indColumna2]["IT_DESCRIPCION"]);

			// Dibujo el SI o NO..
			$pdf->Cell(1.4);
			$pdf->Cell(6.2, 0, $arrDifenilos[$indColumna2]["CUMP_SI"], 0, 0, "C");
			$pdf->Cell(7, 0, $arrDifenilos[$indColumna2]["CUMP_NO"], 0, 0, "C");
		}

		if (isset($arrDifenilos[$indColumna3])) {
			// Dibujo el texto..
			$pdf->SetFont("Arial", "", 6);
			$pdf->Cell(0.6);
			$pdf->Cell(36, 0, $arrDifenilos[$indColumna3]["IT_DESCRIPCION"]);

			// Dibujo el SI o NO..
			$pdf->Cell(2);
			$pdf->Cell(6.2, 0, $arrDifenilos[$indColumna3]["CUMP_SI"], 0, 0, "C");
			$pdf->Cell(7, 0, $arrDifenilos[$indColumna3]["CUMP_NO"], 0, 0, "C");
		}

		if (isset($arrDifenilos[$indColumna4])) {
			// Dibujo el texto..
			$pdf->SetFont("Arial", "", 6);
			$pdf->Cell(0.6);
			$pdf->Cell(36, 0, $arrDifenilos[$indColumna4]["IT_DESCRIPCION"]);

			// Dibujo el SI o NO..
			$pdf->Cell(2);
			$pdf->Cell(6.2, 0, $arrDifenilos[$indColumna4]["CUMP_SI"], 0, 0, "C");
			$pdf->Cell(7, 0, $arrDifenilos[$indColumna4]["CUMP_NO"], 0, 0, "C");
		}
	}
}

function dibujarPie($pagina) {
	global $pdf;
	global $row;

	$pdf->SetY(-24);
	$pdf->SetX(5);
	$pdf->Rect($pdf->GetX(), $pdf->GetY(), 20, 4, "F");
	$pdf->SetTextColor(255, 255, 255);
	$pdf->Ln(2);
	$pdf->SetFont("Arial", "", 6);
	$pdf->Cell(-1.8);
	$pdf->Cell(40, 0, "PV-01-F00".$row["RA_NROFORMULARIO"]);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(0, 0, "P�gina ".$pagina, 0, 0, "R");
	$pdf->SetY(27.4);
}

function dibujarPregunta() {
	global $pdf;
	global $rowPreguntas;

	$tope = $pdf->GetY();

	// Dibujo la linea horizontal..
	$pdf->SetDrawColor(191, 191, 191);
	$pdf->SetLineWidth(0.4);
	$pdf->Line($pdf->GetX() - 5, $tope - 1, $pdf->GetX() + 200.6, $tope - 1);
	$pdf->SetLineWidth(0.2);

	// Dibujo el texto para saber cuantas lineas va a tener el item..
	$texto = $rowPreguntas["IA_DESCRIPCION"];
	$pdf->SetFont("Arial", "", 6);
	$pdf->SetY($tope + 0.4);
	$pdf->WordWrap($texto, 116);
	$texto = explode("\n", $texto);
	for ($i=0; $i<count($texto); $i++) {
		$str = trim($texto[$i]);

		$pdf->Cell(0.1);
		$pdf->Cell(0, 0, $str);
		$pdf->SetY($pdf->GetY() + 2);
	}

	// Dibujo las lineas verticales..
	$pdf->Cell(0.4);
	$pdf->Line($pdf->GetX(), $tope - 1, $pdf->GetX(), $tope + (1.8 * $i));
	$pdf->Cell(117.2);
	$pdf->Line($pdf->GetX(), $tope - 1, $pdf->GetX(), $tope + (1.8 * $i));
	$pdf->Cell(3.6);
	$pdf->Line($pdf->GetX(), $tope - 1, $pdf->GetX(), $tope + (1.8 * $i));
	$pdf->Cell(4.4);
	$pdf->Line($pdf->GetX(), $tope - 1, $pdf->GetX(), $tope + (1.8 * $i));
	$pdf->Cell(9.2);
	$pdf->Line($pdf->GetX(), $tope - 1, $pdf->GetX(), $tope + (1.8 * $i));
	$pdf->Cell(13.2);
	$pdf->Line($pdf->GetX(), $tope - 1, $pdf->GetX(), $tope + (1.8 * $i));

	// Dibujo el resto de los textos..
	$pdf->SetY($tope + 0.4);
	$pdf->SetFont("Arial", "", 6);
	$pdf->Cell(-6);
	$pdf->Cell(6.4, 0, $rowPreguntas["IA_NRODESCRIPCION"], 0, 0, "R");

	$pdf->Cell(117.4);
	$pdf->Cell(4, 0, $rowPreguntas["SICUMP"]);
	$pdf->Cell(-0.6);

	$pdf->Cell(4, 0, $rowPreguntas["NOCUMP"], 0, 0, "C");
	$pdf->Cell(0.6);

	$pdf->Cell(9, 0, $rowPreguntas["NOAPCUMP"], 0, 0, "C");

	$pdf->Cell(13.4, 0, $rowPreguntas["ST_FECHAREGULARIZACION"]);


	$pdf->SetY($pdf->GetY() - 0.4);
	$pdf->SetFont("Arial", "", ((strlen($rowPreguntas["IA_NORMATIVA"]) > 15)?5:4));
	$pdf->Cell(148);
	$pdf->Cell(28, 0, $rowPreguntas["IA_NORMATIVA"]);

	$pdf->SetFont("Arial", "", 4);
	$pdf->Cell(24, 0, $rowPreguntas["IA_NORMATIVABIS"], 0, 0, "R");

	$pdf->SetY($pdf->GetY() + 1.2);
	$pdf->SetFont("Arial", "", ((strlen($rowPreguntas["IA_NORMATIVA"]) > 15)?5:4));
	$pdf->Cell(148);
	$pdf->Cell(28, 0, $rowPreguntas["IA_NORMATIVA2"]);

	$pdf->SetFont("Arial", "", 4);
	$pdf->Cell(24, 0, $rowPreguntas["IA_NORMATIVABIS2"], 0, 0, "R");

	// Seteo propiedades finales..
	$pdf->SetY($tope + 1 + (2 * $i));
	$pdf->SetDrawColor(0, 0, 0);
}

function dibujarQuimicas($arrQuimicas) {
	global $pagina;
	global $pdf;

	if ($pdf->GetY() > 280)
		agregarPagina(4, true);
	else {
		// Dibujo la cabecera..
		$pdf->Ln(8);
		$pdf->SetFont("Arial", "B", 8);
		$pdf->Cell(-4);
		$pdf->Cell(0, 0, "PLANILLA C | SUSTANCIAS QU�MICAS A DECLARAR");	
	}

	$pdf->Ln(3);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->Rect($pdf->GetX() - 5, $pdf->GetY(), 102.4, 4, "F");
	$pdf->Rect($pdf->GetX() + 98, $pdf->GetY(), 102.4, 4, "F");

	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont("Arial", "B", 6);
	$pdf->Ln(1.2);
	$pdf->Cell(79.6, 0, "CANTIDAD", 0, 0, "R");
	$pdf->Cell(103, 0, "CANTIDAD", 0, 0, "R");
	$pdf->Ln(1.8);
	$pdf->Cell(-5);
	$pdf->Cell(70, 0, "DESCRIPCI�N", 0, 0, "C");
	$pdf->Cell(16, 0, "UMBRAL(TN)", 0, 0, "C");
	$pdf->Cell(8, 0, "S�", 0, 0, "C");
	$pdf->Cell(8, 0, "NO", 0, 0, "C");

	$pdf->Cell(71, 0, "DESCRIPCI�N", 0, 0, "C");
	$pdf->Cell(16, 0, "UMBRAL(TN)", 0, 0, "C");
	$pdf->Cell(8, 0, "S�", 0, 0, "C");
	$pdf->Cell(8, 0, "NO", 0, 0, "C");
	$pdf->SetTextColor(0, 0, 0);

	$pdf->Ln(1.4);
	$lineas = 0;

	// Dibujo los items..
	for ($i=0; $i<count($arrQuimicas); $i+=2) {
		if ($pdf->GetY() > 280) {
			agregarPagina(4, false);
			$lineas = 0;
		}

		$indColumna2 = $i + 1;
		$tope = $pdf->GetY() + (1.8 * ($lineas));

		// Calculo la cantidad de lineas que va a ocupar el item..
		$texto = $arrQuimicas[$i]["IT_DESCRIPCION"];
		$pdf->WordWrap($texto, 68);
		$texto = explode("\n", $texto);
		$lineas = count($texto);
		if (isset($arrQuimicas[$indColumna2])) {
			$texto = $arrQuimicas[$indColumna2]["IT_DESCRIPCION"];
			$pdf->WordWrap($texto, 68);
			$texto = explode("\n", $texto);
			if (count($texto) > $lineas)
				$lineas = count($texto);
		}

		// Dibujo el fondo..
		$pdf->SetDrawColor(191, 191, 191);
		$pdf->SetFillColor(202, 202, 202);
		$pdf->Rect(5, $tope, 102.4, (2.4 * $lineas), "DF");
		if (isset($arrQuimicas[$indColumna2]))
			$pdf->Rect(108, $tope, 102.4, (2.4 * $lineas), "DF");

		// Dibujo las lineas verticales..
		$pdf->Line(76, $tope, 76, $tope + (2.4 * $lineas));
		$pdf->Line(91, $tope, 91, $tope + (2.4 * $lineas));
		$pdf->Line(99, $tope, 99, $tope + (2.4 * $lineas));
		if (isset($arrQuimicas[$indColumna2])) {
			$pdf->Line(178, $tope, 178, $tope + (2.4 * $lineas));
			$pdf->Line(194, $tope, 194, $tope + (2.4 * $lineas));
			$pdf->Line(202, $tope, 202, $tope + (2.4 * $lineas));
		}

		// Dibujo el texto..
		$texto = $arrQuimicas[$i]["IT_DESCRIPCION"];
		$pdf->SetFont("Arial", "", 6);
		$pdf->SetY($tope + 1.4);
		$pdf->WordWrap($texto, 68);
		$texto = explode("\n", $texto);
		for ($j=0; $j<count($texto); $j++) {
			$str = trim($texto[$j]);

			$pdf->Cell(-4);
			$pdf->Cell(68, 0, $str);
			$pdf->SetY($tope + (($j + 1.6) * 2));
		}

		// Dibujo el SI o NO..
		$pdf->SetY($tope + 1.4);
		$pdf->Cell(65.6);
		$pdf->Cell(16, 0, $arrQuimicas[$i]["IT_MASDATOS"], 0, 0, "C");
		$pdf->Cell(8, 0, $arrQuimicas[$i]["CUMP_SI"], 0, 0, "C");
		$pdf->Cell(8, 0, $arrQuimicas[$i]["CUMP_NO"], 0, 0, "C");

		// Dibujo el texto..
		if (isset($arrQuimicas[$indColumna2])) {
			$texto = $arrQuimicas[$indColumna2]["IT_DESCRIPCION"];
			$pdf->SetFont("Arial", "", 6);
			$pdf->SetY($tope + 1.4);
			$pdf->WordWrap($texto, 68);
			$texto = explode("\n", $texto);
			for ($j=0; $j<count($texto); $j++) {
				$str = trim($texto[$j]);

				$pdf->Cell(98);
				$pdf->Cell(68, 0, $str);
				$pdf->SetY($tope + (($j + 1.6) * 2));
			}

			// Dibujo el SI o NO..
			$pdf->SetY($tope + 1.4);
			$pdf->Cell(168);
			$pdf->Cell(16, 0, $arrQuimicas[$indColumna2]["IT_MASDATOS"], 0, 0, "C");
			$pdf->Cell(8, 0, $arrQuimicas[$indColumna2]["CUMP_SI"], 0, 0, "C");
			$pdf->Cell(8, 0, $arrQuimicas[$indColumna2]["CUMP_NO"], 0, 0, "C");
		}
	}

	// Dibujo la frase final..
	$pdf->Ln(4.4);
	$pdf->SetFont("Arial", "", 6);
	$texto = "[*]Cantidad umbral: designa respecto de una sustancia o categor�a de sustancias peligrosas la cantidad fijada para cada establecimiento por la legislaci�n nacional con referencia a condiciones espec�ficas que, si se sobrepasa, identifica una instalaci�n expuesta a riesgos de accidentes mayores. La cantidad umbral se refiere a cada establecimiento. Las cantidades umbrales son las maximas que est�n presentes, o puedan estarlo, en un momento dado.";
	$pdf->WordWrap($texto, 200);
	$texto = explode("\n", $texto);
	for ($j=0; $j<count($texto); $j++) {
		$str = trim($texto[$j]);

		$pdf->Cell(-5);
		$pdf->Cell(200, 0, $str);
		$pdf->Ln(2.4);
	}
}

function dibujarTitulo() {
	global $pdf;
	global $rowPreguntas;

	$pdf->Rect($pdf->GetX() - 5, $pdf->GetY() - 0.8, 205.8, 2.4, "F");

	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont("Arial", "", 6);
	$pdf->Ln(0.6);
	$pdf->Cell(0.2);
	$pdf->Cell(196, 0, $rowPreguntas["TA_DESCRIPCION"]);
	$pdf->Ln(2.4);
	$pdf->SetTextColor(0, 0, 0);
}

function setNumeroSolicitud($cuit, $numeroFormulario) {
	global $pdf;

	$pdf->SetY(15.8);
	$pdf->SetX(112);

	$pdf->SetFont("Arial", "", 9);
	$pdf->Cell(0, 0, "00051-".$cuit."-".$numeroFormulario);
}


validarSesion(isset($_SESSION["isAgenteComercial"]));


SetDateFormatOracle("DD/MM/YYYY");

$params = array();
$sql = "select * from ase_solicitudestablecimiento where se_idsolicitud = 53759 AND se_nroestableci > 5000 order by 2";
$stmt3 = DBExecSql($conn, $sql, $params);
while ($row3 = DBGetQuery($stmt3)) {



$params = array(":idsolicitudestablecimiento" => $row3["SE_ID"]);
$sql =
	"SELECT actafip.ac_codigo actafip,
					ciiu.ac_descripcion actividad,
					ciiu.ac_codigo ciiu,
					DECODE(se_cpostala, '99999999', '', se_cpostala) cpostala,
					sa_cuit cuit,
					UPPER(ra_descripcion) descripcion,
					se_domicilio domicilio,
					se_empleados empleados,
					fo_formulario,
					UPPER(ra_header) header,
					se_localidad localidad,
					sa_nombre nombre,
					substr(to_char(se_nroestableci), 4) nroestablecimiento,
					pv_descripcion provincia,
					ra_nroformulario,
					se_nombre,
					se_origendato,
					se_superficie superficie,
					se_telefonos telefonos,
					UPPER(ra_titulo) titulo
		 FROM afi.ase_solicitudestablecimiento, afi.asa_solicitudafiliacion, hys.hsf_solicitudfgrl,
					hys.hra_resolucionanexo, comunes.cac_actividad ciiu, comunes.cac_actividad actafip, art.cpv_provincias,
					afo_formulario
		WHERE sa_id = se_idsolicitud(+)
			AND se_idactividad = ciiu.ac_id(+)
			AND se_id = sf_idsolicitudestablecimiento(+)
			AND ra_id(+) = sf_idresolucionanexo
			AND se_provincia = pv_codigo
			AND sa_idactividad = actafip.ac_id(+)
			AND sa_idformulario = fo_id(+)
			AND se_id = :idsolicitudestablecimiento";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt, 1, false);

if ($row["SE_ORIGENDATO"] == 1)
	$row["CUIT"] = "30545959948";
elseif ($row["SE_ORIGENDATO"] == 2)
	$row["CUIT"] = "30566222457";
elseif ($row["SE_ORIGENDATO"] == 3)
	$row["CUIT"] = "30693800605";
elseif ($row["SE_ORIGENDATO"] == 4)
	$row["CUIT"] = "30527508165";
elseif ($row["SE_ORIGENDATO"] == 5)
	$row["CUIT"] = "33711086019";


if (!isset($_REQUEST["ap"]))
	$autoPrint = false;
else
	$autoPrint = ($_REQUEST["ap"] == "t");

if ($autoPrint)
	$pdf = new PDF_AutoPrint("P", "mm", array(216, 320));
else
	$pdf = new FPDI("P", "mm", array(216, 320));

$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/templates/rgrl.pdf");

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pagina = 1;
dibujarPie($pagina);

// Cabecera..
setNumeroSolicitud($row["CUIT"], $row["FO_FORMULARIO"]);

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(-7.2);
$pdf->Cell(4);
$pdf->Cell(0, 0, "21/12/2012");

$pdf->SetFont("Arial", "B", 9);
$pdf->Ln(3);
$pdf->Cell(192, 0, $row["TITULO"], 0, 0, "C");

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(34.6);
$pdf->Cell(26);
$pdf->Cell(100, 0, $row["SE_NOMBRE"]);


$pdf->Cell(26);
$pdf->Cell(0, 0, ponerGuiones($row["CUIT"]));

$pdf->Ln(5.2);
$pdf->Cell(28);
$pdf->Cell(24, 0, $row["NROESTABLECIMIENTO"]);

$pdf->Cell(60);
$pdf->Cell(20, 0, $row["CIIU"]);

$pdf->Cell(56);
$pdf->Cell(0, 0, $row["SUPERFICIE"]);

$pdf->Ln(5.4);
$pdf->Cell(84);
$pdf->Cell(48, 0, $row["ACTAFIP"]);

$pdf->Cell(44);
if ($row["EMPLEADOS"] == 0)
	$pdf->Cell(0, 0, " ");
else
	$pdf->Cell(0, 0, $row["EMPLEADOS"]);

$pdf->Ln(5);
$pdf->Cell(40);
$pdf->Cell(156, 0, $row["ACTIVIDAD"]);

$pdf->Ln(5.6);
$pdf->Cell(10);
$pdf->Cell(186, 0, $row["DOMICILIO"]);

$pdf->Ln(5.2);
$pdf->Cell(8);
$pdf->Cell(38, 0, $row["PROVINCIA"]);

$pdf->Cell(36);
$pdf->Cell(32, 0, $row["CPOSTALA"]);

$pdf->Cell(16);
$pdf->Cell(28, 0, $row["LOCALIDAD"]);

$pdf->Cell(14);
$pdf->Cell(26, 0, $row["TELEFONOS"]);

$pdf->Ln(4);
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect($pdf->GetX() + 128, $pdf->GetY(), 40, 4.6, "F");
$pdf->SetFillColor(0, 0, 0);

$pdf->SetFont("Arial", "B", 10);
$pdf->Ln(2.6);
$pdf->Cell(128);
$pdf->Cell(70, 0, " (".$row["DESCRIPCION"].")");

$pdf->Ln(3.6);
$pdf->Rect($pdf->GetX() + 8, $pdf->GetY(), 80, 4, "F");

$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont("Arial", "B", 7);
$pdf->Ln(2.4);
$pdf->Cell(1);
$pdf->Cell(114, 0, $row["HEADER"].": CONDICIONES A CUMPLIR", 0, 0, "C");
$pdf->SetTextColor(0, 0, 0);


// Preguntas..
$params = array(":idsolicitudestablecimiento" => $row3["SE_ID"]);
$sql =
	"SELECT ta_descripcion,
					ra_titulo,
					ia_nrodescripcion,
					ia_descripcion,
					DECODE(st_cumplimiento, 'S', 'X', '') AS sicump,
					DECODE(st_cumplimiento, 'N', 'X', '') AS nocump,
					DECODE(st_cumplimiento, 'X', 'X', '') AS noapcump,
					st_fecharegularizacion,
					ia_sololectura,
					LENGTH(NVL(ia_normativa, 0)) + LENGTH(NVL(ia_normativabis, 0)) AS largo,
					NVL(ia_normativa, '') || ' ' || NVL(ia_normativabis, '') AS articulos,
					CASE
						WHEN (LENGTH(NVL(ia_normativa, 0)) > 40) AND (LENGTH(NVL(ia_normativa, 0)) + LENGTH(NVL(ia_normativabis, 0)) > 80) THEN
							TRIM(SUBSTR(ia_normativa, 1, INSTR(ia_normativa, ' ', 39)))
						ELSE
							ia_normativa
					END AS ia_normativa,
					CASE
						WHEN (LENGTH(NVL(ia_normativa, 0)) > 40) AND (LENGTH(NVL(ia_normativa, 0)) + LENGTH(NVL(ia_normativabis, 0)) > 80) THEN
							TRIM(SUBSTR(ia_normativa, INSTR(ia_normativa, ' ', 39) + 1))
						WHEN LENGTH(NVL(ia_normativa, 0)) <= 40 THEN
							NULL
					END AS ia_normativa2,
					CASE
						WHEN LENGTH(NVL(ia_normativabis, 0)) > 40 THEN
							TRIM(SUBSTR(ia_normativabis, 1, INSTR(ia_normativabis, ' ', 39)))
						ELSE
							ia_normativabis
					END AS ia_normativabis,
					CASE
						WHEN LENGTH(NVL(ia_normativabis, 0)) > 40 THEN
							TRIM(SUBSTR(ia_normativabis, INSTR(ia_normativabis, ' ', 39) + 1))
						WHEN LENGTH(NVL(ia_normativabis, 0)) <= 40 THEN
							NULL
					END AS ia_normativabis2, ia_id
		 FROM hys.hia_itemanexo, hys.hta_titulosanexo, hys.hra_resolucionanexo, hys.hst_solicituditemsfgrl,
					hys.hsf_solicitudfgrl
		WHERE ta_id = ia_idtituloanexo
			AND st_iditem = ia_id
			AND ta_idresolucionanexo = ra_id
			AND st_idsolicitudfgrl = sf_id
			AND sf_idsolicitudestablecimiento = :idsolicitudestablecimiento
			AND sf_fechabaja IS NULL
			AND st_fechabaja IS NULL
			AND ia_fechabaja IS NULL
			AND ta_fechabaja IS NULL
			AND ra_fechabaja IS NULL
 ORDER BY 2, 3";
$stmt = DBExecSql($conn, $sql, $params);

$pdf->Ln(4.4);

$tituloAnterior = "";
while ($rowPreguntas = DBGetQuery($stmt, 1, false)) {
	if ($pdf->GetY() > 280)
		agregarPagina(1, false);

	if ($rowPreguntas["TA_DESCRIPCION"] != $tituloAnterior) {
		$tituloAnterior = $rowPreguntas["TA_DESCRIPCION"];
		dibujarTitulo();
	}

	if ($rowPreguntas["IA_DESCRIPCION"] != "")
		dibujarPregunta();
}


// Listado de Sustancias y Agentes Cancer�genos..
$params = array(":idsolicitudestablecimiento" => $row3["SE_ID"]);
$sql =
	"SELECT it_id, it_codigo, it_descripcion, it_masdatos, si_cumplimiento cumplimiento, si_id iditemformrelev,
					DECODE(si_cumplimiento, 'S', 'X', '') cump_si, DECODE(si_cumplimiento, 'N', 'X', '') cump_no, it_orden
		 FROM hys.hit_itemtipoanexo, hys.hsi_solicituditemsplanillafgrl, hys.hsp_solicitudplanillafgrl,
					hys.hsf_solicitudfgrl
		WHERE it_idtipoanexo = 1
			AND sp_idtipoanexo = 1
			AND sp_idsolicitudfgrl = sf_id
			AND sf_idsolicitudestablecimiento = :idsolicitudestablecimiento
			AND si_iditemtipoanexo = it_id
			AND si_idsolicitudplanillafgrl = sp_id
			AND sf_fechabaja IS NULL
			AND sp_fechabaja IS NULL
UNION ALL
	 SELECT it_id, it_codigo, it_descripcion, it_masdatos, NULL cumplimiento, NULL iditemformrelev, NULL cump_si,
					NULL cump_no, it_orden
		 FROM hys.hit_itemtipoanexo
		WHERE it_idtipoanexo = 1
			AND NOT EXISTS(SELECT 1
											 FROM hys.hsp_solicitudplanillafgrl, hys.hsf_solicitudfgrl
											WHERE sp_idtipoanexo = 1
												AND sp_idsolicitudfgrl = sf_id
												AND sf_idsolicitudestablecimiento = :idsolicitudestablecimiento
												AND sf_fechabaja IS NULL
												AND sp_fechabaja IS NULL)
 ORDER BY it_orden";
$stmt = DBExecSql($conn, $sql, $params);
if (DBGetRecordCount($stmt) > 0) {
	$arrCancerigenas = array();
	while ($rowPlanillaA = DBGetQuery($stmt, 1, false))
		$arrCancerigenas[] = $rowPlanillaA;
	dibujarCancerigenas($arrCancerigenas);
}


// Difenilos Policlorados..
$params = array(":idsolicitudestablecimiento" => $row3["SE_ID"]);
$sql =
	"SELECT it_id, it_codigo, it_descripcion, it_masdatos, si_cumplimiento cumplimiento, si_id iditemformrelev,
					DECODE(si_cumplimiento, 'S', 'X', '') cump_si, DECODE(si_cumplimiento, 'N', 'X', '') cump_no, it_orden
		 FROM hys.hit_itemtipoanexo, hys.hsi_solicituditemsplanillafgrl, hys.hsp_solicitudplanillafgrl, hys.hsf_solicitudfgrl
		WHERE it_idtipoanexo = 2
			AND sp_idtipoanexo = 2
			AND sp_idsolicitudfgrl = sf_id
			AND sf_idsolicitudestablecimiento = :idsolicitudestablecimiento
			AND si_iditemtipoanexo = it_id
			AND si_idsolicitudplanillafgrl = sp_id
			AND sf_fechabaja IS NULL
			AND sp_fechabaja IS NULL
UNION ALL
	 SELECT it_id, it_codigo, it_descripcion, it_masdatos, NULL cumplimiento, NULL iditemformrelev, NULL cump_si,
					NULL cump_no, it_orden
		 FROM hys.hit_itemtipoanexo
		WHERE it_idtipoanexo = 2
			AND NOT EXISTS(SELECT 1
											 FROM hys.hsp_solicitudplanillafgrl, hys.hsf_solicitudfgrl
											WHERE sp_idtipoanexo = 2
												AND sp_idsolicitudfgrl = sf_id
												AND sf_idsolicitudestablecimiento = :idsolicitudestablecimiento
												AND sf_fechabaja IS NULL
												AND sp_fechabaja IS NULL)
 ORDER BY it_orden";
$stmt = DBExecSql($conn, $sql, $params);
if (DBGetRecordCount($stmt) > 0) {
	$arrDifenilos = array();
	while ($rowPlanillaB = DBGetQuery($stmt, 1, false))
		$arrDifenilos[] = $rowPlanillaB;
	dibujarDifenilos($arrDifenilos);
}


// Listado de Sustancias Qu�micas a Declarar..
$params = array(":idsolicitudestablecimiento" => $row3["SE_ID"]);
$sql =
	"SELECT it_id, it_codigo, it_descripcion, it_masdatos, si_cumplimiento cumplimiento, si_id iditemformrelev,
					DECODE(si_cumplimiento, 'S', 'X', '') cump_si, DECODE(si_cumplimiento, 'N', 'X', '') cump_no, it_orden
		 FROM hys.hit_itemtipoanexo, hys.hsi_solicituditemsplanillafgrl, hys.hsp_solicitudplanillafgrl, hys.hsf_solicitudfgrl
		WHERE it_idtipoanexo = 3
			AND sp_idtipoanexo = 3
			AND sp_idsolicitudfgrl = sf_id
			AND sf_idsolicitudestablecimiento = :idsolicitudestablecimiento
			AND si_iditemtipoanexo = it_id
			AND si_idsolicitudplanillafgrl = sp_id
			AND sf_fechabaja IS NULL
			AND sp_fechabaja IS NULL
UNION ALL
	 SELECT it_id, it_codigo, it_descripcion, it_masdatos, NULL cumplimiento, NULL iditemformrelev, NULL cump_si,
					NULL cump_no, it_orden
		 FROM hys.hit_itemtipoanexo
		WHERE it_idtipoanexo = 3
			AND NOT EXISTS(SELECT 1
											 FROM hys.hsp_solicitudplanillafgrl, hys.hsf_solicitudfgrl
											WHERE sp_idtipoanexo = 3
												AND sp_idsolicitudfgrl = sf_id
												AND sf_idsolicitudestablecimiento = :idsolicitudestablecimiento
												AND sf_fechabaja IS NULL
												AND sp_fechabaja IS NULL)
 ORDER BY it_orden";
$stmt = DBExecSql($conn, $sql, $params);
if (DBGetRecordCount($stmt) > 0) {
	$arrQuimicas = array();
	while ($rowPlanillaC = DBGetQuery($stmt, 1, false))
		$arrQuimicas[] = $rowPlanillaC;
	dibujarQuimicas($arrQuimicas);
}


// Parte final..
if ($pdf->GetY() > 264)
	agregarPagina(5, false);

$pdf->SetFont("Arial", "B", 6);
$pdf->Ln(8);
$pdf->Cell(-5);
$pdf->Cell(136, 0, "EN CASO DE CONTAR CON DELEGADOS GREMIALES INDIQUE EL N� DE LEGAJO CONFORME A LA");
$pdf->Cell(0, 0, "EN EL CASO DE ENCOMENDAR TAREAS A CONTRATISTAS,");
$pdf->Ln(2);
$pdf->Cell(-5);
$pdf->Cell(136, 0, "INSCRIPCI�N EN EL MINISTERIO DE TRABAJO, EMPLEO Y SEGURIDAD SOCIAL.");
$pdf->Cell(0, 0, "INDICAR EL N� DE C.U.I.T. DE EL O LOS MISMOS.");

$pdf->Ln(2);
$pdf->Cell(1);
$pdf->SetFillColor(0, 0, 0);
$pdf->Rect($pdf->GetX() - 5, $pdf->GetY(), 120, 4, "F");
$pdf->Rect($pdf->GetX() + 130, $pdf->GetY(), 64, 4, "F");

$pdf->Line($pdf->GetX() - 5, $pdf->GetY() + 4.2, $pdf->GetX() - 5, $pdf->GetY() + 16);
$pdf->Line($pdf->GetX() + 32, $pdf->GetY()  + 4.2, $pdf->GetX() + 32, $pdf->GetY() + 16);
$pdf->Line($pdf->GetX() + 115, $pdf->GetY() + 4.2, $pdf->GetX() + 115, $pdf->GetY() + 16);
$pdf->Line($pdf->GetX() + 130, $pdf->GetY() + 4.2, $pdf->GetX() + 130, $pdf->GetY() + 16);
$pdf->Line($pdf->GetX() + 194, $pdf->GetY() + 4.2, $pdf->GetX() + 194, $pdf->GetY() + 16);

$pdf->Ln(4);
$pdf->Cell(1);
$pdf->Line($pdf->GetX() - 5, $pdf->GetY() + 4, $pdf->GetX() + 115, $pdf->GetY() + 4);
$pdf->Line($pdf->GetX() + 130, $pdf->GetY() + 4, $pdf->GetX() + 194, $pdf->GetY() + 4);

$pdf->Ln(4);
$pdf->Cell(1);
$pdf->Line($pdf->GetX() - 5, $pdf->GetY() + 4, $pdf->GetX() + 115, $pdf->GetY() + 4);
$pdf->Line($pdf->GetX() + 130, $pdf->GetY() + 4, $pdf->GetX() + 194, $pdf->GetY() + 4);

$pdf->Ln(4);
$pdf->Cell(1);
$pdf->Line($pdf->GetX() - 5, $pdf->GetY() + 4, $pdf->GetX() + 115, $pdf->GetY() + 4);
$pdf->Line($pdf->GetX() + 130, $pdf->GetY() + 4, $pdf->GetX() + 194, $pdf->GetY() + 4);

$pdf->SetTextColor(255, 255, 255);
$pdf->Ln(-10);
$pdf->SetFont("Arial", "B", 6);
$pdf->Cell(-1);
$pdf->Cell(60, 0, "N� DE LEGAJO DEL GREMIO");
$pdf->Cell(96, 0, "NOMBRE DEL GREMIO");
$pdf->Cell(0, 0, "N� DE C.U.I.T.");
$pdf->SetTextColor(0, 0, 0);


if ($pdf->GetY() > 256) {
	agregarPagina(5, false);
	$pdf->Ln(-16);
}

$pdf->Ln(18);
$pdf->SetFont("Arial", "B", 6);
$pdf->Cell(-2);
$pdf->Cell(0, 0, "DATOS DE LOS PROFESIONALES QUE PRESTAN SERVICIO DE HyS EN EL TRABAJO, MEDICINA LABORAL Y RESPONSABLE DE LOS DATOS DEL FORMULARIO.");

$pdf->SetFont("Arial", "", 6);
$pdf->Ln(2.4);
$pdf->Cell(-1);
$pdf->Cell(112, 0, "CARGO");
$pdf->Cell(0, 0, "REPRESENTACI�N");

$pdf->Ln(2.4);
$pdf->Cell(-1);
$pdf->Cell(112, 0, "H = Profesional de Higiene y Seguridad en el Trabajo");
$pdf->Cell(32, 0, "> Representante Legal");
$pdf->Cell(0, 0, "> Director General");

$pdf->Ln(2.4);
$pdf->Cell(-1);
$pdf->Cell(112, 0, "M = Profesional de Medicina Laboral");
$pdf->Cell(32, 0, "> Presidente");
$pdf->Cell(0, 0, "> Administrador General");

$pdf->Ln(2.4);
$pdf->Cell(-1);
$pdf->Cell(112, 0, "R = Responsable de los datos del formulario en caso que no sea ninguno de los profesionales mencionados");
$pdf->Cell(32, 0, "> Vicepresidente");
$pdf->Cell(0, 0, "> Otro");

$pdf->Ln(2.4);
$pdf->Cell(3);
$pdf->Cell(108, 0, "anteriormente de HyS o Medicina Laboral");
$pdf->Cell(0, 0, "> Gerente General");

$pdf->Ln(2);
$pdf->Cell(1);
$pdf->SetFillColor(0, 0, 0);
$pdf->Rect($pdf->GetX() - 5, $pdf->GetY(), 199, 4, "F");

// Lineas verticales..
$pdf->Line($pdf->GetX() - 5, $pdf->GetY() + 4.2, $pdf->GetX() - 5, $pdf->GetY() + 13);
$pdf->Line($pdf->GetX() + 13, $pdf->GetY()  + 4.2, $pdf->GetX() + 13, $pdf->GetY() + 13);
$pdf->Line($pdf->GetX() + 52, $pdf->GetY()  + 4.2, $pdf->GetX() + 52, $pdf->GetY() + 13);
$pdf->Line($pdf->GetX() + 65, $pdf->GetY()  + 4.2, $pdf->GetX() + 65, $pdf->GetY() + 13);
$pdf->Line($pdf->GetX() + 85, $pdf->GetY() + 4.2, $pdf->GetX() + 85, $pdf->GetY() + 13);
$pdf->Line($pdf->GetX() + 101.6, $pdf->GetY() + 4.2, $pdf->GetX() + 101.6, $pdf->GetY() + 13);
$pdf->Line($pdf->GetX() + 126.4, $pdf->GetY() + 4.2, $pdf->GetX() + 126.4, $pdf->GetY() + 13);
$pdf->Line($pdf->GetX() + 156, $pdf->GetY() + 4.2, $pdf->GetX() + 156, $pdf->GetY() + 13);
$pdf->Line($pdf->GetX() + 194, $pdf->GetY() + 4.2, $pdf->GetX() + 194, $pdf->GetY() + 13);

// Lineas horizontales..
$pdf->Ln(3);
$pdf->Cell(1);
$pdf->Line($pdf->GetX() - 5, $pdf->GetY() + 4, $pdf->GetX() + 194, $pdf->GetY() + 4);

$pdf->Ln(3);
$pdf->Cell(1);
$pdf->Line($pdf->GetX() - 5, $pdf->GetY() + 4, $pdf->GetX() + 194, $pdf->GetY() + 4);

$pdf->Ln(3);
$pdf->Cell(1);
$pdf->Line($pdf->GetX() - 5, $pdf->GetY() + 4, $pdf->GetX() + 194, $pdf->GetY() + 4);

$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont("Arial", "B", 6);
$pdf->Ln(-8);
$pdf->Cell(-4);
$pdf->Cell(18, 0, "C.U.I.T. / C.U.I.L.");
$pdf->Cell(74.4);
$pdf->Cell(12, 0, "PROPIO /", 0, 0, "C");
$pdf->Cell(57);
$pdf->Cell(38, 0, "ENTIDAD QUE OTORGO", 0, 0, "C");

$pdf->Ln(1);
$pdf->Cell(20);
$pdf->Cell(35, 0, "NOMBRE Y APELLIDO");
$pdf->Cell(10, 0, "CARGO");
$pdf->Cell(39, 0, "REPRESENTACI�N");
$pdf->Cell(30, 0, "TITULO HABITANTE");
$pdf->Cell(0, 0, "N� MATR�CULA");

$pdf->Ln(1);
$pdf->Cell(0.2);
$pdf->Cell(32, 0, "C.U.I.P.");
$pdf->Cell(53.4);
$pdf->Cell(32, 0, "CONTRATADO");
$pdf->Cell(40);
$pdf->Cell(38, 0, "EL TITULO HABILITANTE", 0, 0, "C");

$pdf->Ln(2.6);
$pdf->SetFont("Arial", "", 6);
$pdf->SetTextColor(0, 0, 0);


$params = array();
if ($row["SE_ORIGENDATO"] == 1)
$sql =
	"SELECT 'R' rr_cargo,
					'23-10382116-9' rr_cuitcuil,
					NULL rr_entidad,
					NULL rr_matricula,
					'LUIS BENITO DAMASSO' rr_nombre,
					'CONTRATADO' rr_relacion,
					NULL rr_titulo,
					'OTRO' rs_descripcion
		 FROM DUAL";
elseif ($row["SE_ORIGENDATO"] == 2)
$sql =
	"SELECT 'R' rr_cargo,
					'20-17698291-9' rr_cuitcuil,
					NULL rr_entidad,
					NULL rr_matricula,
					'RUBEN DARIO GOMEZ' rr_nombre,
					'CONTRATADO' rr_relacion,
					NULL rr_titulo,
					'OTRO' rs_descripcion
		 FROM DUAL";
elseif ($row["SE_ORIGENDATO"] == 3)
$sql =
	"SELECT 'R' rr_cargo,
					'20-14446238-7' rr_cuitcuil,
					NULL rr_entidad,
					NULL rr_matricula,
					'LUIS RODRIGUEZ' rr_nombre,
					'CONTRATADO' rr_relacion,
					NULL rr_titulo,
					'GERENTE GENERAL' rs_descripcion
		 FROM DUAL";
elseif ($row["SE_ORIGENDATO"] == 4)
$sql =
	"SELECT 'R' rr_cargo,
					'20-23903079-4' rr_cuitcuil,
					NULL rr_entidad,
					NULL rr_matricula,
					'MATIAS JOSE SANTORO' rr_nombre,
					'CONTRATADO' rr_relacion,
					NULL rr_titulo,
					'OTRO' rs_descripcion
		 FROM DUAL";
elseif ($row["SE_ORIGENDATO"] == 5)
$sql =
	"SELECT 'R' rr_cargo,
					'20-25474019-6' rr_cuitcuil,
					NULL rr_entidad,
					NULL rr_matricula,
					'LEANDRO ROBERTO IANTORNO' rr_nombre,
					'CONTRATADO' rr_relacion,
					NULL rr_titulo,
					'OTRO' rs_descripcion
		 FROM DUAL";
else
$sql =
	"SELECT 'R' rr_cargo,
					'..' rr_cuitcuil,
					NULL rr_entidad,
					NULL rr_matricula,
					'..' rr_nombre,
					'..' rr_relacion,
					NULL rr_titulo,
					'..' rs_descripcion
		 FROM DUAL";


$stmt = DBExecSql($conn, $sql, $params);
while ($rowResponsable = DBGetQuery($stmt, 1, false)) {
	$pdf->Cell(-4);
	$pdf->Cell(17, 0, $rowResponsable["RR_CUITCUIL"]);
	$pdf->Cell(1);
	$pdf->Cell(39, 0, $rowResponsable["RR_NOMBRE"]);
	$pdf->Cell(1);
	$pdf->Cell(10, 0, $rowResponsable["RR_CARGO"]);
	$pdf->Cell(2);
	$pdf->Cell(20, 0, $rowResponsable["RS_DESCRIPCION"]);
	$pdf->Cell(0.2);
	$pdf->Cell(17, 0, $rowResponsable["RR_RELACION"]);
	$pdf->Cell(-0.9);
	$pdf->Cell(26, 0, $rowResponsable["RR_TITULO"]);
	$pdf->Cell(0.2);
	$pdf->Cell(27, 0, $rowResponsable["RR_MATRICULA"]);
	$pdf->Cell(1.6);
	$pdf->Cell(37, 0, $rowResponsable["RR_ENTIDAD"]);
	$pdf->Ln(3);
}

$pdf->Ln(6);
$pdf->Cell(-5);
$pdf->Cell(0, 0, "El que suscribe en el car�cter de responsable firmante declara bajo juramento que los datos consignados en la presente son correctos y completos, y que esta declaraci�n ha sido confeccionada sin omitir");
$pdf->Ln(2.4);
$pdf->Cell(-5);
$pdf->Cell(0, 0, "ni falsear dato alguno que deba contener, siendo fiel expresi�n de la verdad.");

$pdf->Ln(8);
$pdf->SetDrawColor(0, 0, 0);
$pdf->Line($pdf->GetX() + 4, $pdf->GetY() + 4, $pdf->GetX() + 92, $pdf->GetY() + 4);
$pdf->Line($pdf->GetX() + 104, $pdf->GetY() + 4, $pdf->GetX() + 192, $pdf->GetY() + 4);

$pdf->Ln(6);
$pdf->SetFont("Arial", "B", 6);
$pdf->Cell(4);
$pdf->Cell(112, 0, "FIRMA, ACLARACI�N Y SELLO DEL RESPONSABLE DE LOS DATOS DECLARADOS");
$pdf->Cell(0, 0, "FIRMA, ACLARACI�N Y SELLO DEL RESPONSABLE DE HyS");


if ($autoPrint)
	$pdf->AutoPrint(false);
$pdf->Output("F:/Storage_Data/Prueba/tmp_rgrl/bapro/".$row3["SE_NOMBRE"]."_".$row3["SE_NROESTABLECI"].".pdf", "F");



}

ECHO "OK";
?>