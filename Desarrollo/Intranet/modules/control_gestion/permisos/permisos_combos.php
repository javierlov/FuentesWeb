<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT se_usuario id, se_usuario detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuario NOT IN(SELECT pt_usuario
															FROM web.wpt_permisostablerocontrol
														 WHERE pt_fechabaja IS NULL)
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$comboUsuariosSinPermiso = new Combo($sql, "usuariosSinPermiso");
$comboUsuariosSinPermiso->setAddFirstItem(false);
$comboUsuariosSinPermiso->setFocus(true);
$comboUsuariosSinPermiso->setMultiple(true);

$sql =
	"SELECT se_usuario id, se_usuario detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuario IN(SELECT pt_usuario
													FROM web.wpt_permisostablerocontrol
												 WHERE pt_fechabaja IS NULL)
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$comboUsuariosConPermiso = new Combo($sql, "usuariosConPermiso[]");
$comboUsuariosConPermiso->setAddFirstItem(false);
$comboUsuariosConPermiso->setClass("usuariosConPermiso");
$comboUsuariosConPermiso->setMultiple(true);
$comboUsuariosConPermiso->setOnChange("cambiarUsuario(this.value)");
?>