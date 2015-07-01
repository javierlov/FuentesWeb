  function inicio() {
	var x;
	x=$(document);
	x.ready(AsignarEventos);	
	
  }
  
  function AsignarEventos() {	
  
		var x=$("#botonNuevoPeritaje");
		x.click(AbrirNuevoPeritaje);
	/*	
		x=$("#txtNroExpediente");
		x.click(function(){this.select();});
		
		x=$("#txtNroCarpeta");
		x.click(function(){this.select();});
*/		
  }
  
  function AbrirNuevoPeritaje(){
	$(location).attr('href', '/index.php?pageid=105');
  }
 
 function ValidarPeritajesWebForm(){
 	var resultado = '';
 	$("#lblErrores").empty();

 	if($("#txtApellido").val() == '')
	 	resultado = "Complete el Apellido";

 	if($("#txtNombre").val() == '')
	 	resultado += "Complete el Nombre"

 	if($("#txtDireccion").val() == '')
	 	resultado += "Complete la direccion";
 	
 	if(resultado > ''){
		var x=$("#lblErrores");
		x.html(resultado);
		x.show("fast");
		return false;
	}
	else{
		var x=$("#lblErrores");
		x.empty();
		x.hide("slow");
		return true;
	}
 }  
  
