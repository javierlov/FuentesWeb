<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once("index_combos.php");


function habilitarBotonGuardar() {
	global $rowEvaluado;
	global $rowJefe;

	$result = "";

	if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"]) {		// Si es el evaluado..
		if ($rowEvaluado["PL_IDESTADO"] != 1)
			$result = "style='visibility:hidden'";
	}
	elseif ($_SESSION["idUsuario"] == $rowJefe["PL_ID"]) {		// Si es el jefe..
		if ($rowEvaluado["PL_IDESTADO"] != 2)
			$result = "style='visibility:hidden'";
	}
	else {		// Si es rrhh..
		if ($rowEvaluado["PL_IDESTADO"] != 3)
			$result = "style='visibility:hidden'";
	}

	return $result;
}

function habilitarBotonNotificar() {
	global $rowEvaluado;
	global $rowJefe;

	$result = "";

	if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"]) {		// Si es el evaluado..
		if ($rowEvaluado["PL_IDESTADO"] != 4)
			$result = "style='visibility:hidden'";
	}
	elseif ($_SESSION["idUsuario"] == $rowJefe["PL_ID"]) {		// Si es el jefe..
		if ($rowEvaluado["PL_IDESTADO"] != 5)
			$result = "style='visibility:hidden'";
	}
	else		// Si es rrhh..
		$result = "style='visibility:hidden'";

	return $result;
}

function habilitarComentarioResponsable() {
	global $rowEvaluado;
	global $rowJefe;

	$result = "";

	if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"])		// Si es el evaluado..
		$result = "DISABLED";
	elseif ($_SESSION["idUsuario"] == $rowJefe["PL_ID"]) {		// Si es el jefe..
		if (($rowEvaluado["PL_IDESTADO"] != 2) and ($rowEvaluado["PL_IDESTADO"] != 5))
			$result = "DISABLED";
	}
	else		// Si es rrhh..
		if ($rowEvaluado["PL_IDESTADO"] != 3)
			$result = "DISABLED";

	return $result;
}

function habilitarComentarioUsuario() {
	global $rowEvaluado;

	$result = "";

	if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"]) {		// Si es el evaluado..
		if (($rowEvaluado["PL_IDESTADO"] != 1) and ($rowEvaluado["PL_IDESTADO"] != 4))
			$result = "DISABLED";
	}
	else		// Si es el jefe o rrhh..
		$result = "DISABLED";

	return $result;
}

function habilitarPrimeraSeccion() {
	global $rowEvaluado;
	global $rowJefe;

	$result = "";

	if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"]) {		// Si es el evaluado..
			$result = "DISABLED";
	}
	elseif ($_SESSION["idUsuario"] == $rowJefe["PL_ID"]) {		// Si es el jefe..
		if ($rowEvaluado["PL_IDESTADO"] != 2)
			$result = "DISABLED";
	}
	else {		// Si es rrhh..
		if ($rowEvaluado["PL_IDESTADO"] != 3)
			$result = "DISABLED";
	}

	return $result;
}

function habilitarSegundaSeccion() {
	global $rowEvaluado;
	global $rowJefe;

	$result = "";

	if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"]) {		// Si es el evaluado..
		if ($rowEvaluado["PL_IDESTADO"] != 1)
			$result = "DISABLED";
	}
	elseif ($_SESSION["idUsuario"] == $rowJefe["PL_ID"]) {		// Si es el jefe..
		if ($rowEvaluado["PL_IDESTADO"] != 2)
			$result = "DISABLED";
	}
	else {		// Si es rrhh..
		if ($rowEvaluado["PL_IDESTADO"] != 3)
			$result = "DISABLED";
	}

	return $result;
}

function habilitarTerceraSeccion() {
	global $rowEvaluado;
	global $rowJefe;

	$result = "";

	if ($_SESSION["idUsuario"] == $_SESSION["idEvaluado"]) {		// Si es el evaluado..
		$result = "DISABLED";
	}
	elseif ($_SESSION["idUsuario"] == $rowJefe["PL_ID"]) {		// Si es el jefe..
		if ($rowEvaluado["PL_IDESTADO"] != 2)
			$result = "DISABLED";
	}
	else {		// Si es rrhh..
		if ($rowEvaluado["PL_IDESTADO"] != 3)
			$result = "DISABLED";
	}

	return $result;
}

function mostrarEstado() {
	global $rowEvaluado;

	$result = "";

	if ((habilitarBotonGuardar() == "style='visibility:hidden'") and (habilitarBotonNotificar() == "style='visibility:hidden'"))
		$result = $rowEvaluado["ES_DETALLE"];

	return $result;
}


// Valido que se haya logueado..
if (!isset($_SESSION["idUsuario"])) {
	header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php");
	exit;
}

setDateFormatOracle("DD/MM/YYYY");

$params = array(":id" => $_SESSION["idEvaluado"]);
$sql =
	"SELECT dpl2.*
		 FROM rrhh.dpl_login dpl1, rrhh.dpl_login dpl2
 		WHERE dpl1.pl_rrhh = dpl2.pl_id
 			AND dpl1.pl_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$rowRrhh = DBGetQuery($stmt);

$params = array(":id" => $_SESSION["idEvaluado"]);
$sql =
	"SELECT dpl2.*, ge_detalle, pu_detalle
		 FROM rrhh.dpl_login dpl1, rrhh.dpl_login dpl2, rrhh.rpu_puestos, rrhh.rge_gerencias
 		WHERE dpl1.pl_jefe = dpl2.pl_id
 			AND dpl2.pl_puesto = pu_id(+)
 			AND dpl2.pl_gerencia = ge_id(+)
 			AND dpl1.pl_id = :id";
$stmt2 = DBExecSql($conn, $sql, $params);
$rowJefe = DBGetQuery($stmt2);

$params = array(":id" => $_SESSION["idEvaluado"]);
$sql =
	"SELECT rrhh.dpl_login.*, rrhh.dpm_mision.*, em_detalle, es_detalle, ge_detalle, pu_detalle
		 FROM rrhh.dpl_login, rrhh.dpm_mision, rrhh.rpu_puestos, rrhh.rge_gerencias, rrhh.res_estadossistemasgestion, rrhh.rem_empresas
		WHERE pl_id = pm_idlogin(+)
			AND pl_puesto = pu_id(+)
			AND pl_gerencia = ge_id(+)
			AND pl_idestado = es_id
			AND pl_empresa = em_id
			AND pl_id = :id";
$stmt3 = DBExecSql($conn, $sql, $params);
$rowEvaluado = DBGetQuery($stmt3);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Sistema de Gestión de RR.HH. ::..</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<link href="/modules/evaluacion_puesto/css/style.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			body {scrollbar-3dlight-color:#eee; scrollbar-arrow-color:#eee; scrollbar-darkshadow-color:#fff; scrollbar-face-color:#aaa; scrollbar-highlight-color:#aaa;
						scrollbar-shadow-color:#aaa; scrollbar-track-color:#e3e3e3;}
		</style>
		<script language="JavaScript" src="/js/functions.js"></script>
		<script language="JavaScript" src="../js/evaluacion_puesto.js"></script>
		<script language="JavaScript" src="../js/tareas.js"></script>
		<!-- INICIO POPUP -->
		<script type="text/javascript" src="/js/popup/dhtmlwindow.js"></script>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<!-- FIN POPUP -->
		<script type="text/javascript">
			divWin = null;

			function cerrarPopup() {
				divWin.close();
			}

			function inicial() {
				if (document.getElementById('pepemac').value == "76711059") {
					mostrar('capa1');
					mostrar('capa2');
					mostrar('capa3');
					mostrar('capa4');
				}
				else {
					ocultar('capa1');
					ocultar('capa2');
					ocultar('capa3');
					ocultar('capa4');
				}
			}

			function mostrar(nombreCapa) {
				document.getElementById(nombreCapa).style.display = "block";
			}

			function ocultar(nombreCapa) {
				document.getElementById(nombreCapa).style.display = "none";
			}

			window.parent.document.getElementById('volver').style.display = 'block';
		</script>
	</head>

<body onLoad="inicial()" link="#336699" vlink="#336699" alink="#336699" topmargin="3" bottommargin="3" leftmargin="0" rightmargin="0">
<iframe id="iframeProcesarTarea" name="iframeProcesarTarea" src="" style="display:none;"></iframe>
<form action="procesar_evaluacion.php" id="formEvaluacion" method="post" name="formEvaluacion">
<input id="modo" name="modo" type="hidden" value="" />
<table bgcolor="#FFFFFF" align="center" width="700">
	<tr>
		<td>
			<table bgcolor="#FFFFFF" align="center" cellspacing="0" width="700">
	<tr>
	<td width="377" height="25">
<table border="0" bgcolor="#FFFFFF" cellspacing="0" width="293">
	<tr>
		<td style="padding-left: 4px; padding-right: 4px" width="8">
			&nbsp;</td>
		<td style="border-left:1px solid #C0C0C0; border-top:1px solid #C0C0C0; border-bottom:1px solid #C0C0C0; padding-left: 4px; padding-right: 4px" bgcolor="#807F84" width="112">
			<p align="left"><span style="font-weight: 700">
			<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">OCUPANTE DEL PUESTO:</font></span><font face="Trebuchet MS">
			</font>
		</td>
		<td style="border-right:1px solid #C0C0C0; border-top:1px solid #C0C0C0; border-bottom:1px solid #C0C0C0; padding-left: 4px; padding-right: 4px; " bgcolor="#807F84"><?= $comboUsuarioAEvaluar->draw();?></td>
	</tr>
</table>

</td>
	<td align="left" width="375">

			<p><font face="Trebuchet MS" style="font-size: 9pt" color="#807F84">Período desde: </font>
			<font face="Trebuchet MS" style="font-size: 9pt"><?= $rowEvaluado["PL_FECHADESDE"]?></font><font face="Trebuchet MS" style="font-size: 9pt" color="#807F84">&nbsp; hasta: </font>
			<font face="Trebuchet MS" style="font-size: 9pt"><?= $rowEvaluado["PL_FECHAHASTA"]?></font><tr>
	<td width="752" height="10" colspan="2"></td>
	<tr>
	<td width="377" height="132" valign="top">

<table border="0" cellspacing="0" width="292">
	<tr>
		<td style="padding-left: 4px; padding-right: 4px" rowspan="6" width="7">&nbsp;</td>
		<td colspan="2" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#C0C0C0" bgcolor="#807F84">
			<span style="font-weight: 700">
				<font face="Trebuchet MS" color="#FFFFFF" style="font-size: 8pt">EMPRESA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $rowEvaluado["EM_DETALLE"]?></font>
			</span>
		</td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="97">
			<font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Nombre del puesto:</font>
		</td>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="164">
			<font face="Trebuchet MS" style="font-size: 8pt"><?= $rowEvaluado["PU_DETALLE"]?></font>
		</td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="97">
			<font face="Trebuchet MS" color="#808080" style="font-size: 8pt">Ocupante:</font>
		</td>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="164">
			<font face="Trebuchet MS" style="font-size: 8pt"><?= $rowEvaluado["PL_EMPLEADO"]?></font>
		</td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="97"><font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Gerencia:</font></td>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="164"><font face="Trebuchet MS" style="font-size: 8pt"><?= $rowEvaluado["GE_DETALLE"]?></font></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="97"><font face="Trebuchet MS" color="#808080" style="font-size: 8pt">Depto / Oficina:</font></td>
		<td style="border-bottom:1px dotted #CCCCCC; padding-left: 4px; padding-right: 4px" width="164"><font face="Trebuchet MS" style="font-size: 8pt"><?= $rowEvaluado["PL_DEPARTAMENTO"]?></font></td>
	</tr>
</table>

</td>
	<td align="left" width="375" valign="top">

<table border="0" cellspacing="0" width="295">
	<tr>
		<td colspan="2" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#C0C0C0" bgcolor="#807F84">
		<span style="font-weight: 700">
		<font face="Trebuchet MS" color="#FFFFFF" style="font-size: 8pt">DATOS DEL JEFE</font></span></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="94">
		<font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Nombre y Apellido:</font></td>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="185">
		<font face="Trebuchet MS" style="font-size: 8pt"><?= $rowJefe["PL_EMPLEADO"]?></font></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="94">
		<font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Puesto:</font></td>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="185">
		<font face="Trebuchet MS" style="font-size: 8pt"><?= $rowJefe["PU_DETALLE"]?></font></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="94">
		<font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Área/Sector:</font></td>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="185">
		<font face="Trebuchet MS" style="font-size: 8pt"><?= $rowJefe["PL_DEPARTAMENTO"]?></font></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="94">
		<font face="Trebuchet MS" style="font-size: 8pt" color="#808080">Gerencia:</font></td>
		<td style="border-bottom:1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px" width="185">
		<font face="Trebuchet MS" style="font-size: 8pt"><?= $rowJefe["GE_DETALLE"]?></font></td>
	</tr>
</table>


</table>	
<div align="center">
<table cellspacing="0" cellpadding="0" width="700" id="table16">
<tr>
	<td width="700" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#808080" bordercolor="#808080">
<p style="margin-left: 6px; margin-top:0; margin-bottom:0"><b>
<font face="Trebuchet MS" style="font-size: 9pt"><font color="#FFFFFF">1.</font>
</font><font face="Trebuchet MS" style="font-size: 9pt" color="#336699">
<a target="_self" href="javascript:mostrar('capa1')" onclick="mostrar('capa1');ocultar('capa2');ocultar('capa3');ocultar('capa4')" ondblclick="ocultar('capa1')" id="pepemac" onChange="inicial()">
<span style="text-decoration: none"><font color="#05459C">[+]</font></span></a>
</font><font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF">MISIÓN DEL PUESTO</font></b></td>
	</tr>
</table>
</div>

<div id='capa1'>
<table>
	<tr>
	<td width="700" height="5"></td>
	</tr>
	<tr>
	<td width="700">

<div class="Section1">
	<p style="text-indent: 0cm; margin: 0 8px" align="justify">
	<font face="Verdana" style="font-size: 8pt" color="#807F84">
	<span style="font-family: Trebuchet MS; font-style:italic">Describa la misión 
	principal del puesto. Se refiere al objetivo principal o responsabilidad 
	primaria del puesto. Aquí se debe expresar en forma sintética el motivo por 
	el cual el puesto existe en la compañia.</span></font></p>
	</div>
		</td>
	</tr>
<tr>
	<td width="700">
		<p style="text-indent: 0cm; margin: 0 8px" align="center">
			<textarea cols="130" id="mision" maxlength="4000" name="mision" rows="5" style="font-family: Trebuchet MS; font-size: 8pt; color: #808080" onKeyUp="return isMaxLength(this)" <?= habilitarPrimeraSeccion()?>><?= $rowEvaluado["PM_DESCRIPCION"]?></textarea>
		</p>
		<p style="text-indent: 0cm; margin: 0 8px" align="justify">
			<font face="Verdana" style="font-size: 8pt" color="#807F84">
				<a href="#" onClick="verRecomendacionesSeccion(1, 40, 640)">Recomendaciones y ejemplos</a>
			</font>
		</p>
	</td>
</tr>
<tr>
	<td width="700">&nbsp;</td>
	</tr>
	</table>
</div>
	
<div align="center">
<table cellspacing="0" cellpadding="0" width="700">
	<tr>
	<td width="700" style="padding-left: 4px; padding-right: 4px" height="5"></td>
	</tr>
	<tr>
	
<td width="700" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#808080" bordercolor="#808080">
<p style="margin-left: 6px; margin-top:0; margin-bottom:0"><b>
<font face="Trebuchet MS" style="font-size: 9pt"><font color="#FFFFFF">2.</font>
</font><font face="Trebuchet MS" style="font-size: 9pt" color="#336699">
<a target="_self" href="javascript:mostrar('capa2')" onclick="mostrar('capa2');ocultar('capa1');ocultar('capa3');ocultar('capa4')" id="pepemac" ondblclick="ocultar('capa2')" onChange="inicial()">
<span style="text-decoration: none"><font color="#05459C">[+]</font></span></a>
</font><font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF">DESCRIPCIÓN DE TAREAS / ÁREAS DE RESPONSABILIDAD</font></b></td>	
	</tr>
</table>	

</div>

<div id='capa2'>
<table>
	<tr>
	<td width="700" height="5"></td>
	</tr>
<tr>
	<td width="700">
	<p style="text-indent: 0cm; margin: 0 8px" align="justify">
	<font face="Verdana" style="font-size: 8pt" color="#807F84">
	<span style="font-family: Trebuchet MS; font-style:italic">Describa con verbos de acción lo que usted hace (acciones), para que lo hace (resultado final 
	esperado) y como lo hace (formas de medir los logros). Sus responsabilidades deben seguir un orden, desde la más importante a la menos importante.</span></font></p>
	</td>
	</tr>
<tr>
	<td width="700">
<table border="0" width="693" cellspacing="1" cellpadding="0" id="table20">
	<tr>
		<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" bgcolor="#807F84">
			<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">Acciones</font></td>
		<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" bgcolor="#807F84">
			<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">Resultado Final Esperado</font></td>
		<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" bgcolor="#807F84">
			<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">Como Medir los Logros</font></td>
	</tr>
	<tr>
		<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="249">
			<font face="Trebuchet MS" style="font-size: 8pt">(Qué hace)</font>
		</td>
		<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="249">
			<font face="Trebuchet MS" style="font-size: 8pt">(Para qué lo hace)</font>
		</td>
		<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="219">
			<font face="Trebuchet MS" style="font-size: 8pt">(Cómo se sabe lo que hizo)</font>
		</td>
	</tr>
	<tr>
		<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="249">
			<p align="center">
				<textarea cols="38" id="queHace" maxlength="2000" name="queHace" rows="5" style="font-family: Trebuchet MS; font-size: 8pt; color: #808080" onKeyUp="return isMaxLength(this)" <?= habilitarSegundaSeccion()?>></textarea>
			</p>
		</td>
		<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="249">
			<p align="center">
				<textarea cols="38" id="paraQueLoHace" maxlength="2000" name="paraQueLoHace" rows="5" style="font-family: Trebuchet MS; font-size: 8pt; color: #808080" onKeyUp="return isMaxLength(this)" <?= habilitarSegundaSeccion()?>></textarea>
			</p>
		</td>
		<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="219">
			<p align="center">
				<textarea cols="38" id="comoSeSabeLoQueHizo" maxlength="2000" name="comoSeSabeLoQueHizo" rows="5" style="font-family: Trebuchet MS; font-size: 8pt; color: #808080" onKeyUp="return isMaxLength(this)" <?= habilitarSegundaSeccion()?>></textarea>
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<p style="text-indent: 0cm; margin: 0 8px" align="justify">
				<font face="Verdana" style="font-size: 8pt" color="#807F84">
					<a href="#" onClick="verRecomendacionesSeccion(2, 0, 720)">Recomendaciones y ejemplos</a>
				</font>
			</p>
		</td>
	</tr>
</table>
	</td>
	</tr>
	<tr>
		<td width="700">
			<p align="center">
				<input id="btnAgregarAccion" name="btnAgregarAccion" type="button" value="AGREGAR ACCIÓN" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; cursor: pointer" onClick="agregarTarea()" <?= habilitarSegundaSeccion()?>>
				<input id="btnModificarAccion" name="btnModificarAccion" type="button" value="MODIFICAR ACCIÓN" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; cursor: pointer; display:none;" onClick="modificarTarea()" <?= habilitarSegundaSeccion()?>>
				<input id="btnCancelarModificacion" name="btnCancelarModificacion" type="button" value="CANCELAR MODIFICACIÓN" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; cursor: pointer; display:none;" onClick="cancelarTarea()" <?= habilitarSegundaSeccion()?>>
				<input id="btnEliminarRegistro" name="btnEliminarRegistro" type="button" value="ELIMINAR REGISTRO" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; cursor: pointer; display:none;" onClick="eliminarTarea()" <?= habilitarSegundaSeccion()?>>
			</p>
		</td>
	</tr>
	<tr>
		<td width="700"></td>
	</tr>
</table>
<iframe frameborder="no" height="0" id="iframeTareas" name="iframeTareas" scrolling="yes" src="tareas.php" width="716" onLoad="ajustarTamanoIframeTareas(this)"></iframe>
</div>
<div align="center">
<table cellspacing="0" cellpadding="0" width="700" id="table21">
<tr>
	<td width="700" style="padding-left: 4px; padding-right: 4px" height="5"></td>
	</tr>
<tr>
	<td width="700" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#808080" bordercolor="#808080">
<p style="margin-left: 6px; margin-top:0; margin-bottom:0"><b>
<font face="Trebuchet MS" style="font-size: 9pt"><font color="#FFFFFF">3.</font>
</font><font face="Trebuchet MS" style="font-size: 9pt" color="#336699">
<a target="_self" href="javascript:mostrar('capa3')" onclick="mostrar('capa3');ocultar('capa1');ocultar('capa2');ocultar('capa4')" ondblclick="ocultar('capa3')" id="pepemac" onChange="inicial()">
<span style="text-decoration: none"><font color="#05459C">[+]</font></span></a>
</font><font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF">CONOCIMIENTOS, HABILIDADES Y COMPETENCIAS</font></b></td>
	</tr>
</table>

</div>

<div id='capa3'>
<table>	
	<tr>
	<td width="700">

<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table23">
	<tr colspan="3">
		<td width="700" height="5"></td>
	</tr>
	<tr>
		<td colspan="3">
			<p style="margin-left: 6px; margin-top: 0; margin-bottom: 0">
				<font face="Verdana" style="font-size: 8pt" color="#807F84">
					<span style="font-family: Trebuchet MS; font-style:italic">Detalle las competencias, conocimientos y habilidades necesarias que considera requeridas para quien deba ocupar el puesto. Indique la opción que considere más apropiada.</span></font><font face="Trebuchet MS" style="font-size: 8pt" color="#807F84">
				</font>
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3" height="8"></td>
	</tr>
<?
$sql =
	"SELECT fc_detalle, fc_id
     FROM rrhh.dfc_factorconocimiento
 ORDER BY fc_id";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
	$params = array(":idfactorconocimiento" => $row["FC_ID"]);
	$sql =
		"SELECT COUNT(*)
			 FROM rrhh.dsc_subfactorconocimiento
			WHERE sc_idfactorconocimiento = :idfactorconocimiento";
	$totRecords = ValorSql($sql, "", $params);
?>
	<tr>
		<td rowspan="<?= $totRecords?>" align="center" bgcolor="#807F84" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="26%"><font style="FONT-SIZE: 8pt" face="Trebuchet MS" color="#ffffff"><?= $row["FC_DETALLE"]?></font></td>
<?
	$default = " ";
	if (habilitarTerceraSeccion() != "DISABLED")
		$default = "- Seleccione una opción -";

	$params = array(":idfactorconocimiento" => $row["FC_ID"], ":idlogin" => $_SESSION["idEvaluado"]);
	$sql =
		"SELECT NVL(pc_iditemconocimiento, -1) iditemconocimiento, NVL(pi_descripcion, ".addQuotes($default).") itemconocimiento, sc_detalle, sc_id
			 FROM rrhh.dsc_subfactorconocimiento, rrhh.dpc_conocimiento, rrhh.dpi_itemconocimiento
			WHERE sc_id = pc_idsubfactorconocimiento(+)
				AND pc_iditemconocimiento = pi_id(+)
				AND sc_idfactorconocimiento = :idfactorconocimiento
				AND pc_idlogin(+) = :idlogin
	 ORDER BY sc_id";
	$stmt2 = DBExecSql($conn, $sql, $params);
	$row2 = DBGetQuery($stmt2)
?>
		<td style="border-bottom: 1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="330"><font style="FONT-SIZE: 8pt" face="Trebuchet MS"><?= $row2["SC_DETALLE"]?></font></td>
		<td style="border-bottom: 1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="24%">
			<input id="idCombo<?= $row2["SC_ID"]?>" name="idCombo<?= $row2["SC_ID"]?>" type="hidden" value="<?= $row2["IDITEMCONOCIMIENTO"]?>" />
			<textarea id="combo<?= $row2["SC_ID"]?>" name="combo<?= $row2["SC_ID"]?>" readonly style="color:#808080; font-family:Trebuchet MS; font-size:8pt; border:1px solid #808080; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; width:256px;"><?= $row2["ITEMCONOCIMIENTO"]?></textarea><img class="btnSeleccionar" id="btn<?= $row2["SC_ID"]?>" style="vertical-align:top;" title="Seleccionar" onClick="abrirVentanaCombo(<?= $row2["SC_ID"]?>, <?= $row2["IDITEMCONOCIMIENTO"]?>, '<?= $row2["SC_DETALLE"]?>')" <?= habilitarTerceraSeccion()?> />
			<script type="text/javascript">resizeTextarea(document.getElementById('combo<?= $row2["SC_ID"]?>'));</script>
		</td>
	</tr>
<?
	echo "<script type='text/javascript'>";
	if ($row2["IDITEMCONOCIMIENTO"] == -1) {
?>
			with (document.getElementById('combo<?= $row2["SC_ID"]?>')) {
				style.backgroundColor = '#fee';
				style.fontStyle = 'italic';
			}
<?
	}
	if (habilitarTerceraSeccion() == "DISABLED") {
?>
			document.getElementById('btn<?= $row2["SC_ID"]?>').style.display = 'none';
<?
	}
	echo "</script>";

	while ($row2 = DBGetQuery($stmt2)) {
?>
	<tr>
		<td style="border-bottom: 1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="330"><font style="FONT-SIZE: 8pt" face="Trebuchet MS"><?= $row2["SC_DETALLE"]?></font></td>
		<td style="border-bottom: 1px dotted #C0C0C0; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="24%">
			<input id="idCombo<?= $row2["SC_ID"]?>" name="idCombo<?= $row2["SC_ID"]?>" type="hidden" value="<?= $row2["IDITEMCONOCIMIENTO"]?>" />
			<textarea id="combo<?= $row2["SC_ID"]?>" name="combo<?= $row2["SC_ID"]?>" readonly style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; width:256px;"><?= $row2["ITEMCONOCIMIENTO"]?></textarea><img class="btnSeleccionar" id="btn<?= $row2["SC_ID"]?>" style="vertical-align:top;" title="Seleccionar" onClick="abrirVentanaCombo(<?= $row2["SC_ID"]?>, <?= $row2["IDITEMCONOCIMIENTO"]?>, '<?= $row2["SC_DETALLE"]?>')" <?= habilitarTerceraSeccion()?> />
			<script type="text/javascript">resizeTextarea(document.getElementById('combo<?= $row2["SC_ID"]?>'));</script>
		</td>
	</tr>
<?
		echo "<script type='text/javascript'>";
		if ($row2["IDITEMCONOCIMIENTO"] == -1) {
?>
			with (document.getElementById('combo<?= $row2["SC_ID"]?>')) {
				style.backgroundColor = '#fee';
				style.fontStyle = 'italic';
			}
<?
		}
		if (habilitarTerceraSeccion() == "DISABLED") {
?>
			document.getElementById('btn<?= $row2["SC_ID"]?>').style.display = 'none';
<?
		}
		echo "</script>";
	}
?>
	<tr>
		<td colspan="3" height="8"></td>
	</tr>
<?
}
?>
</table>
	</td>
	</tr>
	<tr>
	<td width="700"></td>
	</tr>
</table>
	</div>	
<div align="center">
<table cellspacing="0" cellpadding="0" width="700" id="table24">
	<tr>
		<td width="700" style="padding-left: 4px; padding-right: 4px" height="5"></td>
	</tr>
	<tr>
		<td width="700" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#808080" bordercolor="#808080">
			<p style="margin-left: 6px; margin-top:0; margin-bottom:0">
				<b>
					<font face="Trebuchet MS" style="font-size: 9pt">
						<font color="#FFFFFF">4.</font>
					</font>
					<font face="Trebuchet MS" style="font-size: 9pt" color="#336699">
						<a target="_self" href="javascript:mostrar('capa4')" onclick="mostrar('capa4');ocultar('capa1');ocultar('capa2');ocultar('capa3')" ondblclick="ocultar('capa4')" id="pepemac" onChange="inicial()">
							<span style="text-decoration: none">
								<font color="#05459C">[+]</font>
							</span>
						</a>
					</font>
					<font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF">DIMENSIONES DEL PUESTO Y OTRAS INFORMACIONES</font>
				</b>
			</p>
		</td>
	</tr>
</table>
</div>

<div id='capa4'>
	<table>
		<tr>
			<td width="700" height="5"></td>
		</tr>
		<tr>
			<td width="700">
				<p style="margin: 0 8px" align="justify">
					<font face="Verdana" style="font-size: 8pt" color="#807F84">
						<span style="font-family: Trebuchet MS; font-style:italic">Describa cualquier información que le parezca oportuna.</span>
					</font>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<p style="margin-left: 45px">
					<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#807F84">DIMENSIONES DEL PUESTO:</font>
				</p>
			</td>
		</tr>
		<tr>
			<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
				<p style="margin-left: 45px">
					<font style="FONT-SIZE: 8pt" face="Trebuchet MS">Personal a cargo en forma directa:</font>
					<input id="personalCargoDirecta" maxlength="128" name="personalCargoDirecta" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" type="text" value="<?= $rowEvaluado["PM_PERSONALCARGODIRECTA"]?>" <?= habilitarComentarioResponsable()?> />
				</p>
			</td>
		</tr>
		<tr>
			<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
				<p style="margin-left: 45px">
					<font style="FONT-SIZE: 8pt" face="Trebuchet MS">Personal a cargo en forma indirecta:</font>
					<input id="personalCargoIndirecta" maxlength="128" name="personalCargoIndirecta" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" type="text" value="<?= $rowEvaluado["PM_PERSONALCARGOINDIRECTA"]?>" <?= habilitarComentarioResponsable()?> />
				</p>
			</td>
		</tr>
		<tr>
			<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
				<p style="margin-left: 45px">
					<font style="FONT-SIZE: 8pt" face="Trebuchet MS">Nivel de Autorización / Aprobación de gastos:</font>
					<input id="nivelAutorizacion" maxlength="128" name="nivelAutorizacion" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" type="text" value="<?= $rowEvaluado["PM_NIVELAUTORIZACION"]?>" <?= habilitarComentarioResponsable()?> />
					<font style="FONT-SIZE: 8pt" face="Trebuchet MS">(hasta $K)</font>
				</p>
			</td>
		</tr>
	</table>
	<table style="margin-top:8px;">
		<tr>
			<td width="700">
				<p style="margin-left: 45px">
					<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#807F84">COMENTARIOS OCUPANTE DEL PUESTO:</font>
				</p>
			</td>
		</tr>
		<tr>
			<td width="700" height="21">
				<p style="margin-left: 45px">
					<textarea cols="100" id="comentariosUsuario" maxlength="2048" name="comentariosUsuario" rows="4" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" onKeyUp="return isMaxLength(this)" <?= habilitarComentarioUsuario()?>><?= $rowEvaluado["PM_COMENTARIOUSUARIO"]?></textarea>
				</p>
			</td>
		</tr>
		<tr>
			<td width="700" height="5"></td>
		</tr>
		<tr>
			<td width="700">
				<p style="margin-left: 45px">
					<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#807F84">COMENTARIOS RESPONSABLE:</font>
				</p>
			</td>
		</tr>
		<tr>
			<td width="700" height="21">
				<p style="margin-left: 45px">
					<textarea cols="100" id="comentariosResponsable" maxlength="2048" name="comentariosResponsable" rows="4" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" onKeyUp="return isMaxLength(this)" <?= habilitarComentarioResponsable()?>><?= $rowEvaluado["PM_COMENTARIORESPONSABLE"]?></textarea>
				</p>
			</td>
		</tr>
	</table>
</div>
</td>
</tr>
	<tr>
		<td width="700" height="30"></td>
	</tr>
	<tr>
		<td width="700" height="21">
			<p style="margin-left: 45px">
				<input id="btnGuardar" name="btnGuardar" type="button" value="GUARDAR DESCRIPCIÓN" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF" onClick="guardarDescripcion()" <?= habilitarBotonGuardar()?>><font face="Trebuchet MS"></font>
				<input id="btnEnviarDescripcion" name="btnEnviarDescripcion" type="button" value="ENVIAR DESCRIPCIÓN" style="margin-left:80px; color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF" onClick="avisoEnvio()" <?= habilitarBotonGuardar()?>><font face="Trebuchet MS"></font>
				<input id="btnMeNotifique" name="btnMeNotifique" type="button" value="ME NOTIFIQUÉ" style="color: #877F87; font-family: Trebuchet MS; font-size: 8pt; font-weight: bold; border: 1px solid #877F87; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF" onClick="notificar()" <?= habilitarBotonNotificar()?>><font face="Trebuchet MS">	</font>
			</p>
		</td>
	</tr>
	<tr>
		<td align="center"><span id="estado"><?= mostrarEstado()?></span></td>
	</tr>
</table>
</form>
<form action="procesar_tarea.php" id="formTarea" method="post" name="formTarea" target="iframeProcesarTarea">
	<input id="tmpAccion" name="tmpAccion" type="hidden" value="A" />
	<input id="tmpId" name="tmpId" type="hidden" value="-1" />
	<input id="tmpQueHace" name="tmpQueHace" type="hidden" value="" />
	<input id="tmpParaQueLoHace" name="tmpParaQueLoHace" type="hidden" value="" />
	<input id="tmpComoSeSabeLoQueHizo" name="tmpComoSeSabeLoQueHizo" type="hidden" value="" />
</form>
<?
if (isset($_REQUEST["modoOk"])) {
	$msg = "";
	if ($_REQUEST["modoOk"] == "E")
		$msg = "Los datos fueron enviados correctamente.";
	if ($_REQUEST["modoOk"] == "G")
		$msg = "Los datos se guardaron correctamente.";
	if ($_REQUEST["modoOk"] == "N")
		$msg = "La notificación se ha procesado correctamente.";
?>
<div id="msgOk" name="msgOk">
	<p align="center" class="datosGuardados"><br>&nbsp;<br><b><?= $msg?></b></p>
</div>
<script type="text/javascript">
	medioancho = (screen.width - 600) / 2;
	medioalto = (screen.height - 400) / 2;
	divWin = dhtmlwindow.open('divBox', 'div', 'msgOk', 'Aviso', 'width=320px,height=40px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=0,scrolling=0');
	setTimeout('cerrarPopup()', 3000);
</script>
<?
}
?>
	</body>
</html>