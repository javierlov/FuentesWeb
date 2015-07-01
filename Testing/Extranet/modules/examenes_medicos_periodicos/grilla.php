<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


if (!isset($_SESSION["contrato"]))
	exit;


$showProcessMsg = false;

$establecimiento = "-1";
if (isset($_REQUEST["establecimiento"]))
	$establecimiento = $_REQUEST["establecimiento"];

$lote = "";
if (isset($_SESSION["lote"])) {
	$_REQUEST["lote"] = $_SESSION["lote"];
	$_REQUEST["buscar"] = "yes";
	unset($_SESSION["lote"]);
}
if (isset($_REQUEST["lote"]))
	$lote = $_REQUEST["lote"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2, 1, 4";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Establecimientos</title>
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<link href="/modules/formulario_establecimientos/css/establecimientos.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			body {
				scrollbar-face-color: #aaa;
				scrollbar-highlight-color: #aaa;
				scrollbar-shadow-color: #aaa;
				scrollbar-3dlight-color: #eee;
				scrollbar-arrow-color: #eee;
				scrollbar-track-color: #e3e3e3;
				scrollbar-darkshadow-color: #fff;
			}
		</style>
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
	</head>

	<body topmargin="5" link="#0f539c" vlink="#0f539c" alink="#0f539c" bottommargin="5">
		<b><font face="Trebuchet MS" color="#FF0000">&gt; INFORMACIÓN IMPORTANTE</font></b>
		<font face="Trebuchet MS" color="#807F84"><span style="font-size: 8pt">
		<p>Nuestro propósito es detectar precozmente alteraciones en la salud que puedan ser causadas por los riesgos existentes en el ámbito laboral.</p>
		<p style="background-color: #0FA6E3"><b><font color="#000000">Recuerde que de acuerdo a la Res. 37/2010, Ud. debe presentar la Nómina de Personal Expuesto al afiliarse y anualmente ante la renovación  de su contrato de afiliación. Le solicitamos remitir dicha información a Provincia ART en formato digital (soporte magnético o vía e-mail), lo cual permitirá organizar el cronograma de realización de los exámenes del próximo periodo.</font></b></p>
		<p>Estos exámenes se les practicarán a sus trabajadores sin cargo para Ud. y solamente a personal expuesto a riesgos que ameriten examen de salud.</p>
		<p>
			<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
			<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img alt="Espere por favor..." border="0" src="/images/waiting.gif"></div>
			<div id="divContent" name="divContent">
				<form action="<?= $_SERVER["PHP_SELF"]?>" id="formBuscarRes3710" method="post" name="formBuscarRes3710" onSubmit="return ValidarForm(formBuscarRes3710)">
					<input id="buscar" name="buscar" type="hidden" value="yes" />
					<input id="pageid" name="pageid" type="hidden" value="27" />
					<div style="margin-left:47px;">
						<label class="ContenidoSeccion">Nro. Lote</label>
						<input id="lote" maxlength="8" name="lote" title="Nro. Lote" type="text" validarEntero="true" value="<?= $lote?>">
					</div>
					<div style="margin-left:16px; margin-top:4px;">
						<label class="ContenidoSeccion">Establecimiento</label>
						<select id="establecimiento" name="establecimiento"></select>
					</div>
					<div style="margin-left:28px; margin-top:8px;">
						<input class="btnBuscar" type="submit" value="" />
					</div>
				</form>
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	if (($establecimiento == -1) and ($lote == "")) {
		echo "<span style='color:#f00; font-size:12px; font-weight:bold;'>Debe ingresar el Nro. de Lote o el Establecimiento.</span>";
	}
	else {
		$params = array(":contrato" => $_SESSION["contrato"]);
		$where = "";

		if ($establecimiento != -1) {
			$where.= " AND aes.es_id = :establecimiento";
			$params[":establecimiento"] = $establecimiento;
		}
		if ($lote != "") {
			$where.= " AND dl_idlote = :lote";
			$params[":lote"] = intval($lote);
		}

		$sql =
			"SELECT art.utiles.armar_cuit(tj_cuil) ¿cuil?, ¿tj_nombre?, ¿es_descripcion?, ¿es_codigo?
				 FROM afi.aco_contrato, afi.aes_establecimiento aes, hys.hle_loteestudio, hys.hel_estadolote, hys.hdl_detallelote, comunes.ctj_trabajador, art.aes_estudios est
				WHERE dl_fechabaja IS NULL
					AND est.es_fechabaja IS NULL
					AND tj_id = dl_idtrabajador
					AND es_codigo = dl_idestudio
					AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1
					AND co_contrato = es_contrato
					AND aes.es_id = dl_idestableci
					AND el_idlote = dl_idlote
					AND le_id = el_idlote
					AND dl_idlote = art.amp.get_ultimolote_no_anulado(dl_idestableci)
					AND le_estado IN('A', 'P')
					AND co_contrato = :contrato".
				  $where;
		$grilla = new Grid(15, 8);
		$grilla->addColumn(new Column("CUIL"));
		$grilla->addColumn(new Column("Nombre y Apellido"));
		$grilla->addColumn(new Column("Estudio"));
		$grilla->addColumn(new Column("", 0, false));
		$grilla->setColsSeparator(true);
		$grilla->setOrderBy($ob);
		$grilla->setPageNumber($pagina);
		$grilla->setParams($params);
		$grilla->setRowsSeparator(true);
		$grilla->setSql($sql);
		$grilla->Draw();

		$sql =
			"UPDATE hys.hle_loteestudio
					SET le_fechaingresoweb = SYSDATE
				WHERE le_id = (SELECT MAX(dl_idlote)
												 FROM afi.aco_contrato, afi.aes_establecimiento aes, hys.hel_estadolote, hys.hdl_detallelote
												WHERE dl_fechabaja IS NULL
													AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1
													AND co_contrato = es_contrato
													AND aes.es_id = dl_idestableci
													AND el_idlote = dl_idlote
													AND dl_idlote = art.amp.get_ultimolote_no_anulado(dl_idestableci)
													AND el_estado IN('A', 'P')
													AND co_contrato = :contrato".$where.")
					AND le_fechaingresoweb IS NULL";
		DBExecSql($conn, $sql, $params);
	}
}
?>
			</div>
		</p>
		<p>Informamos a Ud. que es responsabilidad del empleador arbitrar los medios para al realización de los exámenes de los trabajadores expuestos. En caso de no poder cumplir con lo notificado, deberá comunicarse con Provincia ART en un plazo no mayor de 48 hs. de recibida la comunicación explicando las causas que motivan el incumplimiento. Si no se cumpliese con la realización de los mismos, nos veremos obligados a notificar a la Superintendencia de Riesgos del Trabajo.</p>
		<p>Ante cualquier inconveniente, no dude en contactarse con el área de Medicina Laboral al (011) 4819-2800 int. 4529/4581/4726 o vía e-mail a <a href="mailto:medicinalaboral@provart.com.ar">medicinalaboral@provart.com.ar</a>.</p>
		<script type="text/javascript">
			function CopyContent() {
				try {
					window.parent.document.getElementById('divContent').innerHTML = document.getElementById('divContent').innerHTML;
				}
				catch(err) {
					//
				}
			}
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";
$RCfield = "establecimiento";
$RCparams = array(":contrato" => $_SESSION["contrato"]);
$RCquery =
	"SELECT es_id id, es_nroestableci || ' - ' || art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, es_localidad) || ' - ' || pv_descripcion detalle
		 FROM cpv_provincias, afi.aes_establecimiento
		WHERE es_contrato = :contrato
			AND es_provincia = pv_codigo
			AND es_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = $establecimiento;
FillCombo();
?>
		CopyContent();

		document.getElementById('lote').focus();
		</script>
	</body>
</html>