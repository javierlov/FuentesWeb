<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT es_id id, es_nroestableci || ' - ' || art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, es_localidad) || ' - ' || pv_descripcion detalle
		 FROM cpv_provincias, afi.aes_establecimiento
		WHERE es_contrato = :contrato
			AND es_provincia = pv_codigo
			AND es_fechabaja IS NULL
 ORDER BY 2";
$comboEstablecimiento = new Combo($sql, "establecimiento", $establecimiento);
$comboEstablecimiento->addParam(":contrato", $_SESSION["contrato"]);
?>