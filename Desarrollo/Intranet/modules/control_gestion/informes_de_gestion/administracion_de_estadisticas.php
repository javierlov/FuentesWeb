<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


$showProcessMsg = false;

$fechaDesde = "";
if (isset($_REQUEST["FechaDesde"]))
	$fechaDesde = $_REQUEST["FechaDesde"];

$fechaHasta = "";
if (isset($_REQUEST["FechaHasta"]))
	$fechaHasta = $_REQUEST["FechaHasta"];

$tema = -1;
if (isset($_REQUEST["tema"]))
	$tema = $_REQUEST["tema"];

$titulo = -1;
if (isset($_REQUEST["titulo"]))
	$titulo = $_REQUEST["titulo"];

$usuario = -1;
if (isset($_REQUEST["Usuario"]))
	$usuario = $_REQUEST["Usuario"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "1_D_";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

require_once("administracion_de_estadisticas_combos.php");
?>
<script>
	function filtrarTitulosBusqueda(valor) {
		document.getElementById('iframeProcesando').src = '/modules/control_gestion/informes_de_gestion/cargar_titulos.php?valor=' + valor;
	}

	showTitle(true, 'INFORMES DE GESTIÓN');
</script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/index.php?pageid=34&mdl=administracion_de_estadisticas.php" id="formEstadisticas" method="post" name="formEstadisticas" target="iframeProcesando" onSubmit="ValidarForm(formEstadisticas)">
	<input id="buscar" name="buscar" type="hidden" value="yes" />
	<div align="center">
		<table width="770" cellspacing="0" cellpadding="0">
			<tr>
				<td width="45" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right" style="margin-left: 7px"><b><font size="2"><img src="/modules/control_gestion/informes_de_gestion/images/usuario.jpg" width="26" height="28"></td>
				<td width="101" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font color="#808080" style="font-size: 10pt">Usuario Actual:</font></td>
				<td align="left" width="529" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font style="font-size: 8pt; " color="#000000"><?= GetUserName()?></font></td>
				<td width="54" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right">&nbsp;</td>
			</tr>
		</table>
		<br />
		<table width="720" cellspacing="0" cellpadding="0">
			<tr>
				<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="30"><b><font size="2"><a href="/index.php?pageid=34"><img src="/modules/control_gestion/informes_de_gestion/images/administracion.jpg" width="30" height="27"></a></td>
				<td align="left" style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><span style="font-weight: 700"><font size="3" color="#00A4E4">Administración de Estadísticas</font></span></td>
			</tr>
		</table>
		<div style="margin-top:8px;">
			<p>
				<label color="#808080" style="font-size:10pt;">Tema</label>
				<?= $comboTema->draw();?>
				<label color="#808080" style="font-size:10pt; margin-left:16px;">Título</label>
				<?= $comboTitulo->draw();?>
			</p>
			<p style="margin-top:8px;">
				<label color="#808080" style="font-size:10pt;">Fecha desde</label>
				<input class="FormInputTextDate" id="FechaDesde" maxlength="10" name="FechaDesde" style="width:80px;" title="Fecha Desde" type="text" validarFecha="true" />
				<input class="BotonFecha" id="btnFechaDesde" name="btnFechaDesde" style="vertical-align:-5px;" type="button" value="" />
				<label color="#808080" style="font-size:10pt; margin-left:16px;">hasta</label>
				<input class="FormInputTextDate" id="FechaHasta" maxlength="10" name="FechaHasta" style="width:80px;" title="Fecha Hasta" type="text" validarFecha="true" />
				<input class="BotonFecha" id="btnFechaHasta" name="btnFechaHasta" style="vertical-align:-5px;" type="button" value="" />
				<label color="#808080" style="font-size:10pt; margin-left:16px;">Usuario</label>
				<?= $comboUsuario->draw();?>
			</p>
			<p style="margin-top:8px;">
				<input id="btnBuscar" name="btnBuscar" type="submit" value="BUSCAR" style="color:#808080; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #fff;" />
				<input id="btnLimpiar" name="btnLimpiar" type="reset" value="LIMPIAR" style="color:#808080; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #fff; margin-left:24px;" />
			</p>
		</div>
	</div>
</form>
<div align="center" id="divContent" name="divContent">
	<div id="divTopGrilla" style="display:none">
		<b>Total de registros</b>
		<span id="total" style="margin-right:200px;">---</span>
		<a id="LinkToExcel" href="" target="_blank">
			<img src="/images/excel.png" style="height:28px; vertical-align:-10px; width:28px;" title="Exportar Grilla a Excel" />
		</a>
	</div>
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$where = "";
	if ($fechaDesde != "")
		$where.= " AND ie_fecha >= TO_DATE(".addQuotes($fechaDesde).", 'dd/mm/yyyy')";
	if ($fechaHasta != "")
		$where.= " AND ie_fecha <= TO_DATE(".addQuotes($fechaHasta).", 'dd/mm/yyyy')";
	if ($tema != -1) {
		if ($tema == -2)
			$where.= " AND ie_idpublicado < 0";
		else
			$where.= " AND it_id = ".$tema;
	}
	if ($titulo != -1) {
		if ($titulo < 0)
			$where.= " AND ie_idpublicado = ".($titulo + 1);
		else
			$where.= " AND ip_id = ".$titulo;
	}
	if ($usuario != -1)
		$where.= " AND se_id = ".$usuario;
	$sql =
		"SELECT ie_fecha ¿fecha?,
						DECODE(it_tema, NULL, 'Tablero de Control', it_tema) ¿tema?,
						CASE 
							WHEN ie_idpublicado = -1 THEN 'Sistema de Información Ejecutiva'
							WHEN ie_idpublicado = -2 THEN 'Sistema de Información de Gestión'
							WHEN ie_idpublicado = -3 THEN 'Sistema de Información Operativa'
							ELSE ip_titulo || DECODE(ie_activohistorico, 0, ' (HISTÓRICO)', '')
						END ¿titulo?,
						se_nombre ¿usuario?
  		 FROM intra.cie_informeestadistica, intra.cit_informetemas, intra.cip_informepublicado, use_usuarios
 			WHERE ie_idtema = it_id(+)
   			AND ie_idpublicado = ip_id(+)
   			AND ie_usuario = se_usuario
   			AND it_fechabaja IS NULL
   			AND ip_fechabaja IS NULL _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("Fecha"));
	$grilla->addColumn(new Column("Tema"));
	$grilla->addColumn(new Column("Título"));
	$grilla->addColumn(new Column("Usuario"));
	$grilla->setColsSeparator(true);
	$grilla->setExtraConditions(array($where));
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setRowsSeparator(true);
	$grilla->setSql($sql);
	$grilla->Draw();
	echo "<script>";
	echo "document.getElementById('divTopGrilla').style.display = 'block';";
	echo "document.getElementById('total').innerHTML = '".$grilla->recordCount()."';";
	echo "document.getElementById('LinkToExcel').href = '/modules/control_gestion/informes_de_gestion/exportar_a_excel.php?sql=".rawurlencode($grilla->getSqlFinal())."';";
	echo "</script>";
}
?>
</div>
<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img src="/images/waiting.gif" title="Espere por favor..."></div>
<script>
	function CopyContent() {
		try {
			window.parent.document.getElementById('divContent').innerHTML = document.getElementById('divContent').innerHTML;
		}
		catch(err) {
			//
		}
	}


	Calendar.setup ({
		inputField: "FechaDesde",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaDesde"
	});
	Calendar.setup ({
		inputField: "FechaHasta",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaHasta"
	});

	CopyContent();
	document.getElementById('tema').focus();
</script>