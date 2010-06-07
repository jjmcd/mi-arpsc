<?php
//    hamgateFreqMap.php
//    $Revision: 1.0 $ - $Date: 2010-05-09 14:31:55-04 $
//
//    Display the hamgate frequency map image on a standard page
//
    include('includes/session.inc');
    $title=_('Michigan Hamgate Frequencies');

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
    <h1><center>Hamgate Frequencies</center></h1>
      <p><center>
        <a target="freqMap" href="hamgatefreqs.php">
          <img src="hamgatefreqs.php" width="460" height="519" />
        </a>
        <p>Click on image to enlarge</p>
<?php
    $SQL = "SELECT `frequency`,`valid`,A.`updated`,`countyname` " .
      "FROM `hamgate_freqs` A, `arpsc_counties` B " .
      "WHERE A.`county` = B.`countycode` AND `valid`=1 " .
      "ORDER BY A.`county`";
    $res = getResult( $SQL, $db );
    echo "      <table>\n";
    echo "        <tr>\n";
    echo "          <th>County</th>\n";
    echo "          <th>Frequency</th>\n";
    echo "        </tr>\n";
    while ( $row = getRow( $res ) )
      {
	echo "        <tr>\n";
	echo "          <td align=\"right\">" . $row[3] . "&nbsp;</td>\n";
	echo "          <td>&nbsp;" . $row[0] . "</td>\n";
	echo "        </tr>\n";
      }
    echo "      </table>\n";
    echo "      <p>Note that node names are typically hamgate.(county)." .
         "ampr.org</b>\n";
    echo "      <p>Refer to <a href=\"http://hamgate.midland.mi-drg.org" .
         "/ares/mihosts.txt\" target=\"hostlist\">http://hamgate.midlan" .
         "d.mi-drg.org/ares/mihosts.txt</a> for a complete list of regi" .
         "stered Michigan packet nodes.</p>\n";
    echo "      </center></p>\n";
    echo "  </div>\n";
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.0 $ - $Date: 2010-05-09 14:31:43-04 $");
?>
</div>
</body>
</html>
