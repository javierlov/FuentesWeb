<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


try {
	for ($i=1; $i<=8; $i++)
		if ($_POST["idNoticia".$i] != "") {
			$params = array(":id" => $_POST["idNoticia".$i], ":posicion" => $_POST["posicion".$i]);
			$sql =
				"UPDATE rrhh.rna_noticiasarteria
						SET na_posicion = :posicion
				  WHERE na_id = :id";
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