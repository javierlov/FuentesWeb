<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$notaPrincipal = "N";
if (isset($_REQUEST["notaPrincipal"]))
	$notaPrincipal = "S";

if (($notaPrincipal == "S") and ($_REQUEST["TipoOp"] != "B")) {		// Si es un alta o una modificación..
	$sql = "UPDATE rrhh.rap_articulosprensa SET ap_notaprincipal = 'N'";
	DBExecSql($conn, $sql, array());
}

if ($_REQUEST["TipoOp"] == "A") {		// Alta..
	$blobParamName = "the_clob";
	$sql =
		"INSERT INTO rrhh.rap_articulosprensa
            		(ap_id, ap_fecha, ap_fuente, ap_titulo, ap_contenido, ap_fechaalta, ap_usualta, ap_notaprincipal)
     		 VALUES (-1, ".SqlDate($_REQUEST["Fecha"]).", ".addQuotes($_REQUEST["Fuente"]).", ".
     		 				 addQuotes($_REQUEST["Titulo"]).", EMPTY_CLOB(), SYSDATE, UPPER(".addQuotes(GetWindowsLoginName())."), ".addQuotes($notaPrincipal).")
     	 RETURNING ap_contenido INTO :".$blobParamName;
	DBSaveLob($conn, $sql, $blobParamName, $_REQUEST["Contenido"], OCI_B_CLOB);
}

if ($_REQUEST["TipoOp"] == "M") {		// Modificación..
	$blobParamName = "the_clob";
	$sql =
  	"UPDATE rrhh.rap_articulosprensa
  			SET ap_fecha = ".SqlDate($_REQUEST["Fecha"]).",
   			  	ap_fuente = ".addQuotes($_REQUEST["Fuente"]).",
   		  		ap_titulo = ".addQuotes($_REQUEST["Titulo"]).",
   		  		ap_contenido = EMPTY_CLOB(),
	  		  	ap_fechamodif = SYSDATE,
  			  	ap_usumodif = UPPER(".addQuotes(GetWindowsLoginName())."),
						ap_notaprincipal = ".addQuotes($notaPrincipal)."
			WHERE ap_id = ".$_REQUEST["id"]."
	RETURNING ap_contenido INTO :".$blobParamName;
	DBSaveLob($conn, $sql, $blobParamName, $_REQUEST["Contenido"], OCI_B_CLOB);
}

if ($_REQUEST["TipoOp"] == "B") {		// Baja..
	$params = array(":usubaja" => GetWindowsLoginName(), ":id" => $_REQUEST["id"]);
	$sql =
		"UPDATE rrhh.rap_articulosprensa
				SET ap_fechabaja = SYSDATE,
						ap_usubaja = UPPER(:usubaja)
		  WHERE ap_id = :id";
	DBExecSql($conn, $sql, $params);
}
?>
<script>
<?
if ($dbError["offset"]) {
?>
	alert('<?= $dbError["message"]?>');
<?
}
else {
?>
	window.parent.location.href = '/index.php?pageid=13';
<?
}
?>
</script>