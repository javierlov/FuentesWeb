<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function formatPeriodo($periodo) {
	return substr($periodo, 0, 4)."/".substr($periodo, -2);
}


validarSesion(isset($_SESSION["isAgenteComercial"]));

$paginaAnterior = $_SESSION["paginaAnterior"];

$alta = !isset($_REQUEST["id"]);
if ($alta) {
	validarSesion(($_SESSION["altaCotizaciones"]));
}
else {
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


if ($alta) {
	$aumentoODescuentoAplicado = false;
	$mostrarAumento = false;
	$mostrarDescuento = false;
}
else {
	$aumentoODescuentoAplicado = (($row["AUMENTO"] > 0) or ($row["DESCUENTO"] > 0));
	$mostrarAumento = (($row["ESTADO"] == "04") or ($row["ESTADO"] == "06"));
	$mostrarDescuento = $mostrarAumento;

	// Aumento..
	if ($modulo == "C") {
		// Si es una solicitud del banco provincia, menor a 60 días, no paso por técnica, no tiene aumento, tiene cotización y no tiene afiliación..
		$params = array(":id" => $id, ":usuario" => "W_".$_SESSION["usuario"]);
		$sql =
			"SELECT art.cotizacion.get_aumento(sc_idactividad, sc_masasalarial, sc_canttrabajador, sc_finalsumafija, sc_finalporcmasa, :usuario)
				 FROM asc_solicitudcotizacion
				WHERE sc_identidad = 9003
					AND sc_fechavigencia - SYSDATE <= 60
					AND sc_idcotizacion IS NULL
					AND CASE WHEN sc_porcaumento IS NULL THEN 0
									 WHEN sc_porcaumento = -1 THEN 0
							ELSE sc_porcaumento
							END = 0
					AND sc_finalporcmasa >= 0
					AND sc_idformulario IS NULL
					AND sc_id = :id";
		$mostrarAumento = (($mostrarAumento) and (floatval(ValorSql($sql, 0, $params)) > 0));
	}

	// Descuento..
	if ($modulo == "C") {
		// Si es una solicitud del banco provincia, menor a 60 días, no paso por técnica, no tiene descuento, tiene cotización y no tiene afiliación..
		$params = array(":id" => $id, ":usuario" => "W_".$_SESSION["usuario"]);
		$sql =
			"SELECT art.cotizacion.get_descuento(sc_cuit, sc_canttrabajador, sc_idactividad, :usuario)
				 FROM asc_solicitudcotizacion
				WHERE sc_identidad = 9003
					AND sc_fechavigencia - SYSDATE <= 60
					AND sc_idcotizacion IS NULL
					AND CASE WHEN sc_porcdescuento IS NULL THEN 0
									 WHEN sc_porcdescuento = -1 THEN 0
							ELSE sc_porcdescuento
							END = 0
					AND sc_finalporcmasa >= 0
					AND sc_idformulario IS NULL
					AND sc_id = :id";
		$mostrarDescuento = (($mostrarDescuento) and (floatval(ValorSql($sql, 0, $params)) > 0));
	}
}
?>
<style>
	#divGridEspera {
		background-color: #0f539c;
		cursor: wait;
		display: none;
		filter: alpha(opacity = 20);
		height: 2400px;
		left: 0;
		opacity: .1;
		position: absolute;
		top: 0;
		width: 740px;
	}

	#divGridEsperaTexto {
		background-color: #fff;
		border: 1px solid #808080;
		color: #000;
		cursor: wait;
		display: none;
		font-family: Trebuchet MS;
		left: 228px;
		padding: 5px;
		position: absolute;
		top: 144px;
	}
</style>

<script src="/modules/solicitud_cotizacion/js/cotizacion.js?rnd=<?= date("Ymdhisu")?>" type="text/javascript"></script>
<script type="text/javascript">
	divWin = null;
</script>

<iframe id="iframeCiiu" name="iframeCiiu" src="" style="display:none;"></iframe>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<iframe id="iframeProcesando2" name="iframeProcesando2" src="" style="display:none;"></iframe>
<form action="/modules/solicitud_cotizacion/procesar_cotizacion.php" id="formSolicitudCotizacion" method="post" name="formSolicitudCotizacion" target="iframeProcesando">
	<input id="artTmp" name="artTmp" type="hidden" value="<?= ($alta)?-1:$row["IDARTANTERIOR"]?>" />
	<input id="aumento" name="aumento" type="hidden" value="<?= ($alta)?-1:$row["AUMENTO"]?>" />
	<input id="aumentoTopeF931" name="aumentoTopeF931" type="hidden" value="" />
	<input id="bajaPorDeuda" name="bajaPorDeuda" type="hidden" value="F" />
	<input id="campanaF931" name="campanaF931" type="hidden" value="<?= ($alta)?"S":"N"?>" />
	<input id="descuento" name="descuento" type="hidden" value="<?= ($alta)?-1:$row["DESCUENTO"]?>" />
	<input id="descuentoTopeF931" name="descuentoTopeF931" type="hidden" value="" />
	<input id="id" name="id" type="hidden" value="<?= ($alta)?"":$id?>" />
	<input id="idHolding" name="idHolding" type="hidden" value="<?= ($alta)?-1:$row["IDHOLDING"]?>" />
	<input id="paginaAnterior" name="paginaAnterior" type="hidden" value="<?= $paginaAnterior?>" />
	<input id="statusSrtAutomatico" name="statusSrtAutomatico" type="hidden" value="F" />
	<input id="statusSrtTmp" name="statusSrtTmp" type="hidden" value="<?= ($alta)?-1:$row["STATUSSRT"]?>" />
	<input id="topeAumento" name="topeAumento" type="hidden" value="<?= ($alta)?"":$row["TOPEAUMENTO"]?>" />
	<input id="topeDescuento" name="topeDescuento" type="hidden" value="<?= ($alta)?"":$row["TOPEDESCUENTO"]?>" />
	<table border="0" id="table1" width="736">
		<tr>
			<td colspan="2" style="padding-left:12px; padding-right:4px;" bgcolor="#808080">
				<p style="margin-top:0; margin-bottom:0;">
					<font face="Verdana" style="font-size:8pt; font-weight:700;" color="#FFFFFF">Solicitud de Cotización</font>
<?
if (!$alta) {
?>
					<span><font face="Verdana" style="font-size:9pt; font-weight:700;" color="#FFFFFF"> - Nº <?= $row["NROSOLICITUD"]?></font></span>
<?
}
?>
				</p>
			</td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" width="120">C.U.I.T. (*)</td>
			<td>
				<input id="cuit" maxlength="11" name="cuit" style="width:80px;" type="text" value="<?= ($alta)?"":$row["CUIT"]?>" onBlur="validarDatosCuit(<?= ($alta)?"true":"false"?>)" />
				<img id="imgCuitLoading" src="/images/loading.gif" style="margin-left:8px; vertical-align:sub; visibility:hidden;" title="Buscando Status ante la SRT..." />
			</td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">Razón Social (*)</td>
			<td><input id="razonSocial" maxlength="60" name="razonSocial" style="width:440px;" type="text" value="<?= ($alta)?"":$row["RAZONSOCIAL"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">Contacto (*)</td>
			<td><input id="contacto" maxlength="100" name="contacto" style="width:440px;" type="text" value="<?= ($alta)?"":$row["CONTACTO"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">Teléfono</td>
			<td><input id="telefono" maxlength="50" name="telefono" style="width:440px;" type="text" value="<?= ($alta)?"":$row["TELEFONO"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">e-Mail</td>
			<td><input id="email" maxlength="100" name="email" style="width:440px;" type="text" value="<?= ($alta)?"":$row["EMAIL"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">
				<span>Holding</span>
				<img border="0" id="holdingBuscar" name="holdingBuscar" src="/modules/solicitud_cotizacion/images/lupa.gif" style="cursor:pointer; vertical-align:-10px;" title="Buscar Holding" onClick="showBuscarHoldingWin()" />
			</td>
			<td><input id="holding" name="holding" readonly style="background-color:#ccc; width:440px;" type="text" value="<?= ($alta)?"":$row["HOLDING"]?>" /></td>
		</tr>
	</table>
	<p style="margin-bottom:0; margin-top:0;">&nbsp;</p>
	<table border="0" id="tableActividad" width="736">
		<tr>
			<td colspan="5" style="padding-left:12px; padding-right:4px;" bgcolor="#808080">
				<font face="Verdana" color="#FFFFFF"><span style="font-size:8pt; font-weight:700;">Actividad</span></font>
			</td>
		</tr>
		<tr>
			<td align="center" bgcolor="#0f539c" width="152" colspan="2">
				<font face="Trebuchet MS" style="font-size:8pt;" color="#FFFFFF">Código</font>
			</td>
			<td bgcolor="#0f539c" width="494" align="center">
				<font face="Trebuchet MS" style="font-size:8pt;" color="#FFFFFF">Descripción</font>
			</td>
			<td align="center" bgcolor="#0f539c" width="229">
				<font face="Trebuchet MS" style="font-size:8pt;" color="#FFFFFF">Total de Trabajadores</font>
			</td>
			<td align="center" bgcolor="#0f539c" width="90">
				<font face="Trebuchet MS" style="font-size:8pt;" color="#FFFFFF">Masa Salarial</font>
			</td>
		</tr>
		<tr>
		<tr id="trActividad1">
			<td valign="top" width="68"><input id="ciiu1" maxlength="6" name="ciiu1" style="width:104px;" title="Código" type="text" value="<?= ($alta)?"":$row["CIIUCODIGO1"]?>" onBlur="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu1Descripcion', this.value)" onKeyUp="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu1Descripcion', this.value)"></td>
			<td valign="top" width="33"><font face="Trebuchet MS"><span style="font-size:9pt;"><img border="0" id="ciiu1Buscar" src="/modules/solicitud_cotizacion/images/lupa.gif" style="cursor:pointer;" title="Buscar CIIU" onClick="showBuscarCiiuWin('ciiu1')"></span></font></td>
			<td valign="top" width="494"><font face="Trebuchet MS" style="font-size:8pt;"><span id="ciiu1Descripcion" name="ciiu1Descripcion"><?= ($alta)?"":$row["CIIUDESCRIPCION1"]?></span></font></td>
			<td align="center" valign="top" width="229"><input class="inputNumber" id="totalTrabajadores1" maxlength="12" name="totalTrabajadores1" style="width:104px;" type="text" value="<?= ($alta)?"":$row["CANTTRAB1"]?>" onBlur="sumarTrabajadores(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarTrabajadores(<?= ($alta)?"true":"false"?>)"></td>
			<td align="center" valign="top" width="90"><input class="inputNumber" id="masaSalarial1" maxlength="15" name="masaSalarial1" style="width:104px;" type="text" value="<?= ($alta)?"":$row["MASASALARIAL1"]?>" onBlur="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)"></td>
		</tr>
		<tr id="trActividad2" style="visibility:hidden;">
			<td valign="top" width="68"><p style="margin-bottom:0; margin-top:0;"><input id="ciiu2" maxlength="6" name="ciiu2" style="width:104px;" type="text" value="<?= ($alta)?"":$row["CIIUCODIGO2"]?>" onBlur="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu2Descripcion', this.value)" onKeyUp="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu2Descripcion', this.value)"></td>
			<td valign="top" width="33"><font face="Trebuchet MS"><span style="font-size:9pt;"><img border="0" id="ciiu2Buscar" src="/modules/solicitud_cotizacion/images/lupa.gif" style="cursor:pointer;" title="Buscar CIIU" onClick="showBuscarCiiuWin('ciiu2')"></span></font></td>
			<td valign="top" width="494"><font face="Trebuchet MS" style="font-size:8pt;"><span id="ciiu2Descripcion" name="ciiu2Descripcion"><?= ($alta)?"":$row["CIIUDESCRIPCION2"]?></span></font></td>
			<td align="center" valign="top" width="229"><p style="margin-bottom:0; margin-top:0;"><input class="inputNumber" id="totalTrabajadores2" maxlength="12" name="totalTrabajadores2" style="width:104px;" type="text" value="<?= ($alta)?"":$row["CANTTRAB2"]?>" onBlur="sumarTrabajadores(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarTrabajadores(<?= ($alta)?"true":"false"?>)"></td>
			<td align="center" valign="top" width="90"><p style="margin-bottom:0; margin-top:0;"><input class="inputNumber" id="masaSalarial2" maxlength="15" name="masaSalarial2" style="width:104px;" type="text" value="<?= ($alta)?"":$row["MASASALARIAL2"]?>" onBlur="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)"></td>
		</tr>
		<tr id="trActividad3" style="visibility:hidden;">
			<td valign="top" width="68"><p style="margin-bottom:0; margin-top:0;"><input id="ciiu3" maxlength="6" name="ciiu3" style="width:104px;" type="text" value="<?= ($alta)?"":$row["CIIUCODIGO3"]?>" onBlur="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu3Descripcion', this.value)" onKeyUp="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu3Descripcion', this.value)"></td>
			<td valign="top" width="33"><font face="Trebuchet MS"><span style="font-size:9pt;"><img border="0" id="ciiu3Buscar" src="/modules/solicitud_cotizacion/images/lupa.gif" style="cursor:pointer;" title="Buscar CIIU" onClick="showBuscarCiiuWin('ciiu3')"></span></font></td>
			<td valign="top" width="494"><font face="Trebuchet MS" style="font-size:8pt;"><span id="ciiu3Descripcion" name="ciiu3Descripcion"><?= ($alta)?"":$row["CIIUDESCRIPCION3"]?></span></font></td>
			<td align="center" valign="top" width="229"><p style="margin-bottom:0; margin-top:0;"><input class="inputNumber" id="totalTrabajadores3" maxlength="12" name="totalTrabajadores3" style="width:104px;" type="text" value="<?= ($alta)?"":$row["CANTTRAB3"]?>" onBlur="sumarTrabajadores(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarTrabajadores(<?= ($alta)?"true":"false"?>)"></td>
			<td align="center" valign="top" width="90"><p style="margin-bottom:0; margin-top:0;"><input class="inputNumber" id="masaSalarial3" maxlength="15" name="masaSalarial3" style="width:104px;" type="text" value="<?= ($alta)?"":$row["MASASALARIAL3"]?>" onBlur="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)"></td>
		</tr>
	</table>
<?
if ($alta) {
?>
	<table border="0" id="btnAgregarActividad" width="736">
		<tr>
			<td align="right" colspan="5"><input class="btnAgregar" type="button" value="" onClick="addActividad()" /></tr>
	</table>
<?
}
?>
	<table border="0" width="736">
		<tr>
			<td colspan="3"></td>
			<td align="center" width="229"><p style="margin-bottom:0; margin-left:54px; margin-top:0;"><input class="inputNumber" id="totalTrabajadores" name="totalTrabajadores" style="width:104px;" type="text" value="<?= ($alta)?"":$row["CANTTRAB"]?>" readonly></td>
			<td align="center" width="90"><p style="margin-bottom:0; margin-top:0;"><input class="inputNumber" id="masaSalarial" name="masaSalarial" style="width:104px;" type="text" value="<?= ($alta)?"":$row["MASASALARIALTOT2"]?>" readonly onChange="calcularMasaSalarialSinSac(true)"></td>
		</tr>
	</table>
	<table border="0" width="736">
		<tr>
			<td style="width:65px;"><p style="margin-bottom:0; margin-top:0;"><font face="Trebuchet MS" color="#676767" style="font-size:8pt;">Período</font></td>
			<td width="417"><p style="margin-bottom:0; margin-top:0;"><input id="periodo" maxlength="7" name="periodo" style="width:80px;" type="text" useSeparator="true" value="<?= ($alta)?"":formatPeriodo($row["PERIODO"])?>" onBlur="calcularMasaSalarialSinSac(<?= ($alta)?"true":"false"?>)" onKeyUp="calcularMasaSalarialSinSac(true)"><font color="#676767" face="Trebuchet MS" style="font-size:8pt;"> (AAAA/MM)</font></td>
			<td align="right" width="128"><p style="margin-bottom:0; margin-right:8px; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt;">Masa Salarial sin SAC</font></td>
			<td><p style="margin-bottom:0; margin-left:0px; margin-top:0;"><input class="inputNumber" id="masaSalarialSinSac" name="masaSalarialSinSac" style="width:104px;" type="text" value="<?= ($alta)?"":$row["MASASALARIALTOT2"]?>" readonly></td>
		</tr>
	</table>
	<table border="0" id="table14" width="736">
		<tr>
			<td width="65"><p style="margin-bottom:0; margin-top:0;"><font face="Trebuchet MS" color="#676767" style="font-size:8pt;">Act. Real</font></td>
			<td><input id="actividadReal" maxlength="200" name="actividadReal" style="width:652px;" type="text" value="<?= ($alta)?"":$row["ACTIVIDADREAL"]?>" /></td>
		</tr>
	</table>
	<p align="left" style="margin-bottom:0; margin-top:0;">&nbsp;</p>
	<table id="table3" width="736">
		<tr>
			<td colspan="4" bgcolor="#808080" style="padding-left:12px;">
				<font color="#FFFFFF">
					<b><font face="Verdana" style="font-size:8pt;">Status SRT</font></b>
				</font>
			</td>
		</tr>
		<tr>
			<td width="15%"><p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt;">Status ante SRT</font></td>
			<td width="37%">
				<p style="margin-bottom:0; margin-top:0;">
				<select id="statusSrt" name="statusSrt" onChange="document.getElementById('statusSrtTmp').value = this.value;"></select>
			</td>
			<td width="4%"><div id=divART1><p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt;">ART</font></div></td>
			<td width="44%">
				<div id=divART2>
					<p style="margin-bottom:0; margin-top:0;">
					<select id="art" name="art" onChange="document.getElementById('artTmp').value = this.value;"></select>
				</div>
			</td>
		</tr>
	</table>
	<p style="margin-bottom:0; margin-top:0;">&nbsp;</p>
	<table id="table13" width="736">
		<tr>
			<td colspan="2" bgcolor="#808080" style="padding-left:12px;">
			<font face="Verdana" style="font-size:8pt; font-weight:700;" color="#FFFFFF">Status BCRA</font></td>
		</tr>
		<tr>
			<td width="14%"><p style="margin-bottom:0; margin-top:0;"><font face="Trebuchet MS" color="#676767" style="font-size:8pt;">Status ante BCRA</font></td>
			<td width="85%">
				<div id="divPagoMensual0">
					<p style="margin-bottom:0; margin-top:0;">
					<select id="statusBcra" name="statusBcra"></select>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"><font face="Trebuchet MS" style="font-size:8pt;"><a class="linkSubrayado" href="http://www.bcra.gov.ar/cenries/cr010000.asp?error=0" target="_blank">www.bcra.gov.ar</a></font></td>
		</tr>
	</table>
	<p style="margin-bottom:0; margin-top:0;">&nbsp;</font></p>
	<table id="table4" width="736">
		<tr>
			<td colspan="4" bgcolor="#808080" style="padding-left:12px;">
				<font face="Verdana" style="font-size:8pt; font-weight:700;" color="#FFFFFF">Datos de la competencia</font>
			</td>
		</tr>
		<tr>
			<td width="3%">
				<font face="Trebuchet MS">
					<span style="font-size:8pt;"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="" <?= ($alta)?"checked":($row["FORM931"] == "")?"checked":""?> /></span>
				</font>
			</td>
			<td width="24%"><font color="#676767" face="Trebuchet MS"><span style="font-size:8pt;">Sin Dato</span></font></td>
			<td width="72%" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="3%">
				<p style="margin-bottom:0; margin-top:0;">
				<font face="Trebuchet MS">
					<span style="font-size:8pt;"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="A" <?= ((!$alta) and ($row["FORM931"] == "A"))?"checked":""?> /></span>
				</font>
			</td>
			<td width="24%"><font face="Trebuchet MS" color="#676767">
			  <label for="soloPagoTotalMensual"><span  style="font-size:8pt;">Solo pago total mensual</span></label>
			</font></td>
			<td width="72%" colspan="2">
				<p style="margin-bottom:0; margin-top:0;">
				<input class="inputNumber" id="soloPagoTotalMensual" maxlength="15" name="soloPagoTotalMensual" style="width:104px;" type="text" value="<?= ($alta)?"":emptyIfZero($row["PAGOMENSUAL"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)">
			</td>
		</tr>
		<tr>
			<td width="3%">
				<p style="margin-bottom:0; margin-top:0;">
				<font face="Trebuchet MS">
					<span style="font-size:8pt;"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="S" <?= ((!$alta) and ($row["FORM931"] == "S"))?"checked":""?> /></span>
				</font>
			</td>
			<td width="24%"><font face="Trebuchet MS" color="#676767">
			  <label for="formulario931CostoFijo"><span style="font-size:8pt;">Formulario 931</span></label>
			</font></td>
			<td width="72%" colspan="2">
				<div id="divForm931">
					<p style="margin-bottom:0; margin-top:0;">
					<font face="Trebuchet MS" color="#676767"><span style="font-size:8pt;">Costo Fijo </span></font>
					<font face="Trebuchet MS" color="#676767"><span style="font-size:8pt;"></span></font>	
					<input class="inputNumber" id="formulario931CostoFijo" maxlength="15" name="formulario931CostoFijo" style="width:104px;" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOFIJO931"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)">
					<font face="Trebuchet MS" color="#676767"><span style="font-size:8pt;">&nbsp;&nbsp;&nbsp; Costo Variable </span></font>	
					<input class="inputNumber" id="formulario931CostoVariable" maxlength="17" name="formulario931CostoVariable" style="width:104px;" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOVARIABLE931"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)">
				</div>
			</td>
		</tr>
		<tr>
			<td width="3%">
				<font face="Trebuchet MS">
					<span style="font-size:8pt;"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="N" <?= ((!$alta) and ($row["FORM931"] == "N"))?"checked":""?> /></span>
				</font>
			</td>
			<td width="24%"><font face="Trebuchet MS" color="#676767"><span style="font-size:8pt;">Alícuota Competencia</span></font></td>
			<td width="72%" colspan="2">
				<font face="Trebuchet MS" color="#676767"><span style="font-size:8pt;">Suma Fija&nbsp; </span></font>
				<input class="inputNumber" id="alicuotaCompetenciaSumaFija" maxlength="15" name="alicuotaCompetenciaSumaFija" style="width:104px;" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOFIJOCOMPETENCIA"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)">
				<font face="Trebuchet MS" color="#676767"><span style="font-size:8pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Variable </span></font>	
				<input class="inputNumber" id="alicuotaCompetenciaVariable" maxlength="17" name="alicuotaCompetenciaVariable" style="width:104px;" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOVARIABLECOMPETENCIA2"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)">
			</td>
		</tr>
		<tr>
			<td width="99%" colspan="4"></td>
		</tr>
		<tr>
			<td width="27%" colspan="2"><p align="right"><font face="Trebuchet MS" color="#676767"><span style="font-size:8pt;">Resultado Mensual por Trabajador</span></font></td>
			<td width="24%"><input class="inputNumber" id="resultadoMensualPorTrabajador" maxlength=15" name="resultadoMensualPorTrabajador" style="width:104px;" type="text" value="<?= ($alta)?"":emptyIfZero($resultadoMensualPorTrabajador)?>" readonly></td>
			<td width="48%">
<?
if ($alta) {
?>
				<input class="btnCalcular" type="button" value="" onClick="calcularDatosCompetencia(<?= ($alta)?"true":"false"?>)" />
<?
}
?>
			</td>
		</tr>
		<tr>
			<td width="27%" colspan="2"><p align="right"><font face="Trebuchet MS" color="#676767"><span style="font-size:8pt;">Suma Fija</span></font></td>
			<td width="72%" colspan="2"><input class="inputNumber" id="calculoSumaFija" maxlength="15" name="calculoSumaFija" style="width:104px;" type="text" value="<?= ($alta)?"":emptyIfZero($calculoSumaFija)?>" readonly></td>
		</tr>
		<tr>
			<td width="27%" colspan="2" align="right"><font face="Trebuchet MS" color="#676767"><span style="font-size:8pt;">Variable </span></font></td>
			<td width="72%" colspan="2"><input class="inputNumber" id="calculoVariable" maxlength="17" name="calculoVariable" style="width:104px;" type="text" value="<?= ($alta)?"":emptyIfZero($calculoVariable)?>" readonly></td>
		</tr>
	</table>
	<p style="margin-bottom:0; margin-top:0;">&nbsp;</p>
	<table border="0" id="table15" width="736">
		<tr>
			<td width="17%"><p style="margin-bottom:0; margin-top:0;"><font face="Trebuchet MS" color="#676767" style="font-size:8pt;">Edad promedio</font></td>
			<td colspan="3">
				<p style="margin-bottom:0; margin-top:0;">
				<input class="inputNumber" id="edadPromedio" maxlength="2" name="edadPromedio" style="width:40px;" type="text" value="<?= ($alta)?"35":emptyIfZero($row["EDADPROMEDIO"])?>">
			</td>
		</tr>
		<tr>
			<td width="17%"><p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt;">Sector (*)</font></td>
			<td colspan="3" width="51%"><select id="sector" name="sector"></select></td>
		</tr>
		<tr>
			<td width="17%"><p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt;">Cant. de establecimientos</font></td>
			<td colspan="3"><input class="inputNumber" id="cantidadEstablecimientos" maxlength="3" name="cantidadEstablecimientos" style="width:40px;" type="text" value="<?= ($alta)?"":emptyIfZero($row["ESTABLECIMIENTOS"])?>"></td>
		</tr>
		<tr>
			<td width="17%"><p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt;">Zona Geográfica (*)</font></td>
			<td colspan="3"><select id="zonaGeografica" name="zonaGeografica"></select></td>
		</tr>
<?
$params = array(":identidad" => $_SESSION["entidad"], ":usuario" => $_SESSION["usuario"]);
$sql =
	"SELECT 1
		 FROM afi.ape_prestacionesespeciales
		WHERE pe_fechabaja IS NULL
			AND ((pe_identidad = :identidad) OR (pe_usuario = :usuario AND pe_tipousuario = 'W'))";
if (ExisteSql($sql, $params)) {
?>
		<tr>
			<td width="21%"><p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt;">Cotización con Prestaciones Especiales</font></td>
			<td colspan="3"><input <?= ($alta)?"":($row["PRESTACIONESESPECIALES"] == "S")?"checked":""?> id="prestacionesEspeciales" name="prestacionesEspeciales" style="margin-left:-3px;" type="checkbox" /></td>
		</tr>
<?
}
?>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td bgcolor="#808080" colspan="4" style="padding-left:12px;">
			<font face="Verdana" style="font-size:8pt; font-weight:700;" color="#FFFFFF">Establecimientos</font></td>
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
$params = array(":usualta" => "W_".$_SESSION["usuario"]);
$sql =
	"DELETE FROM afi.aeu_establecimientos
				 WHERE eu_idsolicitud = -1
					 AND eu_usualta = :usualta
					 AND eu_usuarioweb = 'T'";
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
			<td bgcolor="#808080" colspan="4" style="padding-left:12px;">
			<font face="Verdana" style="font-size:8pt; font-weight:700;" color="#FFFFFF">Observaciones (máximo 2048 caracteres)</font></td>
		</tr>
		<tr>
			<td colspan="4"><textarea cols="1" id="observaciones" name="observaciones" rows="5" style="width:696px;"><?= ($alta)?"":$row["OBSERVACIONES"]?></textarea></td>
		</tr>
		<tr>
<?
if (($_SESSION["entidad"] == 9003) and ($_SESSION["vendedor"] == "")) {
	if (!$alta) {
		$params = array(":id" => nullIsEmpty($row["IDVENDEDOR"]));
		$sql =
			"SELECT ve_nombre, ve_vendedor
				 FROM xve_vendedor
				WHERE ve_id = :id";
		$stmtVend = DBExecSql($conn, $sql, $params);
		$rowVend = DBGetQuery($stmtVend);
		$vendedor = $rowVend["VE_VENDEDOR"];
		$nombreVendedor = $rowVend["VE_NOMBRE"];
	}
?>
			<td colspan="4">
				<font color="#676767" face="Trebuchet MS" style="font-size:8pt;">Código de Vendedor</font>
				<input id="codigoVendedor" maxlength="10" name="codigoVendedor" style="width:104px;" type="text" value="<?= ($alta)?"":$vendedor?>" onKeyUp="getVendedor(this.value)">
				<font color="#676767" face="Trebuchet MS" style="font-size:8pt;"><span id="vendedor"><?= ($alta)?"":$nombreVendedor?></span></font>
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
						<td colspan="3" align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" bgcolor="#808080">
							<font face="Trebuchet MS" style="font-size:8pt; font-weight:700;" color="#FFFFFF">TRABAJADORES</font>
						</td>
					</tr>
					<tr>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" width="30%">
							<p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt; font-weight:700;">Cantidad</font></p>
							<p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt; font-weight:700;">(a)</font>
						</td>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" width="29%">
							<p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt; font-weight:700;">Masa Salarial</font></p>
							<p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt; font-weight:700;">(b)</font>
						</td>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" valign="top" width="41%">
							<p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt; font-weight:700;">Mes/Año</font>
						</td>
					</tr>
					<tr>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0">
							<input class="input2 inputNumber" id="trabajadoresCantidad" name="trabajadoresCantidad" style="width:56px;" type="text" value="<?= ($alta)?"":$row["CANTTRAB"]?>" readonly>
						</td>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0">
							<input class="input2 inputNumber" id="trabajadoresMasaSalarial" name="trabajadoresMasaSalarial" type="text" value="<?= ($alta)?"":trim($row["MASASALARIAL"])?>" readonly>
						</td>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0">
							<input class="input2" id="trabajadoresMesAno" name="trabajadoresMesAno" style="text-align:center; width:48px;" type="text" value="<?= ($alta)?"":formatPeriodo($row["PERIODO"])?>" readonly>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table border="0" cellpadding="0" width="100%" id="table12" cellspacing="1">
					<tr>
						<td colspan="4" align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" bgcolor="#808080">
							<font face="Trebuchet MS" style="font-size:8pt; font-weight:700;" color="#FFFFFF">ALÍCUOTAS</font>
						</td>
					</tr>
					<tr>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" width="29%">
							<p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt; font-weight:700;">% sobre Masa Salarial (c)</font></p>
						</td>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" width="6%">
							<p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt; font-weight:700;">Fijo</font></p>
							<p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt; font-weight:700;">(d)</font>
						</td>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" valign="top" width="9%">
							<p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt; font-weight:700;">F.F.E.P.</font>
						</td>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" width="43%">
							<p style="margin-bottom:0; margin-top:0;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt; font-weight:700;">Cuota inicial resultante</font></p>
							<p style="margin-bottom:0; margin-top:0;"><span style="font-weight:700;"><font color="#676767" face="Trebuchet MS" style="font-size:8pt;">(bxc) + (axd) + (axf.f.e.p.)</font></span>
						</td>
					</tr>
					<tr>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" width="29%">
							<input class="input2 inputNumber" id="alicuotasMasaSalarial" name="alicuotasMasaSalarial" type="text" value="<?= ($alta)?"":trim($rowValorFinal["PORCVARIABLE"])?>" readonly>
						</td>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" width="6%">
							<input class="input2 inputNumber" id="alicuotasFijo" name="alicuotasFijo" style="width:40px;" type="text" value="<?= ($alta)?"":trim($rowValorFinal["SUMAFIJA"])?>" readonly>
						</td>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" width="9%">
							<input class="input2 inputNumber" id="alicuotasFfep" name="alicuotasFfep" style="text-align:center; width:40px;" type="text" value="$ 0,60" readonly>
						</td>
						<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" width="43%">
							<input class="input2 inputNumber" id="alicuotasCuotaInicial" name="alicuotasCuotaInicial" type="text" value="<?= ($alta)?"":trim($rowValorFinal["COSTOMENSUAL"])?>" readonly>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>


	<table border="0" id="tableCampanaF931" style="display:none;" width="736">
		<tr>
			<td align="center" class="ContenidoSeccion" colspan="2">
				<div style="background-color:#808080; border:solid 1px #c0c0c0; color:#fff; font-weight:700; margin-left:-12px; padding-bottom:4px; padding-top:4px; width:103%;">VALORES COTIZADOS POR PROVINCIA ART</div>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table border="0" bordercolor="#c0c0c0" cellpadding="0" cellspacing="1" style="border-collapse:collapse; border-style:solid; border-width:1px; cursor:default; font-family:Trebuchet MS; font-size:9pt;" width="100%">
					<tr>
						<td width="8"></td>
						<td></td>
						<td></td>
						<td align="center">Costo Final Cápitas</td>
						<td align="center">Costo Mensual</td>
						<td align="center">Costo Anual</td>
					</tr>
					<tr>
						<td></td>
						<td>Alícuota Variable (Tarifario)</td>
						<td align="center"><input class="input2 inputNumber" id="porcVarTarifario" name="porcVarTarifario" style="width:80px;" type="text" value="" readonly>%</td>
						<td align="center">$<input class="input2 inputNumber" id="costoFinalTarifario" name="costoFinalTarifario" style="width:80px;" type="text" value="" readonly></td>
						<td align="center">$<input class="input2 inputNumber" id="costoMensualTarifario" name="costoMensualTarifario" style="width:80px;" type="text" value="" readonly></td>
						<td align="center">$<input class="input2 inputNumber" id="costoAnualTarifario" name="costoAnualTarifario" style="width:80px;" type="text" value="" readonly></td>
					</tr>
					<tr height="8">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td style="background-color:#ffb871;"></td>
						<td style="background-color:#ffb871;"><span id="spanAlicuotaF931">Alícuota F931 XX% Descuento</span></td>
						<td align="center" style="background-color:#ffb871;"><input class="input2 inputNumber" id="porcVarF931" name="porcVarF931" style="background-color:#ffb871; border-bottom:1px solid #808080; border-left:1px solid #ffb871; border-right:1px solid #ffb871; border-top:1px solid #ffb871; width:80px;" type="text" value="" readonly>%</td>
						<td align="center" style="background-color:#ffb871;">$<input class="input2 inputNumber" id="costoFinalF931" name="costoFinalF931" style="background-color:#ffb871; border-bottom:1px solid #808080; border-left:1px solid #ffb871; border-right:1px solid #ffb871; border-top:1px solid #ffb871; width:80px;" type="text" value="" readonly></td>
						<td align="center" style="background-color:#ffb871;">$<input class="input2 inputNumber" id="costoMensualF931" name="costoMensualF931" style="background-color:#ffb871; border-bottom:1px solid #808080; border-left:1px solid #ffb871; border-right:1px solid #ffb871; border-top:1px solid #ffb871; width:80px;" type="text" value="" readonly></td>
						<td align="center" style="background-color:#ffb871;">$<input class="input2 inputNumber" id="costoAnualF931" name="costoAnualF931" style="background-color:#ffb871; border-bottom:1px solid #808080; border-left:1px solid #ffb871; border-right:1px solid #ffb871; border-top:1px solid #ffb871; width:80px;" type="text" value="" readonly></td>
					</tr>
					<tr id="trDescuento">
						<td></td>
						<td><span id="spanAlicuotaDescuento">Alícuota XX% Descuento</span></td>
						<td align="center"><input class="input2 inputNumber" id="porcVarDescuento" name="porcVarDescuento" style="width:80px;" type="text" value="" readonly>%</td>
						<td align="center">$<input class="input2 inputNumber" id="costoFinalDescuento" name="costoFinalDescuento" style="width:80px;" type="text" value="" readonly></td>
						<td align="center">$<input class="input2 inputNumber" id="costoMensualDescuento" name="costoMensualDescuento" style="width:80px;" type="text" value="" readonly></td>
						<td align="center">$<input class="input2 inputNumber" id="costoAnualDescuento" name="costoAnualDescuento" style="width:80px;" type="text" value="" readonly></td>
					</tr>
					<tr id="trAumento">
						<td></td>
						<td><span id="spanAlicuotaAumento">Alícuota Máxima</span></td>
						<td align="center"><input class="input2 inputNumber" id="porcVarAumento" name="porcVarAumento" style="width:80px;" type="text" value="" readonly>%</td>
						<td align="center">$<input class="input2 inputNumber" id="costoFinalAumento" name="costoFinalAumento" style="width:80px;" type="text" value="" readonly></td>
						<td align="center">$<input class="input2 inputNumber" id="costoMensualAumento" name="costoMensualAumento" style="width:80px;" type="text" value="" readonly></td>
						<td align="center">$<input class="input2 inputNumber" id="costoAnualAumento" name="costoAnualAumento" style="width:80px;" type="text" value="" readonly></td>
					</tr>
					<tr height="16">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td style="background-color:#7bd066;"></td>
						<td style="background-color:#7bd066; font-weight:700;">Alícuota FINAL (COMPLETAR)</td>
						<td align="center" style="background-color:#7bd066; font-weight:700;"><input class="input2 inputNumber" id="alicuotaFinalF931" maxlength="10" name="alicuotaFinalF931" style="background-color:#7bd066; border-bottom:1px solid #808080; border-left:1px solid #7bd066; border-right:1px solid #7bd066; border-top:1px solid #7bd066; font-weight:700; width:80px;" type="text" value="" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)">%</td>
						<td><span id="spanTopesAlicuotaFinal" style="font-weight:700;">(Entre XX% y XX%)</span></td>
						<td align="right" style="font-weight:700;">Suma Fija</td>
						<td align="center" style="font-weight:700;">$<input class="input2 inputNumber" id="sumaFijaF931" name="sumaFijaF931" style="font-weight:700; width:80px;" type="text" value="" readonly></td>
					</tr>
					<tr height="4">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>


	<table>
		<tr>
			<td>
				<table id="tableDescuento" name="tableDescuento" style="display:<?= ($mostrarDescuento or $aumentoODescuentoAplicado)?"block":"none"?>;">
					<tr>
						<td>
							<table border="0" cellpadding="0" cellspacing="1" id="tableDescuento2" name="tableDescuento2" style="display:<?= ($aumentoODescuentoAplicado)?"none":"block"?>" width="100%">
								<tr>
									<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" bgcolor="#808080">
										<font face="Trebuchet MS" style="font-size:8pt; font-weight:700;" color="#FFFFFF">Descuento<br /><span id="spanTopeDescuento"></span>% Tope</font>
									</td>
								</tr>
								<tr>
									<td bordercolor="#C0C0C0" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;">
										<input class="input2 inputNumber" id="descuentoValor" name="descuentoValor" style="width:80px;" type="text" value="0" onKeyUp="reemplazarPuntoXComa(this); document.getElementById('aumento').value = 0; document.getElementById('aumentoValor').value = 0;">
										<input class="btnCalcular" id="btnCalcularDescuento" style="margin-left:16px;" type="button" value="" onClick="calcularDescuento(document);">
									</td>
								</tr>
							</table>
						</td>
						<td width="104"></td>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="1" style="display:<?= ($aumentoODescuentoAplicado)?"block":"none"?>" width="100%">
								<tr>
									<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" bgcolor="#808080">
										<font face="Trebuchet MS" style="font-size:8pt; font-weight:700;" color="#FFFFFF">Descuento Aplicado <span id="spanDescuento"><?= ($alta)?0:$row["DESCUENTO"]?></span>%</font>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table id="tableAumento" name="tableAumento" style="display:<?= ($mostrarAumento or $aumentoODescuentoAplicado)?"block":"none"?>;">
					<tr>
						<td>
							<table border="0" cellpadding="0" cellspacing="1" id="tableAumento2" name="tableAumento2" style="display:<?= ($aumentoODescuentoAplicado)?"none":"block"?>" width="100%">
								<tr>
									<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" bgcolor="#808080">
										<font face="Trebuchet MS" style="font-size:8pt; font-weight:700;" color="#FFFFFF">Aumento<br /><span id="spanTopeAumento"></span>% Tope</font>
									</td>
								</tr>
								<tr>
									<td bordercolor="#C0C0C0" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;">
										<input class="input2 inputNumber" id="aumentoValor" name="aumentoValor" style="width:80px;" type="text" value="0" onKeyUp="reemplazarPuntoXComa(this); document.getElementById('descuento').value = 0; document.getElementById('descuentoValor').value = 0;" />
										<input class="btnCalcular" id="btnCalcularAumento" style="margin-left:16px;" type="button" value="" onClick="calcularAumento(document);" />
									</td>
								</tr>
							</table>
						</td>
						<td width="104"></td>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="1" style="display:<?= ($aumentoODescuentoAplicado)?"block":"none"?>" width="100%">
								<tr>
									<td align="center" style="border-style:solid; border-width:1px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" bordercolor="#C0C0C0" bgcolor="#808080">
										<font face="Trebuchet MS" style="font-size:8pt; font-weight:700;" color="#FFFFFF">Aumento Aplicado <span id="spanAumento"><?= ($alta)?0:$row["AUMENTO"]?></span>%</font>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>


<?
if (($_SESSION["entidad"] != 400) and ($_SESSION["entidad"] != 10891) and (($alta) or ((!$alta) and (($row["ESTADO"] == "04") or ($row["ESTADO"] == "06") or ($row["ESTADO"] == "13"))))) {
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
	if ((!$alta) and (($row["ESTADO"] == "04") or ($row["ESTADO"] == "06") or ($row["ESTADO"] == "13"))) {
?>
		<div class="ContenidoSeccion" style="height:0px; left:280px; position:relative; top:-68px; width:400px;">
			<label>Recalcular Póliza de Responsabilidad Civil Patronal</label>
			<p style="margin-top:16px;">
				<label>Póliza RC</label>
				<input class="inputNumber" id="polizaRC" name="polizaRC" readonly style="width:80px;" type="text" value="<?= $row["VALORRC"]?>" />
				<input class="btnActualizar" style="margin-left:24px;" type="button" value="" onClick="actualizarRC('<?= $_REQUEST["id"]?>')" />
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
					<input class="input2 inputNumber" id="alicuotaVariableRC" readonly style="width:80px;" type="text" value="<?= trim($row["VALORRCFORMATEADO"])?>" />
				</div>
				<div style="background-color:#fff; border-bottom:1px solid #676767; border-left:1px solid #676767; border-right:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:143px;">
					<input class="input2 inputNumber" id="masaSalarialRC" readonly style="width:80px;" type="text" value="<?= trim($row["MASASALARIAL"])?>" />
				</div>
				<div style="background-color:#fff; border-bottom:1px solid #676767; border-right:1px solid #676767; border-top:1px solid #676767; float:left; padding-bottom:4px; padding-top:4px; width:143px;">
					<input class="input2 inputNumber" id="cuotaInicialResultanteRC" readonly style="width:80px;" type="text" value="<?= trim($row["CUOTAINICIALRC"])?>" />
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
<?
if (($alta) or ($mostrarAumento) or ($mostrarDescuento)) {
?>
				<input class="btnObtenerCotizacion" id="btnGuardar" type="button" value="" onClick="guardarSolicitud()">
				<img id="imgGuardando" src="/images/loading.gif" style="display:none; vertical-align:-4px;">
<?
	if ($alta) {
?>
				<input class="btnCancelar" style="margin-left:8px;" type="button" value="" onClick="window.location.href = '<?= $paginaAnterior?>'" />
<?
	}
}

if (!$alta) {
?>
				<input class="btnVolver" type="button" value="" onClick="window.location.href = '<?= $paginaAnterior?>'" />
<?
	$sql = "SELECT art.cotizacion.get_imprimircotizacion(:id, :modulo) FROM DUAL";
	$params = array(":id" => $id, ":modulo" => $modulo);
	if ((ValorSql($sql, "", $params) == "T") or ($row["ESTADO"] == "13")) {
?>
				<input class="btnCarta" type="button" value="" onClick="window.location.href = '/index.php?pageid=29&id=<?= $_REQUEST["id"]?>'" />
<?
	}

	$sql = "SELECT art.afiliacion.get_imprimirsolicitud(:id, :modulo) FROM DUAL";
	$params = array(":id" => $id, ":modulo" => $modulo);
	if (ValorSql($sql, "", $params) == "T") {
?>
				<input class="btnSolicitarAfiliacion" type="button" value="" onClick="window.location.href = '/index.php?pageid=30&id=<?= $_REQUEST["id"]?>'" />
<?
	}

	$params = array(":idformulario" => nullIsEmpty($row["IDFORMULARIO"]));
	$sql =
		"SELECT sa_id
			 FROM asa_solicitudafiliacion
			WHERE sa_idformulario = :idformulario";
	$idSolicitudAfiliacion = ValorSql($sql, -1, $params);
	if (($idSolicitudAfiliacion > 0) and (($_SESSION["usuario"] == "EDU2824") or ($_SESSION["usuario"] == "ALAPACO"))) {
?>
				<input class="btnReImprimirSolicitudAfiliacion" id="btnReimprimir" type="button" value="" onClick="document.getElementById('iframeProcesando').src = '/modules/solicitud_afiliacion/validar_total_rgrl.php?id=<?= $_REQUEST["id"]?>&idSolicitudAfiliacion=<?= $idSolicitudAfiliacion?>';" />
				<img id="imgImprimiendo" src="/images/loading.gif" style="display:none; vertical-align:-4px;" title="Reimprimiendo Solicitud de Afiliación...">
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
				<input class="btnAnular" type="button" value="" onClick="anularSolicitud('<?= $_REQUEST["id"]?>', '<?= $row["IDFORMULARIO"]?>', unescape('<?= rawurlencode($_SESSION["usuario"])?>'), '<?= $numeroAfiliacion?>', '<?= $row["CUIT"]?>', unescape('<?= rawurlencode($row["RAZONSOCIAL"])?>'), '<?= $afiliacionImpresaYNoPresentada?>', '<?= $row["VIGENTE"]?>')" />
<?
	}
}
?>
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "statusSrt";
$RCparams = array();
$RCquery =
	"SELECT tb_codigo id, tb_descripcion detalle
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
	"SELECT ar_id id, ar_nombre detalle
		 FROM aar_art
		WHERE ar_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["IDARTANTERIOR"];
FillCombo();

$RCfield = "statusBcra";
$RCparams = array();
$RCquery =
	"SELECT DECODE(tb_codigo, -1, 0, tb_codigo) id, tb_codigo || ' - ' || tb_descripcion detalle
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
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'SECT'
			AND tb_codigo IN(2, 3, 4)
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = ($alta)?-1:$row["SECTOR"];
FillCombo();

$RCfield = "zonaGeografica";
$RCparams = array();
$RCquery =
	"SELECT zg_id id, zg_descripcion detalle
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

		lockControls(<?= ($alta)?"false":"true"?>, <?= ($alta)?"false":"true"?>);
		getElementById('cuit').focus();
<?
if ((isset($_REQUEST["i"])) and ($_REQUEST["i"] == "k")) {
?>
		getElementById('tableValoresFinales').style.backgroundColor = '#3ecaff';
<?
}


if ($mostrarAumento) {
	$params = array(":canttrabajador" => $row["CANTTRAB"],
									":finalporcmasa" => trim($rowValorFinal["PORCVARIABLE"]),
									":finalsumafija" => str_replace("$", "", trim($rowValorFinal["SUMAFIJA"])),
									":idactividad" => $row["IDACTIVIDAD"],
									":masasalarial" => $row["MASASALARIALTOT2"],
									":usuario" => "W_".$_SESSION["usuario"]);
	$sql = "SELECT art.cotizacion.get_aumento(:idactividad, :masasalarial, :canttrabajador, :finalsumafija, :finalporcmasa, :usuario) FROM dual";
	$tope = floatval(ValorSql($sql, 0, $params));
?>
		mostrarBotonGuardar(document);

		// Muestro los objetos relacionados con el aumento..
		getElementById('btnGuardar').className = 'btnGuardar';
		getElementById('spanTopeAumento').innerHTML = '<?= $tope?>';
//		getElementById('tableAumento').style.display = 'block';
//		getElementById('tableAumento2').style.display = 'block';
		getElementById('tableValoresFinales').style.display = 'block';
		getElementById('topeAumento').value = '<?= $tope?>';
		getElementById('aumentoValor').select();
		getElementById('aumentoValor').focus();

		// Calculo los valores..
		calcularAumento(document);
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
		getElementById('btnGuardar').className = 'btnGuardar';
		getElementById('spanTopeDescuento').innerHTML = '<?= $tope?>';
//		getElementById('tableDescuento').style.display = 'block';
//		getElementById('tableDescuento2').style.display = 'block';
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