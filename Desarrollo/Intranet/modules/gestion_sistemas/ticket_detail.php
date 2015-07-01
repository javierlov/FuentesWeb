<?
require_once ($_SERVER["DOCUMENT_ROOT"] . "/constants.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/database/db_funcs.php");

require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/CrearLog.php");
/* Implementación de multiples sistemas dentro del sistema de tickets */
$MNU = GetParametro("MNU", 0);
$sistema = GetParametro("sistema", 1);

try {
    global $conn;

    $arrayConfig = GetConfigSistema($sistema);
    $interno = $arrayConfig['ST_INTERNO'];

    $sql = "SELECT ticket, nro_ticket, usuario, fecha, tipo_motivo, detalle_motivo, notas, ss_idestadoactual, estado, pc,
					   CASE
						 WHEN NOT EXISTS(SELECT 1
										   FROM computos.cps_permisosolicitud
										  WHERE ps_idsolicitud = ticket
											AND ps_fechaautorizacion IS NULL) THEN usu_auth
						 ELSE (SELECT ps_idusuario
								 FROM computos.cps_permisosolicitud
								WHERE ps_fechaautorizacion IS NULL
								  AND ps_idsolicitud = ticket)
					   END AS usu_auth,
					   ss_idusuario_solicitud, prioridad
				  FROM (SELECT ss_id AS ticket, ss_nro_ticket AS nro_ticket, se_nombre AS usuario, TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') AS fecha,
							   cms2.ms_descripcion AS tipo_motivo, cms1.ms_descripcion AS detalle_motivo, ss_notas AS notas,
							   ss_idestadoactual, es_descripcion AS estado, eq_descripcion AS pc,
							   computos.general.get_usuarioresponsable
											(NVL((SELECT DECODE(hs_idestado,
																2, hs_idusuario_cambio,
																ss_idusuario_solicitud)
													FROM computos.chs_historicosolicitud chs1
												   WHERE chs1.hs_idsolicitud = ss_id
													 AND chs1.hs_fecha_cambio =
														   (SELECT MAX(chs2.hs_fecha_cambio)
															  FROM computos.chs_historicosolicitud chs2
															 WHERE chs1.hs_idsolicitud = chs2.hs_idsolicitud
															   AND chs2.hs_idusuario_cambio NOT IN(SELECT se_id
																									 FROM art.use_usuarios
																									WHERE se_sector = 'COMPUTOS')
															   AND chs2.hs_idestado = 2)),
												 ss_idusuario_solicitud),
											 cms1.ms_nivel) AS usu_auth,
							   ss_idusuario_solicitud,
							   CASE ss_prioridad
								 WHEN -1 THEN 'Sin definir'
								 WHEN 1 THEN 'Alta'
								 WHEN 2 THEN 'Media'
								 WHEN 3 THEN 'Baja'
							   END AS prioridad
						  FROM art.use_usuarios, computos.ces_estadosolicitud, computos.ceq_equipo, computos.cms_motivosolicitud cms2,
							   computos.cms_motivosolicitud cms1, computos.css_solicitudsistemas
						 WHERE ss_idusuario_solicitud = se_id
						   AND ss_idmotivosolicitud = cms1.ms_id
						   AND cms1.ms_idpadre = cms2.ms_id
						   AND ss_idequipo = eq_id(+)
						   AND ss_idestadoactual = es_id
						   AND ss_id = :ID)";
EscribirLogTxt1("sql", $sql);
    $params = array(":id" => $_REQUEST["id"]);
    $stmt = DBExecSql($conn, $sql, $params);
    $row = DBGetQuery($stmt);

    $usu_auth = $row["USU_AUTH"];
    $usu_soli = $row["SS_IDUSUARIO_SOLICITUD"];

    $jefe = ValorSQL("SELECT se_id
							FROM art.use_usuarios 
						   WHERE se_usuario = (SELECT se_respondea
												 FROM art.use_usuarios
												WHERE se_id = " . $usu_auth . ")");
    $showAuthorizationButton = (($usu_auth == GetUserID()) or ($jefe == GetUserID()));
} catch (Exception $e) {
    DBRollback($conn);
    echo "<script type='text/javascript'>alert(unescape('" . rawurlencode($e -> getMessage()) . "'));</script>";
    exit ;
}
/*
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
*/
?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
	<head>
		<title>Registración</title>
		<meta content="Mon, 06 Jan 1990 00:00:01 GMT" http-equiv="Expires" />
		
		<script type="text/javascript" src="/Js/functions.js"></script>
		<script type="text/javascript" src="/Js/validations.js"></script>

		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/Js/Calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/Js/Calendario/calendar.js"></script>
		<script type="text/javascript" src="/Js/Calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/Js/Calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->
<?
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/general.php");
?>
	</head>
	<body>
		<form id="formSolicitud" name="formSolicitud" method="post" action="index.php?sistema=<?echo $sistema; ?>&id=<?=$_REQUEST["id"] ?>">
		
<div align="center" class="contentIn" style="padding:12px; margin:0px; border:12px; "  >		

		<div id="stylized" class="formGeneric550" >
				<b style="font-size:16px; font-weight:bold;">Información del Ticket</b>
				<br />
				<p>Puede llamar al interno <?echo $interno; ?> para obtener mas información.</p>
	<table>
		<tr>
			<td>		
				<span id="adjuntos" style="text-align:center; display:none; font:12px Neo Sans;"><a href="#titleAdjuntos">Este ticket tiene adjuntos</a></span>
			</td>
		</tr>
		
		<tr>
			<td>			
				<label class="labelTitulo" >Referencia
					<span class="small">N° de Ticket</span>
				</label>
				<input id="Ticket" name="Ticket" readonly="true" value="<?= $row["NRO_TICKET"] ?>"></input>
				<input type="hidden" id="idticket" name="idticket"  value="<?= $_REQUEST["id"] ?>"></input>
			</td>
		</tr>
		
		<tr>
			<td>
				<label class="labelTitulo" >Solicitante
					<span class="small">Por ejemplo: Juan Pérez</span>
				</label>
				<input id="UsuarioSolicitud" name="UsuarioSolicitud" readonly="true" value="<?= $row["USUARIO"] ?>"></input>				
			</td>
		</tr>
		<tr>
			<td>
				<label class="labelTitulo" >Solicitud
					<span class="small">Fecha de la solicitud</span>
				</label>
				<input id="TipoPedido" name="TipoPedido" readonly="true" value="<?= $row["FECHA"] ?>"></input>
			</td>
		</tr>
		<tr>
			<td>
				<label class="labelTitulo" >Pedido
					<span class="small">Tipo de pedido</span>
				</label>
				<input id="TipoPedido" name="TipoPedido" readonly="true" value="<?= Trim($row["TIPO_MOTIVO"]) ?>"></input>
			</td>
		</tr>
		<tr>
			<td>
				<label class="labelTitulo" >Detalle
					<span class="small">Detalle del pedido</span>
				</label>
				<input id="DetallePedido" name="DetallePedido" readonly="true" value="<?= Trim($row["DETALLE_MOTIVO"]) ?>"></input>
			</td>
		</tr>
		<tr>
			<td>
				<label class="labelTitulo" >Descripción
					<span class="small">Acerca del incidente</span>
				</label>
				<textarea rows="3" name="notas" id="notas" readonly="true"><?= Trim($row["NOTAS"]) ?></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<label class="labelTitulo"  >Estado
					<span class="small">Etapa de la solicitud</span>
				</label>
				<input id="Estado" name="Estado" readonly="true" value="<?= $row["ESTADO"] ?>"></input>
			</td>
		</tr>
		<tr>
			<td>
				<label class="labelTitulo"  >Prioridad
					<span class="small">¿Qué tan urgente es?</span>
				</label>
				<input id="Prioridad" name="Prioridad" readonly="true" value="<?= $row["PRIORIDAD"] ?>"></input>
			</td>
		</tr>
		<tr>
			<td>
				<?
				if (($sistema <> 2) and ($sistema <> 4)) {
				echo " <label class='labelTitulo'  >PC<span class='small' >Estación de trabajo</span></label>
					   <input id=PC name=PC readonly=true value=".$row["PC"]."></input>";
				};

$whereExists = "  AND nvl(AS_TIPO, 'U') = 'U' ";
  
// Muestro los archivos adjuntos NO dados de baja...
//EL DIRECTORIO PARA DESARROLLO ES D: TENER EN CUENTA ESTO PUEDE CAMBIAR EN PRODUCCION
$sql =
	"SELECT LOWER(REPLACE(REPLACE(UPPER(as_rutaarchivo), UPPER(directory_path), UPPER('D:/STORAGE_INTRANET/')), '/', '\'))
		FROM computos.cas_adjuntosolicitud, sys.all_directories
	 WHERE directory_name = 'STORAGE_INTRANET'
		  AND as_fechabaja IS NULL
		  AND as_idsolicitud = :idsolicitud";
$sql .= $whereExists;
		  
$params = array(":idsolicitud" => $_REQUEST["id"]);
$stmt = DBExecSql($conn, $sql, $params);
$hayAdjuntos = (DBGetRecordCount($stmt) > 0);
if ($hayAdjuntos) {
	$links = "";
	while ($row2 = DBGetQuery($stmt, 0)) {
		//$filename = basename(htmlentities($row2[0]));
		$filename = basename($row2[0]);
		$links.='<li><a href="/archivo/'.base64_encode($row2[0]).'" target="_blank">'.$filename.'</li>';		
		//$links.='<li><a href="/archivo/'.trim($row2[0]).'" target="_blank">'.$filename.'</li>';		
		$links.="\n";
	}
?>
		</td>
	</tr>
	<tr>
		<td>
		<div><b><a name="titleAdjuntos">Adjuntos</a></b></div>
		<div style="text-align:left; margin:8px 0px 4px 35px;">
			<div><ol><?= $links ?></ol></div>
		</div>
	
<!--
		<table align="center" id="tableAdjuntos" style="margin-bottom:8px;" width="50%">
			<tr>
				<td><b><a name="titleAdjuntos">Adjuntos</a></b></td>
			</tr>
			<tr>
				<td align="left"><ol><?= $links ?></ol></td>
			
			</tr>
		</table>
-->				
<?
}
?>
			</td>
		</tr>
		<tr>
			<td>

			<div id="AreaBotones" style="text-align:center;"> 
			
<?php
$valoresInformacion = array(11);
$valoresCalificacion = array(5);
$valoresAutorizacion = array(2);

// Muestro el botón de calificar solo si corresponde...
if (in_array($row["SS_IDESTADOACTUAL"], $valoresInformacion) and ($usu_soli == GetUserID())) {
?>
	<button type="submit" class="GIBtnAction" onClick="submitFormTicket('&information=yes');">Añadir información</button>
<?
}

// Muestro el botón de calificar solo si corresponde...
if (in_array($row["SS_IDESTADOACTUAL"], $valoresCalificacion) and ($usu_soli == GetUserID())) {
?>
	<button type="submit" class="GIBtnAction" onClick="submitFormTicket('&qualification=yes');">Calificar ahora</button>
<?
}

// Muestro el botón de autorizar solo si corresponde...
else if (in_array($row["SS_IDESTADOACTUAL"], $valoresAutorizacion) and ($showAuthorizationButton)) {
?>
	<button type="button" class="GIBtnAction" onClick="submitFormTicket('&authorize=S');">Autorizar</button>	
	<button type="button" class="GIBtnAction" onClick="submitFormTicket('&authorize=N');">Rechazar</button>
<?
}

// Muestro el botón atrás sólo si me viene por parámetro...
else if ((isset($_REQUEST["back_button"])) and ($_REQUEST["back_button"] == "yes")) {
?>
	<button type="button" class="GIBtnAction" onClick="history.go(-1);">Atrás</button>
<?
}

// Muestro el botón cerrar sólo si me viene por parámetro...
else if ((isset($_REQUEST["close_button"])) and ($_REQUEST["close_button"] == "yes")) {
?>
	<button type="button" class="GIBtnAction" onClick="top.close();">Cerrar</button>
<?
}
?>

	<button type="button" class="GIBtnAction" id="btnLineaTiempo" onClick="verLineaTiempo(<? echo $sistema.','.$MNU.','.$row["NRO_TICKET"] ?>);">Seguimiento</button>
</div>				

				<div class="small" id="DivAreaMensajes"></div>
				<div class="spacer"></div>
			</td>
		</tr>
	</table>
				
		</div>		
	</div>
		
</form>
			
			
<script type="text/javascript"><?
if ($hayAdjuntos) {
?>
	if (document.getElementById('adjuntos'))
		document.getElementById('adjuntos').style.display = 'block';
	if (document.getElementById('tableAdjuntos'))
		document.getElementById('tableAdjuntos').style.border = 'solid 1px;';
<?
}
?></script>
	</body>
</html>