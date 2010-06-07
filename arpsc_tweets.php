<?php
//    arpsc_tweets.php
//
//    arpsc_tweets displays the mi_arpsc twitter timeline inside
//    a normal mi-arpsc frame.  The timeline is generated by some
//    javascript provided by twitter.
//
    include('includes/session.inc');
    $title=_('Michigan Section ARES/RACES');

    include('includes/functions.inc');

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    // Open the database
    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    ARESheader($title,"ARES RACES ARPSC Michigan Section Twitter");

    ARESleftBar( $db );
?>
  <div id="main">
<center>
    <h1>MI-ARPSC Twitter Timeline</h1>
      <p>&nbsp;</p>

      <script src="http://widgets.twimg.com/j/2/widget.js"></script>
      <script>
      new TWTR.Widget({
	version: 2,
	type: 'profile',
	rpp: 8,
	interval: 6000,
	width: 400,
	height: 400,
      theme: {
	shell: {
	  background: '#6699cc',
	  color: '#ffffff'
	   },
	   tweets: {
	   background: '#f8f8f8',
	   color: '#030303',
	   links: '#3399cc'
	   }
	 },
       features: {
	scrollbar: false,
	loop: false,
	live: true,
	hashtags: true,
	timestamp: true,
	avatars: true,
	behavior: 'all'
       }
    }).render().setUser('mi_arpsc').start();
      </script>

      </center>

      <p>&nbsp;</p>
      <p>
        To get real time updates, follow <b>mi_arpsc</b> on 
        <a href="twitter.com" target="other">Twitter</a>.  
        It is free to sign up.
      </p>
      <p>
        You may follow Twitter on the web, or you may have tweets sent 
        to your phone.  There are also a large number of Twitter clients 
        for the PC as well as smartphones.  Some of these can update 
        your Facebook or identi.ca status as well as your Twitter status.
      </p>
  </div>
<?php
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.1 $ - \$Date: 2010-06-07 09:09:13-05 $");
?>
</div>
</body>
</html>