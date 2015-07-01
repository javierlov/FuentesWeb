<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


try {
	$urlSiguiente = "window.parent.location.reload();";

	if ($_REQUEST["accion"] == 	"D") {
		$sql =
			"UPDATE rrhh.bpr_prestamo
					SET pr_idusuariodevolucion = UPPER(:idusuariodevolucion),
							pr_fechadevolucion = SYSDATE
			  WHERE pr_id = :id";
		$params = array(":idusuariodevolucion" => GetWindowsLoginName(), ":id" => $_REQUEST["id"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql =
			"UPDATE rrhh.bli_libro
					SET li_estado = 'LIBRE'
			  WHERE li_id = (SELECT pr_idlibro
			  							  FROM rrhh.bpr_prestamo
			  							WHERE pr_id = :id)";
		$params = array(":id" => $_REQUEST["id"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
	if ($_REQUEST["accion"] == 	"E") {
		$sql =
			"UPDATE rrhh.bpr_prestamo
					SET pr_idusuarioentrega = UPPER(:idusuarioentrega),
							pr_fechaentrega = SYSDATE
			  WHERE pr_id = :id";
		$params = array(":idusuarioentrega" => GetWindowsLoginName(), ":id" => $_REQUEST["id"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql =
			"UPDATE rrhh.bli_libro
					SET li_estado = 'PRESTADO'
			  WHERE li_id = (SELECT pr_idlibro
			  							  FROM rrhh.bpr_prestamo
			  							WHERE pr_id = :id)";
		$params = array(":id" => $_REQUEST["id"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
	if ($_REQUEST["accion"] == 	"M") {
		$urlSiguiente = "window.parent.location.href = '/index.php?pageid=60&id=".$_REQUEST["id"]."';";
	}
	if ($_REQUEST["accion"] == 	"R") {
		$sql =
			"INSERT INTO rrhh.bpr_prestamo (pr_fechareserva, pr_fechavencimiento, pr_horareserva, pr_id, pr_idlibro, pr_idusuario)
													 VALUES (SYSDATE, SYSDATE + (SELECT NVL(li_dias, 0)
																												FROM rrhh.bli_libro
																											 WHERE li_id = :id), SYSDATE, -1, :id, UPPER(:idusuario))";
		$params = array(":id" => $_REQUEST["id"], ":idusuario" => GetWindowsLoginName());
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql =
			"UPDATE rrhh.bli_libro
					SET li_estado = 'RESERVA'
			  WHERE li_id = :id";
		$params = array(":id" => $_REQUEST["id"]);
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
	<?= $urlSiguiente?>
</script>