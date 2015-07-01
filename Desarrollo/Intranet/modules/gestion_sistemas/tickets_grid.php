<?
require_once ($_SERVER["DOCUMENT_ROOT"] . "/modules/gestion_sistemas/ticket_funciones.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/CrearLog.php");

function CompletarFiltroFechas($fechaControl) {
    $fechaResult = "";
    if (isset($_REQUEST[$fechaControl])) {
        $fechaResult = $_REQUEST[$fechaControl];
        $timestamp = strtotime($fechaResult);

        $year = date("Y", $timestamp);
        if ($year > 1970)
            $fechaResult = date("d-m-Y", $timestamp);
        else
            $fechaResult = '';
    }

    return $fechaResult;
}

/*MENU seleccionado*/
$MNU = GetParametro("MNU", 0);

/* Implementación de múltiples sistemas dentro del sistema de tickets */
if (isset($_REQUEST["sistema"]))
    $sistema = $_REQUEST["sistema"];
else
    $sistema = 1;

//$FechahoyGuion = date('Y-m-d');
$FechahoyGuion = '';

if (!isset($_REQUEST["firstcall"]))
    if ($showProcessMsg) {
        //FirstCallPageCode();
    }

$numeroTicket = "";
if (isset($_REQUEST["numeroTicket"]))
    $numeroTicket = $_REQUEST["numeroTicket"];
//---------------------NUEVOS-PARAMETROS----------------------------------------
$all_tickets = "";
if (isset($_REQUEST["all_tickets"]))
    $all_tickets = $_REQUEST["all_tickets"];

$fechaDesde = CompletarFiltroFechas("fechaDesde");
$fechaHasta = CompletarFiltroFechas("fechaHasta");
if ($fechaDesde == '' or $fechaHasta == '') {
    $fechaHasta = '';
    $fechaDesde = '';
}

$ss_notas = "";
if (isset($_REQUEST["textoLibre"]))
    $ss_notas = $_REQUEST["textoLibre"];

$PlanAccion = " AND motivooriginal.ms_idpadre <> 501 ";
$PlanAccionParam = "";
if (isset($_REQUEST["PlanAccion"])) {
    $PlanAccion = " AND motivooriginal.ms_idpadre = 501 ";
    $PlanAccionParam = "&PlanAccion";
}

$ParamfechaDesde = GetParametro('fechaDesde');
$ParamfechaHasta = GetParametro('fechaHasta');
$ParamtextoLibre = GetParametro('textoLibre');
//--------------------------------------------------------------
$TipoPedido = '';
if (isset($_REQUEST["TipoPedido"]) and ($_REQUEST["TipoPedido"] > '-1')) {
    $TipoPedido = " AND motivodetalle.ms_id = " . $_REQUEST["TipoPedido"] . " ";
    $TipoPedidoParam = "&TipoPedido";
}

$DetallePedido = '';
if (isset($_REQUEST["DetallePedido"]) and (intval($_REQUEST["DetallePedido"]) > '0')) {
    $DetallePedido = " AND motivodetalle.ms_id = " . $_REQUEST["DetallePedido"] . " ";
    $TipoPedidoParam = "&DetallePedido";
}
//--------------------------------------------------------------
$where = "";
if (isset($_REQUEST["ticket_detail"]))
    $back_button = $_REQUEST["back_button"];
else
    $back_button = "yes";

/* Para implementar el filtro de los empleados que dependen de uno mismo */
if (isset($_REQUEST["employees"]))
    $employees = $_REQUEST["employees"];
else
    $employees = "yes";

if (isset($_REQUEST["close_button"]))
    $close_button = $_REQUEST["close_button"];
else
    $close_button = "yes";

$formConfig = "/modules/gestion_sistemas/index.php?sistema=" . $sistema . "&search=yes&all_tickets=" . $all_tickets . "&firstcall=false" . $PlanAccionParam . "=yes";
?>
<form action="<? echo $formConfig; ?>" id="formTicket" method="post" name="formTicket"  >

	<div align="center" class="contentIn" style="width:100%; "  >

		<div id="stylized" class="formGeneric750"  >
			<table class="gridTable" border=1 style="width:100%; font-size:12px;" >
				<tr>
		<td><label style="width:80px; text-align:left; margin-left:10px;" for="numeroTicket">Nº Ticket</label>	</td>
					<td><label style="width:200px; text-align:left; margin-left:10px;" >Desde / Hasta</label></td>
					<td><label style="width:auto; text-align:left; margin-left:10px;" for="testoLibre">Texto</label></td>
					<td></td>
				</tr>
				<tr>
					<td>
			<input id="numeroTicket" name="numeroTicket" style="width:80px;" size="10" title="Nº Ticket" type="text" validarEntero="true" value="<?= $numeroTicket ?>"   />			
					</td>
					<td>
					<input  type="text" id="fechaDesde" name="fechaDesde" title="Fecha Desde" value="<? echo $ParamfechaDesde ?>" class='Combo'
					style='width:80px; padding-right:0px;' />
					<input  type="button" id="btnFechaDesde" name="btnFechaDesde" title="Seleccione una Fecha" value="..."
					style='width:20px; padding-left:0; border-left:0; margin-left:0;'  />
					<input  type="text" id="fechaHasta" name="fechaHasta" title="Fecha Hasta" value="<? echo $ParamfechaHasta ?>" class='Combo' style='width:80px;' />
					<input  type="button" id="btnFechaHasta" name="btnFechaHasta" title="Seleccione una Fecha" value="..."
					style='width:20px; padding-left:0; border-left:0; margin-left:0;'  />
					</td>
					<td>
					<input  type="text" id="textoLibre" name="textoLibre" size="30" title="Texto libre" value="<? echo $ParamtextoLibre ?>" class='Combo' style='width:300px;' />
					</td>
					<td>
					<input  id="btnLimpiar" name="btnLimpiar" type="button" value="Limpiar" class="formatoBotonGeneral" style="width:80px;" onclick="LimpiaPermisoUsuarios();" />
					</td>
				</tr>

				<tr>
					<td colspan="2"><label style="width:auto; text-align:left; margin-left:10px;">Motivo</label></td>
					<td><label  style="width:auto; text-align:left; margin-left:10px;" for="testoLibre">Detalle</label></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="2" >
					<div style="width:310px;">
						<? echo PrintComboTipoPedido("formTicket", "width:100%; font: 11px Neo Sans; padding: 4px 2px; border: solid 1px #BBBBBB; "); ?>
					</div></td>
					<td>
					<div style="width:300px;">
						<? echo PrintComboDetallePedido("width:100%; border:0px; font: 11px Neo Sans; padding: 4px 2px; border: solid 1px #BBBBBB; "); ?>
					</div></td>
					<td>
					<input  id="btnBuscar" name="btnBuscar" type="button" value="Buscar" class="formatoBotonGeneral" style="width:80px;" onclick="BuscarPedidos(1);" />
					</td>
				</tr>

			</table>

			<div class="msg_label" style="align:left; width:100%; padding:0px 4px;" id="DivAreaMensajes"></div>
			<img border="0" id="imgProcesando" src="/images/loading.gif" style="display:none; vertical-align:-4px;" title="Procesando, espere por favor..." />
		</div>
		<div id='grillaColaboradores' style="display:block; vertical-align:-4px;" ></div>
		<input type="hidden" value=0 id="PaginaActual">

	</div>

</form>

<script type="text/javascript">
	//<![CDATA[

	var cal = Calendar.setup({
		onSelect : function(cal) {
			cal.hide()
		},
		showTime : false
	});
	cal.manageFields("btnFechaDesde", "fechaDesde", "%d/%m/%Y");
	cal.manageFields("btnFechaHasta", "fechaHasta", "%d/%m/%Y");

	//]]>
</script>

<script type="text/javascript"><?php
echo " var all_tickets = '" . $all_tickets . "'; ";
echo " var pending_tickets = '" . $pending_tickets . "'; ";
echo " var pending_moreinfo_tickets = '" . $pending_moreinfo_tickets . "'; ";
echo " var pending_auth_tickets = '" . $pending_auth_tickets . "'; ";
echo " var numeroTicket = '" . $numeroTicket . "'; ";
echo " var fechaDesde = '" . $fechaDesde . "'; ";
echo " var fechaHasta = '" . $fechaHasta . "'; ";
echo " var ss_notas = '" . $ss_notas . "'; ";
echo " var PlanAccion = '" . $PlanAccion . "'; ";
echo " var TipoPedido = '" . $TipoPedido . "'; ";
echo " var employees = '" . $employees . "'; ";
echo " var sistema = '" . $sistema . "'; ";
echo " var back_button = '" . $back_button . "'; ";
echo " var close_button = '" . $close_button . "'; ";
echo " var pagina = '" . $pagina . "'; ";
echo " var MNU = '" . $MNU . "'; ";

// FillCombos..
$excludeHtml = True;
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "TipoPedido";
$RCparams = array();
$RCquery = "SELECT ms_id ID, ms_descripcion DETALLE
FROM computos.cms_motivosolicitud
WHERE ms_idpadre = -1
AND ms_visible = 'S'
AND ms_fechabaja IS NULL
AND ms_id IN (SELECT ms_idpadre
FROM computos.cms_motivosolicitud, computos.cts_ticketsector
WHERE art.agenda_pkg.is_sectordependiente(ts_idsector, ms_idsectordefault) = 'S'
AND ts_idsistematicket = " . $sistema . ")
ORDER BY 2 ";
$RCselectedItem = -1;
FillCombo();
?>window.onload = InicializaformTicket();</script>