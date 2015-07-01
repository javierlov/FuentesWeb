<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/excel/excel_reader2.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
session_start();


function actualizarRegistros($hayErrores, $seqTrans) {
	global $conn;

	if ($hayErrores) {
		$params = array(":transaccion" => $seqTrans);
		$sql = "DELETE FROM emi.iop_organismopublico WHERE op_transaccion = :transaccion";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
}

function formatNumber($valor) {
	return "0".trim(str_replace(array(",", "$"), array(".", ""), $valor));
}

function getColPos($index) {
	if ($index < 91)
		return chr($index);
	else
		switch ($index) {
			case 91:
				return "AA";
				break;
			case 92:
				return "AB";
				break;
			case 93:
				return "AC";
				break;
			case 94:
				return "AD";
				break;
			case 95:
				return "AE";
				break;
		}
}

function getCuil($documento, $sexo) {
	global $conn;

	$result = "";

	if (($documento != "") and (validarEntero($documento)) and (($sexo == "F") or ($sexo == "M"))) {
		$params = array(":dni" => trim(str_pad($documento, 8, "0", STR_PAD_LEFT)), ":sexo" => $sexo);
		$sql = "SELECT art.get_cuilvalido(:dni, :sexo, 1) FROM DUAL";
		$result = valorSql($sql, "", $params, 0);
	}

	return $result;
}

function getDiasTrabajados($dias, $periodo) {
	$maxDiasMes = cal_days_in_month(CAL_GREGORIAN, substr($periodo, 4, 2), substr($periodo, 0, 4));
	if ($dias > $maxDiasMes)
		return $maxDiasMes;
	else
		return $dias;
}

function guardarLog() {
	global $conn;

	$params = array(":id" => $_SESSION["idUsuario"]);
	$sql =
		"UPDATE emi.iwe_usuariowebemision
				SET we_ultimacargaarchivo = SYSDATE
		  WHERE we_id = :id";
	DBExecSql($conn, $sql, $params);
}

function insertarRegistro($seqTrans, $row, $cols) {
	global $conn;

	$params = array(":antiguedad" => $cols["W"],
									":bonus" => $cols["X"],
									":calle" => substr($cols["L"], 0, 100),
									":cantdiastrabajador" => (($cols["F"] == "")?cal_days_in_month(CAL_GREGORIAN, substr($cols["H"], 4, 2), substr($cols["H"], 0, 4)):getDiasTrabajados(intval($cols["F"]), $cols["H"])),
									":codpostal" => substr($cols["P"], 0, 10),
									":cuil" => nullIsEmpty(substr(trim($cols["C"]), 0, 11)),
									":departamento" => substr($cols["O"], 0, 10),
									":fechaingreso" => $cols["I"],
									":fechaegreso" => $cols["J"],
									":fechanacimiento" => $cols["T"],
									":horasextras" => $cols["AD"],
									":localidad" => substr($cols["Q"], 0, 100),
									":nombre" => (($cols["D"] == "")?"ALTA":substr($cols["D"], 0, 60)),
									":nrodocumento" => intval(substr($cols["A"], 0, 8)),
									":nroestablecimiento" => intval($cols["K"]),
									":nrofila" => $row,
									":numero" => substr($cols["M"], 0, 10),
									":otrosconceptos" => $cols["AA"],
									":periodo" => $cols["H"],
									":piso" => substr($cols["N"], 0, 10),
									":premios" => $cols["V"],
									":presentismo" => $cols["U"],
									":provincia" => substr($cols["R"], 0, 100),
									":refrigerio" => $cols["Z"],
									":sac" => $cols["AC"],
									":sexo" => $cols["E"],
									":sueldo" => nullIsEmpty($cols["AE"]),
									":sueldobruto" => $cols["G"],
									":tipodocumento" => intval($cols["B"]),
									":transaccion" => $seqTrans,
									":usuarioweb" => $_SESSION["idUsuario"],
									":vacaciones" => $cols["AB"],
									":viaticos" => $cols["Y"]);
	$sql =
		"INSERT INTO emi.iop_organismopublico
								 (op_transaccion, op_nrofila, op_nrodocumento, op_tipodocumento, op_cuil, op_nombre, op_sexo, op_cantdiastrabajador, op_sueldobruto, op_periodo, op_fechaingreso,
									op_fechaegreso, op_nroestablecimiento, op_calle, op_numero, op_piso, op_departamento, op_codpostal, op_localidad, op_provincia, op_fechanacimiento, op_fechaproceso,
									op_usuarioweb, op_fechaalta, op_estado, op_presentismo, op_premios, op_antiguedad, op_bonus, op_viaticos, op_refrigerio, op_otrosconceptos, op_vacaciones, op_sac,
									op_horasextras, op_sueldo)
				 VALUES (:transaccion, :nrofila, :nrodocumento, :tipodocumento, :cuil, :nombre, :sexo, :cantdiastrabajador, ROUND(:sueldobruto, 2), :periodo, :fechaingreso,
								 :fechaegreso, :nroestablecimiento, :calle, :numero, :piso, :departamento, :codpostal, :localidad, :provincia, :fechanacimiento, SYSDATE,
								 :usuarioweb, SYSDATE, -1, :presentismo, :premios, :antiguedad, :bonus, :viaticos, :refrigerio, :otrosconceptos, :vacaciones, :sac,
								 :horasextras, ROUND(:sueldo, 2))";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);
}

function insertarRegistroError($seqTrans, $row, $error) {
	global $conn;

	$params = array(":error" => $error, ":nrofila" => $row, ":transaccion" => $seqTrans);
	$sql = "INSERT INTO tmp.teop_errororganismopublico(te_transaccion, te_nrofila, te_error) VALUES (:transaccion, :nrofila, :error)";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);
}

function transaccionConErrores($seqTrans) {
	global $conn;

	$params = array(":transaccion" => $seqTrans);
	$sql =
		"SELECT 1
			 FROM tmp.teop_errororganismopublico
			WHERE te_transaccion = :transaccion";

	return existeSql($sql, $params, 0);
}

function validar() {
	try {
		if ($_FILES["archivo"]["name"] == "")
			throw new Exception("Debe elegir el Archivo a subir.");

		if (!validarExtension($_FILES["archivo"]["name"], array("xls")))
			throw new Exception("El Archivo a subir debe ser de extensión \".xls\".");
	}
	catch (Exception $e) {
		echo "<script type='text/javascript'>window.parent.document.getElementById('divPaso2').style.display = 'none'; window.parent.document.getElementById('divPaso1').style.display = 'inline'; alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
		exit;
	}
}

validarSesion(isset($_SESSION["isOrganismoPublico"]));

register_shutdown_function("shutdownFunction");
function shutDownFunction() {
	global $conn;

	$error = error_get_last();
	if ($error["type"] == 1) {
		DBRollback($conn);
		echo "<script type='text/javascript'>alert(unescape('".rawurlencode($error["message"])."'));</script>";
	}
}

validar();

try {
	setDateFormatOracle("DD/MM/YYYY");

	ini_set("memory_limit", "256M");
	set_time_limit(1800);

	guardarLog();

	error_reporting(E_ALL ^ E_NOTICE);
	$excel = new Spreadsheet_Excel_Reader($_FILES["archivo"]["tmp_name"]);

	$cuits = array();
	$hayRegistros = false;
	$primerPeriodo = $excel->val(2, "H");
	$seqTrans = getSecNextValOracle("TMP.SEQ_TOP_TRANSACCION");
	$totalRemuneracionImponible = 0;
	$totalSueldo = 0;

	for ($row=2; $row<=$excel->rowcount(); $row++) {		// Empiezo desde la 2, porque en la 1 viene la cabecera..
		// Meto los valores de las columnas en un array..
		$cols = array();
		for ($col=65; $col<=95; $col++)
			$cols[getColPos($col)] = $excel->val($row, getColPos($col));

		// Si todas las columnas estan vacías lo tomo como un EOF y salgo del loop principal..
		$existeValor = false;
		foreach ($cols as $key => $value)
			if (trim($value) != "")
				$existeValor = true;
		if (!$existeValor)
			break;

		// Formateo las fechas..
		if ($cols["I"] == "0")
			$cols["I"] = "ERROR";
		if ($cols["J"] == "0")
			$cols["J"] = "ERROR";
		if ($cols["T"] == "0")
			$cols["T"] = "ERROR";

		// Formateo los valores numéricos..
		$cols["G"] = formatNumber((($cols["G"] == "")?0:$cols["G"]));
		$cols["U"] = formatNumber((($cols["U"] == "")?0:$cols["U"]));
		$cols["V"] = formatNumber((($cols["V"] == "")?0:$cols["V"]));
		$cols["W"] = formatNumber((($cols["W"] == "")?0:$cols["W"]));
		$cols["X"] = formatNumber((($cols["X"] == "")?0:$cols["X"]));
		$cols["Y"] = formatNumber((($cols["Y"] == "")?0:$cols["Y"]));
		$cols["Z"] = formatNumber((($cols["Z"] == "")?0:$cols["Z"]));
		$cols["AA"] = formatNumber((($cols["AA"] == "")?0:$cols["AA"]));
		$cols["AB"] = formatNumber((($cols["AB"] == "")?0:$cols["AB"]));
		$cols["AC"] = formatNumber((($cols["AC"] == "")?0:$cols["AC"]));
		$cols["AD"] = formatNumber((($cols["AD"] == "")?0:$cols["AD"]));
		$cols["AE"] = formatNumber(trim($cols["AE"]));


		$cuilOk = true;
		$hayRegistros = true;
		$error = "";

		$cols["E"] = strtoupper($cols["E"]);

		// ***  Si la columna C tiene una C.U.I.L.  ***
		if ($cols["C"] != "") {
			$cols["C"] = sacarGuiones($cols["C"]);

			// ***  Si la columna C tiene una C.U.I.L. válida  ***
			if (validarCuit($cols["C"])) {
				$cols["A"] = substr($cols["C"], 2, 8);

				switch (intval(substr($cols["C"], 0, 2))) {
					case 20:
						$cols["E"] = "M";
						break;
					case 27:
						$cols["E"] = "F";
						break;
					default:
						if (($cols["E"] != "") and ($cols["E"] != "F") and ($cols["E"] != "M")) {
							$error = "Columna E: La columna Sexo debe ser F o M.";
							insertarRegistroError($seqTrans, $row, $error);
						}
				}
			}
			else
				$cuilOk = false;
		}
		else
			$cuilOk = false;

		if (!$cuilOk) {
			$error = "";

			// ***  Verifico que la columna A tenga algún dato  ***
			if ($cols["A"] == "") {
				$error = "Columna A: Nº Documento vacío.";
				insertarRegistroError($seqTrans, $row, $error);
			}

			// ***  Verifico que la columna A tenga un entero de 7 dígitos  ***
			if ((intval($cols["A"]) <= 999999) or (intval($cols["A"]) > 99999999)) {
				$error = "Columna A: Nº Documento inválido.";
				insertarRegistroError($seqTrans, $row, $error);
			}

			// ***  Verifico que la columna E sea 'F' o 'M'  ***
			if (($cols["E"] != "F") and ($cols["E"] != "M")) {
				$error = "Columna E: La columna Sexo debe ser F o M.";
				insertarRegistroError($seqTrans, $row, $error);
			}

			if ($error == "")
				$cols["C"] = getCuil($cols["A"], $cols["E"]);
		}

		// ***  Verifico que el CUIL no estuviera en algún registro anterior  ***
		if (in_array($cols["C"], $cuits)) {
			$error = "Columna C: La C.U.I.L. está duplicada con la C.U.I.L. de algún registro anterior.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna B tiene un valor, sea un entero entre 1 y 5  ***
		if ($cols["B"] != "")
			if ((intval($cols["B"]) < 0) or (intval($cols["B"]) > 5)) {
				$error = "Columna B: Tipo de Documento inválido.";
				insertarRegistroError($seqTrans, $row, $error);
			}

		// ***  Verifico que la columna F tenga un valor entero entre 1 y 31, en caso de tener algún valor  ***
		if ($cols["F"] != "")
			if ((intval($cols["F"]) < 1) or (intval($cols["F"]) > 99999)) {
				$error = "Columna F: La Cantidad de Días Trabajados debe ser un número entre 1 y 31.";
				insertarRegistroError($seqTrans, $row, $error);
			}

		// ***  Verifico que si la columna G tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if (!validarNumero($cols["G"])) {
			$error = "Columna G: Valor inválido.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($cols["G"] < 0) {
			$error = "Columna G: El valor debe ser un número mayor o igual a cero.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que la columna H tenga un valor entero de 6 dígitos, donde los primeros 4 sean mayor a 2005 y los últimos 2 menor a 13  ***
		if (!validarEntero($cols["H"])) {
			$error = "Columna H: Período inválido, el formato correcto es YYYYMM.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif (strlen($cols["H"]) != 6) {
			$error = "Columna H: Período inválido, el formato debe ser YYYYMM.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif (intval(substr($cols["H"], 0, 4)) < 2006) {
			$error = "Columna H: Período inválido, el año debe ser 2006 o posterior.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif (intval(substr($cols["H"], 4, 2)) > 12) {
			$error = "Columna H: Período inválido, el formato debe ser YYYYMM.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif (dateDiff(incMonths(date("01/m/Y"), -1), "01/".substr($cols["H"], 4, 2)."/".substr($cols["H"], 0, 4)) > 0) {
			$error = "Columna H: Período inválido, el período no puede ser igual al actual ni al futuro.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($primerPeriodo != $cols["H"]) {
			$error = "Columna H: Período inválido, el período debe ser igual al período de la primer fila.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna I tiene un valor, sea una fecha válida  ***
		if ($cols["I"] != "")
			if (!isFechaValida($cols["I"])) {
				$error = "Columna I: Fecha de Ingreso inválida, el formato correcto es dd/mm/yyyy.";
				insertarRegistroError($seqTrans, $row, $error);
			}

		// ***  Verifico que si la columna J tiene un valor, sea una fecha válida  ***
		if ($cols["J"] != "")
			if (!isFechaValida($cols["J"])) {
				$error = "Columna J: Fecha de Egreso inválida, el formato correcto es dd/mm/yyyy.";
				insertarRegistroError($seqTrans, $row, $error);
			}

		// ***  Verifico que si la columna K tiene un valor, sea un entero  ***
		if ($cols["K"] != "")
			if (!validarEntero($cols["K"])) {
				$error = "Columna K: Nº de Establecimiento inválido.";
				insertarRegistroError($seqTrans, $row, $error);
			}

		// ***  Verifico que si la columna T tiene un valor, sea una fecha válida  ***
		if ($cols["T"] != "")
			if (!isFechaValida($cols["T"])) {
				$error = "Columna T: Fecha de Nacimiento inválida, el formato correcto es dd/mm/yyyy.";
				insertarRegistroError($seqTrans, $row, $error);
			}
			else {
				// ***  Verifico que el trabajador tenga una edad razonable..  ***
				$edad = dateDiff($cols["T"], date("d/m/Y"), "A");

				if (($edad <= 16) or ($edad >= 90)) {
					$error = "Columna T: La edad del trabajador tiene que ser de entre 16 y 90 años.";
					insertarRegistroError($seqTrans, $row, $error);
				}
			}

		// ***  Verifico que si la columna U tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if (!validarNumero($cols["U"])) {
			$error = "Columna U: Valor inválido.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($cols["U"] < 0) {
			$error = "Columna U: El valor debe ser un número mayor o igual a cero.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna V tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if (!validarNumero($cols["V"])) {
			$error = "Columna V: Valor inválido.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($cols["V"] < 0) {
			$error = "Columna V: El valor debe ser un número mayor o igual a cero.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna W tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if (!validarNumero($cols["W"])) {
			$error = "Columna W: Valor inválido.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($cols["W"] < 0) {
			$error = "Columna W: El valor debe ser un número mayor o igual a cero.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna X tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if (!validarNumero($cols["X"])) {
			$error = "Columna X: Valor inválido.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($cols["X"] < 0) {
			$error = "Columna X: El valor debe ser un número mayor o igual a cero.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna Y tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if (!validarNumero($cols["Y"])) {
			$error = "Columna Y: Valor inválido.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($cols["Y"] < 0) {
			$error = "Columna Y: El valor debe ser un número mayor o igual a cero.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna Z tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if (!validarNumero($cols["Z"])) {
			$error = "Columna Z: Valor inválido.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($cols["Z"] < 0) {
			$error = "Columna Z: El valor debe ser un número mayor o igual a cero.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna AA tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if (!validarNumero($cols["AA"])) {
			$error = "Columna AA: Valor inválido.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($cols["AA"] < 0) {
			$error = "Columna AA: El valor debe ser un número mayor o igual a cero.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna AB tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if (!validarNumero($cols["AB"])) {
			$error = "Columna AB: Valor inválido.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($cols["AB"] < 0) {
			$error = "Columna AB: El valor debe ser un número mayor o igual a cero.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna AC tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if (!validarNumero($cols["AC"])) {
			$error = "Columna AC: Valor inválido.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($cols["AC"] < 0) {
			$error = "Columna AC: El valor debe ser un número mayor o igual a cero.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna AD tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if (!validarNumero($cols["AD"])) {
			$error = "Columna AD: Valor inválido.";
			insertarRegistroError($seqTrans, $row, $error);
		}
		elseif ($cols["AD"] < 0) {
			$error = "Columna AD: El valor debe ser un número mayor o igual a cero.";
			insertarRegistroError($seqTrans, $row, $error);
		}

		// ***  Verifico que si la columna AE tiene un valor, sea un valor numérico mayor o igual a cero  ***
		if ($cols["H"] >= "201210") {
			if ($cols["AE"] == "") {
				$error = "Columna AE: El valor debe ser un número mayor o igual a cero.";
				insertarRegistroError($seqTrans, $row, $error);
			}
			elseif (!validarNumero($cols["AE"])) {
				$error = "Columna AE: Valor inválido.";
				insertarRegistroError($seqTrans, $row, $error);
			}
			elseif ($cols["AE"] < 0) {
				$error = "Columna AE: El valor debe ser un número mayor o igual a cero.";
				insertarRegistroError($seqTrans, $row, $error);
			}
		}
		elseif ($cols["AE"] != "") {
			if (!validarNumero($cols["AE"])) {
				$error = "Columna AE: Valor inválido.";
				insertarRegistroError($seqTrans, $row, $error);
			}
			elseif ($cols["AE"] < 0) {
				$error = "Columna AE: El valor debe ser un número mayor o igual a cero.";
				insertarRegistroError($seqTrans, $row, $error);
			}
		}


		// Inserto la C.U.I.L. en el arreglo de cuiles..
		if ($cols["C"] != "")
			$cuits[] = $cols["C"];

		$totalRemuneracionImponible+= floatval($cols["G"]);
		$totalSueldo+= floatval($cols["AE"]);

		if ($error == "")
			insertarRegistro($seqTrans, $row, $cols);
	}

	if (!$hayRegistros)
		insertarRegistroError($seqTrans, 0, "No hay registros para procesar.");

	$hayErrores = transaccionConErrores($seqTrans);
	actualizarRegistros($hayErrores, $seqTrans);
	DBCommit($conn);

	$ley = 0;
	$params = array(":contrato" => $_SESSION["contrato"], ":periodo" => $primerPeriodo);
	$sql = "SELECT emi.utiles.get_reglaley(:contrato, :periodo) FROM DUAL";
	$ley = valorSql($sql, "", $params, 0);
	$hayErroresSueldo = false;

	if (($ley == 1) and ($totalSueldo <> 0)) {
		$hayErroresSueldo = true;
		$hayErrores = true;
	}
	if (($ley == 2) and ($totalRemuneracionImponible <> 0)) {
		$hayErroresSueldo = true;
		$hayErrores = true;
	}

	if ($hayErrores) {
		if ($hayErroresSueldo) {
			if ($ley == 1)
				echo "<script type='text/javascript'>window.parent.location.href = '/carga-nomina-personal/error/".$seqTrans."/1';</script>";
			elseif ($ley == 2)
				echo "<script type='text/javascript'>window.parent.location.href = '/carga-nomina-personal/error/".$seqTrans."/2';</script>";
		}
		else
			echo "<script type='text/javascript'>window.parent.location.href = '/carga-nomina-personal/error/".$seqTrans."';</script>";
	}
	else {
?>
		<script type="text/javascript">
<?
		$params = array(":transaccion" => $seqTrans);
		$sql =
			"SELECT op_periodo
				 FROM emi.iop_organismopublico
				WHERE op_transaccion = :transaccion
					AND op_estado = -1";
		$periodo = valorSql($sql, "", $params);

		$params = array(":contrato" => $_SESSION["contrato"], ":periodo" => $periodo);
		$sql = "SELECT emi.notas.get_existenominacargada(:contrato, :periodo) FROM DUAL";
		$fechaDdjj = valorSql($sql, "", $params);
		if ($fechaDdjj != "") {
?>
			window.parent.document.getElementById('imgProcesando').style.display = 'none';

			if (confirm('Usted cargó una D.D.J.J. para el período <?= substr($periodo, 4, 2)."/".substr($periodo, 0, 4)?> el <?= $fechaDdjj?>.\n ¿ Desea eliminarla ?'))
				window.parent.location.href = '/carga-nomina-personal/paso-2/<?= $seqTrans?>/<?= $periodo?>';		// amw=ActualizarMostrarWeb, pp=Período procesado..
			else
				window.parent.location.href = '/carga-nomina-personal';
<?
		}
		else {
?>
			window.parent.location.href = '/carga-nomina-personal/paso-2/<?= $seqTrans?>';
<?
		}
?>
		</script>
<?
	}
}
catch (Exception $e) {
	DBRollback($conn);
// Comento el error de JS porque lo muestro por HTML..
//	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	echo "<script type='text/javascript'>window.parent.location.href = '/carga-nomina-personal/error-fatal/".$seqTrans."';</script>";
	exit;
}
?>