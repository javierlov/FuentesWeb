<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT '_self' id, 'Misma ventana' detalle
		 FROM DUAL
UNION ALL
	 SELECT '_blank', 'Ventana nueva'
		 FROM DUAL";
$comboDestino = new Combo($sql, "destino", ($isAlta)?-1:$row["MI_TARGET"]);

$sql =
	"SELECT mi_id id, mi_texto detalle, 2 orden
		 FROM web.wmi_menuintranet
		WHERE mi_fechabaja IS NULL
			AND mi_idpadre = -1
 ORDER BY 3, 2";
$comboMenuPadre = new Combo($sql, "menuPadre", ($isAlta)?-1:$row["MI_IDPADRE"]);
$comboMenuPadre->setFirstItem("* ES UN ITEM PADRE *");
$comboMenuPadre->setFocus(true);
?>