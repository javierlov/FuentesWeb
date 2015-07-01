<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
?>
<html>
	<head>
	<?= GetHead("Prestadores", array("grid.css?today=".date("Ymd"), "style.css?today=".date("Ymd")))?>
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div align="center" id="divContent" name="divContent">
<?
$showProcessMsg = false;

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = 3;
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$params = array(":codpostal" => $_REQUEST["cp"], ":especialidad" => $_REQUEST["prestador"]);
$sql =
	"SELECT NULL ¿id?,
					¿ca_identificador?,
					¿ca_nombrefanta?,
					art.utiles.armar_domicilio(ca_calle, ca_numero, ca_pisocalle, ca_departamento, NULL) ¿dom?,
					¿ca_localidad?,
					¿ca_codarea?,
					¿ca_telefono?
		 FROM art.cpr_prestador
		WHERE ca_cartillaweb IN('A', 'M')
			AND ca_fechabaja IS NULL
			AND ca_codpostal = :codpostal
			AND ca_especialidad = :especialidad";
$grilla = new Grid();
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "GridFirstColumn"));
$grilla->addColumn(new Column("ID"));
$grilla->addColumn(new Column("Nombre de Fantasía"));
$grilla->addColumn(new Column("Domicilio"));
$grilla->addColumn(new Column("Localidad"));
$grilla->addColumn(new Column("Código"));
$grilla->addColumn(new Column("Teléfono"));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setSql($sql);
$grilla->Draw();
?>
		</div>
		<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img alt="Espere por favor..." src="/images/waiting.gif"></div>
		<script>
		function CopyContent() {
			try {
				window.parent.document.getElementById('divContent').innerHTML = document.getElementById('divContent').innerHTML;
			}
			catch(err) {
				//alert(err.description);
			}
		}

		CopyContent();
		</script>
	</body>
</html>