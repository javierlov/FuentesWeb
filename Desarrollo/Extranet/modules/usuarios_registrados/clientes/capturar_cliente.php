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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion((isset($_SESSION["isAdminTotal"])) and ($_SESSION["isAdminTotal"]));


$cuit = sacarGuiones($_REQUEST["valor"]);

if (validarCuit($cuit)) {
	$msg = " Esta C.U.I.T. no puede ser capturada. ";
	$params = array(":cuit" => $cuit);
	$sql = " AND em_cuit = :cuit";
}
elseif (validarEntero($_REQUEST["valor"])) {
	$msg = " Este contrato no puede ser capturado. ";
	$params = array(":contrato" => $_REQUEST["valor"]);
	$sql = " AND co_contrato = :contrato";
}
else {
	$msg = " Esta empresa no puede ser capturada. ";
	$params = array(":nombre" => $_REQUEST["valor"]."%");
	$sql = " AND em_nombre LIKE UPPER(:nombre)";
}

$sql =
	"SELECT co_contrato, em_cuit, em_id, em_nombre, em_suss, art.afiliacion.check_cobertura(co_contrato, SYSDATE) status
		 FROM aco_contrato, aem_empresa
		WHERE co_idempresa = em_id
			AND ROWNUM < 10".$sql."
 ORDER BY co_contrato DESC";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

if ($row["STATUS"] == 1) {
	$_SESSION["contrato"] = $row["CO_CONTRATO"];
	$_SESSION["cuit"] = $row["EM_CUIT"];
	$_SESSION["empresa"] = $row["EM_NOMBRE"];
	$_SESSION["idEmpresa"] = $row["EM_ID"];
	$_SESSION["suss"] = $row["EM_SUSS"];
	$color = "#0bb01b";
	$msg = " Captura realizada exitosamente. ";
}
else
	$color = "#f00";
?>
<script type="text/javascript">
	function hide() {
		window.parent.document.getElementById('msgCaptura').style.display = 'none';
	}

	with (window.parent.document) {
		getElementById('msgCaptura').innerHTML = '<?= $msg?>';
		getElementById('msgCaptura').style.color = '<?= $color?>';
		getElementById('msgCaptura').style.borderStyle = 'dotted';
		getElementById('msgCaptura').style.display = 'block';
		if (getElementById('msgCaptura').style.color != 'rgb(255, 0, 0)')
			setTimeout('hide()', 2000);
<?
if ($row["STATUS"] == 1) {
?>
		getElementById('empresa').innerText = '<?= $_SESSION["empresa"]?>';
		getElementById('trCentralServicios1').style.visibility = 'visible';
		getElementById('trCentralServicios2').style.visibility = 'visible';
<?
}
?>
	}
</script>