<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 66));

try {
	$curs = null;
	$params = array(":nid" => $_REQUEST["id"], ":susubaja" => $_SESSION["usuario"]);
	$sql ="BEGIN webart.set_baja_usuario_cliente(:nid, :susubaja); END;";
	DBExecSP($conn, $curs, $sql, $params, false);
}
catch (Exception $e) {
	echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script>
	function redirect() {
		window.parent.location.href = '/administracion-usuarios-2';
	}

	setTimeout('redirect()', 2000);
	window.parent.document.getElementById('borradoOk').style.display = 'block';
</script>