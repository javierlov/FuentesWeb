<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);

SetDateFormatOracle("DD/MM/YYYY");

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT lc_fechaliq
		 FROM xlc_liqcomision
		WHERE lc_id = :id";
$titulo = "Liquidación Nº ".$_REQUEST["id"]." del ".ValorSql($sql, "", $params);
?>
<html>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div id="divContentGrid" name="divContentGrid">
<?
$where = " WHERE 1 = 1";
$params = array(":idliquidacion" => $_REQUEST["id"]);
$sql =
	"SELECT ¿id?,
					¿tipo?,
					¿provincia?,
					¿comprobante?,
					TRIM(TO_CHAR(retencion, '$9,999,999,990.00')) ¿\"Retención\"?,
					identidad ¿NO_identidad?
		 FROM (SELECT 'IB_' || pv_codigo || '_' || lc_id id, 'Ingresos Brutos' tipo, INITCAP(pv_descripcion) provincia, il_comprobante comprobante, il_retencion retencion, lc_identidad identidad
						 FROM art.cpv_provincias, xil_ibliquidacion, xlc_liqcomision
						WHERE pv_codigo = il_provincia
							AND il_idliquidacion = lc_id
							AND il_retencion <> 0
							AND lc_id = :idliquidacion
				UNION ALL
					 SELECT 'OS_0_' || TO_CHAR(lc_id) id, 'Obra Social' tipo, NULL provincia, NULL comprobante, lc_obrasocial retencion, lc_identidad identidad
						 FROM xlc_liqcomision
						WHERE lc_id = :idliquidacion
				UNION ALL
					 SELECT 'G_0_' || TO_CHAR(lc_id) id, tb_descripcion tipo, NULL provincia, rs_numero comprobante, rs_retencion retencion, lc_identidad identidad
						 FROM art.ctb_tablas imp, xrs_retencionsicore, xlc_liqcomision
						WHERE imp.tb_codigo = rs_impuesto
							AND imp.tb_clave = 'CSIMP'
							AND rs_idliquidacion = lc_id
							AND lc_id = :idliquidacion) _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("I", 0, true, false, -1, "btnPdf", "/modules/usuarios_registrados/agentes_comerciales/comisiones/imprimir_retencion.php", "", -1, true, -1, "Imprimir"));
$grilla->addColumn(new Column("Tipo"));
$grilla->addColumn(new Column("Provincia"));
$grilla->addColumn(new Column("Comprobante", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Retención", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("", 0, false));
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->setUseTmpIframe(true);
$grilla->Draw();

$_SESSION["fieldsAlignment"] = array("left", "left", "right", "right");
$_SESSION["sqlLiquidacionesRetenciones"] = $grilla->getSqlFinal(true);
?>
		</div>
		<script type="text/javascript">
			window.parent.document.getElementById('tituloRetenciones').innerHTML = '<?= $titulo?>';
			window.parent.document.getElementById('divContentGridRetenciones').innerHTML = document.getElementById('divContentGrid').innerHTML;
			window.parent.document.getElementById('btnExportarRetenciones').style.display = 'inline';
			window.parent.document.getElementById('originalGrid').style.display = 'block';
			window.parent.document.getElementById('divProcesando').style.display = 'none';
		</script>
	</body>
</html>