<?
require_once ($_SERVER["DOCUMENT_ROOT"] . "/constants.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db_funcs.php");

global $conn;

$sql = "SELECT InitCap(SE_NOMBRE)
            FROM ART.USE_USUARIOS, COMPUTOS.CMS_MOTIVOSOLICITUD
           WHERE MS_ID = :id
             AND SE_ID = COMPUTOS.GENERAL.GET_USUARIORESPONSABLE(:usuario, MS_NIVEL)";
$params = array(":id" => $_REQUEST['param2'], ":usuario" => $_REQUEST['param1']);
$stmt = DBExecSql($conn, $sql, $params);
$user = ValorSql($sql, "", $params);

if ($user != "") {
    $msg = "<br/><error_label>" . htmlentities("Se solicitará autorización a " . $user, ENT_QUOTES, CHARSET) . "</error_label>";
    echo $msg;
}
?>