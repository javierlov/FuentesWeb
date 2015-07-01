<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$params = array(":fase" => $_REQUEST["fase"], ":votado" => $_REQUEST["votado"], ":valor" => $_REQUEST["valor"]);
$sql =
	"SELECT jo_motivo, se_nombre
		 FROM rrhh.rjo_jjoo2012, use_usuarios
		WHERE jo_idvotante = se_id
			AND jo_votado = :votado
			AND jo_valor = :valor
			AND jo_fase = :fase
 ORDER BY NVL(jo_fechamodif, jo_fechaalta)";
$stmt = DBExecSql($conn, $sql, $params);
?>
<html>
	<body style="margin:0; padding:0; background-color:#ddd">
		<iframe id="iframeComentarios" name="iframeComentarios" src="" style="display:none;"></iframe>
		<div style="background-color:#626464; color:#fff; font-family:Neo Sans; font-weight:bold; padding-left:4px;">Comentarios</div>
		<div style="padding-bottom:15px; padding-left:5px; padding-top:7px;">
<?
while ($row = DBGetQuery($stmt)) {
?>
	<div style="color:#00a4e4; font-family:Neo Sans; padding-left:5px; font-size:8pt;">
		<span style="font-weight:bold; text-transform:uppercase;"><span style="color:#807f84; padding-right:8px;">::</span><?= $row["SE_NOMBRE"]?>:</span>
		<br />
		<span style="font-size:8pt; color:#626464; font-style:italic;"><?= $row["JO_MOTIVO"]?></span>
		<br />
		<br />
	</div>
<?
}
?>
		</div>
	</body>
</html>