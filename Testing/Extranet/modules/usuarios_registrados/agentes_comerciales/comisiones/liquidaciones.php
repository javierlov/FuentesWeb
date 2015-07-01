<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);

SetDateFormatOracle("DD/MM/YYYY");
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

$ob = "3_D_";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$solapa = "p";
if (isset($_REQUEST["solapa"]))
	$solapa = $_REQUEST["solapa"];

$params = array(":id" => $_SESSION["entidad"]);
$sql = 
	"SELECT en_codbanco || ' - ' || en_nombre
		 FROM xen_entidad
		WHERE en_id = :id";
$entidad = ValorSql($sql, "", $params);
?>
<script src="/modules/usuarios_registrados/agentes_comerciales/comisiones/js/comisiones.js" type="text/javascript"></script>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div class="TituloSeccion" style="display:block; width:730px;">Liquidaciones</div>
<div class="SubtituloSeccion" style="color:#0f539c; margin-top:8px;"><?= $entidad?></div>
<div class="ContenidoSeccion" style="margin-top:8px; width:712px;">
	<div style="background-color:#ddd; padding-bottom:2px; padding-top:2px;">
		<label class="ContenidoSeccion" id="labelPendientes" style="background-color:#0f539c; color:#000; cursor:hand; padding-bottom:2px; padding-top:4px;" onClick="cambiarSolapa('p')" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)">PENDIENTES</label>
		<label class="ContenidoSeccion" id="labelLiquidaciones" style="color:#000; cursor:hand; padding-bottom:2px; padding-top:4px;" onClick="cambiarSolapa('l')" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)">LIQUIDACIONES</label>
		<label class="ContenidoSeccion" id="labelMovimientos" style="color:#000; cursor:hand; display:none; padding-bottom:2px; padding-top:4px;" onClick="cambiarSolapa('m')" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)">MOVIMIENTOS</label>
		<label class="ContenidoSeccion" id="labelVendedores" style="color:#000; cursor:hand; display:none; padding-bottom:2px; padding-top:4px;" onClick="cambiarSolapa('v')" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)">VENDEDORES</label>
		<label class="ContenidoSeccion" id="labelRetenciones" style="color:#000; cursor:hand; display:none; padding-bottom:2px; padding-top:4px;" onClick="cambiarSolapa('r')" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)">RETENCIONES</label>
	</div>


	<div id="divPendientes" style="border:2px solid #0f539c; display:none;">
		<p style="margin-left:8px; margin-top:8px;">
			<span class="SubtituloSeccion" id="tituloPendientes" style="color:#00f; font-size:14px; margin-top:8px;">
				<img border="0" src="/images/loading.gif" style="margin-right:8px; vertical-align:-4px;" />
				<span style="font-size:11px;">Calculando el monto total a facturar...</span>
			</span>
			<input class="btnExcel" id="btnExportarPendientes" style="display:none; margin-left:200px; vertical-align:-4px;" title="Exportar grilla a Excel" type="button" value="" onClick="exportarGrilla()" />
		</p>
		<div align="left" id="divContentGridPendientes" name="divContentGridPendientes" style="margin-bottom:4px; margin-left:20px; margin-top:8px; overflow:auto; width:680px;"></div>
		<div align="center" id="divProcesandoPendientes" name="divProcesandoPendientes" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; margin-bottom:4px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<div style="margin-left:8px;">
			<span style="color:#f00; font-weight:bold;">IMPORTANTE:</span>
			<br />
			<span>
				Recuerde, si el saldo es inferior a $100 no está en condiciones de ser facturados.<br />
				Si el monto total corresponde a mas de una liquidación, indicar los períodos en la factura.
			</span>
		</div>
	</div>


	<div id="divLiquidaciones" style="border:2px solid #0f539c;">
		<form action="/index.php?pageid=87" id="formConsultaLiquidaciones" method="post" name="formConsultaLiquidaciones" onSubmit="return ValidarForm(formConsultaLiquidaciones)">
			<input id="buscar" name="buscar" type="hidden" value="s" />
			<input id="solapa" name="solapa" type="hidden" value="<?= $solapa?>" />
			<div style="margin-top:4px;">
				<label class="ContenidoSeccion">Fecha Desde</label>
				<input id="fechaDesde" maxlength="10" name="fechaDesde" style="width:64px;" title="Fecha Desde" type="text" validarFecha="true" value="<?= $fechaDesde?>">
				<input class="botonFecha" id="btnFechaDesde" name="btnFechaDesde" style="vertical-align:-4px;" type="button" value="">
				<label class="ContenidoSeccion">(dd/mm/yyyy)</label>
			</div>
			<div style="margin-top:4px;">
				<label class="ContenidoSeccion">Fecha Hasta</label>
				<input id="fechaHasta" maxlength="10" name="fechaHasta" style="margin-left:4px; width:64px;" title="Fecha Hasta" type="text" validarFecha="true" value="<?= $fechaHasta?>">
				<input class="botonFecha" id="btnFechaHasta" name="btnFechaHasta" style="vertical-align:-4px;" type="button" value="">
				<label class="ContenidoSeccion">(dd/mm/yyyy)</label>
			</div>
			<p style="margin-left:8px; margin-top:8px;">
				<input class="btnBuscar" type="submit" value="" />
				<input class="btnExcel" id="btnExportarLiquidaciones" style="display:none; margin-left:576px;" title="Exportar grilla a Excel" type="button" value="" onClick="exportarGrilla()" />
			</p>
			<div align="left" id="divContentGrid" name="divContentGrid" style="margin-bottom:4px; margin-left:20px; margin-top:8px; overflow:auto; width:680px;">
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "s")) {
	$params = array(":identidad" => $_SESSION["entidad"]);
	$where1 = "";
	$where2 = "";
	$where3 = " WHERE 1 = 1";

	if ($fechaDesde != "") {
		$params[":fechacierredesde"] = $fechaDesde;
		$where1.= " AND lc_fechaliq >= TO_DATE(:fechacierredesde, 'DD/MM/YYYY')";
		$where2.= " AND lc_fechaliq >= TO_DATE(:fechacierredesde, 'DD/MM/YYYY')";
	}

	if ($fechaHasta != "") {
		$params[":fechacierrehasta"] = $fechaHasta;
		$where1.= " AND lc_fechaliq <= TO_DATE(:fechacierrehasta, 'DD/MM/YYYY')";
		$where2.= " AND lc_fechaliq <= TO_DATE(:fechacierrehasta, 'DD/MM/YYYY')";
	}

	$sql =
		"SELECT id ¿NO_id?,
						id ¿\"Liquidación\"?,
						fechacierre ¿\"Fecha Liquidación\"?,
						TO_CHAR(cobradoneto, '$9,999,999,990.00') ¿\"Cobrado Neto de Impuestos\"?,
						TO_CHAR(comision, '$9,999,999,990.00') ¿\"Monto Liquidado\"?,
						TO_CHAR(iva, '$9,999,999,990.00') ¿\"IVA\"?,
						TO_CHAR(monto_a_facturar, '$9,999,999,990.00') ¿\"Monto Facturar\"?,
						TO_CHAR(obrasocial, '$9,999,999,990.00') ¿\"Ret. OS\"?,
						TO_CHAR(ingbrutos, '$9,999,999,990.00') ¿\"Ret. IB\"?,
						TO_CHAR(ganancias, '$9,999,999,990.00') ¿\"Ret. Gcias\"?,
						TO_CHAR(retiva, '$9,999,999,990.00') ¿\"Ret. IVA\"?,
						TO_CHAR(comisionneta, '$9,999,999,990.00') ¿neto?,
						abs_monto_a_facturar ¿NO_abs_monto_a_facturar?,
						id ¿NO_id2?,
						id ¿NO_id3?,
						identidad ¿NO_identidad?
			 FROM (SELECT DISTINCT lc_id id, lc_fechaliq fechacierre, lc_cobradoneto cobradoneto, lc_comision comision, lc_iva iva, lc_obrasocial obrasocial, lc_ingbrutos ingbrutos,
														 lc_comisionneta comisionneta, lc_ganancias ganancias, lc_retiva retiva, art.comision.get_montosinaplicarliquidacion(lc_id) monto_a_facturar,
														 ABS(art.comision.get_montosinaplicarliquidacion(lc_id)) abs_monto_a_facturar, lc_id id2, lc_id id3, en_id identidad
												FROM xen_entidad, xlc_liqcomision
											 WHERE lc_identidad = en_id
												 AND lc_comision <> 0
												 AND en_id = :identidad
												 AND lc_estado = 'A' _EXC1_
									 UNION ALL
						 SELECT DISTINCT lc_id id, lc_fechaliq fechacierre, lc_cobradoneto cobradoneto, lc_comision comision, lc_iva iva, lc_obrasocial obrasocial, lc_ingbrutos ingbrutos,
														 lc_comisionneta comisionneta, lc_ganancias ganancias, lc_retiva retiva, art.comision.get_montosinaplicarliquidacion(lc_id) monto_a_facturar,
														 ABS(art.comision.get_montosinaplicarliquidacion(lc_id)) abs_monto_a_facturar, lc_id id2, lc_id id3, en_id identidad
												FROM xen_entidad, xve_vendedor, xev_entidadvendedor, xlc_liqcomision
											 WHERE lc_identidadvendedor = ev_id
												 AND ev_idvendedor = ve_id
												 AND en_id = ev_identidad
												 AND lc_comision <> 0
												 AND (en_id = :identidad OR ve_id IN (SELECT ve_id
																																FROM xev_entidadvendedor, xve_vendedor
																															 WHERE ve_id = ev_idvendedor
																																 AND ev_identidad = :identidad
																																 AND ve_vendedor = '0'))
												 AND lc_estado = 'A' _EXC2_) _EXC3_";
	$grilla = new Grid(15, 7);
	$grilla->addColumn(new Column("Mov", 0, true, false, -1, "btnLink ", "/modules/usuarios_registrados/agentes_comerciales/comisiones/cambiar_solapa.php?s=m", "", -1, true, -1, "Movimientos"));
	$grilla->addColumn(new Column("Liquidación", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Fecha Liquidación", -1, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Cobrado Neto de Impuestos", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Monto Liquidado", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("IVA", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Monto Facturar", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Ret. OS", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Ret. IB", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Ret. Gcias", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Ret. IVA", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Neto", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("", 0, false));
	$grilla->addColumn(new Column("V", 0, true, false, -1, "btnLink ", "/modules/usuarios_registrados/agentes_comerciales/comisiones/cambiar_solapa.php?s=v", "", -1, true, -1, "Detalle de Vendedores"));
	$grilla->addColumn(new Column("R", 0, true, false, -1, "btnLink ", "/modules/usuarios_registrados/agentes_comerciales/comisiones/cambiar_solapa.php?s=r", "", -1, true, -1, "Detalle de Retenciones"));
	$grilla->addColumn(new Column("", 0, false));
	$grilla->setExtraConditions(array($where1, $where2, $where3));
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);
	$grilla->setTableStyle("GridTableCiiu");
	$grilla->setUseTmpIframe(true);
	$grilla->Draw();

	$_SESSION["fieldsAlignment"] = array("", "right", "center", "right", "right", "right", "right", "right", "right", "right", "right", "right");
	$_SESSION["sqlLiquidaciones"] = $grilla->getSqlFinal(true);
	echo "<script type='text/javascript'>";
	echo "document.getElementById('btnExportarLiquidaciones').style.display = 'inline';";
	echo "</script>";
}
?>
			</div>
			<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; margin-bottom:4px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		</form>
	</div>


	<div id="divMovimientos" style="border:2px solid #0f539c; display:none;">
		<p style="margin-left:8px; margin-top:8px;">
			<span class="SubtituloSeccion" id="tituloMovimientos" style="margin-top:8px;"></span>
			<input class="btnExcel" id="btnExportarMovimientos" style="display:none; margin-left:400px; vertical-align:-4px;" title="Exportar grilla a Excel" type="button" value="" onClick="exportarGrilla()" />
		</p>
		<div class="SubtituloSeccion" id="tituloMovimientosVendedor" style="margin-left:8px; margin-top:8px;"></div>
		<div align="left" id="divContentGridMovimientos" name="divContentGridMovimientos" style="margin-bottom:4px; margin-left:20px; margin-top:8px; overflow:auto; width:680px;"></div>
		<div align="center" id="divProcesandoMovimientos" name="divProcesandoMovimientos" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; margin-bottom:4px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	</div>


	<div id="divVendedores" style="border:2px solid #0f539c; display:none;">
		<p style="margin-left:8px; margin-top:8px;">
			<span class="SubtituloSeccion" id="tituloVendedores" style="margin-top:8px;"></span>
			<input class="btnExcel" id="btnExportarVendedores" style="display:none; margin-left:400px; vertical-align:-4px;" title="Exportar grilla a Excel" type="button" value="" onClick="exportarGrilla()" />
		</p>
		<div align="left" id="divContentGridVendedores" name="divContentGridVendedores" style="margin-bottom:4px; margin-left:20px; margin-top:8px; overflow:auto; width:680px;"></div>
		<div align="center" id="divProcesandoVendedores" name="divProcesandoVendedores" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; margin-bottom:4px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	</div>


	<div id="divRetenciones" style="border:2px solid #0f539c; display:none;">
		<p style="margin-left:8px; margin-top:8px;">
			<span class="SubtituloSeccion" id="tituloRetenciones" style="margin-top:8px;"></span>
			<input class="btnExcel" id="btnExportarRetenciones" style="display:none; margin-left:400px; vertical-align:-4px;" title="Exportar grilla a Excel" type="button" value="" onClick="exportarGrilla()" />
		</p>
		<div align="left" id="divContentGridRetenciones" name="divContentGridRetenciones" style="margin-bottom:4px; margin-left:20px; margin-top:8px; overflow:auto; width:680px;"></div>
		<div align="center" id="divProcesandoRetenciones" name="divProcesandoRetenciones" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; margin-bottom:4px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	</div>
</div>
<span class="ContenidoSeccion" id="spanLeyenda">Los valores en el Monto a Facturar incluyen I.V.A.</span>
<a href="/index.php?pageid=85"><input class="btnVolver" type="button" value="" /></a>
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
	with (window.parent.document) {
		if (getElementById('originalGrid') != null)
			getElementById('originalGrid').style.display = 'block';

		getElementById('divProcesando').style.display = 'none';

		if (getElementById('divGridEspera') != null) {
			getElementById('divGridEspera').style.display = 'none';
			getElementById('divGridEsperaTexto').style.display = 'none';
		}
	}
<?
}
?>
	}

	CopyContent();

	Calendar.setup (
		{
			inputField: "fechaDesde",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaDesde"
		}
	);

	Calendar.setup (
		{
			inputField: "fechaHasta",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaHasta"
		}
	);

<?
if ($solapa == "l") {
?>
	iframeProcesando.location.href = '/modules/usuarios_registrados/agentes_comerciales/comisiones/cambiar_solapa.php?s=p&id=-1&s2=l';
<?
}

if ($solapa == "p") {
?>
	iframeProcesando.location.href = '/modules/usuarios_registrados/agentes_comerciales/comisiones/cambiar_solapa.php?s=p&id=-1';
<?
}
?>

	try {
		with (document)
			if (getElementById('solapa').value == 'l')
				document.getElementById('fechaDesde').focus();
	}
	catch(err) {
		//
	}
</script>