<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


$params = array(":idusuario" => getUserID());
$sql =
	"SELECT no_manualpla
		 FROM rrhh.rno_notificaciones
		WHERE no_idusuario = :idusuario";
$fechaAceptacionPla = valorSql($sql, "", $params);

$params = array(":idusuario" => getUserID());
$sql =
	"SELECT no_manualfraude
		 FROM rrhh.rno_notificaciones
		WHERE no_idusuario = :idusuario";
$fechaAceptacionFraude = valorSql($sql, "", $params);

$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/corporativa/manuales/", ":: Manuales");

if ($fechaAceptacionFraude != "")
	$list->addItem(new ItemList("Manual_Prevencion_de_Fraude.pdf", "Manual Prevención de Fraude", "_blank", true));
else
	$list->addItem(new ItemList("/modules/normativa_interna/corporativa/manuales/control_prevencion_fraude.php", "Manual Prevención de Fraude", "_blank", false, true));

if ($fechaAceptacionPla != "")
	$list->addItem(new ItemList("manual_lavado_de_activos.pdf", "Prevención Lavado de Activos", "_blank", true));
else
	$list->addItem(new ItemList("/normativa-interna/corporativa/manuales/prevencion_lavado_activos.php", "Prevención Lavado de Activos", "_self", false, true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/corporativa/index.php";
?>