<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


if (!isset($_SESSION["idUsuario"]))
	$_SESSION["idUsuario"] = -1;
if (!hasPermiso(4, $_SESSION["idUsuario"]))
	exit;

$dbError = "";

$secuencia = ValorSql("SELECT seq_wab_id.NEXTVAL FROM DUAL");
$nombre = ValorSql("SELECT 'TR' || LPAD(".$secuencia.", 6, '0') || '.txt' FROM DUAL");
$path = ValorSql("SELECT tb_especial1 FROM ctb_tablas WHERE tb_clave = 'PATHS' AND tb_codigo = '007'");
$discPath = ValorSql("SELECT tb_especial1 FROM ctb_tablas WHERE tb_clave = 'PATHS' AND tb_codigo = '008'");

try {
		if ($_FILES["archivo"]["name"] != "") {		// Subo el archivo..
			$tempfile = $_FILES["archivo"]["tmp_name"];
			$filename = $_FILES["archivo"]["name"];

			// Si el archivo ya existe, le cambio el nombre..
			$i = 0;
			while (file_exists($discPath."\\".$filename)) {
				$i++;
				$tmpArray = explode(".", $_FILES["archivo"]["name"]);
				$filename = $tmpArray[0]."(".$i.").".$tmpArray[1];
			}

			$uploadOk = false;
			if (is_uploaded_file($tempfile))
				if (move_uploaded_file($tempfile, $discPath."\\".$filename))
					$uploadOk = true;

			if (!$uploadOk) {
				echo "<script type='text/javascript'>alert('Ocurrió un error al guardar la imagen.');window.history.back();</script>";
				exit;
			}
		}

	$params = array(":id" => $secuencia,
									":path" => $path,
									":nombre" => $filename,
									":usualta" => $_SESSION["usuario"]);
	$sql =
		"INSERT INTO wab_archivobapro (ab_id, ab_tipo, ab_path, ab_nombre, ab_usualta, ab_fechaalta)
													 VALUES (:id, 'R', :path, :nombre, :usualta, SYSDATE)";
	DBExecSql($conn, $sql, $params);
}
catch (Exception $e) {
	$dbError = $e->getMessage();
}
?>
<html>
	<head>
<?
if ($dbError != "") {
?>
	<script type="text/javascript">
		alert('<?= $dbError?>');
	</script>
<?
}
else
	header("location:".LOCAL_PATH_PAGO_TRANSFERENCIA."pago_transferencia.php?flpld=k");		// fileupload=ok
?>
	</head>
	<body>
		<?= $dbError?>
	</body>
</html>