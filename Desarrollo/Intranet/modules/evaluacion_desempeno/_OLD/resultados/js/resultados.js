function printResultados() {
	// Oculto lo que no se tiene que imprimir..
	var divGerencia = document.getElementById("divGerencia").style.display;
	document.getElementById("divAno").style.display = "none";
	document.getElementById("divTema").style.display = "none";
	document.getElementById("divDetalle").style.display = "none";
	document.getElementById("divSubdetalle").style.display = "none";
	document.getElementById("divGerencia").style.display = "none";

  if ((navigator.appName == "Netscape"))
    window.print();
  else {
    var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
    document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
    WebBrowser1.ExecWB(6, -1);
    WebBrowser1.outerHTML = "";
  }

	// Muestro lo que oculte..
  document.getElementById("divTema").style.display = "block";
  document.getElementById("divDetalle").style.display = "block";
  document.getElementById("divSubdetalle").style.display = "block";
  document.getElementById("divGerencia").style.display = divGerencia;
}

function showHideObj(objName) {
	var obj = document.getElementById(objName);
	if (obj.style.display == 'block')
		obj.style.display = 'none';
	else
		obj.style.display = 'block';
}