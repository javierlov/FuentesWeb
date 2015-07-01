<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function chequear($transaccion) {
	global $conn;

	$params = array(":transaccion" => $transaccion);
	$sql =
		"SELECT COUNT(*)
			 FROM hys.hfg_formulariogenerado
			WHERE fg_transaccion = :transaccion";
	$total = ValorSql($sql, 0, $params);

	$params = array(":transaccion" => $transaccion);
	$sql =
		"SELECT COUNT(*)
			 FROM hys.hfg_formulariogenerado
			WHERE fg_estado = 'P'
				AND fg_transaccion = :transaccion";
	$actual = $total - ValorSql($sql, 0, $params);
?>
	<script type="text/javascript">
		parent.document.getElementById('spanProcesando').innerText = 'Agregando formulario <?= $actual?> de <?= $total?>, aguarde un instante por favor...';
<?
	if ($actual >= $total) {
?>
		alert('Los formularios fueron agregados exitosamente.');
		parent.parent.divWinEmpresa.close();
		parent.parent.location.href = parent.parent.location.href;
<?
	}
	else {
?>
		function recargar() {
			window.location.href = '<?= $_SERVER["PHP_SELF"]?>?chequear=s&t=<?= $transaccion?>';
		}

		setTimeout("recargar()", 5000);
<?
	}
?>
	</script>
<?
}

validarSesion(isset($_SESSION["isPreventor"]));

set_time_limit(300);


if ((isset($_REQUEST["chequear"])) and ($_REQUEST["chequear"] == "s")) {		// Si es true chequeo si se generaron los formularios o no..
	chequear($_REQUEST["t"]);
}

if ((isset($_POST["agregar"])) and ($_POST["agregar"] == "s")) {		// Si es true genero los formularios..
	try {
		$params = array(":usualta" => substr($_SESSION["usuario"], 0, 20));
		$sql =
			"SELECT NVL(MAX(fg_transaccion), 0) + 1
				 FROM hys.hfg_formulariogenerado
				WHERE fg_usualta = :usualta";
		$transaccion = ValorSql($sql, 1, $params);

		foreach($_POST as $nombre => $valor)
			if (substr($nombre, 0, 17) == "idTipoFormulario_") {
				$arr = explode("_", $nombre);

				$params = array(":idempresa" => $arr[1],
												":idformulario" => $arr[3],
												":nroestab" => $arr[2],
												":transaccion" => $transaccion,
												":usualta" => substr($_SESSION["usuario"], 0, 20));
				$sql =
					"INSERT INTO hys.hfg_formulariogenerado
											 (fg_id, fg_idempresa, fg_nroestab, fg_estado, fg_idformulario, fg_usualta, fg_fechaalta, fg_blanco, fg_transaccion)
								VALUES (hys.seq_hfg_id.NEXTVAL, :idempresa, :nroestab, 'P', :idformulario, :usualta, SYSDATE, 'N', :transaccion)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
		DBCommit($conn);
	}
	catch (Exception $e) {
		DBRollback($conn);
?>
		<script type="text/javascript">
			alert(unescape('<?= rawurlencode($e->getMessage())?>'));
			with (parent.document) {
				getElementById('imgProcesando').style.display = 'none';
				getElementById('spanProcesando').style.display = 'none';
				getElementById('btnAgregar').style.visibility = 'visible';
			}
		</script>
<?
		exit;
	}
?>
<script type="text/javascript">
	window.location.href = '<?= $_SERVER["PHP_SELF"]?>?chequear=s&t=<?= $transaccion?>';
</script>
<?
}
?>