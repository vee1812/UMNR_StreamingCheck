<?php 
require_once 'proses_sc.php';

$sc = new Shout('localhost',666,'adminpassword',1);
// Mendapatkan Basis Data
if($sc->getAll())
{
   $xml = $sc->getXML();
}else{
   echo $sc->getError();
}
/*SETTING BOR!!*/
?>
<!--
					Mencoba Bijak PART 1

	- Belajar koding asik kalo lingkungan belajarnya juga asik.
	- Kalo udah pinter koding jangan pelit-pelit ngajarin ke orang lain.
	- To be continue...

	Regards...
	Vee_
-->

<script>
	// MixStream Flash Player, http://mixstreamflashplayer.net/ 
	var flashvars = {};flashvars.serverHost = "localhost:666/stream/1/";flashvars.getStats = "1";flashvars.autoStart = "0";flashvars.textColour = "";flashvars.buttonColour = "";var params = {};params.bgcolor= "000";
</script>
<script src="jsc/set.js" type="text/javascript" charset="utf-8" async defer></script>
<!DOCTYPE html>
<html>
<head>
	<title>Test Server</title>
</head>
<body>
<div style="margin: 50px; padding: 20px; border: 1px solid #333; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;">
			<h1>Shoutcsat Status</h1>
			<p>Aplikasi sederhana untuk nunjukkin status dari shoutcast </p>
			<p>Statusnya masih uji coba jadi jangan berharap banyak dulu hahaha!!!</p>
			<hr>
			<h2>SHOUTcast Status: <?php echo $sc->get('streamstatus');?></h2>
			<p>Kalo 1 berarti <font color="red"><b>Online</b></font> sedangkan 0 berarti <font color="green"><b>Offline</b></font></p>
			<hr>
			<p>Our stream is broadcasting at <b><?php echo $sc->get('bitrate'); ?>kbps </b>with <?php echo $sc->get('currentListeners'); ?> listeners (<span id="SHOUTcastListenersUnique"></span> unique IPs). People have been listening for an average of <span id="SHOUTcastAverageListenTime"></span>.</p>
			<p>The test server which this script is set up for supports <span id="SHOUTcastListenersMax"></span> listeners and has peaked at <span id="SHOUTcastListenersPeak"></span></p>
			<p>The current song is: <b><?php echo $sc->get('songtitle');?></b></span>.
			<p><span id="SHOUTcastTitle"></span> is streaming <span id="SHOUTcastGenre"></span> in <span id="SHOUTcastFormat"></span> format
				<script type="text/javascript" src="jsc/v1.3.js"></script>
			<h2>Song History:</h2>
			<ul id="played"></ul>

			<div <?php echo $sc->get('getSongHistory'); ?>></div>
		</div>
		
</body>
		<!-- <script type="text/javascript" src="http://mixstreamflashplayer.net/v1.3.js"></script> -->
		<script type="text/javascript" src="jsc/jquery.min.js"></script>
		<script type="text/javascript" src="jsc/easySetup.js"></script>
      	<script type="text/javascript" src="jsc/jquery.shoutcast.easy.min.js?host=localhost&port=666"></script>
</html>