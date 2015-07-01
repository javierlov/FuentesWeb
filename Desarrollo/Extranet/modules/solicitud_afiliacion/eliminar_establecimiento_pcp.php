<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));

try {
	$params = array(":usubaja" => "W_".$_SESSION["usuario"], ":id" => $_REQUEST["id"]);
	$sql =
		"UPDATE afi.alt_lugartrabajo_pcp
				SET lt_fechabaja = SYSDATE,
						lt_usubaja = :usubaja
			WHERE lt_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function redirect() {
		window.parent.parent.document.getElementById('iframeEstablecimientos').contentWindow.location.reload(true);
		window.parent.parent.divWin.close();
	}

	setTimeout('redirect()', 1500);
	window.parent.document.getElementById('borradoOk').style.display = 'block';
</script>