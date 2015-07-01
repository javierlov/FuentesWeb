<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


function getEmail($users) {
	// Devuelve un array con la dirección de email de los usuarios pasados como parámetro..

	$emails = "";
	foreach ($users as $value) {
		$params = array(":usuario" => $value);
		$sql =
			"SELECT se_mail
				 FROM use_usuarios
				WHERE se_usuario = UPPER(:usuario)";
		$emails.= valorSql($sql, "", $params).",";
	}

	if (substr($emails, -1, 1) == ",")
		$emails = substr($emails, 0, strlen($emails) - 1);

	return explode(",", $emails);
}

function getPCName() {
	$tmp = explode('.', gethostbyaddr($_SERVER['REMOTE_ADDR']));
	return $tmp[0];
}

function getUserId() {
	$params = array(":usuario" => getWindowsLoginName(true));
	$sql =
		"SELECT se_id
			 FROM art.use_usuarios
			WHERE se_usuario = :usuario";
	$user = valorSql($sql, "", $params);

	return $user;
}

function getUserIdJefe($default = "", $user = "") {
	if ($user == "")
		$user = getWindowsLoginName(true);

	$params = array(":usuario" => $user);
	$sql =
		"SELECT se_id
			 FROM art.use_usuarios
			WHERE se_usuario = (SELECT se_respondea
														FROM art.use_usuarios
													 WHERE se_usuario = :usuario)";
	$sector = valorSql($sql, $default, $params);

	return $sector;
}

function getUserIdSectorIntranet() {
	$params = array(":usuario" => getWindowsLoginName(true));
	$sql =
		"SELECT se_idsector
			 FROM art.use_usuarios
			WHERE se_usuario = :usuario";
	$sector = valorSql($sql, "", $params);

	return $sector;
}

function getUserName($user = "") {
	if ($user == "")
		$user = getWindowsLoginName(true);

	$params = array(":usuario" => $user);
	$sql =
		"SELECT se_nombre
			 FROM art.use_usuarios
			WHERE se_usuario = :usuario";
	$user = valorSql($sql, "", $params);

	return $user;
}

function getUserSector() {
	$params = array(":usuario" => getWindowsLoginName(true));
	$sql =
		"SELECT se_sector
			 FROM art.use_usuarios
			WHERE se_usuario = :usuario";
	$sector = valorSql($sql, "", $params);

	return $sector;
}

function getUserSectorNuevo() {
	$params = array(":usuario" => getWindowsLoginName(true));
	$sql =
		"SELECT cse.se_descripcion
			 FROM use_usuarios useu, computos.cse_sector cse
			WHERE useu.se_idsector = cse.se_id
				AND useu.se_usuario = :usuario";
	$sector = valorSql($sql, "", $params);

	return $sector;
}

function getWindowsLoginName($upper = false) {
	
	if (isset($_SESSION["FAKE_REMOTE_USER"])){
		return stringToUpper($_SESSION["FAKE_REMOTE_USER"]);
	}
	
	// Estas dos primeras lineas se agregan para que ande el motor de búsqueda..
	if (!isset($_SERVER["REMOTE_USER"]))
		$_SERVER["REMOTE_USER"] = "xxx";

	$cred = explode("\\", $_SERVER["REMOTE_USER"]);
	if (count($cred) == 1)
		array_unshift($cred, "No hay información disponible sobre el dominio donde Ud. está logueado.");
	list($domain, $user) = $cred;

	if ($upper)
		return stringToUpper($user);
	else
		return stringToLower($user);
}

function IIF($condicion, $valor1, $valor2) {
  if ($condicion)
    return $valor1;
  else
    return $valor2;
}

function Get_Lpa_Parametro($clave) {
	// Devuelve el valor de un parametro de la tabla lpa_parametro
	$params = array(":clave" => $clave);
	$sql ="select pa_valor from legales.lpa_parametro where upper(pa_clave) = upper( :clave )";
	$valor = valorSql($sql, "", $params);

	return trim($valor);
}

?>