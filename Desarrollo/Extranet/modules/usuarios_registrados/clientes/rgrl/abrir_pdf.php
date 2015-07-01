<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 100));

// Valido que el archivo a mostrar sea del establecimiento relacionado al contrato del usuario..
$params = array(":contrato" => $_SESSION["contrato"], ":id" => $_REQUEST["id"]);
$sql =
	"SELECT 1
		 FROM aes_establecimiento
		WHERE es_contrato = :contrato
			AND es_id = :id";
validarSesion(ExisteSql($sql, $params));


$params = array(":contrato" => $_SESSION["contrato"], ":idestablecimiento" => $_REQUEST["id"]);
$sql =
	"SELECT art.hys.get_ultvigenciarelev463(:contrato, (SELECT es_nroestableci
																												FROM aes_establecimiento
																											 WHERE es_id = :idestablecimiento), 'E')
		 FROM DUAL";
$vigencia = ValorSql($sql, "", $params);

$valorContrato = "00".$_SESSION["contrato"];
$valorContrato = substr($valorContrato, strlen($_SESSION["contrato"]) - 1, 3);

$pathDestino = DATA_PDF_SERVER.$valorContrato."/";
$pathDestino.= substr($_REQUEST["id"], strlen($_REQUEST["id"]) - 1, 1)."/";

$filename = $_SESSION["contrato"]."_".$_REQUEST["id"]."_".$vigencia.".pdf";
?>
<html>
	<head>
		<script type="text/javascript">
<?
if (file_exists($pathDestino.$filename)) {
?>
			window.open('<?= getFile($pathDestino.$filename)?>', 'extranetWindow');
<?
}
else {
?>
			alert('No se encuentra el archivo.');
<?
}
?>
		</script>
	</head>
</html>