<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


function updateFileName($id, $archPath) {
	global $conn;

	$params = array(":nombrearchivo" => $archPath, ":id" => $id);
	$sql =
		"UPDATE rrhh.rbc_busquedascorporativas
				SET bc_nombrearchivo = :nombrearchivo
		  WHERE bc_id = :id";
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
		$params = array(":idempresa" => $_POST["empresa"],
										":idestado" => $_POST["estado"],
										":puesto" => $_POST["puesto"],
										":usualta" => GetWindowsLoginName(true));
		$sql =
			"INSERT INTO rrhh.rbc_busquedascorporativas (bc_fechaalta, bc_idempresa, bc_idestado, bc_puesto, bc_usualta)
																					 VALUES (SYSDATE, :idempresa, :idestado, :puesto, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		$sql = "SELECT MAX(bc_id) FROM rrhh.rbc_busquedascorporativas";
		$id = ValorSql($sql, "", array(), 0);
		$msg = "alert('Se generó la búsqueda corporativa Nº ".$id."');";
	}

	if ($_REQUEST["tipoOp"] == "M") {		// Modificación..
		$id = $_POST["id"];

		$params = array(":id" => $id,
										":idempresa" => $_POST["empresa"],
										":idestado" => $_POST["estado"],
										":puesto" => $_POST["puesto"],
										":usumodif" => GetWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.rbc_busquedascorporativas
					SET bc_fechamodif = SYSDATE,
       					bc_idempresa = :idempresa,
       					bc_idestado = :idestado,
							bc_puesto = :puesto,
					 		bc_usumodif = :usumodif
 			  WHERE bc_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if (($_POST["tipoOp"] == "A") or ($_POST["tipoOp"] == "M")) {
		if ($_FILES["archivo"]["name"] != "")		// Si existe el archivo, lo subo..
			if (uploadFile($_FILES["archivo"], DATA_BUSQUEDAS_CORPORATIVAS_PATH, $id, $archPath))
				updateFileName($id, $archPath);
			else
				exit;
	}

	if ($_REQUEST["tipoOp"] == "B") {		// Baja..
		$params = array(":usubaja" => GetWindowsLoginName(true), ":id" => $_POST["id"]);
		$sql =
			"UPDATE rrhh.rbc_busquedascorporativas
					SET bc_fechabaja = SYSDATE,
							bc_usubaja = :usubaja
			  WHERE bc_id = :id";
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
	window.parent.location.href = '/index.php?pageid=63&buscar=yes';
</script>