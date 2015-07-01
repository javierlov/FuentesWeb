<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/modules/gestion_sistemas/ticket_funciones.php");

echo "<script type='text/javascript' src='/modules/gestion_sistemas/js/ticket_permisos.js?rnd=".RandomNumber()."'></script> ";

?>
<link href="/Styles/gridAjax.css" rel="stylesheet" type="text/css" />


<form action="ticket_subsistema.php?sistema=<?  echo  $sistema; ?>" id="formSolicitud" name="formSolicitud" 
		method="post" onSubmit="return ValidarFormTicket(formSolicitud)" enctype="multipart/form-data">

	<div style="width:100%; margin-top:10px;">

		<div id="stylized" class="formGeneric" style="font-size:12px; width:560px; height:140px;">
			<table width="100%" align="center">
				<tr>
					<td><label class="labelTitulo" >Pedido <span class="small">Tipo de pedido</span></label><select class="Combo" id="TipoPedido" name="TipoPedido" onchange="AjaxRequest('DivDetallePedido', 'ajax_detalle_motivos.php', document.formSolicitud.TipoPedido.options[document.formSolicitud.TipoPedido.selectedIndex].value); LimpiarGrid();"></select></td>
				</tr>
				<tr>
					<td><label class="labelTitulo" >Detalle <span class="small">Detalle del pedido</span></label>
					<div id="DivDetallePedido">
						<select class="Combo" id="DetallePedido" name="DetallePedido" onchange="DetallePedidoChange(); "></select>
					</div></td>
				</tr>
				<tr>
					<td>
					<button class="GIBtnAction" id="btnSubmit" type="button" onclick="BuscaColaboradores(1);" style="align:left; width:auto; padding:0px 8px;" >
						Buscar
					</button>
					<button class="GIBtnAction" id="btnNuevosPermisos" type="button" onclick="NuevosPermisos();" style="align:left; width:auto; padding:0px 8px;" >
						Editar / Agregar
					</button></td>
				</tr>
				<tr>
					<td><div class="msg_label" style="align:left; width:100%; padding:0px 4px;" id="DivAreaMensajes"></div><img border="0" id="imgProcesando" src="/images/loading.gif" style="display:none; vertical-align:-4px;" title="Procesando, espere por favor..." /><div class="spacer"></div></td>
				</tr>
			</table>
		</div>

		<div class="gridTableAjxPermisos" style="display:block; overflow:hidden;" >
			<table width="100%" align="center">
				<tr>
					<td align="center" ><div id='grillaColaboradoresPermisos' style="display:block; vertical-align:-4px;" ></div></td>
				</tr>
			</table>
		</div>

	</div>

	<input type="hidden" value=0 id="paginaactual">
</form>

<?php
	$idDialog = 'idDialogMsj';
	$idSubtitulo = 'idSubTitulo';
	$idMensaje = 'idMensaje';
	$TextDialog = 'Eliminar';
	$TextSubtitulo = 'Eliminar grupo de permisos';
	$TextMensaje = '¿Esta seguro que desea eliminar estos usuarios?';
	//drawDialogJQUI($idDialog, $idSubtitulo, $idMensaje, $TextDialog, $TextSubtitulo, $TextMensaje);
	
?>

<div id='idDialogMsj' title='TextDialog'>
	<b class='txt-msj-Aviso' id='idSubtitulo' >TextSubtitulo</b>		
	<p>
	<div id='idMensaje' style='padding:3px 0 0 0; text-align:left; font-style:italic;' >TextMensaje. </div>
	<p>	
</div>

<script type="text/javascript"><?php

$UsuarioSolicitud = GetUsuarioAplicacion();
echo "var sistema = " . $sistema . "; ";
echo "var UsuarioNombre = '" . $UsuarioSolicitud . "'; ";

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
ORDER BY 2";
$RCselectedItem = -1;
FillCombo();
?>
AddAttachment('attachmentInicial', 'ajax_ticket_attachments.php', 0);
</script>