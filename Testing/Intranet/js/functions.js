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


	//si el a�o de la fecha que recibo solo tiene 2 cifras hay que cambiarlo a 4
	if (ano <= 99)
		ano+=1900;

	//resto los a�os de las dos fechas
	var edad = hoy.getYear()- ano - 1; //-1 porque no se si ha cumplido a�os ya este a�o

	//si resto los meses y me da menor que 0 entonces no ha cumplido a�os. Si da mayor si ha cumplido
	if (hoy.getMonth() + 1 - mes < 0) //+ 1 porque los meses empiezan en 0
		return edad;
	if (hoy.getMonth() + 1 - mes > 0)
		return edad + 1

	//entonces es que eran iguales. miro los dias
	//si resto los dias y me da menor que 0 entonces no ha cumplido a�os. Si da mayor o igual si ha cumplido
	if (hoy.getUTCDate() - dia >= 0)
		return edad + 1;

	return edad;
}

function CancelAndCloseWindow() {
	if (window.confirm('Est� a punto de cancelar la operaci�n. � Desea continuar ?'))
		window.close();
}

function checkMaxLength(obj) {
	var mlength = obj.getAttribute?parseInt(obj.getAttribute("maxlength")):"";
	if (obj.getAttribute && obj.value.length > mlength)
		obj.value = obj.value.substring(0, mlength);
}

function CloseWindow() {
	if (window.confirm('Est� a punto de cerrar la ventana. � Desea continuar ?'))
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

function FormatFloat(num) {
	var tempResult = Math.round(num * 100);    // calculo general sin perder precision..
	var integerDigits = Math.floor(tempResult / 100);    // extraer la parte no decimal..
	var decimalDigits = "" + (tempResult - integerDigits * 100);    // extraer la parte decimal..

	while (decimalDigits.length < 2)    // formatear la parte decimal a dos digitos ..
		decimalDigits = "0" + decimalDigits;

	return integerDigits + "." + decimalDigits;    // componer la cadena resultado..
}

function getItemIndex(combo, value) {
	for (var i=0; i<=combo.length-1;i++)
		if (combo.options[i].value == value)
			return i;

	return 0;
}

function getUrlParamValue(vUrl, name) {
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]" + name + "=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(vUrl);
	if (results == null)
		return "";
	else
		return results[1];
}

function isDigit(c) {
// Devuelve true si el caracter pasado como par�metro es un entero..  
	return ((c >= "0") && (c <= "9"));
}

function IsTopLevel(win) {
// Devuelve true si la ventana pasada como par�metro es el Top Level..
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

function verComentarios(idmodulo, idarticulo) {
	height = 200;
	width = 600;

	var left = _x;
	var top = _y + 8;

	if (document.body.offsetWidth < (left + width))
		left = document.body.offsetWidth - width - 20;
	if (left < 0)
		left = 8;

	if ((document.body.offsetHeight - 48) < (top + height))
		top = document.body.offsetHeight - height - 48;
	if (top < 0)
		top = 8;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxComentarios', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1, scrolling=1');
	divWin.load('iframe', '/functions/view_comments.php?idmodulo=' + idmodulo + '&idarticulo=' + idarticulo, '*');
	divWin.show();
}