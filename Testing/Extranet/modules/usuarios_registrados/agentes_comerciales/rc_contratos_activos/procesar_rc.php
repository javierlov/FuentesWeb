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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validar() {
	global $formaPago;
	global $iibb;
	global $iva;
	global $sumaAseguradaRC;

	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if (!isset($_SESSION["isAgenteComercial"])) {
		echo "errores+= '- Su sesión ha caducado, vuelva a loguearse.<br />';";
		$errores = true;
	}

	if (!($_SESSION["entidad"] != 400)) {
		echo "errores+= '- Usted no tiene permiso para cotizar RC.<br />';";
		$errores = true;
	}

	if (!((validarContrato($_POST["contrato"])) or (($_SESSION["canal"] == 321) and ($_POST["entidadContrato"] == 400)))) {
		echo "errores+= '- Contrato inválido.<br />';";
		$errores = true;
	}

	if ($sumaAseguradaRC == NULL) {
		echo "errores+= '- Debe indicar la Suma Asegurada.<br />';";
		$errores = true;
	}

	if ($formaPago == NULL) {
		echo "errores+= '- Debe indicar la Forma de Pago.<br />';";
		$errores = true;
	}

	if (($formaPago == "DA") and (strlen($_POST["cbu"]) != 22)) {
		echo "errores+= '- El C.B.U. debe tener 22 caracteres.<br />';";
		$errores = true;
	}

	if ($formaPago == "TC") {
		if ($_POST["tarjetaCredito"] == -1) {
			echo "errores+= '- Debe seleccionar la Tarjeta de Crédito.<br />';";
			$errores = true;
		}
		if (strlen($_POST["cbu"]) < 16) {
			echo "errores+= '- El Nº de Tarjeta de Crédito debe tener al menos 16 caracteres.<br />';";
			$errores = true;
		}
	}

	if ($iva == NULL) {
		echo "errores+= '- Debe indicar el tipo de I.V.A.<br />';";
		$errores = true;
	}

	if ($iibb == NULL) {
		echo "errores+= '- Debe indicar el tipo de I.I.B.B.<br />';";
		$errores = true;
	}

	if ($_POST["email"] == "") {
		echo "errores+= '- Debe indicar el e-Mail donde quiere recepcionar la póliza de la responsibilidad civil.<br />';";
		$errores = true;
	}
	else {
		$params = array(":email" => $_POST["email"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (ValorSql($sql, "", $params) != "S") {
			echo "errores+= '- El e-Mail es inválido.<br />';";
			$errores = true;
		}
	}

	if ($_POST["nombre"] == "") {
		echo "errores+= '- Debe indicar el Nombre y Apellido del Empleador.<br />';";
		$errores = true;
	}

	if ($_POST["sexo"] == -1) {
		echo "errores+= '- Debe indicar el Sexo del Empleador.<br />';";
		$errores = true;
	}

	if ($_POST["cargo"] == -1) {
		echo "errores+= '- Debe indicar el Cargo del Empleador.<br />';";
		$errores = true;
	}

	if ($_POST["dni"] == "") {
		echo "errores+= '- Debe indicar el D.N.I. del Empleador.<br />';";
		$errores = true;
	}
	elseif (strlen($_POST["dni"]) < 7) {
		echo "errores+= '- El D.N.I. del Empleador es inválido.<br />';";
		$errores = true;
	}
	elseif (!validarEntero($_POST["dni"])) {
		echo "errores+= '- El D.N.I. del Empleador es inválido.<br />';";
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


try {
	$formaPago = NULL;
	if (isset($_POST["formaPago"]))
		$formaPago = $_POST["formaPago"];

	$iibb = NULL;
	if (isset($_POST["iibb"]))
		$iibb = $_POST["iibb"];

	$iva = NULL;
	if (isset($_POST["iva"]))
		$iva = $_POST["iva"];

	$sumaAseguradaRC = NULL;
	if (isset($_POST["sumaAseguradaRC"]))
		$sumaAseguradaRC = $_POST["sumaAseguradaRC"];

	$suscribePolizaRC = NULL;
	if (isset($_POST["suscribePolizaRC"]))
		$suscribePolizaRC = $_POST["suscribePolizaRC"];


	if (!validar())
		exit;


	switch ($sumaAseguradaRC) {
		case 250000:
			$valorRC = $_POST["alicuota250"];
			break;
		case 500000:
			$valorRC = $_POST["alicuota500"];
			break;
		case 1000000:
			$valorRC = $_POST["alicuota1000"];
			break;
	}
	$valorRC = str_replace(array(".", "%"), array(",", ""), $valorRC);

	$params = array(":apellido_nomre" => $_POST["nombre"],
									":cbu" => $_POST["cbu"],
									":contrato" => $_POST["contrato"],
									":idcaracterfirma" => $_POST["cargo"],
									":iibb" => $iibb,
									":iva" => $iva,
									":mail" => $_POST["email"],
									":medio_pago" => $formaPago,
									":nrodocumento" => $_POST["dni"],
									":origenpago" => $_POST["tarjetaCredito"],
									":poliza" => $suscribePolizaRC,
									":sexo" => $_POST["sexo"],
									":sumaasegurada" => $sumaAseguradaRC,
									":usualta" => substr("W_".$_SESSION["usuario"], 0, 20),
									":valor_rc" => $valorRC,
									":valor250" => str_replace(array(".", "%"), array(",", ""), $_POST["alicuota250"]),
									":valor500" => str_replace(array(".", "%"), array(",", ""), $_POST["alicuota500"]),
									":valor1000" => str_replace(array(".", "%"), array(",", ""), $_POST["alicuota1000"]));
	$sql =
		"INSERT INTO art.apr_polizarc
								 (pr_apellido_nomre, pr_cbu, pr_fechaalta, pr_id, pr_idcaracterfirma, pr_idendoso,
									pr_idformulario, pr_iibb, pr_iva, pr_mail, pr_medio_pago, pr_nrodocumento, pr_origenpago, pr_poliza, pr_sexo, pr_sumaasegurada,
									pr_usualta, pr_valor_rc, pr_valor250, pr_valor500, pr_valor1000)
					VALUES (:apellido_nomre, :cbu, SYSDATE, -1, :idcaracterfirma, (SELECT MAX(en_id) FROM aen_endoso WHERE en_contrato = :contrato),
									(SELECT co_idformulario FROM aco_contrato WHERE co_contrato = :contrato), :iibb, :iva, :mail, :medio_pago, :nrodocumento, :origenpago, :poliza, :sexo, :sumaasegurada,
									:usualta, :valor_rc, :valor250, :valor500, :valor1000)";
	DBExecSql($conn, $sql, $params);
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function redirect() {
		window.parent.location.href = '/index.php?buscar=yes&pageid=78&contrato=<?= $_POST["contrato"]?>';
	}

	setTimeout('redirect()', 2000);
	window.parent.document.getElementById('guardadoOk').style.display = 'block';
	window.open('/modules/solicitud_afiliacion/reporte_responsabilidad_civil.php?c=<?= $_POST["contrato"]?>', 'extranetWindow', 'location=0');
</script>