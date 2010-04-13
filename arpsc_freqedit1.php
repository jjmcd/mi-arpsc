<?php
//    arpsc_freqedit1.php
//    $Revision: 1.0 $ - $Date: 2010-04-12 09:49:55-04 $
//
//    Get the user's district preparatory to editing county frequencies
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
?>
  <div id="main">
    <h1><center>Maintain Frequencies</center></h1>
      <p><center>
        <p>Select District</p>
        <p><form name="selectDist" method="post" action="arpsc_freqedit2.php">
          <select name="district" >
<?php
    $SQL = "SELECT `arpsc_district`,`districtkey` " .
      "FROM `arpsc_districts` " .
      "WHERE `districtkey` < 100 " .
      "ORDER BY `districtkey`";
    $res = getResult( $SQL, $db );
    while ( $row = getRow( $res ) )
      {
	echo "            <option value=\"" . $row[1] . "\" >" . $row[0] ."</option>\n";
      }
    echo "          </select></p>\n";
    echo "          <p><input type=\"submit\" name=\"Submit\" value=\"Submit\"></p>\n";
    echo "        </form>\n";
    echo "      </center></p>\n";
    echo "  </div>\n";
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.0 $ - $Date: 2010-04-12 09:49:55-04 $");
?>
</div>
</body>
</html>
