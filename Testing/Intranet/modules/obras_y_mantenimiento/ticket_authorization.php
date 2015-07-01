  <div id="stylized" class="formGeneric" style="font-size:12px; width:500px;">
    <form action="ticket_authorization_save.php" id="formAutorizacion" name="formAutorizacion"
          method="post" onSubmit="return ValidarFormAutorizacion(formAutorizacion)">
      <b>Solicitud a Sistemas</b>
      <br />
      <p>Este módulo le permitirá autorizar o rechazar un pedido que se realizó a la Gerencia de Sistemas</p>

      <input type="hidden" id="id" name="id" value="<?=$_REQUEST["id"]?>" />
      <input type="hidden" id="back_button" name="back_button" value="no" />
      <input type="hidden" id="back_button" name="close_button" value="yes" />

      <label>Autorización
        <span class="small">¿Autoriza la realización del pedido?</span>
      </label>
      <div id="DivAutoriza">
        <select class="Combo" id="autoriza" name="autoriza">
        </select>
      </div>

      <label>Comentario
        <span class="small">Acerca de la autorización<br />(1000 caracteres)</span>
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

$RCfield = "autoriza";
$RCparams = array();
$RCquery =
	"SELECT ID, DETALLE
		FROM (SELECT 'S' ID, 'Sí' DETALLE
					  FROM DUAL
			  UNION ALL
				  SELECT 'N' ID, 'No' DETALLE
					  FROM DUAL) AUTORIZA
	 WHERE 1 = 1 ";
$RCselectedItem = $_REQUEST["authorize"];
FillCombo();
?>
</script>