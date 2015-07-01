<?
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_cotizacion/plantillas/carta_cotizacion_formato_".$tipoReporte.".pdf");
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);

$pdf->AddPage();		// Página 1..
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "B", 9);

$pdf->Ln(-1.4);
$pdf->Cell(3.4);
$pdf->Cell(0, 0, $row["FECHA"]);

$pdf->Ln(25.8);
$pdf->Cell(16);
$pdf->Cell(32, 0, $row["CUIT"]);

$pdf->Cell(42);
$pdf->Cell(108, 0, $row["RAZONSOCIAL"]);

$pdf->Ln(5);
$pdf->Cell(108);
$pdf->Cell(0, 0, $row["CIIU"]);

$pdf->Ln(5);
$pdf->Cell(36);
$pdf->Cell(156, 0, $row["DESCRIPCIONCIIU"]);

$pdf->Ln(12.2);
$pdf->Cell(44);
$pdf->Cell(12, 0, $row["CANTIDADTRABAJADORES"]);

$pdf->Cell(72);
$pdf->Cell(0, 0, $row["MASASALARIAL"]);

$pdf->Ln(26);
$pdf->Cell(-1);
$pdf->Cell(28, 0, $row["SUMAFIJATRABAJADOR"], 0, 0, "R");

$pdf->Cell(12);
$pdf->Cell(16, 0, "$ 0,60");

$pdf->Cell(24);
$pdf->Cell(0, 0, $row["PORCENTAJEVARIABLETRABAJADOR"]."%");


// INICIO - Cuadro alícuota PCP..
$pdf->Ln(37.4);
$params = array(":idsolicitudcotizacion" => $id);
$sql =
	"SELECT NVL((SELECT cp_alicuota
								 FROM afi.acp_cotizacion_pcp
								WHERE cp_idparametro_pcp = pp_id
									AND cp_idsolicitudcotizacion = :idsolicitudcotizacion), 0) alicuota,
					TO_CHAR(NVL((SELECT cp_alicuota
												 FROM afi.acp_cotizacion_pcp
												WHERE cp_idparametro_pcp = pp_id
													AND cp_idsolicitudcotizacion = :idsolicitudcotizacion), 0), '$99,999,990.00') alicuotaformateada,
					NVL((SELECT cp_canttrabajador
								 FROM afi.acp_cotizacion_pcp
								WHERE cp_idparametro_pcp = pp_id
									AND cp_idsolicitudcotizacion = :idsolicitudcotizacion), 0) canttrabajador
		 FROM afi.app_parametro_pcp
		WHERE art.actualdate BETWEEN pp_fechadesde AND pp_fechahasta
			AND pp_fechabaja IS NULL
 ORDER BY pp_renglon";
$stmt = DBExecSql($conn, $sql, $params);
$totalAlicuota = 0;
$totalTrabajadores = 0;
while ($rowPCP = DBGetQuery($stmt, 1, false)) {
	$totalAlicuota+= ($rowPCP["ALICUOTA"]);
	$totalTrabajadores+= $rowPCP["CANTTRABAJADOR"];

	$pdf->Ln(4.6);
	$pdf->Cell(76);
	$pdf->Cell(24, 0, $rowPCP["CANTTRABAJADOR"], 0, 0, "R");

	$pdf->Cell(48);
	$pdf->Cell(24, 0, ($rowPCP["CANTTRABAJADOR"] == 0)?"-":$rowPCP["ALICUOTAFORMATEADA"], 0, 0, "R");
}

$pdf->Ln(4.4);
$pdf->Cell(76);
$pdf->Cell(24, 0, $totalTrabajadores, 0, 0, "R");

$pdf->Cell(48);
$pdf->Cell(24, 0, ($totalAlicuota == 0)?"-":number_format($totalAlicuota, 2), 0, 0, "R");
// FIN - Cuadro alícuota PCP..


$pdf->Ln(18);
$pdf->Cell(34);
$pdf->Cell(32, 0, $row["CUOTAANUAL"], 0, 0, "R");

$pdf->Ln(4.4);
$pdf->Cell(34);
$pdf->Cell(32, 0, $row["CUOTAMENSUAL"], 0, 0, "R");

$pdf->Ln(5.4);
$pdf->Cell(34);
$pdf->Cell(32, 0, $row["CUOTATRABAJADOR"], 0, 0, "R");

$pdf->SetFont("Arial", "B", 8);
$pdf->Ln(-6.2);
$pdf->Cell(152);
$pdf->Cell(0, 0, number_format($row["CLAUSULAPENAL"], 0, ",", "."));

$pdf->Ln(3.2);
$pdf->Cell(89);
$pdf->Cell(76, 0, numerosALetras($row["CLAUSULAPENAL"]));
$pdf->SetFont("Arial", "B", 10);


$pdf->AddPage();		// Página 2..
$tplIdx = $pdf->importPage(2);
$pdf->useTemplate($tplIdx);

$pdf->Ln(-1.4);
$pdf->Cell(3.4);
$pdf->Cell(0, 0, $row["FECHA"]);

$pdf->Ln(25.8);
$pdf->Cell(16);
$pdf->Cell(32, 0, $row["CUIT"]);

$pdf->Cell(42);
$pdf->Cell(108, 0, $row["RAZONSOCIAL"]);

$pdf->Ln(27);
$pdf->Cell(40);
$pdf->Cell(48, 0, $row["VALORRC"], 0, 0, "R");

$pdf->Ln(8);
$pdf->Cell(40);
$pdf->Cell(48, 0, $row["CUOTAINICIALRC"], 0, 0, "R");

$pdf->Ln(8);
$pdf->Cell(40);
$pdf->Cell(48, 0, $row["SUMAASEGURADARC"], 0, 0, "R");


$pdf->Output($file, "F");
?>