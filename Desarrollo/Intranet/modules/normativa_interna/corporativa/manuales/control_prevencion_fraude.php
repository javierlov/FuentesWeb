<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");

$muestroCotrol = false;
$urlVolver = "";
	$params = array(":idusuario" => GetUserID());
	$sql =
		" SELECT 1
			 FROM rrhh.rno_notificaciones
			WHERE NO_IDUSUARIO = :idusuario
			  AND NO_MANUALFRAUDE IS NOT NULL ";
	if( !ExisteSql($sql, $params))		// Muestro control
		$muestroCotrol = true;
		
if ((isset($_REQUEST["noti"])) and ($_REQUEST["noti"] == "s") and ($muestroCotrol )) {
	try {
	
		echo 'muestro control';
		$params = array(":idusuario" => GetUserID());
		$sql =
			"SELECT 1
				 FROM rrhh.rno_notificaciones
				WHERE NO_IDUSUARIO = :idusuario";

		if (!ExisteSql($sql, $params)) {		// Alta..
			$params = array(":idusuario" => GetUserID(), ":pcmanualpla" => GetPCName());
			$sql =
				"INSERT INTO rrhh.rno_notificaciones (NO_IDUSUARIO, NO_MANUALFRAUDE, NO_PCMANUALFRAUDE)
																			VALUES (:idusuario, SYSDATE, :pcmanualpla)";
			DBExecSql($conn, $sql, $params);
		}
		else {		// Modificación..
			$params = array(":idusuario" => GetUserID(), ":pcmanualpla" => GetPCName());
			$sql =
				"UPDATE rrhh.rno_notificaciones
						SET NO_MANUALFRAUDE = SYSDATE,
								NO_PCMANUALFRAUDE = :pcmanualpla
					WHERE NO_IDUSUARIO = :idusuario";
			DBExecSql($conn, $sql, $params);
		}
		
		header('Location: '."http://".$_SERVER['HTTP_HOST'].'/prevencion-fraude');
		
	}
	catch (Exception $e) {
		DBRollback($conn);
		echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
		exit;
	}
}

$params = array(":idusuario" => GetUserID());
$sql =
	"SELECT NO_MANUALFRAUDE
		 FROM rrhh.rno_notificaciones
		WHERE NO_IDUSUARIO = :idusuario";
$fechaAceptacion = ValorSql($sql, "", $params);
?>
<script>
	function guardar() {				
		if (!document.getElementById('notificado').checked) {
			alert('Antes de guardar debe tildar el cuadro "ME NOTIFICO".');
			return;
		}
		
		window.location.href = '<?= $_SERVER["REQUEST_URI"]?>?noti=s';		
	}

	function ocultarMensaje() {
		// document.getElementById('tableMensaje').style.display = 'none';
	}
</script>


<link href="/styles/style_Fraude.css" rel="stylesheet" />

<div style="margin-left:10%; margin-top:10px; text-align:center; max-width:80%;  ">
<div style="min-width:800px; ">
		
	<div class=WordSection1>
	<p>&nbsp;</p>
	<p align="right"><img border="0" src="/modules/normativa_interna/corporativa/manuales/logoART.jpg" /></p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>


	<p class=MsoNormal align=center style='margin-top:0cm; margin-bottom:.95pt;text-align:center;text-indent:-9.6pt; line-height:115%'>
	<b><span style='font-size:16.0pt;line-height:115%'>
	Manual de Control y Prevención de Fraude<br/> (Resolución SSN 38.477) </span></b></p>

	<p>&nbsp; </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:6.85pt;margin-left:0cm;text-align:left;text-indent:0cm;
	line-height:107%'> </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:6.95pt;margin-left:0cm;text-align:left;text-indent:0cm;
	line-height:107%'> </p>


	<h1>TEMARIO </h1>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:4.95pt;margin-left:0cm;text-align:left;text-indent:0cm;
	line-height:107%'><span style='font-size:8.0pt;line-height:107%;font-family:
	"Segoe UI",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm; margin-bottom:7.45pt;margin-left:0cm;text-align:left;text-indent:0cm; line-height:107%'><span style='font-size:8.0pt;line-height:107%;font-family:
	"Segoe UI",sans-serif'> </span></p>

	<div class='indice' >
	<h2>
	1.<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span>OBJETO<p/>
	2.<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span>DEFINICIONES<p/>
	3.<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span>POLÍTICAS Y OBJETIVOS<p/>
	4.<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span>CLASIFICACIÓN Y ACCIONES QUE PUEDEN CONSTITUIR FRAUDE<p/>
	</h2>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm; margin-bottom:4.4pt;margin-left:60pt;text-align:left;line-height:160%'>
	<i><span style='font-size:10.0pt;line-height:160%'>4.1.</span></i><span style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size: 10.0pt;line-height:160%'>CLASIFICACIÓN<p/>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm; margin-bottom:4.4pt;margin-left:60pt;text-align:left;line-height:160%'>
	<i><span style='font-size:10.0pt;line-height:160%'>4.1.</span></i><span style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size: 10.0pt;line-height:160%'>ACCIONES QUE PUEDEN CONSTITUIR FRAUDE<p/>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:9.85pt;margin-left:70pt;text-align:left;line-height:107%'><b><i><span
	style='font-size:10.0pt;line-height:107%;font-family:"Times New Roman",serif'>4.2.1.
	Al momento de la suscripción o endoso:</span></i></b><span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:9.85pt;margin-left:70pt;text-align:left;line-height:107%'><b><i><span
	style='font-size:10.0pt;line-height:107%;font-family:"Times New Roman",serif'>4.2.2.
	Al momento de la denuncia del siniestro:</span></i></b><span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:5.45pt;margin-left:70pt;text-align:left;line-height:107%'><b><i><span
	style='font-size:10.0pt;line-height:107%;font-family:"Times New Roman",serif'>4.2.3.
	Al momento de la liquidación del siniestro:</span></i></b><span style='font-family:"Calibri",sans-serif'> </span></p>

	<h2>
	5.<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span></i>
	</i>RESPONSABILIDADES<i></i><i>
	<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span></h2>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>5.1.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>RESPONSABLE DE CONTACTO</span></i><span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>5.2.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>COMITÉ DE PREVENCIÓN DE FRAUDE</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>5.3.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>GERENCIA GENERAL</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>5.4.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>GERENCIA DE AUDITORÍA INTERNA</span></i><span
	style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>5.5.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>GERENCIA DE SISTEMAS</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>5.6.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>EMPLEADOS</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<h2>6.<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span>RECURSOS HUMANOS 
	<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span></h2>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm; margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i>
	<span
	style='font-size:10.0pt;line-height:107%'>6.1.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>SELECCIÓN</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>6.2.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>CAPACITACIÓN</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>6.3.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>COMUNICACIÓN</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>6.4.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>RÉGIMEN SANCIONATORIO</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<h2>
	7.<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span>INTERMEDIADORES Y AGENTES INSTITORIOS<p/>
	8.<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span>MEDIDAS DE PREVENCIÓN, DISUASIÓN, DETECCIÓN Y DENUNCIA DE FRAUDE
	<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span></h2>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>8.1.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>AL MOMENTO DE LA SUSCRIPCIÓN O ENDOSO</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>8.2.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>AL MOMENTO DE LA DENUNCIA DEL SINIESTRO</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.05pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>8.3.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>AL MOMENTO DE LA LIQUIDACIÓN DEL SINIESTRO</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:9.85pt;margin-left:70pt;text-align:left;text-indent:0cm;
	line-height:107%'><span style='font-family:"Calibri",sans-serif'></span><b><i><span style='font-size:10.0pt;line-height:107%;font-family:"Times New Roman",serif'>8.3.1.</span></i></b><span
	style='font-family:"Calibri",sans-serif'></span><b><i><span style='font-size:10.0pt;line-height:107%;font-family:"Times New Roman",serif'>Prestaciones Dinerarias</span></i></b><span
	style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm; margin-bottom:5.45pt;margin-left:70pt;text-align:left;text-indent:0cm; line-height:107%'><span style='font-family:"Calibri",sans-serif'></span>
	<b><i><span style='font-size:10.0pt;line-height:107%;font-family:"Times New Roman",serif'>8.3.2.</span></i></b><span style='font-family:"Calibri",sans-serif'></span><b><i><span style='font-size:10.0pt;line-height:107%;font-family:"Times New Roman",serif'>Prestaciones en Especie</span></i>
	</b><span style='font-family:"Calibri",sans-serif'> </span></p>

	<h2>
	9.<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span>PROCEDIMIENTOS DE CONTROL (programa de verificación de cumplimiento)
	<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span></h2>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:2.1pt;
	margin-bottom:7.0pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>9.1.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>INFORME ANUAL</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:2.1pt;
	margin-bottom:7.0pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>9.2.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>ANTECEDENTES Y ELEMENTOS DE RESPALDO DEL INFORME</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:2.1pt;margin-bottom:7.0pt;margin-left:60pt;text-align:left;line-height:107%'><i><span
	style='font-size:10.0pt;line-height:107%'>9.3.</span></i><span
	style='font-family:"Calibri",sans-serif'> </span><i><span style='font-size:
	10.0pt;line-height:107%'>CUSTODIA Y RESGUARDO</span></i>
	<span style='font-family:"Calibri",sans-serif'> </span></p>

	<h2>
	10.<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span>LISTADOS DE CHEQUEO DE ALERTAS DE DETECCIÓN TEMPRANA<p/>
	
	11.
	<span style='font-family:"Calibri",sans-serif;font-weight:normal'> </span>RECEPCIÓN Y TRATAMIENTO DE DENUNCIAS DE SOSPECHA DE FRAUDE O FRAUDE</div>
	</h2>


	<p class=MsoNormal align=left style='margin:0cm;margin-bottom:.0001pt;
	text-align:left;text-indent:0cm;line-height:107%'><b>          </b><br
	clear=all style='page-break-before:always'>
	</p>
	</div>
	<h2 class="auto-style1">1.<span style='font-family:"Arial",sans-serif'> </span>OBJETO  </h2>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.45pt;margin-bottom:
	5.65pt;margin-left:-.25pt'>El objeto del Manual para combatir el fraude es
	identificar y formalizar el proceso de prevención, detección y respuesta al
	fraude en Provincia ART S.A., en el marco de lo dispuesto en la Resolución SSN
	38.477. El seguimiento del Manual promueve el accionar de la compañía
	estableciendo el tratamiento de las acciones anti-fraude a través de pautas y
	asignación de responsabilidades para el desarrollo de los controles y la
	atención y solución de eventos o casos presentados.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:5.6pt;
	margin-left:-.25pt'>Con la implementación de las pautas determinadas en este
	manual se afianzará la cultura de integridad propiciada en el Código de valores
	y conducta.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.65pt;margin-bottom:
	7.2pt;margin-left:-.25pt'>La recopilación y el reporte preciso y objetivo de la
	información, se considera esencial para consolidar la credibilidad y reputación
	de Provincia ART S.A. y cumplir con las responsabilidades ante los grupos de
	interés.  </p>

	<h2 class="auto-style1">2.<span style='font-family:"Arial",sans-serif'> </span>DEFINICIONES </h2>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.45pt;margin-bottom:
	5.55pt;margin-left:-.25pt'><b>Agente Institorio:</b> Agente de seguros que se
	desempeña en representación de la compañía de acuerdo a los términos del
	mandato que se le haya conferido, en el marco de lo establecido en la
	Resolución SSN 38.052/2013.  </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:5.95pt;margin-left:0cm;text-align:left;text-indent:0cm;
	line-height:99%'><b>Canales receptores de denuncias:</b> Medios habilitados por
	Provincia ART S.A. para recibir denuncias.  Se han habilitado los siguientes
	canales: una línea gratuita a nivel nacional, correo electrónico: <u><span
	style='color:blue'><a href="mailto:prevenciondefraude@provart.com.ar">prevenciondefraude@provart.com.ar</a></span></u> y página web de
	Provincia ART. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.7pt;margin-bottom:
	5.55pt;margin-left:-.25pt'><b>Código de Valores y Conducta:</b> Establece los
	valores y pautas generales que regulan la conducta del personal de Provincia
	ART en cumplimiento de sus funciones y en sus relaciones comerciales y
	profesionales actuando de acuerdo a la legislación vigente. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.55pt;margin-bottom:
	5.65pt;margin-left:-.25pt'><b>Corrupción: </b>Aceptación de un soborno a través
	del pago en dinero o la entrega de cualquier objeto de valor, como productos o
	servicios en especie, una oferta, un plan o una promesa de pagar o dar algo de
	valor (incluso en el futuro) a cambio de un beneficio personal, un tercero o
	para la empresa, con el fin de obtener una ventaja ilegítima. Estos actos de
	corrupción pueden llevarse a cabo entre otros a través de pagos de viajes,
	entretenimiento, condonación de deuda, favores entre otros.    </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.7pt;margin-bottom:
	5.6pt;margin-left:-.25pt'><b>Fraude:</b> Cualquier acto u omisión que, mediante
	el engaño, ocultamiento o cualquier otro tipo de ardid, sean generados por
	sujetos pertenecientes o no a la organización, y que le provoquen daños
	patrimoniales a ésta o un lucro al perpetrador. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:5.7pt;
	margin-left:-.25pt'><b>Intermediador: </b>Es el Productor Asesor de Seguros y
	las sociedades de productores previstas en la Ley 22.400. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.55pt;margin-bottom:
	4.4pt;margin-left:-.25pt'><b>Malversación de activos: </b>Acto intencional o
	negligente de disponer de los activos de la entidad o aquellos de los que ésta
	sea responsable, en beneficio propio o de terceros. Dicha malversación
	comprende, pero no se limita a: apropiación física de bienes sin la respectiva
	autorización, apropiación de dinero, títulos representativos de valor o
	similares (así sea de manera temporal), realización de gastos no autorizados en
	beneficio propio o de terceros; en general, toda apropiación, desviación o uso
	de los bienes de propiedad o bajo responsabilidad de la entidad para ser
	destinados a fines diferentes de aquellos para los cuales hayan sido
	específicamente fabricados, adquiridos o recibidos. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.7pt;margin-bottom:
	5.7pt;margin-left:-.25pt'><b>Malversación de pasivos: </b>Se define como el
	acto intencional o negligente de ocultar o alterar los pasivos de la entidad,<span
	style='font-size:12.0pt;line-height:103%'> </span>presentar información
	financiera falsa mostrando utilidades ficticias, en beneficio propio o de
	terceros. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:5.35pt;
	margin-left:-.25pt'><b>Omisión: </b>Incumplimiento de una obligación de hacer o
	dar. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:5.35pt;
	margin-left:-.25pt'><b>Perpetrador: </b>Individuo que comete algún acto de
	fraude.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.55pt;margin-bottom:
	5.7pt;margin-left:-.25pt'><b>Resolución SSN 38.477: </b>Acto de alcance general
	normativo emitido por la Superintendencia de Seguros de la Nación mediante la
	que exige a las entidades supervisadas la adopción de políticas y normas
	orientadas a combatir el fraude en los seguros. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.45pt;margin-bottom:
	5.7pt;margin-left:-.25pt'><b>Riesgo: </b>Es un evento incierto que de llegar a
	ocurrir generaría un impacto, positivo o negativo, en el logro o cumplimiento
	de objetivos.  Se mide en términos de probabilidad de ocurrencia del evento por
	el impacto/severidad de la consecuencia.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.45pt;margin-bottom:
	5.65pt;margin-left:-.25pt'><b>Soborno: </b>Ofrecimiento o propuesta de un pago
	en dinero o la entrega de cualquier objeto de valor, como productos o servicios
	en especie, una oferta, un plan o una promesa de pagar o dar algo de valor
	(incluso en el futuro) a cambio de un beneficio personal, un tercero o para la
	empresa.   </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:5.35pt;
	margin-left:-.25pt'><b>SRT: </b>Superintendencia de Riesgos del Trabajo. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:18.85pt;
	margin-left:-.25pt'><b>SSN: </b>Superintendencia de Seguros de la Nación. </p>

	<h2 class="auto-style1">3.<span style='font-family:"Arial",sans-serif'> </span>POLÍTICAS Y
	OBJETIVOS  </h2>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.65pt;margin-bottom:
	5.55pt;margin-left:-.25pt'>Toda la información creada por los empleados de
	Provincia ART S.A., debe ser un reflejo de las transacciones realizadas y sus
	circunstancias. Si la información no se registra con precisión e integridad, no
	sólo se incumplen los principios de Provincia ART S.A., sino también se puede
	llegar a infringir el ordenamiento jurídico.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.7pt;margin-bottom:
	5.55pt;margin-left:-.25pt'>La falsificación de información o la tergiversación
	de los hechos se podrían considerar fraude y puede resultar en responsabilidad
	civil y penal tanto para los trabajadores de Provincia ART S.A. como para la
	empresa. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:5.45pt;
	margin-left:-.25pt'>La normativa contenida en este Manual es aplicable a toda
	la compañía. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:11.6pt;
	margin-left:-.25pt'>Los objetivos de PROVINCIA ART S.A. en materia de
	Prevención de actividades de fraude, dentro del marco de lo dispuesto en la
	Resolución SSN Nº 38.477, son:  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.6pt;margin-bottom:
	11.55pt;margin-left:63.9pt;text-indent:-14.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
	</span>Lograr el compromiso de la totalidad de los empleados de la empresa, en
	la prevención del fraude. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.6pt;margin-bottom:
	11.65pt;margin-left:63.9pt;text-indent:-14.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
	</span>Lograr la concientización de todo el personal acerca de la importancia
	en la aplicación de los procedimientos, controles y monitoreo tendientes a
	prevenir el fraude. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.6pt;margin-bottom:
	11.7pt;margin-left:63.9pt;text-indent:-14.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
	</span>Lograr la internalización del deber de detectar y reportar operaciones
	sospechosas, conforme el procedimiento adoptado por la empresa, y el deber de
	mantener la confidencialidad que debe primar en el análisis, reporte y
	posterior seguimiento de las operaciones. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.6pt;margin-bottom:
	11.7pt;margin-left:63.9pt;text-indent:-14.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
	</span>Capacitar en materia de prevención del fraude a todo el personal, sin
	distinción de escalas jerárquicas. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.6pt;margin-bottom:
	11.7pt;margin-left:63.9pt;text-indent:-14.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
	</span>Proteger el prestigio empresarial y de sus empleados frente a posibles
	sanciones administrativas, penales o pecuniarias, minimizando el riesgo
	asociado al fraude. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.6pt;margin-bottom:
	19.1pt;margin-left:63.9pt;text-indent:-14.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
	</span>Garantizar la confidencialidad respecto de clientes y terceros, que debe
	primar en el análisis y posterior resolución de aquellas operaciones que
	pudieran ser consideradas sospechosas o se haya comprobado la realización del
	fraude. </p>

	<h2 class="auto-style1">4.<span style='font-family:"Arial",sans-serif'> </span>CLASIFICACIÓN Y
	ACCIONES QUE PUEDEN CONSTITUIR FRAUDE  </h2>

	<h3 class="auto-style1">4.1.<span style='font-family:"Arial",sans-serif'> </span>CLASIFICACIÓN
	</h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.05pt;
	margin-left:-.25pt'>Para facilitar el entendimiento del fraude objeto de este
	manual, como género dentro del marco de la Resolución SSN 38.477/2014, se
	clasifica en tres especies: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:2.9pt;
	margin-left:90.0pt;text-indent:-18.0pt'>1.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Reportes fraudulentos. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:2.95pt;
	margin-left:90.0pt;text-indent:-18.0pt'>2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Corrupción </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.35pt;
	margin-left:90.0pt;text-indent:-18.0pt'>3.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Engaño y/o generación o agravamiento doloso de siniestros o de
	información. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:.55pt;
	margin-left:-.25pt'>En términos generales, el fraude se puede categorizar en
	los siguientes tipos:  </p>
		<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:.55pt;
	margin-left:-.25pt'>&nbsp;</p>

	<table class=TableGrid border=0 cellspacing=0 cellpadding=0 width=695
	 style='width:521.15pt;margin-left:-5.4pt;border-collapse:collapse' align="center">
	 <tr style='height:31.9pt'>
	  <td width=185 style='width:138.6pt;border:solid black 1.0pt;padding:0cm 1.5pt 0cm 5.4pt;
	  height:31.9pt'>
	  <p class=MsoNormal align=left style='margin:0cm;margin-bottom:.0001pt;
	  text-align:left;text-indent:0cm;line-height:107%'><b>Tipo de Fraude</b> </p>
	  </td>
	  <td width=510 style='width:382.5pt;border:solid black 1.0pt;border-left:none;
	  padding:0cm 1.5pt 0cm 5.4pt;height:31.9pt'>
	  <p class=MsoNormal align=center style='margin-top:0cm;margin-right:3.95pt;
	  margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;
	  text-indent:0cm;line-height:107%'><b>Breve descripción</b> </p>
	  </td>
	 </tr>
	 <tr style='height:66.0pt'>
	  <td width=185 style='width:138.6pt;border:solid black 1.0pt;border-top:none;
	  padding:0cm 1.5pt 0cm 5.4pt;height:66.0pt'>
	  <p class=MsoNormal align=left style='margin:0cm;margin-bottom:.0001pt;
	  text-align:left;text-indent:0cm;line-height:107%'>a) Reconocimiento de
	  ingresos </p>
	  </td>
	  <td width=510 style='width:382.5pt;border-top:none;border-left:none;
	  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 1.5pt 0cm 5.4pt;
	  height:66.0pt'>
	  <p class=MsoNormal style='margin-top:0cm;margin-right:3.9pt;margin-bottom:
	  0cm;margin-left:0cm;margin-bottom:.0001pt;text-indent:0cm;line-height:107%'>Consiste
	  en la alteración o manipulación indebida de contratos de afiliación y endosos
	  creando la apariencia de que los ingresos de la Empresa tuvieron un desempeño
	  que no corresponde a la realidad. </p>
	  </td>
	 </tr>
	 <tr style='height:79.35pt'>
	  <td width=185 style='width:138.6pt;border:solid black 1.0pt;border-top:none;
	  padding:0cm 1.5pt 0cm 5.4pt;height:79.35pt'>
	  <p class=MsoNormal align=left style='margin:0cm;margin-bottom:.0001pt;
	  text-align:left;text-indent:0cm;line-height:107%'>b) Revelaciones parciales o
	  no ajustadas a la realidad  </p>
	  </td>
	  <td width=510 style='width:382.5pt;border-top:none;border-left:none;
	  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 1.5pt 0cm 5.4pt;
	  height:79.35pt'>
	  <p class=MsoNormal style='margin-top:0cm;margin-right:3.9pt;margin-bottom:
	  0cm;margin-left:0cm;margin-bottom:.0001pt;text-indent:0cm;line-height:107%'>Consiste
	  en revelar al mercado información errónea o incompleta en relación con sus
	  hechos económicos (P.ej. fusiones y adquisiciones, proyecciones de ventas,
	  reservas, contingencias, entre otros) con el fin de presentar una situación
	  económica de la Empresa que no corresponde a la realidad.  </p>
	  </td>
	 </tr>
	 <tr style='height:39.25pt'>
	  <td width=185 style='width:138.6pt;border:solid black 1.0pt;border-top:none;
	  padding:0cm 1.5pt 0cm 5.4pt;height:39.25pt'>
	  <p class=MsoNormal align=left style='margin:0cm;margin-bottom:.0001pt;
	  text-align:left;text-indent:0cm;line-height:107%'>c) Manipulación de pagos  </p>
	  </td>
	  <td width=510 style='width:382.5pt;border-top:none;border-left:none;
	  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 1.5pt 0cm 5.4pt;
	  height:39.25pt'>
	  <p class=MsoNormal align=left style='margin:0cm;margin-bottom:.0001pt;
	  text-align:left;text-indent:0cm;line-height:107%'>Consiste en la alteración o
	  manipulación indebida de los pagos de siniestros  </p>
	  </td>
	 </tr>
	 <tr style='height:67.3pt'>
	  <td width=185 style='width:138.6pt;border:solid black 1.0pt;border-top:none;
	  padding:0cm 1.5pt 0cm 5.4pt;height:67.3pt'>
	  <p class=MsoNormal align=left style='margin:0cm;margin-bottom:.0001pt;
	  text-align:left;text-indent:0cm;line-height:107%'>d) Adulteración de
	  registros </p>
	  </td>
	  <td width=510 valign=top style='width:382.5pt;border-top:none;border-left:
	  none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;
	  padding:0cm 1.5pt 0cm 5.4pt;height:67.3pt'>
	  <p class=MsoNormal style='margin-top:0cm;margin-right:3.8pt;margin-bottom:
	  0cm;margin-left:0cm;margin-bottom:.0001pt;text-indent:0cm;line-height:107%'>Es
	  el riesgo derivado de que se realicen ajustes de forma indebida en los
	  registros de la compañía (tales como la destrucción, mutilación,
	  ocultamiento, falsificación de los registros) con el fin de esconder entre
	  otros sobornos, faltantes o realizar fraudes para el beneficio personal o de
	  terceros.  </p>
	  </td>
	 </tr>
	 <tr style='height:79.45pt'>
	  <td width=185 style='width:138.6pt;border:solid black 1.0pt;border-top:none;
	  padding:0cm 1.5pt 0cm 5.4pt;height:79.45pt'>
	  <p class=MsoNormal align=left style='margin:0cm;margin-bottom:.0001pt;
	  text-align:left;text-indent:0cm;line-height:107%'>e) Falsificación de hechos
	  o datos. </p>
	  </td>
	  <td width=510 style='width:382.5pt;border-top:none;border-left:none;
	  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 1.5pt 0cm 5.4pt;
	  height:79.45pt'>
	  <p class=MsoNormal style='margin-top:0cm;margin-right:3.8pt;margin-bottom:
	  0cm;margin-left:0cm;margin-bottom:.0001pt;text-indent:0cm;line-height:107%'>Es
	  el riesgo derivado de aceptar siniestros que fueran dolosamente causados para
	  obtener un beneficio, reagravaciones dolosas de siniestros ocurridos,
	  falsificación de documentos para lograr la cobertura de siniestros ocurridos
	  fuera del período de cobertura, falsificaciones de datos para la cotización,
	  entre otros. </p>
	  </td>
	 </tr>
	</table>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:6.35pt;margin-left:0cm;text-align:left;text-indent:0cm;
	line-height:107%'> </p>

	<h3 class="auto-style1">4.2.<span style='font-family:"Arial",sans-serif'> </span>ACCIONES QUE
	PUEDEN CONSTITUIR FRAUDE </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:5.55pt;
	margin-left:-.25pt'>A continuación se detallan algunas de las acciones en
	general, entre otras, que pueden constituir fraude o resultar reveladoras de su
	existencia, en Provincia ART S.A.:  </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:6.35pt;margin-left:0cm;text-align:left;text-indent:0cm;
	line-height:107%'> </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.05pt;
	margin-left:36.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Cualquier acto deshonesto que
	impida reflejar la realidad de la compañía en la información emitida
	(financiera y no financiera).  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.0pt;
	margin-left:18.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>La apropiación indebida de
	fondos, valores, materiales, u otros activos. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:6.9pt;
	margin-left:18.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Irregularidades en el manejo de
	información o transacciones.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.2pt;
	margin-left:36.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Especulación, como resultado del
	conocimiento de información privilegiada de las actividades de la empresa  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.2pt;
	margin-left:36.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>La revelación de información
	confidencial de Provincia ART S.A. y/o de propiedad de terceros.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.7pt;margin-bottom:
	7.2pt;margin-left:36.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Aceptar o solicitar cualquier
	elemento de valor material de los afiliados, intermediarios, contratistas,
	proveedores o prestadores de servicios o materiales a la Compañía, que no se
	corresponda con el Código de valores y conducta. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:6.9pt;
	margin-left:18.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Destrucción, remoción, o uso
	inadecuado de información.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.7pt;margin-bottom:
	7.2pt;margin-left:36.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Realizar ajustes de forma
	indebida en los libros contables (tales como la destrucción, mutilación,
	ocultamiento, falsificación de los registros contables) con el fin de esconder
	entre otros sobornos, faltantes o realizar fraudes para el beneficio personal o
	de terceros.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:6.9pt;
	margin-left:18.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Pagos de siniestros que no están
	soportados con documentos formales  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.05pt;
	margin-left:36.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Destrucción, adulteración y
	falsificación de documentos que respalden los pagos de siniestros. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:18.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Pagos no autorizados de siniestros.
	</p>

	<p class="auto-style2 MsoNormal"><b>4.2.1.
	Al momento de la suscripción o endoso: </b></p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.05pt;
	margin-left:78.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Cualquier alteración u
	ocultamiento de la información brindada al momento de cotizar el riesgo. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:11.7pt;
	margin-left:78.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Alteración, adulteración y/o
	falsificación de los documentos y firmas de la solicitud de afiliación y
	endosos. </p>

	<p class=MsoNormal></p>
		<div class="auto-style2 MsoNormal">
			<b>4.2.2.
	Al momento de la denuncia del siniestro: </b></div>
		</p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:78.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Adulteración de la relación
	laboral del accidentado -Empleado no declarado, empleado no registrado. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:6.85pt;
	margin-left:60.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Denuncia de un incidente no
	fortuito y/o intencional. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.0pt;
	margin-left:60.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Denuncia una enfermedad no
	profesional. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.05pt;
	margin-left:78.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Falsificación de la
	circunstancia en la que ocurrió el accidente u originó la enfermedad.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.0pt;
	margin-left:60.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Enfermedades profesionales
	inexistentes. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:6.9pt;
	margin-left:60.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Estudios periódicos fraudulentos
	</p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.0pt;
	margin-left:60.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Cúmulo de siniestros denunciados
	por el mismo trabajador </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.1pt;
	margin-left:78.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Siniestro generado a partir de
	la demanda judicial sin denuncia previa del evento. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.05pt;
	margin-left:78.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Siniestros generados sin datos
	concretos (datos vagos o ambiguos); inconsistencia en el relato de los hechos;
	temporalidad sospechosa. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.0pt;
	margin-left:60.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Siniestro desconocido por el
	empleador </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:11.35pt;
	margin-left:60.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Ingreso masivo de siniestros o
	demandas de un mismo empleador </p>

	<p class="auto-style2 MsoNormal" >4.2.3.
	Al momento de la liquidación del siniestro: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:6.9pt;
	margin-left:60.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Pagos o reclamos de rentas
	vitalicias a personas fallecidas. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.0pt;
	margin-left:60.5pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Accidentados con DNI falso
	(evidenciando una edad menor a la real). </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.2pt;
	margin-left:78.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Empresa que declare sueldos que
	no son correctos (sueldo más alto que el real). </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.45pt;margin-bottom:
	5.55pt;margin-left:78.0pt;text-indent:-18.0pt'><span style='font-family:Webdings'>a</span><span
	style='font-family:"Arial",sans-serif'> </span>Empresa y damnificado denuncian
	un accidente de trabajo cuando corresponde un accidente in itinere (obteniendo
	el 20% adicional por compensación por daño). </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:12.6pt;margin-bottom:
	13.0pt;margin-left:-.25pt'>Todos los funcionarios y empleados de PROVINCIA ART
	S.A. tienen la obligación de cumplir con las obligaciones establecidas por la
	normativa vigente, el Código de Valores y Conducta y las establecidas en el
	presente manual, ello bajo apercibimiento de las sanciones que conforme a
	derecho correspondieren. </p>

	</div>

	<span style='font-size:11.0pt;line-height:103%;font-family:"Verdana",sans-serif;
	color:black'><br clear=all style='page-break-before:always'>
	</span>

	<div class=WordSection2>

	<h2 class="auto-style1">5.<span style='font-family:"Arial",sans-serif'> </span>RESPONSABILIDADES
	</h2>

	<h3 class="auto-style1">5.1.<span style='font-family:"Arial",sans-serif'> </span>RESPONSABLE
	DE CONTACTO  </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.9pt;
	margin-left:-.25pt'>El Responsable de Contacto, será un miembro del Directorio
	de la Compañía designado en reunión de Directorio. Será el responsable directo
	de velar por el cumplimiento del presente manual y tendrá las siguientes
	funciones:   </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Propender a la divulgación y la implementación del Manual para combatir
	el  fraude en toda la Empresa   </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.9pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Facilitar la preparación de planes de mitigación para los riesgos de
	fraude identificados. Realizar seguimiento a su implementación y efectividad. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Facilitar sesiones de sensibilización y capacitación relacionadas con
	temas de fraude tanto para empleados como terceros que así lo requieran. <span
	style='font-size:12.0pt;line-height:103%'> </span></p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.55pt;
	margin-left:47.4pt;text-indent:-8.5pt;line-height:99%'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Asegurar la suscripción de los pactos de transparencia y/o declaraciones
	en temas de fraude, corrupción y conflictos de interés; y analizar y evaluar la
	información declarada en estos pactos que evidencien situaciones de corrupción.
	<span style='font-size:12.0pt;line-height:99%'> </span></p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Propender por la identificación de riesgos y controles de fraude, y la
	actualización periódica de su evaluación. <span style='font-size:12.0pt;
	line-height:103%'> </span></p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Reportar al Directorio las denuncias policiales y/o judiciales
	relacionadas con fraude en la empresa conforme al proceso establecido.<span
	style='font-size:12.0pt;line-height:103%'> </span></p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.55pt;
	margin-left:47.4pt;text-indent:-8.5pt;line-height:99%'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Velar por la capacitación y actualización de los miembros de la compañía
	con el fin de asegurar las habilidades y competencias relacionadas a la
	prevención, detección y respuesta al riesgo de fraude. <span style='font-size:
	12.0pt;line-height:99%'> </span></p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Las demás señaladas en los manuales, procedimientos o políticas internas
	de la empresa.   </p>

	<h3 class="auto-style1">5.2.<span style='font-family:"Arial",sans-serif'> </span>COMITÉ DE
	PREVENCIÓN DE FRAUDE </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Es el órgano de asesoramiento del Responsable de Contacto,
	en materia de Prevención de Fraude. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.2pt;
	margin-left:-.25pt'>Está conformado por el Director Responsable de Contacto, el
	Gerente General, el Subgerente General de Planificación y Coordinación, el
	Auditor Interno, el Gerente de Legales, y el Gerente Técnico Actuarial.
	Cualquier cambio en su composición deberá ser decidido por el Directorio de la
	compañía. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:-.25pt'>Será responsable de velar por el cumplimiento del presente
	manual y tendrá las siguientes funciones: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Proponer y asesorar al Responsable de Contacto acerca de la
	implementación y actualización de políticas y procedimientos de prevención de
	fraude. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Recibir, registrar y derivar los antecedentes de operaciones sospechosas
	de Fraude al Responsable de Contacto. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Dar respuesta a los requerimientos del Responsable de Contacto
	vinculadas con la prevención del Fraude. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Implementar bajo la dirección del Responsable de Contacto, las
	capacitaciones en la materia a todo el personal de la compañía. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:11.55pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Revisar anualmente el presente Manual. Proponer eventuales
	modificaciones y presentarlas ante el Directorio para su evaluación y
	aprobación. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.85pt;
	margin-left:-.25pt'>La periodicidad de las reuniones será establecida por el
	Responsable de Contacto, quien además podrá convocar a gerentes de otras áreas
	cuando ello resultare conducente a los fines de dar cumplimiento a la normativa
	de Prevención de Fraude. Las reuniones de Comité serán asentadas en actas o
	minutas, conforme lo determine el Responsable de Contacto. </p>

	<h3 class="auto-style1">5.3.<span style='font-family:"Arial",sans-serif'> </span>GERENCIA
	GENERAL </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:-.25pt'>Demostrar compromiso con el Manual y una cultura ética,
	antifraude y de cumplimiento en la empresa.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Crear y fomentar una cultura de no tolerancia al fraude, incluida la
	corrupción.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Asegurar la adecuada implementación de controles que mitiguen los
	riesgos de fraude. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Brindar apoyo y dirección respecto de la implementación del Manual para
	combatir el fraude.  </p>

	<h3 class="auto-style1">5.4.<span style='font-family:"Arial",sans-serif'> </span>GERENCIA DE
	AUDITORÍA INTERNA   </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Desarrollar un plan de auditoría que considere evaluar el cumplimiento y
	el monitoreo de las obligaciones del Manual para combatir el Fraude. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Proveer seguridad razonable sobre la suficiencia de los controles para
	mitigar los riesgos de fraude y el funcionamiento efectivo de dichos controles.
	</p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Informar al Responsable de Contacto de las deficiencias identificadas en
	sus auditorías que evidencien posibles actos de fraude en la empresa.  </p>

	<h3 class="auto-style1">5.5.<span style='font-family:"Arial",sans-serif'> </span>GERENCIA DE SISTEMAS
	</h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:35.9pt'>El Gerente de Sistemas debe: </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:13.55pt;margin-left:46.65pt;text-align:left;text-indent:-8.5pt;
	line-height:99%'>•<span style='font:7.0pt "Times New Roman"'> </span>Velar por
	el diseño, implementación y funcionamiento de los sistemas y procedimientos
	informáticos necesarios para prevenir, detectar y reportar las operaciones que
	puedan estar vinculadas a la Prevención de Fraude.  </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:13.55pt;margin-left:46.65pt;text-align:left;text-indent:-8.5pt;
	line-height:99%'>•<span style='font:7.0pt "Times New Roman"'> </span>Asegurar
	la adecuada conservación y custodia de la información electrónica y la
	documentación digitalizada, concerniente a los procesos de Prevención de Fraude
	relativos a las Operaciones que hubieran sido detectadas como sospechosas o
	comprobadas como tales.  </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:13.55pt;margin-left:46.65pt;text-align:left;text-indent:-8.5pt;
	line-height:99%'>•<span style='font:7.0pt "Times New Roman"'> </span>Implementar
	y ejecutar los mecanismos informáticos que permitan cruzar las bases de datos
	de la empresa con aquellas bases que exigiere la normativa en materia de
	Prevención de Fraude. </p>

	<h3 class="auto-style1">5.6.<span style='font-family:"Arial",sans-serif'> </span>EMPLEADOS    </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Conocer, comprender y aplicar el manual antifraude.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Ejecutar los controles antifraude a su cargo y dejar evidencia de su
	cumplimiento.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Reportar acciones sospechosas o incidentes relacionados con fraude.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:19.1pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Cooperar en las investigaciones de denuncias relacionadas con fraude,
	que adelanten las autoridades competentes.  </p>

	<h2 class="auto-style1">6.<span style='font-family:"Arial",sans-serif'> </span> RECURSOS
	HUMANOS </h2>

	<h3 class="auto-style1">6.1.<span style='font-family:"Arial",sans-serif'> </span>SELECCIÓN </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Al momento de seleccionar aspirantes para el ingreso a la
	compañía, la Gerencia de Recursos Humanos deberá realizar todas aquellas
	pruebas y/o averiguaciones para garantizar la idoneidad y probidad del
	ingresante.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Al momento del ingreso, RRHH informará al nuevo empleado
	acerca de la política vigente en la compañía en materia de Prevención de
	Fradude dentro del marco de la Resolución SSN 38.477. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:-.25pt'>En lo establecido en el <i>Procedimiento RH-07- Selección,
	Incorporación, Transferencia y Promoción de Personal,</i> para los procesos de
	promoción, búsquedas internas y corporativas, el cumplimiento de la asistencia
	a la capacitación en Prevención de Fraude es un requisito para postularse en
	los procesos de promoción, búsquedas internas y corporativas. <b> </b></p>

	<h3 class="auto-style1">6.2.<span style='font-family:"Arial",sans-serif'> </span>CAPACITACIÓN </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>La Gerencia de RRHH propondrá un programa anual de
	capacitación antifraude con las actividades a realizar y la cantidad estimada
	de empleados a alcanzar por cada una de las etapas, que deberá ser aprobado por
	la Gerencia General y el Responsable de Contacto. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:9.05pt;
	margin-left:-.25pt'>El Programa de Capacitación deberá contemplar: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;
	margin-left:36.0pt;margin-bottom:.0001pt;text-indent:-18.0pt'>a)<span
	style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp; </span>La difusión de la
	normativa vigente en la materia, de las normas internas en materia de Prevención
	del Fraude (Resolución SSN 38.477), así como de la información sobre técnicas y
	métodos para prevenir, detectar y reportar operaciones de fraude. </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:.35pt;margin-left:36.0pt;text-align:left;text-indent:0cm;
	line-height:107%'> </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:.55pt;
	margin-left:36.0pt;text-indent:-18.0pt'>b)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>La metodología que se utilizará para llevar adelante el programa. </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:6.85pt;margin-left:36.0pt;text-align:left;text-indent:0cm;
	line-height:107%'> </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Para lograr estos objetivos, se realizarán actividades de
	capacitación y en las jornadas de integración que se organicen, se incorporará
	la temática.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:5.55pt;
	margin-left:-.25pt'>Será responsabilidad de la Gerencia de RRHH la logística y
	la convocatoria a las distintas actividades, así como el registro de quienes
	atendieron a las capacitaciones. En caso de inasistencia, se reasignará al
	colaborador dentro del periodo anual en curso para una segunda instancia de
	capacitación. Si tras el segundo intento se recae nuevamente en un incumplimiento,
	Recursos Humanos registrará este hecho y lo informará a la Gerencia General a
	sus efectos. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>Recursos Humanos pondrá a disposición del Responsable de
	Contacto los informes sobre el estado de capacitación del personal.  </p>

	<h3 class="auto-style1">6.3.<span style='font-family:"Arial",sans-serif'> </span>COMUNICACIÓN </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>La Gerencia de RRHH será responsable del armado de los
	contenidos y de las piezas de comunicación para mantener actualizado al
	personal en todo lo referido a la prevención de fraude.  </p>

	<h3 class="auto-style1">6.4.<span style='font-family:"Arial",sans-serif'> </span>RÉGIMEN
	SANCIONATORIO </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.35pt;
	margin-left:-.25pt'>El incumplimiento de las disposiciones establecidas en este
	Manual por parte del personal y/o directivos, los hará pasibles de la
	aplicación de las sanciones a que hubiere lugar y que en derecho correspondan. </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:9.2pt;margin-left:0cm;text-align:left;text-indent:0cm;line-height:
	99%'>Las faltas podrán determinar la aplicación de una sanción, que se graduará
	atendiendo a la conducta del autor y a la gravedad de la infracción, siguiendo
	los criterios que se detallan a continuación: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:63.85pt;text-indent:-21.25pt'>I.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Las faltas cometidas con dolo serán consideradas en todos los casos
	faltas graves y serán sancionadas con suspensión o, según la gravedad del caso,
	hasta con el despido con causa del empleado o directivo.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:6.85pt;
	margin-left:63.85pt;text-indent:-21.25pt'>II.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;
	</span>Ante faltas cometidas a título culposo, se aplicarán las siguientes
	sanciones: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:6.95pt;
	margin-left:4.0cm;text-indent:-18.0pt'>1-<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Observación  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:6.85pt;
	margin-left:4.0cm;text-indent:-18.0pt'>2-<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Apercibimiento </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.0pt;
	margin-left:4.0cm;text-indent:-18.0pt'>3-<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Suspensión </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:5.35pt;
	margin-left:4.0cm;text-indent:-18.0pt'>4-<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Despido, en caso de reiteración de faltas o ante faltas graves. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>El Comité de Prevención de Fraude será el órgano encargado
	de decidir y comunicar a la Gerencia de Recursos Humanos las sanciones a
	aplicar en cada caso.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:20.9pt;
	margin-left:-.25pt'>Cuando exista la presunción de la comisión de un delito, se
	realizará la pertinente denuncia en la justicia. </p>

	<h2 class="auto-style1">7.<span style='font-family:"Arial",sans-serif'> </span>INTERMEDIADORES
	Y AGENTES INSTITORIOS </h2>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Los intermediadores o agentes institorios deberán
	notificarse de las recomendaciones preguntas o datos que solicite la compañía
	en materia de Prevención de Fraude. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:8.9pt;
	margin-left:-.25pt'>Las recomendaciones incluirán como mínimo referencia a los
	siguientes momentos: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:.35pt;
	margin-left:144.0pt;text-indent:-18.0pt;line-height:107%'>1.<span
	style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp; </span>Cuando se solicita o
	recibe una Cotización, Solicitud de Afiliación </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:1.0pt;
	margin-left:144.0pt;text-indent:-18.0pt'>2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Cuando se tramita algún endoso o modificación del contrato. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:18.9pt;
	margin-left:144.0pt;text-indent:-18.0pt'>3.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Al recibir una denuncia de un siniestro </p>

	<h3 class=auto-style1><b>8.<span style='font-family:"Arial",sans-serif'> </span>MEDIDAS
	DE PREVENCIÓN, DISUASIÓN, DETECCIÓN Y DENUNCIA DE FRAUDE. </b></h3>

	<h3 class="auto-style1">8.1.<span style='font-family:"Arial",sans-serif'> </span>AL MOMENTO DE
	LA SUSCRIPCIÓN O ENDOSO  </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>Se deberán tener en cuenta al momento de la suscripción o
	endoso del contrato de afiliación el requerimiento de los siguientes datos:</p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Razón Social/Nombre y apellido </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>CUIT/DNI </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Domicilio (real/comercial/legal) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Teléfono </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>CIIU </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Fotocopia de DNI y/o Poder del firmante (titular o apoderado) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Formulario Declaración Jurada de Personas Expuestas Políticamente (PEP) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Fotocopia del Estatuto o Contrato constitutivo (cuando es una sociedad).
	</p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:11.55pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Fotocopia del decreto de designación de cargo del Organismo Público que
	corresponda o documento que acredite su función. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Toda la documentación presentada por el comercializador
	debe estar firmada por el vendedor y el empleador o apoderado.  </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:7.65pt;margin-left:0cm;text-align:left;text-indent:0cm;
	line-height:99%'>Una vez verificados y completados todos los formularios, el
	vendedor firma la impresión del mismo para dejar asentada su participación en
	el control de los datos relevados y avalando la autenticidad de la firma del
	empleador.   </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>Se debe tener en cuenta la Normativa emitida por los
	Organismos Regulatorios y de Control (SSN, SRT, UIF, etc.) y por lo establecido
	en la normativa interna PO-004 “Política de suscripción”, Procedimientos TE-01
	“Recepción de Solicitud de Cobertura”, TE-02 “Emisión, Endosos y Baja de
	contratos de Cobertura”, TE-03 “Emisión de Primas”, TE-04 “Tarifas
	Determinación y Aprobación”, TE-05 “Emisión y entrega de Certificados de
	cobertura” </p>

	<h3 class="auto-style1">8.2.<span style='font-family:"Arial",sans-serif'> </span>AL MOMENTO DE
	LA DENUNCIA DEL SINIESTRO </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Al producirse el siniestro (accidente o enfermedad profesional)
	el accidentado debe notificar inmediatamente a su empleador, quien deberá
	comunicarle a Coordinación de Emergencias Médicas (0800-333-1333). Dicha
	denuncia también puede realizarla el accidentado o un tercero. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Luego el empleador deberá formalizar la denuncia vía
	formulario papel o denuncia WEB. La documentación presentada será sustento del
	siniestro sucedido. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>El envío de la documentación por parte del Empleador se
	realiza cuando efectúa la denuncia del siniestro a Provincia ART y en el caso
	del Prestador Medico, cuando debe prestar el servicio médico. La documentación
	y/o formularios a presentar se encuentran detallados en el Procedimiento PM-03
	“Mesa de Ingresos” a saber: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.95pt;
	margin-left:-.25pt'>Documentación a presentar por el Empleador:  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.55pt;
	margin-left:51.25pt;text-indent:-12.35pt;line-height:99%'>•<span
	style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp; </span>“Informe de Accidente
	de Trabajo o Enfermedad Profesional” (formulario PM-03F002), para denunciar el
	siniestro a Provincia ART. (este formulario puede ser completado por el
	Empleado directamente y/o terceros) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:11.95pt;
	margin-left:51.25pt;text-indent:-12.35pt;line-height:99%'>•<span
	style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp; </span>“Solicitud de
	Asistencia Médica” (formulario PM-03-F001), permite al Empleado Accidentado ser
	atendido por un Prestador Médico de Provincia ART (quién deberá remitirlo a
	Provincia ART por los medios habilitados). </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Documentación a generar por el Prestador Médico durante la
	atención del accidente o enfermedad profesional:  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:-.25pt'>Formularios vigentes según Resolución SRT 1838/14: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:51.25pt;text-indent:-12.35pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span><span style='font-size:12.0pt;line-height:103%'>“</span>Constancia de
	Asistencia Médica (PM-03-F008) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:51.25pt;text-indent:-12.35pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>“Constancia de Parte Médico de Ingreso” (PM-03-F008) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:51.25pt;text-indent:-12.35pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>“Constancia de Alta Médica /Fin de Tratamiento” (PM-03-F009) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:51.25pt;text-indent:-12.35pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>“Constancia de Solicitud de Reingreso” (PM-03-F011) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:51.25pt;text-indent:-12.35pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>“Formulario Solicitud de Asistencia Médica”(PM-03-F001) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:51.25pt;text-indent:-12.35pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>“Informe de Accidente de Trabajo o Enfermedad Profesional”(PM-03-F002)  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:51.25pt;text-indent:-12.35pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>“Formulario de Solicitud de Autorización&quot; (PM-03-F005) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:51.25pt;text-indent:-12.35pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>“Formulario de Parte Médico de Evolución” (PM-03-F004) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:51.25pt;text-indent:-12.35pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>“Formulario de Solicitud de Traslados” (PM-03-F006) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:51.25pt;text-indent:-12.35pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>“Formulario de Solicitud de Derivación” (PM-03-F007) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:51.25pt;text-indent:-12.35pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>“formulario de Cese de I.L.T “ (PM-03-F010) </p>

	<h3 class="auto-style1">8.3.<span style='font-family:"Arial",sans-serif'> </span>AL MOMENTO DE
	LA LIQUIDACIÓN DEL SINIESTRO </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.95pt;
	margin-left:-.25pt'>Al momento de la liquidación de un siniestro se deberá
	tener en cuenta  </p>

	<p class=MsoNormal><span style='font-family:"Calibri",sans-serif'>        </span><b><i>8.3.1.</i></b><b><i><span
	style='font-family:"Arial",sans-serif'>      </span>Prestaciones Dinerarias </i></b></p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:21.9pt'>8.3.1.1.<span style='font-family:"Arial",sans-serif'> </span>Incapacidad
	Laboral Temporaria (ILT) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>De haber recibido el requerimiento de parte del trabajador
	accidentado o de su empleador, de la ILT correspondiente, verificar la
	existencia y condición del siniestro al verificar la procedencia del
	requerimiento, se solicita empleador la confección y envío del Formulario I:
	PM-01-F001. Adicionalmente, el accidentado o empleador adjuntarán los
	comprobantes respaldatorios correspondientes (copia de recibos de sueldos,
	comprobante de CUIL, copia de DNI, copia del certificado médico, etc.) para
	proceder a la mencionada liquidación, en caso de inconsistencias se procede a
	notificar el pedido de nuevos antecedentes o el rechazo de la liquidación. Las
	modalidades de liquidación pueden ser a) ILT Pago Directo o b) ILT Reintegro. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:21.9pt'>8.3.1.2.<span style='font-family:"Arial",sans-serif'> </span>Incapacidad
	Laboral Temporaria Reintegro </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.9pt;
	margin-left:71.45pt'>Para todas las empresas </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Formulario PM 01-F001.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:15.4pt;
	margin-left:108.05pt;text-indent:-17.3pt;line-height:99%'>•<span
	style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp; </span>Recibos de
	sueldo de los períodos de baja laboral firmados por el trabajador con la
	leyenda de “copia fiel” y la firma de un representante de la empresa. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:10.5pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Formularios 931 y tickets de pago de los periodos de baja laboral<span
	style='font-size:12.0pt;line-height:103%'>. </span></p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.9pt;
	margin-left:71.45pt'>Empresas consideradas VIP  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Formulario PM 01-F001.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Recibos de sueldo de los períodos de baja laboral sin la firma del
	trabajador ni la del empleador. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:11.45pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Formularios 931 y tickets de pago de los periodos de baja laboral. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.95pt;
	margin-left:71.45pt'>Empresas con acuerdos específicos  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Recibos de sueldo de los períodos de baja laboral sin la firma del
	trabajador ni la del empleador. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:12.1pt;
	margin-left:108.05pt;text-indent:-17.3pt;line-height:99%'>•<span
	style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp; </span>Con la
	autorización de Gerencia General en casos de contratos especialmente
	identificados se le reintegra el total de lo abonado por recibo al trabajador,
	realizando sobre liquidaciones en los casos necesarios. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:71.45pt'>Solicitudes Especiales con autorización del Gerente de
	Prestaciones y Servicios: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>En estos casos se autoriza a liquidar solo con las declaraciones juradas
	en los casos en los que no se haya presentado documentación. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>No se le solicita a las empresas copia de DNI del empleado accidentado,
	comprobante de CUIL y/o certificados médicos </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.9pt;
	margin-left:21.9pt'>8.3.1.3.<span style='font-family:"Arial",sans-serif'> </span>Incapacidad
	Laboral Temporaria Pago Directo. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Nota de solicitud del pago directo enviada por el empleador.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Nota o formulario de solicitud de pago directo enviado por el
	trabajador.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>En caso de corresponder carta documento al empleador asegurado con intimación.
	</p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>En caso de no aportar la documentación requerida se procede a realizar
	pago directo.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Constancia de Obra Social. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Impresión de pantalla con los datos personales y laborales del
	trabajador  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.55pt;
	margin-left:108.05pt;text-indent:-17.3pt;line-height:99%'>•<span
	style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp; </span>En los
	casos de “Empresas que tiene en el contratada el pago directo de la ILT”, se
	procede a efectuar la liquida-ción correspondiente, atento a lo informado en el
	“Seguimiento de casos para pago directo”. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.55pt;
	margin-left:108.05pt;text-indent:-17.3pt;line-height:99%'>•<span
	style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp; </span>En los
	casos de trabajadores desvinculados, se procede al pago directo de la ILT
	previa confirmación del distracto laboral en AFIP-Mi Simplificación,
	información suministrada a través del sistema de seguimiento de caso para pago
	directo.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Extranet de la SRT, donde constan las remuneraciones declaradas en
	AFIP-DGI para el cálculo de la PDM. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.55pt;
	margin-left:108.05pt;text-indent:-17.3pt;line-height:99%'>•<span
	style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp; </span>En los
	siniestros de trabajadores embarcados, se solicita tanto a la empresa como al
	damnificado, los recibos correspondientes a la marea en la cual se produjo el
	accidente, a los fines del cálculo y/o ajuste de la PDM en caso de no tener la
	totalidad de la documentación a la fecha de la liquidación de realiza el pago
	teniendo en cuentas las remuneraciones declaradas por la empresa ante AFIP. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Se solicita al Departamento Médico la auditoría correspondiente para que
	convalide el periodo de inhabilitación. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Casos no SUSS se liquida con documentación aportada por empleador o
	trabajador para el cálculo de la PDM </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>No se requiere comprobante de CUIL. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:108.05pt;text-indent:-17.3pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>No se requiere copia del DNI. En algunos casos la misma es aportada por
	el trabajador.   </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:21.9pt'>8.3.1.4.<span style='font-family:"Arial",sans-serif'> </span>INCAPACIDAD
	LABORAL PERMANENTE (ILP) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.8pt;
	margin-left:-.25pt'>Los liquidadores del sector Prestaciones Dinerarias
	imprimen los dictámenes autorizados para liquidar el día anterior. Las
	inconsistencias o falta de datos, se consultan vía mail al sector de
	Incapacidades, a fin de cumplir con los plazos legales para liquidar y aprobar
	indemnizaciones. Se controla la integridad y autenticidad de la misma
	verificado el cumplimiento de todos los requisitos, se procede a la emisión de
	la liquidación; de lo contrario se efectúa el reclamo pertinente ante el
	damnificado o empleador de éste, se pueden dar los siguientes tipos de
	liquidación de ILP, a) Incapacidad Laboral Permanente Parcial Definitiva
	(ILPPD) – Pago Único, b) Incapacidad Laboral Permanente Parcial Definitiva
	(ILPPD) – Renta Vitalicia, c) Incapacidad Laboral Permanente Parcial Provisoria
	(ILPPP) – Pago Mensual, d) Incapacidad Laboral Permanente Total Definitiva
	(ILPTD), e) Incapacidad Laboral Permanente Total Provisoria (ILPTP), f) Gran
	Invalidez Provisoria (GIP), g) Gran Invalidez Definitiva (GID). </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:21.9pt'>8.3.1.5.<span style='font-family:"Arial",sans-serif'> </span>Indemnización
	Por Fallecimiento </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Se recibe del sector Investigación de Siniestros la
	notificación de la aceptación de un siniestro mortal, mediante el Formulario
	IX: PM-01-F008 debidamente completado, junto a la documentación respaldatoria,
	se verifica la misma y en caso de inconsistencias, falta de documentación o que
	el trabajador haya fallecido con posterioridad a la investigación del
	siniestro, se hace el reclamo correspondiente a los familiares del accidentado.
	(Este criterio también alcanza a aquellos siniestros rechazados y luego
	revertidos por la Comisión Médica). Si la primera manifestación invalidante es
	de fecha posterior al 28/02/01, la liquidación incluye la Prestación Adicional
	de Pago Único. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>En todos los casos se debe contemplar lo establecido en la
	legislación vigente en materia de liquidación de prestaciones dinerarias y en
	lo dispuesto por el Órgano de Administración en el Procedimiento PM-01
	“Prestaciones dinerarias”. </p>

	<p class=MsoNormal align=left style='margin-top:0cm;margin-right:0cm;
	margin-bottom:14.75pt;margin-left:0cm;text-align:left;text-indent:0cm;
	line-height:107%'><span style='font-family:"Calibri",sans-serif'>        </span><b><i>8.3.2.</i></b><b><i><span
	style='font-family:"Arial",sans-serif'>      </span>Prestaciones en Especie: </i></b></p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:21.9pt'>8.3.2.1.<span style='font-family:"Arial",sans-serif'> </span>Sector
	Auditoría Médica de Facturas: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.9pt;
	margin-left:-.25pt'>A efectos de verificar la procedencia del pago de
	prestaciones en especie se deberá tener en cuenta lo dispuesto en la
	legislación vigente y a la normativa interna de la compañía en especial a lo
	establecido en el procedimiento AF-07 “Auditoría Médica de Facturas y
	Liquidación de Prestaciones en Especie”, se tendrán en cuenta los siguientes
	parámetros: Por cada Volante “A” recibido y por cada Volante “L” con estado
	“Pendiente Auditar” el Auditor Médico verifica para cada prestación alcanzada
	por Auditoría: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La carga en el sistema del siniestro correspondiente a dicha prestación </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La existencia de la autorización de la prestación </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La fecha de realización de la misma. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La existencia de documentación que respalde su realización  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Que el consumo del siniestro demuestre que la prestación no fue abonada
	con anterioridad.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Su justificación y pertinencia (desde el punto de vista médico) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Si cumple con las normas generales del Nomenclador Nacional (si
	correspondiere)  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La carga en el sistema de su convenio y vigencia o en su defecto su
	inclusión en el nomenclador que correspondiere </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>El cumplimiento de la normativa vigente sobre el tratamiento de heridas
	cortopunzantes con riesgo biológico. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La existencia de sugerencias de débitos efectuados por el Médico Auditor
	(en caso que siniestro disponga de un Informe de Auditoría en Terreno) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La coincidencia entre la descripción de la prótesis autorizada a la
	ortopedia, con lo presentado en el protocolo médico quirúrgico. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:21.9pt'>8.3.2.2.<span style='font-family:"Arial",sans-serif'> </span>Sector
	Liquidaciones y Reintegros </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.9pt;
	margin-left:-.25pt'>Sobre las facturas y antecedentes recibidos se debe
	verificar: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La existencia de la autorización de la prestación </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Si se pagó una autorización similar para el mismo período </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La concordancia entre la prestación autorizada y la facturada  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La concordancia entre la cantidad de prestaciones autorizadas con la
	cantidad de prestaciones facturadas </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La existencia de las prestaciones facturadas en el convenio con el
	prestador </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La concordancia entre el monto de la prestación o módulo facturado con
	el monto convenido </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La vigencia del convenio entre el prestador y Provincia ART </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:11.7pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La presentación de documentación que respalde las prácticas ortopédicas
	(certificado de implante, remito de recepción de material, sticker y
	autorización). </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Si el Liquidador identifica que el prestador no posee
	convenio con la ART asigna al volante el estado “Derivado Contrataciones”,
	solicitando al sector Gestión de Prestadores mediante un correo electrónico
	(antes SGC) la carga de los valores en el sistema dentro de las 72 hs hábiles.
	Con la confirmación del sector, el Liquidador cambia desde el sistema el estado
	del volante, pasándolo nuevamente a “Pendiente”.  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>El Liquidador efectúa un débito completando en el campo
	asignado el importe neto a abonar y especificando en el campo “Observaciones”
	los motivos del mismo, en los casos en que: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>El monto de lo facturado por el prestador no coincide con lo acordado en
	el convenio (por práctica o por módulo) </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>No corresponde lo facturado por el prestador con respecto a las
	autorizaciones aprobadas </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La cantidad de prestaciones facturadas es mayor a las autorizadas </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>El afiliado fue dado de baja  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>El prestador asignado en la autorización es incorrecto </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Las prestaciones facturadas corresponden a prácticas que no han sido
	autorizadas con antelación, o que tienen autorización con estado “Pendiente”. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.55pt;
	margin-left:47.4pt;text-indent:-8.5pt;line-height:99%'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Para casos de internados, se debitará la práctica cuando su fecha de
	realización sea posterior a la fecha de alta de internación, ya sea por alta
	médica o por derivación a la obra social del accidentado, siempre y cuando la
	misma no haya sido autorizada por la Gerencia Prestaciones y Servicios. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Falta de documentación respaldatoria de la prestación facturada. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>Falta de alta médica </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>No corresponde firma de paciente </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:11.35pt;
	margin-left:47.4pt;text-indent:-8.5pt'>•<span style='font:7.0pt "Times New Roman"'>
	</span>La medicación facturada está incluida en el módulo facturado </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>En caso de existir, el Liquidador imputa los débitos
	sugeridos y avalados previamente por el Sector Auditoría Médica de Facturas. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:21.9pt'>8.3.2.3.<span style='font-family:"Arial",sans-serif'> </span>Auditoría
	de Refacturación </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>El circuito de volantes correspondientes a refacturaciones
	es el mismo que el establecido en los puntos precedentes al momento de efectuar
	los controles necesarios, el Auditor Médico o el Liquidador, según corresponda,
	debe verificar los argumentos justificativos de la refacturación de acuerdo a
	los motivos que originaron el ajuste a la factura original. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:21.9pt'>8.3.2.4.<span style='font-family:"Arial",sans-serif'> </span>Auditoría
	Compartida </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:-.25pt'>Si el prestador médico se negara a aceptar los débitos
	efectuados por el Sector Auditoría Médica de Facturas, puede solicitar la
	realización de una auditoría compartida sobre dichos ajustes, anunciándoselo al
	Responsable de dicho sector personalmente, o a través de una nota, correo electrónico
	y/o Carta Documento. A tal efecto, el Auditor Médico procede a reunir los
	antecedentes con que se cuenta sobre los ajustes objeto de la revisión
	solicitada por el prestador (factura original, re-facturación, notas de ajuste,
	documentación adjunta, etc.) y dentro de las 96 horas hábiles desde recibida la
	solicitud contacta al prestador vía teléfono y/o correo electrónico para
	coordinar fecha, lugar y hora de reunión. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:20.8pt;
	margin-left:-.25pt'>Finalizada la auditoría compartida, el Auditor Médico
	realiza un Acta de Auditoria Compartida indicando los conceptos y montos
	debitados y a pagar, firmando ambas partes dicho documento. Adjunta al Acta la
	nota de ajuste que da origen al reclamo del prestador entregando copia del Acta
	al prestador médico. </p>

	<h2 class="auto-style1">9.<span style='font-family:"Arial",sans-serif'> </span>PROCEDIMIENTOS
	DE CONTROL (programa de verificación de cumplimiento) </h2>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>El programa de verificación de cumplimiento de las
	políticas y procedimientos para luchar contra el fraude, será aprobado por el
	Comité de Prevención de Fraude durante el mes de diciembre de cada año y
	elevado a consideración del Órgano de Administración en la primera reunión de
	Directorio que se desarrolle al año siguiente, el que una vez aprobado se
	transcribirá en el libro de Actas de Directorio. </p>

	<h3 class="auto-style1">9.1.<span style='font-family:"Arial",sans-serif'> </span>INFORME ANUAL
	</h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>En la ejecución del programa de verificación se elaborará
	un informe anual en el que se consignarán, en caso de ser detectados, los
	desvíos de significación y las recomendaciones para su regularización. Dicho
	Informe deberá ser presentado al Comité de Prevención de Fraude en diciembre de
	cada año, para su aprobación; y luego deberá someterse, junto con el programa
	de verificación anual, para su tratamiento por el Directorio, quien se expedirá
	respecto de los informes, el régimen de recomendaciones y acciones de
	seguimiento frente a eventuales desvíos significativos que hubieran sido
	detectados. </p>

	<h3 class="auto-style1">9.2.<span style='font-family:"Arial",sans-serif'> </span>ANTECEDENTES
	Y ELEMENTOS DE RESPALDO DEL INFORME </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.35pt;
	margin-left:-.25pt'>Los papeles de trabajo que se utilicen para la elaboración
	de los informes deberán contener como mínimo lo siguiente: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:53.65pt;margin-bottom:
	7.4pt;margin-left:53.4pt;text-indent:-10.8pt'>i.<span style='font:7.0pt "Times New Roman"'>&nbsp;
	</span>La descripción de la tarea realizada. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:53.65pt;margin-bottom:
	.05pt;margin-left:53.4pt;text-indent:-10.8pt;line-height:159%'>ii.<span
	style='font:7.0pt "Times New Roman"'> </span>Los datos y antecedentes recogidos
	durante el desarrollo de la tarea. iii. Las limitaciones al alcance de la
	tarea. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:43.1pt'>iv.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Las conclusiones sobre el examen de cada rubro o área y las conclusiones
	finales o generales del trabajo. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:43.1pt'>v.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Posibles desvíos y recomendaciones para implementar acciones de
	regularización, con plan de seguimiento. </p>

	<h3 class="auto-style1">9.3.<span style='font-family:"Arial",sans-serif'> </span>CUSTODIA Y
	RESGUARDO </h3>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:20.75pt;
	margin-left:-.25pt'>Los antecedentes y elementos que respalden los informes que
	se presenten ante el Directorio se conservarán en legajos foliados, y
	permanecerán a disposición de la SUPERINTENDENCIA DE SEGUROS DE LA NACIÓN por
	el término de tres (3) años como mínimo. El responsable del área de control
	interno de la compañía estará a cargo de su custodia y resguardo. </p>

	<h2 class="auto-style1">10.<span style='font-family:"Arial",sans-serif'> </span>LISTADOS DE
	CHEQUEO DE ALERTAS DE DETECCIÓN TEMPRANA </h2>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:14.75pt;
	margin-left:-.25pt'>Se efectuarán mensualmente procedimientos preventivos de
	control de Fraude en procesos relacionados con:  </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:49.7pt;text-indent:-10.8pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;
	</span>El momento de la Suscripción o Endosos </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:49.7pt;text-indent:-10.8pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;
	</span>El momento de la denuncia del siniestro </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:18.9pt;
	margin-left:49.7pt;text-indent:-10.8pt'>•<span style='font:7.0pt "Times New Roman"'>&nbsp;
	</span>El momento de la liquidación del siniestro </p>

	<H2 class=auto-style1><b>11.<span style='font-family:"Arial",sans-serif'> </span>RECEPCIÓN Y TRATAMIENTO DE DENUNCIAS DE SOSPECHA DE FRAUDE O FRAUDEA</b></p>
	</H2>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>Recibida una denuncia por los canales de comunicación que
	se habilitan a tal efecto, ésta será evaluada por el Comité de Prevención de
	Fraude. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:-.25pt'>En caso de que el Comité de Prevención de Fraude determine
	la existencia de una sospecha de fraude, se instruirá su investigación por
	parte del sector Prevención de Fraude, quien elaborará una memoria del caso
	investigado por sospecha de fraude, en la que se registrará un resumen o síntesis
	que describa brevemente los principales contenidos del caso, acorde con las
	siguientes pautas: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>1)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Si se trata de un fraude en una relación e seguros de riesgos del
	trabajo. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>2)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Fecha y lugar de concertación de la cobertura, en caso de existir. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:94.8pt;text-indent:-22.8pt'>3)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Número de contrato de afiliación, con fecha y lugar de suscripción, en
	caso de corresponder. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
	margin-left:94.8pt;text-indent:-22.8pt'>4)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Vigencia de la cobertura (fecha de inicio/fin), en caso de corresponder.
	</p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>5)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Fecha y lugar del siniestro, en caso de corresponder. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>6)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Fecha de la denuncia. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>7)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Hechos denunciados con indicación precisa del reclamo. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:94.8pt;text-indent:-22.8pt'>8)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Datos de la Comisaría y/o de los funcionarios de Gendarmería y/o
	Prefectura y/o Bomberos y/o Defensa Civil, que eventualmente hayan tomado
	intervención en el siniestro. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:13.0pt;
	margin-left:94.8pt;text-indent:-22.8pt'>9)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Investigación producida e indicadores considerados. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>10)<span style='font:7.0pt "Times New Roman"'>
	</span>Elementos de prueba recabados. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>11)<span style='font:7.0pt "Times New Roman"'>
	</span>Hechos descubiertos de manera clara y concisa. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>12)<span style='font:7.0pt "Times New Roman"'>
	</span>Datos del/los tomador/es, asegurado/s, beneficiario/s, damnificado/s. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>13)<span style='font:7.0pt "Times New Roman"'>
	</span>Datos del/los presunto/s involucrado/s. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.2pt;
	margin-left:94.8pt;text-indent:-22.8pt'>14)<span style='font:7.0pt "Times New Roman"'>
	</span>Datos del profesional (abogado, médico, etc.) que eventualmente hubiera
	prestado colaboración para la maniobra. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.25pt;
	margin-left:94.8pt;text-indent:-22.8pt'>15)<span style='font:7.0pt "Times New Roman"'>
	</span>Datos del Productor Asesor de Seguros (o Sociedad de Productores) que
	eventualmente hubiera intermediado y/o organizador y/o agente institorio que
	hubiere intervenido en la concertación de la cobertura. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>16)<span style='font:7.0pt "Times New Roman"'>
	</span>Datos de los testigos. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>17)<span style='font:7.0pt "Times New Roman"'>
	</span>Datos de los abogados de las partes (asegurado/tomador/beneficiario y —
	de corresponder— del tercero damnificado). </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>18)<span style='font:7.0pt "Times New Roman"'>
	</span>Si ha intervenido algún liquidador de siniestros o inspector, su
	individualización y breve resumen de su informe. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>19)<span style='font:7.0pt "Times New Roman"'>
	</span>Conclusión del caso con la siguiente parametrización mínima: </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:287.2pt;margin-bottom:
	7.4pt;margin-left:124.2pt;text-indent:-10.8pt'>i.<span style='font:7.0pt "Times New Roman"'>&nbsp;
	</span>Acuerdo. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:287.2pt;margin-bottom:7.4pt;margin-left:124.2pt;text-indent:-10.8pt;line-height:
	159%'>ii.<span style='font:7.0pt "Times New Roman"'> </span>Desistimiento. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:287.2pt;margin-bottom:7.4pt;margin-left:124.2pt; ;text-indent:-10.8pt;line-height:
	159%'>iii.<span style='font:7.0pt "Times New Roman"'> </span> Reticencia. </p>


	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:133.7pt;text-indent:-20.3pt'>iv.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Rechazo del siniestro. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:133.7pt;text-indent:-20.3pt'>v.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;
	</span>Prescripción. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:133.7pt;text-indent:-20.3pt'>vi.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Caducidad de instancia. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
	margin-left:133.7pt;text-indent:-20.3pt'>vii.<span style='font:7.0pt "Times New Roman"'>&nbsp;
	</span>Sentencia que rechaza la demanda. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:133.7pt;text-indent:-20.3pt;line-height:159%'>viii.<span
	style='font:7.0pt "Times New Roman"'> </span>Condena en juicio.</p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:133.7pt;text-indent:-20.3pt;line-height:159%'>ix. <span
	style='font:7.0pt "Times New Roman"'> </span> Denuncia
	penal. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:130.7pt;text-indent:-17.3pt'>x.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;
	</span>Querella penal o rol de particular damnificado en proceso penal. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:130.7pt;text-indent:-17.3pt'>xi.<span style='font:7.0pt "Times New Roman"'>&nbsp;
	</span>Procesamiento penal. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:130.7pt;text-indent:-17.3pt;line-height:159%'>xii.<span
	style='font:7.0pt "Times New Roman"'> </span>Suspensión del juicio a prueba
	(probation).</p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:130.7pt;text-indent:-17.3pt;line-height:159%'>xiii.<span
	style='font:7.0pt "Times New Roman"'> </span> Condena penal del imputado. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:113.9pt'>xiv. Otros. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>20)<span style='font:7.pt "Times New Roman"'>
	</span>Montos involucrados. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>21)<span style='font:7.0pt "Times New Roman"'>
	</span>Indicar si se hizo denuncia penal y seguimiento, o alguna presentación
	ante Asociación, Colegio o Consejo profesional de corresponder. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:94.8pt;text-indent:-22.8pt'>22)<span style='font:7.0pt "Times New Roman"'>
	</span>Otras consideraciones de interés. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.4pt;
	margin-left:-.25pt'>La memoria de cada caso será archivada en el sector
	Prevención de Fraude, asignándose un número correlativo a cada una, comenzando
	por el “1”, debiendo quedar a disposición del Comité de Prevención de Fraude y
	de las autoridades que tengan competencia para requerirlas. </p>

	<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:1.25pt;
	margin-left:-.25pt'>En caso de que se considere la existencia de indicios que
	podrían configurar un fraude, asimismo, se presentará el caso ante la Unidad de
	Análisis de Litigiosidad y Control de Fraude de la Superintendencia de Riesgos
	del Trabajo. </p>
	<br>
	</div>
		</i>
		<div style="background-color:#6FB43F; <?= ($fechaAceptacion != "")?"height:36px;":""?> margin-top:8px; padding:18px;">
			<div class="auto-style1">
				<span style="margin-left:5px; color:#FFFFFF" class="auto-style3">Nombre y apellido: </span> 
				<span class="auto-style3"><?= strtoupper(GetUserName())?></span></div>
			<div style="margin-left:37px; margin-top:4px;" class="auto-style1">
				<span class="auto-style3">&nbsp;&nbsp;
	<?php 
		if($muestroCotrol){ 
	?>	
				</span>	
				<span style="color:#FFFFFF" class="auto-style3">&nbsp;Me notifico:</span><i>
				<input id="notificado" name="notificado" style="margin-left:5px; vertical-align:-3px;" type="checkbox" class="auto-style3" />
				<input class="auto-style3" type="button" value="GUARDAR" onClick="guardar()" />
				</i><span class="auto-style3">
				
	<?php }else{ ?>				
				</span>				
			<span style="color:#FFFFFF" class="auto-style3">Ya se notificó</span>
				<span class="auto-style3">
	<?php } ?>
				</span>
			</div>
		</div>	
</div>
</div>
