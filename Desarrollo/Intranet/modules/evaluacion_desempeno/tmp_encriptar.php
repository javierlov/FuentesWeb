<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once("crypt.php");


echo strlen(encriptar("1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 1234567890 qwe rty uio pas dfg hjk lñz xcv bnm 123456789 abc def ghi jkl22"))."<br>";
echo strlen(encriptar("________________1____________________________________1______________________________2__________________________3____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________dsfsdfsdfsdfsdfdsfsdfsdf_____"))."<br>";
exit;


$sql =
	"SELECT rc_descripcion, rc_id, rc_valor
		 FROM rrhh.rrc_relacomptencia";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
	$params = array(":descripcion" => encriptar($row["RC_ID"].$row["RC_DESCRIPCION"]), ":id" => $row["RC_ID"], ":valor" => encriptar($row["RC_ID"].$row["RC_VALOR"]));
	$sql =
		"UPDATE rrhh.rrc_relacomptencia
				SET rc_descripcion = :descripcion, rc_valor = :valor
			WHERE rc_id = :id";
//	DBExecSql($conn, $sql, $params);

	echo substr(desencriptar($row["RC_VALOR"]), strlen($row["RC_ID"]))." - ".substr(desencriptar($row["RC_DESCRIPCION"]), strlen($row["RC_ID"]))."<br/>";
}

echo "OK<br />";
echo "*** CORRERLO SOLO UNA VEZ ***<br />";
?>