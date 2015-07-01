<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


if ($_REQUEST["id"] == 2)
	$sql =
		"SELECT 0 id, 'Capital Federal' detalle
			 FROM DUAL";
else
	$sql =
		"SELECT cp_id id, cp_localidadcap detalle
			 FROM art.ccp_codigopostal
			WHERE cp_fechabaja IS NULL
				AND cp_provincia = :provincia
	 ORDER BY 2";
$comboLocalidad = new Combo($sql, "localidad");
if ($_REQUEST["id"] == 2)
	$comboLocalidad->setAddFirstItem(false);
else
	$comboLocalidad->addParam(":provincia", $idProvincia);
?>