<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


if (!isset($_POST["solapa"])) {
	validarParametro(false);
	exit;
}

try {
	$campoError = "";

	if ($_POST["solapa"] == "e") {
		if ($_POST["eRazonSocial"] == "") {
			$campoError = "eRazonSocial";
			throw new Exception("Por favor, complete el campo Razón Social.");
		}
		if ($_POST["eCuit"] == "") {
			$campoError = "eCuit";
			throw new Exception("Por favor, complete el campo C.U.I.T.");
		}
		if (!validarCuit(sacarGuiones($_POST["eCuit"]))) {
			$campoError = "eCuit";
			throw new Exception("La C.U.I.T. ingresada es inválida.");
		}
		if ($_POST["eNombreApellido"] == "") {
			$campoError = "eNombreApellido";
			throw new Exception("Por favor, complete el campo Nombre y Apellido.");
		}
		if ($_POST["eCargo"] == "") {
			$campoError = "eCargo";
			throw new Exception("Por favor, complete el campo Cargo.");
		}
		if ($_POST["eEmail"] == "") {
			$campoError = "eEmail";
			throw new Exception("Por favor, complete el campo e-Mail.");
		}

		$params = array(":email" => $_POST["eEmail"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			$campoError = "eEmail";
			throw new Exception("El e-Mail ingresado es inválido.");
		}

		if ($_POST["eTelefono"] == "") {
			$campoError = "eTelefono";
			throw new Exception("Por favor, complete el campo Teléfono.");
		}
		if ($_POST["eDireccion"] == "") {
			$campoError = "eDireccion";
			throw new Exception("Por favor, complete el campo Dirección.");
		}
		if ($_POST["eMotivo"] == -1) {
			$campoError = "eMotivo";
			throw new Exception("Por favor, seleccione un item del campo Motivo.");
		}
	}

	if ($_POST["solapa"] == "t") {
		if ($_POST["tNombreApellido"] == "") {
			$campoError = "tNombreApellido";
			throw new Exception("Por favor, complete el campo Nombre y Apellido.");
		}
		if ($_POST["tCuil"] == "") {
			$campoError = "tCuil";
			throw new Exception("Por favor, complete el campo C.U.I.L. o D.N.I.");
		}
		if ((!validarCuit(sacarGuiones($_POST["tCuil"]))) and (!validarEntero($_POST["tCuil"]))) {
			$campoError = "tCuil";
			throw new Exception("La C.U.I.L. o el D.N.I. ingresado es inválido.");
		}
		if ($_POST["tEmail"] == "") {
			$campoError = "tEmail";
			throw new Exception("Por favor, complete el campo e-Mail.");
		}

		$params = array(":email" => $_POST["tEmail"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			$campoError = "tEmail";
			throw new Exception("El e-Mail ingresado es inválido.");
		}

		if ($_POST["tTelefono"] == "") {
			$campoError = "tTelefono";
			throw new Exception("Por favor, complete el campo Teléfono.");
		}
		if ($_POST["tDireccion"] == "") {
			$campoError = "tDireccion";
			throw new Exception("Por favor, complete el campo Dirección.");
		}
		if ($_POST["tMotivo"] == -1) {
			$campoError = "tMotivo";
			throw new Exception("Por favor, seleccione un item del campo Motivo.");
		}
	}

	if ($_POST["solapa"] == "p") {
		if ($_POST["pRazonSocial"] == "") {
			$campoError = "pRazonSocial";
			throw new Exception("Por favor, complete el campo Razón Social.");
		}
		if ($_POST["pCuit"] == "") {
			$campoError = "pCuit";
			throw new Exception("Por favor, complete el campo C.U.I.T.");
		}
		if (!validarCuit(sacarGuiones($_POST["pCuit"]))) {
			$campoError = "pCuit";
			throw new Exception("La C.U.I.T. ingresada es inválida.");
		}
		if ($_POST["pNombreApellido"] == "") {
			$campoError = "pNombreApellido";
			throw new Exception("Por favor, complete el campo Nombre y Apellido.");
		}
		if ($_POST["pCargo"] == "") {
			$campoError = "pCargo";
			throw new Exception("Por favor, complete el campo Cargo.");
		}
		if ($_POST["pEmail"] == "") {
			$campoError = "pEmail";
			throw new Exception("Por favor, complete el campo e-Mail.");
		}

		$params = array(":email" => $_POST["pEmail"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			$campoError = "pEmail";
			throw new Exception("El e-Mail ingresado es inválido.");
		}

		if ($_POST["pTelefono"] == "") {
			$campoError = "pTelefono";
			throw new Exception("Por favor, complete el campo Teléfono.");
		}
		if ($_POST["pMotivo"] == -1) {
			$campoError = "pMotivo";
			throw new Exception("Por favor, seleccione un item del campo Motivo.");
		}
	}

	if ($_POST["solapa"] == "o") {
		if (($_POST["oRazonSocial"] == "") and ($_POST["oNombreApellido"] == "")) {
			$campoError = "oRazonSocial";
			throw new Exception("Por favor, complete la Razón Social o el Nombre y Apellido.");
		}
		if ($_POST["oEmail"] == "") {
			$campoError = "oEmail";
			throw new Exception("Por favor, complete el campo e-Mail.");
		}

		$params = array(":email" => $_POST["oEmail"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			$campoError = "oEmail";
			throw new Exception("El e-Mail ingresado es inválido.");
		}

		if ($_POST["oTelefono"] == "") {
			$campoError = "oTelefono";
			throw new Exception("Por favor, complete el campo Teléfono.");
		}
		if ($_POST["oMotivo"] == -1) {
			$campoError = "oMotivo";
			throw new Exception("Por favor, seleccione un item del campo Motivo.");
		}
	}

	if ($_POST["mensaje"] == "") {
		$campoError = "mensaje";
		throw new Exception("Por favor, complete el campo Mensaje.");
	}

	if ($_POST["captcha"] != $_SESSION["captcha"]) {
		$campoError = "captcha";
		throw new Exception("Por favor, ingrese el captcha correcto.");
	}
}
catch (Exception $e) {
?>
	<script type='text/javascript'>
		with (window.parent.document) {
			if (getElementById('<?= $campoError?>') != null) {
				getElementById('<?= $campoError?>').style.backgroundColor = '#f00';
				getElementById('<?= $campoError?>').style.color = '#fff';
				getElementById('<?= $campoError?>').focus();
			}
			alert(unescape('<?= rawurlencode($e->getMessage())?>'));
			setTimeout("window.parent.document.getElementById('<?= $campoError?>').style.backgroundColor = ''; window.parent.document.getElementById('<?= $campoError?>').style.color = '';", 700);
		}
	</script>
<?
	exit;
}


$body = "<html><body>";
$body.= "<p>Tipo de mensaje: <b>".$_POST["tipo"]."</b></p>";

if ($_POST["solapa"] == "e") {
	$body.= "<p>Razón Social: <b>".$_POST["eRazonSocial"]."</b></p>";
	$body.= "<p>C.U.I.T.: <b>".$_POST["eCuit"]."</b></p>";
	$body.= "<p>Nombre y Apellido: <b>".$_POST["eNombreApellido"]."</b></p>";
	$body.= "<p>Cargo: <b>".$_POST["eCargo"]."</b></p>";
	$body.= "<p>e-Mail: <b>".$_POST["eEmail"]."</b></p>";
	$body.= "<p>Teléfono: <b>".$_POST["eTelefono"]."</b></p>";
	$body.= "<p>Dirección: <b>".$_POST["eDireccion"]."</b></p>";
	$body.= "<p>Motivo: <b>".$_POST["eMotivo"]."</b></p>";
}
if ($_POST["solapa"] == "t") {
	$body.= "<p>Nombre y Apellido: <b>".$_POST["tNombreApellido"]."</b></p>";
	$body.= "<p>C.U.I.L.: <b>".$_POST["tCuil"]."</b></p>";
	$body.= "<p>e-Mail: <b>".$_POST["tEmail"]."</b></p>";
	$body.= "<p>Teléfono: <b>".$_POST["tTelefono"]."</b></p>";
	$body.= "<p>Dirección: <b>".$_POST["tDireccion"]."</b></p>";
	$body.= "<p>Motivo: <b>".$_POST["tMotivo"]."</b></p>";
}
if ($_POST["solapa"] == "p") {
	$body.= "<p>Razón Social: <b>".$_POST["pRazonSocial"]."</b></p>";
	$body.= "<p>C.U.I.T.: <b>".$_POST["pCuit"]."</b></p>";
	$body.= "<p>Nombre y Apellido: <b>".$_POST["pNombreApellido"]."</b></p>";
	$body.= "<p>Cargo: <b>".$_POST["pCargo"]."</b></p>";
	$body.= "<p>e-Mail: <b>".$_POST["pEmail"]."</b></p>";
	$body.= "<p>Teléfono: <b>".$_POST["pTelefono"]."</b></p>";
	$body.= "<p>Motivo: <b>".$_POST["pMotivo"]."</b></p>";
}
if ($_POST["solapa"] == "o") {
	$body.= "<p>Razón Social: <b>".$_POST["oRazonSocial"]."</b></p>";
	$body.= "<p>Nombre y Apellido: <b>".$_POST["oNombreApellido"]."</b></p>";
	$body.= "<p>e-Mail: <b>".$_POST["oEmail"]."</b></p>";
	$body.= "<p>Teléfono: <b>".$_POST["oTelefono"]."</b></p>";
	$body.= "<p>Motivo: <b>".$_POST["oMotivo"]."</b></p>";
}

$body.= "<p>Mensaje: <b>".$_POST["mensaje"]."</b></p>";
$body.= "</body></html>";

sendEmail($body, "Contacto Sitio Web", "Mensaje desde el sitio web de Provincia ART", array("info@provart.com.ar"), array(), array(), "H");
?>
<script src="/js/functions.js" type="text/javascript"></script>
<script type="text/javascript">
	function mostrarBoton() {
		with (window.parent.document) {
			getElementById('spanPuntos').style.display = 'none';
			getElementById('btnEnviar').style.display = 'inline';
			getElementById('formContacto').reset();
			getElementById('solapa').value = '<?= $_POST["solapa"]?>';
		}
		recargarCaptcha(window.parent.document.getElementById('imgCaptcha'));
	}

	with (window.parent.document) {
		getElementById('btnEnviar').style.display = 'none';
		getElementById('spanMsgOk').style.visibility = 'visible';
		getElementById('spanPuntos').style.display = 'inline';
	}
	setTimeout("mostrarBoton()", 3000);
</script>