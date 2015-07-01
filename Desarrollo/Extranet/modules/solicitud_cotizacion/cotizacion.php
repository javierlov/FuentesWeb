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

	if (strpos("XX".$paginaAnterior, "/cotizacion/"))
		$paginaAnterior = "/buscar-cotizacion/".$row["NROSOLICITUD"];
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
		$mostrarAumento = (($mostrarAumento) and (floatval(valorSql($sql, 0, $params)) > 0));
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
		$mostrarDescuento = (($mostrarDescuento) and (floatval(valorSql($sql, 0, $params)) > 0));
	}
}

require_once("cotizacion_combos.php");
?>
<link href="/modules/solicitud_cotizacion/css/cotizacion.css" rel="stylesheet" type="text/css" />
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
			<td class="tdTitulo" colspan="2">
				<span>Solicitud de Cotización</span>
<?
if (!$alta) {
?>
				<span> - Nº <?= $row["NROSOLICITUD"]?></span>
<?
}
?>
			</td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" width="120">C.U.I.T. (*)</td>
			<td>
				<input autofocus id="cuit" maxlength="11" name="cuit" type="text" value="<?= ($alta)?"":$row["CUIT"]?>" onBlur="validarDatosCuit(<?= ($alta)?"true":"false"?>)" onChange="limpiarAvisoPCP()" />
				<img id="imgCuitLoading" src="/images/loading.gif" title="Buscando Status ante la SRT..." />
			</td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">Razón Social (*)</td>
			<td><input id="razonSocial" maxlength="60" name="razonSocial" readonly type="text" value="<?= ($alta)?"":$row["RAZONSOCIAL"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">Contacto (*)</td>
			<td><input id="contacto" maxlength="100" name="contacto" type="text" value="<?= ($alta)?"":$row["CONTACTO"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">Teléfono</td>
			<td><input id="telefono" maxlength="50" name="telefono" type="text" value="<?= ($alta)?"":$row["TELEFONO"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">e-Mail</td>
			<td><input id="email" maxlength="100" name="email" type="text" value="<?= ($alta)?"":$row["EMAIL"]?>"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">
				<span>Holding</span>
				<img border="0" id="holdingBuscar" name="holdingBuscar" src="/modules/solicitud_cotizacion/images/lupa.gif" title="Buscar Holding" onClick="showBuscarHoldingWin()" />
			</td>
			<td><input id="holding" name="holding" readonly type="text" value="<?= ($alta)?"":$row["HOLDING"]?>" /></td>
		</tr>
	</table>

	<table width="736">
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td id="tdSoloPCP">
				<table width="720">
					<tr>
						<td><input <?= ($alta)?"":($row["SOLO_CASAS_PARTICULARES"] == "S")?"checked":""?> id="soloPCP" name="soloPCP" type="checkbox" onClick="mostrarSoloPCP(this.checked)" /></td>
						<td><label for="soloPCP">Clic aquí para cotizar solamente Régimen Especial para Empleadores de Personal de Casas Particulares</label></td>
					</tr>
				</table>
				<div id="avisoPCP"></div>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>

	<table border="0" id="tableActividad" width="736">
		<tr>
			<td class="tdTitulo" colspan="5">Actividad</td>
		</tr>
		<tr>
			<td align="center" class="tdTituloActividad" colspan="2">Código</td>
			<td align="center" class="tdTituloActividad">Descripción</td>
			<td align="center" class="tdTituloActividad">Total de Trabajadores</td>
			<td align="center" class="tdTituloActividad">Masa Salarial</td>
		</tr>
		<tr>
		<tr id="trActividad1">
			<td valign="top" width="68"><input id="ciiu1" maxlength="6" name="ciiu1" title="Código" type="text" value="<?= ($alta)?"":$row["CIIUCODIGO1"]?>" onBlur="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu1Descripcion', this.value)" onKeyUp="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu1Descripcion', this.value)"></td>
			<td valign="top" width="33"><img border="0" id="ciiu1Buscar" src="/modules/solicitud_cotizacion/images/lupa.gif" title="Buscar CIIU" onClick="showBuscarCiiuWin('ciiu1')" /></td>
			<td valign="top" width="494"><span class="ciiuDescripcion" id="ciiu1Descripcion" name="ciiu1Descripcion"><?= ($alta)?"":$row["CIIUDESCRIPCION1"]?></span></td>
			<td align="center" valign="top" width="229"><input class="inputNumber" id="totalTrabajadores1" maxlength="12" name="totalTrabajadores1" type="text" value="<?= ($alta)?"":$row["CANTTRAB1"]?>" onBlur="sumarTrabajadores(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarTrabajadores(<?= ($alta)?"true":"false"?>)"></td>
			<td align="center" valign="top" width="90"><input class="inputNumber" id="masaSalarial1" maxlength="15" name="masaSalarial1" type="text" value="<?= ($alta)?"":$row["MASASALARIAL1"]?>" onBlur="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)"></td>
		</tr>
		<tr id="trActividad2">
			<td valign="top" width="68"><input id="ciiu2" maxlength="6" name="ciiu2" type="text" value="<?= ($alta)?"":$row["CIIUCODIGO2"]?>" onBlur="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu2Descripcion', this.value)" onKeyUp="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu2Descripcion', this.value)" /></td>
			<td valign="top" width="33"><img border="0" id="ciiu2Buscar" src="/modules/solicitud_cotizacion/images/lupa.gif" title="Buscar CIIU" onClick="showBuscarCiiuWin('ciiu2')" /></td>
			<td valign="top" width="494"><span class="ciiuDescripcion" id="ciiu2Descripcion" name="ciiu2Descripcion"><?= ($alta)?"":$row["CIIUDESCRIPCION2"]?></span></td>
			<td align="center" valign="top" width="229"><input class="inputNumber" id="totalTrabajadores2" maxlength="12" name="totalTrabajadores2" type="text" value="<?= ($alta)?"":$row["CANTTRAB2"]?>" onBlur="sumarTrabajadores(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarTrabajadores(<?= ($alta)?"true":"false"?>)" /></td>
			<td align="center" valign="top" width="90"><input class="inputNumber" id="masaSalarial2" maxlength="15" name="masaSalarial2" type="text" value="<?= ($alta)?"":$row["MASASALARIAL2"]?>" onBlur="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)" /></td>
		</tr>
		<tr id="trActividad3">
			<td valign="top" width="68"><input id="ciiu3" maxlength="6" name="ciiu3" type="text" value="<?= ($alta)?"":$row["CIIUCODIGO3"]?>" onBlur="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu3Descripcion', this.value)" onKeyUp="getActividad(<?= ($alta)?"true":"false"?>, 'ciiu3Descripcion', this.value)" /></td>
			<td valign="top" width="33"><img border="0" id="ciiu3Buscar" src="/modules/solicitud_cotizacion/images/lupa.gif" title="Buscar CIIU" onClick="showBuscarCiiuWin('ciiu3')" /></td>
			<td valign="top" width="494"><span class="ciiuDescripcion" id="ciiu3Descripcion" name="ciiu3Descripcion"><?= ($alta)?"":$row["CIIUDESCRIPCION3"]?></span></td>
			<td align="center" valign="top" width="229"><input class="inputNumber" id="totalTrabajadores3" maxlength="12" name="totalTrabajadores3" type="text" value="<?= ($alta)?"":$row["CANTTRAB3"]?>" onBlur="sumarTrabajadores(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarTrabajadores(<?= ($alta)?"true":"false"?>)" /></td>
			<td align="center" valign="top" width="90"><input class="inputNumber" id="masaSalarial3" maxlength="15" name="masaSalarial3" type="text" value="<?= ($alta)?"":$row["MASASALARIAL3"]?>" onBlur="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)" onKeyUp="sumarMasaSalarial(<?= ($alta)?"true":"false"?>)"></td>
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
	<table border="0" id="tableTotalesActividad" width="736">
		<tr>
			<td colspan="3"></td>
			<td align="center" width="229"><input class="pcpCampoGris" id="totalTrabajadores" name="totalTrabajadores" readonly type="text" value="<?= ($alta)?"":$row["CANTTRAB"]?>" /></td>
			<td align="center" width="90"><input class="pcpCampoGris" id="masaSalarial" name="masaSalarial" readonly type="text" value="<?= ($alta)?"":$row["MASASALARIALTOT2"]?>" onChange="calcularMasaSalarialSinSac(true)" /></td>
		</tr>
	</table>

	<table border="0" id="tablePeriodo" width="736">
		<tr>
			<td class="ContenidoSeccion" width="65">Período</td>
			<td class="ContenidoSeccion" width="417"><input id="periodo" maxlength="7" name="periodo" type="text" useSeparator="true" value="<?= ($alta)?"":formatPeriodo($row["PERIODO"])?>" onBlur="calcularMasaSalarialSinSac(<?= ($alta)?"true":"false"?>)" onKeyUp="calcularMasaSalarialSinSac(true)" /><span> (AAAA/MM)</span></td>
			<td align="right" class="ContenidoSeccion" width="128"><label>Masa Salarial sin SAC</label></td>
			<td><input class="pcpCampoGris" id="masaSalarialSinSac" name="masaSalarialSinSac" readonly type="text" value="<?= ($alta)?"":$row["MASASALARIALTOT2"]?>" /></td>
		</tr>
	</table>

	<table border="0" id="tableActividadReal" width="736">
		<tr>
			<td class="ContenidoSeccion" width="65"><span>Act. Real</span></td>
			<td><input id="actividadReal" maxlength="200" name="actividadReal" type="text" value="<?= ($alta)?"":$row["ACTIVIDADREAL"]?>" /></td>
		</tr>
	</table>

	<table border="0" id="tableStatusSrt" width="736">
		<tr>
			<td class="tdTitulo" colspan="4">Status SRT</td>
		</tr>
		<tr>
			<td class="ContenidoSeccion">Status ante SRT</td>
			<td><?= $comboStatusSrt->draw();?></td>
			<td class="ContenidoSeccion">ART</td>
			<td><?= $comboArt->draw();?></td>
		</tr>
	</table>

	<table id="tableStatusBcra" width="736">
		<tr>
			<td class="tdTitulo" colspan="2">Status BCRA</td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" width="96">Status ante BCRA</td>
			<td><?= $comboStatusBcra->draw();?></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" colspan="2"><a class="linkSubrayado" href="http://www.bcra.gov.ar/cenries/cr010000.asp?error=0" target="_blank">www.bcra.gov.ar</a></td>
		</tr>
	</table>

	<table id="tableDatosCompetencia" width="736">
		<tr>
			<td class="tdTitulo" colspan="4">Datos de la Competencia</td>
		</tr>
		<tr>
			<td width="3%"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="" <?= ($alta)?"checked":($row["FORM931"] == "")?"checked":""?> /></td>
			<td class="ContenidoSeccion" width="24%">Sin Dato</td>
			<td width="72%" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="3%"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="A" <?= ((!$alta) and ($row["FORM931"] == "A"))?"checked":""?> /></td>
			<td class="ContenidoSeccion" width="24%"><label for="soloPagoTotalMensual">Solo pago total mensual</label></td>
			<td class="ContenidoSeccion" colspan="2" width="72%">
				<span>$</span>
				<input class="inputNumber" id="soloPagoTotalMensual" maxlength="15" name="soloPagoTotalMensual" type="text" value="<?= ($alta)?"":emptyIfZero($row["PAGOMENSUAL"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)" />
			</td>
		</tr>
		<tr>
			<td width="3%"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="S" <?= ((!$alta) and ($row["FORM931"] == "S"))?"checked":""?> /></td>
			<td class="ContenidoSeccion" width="24%"><label for="formulario931CostoFijo">Formulario 931</label></td>
			<td class="ContenidoSeccion" colspan="2" width="72%">
				<div id="divForm931">
					<span>Costo Fijo: $</span>
					<input class="inputNumber" id="formulario931CostoFijo" maxlength="15" name="formulario931CostoFijo" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOFIJO931"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)" />
					<span>Costo Variable: $</span>
					<input class="inputNumber" id="formulario931CostoVariable" maxlength="17" name="formulario931CostoVariable" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOVARIABLE931"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)" />
				</div>
			</td>
		</tr>
		<tr>
			<td width="3%"><input id="rDatosCompetencia" name="rDatosCompetencia" type="radio" value="N" <?= ((!$alta) and ($row["FORM931"] == "N"))?"checked":""?> /></td>
			<td class="ContenidoSeccion" width="24%"><span>Alícuota Competencia</span></td>
			<td class="ContenidoSeccion" colspan="2" width="72%">
				<span>Suma Fija: $</span>
				<input class="inputNumber" id="alicuotaCompetenciaSumaFija" maxlength="15" name="alicuotaCompetenciaSumaFija" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOFIJOCOMPETENCIA"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)" />
				<span>Variable: %</span>
				<input class="inputNumber" id="alicuotaCompetenciaVariable" maxlength="17" name="alicuotaCompetenciaVariable" type="text" value="<?= ($alta)?"":emptyIfZero($row["COSTOVARIABLECOMPETENCIA2"])?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)" />
			</td>
		</tr>
		<tr>
			<td width="99%" colspan="4"></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" colspan="2" width="27%">Resultado Mensual por Trabajador</td>
			<td width="24%"><input class="inputNumber" id="resultadoMensualPorTrabajador" maxlength=15" name="resultadoMensualPorTrabajador" readonly type="text" value="<?= ($alta)?"":emptyIfZero($resultadoMensualPorTrabajador)?>" /></td>
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
			<td class="ContenidoSeccion" colspan="2" width="27%">Suma Fija</td>
			<td colspan="2" width="72%"><input class="inputNumber" id="calculoSumaFija" maxlength="15" name="calculoSumaFija" readonly type="text" value="<?= ($alta)?"":emptyIfZero($calculoSumaFija)?>" /></td>
		</tr>
		<tr>
			<td align="right" class="ContenidoSeccion" colspan="2" width="27%">Variable</td>
			<td colspan="2" width="72%"><input class="inputNumber" id="calculoVariable" maxlength="17" name="calculoVariable" readonly type="text" value="<?= ($alta)?"":emptyIfZero($calculoVariable)?>" /></td>
		</tr>
	</table>

	<table border="0" id="tableEdadPromedio" width="736">
		<tr>
			<td class="ContenidoSeccion" width="17%">Edad promedio</td>
			<td colspan="3"><input class="inputNumber" id="edadPromedio" maxlength="2" name="edadPromedio" type="text" value="<?= ($alta)?"35":emptyIfZero($row["EDADPROMEDIO"])?>" /></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" width="17%">Sector (*)</td>
			<td colspan="3" width="51%"><?= $comboSector->draw();?></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" width="17%">Cant. de establecimientos</td>
			<td colspan="3"><input class="inputNumber" id="cantidadEstablecimientos" maxlength="3" name="cantidadEstablecimientos" type="text" value="<?= ($alta)?"":emptyIfZero($row["ESTABLECIMIENTOS"])?>" /></td>
		</tr>
		<tr>
			<td class="ContenidoSeccion" width="17%">Zona Geográfica (*)</td>
			<td colspan="3"><?= $comboZonaGeografica->draw();?></td>
		</tr>
<?
$params = array(":identidad" => $_SESSION["entidad"], ":usuario" => $_SESSION["usuario"]);
$sql =
	"SELECT 1
		 FROM afi.ape_prestacionesespeciales
		WHERE pe_fechabaja IS NULL
			AND ((pe_identidad = :identidad) OR (pe_usuario = :usuario AND pe_tipousuario = 'W'))";
if (existeSql($sql, $params)) {
?>
		<tr>
			<td class="ContenidoSeccion" width="21%">Cotización con Prestaciones Especiales</td>
			<td colspan="3"><input <?= ($alta)?"":($row["PRESTACIONESESPECIALES"] == "S")?"checked":""?> id="prestacionesEspeciales" name="prestacionesEspeciales" type="checkbox" /></td>
		</tr>
<?
}
?>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td class="tdTitulo" colspan="4">Establecimientos</td>
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
				<iframe frameborder="0" id="iframeEstablecimientos" name="iframeEstablecimientos" src="/modules/solicitud_cotizacion/establecimientos.php?idsolicitud=<?= $idSolicitud?>&tiposolicitud=<?= $tipoSolicitud?>&usualta=<?= $usuAlta?>"></iframe>
			</td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
<!--
		<tr>
			<td class="tdTitulo" colspan="4">Observaciones (máximo 2048 caracteres)</td>
		</tr>
		<tr>
			<td colspan="4"><textarea id="observaciones" name="observaciones"><?= ($alta)?"":$row["OBSERVACIONES"]?></textarea></td>
		</tr>
-->
	</table>

	<table border="0" id="tableCodigoVendedor" width="736">
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
			<td class="ContenidoSeccion" colspan="4">
				<span>Código de Vendedor</span>
				<input id="codigoVendedor" maxlength="10" name="codigoVendedor" type="text" value="<?= ($alta)?"":$vendedor?>" onKeyUp="getVendedor(this.value)" />
				<span id="vendedor"><?= ($alta)?"":$nombreVendedor?></span>
			</td>
<?
}
?>
		</tr>
	<table>

	<table id="tableValoresFinales" name="tableValoresFinales" style="display:<?= ((!$alta) and ($row["SOLO_CASAS_PARTICULARES"] != "S") and (($row["ESTADO"] == "04") or ($row["ESTADO"] == "06")))?"block":"none"?>" width="736">
		<tr>
			<td align="center" class="tdTitulo" colspan="2">VALORES COTIZADOS POR PROVINCIA ART</td>
		</tr>
		<tr>
			<td valign="top" width="50%">
				<table border="0" cellpadding="0" width="100%" id="table11" cellspacing="1">
					<tr>
						<td align="center" class="tdTitulo" colspan="3">TRABAJADORES</td>
					</tr>
					<tr>
						<td align="center" class="ContenidoSeccion tdBorde" width="30%">Cantidad<br />(a)</td>
						<td align="center" class="ContenidoSeccion tdBorde" width="29%">Masa Salarial<br />(b)</td>
						<td align="center" class="ContenidoSeccion tdBorde" valign="top" width="41%">Mes/Año</td>
					</tr>
					<tr>
						<td align="center" class="ContenidoSeccion tdBorde"><input class="input2 inputNumber" id="trabajadoresCantidad" name="trabajadoresCantidad" readonly type="text" value="<?= ($alta)?"":$row["CANTTRAB"]?>" /></td>
						<td align="center" class="ContenidoSeccion tdBorde"><input class="input2 inputNumber" id="trabajadoresMasaSalarial" name="trabajadoresMasaSalarial" readonly type="text" value="<?= ($alta)?"":trim($row["MASASALARIAL"])?>" /></td>
						<td align="center" class="ContenidoSeccion tdBorde"><input class="input2" id="trabajadoresMesAno" name="trabajadoresMesAno" readonly type="text" value="<?= ($alta)?"":formatPeriodo($row["PERIODO"])?>" /></td>
					</tr>
				</table>
			</td>
			<td valign="top" width="50%">
				<table border="0" cellpadding="0" cellspacing="1" id="table12" width="100%">
					<tr>
						<td align="center" class="tdTitulo" colspan="4">ALÍCUOTAS</td>
					</tr>
					<tr>
						<td align="center" class="ContenidoSeccion tdBorde">% sobre Masa Salarial<br />(c)</td>
						<td align="center" class="ContenidoSeccion tdBorde">Fijo <br />(d)</td>
						<td align="center" class="ContenidoSeccion tdBorde" valign="top">F.F.E.P.</td>
						<td align="center" class="ContenidoSeccion tdBorde">Cuota inicial resultante<br />(bxc) + (axd) + (axf.f.e.p.)</td>
					</tr>
					<tr>
						<td align="center" class="ContenidoSeccion tdBorde"><input class="input2 inputNumber" id="alicuotasMasaSalarial" name="alicuotasMasaSalarial" readonly type="text" value="<?= ($alta)?"":trim($rowValorFinal["PORCVARIABLE"])?>" /></td>
						<td align="center" class="ContenidoSeccion tdBorde"><input class="input2 inputNumber" id="alicuotasFijo" name="alicuotasFijo" readonly type="text" value="<?= ($alta)?"":trim($rowValorFinal["SUMAFIJA"])?>" /></td>
						<td align="center" class="ContenidoSeccion tdBorde"><input class="input2 inputNumber" id="alicuotasFfep" name="alicuotasFfep" readonly type="text" value="$ 0,60" /></td>
						<td align="center" class="ContenidoSeccion tdBorde"><input class="input2 inputNumber" id="alicuotasCuotaInicial" name="alicuotasCuotaInicial" readonly type="text" value="<?= ($alta)?"":trim($rowValorFinal["COSTOMENSUAL"])?>" /></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>


	<table border="0" id="tableCampanaF931" width="736">
		<tr>
			<td align="center" class="tdTitulo" colspan="2">VALORES COTIZADOS POR PROVINCIA ART</td>
		</tr>
		<tr>
			<td valign="top">
				<table border="0" cellpadding="0" cellspacing="1" id="tableValoresCotizados" width="100%">
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
						<td align="center"><input class="input2 inputNumber" id="porcVarTarifario" name="porcVarTarifario" readonly type="text" value="" />%</td>
						<td align="center">$<input class="input2 inputNumber" id="costoFinalTarifario" name="costoFinalTarifario" readonly type="text" value="" /></td>
						<td align="center">$<input class="input2 inputNumber" id="costoMensualTarifario" name="costoMensualTarifario" readonly type="text" value="" /></td>
						<td align="center">$<input class="input2 inputNumber" id="costoAnualTarifario" name="costoAnualTarifario" readonly type="text" value="" /></td>
					</tr>
					<tr height="8">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr class="fondoNaranja">
						<td></td>
						<td><span id="spanAlicuotaF931">Alícuota F931 XX% Descuento</span></td>
						<td align="center"><input class="input2 inputNumber" id="porcVarF931" name="porcVarF931" readonly type="text" value="" />%</td>
						<td align="center">$<input class="input2 inputNumber" id="costoFinalF931" name="costoFinalF931" readonly type="text" value="" /></td>
						<td align="center">$<input class="input2 inputNumber" id="costoMensualF931" name="costoMensualF931" readonly type="text" value="" /></td>
						<td align="center">$<input class="input2 inputNumber" id="costoAnualF931" name="costoAnualF931" readonly type="text" value="" /></td>
					</tr>
					<tr id="trDescuento">
						<td></td>
						<td><span id="spanAlicuotaDescuento">Alícuota XX% Descuento</span></td>
						<td align="center"><input class="input2 inputNumber" id="porcVarDescuento" name="porcVarDescuento" readonly type="text" value="" />%</td>
						<td align="center">$<input class="input2 inputNumber" id="costoFinalDescuento" name="costoFinalDescuento" readonly type="text" value="" /></td>
						<td align="center">$<input class="input2 inputNumber" id="costoMensualDescuento" name="costoMensualDescuento" readonly type="text" value="" /></td>
						<td align="center">$<input class="input2 inputNumber" id="costoAnualDescuento" name="costoAnualDescuento" readonly type="text" value="" /></td>
					</tr>
					<tr id="trAumento">
						<td></td>
						<td><span id="spanAlicuotaAumento">Alícuota Máxima</span></td>
						<td align="center"><input class="input2 inputNumber" id="porcVarAumento" name="porcVarAumento" readonly type="text" value="" />%</td>
						<td align="center">$<input class="input2 inputNumber" id="costoFinalAumento" name="costoFinalAumento" readonly type="text" value="" /></td>
						<td align="center">$<input class="input2 inputNumber" id="costoMensualAumento" name="costoMensualAumento" readonly type="text" value="" /></td>
						<td align="center">$<input class="input2 inputNumber" id="costoAnualAumento" name="costoAnualAumento" readonly type="text" value="" /></td>
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
						<td class="fondoVerde"></td>
						<td class="fondoVerde"><b>Alícuota FINAL (COMPLETAR)</b></td>
						<td align="center" class="fondoVerde"><input class="input2 inputNumber" id="alicuotaFinalF931" maxlength="10" name="alicuotaFinalF931" type="text" value="" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)" />%</td>
						<td><span id="spanTopesAlicuotaFinal">(Entre XX% y XX%)</span></td>
						<td align="right"><b>Suma Fija</b></td>
						<td align="center">$<input class="input2 inputNumber" id="sumaFijaF931" name="sumaFijaF931" readonly type="text" value="" /></td>
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
									<th align="center" class="tdBorde">DESCUENTO<br /><span id="spanTopeDescuento"></span>% Tope</th>
								</tr>
								<tr>
									<td class="tdBorde">
										<input class="input2 inputNumber" id="descuentoValor" name="descuentoValor" type="text" value="0" onKeyUp="reemplazarPuntoXComa(this); document.getElementById('aumento').value = 0; document.getElementById('aumentoValor').value = 0;" />
										<input class="btnCalcular" id="btnCalcularDescuento" type="button" value="" onClick="calcularDescuento(document);" />
									</td>
								</tr>
							</table>
						</td>
						<td width="104"></td>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="1" style="display:<?= ($aumentoODescuentoAplicado)?"block":"none"?>" width="100%">
								<tr>
									<td align="center" id="tdDescuentoAplicado">Descuento Aplicado <span id="spanDescuento"><?= ($alta)?0:$row["DESCUENTO"]?></span>%</td>
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
									<th align="center" class="tdBorde">AUMENTO<br /><span id="spanTopeAumento"></span>% Tope</th>
								</tr>
								<tr>
									<td class="tdBorde">
										<input class="input2 inputNumber" id="aumentoValor" name="aumentoValor" type="text" value="0" onKeyUp="reemplazarPuntoXComa(this); document.getElementById('descuento').value = 0; document.getElementById('descuentoValor').value = 0;" />
										<input class="btnCalcular" id="btnCalcularAumento" type="button" value="" onClick="calcularAumento(document);" />
									</td>
								</tr>
							</table>
						</td>
						<td width="104"></td>
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="1" style="display:<?= ($aumentoODescuentoAplicado)?"block":"none"?>" width="100%">
								<tr>
									<td align="center" id="tdAumentoAplicado">Aumento Aplicado <span id="spanAumento"><?= ($alta)?0:$row["AUMENTO"]?></span>%</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<div id="divPCP">
		<div class="tdTitulo" id="divPCPTitulo">Personal Casas Particulares</div>
		<div id="divPCPLeyenda">IMPORTANTE: Recuerde que el firmante del contrato de afiliación deberá coincidir con el Dador de Empleo registrado en AFIP.</div>
		<div align="center" id="divPCPTabla">
			<table id="tablePCPTabla">
				<tr>
					<th>Horas trabajadas semanalmente</th>
					<th>Cantidad de trabajadores</th>
					<th>Valor</th>
					<th>Alícuota</th>
				</tr>
<?
$params = array(":idsolicitudcotizacion" => $idSolicitud);
$sql =
	"SELECT (SELECT cp_canttrabajador
						 FROM afi.acp_cotizacion_pcp
						WHERE cp_idparametro_pcp = pp_id
							AND cp_idsolicitudcotizacion = :idsolicitudcotizacion) canttrabajador, pp_descripcion, pp_id, pp_valor
		 FROM afi.app_parametro_pcp
		WHERE art.actualdate BETWEEN pp_fechadesde AND pp_fechahasta
			AND pp_fechabaja IS NULL
 ORDER BY pp_renglon";
$stmt = DBExecSql($conn, $sql, $params);
while ($rowPCP = DBGetQuery($stmt)) {
?>
				<tr>
					<td><?= $rowPCP["PP_DESCRIPCION"]?></td>
					<td align="right"><input class="pcpCampoBlanco" id="trabajadoresPCP_<?= $rowPCP["PP_ID"]?>" name="trabajadoresPCP_<?= $rowPCP["PP_ID"]?>" type="text" value="<?= $rowPCP["CANTTRABAJADOR"]?>" onChange="calcularTotalesPCP()" /></td>
					<td align="right">$ <?= $rowPCP["PP_VALOR"]?><input id="valorPCP_<?= $rowPCP["PP_ID"]?>" name="valorPCP_<?= $rowPCP["PP_ID"]?>" type="hidden" value="<?= $rowPCP["PP_VALOR"]?>" /></td>
					<td align="right"><input class="pcpCampoGris" id="alicuotaPCP_<?= $rowPCP["PP_ID"]?>" name="alicuotaPCP_<?= $rowPCP["PP_ID"]?>" readonly type="text" value="" /></td>
				</tr>
<?
}
?>
				<tr>
					<td>TOTAL</td>
					<td align="right"><input class="pcpCampoGris" id="totalTrabajadoresPCP" name="totalTrabajadoresPCP" readonly type="text" value="" /></td>
					<td></td>
					<td align="right"><input class="pcpCampoGris" id="totalAlicuotaPCP" name="totalAlicuotaPCP" readonly type="text" value="" /></td>
				</tr>
			</table>
		</div>
	</div>

<?
if (($_SESSION["entidad"] != 400) and ($_SESSION["entidad"] != 10891) and (($alta) or ((!$alta) and (($row["ESTADO"] == "04") or ($row["ESTADO"] == "06") or ($row["ESTADO"] == "13"))))) {
// Si (no es del Banco Nación) y (no es del CPCECABA) y ((es un alta) o (si no es un alta y no está finalizada))..
?>
	<div id="divResponsabilidadCivil">
		<div id="divResponsabilidadCivilTitulo">
			<span id="spanResponsabilidadCivilTitulo">Responsabilidad Civil Patronal</span>
			<img id="imgResponsabilidadCivilTitulo" src="/modules/solicitud_cotizacion/images/provincia_seguros.gif" />
		</div>
		<div class="ContenidoSeccion">
			<div id="divResponsabilidadCivilDatos">
				<b>¿ Suscribe Póliza de Responsabilidad Civil Patronal ?</b>
				<label for="suscribePolizaRC" id="labelSuscribePolizaRC">SI</label>
				<input <?= (($alta) or ($row["POLIZARC"] == "S"))?"checked":""?> id="suscribePolizaRC" name="suscribePolizaRC" type="radio" value="S" />
				<label for="suscribePolizaRC" id="labelSuscribePolizaRC">NO</label>
				<input <?= ((!$alta) and ($row["POLIZARC"] == "N"))?"checked":""?> id="suscribePolizaRC" name="suscribePolizaRC" type="radio" value="N" />
			</div>
			<label>Selección Suma Asegurada</label>
			<p>
				<input <?= ((!$alta) and ($row["SUMAASEGURADARC"] == "250000"))?"checked":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" type="radio" value="250000" onClick="recalcularRC('<?= ($alta)?0:$_REQUEST["id"]?>', 250000)" />
				<label id="labelSumaAseguradaRC">Hasta $250.000</label>
				<br />
				<input <?= ((!$alta) and ($row["SUMAASEGURADARC"] == "500000"))?"checked":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" type="radio" value="500000" onClick="recalcularRC('<?= ($alta)?0:$_REQUEST["id"]?>', 500000)" />
				<label id="labelSumaAseguradaRC">Hasta $500.000</label>
				<br />
				<input <?= ((!$alta) and ($row["SUMAASEGURADARC"] == "1000000"))?"checked":""?> id="sumaAseguradaRC" name="sumaAseguradaRC" type="radio" value="1000000" onClick="recalcularRC('<?= ($alta)?0:$_REQUEST["id"]?>', 1000000)" />
				<label id="labelSumaAseguradaRC">Hasta $1.000.000</label>
			</p>
		</div>
<?
	if ((!$alta) and (($row["ESTADO"] == "04") or ($row["ESTADO"] == "06") or ($row["ESTADO"] == "13"))) {
?>
		<div class="ContenidoSeccion" id="divRecalcularRC">
			<label id="labelRecalcularRC">Recalcular Póliza de Responsabilidad Civil Patronal</label>
			<p>
				<label>Póliza RC</label>
				<input class="inputNumber" id="polizaRC" name="polizaRC" readonly type="text" value="<?= $row["VALORRC"]?>" />
				<input class="btnActualizar" type="button" value="" onClick="actualizarRC('<?= $_REQUEST["id"]?>')" />
				<span id="spanActualizarOk">Datos actualizados correctamente.</span>
			</p>
		</div>
		<div class="ContenidoSeccion" id="divValoresRC">
			<div align="center" id="divValoresRCTitulo">VALOR COTIZADO DE RESPONSABILIDAD CIVIL PATRONAL</div>
			<div align="center" id="divValoresRCTituloTabla">
				<div id="divValoresRCTituloAlicuotaVariable">Alícuota variable</div>
				<div id="divValoresRCTituloMasaSalarial">Masa salarial</div>
				<div id="divValoresRCTituloCuotaInicial">Cuota inicial resultante</div>
			</div>
			<div align="center" id="divValoresRCValoresTabla">
				<div id="divValoresRCAlicuotaVariable">
					<input class="input2 inputNumber" id="alicuotaVariableRC" readonly type="text" value="<?= trim($row["VALORRCFORMATEADO"])?>" />
				</div>
				<div id="divValoresRCMasaSalarial">
					<input class="input2 inputNumber" id="masaSalarialRC" readonly type="text" value="<?= trim($row["MASASALARIAL"])?>" />
				</div>
				<div id="divValoresRCCuotaInicial">
					<input class="input2 inputNumber" id="cuotaInicialResultanteRC" readonly type="text" value="<?= trim($row["CUOTAINICIALRC"])?>" />
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
//if (($alta) or ($mostrarAumento) or ($mostrarDescuento)) {		// Comentado por pedido de EVila en e-mail del 22.10.2014..
if ($alta) {
?>
				<input class="btnObtenerCotizacion" id="btnGuardar" type="button" value="" onClick="guardarSolicitud()" />
				<img id="imgGuardando" src="/images/loading.gif" />
<?
	if ($alta) {
?>
				<input class="btnCancelar" type="button" value="" onClick="window.location.href = '<?= $paginaAnterior?>'" />
<?
	}
}

if (!$alta) {
?>
				<input class="btnVolver" type="button" value="" onClick="window.location.href = '<?= $paginaAnterior?>'" />
<?
	$sql = "SELECT art.cotizacion.get_imprimircotizacion(:id, :modulo) FROM DUAL";
	$params = array(":id" => $id, ":modulo" => $modulo);
	if ((valorSql($sql, "", $params) == "T") or ($row["ESTADO"] == "13")) {
?>
				<input class="btnCarta" type="button" value="" onClick="window.location.href = '/carta-cotizacion/<?= $_REQUEST["id"]?>'" />
<?
	}

	$sql = "SELECT art.afiliacion.get_imprimirsolicitud(:id, :modulo) FROM DUAL";
	$params = array(":id" => $id, ":modulo" => $modulo);
	if (valorSql($sql, "", $params) == "T") {
?>
				<input class="btnSolicitarAfiliacion" type="button" value="" onClick="window.location.href = '/solicitud-afiliacion/<?= $_REQUEST["id"]?>'" />
<?
	}

	$params = array(":idformulario" => nullIsEmpty($row["IDFORMULARIO"]));
	$sql =
		"SELECT sa_id
			 FROM asa_solicitudafiliacion
			WHERE sa_idformulario = :idformulario";
	$idSolicitudAfiliacion = valorSql($sql, -1, $params);
	if (($idSolicitudAfiliacion > 0) and (($_SESSION["usuario"] == "EDU2824") or ($_SESSION["usuario"] == "ALAPACO") or ($_SESSION["usuario"] == "GGROSSI"))) {
?>
				<input class="btnReImprimirSolicitudAfiliacion" id="btnReimprimir" type="button" value="" onClick="document.getElementById('iframeProcesando').src = '/modules/solicitud_afiliacion/validar_total_rgrl.php?id=<?= $_REQUEST["id"]?>&idSolicitudAfiliacion=<?= $idSolicitudAfiliacion?>&soloPCP=' + iif(soloPCP.checked, 'S', 'N');" />
				<img id="imgImprimiendo" src="/images/loading.gif" title="Reimprimiendo Solicitud de Afiliación..." />
<?
	}

	$sql =
		"SELECT 'T'
			 FROM asa_solicitudafiliacion
			WHERE sa_idformulario = :idformulario
				AND sa_fecharecepcionsectorafi IS NULL";
	$params = array(":idformulario" => nullIsEmpty($row["IDFORMULARIO"]));
	$afiliacionImpresaYNoPresentada = valorSql($sql, 'F', $params);

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
	$numeroAfiliacion = valorSql($sql, "", $params);

	$params = array(":id" => $id, ":modulo" => $modulo);
	$sql = "SELECT art.cotizacion.get_anulacotizacion(:id, :modulo) FROM DUAL";
	if (valorSql($sql, "", $params) == "T") {
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
	with (document) {
		if (getElementById('ciiu2').value != '')
			getElementById('trActividad2').style.display = 'table-row';
		if (getElementById('ciiu3').value != '')
			getElementById('trActividad3').style.display = 'table-row';

		lockControls(<?= ($alta)?"false":"true"?>, <?= ($alta)?"false":"true"?>);
		mostrarSoloPCP(getElementById('soloPCP').checked);
		calcularTotalesPCP();
<?
if (!$alta) {
?>
		verificarPCP(document);
<?
}
if ((isset($_REQUEST["i"])) and ($_REQUEST["i"] == "ok")) {
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
	$tope = floatval(valorSql($sql, 0, $params));
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
	$tope = floatval(valorSql($sql, 0, $params));
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