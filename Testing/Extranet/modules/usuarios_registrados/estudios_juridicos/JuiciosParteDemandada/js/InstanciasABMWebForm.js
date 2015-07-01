var x;
x=$(document);
x.ready(inicio);

  function inicio()  {    
	$("#idcmbJurisdiccion").change(CompletarTxtJurisdiccion);	
	$("#idcmbFuero").change(CompletarTxtFuero);
	$("#idcmbJuzgadoNro").change(CompletarTxtJuzgado);
	$("#idcmbSecretaria").change(CompletarSecretaria);
	$("#idcmbMotivo").change(CompletarMotivo);
	
  }

  function ValidarFormI(){  
		var x=$("#divMsg");
		x.text("Error algunos campos son incorrectos.....");
		x.show("fast");
		return false;
  }
  
  function ValidarFormInstanciasABMWebForm(){
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
  
  function CompletarTxtJurisdiccion(){
  	var valor = $("#idcmbJurisdiccion").val();  	
  	$("#idtxtJurisdiccion").val(valor);
  	
  	LimpiarControles(0);
  	CompletarFueroSubmit();  	
  }
  
  function BloquearControles(){  	  	
  	$("#idcmbFuero").attr("disabled", "disabled");
  	$("#idcmbJuzgadoNro").attr("disabled", "disabled");
  	$("#idcmbSecretaria	").attr("disabled", "disabled");
  	$("#idcmbMotivo	").attr("disabled", "disabled");
  }
  
  function LimpiarControles(nivel){
  	if(nivel == 0){
	  	$("#idcmbFuero").empty();
	  	$("#idtxtFuero").val('');
	}  	
	if(nivel <= 1){
	  	$("#idcmbJuzgadoNro").empty();
	  	$("#idtxtJuzgadoNro").val('');
	}  	
	if(nivel <= 2){
	  	$("#idcmbSecretaria	").empty();
	  	$("#idtxtSecretaria	").val('');
	}  	
	if(nivel <= 3){
	  //$("#idcmbMotivo	").empty();
	  //$("#idtxtMotivo	").val('');
	}
		  	
  }
  
  function CompletarFueroSubmit()
  {   	  	
  	var valorJuridiccion = $("#idtxtJurisdiccion").val();
	var valorFuero = "0";
	
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatos.php";
	pagefunciones = pagefunciones + "?FUNCION=CargarFuero";
	pagefunciones = pagefunciones + "&Juridiccion="+valorJuridiccion;
	pagefunciones = pagefunciones + "&Fuero="+valorFuero;
		 
	var x=$("#idcmbFuero");	
	/////alert("envio...");
	//x.ajaxStart(EnvioJur);
	//x.ajaxStop(finJur);
	x.html("<option selected='selected'>buscando...</option>");
	x.load(pagefunciones); 
	
	//--------------------------------------
	var valor = $("#idcmbJurisdiccion").val();  	
  	$("#idtxtJurisdiccion").val(valor);
  	
  	//var texto = $("#idcmbJurisdiccion option:selected").text();
  	//--------------------------------------
  	
	return false;
  }

  function EnvioJur(){ $("#lblMensaje").text('Procesando Fuero');  }
  function finJur(){ $("#lblMensaje").text('Fin Proceso Fuero');  }
  /*----------------------------------------------------------------*/
  function CompletarTxtFuero(){
  	var valor = $("#idcmbFuero").val();  	
  	$("#idtxtFuero").val(valor);
  	
  	LimpiarControles(1);
  	CompletarJuzgadoSubmit();  	
  }
  
  function CompletarJuzgadoSubmit(){
	
	//$("#idJuzgadoTxtAnterior").text('Cargando.....');
	
	var valorJurisdiccion= $("#idtxtJurisdiccion").val();
	var valorFuero=$("#idtxtFuero").val();
	var valorJuzgado=$("#idtxtJuzgado").val();
	
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatos.php";
	pagefunciones = pagefunciones + "?FUNCION=CargarJuzgado";
	pagefunciones = pagefunciones + "&jurisdiccion="+valorJurisdiccion;
	pagefunciones = pagefunciones + "&Fuero="+valorFuero;
	pagefunciones = pagefunciones + "&Juzgado="+valorJuzgado;
				 
	var x=$("#idcmbJuzgadoNro");
	x.html("<option selected='selected'>buscando...</option>");
	x.ajaxStart(inicioEnvioCargarFuero);
	x.load(pagefunciones);

	return false;  	
  }
  
  function inicioEnvioCargarFuero(){
  	var texto = $("#idcmbFuero option:selected").text();
  	//$("#idcmbJuzgadoNro").attr("disabled", '');
  }

/*------------------------------------------------------*/
  
  function CompletarTxtJuzgado(){
	var valor = $("#idcmbJuzgadoNro").val();  	
  	$("#idtxtJuzgadoNro").val(valor);
  	
  	LimpiarControles(2);
  	CompletarSecretariaSubmit();  	
  }
  
  function CompletarSecretariaSubmit(){  		
	var valorJuzgado= $("#idtxtJuzgadoNro").val();
	var valorSecretaria=$("#idtxtSecretaria").val();
		
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatos.php";
	pagefunciones = pagefunciones + "?FUNCION=CargarSecretaria";
	pagefunciones = pagefunciones + "&Juzgado="+valorJuzgado;
	pagefunciones = pagefunciones + "&Secretaria="+valorSecretaria;	
				 
	var x=$("#idcmbSecretaria");
	x.html("<option selected='selected'>buscando...</option>");
	x.ajaxStart(inicioEnvioCargarSecretaria);
	x.load(pagefunciones);

	return false;    	
  }
  
  function inicioEnvioCargarSecretaria(){  	
  	var texto = $("#idcmbSecretaria option:selected").text();
  	//$("#idSecretariaTxtAnterior").text(texto);  	   	
  	//$("#idcmbSecretaria").attr("disabled", '');
  }
  
  
  function CompletarSecretaria(){  	
  	var valor = $("#idcmbSecretaria").val();  	
  	$("#idtxtSecretaria").val(valor);    
  	
  	//$("#idcmbMotivo").attr("disabled", '');
  	LimpiarControles(3);
  }
  
  function CompletarMotivo(){  	
  	var valor = $("#idcmbMotivo").val();  	
  	$("#idtxtMotivo").val(valor);    
  }
    
/*  
  function llegadaDatosCargarFuero(datos)
  {
 	 $("#idFueroTxtAnterior").text(datos);
  }
  
  function errorCargarFuero()
  {
  	$("#idFueroTxtAnterior").text('Problemas en el servidor.');
  }
    
  function CompletarTestoJuridiccion(texto){
  	$("#idJuridiccionTxtAnterior").text(texto);  	
  }
  
  function submitFormInstancias() {
       $("#idInstanciasABMWeb").submit();
   }
      
  function hrefInstancias(){
  	$(location).attr('href', '/InstanciasABMWebForm');  	
  }
  
  function SelectedJurisdiccion(){
  	var valor = $("#idtxtJurisdiccion").val();  	
  	$("#idcmbJurisdiccion").val(valor);  	  	
  	$("#idcmbJurisdiccion option[value="+ valor +"]").attr("selected", "selected");
  }
  
  */
/*----------------------------------------------*/
/*
  function CompletarJurisdiccion(){  	
  	var valor = $("#idcmbJurisdiccion").val();  	
  	$("#idtxtJurisdiccion").val(valor);
  	
  	var texto = $("#idcmbJurisdiccion option:selected").text();
  	$("#idJuridiccionTxtAnterior").text(texto);
  	
  		
  	$("#idcmbFuero").empty();
  	$("#idcmbJuzgadoNro").empty();
  	$("#idcmbSecretaria").empty();
  	$("#idcmbMotivo").empty();
  	
  	submitFormInstancias();  	    
  }
  */
/*----------------------------------------------*/
/*
  function CompletarFuero(){  	
  	var valor = $("#idcmbFuero").val();  	
  	$("#idtxtFuero").val(valor);
  	  	
  	var texto = $("#idcmbFuero option:selected").text();
  	$("#idFueroTxtAnterior").text(texto);
  	  	
  	$("#idcmbJuzgadoNro").empty();
  	$("#idcmbSecretaria").empty();
  	$("#idcmbMotivo").empty();
  	
  	submitFormInstancias();
  	    
  }
  
  
  function CompletarSecretaria(){  	
  	var valor = $("#idcmbSecretaria").val();  	
  	$("#idtxtSecretaria").val(valor);    
  }
	
  
  
*/
