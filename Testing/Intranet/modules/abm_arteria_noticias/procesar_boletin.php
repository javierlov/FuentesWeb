<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


function updateFileName($id, $archPath) {
	global $conn;

	$sql =
		"UPDATE rrhh.rbi_busquedasinternas
				SET bi_nombrearchivo = :nombrearchivo
		  WHERE bi_id = :id";
	$params = array(":nombrearchivo" => $archPath, ":id" => $id);
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);
}

function uploadFile($arch, $folder, $id, &$archPath) {
	$tempfile = $arch["tmp_name"];
	$partes_ruta = pathinfo($arch["name"]);
	$filename = StringToLower($id.".".$partes_ruta["extension"]);

	$uploadOk = false;
	if (is_uploaded_file($tempfile))
		if (move_uploaded_file($tempfile, $folder.$filename)) {
			$uploadOk = true;
			$archPath = $partes_ruta["basename"];
		}

	if (!$uploadOk)
		echo "<script>alert('Ocurrió un error al guardar el archivo.');</script>";

	return $uploadOk;
}


try {
	$archPath = "";
	$msg = "";

	if ($_REQUEST["tipoOp"] == "A") {		// Alta..
		$sql =
			"INSERT INTO rrhh.rbi_busquedasinternas (bi_cantidadpostulantes, bi_designados, bi_fechaalta, bi_idestado, bi_puesto, bi_usualta)
																	VALUES (:cantidadpostulantes, :designados, SYSDATE, :idestado, :puesto, :usualta)";
		$params = array(":cantidadpostulantes" => nullIsEmpty($_POST["cantidadPostulantes"]),
									":designados" => $_POST["designados"],
									":idestado" => $_POST["estado"],
									":puesto" => $_POST["puesto"],
									":usualta" => GetWindowsLoginName(true));
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		$sql = "SELECT MAX(bi_id) FROM rrhh.rbi_busquedasinternas";
		$id = ValorSql($sql, "", array(), 0);
		$msg = "alert('Se generó la búsqueda interna Nº ".$id."');";
	}

	if ($_REQUEST["tipoOp"] == "M") {		// Modificación..
		$id = $_POST["id"];

		$sql =
			"UPDATE rrhh.rbi_busquedasinternas
					SET bi_cantidadpostulantes = :cantidadpostulantes,
       					bi_designados = :designados,
       					bi_fechamodif = SYSDATE,
       					bi_idestado = :idestado,
							bi_puesto = :puesto,
					 		bi_usumodif = :usumodif
 			  WHERE bi_id = :id";
		$params = array(":cantidadpostulantes" => nullIsEmpty($_POST["cantidadPostulantes"]),
									":designados" => $_POST["designados"],
									":idestado" => $_POST["estado"],
									":puesto" => $_POST["puesto"],
									":usumodif" => GetWindowsLoginName(true),
									":id" => $id);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if (($_POST["tipoOp"] == "A") or ($_POST["tipoOp"] == "M")) {
		if ($_FILES["archivo"]["name"] != "")		// Si existe el archivo, lo subo..
			if (uploadFile($_FILES["archivo"], DATA_BUSQUEDAS_INTERNAS_PATH, $id, $archPath))
				updateFileName($id, $archPath);
			else
				exit;
	}

	if ($_REQUEST["tipoOp"] == "B") {		// Baja..
		$sql =
			"UPDATE rrhh.rbi_busquedasinternas
					SET bi_fechabaja = SYSDATE,
							bi_usubaja = :usubaja
			  WHERE bi_id = :id";
		$params = array(":usubaja" => GetWindowsLoginName(true), ":id" => $_POST["id"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script>
	<?= $msg?>
	window.parent.location.href = '/index.php?pageid=62&buscar=yes';
</script>