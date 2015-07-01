<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");
 

function GetLink($folder) {
	$dir = DATA_PORTADA_PATH.$folder;
	$files = array();

	// Obtengo los archivos que hay en la carpeta..
	if (is_dir($dir))
		if ($gd = opendir($dir)) {
			while (($file = readdir($gd)) !== false)
				if (($file != ".") and ($file != ".."))
					array_push($files, $file);
			closedir($gd);
		}

	if (count($files) == 1)		// Si hay uno solo pongo un link hacia ese archivo..
		return DATA_PORTADA_RELATIVE_PATH.$folder."/".$files[0];
	elseif (in_array("index.html", $files))
		return DATA_PORTADA_RELATIVE_PATH.$folder."/index.html"; 
	elseif (in_array("index.htm", $files))
		return DATA_PORTADA_RELATIVE_PATH.$folder."/index.htm";
	elseif (in_array("index.php", $files))
		return DATA_PORTADA_RELATIVE_PATH.$folder."/index.php";
	elseif (in_array($folder."html", $files))
		return DATA_PORTADA_RELATIVE_PATH.$folder."/".$folder."html";
	elseif (in_array($folder."htm", $files))
		return DATA_PORTADA_RELATIVE_PATH.$folder."/".$folder."htm";
	elseif (in_array($folder."php", $files))
		return DATA_PORTADA_RELATIVE_PATH.$folder."/".$folder."php";
	else
		return DATA_PORTADA_RELATIVE_PATH.$folder."/".$files[0];
}


if (isset($_REQUEST["prv"]))
	$sql = 
		"SELECT ai_cuerpo cuerpo, ai_destino destino, ai_link link, ai_rutaimagen rutaimagen, ai_titulo titulo,
						ai_volanta volanta
  		 FROM tmp.tai_articulosintranet
 	 ORDER BY ai_posicion";
else
	$sql = 
		"SELECT ai_cuerpo cuerpo, ai_destino destino, ai_link link, ai_rutaimagen rutaimagen, ai_titulo titulo,
						ai_volanta volanta
  		 FROM web.wai_articulosintranet
	 		WHERE ai_fechabaja IS NULL
 	 ORDER BY ai_posicion";
$stmt = DBExecSql($conn, $sql);

$sql = "SELECT TO_CHAR(art.actualdate, 'DD/MM') FROM DUAL";
$dia = ValorSql($sql);
?>
<script>
	function cambiarDia(a) {
		with (document) {
			getElementById('tableSociales').style.display = 'none';
			getElementById('divProcesandoDia').style.display = 'block';
			getElementById('iframeCambiarDia').src = '/modules/portada/cambiar_dia_cumple.php?a=' + a + '&d=' + dia;
		}
	}

	function cargarImagen(pos) {
		document.getElementById('imgFotoPersonal').src = listaImagenes[pos].src;
		document.getElementById('divFotoPersonal').style.display = 'block';
	}

	function cerrarDiv() {
		if (cerrar)
			document.getElementById('divMenuSociales').style.display = 'none';
	}

	function mostrarMenuSociales() {
		document.getElementById('divMenuSociales').style.display = 'block';
	}

	function moverImagen() {
		with (document.getElementById('divFotoPersonal').style) {
			left = _x + 16;
			top = _y;
		}
	}

	function ocultarImagen() {
		document.getElementById('divFotoPersonal').style.display = 'none';
	}

	function ocultarMenuSociales() {
		setTimeout('cerrarDiv()', 500);
	}


	var cerrar = false;
	var dia = '<?= $dia?>';
	var listaImagenes = new Array();

	if (<?= (isset($_REQUEST["prv"]))?"false":"true"?>) {		// Si no existe el parámetro prv en esta página..
		if (window.parent.location.href.indexOf('?prv=true') > -1)		// Si existe el parámetro prv en el parent..
			window.location.href = '/index.php?prv=true';
	}
	else {
		document.getElementById('imagen2HomePage').src = '/images/vista_previa.jpg';
	}
</script>
<iframe id="iframeCambiarDia" name="iframeCambiarDia" src="" style="display:none;"></iframe>
<input id="fechaCumple" name="fechaCumple" type="hidden" value="" onChange="dia = document.getElementById('fechaCumple').value.substr(0, 5); cambiarDia('d');" />
<table border="0" bordercolor="#fff" bordercolordark="#fff" bordercolorlight="#fff" cellpadding="0" cellspacing="0" width="770">
  <tr>
  	<td height="58">
  		<table border="0" cellpadding="0" cellspacing="0" width="100%">
  			<tr>
  				<td valign="top" width="450">
  					<table border="0" cellpadding="0" cellspacing="0" width="100%">
  						<tr>
  							<td width="4"></td>
<?
$row = DBGetQuery($stmt);		// Sector 1..
if ($row["RUTAIMAGEN"] != "") {
?>
	<td style="width: 57px"><img border="0" src="<?= IMAGES_ARTICULOS_RELATIVE_PATH.$row["RUTAIMAGEN"] ?>"></td>
	<td width="8"></td>
<?
}
?>
								<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td class="VolantaArticulo"><?= $row["VOLANTA"] ?></td>
										</tr>
										<tr>
											<td><a class="TituloArticulo" href="<?= GetLink($row["LINK"]) ?>" target="<?= $row["DESTINO"]?>"><?= $row["TITULO"] ?></td>
										</tr>
										<tr>
											<td class="CuerpoArticulo"><?= $row["CUERPO"] ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td width="4"></td>
					<td class="lineaVertical"></td>
					<td width="4"></td>
  				<td valign="top">
<?
$row = DBGetQuery($stmt);		// Sector 2..
?>
  					<table border="0" cellpadding="0" cellspacing="0" width="100%">
  						<tr>
								<td width="300px" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td class="VolantaArticulo"><?= $row["VOLANTA"] ?></td>
										</tr>
										<tr>
											<td><a class="TituloArticulo" href="<?= GetLink($row["LINK"]) ?>" target="<?= $row["DESTINO"]?>"><?= $row["TITULO"] ?></td>
										</tr>
										<tr>
											<td class="CuerpoArticulo"><?= $row["CUERPO"] ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="height:8px;"></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #808080; height:8px;">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
  				<td height="58" valign="top" width="336">
  					<table border="0" cellpadding="0" cellspacing="0" width="100%">
  						<tr>
  							<td width="4"></td>
<?
$row = DBGetQuery($stmt);		// Sector 3..
if ($row["RUTAIMAGEN"] != "") {
?>
	<td><img border="0" src="<?= IMAGES_ARTICULOS_RELATIVE_PATH.$row["RUTAIMAGEN"] ?>"></td>
	<td width="8"></td>
<?
}
?>
								<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td class="VolantaArticulo"><?= $row["VOLANTA"] ?></td>
										</tr>
										<tr>
											<td><a class="TituloArticulo" href="<?= GetLink($row["LINK"]) ?>" target="<?= $row["DESTINO"]?>"><?= $row["TITULO"] ?></td>
										</tr>
										<tr>
											<td class="CuerpoArticulo"><?= $row["CUERPO"] ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td width="4"></td>
					<td class="lineaVertical"></td>
					<td width="4"></td>
  				<td valign="top">
<?
$row = DBGetQuery($stmt);		// Sector 4..
?>
  					<table border="0" cellpadding="0" cellspacing="0" width="100%">
  						<tr>
								<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td class="VolantaArticulo"><?= $row["VOLANTA"] ?></td>
										</tr>
										<tr>
											<td><a class="TituloArticulo" href="<?= GetLink($row["LINK"]) ?>" target="<?= $row["DESTINO"]?>"><?= $row["TITULO"] ?></td>
										</tr>
										<tr>
											<td class="CuerpoArticulo"><?= $row["CUERPO"] ?></td>
										</tr>
									</table>
								</td>
<?
if ($row["RUTAIMAGEN"] != "") {
?>
	<td width="8"></td>
	<td><p align="center"><img border="0" src="<?= IMAGES_ARTICULOS_RELATIVE_PATH.$row["RUTAIMAGEN"] ?>"></td>
<?
}
?>
								<td width="4"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="height:8px;"></td>
	</tr>
	<tr>
		<td style="border-top: 1px solid #808080; height:8px;">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
  				<td height="58" valign="top" width="232">
  					<table bgcolor="#e7e7e7" border="0" cellpadding="0" cellspacing="0" id="tableSociales" width="100%">
  						<tr>
  							<td width="4"></td>
								<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td class="VolantaArticulo" style="cursor:default;">
												<div style="width:80px;" onMouseOut="ocultarMenuSociales()" onMouseOver="mostrarMenuSociales()">Sociales</div>
											</td>
										</tr>
										<tr>
											<td><a class="TituloArticulo" href="/index.php?pageid=29">CUMPLEAÑOS</a></td>
										</tr>
										<tr>
											<td height="56" valign="top">
												<div id="tableCumples">
<?
$sql =
	"SELECT se_id, se_nombre
     FROM art.use_usuarios
   	WHERE TO_CHAR(se_fechacumple, 'DD/MM') = TO_CHAR(art.actualdate, 'DD/MM')
     	AND se_fechabaja IS NULL
 ORDER BY se_nombre";
$stmt2 = DBExecSql($conn, $sql);
$count = 0;
while ($row2 = DBGetQuery($stmt2)) {
	$count++;
?>
													<div class="CuerpoArticulo" style="margin-bottom:4px;">
														<span class="LineaGris">
															<a class="CuerpoArticulo" href="/index.php?pageid=56&id=<?= $row2["SE_ID"]?>" style="text-decoration:none;" onMouseMove="moverImagen()" onMouseOut="ocultarImagen()" onMouseOver="cargarImagen('<?= $count?>')"><?= $row2["SE_NOMBRE"] ?></a>
														</span>
													</div>
<?
}
if ($count == 0) {
?>
													<div>
														<span class="CuerpoArticulo" style="text-align:left;"><br><b>NO HAY CUMPLEAÑOS</b></span>
													</div>
<?
}
?>
												</div>
											</td>
										</tr>
									</table>
								</td>
								<td align="right" valign="top">
									<br />
									<img border="0" id="imgDia" src="/modules/portada/imagen_dia.php" style="cursor:hand;" title="Cambiar fecha" />
									<br />
									<img src="/images/flecha_izquierda.png" style="cursor:hand; height:22px; width:22px;" title="Ir al día anterior" onClick="cambiarDia('a');" />
									<img src="/images/flecha_derecha.png" style="cursor:hand; height:22px; width:22px;" title="Ir al día siguiente" onClick="cambiarDia('p');" />
								</td>
							</tr>
						</table>
						<div align="center" id="divProcesandoDia" style="cursor:wait; display:none; margin-top:24px; width:100%;">Procesando...</div>
					</td>
					<td width="4"></td>
					<td class="lineaVertical"></td>
					<td width="4"></td>
  				<td valign="top" width="224">
<?
$row = DBGetQuery($stmt);		// Sector 5..
?>
  					<table border="0" cellpadding="0" cellspacing="0" width="100%">
  						<tr>
								<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td class="VolantaArticulo"><?= $row["VOLANTA"] ?></td>
										</tr>
										<tr>
											<td><a class="TituloArticulo" href="<?= GetLink($row["LINK"]) ?>" target="<?= $row["DESTINO"]?>"><?= $row["TITULO"] ?></td>
										</tr>
										<tr>
											<td class="CuerpoArticulo"><?= $row["CUERPO"] ?></td>
										</tr>
									</table>
								</td>
<?
if ($row["RUTAIMAGEN"] != "") {
?>
	<td width="8"></td>
	<td><img border="0" src="<?= IMAGES_ARTICULOS_RELATIVE_PATH.$row["RUTAIMAGEN"] ?>"></td>
<?
}
?>
							</tr>
						</table>
					</td>
					<td width="4"></td>
					<td class="lineaVertical"></td>
					<td width="4"></td>
  				<td valign="top">
<?
$row = DBGetQuery($stmt);		// Sector 6..
?>
  					<table border="0" cellpadding="0" cellspacing="0" width="100%">
  						<tr>
								<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td class="VolantaArticulo"><?= $row["VOLANTA"] ?></td>
										</tr>
										<tr>
											<td><a class="TituloArticulo" href="<?= GetLink($row["LINK"]) ?>" target="<?= $row["DESTINO"]?>"><?= $row["TITULO"] ?></td>
										</tr>
										<tr>
											<td class="CuerpoArticulo"><?= $row["CUERPO"] ?></td>
										</tr>
									</table>
								</td>
<?
if ($row["RUTAIMAGEN"] != "") {
?>
	<td width="8"></td>
	<td height="85" width="48" align="left" valign="top">&nbsp;<img border="0" src="<?= IMAGES_ARTICULOS_RELATIVE_PATH.$row["RUTAIMAGEN"] ?>"></td>
<?
}
?>
								<td width="4"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="divMenuSociales" name="divMenuSociales" style="background-color:#fff; display:none; left:64px; position:relative; top:-96px; width:80px;" onMouseOut="ocultarMenuSociales()" onMouseOver="mostrarMenuSociales()">
	<div style="cursor:hand; padding-bottom:2px; padding-left:4px; padding-top:2px;" onMouseOut="this.style.backgroundColor='';  cerrar = true;" onMouseOver="this.style.backgroundColor='#84ddff'; cerrar = false;"><a href="/index.php?pageid=19" style="color:#999; font-size:12px; text-decoration:none;">Casamientos</a></div>
	<div style="cursor:hand; padding-bottom:2px; padding-left:4px; padding-top:2px;" onMouseOut="this.style.backgroundColor='';  cerrar = true;" onMouseOver="this.style.backgroundColor='#84ddff'; cerrar = false;"><a href="/index.php?pageid=41" style="color:#999; font-size:12px; text-decoration:none;">Graduaciones</a></div>
	<div style="cursor:hand; padding-bottom:2px; padding-left:4px; padding-top:2px;" onMouseOut="this.style.backgroundColor='';  cerrar = true;" onMouseOver="this.style.backgroundColor='#84ddff'; cerrar = false;"><a href="/index.php?pageid=32" style="color:#999; font-size:12px; text-decoration:none;">Nacimientos</a></div>
</div>
<div id="divFotoPersonal" style="display:none; position:absolute;" onMouseOut="ocultarImagen()">
	<img border="5" id="imgFotoPersonal" src="" style="border-color:#808082; height:115px; width:117px;" />
</div>
<script>
<?
$sql =
	"SELECT se_foto
		 FROM art.use_usuarios
		WHERE TO_CHAR(se_fechacumple, 'DD/MM') = TO_CHAR(art.actualdate, 'DD/MM')
			AND se_fechabaja IS NULL
 ORDER BY se_nombre";
$stmt2 = DBExecSql($conn, $sql);
$count = 0;
while ($row2 = DBGetQuery($stmt2)) {
	$count++;

	$rutaFoto = base64_encode(IMAGES_FOTOS_PATH."cartel.jpg");
	if (is_file(IMAGES_FOTOS_PATH.$row2["SE_FOTO"]))
		$rutaFoto = base64_encode(IMAGES_FOTOS_PATH.$row2["SE_FOTO"]);
?>
	listaImagenes[<?= $count?>] = new Image();
	listaImagenes[<?= $count?>].src = '/functions/get_image.php?file=<?= $rutaFoto?>';
<?
}
?>
	Calendar.setup (
		{
			inputField: "fechaCumple",
			ifFormat  : "%d/%m/%Y",
			button    : "imgDia"
		}
 	);
</script>