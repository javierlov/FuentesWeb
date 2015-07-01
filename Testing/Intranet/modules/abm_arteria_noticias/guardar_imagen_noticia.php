<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


try {
	if (isset($_REQUEST["idnoticia"]))
		$idNoticia = $_REQUEST["idnoticia"];
	else {
		$sql =
			"SELECT na_id
				FROM rrhh.rna_noticiasarteria
			 WHERE na_idboletin = :idboletin
				  AND na_posicion = :posicion";
		$params = array(":idboletin" => $_REQUEST["idboletin"], ":posicion" => $_REQUEST["num"]);
		$idNoticia = ValorSql($sql, "", $params, 0);
	}

	if (($_REQUEST["tipoop"] == "a") or ($_REQUEST["tipoop"] == "m")) {
		$fileOrigen = IMAGES_ARTERIA_PATH."noticias\\".$_REQUEST["imgName"];
		$partes_ruta = pathinfo($_REQUEST["imgName"]);
	}

	if ($_REQUEST["tipoop"] == "a") {		// Alta..
		$sql =
			"INSERT INTO rrhh.ria_imagenesarteria (ia_extension, ia_fechaalta, ia_idnoticia, ia_orden, ia_usualta)
															  VALUES (:extension, SYSDATE, :idnoticia,
															  				 (SELECT NVL((SELECT MAX(ia_orden) + 1
															  				 							 FROM rrhh.ria_imagenesarteria
															  				 						  WHERE ia_idnoticia = :idnoticia), 1)
															  					 FROM DUAL), :usualta)";
		$params = array(":extension" => $partes_ruta["extension"],
									":idnoticia" => $idNoticia,
									":usualta" => GetWindowsLoginName(true));
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(ia_id) FROM rrhh.ria_imagenesarteria WHERE ia_idnoticia = :idnoticia";
		$params = array(":idnoticia" => $idNoticia);
		$idImagen = ValorSql($sql, "", $params, 0);
	}

	if ($_REQUEST["tipoop"] == "m") {		// Modificación..
		$idImagen = $_REQUEST["id"];
		$sql =
			"UPDATE rrhh.ria_imagenesarteria
					SET ia_extension = :extension,
							ia_fechamodif = SYSDATE,
							ia_usumodif = :usumodif
			  WHERE ia_id = :id";
		$params = array(":extension" => $partes_ruta["extension"], ":usumodif" => GetWindowsLoginName(true), ":id" => $idImagen);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_REQUEST["tipoop"] == "b") {		// Baja..
		$sql =
			"UPDATE rrhh.ria_imagenesarteria
					SET ia_fechabaja = SYSDATE,
							ia_usubaja = :usubaja
			  WHERE ia_id = :id";
		$params = array(":usubaja" => GetWindowsLoginName(true), ":id" => $_REQUEST["id"]);
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if (($_REQUEST["tipoop"] == "a") or ($_REQUEST["tipoop"] == "m")) {
		$fileDest = IMAGES_ARTERIA_PATH."noticias/".$idNoticia."_".$idImagen.".".$partes_ruta["extension"];

		unlink($fileDest);
		if (!rename($fileOrigen, $fileDest))
			unlink($fileOrigen);
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
	parent.document.iframeImagenes.location.href = '/modules/abm_arteria_noticias/imagenes.php?idnoticia=<?= $idNoticia?>';
</script>