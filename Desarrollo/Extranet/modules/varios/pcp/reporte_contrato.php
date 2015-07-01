<?
require_once($_SERVER["DOCUMENT_ROOT"]."../Common/miscellaneous/date_utils.php");


$params = array(":id" => $_SESSION["pcpId"]);
$sql =
	"SELECT ac1.ac_descripcion actividad1,
					ac2.ac_descripcion actividad2,
					ac3.ac_descripcion actividad3,
					co_idformulario,
					ac1.ac_codigo cod_actividad1,
					ac2.ac_codigo cod_actividad2,
					ac3.ac_codigo cod_actividad3,
					TO_CHAR(vp_vigenciadesde, 'YYYY') ano,
					UPPER(vp_calle) calle,
					(SELECT ct_contacto
						 FROM act_contacto
						WHERE em_id = ct_idempresa
							AND ct_firmante = 'S'
							AND ct_fechabaja IS NULL
							AND ROWNUM = 1) contacto,
					art.utiles.armar_cuit(vp_cuit) cuit,
					TO_CHAR(vp_vigenciadesde, 'DD') dia,
					UPPER(utiles.armar_domicilio(vp_calle, vp_numero, vp_piso, vp_departamento) || ' ' || art.utiles.armar_localidad(vp_cpostal, NULL, vp_localidad, vp_provincia)) domicilio,
					em_feinicactiv,
					UPPER(vp_localidad) localidad,
					afiliacion.get_clausulapenal montopenal,
					UPPER(vp_nombreapellido) nombreapellido,
					fo_formulario nro_formulario,
					UPPER(vp_numero) numero,
					(SELECT ct_numerodocumento
						 FROM act_contacto
						WHERE em_id = ct_idempresa
							AND ct_firmante = 'S'
							AND ct_fechabaja IS NULL
							AND ROWNUM = 1) numerodocumento,
					UPPER(pv_descripcion) provincia,
					vp_contrato,
					vp_cpostal,
					vp_departamento,
					vp_email,
					vp_piso,
					vp_telefonos,
					vp_vigenciadesde,
					vp_vigenciahasta
		 FROM afi.avp_valida_pcp, aco_contrato, aem_empresa, afo_formulario, cac_actividad ac1, cac_actividad ac2, cac_actividad ac3, cpv_provincias
		WHERE vp_contrato = co_contrato
			AND co_idempresa = em_id
			AND co_idformulario = fo_id(+)
			AND co_idactividad = ac1.ac_id
			AND co_idactividad2 = ac2.ac_id(+)
			AND co_idactividad3 = ac3.ac_id(+)
			AND vp_provincia = pv_codigo(+)
			AND vp_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$pdf = new PDF_Ellipse();
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/varios/pcp/templates/contrato.pdf");

// Página 1..
$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "B", 9);

$pdf->Ln(29);
$pdf->Cell(164);
$pdf->Cell(0, 0, $row["VP_CONTRATO"]);

$pdf->Ln(14.6);
$pdf->Cell(144);
$pdf->Cell(16, 0, $row["VP_VIGENCIADESDE"]);

$pdf->Cell(4);
$pdf->Cell(0, 0, $row["VP_VIGENCIAHASTA"]);

$pdf->Ln(9);
$pdf->Cell(12);
$pdf->Cell(140, 0, $row["NOMBREAPELLIDO"]);

$pdf->Cell(4);
$pdf->Cell(0, 0, $row["CUIT"]);

$pdf->Ln(7);
$pdf->Cell(12);
$pdf->Cell(172, 0, $row["DOMICILIO"]);

$pdf->Ln(8);
$pdf->Cell(12);
$pdf->Cell(16, 0, $row["COD_ACTIVIDAD1"]);

$pdf->Cell(1);
$pdf->Cell(152, 0, $row["ACTIVIDAD1"]);

$pdf->Ln(3.6);
$pdf->Cell(12);
$pdf->Cell(16, 0, $row["COD_ACTIVIDAD2"]);

$pdf->Cell(1);
$pdf->Cell(152, 0, $row["ACTIVIDAD2"]);

$pdf->Ln(3.6);
$pdf->Cell(12);
$pdf->Cell(16, 0, $row["COD_ACTIVIDAD3"]);

$pdf->Cell(1);
$pdf->Cell(152, 0, $row["ACTIVIDAD3"]);

$pdf->Ln(6.4);
$pdf->Cell(12);
$pdf->Cell(0, 0, $row["NRO_FORMULARIO"]);

$pdf->Ln(51.6);
$pdf->Cell(94);
$pdf->Cell(0, 0, $row["MONTOPENAL"]);

$pdf->SetFont("Arial", "", 9);
$pdf->Ln(71);
$pdf->Cell(12);
$texto = "En la Ciudad de Buenos Aires, a los ".$row["DIA"]." días del mes de ".getMonthName($row["VP_VIGENCIADESDE"])." de ".$row["ANO"].", por una parte PROVINCIA ART S.A., constituyendo domicilio en la calle Carlos Pellegrini 91, CABA, en adelante denominada \"la Aseguradora\" o \"la ART\" en forma indistinta; y por la otra ".$row["NOMBREAPELLIDO"]." C.U.I.T. Nº ".$row["CUIT"].", representada en este acto por ".$row["CONTACTO"]." Documento Nacional de Identidad Nº ".$row["NUMERODOCUMENTO"]."  (TACHAR SI NO CORRESPONDE), acreditando personería conforme documentación cuyo original exhibe y entrega copia a \"la Aseguradora\", constituyendo domicilio en la calle ".$row["DOMICILIO"].", denominado en lo sucesivo \"el EMPLEADOR\", suscriben el presente CONTRATO DE AFILIACION en el marco de lo normado por las Leyes Nº 24.557, N° 26.773, y N° 26.844, sus Decretos Reglamentarios, normas complementarias y reglamentarias dictadas por la SUPERINTENDENCIA DE RIESGOS DEL TRABAJO (S.R.T.) y por la SUPERINTENDENCIA DE SEGUROS DE LA NACION (S.S.N.) y sujetos a las siguientes cláusulas y condiciones:";
$pdf->WordWrap($texto, 168);
$texto = explode("\n", $texto);
for ($i=0; $i<count($texto); $i++) {
	if ($i > 14)
		break;

	$str = trim($texto[$i]);

	$pdf->Cell(1);
	$pdf->Cell(0, 0, $str);
	$pdf->Ln(3.4);
	$pdf->Cell(12);
}


// Menos de 12 horas..
$params = array(":idformulario" => $row["CO_IDFORMULARIO"]);
$sql =
	"SELECT ap_canttrabajador, ap_alicuota
		 FROM afi.aap_alicuotas_pcp
LEFT JOIN afi.app_parametro_pcp ON ap_idparametro_pcp = pp_id
			AND NVL(ap_fechaalta, SYSDATE) BETWEEN pp_fechadesde AND pp_fechahasta
		WHERE pp_renglon = 1
			AND ap_idformulario = :idformulario";
$stmt = DBExecSql($conn, $sql, $params);
$rowValores = DBGetQuery($stmt);

$pdf->SetY(110.4);
$pdf->Cell(80);
$pdf->Cell(36, 0, $rowValores["AP_CANTTRABAJADOR"], 0, 0, "R");

$pdf->Cell(30);
$pdf->Cell(34, 0, $rowValores["AP_ALICUOTA"], 0, 0, "R");

// Entre 12 y 16 horas..
$params = array(":idformulario" => $row["CO_IDFORMULARIO"]);
$sql =
	"SELECT ap_canttrabajador, ap_alicuota
		 FROM afi.aap_alicuotas_pcp
LEFT JOIN afi.app_parametro_pcp ON ap_idparametro_pcp = pp_id
			AND NVL(ap_fechaalta, SYSDATE) BETWEEN pp_fechadesde AND pp_fechahasta
		WHERE pp_renglon = 2
			AND ap_idformulario = :idformulario";
$stmt = DBExecSql($conn, $sql, $params);
$rowValores = DBGetQuery($stmt);

$pdf->Ln(7.4);
$pdf->Cell(80);
$pdf->Cell(36, 0, $rowValores["AP_CANTTRABAJADOR"], 0, 0, "R");

$pdf->Cell(30);
$pdf->Cell(34, 0, $rowValores["AP_ALICUOTA"], 0, 0, "R");

// Mas de 16 horas..
$params = array(":idformulario" => $row["CO_IDFORMULARIO"]);
$sql =
	"SELECT ap_canttrabajador, ap_alicuota
		 FROM afi.aap_alicuotas_pcp
LEFT JOIN afi.app_parametro_pcp ON ap_idparametro_pcp = pp_id
			AND NVL(ap_fechaalta, SYSDATE) BETWEEN pp_fechadesde AND pp_fechahasta
		WHERE pp_renglon = 3
			AND ap_idformulario = :idformulario";
$stmt = DBExecSql($conn, $sql, $params);
$rowValores = DBGetQuery($stmt);

$pdf->Ln(7.4);
$pdf->Cell(80);
$pdf->Cell(36, 0, $rowValores["AP_CANTTRABAJADOR"], 0, 0, "R");

$pdf->Cell(30);
$pdf->Cell(34, 0, $rowValores["AP_ALICUOTA"], 0, 0, "R");

// Total..
$params = array(":idformulario" => $row["CO_IDFORMULARIO"]);
$sql =
	"SELECT SUM(ap_canttrabajador) canttrabajador, SUM(ap_alicuota) alicuota
		 FROM afi.aap_alicuotas_pcp
LEFT JOIN afi.app_parametro_pcp ON ap_idparametro_pcp = pp_id
			AND NVL(ap_fechaalta, SYSDATE) BETWEEN pp_fechadesde AND pp_fechahasta
		WHERE ap_idformulario = :idformulario";
$stmt = DBExecSql($conn, $sql, $params);
$rowValores = DBGetQuery($stmt);

$pdf->Ln(7.4);
$pdf->Cell(80);
$pdf->Cell(36, 0, $rowValores["CANTTRABAJADOR"], 0, 0, "R");

$pdf->Cell(30);
$pdf->Cell(34, 0, "$ ".$rowValores["ALICUOTA"], 0, 0, "R");


$pdf->SetFont("Arial", "B", 7);
$pdf->SetY(254);
$pdf->Cell(154);
$pdf->Cell(0, 0, "ORIGINAL");

$pdf->Ln(3);
$pdf->Cell(149);
$pdf->Cell(0, 0, "PARA EL CLIENTE");


// Página 2..
$pdf->AddPage();
$tplIdx = $pdf->importPage(2);
$pdf->useTemplate($tplIdx);


// Página 3..
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/varios/pcp/templates/contrato2.pdf");
$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);

$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(24, 8, 120, 4, "F");

$pdf->SetFont("Arial", "B", 11);
$pdf->SetY(10);
$pdf->Cell(31);
$pdf->Cell(0, 0, "ANEXO DEL CONTRATO DE EMPLEADORES");

$pdf->Ln(15.6);
$pdf->Cell(75);
$pdf->Cell(0, 0, $row["VP_CONTRATO"]);
$pdf->SetFont("Arial", "B", 9);

$pdf->Ln(15);
$pdf->Cell(16);
$pdf->Cell(176, 0, $row["NOMBREAPELLIDO"]);

$pdf->Ln(6);
$pdf->Cell(18);
$pdf->Cell(80, 0, $row["CUIT"]);

$pdf->Cell(76);
$pdf->Cell(0, 0, $row["EM_FEINICACTIV"]);

$pdf->Ln(6);
$pdf->Cell(16);
$pdf->Cell(176, 0, $row["COD_ACTIVIDAD1"]." ".$row["ACTIVIDAD1"]);

$pdf->Ln(11.4);
$pdf->Cell(12);
$pdf->Cell(116, 0, $row["CALLE"]);

$pdf->Cell(8);
$pdf->Cell(12, 0, $row["NUMERO"]);

$pdf->Cell(12);
$pdf->Cell(9, 0, $row["VP_PISO"]);

$pdf->Cell(13);
$pdf->Cell(10, 0, $row["VP_DEPARTAMENTO"]);

$pdf->Ln(6);
$pdf->Cell(16);
$pdf->Cell(48, 0, $row["LOCALIDAD"]);

$pdf->Cell(18);
$pdf->Cell(45, 0, $row["PROVINCIA"]);

$pdf->Cell(40);
$pdf->Cell(0, 0, $row["VP_CPOSTAL"]);

$pdf->Ln(5.4);
$pdf->Cell(12);
$pdf->Cell(114, 0, $row["VP_EMAIL"]);

$pdf->Cell(28);
$pdf->Cell(36, 0, $row["VP_TELEFONOS"]);

$params = array(":id_valida_pcp" => $_SESSION["pcpId"]);
$sql =
	"SELECT a.*, UPPER(vl_localidad) localidad, pv_descripcion provincia
		 FROM afi.avl_valida_lugartrabajo_pcp a, cpv_provincias
		WHERE vl_id_valida_pcp = :id_valida_pcp
			AND vl_provincia = pv_codigo(+)
 ORDER BY 1";
$stmt = DBExecSql($conn, $sql, $params);
$rowLugaresTrabajo = DBGetQuery($stmt);

$pdf->Ln(19.2);
$pdf->Cell(12);
$pdf->Cell(116, 0, strtoupper($rowLugaresTrabajo["VL_CALLE"]));

$pdf->Cell(8);
$pdf->Cell(12, 0, strtoupper($rowLugaresTrabajo["VL_NUMERO"]));

$pdf->Cell(12);
$pdf->Cell(9, 0, $rowLugaresTrabajo["VL_PISO"]);

$pdf->Cell(13);
$pdf->Cell(10, 0, $rowLugaresTrabajo["VL_DEPARTAMENTO"]);

$pdf->Ln(5.8);
$pdf->Cell(16);
$pdf->Cell(48, 0, $rowLugaresTrabajo["LOCALIDAD"]);

$pdf->Cell(18);
$pdf->Cell(45, 0, $rowLugaresTrabajo["PROVINCIA"]);

$pdf->Cell(40);
$pdf->Cell(0, 0, $rowLugaresTrabajo["VL_CPOSTAL"]);

$params = array(":idformulario" => $row["CO_IDFORMULARIO"]);
$sql =
	"SELECT SUM(ap_canttrabajador)
		 FROM afi.aap_alicuotas_pcp
LEFT JOIN afi.app_parametro_pcp ON ap_idparametro_pcp = pp_id
		WHERE ap_idformulario = :idformulario";
$pdf->Ln(5.8);
$pdf->Cell(44);
$pdf->Cell(16, 0, valorSql($sql, 0, $params));

$pdf->Cell(28);
$pdf->Cell(104, 0, $rowLugaresTrabajo["VL_TELEFONOS"]);


if ($rowLugaresTrabajo = DBGetQuery($stmt)) {
	$pdf->Ln(12.2);
	$pdf->Cell(12);
	$pdf->Cell(116, 0, strtoupper($rowLugaresTrabajo["VL_CALLE"]));

	$pdf->Cell(8);
	$pdf->Cell(12, 0, strtoupper($rowLugaresTrabajo["VL_NUMERO"]));

	$pdf->Cell(12);
	$pdf->Cell(9, 0, $rowLugaresTrabajo["VL_PISO"]);

	$pdf->Cell(13);
	$pdf->Cell(10, 0, $rowLugaresTrabajo["VL_DEPARTAMENTO"]);

	$pdf->Ln(5.8);
	$pdf->Cell(16);
	$pdf->Cell(48, 0, $rowLugaresTrabajo["LOCALIDAD"]);

	$pdf->Cell(18);
	$pdf->Cell(45, 0, $rowLugaresTrabajo["PROVINCIA"]);

	$pdf->Cell(40);
	$pdf->Cell(0, 0, $rowLugaresTrabajo["VL_CPOSTAL"]);

	$pdf->Ln(5.8);
	$pdf->Cell(88);
	$pdf->Cell(104, 0, $rowLugaresTrabajo["VL_TELEFONOS"]);
}


$params = array(":id_valida_pcp" => $_SESSION["pcpId"]);
$sql =
	"SELECT *
		 FROM afi.avr_valida_riesgo_pcp
		WHERE vr_id_valida_pcp = :id_valida_pcp";
$stmt = DBExecSql($conn, $sql, $params);
$rowRiesgo = DBGetQuery($stmt);

$pdf->SetFont("Arial", "", 9);
$pdf->SetY(142.4);
$pdf->Cell(44);
$texto = $rowRiesgo["VR_DESCRIPCION"];
$pdf->WordWrap($texto, 144);
$texto = explode("\n", $texto);
for ($i=0; $i<count($texto); $i++) {
	if ($i > 1)
		break;

	$str = trim($texto[$i]);

	$pdf->Cell(1);
	$pdf->Cell(0, 0, $str);
	$pdf->Ln(5);
	$pdf->Cell(44);
}

$pdf->SetY(157.6);
($rowRiesgo["VR_ELECTRICO"] == "S")?$pdf->Cell(129.9):$pdf->Cell(144);
$pdf->Cell(0, 0, "X");

$pdf->Ln(8.4);
($rowRiesgo["VR_INCENDIO"] == "S")?$pdf->Cell(129.9):$pdf->Cell(144);
$pdf->Cell(0, 0, "X");

$pdf->Ln(4.5);
switch ($rowRiesgo["VR_EXTINTOR"]) {
	case 1:
		$pdf->Cell(51.6);
		break;
	case 2:
		$pdf->Cell(103);
		break;
	case 3:
		$pdf->Cell(137);
		break;
}
$pdf->Cell(0, 0, "X");

$pdf->SetX(156);
$pdf->Cell(44, 0, $rowRiesgo["VR_EXTINTOR_CUAL"]);

$pdf->Ln(8.2);
($rowRiesgo["VR_INSECTICIDA"] == "S")?$pdf->Cell(62):$pdf->Cell(75.4);
$pdf->Cell(0, 0, "X");

$pdf->SetX(108);
$pdf->Cell(92, 0, $rowRiesgo["VR_INSECTICIDA_CUAL"]);

$pdf->Ln(4);
($rowRiesgo["VR_BENCINA"] == "S")?$pdf->Cell(62):$pdf->Cell(75.4);
$pdf->Cell(0, 0, "X");

$pdf->Ln(4);
($rowRiesgo["VR_RATICIDA"] == "S")?$pdf->Cell(62):$pdf->Cell(75.4);
$pdf->Cell(0, 0, "X");

$pdf->SetX(108);
$pdf->Cell(92, 0, $rowRiesgo["VR_RATICIDA_CUAL"]);

$pdf->Ln(4);
($rowRiesgo["VR_DESINFECTANTES"] == "S")?$pdf->Cell(62):$pdf->Cell(75.4);
$pdf->Cell(0, 0, "X");

$pdf->Ln(4);
($rowRiesgo["VR_DETERGENTES"] == "S")?$pdf->Cell(62):$pdf->Cell(75.4);
$pdf->Cell(0, 0, "X");

$pdf->Ln(4);
($rowRiesgo["VR_SODACAUSTICA"] == "S")?$pdf->Cell(62):$pdf->Cell(75.4);
$pdf->Cell(0, 0, "X");

$pdf->Ln(4);
($rowRiesgo["VR_DESENGRASANTE"] == "S")?$pdf->Cell(62):$pdf->Cell(75.4);
$pdf->Cell(0, 0, "X");

$pdf->Ln(4);
($rowRiesgo["VR_HIPOCLORITODESODIO"] == "S")?$pdf->Cell(62):$pdf->Cell(75.4);
$pdf->Cell(0, 0, "X");

$pdf->Ln(3.8);
($rowRiesgo["VR_AMONIACO"] == "S")?$pdf->Cell(62):$pdf->Cell(75.4);
$pdf->Cell(0, 0, "X");

$pdf->Ln(4);
($rowRiesgo["VR_ACIDOMURIATICO"] == "S")?$pdf->Cell(62):$pdf->Cell(75.4);
$pdf->Cell(0, 0, "X");

$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(24, 216.8, 120, 3, "F");

$pdf->SetY(218.4);
$pdf->Cell(12);
$texto = $rowRiesgo["VR_OTRORIESGOQUIMICO"];
$pdf->WordWrap($texto, 160);
$texto = explode("\n", $texto);
for ($i=0; $i<count($texto); $i++) {
	if ($i > 1)
		break;

	$str = trim($texto[$i]);

	$pdf->Cell(1);
	$pdf->Cell(0, 0, $str);
	$pdf->Ln(4);
	$pdf->Cell(12);
}

$pdf->SetY(232.4);
($rowRiesgo["VR_PROTECCIONBALCONES"] == "S")?$pdf->Cell(101.7):$pdf->Cell(117.8);
$pdf->Cell(0, 0, "X");

$pdf->Ln(5.2);
($rowRiesgo["VR_INTERIORALTURA"] == "S")?$pdf->Cell(101.7):$pdf->Cell(117.8);
$pdf->Cell(0, 0, "X");

$pdf->SetX(145);
$pdf->Cell(54, 0, $rowRiesgo["VR_INTERIORALTURA_CUAL"]);

$pdf->Ln(5);
($rowRiesgo["VR_EXTERIORALTURA"] == "S")?$pdf->Cell(101.7):$pdf->Cell(117.8);
$pdf->Cell(0, 0, "X");

$pdf->SetX(145);
$pdf->Cell(54, 0, $rowRiesgo["VR_EXTERIORALTURA_CUAL"]);

$pdf->Ln(6);
($rowRiesgo["VR_ESCALERABARANDA"] == "S")?$pdf->Cell(101.7):$pdf->Cell(117.8);
$pdf->Cell(0, 0, "X");

$pdf->Ln(9);
($rowRiesgo["VR_INDUMENTARIA"] == "S")?$pdf->Cell(101.7):$pdf->Cell(117.8);
$pdf->Cell(0, 0, "X");

$pdf->SetX(145);
$pdf->Cell(54, 0, $rowRiesgo["VR_INDUMENTARIA_CUAL"]);

$pdf->Ln(6);
($rowRiesgo["VR_PROTECCIONPERSONAL"] == "S")?$pdf->Cell(101.7):$pdf->Cell(117.8);
$pdf->Cell(0, 0, "X");

$pdf->SetX(145);
$pdf->Cell(54, 0, $rowRiesgo["VR_PROTECCIONPERSONAL_CUAL"]);

$pdf->SetFillColor(0, 0, 0);
$pdf->Rect(112, 230.6, 3.6, 3.6);
$pdf->Rect(128, 230.6, 3.6, 3.6);
$pdf->Rect(112, 235.6, 3.6, 3.6);
$pdf->Rect(128, 235.6, 3.6, 3.6);
$pdf->Rect(112, 240.6, 3.6, 3.6);
$pdf->Rect(128, 240.6, 3.6, 3.6);
$pdf->Rect(112, 246.6, 3.6, 3.6);
$pdf->Rect(128, 246.6, 3.6, 3.6);
$pdf->Rect(112, 255.6, 3.6, 3.6);
$pdf->Rect(128, 255.6, 3.6, 3.6);
$pdf->Rect(112, 261.6, 3.6, 3.6);
$pdf->Rect(128, 261.6, 3.6, 3.6);


// Actualizo la fecha de impresión..
$params = array(":id" => $_SESSION["pcpId"]);
$sql =
	"UPDATE afi.avp_valida_pcp
			SET vp_fechaimpresion = SYSDATE
		WHERE vp_fechaimpresion IS NULL
			AND vp_id = :id";
DBExecSql($conn, $sql, $params);

$pdf->Output();
?>