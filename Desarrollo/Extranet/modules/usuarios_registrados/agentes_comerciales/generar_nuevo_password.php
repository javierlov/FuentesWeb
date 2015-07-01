<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


$sql =
	"SELECT uw_clave, uw_id
  		FROM afi.auw_usuarioweb";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
	$sql =
		"UPDATE afi.auw_usuarioweb
				SET uw_password = :password
		  WHERE uw_id = :id";
	$params = array(":password" => md5($row["UW_CLAVE"]), ":id" => $row["UW_ID"]);
	DBExecSql($conn, $sql, $params);
}
?>