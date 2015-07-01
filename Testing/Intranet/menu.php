<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
?>
<script type="text/javascript">
<!--
stm_bm(["menu1226",820,"","/images/blank.gif",0,"","",1,4,19,19,1500,1,0,0,"","",0,0,1,1,"default","pointer",""],this);
stm_bp("p0",[0,4,0,0,2,2,0,0,100,"",-2,"",-2,50,0,0,"#999999","#807F84","",3,0,0,"#999999"]);
stm_ai("p0i0",[0,"           ÚTILES            ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",-1,-1,0,0,1,"#FFFFF7",1,"#FFFFF7",1,"","",3,3,1,1,"#999999","#EDEBEB","#FFFFFF","#FFFFFF","bold 9pt Neo Sans","bold 9pt Neo Sans",0,0]);
stm_bp("p1",[1,4,-3,2,4,0,0,0,100,"stEffect(\"slip\")",-2,"stEffect(\"slip\")",-2,65,2,3,"#999999","#FFFFFF","",3,1,1,"#CCCCCC"]);
stm_aix("p1i0","p0i0",[0,"Agenda Telefónica","","",-1,-1,0,"/index.php?pageid=5","frameMain","","","","",0,0,0,"","",0,0,0,0,1,"#FFFFF7",1,"#FFFFF7",1,"","",3,3,1,2,"#FFFFFF","#FFFFFF","#999999","#6E95BC","9pt Neo Sans","9pt Neo Sans"]);
stm_aix("p1i1","p1i0",[0,"Sucursales","","",-1,-1,0,"/index.php?pageid=69","_self"]);
stm_aix("p1i2","p1i0",[0,"Normativa Interna","","",-1,-1,0,"/index.php?pageid=40"]);
stm_aix("p1i3","p1i0",[0,"Delivery","","",-1,-1,0,"/index.php?pageid=39"]);
stm_aix("p1i4","p1i0",[0,"Clima","","",-1,-1,0,"http://www.smn.gov.ar/","_blank"]);
stm_aix("p1i5","p1i0",[0,"Sindicato del Seguro","","",-1,-1,0,"http://www.sindicatodelseguro.com/index.html","_blank"]);
stm_aix("p1i6","p1i0",[0,"Programa de Reciclado","","",-1,-1,0,"http://www.vaporlospibes.com.ar/","_blank"]);
stm_aix("p1i7","p1i0",[0,"Diccionarios","","",-1,-1,0,"/index.php?pageid=51","_self"]);
stm_mc("p1",[11,"#000000",0,5,"",0]);
stm_ep();
stm_aix("p0i1","p0i0",[0,"        BENEFICIOS         ","","",-1,-1,0,"/index.php?pageid=43","frameMain","","","","",0,0,0,"","",0,0]);
stm_bpx("p2","p1",[]);
stm_ep();
stm_aix("p0i2","p0i0",[0,"      RECURSOS HUMANOS      ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",-1,-1,0,0,1,"#FFFFF7",1,"#FFFFF7",1,"","",3,3,1,1,"#999999","#FFFFF7"]);
stm_bpx("p3","p1",[1,4,0,2,4,0,0,0,100,"stEffect(\"slip\")",-2,"stEffect(\"slip\")",-2,40]);
stm_aix("p3i0","p1i0",[0,"Novedades","","",-1,-1,0,"/index.php?pageid=6","frameMain","","","","",0,0,0,"","",0,0,0,0,1,"#FFFFF7",1,"#FFFFF7",1,"","",3,3,0,0,"#6699FF","#FFFFF7"]);
stm_aix("p3i1","p3i0",[0,"Ausentismo","","",-1,-1,0,"/index.php?pageid=7"]);
stm_aix("p3i2","p3i0",[0,"Búsquedas Laborales","","",-1,-1,0,"/index.php?pageid=61"]);
stm_aix("p3i3","p3i0",[0,"Organigrama","","",-1,-1,0,"/index.php?pageid=54","frameMain","","","","",0,0,0,"","",0,0]);
stm_aix("p3i4","p3i0",[0,"Obras Sociales","","",-1,-1,0,"/index.php?pageid=45"]);
stm_aix("p3i5","p3i0",[0,"Formación","","",-1,-1,0,"/index.php?pageid=65"]);
<?
if (HasPermiso(11)) {
?>
stm_aix("p3i7","p3i0",[0,"Mantenimiento","","",-1,-1,0,"/index.php?pageid=11"]);
<?
}
?>
stm_aix("p3i6","p3i0",[0,"Autogestión","","",-1,-1,0,"http://10.60.1.3/rhprox2/ess/index2.asp","_blank"]);
stm_ep();
stm_aix("p0i3","p0i2",[0,"          NOTICIAS           "]);
stm_bpx("p4","p1",[1,4,0]);
stm_aix("p4i0","p3i0",[0,"Institucionales","","",-1,-1,0,"/index.php?pageid=25"]);
stm_aix("p4i1","p3i0",[0,"Arteria Noticias","","",-1,-1,0,"/index.php?pageid=52"]);
stm_aix("p4i2","p3i0",[0,"Síntesis de Prensa","","",-1,-1,0,"/index.php?pageid=12"]);
stm_aix("p4i3","p3i0",[0,"Boletín Oficial","","",-1,-1,0,"/index.php?pageid=14"]);
stm_aix("p4i4","p3i0",[0,"Normativa","","",-1,-1,0,"/index.php?pageid=31"]);
stm_aix("p4i5","p3i0",[0,"Seguridad Informática","","",-1,-1,0,"http://www.artprov.com.ar/SegInfo/index.html","_blank"]);
stm_aix("p4i6","p3i0",[0,"Protección de Datos Personales","","",-1,-1,0,"/index.php?pageid=44"]);
stm_ep();
stm_aix("p0i4","p0i2",[0,"          SOCIALES           "]);
stm_bpx("p5","p1",[]);
stm_aix("p4i0","p4i0",[0,"Cumpleaños","","",-1,-1,0,"/index.php?pageid=18","frameMain","","","","",0,0,0,"","",0,0,0,0,1,"#FFFFF7",1,"#FFFFF7",1,"","",3,3,0,0,"#FFFFFF","#0099FF","#999999","#6E95BC"]);
stm_aix("p4i1","p4i0",[0,"Nacimientos","","",-1,-1,0,"/index.php?pageid=32"]);
stm_aix("p4i2","p4i0",[0,"Casamientos","","",-1,-1,0,"/index.php?pageid=19"]);
stm_aix("p4i3","p4i0",[0,"Graduaciones","","",-1,-1,0,"/index.php?pageid=41"]);
stm_aix("p4i4","p4i0",[0,"Fotos","","",-1,-1,0,"/index.php?pageid=22"]);
stm_ep();
stm_aix("p0i5","p0i2",[0,"          SISTEMAS           ","","",-1,-1,0,"/index.php?pageid=21","frameMain","","","","",0,0,0,"","",0,0]);
stm_ep();
stm_em();
//-->
</script>
<noscript>
	<span style="background-color:#ff0;">Usted tiene JavaScript desactivado. Para navegar correctamente por la Intranet debe tener activado JavaScript.</span>
</noscript>