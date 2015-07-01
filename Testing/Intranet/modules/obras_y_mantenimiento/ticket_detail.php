<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


$sql = "SELECT ticket, usuario, fecha, tipo_motivo, detalle_motivo, notas, ss_idestadoactual, estado, pc,
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
          FROM (SELECT ss_id AS ticket, se_nombre AS usuario, TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') AS fecha,
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

$params = array(":id" => $_REQUEST["id"]);
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$usu_auth = $row["USU_AUTH"];
$usu_soli = $row["SS_IDUSUARIO_SOLICITUD"];

$jefe = ValorSQL("SELECT se_id
                    FROM art.use_usuarios 
                   WHERE se_usuario = (SELECT se_respondea
                                         FROM art.use_usuarios
                                        WHERE se_id = ".$usu_auth.")");
$showAuthorizationButton = (($usu_auth == GetUserID()) or ($jefe == GetUserID()));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
	<head>
		<title>Registración</title>
		<meta content="Mon, 06 Jan 1990 00:00:01 GMT" http-equiv="Expires" />
		<link href="/Styles/formstyle.css" rel="stylesheet" type="text/css"></link>
		<script type="text/javascript" src="/Js/functions.js"></script>
		<script type="text/javascript" src="/Js/validations.js"></script>
		<script type="text/javascript" src="/Js/ajax.js" charset="iso-8859-1"></script>
		<script type="text/javascript" src="/Js/ticket.js" charset="iso-8859-1"></script>

		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/Js/Calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/Js/Calendario/calendar.js"></script>
		<script type="text/javascript" src="/Js/Calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/Js/Calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
?>
	</head>
	<body>
		<div id="stylized" class="formGeneric" style="font-size:12px; width:500px;">
			<form id="formSolicitud" name="formSolicitud" method="post" action="index.php?id=<?=$_REQUEST["id"]?>">
				<b>Información del Ticket</b>
				<br />
				<p>Puede llamar al interno 2929 para obtener mas información.</p>

				<label>Referencia
					<span class="small">N° de Ticket</span>
				</label>
				<input id="Ticket" name="Ticket" readonly="true" value="<?= $row["TICKET"] ?>"></input>

				<span id="adjuntos" style="display:none;"><a href="#titleAdjuntos">Adjuntos</a></span>

				<label>Solicitante
					<span class="small">Por ejemplo: Juan Pérez</span>
				</label>
				<input id="UsuarioSolicitud" name="UsuarioSolicitud" readonly="true" value="<?= $row["USUARIO"] ?>"></input>

				<label>Solicitud
					<span class="small">Fecha de la solicitud</span>
				</label>
				<input id="TipoPedido" name="TipoPedido" readonly="true" value="<?= $row["FECHA"] ?>"></input>

				<label>Pedido
					<span class="small">Tipo de pedido</span>
				</label>
				<input id="TipoPedido" name="TipoPedido" readonly="true" value="<?= $row["TIPO_MOTIVO"] ?>"></input>

				<label>Detalle
					<span class="small">Detalle del pedido</span>
				</label>
				<input id="DetallePedido" name="DetallePedido" readonly="true" value="<?= $row["DETALLE_MOTIVO"] ?>"></input>

				<label>Descripción
					<span class="small">Acerca del incidente</span>
				</label>
				<textarea rows="3" name="notas" id="notas" readonly="true"><?= $row["NOTAS"] ?></textarea>

				<label>Estado
					<span class="small">Etapa de la solicitud</span>
				</label>
				<input id="Estado" name="Estado" readonly="true" value="<?= $row["ESTADO"] ?>"></input>

				<label>Prioridad
					<span class="small">¿Qué tan urgente es?</span>
				</label>
				<input id="Prioridad" name="Prioridad" readonly="true" value="<?= $row["PRIORIDAD"] ?>"></input>

				<label>PC
					<span class="small">Estación de trabajo</span>
				</label>
				<input id="PC" name="PC" readonly="true" value="<?= $row["PC"] ?>"></input>

<?
// Muestro los archivos adjuntos NO dados de baja...
$sql =
	"SELECT LOWER(REPLACE(REPLACE(UPPER(as_rutaarchivo), UPPER(directory_path), UPPER('F:/STORAGE_INTRANET/')), '/', '\'))
		FROM computos.cas_adjuntosolicitud, sys.all_directories
	 WHERE directory_name = 'STORAGE_INTRANET'
		  AND as_fechabaja IS NULL
		  AND as_idsolicitud = :idsolicitud";
$params = array(":idsolicitud" => $_REQUEST["id"]);
$stmt = DBExecSql($conn, $sql, $params);
$hayAdjuntos = (DBGetRecordCount($stmt) > 0);
if ($hayAdjuntos) {
	$links = "";
	while ($row2 = DBGetQuery($stmt, 0)) {
		$links.='<li><a href="/functions/get_file.php?fl='.base64_encode($row2[0]).'" target="blank">'.basename(htmlentities($row2[0])).'</li>';
		$links.="\n";
	}
?>
	<table align="center" id="tableAdjuntos" style="margin-bottom:8px;" width="50%">
		<tr>
			<td><b><a name="titleAdjuntos">Adjuntos</a></b></td>
		</tr>
		<tr>
			<td align="left"><ol><?= $links ?></ol></td>
		</tr>
	</table>
<?
}

$valoresInformacion = array(11);
$valoresCalificacion = array(5);
$valoresAutorizacion = array(2);

// Muestro el botón de calificar solo si corresponde...
if (in_array($row["SS_IDESTADOACTUAL"], $valoresInformacion) and ($usu_soli == GetUserID())) {
?>
	<button type="submit" class="btnAction" onClick="submitFormTicket('&information=yes');">Añadir información</button>
<?
}

// Muestro el botón de calificar solo si corresponde...
if (in_array($row["SS_IDESTADOACTUAL"], $valoresCalificacion) and ($usu_soli == GetUserID())) {
?>
	<button type="submit" class="btnAction" onClick="submitFormTicket('&qualification=yes');">Calificar ahora</button>
<?
}

// Muestro el botón de autorizar solo si corresponde...
else if (in_array($row["SS_IDESTADOACTUAL"], $valoresAutorizacion) and ($showAuthorizationButton)) {
?>
	<button type="button" class="btnAction" onClick="submitFormTicket('&authorize=S');">Autorizar</button>
	&nbsp;
	<button type="button" class="btnAction" onClick="submitFormTicket('&authorize=N');">Rechazar</button>
<?
}

// Muestro el botón atrás sólo si me viene por parámetro...
else if ((isset($_REQUEST["back_button"])) and ($_REQUEST["back_button"] == "yes")) {
?>
	<button type="button" class="btnAction" onClick="history.go(-1);">Atrás</button>
<?
}

// Muestro el botón cerrar sólo si me viene por parámetro...
else if ((isset($_REQUEST["close_button"])) and ($_REQUEST["close_button"] == "yes")) {
?>
	<button type="button" class="btnAction" onClick="top.close();">Cerrar</button>
<?
}
?>

				<div class="small" id="DivAreaMensajes"></div>
				<div class="spacer"></div>
			</form>
		</div>
<?
if ($hayAdjuntos) {
?>
<script>
	document.getElementById('adjuntos').style.display = 'block';
	document.getElementById('tableAdjuntos').style.border = 'solid 1px;';
</script>
<?
}
?>
	</body>
</html>