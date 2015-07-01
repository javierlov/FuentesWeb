<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT ta_id id, ta_detalle detalle
		 FROM afi.ata_tipoactividad
		WHERE ta_fechabaja IS NULL
 ORDER BY 2";
$comboActividad = new Combo($sql, "actividad", ($_REQUEST["id"] == -1)?-1:$row["EU_IDTIPOACTIVIDAD"]);

$sql =
	"SELECT ac_id id, ac_codigo || ' - ' || UPPER(ac_descripcion) detalle
		 FROM cac_actividad
		WHERE LENGTH(ac_codigo) = 6
 ORDER BY 2";
$comboCiiu = new Combo($sql, "ciiu", ($_REQUEST["id"] == -1)?-1:$row["EU_IDACTIVIDAD"]);

if ($row["EU_IDZONAGEOGRAFICA"] == 2) {
	$sql =
		"SELECT 0 id, 'Capital Federal' detalle
			 FROM DUAL";
	$comboLocalidad = new Combo($sql, "localidad", ($_REQUEST["id"] == -1)?-1:$row["EU_IDLOCALIDAD"]);
	$comboLocalidad->setAddFirstItem(false);
}
else {
	$sql =
		"SELECT cp_id id, cp_localidadcap detalle
			 FROM art.ccp_codigopostal
			WHERE cp_fechabaja IS NULL
				AND cp_provincia = :provincia
	 ORDER BY 2";
	$comboLocalidad = new Combo($sql, "localidad", ($_REQUEST["id"] == -1)?-1:$row["EU_IDLOCALIDAD"]);
	$comboLocalidad->addParam(":provincia", nullIsEmpty($idProvincia));
}

$sql =
	"SELECT zg_id id, zg_descripcion detalle
		 FROM afi.azg_zonasgeograficas
		WHERE zg_fechabaja IS NULL
 ORDER BY 2";
$comboProvincia = new Combo($sql, "provincia", ($_REQUEST["id"] == -1)?-1:$row["EU_IDZONAGEOGRAFICA"]);
$comboProvincia->setFocus(true);
$comboProvincia->setOnChange("cambiaProvincia(this.value)");
?>