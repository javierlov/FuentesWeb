<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/busqueda_empleados/busqueda_empleados.php");


function getTotalVotos($idPregunta, $idOpcion) {
	global $conn;

	$params = array(":idopcion" => $idOpcion, ":idpregunta" => $idPregunta);
	$sql =
		"SELECT COUNT(*)
			 FROM rrhh.rrp_respuestaspreguntas
			WHERE rp_fechabaja IS NULL
				AND rp_idpregunta = :idpregunta
				AND rp_idopcion = :idopcion";
	$votos = valorSql($sql, "", $params);

	if ($votos == 1)
		$votos = "(1 voto)";
	else
		$votos = "(".$votos." votos)";

	return $votos;
}
?>
<link href="/css/portada.css" rel="stylesheet" type="text/css" />
<script src="/modules/portada/js/portada.js?rnd=<?= date("Ymdhni")?>" type="text/javascript"></script>

<link href="/modules/portada/slider/js-image-slider.css" rel="stylesheet" type="text/css" />
<script src="/modules/portada/slider/js-image-slider.js" type="text/javascript"></script>

<div id="divContenidoIn">
	<div id="sliderFrame">
		<iframe id="iframeImagenesGrandes" name="iframeImagenesGrandes" src=""></iframe>
		<div id="slider">
<?
if (isset($_REQUEST["vistaprevia"]))
	$sql =
		"SELECT ai_id, ai_titulo
			 FROM web.wai_articulosintranet
			WHERE ai_ubicacion = 0
				AND ai_mostrarenportada = 'S'
				AND ai_fechabaja IS NULL
				AND ai_vistaprevia = 'S'
	 ORDER BY ai_posicion";
else
	$sql =
		"SELECT ai_id, ai_titulo
			 FROM web.wai_articulosintranet
			WHERE art.actualdate BETWEEN TRUNC(ai_fechavigenciadesde) AND ai_fechavigenciahasta
				AND ai_ubicacion = 0
				AND ai_mostrarenportada = 'S'
				AND ai_fechabaja IS NULL
	 ORDER BY ai_posicion";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
	$js = (isset($_REQUEST["vistaprevia"]))?"onMouseMove=\"mostrarBotonEdicion(this, 'a', ".$row["AI_ID"].")\" onMouseOut=\"ocultarBotonEdicion()\"":"";
?>
	<a href="/articulos/<?= $row["AI_ID"]?>" <?= $js?>><img alt="<?= $row["AI_TITULO"]?>" class="imagenesGrandes" src="" /></a>
<?
}
?>
		</div>
	</div>

	<div id="divBanner">
<?
if (isset($_REQUEST["vistaprevia"]))
	$sql =
		"SELECT br_id, br_imagen, br_multilink, br_target, br_url, br_vistaprevia
			 FROM rrhh.rbr_banners
			WHERE br_vistaprevia = 'S'
				AND br_fechabaja IS NULL
	 ORDER BY br_posicion";
else
	$sql =
		"SELECT br_id, br_imagen, br_multilink, br_target, br_url, br_vistaprevia
			 FROM rrhh.rbr_banners
			WHERE art.actualdate BETWEEN TRUNC(br_fechavigenciadesde) AND br_fechavigenciahasta
				AND br_fechabaja IS NULL
	 ORDER BY br_posicion";
$stmt = DBExecSql($conn, $sql);
$numBanner = -1;
$datos = array();
while ($row = DBGetQuery($stmt)) {
	$datos[] = $row;

	if ($numBanner == -1)
		$numBanner = $row["BR_ID"];
}
?>
		<div id="divBannerImagenes">
<?
foreach($datos as $row) {
	$img = IMAGES_BANNERS_PATH.$row["BR_ID"]."/".$row["BR_IMAGEN"];
	$img = "/functions/get_image.php?file=".base64_encode($img);

	$existeUrl = (($row["BR_MULTILINK"] == "S") or ($row["BR_URL"] != ""));
	if ($existeUrl) {
		$js = (isset($_REQUEST["vistaprevia"]))?"onMouseMove=\"mostrarBotonEdicion(this, 'b', ".$row["BR_ID"].")\" onMouseOut=\"ocultarBotonEdicion()\"":"";

		$target = "";
		if ($row["BR_TARGET"] != "")
			$target = "target=\"".$row["BR_TARGET"]."\"";
?>
		<a href="/modules/portada/link.php?l=5&id=<?= $row["BR_ID"]?>" <?= $target?>><img class="imgBanner" id="imgBanner_<?= $row["BR_ID"]?>" src="<?= $img?>" <?= $js?> /></a>
<?
	}
	else {
?>
		<img class="imgBanner" id="imgBanner_<?= $row["BR_ID"]?>" src="<?= $img?>" />
<?
	}
}
?>
		</div>
		<div id="divBannerBotonera">
<?
foreach($datos as $row) {
?>
	<div class="divBannerBoton" id="divBannerBoton_<?= $row["BR_ID"]?>" onClick="clicBotonBanner(<?= $row["BR_ID"]?>)"></div>
<?
}
?>
		</div>
	</div>

<?
if (isset($_REQUEST["vistaprevia"]))
	$sql =
		"SELECT ai_cuerpo, ai_id, ai_rutaimagen, ai_titulo, ai_volanta
			 FROM web.wai_articulosintranet
			WHERE ai_vistaprevia = 'S'
				AND ai_ubicacion = 1
				AND ai_mostrarenportada = 'S'
				AND ai_fechabaja IS NULL
	 ORDER BY ai_posicion";
else
	$sql =
		"SELECT ai_cuerpo, ai_id, ai_rutaimagen, ai_titulo, ai_volanta
			 FROM web.wai_articulosintranet
			WHERE art.actualdate BETWEEN TRUNC(ai_fechavigenciadesde) AND ai_fechavigenciahasta
				AND ai_ubicacion = 1
				AND ai_mostrarenportada = 'S'
				AND ai_fechabaja IS NULL
	 ORDER BY ai_posicion";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
	$imgChica = IMAGES_ARTICULOS_PATH.$row["AI_ID"]."/".$row["AI_RUTAIMAGEN"];
	$imgChica = "/functions/get_image.php?file=".base64_encode($imgChica);

	$js = (isset($_REQUEST["vistaprevia"]))?"onMouseMove=\"mostrarBotonEdicion(this, 'a', ".$row["AI_ID"].")\" onMouseOut=\"ocultarBotonEdicion()\"":"";
?>
	<div id="divNoticia">
		<div id="divNoticiaImagen"><img id="imgNoticiaImagen" src="<?= $imgChica?>" <?= $js?> /></div>
		<div id="divNota">
			<div id="divVolanta"><?= $row{"AI_VOLANTA"}?></div>
			<a href="/articulos/<?= $row["AI_ID"]?>"><div id="divTitulo"><?= $row{"AI_TITULO"}?></div></a>
			<div id="divTextoArticulo"><?= $row{"AI_CUERPO"}?></div>
		</div>
		<div id="divNada"></div>
	</div>
<?
}
?>

	<div id="divIngresos">
		<div id="divTituloNovedades"><img id="imgTituloNovedades" src="/modules/portada/images/ingresos.jpg" /></div>
<?
$sql =
	"SELECT useu.se_foto, useu.se_id, useu.se_nombre, se2.se_descripcion sector
		 FROM rrhh.rhn_novedades, use_usuarios useu, computos.cse_sector se2
		WHERE hn_idusuario = useu.se_id
			AND hn_idsectorhasta = se2.se_id(+)
			AND hn_tipomovimiento = 'A'
			AND art.actualdate BETWEEN TRUNC(hn_fechavigenciadesde) AND hn_fechavigenciahasta
			AND hn_fechabaja IS NULL
 ORDER BY hn_fechaalta DESC";
$stmt = DBExecSql($conn, $sql);
$mostrarIngresos = (DBGetRecordCount($stmt) > 0);
$par = false;
while ($row = DBGetQuery($stmt)) {
	$par = !$par;

	$rutaFoto = base64_encode(IMAGES_FOTOS_PATH."cartel.jpg");
	$foto = $row["SE_FOTO"];

	if (is_file(IMAGES_FOTOS_PATH.$foto))
		$rutaFoto = base64_encode(IMAGES_FOTOS_PATH.$foto);
?>
	<div id="<?= ($par)?"divItem1":"divItem2"?>">
		<div id="divItemNovedadesFoto"><img class="expando" id="imgItemNovedadesFoto" src="<?= "/functions/get_image.php?file=".$rutaFoto?>" onMouseOver="expandirImagen(this)" /></div>
		<div id="divItemNovedadesTexto">
			<a class="linkItemNovedadesTexto" href="/contacto/<?= $row["SE_ID"]?>"><?= $row["SE_NOMBRE"]?></a>
			<br />
			<span class="noLinkItemNovedadesTexto"><?= $row["SECTOR"]?></span>
		</div>
		<div id="divNada"></div>
	</div>
<?
}
?>
	</div>

	<div id="divPasesSector">
		<div id="divTituloNovedades"><img id="imgTituloNovedades" src="/modules/portada/images/pases_sector.jpg" /></div>
<?
$sql =
	"SELECT useu.se_foto, useu.se_id, useu.se_nombre, se1.se_descripcion sectordesde, se2.se_descripcion sectorhasta
		 FROM rrhh.rhn_novedades, use_usuarios useu, computos.cse_sector se1, computos.cse_sector se2
		WHERE hn_idusuario = useu.se_id
			AND hn_idsectordesde = se1.se_id(+)
			AND hn_idsectorhasta = se2.se_id(+)
			AND hn_tipomovimiento = 'M'
			AND art.actualdate BETWEEN TRUNC(hn_fechavigenciadesde) AND hn_fechavigenciahasta
			AND hn_fechabaja IS NULL
 ORDER BY hn_fechaalta DESC";
$stmt = DBExecSql($conn, $sql);
$mostrarPasesDeSector = (DBGetRecordCount($stmt) > 0);
$par = false;
while ($row = DBGetQuery($stmt)) {
	$par = !$par;

	$rutaFoto = base64_encode(IMAGES_FOTOS_PATH."cartel.jpg");
	$foto = $row["SE_FOTO"];

	if (is_file(IMAGES_FOTOS_PATH.$foto))
		$rutaFoto = base64_encode(IMAGES_FOTOS_PATH.$foto);
?>
		<div id="<?= ($par)?"divItem1":"divItem2"?>">
			<div id="divItemNovedadesFoto"><img class="expando" id="imgItemNovedadesFoto" src="<?= "/functions/get_image.php?file=".$rutaFoto?>" onMouseOver="expandirImagen(this)" /></div>
			<div id="divItemNovedadesTexto">
				<a class="linkItemNovedadesTexto" href="/contacto/<?= $row["SE_ID"]?>"><?= $row["SE_NOMBRE"]?></a>
				<br />
				<span class="noLinkItemNovedadesTexto">Pasa de <?= $row["SECTORDESDE"]?> a <?= $row["SECTORHASTA"]?></span>
			</div>
			<div id="divNada"></div>
		</div>
<?
}
?>
	</div>

	<div id="divBusquedas">
		<div id="divTituloNovedades"><img id="imgTituloNovedades" src="/modules/portada/images/busquedas.png" /></div>
<?
$sql =
	"SELECT bc_id, bc_nombrearchivo, bc_puesto, INSTR(bc_postulados, ',".getUserId().",') postulado
		 FROM rrhh.rbc_busquedascorporativas
		WHERE art.actualdate BETWEEN TRUNC(bc_fechavigenciadesde) AND bc_fechavigenciahasta
			AND bc_idestado = 3
			AND bc_fechabaja IS NULL
 ORDER BY bc_fechaalta DESC";
$stmt = DBExecSql($conn, $sql);
$mostrarBusquedas = (DBGetRecordCount($stmt) > 0);
$par = false;
while ($row = DBGetQuery($stmt)) {
	$par = !$par;

	$fileTitle = addslashes($row["BC_NOMBREARCHIVO"]);
	$partesFile = pathinfo($fileTitle);
	if (!isset($partesFile["extension"]))
		$partesFile["extension"] = "";
	$file = base64_encode(DATA_BUSQUEDAS_CORPORATIVAS_PATH.$row["BC_ID"].".".$partesFile["extension"]);
?>
		<div id="<?= ($par)?"divItem1":"divItem2"?>">
			<div class="noLinkItemNovedadesTexto" id="divItemNovedadesTexto">
				<span class="spanBusquedasTexto"><?= $row["BC_PUESTO"]?></span>
<?
	if ($partesFile["extension"] != "") {
?>
		<a href="<?= "/archivo/".$file."/".$fileTitle."/ok"?>" target="_blank"><img class="imgBusquedasVerPefil" src="/modules/portada/images/ver_perfil.png" title="VER PERFIL" /></a>
<?
	}
?>
				<a href="/modules/portada/postular_a_busqueda.php?id=<?= $row["BC_ID"]?>" target="iframeGeneral"><img class="imgBusquedasPostularme" id="imgBusquedasPostularme_<?= $row["BC_ID"]?>" src="/modules/portada/images/<?= ($row["POSTULADO"]==0)?"postularme.png":"ya_postulado.png"?>" title="POSTULARME" /></a>
			</div>
		</div>
<?
}
?>
	</div>

	<div id="divContenidoInBotonesAbajo">
		<div id="divExtranet"><a href="/modules/portada/link.php?l=2" target="_blank"><img id="imgExtranet" src="/modules/portada/images/extranet.jpg" /></a></div>
		<div id="divGrupoProvincia"><a href="/modules/portada/link.php?l=1" target="_blank"><img id="imgGrupoProvincia" src="/modules/portada/images/logo_grupo_provincia.jpg" /></a></div>
		<div id="divNada"></div>
	</div>
</div>

<div id="divSecciones">
	<div id="divBusquedaEmpleado">
		<div><img id="imgBusquedaEmpleadoFondo" src="/modules/portada/images/busqueda.jpg" /></div>
		<div id="divBusquedaEmpleadoFondo"></div>
		<div id="divBusquedaEmpleadoCampo">
			<? busquedaEmpleadosAgregarCodigo("", "left:3.4%; text-align:left; top:5.9%; width:91.2%;", true, true, "/contacto/<<idusuario>>")?>
		</div>
		<div id="divNada"></div>
	</div>

	<div id="divCalendario">
		<iframe id="iframeCalendario" name="iframeCalendario"></iframe>
		<iframe id="iframeEventos" name="iframeEventos" src=""></iframe>
		<input id="anoCalendario" name="anoCalendario" type="hidden" value="<?= date("Y")?>" />
		<input id="mesCalendario" name="mesCalendario" type="hidden" value="<?= date("m")?>" />
		<div id="divCalendarioTitulo">
			<span id="spanCalendarioPeriodo" onMouseEnter="mostrarPeriodos(document.getElementById('mesCalendario').value, document.getElementById('anoCalendario').value)" onMouseOut="ocultarPeriodos()"></span>
		</div>
		<div id="divCalendarioFondo">
			<div id="divTableCalendario"></div>
			<div id="divFeriados"></div>
			<div id="divEventos"></div>
		</div>
		<div id="divPeriodos"></div>
		<div id="divNada"></div>
	</div>

	<div style="margin-top:16px; position:relative; text-align:center; width:100%;">
		<a href="http://grupoprovincia.sumatupasion.com.ar" target="_blank"><img src="/modules/portada/images/prodeCA2015.jpg" style="border-bottom:#EF7E27 1px solid;"/></a>
	</div>

	<div id="divCumpleaños">
		<iframe id="iframeCambiarDia" name="iframeCambiarDia" src=""></iframe>
		<input id="diaCumple" name="diaCumple" type="hidden" value="0" />
		<input id="fechaActual" name="fechaActual" type="hidden" value="<?= date("d/m/Y")?>" />
		<input id="fechaCumple" name="fechaCumple" type="hidden" value="" onChange="elegirDiaCumple()" />
		<div id="divCumpleañosDatos">
			<div id="divCumpleañosVolanta">Sociales</div>
			<div id="divCumpleañosTitulo">CUMPLEAÑOS</div>
			<div id="divCumpleañosItems"></div>
		</div>
		<div id="divNada"></div>
	</div>

	<div id="divNacimientos">
		<div><img id="imgNacimientosFondo" src="/modules/portada/images/nacimientos.jpg" /></div>
<?
if (isset($_REQUEST["vistaprevia"]))
	$sql =
		"SELECT np_id, np_texto
			 FROM rrhh.rnp_novedadespersonales
			WHERE np_vistaprevia = 'S'
				AND np_tiponovedad = 'N'
				AND np_fechabaja IS NULL
	 ORDER BY NVL(np_fechamodif, np_fechaalta) DESC";
else
	$sql =
		"SELECT np_id, np_texto
			 FROM rrhh.rnp_novedadespersonales
			WHERE art.actualdate BETWEEN TRUNC(np_fechavigenciadesde) AND np_fechavigenciahasta
				AND np_tiponovedad = 'N'
				AND np_fechabaja IS NULL
	 ORDER BY NVL(np_fechamodif, np_fechaalta) DESC";
$stmt = DBExecSql($conn, $sql);
$numNacimiento = -1;
$datos = array();
while ($row = DBGetQuery($stmt)) {
	$datos[] = $row;

	if ($numNacimiento == -1)
		$numNacimiento = $row["NP_ID"];
}

foreach($datos as $row) {
	$js = (isset($_REQUEST["vistaprevia"]))?"onMouseMove=\"mostrarBotonEdicion(this, 'n', ".$row["NP_ID"].")\" onMouseOut=\"ocultarBotonEdicion()\"":"";

	$img = DATA_CELEBRACIONES_PATH.$row["NP_ID"];
	if (file_exists($img.".gif"))
		$img.= ".gif";
	elseif (file_exists($img.".jpeg"))
		$img.= ".jpeg";
	elseif (file_exists($img.".jpg"))
		$img.= ".jpg";
	elseif (file_exists($img.".png"))
		$img.= ".png";
	$img = "/functions/get_image.php?file=".base64_encode($img);
?>
	<div id="divNacimientosFondo">
		<div class="divNacimientosDatos" id="divNacimientos_<?= $row["NP_ID"]?>">
			<div class="divNacimientosImagen" id="divNacimientosImagen_<?= $row["NP_ID"]?>">
				<a href="/nacimientos/<?= $row["NP_ID"]?>"><img id="divNacimientosImagen" src="<?= $img?>" title="Clic aquí para comentar" <?= $js?> /></a>
			</div>
			<div class="divNacimientosLeft" id="divNacimientosLeft_<?= $row["NP_ID"]?>">
				<div id="divNacimientosTexto">
					<a class="divNacimientosTextoLink" href="/nacimientos/<?= $row["NP_ID"]?>"><?= htmlspecialchars_decode($row["NP_TEXTO"], ENT_QUOTES)?></a>
					<br /><br /><br />
				</div>
			</div>
		</div>
	</div>
<?
}
?>
		<div id="divNada"></div>
		<div id="divNacimientosBotonera">
<?
foreach($datos as $row) {
?>
	<div class="divNacimientosBoton" id="divNacimientosBoton_<?= $row["NP_ID"]?>" onClick="clicBotonNacimiento(<?= $row["NP_ID"]?>)"></div>
<?
}
?>
		</div>
	</div>

	<div id="divEncuesta">
		<div><img id="imgEncuestaFondo" src="/modules/portada/images/encuesta.jpg" /></div>
		<div id="divEncuestaFondo">
<?
$sql =
	"SELECT COUNT(*)
		 FROM rrhh.ren_encuestas, rrhh.rpe_preguntasencuesta
		WHERE en_id = pe_idencuesta
			AND art.actualdate BETWEEN TRUNC(en_fechavigenciadesde) AND en_fechavigenciahasta
			AND en_activa = 'T'
			AND en_fechabaja IS NULL
			AND pe_fechabaja IS NULL";
$preguntas = valorSql($sql, "");

if ($preguntas > 0) {
	$sql =
		"SELECT en_id, en_mostrarresultados, en_titulo, pe_id, pe_pregunta
			 FROM rrhh.ren_encuestas, rrhh.rpe_preguntasencuesta
			WHERE en_id = pe_idencuesta
				AND art.actualdate BETWEEN TRUNC(en_fechavigenciadesde) AND en_fechavigenciahasta
				AND en_activa = 'T'
				AND en_fechabaja IS NULL
				AND pe_fechabaja IS NULL";
	$stmt = DBExecSql($conn, $sql);
	$rowPregunta = DBGetQuery($stmt);

	if ($preguntas == 1) {
		$params = array(":idpregunta" => $rowPregunta["PE_ID"], ":usuario" => getUserId());
		$sql =
			"SELECT 1
				 FROM rrhh.rrp_respuestaspreguntas
				WHERE rp_fechabaja IS NULL
					AND rp_idpregunta = :idpregunta
					AND rp_usuario = :usuario";
		$encuestaContestada = existeSql($sql, $params);

		$params = array(":idpregunta" => $rowPregunta["PE_ID"]);
		$sql =
			"SELECT COUNT(*)
				 FROM rrhh.rrp_respuestaspreguntas
				WHERE rp_fechabaja IS NULL
					AND rp_idpregunta = :idpregunta";
		$cantidadVotos = valorSql($sql, "", $params);
		switch ($cantidadVotos) {
			case 0:
				$cantidadVotos = "Sin votos";
				break;
			case 1:
				$cantidadVotos = "1 voto";
				break;
			default:
				$cantidadVotos = $cantidadVotos." votos";
		}

		$params = array(":idpregunta" => $rowPregunta["PE_ID"], ":usuario" => getUserId());
		$sql =
			"SELECT op_id, op_opcion, rp_usuario
				 FROM rrhh.rop_opcionespreguntas, rrhh.rrp_respuestaspreguntas
				WHERE op_id = rp_idopcion(+)
					AND op_fechabaja IS NULL
					AND rp_fechabaja IS NULL
					AND op_idpregunta = :idpregunta
					AND rp_usuario(+) = :usuario
		 ORDER BY 1";
		$stmt = DBExecSql($conn, $sql, $params);
?>
		<iframe id="iframeEncuesta" name="iframeEncuesta" src="" style="display:none;"></iframe>
		<form action="/modules/encuestas/guardar_encuesta_portada.php" id="formEncuesta" method="post" name="formEncuesta" target="iframeEncuesta">
			<input id="idEncuesta" name="idEncuesta" type="hidden" value="<?= $rowPregunta["EN_ID"]?>" />
			<input id="idPregunta" name="idPregunta" type="hidden" value="<?= $rowPregunta["PE_ID"]?>" />
			<div id="divEncuestaTitulo"><?= $rowPregunta["PE_PREGUNTA"]?></div>
			<div id="divEncuestasOpciones">
<?
		while ($row = DBGetQuery($stmt)) {
?>
			<div>
				<input <?= ($row["RP_USUARIO"] != "")?"checked":""?> <?= ($encuestaContestada)?"disabled":""?> id="itemEncuesta" name="itemEncuesta" type="radio" value="<?= $row["OP_ID"]?>" />
				<span><?= $row["OP_OPCION"]?></span>
				<span class="spanEncuestasCantidadVotos"><?= getTotalVotos($rowPregunta["PE_ID"], $row["OP_ID"])?></span>
			</div>
<?
		}
?>
			</div>
			<div id="divEncuestaBottom">
				<span id="divEncuestaCantidadVotos"><?= $cantidadVotos?></span>
<?
		if ($encuestaContestada) {
			if ($rowPregunta["EN_MOSTRARRESULTADOS"] == "T") {
?>
				<div id="divEncuestaBottomRight"><a href="javascript:verResultadosEncuesta()">Ver resultados</a></div>
<?
			}
			else {
?>
				<div id="divEncuestaBottomRight"><b>Gracias por participar!</b></div>
<?
			}
		}
		else {
?>
			<div id="divEncuestaBottomRight"><input id="btnVotar" name="btnVotar" type="submit" value="" /></div>
<?
		}
?>
				<div id="divNada"></div>
			</div>
		</form>
<?
	}
	else {
?>
		<div id="divEncuestaGrande" title="Clic aquí para participar de la encuesta" onClick="window.location.href = '/encuestas/<?= $rowPregunta["EN_ID"]?>'">
			<div id="divEncuestaTitulo"><?= $rowPregunta["EN_TITULO"]?></div>
			<div id="divEncuestasOpciones">Clic aquí para participar de la encuesta</div>
		</div>
<?
	}
}
?>
			<div id="divEncuestasFondoValidacion">
				<img id="imgEncuestasCerrarValidacion" src="/images/cerrar.png" title="Cerrar" onClick="continuarVotando()" />
			</div>
			<div id="divEncuestasContenidoValidacion">
				<img src="/images/atencion.png" />
				<span id="spanEncuestasTextoValidacion">Por favor, seleccione una opción.</span>
			</div>
		</div>
	</div>
<!--
	<div id="divArteriaNoticias" onClick="window.open('/arteria-noticias', 'arteriaWindow', '')">
		<div><img id="imgArteriaNoticiasFondo" src="/modules/portada/images/arteria.jpg" /></div>
	</div>
-->
	<div id="divFotoPersonal" onMouseOut="ocultarImagen()">
		<div id="divFotoPersonalFecha"></div>
		<img id="imgFotoPersonal" src="" />
	</div>

	<div id="divEditar">
		<input id="editarTipo" name="editarTipo" type="hidden" value="" />
		<input id="editarId" name="editarId" type="hidden" value="-1" />
		<img src="/images/botones_formularios/boton_editar.png" title="Clic aquí para editar" onClick="editar()" />
	</div>
</div>

<div id="divNada"></div>

<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaCumple",
		ifFormat  : "%d/%m/%Y",
		button    : "divCumpleañosTitulo"
	});

	cambiarDia(0);
	cambiarPeriodoCalendario(0, 0, <?= (isset($_REQUEST["vistaprevia"]))?"true":"false"?>);
	mostrarBusquedas(<?= ($mostrarBusquedas)?"true":"false"?>);
	mostrarIngresos(<?= ($mostrarIngresos)?"true":"false"?>);
	mostrarPasesDeSector(<?= ($mostrarPasesDeSector)?"true":"false"?>);
	setBanners(<?= $numBanner?>);
	setEncuestas(<?= $preguntas?>);
	setImagenesGrandes(document.getElementById('slider').offsetWidth, <?= (isset($_REQUEST["vistaprevia"]))?"true":"false"?>);
	setNacimientos(<?= $numNacimiento?>);
</script>