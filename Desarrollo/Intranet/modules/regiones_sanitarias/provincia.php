<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$params = array(":codigo" => $_REQUEST["provincia"]);
$sql =
	"SELECT pv_descripcion
		 FROM cpv_provincias
		WHERE pv_fechabaja IS NULL
			AND pv_codigo = :codigo";
$provincia = ValorSql($sql, "", $params);

switch ($_REQUEST["provincia"]) {
	case 8:
		$referencias = array("018", "031", "019", "074", "002", "075", "003", "013", "023", "011");
		break;
	default:
		$referencias = array("018", "031", "019", "074", "002", "075", "003", "013", "023", "011");
}
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
		<title>Provincias</title>
		<style type="text/css"> 
			body {scrollbar-3dlight-color:#eee; scrollbar-arrow-color:#eee; scrollbar-darkshadow-color:#fff; scrollbar-face-color:#aaa; scrollbar-highlight-color:#aaa; scrollbar-shadow-color:#aaa; scrollbar-track-color:#e3e3e3;}
		</style>
		<script type="text/javascript" src="/modules/regiones_sanitarias/js/regiones_sanitarias.js?rnd=<?= time()?>"></script>
		<script type="text/javascript" src="/js/popup/dhtmlwindow.js"></script>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<script>
			divWin = null;
			top.frames['encabezado'].document.getElementById('imgHeader').src = '/modules/regiones_sanitarias/imagenes/provincia_<?= $_REQUEST["provincia"]?>_titulo<?= ($_SESSION["RegionesSanitariasEditar"])?"ME":""?>.gif';
		</script>
	</head>

	<body bottommargin="2" leftmargin="0" rightmargin="0" topmargin="0">
		<iframe id="iframePrestadores" name="iframePrestadores" src="" style="display:none;"></iframe>
		<iframe id="iframeTitle" name="iframeTitle" src="" style="display:none;"></iframe>
		<input id="cpSeleccionado" name="cpSeleccionado" type="hidden" value="" />
		<input id="modoEdicion" name="modoEdicion" type="hidden" value="<?= ($_SESSION["RegionesSanitariasEditar"])?"t":"f"?>" />
		<div id="divMapa" style="margin-top:40px; left:0px; margin-left:30%; position:relative; top:0px; width:50%;">
			<div cp="0" id="idMenuBaja" style="background-color:#e9af25; border:1px solid; color:#000; cursor:hand; display:none; left:0px; padding:4px; position:absolute; top:0px; z-index:100;" onClick="eliminarCoordenada(this.cp)" onMouseOut="ocultarMenuBaja()" onMouseOver="document.getElementById('idMenuBaja').style.display='inline';">Eliminar</div>
			<map name="map">
<? include($_SERVER["DOCUMENT_ROOT"]."/modules/regiones_sanitarias/map_provincias/provincia_".$_REQUEST["provincia"].".php");?>
			</map>
			<img border="0" alt="<?= $provincia?>" id="imgMapa" src="/modules/regiones_sanitarias/imagenes/provincia_<?= $_REQUEST["provincia"]?>.gif" usemap="#map" onDblClick="dblClickMapa()" />
<?
if ($_SESSION["RegionesSanitariasEditar"]) {
	$params = array(":provincia" => $_REQUEST["provincia"]);
	$sql =
		"SELECT DISTINCT cp_codigo, ra_coordenadax, ra_coordenaday
			 FROM comunes.cra_coordregionessanitarias, ccp_codigopostal
			WHERE ra_codigopostal = cp_codigo
				AND ra_fechabaja IS NULL
				AND cp_fechabaja IS NULL
				AND cp_provincia = :provincia";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt)) {
?>
		<img id="img<?= $row["CP_CODIGO"]?>" src="/modules/regiones_sanitarias/imagenes/coordenada.png" style="cursor:hand; position:absolute; left:<?= $row["RA_COORDENADAX"]?>px; top:<?= $row["RA_COORDENADAY"]?>px;" onDblClick="dblClickImg(<?= $row["RA_COORDENADAX"]?>, <?= $row["RA_COORDENADAY"]?>)" onMouseOut="ocultarMenuBaja()" onMouseOver="mostrarMenuBaja(this, '<?= $row["CP_CODIGO"]?>')" />
<?
	}
}
?>
		</div>
		<div align="center" style="left:50%; margin-left:-380px; position:absolute; top:3px; width:760px;">
<?
if (!$_SESSION["RegionesSanitariasEditar"]) {
?>
			<label style="color:#807f84; font-family:Arial; font-size:10pt; font-weight:700;">TIPOS DE PRESTADOR</label>
			<select style="font-family: Arial; font-size: 9pt; color: #807f84; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF;"  id="tipoPrestador" onChange="cargarIconos(<?= $_REQUEST["provincia"]?>, this.value, 'p')">
				<option value="-1">- Seleccione un Tipo de Prestador -</option>
<?
	for ($i = 0; $i <= count($referencias) - 1; $i++)
		$referencias[$i] = "'".$referencias[$i]."'";
	$referencias =  implode(",", $referencias);

	$sql =
		"SELECT tp_descripcion, tp_codigo
			 FROM mtp_tipoprestador
			WHERE tp_codigo IN(".$referencias.")
	 ORDER BY 1";
	$stmt = DBExecSql($conn, $sql);
	while ($row = DBGetQuery($stmt)) {
?>
		<option value="<?= $row["TP_CODIGO"]?>"><?= $row["TP_DESCRIPCION"]?></option>
<?
	}
?>
			</select>
<?
}
?>
			<div style="left:696px; position:absolute; top:0px;">
				<a target="_top" href="/regiones-sanitarias"><img border="0" src="/modules/regiones_sanitarias/imagenes/boton_volver.gif" /></a>
			</div>
		</div>
<?
if ($_SESSION["RegionesSanitariasEditar"]) {
?>
		<script>
			mostrarGrilla(<?= $_REQUEST["provincia"]?>, 'p');
		</script>
<?
}
?>
		<div id="prestadoresWindow" name="prestadoresWindow" style="display:none"></div>
		<script>
			if (document.getElementById('tipoPrestador') != null)
				document.getElementById('tipoPrestador').focus();
		</script>
	</body>
</html>