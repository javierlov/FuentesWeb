<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/DataBase/DB.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


$usuario = "";
if (isset($_REQUEST["Usuario"]))
  $usuario = $_REQUEST["Usuario"];
  
$nombre = "";
if (isset($_REQUEST["Nombre"]))
  $nombre = $_REQUEST["Nombre"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];
?>
<html>
	<head>
		<link href="/Styles/style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<form action="ejemplo_grilla.php?buscar=yes" enctype="multipart/form-data" id="formTest" method="post" name="formTest">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>Usuario: <input class="FormInputText" id="Usuario" maxlength="50" name="Usuario" size="50" type="text" value="<?= $usuario?>"></td>
				<td>Nombre: <input class="FormInputText" id="Nombre" maxlength="50" name="Nombre" size="50" type="text" value="<?= $nombre?>"></td>
			</tr>
			<tr>
				<td colspan="2"><input class="BotonBuscar" id="btnBuscar" name="btnBuscar" type="submit" value="Buscar"></td>
			</tr>
		</table>
		</form>
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$where = "";
	if ($usuario != "")
		$where.= " AND UPPER(se_usuario) like UPPER('%".$usuario."%')";
	if ($nombre != "")
		$where.= " AND UPPER(se_nombre) like UPPER('%".$nombre."%')";

	$sql = 
		"SELECT se_usuario, se_nombre, se_mail
  	   FROM use_usuarios
	    WHERE se_fechabaja is null
	   	  AND se_usuariogenerico = 'N'".$where.
	"ORDER BY se_nombre";

	$grilla = new Grid(array("Nombre", "Usuario", "E-Mail"), array(240, 80, 240));
	$grilla->setExtraFields("&Usuario=".$usuario."&Nombre=".$nombre);
	$grilla->setPageNumber($pagina);
	$grilla->setSql($sql);
	$grilla->Draw();
}
?>
	</body>
</html>