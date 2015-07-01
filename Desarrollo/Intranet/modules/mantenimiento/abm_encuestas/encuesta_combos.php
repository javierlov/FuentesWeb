<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT 1, 't' id, 'Todos' detalle FROM DUAL
UNION ALL
	 SELECT 2, 'e', 'Empleados' FROM DUAL
UNION ALL
	 SELECT 3, 'j', 'Jefes' FROM DUAL
UNION ALL
	 SELECT 4, 'jyg', 'Jefes y Gerentes' FROM DUAL
UNION ALL
	 SELECT 5, 'g', 'Gerentes' FROM DUAL
UNION ALL
	 SELECT 6, 'd', 'Directores' FROM DUAL
 ORDER BY 1";
$comboTiposUsuario = new Combo($sql, "tiposUsuario");
$comboTiposUsuario->setAddFirstItem(false);
$comboTiposUsuario->setOnChange("seleccionarUsuarios(this.value)");

$sql =
	"SELECT se_id id, se_nombre detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
			AND se_idsector IS NOT NULL
 ORDER BY 2";
$comboUsuarios = new Combo($sql, "usuarios[]");
$comboUsuarios->setAddFirstItem(false);
$comboUsuarios->setClass("selectUsuarios");
$comboUsuarios->setMultiple(true);
$comboUsuarios->setOnChange("contarUsuariosSeleccionados(this)");
?>