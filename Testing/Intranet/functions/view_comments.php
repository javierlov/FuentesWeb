<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$params = array(":idmodulo" => $_REQUEST["idmodulo"], ":idarticulo" => $_REQUEST["idarticulo"]);
$sql =
	"SELECT co_detalle, co_id, se_nombre
		 FROM rrhh.rco_comentarios, use_usuarios
		WHERE co_usualta = se_usuario
			AND co_fechabaja IS NULL
			AND co_idmodulo = :idmodulo
			AND co_idarticulo = :idarticulo
 ORDER BY co_id DESC";
$stmt = DBExecSql($conn, $sql, $params);

if (DBGetRecordCount($stmt) == 0) {
?>
	<script>
		parent.divWin.close();
		alert('Este artículo aún no tiene comentarios.');
	</script>
<?
}

$params = array(":usuario" => GetWindowsLoginName());
$sql =
	"SELECT 1
		 FROM use_usuarios
		WHERE se_usuario IN ('ALAPACO', 'AANGIOLILLO', 'JSANTORO', 'NPEREIRA')
			AND UPPER(se_usuario) = UPPER(:usuario)";
$esAdmin = ExisteSql($sql, $params);
?>
<html>
	<head>
		<script>
<?
if ($esAdmin) {
?>
			function eliminarComentario(id) {
				if (confirm('¿ Realmente desea eliminar este comentario ?'))
					document.getElementById('iframeComentarios').src = '/functions/save_comment.php?baja=s&id=' + id;
			}
<?
}
?>
		</script>
	</head>
	<body style="margin:0; padding:0; background-color:#ddd">
		<iframe id="iframeComentarios" name="iframeComentarios" src="" style="display:none;"></iframe>
		<div style="background-color:#626464; color:#fff; font-family:Neo Sans; font-weight:bold; padding-left:4px;">Comentarios</div>
		<div style="padding-bottom:15px; padding-left:5px; padding-top:7px;">
<?
while ($row = DBGetQuery($stmt)) {
?>
	<div id="divComentario<?= $row["CO_ID"]?>" style="color:#00a4e4; font-family:Neo Sans; padding-left:5px; font-size:8pt;">
		<span style="font-weight:bold; text-transform:uppercase;"><span style="color:#807f84; padding-right:8px;">::</span><?= $row["SE_NOMBRE"]?>:</span>
		<img border="0" src="/images/delete16.png" style="cursor:hand; display:<?= ($esAdmin)?"inline":"none"?>; vertical-align:-4px;" title="Eliminar comentario" onClick="eliminarComentario(<?= $row["CO_ID"]?>)" onMouseOut="document.getElementById('divComentario<?= $row["CO_ID"]?>').style.backgroundColor = '#ddd';" onMouseOver="document.getElementById('divComentario<?= $row["CO_ID"]?>').style.backgroundColor = '#ffffff';" />
		<br />
		<span style="font-size:8pt; color:#626464; font-style:italic;"><?= $row["CO_DETALLE"]?></span>
		<br />
		<br />
	</div>
<?
}
?>
		</div>
	</body>
</html>