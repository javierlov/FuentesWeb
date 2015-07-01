<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once("funciones.php");
?>
<script type="text/javascript" src="/modules/chat/js/chat.js"></script>
<script>
	mensajesNuevos = false;
	ventanaAbierta = (window.parent.document.getElementById('divChatContenido').style.width == '600px');
<?
$actualizarDatosOperador = false;
$chatFinalizado = false;

if (isset($_SESSION["chatIdSession"])) {
	// Chequeo el estado de la sesión..
	$params = array(":id" => $_SESSION["chatIdSession"]);
	$sql =
		"SELECT 1
			 FROM web.wsc_sesioneschat
			WHERE sc_estado = 3
				AND sc_id = :id";
	$actualizarDatosOperador = existeSql($sql, $params);

	// Leo los mensajes..
	$params = array(":idsesion" => $_SESSION["chatIdSession"]);
	$sql =
		"SELECT mc_enviadopor, mc_idarchivo, mc_mensaje, mc_tipomensaje
			 FROM web.wmc_mensajeschat
			WHERE mc_leidoporusuario = 'N'
				AND mc_idsesion = :idsesion
	 ORDER BY mc_id";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt)) {
		if ($row["MC_TIPOMENSAJE"] == "F")
			$chatFinalizado = true;
	?>
		mensajesNuevos = true;
		if (ventanaAbierta) {
			msg = '<?= getMensaje($row)?>';
			escribirMensaje(window.parent, msg);
		}
	<?	
	}

	// Marco los mensajes como leidos..
	$params = array(":idsesion" => $_SESSION["chatIdSession"]);
	$sql =
		"UPDATE web.wmc_mensajeschat
				SET mc_leidoporusuario = 'S'
			WHERE mc_idsesion = :idsesion";
	DBExecSql($conn, $sql, $params);
}

if ($chatFinalizado) {
	unset($_SESSION["chatIdSession"]);
?>
	if (ventanaAbierta)
		with (window.parent.document) {
			getElementById('divAdjuntar').style.display = 'none';
			getElementById('mensaje2').disabled = true;
			getElementById('spanBtnEnviar').onClick = '';
			getElementById('spanBtnEnviar').id = 'spanBtnEnviarDisabled';
		}
<?
}
?>
	if ((mensajesNuevos) && (!ventanaAbierta))
		with (window.parent.document.getElementById('imgBotonChat')) {
			src = '/modules/chat/images/chat_on.png';
			title = 'Tiene mensajes pendientes';
		}
<?
if ($actualizarDatosOperador) {
	$params = array(":id" => isset($_SESSION["chatIdSession"])?$_SESSION["chatIdSession"]:-1);
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
	if (ventanaAbierta)
		with (window.parent.document)
			if (getElementById('divBuscandoOperador').style.display == '') {		// Si se esta mostrando esto faltan actualizar los datos..
				getElementById('divNombreOperador').innerHTML = '<?= $row["NOMBRE"]?>';
				getElementById('divSectorOperador').innerHTML = '>> <?= $row["SECTOR"]?>';
				getElementById('imgOperador').src = '/functions/get_image.php?width=80&file=<?= $rutaFoto?>';

				getElementById('divNombreOperador').style.display = 'block';
				getElementById('divSectorOperador').style.display = 'block';
				getElementById('imgOperador').style.display = 'inline';

				getElementById('divBuscandoOperador').style.display = 'none';
				getElementById('imgCargando').style.display = 'none';
			}
<?
}
?>
	setTimeout("window.location.href = '/modules/chat/cargar_mensaje.php?rnd=<?= date("his")?>';", 2000);
</script>