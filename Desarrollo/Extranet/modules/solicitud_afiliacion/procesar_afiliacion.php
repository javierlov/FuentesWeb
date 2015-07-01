<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");


function formatNumber(&$val1) {
	$val1 = "0".trim(str_replace(array(",", "$"), array(".", ""), $val1));
}

function getArtAnteriorParaAfiliacion($motivoAlta) {
	global $rowCotizacion;

	if ($motivoAlta == "03")
		return NULL;
	elseif ($motivoAlta == "04")
		return NULL;
	elseif ($motivoAlta == "05")
		return NULL;
	else
		return nullIfCero($rowCotizacion["IDARTANTERIOR"]);
}

function getIdActividad($codigo) {
	global $conn;

	$params = array(":codigo" => intval($codigo));
	$sql =
		"SELECT ac_id
			 FROM cac_actividad
			WHERE ac_codigo = TO_NUMBER(:codigo)";
	return valorSql($sql, "", $params, 0);
}

function getValorCheck($nombreCampo) {
	$result = NULL;
	if (isset($_POST[$nombreCampo]))
		$result = $_POST[$nombreCampo];

	return $result;
}

function updateTelefono($idSolicitud) {
	global $conn;

	$params = array(":solicitud" => $idSolicitud);
	$sql =
		"SELECT 1
			 FROM ats_telefonosolicitud
			WHERE ts_solicitud = :solicitud
				AND ts_tipo = 'X'";
	if (!existeSql($sql, $params)) {
		$sql =
			"INSERT INTO ats_telefonosolicitud(ts_area, ts_confirmado, ts_fechaalta, ts_id, ts_idtipotelefono, ts_interno, ts_numero, ts_observacion, ts_principal, ts_solicitud, ts_tipo, ts_usualta)
						SELECT ts_area, ts_confirmado, SYSDATE, -1, ts_idtipotelefono, ts_interno, ts_numero, ts_observacion, ts_principal, ts_solicitud, 'X', ts_usualta
							FROM ats_telefonosolicitud
						 WHERE ts_solicitud = :solicitud
							 AND ts_tipo = 'L'";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
}

function validar($id, $alta, $rowCotizacion, $isSoloPCP, $idVendedor, $datosEmpleadorManual, $sumaAseguradaRC, $formaPago, $iva, $iibb) {
	global $campoError;
	global $modulo;

	if (!isset($_SESSION["isAgenteComercial"]))
		throw new Exception("Usted no tiene permiso para acceder a este módulo.");

	// Bloque 1.1.1..
	if ($_POST["fechaSuscripcion"] == "") {
		$campoError = "fechaSuscripcion";
		throw new Exception("Debe ingresar la Fecha de Suscripción.");
	}

	if (!isFechaValida($_POST["fechaSuscripcion"])) {
		$campoError = "fechaSuscripcion";
		throw new Exception("La Fecha de Suscripción debe tener un formato válido.");
	}

	$params = array(":fechaalta" => $_POST["fechaSuscripcion"], ":id" => $id);
	if ($modulo == "R")		// Si es una revisión de precio..
		$sql =
			"SELECT 1
				 FROM asr_solicitudreafiliacion
				WHERE sr_id = :id
					AND TRUNC(sr_fechaalta) > TO_DATE(:fechaalta, 'DD/MM/YYYY')";
	else		// Si es una solicitud de cotización..
		$sql =
			"SELECT 1
				 FROM asc_solicitudcotizacion
				WHERE sc_id = :id
					AND TRUNC(sc_fechaalta) > TO_DATE(:fechaalta, 'DD/MM/YYYY')";
	if (existeSql($sql, $params)) {
		$campoError = "fechaSuscripcion";
		throw new Exception("La Fecha de Suscripción no puede ser anterior a la fecha de la solicitud de cotización.");
	}

	$params = array(":fechasuscripcion" => $_POST["fechaSuscripcion"], ":fechavencimiento" => $_POST["fechaVencimiento"]);
	$sql =
		"SELECT 1
			 FROM DUAL
			WHERE TO_DATE(:fechasuscripcion, 'DD/MM/YYYY') > TO_DATE(:fechavencimiento, 'DD/MM/YYYY')";
	if (existeSql($sql, $params)) {
		$campoError = "fechaSuscripcion";
		throw new Exception("La Fecha de Suscripción no puede ser posterior a la fecha de vigencia de la cotización (".$_POST["fechaVencimiento"].").");
	}

	if ($alta) {
		$params = array(":fechasuscripcion" => $_POST["fechaSuscripcion"]);
		$sql =
			"SELECT 1
				 FROM DUAL
				WHERE TO_DATE(:fechasuscripcion, 'DD/MM/YYYY') < ART.ACTUALDATE";
		if (existeSql($sql, $params)) {
			$campoError = "fechaSuscripcion";
			throw new Exception("La Fecha de Suscripción no puede ser anterior a la fecha actual.");
		}
	}
	else {
		$params = array(":fechasuscripcion" => $_POST["fechaSuscripcion"], ":id" => $id);
		$sql =
			"SELECT 1
				 FROM asa_solicitudafiliacion
				WHERE ((TO_DATE(:fechasuscripcion, 'DD/MM/YYYY') < TRUNC(sa_fechaalta)) OR (TO_DATE(:fechasuscripcion, 'DD/MM/YYYY') > ART.ACTUALDATE))
					AND TRUNC(sa_fechaafiliacion) <> TO_DATE(:fechasuscripcion, 'DD/MM/YYYY')
					AND ".$rowCotizacion["FORMULARIOFIELD"]." = :id";
		if (existeSql($sql, $params)) {
			$campoError = "fechaSuscripcion";
			throw new Exception("La Fecha de Suscripción no puede ser ni anterior a la fecha previamente cargada ni posterior a la fecha actual.");
		}
	}

	// Bloque 1.2..
	if ($_POST["razonSocial"] == "") {
		$campoError = "razonSocial";
		throw new Exception("Debe ingresar el Nombre o razón social.");
	}

	if ((isset($_POST["formaJuridica"])) and ($_POST["formaJuridica"] == -1)) {
		$campoError = "formaJuridica";
		throw new Exception("Debe seleccionar la Forma Jurídica.");
	}

	if (($_POST["fechaInicioActividad"] != "") and (!isFechaValida($_POST["fechaInicioActividad"]))) {
		$campoError = "fechaInicioActividad";
		throw new Exception("La Fecha de inicio de actividad debe tener un formato válido.");
	}

	if ($_POST["fechaInicioActividad"] != "") {
		$arrFecha = explode("/", $_POST["fechaInicioActividad"]);
		$fec = $arrFecha[2].$arrFecha[1].$arrFecha[0];
		if ($fec > date("Ymd")) {
			$campoError = "fechaInicioActividad";
			throw new Exception("La fecha de inicio de actividad no puede ser posterior a la fecha de alta.");
		}
	}

	if ($_POST["calle"] == "") {
		$campoError = "calle";
		throw new Exception("Debe ingresar la Calle.");
	}

	if ($_POST["numero"] == "") {
		$campoError = "numero";
		throw new Exception("Debe ingresar el Número.");
	}

	if ($_POST["codigoPostal"] == "") {
		$campoError = "codigoPostal";
		throw new Exception("Debe ingresar el Código Postal.");
	}

	if ((isset($_POST["provincia"])) and ($_POST["provincia"] == -1)) {
		$campoError = "provincia";
		throw new Exception("Debe seleccionar la Provincia.");
	}

	if ($_POST["localidad"] == "") {
		$campoError = "localidad";
		throw new Exception("Debe ingresar la Localidad.");
	}

	if ($_POST["email"] != "") {
		$params = array(":email" => $_POST["email"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			$campoError = "email";
			throw new Exception("El e-Mail debe tener un formato válido.");
		}
	}

	if (($_POST["establecimientos"] != "") and (!validarEntero($_POST["establecimientos"]))) {
		$campoError = "establecimientos";
		throw new Exception("La Cantidad de establecimientos debe tener un formato válido.");
	}

	if ((!$isSoloPCP) and ($_POST["nivel"] == "")) {
		$campoError = "nivel";
		throw new Exception("Debe indicar el Nivel de cumplimiento en Higiene y Seguridad.");
	}

	if ((!$isSoloPCP) and ($_POST["cargoResponsable"] == -1)) {
		$campoError = "cargoResponsable";
		throw new Exception("El campo Cargo del Responsable ART es obligatorio.");
	}

	if ((!$isSoloPCP) and ($_POST["sexoResponsable"] == -1)) {
		$campoError = "sexoResponsable";
		throw new Exception("El campo Sexo del Responsable ART es obligatorio.");
	}

	if ($_POST["emailResponsable"] != "") {
		$params = array(":email" => $_POST["emailResponsable"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			$campoError = "emailResponsable";
			throw new Exception("El e-Mail debe tener un formato válido.");
		}
	}

	// Bloque 2..
	if ($_POST["fechaVigenciaDesde"] == "") {
		$campoError = "fechaVigenciaDesde";
		throw new Exception("Debe ingresar la Vigencia Desde.");
	}

	$params = array(":fechavigenciadesde" => $_POST["fechaVigenciaDesde"]);
	$sql =
		"SELECT 1
			 FROM DUAL
			WHERE TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY') < TO_DATE('01/01/1996', 'DD/MM/YYYY')";
	if (existeSql($sql, $params)) {
		$campoError = "fechaVigenciaDesde";
		throw new Exception("La vigencia no puede ser anterior al 01/01/1996.");
	}

	if ($_POST["fechaVigenciaHasta"] == "") {
		$campoError = "fechaVigenciaHasta";
		throw new Exception("Debe ingresar la Vigencia Hasta.");
	}

	$params = array(":fechavigenciadesde" => $_POST["fechaVigenciaDesde"], ":fechasuscripcion" => $_POST["fechaSuscripcion"]);
	$sql =
		"SELECT 1
			 FROM DUAL
			WHERE TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY') < TO_DATE(:fechasuscripcion, 'DD/MM/YYYY')";
	if (existeSql($sql, $params)) {
		$campoError = "fechaVigenciaDesde";
		throw new Exception("La vigencia no puede ser anterior a la fecha de suscripción.");
	}

	// Bloque 3..
	if ($_POST["trabajadoresCantidad"] <= 0) {
		$campoError = "trabajadoresCantidad";
		throw new Exception("Debe especificar la cantidad de empleados de la empresa.");
	}

	if ((!$isSoloPCP) and ($_POST["trabajadoresMasaSalarial"] <= 240)) {
		$campoError = "trabajadoresMasaSalarial";
		throw new Exception("La masa salarial no puede ser inferior a los $240.");
	}

	// Bloque 4..
	if ($isSoloPCP) {
		if ($alta) {
			$params = array(":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
			$sql =
				"SELECT COUNT(*)
					 FROM afi.alt_lugartrabajo_pcp
					WHERE lt_usualta = :usualta
						AND lt_idsolicitud = -1";
		}
		else {
			$params = array(":idsolicitudcotizacion" => $id);
			$sql =
				"SELECT COUNT(*)
					 FROM afi.alt_lugartrabajo_pcp, asa_solicitudafiliacion
					WHERE lt_idsolicitud = sa_id
						AND lt_fechabaja IS NULL
						AND sa_idsolicitudcotizacion = :idsolicitudcotizacion";
		}
		if (valorSql($sql, "", $params, 0) < 1) {
			$campoError = "iframeEstablecimientos";
			throw new Exception("Debe ingresar al menos un (1) lugar de trabajo.");
		}
	}

	// Bloque 7..
	if (($isSoloPCP) and ($_POST["breveDescripcionTareas"] == "")) {
		$campoError = "breveDescripcionTareas";
		throw new Exception("Debe ingresar una Breve descripción de tareas (máximo 250 caracteres).");
	}

	if ($isSoloPCP) {
		$elementosSinChequear = false;
		$arrElementosPunto7 = array("electrico", "incendio", "extintor", "insecticida", "bencina", "raticida", "desinfectantes", "detergentes", "sodaCaustica", "desengrasante",
																"hipocloritoDeSodio", "amoniaco", "acidoMuriatico", "proteccionBalcones", "interiorAltura", "exteriorAltura", "escaleraBaranda", "indumentaria",
																"proteccionPersonal");
		foreach ($arrElementosPunto7 as $valor) {
			if (getValorCheck($valor) == NULL) {
				if (($valor == "extintor") and (getValorCheck("incendio") == "N") and ($_POST["extintorCual"] != ""))
					continue;

				$elementosSinChequear = true;
				break;
			}
		}

		if ($elementosSinChequear	) {
			$campoError = $valor;
			throw new Exception("_Debe completar el cuestionario, 7. DESCRIPCIÓN DE TAREAS Y RIESGOS LABORALES (POSEE CARÁCTER DE DECLARACIÓN JURADA DEL EMPLEADOR)");
		}
	}

	if (($isSoloPCP) and ($_POST["incendio"] == "S") and (getValorCheck("extintor") == NULL) and ($_POST["extintorCual"] != "")) {
		$campoError = "incendioS";
		throw new Exception("Hay inconsistencias en las respuestas de Riesgo de Incendio No y selecciono un item en \"Indique cual\"");
	}

	if (($isSoloPCP) and (getValorCheck("insecticida") == "N") and ($_POST["insecticidaCual"] != "")) {
		$campoError = "insecticidaN";
		throw new Exception("Hay inconsistencias en las respuestas de Riesgo Químico Insecticidas No y completó \"¿Cuáles?\"");
	}

	if (($isSoloPCP) and (getValorCheck("raticida") == "N") and ($_POST["raticidaCual"] != "")) {
		$campoError = "raticidaN";
		throw new Exception("Hay inconsistencias en las respuestas de Riesgo Químico Raticidas No y completó \"¿Cuáles?\"");
	}

	if (($isSoloPCP) and (getValorCheck("interiorAltura") == "N") and ($_POST["interiorAlturaCual"] != "")) {
		$campoError = "interiorAlturaN";
		throw new Exception("Hay inconsistencias en las respuestas de Instalaciones Edilicias, Realizan tareas interiores No y completó \"¿Cuáles?\"");
	}

	if (($isSoloPCP) and (getValorCheck("exteriorAltura") == "N") and ($_POST["exteriorAlturaCual"] != "")) {
		$campoError = "exteriorAlturaN";
		throw new Exception("Hay inconsistencias en las respuestas de Instalaciones Edilicias, Realizan tareas exteriores No y completó \"¿ Cuáles ?\"");
	}

	if (($isSoloPCP) and (getValorCheck("indumentaria") == "N") and ($_POST["indumentariaCual"] != "")) {
		$campoError = "indumentariaN";
		throw new Exception("Hay inconsistencias en las respuestas de Ropa y elementos de trabajo, Entrega indumentaria de trabajo No y completó \"¿Cuáles?\"");
	}

	if (($isSoloPCP) and (getValorCheck("proteccionPersonal") == "N") and ($_POST["proteccionPersonalCual"] != "")) {
		$campoError = "proteccionPersonalN";
		throw new Exception("Hay inconsistencias en las respuestas de Ropa y elementos de trabajo, Entrega de Elementos de protección personal No y completó \"¿Cuáles?\"");
	}
	
	// Bloque 7..
	if ($_POST["lugarSuscripcion"] == "") {
		$campoError = "lugarSuscripcion";
		throw new Exception("Debe ingresar el Lugar.");
	}

	if ($_POST["nombreComercializador"] == "") {
		$campoError = "nombreComercializador";
		throw new Exception("Debe ingresar el Nombre y Apellido.");
	}

	if ($_POST["vendedor"] == "") {
		$campoError = "vendedor";
		throw new Exception("Debe ingresar el Vendedor.");
	}

	if ($idVendedor == -1) {
		$campoError = "vendedor";
		throw new Exception("El Código de Vendedor no está dado de alta para la entidad ".$_SESSION["entidad"].".");
	}

	if (($_SESSION["canal"] != 322) or (($_SESSION["canal"] == 322) and ($datosEmpleadorManual == "N"))) {
		if ($_POST["nombreEmpleador"] == "") {
			$campoError = "nombreEmpleador";
			throw new Exception("El campo Nombre y Apellido del empleador es obligatorio.");
		}

		if ($_POST["cargoEmpleador"] < 1) {
			$campoError = "cargoEmpleador";
			throw new Exception("El campo Cargo/Personería del empleador es obligatorio.");
		}

		if (($_POST["sexoEmpleador"] != "F") and ($_POST["sexoEmpleador"] != "M")) {
			$campoError = "sexoEmpleador";
			throw new Exception("El campo Sexo del empleador es obligatorio.");
		}

		if ($_POST["dniTitular"] == "") {
			$campoError = "dniTitular";
			throw new Exception("El campo D.N.I. del empleador es obligatorio.");
		}

		if (!validarEntero($_POST["dniTitular"])) {
			$campoError = "dniTitular";
			throw new Exception("El campo D.N.I. es inválido.");
		}

		if ($_POST["telefonoEmpleador"] == "") {
			$campoError = "telefonoEmpleador";
			throw new Exception("El campo Teléfono del empleador es obligatorio.");
		}

		if ($_POST["emailTitular"] == "") {
			$campoError = "emailTitular";
			throw new Exception("El campo e-Mail del empleador es obligatorio.");
		}
	}

	if (($_POST["dniTitular"] != "") and (!validarEntero($_POST["dniTitular"]))) {
		$campoError = "dniTitular";
		throw new Exception("El D.N.I. del empleador debe tener un formato válido.");
	}

	if ((($_SESSION["canal"] != 322) or (($_SESSION["canal"] == 322) and ($datosEmpleadorManual == "N"))) and (strlen($_POST["dniTitular"]) < 7)) {
		$campoError = "dniTitular";
		throw new Exception("El D.N.I. del Empleador es inválido.");
	}

	if ($_POST["emailTitular"] != "") {
		$params = array(":email" => $_POST["emailTitular"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			$campoError = "emailTitular";
			throw new Exception("El e-Mail Titular debe tener un formato válido.");
		}
	}

	// Bloque 8..
	if ((!$isSoloPCP) and (!isset($_POST["rgrlImpreso"]))) {
		$campoError = "rgrlImpreso";
		throw new Exception("Debe indicar si tiene impreso el RGRL de cada establecimiento.");
	}

	// Bloque RC..
	if ((isset($_POST["suscribePolizaRC"])) and ($_POST["suscribePolizaRC"] == "S")) {		// Si es una solicitud de cotización y suscribe la póliza de responsibilidad civil, hay que validar lo de abajo..
		if ($sumaAseguradaRC == NULL) {
			$campoError = "sumaAseguradaRC";
			throw new Exception("Debe indicar la Suma Asegurada de la responsibilidad civil.");
		}
		if ($formaPago == NULL) {
			$campoError = "formaPago";
			throw new Exception("Debe indicar la Forma de Pago de la responsibilidad civil.");
		}
		if ($formaPago == "TC") {
			if ($_POST["tarjetaCredito"] == -1) {
				$campoError = "tarjetaCredito";
				throw new Exception("Debe seleccionar la Tarjeta de Crédito.");
			}
			if (strlen($_POST["cbu"]) < 16) {
				$campoError = "cbu";
				throw new Exception("El Nº Tarjeta de Crédito debe tener al menos 16 caracteres.");
			}
		}
		if (($formaPago == "DA") and (strlen($_POST["cbu"]) != 22)) {
			$campoError = "cbu";
			throw new Exception("El C.B.U. de la responsibilidad civil debe tener 22 caracteres.");
		}
		if ($iva == NULL) {
			$campoError = "iva";
			throw new Exception("Debe indicar el I.V.A. de la responsibilidad civil.");
		}
		if ($iibb == NULL) {
			$campoError = "iibb";
			throw new Exception("Debe indicar el I.I.B.B. de la responsibilidad civil.");
		}
		if ($_POST["emailPolizaRC"] == "") {
			$campoError = "emailPolizaRC";
			throw new Exception("Debe indicar el e-Mail donde quiere recepcionar la póliza de la responsibilidad civil.");
		}

		if ($_POST["emailPolizaRC"] != "") {
			$params = array(":email" => $_POST["emailPolizaRC"]);
			$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
			if (valorSql($sql, "", $params) != "S") {
				$campoError = "emailPolizaRC";
				throw new Exception("La Recepción de Póliza vía e-mail debe tener un formato válido.");
			}
		}
	}
}


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_POST["id"]);
try {
	$campoError = "";
	$id = substr($_POST["id"], 1);
	$modulo = substr($_POST["id"], 0, 1);

	// Me fijo si estan dando un alta o una modificación..
	$params = array(":id" => $id);
	if ($modulo == "R")		// Si es una revisión de precio..
		$sql =
			"SELECT sa_id
				 FROM asr_solicitudreafiliacion, asa_solicitudafiliacion
				WHERE sr_id = sa_idrevisionprecio(+)
					AND sr_id = :id";
	else		// Sino, es una solicitud de cotización..
		$sql =
			"SELECT sa_id
				 FROM asc_solicitudcotizacion, asa_solicitudafiliacion
				WHERE sc_id = sa_idsolicitudcotizacion(+)
					AND sc_id = :id";
	$alta = (valorSql($sql, "", $params, 0) == "");


	// Según ticket 23335, dejo esos campos de solo lectura, asi que pongo los valores de esos campos en verdadero..
	$_POST["entregaRgrl"] = "T";
	$_POST["suscribeClausulas"] = "S";

	$_POST["localidad"] = substr($_POST["localidad"], 0, 60);

	$datosEmpleadorManual = "N";
	if (isset($_POST["datosEmpleadorManual"]))
		$datosEmpleadorManual = $_POST["datosEmpleadorManual"];

	if ($_POST["numero"] == "")
		$_POST["numero"] = "S\N";
	$_POST["trabajadoresCantidad"] = trim(str_replace(",", "", str_replace("$", "", $_POST["trabajadoresCantidad"])));
	$_POST["trabajadoresMasaSalarial"] = trim(str_replace(",", "", str_replace("$", "", $_POST["trabajadoresMasaSalarial"])));

	$params = array(":identidad" => $_SESSION["entidad"], ":vendedor" => $_POST["vendedor"]);
	$sql =
		"SELECT ve_id
			 FROM xev_entidadvendedor, xve_vendedor
			WHERE ev_idvendedor = ve_id
				AND ev_identidad = :identidad
				AND ev_fechabaja IS NULL
				AND ve_fechabaja IS NULL
				AND ve_vendedor = :vendedor";
	$idVendedor = valorSql($sql, -1, $params);

	$formaPago = NULL;
	if (isset($_POST["formaPago"]))
		$formaPago = $_POST["formaPago"];

	$iibb = NULL;
	if (isset($_POST["iibb"]))
		$iibb = $_POST["iibb"];

	$iva = NULL;
	if (isset($_POST["iva"]))
		$iva = $_POST["iva"];

	$sumaAseguradaRC = NULL;
	if (isset($_POST["sumaAseguradaRC"]))
		$sumaAseguradaRC = $_POST["sumaAseguradaRC"];

	$isSoloPCP = ($_REQUEST["soloPCP"] == "S");

	$params = array(":id" => $id);
	if ($modulo == "R")		// Si es una revisión de precio..
		$sql =
			"SELECT 'sa_idrevisionprecio' formulariofield, sr_idartanterior idartanterior, sr_nrosolicitud nrosolicitud, sr_costofijocotizado, sr_porcentajevariablecotizado, sr_sector sector,
							sr_statussrt statussrt
				 FROM asr_solicitudreafiliacion
				WHERE sr_id = :id";
	else		// Si es una solicitud de cotización..
		$sql =
			"SELECT 'sa_idsolicitudcotizacion' formulariofield, sc_idartanterior idartanterior, sc_nrosolicitud nrosolicitud, sc_sector sector, sc_statussrt statussrt
				 FROM asc_solicitudcotizacion
				WHERE sc_id = :id";
	$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	$rowCotizacion = DBGetQuery($stmt);


	validar($id, $alta, $rowCotizacion, $isSoloPCP, $idVendedor, $datosEmpleadorManual, $sumaAseguradaRC, $formaPago, $iva, $iibb);


	$params = array(":idvendedor" => $idVendedor, ":identidad" => $_SESSION["entidad"]);
	$sql =
		"SELECT ev_id
			 FROM xev_entidadvendedor
			WHERE ev_fechabaja IS NULL
				AND ev_idvendedor = :idvendedor
				AND ev_identidad = :identidad";
	$idEntidadVendedor = valorSql($sql, "", $params, 0);

	if ($alta) {
		if (!isset($_POST["ciiu2"]))
			$_POST["ciiu2"] = -1;
		if (!isset($_POST["ciiu3"]))
			$_POST["ciiu3"] = -1;

		$estado = "2.1";
		$motivoNoAprobacionTarifa = "";
		$sector = $rowCotizacion["SECTOR"];

		if ($isSoloPCP) {
			$_POST["cargoResponsable"] = $_POST["cargoEmpleador"];
			$_POST["emailResponsable"] = $_POST["emailTitular"];
			$_POST["nombreApellidoResponsable"] = $_POST["nombreEmpleador"];
			$_POST["sexoResponsable"] = $_POST["sexoEmpleador"];
		}

		if ($modulo == "R") {		// Si es una revisión de precio..
			$motivoAlta = "04";
			$sumafija = $rowCotizacion["SR_COSTOFIJOCOTIZADO"];
			$porcvariable = $rowCotizacion["SR_PORCENTAJEVARIABLECOTIZADO"];
		}
		else {
			if (($rowCotizacion["STATUSSRT"] == 2) and ($rowCotizacion["IDARTANTERIOR"] != 51))
				$motivoAlta = "02";
			elseif ((($rowCotizacion["STATUSSRT"] == 5) or ($rowCotizacion["STATUSSRT"] == 6) or ($rowCotizacion["STATUSSRT"] == 7)) and ($rowCotizacion["IDARTANTERIOR"] != 51))
				$motivoAlta = "05";
			else
				$motivoAlta = "03";

			if (!$isSoloPCP) {
				$curs = NULL;
				$params = array(":nrosolicitud" => $rowCotizacion["NROSOLICITUD"], ":sumarffep" => "T");
				$sql = "BEGIN art.cotizacion.get_valor_carta(:nrosolicitud, :data, :sumarffep); END;";
				$stmt2 = DBExecSP($conn, $curs, $sql, $params, true, 0);
				$rowValorFinal = DBGetSP($curs);
				$sumafija = $rowValorFinal["SUMAFIJA"];
				$porcvariable = $rowValorFinal["PORCVARIABLE"];
			}
			else {
				$sumafija = 0;
				$porcvariable = 0;
			}
		}
		formatNumber($sumafija);
		formatNumber($porcvariable);

		$params = array(":alicuotapesos" => formatFloat($sumafija),
										":alicuotaporc" => formatFloat($porcvariable),
										":calle" => substr($_POST["calle"], 0, 60),
										":callepost" => substr($_POST["calle"], 0, 60),
										":cargo" => nullIfCero($_POST["cargoResponsable"]),
										":cargoadmin" => 0,
										":cargotitular" => $_POST["cargoEmpleador"],
										":clausulasadicionales" => $_POST["suscribeClausulas"],
										":condicionanteafip" => $_POST["condicionAnteAfip"],
										":contacto" => $_POST["nombreApellidoResponsable"],
										":cpostal" => $_POST["codigoPostal"],
										":cpostalpost" => $_POST["codigoPostal"],
										":cuit" => str_replace("-", "", $_POST["cuit"]),
										":datosempleadormanual" => nullIsEmpty($datosEmpleadorManual),
										":departamento" => $_POST["oficina"],
										":departamentopost" => $_POST["oficina"],
										":direlectronicacont" => $_POST["emailResponsable"],
										":direlectronicatitular" => $_POST["emailTitular"],
										":documentotitular" => intval($_POST["dniTitular"]),
										":establecimientos" => nullIfCero($_POST["establecimientos"]),
										":estado" => 20,
										":fechaafiliacion" => $_POST["fechaSuscripcion"],
										":fecharecepcion" => $_POST["fechaSuscripcion"],
										":fechavigenciadesde" => $_POST["fechaVigenciaDesde"],
										":fechavigenciahasta" => $_POST["fechaVigenciaHasta"],
										":feinicactiv" => $_POST["fechaInicioActividad"],
										":formaj" => nullIfCero($_POST["formaJuridicaTmp"]),
										":franquicia" => 10,
										":idactividad" => getIdActividad($_POST["ciiu"]),
										":idactividad2" => nullIfCero(getIdActividad($_POST["ciiu2"])),
										":idactividad3" => nullIfCero(getIdActividad($_POST["ciiu3"])),
										":idartanterior" => getArtAnteriorParaAfiliacion($motivoAlta),
										":identidadvendedor" => nullIfCero($idEntidadVendedor),
										":idgrupoeconomico" => nullIfCero($_POST["idGrupoEconomico"]),
										":idrevisionprecio" => (($modulo == "R")?$id:NULL),
										":idsolicitudcotizacion" => (($modulo != "R")?$id:NULL),
										":idsucursal" => nullIfCero($_SESSION["sucursal"]),
										":idusuarioweb" => $_SESSION["idUsuario"],
										":idvendedor" => nullIfCero($idVendedor),
										":localidad" => $_POST["localidad"],
										":localidadpost" => $_POST["localidad"],
										":lugarsuscripcion" => $_POST["lugarSuscripcion"],
										":maillegal" => $_POST["email"],
										":mailpostal" => $_POST["email"],
										":masatotal" => formatFloat($_POST["trabajadoresMasaSalarial"]),
										":motivoalta" => $motivoAlta,
										":motivosnoaprobaciontarifa" => $motivoNoAprobacionTarifa,
										":nivel" => $_POST["nivel"],
										":nombre" => substr($_POST["razonSocial"], 0, 60),
										":nombrevendedor" => $_POST["nombreComercializador"],
										":numero" => $_POST["numero"],
										":numeropost" => $_POST["numero"],
										":observaciones" => substr($_POST["observaciones"], 0, 250),
										":origen" => 5,
										":piso" => $_POST["piso"],
										":pisopost" => $_POST["piso"],
										":porcdescnivel" => 0,
										":porcdescvolumen" => 0,
										":porcmasa" => 0,
										":porcmasatarifa" => 0,
										":presentorgrl" => $_POST["entregaRgrl"],
										":provincia" => $_POST["provincia"],
										":provinciapost" => $_POST["provincia"],
										":recargoesp" => 0,
										":recargoespsobrefijo" => 0,
										":recargosin" => 0,
										":recargosinmontofijo" => 0,
										":recargosinsobrefijo" => 0,
										":rgrlimpreso" => (($isSoloPCP)?"N":$_POST["rgrlImpreso"]),
										":sector" => nullIsEmpty($sector),
										":sexocont" => nullIfCero($_POST["sexoResponsable"]),
										":sexotitular" => nullIfCero($_POST["sexoEmpleador"]),
										":sumafija" => 0,
										":sumafijatarifa" => 0,
										":telefonoscont" => NULL,
										":telefonotitular" => $_POST["telefonoEmpleador"],
										":tipocotizacion" => $modulo,
										":tipodocumentotitular" => "DNI",
										":tipodetarifa" => 20,
										":titular" => $_POST["nombreEmpleador"],
										":totempleados" => $_POST["trabajadoresCantidad"],
										":usualta" => "W_".substr($_SESSION["usuario"], 0, 18),
										":solicitud_pcp" => (($isSoloPCP)?"S":"N"));
		$sql =
			"INSERT INTO asa_solicitudafiliacion
									(sa_alicuotapesos, sa_alicuotaporc, sa_calle, sa_calle_post, sa_cargo, sa_cargoadmin, sa_cargo_titular, sa_clausulasadicionales, sa_condicionanteafip, sa_contacto,
									 sa_cpostal, sa_cpostal_post, sa_cuit, sa_datosempleadormanual, sa_departamento, sa_departamento_post, sa_direlectronica_cont, sa_direlectronica_titular,
									 sa_documento_titular, sa_establecimientos, sa_estado, sa_fechaafiliacion, sa_fechaalta, sa_fecharecepcion,
									 sa_fechavigenciadesde, sa_fechavigenciahasta, sa_feinicactiv, sa_formaj, sa_franquicia, sa_id,
									 sa_idactividad, sa_idactividad2, sa_idactividad3, sa_idartanterior, sa_identidadvendedor, sa_idgrupoeconomico, sa_idrevisionprecio, sa_idsolicitudcotizacion,
									 sa_idsucursal, sa_idusuarioweb, sa_idvendedor, sa_localidad, sa_localidad_post, sa_lugarsuscripcion, sa_mail_legal, sa_mail_postal, sa_masatotal, sa_motivoalta,
									 sa_motivosnoaprobaciontarifa, sa_nivel, sa_nombre, sa_nombre_vendedor, sa_nrointerno, sa_numero, sa_numero_post, sa_observaciones, sa_origen, sa_piso, sa_piso_post,
									 sa_porcdescnivel, sa_porcdescvolumen, sa_porcmasa, sa_porcmasatarifa, sa_presentorgrl, sa_provincia, sa_provincia_post, sa_recargoesp, sa_recargoesp_sobrefijo,
									 sa_recargosin, sa_recargosin_montofijo, sa_recargosin_sobrefijo, sa_rgrlimpreso, sa_sector, sa_sexo_cont, sa_sexo_titular, sa_sumafija, sa_sumafijatarifa,
									 sa_telefonos_cont, sa_telefono_titular, sa_tipocotizacion, sa_tipo_documento_titular, sa_tipodetarifa, sa_titular, sa_totempleados, sa_usualta, sa_solicitud_pcp)
					 VALUES (:alicuotapesos, :alicuotaporc, :calle, :callepost, :cargo, :cargoadmin, :cargotitular, :clausulasadicionales, :condicionanteafip, :contacto,
									 :cpostal, :cpostalpost, :cuit, :datosempleadormanual, :departamento, :departamentopost, :direlectronicacont, :direlectronicatitular,
									 :documentotitular, :establecimientos, :estado, TO_DATE(:fechaafiliacion, 'DD/MM/YYYY'), SYSDATE, TO_DATE(:fecharecepcion, 'DD/MM/YYYY'),
									 TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'), TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'), TO_DATE(:feinicactiv, 'DD/MM/YYYY'), :formaj, :franquicia, afi.seq_asa_id.NEXTVAL,
									 :idactividad, :idactividad2, :idactividad3, :idartanterior, :identidadvendedor, :idgrupoeconomico, :idrevisionprecio, :idsolicitudcotizacion,
									 :idsucursal, :idusuarioweb, :idvendedor, :localidad, :localidadpost, :lugarsuscripcion, :maillegal, :mailpostal, :masatotal, :motivoalta,
									 :motivosnoaprobaciontarifa, :nivel, :nombre, :nombrevendedor, seq_asa_nrointerno.NEXTVAL, :numero, :numeropost, :observaciones, :origen, :piso, :pisopost,
									 :porcdescnivel, :porcdescvolumen, :porcmasa, :porcmasatarifa, :presentorgrl, :provincia, :provinciapost, :recargoesp, :recargoespsobrefijo,
									 :recargosin, :recargosinmontofijo, :recargosinsobrefijo, :rgrlimpreso, :sector, :sexocont, :sexotitular, :sumafija, :sumafijatarifa,
									 :telefonoscont, :telefonotitular, :tipocotizacion, :tipodocumentotitular, :tipodetarifa, :titular, :totempleados, :usualta, :solicitud_pcp)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(sa_id) FROM asa_solicitudafiliacion";
		$idSolicitudAfiliacion = ValorSql($sql, "", array(), 0);

		if ($modulo == "R") {		// Si es una revisión de precio..
/*			$sql =
				"UPDATE asr_solicitudreafiliacion
						SET sr_idformulario = :idformulario
				  WHERE sr_id = :id";
			$params = array(":idformulario" => $idFormulario, ":id" => $id);
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);*/
		}
		else {		// Si es una solicitud de cotización..
			$params = array(":id" => $id);
			$sql =
/*				"UPDATE asc_solicitudcotizacion
						SET sc_idformulario = ".$idFormulario.",
								sc_estado = '13'
				  WHERE sc_id = ".$id;*/
				"UPDATE asc_solicitudcotizacion
						SET sc_estado = '13'
				  WHERE sc_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			actualizarRankingBNA($id, 0);
		}

		// Actualizo los establecimientos..
		if ($modulo == "R") {		// Si es una revisión de precio..
			$params = array(":cuit" => str_replace("-", "", $_POST["cuit"]));
			$sql =
				"SELECT co_contrato
					 FROM aem_empresa, aco_contrato
					WHERE co_idempresa = em_id
						AND em_cuit = :cuit
			 ORDER BY co_vigenciadesde DESC";
			$contratoAnterior = valorSql($sql, "", $params, 0);

			$params = array(":contrato" => $contratoAnterior);
			$sql =
				"SELECT es_id, es_nroestableci, es_nombre, es_nivel, es_observaciones, es_calle, es_localidad, es_cpostal, es_provincia, es_numero, es_piso, es_departamento, es_cpostala,
								es_codareatelefonos, es_telefonos, es_codareafax, es_fax, es_empleados, es_masa, es_idactividad, es_nivel, es_feinicactiv, es_fechainicest, es_empleados, es_masa,
								es_observaciones, es_eventual, es_domicilio, es_superficie, es_descripcionactividad, es_usubaja, es_fechabaja
					 FROM aes_establecimiento
					WHERE es_contrato = :contrato";
			$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			while ($row = DBGetQuery($stmt)) {
				$idEstablecimiento = getSecNextValOracle("AFI.SEQ_ASE_ID", OCI_DEFAULT);

				$params = array(":idsolicitud" => $idSolicitudAfiliacion);
				$sql = "SELECT NVL(MAX(se_nroestableci), 0) + 1 FROM ase_solicitudestablecimiento WHERE se_idsolicitud = :idsolicitud";
				$nroEstablecimiento = valorSql($sql, "", $params, 0);

				$params = array(":calle" => $row["ES_CALLE"],
												":codareafax" => $row["ES_CODAREAFAX"],
												":codareatelefonos" => $row["ES_CODAREATELEFONOS"],
												":cpostal" => $row["ES_CPOSTAL"],
												":cpostala" => $row["ES_CPOSTALA"],
												":departamento" => $row["ES_DEPARTAMENTO"],
												":descripcionactividad" => $row["ES_DESCRIPCIONACTIVIDAD"],
												":domicilio" => $row["ES_DOMICILIO"],
												":empleados" => $row["ES_EMPLEADOS"],
												":fax" => $row["ES_FAX"],
												":fechabaja" => date("d/m/Y"),
												":fechainicest" => $row["ES_FECHAINICEST"],
												":feinicactiv" => $row["ES_FEINICACTIV"],
												":id" => $idEstablecimiento,
												":idactividad" => $row["ES_IDACTIVIDAD"],
												":idsolicitud" => $idSolicitudAfiliacion,
												":localidad" => $row["ES_LOCALIDAD"],
												":masa" => $row["ES_MASA"],
												":nivel" => $row["ES_NIVEL"],
												":nombre" => $row["ES_NOMBRE"],
												":nroestableci" => $nroEstablecimiento,
												":numero" => $row["ES_NUMERO"],
												":observaciones" => $row["ES_OBSERVACIONES"],
												":piso" => $row["ES_PISO"],
												":provincia" => $row["ES_PROVINCIA"],
												":superficie" => $row["ES_SUPERFICIE"],
												":telefonos" => $row["ES_TELEFONOS"],
												":usualta" => "W_".substr($_SESSION["usuario"], 0, 18),
												":usubaja" => "W_".substr($_SESSION["usuario"], 0, 18));
				$sql =
					"INSERT INTO ase_solicitudestablecimiento
											(se_calle, se_codareafax, se_codareatelefonos, se_cpostal, se_cpostala, se_departamento, se_descripcionactividad, se_domicilio, se_empleados, se_fax, se_fechaalta,
											 se_fechabaja, se_fechainicest, se_feinicactiv, se_id, se_idactividad, se_idsolicitud, se_localidad, se_masa, se_nivel, se_nombre, se_nroestableci, se_numero,
											 se_observaciones, se_piso, se_provincia, se_superficie, se_telefonos, se_usualta, se_usubaja)
								VALUES (:calle, :codareafax, :codareatelefonos, :cpostal, :cpostala, :departamento, :descripcionactividad, :domicilio, :empleados, :fax, ART.ACTUALDATE,
												TO_DATE(:fechabaja, 'DD/MM/YYYY'), :fechainicest, :feinicactiv, :id, :idactividad, :idsolicitud, :localidad, :masa, :nivel, :nombre, :nroestableci, :numero,
												:observaciones, :piso, :provincia, :superficie, :telefonos, :usualta, :usubaja)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);

				// Agrego los teléfonos..
				$params = array(":idestablecimiento" => $idEstablecimiento, ":idsolicitud" => $idSolicitudAfiliacion);
				$sql =
					"INSERT INTO asf_solicitudtelefonoestableci
											(sf_area, sf_id, sf_idestablecimiento, sf_idtipotelefono, sf_interno, sf_numero, sf_principal, sf_observacion)
								SELECT te_area, 1, :idestablecimiento, te_idtipotelefono, te_interno, te_numero, te_principal, te_observacion
									FROM ate_telefonoestablecimiento
								 WHERE te_idestablecimiento = :idestablecimiento";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
		}
		else {		// Sino, es una solicitud de cotización..
			$params = array(":idsolicitud" => (($alta)?9:$idSolicitudAfiliacion));
			$sql =
				"SELECT 1
					 FROM ase_solicitudestablecimiento
					WHERE se_idsolicitud = :idsolicitud";
			if (!existeSql($sql, $params, 0)) {		// Si no tiene establecimientos, agrego uno..
				$idEstablecimiento = getSecNextValOracle("AFI.SEQ_ASE_ID", OCI_DEFAULT);

				$params = array(":idsolicitud" => $idSolicitudAfiliacion);
				$sql = "SELECT NVL(MAX(se_nroestableci), 0) + 1 FROM ase_solicitudestablecimiento WHERE se_idsolicitud = :idsolicitud";
				$nroEstablecimiento = valorSql($sql, "", $params, 0);

				$params = array(":calle" => $_POST["calle"],
												":cpostal" => $_POST["codigoPostal"],
												":departamento" => $_POST["oficina"],
												":empleados" => $_POST["trabajadoresCantidad"],
												":feinicactiv" => $_POST["fechaInicioActividad"],
												":id" => $idEstablecimiento,
												":idactividad" => getIdActividad($_POST["ciiu"]),
												":idsolicitud" => $idSolicitudAfiliacion,
												":localidad" => $_POST["localidad"],
												":masa" => formatFloat($_POST["trabajadoresMasaSalarial"]),
												":nivel" => $_POST["nivel"],
												":nombre" => substr($_POST["razonSocial"], 0, 60),
												":nroestableci" => $nroEstablecimiento,
												":numero" => $_POST["numero"],
												":piso" => $_POST["piso"],
												":provincia" => $_POST["provincia"],
												":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
				$sql =
					"INSERT INTO ase_solicitudestablecimiento
											(se_calle, se_cpostal, se_departamento, se_empleados, se_fechaalta, se_feinicactiv, se_id, se_idactividad, se_idsolicitud, se_localidad, se_masa, se_nivel,
											 se_nombre, se_nroestableci, se_numero, se_piso, se_provincia, se_tipoestablecimiento, se_usualta)
								VALUES (:calle, :cpostal, :departamento, :empleados, SYSDATE, TO_DATE(:feinicactiv, 'DD/MM/YYYY'), :id, :idactividad, :idsolicitud, :localidad, :masa, :nivel,
												:nombre, :nroestableci, :numero, :piso, :provincia, 'P', :usualta)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);

				// Agrego los teléfonos..
				$params = array(":idestablecimiento" => $idEstablecimiento, ":idsolicitud" => $idSolicitudAfiliacion);
				$sql =
					"INSERT INTO asf_solicitudtelefonoestableci
											(sf_area, sf_id, sf_idestablecimiento, sf_idtipotelefono, sf_interno, sf_numero, sf_observacion, sf_principal)
								SELECT ts_area, 1, :idestablecimiento, ts_idtipotelefono, ts_interno, ts_numero, ts_observacion, ts_principal
									FROM ats_telefonosolicitud
								 WHERE ts_solicitud = :idsolicitud
									 AND ts_tipo IN ('L', 'X')";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
		}
	}

	if (!$alta) {
		$idSolicitudAfiliacion = $_POST["idSolicitudAfiliacion"];
		$params = array(":calle" => $_POST["calle"],
										":callepost" => $_POST["calle"],
										":cargo" => nullIfCero($_POST["cargoResponsable"]),
										":cargotitular" => $_POST["cargoEmpleador"],
										":clausulasadicionales" => $_POST["suscribeClausulas"],
										":condicionanteafip" => $_POST["condicionAnteAfip"],
										":contacto" => $_POST["nombreApellidoResponsable"],
										":cpostal" => $_POST["codigoPostal"],
										":cpostalpost" => $_POST["codigoPostal"],
										":datosempleadormanual" => nullIsEmpty($datosEmpleadorManual),
										":departamento" => $_POST["oficina"],
										":departamentopost" => $_POST["oficina"],
										":direlectronicacont" => $_POST["emailResponsable"],
										":direlectronicatitular" => $_POST["emailTitular"],
										":documentotitular" => intval($_POST["dniTitular"]),
										":establecimientos" => nullIfCero($_POST["establecimientos"]),
										":fechaafiliacion" => $_POST["fechaSuscripcion"],
										":fecharecepcion" => $_POST["fechaSuscripcion"],
										":fechavigenciadesde" => $_POST["fechaVigenciaDesde"],
										":fechavigenciahasta" => $_POST["fechaVigenciaHasta"],
										":feinicactiv" => $_POST["fechaInicioActividad"],
										":formaj" => nullIfCero($_POST["formaJuridicaTmp"]),
										":idactividad" => getIdActividad($_POST["ciiu"]),
										":identidadvendedor" => nullIfCero($idEntidadVendedor),
										":idvendedor" => nullIfCero($idVendedor),
										":localidad" => $_POST["localidad"],
										":localidadpost" => $_POST["localidad"],
										":lugarsuscripcion" => $_POST["lugarSuscripcion"],
										":maillegal" => $_POST["email"],
										":mailpostal" => $_POST["email"],
										":masatotal" => formatFloat($_POST["trabajadoresMasaSalarial"]),
										":nivel" => $_POST["nivel"],
										":nombre" => substr($_POST["razonSocial"], 0, 60),
										":nombrevendedor" => $_POST["nombreComercializador"],
										":numero" => $_POST["numero"],
										":numeropost" => $_POST["numero"],
										":observaciones" => substr($_POST["observaciones"], 0, 250),
										":piso" => $_POST["piso"],
										":pisopost" => $_POST["piso"],
										":presentorgrl" => $_POST["entregaRgrl"],
										":provincia" => $_POST["provincia"],
										":provinciapost" => $_POST["provincia"],
										":rgrlimpreso" => (($isSoloPCP)?"N":$_POST["rgrlImpreso"]),
										":sexocont" => nullIfCero($_POST["sexoResponsable"]),
										":sexotitular" => nullIfCero($_POST["sexoEmpleador"]),
										":telefonoscont" => NULL,
										":telefonotitular" => $_POST["telefonoEmpleador"],
										":tipocotizacion" => $modulo,
										":titular" => $_POST["nombreEmpleador"],
										":totempleados" => $_POST["trabajadoresCantidad"],
										":usumodif" => "W_".substr($_SESSION["usuario"], 0, 18),
										":id" => $id);
		$sql =
			"UPDATE asa_solicitudafiliacion
					SET sa_calle = :calle,
							sa_calle_post = :callepost,
							sa_cargo = :cargo,
							sa_cargo_titular = :cargotitular,
							sa_clausulasadicionales = :clausulasadicionales,
							sa_condicionanteafip = :condicionanteafip,
							sa_contacto = :contacto,
							sa_cpostal = :cpostal,
							sa_cpostal_post = :cpostalpost,
							sa_datosempleadormanual = :datosempleadormanual,
							sa_departamento = :departamento,
							sa_departamento_post = :departamentopost,
							sa_direlectronica_cont = :direlectronicacont,
							sa_direlectronica_titular = :direlectronicatitular,
							sa_documento_titular = :documentotitular,
							sa_establecimientos = :establecimientos,
							sa_fechaafiliacion = TO_DATE(:fechaafiliacion, 'DD/MM/YYYY'),
							sa_fechamodif = SYSDATE,
							sa_fecharecepcion = TO_DATE(:fecharecepcion, 'DD/MM/YYYY'),
							sa_fechavigenciadesde = TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'),
							sa_fechavigenciahasta = TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'),
							sa_feinicactiv = TO_DATE(:feinicactiv, 'DD/MM/YYYY'),
							sa_formaj = :formaj,
							sa_idactividad = :idactividad,
							sa_identidadvendedor = :identidadvendedor,
							sa_idvendedor = :idvendedor,
							sa_localidad = :localidad,
							sa_localidad_post = :localidadpost,
							sa_lugarsuscripcion = :lugarsuscripcion,
							sa_mail_legal = :maillegal,
							sa_mail_postal = :mailpostal,
							sa_masatotal = :masatotal,
							sa_nivel = :nivel,
							sa_nombre = :nombre,
							sa_nombre_vendedor = :nombrevendedor,
							sa_numero = :numero,
							sa_numero_post = :numeropost,
							sa_observaciones = :observaciones,
							sa_piso = :piso,
							sa_piso_post = :pisopost,
							sa_presentorgrl = :presentorgrl,
							sa_provincia = :provincia,
							sa_provincia_post = :provinciapost,
							sa_rgrlimpreso = :rgrlimpreso,
							sa_sexo_cont = :sexocont,
							sa_sexo_titular = :sexotitular,
							sa_telefonos_cont = :telefonoscont,
							sa_telefono_titular = :telefonotitular,
							sa_tipocotizacion = :tipocotizacion,
							sa_titular = :titular,
							sa_totempleados = :totempleados,
							sa_usumodif = :usumodif
			  WHERE ".$rowCotizacion["FORMULARIOFIELD"]." = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	// Le asigno a los establecimientos nuevos el id de la solicitud..
	if ($isSoloPCP) {
		$params = array(":idsolicitud" => $idSolicitudAfiliacion, ":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
		$sql =
			"UPDATE afi.alt_lugartrabajo_pcp
					SET lt_idsolicitud = :idsolicitud,
							lt_usuarioweb = 'F'
				WHERE lt_idsolicitud = -1
					AND lt_usualta = :usualta
					AND lt_usuarioweb = 'T'";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
	else {
		$params = array(":idsolicitud" => $idSolicitudAfiliacion, ":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
		$sql =
			"UPDATE ase_solicitudestablecimiento
					SET se_idsolicitud = :idsolicitud,
							se_nroestableci = CASE WHEN (LENGTH(se_nroestableci) > 4)
																	THEN to_number(SUBSTR(se_nroestableci, 5))
																	ELSE se_nroestableci
																END,
							se_usuarioweb = 'F'
				WHERE se_idsolicitud = 9
					AND se_usualta = :usualta
					AND se_usuarioweb = 'T'";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	// Guardo los datos del PCP..
	$params = array(":idsolicitud" => $idSolicitudAfiliacion);
	$sql =
		"SELECT 1
			 FROM afi.aap_alicuotas_pcp
			WHERE ap_idsolicitud = :idsolicitud";
	if (!existeSql($sql, $params)) {		// Si no hay datos, los guardo..
		$params = array(":idsolicitud" => $idSolicitudAfiliacion,
										":idsolicitudcotizacion" => $id,
										":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
		$sql =
			"INSERT INTO afi.aap_alicuotas_pcp(ap_alicuota, ap_canttrabajador, ap_fechaalta, ap_idparametro_pcp, ap_idsolicitud, ap_usualta)
																	SELECT cp_alicuota, cp_canttrabajador, SYSDATE, cp_idparametro_pcp, :idsolicitud, :usualta
																		FROM afi.acp_cotizacion_pcp
																	 WHERE cp_idsolicitudcotizacion = :idsolicitudcotizacion";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}


	// Guardo los datos de la descripción de tareas y riesgos laborales..
	$params = array(":idsolicitud" => $idSolicitudAfiliacion);
	$sql =
		"SELECT 1
			 FROM afi.arp_riesgo_pcp
			WHERE rp_idsolicitud = :idsolicitud";
	if (!existeSql($sql, $params)) {		// Alta..
		$params = array(":acidomuriatico" => getValorCheck("acidoMuriatico"),
										":amoniaco" => getValorCheck("amoniaco"),
										":bencina" => getValorCheck("bencina"),
										":descripcion" => substr($_POST["breveDescripcionTareas"], 0, 250),
										":desengrasante" => getValorCheck("desengrasante"),
										":desinfectantes" => getValorCheck("desinfectantes"),
										":detergentes" => getValorCheck("detergentes"),
										":electrico" => getValorCheck("electrico"),
										":escalerabaranda" => getValorCheck("escaleraBaranda"),
										":exterioraltura" => getValorCheck("exteriorAltura"),
										":exterioraltura_cual" => substr($_POST["exteriorAlturaCual"], 0, 100),
										":extintor" => getValorCheck("extintor"),
										":extintor_cual" => substr($_POST["extintorCual"], 0, 100),
										":hipocloritodesodio" => getValorCheck("hipocloritoDeSodio"),
										":idsolicitud" => $idSolicitudAfiliacion,
										":incendio" => getValorCheck("incendio"),
										":indumentaria" => getValorCheck("indumentaria"),
										":indumentaria_cual" => substr($_POST["indumentariaCual"], 0, 100),
										":insecticida" => getValorCheck("insecticida"),
										":insecticida_cual" => substr($_POST["insecticidaCual"], 0, 100),
										":interioraltura" => getValorCheck("interiorAltura"),
										":interioraltura_cual" => substr($_POST["interiorAlturaCual"], 0, 100),
										":otroriesgoquimico" => substr($_POST["otroRiesgoQuimico"], 0, 100),
										":proteccionbalcones" => getValorCheck("proteccionBalcones"),
										":proteccionpersonal" => getValorCheck("proteccionPersonal"),
										":proteccionpersonal_cual" => substr($_POST["proteccionPersonalCual"], 0, 100),
										":raticida" => getValorCheck("raticida"),
										":raticida_cual" => substr($_POST["raticidaCual"], 0, 100),
										":sodacaustica" => getValorCheck("sodaCaustica"),
										":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
		$sql =
			"INSERT INTO afi.arp_riesgo_pcp(rp_acidomuriatico, rp_amoniaco, rp_bencina, rp_descripcion, rp_desengrasante, rp_desinfectantes, rp_detergentes, rp_electrico, rp_escalerabaranda,
																			rp_exterioraltura, rp_exterioraltura_cual, rp_extintor, rp_extintor_cual, rp_fechaalta, rp_hipocloritodesodio, rp_idsolicitud, rp_incendio,
																			rp_indumentaria, rp_indumentaria_cual, rp_insecticida, rp_insecticida_cual, rp_interioraltura, rp_interioraltura_cual, rp_otroriesgoquimico,
																			rp_proteccionbalcones, rp_proteccionpersonal, rp_proteccionpersonal_cual, rp_raticida, rp_raticida_cual, rp_sodacaustica, rp_usualta)
															VALUES (:acidomuriatico, :amoniaco, :bencina, :descripcion, :desengrasante, :desinfectantes, :detergentes, :electrico, :escalerabaranda,
																			:exterioraltura, :exterioraltura_cual, :extintor, :extintor_cual, SYSDATE, :hipocloritodesodio, :idsolicitud, :incendio,
																			:indumentaria, :indumentaria_cual, :insecticida, :insecticida_cual, :interioraltura, :interioraltura_cual, :otroriesgoquimico,
																			:proteccionbalcones, :proteccionpersonal, :proteccionpersonal_cual, :raticida, :raticida_cual, :sodacaustica, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
	else {		// Modificación..
		$params = array(":acidomuriatico" => getValorCheck("acidoMuriatico"),
										":amoniaco" => getValorCheck("amoniaco"),
										":bencina" => getValorCheck("bencina"),
										":descripcion" => substr($_POST["breveDescripcionTareas"], 0, 250),
										":desengrasante" => getValorCheck("desengrasante"),
										":desinfectantes" => getValorCheck("desinfectantes"),
										":detergentes" => getValorCheck("detergentes"),
										":electrico" => getValorCheck("electrico"),
										":escalerabaranda" => getValorCheck("escaleraBaranda"),
										":exterioraltura" => getValorCheck("exteriorAltura"),
										":exterioraltura_cual" => substr($_POST["exteriorAlturaCual"], 0, 100),
										":extintor" => getValorCheck("extintor"),
										":extintor_cual" => substr($_POST["extintorCual"], 0, 100),
										":hipocloritodesodio" => getValorCheck("hipocloritoDeSodio"),
										":idsolicitud" => $idSolicitudAfiliacion,
										":incendio" => getValorCheck("incendio"),
										":indumentaria" => getValorCheck("indumentaria"),
										":indumentaria_cual" => substr($_POST["indumentariaCual"], 0, 100),
										":insecticida" => getValorCheck("insecticida"),
										":insecticida_cual" => substr($_POST["insecticidaCual"], 0, 100),
										":interioraltura" => getValorCheck("interiorAltura"),
										":interioraltura_cual" => substr($_POST["interiorAlturaCual"], 0, 100),
										":otroriesgoquimico" => substr($_POST["otroRiesgoQuimico"], 0, 100),
										":proteccionbalcones" => getValorCheck("proteccionBalcones"),
										":proteccionpersonal" => getValorCheck("proteccionPersonal"),
										":proteccionpersonal_cual" => substr($_POST["proteccionPersonalCual"], 0, 100),
										":raticida" => getValorCheck("raticida"),
										":raticida_cual" => substr($_POST["raticidaCual"], 0, 100),
										":sodacaustica" => getValorCheck("sodaCaustica"),
										":usumodif" => "W_".substr($_SESSION["usuario"], 0, 18));
		$sql =
			"UPDATE afi.arp_riesgo_pcp
					SET rp_acidomuriatico = :acidomuriatico,
							rp_amoniaco = :amoniaco,
							rp_bencina = :bencina,
							rp_descripcion = :descripcion,
							rp_desengrasante = :desengrasante,
							rp_desinfectantes = :desinfectantes,
							rp_detergentes = :detergentes,
							rp_electrico = :electrico,
							rp_escalerabaranda = :escalerabaranda,
							rp_exterioraltura = :exterioraltura,
							rp_exterioraltura_cual = :exterioraltura_cual,
							rp_extintor = :extintor,
							rp_extintor_cual = :extintor_cual,
							rp_fechamodif = SYSDATE,
							rp_hipocloritodesodio = :hipocloritodesodio,
							rp_incendio = :incendio,
							rp_indumentaria = :indumentaria,
							rp_indumentaria_cual = :indumentaria_cual,
							rp_insecticida = :insecticida,
							rp_insecticida_cual = :insecticida_cual,
							rp_interioraltura = :interioraltura,
							rp_interioraltura_cual = :interioraltura_cual,
							rp_otroriesgoquimico = :otroriesgoquimico,
							rp_proteccionbalcones = :proteccionbalcones,
							rp_proteccionpersonal = :proteccionpersonal,
							rp_proteccionpersonal_cual = :proteccionpersonal_cual,
							rp_raticida = :raticida,
							rp_raticida_cual = :raticida_cual,
							rp_sodacaustica = :sodacaustica,
							rp_usumodif = :usumodif
				WHERE rp_idsolicitud = :idsolicitud";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	// Guardo los datos de la Responsabilidad Civil de la solicitud de cotización si corresponde..
	if (($modulo == "C") and (isset($_POST["suscribePolizaRC"]))) {
		$params = array(":id" => $id);
		$sql =
			"SELECT sa_id
				 FROM asa_solicitudafiliacion, asc_solicitudcotizacion
				WHERE sa_idsolicitudcotizacion = sc_id
					AND sc_id = :id";
		$idSolicitudAfi = valorSql($sql, "", $params, 0);

		$params = array(":id" => $idVendedor);
		$sql = "SELECT ve_provinciaseguros FROM xve_vendedor WHERE ve_id = :id";
		$codigoVendedorProvinciaSeguros = valorSql($sql, "", $params, 0);

		$params = array(":codigo" => $_POST["ciiu"]);
		$sql = "SELECT ac_relacion FROM cac_actividad WHERE ac_codigo = :codigo";
		$ciiuProvinciaSeguros = valorSql($sql, "", $params, 0);

		$params = array(":idsolicitudafi" => $idSolicitudAfi);
		$sql = "SELECT 1 FROM art.apr_polizarc WHERE pr_idsolicitudafi = :idsolicitudafi";
		if ((!existeSql($sql, $params, 0)) and ($_POST["suscribePolizaRC"] == "S")) {		// Hago un insert..
			$params = array(":cbu" => $_POST["cbu"],
											":cod_actividad" => $ciiuProvinciaSeguros,
											":cod_productor" => $codigoVendedorProvinciaSeguros,
											":idsolicitudafi" => $idSolicitudAfi,
											":iibb" => $_POST["iibb"],
											":iva" => $_POST["iva"],
											":mail" => $_POST["emailPolizaRC"],
											":medio_pago" => $_POST["formaPago"],
											":origenpago" => $_POST["tarjetaCredito"],
											":poliza" => $_POST["suscribePolizaRC"],
											":sumaasegurada" => $sumaAseguradaRC,
											":tipo_doc" => "DNI",
											":usualta" => substr("W_".$_SESSION["usuario"], 0, 18),
											":valor_rc" => $_POST["polizaRC"]);
			$sql =
				"INSERT INTO art.apr_polizarc
										(pr_cbu, pr_cod_actividad, pr_cod_productor, pr_fechaalta, pr_id, pr_idsolicitudafi, pr_iibb, pr_iva, pr_mail, pr_medio_pago, pr_origenpago, pr_poliza,
										 pr_sumaasegurada, pr_tipo_doc, pr_usualta, pr_valor_rc)
						 VALUES (:cbu, :cod_actividad, :cod_productor, SYSDATE, -1, :idsolicitudafi, :iibb, :iva, :mail, :medio_pago, :origenpago, :poliza,
										 :sumaasegurada, :tipo_doc, :usualta, :valor_rc)";
		}
		else {		// Hago un update..
			$dni = "DNI";
			if ($_POST["suscribePolizaRC"] == "N") {
				$codigoVendedorProvinciaSeguros = NULL;
				$dni = NULL;
				$_POST["cbu"] = NULL;
				$_POST["emailPolizaRC"] = NULL;
				$_POST["formaPago"] = NULL;
				$_POST["iibb"] = NULL;
				$_POST["iva"] = NULL;
			}

			$params = array(":cbu" => $_POST["cbu"],
											":cod_actividad" => $ciiuProvinciaSeguros,
											":cod_productor" => $codigoVendedorProvinciaSeguros,
											":idsolicitudafi" => $idSolicitudAfi,
											":iibb" => $_POST["iibb"],
											":iva" => $_POST["iva"],
											":mail" => $_POST["emailPolizaRC"],
											":medio_pago" => $_POST["formaPago"],
											":origenpago" => $_POST["tarjetaCredito"],
											":poliza" => $_POST["suscribePolizaRC"],
											":sumaasegurada" => $sumaAseguradaRC,
											":tipo_doc" => $dni,
											":usumodif" => substr("W_".$_SESSION["usuario"], 0, 18),
											":valor_rc" => $_POST["polizaRC"]);
			$sql =
				"UPDATE art.apr_polizarc
						SET pr_cbu = :cbu,
								pr_cod_actividad = :cod_actividad,
								pr_cod_productor = :cod_productor,
								pr_fechamodif = SYSDATE,
								pr_iibb = :iibb,
								pr_iva = :iva,
								pr_mail = :mail,
								pr_medio_pago = :medio_pago,
								pr_origenpago = :origenpago,
								pr_poliza = :poliza,
								pr_sumaasegurada = :sumaasegurada,
								pr_tipo_doc = :tipo_doc,
								pr_usumodif = :usumodif,
								pr_valor_rc = :valor_rc
				  WHERE pr_idsolicitudafi = :idsolicitudafi";
		}
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$params = array(":id" => $id,
										":sumaasegurada_rc" => $sumaAseguradaRC,
										":valor_rc" => $_POST["polizaRC"]);
		$sql =
			"UPDATE asc_solicitudcotizacion
					SET sc_sumaasegurada_rc = :sumaasegurada_rc,
							sc_valor_rc = :valor_rc
			  WHERE sc_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		actualizarRankingBNA($id, 0);

		$params = array(":id" => $id);
		$sql =
			"UPDATE asc_solicitudcotizacion
					SET sc_valor_rc = 0
			  WHERE sc_valor_rc < 0
					AND sc_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		actualizarRankingBNA($id, 0);
	}

	// Actualizo los teléfonos del Domicilio..
	$dataTel = inicializarTelefonos(OCI_DEFAULT, "ts_solicitud", $idSolicitudAfiliacion, "ts", "ats_telefonosolicitud", $_SESSION["usuario"]);
	copiarTempATelefonos($dataTel);

	// Actualizo los teléfonos del Responsable ART..
	$dataTel2 = inicializarTelefonos(OCI_DEFAULT, "ts_solicitud", $idSolicitudAfiliacion, "ts", "ats_telefonosolicitud", $_SESSION["usuario"], "X");
	copiarTempATelefonos($dataTel2);

	updateTelefono($idSolicitudAfiliacion);

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script type="text/javascript">
		with (window.parent.document) {
			alert(unescape('<?= rawurlencode($e->getMessage())?>'));
			getElementById('btnGrabar').style.display = 'inline';
			getElementById('imgGuardando').style.display = 'none';
			if (getElementById('<?= $campoError?>') != null) {
				getElementById('<?= $campoError?>').style.backgroundColor = '#f00';
				getElementById('<?= $campoError?>').style.color = '#fff';
				getElementById('<?= $campoError?>').focus();
			}
			setTimeout("window.parent.document.getElementById('<?= $campoError?>').style.backgroundColor = ''; window.parent.document.getElementById('<?= $campoError?>').style.color = '';", 2000);
		}
	</script>
<?
	exit;
}
?>
<script type="text/javascript">
	window.parent.location.href = '/solicitud-afiliacion/<?= $_POST["id"]?>/ok';		// insert=ok..
</script>