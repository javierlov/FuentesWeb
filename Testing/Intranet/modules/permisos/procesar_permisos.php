<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


// Borro todos los permisos de la página en cuestión..
$params = array(":idpagina" => $_REQUEST["PageId"]);
$sql =
	"DELETE FROM web.wpe_permisosintranet
				 WHERE pe_idpagina = :idpagina";
DBExecSql($conn, $sql, $params);


// Agrego los permisos para los usuarios seleccionados..
for ($i=0; $i<count($_REQUEST["UsuariosConPermiso"]); $i++) {
	$params = array(":idusuario" => $_REQUEST["UsuariosConPermiso"][$i], ":idpagina" => $_REQUEST["PageId"]);
	$sql =
		"INSERT INTO web.wpe_permisosintranet (pe_idusuario, pe_idpagina)
																	 VALUES (:idusuario, :idpagina)";
	DBExecSql($conn, $sql, $params);
}
?>
<script>
<?
if ($dbError["offset"]) {
?>
	alert('<?= $dbError["message"]?>');
<?
}
else {
?>
	window.parent.document.getElementById('spanMensaje').style.display = 'block';
<?
}
?>
</script>