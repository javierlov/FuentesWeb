<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT se_nombre
		 FROM use_usuarios
		WHERE se_id = :id";
$body = GetWindowsLoginName(true)." informa que los datos de ".ValorSql($sql, "", $params)." son incorrectos.";

$curs = null;
$params = array(":direccionorigen" => "Intranet",
								":direccionesdestino" => "vdominguez@provart.com.ar",
								":motivo" => "Error Datos Internos",
								":cuerpo" => $body,
								":nombreadjunto" => NULL,
								":ubicacion" => NULL,
								":idejecutable" => NULL);
$sql = "BEGIN art.varios.do_insertartablamails(:direccionorigen, :direccionesdestino, :motivo, :cuerpo, :nombreadjunto, :ubicacion, :idejecutable); END;";
$stmt = DBExecSP($conn, $curs, $sql, $params, false);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Intranet de Provincia ART" />
		<meta name="Language" content="Spanish" />
		<meta name="Subject" content="Intranet" />
		<link href="/Styles/style.css" rel="stylesheet" type="text/css" />
		<title>Aviso Registrado</title>
		<script> 
			setTimeout("window.close();", 3000);
		</script>
	</head>
	<body bgcolor="#C0C0C0">
		<table border="0" width="100%" height="100%">
			<tr>
				<td align="center" class="FormLabelBlancoGrande">Aviso Registrado</td>
			</tr>
		</table>
	</body>
</html>