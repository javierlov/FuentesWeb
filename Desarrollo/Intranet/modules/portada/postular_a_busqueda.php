<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


try {
	$params = array(":texto" => ",".getUserId().",");
	$sql =
		"SELECT 1
			 FROM rrhh.rbc_busquedascorporativas
			WHERE INSTR(bc_postulados, :texto) > 0";
	if (!existeSql($sql, $params)) {
		$params = array(":id" => $_REQUEST["id"], ":texto" => getUserId().",");
		$sql =
			"UPDATE rrhh.rbc_busquedascorporativas
					SET bc_postulados = bc_postulados || :texto
				WHERE bc_id = :id";
		DBExecSql($conn, $sql, $params);
	}

	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT bc_nombrearchivo, bc_puesto
			 FROM rrhh.rbc_busquedascorporativas
			WHERE bc_id = :id";
	$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	$row = DBGetQuery($stmt);

	$body = "El usuario ".getUserName()." se ha postulado a la búsqueda para el puesto de <i>".$row["BC_PUESTO"]."</i>.";

	$fileTitle = addslashes($row["BC_NOMBREARCHIVO"]);
	$partesFile = pathinfo($fileTitle);
	if (!isset($partesFile["extension"]))
		$partesFile["extension"] = "";
	$file = base64_encode(DATA_BUSQUEDAS_CORPORATIVAS_PATH.$_REQUEST["id"].".".$partesFile["extension"]);

	if ($partesFile["extension"] != "")
		$body.= "<br /><br />El perfil buscado lo puede ver <a href=\"http://".$_SERVER["HTTP_HOST"]."/archivo/".$file."/".$fileTitle."/ok\">aquí</a>.";

	$subject = "Nueva postulación laboral en la Intranet de Provincia ART";
	sendEmail($body, "Intranet", $subject, getEmailsAviso(), array(), array(), "H");

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	showErrorIntranet("", rawurlencode($e->getMessage()));
	exit;
}
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	window.parent.document.getElementById('imgBusquedasPostularme_<?= $_REQUEST["id"]?>').src = '/modules/portada/images/postularme.png';
	setTimeout("window.parent.document.getElementById('imgBusquedasPostularme_<?= $_REQUEST["id"]?>').src = '/modules/portada/images/ya_postulado.png';", 500);
</script>