<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once("administrar_establecimientos_combos.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));

set_time_limit(60);


if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {		// Si es true, proceso la acción del botón seleccionado..
	switch ($_REQUEST["a"]) {
		case "q":
			$params = array(":id" => $_REQUEST["id"], ":usubaja" => "W_".$_SESSION["idUsuario"]);
			$sql =
				"UPDATE SIN.set_establecimiento_temporal
						SET et_fechabaja = SYSDATE,
								et_usubaja = :usubaja
				  WHERE et_id = :id";
			DBExecSql($conn, $sql, $params);
?>
			<script type="text/javascript">
				parent.window.document.getElementById('establecimientoTercero').parentNode.innerHTML = '<?= $comboEstablecimientoTercero->draw();?>';
				parent.window.copiarDomicilioEstablecimiento(-1, 'f');
				setTimeout('history.back();', 1000);
			</script>
<?
			break;
		case "s":
?>
			<script type="text/javascript">
				window.parent.document.getElementById('establecimientoTercero').value = '<?= $_REQUEST["id"]?>';
				window.parent.copiarDomicilioEstablecimiento('<?= $_REQUEST["id"]?>', 'f');
				parent.divWin2.close();
			</script>
<?
			exit;
	}
}


$showProcessMsg = false;

$altura = "";
if (isset($_REQUEST["altura"]))
	$altura = $_REQUEST["altura"];

$calle = "";
if (isset($_REQUEST["calle"]))
	$calle = $_REQUEST["calle"];

$codigoPostal = "";
if (isset($_REQUEST["codigoPostal"]))
	$codigoPostal = $_REQUEST["codigoPostal"];

$cpa = "";
if (isset($_REQUEST["cpa"]))
	$cpa = $_REQUEST["cpa"];

$cuit = "";
if (isset($_REQUEST["cuit"]))
	$cuit = $_REQUEST["cuit"];

$id = "";
if (isset($_REQUEST["id"]))
	$id = $_REQUEST["id"];

$localidad = "";
if (isset($_REQUEST["localidad"]))
	$localidad = $_REQUEST["localidad"];

$nombre = "";
if (isset($_REQUEST["nombre"]))
	$nombre = $_REQUEST["nombre"];

$provincia = -1;
if (isset($_REQUEST["provincia"]))
	$provincia = $_REQUEST["provincia"];

$tipoCalle = "c";
if (isset($_REQUEST["tipoCalle"]))
	$tipoCalle = $_REQUEST["tipoCalle"];


$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "4";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<style type="text/css"> 
			* {margin:0; padding:0;}
			html, body {background-color:#fff; overflow:hidden;}
		</style>

		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
	</head>
	<body style="margin:0; padding:0;">
		<form action="<?= $_SERVER["PHP_SELF"]?>" id="formBuscarEstablecimiento" method="get" name="formBuscarEstablecimiento">
			<input id="buscar" name="buscar" type="hidden" value="yes" />
			<div style="background-color:#49bdec; padding-bottom:8px; padding-left:8px; padding-top:8px; position:relative; right:0;">
				<div style="margin-bottom:4px; margin-left:39px;">
					<label class="Text5" for="nombre">Nombre</label>
					<input autofocus id="nombre" maxlength="128" name="nombre" style="width:248px;" type="text" value="<?= $nombre?>" />
					<label class="Text5" for="cuit" style="margin-left:24px;">C.U.I.T.</label>
					<input id="cuit" maxlength="13" name="cuit" style="width:84px;" type="text" value="<?= $cuit?>" />
				</div>
				<div style="margin-bottom:4px;">
					<label class="Text5" for="codigoPostal">Código Postal</label>
					<input id="codigoPostal" maxlength="4" name="codigoPostal" style="width:48px;" type="text" value="<?= $codigoPostal?>" />
					<label class="Text5" for="cpa" style="margin-left:58px;">C.P.A.</label>
					<input id="cpa" name="cpa" style="text-transform:uppercase; width:88px;" type="text" value="<?= $cpa?>" />
				</div>
				<div style="margin-bottom:4px; margin-left:28px;">
					<label class="Text5" for="localidad">Localidad</label>
					<input id="localidad" name="localidad" style="text-transform:uppercase; width:248px;" type="text" value="<?= $localidad?>" />
					<label class="Text5" for="provincia" style="margin-left:16px;">Provincia</label>
					<?= $comboProvincia->draw();?>
				</div>
				<div style="margin-bottom:4px; margin-left:58px;">
					<label class="Text5" for="calle">Calle</label>
					<input id="calle" name="calle" style="text-transform:uppercase; width:248px;" type="text" value="<?= $calle?>"/>
					<label class="Text5" for="altura" style="margin-left:26px;">Número</label>
					<input id="altura" name="altura" style="width:48px;" type="text" value="<?= $altura?>" />
				</div>
				<input class="btnBuscar" type="submit" value="" />
				<input class="btnAgregar" style="margin-top:12px;" type="button" value="" onClick="window.location.href='/modules/usuarios_registrados/clientes/denuncias_de_siniestros/establecimiento.php'" />
			</div>
		</form>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div align="center" id="divContentGrid" name="divContentGrid" style="height:260px; overflow:auto;">
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array(":cuit" => $_SESSION["cuit"]);
	$where = "";

	if ($altura != "") {
		$params[":altura"] = $altura;
		$where.= " AND et_numero LIKE UPPER(:altura)";
	}

	if ($calle != "") {
		$params[":calle"] = "%".$calle."%";
		$where.= " AND et_localidad LIKE UPPER(:calle)";
	}

	if ($cuit != "") {
		$params[":cuit_temporal"] = sacarGuiones($cuit);
		$where.= " AND et_cuit_temporal = :cuit_temporal";
	}

	if ($codigoPostal != "") {
		$params[":cpostal"] = $codigoPostal;
		$where.= " AND et_cpostal = :cpostal";
	}

	if ($cpa != "") {
		$params[":cpostala"] = $cpa;
		$where.= " AND et_cpostala = :cpostala";
	}

	if ($id != "") {
		$params[":id"] = $id;
		$where.= " AND et_id = :id";
	}

	if ($localidad != "") {
		$params[":localidad"] = "%".$localidad."%";
		$where.= " AND et_localidad LIKE UPPER(:localidad)";
	}

	if ($nombre != "") {
		$params[":nombre"] = "%".$nombre."%";
		$where.= " AND et_nombre LIKE UPPER(:nombre)";
	}

	if ($provincia != -1) {
		$params[":provincia"] = $provincia;
		$where.= " AND et_provincia = :provincia";
	}

	$sql =
		"SELECT ¿et_id?,
						et_id ¿id2?,
						et_id ¿id3?,
						¿et_nroestableci?,
						¿et_nombre?,
						¿et_cuit_temporal?,
						art.utiles.armar_domicilio(et_calle, et_numero, et_piso, et_departamento, NULL) || art.utiles.armar_localidad(et_cpostal, NULL, et_localidad, et_provincia) || ')' ¿domicilio?
			 FROM SIN.set_establecimiento_temporal
			WHERE et_fechabaja IS NULL
				AND et_cuit = :cuit _EXC1_";
	$grilla = new Grid(10, 10);
	$grilla->addColumn(new Column("S", 0, true, false, -1, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t&a=s", "", -1, true, -1, "Seleccionar"));
	$grilla->addColumn(new Column("E", 0, true, false, -1, "btnEditar", "/modules/usuarios_registrados/clientes/denuncias_de_siniestros/establecimiento.php", "", -1, true, -1, "Editar"));
	$grilla->addColumn(new Column("Q", 0, true, false, -1, "btnQuitar", $_SERVER["PHP_SELF"]."?sd=t&a=q", "", -1, true, -1, "Quitar"));
	$grilla->addColumn(new Column("Nº", 60));
	$grilla->addColumn(new Column("Nombre", 28));
	$grilla->addColumn(new Column("C.U.I.T."));
	$grilla->addColumn(new Column("Domicilio", 44));
	$grilla->setExtraConditions(array($where));
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);
	$grilla->setTableStyle("GridTableCiiu");
	$grilla->Draw();
}
?>
		</div>
		<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<script type="text/javascript">
			function CopyContent() {
				try {
					window.parent.document.getElementById('divContentGrid').innerHTML = document.getElementById('divContentGrid').innerHTML;
				}
				catch(err) {
					//
				}
<?
if ($showProcessMsg) {
?>
				if (document.getElementById('originalGrid') != null)
					document.getElementById('originalGrid').style.display = 'block';
				document.getElementById('divProcesando').style.display = 'none';
<?
}
?>
			}

			CopyContent();
		</script>
	</body>
</html>