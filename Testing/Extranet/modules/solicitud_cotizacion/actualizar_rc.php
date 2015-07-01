<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));

$id = substr($_REQUEST["id"], 1);
$modulo = strtoupper(substr($_REQUEST["id"], 0, 1));

$params = array(":id" => $id,
								":polizarc" => $_REQUEST["sp"],
								":sumaaseguradarc" => $_REQUEST["sa"],
								":valorrc" => str_replace(array(".", "%"), array(",", ""), $_REQUEST["p"]));
$sql =
	"UPDATE asc_solicitudcotizacion
			SET sc_poliza_rc = :polizarc,
					sc_sumaasegurada_rc = :sumaaseguradarc,
					sc_valor_rc = :valorrc
	  WHERE sc_id = :id";
DBExecSql($conn, $sql, $params);
actualizarRankingBNA($id);

$params = array(":id" => $id);
$sql =
	"UPDATE asc_solicitudcotizacion
			SET sc_valor_rc = 0
	  WHERE sc_valor_rc < 0
			AND sc_id = :id";
DBExecSql($conn, $sql, $params);
actualizarRankingBNA($id, 0);

$curs = null;
$params = array(":modulo" => $modulo, ":id" => $id);
$sql = "BEGIN webart.get_solicitud_cotizacion(:data, :modulo, :id); END;";
$stmt = DBExecSP($conn, $curs, $sql, $params);
$row = DBGetSP($curs);
?>
<script type="text/javascript">
	function hideMsg() {
		window.parent.document.getElementById('spanActualizarOk').style.display = 'none';
	}

	with (window.parent.document) {
		getElementById('alicuotaVariableRC').value = '<?= $row["VALORRCFORMATEADO"]?>';
		getElementById('cuotaInicialResultanteRC').value = '<?= $row["CUOTAINICIALRC"]?>';
		getElementById('masaSalarialRC').value = '<?= $row["MASASALARIAL"]?>';

		getElementById('spanActualizarOk').style.display = 'block';
	}

	setTimeout('hideMsg()', 2000);
</script>