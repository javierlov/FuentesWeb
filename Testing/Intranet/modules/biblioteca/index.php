<?
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/biblioteca/get_grid.php");


$autor = "";
if (isset($_REQUEST["autor"]))
	$autor = $_REQUEST["autor"];

$estado = "";
if (isset($_REQUEST["estado"]))
	$estado = $_REQUEST["estado"];

$isbn = "";
if (isset($_REQUEST["isbn"]))
	$isbn = $_REQUEST["isbn"];

$tema = "";
if (isset($_REQUEST["tema"]))
	$tema = $_REQUEST["tema"];

$titulo = "";
if (isset($_REQUEST["titulo"]))
	$titulo = $_REQUEST["titulo"];
?>
<script>
	showTitle(true, 'Solicitud Préstamo de Libros');
</script>
<div id="datos" style="height:180px;">
	<form action="<?= $_SERVER["PHP_SELF"]?>" id="formBuscar" method="get" name="formBuscar" target="_self" onSubmit="return ValidarForm(formBuscar)">
		<input id="buscar" name="buscar" type="hidden" value="yes" />
		<input id="pageid" name="pageid" type="hidden" value="59" />
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="FormLabelAzul" width="200" align="right">Titulo</td>
				<td width="32"></td>
				<td><input class="FormInputText" id="titulo" maxlength="250" name="titulo" size="50" type="text" value="<?= $titulo?>"></td>
			</tr>
			<tr>
				<td colspan="3" height="4"></td>
			</tr>
			<tr>
				<td class="FormLabelAzul" width="200" align="right">Autor</td>
				<td width="32"></td>
				<td><input class="FormInputText" id="autor" maxlength="250" name="autor" size="50" type="text" value="<?= $autor?>"></td>
			</tr>
			<tr>
				<td colspan="3" height="4"></td>
			</tr>
			<tr>
				<td class="FormLabelAzul" width="200" align="right">Tema</td>
				<td width="32"></td>
				<td><input class="FormInputText" id="tema" maxlength="2000" name="tema" size="50" type="text" value="<?= $tema?>"></td>
			</tr>
			<tr>
				<td colspan="3" height="4"></td>
			</tr>
			<tr>
				<td class="FormLabelAzul" width="200" align="right">I.S.B.N.</td>
				<td width="32"></td>
				<td><input class="FormInputText" id="isbn" maxlength="50" name="isbn" size="50" type="text" value="<?= $isbn?>"></td>
			</tr>
			<tr>
				<td colspan="3" height="4"></td>
			</tr>
			<tr>
				<td class="FormLabelAzul" width="200" align="right">Estado</td>
				<td width="32"></td>
				<td><input class="FormInputText" id="estado" maxlength="20" name="estado" size="50" type="text" value="<?= $estado?>"></td>
			</tr>
			<tr>
				<td colspan="3" height="4"></td>
			</tr>
			<tr>
				<td class="FormLabelAzul" width="200" align="right">&nbsp;</td>
				<td width="32">&nbsp;</td>
				<td><input class="BotonBlanco" type="submit" value="Buscar"></td>
			</tr>
			<tr>
				<td class="FormLabelAzul" align="right" colspan="3"><hr color="#C0C0C0" size="1"></td>
			</tr>
		</table>
	</form>
</div>
<div align="center" id="divContent" name="divContent">
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] = "yes"))
	getGrid($autor, $estado, $isbn, $tema, $titulo);
?>
</div>
<?
if (HasPermiso(60)) {
?>
<div>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align="center"><input class="BotonBlanco" type="button" value="Agregar Libros" onClick="window.location.href='/index.php?pageid=60'"></td>		
		</tr>
	</table>
</div>
<?
}
?>