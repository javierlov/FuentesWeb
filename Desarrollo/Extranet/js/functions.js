function abrirVentanaTelefono(sesion, idModulo, id, idTablaPadre, tablaTel, campoClave, prefijo, tipo) {
	if (id < 1)
		caption = 'Alta de Teléfono';
	else
		caption = 'Modificación de Teléfono';

	height = 264;
	width = 400;

	var left = ((screen.width - width) / 2) + 52;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxRGRL', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1, scrolling=1');
	divWin.load('iframe', '/functions/telefonos/telefono.php?s=' + sesion + '&idModulo=' + idModulo + '&idTelefono=' + id + '&idTablaPadre=' + idTablaPadre + '&tablaTel=' + tablaTel + '&campoClave=' + campoClave + '&prefijo=' + prefijo + '&tipo=' + tipo, caption);
	divWin.show();
}
/*
function addItemToDropDown(oDropDown, cValue, cText, bDisabled) {
// Agrega un item a un combo..
	var elOptNew = document.createElement('option');
	elOptNew.value = cValue;
	elOptNew.text = cText;

	if (bDisabled)
		elOptNew.disabled = true;

	var elSel = document.getElementById(oDropDown);
	try {
		elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
	}
	catch(ex) {
		elSel.add(elOptNew); // IE only
	}
}
*/
function ajustarTamanoIframe(iFrame, altoBase) {
	if (iFrame.contentWindow == undefined)
		return;

	var code = iFrame.contentWindow.document.body.innerHTML;
	var cant = 0;

	while (code.indexOf('gridFondoOnMouseOver') != -1) {
		cant++;
		code = code.substr(code.indexOf('gridFondoOnMouseOver') + 2);
	}

	if (cant == 0)
		iFrame.height = altoBase;
	else
		iFrame.height = altoBase + 40 + (cant * 39);
}

function buscarDomicilio(buscarCalle, sinDatosConocidos, datosDomicilio, idprovincia, provincia, localidad, cpa, cp, calle, numero, piso, departamento, domicilioManual, height, width, left, top) {
	if (height == 0)
		height = 440;
	if (width == 0)
		width = 600;
	if (left == 0)
		left = ((screen.width - width) / 2) + 52;
	if (top == 0)
		top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxDomicilio', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/functions/buscar_domicilio.php?buscarcalle=' + iif(buscarCalle, 't', 'f') + '&objSinDatosConocidos=' + sinDatosConocidos + '&objDatosDomicilio=' + datosDomicilio + '&objDepartamento=' + departamento + '&objIdProvincia=' + idprovincia + '&objNumero=' + numero + '&objPiso=' + piso + '&objProvincia=' + provincia + '&objLocalidad=' + localidad + '&objCpa=' + cpa + '&objCp=' + cp + '&objCalle=' + calle + '&objDomicilioManual=' + domicilioManual, 'Buscar Domicilio');
	divWin.show();
}

function daysInMonth(humanMonth, year) {
	return new Date(year || new Date().getFullYear(), humanMonth, 0).getDate();
}

function getItemIndex(combo, value) {
	for (var i=0; i<=combo.length-1;i++)
		if (combo.options[i].value == value)
			return i;

	return 0;
}

function getMonthName(mes) {
	if (isNaN(mes))
		return '';
	if ((Number(mes) < 1) || (Number(mes) > 12))
		return '';

	var meses = new Array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	return meses[Number(mes) - 1];
}

function iif(condicion, valor1, valor2) {
	if (condicion)
		return valor1;
	else
		return valor2;
}

function isDigit(c) {
// Devuelve true si el caracter pasado como parámetro es un entero..  
  return ((c >= "0") && (c <= "9"));
}

function isTopLevel(win) {
// Devuelve true si la ventana pasada como parámetro es el Top Level..
  return (win.parent == win);
}

function openUrlFromFlash(sUrl) {
	window.location.href = sUrl.replace('&amp;', '&');
}

function recargarCaptcha(objImagen) {
	objImagen.src = '/functions/captcha.php?rnd=' + Math.random();
}

function reemplazarPuntoXComa(field) {
	if (field.value.indexOf(',') > -1)
		field.value = field.value.replace(',', '.');
}

function resizeTextarea(t) {
	if (!t.initialRows)
		t.initialRows = t.rows;
 
	a = t.value.split('\n');
	b = 0;
	for (x=0; x < a.length; x++)
		if (a[x].length >= t.cols)
			b+= Math.floor(a[x].length / t.cols);

	b+= a.length;
	b++;

	if (navigator.userAgent.toLowerCase().indexOf('opera') != -1)
		b+= 2;

	if (b > t.rows || b < t.rows)
		t.rows = (b < t.initialRows ? t.initialRows : b);
}

function StringToAscii(cadena) {
	var result = '';

	for (i = 0; i < cadena.length; i++) {
		character = cadena.substring(i, i + 1);
		var code = character.charCodeAt(0);
		result = result + (code * code) + '.';
	}

	return result;
}