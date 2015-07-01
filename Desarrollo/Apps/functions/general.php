<?
function actualizarRankingBNA($idSolicitudCotizacion, $commit = 1) {
	global $conn;

	$curs = null;
	$params = array(":id" => $idSolicitudCotizacion);
	$sql = "BEGIN art.afiliacion.do_rankingbna('S', :id); END;";
	DBExecSP($conn, $curs, $sql, $params, false, $commit);
}
?>