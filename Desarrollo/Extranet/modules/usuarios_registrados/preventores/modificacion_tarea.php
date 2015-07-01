<?		
	validarSesion(isset($_SESSION["isPreventor"]));
	require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");
		$params = array(":idvisita" => $_GET["id"]);
	$sql = 
		" SELECT pit.*, aes.*, aco.*, aem.*, hvd.*, hvp.*,to_char(VP_FECHAVISITA, 'HH24:MM') horavisita,
				 (SELECT COUNT (*)
					FROM hys.hvp_visitapreventor hvp2
				   WHERE hvp2.vp_fechabaja IS NULL
					 AND TRUNC (hvp2.vp_fechavisita) = TRUNC (hvp.vp_fechavisita)
					 AND hvp2.vp_origen = 'P'
					 AND hvp2.vp_idpreventor = hvp.vp_idpreventor) cantvisitas,
				 DECODE (art.afiliacion.is_empresavip (co_contrato),
						 'S', 'VIP',
						 'N', NULL
						) empresavip,
				 DECODE (vp_origen,
						 'P', 'Preventor',
						 'A', 'Actuaciones'
						) origencarga
			FROM art.pit_firmantes pit,
				 afi.aes_establecimiento aes,
				 afi.aco_contrato aco,
				 afi.aem_empresa aem,
				 hys.hvd_visitadeclarada hvd,
				 hys.hvp_visitapreventor hvp
		   WHERE em_id = vp_idempresa
			 AND em_id = co_idempresa
			 AND es_contrato = co_contrato
			 AND co_contrato = art.get_vultcontrato (em_cuit)
			 AND es_nroestableci = vp_establecimiento
			 AND TRUNC (vp_fechavisita) = vd_fechavisita(+)
			 AND vp_idpreventor = vd_idpreventor(+)
			 AND it_id = vp_idpreventor
			 AND vp_fechabaja IS NULL
			 AND vp_id = :idvisita";
	$stmt = DBExecSql($conn, $sql,$params);	
	$rowprincipal = DBGetQuery($stmt);
	$_SESSION["CARGA_TAREA"]["cuit"] = $rowprincipal['EM_CUIT'];
	$_SESSION["CARGA_TAREA"]["establecimiento"] = $rowprincipal['ES_ID'];
	$_SESSION["CARGA_TAREA"]["contrato"] = $rowprincipal['CO_CONTRATO'];
	$_SESSION["CARGA_TAREA"]["nombre"] = $rowprincipal['EM_NOMBRE'];
	require_once("carga_tareas_combos.php");
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/design.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css?rnd=20141202" type="text/css" />
		<script src="/js/functions.js?rnd=20130802" type="text/javascript"></script>


		<script src="/modules/usuarios_registrados/preventores/js/carga_tareas.js" type="text/javascript"></script>
	</head>
	<body>
	<iframe id="iframe2" name="iframe2" src="" style="display:none;"></iframe>
	<h1 class="TituloSeccion" style="display:block;width:700px;">Modificación Tarea</h1>
	<script type="text/javascript">
		function submitForm() {
			document.getElementById('btnGuardar').style.display = 'none';
			document.getElementById('divProcesando').style.display = 'block';
			return true;
		}
	</script>
	<div class="ContenidoSeccion" style="margin-top:15px;text-align:left;" >
		 
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<script src="/modules/usuarios_registrados/preventores/js/alta_tareas.js" type="text/javascript"></script>
		<form action="/modules/usuarios_registrados/preventores/procesar_modificacion_tarea.php" id="formAltaTarea" method="post" name="formAltaTarea" target="iframeProcesando" onSubmit="return submitForm(true)">
		<div>
			<input id="idTarea" name="idTarea" type="hidden" value="<?=$_GET["id"];?>" />
			<label style="margin-left:0px;">CUIT</label>
			<input id="cuit" name="cuit" style="cursor:default; width:76px;" type="text" value="<?=$rowprincipal['EM_CUIT'];?>" readonly="true"/>
			<label style="margin-left:3px;">Raz&oacuten Social</label>
			<input id="razonSocial" name="razonSocial" style="cursor:default;  width:400px;" type="text" value="<?=$rowprincipal['EM_NOMBRE'];?>" readonly="true" />
			<label style="margin-left:3px;">Contrato</label>
			<input id="contrato" name="contrato" style="cursor:default; width:40px;" type="text" value="<?=$rowprincipal['CO_CONTRATO'];?>"  readonly="true"/>
		</div>
		<div style="margin-top:10px;">
			<label>Establecimiento</label>
			<?php $comboEstablecimiento->setDisabled(true);
				  $comboEstablecimiento->draw();
				   ?>
		</div>
		<div style="margin-top:10px;">
			<label style="margin-left:0px;">Fecha Visita&nbsp </label>
			<input id="fechaVisita" maxlength="10" name="fechaVisita" style="width:64px;" title="Fecha Visita" type="text" validarFecha="true" value="<?=$rowprincipal['VP_FECHAVISITA'];?>">
			<input class="botonFecha" id="btnFechaVisita" name="btnFechaVisita" style="vertical-align:-5px;" type="button" value="">		
			<label style="margin-left:3px;">Hora Desde</label>
			<input id="horaDesde" name="horaDesde" type="time" value="<?=$rowprincipal['HORAVISITA'];?>"/>
			<label style="margin-left:3px;">Cant.Visitas del Día</label>
			<input id="cantVisitas" name="cantVisitas" style="cursor:default;  width:60px;" type="text" value="<?=$rowprincipal['VD_CANTVISITAS'];?>" />
			<label style="margin-left:3px;">/</label>
			<input id="cantVisitasCargadas" name="cantVisitasCargadas" style="cursor:default;  width:60px;" type="text" value="<?=$rowprincipal['CANTVISITAS'];?>" readonly="true" />
		</div>
		<div  style="margin-top:10px;">
			<label style="margin-left:0px;">Fecha Viatico</label>
			<input id="fechaViatico" maxlength="10" name="fechaViatico" style="width:64px;" title="Fecha Viatico" type="text" validarFecha="true" value="<?=$rowprincipal['VP_FECHAVIATICO'];?>">
			<input class="botonFecha" id="btnFechaViatico" name="btnFechaViatico" style="vertical-align:-5px;" type="button">		
			<label style="margin-left:3px;">Kms</label>
			<input id="kms" name="kms" style="cursor:default;  width:60px;" type="text" value="<?=$rowprincipal['VP_KMS'];?>" />
		</div>
		<div  style="margin-top:10px;">
			<label style="margin-left:3px;">Tareas</label>
			<label style="margin-left:330px;">Detalle</label>
		</div>
		<div style="margin-top:5px;overflow-y:scroll;height:135px;border:thick solid #DDDDDD;border-width:3px">
			<table  >
			<?	
				
				$sql =
					"SELECT   *
					   FROM hys.hta_tarea, hys.htp_tareapreventor
					  WHERE ta_visible = 'S'
						AND ta_fechabaja IS NULL
						AND tp_idvisitapreventor(+) = ".$_GET["id"].
					"	AND tp_idtarea(+) = ta_id
						AND tp_fechabaja IS NULL
						AND ta_fechabaja IS NULL
						AND ta_visible = 'S'
				   ORDER BY TP_IDTAREA, 2";
				$stmt = DBExecSql($conn, $sql);	
				$count = 0;
				while ($row = DBGetQuery($stmt))
				{
					if ($count == 0)
					{
					
			?>
				<tr>
			<?
					}
			?>
					<td>
						<input  id="item_<? echo $row["TA_ID"]; ?>" name="item_<? echo $row["TA_ID"]; ?>" style="vertical-align:-2px; border:0" type="checkbox" value="<? echo $row["TA_ID"]; ?>" onClick="mostrarDetallaTarea(<?= $row["TA_ID"]?>)"
								<?if($row["TP_IDTAREA"] != ""){	echo "checked"; }?> />
						<label style="margin-left:8px;"><? echo $row["TA_DESCRIPCION"]; ?></label>
					</td>
					<td>
					<div id= "divDetalleTarea<?=$row["TA_ID"];?>" style= "<?=($row["TP_IDTAREA"] != "")?"display:block":"display:none"?>">
			<?
					$sqlDetalle = 
					" SELECT MO_ID id, MO_DESCRIPCION detalle 
						FROM ART.PMO_MOTIVOS, HYS.HTM_TAREAMOTIVO 
					   WHERE TM_IDTAREA = :idtarea 
						 AND TM_FECHABAJA IS NULL 
						 AND TM_IDMOTIVO = MO_ID 
						 AND MO_FECHABAJA IS NULL ";
					if($row["TP_IDTAREA"]!='')
					{
						$idmotivo = $row["TP_IDMOTIVO"];
					}
					else
					{ 
						$idmotivo = -1;
					}
					$comboDetalle = new Combo($sqlDetalle, "detalleTarea_".$row["TA_ID"], $idmotivo );
					$comboDetalle->setFirstItem("- SELECCIONE DETALLE TAREA -");
					$comboDetalle->addParam(":idtarea",$row["TA_ID"] );
					$comboDetalle->setClass("combo");
					$comboDetalle->draw();
					
			?>
					</div>
					</td>
			<?
					$count++;
					if($count == 1)
					{
			?>
				</tr>
			<?       
					$count = 0;
					}
				}
				if ($count >0)
				{
			?>
				</tr>
			<?
				}
			?>
			
			</table>
		</div>
		<div  style="margin-top:5px;">
			<label>Observaciones</label>
		</div>
		<div  style="margin-top:5px;">
			<textarea name="observaciones" id= "observaciones" cols="140" rows="3" style="color: #808080; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><?=$rowprincipal['VP_OBSERVACIONES'];?></textarea>
		</div>
		<div id="guardarDiv" style="text-align:right;margin-top:8px;margin-right:5px;">
			<input class="btnGuardar" id="btnGuardar" name="btnGuardar" type="submit" value="" />
			<a href="/prevencion/Carga-Tareas"><input class="btnCancelar" id= "btnCancelar" name = "btnCancelar" type="button" value="" style="width:70px" /></a>
			<a href="/prevencion/Carga-Tareas"><input class="btnFinalizar" id= "btnFinalizar" name = "btnFinalizar" type="button" value="" style="display:none" /></a>
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
		<form id="form" name="form">
			<div align="center" id="divContentGrid" name="divContentGrid"></div>
		</form>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:10px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	</div>
	</body>
	<script type="text/javascript">
		Calendar.setup ({
			inputField: "fechaVisita",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaVisita"
		});
		Calendar.setup ({
			inputField: "fechaViatico",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaViatico"
		});
	</script>
</html>