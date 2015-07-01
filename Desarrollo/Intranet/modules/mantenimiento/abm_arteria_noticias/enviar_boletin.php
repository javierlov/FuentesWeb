<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function shutdown() {
	echo "<script>alert('El servicio \"ART - Servicio de Envío del Boletín ARTeria Noticias\" probablemente este caído, sea tan amable de informar al sector IT y Comunicaciones (Int. 2929).');</script>";
}

function solicitarStatus($id, $destinatarios, $url) {
	global $conn;

	$params = array(":destinatarios" => $destinatarios, ":idboletin" => $id, ":url" => $url);
	$sql =
		"INSERT INTO tmp.tea_envioboletinarteria (ea_destinatarios, ea_idboletin, ea_fechahorainicio, ea_url)
																			VALUES (:destinatarios, :idboletin, SYSDATE, :url)";
	DBExecSql($conn, $sql, $params);

	$sql = "SELECT MAX(ea_id) FROM tmp.tea_envioboletinarteria";
	return valorSql($sql);
}


try {
	if (!hasPermiso(92))
		throw new Exception("Usted no tiene permiso para enviar el boletín ARTeria Noticias.");

	register_shutdown_function("shutdown");
	$tmpId = solicitarStatus($_POST["id"], $_POST["destinatarios"], "http://".$_SERVER["HTTP_HOST"]."/modules/arteria_noticias/envio.php?id=".$_POST["id"]);

	$params = array(":id" => $tmpId);
	$sql =
		"SELECT ea_generar
			 FROM tmp.tea_envioboletinarteria
			WHERE ea_id = :id";
	$statusOk = (valorSql($sql, "", $params) == "F");

	set_time_limit(40);
	while (!$statusOk) {		// Queda loopeando hasta que se obtenga el status o salga por timeout..
		sleep(2);

		$params = array(":id" => $tmpId);
		$sql =
			"SELECT ea_generar
				 FROM tmp.tea_envioboletinarteria
				WHERE ea_id = :id";
		$statusOk = (valorSql($sql, "", $params) == "F");
	}

	$params = array(":id" => $tmpId);
	$sql =
		"SELECT ea_error
			 FROM tmp.tea_envioboletinarteria
			WHERE ea_id = :id";

	if (valorSql($sql, "", $params) == "T")
		throw new Exception("Ocurrió un error al intentar enviarse el boletín, el boletín NO fue enviado.\\nComuníquese con Desarrollo de Sistemas.");
	else {
		$params = array(":id" => $_POST["id"]);
		$sql =
			"UPDATE rrhh.rba_boletinesarteria
					SET ba_estadoenvio = 'E',
							ba_fechaenvio = SYSDATE
			  WHERE ba_id = :id";
		DBExecSql($conn, $sql, $params);
	}
}
catch (Exception $e) {
?>
	<script>
		parent.document.getElementById('spanEnviando').style.visibility = 'hidden';
		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	</script>
<?
	exit;
}
$sql = "SELECT TO_CHAR(SYSDATE, 'dd/mm/yyyy HH24:MI') FROM DUAL";
?>
<script>
	with (parent.document) {
		getElementById('btnEnviar').style.visibility = 'visible';
		getElementById('fechaUltimoEnvio').value = '<?= ValorSql($sql)?>';
		getElementById('spanEnviando').style.visibility = 'hidden';
	}

	alert('Se ha enviado el boletín a los destinatarios ingresados.');
</script>