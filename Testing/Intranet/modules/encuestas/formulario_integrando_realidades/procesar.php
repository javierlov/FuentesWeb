<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$opcion1 = (isset($_REQUEST["check1"]))?"S":"N";
$opcion2 = (isset($_REQUEST["check2"]))?"S":"N";
$opcion3 = (isset($_REQUEST["check3"]))?"S":"N";
$opcion4 = (isset($_REQUEST["check4"]))?"S":"N";
$opcion5 = (isset($_REQUEST["check5"]))?"S":"N";
$opcion6 = (isset($_REQUEST["check6"]))?"S":"N";

$otras = "";
if ($_REQUEST["p1"] == "S")
	$otras = $_REQUEST["Otras"];

$sql =
	"SELECT ir_id
		FROM intra.oir_integrandorealidades
	 WHERE ir_idusuario = :idusuario";
$params = array(":idusuario" => GetUserID());
if (ValorSql($sql, -1, $params) == -1) {		// Doy de alta el registro vacío..
	$sql =
		"INSERT INTO intra.oir_integrandorealidades (ir_id, ir_idusuario)
																	VALUES (-1, :idusuario)";
	$params = array(":idusuario" => GetUserID());
	DBExecSql($conn, $sql, $params);
}

$sql =
	"UPDATE intra.oir_integrandorealidades
			SET ir_respuesta1 = :respuesta1,
					ir_respuesta2 = :respuesta2,
					ir_opcion1 = :opcion1,
					ir_opcion2 = :opcion2,
					ir_opcion3 = :opcion3,
					ir_opcion4 = :opcion4,
					ir_opcion5 = :opcion5,
					ir_opcion6 = :opcion6,
					ir_otras = :otras,
					ir_fechamodif = SYSDATE
	  WHERE ir_idusuario = :idusuario";
$params = array(":respuesta1" => $_REQUEST["p1"],
							":respuesta2" => $_REQUEST["p2"],
							":opcion1" => $opcion1,
							":opcion2" => $opcion2,
							":opcion3" => $opcion3,
							":opcion4" => $opcion4,
							":opcion5" => $opcion5,
							":opcion6" => $opcion6,
							":otras" => $otras,
							":idusuario" => GetUserID());
DBExecSql($conn, $sql, $params);

if ($dbError["offset"])
	header("refresh: 0; url=index.php?result=".$dbError["message"]);
else
	header("refresh: 0; url=index.php?result=ok");
?>