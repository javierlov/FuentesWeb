var x;
x=$(document);
x.ready(inicio);


  function inicio()
  {
    document.getElementById('botonImprimir').addEventListener('click',Doc_AbrirParametros,false);
    document.getElementById('botonLimpiar').addEventListener('click',limpiarControles,false);

	var x;
	x=$(document);
	x.ready(AsignarEventos);	
	
  }
  
  function AsignarEventos()
  {	
		var x=$("#idCaratula");
		x.click(function(){this.select();});
		
		x=$("#txtNroExpediente");
		x.click(function(){this.select();});
		
		x=$("#txtNroCarpeta");
		x.click(function(){this.select();});
  }
  
  function limpiarControles()
  {
    document.getElementById('txtNroExpediente').value = '';
	document.getElementById('idCaratula').value = '';
	document.getElementById('txtNroCarpeta').value = '';	
	document.getElementById('cmbTipoJuicio').value = '';		
  }

  function ImprimirControles()
  {
    Doc_AbrirParametros();		
  }
///////////////////////////////////////////
  function Doc_Abrir()
  {
    var ventana=open();	
	ventana.document.write("<link rel='stylesheet' href='/styles/design.css' type='text/css' />");	
	ventana.document.write("<link rel='stylesheet' href='/styles/style2.css?rnd=20131115' type='text/css' />");
	
    ventana.document.write("<span>Ventana temporal de ipresion </span><br>");
    ventana.document.write("<span>Tal vez genere un PDF </span><br>");
	ventana.document.write("<div class='btnImprimir' id='botonImprimirPDF'/></div>");		
  }

  function Doc_AbrirParametros()
  {
    var ventana=open('','','status=yes,width=400,height=250,menubar=yes');
	ventana.document.write("<link rel='stylesheet' href='/styles/design.css' type='text/css' />");
	ventana.document.write("<link rel='stylesheet' href='/styles/style2.css?rnd=20131115' type='text/css' />");
	ventana.document.write("<script src='/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/funcionesjuridicos.js' type='text/javascript'></script>");
	
	//ventana.document.write("<head><script type='text/javascript'> addEventListener('load',inicioPDF,false);</script></head>");
	
	ventana.document.write("<span>Ventana temporal de ipresion </span><br>");
    ventana.document.write("<span>Tal vez genere un PDF </span><br>");
	ventana.document.write("<div class='btnImprimir' id='botonImprimirPDF'/> </div>");
  }
  
  function inicioPDF()
  {    
    document.getElementById('botonImprimirPDF').addEventListener('click',Doc_Confirmar,false);
  }
 
  function Doc_MostrarAlerta()
  {
    alert("Esta ventana de alerta ya la utilizamos en otros problemas.");
  }

  function Doc_Confirmar()
  {
    var respuesta=confirm("Presione alguno de los dos botones");
    if (respuesta==true)
      alert("presionó aceptar");
    else
      alert("presionó cancelar");  
  }

  function Doc_CargarCadena()
  {
    var cad=prompt("cargue una cadena:","");
    alert("Usted ingreso "+cad);
  }	
  
  function seleccionaFoco(control){    		
	var controlname = control;
	document.getElementById(controlname).focus(); 
   	document.getElementById(controlname).select(); 
	
}