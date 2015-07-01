<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


if ($_REQUEST["action"] == "C") {		// Cargar..
	$sql =
		"SELECT it_tema
			 FROM intra.cit_informetemas
			WHERE it_id = :id";
	$params = array(":id" => $_REQUEST["id"]);
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
?>
<script>
	window.parent.verTema(<?= $_REQUEST["id"]?>, unescape('<?= rawurlencode($row["IT_TEMA"])?>'));
</script>
<?
}


if ($_REQUEST["action"] == "E") {		// Eliminar..
	$sql =
		"UPDATE intra.cit_informetemas
				SET it_usubaja = UPPER(:usubaja),
						it_fechabaja = SYSDATE
		  WHERE it_id = :id";
	$params = array(":usubaja" => GetWindowsLoginName(), ":id" => $_REQUEST["id"]);
	DBExecSql($conn, $sql, $params);

	$sql =
		"UPDATE intra.cip_informepublicado
				SET ip_usubaja = UPPER(:usubaja),
						ip_fechabaja = SYSDATE
		  WHERE ip_idtema = :idtema";
	$params = array(":usubaja" => GetWindowsLoginName(), ":idtema" => $_REQUEST["id"]);
	DBExecSql($conn, $sql, $params);
?>
<script>
	window.parent.location.reload();
</script>
<?
}


if ($_REQUEST["action"] == "G") {		// Guardar..
	try {
		if ($_POST["Id"] == -1)		// Es una alta..
			$sql =
				"INSERT INTO intra.cit_informetemas (it_tema, it_usualta, it_fechaalta)
																VALUES (".addQuotes($_POST["NombreTema"]).", UPPER(".addQuotes(GetWindowsLoginName())."), SYSDATE)";
		else
			$sql =
				"UPDATE intra.cit_informetemas
						SET it_tema = :tema,
								it_usumodif = UPPER(:usumodif),
								it_fechamodif = SYSDATE
				  WHERE it_id = :id";
		$params = array(":tema" => $_POST["NombreTema"], ":usumodif" => GetWindowsLoginName(), ":id" => $_POST["Id"]);
		DBExecSql($conn, $sql, $params);
	}
	catch (Exception $e) {
		echo "<script>alert('".$e->getMessage()."');</script>";
		exit;
	}
?>
	<script>
		window.parent.location.reload();
	</script>
<?
}
?>