<?
require_once ($_SERVER["DOCUMENT_ROOT"] . "/constants.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/../Common/miscellaneous/general.php");

$ajaxRequest = "AddAttachment('newAttachment" . ($_REQUEST['param1'] + 1) . "', 'ajax_ticket_attachments.php', " . ($_REQUEST['param1']) . ");";

$attachments = "";
//$attachments="\n";

if ($_REQUEST['param1'] != 0) {
    $attachments .= '<br>';

    /*
     $attachments.='<table border=1 style="width:100%; ">
     <tr>
     <td style="height:24px;" colspan="3">
     </td>
     </tr>
     </table>';
     */
}

/***************************************************************************************************/
$attachments .= '<div id="contenedorBoton" style="float:left;">';

$attachments .= '<input type="file" name="attachments[]" style="width:238px; height:auto; border:0px; margin:0px; padding:0px;" 
							id="attachmentInput' . ($_REQUEST['param1']) . '"></input>';

$attachments .= '<div class="GIAttach2" id="btnAdd' . ($_REQUEST['param1']) . '">
					<button type="button" class="GIBtnAddRemove" style="width:80px; height:auto; margin:5px 10px;" onClick="' . $ajaxRequest . '">Agregar</button>
					</div>';

$attachments .= '<div style="height:20px; margin:0px; padding:0px; border:0px; "  id="newAttachment' . ($_REQUEST['param1'] + 1) . '"> </div>';

$attachments .= '</div>';

/***************************************************************************************************/

/*
 $attachments.='<table border=0 style="width:100%; height=auto; margin:0; padding:0;">';
 $attachments.="\n";
 $attachments.= '<tr> <td class="attach0" style="padding:0px; height=30px;">';

 if ($_REQUEST['param1'] == 0) {
 $attachments.= '   <label class="labelTitulo"  >Adjuntos <span class="small">Documentos, '.htmlentities("imógenes").'</span>';
 } else {
 $attachments.= '   <label> <span class="small">  </span>';
 }
 $attachments.= '</label>
 </td>
 <td class="attach1" style=" margin-left:10px;" >
 <input type="file" name="attachments[]" style="width:238px; height:auto;"
 id="attachmentInput'.($_REQUEST['param1']).'"></input>
 </td>
 <td class="attach2" id="btnAdd'.($_REQUEST['param1']).'">
 <button type="button" class="btnAddRemove" style="width:100px; height:auto; margin:5px 10px;"  onClick="'.$ajaxRequest.'">Agregar</button>
 </td>

 </tr>
 <tr>
 <td colspan="3" id="newAttachment'.($_REQUEST['param1'] + 1).'">
 </td>
 </tr>
 ';

 $attachments.="\n";
 $attachments.=" </table>

 ";

 $attachments.="\n";
 */
echo $attachments;
?>