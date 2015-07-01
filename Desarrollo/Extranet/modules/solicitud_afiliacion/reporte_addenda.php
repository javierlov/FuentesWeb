<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


function dibujarParrafo($texto, $salto) {
	global $pdf;

	$pdf->Ln($salto);
	$pdf->WordWrap($texto, 184);
	$texto = explode("\n", $texto);
	for ($j=0; $j<count($texto); $j++) {
		$str = trim($texto[$j]);

		$pdf->Cell(2);
		$pdf->Cell(184, 0, $str);
		$pdf->Ln(3.8);
	}
}

function setNumeroSolicitud($cuit, $numeroFormulario) {
	return "Nº 00051-".$cuit."-".$numeroFormulario;
}


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idmodulo"]);


SetDateFormatOracle("DD/MM/YYYY");

$id = substr($_REQUEST["idmodulo"], 1);
$modulo = substr($_REQUEST["idmodulo"], 0, 1);

if (!isset($_REQUEST["ap"]))
	$autoPrint = false;
else
	$autoPrint = ($_REQUEST["ap"] == "t");

$params = array(":id" => $id);
if ($modulo == "C")
	$sql =
		"SELECT sa_lugarsuscripcion lugarsuscripcion,
						TO_NUMBER(TO_CHAR(sa_fecharecepcion, 'dd')) diasuscripcion,
						TO_CHAR(sa_fecharecepcion, 'Month') messuscripcion,
						TO_CHAR(sa_fecharecepcion, 'yyyy') anosuscripcion,
						NVL(sa_nombre_vendedor, ve_nombre) comercializador,
						DECODE(su_codsucursal, NULL, (SELECT en_codbanco FROM xen_entidad WHERE NVL(ev_identidad, uw_identidad) = en_id), (SELECT en_codbanco FROM xen_entidad WHERE NVL(ev_identidad, uw_identidad) = en_id) || '(' || su_codsucursal || ')') entidad,
						ve_vendedor vendedor,
						sa_titular titular,
						sa_documento_titular dnititular,
						cargo.tb_descripcion cargotitular,
						NVL(sa_nombre, NVL(co_razonsocial, sc_razonsocial)) empleador,
						art.utiles.armar_cuit(sc_cuit) cuitempleador,
						art.utiles.armar_domicilio(sa_calle, sa_numero, sa_piso, sa_departamento, NULL) || art.utiles.armar_localidad(sa_cpostal, NULL, sa_localidad, pv_descripcion) domicilioempleador,
						fo_formulario,
						NVL(fo_cuit, uw_cuitsuscripcion) cuitsuscripcion
			 FROM asc_solicitudcotizacion, asa_solicitudafiliacion, afo_formulario, aco_cotizacion, xev_entidadvendedor,
						xve_vendedor, asu_sucursal, cpv_provincias, ctb_tablas cargo, afi.auw_usuarioweb
			WHERE sc_idformulario = sa_idformulario(+)
				AND sc_idformulario = fo_id(+)
				AND sc_idcotizacion = co_id(+)
				AND sa_identidadvendedor = ev_id(+)
				AND ev_idvendedor = ve_id(+)
				AND sa_idsucursal = su_id(+)
				AND sa_provincia = pv_codigo(+)
				AND sa_cargo_titular = cargo.tb_codigo(+)
				AND cargo.tb_clave(+) = 'CARGO'
				AND cargo.tb_especial2(+) = 'SOLO_FIRMANTE'
				AND cargo.tb_fechabaja(+) IS NULL
				AND sc_usuariosolicitud = uw_usuario(+)
				AND sc_id = :id";
else
	$sql =
		"SELECT sa_lugarsuscripcion lugarsuscripcion,
						TO_CHAR(sa_fecharecepcion, 'dd') diasuscripcion,
						TO_CHAR(sa_fecharecepcion, 'Month') messuscripcion,
						TO_CHAR(sa_fecharecepcion, 'yyyy') anosuscripcion,
						NVL(sa_nombre_vendedor, ve_nombre) comercializador,
						DECODE(su_codsucursal, NULL, (SELECT en_codbanco FROM xen_entidad WHERE NVL(ev_identidad, uw_identidad) = en_id), (SELECT en_codbanco FROM xen_entidad WHERE NVL(ev_identidad, uw_identidad) = en_id) || '(' || su_codsucursal || ')') entidad,
						ve_vendedor vendedor,
						sa_titular titular,
						sa_documento_titular dnititular,
						cargo.tb_descripcion cargotitular,
						NVL(sa_nombre, em_nombre) empleador,
						art.utiles.armar_cuit(sr_cuit) cuitempleador,
						art.utiles.armar_domicilio(sa_calle, sa_numero, sa_piso, sa_departamento, NULL) || art.utiles.armar_localidad(sa_cpostal, NULL, sa_localidad, pv_descripcion) domicilioempleador,
						fo_formulario,
						NVL(fo_cuit, uw_cuitsuscripcion) cuitsuscripcion
			 FROM asr_solicitudreafiliacion, asa_solicitudafiliacion, afo_formulario, aem_empresa, xev_entidadvendedor,
						xve_vendedor, asu_sucursal, cpv_provincias, ctb_tablas cargo, afi.auw_usuarioweb
			WHERE sr_idformulario = sa_idformulario(+)
				AND sr_idformulario = fo_id(+)
				AND sa_identidadvendedor = ev_id(+)
				AND ev_idvendedor = ve_id(+)
				AND sa_idsucursal = su_id(+)
				AND sa_provincia = pv_codigo(+)
				AND sa_cargo_titular = cargo.tb_codigo(+)
				AND cargo.tb_clave(+) = 'CARGO'
				AND cargo.tb_especial2(+) = 'SOLO_FIRMANTE'
				AND cargo.tb_fechabaja(+) IS NULL
				AND sr_usualta = uw_usuario(+)
				AND sr_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row2 = DBGetQuery($stmt, 1, false);

if ($autoPrint)
	$pdf = new PDF_AutoPrint();
else
	$pdf = new FPDI();

$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/templates/addenda.pdf");

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "", 10);

$pdf->Ln(10);
$pdf->Cell(-5);
$pdf->Cell(198, 0, setNumeroSolicitud($row2["CUITSUSCRIPCION"], $row2["FO_FORMULARIO"]), 0, 0, "C");

$texto = "En ".$row2["LUGARSUSCRIPCION"].", a los ".$row2["DIASUSCRIPCION"]." días del mes de ".$row2["MESSUSCRIPCION"]." de ".$row2["ANOSUSCRIPCION"].", comparecen, por una parte, ".$row2["COMERCIALIZADOR"].", Entidad (Sucursal) ".$row2["ENTIDAD"].", Vendedor ".$row2["VENDEDOR"].",";
$texto.= " en su carácter Comercializador de Provincia ART S.A., en adelante denominada “La Aseguradora”, y, por la otra,";
$texto.= " ".$row2["TITULAR"]." (D.N.I. Nº ".$row2["DNITITULAR"]."), en su carácter de ".$row2["CARGOTITULAR"].", representando en este acto a ".$row2["EMPLEADOR"].", ".$row2["CUITEMPLEADOR"].", con domicilio constituido en ".$row2["DOMICILIOEMPLEADOR"].",";
$texto.= " , en adelante denominado “El Cliente”, de acuerdo con el poder que en copia se acompaña, quienes deciden";
$texto.= " incorporar la presente ADDENDA al contrato que las vincula.";
dibujarParrafo($texto, 20);

$texto = "PRIMERA: La Aseguradora, ante la suscripción del contrato de afiliación en los términos de la ley 24.557, por parte de El Cliente y la consecuente entrada en vigencia del mismo, se compromete a bonificar en forma automática a favor de éste último, el 50% del valor de la primera cuota que debe abonar El Cliente en concepto de contraprestación, conforme la tarifa pactada (% sobre masa salarial y suma fija). La bonificación no alcanza al importe correspondiente al FFEP (Fondo Fiduciario de Enfermedades Profesionales).";
dibujarParrafo($texto, 5);

$texto = "SEGUNDA: Las partes acuerdan que en caso de operar la renovación automática del contrato de afiliación celebrado en los términos de la Ley 24.557, por no haber manifestado El Cliente, en forma fehaciente su decisión en contrario ni haber solicitado la afiliación a otra ART, la Aseguradora bonificará, a favor del mismo, el 50 % del valor de la alícuota correspondiente al último mes de la vigencia anual del citado contrato. Esta bonificación se ejecutará sobre la cuota número 12 del contrato durante el 13º mes de vigencia.";
dibujarParrafo($texto, 5);

$texto = "TERCERA: Se deja constancia que la presente ADDENDA, comenzará a producir efectos a partir de la  suscripción del contrato y la aprobación del mismo por parte de la SRT.";
dibujarParrafo($texto, 5);

$texto = "CUARTA: Las bonificaciones a que se refieren los artículos que anteceden, están condicionadas a que el Cliente efectúe cada pago en tiempo y forma. Ante el primer incumplimiento de “el Cliente” de cualquiera de las obligaciones asumidas (pago en tiempo y forma de la tarifa, presentación de nómina de personal y de Declaraciones Juradas de Personal) la Aseguradora está facultada para dejar sin efecto las bonificaciones acordadas, operadas o futuras, en los artículos PRIMERO y SEGUNDO y será exigibles la totalidad de la deuda nominal más sus intereses.";
dibujarParrafo($texto, 5);

$texto = "En prueba de conformidad se firman dos ejemplares de un  mismo tenor y a un único efecto.";
dibujarParrafo($texto, 5);


if ($autoPrint)
	$pdf->AutoPrint(false);
$pdf->Output();
?>