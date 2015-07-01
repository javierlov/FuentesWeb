<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function solicitarStatus($cuit) {
	global $conn;

	$params = array(":codigo" => $_REQUEST["codigo"], ":cuit" => $cuit);
	$sql =
		"UPDATE web.wos_obtenerstatusbcra
				SET os_idestado = 3,
						os_codigo = :codigo,
						os_horapedidostatus = SYSDATE
			WHERE os_cuit = :cuit";
	DBExecSql($conn, $sql, $params);
}


$cuit = $_REQUEST["cuit"];
solicitarStatus($cuit);

$params = array(":cuit" => $cuit);
$sql =
	"SELECT os_idestado
		 FROM web.wos_obtenerstatusbcra
		WHERE os_cuit = :cuit";
$statusObtenido = (ValorSql($sql, "", $params) == 4);

set_time_limit(60);
while (!$statusObtenido) {		// Queda loopeando hasta que se obtenga el status o salga por timeout..
	sleep(2);
	$params = array(":cuit" => $cuit);
	$sql =
		"SELECT os_idestado
			 FROM web.wos_obtenerstatusbcra
			WHERE os_cuit = :cuit";
	$statusObtenido = (ValorSql($sql, "", $params) == 4);
}

$params = array(":cuit" => $cuit);
$sql =
	"SELECT os_status
		 FROM web.wos_obtenerstatusbcra
		WHERE os_cuit = :cuit";
$status = ValorSql($sql, "", $params);

if ($status == -2) {		// Código erróneo..
	$error = "t";
	require_once("import_from_bcra.php");
}
else {
?>
<script type="text/javascript">
	window.parent.document.getElementById('statusBcra').value = '<?= $status?>';
	window.parent.divWin.close();
</script>
<?
}
?>