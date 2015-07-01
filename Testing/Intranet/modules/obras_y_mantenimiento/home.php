        <table align="center" width="770">
          <tr>
            <td colspan="3" align="center">
              <br/>
<?
$sql =
	"SELECT BN_DESCRIPCION
		FROM COMPUTOS.CBN_BANNERNOTICIAS
	 WHERE BN_FECHAFIN IS NULL";
$texto_banner = ValorSQL($sql);
if ($texto_banner != "")
	echo "<font size='2' color='#044A88'><b>ATENCIÓN<br />".$texto_banner."</b></font><br /><br />";
?>
              <br/><br/>
            </td>
          </tr>
          <tr align="left">
            <td width="200" align="right">
              <img src="/modules/obras_y_mantenimiento/images/user_accounts.png"/>
            </td>
            <td width="370">
              <div align="justify">
                <p><font color="#807F84">Bienvenido/a</font> <font color="#FF6600"><b><?echo strtoupper(GetUserName());?></b>!</font>
                  <br />
                  <br />
                  <font color="#807F84">Este módulo permite realizar todo tipo de solicitudes a sistemas.
              En particular, se aceptarán pedidos tanto para el sector de IT &amp; Comunicaciones, Seguridad Informática, como
              para el equipo de Desarrollo de Sistemas de las aplicaciones de la compañía. <br />
              <br />
              Al realizar una solicitud, como constancia Ud. recibirá una confirmación por correo electrónico indicando
              un número de ticket que le servirá como referencia para futuras consultas. </font><br />
              <br />
<?
$sql =
	"SELECT COUNT(*)
		FROM COMPUTOS.CSS_SOLICITUDSISTEMAS
	 WHERE SS_IDESTADOACTUAL = 5
		  AND SS_IDUSUARIO_SOLICITUD = :idusuario";
$params = array(":idusuario" => GetUserID());
$pending_tickets = ValorSQL($sql, "", $params);
$link = '<a href="index.php?search=yes&amp;pending_tickets=yes" style="text-decoration: none;">';
if ($pending_tickets > 0) {
?>
                <font color="#807F84">Ud. tiene</font><b> <?echo $link;?>
                <?
              echo $pending_tickets;
              echo "</a></b>";
              echo ($pending_tickets == 1)?" ticket pendiente ":" tickets pendientes ";
              ?>
                <font color="#807F84">de calificar. Puede hacer click</font> <?echo $link;?> <font color="#807F84"></b><font color="#000000">aquí</font><b> </a></b> para calificarnos. </font>
  			  <?
              }

$sql =
	"SELECT COUNT(*)
		FROM COMPUTOS.CSS_SOLICITUDSISTEMAS
	 WHERE SS_IDESTADOACTUAL = 11
		  AND SS_IDUSUARIO_SOLICITUD = :idusuario";
$params = array(":idusuario" => GetUserID());
$pending_moreinfo_tickets = ValorSQL($sql, "", $params);
$link = '<a href="index.php?search=yes&amp;pending_moreinfo_tickets=yes" style="text-decoration: none;">';
if ($pending_moreinfo_tickets > 0) {
?>
                </p>
                <p><font color="#807F84">Ud. tiene</font> <b><?echo $link;?>
                  <?
              echo $pending_moreinfo_tickets;
              echo "</a></b>";
              echo ($pending_moreinfo_tickets == 1)?" ticket ":" tickets ";
              ?>
                  <font color="#807F84">en estado </font>Esperando mas información<font color="#807F84">. Puede hacer click</font> <?echo $link;?> <font color="#000000">aquí </font><font color="#807F84"></a> para completarlos.</font>                  <?
              }
              ?>
                  <?
              $pending_auth_tickets = ValorSQL("SELECT SUM(cantidad) AS cantidad
                                                  FROM (SELECT COUNT(*) AS cantidad
                                                          FROM computos.cps_permisosolicitud
                                                         WHERE ps_fechaautorizacion IS NULL
                                                           AND ps_idusuario = ".GetUserID()."
                                                        UNION
                                                        SELECT COUNT(*) AS cantidad
                                                          FROM computos.css_solicitudsistemas, computos.cms_motivosolicitud
                                                         WHERE ss_idestadoactual = 2
                                                           AND ms_id = ss_idmotivosolicitud
                                                           AND NOT EXISTS(SELECT 1
                                                                            FROM computos.cps_permisosolicitud
                                                                           WHERE ps_idsolicitud = ss_id
                                                                             AND ps_fechaautorizacion IS NULL)
                                                           AND computos.general.get_usuarioresponsable
                                                                                         (NVL((SELECT DECODE(hs_idestado,
                                                                                                             2, hs_idusuario_cambio,
                                                                                                             ss_idusuario_solicitud)
                                                                                                 FROM computos.chs_historicosolicitud chs1
                                                                                                WHERE chs1.hs_idsolicitud = ss_id
                                                                                                  AND chs1.hs_fecha_cambio =
                                                                                                        (SELECT MAX(chs2.hs_fecha_cambio)
                                                                                                           FROM computos.chs_historicosolicitud chs2
                                                                                                          WHERE chs1.hs_idsolicitud = chs2.hs_idsolicitud
                                                                                                            AND chs2.hs_idusuario_cambio NOT IN(
                                                                                                                                            SELECT se_id
                                                                                                                                              FROM art.use_usuarios
                                                                                                                                             WHERE se_sector =
                                                                                                                                                              'COMPUTOS')
                                                                                                            AND chs2.hs_idestado = 2)),
                                                                                              ss_idusuario_solicitud),
                                                                                          ms_nivel) = ".GetUserID().")");

              $link = '<a href="index.php?search=yes&amp;pending_auth_tickets=yes" style="text-decoration: none;">';
              if ($pending_auth_tickets > 0) {
              ?>
                  <br />
			    </p>
                <p>
                <font color="#807F84">Ud. tiene</font> <?echo $link;?>
                <?
              echo $pending_auth_tickets;
              ?>
                </a> <font color="#807F84">tickets pendientes de autorizar. Puede hacer click</font> <?echo $link;?> <font color="#000000">aquí</font><font color="#807F84"> </a> para autorizarlos o rechazarlos.</font></p>
                <?
              }
              ?>			  
              </div></td>
            <td width="200">
              <img src="/modules/obras_y_mantenimiento/images/System.png"/>
            </td>
          </tr>
        </table>