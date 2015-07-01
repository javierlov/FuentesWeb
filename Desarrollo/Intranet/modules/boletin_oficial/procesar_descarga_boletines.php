<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


$msg = "";
$desde = $_REQUEST["PeriodoDesde"];
$hasta = $_REQUEST["PeriodoHasta"];
$ano = substr($_REQUEST["PeriodoDesde"], 0, 4);
$mes = substr($_REQUEST["PeriodoDesde"], 4, 2);
$dia = substr($_REQUEST["PeriodoDesde"], 6, 2);
$_REQUEST["PeriodoDesde"] = date("Ymd", strtotime($desde." +1 day"));

if ($desde <= $hasta) {
//	$url = fopen("http://www.boletinoficial.gov.ar/Bora.Portal/CustomControls/PdfContent.aspx?fp=".$dia.$mes.$ano."&sec=01&pi=0&pf=0&s=0&Busqueda=PDF%20DEL%20DIA", "r");
//	if (!$url) {
//		$msg = "Ocurrió un error!";
	if (false == ($contenido=file_get_contents("http://www.boletinoficial.gov.ar/Bora.Portal/CustomControls/PdfContent.aspx?fp=".$dia.$mes.$ano."&sec=01&pi=0&pf=0&s=0&Busqueda=PDF%20DEL%20DIA"))) {
    $msg = "Ocurrió un error!";
	}
	else {
/*		$contenido = "";
		while (!feof ($url_web))
			$contenido.= fgets($url);
		fclose($url);
*/

		// Guardo el contenido en disco..
		$filename = DATA_BOLETIN_OFICIAL_PATH.$ano."/".$mes."/".$dia."/".$ano."_".$mes."_".$dia."_1.pdf";
		$contenido = "";

		// Asegurarse primero de que el archivo existe y puede escribirse sobre él.
		if (is_writable($filename)) {
    	// En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adición.
	    // El apuntador de archivo se encuentra al final del archivo, así que
  	  // allí es donde irá $contenido cuando llamemos fwrite().
    	if (!$gestor = fopen($filename, 'w')) {
				$msg = "No se puede abrir el archivo ($filename)";
				exit;
    	}

	    // Escribir $contenido a nuestro arcivo abierto.
  	  if (fwrite($gestor, $contenido) === FALSE) {
				$msg = "No se puede escribir al archivo ($filename)";
				exit;
	    }

  	  echo "Éxito!";

    	fclose($gestor);
		}
		else {
			echo "No se puede escribir sobre el archivo $filename";
		}
	}

	if ($msg != "") {
?>
<script>
	window.parent.resultado.innerText = '<?= $msg?>';
</script>
<?
	}
	else {
?>
<script>
	window.parent.resultado.innerText = "Procesando día <?= $dia."/".$mes."/".$ano?>.";
//	window.location.href = window.location.href;
</script>
<?
	}
}
else {
?>
<script>
	window.parent.resultado.innerText = "El proceso ha finalizado correctamente.";
</script>
<?
}
?>