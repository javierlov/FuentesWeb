<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function solicitarArchivo($contrato, $file) {
	global $conn;

	$params = array(":contrato" => $contrato, ":rutasalida" => $file);
	$sql =
		"INSERT INTO web.wfe_formularioestablecimientos (fe_contrato, fe_rutasalida, fe_fechahorainicio)
																						 VALUES (:contrato, :rutasalida, SYSDATE)";
	DBExecSql($conn, $sql, $params);
}


if (!isset($_SESSION["contrato"])) {
	header("Location: login.php");
	validarParametro(false);
	exit;
}

$contrato = $_SESSION["contrato"];
$file = DATA_FORMULARIO_ESTABLECIMIENTOS."contrato_".$contrato.".pdf";
$fileE = DATA_FORMULARIO_ESTABLECIMIENTOS_EXTERNAL."contrato_".$contrato.".pdf";

if (!file_exists($file))
	solicitarArchivo($contrato, $fileE);
elseif ((file_exists($file)) and (date("c") > date("c", filemtime($file) + 600))) {		// Si el archivo existe y se creó hace mas de 10 minutos lo borro para que se genere de nuevo..
	unlink($fileE);
	solicitarArchivo($contrato, $fileE);
}

set_time_limit(120);
while (!file_exists($file))		// Queda loopeando hasta que se genere el archivo o salga por timeout..
	sleep(2);

logAccess($contrato, 1, gethostbyaddr($_SERVER['REMOTE_ADDR']), $_SERVER["REMOTE_ADDR"], 2);
?>
<script type="text/javascript">
	window.open('<?= getFile($file)?>', 'extranetWindow', 'location=0');
	history.back();
</script>
Abriendo archivo...