<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


$files = array();
$ano = date("Y");
$mes = date("m");
$dir = DATA_SEGURIDAD_INFORMATICA_PATH.$_REQUEST["ano"]."/".$_REQUEST["mes"]."/";
$relativeDir = DATA_SEGURIDAD_INFORMATICA_RELATIVE_PATH.$_REQUEST["ano"]."/".$_REQUEST["mes"]."/";
if (is_dir($dir))
	if ($gd = opendir($dir)) {
		while (($file = readdir($gd)) !== false)
			if (($file != ".") and ($file != ".."))
				array_push($files, $file);
		closedir($gd);
	}
rsort($files, SORT_NUMERIC);
?>
<script>
	showTitle(true, 'BOLETINES DE SEGURIDAD INFORMÁTICA');
</script>
<body link="#00539B" vlink="#00539B" alink="#00539B">

<div align="center">
	<table border="0" cellspacing="0" height="16" width="339" id="table1">
		<tr>
			<td align="center" height="1" width="433" colspan="2"><p align="center" style="margin-top: 0; margin-bottom: 0"><font face="Neo Sans" color="#807F84" size="3"><b>Boletines del Mes de <?= GetMonthName($_REQUEST["mes"])?> de <?= $_REQUEST["ano"]?></b></font></p><hr size="1" color="#807F84"></td>
		</tr>
	</table>
	<table cellspacing="0" height="16" width="339" id="table1">
<?
foreach ($files as $value) {
	$arr = explode(".", $value);
?>
		<tr>
			<td style="border: 1 solid #C0C0C0" align="center" height="17" width="17"><a target="_blank" href="<?= $relativeDir.$value?>"><img border="0" src="/modules/sistemas/boletines_seguridad_informatica/flecha.gif" width="16" height="16"></a></td>
			<td align="left" style="background-color: #807F84; border: 1 solid #C0C0C0" height="1" width="314"><font face="Neo Sans" color="#FFFFFF" size="1"><b>
			&nbsp;Boletín Nº <?= $arr[0]?></b></font></td>
		</tr>

<tr>
<td height="1px"></td>
</tr>
<?
}
?>
	</table>
</div>

<p>&nbsp;</p>
<p align="center"><a href="index.php?pageid=27" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>
</body>