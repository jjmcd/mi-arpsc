<?php
//    ec_his.php
//    $Revision: 1.2 - $Date: 2008-08-10 10:12:48-04 $
//
//    arpsc_ecrept displays the FSD-212 results for a month.  If there
//    are no parameters, the most recent period is displayed, otherwise
//	the requested period is displayed.
//

include('includes/session.inc');
$title=_('Michigan Section FSD-212');

include('includes/functions.inc');

// Remember the launch time
$starttime = strftime("%A, %B %d %Y, %H:%M");

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

$start = 144;
$end = 155;

// $period=92;

    // Get the requested period, if blank choose the latest
    $period = $_GET['period'];
    if ( $period < 1 )
    {
	$SQL="SELECT MAX(`period`) FROM `arpsc_ecrept`";
	$period = singleResult($SQL,$db);;
    }

    $p9 = $start;
    $p1 = $end;

    // Display the month name for this report

    $SQL = 'SELECT lastday FROM `periods` WHERE `periodno`=' . $period;
    $usedate=singleResult($SQL,$db);
    $monthnames = array('J','F','M','A','M','J','J','A','S','O','N','D');

ARESheader($title,"ARPSC Michigan Section FSD-212");

ARESleftBar( $db );

echo '  <div id="main">' . "\n";

echo "    <p><h1><center>FSD-212 Reporting History - 2012</center></h1></p>\n";
echo "    <center>\n";
echo "    <table width=\"60%\">\n";
echo "\t<tr>\n";
echo "		<th class=\"rowS0\">Dist</th>\n";
echo "		<th class=\"rowS0\">County</th>\n";
/*for ( $i=$start; $i<$end+1; $i++ )
    {
	$index = $i % 12;
	echo "\t\t<td class=\"rowS0\" width=\"15px\">" . $monthnames[$index] . "</td>\n";
	}*/
echo "\t\t<th>Reports<br />Rec'd</th>\n";
echo "\t</tr>\n";

// CSS classes used in the table
$s0="class=\"rowS0\"";
$s1="class=\"rowS1\"";
$s1B="class=\"rowS1B\"";
$s1S="class=\"rowS1S\"";
$s1L="class=\"rowS1L\"";
$s2="class=\"rowS2\"";
$s2S="class=\"rowS2S\"";



//==================================================================================================
// D e t a i l   L i n e s
//==================================================================================================

$q1='SELECT `districtkey`, `district_code` from `arpsc_districts` ORDER BY `district_code`';
$r1=getResult($q1,$db);

$olddistrict=0;

$totalhours = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$reports = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

while ( $row1 = getRow($r1,$db) )
{
    $district=$row1[1];
    $q2="SELECT `countyname`, `countycode` FROM `arpsc_counties` WHERE `district`='" .
	$district . "' ORDER BY `countyname`";
    $r2=getResult($q2,$db);
    if ( $district < ":" )
    {
	echo "\t<tr></tr>\n";

	$sqlc='SELECT COUNT(*) FROM `arpsc_counties` WHERE `district`='
	    . $district;
	$lc=singleResult($sqlc,$db);

	/* For each county */
	while ( $row2 = getRow($r2,$db) )
	{
	  $countycount = 0;
	  $q3='SELECT `period`, `drillshrs`,`pseshrs`,`eopshrs`,`aresops` ' .
	    "FROM `arpsc_ecrept` WHERE `county`='" . $row2[1] .
	    "' AND `period` BETWEEN " . $start . ' AND ' . $end;
	  $r3=getResult($q3,$db);
	  
	  $reported = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

	  while ( $row3 = getRow($r3,$db) )
	    {
	      $countycount++;
	      $index = $row3[0] - $start;
	      $reported[$index] = 1;
	      $totalhours[$index]=$totalhours[$index]+
		$row3[1]+$row3[2]+$row3[3]+$row3[4];
	      $reports[$index] = $reports[$index] + 1;
	    }
	  
	  
	  echo "\t<tr>\n";
	  if ( $district != $olddistrict )
	    {
	      echo "\t\t<td " . $s1B . " rowspan=\"" . $lc . "\">" . $district . " </td>\n";
	      $olddistrict = $district;
	    }
	  echo "\t\t<td " . $s1S . "><a href=\"ecrpti.php?county=". $row2[1] . 
	    "\">" . $row2[0] . "</a> </td>\n";

	  echo "<td class=\"rowS0\">" . $countycount . "</td>\n";
	  echo "\t</tr>\n";
	  
	}
	
    }
}

echo "    </table>\n";
echo "    </center>\n";

echo "    <p>Note that \"report received\" does not necessarily indicate that report was received\n";
echo "    in time to be reported to headquarters.  Reports received late or data included in later\n";
echo "    reports is added to the database to make the data as complete as possible.</p>\n";

echo "  </div>\n\n";
sectLeaders($db);
footer($starttime . "Z",$maxdate,
       "\$Revision: 1.2 $ - \$Date: 2008-08-10 16:06:57-04 $");
?>
</div>
</body>
</html>
