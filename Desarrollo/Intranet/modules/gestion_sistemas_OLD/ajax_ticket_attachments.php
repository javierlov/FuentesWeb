<?
  $ajaxRequest="AddAttachment('newAttachment".($_REQUEST['param1'] + 1)."', 'ajax_ticket_attachments.php', ".($_REQUEST['param1']).");";

  $attachments="\n";
  if ($_REQUEST['param1'] != 0) {
    $attachments.='
                   <table width="100%" height="20">
                     <tbody>
                       <tr>
                         <td style="height:20px;" colspan="3">
                         </td>
                       </tr>
                     </tbody>
                   </table>
                  ';
  }

  $attachments.='
                <table width="100%" height="24" style="margin-left:-5px;">
                  <tbody>
                ';
  $attachments.="\n";
  $attachments.= '
                    <tr>
                      <td class="attach0">
                 ';
  if ($_REQUEST['param1'] == 0) {
    $attachments.= '
                        <label>Adjuntos
                            <span class="small">Documentos, '.htmlentities("imágenes").'</span>
                   ';
  } else {
    $attachments.= '
                        <label>
                            <span class="small"></span>
                   ';
  }
  $attachments.= '
                        </label>
                      </td>
                      <td class="attach1">
                        <input type="file" name="attachments[]" style="width:238px;" id="attachmentInput'.($_REQUEST['param1']).'"></input>
                      </td>
                      <td class="attach2" id="btnAdd'.($_REQUEST['param1']).'">
                        <button type="button" class="btnAddRemove" onClick="'.$ajaxRequest.'">Agregar otro...</button>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" id="newAttachment'.($_REQUEST['param1'] + 1).'">
                      </td>
                    </tr>
                 ';

  $attachments.="\n";
  $attachments.="
                   </tbody>
                 </table>
                ";
  $attachments.="\n";
  echo $attachments;
?>