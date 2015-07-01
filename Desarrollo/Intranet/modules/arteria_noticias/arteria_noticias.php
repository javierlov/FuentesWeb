<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getCopete($num, $max) {
	global $host;
	global $row;

	$params = array(":idboletin" => $row["BA_ID"], ":posicion" => $num);
	$sql =
		"SELECT DBMS_LOB.SUBSTR(na_nota, ".$max.", 1) || '...'
			 FROM rrhh.rna_noticiasarteria
			WHERE na_idboletin = :idboletin
				AND na_posicion = :posicion";
	return strip_tags(htmlspecialchars_decode(valorSql($sql, "", $params), ENT_QUOTES))."&nbsp;&nbsp;<span class='LeerMas'><a href='http://".$_SERVER["HTTP_HOST"]."/nada'></a><a href='".$host."/arteria-noticias/".$row["BA_ID"]."/".$num."'>Leer más &gt;&gt;</a></span>";
}

function getColor($num) {
	global $row;

	$params = array(":idboletin" => $row["BA_ID"], ":posicion" => $num);
	$sql =
		"SELECT na_colortitulo
			 FROM rrhh.rna_noticiasarteria
			WHERE na_idboletin = :idboletin
				AND na_posicion = :posicion";

	return valorSql($sql, "", $params);
}

function getTitle($num) {
	global $row;

	$params = array(":idboletin" => $row["BA_ID"], ":posicion" => $num);
	$sql =
		"SELECT na_titulo
			 FROM rrhh.rna_noticiasarteria
			WHERE na_idboletin = :idboletin
				AND na_posicion = :posicion";
	$result =	valorSql($sql, "", $params);

	if ($result == "")
		$result = "<span style='color:#f00;'>Título ".$num."</span>";

	return $result;
}

function isNoticiaVisible($num) {
	global $row;

	$params = array(":idboletin" => $row["BA_ID"], ":posicion" => $num);
	$sql =
		"SELECT 1
			 FROM rrhh.rna_noticiasarteria
			WHERE na_visible = 'S'
				AND na_idboletin = :idboletin
				AND na_posicion = :posicion";
	return existeSql($sql, $params);
}


$host = "http://".$_SERVER["HTTP_HOST"];

if (!isset($esEnvio)) {
	$sql =
		"SELECT ba_id
			 FROM rrhh.rba_boletinesarteria
			WHERE ba_estadoenvio = 'E'
	 ORDER BY ba_fecha DESC";
	$_REQUEST["id"] = valorSql($sql);
}



if (!isset($_REQUEST["id"])) {
//if (!isset($esEnvio)) {
	echo "<h5>El boletín ARTeria Noticias estará para consultar desde la web proximamente..</h5>";
	exit;
}

$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ba_ano, ba_emailscontacto, ba_extensionimagen, ba_fecha, ba_id, ba_numero
		 FROM rrhh.rba_boletinesarteria
		WHERE ba_fechabaja IS NULL
			AND ba_id = :id
 ORDER BY ba_fecha DESC";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$imgPortada = "/modules/mantenimiento/abm_arteria_noticias/images/portada.jpg";
if ($row["BA_EXTENSIONIMAGEN"] != "") {
	$imgPortada = IMAGES_ARTERIA_PATH."portada\\".$row["BA_ID"].".".$row["BA_EXTENSIONIMAGEN"];
	$imgPortada = "/modules/arteria_noticias/get_image.php?file=".base64_encode($imgPortada);
}

$fecha = '<span style="color:#f00;">Escoja una fecha</span>';
if ($row["BA_FECHA"] != "") {
	$vals = explode("/", $row["BA_FECHA"]);
	$fecha = getDayName(date("N", strtotime($vals[2]."-".$vals[1]."-".$vals[0])))." ".$vals[0]." de ".GetMonthName($vals[1])." de ".$vals[2];
}

$ano = '<span style="color:#f00;">Escoja una año</span>';
if ($row["BA_ANO"] != "")
	$ano = "Año ".decimalToRomana($row["BA_ANO"]);

$numero = '<span style="color:#f00;">Escoja una número</span>';
if ($row["BA_NUMERO"] != "")
	$numero = "Número ".decimalToRomana($row["BA_NUMERO"]);

$idEstadistica = logUrlIn($_SERVER["REQUEST_URI"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>ARTeria Noticias :: Intranet de Provincia ART</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Intranet de Provincia ART" />
		<meta name="Language" content="Spanish" />
		<meta name="Subject" content="Intranet" />
		<link href="<?= $host?>/modules/arteria_noticias/css/style.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="/js/functions.js"></script>
	</head>
	<body onLoad="onLoadBody()">
		<input id="idEstadistica" type="hidden" value="<?= $idEstadistica?>" />
		<table cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td colspan="3"><img src="<?= $host?>/modules/arteria_noticias/images/header.jpg" usemap="#header" /></td>
			</tr>
			<tr>
				<td colspan="3" height="10"></td></tr>
			<tr>
				<td width="375">
					<table cellpadding="0" cellspacing="0" width="100%" height="350">
						<tr>
							<td><p style="margin-left: 10px"><img id="imgPortada" name="imgPortada" src="<?= $host?><?= $imgPortada?>" /></td>
						</tr>
						<tr id="trTitulo1" style="cursor:default;">
							<td align="left" class="TituloPrincipal"><?= getTitle(1)?></td>
						</tr>
						<tr id="trCuerpo1" style="cursor:default;">
							<td class="CuerpoArticulo"><p style="margin-left: 10px"><?= getCopete(1, 296)?></td>
						</tr>
					</table>
				</td>
				<td width="10"></td>
				<td width="375">
					<table cellpadding="0" cellspacing="0" width="360" height="384">
						<tr>
							<td valign="top">
								<div align="left" id="divNoticia2" style="cursor:default; height:120px;">
									<div align="left" class="TituloNoticiasSecundarias" style="background-image: url('<?= $host?>/modules/arteria_noticias/fondo_titulos/fondo_chico_<?= getColor(2)?>.jpg'); background-repeat:no-repeat; color:#<?= getColor(2)?>; height:53px; line-height:56px;"><?= getTitle(2)?></div>
									<p>
										<span class="CuerpoArticulo"><?= getCopete(2, 160)?></span>
									</p>
								</div>
								<div align="left" id="divNoticia3" style="cursor:default; height:120px;">
									<div align="left" class="TituloNoticiasSecundarias" style="background-image: url('<?= $host?>/modules/arteria_noticias/fondo_titulos/fondo_chico_<?= getColor(3)?>.jpg'); background-repeat:no-repeat; color:#<?= getColor(3)?>; height:53px; line-height:56px;"><?= getTitle(3)?></div>
									<p>
										<span class="CuerpoArticulo"><?= getCopete(3, 160)?></span>
									</p>
								</div>
								<div align="left" id="divNoticia4" style="cursor:default; height:120px;">
									<div align="left" class="TituloNoticiasSecundarias" style="background-image: url('<?= $host?>/modules/arteria_noticias/fondo_titulos/fondo_chico_<?= getColor(4)?>.jpg'); background-repeat:no-repeat; color:#<?= getColor(4)?>; height:53px; line-height:56px;"><?= getTitle(4)?></div>
									<p>
										<span class="CuerpoArticulo"><?= getCopete(4, 160)?></span>
									</p>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" height="45">
					<table cellpadding="0" cellspacing="0" width="100%" background="<?= $host?>/modules/arteria_noticias/images/footer.jpg" height="45">
						<tr>
							<td align="left" class="PieFecha">
								<span id="fecha" name="fecha" style="cursor:default;"><?= $fecha?></span> -
								<span id="ano" name="ano" style="cursor:default;"><?= $ano?></span> -
								<span id="numero" name="numero" style="cursor:default;"><?= $numero?></span>
							</td>
							<td align="right" class="PieMenu">
		<?
		for ($i=5; $i<=8; $i++)
			if (isNoticiaVisible($i)) {
		?>
				<span class="FndPieMenu" id="spanNoticia<?= $i?>">&nbsp;<a class="LinkPie" href="<?= $host?>/arteria-noticias/<?= $row["BA_ID"]?>/<?= $i?>"><?= getTitle($i)?></a></span>&nbsp;
		<?
			}
		?>
							</td>
							<td align="right"><a href="/"><img src="<?= $host?>/modules/arteria_noticias/images/logo_art.jpg" title="Volver a la Intranet" /></a></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<map id="header" name="header">
			<area coords="670, 84, 688, 100" href="<?= $host?>/arteria-noticias-ediciones-anteriores" shape="rect" title="Ediciones Anteriores" />
			<area coords="692, 84, 712, 100" href="mailto:<?= $row["BA_EMAILSCONTACTO"]?>?subject=Contacto desde ARTeria Noticias" shape="rect" title="Contáctenos" />
		</map>
	</body>
</html>