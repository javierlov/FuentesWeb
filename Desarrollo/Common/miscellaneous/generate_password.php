<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/encryptation.php");
  
echo encrypt($_REQUEST["PASSWORD"], 'PROVART');
?>