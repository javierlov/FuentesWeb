<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/constants.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db_funcs.php");

require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/CrearLog.php");

global $conn;

$sql = "SELECT MS_ID AS ID, MS_DESCRIPCION AS DETALLE
            FROM COMPUTOS.CMS_MOTIVOSOLICITUD
           WHERE MS_VISIBLE = 'S'
             AND MS_FECHABAJA IS NULL
             AND MS_IDPADRE = :idpadre
        ORDER BY 2";
$params = array(":idpadre" => $_REQUEST['param1']);
if (!is_numeric($_REQUEST['param1'])) {
    return '';
}

$style = '';
if (isset($_REQUEST['style']))
    $style = $_REQUEST['style'];

$stmt = DBExecSql($conn, $sql, $params);

//creo las distintas opciones del select
$opciones = '<select class="GICombo" style="' . $style . '"
					id="DetallePedido" name="DetallePedido"
                       onchange="CambioDetallePedido();" >
               <option value="-1">- SELECCIONAR -</option>';

if (intval($_REQUEST['param1']) > -1) {
    while ($row = DBGetQuery($stmt, 0)) {
        $opciones .= '<option value="' . $row[0] . '">' . htmlentities($row[1], ENT_QUOTES, CHARSET) . '</option>';
    }
}

$opciones .= "</select>";
echo $opciones;
?>