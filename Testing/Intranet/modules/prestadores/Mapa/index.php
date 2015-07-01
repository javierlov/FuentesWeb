<html>
<head>
  <title>Provincia ART - Mapa interactivo de prestadores</title>
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
  <link rel="stylesheet" type="text/css" media='screen,print' href="http://www.ForoAlfaRomeo.com/public/min/index.php?ipbv=32005&amp;f=public/style_css/css_3/SOS_BBCodes.css,public/style_css/css_3/calendar_select.css,public/style_css/css_3/ipb_common.css,public/style_css/css_3/ipb_styles.css,public/style_css/css_3/ipb_ckeditor.css,public/style_css/prettify.css" />
  <?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");

    $referrerIsValid = false;
    $hasReferrer = false;
    if (isset($_SERVER['HTTP_REFERER'])) {
      $parts = parse_url($_SERVER['HTTP_REFERER']);
      if (isset($parts['host'])) {
        $hasReferrer = true;
        $referrerIsValid = (bool) preg_match('/(?:^|\.)artprov\.com$/', strtolower($parts['host']));
      }
    }

    $sql = "SELECT ROUND (sys.DBMS_RANDOM.VALUE (1, 7)) AS tipo,
       ca_descripcion,
       tp_descripcion,
       ca_telefono,
       ca_calle || ' ' || ca_numero || ' ' || ca_localidad AS domicilio,
       ca_direlectronica,
       REPLACE (ca_lat, ',', '.') AS ca_lat,
       REPLACE (ca_lng, ',', '.') AS ca_lng
  FROM art.cpr_prestador, mtp_tipoprestador
 WHERE ca_especialidad = tp_codigo
   AND ca_lat IS NOT NULL
   AND ca_lng IS NOT NULL
   AND ca_provincia IN (1, 2) and rownum < 300
";
    $stmt = DBExecSql($conn, $sql);
    $number = DBGetRecordCount($stmt);
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
      while ($row = DBGetQuery($stmt)) {
        $tipo   = $row["TIPO"];
        $nombre = $row["CA_DESCRIPCION"];
        $lat    = $row['CA_LAT'];
        $lng    = $row['CA_LNG'];
        $dire   = $row['DOMICILIO'];
        $tel    = $row['CA_TELEFONO'];
        $info   = $row['TP_DESCRIPCION'];
        $url    = $row['CA_DIRELECTRONICA'];
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
                  			     '<p><a href="<?php echo $url; ?>" target="_blank"><font color="BF311A">Correo electrónico</font></a></p>'+
                  			     '</div>'+
                  			     '</div>';
        var infowindow<?php echo $i; ?> = new google.maps.InfoWindow({content: contentString<?php echo $i; ?>});
        google.maps.event.addListener(itemMarker<?php echo $i; ?>, 'click', function() {infowindow<?php echo $i; ?>.open(map,itemMarker<?php echo $i; ?>);});

<?php
        $i++;
      }
    }
    //mysql_free_result($result);
    //mysql_close();
?>

  }
  </script>
</head>
<body onload="initialize()">
  <div id="map_canvas" style="width:700px; height:500px"></div>
</body>
</html>