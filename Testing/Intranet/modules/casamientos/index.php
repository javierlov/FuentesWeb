<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


function getImage($id) {
	$img = "";
	if (file_exists(DATA_CELEBRACIONES_PATH.$id.".gif"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$id.".gif");
	elseif (file_exists(DATA_CELEBRACIONES_PATH.$id.".jpeg"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$id.".jpeg");
	elseif (file_exists(DATA_CELEBRACIONES_PATH.$id.".jpg"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$id.".jpg");
	elseif (file_exists(DATA_CELEBRACIONES_PATH.$id.".png"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$id.".png");

	if ($img == "")
		$img = base64_encode(DATA_CELEBRACIONES_PATH."default_image_casamientos.jpg");

	return $img;
}

function getLink($num) {
	global $pagina;

	if ($pagina == $num)
		echo '<span style="color:#00539B; cursor:default; margin-left:4px;">'.$num.'</span>';
	else
		echo '<a href="/index.php?pageid=19&pagina='.$num.'" style="color:#ffffff; margin-left:4px; text-decoration:none;">'.$num.'</a>';
}


$jsImagen = "arrVisorImagenes = new Array(";

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$params = array(":desde" => (($pagina * 2) - 1), ":hasta" => ($pagina * 2));
$sql =
	"SELECT np_id, np_texto, np_titulo, numrec
		 FROM (SELECT np_id, np_texto, np_titulo, ROWNUM numrec
						 FROM (SELECT np_id, np_texto, NVL(np_titulo, '&nbsp;') np_titulo
										 FROM rrhh.rnp_novedadespersonales
										WHERE np_tiponovedad = 'C'
											AND np_fechabaja IS NULL
								 ORDER BY np_fechaalta DESC))
		WHERE numrec BETWEEN :desde AND :hasta";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$jsImagen.= "'".getImage($row["NP_ID"])."'";
?>
<script>
	showTitle(true, 'CASAMIENTOS');
</script>
<iframe id="iframeComentario" name="iframeComentario" src="" style="display:none;"></iframe>
<div style="margin-left:25px; margin-top:24px;">
	<div style="float:left; width:520px;">
		<div align="left" class="FormLabelBlancoGrande" style="background-color:#00539B; padding-left:4px;"><?= htmlspecialchars_decode($row["NP_TITULO"], ENT_QUOTES)?></div>
		<div align="left" class="FormLabelNegroSinNegrita10" style="padding-bottom:10px; padding-top:5px;"><?= htmlspecialchars_decode($row["NP_TEXTO"], ENT_QUOTES)?></div>
		<form action="/functions/save_comment.php" id="formComentario1" method="post" name="formComentario1" target="iframeComentario" onSubmit="return ValidarForm(formComentario1)">
			<input id="idarticulo" name="idarticulo" type="hidden" value="<?= $row["NP_ID"]?>" />
			<input id="idmodulo" name="idmodulo" type="hidden" value="19" />
			<div><textarea class="FormTextArea" id="comentario" name="comentario" style="height:40px; width:100%;" title="Comentario" validar="true"></textarea></div>
			<div align="right" style="margin-top:16px;">
				<input class="BotonBlanco" type="button" value="VER COMENTARIOS" onClick="verComentarios(19, <?= $row["NP_ID"]?>)">
				<input class="BotonBlanco" style="margin-left:16px;" type="submit" value="COMENTAR" />
			</div>
		</form>
	</div>
	<div align="center" style="float:left;">
		<img border="0" src="/functions/get_image.php?file=<?= getImage($row["NP_ID"])?>&mh=176&mw=204" style="cursor:hand; margin-left:16px;" onClick="mostrarImagen(0)" />
	</div>

	<div style="clear:left;">
		<br />
		<hr color="#807F84" style="width:740px;">
	</div>
<?
$row = DBGetQuery($stmt);
$jsImagen.= ", '".getImage($row["NP_ID"])."');";
?>
	<div style="float:left; margin-top:16px; width:520px;">
		<div align="left" class="FormLabelBlancoGrande" style="background-color:#00539B; padding-left:4px;"><?= htmlspecialchars_decode($row["NP_TITULO"], ENT_QUOTES)?></div>
		<div align="left" class="FormLabelNegroSinNegrita10" style="padding-bottom:10px; padding-top:5px;"><?= htmlspecialchars_decode($row["NP_TEXTO"], ENT_QUOTES)?></div>
		<form action="/functions/save_comment.php" id="formComentario2" method="post" name="formComentario2" target="iframeComentario" onSubmit="return ValidarForm(formComentario2)">
			<input id="idarticulo" name="idarticulo" type="hidden" value="<?= $row["NP_ID"]?>" />
			<input id="idmodulo" name="idmodulo" type="hidden" value="19" />
			<div><textarea class="FormTextArea" id="comentario" name="comentario" style="height:40px; width:100%;" title="Comentario" validar="true"></textarea></div>
			<div align="right" style="margin-top:16px;">
				<input class="BotonBlanco" type="button" value="VER COMENTARIOS" onClick="verComentarios(19, <?= $row["NP_ID"]?>)">
				<input class="BotonBlanco" style="margin-left:16px;" type="submit" value="COMENTAR" />
			</div>
		</form>
	</div>
	<div align="center" style="float:left; margin-top:16px;">
		<img border="0" src="/functions/get_image.php?file=<?= getImage($row["NP_ID"])?>&mh=176&mw=204" style="cursor:hand; margin-left:16px;" onClick="mostrarImagen(1)" />
	</div>
	<div style="clear:left; height:20px;"><!-- DIV puesto para que los botones se vean bien.. --></div>
	<div style="background-color:#b8b8b8; bottom:32px; clear:left; color:#ffffff; font-weight:bold; height:20px; position:absolute; text-align:center; width:740px;">
<?
getLink(1);
getLink(2);
getLink(3);
getLink(4);
getLink(5);
?>
	</div>
	<div id="datoGuardadoOk" style="background-color:#6fc45b; border: 1px #000 solid; color:#fff; display:none; font-size:12pt; font-weight:bold; left:40%; padding:4px; position:absolute; top:50%;">El comentario fue agregado exitosamente.</div>
</div>
<script>
<?= $jsImagen?>
</script>