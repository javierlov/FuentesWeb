<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


function getActualFilename($id) {
	// Obtengo el nombre del archivo anterior..

	$result = "";
	if ($gd = opendir(DATA_INFORMES_GESTION)) {
		while (($file = readdir($gd)) !== false)
			if (($file != ".") and ($file != "..") and ($file != "Thumbs.db")) {
				$arrFilename = explode("_", $file);
				if ($arrFilename[0] == $id) {
					$result = $file;
					break;
				}
			}
		closedir($gd);
	}

	return $result;
}

function uploadFile($id, $alta) {
	// Subo el archivo y borro el anterior para que no quede basura..

	$filePath = "";
	if ($_FILES["Archivo"]["name"] != "") {
		$tempfile = $_FILES["Archivo"]["tmp_name"];
		$filename = StringToLower($id."_".$_FILES["Archivo"]["name"]);

		$uploadOk = false;
		if (is_uploaded_file($tempfile)) {
			$oldFilename = getActualFilename($id);
			rename(DATA_INFORMES_GESTION.$oldFilename, DATA_INFORMES_GESTION."OLD_".$oldFilename);
			if (move_uploaded_file($tempfile, DATA_INFORMES_GESTION.$filename)) {
				unlink(DATA_INFORMES_GESTION."OLD_".$oldFilename);
				$uploadOk = true;
				$filePath = $filename;
			}
			else		// Si no se subió el archivo, pongo el nombre del archivo anterior de esa publicación como estaba antes..
				rename(DATA_INFORMES_GESTION."OLD_".$oldFilename, DATA_INFORMES_GESTION.$oldFilename);
		}

		if ($uploadOk)
			return $filePath;
		else {
?>
			<SCRIPT>
				alert('Ocurrió un error al subir el archivo. Inténtelo nuevamente.');
				history.go(-1);
			</SCRIPT>
<?
			exit;
		}
	}
	elseif ($alta) {
?>
		<script>
			alert('Debe seleccionar un archivo a publicar.');
		</script>
<?
		exit;
	}
}


if ($_REQUEST["action"] == "B") {		// Buscar..
?>
	<script>
		window.parent.location.href = '/index.php?pageid=34&mdl=administracion_de_publicaciones.php&temaFiltro=<?= $_REQUEST["temaFiltro"]?>&publicacionFiltro=<?= $_REQUEST["publicacionFiltro"]?>';
	</script>
<?
}


if ($_REQUEST["action"] == "C") {		// Cargar..
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT ip_activo, ip_archivo, ip_idtema, ip_titulo
			 FROM intra.cip_informepublicado
			WHERE ip_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
?>
<script>
	window.parent.verPublicacion(<?= $_REQUEST["id"]?>, <?= $row["IP_IDTEMA"]?>, '<?= $row["IP_ARCHIVO"]?>', unescape('<?= rawurlencode($row["IP_TITULO"])?>'), <?= $row["IP_ACTIVO"]?>);
</script>
<?
}


if ($_REQUEST["action"] == "E") {		// Eliminar..
	$params = array(":usubaja" => GetWindowsLoginName(), ":id" => $_REQUEST["id"]);
	$sql =
		"UPDATE intra.cip_informepublicado
				SET ip_usubaja = UPPER(:usubaja),
						ip_fechabaja = SYSDATE
			WHERE ip_id = :id";
	DBExecSql($conn, $sql, $params);
?>
<script>
	window.parent.location.reload();
</script>
<?
}


if ($_REQUEST["action"] == "G") {		// Guardar..
	try {
		if ($_POST["Id"] == -1)		// Es una alta..
			$id = GetSecNextValOracle("intra.seq_cip_id");
		else
			$id = $_POST["Id"];

		$filePath = uploadFile($id, ($_POST["Id"] == -1));

		if ($_POST["Id"] == -1) {		// Es una alta..
			$sql =
				"INSERT INTO intra.cip_informepublicado (ip_id, ip_idtema, ip_archivo, ip_titulo, ip_activo, ip_usualta, ip_fechaalta)
																				 VALUES (:id, :idtema, :archivo, :titulo, :activo, UPPER(:usualta), SYSDATE)";
			$params = array(":activo" => $_POST["Activo"],
											":archivo" => $filePath,
											":id" => $id,
											":idtema" => $_POST["tema"],
											":titulo" => $_POST["Titulo"],
											":usualta" => GetWindowsLoginName());
			DBExecSql($conn, $sql, $params);
		}
		else {
			$sql =
				"UPDATE intra.cip_informepublicado
						SET ip_idtema = :idtema,
								ip_titulo = :titulo,
								ip_activo = :activo,
								ip_usumodif = UPPER(:usumodif),
								ip_fechamodif = SYSDATE";
			if ($filePath != "")
				$sql.= ", ip_archivo = ".addQuotes($filePath);
			$sql.= " WHERE ip_id = :id";

			$params = array(":activo" => $_POST["Activo"],
											":id" => $id,
											":idtema" => $_POST["tema"],
											":titulo" => $_POST["Titulo"],
											":usumodif" => GetWindowsLoginName());
			DBExecSql($conn, $sql, $params);
		}
	}
	catch (Exception $e) {
		echo "<script>alert('".$e->getMessage()."');</script>";
		exit;
	}
?>
	<script>
		window.parent.location.reload();
	</script>
<?
}

if ($_REQUEST["action"] == "V") {		// Ver el archivo asociado..
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT ip_archivo
			 FROM intra.cip_informepublicado
			WHERE ip_id = :id";
 	$filename = ValorSql($sql, "", $params);
 	if ($filename != "") {
?>
	<script>
		window.open('<?= "/archivo/".base64_encode(DATA_INFORMES_GESTION.$filename)?>', 'intranetWindow');
	</script>
<?
	}
}
?>