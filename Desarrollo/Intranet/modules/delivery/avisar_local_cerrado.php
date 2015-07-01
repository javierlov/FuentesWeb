<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


$params = array(":id" => getUserId());
$sql =
	"SELECT se_nombre
		 FROM art.use_usuarios
		WHERE se_id = :id";
$usuario = valorSql($sql, "", $params);

$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT hd_nombre
		 FROM rrhh.rhd_delivery
		WHERE hd_id = :id";
$local = valorSql($sql, "", $params);

$body = "El usuario ".$usuario." avisa que el local ".$local." está cerrado.";
$subject = "Aviso de cierre de local de delivery";
sendEmail($body, "Intranet", $subject, getEmailsAviso(), array(), array(), "H");
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	showMsgOk('/delivery', window.parent);
</script>