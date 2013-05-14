<?php

//    eventcal.php
//    $Revision: 1.2 $ - $Date: 2007-11-06 08:27:13-05 $
//
//    eventcal displays the upcoming events for the next 90 days
//
{
  include('includes/session.inc');
  $title=_('Michigan Section Event Calendar');

  include('includes/functions.inc');

  $db = mysql_connect($host , $dbuser, $dbpassword);
  mysql_select_db($DatabaseName,$db);

  ARESheader($title,"ARES Michigan Section ARPSC RACES");

  ARESleftBar( $db );

  // Remember the launch time
  $starttime = strftime("%A, %B %d %Y, %H:%M");
  $maxdate = $starttime;

  echo '  <div id="main">' . "\n";
  echo '    <h1>Calendar of Events</h1>' . "\n";

  // Determine the dates of the events to search for
  $now = mktime();
  // Today as ISO date
  $searchdate = strftime("%Y-%m-%d",$now);
  // Today + 90 days
  $then = $now + 86400*90;
  // 90 days hence as ISO date
  $enddate = strftime("%Y-%m-%d",$then);
  // Will want to remember when month changes
  $prevmonth = " ";
  $ifall = $_GET['all'];

  // Get the events for the next 90 days
  $s1 = "SELECT `eventdate`, `eventtext`, `url`, `flag` FROM " .
    "`arpsc_events` WHERE `eventdate`>'" . $searchdate . "' AND " .
    "`eventdate`<'" . $enddate . "' ORDER BY `eventdate`";
  if ( !strcmp($ifall,"yes") )
  $s1 = "SELECT `eventdate`, `eventtext`, `url`, `flag` FROM " .
    "`arpsc_events` WHERE `eventdate`>'" . $searchdate . 
    "' ORDER BY `eventdate`";

  $r1 = getResult($s1,$db);

  // Loop through the events in the database
  while ( $row1 = getRow($r1,$db) )
    {
      $eventtime = strtotime( $row1[0] );
      // If this is a new month, display the month in big letters
      if ( $prevmonth != strftime("%b",$eventtime) )
	{
	  echo "      <h3>" . strftime("%B",$eventtime) . "</h3>\n";
	  $prevmonth = strftime("%b",$eventtime);
	}
      // Show the event date
      echo '        ' . strftime("%B %d",$eventtime) . " - \n          ";
      // If it is one of ours, show it in a unique style
      // Note the limitation that "ours" must have an URL
      $st="";
      if ( $row1[3] == 1 )
	{
	  $st=' style="font-size: 12pt; background: #fdd;  "';
	}
      // If there is no URL, just display the event name
      if ( $row1[2] == '' )
	{
	  echo $row1[1];
	}
      // Otherwise display the name as a link to the URL
      else
	{
	  echo '<a href="' . $row1[2] . '" target="other"' . $st . '>' . 
	    $row1[1] . "</a>";
	}
      echo "<br />\n";
    }

  // Get most recent date
  $s2 = "SELECT MAX(`updated`) FROM " .
    "`arpsc_events` WHERE `eventdate`>'" . $searchdate . "' AND " .
    "`eventdate`<'" . $enddate . "' ORDER BY `eventdate`";
  $r2 = getResult($s2,$db);
  if ( $row2 = getRow($r2,$db) )
    {
      $maxdate = $row2[0];
    }

  echo "  </div>\n";
  sectLeaders($db);
  footer($starttime,$maxdate,
	 "\$Revision: 1.2 $ - \$Date: 2007-11-06 08:27:13-05 $");
}
?>
</body>
</html>
