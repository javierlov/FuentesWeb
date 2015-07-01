<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


function setError($campo, $msg) {
	echo "getElementById('".$campo."').style.backgroundColor = '#f00';";
	echo "getElementById('".$campo."').style.color = '#fff';";
	echo "getElementById('spanError".$campo."').innerHTML = '".$msg."';";

	return true;
}

function validar() {
	global $conn;

	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";

	if ($_POST["nombre"] == "")
		$errores = setError("nombre", "* Debe ingresar un nombre.");

	if ($_POST["email"] == "")
		$errores = setError("email", "* Debe ingresar un e-Mail.");
	else {
		$params = array(":email" => $_POST["email"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S")
			$errores = setError("email", "* Debe ingresar un e-Mail válido.");
	}

	if ($_POST["sector"] == -1)
		$errores = setError("sector", "* Debe seleccionar un sector.");
	else {
/*
		$params = array(":id" => $_POST["sector"]);
		$sql =
			"SELECT 1
				 FROM web.wse_sectoreschat
				WHERE TO_DATE(TO_CHAR(SYSDATE, 'hh24:mi'), 'hh24:mi') BETWEEN TO_DATE(NVL(se_horarioatencioninicio, '00:00'), 'hh24:mi') AND TO_DATE(NVL(se_horarioatencionfin, '23:59'), 'hh24:mi')
					AND se_id = :id";
		if (!existeSql($sql, $params)) {
			$params = array(":id" => $_POST["sector"]);
			$sql =
				"SELECT se_horarioatencionfin, se_horarioatencioninicio, se_nombre
					 FROM web.wse_sectoreschat
					WHERE se_id = :id";
			$stmt = DBExecSql($conn, $sql, $params);
			$row = DBGetQuery($stmt);
			$errores = setError("generico", "* El horario de atención del sector ".$row["SE_NOMBRE"]." es de ".$row["SE_HORARIOATENCIONINICIO"]." hs. a ".$row["SE_HORARIOATENCIONFIN"]." hs.");
		}
		else {
			$params = array(":idsector" => $_POST["sector"]);
			$sql =
				"SELECT 1
					 FROM web.woc_operadoreschat
					WHERE oc_estado = 'A'
						AND oc_fechabaja IS NULL
						AND TO_DATE(TO_CHAR(SYSDATE, 'hh24:mi'), 'hh24:mi') BETWEEN TO_DATE(NVL(oc_horarioatencioninicio, '00:00'), 'hh24:mi') AND TO_DATE(NVL(oc_horarioatencionfin, '23:59'), 'hh24:mi')
						AND oc_idsector = :idsector";
			if (!existeSql($sql, $params))
				$errores = setError("generico", "* En este momento todos los operadores están ocupados, reintente en unos minutos.");
		}
*/
	}

	if ($_POST["sector"] == 1) {		// Patologías Crónicas..
		if ($_POST["dniChat"] == "")
			$errores = setError("dniChat", "* Debe ingresar un D.N.I.");
		else {
			$params = array(":documento" => $_POST["dniChat"]);
			$sql =
				"SELECT 1
					 FROM art.sex_expedientes, ctj_trabajador, art.mgp_gtrabajo
					WHERE ex_idtrabajador = tj_id
						AND ex_gtrabajo = gp_codigo
						AND gp_cronico = 'S'
						AND ex_altamedica IS NULL
						AND NVL(ex_causafin, ' ') NOT IN ('02', '99', '95')
						AND tj_documento = :documento";
			if (!existeSql($sql, $params))
				$errores = setError("dniChat", "* No tenemos registrado ese D.N.I. como un enfermo crónico, por favor comuníquese al 0800-333-1278.");
		}
	}

	if ($_POST["mensaje"] == "")
		$errores = setError("mensaje", "* Debe ingresar un mensaje.");

	echo "}";
	echo "</script>";

	return !$errores;
}


try {
	if (!validar())
		exit;

	$params = array(":conexionsolocongestor" => (($_POST["sector"] == 1)?"S":"N"),
									":dniusuario" => $_POST["dniChat"],
									":emailusuario" => $_POST["email"],
									":idsectoroperador" => $_POST["sector"],
									":ipusuario" => $_SERVER["REMOTE_ADDR"],
									":nombreusuario" => $_POST["nombre"]);
	$sql =
		"INSERT INTO web.wsc_sesioneschat (sc_conexionsolocongestor, sc_dniusuario, sc_emailusuario, sc_fechaconexionusuario, sc_idsectoroperador, sc_ipusuario, sc_nombreusuario)
															 VALUES (:conexionsolocongestor, :dniusuario, :emailusuario, SYSDATE, :idsectoroperador, :ipusuario, :nombreusuario)";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":emailusuario" => $_POST["email"]);
	$sql =
		"SELECT MAX(sc_id)
			 FROM web.wsc_sesioneschat
			WHERE sc_emailusuario = :emailusuario";
	$idSesion = valorSql($sql, -1, $params, 0);
/*
	// Inserto el mensaje standard del operador..
	$sql = "SELECT cc_mensajeinicial FROM web.wcc_constanteschat";
	$msgInicial = valorSql($sql);

	$params = array(":idsesion" => $idSesion,
									":mensaje" => $msgInicial);
	$sql =
		"INSERT INTO web.wmc_mensajeschat (mc_enviadopor, mc_fechaenvio, mc_idsesion, mc_leidoporoperador, mc_leidoporusuario, mc_mensaje)
															 VALUES ('O', SYSDATE, :idsesion, 'N', 'N', :mensaje)";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);
*/
	// Inserto el mensaje del usuario..
	$params = array(":idsesion" => $idSesion,
									":mensaje" => ucfirst($_POST["mensaje"]));
	$sql =
		"INSERT INTO web.wmc_mensajeschat (mc_enviadopor, mc_fechaenvio, mc_idsesion, mc_leidoporoperador, mc_leidoporusuario, mc_mensaje)
															 VALUES ('U', SYSDATE, :idsesion, 'N', 'N', :mensaje)";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	DBCommit($conn);

	$_SESSION["chatIdSession"] = $idSesion;
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script type='text/javascript'>
		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	</script>
<?
	exit;
}
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('iframeChat').src = '/modules/chat/marco.php?rnd=' + Math.random();
		getElementById('iframeChatRecibir').src = '/modules/chat/cargar_mensaje.php?rnd=' + Math.random();
	}
</script>