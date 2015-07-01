<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT de_id id, de_nombre detalle, 2 orden
		 FROM rrhh.rde_descargables
		WHERE de_fechabaja IS NULL
			AND de_idpadre = -1
 ORDER BY 3, 2";
$comboItemPadre = new Combo($sql, "itemPadre", ($isAlta)?-1:$row["DE_IDPADRE"]);
$comboItemPadre->setFirstItem("* ITEM RAÍZ *");
$comboItemPadre->setFocus(true);
$comboItemPadre->setOnChange("cambiarItemPadre(this.value)");
?>