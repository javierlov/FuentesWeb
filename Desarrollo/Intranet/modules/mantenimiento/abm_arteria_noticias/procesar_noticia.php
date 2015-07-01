<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


try {
	$params = array(":idboletin" => $_POST["idboletin"], ":posicion" => $_POST["num"]);
	$sql =
		"SELECT 1
			 FROM rrhh.rna_noticiasarteria
			WHERE na_idboletin = :idboletin
				AND na_posicion = :posicion";
	$esAlta = (!existeSql($sql, $params));

	switch ($_POST["plantilla"]) {
		case 1:
		case 2:
		case 4:
			$altoImagenes = 208;
			$anchoImagenes = 250;
			break;
		case 3:
			$altoImagenes = 82;
			$anchoImagenes = 100;
			break;
	}

	if ($esAlta) {		// Alta..
		$blobParamName = "the_clob";
		$sql =
			"INSERT INTO rrhh.rna_noticiasarteria (na_altoimagenes, na_anchoimagenes, na_colortitulo, na_fechaalta, na_idboletin, na_nota,
																						 na_numeroplantilla, na_posicion, na_titulo, na_usualta,
																						 na_visible)
																		 VALUES (".$altoImagenes.", ".$anchoImagenes.", ".addQuotes($_POST["fondo"], true).", SYSDATE, ".$_POST["idboletin"].", EMPTY_CLOB(), ".
																						 nullIsEmpty($_POST["plantilla"]).", ". $_POST["num"].", ".addQuotes($_POST["titulo"], true).", ".addQuotes(GetWindowsLoginName(true)).", ".
																						 addQuotes((isset($_POST["visible"])?"S":"N"), true).")
																	RETURNING na_nota INTO :".$blobParamName;
		DBSaveLob($conn, $sql, $blobParamName, $_POST["cuerpo"], OCI_B_CLOB);
	}
	else {		// Modificación..
		$blobParamName = "the_clob";
		$sql =
			"UPDATE rrhh.rna_noticiasarteria
					SET na_altoimagenes = ".$altoImagenes.",
							na_anchoimagenes = ".$anchoImagenes.",
							na_colortitulo = ".addQuotes($_POST["fondo"], true).",
							na_fechamodif = SYSDATE,
							na_nota = EMPTY_CLOB(),
							na_numeroplantilla = ".nullIsEmpty($_POST["plantilla"]).",
							na_titulo = ".addQuotes($_POST["titulo"], true).",
							na_usumodif = ".addQuotes(GetWindowsLoginName(true)).",
							na_visible = ".addQuotes((isset($_POST["visible"])?"S":"N"), true)."
				WHERE na_idboletin = ".$_POST["idboletin"]."
					AND na_posicion = ".$_POST["num"]."
		RETURNING na_nota INTO :".$blobParamName;
		DBSaveLob($conn, $sql, $blobParamName, $_POST["cuerpo"], OCI_B_CLOB);

		// Guardo el comentario de las imagenes..
		$recs = explode("@_@", $_POST["descripcion_imagenes"]);
		foreach ($recs as $value) {
			$fields = explode("=_=", $value);
			if ($fields[0] != "") {
				$params = array(":descripcion" => substr($fields[1], 0, 128), ":usumodif" => getWindowsLoginName(true), ":id" => $fields[0]);
				$sql =
					"UPDATE rrhh.ria_imagenesarteria
							SET ia_descripcion = :descripcion,
									ia_fechamodif = SYSDATE,
									ia_usumodif = :usumodif
						WHERE ia_id = :id";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
		}
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
	window.parent.location.reload();
</script>