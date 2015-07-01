<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");

/* Implementación de múltiples sistemas dentro del sistema de tickets */
if (isset($_REQUEST["sistema"]))
  $sistema = $_REQUEST["sistema"];
else
  $sistema = 1;

$estado = 3;

// Se setea esta variable que se utiliza en el trigger trg_css_permisosolicitud de la tabla computos.css_solicitudsistemas..
$curs = null;
$params = array(":usuario" => GetWindowsLoginName());
$sql = "BEGIN COMPUTOS.GENERAL.v_nombreusuario := UPPER(:usuario); END;";
$stmt = DBExecSP($conn, $curs, $sql, $params, false);

// Guardo la autorización del pedido en la tabla..
$sql = "UPDATE computos.css_solicitudsistemas
           SET ss_fechamodif = SYSDATE,
      	       ss_idusumodif = :idusumodif,
      	       ss_idestadoactual = :idestadoactual,
               ss_notas = SUBSTR(ss_notas || CHR(10) || CHR(13) || :comentarios,1,1000),
               ss_observaciones = :comentarios
         WHERE ss_id = :id";
$params = array(":idusumodif" => GetUserID(),
                ":idestadoactual" => $estado,
                ":comentarios" => $_REQUEST["comentarios"],
                ":id" => $_REQUEST["id"]);
DBExecSql($conn, $sql, $params);
?>
<html>
<head>
  <link href="http://ntintraweb/styles/style_sistemas.css?sid=<?= date('YmdHis'); ?>" rel="stylesheet" type="text/css" />
  <meta http-equiv="Refresh" content="0; url=index.php?sistema=<?echo $sistema;?>&ticket_detail=yes&id=<?= $_REQUEST["id"] ?>">
</head>
<body>
  Procesando la información añadida...
</body>
</html>