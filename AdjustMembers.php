<?php

include('includes/session.inc');
$title=_('Michigan Section FSD-212');

include('includes/functions.inc');

// Remember the launch time
$starttime = strftime("%A, %B %d %Y, %H:%M");

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

ARESheader($title,"Adjust Member Count");

ARESleftBar( $db );

echo '  <div id="main">' . "\n";


$Q1 = "SELECT DISTINCT `COUNTY` FROM `arpsc_ecrept`";
$R1 = getResult($Q1,$db);
while ( $row1 = getRow($R1,$db) )
  {
    $Q2 = "SELECT MAX(`PERIOD`) FROM `arpsc_ecrept` " .
      "WHERE `county`='" . $row1[0] . "'";
    $last = singleResult($Q2,$db);
    $Q3 = "SELECT `ARESMEM` FROM `arpsc_ecrept` " .
      "WHERE `COUNTY`='" . $row1[0] . "' " .
      "AND `PERIOD`=" . $last;
    $latest = singleResult($Q3,$db);
    $Q4 = "SELECT `ARESMEM` FROM `arpsc_ecrept` " .
      "WHERE `COUNTY`='" . $row1[0] . "' " .
      "AND `PERIOD`=0";
    $zeroth = singleResult($Q4,$db);

    echo "<p>" . $row1[0] . ": " . $last . ", ";
    if ( $latest == $zeroth )
      echo $latest . ", " . $zeroth . "</p>\n";
    else
      if ( $zeroth > 0 )
	{
	  echo "<b>" . $latest . ", " . $zeroth . "</b><br/>\n";
	  $Q5 = 'UPDATE `arpsc_ecrept` SET `ARESMEM`=' . $latest .
	    " WHERE `COUNTY`='" . $row1[0] . "' AND `PERIOD`=0";
	  echo "---" . $Q5 . "---</p>\n";
	  $R5 = getResult($Q5,$db);
	}
      else
	{
	  echo "<span style=\"color: red;\">" . $latest . ", " . 
	    $zeroth . "</span><br />\n";
	  $Q5 = "INSERT INTO `arpsc_ecrept` (PERIOD,COUNTY,ARESMEM,UPDATED) " .
	    "VALUES(0,'" . $row1[0] . "'," . $latest . ",NOW());";
	  echo "---" . $Q5 . "---</p>\n";
	  $R5 = getResult($Q5,$db);
	}
  }
echo "  </div>\n";
sectLeaders($db);
footer($starttime,"2010-11-11",
       "\$Revision: 1.1 $ - \$Date: 2010-11-11 10:12:48-04 $");
?>
</div>
</body>
</html>
