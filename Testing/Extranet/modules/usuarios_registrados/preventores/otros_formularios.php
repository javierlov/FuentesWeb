<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isPreventor"]));
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
		<script type="text/javascript">
			function agregarForms() {
				if (validar())
					with (document) {
						getElementById('btnAgregar').style.visibility = 'hidden';
						getElementById('imgProcesando').style.display = 'block';
						getElementById('spanProcesando').style.display = 'block';
						formOtrosFormularios.submit();
					}
			}

			function validar() {
				var form = document.formOtrosFormularios;

				for (i=0; i<form.elements.length; i++)
					if (form.elements[i].type == 'checkbox')
						if (form.elements[i].checked)
							return true;

				alert('Debe seleccionar algún formulario para agregar.');
				return false;
			}
		</script>
	</head>
	<body>
		<div class="ContenidoSeccion">
			<div align="center" class="SubtituloSeccion" style="color:#00a4e4; margin-top:4px;">Seleccione los formularios que desea agregar al listado para ser impresos.</div>
			<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
			<form action="/modules/usuarios_registrados/preventores/agregar_otros_formularios.php" id="formOtrosFormularios" method="post" name="formOtrosFormularios" target="iframeProcesando">
				<input id="agregar" name="agregar" type="hidden" value="s" />
<?
$hayFormularios = false;
sort($_SESSION["preventores"]["empresas"]);
foreach ($_SESSION["preventores"]["empresas"] as $value) {
	$arr = explode("-", $value);

	$params = array(":estableci" => $arr[1], ":id" => $arr[0]);
	$sql =
		"SELECT em_nombre, ep_tipo
			 FROM aem_empresa, hys.hep_estabporpreventor
			WHERE em_id = ep_idempresa(+)
				AND ep_estableci(+) = :estableci
				AND em_id = :id";
	$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	$row = DBGetQuery($stmt);

	$params = array(":idempresa" => $arr[0], ":nroestab" => $arr[1], ":tipo" => $row["EP_TIPO"]);
	$sql =
		"SELECT DISTINCT tf_id, tf_nombre
								FROM hys.htf_tipoformulario, hys.hta_tarea, hys.hft_formulariotarea
							 WHERE ft_idtarea = ta_id
								 AND tf_id = ft_idformulario
								 AND tf_fechabaja IS NULL
								 AND ta_fechabaja IS NULL
								 AND ft_fechabaja IS NULL
								 AND ta_tipos LIKE '%' || :tipo || '%'
								 AND NOT EXISTS(SELECT 1
																	FROM hys.hfg_formulariogenerado
																 WHERE fg_idformulario = ft_idformulario
																	 AND fg_idempresa = :idempresa
																	 AND fg_nroestab = :nroestab
																	 AND fg_fechabaja IS NULL)
						ORDER BY tf_nombre";
	$stmt2 = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	if (DBGetRecordCount($stmt2) > 0) {
		$hayFormularios = true;
?>
		<div style="margin-left:8px;">
			<div style="margin-top:20px;">
				<label class="Text5" style="font-weight:bold; margin-left:-8px;"><?= $row["EM_NOMBRE"]." - Nº Establecimiento ".$arr[1]?></label>
			</div>
		</div>
		<div>
<?
		while ($row2 = DBGetQuery($stmt2)) {
?>
			<input id="idTipoFormulario_<?= $arr[0]?>_<?= $arr[1]?>_<?= $row2["TF_ID"]?>" name="idTipoFormulario_<?= $arr[0]?>_<?= $arr[1]?>_<?= $row2["TF_ID"]?>" style="margin-left:8px; vertical-align:-3px;" type="checkbox" value="<?= $row2["TF_ID"]?>" />
			<span><?= substr($row2["TF_NOMBRE"], 0, 104)?></span>
			<br />
<?
		}
?>
		</div>
<?
	}
}

if ($hayFormularios) {
?>
	<div align="center" style="margin-bottom:4px; margin-top:16px;">
		<img id="imgProcesando" src="/images/loading.gif" style="display:none; vertical-align:-1px;" title="Agregando formularios, aguarde un instante por favor...">
		<span id="spanProcesando" style="color:#2e8d1d; display:none;">Agregando formularios, aguarde un instante por favor...</span>
		<input class="btnAgregar" id="btnAgregar" type="button" onClick="agregarForms()" />
	</div>
<?
}
else
	echo "<span style='color:red;'>No hay formularios para agregar.</span>";
?>
			</form>
		</div>
	</body>
</html>