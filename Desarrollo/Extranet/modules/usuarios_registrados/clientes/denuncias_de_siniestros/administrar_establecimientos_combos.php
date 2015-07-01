<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT et_id id, et_nombre || ' (' || art.utiles.armar_domicilio(et_calle, et_numero, et_piso, et_departamento, NULL) || art.utiles.armar_localidad(et_cpostal, NULL, et_localidad, et_provincia) || ')' detalle
		 FROM SIN.set_establecimiento_temporal
		WHERE et_fechabaja IS NULL
			AND et_cuit = :cuit
 ORDER BY 2";
$comboEstablecimientoTercero = new Combo($sql, "establecimientoTercero");
$comboEstablecimientoTercero->addParam(":cuit", $_SESSION["cuit"]);

$sql =
	"SELECT DISTINCT pv_codigo id, pv_descripcion detalle
							FROM cpv_provincias
					ORDER BY 2";
$comboProvincia = new Combo($sql, "provincia", $provincia);
?>