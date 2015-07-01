<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");


validarSesion(isset($_SESSION["isOrganismoPublico"]));
try {
	$antiguedad = "F";
	if (isset($_POST["antiguedad"]))
		$antiguedad = "T";

	$bonus = "F";
	if (isset($_POST["bonus"]))
		$bonus = "T";

	$premios = "F";
	if (isset($_POST["premios"]))
		$premios = "T";

	$otrosConceptos = "F";
	if (isset($_POST["otrosConceptos"]))
		$otrosConceptos = "T";

	$presentismo = "F";
	if (isset($_POST["presentismo"]))
		$presentismo = "T";

	$refrigerio = "F";
	if (isset($_POST["refrigerio"]))
		$refrigerio = "T";

	$viaticos = "F";
	if (isset($_POST["viaticos"]))
		$viaticos = "T";

	$params = array(":contrato" => $_SESSION["contrato"], ":periodo" => $_POST["periodoProcesado"]);
	$sql = 
		"SELECT 1
			 FROM emi.icr_conceptoremunerativo
			WHERE cr_contrato = :contrato
				AND cr_periodo = :periodo";
	if (ExisteSql($sql, $params)) {
		$_POST["esAlta"] = "F";
		$_POST["esModificacion"] = "T";
	}

	if ($_POST["esAlta"] == "T") {
		$params = array(":antiguedad" => $antiguedad,
										":bonus" => $bonus,
										":contrato" => $_SESSION["contrato"],
										":otros" => $otrosConceptos,
										":periodo" => $_POST["periodoProcesado"],
										":premios" => $premios,
										":presentismo" => $presentismo,
										":refrigerio" => $refrigerio,
										":usualta" => $_SESSION["idUsuario"],
										":viaticos" => $viaticos);
		$sql =
			"INSERT INTO emi.icr_conceptoremunerativo
									 (cr_antiguedad, cr_bonus, cr_contrato, cr_fechaalta, cr_id, cr_otros, cr_periodo, cr_premios, cr_presentismo, cr_refrigerio, cr_usualta, cr_viaticos)
						VALUES (:antiguedad, :bonus, :contrato, SYSDATE, -1, :otros, :periodo, :premios, :presentismo, :refrigerio, :usualta, :viaticos)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_POST["esModificacion"] == "T") {
		$params = array(":antiguedad" => $antiguedad,
										":bonus" => $bonus,
										":contrato" => $_SESSION["contrato"],
										":otros" => $otrosConceptos,
										":periodo" => $_POST["periodoProcesado"],
										":premios" => $premios,
										":presentismo" => $presentismo,
										":refrigerio" => $refrigerio,
										":usumodif" => $_SESSION["idUsuario"],
										":viaticos" => $viaticos);
		$sql =
			"UPDATE emi.icr_conceptoremunerativo
					SET cr_antiguedad = :antiguedad,
							cr_bonus = :bonus,
							cr_fechamodif = SYSDATE,
							cr_otros = :otros,
							cr_premios = :premios,
							cr_presentismo = :presentismo,
							cr_refrigerio = :refrigerio,
							cr_usumodif = :usumodif,
							cr_viaticos = :viaticos
				WHERE cr_contrato = :contrato
					AND cr_periodo = :periodo";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	// Actualizo el estado en la tabla de organismos públicos..
	$params = array(":transaccion" => $_POST["idTmp"]);
	$sql =
		"UPDATE emi.iop_organismopublico
				SET op_estado = 1
		  WHERE op_transaccion = :transaccion";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	window.parent.location.href = '/index.php?pageid=46&page=paso3.php&id=<?= $_POST["idTmp"]?>&amw=<?= $_POST["amw"]?>&pp=<?= $_POST["periodoProcesado"]?>';
</script>