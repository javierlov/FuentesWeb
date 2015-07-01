<?
function getClassMensaje($enviadoPor, $tipoMensaje) {
	if ($tipoMensaje == "A")		// Archivo..
		return "divMsgArchivo";
	elseif ($tipoMensaje == "F")		// Finalización de la conexión..
		return "divMsgFinalzacion";
	elseif ($enviadoPor == "O")		// Operador..
		return "divMsgOperador";
	else		// Usuario..
		return "divMsgUsuario";
}

function getMensaje($row) {
	$className = getClassMensaje($row["MC_ENVIADOPOR"], $row["MC_TIPOMENSAJE"]);

	if ($row["MC_TIPOMENSAJE"] == "A")		// Es un archivo..
		$mensaje = getMensajeArchivo($row["MC_IDARCHIVO"]);
	if ($row["MC_TIPOMENSAJE"] == "C")		// Es un mensaje común..
		$mensaje = $row["MC_MENSAJE"];
	if ($row["MC_TIPOMENSAJE"] == "F")		// Es un mensaje de finalización de la conexión..
		$mensaje = $row["MC_MENSAJE"];

	return '<div class="'.$className.'">'.str_replace(array("\t", "\n", "\r", "\0", "\x0B"), " ", $mensaje).'</div>';
}

function getMensajeArchivo($idArchivo) {
	global $conn;

	$sql = "SELECT cc_mensajearchivo FROM web.wcc_constanteschat";
	$msgArchivo = htmlspecialchars_decode(valorSql($sql), ENT_QUOTES);

	$params = array(":id" => $idArchivo);
	$sql =
		"SELECT ac_nombreoriginal
			 FROM web.wac_archivoschat
			WHERE ac_id = :id";
	$result = str_replace("@FILE@", valorSql($sql, "", $params), $msgArchivo);
	$result = str_replace("@LINK@", "/modules/chat/ver_adjunto.php?id=".$idArchivo, $result);

	return $result;
}
?>