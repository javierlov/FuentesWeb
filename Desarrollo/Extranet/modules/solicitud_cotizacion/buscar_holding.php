<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getCodigoHolding($holding) {
	$hol = str_replace("grupo", "", $holding);
	$hol = trim(str_replace("GRUPO", "", $hol));
	$arr = explode(" ", $hol);

	// Trato de obtener el código..
	for ($i=10; $i>=3; $i--)
		foreach ($arr as $value) {
			$value = substr($value, 0, $i);

			if ($value <> "") {
				$params = array(":codigo" => $value);
				$sql =
					"SELECT 1
						 FROM age_grupoeconomico
						WHERE UPPER(ge_codigo) = UPPER(:codigo)";
				if (!ExisteSql($sql, $params))
					return $value;
			}
		}

	// Si no se pudo encontrar un código, invento uno que no exista..
	for ($a=65; $a<=90; $a++)
		for ($b=65; $b<=90; $b++)
			for ($c=65; $c<=90; $c++)
				for ($d=65; $d<=90; $d++)
					for ($e=65; $e<=90; $e++)
						for ($f=65; $f<=90; $f++)
							for ($g=65; $g<=90; $g++)
								for ($h=65; $h<=90; $h++)
									for ($i=65; $i<=90; $i++)
										for ($j=65; $j<=90; $j++) {
											$value = chr($a).chr($b).chr($c).chr($d).chr($e).chr($f).chr($g).chr($h).chr($i).chr($j);

											$params = array(":codigo" => $value);
											$sql =
												"SELECT 1
													 FROM age_grupoeconomico
													WHERE UPPER(ge_codigo) = UPPER(:codigo)";
											if (!ExisteSql($sql, $params))
												return $value;
										}

	return "ERR";
}


if (isset($_POST["alta"])) {
	if ($_POST["holding"] == "") {
?>
		<script type="text/javascript">
			history.back();
			alert('Ingrese el holding que desea agregar.');
		</script>
<?
		exit;
	}

	$params = array(":descripcion" => $_POST["holding"]);
	$sql =
		"SELECT ge_id
			 FROM age_grupoeconomico
			WHERE UPPER(ge_descripcion) = UPPER(:descripcion)";
	$id = ValorSql($sql, -1, $params);
	if ($id > 0)		// Si agrega uno que ya existe lo reutilizo..
		header("Location: /modules/solicitud_cotizacion/seleccionar_holding.php?id=".$id);
	else {		// Agrego el ingresado por el usuario..
		$params = array(":codigo" => getCodigoHolding($_POST["holding"]),
										":descripcion" => $_POST["holding"],
										":usualta" => $_SESSION["usuario"]);
		$sql =
			"INSERT INTO age_grupoeconomico
									 (ge_codigo, ge_descripcion, ge_fechaalta, ge_id, ge_incluiranalisisemision, ge_temporal, ge_usualta)
						VALUES (UPPER(:codigo), UPPER(:descripcion), SYSDATE, seq_age_id.NEXTVAL, 'N', 'T', :usualta)";
		DBExecSql($conn, $sql, $params);

	$sql = "SELECT MAX(ge_id) FROM age_grupoeconomico";
?>
		<script type="text/javascript">
			with (window.parent) {
				document.getElementById('idHolding').value = '<?= ValorSql($sql, "", array())?>';
				document.getElementById('holding').value = '<?= strtoupper($_POST["holding"])?>';
				divWin.close();
			}
		</script>
<?
		exit;
	}
}
?>
<html>
	<head>
		<link rel="stylesheet" href="/modules/solicitud_cotizacion/css/grid.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<script language="JavaScript" src="/js/grid.js"></script>
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/solicitud_cotizacion/buscar_holding_busqueda.php" id="formBuscarHolding" method="post" name="formBuscarHolding" target="iframeProcesando" onSubmit="return ValidarForm(formBuscarHolding)">
			<div style="margin-left:8px; margin-top:8px;">
				<font face="Trebuchet MS" style="font-size:8pt; font-weight:bold;">NOTA: Usted puede ingresar solo una parte del holding a buscar.</font>
			</div>
			<div style="margin-left:8px; margin-top:8px;">
				<label for="descripcion"><font face="Trebuchet MS" style="font-size:8pt;">Descripción</font></label>
				<input id="descripcion" name="descripcion" style="margin-right:16px; width:404px;" type="text" value="" />
				<input class="btnBuscar" style="vertical-align:-3px;" type="submit" value="" />
			</div>
		</form>
		<div align="center" id="divContentGrid" name="divContentGrid"></div>
		<div id="divAlta" style="display:none; margin-left:8px;">
			<div style="margin-top:4px;">
				<font face="Trebuchet MS" style="font-size:8pt; font-weight:bold;">Si el holding buscado no pudo ser encontrado lo podrá dar de alta.</font>
			</div>
			<div style="margin-top:8px;">
				<form action="<?= $_SERVER["PHP_SELF"]?>" id="formAltaHolding" method="post" name="formAltaHolding" onSubmit="return ValidarForm(formAltaHolding)">
					<input id="alta" name="alta" type="hidden" value="yes" />
					<input class="btnAlta" style="vertical-align:-3px;" type="submit" value="" />
					<input id="holding" name="holding" style="text-transform:uppercase; width:400px;" type="text" value="" />
				</form>
			</div>
		</div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<script type="text/javascript">
			document.getElementById('descripcion').focus();
		</script>
	</body>
</html>