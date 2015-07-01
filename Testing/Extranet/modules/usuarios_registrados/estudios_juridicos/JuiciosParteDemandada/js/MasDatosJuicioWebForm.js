var x;
x=$(document);
x.ready(inicio);

  function inicio(){
    var x;		
	x=$("#idbtnAceptar");
	x.click(AccionAceptar);	
		
  }  
  
 function AccionNOAceptar(){	
	var x = $("#idhiddenBtnAceptar");	
	x.val("S");			
	alert('valor de idhiddenwBtnAceptar = ' + x.val() );	
	window.parent.location.href = '/MasDatosJuicioWebForm';
 }
 
 function AccionAceptar(){ 	
	/*
		var FormMasDatosJuicioWebForm = $('#MasDatosJuicioWebForm');
		$('#idbtnAceptar').val("S");
		FormMasDatosJuicioWebForm.attr("action", "/MasDatosJuicioWebForm");    
		FormMasDatosJuicioWebForm.submit();    
	*/
	
	var x = $("#idhiddenBtnAceptar");
	x.val("S");		
	alert("Datos Actualizados ");
	
 }
 
 function Volver(){
  window.history.back();
 }
 
 function Redirect() {
	window.parent.location.href = '/MasDatosJuicioWebForm';
 }
 
 function ValidarFormMasDatosJuicioWeb(){
 	var x = $("#txtEmail");
 	resultado = validarEmail(x.val() );
 	
 	if(resultado)   
 		alert ('email valido ' + x.val());
 	
 	return resultado; 
 }
 
 