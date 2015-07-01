  <div id="stylized" class="formGeneric" style="font-size:12px; width:500px;">
    <form action="ticket_save.php" id="formSolicitud" name="formSolicitud"
          method="post" onSubmit="return ValidarFormTicket(formSolicitud)"
          enctype="multipart/form-data">
      <b>Solicitud a Sistemas</b>
      <br />
      <p>Este módulo le permitirá realizar una solicitud a la Gerencia de Sistemas</p>

<?
$sql =
	"SELECT COUNT(*)
		FROM COMPUTOS.CSS_SOLICITUDSISTEMAS
	 WHERE SS_IDESTADOACTUAL = 5
		  AND SS_FECHAMODIF < ART.ACTUALDATE - 7
		  AND SS_IDUSUARIO_SOLICITUD = :idusuario";
$params = array(":idusuario" => GetUserID());
$pending_tickets = ValorSQL($sql, "", $params);
$link = '<b><a href="index.php?search=yes&amp;pending_tickets=yes" style="text-decoration: none;">';
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
        Haga click <?echo $link;?> aquí </a></b> para calificarnos para poder realizar una nueva solicitud.
      <?
      } else {
      ?>

      <label>Solicitud para
        <span class="small">Recuerde que debe indicar para quien es el pedido</span>
      </label>
      <select class="Combo" id="UsuarioSolicitud" name="UsuarioSolicitud" onchange="ValidarPermisoUsuario();">
      </select>

      <label>Pedido
        <span class="small">Tipo de pedido</span>
      </label>
      <select class="Combo" id="TipoPedido" name="TipoPedido"
              onchange="AjaxRequest('DivDetallePedido', 'ajax_detalle_motivos.php', document.formSolicitud.TipoPedido.options[document.formSolicitud.TipoPedido.selectedIndex].value);">
      </select>

      <label>Detalle
        <span class="small">Detalle del pedido</span>
      </label>
      <div id="DivDetallePedido">
        <select class="Combo" id="DetallePedido" name="DetallePedido"
                onchange="CambioDetallePedido();CambioDetallePedido();">
        </select>
      </div>

      <div id="DivEjecutable">
      </div>
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
      <label>Descripción
        <span class="small">Acerca del incidente<br />(1000 caracteres)</span>
      </label>
      <textarea rows="3" name="notas" id="notas"></textarea>

      <div id="DivAdjuntos">
        <table width="100%" height="24" id="trAdjuntos">
          <tr>
            <td id="attachmentInicial">
              &nbsp;
            </td>
          </tr>
        </table>

        <table width="100%" height="10">
          <tr>
            <td>
              &nbsp;
            </td>
          </tr>
        </table>
      </div> 

      <label>Prioridad
        <span class="small">¿Qué tan urgente es? (Depende del motivo)</span>
      </label>
      <div id="DivDetallePrioridad">
        <select class="Combo" id="Prioridad" name="Prioridad">
        </select>
      </div>

      <button type="submit" class="btnAction">Realizar pedido</button>
      <div class="msg_label" id="DivAreaMensajes"></div>
      <div class="spacer"></div>
      <?
      }
      ?>
    </form>
  </div>

<script type="text/javascript">
<?
// FillCombos..
$excludeHtml = True;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "UsuarioSolicitud";
$RCparams = array(":id" => GetUserID(),
								":idjefe" => GetUserIDJefe(NULL),
								":sector" => GetUserSector(),
								":idsector" => GetUserIdSectorIntranet(),
								":respondea" => GetWindowsLoginName());
$RCquery =
	"SELECT se_id ID, InitCap(se_nombre) DETALLE                             /* El propio usuario */
		FROM art.use_usuarios
	 WHERE se_fechabaja IS NULL
		  AND se_usuariogenerico = 'N'
		  AND se_id = :id
UNION ALL
	 SELECT se_id ID, InitCap(se_nombre) DETALLE                             /* El jefe */
		FROM art.use_usuarios
	 WHERE se_fechabaja IS NULL
		  AND se_usuariogenerico = 'N'
		  AND se_id = NVL(:idjefe, -1)
	  UNION
	 SELECT se_id ID, InitCap(se_nombre) DETALLE                             /* Los compañeros de trabajo */
		FROM art.use_usuarios
	 WHERE se_fechabaja IS NULL
		  AND se_usuariogenerico = 'N'
		  AND se_sector = NVL(:sector, '')
	  UNION
	 SELECT se_id ID, InitCap(se_nombre) DETALLE                             /* Los otros compañeros de trabajo */
		FROM art.use_usuarios
	 WHERE se_fechabaja IS NULL
		  AND se_usuariogenerico = 'N'
		  AND se_idsector = NVL(:idsector, -1)
	  UNION
	 SELECT se_id ID, InitCap(se_nombre) DETALLE                             /* Los otros empleados a cargo */
		FROM art.use_usuarios
	 WHERE se_fechabaja IS NULL
		  AND se_usuariogenerico = 'N'
		  AND se_respondea = NVL(UPPER(:respondea), '')
	  UNION
	 SELECT se_id ID, InitCap(se_nombre) DETALLE                             /* Los empleados de los empleados a cargo */
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
$RCquery =
	"SELECT ms_id ID, ms_descripcion DETALLE
		FROM computos.cms_motivosolicitud
	 WHERE ms_idpadre = -1
		  AND ms_visible = 'S'
		  AND ms_fechabaja IS NULL
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