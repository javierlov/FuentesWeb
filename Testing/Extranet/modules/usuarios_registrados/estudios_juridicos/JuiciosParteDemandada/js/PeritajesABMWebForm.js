var x;
x=$(document);
x.ready(inicio);

  function inicio()
  {    
	$("#idbotonLimpiar").click(LimpiarComboPeritosNombre);
	
	$("#cmbTipoPericia").change(LimpiarDatosPerito);
	
	$("#btnPeritoBuscar").click(CargarPeritos);

	
	$("#btnFechaAsignacion").click(CompletarFechaAsignacion);
	$("#btnFechaPericia").click(CompletarFechaPericia);
	$("#btnFVencImpugnacion").click(CompletarFVencImpugnacion);
	
  }
  
  
  function FechaHoy(){
  	var f = new Date();
  	return f.getDay()+"/"+f.getMonth()+"/"+f.getFullYear() ;
  }
  
  function CompletarFechaAsignacion(){  	
  	$('#txtFechaAsignacion').val(FechaHoy());  
  }
  
  function CompletarFechaPericia(){
  	$('#txtFechaPericia').val(FechaHoy());  
  }
  
  function CompletarFVencImpugnacion(){
  	$('#txtFVencImpugnacion').val(FechaHoy());  
  }
  
  function CargarPeritos(){
	var vtxtPeritoApellido= $("#txtPeritoApellido").val();
	var vtxtPeritoNombre=$("#txtPeritoNombre").val();
	var vcmbTipoPericia=$("#cmbTipoPericia").val();
	
	if((vtxtPeritoApellido=='') && (vtxtPeritoNombre=='')){
		alert('Por favor complete nombre o apellido');
		return false;      	
	}
	
	if(vcmbTipoPericia =='0'){
		alert('Por favor Seleccione una pericia');
		return false;      	
	}
	
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatos.php";
	pagefunciones = pagefunciones + "?FUNCION=ObtenerPeritos";	
	pagefunciones = pagefunciones + "&Nombre="+vtxtPeritoNombre;
	pagefunciones = pagefunciones + "&Apellido="+vtxtPeritoApellido;	
	pagefunciones = pagefunciones + "&tipoPericia="+vcmbTipoPericia;	
	
	var x=$("#idcmbPeritosNombre");
	x.html("<option selected='selected'>buscando...</option>");

	x.load(pagefunciones);

	return true;      	
  }
  
  function presionSubmit()
  {
	var vtxtPeritoApellido= $("#txtPeritoApellido").val();
	var vtxtPeritoNombre=$("#txtPeritoNombre").val();
	var vcmbTipoPericia=$("#cmbTipoPericia").val();
	
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatos.php";
	pagedatos = "?FUNCION=ObtenerPeritos";	
	pagedatos += "&Nombre="+vtxtPeritoNombre;
	pagedatos += "&Apellido="+vtxtPeritoApellido;	
	pagedatos += "&tipoPericia="+vcmbTipoPericia;	
	
	var v=$("#nro").attr("value");
	$.ajax({
		   async:true,
		   type: "POST",
		   dataType: "html",
		   contentType: "application/x-www-form-urlencoded",
		   url: pagefunciones,
		   data: pagedatos,
		   beforeSend:inicioEnvio,
		   success:llegadaDatos,
		   timeout:4000,
		   error:problemas
		 }); 
	return false;
  }
  
	function inicioEnvio()
	{
	  var x=$("#idcmbPeritosNombre");
	  x.html("<option selected='selected'>inico busqueda...</option>");
	}

	function llegadaDatos(datos)
	{
	  $("#idcmbPeritosNombre").html(datos);
	}

	function problemas()
	{
	  var x=$("#idcmbPeritosNombre");
	  x.html("<option selected='selected'>Problemas en el server...</option>");
	}
  
  
  function LimpiarComboPeritosNombre(){
	$("#idcmbPeritosNombre").empty();		
	LimpiarDatosPerito();
  }
  
  function LimpiarDatosPerito(){
    $('#txtPeritoNombre').val('');  
    $('#txtPeritoApellido').val('');    
  }
  
