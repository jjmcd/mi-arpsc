<?php
//    index.php
//    $Revision: 1.1 $ - $Date: 2007-11-07 21:11:38-05 $
//
//    index is the opening page of the mi-nts website.  It displays the
//    standard menu, and then only some text introducing the site.
//

include('includes/session.inc');
$title=_('Michigan Section FSD-96');

include('includes/functions.inc');

// Remember the launch time
$starttime = strftime("%A, %B %d %Y, %H:%M");

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

//$period=92;

    // Get the requested period, if blank choose the latest
    $period = $_GET['period'];
    if ( $period < 1 )
    {
	$SQL="SELECT MAX(`period`) FROM `arpsc_ecrept`";
	$period = singleResult($SQL,$db);;
    }

    // Display the month name for this report
    $SQL = 'SELECT lastday FROM `periods` WHERE `periodno`=' . $period;
    $usedate=singleResult($SQL,$db);

ARESheader($title,"NTS Michigan Section Traffic");

ARESleftBar( $db );

echo '  <div id="main">' . "\n";
echo '  <p><h3><center>Monthly Section Emergency Coordinator Report<br />to ARRL Headquarters</center></h3></p>' . "\n";
echo "  <hr />\n";
echo '  <p><center>ARRL Section: Michigan &nbsp; &nbsp; &nbsp; &nbsp;  Month: ' . convertDate($usedate) . "</center></p>\n";
echo "  <hr />\n";
echo "  <p><center><b>AMATEUR RADIO EMERGENCY SERVICE</b></center></p>\n";
echo "  <hr />\n";

// CSS classes used in the table
$s0="class=\"rowS0\"";
$s1="class=\"rowS1\"";
$s1B="class=\"rowS1B\"";
$s1S="class=\"rowS1S\"";
$s1L="class=\"rowS1L\"";
$s2="class=\"rowS2\"";
$s2S="class=\"rowS2S\"";

//==================================================================================================
// S u m m a r y   L i n e s
//==================================================================================================

// Initialize maxdate to most recent county update
$q0='SELECT MAX(`updated`) FROM `arpsc_counties`';
$r0=getResult($q0,$db);
$row0=getRow($r0,$db);
$maxdate=$row0[0];

// Loop through districts
$q1='SELECT `districtkey`, `district_code` from `arpsc_districts` ORDER BY `district_code`';
$r1=getResult($q1,$db);

$garesmem=0;
$gareschg=0;
$gnetsess=0;
$gnethrs =0;
$gpsnum  =0;
$gpshrs  =0;
$gemnum  =0;
$gemhrs  =0;
$gmanhrs =0;
$gvalue  =0;
$numecs = 0;
$numnet = 0;

while ( $row1 = getRow($r1,$db) )
{
    $district=$row1[1];
    $manhrs=0;
    $aresmem=0;
    $areschg=0;
    $netsess=0;
    $nethrs=0;
    $psnum = 0;
    $pshrs = 0;
    $emnum = 0;
    $emhrs = 0;
    $adhrs = 0;

    $q2="SELECT `countyname`, `countycode` FROM `arpsc_counties` WHERE `district`='" .
	$row1[0] . "' ORDER BY `countyname`";

    $r2=getResult($q2,$db);
    while ( $row2 = getRow($r2,$db) )
	{
	    $q3='SELECT `aresmem`,`drillsnum`,`drillshrs`,`psesnum`,`pseshrs`,`eopsnum`,`eopshrs`,`aresopsnum`,`aresops`, `updated` ' .
		"FROM `arpsc_ecrept` WHERE `county`='" . $row2[1] . "' AND `period`=" . $period;
	    $r3=getResult($q3,$db);

	    if ( $row3 = getRow($r3,$db) )
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
				    $change = 0;
				}
			}
		    $aresmem=$aresmem+$row3[0];
		    $areschg=$areschg+$change;
		    $netsess=$netsess+$row3[1];
		    $nethrs=$nethrs+$row3[2];
		    $psnum = $psnum+$row3[3];
		    $pshrs = $pshrs+$row3[4];
		    $emnum = $emnum+$row3[5];
		    $emhrs = $emhrs+$row3[6];
		    $adhrs = $adhrs+$row3[8];
		    $numecs = $numecs +1;
		    if ( $netsess > 1 )
			$numnets = $numnets + 1;

		    if ( $row3[9] > $maxdate )
		    {
			$maxdate = $row3[9];
		    }
		}
	    else
		{
		    $q3='SELECT `aresmem` ' .
			"FROM `arpsc_ecrept` WHERE `county`='" . $row2[1] . "' AND `period`=0";
		    $r3=getResult($q3,$db);
		    if ( $row3 = getRow($r3,$db) )
			{
			    $aresmem=$aresmem+$row3[0];
			}
		}
	}
    $manhrs = $nethrs + $pshrs + $emhrs + $adhrs;
    $value = $manhrs * 18.11;

    $garesmem=$garesmem+$aresmem;
    $gareschg=$gareschg+$areschg;
    $gnetsess=$gnetsess+$netsess;
    $gnethrs =$gnethrs +$nethrs ;
    $gpsnum  =$gpsnum  +$psnum  ;
    $gpshrs  =$gpshrs  +$pshrs  ;
    $gemnum  =$gemnum  +$emnum  ;
    $gemhrs  =$gemhrs  +$emhrs  ;
    $gmanhrs =$gmanhrs +$manhrs ;
    $gvalue  =$gvalue  +$value  ;
}


echo "<table width=100% style=\"background-color: white; \">\n";

echo "<tr>\n";
echo "<td class=\"rowS0\">Total number of ARES members</td>\n";
echo "<td class=\"rowS0\">" . $garesmem . "</td>\n";
echo "<td class=\"rowS0\">Change since last month:</td>\n";
echo "<td class=\"rowS0\">" . $gareschg . "</td>\n";

echo "<tr>\n";
echo "<td class=\"rowS0\"># EC's reporting</td>\n";
echo "<td class=\"rowS0\">" . $numecs . "</td>\n";
echo "<td class=\"rowS0\"># ARES nets</td>\n";
echo "<td class=\"rowS0\">" . $numnets . "</td>\n";

echo "<tr>\n";
echo "<td class=\"rowS0\">Drills, tests and training sessions</td>\n";
echo "<td class=\"rowS0\">" . $gnetsess . "</td>\n";
echo "<td class=\"rowS0\">Person hours</td>\n";
echo "<td class=\"rowS0\">" . round($gnethrs) . "</td>\n";

echo "<tr>\n";
echo "<td class=\"rowS0\">Public Service Events</td>\n";
echo "<td class=\"rowS0\">" . $gpsnum . "</td>\n";
echo "<td class=\"rowS0\">Person hours</td>\n";
echo "<td class=\"rowS0\">" . round($gpshrs) . "</td>\n";

echo "<tr>\n";
echo "<td class=\"rowS0\">Emergency Operations</td>\n";
echo "<td class=\"rowS0\">" . $gemnum . "</td>\n";
echo "<td class=\"rowS0\">Person hours</td>\n";
echo "<td class=\"rowS0\">" . round($gemhrs) . "</td>\n";

echo "<tr>\n";
echo "<td class=\"rowS0\">Total number of ARES ops</td>\n";
echo "<td class=\"rowS0\">" . round($gnetsess+$gpsnum+$gemnum) . "</td>\n";
echo "<td class=\"rowS0\">Person hours</td>\n";
echo "<td class=\"rowS0\">" . round($gmanhrs) . "</td>\n";

echo "</table>\n";

    echo "<P>\n";

echo "  </div>\n";
sectLeaders($db);
footer($starttime,$maxdate,
       "\$Revision: 1.1 $ - \$Date: 2007-11-07 21:11:38-05 $");
?>
</div>
</body>
</html>
