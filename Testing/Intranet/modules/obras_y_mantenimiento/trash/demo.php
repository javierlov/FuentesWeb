<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
<head>
  <title>Prueba fecha</title>
  <script type="text/javascript" language="javascript" src="datetimepicker.js"></script>
  <link href="/Modules/Gestion_Sistemas/Styles/style_sistemas.css?sid=<?= date('YmdHis'); ?>" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000">
  Texto
  <table>
    <tr>
	    <td>
	  	  <input type="Text" id="demo51" maxlength="20" size="20" style="width:100px;" name="demo51" disabled=true>
          <a href="javascript:NewCal('demo51','ddmmyyyy',true,24,'dropdown',true)">
            <img src="images/cal.gif" width="16" height="16" border="0" alt="Seleccione una fecha">
          </a>
 			</td>
		</tr>
  </table>
</body>
</html>