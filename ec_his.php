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

// $period=92;

    // Get the requested period, if blank choose the latest
    $period = $_GET['period'];
    if ( $period < 1 )
    {
	$SQL="SELECT MAX(`period`) FROM `arpsc_ecrept`";
	$period = singleResult($SQL,$db);;
    }

    $p9 = $period;
    $p1 = (int)((int)$p9 - 17);

    // Display the month name for this report

    $SQL = 'SELECT lastday FROM `periods` WHERE `periodno`=' . $period;
    $usedate=singleResult($SQL,$db);
    $monthnames = array('J','F','M','A','M','J','J','A','S','O','N','D');

ARESheader($title,"ARPSC Michigan Section FSD-212");

ARESleftBar( $db );

echo '  <div id="main">' . "\n";
//echo "<p>period=" . $period . ", first=" . $p1 . ", last=" . $p9 . "</p>\n";
echo "    <p><h1>FSD-212 Reporting History</h1></p>\n";
echo "    <center>\n";
echo "    <table width=\"100%\">\n";
echo "\t<tr>\n";
echo "		<td class=\"rowS0\">Dist</td>\n";
echo "		<td class=\"rowS0\">County</td>\n";
for ( $i=$p1; $i<$p9+1; $i++ )
    {
	$index = $i % 12;
	echo "\t\t<td class=\"rowS0\" width=\"15px\">" . $monthnames[$index] . "</td>\n";
    }
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
	//echo "\t<tr height=\"2 px\"><td style=\"background-color: paleturquoise;\" colspan=\"20\"></td></tr>\n";
	echo "\t<tr></tr>\n";

	$sqlc='SELECT COUNT(*) FROM `arpsc_counties` WHERE `district`='
	    . $district;
	$lc=singleResult($sqlc,$db);

	// Extra line for staff, if reported ----------------------------
	//    $key1 = "D" . $district;
	//$sqlc1="SELECT COUNT(*) FROM `arpsc_ecrept` WHERE `county`='"
	//    . $key1 . "' AND `period`=" . $period;
	//if ( singleResult($sqlc1,$db)>0 )
	//    $lc = $lc + 1;

	while ( $row2 = getRow($r2,$db) )
	{
	    //echo $district . ',' . $row2[0] . "<br>\n";


	    $q3='SELECT `period`, `drillshrs`,`pseshrs`,`eopshrs`,`aresops` ' .
		"FROM `arpsc_ecrept` WHERE `county`='" . $row2[1] .
		"' AND `period` BETWEEN " . $p1 . ' AND ' . $p9;
	    $r3=getResult($q3,$db);

	    $reported = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

	    while ( $row3 = getRow($r3,$db) )
	    {
		$index = $row3[0] - $p1;
		$reported[$index] = 1;
		$totalhours[$index]=$totalhours[$index]+
		    $row3[1]+$row3[2]+$row3[3]+$row3[4];
		$reports[$index] = $reports[$index] + 1;
	    }

	    if ( $row2[0]=='NotArenac' )
	    {
		if ( $district != $olddistrict )
		{
		    echo "\t\t<td " . $s1B . " rowspan=\"" . $lc . "\">" . $district . " </td>\n";
		    $olddistrict = $district;
		}
		echo "\t\t<td " . $s1S . ">" . $row2[0] . " </td>\n";
		echo "\t\t<td " . $s1L . " colspan=\"18\">" . "w/Ogemaw" . "</td>\n";
		echo "\t</tr>\n";
		//	    }
		//	    else if ( $row2[0]=='Clare' )
		//	    {
		if ( $district != $olddistrict )
		{
		    echo "\t\t<td " . $s1B . " rowspan=\"" . $lc . "\">" . $district . " </td>\n";
		    $olddistrict = $district;
		}
		echo "\t\t<td " . $s1S . ">" . $row2[0] . " </td>\n";
		echo "\t\t<td " . $s1L . " colspan=\"18\">" . "w/Isabella" . "</td>\n";
		echo "\t</tr>\n";
	    }
	    else
	    {
		echo "\t<tr>\n";
		if ( $district != $olddistrict )
		{
		    echo "\t\t<td " . $s1B . " rowspan=\"" . $lc . "\">" . $district . " </td>\n";
		    $olddistrict = $district;
		}
		//echo "\t\t<td " . $s2S . ">" . $row2[0] . " </td>\n";
		echo "\t\t<td " . $s1S . "><a href=\"ecrpti.php?county=". $row2[1] . "\">" . $row2[0] . "</a> </td>\n";
		//echo "\t\t<td " . $s2S . "><a href=\"ecrpti.php?county=". $row2[1] . "\">" . $row2[0] . "</a> </td>\n";
		for ( $i=0; $i<18; $i++ )
		{
		    if ( $reported[$i] == 0 )
			echo "\t\t<td style=\"background-color: deeppink;\">&nbsp;</td>\n";
		    else
			echo "\t\t<td style=\"background-color: palegreen; color: forestgreen; text-align: center\">X</td>\n";
		}
		echo "\t</tr>\n";
	    }

	}

    }
}

echo "\t<tr>\n";
echo "\t\t<td colspan=\"20\" style=\"background-color: white;\">Pink=No Report, Green w/X=Report Received</td>\n";
echo "\t</tr>\n";

echo "\t<tr>\n";
echo "\t\t<th colspan=\"20\">Section Summary</th>\n";
echo "\t</tr>\n";

$max=0;
$min=999999;
$mxr=0;
$mnr=999999;
for ( $index=0;  $index<18; $index++ )
{
    if ( $totalhours[$index] < $min )
	$min = $totalhours[$index];
    if ( $totalhours[$index] > $max )
	$max = $totalhours[$index];
    if ( $reports[$index] < $mnr )
	$mnr = $reports[$index];
    if ( $reports[$index] > $mxr )
	$mxr = $reports[$index];
}
$delta = ($max - $min) / 255;
$deltar = ($mxr - $mnr) / 255;

echo "\t<tr>\n";
echo "\t\t<td colspan=2 style=\"background-color: white;\">K Hours Reported</td>\n";
for ( $index=0; $index<18; $index++ )
{
    $value = (int) (($totalhours[$index]-$min) / $delta + .5);
    $pvalue = sprintf("%02x",255-$value);
    $fcolor = "White";
    if ( $value < 128 ) $fcolor="Black";
    $bcolor = "#" . $pvalue . $pvalue . $pvalue;
    echo "\t\t<td style=\"background-color: " . $bcolor . "; color: " . $fcolor .
	"; font-size: 8pt;\">" . (int)($totalhours[$index]/1000+.5) . "</td>\n";
}
echo "\t</tr>\n";

echo "\t<tr>\n";
echo "\t\t<td colspan=2 style=\"background-color: white;\">Reports Rec'd</td>\n";
for ( $index=0; $index<18; $index++ )
{
    $value = (int) (($reports[$index]-$mnr) / $deltar + .5);
    $pvalue = sprintf("%02x",255-$value);
    $fcolor = "White";
    if ( $value < 128 ) $fcolor="Black";
    if ( $value < 64 ) $fcolor="Red";
    $bcolor = "#" . $pvalue . $pvalue . $pvalue;
    echo "\t\t<td style=\"background-color: " . $bcolor . "; color: " . $fcolor .
	"; font-size: 8pt;\">" . $reports[$index] . "</td>\n";
}
echo "\t</tr>\n";

echo "\t<tr>\n";
echo "\t\t<td colspan=\"20\" style=\"background-color: white;\">Black=most, White=least</td>\n";
echo "\t</tr>\n";

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
