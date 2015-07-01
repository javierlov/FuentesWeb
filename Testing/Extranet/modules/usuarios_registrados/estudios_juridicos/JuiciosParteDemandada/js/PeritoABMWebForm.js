var x;
x=$(document);
x.ready(inicio);

  function inicio()
  {    
  	$("#cmbDesignacion").click(validardesignacion);
  }
  
  function validardesignacion{
  	alert("validardesignacion") ;
  }
  
  function ValidarPeritoWebForm(){
    var resultado = '';
  	if ($('#idtxtJurisdiccion').val() == ''){
	  	resultado += '<p>Debe seleccionar una Jurisdiccion ';
	}  	
  	if ($('#idtxtFuero').val() == ''){
	  	resultado += '<p>Debe seleccionar un fuero \n';
	}  	
  	if ($('#idtxtJuzgadoNro').val() == ''){
	  	resultado += '<p>Debe seleccionar un Juzgado \n';
	}  	
	if ($('#idtxtSecretaria').val() == ''){
	  	resultado += '<p>Debe seleccionar una Secretaria \n';
	}  	
	if ($('#idtxtMotivo').val() == ''){
	  	resultado += '<p>Debe seleccionar un Motivo\n';
	}  	
	
	if(resultado > ''){
		var x=$("#divMsg");
		x.html(resultado);
		x.show("fast");
		return false;
	}
	else{
		var x=$("#divMsg");
		x.empty();
		x.hide("slow");
		return true;
	}
}