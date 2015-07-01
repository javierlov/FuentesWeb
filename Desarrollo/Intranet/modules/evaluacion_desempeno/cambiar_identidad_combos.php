<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$params = array();
$sql =
	"SELECT se_usuario id, se_usuario detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$comboUsuario = new Combo($sql, "usuario");
$comboUsuario->setAddFirstItem(false);
$comboUsuario->setFocus(true);
?>