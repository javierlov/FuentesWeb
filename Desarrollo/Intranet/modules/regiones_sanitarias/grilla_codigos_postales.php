<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getDepartamentos($codigopostal) {
	global $conn;

	$result = "";

	$params = array(":codigopostal" => $codigopostal);
	$sql =
		"SELECT DISTINCT cp_departamento
			 FROM ccp_codigopostal
			WHERE cp_codigo = :codigopostal
				AND cp_fechabaja IS NULL
	 ORDER BY 1";
	$stmt = DBExecSql($conn, $sql, $params);
	$i = 0;
	while ($row = DBGetQuery($stmt)) {
		$result.= $row["CP_DEPARTAMENTO"].", ";
		$i++;
	}

	return $result;
}

function getLocalidades($codigopostal) {
	global $conn;

	$result = "";

	$params = array(":codigopostal" => $codigopostal);
	$sql =
		"SELECT DISTINCT cp_localidad
			 FROM ccp_codigopostal
			WHERE cp_codigo = :codigopostal
				AND cp_fechabaja IS NULL
	 ORDER BY 1";
	$stmt = DBExecSql($conn, $sql, $params);
	$i = 0;
	while ($row = DBGetQuery($stmt)) {
		$result.= "<span style='color:#".(($i % 2 == 0)?"f00":"00f").";'>".$row["CP_LOCALIDAD"]."</span>, ";
		$i++;
	}

	return $result;
}

$cp = "";
if (isset($_REQUEST["cp"]))
	$cp = $_REQUEST["cp"];

$cpSinCoor = "f";
if (isset($_REQUEST["cpSinCoor"]))
	$cpSinCoor = $_REQUEST["cpSinCoor"];

$departamento = "";
if (isset($_REQUEST["departamento"]))
	$departamento = $_REQUEST["departamento"];

$localidad = "";
if (isset($_REQUEST["localidad"]))
	$localidad = $_REQUEST["localidad"];
?>
<html>
	<head>
		<?= GetHead("Códigos Postales", array("grid.css?today=".date("Ymd"), "style.css?today=".date("Ymd")))?>
		<script src="/modules/regiones_sanitarias/js/regiones_sanitarias.js?rnd=<?= date("Ymdhisu")?>" type="text/javascript"></script>
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="<?= $_SERVER["PHP_SELF"]?>" id="formBuscar" method="get" name="formBuscar">
			<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
			<input id="tipo" name="tipo" type="hidden" value="<?= $_REQUEST["tipo"]?>" />
			<label style="margin-left:8px;">C.P.</label>
			<input id="cp" maxlength="5" name="cp" style="width:44px;" type="text" value="<?= $cp?>" />
			<label style="margin-left:16px;">Localidad</label>
			<input id="localidad" maxlength="80" name="localidad" style="width:96px;" type="text" value="<?= $localidad?>" />
			<label style="margin-left:16px;">Departamento</label>
			<input id="departamento" maxlength="80" name="departamento" style="width:96px;" type="text" value="<?= $departamento?>" />
			<label style="margin-left:16px;">C.P. sin coordenadas</label>
			<input <?= ($cpSinCoor=="t")?"checked":""?> id="cpSinCoor" name="cpSinCoor" type="checkbox" value="t" />
			<input style="margin-left:16px;" type="submit" value="BUSCAR" />
		</form>
		<table border="1" id="tabla">
			<tr>
				<td>CP</td>
				<td>Localidades</td>
				<td>Departamento</td>
			</tr>
<?
$params = array(":id" => $_REQUEST["id"]);
$where = "";

if ($cp != "") {
	$params[":codigo"] = $cp;
	$where.= " AND cp_codigo = :codigo";
}

if ($cpSinCoor == "t") {
	$where.= " AND (ra_coordenadax IS NULL OR ra_fechabaja IS NOT NULL)";
}

if ($departamento != "") {
	$params[":departamento"] = "%".$departamento."%";
	$where.= " AND UPPER(cp_departamento) LIKE UPPER(:departamento)";
}

if ($localidad != "") {
	$params[":localidad"] = "%".$localidad."%";
	$where.= " AND UPPER(cp_localidad) LIKE UPPER(:localidad)";
}

$sql =
	"SELECT DISTINCT cp_codigo
							FROM ccp_codigopostal, comunes.cra_coordregionessanitarias
						 WHERE cp_codigo = ra_codigopostal(+)
							 AND cp_fechabaja IS NULL".
									 (($_REQUEST["tipo"] == "p")?" AND cp_provincia = :id":" AND cp_regionsanitaria = :id").
									 $where."
					ORDER BY 1";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt)) {
?>
			<tr id="tr_<?= $row["CP_CODIGO"]?>">
				<td valign="top"><a href="javascript:clicCP('<?= $row["CP_CODIGO"]?>')"><?= $row["CP_CODIGO"]?></a></td>
				<td><?= getLocalidades($row["CP_CODIGO"])?></td>
				<td style="color:#909;" valign="top"><?= getDepartamentos($row["CP_CODIGO"])?></td>
			</tr>
<?
}
?>
		</table>
	</body>
	<script>
		parent.document.getElementById('cpSeleccionado').value = '';
		document.getElementById('cp').focus();
	</script>
</html>