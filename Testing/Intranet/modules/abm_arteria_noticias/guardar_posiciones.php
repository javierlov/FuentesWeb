<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


try {
	for ($i = 1; $i <= 8; $i++)
		if ($_POST["idNoticia".$i] != "") {
			$sql =
				"UPDATE rrhh.rna_noticiasarteria
						SET na_posicion = :posicion
				  WHERE na_id = :id";
			$params = array(":posicion" => $_POST["posicion".$i], ":id" => $_POST["idNoticia".$i]);
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script>
	parent.location.reload();
</script>