<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idModulo"]);

$showProcessMsg = false;

$idTablaPadre = $_REQUEST["idTablaPadre"];
$tablaTel = $_REQUEST["tablaTel"];

$ob = "2";
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
			.input {
				background-color: #fff;
				border-bottom: 1px solid #808080;
				border-left: 1px solid #fff;
				border-right: 1px solid #fff;
				border-top: 1px solid #fff;
				color: #000080;
				font-size: 9;
				padding-bottom: 1px;
				padding-left: 4px;
				padding-right: 4px;
				padding-top: 1px;
				text-transform: uppercase;
			}
		</style>
		<script src="/js/functions.js" type="text/javascript"></script>
	</head>
	<body style="margin:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/functions/telefonos.php" id="formTelefonos" method="get" name="formTelefonos">
			<input id="idModulo" name="idModulo" type="hidden" value="<?= $_REQUEST["idModulo"]?>">
			<input id="idSolicitud" name="idSolicitud" type="hidden" value="<?= $_REQUEST["idSolicitud"]?>">
			<div align="left" style="color:#211e1e; font-family:Trebuchet MS; font-size:8pt; margin-bottom:16px; margin-left:8px; margin-top:8px;">
				<label for="numero">Número</label>
				<input class="input" id="numero" name="numero" style="width:48px;" type="text" value="<?= $numero?>">
				<label for="nombre" style="margin-left:4px;">Nombre</label>
				<input class="input" id="nombre" name="nombre" style="width:88px;" type="text" value="<?= $nombre?>">
				<label for="domicilio" style="margin-left:4px;">Domicilio</label>
				<input class="input" id="domicilio" name="domicilio" style="width:120px;" type="text" value="<?= $domicilio?>">
				<input class="botonGris" style="margin-left:24px;" type="submit" value="BUSCAR">
				<input class="botonGris" style="margin-left:136px;" type="button" value="NUEVO" onClick="document.getElementById('iframeProcesando').src = '/modules/solicitud_afiliacion/abrir_ventana_establecimiento.php?idModulo=<?= $_REQUEST["idModulo"]?>&idSolicitud=<?= $_REQUEST["idSolicitud"]?>&id=-1'">
			</div>
			<div align="center" id="divContent" name="divContent">
<?
$params = array(":idsolicitud" => $_REQUEST["idSolicitud"]);
$where = "";

if ($domicilio != "") {
	$params[":domicilio"] = "%".$domicilio."%";
	$where.= " AND UPPER(art.utiles.armar_domicilio(se_calle, se_numero, se_piso, se_departamento, NULL) || art.utiles.armar_localidad(se_cpostal, NULL, se_localidad, se_provincia)) LIKE UPPER(:domicilio)";
}

if ($nombre != "") {
	$params[":nombre"] = "%".$nombre."%";
	$where.= " AND UPPER(se_nombre) LIKE UPPER(:nombre)";
}

if ($numero != "") {
	$params[":numero"] = "%".$numero."%";
	$where.= " AND UPPER(se_nroestableci) LIKE UPPER(:numero)";
}

$sql =
	"SELECT ¿se_id?,
					se_id ¿id2?,
					se_id ¿id3?,
					¿se_nroestableci?,
					¿se_nombre?,
					art.utiles.armar_domicilio(se_calle, se_numero, se_piso, se_departamento, NULL) || art.utiles.armar_localidad(se_cpostal, NULL, se_localidad, se_provincia) ¿domicilio?,
					se_fechabaja ¿baja?,
					DECODE(se_fechabaja, NULL, 'F', 'T') ¿hidecol?,
					DECODE(se_fechabaja, NULL, 'T', 'F') ¿hidecol2?,
					DECODE(se_fechabaja, NULL, DECODE(art.hys.get_idresolucion463(se_id), NULL, 'T', 'F'), 'T') ¿hidecol3?,
					DECODE(mod(se_id, 2), 1, 'btnRGRLOk', 'btnRGRL') ¿buttonclass?
		 FROM ase_solicitudestablecimiento
		WHERE se_idsolicitud = :idsolicitud _EXC1_";
$grilla = new Grid(10, 5);
$grilla->addColumn(new Column("E", 0, true, false, -1, "btnEditar", "/modules/solicitud_afiliacion/abrir_ventana_establecimiento.php?idModulo=".$_REQUEST["idModulo"]."&idSolicitud=".$_REQUEST["idSolicitud"], "", -1, true, -1, "Editar"));
$grilla->addColumn(new Column("R", 0, true, false, -1, "btnReactivar", "/modules/solicitud_afiliacion/reactivar_establecimiento.php?idModulo=".$_REQUEST["idModulo"], "", -1, true, -1, "Reactivar"));
$grilla->addColumn(new Column("RGRL", 0, true, false, -1, "", "/modules/solicitud_afiliacion/abrir_ventana_rgrl.php?idModulo=".$_REQUEST["idModulo"], "", -1, true, -1, "Relevamiento General de Riesgos Laborales", false, "", "button", -1, 11));
$grilla->addColumn(new Column("Número"));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("Domicilio"));
$grilla->addColumn(new Column("", -1, false, true));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 1));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 2));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 3));
$grilla->addColumn(new Column("", 0, false));
$grilla->setBaja(7, $sb, true);
$grilla->setColsSeparator(true);
$grilla->setExtraConditions(array($where));
$grilla->setFieldBaja("se_fechabaja");
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

		document.getElementById('foco').style.display = 'block';
		document.getElementById('foco').focus();
		document.getElementById('foco').style.display = 'none';
	</script>
</html>