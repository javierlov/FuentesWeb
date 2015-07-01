<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems("");

$params = array();
$sql =
	"SELECT ai_id, ai_titulo
		 FROM web.wai_articulosintranet
		WHERE art.actualdate NOT BETWEEN TRUNC(ai_fechavigenciadesde) AND ai_fechavigenciahasta
			AND ai_fechavigenciahasta > SYSDATE - 150
			AND ai_fechabaja IS NULL
 ORDER BY ai_fechavigenciadesde DESC, ai_fechavigenciahasta DESC, ai_fechaalta DESC";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt))
	$list->addItem(new ItemList("/articulos/".$row["AI_ID"], $row["AI_TITULO"], "_self", false, true));

$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/historico_articulos/images/item.bmp");
$list->setShowTitle(false);
$list->draw();
?>