<!--ZOOMSTOP-->
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


$params = array(":usuario" => getWindowsLoginName(true));
$sql =
	"SELECT 1
		 FROM use_usuarios
		WHERE se_usuario IN ('ALAPACO', 'FPEREZ')
			AND se_usuario = :usuario";
$isSegInfo = existeSql($sql, $params);

$today = stringToUpper(getDayName(date("N")))." ".date("j")." DE ".stringToUpper(getMonthName(date("n")))." DE ".date("Y");
?>
<div>
	<div id="divImagenCabecera"><img id="imgImagenCabecera" src="/images/header/header_logo_1.jpg" usemap="#inicio" /></div>
<?
if (isset($_REQUEST["vistaprevia"])) {
?>
	<div id="divVistaPrevia">
		<div><img src="/modules/portada/images/vista_previa.png" title="Vista Previa" /></div>
		<div><a href="javascript:limpiarVistaPrevia()"><img src="/modules/portada/images/limpiar_vista_previa.png" title="Limpiar Vista Previa" /></a></div>
	</div>
<?
}

if ($isSegInfo) {
?>
	<div id="divPermisos">
		<img src="/images/mostrar_permisos.png" title="Ir al Módulo de Permisos" onClick="showPermisosWindow(<?= $pageid?>)" />
	</div>
<?
}
?>
	<div id="divClima">
		Pronóstico del tiempo en Buenos Aires
		<div id="TT_tyjgrxtBddjBAc8AKfqjjDzzDtlAMdjFGyiyizpYW65">
			<h2><a href="http://www.tutiempo.net">Predicción meteorológica</a></h2>
			<a href="http://www.tutiempo.net/tiempo/Buenos_Aires_Observatorio/SABA.htm">El tiempo en Buenos Aires</a>
		</div>
		<script type="text/javascript" src="http://www.tutiempo.net/widget/eltiempo_tyjgrxtBddjBAc8AKfqjjDzzDtlAMdjFGyiyizpYW65"></script>
	</div>
</div>
<div id="divBusquedaGeneral">
	<span id="spanHeaderFecha"><?= $today?></span>
	<img id="imgBusquedaGeneral" src="/images/buscar.png" title="Buscar" onClick="buscarEnTodaLaIntranet(false)" />
	<input id="busquedaGeneral" name="busquedaGeneral" placeholder="Buscar en toda la intranet" value="<?= (isset($_REQUEST["busquedaGeneral"]))?$_REQUEST["busquedaGeneral"]:""?>" onKeyPress="buscarEnTodaLaIntranet(true);" />
	<div id="divNada"></div>
</div>

<map id="inicio" name="inicio">
	<area coords="44, 24, 228, 84" href="/" shape="rect" title="Ir al inicio" />
</map>

<script>
	document.getElementById('TTF_tyjgrxtBddjBAc8AKfqjjDzzDtlAMdjFGyiyizpYW65').style.display = 'block';
</script>
<!--ZOOMRESTART-->