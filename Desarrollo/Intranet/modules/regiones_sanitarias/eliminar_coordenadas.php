<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


try {
	$params = array(":codigopostal" => $_REQUEST["cp"], ":usubaja" => GetWindowsLoginName(true));
	$sql =
		"UPDATE comunes.cra_coordregionessanitarias
				SET ra_fechabaja = SYSDATE,
						ra_usubaja = :usubaja
			WHERE ra_codigopostal = :codigopostal";
	DBExecSql($conn, $sql, $params);
}
catch (Exception $e) {
?>
	<script>
		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	</script>
<?
	exit;
}
?>