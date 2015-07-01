<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT ca_localidad id, ca_localidad detalle
		 FROM (SELECT DISTINCT cpr.ca_localidad
											FROM art.cpr_prestador cpr, art.cpv_provincias
										 WHERE cpr.ca_cartillaweb IN('S', 'A')
											 AND cpr.ca_provincia = pv_codigo
											 AND NVL(cpr.ca_visible, 'S') = 'S'
											 AND cpr.ca_fechabaja IS NULL
											 AND ca_provincia = -1)
 ORDER BY 2";
$comboLocalidad = new Combo($sql, "localidad");
$comboLocalidad->setFirstItem("- TODAS -");

$sql =
	"SELECT pv_codigo id, pv_descripcion detalle
		 FROM (SELECT DISTINCT pv_codigo, pv_descripcion
											FROM art.cpr_prestador cpr, art.cpv_provincias
										 WHERE cpr.ca_cartillaweb IN('S', 'A')
											 AND cpr.ca_provincia = pv_codigo
											 AND NVL(cpr.ca_visible, 'S') = 'S'
											 AND cpr.ca_fechabaja IS NULL)
 ORDER BY 2";
$comboProvincia = new Combo($sql, "provincia");
$comboProvincia->setFirstItem("- TODAS -");
$comboProvincia->setFocus(true);
$comboProvincia->setOnChange("cambiaProvincia(this.value)");

$sql =
	"SELECT tp_codigo id, tp_descripcion detalle
		 FROM (SELECT DISTINCT tp_codigo, tp_descripcion
											FROM art.cpr_prestador cpr, art.mtp_tipoprestador
										 WHERE cpr.ca_cartillaweb IN('S', 'A')
											 AND tp_codigo = cpr.ca_especialidad
											 AND NVL(cpr.ca_visible, 'S') = 'S'
											 AND cpr.ca_fechabaja IS NULL)
 ORDER BY 2";
$comboTipoPrestacion = new Combo($sql, "tipoPrestacion", 0);
$comboTipoPrestacion->setFirstItem("- TODOS -");
?>