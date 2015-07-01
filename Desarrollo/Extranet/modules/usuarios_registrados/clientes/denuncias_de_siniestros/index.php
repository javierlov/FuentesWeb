<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function denunciaTelefonica() {
	$params = array(":idempresa" => $_SESSION["idEmpresa"]);
	$sql = "SELECT art.siniestro.is_empresatercerizada(:idempresa, art.actualdate) FROM DUAL";

	return (valorSql($sql, "", $params) == "N");
}


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));


if (isset($_SESSION["isAgenteComercial"])) {
	validarSesion(validarContrato($_REQUEST["id"]));
	validarSesion(validarEntero($_REQUEST["id"]));
	if (isset($_REQUEST["id"])) {
		$id = $_REQUEST["id"];
		$params = array(":contrato" => $id);
		$sql = 
			"SELECT em_cuit, NVL(em_nombre, '-') empresa, NVL(co_idempresa, -1) idempresa
				 FROM aco_contrato, aem_empresa
				WHERE co_idempresa = em_id
					AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1
					AND co_contrato = :contrato";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);

		$_SESSION["contrato"] = $_REQUEST["id"];
		$_SESSION["cuit"] = $row["EM_CUIT"];
		$_SESSION["empresa"] = $row["EMPRESA"];
		$_SESSION["idEmpresa"] = $row["IDEMPRESA"];
	}
	else {
		$_SESSION["contrato"] = 0;
		$_SESSION["cuit"] = "";
		$_SESSION["empresa"] = "";
		$_SESSION["idEmpresa"] = 0;
	}
}
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div class="TituloSeccion" style="display:block; width:730px;">Denuncias de Siniestros</div>
<div align="right" class="ContenidoSeccion" style="margin-top:5px;"><i>>> <a href="/denuncia-siniestros/terminos-y-condiciones">Términos y Condiciones de uso</a></i></div>
<div class="ContenidoSeccion" style="margin-top:20px;">
	<span>Realice las denuncias de accidentes de trabajo y enfermedades profesionales on-line; así contaremos con información precisa para agilizar la gestión y seguimiento del caso y lograr una más rápida recuperación del accidentado.</span>
</div>
<div class="ContenidoSeccion" style="margin-left:8px; margin-top:32px;">
<?
if (denunciaTelefonica()) {
?>
	<table cellpadding="0" cellspacing="7">
		<tr>
			<td width="3%"><a href="/denuncia-siniestros/finalizacion-denuncias/<?= $_SESSION["contrato"]?>"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg" /></a></td>
			<td width="97%"><a href="/denuncia-siniestros/finalizacion-denuncias/<?= $_SESSION["contrato"]?>"><font color="#00539B"><b>FINALIZACIÓN DE DENUNCIAS INICIADAS A TRAVÉS DEL 0800-333-1333</b></font></a></td>
		</tr>
<?
}
?>
		<tr>
			<td width="3%"><a href="/denuncia-siniestros/alta-nuevas-denuncias"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg" /></a></td>
			<td width="97%"><a href="/denuncia-siniestros/alta-nuevas-denuncias"><font color="#00539B"><b>ALTA DE NUEVAS DENUNCIAS</b></font></a></td>
		</tr>
		<tr>
			<td width="3%"><a href="/denuncia-siniestros/consulta-denuncias-realizadas/<?= $_SESSION["contrato"]?>"><img border="0" src="/modules/usuarios_registrados/images/vinieta.jpg" /></a></td>
			<td width="97%"><a href="/denuncia-siniestros/consulta-denuncias-realizadas/<?= $_SESSION["contrato"]?>"><font color="#00539B"><b>CONSULTA DE DENUNCIAS REALIZADAS</b></font></a></td>
		</tr>
	</table>
</div>
<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />