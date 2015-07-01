<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");

SetDateFormatOracle("DD/MM/YYYY"); 

$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT TO_CHAR(se_fechacumple, 'dd/mm') cumple,
					art.utiles.get_anios(se_fechacumple, SYSDATE) edad,
					es_descripcion || ' - ' || es_calle || ' ' || es_numero edificio,
					cse3.se_descripcion gerencia,
					es_imagenintranet imagenedificio,
					useu.se_contrato,
					useu.se_delegacion,
					useu.se_ejex,
					useu.se_ejey,
					useu.se_fechacumple,
					useu.se_foto,
					useu.se_horarioatencion,
					useu.se_id,
					useu.se_interno,
					useu.se_legajo,
					useu.se_mail,
					useu.se_nombre,
					useu.se_usuario,
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

if ($row["IMAGENEDIFICIO"] != "")
	$strMapa = IMAGES_MAPAS_RELATIVE_PATH.$row["IMAGENEDIFICIO"];
elseif ($row["SE_PISO"] != "")
	$strMapa = IMAGES_MAPAS_RELATIVE_PATH."piso".$row["SE_PISO"].".gif";
elseif ($row["SE_SECTOR"] == "CSUIZA")
	$strMapa = IMAGES_MAPAS_RELATIVE_PATH."csuiza.jpg";
elseif ($row["SE_DELEGACION"] == 840)
	$strMapa = "/images/blank.gif";
else {
	$params = array(":id" => $row["SE_DELEGACION"]);
	$sql =
		"SELECT el_mapa
			 FROM del_delegacion
			WHERE el_id = :id";
	$strMapa = IMAGES_MAPAS_RELATIVE_PATH.ValorSql($sql, "", $params);
}

$fechaCumple = "";
if (GetUserSector() == "COMPUTOS") {
	if ($row["SE_FECHACUMPLE"] != "")
		$fechaCumple = $row["SE_FECHACUMPLE"]."&nbsp;&nbsp;(".$row["EDAD"]." años)";
	}
else
	$fechaCumple = $row["CUMPLE"];
?>
<link href="/modules/buscar_usuarios/css/style_buscar_usuarios.css" rel="stylesheet" type="text/css" />
<script>
	showTitle(true, 'AGENDA TELEFÓNICA');
</script>
<iframe id="iframeProcesando" name="iframeProcesando" src="/modules/buscar_usuarios/obtener_dentro_fuera.php?id=<?= $_REQUEST["id"]?>&l=<?= $row["SE_LEGAJO"]?>&c=<?= $row["SE_CONTRATO"]?>&e=<?= $row["IMAGENEDIFICIO"]?>" style="display:none;"></iframe>
<input id="EjeX" name="EjeX" type="hidden" value="<?= $row["SE_EJEX"]?>" />
<input id="EjeY" name="EjeY" type="hidden" value="<?= $row["SE_EJEY"]?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="center">
			<table border="0" cellpadding="0" cellspacing="0" width="770">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td height="24"></td>
							</tr>
							<tr>
								<td>
									<table background="/modules/buscar_usuarios/images/marco.jpg" border="0" cellpadding="0" cellspacing="0" height="133" width="141">
										<tr>
											<td align="center"><?= $inicioLinkFoto?><img border="0" src="<?= "/functions/get_image.php?file=".$rutaFoto ?>" width="117" height="115"><?= $finLinkFoto?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td width="8"></td>
					<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td align="left" class="FormLabelAzulCabecera"><p><?= $row["SE_NOMBRE"] ?></td>
							</tr>
							<tr>
								<td><hr color="#C0C0C0" size="1"></td>
							</tr>
							<tr>
								<td style="height:8px;"></td>
							</tr>
							<tr>
								<td align="left" class="FormLabelAzul12">
									<img border="0" height="19" src="/modules/buscar_usuarios/images/tel.jpg" style="vertical-align:-4px;" width="23">&nbsp;&nbsp;&nbsp;<?= $row["SE_INTERNO"] ?>
								</td>
							</tr>
							<tr>
								<td height="8"></td>
							</tr>
							<tr>
								<td align="left"><img border="0" src="/modules/buscar_usuarios/images/vineta.jpg" width="10" height="7"><span class="FormLabelGrisSinNegrita11">&nbsp;E-Mail: </span><span class="FormLabelAzul"><a class="FormLabelNegroSinNegrita11" href="mailto:<?= $row["SE_MAIL"]?>" style="text-decoration:none;"><?= $row["SE_MAIL"] ?></a></span></td>
							</tr>
							<tr>
								<td align="left"><img border="0" src="/modules/buscar_usuarios/images/vineta.jpg" width="10" height="7"><span class="FormLabelGrisSinNegrita11">&nbsp;Edificio: </span><span class="FormLabelNegroSinNegrita11"><?= $row["EDIFICIO"] ?></span></td>
							</tr>							
<?
if ($row["SE_PISO"] != "") {
?>
							<tr>
								<td align="left"><img border="0" src="/modules/buscar_usuarios/images/vineta.jpg" width="10" height="7"><span class="FormLabelGrisSinNegrita11">&nbsp;Piso: </span><span class="FormLabelNegroSinNegrita11"><?= $row["SE_PISO"] ?></span></td>
							</tr>
<?
}
?>
							<tr>
								<td align="left"><img border="0" src="/modules/buscar_usuarios/images/vineta.jpg" width="10" height="7"><span class="FormLabelGrisSinNegrita11">&nbsp;Gerencia: </span><span><a class="FormLabelNegroSinNegrita11" href="/index.php?pageid=5&buscar=yes&Sector=<?= $row["GERENCIA"] ?>" style="text-decoration: none"><?= $row["GERENCIA"] ?></a></span></td>
							</tr>
							<tr>
								<td align="left"><img border="0" src="/modules/buscar_usuarios/images/vineta.jpg" width="10" height="7"><span class="FormLabelGrisSinNegrita11">&nbsp;Sector: </span><span class="FormLabelNegroSinNegrita11"><a class="FormLabelNegroSinNegrita11" href="/index.php?pageid=5&buscar=yes&Sector=<?= $row["SECTOR"] ?>" style="text-decoration: none"><?= $row["SECTOR"] ?></a></span></td>
							</tr>
<?
if ($row["SE_HORARIOATENCION"] != "") {
?>
							<tr>
								<td align="left"><img border="0" src="/modules/buscar_usuarios/images/vineta.jpg" width="10" height="7"><span class="FormLabelGrisSinNegrita11">&nbsp;Horario de Atención: </span><span class="FormLabelNegroSinNegrita11"><?= $row["SE_HORARIOATENCION"] ?></span></td>
							</tr>
<?
}
?>
							<tr>
								<td align="left"><img border="0" src="/modules/buscar_usuarios/images/vineta.jpg" width="10" height="7"><span class="FormLabelGrisSinNegrita11">&nbsp;Cumpleaños: </span><span class="FormLabelNegroSinNegrita11"><?= $fechaCumple ?></span></td>
							</tr>
<?
if ($row["SE_PISO"] != "") {
?>
							<tr>
								<td height="8"></td>
							</tr>
							<tr>
								<td align="left">
									<img border="0" height="8" id="imgReferencia" src="/images/loading.gif" style="display:none; margin-left:4px;" width="8">
									<span class="FormLabelAzul" id="spanLeyenda" style="display:none; margin-left:8px;">Cargando información...</span>
<!-- COMENTADO POR E-MAIL DE VDOMINGUEZ DEL 20.8.2013..
									<img border="0" height="8" id="imgReferencia" src="/images/loading.gif" style="margin-left:4px;" width="8">
									<span class="FormLabelAzul" id="spanLeyenda" style="margin-left:8px;">Cargando información...</span>-->
								</td>
							</tr>
<?
}
?>
							<tr>
								<td height="24"><hr color="#C0C0C0" size="1"></td>			
							</tr>
<!--
							<tr>
								<td><input type="hidden" name="id" size="19" value="<?= $row["SE_ID"]?>"><input class="BotonGris" name="btnInformar" type="button" value="Informar Datos Erróneos" onClick="OpenWindow('/modules/buscar_usuarios/errores.php?id=<?= $row["SE_ID"]?>', 'intranetWindow', 320, 160, 'no', 'no')">
							</tr>
-->
						</table>
					</td>
					<td>
<?
if ($row["SE_PISO"] != "") {
?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td valign="top"><img id="Mapa" name="Mapa" src="<?= $strMapa ?>" />
<!--				<img border="0" id="imgCoordenada" name="imgCoordenada" src="/images/loading.gif" style="height:8px; left:0; position:relative; top:0; width:8px;"><p>
-->			</td>
		</tr>
	</table>

<?
}
else {
?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align="center" valign="top"><img border="0" src="<?= $strMapa ?>"></td>
		</tr>
	</table>
<?
}
?>
					</td>
				</tr>
			</table>
		</tr>
	</td>
</table>
<script>
	function setCoordenadas() {
		try {
			with (document) {
				getElementById('imgCoordenada').style.left = (getElementById('EjeX').value - getElementById('Mapa').width - 4) + 'px';
				getElementById('imgCoordenada').style.top = (getElementById('EjeY').value - getElementById('Mapa').height + 4) + 'px';
			}
		}
		catch(err) {
			//
		}
	}

	if (document.getElementById('imgCoordenada') != null) {
		document.getElementById('imgCoordenada').onload = function() {
			try {
				setCoordenadas();
			}
			catch(err) {
				//
			}
		}
	}

	if (document.getElementById('Mapa') != null) {
		document.getElementById('Mapa').onload = function() {
			setCoordenadas();
			try {		// Este try lo pongo porque en algún momento (que no logro descrifrar bien cual es) se invoca a esta función, pero no se encuentra la llamada..
				CopyContent();
			}
			catch(err) {
				//
			}
			setCoordenadas();
		}
	}
<?= $jsImagen?>
</script>