<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


function GetHead($pageTitle, $styleFiles) {
?>
	
<head>
	<title><?= $pageTitle?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="Author" content="Gerencia de Sistemas" />
	<meta name="Description" content="Intranet de Provincia ART" />
	<meta name="Language" content="Spanish" />
	<meta name="Subject" content="Intranet" />
<?
	foreach ($styleFiles as $value) {
		if (substr($value, 0, 1) == "/")
			echo '<link href="'.$value.'" rel="stylesheet" type="text/css">';
		else
			echo '<link href="/styles/'.$value.'" rel="stylesheet" type="text/css">';
	}
?>
	<script language="JavaScript" src="/js/cubo.js"></script>
	<script language="JavaScript" src="/js/functions.js"></script>
	<script language="JavaScript" src="/js/grid.js"></script>
	<script language="JavaScript" src="/js/miscellaneous.js"></script>
	<script language="JavaScript" src="/js/validations.js"></script>
	<script language="JavaScript" src="/js/visor_imagenes.js?rnd=<?= date("Ymdhns")?>"></script>

	<!-- INICIO MENÚ.. -->
	<script language="JavaScript" src="/js/menu/stmenu.js"></script>
	<!-- FIN MENÚ.. -->

	<!-- INICIO CALENDARIO.. -->
	<style type="text/css">@import url(/js/Calendario/calendar-system.css);</style>
	<script type="text/javascript" src="/js/Calendario/calendar.js"></script>
	<script type="text/javascript" src="/js/Calendario/calendar-es.js"></script>
	<script type="text/javascript" src="/js/Calendario/calendar-setup.js"></script>
	<!-- FIN CALENDARIO.. -->

	<!-- INICIO HINT.. -->
	<script language="JavaScript" src="/js/hint/hints.js"></script>
	<!-- FIN HINT.. -->

	<!-- INICIO POPUP -->
	<script type="text/javascript" src="/js/popup/dhtmlwindow.js"></script>
	<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
	<!-- FIN POPUP -->
<?
}

function GetPageName($id) {
	global $conn;
	$result = "";

	if ($id != "") {
		$params = array(":id" => $id);
		$sql =
			"SELECT pi_nombre
				 FROM web.wpi_paginasintranet
				WHERE pi_id = :id";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);
		$result = $row["PI_NOMBRE"];
	}

	return $result;
}

function GetPagePath($id) {
	global $conn;
	$result = $_SERVER["DOCUMENT_ROOT"]."/modules/portada/main.php";

	if ($id > 0) {
		$params = array(":id" => $id);
		$sql =
			"SELECT pi_ruta
				 FROM web.wpi_paginasintranet
				WHERE pi_id = :id";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);
		$result = $_SERVER["DOCUMENT_ROOT"]."/".$row["PI_RUTA"];
	}

	return $result;
}

function GetPageTitle($id) {
	global $conn;
	$result = SITE_TITLE;

	if ($id != "") {
		$params = array(":id" => $id);
		$sql =
			"SELECT pi_titulo
				 FROM web.wpi_paginasintranet
				WHERE pi_id = :id";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);
		$result = $row["PI_TITULO"]." :: ".SITE_TITLE;
	}

	return $result;
}

function HasPermiso($paginaId) {
	$params = array(":id" => $paginaId);
	$sql =
		"SELECT 1
			 FROM web.wpi_paginasintranet
			WHERE pi_id = :id
				AND pi_privada = 'F'";
	$result = ExisteSql($sql, $params);

	if ($result)		// Si la página es pública..
		return true;

	$params = array(":usuario" => GetWindowsLoginName(), ":idpagina" => $paginaId);
	$sql =
		"SELECT 1
			 FROM web.wpe_permisosintranet, use_usuarios
			WHERE pe_idusuario = se_id
				AND se_usuario = UPPER(:usuario)
				AND pe_idpagina = :idpagina";
	$result = ExisteSql($sql, $params);
/*
	if (!$result) {
?>
		<SCRIPT>
			alert("Usted no tiene permiso para ingresar a esta página.");
    	window.location.href = "/index.php";
  	</SCRIPT>
</head>

<?
	}
*/
	return (($result) or (GetWindowsLoginName() == "alapaco"));
}

function IsPublicPage($paginaId) {
	$params = array(":id" => $paginaId);
	$sql =
		"SELECT 1
			 FROM web.wpi_paginasintranet
			WHERE pi_id = :id
				AND pi_privada = 'F'";

	return ExisteSql($sql, $params);
}

function LogAccess($pageId) {
	global $conn;

	$params = array(":idpagina" => $pageId, ":idusuario" => GetUserID());
	$sql =
		"INSERT INTO web.wcp_contadorpaginasintranet (cp_id, cp_idpagina, cp_idusuario, cp_fechahora)
																					VALUES (-1, :idpagina, :idusuario, SYSDATE)";
	DBExecSql($conn, $sql, $params);
}

function validarParametro($isValid) {
	if (!$isValid) {
		echo '<span id="sesionInvalidData">'.$_SERVER["REMOTE_ADDR"].' ('.gethostbyaddr($_SERVER['REMOTE_ADDR']).')</span><br />';
		echo '<span id="sesionInvalidMsg">Modo incorrecto de acceso al sistema.';
		exit;
	}
}
?>