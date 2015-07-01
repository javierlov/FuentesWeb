<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function getCopete($num, $max) {
	$params = array(":idboletin" => $_REQUEST["id"], ":posicion" => $num);
	$sql =
		"SELECT DBMS_LOB.SUBSTR(na_nota, ".$max.", 1) || '...'
			 FROM rrhh.rna_noticiasarteria
			WHERE na_idboletin = :idboletin
				AND na_posicion = :posicion";

	return strip_tags(htmlspecialchars_decode(ValorSql($sql, "", $params), ENT_QUOTES))."&nbsp;<span class='LeerMas'>Leer más &gt;&gt;</span>";
}

function getColor($num) {
	$params = array(":idboletin" => $_REQUEST["id"], ":posicion" => $num);
	$sql =
		"SELECT na_colortitulo
			 FROM rrhh.rna_noticiasarteria
			WHERE na_idboletin = :idboletin
				AND na_posicion = :posicion";

	return ValorSql($sql, "", $params);
}

function getTitle($num) {
	$params = array(":idboletin" => $_REQUEST["id"], ":posicion" => $num);
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


if (!isset($_REQUEST["id"])) {
	$params = array(":usualta" => GetWindowsLoginName(true));
	$sql = "INSERT INTO rrhh.rba_boletinesarteria (ba_fechaalta, ba_usualta) VALUES (SYSDATE, :usualta)";
	DBExecSql($conn, $sql, $params);
	$id = ValorSql("SELECT MAX(ba_id) FROM rrhh.rba_boletinesarteria");
	echo "<script>window.location.href = window.location.href + '&id=".$id."'</script>";
	exit;
}

$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ba_ano, ba_extensionimagen, ba_fecha, ba_numero, TO_CHAR(ba_fechaenvio, 'dd/mm/yyyy hh24:mi') fechaenvio
		 FROM rrhh.rba_boletinesarteria
		WHERE ba_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

if ($row["BA_EXTENSIONIMAGEN"] == "")
	$imgPortada = "/modules/abm_arteria_noticias/images/portada.jpg";
else {
	$imgPortada = IMAGES_ARTERIA_PATH."portada\\".$_REQUEST["id"].".".$row["BA_EXTENSIONIMAGEN"];
	$imgPortada = "/functions/get_image.php?file=".base64_encode($imgPortada);
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
	$ano = '<span style="color:#f00;">Escoja un año</span>';

if ($row["BA_NUMERO"] != "")
	$numero = "Número ".decimalToRomana($row["BA_NUMERO"]);
else
	$numero = '<span style="color:#f00;">Escoja un número</span>';
?>
<link href="/modules/arteria_noticias/css/style.css" rel="stylesheet" type="text/css" />
<link href="/js/popup/dhtmlwindow.css" rel="stylesheet" type="text/css" />
<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
<iframe id="iframeBoletin" name="iframeBoletin" src="" style="display:none;"></iframe>
<div id="divMostrarPanelAbm" style="display:none;">
	<div align="right" style="margin-right:4px;">
		<img border="0" src="/modules/abm_arteria_noticias/images/mostrar.png" style="cursor:hand; margin-top:4px;" title="Click aquí para mostrar el panel de edición" onClick="mostrarPanelAbm()" />
	</div>
</div>
<div align="left" id="divPanelAbm" style="background-color:#eee;">
	<form action="/modules/abm_arteria_noticias/enviar_boletin.php" id="formEnviarBoletin" method="post" name="formEnviarBoletin" target="iframeBoletin">
		<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>">
		<p style="margin-left:36px;">
			<label class="FormLabelAzul" for="destinatarios" style="margin-right:4px;">Destinatarios</label>
			<input class="FormInputText" id="destinatarios" name="destinatarios" style="width:560px;" title="Destinatarios" type="text" validar="true" value="ProvinciaA.R.T.-DelegacionesdelInterior@provart.com.ar;ProvinciaA.R.T.-CasaCentral@provart.com.ar">
			<img border="0" src="/modules/abm_arteria_noticias/images/ocultar.png" style="cursor:hand; margin-left:46px; margin-top:4px;" title="Click aquí para ocultar el panel de edición" onClick="ocultarPanelAbm()" />
		</p>
		<p style="margin-left:4px; margin-top:4px;">
			<label class="FormLabelAzul" for="fechaUltimoEnvio" style="margin-right:4px;">Fecha último envío</label>
			<input class="FormInputText" id="fechaUltimoEnvio" maxlength="10" name="fechaUltimoEnvio" readonly size="18" style="cursor:default;" type="text" value="<?= $row["FECHAENVIO"]?>">
			<span id="spanEnviando" style="background-color:#77DAFF; cursor:default; margin-left:32px; visibility:hidden;">&nbsp;Enviando, aguarde un instante por favor...&nbsp;</span>
		</p>
		<div align="center">
			<hr color="#C0C0C0" width="98%" size="1" style="border-bottom-style:dotted; border-bottom-width: 1px; border-left-width:1px; border-right-width:1px; border-top-width:1px;">
		</div>
		<p style="height:28px; margin-left:59px; margin-top:4px;">
			<input class="BotonBlanco" id="btnGuardar" name="btnGuardar" type="button" value="Enviar" onClick="enviarBoletin()">
			<span style="margin-left:70px;">> <a href="javascript:ordenar(<?= $_REQUEST["id"]?>)">ORDENAR NOTICIAS</a></span>
			<input class="BotonBlanco" name="btnVolver" style="margin-left:167px;" type="button" value="Volver a la búsqueda" onClick="window.location.href = 'index.php?pageid=64'">
		</p>
	</form>
</div>
<form action="/modules/abm_arteria_noticias/procesar_boletin.php" enctype="multipart/form-data" id="formBoletin" method="post" name="formBoletin" target="iframeBoletin" onSubmit="return ValidarForm(formBoletin)">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>">
	<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="20000000">
	<input id="tipoOp" name="tipoOp" type="hidden" value="<?= "M"?>">
	<table border="0" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td colspan="3"><img border="0" src="/modules/arteria_noticias/images/header.jpg" usemap="#header"></td>
		</tr>
		<tr>
			<td colspan="3" height="10"></td></tr>
		<tr>
			<td width="375">
				<table border="0" cellpadding="0" cellspacing="0" width="100%" height="350">
					<tr>
						<td><p style="margin-left: 10px"><a href="/functions/edit_image.php?finalFunction=setImagenPortada&minWidth=360&maxWidth=360&minHeight=235&maxHeight=235" target="_blank"><img alt="Click aquí para cambiar la imagen" border="0" id="imgPortada" name="imgPortada" src="<?= $imgPortada?>" onMouseOut="this.border = 0;" onMouseOver="this.border = 3;" /></a></td>
					</tr>
					<tr id="trTitulo1" style="cursor:hand;" title="Click aquí para modificar la Noticia 1" onClick="editarNoticia(1)" onMouseOut="mouseOutNotica(1)" onMouseOver="mouseOverNotica(1)">
						<td align="left" class="TituloPrincipal"><?= getTitle(1)?></td>
					</tr>
					<tr id="trCuerpo1" style="cursor:hand;" title="Click aquí para modificar la Noticia 1" onClick="editarNoticia(1)" onMouseOut="mouseOutNotica(1)" onMouseOver="mouseOverNotica(1)">
						<td class="CuerpoArticulo"><p style="margin-left: 10px"><?= getCopete(1, 296)?></td>
					</tr>
				</table>
			</td>
			<td width="10"></td>
			<td width="375">
				<table border="0" cellpadding="0" cellspacing="0" width="360" height="384">
					<tr>
						<td valign="top">
							<div align="left" id="divNoticia2" style="cursor:hand; height:120px;" title="Click aquí para modificar la Noticia 2" onClick="editarNoticia(2)" onMouseOut="mouseOutNotica(2)" onMouseOver="mouseOverNotica(2)">
								<div align="left" class="TituloNoticiasSecundarias" style="background-image: url('/modules/arteria_noticias/fondo_titulos/fondo_chico_<?= getColor(2)?>.jpg'); background-repeat:no-repeat; color:#<?= getColor(2)?>; height:53px; line-height:56px;"><?= getTitle(2)?></div>
								<p>
									<span class="CuerpoArticulo"><?= getCopete(2, 160)?></span>
								</p>
							</div>
							<div align="left" id="divNoticia3" style="cursor:hand; height:120px;" title="Click aquí para modificar la Noticia 3" onClick="editarNoticia(3)" onMouseOut="mouseOutNotica(3)" onMouseOver="mouseOverNotica(3)">
								<div align="left" class="TituloNoticiasSecundarias" style="background-image: url('/modules/arteria_noticias/fondo_titulos/fondo_chico_<?= getColor(3)?>.jpg'); background-repeat:no-repeat; color:#<?= getColor(3)?>; height:53px; line-height:56px;"><?= getTitle(3)?></div>
								<p>
									<span class="CuerpoArticulo"><?= getCopete(3, 160)?></span>
								</p>
							</div>
							<div align="left" id="divNoticia4" style="cursor:hand; height:120px;" title="Click aquí para modificar la Noticia 4" onClick="editarNoticia(4)" onMouseOut="mouseOutNotica(4)" onMouseOver="mouseOverNotica(4)">
								<div align="left" class="TituloNoticiasSecundarias" style="background-image: url('/modules/arteria_noticias/fondo_titulos/fondo_chico_<?= getColor(4)?>.jpg'); background-repeat:no-repeat; color:#<?= getColor(4)?>; height:53px; line-height:56px;"><?= getTitle(4)?></div>
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
				<table border="0" cellpadding="0" cellspacing="0" width="100%" background="/modules/arteria_noticias/images/footer.jpg" height="45">
					<tr>
						<td align="left" class="PieFecha">
							<span id="fecha" name="fecha" style="cursor:hand;" title="Click aquí para modificar la fecha" onClick="showTmpWin(<?= $_REQUEST["id"]?>, 'fecha', 'Indique la Fecha del Boletín')" onMouseOut="this.style.backgroundColor = '';" onMouseOver="this.style.backgroundColor = '#77daff';"><?= $fecha?></span> -
							<span id="ano" name="ano" style="cursor:hand;" title="Click aquí para modificar el año" onClick="showTmpWin(<?= $_REQUEST["id"]?>, 'ano', 'Indique el Año (en decimales)')" onMouseOut="this.style.backgroundColor = '';" onMouseOver="this.style.backgroundColor = '#77daff';"><?= $ano?></span> -
							<span id="numero" name="numero" style="cursor:hand;" title="Click aquí para modificar el número" onClick="showTmpWin(<?= $_REQUEST["id"]?>, 'numero', 'Indique el Número (en decimales)')" onMouseOut="this.style.backgroundColor = '';" onMouseOver="this.style.backgroundColor = '#77daff';"><?= $numero?></span>
						</td>
						<td align="right" class="PieMenu">
							<span class="FndPieMenu" id="spanNoticia5" title="Click aquí para modificar la Noticia 5" onMouseOut="mouseOutNotica(5)" onMouseOver="mouseOverNotica(5)">&nbsp;<a class="LinkPie" href="javascript:editarNoticia(5)"><?= getTitle(5)?></a></span>&nbsp;
							<span class="FndPieMenu" id="spanNoticia6" title="Click aquí para modificar la Noticia 6" onMouseOut="mouseOutNotica(6)" onMouseOver="mouseOverNotica(6)">&nbsp;<a class="LinkPie" href="javascript:editarNoticia(6)"><?= getTitle(6)?></a></span>&nbsp;
							<span class="FndPieMenu" id="spanNoticia7" title="Click aquí para modificar la Noticia 7" onMouseOut="mouseOutNotica(7)" onMouseOver="mouseOverNotica(7)">&nbsp;<a class="LinkPie" href="javascript:editarNoticia(7)"><?= getTitle(7)?></a></span>&nbsp;
							<span class="FndPieMenu" id="spanNoticia8" title="Click aquí para modificar la Noticia 8" onMouseOut="mouseOutNotica(8)" onMouseOver="mouseOverNotica(8)">&nbsp;<a class="LinkPie" href="javascript:editarNoticia(8)"><?= getTitle(8)?></a></span>&nbsp;
						</td>
						<td align="right"><img border="0" src="/modules/arteria_noticias/images/logo_art.jpg"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<map id="header" name="header">
	<area alt="Click aquí para editar el e-mail de contacto" coords="692, 84, 712, 100" href="#" shape="rect" onClick="showTmpWin(<?= $_REQUEST["id"]?>, 'emailsContacto', 'Indique el e-mail del contacto')" />
</map>
<script>
if (document.getElementById('btnFechaEnvio') != null)
	Calendar.setup (
		{
			inputField: "fechaEnvio",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaEnvio"
		}
	);
</script>