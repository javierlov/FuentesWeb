var x;
x=$(document).ready(inicio);

  function inicio(){
	$("#idbtnPerito").click(CompletarCmbRazonSocila);	
	
	$("#btnBusqueda1").click(BuscarWGTrue);	
  } 
  
  function CompletarCmbRazonSocila(){		
	comboselect = "#idcmbRsocial";
	seleccionado = $('#txtPerito').val();	
	cuenta = $('#txtPerito').val().length;
	
	if(cuenta < 3){
		MostrarVentana('Debe ingresar al menos 3 caractres para buscar.')
		return false;
	}
	//se hace un encode del parametro
	seleccionado = encodeURIComponent(seleccionado);	
	CompletarComboEstados(comboselect, EnvioJur(), seleccionado);	
	return true;
  }  
  
  function EnvioJur(){return true;}
