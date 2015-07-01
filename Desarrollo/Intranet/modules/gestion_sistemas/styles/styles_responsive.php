<?php
session_start();
header('content-type:text/css');
$paginas = 4;
if(isset($_SESSION['CANTIDADBOTONES'])) {
  $paginas = $_SESSION['CANTIDADBOTONES'];
}

$porcentajeporpagina = 100/$paginas;
 
echo <<<FINCSS
@charset "UTF-8";

/* ------------------------------------------
  RESET
--------------------------------------------- */

body, div,
h1, h2, h3, h4, h5, h6,
p, blockquote, pre, dl, dt, dd, ol, ul, li, hr,
fieldset, form, label, legend, th, td,
article, aside, figure, footer, header, hgroup, menu, nav, section,
summary, hgroup {
  margin: 0;
  padding: 0;
  border: 0;
  font-family: Neo Sans;
  font-size: 12;
  font-style: normal;  
  font-weight: normal;  
}

a:active,
a:hover {
   outline: 0;
}

@-webkit-viewport { width: device-width; }
@-moz-viewport { width: device-width; }
@-ms-viewport { width: device-width; }
@-o-viewport { width: device-width; }
@viewport { width: device-width; }


/* ------------------------------------------
  BASE DEMO STYLES
--------------------------------------------- */

body {
  -webkit-text-size-adjust: 100%;
  -ms-text-size-adjust: 100%;
  text-size-adjust: 100%;
  color: #37302a; 
  background:#f1f1f1;
  font-family: Neo Sans;
  font-size: 10;
}

section {
  border-bottom: 1px solid #999;
  float: left;
  width: 100%;
  height: 800px;
}

/* ------------------------------------------
  NAVIGATION STYLES
  (+ responsive-nav.css file is loaded in the <head>)
--------------------------------------------- */

.fixed {
  position: fixed;
  width: 100%;
  top: 0;
  left: 0;
}

.nav-collapse,
.nav-collapse * {
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}

.nav-collapse,
.nav-collapse ul {
  list-style: none;
  width: 100%;
  float: left;
}

.nav-collapse li {
  float: left;
  width: 100%;  
}

.nav-collapse a:hover {
	/*
	background: #f1f1f1;
	color:black;
	*/
	opacity:.7;
}

.nav-collapse a {
  color: #fff;
  text-decoration: none;
  width: 100%;
/*  background: #f4421a; */
  background: #818085; 
  border-bottom: 1px solid white;
  padding: 0.7em 1em;
  float: left;
}

.nav-collapse ul ul a {
  background: #ca3716;
  padding-left: 2em;
}

/* ------------------------------------------
  NAV TOGGLE STYLES
--------------------------------------------- */

@font-face {
  font-family: "responsivenav";
  src:url("../icons/responsivenav.eot");
  src:url("../icons/responsivenav.eot?#iefix") format("embedded-opentype"),
    url("../icons/responsivenav.ttf") format("truetype"),
    url("../icons/responsivenav.woff") format("woff"),
    url("../icons/responsivenav.svg#responsivenav") format("svg");
  font-weight: normal;
  font-style: normal;
}

.nav-toggle {
  position: fixed;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  text-decoration: none;
  text-indent: -999px;
  position: relative;
  overflow: hidden;
  width: 70px;
  height: 55px;
  float: right;
}

.nav-toggle:before {
  color: #6A82D4; /* Edit this to change the icon color */  
  font-family: "responsivenav", sans-serif;
  font-style: normal;
  font-weight: normal;
  font-variant: normal;
  font-size: 28px;
  text-transform: none;
  position: absolute;
  content: "≡";
  text-indent: 0;
  text-align: center;
  line-height: 55px;
  speak: none;
  width: 100%;
  top: 0;
  left: 0;
}

.nav-toggle.active::before {
  font-size: 24px;
  content:"x";
}
	
@media screen and (min-width: 770px) {
  .nav-collapse ul ul a {
    display: none;
  }
  
  .nav-collapse li {
    width: $porcentajeporpagina%;
    *width: 24.9%; /* IE7 Hack */
    _width: 19%; /* IE6 Hack */
  }
  
  .nav-collapse a {    
	margin: 0;
    padding: 1em;
    float: left;
    text-align: center;
    border-bottom: 0;
    border-right: 1px solid white;
  }
}


FINCSS;
?>


