<?php
//    arpsc_freqedit1.php
//    $Revision: 1.0 $ - $Date: 2010-04-12 09:49:55-04 $
//
//    Get the frequency fo a county
//
    include('includes/session.inc');
    $title=_('Volunteer Data Results');

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
    <h1><center>Result</center></h1>
      <center><table width="95%">
<?php
    $SQL1 = "SELECT DISTINCT `id` FROM `Capabilities` ORDER BY `sequence`";
    $r1 = getResult($SQL1,$db);
    while ( $row1=getRow($r1,$db) )
      {
	if ( isset($_POST[$row1[0]]) )
	  {
	    $SQL2 = "SELECT `short_name`,`description` FROM `Capabilities` " .
	      "WHERE `id`=" . $_POST[$row1[0]];
	    $r2 = getResult($SQL2,$db);
	    $row2 = getRow($r2,$db);
	    echo "<tr><td>" . $row2[0] . "</td><td>" . 
	      $row2[1] . "</td></tr>\n";
	  }
      }

    echo "  </table></center>\n";
    echo "</div>\n";
    sectLeaders($db);
    footer($starttime,$maxdate,
      "\$Revision: 1.0 $ - $Date: 2010-04-12 09:49:55-04 $");
?>
</ul>
</div>
</body>
</html>
