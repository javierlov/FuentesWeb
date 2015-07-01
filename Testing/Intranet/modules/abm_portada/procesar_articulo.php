<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$params = array(":posicion" => $_REQUEST["Seccion"]);
$sql =
	"SELECT 1
		 FROM tmp.tai_articulosintranet
		WHERE ai_posicion = :posicion";
if (!ExisteSql($sql, $params)) {		// Alta..
	$params = array(":cuerpo" => $_REQUEST["Cuerpo"],
									":destino" => $_REQUEST["Destino"],
									":id" => -1,
									":link" => IIF(($_REQUEST["Link"] == "-1"), NULL, $_REQUEST["Link"]),
									":posicion" => $_REQUEST["Seccion"],
									":rutaimagen" => IIF(($_REQUEST["Imagen"] == "-1"), NULL, $_REQUEST["Imagen"]),
									":titulo" => $_REQUEST["Titulo"],
									":volanta" => $_REQUEST["Volanta"]);
	$sql =
		"INSERT INTO tmp.tai_articulosintranet (ai_id, ai_titulo, ai_volanta, ai_cuerpo, ai_rutaimagen, ai_link, ai_destino, ai_posicion)
																		VALUES (:id, :titulo, :volanta, SUBSTR(:cuerpo, 1, 512), :rutaimagen, :link, :destino, :posicion)";
	DBExecSql($conn, $sql, $params);
}
else {		// Modificación..
	$params = array(":cuerpo" => $_REQUEST["Cuerpo"],
									":destino" => $_REQUEST["Destino"],
									":link" => IIF(($_REQUEST["Link"] == "-1"), NULL, $_REQUEST["Link"]),
									":posicion" => $_REQUEST["Seccion"],
									":rutaimagen" => IIF(($_REQUEST["Imagen"] == "-1"), NULL, $_REQUEST["Imagen"]),
									":titulo" => $_REQUEST["Titulo"],
									":volanta" => $_REQUEST["Volanta"]);
	$sql =
		"UPDATE tmp.tai_articulosintranet
				SET ai_titulo = :titulo,
						ai_volanta = :volanta,
						ai_cuerpo = SUBSTR(:cuerpo, 1, 512),
						ai_rutaimagen = :rutaimagen,
						ai_link = :link,
						ai_destino = :destino
		  WHERE ai_posicion = :posicion";
	DBExecSql($conn, $sql, $params);
}
?>
<script>
<?
if ($dbError["offset"]) {
?>
	alert('<?= $dbError["message"]?>');
<?
}
else {
?>
	window.close();
<?
}
?>
</script>