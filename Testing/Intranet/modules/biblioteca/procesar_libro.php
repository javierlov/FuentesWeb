<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


if ($_POST["tipoOp"] == "A") {		// Alta..
	$sql =
		"INSERT INTO rrhh.bli_libro (li_autor, li_ibsn, li_dias, li_estado, li_fechaalta, li_id, li_resumen, li_tema, li_titulo, li_usualta)
										VALUES (:autor, :isbn, :dias, :estado, SYSDATE, :id, :resumen, :tema, :titulo, UPPER(:usualta))";
	$params = array(":autor" => $_POST["autor"],
								":isbn" => $_POST["isbn"],
								":dias" => nullIsEmpty($_POST["dias"]),
								":estado" => "LIBRE",
								":id" => -1,
								":resumen" => $_POST["resumen"],
								":tema" => $_POST["tema"],
								":titulo" => $_POST["titulo"],
								":usualta" => GetWindowsLoginName());
	DBExecSql($conn, $sql, $params);
}

if ($_POST["tipoOp"] == "M") {		// Modificación..
	$sql =
		"UPDATE rrhh.bli_libro
				SET li_autor = :autor,
						li_dias = :dias,
						li_fechamodif = SYSDATE,
						li_ibsn = :isbn,
						li_resumen = :resumen,
						li_tema = :tema,
						li_titulo = :titulo,
						li_usumodif = UPPER(:usumodif)
			WHERE li_id = :id";
	$params = array(":autor" => $_POST["autor"],
								":dias" => nullIsEmpty($_POST["dias"]),
								":isbn" => $_POST["isbn"],
								":resumen" => $_POST["resumen"],
								":tema" => $_POST["tema"],
								":titulo" => $_POST["titulo"],
								":usumodif" => GetWindowsLoginName(),
								":id" => $_POST["id"]);
	DBExecSql($conn, $sql, $params);
}

if ($_POST["tipoOp"] == "B") {		// Baja..
	$sql =
		"UPDATE rrhh.bli_libro
				SET li_fechabaja = SYSDATE,
						li_usubaja = UPPER(:usubaja)
			WHERE li_id = :id";
	$params = array(":usubaja" => GetWindowsLoginName(), ":id" => $_POST["id"]);
	DBExecSql($conn, $sql, $params);
}
?>
<html>
<head>
<script>
<?
if ($dbError["offset"]) {
?>
	alert('<?= $dbError["message"]?>');
<?
}
else {
?>
	window.parent.location.href = '/index.php?pageid=59&buscar=yes';
<?
}
?>
</script>
</head>
<body>
	ok
</body>
</html>