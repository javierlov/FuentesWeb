<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


if ((isset($_REQUEST["buscar"])) and (!isset($_REQUEST["firstcall"])))
	if ($showProcessMsg)
		FirstCallPageCode();

$usuario = "";
if (isset($_REQUEST["Usuario"]))
	$usuario = $_REQUEST["Usuario"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2_D_";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$sb = false;
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "T")
		$sb = true;
?>
<form action="index.php?pageid=10" id="formNovedades" method="post" name="formNovedades" target="iframeProcesando">
<input id="buscar" name="buscar" type="hidden" value="yes">
<table border="0" width="770" id="table1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#C0C0C0" height="25">
		<td class="FormLabelBlanco" width="160" bgcolor="#C0C0C0">&nbsp;Seleccionar Usuario</td>
		<td width="432"><select class="Combo" id="Usuario" name="Usuario" onChange="formNovedades.submit()"></select></td>
		<td align="center" width="196"><input class="BotonBlanco" name="btnAgregarNovedad" type="button" value="AGREGAR NOVEDAD" onClick="window.location.href='/index.php?pageid=10&page=novedad.php&usuario='+document.getElementById('Usuario').value"></td>
	</tr>
	<tr>
		<td width="143">&nbsp;</td>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array(":idusuario" => $usuario);
	$where = " AND hn_idusuario = :idusuario";

	$sql =
		"SELECT ¿hn_id?, TO_CHAR(NVL(hn_fechamodif, hn_fechaalta), 'dd/mm/yyyy') ¿fechamodificacion?,
						DECODE(hn_tipomovimiento, 'A', 'Ingreso', DECODE(hn_tipomovimiento, 'B', 'Egreso', 'Pase de sector')) ¿movimiento?,
						se1.se_descripcion ¿sectordesde?, se2.se_descripcion ¿sectorhasta?, ¿hn_fechabaja?
			 FROM rrhh.rhn_novedades, use_usuarios useu, computos.cse_sector se1, computos.cse_sector se2
		  WHERE hn_idusuario = useu.se_id
				AND hn_idsectordesde = se1.se_id(+)
				AND hn_idsectorhasta = se2.se_id(+) _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "/index.php?pageid=10&page=novedad.php", "GridFirstColumn"));
	$grilla->addColumn(new Column("Fecha Modificación"));
	$grilla->addColumn(new Column("Tipo Movimiento"));
	$grilla->addColumn(new Column("Sector Desde"));
	$grilla->addColumn(new Column("Sector Hasta"));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja(6, $sb, false);
	$grilla->setColsSeparator(true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("hn_fechabaja");
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setSql($sql);
	$grilla->Draw();
}
?>
</form>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "Usuario";
$RCparams = array();
$RCquery =
	"SELECT se_id ID, se_nombre detalle
     FROM use_usuarios
    WHERE (se_fechabaja IS NULL
        OR se_fechabaja >=(SYSDATE - 90))
      AND se_usuariogenerico = 'N'
 ORDER BY se_buscanombre";
$RCselectedItem = $usuario;
FillCombo();
?>
  document.getElementById('Usuario').focus();
</script>