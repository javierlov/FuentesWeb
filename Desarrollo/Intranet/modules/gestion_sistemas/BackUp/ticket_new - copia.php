<?
/* Implementación de multiples sistemas dentro del sistema de tickets */
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
	
<form action="ticket_save.php?sistema=<?echo $sistema; ?>" id="formSolicitud" name="formSolicitud" method="post" onSubmit="return ValidarFormTicketPermiso()" enctype="multipart/form-data">
	
	<div align="center" style="padding:12px; margin:12px; border:12px; "  >

	
	<div id="stylized" class="formGeneric" style="font-size:12px; width:500px;">
			<b><?echo $textoHeader; ?></b>
			<br />
			<p><?echo $textoSubHeader; ?></p>
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
	Haga clic <?echo $link; ?> aquí </a></b> para calificarnos para poder realizar una nueva solicitud.
<?
}
else {
?>
		<label>Solicitud para <span class="small">Recuerde que debe indicar para quien es el pedido</span></label>
		<select class="Combo" id="UsuarioSolicitud" name="UsuarioSolicitud" onchange="ValidarPermisoUsuario();"></select>

		<label>Pedido <span class="small">Tipo de pedido</span></label>
		<select class="Combo" id="TipoPedido" name="TipoPedido" onchange="AjaxRequest('DivDetallePedido', 'ajax_detalle_motivos.php', document.formSolicitud.TipoPedido.options[document.formSolicitud.TipoPedido.selectedIndex].value);"></select>

		<label>Detalle <span class="small">Detalle del pedido</span></label>
		<div id="DivDetallePedido">
			<select class="GICombo" id="DetallePedido" name="DetallePedido" onchange="CambioDetallePedido();"></select>
		</div>

		<div id="DivEjecutable"></div>

		<label>Descripción <span class="small">Acerca del incidente<br />(1000 caracteres)</span></label>
		<textarea rows="3" name="notas" id="notas"></textarea>

		<div  style="border: 2px solid #321; display:table;" >		
		
				<div id="DivAdjuntos"  style="display:run-in;" ></div>
				<div id="attachmentInicial" style="display:run-in;" ></div>
			<!--
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
			-->
			
		</div> 

		<label>Prioridad <span class="small">¿Qué tan urgente es? (Depende del motivo)</span></label>
		<div id="DivDetallePrioridad">
			<select class="Combo" id="Prioridad" name="Prioridad"></select>
		</div>

		<button class="btnAction" id="btnSubmit" type="submit">Realizar pedido</button>
		<img border="0" id="imgProcesando" src="/images/loading.gif" style="display:none; vertical-align:-4px;" title="Procesando, espere por favor..." />
		<div class="msg_label" id="DivAreaMensajes"></div>
		<div class="spacer"></div>
<?
}
?>
	</div>
	
	
</div>

</form>

<script type="text/javascript"><?
echo "var usuarioLogeado = '" . GetUsuarioAplicacion() . "'; ";
// FillCombos..
$excludeHtml = True;
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "UsuarioSolicitud";
$RCparams = array(":id" => GetUserID(), ":idjefe" => GetUserIDJefe(NULL), ":sector" => GetUserSector(), ":idsector" => GetUserIdSectorIntranet(), ":respondea" => GetWindowsLoginName());

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
?>
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