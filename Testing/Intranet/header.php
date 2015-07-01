<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$today = GetDayName(date("N"))." ".date("j")." de ".GetMonthName(date("n"))." de ".date("Y");
?>

<img border="0" id="imagen2HomePage" src="images/entrenos.jpg">
<span id="fechaHomePage"><?= $today?></span>
<map name="FPMap0"><area href="/" shape="rect" coords="62, 4, 206, 51"></map>
<img border="0" id="cube" name="cube" src="images/logo_provart.jpg" style="filter:progid:DXImageTransform.Microsoft.Stretch(stretchStyle='PUSH')" usemap="#FPMap0">