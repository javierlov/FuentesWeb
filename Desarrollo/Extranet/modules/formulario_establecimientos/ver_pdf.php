<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


if (!isset($_SESSION["contrato"])) {
	header("Location: login.php");
	exit;
}

$params = array(":contrato" => $_SESSION["contrato"], ":id" => $_REQUEST["id"]);
$sql =
	"SELECT MAX(rl_vigencia)
		 FROM aes_establecimiento, hys.hrl_relevriesgolaboral
		WHERE es_nroestableci = rl_estableci
			AND rl_contrato = :contrato
			AND es_id = :id
			AND rl_fechabaja IS NULL";
$vigencia = valorSql($sql, "", $params);

$valorContrato = "00".$_SESSION["contrato"];
$valorContrato = substr($valorContrato, strlen($_SESSION["contrato"]) - 1, 3);

$pathDestino = DATA_PDF_SERVER.$valorContrato."\\";
$pathDestino.= substr($_REQUEST["id"], strlen($_REQUEST["id"]) - 1, 1)."\\";

$filename = $_SESSION["contrato"]."_".$_REQUEST["id"]."_".$vigencia.".pdf";

if (file_exists($pathDestino.$filename)) {
?>
<script type="text/javascript">
	window.open('<?= getFile($pathDestino.$filename)?>', 'intranetWindow');
	window.history.go(-1);
</script>
<?
}
else {
?>
	Formulario no presentado<br><br><a href="#" onClick="history.back();">Volver</a>
<?
}
?>