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

$cuil = "";
if (isset($_REQUEST["cuil"]))
	$cuil = $_REQUEST["cuil"];

$email = "";
if (isset($_REQUEST["email"]))
	$email = $_REQUEST["email"];

$empleado = "";
if (isset($_REQUEST["empleado"]))
	$empleado = $_REQUEST["empleado"];

$empresa = $_SESSION["idEmpresa"];
if (isset($_REQUEST["empresa"]))
	$empresa = $_REQUEST["empresa"];

$estado = "-1";
if (isset($_REQUEST["estado"]))
	$estado = $_REQUEST["estado"];

$gerencia = "-1";
if (isset($_REQUEST["gerencia"]))
	$gerencia = $_REQUEST["gerencia"];

$grupo = "-1";
if (isset($_REQUEST["grupo"]))
	$grupo = $_REQUEST["grupo"];

$numeroDocumento = "";
if (isset($_REQUEST["numeroDocumento"]))
	$numeroDocumento = $_REQUEST["numeroDocumento"];

$puesto = "-1";
if (isset($_REQUEST["puesto"]))
	$puesto = $_REQUEST["puesto"];

$referente = false;
if (isset($_REQUEST["referente"]))
	$referente = true;

$referenteRrhh = "-1";
if (isset($_REQUEST["referenteRrhh"]))
	$referenteRrhh = $_REQUEST["referenteRrhh"];

$reporta = "-1";
if (isset($_REQUEST["reporta"]))
	$reporta = $_REQUEST["reporta"];


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
	$habilitarEmpresa = "DISABLED";
?>
<html>
	<head>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<link rel="stylesheet" href="/modules/evaluacion_puesto/abm_descripcion_de_puesto/css/style.css" type="text/css" />
		<script language="JavaScript" src="/js/functions.js"></script>
		<script language="JavaScript" src="/js/grid.js"></script>
		<script language="JavaScript" src="/js/validations.js"></script>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script language="JavaScript" src="/modules/evaluacion_puesto/abm_descripcion_de_puesto/js/usuarios.js"></script>
		<script type="text/javascript">
			divWin = null;

			function showAlta(item) {
				if ((divWin == null) || (divWin.style.display == 'none')) {
					//medioancho = (screen.width - 760) / 2;
					medioancho = 16;
					medioalto = document.body.offsetHeight - 280;
					divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=560px,height=200px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=1,scrolling=1');
				}

				if (item == 'E')
					titulo = 'Empresas';
				if (item == 'G')
					titulo = 'Gerencias';
				if (item == 'P')
					titulo = 'Puestos';
				if (item == 'R')
					titulo = 'Grupos';

				divWin.load('iframe', '/modules/evaluacion_puesto/abm_descripcion_de_puesto/tablas_auxiliares.php?tipotabla=' + item, titulo);
				divWin.show();
			}

			if (window.parent.document.getElementById('volver') != null)
				window.parent.document.getElementById('volver').style.display = 'block';
			
		</script>
		<style type="text/css">
			body,html {
				scrollbar-face-color: #aaaaaa;
				scrollbar-highlight-color: #aaaaaa;
				scrollbar-shadow-color: #aaaaaa;
				scrollbar-3dlight-color: #eeeeee;
				scrollbar-arrow-color: #eeeeee;
				scrollbar-track-color: #e3e3e3;
				scrollbar-darkshadow-color: #ffffff;
				font-family: Trebuchet MS;
			}
		</style>
	</head>
	<body topmargin="0">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<iframe id="iframeUsuario" name="iframeUsuario" src="" style="display:none;"></iframe>
		<form action="buscar_usuario.php" id="formBuscarUsuario" method="post" name="formBuscarUsuario" onSubmit="return ValidarForm(formBuscarUsuario)">
			<input id="buscar" name="buscar" type="hidden" value="yes">
			<div style="height:176px;width:700px">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td align="center" colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td align="right" width="4%">&nbsp;</td>
						<td width="10%"><font style="font-size: 8pt">Empresa:</font></td>
						<td width="28%"><p align="left"><select id="empresa" name="empresa" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px" onChange="cambiarEmpresa(-1, -1, -1)" <?= $habilitarEmpresa?>></select></td>
						<td width="10%">&nbsp;<input type="button" value="Alta" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF" onClick="showAlta('E')"></td>
						<td width="12%"><font style="font-size: 8pt">Referente RRHH</font></td>
						<td width="36%" colspan="2"><select id="referenteRrhh" name="referenteRrhh" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px"></select></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">Empleado:</font></td>
						<td width="38%" colspan="2"><input style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" id="empleado" name="empleado" size="37" type="text" value="<?= $empleado?>"></td>
						<td width="12%"><font style="font-size: 8pt">Estado:</font></td>
						<td width="36%" colspan="2"><select id="estado" name="estado" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:280px"></select></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">CUIL:</font></td>
						<td width="38%" colspan="2"><input style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" id="cuil" name="cuil" type="text" size="37" value="<?= $cuil?>"></td>
						<td width="12%"><font style="font-size: 8pt">Nro. de Doc:</font></td>
						<td width="36%" colspan="2"><input style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" id="numeroDocumento" name="numeroDocumento" size="37" title="Nro. de Doc" type="text" validarEntero="true" value="<?= $numeroDocumento?>"></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">Mail:</font></td>
						<td width="38%" colspan="2"><input style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" id="email" name="email" size="37" type="text" value="<?= $email?>"></td>
						<td width="12%"><font style="font-size: 8pt">Responde a:</font></td>
						<td width="36%" colspan="2"><select id="reporta" name="reporta" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px"></select></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">Puesto:</font></td>
						<td width="28%"><select id="puesto" name="puesto" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px"></select></td>
						<td width="10%">&nbsp;<input type="button" value="Alta" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF" onClick="showAlta('P')"></td>
						<td width="12%"><font style="font-size: 8pt">Gerencia:</font></td>
						<td width="28%"><select id="gerencia" name="gerencia" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px"></select></td>
						<td width="9%">&nbsp;<input type="button" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF" value="Alta" onClick="showAlta('G')"></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">Referente:</font></td>
						<td width="38%" colspan="2"><input id="referente" name="referente" type="checkbox" value="ON" <?= ($referente)?"CHECKED":""?>></td>
						<td width="12%"><font style="font-size: 8pt">Grupo:</font></td>
						<td width="28%"><select id="grupo" name="grupo" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px"></select></td>
						<td width="9%">&nbsp;<input type="button" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF" value="Alta" onClick="showAlta('R')"></td>
					</tr>
					<tr>
						<td align="center" colspan="7"><hr color="#C0C0C0" size="1"></td>
					</tr>
					<tr>
						<td colspan="5"><p style="margin-left: 30px"><input type="submit" value="BUSCAR" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF"></td>
						<td></td>
						<td><input type="button" value="NUEVO" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF" onClick="window.location.href='/modules/evaluacion_puesto/abm_descripcion_de_puesto/usuario.php'"></td>
					</tr>
				</table>
			</div>
		</form>
		<p>&nbsp;</p>
		<div id="divContent" name="divContent" style="height:176px;width:700px">
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array();
	$where = "";

	if ($email != "")
		$where.= " AND UPPER(ART.UTILES.reemplazar_acentos(pl_mail)) LIKE '%".RemoveAccents(str_replace("'", "''", $email))."%'";

	if ($empleado != "") {
		$params[":empleado"] = "%".RemoveAccents($empleado)."%";
		$where.= " AND UPPER(ART.UTILES.reemplazar_acentos(pl_empleado)) LIKE UPPER(:empleado)";
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

	if ($numeroDocumento != "") {
		$params[":documento"] = $numeroDocumento;
		$where.= " AND pl_documento = :documento";
	}

	if ($puesto != -1) {
		$params[":puesto"] = $puesto;
		$where.= " AND pl_puesto = :puesto";
	}

	if ($referente)
		$where.= " AND pl_referente = 'S'";

	if ($reporta != -1) {
		$params[":jefe"] = $reporta;
		$where.= " AND pl_jefe = :jefe";
	}

	if ($referenteRrhh != -1) {
		$params[":rrhh"] = $referenteRrhh;
		$where.= " AND pl_rrhh = :rrhh";
	}

	$sql =
		"SELECT ¿pl_id?, ¿pl_empleado?, ¿em_detalle?, ¿pu_detalle?, ¿ge_detalle?, DECODE(pl_fechanotificajefe, NULL, 'Si', 'No') ¿finalizado?,
						DECODE(pl_referente, 'S', 'Si', 'No') ¿pl_referente?, ¿pl_mail?, ¿gr_detalle?, ¿es_detalle?, ¿pl_fechabaja?
			 FROM rrhh.dpl_login, rrhh.rem_empresas, rrhh.rpu_puestos, rrhh.rge_gerencias, rrhh.rgr_grupos, rrhh.res_estadossistemasgestion
			WHERE pl_empresa = em_id(+)
				AND pl_puesto = pu_id(+)
				AND pl_gerencia = ge_id(+)
				AND pl_idgrupo = gr_id(+)
				AND pl_idestado = es_id _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "/modules/evaluacion_puesto/abm_descripcion_de_puesto/usuario.php", "GridFirstColumn"));
	$grilla->addColumn(new Column("Empleado"));
	$grilla->addColumn(new Column("Empresa"));
	$grilla->addColumn(new Column("Puesto"));
	$grilla->addColumn(new Column("Gerencia"));
	$grilla->addColumn(new Column("Fin.", 0, true, false, -1, "", "", "colCenter", -1, false));
	$grilla->addColumn(new Column("Ref.", 0, true, false, -1, "", "", "colCenter", -1, false));
	$grilla->addColumn(new Column("e-Mail", 184));
	$grilla->addColumn(new Column("Grupo"));
	$grilla->addColumn(new Column("Estado"));
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
		"SELECT pl_id ID, pl_empleado detalle
			 FROM rrhh.dpl_login
			WHERE pl_empresa = :empresa
				AND pl_fechabaja IS NULL
	 ORDER BY 2";
	$RCselectedItem = $referenteRrhh;
	FillCombo();

	$RCfield = "estado";
	$RCparams = array();
	$RCquery =
		"SELECT es_id ID, es_detalle detalle
			 FROM rrhh.res_estadossistemasgestion
	 ORDER BY 1";
	$RCselectedItem = $estado;
	FillCombo();

	$RCfield = "puesto";
	$RCparams = array();
	$RCquery =
		"SELECT pu_id id, pu_detalle detalle
			 FROM rrhh.rpu_puestos
	 ORDER BY 2";
	$RCselectedItem = $puesto;
	FillCombo();

	$RCfield = "gerencia";
	$RCparams = array();
	$RCquery =
		"SELECT ge_id id, ge_detalle detalle
			 FROM rrhh.rge_gerencias
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
			cambiarEmpresa(-1, -1, <?= $reporta?>);
			try {
				document.getElementById('empleado').focus();
			}
			catch(err) {
				//
			}
		</script>
	</body>
</html>