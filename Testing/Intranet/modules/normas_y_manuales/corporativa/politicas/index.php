<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/corporativa/politicas/", ":: Políticas");
$list->addItem(new ItemList("po-001_tipo_de_normativa.pdf", "Normativa: Tipo, Alcance y Ámbito de Aplicación", "_blank", true));
$list->addItem(new ItemList("politica_de_inversiones.pdf", "Inversiones", "_blank", true));
$list->addItem(new ItemList("PO-004_Politica_de_Suscripcion.pdf", "Política de Suscripción", "_blank", true));
$list->addItem(new ItemList("PO-005_Politica_de_Calidad.pdf", "Política de Calidad", "_blank", true));
$list->setCols(1);
$list->setColsWidth(500);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=corporativa/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>