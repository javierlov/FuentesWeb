function phpwc_ajax() {
	var obj;
	if (window.XMLHttpRequest)		// no es IE
		obj = new XMLHttpRequest();
	else		// Es IE o no tiene el objeto
		try {
			obj = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e) {
			alert('El navegador utilizado no está soportado');
		}

	return obj;
}

function phpwc_cambiaidioma(to, n) {
	if (phpwc_ready) {
		var x = 0;
		var y = 0;

		oXML = phpwc_ajax();
		oXML.open('POST', '/functions/ortografia/phpwebcorrect.php');
		oXML.onreadystatechange = phpwc_leeridiomas;
		oXML.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		oXML.setRequestHeader('Accept-Language','sp'); 
		oXML.send('consulta=idiomas&num=' + n);

		while (to.offsetParent) {
			x = x + to.offsetLeft;
			y = y + to.offsetTop;
			to = to.offsetParent;
		}

		phpwc_ready = 0;
		document.getElementById('phpwc_sugerencias').innerHTML = '<img src="/functions/ortografia/img/load.gif" />';
		document.getElementById('phpwc_sugerencias').style.left = x - document.getElementById('phpwcDiv_' + n).scrollLeft;
		document.getElementById('phpwc_sugerencias').style.top = y + 20 - document.getElementById('phpwcDiv_' + n).scrollTop;
		document.getElementById('phpwc_sugerencias').style.visibility = 'visible';
	}
}

function phpwc_cambiamodo(o, n) {
	if (document.getElementById('phpwcDiv_' + n).style.display == 'block' ) {
		document.getElementById('phpwc_sugerencias').style.visibility = 'hidden'; 
		document.getElementById('phpwc_cm' + n).alt = 'Corregir texto';
		document.getElementById('phpwc_cm' + n).src = '/functions/ortografia/img/corregir.png';
		phpwc_editar(n);
	}
	else {
		document.getElementById('phpwc_cm' + n).alt = 'Aceptar';
		document.getElementById('phpwc_cm' + n).src = '/functions/ortografia/img/editar.gif';
		phpwc_corregir(n);
	}
}

function phpwc_cambiar(texto) {
	phpwc_o.className = 'phpwc_ok';
	phpwc_o.innerHTML = texto;
}

function phpwc_corregir(n) {
	phpwc_n = n;

	document.getElementById('phpwcDiv_' + n).style.height = document.getElementById(n).style.height;
	document.getElementById('phpwcDiv_' + n).style.left = document.getElementById(n).style.left;
	document.getElementById('phpwcDiv_' + n).style.top = document.getElementById(n).style.top;
	document.getElementById('phpwcDiv_' + n).style.width = document.getElementById(n).style.width;

	document.getElementById('phpwcDiv_' + n).style.display = 'block';
	document.getElementById(n).style.display = 'none';

	oXML = phpwc_ajax();
	oXML.open('POST', '/functions/ortografia/phpwebcorrect.php');
	oXML.onreadystatechange = phpwc_leercorreccion;
	oXML.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	oXML.setRequestHeader('Accept-Language', 'sp'); 
	oXML.send('consulta=corregir&texto=' + document.getElementById(n).value + '&idioma=' + document.getElementById('phpwcDiv_' + n).idioma + '&num=' + n);

	document.getElementById('phpwcDiv_' + n).innerHTML = '<img src="/functions/ortografia/img/load.gif" />';
	document.getElementById('phpwcDiv_' + n).scrollTop = 0;
}

function phpwc_editar(n) {
	document.getElementById('phpwcDiv_' + n).style.display = 'none';
	document.getElementById(n).style.display = 'block';
	var texto = document.getElementById('phpwcDiv_' + n).innerHTML.replace(/<br>/gi,"\n");
	if (document.getElementById('phpwcDiv_' + n).innerText != undefined)
		document.getElementById(n).value = document.getElementById('phpwcDiv_' + n).innerText;
	else {
		var html = document.createRange();
		html.selectNodeContents(document.getElementById('phpwcDiv_' + n));
		document.getElementById(n).value = html.toString();
	}
}

function phpwc_leercorreccion() {
	if (oXML.readyState == 4)
		document.getElementById('phpwcDiv_' + phpwc_n).innerHTML = oXML.responseText;
}

function phpwc_leeridiomas() {
	if (oXML.readyState == 4) {
		document.getElementById('phpwc_sugerencias').innerHTML = oXML.responseText;
		phpwc_ready = 1;
	}
}

function phpwc_leersugerencias() {
	if (oXML.readyState == 4) {
		document.getElementById('phpwc_sugerencias').innerHTML = oXML.responseText;
		phpwc_ready = 1;
	}
}

function phpwc_ocultarsug() {
	if (phpwc_ready)
		document.getElementById('phpwc_sugerencias').style.visibility = 'hidden';
}

function phpwc_seleccionidioma(idioma, n) {
	document.getElementById('phpwcDiv_' + n).idioma = idioma;
	phpwc_editar(n);
	phpwc_corregir(n);
}

function phpwc_sugerir(o, n) {
	if (phpwc_ready) {
		phpwc_ready = 0;
		var palabra = o.innerHTML;
		phpwc_o = o;

		oXML = phpwc_ajax();
		oXML.open('POST', '/functions/ortografia/phpwebcorrect.php');
		oXML.onreadystatechange = phpwc_leersugerencias;
		oXML.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		oXML.setRequestHeader('Accept-Language','sp'); 
		oXML.send('consulta=sugerir&palabra=' + o.innerHTML + '&idioma=' + document.getElementById('phpwcDiv_' + n).idioma);

		document.getElementById('phpwc_sugerencias').style.visibility = 'visible';
		document.getElementById('phpwc_sugerencias').innerHTML = '<img src="/functions/ortografia/img/load.gif" />';

		var to = o;
		var x = 0;
		var y = 0;

		while (to.offsetParent) {
			x = x + to.offsetLeft;
			y = y + to.offsetTop;
			to = to.offsetParent;
		}

		document.getElementById('phpwc_sugerencias').style.left = x - document.getElementById('phpwcDiv_' + n).scrollLeft;
		document.getElementById('phpwc_sugerencias').style.top = y + 20 - document.getElementById('phpwcDiv_' + n).scrollTop;
	}
}


phpwc_ready = 1;