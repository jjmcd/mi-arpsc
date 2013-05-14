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
  echo "      <center>\n";

  // Get the meeting ID from the argument list
  $meetid = $_GET['mid'];
  $seq = $_GET['seq'];
  $call = strtoupper($_POST['call']);
  $email = strtolower($_POST['email']);

  //echo "<p>mid=" . $meetid . ", seq=" . $seq .
  //  ", call=" . $call . ", email=" . $email . "</p>\n";

  $SQL0 = "SELECT `description` FROM `meet_meetings` WHERE `mid`=" .
    $meetid;
  //echo "    <p>" . $SQL0 . "</p>\n";
  $result0=getResult($SQL0,$db);
  $row0 = getRow($result0,$db);
  $desc = $row0[0];
  echo "      <h1>" . $desc . "</h1>\n";

  $SQL1 = "SELECT `date`,`capacity` FROM `meet_sessions` WHERE `mid`=" .
    $meetid . " AND `seq`=" . $seq;
  $res1 = getResult($SQL1,$db);
  $row1 = getRow($res1,$db);
  echo "      <h2>" . $row1[0] . "</h2>\n";

  $SQL2 = "SELECT COUNT(*) FROM `meet_signup` WHERE `mid`=" . $meetid .
    " AND `seq`=" . $seq;
  $res2 = getResult($SQL2,$db);
  $row2 = getRow($res2,$db);
  $slotsleft = $row1[1]-$row2[0];
  $slot = $row2[0] + 1;
  if ( $slotsleft < 1 )
    {
      echo "<h1>Sorry, no seats available for that session!</h1>\n";
    }
  else
    {
      echo "<h2>" . $slotsleft . " seats remaining,<br />Processing registration for " . $call . ".</h2>\n";
      echo "      </center>\n";  
      echo "<ul>\n";
      echo "<li>Meeting ID: <b>" . $meetid . "</b></li>\n";
      echo "<li>Session: <b>" . $seq . "</b></li>\n";
      echo "<li>Slot: <b>" . $slot . "</b></li>\n";
      echo "<li>Call: <b>" . $call . "</b></li>\n";
      echo "<li>Email: <b>" . $email . "</b></li>\n";
      echo "</ul>\n";

      $SQL3 = "INSERT INTO `meet_signup` VALUES(" .
	$meetid . "," .
	$seq . "," .
	$slot . "," .
	"'" . $call . "'," .
	"'" . $email . "'," .
	"now())";
      //echo "<p>" . $SQL3 . "</p>\n";
      $res4 = mysql_query( $SQL3, $db );
      echo "<p>One day before the meeting an email will be sent to <b>" .
	$email . "</b> with specifics of call in number and PIN, URL " .
	"for the web view and any other necessary information for the " .
	"session.</p>\n";
    }

  /*
  echo "      <p>Session #" . $session . " - " . $row1[0] . "</p>\n";
  echo "        <form name=\"slot\" method=\"post\" " .
    "action=\"meetingSignup2.php?mid=" . $meetid , "&seq=" . $session .
    "\">\n";
  echo "          <table width=\"90%\">\n";
  echo "            <tr>\n";
  echo "              <th align=\"right\">Call &nbsp; </th>\n";
  echo "              <td align=\"left\">\n";
  echo "                <input type=\"text\" size=\"12\" name=\"call\">\n";
  echo "              </td>\n";
  echo "            </tr>\n";
  echo "            <tr>\n";
  echo "              <th align=\"right\">email &nbsp; </th>\n";
  echo "              <td align=\"left\">\n";
  echo "                <input type=\"text\" size=\"40\" name=\"email\">\n";
  echo "              </td>\n";
  echo "            </tr>\n";
  echo "          </table>\n";
  echo "          <p><input type=\"submit\" value=\"Submit\"></p>\n";

  echo "        </form>\n";
  */
  echo "    </div>\n";
  sectLeaders($db);
  footer($starttime,$maxdate,
	 "\$Revision: 1.1 $ - \$Date: 2013-02-04 19:07:00-04 $");
}
?>
</div>
</body>
</html>
