<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));

try {
	if ($_REQUEST["ep"] == "t")		// Establecimiento propio..
		$sql =
			"SELECT es_calle calle, es_cpostal cpostal, es_departamento departamento, es_provincia idprovincia, es_localidad localidad, es_numero numero, es_piso piso, pv_descripcion provincia
				 FROM aes_establecimiento, cpv_provincias
				WHERE es_provincia = pv_codigo
					AND es_id = :id";
	else		// Establecimiento de tercero..
		$sql =
			"SELECT et_calle calle, et_cpostal cpostal, et_cuit_temporal cuit, et_departamento departamento, et_provincia idprovincia, et_localidad localidad, et_numero numero, et_piso piso,
							pv_descripcion provincia
				 FROM SIN.set_establecimiento_temporal, cpv_provincias
				WHERE et_provincia = pv_codigo
					AND et_id = :id";
	$params = array(":id" => $_REQUEST["id"]);
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script src="/modules/usuarios_registrados/clientes/js/denuncia_siniestros.js" type="text/javascript"></script>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('calleAccidente').value				= '<?= $row["CALLE"]?>';
		getElementById('codigoPostalAccidente').value	= '<?= $row["CPOSTAL"]?>';
		getElementById('departamentoAccidente').value	= '<?= addslashes(htmlspecialchars_decode($row["DEPARTAMENTO"], ENT_QUOTES))?>';
		getElementById('idProvinciaAccidente').value		= '<?= $row["IDPROVINCIA"]?>';
		getElementById('localidadAccidente').value			= '<?= $row["LOCALIDAD"]?>';
		getElementById('numeroAccidente').value			= '<?= addslashes(htmlspecialchars_decode($row["NUMERO"], ENT_QUOTES))?>';
		getElementById('pisoAccidente').value					= '<?= addslashes(htmlspecialchars_decode($row["PISO"], ENT_QUOTES))?>';
		getElementById('provinciaAccidente').value			= '<?= $row["PROVINCIA"]?>';

<?
if (isset($row["CUIT"])) {
?>
	getElementById('cuitContratista').value = '<?= $row["CUIT"]?>';
	getElementById('establecimientoAccidente').value = 0;
<?
}
?>

		getElementById('pSinDatosconocidosAccidente').style.display = 'none';
		getElementById('divDatosDomicilioAccidente').style.display = 'block';
	}

	habilitarDomicilioAccidente(window.parent.document, (<?= $_REQUEST["id"]?> == -1));
</script>