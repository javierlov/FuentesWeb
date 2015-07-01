<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");

$showProcessMsg = true;

$all_tickets = "no";
if (isset($_REQUEST["all_tickets"]))
	$all_tickets = $_REQUEST["all_tickets"];

$pending_tickets = "no";
if (isset($_REQUEST["pending_tickets"]))
	$pending_tickets = $_REQUEST["pending_tickets"];

$pending_moreinfo_tickets = "no";
if (isset($_REQUEST["pending_moreinfo_tickets"]))
	$pending_moreinfo_tickets = $_REQUEST["pending_moreinfo_tickets"];

$pending_auth_tickets = "no";
if (isset($_REQUEST["pending_auth_tickets"]))
	$pending_auth_tickets = $_REQUEST["pending_auth_tickets"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Provincia ART | Sistemas</title>
  <meta content="Mon, 06 Jan 1990 00:00:01 GMT" http-equiv="Expires" />

  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/interface.js"></script>

  <script type="text/javascript" src="/Js/functions.js"></script>
  <script type="text/javascript" src="/Js/validations.js"></script>
  <script type="text/javascript" src="/Js/ajax.js" charset="iso-8859-1"></script>
  <script type="text/javascript" src="Js/ticket.js" charset="iso-8859-1"></script>
  <script type="text/javascript" src="/Js/grid.js"></script>

  <!--
  <link href="/Styles/formstyle.css" rel="stylesheet" type="text/css"></link>
  -->
  <link href="Styles/style_sistemas.css?sid=<?= date('YmdHis'); ?>" rel="stylesheet" type="text/css" />

  <!-- calendar stylesheet
  <link rel="stylesheet" type="text/css" media="all" href="/js/calendario/calendar-win2k-cold-1.css" title="win2k-cold-1" />
  -->
  <!-- main calendar program
  <script type="text/javascript" src="/js/calendario/calendar.js"></script>
  -->
  <!-- language for the calendar
  <script type="text/javascript" src="/js/calendario/lang/calendar-es.js"></script>
  -->
  <!-- the following script defines the Calendar.setup helper function, which makes adding a calendar a matter of 1 or 2 lines of code.
  <script type="text/javascript" src="/js/calendario/calendar-setup.js"></script>
  -->

  <!--[if lt IE 7]>
   <style type="text/css">
   div, img { behavior: url(iepngfix.htc) }
   </style>
  <![endif]-->
	<script>
 		function load() {
			CopyContent();
			try {
				window.parent.setSize();
			}
			catch (ex) {
				window.parent.parent.setSize();
			}
		}
	</script>
</head>
<body background="images/dock-bg2.gif" onLoad="load()">
<iframe id="iframeProcesando" name="iframeProcesando" src="about:blank" style="display:none;"></iframe>
  <div class="dock" id="dock">
    <div class="dock-container">
      <a class="dock-item" href="index.php?search=no"><img src="images/home.png" alt="Inicio" /><span>Inicio</span></a>
<!--
      <a class="dock-item" href="index.php?search=no"><img src="images/email.png" alt="Contáctenos" /><span>Contáctenos</span></a>
-->
      <a class="dock-item" href="index.php?newticket=yes"><img src="images/portfolio.png" alt="Realizar pedido" /><span>Realizar pedido</span></a>
      <a class="dock-item" href="index.php?search=yes&amp;all_tickets=no"><img src="images/calendar.png" alt="Pedidos actuales" /><span>Pedidos actuales</span></a>
      <a class="dock-item" href="index.php?search=yes&amp;all_tickets=yes"><img src="images/history.png" alt="Historial de pedidos" /><span>Historial de pedidos</span></a>
    </div>
  </div>
<script type="text/javascript">
	$(document).ready(
		function()
		{
			$('#dock').Fisheye(
				{
					maxWidth: 50,
					items: 'a',
					itemsText: 'span',
					container: '.dock-container',
					itemWidth: 40,
					proximity: 90,
					halign : 'center'
				}
			)
		}
	);
</script>
<br />
<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none">
  <img alt="Espere por favor..." border="0" src="/images/waiting.gif" />
</div>
<div align="center" id="divContent" name="divContent">
  <table align="center" width="770">
    <tr>
      <td>
        <?
        if ((isset($_REQUEST["search"])) and ($_REQUEST["search"] == "yes")) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/obras_y_mantenimiento/tickets_grid.php");
        }
        else
        if ((isset($_REQUEST["information"])) and ($_REQUEST["information"] == "yes")) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/obras_y_mantenimiento/ticket_information.php");
        }
        else
        if ((isset($_REQUEST["qualification"])) and ($_REQUEST["qualification"] == "yes")) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/obras_y_mantenimiento/ticket_qualification.php");
        }
        else
        if (isset($_REQUEST["authorize"])) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/obras_y_mantenimiento/ticket_authorization.php");
        }
        else
        if ((isset($_REQUEST["newticket"])) and ($_REQUEST["newticket"] == "yes")) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/obras_y_mantenimiento/ticket_new.php");
        }
        else
        if ((isset($_REQUEST["ticket_detail"])) and ($_REQUEST["ticket_detail"] == "yes")) {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/obras_y_mantenimiento/ticket_detail.php");
        }
        else {
          require($_SERVER["DOCUMENT_ROOT"]."/Modules/obras_y_mantenimiento/home.php");
        }
        ?>
      </td>
    </tr>
  </table>
</div>
<?
require($_SERVER["DOCUMENT_ROOT"]."/functions/copy_content.php");
?>
<script>
	CopyContent();
</script>
</body>
</html>