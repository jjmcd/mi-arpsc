<?php
//    arpsc_freqedit1.php
//    $Revision: 1.0 $ - $Date: 2010-04-12 09:49:55-04 $
//
//    Get the frequency fo a county
//
    include('includes/session.inc');
    $title=_('Michigan County Emergency Frequencies');

    include('includes/functions.inc');

    // Remember the launch time
    $starttime = strftime("%A, %B %d %Y, %H:%M");

    // Open the database
    $db = mysql_connect($host , $dbuser, $dbpassword);
    mysql_select_db($DatabaseName,$db);

    ARESheader($title,"ARES RACES ARPSC Michigan Section");
    ARESleftBar( $db );
    if (isset($_GET['district']))
      {
	$district = $_GET['district'];
      }
    else
      {
	if (isset($_POST['district']))
	  $district = strtoupper($_POST['district']);
      }
?>
  <div id="main">
    <h1><center>Maintain Frequencies</center></h1>
      <p><center>

        <p><form name="selectDist" method="post" action="arpsc_freqedit3.php">
          <select name="countyfreq" >
<?php

    $SQL = "SELECT `countycode`, `countyname` FROM `arpsc_counties` " .
      "WHERE `district`=" . $district . " " .
      "ORDER BY `countyname`";

    $res = getResult( $SQL, $db );
    while ( $row = getRow( $res ) )
      {
	echo "            <option value=\"" . $row[0] . "\" >" . $row[1] .
	  "</option>\n";
      }
    echo "          </select>\n";
    echo "          &nbsp;Freq: <input name=\"freq\" type=\"text\" /></p>\n";
    echo "          <input type=\"hidden\" name=\"district\" value=\"" . 
      $district . "\">\n";
    echo "          <p><input type=\"submit\" name=\"Submit\" " .
      "value=\"Submit\"></p>\n";
    echo "        </form>\n";
    echo "      </center></p>\n";
    echo "      <p><center>To exit, simply close this page or select " .
      "another link.</center></p>\n";
    echo "      <p><center><img src=\"countyfreqs.php\" width=\"460\" " .
      "height=\"519\" /></center></p>\n";
    echo "  </div>\n";
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.0 $ - $Date: 2010-04-12 09:49:55-04 $");
?>
</div>
</body>
</html>
