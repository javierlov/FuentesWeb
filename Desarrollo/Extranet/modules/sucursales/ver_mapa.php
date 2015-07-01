<?
if (strpos($_REQUEST["d"], "Tandil"))
	header("Location: https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3172.6663322101163!2d-59.139658!3d-37.3267307!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95911f9a6c0e3591%3A0xf92c15c9e0f421c0!2s".$_REQUEST["d"]."!5e0!3m2!1ses-419!2sus!4v1417440435088");
else
	header("Location: https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3283.855669740471!2d-58.38076019999999!3d-34.607811000000005!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bccad02e2a2069%3A0xc5f8338f015428f2!2s".$_REQUEST["d"]."!5e0!3m2!1ses-419!2s!4v1417185994253");
?>