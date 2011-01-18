<?php

//    arpscsked.php
//    $Revision: 1.3 $ - $Date: 2010-02-16 08:53:44-05 $
//
//	arpscsked displays the net control schedule for the MI-ARPSC
//	Sunday night net.
//
{
  include('includes/session.inc');
  $title=_('Michigan Section ARPSC Net Control Schedule');

  include('includes/functions.inc');

  // Connect to database
  $db = mysql_connect($host , $dbuser, $dbpassword);
  mysql_select_db($DatabaseName,$db);

  // Display the top banner etc.
  ARESheader($title,"ARES Michigan Section ARPSC RACES");

  // Left bar
  ARESleftBar( $db );

  // Remember the launch time
  $starttime = strftime("%A, %B %d %Y, %H:%M");
  $maxdate = $starttime;

  echo '  <div id="main">' . "\n";
  echo '    <h1>MI-ARPSC Net Control Schedule</h1>' . "\n";

  echo "      <p>The Michigan ARPSC Net meets each Sunday at 1700\n";
  echo "      local time near 3.932 MHz.  If conditions warrant, the\n";
  echo "      net will meet near 7.232 MHz.  These are the designated\n";
  echo "      Michigan Emergency Frequencies.</p>\n";
  echo "      <p>Net control duties rotate among the various districts\n";
  echo "      with a member of the section staff assuming net control\n";
  echo "      duties on the first Sunday of each month.</p>\n";

  // This array is indexed by week number.  It contains the number of the
  // district responsible for net control, with 0 being the staff.
  $ncs = array( 80,0,1,2,3,5,       // Jan
		0,6,7,8,
		0,1,2,3,
		0,5,6,7,            // Apr
		0,8,1,2,3,
		0,5,6,7,
		0,8,1,2,3,          // Jul
		0,5,6,7,
		0,8,1,2,
		0,3,5,6,7,          // Oct
		0,8,1,2,
		0,3,5,6
		);

  $now = mktime();
  $thismonth = date("m");

  $lastmonth = " ";

  // Loop through the coming 90 days
  for ( $i=0; $i<90; $i++ )
    {
      $timeparts = getdate( $now );
      // Is this date a Sunday?
      if ( $timeparts['wday'] == 0 )
	{
	  $result = strftime("%U",$now);	// Week number
	  $thisweek = round($result);	// Force to be a number
	  $thisncs=$ncs[$thisweek];		// Look up NCS district
	  // Display the month name in large type whenever it changes
	  if ( $timeparts['mon'] != $lastmonth )
	    {
	      echo "    <h3>" . $timeparts['month'] . "</h3>\n";
	      $lastmonth = $timeparts['mon'];
	    }
	  // Now show the month and day
	  echo '      ' . $timeparts['month'] . " " . $timeparts['mday'] . " - ";
	  // If the NCS is staff, say so, otherwise say "District <districtno>"
	  if ( $thisncs == 0 )
	    {
	      echo "<span style=\"color:steelblue;\">Section Staff</span><br />\n";
	    }
	  else
	    {
	      echo "District <b>" . $thisncs . "</b><br />\n";
	    }
	}
      $now += 86400;	// Add one day to current date
    }
  echo "    <p>The Michigan ARPSC net has a standard preamble\n";
  echo "      whose text is available \n";
  echo "      <a href=\"downloads/netpreamble.pdf\">here</a>.</p>\n";
  echo "    <hr />\n";

  // End of middle bar
  echo "  </div>\n";

  // Right bar
  sectLeaders($db);
  footer($starttime,$maxdate,"\$Revision: 1.4 $ - \$Date: 2011-01-07 09:33:44-05 $");
}
?>
</body>
</html>
