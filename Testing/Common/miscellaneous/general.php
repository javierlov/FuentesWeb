<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


function FirstCallPageCode() {
	// Devuelve una porción de código que reprocesa la página actual en el iframe "iframeProcesando"..

	$thisPage = $_SERVER["PHP_SELF"]."?";
	foreach ($_REQUEST as $key => $value)
		$thisPage.= $key."=".$value."&";
	$thisPage.= "firstcall=false";
?>
	<script type="text/javascript">
		try {
			window.parent.document.getElementById('originalGrid').style.display = 'none';
		}
		catch(err) {
			//
		}
		try {
			document.getElementById('divProcesando').style.display = 'block';
		}
		catch(err) {
			//
		}
		window.location.href = '<?= $thisPage?>';
	</script>
<?
	exit;
}

function GetEmail($users) {
	// Devuelve un array con la dirección de email de los usuarios pasados como parámetro..

	$emails = "";
 	foreach ($users as $value) {
		$params = array(":usuario" => $value);
		$sql = 			
			"SELECT se_mail
				 FROM use_usuarios
				WHERE se_usuario = UPPER(:usuario)";
		$emails.= ValorSql($sql, "", $params).",";
	}

	if (substr($emails, -1, 1) == ",")
		$emails = substr($emails, 0, strlen($emails) - 1);

	return explode(",", $emails);
}

function GetPCName() {
  $tmp = explode('.', gethostbyaddr($_SERVER['REMOTE_ADDR']));
  return $tmp[0];
}

function GetUserID() {
	$params = array(":usuario" => GetWindowsLoginName());
	$sql =
		"SELECT se_id
			 FROM art.use_usuarios
			WHERE se_usuario = UPPER(:usuario)";
	$user = ValorSQL($sql, "", $params);

	return $user;
}

function GetUserIDJefe($default = "", $user = "") {
	if ($user == "")
		$user = GetWindowsLoginName();

	$params = array(":usuario" => $user);
	$sql =
		"SELECT se_id
			 FROM art.use_usuarios
			WHERE se_usuario = (SELECT se_respondea
														FROM art.use_usuarios
													 WHERE se_usuario = UPPER(:usuario))";
	$sector = ValorSQL($sql, $default, $params);

	return $sector;
}

function GetUserIdSectorIntranet() {
	$params = array(":usuario" => GetWindowsLoginName());
	$sql =
		"SELECT se_idsector
			 FROM art.use_usuarios
			WHERE se_usuario = UPPER(:usuario)";
	$sector = ValorSQL($sql, "", $params);

	return $sector;
}

function GetUserName($user = "") {
	if ($user == "")
		$user = GetWindowsLoginName();

	$params = array(":usuario" => $user);
	$sql =
		"SELECT se_nombre
			 FROM art.use_usuarios
			WHERE se_usuario = UPPER(:usuario)";
	$user = ValorSQL($sql, "", $params);

	return $user;
}

function GetUserSector() {
	$params = array(":usuario" => GetWindowsLoginName());
	$sql =
		"SELECT se_sector
			 FROM art.use_usuarios
			WHERE se_usuario = UPPER(:usuario)";
	$sector = ValorSQL($sql, "", $params);

	return $sector;
}

function GetUserSectorNuevo() {
	$params = array(":usuario" => GetWindowsLoginName());
	$sql =
		"SELECT cse.se_descripcion
			 FROM use_usuarios useu, computos.cse_sector cse
			WHERE useu.se_idsector = cse.se_id
				AND useu.se_usuario = UPPER(:usuario)";
	$sector = ValorSQL($sql, "", $params);

	return $sector;
}

function GetWindowsLoginName($upper = false) {
  $cred = explode("\\", $_SERVER["REMOTE_USER"]);
  if (count($cred) == 1)
    array_unshift($cred, "No hay información disponible sobre el dominio donde Ud. está logueado.");
  list($domain, $user) = $cred;

	if ($upper)
  	return StringToUpper($user);
  else
  	return $user;
}

function IIF($condicion, $valor1, $valor2) {
  if ($condicion)
    return $valor1;
  else
    return $valor2;
}
?>