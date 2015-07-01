<div id="theLayer" style="position:absolute;left:2000;top:2000;visibility:hidden">
  <table bgcolor="#446D8C" border="0" cellpadding="5" cellspacing="0" width="320">
    <tr>
      <td valign="top">
				<iframe border="0" frameborder="0" framespacing="0" height="100" id="ventana" name="ventana" scrolling="no" src="mensaje_ok.php?result=<?= $_REQUEST["result"]?>" width="354">Lo sentimos, pero su navegador no soporta frames.</iframe>
			</td>
		</tr>
	</table>
</div>
<script language="javascript">
  afterLoad();
  showMe();
</script>