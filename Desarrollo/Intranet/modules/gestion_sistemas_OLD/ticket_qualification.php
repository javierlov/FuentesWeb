<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once("ticket_qualification_combos.php");


/* Implementaci�n de m�ltiples sistemas dentro del sistema de tickets */
if (isset($_REQUEST["sistema"]))
  $sistema = $_REQUEST["sistema"];
else
  $sistema = 1;
?>             

  <div id="stylized" class="formGeneric" style="font-size:12px; width:500px;">
    <form action="ticket_qualification_save.php?sistema=<?echo $sistema;?>" id="formCalificacion" name="formCalificacion" method="post" onSubmit="return ValidarFormCalificacion(formCalificacion)">
      <b>Solicitud a Sistemas</b>
      <br />
      <p>Este m�dulo le permitir� calificar la resoluci�n obtenida por parte de la Gerencia de Sistemas</p>

      <input type="hidden" id="id" name="id" value="<?=$_REQUEST["id"]?>" />
      <input type="hidden" id="back_button" name="back_button" value="no" />

      <label>Resoluci�n
        <span class="small">�Se ha realizado su pedido?</span>
      </label>
      <div id="DivDetalleResuelto">
				<?= $comboResuelto->draw();?>
      </div>

      <label>Calidad de resoluci�n
        <span class="small">�C�mo calificar�a la resoluci�n de su pedido?</span>
      </label>
			<?= $comboCalificacion->draw();?>

      <label>Comentario
        <span class="small">Acerca de la resoluci�n<br />(1000 caracteres)</span>
      </label>
      <textarea rows="3" name="comentarios" id="comentarios"></textarea>

      <button type="submit" class="btnAction">Aceptar</button>
      <div class="msg_label" id="DivAreaMensajes" style="text-align:left;"><br />Gracias por su tiempo.</div>
      <div class="spacer"></div>
    </form>
  </div>