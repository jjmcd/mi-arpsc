<?php
//    meetingSignup.php
//    $Revision: 1.1 $ - $Date: 2013-02-04 19:07:00-04 $
//
//    Select slot for meeting signup
//
{
  include('includes/session.inc');
  $title=_('Meeting Signup');

  include('includes/functions.inc');

  // Remember the launch time
  $starttime = strftime("%A, %B %d %Y, %H:%M");

  // Open the database
  $db = mysql_connect($host , $dbuser, $dbpassword);
  mysql_select_db($DatabaseName,$db);

  ARESheader($title,"ARES Michigan Section ARPSC");

  ARESleftBar( $db );
  echo "    <div id=\"main\">\n";
  echo "      <p><h1><center>Online Meeting Signup</center></h1></p>\n";

  // Get the meeting ID from the argument list
  $meetid = $_GET['meetid'];

  $SQL0 = "SELECT `description` FROM `meet_meetings` WHERE `mid`=" .
    $meetid;
  if ( $result0=getResult($SQL0,$db) )
    {
      if ($row0 = getRow($result0,$db))
	{
	  echo "      <center><h2>" . $row0[0] . "</h2></center>\n";
	  echo "      <center>\n";
	  echo "        <form name=\"signup\" method=\"post\" " .
	    "action=\"meetingSignup1.php?mid=" . $meetid . "\" >\n";
	  echo "        <p><b>Select session to attend</p>\n";
	  
	  $SQL1 = "SELECT `seq`,`date`,`capacity` FROM `meet_sessions` " .
	    "WHERE `mid`=" . $meetid . " ORDER BY `seq`";
	  $result1=getResult($SQL1,$db);
	  echo "        <select name=\"session\">\n";
	  while( $row1 = getRow($result1,$db) )
	    {
	      $SQL2 = "SELECT COUNT(*) FROM `meet_signup` WHERE " .
		"`mid`=" . $meetid . " AND " .
		"`seq`=" . $row1[0];
	      $res2 = getResult($SQL2,$db);
	  $row2 = getRow($res2,$db);
	  $slotsleft = $row1[2] - $row2[0];
	  //echo "<p>" . $row1[0] . ": " . $row1[1] . " (" . $row2[0] . ")</p>\n";
	  if ( $slotsleft > 0 )
	    echo "          <option value=\"" . $row1[0] . "\">" . $row1[1] . 
	      "</option>\n";
	}
	  echo "        </select>\n";
	  echo "        <p><input type=\"submit\" value=\"Submit\"></p>\n";
	  echo "        </form>\n";
	  echo "      </center>\n";
	}
      else
	echo "<center><h1>INVALID MEETING</h1></center>\n";
    }
  else
    echo "<center><h1>INVALID MEETING</h1></center>\n";
  echo "    </div>\n";
  
  sectLeaders($db);
  footer($starttime,$maxdate,
	 "\$Revision: 1.1 $ - \$Date: 2013-02-04 19:07:00-04 $");
}
?>
</div>
</body>
</html>
