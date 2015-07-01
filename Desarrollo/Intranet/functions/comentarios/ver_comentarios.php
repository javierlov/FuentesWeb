<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="/css/general.css" rel="stylesheet" type="text/css" />
		<link href="/css/style.css" rel="stylesheet" type="text/css" />
		<link href="/functions/comentarios/css/comentarios.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="/js/functions.js"></script>

		<style>
			html, body {background:#fff;}
		</style>
	</head>

	<body onLoad="ajustarIframeComentarios()">
		<div id="divTituloComentarios">Comentarios</div>
<?
$params = array(":idmodulo" => $_REQUEST["idmodulo"], ":idarticulo" => $_REQUEST["idarticulo"]);
$sql =
	"SELECT co_detalle, co_fechabaja, co_id, se_foto, se_id, se_nombre,
					CASE
						WHEN TRUNC(dif * 24 * 60) < 1 THEN 'unos segundos'
						WHEN TRUNC(dif * 24 * 60) = 1 THEN '1 minuto'
						WHEN TRUNC(dif * 24) < 1 THEN TRUNC(dif * 24 * 60) || ' minutos'
						WHEN TRUNC(dif * 24) = 1 THEN '1 hora'
						WHEN TRUNC(dif * 24) < 24 THEN TRUNC(dif * 24) || ' horas'
						WHEN TRUNC(dif) = 1 THEN '1 día'
						WHEN TRUNC(dif) < 31 THEN TRUNC(dif) || ' días'
						WHEN TRUNC(MONTHS_BETWEEN(SYSDATE, co_fechaalta)) = 1 THEN '1 mes'
						WHEN TRUNC(MONTHS_BETWEEN(SYSDATE, co_fechaalta)) < 12 THEN TRUNC(MONTHS_BETWEEN(SYSDATE, co_fechaalta)) || ' meses'
						WHEN TRUNC(MONTHS_BETWEEN(SYSDATE, co_fechaalta) / 12) = 1 THEN '1 año'
						ELSE TRUNC(MONTHS_BETWEEN(SYSDATE, co_fechaalta) / 12) || ' años'
					END tiempo
		 FROM (SELECT DECODE(co_fechabaja, NULL, co_detalle, 'Comentario moderado.') co_detalle, co_fechaalta, co_fechabaja, co_id, se_foto, se_id, se_nombre, (SYSDATE - co_fechaalta) dif
						 FROM rrhh.rco_comentarios, use_usuarios
						WHERE co_usualta = se_usuario
							AND co_idmodulo = :idmodulo
							AND co_idarticulo = :idarticulo)
 ORDER BY co_id DESC";
$stmt = DBExecSql($conn, $sql, $params);

if (DBGetRecordCount($stmt) == 0) {
?>
	<div id="divSinComentarios">Sin comentarios cargados.</div>
<?
}
else {
	$params = array(":usuario" => getWindowsLoginName(true));
	$sql =
		"SELECT 1
			 FROM use_usuarios
			WHERE se_usuario IN ('ALAPACO', 'NPEREIRA', 'SMARZANO', 'VDOMINGUEZ')
				AND UPPER(se_usuario) = :usuario";
	$esAdmin = existeSql($sql, $params);
?>
	<form action="/functions/comentarios/guardar_comentario.php" id="formEliminarComentario" method="post" name="formEliminarComentario" target="iframeProcesando">
		<input id="baja" name="baja" type="hidden" value="s" />
		<input id="id" name="id" type="hidden" value="-1" />
		<input id="url" name="url" type="hidden" value="<?= $_SERVER["REQUEST_URI"]?>" />
	</form>
	<div id="divComentarios">
<?
	while ($row = DBGetQuery($stmt)) {
		$rutaFoto = base64_encode(IMAGES_FOTOS_PATH."cartel.jpg");
		if (is_file(IMAGES_FOTOS_PATH.$row["SE_FOTO"]))
			$rutaFoto = base64_encode(IMAGES_FOTOS_PATH.$row["SE_FOTO"]);
?>
		<div id="divComentario">
			<div id="divComentarioLeft"><img id="imgComentador" src="<?= "/functions/get_image.php?file=".$rutaFoto ?>"></div>
			<div id="divComentarioRight">
				<div>
<?
		if (($esAdmin) and ($row["CO_FECHABAJA"] == "")) {
?>
				<img id="imgEliminarComentario" src="/images/cerrar.png" title="Eliminar comentario" onClick="eliminarComentario(<?= $row["CO_ID"]?>)" />
<?
		}
?>
					<b id="bComentador"><a href="/contacto/<?= $row["SE_ID"]?>" target="_top"><?= $row["SE_NOMBRE"]?></a></b>
					<span class="<?= ($row["CO_FECHABAJA"] == "")?"":"spanComentarioModerado"?>"><?= $row["CO_DETALLE"]?></span>
				</div>
				<div id="divFechaComentario">Hace <?= $row["TIEMPO"]?></div>
			</div>
			<div id="divNada"></div>
		</div>
<?
	}
?>
	</div>
<?
}
?>
	</body>
</html>