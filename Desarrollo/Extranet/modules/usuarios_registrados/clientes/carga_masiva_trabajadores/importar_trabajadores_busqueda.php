<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 55));

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "1";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];
if ($ob == "1")
	$ob = "TO_NUMBER(cm_fila)";

$params = array(":idusuario" => $_SESSION["idUsuario"], ":ipusuario" => $_SERVER["REMOTE_ADDR"]);
$sql =
	"SELECT DECODE(TO_NUMBER(cm_errores), 0, '<span error=\"true\">' || cm_fila || '</span>', TO_NUMBER(cm_fila)) ¿fila?,
					DECODE(SUBSTR(cm_errores, 1, 1), 1, cm_cuil, '<span error=\"true\">' || cm_cuil || '</span>') ¿cuil?,
					DECODE(SUBSTR(cm_errores, 2, 1), 1, cm_nombre, '<span error=\"true\">' || cm_nombre || '</span>') ¿nombre?,
					DECODE(SUBSTR(cm_errores, 3, 1), 1, cm_sexo, '<span error=\"true\">' || cm_sexo || '</span>') ¿sexo?,
					DECODE(SUBSTR(cm_errores, 4, 1), 1, cm_nacionalidad, '<span error=\"true\">' || cm_nacionalidad || '</span>') ¿nacionalidad?,
					DECODE(SUBSTR(cm_errores, 5, 1), 1, cm_otranacionalidad, '<span error=\"true\">' || cm_otranacionalidad || '</span>') ¿otranacionalidad?,
					DECODE(SUBSTR(cm_errores, 6, 1), 1, cm_fechanacimiento, '<span error=\"true\">' || cm_fechanacimiento || '</span>') ¿fechanacimiento?,
					DECODE(SUBSTR(cm_errores, 7, 1), 1, cm_estadocivil, '<span error=\"true\">' || cm_estadocivil || '</span>') ¿estadocivil?,
					DECODE(SUBSTR(cm_errores, 8, 1), 1, cm_fechaingreso, '<span error=\"true\">' || cm_fechaingreso || '</span>') ¿fechaingreso?,
					DECODE(SUBSTR(cm_errores, 9, 1), 1, cm_establecimiento, '<span error=\"true\">' || cm_establecimiento || '</span>') ¿establecimiento?,
					DECODE(SUBSTR(cm_errores, 10, 1), 1, cm_tipocontrato, '<span error=\"true\">' || cm_tipocontrato || '</span>') ¿tipocontrato?,
					DECODE(SUBSTR(cm_errores, 11, 1), 1, cm_tarea, '<span error=\"true\">' || cm_tarea || '</span>') ¿tarea?,
					DECODE(SUBSTR(cm_errores, 12, 1), 1, cm_sector, '<span error=\"true\">' || cm_sector || '</span>') ¿sector?,
					DECODE(SUBSTR(cm_errores, 13, 1), 1, cm_ciuo, '<span error=\"true\">' || cm_ciuo || '</span>') ¿ciuo?,
					DECODE(SUBSTR(cm_errores, 14, 1), 1, cm_sueldo, '<span error=\"true\">' || cm_sueldo || '</span>') ¿sueldo?,
					DECODE(SUBSTR(cm_errores, 15, 1), 1, cm_calle, '<span error=\"true\">' || cm_calle || '</span>') ¿calle?,
					DECODE(SUBSTR(cm_errores, 16, 1), 1, cm_numero, '<span error=\"true\">' || cm_numero || '</span>') ¿numero?,
					DECODE(SUBSTR(cm_errores, 17, 1), 1, cm_piso, '<span error=\"true\">' || cm_piso || '</span>') ¿piso?,
					DECODE(SUBSTR(cm_errores, 18, 1), 1, cm_departamento, '<span error=\"true\">' || cm_departamento || '</span>') ¿departamento?,
					DECODE(SUBSTR(cm_errores, 19, 1), 1, cm_codigopostal, '<span error=\"true\">' || cm_codigopostal || '</span>') ¿codigopostal?,
					DECODE(SUBSTR(cm_errores, 20, 1), 1, cm_localidad, '<span error=\"true\">' || cm_localidad || '</span>') ¿localidad?,
					DECODE(SUBSTR(cm_errores, 21, 1), 1, cm_provincia, '<span error=\"true\">' || cm_provincia || '</span>') ¿provincia?,
					DECODE(SUBSTR(cm_errores, 22, 1), 1, cm_fechabaja, '<span error=\"true\">' || cm_fechabaja || '</span>') ¿fechabaja?
		 FROM tmp.tcm_cargamasivatrabajadoresweb
		WHERE cm_idusuario = :idusuario
			AND cm_ipusuario = :ipusuario";
$grilla = new Grid();
$grilla->addColumn(new Column("#"));
$grilla->addColumn(new Column("C.U.I.L."));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("Sexo"));
$grilla->addColumn(new Column("Nacionalidad"));
$grilla->addColumn(new Column("Otra Nacionalidad"));
$grilla->addColumn(new Column("Fecha Nacimiento"));
$grilla->addColumn(new Column("Estado Civil"));
$grilla->addColumn(new Column("Fecha Ingreso"));
$grilla->addColumn(new Column("Establecimiento"));
$grilla->addColumn(new Column("Tipo Contrato"));
$grilla->addColumn(new Column("Tarea"));
$grilla->addColumn(new Column("Sector"));
$grilla->addColumn(new Column("Código CIUO"));
$grilla->addColumn(new Column("Remuneración"));
$grilla->addColumn(new Column("Calle"));
$grilla->addColumn(new Column("Número"));
$grilla->addColumn(new Column("Piso"));
$grilla->addColumn(new Column("Departamento"));
$grilla->addColumn(new Column("Código Postal"));
$grilla->addColumn(new Column("Localidad"));
$grilla->addColumn(new Column("Provincia"));
$grilla->addColumn(new Column("Fecha Baja"));

$grilla->setDecodeSpecialChars(true);
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';

		var errores = getElementsByTagName("span");

		for (var i=0; i<errores.length; i++)
			if (errores[i].getAttribute('ERROR') == 'TRUE')
				errores[i].parentNode.parentNode.style.background = '#f00';
	}
</script>