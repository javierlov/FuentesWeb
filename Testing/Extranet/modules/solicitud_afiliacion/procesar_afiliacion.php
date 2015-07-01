<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
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
	return ValorSql($sql, "", $params, 0);
}

function setFocus($fieldName) {
	echo "<script type='text/javascript'>window.parent.document.getElementById('".$fieldName."').focus()</script>";
}


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_POST["id"]);
try {
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
	$alta = (ValorSql($sql, "", $params, 0) == "");


	// Según ticket 23335, dejo esos campos de solo lectura, asi que pongo los valores de esos campos en verdadero..
	$_POST["entregaRgrl"] = "T";
	$_POST["suscribeClausulas"] = "S";

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
	$idVendedor = ValorSql($sql, -1, $params);

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


	$params = array(":id" => $id);
	if ($modulo == "R")		// Si es una revisión de precio..
		$sql =
			"SELECT 'sa_idrevisionprecio' formulariofield, sr_idartanterior idartanterior, sr_nrosolicitud nrosolicitud,
							sr_costofijocotizado, sr_porcentajevariablecotizado, sr_sector sector, sr_statussrt statussrt
				 FROM asr_solicitudreafiliacion
				WHERE sr_id = :id";
	else		// Si es una solicitud de cotización..
		$sql =
			"SELECT 'sa_idsolicitudcotizacion' formulariofield, sc_idartanterior idartanterior,
							sc_nrosolicitud nrosolicitud, sc_sector sector, sc_statussrt statussrt
				 FROM asc_solicitudcotizacion
				WHERE sc_id = :id";
	$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	$rowCotizacion = DBGetQuery($stmt);

	
	// *******  INICIO VALIDACIONES  *******
	// Validación 1..
	if (!isset($_SESSION["isAgenteComercial"]))
		throw new Exception("Usted no tiene permiso para acceder a este módulo.");

	// Validación 1.0.1..
	if ($_POST["nivel"] == "")
		throw new Exception("Debe indicar el nivel de cumplimiento en Higiene y Seguridad.");

	// Validación 1.1.1..
	if (!isset($_POST["rgrlImpreso"]))
		throw new Exception("Debe indicar si tiene impreso el RGRL de cada establecimiento.");

	// Validación 1.1.2..
	if ((isset($_POST["suscribePolizaRC"])) and ($_POST["suscribePolizaRC"] == "S")) {		// Si es una solicitud de cotización y suscribe la póliza de responsibilidad civil, hay que validar lo de abajo..
		if ($sumaAseguradaRC == NULL)
			throw new Exception("Debe indicar la Suma Asegurada de la responsibilidad civil.");
		if ($formaPago == NULL)
			throw new Exception("Debe indicar la Forma de Pago de la responsibilidad civil.");
		if ($formaPago == "TC") {
			if ($_POST["tarjetaCredito"] == -1)
				throw new Exception("Debe seleccionar la Tarjeta de Crédito.");
			if (strlen($_POST["cbu"]) < 16)
				throw new Exception("El Nº Tarjeta de Crédito debe tener al menos 16 caracteres.");
		}
		if (($formaPago == "DA") and (strlen($_POST["cbu"]) != 22))
			throw new Exception("El C.B.U. de la responsibilidad civil debe tener 22 caracteres.");
		if ($iva == NULL)
			throw new Exception("Debe indicar el I.V.A. de la responsibilidad civil.");
		if ($iibb == NULL)
			throw new Exception("Debe indicar el I.I.B.B. de la responsibilidad civil.");
		if ($_POST["emailPolizaRC"] == "")
			throw new Exception("Debe indicar el e-Mail donde quiere recepcionar la póliza de la responsibilidad civil.");
	}

	// Validación 1.2..
	if ($_POST["fechaSuscripcion"] != "") {
		$params = array(":id" => $id, ":fechaalta" => $_POST["fechaSuscripcion"]);
		if ($modulo == "R")		// Si es una revisión de precio..
			$sql =
				"SELECT 1
					 FROM asr_solicitudreafiliacion
					WHERE sr_id = :id
						AND TRUNC(sr_fechaalta) > TO_DATE(:fechaalta)";
		else		// Si es una solicitud de cotización..
			$sql =
				"SELECT 1
					 FROM asc_solicitudcotizacion
					WHERE sc_id = :id
						AND TRUNC(sc_fechaalta) > TO_DATE(:fechaalta)";
		if (ExisteSql($sql, $params))
			throw new Exception("La Fecha de Suscripción no puede ser anterior a la fecha de la solicitud de cotización.");

		// Validación 1.3..
		$params = array(":fechasuscripcion" => $_POST["fechaSuscripcion"], ":fechavencimiento" => $_POST["fechaVencimiento"]);
		$sql =
			"SELECT 1
				 FROM DUAL
				WHERE TO_DATE(:fechasuscripcion) > TO_DATE(:fechavencimiento)";
		if (ExisteSql($sql, $params))
			throw new Exception("La Fecha de Suscripción no puede ser posterior a la fecha de vigencia de la cotización (".$_POST["fechaVencimiento"].").");

		// Validación 1.4..
		if ($alta) {
			$params = array(":fechasuscripcion" => $_POST["fechaSuscripcion"]);
			$sql =
				"SELECT 1
					 FROM DUAL
					WHERE TO_DATE(:fechasuscripcion) < ART.ACTUALDATE";
			if (ExisteSql($sql, $params))
				throw new Exception("La Fecha de Suscripción no puede ser anterior a la fecha actual.");
		}
		else {
			$params = array(":fechasuscripcion" => $_POST["fechaSuscripcion"], ":id" => $id);
			$sql =
				"SELECT 1
					 FROM asa_solicitudafiliacion
					WHERE ((TO_DATE(:fechasuscripcion) < TRUNC(sa_fechaalta)) OR (TO_DATE(:fechasuscripcion) > ART.ACTUALDATE))
						AND TRUNC(sa_fechaafiliacion) <> TO_DATE(:fechasuscripcion)
						AND ".$rowCotizacion["FORMULARIOFIELD"]." = :id";
			if (ExisteSql($sql, $params))
				throw new Exception("La Fecha de Suscripción no puede ser ni anterior a la fecha previamente cargada ni posterior a la fecha actual.");
		}
	}

	// Validación 2..
	if ($_POST["fechaInicioActividad"] != "") {
		$arrFecha = split("/", $_POST["fechaInicioActividad"]);
		$fec = $arrFecha[2].$arrFecha[1].$arrFecha[0];
		if ($fec > date("Ymd"))
			throw new Exception("La fecha de inicio de Actividad no puede ser posterior a la fecha de alta.");
	}

	// Validación 3..
	if ($_POST["trabajadoresCantidad"] <= 0)
		throw new Exception("Debe especificar la cantidad de empleados de la empresa.");

	// Validación 4..
	if ($_POST["trabajadoresMasaSalarial"] <= 240)
		throw new Exception("La masa salarial no puede ser inferior a los $240.");

	// Validación 4.1.0..
	$params = array(":fechavigenciadesde" => $_POST["fechaVigenciaDesde"]);
	$sql =
		"SELECT 1
			 FROM DUAL
			WHERE TO_DATE(:fechavigenciadesde) < TO_DATE('01/01/1996')";
	if (ExisteSql($sql, $params))
		throw new Exception("La vigencia no puede ser anterior al 01/01/1996.");

	// Validación 4.1.1..
	$params = array(":fechavigenciadesde" => $_POST["fechaVigenciaDesde"], ":fechasuscripcion" => $_POST["fechaSuscripcion"]);
	$sql =
		"SELECT 1
			 FROM DUAL
			WHERE TO_DATE(:fechavigenciadesde) < TO_DATE(:fechasuscripcion)";
	if (ExisteSql($sql, $params))
		throw new Exception("La vigencia no puede ser anterior a la fecha de suscripción.");

	// Validación 4.2..
	if ($idVendedor == -1)
		throw new Exception("El Código de Vendedor no está dado de alta para la entidad ".$_SESSION["entidad"].".");

	// Validación 4.5..
	if (($_SESSION["canal"] != 322) or (($_SESSION["canal"] == 322) and ($datosEmpleadorManual == "N"))) {
		if ($_POST["nombreEmpleador"] == "") {
			setFocus("nombreEmpleador");
			throw new Exception("El campo Nombre y Apellido del empleador es obligatorio.");
		}

		if ($_POST["cargoEmpleador"] < 1) {
			setFocus("cargoEmpleador");
			throw new Exception("El campo Cargo/Personería del empleador es obligatorio.");
		}

		if (($_POST["sexoEmpleador"] != "F") and ($_POST["sexoEmpleador"] != "M")) {
			setFocus("sexoEmpleador");
			throw new Exception("El campo Sexo del empleador es obligatorio.");
		}

		if ($_POST["dniTitular"] == "") {
			setFocus("dniTitular");
			throw new Exception("El campo D.N.I. del empleador es obligatorio.");
		}

		if (!validarEntero($_POST["dniTitular"])) {
			setFocus("dniTitular");
			throw new Exception("El campo D.N.I. es inválido.");
		}

		if ($_POST["telefonoEmpleador"] == "") {
			setFocus("telefonoEmpleador");
			throw new Exception("El campo Teléfono del empleador es obligatorio.");
		}

		if ($_POST["emailTitular"] == "") {
			setFocus("emailTitular");
			throw new Exception("El campo e-Mail del empleador es obligatorio.");
		}
	}

	// Validación 5 - Pedido por PMarrone el 16.2.2011..
	if ((($_SESSION["canal"] != 322) or (($_SESSION["canal"] == 322) and ($datosEmpleadorManual == "N"))) and (strlen($_POST["dniTitular"]) < 7))
		throw new Exception("El D.N.I. del Empleador es inválido.");

	// Validación 6.. - Se comenta por ticket 35597..
//	if (intval($_POST["cargoEmpleador"]) < 1)
//		throw new Exception("Debe seleccionar el cargo del empleador.");
	// *******  FIN VALIDACIONES  *******



	$params = array(":idvendedor" => $idVendedor, ":identidad" => $_SESSION["entidad"]);
	$sql =
		"SELECT ev_id
			 FROM xev_entidadvendedor
			WHERE ev_fechabaja IS NULL
				AND ev_idvendedor = :idvendedor
				AND ev_identidad = :identidad";
	$idEntidadVendedor = ValorSql($sql, "", $params, 0);

	if ($alta) {
		$estado = '2.1';
		$motivoNoAprobacionTarifa = "";
		$sector = $rowCotizacion["SECTOR"];

		if ($modulo == "R") {		// Si es una revisión de precio..
			$motivoAlta = "04";
			$sumafija = $rowCotizacion["SR_COSTOFIJOCOTIZADO"];
			$porcvariable = $rowCotizacion["SR_PORCENTAJEVARIABLECOTIZADO"];
		}
		else {
			if (($rowCotizacion["STATUSSRT"] == 2) and ($rowCotizacion["IDARTANTERIOR"] != 51))
				$motivoAlta = "02";
			elseif ((($rowCotizacion["STATUSSRT"] == 5) or ($rowCotizacion["STATUSSRT"] == 6) or ($rowCotizacion["STATUSSRT"] == 7)) and
						($rowCotizacion["IDARTANTERIOR"] != 51))
				$motivoAlta = "05";
			else
				$motivoAlta = "03";

			$curs = NULL;
			$params = array(":nrosolicitud" => $rowCotizacion["NROSOLICITUD"], ":sumarffep" => "T");
			$sql = "BEGIN art.cotizacion.get_valor_carta(:nrosolicitud, :data, :sumarffep); END;";
			$stmt2 = DBExecSP($conn, $curs, $sql, $params, true, 0);
			$rowValorFinal = DBGetSP($curs);
			$sumafija = $rowValorFinal["SUMAFIJA"];
			$porcvariable = $rowValorFinal["PORCVARIABLE"];
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
										":rgrlimpreso" => $_POST["rgrlImpreso"],
										":sector" => nullIsEmpty($sector),
										":sexocont" => $_POST["sexoResponsable"],
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
										":usualta" => "W_".substr($_SESSION["usuario"], 0, 18));
		$sql =
			"INSERT INTO asa_solicitudafiliacion
									(sa_alicuotapesos, sa_alicuotaporc, sa_calle, sa_calle_post, sa_cargo, sa_cargoadmin,
									 sa_cargo_titular, sa_clausulasadicionales, sa_condicionanteafip, sa_contacto, sa_cpostal,
									 sa_cpostal_post, sa_cuit, sa_datosempleadormanual, sa_departamento, sa_departamento_post,
									 sa_direlectronica_cont, sa_direlectronica_titular, sa_documento_titular, sa_establecimientos,
									 sa_estado, sa_fechaafiliacion, sa_fechaalta, sa_fecharecepcion, sa_fechavigenciadesde,
									 sa_fechavigenciahasta, sa_feinicactiv, sa_formaj, sa_franquicia, sa_id, sa_idactividad,
									 sa_idactividad2, sa_idactividad3, sa_idartanterior, sa_identidadvendedor,
									 sa_idgrupoeconomico, sa_idrevisionprecio, sa_idsolicitudcotizacion, sa_idsucursal,
									 sa_idusuarioweb, sa_idvendedor, sa_localidad, sa_localidad_post, sa_lugarsuscripcion,
									 sa_mail_legal, sa_mail_postal, sa_masatotal, sa_motivoalta, sa_motivosnoaprobaciontarifa,
									 sa_nivel, sa_nombre, sa_nombre_vendedor, sa_nrointerno, sa_numero, sa_numero_post,
									 sa_observaciones, sa_origen, sa_piso, sa_piso_post, sa_porcdescnivel, sa_porcdescvolumen,
									 sa_porcmasa, sa_porcmasatarifa, sa_presentorgrl, sa_provincia, sa_provincia_post, sa_recargoesp,
									 sa_recargoesp_sobrefijo, sa_recargosin, sa_recargosin_montofijo, sa_recargosin_sobrefijo,
									 sa_rgrlimpreso, sa_sector, sa_sexo_cont, sa_sexo_titular, sa_sumafija, sa_sumafijatarifa,
									 sa_telefonos_cont, sa_telefono_titular, sa_tipocotizacion, sa_tipo_documento_titular,
									 sa_tipodetarifa, sa_titular, sa_totempleados, sa_usualta)
					 VALUES (:alicuotapesos, :alicuotaporc, :calle, :callepost, :cargo, :cargoadmin,
									 :cargotitular, :clausulasadicionales, :condicionanteafip, :contacto, :cpostal,
									 :cpostalpost, :cuit, :datosempleadormanual, :departamento, :departamentopost,
									 :direlectronicacont, :direlectronicatitular, :documentotitular, :establecimientos,
									 :estado, TO_DATE(:fechaafiliacion, 'dd/mm/yyyy'), SYSDATE, TO_DATE(:fecharecepcion, 'dd/mm/yyyy'), TO_DATE(:fechavigenciadesde, 'dd/mm/yyyy'),
									 TO_DATE(:fechavigenciahasta, 'dd/mm/yyyy'), TO_DATE(:feinicactiv, 'dd/mm/yyyy'), :formaj, :franquicia, afi.seq_asa_id.NEXTVAL, :idactividad,
									 :idactividad2, :idactividad3, :idartanterior, :identidadvendedor,
									 :idgrupoeconomico, :idrevisionprecio, :idsolicitudcotizacion, :idsucursal,
									 :idusuarioweb, :idvendedor, :localidad, :localidadpost, :lugarsuscripcion,
									 :maillegal, :mailpostal, :masatotal, :motivoalta, :motivosnoaprobaciontarifa,
									 :nivel, :nombre, :nombrevendedor, seq_asa_nrointerno.NEXTVAL, :numero, :numeropost,
									 :observaciones, :origen, :piso, :pisopost, :porcdescnivel, :porcdescvolumen,
									 :porcmasa, :porcmasatarifa, :presentorgrl, :provincia, :provinciapost, :recargoesp,
									 :recargoespsobrefijo, :recargosin, :recargosinmontofijo, :recargosinsobrefijo,
									 :rgrlimpreso, :sector, :sexocont, :sexotitular, :sumafija, :sumafijatarifa,
									 :telefonoscont, :telefonotitular, :tipocotizacion, :tipodocumentotitular,
									 :tipodetarifa, :titular, :totempleados, :usualta)";
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
			$contratoAnterior = ValorSql($sql, "", $params, 0);

			$params = array(":contrato" => $contratoAnterior);
			$sql =
				"SELECT es_id, es_nroestableci, es_nombre, es_nivel, es_observaciones, es_calle, es_localidad, es_cpostal,
								es_provincia, es_numero, es_piso, es_departamento, es_cpostala, es_codareatelefonos, es_telefonos,
								es_codareafax, es_fax, es_empleados, es_masa, es_idactividad, es_nivel, es_feinicactiv,
								es_fechainicest, es_empleados, es_masa, es_observaciones, es_eventual, es_domicilio, es_superficie,
								es_descripcionactividad, es_usubaja, es_fechabaja
					 FROM aes_establecimiento
					WHERE es_contrato = :contrato";
			$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			while ($row = DBGetQuery($stmt)) {
				$idEstablecimiento = GetSecNextValOracle("AFI.SEQ_ASE_ID", OCI_DEFAULT);

				$params = array(":idsolicitud" => $idSolicitudAfiliacion);
				$sql = "SELECT NVL(MAX(se_nroestableci), 0) + 1 FROM ase_solicitudestablecimiento WHERE se_idsolicitud = :idsolicitud";
				$nroEstablecimiento = ValorSql($sql, "", $params, 0);

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
											 (se_calle, se_codareafax, se_codareatelefonos, se_cpostal, se_cpostala, se_departamento,
												se_descripcionactividad, se_domicilio, se_empleados, se_fax, se_fechaalta, se_fechabaja,
												se_fechainicest, se_feinicactiv, se_id, se_idactividad, se_idsolicitud, se_localidad,
												se_masa, se_nivel, se_nombre, se_nroestableci, se_numero, se_observaciones, se_piso,
												se_provincia, se_superficie, se_telefonos, se_usualta, se_usubaja)
								VALUES (:calle, :codareafax, :codareatelefonos, :cpostal, :cpostala, :departamento,
												:descripcionactividad, :domicilio, :empleados, :fax, ART.ACTUALDATE, TO_DATE(:fechabaja, 'dd/mm/yyyy'),
												:fechainicest, :feinicactiv, :id, :idactividad, :idsolicitud, :localidad,
												:masa, :nivel, :nombre, :nroestableci, :numero, :observaciones, :piso,
												:provincia, :superficie, :telefonos, :usualta, :usubaja)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);

				// Agrego los teléfonos..
				$params = array(":idestablecimiento" => $idEstablecimiento, ":idsolicitud" => $idSolicitudAfiliacion);
				$sql =
					"INSERT INTO asf_solicitudtelefonoestableci
											(sf_area, sf_id, sf_idestablecimiento, sf_idtipotelefono, sf_interno, sf_numero, sf_principal,
											 sf_observacion)
								SELECT te_area, 1, :idestablecimiento, te_idtipotelefono, te_interno, te_numero, te_principal,
											 te_observacion
									FROM ate_telefonoestablecimiento
								 WHERE te_idestablecimiento = :idestablecimiento";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
		}
		else {		// Sino, es una solicitud de cotización..
			$params = array(":idsolicitud" => $idSolicitudAfiliacion);
			$sql =
				"SELECT 1
					 FROM ase_solicitudestablecimiento
					WHERE se_idsolicitud = :idsolicitud";
			if (!ExisteSql($sql, $params, 0)) {		// Si no tiene establecimientos, agrego uno..
				$idEstablecimiento = GetSecNextValOracle("AFI.SEQ_ASE_ID", OCI_DEFAULT);

				$params = array(":idsolicitud" => $idSolicitudAfiliacion);
				$sql = "SELECT NVL(MAX(se_nroestableci), 0) + 1 FROM ase_solicitudestablecimiento WHERE se_idsolicitud = :idsolicitud";
				$nroEstablecimiento = ValorSql($sql, "", $params, 0);

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
											 (se_calle, se_cpostal, se_departamento, se_empleados, se_fechaalta, se_feinicactiv, se_id,
												se_idactividad, se_idsolicitud, se_localidad, se_masa, se_nivel, se_nombre, se_nroestableci,
												se_numero, se_piso, se_provincia, se_tipoestablecimiento, se_usualta)
								VALUES (:calle, :cpostal, :departamento, :empleados, SYSDATE, TO_DATE(:feinicactiv, 'dd/mm/yyyy'), :id,
												:idactividad, :idsolicitud, :localidad, :masa, :nivel, :nombre, :nroestableci,
												:numero, :piso, :provincia, 'P', :usualta)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);

				// Agrego los teléfonos..
				$params = array(":idestablecimiento" => $idEstablecimiento, ":idsolicitud" => $idSolicitudAfiliacion);
				$sql =
					"INSERT INTO asf_solicitudtelefonoestableci
											 (sf_area, sf_id, sf_idestablecimiento, sf_idtipotelefono, sf_interno, sf_numero,
												sf_observacion, sf_principal)
								 SELECT ts_area, 1, :idestablecimiento, ts_idtipotelefono, ts_interno, ts_numero,
												ts_observacion, ts_principal
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
										":rgrlimpreso" => $_POST["rgrlImpreso"],
										":sexocont" => $_POST["sexoResponsable"],
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
							sa_fechaafiliacion = TO_DATE(:fechaafiliacion, 'dd/mm/yyyy'),
							sa_fechamodif = SYSDATE,
							sa_fecharecepcion = TO_DATE(:fecharecepcion, 'dd/mm/yyyy'),
							sa_fechavigenciadesde = TO_DATE(:fechavigenciadesde, 'dd/mm/yyyy'),
							sa_fechavigenciahasta = TO_DATE(:fechavigenciahasta, 'dd/mm/yyyy'),
							sa_feinicactiv = TO_DATE(:feinicactiv, 'dd/mm/yyyy'),
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

	// Guardo los datos de la Responsabilidad Civil de la solicitud de cotización si corresponde..
	if (($modulo == "C") and (isset($_POST["suscribePolizaRC"]))) {
		$params = array(":id" => $id);
		$sql =
			"SELECT sa_id
				 FROM asa_solicitudafiliacion, asc_solicitudcotizacion
				WHERE sa_idsolicitudcotizacion = sc_id
					AND sc_id = :id";
		$idSolicitudAfi = ValorSql($sql, "", $params, 0);

		$params = array(":id" => $idVendedor);
		$sql = "SELECT ve_provinciaseguros FROM xve_vendedor WHERE ve_id = :id";
		$codigoVendedorProvinciaSeguros = ValorSql($sql, "", $params, 0);

		$params = array(":codigo" => $_POST["ciiu"]);
		$sql = "SELECT ac_relacion FROM cac_actividad WHERE ac_codigo = :codigo";
		$ciiuProvinciaSeguros = ValorSql($sql, "", $params, 0);

		$params = array(":idsolicitudafi" => $idSolicitudAfi);
		$sql = "SELECT 1 FROM art.apr_polizarc WHERE pr_idsolicitudafi = :idsolicitudafi";
		if ((!ExisteSql($sql, $params, 0)) and ($_POST["suscribePolizaRC"] == "S")) {		// Hago un insert..
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
											":sumaasegurada" => $_POST["sumaAseguradaRC"],
											":tipo_doc" => "DNI",
											":usualta" => substr("W_".$_SESSION["usuario"], 0, 20),
											":valor_rc" => $_POST["polizaRC"]);
			$sql =
				"INSERT INTO art.apr_polizarc
										 (pr_cbu, pr_cod_actividad, pr_cod_productor, pr_fechaalta, pr_id, pr_idsolicitudafi, pr_iibb,
											pr_iva, pr_mail, pr_medio_pago, pr_origenpago, pr_poliza, pr_sumaasegurada, pr_tipo_doc,
											pr_usualta, pr_valor_rc)
							VALUES (:cbu, :cod_actividad, :cod_productor, SYSDATE, -1, :idsolicitudafi, :iibb,
											:iva, :mail, :medio_pago, :origenpago, :poliza, :sumaasegurada, :tipo_doc,
											:usualta, :valor_rc)";
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
											":sumaasegurada" => $_POST["sumaAseguradaRC"],
											":tipo_doc" => $dni,
											":usumodif" => substr("W_".$_SESSION["usuario"], 0, 20),
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
										":sumaasegurada_rc" => $_POST["sumaAseguradaRC"],
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

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script type="text/javascript">
		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
		window.parent.document.getElementById('btnGrabar').style.display = 'inline';
		window.parent.document.getElementById('imgGuardando').style.display = 'none';
	</script>
<?
	exit;
}
?>
<script type="text/javascript">
	window.parent.location.href = '/index.php?pageid=30&id=<?= $_POST["id"]?>&i=k';		// insert=ok..
</script>