<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$params = array(":id" => $_REQUEST["pblccn"]);
$sql =
	"SELECT ip_activo, ip_archivo, ip_idtema
		 FROM intra.cip_informepublicado
		WHERE ip_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
$filename = base64_encode(DATA_INFORMES_GESTION.$row["IP_ARCHIVO"]);

$params = array(":activohistorico" => $row["IP_ACTIVO"],
								":idpublicado" => $_REQUEST["pblccn"],
								":idtema" => $row["IP_IDTEMA"],
								":usuario" => getWindowsLoginName());
$sql =
	"INSERT INTO intra.cie_informeestadistica (ie_idtema, ie_idpublicado, ie_usuario, ie_fecha, ie_activohistorico)
																		 VALUES (:idtema, :idpublicado, UPPER(:usuario), SYSDATE, :activohistorico)";
DBExecSql($conn, $sql, $params);
?>
<script>
	win = window.open('<?= "/archivo/".$filename?>', 'intranetWindow');
	win.location.href = '<?= "/archivo/".$filename?>';
</script>