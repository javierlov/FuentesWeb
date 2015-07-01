<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


session_unset();
session_destroy();
header("Location: ".LOCAL_PATH_PAGO_TRANSFERENCIA."login.php");
?>