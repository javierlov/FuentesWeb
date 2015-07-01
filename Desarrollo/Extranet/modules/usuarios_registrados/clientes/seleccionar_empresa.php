<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));

$params = array(":contrato" => $_REQUEST["id"]);
$sql =
	"SELECT art.afi.check_cobertura(em_cuit, SYSDATE) contratovigente, em_cuit, em_id, em_nombre, em_suss
		 FROM aco_contrato, aem_empresa
		WHERE co_idempresa = em_id
			AND co_contrato = :contrato";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$_SESSION["contrato"] = $_REQUEST["id"];
$_SESSION["contratoVigente"] = ($row["CONTRATOVIGENTE"] == 1);
$_SESSION["cuit"] = $row["EM_CUIT"];
$_SESSION["empresa"] = $row["EM_NOMBRE"];
$_SESSION["idEmpresa"] = $row["EM_ID"];
$_SESSION["suss"] = $row["EM_SUSS"];
?>
<script type="text/javascript">
	window.parent.location.href = '/acceso-clientes';
</script>