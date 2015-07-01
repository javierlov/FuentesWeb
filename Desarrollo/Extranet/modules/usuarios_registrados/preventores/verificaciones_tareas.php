<?php 
	session_start();
	require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
	validarSesion(isset($_SESSION["isPreventor"]));
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
	
	require_once("verificaciones_tareas_combos.php");
	
	function PrintComboGrupoDenuncia($FormName, $style=''){
		$printResult = "<select ";
		$printResult .= " class='Combo' ";
		$printResult .= " style='".$style."' ";
		$printResult .= " id='GrupoDenuncia' ";
		$printResult .= " name='GrupoDenuncia' ";
		//$printResult .= " onchange=\"AjaxRequest('DivDetallePedido', 'ajax_detalle_denuncias.php', document.".$FormName.".GrupoDenuncia.options[document.".$FormName.".GrupoDenuncia.selectedIndex].value, '', '', '".$style."'); \" >";
		$printResult .= "</select>";
		
		return $printResult;
	}
	
	$_SESSION["verificacionTarea"]["enfermedad"]["cumplimiento"] =array();
	$_SESSION["verificacionTarea"]["enfermedad"]["incumplimiento"]	= array();
	$_SESSION["verificacionTarea"]["accidente"]["cumplimiento"] =array();
	$_SESSION["verificacionTarea"]["accidente"]["incumplimiento"]	= array();
	$_SESSION["verificacionTarea"]["pal"]["cumplimiento"] =array();
	$_SESSION["verificacionTarea"]["pal"]["incumplimiento"]	= array();
	$_SESSION["verificacionTarea"]["prs"]["cumplimiento"] =array();
	$_SESSION["verificacionTarea"]["prs"]["incumplimiento"]	= array();
	$_SESSION["verificacionTarea"]["463"]["cumplimiento"] =array();
	$_SESSION["verificacionTarea"]["463"]["incumplimiento"]	= array();
		
	
/*set_time_limit(240);
*/
$showProcessMsg = true;


$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "3_D_";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$solapa = "tsAccidente";
if (isset($_REQUEST["solapa"]))
	$solapa = $_REQUEST["solapa"];

$seleccionado = false;
$seleccionlabel = false;
/*$params = array(":id" => $_SESSION["entidad"]);
$sql = 
	"SELECT en_codbanco || ' - ' || en_nombre
		 FROM xen_entidad
		WHERE en_id = :id";
$entidad = valorSql($sql, "", $params);*/
?>
<html 
	<head>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<link rel="stylesheet" href="/styles/design.css" type="text/css" />
		<link rel="stylesheet" href="/styles/portada.css" type="text/css" />
<!--		<link rel="stylesheet" href="/styles/reset.css" type="text/css" />-->
		<link rel="stylesheet" href="/styles/style2.css?rnd=20141202" type="text/css" />
<!--		<link rel="apple-touch-icon" sizes="114×114" href="favicon_apple.png" />-->
		<link rel="shortcut icon" type="image/x-icon" href="favicon2.ico" />	

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Provincia ART es una de las empresas aseguradoras del Grupo Banco Provincia, especializada en la prestación del seguro de cobertura de riesgos del trabajo." />
		<meta name="Language" content="Spanish" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script src="/js/browser.js" type="text/javascript"></script>
		<script src="/js/functions.js?rnd=20130802" type="text/javascript"></script>
		<script src="/js/grid.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>

		<title>..:: Provincia ART ::..</title>

		<!-- INICIO HINT.. -->
		<script language="JavaScript" src="/js/hint/hints.js"></script>
		<!-- FIN HINT.. -->

		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->

		<!-- INICIO SCRIPT ACCESIBILIDAD.. -->
		<script type="text/javascript">
			(function(){
				var i7e_e = document.createElement("script");
				var i7e_t = window.location.host;
				i7e_e.type = "text/javascript";
				return i7e_r = "es-ES", i7e_t=i7e_t.replace(/\./g,"--"), i7e_t+=".accesible.inclusite.com",
																i7e_e.src=("https:"==document.location.protocol?"https://":"http://") + i7e_t + "/inclusite/frameworks_initializer.js?lng=" + i7e_r, document.getElementsByTagName("head")[0].appendChild(i7e_e), i7e_e.src})()
		</script>
		<!-- FIN SCRIPT ACCESIBILIDAD.. -->

		<style>
			#divPopup {background-color:#0f539c; filter:alpha(opacity = 30); height:100%; left:0px; opacity:.3; position:absolute; top:0px; width:100%; z-index:99;}
			#divPopupTexto {background-color:#d8d8da; border:1px solid #808080; font-size:10pt; height:328px; left:50%; margin-left:-336px; padding:0px; position:absolute; top:80px; width:672px; z-index:100;}
		</style>
	</head>

	<body>
	<script type="text/javascript">
		function submitForm() {
			document.getElementById('btnGuardar').style.display = 'none';
			document.getElementById('divProcesando').style.display = 'block';
			return true;
		}
	</script>
	<script src="/modules/usuarios_registrados/preventores/js/verificacion_tarea.js" type="text/javascript"></script>
	<link rel="stylesheet" href="/styles/style.css" type="text/css" />
	<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
	<div class="TituloSeccion" style="display:block; width:99%;">Verificaciones</div>
	<div class="SubtituloSeccion" style="color:#5ba4a4; margin-top:8px;"></div>
	<div class="ContenidoSeccion" style="margin-top:8px; width:98%;">
		<div style="background-color:#D5D5D5; padding-bottom:2px; padding-top:2px;">
			<label class="ContenidoSeccion" id="labelAccidente" style="color:#FFF; font-family: Neo Sans; cursor:hand; padding-bottom:2px; padding-top:4px
				   <? if(strpos($_SESSION['tabVisible'],'tsAccidente')===false){echo ";display:none";}else{if(!($seleccionlabel)){echo ";background-color:#0F539C"; $seleccionlabel= true;}}?>" onClick="cambiarSolapa('tsAccidente')" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)">Accidentes</label>
			<label class="ContenidoSeccion" id="labelEnfermedades" style="color:#FFF; font-family: Neo Sans; cursor:hand; padding-bottom:2px; padding-top:4px
				   <? if(strpos($_SESSION['tabVisible'],'tsEnfermedad')===false){echo ";display:none";}else{if(!($seleccionlabel)){echo ";background-color:#0F539C"; $seleccionlabel= true;}}?>	" onClick="cambiarSolapa('tsEnfermedades')" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)">Enfermedades</label>
			<label class="ContenidoSeccion" id="labelPRS" style="color:#FFF; font-family: Neo Sans; cursor:hand; padding-bottom:2px; padding-top:4px
				   <? if(strpos($_SESSION['tabVisible'],'tsPRS')===false){echo ";display:none";}else{if(!($seleccionlabel)){echo ";background-color:#0F539C"; $seleccionlabel= true;}}?>" onClick="cambiarSolapa('tsPRS')" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)">PRS</label>
			<label class="ContenidoSeccion" id="labelPAL" style="color:#FFF; font-family: Neo Sans; cursor:hand; padding-bottom:2px; padding-top:4px
				   <? if(strpos($_SESSION['tabVisible'],'tsPAL')===false){echo ";display:none";}else{if(!($seleccionlabel)){echo ";background-color:#0F539C"; $seleccionlabel= true;}}?>" onClick="cambiarSolapa('tsPAL')" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)">PAL</label>
			<label class="ContenidoSeccion" id="labelVerosimilitud" style="color:#FFF; font-family: Neo Sans; cursor:hand; padding-bottom:2px; padding-top:4px
				   <? if(strpos($_SESSION['tabVisible'],'ts463')===false){echo ";display:none";}else{if(!($seleccionlabel)){echo ";background-color:#0F539C"; $seleccionlabel= true;}}?>" onClick="cambiarSolapa('ts463')" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)">Verosimilitud</label>
			<label class="ContenidoSeccion" id="labelBasica" style="color:#FFF; font-family: Neo Sans; cursor:hand; padding-bottom:2px; padding-top:4px
				   <? if(strpos($_SESSION['tabVisible'],'tsBasica')===false){echo ";display:none";}else{if(!($seleccionlabel)){echo ";background-color:#0F539C"; $seleccionlabel= true;}}?>" onClick="cambiarSolapa('tsBasica')" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)">Basica</label>
		</div>
		<form action="/modules/usuarios_registrados/preventores/procesar_verificaciones_tareas.php" id="formVerificacionTareas" method="post" name="formVerificacionTareas" target="iframeProcesando" onSubmit="return submitForm(true)">
		<input id="solapa" name="solapa" type="hidden" value="<?= $solapa?>" />
		<div id="divAccidente" style="border:2px solid #D5D5D5;overflow-y:scroll
			<? if(!($seleccionado) && !(strpos($_SESSION['tabVisible'],'tsAccidente')===false)){ echo ";display:block"; $seleccionado = true;}else{echo ";display:none";}?>">
				<div align="left" id="divContentGrid" name="divContentGrid" style="margin-bottom:4px; margin-left:20px; margin-top:8px; overflow:auto;">
				<?
				$param = array(":cuit" => $_SESSION["CARGA_TAREA"]["cuit"], ":nroestableci" => $_SESSION["CARGA_TAREA"]["establecimiento"]); 
				$sql =
					"SELECT	mc_id ¿id1?,mc_id ¿id2?,art.hys_prevencionweb.get_ultseguimientoaccidente(mc_id) ¿ultseguimiento?, ex_siniestro || '/' || ex_orden || '/' || ex_recaida ¿siniestro?, 
							mc_medida ¿medida?, mc_fechaejecucion ¿fechaejecucion?, 
							mc_fechaverificacion ¿fechaverificacion?, mc_descripcion ¿observacion?
						 FROM hys.pmc_medidacorrectiva,
							  hys.pae_accidenteestablecimiento,
							  art.sex_expedientes,
							  afi.aes_Establecimiento 
						WHERE ae_idexpediente = ex_id
						  AND mc_idaccidenteestablecimiento = ae_id
						  AND ae_cuit = :cuit
						  AND ae_nroestablecimiento = es_nroestableci
						  AND es_id = :nroestableci	";

				$grilla = new Grid(1000);
				$grilla->addColumn(new Column("Cump.", 1, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/preventores/seleccionar_accidente.php?origen=c", "", -1, true, -1, "", false, "", "checkbox", 6));
				$grilla->addColumn(new Column("Incump.", 1, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/preventores/seleccionar_accidente.php?origen=i", "", -1, true, -1, "", false, "", "checkbox", 6));
				$grilla->addColumn(new Column("Ult. Estado"));
				$grilla->addColumn(new Column("Siniestro"));
				$grilla->addColumn(new Column("Medida"));
				$grilla->addColumn(new Column("Fecha Ejecución"));
				$grilla->addColumn(new Column("Fecha Verificación"));
				$grilla->addColumn(new Column("Observación"));
				$grilla->setOrderBy("4,5");
				//$grilla->addColumn(new Column("Usu.Baja"));
				//$grilla->addColumn(new Column("Fecha Baja"));
				//$grilla->setExtraConditions(array($where));
				
				//$grilla->setPageNumber($pagina);
				$grilla->setUseTmpIframe(true);
				$grilla->setParams($param);
				$grilla->setShowProcessMessage(true);
				$grilla->setShowTotalRegistros(true);
				$grilla->setSql($sql);
				$grilla->setTableStyle("GridTableCiiu");
				$grilla->Draw();
				
				?>
				</div>
				<div align="center" id="divProcesandoAccidente" name="divProcesandoAccidente" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; margin-bottom:4px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		</div>
		<div id="divEnfermedades" style="border:2px solid #D5D5D5;overflow-y:scroll
			 <? if(!($seleccionado) && !(strpos($_SESSION['tabVisible'],'tsEnfermedad')===false)){ echo ";display:block"; $seleccionado = true;}else{echo ";display:none";}?>">
				<div align="left" id="divContentGrid" name="divContentGrid" style="margin-bottom:4px; margin-left:20px; margin-top:8px; overflow:auto; ">
				<?
				$param = array(":cuit" => $_SESSION["CARGA_TAREA"]["cuit"], ":nroestableci" => $_SESSION["CARGA_TAREA"]["establecimiento"]); 
				$sql =
					"SELECT	mc_id ¿id1?,mc_id ¿id2?,art.hys_prevencionweb.get_ultseguimientoenfermadad(mc_id) ¿ultseguimiento?, ex_siniestro || '/' || ex_orden || '/' || ex_recaida ¿siniestro?, 
							mc_medida ¿medida?, mc_fechaejecucion ¿fechaejecucion?, mc_fechaverificacion ¿fechaverificacion?, 
							mc_descripcion ¿observacion?
					FROM hys.pee_enfermedadestablecimiento, 
						 art.sex_expedientes, 
						 hys.pmc_medidacorrectivaenf,
						 afi.aes_Establecimiento 
				   WHERE ee_idexpediente = ex_id 
					 AND ee_cuit = :cuit
					 AND mc_idenfermedadestablecimiento = ee_id 
					 AND ee_nroestablecimiento = es_nroestableci
					 AND es_id = :nroestableci";

				$grilla = new Grid(1000);
				$grilla->addColumn(new Column("Cump.", 1, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/preventores/seleccionar_enfermedad.php?origen=c", "", -1, true, -1, "", false, "", "checkbox", 6));
				$grilla->addColumn(new Column("Incump.", 1, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/preventores/seleccionar_enfermedad.php?origen=i", "", -1, true, -1, "", false, "", "checkbox", 6));
				$grilla->addColumn(new Column("Ult. Estado"));
				$grilla->addColumn(new Column("Siniestro"));
				$grilla->addColumn(new Column("Medida"));
				$grilla->addColumn(new Column("Fecha Ejecución"));
				$grilla->addColumn(new Column("Fecha Verificación"));
				$grilla->addColumn(new Column("Observación"));
				//$grilla->addColumn(new Column("Usu.Baja"));
				//$grilla->addColumn(new Column("Fecha Baja"));
				//$grilla->setExtraConditions(array($where));
				$grilla->setOrderBy("4,5");
				//$grilla->setPageNumber($pagina);
				$grilla->setParams($param);
				$grilla->setUseTmpIframe(true);
				$grilla->setShowProcessMessage(true);
				$grilla->setShowTotalRegistros(true);
				$grilla->setSql($sql);
				$grilla->setTableStyle("GridTableCiiu");
				$grilla->Draw();
				?>
				</div>
				<div align="center" id="divProcesandoAccidente" name="divProcesandoAccidente" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; margin-bottom:4px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
			
		</div>
		<div id="divPRS" style="border:2px solid #D5D5D5;overflow-y:scroll
			  <? if(!($seleccionado) && !(strpos($_SESSION['tabVisible'],'tsPRS')===false)){ echo ";display:block"; $seleccionado = true;}else{echo ";display:none";}?>">
				<div align="left" id="divContentGrid" name="divContentGrid" style="margin-bottom:4px; margin-left:20px; margin-top:8px; overflow:auto;">
				<?
				$param = array(":cuit" => $_SESSION["CARGA_TAREA"]["cuit"], ":nroestableci" => $_SESSION["CARGA_TAREA"]["establecimiento"]); 
				$sql =
					"SELECT	re_id ¿id1?,re_id ¿id2?, art.hys_prevencionweb.get_ultseguimientoprs(RE_CUIT,RE_ESTABLECI,RE_TIPO,RE_OPERATIVO,RE_RECOMENDACION) ¿ultseguimiento?, 
							re_recomendacion ¿nro?, re_descripcion1 ¿recomendacion?,  re_responsable ¿responsable?, re_cumplimiento ¿cumplimiento?,
							re_seguimiento ¿seguimiento?
					   FROM art.pre_recomendaciones,
							afi.aes_Establecimiento 
					  WHERE re_cuit = :cuit 
						AND re_estableci = es_nroestableci
						AND es_id = :nroestableci
						AND re_fechabaja IS NULL ";

				$grilla = new Grid(1000);
				$grilla->addColumn(new Column("Cump.", 1, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/preventores/seleccionar_prs.php?origen=c", "", -1, true, -1, "", false, "", "checkbox", 6));
				$grilla->addColumn(new Column("Incump.", 1, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/preventores/seleccionar_prs.php?origen=i", "", -1, true, -1, "", false, "", "checkbox", 6));
				$grilla->addColumn(new Column("Ult. Estado"));
				$grilla->addColumn(new Column("Nro"));
				$grilla->addColumn(new Column("Recomendación"));
				$grilla->addColumn(new Column("Responsable"));
				$grilla->addColumn(new Column("Cumplimiento"));
				$grilla->addColumn(new Column("Seguimiento"));
				$grilla->setUseTmpIframe(true);
				$grilla->setParams($param);
				$grilla->setShowProcessMessage(true);
				$grilla->setShowTotalRegistros(true);
				$grilla->setSql($sql);
				$grilla->setTableStyle("GridTableCiiu");
				$grilla->Draw();
				?>
				</div>
				<div align="center" id="divProcesandoAccidente" name="divProcesandoAccidente" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; margin-bottom:4px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		</div>
		<div id="divPAL" style="border:2px solid #D5D5D5;overflow-y:scroll
			 <? if(!($seleccionado) && !(strpos($_SESSION['tabVisible'],'tsPAL')===false)){ echo ";display:block"; $seleccionado = true;}else{echo ";display:none";}?>">
				<div align="left" id="divContentGrid" name="divContentGrid" style="margin-bottom:4px; margin-left:20px; margin-top:8px; overflow:auto;">
				<?
				$param = array(":cuit" => $_SESSION["CARGA_TAREA"]["cuit"], ":nroestableci" => $_SESSION["CARGA_TAREA"]["establecimiento"]); 
				$sql =
					"SELECT	pa_id ¿id1?,pa_id ¿id2?,art.hys_prevencionweb.get_ultseguimientopal(pa_id) ¿ultseguimiento?,ai_anexosrt ¿tipoanexo?,pa_itemanexo ¿itemanexo?,DECODE (pa_noamerita, 'S', 'No Amerita', ai_descripcion) ¿descitem?,
							pa_cumplimiento ¿cumplimiento?,pa_seguimiento ¿seguimiento?,pa_tipo ¿tipo?, pa_operativo ¿operativo?
							,pa_fechacumplimientopost ¿fcumpposterior?
					   FROM hys.hpa_pal, 
							art.pai_anexo2items,
							afi.aes_Establecimiento 
					  WHERE pa_anexo = ai_anexo(+)
						AND ai_codigo(+) = pa_itemanexo
						AND pa_cuit = :cuit
						AND pa_estableci = es_nroestableci
						AND es_id = :nroestableci
				   ORDER BY pa_itemanexo ";

				$grilla = new Grid(1000);
				$grilla->addColumn(new Column("Cump.", 1, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/preventores/seleccionar_pal.php?origen=c", "", -1, true, -1, "", false, "", "checkbox", 6));
				$grilla->addColumn(new Column("Incump.", 1, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/preventores/seleccionar_pal.php?origen=i", "", -1, true, -1, "", false, "", "checkbox", 6));
				$grilla->addColumn(new Column("Ult. Estado"));
				$grilla->addColumn(new Column("Anexo"));
				$grilla->addColumn(new Column("Nro.Item"));
				$grilla->addColumn(new Column("Desc.Item"));
				$grilla->addColumn(new Column("Fecha Cumplimiento"));
				$grilla->addColumn(new Column("Fecha Seguimiento"));
				$grilla->addColumn(new Column("Tipo"));
				$grilla->addColumn(new Column("Operativo"));
				$grilla->addColumn(new Column("F.Cumplimiento Posterior"));
				
				//$grilla->addColumn(new Column("Usu.Baja"));
				//$grilla->addColumn(new Column("Fecha Baja"));
				//$grilla->setExtraConditions(array($where));
				//$grilla->setOrderBy($ob);
				//$grilla->setPageNumber($pagina);
				$grilla->setUseTmpIframe(true);
				$grilla->setParams($param);
				$grilla->setShowProcessMessage(true);
				$grilla->setShowTotalRegistros(true);
				$grilla->setSql($sql);
				$grilla->setTableStyle("GridTableCiiu");
				$grilla->Draw();
				?>
				</div>
				<div align="center" id="divProcesandoAccidente" name="divProcesandoAccidente" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; margin-bottom:4px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		</div>
		<div id="div463" style="border:2px solid #D5D5D5;overflow-y:scroll
			 <? if(!($seleccionado) && !(strpos($_SESSION['tabVisible'],'ts463')===false)){ echo ";display:block"; $seleccionado = true;}else{echo ";display:none";}?>">
				<div align="left" id="divContentGrid" name="divContentGrid" style="margin-bottom:4px; margin-left:20px; margin-top:8px; overflow:auto;">
				<?
				$param = array(":contrato" => $_SESSION["CARGA_TAREA"]["contrato"], ":nroestableci" => $_SESSION["CARGA_TAREA"]["establecimiento"]); 
				$sql =
					"SELECT	il_id ¿id1?,il_id ¿id2?,art.hys_prevencionweb.get_ultseguimiento463(rl_id,ia_id) ¿ultseguimiento?, ia_nrodescripcion ¿nrodescripcion?, ia_descripcion ¿descripcion?, il_fecharegularizacion ¿fecharegularizacion?, 
							il_fechaverificacion ¿fechaverificacion?
					   FROM hys.hrl_relevriesgolaboral,
							afi.aem_empresa,
							afi.aco_contrato,
							hys.hil_itemsriesgolaboral,
							hys.hia_itemanexo,
							afi.aes_Establecimiento
					  WHERE rl_id = il_idrelevriesgolaboral
						AND rl_fechabaja IS NULL
						AND ia_id = il_iditemanexo
						AND em_id = co_idempresa
						AND co_contrato = rl_contrato
						AND (   (ia_idtipoformanexo IS NULL AND il_cumplimiento = 'N')
						 OR (ia_idtipoformanexo IS NOT NULL AND il_cumplimiento = 'S')
							)
						AND rl_id = art.hys.get_ultidrelev463 (:contrato, es_nroestableci, 'P')
						AND es_id = :nroestableci
				   ORDER BY ia_id ";

				$grilla = new Grid(1000);
				$grilla->addColumn(new Column("Cump.", 1, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/preventores/seleccionar_463.php?origen=c", "", -1, true, -1, "", false, "", "checkbox", 6));
				$grilla->addColumn(new Column("Incump.", 1, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/preventores/seleccionar_463.php?origen=i", "", -1, true, -1, "", false, "", "checkbox", 6));
				$grilla->addColumn(new Column("Ult. Estado"));
				$grilla->addColumn(new Column("Nro Descripción"));
				$grilla->addColumn(new Column("Descripción"));
				$grilla->addColumn(new Column("Fecha Regularización"));
				$grilla->addColumn(new Column("Fecha Verificación"));
				$grilla->setUseTmpIframe(true);
				$grilla->setParams($param);
				$grilla->setShowProcessMessage(true);
				$grilla->setShowTotalRegistros(true);
				$grilla->setSql($sql);
				$grilla->setTableStyle("GridTableCiiu");
				$grilla->Draw();
				?>
				</div>
				<div align="center" id="divProcesandoAccidente" name="divProcesandoAccidente" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; margin-bottom:4px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		</div>
		<div id="divBasica" style="border:2px solid #D5D5D5;
			 <? if(!($seleccionado) && !(strpos($_SESSION['tabVisible'],'tsBasica')===false)){ echo ";display:block"; $seleccionado = true;}else{echo ";display:none";}?>">
				<div align="left" id="divContentGrid" name="divContentGrid" style="margin-bottom:4px; margin-left:20px; margin-top:8px; overflow:auto;">
					<label>Grupo</label>
					<div style="width:310px;">			
						<? $comboGrupoDenuncia->draw(); ?>
					</div>
					<div id="divBasicaDetalleDenuncia" style="margin-top:5px;overflow-y:scroll;height:200px;border:thick solid #DDDDDD;border-width:3px">
					</div>
				</div>
				<div align="center" id="divProcesandoBasica" name="divProcesandoBasica" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none; margin-bottom:4px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		</div>
		<div style="text-align:right;margin-top:8px;margin-right:5px;">
			<input class="btnGuardar" id="btnGuardar" name="btnGuardar" type="submit" value="" />
			
		</div>
		<div id="divErroresForm" style="display:none">
			<img src="/images/atencion.jpg" />
			<span>No es posible continuar mientras no se corrijan los siguientes errores:</span>
			<br />
			<br />
			<span id="errores"></span>
			<input id="foco" name="foco" readonly type="checkbox" />
		</div>
		</form>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:10px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		
	</div>
	</body>
</html>


