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
	if (window.confirm('Está a punto de cancelar la operación. ¿Desea continuar?'))
		window.close();
}

function CloseWindow() {
	if (window.confirm('Está a punto de cerrar la ventana. ¿Desea continuar?'))
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

function OpenWindow(url, name, width, height, scrollbars) {
// Abre una ventana centrada..
  medioancho = (screen.width - width) / 2;
  medioalto = (screen.height - height) / 2;
  features = 'width=' + width + ',height=' + height + ',left=' + medioancho + ',top=' + medioalto + ',scrollbars=' + scrollbars;

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