<?
if (!hasPermiso(2)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT *
		 FROM use_usuarios
		WHERE se_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

if ($row["SE_FECHABAJA"] != "") {
	showErrorIntranet("", "No se puede modificar un usuario dado de baja.");
	return;
}

if ($row["SE_FOTO"] == "")
	$foto = "/modules/mantenimiento/images/agregar_grande.png";
else {
	$foto = IMAGES_FOTOS_PATH.$row["SE_FOTO"];
	$foto = "/functions/get_image.php?file=".base64_encode($foto);
}

require_once("usuario_combos.php");
?>
<link href="/modules/mantenimiento/css/abm_usuarios.css" rel="stylesheet" type="text/css" />
<script src="/js/constants.js" type="text/javascript"></script>
<script src="/modules/mantenimiento/js/abm_usuarios.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_usuarios/guardar_usuario.php" enctype="multipart/form-data" id="formAbmUsuario" method="post" name="formAbmUsuario" target="iframeProcesando">
	<input id="fileFoto" name="fileFoto" type="hidden" value="" />
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<input id="usuario" name="usuario" type="hidden" value="<?= $row["SE_USUARIO"]?>" />
	<div>
		<div class="fila">
			<label for="usuarioART">Usuario ART</label>
			<input id="usuarioART" name="usuarioART" readonly type="text" value="<?= $row["SE_USUARIO"]?>" />
		</div>
		<div class="fila">
			<label for="nombre">Nombre</label>
			<input id="nombre" name="nombre" readonly type="text" value="<?= $row["SE_NOMBRE"]?>" />
		</div>
		<div class="fila">
			<label for="interno">Interno</label>
			<input autofocus id="interno" maxlength="50" name="interno" type="text" value="<?= $row["SE_INTERNO"]?>" />
		</div>
		<div class="fila">
			<label for="fechaNacimiento">Fecha Nacimiento</label>
			<input class="fecha" id="fechaNacimiento" maxlength="10" name="fechaNacimiento" type="text" value="<?= $row["SE_FECHACUMPLE"]?>" />
			<input class="botonFecha" id="btnFechaNacimiento" name="btnFechaNacimiento" type="button" value="" />
		</div>
		<div class="fila">
			<label for="sector">Sector</label>
			<?= $comboSector->draw();?>
		</div>
		<div class="fila">
			<label for="cargo">Cargo</label>
			<?= $comboCargo->draw();?>
		</div>
		<div class="fila">
			<label for="delegacion">Delegación</label>
			<?= $comboDelegacion->draw();?>
		</div>
		<div class="fila" id="divEdificio">
			<label for="edificio">Edificio</label>
			<?= $comboEdificio->draw();?>
		</div>
		<div class="fila" id="divPiso">
			<label for="piso">Piso</label>
			<input id="piso" maxlength="2" name="piso" type="text" value="<?= $row["SE_PISO"]?>" />
		</div>
		<div class="fila">
			<label for="codigoInternoRRHH">Código Interno RRHH</label>
			<input id="codigoInternoRRHH" maxlength="8" name="codigoInternoRRHH" type="text" value="<?= $row["SE_LEGAJO"]?>" />
		</div>
		<div class="fila">
			<label for="legajoRRHH">Legajo RRHH</label>
			<input id="legajoRRHH" maxlength="8" name="legajoRRHH" type="text" value="<?= $row["SE_LEGAJORRHH"]?>" />
		</div>
		<div class="fila">
			<label for="cuil">C.U.I.L.</label>
			<input id="cuil" maxlength="13" name="cuil" type="text" value="<?= $row["SE_CUIL"]?>" />
		</div>
		<div class="fila">
			<label for="relacionLaboral">Relación Laboral</label>
			<?= $comboRelacionLaboral->draw();?>
		</div>
		<div class="fila">
			<label for="respondeA">Responde A</label>
			<?= $comboRespondeA->draw();?>
		</div>
		<div class="fila">
			<label for="mostrarEnIntranet">Mostrar en Intranet</label>
			<input <?= (($row["SE_MOSTRARENINTRANET"] == "S")?"checked":"")?> id="mostrarEnIntranet" name="mostrarEnIntranet" type="checkbox" value="ok" />
		</div>
		<div class="fila" id="divHorarioAtencion">
			<label for="horarioAtencion">Horario de Atención</label>
			<input id="horarioAtencion" maxlength="50" name="horarioAtencion" type="text" value="<?= $row["SE_HORARIOATENCION"]?>" />
		</div>
		<div class="fila">
			<label for="imgFoto" id="labelFoto">Foto</label>
			<a href="/functions/edit_image.php?finalFunction=setFoto&minWidth=120&minHeight=120" target="_blank">
				<img id="imgFoto" name="imgFoto" src="<?= $foto?>" title="Clic aquí para cambiar la foto" />
			</a>
		</div>
	</div>
	<div id="divBotones">
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

<div id="divFondo"></div>

<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaNacimiento",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaNacimiento"
	});

	cambiarDelegacion('<?= $row["SE_DELEGACION"]?>');
</script>