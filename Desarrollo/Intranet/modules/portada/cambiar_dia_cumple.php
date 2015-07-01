<script>
	with (window.parent) {
		fechasCumpleanos = new Array();
		listaImagenes = new Array();
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$dia = $_REQUEST["d"];

$sql = "SELECT TO_CHAR(TRUNC(SYSDATE + ".$dia."), 'DD/MM') FROM DUAL";
$diaFormateado = valorSql($sql);

if ($dia == 0) {		// Si es el día actual..
	$diaHabil = "N";
	$dias = addQuotes(date("d/m"));

	while ($diaHabil != "S") {
		if ($diaHabil != "S") {
			$sql = "SELECT TO_CHAR(TRUNC(SYSDATE + ".$dia."), 'DD/MM') FROM DUAL";
			$dias.= ",".addQuotes(valorSql($sql));
		}

		$dia--;
		$sql = "SELECT amebpba.isdiahabil(SYSDATE + ".$dia.") FROM DUAL";
		$diaHabil = valorSql($sql);
	}
}
else {		// Sino, si esta navegando hacia adelante o hacia atrás..
	$sql = "SELECT TO_CHAR(TRUNC(SYSDATE + ".$dia."), 'DD/MM') FROM DUAL";
	$dias = addQuotes(valorSql($sql));
}

$sql =
	"SELECT TO_CHAR(TO_DATE(TO_CHAR(se_fechacumple, 'DD/MM') || '/' || TO_CHAR (SYSDATE, 'YYYY')), 'DAY') dia, TO_CHAR(se_fechacumple, 'DD/MM') fechacumple, se_foto, se_id, se_nombre
		 FROM art.use_usuarios
		WHERE TO_CHAR(se_fechacumple, 'DD/MM') IN (".$dias.")
			AND se_fechabaja IS NULL
 ORDER BY se_nombre";
$stmt = DBExecSql($conn, $sql);
$count = 0;
$html = "";
while ($row = DBGetQuery($stmt)) {
	$count++;

	$rutaFoto = base64_encode(IMAGES_FOTOS_PATH."cartel.jpg");
	if (is_file(IMAGES_FOTOS_PATH.$row["SE_FOTO"]))
		$rutaFoto = base64_encode(IMAGES_FOTOS_PATH.$row["SE_FOTO"]);

	$html.= '<div class="divCumpleañosItem">';
	$html.= '<span onMouseMove="moverImagen(this)" onMouseOut="ocultarImagen()" onMouseOver="cargarImagen('.$count.')"><a href="/contacto/'.$row["SE_ID"].'" style="color:#fff;">'.$row["SE_NOMBRE"].'</a></span>';
	$html.= '</div>';
?>
	fechasCumpleanos[<?= $count?>] = '<?= $row["DIA"]." ".$row["FECHACUMPLE"]?>';

	listaImagenes[<?= $count?>] = new Image();
	listaImagenes[<?= $count?>].src = '/functions/get_image.php?file=<?= $rutaFoto?>';
<?
}
if ($count == 0)
	$html = "<div id=\"divCumpleañosVacio\">NO HAY CUMPLEAÑOS</div>";
else {
?>
	document.getElementById('divCumpleaños').style.display = 'block';
<?
}
?>
		dia = '<?= $dia?>';
		document.getElementById('divCumpleañosItems').innerHTML = '<?= $html?>';
//		document.getElementById('imgDia').src = '/modules/portada/imagen_dia.php?d=<?= $diaFormateado?>';
	}
</script>