function aceptarMsgError() {
	document.getElementById('divMsgError').style.display = 'none';
}

function aceptarMsgOk() {
	document.getElementById('divMsgOk').style.display = 'none';
}

function AddItemToDropDown(oDropDown, cValue, cText, bDisabled) {
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

function ajustarAltoMenu() {
	// Ajusto el alto del menú al alto del contenido..
	with (document)
		if ((getElementById('divContenido') != null) && (getElementById('divMenu') != null))
			if (getElementById('divContenido').offsetHeight > getElementById('divMenu').offsetHeight)
				getElementById('divMenu').style.height = getElementById('divContenido').offsetHeight + 'px';
}

function ajustarIframeComentarios() {
	with (top.document.getElementById('iframeComentarios'))
		height =  contentWindow.document.body.scrollHeight + "px";
}

function buscarEnTodaLaIntranet(validarEnter) {
	if (validarEnter) {
		var keyCode = event.which;
		if (keyCode == undefined)
			keyCode = event.keyCode;
		buscar = (keyCode == 13);
	}
	else
		buscar = true;

	if (buscar)
		with (document)
			if (getElementById('busquedaGeneral').value != '')
				window.location.href = '/buscar/' + escape(getElementById('busquedaGeneral').value);
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

function capitalize(s) {
	return s[0].toUpperCase() + s.slice(1);
}

function cargarImagenHeader() {
//	iframeGeneral.location.href = '/functions/cargar_imagen_header.php?ancho=' + document.getElementById('divImagenCabecera').offsetWidth;
}

function checkMaxLength(obj) {
	var mlength = obj.getAttribute?parseInt(obj.getAttribute("maxlength")):"";
	if (obj.getAttribute && obj.value.length > mlength)
		obj.value = obj.value.substring(0, mlength);
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

function eliminarComentario(id) {
	if (confirm('¿ Realmente desea eliminar este comentario ?')) {
		document.getElementById('id').value = id;
		document.getElementById('formEliminarComentario').submit();
	}
}

function FormatFloat(num) {
	var tempResult = Math.round(num * 100);    // calculo general sin perder precision..
	var integerDigits = Math.floor(tempResult / 100);    // extraer la parte no decimal..
	var decimalDigits = "" + (tempResult - integerDigits * 100);    // extraer la parte decimal..

	while (decimalDigits.length < 2)    // formatear la parte decimal a dos digitos ..
		decimalDigits = "0" + decimalDigits;

	return integerDigits + "." + decimalDigits;    // componer la cadena resultado..
}

function getAbsoluteElementPosition(element) {
	if (typeof element == "string")
		element = document.getElementById(element);

	if (!element)
		return {top:0, left:0};

	var y = 0;
	var x = 0;
	while (element.offsetParent) {
		x += element.offsetLeft;
		y += element.offsetTop;
		element = element.offsetParent;
	}

	return {top:y, left:x};
}

function getItemIndex(combo, value) {
	for (var i=0; i<=combo.length-1;i++)
		if (combo.options[i].value == value)
			return i;

	return 0;
}

function getNumeroDiasentreFechas(f1, f2) {
	var d1 = f1.split("/");
	var dat1 = new Date(d1[2], parseFloat(d1[1]) - 1, parseFloat(d1[0]));

	var d2 = f2.split("/");
	var dat2 = new Date(d2[2], parseFloat(d2[1]) - 1, parseFloat(d2[0]));

	var fin = dat2.getTime() - dat1.getTime();
	var dias = Math.floor(fin / (1000 * 60 * 60 * 24))

	return dias;
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

function logUrlOut() {
	url = '/functions/log_url_out.php?id=' + document.getElementById('idEstadistica').value;

	if (document.getElementsByTagName('iframe').length > 0)		// Si hay un iframe abro en un iframe, sino en una ventana nueva..
		document.getElementsByTagName('iframe')[document.getElementsByTagName('iframe').length - 1].src = url;
	else {
		features = 'width=1,height=1,left=9999,top=9999';
		name = '_blank';
	
		window.open(url, name, features);
	}
}

function onLoadBody() {
	ajustarAltoMenu();
//	cargarImagenHeader();

	window.onbeforeunload = function() {
		setTimeout("logUrlOut()", "10");
//		return '¿?';
	}
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

function readAttributeFromCssClassFile(file, className, attribute) {
	for (var i = 0; i < document.styleSheets.length; i++)
		if (document.styleSheets[i].href.substr(-file.length) == file) {
			var classes = document.styleSheets[i].rules || document.styleSheets[i].cssRules;
			for (var j = 0; j < classes.length; j++)
				if (classes[j].selectorText == className)
					return classes[j].style[attribute];
		}

	return '';
}

function redirectToGestionSistemas() {
	topUrl = top.location.href;
	if (getUrlParamValue(window.location.href, "pageid") == '') {
		params = topUrl.substr(topUrl.indexOf('?') + 1, 10000);
		redirect = getUrlParamValue(topUrl, "gs");
		if (redirect == 't')
			window.location.href = '/index.php?pageid=38&' + params;
	}
}

function RemoveItemsToDropDown(oDropDown) {
	while (oDropDown.length > 0)
		oDropDown.remove(0);
}

function resizeBody() {
//	cargarImagenHeader();		// Esta linea puede hacer lenta la pc..
	if (document.getElementById('slider') != null)
		setImagenesGrandes(document.getElementById('slider').offsetWidth);
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

function showError(msg, win) {
	with (win.document) {
		body.style.cursor = 'default';

		if (getElementById('imgProcesando') != null)
			getElementById('imgProcesando').style.display = 'none';
		if (getElementById('btnGuardar') != null)
			getElementById('btnGuardar').style.display = 'inline';

		getElementById('divMsgErrorTexto').innerText = msg;
		getElementById('divMsgError').style.display = 'block';
	}
}

function showMsgOk(url, win) {
	function redirect(url, win) {
		win.location.href = url;
	}

	setTimeout(function() {redirect(url, win);}, 2000);

	with (win.document) {
		body.style.cursor = 'default';
		getElementById('imgProcesando').style.display = 'none';
		getElementById('divMsgOk').style.display = 'block';
	}
}

function showPermisosWindow(pageId) {
	if ((document.getElementById('iPaginaPublica').value == 't') || (pageId == -1))
		alert('Esta página es pública por lo tanto no se le puede configurar permisos.');
	else
		window.open('/permisos/' + pageId, 'ProvartPopup', '');
}


var _x;
var _y;
var isIE = document.all?true:false;

document.onmousemove = getMousePosition;


function getMousePosition(event) {
	if (isIE) {
		_x = event.clientX + document.body.scrollLeft;
		_y = event.clientY + document.body.scrollTop;
	}
	else {
		_x = event.pageX;
		_y = event.pageY;
	}

	posX = _x;
	posY = _y;

	var  pos = Array(posX, posY);

	return pos;
}

function keyDown(event) {
var keyCode = event.which;
	if (keyCode == undefined)
		keyCode = event.keyCode;

	if (keyCode == 17)
		isCtrl = true;

// ***  VISOR DE IMÁGENES  -  INICIO..
	if (isVisorImagenesVisible()) {
		if (keyCode == 27) {
			cerrarVisor = true;
			cerrarVisorImagenes()
		}

		if ((keyCode == 37) && (isFlechaAnteriorVisible())) {
			cerrarVisor = false;
			document.getElementById('divVisorImagenesFlechaAnterior').click();
			cerrarVisor = true;
		}

		if (keyCode == 38) {
			mostrarImagen(0);
		}

		if ((keyCode == 39) && (isFlechaSiguienteVisible())) {
			cerrarVisor = false;
			document.getElementById('divVisorImagenesFlechaSiguiente').click();
			cerrarVisor = true;
		}

		if (keyCode == 40) {
			mostrarImagen(arrVisorImagenes.length - 1);
		}
	}
// ***  VISOR DE IMÁGENES  -  FIN..
}

function keyUp(e) {
	if (e.keyCode == 17)
		isCtrl = false;
}