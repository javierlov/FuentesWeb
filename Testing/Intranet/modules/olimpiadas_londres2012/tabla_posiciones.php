<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$params = array(":valor" => $_REQUEST["v"]);
$sql =
	"SELECT jo_votado, se_nombre usuario, COUNT(*) votos
		 FROM rrhh.rjo_jjoo2012, use_usuarios
		WHERE jo_votado = se_id
			AND jo_valor = :valor
			AND jo_fase = 1
			AND se_fechabaja IS NULL
			AND jo_fechabaja IS NULL
 GROUP BY jo_votado, se_nombre
 ORDER BY votos DESC, usuario";
$stmt = DBExecSql($conn, $sql, $params);
?>
<html>
	<head>
		<link rel="stylesheet" href="style/style.css" type="text/css">
		<link rel="stylesheet" href="style/tabla.css" type="text/css">
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	</head>
	<body style="border:0;">
		<table cellpadding="0" cellspacing="2" width="500">
			<tr><td class="header1">USUARIO</td><td class="header2">VOTOS</td><td></td></tr>
<?
while ($row = DBGetQuery($stmt)) {
?>
			<tr><td class="data1"><?= $row["USUARIO"]?></td><td class="data2"><?= $row["VOTOS"]?></td><td><img alt="Ver Comentarios" border="0" src="images/comentarios.jpg" style="cursor:hand;" onClick="window.parent.verComentarios('<?= $row["JO_VOTADO"]?>', '<?= $_REQUEST["v"]?>')" /></td></tr>
<?
}
?>
		</table>
	</body>
</html>