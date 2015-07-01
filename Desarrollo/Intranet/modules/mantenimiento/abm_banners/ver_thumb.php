<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/images.php");


$params = array(":id" => substr($_SERVER["PATH_INFO"], strpos($_SERVER["PATH_INFO"], "/ID=") + 4));
$sql =
	"SELECT br_id, br_imagen
		 FROM rrhh.rbr_banners
		WHERE br_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$img = IMAGES_BANNERS_PATH.$row["BR_ID"]."/".$row["BR_IMAGEN"];
getImage($img, 40, 40, 40);
?>