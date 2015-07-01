<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["email"] == "") {
		echo "errores+= '- El campo e-Mail es obligatorio.<br />';";
		$errores = true;
	}
	elseif (intval($_POST["id"]) == 0) {
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

	if ($_POST["email"] != $_POST["email2"]) {
		echo "errores+= '- El e-Mail no coincide con su confirmación.<br />';";
		$errores = true;
	}

	if ((intval($_POST["id"]) == 0) and ($_POST["contrasena"] == "")) {
		echo "errores+= '- El campo Contraseña es obligatorio.<br />';";
		$errores = true;
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

	if ($_POST["estado"] == -1) {
		echo "errores+= '- El campo Estado es obligatorio.<br />';";
		$errores = true;
	}

	if (!isset($_POST["administradorArt"])) {
		$arrContratos = explode(",", $_POST["contratos"]);
		if (count($arrContratos) <= 1) {
			echo "errores+= '- Debe seleccionar el contrato al que pertenece el usuario.<br />';";
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


validarSesion(isset($_SESSION["isCliente"]));
validarSesion($_SESSION["isAdminTotal"]);

try {
	$_POST["email"] = trim($_POST["email"]);
	$_POST["email2"] = trim($_POST["email2"]);

	if (!validar())
		exit;

	$forzarClave = "N";
	$pass = "";
	if ($_POST["contrasena"] != "") {
		$forzarClave = "S";
		$pass = md5($_POST["contrasena"]);
	}

	$avisoObra = ((isset($_POST["avisoObra"]))?"S":"N");
	$cartilla = ((isset($_POST["cartilla"]))?"S":"N");
	$certificadocobertura = ((isset($_POST["certificadoCobertura"]))?"S":"N");
	$consultasiniestros = ((isset($_POST["consultaSiniestros"]))?"S":"N");
	$denunciasiniestros = ((isset($_POST["denunciaSiniestros"]))?"S":"N");
	$estadosituacionpagos = ((isset($_POST["estadoSituacionPagos"]))?"S":"N");
	$habilitarEstablecimientos = "N";
	$legales = ((isset($_POST["legales"]))?"S":"N");
	$nominatrabajadores = ((isset($_POST["nominaTrabajadores"]))?"S":"N");
	$prevencion = ((isset($_POST["prevencion"]))?"S":"N");
	$rgrl = ((isset($_POST["rgrl"]))?"S":"N");
	$crar = ((isset($_POST["crar"]))?"S":"N");
	if ((isset($_POST["administrador"])) or (isset($_POST["administradorArt"]))) {		// Si se tildó que es administrador o administrador total, le doy permiso a todo..
		$avisoObra = "S";
		$cartilla = "S";
		$certificadocobertura = "S";
		$consultasiniestros = "S";
		$denunciasiniestros = "S";
		$estadosituacionpagos = "S";
		$habilitarEstablecimientos = "S";
		$legales = "S";
		$nominatrabajadores = "S";
		$prevencion = "S";
		$rgrl = "S";
	}

	$curs = null;
	$params = array(":cavisoobra" => $avisoObra,
									":ccartilla" => $cartilla,
									":ccertificadocobertura" => $certificadocobertura,
									":cconsultasiniestros" => $consultasiniestros,
									":cdenunciasiniestros" => $denunciasiniestros,
									":cesadmin" => ((isset($_POST["administrador"]))?"S":"N"),
									":cesadmintotal" => ((isset($_POST["administradorArt"]))?"S":"N"),
									":cestado" => $_POST["estado"],
									":cestadosituacionpagos" => $estadosituacionpagos,
									":cforzarclave" => $forzarClave,
									":chabilitarestablecimientos" => $habilitarEstablecimientos,
									":cinformesiniestrado" => "N",
									":clegales" => $legales,
									":cnominatrabajadores" => $nominatrabajadores,
									":cprevencion" => $prevencion,
									":crgrl" => $rgrl,
									":crar" => $crar,
									":nid" => intval($_POST["id"]),
									":scargo" => $_POST["cargo"],
									":sclave" => $pass,
									":scontratos" => $_POST["contratos"],
									":semail" => strtolower($_POST["email"]),
									":sidsestablecimientos" => ",",
									":snombre" => $_POST["nombre"],
									":stelefonos" => $_POST["telefono"],
									":susualta" => $_SESSION["usuario"]);
	$sql = "BEGIN webart.set_usuario_cliente(:data, :cavisoobra, :ccartilla, :ccertificadocobertura, :cconsultasiniestros, :cdenunciasiniestros, :cesadmin, :cesadmintotal, :cestado, :cestadosituacionpagos, :cforzarclave, :chabilitarestablecimientos, :cinformesiniestrado, :clegales, :cnominatrabajadores, :cprevencion, :crgrl, :crar, :nid, :scargo, :sclave, :scontratos, :semail, :sidsestablecimientos, :snombre, :stelefonos, :susualta); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs);

	// Si se está activando al usuario, envío el e-mail..
	if ($_POST["enviarDatos"] == "S") {
		if ($_POST["contrasena"] != "")
			$clave = $_POST["contrasena"];
		else {
			$params = array(":id" => intval($_POST["id"]));
			$sql =
				"SELECT ue_clave
					 FROM web.wue_usuariosextranet
					WHERE ue_id = :id";
			$clave = valorSql($sql, "", $params);
			if ($clave == "") {
				$params = array(":idusuarioextranet" => intval($_POST["id"]));
				$sql =
					"SELECT art.webart.get_cuit_encriptado(em_cuit)
						 FROM aem_empresa, aco_contrato, web.wcu_contratosxusuarios, web.wuc_usuariosclientes
						WHERE em_id = co_idempresa
							AND co_contrato = cu_contrato
							AND cu_idusuario = uc_id
							AND uc_idusuarioextranet = :idusuarioextranet";
				$clave = valorSql($sql, "", $params);

				$params = array(":clave" => $clave, ":id" => intval($_POST["id"]));
				$sql =
					"UPDATE web.wue_usuariosextranet
							SET ue_clave = art.utiles.md5(:clave)
						WHERE ue_id = :id";
				DBExecSql($conn, $sql, $params);
			}
		}

		// Guardo al usuario que habilita al cliente por primera vez..
		$params = array(":idusuarioextranet" => intval($_POST["id"]));
		$sql =
			"SELECT 1
				 FROM web.wuc_usuariosclientes
				WHERE uc_fechahabilitacion IS NULL
					AND uc_idusuarioextranet = :idusuarioextranet";
		if (existeSql($sql, $params)) {
			$params = array(":idcliente" => intval($_POST["id"]), ":idusuariocomercial" => $_SESSION["idUsuario"]);
			$sql =
				"UPDATE web.wuc_usuariosclientes
						SET uc_fechahabilitacion = SYSDATE,
								uc_idusuariohabilitacion = (SELECT uc_id FROM web.wuc_usuariosclientes WHERE uc_idusuarioextranet = :idusuariocomercial)
					WHERE uc_idusuarioextranet = :idcliente";
			DBExecSql($conn, $sql, $params);
		}

		$body =
			"<html><body>".
			"Usted ha sido dado de alta en el sitio web de Provincia ART.<br />".
			"Su usuario es: ".$_POST["email"]."<br />".
			"Y su contraseña: ".$clave.
			"</body></html>";
		$subject = "Alta en el Sitio Web de Provincia ART";
		sendEmail($body, "Provincia ART", $subject, array($_POST["email"]), array(), array(), 'H');
	}
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function redirect() {
		window.parent.location.href = '/administracion-usuarios/<?= $row["ID"]?>';
	}

	setTimeout('redirect()', 2000);
	window.parent.document.getElementById('guardadoOk').style.display = 'block';
</script>