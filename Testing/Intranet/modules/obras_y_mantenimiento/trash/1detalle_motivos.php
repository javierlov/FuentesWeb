<?
  require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");

  global $conn;

  $sql = "SELECT MS_ID AS ID, MS_DESCRIPCION AS DETALLE 
            FROM COMPUTOS.CMS_MOTIVOSOLICITUD 
           WHERE MS_VISIBLE = 'S' 
             AND MS_IDPADRE = ".$_REQUEST['param1']." 
        ORDER BY 2";
  $stmt = DBExecSql($conn, $sql);

  //creo las distintas opciones del select
  $opciones = '<select class="Combo" id="DetallePedido" name="DetallePedido" onchange="ValidarPermisoUsuario();"><option value="0">- SELECCIONAR -</option>';
  while ($row = DBGetQuery($stmt, 0)) {
    $opciones .= '<option value="' . htmlentities($row[0]) . '">' . htmlentities($row[1]) . '</option>';
    }

  $opciones .= "</select>";
  echo $opciones;
?>