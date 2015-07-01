<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


switch ($_REQUEST["tipo"]) {
	case "t":		// Todos..
		$sql =
			"SELECT se_id
  			 FROM use_usuarios
 				WHERE se_fechabaja IS NULL
   				AND se_usuariogenerico = 'N'
   				AND se_idsector IS NOT NULL";
		break;
	case "e":		// Empleados..
		$sql =
			"SELECT se_id
    		 FROM use_usuarios use1, usc_sectores
   			WHERE use1.se_sector = sc_codigo
     			AND use1.se_fechabaja IS NULL
     			AND use1.se_usuariogenerico = 'N'
     			AND se_idsector IS NOT NULL
     			AND NOT EXISTS(SELECT 1
                      		 FROM use_usuarios use2
                     			WHERE use1.se_usuario = use2.se_respondea)
     			AND use1.se_cargo NOT IN('GE', 'GG')
     			AND use1.se_cargo NOT IN('DIR', 'PRE')";
		break;
	case "j":		// Jefes..
		$sql =
			"SELECT se_id
  			 FROM use_usuarios use1, usc_sectores
 				WHERE use1.se_sector = sc_codigo
   				AND use1.se_fechabaja IS NULL
   				AND use1.se_usuariogenerico = 'N'
   				AND se_idsector IS NOT NULL
   				AND EXISTS(SELECT 1
                			 FROM use_usuarios use2
               				WHERE use1.se_usuario = use2.se_respondea)
   				AND use1.se_cargo NOT IN('GE', 'GG')
   				AND use1.se_cargo NOT IN('DIR', 'PRE')";
		break;
	case "jyg":		// Jefes y gerentes..
		$sql =
			"SELECT se_id
  			 FROM use_usuarios use1, usc_sectores
 				WHERE use1.se_sector = sc_codigo
   				AND use1.se_fechabaja IS NULL
   				AND use1.se_usuariogenerico = 'N'
   				AND se_idsector IS NOT NULL
   				AND EXISTS(SELECT 1
                			 FROM use_usuarios use2
               				WHERE use1.se_usuario = use2.se_respondea)
   				AND use1.se_cargo NOT IN('DIR', 'PRE')";
		break;
	case "g":		// Gerentes..
		$sql =
			"SELECT se_id
  			 FROM use_usuarios use1, usc_sectores
 				WHERE use1.se_sector = sc_codigo
   				AND use1.se_fechabaja IS NULL
   				AND use1.se_usuariogenerico = 'N'
   				AND se_idsector IS NOT NULL
   				AND EXISTS(SELECT 1
                			 FROM use_usuarios use2
               				WHERE use1.se_usuario = use2.se_respondea)
   				AND use1.se_cargo IN('GE', 'GG')
   				AND use1.se_cargo NOT IN('DIR', 'PRE')";
		break;
	case "d":		// Directores..
		$sql =
			"SELECT se_id
  			 FROM use_usuarios use1, usc_sectores
 				WHERE use1.se_sector = sc_codigo
   				AND use1.se_fechabaja IS NULL
   				AND use1.se_usuariogenerico = 'N'
   				AND se_idsector IS NOT NULL
   				AND use1.se_cargo NOT IN('GE', 'GG')
   				AND use1.se_cargo IN('DIR', 'PRE')";
		break;
	case "s":		// Seleccionados..
		$sql =
			"SELECT ue_idusuario se_id
  			 FROM rrhh.rue_usuariosxencuestas
 				WHERE ue_idencuesta = ".$_REQUEST["idencuesta"];
		break;
}

$usuarios = array();
$stmt = DBExecSql($conn, $sql);
?>
<script type="text/javascript">
	arr = Array();
<?
while ($row = DBGetQuery($stmt)) {
?>
	arr[<?= $row["SE_ID"]?>] = true;
<?
}
?>
	obj = window.parent.document.getElementById('usuarios[]');
	totAut = 0;

	for (i=0; i<obj.options.length; i++)
		if (arr[obj.options[i].value] != undefined) {
			obj.options[i].selected = true;
			totAut++;
		}
		else
			obj.options[i].selected = false;

	with (window.parent) {
		totalAutorizados = totAut;
		document.getElementById('usuariosTitulo').innerHTML = 'Usuarios Autorizados (' + totAut + ')';
	}
</script>