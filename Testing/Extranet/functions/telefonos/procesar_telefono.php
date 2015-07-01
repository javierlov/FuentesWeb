<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");


function tieneSoloNumeros($cadena) {
	for ($i=0; $i < strlen($cadena); $i++)
		if (!validarEntero($cadena[$i]))
		return false;

	return true;
}

function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["tipoTelefono"] == -1) {
		echo "errores+= '- El campo Tipo de Teléfono es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["area"] != "")
		if (!tieneSoloNumeros($_POST["area"])) {
			echo "errores+= '- El campo Área solo puede contener caracteres numéricos.<br />';";
			$errores = true;
		}

	if ($_POST["numero"] == "") {
		echo "errores+= '- El campo Número es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["numero"] != "")
		if (!tieneSoloNumeros($_POST["numero"])) {
			echo "errores+= '- El campo Número solo puede contener caracteres numéricos.<br />';";
			$errores = true;
		}

	if ($_POST["interno"] != "")
		if (!tieneSoloNumeros($_POST["interno"])) {
			echo "errores+= '- El campo Interno solo puede contener caracteres numéricos.<br />';";
			$errores = true;
		}


	if ($errores) {
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


validarSesion(isset($_SESSION[$_POST["s"]]));

try {
	if ($_POST["baja"] == "t") {
		$idTelefono = $_POST["id"];
		$params = array(":id" => $idTelefono);
		$sql =
			"UPDATE tmp_telefonos
					SET mp_estado = 'B'
				WHERE mp_id = :id";
		$stmt = DBExecSql($conn, $sql, $params);
	}
	else {
		if (!validar())
			exit;


		if (intval($_POST["id"]) <= 0) {		// Es un alta..
			$params = array(":area" => $_POST["area"],
											":idtipotelefono" => $_POST["tipoTelefono"],
											":interno" => $_POST["interno"],
											":numero" => $_POST["numero"],
											":observacion" => $_POST["observaciones"],
											":principal" => (isset($_POST["principal"])?"S":"N"),
											":tablapadreid" => $_POST["idTablaPadre"],
											":tablatel" => $_POST["tablaTel"],
											":tipo" => $_POST["tipo"],
											":usuarioweb" => $_SESSION["usuario"]);
			$sql =
				"INSERT INTO tmp.tmp_telefonos
										(mp_area, mp_estado, mp_id, mp_idtipotelefono, mp_interno, mp_numero, mp_observacion, mp_principal, mp_registrotelid, mp_tablapadreid, mp_tablatel, mp_tipo,
										 mp_usuarioid, mp_usuarioweb)
						 VALUES (:area, 'A', 1, :idtipotelefono, :interno, :numero, :observacion, :principal, 0, :tablapadreid, :tablatel, :tipo,
										 0, :usuarioweb)";
			DBExecSql($conn, $sql, $params);

			$params = array(":usuarioweb" => $_SESSION["usuario"]);
			$sql = "SELECT MAX(mp_id) FROM tmp.tmp_telefonos WHERE mp_usuarioweb = :usuarioweb";
			$idTelefono = ValorSql($sql, "", $params, 0);
		}
		else {		// Modificación..
			$idTelefono = $_POST["id"];
			$params = array(":area" => $_POST["area"],
											":id" => $idTelefono,
											":idtipotelefono" => $_POST["tipoTelefono"],
											":interno" => $_POST["interno"],
											":numero" => $_POST["numero"],
											":observacion" => $_POST["observaciones"],
											":principal" => (isset($_POST["principal"])?"S":"N"),
											":registrotelid" => $_POST["idRegistroTel"],
											":tablapadreid" => $_POST["idTablaPadre"],
											":usuarioweb" => $_SESSION["usuario"]);
			$sql =
				"UPDATE tmp.tmp_telefonos
						SET mp_area = :area,
								mp_estado = 'M',
								mp_idtipotelefono = :idtipotelefono,
								mp_interno = :interno,
								mp_numero = :numero,
								mp_observacion = :observacion,
								mp_principal = :principal,
								mp_registrotelid = :registrotelid,
								mp_tablapadreid = :tablapadreid,
								mp_usuarioweb = :usuarioweb
				  WHERE mp_id = :id";
			DBExecSql($conn, $sql, $params);
		}

		// Actualizo el campo principal de los demás teléfonos a 'N'..
		if (isset($_POST["principal"])) {
			$params = array(":id" => $idTelefono,
											":tablapadreid" => $_POST["idTablaPadre"],
											":tablatel" => $_POST["tablaTel"],
											":tipo" => $_POST["tipo"],
											":usuarioweb" => $_SESSION["usuario"]);
			$sql =
				"UPDATE tmp.tmp_telefonos
						SET mp_principal = 'N'
				  WHERE mp_usuarioweb = :usuarioweb
						AND mp_tablatel = :tablatel
						AND mp_tablapadreid = :tablapadreid
						AND mp_tipo = :tipo
						AND mp_id <> :id";
			DBExecSql($conn, $sql, $params);
		}
	}

	// Actualizo los teléfonos en la tabla padre..
	$dataTel = inicializarTelefonos(OCI_COMMIT_ON_SUCCESS, $_POST["campoClave"], $_POST["idTablaPadre"], $_POST["prefijo"], $_POST["tablaTel"], $_SESSION["usuario"], $_POST["tipo"]);
	if (intval($_POST["idTablaPadre"]) > 0)		// Si el registro padre ya existe copio los teléfonos a la tabla base..
		copiarTempATelefonos($dataTel, $idTelefono);
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function redirect() {
		window.parent.parent.document.getElementById('iframeTelefonos').contentWindow.location.reload(true);

		// Loopeo por si hay hasta 10 iframes con teléfonos en la misma página..
		for (i=2; i <= 10; i++) {
			obj = window.parent.parent.document.getElementById('iframeTelefonos' + i);
			if (obj != null)
				obj.contentWindow.location.reload(true);
			else
				break;
		}

		window.parent.parent.divWin.close();
	}

	setTimeout('redirect()', 1500);
<?
if ($_POST["baja"] == "t")
	echo "window.parent.document.getElementById('borradoOk').style.display = 'block';";
else
	echo "window.parent.document.getElementById('guardadoOk').style.display = 'block';";
?>
</script>