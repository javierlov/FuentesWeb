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
	setDateFormatOracle("DD/MM/YYYY");

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
$habilitarEmpresa = true;
$params = array(":id" => $_SESSION["idUsuario"]);
$sql =
	"SELECT 1
		 FROM rrhh.dpl_login
		WHERE pl_id = :id
			AND pl_mail IN ('evila@provart.com.ar', 'alapaco@provart.com.ar', 'msanz@provart.com.ar', 'rortiz@provart.com.ar', 'cestrada@gbapro.com.ar', 'cdorpalen@gbapro.com.ar',
											'silvina.steinbaum@gbapro.com.ar', 'dgoldfarb@gbapro.com.ar')";
$esSuperUsuario = existeSql($sql, $params);
if (($esSuperUsuario) and (isset($row["PL_EMPRESA"])))
	$empresa = $row["PL_EMPRESA"];
if (!$esSuperUsuario)
	$habilitarEmpresa = false;

require_once("usuario_combos.php");
?>
<html>
	<head>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<link href="/modules/evaluacion_puesto/abm_descripcion_de_puesto/css/style.css" rel="stylesheet" type="text/css" />

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
	</head>
	<body link="#877F87" vlink="#877F87" alink="#877F87" topmargin="0">
		<iframe id="iframeUsuario" name="iframeUsuario" src="" style="display:none;"></iframe>
		<form action="/modules/evaluacion_puesto/abm_descripcion_de_puesto/procesar_usuario.php" id="formUsuario" method="post" name="formUsuario" target="iframeUsuario" onSubmit="return validarUsuario(formUsuario)">
			<input id="id" name="id" type="hidden" value="<?= ($alta)?"":$_REQUEST["id"]?>" />
			<input id="idempresa" name="idempresa" type="hidden" value="<?= $empresa?>" />
			<input id="tipoOp" name="tipoOp" type="hidden" value="<?= ($alta)?"A":"M"?>" />
			<input id="estadoAnterior" name="estadoAnterior" type="hidden" value="<?= ($alta)?1:$row["PL_IDESTADO"]?>" />
			<div id="divPrincipal">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td align="center" colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td width="11%">Empresa</td>
						<td width="28%" colspan="2"><?= $comboEmpresa->draw();?></td>
						<td width="13%">Gerencia</td>
						<td width="50%" colspan="2"><?= $comboGerencia->draw();?></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td>Empleado</td>
						<td width="41%" colspan="2"><input id="empleado" name="empleado" type="text" value="<?= ($alta)?"":$row["PL_EMPLEADO"]?>"></td>
						<td width="9%">Puesto</td>
						<td width="41%" colspan="2"><?= $comboPuesto->draw();?></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td>N° de Doc.</td>
						<td width="41%" colspan="2"><input id="numeroDocumento" name="numeroDocumento" type="text" value="<?= ($alta)?"":$row["PL_DOCUMENTO"]?>"></td>
						<td width="9%">Sector / Oficina</td>
						<td width="41%" colspan="2"><input id="departamento" maxlength="250" name="departamento" type="text" value="<?= ($alta)?"":$row["PL_DEPARTAMENTO"]?>"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td width="9%">e-Mail</td>
						<td width="41%" colspan="4"><input id="email" name="email" type="text" value="<?= ($alta)?"":$row["PL_MAIL"]?>"></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right" colspan="7" height="20"><p align="left">&nbsp;</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td>Reporta</td>
						<td width="41%" colspan="2"><?= $comboReporta->draw();?></td>
						<td width="9%">Estado</td>
						<td width="41%" colspan="2"><?= $comboEstado->draw();?></td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td>Grupo</td>
						<td width="28%"><i><span id="grupoJefe"></i></span></td>
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
						<td>Referente</td>
						<td width="28%"><input id="referente" name="referente" type="checkbox" value="ON" <?= $referente?>></td>
						<td width="13%"></td>
						<td>Activar de</td>
						<td colspan="2" width="17%">
							<input class="fecha" id="activarDesde" maxlength="10" name="activarDesde" type="text" value="<?= ($alta)?"":$row["PL_FECHADESDE"]?>" />
							<input class="BotonFecha" id="btnFechaDesde" name="btnFechaDesde" type="button" value="" />
							<input class="fecha" id="activarHasta" maxlength="10" name="activarHasta" type="text" value="<?= ($alta)?"":$row["PL_FECHAHASTA"]?>" />
							<input class="BotonFecha" id="btnFechaHasta" name="btnFechaHasta" type="button" value="" />
						</td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td>Grupo</td>
						<td width="28%"><?= $comboGrupo->draw();?></td>
						<td width="13%">&nbsp;<input type="button" value="Alta" onClick="showAlta('R')"></td>
						<td width="50%" colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" height="4"></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td>Referente RRHH</td>
						<td width="28%"><?= $comboReferenteRrhh->draw();?></td>
						<td width="13%"></td>
						<td width="14%">Resetear Clave</td>
						<td align="left"  width="27%"><input id="resetearClave" name="resetearClave" type="checkbox" value="ON"></td>
						<td>Administrador<input id="administrador" name="administrador" type="checkbox" value="ON" <?= $administrador?> /></td>
					</tr>
					<tr>
						<td align="center" colspan="7"><hr color="#c0c0c0"></td>
					</tr>
					<tr>
						<td colspan="4">
<?
if (!$alta) {
?>
							<input type="button" value="DAR DE BAJA" onClick="eliminar(<?= $_REQUEST["id"]?>)">
<?
}
?>
						</td>
						<td colspan="4"><input type="submit" value="GRABAR"></td>
					</tr>
				</table>
			</div>
		</form>
		<div id="ABMWindow" name="ABMWindow" style="display:none"></div>
		<script type="text/javascript">
			Calendar.setup ({
				inputField: "activarDesde",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaDesde"
			});
			Calendar.setup ({
				inputField: "activarHasta",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaHasta"
			});

			cambiarEmpresa(<?= ($alta)?-1:$_REQUEST["id"]?>, '<?= ($alta)?-1:$row["PL_JEFE"]?>', '<?= ($alta)?-1:$row["PL_RRHH"]?>');
		</script>
	</body>
</html>