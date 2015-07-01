//document.addEventListener("DOMContentLoaded", load, false);
//bindEvent(document, 'load', DOMContentLoaded);
if (document.addEventListener){
    document.addEventListener("DOMContentLoaded", load, false); 
  } else if (document.attachEvent){
    document.attachEvent('on'+"DOMContentLoaded", load);
  }

function DOMContentLoaded(){}

function load() { 
  var ArrayFechas = document.getElementsByClassName("input_text_Fecha");  
  
  for (index = 0; index < ArrayFechas.length; ++index) {
	  if(ArrayFechas[index]){	
		var txtFecha = ArrayFechas[index];
		//AddTitleAttr(txtFecha);	
	 }
  }  
  
} 

function AddDivControl(elemento, index){
	//funcion agrega una x para borrar los datos
	var iDiv = document.createElement('div');
	iDiv.innerHTML  = 'ssssx';
	iDiv.id = 'divcerrar'+index;
	iDiv.className = 'input_text_Fecha';
	iDiv.style.position = 'absolute';
	iDiv.style.top = 100;
	iDiv.style.left = 100;
	iDiv.style.width = 100;
	iDiv.style.heigth = 10;
	//elemento.appendChild(iDiv);
	document.body.appendChild(iDiv);
}

function AddTitleAttr(txtFecha){		
	txtFecha.addEventListener("keypress", keysPressed, false); 
	txtFecha.setAttribute('title','presione L para borrar la fecha.');	
}

function keysPressed(e) {
	e.preventDefault();	
		
	if(e.keyCode == 76 || e.keyCode == 108){
		this.value = '';		
	}
}