<?
/* Implementación de múltiples sistemas dentro del sistema de tickets */

$sistema = GetParametro("sistema", 1);

$arrayConfig = GetConfigSistema($sistema);
$textoHeader = $arrayConfig['ST_HEADER'];
$textoSubHeader = $arrayConfig['ST_SUBHEADER_NEW'];

?>

<form action="ticket_save.php?sistema=<?echo $sistema; ?>" 
	id="formSolicitud" 
	name="formSolicitud" 
	method="post" 
	onSubmit="return ValidarFormTicketPermiso()" 
	enctype="multipart/form-data">

<input	type="hidden" name="MNU" value="2" />
<div align="center" class="contentIn" style="padding:12px; margin:0px; border:12px; "  >
<div id="stylized" class="formGeneric550 styleForm" >
			
	<table id='tablaprincipal' >
		<tr style="text-align:center">
			<td>			
				<b><?echo $textoHeader; ?></b><br/>
				<p><?echo $textoSubHeader; ?></p>				
			</td>
		</tr>	
		<tr>
			<td>
		
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
				Ud. tiene <?echo $link; ?>
				<?
					echo $pending_tickets;
					echo "</a></b>";
					echo($pending_tickets == 1) ? " ticket pendiente " : " tickets pendientes ";
				?>
				de calificar desde hace mas de una semana.
				<br/>
				Haga clic <? echo $link; ?> aquí </a></b> para calificarnos para poder realizar una nueva solicitud.
				<?
                }
                else {
				?>
			</td>
		</tr>
		<tr>
			<td>
		
				<label class="labelTitulo" >Solicitud para <span class="small">Recuerde que debe indicar para quien es el pedido</span></label>
				<select class="GICombo" id="UsuarioSolicitud" name="UsuarioSolicitud" onchange="ValidarPermisoUsuario();"></select>

			</td>
		</tr>		
		<tr>
			<td>	
				
				<label class="labelTitulo" >Pedido <span class="small">Tipo de pedido</span></label>
				<select class="GICombo" id="TipoPedido" name="TipoPedido" 
					onchange="AjaxRequest('DivDetallePedido', 'ajax_detalle_motivos.php', document.formSolicitud.TipoPedido.options[document.formSolicitud.TipoPedido.selectedIndex].value);"></select>
			</td>
		</tr>
		<tr>
			<td>						
				<label class="labelTitulo" >Detalle <span class="small">Detalle del pedido</span></label>
				<div id="DivDetallePedido">
				<select class="GICombo" id="DetallePedido" name="DetallePedido" 
						onchange="CambioDetallePedido(); "></select>
				</div>
				<!--
				<div id="DivEjecutable"></div>
				-->
			</td>
		</tr>
		
		<tr>
			<td>										
				<div id="AplicacionSelect"></div>
			</td>
		</tr>
		
		<tr>
			<td>						
				<label class="labelTitulo" >Descripción <span class="small">Acerca del incidente<br />(1000 caracteres)</span></label>
				<textarea style="  font-family: Neo Sans; font-size: 9pt;" rows="3" name="notas" id="notas"></textarea>
			</td>
		</tr>
		<tr>
			<td>				
				<table>
				<tr>
				<td valign="top" >
					<label class="labelTitulo"  >Adjuntos<span class="small">Documentos, imágenes</span></label>
				</td>
				<td>
					<div id="attachmentInicial" style="padding:4px;"  ></div></td>
				</tr>							
				</table>	
				<p>				
			</td>
		</tr>
		<tr>
			<td>						
				<label class="labelTitulo" >Prioridad <span class="small">¿Qué tan urgente es? (Depende del motivo)</span></label>
				<div id="DivDetallePrioridad">
					<select class="GICombo" id="Prioridad" name="Prioridad"></select>
				</div>			
			</td>
		</tr>
		<tr>
			<td>				
				<div id="AreaBotones" style="text-align:center;"> 						
					<div id="AreaBotones" style="text-align:center;"> 
						<button class="GIBtnAction" id="btnSubmit" type="submit" style="margin:5px 10px;">Realizar pedido</button>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>	
				<code> <img border="0" id="imgProcesando" src="/images/loading.gif" style="display:none; vertical-align:-4px;" title="Procesando, espere por favor..." />
					<div class="msg_label" id="DivAreaMensajes" style="font: 18px; text-align:left; width:460px;" ></div>
					<div class="msg_label" id="DivTicketMensajes" style="display:none;" ></div>
					<div class="spacer"></div></code>									
			</td>
		</tr>
	</table>
<?
}
?>
	</div>
</div>
</form>


<script type="text/javascript"><?
try {
	
	echo "\n";
	echo "\n";
    echo "	var usuarioLogeado = '" . GetUsuarioAplicacion() . "'; ";
	echo "\n";
    echo "	var TicketTienePermiso= false; ";
	echo "\n";
	echo "\n";
    // FillCombos..
    $excludeHtml = True;

    require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/refresh_combo.php");

    $RCwindow = "window";

    $RCfield = "UsuarioSolicitud";
		$RCparams = array(":id" => GetUserID(),
						  ":idjefe" => GetUserIDJefe(NULL),
						  ":sector" => GetUserSector(),
						  ":idsector" => GetUserIdSectorIntranet(),
						  ":respondea" => GetWindowsLoginName());

    $RCquery = "SELECT se_id ID, InitCap(se_nombre) DETALLE, SE_USUARIO IDUSUARIO                             /* El propio usuario */
					  FROM art.use_usuarios
					 WHERE se_fechabaja IS NULL
					   AND se_usuariogenerico = 'N'
					   AND se_id = :id
				 UNION ALL
					SELECT se_id ID, InitCap(se_nombre) DETALLE, SE_USUARIO IDUSUARIO                             /* El jefe */
					  FROM art.use_usuarios
					 WHERE se_fechabaja IS NULL
					   AND se_usuariogenerico = 'N'
					   AND se_id = NVL(:idjefe, -1)
					 UNION
					SELECT se_id ID, InitCap(se_nombre) DETALLE, SE_USUARIO IDUSUARIO                             /* Los compañeros de trabajo */
					  FROM art.use_usuarios
					 WHERE se_fechabaja IS NULL
					   AND se_usuariogenerico = 'N'
					   AND se_sector = NVL(:sector, '')
					 UNION
					SELECT se_id ID, InitCap(se_nombre) DETALLE, SE_USUARIO IDUSUARIO                             /* Los otros compañeros de trabajo */
					  FROM art.use_usuarios
					 WHERE se_fechabaja IS NULL
					   AND se_usuariogenerico = 'N'
					   AND se_idsector = NVL(:idsector, -1)
					 UNION
					SELECT se_id ID, InitCap(se_nombre) DETALLE, SE_USUARIO IDUSUARIO                             /* Los otros empleados a cargo */
					  FROM art.use_usuarios
					 WHERE se_fechabaja IS NULL
					   AND se_usuariogenerico = 'N'
					   AND se_respondea = NVL(UPPER(:respondea), '')
					 UNION
					SELECT se_id ID, InitCap(se_nombre) DETALLE, SE_USUARIO IDUSUARIO                             /* Los empleados de los empleados a cargo */
					  FROM art.use_usuarios
					 WHERE se_fechabaja IS NULL
					   AND se_usuariogenerico = 'N'
					   AND se_respondea IN (SELECT se_usuario
											  FROM art.use_usuarios
											 WHERE se_fechabaja IS NULL
											   AND se_usuariogenerico = 'N'
											   AND se_respondea = NVL(UPPER(:respondea), ''))
				  ORDER BY 2";

    $RCselectedItem = GetUserID();
    FillCombo();
	
	echo "\n";

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
   
   echo "\n";

    $RCfield = "Prioridad";
    $RCparams = array();
    $RCquery = "SELECT ID, DETALLE
						FROM (SELECT 1 ID, 'Alta' DETALLE
								FROM DUAL
							   UNION ALL
							  SELECT 2 ID, 'Media' DETALLE
								FROM DUAL
							   UNION ALL
							  SELECT 3 ID, 'Baja' DETALLE
								FROM DUAL) PRIORIDADES
					   WHERE 1 = 1 ";
    $RCselectedItem = -1;
    FillCombo();
	
	echo "\n";

} catch (Exception $e) {
    EscribirLogTxt1(' ERROR Tickets nuevos', $e -> getMessage());
    return false;
}
?>
 AddAttachment('attachmentInicial', 'ajax_ticket_attachments.php', 0);
 </script>
 