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

	if ((intval($_POST["id"]) > 0) and ($_POST["email"] != $_POST["email2"])) {
		echo "errores+= '- El e-Mail no coincide con su confirmación.<br />';";
		$errores = true;
	}

	$arrContratos = explode(",", $_POST["contratos"]);
	if (count($arrContratos) <= 1) {
		echo "errores+= '- Debe seleccionar el contrato al que pertenece el usuario.<br />';";
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


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 66));

try {
	if (!validar())
		exit;

	if (intval($_POST["id"]) == 0)		// Si es un alta siempre fuerzo la clave..
		$forzarClave = "S";
	else
		$forzarClave = ((isset($_POST["forzarClave"]))?"S":"N");

	$habilitarEstablecimientos = "N";
	if (isset($_POST["habilitarEstablecimientos"]))
		$habilitarEstablecimientos = "S";

	$params = array();
	$sql = "SELECT art.webart.get_cuit_encriptado(TO_CHAR(SYSDATE, 'SSMIHH24DDMMYYYY'), 'N') FROM DUAL";
	$pass = valorSql($sql, "", $params);

	$curs = null;
	$params = array(":cavisoobra" => ((isset($_POST["avisoObra"]))?"S":"N"),
									":ccartilla" => ((isset($_POST["cartilla"]))?"S":"N"),
									":ccertificadocobertura" => ((isset($_POST["certificadoCobertura"]))?"S":"N"),
									":cconsultasiniestros" => ((isset($_POST["consultaSiniestros"]))?"S":"N"),
									":cdenunciasiniestros" => ((isset($_POST["denunciaSiniestros"]))?"S":"N"),
									":cesadmin" => "N",
									":cesadmintotal" => "N",
									":cestado" => "A",
									":cestadosituacionpagos" => ((isset($_POST["estadoSituacionPagos"]))?"S":"N"),
									":cforzarclave" => $forzarClave,
									":chabilitarestablecimientos" => $habilitarEstablecimientos,
									":cinformesiniestrado" => "N",
									":clegales" => ((isset($_POST["legales"]))?"S":"N"),
									":cnominatrabajadores" => ((isset($_POST["nominaTrabajadores"]))?"S":"N"),
									":cprevencion" => ((isset($_POST["prevencion"]))?"S":"N"),
									":crgrl" => ((isset($_POST["rgrl"]))?"S":"N"),
									":crar" => ((isset($_POST["crar"]))?"S":"N"),
									":nid" => intval($_POST["id"]),
									":scargo" => $_POST["cargo"],
									":sclave" => $pass,
									":scontratos" => $_POST["contratos"],
									":semail" => strtolower($_POST["email"]),
									":sidsestablecimientos" => $_SESSION["establecimientosUsuario"],
									":snombre" => $_POST["nombre"],
									":stelefonos" => $_POST["telefono"],
									":susualta" => $_SESSION["usuario"]);
	$sql = "BEGIN webart.set_usuario_cliente(:data, :cavisoobra, :ccartilla, :ccertificadocobertura, :cconsultasiniestros, :cdenunciasiniestros, :cesadmin, :cesadmintotal, :cestado, :cestadosituacionpagos, :cforzarclave, :chabilitarestablecimientos, :cinformesiniestrado, :clegales, :cnominatrabajadores, :cprevencion, :crgrl, :crar, :nid, :scargo, :sclave, :scontratos, :semail, :sidsestablecimientos, :snombre, :stelefonos, :susualta); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs);

	if (intval($_POST["id"]) == 0) {		// Si es un alta..
		// Pongo la clave como provisoria..
		$params = array(":claveprovisoria" => $pass, ":id" => $row["ID"]);
		$sql =
			"UPDATE web.wue_usuariosextranet
					SET ue_clave = art.utiles.md5(:claveprovisoria),
							ue_claveprovisoria = art.utiles.md5(:claveprovisoria),
							ue_fechavencclaveprovisoria = SYSDATE + 3
			   WHERE ue_id = :id";
		DBExecSql($conn, $sql, $params);

		// Le envío un e-mail al usuario..
		$body =
			"Usted ha sido dado de alta en el sitio web de Provincia ART.\n".
			"Su usuario es: ".$_POST["email"]."\n".
			"Y su contraseña provisoria: ".$pass;
		$subject = "Usuario nuevo en la web de Provincia ART";
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
		window.parent.location.href = '/administracion-usuarios-2/<?= $row["ID"]?>';
	}

	setTimeout('redirect()', 2000);
	window.parent.document.getElementById('guardadoOk').style.display = 'block';
</script>