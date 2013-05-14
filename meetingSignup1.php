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
?>
<script type="text/javascript" language="javascript">
function isEmailAddr(elem)
{
    var str = elem.value;
    str = str.toLowerCase();
    if (str.indexOf("@") > 1)
    {
	var addr = str.substring(0,str.indexOf("@"));
	var domain = str.substring(str.indexOf("@") + 1,str.length);
	if (domain.indexOf(".") == -1)
        {
            alert("Verify the domain portion of the email address.");
            return false;
        }
        for ( var i=0; i<addr.length; i++ )
        {
            oneChar = addr.charAt(i).charCodeAt(0);
            if ((i==0 && (oneChar==45 || oneChar==46)) ||
                (i==addr.length-1 && oneChar==46))
            {
                alert("Verify the user name portion of the email address.");
                return false;
            }
            if ( oneChar==45 || oneChar==46 || oneChar==95 ||
                 (oneChar>47 && oneChar<58) || (oneChar>96 && oneChar<123))
            {
                continue;
            }
            else
            {
                alert("Verify the user name portion of the email address.");
                return false;
            }
	}
        for ( i=0; i<domain.length; i++ )
        {
            oneChar = domain.charAt(i).charCodeAt(0);
            if ((i==0 && (oneChar==45 || oneChar==46)) ||
                ((i==domain.length-1 || i==domain.length-2) && oneChar==46))
            {
                alert("Verify the domain portion of the email address.");
                return false;
            }
            if (oneChar==45 || oneChar==46 || oneChar==95 ||
                (oneChar>47 && oneChar<58) || (oneChar>96 && oneChar<123))
            {
                continue;
            }
            else
            {
                alert("Verify the domain portion of the email address.");
                return false;
            }
        }
        return true;
    }
    alert("The email address may not be formatted correctly. Please verify.");
    return false;
}
function isNotEmpty(elem) 
{
    var str = elem.value;
    var re = /.+/;
    if (!str.match(re))
    {
	alert("Please fill in the required field.");
	return false; 
    } 
    else 
    { 
	return true; 
    } 
}
function validateForm( form )
{
    if (isNotEmpty(form.call))
    {
	if (isNotEmpty(form.email))
        {
	    if (isEmailAddr(form.email)) 
	    {
		return true;
	    }
	}
    }
    return false;
}
</script>
<?php
  echo "      <p><h1><center>Online Meeting Signup</center></h1></p>\n";
  echo "      <center>\n";

  // Get the meeting ID from the argument list
  $meetid = $_GET['mid'];

  $session = $_POST['session'];

  //echo "<p>Session=" . $session . ".</p>\n";


  $SQL0 = "SELECT `description` FROM `meet_meetings` WHERE `mid`=" .
    $meetid;
  //echo "    <p>" . $SQL0 . "</p>\n";
  $result0=getResult($SQL0,$db);
  $row0 = getRow($result0,$db);
  $desc = $row0[0];
  echo "      <h1>" . $desc . "</h1>\n";

  $SQL1 = "SELECT `date` FROM `meet_sessions` WHERE `mid`=" .
    $meetid . " AND `seq`=" . $session;
  $res1 = getResult($SQL1,$db);
  $row1 = getRow($res1,$db);
  echo "      <p>Session #" . $session . " - " . $row1[0] . "</p>\n";
  echo "        <form name=\"slot\" method=\"post\" " .
    "action=\"meetingSignup2.php?mid=" . $meetid , "&seq=" . $session .
    "\"  onsubmit=\"return validateForm(this)\" >\n";
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
  echo "      </center>\n";  
  echo "    </div>\n";
  sectLeaders($db);
  footer($starttime,$maxdate,
	 "\$Revision: 1.1 $ - \$Date: 2013-02-04 19:07:00-04 $");
}
?>
</div>
</body>
</html>
