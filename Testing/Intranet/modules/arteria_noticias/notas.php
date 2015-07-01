<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
		<title>..:: ARTeria Noticias ::..</title>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
	</head>
	<body>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td class="TituloBannerNotas">EN ESTE N�MERO:</td></tr>
			<tr>
				<td class="BannerNotas">
<?
if ($_REQUEST["id"] != "") {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT na_idboletin
			 FROM rrhh.rna_noticiasarteria
			WHERE na_id = :id";
	$idBoletin = ValorSql($sql, "", $params);

	$params = array(":idboletin" => $idBoletin, ":id" => $_REQUEST["id"]);
	$sql =
		"SELECT na_posicion, na_titulo
			 FROM rrhh.rna_noticiasarteria
			WHERE na_idboletin = :idboletin
				AND na_id <> :id
				AND na_fechabaja IS NULL
	 ORDER BY na_posicion";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt)) {
		if ($_REQUEST["modo"] == "e")
			$href = "";
		else
			$href = "href='/modules/arteria_noticias/noticia.php?b=".$idBoletin."&n=".$row["NA_POSICION"]."'";
?>
					<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
					<p style="margin-top: 0; margin-bottom: 0"><a <?= $href?> target="_parent"><?= $row["NA_TITULO"]?></a></p>
<?
	}
}
?>
				</td>
			</tr>
		</table>
	</body>
</html>