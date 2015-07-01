<?
validarSesion(isset($_SESSION["isAgenteComercial"]));

// Chequeo que tenga permiso para entrar a ver el estado de cuenta..
$params = array(":id" => $_SESSION["idUsuario"]);
$sql =
	"SELECT uw_estadocuenta
		 FROM auw_usuarioweb
		WHERE uw_id = :id";
if (valorSql($sql, "", $params) != 1) {
	echo "Usted no tiene habilitada esta opción.";
	exit;
}

if (!isset($_SESSION["BUSQUEDA_ESTADO_CUENTA"]))
	$_SESSION["BUSQUEDA_ESTADO_CUENTA"] = array("buscar" => "N",
																							"contrato" => "",
																							"cuit" => "",
																							"ob" => "1_D_",
																							"pagina" => 1,
																							"razonSocial" => "");
?>
<link rel="stylesheet" href="/modules/estado_cuenta/css/grid.css" type="text/css" />
<script type="text/javascript">
	function buscar() {
		resultado = ValidarForm(formEstadoCuenta);
		if (resultado) {
			document.getElementById('divContentGrid').style.display = 'none';
			document.getElementById('divProcesando').style.display = 'block';
		}
	}
</script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/estado_cuenta/index_busqueda.php" id="formEstadoCuenta" method="post" name="formEstadoCuenta" target="iframeProcesando" onSubmit="return buscar()">
	<div class="TituloSeccion" style="width:736px;">Estado de Cuenta</div>
	<div class="ContenidoSeccion" style="margin-top:16px;">
		<p>
			<label>Contrato</label>
			<input id="contrato" maxlength="8" name="contrato" style="margin-left:22px; width:88px;" title="Contrato" type="text" validarEntero="true" value="<?= $_SESSION["BUSQUEDA_ESTADO_CUENTA"]["contrato"]?>" />
		</p>
		<p>
			<label>C.U.I.T.</label>
			<input id="cuit" maxlength="13" name="cuit" style="margin-left:28px; width:88px;" title="C.U.I.T." type="text" validarCuit="true" value="<?= $_SESSION["BUSQUEDA_ESTADO_CUENTA"]["cuit"]?>" />
		</p>
		<p>
			<label>Razón Social</label>
			<input id="razonSocial" maxlength="60" name="razonSocial" style="width:440px;" type="text" value="<?= $_SESSION["BUSQUEDA_ESTADO_CUENTA"]["razonSocial"]?>" />
		</p>
		<p>
			<input class="btnBuscar" type="submit" value="" />
		</p>
		<div>Utilice el Número de Solicitud, Fecha Inicio/Fin, C.U.I.T. o Razón Social para buscar en el listado de sus solicitudes de cotización. Si no especifica ningún filtro, la búsqueda traerá la lista completa.</div>
	</div>
</form>
<div id="divContentGrid" name="divContentGrid" style="margin-top:8px;"></div>
<div id="divProcesando" name="divProcesando" style="display:none; margin-left:280px; margin-top:16px;"><img align="left" border="0" src="/images/waiting.gif" title="Espere por favor..." /></div>
<script type="text/javascript">
<?
if ($_SESSION["BUSQUEDA_ESTADO_CUENTA"]["buscar"] == "S") {
?>
	buscar();
	document.getElementById('formEstadoCuenta').submit();
<?
}
?>

	document.getElementById('contrato').focus();
</script>