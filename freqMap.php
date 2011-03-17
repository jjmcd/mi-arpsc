<?php
//    freqmap.php
//    $Revision: 1.0 $ - $Date: 2010-04-12 09:49:55-04 $
//
//    Display the frequency map image on a standard page
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
    <h1><center>County Frequencies</center></h1>
      <p><center>
        <a target="freqMap" href="countyfreqs.php">
          <img src="countyfreqs.php" width="460" height="519" />
        </a>
        <p>Click on image to enlarge</p>
<?php
    $SQL = "SELECT `frequency`,`valid`,A.`updated`,`countyname` " .
      "FROM `county_freqs` A, `arpsc_counties` B " .
      "WHERE A.`county` = B.`countycode` " .
      "ORDER BY A.`county`";
    $res = getResult( $SQL, $db );
    echo "      <table>\n";
    echo "        <tr>\n";
    echo "          <th>County</th>\n";
    echo "          <th>Frequency</th>\n";
    echo "          <th>Updated</th>\n";
    echo "        </tr>\n";
while ( $row = getRow( $res, $db ) )
      {
	echo "        <tr>\n";
	echo "          <td>" . $row[3] . "</td>\n";
	if ( $row[1] == 0 )
	  echo "          <td colspan=2><center>--  N o &nbsp; D a t a  --</center></td>\n";
	else
	  {
	    echo "          <td>" . $row[0] . "</td>\n";
	    echo "          <td>" . $row[2] . "</td>\n";
	  }
	echo "        </tr>\n";
      }
    echo "      </table>\n";
    echo "      </center></p>\n";
    echo "  </div>\n";
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.0 $ - $Date: 2010-04-12 09:49:55-04 $");
?>
</div>
</body>
</html>
