<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
?>
<html>
	<head>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<link href="/modules/arteria_noticias/css/style.css" rel="stylesheet" type="text/css" />
		<script src="/modules/mantenimiento/abm_arteria_noticias/js/posiciones.js" type="text/javascript"></script>
	</head>
	<body onLoad="carga()">
		<form action="/modules/mantenimiento/abm_arteria_noticias/guardar_posiciones.php" id="formPosiciones" method="post" name="formPosiciones">
<?
for ($i=1; $i<=8; $i++) {
	$params = array(":idboletin" => $_REQUEST["id"], ":posicion" => $i);
	$sql =
		"SELECT na_id, na_titulo
			 FROM rrhh.rna_noticiasarteria
			WHERE na_idboletin = :idboletin
				AND na_posicion = :posicion";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
?>
	<div style="position:absolute;">
		<input id="posicion<?= $i?>" name="posicion<?= $i?>" type="hidden" value="<?= $i?>" />
		<input id="idNoticia<?= $i?>" name="idNoticia<?= $i?>" type="hidden" value="<?= $row["NA_ID"]?>" />
		<span id="destino<?= $i?>" style="background-color:#00A4E4; color: #ffffff; cursor:default; left:12px; position:absolute; top:<?= (-14 + ($i * 26))?>px; width:120px;">&nbsp;Noticia <?= $i?>&nbsp;</span>
		<span id="titulo<?= $i?>" style="cursor:move; left:132px; position:absolute; top:<?= (-14 + ($i * 26))?>px; width:400px;" onMouseDown="comienzoMovimiento(event, this.id);"><?= $row["NA_TITULO"]?></span>
	</div>
<?
}
?>
			<input id="btnGuardar" name="btnGuardar" style="left:310px; position:absolute; top:230px;" type="submit" value="Guardar" />
		</form>
<!-- <span id="data" style="left:240px; position:absolute;">data</span>-->
	</body>
</html>