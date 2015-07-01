  <div id="stylized" class="formGeneric" style="font-size:12px; width:500px;">
    <form action="ticket_qualification_save.php" id="formCalificacion" name="formCalificacion"
          method="post" onSubmit="return ValidarFormCalificacion(formCalificacion)">
      <b>Solicitud a Sistemas</b>
      <br />
      <p>Este módulo le permitirá calificar la resolución obtenida por parte de la Gerencia de Sistemas</p>

      <input type="hidden" id="id" name="id" value="<?=$_REQUEST["id"]?>" />
      <input type="hidden" id="back_button" name="back_button" value="no" />

      <label>Resolución
        <span class="small">¿Se ha realizado su pedido?</span>
      </label>
      <div id="DivDetalleResuelto">
        <select class="Combo" id="resuelto" name="resuelto">
        </select>
      </div>

      <label>Calidad de resolución
        <span class="small">¿Cómo calificaría la resolución de su pedido?</span>
      </label>
      <select class="Combo" id="calificacion" name="calificacion">
      </select>

      <label>Comentario
        <span class="small">Acerca de la resolución<br />(1000 caracteres)</span>
      </label>
      <textarea rows="3" name="comentarios" id="comentarios"></textarea>

      <button type="submit" class="btnAction">Aceptar</button>
      <div class="msg_label" id="DivAreaMensajes" style="text-align:left;"><br />Gracias por su tiempo.</div>
      <div class="spacer"></div>
    </form>
  </div>

<script type="text/javascript">
<?
// FillCombos..
$excludeHtml = True;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "resuelto";
$RCparams = array();
$RCquery = "SELECT ID, DETALLE
                FROM (SELECT 'S' ID, 'Sí' DETALLE
                        FROM DUAL
                       UNION ALL
                      SELECT 'N' ID, 'No' DETALLE
                        FROM DUAL
                       UNION ALL
                      SELECT '?' ID, 'Desconozco' DETALLE
                        FROM DUAL) PRIORIDADES
               WHERE 1 = 1 ";
$RCselectedItem = -1;
FillCombo();

$RCfield = "calificacion";
$RCparams = array();
$RCquery = "SELECT ca_id ID, ca_descripcion DETALLE
                FROM computos.cca_calificacion
           ORDER BY 1";
$RCselectedItem = -1;
FillCombo();
?>
</script>