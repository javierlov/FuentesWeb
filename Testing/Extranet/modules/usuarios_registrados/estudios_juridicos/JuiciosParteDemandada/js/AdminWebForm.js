var x;
x=$(document);
x.ready(inicio);

  function inicio(){
    var x;	
	
	x=$("#idcmbJurisdiccion");
	x.click(leerJurisdiccion);
	
	x=$("#idcmbJuzgadoNro");
	x.click(leerJurisdiccion);
	
	///x=$("#boton");	x.click(hiddenJurisdiccion);		
	
	leerJurisdiccion();
  }  
  
 function leerJurisdiccion(){	
	var valor = $("#idcmbJurisdiccion option:selected").text();	
	valor = valor + "  " + $("#idcmbJuzgadoNro option:selected").text();	
	
	$("#idHJuzgadoComp").val(valor);	
    return valor;
 }
 
 function hiddenJurisdiccion(){	
	var valor = $("#idHJuzgadoComp").val();
	alert(valor);
 }
 
 function leer_cmbJurisdiccion(){	
	valor = leerJurisdiccion();	
    alert(valor);
  }
  