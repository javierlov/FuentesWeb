<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);

set_time_limit(240);

$showProcessMsg = true;

$fechaDesde = incMonths(date("d/m/Y"), -12);
if (isset($_REQUEST["fechaDesde"]))
	$fechaDesde = $_REQUEST["fechaDesde"];

$fechaHasta = date("d/m/Y");
if (isset($_REQUEST["fechaHasta"]))
	$fechaHasta = $_REQUEST["fechaHasta"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2_D_";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$params = array(":id" => $_SESSION["entidad"]);
$sql = 
	"SELECT en_codbanco || ' - ' || en_nombre
		 FROM xen_entidad
		WHERE en_id = :id";
$entidad = valorSql($sql, "", $params);
?>
<script src="/modules/usuarios_registrados/agentes_comerciales/comisiones/js/comisiones.js" type="text/javascript"></script>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/comisiones/facturas" id="formConsultaFacturas" method="post" name="formConsultaFacturas" onSubmit="return ValidarForm(formConsultaFacturas)">
	<input id="buscar" name="buscar" type="hidden" value="s" />
	<input id="solapa" name="solapa" type="hidden" value="f" />
	<div class="TituloSeccion" style="display:block; width:730px;">Facturas</div>
	<div class="SubtituloSeccion" style="color:#00a4e4; margin-top:8px;"><?= $entidad?></div>
	<div class="ContenidoSeccion" style="margin-top:8px;">
		<div>
			<label class="ContenidoSeccion">Fecha Desde</label>
			<input autofocus id="fechaDesde" maxlength="10" name="fechaDesde" style="width:64px;" title="Fecha Desde" type="text" validarFecha="true" value="<?= $fechaDesde?>">
			<input class="botonFecha" id="btnFechaDesde" name="btnFechaDesde" style="vertical-align:-4px;" type="button" value="">
			<label class="ContenidoSeccion">(dd/mm/yyyy)</label>
		</div>
		<div style="margin-top:4px;">
			<label class="ContenidoSeccion">Fecha Hasta</label>
			<input id="fechaHasta" maxlength="10" name="fechaHasta" style="margin-left:4px; width:64px;" title="Fecha Hasta" type="text" validarFecha="true" value="<?= $fechaHasta?>">
			<input class="botonFecha" id="btnFechaHasta" name="btnFechaHasta" style="vertical-align:-4px;" type="button" value="">
			<label class="ContenidoSeccion">(dd/mm/yyyy)</label>
		</div>
		<p>
			<input class="btnBuscar" type="submit" value="" />
			<input class="btnExcel" id="btnExportar" style="display:none; margin-left:40px;" title="Exportar grilla a Excel" type="button" value="" onClick="exportarGrilla()" />
		</p>
	</div>
	<div align="left" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:20px; margin-top:8px; overflow:auto; width:712px;">
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "s")) {
	$params = array(":identidad" => $_SESSION["entidad"]);
	$where1 = "";
	$where2 = "";
	$where3 = " WHERE 1 = 1";

	if ($_SESSION["vendedor"] != "") {
		$params[":idvendedor"] = $_SESSION["vendedor"];
		$where1.= " AND ve_id = :idvendedor";
		$where2.= " AND ve_id = :idvendedor";
	}

	if ($fechaDesde != "") {
		$params[":fechadesde"] = $fechaDesde;
		$where1.= " AND fc_fechafactura >= TO_DATE(:fechadesde, 'dd/mm/yyyy')";
		$where2.= " AND fc_fechafactura >= TO_DATE(:fechadesde, 'dd/mm/yyyy')";
	}

	if ($fechaHasta != "") {
		$params[":fechahasta"] = $fechaHasta;
		$where1.= " AND fc_fechafactura <= TO_DATE(:fechahasta, 'dd/mm/yyyy')";
		$where2.= " AND fc_fechafactura <= TO_DATE(:fechahasta, 'dd/mm/yyyy')";
	}

	$sql =
		"SELECT ¿ordenpago?,
						¿factura?,
						¿fecha?,
						fecharecepcion ¿\"fecha recepción\"?,
						TO_CHAR(importe, '$9,999,999,990.00') ¿importe?,
						ordenpago ¿\"orden pago\"?,
						fechaop ¿\"fecha op\"?,
						situacion ¿\"situación\"?,
						fechasituacion ¿\"fecha situación\"?,
						identidad ¿NO_identidad?
			 FROM (SELECT fc_facturatipo || '-' || fc_facturanro factura, fc_fechafactura fecha, fc_fecharecepfact fecharecepcion, fc_importe importe, ce_ordenpago ordenpago, ce_fechaop fechaop,
										tb_descripcion situacion, ce_fechasituacion fechasituacion, ev_identidad identidad
							 FROM art.ctb_tablas, rce_chequeemitido, xen_entidad, xve_vendedor, xev_entidadvendedor, xfc_factura
							WHERE en_id = fc_identidad
								AND ev_id = fc_identidadvend
								AND ve_id = ev_idvendedor
								AND fc_idchequeemitido = ce_id(+)
								AND tb_codigo(+) = ce_situacion
								AND tb_clave(+) = 'SITCH'
								AND ce_estado(+) = '01'
								AND en_modoliq <> '02'
								AND fc_fechafactura > TO_DATE('01/02/2010', 'dd/mm/yyyy')
								AND en_id = :identidad
								AND fc_fechabaja IS NULL _EXC1_
					UNION ALL
						 SELECT fc_facturatipo || '-' || fc_facturanro factura, fc_fechafactura fecha, fc_fecharecepfact fecharecepcion, fc_importe importe, ce_ordenpago ordenpago, ce_fechaop fechaop,
										tb_descripcion situacion, ce_fechasituacion fechasituacion, ev_identidad identidad
							 FROM art.ctb_tablas, rce_chequeemitido, xen_entidad, xve_vendedor, xev_entidadvendedor, xfc_factura
							WHERE ev_id = fc_identidadvend
								AND en_modoliq = '02'
								AND ve_id = ev_idvendedor
								AND en_id = ev_identidad
								AND fc_idchequeemitido = ce_id(+)
								AND tb_codigo(+) = ce_situacion
								AND tb_clave(+) = 'SITCH'
								AND ce_estado(+) = '01'
								AND fc_fechafactura > TO_DATE('01/02/2010', 'dd/mm/yyyy')
								AND en_id = :identidad
								AND fc_fechabaja IS NULL _EXC2_) _EXC3_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("OP", 0, true, false, -1, "btnPdf ", "/modules/usuarios_registrados/agentes_comerciales/comisiones/mostrar_orden_pago.php", "", -1, true, -1, "Imprimir Orden de Pago"));
	$grilla->addColumn(new Column("Factura"));
	$grilla->addColumn(new Column("Fecha", -1, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Fecha Recepción", -1, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Importe", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Orden Pago", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Fecha OP", -1, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Situación"));
	$grilla->addColumn(new Column("Fecha Situación", -1, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("", 0, false));
	$grilla->setExtraConditions(array($where1, $where2, $where3));
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);
	$grilla->setTableStyle("GridTableCiiu");
	$grilla->setUseTmpIframe(true);
	$grilla->Draw();

	$_SESSION["fieldsAlignment"] = array("left", "center", "center", "right", "right", "center", "left", "center");
	$_SESSION["sqlFacturas"] = $grilla->getSqlFinal(true);
	echo "<script type='text/javascript'>";
	echo "document.getElementById('btnExportar').style.display = 'inline';";
	echo "</script>";
}
?>
	</div>
	<div align="left" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	<a href="/comisiones"><input class="btnVolver" type="button" value="" /></a>
</form>
<script type="text/javascript">
	function CopyContent() {
		try {
			window.parent.document.getElementById('divContentGrid').innerHTML = document.getElementById('divContentGrid').innerHTML;
		}
		catch(err) {
			//
		}
<?
if ($showProcessMsg) {
?>
	if (window.parent.document.getElementById('originalGrid') != null)
		window.parent.document.getElementById('originalGrid').style.display = 'block';
	window.parent.document.getElementById('divProcesando').style.display = 'none';
<?
}
?>
	}

	CopyContent();

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