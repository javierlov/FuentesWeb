<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


$sql =
	"SELECT bc_nombrearchivo
		FROM rrhh.rbc_busquedascorporativas
	 WHERE bc_id = :id";
$params = array(":id" => $_REQUEST["id"]);
$fileTitle = addslashes(ValorSql($sql, "", $params));
$partesFile = pathinfo($fileTitle);
$file = base64_encode(DATA_BUSQUEDAS_CORPORATIVAS_PATH.$_REQUEST["id"].".".$partesFile["extension"]);
echo DATA_BUSQUEDAS_CORPORATIVAS_PATH.$_REQUEST["id"].".".$partesFile["extension"];
?>
<script>
	history.back();
	win = window.open('<?= "/functions/get_file.php?fl=".$file."&ft=".$fileTitle?>', 'intranetWindowx');
	win.location.href = '<?= "/functions/get_file.php?fl=".$file."&ft=".$fileTitle?>';
</script>