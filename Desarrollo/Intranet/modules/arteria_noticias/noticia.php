<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


$params = array(":idboletin" => $_REQUEST["b"], ":posicion" => $_REQUEST["n"]);
$sql =
	"SELECT ba_ano, ba_emailscontacto, ba_fecha, ba_numero, na_altoimagenes, na_anchoimagenes, na_colortitulo, na_id, na_nota, na_numeroplantilla, na_titulo, na_visible
		 FROM rrhh.rna_noticiasarteria, rrhh.rba_boletinesarteria
		WHERE na_idboletin = ba_id
			AND na_idboletin = :idboletin
			AND na_posicion = :posicion";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$numeroPlantilla = 1;
if ($row["NA_NUMEROPLANTILLA"] != "")
	$numeroPlantilla = $row["NA_NUMEROPLANTILLA"];

$vals = explode("/", $row["BA_FECHA"]);
$fecha = getDayName(date("N", strtotime($vals[2]."-".$vals[1]."-".$vals[0])))." ".$vals[0]." de ".GetMonthName($vals[1])." de ".$vals[2];

$ano = "Año ".decimalToRomana($row["BA_ANO"]);
$numero = "Número ".decimalToRomana($row["BA_NUMERO"]);

$modo = "r";		// Real..

$idEstadistica = logUrlIn($_SERVER["REQUEST_URI"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>ARTeria Noticias - <?= $row["NA_TITULO"]?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="ARTeria Noticas" />
		<meta name="Language" content="Spanish" />
		<meta name="Subject" content="ARTeria Noticas" />
		<link href="/modules/arteria_noticias/css/style.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="/js/functions.js"></script>
	</head>
	<body onLoad="onLoadBody()">
		<input id="idEstadistica" type="hidden" value="<?= $idEstadistica?>" />
		<table cellpadding="0" cellspacing="0" align="center" width="745">
			<tr><td colspan="2"><img src="/modules/arteria_noticias/images/header.jpg" usemap="#header" /></td></tr>
			<tr><td align="left" colspan="2" class="TituloBlanco" height="42" background="/modules/arteria_noticias/fondo_titulos/fondo_grande_<?= $row["NA_COLORTITULO"]?>.jpg"><?= $row["NA_TITULO"]?></td></tr>
			<tr><td colspan="2" height="5"></td></tr>
<?
if (($_REQUEST["n"] < 5) OR ($_REQUEST["n"] >= 5 AND $row["NA_VISIBLE"] == 'S'))
	include($_SERVER["DOCUMENT_ROOT"]."/modules/mantenimiento/abm_arteria_noticias/plantillas_noticia/plantilla_".$numeroPlantilla.".php");
else
	echo "<tr><td colspan=\"2\"><br /><br /><br />Nota no disponible.<br /><br /><br /></td></tr>";
?>
			<tr><td colspan="2" height="10"></td></tr>
			<tr>
				<td colspan="2" height="45">
					<table cellpadding="0" cellspacing="0" width="100%" background="/modules/arteria_noticias/images/footer.jpg" height="45">
						<tr>
							<td align="left" class="PieFecha"><?= $fecha?> - Año <?= $ano?> - Número <?= $numero?></td>
							<td class="PieMenu"></td>
							<td align="right"><a href="/"><img src="/modules/arteria_noticias/images/logo_art.jpg" title="Volver a la Intranet" /></a></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<map id="header" name="header">
			<area alt="ARTeria Noticias" coords="16, 16, 196, 92" href="/arteria-noticias" shape="rect" />
			<area alt="Ediciones Anteriores" coords="670, 84, 688, 100" href="/arteria-noticias-ediciones-anteriores" shape="rect" />
			<area alt="Contáctenos" coords="692, 84, 712, 100" href="mailto:<?= $row["BA_EMAILSCONTACTO"]?>?subject=Contacto desde ARTeria Noticias" shape="rect" />
		</map>
	</body>
</html>