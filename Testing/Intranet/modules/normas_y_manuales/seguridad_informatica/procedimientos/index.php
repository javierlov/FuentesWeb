<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


function mostrarArchivo() {
	// Traigo los id de Auditoría, Control de Gestión y Sistemas..
	$sql =
		"SELECT cse3.se_id
			FROM use_usuarios useu, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3
		  WHERE useu.se_idsector = cse.se_id
			  AND cse.se_idsectorpadre = cse2.se_id
			  AND cse2.se_idsectorpadre = cse3.se_id
			  AND useu.se_usuario = UPPER(:usuario)";
	$params = array(":usuario" => GetWindowsLoginName());
	$idGerencia = ValorSql($sql, "", $params);

	// Traigo los id de los cargos Gerente, Director y Responsable..
	$sql =
		"SELECT se_cargo
			FROM art.use_usuarios
		  WHERE se_usuario = UPPER(:usuario)";
	$params = array(":usuario" => GetWindowsLoginName());
	$idCargo = ValorSql($sql, "", $params);

	return in_array($idGerencia, array(2009, 3008, 3010)) or in_array($idCargo, array('DIR', 'GE', 'GG', 'RES'));
}
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/seguridad_informatica/procedimientos/", ":: Procedimientos");
if (mostrarArchivo())
	$list->addItem(new ItemList("GG-01_Gestion_de_seguridad_informatica.pdf", "Gestión de Seguridad Informática", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/icono_descargable.jpg");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>