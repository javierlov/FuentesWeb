<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$sql =
	"SELECT tj_id
		 FROM ctj_trabajador, crl_relacionlaboral
		WHERE tj_id = rl_idtrabajador
			AND tj_cuil = :cuil
			AND rl_contrato = art.afiliacion.get_contratovigente((SELECT em_cuit
																															FROM aem_empresa
																														 WHERE em_id = :idempresa), SYSDATE)";

$existe = false;
foreach ($_SESSION["CUILES_A_AGREGAR"] as $value) {
	$params = array(":cuil" => $value, ":idempresa" => $_SESSION["idEmpresa"]);
	$idTrabajador = ValorSql($sql, "", $params);
	if ($idTrabajador != "") {
		$existe = true;
		$_SESSION["certificadoCobertura"]["trabajadores"][] = $idTrabajador;
	}
}

$_SESSION["certificadoCobertura"]["trabajadores"] = array_unique($_SESSION["certificadoCobertura"]["trabajadores"]);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('archivo').readOnly = false;
		getElementById('btnCargar').style.display = 'inline';
		getElementById('btnVerEjemplo').style.display = 'inline';
		getElementById('imgProcesando').style.display = 'none';
		getElementById('divErrores').style.display = 'none';
<?
if ($existe) {
?>
		getElementById('divCargaOk').style.display = 'block';
		setTimeout("window.parent.location.href = '/certificados-cobertura/seleccion-trabajadores';", 3000);
<?
}
else {
?>
		getElementById('divSinRegistros').style.display = 'block';
<?
}
?>
	}
</script>