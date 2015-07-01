<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


function sendEmail($body, $fromName, $subject, $to, $cc, $bcc, $tipoCuerpo = "T", $tipoRegistroAsociado = NULL, $idRegistroAsociado = -1, $direccionOrigen = NULL) {
	global $conn;

	$rawParamName = "valor";
	$params = array(":direccionesdestino" => implode(",", $to),
									":direccionorigen" => $direccionOrigen,
									":motivo" => $subject,
									":tipocuerpo" => $tipoCuerpo,
									":tiporegistroasociado" => $tipoRegistroAsociado,
									":idregistroasociado" => nullIfCero($idRegistroAsociado));
	$sql =
		"INSERT INTO comunes.cee_emailaenviar (ee_direccionesdestino, ee_direccionorigen, ee_motivo, ee_fechamensaje, ee_tipocuerpo, ee_tiporegistroasociado, ee_idregistroasociado, ee_cuerpoex)
																	 VALUES (:direccionesdestino, :direccionorigen, :motivo, SYSDATE, :tipocuerpo, :tiporegistroasociado, :idregistroasociado, RAWTOHEX(:".$rawParamName."))";
	DBExecSqlRawValue($conn, $sql, $params, $rawParamName, $body);
}
?>