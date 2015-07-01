<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


if (!isset($_SESSION["idUsuario"]))
	$_SESSION["idUsuario"] = -1;
if (!hasPermiso(4, $_SESSION["idUsuario"])) {
?>
	<script type="text/javascript">
		alert('Usted no tiene permiso para acceder a este módulo.');
		window.history.back();
	</script>
<?
}


if (isset($_REQUEST["obj"])) {		// Si es la primera vez que entra a la página oculto al objeto y recargo esta página..
?>
	<meta http-equiv="refresh" content="0;url=bajar_archivo.php?id=<?= $_REQUEST["id"]?>&u=<?= $_REQUEST["u"]?>" />
	<script type="text/javascript">
		window.parent.document.getElementById('<?= $_REQUEST["obj"]?>').style.display = 'none';
	</script>
<?
}
else {		// Muestro el archivo..
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT tb_especial1 || '\' || ab_nombre
			 FROM ctb_tablas, web.wab_archivobapro
			WHERE tb_clave = 'PATHS'
				AND tb_codigo = '008'
				AND ab_id = :id";
	$file = StringToLower(ValorSql($sql, "", $params));

	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".basename($file));

	if ((readfile($file)) and ($_REQUEST["u"] == "t")) {
		$params = array(":usuario" => $_SESSION["usuario"], ":id" => $_REQUEST["id"]);
		$sql =
			"UPDATE web.wab_archivobapro
					SET ab_usudescargado = :usuario,
							ab_fechadescargado = SYSDATE
				WHERE ab_id = :id";
		DBExecSql($conn, $sql, $params);
	}
}
?>