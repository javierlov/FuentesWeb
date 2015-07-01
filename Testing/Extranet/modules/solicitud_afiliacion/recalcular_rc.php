<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));

$id = substr($_REQUEST["id"], 1);

$params = array(":id" => $id);
$sql =
	"SELECT NVL(co_canttrabajador, sc_canttrabajador) canttrabajador, NVL(co_masasalarial, sc_masasalarial) masasalarial, sc_cuit, sc_idcotizacion, sc_idzonageografica, sc_nrosolicitud, sc_porcdescuento
		 FROM asc_solicitudcotizacion, aco_cotizacion
		WHERE sc_idcotizacion = co_id(+)
			AND sc_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$curs = null;
$params = array(":nrosolicitud" => $row["SC_NROSOLICITUD"]);
$sql = "BEGIN art.cotizacion.get_valor_carta(:nrosolicitud, :data); END;";
$stmt2 = DBExecSP($conn, $curs, $sql, $params);
$rowValorFinal = DBGetSP($curs);

$params = array(":descuento" => 0,
								":id" => $id,
								":n_capitas_cotiz" => $row["CANTTRABAJADOR"],
								":n_masa_cotiz" => $row["MASASALARIAL"],
								":n_porc_var_cotiz" => str_replace(array(".", "%"), array(",", ""), $rowValorFinal["PORCVARIABLE"]),
								":n_sumafija_cotiz" => str_replace(array("$", ",", "."), array("", "", ","), $rowValorFinal["SUMAFIJA"]),
								":nid_cotizacion" => nullIfCero($row["SC_IDCOTIZACION"]),
								":nsc_idzonageografica" => nullIfCero($row["SC_IDZONAGEOGRAFICA"]),
								":s_cuit" => $row["SC_CUIT"],
								":seleccion_sumaaseg" => $_REQUEST["valor"]);
$sql =
	"SELECT TO_CHAR(sc_masasalarial * art.cotizacion.get_valor_rc(:s_cuit, :n_capitas_cotiz, :n_masa_cotiz, :n_porc_var_cotiz, :n_sumafija_cotiz, :nsc_idzonageografica, :nid_cotizacion,
																																:seleccion_sumaaseg, :descuento) / 100, '$9,999,999,990.00') cuotainicialrc,
					art.cotizacion.get_valor_rc(:s_cuit, :n_capitas_cotiz, :n_masa_cotiz, :n_porc_var_cotiz, :n_sumafija_cotiz, :nsc_idzonageografica, :nid_cotizacion, :seleccion_sumaaseg, :descuento) polizarc
		 FROM asc_solicitudcotizacion, aco_cotizacion
		WHERE sc_idcotizacion = co_id(+)
			AND sc_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('polizaRC').value = '<?= $row["POLIZARC"]?>';
		getElementById('cuotaInicialRC').value = '<?= $row["CUOTAINICIALRC"]?>';
	}
</script>