  function JQInputSetValue(idInput, valor){
	/*asignar un valor a un elemento: asigna a un control input el valor */
	$(idInput).val(valor);  
  }
  
  function JQInputGetValue(idInput){
	/*obtiene el valor de un elemento: el valor de un control input 
		Comment: 
		.val() Obtener y asignar el valor de controles de formulario como ser input, select y textarea 
		.text() Obtener y asignar el texto de cualquier elemento DOM. Específicamente utiliza en la propiedad innerHTML .html() Lo mismo que el text() con la diferencia que el texto es interpretado como HTML, no como texto plano 
	*/
	var valor = $(idInput).val();
	return valor;
  }
    
  function JQElementSetEventClick(idDiv, funcion){
	/*Asocia al evento click  de la 'funcion' a el elemento 'idDiv' */
	$(idDiv).click(funcion);
	//
  }
  
  function JQDeleteEvent(idDiv, evento){
	/*Des-Asocia al evento "click" o cualquier evento del elemento 'idDiv' */
	$(idDiv).off(evento);
	// no funciona siempre ver si hay uno compatible con todas las versiones
  }
  
  function JQAsignEvent(idDiv, evento, funcion){
	/*Asocia al evento "click" o cualquier evento del elemento 'idDiv' */
	$(idDiv).bind(evento, funcion);
	//
  }
  
  function JQElementSetEventKeyPressEnter(idDiv, funcion){
	/*Asocia al evento keypress (se ejecuta la al presionar ENTER) de la 'funcion' a el elemento 'idDiv' */
	
	$(idDiv).keypress( function( event ) {
			  if ( event.which == 13 ) {
				 funcion();				 
				 //event.preventDefault();
				 return false;
			  } 			  
		  });		
	return this;
	
  }
  
  function JQElementSetEventdblClick(idDiv, funcion){
	/*Asocia al evento click  de la 'funcion' a el elemento 'idDiv' */
	$(idDiv).dblclick(funcion);	
	return this;
  }
  
  function JQDivSetValue(idDiv, valor){
	/*Asignar un valor a un elemento: asigna a un control input el valor */
	$(idDiv).empty().html(valor);  			
	return this;
  }
  
  function JQDivGetValue(idDiv){
	/*obtiene el valor de un div 
		Comment: 		
		.text() Obtener y asignar el texto de cualquier elemento DOM. Específicamente utiliza en la propiedad innerHTML .html() Lo mismo que el text() con la diferencia que el texto es interpretado como HTML, no como texto plano 
	*/
	var valor = $(idDiv).html();
	return valor;
  }
  
  function JQradioButtonSelect(nameRadio){
	/*retorna el atributo value seleccionado de un grupo, nameRadio es el atributo name*/
	return $('input:radio[name="'+nameRadio+'"]:checked').val();
  }
  
  function JQIsChecked(idCheck){
	/*valida si el check pasado esta checkeado o no pasar con el #*/
	if( $(idCheck).is(':checked') ) return true;
	return false;
  }
   
  function JQCheckedControl(idCheck, checkControl){
	/*checkea o deschekea un option control #*/
	if( checkControl ) $(idCheck).attr('checked', true);
	else $(idCheck).attr('checked', false);
	
	return true;
  }
  
  function JQMostrarElemento(idelement){
	/*muestra un elemento*/	
	$(idelement).show();
  }
  
  function JQOcultarElemento(idelement){
	/*oculta un elemento*/	
	$(idelement).hide();
  }
  
  function JQAsignaClaseCSS(idelement, claseCSS){
	/*Se le asigana una clase css a un elemento*/	
	$(idelement).addClass(claseCSS);	
  }
   
  function JQRemoveClaseCSS(idelement, claseCSS){
	/*Se le quita una clase css a un elemento*/	
	$(idelement).removeClass(claseCSS);	
	//nrCellCuil
  }

  function JQBloqueaControl(idelement, claseAdd, claseRem){
	JQBloqueaInput(idelement, true);
	JQRemoveClaseCSS(idelement, claseRem);
	JQAsignaClaseCSS(idelement, claseAdd);
  }
  
  function JQDesBloqueaControl(idelement,  claseAdd, claseRem){
	JQBloqueaInput(idelement, false);
	JQRemoveClaseCSS(idelement, claseRem);
	JQAsignaClaseCSS(idelement, claseAdd);
  }
  
  function JQReadOnlyInput(idelement, bloquea){
	/*read only control*/
	$(idelement).attr('readonly', bloquea);
  }
  
  function JQBloqueaInput(idelement, bloquea){
	/*bloquea o desbloquea elementos */
	
	if(bloquea)	
		$(idelement).attr('disabled','disabled'); //bloquea
	else{
		//$(idelement).attr('disabled',''); //desbloquea
		$(idelement).removeAttr("disabled");//desbloquea funciona
	}
	
  }
/************ FECHAS *****************/   
 function JQformateaFecha(fecha){
	fecha = fecha.val($.format.date(new Date(), 'dd/mm/yyyy'));
	return fecha;		 
  }
	
	
 function JQEliminarElemento(idelement){
	//elimina un elemento html de la pagina
		$(idelement).empty().remove();	
 }
	
	

  function AddToolTips(arrayids, typeToolTip){		
	//Asigna tool tips jquery ui a los controles del array
	for(var i=0; i < arrayids.length ;i++){	
		
		var idControl = "#"+arrayids[i];
		
		if( $(idControl).length ){
			
			if(typeToolTip == 1){
				 $(idControl).tooltip({		
					position: { my: "left center", at: "bottom" },
					show: {
					effect: "slideDown",		
					delay: 250
				  }
				});
			}
			
			if(typeToolTip == 2){	
				 $( idControl ).tooltip({
				  position: { my: "left", collision: "flipfit" },
				  hide: {
					effect: "explode",
					delay: 250
				  }
				});
			}
		
			if(typeToolTip == 3){
				$( idControl ).tooltip({
				  show: null,
				  position: {
					my: "left top",
					at: "left bottom"
				  },
				  open: function( event, ui ) {
					ui.tooltip.animate({ top: ui.tooltip.position().top}, "fast" );
				  }
				});
			}
		}
	}
  }