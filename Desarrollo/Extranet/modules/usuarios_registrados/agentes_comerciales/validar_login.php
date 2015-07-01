<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/net.php");

if (session_status() != PHP_SESSION_ACTIVE)
	session_start();


// *************************************
// *******  Inicio validaciones  *******
// *************************************
$controlBcra = 0;
$error = false;

if ((isset($_POST["captcha"])) and ($_POST["captcha"] != $_SESSION["captcha"])) {
	$_SESSION["fieldError"] = "captcha";
	$_SESSION["msgError"] = "Por favor, ingrese el captcha correcto. (1).";
	$error = true;
}
elseif ((!isset($_POST["sr"])) or ($_POST["sr"] == "")) {
	$_SESSION["fieldError"] = "sr";
	$_SESSION["msgError"] = "Por favor ingrese el Usuario (2).";
	$error = true;
}
else {
	$_POST["sr"] = substr($_POST["sr"], 0, 20);
	$_POST["ps"] = substr($_POST["ps"], 0, 60);

	$params = array(":usuario" => $_POST["sr"]);
	$sql =
		"SELECT uw_ctrbcra
			 FROM afi.auw_usuarioweb
			WHERE uw_fechabaja IS NULL
				AND uw_usuario = :usuario";
	$controlBcra = valorSql($sql, "", $params);
	if ($controlBcra == 1) {		// Si hay que hacerle controles especiales al usuario..
		$params = array(":usuario" => $_POST["sr"]);
		$sql =
			"SELECT uw_id
				 FROM afi.auw_usuarioweb
				WHERE uw_fechabaja IS NULL
					AND uw_usuario = :usuario";
		$id = valorSql($sql, "", $params);

		$curs = null;
		$params = array(":id" => $id, ":password" => $_POST["ps"], ":ip" => $_SERVER["REMOTE_ADDR"]);
		$sql = "BEGIN art.cotizacion.get_ctrlpassword(:id, :password, :ip, :data); END;";
		$stmt = DBExecSP($conn, $curs, $sql, $params);
		$rowCtrl = DBGetSP($curs);
		switch ($rowCtrl["NERROR"]) {
			case 1:
			case 2:
			case 4:
				$_SESSION["fieldError"] = "sr";
				$_SESSION["msgError"] = $rowCtrl["SERROR"]." (3).";
				$error = true;
				break;
		}
		$_SESSION["cambiarPassword"] = ($rowCtrl["NERROR"] == 3);

		if ((!isset($_POST["psn"])) and ($_SESSION["cambiarPassword"])) {
			$_SESSION["fieldError"] = "ps";
			$_SESSION["msgError"] = $rowCtrl["SERROR"]." (4).";
			$error = true;
		}
		elseif ((!isset($_POST["ps"])) or ($_POST["ps"] == "")) {
			$_SESSION["fieldError"] = "ps";
			$_SESSION["msgError"] = "Por favor ingrese la Contraseña (5).";
			$error = true;
		}
		elseif (isset($_POST["psn"])) {
			if (((isset($_POST["psn"])) and ($_POST["psn"] == "")) or ((isset($_POST["cnf"])) and ($_POST["cnf"] == ""))) {
				$_SESSION["fieldError"] = "psn";
				$_SESSION["msgError"] = "Por favor ingrese la Contraseña Nueva y su Confirmación (6).";
				$error = true;
			}
			elseif ($_POST["psn"] != $_POST["cnf"]) {
				$_SESSION["fieldError"] = "psn";
				$_SESSION["msgError"] = "La Confirmación no coincide con la Contraseña Nueva (7).";
				$error = true;
			}
			else {
				$curs = null;
				$params = array(":id" => $id, ":password" => $_POST["psn"]);
				$sql = "BEGIN art.cotizacion.set_cambiopassword(:id, :password, :data); END;";
				$stmt = DBExecSP($conn, $curs, $sql, $params);
				$rowCtrl = DBGetSP($curs);
				if ($rowCtrl["NERROR"] == 1) {
					$_SESSION["fieldError"] = "psn";
					$_SESSION["msgError"] = $rowCtrl["SERROR"]." (8).";
					$error = true;
				}
			}
		}
	}
	else {		// Validaciones a usuarios comunes..
		$params = array(":usuario" => $_POST["sr"]);
		$sql =
			"SELECT uw_forzarclave
				 FROM afi.auw_usuarioweb
				WHERE uw_fechabaja IS NULL
					AND uw_usuario = :usuario";
		$_SESSION["cambiarPassword"] = (valorSql($sql, "", $params) == 1);
		if ((!isset($_POST["psn"])) and ($_SESSION["cambiarPassword"])) {
			$_SESSION["fieldError"] = "ps";
			$_SESSION["msgError"] = "Por favor ingrese la Contraseña Actual, la Contraseña Nueva y su Confirmación (9).";
			$error = true;
		}
		elseif ((!isset($_POST["ps"])) or ($_POST["ps"] == "")) {
			$_SESSION["fieldError"] = "ps";
			$_SESSION["msgError"] = "Por favor ingrese la Contraseña (10).";
			$error = true;
		}
		else {
			$params = array(":usuario" => $_POST["sr"]);
			$sql =
				"SELECT uw_password
					 FROM afi.auw_usuarioweb
					WHERE uw_fechabaja IS NULL
						AND uw_usuario = :usuario";
			$pass = valorSql($sql, "", $params);
			if ($pass == "") {
				$_SESSION["fieldError"] = "sr";
				$_SESSION["msgError"] = "El Usuario ingresado es inexistente (11).";
				$error = true;
			}
			elseif ($pass != $_POST["ps"]) {
				$_SESSION["fieldError"] = "ps";
				$_SESSION["msgError"] = "La Contraseña ingresada es incorrecta (12).";
				$error = true;
			}
			elseif (isset($_POST["psn"])) {
				if (((isset($_POST["psn"])) and ($_POST["psn"] == "")) or ((isset($_POST["cnf"])) and ($_POST["cnf"] == ""))) {
					$_SESSION["fieldError"] = "psn";
					$_SESSION["msgError"] = "Por favor ingrese la Contraseña Nueva y su Confirmación (13).";
					$error = true;
				}
				elseif ($_POST["psn"] != $_POST["cnf"]) {
					$_SESSION["fieldError"] = "psn";
					$_SESSION["msgError"] = "La Confirmación no coincide con la Contraseña Nueva (14).";
					$error = true;
				}
				elseif ($_POST["ps"] == $_POST["psn"]) {
					$_SESSION["fieldError"] = "psn";
					$_SESSION["msgError"] = "La Contraseña Nueva no puede ser la misma que la Contraseña Actual (15).";
					$error = true;
				}
			}
		}
	}
}

if ((!$error) and ($controlBcra == 1)) {
	// Si ingresó correctamente y si hay que hacerle controles especiales al usuario, valido que se haya conectado desde una IP permitida..
	$params = array(":usuario" => $_POST["sr"]);
	$sql = 
		"SELECT uw_idcanal, uw_identidad, uw_nivel
			 FROM afi.auw_usuarioweb
			WHERE uw_usuario = :usuario";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	if ($row["UW_NIVEL"] != 99) {		// Si tiene nivel 99 no se chequea desde que IP se conecta..
		$params = array(":idcanal" => $row["UW_IDCANAL"], ":identidad" => $row["UW_IDENTIDAD"]);
		$sql =
			"SELECT ip_rangodesde, ip_rangohasta
				 FROM web.wip_ipspermitidas
				WHERE ip_idpagina = 25
					AND ip_idcanal = :idcanal
					AND ip_identidad = :identidad
					AND ip_fechabaja IS NULL";
		$stmt = DBExecSql($conn, $sql, $params);

		$enRango = true;
		while ($row = DBGetQuery($stmt)) {
			$enRango = ipEnRango($_SERVER["REMOTE_ADDR"], $row["IP_RANGODESDE"], $row["IP_RANGOHASTA"]);
			if ($enRango)
				break;
		}

		if (!$enRango) {
			$_SESSION["fieldError"] = "sr";
			$_SESSION["msgError"] = "Usted no tiene permiso para conectarse desde esa ubicación (16).";
			$error = true;
		}
	}
}
// **********************************
// *******  Fin validaciones  *******
// **********************************


if ($error) {
	$_SESSION["intentosLogin"]++;
}
else {
	unset($_SESSION["intentosLogin"]);

	$params = array(":usuario" => $_POST["sr"]);
	$sql = 
		"SELECT *
			 FROM afi.auw_usuarioweb
			WHERE uw_fechabaja IS NULL
				AND uw_usuario = :usuario";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$_SESSION["altaCotizaciones"] = ($row["UW_COTIZACION"] == 1);
	$_SESSION["autoCotizacion"] = $row["UW_AUTOCOTIZACION"];
	$_SESSION["canal"] = $row["UW_IDCANAL"];
	$_SESSION["certificadoCobertura"] = array();
	$_SESSION["comisiones"] = ($row["UW_LIQUIDACION"] == 1);
	$_SESSION["contrato"] = 0;
	$_SESSION["cuit"] = "";
	$_SESSION["cuitSuscripcion"] = $row["UW_CUITSUSCRIPCION"];
	$_SESSION["email"] = $row["UW_MAIL"];
	$_SESSION["emailAvisoArt"] = $row["UW_MAILAVISOART"];
	$_SESSION["empresa"] = "";
	$_SESSION["entidad"] = $row["UW_IDENTIDAD"];
	$_SESSION["entidadReal"] = $row["UW_IDENTIDAD"];
	$_SESSION["fieldError"] = "";
	$_SESSION["idEmpresa"] = 0;
	$_SESSION["idUsuario"] = $row["UW_ID"];
	$_SESSION["isAgenteComercial"] = true;
	$_SESSION["login"] = true;
	$_SESSION["msgError"] = "";
	$_SESSION["nivel"] = $row["UW_NIVEL"];
	$_SESSION["sucursal"] = $row["UW_IDSUCURSAL"];
	$_SESSION["usuario"] = $row["UW_USUARIO"];
	$_SESSION["vendedor"] = $row["UW_IDVENDEDOR"];

	logAccess($_SESSION["idUsuario"], 2, gethostbyaddr($_SERVER['REMOTE_ADDR']), $_SERVER["REMOTE_ADDR"], 25);

	// Actualizo la password si corresponde..
	if (isset($_POST["psn"])) {
		$params = array(":password" => $_POST["psn"], ":id" => $_SESSION["idUsuario"]);
		$sql =
			"UPDATE afi.auw_usuarioweb
					SET uw_password = :password,
							uw_forzarclave = 0
			  WHERE uw_id = :id
				 	AND uw_forzarclave = 1";
		DBExecSql($conn, $sql, $params);
	}

	// Registro el último login..
	$params = array(":id" => $_SESSION["idUsuario"]);
	$sql =
		"UPDATE auw_usuarioweb
				SET uw_ultimologin = SYSDATE
		  WHERE uw_id = :id";
	DBExecSql($conn, $sql, $params);

	echo "<script type='text/javascript'>window.location.href = '/bienvenida/aviso'</script>";
	exit;
}
?>