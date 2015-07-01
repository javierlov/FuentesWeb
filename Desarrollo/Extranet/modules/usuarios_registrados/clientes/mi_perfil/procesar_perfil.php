<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validar($esAltaAdministrador, $esIngresoPrimeraVezUsuarioRaso) {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["nombre"] == "") {
		echo "errores+= '- El campo Nombre y Apellido es obligatorio.<br />';";
		$errores = true;
	}

	if ($esAltaAdministrador) {
		if ($_POST["email"] == "") {
			echo "errores+= '- El campo e-Mail es obligatorio.<br />';";
			$errores = true;
		}
		else {
			$params = array(":email" => $_POST["email"]);
			$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
			if (valorSql($sql, "", $params) != "S") {
				echo "errores+= '- El e-Mail es inválido.<br />';";
				$errores = true;
			}

			$params = array(":email" => strtolower($_POST["email"]));
			$sql = "SELECT 1 FROM web.wue_usuariosextranet WHERE ue_idmodulo = 49 AND UPPER(ue_usuario) = UPPER(:email)";
			if (valorSql($sql, "", $params) == 1) {
				echo "errores+= '- El e-Mail ya existe en la base de datos.<br />';";
				$errores = true;
			}
		}
	}

	if ($esIngresoPrimeraVezUsuarioRaso) {
		if ($_POST["contrasena"] == "") {
			echo "errores+= '- Debe cambiar la Contraseña con la que ingresó.<br />';";
			$errores = true;
		}
	}

	if ($_POST["contrasena"] != "") {
		if (strlen($_POST["contrasena"]) < 8) {
			echo "errores+= '- La Contraseña debe tener al menos 8 caracteres.<br />';";
			$errores = true;
		}

		if ($_POST["contrasena"] != $_POST["repetirContrasena"]) {
			echo "errores+= '- La Contraseña no coincide con su repetición.<br />';";
			$errores = true;
		}
	}

	if ($esAltaAdministrador) {
		if (!isset($_POST["aceptoCondiciones"])) {
			echo "errores+= '- Si no acepta las Condiciones de Uso no se puede continuar.<br />';";
			$errores = true;
		}
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


// INICIO - Validación de la sesión..
if (isset($_SESSION["isCliente"]))
	validarSesion(isset($_SESSION["isCliente"]));
else
	validarSesion((isset($_SESSION["EsAltaAdministrador"])) or (isset($_SESSION["UsuarioIdIngresoPrimeraVez"])));

$esAltaAdministrador = ((isset($_SESSION["EsAltaAdministrador"])) and ($_SESSION["EsAltaAdministrador"]));
$esIngresoPrimeraVezUsuarioRaso = isset($_SESSION["UsuarioIdIngresoPrimeraVez"]);
	
if ((!$esAltaAdministrador) and (!$esIngresoPrimeraVezUsuarioRaso))
	validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 69));
// FIN - Validación de la sesión..


try {
	if (!validar($esAltaAdministrador, $esIngresoPrimeraVezUsuarioRaso))
		exit;

	$pass = "";
	if ($_POST["contrasena"] != "")
		$pass = md5($_POST["contrasena"]);

	if ($esAltaAdministrador) {
		$curs = null;
		$params = array(":cestado" => "A",
										":scargo" => $_POST["cargo"],
										":sclave" => $pass,
										":scuit" => $_SESSION["AltaAdministradorCuit"],
										":semail" => strtolower($_POST["email"]),
										":snombre" => $_POST["nombre"],
										":stelefonos" => $_POST["telefono"]);
		$sql = "BEGIN webart.set_alta_cliente_administrador(:data, :cestado, :scargo, :sclave, :scuit, :semail, :snombre, :stelefonos); END;";
		$stmt = DBExecSP($conn, $curs, $sql, $params);
	}
	elseif ($esIngresoPrimeraVezUsuarioRaso) {
		$params = array(":id" => $_SESSION["UsuarioIdIngresoPrimeraVez"]);
		$sql =
			"SELECT ue_usuario
				 FROM web.wue_usuariosextranet
				WHERE ue_id = :id";
		$usuario = valorSql($sql, "", $params);

		$curs = null;
		$params = array(":nid" => $_SESSION["UsuarioIdIngresoPrimeraVez"],
										":scargo" => $_POST["cargo"],
										":sclave" => $pass,
										":sestado" => "A",
										":sforzarclave" => "F",
										":snombre" => $_POST["nombre"],
										":stelefonos" => $_POST["telefono"],
										":susumodif" => $usuario);
		$sql ="BEGIN webart.set_perfil_cliente(:nid, :scargo, :sclave, :sestado, :sforzarclave, :snombre, :stelefonos, :susumodif); END;";
		$stmt = DBExecSP($conn, $curs, $sql, $params, false);
	}
	else {		// Es una modificación normal del perfil..
		$curs = null;
		$params = array(":nid" => $_SESSION["idUsuario"],
										":scargo" => $_POST["cargo"],
										":sclave" => $pass,
										":sestado" => NULL,
										":sforzarclave" => NULL,
										":snombre" => $_POST["nombre"],
										":stelefonos" => $_POST["telefono"],
										":susumodif" => $_SESSION["usuario"]);
		$sql ="BEGIN webart.set_perfil_cliente(:nid, :scargo, :sclave, :sestado, :sforzarclave, :snombre, :stelefonos, :susumodif); END;";
		$stmt = DBExecSP($conn, $curs, $sql, $params, false);
	}
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
<?
if (($esAltaAdministrador) or ($esIngresoPrimeraVezUsuarioRaso)) {
// Si se está dando de alta al administrador desde el CUIT o un usuario raso entra por primera vez lo logueo como si entrara por primera vez..
?>
	function redirect() {
		with (window.parent.document) {
			getElementById('sr').value = '<?= $_POST["email"]?>';
			getElementById('ps').value = '<?= $_POST["contrasena"]?>';
			getElementById('formAltaOk').submit();
		}
	}

	setTimeout('redirect()', 1500);
	window.parent.document.getElementById('guardadoOk').style.display = 'block';
<?
}
else {
?>
	function redirect() {
		window.parent.document.getElementById('guardadoOk').style.display = 'none';
	}

	setTimeout('redirect()', 3000);
	window.parent.document.getElementById('guardadoOk').style.display = 'block';
<?
}
?>
</script>