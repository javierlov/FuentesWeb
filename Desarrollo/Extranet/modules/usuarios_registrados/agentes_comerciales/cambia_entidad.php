<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT su_id id, su_codsucursal || ' - ' || su_descripcion detalle
		 FROM asu_sucursal
		WHERE su_fechabaja IS NULL
			AND su_identidad = :identidad
 ORDER BY 2";
$comboSucursal = new Combo($sql, "sucursal");
$comboSucursal->addParam(":identidad", $_REQUEST["identidad"]);

$sql =
	"SELECT ve_id id, ve_vendedor || ' - ' || ve_nombre detalle
		 FROM xve_vendedor, xev_entidadvendedor
		WHERE ev_idvendedor = ve_id
			AND ve_fechabaja IS NULL
			AND ev_fechabaja IS NULL
			AND ev_identidad = :identidad 
 ORDER BY 2";
$comboVendedor = new Combo($sql, "vendedor");
$comboVendedor->addParam(":identidad", $_REQUEST["identidad"]);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('sucursal').parentNode.innerHTML = '<?= $comboSucursal->draw();?>';
		getElementById('vendedor').parentNode.innerHTML = '<?= $comboVendedor->draw();?>';
	}
</script>