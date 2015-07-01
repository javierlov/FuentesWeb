<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));

try {
	if ($_REQUEST["estado"] == '')
		throw new Exception("Estado incorrecto!");

	$id = substr($_REQUEST["id"], 1);
	$modulo = substr($_REQUEST["id"], 0, 1);
	if ($modulo == "R") {		// Si es una revisión de precio..
		$params = array(":estadosolicitud" => $_REQUEST["estado"], ":usubaja" => $_SESSION["usuario"], ":id" => $id);
		$sql =
			"UPDATE asr_solicitudreafiliacion
					SET sr_fechacancelacion = SYSDATE,
							sr_estadosolicitud = :estadosolicitud,
							sr_usubaja = :usubaja,
							sr_fechabaja = SYSDATE
			  WHERE sr_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		// Actualizo la solicitud de afiliación..
		$params = array(":estado" => $_REQUEST["estado"],
										":usubaja" => $_SESSION["usuario"],
										":idrevisionprecio" => $id);
		$sql =
			"UPDATE asa_solicitudafiliacion
					SET sa_cotizacioncerrada = 'T',
							sa_estado = :estado,
							sa_fechabaja = SYSDATE,
							sa_usubaja = :usubaja
				WHERE sa_idrevisionprecio = :idrevisionprecio";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
	else {		// Sino tiene que ser una solicitud de cotización..
		$params = array(":estado" => $_REQUEST["estado"], ":usubaja" => $_SESSION["usuario"], ":id" => $id);
		$sql =
			"UPDATE asc_solicitudcotizacion
					SET sc_estado = :estado,
							sc_fechabaja = SYSDATE,
							sc_fechacierre = SYSDATE,
							sc_observacionescierre = 'Cotización cerrada desde la web por el usuario ".$_SESSION["usuario"].".',
							sc_usubaja = :usubaja
			  WHERE sc_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		actualizarRankingBNA($id, 0);

		$params = array(":id" => $id);
		$sql =
			"SELECT sc_idcotizacion
				 FROM asc_solicitudcotizacion
				WHERE sc_id = :id";
		$idCotizacion = valorSql($sql, "", $params, 0);
		if ($idCotizacion != "") {
			$params = array(":estado" => $_REQUEST["estado"], ":id" => $idCotizacion);
			$sql =
				"UPDATE aco_cotizacion
						SET co_estado = :estado
				  WHERE co_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}

		// Actualizo la solicitud de afiliación..
		$params = array(":estado" => $_REQUEST["estado"],
										":usubaja" => $_SESSION["usuario"],
										":idsolicitudcotizacion" => $id);
		$sql =
			"UPDATE asa_solicitudafiliacion
					SET sa_cotizacioncerrada = 'T',
							sa_estado = :estado,
							sa_fechabaja = SYSDATE,
							sa_usubaja = :usubaja
				WHERE sa_idsolicitudcotizacion = :idsolicitudcotizacion";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	DBCommit($conn);
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	window.parent.location.href = '/buscar-cotizacion';
</script>