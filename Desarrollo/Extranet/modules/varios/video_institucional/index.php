<?
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

$url = getFile(STORAGE_EXTRANET."videos/P_ART_Institucional_2012.mpg");
?>
<html>
	<body>
<!--
		<object classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112" standby="Cargando ..." type="application/x-oleobject">
			<param name="movie" value="<?= $url?>">
			<param name="showcontrols" value="1"/>
			<param name="showdisplay" value="0"/>
			<param name="showstatusbar" value="0"/>
			<param name="autosize" value="1"/>
			<param name="autostart" value="1"/>
		</object>
-->




		<object align="middle" border="5" classid="clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6" hspace="5" id="WindowsMediaPlayer1" vspace="5">
			<param name="URL" value="<?= $url?>" ref>
			<param name="rate" value="1">
			<param name="balance" value="0">
			<param name="currentPosition" value="0">
			<param name="defaultFrame" value>
			<param name="playCount" value="1">
			<param name="autoStart" value="1">
			<param name="currentMarker" value="0">
			<param name="invokeURLs" value="-1">
			<param name="baseURL" value>
			<param name="volume" value="50">
			<param name="mute" value="0">
			<param name="uiMode" value="full">
			<param name="stretchToFit" value="-1">
			<param name="windowlessVideo" value="0">
			<param name="enabled" value="-1">
			<param name="enableContextMenu" value="-1">
			<param name="fullScreen" value="0">
			<param name="SAMIStyle" value>
			<param name="SAMILang" value>
			<param name="SAMIFilename" value>
			<param name="captioningID" value>
			<param name="enableErrorDialogs" value="0">
			<param name="_cx" value="9260">
			<param name="_cy" value="9790">
			<embed type="application/x-mplayer2" pluginspage="http://microsoft.com/windows/mediaplayer/en/download/" id="mediaPlayer" name="mediaPlayer" displaysize="4" autosize="1" bgcolor="darkblue" showcontrols="true" showtracker="-1" showdisplay="0" showstatusbar="0" videoborder3d="-1" src="<?= $url?>" autostart="true" designtimesp="5311"></embed>
		</object>
	</body>
</html>