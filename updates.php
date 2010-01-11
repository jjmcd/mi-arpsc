<?php
//    updates.php
//    $Revision: 1.1 $ - $Date: 2007-10-30 16:10:49-04 $
//
    include('includes/session.inc');
    $title=_('Michigan Section ARES');

    include('includes/functions.inc');

    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    ARESheader($title,"ARES Net Michigan Section ARPSC RACES");

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    ARESleftBar( $db );

  // Remember the launch time
  $starttime = strftime("%A, %B %d %Y, %H:%M");

  // Determine the dates of the events to search for
  $now = mktime();
  // Today as ISO date
  $searchdate = strftime("%Y-%m-%d",$now);
  // Today - 7 days
  $then = $now - 86400*7;
  // 7 days ago as ISO date
  $checkdate = strftime("%Y-%m-%d",$then);

  $maxdate=0;

?>
  <div id="main">
<p></p>
<?php
    echo '<h1>Updated Pages</h1>' . "\n";

    $s1='SELECT MAX(`updated`) FROM `arpsc_ecrept`';
    echo "<h3>FSD-212</h3>\n";
    echo "<p>FSD-212 data last updated " . singleResult($s1,$db) . ".</p>\n";
    if ( singleResult($s1,$db)>$maxdate )
	$maxdate=singleResult($s1,$db);

    $s1='SELECT MAX(`updated`) FROM `nets`';
    echo "<h3>Nets</h3>\n";
    echo "<p>Net data last updated " . singleResult($s1,$db) . ".</p>\n";
    if ( singleResult($s1,$db)>$maxdate )
	$maxdate=singleResult($s1,$db);

    $s1='SELECT MAX(`updated`) FROM `arpsc_events`';
    echo "<h3>Events</h3>\n";
    echo "<p>Event data last updated " . singleResult($s1,$db) . ".  Note that only the events for the coming 90 days are shown, so the new or modified event might not appear.</p>\n";
    if ( singleResult($s1,$db)>$maxdate )
	$maxdate=singleResult($s1,$db);

    $s1='SELECT MAX(`updated`) FROM `anavbar_subs`';
    echo "<h3>Navigation bar</h3>\n";
    echo "<p>The left hand navigation bar was last changed " . singleResult($s1,$db) . ".</p>\n";
    if ( singleResult($s1,$db)>$maxdate )
	$maxdate=singleResult($s1,$db);

    $s1="SELECT `pageid`, `updated` FROM `textblocks` WHERE `updated`>'" . $checkdate . "' " .
	"AND `pageid`>0 ORDER BY `pageid`,`updated`";
    $q1=getResult($s1,$db);
    echo "<h3>Text Pages</h3>\n";
    echo "<p>The following pages have been updated in the past 7 days:<br />\n";
    $lastblock = 0;
    while ( $r1 = getRow($q1,$db) )
    {
	if ( $r1[0] != $lastblock )
	{
	  if ( $r1[1]>$maxdate )
	    $maxdate=$r1[1];
	  $pageurl="txtblk.php?topic=" . $r1[0];
	  $s2="SELECT `pageid` FROM `webpages` WHERE `url`='" . $pageurl . "'";
	  $pageid=singleResult($s2,$db);
	  if ( $pageid>0 )
	  {
	    $s3="SELECT `buttontext` FROM `anavbar_subs` WHERE `pageid`=" . $pageid;
	    $buttontext=singleResult($s3,$db);
	    echo $buttontext . " updated on " . $r1[1] . ".<br />\n";
	  }
	  else
	  {
	    echo "txtblk.php?topic=" . $r1[0] . " updated on " . $r1[1] . ".<br />\n";
	  }
	  $lastblock = $r1[0];
	}
    }
    echo "</p>\n";

    echo "<h3>Section Leaders</h3>\n";
    $s1="SELECT MAX(`updated`) FROM `sect_appt`";
    $leaderdate=singleResult($s1,$db);
    $s2="SELECT `title`,`id` FROM `sect_appt` WHERE `updated`='" . $leaderdate . "'";
    $q2=getResult($s2,$db);
    while ( $r2 = getRow($q2,$db) )
    {
	echo "<p>" . $r2[0] . " updated on " . $leaderdate . ".</p>\n";
    }
    if ( $leaderdate>$maxdate )
	$maxdate=$leaderdate;

    echo "<h3>ARPSC Net Controls</h3>\n";
    echo "<p>The ARPSC Net Control page relies entirely on calculation, and " .
	"thus may change any time you look at it.</p>\n";

    echo "  </div>\n";
    sectLeaders($db);

    footer($starttime,$maxdate,
      "\$Revision: 1.1 $ - \$Date: 2007-10-30 16:10:49-04 $");
?>
</div>
</body>
</html>
