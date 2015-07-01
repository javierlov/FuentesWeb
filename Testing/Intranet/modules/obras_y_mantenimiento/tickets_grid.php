<?
if (!isset($_REQUEST["firstcall"]))
	if ($showProcessMsg)
		FirstCallPageCode();

	$numeroTicket = "";
	if (isset($_REQUEST["numeroTicket"]))
		$numeroTicket = $_REQUEST["numeroTicket"];

	$where = "";
  if (isset($_REQUEST["ticket_detail"]))
    $back_button = $_REQUEST["back_button"];
	else
    $back_button = "yes";

  /* Para implementar el filtro de los empleados que dependen de uno mismo */
  if (isset($_REQUEST["employees"]))
    $employees = $_REQUEST["employees"];
  else
    $employees = "yes";

  if (isset($_REQUEST["close_button"]))
    $close_button = $_REQUEST["close_button"];
  else
    $close_button = "yes";
?>
<form action="/modules/obras_y_mantenimiento/index.php?search=yes&all_tickets=yes&firstcall=false" id="formTicket" method="post" name="formTicket" onSubmit="ValidarForm(formTicket)">
	<span style="left:-272px; position:relative;">
		<label class="FormLabelBlanco" for="numeroTicket">Nº Ticket</label>
		<input id="numeroTicket" name="numeroTicket" size="10" title="Nº Ticket" type="text" validarEntero="true" value="<?= $numeroTicket?>" style="background-color: #ccc; border: 1px solid #808080; color: #000; font-family: Neo Sans; font-size: 10pt; margin-right:8px;" />
		<input  id="btnBuscar" name="btnBuscar" type="submit" value="Buscar" style="background-color: #808080; border: 1px solid #333; color: #fff; cursor: pointer; font-family: Neo Sans; font-size: 8pt;" />
	</span>
</form>
<div style="margin-top:4px; position:relative;">
<?
/*
  $sql = "CREATE GLOBAL TEMPORARY TABLE
          grilla_solicitud_sistemas (ss_id NUMBER(8),
                                     nro_ticket NUMBER(8),
                                     fecha_solicitud DATE,
                                     motivo VARCHAR2(30),
                                     detalle VARCHAR2(30),
                                     estado VARCHAR2(30),
                                     se_nombre VARCHAR2(30),
                                     ss_fecha_carga DATE)
           ON COMMIT DELETE ROWS";
  DBExecSql($conn, $sql);
*/
  if ($all_tickets != "yes")
    $where = " AND ss_idestadoactual in (1,2,3,4,5,9,10,11) ";
  else
    $where = " AND ss_idestadoactual in (6,7,8) ";

  if ($pending_tickets == "yes") {
    $employees = "no";
    $where = " AND ss_idestadoactual = 5 ";
  }

  if ($pending_moreinfo_tickets == "yes")
    $where = " AND ss_idestadoactual = 11 ";

  if ($pending_auth_tickets == "yes")
    $where = " AND ss_idestadoactual = 2 ";

  if ($numeroTicket != "")
    $where = " AND ss_id = ".$numeroTicket;

  $sql = "SELECT /*+ INDEX(css_solicitudsistemas NDX_CSS_ESTADO)*/
                 ¿ss_id?, ss_id as ¿nro_ticket?, TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') ¿fecha_solicitud?,
                 motivodetalle.ms_descripcion ¿motivo?, motivooriginal.ms_descripcion ¿detalle?,
                 es_descripcion ¿estado?, ¿se_nombre?, ¿ss_fecha_carga?, ¿ss_notas?
            FROM use_usuarios usuarios, computos.cse_sector sector, computos.cms_motivosolicitud motivooriginal,
                 computos.cms_motivosolicitud motivodetalle, computos.ces_estadosolicitud,
                 computos.css_solicitudsistemas
           WHERE usuarios.se_idsector = sector.se_id(+)
             AND usuarios.se_usuario = UPPER('".GetWindowsLoginName()."')
             AND ss_idmotivosolicitud = motivooriginal.ms_id
             AND motivooriginal.ms_idpadre = motivodetalle.ms_id
             AND ss_idestadoactual = es_id
             AND ss_idusuario_solicitud = usuarios.se_id ".$where;

  if (($pending_auth_tickets == "yes") or ($employees == "yes")) {
    $sql = $sql.
      " UNION ALL
          SELECT /*+ INDEX(css_solicitudsistemas NDX_CSS_ESTADO)*/
                 ss_id, ss_id AS nro_ticket, TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') fecha_solicitud,
                 motivodetalle.ms_descripcion motivo, motivooriginal.ms_descripcion detalle, es_descripcion estado, se_nombre,
                 ss_fecha_carga, ss_notas
            FROM use_usuarios usuarios, computos.cse_sector sector, computos.cms_motivosolicitud motivooriginal,
                 computos.cms_motivosolicitud motivodetalle, computos.ces_estadosolicitud, computos.css_solicitudsistemas
           WHERE usuarios.se_idsector = sector.se_id(+)
             AND ss_idmotivosolicitud = motivooriginal.ms_id
             AND motivooriginal.ms_idpadre = motivodetalle.ms_id
             AND ss_idestadoactual = es_id
             AND (SELECT gerente.se_id
                    FROM art.use_usuarios gerente
                   WHERE gerente.se_usuario = (SELECT jefe.se_respondea
                                                 FROM art.use_usuarios jefe
                                                WHERE jefe.se_id = computos.general.get_usuarioresponsable(NVL((SELECT DECODE(hs_idestado, 2, hs_idusuario_cambio, ss_idusuario_solicitud)
                                                                                                                  FROM computos.chs_historicosolicitud chs1
                                                                                                                 WHERE chs1.hs_idsolicitud = ss_id
                                                                                                                   AND chs1.hs_fecha_cambio =
                                                                                                                         (SELECT MAX(chs2.hs_fecha_cambio)
                                                                                                                            FROM computos.chs_historicosolicitud chs2
                                                                                                                           WHERE chs1.hs_idsolicitud = chs2.hs_idsolicitud
                                                                                                                             AND chs2.hs_idusuario_cambio NOT IN(SELECT usuario.se_id
                                                                                                                                                                   FROM art.use_usuarios usuario
                                                                                                                                                                  WHERE usuario.se_sector = 'COMPUTOS')
                                                                                                                             AND chs2.hs_idestado = 2)),
                                                                                                               ss_idusuario_solicitud),
                                                                                                           motivooriginal.ms_nivel + 10))) = ".GetUserID()."
             AND ss_idusuario_solicitud = usuarios.se_id ".$where.
     " UNION ALL
      SELECT /*+ INDEX(css_solicitudsistemas NDX_CSS_ESTADO)*/
                 ss_id, ss_id AS nro_ticket, TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') fecha_solicitud,
                 motivodetalle.ms_descripcion motivo, motivooriginal.ms_descripcion detalle, es_descripcion estado, se_nombre,
                 ss_fecha_carga, ss_notas
            FROM use_usuarios usuarios, computos.cse_sector sector, computos.cms_motivosolicitud motivooriginal,
                 computos.cms_motivosolicitud motivodetalle, computos.ces_estadosolicitud, computos.css_solicitudsistemas
           WHERE usuarios.se_idsector = sector.se_id(+)
             AND ss_idmotivosolicitud = motivooriginal.ms_id
             AND motivooriginal.ms_idpadre = motivodetalle.ms_id
             AND ss_idestadoactual = es_id
             AND usuarios.se_id = ss_idusuario_solicitud
             AND EXISTS(SELECT 1
                          FROM computos.cps_permisosolicitud
                         WHERE ps_idsolicitud = ss_id
                           AND ps_fechaautorizacion IS NULL
                           AND ps_idusuario = ".GetUserID().")".$where.
     " UNION ALL
          SELECT /*+ INDEX(css_solicitudsistemas NDX_CSS_ESTADO)*/
                 ss_id, ss_id AS nro_ticket, TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') fecha_solicitud,
                 motivodetalle.ms_descripcion motivo, motivooriginal.ms_descripcion detalle, es_descripcion estado, se_nombre,
                 ss_fecha_carga, ss_notas
            FROM use_usuarios usuarios, computos.cse_sector sector, computos.cms_motivosolicitud motivooriginal,
                 computos.cms_motivosolicitud motivodetalle, computos.ces_estadosolicitud, computos.css_solicitudsistemas
           WHERE usuarios.se_idsector = sector.se_id(+)
             AND ss_idmotivosolicitud = motivooriginal.ms_id
             AND motivooriginal.ms_idpadre = motivodetalle.ms_id
             AND ss_idestadoactual = es_id
             AND NOT EXISTS(SELECT 1
                              FROM computos.cps_permisosolicitud
                             WHERE ps_idsolicitud = ss_id
                               AND ps_fechaautorizacion IS NULL)
             AND computos.general.get_usuarioresponsable(NVL((SELECT DECODE(hs_idestado, 2, hs_idusuario_cambio, ss_idusuario_solicitud)
                                                                FROM computos.chs_historicosolicitud chs1
                                                               WHERE chs1.hs_idsolicitud = ss_id
                                                                 AND chs1.hs_fecha_cambio =
                                                                       (SELECT MAX(chs2.hs_fecha_cambio)
                                                                          FROM computos.chs_historicosolicitud chs2
                                                                         WHERE chs1.hs_idsolicitud = chs2.hs_idsolicitud
                                                                           AND chs2.hs_idusuario_cambio NOT IN(SELECT usuario.se_id
                                                                                                                 FROM art.use_usuarios usuario
                                                                                                                WHERE usuario.se_sector = 'COMPUTOS')
                                                                           AND chs2.hs_idestado = 2)),
                                                             ss_idusuario_solicitud),
                                                         motivooriginal.ms_nivel + 10) = ".GetUserID()."
             AND ss_idusuario_solicitud = usuarios.se_id ".$where;
  }
  $sql = $sql." ORDER BY ss_fecha_carga desc ";

  set_time_limit(100);
  $grilla = new Grid(array("", "N° de Ticket", "Fecha", "Motivo", "Detalle", "Estado", "Pedido por", ""),
                     array(8, 0, 0, 0, 0, 0, 0, -1),
	             array("btnTicket", "", "", "", "", "", "", ""),
                     array("index.php?ticket_detail=yes&amp;all_tickets=".$all_tickets."&amp;pending_tickets=".
                                                                          $pending_tickets."&amp;back_button=".
                                                                          $back_button."&amp;close_button=".
                                                                          $close_button, "", "", "", "", "", "", ""));

	$grilla = new Grid();
	$grilla->addColumn(new Column("", 8, true, false, 9, "btnTicket", "index.php?ticket_detail=yes&amp;all_tickets=".$all_tickets."&amp;pending_tickets=".$pending_tickets."&amp;back_button=".$back_button."&amp;close_button=".$close_button, "GridFirstColumn"));
	$grilla->addColumn(new Column("N° de Ticket"));
	$grilla->addColumn(new Column("Fecha"));
	$grilla->addColumn(new Column("Motivo"));
	$grilla->addColumn(new Column("Detalle"));
	$grilla->addColumn(new Column("Estado"));
	$grilla->addColumn(new Column("Pedido por"));
	$grilla->addColumn(new Column("", 0, false));
	$grilla->addColumn(new Column("", 0, false));
	$grilla->setColsSeparator(true);
	$grilla->setPageNumber($pagina);
	$grilla->setRowsSeparator(true);
	$grilla->setSql($sql);
	$grilla->Draw(true, 1);
?>
</div>