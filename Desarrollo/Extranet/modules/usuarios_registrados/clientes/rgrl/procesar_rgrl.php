<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function guardarResponsable($num) {
	global $conn;
	global $idSolicitudRGRL;

	if ($num == 1) {
		$cargo = "R";
		$responsableForm = "S";
	}
	if ($num == 2) {
		$cargo = "H";
		$responsableForm = "N";
	}
	if ($num == 3) {
		$cargo = "M";
		$responsableForm = "N";
	}

	if (($_POST["cuit".$num] != "") or ($_POST["nombre".$num] != "") or ($_POST["representacion".$num] != -1) or ($_POST["tipo".$num] != -1) or ($_POST["titulo".$num] != "") or
			($_POST["entidad".$num]) or ($_POST["matricula".$num] != "") or ($_POST["entidad".$num] != "")) {		// Si se llenó algún campo, guardo..
		if ($_POST["idResponsable".$num] == "") {		// Alta..
			$params = array(":cargo" => $cargo,
											":cuitcuil" => $_POST["cuit".$num],
											":entidad" => $_POST["entidad".$num],
											":idrepresentacion" => $_POST["representacion".$num],
											":idsolicitudfgrl" => $idSolicitudRGRL,
											":matricula" => $_POST["matricula".$num],
											":nombre" => $_POST["nombre".$num],
											":relacion" => nullIfCero($_POST["tipo".$num]),
											":responsableform" => $responsableForm,
											":titulo" => $_POST["titulo".$num],
											":usualta" => substr("W_".$_SESSION["usuario"], 0, 20));
			$sql =
				"INSERT INTO hys.hrw_responsablerelevweb (rw_cargo, rw_cuitcuil, rw_entidad, rw_fechaalta, rw_id, rw_idrepresentacion, rw_idsolicitudfgrl, rw_matricula, rw_nombre, rw_relacion,
																									rw_responsableform, rw_titulo, rw_usualta)
																					VALUES (:cargo, :cuitcuil, :entidad, SYSDATE, -1, :idrepresentacion, :idsolicitudfgrl, :matricula, :nombre, :relacion,
																									:responsableform, :titulo, :usualta)";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
		else {		// Modificación..
			$params = array(":cuitcuil" => $_POST["cuit".$num],
											":entidad" => $_POST["entidad".$num],
											":id" => $_POST["idResponsable".$num],
											":idrepresentacion" => $_POST["representacion".$num],
											":matricula" => $_POST["matricula".$num],
											":nombre" => $_POST["nombre".$num],
											":relacion" => nullIfCero($_POST["tipo".$num]),
											":titulo" => $_POST["titulo".$num],
											":usumodif" => substr("W_".$_SESSION["usuario"], 0, 20));
			$sql =
				"UPDATE hys.hrw_responsablerelevweb
						SET rw_cuitcuil = :cuitcuil,
								rw_entidad = :entidad,
								rw_fechamodif = SYSDATE,
								rw_idrepresentacion = :idrepresentacion,
								rw_matricula = :matricula,
								rw_nombre = :nombre,
								rw_relacion = :relacion,
								rw_titulo = :titulo,
								rw_usumodif = :usumodif
					WHERE rw_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
	}
	else {		// Sino doy la baja..
		$params = array(":id" => $_POST["idResponsable".$num],
										":usubaja" => substr("W_".$_SESSION["usuario"], 0, 20));
		$sql =
			"UPDATE hys.hrw_responsablerelevweb
					SET rw_fechabaja = SYSDATE,
							rw_usubaja = :usubaja
				WHERE rw_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
}

function mostrarAviso() {
	$avisos = array();

	foreach ($_POST as $key => $value)
		if ((substr($key, 0, 9) == "pregunta_") and ($value == "S")) {
			$idTipoFormaAnexo = valorSql("SELECT ia_idtipoformanexo FROM hys.hia_itemanexo WHERE ia_id = :id", "", array(":id" => $_POST["H".$key]), 0);

			$params = array(":id" => $idTipoFormaAnexo);
			$sql =
				"SELECT ta_descripcionvisitainc
					 FROM hys.hta_tipoanexo
					WHERE ta_id = :id";
			$aviso = valorSql($sql, "", $params);
			if ($aviso != "")
				$avisos[] = $aviso;
		}

	$avisos = array_unique($avisos);

	echo "<script type='text/javascript'>";
	foreach ($avisos as $value)
		echo "alert('".$value."');";
	echo "</script>";
}

function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["preguntasAdicionales"] == "t") {		// Valido el formulario de preguntas adicionales..
		// Valido que se contesten todas las preguntas..
		$preguntaContestada = true;
		foreach ($_POST as $key => $value)
			if (substr($key, 0, 10) == "Hpregunta_")
				if (!isset($_POST[substr($key, 1)])) {
					$preguntaContestada = false;
					break;
				}
		if (!$preguntaContestada) {
			echo "errores+= '- Debe contestar todas las preguntas.<br />';";
			$errores = true;
		}

		// Valido que si hay alguna planilla desplegada se haya seleccionado 'si' en algún item..
		$idPlanillas = array();
		foreach ($_POST as $key => $value)
			if (substr($key, 0, 19) == "Hplanilla_pregunta_")
				$idPlanillas[] = $value;

		$preguntaSi = true;
		foreach ($idPlanillas as $id) {
			if (!$preguntaSi)
				break;

			if ((isset($_POST["pregunta_".$id])) and ($_POST["pregunta_".$id] == "S")) {
				$preguntaSi = false;
				foreach ($_POST as $key => $value)
					if ((substr($key, 0, 7) == "Hextra_") and (substr($key, -10 - strlen($id)) == "_pregunta_".$id))
						if ((isset($_POST["extra_".$value])) and ($_POST["extra_".$value] == "S")) {
							$preguntaSi = true;
							break;
						}
			}
		}
		if (!$preguntaSi) {
			echo "errores+= '- Debe seleccionar SÍ en al menos un item de cada planilla.<br />';";
			$errores = true;
		}
	}
	else {		// Valido el formulario RGRL..
		if ($_POST["cantidadTrabajadores"] == "") {
			echo "errores+= '- Debe ingresar la cantidad de trabajadores.<br />';";
			$errores = true;
		}
		else if (!validarEntero($_POST["cantidadTrabajadores"])) {
			echo "errores+= '- La cantidad de trabajadores debe ser un entero válido.<br />';";
			$errores = true;
		}

		// Valido que se contesten todas las preguntas..
		$preguntaContestada = true;
		foreach ($_POST as $key => $value)
			if (substr($key, 0, 10) == "Hpregunta_")
				if (!isset($_POST[substr($key, 1)])) {
					$preguntaContestada = false;
					break;
				}
		if (!$preguntaContestada) {
			echo "errores+= '- Debe contestar todas las preguntas.<br />';";
			$errores = true;
		}

		if ($preguntaContestada) {
			// Valido que si se contesta con N debe requerirse una fecha de regularización solo para los items cuyo campo ia_idtipoformanexo sea null..
			$fechaOk = true;
			foreach ($_POST as $key => $value)
				if (substr($key, 0, 10) == "Hpregunta_"){
					if (($_POST[substr($key, 1)] == "N") and (!isset($_POST["Hplanilla_pregunta_".$value])) and (!isFechaValida($_POST["fecha_".$value]))) {
						$fechaOk = false;
						break;
					}
				}
			if (!$fechaOk) {
				echo "errores+= '- Debe ingresar una fecha de regularización válida para los campos que contestó como \"No\".<br />';";
				$errores = true;
			}
		}

		// La fecha de regularización debe ser mayor a la fecha actual..
		$fechaOk = true;
		foreach ($_POST as $key => $value)
			if (substr($key, 0, 6) == "fecha_")
				if (isset($_POST["pregunta_".substr($key, 6)]))
					if ($_POST["pregunta_".substr($key, 6)] == "N")		// Si la pregunta está cargada como "N"..
						if (($value != "") and (dateDiff(date("d/m/Y"), $value) < 0)) {
							$fechaOk = false;
							break;
						}
		if (!$fechaOk) {
			echo "errores+= '- La Fecha de Regularización debe ser mayor o igual a la fecha actual en todos los casos.<br />';";
			$errores = true;
		}

		// Valido que si hay alguna planilla desplegada se haya seleccionado 'si' en algún item..
		$idPlanillas = array();
		foreach ($_POST as $key => $value)
			if (substr($key, 0, 19) == "Hplanilla_pregunta_")
				$idPlanillas[] = $value;

		$preguntaSi = array("A" => false, "B" => false, "C" => false);
		$planillasTotPreguntasSi = array("A" => 0, "B" => 0, "C" => 0);
		foreach ($idPlanillas as $id)
			if ((isset($_POST["pregunta_".$id])) and ($_POST["pregunta_".$id] == "S"))
				foreach ($_POST as $key => $value)
					if ((substr($key, 0, 7) == "Hextra_") and (substr($key, -10 - strlen($id)) == "_pregunta_".$id)) {
						$preguntaSi[$_POST["Hextra_".$value."_pregunta_".$id."_planilla"]] = true;
						if ((isset($_POST["extra_".$value])) and ($_POST["extra_".$value] == "S")) {
							$planillasTotPreguntasSi[$_POST["Hextra_".$value."_pregunta_".$id."_planilla"]]++;
						}
					}

		if (($preguntaSi["A"]) and ($planillasTotPreguntasSi["A"] == 0)) {
			$errores = true;
			echo "errores+= '- Debe seleccionar SÍ en al menos un item de la planilla A.<br />';";
		}
		if (($preguntaSi["B"]) and ($planillasTotPreguntasSi["B"] == 0)) {
			$errores = true;
			echo "errores+= '- Debe seleccionar SÍ en al menos un item de la planilla B.<br />';";
		}
		if (($preguntaSi["C"]) and ($planillasTotPreguntasSi["C"] == 0)) {
			$errores = true;
			echo "errores+= '- Debe seleccionar SÍ en al menos un item de la planilla C.<br />';";
		}


		// Valido datos de las grillas de abajo..
		$cuitOk = true;
		$nombreGremioOk = true;
		$numeroLegajoOk = true;
		foreach ($_POST as $key => $value) {
			if ($_POST["delegadosGremiales"] == "S") {
				// Nº Legajo del Delegado Gremial..
				if ((substr($key, 0, 13) == "numeroLegajo_") and ($value == ""))
					$numeroLegajoOk = false;

				// Nombre del Gremio..
				if ((substr($key, 0, 7) == "nombre_") and ($value == ""))
					$nombreGremioOk = false;
			}

			if ($_POST["contratistas"] == "S") {
				// C.U.I.T. del Contratista..
				if ((substr($key, 0, 5) == "cuit_") and (($value == "") or (!validarCuit($value))))
					$cuitOk = false;
			}
		}

		if (!$numeroLegajoOk) {
			echo "errores+= '- Debe ingresar el Nº Legajo de todos los delegados gremiales.<br />';";
			$errores = true;
		}

		if (!$nombreGremioOk) {
			echo "errores+= '- Debe ingresar el Nombre del Gremio de todos los delegados gremiales.<br />';";
			$errores = true;
		}

		if (!$cuitOk) {
			echo "errores+= '- Debe ingresar una C.U.I.T. válida para todos los contratistas.<br />';";
			$errores = true;
		}


		// Validación RESPONSABLE DE LOS DATOS DEL FORMULARIO..
		if ($_POST["cuit1"] == "") {
			echo "errores+= '- RESPONSABLE DE LOS DATOS: El campo CUIT/CUIL/CUIP es obligatorio.<br />';";
			$errores = true;
		}
		else if (!validarCuit($_POST["cuit1"])) {
			echo "errores+= '- RESPONSABLE DE LOS DATOS: Debe ingresar una CUIT/CUIL/CUIP válida.<br />';";
			$errores = true;
		}
		if ($_POST["nombre1"] == "") {
			echo "errores+= '- RESPONSABLE DE LOS DATOS: El campo Nombre y Apellido es obligatorio.<br />';";
			$errores = true;
		}
		if ($_POST["representacion1"] == -1) {
			echo "errores+= '- RESPONSABLE DE LOS DATOS: El campo Representación es obligatorio.<br />';";
			$errores = true;
		}


		// Validación PROFESIONAL DE HIGIENE Y SEGURIDAD EN EL TRABAJO..
		if (($_POST["preguntasQueValidaHyS"] != "") and ($_POST["pregunta_".$_POST["preguntasQueValidaHyS"]] == "S")) {
			if ($_POST["cuit2"] == "") {
				echo "errores+= '- PROFESIONAL DE HIGIENE Y SEGURIDAD: El campo CUIT/CUIL/CUIP es obligatorio.<br />';";
				$errores = true;
			}
			else if (!validarCuit($_POST["cuit2"])) {
				echo "errores+= '- PROFESIONAL DE HIGIENE Y SEGURIDAD: Debe ingresar una CUIT/CUIL/CUIP válida.<br />';";
				$errores = true;
			}
			if ($_POST["nombre2"] == "") {
				echo "errores+= '- PROFESIONAL DE HIGIENE Y SEGURIDAD: El campo Nombre y Apellido es obligatorio.<br />';";
				$errores = true;
			}
			if ($_POST["representacion2"] == -1) {
				echo "errores+= '- PROFESIONAL DE HIGIENE Y SEGURIDAD: El campo Representación es obligatorio.<br />';";
				$errores = true;
			}
			if ($_POST["tipo2"] == -1) {
				echo "errores+= '- PROFESIONAL DE HIGIENE Y SEGURIDAD: El campo Tipo es obligatorio.<br />';";
				$errores = true;
			}
			if ($_POST["titulo2"] == "") {
				echo "errores+= '- PROFESIONAL DE HIGIENE Y SEGURIDAD: El campo Título Habilitante es obligatorio.<br />';";
				$errores = true;
			}
			if ($_POST["matricula2"] == "") {
				echo "errores+= '- PROFESIONAL DE HIGIENE Y SEGURIDAD: El campo Nº Matrícula es obligatorio.<br />';";
				$errores = true;
			}
			if ($_POST["entidad2"] == "") {
				echo "errores+= '- PROFESIONAL DE HIGIENE Y SEGURIDAD: El campo Entidad que otorgó el título habilitante es obligatorio.<br />';";
				$errores = true;
			}
		}


		// Validación PROFESIONAL DE MEDICINA LABORAL..
		if (($_POST["preguntasQueValidaML"] != "") and ($_POST["pregunta_".$_POST["preguntasQueValidaML"]] == "S")) {
			if ($_POST["cuit3"] == "") {
				echo "errores+= '- PROFESIONAL DE MEDICINA LABORAL: El campo CUIT/CUIL/CUIP es obligatorio.<br />';";
				$errores = true;
			}
			else if (!validarCuit($_POST["cuit3"])) {
				echo "errores+= '- PROFESIONAL DE MEDICINA LABORAL: Debe ingresar una CUIT/CUIL/CUIP válida.<br />';";
				$errores = true;
			}
			if ($_POST["nombre3"] == "") {
				echo "errores+= '- PROFESIONAL DE MEDICINA LABORAL: El campo Nombre y Apellido es obligatorio.<br />';";
				$errores = true;
			}
			if ($_POST["representacion3"] == -1) {
				echo "errores+= '- PROFESIONAL DE MEDICINA LABORAL: El campo Representación es obligatorio.<br />';";
				$errores = true;
			}
			if ($_POST["tipo3"] == -1) {
				echo "errores+= '- PROFESIONAL DE MEDICINA LABORAL: El campo Tipo es obligatorio.<br />';";
				$errores = true;
			}
			if ($_POST["titulo3"] == "") {
				echo "errores+= '- PROFESIONAL DE MEDICINA LABORAL: El campo Título Habilitante es obligatorio.<br />';";
				$errores = true;
			}
			if ($_POST["matricula3"] == "") {
				echo "errores+= '- PROFESIONAL DE MEDICINA LABORAL: El campo Nº Matrícula es obligatorio.<br />';";
				$errores = true;
			}
			if ($_POST["entidad3"] == "") {
				echo "errores+= '- PROFESIONAL DE MEDICINA LABORAL: El campo Entidad que otorgó el título habilitante es obligatorio.<br />';";
				$errores = true;
			}
		}
	}



	if ($errores) {
		echo "getElementById('btnGrabar').style.display = 'block';";
		echo "getElementById('spanProcesando').style.display = 'none';";
		echo "getElementById('errores').innerHTML = errores;";
		echo "getElementById('divErrores').style.display = 'inline';";
		echo "getElementById('foco').style.display = 'block';";
		echo "getElementById('foco').focus();";
		echo "getElementById('foco').style.display = 'none';";
	}
	else {
		echo "getElementById('divErrores').style.display = 'none';";
	}

	echo "}";
	echo "</script>";

	return !$errores;
}


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 100));

// Valido que el rgrl sea del establecimiento relacionado al contrato del usuario..
$params = array(":contrato" => $_SESSION["contrato"], ":id" => $_POST["idEstablecimiento"]);
$sql =
	"SELECT 1
		 FROM aes_establecimiento
		WHERE es_contrato = :contrato
			AND es_id = :id";
validarSesion(ExisteSql($sql, $params));


try {
	$_POST["cuit1"] = sacarGuiones($_POST["cuit1"]);
	$_POST["cuit2"] = sacarGuiones($_POST["cuit2"]);
	$_POST["cuit3"] = sacarGuiones($_POST["cuit3"]);

	if (!validar())
		exit;

	if (($_POST["preguntasAdicionales"] != "t") and ($_POST["mostrarAviso"] == "t"))
		mostrarAviso();

	if ($_POST["preguntasAdicionales"] == "t") {		// Guardo las preguntas adicionales..
		// Guardo el registro maestro si no existe..
		$params = array(":idestablecimiento" => $_POST["idEstablecimiento"]);
		$sql = "SELECT sf_id FROM hys.hsf_solicitudfgrl WHERE sf_idestablecimiento = :idestablecimiento";
		$idSolicitudRGRL = valorSql($sql, "0", $params, 0);

		if (intval($idSolicitudRGRL) < 1) {
			$params = array(":idestablecimiento" => $_POST["idEstablecimiento"],
											":idestablecimiento2" => $_POST["idEstablecimiento"],
											":usualta" => substr("W_".$_SESSION["usuario"], 0, 20));
			$sql =
				"INSERT INTO hys.hsf_solicitudfgrl (sf_fechaalta, sf_id, sf_idresolucionanexo, sf_idestablecimiento, sf_usualta)
																		VALUES (SYSDATE, 1, art.hys.get_idresolucion463(:idestablecimiento, 'C'), :idestablecimiento2, :usualta)";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			$idSolicitudRGRL = valorSql("SELECT MAX(sf_id) FROM hys.hsf_solicitudfgrl", "", array(), 0);
		}


		// Loopeo por todas las preguntas..
		foreach ($_POST as $key => $value) {
			if (substr($key, 0, 9) == "pregunta_") {
				$idTipoFormaAnexo = valorSql("SELECT pa_idtipoformanexo FROM hys.hpa_preguntaadicional WHERE pa_id = :id", "", array(":id" => $_POST["H".$key]), 0);

				$params = array(":idestablecimiento" => $_POST["idEstablecimiento"],
												":idpreguntaadicional" => $_POST["H".$key],
												":respuesta" => $value,
												":usualta" => substr("W_".$_SESSION["usuario"], 0, 20));
				$sql =
					"INSERT INTO hys.hra_respuestaadicional (ra_fechaalta, ra_id, ra_idpreguntaadicional, ra_idestablecimiento, ra_respuesta, ra_usualta)
																					 VALUES (SYSDATE, 1, :idpreguntaadicional, :idestablecimiento, :respuesta, :usualta)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);

				// Guardo las planillas..
				if (isset($_POST["Hplanilla_pregunta_".$_POST["H".$key]])) {		// Si la pregunta tiene planilla..
					// Guardo la cabecera de la planilla..
					$params = array(":idsolicitudfgrl" => $idSolicitudRGRL,
													":idtipoanexo" => $idTipoFormaAnexo);
					$sql =
						"SELECT sp_id
							 FROM hys.hsp_solicitudplanillafgrl
							WHERE sp_idsolicitudfgrl = :idsolicitudfgrl
								AND sp_idtipoanexo = :idtipoanexo";
					$idItem = valorSql($sql, -1, $params, 0);
					if ($idItem < 1) {		// Es un alta..
						$params = array(":idsolicitudfgrl" => $idSolicitudRGRL,
														":idtipoanexo" => $idTipoFormaAnexo,
														":usualta" => substr("W_".$_SESSION["usuario"], 0, 20));
						$sql =
							"INSERT INTO hys.hsp_solicitudplanillafgrl (sp_fechaalta, sp_id, sp_idsolicitudfgrl, sp_idtipoanexo, sp_usualta)
																									VALUES (SYSDATE, 1, :idsolicitudfgrl, :idtipoanexo, :usualta)";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
						$idSolicitudPlanillaRGRL = valorSql("SELECT MAX(sp_id) FROM hys.hsp_solicitudplanillafgrl", "", array(), 0);
					}
					else {
						$params = array(":id" => $idItem, ":usumodif" => substr("W_".$_SESSION["usuario"], 0, 20));
						$sql =
							"UPDATE hys.hsp_solicitudplanillafgrl
									SET sp_fechamodif = SYSDATE,
											sp_usumodif = :usumodif
							  WHERE sp_id = :id";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
						$idSolicitudPlanillaRGRL = $idItem;
					}


					// Guardo los items..
					foreach ($_POST as $key2 => $value2) {
						if ((substr($key2, 0, 7) == "Hextra_") and (substr($key2, strpos($key2, "_pregunta_")) == "_pregunta_".$_POST["H".$key])) {
							$arr = explode("_", $key2);
							$cumplimiento = "N";
							if (($value == "S") and (isset($_POST["extra_".$arr[1]])))		// Si la pregunta padre se contestó como SI y si se eligió un item..
								$cumplimiento = $_POST["extra_".$arr[1]];

							$params = array(":cumplimiento" => $cumplimiento,
															":idsolicitudplanillafgrl" => $idSolicitudPlanillaRGRL,
															":iditemtipoanexo" => $arr[1],
															":usualta" => substr("W_".$_SESSION["usuario"], 0, 20));
							$sql =
								"INSERT INTO hys.hsi_solicituditemsplanillafgrl (si_cumplimiento, si_fechaalta, si_id, si_iditemtipoanexo, si_idsolicitudplanillafgrl, si_usualta)
																												 VALUES (:cumplimiento, SYSDATE, 1, :iditemtipoanexo, :idsolicitudplanillafgrl, :usualta)";
							DBExecSql($conn, $sql, $params, OCI_DEFAULT);
						}
					}
				}
			}
		}
	}
	else {		// Guardo los datos del formulario RGRL..
		$huboCambios = false;

		// Guardo el registro maestro si no existe..
		$params = array(":idestablecimiento" => $_POST["idEstablecimiento"]);
		$sql = "SELECT sf_id FROM hys.hsf_solicitudfgrl WHERE sf_idestablecimiento = :idestablecimiento";
		$idSolicitudRGRL = valorSql($sql, "0", $params, 0);
		if (intval($idSolicitudRGRL) < 1) {
			$params = array(":empleados" => $_POST["cantidadTrabajadores"],
											":idestablecimiento" => $_POST["idEstablecimiento"],
											":idestablecimiento2" => $_POST["idEstablecimiento"],
											":usualta" => substr("W_".$_SESSION["usuario"], 0, 20));
			$sql =
				"INSERT INTO hys.hsf_solicitudfgrl (sf_empleados, sf_fechaalta, sf_id, sf_idresolucionanexo, sf_idestablecimiento, sf_usualta)
																		VALUES (:empleados, SYSDATE, 1, art.hys.get_idresolucion463(:idestablecimiento, 'C'), :idestablecimiento2, :usualta)";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			$idSolicitudRGRL = ValorSql("SELECT MAX(sf_id) FROM hys.hsf_solicitudfgrl", "", array(), 0);
		}
		else {
			$params = array(":empleados" => $_POST["cantidadTrabajadores"], ":id" => $idSolicitudRGRL);
			$sql =
				"UPDATE hys.hsf_solicitudfgrl
						SET sf_empleados = :empleados
					WHERE sf_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}


		// Loopeo por todas las preguntas..
		foreach ($_POST as $key => $value) {
			if (substr($key, 0, 9) == "pregunta_") {
				$idTipoFormaAnexo = ValorSql("SELECT ia_idtipoformanexo FROM hys.hia_itemanexo WHERE ia_id = :id", "", array(":id" => $_POST["H".$key]), 0);

				$fechaRegularizacion = NULL;
				if (($value == "N") and (isset($_POST["fecha_".$_POST["H".$key]])))
					$fechaRegularizacion = $_POST["fecha_".$_POST["H".$key]];

				$sql =
					"SELECT st_id
						 FROM hys.hst_solicituditemsfgrl
						WHERE st_idsolicitudfgrl = :idsolicitudfgrl
							AND st_iditem = :iditem";
				$params = array(":idsolicitudfgrl" => $idSolicitudRGRL, ":iditem" => $_POST["H".$key]);
				$idItem = valorSql($sql, -1, $params, 0);
				if ($idItem < 1) {		// Es un alta..
					$params = array(":cumplimiento" => $value,
													":fecharegularizacion" => $fechaRegularizacion,
													":iditem" => $_POST["H".$key],
													":idsolicitudfgrl" => $idSolicitudRGRL,
													":usualta" => substr("W_".$_SESSION["usuario"], 0, 20));
					$sql =
						"INSERT INTO hys.hst_solicituditemsfgrl (st_cumplimiento, st_fechaalta, st_fecharegularizacion, st_id, st_iditem, st_idsolicitudfgrl, st_usualta)
																						 VALUES (:cumplimiento, SYSDATE, TO_DATE(:fecharegularizacion, 'dd/mm/yyyy'), 1, :iditem, :idsolicitudfgrl, :usualta)";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
					$huboCambios = true;
				}
				else {		// Es una modificación..
					// Me fijo si se modificó algún valor..
					$params = array(":id" => $idItem);
					$sql =
						"SELECT st_cumplimiento, st_fecharegularizacion
							 FROM hys.hst_solicituditemsfgrl
							WHERE st_id = :id";
					$stmt = DBExecSql($conn, $sql, $params);
					$row = DBGetQuery($stmt);
					if (($row["ST_CUMPLIMIENTO"] != $value) or ($row["ST_FECHAREGULARIZACION"] != $fechaRegularizacion))
						$huboCambios = true;


					$params = array(":cumplimiento" => $value,
													":fecharegularizacion" => $fechaRegularizacion,
													":id" => $idItem,
													":usumodif" => substr("W_".$_SESSION["usuario"], 0, 20));
					$sql =
						"UPDATE hys.hst_solicituditemsfgrl
								SET st_cumplimiento = :cumplimiento,
										st_fechamodif = SYSDATE,
										st_fecharegularizacion = :fecharegularizacion,
										st_usumodif = :usumodif
						  WHERE st_id = :id";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				}


				// Guardo las planillas..
				if (isset($_POST["Hplanilla_pregunta_".$_POST["H".$key]])) {		// Si la pregunta tiene planilla..
					// Actualizo la cabecera de la planilla..
					$params = array(":idsolicitudfgrl" => $idSolicitudRGRL, ":idtipoanexo" => $idTipoFormaAnexo);
					$sql =
						"SELECT sp_id
							 FROM hys.hsp_solicitudplanillafgrl
							WHERE sp_idsolicitudfgrl = :idsolicitudfgrl
								AND sp_idtipoanexo = :idtipoanexo";
					$idItem = valorSql($sql, -1, $params, 0);
					if ($idItem < 1) {		// Es un alta..
						$params = array(":idsolicitudfgrl" => $idSolicitudRGRL,
														":idtipoanexo" => $idTipoFormaAnexo,
														":usualta" => substr("W_".$_SESSION["usuario"], 0, 20));
						$sql =
							"INSERT INTO hys.hsp_solicitudplanillafgrl (sp_fechaalta, sp_id, sp_idsolicitudfgrl, sp_idtipoanexo, sp_usualta)
																									VALUES (SYSDATE, 1, :idsolicitudfgrl, :idtipoanexo, :usualta)";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
						$idSolicitudPlanillaRGRL = valorSql("SELECT MAX(sp_id) FROM hys.hsp_solicitudplanillafgrl", "", array(), 0);
					}
					else {
						$params = array(":id" => $idItem, ":usumodif" => substr("W_".$_SESSION["usuario"], 0, 20));
						$sql =
							"UPDATE hys.hsp_solicitudplanillafgrl
									SET sp_fechamodif = SYSDATE,
											sp_usumodif = :usumodif
							  WHERE sp_id = :id";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
						$idSolicitudPlanillaRGRL = $idItem;
					}

					// Guardo los items..
					foreach ($_POST as $key2 => $value2) {
						if ((substr($key2, 0, 7) == "Hextra_") and (substr($key2, strpos($key2, "_pregunta_")) == "_pregunta_".$_POST["H".$key])) {
							$arr = explode("_", $key2);

							$cumplimiento = "N";
							if (($value == "S") and (isset($_POST["extra_".$arr[1]])))		// Si le puso que si a la pregunta padre y si a la pregunta hija..
								$cumplimiento = $_POST["extra_".$arr[1]];

							$params = array(":iditemtiponexo" => $arr[1], ":idsolicitudplanillafgrl" => $idSolicitudPlanillaRGRL);
							$sql =
								"SELECT si_id
									 FROM hys.hsi_solicituditemsplanillafgrl
									WHERE si_idsolicitudplanillafgrl = :idsolicitudplanillafgrl
										AND si_iditemtipoanexo = :iditemtiponexo";
							$idItem = valorSql($sql, -1, $params, 0);
							if ($idItem < 1) {		// Es un alta..
								$params = array(":cumplimiento" => $cumplimiento,
																":iditemtipoanexo" => $arr[1],
																":idsolicitudplanillafgrl" => $idSolicitudPlanillaRGRL,
																":usualta" => substr("W_".$_SESSION["usuario"], 0, 20));
								$sql =
									"INSERT INTO hys.hsi_solicituditemsplanillafgrl (si_cumplimiento, si_fechaalta, si_id, si_iditemtipoanexo, si_idsolicitudplanillafgrl, si_usualta)
																													 VALUES (:cumplimiento, SYSDATE, 1, :iditemtipoanexo, :idsolicitudplanillafgrl, :usualta)";
								DBExecSql($conn, $sql, $params, OCI_DEFAULT);
								$huboCambios = true;
							}
							else {
								$params = array(":cumplimiento" => $cumplimiento, ":id" => $idItem);
								$sql =
									"SELECT 1
										 FROM hys.hsi_solicituditemsplanillafgrl
										WHERE si_cumplimiento = :cumplimiento
											AND si_id = :id";
								if (!existeSql($sql, $params))
									$huboCambios = true;

								$params = array(":cumplimiento" => $cumplimiento,
																":id" => $idItem,
																":usumodif" => substr("W_".$_SESSION["usuario"], 0, 20));
								$sql =
									"UPDATE hys.hsi_solicituditemsplanillafgrl
											SET si_cumplimiento = :cumplimiento,
													si_fechamodif = SYSDATE,
													si_usumodif = :usumodif
									  WHERE si_id = :id";
								DBExecSql($conn, $sql, $params, OCI_DEFAULT);
							}
						}
					}
				}
			}
		}

		// Actualizo la versión del formulario..
		if ($huboCambios) {
			$params = array(":idestablecimiento" => $_POST["idEstablecimiento"]);
			$sql =
				"UPDATE hys.hsf_solicitudfgrl
						SET sf_version = sf_version + 1
				  WHERE sf_idestablecimiento = :idestablecimiento";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}

		// Guardo los datos de las grillas..
		foreach ($_POST as $key => $value) {
			// Datos gremiales..
			if (substr($key, 0, 18) == "idDelegadoGremial_") {
				if ($value == -1) {		// Alta..
					$params = array(":idsolicitudfgrl" => $idSolicitudRGRL,
													":nombregremio" => $_POST["nombre_".substr($key, 18)],
													":nrolegajo" => $_POST["numeroLegajo_".substr($key, 18)],
													":usualta" => substr("W_".$_SESSION["usuario"], 0, 20));
					$sql =
						"INSERT INTO hys.hrw_relevgremialistaweb (rw_fechaalta, rw_id, rw_idsolicitudfgrl, rw_nombregremio, rw_nrolegajo, rw_usualta)
																							VALUES (SYSDATE, -1, :idsolicitudfgrl, :nombregremio, :nrolegajo, :usualta)";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				}
				else {		// Modificación..
					$params = array(":id" => $value,
													":nombregremio" => $_POST["nombre_".substr($key, 18)],
													":nrolegajo" => $_POST["numeroLegajo_".substr($key, 18)],
													":usumodif" => substr("W_".$_SESSION["usuario"], 0, 20));
					$sql =
						"UPDATE hys.hrw_relevgremialistaweb
								SET rw_fechamodif = SYSDATE,
										rw_nombregremio = :nombregremio,
										rw_nrolegajo = :nrolegajo,
										rw_usumodif = :usumodif
							WHERE rw_id = :id";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				}
			}

			// Datos de contratistas..
			if (substr($key, 0, 14) == "idContratista_") {
				if ($value == -1) {		// Alta..
					$params = array(":cuit" => $_POST["cuit_".substr($key, 14)],
													":idsolicitudfgrl" => $idSolicitudRGRL,
													":usualta" => substr("W_".$_SESSION["usuario"], 0, 20));
					$sql =
						"INSERT INTO hys.hrw_relevcontratistaweb (rw_cuit, rw_fechaalta, rw_id, rw_idsolicitudfgrl, rw_usualta)
																							VALUES (:cuit, SYSDATE, -1, :idsolicitudfgrl, :usualta)";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				}
				else {		// Modificación..
					$params = array(":cuit" => $_POST["cuit_".substr($key, 14)],
													":id" => $value,
													":usumodif" => substr("W_".$_SESSION["usuario"], 0, 20));
					$sql =
						"UPDATE hys.hrw_relevcontratistaweb
								SET rw_cuit = :cuit,
										rw_fechamodif = SYSDATE,
										rw_usumodif = :usumodif
							WHERE rw_id = :id";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				}
			}
		}


		// Bajas gremiales..
		$bajas = explode(",", $_POST["bajasGremiales"]);
		for ($i=0; $i < count($bajas); $i++) {
			$params = array(":id" => $bajas[$i], ":usubaja" => substr("W_".$_SESSION["usuario"], 0, 20));
			$sql =
				"UPDATE hys.hrw_relevgremialistaweb
						SET rw_fechabaja = SYSDATE,
								rw_usubaja = :usubaja
					WHERE rw_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}

		// Bajas contratistas..
		$bajas = explode(",", $_POST["bajasContratistas"]);
		for ($i=0; $i < count($bajas); $i++) {
			$params = array(":id" => $bajas[$i], ":usubaja" => substr("W_".$_SESSION["usuario"], 0, 20));
			$sql =
				"UPDATE hys.hrw_relevcontratistaweb
						SET rw_fechabaja = SYSDATE,
								rw_usubaja = :usubaja
					WHERE rw_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}



		// Se usan estos campos por compatibilidad..
		$_POST["tipo1"] = -1;
		$_POST["titulo1"] = "";
		$_POST["matricula1"] = "";
		$_POST["entidad1"] = "";

		guardarResponsable(1);
		guardarResponsable(2);
		guardarResponsable(3);
	}

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function redirect() {
		window.parent.parent.divWin.close();
<?
if ($_POST["preguntasAdicionales"] == "t") {		// Si son las preguntas adicionales, abro el form rgrl..
?>
		window.parent.parent.abrirVentanaRGRL(<?= $_POST["idEstablecimiento"]?>);
<?
}
else {		// Sino imprimo el rgrl que acaba de guardar..
?>
		window.parent.parent.location.reload(true);
		window.open('/modules/solicitud_afiliacion/reporte_rgrl.php?idestablecimiento=<?= $_POST["idEstablecimiento"]?>', 'extranetWindow');
<?
}
?>
	}

	setTimeout('redirect()', 3000);

<?
if ($_POST["preguntasAdicionales"] == "t") {		// Si son las preguntas adicionales, abro el form rgrl..
?>
		window.parent.document.getElementById('guardadoOk').innerHTML = 'Datos guardados exitosamente.';
<?
}
else {		// Sino imprimo el rgrl que acaba de guardar..
?>
		window.parent.document.getElementById('guardadoOk').innerHTML = 'Luego de completar el formulario debe imprimirlo y enviarlo a esta ART <b>firmado</b>, a: Gerencia de Prevención: Carlos Pellegrini 91 - CP 1009 - Ciudad Autónoma de Buenos Aires. Una vez cargado se le remitirá un e-mail, a la dirección por Uds. informada, detallando el estado del mismo.';
<?
}
?>

	window.parent.document.getElementById('guardadoOk').style.display = 'block';
	window.parent.document.getElementById('spanProcesando').style.display = 'none';
	window.parent.document.getElementById('foco').style.display = 'block';
	window.parent.document.getElementById('foco').focus();
	window.parent.document.getElementById('foco').style.display = 'none';
</script>