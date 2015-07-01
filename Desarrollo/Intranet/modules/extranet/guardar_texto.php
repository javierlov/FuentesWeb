<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


try {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT ax_posicion
			 FROM web.wax_articulosextranetedicion
			WHERE ax_id = :id";
	$campo = $_REQUEST["tipo"].valorSql($sql, "", $params);

	$sql = "UPDATE web.wax_articulosextranetedicion";
	switch ($_REQUEST["tipo"]) {
		case 'c':
			$sql.= " SET ax_cuerpo = :texto";
			break;
		case 't':
			$sql.= " SET ax_titulo = :texto";
			break;
		case 'v':
			$sql.= " SET ax_volanta = :texto";
			break;
	}

	// El usuario lo agrego para que no pueda entrar cualquiera a modificar cualquier cosa..
	$params = array(":id" => $_REQUEST["id"], ":texto" => $_REQUEST["texto"], ":usuario" => getWindowsLoginName(true));
	$sql.= " WHERE ax_usuario = :usuario AND ax_id = :id";
	DBExecSql($conn, $sql, $params);

	if ($_REQUEST["tipo"] == "c")
		$_REQUEST["texto"].= "[+]";
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function finalizar() {
		with (window.parent.document) {
			getElementById('btnGuardar').style.display = 'inline';
			getElementById('divFondo').style.display = 'none';
			getElementById('divCampo').style.display = 'none';
			getElementById('imgCampoOk').style.display = 'none';
		}
	}

	setTimeout('finalizar()', 2000);

	with (window.parent.document) {
		getElementById('<?= $campo?>').style.backgroundColor = '';
		getElementById('<?= $campo?>').innerText = '<?= $_REQUEST["texto"]?>';
		getElementById('imgGuardandoCampo').style.display = 'none';
		getElementById('imgCampoOk').style.display = 'inline';
	}
</script>