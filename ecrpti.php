<?php
//    ecrpti.php
//    $Revision: 1.1 $ - $Date: 2007-11-14 09:34:23-05 $
//
//    Report FSD 212 data for a single county
//

include('includes/session.inc');
$title=_('Michigan Section FSD-212');

include('includes/functions.inc');

// Remember the launch time
$starttime = strftime("%A, %B %d %Y, %H:%M");

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);


    // Get the requested period, if blank choose the latest
    $county = $_GET['county'];

//    // Display the month name for this report
//    $SQL = 'SELECT lastday FROM `periods` WHERE `periodno`=' . $period;
//    $usedate=singleResult($SQL,$db);

ARESheader($title,"NTS Michigan Section Traffic");

ARESleftBar( $db );

echo '  <div id="main">' . "\n";

    $q2="SELECT `countyname` FROM `arpsc_counties` WHERE `countycode`='" .
	$county . "'";
    $countyname = singleResult($q2,$db);
echo '  <p><h1>FSD-212 Report for ' . $countyname . "</h1></p>\n";

?>
  <table>
	<tr>
		<td class="rowS0" rowspan="2">Period</td>
		<td class="rowS0" rowspan="2">Monthly Man Hours</td>
		<td class="rowS0" rowspan="2">Contrib Dollar Value</td>
		<td class="rowS0" rowspan="2">Total # ARES mbrs</td>
		<td class="rowS0" colspan="2">Net</td>
		<td class="rowS0" colspan="2">Public Service</td>
		<td class="rowS0" colspan="2">Emergency</td>
	</tr>
	<tr>
		<td class="rowS0">Num</td>
		<td class="rowS0">Man Hours</td>
		<td class="rowS0">Num</td>
		<td class="rowS0">Man Hours</td>
		<td class="rowS0">Num</td>
		<td class="rowS0">Man Hours</td>
	</tr>
<?php

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


$q3='SELECT `aresmem`,`drillsnum`,`drillshrs`,`psesnum`,`pseshrs`,`eopsnum`,`eopshrs`,`aresopsnum`,`aresops`,`period` ' .
"FROM `arpsc_ecrept` WHERE `county`='" . $county . "' ORDER BY `period` DESC";
$r3=getResult($q3,$db);
$tog = 0;
while ( $row3 = getRow($r3,$db) )
{
    if ( $row3[9] > 0 )
	{
	    $hours = $row3[2]+$row3[4]+$row3[6]+$row3[8];
	    $value = $hours * 18.11;
	    $lastperiod = $period-1;
	    $q4="SELECT `aresmem` FROM `arpsc_ecrept` WHERE `county`='" . $row2[1] . 
		"' AND `period`=" . $lastperiod;
	    $r4=getResult($q4,$db);
	    if ( $row4 = getRow($r4,$db) )
		{
		    $change = $row3[0]-$row4[0];
		}
	    else
		{
		    $q4="SELECT `aresmem` FROM `arpsc_ecrept` WHERE `county`='" . $row2[1] . 
			"' AND `period`=0";
		    $r4=getResult($q4,$db);
		    if ( $row4 = getRow($r4,$db) )
			{
			    $change = $row3[0]-$row4[0];
			}
		    else
			{
			    $change = " ";
			}
		}
	    if ( $tog == 0 )
		{
		    $suse = $s1;
		    $tog = 1;
		}
	    else
		{
		    $suse = $s0;
		    $tog = 0;
		}
	    echo "\t<tr>\n";
	    $SQL2 = "SELECT lastday FROM periods WHERE periodno = " . $row3[9];
	    $result2 = getResult($SQL2,$db);
	    $myrow2 = getRow($result2,$db);
	    $displaydate=convertDate($myrow2[0]);
	    echo "\t\t<td " . $s1S . ">" . $displaydate . " </td>\n";
	    echo "\t\t<td " . $suse . ">" . round($hours) . "</td>\n";
	    echo "\t\t<td " . $suse . ">$" . round($value) . "</td>\n";
	    echo "\t\t<td " . $suse . ">" . $row3[0] . " </td>\n";
	    echo "\t\t<td " . $suse . ">" . $row3[1] . " </td>\n";
	    echo "\t\t<td " . $suse . ">" . $row3[2] . " </td>\n";
	    echo "\t\t<td " . $suse . ">" . $row3[3] . " </td>\n";
	    echo "\t\t<td " . $suse . ">" . $row3[4] . " </td>\n";
	    echo "\t\t<td " . $suse . ">" . $row3[5] . " </td>\n";
	    echo "\t\t<td " . $suse . ">" . $row3[6] . " </td>\n";
	    echo "\t</tr>\n";
	}
}

// echo "</p>\n";
echo "</table>\n";
echo "<p></p>\n";
echo "<center>\n";
echo "<img src=\"ecrptigl.php?county=" . $county . "\" width=\"380\" height=\"220\" alt=\"Historical effort chart\" />\n";
echo "<p></p>\n";
echo "<img src=\"ecrptpc.php?county=" . $county . "\" width=\"380\" height=\"220 \" alt=\"Effort by category\" />\n";
echo "</center>\n";
echo "  </div>\n";

sectLeaders($db);
footer($starttime,$maxdate,
       "\$Revision: 1.1 $ - \$Date: 2007-11-14 09:34:23-05 $");
?>
</div>
</body>
</html>
