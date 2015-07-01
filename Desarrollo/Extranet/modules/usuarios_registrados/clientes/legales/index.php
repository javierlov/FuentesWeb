<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once("index_combos.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 33));


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
<style>
	.gridEmpty {background-image:url(/images/grid_not_found.gif); border:none; height:50px; width:237px;}
</style>
<script src="/modules/usuarios_registrados/clientes/js/legales.js" type="text/javascript"></script>
<script type="text/javascript">
	function submitForm() {
		resultado = ValidarForm(formLegales);
		if (resultado) {
			document.getElementById('divContentGrid').style.display = 'none';
			document.getElementById('divProcesando').style.display = 'block';
		}
		return resultado;
	}
</script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/legales/index_busqueda.php" id="formLegales" method="post" name="formLegales" target="iframeProcesando" onSubmit="return submitForm()">
	<div class="TituloSeccion" style="display:block; width:730px;">Legales</div>
<!--	<div class="ContenidoSeccion" align=right style="margin-top:5px;"><i>>> <a href="">Términos y Condiciones de uso</a></i></div>-->
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<div style="margin-left:90px; margin-top:4px;">
			<label>C.U.I.L.</label>
			<input autofocus id="cuil" maxlength="13" name="cuil" style="width:80px;" title="CUIL" type="text" validarCuit="true" value="" />
		</div>
		<div style="margin-top:4px;">
			<label>Nombre del Accidentado</label>
			<input id="nombreAccidentado" name="nombreAccidentado" style="text-transform:uppercase; width:240px;" type="text" value="" />
		</div>
		<div style="margin-left:41px; margin-top:4px;">
			<label>Estado del Juicio</label>
			<?= $comboEstadoJuicio->draw();?>
		</div>
		<div style="margin-left:17px; margin-top:4px;">
			<label>Fecha de Notificación</label>
			<input id="fechaNotificacion" maxlength="10" name="fechaNotificacion" style="width:64px;" title="Fecha de Notificación" type="text" validarFecha="true" value="">
			<input class="botonFecha" id="btnFechaNotificacion" name="btnFechaNotificacion" style="vertical-align:-4px;" type="button" value="">
		</div>
		<p style="margin-left:0px;">
			<input class="btnBuscar" type="submit" value="" />
			<input class="btnExportar" id="btnExportar" style="display:none; margin-left:40px;" title="Exportar grilla a Excel" type="button" value="" onClick="exportarGrilla()" />
		</p>
		<p>
			<i>Utilice la C.U.I.L., Nombre del Accidentado, Estado del Juicio y Fecha de Notificación para buscar. Si no especifica ningún filtro, la búsqueda traerá todos los datos y puede demorar unos minutos.</i>
		</p>
	</div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:10px; margin-top:8px; overflow:auto; width:720px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
</form>
<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaNotificacion",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaNotificacion"
	});
</script>