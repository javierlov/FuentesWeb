<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));

$id = substr($_REQUEST["id"], 1);

$params = array(":id" => $id);
$sql = "SELECT sc_idcotizacion FROM asc_solicitudcotizacion WHERE sc_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$params = array(":descuento" => 0,
								":n_capitas_cotiz" => str_replace(array("%"), array(""), $_REQUEST["capitas"]),
								":n_masa_cotiz" => str_replace(array("%", "NaN"), array("", 0), $_REQUEST["masasalarial"]),
								":n_porc_var_cotiz" => str_replace(array("%"), array(""), $_REQUEST["porcentajevariable"]),
								":n_sumafija_cotiz" => str_replace(array("$", ","), array("", ""), $_REQUEST["costomensual"]),
								":nid_cotizacion" => nullIfCero($row["SC_IDCOTIZACION"]),
								":nsc_idzonageografica" => $_REQUEST["zonageografica"],
								":s_cuit" => $_REQUEST["cuit"],
								":seleccion_sumaaseg" => $_REQUEST["valor"]);
$sql = "SELECT art.cotizacion.get_valor_rc(:s_cuit, :n_capitas_cotiz, :n_masa_cotiz, :n_porc_var_cotiz, :n_sumafija_cotiz, :nsc_idzonageografica, :nid_cotizacion, :seleccion_sumaaseg, :descuento) FROM DUAL";
?>
<script type="text/javascript">
	with (window.parent.document)
		if (getElementById('polizaRC') != undefined)
			getElementById('polizaRC').value = '<?= ValorSql($sql, "", $params)?>';
</script>