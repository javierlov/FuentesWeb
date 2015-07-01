<?
function shutDownFunction() {
	global $statusOk;

	if (!$statusOk) {
?>
	<script type="text/javascript">
		with (window.parent.document) {
			getElementById('imgCuitLoading').style.visibility = 'hidden';
			getElementById('statusSrtAutomatico').value = 'F';
			getElementById('statusSrt').disabled = false;
			getElementById('art').disabled = false;
		}
	</script>
<?
	}
}

function solicitarStatus($cuit) {
	global $conn;

	$params = array(":cuit" => $cuit);
	$sql =
		"SELECT 1
			 FROM tmp.tss_statussrt
			WHERE ss_cuit = :cuit";
	if (!ExisteSql($sql, $params)) {
		$params = array(":cuit" => $cuit);
		$sql =
			"INSERT INTO tmp.tss_statussrt (ss_cuit, ss_fechahorainicio)
															VALUES (:cuit, SYSDATE)";
		DBExecSql($conn, $sql, $params);
	}
	else {
		$params = array(":cuit" => $cuit);
		$sql =
			"UPDATE tmp.tss_statussrt
					SET ss_fechahorainicio = SYSDATE,
							ss_fechahorafin = NULL,
							ss_generar = 'T',
							ss_idartanterior = NULL,
							ss_provincia = NULL,
							ss_status = NULL
				WHERE ss_cuit = :cuit";
		DBExecSql($conn, $sql, $params);
	}
}


register_shutdown_function("shutdownFunction");

$cuit = $_REQUEST["cuit"];
solicitarStatus($cuit);

$params = array(":cuit" => $cuit);
$sql =
	"SELECT ss_generar
		 FROM tmp.tss_statussrt
		WHERE ss_cuit = :cuit";
$statusOk = (ValorSql($sql, "", $params) == "F");

set_time_limit(60);
while (!$statusOk) {		// Queda loopeando hasta que se obtenga el status o salga por timeout..
	sleep(2);

	$params = array(":cuit" => $cuit);
	$sql =
		"SELECT ss_generar
			 FROM tmp.tss_statussrt
			WHERE ss_cuit = :cuit";
	$statusOk = (ValorSql($sql, "", $params) == "F");
}

$params = array(":cuit" => $cuit);
$sql =
	"SELECT ss_idartanterior, ss_provincia, ss_status
		 FROM tmp.tss_statussrt
		WHERE ss_cuit = :cuit";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$params = array(":idprovincia" => $row["SS_PROVINCIA"]);
$sql =
	"SELECT zg_id
		 FROM afi.azg_zonasgeograficas
		WHERE zg_idprovincia = :idprovincia";
$idZonaGeografica = ValorSql($sql, "-1", $params);
?>
<script type="text/javascript">
	statusSrt = '<?= $row["SS_STATUS"]?>';
	with (window.parent.document) {
/*
		getElementById('soloPCP').disabled = (statusSrt > 1);
		if (getElementById('soloPCP').disabled) {
			getElementById('soloPCP').checked = false;
			window.parent.mostrarSoloPCP(this.checked);
		}
*/

		getElementById('statusSrtAutomatico').value = 'T';
		getElementById('statusSrt').value = statusSrt;
		getElementById('statusSrtTmp').value = statusSrt;
		getElementById('art').value = '<?= $row["SS_IDARTANTERIOR"]?>';
		getElementById('artTmp').value = '<?= $row["SS_IDARTANTERIOR"]?>';
		getElementById('art').disabled = (getElementById('art').value != 0);

		getElementById('zonaGeografica').value = '<?= $idZonaGeografica?>';

		if (statusSrt != -1) {
			getElementById('statusSrt').disabled = true;
			getElementById('art').disabled = true;
		}

//		if (statusSrt == -1)
//			alert('No se pudo obtener el Status ante la SRT, los datos no serán guardados. \n\nAnte cualquier duda comuníquese con su Ejecutivo de Cuenta.');
	}
</script>