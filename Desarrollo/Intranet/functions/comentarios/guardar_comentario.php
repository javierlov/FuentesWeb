<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getNombreModulo($idmodulo) {
	global $conn;

	$params = array(":idmodulo" => $idmodulo);
	$sql =
		"SELECT pi_titulo
			 FROM web.wpi_paginasintranet
			WHERE pi_id = :idmodulo";
	return valorSql($sql, "", $params);
}


try {
	if (isset($_POST["baja"])) {
		$params = array(":id" => $_POST["id"],
										":usubaja" => getWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.rco_comentarios
					SET co_fechabaja = SYSDATE,
							co_usubaja = :usubaja
				WHERE co_id = :id";
		DBExecSql($conn, $sql, $params);
	}
	else {
		if ($_POST["comentario"] == "")
			throw new Exception("Debe ingresar un comentario.");

		// Valido que no se pueda guardar un mensaje para el mismo artículo por el mismo usuario con menos de 20 segundos de diferencia con el mensaje anterior..
		$params = array(":idarticulo" => $_POST["idarticulo"],
										":idmodulo" => $_POST["idmodulo"],
										":usualta" => getWindowsLoginName(true));
		$sql =
			"SELECT 1
				 FROM rrhh.rco_comentarios
				WHERE co_idmodulo = :idmodulo
					AND co_idarticulo = :idarticulo
					AND co_usualta = :usualta
					AND co_fechaalta > SYSDATE - 0.00023";		// 20 segundos..
		if (existeSql($sql, $params))
			throw new Exception("Debe esperar 20 segundos para agregar un nuevo comentario.");


		$params = array(":detalle" => substr($_POST["comentario"], 0, 1024),
										":idarticulo" => $_POST["idarticulo"],
										":idmodulo" => $_POST["idmodulo"],
										":usualta" => getWindowsLoginName(true));
		$sql =
			"INSERT INTO rrhh.rco_comentarios (co_detalle, co_fechaalta, co_id, co_idarticulo, co_idmodulo, co_usualta)
																 VALUES (:detalle, SYSDATE, -1, :idarticulo, :idmodulo, :usualta)";
		DBExecSql($conn, $sql, $params);

		// Envío un aviso a RRHH..
		$body = "El usuario ".getUserName()." ha agregado un comentario a la página <i>".$_POST["titulo"]."</i> del módulo de ".getNombreModulo($_POST["idmodulo"]).".<br /><br />";
		$body.= "http://".$_SERVER["HTTP_HOST"].$_POST["url"];
		$subject = "Nuevo comentario en la Intranet de Provincia ART";
		sendEmail($body, "Intranet", $subject, getEmailsAviso(), array(), array(), "H");
	}
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script language="JavaScript" src="/js/functions.js"></script>
	<script type='text/javascript'>
		try {
			showError(unescape('<?= rawurlencode($e->getMessage())?>'), window.parent);
		}
		catch(err) {
			alert(unescape('<?= rawurlencode($e->getMessage())?>'));
		}
	</script>
<?
	exit;
}
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	window.parent.location.reload();
</script>