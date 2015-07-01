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

function ajustarTamanoIframe(iFrame, altoBase) {
	if (iFrame.contentWindow == undefined)
		return;

	var code = iFrame.contentWindow.document.body.innerHTML;
	var cant = 0;

	while (code.indexOf('GridFondoOnMouseOver') != -1) {
		cant++;
		code = code.substr(code.indexOf('GridFondoOnMouseOver') + 2);
	}

	if (cant == 0)
		iFrame.height = altoBase;
	else
		iFrame.height = altoBase + 40 + (cant * 39);
}

function AddItemToDropDown(oDropDown, cValue, cText) {
// Agrega un item a un combo..
	var elOptNew = document.createElement('option');
	elOptNew.value = cValue;
	elOptNew.text = cText;

	var elSel = document.getElementById(oDropDown);
	try {
		elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
	}
	catch(ex) {
		elSel.add(elOptNew); // IE only
	}
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

function CalcularEdad(fecha){
	//calculo la fecha de hoy
	hoy = new Date();

	//calculo la fecha que recibo
	//La descompongo en un array
	var array_fecha = fecha.split("/");

	//si el array no tiene tres partes, la fecha es incorrecta
	if (array_fecha.length != 3)
		return false;

	//compruebo que los ano, mes, dia son correctos
	var ano;
	ano = parseInt(array_fecha[2]);
	if (isNaN(ano))
		return false;

	var mes;
	mes = parseInt(array_fecha[1]);
	if (isNaN(mes))
		return false;

	var dia;
	dia = parseInt(array_fecha[0]);
	if (isNaN(dia))
		return false;


	//si el año de la fecha que recibo solo tiene 2 cifras hay que cambiarlo a 4
	if (ano <= 99)
		ano+=1900;

	//resto los años de las dos fechas
	var edad = hoy.getYear()- ano - 1; //-1 porque no se si ha cumplido años ya este año

	//si resto los meses y me da menor que 0 entonces no ha cumplido años. Si da mayor si ha cumplido
	if (hoy.getMonth() + 1 - mes < 0) //+ 1 porque los meses empiezan en 0
		return edad;
	if (hoy.getMonth() + 1 - mes > 0)
		return edad + 1

	//entonces es que eran iguales. miro los dias
	//si resto los dias y me da menor que 0 entonces no ha cumplido años. Si da mayor o igual si ha cumplido
	if (hoy.getUTCDate() - dia >= 0)
		return edad + 1;

	return edad;
}

function CancelAndCloseWindow() {
	if (window.confirm('Está a punto de cancelar la operación. ¿ Desea continuar ?'))
		window.close();
}

function CloseWindow() {
	if (window.confirm('Está a punto de cerrar la ventana. ¿ Desea continuar ?'))
		window.close();
}

function ComparaItems(a, b) {
	return (a[0] < b[0]?"-1":"1");
}

function ConfirmDelete(link, texto) {
  result = confirm(texto);
  if (result)
    window.location.href = link;

  return result;  
}

function daysInMonth(humanMonth, year) {
	return new Date(year || new Date().getFullYear(), humanMonth, 0).getDate();
}

function formatNumber(nStr, decimalSeparator, thousandSeparator) {
	if (decimalSeparator === undefined)
		decimalSeparator = '.';
	if (thousandSeparator === undefined)
		thousandSeparator = ',';

	nStr += '';
	x = nStr.split(decimalSeparator);
	x1 = x[0];
	x2 = x.length > 1 ? decimalSeparator + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1))
		x1 = x1.replace(rgx, '$1' + thousandSeparator + '$2');

	return x1 + x2;
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

function getUrlParamValue(vUrl, name) {
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]" + name + "=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(vUrl);
	if(results == null)
		return "";
	else
		return results[1];
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

function IsTopLevel(win) {
// Devuelve true si la ventana pasada como parámetro es el Top Level..
  return (win.parent == win);
}

function LimpiarForm(form) {
  for (i=0; i<form.elements.length; i++)
    if ((form.elements[i].type == "text") || (form.elements[i].type == 'textarea'))
        form.elements[i].value = "";
    else if (form.elements[i].type == "checkbox")
      form.elements[i].checked = false;
    else
      form.elements[i].selectedIndex = 0;  
}

function openUrlFromFlash(sUrl) {
	window.location.href = sUrl.replace('&amp;', '&');
}

function OpenWindow(url, name, width, height, scrollbars, resizeable) {
// Abre una ventana centrada..
  medioancho = (screen.width - width) / 2;
  medioalto = (screen.height - height) / 2;
  features = 'width=' + width + ',height=' + height + ',left=' + medioancho + ',top=' + medioalto + ',scrollbars=' + scrollbars + ',resizeable=' + resizeable;

  window.open(url, name, features);
  return false;
}

function OrdenarCombo(o) {
	var v = new Array();
	
	for (var i=0; i<o.options.length; i++)
		v[v.length] = new Array(o[i].text, o[i].value);

	v.sort(ComparaItems);

	for (var i=0; i<o.options.length; i++)
		o[i] = new Option(v[i][0], v[i][1], false, false);
}

function PrintWebPage() {
	window.print();
}

function reemplazarPuntoXComa(field) {
	if (field.value.indexOf(',') > -1)
		field.value = field.value.replace(',', '.');
}

function RemoveItemsToDropDown(oDropDown) {
  while (oDropDown.length > 0)
    oDropDown.remove(0);
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

function SetPageHeight() {
  document.getElementById("tableMain").height = document.body.offsetHeight - 33;
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