<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT tt_id id, tt_descripcion detalle
		 FROM att_tipotelefono
 ORDER BY 2";
$comboTipoTelefono = new Combo($sql, "tipoTelefono", (!$isAlta)?$row["MP_IDTIPOTELEFONO"]:-1);
$comboTipoTelefono->setFocus(true);
$comboTipoTelefono->setOnChange("setearNumero()");
?>