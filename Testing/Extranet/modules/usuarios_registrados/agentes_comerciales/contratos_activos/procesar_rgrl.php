<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
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


function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

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
			if (substr($key, 0, 10) == "Hpregunta_") {
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


	// Guardo el registro maestro si no existe..
	array(":contrato" => $_POST["contrato"], ":estableci" => $_POST["nro"])
	$sql =
		"SELECT rl_id
			 FROM hys.hrl_relevriesgolaboral
			WHERE rl_contrato = :contrato
				AND rl_estableci = :estableci"
	$idSolicitudRGRL = ValorSql($sql, "0", $params, 0);
	if (intval($idSolicitudRGRL) < 1) {
		$params = array(":cantempleados" => ???,
										":contrato" => $_POST["contrato"],
										":duplicado" => ???,
										":estableci" => $_POST["nro"],
										":fechaexport" => ???,
										":fechaformbaja" => ???,
										":fechaformulario" => ???,
										":fechavigencia" => ???,
										":fueradetermino" => ???,
										":idcontratohist" => ???,
										":idresolucionanexo" => ???,
										":origen" => ???,
										":procedencia" => ???,
										":requiererelev" => ???,
										":sindatos" => ???,
										":sinpersonal" => ???,
										":usualta" => "W_".$_SESSION["usuario"],
										":usuexport" => ???,
										":valido" => ???,
										":vigencia" => ???);
		$sql =
			"INSERT INTO hys.hrl_relevriesgolaboral
									 (rl_cantempleados, rl_contrato, rl_duplicado, rl_estableci, rl_fechaalta, rl_fechaexport, rl_fechaformbaja, rl_fechaformulario, rl_fecharecepcion, rl_fechavigencia,
										rl_fueradetermino, rl_id, rl_idcontratohist, rl_idresolucionanexo, rl_origen, rl_procedencia, rl_requiererelev, rl_sindatos, rl_sinpersonal, rl_usualta, rl_usuexport,
										rl_valido, rl_vigencia)
						VALUES (:cantempleados, :contrato, :duplicado, :estableci, SYSDATE, :fechaexport, :fechaformbaja, :fechaformulario, SYSDATE, :fechavigencia,
										:fueradetermino, HYS.SEQ_HRL_RELEV_ID.NETVAL, :idcontratohist, :idresolucionanexo, :origen, :procedencia, :requiererelev, :sindatos, :sinpersonal, :usualta, :usuexport,
										:valido, :vigencia)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		$idSolicitudRGRL = ValorSql("SELECT MAX(rl_id) FROM hys.hrl_relevriesgolaboral", "", array(), 0);
	}


	// Loopeo por todas las preguntas..
	foreach ($_POST as $key => $value) {
		if (substr($key, 0, 9) == "pregunta_") {
			$idTipoFormaAnexo = ValorSql("SELECT ia_idtipoformanexo FROM hys.hia_itemanexo WHERE ia_id = :id", "", array(":id" => $_POST["H".$key]), 0);

			$fechaRegularizacion = NULL;
			if (($value == "N") and (isset($_POST["fecha_".$_POST["H".$key]])))
				$fechaRegularizacion = $_POST["fecha_".$_POST["H".$key]];

			$params = array(":idrelevriesgolaboral" => $idSolicitudRGRL, ":iditem" => $_POST["H".$key]);
			$sql =
				"SELECT il_id
					 FROM hys.hil_itemsriesgolaboral
					WHERE il_idrelevriesgolaboral = :idrelevriesgolaboral
						AND il_iditem = :iditem";
			$idItem = ValorSql($sql, -1, $params, 0);
			if ($idItem < 1) {		// Es un alta..
				$params = array(":cumplimiento" => $value,
												":fecharegularizacion" => $fechaRegularizacion,
												":fechaverificacion" => ???,
												":iditemanexo" => $_POST["H".$key],
												":idrelevriesgolaboral" => $idSolicitudRGRL,
												":observaciondenuncia" => ???,
												":usualta" => "W_".$_SESSION["usuario"]);
				$sql =
					"INSERT INTO hys.hil_itemsriesgolaboral
											(il_cumplimiento, il_fechaalta, il_fecharegularizacion, il_fechaverificacion, il_id, il_iditemanexo, il_idrelevriesgolaboral, il_observaciondenuncia, il_usualta)
							 VALUES (:cumplimiento, SYSDATE, TO_DATE(:fecharegularizacion, 'dd/mm/yyyy'), :fechaverificacion, ???, :iditemanexo, :idrelevriesgolaboral, :observaciondenuncia, :usualta)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
			else {		// Es una modificación..
				// Me fijo si se modificó algún valor..
				$params = array(":id" => $idItem);
				$sql =
					"SELECT il_cumplimiento, il_fecharegularizacion
						 FROM hys.hil_itemsriesgolaboral
						WHERE il_id = :id";
				$stmt = DBExecSql($conn, $sql, $params);
				$row = DBGetQuery($stmt);

				$params = array(":cumplimiento" => $value,
												":fecharegularizacion" => $fechaRegularizacion,
												":id" => $idItem,
												":usumodif" => "W_".$_SESSION["usuario"]);
				$sql =
					"UPDATE hys.hil_itemsriesgolaboral
							SET il_cumplimiento = :cumplimiento,
									il_fechamodif = SYSDATE,
									il_fecharegularizacion = :fecharegularizacion,
									il_usumodif = :usumodif
					  WHERE il_id = :id";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}


			// Guardo las planillas..
			if (isset($_POST["Hplanilla_pregunta_".$_POST["H".$key]])) {		// Si la pregunta tiene planilla..
				// Actualizo la cabecera de la planilla..
				$params = array(":idsolicitudfgrl" => $idSolicitudRGRL, ":idtipoanexo" => $idTipoFormaAnexo);
				$sql =
					"SELECT fr_id
						 FROM hys.hfr_formulariorelev
						WHERE fr_idrelevriesgolaboral = :idsolicitudfgrl
							AND fr_idtipoanexo = :idtipoanexo";
				$idItem = ValorSql($sql, -1, $params, 0);
				if ($idItem < 1) {		// Es un alta..
					$params = array(":duplicado" => ???,
													":fechaexport" => ???,
													":idrelevriesgolaboral" => $idSolicitudRGRL,
													":idtipoanexo" => $idTipoFormaAnexo,
													":usualta" => "W_".$_SESSION["usuario"],
													":usuexport" => ???,
													":valido" => ???);
					$sql =
						"INSERT INTO hys.hfr_formulariorelev
												 (fr_duplicado, fr_fechaalta, fr_fechaexport, fr_id, fr_idrelevriesgolaboral, fr_idtipoanexo, fr_usualta, fr_usuexport, fr_valido)
									VALUES (:duplicado, SYSDATE, :fechaexport, ???, :idrelevriesgolaboral, :idtipoanexo, :usualta, :usuexport, :valido)";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
					$idSolicitudPlanillaRGRL = ValorSql("SELECT MAX(fr_id) FROM hys.hfr_formulariorelev", "", array(), 0);
				}
				else {
					$params = array(":id" => $idItem,
													":usumodif" => "W_".$_SESSION["usuario"]);
					$sql =
						"UPDATE hys.hfr_formulariorelev
								SET fr_fechamodif = SYSDATE,
										fr_usumodif = :usumodif
						  WHERE fr_id = :id";
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

						$params = array(":iditemtiponexo" => $arr[1],
														":idformulariorelev" => $idSolicitudPlanillaRGRL);
						$sql =
							"SELECT if_id
								 FROM hys.hif_itemsformulariorelev
								WHERE if_idformulariorelev = :idformulariorelev
									AND if_iditemtipoanexo = :iditemtiponexo";
						$idItem = ValorSql($sql, -1, $params, 0);
						if ($idItem < 1) {		// Es un alta..
							$params = array(":cumplimiento" => $cumplimiento,
															":idformulariorelev" => $idSolicitudPlanillaRGRL,
															":iditemtipoanexo" => $arr[1],
															":usualta" => "W_".$_SESSION["usuario"]);
							$sql =
								"INSERT INTO hys.hif_itemsformulariorelev
														(if_cumplimiento, if_fechaalta, if_id, if_iditemtipoanexo, if_idformulariorelev, if_usualta)
										 VALUES (:cumplimiento, SYSDATE, ???, :iditemtipoanexo, :idformulariorelev, :usualta)";
							DBExecSql($conn, $sql, $params, OCI_DEFAULT);
						}
						else {
							$params = array(":cumplimiento" => $cumplimiento,
															":id" => $idItem,
															":usumodif" => "W_".$_SESSION["usuario"]);
							$sql =
								"UPDATE hys.hif_itemsformulariorelev
										SET if_cumplimiento = :cumplimiento,
												if_fechamodif = SYSDATE,
												if_usumodif = :usumodif
								  WHERE if_id = :id";
							DBExecSql($conn, $sql, $params, OCI_DEFAULT);
						}
					}
				}
			}
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
	}

	setTimeout('redirect()', 1500);
	window.parent.document.getElementById('guardadoOk').style.display = 'block';
	window.parent.document.getElementById('spanProcesando').style.display = 'none';
	window.parent.document.getElementById('foco').style.display = 'block';
	window.parent.document.getElementById('foco').focus();
	window.parent.document.getElementById('foco').style.display = 'none';
</script>