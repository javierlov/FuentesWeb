<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");


if (!isset($_REQUEST["idpadre"]))
	$_REQUEST["idpadre"] = -1;

$list = new ListOfItems("");

$params = array(":idpadre" => $_REQUEST["idpadre"]);
$sql =
	"SELECT de_id, de_idpadre, de_nombre, de_nombrearchivo, de_orden
		 FROM rrhh.rde_descargables
		WHERE de_idpadre = :idpadre
			AND de_fechabaja IS NULL
 ORDER BY de_orden";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt)) {
	$encode = false;
	$link = "/descargables/".$row["DE_ID"];
	$target = "_self";
	if ($row["DE_NOMBREARCHIVO"] != "") {
		$encode = true;
		$link = DATA_DESCARGABLES_PATH.armPathFromNumber($row["DE_ID"]).$row["DE_NOMBREARCHIVO"];
		$target = "_blank";
	}

	$list->addItem(new ItemList($link, $row["DE_NOMBRE"], $target, $encode));
}

$list->setCols(1);
$list->setImagePath("/modules/descargables/images/item.bmp");
$list->setItemsStyle("listaItemsBold");
$list->setShowTitle(false);
$list->draw();

if ($_REQUEST["idpadre"] != -1) {
	$params = array(":id" => $_REQUEST["idpadre"]);
	$sql =
		"SELECT de_idpadre
			 FROM rrhh.rde_descargables
			WHERE de_id = :id";
	$paginaAnterior = valorSql($sql, -1, $params);
	if ($paginaAnterior == -1);
		$paginaAnterior = "";
?>
	<a href="/descargables/<?= $paginaAnterior?>"><input class="btnVolver" type="button" value="" /></a>
<?
}
?>