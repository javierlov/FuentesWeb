<?
if (!hasPermiso(89)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$isAlta = ($_REQUEST["id"] == 0);
$img = "/modules/mantenimiento/images/agregar_grande.png";

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM rrhh.rbr_banners
			WHERE br_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$img = IMAGES_BANNERS_PATH.$_REQUEST["id"]."/".$row["BR_IMAGEN"];
	$img = "/functions/get_image.php?file=".base64_encode($img);
}

require_once("banner_combos.php");
?>
<link href="/modules/mantenimiento/css/abm_banners.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_banners.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_banners/guardar_banner.php" id="formAbmBanner" method="post" name="formAbmBanner" target="iframeProcesando">
	<input id="baja" name="baja" type="hidden" value="<?= ($isAlta)?"f":($row["BR_FECHABAJA"]=="")?"f":"t"?>" />
	<input id="fileImg" name="fileImg" type="hidden" value="" />
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="imagen" id="labelImagen">Imagen</label>
			<a href="/functions/edit_image.php?finalFunction=setImagen&minWidth=620&minHeight=155&mantenerProporcion=t" target="_blank">
				<img id="img" name="img" src="<?= $img?>" title="Clic aquí para cambiar la imagen" />
			</a>
		</div>
		<div class="fila">
			<label for="link">Link</label>
			<input autofocus class="link" id="link" maxlength="255" name="link" type="text" value="<?= ($isAlta)?"":$row["BR_URL"]?>" />
		</div>
		<div class="fila">
			<label for="destino">Destino</label>
			<?= $comboDestino->draw();?>
		</div>
		<div class="fila">
			<label for="posicion">Posición</label>
			<input id="posicion" maxlength="2" name="posicion" type="text" value="<?= ($isAlta)?"":$row["BR_POSICION"]?>" />
		</div>
		<div class="fila">
			<label for="vigenciaDesde">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesde" maxlength="10" name="vigenciaDesde" type="text" value="<?= ($isAlta)?"":$row["BR_FECHAVIGENCIADESDE"]?>" />
			<input class="botonFecha" id="btnVigenciaDesde" name="btnVigenciaDesde" type="button" value="" />
			<label for="vigenciaHasta" id="labelVigenciaHasta">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHasta" maxlength="10" name="vigenciaHasta" type="text" value="<?= ($isAlta)?"":$row["BR_FECHAVIGENCIAHASTA"]?>" />
			<input class="botonFecha" id="btnVigenciaHasta" name="btnVigenciaHasta" type="button" value="" />
		</div>
		<div class="fila">
			<label for="vistaPrevia">Vista Previa</label>
			<input <?= ($isAlta)?"":(($row["BR_VISTAPREVIA"] == "S")?"checked":"")?> id="vistaPrevia" name="vistaPrevia" type="checkbox" value="ok" />
		</div>
		<div class="fila">
			<label for="multiLink">Multi Link</label>
			<input <?= ($isAlta)?"":(($row["BR_MULTILINK"] == "S")?"checked":"")?> id="multiLink" name="multiLink" type="checkbox" value="ok" onClick="clicMultiLink(this)" />
		</div>
		<div class="fila" id="divGrupos">
			<div class="divGruposTitulo">Link para las personas que NO pertenecen a ninguno de los grupos que a continuzación se detalla.</div>
			<div class="fila">
				<label for="linkSinGrupo">Link</label>
				<input class="link" id="linkSinGrupo" maxlength="255" name="linkSinGrupo" type="text" value="<?= ($isAlta)?"":$row["BR_URLSINGRUPO"]?>" />
				<img id="imgAgregarGrupo" src="/images/agregar.png" title="Agregar Grupo" onClick="agregarGrupo()" />
				<br />
				<br />
			</div>
<?
$params = array(":idbanner" => $_REQUEST["id"]);
$sql =
	"SELECT gb_id, gb_link
		 FROM rrhh.rgb_gruposbanners
		WHERE gb_idbanner = :idbanner
			AND gb_fechabaja IS NULL
 ORDER BY gb_id";
$stmt = DBExecSql($conn, $sql, $params);
for ($i=1 ;$i<10; $i++) {
	$row = DBGetQuery($stmt);
	if (!$row) {
		$row["GB_ID"] = -1;
		$row["GB_LINK"] = "";
	}

	$sql =
		"SELECT se_id id, se_nombre detalle
			 FROM use_usuarios
			WHERE se_fechabaja IS NULL
				AND se_id IN(SELECT ug_idusuario
											 FROM rrhh.rug_usuariosxgruposbanners
											WHERE ug_idgrupobanner = :idgrupobanner)
				AND se_usuariogenerico = 'N'
	 ORDER BY 2";
	$comboUsuariosGrupo = new Combo($sql, "usuariosGrupo".$i."[]");
	$comboUsuariosGrupo->addParam(":idgrupobanner", $row["GB_ID"]);
	$comboUsuariosGrupo->setAddFirstItem(false);
	$comboUsuariosGrupo->setClass("selectMultiple");
	$comboUsuariosGrupo->setMultiple(true);

	$sql =
		"SELECT se_id id, se_nombre detalle
			 FROM use_usuarios
			WHERE se_fechabaja IS NULL
				AND se_id NOT IN(SELECT ug_idusuario
													 FROM rrhh.rug_usuariosxgruposbanners
													WHERE ug_idgrupobanner = :idgrupobanner)
				AND se_usuariogenerico = 'N'
	 ORDER BY 2";
	$comboUsuariosSinGrupo = new Combo($sql, "usuariosSinGrupo".$i);
	$comboUsuariosSinGrupo->addParam(":idgrupobanner", $row["GB_ID"]);
	$comboUsuariosSinGrupo->setAddFirstItem(false);
	$comboUsuariosSinGrupo->setClass("selectMultiple");
	$comboUsuariosSinGrupo->setMultiple(true);
?>
			<div class="divGrupo" id="divGrupo<?= $i?>" style="background-color:#<?= (($i % 2) == 0)?"bbb":"eee"?>; display:<?= ($row["GB_ID"] == -1)?"none":"block"?>">
				<input id="bajaGrupo<?= $i?>" name="bajaGrupo<?= $i?>" type="hidden" value="<?= ($row["GB_ID"] == -1)?"t":"f"?>" />
				<input id="idGrupo_<?= $i?>" name="idGrupo_<?= $i?>" type="hidden" value="<?= ($isAlta)?-1:$row["GB_ID"]?>" />
				<div class="divGruposTitulo" id="divGruposTitulo<?= $i?>">GRUPO <?= $i?></div>
				<img class="imgEliminar" src="/images/cerrar.png" title="Eliminar Grupo <?= $i?>" onClick="eliminarGrupo(<?= $i?>)" />
				<div class="fila">
					<label for="linkGrupo<?= $i?>">Link</label>
					<input class="link" id="linkGrupo<?= $i?>" maxlength="255" name="linkGrupo<?= $i?>" type="text" value="<?= ($isAlta)?"":$row["GB_LINK"]?>" />
				</div>
				<div class="divUsuariosSinGrupo">
					<label class="labelCampos">Usuarios SIN grupo</label>
					<br />
					<?= $comboUsuariosSinGrupo->draw();?>
				</div>
				<div id="divBotonesUsuarios">
					<input class="botonPasaje" id="btnAgregarTodosGrupo<?= $i?>" name="btnAgregarTodosGrupo<?= $i?>" title="Agregar a Todos" type="button" value=">>" onClick="agregarTodos(document.getElementById('usuariosSinGrupo<?= $i?>'), document.getElementById('usuariosGrupo<?= $i?>[]'))" />
					<br /><br />
					<input class="botonPasaje" id="btnAgregarGrupo<?= $i?>" name="btnAgregarGrupo<?= $i?>" title="Agregar" type="button" value=">" onClick="agregarUsuarios(document.getElementById('usuariosSinGrupo<?= $i?>'), document.getElementById('usuariosGrupo<?= $i?>[]'))" />
					<br /><br />
					<input class="botonPasaje" id="btnQuitarGrupo<?= $i?>" name="btnQuitarGrupo<?= $i?>" title="Quitar" type="button" value="<" onClick="agregarUsuarios(document.getElementById('usuariosGrupo<?= $i?>[]'), document.getElementById('usuariosSinGrupo<?= $i?>'))" />
					<br /><br />
					<input class="botonPasaje" id="btnQuitarTodosGrupo<?= $i?>" name="btnQuitarTodosGrupo<?= $i?>" title="Quitar a Todos" type="button" value="<<" onClick="agregarTodos(document.getElementById('usuariosGrupo<?= $i?>[]'), document.getElementById('usuariosSinGrupo<?= $i?>'))" />
					<br /><br />
				</div>
				<div class="divUsuariosConGrupo">
					<label class="labelCampos">Usuarios en el GRUPO <?= $i?></label>
					<br />
					<?= $comboUsuariosGrupo->draw();?>
				</div>
				<div id="divNada"></div>
			</div>
<?
}
?>










		</div>
	</div>
	<div id="divBotones">
<?
if (!$isAlta) {
?>
		<input id="btnDarBaja" name="btnDarBaja" type="button" onClick="darBaja(<?= $_REQUEST["id"]?>)" />
<?
}
?>
		<input id="btnGuardar" name="btnGuardar" type="button" onClick="guardar()" />
		<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde unos segundos por favor..." />
		<input id="btnCancelar" name="btnCancelar" type="button" onClick="cancelar()" />
	</div>
	<div id="divErroresForm">
		<img src="/images/atencion.png" />
		<span>No es posible continuar mientras no se corrijan los siguientes errores:</span>
		<br />
		<br />
		<span id="errores"></span>
		<input id="foco" name="foco" readonly type="checkbox" />
	</div>
</form>

<script type="text/javascript">
	Calendar.setup ({
		inputField: "vigenciaDesde",
		ifFormat  : "%d/%m/%Y",
		button    : "btnVigenciaDesde"
	});
	Calendar.setup ({
		inputField: "vigenciaHasta",
		ifFormat  : "%d/%m/%Y",
		button    : "btnVigenciaHasta"
	});

	clicMultiLink(document.getElementById('multiLink'));
</script>