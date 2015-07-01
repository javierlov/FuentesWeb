<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 94));


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ii_archivo, ii_contrato, ii_periodo
		 FROM web.wii_informesiys
		WHERE ii_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$file = DATA_INFORMES_INGENIERIA_SINIESTRALIDAD.$row["II_CONTRATO"]."/".$row["II_PERIODO"]."/".$row["II_ARCHIVO"];
?>
<script type="text/javascript">
	window.location.href = '<?= getFile($file, "a")?>';
</script>