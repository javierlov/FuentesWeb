<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$_POST["mensaje2"] = ucfirst($_POST["mensaje2"]);

$params = array(":idsesion" => $_SESSION["chatIdSession"], ":mensaje" => $_POST["mensaje2"]);
$sql =
	"INSERT INTO web.wmc_mensajeschat (mc_enviadopor, mc_fechaenvio, mc_idsesion, mc_leidoporoperador, mc_leidoporusuario, mc_mensaje)
														 VALUES ('U', SYSDATE, :idsesion, 'N', 'S', :mensaje)";
DBExecSql($conn, $sql, $params);
?>
<script type="text/javascript" src="/modules/chat/js/chat.js"></script>
<script>
	msg = '<div class="divMsgUsuario"><?= htmlspecialchars(str_replace(array("\t", "\n", "\r", "\0", "\x0B"), " ", $_POST["mensaje2"]), ENT_QUOTES, CHARSET)?></div>';
	escribirMensaje(window.parent, msg);
</script>