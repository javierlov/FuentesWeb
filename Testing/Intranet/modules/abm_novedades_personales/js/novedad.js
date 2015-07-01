function darBaja() {
	if (confirm('¿ Realmente desea dar de baja este registro ?')) {
		document.getElementById('tipoOp').value = 'B';
		document.getElementById('formNovedad').submit();
	}
}