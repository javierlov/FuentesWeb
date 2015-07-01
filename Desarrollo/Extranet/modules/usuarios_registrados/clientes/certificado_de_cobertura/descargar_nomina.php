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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getClausulas($tipoCertificado, $deudaContrato, $esTraspaso, $fechaImpresion, $nroCertificado) {
	global $conn;
	global $rowPrincipal;

	$result = "";

	if ($tipoCertificado == "C") {
		$params = array(":contrato" => $_SESSION["contrato"]);
		$sql =
			"SELECT ad_autorizado
				 FROM art.aad_autorizacertificadodeuda
				WHERE TRUNC(SYSDATE) <= ad_fechavigencia
					AND ad_contrato = :contrato
					AND ad_id = (SELECT MAX(ad.ad_id)
												 FROM art.aad_autorizacertificadodeuda ad
												WHERE ad.ad_contrato = :contrato)";
		$autorizaImpresionSinDeuda = valorSql($sql, "", $params, 0);

		$result.=
			"Se deja constancia por la presente que la empresa de referencia se encuentra asegurada en Provincia Aseguradora de Riesgos del".
			" Trabajo S.A. de acuerdo con lo normado por la Ley 24.557 de Riesgos de Trabajo y sus disposiciones reglamentarias. ";

		if (($rowPrincipal["CC_DDJJFALTANTE"] == "T") and ($rowPrincipal["CC_DDJJADEUDADAS"] != ""))
			$result.= getTextoDeclaracionesJuradasAdeudadas($rowPrincipal["CC_DDJJADEUDADAS"]);

		// Si no tiene autorización, o si nunca se pidió autorización (o venció la autorización) y tiene deuda..
		if (($autorizaImpresionSinDeuda != "S") and (intval($deudaContrato["DEUDATOTAL"]) > 5))
			$result.= sprintf("\n\nSu empresa posee al %s un saldo deudor total que asciende a la suma de $%s compuesto por $%s de deuda".
												" nominal y $%s en concepto de intereses. Provincia ART está en condiciones de aplicar lo establecido en el Art. 28".
												" punto 4 de la Ley 24.557 y sus reglamentaciones.",
												$fechaImpresion,
												$deudaContrato["DEUDATOTAL"],
												$deudaContrato["DEUDANOMINAL"],
												$deudaContrato["INTERES"]);
		$result.= "\n\nSe extiende el presente certificado a efectos de ser presentado ante quien corresponda.";

		if ($esTraspaso)
			$result.=
				"\n\nEl presente certificado tiene validez hasta el último día del mes en el que su empresa solicitó el".
				" traspaso de aseguradora conforme la Resolución SRT 41/1997.";
		else
			$result.=
				"\n\nEl presente certificado tiene validez de 30 días corridos a partir de la fecha de emisión. En ningún".
				" caso Provincia ART S.A. será responsable de las consecuencias del uso del certificado una vez vencido el".
				" plazo de validez.";

		$result.=
			"\n\nRecuerde revisar periódicamente las comunicaciones que le remite la Aseguradora a través del sistema de".
			" Ventanilla Electrónica de la SRT. Tenga en cuenta que tanto la suscripción a este servicio como el acceso,".
			" se efectúa con su clave fiscal de AFIP.".
			" En www.srt.gov.ar podrá encontrar los Manuales que indican cómo adherirse a esta vía de comunicación, así".
			" como el modo de utilización de la misma.";
	}
	elseif ($tipoCertificado == "X") {
		$result.=
			"Se deja constancia por la presente que la empresa de referencia se encuentra asegurada en Provincia".
			" Aseguradora del Riesgo del Trabajo S.A. de acuerdo con lo normado por la ley 24.557 y sus disposiciones".
			" reglamentarias.";

		if (($rowPrincipal["CC_DDJJFALTANTE"] == "T") and ($rowPrincipal["CC_DDJJADEUDADAS"] != ""))
			$result.= getTextoDeclaracionesJuradasAdeudadas($rowPrincipal["CC_DDJJADEUDADAS"]);

		$result.= "\n\nEl siguiente, es el trabajador informado por la empresa afiliada que realizará tareas / comisiones en el exterior:\n\n";
		$result.= "Nombre y Apellido (D.N.I.)\n";

		$params = array(":nrocertificado" => $nroCertificado);
		$sql =
			"SELECT cx_destino, cx_fechasalida, cx_fecharegreso, pa_descripcion, tj_nombre, tj_documento
				 FROM acx_certxtrabenviaje
		LEFT JOIN acc_certificadocobertura ON(cc_id = cx_idcertificado)
		LEFT JOIN mtv_trabajadorenviaje ON(tv_idcertxtrab = cx_id)
		LEFT JOIN ctj_trabajador ON(tj_cuil = tv_cuil)
		LEFT JOIN afi.ace_certificadoextpaises ON (ce_idcertificado = cc_id)
		LEFT JOIN art.cpa_paises ON(ce_codigopais = pa_codigo)
				WHERE cc_nrocertificado = :nrocertificado
		 ORDER BY 4";
		$stmt = DBExecSql($conn, $sql, $params);
		while ($row = DBGetQuery($stmt, 1, false)) {
			$destino = $row["PA_DESCRIPCION"]." - ".$row["CX_DESTINO"];
			$regreso = $row["CX_FECHAREGRESO"];
			$salida = $row["CX_FECHASALIDA"];

			$result.= $row["TJ_NOMBRE"]."       (".$row["TJ_DOCUMENTO"].")))\n";
		}
		$result.= "\nDestino: ".$destino."\n";
		$result.= "Desde: ".$salida."       Hasta: ".$regreso." (*)";

		$result.=
			"\n\nEl presente certificado tiene validez de 30 días corridos a partir de la fecha de emisión. En ningún caso Provincia ART será responsable de las consecuencia del uso del".
			" certificado una vez vencido el plazo de validez.".
			"\nAnte cualquier inconveniente, recuerde que el viajero podrá comunicarse en forma gratuita (cobro revertido) con nuestra Coordinación de Emergencias Médicas al teléfono";
		if ($_SESSION["contrato"] == 130760)
			$result.= " +(54 11)6009.2620";
		else
			$result.= " +(54 11)3753-5599";
	}
	else {
		$params = array(":nrocertificado" => $nroCertificado);
		$sql =
			"SELECT cc_calle, cc_contrato, cc_cpostal, cc_ddjjadeudadas, cc_ddjjfaltante, cc_departamento, cc_id, cc_localidad, cc_numero, cc_piso, cc_provincia, cc_rsocialtercero,
							cc_tipocertificado, cc_tiponomina, dr_calle, dr_codigopostal, dr_departamento, dr_localidad, dr_numero, dr_piso, dr_razonsocial, pv_descripcion provincia
				 FROM acc_certificadocobertura, afi.adr_datosnorepeticion, cpv_provincias
				WHERE cc_id = dr_idcertificado(+)
					AND dr_provincia = pv_codigo(+)
					AND cc_nrocertificado = :nrocertificado";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt, 1, false);

		// Claúsula Especial/Particular..
		$result.= sprintf("Por la presente Provincia Aseguradora de Riesgos del Trabajo S.A., renuncia en forma".
											" expresa a reclamar o iniciar toda acción de repetición o de regreso contra %s, sus".
											" funcionarios, empleados u obreros, sea con fundamento en el art. 39, ap. 5, de la".
											" Ley Nº 24.557, sea en cualquier otra norma jurídica, con motivo de las prestaciones en".
											" especie o dinerarias que se vea obligada a abonar, contratar u otorgar al personal".
											" dependiente o ex dependiente de %s, amparados por la cobertura del Contrato de".
											" Afiliación Nº %s, por acciones del trabajo o enfermedades profesionales, ocurridos o".
											" contraídas por el hecho o en ocasión",
											htmlspecialchars_decode($row["DR_RAZONSOCIAL"]),
											$rowPrincipal["NOMBRE"],
											$_SESSION["contrato"]);

		// Claúsula Especial..
		if ($tipoCertificado == "E")
			$result.=
				" del trabajo.\nEsta \"Cláusula de no repetición\" cesará en sus efectos si el empresario comitente a favor".
				" de quien se emite, no cumple estrictamente con las medidas de prevención e higiene y seguridad en el".
				" trabajo, o de cualquier manera infringe la Ley Nº 19.587; su Decreto Reglamentario Nº 351/79 y las".
				" normativas que sobre el particular ha dictado la Superintendencia de Riesgos del Trabajo; las Provincias".
				" y la Ciudad Autónoma de la Ciudad de Buenos Aires en el ámbito de su competencia.\n";

		// Claúsula Particular..
		if ($tipoCertificado == "P")
			$result.=
				" del trabajo, en la medida que el empresario comitente a favor de quien se emite la presente cumpla con".
				" la calificación en el tercer nivel según lo estipulado por el artículo segundo inciso c) del Decreto".
				" 170/96 o las obligaciones legales en materia de higiene y seguridad.\n";

		if ($row["DR_CALLE"] != "") {
			$result.= sprintf("PROVINCIA A.R.T. S.A. se obliga a comunicar a %s, en forma fehaciente, los incumplimientos".
												" en que incurra el asegurado, especialmente la falta de pago en término dentro de los".
												" diez días de verificado. El Asegurado declara como domicilio de %s para realizar las".
												" comunicaciones en: %s Nº %s",
												$row["DR_RAZONSOCIAL"],
												$row["DR_RAZONSOCIAL"],
												strtoupper($row["DR_CALLE"]),
												$row["DR_NUMERO"]);

			if (trim($row["DR_PISO"]) != "")
				$result.= " Piso ".strtoupper($row["DR_PISO"]);
			if (trim($row["DR_DEPARTAMENTO"]) != "")
				$result.= " Depto. ".strtoupper($row["DR_DEPARTAMENTO"]);
			if (trim($row["DR_CODIGOPOSTAL"]) != "")
				$result.= " (".strtoupper($row["DR_CODIGOPOSTAL"]).")";
			if (trim($row["DR_LOCALIDAD"]) != "")
				$result.= " ".strtoupper($row["DR_LOCALIDAD"]);
			if (trim($row["PROVINCIA"]) != "")
				$result.= " ".strtoupper($row["PROVINCIA"]);
			$result.= ".\n";
		}

		$result.= sprintf("Fuera de las causales que expresamente prevee la normativa vigente, el contrato de".
											" afiliación no podrá ser modificado o enmendado sin previa notificación fehaciente a %s, en".
											" un plazo no inferior a quince(15) días corridos.\n\n".
											"Se deja constancia por la presente que la empresa de referencia se encuentra asegurada en".
											" Provincia A.R.T.",
											htmlspecialchars_decode($row["DR_RAZONSOCIAL"]));

		if (($rowPrincipal["CC_DDJJFALTANTE"] == "T") and ($rowPrincipal["CC_DDJJADEUDADAS"] != ""))
			$result.= getTextoDeclaracionesJuradasAdeudadas($rowPrincipal["CC_DDJJADEUDADAS"]);

		$result.=
			"\n\nEl presente certificado tiene una validez de 30 días corridos a partir de la fecha de emisión. En ningún".
			" caso Provincia ART S.A. será responsable de las consecuencias del uso del certificado una vez vencido el".
			" plazo de validez.";
	}

	return $result;
}

function getSelectReporteNomina(&$sql, &$params) {
	global $idCertificado;
	global $servidorContingenciaActivo;

	if ($servidorContingenciaActivo) {
		if ($_SESSION["certificadoCobertura"]["seleccionNomina"] == "p") {		// Nómina parcial..
			$params = array(":contrato" => $_SESSION["contrato"]);
			$sql =
				"SELECT rl_categoria categoria, tj_cuil cuil, rl_fechaingreso ni_fmovimiento, tj_fnacimiento ni_fnacimiento, tj_nombre ni_nombre, rl_sueldo ni_remuneracion, tj_sexo ni_sexo,
								rl_tarea ni_tarea
					 FROM ctj_trabajador, crl_relacionlaboral
					WHERE tj_id = rl_idtrabajador
						AND rl_contrato = :contrato
						AND tj_id IN (".implode(",", $_SESSION["certificadoCobertura"]["trabajadores"]).")
			 ORDER BY rl_id DESC";
		}
		if ($_SESSION["certificadoCobertura"]["seleccionNomina"] == "t") {		// Nómina total..
			$params = array(":contrato" => $_SESSION["contrato"]);
			$sql =
				"SELECT rl_categoria categoria, tj_cuil cuil, rl_fechaingreso ni_fmovimiento, tj_fnacimiento ni_fnacimiento, tj_nombre ni_nombre, rl_sueldo ni_remuneracion, tj_sexo ni_sexo,
								rl_tarea ni_tarea
					 FROM ctj_trabajador, crl_relacionlaboral
					WHERE tj_id = rl_idtrabajador
						AND rl_contrato = :contrato";
		}
	}
	else {
		$params = array(":idcertificado" => $idCertificado);
		$sql =
			"SELECT categ.tb_descripcion categoria, art.utiles.armar_cuit(ni_cuil) cuil, ni_fmovimiento, ni_fnacimiento, ni_nombre, ni_remuneracion, ni_sexo, ni_tarea
				 FROM ctj_trabajador, ani_nominaimpresa, ctb_tablas categ
				WHERE ni_cuil = tj_cuil
					AND ni_categoria = tb_codigo(+)
					AND tb_clave(+) = 'CATEG'
					AND ni_idcertificado = :idcertificado
		 ORDER BY tj_nombre";
	}
}

function getTextoDeclaracionesJuradasAdeudadas($ddjjAdeudadas) {
// No se muestra mas el texto de esta función, según ticket 39819..
	$result = "";
/*
	$arrPeriodos = explode(",", $ddjjAdeudadas);
	$cantidadPeriodos = count($arrPeriodos);

	if ($cantidadPeriodos > 0) {
		if ($cantidadPeriodos == 1)
			$result = "\n\nLa empresa adeuda 1 Declaración Jurada de Personal";
		elseif ($cantidadPeriodos > 1)
			$result = sprintf("\n\nLa empresa adeuda %s Declaraciones Juradas de Personal", $cantidadPeriodos);
		$result.= ", por ese motivo le solicitamos que se contacte por e-mail a la siguiente dirección: emision@provart.com.ar.";
	}
*/
	return $result;
}

function guardarCertificado($calle, $contrato, $cpostal, $criterioVencimiento, $departamento, $deuda, $establecimiento, $estado, $fechaDdjj, $localidad, $idUsuAlta, $numero, $observaciones,
														$piso, $provincia, $razonSocialTercero, $tipoCertificado, $tipoNomina, &$idCertificado, &$nroCertificado) {
	global $conn;

	$curs = null;
	$params = array(":calle" => $calle,
									":contrato" => $contrato,
									":cpostal" => $cpostal,
									":criteriovencimiento" => $criterioVencimiento,
									":departamento" => substr($departamento, 0, 20),
									":deuda" => $deuda,
									":establecimiento" => $establecimiento,
									":estado" => $estado,
									":fechaddjj" => $fechaDdjj,
									":localidad" => $localidad,
									":nidusualta" => $idUsuAlta,
									":numero" => $numero,
									":observaciones" => $observaciones,
									":piso" => $piso,
									":provincia" => $provincia,
									":razonsocialtercero" => $razonSocialTercero,
									":tipocertificado" => $tipoCertificado,
									":tiponomina" => $tipoNomina);
	$sql = 	"BEGIN art.webart.set_certificado_cobertura(:contrato, :estado, :deuda, :nidusualta, :tipocertificado, :observaciones, :razonsocialtercero, :numero, :piso, :departamento, :fechaddjj, :criteriovencimiento, :tiponomina, :calle, :localidad, :cpostal, :provincia, :establecimiento, :data); END;";
	DBExecSP($conn, $curs, $sql, $params, true, 0);
	$row = DBGetSP($curs, false);
	$idCertificado = $row["ID"];
	$nroCertificado = $row["NROCERTIFICADO"];
}

function guardarTrabajador($row) {
	global $conn;
	global $idCertificado;

	$params = array(":idcertificado" => $idCertificado,
									":cuil" => $row["TJ_CUIL"],
									":nombre" => $row["TJ_NOMBRE"],
									":sexo" => $row["TJ_SEXO"],
									":categoria" => $row["RL_CATEGORIA"],
									":tarea" => $row["RL_TAREA"],
									":remuneracion" => nullIfCero($row["RL_SUELDO"]),
									":fechamovimiento" => $row["RL_FECHAINGRESO"],
									":fechanacimiento" => $row["TJ_FNACIMIENTO"],
									":idestablecimiento" => NULL);
	$sql =
		"INSERT INTO ani_nominaimpresa
								(ni_id, ni_idcertificado, ni_cuil, ni_nombre, ni_sexo, ni_categoria, ni_tarea, ni_remuneracion, ni_fmovimiento, ni_fnacimiento, ni_idestablecimiento)
				 VALUES (seq_ani_id.NEXTVAL, :idcertificado, :cuil, :nombre, :sexo, :categoria, :tarea, :remuneracion, :fechamovimiento, :fechanacimiento, :idestablecimiento)";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);
}

function guardarTrabajadoresEnViaje($tipoCertificado, $idCertXTrabEnViaje, $cuil, $usualta) {
	global $conn;

	if ($tipoCertificado == "X") {
		$params = array(":cuil" => $cuil,
										":idcertxtrab" => $idCertXTrabEnViaje,
										":usualta" => "W_".$usualta);
		$sql =
			"INSERT INTO mtv_trabajadorenviaje
									 (tv_id, tv_idcertxtrab, tv_cuil, tv_usualta, tv_fechaalta)
						VALUES (seq_mtv_id.NEXTVAL, :idcertxtrab, :cuil, :usualta, SYSDATE)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
}


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 70));
validarSesion(isset($_SESSION["contrato"]));

try {
	set_time_limit(300);
	
	SetDateFormatOracle("DD/MM/YYYY");

	$params = array(":contrato" => $_SESSION["contrato"]);
	$sql =
		"SELECT co_estado
			 FROM aco_contrato
			WHERE co_contrato = :contrato";
	$estado = valorSql($sql, "", $params, 0);

	if ($estado == 2)
		throw new Exception("Si desea imprimir certificados de cobertura para el contrato ".$_SESSION["contrato"]." debe comunicarse con el sector de Afiliaciones.");

	// Traigo la deuda de la empresa..
	$curs = null;
	$params = array(":contrato" => $_SESSION["contrato"]);
	$sql = "BEGIN web.get_busca_deuda_certificado(SYSDATE, :contrato, :data); END;";
	DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs, false);
	$deudaContrato = $row;

	$sql = "SELECT Nomina.Get_UltPeriodoNomina FROM DUAL";
	$fechaDdjj = valorSql($sql, "", array(), 0);

	switch ($_SESSION["certificadoCobertura"]["tipoCertificado"]) {
		case "ccc":
			$tipoCertificado = "C";
			break;
		case "cccr":
			$tipoCertificado = "E";
			break;
		case "cce":
			$tipoCertificado = "X";
			break;
	}


	$idCertificado = "";
	$nroCertificado = "";
	$cantidadReintentos = 60;
	$iLoop = IIF($servidorContingenciaActivo, 1000, 0);
	while ((($idCertificado == "") or ($idCertificado == 0)) and ($iLoop < $cantidadReintentos)) {
		try {
			guardarCertificado($_SESSION["certificadoCobertura"]["calle"],
												 $_SESSION["contrato"],
												 $_SESSION["certificadoCobertura"]["codigoPostal"],
												 "30",
												 $_SESSION["certificadoCobertura"]["departamento"],
												 $deudaContrato["DEUDATOTAL"],
												 "NM",
												 $estado,
												 $fechaDdjj,
												 $_SESSION["certificadoCobertura"]["localidad"],
												 $_SESSION["idUsuario"],
												 $_SESSION["certificadoCobertura"]["numero"],
												 substr($_SESSION["certificadoCobertura"]["observaciones"], 0, 250),
												 $_SESSION["certificadoCobertura"]["piso"],
												 $_SESSION["certificadoCobertura"]["idprovincia"],
												 $_SESSION["certificadoCobertura"]["razonSocial"],
												 $tipoCertificado,
												 strtoupper($_SESSION["certificadoCobertura"]["tipoNomina"]),
												 $idCertificado,
												 $nroCertificado);
		}
		catch (Exception $e) {
			$iLoop++;
			DBRollback($conn);
			sleep(2);
		}
	}


	if (intval($idCertificado) == 0) {		// Si por algún motivo no se pudo capturar el id del certificado insertado..
		$params = array(":contrato" => $_SESSION["contrato"]);
		$sql = "SELECT MAX(cc_id) FROM acc_certificadocobertura WHERE cc_contrato = :contrato";
		$idCertificado = valorSql($sql, 0, $params, 0);

		$params = array(":id" => $idCertificado);
		$sql = "SELECT cc_nrocertificado FROM acc_certificadocobertura WHERE cc_id = :id";
		$nroCertificado = valorSql($sql, 0, $params, 0);
	}

	$idCertXTrabEnViaje = -1;
	if (($tipoCertificado == "X") and (!$servidorContingenciaActivo)) {
		$params = array(":asistenciaviajero" => $_SESSION["certificadoCobertura"]["asistenciaViajero"],
										":cuit" => $_SESSION["cuit"],
										":destino" => $_SESSION["certificadoCobertura"]["destino"],
										":fecharegreso" => $_SESSION["certificadoCobertura"]["fechaRegreso"],
										":fechasalida" => $_SESSION["certificadoCobertura"]["fechaSalida"],
										":formaviaje" => $_SESSION["certificadoCobertura"]["formaViaje"],
										":idcertificado" => $idCertificado,
										":mostrarvalidez" => "T",
										":observaciones" => substr($_SESSION["certificadoCobertura"]["observaciones"], 0, 255),
										":usualta" => "W_".$_SESSION["idUsuario"]);
		$sql =
			"INSERT INTO acx_certxtrabenviaje
									 (cx_asistenciaviajero, cx_cuit, cx_destino, cx_fechaalta, cx_fecharecepcion, cx_fecharegreso, cx_fechasalida, cx_formaviaje, cx_id,
										cx_idcertificado, cx_mostrarvalidez, cx_observaciones, cx_usualta)
						VALUES (:asistenciaviajero, :cuit, :destino, SYSDATE, SYSDATE, TO_DATE(:fecharegreso, 'dd/mm/yyyy'), TO_DATE(:fechasalida, 'dd/mm/yyyy'), :formaviaje, seq_acx_id.NEXTVAL,
										:idcertificado, :mostrarvalidez, :observaciones, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$params = array(":idcertificado" => $idCertificado);
		$sql = "SELECT cx_id FROM acx_certxtrabenviaje WHERE cx_idcertificado = :idcertificado";
		$idCertXTrabEnViaje = valorSql($sql, "", $params, 0);

		$params = array(":codigopais" => $_SESSION["certificadoCobertura"]["pais"],
										":idcertificado" => $idCertXTrabEnViaje);
		$sql =
			"INSERT INTO afi.ace_certificadoextpaises (ce_codigopais, ce_id, ce_idcertificado)
																				 VALUES (:codigopais, -1, :idcertificado)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}


	if (!$servidorContingenciaActivo) {
		if ($_SESSION["certificadoCobertura"]["seleccionNomina"] == "p") {		// Nómina parcial..
			foreach ($_SESSION["certificadoCobertura"]["trabajadores"] as $value)
				if (intval($value)) {
					$params = array(":contrato" => $_SESSION["contrato"], ":id" => $value);
					$sql =
						"SELECT rl_categoria, rl_fechaingreso, tj_fnacimiento, rl_sueldo, rl_tarea, tj_cuil, tj_nombre, tj_sexo
							 FROM ctj_trabajador, crl_relacionlaboral
							WHERE tj_id = rl_idtrabajador
								AND rl_contrato = :contrato
								AND tj_id = :id
					 ORDER BY rl_id DESC";
					$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
					$row = DBGetQuery($stmt, 1, false);
					guardarTrabajador($row);
					guardarTrabajadoresEnViaje($tipoCertificado, $idCertXTrabEnViaje, $row["TJ_CUIL"], "W_".$_SESSION["idUsuario"]);
				}
		}
		if ($_SESSION["certificadoCobertura"]["seleccionNomina"] == "t") {		// Nómina total..
			$params = array(":contrato" => $_SESSION["contrato"]);
			$sql =
				"SELECT rl_categoria, rl_fechaingreso, tj_fnacimiento, rl_sueldo, rl_tarea, tj_cuil, tj_nombre, tj_sexo
					 FROM ctj_trabajador, crl_relacionlaboral
					WHERE tj_id = rl_idtrabajador
						AND rl_contrato = :contrato";
			$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			while ($row = DBGetQuery($stmt, 1, false)) {
				guardarTrabajador($row);

				// Guardo los trabajadores en viaje si correspondiere..
				guardarTrabajadoresEnViaje($tipoCertificado, $idCertXTrabEnViaje, $row["TJ_CUIL"], "W_".$_SESSION["idUsuario"]);
			}
		}

		// Inserto en la acd_certificadodeuda..
		$sql =
			"INSERT INTO acd_certificadodeuda
									(cd_id, cd_idcertificado, cd_periodo, cd_importe, cd_intereses, cd_situacion)
									(SELECT seq_acd_id.NEXTVAL, ".$idCertificado.", rc_periodo, importe, ROUND(tasa / 100 * importe, 2) intereses, concursos
										 FROM (SELECT rc_periodo, utiles.iif_compara('<=', rc_periodo, em_ultimomesconcurso, 0, deuda.get_tasaacumulada(deuda.get_vencimientocuota(SUBSTR(em_cuit, 11, 1), rc_periodo, co_idtiporegimen_orig), actualdate)) tasa,
																	(rc_devengadocuota + rc_devengadofondo) -(rc_pagocuota + rc_pagofondo + rc_recuperocuota + rc_recuperofondo) - rc_importereclamado - rc_montorefinanciado importe,
																	utiles.iif_compara('<=', rc_periodo, em_ultimomesconcurso, 'C', NULL) concursos
														 FROM aem_empresa, aco_contrato, zrc_resumencobranza
														WHERE rc_periodo <= deuda.ultimoperiododevengado
															AND rc_prescripto = 'N'
															AND co_idempresa = em_id
															AND co_contrato = rc_contrato
															AND rc_periodo >= TO_CHAR(NVL(cobranza.get_maxfechaconcquiebra(em_cuit), TO_DATE(rc_periodo || '01', 'YYYYMMDD')), 'YYYYMM')
															AND rc_contrato = :contrato)
										WHERE importe > 0)";
		$params = array(":contrato" => $_SESSION["contrato"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		DBCommit($conn);
	}


	if ($servidorContingenciaActivo) {
		$idCertificado = 0;
		$nroCertificado = "";
	}


	//	*******  INICIO - Armado del reporte..  *******
	$path = DATA_CERTIFICADOS_COBERTURA.armPathFromNumber($nroCertificado);
	if (!makeDirectory($path))
		throw new Exception("ERROR: No se puede crear la carpeta.");
	$file = $path."certificado_cobertura_".$nroCertificado.".pdf";

	// Armo el sql principal..
	$params = array(":contrato" => $_SESSION["contrato"], ":nrocertificado" => $nroCertificado);
	$sql =
		"SELECT dc_calle calle,
						cc_ddjjadeudadas,
						cc_ddjjfaltante,
						co_estado,
						co_nivel,
						ca1.ac_codigo codactividad,
						ca2.ac_codigo codactividad2,
						ca3.ac_codigo codactividad3,
						co_direlectronica correo,
						cuit_ponerguiones(em_cuit) cuit,
						dc_cpostal,
						dc_cpostala,
						dc_departamento,
						dc_fax,
						dc_numero,
						dc_piso,
						dc_telefonos,
						UPPER(consultas.get_descripciones('AFEST', em_cuit)) desc_estado,
						pv_descripcion desc_provincia,
						ca1.ac_descripcion descactividad,
						ca2.ac_descripcion descactividad2,
						ca3.ac_descripcion descactividad3,
						TO_CHAR(co_fechaafiliacion, 'dd/mm/yyyy') fafilia,
						art.varios.get_faxparaenvio(dc_fax) fax,
						nomina.get_ultperiodonomina fechaddjj,
						dc_localidad localidad,
						art.utiles.armar_domicilio(dc_calle, dc_numero, dc_piso, dc_departamento, NULL) || art.utiles.armar_localidad(dc_cpostal, dc_cpostala, dc_localidad, dc_provincia) locprov,
						'Buenos Aires, ' || TO_CHAR(SYSDATE, 'dd/mm/yyyy') lugarfecha,
						em_nombre nombre,
						TO_CHAR(co_vigenciadesde, 'dd/mm/yyyy') vigendesde,
						TO_CHAR(co_vigenciahasta, 'dd/mm/yyyy') vigenhasta
			 FROM aem_empresa, cac_actividad ca1, cac_actividad ca2, cac_actividad ca3, aco_contrato, adc_domiciliocontrato, cpv_provincias prov, acc_certificadocobertura
			WHERE dc_provincia = prov.pv_codigo(+)
				AND ca1.ac_id(+) = ".IIF($servidorContingenciaActivo, "co_idactividad", "cc_idactividad1")."
				AND ca2.ac_id(+) = ".IIF($servidorContingenciaActivo, "co_idactividad2", "cc_idactividad2")."
				AND ca3.ac_id(+) = ".IIF($servidorContingenciaActivo, "co_idactividad3", "cc_idactividad3")."
				AND dc_tipo = 'L'
				AND co_contrato = dc_contrato
				AND co_idempresa = em_id
				AND co_contrato = cc_contrato(+)
				AND cc_nrocertificado(+) = :nrocertificado
				AND co_contrato = :contrato";
	$stmt = DBExecSql($conn, $sql, $params);
	$rowPrincipal = DBGetQuery($stmt, 1, false);

	$estado = (($rowPrincipal["CO_ESTADO"] == 2)?"EMPRESA ".$rowPrincipal["DESC_ESTADO"]:" ");
	$fechaDDJJ = $rowPrincipal["FECHADDJJ"];
	$lugarFecha = $rowPrincipal["LUGARFECHA"];

	$params = array(":contrato" => $_SESSION["contrato"]);
	$sql =
		"SELECT ar_nombre
			 FROM ate_traspasoegreso ate, aar_art
			WHERE ate.te_contrato = :contrato
				AND ate.te_fechabaja IS NULL
				AND ar_id = ate.te_idartfutura
				AND EXISTS(SELECT MAX(te.te_fecha)
										 FROM ate_traspasoegreso te
										WHERE te.te_contrato = ate.te_contrato
								 GROUP BY te.te_contrato
									 HAVING ROUND(SYSDATE - MAX(te.te_fecha), 0) < 60)";
	$esTraspaso = (valorSql($sql, "", $params) != "");

	$clausulas = getClausulas($tipoCertificado, $deudaContrato, $esTraspaso, date("d/m/Y"), $nroCertificado);


	$pdf = new FPDI();

	$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/certificado_de_cobertura/templates/certificado_cobertura.pdf");
	$pdf->AddPage();
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx);
	$pdf->SetFont("Arial", "", 10);

	$pdf->Ln(4);
	$pdf->Cell(1);
	$pdf->Cell(120, 0, $rowPrincipal["LUGARFECHA"]);

	$pdf->Ln(10);
	$pdf->Cell(33);
	$pdf->Cell(40, 0, $nroCertificado);

	$pdf->Cell(40, 0, $estado);

	$pdf->Ln(13);
	$pdf->Cell(28);
	$pdf->Cell(96, 0, $rowPrincipal["NOMBRE"]);

	$pdf->Cell(20);
	$pdf->Cell(48, 0, $rowPrincipal["CUIT"]);

	$pdf->Ln(4);
	$pdf->Cell(34);
	$pdf->Cell(90, 0, substr($rowPrincipal["LOCPROV"], 0, 44));

	$pdf->Ln(2);
	$pdf->Cell(152);
	$pdf->Cell(40, 0, $_SESSION["contrato"]);

	$pdf->Ln(2);
	$pdf->Cell(2);
	$pdf->Cell(120, 0, substr($rowPrincipal["LOCPROV"], 44));

	$pdf->Ln(4);
	$pdf->Cell(20);
	$pdf->Cell(104, 0, $rowPrincipal["DC_TELEFONOS"]);

	$pdf->Cell(42);
	$pdf->Cell(24, 0, $rowPrincipal["FAFILIA"]);

	$pdf->Ln(4);
	$pdf->Cell(12);
	$pdf->Cell(112, 0, $rowPrincipal["FAX"]);

	$pdf->Cell(22);
	$pdf->Cell(24, 0, $rowPrincipal["VIGENDESDE"]);

	$pdf->Cell(2);
	$pdf->Cell(24, 0, $rowPrincipal["VIGENHASTA"]);

	$pdf->Ln(14);
	$pdf->Cell(2);
	$pdf->Cell(168, 0, $rowPrincipal["CODACTIVIDAD"]."   ".$rowPrincipal["DESCACTIVIDAD"]);

	$pdf->Cell(12);
	$pdf->Cell(16, 0, $rowPrincipal["CO_NIVEL"]);

	$pdf->Ln(5);
	$pdf->Cell(2);
	$pdf->Cell(188, 0, $rowPrincipal["CODACTIVIDAD2"]."   ".$rowPrincipal["DESCACTIVIDAD2"]);

	$pdf->Ln(5);
	$pdf->Cell(2);
	$pdf->Cell(188, 0, $rowPrincipal["CODACTIVIDAD3"]."   ".$rowPrincipal["DESCACTIVIDAD3"]);

	// Muestro las claúsulas..
	$pdf->Ln(4);
	$parrafos = explode("\n", $clausulas);
	for ($i=0; $i<count($parrafos); $i++) {
		$str = trim($parrafos[$i]);

		$pdf->WordWrap($str, 188);
		$pdf->Write(4, $str);

		$pdf->Ln(4);
	}

	if ($tipoCertificado == "X") {
		$pdf->SetFont("Arial", "", 7);
		$pdf->Ln(4);
		$pdf->Cell(188, 0, "(*) La cobertura del trabajador que realice tareas / comisiones en el exterior queda sujeta a la vigencia del contrato de afiliación suscripto con la empresa afiliada.");
		$pdf->SetFont("Arial", "", 10);
	}

	$pdf->Output($file, "F");
	//	*******  FIN - Armado del reporte..  *******


	//	*******  INICIO - Armado del reporte de la nómina..  *******
	$params = array(":idcertificado" => $idCertificado);
	$sql = "SELECT COUNT(*) FROM ani_nominaimpresa WHERE ni_idcertificado = :idcertificado";
	$mostrarNomina = (intval(valorSql($sql, 0, $params)) > 0);

	$mostrarNomina = (($_SESSION["certificadoCobertura"]["seleccionNomina"] == "p") or ($_SESSION["certificadoCobertura"]["seleccionNomina"] == "t"));
	if ($mostrarNomina) {		// Si se muestra la nómina genero el reporte de la nómina..
		$pdf = new FPDI();
		$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/certificado_de_cobertura/templates/certificado_cobertura.pdf");

		$trabajadoresXPagina = 35;
		$tmpTrabajadores = $trabajadoresXPagina;

		getSelectReporteNomina($sql, $params);
		$stmt = DBExecSql($conn, $sql, $params);
		while ($row = DBGetQuery($stmt, 1, false)) {
			if (($tmpTrabajadores % $trabajadoresXPagina) == 0) {
				$pdf->AddPage();
				$tplIdx = $pdf->importPage(1);
				$pdf->useTemplate($tplIdx);
				$pdf->SetFont("Arial", "", 10);

				$pdf->Ln(4);
				$pdf->Cell(1);
				$pdf->Cell(120, 0, $rowPrincipal["LUGARFECHA"]);

				$pdf->Ln(10);
				$pdf->Cell(33);
				$pdf->Cell(40, 0, $nroCertificado);

				$pdf->Cell(40, 0, $estado);

				$pdf->Ln(13);
				$pdf->Cell(28);
				$pdf->Cell(96, 0, $rowPrincipal["NOMBRE"]);

				$pdf->Cell(20);
				$pdf->Cell(48, 0, $rowPrincipal["CUIT"]);

				$pdf->Ln(4);
				$pdf->Cell(34);
				$pdf->Cell(90, 0, substr($rowPrincipal["LOCPROV"], 0, 44));

				$pdf->Ln(2);
				$pdf->Cell(152);
				$pdf->Cell(40, 0, $_SESSION["contrato"]);

				$pdf->Ln(2);
				$pdf->Cell(2);
				$pdf->Cell(120, 0, substr($rowPrincipal["LOCPROV"], 44));

				$pdf->Ln(4);
				$pdf->Cell(20);
				$pdf->Cell(104, 0, $rowPrincipal["DC_TELEFONOS"]);

				$pdf->Cell(42);
				$pdf->Cell(24, 0, $rowPrincipal["FAFILIA"]);

				$pdf->Ln(4);
				$pdf->Cell(12);
				$pdf->Cell(112, 0, $rowPrincipal["FAX"]);

				$pdf->Cell(22);
				$pdf->Cell(24, 0, $rowPrincipal["VIGENDESDE"]);

				$pdf->Cell(2);
				$pdf->Cell(24, 0, $rowPrincipal["VIGENHASTA"]);

				$pdf->Ln(14);
				$pdf->Cell(2);
				$pdf->Cell(168, 0, $rowPrincipal["CODACTIVIDAD"]."   ".$rowPrincipal["DESCACTIVIDAD"]);

				$pdf->Cell(12);
				$pdf->Cell(16, 0, $rowPrincipal["CO_NIVEL"]);

				$pdf->Ln(5);
				$pdf->Cell(2);
				$pdf->Cell(188, 0, $rowPrincipal["CODACTIVIDAD2"]."   ".$rowPrincipal["DESCACTIVIDAD2"]);

				$pdf->Ln(5);
				$pdf->Cell(2);
				$pdf->Cell(188, 0, $rowPrincipal["CODACTIVIDAD3"]."   ".$rowPrincipal["DESCACTIVIDAD3"]);

				// Muestro la cabecera de los trabajadores..
				$pdf->Ln(8);
				$pdf->Cell(1);
				switch ($_SESSION["certificadoCobertura"]["tipoNomina"]) {
					case "c":
						$pdf->Cell(27, 4, "C.U.I.L.", 1, 0, "C");
						$pdf->Cell(52, 4, "Nombre y Apellido", 1);
						$pdf->Cell(24, 4, "Cat.", 1, 0, "C");
						$pdf->Cell(34, 4, "Tarea", 1, 0, "C");
						$pdf->Cell(20, 4, "F. Mov.", 1, 0, "C");
						$pdf->Cell(20, 4, "F. Nacim.", 1, 0, "C");
						$pdf->Cell(16, 4, "Sexo", 1, 0, "C");
						break;
					case "s":
						$pdf->Cell(27, 4, "C.U.I.L.", 1);
						$pdf->Cell(82, 4, "Nombre y Apellido", 1);
						$pdf->Cell(83, 4, "Tarea", 1);
						break;
				}
			}
			$pdf->Ln(4);
			$pdf->Cell(1);

			// Muestro a los trabajadores..
			switch ($_SESSION["certificadoCobertura"]["tipoNomina"]) {
				case "c":
					$pdf->Cell(27, 4, $row["CUIL"], 1);
					$pdf->Cell(52, 4, $row["NI_NOMBRE"], 1);
					$pdf->Cell(24, 4, $row["CATEGORIA"], 1);
					$pdf->Cell(34, 4, $row["NI_TAREA"], 1);
					$pdf->Cell(20, 4, $row["NI_FMOVIMIENTO"], 1);
					$pdf->Cell(20, 4, $row["NI_FNACIMIENTO"], 1);
					$pdf->Cell(16, 4, $row["NI_SEXO"], 1, 0, "C");
					break;
				case "s":
					$pdf->Cell(27, 4, $row["CUIL"], 1);
					$pdf->Cell(82, 4, $row["NI_NOMBRE"], 1);
					$pdf->Cell(83, 4, $row["NI_TAREA"], 1);
					break;
			}

			$tmpTrabajadores++;
		}

		$fileNomina = str_replace("certificado_cobertura_", "certificado_cobertura_nomina_", $file);
		$pdf->Output($fileNomina, "F");
	}
	//	*******  FIN - Armado del reporte de la nómina..  *******
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script type="text/javascript">
		with (window.parent.document) {
			body.style.cursor = 'default';
			getElementById('imgProcesando').style.visibility = 'hidden';
			getElementById('imgDescargarNomina').style.display = 'inline';
		}
		if (confirm('El servidor se encuentra congestionado. ¿ Desea intentar descargar el certificado de cobertura nuevamente ?'))
			window.parent.descargarNomina();
	</script>
<?
	exit;
}
?>
<script type="text/javascript">
	with (window.parent.document) {
		body.style.cursor = 'default';
		getElementById('contenidoPaso3').style.display = 'none';
		getElementById('iframePdf').style.display = 'block';
		getElementById('iframePdfNomina').src = '<?= ($mostrarNomina)?getFile($fileNomina):""?>';
		getElementById('btnVerNomina').style.display = '<?= ($mostrarNomina)?"block":"none"?>';

		getElementById('imgProcesando').style.visibility = 'hidden';
		getElementById('imgDescargarNomina').style.display = 'block';
	}

	window.location.href = '<?= getFile($file)?>';
</script>