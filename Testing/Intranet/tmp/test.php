<?
$max_cell = 50;
?>
<html>
	<script>
		var MAX_CELL = <?= $max_cell?>;
		var isMouseDown = false;
		var xDesde = 0;
		var yDesde = 0;

		function Aplicar() {
			alert('No anda bien...');
			BeginProc(document.getElementById('xDesde').value, document.getElementById('yDesde').value);
			EndProc(document.getElementById('xHasta').value, document.getElementById('yHasta').value);
		}

		function ClearSelection() {
			if (document.selection)
				document.selection.empty();
			else if (window.getSelection)
				window.getSelection().removeAllRanges();
		}

		function DisableSelect(e) {
			return false;
		}

		function BeginProc(x, y) {
			isMouseDown = true;
			xDesde = x;
			yDesde = y;

			ClearAll();
			document.getElementById('xDesde').value = xDesde;
			document.getElementById('yDesde').value = yDesde;			
		}

		function ClearAll() {
			for (i=1;i<=MAX_CELL;i++)
				for (j=1;j<=MAX_CELL;j++)
					document.getElementById(j + '_' + i).style.backgroundColor = '#FFFFFF';
		}

		function EndProc(x, y) {
			isMouseDown = false;
			PaintBlock(x, y);
		}

		function MouseOut(x, y) {
//			if (isMouseDown)
//				PaintBlock(x, y);
		}

		function MouseOver(x, y) {
			if (isMouseDown) {
				document.getElementById('xHasta').value = x;
				document.getElementById('yHasta').value = y;
				ClearSelection();
//				PaintBlock(x, y);
			}
		}

		function PaintBlock(x, y) {
			var xIni = 0;
			var xFin = 0;
			var yIni = 0;
			var yFin = 0;

			if (x < xDesde) {
				xIni = x;
				xFin = xDesde;
			}
			else {
				xIni = xDesde;
				xFin = x;
			}
			if (y < yDesde) {
				yIni = y;
				yFin = yDesde;
			}
			else {
				yIni = yDesde;
				yFin = y;
			}

			for (i=1;i<=MAX_CELL;i++)
				for (j=1;j<=MAX_CELL;j++)
					if ((j >= xIni) && (j <= xFin) && (i >= yIni) && (i <= yFin))
						document.getElementById(j + '_' + i).style.backgroundColor = '#FF0000';
					else
						document.getElementById(j + '_' + i).style.backgroundColor = '#FFFFFF';
		}

		function ReEnable() {
			return true;
		}

		//document.onmouseout = EndProc;
		document.onselectstart = new Function ("return false")
		if (window.sidebar) {
			document.onmousedown = disableselect;
			document.onclick = reEnable;
		}
	</script>
	<body>
		<table border="1" cellpadding="0" cellspacing="0" onMouseOver="document.body.style.cursor='hand'" onMouseOut="document.body.style.cursor='default'">
<?
for ($y=1;$y<=$max_cell;$y++) {
?>
	<tr height="2">
<?
	for ($x=1;$x<=$max_cell;$x++) {
?>
		<td id=<?= $x?>_<?= $y?> style="font-size:2;width:3px" x=<?= $x?> y=<?= $y?> onMouseDown="BeginProc(<?= $x?>, <?= $y?>)" onMouseUp="EndProc(<?= $x?>, <?= $y?>)" onMouseOver="MouseOver(<?= $x?>, <?= $y?>)" onMouseOut="MouseOut(<?= $x?>, <?= $y?>)">&nbsp;</td>
<?
	}
}
?>
			</tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>X Desde:</td>
				<td><input id="xDesde" name="xDesde" size="5" type="text"></td>
				<td>Y Desde:</td>
				<td><input id="yDesde" name="yDesde" size="5" type="text"></td>
			</tr>
			<tr>
				<td>X Hasta:</td>
				<td><input id="xHasta" name="xHasta" size="5" type="text"></td>
				<td>Y Hasta:</td>
				<td><input id="yHasta" name="yHasta" size="5" type="text"></td>
			</tr>
			<tr>
				<td colspan="4"><input id="btnAplicar" name="btnAplicar" type="button" value="Aplicar" onClick="Aplicar()"></td>
			</tr>
		</table>
	</body>
</html>