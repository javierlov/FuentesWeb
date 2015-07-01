<?
function getArticulo($pos) {
	global $conn;

	$params = array(":posicion" => $pos, ":usuario" => GetWindowsLoginName(true));
	$sql =
		"SELECT ax_cuerpo cuerpo, ax_id id, ax_rutaimagen rutaimagen, ax_target target, UPPER(ax_titulo) titulo, ax_volanta volanta
			 FROM web.wax_articulosextranetedicion
			WHERE ax_baja = 'F'
				AND ax_usuario = :usuario
				AND ax_posicion = :posicion";
	$stmt = DBExecSql($conn, $sql, $params);
	return DBGetQuery($stmt);
}


if (!isset($_SESSION["extranetEdicionActiva"])) {		// Si es la primera vez que entra a la pàgina en la sesión actual del navegador..
	// Borro registros temporales anteriores..
	$params = array(":usuario" => getWindowsLoginName(true));
	$sql = "DELETE FROM web.wax_articulosextranetedicion WHERE ax_usuario = :usuario";
	DBExecSql($conn, $sql, $params);

	// Cargo los registros temporales..
	$params = array(":usuario" => getWindowsLoginName(true));
	$sql =
		"INSERT INTO web.wax_articulosextranetedicion
								 (ax_cuerpo, ax_cuerpofull, ax_id, ax_idarticuloextranet, ax_posicion, ax_target, ax_titulo, ax_usuario, ax_volanta)
					SELECT ae_cuerpo, ae_cuerpofull, web.seq_wax_id.NEXTVAL, ae_id, ae_posicion, ae_target, ae_titulo, :usuario, ae_volanta
						FROM web.wae_articulosextranet
					 WHERE ae_fechabaja IS NULL";
	DBExecSql($conn, $sql, $params);
}

if (!isset($_SESSION["extranetEdicionActiva"]))
	$_SESSION["extranetEdicionActiva"] = true;


$art1 = getArticulo(1);
$art2 = getArticulo(2);
$art3 = getArticulo(3);
$art4 = getArticulo(4);

$carpetaImagenes = date("YmdHis").substr(getWindowsLoginName(true), 0, 3);
?>
<script src="/js/ckeditor/ckeditor.js"></script>
<script>
	function agregarArticulo() {
		with (document) {
			getElementById('cuerpo').value = '';
			getElementById('cuerpoFull').value = '';
			getElementById('posicion').value = '';
			getElementById('titulo').value = '';
			getElementById('volanta').value = '';

			getElementById('divFondo').style.display = 'block';
			getElementById('divAlta').style.display = 'block';

			getElementById('volanta').focus();
		}
	}

	function aplicarCambios() {
		if (confirm('Esta acción va a modificar la información de la extranet.\nSi continua, todas las modificaciones que haya hecho van a estar disponibles en el sitio web de Provincia ART.\n\n¿ Realmente desea continuar ?')) {
			document.getElementById('btnAplicarCambios').style.display = 'none';
			document.getElementById('imgAplicandoCambios').style.display = 'inline';
			iframeProcesando.location.href = '/modules/extranet/aplicar_cambios.php';
		}
	}

	function cargarCuerpoFull(pos) {
		iframeProcesando.location.href = '/modules/extranet/cargar_cuerpo_full.php?id=' + document.getElementById('id' + pos).value;
	}

	function cerrarVentanaAlta() {
		with (document) {
			getElementById('divAlta').style.display = 'none';
			getElementById('divFondo').style.display = 'none';
		}
	}

	function cerrarVentanaCuerpoFull() {
		with (document) {
			getElementById('divCuerpoFull').style.display = 'none';

			if (getElementById('idArticulo').value == -1)
				getElementById('divAlta').style.display = 'block';
			else
				getElementById('divFondo').style.display = 'none';
		}
	}

	function cerrarVentanaModificacion() {
		with (document) {
			for (var i=1; i <= 4; i++)
				getElementById(getElementById('tipoTmp').value + i).style.backgroundColor = '';

			getElementById('divCampo').style.display = 'none';
			getElementById('divFondo').style.display = 'none';
		}
	}

	function contarCaracteres() {
		with (document) {
			totalCaracteres = getElementById('texto').value.length;

			if (totalCaracteres > getElementById('maxCaracteres').value)
				getElementById('texto').value = getElementById('texto').value.substr(0, getElementById('maxCaracteres').value);
			else
				getElementById('caracteresRestantes').innerHTML = getElementById('maxCaracteres').value - totalCaracteres;

			if (totalCaracteres > (getElementById('maxCaracteres').value * 16 /100))
				getElementById('caracteresRestantes').style.color = '#855353';
			if (totalCaracteres > (getElementById('maxCaracteres').value * 32 /100))
				getElementById('caracteresRestantes').style.color = '#a33f3f';
			if (totalCaracteres > (getElementById('maxCaracteres').value * 49 /100))
				getElementById('caracteresRestantes').style.color = '#c13535';
			if (totalCaracteres > (getElementById('maxCaracteres').value * 65 /100))
				getElementById('caracteresRestantes').style.color = '#df2121';
			if (totalCaracteres > (getElementById('maxCaracteres').value * 82 /100))
				getElementById('caracteresRestantes').style.color = '#f00';
		}
	}

	function editar(tipo, numero) {
		with (document) {
			getElementById(tipo + numero).style.backgroundColor = '#91cbff';

			if ((numero == 1) || (numero == 2))
				getElementById('divCampo').style.top = '320px';
			else
				getElementById('divCampo').style.top = '112px';

			getElementById('idTmp').value = getElementById('id' + numero).value;
			getElementById('tipoTmp').value = tipo;

			switch(tipo) {
				case 'c':
					getElementById('maxCaracteres').value = 512;
					getElementById('texto').value = getElementById(tipo + numero).innerText.substr(0, (getElementById(tipo + numero).innerText.length - 3));
					break;
				case 't':
					getElementById('maxCaracteres').value = 70;
					getElementById('texto').value = getElementById(tipo + numero).innerText;
					break;
				case 'v':
					getElementById('maxCaracteres').value = 50;
					getElementById('texto').value = getElementById(tipo + numero).innerText;
					break;
			}

			getElementById('caracteresRestantes').innerText = getElementById('maxCaracteres').value - getElementById('texto').value.length;

			getElementById('divFondo').style.display = 'block';
			getElementById('divCampo').style.display = 'block';

			getElementById('texto').focus();
		}
	}

	function editarCuerpoFull() {
		with (document) {
			CKEDITOR.instances.cuerpoFullEditable.setData(getElementById('cuerpoFull').value);
			getElementById('idArticulo').value = -1;
			getElementById('divAlta').style.display = 'none';
			getElementById('btnGuardarCuerpoFull').style.display = 'inline';
			getElementById('imgGuardandoCuerpoFull').style.display = 'none';
			getElementById('divCuerpoFull').style.display = 'block';
		}
	}

	function editarOff(obj) {
		var url = obj.src.substr(0, (obj.src.length - 6));
		url+= 'off.png';
		obj.src = url;
	}

	function editarOn(obj) {
		var url = obj.src.substr(0, (obj.src.length - 7));
		url+= 'on.png';
		obj.src = url;
	}

	function enviar() {
		with (document) {
			getElementById('btnEnviar').style.display = 'none';
			getElementById('imgSubiendoImagen').style.display = 'inline';
		}
	}

	function flechaOff(obj) {
		var url = obj.src.substr(0, (obj.src.length - 6));
		url+= 'off.png';
		obj.src = url;
	}

	function flechaOn(obj) {
		var url = obj.src.substr(0, (obj.src.length - 7));
		url+= 'on.png';
		obj.src = url;
	}

	function guardar() {
		with (document) {
			getElementById('btnGuardar').style.display = 'none';
			getElementById('imgGuardandoCampo').style.display = 'inline';
			iframeProcesando.location.href = '/modules/extranet/guardar_texto.php?id=' + getElementById('idTmp').value + '&tipo=' + getElementById('tipoTmp').value + '&texto=' + getElementById('texto').value;
		}
	}

	function guardarAlta() {
		with (document) {
			getElementById('btnGuardarAlta').style.display = 'none';
			getElementById('imgGuardandoAlta').style.display = 'inline';
		}
	}

	function guardarCuerpoFull() {
		with (document) {
			getElementById('btnGuardarCuerpoFull').style.display = 'none';
			getElementById('imgGuardandoCuerpoFull').style.display = 'inline';
		}
	}

	function manejarFlechas() {
		with (document) {
			for (var i=0; i < getElementsByName('divFlechas').length; i++)
				if (document.getElementById('checkMostrarIconos').checked)
					getElementsByName('divFlechas')[i].style.display = 'block';
				else
					getElementsByName('divFlechas')[i].style.display = 'none';
		}
		with (document) {
			for (var i=0; i < getElementsByName('divEditar').length; i++)
				if (document.getElementById('checkMostrarIconos').checked)
					getElementsByName('divEditar')[i].style.display = 'block';
				else
					getElementsByName('divEditar')[i].style.display = 'none';
		}
	}

	function moverArticulo(pos1, pos2) {
		iframeProcesando.location.href = '/modules/extranet/mover_articulo.php?pos1=' + pos1 + '&pos2=' + pos2;
	}

	function recuperar() {
		if (confirm('Esta acción va a recuperar los datos actuales de la extranet.\nSi continua, todas las modificaciones que haya hecho se van a perder.\n\n¿ Realmente desea continuar ?'))
			iframeProcesando.location.href = '/modules/extranet/recuperar.php';
	}
</script>
<link rel="stylesheet" href="http://www.provinciart.com.ar/styles/portada.css" type="text/css" />
<style type="text/css">
	.Cuerpo:hover {background-color:#91cbff; cursor:hand;}
	.Titular:hover {background-color:#91cbff; cursor:hand;}
	.Volanta:hover {background-color:#91cbff; cursor:hand;}

	#divFlechas {cursor:pointer;}
	#imgAplicandoCambios {margin-left:64px;}
</style>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<input id="id1" name="id1" type="hidden" value="<?= $art1["ID"]?>" />
<input id="id2" name="id2" type="hidden" value="<?= $art2["ID"]?>" />
<input id="id3" name="id3" type="hidden" value="<?= $art3["ID"]?>" />
<input id="id4" name="id4" type="hidden" value="<?= $art4["ID"]?>" />
<input id="idTmp" name="idTmp" type="hidden" value="" />
<input id="maxCaracteres" name="maxCaracteres" type="hidden" value="255" />
<input id="tipoTmp" name="tipoTmp" type="hidden" value="" />
<div style="position:relative;">
	<div style="height:120px; left:0px; position:absolute; top:12px; width:368px;">
		<div class="Volanta" id="v1" name="v1" onClick="editar('v', 1)"><?= $art1["VOLANTA"]?></div>
		<div class="Titular" id="t1" name="t1" style="margin-bottom:4px; margin-top:4px;" onClick="editar('t', 1)"><?= $art1["TITULO"]?></div>
		<div class="Cuerpo" id="c1" name="c1" onClick="editar('c', 1)"><?= $art1["CUERPO"]?>[+]</div>
		<div id="divEditar" name="divEditar" style="display:none; left:50%; position:absolute; top:50%; z-index:50;"><img border="0" src="/modules/extranet/images/editar_off.png" style="cursor:pointer; height:32px; width:32px;" title="Editar Cuerpo Full" onClick="cargarCuerpoFull(1)" onMouseOut="editarOff(this)" onMouseOver="editarOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:336px; position:absolute; top:64px; z-index:50;"><img src="/modules/extranet/images/derecha_off.png" title="Mover hacia la derecha" onClick="moverArticulo(1, 2)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:334px; position:absolute; top:104px; z-index:50;"><img src="/modules/extranet/images/abajo_derecha_off.png" title="Mover hacia abajo y a la derecha" onClick="moverArticulo(1, 4)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:184px; position:absolute; top:108px; z-index:50;"><img src="/modules/extranet/images/abajo_off.png" title="Mover hacia abajo" onClick="moverArticulo(1, 3)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
	</div>
	<div class="LineaVertical" style="height:132px; left:376px; position:absolute; top:12px;"></div>
	<div style="left:392px; position:absolute; top:12px; width:360px;">
		<div class="Volanta" id="v2" name="v2" onClick="editar('v', 2)"><?= $art2["VOLANTA"]?></div>
		<div class="Titular" id="t2" name="t2" style="margin-bottom:4px; margin-top:4px;" onClick="editar('t', 2)"><?= $art2["TITULO"]?></div>
		<div class="Cuerpo" id="c2" name="c2" onClick="editar('c', 2)"><?= $art2["CUERPO"]?>[+]</div>
		<div id="divEditar" name="divEditar" style="display:none; left:50%; position:absolute; top:50%; z-index:50;"><img border="0" src="/modules/extranet/images/editar_off.png" style="cursor:pointer; height:32px; width:32px;" title="Editar Cuerpo Full" onClick="cargarCuerpoFull(2)" onMouseOut="editarOff(this)" onMouseOver="editarOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:-8px; position:absolute; top:64px; z-index:50;"><img src="/modules/extranet/images/izquierda_off.png" title="Mover hacia la izquierda" onClick="moverArticulo(2, 1)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:-12px; position:absolute; top:104px; z-index:50;"><img src="/modules/extranet/images/abajo_izquierda_off.png" title="Mover hacia abajo y a la izquierda" onClick="moverArticulo(2, 3)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:168px; position:absolute; top:108px; z-index:50;"><img src="/modules/extranet/images/abajo_off.png" title="Mover hacia abajo" onClick="moverArticulo(2, 4)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
	</div>

	<div class="LineaHorizontal" style="left:0px; position:absolute; top:144px; width:744px;">&nbsp;</div>

	<div style="height:120px; left:0px; position:absolute; top:168px; width:344px;">
		<div class="Volanta" id="v3" name="v3" onClick="editar('v', 3)"><?= $art3["VOLANTA"]?></div>
		<div class="Titular" id="t3" name="t3" style="margin-bottom:4px; margin-top:4px;" onClick="editar('t', 3)"><?= $art3["TITULO"]?></div>
		<div class="Cuerpo" id="c3" name="c3" onClick="editar('c', 3)"><?= $art3["CUERPO"]?>[+]</div>
		<div id="divEditar" name="divEditar" style="display:none; left:50%; position:absolute; top:50%; z-index:50;"><img border="0" src="/modules/extranet/images/editar_off.png" style="cursor:pointer; height:32px; width:32px;" title="Editar Cuerpo Full" onClick="cargarCuerpoFull(3)" onMouseOut="editarOff(this)" onMouseOver="editarOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:168px; position:absolute; top:-4px; z-index:50;"><img src="/modules/extranet/images/arriba_off.png" title="Mover hacia arriba" onClick="moverArticulo(3, 1)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:312px; position:absolute; top:-8px; z-index:50;"><img src="/modules/extranet/images/arriba_derecha_off.png" title="Mover hacia arriba y a la derecha" onClick="moverArticulo(3, 2)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:312px; position:absolute; top:56px; z-index:50;"><img src="/modules/extranet/images/derecha_off.png" title="Mover hacia la derecha" onClick="moverArticulo(3, 4)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
	</div>
	<div class="LineaVertical" style="height:132px; left:352px; position:absolute; top:168px;"></div>
	<div style="left:368px; position:absolute; top:168px; width:384px;">
		<div class="Volanta" id="v4" name="v4" onClick="editar('v', 4)"><?= $art4["VOLANTA"]?></div>
		<div class="Titular" id="t4" name="t4" style="margin-bottom:4px; margin-top:4px;" onClick="editar('t', 4)"><?= $art4["TITULO"]?></div>
		<div class="Cuerpo" id="c4" name="c4" onClick="editar('c', 4)"><?= $art4["CUERPO"]?>[+]</div>
		<div id="divEditar" name="divEditar" style="display:none; left:50%; position:absolute; top:50%; z-index:50;"><img border="0" src="/modules/extranet/images/editar_off.png" style="cursor:pointer; height:32px; width:32px;" title="Editar Cuerpo Full" onClick="cargarCuerpoFull(4)" onMouseOut="editarOff(this)" onMouseOver="editarOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:-8px; position:absolute; top:56px; z-index:50;"><img src="/modules/extranet/images/izquierda_off.png" title="Mover hacia la izquierda" onClick="moverArticulo(4, 3)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:-16px; position:absolute; top:-8px; z-index:50;"><img src="/modules/extranet/images/arriba_izquierda_off.png" title="Mover hacia arriba y a la izquierda" onClick="moverArticulo(4, 1)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
		<div id="divFlechas" name="divFlechas" style="display:none; left:168px; position:absolute; top:-4px; z-index:50;"><img src="/modules/extranet/images/arriba_off.png" title="Mover hacia arriba" onClick="moverArticulo(4, 2)" onMouseOut="flechaOff(this)" onMouseOver="flechaOn(this)" /></div>
	</div>
	<div style="left:0px; position:absolute; top:400px;">
		<label>Mostrar íconos</label>
		<input checked id="checkMostrarIconos" name="checkMostrarIconos" style="vertical-align:-3px;" type="checkbox" onClick="manejarFlechas()" />
	</div>
	<div style="left:272px; position:absolute; text-align:center; top:400px;">
		<input class="btnAgregarArticulo" id="btnAgregarArticulo" type="button" onClick="agregarArticulo()" />
		<input class="btnRecuperar" id="btnRecuperar" type="button" onClick="recuperar()" />
		<input class="btnAplicarCambios" id="btnAplicarCambios" type="button" onClick="aplicarCambios()" />
		<img id="imgAplicandoCambios" src="/images/loading.gif" style="display:none;" title="Aplicando, aguarde un instante por favor..." />
	</div>
</div>

<div id="divFondo" style="background-color:#00539b; display:none; filter:alpha(opacity=40); height:100%; left:0px; position:absolute; top:0px; width:100%; z-index:99;"></div>

<div id="divAlta" style="background-color:#eee; border:1px solid #00539b; display:none; height:400px; left:50%; margin-left:-300px; position:absolute; top:160px; width:600px; z-index:100;">
	<form action="/modules/extranet/guardar_alta.php" id="formAlta" method="post" name="formAlta" style="margin-left:8px;" target="iframeProcesando">
		<input id="carpeta" name="carpeta" type="hidden" value="<?= $carpetaImagenes?>" />
		<div style="margin-top:8px;">
			<label style="margin-left:25px;"><b>Volanta</b></label>
			<input id="volanta" maxlength="50" name="volanta" style="width:480px;" type="text" value=""></textarea>
		</div>
		<div style="margin-top:4px;">
			<label style="margin-left:37px;"><b>Título</b></label>
			<input id="titulo" maxlength="70" name="titulo" style="width:480px;" type="text" value=""></textarea>
		</div>
		<div style="margin-top:4px;">
			<label style="margin-left:28px; vertical-align:100px;"><b>Cuerpo</b></label>
			<textarea id="cuerpo" maxlength="512" name="cuerpo" style="height:120px; width:480px;" onKeyUp="contarCaracteres()"></textarea>
		</div>
		<div style="margin-top:4px;">
			<label style="vertical-align:100px;"><b>Cuerpo Full</b></label>
			<textarea id="cuerpoFull" name="cuerpoFull" readonly style="cursor:pointer; height:120px; width:480px;" onClick="editarCuerpoFull()"></textarea>
			<img border="0" src="/modules/extranet/images/editar_off.png" style="cursor:pointer; height:64px; left:4px; position:relative; top:-80px; width:64px;" title="Editar Cuerpo Full" onClick="editarCuerpoFull()" />
		</div>
		<div style="margin-top:4px; position:relative; top:-64px;">
			<label style="margin-left:20px;"><b>Posición</b></label>
			<input id="posicion" maxlength="1" name="posicion" style="width:48px;" type="number" value=""></textarea>
		</div>
		<div style="margin-right:32px; position:relative; text-align:right; top:-64px;">
			<input class="btnGuardar" id="btnGuardarAlta" type="submit" value="" onClick="guardarAlta()" />
			<img id="imgGuardandoAlta" src="/images/loading.gif" style="display:none; vertical-align:-2px;" title="Guardando, aguarde un instante por favor..." />
			<img id="imgAltaOk" src="/images/btn_ok.gif" style="display:none; vertical-align:-2px;" title="Proceso exitoso!" />
		</div>
		<img src="/images/cerrar.png" style="cursor:hand; position:absolute; right:0; top:0;" onClick="cerrarVentanaAlta()" />
	</form>
</div>

<div id="divCampo" style="background-color:#eee; border:1px solid #00539b; display:none; height:200px; left:50%; margin-left:-300px; position:absolute; text-align:center; top:320px; width:600px; z-index:100;">
	<br />
	<label><b>Ingrese el nuevo valor</b></label>
	<br />
	<textarea id="texto" name="texto" style="height:120px; width:560px;" onKeyUp="contarCaracteres()"></textarea>
	<br />
	<div style="margin-left:20px; text-align:left;"><span style="font-size:9px;">restan <span id="caracteresRestantes">255</span> caracteres</span></div>
	<div style="margin-right:20px; text-align:right;">
		<input class="btnGuardar" id="btnGuardar" type="button" onClick="guardar()" />
		<img id="imgGuardandoCampo" src="/images/loading.gif" style="display:none;" title="Guardando, aguarde un instante por favor..." />
		<img id="imgCampoOk" src="/images/btn_ok.gif" style="display:none;" title="Proceso exitoso!" />
	</div>
	<img src="/images/cerrar.png" style="cursor:hand; position:absolute; right:0; top:0;" onClick="cerrarVentanaModificacion()" />
</div>

<div id="divCuerpoFull" style="background-color:#eee; border:1px solid #00539b; display:none; height:464px; left:50%; margin-left:-340px; position:absolute; text-align:center; top:160px; width:680px; z-index:100;">
	<br />
	<label style="color:#f00;"><b>EDICIÓN DEL CUERPO DEL ARTÍCULO</b></label>
	<br />
	<br />
	<div style="border:1px solid #00f;; margin-bottom:16px; padding:2px; text-align:left;">
		<form action="/modules/extranet/subir_imagen.php" enctype="multipart/form-data" id="formSubirImagen" method="post" name="formSubirImagen" target="iframeProcesando">
			<input id="carpeta" name="carpeta" type="hidden" value="<?= $carpetaImagenes?>" />
			<label style="margin-left:4px;">Subir imagen</label>
			<input id="imagen" name="imagen" style="width:448px;" type="file" value="" />
			<input class="btnEnviar" id="btnEnviar" name="btnEnviar" style="margin-left:80px; vertical-align:-4px;" type="submit" value="" onClick="enviar()" />
			<img id="imgSubiendoImagen" src="/images/loading.gif" style="display:none; margin-left:88px; vertical-align:-4px;" title="Enviando, aguarde un instante por favor..." />
			<img id="imgSubidaOk" src="/images/btn_ok.gif" style="display:none; margin-left:88px; vertical-align:-4px;" title="Imagen subida exitosamente!" />
		</form>
		<div style="background-color:#ffe1c4; margin-top:8px;">La ruta de las imagenes es la siguiente: http://www.provinciart.com.ar/novedades_imagenes/<?= $carpetaImagenes?>/</div>
	</div>
	<form action="/modules/extranet/guardar_cuerpo_full.php" id="formCuerpoFull" method="post" name="formCuerpoFull" target="iframeProcesando">
		<input id="idArticulo" name="idArticulo" type="hidden" value="-1" />
		<textarea class="ckeditor" id="cuerpoFullEditable" name="cuerpoFullEditable" onKeyUp="contarCaracteres()"></textarea>
		<br />
		<div style="margin-right:20px; text-align:right;">
			<img id="imgGuardandoCuerpoFull" src="/images/loading.gif" style="display:none;" title="Guardando, aguarde un instante por favor..." />
			<input class="btnGuardar" id="btnGuardarCuerpoFull" type="submit" value="" onClick="guardarCuerpoFull()" />
		</div>
	</form>
	<img src="/images/cerrar.png" style="cursor:hand; position:absolute; right:0; top:0;" onClick="cerrarVentanaCuerpoFull()" />
</div>

<script>
	manejarFlechas();
</script>