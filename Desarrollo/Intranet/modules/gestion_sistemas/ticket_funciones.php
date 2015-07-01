<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid_columnAjax.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/gridAjax.php");
//require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/gridAjaxDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CommonFunctions.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");

if (isset($_REQUEST["funcion"])) {

    if ($_REQUEST["funcion"] == "TienePermisoTicket") {
        try {

            $usuario = GetParametro("usuario");
            $TicketDetalle = GetParametro("TicketDetalle");

            if (TienePermisoTicket($usuario, $TicketDetalle))
                echo "OK";
            else
                echo "CANCEL";

        } catch (Exception $e) {
            echo "<b>Fallo: </b>" . $e -> getMessage();
        }
    }

    if ($_REQUEST["funcion"] == "GrillaPedidos") {
        try {
            $all_tickets = GetParametro("all_tickets");
            $pending_tickets = GetParametro("pending_tickets");
            $pending_moreinfo_tickets = GetParametro("pending_moreinfo_tickets");
            $pending_auth_tickets = GetParametro("pending_auth_tickets");
            $numeroTicket = GetParametro("numeroTicket");
            $fechaDesde = GetParametro("fechaDesde");
            $fechaHasta = GetParametro("fechaHasta");
            $ss_notas = GetParametro("ss_notas");
            $PlanAccion = GetParametro("PlanAccion");
            $TipoPedido = GetParametro("TipoPedido");
            $DetallePedido = GetParametro("DetallePedido");
            $employees = GetParametro("employees");
            $sistema = GetParametro("sistema");
            $back_button = GetParametro("back_button");
            $close_button = GetParametro("close_button");
            $pagina = GetParametro("pagina");
            $MNUselect = GetParametro("MNUselect");
            $retorno = '';
            $urlencode = '';

            $grilla = GrillaPedidos($all_tickets, $pending_tickets, $pending_moreinfo_tickets, $pending_auth_tickets, $numeroTicket, $fechaDesde, $fechaHasta, $ss_notas, $PlanAccion, $TipoPedido, $DetallePedido, $employees, $sistema, $back_button, $close_button, $pagina, $MNUselect);

            if ($grilla -> recordCount() == 1) {
                //header('Location: '.$grilla->GetCurrentURL() );
                /*
                 $urlencode = urlencode($grilla->GetCurrentURL());

                 $urlencode = $grilla->GetCurrentURL();
                 $retorno = "<div style='display:block' id='urlredireccion' >".$urlencode."</div>";
                 echo $grilla->Draw();
                 */
                $retorno = $grilla -> Draw();
            } else {
                $retorno = $grilla -> Draw();
            }

            echo $retorno;

        } catch (Exception $e) {
            EscribirLogTxt1("GrillaPedidos Error", $e -> getMessage());
            echo "<b>Fallo: </b>" . $e -> getMessage();
        }
    }

    if ($_REQUEST["funcion"] == "DatosUsuarioGrid") {
        try {
            $UsuarioSolicitud = GetParametroDecode("UsuarioSolicitud");
            $sistema = GetParametroDecode("sistema");
            $motivos = GetParametroDecode("motivos");
            $pagina = GetParametroDecode("pagina");
            $ArraySeleccion = GetParametroDecode("ArraySeleccion");
            $idsolicitud = GetParametroDecode("idsolicitud");

            $grilla = DatosUsuarioGrid($UsuarioSolicitud, $sistema, $motivos, $idsolicitud, $pagina, $ArraySeleccion);
            echo $grilla -> Draw();

        } catch (Exception $e) {
            echo "<b>Fallo: </b>" . $e -> getMessage();
        }
    }

    if ($_REQUEST["funcion"] == "GrillaPermisosGenerales") {
        try {
            $UsuarioSolicitud = GetParametroDecode("UsuarioSolicitud");
            $sistema = GetParametroDecode("sistema");
            $pagina = GetParametroDecode("pagina");
            $idPadre = GetParametroDecode("idPadre");
            $idItem = GetParametroDecode("idItem");

            echo GetGrillaPermisosGenerales($UsuarioSolicitud, $sistema, $pagina, $idPadre, $idItem);

        } catch (Exception $e) {
            echo "<b>fallo: </b>" . $e -> getMessage();
        }
    }

    if ($_REQUEST["funcion"] == "ActualizarPermisos") {
        try {
            $USUARIOALTA = GetParametro('UsuarioAlta');
            $IDMOTIVOSOLICITUD = GetParametro('DetallePedido');
            $USUARIOSLISTA = GetParametro('USUARIOSLISTA');
            $USUARIOSLISTABAJA = GetParametro('USUARIOSLISTABAJA');

            if (TRIM($USUARIOSLISTA) > '') {
                $USUARIOSLISTAEXP = explode(",", $USUARIOSLISTA);
                foreach ($USUARIOSLISTAEXP as $USUARIO) {
                    AltaPermiso($USUARIOALTA, $IDMOTIVOSOLICITUD, $USUARIO);
                }
            }

            if (TRIM($USUARIOSLISTABAJA)) {
                $USUARIOSLISTABAJAEXP = explode(",", $USUARIOSLISTABAJA);
                foreach ($USUARIOSLISTABAJAEXP as $USUARIO) {
                    BajaPermiso($IDMOTIVOSOLICITUD, $USUARIO);
                }
            }

            return TRUE;

        } catch (Exception $e) {
            return FALSE;
        }
    }

    if ($_REQUEST["funcion"] == "EliminarPermisoGrupo") {
        try {

            $IDgroup = GetParametroDecode("IDgroup");

            if (EliminarPermisoGrupo($IDgroup))
                echo "OK";
            else
                echo "Error";

        } catch (Exception $e) {
            echo "<b>Error EliminarPermisoGrupo: </b>" . $e -> getMessage();
        }
    }
}

function GetUsuarioAplicacion() {
    $resultado = GetWindowsLoginName(TRUE);

    //$resultado = 'JBALESTRINI';
    //$resultado = 'SMILEO';
    //$resultado = 'DROVEGNO';
    //$resultado = 'LPIPARO';
    //$resultado = 'PATLANTE';
    //$resultado = 'PTAVASCI';
    //$resultado = 'NKUSTER';
    //$resultado = 'MMONTERO';
    //$resultado = 'JSEARA';
    //$resultado = 'EVILA';
    //$resultado = 'HARDITI';
	
	/* Comentar estas lineas si no se va a usar  el usuario sobreescrito */
	unset($_SESSION["FAKE_REMOTE_USER"]);
	//$_SESSION["FAKE_REMOTE_USER"] = $resultado;      		$resultado = $_SESSION["FAKE_REMOTE_USER"];
	 
    return strtoupper($resultado);
}

function EliminarPermisoGrupo($IDgroup) {
    try {

        global $conn;

        $sqlDelete = "DELETE computos.cmp_motivopermitidousuario  WHERE MP_ID in (" . $IDgroup . ")";
        $sqlDelete = str_replace("'", "", $sqlDelete);

        $params = array();

        @DBExecSql($conn, $sqlDelete, $params);
        DBCommit($conn);

        return TRUE;
    } catch (Exception $e) {
        DBRollback($conn);
        return FALSE;
    }
}

function GetParametroDecode($nombreparam, $default = '') {
    $valor = $default;
    if (isset($_REQUEST[$nombreparam]))
        $valor = $_REQUEST[$nombreparam];

    return html_entity_decode($valor);
    //return utf8_decode($valor);
}

function GetParametro($nombreparam, $default = '') {
    $valor = $default;
    if (isset($_REQUEST[$nombreparam]))
        $valor = $_REQUEST[$nombreparam];

    return $valor;
}

function AltaPermiso($USUALTA, $IDMOTIVOSOLICITUD, $USUARIO) {
    /*Se da de alta un permiso en la tabla computos.cmp_motivopermitidousuario
     Parametros:
     $USUALTA = debe ser el nombre de usuario (JLOVATTO)
     $IDMOTIVOSOLICITUD = id de soluciotud tabla computos.cms_motivosolicitud
     $USUARIO = debe ser el nombre de usuario (JLOVATTO)
     */	 
    try {

        global $conn;
		if($IDMOTIVOSOLICITUD == '') throw new Exception('Debe indicar el motivo.');

        $params = array(":IDMOTIVOSOLICITUD" => $IDMOTIVOSOLICITUD, ":USUARIO" => $USUARIO);
        $PermisoExistente = ValorSql("SELECT 1 FROM computos.cmp_motivopermitidousuario
						WHERE MP_IDMOTIVOSOLICITUD = :IDMOTIVOSOLICITUD
						AND MP_USUARIO = :USUARIO ", "", $params);

        if ($PermisoExistente > 0) {
            return TRUE;
        }

        $params = array();
        $nextID = ValorSql("SELECT NVL(max(MP_ID), 0)+1 FROM computos.cmp_motivopermitidousuario", "", $params);

        $sqlinsert = "INSERT INTO computos.cmp_motivopermitidousuario 
					(MP_ID,
					MP_USUALTA,
					MP_FECHAALTA,
					MP_IDMOTIVOSOLICITUD,
					MP_USUARIO)
				VALUES(:P_ID,
					UPPER(:P_USUALTA),
					SYSDATE, 
					:P_IDMOTIVO,
					UPPER(:P_USUARIO) ) ";

        $params = array(":P_ID" => $nextID, ":P_USUALTA" => $USUALTA, ":P_IDMOTIVO" => $IDMOTIVOSOLICITUD, ":P_USUARIO" => $USUARIO);

        DBExecSql($conn, $sqlinsert, $params);
        DBCommit($conn);

        return TRUE;
    } catch (Exception $e) {
		EscribirLogTxt1('AltaPermiso', $e->getMessage() );
		
        if( !is_null($conn) )DBRollback($conn);
        return FALSE;
    }
}

function BajaPermiso($IDMOTIVOSOLICITUD, $USUARIO) {
    /*Se da de alta un permiso en la tabla computos.cmp_motivopermitidousuario
     Parametros:
     $IDMOTIVOSOLICITUD = id de soluciotud tabla computos.cms_motivosolicitud
     $USUARIO = debe ser el nombre de usuario (JLOVATTO)
     */
    try {

        global $conn;

        $params = array(":IDMOTIVOSOLICITUD" => $IDMOTIVOSOLICITUD, ":USUARIO" => $USUARIO);
        $PermisoExistente = ValorSql("SELECT 1 FROM computos.cmp_motivopermitidousuario
						WHERE MP_IDMOTIVOSOLICITUD = :IDMOTIVOSOLICITUD
						AND MP_USUARIO = :USUARIO ", "", $params);

        if ($PermisoExistente == 0) {
            return TRUE;
        }

        $sqlDelete = "DELETE computos.cmp_motivopermitidousuario 
					WHERE	MP_IDMOTIVOSOLICITUD = :P_IDMOTIVO
					AND UPPER(MP_USUARIO) = UPPER(:P_USUARIO) ";

        $params = array(":P_IDMOTIVO" => $IDMOTIVOSOLICITUD, ":P_USUARIO" => $USUARIO);

        DBExecSql($conn, $sqlDelete, $params);
        DBCommit($conn);

        return TRUE;
    } catch (Exception $e) {
        DBRollback($conn);
        return FALSE;
    }
}

function GetDescripComputo($idsolicitud) {
    /*JLOVATTO 17-Marzo-2015
     Busca la descripcion en la tabla cms_motivosolicitud dado un id
     */
    try {
        $sql = "SELECT ms_descripcion 
				FROM   computos.cms_motivosolicitud 
				Where ms_id = :id";

        global $conn;

        $params = array(":id" => $idsolicitud);

        $descripcion = ValorSql($sql, "", $params);

        return $descripcion;

    } catch (Exception $e) {
        echo "<b>GetDescripComputo fallo: </b>" . $e -> getMessage();
    }
}

function GetGrillaPermisosGenerales($UsuarioSolicitud, $sistema, $pagina, $idPadre = 0, $idItem = 0) {
    try {
        $sql = "SELECT MOTIVOPADRE [MOTIVOPADRE], 
   				   MOTIVO [MOTIVO], 
				   LISTAGG(usuario, ', ' ) WITHIN GROUP (ORDER BY usuario) [USUARIOS],
				   '''' || listagg(se_usuario, ',' ) WITHIN GROUP (ORDER BY MP_ID) || ''',' || idmotivo || ',''' || idmotivopadre || ''',''' || idmotivo || '''' [MS_ID1],
				   '''' ||listagg(MP_ID, ',' ) WITHIN GROUP (ORDER BY MP_ID) || ''',''' || MOTIVOPADRE || ''',''' || MOTIVO || ''''  [MS_ID2]
    FROM   (SELECT   DISTINCT ms1.ms_descripcion motivo,
                              ms2.ms_descripcion motivopadre,
                              mp_id,
                              ms1.ms_id idmotivo,
                              ms2.ms_id idmotivopadre,
                              users.se_usuario,
                              users.se_nombre usuario
              FROM   computos.cms_motivosolicitud ms1,
                     computos.cms_motivosolicitud ms2,
                     computos.cmp_motivopermitidousuario,
                     art.use_usuarios users,
                     (    SELECT   se_usuario, se_sector, LEVEL
                            FROM   art.use_usuarios
                           WHERE   se_fechabaja IS NULL
                      --                         AND se_usuario <> :usuario
                      START WITH   se_usuario = UPPER (:usuario)
                      CONNECT BY   PRIOR se_usuario = se_respondea
                                   AND se_usuario <> se_respondea) usuarios
             WHERE       mp_idmotivosolicitud = ms1.ms_id
                     AND ms1.ms_idpadre = ms2.ms_id
                     AND mp_usuario = users.se_usuario(+)
                     AND users.se_fechabaja(+) IS NULL
                     AND usuarios.se_usuario = mp_usualta ";

        if ($idPadre > 0)
            $sql .= " AND MS2.ms_id = " . $idPadre . " ";

        if ($idItem > 0)
            $sql .= " AND MS1.ms_id = " . $idItem . " ";

        $sql .= ")	GROUP BY   motivo, motivopadre, idmotivo, idmotivopadre ";
        $sql .= "ORDER BY motivopadre, motivo  ";
		$sql = ReemplazaCorchetesQRY($sql);

        $MotivoSolicitud = GetParametro("DetallePedido");
        $params = array(":usuario" => $UsuarioSolicitud);
        
        return GetGrillaPermisos($sql, $params, $UsuarioSolicitud, $sistema, $pagina);

    } catch (Exception $e) {
        echo "<b>GetGrillaPermisosGenerales fallo: </b>" . $e -> getMessage();
    }
}

function GetGrillaPermisos($sql, $params, $UsuarioSolicitud, $sistema, $paginaN) {

    $HTMLgrilla = "<div id='divprincipal' style='margin:10px auto;'>";

    set_time_limit(100);
    $RegistrosPPag = 10;
    $RegistrosPBloque = 10;

    $grilla = new gridAjax($RegistrosPBloque, $RegistrosPPag);
    /*	(
     $title,
     $width = 0,
     $visible = true,
     $deletedRow = false,
     $colHint = -1,
     $buttonClass = "",
     $actionButton = "",
     $cellClass = "",
     $maxChars = -1,
     $useStyleForTitle = true,
     $numCellHide = -1,
     $titleHint = "",
     $mostrarEspera = false,
     $msgEspera = "",
     $inputType = "button",
     $colChecked = -1,
     $colButtonClass = -1)
     */
    $grilla -> addColumn(new columnAjax("PEDIDO", 50, TRUE, FALSE, 1, "", "", "", -1));
    $grilla -> addColumn(new columnAjax("DETALLE", 50, TRUE, FALSE, 1, "", "", "", -1));
    $grilla -> addColumn(new columnAjax("USUARIOS", 0, TRUE, FALSE, 1, "", "", "", -1));

    $ajaxColumnEdi = new columnAjax("", 0, TRUE, FALSE, 1, "btnEditar", "", "", -1, TRUE, -1, "Editar");

    $ajaxColumnEdi -> setFunctionAjax("EventEditaPermiso");
    $ajaxColumnEdi -> SetUseIdPageinName(TRUE);
    $grilla -> addColumn($ajaxColumnEdi);

    $ajaxColumnDel = new columnAjax("", 0, TRUE, FALSE, 1, "btnEliminar", "", "", -1, TRUE, -1, "Eliminar");
    $ajaxColumnDel -> setFunctionAjax("EventEliminarPermisoGrupo");
    $ajaxColumnDel -> SetUseIdPageinName(TRUE);
    $grilla -> addColumn($ajaxColumnDel);

    $pagina = 1;
    if (isset($paginaN))
        $pagina = $paginaN;

    if (isset($_REQUEST["pagina"])) {
        $pagina = $_REQUEST["pagina"];

        $rtotal = $grilla -> GetRecordTotal($sql, $params);
        $rceil = ceil($rtotal / $RegistrosPPag);

        if ($pagina > $rceil)
            $pagina = $rceil;
    }

    $grilla -> setPageNumber($pagina);
    $grilla -> setRowsSeparator(FALSE);

    $grilla -> setDecodeSpecialChars(TRUE);

    $grilla -> setUnderlineSelectedRow(TRUE);
    $grilla -> setRefreshIntoWindow(TRUE);
    $grilla -> setColsSeparator(TRUE);
    $grilla -> setRowsSeparatorColor("#c0c0c0");
    $grilla -> setShowTotalRegistros(TRUE);
    $grilla -> setShowProcessMessage(TRUE);

    $grilla -> setUseTmpIframe(FALSE);
    $grilla -> setTableStyle("gridTableAjxPermisos");

    $grilla -> setRow1Style(" GridRowAjx1Btn ");
    $grilla -> setRow2Style(" GridRowAjx2Btn ");
    $grilla -> setStyleunderlineSelectedRow(" GridRowAjx2Fondo ");
    $grilla -> SetFooterSelected(" GridFooterFontSelectedAjx ");
    $grilla -> SetStyleCellText(" GridCellTextAjx ");

    $grilla -> setParams($params);
    $grilla -> setSql($sql);

    $grilla -> setFuncionAjaxJS("BuscaColaboradores");
    $HTMLgrilla .= $grilla -> Draw(FALSE);
    $HTMLgrilla .= "</div>";

    return $HTMLgrilla;
}

////----------------------------------------------------------------------------
function GetSqlUsuarioGrid($idsolicitud, $motivos) {

    $sql = "	   
				SELECT   SE_NOMBRE || NVL2(MP_USUALTA,   ' [ ' || MP_USUALTA || ' ]', '' ) ¿NOMBRE?,
				SE_DESCRIPCION ¿SECTOR?,
						 NVL (MP_ID, 0) ¿ACTIVO?,			 
						 SE_USUARIO ¿USUARIO?
				  FROM   computos.cse_sector cse,
						 art.use_usuarios us1,
						 computos.cmp_motivopermitidousuario mp1
				 WHERE       us1.se_fechabaja IS NULL
						 AND cse.se_id = us1.se_idsector
						 AND se_usuario = mp_usuario(+)
						 AND mp_idmotivosolicitud(+) = ".intval($idsolicitud)." 
						 AND se_usuario <> UPPER (:usuario)
			START WITH   se_usuario = UPPER (:usuario)
			CONNECT BY   PRIOR se_usuario = se_respondea AND se_usuario <> se_respondea
						 AND NOT EXISTS
								(    SELECT   1
									   FROM   computos.cmp_motivopermitidousuario mp2,
											  art.use_usuarios us2
									  WHERE       mp2.mp_id = mp1.mp_id
											  AND us2.se_usuario = mp2.mp_usualta
											  AND us2.se_usuario <> UPPER (:usuario)
								 START WITH   se_usuario = UPPER(:usuario)
								 CONNECT BY   PRIOR se_respondea = se_usuario
											  AND se_usuario <> se_respondea)
			ORDER BY se_descripcion, se_nombre ";
			
	//$sql = ReemplazaCarateres($sql, '<','¿');
	//$sql = ReemplazaCarateres($sql, '>','?');
	
    return $sql;
}

function GetDatosUsuarioGrid($params, $idsolicitud = 0, $motivos = '') {
    try {
        global $conn;
        $sqlFinal = GetSqlUsuarioGrid($idsolicitud, $motivos);
        $sqlFinal = str_replace("?", "", str_replace("¿", "", $sqlFinal));

        $stmt = DBExecSql($conn, $sqlFinal, $params);

        $registros = '';

        while ($row = DBGetQuery($stmt, 0)) {
            $registros[] = $row;
        }
        return $registros;

    } catch (Exception $e) {
        echo "<b>GetDatosUsuarioGrid FALLO: </b>" . $e -> getMessage();
    }
}

function DatosUsuarioGrid($UsuarioSolicitud, $sistema, $motivos, $idsolicitud, $pagina, $ArraySeleccion) {
    try {
			
        $sql = GetSqlUsuarioGrid($idsolicitud, $motivos, $idsolicitud);

        $params = array(":usuario" => $UsuarioSolicitud);

        $grilla = GetGrillaUsuariosPermisos($sql, $params, $sistema, $pagina, $ArraySeleccion);
        return $grilla;

    } catch (Exception $e) {
        echo "<b>DatosUsuarioGrid fallo: </b>" . $e -> getMessage();
    }
}

function GetGrillaUsuariosPermisos($sql, $params, $sistema, $paginaN = 1, $ArraySeleccion) {

    $HTMLgrilla = '';
    set_time_limit(100);

    $RegistrosPPag = 14;
    $RegistrosPBloque = 5;

    $grilla = new gridAjax($RegistrosPBloque, $RegistrosPPag, $ArraySeleccion);

    $grilla -> addColumn(new columnAjax("NOMBRE", '50%', TRUE, FALSE, 1, "", "", "", -1));
    $grilla -> addColumn(new columnAjax("SECTOR", '35%', TRUE, FALSE, 1, "", "", "", -1));

    $ajaxColumnDel = new columnAjax("ACTIVO", "15%", TRUE, FALSE, 1, "checkbox", "", "", -1, TRUE, -1, "Eliminar", FALSE, "", "checkbox", 0, -1);

    $grilla -> addColumn($ajaxColumnDel);
    $grilla -> addColumn(new columnAjax("USUARIO", 0, FALSE, FALSE, 1, "", "", "", -1));

    $pagina = 1;
    if (isset($paginaN))
        $pagina = $paginaN;

    if (isset($_REQUEST["pagina"])) {
        $pagina = $_REQUEST["pagina"];
    }

    $grilla -> setPageNumber($pagina);
    $grilla -> setRowsSeparator(FALSE);

    $grilla -> setDecodeSpecialChars(TRUE);

    $grilla -> setUnderlineSelectedRow(TRUE);
    $grilla -> setRefreshIntoWindow(TRUE);
    $grilla -> setColsSeparator(TRUE);
    $grilla -> setRowsSeparatorColor("#c0c0c0");
    $grilla -> setShowTotalRegistros(TRUE);
    $grilla -> setShowProcessMessage(TRUE);

    $grilla -> setUseTmpIframe(FALSE);

    $grilla -> setTableStyle(" gridTableAjxPermisos ");
    $grilla -> setRow1Style(" GridRowAjx1Btn ");
    $grilla -> setRow2Style(" GridRowAjx2Btn ");
    $grilla -> setStyleunderlineSelectedRow(" GridRowAjx2Fondo ");
    $grilla -> SetFooterSelected(" GridFooterFontSelectedAjx ");

    $grilla -> setParams($params);
    $grilla -> setSql($sql);

    $grilla -> setFuncionAjaxJS("BuscaPermisoUsuarios");

    return $grilla;
}

function GrillaPedidos($all_tickets, $pending_tickets, $pending_moreinfo_tickets, $pending_auth_tickets, $numeroTicket, $fechaDesde, $fechaHasta, $ss_notas, $PlanAccion, $TipoPedido, $DetallePedido, $employees, $sistema, $back_button, $close_button, $pagina, $MNUselect) {

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
        $where = " AND ss_nro_ticket = " . $numeroTicket;
    //----------------------NUEVOS-FILTROS---------------------------------------------------------------
    if ($pending_tickets == "no") {
        if ($fechaDesde != "" and $fechaHasta != "") {

            //$fechaDesde = date("d-m-Y", strtotime($fechaDesde));
            //$fechaHasta = date("d-m-Y", strtotime($fechaHasta));

            $where .= " AND ss_fecha_solicitud >= TO_DATE('" . $fechaDesde . "', 'DD/MM/YYYY')  ";
            $where .= " AND ss_fecha_solicitud <= TO_DATE('" . $fechaHasta . "', 'DD/MM/YYYY')  ";
        }

        if (trim($ss_notas) != '')
            $where .= " AND UPPER(ss_notas) like UPPER('%" . trim($ss_notas) . "%') ";
        if (trim($PlanAccion) != '')
            $where .= $PlanAccion;
        if (intval($TipoPedido) > 0)
            $where .= " AND motivodetalle.ms_id = " . $TipoPedido . " ";
        if (intval($DetallePedido) > 0)
            $where .= " AND motivooriginal.ms_id = " . $DetallePedido . " ";

    }
    //--------------------------------------------------------------------------------------

    $where = $where . " AND ss_idsistematicket = " . $sistema;
    /*GetWindowsLoginName = GetUsuarioAplicacion */
    $sql = "SELECT /*+ INDEX(css_solicitudsistemas NDX_CSS_GRILLAWEB)*/
					 [ss_id], ss_nro_ticket as [nro_ticket], TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') [fecha_solicitud],
					 motivodetalle.ms_descripcion [motivo], motivooriginal.ms_descripcion [detalle],
					 es_descripcion [estado], [se_nombre], [ss_fecha_carga], [ss_notas]
				FROM art.use_usuarios usuarios, computos.cse_sector sector, computos.cms_motivosolicitud motivooriginal,
					 computos.cms_motivosolicitud motivodetalle, computos.ces_estadosolicitud,
					 computos.css_solicitudsistemas
			   WHERE usuarios.se_idsector = sector.se_id(+)
				 AND usuarios.se_usuario = UPPER('" . GetUsuarioAplicacion() . "')
				 AND ss_idmotivosolicitud = motivooriginal.ms_id
				 AND motivooriginal.ms_idpadre = motivodetalle.ms_id
				 AND ss_idestadoactual = es_id
				 AND ss_idusuario_solicitud = usuarios.se_id " . $where;

    if (($pending_auth_tickets == "yes") or ($employees == "yes")) {
        $sql = $sql . " UNION ALL
          SELECT /*+ INDEX(css_solicitudsistemas NDX_CSS_GRILLAWEB)*/
                 ss_id, ss_nro_ticket AS nro_ticket, TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') fecha_solicitud,
                 motivodetalle.ms_descripcion motivo, motivooriginal.ms_descripcion detalle, es_descripcion estado, se_nombre,
                 ss_fecha_carga, ss_notas
            FROM art.use_usuarios usuarios, computos.cse_sector sector, computos.cms_motivosolicitud motivooriginal,
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
                                                                                                           motivooriginal.ms_nivel + 10))) = " . GetUserID() . "
             AND ss_idusuario_solicitud = usuarios.se_id " . $where . "
             AND ss_fecha_carga > TRUNC(SYSDATE) - 365
       UNION
      SELECT /*+ INDEX(css_solicitudsistemas NDX_CSS_GRILLAWEB)*/
                 ss_id, ss_nro_ticket AS nro_ticket, TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') fecha_solicitud,
                 motivodetalle.ms_descripcion motivo, motivooriginal.ms_descripcion detalle, es_descripcion estado, se_nombre,
                 ss_fecha_carga, ss_notas
            FROM art.use_usuarios usuarios, computos.cse_sector sector, computos.cms_motivosolicitud motivooriginal,
                 computos.cms_motivosolicitud motivodetalle, computos.ces_estadosolicitud, computos.css_solicitudsistemas
           WHERE art.usuarios.se_idsector = sector.se_id(+)
             AND ss_idmotivosolicitud = motivooriginal.ms_id
             AND motivooriginal.ms_idpadre = motivodetalle.ms_id
             AND ss_idestadoactual = es_id
             AND usuarios.se_id = ss_idusuario_solicitud
             AND EXISTS(SELECT 1
                          FROM computos.cps_permisosolicitud
                         WHERE ps_idsolicitud = ss_id
                           AND ps_fechaautorizacion IS NULL
                           AND ps_idusuario = " . GetUserID() . ")" . $where . " UNION 
          SELECT /*+ INDEX(css_solicitudsistemas NDX_CSS_GRILLAWEB)*/
                 ss_id, ss_nro_ticket AS nro_ticket, TO_CHAR(ss_fecha_solicitud, 'DD/MM/YYYY') fecha_solicitud,
                 motivodetalle.ms_descripcion motivo, motivooriginal.ms_descripcion detalle, es_descripcion estado, se_nombre,
                 ss_fecha_carga, ss_notas
            FROM art.use_usuarios usuarios, computos.cse_sector sector, computos.cms_motivosolicitud motivooriginal,
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
                                                         motivooriginal.ms_nivel + 10) = " . GetUserID() . "
             AND ss_fecha_carga > TRUNC(SYSDATE) - 365
             AND ss_idusuario_solicitud = usuarios.se_id " . $where;
    }
    $sql = $sql . " ORDER BY ss_fecha_carga desc ";
	$sql = ReemplazaCorchetesQRY($sql);

    set_time_limit(100);

    $grilla = new gridAjax();
    $psistema = $sistema;
    $urlIndex = "index.php?sistema=" . $psistema . "&MNU=" . $MNUselect . "&ticket_detail=yes&amp;all_tickets=" . $all_tickets . "&amp;pending_tickets=" . $pending_tickets . "&amp;back_button=" . $back_button . "&amp;close_button=" . $close_button;

    $grilla -> addColumn(new columnAjax("", 8, TRUE, FALSE, 9, "btnTicket", $urlIndex, "gridFirstColumn"));

    $grilla -> addColumn(new columnAjax("Num. de Ticket"));
    $grilla -> addColumn(new columnAjax("Fecha"));
    $grilla -> addColumn(new columnAjax("Motivo"));
    $grilla -> addColumn(new columnAjax("Detalle"));
    $grilla -> addColumn(new columnAjax("Estado"));
    $grilla -> addColumn(new columnAjax("Pedido por"));
    $grilla -> addColumn(new columnAjax("", 0, FALSE));
    $grilla -> addColumn(new columnAjax("", 0, FALSE));

    $grilla -> setColsSeparator(TRUE);
    $grilla -> setPageNumber($pagina);
    $grilla -> setRowsSeparator(TRUE);

    $grilla -> SetFooterSelected('GIgridFooterFontSelected');
    $grilla -> setShowTotalRegistros(TRUE);

    $grilla -> setSql($sql);
    //$grilla->SetCurrentURL($urlIndex.'&id='.$grilla->GetLastID() );
    $grilla -> SetCurrentURL($urlIndex . '&id=227');

    $grilla -> setFuncionAjaxJS("BuscarPedidos");

    return $grilla;
}

function TienePersonalACargo($usuario) {
    try {
        global $conn;

        $sql = "SELECT 1
				  FROM art.use_usuarios
				WHERE se_respondea = :usuario
				   AND se_fechabaja IS NULL";

        $params = array(":usuario" => $usuario);
        $TienePersonal = ValorSql($sql, "", $params);
        DBCommit($conn);

        if ($TienePersonal > 0)
            return TRUE;

        return FALSE;

    } catch (Exception $e) {
        DBRollback($conn);
        return FALSE;
    }
}

function PrintComboTipoPedido($FormName, $style = '') {
    $printResult = "<select ";
    $printResult .= " class='GICombo' ";
    $printResult .= " style='" . $style . "' ";
    $printResult .= " id='TipoPedido' ";
    $printResult .= " name='TipoPedido' ";
    $printResult .= " onchange=\"AjaxRequest('DivDetallePedido', 'ajax_detalle_motivos.php', document." . $FormName . ".TipoPedido.options[document." . $FormName . ".TipoPedido.selectedIndex].value, '', '', '" . $style . "'); \" >";
    $printResult .= "</select>";

    return $printResult;
}

function PrintComboDetallePedido($style = '') {
    $printResult = "<div id='DivDetallePedido'> " . "<select " . " class='GICombo'  " . " style='" . $style . "' " . " id='DetallePedido'  " . " name='DetallePedido'  " . " onchange=\"CambioDetallePedido();\"  >" . "</select> " . " </div> ";

    return $printResult;
}

//-------------------- Linea del Tiempo ----------------------
function GetDatosLineaTiempo($idReferencia) {
    try {
        global $conn;

        $sql = "  SELECT DISTINCT
			 paso_actual.num AS paso,
			 UPPER(CASE
			   WHEN paso_actual.num = 1 THEN
				 'Solicitud de ticket'
				 || CASE
					  WHEN paso_actual.usuario <> paso_actual.usuario_solicitud THEN
						' en nombre de ' || paso_actual.usuario_solicitud
					  ELSE
						''
					END
			   WHEN paso_actual.hs_idestado = 10
				AND paso_anterior.hs_idestado = 2 THEN
				 'Autorizado por ' || paso_actual.usuario
			   WHEN paso_actual.hs_idestado = 11 THEN
				 'Pedido de información de ' || paso_actual.usuario
			   WHEN paso_actual.hs_idestado = 3
				AND paso_anterior.hs_idestado IN (1, 3, 10)
				AND paso_actual.usuario <> paso_anterior.usuario THEN
				 'Asignado por ' || paso_actual.usuario || ' a ' || paso_actual.usuario_resolucion
			   WHEN paso_anterior.hs_idestado = 11
				AND paso_actual.hs_idestado <> 11 THEN
				 'Información añadida por ' || paso_actual.usuario
			   ELSE
				 paso_actual.estado
			 END)
			   AS situacion,
			 TO_CHAR(paso_actual.hs_fecha_cambio,  'DD/MM/YYYY' ) dia,
			 TO_CHAR(paso_actual.hs_fecha_cambio,  'HH24:MI' ) hora,
			 CASE WHEN paso_actual.num = 1 THEN paso_actual.hs_notas ELSE paso_actual.hs_observaciones END AS texto,
			 paso_actual.usuario AS usuario
		FROM (SELECT ROWNUM AS num,
					 paso_actual.*
				FROM (  SELECT chs.hs_idestado,
							   chs.hs_idsolicitud,
							   chs.hs_notas,
							   chs.hs_observaciones,
							   chs.hs_fecha_cambio,
							   es_descripcion AS estado,
							   usu_cambio.se_nombre usuario,
							   usuario.se_nombre usuario_resolucion,
							   usu_solicitud.se_nombre usuario_solicitud
						  FROM computos.chs_historicosolicitud chs,
							  art.use_usuarios usu_cambio,
							   art.use_usuarios usu_solicitud,
							   computos.ces_estadosolicitud,
							   art.use_usuarios usuario
						 WHERE chs.hs_idusuario_cambio = usu_cambio.se_id
						   AND chs.hs_idusuario = usuario.se_id(+)
						   AND chs.hs_idusuario_solicitud = usu_solicitud.se_id(+)
						   AND chs.hs_idsolicitud = :idticket
						   AND chs.hs_idestado = es_id
					  ORDER BY chs.hs_fecha_cambio) paso_actual) paso_actual,
			 (SELECT ROWNUM AS num,
					 paso_anterior.*
				FROM (  SELECT chs.hs_idestado,
							   chs.hs_idsolicitud,
							   chs.hs_notas,
							   chs.hs_observaciones,
							   chs.hs_fecha_cambio,
							   es_descripcion AS estado,
							   usu_cambio.se_nombre usuario,
							   usuario.se_nombre usuario_resolucion,
							   usu_solicitud.se_nombre usuario_solicitud
						  FROM computos.chs_historicosolicitud chs,
							   art.use_usuarios usu_cambio,
							   art.use_usuarios usu_solicitud,
							   computos.ces_estadosolicitud,
							   art.use_usuarios usuario
						 WHERE chs.hs_idusuario_cambio = usu_cambio.se_id
						   AND chs.hs_idusuario = usuario.se_id(+)
						   AND chs.hs_idusuario_solicitud = usu_solicitud.se_id(+)
						   AND chs.hs_idsolicitud = :idticket
						   AND chs.hs_idestado = es_id
					  ORDER BY chs.hs_fecha_cambio) paso_anterior) paso_anterior
	   WHERE (paso_actual.hs_idsolicitud = paso_anterior.hs_idsolicitud
		  AND paso_actual.num = paso_anterior.num + 1)
		  OR (paso_actual.num = 1
		  AND paso_anterior.num = 1)
	   ORDER BY paso_actual.num";

        $params = array(":idticket" => $idReferencia);
        $stmt = DBExecSql($conn, $sql, $params);

        $listaitems = GetItemLineaTiempo($stmt);

        DBCommit($conn);

        return $listaitems;
    } catch (Exception $e) {
        DBRollback($conn);
        return '';
    }
}

function GetItemLineaTiempo($stmt) {
    try {
        global $conn;
        $itemlista = '';

        while ($row = DBGetQuery($stmt, 1, FALSE)) {

            $itemlista .= "<li>";
            $fechaitem = $row['DIA'];
            $horaitem = $row['HORA'];
            $itemlista .= "<time class='cbp_tmtime' datetime='" . $row['DIA'] . "'><span>" . $fechaitem . "</span> <span>" . $horaitem . "</span></time>";
            $itemlista .= "<div class='cbp_tmicon cbp_tmicon-mail'></div>";
            $itemlista .= "<div class='cbp_tmlabel'>";
            $itemlista .= "<h2 class='containerLineaTiempo' >" . $row['SITUACION'] . "</h2>";
            $itemlista .= "<p> - " . $row['USUARIO'] . "</p>";
            $itemlista .= "<p>" . procesarCodigo($row['TEXTO']) . "</p>";
            $itemlista .= "</div>";
            $itemlista .= "</li>";

        }

        return $itemlista;

    } catch (Exception $e) {
        echo "<b>GetItemLineaTiempo FALLO: </b>" . $e -> getMessage();
        return '';
    }
}

function procesarCodigo($texto) {
    $codehtml = array("<", ">", "/>");
    $codetxt = array("&lt", "&gt", "&gt");

    $newtexto = str_replace($codehtml, $codetxt, $texto);

    return $newtexto;
}

function TienePermisoTicket($usuario, $TicketDetalle) {
    /* Función: Tiene permiso para cargar TICKET de TAL MOTIVO
     Mensaje = Usted no tiene permiso para generar un ticket con el motivo %, consulte con su responsable.
     */
    try {
        global $conn;

        $sql = "SELECT   1
				  FROM   computos.cms_motivosolicitud ms1
				 WHERE   ms1.ms_id = :TicketDetalle
					 AND NOT EXISTS (SELECT   1
									   FROM   computos.cmp_motivopermitidousuario mp1
									  WHERE   ms1.ms_id = mp1.mp_idmotivosolicitud
										  AND mp1.mp_usualta IN (    SELECT   se_usuario
																	   FROM   art.use_usuarios
																	  WHERE   se_fechabaja IS NULL
																		  AND se_usuario <> UPPER (:usuario)
																 START WITH   se_usuario = UPPER (:usuario)
																 CONNECT BY   PRIOR se_respondea = se_usuario
																		  AND se_usuario <> se_respondea))
					 AND EXISTS (SELECT   1
								   FROM   art.use_usuarios
								  WHERE   se_fechabaja IS NULL
									  AND se_usuario = UPPER (:usuario))
				UNION
				SELECT   1
				  FROM   computos.cms_motivosolicitud ms1
				 WHERE   ms1.ms_id = :TicketDetalle
					 AND EXISTS (SELECT   1
								   FROM   computos.cmp_motivopermitidousuario mp1
								  WHERE   ms1.ms_id = mp1.mp_idmotivosolicitud
									  AND mp1.mp_usualta IN (    SELECT   se_usuario
																   FROM   art.use_usuarios
																  WHERE   se_fechabaja IS NULL
																	  AND se_usuario <> UPPER (:usuario)
															 START WITH   se_usuario = UPPER (:usuario)
															 CONNECT BY   PRIOR se_respondea = se_usuario
																	  AND se_usuario <> se_respondea))
					 AND EXISTS (SELECT   1
								   FROM   computos.cmp_motivopermitidousuario mp1
								  WHERE   ms1.ms_id = mp1.mp_idmotivosolicitud
									  AND mp1.mp_usuario = UPPER (:usuario)
									  AND mp1.mp_usualta IN (    SELECT   se_usuario
																   FROM   art.use_usuarios
																  WHERE   se_fechabaja IS NULL
																	  AND se_usuario <> UPPER (:usuario)
															 START WITH   se_usuario = UPPER (:usuario)
															 CONNECT BY   PRIOR se_respondea = se_usuario
																	  AND se_usuario <> se_respondea)) ";

        $params = array(":usuario" => $usuario, ":TicketDetalle" => $TicketDetalle);
        $TienePermiso = ValorSql($sql, "", $params);
        DBCommit($conn);

        if ($TienePermiso > 0) {
            return TRUE;
        } else {
            return FALSE;
        }

    } catch (Exception $e) {
        DBRollback($conn);
        EscribirLogTxt1("Error: TienePermiso", $e -> getMessage());
        return FALSE;
    }
}

function GetConfigSistema($idSistema = 1) {
    unset($_SESSION['CONFIGTICKET']);
	
	if (!isset($_SESSION['CONFIGTICKET'][$idSistema])) {
        $_SESSION['CONFIGTICKET'][$idSistema] = GetConfigSistemaDB($idSistema);
    }

    return $_SESSION['CONFIGTICKET'][$idSistema];
}

function GetConfigSistemaDB($idSistema = 1) {
    try {
        global $conn;
        $sqlConfig = "SELECT  ST_INTERNO,
						 ST_COLOR,
						 ST_HEADER,
						 ST_SUBHEADER_NEW,
						 ST_SUBHEADER_AUTH,
						 ST_SUBHEADER_QUALI,
						 ST_SUBHEADER_INFO,
						 ST_HOME
				  FROM   computos.cst_sistematicket
				 WHERE   st_id = :idSistema";

        $params = array(":idSistema" => $idSistema);
        $stmt = DBExecSql($conn, $sqlConfig, $params);
        $registros = '';

        $row = DBGetQuery($stmt, 0);

        $resultado['ST_INTERNO'] = $row[0];
        $resultado['ST_COLOR'] = $row[1];
        $resultado['ST_HEADER'] = $row[2];
        $resultado['ST_SUBHEADER_NEW'] = $row[3];
        $resultado['ST_SUBHEADER_AUTH'] = $row[4];
        $resultado['ST_SUBHEADER_QUALI'] = $row[5];
        $resultado['ST_SUBHEADER_INFO'] = $row[6];
        $resultado['ST_HOME'] = $row[7];

        return $resultado;

    } catch (Exception $e) {
        echo "<b>GetConfigSistema FALLO: </b>" . $e -> getMessage();
    }
}

function ScanDirectory($Directory) {
    // Recorre recursivamente los directorios y sub-directorios y muestra los archivos
    $MyDirectory = opendir($Directory) or die('Error');
    while ($Entry = @readdir($MyDirectory)) {
        if (is_dir($Directory . '/' . $Entry) && $Entry != '.' && $Entry != '..') {
            echo '<ul>' . $Directory;
            ScanDirectory($Directory . '/' . $Entry);
            echo '</ul>';
        } else {
            echo '<li>' . $Entry . '</li>';
        }
    }
    closedir($MyDirectory);
    /*
     Ejemplo
     ScanDirectory($_SERVER["DOCUMENT_ROOT"].'/../Common/miscellaneous/');
     */
}

function drawDialogJQUI($idDialog, $idSubtitulo, $idMensaje, $TextDialog, $TextSubtitulo, $TextMensaje){
	echo " <div id='".$idDialog."' title='".$TextDialog."'>
				<b class='txt-msj-Aviso' id='".$idSubtitulo."' >".$TextSubtitulo."</b>		
				<p>
				<div id='".$idMensaje."' style='padding:3px 0 0 0; text-align:left; font-style:italic;' >'".$TextMensaje."'. </div>
				<p>	
			</div>";
	
}