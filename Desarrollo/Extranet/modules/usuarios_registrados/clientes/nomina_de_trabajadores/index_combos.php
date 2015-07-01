<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT es_id id, es_nombre || ' (' || art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, NULL) || art.utiles.armar_localidad(es_cpostal, NULL, es_localidad, es_provincia) || ')' detalle
		 FROM aes_establecimiento
		WHERE es_contrato = :contrato
			AND es_fechabaja IS NULL
 ORDER BY 2";
$comboEstablecimiento = new Combo($sql, "establecimiento", $_SESSION["BUSQUEDA_NOMINA_TRABAJADORES"]["establecimiento"]);
$comboEstablecimiento->addParam(":contrato", $_SESSION["contrato"]);
?>