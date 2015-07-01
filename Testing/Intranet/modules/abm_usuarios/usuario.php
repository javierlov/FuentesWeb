<?
$modulePath = "/modules/abm_usuarios/";
?>
<script>
	showTitle(true, 'ABM DE USUARIOS');
</script>
<script language="JavaScript" src="/js/constants.js"></script>
<script language="JavaScript" src="<?= $modulePath?>js/usuario.js?f=<?= date("YmdHis")?>"></script>
<iframe id="iframeUsuario" name="iframeUsuario" src="" style="display:none;"></iframe>

<div style="background-color:#ffa4a4; margin-bottom:8px;">
	<form action="<?= $modulePath?>buscar_legajo.php" id="formBuscarPorLegajo" method="post" name="formBuscarPorLegajo" target="iframeUsuario">
		<label>Legajo RRHH</label>
		<input id="legajo" name="legajo" type="text" value="" />
		<input type="submit" value="Buscar" />
		<span id="resultadoLegajo" name="resultadoLegajo" style="font-weight:bold; margin-left:16px;"></span>
	</form>
</div>

<div style="background-color:#c0c0c0; padding-left:4px; padding-bottom:4px; padding-top:4px;">
	<label class="FormLabelBlanco">Seleccionar Usuario</label>
	<select class="Combo" id="Usuario" name="Usuario" style="margin-left:8px;"></select>
	<input class="BotonBlanco" id="btnOk" name="btnOk" style="margin-left:8px;" type="button" value="SELECCIONAR" onClick="SeleccionarUsuario(document.getElementById('Usuario').value)">
</div>

<form action="<?= $modulePath?>procesar_usuario.php" enctype="multipart/form-data" id="formUsuario" method="post" name="formUsuario" target="iframeUsuario" onSubmit="return ValidarFormUsuario(formUsuario)">
	<input id="Id" name="Id" type="hidden" value="-1" />
	<input id="UserName" name="UserName" type="hidden" value="" />
	<input id="NombreFoto" name="NombreFoto" type="hidden" value="" />
	<input id="EjeX" name="EjeX" type="hidden" value="" />
	<input id="EjeY" name="EjeY" type="hidden" value="" />
	<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="1000000" />
	<div align="center" id="divProcesando" style="display:none; margin-top:16px;"><div><img src="/images/loading_grande.gif" title="Cargando datos..." /></div></div>
	<div id="datos" style="display:none;">
		<div style="margin-top:8px;">
			<img border="0" src="<?= $modulePath?>images/usuario.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzulCabecera">Usuario</label>
			<span class="FormLabelGris" id="Nombre" name="Nombre"></span>
		</div>
		<div align="center" style="margin-bottom:16px; margin-top:4px;"><hr style="color:#c0c0c0;"></div>
		<div>
			<img border="0" src="<?= $modulePath?>images/tel.jpg" style="vertical-align:-5px;" />
			<label class="FormLabelAzul" style="margin-left:12px;">Interno</label>
			<input class="FormInputText" id="Interno" maxlength="50" name="Interno" style="margin-left:85px; width:456px;" type="text" />
		</div>
		<div>
			<img border="0" src="<?= $modulePath?>images/fecha_cumple.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Fecha de Nacimiento</label>
			<input class="FormInputTextDate" id="FechaNacimiento" maxlength="10" name="FechaNacimiento" style="margin-left:6px; width:80px;" title="Fecha de Nacimiento" type="text" validarFecha="true" />
			<input class="BotonFecha" id="btnFechaNacimiento" name="btnFechaNacimiento" style="vertical-align:-6px;" type="button" value="" />
		</div>
		<div>
			<img border="0" src="<?= $modulePath?>images/sector.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Sector</label>
			<select class="Combo" id="Sector" name="Sector" style="margin-left:89px; width:592px"></select>
		</div>
		<div>
			<img border="0" src="<?= $modulePath?>images/cargo.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Cargo</label>
			<select class="Combo" id="Cargo" name="Cargo" style="margin-left:92px;"></select>
		</div>
		<div>
			<img border="0" src="<?= $modulePath?>images/delegacion.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Delegación</label>
			<select class="Combo" id="Delegacion" name="Delegacion" style="margin-left:62px;" onChange="CambiaDelegacion(document)"></select>
		</div>
		<div id="divEdificio" name="divEdificio" style="display:block;">
			<img border="0" src="<?= $modulePath?>images/edificio.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Edificio</label>
			<select class="Combo" id="Edificio" name="Edificio" style="margin-left:84px;" onChange="cambiaEdificio(document)"></select>
		</div>
		<div id="divPiso" name="divPiso" style="display:block;">
			<img border="0" src="<?= $modulePath?>images/posicion.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Piso</label>
			<input class="FormInputText" id="Piso" maxlength="2" name="Piso" style="margin-left:101px; width:24px;" title="Piso" type="text" validarEntero="true" onKeyUp="CambiaPiso(document)" />
		</div>
		<div id="divSeparadorPiso" name="divSeparadorPiso"></div>
		<div>
			<img border="0" src="<?= $modulePath?>images/legajo.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Código Interno RR.HH.</label>
			<input class="FormInputText" id="Legajo" maxlength="8" name="Legajo" style="width:88px;" title="Código Interno RR.HH." type="text" validarEntero="true" />
		</div>
		<div>
			<img border="0" src="<?= $modulePath?>images/legajoRRHH.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Legajo RR.HH.</label>
			<input class="FormInputText" id="LegajoRRHH" maxlength="8" name="LegajoRRHH" style="margin-left:43px; width:88px;" title="LegajoRRHH" type="text" validar="true" validarEntero="true" />
		</div>
		<div>
			<img border="0" src="<?= $modulePath?>images/relacion_laboral.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Relación Laboral</label>
			<select class="Combo" id="RelacionLaboral" name="RelacionLaboral" style="margin-left:32px;"></select>
		</div>
		<div>
			<img border="0" src="<?= $modulePath?>images/responde_a.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Responde a</label>
			<select class="Combo" id="RespondeA" name="RespondeA" style="margin-left:59px;"></select>
		</div>
		<div id="divHorarioAtencion" name="divHorarioAtencion" style="display:block;">
			<img border="0" src="<?= $modulePath?>images/horario_atencion.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Horario de Atención</label>
			<input class="FormInputText" id="HorarioAtencion" maxlength="50" name="HorarioAtencion" style="margin-left:14px; width:456px;" type="text" />
		</div>
		<div id="divSeparadorHorarioAtencion" name="divSeparadorHorarioAtencion" style="display:block;"></div>
		<div>
			<img border="0" src="<?= $modulePath?>images/foto.jpg" style="vertical-align:-6px;" />
			<label class="FormLabelAzul" style="margin-left:8px;">Foto</label>
			<span id="spanFoto" style="display:none;">
				<a href="#" onClick="MostrarFoto()"><img alt="Ver Foto" border="1" id="imgFoto" name="imgFoto" src="/images/loading.gif" style="height:24px; vertical-align:-8px; width:24px;" /></a>
			</span>
			<input class="FormInputText" id="Foto" name="Foto" style="margin-left:99px; width:467px;" title="Foto" type="file" validarImagen="true" />
		</div>
	</div>
	<div id="divMapa" style="display:none;">
		<img border="0" src="<?= $modulePath?>images/mapa.jpg" style="vertical-align:-6px;" />
		<label class="FormLabelAzul" style="margin-left:8px;">Mapa</label>
		<div align="center" style="height:100%; left:0px; position:relative; top:0px; width:100%;">
			<img id="Mapa" name="Mapa" src="" style="cursor:hand" onClick="GetCoordenadasMapa()" onError="NoExisteMapa()" />
<!--			<img border="0" id="Coordenada" src="<?= $modulePath?>images/dentro_del_edificio.gif" style="height:8px; left:0px; position:relative; top:0px; width:8px;" />-->
		</div>
		<div id="divSeparadorMapa" name="divSeparadorMapa" style="margin-bottom:8px; margin-top:8px;"><hr color="#c0c0c0"></div>
	</div>
	<div align="center" id="divBtnGuardar" style="display:none; margin-top:16px;">
		<input class="BotonBlanco" id="btnGuardar" name="btnGuardar" style="background-color:#ccc; color:#000; font-weight:bold;" type="submit" value="GUARDAR" />
		<span class="Mensaje" id="spanMensaje" name="spanMensaje" style="display:none;" onMouseMove="OcultarMensajeOk()">Los datos se guardaron correctamente.</span>
	</div>
</form>

<div id="msgOk" name="msgOk" style="display:none; margin-top:8px;">
	<p align="center" style="font-size:14px; font-weight:bold;">Los datos se guardaron correctamente.</p>
</div>
<script>
/*
	document.getElementById('Mapa').onLoad = function() {
		SetCoordenadaPuesto(document, document.getElementById('EjeX').value, document.getElementById('EjeY').value);
	}

	document.getElementById('content').onScroll = function() {
		SetCoordenadaPuesto(document, document.getElementById('EjeX').value,  document.getElementById('EjeY').value);
	}
*/
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "Usuario";
$RCparams = array();
$RCquery =
	"SELECT se_id id, se_nombre detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
UNION ALL
	 SELECT se_id, se_nombre
		 FROM use_usuarios
		WHERE se_usuario LIKE 'RECEPCION%'
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "Sector";
$RCparams = array();
$RCquery =
	"SELECT se1.se_id id, se1.se_descripcion || (SELECT DECODE(se1.se_descripcion, se2.se_descripcion, NULL, ' (' || se2.se_descripcion || (SELECT DECODE(se2.se_descripcion, se3.se_descripcion, NULL, ' - ' || se3.se_descripcion)
																																																																						FROM computos.cse_sector se3
																																																																					 WHERE se3.se_nivel = 2
																																																																						 AND se3.se_id = se2.se_idsectorpadre) || ')')
																								 FROM computos.cse_sector se2
																								WHERE se2.se_nivel = 3
																									AND se2.se_fechabaja IS NULL
																									AND se2.se_id = se1.se_idsectorpadre) || DECODE(se1.se_fechabaja, NULL, '', ' -BAJA- ') detalle
		 FROM computos.cse_sector se1
		WHERE se1.se_nivel = 4
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "Cargo";
$RCparams = array();
$RCquery = 
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'USCAR'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "Delegacion";
$RCparams = array();
$RCquery = 
	"SELECT el_id id, el_nombre detalle
		 FROM del_delegacion
		WHERE el_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "Edificio";
$RCparams = array();
$RCquery = 
	"SELECT es_id id, es_descripcion || ' - ' || es_calle || ' ' || es_numero detalle
		 FROM art.des_delegacionsede
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "RelacionLaboral";
$RCparams = array();
$RCquery = 
	"SELECT ru_id id, ru_detalle detalle
		 FROM comunes.cru_relacionlaboralusuario
		WHERE ru_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "RespondeA";
$RCparams = array();
$RCquery = 
	"SELECT se_usuario id, UPPER(SUBSTR(se_usuario, 1, 2)) || LOWER(SUBSTR(se_usuario, 3, 1000)) detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$RCselectedItem = -1;
FillCombo();
?>

	Calendar.setup (
		{
			inputField: "FechaNacimiento",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaNacimiento"
		}
	);

	document.getElementById('Usuario').focus();
</script>