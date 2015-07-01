<html>
<head>  
  <title>ForoAlfaRomeo - Mapa interactivo</title>
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
  <link rel="stylesheet" type="text/css" media='screen,print' href="http://www.ForoAlfaRomeo.com/public/min/index.php?ipbv=32005&amp;f=public/style_css/css_3/SOS_BBCodes.css,public/style_css/css_3/calendar_select.css,public/style_css/css_3/ipb_common.css,public/style_css/css_3/ipb_styles.css,public/style_css/css_3/ipb_ckeditor.css,public/style_css/prettify.css" />
  <?php
    $referrerIsValid = false;
    $hasReferrer = false;
    if (isset($_SERVER['HTTP_REFERER'])) {
      $parts = parse_url($_SERVER['HTTP_REFERER']);
      if (isset($parts['host'])) {
        $hasReferrer = true;
        $referrerIsValid = (bool) preg_match('/(?:^|\.)foroalfaromeo\.com$/', strtolower($parts['host']));
      }
    }

    $dbhost = '192.168.0.194';
    $dbuser = 'user_ipboard';
    $dbpass = 'ipAstonMartin..00';
    $dbname = 'ipboard';
    $dbtable = '_mapa';

    //------ DATABASE CONNECTION --------//
    mysql_connect($dbhost,$dbuser,$dbpass)
    or die ("No se pudo conectar a la Base de Datos");

    mysql_select_db($dbname)
    or die ("No se pudo seleccionar la Base de Datos");

    $sql = "SELECT * FROM $dbtable";
    $result = mysql_query($sql);

    $number = mysql_numrows($result);
    //print "Talleres: ".$number;
    $i = 0;

    if ($number == 0)
      print "Error - No records found";
    elseif ($number > 0)
    {
      echo"<script type='text/javascript'>\n";
      echo"  function initialize() {\n";

?>
      var latlng = new google.maps.LatLng(-34.60399,-58.455776);
      var settings = {
                      zoom: 12,
  		      center: latlng,
  		      mapTypeControl: true,
  		      mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
  		      navigationControl: true,
  		      navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
  		      mapTypeId: google.maps.MapTypeId.ROADMAP};
      var map = new google.maps.Map(document.getElementById("map_canvas"), settings);
<?php
      echo"    var Talleres = new Array($number-1);\n";

      while ($i < $number)
      {
        //$fila = array(mysql_result($result, $i, 'tipo'), mysql_result($result, $i, 'nombre'), mysql_result($result, $i, 'lat'), mysql_result($result, $i, 'lng'));
        //echo"    Talleres[$i]=$fila;\n";

        $tipo   = mysql_result($result, $i, 'tipo');
        $nombre = mysql_result($result, $i, 'nombre');
        $lat    = mysql_result($result, $i, 'lat');
        $lng    = mysql_result($result, $i, 'lng');
        $dire   = mysql_result($result, $i, 'direccion');
        $tel    = mysql_result($result, $i, 'telefonos');
        $info   = mysql_result($result, $i, 'info_extra');
        $url    = mysql_result($result, $i, 'url');
    ?>

        var itemImage = new google.maps.MarkerImage('images/img<?php echo $tipo;?>.png',
                                                    new google.maps.Size(40,40),
                                                    new google.maps.Point(0,0),
                                                    new google.maps.Point(10,40)
                                                   );

        var itemShadow = new google.maps.MarkerImage('images/shadow.png',
                                                     new google.maps.Size(60,40),
                                                     new google.maps.Point(0,0),
                                                     new google.maps.Point(10,40)
                                                    );

        var itemPos = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);

        var itemMarker<?php echo $i; ?> = new google.maps.Marker({position: itemPos,
     				                                  map: map,
                 					          icon: itemImage,
                 					          shadow: itemShadow,
                					          title:"<?php echo $nombre; ?>",
                               				          zIndex: 1
                                                                 });
                                                                 
        var contentString<?php echo $i; ?> = '<div id="content">'+
                            		     '<div id="siteNotice">'+
                  		  	     '</div>'+
                  			     '<h1 id="firstHeading" class="firstHeading"><font color="000000"><b><?php echo $nombre; ?></b></font></h1>'+
                  			     '<div id="bodyContent">'+
                  			     '<p>Dirección: <?php echo $dire; ?></p>'+
                  			     '<p>Teléfonos: <?php echo $tel; ?></p>'+
                  			     '<p><?php echo $info; ?></p>'+
                  			     '<p><a href="<?php echo $url; ?>" target="_blank"><font color="BF311A">Experiencia de usuarios</font></a></p>'+
                  			     '</div>'+
                  			     '</div>';
        var infowindow<?php echo $i; ?> = new google.maps.InfoWindow({content: contentString<?php echo $i; ?>});
        google.maps.event.addListener(itemMarker<?php echo $i; ?>, 'click', function() {infowindow<?php echo $i; ?>.open(map,itemMarker<?php echo $i; ?>);});

<?php
        $i++;
      }
    }
    mysql_free_result($result);
    mysql_close();
?>

  }
  </script>
</head>
<body onload="initialize()">
  <div id="map_canvas" style="width:700px; height:500px"></div>
</body>
</html>