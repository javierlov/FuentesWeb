<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function solicitarImagen($cuit) {
	global $conn;

	$params = array(":cuit" => $cuit);
	$sql =
		"SELECT TRUNC(SYSDATE - os_horadevolucionstatus) dias, os_status
			 FROM web.wos_obtenerstatusbcra
			WHERE os_cuit = :cuit";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
	$diasUltimoStatus = $row["DIAS"];

	if ((!isset($_REQUEST["refresh"])) and ($diasUltimoStatus == "0"))
		return $row["OS_STATUS"];

	$params = array(":cuit" => $cuit);
	$sql = "DELETE FROM web.wos_obtenerstatusbcra WHERE os_cuit = :cuit";
	DBExecSql($conn, $sql, $params);

	$params = array(":cuit" => $cuit);
	$sql =
		"INSERT INTO web.wos_obtenerstatusbcra (os_id, os_idestado, os_cuit, os_horapedidoimagen)
																		VALUES (-1, 1, :cuit, SYSDATE)";
	DBExecSql($conn, $sql, $params);

	return -1;
}


$cuit = $_REQUEST["cuit"];
$status = solicitarImagen($cuit);

if ($status == -1) {		// Si es distinto a -1 entonces ya tengo el status..
	$params = array(":cuit" => $cuit);
	$sql =
		"SELECT os_idestado
			 FROM web.wos_obtenerstatusbcra
			WHERE os_cuit = :cuit";
	$imagenObtenida = (ValorSql($sql, "", $params) == 2);

	set_time_limit(40);
	while (!$imagenObtenida) {		// Queda loopeando hasta que se obtenga la imagen o salga por timeout..
		sleep(2);
		$params = array(":cuit" => $cuit);
		$sql =
			"SELECT os_idestado
				 FROM web.wos_obtenerstatusbcra
				WHERE os_cuit = :cuit";
		$imagenObtenida = (ValorSql($sql, "", $params) == 2);
	}

	if (!isset($error))
		$error = "f";
?>
<html>
	<head>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
	</head>
	<body>
		<script type="text/javascript">
			parent.divWin = null;

			function showStatusBcraWin() {
				if ((parent.divWin == null) || (parent.divWin.style.display == 'none')) {
					parent.divWin = parent.dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=384px,height=200px,left=240px,top=240px,resize=1,scrolling=1');
				}
				parent.divWin.load('iframe', 'get_captcha.php?cuit=<?= $_REQUEST["cuit"]?>&error=<?= $error?>', 'Obtener Status BCRA');
				parent.divWin.show();
			}

			showStatusBcraWin();
		</script>
	</body>
</html>
<?
}
else {
?>
<script type="text/javascript">
	window.parent.document.getElementById('statusBcra').value = '<?= $status?>';
</script>
<?
}
?>