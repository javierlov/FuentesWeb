<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


LogAccess(4);
$user = GetWindowsLoginName();

$params = array(":usuario" => $user);
$sql =
	"SELECT se_nombre
		 FROM use_usuarios
		WHERE UPPER(se_usuario) = UPPER(:usuario)";
$longUser = ValorSql($sql, "", $params);

$params = array(":usuario" => $user);
$sql =
	"SELECT 1
		 FROM rrhh.emm_encuestamandosmedios, use_usuarios
		WHERE mm_idusuario = se_id
			AND UPPER(se_usuario) = UPPER(:usuario)";
$esMandoMedio = ExisteSql($sql, $params);

if ($esMandoMedio) {
	$img = "titular2.jpg";
	$strMandoMedio = "S";
}
else {
	$img = "titular.jpg";
	$strMandoMedio = "N";
}

$params = array(":usuario" => $user);
$sql =
	"SELECT rg_idopcion1, rg_idopcion2, rg_idopcion3, rg_otros
		 FROM rrhh.erg_encuestareuniongte, use_usuarios
		WHERE rg_idusuario = se_id
			AND UPPER(se_usuario) = UPPER(:usuario)";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
		<title>Encuesta</title>
		<link href="/Styles/style.css" rel="stylesheet" type="text/css" />
		<link href="css/style.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="/Js/functions.js"></script>
		<script language="JavaScript" src="js/functions.js"></script>
	</head>
	<body class="Body">
		<form action="procesar.php" method="post">
			<div align="center">
				<table cellpadding="0" cellspacing="0" width="770">
					<tr>
						<td align="center">
							<div align="center">
								<table border="0" id="table1" cellspacing="0" cellpadding="0">
									<tr>
										<td><img border="0" height="51" src="/modules/encuestas/formacion_y_desarrollo/images/<?= $img?>" width="770"></td>
									</tr>
								</table>
							</div>
							<div align="center">
								<table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" id="table2" width="770">
									<tr>
										<td align="right" width="45"><img border="0" height="27" src="/modules/encuestas/formacion_y_desarrollo/images/usuario.jpg" width="32"></td>
										<td class="LabelGrisBold" width="100">Usuario Actual:</td>
										<td class="LabelNegro" width="546"><?= $longUser?></td>
										<td width="54"></td>
									</tr>
								</table>
							</div>
							<div>
<?
if ($esMandoMedio)
	require("texto_mandos_medios.htm");
else
	require("texto_otros.htm");
?>
							</div>
							<div align="center">
								<table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="770">
									<tr>
										<td colspan="3" height="12"></td>
									</tr>
									<tr>
										<td width="16"><img border="0" height="7" src="/modules/encuestas/formacion_y_desarrollo/images/viñeta.jpg" width="10"></td>
										<td class="LabelGris" width="80">Tema 1</td>
										<td><select class="ComboGris" id="Tema1" name="Tema1" size="1" onChange="SetOtros()"></select></td>
									</tr>
									<tr>
										<td><img border="0" height="7" src="/modules/encuestas/formacion_y_desarrollo/images/viñeta.jpg" width="10"></td>
										<td class="LabelGris">Tema 2</td>
										<td><select class="ComboGris" id="Tema2" name="Tema2" size="1" onChange="SetOtros()"></select></td>
									</tr>
									<tr>
										<td><img border="0" height="7" src="/modules/encuestas/formacion_y_desarrollo/images/viñeta.jpg" width="10"></td>
										<td class="LabelGris">Tema 3</td>
										<td><select id="Tema3" name="Tema3" size="1" class="ComboGris" onChange="SetOtros()"></select></td>
									</tr>
									<tr>
										<td valign="top"><img border="0" height="7" src="/modules/encuestas/formacion_y_desarrollo/images/viñeta.jpg" width="10"></td>
										<td class="LabelGris" valign="top">Otros</td>
										<td><textarea class="LabelGris" cols="35" id="Otros" name="Otros" rows="4"><?= $row["RG_OTROS"]?></textarea></td>
									</tr>
									<tr>
										<td colspan="3" height="8"></td>
									</tr>
									<tr>
										<td></td>
										<td></td>
										<td><input class="BotonGrabar" id="btnEnviar" name="btnEnviar" type="submit" value="Enviar"></td>
									</tr>
									<tr>
										<td colspan="3" height="16" width="770"></td>
									</tr>
								</table>
<?
if ($esMandoMedio)
	require("texto_mandos_medios_2.htm");
else
	require("texto_otros_2.htm");
?>
							</div>
						</td>
					</tr>
					<tr>
						<td bgcolor="#808080" height="4"></td>
					</tr>
				</table>
			</div>
		</form>
		<script>
<?
  // FillCombos..
  $excludeHtml = true;
  require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

  $RCwindow = "window";

  $RCfield = "Tema1";
  $RCparams = array(":mandosmedios" => $strMandoMedio);
  $RCquery = 
		"SELECT op_id id, op_descripcion DETALLE
			 FROM rrhh.eop_encuestaopcion
			WHERE op_mandosmedios = :mandosmedios
	 ORDER BY 2";
  $RCselectedItem = $row["RG_IDOPCION1"];
  FillCombo();

  $RCfield = "Tema2";
  $RCparams = array(":mandosmedios" => $strMandoMedio);
  $RCquery = 
		"SELECT op_id id, op_descripcion DETALLE
			 FROM rrhh.eop_encuestaopcion
			WHERE op_mandosmedios = :mandosmedios
	 ORDER BY 2";
  $RCselectedItem = $row["RG_IDOPCION2"];
  FillCombo();

  $RCfield = "Tema3";
  $RCparams = array(":mandosmedios" => $strMandoMedio);
  $RCquery = 
		"SELECT op_id id, op_descripcion DETALLE
			 FROM rrhh.eop_encuestaopcion
			WHERE op_mandosmedios = :mandosmedios
	 ORDER BY 2";
  $RCselectedItem = $row["RG_IDOPCION3"];
  FillCombo();
?>
			SetOtros();
			document.getElementById('Tema1').focus();
		</script>
	</body>
</html>