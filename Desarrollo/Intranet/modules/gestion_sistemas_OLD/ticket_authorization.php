<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


/* Implementación de múltiples sistemas dentro del sistema de tickets */
if (isset($_REQUEST["sistema"]))
  $sistema = $_REQUEST["sistema"];
else
  $sistema = 1;
  
$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ss_notas as notas, ss_observaciones as observaciones, 1000 - length(ss_notas) as longitud
		 FROM computos.css_solicitudsistemas
		WHERE ss_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>

  <div id="stylized" class="formGeneric" style="font-size:12px; width:500px;">
    <form action="ticket_authorization_save.php?sistema=<?echo $sistema;?>" id="formAutorizacion" name="formAutorizacion"
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
<?
$sql =
	"SELECT id, detalle
		 FROM (SELECT 'S' ID, 'Sí' DETALLE
						 FROM DUAL
				UNION ALL
					 SELECT 'N' ID, 'No' DETALLE
						 FROM DUAL) AUTORIZA
		WHERE 1 = 1 ";
$comboAutoriza = new Combo($sql, "autoriza", $_REQUEST["authorize"]);
$comboAutoriza->setClass("Combo");
$comboAutoriza->draw();
?>
      </div>

      <label>Comentario
        <span class="small">Acerca de la autorización<br />(Hasta <?= $row["LONGITUD"] ?> caracteres)</span>
      </label>
      <textarea rows="3" name="comentarios" id="comentarios"></textarea>

      <button type="submit" class="btnAction">Aceptar</button>
      <div class="msg_label" id="DivAreaMensajes" style="text-align:left;"><br />Gracias por su tiempo.</div>
      <div class="spacer"></div>
    </form>
  </div>