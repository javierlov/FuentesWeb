<?
$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT TO_CHAR(se_fechacumple, 'dd/mm') cumple,
					art.utiles.get_anios(se_fechacumple, SYSDATE) edad,
					es_descripcion || ' - ' || es_calle || ' ' || es_numero edificio,
					cse3.se_descripcion gerencia,
					useu.se_fechabaja,
					useu.se_fechacumple,
					useu.se_foto,
					useu.se_horarioatencion,
					useu.se_interno,
					useu.se_mail,
					useu.se_nombre,
					useu.se_piso,
					cse.se_descripcion sector,
					se_sector
  	 FROM use_usuarios useu, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3, art.des_delegacionsede
	  WHERE useu.se_idsector = cse.se_id(+)
  	  AND cse.se_idsectorpadre = cse2.se_id(+)
      AND cse2.se_idsectorpadre = cse3.se_id(+)
			AND se_iddelegacionsede = es_id(+)
      AND useu.se_id = :id
 ORDER BY useu.se_nombre";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

if ($row["SE_FECHABAJA"] != "") {
	showErrorIntranet("", "Modo de acceso incorrecto.");
	return;
}

$jsImagen = "";
$inicioLinkFoto = "";
$finLinkFoto = "";
$rutaFoto = base64_encode(IMAGES_FOTOS_PATH."cartel.jpg");
$foto = $row["SE_FOTO"];

if (is_file(IMAGES_FOTOS_PATH.$foto)) {
	$rutaFoto = base64_encode(IMAGES_FOTOS_PATH.$foto);
	$inicioLinkFoto = "<a href=\"#\" onClick=\"mostrarImagen(0);\">";
	$finLinkFoto = "</a>"; 
	$jsImagen = "arrVisorImagenes = new Array('".$rutaFoto."');";
}
?>
<link href="/modules/usuarios/css/usuarios.css" rel="stylesheet" type="text/css" />
<script src="/modules/usuarios/js/usuarios.js" type="text/javascript"></script>
<div id="divDatos">
	<div><span id="spanNombre"><?= $row["SE_NOMBRE"] ?></span></div>
	<div id="divDatosFila2">
		<div id="divFotoUsuario">
			<?= $inicioLinkFoto?><img src="<?= "/functions/get_image.php?mh=117&mw=115&file=".$rutaFoto ?>" /><?= $finLinkFoto?>
		</div>
		<div id="divDatosUsuario">
			<div id="divInterno">
				<form action="/modules/usuarios/guardar_interno.php" id="formInterno" method="post" name="formInterno" target="iframeGeneral">
					<span>INTERNO:</span>
					<span id="<?= (getUserID() == $_REQUEST["id"])?"spanInternoPropio":"spanInternoOtro"?>" title="Clic aquí para modificar su interno" onClick="modificarCampoEditable('interno', <?= (getUserID() == $_REQUEST["id"])?"true":"false"?>)"><?= $row["SE_INTERNO"]?></span>
					<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
					<input id="imgProcesando" name="imgProcesando" type="hidden" value="" />
					<input id="interno" maxlength="50" name="interno" title="Ingrese aquí su número de interno" type="text" value="" onBlur="guardarCampoEditable('interno')" onKeyPress="teclaPresionadaCampoEditable('interno')" />
				</form>
			</div>
			<div>
				<span>e-Mail:</span>
				<a href="mailto:<?= $row["SE_MAIL"]?>"><?= $row["SE_MAIL"] ?></a>
			</div>
			<div>
				<span>Edificio:</span>
				<span><?= $row["EDIFICIO"] ?></span>
			</div>
<?
if ($row["SE_PISO"] != "") {
?>
	<div>
		<span>Piso:</span>
		<span><?= $row["SE_PISO"] ?></span>
	</div>
<?
}
?>
			<div>
				<span>Gerencia:</span>
				<a href="/contactos/s/<?= setUrlAmigable($row["GERENCIA"]) ?>"><?= $row["GERENCIA"] ?></a>
			</div>
			<div>
				<span>Sector:</span>
				<a href="/contactos/s/<?= setUrlAmigable($row["SECTOR"]) ?>"><?= $row["SECTOR"] ?></a>
			</div>
<?
if ($row["SE_HORARIOATENCION"] != "") {
?>
	<div>
		<span>Horario de Atención:</span>
		<span><?= $row["SE_HORARIOATENCION"] ?></span>
	</div>
<?
}
?>
			<div id="divCampoEditable">
				<form action="/modules/usuarios/guardar_cumpleanos.php" id="formCumpleaños" method="post" name="formCumpleaños" target="iframeGeneral">
					<span>Cumpleaños:</span>
					<span id="<?= (getUserID() == $_REQUEST["id"])?"spanCumpleañosPropio":"spanCumpleañosOtro"?>" title="Clic aquí para modificar su cumpleaños" onClick="modificarCampoEditable('cumpleaños', <?= (getUserID() == $_REQUEST["id"])?"true":"false"?>)"><?= $row["CUMPLE"]?></span>
					<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
					<input id="imgProcesando" name="imgProcesando" type="hidden" value="" />
					<input id="cumpleaños" maxlength="5" name="cumpleaños" title="Ingrese aquí su cumpleaños" type="text" value="" onBlur="guardarCampoEditable('cumpleaños')" onKeyPress="teclaPresionadaCampoEditable('cumpleaños')" />
<?
if ((getUserSector() == "COMPUTOS") and ($row["SE_FECHACUMPLE"] != "")) {
?>
					<span>(<?= $row["EDAD"]?> años)</span>
<?
}
?>
				</form>
			</div>
		</div>
		<div id="divNada"></div>
	</div>
</div>
<?
if (getUserID() == $_REQUEST["id"]) {
?>
	<div>* Puede modificar su interno y cumpleaños haciendo clic sobre ellos.</div>
<?
}
?>
<div><a href="/contactos"><input class="btnVolver" type="button" value="" /></a></div>
<script>
	if (document.getElementById('spanCumpleañosOtro') != null)
		document.getElementById('spanCumpleañosOtro').title = '';

	if (document.getElementById('spanInternoOtro') != null)
		document.getElementById('spanInternoOtro').title = '';

<?= $jsImagen?>
</script>