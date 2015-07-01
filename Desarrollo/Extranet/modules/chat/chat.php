<?
$html.= '<div id="divProvartChica"><img id="imgProvartChica" src="/images/provart_blanco.png" /></div>';
$html.= '<div id="divVentanaChat">';
$html.= 	'<div id="divDatosOperador">';
$html.= 		'<img id="imgOperador" src="" />';
$html.= 		'<div id="divNombreOperador"></div>';
$html.= 		'<div id="divSectorOperador"></div>';
$html.= 		'<img id="imgCargando" src="/modules/chat/images/cargando.gif" />';
$html.= 		'<div id="divBuscandoOperador">Buscando operador, aguarde por favor...</div>';
$html.= 	'</div>';
$html.= 	'<div id="divNada"></div>';
$html.= 	'<div id="divMensajes">';

// Leo los mensajes..
$params = array(":idsesion" => $_SESSION["chatIdSession"]);
$sql =
	"SELECT mc_enviadopor, mc_idarchivo, mc_mensaje, mc_tipomensaje
		 FROM web.wmc_mensajeschat
		WHERE mc_idsesion = :idsesion
 ORDER BY mc_id";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt))
	$html.= getMensaje($row);

// Marco los mensajes como leidos..
$params = array(":idsesion" => $_SESSION["chatIdSession"]);
$sql =
	"UPDATE web.wmc_mensajeschat
			SET mc_leidoporusuario = 'S'
		WHERE mc_idsesion = :idsesion";
DBExecSql($conn, $sql, $params);

$html.= 	'</div>';
$html.= 	'<div id="divEnviarChat">';
$html.= 		'<form action="/modules/chat/enviar_mensaje.php" id="formEnviarMensaje" method="post" name="formEnviarMensaje" target="iframeChatEnviar">';
$html.= 			'<div>';
$html.= 				'<textarea autofocus id="mensaje2" maxlength="255" name="mensaje2" onKeyPress="detectarEnterEnvioMensaje(event)"></textarea>';
$html.= 				'<span id="spanBtnEnviar" onClick="enviarMensaje()"><b>ENVIAR</b></span>';
$html.= 			'</div>';
$html.= 		'</form>';

$html.= 		'<form action="/modules/chat/adjuntar.php" enctype="multipart/form-data" id="formAdjuntarArchivo" method="post" name="formAdjuntarArchivo" target="iframeChat">';
$html.= 			'<div class="divAdjuntar" id="divAdjuntar">';
$html.= 				'<input id="archivoChat" name="archivoChat" type="file" onChange="adjuntarArchivo()" />';
$html.= 				'<img id="imgSubiendoArchivo" src="/modules/chat/images/cargando.gif" />';
$html.= 				'<img id="imgTildeArchivo" src="/modules/chat/images/tilde.png" />';
$html.= 			'</div>';
$html.= 		'</form>';

$html.= 	'</div>';
$html.= '</div>';


// Chequeo el estado de la sesión..
$params = array(":id" => $_SESSION["chatIdSession"]);
$sql =
	"SELECT 1
		 FROM web.wsc_sesioneschat
		WHERE sc_estado = 3
			AND sc_id = :id";
$actualizarDatosOperador = existeSql($sql, $params);

$params = array(":id" => $_SESSION["chatIdSession"]);
$sql =
	"SELECT se_foto, use.se_nombre nombre, wse.se_nombre sector
		 FROM use_usuarios use, web.wsc_sesioneschat, web.wse_sectoreschat wse
		WHERE use.se_id = sc_idoperador
			AND sc_idsectoroperador = wse.se_id
			AND sc_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$rutaFoto = base64_encode(IMAGES_USUARIOS_PATH."cartel.jpg");
if (is_file(IMAGES_USUARIOS_PATH.$row["SE_FOTO"]))
	$rutaFoto = base64_encode(IMAGES_USUARIOS_PATH.$row["SE_FOTO"]);
?>