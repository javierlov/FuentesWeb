<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function validar() {
	global $campoError;

	if ($_POST["cuitInicial"] == "") {
		$campoError = "cuitInicial";
		throw new Exception("Debe ingresar la C.U.I.T.");
	}

	if (!validarCuit($_POST["cuitInicial"])) {
		$campoError = "cuitInicial";
		throw new Exception("La C.U.I.T. ingresada es inválida");
	}

	if ($_POST["contratoInicial"] == "") {
		$campoError = "contratoInicial";
		throw new Exception("Debe ingresar el Contrato.");
	}

	if (!validarEntero($_POST["contratoInicial"])) {
		$campoError = "contratoInicial";
		throw new Exception("El Contrato debe ser un valor numérico.");
	}

	$params = array(":contrato" => $_POST["contratoInicial"], ":cuit" => $_POST["cuitInicial"]);
	$sql =
		"SELECT vp_id
			 FROM afi.avp_valida_pcp
			WHERE vp_contrato = :contrato
				AND vp_cuit = :cuit
				AND vp_fechabaja IS NULL";
	$_SESSION["pcpId"] = valorSql($sql, -1, $params);
	if ($_SESSION["pcpId"] == -1) {
		$campoError = "contratoInicial";
		throw new Exception("El Contrato no se corresponde con la C.U.I.T. ingresada.");
	}

	return true;
}


try {
	$campoError = "";

	if (!validar())
		exit;

	$_SESSION["paso"] = 1;
}
catch (Exception $e) {
?>
	<script type="text/javascript">
		with (window.parent.document) {
			if (getElementById('<?= $campoError?>') != null) {
				getElementById('<?= $campoError?>').style.backgroundColor = '#f00';
				getElementById('<?= $campoError?>').style.color = '#fff';
				getElementById('<?= $campoError?>').focus();
			}
			alert(unescape('<?= rawurlencode($e->getMessage())?>'));
			setTimeout("window.parent.document.getElementById('<?= $campoError?>').style.backgroundColor = ''; window.parent.document.getElementById('<?= $campoError?>').style.color = '';", 2000);
		}
	</script>
<?
	exit;
}
?>
<script type="text/javascript">
	window.parent.location.reload();
</script>