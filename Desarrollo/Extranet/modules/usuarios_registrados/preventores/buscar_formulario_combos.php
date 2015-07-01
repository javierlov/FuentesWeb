<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT es_id id, es_nroestableci || ' - ' || es_nombre detalle
		 FROM aes_establecimiento
		WHERE es_fechabaja IS NULL
			AND es_contrato = 0
 ORDER BY 2";
$comboEstablecimiento = new Combo($sql, "establecimiento", $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["idEstablecimiento"]);
$comboEstablecimiento->setOnChange("document.getElementById('idEstablecimiento').value = this.value;");

$sql =
	"SELECT it_id id, it_nombre detalle
		 FROM art.pit_firmantes
		WHERE it_fechabaja IS NULL
 ORDER BY 2";
$comboPreventor = new Combo($sql, "preventor", $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["idPreventor"]);
$comboPreventor->setOnChange("document.getElementById('idPreventor').value = this.value;");

$sql =
	"SELECT pv_codigo id, pv_descripcion detalle
		 FROM cpv_provincias
		WHERE pv_fechabaja IS NULL
 ORDER BY 2";
$comboProvincia = new Combo($sql, "provincia", $_SESSION["BUSQUEDA_IMPRESION_FORMULARIOS"]["idProvincia"]);
$comboProvincia->setOnChange("document.getElementById('idProvincia').value = this.value;");
?>