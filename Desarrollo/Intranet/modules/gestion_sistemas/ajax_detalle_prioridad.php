<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/constants.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db_funcs.php");

global $conn;

$sql = "SELECT ID, DETALLE
            FROM (SELECT 1 ID, 'Alta' DETALLE
                    FROM DUAL
                   UNION ALL
                  SELECT 2 ID, 'Media' DETALLE
                    FROM DUAL
                   UNION ALL
                  SELECT 3 ID, 'Baja' DETALLE
                    FROM DUAL) PRIORIDADES
           WHERE 1 = 1
             AND ID >= (SELECT ms_maximaprioridad 
                          FROM computos.CMS_MOTIVOSOLICITUD 
                         WHERE ms_id = :id)";
$params = array(":id" => $_REQUEST['param1']);
$stmt = DBExecSql($conn, $sql, $params);

//creo las distintas opciones del select
$opciones = '<select class="Combo" id="Prioridad" name="Prioridad"><option value="-1">- SELECCIONAR -</option>';
while ($row = DBGetQuery($stmt, 0)) {
    $opciones .= '<option value="' . htmlentities($row[0]) . '">' . htmlentities($row[1]) . '</option>';
}

$opciones .= '</select>';
echo $opciones;
?>