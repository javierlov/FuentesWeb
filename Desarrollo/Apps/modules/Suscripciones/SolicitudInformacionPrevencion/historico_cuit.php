<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


if ((isset($_REQUEST["buscar"])) and (!isset($_REQUEST["firstcall"])))
	if ($showProcessMsg)
		FirstCallPageCode();

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];
?>
<html>
	<head>
		<title>IntraWEB | Histórico por CUIT</title>
		<script src="solicitud.js" type="text/javascript"></script>
		<link href="/styles/grid.css" rel="stylesheet" type="text/css" />
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<table class="Width600 GrisOscuro" cellpadding="0">
			<tr>
				<td width="5%"><img src="/images/01.jpg"></td>
				<td class="Title01" width="90%"><h5>Histórico por CUIT</h5></td>
				<td width="5%"><img src="/images/02.jpg"></td>
			</tr>
		</table>
		<br />
<?
$params = array(":idtransaccion" => $_REQUEST["id"]);
$sql =
	"SELECT ¿cuit?, ¿ciiu?, ¿fecharespuesta?, ¿si_usurespuesta?, ¿porcentajeexpuestos?, ¿costoexamen?, ¿si_trabajadoresexpuestos?, ¿costototalperiodicos?, ¿costopromediovisita?,
					¿si_cantidadvisitastotales?, ¿totalvisitas?, ¿otraserogaciones?, ¿costototalprevencion?
		 FROM afi.v_infoprevencion
		WHERE sc_cuit = (SELECT sc_cuit
											 FROM asc_solicitudcotizacion, asi_solicitudinfoprevencion
											WHERE si_idsolicitudcotizacion = sc_id
												AND si_idtransaccionweb = :idtransaccion)
			AND si_fecharespuesta IS NOT NULL";
$grilla = new Grid(10, 100);
$grilla->addColumn(new Column("CUIT"));
$grilla->addColumn(new Column("CIIU"));
$grilla->addColumn(new Column("Fecha"));
$grilla->addColumn(new Column("Usuario"));
$grilla->addColumn(new Column("% Expuestos"));
$grilla->addColumn(new Column("Costo Exámen"));
$grilla->addColumn(new Column("Trabajadores Expuestos"));
$grilla->addColumn(new Column("Costo Total Periódicos"));
$grilla->addColumn(new Column("Costo Promedio Visita"));
$grilla->addColumn(new Column("Cantidad Visitas Totales"));
$grilla->addColumn(new Column("Total Visitas"));
$grilla->addColumn(new Column("Otras Erogaciones"));
$grilla->addColumn(new Column("Costo Total Prevención"));
$grilla->setColsSeparator(true);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
$grilla->setSql($sql);
$grilla->Draw();
?>
		<table class="Width600 Celeste">
			<tr>
				<td width="5%"><img src="/images/03.jpg"></td>
				<td width="90%"></td>
				<td width="5%"><img src="/images/04.jpg"></td>
			</tr>
		</table>
	</body>
</html>