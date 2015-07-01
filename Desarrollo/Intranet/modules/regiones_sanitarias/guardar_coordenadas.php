<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


try {
	$params = array(":codigopostal" => $_REQUEST["cp"]);
	$sql =
		"SELECT ra_id
			 FROM comunes.cra_coordregionessanitarias
			WHERE ra_codigopostal = :codigopostal";

	if (!ExisteSql($sql, $params)) {
		$params = array(":codigopostal" => $_REQUEST["cp"],
										":coordenadax" => $_REQUEST["x"],
										":coordenaday" => $_REQUEST["y"],
										":region" => -1,
										":usualta" => GetWindowsLoginName(true));
		$sql =
			"INSERT INTO comunes.cra_coordregionessanitarias
									 (ra_codigopostal, ra_coordenadax, ra_coordenaday, ra_fechaalta, ra_region, ra_usualta)
						VALUES (:codigopostal, :coordenadax, :coordenaday, SYSDATE, :region, :usualta)";
	}
	else {
		$params = array(":codigopostal" => $_REQUEST["cp"],
										":coordenadax" => $_REQUEST["x"],
										":coordenaday" => $_REQUEST["y"],
										":usumodif" => GetWindowsLoginName(true));
		$sql =
			"UPDATE comunes.cra_coordregionessanitarias
					SET ra_coordenadax = :coordenadax,
							ra_coordenaday = :coordenaday,
							ra_fechabaja = NULL,
							ra_fechamodif = SYSDATE,
							ra_usubaja = NULL,
							ra_usumodif = :usumodif
				WHERE ra_codigopostal = :codigopostal";
	}

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