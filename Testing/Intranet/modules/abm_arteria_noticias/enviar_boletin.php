<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function shutdown() {
	echo "<script>alert('El servicio \"ART - Servicio de Envío del Boletín ARTeria Noticias\" probablemente este caído, sea tan amable de informar al sector IT y Comunicaciones (Int. 2929).');</script>";
}

function solicitarStatus($id, $destinatarios, $url) {
	global $conn;

	$sql =
		"INSERT INTO tmp.tea_envioboletinarteria (ea_destinatarios, ea_idboletin, ea_fechahorainicio, ea_url)
																 VALUES (:destinatarios, :idboletin, SYSDATE, :url)";
	$params = array(":destinatarios" => $destinatarios, ":idboletin" => $id, ":url" => $url);
	DBExecSql($conn, $sql, $params);

	$sql = "SELECT MAX(ea_id) FROM tmp.tea_envioboletinarteria";
	return ValorSql($sql);
}


try {
	register_shutdown_function("shutdown");
	$tmpId = solicitarStatus($_POST["id"], $_POST["destinatarios"], "http://".$_SERVER["HTTP_HOST"]."/modules/arteria_noticias/envio.php?id=".$_POST["id"]);

	$sql =
		"SELECT ea_generar
			FROM tmp.tea_envioboletinarteria
		 WHERE ea_id = :id";
	$params = array(":id" => $tmpId);
	$statusOk = (ValorSql($sql, "", $params) == "F");

	set_time_limit(40);
	while (!$statusOk) {		// Queda loopeando hasta que se obtenga el status o salga por timeout..
		sleep(2);
		$sql =
			"SELECT ea_generar
				FROM tmp.tea_envioboletinarteria
			 WHERE ea_id = :id";
		$params = array(":id" => $tmpId);
		$statusOk = (ValorSql($sql, "", $params) == "F");
	}

	$sql =
		"SELECT ea_error
			FROM tmp.tea_envioboletinarteria
		 WHERE ea_id = :id";
	$params = array(":id" => $tmpId);

	if (ValorSql($sql, "", $params) == "T")
		throw new Exception("Ocurrió un error al intentar enviarse el boletín, el boletín NO fue enviado.\\nComuníquese con Desarrollo de Sistemas.");
	else {
		$sql =
			"UPDATE rrhh.rba_boletinesarteria
					SET ba_estadoenvio = 'E',
							ba_fechaenvio = SYSDATE
			  WHERE ba_id = :id";
		$params = array(":id" => $_POST["id"]);
		DBExecSql($conn, $sql, $params);
	}
}
catch (Exception $e) {
	echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
$sql = "SELECT TO_CHAR(SYSDATE, 'dd/mm/yyyy HH24:MI') FROM DUAL";
?>
<script>
	parent.document.getElementById('btnGuardar').style.visibility = 'visible';
	parent.document.getElementById('fechaUltimoEnvio').value = '<?= ValorSql($sql)?>';
	parent.document.getElementById('spanEnviando').style.visibility = 'hidden';
	alert('Se ha enviado el boletín a los destinatarios ingresados.');
</script>