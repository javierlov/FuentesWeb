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
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));

try {
	SetDateFormatOracle("DD/MM/YYYY");

	$params = array(":contrato" => $_SESSION["contrato"], ":cuil" => $_REQUEST["cl"]);
	$sql = "SELECT tj_id FROM ctj_trabajador, crl_relacionlaboral WHERE rl_id = rl_idtrabajador AND rl_contrato = :contrato AND tj_cuil = :cuil";
	$idTrabajador = ValorSql($sql, "", $params);

	if ($idTrabajador == "")		// Si la CUIL no está en nuestra base de datos, salgo..
		exit;

	$curs = null;
	$params = array(":idempresa" => $_SESSION["idEmpresa"], ":id" => $idTrabajador);
	$sql = "BEGIN webart.get_trabajador(:data, :idempresa, :id); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs);
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script src="/js/functions.js" type="text/javascript"></script>
<script src="/modules/usuarios_registrados/clientes/js/nomina_trabajadores.js" type="text/javascript"></script>
<script type="text/javascript">
	if (confirm('Los datos de este trabajador se encuentran en nuestra base de datos.\n ¿ Desea recuperarlos ?')) {
		with (window.parent.document) {
			getElementById('ciuo').innerHTML = '<?= ($row["CIUODESCRIPCION"] != "")?$row["CIUODESCRIPCION"]:"Utilice el buscador para seleccionar el CIUO."?>';
			getElementById('estadoCivil').selectedIndex = getItemIndex(getElementById('estadoCivil'), '<?= $row["ESTADOCIVILID"]?>');
			getElementById('fechaIngreso').value = '<?= $row["FECHAINGRESO"]?>';
			getElementById('fechaNacimiento').value = '<?= $row["FECHANACIMIENTO"]?>';
			getElementById('nacionalidad').selectedIndex = getItemIndex(getElementById('nacionalidad'), '<?= $row["NACIONALIDADID"]?>');
			getElementById('nombre').value = '<?= $row["NOMBRE"]?>';
			getElementById('otraNacionalidad').value = '<?= $row["OTRANACIONALIDAD"]?>';
			getElementById('remuneracion').value = '<?= $row["REMUNERACION"]?>';
			getElementById('sector').value = '<?= $row["SECTOR"]?>';
			getElementById('sexo').selectedIndex = getItemIndex(getElementById('sexo'), '<?= $row["SEXO"]?>');
			getElementById('tarea').value = '<?= $row["TAREA"]?>';
			getElementById('tipoContrato').selectedIndex = getItemIndex(getElementById('tipoContrato'), '<?= $row["MODALIDADCONTRATACIONID"]?>');

			getElementById('imgQuitarCiuo').style.visibility = '<?= ($row["CIUODESCRIPCION"] != "")?"visible":"hidden"?>';

<?
if ($row["CALLE"] == "") {
?>
	getElementById('divDatosDomicilio').style.display = 'none';
	getElementById('pSinDatosconocidos').style.display = 'block';
<?
}
else {
?>
	getElementById('divDatosDomicilio').style.display = 'block';
	getElementById('pSinDatosconocidos').style.display = 'none';

	getElementById('calle').value = '<?= $row["CALLE"]?>';
	getElementById('codigoPostal').value = '<?= $row["CPOSTAL"]?>';
	getElementById('departamento').value = '<?= $row["DEPARTAMENTO"]?>';
	getElementById('localidad').value = '<?= $row["LOCALIDAD"]?>';
	getElementById('numero').value = '<?= $row["NUMERO"]?>';
	getElementById('piso').value = '<?= $row["PISO"]?>';
	getElementById('provincia').value = '<?= $row["PROVINCIA"]?>';
<?
}
?>

		}
		cambiaNacionalidad('<?= $row["NACIONALIDADID"]?>', window.parent.document);
	}
</script>