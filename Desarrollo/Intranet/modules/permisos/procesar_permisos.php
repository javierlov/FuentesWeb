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


try {
	$params = array(":usuario" => getWindowsLoginName());
	$sql =
		"SELECT 1
			 FROM use_usuarios
			WHERE se_usuario IN ('ALAPACO', 'FPEREZ')
				AND UPPER(se_usuario) = UPPER(:usuario)";
	if (!existeSql($sql, $params))
		throw new Exception("Usted no tiene permiso para guardar en esta página.");

	// Borro todos los permisos de la página en cuestión..
	$params = array(":idpagina" => $_REQUEST["pageId"]);
	$sql =
		"DELETE FROM web.wpe_permisosintranet
					 WHERE pe_idpagina = :idpagina";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);


	// Agrego los permisos para los usuarios seleccionados..
	for ($i=0; $i<count($_REQUEST["usuariosConPermiso"]); $i++) {
		$params = array(":idpagina" => $_REQUEST["pageId"], ":idusuario" => $_REQUEST["usuariosConPermiso"][$i]);
		$sql =
			"INSERT INTO web.wpe_permisosintranet (pe_idusuario, pe_idpagina)
																		 VALUES (:idusuario, :idpagina)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script type='text/javascript'>
		alert('<?= $e->getMessage()?>');
	</script>
<?
	exit;
}
?>
<script type="text/javascript">
	window.parent.document.getElementById('divMsgOk').style.display = 'block';
	setTimeout(function() {window.parent.document.getElementById('divMsgOk').style.display = 'none';}, 2000);
</script>