<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


validarSesion(isset($_SESSION["isOrganismoPublico"]));

SetDateFormatOracle("DD/MM/YYYY HH24:MI");

set_time_limit(300);

$showProcessMsg = true;

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2_D_";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];
?>
<style>
.colFecha {
	text-align: center;
	white-space: nowrap;
}
</style>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<div class="TituloSeccion" style="display:block; width:730px;">Acceso exclusivo organismos públicos</div>
<div class="SubtituloSeccion" style="margin-top:8px;">Declaración Jurada de personal</div>
<div class="ContenidoSeccion" style="margin-top:16px;">
	Estimado Cliente, aquí pueden encontrar todas sus declaraciones juradas presentadas de manera on-line, para imprimirlas y remitirlas firmadas de manera original (sin enmienda ni tachaduras) a:<br />
	<p style="margin-left: 15px">
		> Pellegrini 91, 4º piso, Sector EMISION, 4º piso (C.P. 1009) Ciudad Autónoma de Buenos Aires.<br />
		> Casilla de Correo Argentino especial Nº 4, sucursal 1, Av. de Mayo (C.P. 1084).
	</p>
	Recuerde que ante cualquier duda o consulta referida a las mismas puede contactarse con el Sector Emisión de Provincia ART al teléfono <b>4819-2842</b> o al e-mail <b><a class="linkSubrayado" href="mailto:emision@provart.com.ar">emision@provart.com.ar</a></b>.
	<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
	<div align="center" id="divContentGrid" name="divContentGrid" style="margin-top:8px;">
<?
$params = array(":contrato" => $_SESSION["contrato"]);

$sql =
	"SELECT ¿no_transaccion?, ¿np_periodo?, ¿fechahoraalta?
		 FROM (SELECT no_secuencia, np_idestadoformulario, no_transaccion,
									(SELECT op_fechaalta
										 FROM emi.iop_organismopublico
										WHERE op_transaccion = no_transaccion
											AND ROWNUM = 1) fechahoraalta, np_periodo,
									emi.utiles.get_parametrobyclave('PATHOP') || '\' || LPAD(SUBSTR(no_contrato, -3), 3, '0') || '\' || np_periodo || '\' || em_cuit || '_' || no_contrato || '_' || no_secuencia || '.pdf' path
						 FROM emi.ipo_notaperiodoobservacion, afi.aem_empresa, afi.aco_contrato, emi.ino_nota, emi.inp_notacontratoperiodo
						WHERE no_id = np_idnota
							AND em_id = co_idempresa
							AND co_contrato = no_contrato
							AND np_id = po_idnotacontratoperiodo
							AND no_contrato = :contrato
							AND no_fechabaja IS NULL
							AND np_fechabaja IS NULL
							AND np_idestadoformulario <> 6
							AND NVL(no_mostrarweb, 'S') = 'S'
							AND no_idestadonota = 2
							AND np_idtipoformulario = 91
							AND po_idobservacion = 1087)";
	$grilla = new Grid(15, 8);
	$grilla->addColumn(new Column("V", 0, true, false, -1, "btnEditar", "/modules/usuarios_registrados/organismos_publicos/ver_informe.php?rnd".date("Ymdhns"), "", -1, true, -1, "Ver"));
	$grilla->addColumn(new Column("Período", 0, true, false, -1, "", "", "colFecha", -1, false));
	$grilla->addColumn(new Column("Fecha Alta", 0, true, false, -1, "", "", "colFecha", -1, false));
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);
	$grilla->setTableStyle("GridTableCiiu");
	$grilla->setUseTmpIframe(true);
	$grilla->Draw();
?>
	</div>
	<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	<script type="text/javascript">
		function CopyContent() {
			try {
				window.parent.document.getElementById('divContentGrid').innerHTML = document.getElementById('divContentGrid').innerHTML;
			}
			catch(err) {
				//
			}
<?
if ($showProcessMsg) {
?>
	if (window.parent.document.getElementById('originalGrid') != null)
		window.parent.document.getElementById('originalGrid').style.display = 'block';
	window.parent.document.getElementById('divProcesando').style.display = 'none';
<?
}
?>
		}

		CopyContent();
	</script>
	<p style="margin-left:648px; position:relative; top:0px;">
		<a href="/index.php?pageid=46"><input class="btnVolver" type="button" value="" /></a>
	</p>
</div>