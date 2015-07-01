<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT se_id id, se_usuario detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$comboUsuarioOrigen = new Combo($sql, "usuarioOrigen", getUserId());
$comboUsuarioOrigen->setAddFirstItem(false);

$sql =
	"SELECT se_id id, se_usuario detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_id IN(SELECT pe_idusuario
										 FROM web.wpe_permisosintranet
										WHERE pe_idpagina = :idpagina)
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$comboUsuariosConPermiso = new Combo($sql, "usuariosConPermiso[]");
$comboUsuariosConPermiso->addParam(":idpagina", $_REQUEST["pageid"]);
$comboUsuariosConPermiso->setAddFirstItem(false);
$comboUsuariosConPermiso->setMultiple(true);

$sql =
	"SELECT se_id id, se_usuario detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$comboUsuariosDestino = new Combo($sql, "usuariosDestino[]");
$comboUsuariosDestino->setAddFirstItem(false);
$comboUsuariosDestino->setMultiple(true);

$sql =
	"SELECT se_id id, se_usuario detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_id NOT IN(SELECT pe_idusuario
												 FROM web.wpe_permisosintranet
												WHERE pe_idpagina = :idpagina)
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$comboUsuariosSinPermiso = new Combo($sql, "usuariosSinPermiso");
$comboUsuariosSinPermiso->addParam(":idpagina", $_REQUEST["pageid"]);
$comboUsuariosSinPermiso->setAddFirstItem(false);
$comboUsuariosSinPermiso->setFocus(true);
$comboUsuariosSinPermiso->setMultiple(true);
?>