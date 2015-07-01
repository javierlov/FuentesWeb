<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


if ((isset($_REQUEST["al"])) and ($_REQUEST["al"] == "t")) {
	$origen = "";
	if (isset($_REQUEST["o"]))
		$origen = $_REQUEST["o"];
	header("Location: validar_login.php?o=".$origen."&al=t&p=establecimientos&contrato=".$_REQUEST["contrato"]."&cuit=".$_REQUEST["cuit"]);
	exit;
}

if (!isset($_SESSION["contrato"])) {
	header("Location: login.php");
	exit;
}

if ((!isset($_SESSION["origen"])) or ($_SESSION["origen"] != "ehys")) {		// Si no se entró desde el e-mail de hys..
	$params = array(":contrato" => $_SESSION["contrato"]);
	$sql =
		"UPDATE hys.hrg_relevgestion
				SET rg_fechaingreso = SYSDATE
			WHERE rg_contrato = :contrato
				AND TO_NUMBER(rg_vigencia) = (SELECT MAX(TO_NUMBER(rg_vigencia))
																				FROM hys.hrg_relevgestion
																			 WHERE rg_contrato = :contrato
																				 AND rg_fechabaja IS NULL)
				AND rg_fechaingreso IS NULL
				AND rg_fechabaja IS NULL";
	DBExecSql($conn, $sql, $params);
}
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Establecimientos</title>
		<link href="/modules/formulario_establecimientos/css/establecimientos.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			body {scrollbar-face-color:#aaa; scrollbar-highlight-color:#aaa; scrollbar-shadow-color:#aaa; scrollbar-3dlight-color:#eee; scrollbar-arrow-color:#eee; scrollbar-track-color:#e3e3e3;
						scrollbar-darkshadow-color:#fff;}
		</style>
	</head>

	<body topmargin="5" link="#00539B" vlink="#00539B" alink="#00539B" bottommargin="5">
		<span color="#999999" face="Trebuchet MS" style="font-family: Trebuchet MS; font-size: 10pt; color:#807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><b>LISTADO DE ESTABLECIMIENTOS</b><br></span>
		<span>&nbsp;</span>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div align="center" id="divContent" name="divContent">
	<?
	$ob = "1";
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$imgStyle = "style=\"left:2px; margin-right:8px; position:relative; top:2px;\"";
	$showProcessMsg = false;

	$params = array(":contrato" => $_SESSION["contrato"]);
	$sql =
		"SELECT ¿es_nroestableci?,
						art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, NULL) ¿domicilio?, ¿es_localidad?,
						DECODE(es_cpostala, '99999999', es_cpostal, DECODE(es_cpostala, NULL, es_cpostal, es_cpostala)) ¿cpostal?,
						¿pv_descripcion?,
						DECODE(rl_valido, 'S', '<img src=\"/modules/formulario_establecimientos/images/valido.jpg\" ".$imgStyle." />VÁLIDO', 'N', '<img src=\"/modules/formulario_establecimientos/images/novalido.jpg\" ".$imgStyle." />NO VÁLIDO') AS ¿status?,
						¿es_id?,
						'F' AS ¿hidecell?
			 FROM cpv_provincias, afi.aes_establecimiento, afi.aem_empresa, afi.aco_contrato, hys.hrl_relevriesgolaboral a
			WHERE rl_contrato = co_contrato
				AND em_id = co_idempresa
				AND es_contrato = co_contrato
				AND es_nroestableci = rl_estableci
				AND es_provincia = pv_codigo
				AND co_contrato = :contrato
				AND rl_vigencia = (SELECT MAX(b.rl_vigencia)
														 FROM hys.hrl_relevriesgolaboral b
														WHERE b.rl_contrato = a.rl_contrato
															AND b.rl_fechabaja IS NULL
															AND b.rl_procedencia = 'E'
															AND b.rl_estableci = a.rl_estableci)
				AND es_fechabaja IS NULL
				AND rl_fechabaja IS NULL
				AND rl_procedencia = 'E'
				AND art.hys.get_requiererelev463(es_contrato, es_nroestableci) = 'S'
			UNION
		 SELECT es_nroestableci,
						art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, NULL), es_localidad,
						DECODE(es_cpostala, '99999999', es_cpostal, DECODE(es_cpostala, NULL, es_cpostal, es_cpostala)) cpostal,
						pv_descripcion,
						'<img src=\"/modules/formulario_establecimientos/images/nopresentado.jpg\" ".$imgStyle." />NO PRESENTADO',
						es_id,
						'T'
			 FROM cpv_provincias, afi.aes_establecimiento, afi.aem_empresa, afi.aco_contrato
			WHERE em_id = co_idempresa
				AND es_contrato = co_contrato
				AND es_provincia = pv_codigo
				AND co_contrato = :contrato
				AND es_fechabaja IS NULL
				AND art.hys.get_requiererelev463(es_contrato, es_nroestableci) = 'S'
				AND NOT EXISTS(SELECT 1
												 FROM hys.hrl_relevriesgolaboral a
												WHERE rl_contrato = co_contrato
													AND es_nroestableci = rl_estableci
													AND rl_vigencia = (SELECT MAX(b.rl_vigencia)
																							 FROM hys.hrl_relevriesgolaboral b
																							WHERE b.rl_contrato = a.rl_contrato
																								AND b.rl_fechabaja IS NULL
																								AND b.rl_procedencia = 'E'
																								AND b.rl_estableci = a.rl_estableci)
																								AND rl_fechabaja IS NULL
				AND rl_procedencia = 'E')";
	$grilla = new Grid();
	$grilla->addColumn(new Column("Nº"));
	$grilla->addColumn(new Column("Domicilio"));
	$grilla->addColumn(new Column("Localidad"));
	$grilla->addColumn(new Column("C.P."));
	$grilla->addColumn(new Column("Provincia"));
	$grilla->addColumn(new Column("Status", 0, true, false, -1, "", "", "colStatus", -1, false));
	$grilla->addColumn(new Column("", 0, true, false, -1, "botonDescargar", "ver_pdf.php", "gridFirstColumn"));
	$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 7));
	$grilla->setClassToHideLastPartOfTheFooter("classToHideLastPartOfTheFooter");
	$grilla->setColsSeparator(true);
	$grilla->setDecodeSpecialChars(true);
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setSql($sql);
	$grilla->Draw();
	?>
		</div>
		<br>
		<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<div align="center">
			<table border="0" cellpadding="0" width="710" id="table1">
				<tr>
					<td colspan="2" height="40">
						<p style="margin-top: 0; margin-bottom: 0">
						<font face="Trebuchet MS" color="#807F84">
							<span style="font-size: 8pt">Recuerde que usted debe presentar un formulario de Relevamiento General de Riesgos Laborales (RGRL)<b> por cada establecimiento activo</b>, al momento de su 
						afiliación o antes de que opere la renovación de su contrato vigente.</span></font></td>
				</tr>
				<tr>
					<td width="22" align="center">
					<p style="margin-top: 0; margin-bottom: 0"><font color="#807F84">
					<img border="0" src="images/TildeVerde_fondo_blanco.jpg" width="15" height="15"></font></td>
					<td width="688">
					<p style="margin-top: 0; margin-bottom: 0"><span style="font-size: 8pt">
					<font face="Trebuchet MS" color="#807F84">VALIDO : La presentación del RGRL del establecimiento es válida.</font></span></td>
				</tr>
				<tr>
					<td colspan="2" height="5"></td>
				</tr>
				<tr>
					<td width="22" align="center" valign="top">
					<p style="margin-top: 0; margin-bottom: 0"><font color="#807F84">
					<img border="0" src="images/CruzRoja_fondo_blanco.jpg" width="15" height="15"></font></td>
					<td width="688">
					<p style="margin-top: 0; margin-bottom: 0"><span style="font-size: 8pt">
					<font face="Trebuchet MS" color="#807F84">NO VALIDO : La presentación del RGRL del establecimiento está incompleta o presenta inconsistencias en los datos declarados. A partir del archivo adjunto, usted puede completar el RGRL, firmarlo y enviarlo lo antes posible a Provincia ART.</font></span><span class="307224914-12062009"><p style="margin-top: 0; margin-bottom: 0">
							<b>
								<span style="FONT-SIZE: 8pt">
									<font face="Trebuchet MS" color="#00539B">&gt;</font>
									</span>
							</b>
								<font style="FONT-SIZE: 8pt" face="Trebuchet MS" color="#808080">CONSEJO DE IMPRESIÓN:</font><b>
								</b>
							<span style="FONT-SIZE: 8pt">
								<font face="Trebuchet MS" color="#808080">Al elegir la opción imprimir, ajuste la orientación del papel a vertical y elija como tamaño de papel Oficio/Legal.</font></span></td>
				</tr>
				<tr>
					<td colspan="2" height="5"></td>
				</tr>
				<tr>
					<td width="22" align="center" valign="top">
					<p style="margin-top: 0; margin-bottom: 0"><img border="0" src="images/CruzRoja_fondo_blanco.jpg" width="15" height="15"></td>
					<td width="688">
					<p style="margin-top: 0; margin-bottom: 0"><span style="font-size: 8pt">
					<font face="Trebuchet MS" color="#807F84">NO PRESENTADO : La presentación del RGRL del establecimiento no se ha realizado.</font></span></td>
				</tr>
			</table>
		</div>
		<p align="center">
			<span color="#999999" style="font-family: Trebuchet MS; font-size: 8pt"><a href="conten.php"><b>Ir a la página principal</b></a></span>
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
		</script>
	</body>
</html>