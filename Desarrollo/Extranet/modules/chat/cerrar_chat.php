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


try {
	if (isset($_SESSION["chatIdSession"])) {
		$params = array(":id" => $_SESSION["chatIdSession"]);
		$sql =
			"SELECT 1
				 FROM web.wsc_sesioneschat
				WHERE sc_id = :id
					AND sc_estado <> 4";
		if (existeSql($sql, $params)) {		// Si la sesión esta abierta, la cierro..
			$sql = "SELECT cc_mensajefinal FROM web.wcc_constanteschat";
			$msgFinal = valorSql($sql);

			$params = array(":idsesion" => $_SESSION["chatIdSession"], ":mensaje" => $msgFinal);
			$sql =
				"INSERT INTO web.wmc_mensajeschat (mc_enviadopor, mc_fechaenvio, mc_idsesion, mc_leidoporoperador, mc_leidoporusuario, mc_mensaje, mc_tipomensaje)
																	 VALUES ('U', SYSDATE, :idsesion, 'N', 'N', :mensaje, 'F')";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			$params = array(":id" => $_SESSION["chatIdSession"]);
			$sql =
				"UPDATE web.wsc_sesioneschat
						SET sc_estado = 4,
								sc_fechacierreconexion = SYSDATE,
								sc_generadordesconexion = 'U'
					WHERE sc_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			DBCommit($conn);
		}

		unset($_SESSION["chatIdSession"]);
	}
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script type='text/javascript'>
		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	</script>
<?
	exit;
}
?>
<script type="text/javascript" src="/modules/chat/js/chat.js"></script>
<script>
	with (window.parent.document) {
		getElementById('iframeChatRecibir').src = '';

		getElementById('divChatFondo').style.display = 'none';
		getElementById('divChatContenido').innerHTML = '';
		getElementById('divChatContenido').style.width = '0';
		getElementById('imgBotonChat').onClick = 'abrirChat()';
		getElementById('imgBotonChat').style.cursor = 'pointer';
	}
</script>