<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


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


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idModulo"]);

try {
	if (!validar())
		exit;

	if (($_POST["preguntasAdicionales"] != "t") and ($_POST["mostrarAviso"] == "t"))
		mostrarAviso();

	if ($_POST["preguntasAdicionales"] == "t") {		// Guardo las preguntas adicionales..
		// Guardo el registro maestro si no existe..
		$idSolicitudRGRL = valorSql("SELECT sf_id FROM hys.hsf_solicitudfgrl WHERE sf_idsolicitudestablecimiento = :idsolicitudestablecimiento", "0", array(":idsolicitudestablecimiento" => $_POST["idEstablecimiento"]), 0);
		if (intval($idSolicitudRGRL) < 1) {
			$params = array(":idestablecimiento" => $_POST["idEstablecimiento"],
											":idestablecimiento2" => $_POST["idEstablecimiento"],
											":usualta" => "W_".$_SESSION["usuario"]);
			$sql =
				"INSERT INTO hys.hsf_solicitudfgrl (sf_fechaalta, sf_id, sf_idresolucionanexo, sf_idsolicitudestablecimiento, sf_usualta)
																		VALUES (SYSDATE, 1, art.hys.get_idresolucion463(:idestablecimiento), :idestablecimiento2, :usualta)";
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
												":usualta" => "W_".$_SESSION["usuario"]);
				$sql =
					"INSERT INTO hys.hra_respuestaadicional (ra_fechaalta, ra_id, ra_idpreguntaadicional, ra_idsolicitudestablecimiento, ra_respuesta, ra_usualta)
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
														":usualta" => "W_".$_SESSION["usuario"]);
						$sql =
							"INSERT INTO hys.hsp_solicitudplanillafgrl (sp_fechaalta, sp_id, sp_idsolicitudfgrl, sp_idtipoanexo, sp_usualta)
																									VALUES (SYSDATE, 1, :idsolicitudfgrl, :idtipoanexo, :usualta)";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
						$idSolicitudPlanillaRGRL = valorSql("SELECT MAX(sp_id) FROM hys.hsp_solicitudplanillafgrl", "", array(), 0);
					}
					else {
						$params = array(":id" => $idItem, ":usumodif" => "W_".$_SESSION["usuario"]);
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
															":usualta" => "W_".$_SESSION["usuario"]);
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
		$idSolicitudRGRL = valorSql("SELECT sf_id FROM hys.hsf_solicitudfgrl WHERE sf_idsolicitudestablecimiento = :idsolicitudestablecimiento", "0", array(":idsolicitudestablecimiento" => $_POST["idEstablecimiento"]), 0);
		if (intval($idSolicitudRGRL) < 1) {
			$params = array(":idestablecimiento" => $_POST["idEstablecimiento"],
											":idestablecimiento2" => $_POST["idEstablecimiento"],
											":usualta" => "W_".$_SESSION["usuario"]);
			$sql =
				"INSERT INTO hys.hsf_solicitudfgrl (sf_fechaalta, sf_id, sf_idresolucionanexo, sf_idsolicitudestablecimiento, sf_usualta)
																		VALUES (SYSDATE, 1, art.hys.get_idresolucion463(:idestablecimiento), :idestablecimiento2, :usualta)";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			$idSolicitudRGRL = valorSql("SELECT MAX(sf_id) FROM hys.hsf_solicitudfgrl", "", array(), 0);
		}


		// Loopeo por todas las preguntas..
		foreach ($_POST as $key => $value) {
			if (substr($key, 0, 9) == "pregunta_") {
				$idTipoFormaAnexo = valorSql("SELECT ia_idtipoformanexo FROM hys.hia_itemanexo WHERE ia_id = :id", "", array(":id" => $_POST["H".$key]), 0);

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
													":usualta" => "W_".$_SESSION["usuario"]);
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
													":usumodif" => "W_".$_SESSION["usuario"]);
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
														":usualta" => "W_".$_SESSION["usuario"]);
						$sql =
							"INSERT INTO hys.hsp_solicitudplanillafgrl (sp_fechaalta, sp_id, sp_idsolicitudfgrl, sp_idtipoanexo, sp_usualta)
																									VALUES (SYSDATE, 1, :idsolicitudfgrl, :idtipoanexo, :usualta)";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
						$idSolicitudPlanillaRGRL = ValorSql("SELECT MAX(sp_id) FROM hys.hsp_solicitudplanillafgrl", "", array(), 0);
					}
					else {
						$params = array(":id" => $idItem, ":usumodif" => "W_".$_SESSION["usuario"]);
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
																":usualta" => "W_".$_SESSION["usuario"]);
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
																":usumodif" => "W_".$_SESSION["usuario"]);
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
			$params = array(":idsolicitudestablecimiento" => $_POST["idEstablecimiento"]);
			$sql =
				"UPDATE hys.hsf_solicitudfgrl
						SET sf_version = sf_version + 1
				  WHERE sf_idsolicitudestablecimiento = :idsolicitudestablecimiento";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
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
		window.parent.parent.document.getElementById('iframeEstablecimientos').contentWindow.location.reload(true);
		window.parent.parent.divWin.close();
<?
if ($_POST["preguntasAdicionales"] == "t") {		// Si son las preguntas adicionales, abro el form rgrl..
?>
		window.parent.parent.abrirVentanaRGRL('<?= $_REQUEST["idModulo"]?>', <?= $_POST["idEstablecimiento"]?>);
<?
}
?>
	}

	setTimeout('redirect()', 1500);
	window.parent.document.getElementById('guardadoOk').style.display = 'block';
	window.parent.document.getElementById('spanProcesando').style.display = 'none';
	window.parent.document.getElementById('foco').style.display = 'block';
	window.parent.document.getElementById('foco').focus();
	window.parent.document.getElementById('foco').style.display = 'none';
</script>