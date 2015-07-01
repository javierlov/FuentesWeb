<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 70));

$params = array(":id" => $_SESSION["idEmpresa"]);
$sql =
	"SELECT art.afiliacion.get_contratovigente(em_cuit, SYSDATE)
		 FROM aem_empresa
		WHERE em_id = :id";
$contrato = ValorSql($sql, "", $params);

$curs = null;
$params = array(":contrato" => $contrato);
$sql = "BEGIN art.web.get_deuda_certificado(:contrato, :data); END;";
$stmt = DBExecSP($conn, $curs, $sql, $params);
$row = DBGetSP($curs);
$tieneDeuda = (floatval("0".str_replace(",", ".", $row["DEUDATOTAL"])) > 0);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('tieneDeuda').value = 'f';
		getElementById('trCccr').style.display = 'none';
		getElementById('trCce').style.display = 'none';
		getElementById('trDeuda').style.display = 'none';
		getElementById('trDeuda2').style.display = 'none';

		getElementById('<?= $_REQUEST["valor"]?>').style.display = 'block';

<?
if (($tieneDeuda) and ($_REQUEST["valor"] == "trCccr")) {
?>
		getElementById('tieneDeuda').value = 't';
		getElementById('trCccr').style.display = 'none';
		getElementById('trDeuda').style.display = 'block';
<?
}
?>
	}
</script>