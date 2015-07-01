<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function formatPeriodo($periodo) {
	return substr($periodo, 0, 4)."/".substr($periodo, -2);
}


validarSesion(isset($_SESSION["isAgenteComercial"]));
SetNumberFormatOracle();

$paginaAnterior = $_SESSION["paginaAnterior"];

$alta = !isset($_REQUEST["id"]);
if (!$alta) {
	$id = substr($_REQUEST["id"], 1);
	$modulo = strtoupper(substr($_REQUEST["id"], 0, 1));

	$curs = null;
	$params = array(":modulo" => $modulo, ":id" => $id);
	$sql = "BEGIN webart.get_solicitud_cotizacion(:data, :modulo, :id); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs);
	if ($modulo == "R") {		// Si es una revisión de precio..
		$calculoSumaFija = $row["TOTALFIJOCOMPETENCIA"];
		$calculoVariable = "";
		$resultadoMensualPorTrabajador = $row["COSTOFINALCOMPETENCIA"];

		if ($row["TOTALVARIABLECOMPETENCIA"] != "")
			$calculoVariable = $row["TOTALVARIABLECOMPETENCIA"]." %";
	}
	else {		// Sino tiene que ser una solicitud de cotización..
		$calculoSumaFija = "";
		$calculoVariable = "";
		$resultadoMensualPorTrabajador = "";
		switch ($row["FORM931"]) {
			case "A":
				$calculoSumaFija = $row["PAGOMENSUAL2"];
				$calculoVariable = $row["COSTOVARIABLEPAGOMENSUAL"]." %";
				$resultadoMensualPorTrabajador = $row["COSTOFINALPAGOMENSUAL"];
				break;
			case "N":
				$calculoSumaFija = $row["COSTOFIJOCOMPETENCIA2"];
				$calculoVariable = $row["COSTOVARIABLECOMPETENCIA"]." %";
				$resultadoMensualPorTrabajador = $row["COSTOFINAL"];
				break;
			case "S":
				$calculoSumaFija = $row["COSTOFIJO9312"];
				$calculoVariable = $row["COSTOVARIABLE9312"]." %";
				$resultadoMensualPorTrabajador = $row["COSTOFINALFORM931"];
				break;
		}

		$curs = null;
		$params = array(":nrosolicitud" => $row["NROSOLICITUD"]);
		$sql = "BEGIN art.cotizacion.get_valor_carta(:nrosolicitud, :data); END;";
		$stmt = DBExecSP($conn, $curs, $sql, $params);
		$rowValorFinal = DBGetSP($curs);
	}

	validarAccesoCotizacion($_REQUEST["id"]);

	if (strpos($paginaAnterior, "pageid=28"))
		$paginaAnterior = "/index.php?pageid=27&buscar=yes&numero=".$row["NROSOLICITUD"];
}


if ($alta)
	$mostrarDescuento = false;
else {
	$mostrarDescuento = (($row["ESTADO"] == "04") or ($row["ESTADO"] == "06"));
	if ($modulo == "C") {
		// Si es una solicitud del banco provincia, menor a 60 días, no paso por técnica, no tiene descuento, tiene cotización y no tiene afiliación..
		$params = array(":id" => $id, ":usuario" => "W_".$_SESSION["usuario"]);
		$sql =
			"SELECT art.cotizacion.get_descuento(sc_cuit, sc_canttrabajador, sc_idactividad, :usuario)
				FROM asc_solicitudcotizacion
			 WHERE sc_identidad = 9003
				  AND sc_fechavigencia - SYSDATE <= 60
				  AND sc_idcotizacion IS NULL
				  AND CASE WHEN sc_porcdescuento is null then 0 
									WHEN sc_porcdescuento = -1 then 0 
									ELSE sc_porcdescuento
						  END = 0
				  AND sc_finalporcmasa >= 0
				  AND sc_idformulario IS NULL
				  AND sc_id = :id";
		$mostrarDescuento = (($mostrarDescuento) and (floatval(ValorSql($sql, 0, $params)) > 0));
	}
}
?>
<script src="/modules/solicitud_cotizacion/js/cotizacion.js" type="text/javascript"></script>
<script>
	divWin = null;

	function showBuscarCiiuWin(destino) {
//		if ((divWin == null) || (divWin.style.display == 'none'))
			divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=600px,height=400px,left=280px,top=160px,resize=1,scrolling=1');

		divWin.load('iframe', '/modules/solicitud_cotizacion/buscar_ciiu.php?trgt=' + destino, 'Buscar Actividad');
		divWin.show();
	}

	function showEstablecimientoWindow(idsolicitud, id) {
		divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=600px,height=216px,left=280px,top=160px,resize=1,scrolling=1');

		if (id == -1)
			divWin.load('iframe', '/modules/solicitud_cotizacion/establecimiento.php?idsolicitud=' + idsolicitud + '&id=' + id, 'Agregar Establecimiento');
		else
			divWin.load('iframe', '/modules/solicitud_cotizacion/establecimiento.php?idsolicitud=' + idsolicitud + '&id=' + id, 'Modificar Establecimiento');
		divWin.show();
	}
</script>

<iframe id="iframeCiiu" name="iframeCiiu" src="" style="display:none;"></iframe>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/solicitud_cotizacion/procesar_cotizacion.php" id="formSolicitudCotizacion" method="post" name="formSolicitudCotizacion" target="iframeProcesando">
	<input id="artTmp" name="artTmp" type="hidden" value="<?= ($alta)?-1:$row["IDARTANTERIOR"]?>" />
	<input id="bajaPorDeuda" name="bajaPorDeuda" type="hidden" value="F" />
	<input id="descuento" name="descuento" type="hidden" value="<?= ($alta)?-1:$row["DESCUENTO"]?>" />
	<input id="id" name="id" type="hidden" value="<?= ($alta)?"":$id?>" />
	<input id="paginaAnterior" name="paginaAnterior" type="hidden" value="<?= $paginaAnterior?>" />
	<input id="statusSrtTmp" name="statusSrtTmp" type="hidden" value="<?= ($alta)?-1:$row["STATUSSRT"]?>" />
	<input id="topeDescuento" name="topeDescuento" type="hidden" value="<?= ($alta)?"":$row["TOPEDESCUENTO"]?>" />
	<table border="0" id="table1" width="736">
		<tr>
			<td colspan="2" style="padding-left: 12px; padding-right: 4px" bgcolor="#808080">
				<p style="margin-top: 0; margin-bottom: 0">
					<font face="Verdana" style="font-size: 8pt; font-weight: 700" color="#FFFFFF">Solicitud de Cotización</font>
<?
if (!$alta) {
?>
					<span><font face="Verdana" style="font-size: 9pt; font-weight:700" color="#FFFFFF"> - Nº <?= $row["NROSOLICITUD"]?></font></span>
<?
}
?>
				</p>
			</td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">CUIT (*)</td>
			<td><input class="input" id="cuit" maxlength="11" name="cuit" size="12" type="text" value="<?= ($alta)?"":$row["CUIT"]?>" onBlur="validarDatosCuit(<?= ($alta)?"true":"false"?>)"><img id="imgCuitLoading" src="/images/loading.gif" style="visibility:hidden; margin-left:8px; vertical-align:sub;" title="Buscando Status ante la SRT..." /></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">Razón Social (*)</td>
			<td><input class="input" id="razonSocial" maxlength="60" name="razonSocial" size="80" type="text" value="<?= ($alta)?"":$row["RAZONSOCIAL"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">Contacto (*)</td>
			<td><input class="input" id="contacto" maxlength="100" name="contacto" size="50" type="text" value="<?= ($alta)?"":$row["CONTACTO"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">Teléfono</td>
			<td><input class="input" id="telefono" maxlength="50" name="telefono" size="50" type="text" value="<?= ($alta)?"":$row["TELEFONO"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">e-Mail</td>
			<td><input class="input" id="email" maxlength="100" name="email" size="50" type="text" value="<?= ($alta)?"":$row["EMAIL"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">Holding</td>
			<td><select id="holding" name="holding" size="1" style="color: #676767; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></select></td>
		</tr>
	</table>
	<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
	<table border="0" id="tableActividad" width="736">
		<tr>
			<td colspan="5" style="padding-left: 12px; padding-right: 4px" bgcolor="#808080">
				<font face="Verdana" color="#FFFFFF"><span style="font-size: 8pt; font-weight: 700">Actividad</span></font>
			</td>
		</tr>
		<tr>
			<td bgcolor="#00A3E4" width="152" colspan="2">
				<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">Código</font>
			</td>
			<td bgcolor="#00A3E4" width="494" align="center">
				<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">Descripción</font>
			</td>
			<td bgcolor="#00A3E4" width="229">
				<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">Total de Trabajadores</font>
			</td>
			<td bgcolor="#00A3E4" width="90">
				<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">Masa Salarial</font>
			</td>
		</tr>
		<tr>
		<tr id="trActividad1">
			<td valign="top" width="68"><input class="input" id="ciiu1" maxlength="6" name="ciiu1" size="15" title="Código" type="text" value="<?= ($alta)?"":$row["CIIUCODIGO1"]?>" onBlur="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu1Descripcion', this.value)" onKeyUp="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu1Descripcion', this.value)"></td>
			<td valign="top" width="33"><font face="Trebuchet MS" size="1"><span style="font-size: 9pt"><img border="0" id="ciiu1Buscar" src="/modules/solicitud_cotizacion/images/lupa.gif" style="cursor:pointer;" onClick="showBuscarCiiuWin('ciiu1')"></span></font></td>
			<td valign="top" width="494"><font face="Trebuchet MS" style="font-size: 8pt"><span id="ciiu1Descripcion" name="ciiu1Descripcion"><?= ($alta)?"":$row["CIIUDESCRIPCION1"]?></span></font></td>
			<td valign="top" width="229"><input class="inputNumber" id="totalTrabajadores1" maxlength="12" name="totalTrabajadores1" size="20" type="text" value="<?= ($alta)?"":$row["CANTTRAB1"]?>" onBlur="sumarTrabajadores(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarTrabajadores(<?= ($alta)?"true":"false"?>)"></td>
			<td valign="top" width="90"><input class="inputNumber" id="masaSalarial1" maxlength="15" name="masaSalarial1" size="20" type="text" value="<?= ($alta)?"":$row["MASASALARIAL1"]?>" onBlur="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)"></td>
		</tr>
		<tr id="trActividad2" style="visibility:hidden">
			<td valign="top" width="68"><p style="margin-top: 0; margin-bottom: 0"><input class="input" id="ciiu2" maxlength="6" name="ciiu2" size="15" type="text" value="<?= ($alta)?"":$row["CIIUCODIGO2"]?>" onBlur="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu2Descripcion', this.value)" onKeyUp="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu2Descripcion', this.value)"></td>
			<td valign="top" width="33"><font face="Trebuchet MS" size="1"><span style="font-size: 9pt"><img border="0" id="ciiu2Buscar" src="/modules/solicitud_cotizacion/images/lupa.gif" style="cursor:pointer;" onClick="showBuscarCiiuWin('ciiu2')"></span></font></td>
			<td valign="top" width="494"><font face="Trebuchet MS" style="font-size: 8pt"><span id="ciiu2Descripcion" name="ciiu2Descripcion"><?= ($alta)?"":$row["CIIUDESCRIPCION2"]?></span></font></td>
			<td valign="top" width="229"><p style="margin-top: 0; margin-bottom: 0"><input class="inputNumber" id="totalTrabajadores2" maxlength="12" name="totalTrabajadores2" size="20" type="text" value="<?= ($alta)?"":$row["CANTTRAB2"]?>" onBlur="sumarTrabajadores(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarTrabajadores(<?= ($alta)?"true":"false"?>)"></td>
			<td valign="top" width="90"><p style="margin-top: 0; margin-bottom: 0"><input class="inputNumber" id="masaSalarial2" maxlength="15" name="masaSalarial2" size="20" type="text" value="<?= ($alta)?"":$row["MASASALARIAL2"]?>" onBlur="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)"></td>
		</tr>
		<tr id="trActividad3" style="visibility:hidden">
			<td valign="top" width="68"><p style="margin-top: 0; margin-bottom: 0"><input class="input" id="ciiu3" maxlength="6" name="ciiu3" size="15" type="text" value="<?= ($alta)?"":$row["CIIUCODIGO3"]?>" onBlur="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu3Descripcion', this.value)" onKeyUp="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu3Descripcion', this.value)"></td>
			<td valign="top" width="33"><font face="Trebuchet MS" size="1"><span style="font-size: 9pt"><img border="0" id="ciiu3Buscar" src="/modules/solicitud_cotizacion/images/lupa.gif" style="cursor:pointer;" onClick="showBuscarCiiuWin('ciiu3')"></span></font></td>
			<td valign="top" width="494"><font face="Trebuchet MS" style="font-size: 8pt"><span id="ciiu3Descripcion" name="ciiu3Descripcion"><?= ($alta)?"":$row["CIIUDESCRIPCION3"]?></span></font></td>
			<td valign="top" width="229"><p style="margin-top: 0; margin-bottom: 0"><input class="inputNumber" id="totalTrabajadores3" maxlength="12" name="totalTrabajadores3" size="20" type="text" value="<?= ($alta)?"":$row["CANTTRAB3"]?>" onBlur="sumarTrabajadores(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarTrabajadores(<?= ($alta)?"true":"false"?>)"></td>
			<td valign="top" width="90"><p style="margin-top: 0; margin-bottom: 0"><input class="inputNumber" id="masaSalarial3" maxlength="15" name="masaSalarial3" size="20" type="text" value="<?= ($alta)?"":$row["MASASALARIAL3"]?>" onBlur="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)"></td>
		</tr>
	</table>
<?
if ($alta) {
?>
	<table border="0" id="btnAgregarActividad" width="736">
		<tr>
			<td colspan="5">
				<p align="right" style="margin-top: 0; margin-bottom: 0">
				<font face="Trebuchet MS" size="1"><span style="font-size: 9pt"><input type="button" value="AGREGAR" class="botonGris" onClick="addActividad()"></span></font>
			</td>
		</tr>
	</table>
<?
}
?>
	<table border="0" width="736">
		<tr>
			<td colspan="3"></td>
			<td width="229"><p style="margin-left: 49px; margin-top: 0; margin-bottom: 0"><input class="inputNumber" id="totalTrabajadores" name="totalTrabajadores" size="20" type="text" value="<?= ($alta)?"":$row["CANTTRAB"]?>" readonly></td>
			<td width="90"><p style="margin-top: 0; margin-bottom: 0"><input class="inputNumber" id="masaSalarial" name="masaSalarial" size="20" type="text" value="<?= ($alta)?"":$row["MASASALARIALTOT2"]?>" readonly onChange="calcularMasaSalarialSinSac(true)"></td>
		</tr>
	</table>
	<table border="0" width="736">
		<tr>
			<td style="width:65px;"><p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" color="#676767" style="font-size: 8pt">Período</font></td>
			<td width="417"><p style="margin-top: 0; margin-bottom: 0"><input class="input" id="periodo" maxlength="7" name="periodo" size="10" type="text" useSeparator="true" value="<?= ($alta)?"":formatPeriodo($row["PERIODO"])?>" onBlur="calcularMasaSalarialSinSac(<?= ($alta)?"true":"false"?>)" onKeyUp="calcularMasaSalarialSinSac(true)"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt"> (AAAA/MM)</font></td>
			<td align="right" width="120"><p style="margin-right: 8px; margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt">Masa Salarial sin SAC</font></td>
			<td><p style="margin-left: 0px; margin-top: 0; margin-bottom: 0"><input class="inputNumber" id="masaSalarialSinSac" name="masaSalarialSinSac" size="20" type="text" value="<?= ($alta)?"":$row["MASASALARIALTOT2"]?>" readonly></td>
		</tr>
	</table>
	<table border="0" id="table14" width="736">
		<tr>
			<td width="65"><p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" color="#676767" style="font-size: 8pt">Act. Real</font></td>
			<td>
				<p style="margin-top: 0; margin-bottom: 0">
				<font face="Trebuchet MS" size="1"><span style="font-size: 9pt"><input class="input" id="actividadReal" maxlength="200" name="actividadReal" size="100" type="text" value="<?= ($alta)?"":$row["ACTIVIDADREAL"]?>"></span></font>
			</td>
		</tr>
	</table>
	<p align="left" style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
	<table id="table3" width="736">
		<tr>
			<td colspan="4" bgcolor="#808080" style="padding-left: 12px">
				<font color="#FFFFFF">
					<b><font face="Verdana" style="font-size: 8pt">Status SRT</font></b>
				</font>
			</td>
		</tr>
		<tr>
			<td width="14%"><p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt">Status ante SRT</font></td>
			<td width="37%">
				<p style="margin-top: 0; margin-bottom: 0">
				<select id="statusSrt" name="statusSrt" size="1" style="color: #676767; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" onChange="document.getElementById('statusSrtTmp').value = this.value;" disabled></select>
			</td>
			<td width="4%"><div id=divART1><p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt">ART</font></div></td>
			<td width="44%">
				<div id=divART2>
					<p style="margin-top: 0; margin-bottom: 0">
					<select id="art" name="art" size="1" style="color: #676767; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" onChange="document.getElementById('artTmp').value = this.value;"></select>
				</div>
			</td>
		</tr>
	</table>
	<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
	<table id="table13" width="736">
		<tr>
			<td colspan="2" bgcolor="#808080" style="padding-left: 12px">
			<font face="Verdana" style="font-size: 8pt; font-weight:700" color="#FFFFFF">Status BCRA</font></td>
		</tr>
		<tr>
			<td width="14%"><p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" color="#676767" style="font-size: 8pt">Status ante BCRA</font></td>
			<td width="85%">
				<div id="divPagoMensual0">
					<p style="margin-top: 0; margin-bottom: 0">
					<select id="statusBcra" name="statusBcra" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></select>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"><font face="Trebuchet MS" style="font-size: 8pt"><a href="http://www.bcra.gov.ar/cenries/cr010000.asp?error=0" target="_blank">www.bcra.gov.ar</a></font></td>
		</tr>
	</table>
	<p style="margin-top: 0; margin-bottom: 0">&nbsp;</font></p>
	<table id="table4" width="736">
		<tr>
			<td colspan="4" bgcolor="#808080" style="padding-left: 12px">
				<font face="Verdana" style="font-size: 8pt; font-weight:700" color="#FFFFFF">Datos de la competencia</font>
			</td>
		</tr>
		<tr>
			<td width="3%">
				<font face="Trebuchet MS">
					<span style="font-size: 8pt"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="" <?= ($alta)?"checked":($row["FORM931"] == "")?"checked":""?> /></span>
				</font>
			</td>
			<td width="24%"><font color="#676767" face="Trebuchet MS"><span style="font-size: 8pt">Sin Dato</span></font></td>
			<td width="72%" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="3%">
				<p style="margin-top: 0; margin-bottom: 0">
				<font face="Trebuchet MS">
					<span style="font-size: 8pt"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="A" <?= ((!$alta) and ($row["FORM931"] == "A"))?"checked":""?> /></span>
				</font>
			</td>
			<td width="24%"><font face="Trebuchet MS" color="#676767">
			  <label for="soloPagoTotalMensual"><span  style="font-size: 8pt">Solo pago total mensual</span></label>
			</font></td>
			<td width="72%" colspan="2">
				<p style="margin-top: 0; margin-bottom: 0">
				<input class="inputNumber" id="soloPagoTotalMensual" maxlength="15" name="soloPagoTotalMensual" size="12" type="text" value="<?= ($alta)?"":emptyIfZero($row["PAGOMENSUAL"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)">
			</td>
		</tr>
		<tr>
			<td width="3%">
				<p style="margin-top: 0; margin-bottom: 0">
				<font face="Trebuchet MS">
					<span style="font-size: 8pt"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="S" <?= ((!$alta) and ($row["FORM931"] == "S"))?"checked":""?> /></span>
				</font>
			</td>
			<td width="24%"><font face="Trebuchet MS" color="#676767">
			  <label for="formulario931CostoFijo"><span style="font-size: 8pt">Formulario 931</span></label>
			</font></td>
			<td width="72%" colspan="2">
				<div id="divForm931">
					<p style="margin-top: 0; margin-bottom: 0">
					<font face="Trebuchet MS" color="#676767"><span style="font-size: 8pt">Costo Fijo: </span></font>
					<font face="Trebuchet MS" color="#676767"><span style="font-size: 8pt"></span></font>	
					<input class="inputNumber" id="formulario931CostoFijo" maxlength="15" name="formulario931CostoFijo" size="12" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOFIJO931"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)">
					<font face="Trebuchet MS" color="#676767"><span style="font-size: 8pt">&nbsp;&nbsp;&nbsp; Costo Variable: </span></font>	
					<input class="inputNumber" id="formulario931CostoVariable" maxlength="17" name="formulario931CostoVariable" size="12" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOVARIABLE931"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)">
				</div>
			</td>
		</tr>
		<tr>
			<td width="3%">
				<font face="Trebuchet MS">
					<span style="font-size: 8pt"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="N" <?= ((!$alta) and ($row["FORM931"] == "N"))?"checked":""?> /></span>
				</font>
			</td>
			<td width="24%"><font face="Trebuchet MS" color="#676767"><span style="font-size: 8pt">Alícuota Competencia</span></font></td>
			<td width="72%" colspan="2">
				<font face="Trebuchet MS" color="#676767"><span style="font-size: 8pt">Suma Fija:&nbsp; </span></font>
				<input class="inputNumber" id="alicuotaCompetenciaSumaFija" maxlength="15" name="alicuotaCompetenciaSumaFija" size="12" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOFIJOCOMPETENCIA"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)">
				<font face="Trebuchet MS" color="#676767"><span style="font-size: 8pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Variable: </span></font>	
				<input class="inputNumber" id="alicuotaCompetenciaVariable" maxlength="17" name="alicuotaCompetenciaVariable" size="12" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOVARIABLECOMPETENCIA2"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)">
			</td>
		</tr>
		<tr>
			<td width="99%" colspan="4"></td>
		</tr>
		<tr>
			<td width="27%" colspan="2"><p align="right"><font face="Trebuchet MS" color="#676767"><span style="font-size: 8pt">Resultado Mensual por Trabajador:</span></font></td>
			<td width="24%"><input class="inputNumber" id="resultadoMensualPorTrabajador" maxlength=15" name="resultadoMensualPorTrabajador" size="12" type="text" value="<?= ($alta)?"":emptyIfZero($resultadoMensualPorTrabajador)?>" readonly></td>
			<td width="48%">
<?
if ($alta) {
?>
				<input type="button" value="CALCULAR" class="botonGris" onClick="calcularDatosCompetencia(<?= ($alta)?"true":"false"?>)">
<?
}
?>
			</td>
		</tr>
		<tr>
			<td width="27%" colspan="2"><p align="right"><font face="Trebuchet MS" color="#676767"><span style="font-size: 8pt">Suma Fija:</span></font></td>
			<td width="72%" colspan="2"><input class="inputNumber" id="calculoSumaFija" maxlength="15" name="calculoSumaFija" size="12" type="text" value="<?= ($alta)?"":emptyIfZero($calculoSumaFija)?>" readonly></td>
		</tr>
		<tr>
			<td width="27%" colspan="2" align="right"><font face="Trebuchet MS" color="#676767"><span style="font-size: 8pt">Variable: </span></font></td>
			<td width="72%" colspan="2"><input class="inputNumber" id="calculoVariable" maxlength="17" name="calculoVariable" size="12" type="text" value="<?= ($alta)?"":emptyIfZero($calculoVariable)?>" readonly></td>
		</tr>
	</table>
	<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
	<table border="0" id="table15" width="736">
		<tr>
			<td width="17%"><p style="margin-top: 0; margin-bottom: 0"><font face="Trebuchet MS" color="#676767" style="font-size: 8pt">Edad promedio</font></td>
			<td colspan="3">
				<p style="margin-top: 0; margin-bottom: 0">
				<input class="inputNumber" id="edadPromedio" maxlength="2" name="edadPromedio" size="12" type="text" value="<?= ($alta)?"35":emptyIfZero($row["EDADPROMEDIO"])?>">
			</td>
		</tr>
		<tr>
			<td width="17%"><p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt">Sector (*)</font></td>
			<td colspan="3" width="51%">
				<select id="sector" name="sector" size="1" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></select>
			</td>
		</tr>
		<tr>
			<td width="17%"><p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt">Cant. de establecimientos</font></td>
			<td colspan="3"><input class="inputNumber" id="cantidadEstablecimientos" maxlength="3" name="cantidadEstablecimientos" size="12" type="text" value="<?= ($alta)?"":emptyIfZero($row["ESTABLECIMIENTOS"])?>"></td>
		</tr>
		<tr>
			<td width="17%"><p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt">Zona Geográfica (*)</font></td>
			<td colspan="3"><select id="zonaGeografica" name="zonaGeografica" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"></select></td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td bgcolor="#808080" colspan="4" style="padding-left: 12px">
			<font face="Verdana" style="font-size: 8pt; font-weight:700" color="#FFFFFF">Establecimientos</font></td>
		</tr>
<?
$idSolicitud = -1;
$tipoSolicitud = 1;
$usuAlta = "W_".$_SESSION["usuario"];
if (!$alta) {
	$idSolicitud = $id;
	$tipoSolicitud = (($modulo == "R")?2:1);
	$usuAlta = $row["USUALTA"];
}

// Borro los establecimientos temporales que pudieran existir para el usuario actual..
$sql =
	"DELETE FROM afi.aeu_establecimientos
				WHERE eu_idsolicitud = -1
					 AND eu_usualta = :usualta
					 AND eu_usuarioweb = 'T'";
$params = array(":usualta" => "W_".$_SESSION["usuario"]);
DBExecSql($conn, $sql, $params);
?>
		<tr>
			<td colspan="4">
				<iframe frameborder="0" id="iframeEstablecimientos" name="iframeEstablecimientos" src="/modules/solicitud_cotizacion/establecimientos.php?idsolicitud=<?= $idSolicitud?>&tiposolicitud=<?= $tipoSolicitud?>&usualta=<?= $usuAlta?>" style="height:240px; width:706px;"></iframe>
			</td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td bgcolor="#808080" colspan="4" style="padding-left: 12px">
			<font face="Verdana" style="font-size: 8pt; font-weight:700" color="#FFFFFF">Observaciones (máximo 2048 caracteres)</font></td>
		</tr>
		<tr>
			<td colspan="4"><textarea class="input" cols="1" id="observaciones" name="observaciones" rows="5" style="width:696px"><?= ($alta)?"":$row["OBSERVACIONES"]?></textarea></td>
		</tr>
		<tr>
<?
if (($_SESSION["entidad"] == 9003) and ($_SESSION["vendedor"] == "")) {
	if (!$alta) {
		$sql =
			"SELECT ve_nombre, ve_vendedor
				FROM xve_vendedor
			  WHERE ve_id = :id";
		$params = array(":id" => nullIsEmpty($row["IDVENDEDOR"]));
		$stmtVend = DBExecSql($conn, $sql, $params);
		$rowVend = DBGetQuery($stmtVend);
		$vendedor = $rowVend["VE_VENDEDOR"];
		$nombreVendedor = $rowVend["VE_NOMBRE"];
	}
?>
			<td colspan="4">
				<font color="#676767" face="Trebuchet MS" style="font-size: 8pt">Código de Vendedor</font>
				<input class="input" id="codigoVendedor" maxlength="10" name="codigoVendedor" size="10" type="text" value="<?= ($alta)?"":$vendedor?>" onKeyUp="getVendedor(this.value)">
				<font color="#676767" face="Trebuchet MS" style="font-size: 8pt"><span id="vendedor"><?= ($alta)?"":$nombreVendedor?></span></font>
			</td>
<?
}
?>
		</tr>
	</table>
	<p style="margin-bottom:0; margin-top:0;">&nbsp;</p>
	<table id="tableValoresFinales" name="tableValoresFinales" style="display:<?= ((!$alta) and (($row["ESTADO"] == "04") or ($row["ESTADO"] == "06")))?"block":"none"?>" width="736">
		<tr>
			<td align="center" class="ContenidoSeccion" colspan="2">
				<div style="background-color:#808080; border:solid 1px #c0c0c0; color:#fff; font-weight:700; margin-left:-12px; padding-bottom:4px; padding-top:4px; width:103%;">VALORES COTIZADOS POR PROVINCIA ART</div>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table border="0" cellpadding="0" width="100%" id="table11" cellspacing="1">
					<tr>
						<td colspan="3" align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" bgcolor="#808080">
							<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#FFFFFF">TRABAJADORES</font>
						</td>
					</tr>
					<tr>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="30%">
							<p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">Cantidad</font></p>
							<p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">(a)</font>
						</td>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="29%">
							<p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">Masa Salarial</font></p>
							<p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">(b)</font>
						</td>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="20%">
							<p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">Mes/Año</font>
						</td>
					</tr>
					<tr>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0">
							<font face="Trebuchet MS" size="1">
								<span style="font-size: 9pt">
									<input class="inputNumber" id="trabajadoresCantidad" name="trabajadoresCantidad" size="10" style="border-left:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-top:1px solid #FFFFFF; border-bottom:1px solid #808080; font-size: 9; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#000080" type="text" value="<?= ($alta)?"":$row["CANTTRAB"]?>" readonly>
								</span>
							</font>
						</td>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0">
							<font face="Trebuchet MS" size="1">
								<span style="font-size: 9pt">
									<input class="inputNumber" id="trabajadoresMasaSalarial" name="trabajadoresMasaSalarial" size="10" style="border-left:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-top:1px solid #FFFFFF; border-bottom:1px solid #808080; font-size: 9; padding-left: 2px; padding-right: 20px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#000080;" type="text" value="<?= ($alta)?"":$row["MASASALARIAL"]?>" readonly>
								</span>
							</font>
						</td>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0">
							<font face="Trebuchet MS" size="1">
								<span style="font-size: 9pt">
									<input class="input" id="trabajadoresMesAno" name="trabajadoresMesAno" size="6" style="border-left:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-top:1px solid #FFFFFF; border-bottom:1px solid #808080; font-size: 9; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#000080" type="text" value="<?= ($alta)?"":formatPeriodo($row["PERIODO"])?>" readonly>
								</span>
							</font>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table border="0" cellpadding="0" width="100%" id="table12" cellspacing="1">
					<tr>
						<td colspan="4" align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" bgcolor="#808080">
							<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#FFFFFF">ALÍCUOTAS</font>
						</td>
					</tr>
					<tr>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="29%">
							<p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">% sobre Masa Salarial (c)</font></p>
						</td>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="6%">
							<p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">Fijo</font></p>
							<p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">(d)</font>
						</td>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="9%">
							<p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">F.F.E.P.</font>
						</td>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="43%">
							<p style="margin-top: 0; margin-bottom: 0"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt; font-weight: 700">Cuota inicial resultante</font></p>
							<p style="margin-top: 0; margin-bottom: 0"><span style="font-weight: 700"><font color="#676767" face="Trebuchet MS" style="font-size: 8pt">(bxc) + (axd) + (axf.f.e.p.)</font></span>
						</td>
					</tr>
					<tr>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="29%">
							<font face="Trebuchet MS" size="1">
								<span style="font-size: 9pt">
									<input class="inputNumber" id="alicuotasMasaSalarial" name="alicuotasMasaSalarial" size="10" style="border-left:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-top:1px solid #FFFFFF; border-bottom:1px solid #808080; font-size: 9; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#000080" type="text" value="<?= ($alta)?"":$rowValorFinal["PORCVARIABLE"]?>" readonly>
								</span>
							</font>
						</td>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="6%">
							<font face="Trebuchet MS" size="1">
								<span style="font-size: 9pt">
									<input class="inputNumber" id="alicuotasFijo" name="alicuotasFijo" size="5" style="border-left:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-top:1px solid #FFFFFF; border-bottom:1px solid #808080; font-size: 9; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#000080" type="text" value="<?= ($alta)?"":$rowValorFinal["SUMAFIJA"]?>" readonly>
								</span>
							</font>
						</td>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="9%">
							<font face="Trebuchet MS" size="1">
								<span style="font-size: 9pt">
									<input class="inputNumber" id="alicuotasFfep" name="alicuotasFfep" size="6" style="border-left:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-top:1px solid #FFFFFF; border-bottom:1px solid #808080; font-size: 9; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#000080; text-align:center;" type="text" value="$ 0,60" readonly>
								</span>
							</font>
						</td>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" width="43%">
							<font face="Trebuchet MS" size="1">
								<span style="font-size: 9pt">
									<input class="inputNumber" id="alicuotasCuotaInicial" name="alicuotasCuotaInicial" size="16" style="border-left:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-top:1px solid #FFFFFF; border-bottom:1px solid #808080; font-size: 9; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#000080" type="text" value="<?= ($alta)?"":$rowValorFinal["COSTOMENSUAL"]?>" readonly>
								</span>
							</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table id="tableDescuento" name="tableDescuento" style="display:<?= ($mostrarDescuento)?"block":"none"?>" width="736">
		<tr>
			<td>
				<table border="0" cellpadding="0" cellspacing="1" id="tableDescuento2" name="tableDescuento2" style="visibility:hidden"width="100%">
					<tr>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" bgcolor="#808080">
							<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#FFFFFF">Descuento<br /><span id="spanTope"></span>% Tope</font>
						</td>
					</tr>
					<tr>
						<td bordercolor="#C0C0C0" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
							<font face="Trebuchet MS" size="1">
								<span style="font-size: 9pt">
									<input class="inputNumber" id="descuentoValor" name="descuentoValor" size="5" style="border-left:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-top:1px solid #FFFFFF; border-bottom:1px solid #808080; font-size: 9; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#000080" type="text" value="0" onKeyUp="reemplazarPuntoXComa(this);">
								</span>
							</font>
							<input class="botonGris" id="btnCalcularDescuento" style="margin-left:16px;" type="button" value="Calcular" onClick="calcularDescuento(document);">
						</td>
					</tr>
				</table>
			</td>
			<td width="104"></td>
			<td valign="top" width="448">
				<table border="0" cellpadding="0" cellspacing="1" style="display:<?= ((!$alta) and ($row["DESCUENTO"] > 0))?"block":"none"?>" width="100%">
					<tr>
						<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#C0C0C0" bgcolor="#808080">
							<font face="Trebuchet MS" style="font-size: 8pt; font-weight: 700" color="#FFFFFF">Descuento Aplicado <span id="spanDescuento"><?= ($alta)?0:$row["DESCUENTO"]?></span>%</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<?
if (($_SESSION["entidad"] != 400) and ($_SESSION["entidad"] != 10891) and (($alta) or ((!$alta) and (($row["ESTADO"] == "04") or ($row["ESTADO"] == "06"))))) {
// Si (no es del Banco Nación) y (no es del CPCECABA) y ((es un alta) o (si no es un alta y no está finalizada))..
?>
	<div style="margin-left:4px; margin-top:8px; width:728px;">
		<div style="background-color:#00539b; height:36px; padding-left:12px;">
			<span face="Verdana" style="color:#fff; font-size:8pt; font-weight:700; vertical-align:-16px;">Responsabilidad Civil Patronal</span>
			<img src="/modules/solicitud_cotizacion/images/provincia_seguros.gif" style="float:right; height:34; width:85px;" />
		</div>
		<div class="ContenidoSeccion">
			<div style="margin-bottom:6px; margin-top:8px;">
				<span style="font-weight:700;">¿ Suscribe Póliza de Responsabilidad Civil Patronal ?</span>
				<label for="suscribePolizaRC" style="margin-left:16px;">SI</label>
				<input id="suscribePolizaRC" name="suscribePolizaRC" style="margin:0px; vertical-align:middle;" type="radio" value="S" <?= (($alta) or ($row["POLIZARC"] == "S"))?"checked":""?>>
				<label for="suscribePolizaRC" style="margin-left:16px;">NO</label>
				<input id="suscribePolizaRC" name="suscribePolizaRC" style="margin:0px; vertical-align:middle;" type="radio" value="N" <?= ((!$alta) and ($row["POLIZARC"] == "N"))?"checked":""?>>
			</div>
			<label>Selección Suma Asegurada</label>
			<p style="margin-left:16px;">
				<input <?= ((!$alta) and ($row["SUMAASEGURADARC"] == "250000"))?"checked":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" type="radio" value="250000" onClick="recalcularRC('<?= ($alta)?0:$_REQUEST["id"]?>', 250000)" />
				<label style="vertical-align:3px;">Hasta $250.000</label>
				<br />
				<input <?= ((!$alta) and ($row["SUMAASEGURADARC"] == "500000"))?"checked":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" type="radio" value="500000" onClick="recalcularRC('<?= ($alta)?0:$_REQUEST["id"]?>', 500000)" />
				<label style="vertical-align:3px;">Hasta $500.000</label>
				<br />
				<input <?= ((!$alta) and ($row["SUMAASEGURADARC"] == "1000000"))?"checked":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" type="radio" value="1000000" onClick="recalcularRC('<?= ($alta)?0:$_REQUEST["id"]?>', 1000000)" />
				<label style="vertical-align:3px;">Hasta $1.000.000</label>
			</p>
		</div>
<?
	if ((!$alta) and (($row["ESTADO"] == "04") or ($row["ESTADO"] == "06"))) {
?>
		<div class="ContenidoSeccion" style="height:0px; left:280px; position:relative; top:-68px; width:400px;">
			<label>Recalcular Póliza de Responsabilidad Civil Patronal</label>
			<p style="margin-top:16px;">
				<label>Póliza RC</label>
				<input class="input" id="polizaRC" name="polizaRC" readonly style="width:80px;" type="text" value="<?= $row["VALORRC"]?>" />
				<input class="botonGris" style="margin-left:24px;" type="button" value="Actualizar" onClick="actualizarRC('<?= $_REQUEST["id"]?>')" />
				<span id="spanActualizarOk" style="background-color:#82ddff; display:none; margin-top:4px; padding:2px; width:200px;">Datos actualizados correctamente.</span>
			</p>
		</div>
		<div class="ContenidoSeccion" style="left:280px; position:relative; width:432px;">
			<div align="center" style="background-color:#00539b; color:#fff; font-weight:700; padding-bottom:8px; padding-top:8px;">VALOR COTIZADO DE RESPONSABILIDAD CIVIL PATRONAL</div>
			<div align="center" style="color:#676767;">
				<div style="background-color:#fff; border-left:1px solid #676767; float:left; padding-bottom:2px; padding-top:2px; width:142px;">Alícuota variable</div>
				<div style="background-color:#fff; border-left:1px solid #676767; border-right:1px solid #676767; float:left; padding-bottom:2px; padding-top:2px; width:143px;">Masa salarial</div>
				<div style="background-color:#fff; border-right:1px solid #676767; float:left; padding-bottom:2px; padding-top:2px; width:143px;">Cuota inicial resultante</div>
			</div>
			<div align="center" style="position:relative;">
				<div style="background-color:#fff; border-bottom:1px solid #676767; border-left:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:142px;">
					<input class="inputNumber" id="alicuotaVariableRC" readonly size="16" style="border-left:1px solid #fff; border-right:1px solid #fff; border-top:1px solid #fff; border-bottom:1px solid #808080; font-size: 9; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #fff; color:#000080" type="text" value="<?= $row["VALORRCFORMATEADO"]?>" />
				</div>
				<div style="background-color:#fff; border-bottom:1px solid #676767; border-left:1px solid #676767; border-right:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:143px;">
					<input class="inputNumber" id="masaSalarialRC" readonly size="16" style="border-left:1px solid #fff; border-right:1px solid #fff; border-top:1px solid #fff; border-bottom:1px solid #808080; font-size: 9; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #fff; color:#000080" type="text" value="<?= $row["MASASALARIAL"]?>" />
				</div>
				<div style="background-color:#fff; border-bottom:1px solid #676767; border-right:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:143px;">
					<input class="inputNumber" id="cuotaInicialResultanteRC" readonly size="16" style="border-left:1px solid #fff; border-right:1px solid #fff; border-top:1px solid #fff; border-bottom:1px solid #808080; font-size: 9; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #fff; color:#000080" type="text" value="<?= $row["CUOTAINICIALRC"]?>" />
				</div>
			</div>
		</div>
<?
	}
?>
	</div>
<?
}
?>
	<table width="736">
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
				<font face="Trebuchet MS" size="1">
					<span style="font-size: 9pt">
<?
if (($alta) or ($mostrarDescuento)) {
?>
						<input class="botonGris" id="btnGuardar" type="button" value="OBTENER COTIZACIÓN" onClick="guardarSolicitud()">
						<img id="imgGuardando" src="/images/loading.gif" style="display:none; vertical-align:-4px;">
<?
	if ($alta) {
?>
						<input class="botonGris" style="margin-left:8px;" type="button" value="CANCELAR" onClick="window.location.href = '<?= $paginaAnterior?>'">
<?
	}
}

if (!$alta) {
?>
						<input class="botonGris" type="button" value="VOLVER" onClick="window.location.href = '<?= $paginaAnterior?>'">
<?
	$sql = "SELECT art.cotizacion.get_imprimircotizacion(:id, :modulo) FROM DUAL";
	$params = array(":id" => $id, ":modulo" => $modulo);
	if (ValorSql($sql, "", $params) == "T") {
?>
						<input class="botonGris" type="button" value="CARTA" onClick="window.location.href = '/index.php?pageid=29&id=<?= $_REQUEST["id"]?>'">
<?
	}

	$sql = "SELECT art.afiliacion.get_imprimirsolicitud(:id, :modulo) FROM DUAL";
	$params = array(":id" => $id, ":modulo" => $modulo);
	if (ValorSql($sql, "", $params) == "T") {
?>
						<input class="botonGris" type="button" value="SOLICITAR AFILIACIÓN" onClick="window.location.href = '/index.php?pageid=30&id=<?= $_REQUEST["id"]?>'">
<?
	}

	$sql =
		"SELECT 'T'
			FROM asa_solicitudafiliacion
		 WHERE sa_idformulario = :idformulario
			  AND sa_fecharecepcionsectorafi IS NULL";
	$params = array(":idformulario" => nullIsEmpty($row["IDFORMULARIO"]));
	$afiliacionImpresaYNoPresentada = ValorSql($sql, 'F', $params);

	if ($modulo == "R")		// Si es una revisión de precio..
		$sql =
			"SELECT '00051-' || NVL(fo_cuit, uw_cuitsuscripcion) || '-' || fo_formulario
				FROM asr_solicitudreafiliacion, afo_formulario, afi.auw_usuarioweb
			 WHERE sr_idformulario = fo_id(+)
				  AND sr_idusuarioweb = uw_id(+)
				  AND sr_id = :id";
	else		// Si es una solicitud de cotización..
		$sql =
			"SELECT '00051-' || NVL(fo_cuit, uw_cuitsuscripcion) || '-' || fo_formulario
				FROM asc_solicitudcotizacion, afo_formulario, afi.auw_usuarioweb
			 WHERE sc_idformulario = fo_id(+)
				  AND sc_usuariosolicitud = uw_usuario(+)
				  AND sc_id = :id";
	$params = array(":id" => $id);
	$numeroAfiliacion = ValorSql($sql, "", $params);

	$sql = "SELECT art.cotizacion.get_anulacotizacion(:id, :modulo) FROM DUAL";
	$params = array(":id" => $id, ":modulo" => $modulo);
	if (ValorSql($sql, "", $params) == "T") {
?>
						<input class="botonGris" type="button" value="ANULAR" onClick="anularSolicitud('<?= $_REQUEST["id"]?>', '<?= $row["IDFORMULARIO"]?>', unescape('<?= rawurlencode($_SESSION["usuario"])?>'), '<?= $numeroAfiliacion?>', '<?= $row["CUIT"]?>', unescape('<?= rawurlencode($row["RAZONSOCIAL"])?>'), '<?= $afiliacionImpresaYNoPresentada?>', '<?= $row["VIGENTE"]?>')">
<?
	}
}
?>
					</span>
				</font>
			</td>
		</tr>
	</table>
</form>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "holding";
$RCparams = array();
$RCquery =
	"SELECT ge_id ID, ge_descripcion detalle
		FROM age_grupoeconomico
	  WHERE ge_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["HOLDING"];
FillCombo();

$RCfield = "statusSrt";
$RCparams = array();
$RCquery =
	"SELECT tb_codigo ID, tb_descripcion detalle
		FROM ctb_tablas
	  WHERE tb_clave = 'STSRT'
		  AND tb_codigo <> '0'
		  AND tb_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["STATUSSRT"];
FillCombo(true, 0, "Desconocido");

$RCfield = "art";
$RCparams = array();
$RCquery =
	"SELECT ar_id ID, ar_nombre detalle
    	FROM aar_art
     WHERE ar_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["IDARTANTERIOR"];
FillCombo();

$RCfield = "statusBcra";
$RCparams = array();
$RCquery =
	"SELECT DECODE(tb_codigo, -1, 0, tb_codigo) ID, tb_descripcion detalle
		FROM ctb_tablas
	 WHERE tb_clave = 'STBCR'
		  AND tb_codigo <> '0'
		  AND tb_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["STATUSBCRA"];
FillCombo();

$RCfield = "sector";
$RCparams = array();
$RCquery =
	"SELECT tb_codigo ID, tb_descripcion detalle
    	FROM ctb_tablas
   	 WHERE tb_clave = 'SECT'
     	 AND tb_codigo <> '0'
     	 AND tb_fechabaja IS NULL
ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["SECTOR"];
FillCombo();

$RCfield = "zonaGeografica";
$RCparams = array();
$RCquery =
	"SELECT zg_id ID, zg_descripcion detalle
		FROM afi.azg_zonasgeograficas
	 WHERE zg_fechabaja IS NULL
ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["ZONAGEOGRAFICA"];
FillCombo();
?>
	with (document) {
		if (getElementById('ciiu2').value != '')
			getElementById('trActividad2').style.visibility = 'visible';
		if (getElementById('ciiu3').value != '')
			getElementById('trActividad3').style.visibility = 'visible';

		lockControls(<?= ($alta)?"false":"true"?>);
		getElementById('cuit').focus();
<?
if ((isset($_REQUEST["i"])) and ($_REQUEST["i"] == "k")) {
?>
		getElementById('tableValoresFinales').style.backgroundColor = '#3ecaff';
<?
}


if ($mostrarDescuento) {
	$params = array(":canttrabajador" => $row["CANTTRAB"],
								":cuit" => $row["CUIT"],
								":idactividad" => $row["IDACTIVIDAD"],
								":usuario" => "W_".$_SESSION["usuario"]);
	$sql = "SELECT art.cotizacion.get_descuento(:cuit, :canttrabajador, :idactividad, :usuario) FROM dual";
	$tope = floatval(ValorSql($sql, 0, $params));
?>
		mostrarBotonGuardar(document);

		// Muestro los objetos relacionados con el descuento..
		getElementById('btnGuardar').value = 'GUARDAR';
		getElementById('spanTope').innerHTML = '<?= $tope?>';
		getElementById('tableDescuento').style.display = 'block';
		getElementById('tableDescuento2').style.visibility = 'visible';
		getElementById('tableValoresFinales').style.display = 'block';
		getElementById('topeDescuento').value = '<?= $tope?>';
		getElementById('descuentoValor').select();
		getElementById('descuentoValor').focus();

		// Calculo los valores..
		calcularDescuento(document);
<?
}
?>
	}
</script>
<?
$_SESSION["paginaAnterior"] = $_SERVER["REQUEST_URI"];
?>