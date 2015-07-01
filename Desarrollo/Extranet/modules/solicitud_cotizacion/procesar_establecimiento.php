<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validar() {
	if ($_POST["provincia"] == -1)
		throw new Exception("Debe seleccionar una provincia.");

	if ($_POST["localidad"] == -1)
		throw new Exception("Debe seleccionar una localidad.");

	if ($_POST["actividad"] == -1)
		throw new Exception("Debe seleccionar una actividad.");

	return true;
}


try {
	setDateFormatOracle("DD/MM/YYYY");

	if (!validar())
		exit;

	if ($_POST["tipoOp"] == "A") {
		$params = array(":idactividad" => nullIfCero($_POST["ciiu"]),
										":idlocalidad" => $_POST["localidad"],
										":idsolicitud" => $_POST["idsolicitud"],
										":idtipoactividad" => nullIfCero($_POST["actividad"]),
										":idzonageografica" => nullIfCero($_POST["provincia"]),
										":trabajadores" => nullIfCero($_POST["trabajadores"]),
										":usualta" => "W_".$_SESSION["usuario"]);
		$sql =
			"INSERT INTO afi.aeu_establecimientos (eu_fechaalta, eu_id, eu_idactividad, eu_idlocalidad, eu_idsolicitud, eu_idtipoactividad, eu_idzonageografica, eu_trabajadores, eu_usualta, eu_usuarioweb)
																		 VALUES (SYSDATE, -1, :idactividad, :idlocalidad, :idsolicitud, :idtipoactividad, :idzonageografica, :trabajadores, :usualta, 'T')";
		DBExecSql($conn, $sql, $params);
	}

	if ($_POST["tipoOp"] == "M") {
		$params = array(":idactividad" => nullIfCero($_POST["ciiu"]),
										":idlocalidad" => nullIfCero($_POST["localidad"]),
										":idtipoactividad" => nullIfCero($_POST["actividad"]),
										":idzonageografica" => nullIfCero($_POST["provincia"]),
										":trabajadores" => nullIfCero($_POST["trabajadores"]),
										":usumodif" => "W_".$_SESSION["usuario"],
										":id" => $_POST["id"]);
		$sql =
			"UPDATE afi.aeu_establecimientos
					SET eu_fechamodif = SYSDATE,
							eu_idactividad = :idactividad,
							eu_idlocalidad = :idlocalidad,
							eu_idtipoactividad = :idtipoactividad,
							eu_idzonageografica = :idzonageografica,
							eu_trabajadores = :trabajadores,
							eu_usumodif = :usumodif
			  WHERE eu_id = :id";
		DBExecSql($conn, $sql, $params);
	}

	if ($_POST["tipoOp"] == "B") {
		$params = array(":usubaja" => "W_".$_SESSION["usuario"], ":id" => $_POST["id"]);
		$sql =
			"UPDATE afi.aeu_establecimientos
					SET eu_fechabaja = SYSDATE,
							eu_usubaja = :usubaja
			  WHERE eu_id = :id";
		DBExecSql($conn, $sql, $params);
	}
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	window.parent.parent.divWin.close();
	window.parent.parent.iframeEstablecimientos.document.location.reload();
</script>