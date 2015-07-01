<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function dibujarParrafo($texto, $salto) {
	global $pdf;

	$pdf->Ln($salto);
	$pdf->WordWrap($texto, 164);
	$texto = explode("\n", $texto);
	$maxLineas = (count($texto) > 4)?4:count($texto);
	for ($j=0; $j<$maxLineas; $j++) {
		$str = trim($texto[$j]);

		$pdf->Cell(16);
		$pdf->Cell(168, 0, $str);
		$pdf->Ln(3.8);
	}

	return (4 - $maxLineas);
}


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));

try {
	// Traigo los datos del empleador..
	$params = array(":contrato" => $_SESSION["contrato"]);
	$sql =
		"SELECT ac_codigo ciiu, NVL(dc_cpostala, dc_cpostal) codigopostal, dc_calle domicilio, dc_mail email, dc_fax fax, dc_localidad localidad, dc_numero numero, dc_departamento oficina,
						dc_piso piso, pv_descripcion provincia, art.afi.get_telefonos('ATO_TELEFONOCONTRATO', dc_id, 'L') telefono
			 FROM aco_contrato, aem_empresa, adc_domiciliocontrato, cac_actividad, cpv_provincias
			WHERE co_idempresa = em_id
				AND co_contrato = dc_contrato
				AND co_idactividad = ac_id
				AND dc_provincia = pv_codigo
				AND dc_tipo = 'L'
				AND co_contrato = :contrato";
	$stmt = DBExecSql($conn, $sql, $params);
	$rowEmpleador = DBGetQuery($stmt, 1, false);

	// Armo el sql principal..
	$curs = null;
	$params = array(":idtransaccion" => $_REQUEST["id"]);
	$sql = "BEGIN art.webart.get_denuncia_siniestro(:data, :idtransaccion); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs, false);

	//	*******  INICIO - Armado del reporte..  *******
	$pdf = new FPDI();

	$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/denuncias_de_siniestros/templates/denuncia_siniestro.pdf");
	$pdf->AddPage();
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx);
	$pdf->SetFont("Arial", "", 8);

	$pdf->Ln(15);
	switch ($row["EW_TIPOSINIESTRO"]) {
		case 1:
			$pdf->Cell(81);
			$pdf->Cell(0, 0, "X");
			break;
		case 2:
			$pdf->Cell(113);
			$pdf->Cell(0, 0, "X");
			break;
		case 3:
			$pdf->Cell(45.2);
			$pdf->Cell(0, 0, "X");
			break;
	}

	$pdf->Ln(4);
	$pdf->Cell(154);
	$pdf->Cell(0, 0, str_replace("/", "     ", $row["EW_FECHASIN"]));

	$pdf->Ln(0.3);
	switch ($row["EW_LUGAROCURRENCIA"]) {
		case 1:
			$pdf->Cell(45.2);
			$pdf->Cell(0, 0, "X");
			break;
		case 3:
			$pdf->Cell(113);
			$pdf->Cell(0, 0, "X");
			break;
	}

	$pdf->Ln(4.4);
	$pdf->Cell(81);
	if ($row["EW_MULTIPLE"] == 1)
		$pdf->Cell(8, 0, "X");
	else
		$pdf->Cell(8, 0, "");

	$pdf->Cell(22);
	$pdf->Cell(24, 0, $row["EW_EPMANIFESTACION"]);

	if ($row["EW_LUGAROCURRENCIA"] == 2) {
		$pdf->Cell(23.8);
		$pdf->Cell(0, 0, "X");
	}

	if (($row["EW_LUGAROCURRENCIA"] != 1) and ($row["EW_LUGAROCURRENCIA"] != 2) and ($row["EW_LUGAROCURRENCIA"] != 3)) {
		$pdf->Cell(41);
		$pdf->Cell(0, 0, "X");
	}


	// Datos del empleador..
	$pdf->Ln(11.4);
	$pdf->Cell(30);
	$pdf->Cell(54, 0, $_SESSION["empresa"]);

	$pdf->Cell(8);
	$pdf->Cell(24, 0, ponerGuiones($_SESSION["cuit"]));

	$pdf->Cell(14);
	$pdf->Cell(14, 0, $_SESSION["contrato"]);

	$pdf->Cell(10);
	$pdf->Cell(0, 0, $rowEmpleador["CIIU"]);

	$pdf->Ln(4);
	$pdf->Cell(27);
	$pdf->Cell(84, 0, $rowEmpleador["DOMICILIO"]);

	$pdf->Cell(4);
	$pdf->Cell(18, 0, $rowEmpleador["NUMERO"]);

	$pdf->Cell(7);
	$pdf->Cell(11, 0, $rowEmpleador["PISO"]);

	$pdf->Cell(6);
	$pdf->Cell(20, 0, $rowEmpleador["OFICINA"]);

	$pdf->Ln(3.7);
	$pdf->Cell(32);
	$pdf->Cell(28, 0, $rowEmpleador["CODIGOPOSTAL"]);

	$pdf->Cell(13);
	$pdf->Cell(35, 0, $rowEmpleador["LOCALIDAD"]);

	$pdf->Cell(11);
	$pdf->Cell(60, 0, $rowEmpleador["PROVINCIA"]);

	$pdf->Ln(3.7);
	$pdf->Cell(27);
	$pdf->Cell(51, 0, $rowEmpleador["TELEFONO"]);

	$pdf->Cell(6);
	$pdf->Cell(42, 0, $rowEmpleador["FAX"]);

	$pdf->Cell(8);
	$pdf->Cell(46, 0, $rowEmpleador["EMAIL"]);

	$pdf->Ln(4);
	$pdf->Cell(113);
//	$pdf->Cell(67, 0, $row["ESTABLECIMIENTOPROPIO"]);
	$pdf->Cell(67, 0, $row["EW_OTROLUGAR"]);

	$pdf->Ln(3.6);
	$pdf->Cell(44);
	$pdf->Cell(80, 0, $row["CODIGOESTABLECIMIENTOPROPIO"]);

	$pdf->Cell(12);
	$pdf->Cell(44, 0, "");

	$pdf->Ln(3.7);
	if (false)
		$pdf->Cell(47.4);
	else
		$pdf->Cell(58.3);
	$pdf->Cell(4, 0, "X");

	$pdf->Cell(40);
	$pdf->Cell(72, 0, $row["EW_CUITCONTRATISTA"]);

	$pdf->Ln(3.7);
	$pdf->Cell(23);
	$pdf->Cell(72, 0, $row["EW_LUGARCALLE"]);

	$pdf->Cell(4);
	$pdf->Cell(20, 0, $row["EW_LUGARNRO"]);

	$pdf->Cell(11);
	$pdf->Cell(50, 0, $row["EW_LUGARLOCALIDAD"]);

	$pdf->Ln(3.7);
	$pdf->Cell(52);
	$pdf->Cell(62, 0, $row["PROVINCIAACCIDENTE"]);

	$pdf->Cell(15);
	$pdf->Cell(50, 0, $row["EW_LUGARCPOSTAL"]);

	// Datos del trabajador..
	$pdf->Ln(12);
	$pdf->Cell(35);
	$pdf->Cell(78, 0, $row["JW_NOMBRE"]);

	$pdf->Cell(29);
	$pdf->Cell(38, 0, $row["JW_DOCUMENTO"]);

	$pdf->Ln(3.7);
	$pdf->Cell(29);
	$pdf->Cell(34, 0, $row["JW_DOCUMENTO"]);

	$pdf->Cell(25.5);
	$pdf->Cell(24, 0, str_replace("/", "     ", $row["JW_FEC_NACIMIENTO"]));

	$pdf->Cell(8.8);
	if ($row["JW_FEC_NACIMIENTO"] == "F")
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Cell(3.2);
	if ($row["JW_FEC_NACIMIENTO"] == "M")
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Cell(15);
	$pdf->Cell(32, 0, $row["NACIONALIDAD"]);

	$pdf->Ln(4.1);
	$pdf->Cell(38);
	if ($row["JW_ESTCIVIL"] == "S")
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Cell(6.8);
	if ($row["JW_ESTCIVIL"] == "C")
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Cell(5.7);
	if ($row["JW_ESTCIVIL"] == "V")
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Cell(9.8);
	if ($row["JW_ESTCIVIL"] == "D")
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Cell(8.7);
	if ($row["JW_ESTCIVIL"] == "E")
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Cell(11.7);
	if ($row["JW_ESTCIVIL"] == "H")
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Cell(7);
	$pdf->Cell(68, 0, $row["JW_CALLE"]);

	$pdf->Ln(3.8);
	$pdf->Cell(19);
	$pdf->Cell(13, 0, $row["JW_NUMERO"]);

	$pdf->Cell(5);
	$pdf->Cell(8, 0, $row["JW_PISO"]);

	$pdf->Cell(6);
	$pdf->Cell(9, 0, $row["JW_DEPARTAMENTO"]);

	$pdf->Cell(9);
	$pdf->Cell(31, 0, $row["JW_LOCALIDAD"]);

	$pdf->Cell(14);
	$pdf->Cell(18, 0, $row["JW_CODPOSTAL"]);

	$pdf->Cell(9);
	$pdf->Cell(39, 0, $row["PROVINCIATRABAJADOR"]);

	$pdf->Ln(3.6);
	$pdf->Cell(25);
	$pdf->Cell(34, 0, $row["JW_TELEFONO"]);

	$pdf->Cell(29.5);
	$pdf->Cell(26, 0, str_replace("/", "     ", $row["JW_FEC_INGRESO"]));

	$pdf->Ln(6.5);
	$pdf->Cell(116.5);
	$pdf->Cell(10, 0, $row["JW_HORARIOINICIO"]);

	$pdf->Cell(11.5);
	$pdf->Cell(10, 0, $row["JW_HORARIOFIN"]);

	$pdf->Cell(21.6);
	if ($row["EW_MANOHABIL"] != "D")
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Ln(3.6);
	$pdf->Cell(169.8);
	if ($row["EW_MANOHABIL"] != "I")
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Ln(5);
	$pdf->Cell(37);
	$pdf->Cell(0, 0, "");		// Situación contractual..

	$pdf->Ln(4);
	$pdf->Cell(27);
	$pdf->Cell(0, 0, "");		// Obra social..

	$pdf->Ln(3.7);
	$pdf->Cell(103);
	$pdf->Cell(37, 0, $row["JW_PUESTO"]);		// Puesto de trabajo..

	$pdf->Ln(3.7);
	$pdf->Cell(55);
	$pdf->Cell(0, 0, "");		// Antiguedad..

	$pdf->Ln(3.5);
	$pdf->Cell(79.4);
	$pdf->Cell(0, 0, "X");		// Otro empleador..

	// Información sobre el siniestro..
	$pdf->Ln(12);
	$pdf->Cell(35);
	$pdf->Cell(12, 0, $row["EW_HORASIN"]);

	$pdf->SetFont("Arial", "", 7);
	$pdf->Cell(53);
	$pdf->Cell(10, 0, $row["EW_HORJORNADADESDE"]);

	$pdf->Cell(8.6);
	$pdf->Cell(10, 0, $row["EW_HORJORNADAHASTA"]);
	$pdf->SetFont("Arial", "", 8);

	$pdf->Ln(4.2);
	$pdf->Cell(69.8);
	if ($row["EW_LUGAROCURRENCIA"] != 5)
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Cell(7);
	$pdf->Cell(62, 0, $row["EW_LUGARCALLE"]);

	$pdf->Cell(14);
	$pdf->Cell(23, 0, $row["EW_LUGARNRO"]);

	$pdf->Ln(3.8);
	$pdf->Cell(69.8);
	if ($row["EW_LUGAROCURRENCIA"] == 5)
		$pdf->Cell(4, 0, "X");
	else
		$pdf->Cell(4, 0, "");

	$pdf->Ln(3.4);
	$pdf->Cell(30);
	$pdf->Cell(30, 0, $row["EW_LUGARCPOSTAL"]);

	$pdf->Cell(9);
	$pdf->Cell(60, 0, $row["EW_LUGARLOCALIDAD"]);

	$pdf->Cell(9);
	$pdf->Cell(42, 0, $row["PROVINCIAACCIDENTE"]);

	$lineas = dibujarParrafo("_____________________________ ".$row["EW_DESCRIPCION"], 4);

	$pdf->Ln((3.8 * $lineas) + 2);
	switch ($row["EW_GRAVEDAD"]) {
		case 1:
		case 2:
			$pdf->Cell(140);
			$pdf->Cell(0, 0, "X");
			break;
		case 3:
		case 4:
			$pdf->Cell(154.2);
			$pdf->Cell(0, 0, "X");
			break;
		case 5:
			$pdf->Cell(169.3);
			$pdf->Cell(0, 0, "X");
			break;
	}


	while (strlen($row["CODIGOAGENTEMATERIAL"]) < 5)
		$row["CODIGOAGENTEMATERIAL"].= " ".$row["CODIGOAGENTEMATERIAL"];
	$pdf->Ln(4.2);
	$pdf->Cell(43.5);
	$pdf->Cell(4, 0, substr($row["CODIGOAGENTEMATERIAL"], 0, 1));

	$pdf->Cell(-1.2);
	$pdf->Cell(4, 0, substr($row["CODIGOAGENTEMATERIAL"], 1, 1));

	$pdf->Cell(-1.2);
	$pdf->Cell(4, 0, substr($row["CODIGOAGENTEMATERIAL"], 2, 1));

	$pdf->Cell(-1.2);
	$pdf->Cell(4, 0, substr($row["CODIGOAGENTEMATERIAL"], 3, 1));

	$pdf->Cell(-1.2);
	$pdf->Cell(4, 0, substr($row["CODIGOAGENTEMATERIAL"], 4, 1));


	while (strlen($row["CODIGONATURALEZALESION"]) < 2)
		$row["CODIGONATURALEZALESION"].= " ".$row["CODIGONATURALEZALESION"];
	$pdf->Ln(3.5);
	$pdf->Cell(125.5);
	$pdf->Cell(4, 0, substr($row["CODIGONATURALEZALESION"], 0, 1));

	$pdf->Cell(-1.2);
	$pdf->Cell(4, 0, substr($row["CODIGONATURALEZALESION"], 1, 1));

	while (strlen($row["CODIGOFORMAACCIDENTE"]) < 3)
		$row["CODIGOFORMAACCIDENTE"].= " ".$row["CODIGOFORMAACCIDENTE"];
	$pdf->Ln(3.6);
	$pdf->Cell(43.5);
	$pdf->Cell(4, 0, substr($row["CODIGOFORMAACCIDENTE"], 0, 1));

	$pdf->Cell(-1.2);
	$pdf->Cell(4, 0, substr($row["CODIGOFORMAACCIDENTE"], 1, 1));

	$pdf->Cell(-1.2);
	$pdf->Cell(4, 0, substr($row["CODIGOFORMAACCIDENTE"], 2, 1));


	while (strlen($row["CODIGOZONA"]) < 3)
		$row["CODIGOZONA"].= " ".$row["CODIGOZONA"];
	$pdf->Cell(69.7);
	$pdf->Cell(4, 0, substr($row["CODIGOZONA"], 0, 1));

	$pdf->Cell(-1.2);
	$pdf->Cell(4, 0, substr($row["CODIGOZONA"], 1, 1));

	$pdf->Cell(-1.2);
	$pdf->Cell(4, 0, substr($row["CODIGOZONA"], 2, 1));



	$pdf->Ln(52.8);
	$pdf->SetFont("Arial", "", 7);
	if ($row["EW_PRESTADORID"] == "") {
		$pdf->Cell(33);
		$pdf->Cell(25, 0, $row["EW_PRESTADORNOMBRE"]);

		$pdf->Cell(10);
		$pdf->Cell(36, 0, $row["EW_PRESTADORDOMICILIO"]);

		$pdf->Cell(61);
		$pdf->Cell(16, 0, $row["EW_PRESTADORTELEFONO"]);
	}
	else {
		$pdf->Cell(33);
		$pdf->Cell(25, 0, $row["NOMBREPRESTADOR"]);

		$pdf->Cell(10);
		$pdf->Cell(36, 0, $row["DOMICILIOPRESTADOR"]);

		$pdf->Cell(13.6);
		$pdf->Cell(11, 0, $row["CPPRESTADOR"], 0, 0, "C");

		$pdf->Cell(9);
		$pdf->Cell(25, 0, $row["LOCALIDADPRESTADOR"]);

		$pdf->Cell(2);
		$pdf->Cell(16, 0, $row["TELEFONOPRESTADOR"]);
	}
	$pdf->SetFont("Arial", "", 8);

	$pdf->Ln(19.6);
	$pdf->Cell(15);
	$pdf->Cell(24, 0, $row["EW_LUGARDENUNCIA"].(($row["EW_LUGARDENUNCIA"] == "")?"":", "));
	$pdf->Cell(18, 0, $row["EW_FECHADENUNCIA"]);

	$pdf->Cell(84);
	$pdf->Cell(40, 0, $row["EW_DENUNCIANTE"].(($row["EW_DNIDENUNCIANTE"] == "")?"":" - ".$row["EW_DNIDENUNCIANTE"]));

	$pdf->Output("Denuncia_de_Siniestro.pdf", "I");
	//	*******  FIN - Armado del reporte..  *******
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>