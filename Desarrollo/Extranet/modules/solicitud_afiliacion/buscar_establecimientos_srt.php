<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");


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

function solicitarEstablecimientos($cuit) {
	global $conn;

	$params = array(":cuit" => $cuit);
	$sql =
		"SELECT 1
			 FROM tmp.tes_establecimientossrt
			WHERE es_cuit = :cuit";
	if (!existeSql($sql, $params)) {
		$params = array(":cuit" => $cuit);
		$sql =
			"INSERT INTO tmp.tes_establecimientossrt (es_cuit, es_fechahorainicio)
																				VALUES (:cuit, SYSDATE)";
		DBExecSql($conn, $sql, $params);
	}
	else {
		$params = array(":cuit" => $cuit);
		$sql =
			"UPDATE tmp.tes_establecimientossrt
					SET es_fechahorainicio = SYSDATE,
							es_fechahorafin = NULL,
							es_generar = 'T'
				WHERE es_cuit = :cuit";
		DBExecSql($conn, $sql, $params);
	}
}


register_shutdown_function("shutdownFunction");

$cuit = sacarGuiones($_REQUEST["c"]);
solicitarEstablecimientos($cuit);

$params = array(":cuit" => $cuit);
$sql =
	"SELECT es_generar
		 FROM tmp.tes_establecimientossrt
		WHERE es_cuit = :cuit";
$statusOk = (valorSql($sql, "", $params) == "F");

set_time_limit(90);
while (!$statusOk) {		// Queda loopeando hasta que se obtenga el status o salga por timeout..
	sleep(2);

	$params = array(":cuit" => $cuit);
	$sql =
		"SELECT es_generar
			 FROM tmp.tes_establecimientossrt
			WHERE es_cuit = :cuit";
	$statusOk = (valorSql($sql, "", $params) == "F");
}


// Si llegó acá es porque trajo los establecimientos de la srt, asi que los inserto en la ase..
try {
	$params = array(":cuit" => $cuit);
	$sql =
		"SELECT ac_codigo
			 FROM asc_solicitudcotizacion, cac_actividad
			WHERE sc_idactividad = ac_id
				AND sc_cuit = :cuit
	 ORDER BY sc_id DESC";
	$idActividad = valorSql($sql, "", $params, 0);


	$params = array(":cuit" => $cuit,
									":idsolicitud" => $_REQUEST["idsolicitud"]);
	$sql =
		"SELECT ee_ciiu, es_altura, es_calle, es_codigo, es_cp, es_dpto, es_id, es_idprovincia, es_localidad, es_numeroestablecimiento, es_piso, NVL(es_descripcion, es_codigo) nombre
			 FROM srt.ses_establecimiento, srt.see_establecimientoempresa
			WHERE ee_id = es_idestablecimientoempresa
				AND es_codigo NOT IN (SELECT NVL(se_codigosrt, -1)
																FROM ase_solicitudestablecimiento
															 WHERE se_idsolicitud = :idsolicitud)
				AND ee_cuit = :cuit";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt)) {
		$curs = null;
		$params = array(":dfechafinobra" => null,
										":dfechainicio" => null,
										":ncodigoarea" => null,
										":ncodigoareafax" => null,
										":ncodigosrt" => nullIsEmpty($row["ES_CODIGO"]),
										":nempleados" => 0,
										":nfax" => null,
										":nid" => null,
										":nidsolicitud" => $_REQUEST["idsolicitud"],
										":ninterno" => null,
										":nmasasalarial" => 0,
										":nnumeroestablecimiento" => substr($row["ES_ID"], -4).substr($row["ES_NUMEROESTABLECIMIENTO"], -2),
										":nsuperficie" => null,
										":ntelefono" => null,
										":sactividad" => $idActividad,
										":scalle" => substr($row["ES_CALLE"], 0, 60),
										":scodigopostal" => $row["ES_CP"],
										":sdepartamento" => $row["ES_DPTO"],
										":sidprovincia" => $row["ES_IDPROVINCIA"],
										":slocalidad" => $row["ES_LOCALIDAD"],
										":snombre" => $row["NOMBRE"],
										":snumero" => $row["ES_ALTURA"],
										":sobservaciones" => null,
										":sorigendato" => 10,
										":spiso" => $row["ES_PISO"],
										":stipoestablecimiento" => "P",
										":susumodif" => "W_".$_SESSION["usuario"]);
		$sql ="BEGIN webart.set_establecimiento_afiliacion(TO_DATE(:dfechafinobra, 'dd/mm/yyyy'), TO_DATE(:dfechainicio, 'dd/mm/yyyy'), :ncodigoarea, :ncodigoareafax, :ncodigosrt, :nempleados, :nfax, :nid, :nidsolicitud, :ninterno, :nmasasalarial, :nsuperficie, :ntelefono, :sactividad, :scalle, :scodigopostal, :sdepartamento, :sidprovincia, :slocalidad, :snombre, :snumero, :sobservaciones, :sorigendato, :spiso, :stipoestablecimiento, :susumodif, :nnumeroestablecimiento); END;";
		DBExecSP($conn, $curs, $sql, $params, false, 0);
	}

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}

?>
<script type="text/javascript">
	window.parent.parent.document.getElementById('iframeEstablecimientos').contentWindow.location.reload(true);
</script>