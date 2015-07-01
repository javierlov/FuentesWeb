<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validarCantidadEstablecimientos() {
	// Valido que tenga al menos un establecimiento cargado..
	$params = array(":idsolicitud" => $_REQUEST["idSolicitudAfiliacion"]);
	$sql =
		"SELECT COUNT(*)
			 FROM ase_solicitudestablecimiento
			WHERE se_fechabaja IS NULL
				AND se_idsolicitud = :idsolicitud";
	return (ValorSql($sql, 0, $params) > 0);
}

function validarRGRL() {
	// Valido que esten todos los formularios de RGRL..
	$params = array(":idsolicitud" => $_REQUEST["idSolicitudAfiliacion"]);
	$sql =
		"SELECT COUNT(*)
			 FROM ase_solicitudestablecimiento
			WHERE se_fechabaja IS NULL
				AND se_idsolicitud = :idsolicitud";
	$totalEstablecimientosRGRL = ValorSql($sql, "", $params);

	$params = array(":idsolicitud" => $_REQUEST["idSolicitudAfiliacion"]);
	$sql =
		"SELECT SUM(total)
			 FROM (SELECT COUNT(DISTINCT se_nroestableci) total
							 FROM ase_solicitudestablecimiento, hys.hsf_solicitudfgrl, hys.hst_solicituditemsfgrl
							WHERE se_id = sf_idsolicitudestablecimiento
								AND sf_id = st_idsolicitudfgrl
								AND se_fechabaja IS NULL
								AND sf_fechabaja IS NULL
								AND st_fechabaja IS NULL
								AND se_idsolicitud = :idsolicitud
					UNION ALL
						 SELECT COUNT(DISTINCT se_nroestableci)
							 FROM ase_solicitudestablecimiento
							WHERE art.hys.get_idresolucion463(se_id) IS NULL
								AND se_fechabaja IS NULL
								AND se_idsolicitud = :idsolicitud)";
	$totalRGRLCompletos = ValorSql($sql, "", $params);

	return ($totalEstablecimientosRGRL == $totalRGRLCompletos);
}


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["id"]);


$params = array(":id" => $_REQUEST["idSolicitudAfiliacion"]);
$sql =
	"SELECT sa_rgrlimpreso
		 FROM asa_solicitudafiliacion
		WHERE sa_id = :id";

if (ValorSql($sql, "", $params, 0) == "S") {
	$validarCantidadEstablecimientos = true;
	$validarRGRL = true;
}
else {
	$validarCantidadEstablecimientos = validarCantidadEstablecimientos();
	$validarRGRL = validarRGRL();
}
?>
<script type="text/javascript">
<?
if (!$validarCantidadEstablecimientos) {
?>
	alert('Antes de imprimir la solicitud de afiliación debe cargar al menos un (1) establecimiento.');
<?
}

if (!$validarRGRL) {
?>
	alert('Antes de imprimir la solicitud de afiliación debe completar los formularios RGRL de todos los establecimientos.');
<?
}

if (($validarCantidadEstablecimientos) and ($validarRGRL)) {
?>
	if (window.parent.document.getElementById('divGridEspera') != null) {
		window.parent.document.getElementById('divGridEspera').style.height = (2200 + parseInt(window.parent.document.getElementById('iframeEstablecimientos').height)) + 'px';
		window.parent.document.getElementById('divGridEspera').style.display = 'block';

		window.parent.document.getElementById('divGridEsperaTexto').style.top = (2200 + parseInt(window.parent.document.getElementById('iframeEstablecimientos').height) - 120) + 'px';
		window.parent.document.getElementById('divGridEsperaTexto').style.display = 'block';
	}

	if (window.parent.document.getElementById('imgImprimiendo') != null) {
		window.parent.document.getElementById('btnReimprimir').style.display = 'none';
		window.parent.document.getElementById('imgImprimiendo').style.display = 'inline';
	}

	window.parent.location.href = '/index.php?pageid=75&idModulo=<?= $_REQUEST["id"]?>&idsa=<?= $_REQUEST["idSolicitudAfiliacion"]?>';
<?
}
?>
</script>