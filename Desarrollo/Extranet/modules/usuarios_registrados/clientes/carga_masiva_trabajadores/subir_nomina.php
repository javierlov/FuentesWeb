<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
session_start();


function guardarArchivo($filename) {
	$tempfile = $_FILES["archivo"]["tmp_name"];
	$uploadOk = false;
	if (is_uploaded_file($tempfile))
		if (move_uploaded_file($tempfile, $filename))
			$uploadOk = true;

	if (!$uploadOk)
		echo "<script type='text/javascript'>alert('Ocurrió error al guardar el archivo.');</script>";

	return $uploadOk;
}

function solicitarArchivo($path) {
	global $conn;

	$params = array(":idusuario" => $_SESSION["idUsuario"],
									":ipusuario" => $_SERVER["REMOTE_ADDR"],
									":ruta" => $path);
	$sql =
		"INSERT INTO tmp.tnw_nominaweb (nw_idusuario, nw_ipusuario, nw_ruta, nw_fechahorainicio)
														VALUES (:idusuario, :ipusuario, :ruta, SYSDATE)";
	DBExecSql($conn, $sql, $params);
}


$_SESSION["pageLoadOk"] = false;
register_shutdown_function("shutdown", 57);
set_time_limit(1800);

if (!makeDirectory(DATA_CARGA_MASIVA_TRABAJADORES.$_SESSION["idUsuario"])) {
	echo "<script type='text/javascript'>alert('ERROR: No se puede crear la carpeta de usuario.');</script>";
	exit;
}

$file = DATA_CARGA_MASIVA_TRABAJADORES.$_SESSION["idUsuario"]."/".date("Ymd_His").".xls";
$fileE = DATA_CARGA_MASIVA_TRABAJADORES_EXTERNAL.$_SESSION["idUsuario"]."\\".date("Ymd_His").".xls";

guardarArchivo($file);
solicitarArchivo($fileE);

$params = array(":idusuario" => $_SESSION["idUsuario"], ":ipusuario" => $_SERVER["REMOTE_ADDR"]);
$sql =
	"SELECT MAX(nw_id)
		 FROM tmp.tnw_nominaweb
		WHERE nw_idusuario = :idusuario
			AND nw_ipusuario = :ipusuario";
$id = valorSql($sql, "", $params);

$procesoFinalizado = false;
while (!$procesoFinalizado) {		// Queda loopeando hasta que se procese el archivo o salga por timeout..
	sleep(2);

	$params = array(":id" => $id);
	$sql =
		"SELECT nw_generar
			 FROM tmp.tnw_nominaweb
			WHERE nw_id = :id";
	$procesoFinalizado = (valorSql($sql, "", $params) == "F");
}

$_SESSION["pageLoadOk"] = true;

$params = array(":id" => $id);
$sql =
	"SELECT nw_resultado
		 FROM tmp.tnw_nominaweb
		WHERE nw_id = :id";
$procesoOk = (valorSql($sql, "", $params) == "T");

if ($procesoOk)
	unlink($file);
?>
<script type="text/javascript">
	if (<?= ($procesoOk)?"true":"false"?>)
		window.parent.location.href = '/importacion-masiva-trabajadores';
	else {
		alert('Ocurrió un error al procesar el archivo.\nConsulte con el administrador del sistema. [Revisar visor de sucesos]');
		window.parent.document.getElementById('divGridEspera').style.display = 'none';
		window.parent.document.getElementById('divGridEsperaTexto').style.display = 'none';
	}
</script>
Procesando archivo...