<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


function getNombreModulo($idmodulo) {
	global $conn;

	$params = array(":idmodulo" => $idmodulo);
	$sql =
		"SELECT pi_titulo
			 FROM web.wpi_paginasintranet
			WHERE pi_id = :idmodulo";
	return ValorSql($sql, "", $params);
}


try {
	SetDateFormatOracle("DD/MM/YYYY");

	if (isset($_REQUEST["baja"])) {
		$params = array(":id" => $_REQUEST["id"],
										":usubaja" => GetWindowsLoginName());
		$sql =
			"UPDATE rrhh.rco_comentarios
					SET co_fechabaja = SYSDATE,
							co_usubaja = UPPER(:usubaja)
				WHERE co_id = :id";
		DBExecSql($conn, $sql, $params);
?>
		<script>
			window.parent.document.getElementById('divComentario<?= $_REQUEST["id"]?>').style.display = 'none';
		</script>
<?
		exit;
	}

	// Valido que no se pueda guardar un mensaje para el mismo artículo por el mismo usuario con menos de 20 segundos
	// de diferencia con el mensaje anterior..
	$params = array(":idarticulo" => $_REQUEST["idarticulo"],
									":idmodulo" => $_REQUEST["idmodulo"],
									":usualta" => GetWindowsLoginName());
	$sql =
		"SELECT 1
			 FROM rrhh.rco_comentarios
			WHERE co_idmodulo = :idmodulo
				AND co_idarticulo = :idarticulo
				AND co_usualta = :usualta
				AND SYSDATE > co_fechaalta - 0.00023";		// 20 segundos..
	if (ExisteSql($sql, $params))
		throw new Exception("Debe esperar 20 segundos para agregar un nuevo comentario.");


	$params = array(":detalle" => substr($_REQUEST["comentario"], 0, 1024),
									":idarticulo" => $_REQUEST["idarticulo"],
									":idmodulo" => $_REQUEST["idmodulo"],
									":usualta" => GetWindowsLoginName());
	$sql =
		"INSERT INTO rrhh.rco_comentarios (co_detalle, co_fechaalta, co_id, co_idarticulo, co_idmodulo, co_usualta)
															 VALUES (:detalle, SYSDATE, -1, :idarticulo, :idmodulo, UPPER(:usualta))";
	DBExecSql($conn, $sql, $params);

	// Envío un aviso a RRHH..
	$params = array(":id" => $_REQUEST["idarticulo"]);
	$sql =
		"SELECT np_titulo, se_nombre
			 FROM rrhh.rnp_novedadespersonales, rrhh.rco_comentarios, use_usuarios
			WHERE np_id = co_idarticulo
				AND co_usualta = se_usuario
				AND np_id = :id
	 ORDER BY co_id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$body = "El usuario ".$row["SE_NOMBRE"]." ha agregado un comentario al artículo \"".$row["NP_TITULO"]."\" del módulo de ".getNombreModulo($_REQUEST["idmodulo"]).".";
	$subject = "Nuevo comentario en la Intranet de Provincia ART";
	SendEmail($body, "Intranet", $subject, array("npereira@provart.com.ar", "jsantoro@provart.com.ar"), array(), array());
}
catch (Exception $e) {
?>
<script>
	alert(unescape('<?= rawurlencode($e->getMessage())?>'));
</script>
<?
	exit;
}
?>
<script>
	function hideMsg() {
		with (window.parent.document) {
			getElementById('datoGuardadoOk').style.display = 'none';

			if (getElementById('formComentario') != null)
				formComentario.comentario.value = '';
			if (getElementById('formComentario1') != null)
				formComentario1.comentario.value = '';
			if (getElementById('formComentario2') != null)
				formComentario2.comentario.value = '';
		}
	}


	with (window.parent.document)
		if (getElementById('datoGuardadoOk') != null) {
			getElementById('datoGuardadoOk').style.display = 'inline';
			setTimeout('hideMsg()', 3000);
		}
</script>