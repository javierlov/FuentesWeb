<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");

/* Implementación de múltiples sistemas dentro del sistema de tickets */

$sistema = GetParametro("sistema", 1); 
$arrayConfig = GetConfigSistema($sistema);
$textoHeader = $arrayConfig['ST_HEADER'];
$textoSubHeader = $arrayConfig['ST_SUBHEADER_INFO'];

$sql = "SELECT ss_notas as notas, ss_observaciones as observaciones, 1000 - length(ss_notas) as longitud
          FROM computos.css_solicitudsistemas
         WHERE ss_id = :id";
$params = array(":id" => $_REQUEST["id"]);
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>

  <div id="stylized" class="formGeneric" style="font-size:12px; width:500px;">
    <form action="ticket_information_save.php?sistema=<?  echo  $sistema; ?>" id="formInformacion" name="formInformacion"
          method="post" onSubmit="return ValidarFormInformacion(formInformacion)">
      <b><?  echo  $textoHeader;?></b>
      <br />
      <p><?  echo  $textoSubHeader;?></p>

      <input type="hidden" id="id" name="id" value="<?= $_REQUEST["id"]?>" />
      <input type="hidden" id="back_button" name="back_button" value="no" />
      <input type="hidden" id="back_button" name="close_button" value="no" />

      <label  class="labelTitulo" >Notas
        <span class="small">Pedido original<br /></span>
      </label>
      <textarea rows="3" name="notas" id="notas" readonly="true"><?=  $row["NOTAS"] ?></textarea>

      <label class="labelTitulo" >Observaciones
        <span class="small">Información solicitada<br /></span>
      </label>
      <textarea rows="3" name="observaciones" id="observaciones" readonly="true"><?=  $row["OBSERVACIONES"] ?></textarea>

      <label class="labelTitulo" >Mas información
        <span class="small">Acerca de lo solicitado<br />(Hasta <?=  $row["LONGITUD"] ?> caracteres)</span>
      </label>
      <textarea rows="3" name="comentarios" id="comentarios"></textarea>

	  <div style="text-align:center;">
		<button type="submit" class="btnAction">Aceptar</button>
	  </div>
	  
      <div class="msg_label" id="DivAreaMensajes" style="text-align:left;"><br />Gracias por su tiempo.</div>
      <div class="spacer"></div>
    </form>
  </div>