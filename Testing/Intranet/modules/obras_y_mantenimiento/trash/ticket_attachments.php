<div id="DivAdjuntos">
<?
  echo '<label>Adjuntos<span class="small">Documentos, imágenes</span></label>';
/*  echo '<input id="newAttachment" name="newAttachment" ></input>'; style="width:168px;"*/
  echo '<input type="file" style="width:240px;"></input>';
  $ajaxRequest = "AjaxRequest('DivAdjuntos', 'ajax_detalle_ejecutable.php');";
  echo '<button type="button" style="margin-top:-42px; margin-left:396px; width:60px; height:21px; line-height:21px; float:center;" onClick="'.$ajaxRequest.'">Adjuntar</button>';
?>
</div>