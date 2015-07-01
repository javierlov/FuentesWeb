Servidor de SMS

STATUS 

<?php

/*

Usar con la siguiente URL

http://apps-test.artprov.com.ar/modules/SMS/cliente_http2.php

*/

$w = stream_get_wrappers();
echo 'openssl: ',  extension_loaded  ('openssl') ? 'yes':'no', "\n";
echo 'http wrapper: ', in_array('http', $w) ? 'yes':'no', "\n";
echo 'https wrapper: ', in_array('https', $w) ? 'yes':'no', "\n";
echo 'wrappers: ', var_dump($w);

?>
