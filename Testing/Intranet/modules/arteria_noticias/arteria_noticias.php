<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function getCopete($num, $max) {
	global $host;
	global $row;

	$params = array(":idboletin" => $row["BA_ID"], ":posicion" => $num);
	$sql =
		"SELECT DBMS_LOB.SUBSTR(na_nota, ".$max.", 1) || '...'
			 FROM rrhh.rna_noticiasarteria
			WHERE na_idboletin = :idboletin
				AND na_posicion = :posicion";
	return strip_tags(htmlspecialchars_decode(ValorSql($sql, "", $params), ENT_QUOTES))."&nbsp;&nbsp;<span class='LeerMas'><a href='http://".$_SERVER["HTTP_HOST"]."/nada'></a><a href='".$host."/modules/arteria_noticias/noticia.php?b=".$row["BA_ID"]."&n=".$num."' target='winArteria'>Leer más &gt;&gt;</a></span>";
}

function getColor($num) {
	global $row;

	$params = array(":idboletin" => $row["BA_ID"], ":posicion" => $num);
	$sql =
		"SELECT na_colortitulo
			 FROM rrhh.rna_noticiasarteria
			WHERE na_idboletin = :idboletin
				AND na_posicion = :posicion";

	return ValorSql($sql, "", $params);
}

function getTitle($num) {
	global $row;

	$params = array(":idboletin" => $row["BA_ID"], ":posicion" => $num);
	$sql =
		"SELECT na_titulo
			 FROM rrhh.rna_noticiasarteria
			WHERE na_idboletin = :idboletin
				AND na_posicion = :posicion";
	$result =	ValorSql($sql, "", $params);

	if ($result == "")
		$result = "<span style='color:#f00;'>Título ".$num."</span>";

	return $result;
}


SetDateFormatOracle("DD/MM/YYYY");

$host = "http://".$_SERVER["HTTP_HOST"];

if (!isset($esEnvio)) {
	$sql =
		"SELECT ba_id
			 FROM rrhh.rba_boletinesarteria
			WHERE ba_estadoenvio = 'E'
	 ORDER BY ba_fecha DESC";
	$_REQUEST["id"] = ValorSql($sql);
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

if ($row["BA_EXTENSIONIMAGEN"] == "")
	$imgPortada = "/modules/abm_arteria_noticias/images/portada.jpg";
else {
	$imgPortada = IMAGES_ARTERIA_PATH."portada\\".$row["BA_ID"].".".$row["BA_EXTENSIONIMAGEN"];
	$imgPortada = "/modules/arteria_noticias/get_image.php?file=".base64_encode($imgPortada);
}

if ($row["BA_FECHA"] != "") {
	$vals = split("/", $row["BA_FECHA"]);
	$fecha = GetDayName(date("N", strtotime($vals[2]."-".$vals[1]."-".$vals[0])))." ".$vals[0]." de ".GetMonthName($vals[1])." de ".$vals[2];
}
else
	$fecha = '<span style="color:#f00;">Escoja una fecha</span>';

if ($row["BA_ANO"] != "")
	$ano = "Año ".decimalToRomana($row["BA_ANO"]);
else
	$ano = '<span style="color:#f00;">Escoja una año</span>';

if ($row["BA_NUMERO"] != "")
	$numero = "Número ".decimalToRomana($row["BA_NUMERO"]);
else
	$numero = '<span style="color:#f00;">Escoja una número</span>';
?>
<link href="<?= $host?>/modules/arteria_noticias/css/style.css" rel="stylesheet" type="text/css" />
<table border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td colspan="3"><img border="0" src="<?= $host?>/modules/arteria_noticias/images/header.jpg" usemap="#header"></td>
	</tr>
	<tr>
		<td colspan="3" height="10"></td></tr>
	<tr>
		<td width="375">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" height="350">
				<tr>
					<td><p style="margin-left: 10px"><img border="0" id="imgPortada" name="imgPortada" src="<?= $host?><?= $imgPortada?>" /></td>
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
			<table border="0" cellpadding="0" cellspacing="0" width="360" height="384">
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
			<table border="0" cellpadding="0" cellspacing="0" width="100%" background="<?= $host?>/modules/arteria_noticias/images/footer.jpg" height="45">
				<tr>
					<td align="left" class="PieFecha">
						<span id="fecha" name="fecha" style="cursor:default;"><?= $fecha?></span> -
						<span id="ano" name="ano" style="cursor:default;"><?= $ano?></span> -
						<span id="numero" name="numero" style="cursor:default;"><?= $numero?></span>
					</td>
					<td align="right" class="PieMenu">
						<span class="FndPieMenu" id="spanNoticia5">&nbsp;<a class="LinkPie" href="<?= $host?>/modules/arteria_noticias/noticia.php?b=<?= $row["BA_ID"]?>&n=5" target='winArteria'><?= getTitle(5)?></a></span>&nbsp;
						<span class="FndPieMenu" id="spanNoticia6">&nbsp;<a class="LinkPie" href="<?= $host?>/modules/arteria_noticias/noticia.php?b=<?= $row["BA_ID"]?>&n=6" target='winArteria'><?= getTitle(6)?></a></span>&nbsp;
						<span class="FndPieMenu" id="spanNoticia7">&nbsp;<a class="LinkPie" href="<?= $host?>/modules/arteria_noticias/noticia.php?b=<?= $row["BA_ID"]?>&n=7" target='winArteria'><?= getTitle(7)?></a></span>&nbsp;
						<span class="FndPieMenu" id="spanNoticia8">&nbsp;<a class="LinkPie" href="<?= $host?>/modules/arteria_noticias/noticia.php?b=<?= $row["BA_ID"]?>&n=8" target='winArteria'><?= getTitle(8)?></a></span>&nbsp;
					</td>
					<td align="right"><img border="0" src="<?= $host?>/modules/arteria_noticias/images/logo_art.jpg"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<map id="header" name="header">
	<area coords="670, 84, 688, 100" href="<?= $host?>/index.php?pageid=53" shape="rect" title="Ediciones Anteriores" />
	<area coords="692, 84, 712, 100" href="mailto:<?= $row["BA_EMAILSCONTACTO"]?>?subject=Contacto desde ARTeria Noticias" shape="rect" title="Contáctenos" />
</map>