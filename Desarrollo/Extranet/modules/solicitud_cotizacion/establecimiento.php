<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT eu_idactividad, eu_idlocalidad, eu_idtipoactividad, eu_idzonageografica, eu_trabajadores
		 FROM afi.aeu_establecimientos
		WHERE eu_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$params = array(":id" => nullIsEmpty($row["EU_IDZONAGEOGRAFICA"]));
$sql =
	"SELECT zg_idprovincia
		 FROM afi.azg_zonasgeograficas
		WHERE zg_id = :id";
$idProvincia = valorSql($sql, "", $params);

require_once("establecimiento_combos.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Establecimientos</title>

		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<style>
			#actividad {margin-left:37px;}
			#ciiu {margin-left:62px; width:440px;}
			#localidad {margin-left:37px;}
			#provincia {margin-left:37px;}
		</style>

		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
		<script type="text/javascript">
			function cambiaProvincia(idprovincia) {
				document.getElementById('imgLoadingProvincia').style.visibility = 'visible';
				document.getElementById('iframeProcesando').src = '/modules/solicitud_cotizacion/cambia_provincia_establecimiento.php?id=' + idprovincia + '&rnd=' + Math.random();
			}

			function eliminar() {
				if (confirm('¿ Realmente desea eliminar este establecimiento ?'))
					with (document) {
						getElementById('tipoOp').value = 'B';
						getElementById('formEstablecimiento').submit();
					}
			}
		</script>
	</head>

	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/solicitud_cotizacion/procesar_establecimiento.php" id="formEstablecimiento" method="post" name="formEstablecimiento" target="iframeProcesando" onSubmit="return ValidarForm(formEstablecimiento)">
			<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>">
			<input id="idsolicitud" name="idsolicitud" type="hidden" value="<?= $_REQUEST["idsolicitud"]?>">
			<input id="tipoOp" name="tipoOp" type="hidden" value="<?= ($_REQUEST["id"] == -1)?"A":"M"?>">
			<div style="margin-top:8px;">
				<label class="ContenidoSeccion" for="provincia">Provincia</label>
				<?= $comboProvincia->draw();?>
				<img id="imgLoadingProvincia" src="/images/loading.gif" style="margin-left:8px; visibility:hidden;" title="Cargando localidades..." />
			</div>
			<div style="margin-top:8px;">
				<label class="ContenidoSeccion" for="localidad">Localidad</label>
				<?= $comboLocalidad->draw();?>
			</div>
			<div style="margin-top:8px;">
				<label class="ContenidoSeccion" for="actividad">Actividad</label>
				<?= $comboActividad->draw();?>
			</div>
			<div style="margin-top:8px;">
				<label class="ContenidoSeccion" for="ciiu">CIIU</label>
				<?= $comboCiiu->draw();?>
			</div>
			<div style="margin-top:8px;">
				<label class="ContenidoSeccion" for="trabajadores"># Trabajadores</label>
				<input id="trabajadores" maxlength="8" name="trabajadores" style="width:80px:" title="# Trabajadores" type="text" validarEntero="true" value="<?= ($_REQUEST["id"] == -1)?"":$row["EU_TRABAJADORES"]?>">
			</div>
			<div align="right" style="margin-right:16px; margin-top:-4px;">
				<input class="btnGrabar" type="submit" value="" />
<?
if ($_REQUEST["id"] != -1) {
?>
				<input class="btnDarDeBaja" style="margin-left:8px;" type="button" value="" onClick="eliminar()" />
<?
}
?>
			</div>
		</form>
	</body>
</html>