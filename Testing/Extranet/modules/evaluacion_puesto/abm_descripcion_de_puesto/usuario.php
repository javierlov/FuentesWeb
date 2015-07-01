<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


// Valido que se haya logueado o que sea administrador..
if ((!isset($_SESSION["idUsuario"])) or (!$_SESSION["esAdministrador"])) {
	header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php");
	exit;
}

$alta = !isset($_REQUEST["id"]);
$administrador = "";
$referente = "";

if (!$alta) {
	SetDateFormatOracle("DD/MM/YYYY");

	$params = array(":id" => $_REQUEST["id"]);
	$sql = 
		"SELECT gr_detalle, dpl1.pl_administrador, dpl1.pl_cambiopassword, dpl1.pl_departamento, dpl1.pl_documento, dpl1.pl_empleado, dpl1.pl_empresa, dpl1.pl_fechadesde, dpl1.pl_fechahasta,
						dpl1.pl_gerencia, dpl1.pl_idestado, dpl1.pl_idgrupo, dpl1.pl_jefe, dpl1.pl_mail, dpl1.pl_rrhh, dpl1.pl_puesto, dpl1.pl_referente
			 FROM rrhh.dpl_login dpl1, rrhh.dpl_login dpl2, rrhh.rgr_grupos
			WHERE dpl1.pl_jefe = dpl2.pl_id(+)
				AND dpl2.pl_idgrupo = gr_id(+)
				AND dpl1.pl_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
	$administrador = ($row["PL_ADMINISTRADOR"] == "T")?"CHECKED":"";
	$referente = ($row["PL_REFERENTE"] == "S")?"CHECKED":"";
}

$empresa = $_SESSION["idEmpresa"];
$habilitarEmpresa = "";
$params = array(":id" => $_SESSION["idUsuario"]);
$sql =
	"SELECT 1
		 FROM rrhh.dpl_login
		WHERE pl_id = :id
			AND pl_mail IN ('jlovatto@provart.com.ar')";
$esSuperUsuario = ExisteSql($sql, $params);
if (($esSuperUsuario) and (isset($row["PL_EMPRESA"])))
	$empresa = $row["PL_EMPRESA"];
if (!$esSuperUsuario)
	$habilitarEmpresa = "DISABLED";
?>
<html>
	<head>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<script language="JavaScript" src="/js/functions.js"></script>
		<script language="JavaScript" src="/js/validations.js"></script>
		<script type="text/javascript" src="/js/popup/dhtmlwindow.js"></script>
		<script language="JavaScript" src="/modules/evaluacion_puesto/abm_descripcion_de_puesto/js/usuarios.js"></script>
		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/Calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/Calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/Calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/Calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->
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

			.BotonFecha {
				border: none;
				background-image: url(../Images/boton_fecha.jpg);
				cursor: pointer;
				height: 21px;
				width: 32px;
			}
		</style>
	</head>
	<body link="#877F87" vlink="#877F87" alink="#877F87" topmargin="0">
		<iframe id="iframeUsuario" name="iframeUsuario" src="" style="display:none;"></iframe>
		<form action="/modules/evaluacion_puesto/abm_descripcion_de_puesto/procesar_usuario.php" id="formUsuario" method="post" name="formUsuario" target="iframeUsuario" onSubmit="return validarUsuario(formUsuario)">
			<input id="id" name="id" type="hidden" value="<?= ($alta)?"":$_REQUEST["id"]?>" />
			<input id="idempresa" name="idempresa" type="hidden" value="<?= $empresa?>" />
			<input id="tipoOp" name="tipoOp" type="hidden" value="<?= ($alta)?"A":"M"?>" />
			<input id="estadoAnterior" name="estadoAnterior" type="hidden" value="<?= ($alta)?1:$row["PL_IDESTADO"]?>" />
			<div style="height:176px;width:700px">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td align="center" colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td width="11%"><font style="font-size: 8pt">Empresa:</font></td>
						<td width="28%" colspan="2"><select id="empresa" name="empresa" size="1" title="Empresa" validar="true" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px" onChange="cambiarEmpresa(<?= ($alta)?-1:$_REQUEST["id"]?>, -1, -1)" <?= $habilitarEmpresa?>></select></td>
						<td width="13%"><font style="font-size: 8pt">Gerencia:</font></td>
						<td width="50%" colspan="2"><select id="gerencia" name="gerencia" size="1" title="Gerencia" validar="true" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px"></select></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">Empleado:</font></td>
						<td width="41%" colspan="2"><input id="empleado" name="empleado" size="37" title="Empleado" type="text" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" validar="true" value="<?= ($alta)?"":$row["PL_EMPLEADO"]?>"></td>
						<td width="9%"><font style="font-size: 8pt">Puesto:</font></td>
						<td width="41%" colspan="2"><select id="puesto" name="puesto" size="1" title="Puesto" validar="true" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px"></select></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">Nro. de Doc:</font></td>
						<td width="41%" colspan="2"><input id="numeroDocumento" name="numeroDocumento" size="37" title="Nro. de Doc" type="text" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" validar="true" validarEntero"true" value="<?= ($alta)?"":$row["PL_DOCUMENTO"]?>"></td>
						<td width="9%"><font style="font-size: 8pt">Sector / Oficina:</font></td>
						<td width="41%" colspan="2"><input id="departamento" maxlength="250" name="departamento" size="37" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" type="text" value="<?= ($alta)?"":$row["PL_DEPARTAMENTO"]?>"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td width="9%"><font style="font-size: 8pt">e-Mail:</font></td>
						<td width="41%" colspan="4"><input id="email" name="email" size="37" title="Mail" type="text" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" validar="true" validarEmail="true" value="<?= ($alta)?"":$row["PL_MAIL"]?>"></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right" colspan="7" height="20"><p align="left">&nbsp;</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">Reporta:</font></td>
						<td width="41%" colspan="2"><select id="reporta" name="reporta" title="Reporta" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px" validar="true" onChange="cambiarReporta()"></select></td>
						<td width="9%"><font style="font-size: 8pt">Estado:</font></td>
						<td width="41%" colspan="2"><select id="estado" name="estado" title="Estado" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:280px" validar="true"></select></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">Grupo:</font></td>
						<td width="28%"><font style="font-size: 8pt"><i><span id="grupoJefe"></i></font></span></td>
						<td width="13%"></td>
						<td width="50%" colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right" colspan="7" height="20">&nbsp;</td>
						</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">Referente:</font></td>
						<td width="28%"><input id="referente" name="referente" type="checkbox" value="ON" <?= $referente?>></td>
						<td width="13%"></td>
						<td><font style="font-size: 8pt">Activar de:</font></td>
						<td width="7%"><input id="activarDesde" maxlength="10" name="activarDesde" size="12" title="Activar Desde" type="text" validarFecha="true" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" value="<?= ($alta)?"":$row["PL_FECHADESDE"]?>"><input class="BotonFecha" id="btnFechaDesde" name="btnFechaDesde" type="button" value=""></td>
						<td width="27%"><input id="activarHasta" maxlength="10" name="activarHasta" size="12" title="Activar Hasta" type="text" validarFecha="true" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" value="<?= ($alta)?"":$row["PL_FECHAHASTA"]?>"><input class="BotonFecha" id="btnFechaHasta" name="btnFechaHasta" type="button" value=""></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">Grupo:</font></td>
						<td width="28%"><select id="grupo" name="grupo" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px"></select></td>
						<td width="13%">&nbsp;<input type="button" value="Alta" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF" onClick="showAlta('R')"></td>
						<td width="50%" colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><font style="font-size: 8pt">Referente RRHH:</font></td>
						<td width="28%"><select id="referenteRrhh" name="referenteRrhh" title="Referente RRHH" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:200px" validar="true"></select></td>
						<td width="13%"></td>
						<td width="14%"><font style="font-size: 8pt">Resetear Clave:</font></td>
						<td align="left"  width="27%"><input id="resetearClave" name="resetearClave" type="checkbox" value="ON"></td>
						<td><font style="font-size: 8pt">Administrador:</font><input id="administrador" name="administrador" type="checkbox" value="ON" <?= $administrador?>></td>
					</tr>
					<tr>
						<td align="center" colspan="7"><hr color="#C0C0C0" size="1"></td>
					</tr>
					<tr>
						<td colspan="4">
<?
if (!$alta) {
?>
							<input style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; float: left" type="button" value="DAR DE BAJA" onClick="eliminar(<?= $_REQUEST["id"]?>)">
<?
}
?>
						</td>
						<td colspan="4"><input style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; float: right" type="submit" value="GRABAR"></td>
					</tr>
				</table>
			</div>
		</form>
		<div id="ABMWindow" name="ABMWindow" style="display:none"></div>
		<script type="text/javascript">
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

	$RCfield = "gerencia";
	$RCparams = array();
	$RCquery =
		"SELECT ge_id id, ge_detalle detalle
			 FROM rrhh.rge_gerencias
	 ORDER BY 2";
	$RCselectedItem = ($alta)?-1:$row["PL_GERENCIA"];
	FillCombo();

	$RCfield = "puesto";
	$RCparams = array();
	$RCquery =
		"SELECT pu_id id, pu_detalle detalle
			 FROM rrhh.rpu_puestos
	 ORDER BY 2";
	$RCselectedItem = ($alta)?-1:$row["PL_PUESTO"];
	FillCombo();

	$RCfield = "grupo";
	$RCparams = array();
	$RCquery =
		"SELECT gr_id id, gr_detalle detalle
			 FROM rrhh.rgr_grupos
	 ORDER BY 2";
	$RCselectedItem = ($alta)?-1:$row["PL_IDGRUPO"];
	FillCombo();

	$RCfield = "estado";
	$RCparams = array();
	$RCquery =
		"SELECT es_id id, es_detalle detalle
			 FROM rrhh.res_estadossistemasgestion
	 ORDER BY 1";
	$RCselectedItem = ($alta)?1:$row["PL_IDESTADO"];
	FillCombo();

	$RCfield = "referenteRrhh";
	$RCparams = array(":empresa" => $empresa, ":id" => (($alta)?-1:$_REQUEST["id"]));
	$RCquery =
		"SELECT pl_id id, pl_empleado detalle
			 FROM rrhh.dpl_login
			WHERE pl_empresa = :empresa
				AND pl_id <> :id
				AND pl_fechabaja IS NULL
	 ORDER BY 2";
	$RCselectedItem = ($alta)?-1:$row["PL_RRHH"];
	FillCombo();
?>
			Calendar.setup (
				{
					inputField: "activarDesde",
					ifFormat  : "%d/%m/%Y",
					button    : "btnFechaDesde"
				}
			);
			Calendar.setup (
				{
					inputField: "activarHasta",
					ifFormat  : "%d/%m/%Y",
					button    : "btnFechaHasta"
				}
			);

			cambiarEmpresa(<?= ($alta)?-1:$_REQUEST["id"]?>, '<?= ($alta)?-1:$row["PL_JEFE"]?>', '<?= ($alta)?-1:$row["PL_RRHH"]?>');
			document.getElementById('gerencia').focus();
		</script>
	</body>
</html>