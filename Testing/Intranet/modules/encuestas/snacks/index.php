<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
		<title>Encuesta. Máquina de Snacks</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script language="JavaScript" src="/js/validations.js"></script>
		<script language="JavaScript" src="js/snacks.js?rnd=<?= time()?>"></script>
	</head>
	<body>
		<form action="procesar_envio_snacks.php" id="formEncuesta" method="post" name="formEncuesta">
			<div align="center">
				<table border="0" cellpadding="0" cellspacing="0" width="750">
					<tr>
						<td></td>
					</tr>
					<tr>
						<td class="EncabezadoEncuesta"><img border="0" src="images/titular.jpg" width="750" height="57"></td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
				</table>
				<table border="0" cellpadding="0" cellspacing="0" id="tablePreguntas" width="750">
<?
$sql =
	"SELECT pe_id, pe_pregunta
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_idencuesta = 1
			AND pe_fechabaja IS NULL
 ORDER BY pe_orden";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
?>
					<tr>
						<td>
							<input id="pregunta<?= $row["PE_ID"]?>" name="pregunta<?= $row["PE_ID"]?>" type="hidden" value="<?= $row["PE_ID"]?>">
							<table border="0" cellpadding="0" cellspacing="0" id="table<?= $row["PE_ID"]?>" width="100%" style="display:none">
								<tr>
									<td class="EncabezadoPregunta" colspan="2"><?= $row["PE_PREGUNTA"]?></td>
								</tr>
<?
	$params = array(":idpregunta" => $row["PE_ID"]);
	$sql =
		"SELECT op_id, op_opcion, op_permiteobservacion
			 FROM rrhh.rop_opcionespreguntas
			WHERE op_idpregunta = :idpregunta
				AND op_fechabaja IS NULL";
	$stmt2 = DBExecSql($conn, $sql, $params);
	while ($row2 = DBGetQuery($stmt2)) {
?>
								<tr>
									<td class="Respuestas" width="2%"><input id="opcion<?= $row["PE_ID"]?>" name="opcion<?= $row["PE_ID"]?>" type="radio" value="<?= $row2["OP_ID"]?>" onClick="showHideObservacion(<?= $row["PE_ID"]?>, <?= $row2["OP_ID"]?>)"></td>
									<td class="Respuestas" width="98%"><?= $row2["OP_OPCION"]?></td>
								</tr>
<?
		if ($row2["OP_PERMITEOBSERVACION"] == "T") {
?>
								<tr id="trObservacion<?= $row2["OP_ID"]?>" style="display:none">
									<td class="Respuestas" width="2%">&nbsp;</td>
									<td class="Respuestas" width="95%"><input class="InputText" id="Observacion<?= $row2["OP_ID"]?>" name="Observacion<?= $row2["OP_ID"]?>" size="60" type="text"></td>
								</tr>
<?
		}
	}
?>
								<tr>
									<td colspan="2" height="8"></td>
								</tr>
							</table>
						</td>
					</tr>
<?
}
?>
					<tr id="trSiguiente">
						<td align="center" class="Respuestas" height="29"><input class="InputSubmit" id="btnSiguiente" name="btnSiguiente" type="button" value="Siguiente" onClick="siguiente()"></td>
					</tr>
					<tr id="trEnviar" style="display:none">
						<td align="center" class="Respuestas" height="29"><input class="InputSubmit" id="btnEnviar" name="btnEnviar" type="button" value="Enviar" onClick="enviar()"></td>
					</tr>
					<tr>
						<td width="100%" height="8"></td>
					</tr>
				</table>
				<table border="0" cellpadding="0" cellspacing="0" id="tableAgradecimiento" width="750" style="display:none">
					<tr>
						<td class="Respuestas" width="97%" height="8">
							<table border="0" cellspacing="0" cellpadding="0" height="376" width="100%">
								<tr>
									<td width="43%"><p align="right"><img border="0" src="images/logo_ART.jpg" width="115" height="44"></td>
									<td class="Pie" width="57%">Le agradece su tiempo y colaboración.</td>
								</tr>
								<tr>
									<td width="100%" colspan="2" height="5" bgcolor="#C0C0C0"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="0" cellspacing="0" id="tableYaVoto" width="750" style="display:none">
					<tr>
						<td class="Respuestas" width="97%" height="8">
							<table border="0" cellspacing="0" cellpadding="0" height="376" width="100%">
								<tr>
									<td width="43%"><p align="right"><img border="0" src="images/logo_ART.jpg" width="115" height="44"></td>
									<td class="Pie" width="57%">Usted ya ha participado en la encuesta.</td>
								</tr>
								<tr>
									<td width="100%" colspan="2" height="5" bgcolor="#C0C0C0"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</form>
		<script>
<?
$params = array(":usuario" => GetUserID());
$sql =
	"SELECT 1
		 FROM rrhh.rrp_respuestaspreguntas
		WHERE rp_idencuesta = 1
			AND rp_usuario = :usuario
			AND rp_fechabaja IS NULL";
if ((ExisteSql($sql, $params)) and (!isset($_REQUEST["std"]))) {
?>
	document.getElementById('tableYaVoto').style.display = 'block';
	document.getElementById('tablePreguntas').style.display = 'none';
	document.getElementById('tableAgradecimiento').style.display = 'none';
<?
}
else {
?>
	document.getElementById('tableYaVoto').style.display = 'none';
	document.getElementById('table1').style.display = 'block';
	document.getElementById('tablePreguntas').style.display = '<?= (!isset($_REQUEST["std"]))?"block":"none"?>';
	document.getElementById('tableAgradecimiento').style.display = '<?= (isset($_REQUEST["std"]))?"block":"none"?>';
<?
}
?>
		</script>
	</body>
</html>