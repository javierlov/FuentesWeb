function CreateXmlHttpObject()
{
  var objXMLHttp=null;
  if (window.XMLHttpRequest)
  {
    objXMLHttp=new XMLHttpRequest();
  }
  else if (window.ActiveXObject)
  {
    objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  return objXMLHttp;
}

function AjaxRequest(htmlid,url,param1,param2,param3)
{
  var xmlHttp;
  xmlHttp=CreateXmlHttpObject();
  if (xmlHttp==null)
  {
    alert ("Browser does not support HTTP Request");
    return;
  }
  url=url+"?sid="+Math.random(); //para evitar que los browsers hagan cache
  var postvars="param1="+param1+"&param2="+param2+"&param3="+param3;
  xmlHttp.onreadystatechange=function () {stateChanged(xmlHttp, htmlid);};  //esta es la función de callback
  xmlHttp.open("POST",url,true); //si se elige POST o GET
  //Si se va a usar POST
  xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
  xmlHttp.send(postvars);
  return false;
}

function stateChanged(xmlHttp,htmlid)
{
  if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
  {
	if(document.getElementById(htmlid)){
		document.getElementById(htmlid).innerHTML = xmlHttp.responseText;
	}
  }
/*  // Esto es para usar JSON
    myJSONtext=xmlHttp.responseText //asigna el valor de la respuesta HTTP a la variable myJSONtext (si se usa JSON, JavaScript Object Notation)
    var myObject = eval('(' + myJSONtext + ')'); //convierte el JSON text en un objeto
    message=myObject.message
    sarasa=myObject.sarasa
*/
}