var x;
x=$(document).ready(inicio);

	function inicio(){
		$('#idbtnAceptarOKCancel').click( function(){ OcultarVentanaResultado(); } );
	} 
	
	function OcultarVentanaEvento(){
		OcultarVentanaResultado();
		RedirectPageEventosCYQWebForm(); 
		return true;
	}
	
	function RedirectPageEventosCYQWebForm(){
		window.location.href = '/EventosCYQWebForm';
		return true;	
	}
	
	function RedirectPageElim(id){				
		var Nro_evento = id;		
		window.location.href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?pageid=111&DELETECONFIRM&nroorden='+Nro_orden+'&id='+Nro_evento;				
		return true;	
	}
	
	function MostrarVentanaQuery(id){
		MostrarVentana('¿Está seguro de eliminar este Evento?');
		$('#idbtnAceptarVentana').click( function(){ RedirectPageElim(id); });
		$('#idbtnCancelarVentana').click( function(){ return false; });  
		//RedirectPageEventosCYQWebForm();
	}

	