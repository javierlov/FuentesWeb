function darBaja() {
	if (confirm('¿ Realmente desea dar de baja este registro ?')) {
		document.getElementById('TipoOp').value = 'B';
		document.getElementById('formArticulo').submit();
	}
}