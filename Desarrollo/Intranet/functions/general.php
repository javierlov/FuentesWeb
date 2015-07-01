<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


function getCabecera() {
	$params = array(":url" => $_SERVER["REQUEST_URI"]);
	$sql =
		"SELECT mi_imagencabecera
			 FROM web.wmi_menuintranet
			WHERE mi_url = SUBSTR(:url, 0, LENGTH(mi_url))";
	$result = valorSql($sql, "", $params);
	if ($result != "")
		$result = "background-image:url(/images/encabezado_secciones/".$result.".jpg);";

	return $result;
}

function getEmailsAviso() {
	global $conn;

	$result = array();

	$sql =
		"SELECT se_mail
			 FROM use_usuarios
			WHERE se_recibeemailintranet = 'S'";
	$stmt = DBExecSql($conn, $sql);
	while ($row = DBGetQuery($stmt))
		$result[] = $row["SE_MAIL"];

	return $result;
}

function getHead($pageTitle, $styleFiles) {
?>
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
			echo '<link href="/css/'.$value.'" rel="stylesheet" type="text/css">';
	}
?>
	<script language="JavaScript" src="/js/functions.js"></script>
	<script language="JavaScript" src="/js/agrandar_imagen.js"></script>
	<script language="JavaScript" src="/js/grid.js"></script>
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

function getPageName($id) {
	$params = array(":url" => $_SERVER["REQUEST_URI"]);
	$sql =
		"SELECT UPPER(mi_texto)
			 FROM web.wmi_menuintranet
			WHERE mi_url = SUBSTR(:url, 0, LENGTH(mi_url))";
	$result = valorSql($sql, "", $params);
	if (($result == "") and ($id != "")) {
		$params = array(":id" => $id);
		$sql =
			"SELECT pi_nombre
				 FROM web.wpi_paginasintranet
				WHERE pi_id = :id";
		$result = valorSql($sql, "", $params);
	}

	return $result;
}

function getPagePath($id) {
	$result = $_SERVER["DOCUMENT_ROOT"]."/modules/portada/main.php";

	if ($id > 0) {
		$params = array(":id" => $id);
		$sql =
			"SELECT pi_ruta
				 FROM web.wpi_paginasintranet
				WHERE pi_id = :id";
		$result = $_SERVER["DOCUMENT_ROOT"]."/".valorSql($sql, "", $params);

		// Dibujo el título..
		echo '<div id="divFondoSeccion" style="'.getCabecera().'"><div id="divTituloSeccion">'.getPageName($id).'</div></div>';
	}

	return $result;
}

function getPageTitle($id) {
	$result = SITE_TITLE;

	if ($id != "") {
		$params = array(":id" => $id);
		$sql =
			"SELECT pi_titulo
				 FROM web.wpi_paginasintranet
				WHERE pi_id = :id";
		$result = valorSql($sql, "", $params)." :: ".SITE_TITLE;
	}

	return $result;
}

function hasPermiso($paginaId) {
	$params = array(":id" => $paginaId);
	$sql =
		"SELECT 1
			 FROM web.wpi_paginasintranet
			WHERE pi_id = :id
				AND pi_privada = 'F'";
	$result = existeSql($sql, $params);

	if ($result)		// Si la página es pública..
		return true;

	$params = array(":usuario" => getWindowsLoginName(true), ":idpagina" => $paginaId);
	$sql =
		"SELECT 1
			 FROM web.wpe_permisosintranet, use_usuarios
			WHERE pe_idusuario = se_id
				AND se_usuario = :usuario
				AND pe_idpagina = :idpagina";
	$result = existeSql($sql, $params);

	return $result;
/*
	return (($result) or (getWindowsLoginName() == "alapaco") or
											 (getWindowsLoginName() == "aangiolillo") or
											 (getWindowsLoginName() == "evila") or
											 (getWindowsLoginName() == "npereira") or
											 (getWindowsLoginName() == "smarzano"));
*/
}

function isPublicPage($paginaId) {
	$params = array(":id" => $paginaId);
	$sql =
		"SELECT 1
			 FROM web.wpi_paginasintranet
			WHERE pi_id = :id
				AND pi_privada = 'F'";

	return existeSql($sql, $params);
}

function logUrlIn($url) {
	global $conn;

	$curs = null;
	$params = array(":idusuario" => getUserId(), ":url" => $url);
	$sql = "BEGIN art.intranet.get_id_estadistica(:data, :idusuario, :url); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs);

	return $row[0];
}

function setUrlAmigable($url) {
	return removeAccents(str_replace(array(" ", "¦", "ñ"), array("_", "", "n"), $url));
}

function showErrorIntranet($title, $msg) {
	if ($title != "") {
?>
		<h1>
			<br />
			<?= $title ?>
		</h1>
<?
	}
?>
	<div id="divError">
		<b><?= $msg; ?></b>
		<br />
		<br />
		<?= 'Usted ha sido identificado como ' . gethostbyaddr($_SERVER["REMOTE_ADDR"]); ?>
	</div>
<?
}

function validarIngresoPrimeraVez() {
	global $conn;

	$params = array(":id" => getUserId());
	$sql =
		"SELECT 1
			 FROM tmp.tip_intranetprimeravez
			WHERE ip_id = :id";
	if (!existeSql($sql, $params))
		header("Location: http://".$_SERVER["HTTP_HOST"]."/index_primera_vez.php?rnd=".date("Ymdhis"));
}

function validarParametro($isValid) {
	if (!$isValid) {
		echo '<span id="sesionInvalidData">'.$_SERVER["REMOTE_ADDR"].' ('.gethostbyaddr($_SERVER['REMOTE_ADDR']).')</span><br />';
		echo '<span id="sesionInvalidMsg">Modo incorrecto de acceso al sistema.';
		exit;
	}
}
?>