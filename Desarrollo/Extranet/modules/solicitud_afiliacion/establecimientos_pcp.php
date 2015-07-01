<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idModulo"]);

$showProcessMsg = false;

$domicilio = "";
if (isset($_REQUEST["domicilio"]))
	$domicilio = $_REQUEST["domicilio"];

$numero = "";
if (isset($_REQUEST["numero"]))
	$numero = $_REQUEST["numero"];

$ob = "4";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$sb = true;
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "F")
		$sb = false;
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<style type="text/css">
			.input {background-color:#fff; border-bottom:1px solid #808080; border-left:1px solid #fff; border-right:1px solid #fff; border-top:1px solid #fff; color:#000080; font-size:9;
							padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; text-transform:uppercase;}
		</style>
		<script src="/js/functions.js" type="text/javascript"></script>
	</head>
	<body style="margin:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/solicitud_afiliacion/establecimientos_pcp.php" id="formEstablecimientos" method="get" name="formEstablecimientos">
			<input id="idModulo" name="idModulo" type="hidden" value="<?= $_REQUEST["idModulo"]?>">
			<input id="idSolicitud" name="idSolicitud" type="hidden" value="<?= $_REQUEST["idSolicitud"]?>">
			<div align="left" style="color:#211e1e; font-family:Trebuchet MS; font-size:8pt; margin-bottom:16px; margin-left:8px; margin-top:8px;">
				<label for="numero">Número</label>
				<input id="numero" name="numero" style="width:48px;" type="text" value="<?= $numero?>" />
				<label for="domicilio" style="margin-left:16px;">Domicilio</label>
				<input id="domicilio" name="domicilio" style="width:200px;" type="text" value="<?= $domicilio?>" />
				<input class="btnBuscar" style="margin-left:24px; vertical-align:-3px;" type="submit" value="" />
			</div>
			<div align="left" style="margin-bottom:4px;">
				<input class="btnAgregarLugarTrabajo" style="margin-left:2px;" type="button" value="" onClick="document.getElementById('iframeProcesando').src = '/modules/solicitud_afiliacion/abrir_ventana_establecimiento_pcp.php?idModulo=<?= $_REQUEST["idModulo"]?>&idSolicitud=<?= $_REQUEST["idSolicitud"]?>&id=-1'">
				<p id="leyendaReactivar">
					<span style="font-family:Trebuchet MS; font-size:8pt; font-weight:700;">Hemos detectado en nuestros registros los siguientes establecimientos, si alguno de éstos aún permanece en actividad le solicitamos lo reactive, utilizando el botón ubicado en la columna “R” de la grilla.</span>
				</p>
			</div>
			<div align="center" id="divContent" name="divContent">
<?
$params = array(":idsolicitud" => $_REQUEST["idSolicitud"]);
$where = "";

if ($domicilio != "") {
	$params[":domicilio"] = "%".$domicilio."%";
	$where.= " AND UPPER(art.utiles.armar_domicilio(lt_calle, lt_numero, lt_piso, lt_departamento, NULL) || art.utiles.armar_localidad(lt_cpostal, NULL, lt_localidad, lt_provincia)) LIKE UPPER(:domicilio)";
}

if ($numero != "") {
	$params[":numero"] = "%".$numero."%";
	$where.= " AND UPPER(lt_nrolugartrabajo) LIKE UPPER(:numero)";
}

if ($_REQUEST["idSolicitud"] == -1) {
	$params[":usualta"] = "W_".substr($_SESSION["usuario"], 0, 18);
	$sql =
		"SELECT ¿lt_id?,
						¿lt_nrolugartrabajo?,
						art.utiles.armar_domicilio(lt_calle, lt_numero, lt_piso, lt_departamento, NULL) || art.utiles.armar_localidad(lt_cpostal, NULL, lt_localidad, lt_provincia) ¿domicilio?,
						lt_fechabaja ¿baja?
			 FROM afi.alt_lugartrabajo_pcp
			WHERE lt_usualta = :usualta
				AND lt_idsolicitud = :idsolicitud _EXC1_";
}
else {
	$sql =
		"SELECT ¿lt_id?,
						¿lt_nrolugartrabajo?,
						art.utiles.armar_domicilio(lt_calle, lt_numero, lt_piso, lt_departamento, NULL) || art.utiles.armar_localidad(lt_cpostal, NULL, lt_localidad, lt_provincia) ¿domicilio?,
						lt_fechabaja ¿baja?
			 FROM afi.alt_lugartrabajo_pcp
			WHERE lt_fechabaja IS NULL
				AND lt_idsolicitud = :idsolicitud _EXC1_";
}
$grilla = new Grid(10, 5);
$grilla->addColumn(new Column("E", 0, true, false, -1, "btnEditar", "/modules/solicitud_afiliacion/abrir_ventana_establecimiento_pcp.php?idModulo=".$_REQUEST["idModulo"]."&idSolicitud=".$_REQUEST["idSolicitud"], "", -1, true, -1, "Editar"));
$grilla->addColumn(new Column("Número"));
$grilla->addColumn(new Column("Domicilio"));
$grilla->addColumn(new Column("", -1, false, true));
$grilla->setBaja(4, $sb, true);
$grilla->setColsSeparator(true);
$grilla->setExtraConditions(array($where));
$grilla->setFieldBaja("lt_fechabaja");
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRefreshIntoWindow(true);
$grilla->setRowsSeparator(true);
$grilla->setRowsSeparatorColor("#c0c0c0");
$grilla->setShowTotalRegistros(false);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->setUseTmpIframe(true);
$grilla->Draw();

$mostrarLeyenda = ((substr($_REQUEST["idModulo"], 0, 1) == "R") and ($grilla->recordCount() > 0));
?>
			</div>
			<div align="center" id="divProcesando" name="divProcesando" style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
			<input id="foco" name="foco" readonly style="height:1px; width:1px;" type="checkbox" />
		</form>
	</body>
	<script type="text/javascript">
		function CopyContent() {
			try {
				window.parent.document.getElementById('divContentGrid').innerHTML = document.getElementById('divContentGrid').innerHTML;
			}
			catch(err) {
				//
			}
		}

		CopyContent();

		document.getElementById('leyendaReactivar').style.display = '<?= ($mostrarLeyenda)?"block":"none"?>';

		document.getElementById('foco').style.display = 'block';
		document.getElementById('foco').focus();
		document.getElementById('foco').style.display = 'none';
	</script>
</html>