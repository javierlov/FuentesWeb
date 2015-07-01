<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");

global $conn;

$sql =
	"SELECT MS_EJECUTABLEOBLIGATORIO
		FROM COMPUTOS.CMS_MOTIVOSOLICITUD
	 WHERE MS_ID = :id";
$params = array(":id" => $_REQUEST['param1']);
$ver_combo = ValorSQL($sql, "", $params);

  if ($ver_combo == 'S') {
    $label = '<label>'.htmlentities("Aplicación").'<span class="small">'.htmlentities("Elija una aplicación del portal").'</span></label>';

    $sql = "SELECT EJ_ID AS ID, EJ_DESCRIPCION AS DETALLE
              FROM COMUNES.CEJ_EJECUTABLE
             WHERE EJ_FECHABAJA IS NULL
               AND((EJ_ACTIVO = 'S'
               AND EXISTS(SELECT 1
                            FROM COMUNES.CPE_PERFILEJECUTABLE
                           WHERE PE_IDEJECUTABLE = EJ_ID
                             AND PE_IDGRUPO = :idgrupo))
                OR UPPER(EJ_DESCRIPCION) LIKE '%INTRANET%')
          ORDER BY 2";
	$params = array(":idgrupo" => GetUserSector());
    $stmt = DBExecSql($conn, $sql, $params);

    //creo las distintas opciones del select
    $opciones = '<select class="Combo" id="Ejecutable" name="Ejecutable">
                 <option value="-1">- SELECCIONAR -</option>';
    while ($row = DBGetQuery($stmt, 0)) {
      $opciones .= '<option value="' . htmlentities($row[0]) . '">' . htmlentities($row[1]) . '</option>';
      }

    $opciones .= "</select>";
    echo $label.$opciones;
  }
?>