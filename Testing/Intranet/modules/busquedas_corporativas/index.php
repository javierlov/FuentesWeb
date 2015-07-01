<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


if ((isset($_REQUEST["buscar"])) and (!isset($_REQUEST["firstcall"])))
	if ($showProcessMsg)
		FirstCallPageCode();

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2_D_";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];
?>
<script>
	showTitle(true, 'BÚSQUEDAS LABORALES');
</script>
<br />
<?
// Muestro los últimos 10 registros cargados..
$ids = array();
$sql =
	"SELECT bc_id
		 FROM (SELECT bc_id
						 FROM rrhh.rbc_busquedascorporativas, rrhh.rec_estadosbusquedacorporativa
						WHERE bc_idestado = ec_id
							AND bc_fechabaja IS NULL
				 ORDER BY bc_id DESC)
		WHERE ROWNUM <= 10";
$stmt = DBExecSql($conn, $sql, array());
while ($row = DBGetQuery($stmt))
	$ids[] = $row["BC_ID"];

$sql =
	"SELECT ¿bc_id?, TO_NUMBER(bc_id) ¿id2?, ¿bc_puesto?, DECODE(bc_idempresa, -2, 'Interna', 'Corporativa') ¿tipobusqueda?, ¿em_nombre?, ¿ec_detalle?,
					DECODE(bc_nombrearchivo, NULL, 'T', 'F') ¿hidecell?
		 FROM rrhh.rbc_busquedascorporativas, rrhh.rec_estadosbusquedacorporativa, aem_empresa
		WHERE bc_idestado = ec_id
			AND bc_idempresa = em_id(+)
			AND bc_id IN (-1, ".implode(",", $ids).")";
$grilla = new Grid(10, 100);
$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "/modules/busquedas_corporativas/ver_archivo.php", "GridFirstColumn"));
$grilla->addColumn(new Column("Nº"));
$grilla->addColumn(new Column("Puesto"));
$grilla->addColumn(new Column("Tipo Búsqueda"));
$grilla->addColumn(new Column("Empresa solicitante"));
$grilla->addColumn(new Column("Estado"));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 1));
$grilla->setColsSeparator(true);
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setRowsSeparator(true);
$grilla->setSql($sql);
$grilla->Draw();
?>