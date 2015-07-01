<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


try {
	$params = array(":usuario" => getWindowsLoginName(true), ":usubaja" => getWindowsLoginName(true), ":usumodif" => getWindowsLoginName(true));
	$sql =
		"UPDATE web.wae_articulosextranet
				SET (ae_carpetaimagenes, ae_cuerpo, ae_cuerpofull, ae_fechabaja, ae_fechamodif, ae_posicion, ae_titulo, ae_usubaja, ae_usumodif, ae_volanta) =
						(SELECT ax_carpetaimagenes, ax_cuerpo, ax_cuerpofull, DECODE(ax_baja, 'T', SYSDATE, NULL), SYSDATE, ax_posicion, ax_titulo, DECODE(ax_baja, 'T', :usubaja, NULL), :usumodif, ax_volanta
							 FROM web.wax_articulosextranetedicion
							WHERE ae_id = ax_idarticuloextranet
								AND ax_usuario = :usuario)
			WHERE EXISTS(SELECT 1
										 FROM web.wax_articulosextranetedicion
										WHERE ae_id = ax_idarticuloextranet)";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":usuario" => getWindowsLoginName(true));
	$sql =
		"INSERT INTO web.wae_articulosextranet
								 (ae_carpetaimagenes, ae_cuerpo, ae_cuerpofull, ae_fechaalta, ae_posicion, ae_titulo, ae_usualta, ae_volanta)
					SELECT ax_carpetaimagenes, ax_cuerpo, ax_cuerpofull, sysdate, ax_posicion, ax_titulo, ax_usuario, ax_volanta
						FROM web.wax_articulosextranetedicion
					 WHERE ax_usuario = :usuario
						 AND ax_idarticuloextranet = -1";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);


	DBCommit($conn);

	unset($_SESSION["extranetEdicionActiva"]);		// Borro esta variable para que se recarguen los datos..
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	alert('Los cambios se aplicaron exitosamente.');
	window.parent.location.reload(true);
</script>