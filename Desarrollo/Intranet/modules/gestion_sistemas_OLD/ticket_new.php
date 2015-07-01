<?
require_once("ticket_new_combos.php");


/* Implementación de múltiples sistemas dentro del sistema de tickets */
if (isset($_REQUEST["sistema"]))
	$sistema = $_REQUEST["sistema"];
else
	$sistema = 1;

if ($sistema == 1) {
	$textoHeader = 'Solicitud a Sistemas';
	$textoSubHeader = 'Este módulo le permitirá realizar una solicitud a la Gerencia de Sistemas';
}
if ($sistema == 2) {
	$textoHeader = 'Solicitud a Obras & Mantenimiento';
	$textoSubHeader = 'Este módulo le permitirá realizar una solicitud al sector de Obras & Mantenimiento';
}
if ($sistema == 3) {
	$textoHeader = 'Solicitud a Sistemas del Grupo Banco Provincia';
	$textoSubHeader = 'Este módulo le permitirá realizar una solicitud a la Gerencia de Sistemas';
}
if ($sistema == 4) {
	$textoHeader = 'Solicitud a Análisis y Control de Gestión';
	$textoSubHeader = 'Este módulo le permitirá realizar una solicitud a la Gerencia de Análisis y Control de Gestión';
}
?>
	<div id="stylized" class="formGeneric" style="font-size:12px; width:500px;">
		<form action="ticket_save.php?sistema=<?echo $sistema;?>" id="formSolicitud" name="formSolicitud" method="post" onSubmit="return ValidarFormTicket(formSolicitud)" enctype="multipart/form-data">
			<b><?echo $textoHeader;?></b>
			<br />
			<p><?echo $textoSubHeader;?></p>
<?
$params = array(":idusuario" => GetUserID(), ":idsistema" => $sistema);
$sql = 
	"SELECT COUNT(*)
		 FROM computos.css_solicitudsistemas
		WHERE ss_idestadoactual = 5
			AND ss_fechamodif < art.actualdate - 7
			AND ss_idusuario_solicitud = :idusuario
			AND ss_idsistematicket = :idsistema";
$pending_tickets = ValorSQL($sql, "", $params);
$link = '<b><a href="index.php?sistema='.$sistema.'&search=yes&amp;pending_tickets=yes" style="text-decoration: none;">';
if ($pending_tickets > 0) {
?>
	Ud. tiene <?echo $link;?>
<?
	echo $pending_tickets;
	echo "</a></b>";
	echo ($pending_tickets == 1)?" ticket pendiente ":" tickets pendientes ";
?>
	de calificar desde hace mas de una semana.
	<br/>
	Haga clic <?echo $link;?> aquí </a></b> para calificarnos para poder realizar una nueva solicitud.
<?
	}
	else {
?>
		<label>Solicitud para <span class="small">Recuerde que debe indicar para quien es el pedido</span></label>
		<?= $comboUsuarioSolicitud->draw();?>

		<label>Pedido <span class="small">Tipo de pedido</span></label>
		<?= $comboTipoPedido->draw();?>

		<label>Detalle <span class="small">Detalle del pedido</span></label>
		<div id="DivDetallePedido">
			<select class="Combo" id="DetallePedido" name="DetallePedido" onchange="CambioDetallePedido();CambioDetallePedido();"></select>
		</div>

		<div id="DivEjecutable"></div>
<!--
      <label>Fecha requerida
        <span class="small">¿Para cuándo lo necesito?</span>
      </label>
      <div id="DivFecha">
        <table width="100%" height="50" id="trFecha">
          <tr>
            <td id="tdFecha">
              <input class="FormInputTextDate" id="FechaNacimiento" maxlength="10" name="FechaNacimiento" title="Fecha de Nacimiento" type="text" validarFecha="true">
              <button class="btnFecha" id="btnFechaNacimiento" name="btnFechaNacimiento" value=""></button>
            </td>
          </tr>
        </table>
      </div>
-->
		<label>Descripción <span class="small">Acerca del incidente<br />(1000 caracteres)</span></label>
		<textarea rows="3" name="notas" id="notas"></textarea>

		<div id="DivAdjuntos">
			<table width="100%" height="24" id="trAdjuntos">
				<tr>
					<td id="attachmentInicial">&nbsp;</td>
				</tr>
			</table>

			<table width="100%" height="10">
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		</div> 

		<label>Prioridad <span class="small">¿Qué tan urgente es? (Depende del motivo)</span></label>
		<div id="DivDetallePrioridad">
			<?= $comboPrioridad->draw();?>
		</div>

		<button class="btnAction" id="btnSubmit" type="submit">Realizar pedido</button>
		<img id="imgProcesando" src="/images/loading.gif" style="display:none; vertical-align:-4px;" title="Procesando, espere por favor..." />
		<div class="msg_label" id="DivAreaMensajes"></div>
		<div class="spacer"></div>
<?
	}
?>
		</form>
	</div>

<script type="text/javascript">
/*
	Calendar.setup (
		{
			inputField: "FechaNacimiento",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaNacimiento"
		}
  );
*/
  AddAttachment('attachmentInicial', 'ajax_ticket_attachments.php', 0);
</script>