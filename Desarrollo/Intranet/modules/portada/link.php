<?php
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


switch ($_REQUEST["l"]) {
	case 1:
		$url = "http://intranetgb/gbapro/";
		break;
	case 2:
		$url = "http://www.provinciart.com.ar";
		break;
	case 3:
		$url = "http://twitter.com/GrupoprovSA";
		break;
	case 4:
		$url = "http://www.facebook.com/GrupoProvincia";
		break;
	case 5:
		$params = array(":id" => $_REQUEST["id"]);
		$sql =
			"SELECT br_multilink, br_url, br_urlsingrupo
				 FROM rrhh.rbr_banners
				WHERE br_id = :id";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);

		if ($row["BR_MULTILINK"] == "S") {
			$params = array(":idbanner" => $_REQUEST["id"], ":idusuario" => getUserId());
			$sql =
				"SELECT gb_link
					 FROM rrhh.rgb_gruposbanners, rrhh.rug_usuariosxgruposbanners
					WHERE gb_id = ug_idgrupobanner
						AND gb_fechabaja IS NULL
						AND gb_idbanner = :idbanner
						AND ug_idusuario = :idusuario";
			$url = valorSql($sql, $row["BR_URLSINGRUPO"], $params);
		}
		else
			$url = $row["BR_URL"];

		if ($url == "")
			$url = "/pagina-no-encontrada";

		logUrlIn("/modules/portada/link.php?l=5");
		break;
}

logUrlIn($url);

header("Location: ".$url);
?>