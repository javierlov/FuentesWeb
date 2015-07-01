<?php
function phpwc_boton_corregir($name) {
?>
<img alt="Corregir texto" class="phpwc_img" id="phpwc_cm<?=$name?>" src="/functions/ortografia/img/corregir.png" onClick="phpwc_cambiamodo(this,'<?=$name?>')">
<?
}

function phpwc_boton_idioma($name) {
?>
<img alt="Cambiar idioma" class="phpwc_img" id="phpwc_ci<?=$name?>" src="/functions/ortografia/img/idioma.gif" onClick="phpwc_cambiaidioma(this,'<?=$name?>')">
<?
}

function phpwc_comparar($valores) {
	global $corregir;

	$encontrado = 0;
	if (!is_array($corregir) || !array_search(strtolower($valores[0]), $corregir))
		$corregir[] = strtolower($valores[0]);
	return "@".$valores[0]."@";
}

function phpwc_corregir($texto, $idioma, $num) {
	global $corregir;

	$corregir = array();
	$texto = str_replace("@", "%$0--", $texto);
	$texto = stripslashes($texto);
	$texto = utf8_decode($texto);
	$texto = preg_replace_callback("([a-zA-ZáéíóúàèìòùñçÑÇüÜïÏ·]+)", "phpwc_comparar", $texto);
	$errores = array();
	$contador = 0;
	if (count($corregir))
		sort($corregir);
	else
		return;

	$diccionario = @fopen($_SERVER["DOCUMENT_ROOT"]."/functions/ortografia/diccionarios/".$idioma."/diccionario.dic", "r");
	while (!feof($diccionario)) {
		$palabra = str_replace("\n", "", fgets($diccionario));
		while ($palabra >= $corregir[$contador]) {
			if ($palabra != $corregir[$contador])
				$errores[] = $contador++;
			else
				$contador++;

			if ($contador >= count($corregir))
				break 2;
		}
	}

	$texto = htmlspecialchars($texto);
	foreach ($errores as $error)
		$texto = eregi_replace("@".$corregir[$error]."@", '<span onclick="phpwc_sugerir(this,\''.$num.'\')" class="phpwc_error">'.$corregir[$error].'</span>', $texto);

	$texto = str_replace("@", "", $texto);
	$texto = str_replace("%$0--", "@", $texto);
	echo utf8_encode(nl2br($texto));
}

function phpwc_idiomas($num) {
	$directorio = opendir("diccionarios"); 
	while ($archivo = readdir($directorio))
		if(($archivo != ".") && ($archivo != ".."))
			echo '<div class=\"phpwc_sugerencia\" onclick="phpwc_seleccionidioma(\''.$archivo.'\',\''.$num.'\')">'.$archivo.'</div>';

	closedir($directorio); 
}

function phpwc_init() {
?>
<link rel="stylesheet" type="text/css" href="/functions/ortografia/style.css" />
<script src="/functions/ortografia/phpwebcorrect.js"></script>
<div class="phpwc_sugerencias" id="phpwc_sugerencias" onClick="phpwc_ocultarsug()"></div>
<?
}

function phpwc_sugerir($error, $idioma) {
	error_reporting(E_ALL);

	$error = utf8_decode($error);
	$sugerido = 0;
	$diccionario = @fopen($_SERVER["DOCUMENT_ROOT"]."/functions/ortografia/diccionarios/".$idioma."/diccionario.dic", "r");
	while (!feof($diccionario)) {
		$palabra = str_replace("\n", "", fgets($diccionario));
		if (levenshtein($palabra, $error) <= 1 + (strlen($error) / 7)) {
			$palabra = utf8_encode($palabra);
			echo "<div class=\"phpwc_sugerencia\" onclick=\"phpwc_cambiar('$palabra')\">$palabra</div>";
			$sugerido++;
		}
	}  
	if (!$sugerido)
		echo utf8_encode('<span style="background:#ff0;">No hay sugerencias</span>');
}

function phpwc_textarea($width, $height, $name, $value) {
?>
<br>
<div class="phpwc_div" onclick="phpwc_ocultarsug()" id="phpwcDiv_<?=$name?>" style="width: <?=($width-8)?>; height: <?=($height-8)?>;"></div>
<textarea class="phpwc_textarea" name="<?=$name?>" id="phpwcText<?=$name?>" style=" width: <?=$width?>; height: <?=$height?>;"><?=$value?></textarea>
<?
}


if (isset($_REQUEST["consulta"])) {
	if (!isset($_REQUEST["idioma"]) || ($_REQUEST["idioma"] == "undefined"))
		$_REQUEST["idioma"] = "es";

	switch ($_REQUEST["consulta"]) {
		case "corregir":
			phpwc_corregir($_REQUEST["texto"], $_REQUEST["idioma"], $_REQUEST["num"]);
			break;
		case "sugerir":
			phpwc_sugerir($_REQUEST["palabra"], $_REQUEST["idioma"]);
			break;
		case "idiomas":
			phpwc_idiomas($_REQUEST["num"]);
			break;
	}
}
?>