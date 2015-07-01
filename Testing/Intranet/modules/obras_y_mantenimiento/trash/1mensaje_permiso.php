<?
  require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");

  global $conn;

  $sql = "SELECT InitCap(SE_NOMBRE)
            FROM ART.USE_USUARIOS, COMPUTOS.CMS_MOTIVOSOLICITUD
           WHERE MS_ID = ".$_REQUEST['param2']."
             AND SE_ID = COMPUTOS.GENERAL.GET_USUARIORESPONSABLE(".$_REQUEST['param1'].", MS_NIVEL)";
  $stmt = DBExecSql($conn, $sql);
  $user = ValorSql($sql);

  if ($user != "") {
    $msg = "<br/><error_label>".htmlentities("Se solicitará autorización a ".$user)."</error_label>";
    echo $msg;
  }
?>