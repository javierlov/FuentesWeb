<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once("funciones.php");


$actualizarDatosOperador = false;

$html = '<div align="left">';
$html.= 	'<div align="right" id="divBotonesArriba">';
$html.= 		'<img id="imgMinimizar" src="/modules/chat/images/minimizar.png" title="Minimizar" onClick="minimizarChat()" />';
$html.= 		'<img id="imgCerrar" src="/modules/chat/images/cerrar.png" title="Cerrar" onClick="cerrarChat()" />';
$html.= 	'</div>';

if (isset($_SESSION["chatIdSession"]))
	require_once("chat.php");
else
	require_once("inicio.php");

$html.= 	'<div id="divCerrarChatFondo"></div>';
$html.= 	'<div id="divCerrarChat">';
$html.= 		'<div id="divConfirmacionSalida">¿ CONFIRMA SU SALIDA DEL CHAT ?</div>';
$html.= 		'<div>';
$html.= 			'<span id="spanBotonSalirSi" onClick="salirChat(true)"><b>SI</b></span><span id="spanBotonSalirNo" onClick="salirChat(false)"><b>NO</b></span>';
$html.= 		'</div>';
$html.= 	'</div>';
$html.= '</div>';
?>
<script type="text/javascript" src="/modules/chat/js/chat.js"></script>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divChatContenido').style.width = '600px';
		getElementById('divChatContenido').innerHTML = '<?= str_replace(array("\t", "\n", "\r", "\0", "\x0B"), " ", $html)?>';
		getElementById('divChatFondo').style.display = 'block';
		getElementById('imgBotonChat').onClick = '';
		getElementById('imgBotonChat').src = '/modules/chat/images/chat_off.png';
		getElementById('imgBotonChat').style.cursor = 'default';
		getElementById('imgBotonChat').title = '';
<?
if ($actualizarDatosOperador) {
?>
	getElementById('divNombreOperador').innerHTML = '<?= $row["NOMBRE"]?>';
	getElementById('divSectorOperador').innerHTML = '>> <?= $row["SECTOR"]?>';
	getElementById('imgOperador').src = '/functions/get_image.php?width=80&file=<?= $rutaFoto?>';

	getElementById('divNombreOperador').style.display = 'block';
	getElementById('divSectorOperador').style.display = 'block';
	getElementById('imgOperador').style.display = 'inline';

	getElementById('divBuscandoOperador').style.display = 'none';
	getElementById('imgCargando').style.display = 'none';
<?
}
?>
		if (getElementById('divMensajes') != null)
			escribirMensaje(window.parent, '');
<?
if (isset($_SESSION["chatIdSession"])) {
?>
		getElementById('iframeChatRecibir').src = '/modules/chat/cargar_mensaje.php?rnd=' + Math.random();
<?
}
?>
		if (getElementById('nombre') != null)
			getElementById('nombre').focus();
	}
</script>