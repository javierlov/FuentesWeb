<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getGerencias() {
	global $conn;

	$result = GetUserIdSectorIntranet();

	$params = array(":id" => $result);
	$sql =
		"SELECT se_idsectorpadre
			 FROM computos.cse_sector
			WHERE se_id = :id";
	$id = ValorSql($sql, 0, $params);
	$result.= ",".$id;

	$params = array(":id" => $id);
	$sql =
		"SELECT se_idsectorpadre
			 FROM computos.cse_sector
			WHERE se_id = :id";
	$id = ValorSql($sql, 0, $params);
	$result.= ",".$id;

	$params = array(":id" => $id);
	$sql =
		"SELECT se_idsectorpadre
			 FROM computos.cse_sector
			WHERE se_id = :id";
	$id = ValorSql($sql, 0, $params);
	$result.= ",".$id;

	return $result;
}

function validar() {
	$errores = false;

	echo "<script>";
	echo "with (window.parent.document) {";

	if ($_POST["cumplimientoPlazos"] == -1) {
		echo "getElementById('cumplimientoPlazos').style.borderColor = '#f00';";
		$errores = true;
	}

	if ($_POST["plazosRespuesta"] == -1) {
		echo "getElementById('plazosRespuesta').style.borderColor = '#f00';";
		$errores = true;
	}

	if ($_POST["adecuacionRespuesta"] == -1) {
		echo "getElementById('adecuacionRespuesta').style.borderColor = '#f00';";
		$errores = true;
	}

	if ($_POST["respuestaAgregaValor"] == -1) {
		echo "getElementById('respuestaAgregaValor').style.borderColor = '#f00';";
		$errores = true;
	}

	if ($_POST["amabilidad"] == -1) {
		echo "getElementById('amabilidad').style.borderColor = '#f00';";
		$errores = true;
	}

	if ($_POST["predisposicion"] == -1) {
		echo "getElementById('predisposicion').style.borderColor = '#f00';";
		$errores = true;
	}

	if ($_POST["comentarios"] == "") {
		echo "getElementById('comentarios').style.borderColor = '#f00';";
		$errores = true;
	}


	if ($errores)
		echo "getElementById('spanMsgError').style.display = 'inline';";
	else
		echo "getElementById('spanMsgError').style.display = 'none';";

	echo "}";
	echo "</script>";

	return !$errores;
}


try {
	if (!validar())
		exit;


	$params = array(":cumplimientoplazos" => $_POST["cumplimientoPlazos"],
									":plazosrespuesta" => $_POST["plazosRespuesta"],
									":adecuacionrespuesta" => $_POST["adecuacionRespuesta"],
									":respuestaagregavalor" => $_POST["respuestaAgregaValor"],
									":amabilidad" => $_POST["amabilidad"],
									":predisposicion" => $_POST["predisposicion"],
									":comentarios" => substr($_POST["comentarios"], 0, 2048),
									":usumodif" => strtoupper(GetWindowsLoginName()),
									":gerenciaevaluadora" => $_POST["gerenciaEvaluadora"],
									":sectorevaluado" => $_POST["sectorEvaluado"]);
	$sql =
		"UPDATE rrhh.rea_encuestaclienteinterno
				SET ea_cumplimientoplazos = :cumplimientoplazos,
						ea_plazosrespuesta = :plazosrespuesta,
						ea_adecuacionrespuesta = :adecuacionrespuesta,
						ea_respuestaagregavalor = :respuestaagregavalor,
						ea_amabilidad = :amabilidad,
						ea_predisposicion = :predisposicion,
						ea_comentarios = :comentarios,
						ea_usumodif = :usumodif,
						ea_fechamodif = SYSDATE
			WHERE ea_gerenciaevaluadora = :gerenciaevaluadora
				AND ea_sectorevaluado = :sectorevaluado";
	DBExecSql($conn, $sql, $params);

	$sql =
		"SELECT 1
			 FROM rrhh.rea_encuestaclienteinterno
			WHERE ea_gerenciaevaluadora IN(".getGerencias().")
				AND ea_usumodif IS NULL";
	$quedanEncuestasSinCompletar = ExisteSql($sql, array());
}
catch (Exception $e) {
?>
<script>
	alert(unescape('<?= rawurlencode($e->getMessage())?>'));
</script>
<?
	exit;
}
?>
<script>
<?
if ($quedanEncuestasSinCompletar) {
?>
	alert('Los datos fueron guardados. Por favor evalue al siguiente sector.');
<?
}
?>
	window.parent.location.href = window.parent.location.href;
</script>