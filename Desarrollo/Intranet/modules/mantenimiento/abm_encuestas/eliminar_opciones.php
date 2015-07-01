<script type="text/javascript">
	function eliminar(numeroPregunta, numeroOpcion) {
		with (window.parent.document) {
			getElementById('pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'Label').style.color = 'f00';
			getElementById('pregunta' + numeroPregunta + 'Opcion' + numeroOpcion).readOnly = true;
			getElementById('pregunta' + numeroPregunta + 'Opcion' + numeroOpcion).style.color = 'f00';
			getElementById('pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'Baja').value = 'T';
			getElementById('pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'BtnBaja').style.display = 'none';
			getElementById('pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'PO').style.display = 'none';
			getElementById('pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'PS').style.display = 'none';
			getElementById('pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'PI').style.display = 'none';
		}
	}


	var numeroPregunta = <?= $_REQUEST["numeropregunta"]?>;
<?
if ((isset($_REQUEST["eliminar"])) and ($_REQUEST["eliminar"] == "T")) {
?>
	// Oculto el botón que agrega mas opciones..
	window.parent.document.getElementById('pregunta' + numeroPregunta + 'BtnAgregar').style.visibility = 'hidden';
	window.parent.document.getElementById('pregunta' + numeroPregunta + 'Multi').checked = true;
	window.parent.document.getElementById('pregunta' + numeroPregunta + 'Multi').disabled = true;

	// Elimino todas las opciones que existieren..
	for (i=1; i<100; i++)		// No creo que haya mas de 100 opciones..
		if (window.parent.document.getElementById('pregunta' + numeroPregunta + 'Opcion' + i) != null)
			eliminar(numeroPregunta, i);
		else
			break;

	// Agrego la única opción posible..
	window.parent.agregarOpcion(numeroPregunta, -1, -1, 'Escriba su respuesta aquí', true, '', true);
<?
}
else {		// Habilito lo deshabilitado anteriormente..
?>
	// Obtengo la única opción válida..
	for (i=1; i<100; i++)		// No creo que haya mas de 100 opciones..
		if (window.parent.document.getElementById('pregunta' + numeroPregunta + 'Opcion' + i) == null)
			break;
	i--;

	window.parent.document.getElementById('pregunta' + numeroPregunta + 'BtnAgregar').style.visibility = 'visible';
	window.parent.document.getElementById('pregunta' + numeroPregunta + 'Multi').disabled = false;
	window.parent.document.getElementById('pregunta' + numeroPregunta + 'Opcion' + i + 'BtnBaja').style.visibility = 'visible';
	window.parent.document.getElementById('poPregunta' + numeroPregunta + 'Opcion' + i).disabled = false;
<?
}
?>
</script>