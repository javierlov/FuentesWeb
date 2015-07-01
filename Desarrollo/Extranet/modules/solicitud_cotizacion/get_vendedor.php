<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));

$params = array(":identidad" => $_SESSION["entidad"], ":vendedor" => $_REQUEST["codigo"]);
$sql =
	"SELECT ve_nombre
		 FROM xve_vendedor, xev_entidadvendedor
		WHERE ve_id = ev_idvendedor
			AND ev_identidad = :identidad
			AND ve_vendedor = :vendedor
			AND ev_fechabaja IS NULL
			AND ve_fechabaja IS NULL";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	window.parent.document.getElementById('vendedor').innerText = '<?= $row["VE_NOMBRE"]?>';
</script>