<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


function BuildTable($title, $conn, $sql, $rowTitles, $editableFields, $visibleFields, $url, $buttons, $buttonsActions, $pregunta, $addButton, $requiredFields = false,
										$validarEntero = false, $validarNumero = false, $valoresHtml = false, $inputTypes = false, $decodeSpecialChars = false) {
?>
	<script type="text/javascript">
		function addField(dest, name) {
			var now = new Date();
			var id = now.getHours().toString() + now.getMinutes().toString() + now.getSeconds().toString() + now.getMilliseconds().toString();

			var tr = document.createElement('tr');
			var td = document.createElement('td');
			td.width = 4;

			// Agrego el input..
			var control = document.getElementById(name).cloneNode(false);
			control.id = name + '_' + id;
			control.name = name + '_' + id;
			control.value = '';
			td.appendChild(control);
			tr.appendChild(td);

			var td = document.createElement('td');

			// Agrego la imagen..
			var img = document.createElement('img');
			img.alt = 'Quitar';
			img.border = 0;
			img.src = '/images/quitar.gif';
			img.onclick = function deleteField() {
				dest.removeChild(tr);
			}
			td.appendChild(img);
			tr.appendChild(td);
			
			dest.appendChild(tr);
			control.focus();
		}

		function isDigit(c) {
		// Devuelve true si el caracter pasado como parámetro es un entero..  
			return ((c >= "0") && (c <= "9"));
		}

		function reemplazarPuntoXComa(field) {
			if (field.value.indexOf(',') > -1)
				field.value = field.value.replace(',', '.');
		}

		function validar() {
			for (i=0; i<builtForm.elements.length; i++) {
				var field = builtForm.elements[i];

				if (document.getElementById('titulo_' + field.id) != null)
					document.getElementById('titulo_' + field.id).style.color = '000';

				if (field.getAttribute('validar') == 'true') {
					if (field.value == '') {
						document.getElementById('titulo_' + field.id).style.color = 'f00';
						alert('Por favor complete el campo ' + field.title + '.');
						field.focus();
						return false;
					}
				}

				if (field.getAttribute('validarEntero') == 'true') {
					if (!validarEntero(field.value)) {
						document.getElementById('titulo_' + field.id).style.color = 'f00';
						alert('Por favor ingrese un valor entero válido.');
						field.focus();
						return false;
					}
				}

				if (field.getAttribute('validarNumero') == 'true') {
					if (!validarNumero(field.value)) {
						document.getElementById('titulo_' + field.id).style.color = 'f00';
						alert('Por favor ingrese un valor numérico válido.');
						field.focus();
						return false;
					}
				}
			}

			return true;
		}

		function validarEntero(value) {
			var i;

			for (i=0; i<value.length; i++) {   
				var c = value.charAt(i);
				if (i != 0) {
					if (!isDigit(c))
						return false;
				}
				else
					if (!isDigit(c) && (c != '-') || (c == '+'))
						return false;
			}

			return true;
		}

		function validarNumero(value) {
			aFloat = Number(value);

			return (!isNaN(aFloat));
		}
	</script>
<!--
  <table width="100%">
    <tr>
      <td>
        <h1><img src="/images/logo_provart.jpg"><br><?= $title ?></h1>
      </td>
    </tr>
  </table>
-->
	<table class="Width600 GrisOscuro" cellpadding="0">
		<tr>
			<td width="5%"><img src="/images/01.jpg"></td>
			<td class="Title01" width="90%"><?= $title ?></td>
			<td width="5%"><img src="/images/02.jpg"></td>
		</tr>
	</table>

	<form action="<?= $url ?>" id="builtForm" method="post">
		<table class="Width560 GrisClaro">
			<tr>
				<td class="CS40 GrisClaro" colspan=2></td>
			</tr>
<?
	$stmt = DBExecSql($conn, $sql);
	$row = DBGetQuery($stmt, 1);

	$i = 0;
	$foco = ''; 
	foreach ($row as $k => $v) {
		if (($foco == '') and ($editableFields[$i] == 1))
			$foco = $k;
?>
		<tr>
			<td valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="216px">
					<tr>
						<td id="titulo_<?= $k ?>" class="ItTit" style="color:000"><?= $rowTitles[$i] ?> <span id="ñ">&gt;</span></td>
<?
		if ($addButton[$i] == 1) {
?>
						<td><img border="0" src="/images/agregar.gif" title="Agregar" onClick="addField(document.getElementById('table<?= $k?>'), '<?= $k?>');"></td>
<?
		}
		else
			echo "<td></td>";
?>
					</tr>
				</table>
			</td>
			<td>
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody id="table<?= $k ?>" name="table<?= $k ?>">
						<tr>
							<td>
<?
		if (($valoresHtml) and ($valoresHtml[$i] == 1)) {
			if ($decodeSpecialChars[$i])
				echo htmlspecialchars_decode($v, ENT_QUOTES);
			else
				echo $v;
		}
		else {
?>
								<input
									class="ItInput"
									id="<?= $k ?>"
									name="<?= $k ?>"
									size="<?= strlen($v) + strlen($v) * 0.5 ?>"
							 		value="<?= trim($v) ?>"
												 <?= ($editableFields[$i] == 0)?" READONLY":"" ?>
												 <?= ($editableFields[$i] == 0)?" style='background-color:#EEEEEE'":"" ?>
												 <?= ($visibleFields[$i]  == 0)?" type=hidden size='0'":"type=".(($inputTypes)?$inputTypes[$i]:"text") ?>
												 <?= (($requiredFields) and ($requiredFields[$i] == 1))?" validar='true'":"" ?>
												 <?= (($validarEntero) and ($validarEntero[$i] == 1))?" validarEntero='true'":"" ?>
												 <?= (($validarNumero) and ($validarNumero[$i] == 1))?" validarNumero='true'":"" ?>
          			>
									<script type="text/javascript">
										var field = document.getElementById("<?= $k ?>");
										field.title = "<?= $rowTitles[$i] ?>";
									</script>
<?
		}
?>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
<?
		$i++;
	}

	if ($pregunta != "") {
?>
		<tr height="24">
			<td></td>
			<td style="font-size:14px; width:180px;" valign="bottom">&nbsp;&nbsp;<b><?= $pregunta?></b></td>
		</tr>
<?
	}
?>
			<tr>
				<td></td>
				<td class="CS40 GrisClaro">
					<input type="hidden" value="<?= $_REQUEST["TRANSACCION"] ?>" id="TRANSACCION" name="TRANSACCION">
					<input type="hidden" value="<?= $_REQUEST["USERNAME"] ?>" id="USERNAME" name="USERNAME">
					<input type="hidden" value="<?= $title ?>" id="TITLE" name="TITLE">
<?
	$i = 0;
	foreach ($buttons as $btn) {
		echo "<input class='Submit' type=button value='".$btn."' onClick='".$buttonsActions[$i]."();'>";
		$i++;
	}
?>
				</td>
			</tr>
		</table>
	</form>
	<table class="Width600 Celeste">
		<tr>
			<td width="5%"><img src="/images/03.jpg"></td>
			<td width="90%"></td>
			<td width="5%"><img src="/images/04.jpg"></td>
		</tr>
	</table>
	<script type="text/javascript">
<?
	if ($foco != '')
    echo "document.getElementById('".$foco."').focus();";
?>
	</script>
<?
	DBCloseConnection($stmt);
}
?>