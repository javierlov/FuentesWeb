<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");


if (!isset($_SESSION["idUsuario"])) {
	header("Location: ".LOCAL_PATH_PROGRAMA_INCENTIVOS);
	exit;
}

SetDateFormatOracle("DD/MM/YYYY");

$params = array(":id" => $_SESSION["idUsuario"]);
$sql =
	"SELECT CASE
						WHEN ui_fechacierre IS NOT NULL
							OR art.actualdate < ui_fechadesde
							OR art.actualdate > ui_fechahasta THEN 'N'
																								ELSE 'S'
					END canjecerrado, em_detalle, re_resultado, ui_cuil, ui_empleado, ui_fechahasta, ui_puntos, ui_resultadopuesto, ui_saldo
		 FROM rrhh.rui_usuarioincentivo, rrhh.rre_resultadoempresa, rrhh.rem_empresas
		WHERE ui_idempresa = re_idempresa
			AND ui_idempresa = em_id
			AND ui_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$params = array(":idusuario" => $_SESSION["idUsuario"]);
$sql = 
	"SELECT be_descripcion, be_id, be_posicion, NVL(cp_puntos, 0) puntos
		 FROM rrhh.rbe_beneficios, rrhh.rcp_canjepuntos
		WHERE be_id = cp_idbeneficio(+)
			AND be_fechabaja IS NULL
			AND cp_fechabaja IS NULL
			AND cp_idusuario(+) = :idusuario
 ORDER BY be_posicion";
$stmt2 = DBExecSql($conn, $sql, $params);
$alto = 128 + (22 * DBGetRecordCount($stmt2));
if ($alto < 240)
	$alto = 240;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<script language="JavaScript" src="/js/functions.js"></script>
		<script language="JavaScript" src="/js/hint/hints.js"></script>
		<script language="JavaScript" src="js/programa_incentivos.js"></script>
		<style>
			.hintText {
				background-color: #FFFFCC;
				color: #000000;
				font-family: tahoma, verdana, arial;
				font-size: 12px;
				padding: 5px;
			}
		</style>
		<script type="text/javascript">
			<? require_once($_SERVER["DOCUMENT_ROOT"]."/modules/programa_incentivos_2012_2013/js/hint_config.php");?>
		</script>
	</head>
	<body background="images/fnd.jpg" topmargin="10">
		<div align="center">
			<div><img border="0" src="images/top.jpg" /></div>
			<div style="background-color:#fff; height:<?= $alto?>px; width:755px;">
				<div class="ContenidoSeccion">
					<div align="right" style="background-color:#6bb642; cursor:default; margin-left:-12px; padding-bottom:2px; width:755px;"><?= $row["UI_EMPLEADO"]?> [<a style="cursor:pointer;" onClick="window.location.href = 'logout.php'">Cerrar sesión</a>]&nbsp;&nbsp;</div>
					<table style="width:755px;">
						<tr>
							<td align="right">Empleado</td>
							<td align="left" style="color:#000;"><b><?= $row["UI_EMPLEADO"]?></b></td>
							<td align="right">Fecha Tope del Canje</td>
							<td align="left" style="color:#000;"><b><?= $row["UI_FECHAHASTA"]?></b></td>
							<td style="width:80px;"></td>
						</tr>
						<tr>
							<td align="right">C.U.I.L.</td>
							<td align="left" style="color:#000;"><?= ponerGuiones($row["UI_CUIL"])?></td>
							<td align="right">Resultado Obtenido por Empresa</td>
							<td align="left" style="color:#000;"><?= $row["RE_RESULTADO"]?> %</td>
							<td></td>
						</tr>
						<tr>
							<td align="right">Empresa</td>
							<td align="left" style="color:#000;"><b>Provincia <?= $row["EM_DETALLE"]?></b></td>
							<td align="right">Porcentaje Obtenido por el Puesto</td>
							<td align="left" style="color:#000;"><?= $row["UI_RESULTADOPUESTO"]?> %</td>
							<td></td>
						</tr>
					</table>
				</div>
				<div style="margin-top:16px;">
					<div style="float:left; margin-left:24px; width:520px;">
						<div style="-moz-border-radius:7px; -webkit-border-radius:7px; background-color:#6ab445; border:2px solid #000; color:#fff; padding:4px;"><b>CANJE DE PUNTOS</b></div>
						<div class="ContenidoSeccion" style="-moz-border-radius:7px; -webkit-border-radius:7px; border:2px solid #000; margin-top:-2px;">
							<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
							<form action="/modules/programa_incentivos_2012_2013/guardar.php" id="formPuntos" method="post" name="formPuntos" target="iframeProcesando">
								<input id="accion" name="accion" type="hidden" value="" />
								<table>
<?
while ($rowPuntos = DBGetQuery($stmt2)) {
?>
									<tr>
										<td align="right"><b><?= $rowPuntos["BE_POSICION"]?></b></td>
										<td align="left" style="padding-left:4px; width:600px;"><?= substr($rowPuntos["BE_DESCRIPCION"], 0, 52).((strlen($rowPuntos["BE_DESCRIPCION"])>52)?"...":"")?> (<a href="#" style="cursor:pointer;" onClick="myHint.show(<?= $rowPuntos["BE_ID"]?>, this)" onMouseOut="myHint.hide()">+ info</a>)</td>
										<td><input id="puntos_id_<?= $rowPuntos["BE_ID"]?>" maxlength="8" name="puntos_id_<?= $rowPuntos["BE_ID"]?>" <?= ($row["CANJECERRADO"] == "S")?"readonly":""?> style="<?= ($row["CANJECERRADO"] == "S")?"background-color:#ccc;":""?> height:14px; padding-right:2px; text-align:right; width:64px;" type="text" value="<?= $rowPuntos["PUNTOS"]?>" onBlur="calcularSaldo()" onKeyPress="return validarCaracter(this, event)" onPaste="return false" /></td>
									</tr>
<?
}
?>
									<tr>
										<td></td>
										<td></td>
										<td></td>
									</tr>
								</table>
							</form>
						</div>
					</div>
					<div class="ContenidoSeccion" style="-moz-border-radius:7px; -webkit-border-radius:7px; border:2px solid #000; color:#000; margin-left:544px; padding:4px; width:120px;">
						<div>Puntos a Canjear</div>
						<div id="divPuntos" style="font-size:14px; font-weight:bold;"><?= $row["UI_PUNTOS"]?></div>
						<div><hr style="background-color:#000; border:0px; height:2px; width:100%;" /></div>
						<div>Saldo</div>
						<div id="divSaldo" style="font-size:14px; font-weight:bold;"><?= $row["UI_SALDO"]?></div>
					</div>
					<div style="<?= ($row["CANJECERRADO"] == "S")?"display:none;":""?> margin-top:<?= $alto - 232?>px;">
						<input id="btnGuardar" name="btnGuardar" type="buton" value="GUARDAR" style="background-color:#21b24a; border:1px solid #808080; color:#fff; cursor:pointer; font-family:Trebuchet MS; font-size:10pt; font-weight:bold; margin-left:8px; padding-bottom:1px; padding-left:12px; padding-right:12px; padding-top:1px; width:58px;" onClick="guardar('g')" />
						<br />
						<input id="btnCerrar" name="btnCerrar" type="buton" value="CERRAR / ENVIAR" style="background-color:#21b24a; border:1px solid #808080; color:#fff; cursor:pointer; font-family:Trebuchet MS; font-size:10pt; font-weight:bold; margin-top:8px; padding-bottom:1px; padding-left:12px; padding-right:12px; padding-top:1px; width:104px;" onClick="guardar('c')" />
					</div>
				</div>
			</div>
			<div align="right" style="background-color:#808185; clear:both; height:64px; width:755px;">
				<img src="/modules/programa_incentivos_2012_2013/logo_empresa.php" style="margin-right:14px; margin-top:15px;" />
			</div>
		</div>
	</body>
</html>