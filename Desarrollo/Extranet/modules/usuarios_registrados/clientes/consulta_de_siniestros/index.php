<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once("index_combos.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 64));


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

$sql =
	"SELECT 1
		 FROM comunes.cev_empresavip JOIN aco_contrato ON co_idempresa = ev_idempresa
		WHERE SYSDATE BETWEEN ev_vigenciadesde AND NVL(ev_vigenciahasta, TO_DATE('31/12/2999', 'dd/mm/yyyy'))
			AND co_contrato = :contrato";
$esEmpresaVip = existeSql($sql, array(":contrato" => $_SESSION["contrato"]));
?>
<script src="/modules/usuarios_registrados/clientes/js/consulta_siniestros.js" type="text/javascript"></script>
<script type="text/javascript">
	function submitForm() {
		resultado = ValidarForm(formConsultaSiniestros);
		if (resultado) {
			document.getElementById('divContentGrid').style.display = 'none';
			document.getElementById('divProcesando').style.display = 'block';
		}
		return resultado;
	}
</script>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/consulta_de_siniestros/index_busqueda.php" id="formConsultaSiniestros" method="post" name="formConsultaSiniestros" target="iframeProcesando" onSubmit="return submitForm()">
	<div class="TituloSeccion" style="display:block; width:730px;">Consulta de Siniestros</div>
	<div class="ContenidoSeccion" align="right" style="margin-top:5px;"><i>>> <a href="/consulta-siniestros/terminos-y-condiciones">Términos y Condiciones de uso</a></i></div>
	<div class="ContenidoSeccion" style="margin-top:20px;">Seleccione período y/o C.U.I.L./Nombre y consulte la situación de los siniestros del personal de su empresa.</div>
	<div>
		<div style="margin-top:16px;">
			<label class="ContenidoSeccion" style="margin-left:12px; margin-right:-8px;">Fecha</label>
			<?= $comboFecha->draw();?>
			<label class="ContenidoSeccion">Fecha Desde</label>
			<input id="fechaDesde" maxlength="10" name="fechaDesde" style="margin-left:-8px; width:64px;" title="Fecha Desde" type="text" validarFecha="true" value="">
			<input class="botonFecha" id="btnFechaDesde" name="btnFechaDesde" style="vertical-align:-4px;" type="button" value="">
			<label class="ContenidoSeccion">Fecha Hasta</label>
			<input id="fechaHasta" maxlength="10" name="fechaHasta" style="margin-left:-8px; width:64px;" title="Fecha Hasta" type="text" validarFecha="true" value="">
			<input class="botonFecha" id="btnFechaHasta" name="btnFechaHasta" style="vertical-align:-4px;" type="button" value="">
		</div>
		<div style="margin-top:4px;">
			<label class="ContenidoSeccion">Nombre</label>
			<input id="nombre" name="nombre" style="margin-left:-8px; width:240px;" type="text" value="">
			<label class="ContenidoSeccion">C.U.I.L.</label>
			<input id="cuil" name="cuil" maxlength="11" style="margin-left:-8px; width:80px;" title="CUIL" type="text" validarCuit="true" value="">
		</div>
		<p>
			<input class="btnBuscar" type="submit" value="" />
			<input class="btnExportar" id="btnExportar" style="display:none; margin-left:40px;" title="Exportar grilla a Excel" type="button" value="" onClick="exportarGrilla()" />
		</p>
	</div>
	<div align="left" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:20px; margin-top:8px; overflow:auto; width:<?= ($esEmpresaVip)?2000:712?>px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	<div class="ContenidoSeccion" id="divObservacion" style="display:none; margin-top:24px;">(*) OBSERVACIÓN: el alta médica puede darse: en forma inmediata, por finalización o abandono del tratamiento, o por la muerte (por causas laborales o inculpables) del paciente. En los casos en que haya una sugerencia de recalificación profesional en el alta medica, esto no implica el alta laboral o sea el reinicio laboral. Para dudas o consultas sobre este tema, comuníquese con nuestro sector de Recalificación Profesional.<br />&nbsp;</div>
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
</form>
<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaDesde",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaDesde"
	});

	Calendar.setup ({
		inputField: "fechaHasta",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaHasta"
	});
</script>