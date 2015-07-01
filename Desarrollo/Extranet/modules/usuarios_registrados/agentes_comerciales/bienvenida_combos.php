<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT DISTINCT ca_id id, ca_codigo || ' - ' || ca_descripcion detalle
							FROM aca_canal
						 WHERE ca_fechabaja IS NULL
					ORDER BY 2";
$comboCanal = new Combo($sql, "canal", $_SESSION["canal"]);
$comboCanal->setFocus(true);
$comboCanal->setOnChange("cambiaCanal(this.value)");

$sql =
	"SELECT DISTINCT en_id id, en_codbanco || ' - ' || en_nombre detalle
							FROM xen_entidad
						 WHERE en_idcanal = :idcanal
							 AND en_fechabaja IS NULL
					ORDER BY 2";
$comboEntidad = new Combo($sql, "entidad", $_SESSION["entidad"]);
$comboEntidad->addParam(":idcanal", $_SESSION["canal"]);
$comboEntidad->setOnChange("cambiaEntidad(this.value)");

$sql =
	"SELECT su_id id, su_codsucursal || ' - ' || su_descripcion detalle
		 FROM asu_sucursal
		WHERE su_fechabaja IS NULL
			AND su_identidad = :identidad
 ORDER BY 2";
$comboSucursal = new Combo($sql, "sucursal", $_SESSION["sucursal"]);
$comboSucursal->addParam(":identidad", $_SESSION["entidad"]);

$sql =
	"SELECT ve_id id, ve_vendedor || ' - ' || ve_nombre detalle
		 FROM xve_vendedor, xev_entidadvendedor
		WHERE ev_idvendedor = ve_id
			AND ve_fechabaja IS NULL
			AND ev_fechabaja IS NULL
			AND ev_identidad = :identidad 
 ORDER BY 2";
$comboVendedor = new Combo($sql, "vendedor", $_SESSION["vendedor"]);
$comboVendedor->addParam(":identidad", $_SESSION["entidad"]);
?>