<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
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
	"SELECT ba_ano, ba_emailscontacto, ba_fecha, ba_numero, na_altoimagenes, na_anchoimagenes, na_colortitulo, na_id, na_nota, na_numeroplantilla, na_titulo
		 FROM rrhh.rna_noticiasarteria, rrhh.rba_boletinesarteria
		WHERE na_idboletin = ba_id
			AND na_idboletin = :idboletin
			AND na_posicion = :posicion";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$numeroPlantilla = 1;
if ($row["NA_NUMEROPLANTILLA"] != "")
	$numeroPlantilla = $row["NA_NUMEROPLANTILLA"];

$vals = split("/", $row["BA_FECHA"]);
$fecha = GetDayName(date("N", strtotime($vals[2]."-".$vals[1]."-".$vals[0])))." ".$vals[0]." de ".GetMonthName($vals[1])." de ".$vals[2];

$ano = "Año ".decimalToRomana($row["BA_ANO"]);
$numero = "Número ".decimalToRomana($row["BA_NUMERO"]);

$modo = "r";		// Real..
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
		<script language="JavaScript" src="/js/visor_imagenes.js?rnd=<?= date("Ymdhns")?>"></script>
		<style>
			#divVisorImagenes {
				display: none;
			}

			#divVisorImagenesFlechaAnterior {
				cursor: hand;
				left: 0;
				margin-top: 248px;
				position: fixed;
				top: 0;
				z-index: 99;
			}

			#divVisorImagenesFlechas {
				left: 0;
				position: absolute;
				top: 0;
			}

			#divVisorImagenesFlechaSiguiente {
				cursor: hand;
				margin-left: -24px;
				position: fixed;
				z-index: 99;
			}

			#divVisorImagenesFondo {
				background-color: #17a4d9;
				filter: alpha(opacity=60);
				height: 100%;
				left: 0px;
				position: fixed;
				top: 0px;
				width: 100%;
				z-index: 97;
			}

			#divVisorImagenesImagen {
				display: table-cell;
				height: 100%;
				left: 0px;
				position: fixed;
				top: 0px;
				width: 100%;
				z-index: 98;
			}
		</style>
		<script>
// ***  VISOR DE IMÁGENES  -  INICIO..
			function keyDown(event) {
				var keyCode = event.which;
				if (keyCode == undefined)
					keyCode = event.keyCode;

				if (isVisorImagenesVisible()) {
					if (keyCode == 27) {
						cerrarVisor = true;
						cerrarVisorImagenes()
					}

					if ((keyCode == 37) && (isFlechaAnteriorVisible())) {
						cerrarVisor = false;
						document.getElementById('divVisorImagenesFlechaAnterior').click();
						cerrarVisor = true;
					}

					if ((keyCode == 39) && (isFlechaSiguienteVisible())) {
						cerrarVisor = false;
						document.getElementById('divVisorImagenesFlechaSiguiente').click();
						cerrarVisor = true;
					}
				}
			}
// ***  VISOR DE IMÁGENES  -  FIN..
		</script>
	</head>
	<body onKeyDown="keyDown(event)">
		<table cellpadding="0" cellspacing="0" align="center" width="745">
			<tr><td colspan="2"><img border="0" src="/modules/arteria_noticias/images/header.jpg" usemap="#header"></td></tr>
			<tr><td align="left" colspan="2" class="TituloBlanco" height="42" background="/modules/arteria_noticias/fondo_titulos/fondo_grande_<?= $row["NA_COLORTITULO"]?>.jpg"><?= $row["NA_TITULO"]?></td></tr>
			<tr><td colspan="2" height="5"></td></tr>
<? include($_SERVER["DOCUMENT_ROOT"]."/modules/abm_arteria_noticias/plantillas_noticia/plantilla_".$numeroPlantilla.".php");?>
			<tr><td colspan="2" height="10"></td></tr>
			<tr>
				<td colspan="2" height="45">
					<table border="0" cellpadding="0" cellspacing="0" width="100%" background="/modules/arteria_noticias/images/footer.jpg" height="45">
						<tr>
							<td align="left" class="PieFecha"><?= $fecha?> - Año <?= $ano?> - Número <?= $numero?></td>
							<td class="PieMenu"></td>
							<td align="right"><a href="/"><img border="0" src="/modules/arteria_noticias/images/logo_art.jpg"></a></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<map id="header" name="header">
			<area alt="Ediciones Anteriores" coords="670, 84, 688, 100" href="/index.php?pageid=53" shape="rect" />
			<area alt="Contáctenos" coords="692, 84, 712, 100" href="mailto:<?= $row["BA_EMAILSCONTACTO"]?>?subject=Contacto desde ARTeria Noticias" shape="rect" />
		</map>

<!-- ***  VISOR DE IMÁGENES  -  INICIO.. -->
		<div id="divVisorImagenes" onClick="cerrarVisorImagenes()">
			<div id="divVisorImagenesFondo"></div>
			<div id="divVisorImagenesImagen">
				<img id="imgVisorImagenesCargandoImagen" src="/images/loading_grande.gif" style="position:absolute;" />
				<img id="imgVisorImagenesImagen" />
			</div>
			<div id="divVisorImagenesFlechas" style="display:none;">
				<div id="divVisorImagenesFlechaAnterior" onMouseOut="mouseOutFlechas()" onMouseOver="mouseOverFlechas()"><img src="/images/anterior.gif" /></div>
				<div id="divVisorImagenesFlechaSiguiente" onMouseOut="mouseOutFlechas()" onMouseOver="mouseOverFlechas()"><img src="/images/siguiente.gif" /></div>
			</div>
		</div>
<!-- ***  VISOR DE IMÁGENES  -  FIN.. -->
	</body>
</html>