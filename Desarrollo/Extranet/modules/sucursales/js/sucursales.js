function verMapa(elem) {
	var direccion = elem.childNodes[1].innerHTML + ' ' + elem.childNodes[3].innerHTML;
	direccion = direccion.replace('Local 1', '');
	direccion = direccion.replace('Alsina 443 B6450FAI | Pehuajó', 'Pehuajo, Buenos Aires');
	direccion = direccion.replace('esquina Avellaneda 345', '');
	direccion = direccion.replace('of. H3 – Complejo Capitalinas', '');
	direccion = direccion.replace('Av. 25 de Mayo', '25 de Mayo');
	direccion = direccion.replace('Gualeguay', 'Rio Gualeguay');
	direccion = direccion.replace('Entre Ríos', 'Parana, Entre Ríos');
	direccion+= ', Argentina';

	var height = 376;
	var width = 736;
	var left = ((window.innerWidth - width) / 2) + 88;
	var top = 184;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxMapa', 'iframe', '/test.php', 'Mapa', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/sucursales/ver_mapa.php?d=' + direccion, 'Mapa Sucursal ' + elem.previousSibling.previousSibling.firstChild.innerHTML);
	divWin.show();
}