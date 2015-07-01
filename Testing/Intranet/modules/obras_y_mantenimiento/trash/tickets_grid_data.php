<?
  $where = "";
  if (isset($_REQUEST["ticket_detail"])) {
    $back_button = $_REQUEST["back_button"];
  } else {
    $back_button = "no";
  }

  if ($all_tickets != "yes")
    $where = " AND ss_idestadoactual in (1,2,3,4,5,9) ";
  else
    $where = " AND ss_idestadoactual not in (1,2,3,4,5,9) ";

  if ($pending_tickets == "yes")
    $where = " AND ss_idestadoactual = 5 ";

  $sql = "SELECT ss_id, ss_id as nro_ticket, TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') fecha_solicitud,
                 motivodetalle.ms_descripcion motivo, motivooriginal.ms_descripcion detalle,
                 es_descripcion estado, ss_fecha_carga
            FROM use_usuarios usuarios, computos.cse_sector sector, computos.cms_motivosolicitud motivooriginal,
                 computos.cms_motivosolicitud motivodetalle, computos.ces_estadosolicitud, 
                 computos.css_solicitudsistemas
           WHERE usuarios.se_idsector = sector.se_id
             AND usuarios.se_usuario = UPPER('".GetWindowsLoginName()."')
             AND ss_idmotivosolicitud = motivooriginal.ms_id
             AND motivooriginal.ms_idpadre = motivodetalle.ms_id
             AND ss_idestadoactual = es_id
             AND ss_idusuario_solicitud = usuarios.se_id ".$where."
       UNION ALL
          SELECT ss_id, ss_id as nro_ticket, TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') fecha_solicitud,
                 motivodetalle.ms_descripcion motivo, motivooriginal.ms_descripcion detalle,
                 es_descripcion estado, ss_fecha_carga
            FROM use_usuarios usuarios, computos.cse_sector sector, computos.cms_motivosolicitud motivooriginal,
                 computos.cms_motivosolicitud motivodetalle, computos.ces_estadosolicitud,
                 computos.css_solicitudsistemas
           WHERE usuarios.se_idsector = sector.se_id
             AND usuarios.se_respondea = UPPER('".GetWindowsLoginName()."')
             AND ss_idmotivosolicitud = motivooriginal.ms_id
             AND motivooriginal.ms_idpadre = motivodetalle.ms_id
             AND ss_idestadoactual = es_id
             AND ss_idusuario_solicitud = usuarios.se_id ".$where."
        ORDER BY ss_fecha_carga desc ";

  $grilla = new Grid(array("", "N° de Ticket", "Fecha", "Motivo", "Detalle", "Estado", ""),
                     array(8, 0, 0, 0, 0, 0, -1),
  									 array("btnTicket", "", "", "", "", "", ""),
                     array("index.php?ticket_detail=yes&amp;all_tickets=".$all_tickets."&amp;pending_tickets=".
                                                                          $pending_tickets."&amp;back_button=".
                                                                          $back_button, "", "", "", "", "", ""));
  $grilla->setColsSeparator(true);
  $grilla->setPageNumber($pagina);
  $grilla->setRowsSeparator(true);
  $grilla->setSql($sql);
  $grilla->Draw();
?>