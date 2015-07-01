<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");

$params = array(":idusuario" => GetUserID());
$sql =
	"SELECT no_manualpla
		 FROM rrhh.rno_notificaciones
		WHERE no_idusuario = :idusuario";
$fechaAceptacion = ValorSql($sql, "", $params);
?>
<style>
	a:active {color: #00539B;}
	a:link {color: #00539B;}
	a:visited {color: #00539B;}
</style>
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/gral/manuales/lavado_de_activos/", ":: Manuales");

if ($fechaAceptacion != "")
	$list->addItem(new ItemList("manual_lavado_de_activos.pdf", "Prevención Lavado de Activos", "_blank", true));
else
	$list->addItem(new ItemList("index.php?pageid=40&fldr=gral/manuales/prevencion_lavado_activos.php", "Prevención Lavado de Activos", "_self", false, true));

$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=gral/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>