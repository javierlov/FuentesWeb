<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


// Valido que se haya logueado o que sea administrador..
if ((!isset($_SESSION["idUsuario"])) or (!$_SESSION["esAdministrador"])) {
	header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php");
	exit;
}

$showProcessMsg = false;

$empleado = -1;
if (isset($_REQUEST["empleado"]))
	$empleado = $_REQUEST["empleado"];

$empresa = $_SESSION["idEmpresa"];
if (isset($_REQUEST["empresa"]))
	$empresa = $_REQUEST["empresa"];

$estado = -1;
if (isset($_REQUEST["estado"]))
	$estado = $_REQUEST["estado"];

$gerencia = -1;
if (isset($_REQUEST["gerencia"]))
	$gerencia = $_REQUEST["gerencia"];

$grupo = -1;
if (isset($_REQUEST["grupo"]))
	$grupo = $_REQUEST["grupo"];

$puesto = -1;
if (isset($_REQUEST["puesto"]))
	$puesto = $_REQUEST["puesto"];

$referenteRrhh = -1;
if (isset($_REQUEST["referenteRrhh"]))
	$referenteRrhh = $_REQUEST["referenteRrhh"];

$respondeA = -1;
if (isset($_REQUEST["respondeA"]))
	$respondeA = $_REQUEST["respondeA"];


$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$sb = false;
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "T")
		$sb = true;


$habilitarEmpresa = "";
$params = array(":id" => $_SESSION["idUsuario"]);
$sql =
	"SELECT 1
		 FROM rrhh.dpl_login
		WHERE pl_id = :id
			AND pl_mail IN ('jlovatto@provart.com.ar')";
if (!ExisteSql($sql, $params))
	$habilitarEmpresa = "disabled";
?>
<html>
	<head>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<link rel="stylesheet" href="/modules/evaluacion_puesto/abm_descripcion_de_puesto/css/style.css" type="text/css" />
		<script language="JavaScript" src="/js/functions.js"></script>
		<script language="JavaScript" src="/js/grid.js"></script>
		<script language="JavaScript" src="/js/validations.js"></script>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script language="JavaScript" src="/modules/evaluacion_puesto/resultados/js/resultados.js"></script>
		<script type="text/javascript">
			if (window.parent.document.getElementById('volver') != null)
				window.parent.document.getElementById('volver').style.display = 'block';
		</script>
		<style type="text/css">
			body, html {
				scrollbar-face-color: #aaaaaa;
				scrollbar-highlight-color: #aaaaaa;
				scrollbar-shadow-color: #aaaaaa;
				scrollbar-3dlight-color: #eeeeee;
				scrollbar-arrow-color: #eeeeee;
				scrollbar-track-color: #e3e3e3;
				scrollbar-darkshadow-color: #ffffff;
				font-family: Trebuchet MS;
			}

			select {
				border: 1px solid #808080;
				color: #808080;
				font-family: Trebuchet MS;
				font-size: 8pt;
				padding-bottom: 1px;
				padding-left: 4px;
				padding-right: 4px;
				padding-top: 1px;
				width:200px;
			}
		</style>
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="<?= $_SERVER["PHP_SELF"]?>" id="formResultados" method="post" name="formResultados">
			<input id="buscar" name="buscar" type="hidden" value="yes">
			<div style="font-size:8pt;">
				<p>
					<label for="empresa" style="margin-left:14px;">Empresa</label>
					<select id="empresa" name="empresa" onChange="cambiarEmpresa()" <?= $habilitarEmpresa?>></select>
					<label for="referenteRrhh" style="margin-left:8px;">Referente RRHH</label>
					<select id="referenteRrhh" name="referenteRrhh"></select>
				</p>
				<p>
					<label for="empleado" style="margin-left:10px;">Empleado</label>
					<select id="empleado" name="empleado"></select>
					<label for="estado" style="margin-left:52px;">Estado</label>
					<select id="estado" name="estado"></select>
				</p>
				<p>
					<label for="respondeA">Responde a</label>
					<select id="respondeA" name="respondeA"></select>
					<label for="gerencia" style="margin-left:41px;">Gerencia</label>
					<select id="gerencia" name="gerencia"></select>
				</p>
				<p>
					<label for="puesto" style="margin-left:22px;">Puesto</label>
					<select id="puesto" name="puesto"></select>
					<label for="grupo" style="margin-left:56px;">Grupo</label>
					<select id="grupo" name="grupo"></select>
				</p>
				<p>
					<input type="submit" value="BUSCAR" style="color:#877F87; font-family:Trebuchet MS; font-size:8pt; font-weight:bold; border:1px solid #877F87; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; background-color:#ffffff; margin-left:58px;" />
				</p>
			</div>
		</form>
		<p>&nbsp;</p>
		<div id="divContent" name="divContent">
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array();
	$where = "";

	if ($empleado != -1) {
		$params[":empleado"] = $empleado;
		$where.= " AND pl_id = :empleado";
	}

	if ($estado != -1) {
		$params[":estado"] = $estado;
		$where.= " AND pl_idestado = :estado";
	}

	if ($empresa != -1) {
		$params[":empresa"] = $empresa;
		$where.= " AND pl_empresa = :empresa";
	}

	if ($gerencia != -1) {
		$params[":gerencia"] = $gerencia;
		$where.= " AND pl_gerencia = :gerencia";
	}

	if ($grupo != -1) {
		$params[":idgrupo"] = $grupo;
		$where.= " AND pl_idgrupo = :idgrupo";
	}

	if ($puesto != -1) {
		$params[":puesto"] = $puesto;
		$where.= " AND pl_puesto = :puesto";
	}

	if ($respondeA != -1) {
		$params[":jefe"] = $respondeA;
		$where.= " AND pl_jefe = :jefe";
	}

	if ($referenteRrhh != -1) {
		$params[":rrhh"] = $referenteRrhh;
		$where.= " AND pl_rrhh = :rrhh";
	}

	$sql =
		"SELECT ¿pl_id?, ¿em_detalle?, ¿pl_empleado?, ¿es_detalle?, ¿ge_detalle?, ¿pu_detalle?, ¿pl_fechabaja?
			 FROM rrhh.dpl_login, rrhh.rem_empresas, rrhh.rpu_puestos, rrhh.rge_gerencias, rrhh.res_estadossistemasgestion
			WHERE pl_empresa = em_id(+)
				AND pl_puesto = pu_id(+)
				AND pl_gerencia = ge_id(+)
				AND pl_idestado = es_id _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("", 8, true, false, -1, "btnPdf", "/modules/evaluacion_puesto/resultados/ver_evaluacion.php", "GridFirstColumn"));
	$grilla->addColumn(new Column("Empresa"));
	$grilla->addColumn(new Column("Empleado"));
	$grilla->addColumn(new Column("Estado"));
	$grilla->addColumn(new Column("Gerencia"));
	$grilla->addColumn(new Column("Puesto"));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja("PL_FECHABAJA", $sb, false);
	$grilla->setColsSeparator(true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("PL_FECHABAJA");
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setSql($sql);
	$grilla->Draw();
}
?>
		</div>
		<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<div id="ABMWindow" name="ABMWindow" style="display:none"></div>
		<script type="text/javascript">
			function CopyContent() {
				try {
					window.parent.document.getElementById('divContent').innerHTML = document.getElementById('divContent').innerHTML;
				}
				catch(err) {
					//
				}
			}

			CopyContent();
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "empresa";
$RCparams = array();
$RCquery =
	"SELECT em_id id, em_detalle detalle
		 FROM rrhh.rem_empresas
 ORDER BY 2";
$RCselectedItem = $empresa;
FillCombo();

$RCfield = "referenteRrhh";
$RCparams = array(":empresa" => $empresa);
$RCquery =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = $referenteRrhh;
FillCombo();

$RCfield = "empleado";
$RCparams = array(":empresa" => $empresa);
$RCquery =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login
		WHERE pl_empresa = :empresa
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = $empleado;
FillCombo();

$RCfield = "estado";
$RCparams = array();
$RCquery =
	"SELECT es_id id, es_detalle detalle
		 FROM rrhh.res_estadossistemasgestion
 ORDER BY 1";
$RCselectedItem = $estado;
FillCombo();

$RCfield = "respondeA";
$RCparams = array(":empresa" => $empresa, ":id" => $empleado);
$RCquery =
	"SELECT pl_id id, pl_empleado detalle
		 FROM rrhh.dpl_login a
		WHERE pl_empresa = :empresa
			AND EXISTS(SELECT 1
									 FROM rrhh.dpl_login b
									WHERE a.pl_id = b.pl_jefe)
			AND pl_id <> :id
			AND pl_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = $empleado;
FillCombo();

$RCfield = "gerencia";
$RCparams = array();
$RCquery =
	"SELECT ge_id id, ge_detalle detalle
		 FROM rrhh.rge_gerencias
 ORDER BY 2";
$RCselectedItem = $puesto;
FillCombo();

$RCfield = "puesto";
$RCparams = array();
$RCquery =
	"SELECT pu_id id, pu_detalle detalle
		 FROM rrhh.rpu_puestos
 ORDER BY 2";
$RCselectedItem = $puesto;
FillCombo();

$RCfield = "grupo";
$RCparams = array();
$RCquery =
	"SELECT gr_id id, gr_detalle detalle
		 FROM rrhh.rgr_grupos
 ORDER BY 2";
$RCselectedItem = $grupo;
FillCombo();
?>
			try {
				document.getElementById('empresa').focus();
			}
			catch(err) {
				document.getElementById('referenteRrhh').focus();
			}
		</script>
	</body>
</html>