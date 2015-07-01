<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");

if (!isset($_POST["solapa"])) {
	validarParametro(false);
	exit;
}

if ($_POST["solapa"] == "e") {
	if ($_POST["eRazonSocial"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Razón Social.'); parent.document.getElementById('eRazonSocial').focus();</script>";
		exit;
	}
	if ($_POST["eCuit"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo C.U.I.T.'); parent.document.getElementById('eCuit').focus();</script>";
		exit;
	}
	if (!validarCuit(sacarGuiones($_POST["eCuit"]))) {
		echo "<script type='text/javascript'>alert('La C.U.I.T. ingresada es inválida.'); parent.document.getElementById('eCuit').select(); parent.document.getElementById('eCuit').focus();</script>";
		exit;
	}
	if ($_POST["eNombreApellido"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Nombre y Apellido.'); parent.document.getElementById('eNombreApellido').focus();</script>";
		exit;
	}
	if ($_POST["eCargo"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Cargo.'); parent.document.getElementById('eCargo').focus();</script>";
		exit;
	}
	if ($_POST["eEmail"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo e-Mail.'); parent.document.getElementById('eEmail').focus();</script>";
		exit;
	}

	$params = array(":email" => $_POST["eEmail"]);
	$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
	if (ValorSql($sql, "", $params) != "S") {
		echo "<script type='text/javascript'>alert('El e-Mail ingresado es inválido.'); parent.document.getElementById('eEmail').select(); parent.document.getElementById('eEmail').focus();</script>";
		exit;
	}

	if ($_POST["eTelefono"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Teléfono.'); parent.document.getElementById('eTelefono').focus();</script>";
		exit;
	}
	if ($_POST["eMotivo"] == -1) {
		echo "<script type='text/javascript'>alert('Por favor, seleccione un item del campo Motivo.'); parent.document.getElementById('eMotivo').focus();</script>";
		exit;
	}
}

if ($_POST["solapa"] == "t") {
	if ($_POST["tNombreApellido"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Nombre y Apellido.'); parent.document.getElementById('tNombreApellido').focus();</script>";
		exit;
	}
	if ($_POST["tCuil"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo C.U.I.L. o D.N.I.'); parent.document.getElementById('tCuil').focus();</script>";
		exit;
	}
	if ((!validarCuit(sacarGuiones($_POST["tCuil"]))) and (!validarEntero($_POST["tCuil"]))) {
		echo "<script type='text/javascript'>alert('La C.U.I.L. o el D.N.I. ingresado es inválido.'); parent.document.getElementById('tCuil').select(); parent.document.getElementById('tCuil').focus();</script>";
		exit;
	}
	if ($_POST["tEmail"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo e-Mail.'); parent.document.getElementById('tEmail').focus();</script>";
		exit;
	}

	$params = array(":email" => $_POST["tEmail"]);
	$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
	if (ValorSql($sql, "", $params) != "S") {
		echo "<script type='text/javascript'>alert('El e-Mail ingresado es inválido.'); parent.document.getElementById('tEmail').select(); parent.document.getElementById('tEmail').focus();</script>";
		exit;
	}

	if ($_POST["tTelefono"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Teléfono.'); parent.document.getElementById('tTelefono').focus();</script>";
		exit;
	}
	if ($_POST["tMotivo"] == -1) {
		echo "<script type='text/javascript'>alert('Por favor, seleccione un item del campo Motivo.'); parent.document.getElementById('tMotivo').focus();</script>";
		exit;
	}
}

if ($_POST["solapa"] == "p") {
	if ($_POST["pRazonSocial"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Razón Social.'); parent.document.getElementById('pRazonSocial').focus();</script>";
		exit;
	}
	if ($_POST["pCuit"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo C.U.I.T.'); parent.document.getElementById('pCuit').focus();</script>";
		exit;
	}
	if (!validarCuit(sacarGuiones($_POST["pCuit"]))) {
		echo "<script type='text/javascript'>alert('La C.U.I.T. ingresada es inválida.'); parent.document.getElementById('pCuit').select(); parent.document.getElementById('pCuit').focus();</script>";
		exit;
	}
	if ($_POST["pNombreApellido"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Nombre y Apellido.'); parent.document.getElementById('pNombreApellido').focus();</script>";
		exit;
	}
	if ($_POST["pCargo"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Cargo.'); parent.document.getElementById('pCargo').focus();</script>";
		exit;
	}
	if ($_POST["pEmail"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo e-Mail.'); parent.document.getElementById('pEmail').focus();</script>";
		exit;
	}

	$params = array(":email" => $_POST["pEmail"]);
	$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
	if (ValorSql($sql, "", $params) != "S") {
		echo "<script type='text/javascript'>alert('El e-Mail ingresado es inválido.'); parent.document.getElementById('pEmail').select(); parent.document.getElementById('pEmail').focus();</script>";
		exit;
	}

	if ($_POST["pTelefono"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Teléfono.'); parent.document.getElementById('pTelefono').focus();</script>";
		exit;
	}
	if ($_POST["pMotivo"] == -1) {
		echo "<script type='text/javascript'>alert('Por favor, seleccione un item del campo Motivo.'); parent.document.getElementById('pMotivo').focus();</script>";
		exit;
	}
}

if ($_POST["solapa"] == "o") {
	if (($_POST["oRazonSocial"] == "") and ($_POST["oNombreApellido"] == "")) {
		echo "<script type='text/javascript'>alert('Por favor, complete la Razón Social o el Nombre y Apellido.'); parent.document.getElementById('oRazonSocial').focus();</script>";
		exit;
	}
	if ($_POST["oEmail"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo e-Mail.'); parent.document.getElementById('oEmail').focus();</script>";
		exit;
	}

	$params = array(":email" => $_POST["oEmail"]);
	$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
	if (ValorSql($sql, "", $params) != "S") {
		echo "<script type='text/javascript'>alert('El e-Mail ingresado es inválido.'); parent.document.getElementById('oEmail').select(); parent.document.getElementById('oEmail').focus();</script>";
		exit;
	}

	if ($_POST["oTelefono"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Teléfono.'); parent.document.getElementById('oTelefono').focus();</script>";
		exit;
	}
	if ($_POST["oMotivo"] == -1) {
		echo "<script type='text/javascript'>alert('Por favor, seleccione un item del campo Motivo.'); parent.document.getElementById('oMotivo').focus();</script>";
		exit;
	}
}

if ($_POST["mensaje"] == "") {
	echo "<script type='text/javascript'>alert('Por favor, complete el campo Mensaje.'); parent.document.getElementById('mensaje').focus();</script>";
	exit;
}

if ($_POST["captcha"] != $_SESSION["captcha"]) {
	echo "<script type='text/javascript'>alert('Por favor, ingrese el captcha correcto.'); parent.document.getElementById('captcha').focus();</script>";
	exit;
}


$body = "<html><body>";

if ($_POST["solapa"] == "e") {
	$body.= "<p>Razón Social: <b>".$_POST["eRazonSocial"]."</b></p>";
	$body.= "<p>C.U.I.T.: <b>".$_POST["eCuit"]."</b></p>";
	$body.= "<p>Nombre y Apellido: <b>".$_POST["eNombreApellido"]."</b></p>";
	$body.= "<p>Cargo: <b>".$_POST["eCargo"]."</b></p>";
	$body.= "<p>e-Mail: <b>".$_POST["eEmail"]."</b></p>";
	$body.= "<p>Teléfono: <b>".$_POST["eTelefono"]."</b></p>";
	$body.= "<p>Motivo: <b>".$_POST["eMotivo"]."</b></p>";
}
if ($_POST["solapa"] == "t") {
	$body.= "<p>Nombre y Apellido: <b>".$_POST["tNombreApellido"]."</b></p>";
	$body.= "<p>C.U.I.L.: <b>".$_POST["tCuil"]."</b></p>";
	$body.= "<p>e-Mail: <b>".$_POST["tEmail"]."</b></p>";
	$body.= "<p>Teléfono: <b>".$_POST["tTelefono"]."</b></p>";
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

SendEmail($body, "Contacto Sitio Web", "Mensaje desde el sitio web de Provincia ART", array("info@provart.com.ar"), array(), array(), "H");
?>
<script type="text/javascript">
	function mostrarBoton() {
		with (window.parent.document) {
			getElementById('spanPuntos').style.display = 'none';
			getElementById('btnEnviar').style.display = 'inline';
			getElementById('formContacto').reset();
			getElementById('solapa').value = '<?= $_POST["solapa"]?>';
		}
		window.parent.recargarCaptcha();
	}

	with (window.parent.document) {
		getElementById('btnEnviar').style.display = 'none';
		getElementById('spanMsgOk').style.visibility = 'visible';
		getElementById('spanPuntos').style.display = 'inline';
	}
	setTimeout("mostrarBoton()", 3000);
</script>