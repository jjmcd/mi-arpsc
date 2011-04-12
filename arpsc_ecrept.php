<?php
//    arpsc_ecrept.php
//    $Revision: 1.3 $ - \$Date: 2010-12-13 08:53:48-04 $
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

    // Display the month name for this report
    $SQL = 'SELECT lastday FROM `periods` WHERE `periodno`=' . $period;
    $usedate=singleResult($SQL,$db);

ARESheader($title,"ARPSC Michigan Section FSD-212");

ARESleftBar( $db );

echo '  <div id="main">' . "\n";
echo '  <p><h1>ARES Report for ' . convertDate($usedate) . "</h1></p>\n";
?>
  <table>
	<tr>
		<td class="rowS0" rowspan="2">District</td>
		<td class="rowS0" rowspan="2">Juris</td>
		<td class="rowS0" rowspan="2">Monthly Man Hours</td>
		<td class="rowS0" rowspan="2">Contrib Dollar Value</td>
		<td class="rowS0" rowspan="2">Total # ARES mbrs</td>
		<td class="rowS0" rowspan="2">Chg</td>
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

    $key1 = "D" . $district;
    $q5='SELECT `aresmem`,`drillsnum`,`drillshrs`,`psesnum`,`pseshrs`,`eopsnum`,`eopshrs`,`aresopsnum`,`aresops`, `updated` ' .
		"FROM `arpsc_ecrept` WHERE `county`='" . $key1 . "' AND `period`=" . $period;
    $r5=getResult($q5,$db);

    if ( $row5 = getRow($r5,$db) )
	{
		    $hours = $row5[2]+$row5[4]+$row5[6]+$row5[8];
		    $value = $hours * 18.11;
		    $aresmem=$row5[0];
		    $netsess=$row5[1];
		    $nethrs= $row5[2];
		    $psnum = $row5[3];
		    $pshrs = $row5[4];
		    $emnum = $row5[5];
		    $emhrs = $row5[6];
		    $adhrs = $row5[8];
	}


    $q2="SELECT `countyname`, `countycode` FROM `arpsc_counties` WHERE `district`='" .
	$row1[0] . "' ORDER BY `countyname`";

    $r2=getResult($q2,$db);
    //echo "<tr></tr>\n";
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
    echo "\t<tr>\n";
    echo "\t\t<td " . $s1 . ">" . $district . " </td>\n";
    echo "\t\t<td " . $s1 . ">" . " " . " </td>\n";
    echo "\t\t<td " . $s1 . ">" . round($manhrs) . "</td>\n";
    echo "\t\t<td " . $s1 . ">$" . round($value) . "</td>\n";
    echo "\t\t<td " . $s1 . ">" . $aresmem . " </td>\n";
    echo "\t\t<td " . $s1 . ">" . $areschg . "</td>\n";
    echo "\t\t<td " . $s1 . ">" . $netsess . " </td>\n";
    echo "\t\t<td " . $s1 . ">" . round($nethrs) . " </td>\n";
    echo "\t\t<td " . $s1 . ">" . $psnum . " </td>\n";
    echo "\t\t<td " . $s1 . ">" . round($pshrs) . " </td>\n";
    echo "\t\t<td " . $s1 . ">" . $emnum . " </td>\n";
    echo "\t\t<td " . $s1 . ">" . round($emhrs) . " </td>\n";
    echo "\t</tr>\n";
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

echo "\t<tr>\n";
echo "\t\t<td " . $s1 . ">" . "Total all districts" . " </td>\n";
echo "\t\t<td " . $s1 . ">" . " " . " </td>\n";
echo "\t\t<td " . $s1 . ">" . round($gmanhrs) . "</td>\n";
echo "\t\t<td " . $s1 . ">$" . round($gvalue) . "</td>\n";
echo "\t\t<td " . $s1 . ">" . $garesmem . " </td>\n";
echo "\t\t<td " . $s1 . ">" . $gareschg . "</td>\n";
echo "\t\t<td " . $s1 . ">" . $gnetsess . " </td>\n";
echo "\t\t<td " . $s1 . ">" . round($gnethrs) . " </td>\n";
echo "\t\t<td " . $s1 . ">" . $gpsnum . " </td>\n";
echo "\t\t<td " . $s1 . ">" . round($gpshrs) . " </td>\n";
echo "\t\t<td " . $s1 . ">" . $gemnum . " </td>\n";
echo "\t\t<td " . $s1 . ">" . round($gemhrs) . " </td>\n";
echo "\t</tr>\n";

echo "<tr><td colspan=12 " . $s1 . ">&nbsp;</td></tr>\n";


//==================================================================================================
// D e t a i l   L i n e s
//==================================================================================================

$q1='SELECT `districtkey`, `district_code` from `arpsc_districts` ORDER BY `district_code`';
$r1=getResult($q1,$db);

$olddistrict=0;


while ( $row1 = getRow($r1,$db) )
{
    $district=$row1[1];
    $q2="SELECT `countyname`, `countycode` FROM `arpsc_counties` WHERE `district`='" .
	$district . "' ORDER BY `countyname`";
    $r2=getResult($q2,$db);
    if ( $district < ":" )
	{
	    echo "<tr></tr>\n";

	    $sqlc='SELECT COUNT(*) FROM `arpsc_counties` WHERE `district`='
		. $district;
	    $lc=singleResult($sqlc,$db);

	    // Extra line for staff, if reported ----------------------------
	    $key1 = "D" . $district;
	    $sqlc1="SELECT COUNT(*) FROM `arpsc_ecrept` WHERE `county`='"
		. $key1 . "' AND `period`=" . $period;
	    if ( singleResult($sqlc1,$db)>0 )
		$lc = $lc + 1;

	    while ( $row2 = getRow($r2,$db) )
		{
		    //echo $district . ',' . $row2[0] . "<br>\n";


		    $q3='SELECT `aresmem`,`drillsnum`,`drillshrs`,`psesnum`,`pseshrs`,`eopsnum`,`eopshrs`,`aresopsnum`,`aresops` ' .
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
					    $change = " ";
					}
				}
			    echo "\t<tr>\n";
			    if ( $district != $olddistrict )
			    {
				echo "\t\t<td " . $s1B . " rowspan=\"" . $lc . "\">" . $district . " </td>\n";
				$olddistrict = $district;
			    }
			    echo "\t\t<td " . $s1S . "><a href=\"ecrpti.php?county=". $row2[1] . "\">" . $row2[0] . "</a> </td>\n";
			    echo "\t\t<td " . $s1 . ">" . round($hours) . "</td>\n";
			    echo "\t\t<td " . $s1 . ">$" . round($value) . "</td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row3[0] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . $change . "</td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row3[1] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row3[2] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row3[3] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row3[4] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row3[5] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row3[6] . " </td>\n";
			    echo "\t</tr>\n";
			}
		    else
			{
/*===>			  if ( $row2[0]=='Arenac' )
			    {
			      if ( $district != $olddistrict )
				{
				  echo "\t\t<td " . $s1B . " rowspan=\"" . $lc . "\">" . $district . " </td>\n";
				  $olddistrict = $district;
				}
			      echo "\t\t<td " . $s1S . ">" . $row2[0] . " </td>\n";
			      echo "\t\t<td " . $s1L . " colspan=\"10\">" . "w/Ogemaw" . "</td>\n";
			      echo "\t</tr>\n";
			    }
=====*/
			  /*
			  else if ( $row2[0]=='Clare' )
			    {
			      if ( $district != $olddistrict )
				{
				  echo "\t\t<td " . $s1B . " rowspan=\"" . $lc . "\">" . $district . " </td>\n";
				  $olddistrict = $district;
				}
			      echo "\t\t<td " . $s1S . ">" . $row2[0] . " </td>\n";
			      echo "\t\t<td " . $s1L . " colspan=\"10\">" . "w/Isabella" . "</td>\n";
			      echo "\t</tr>\n";
			    }
			  */
			  /*====else====*/
			    {
			      echo "\t<tr>\n";
			      if ( $district != $olddistrict )
				{
				  echo "\t\t<td " . $s1B . " rowspan=\"" . $lc . "\">" . $district . " </td>\n";
				  $olddistrict = $district;
				}
			      //echo "\t\t<td " . $s2S . ">" . $row2[0] . " </td>\n";
			      echo "\t\t<td " . $s2S . "><a href=\"ecrpti.php?county=". $row2[1] . "\">" . $row2[0] . "</a> </td>\n";
			      echo "\t\t<td colspan=\"10\" " . $s2 . ">" . "- - - - N o &nbsp;  R e p o r t - - - -" . "</td>\n";
			      echo "\t</tr>\n";
			    }
			}
		}
	    
	    // Get staff results, if provided  ---------------------------------
	    $q5 ='SELECT `aresmem`,`drillsnum`,`drillshrs`,`psesnum`,`pseshrs`,`eopsnum`,`eopshrs`,`aresopsnum`,`aresops` ' .
			"FROM `arpsc_ecrept` WHERE `county`='" . $key1 . "' AND `period`=" . $period;
	    $r5=getResult($q5,$db);
	    if ( $row5 = getRow($r5,$db) )
		{
			    $hours = $row5[2]+$row5[4]+$row5[6]+$row5[8];
			    $value = $hours * 18.11;
			    echo "\t\t<td " . $s1 . ">Staff</td>\n";
			    echo "\t\t<td " . $s1 . ">" . round($hours) . "</td>\n";
			    echo "\t\t<td " . $s1 . ">$" . round($value) . "</td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row5[0] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . "&nbsp;" . "</td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row5[1] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row5[2] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row5[3] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row5[4] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row5[5] . " </td>\n";
			    echo "\t\t<td " . $s1 . ">" . $row5[6] . " </td>\n";
			    echo "\t</tr>\n";
		}

	}
    //echo "<br>\n";
}

// echo "</p>\n";
echo "</table>\n";
    echo "<P>\n";
    echo "<center>\n";
    dateLinks($period,"arpsc_ecrept",$db);
    echo "</center>\n";

echo "  </div>\n";
sectLeaders($db);
footer($starttime,$maxdate,
       "\$Revision: 1.3 $ - \$Date: 2010-12-13 08:53:48-04 $");
?>
</div>
</body>
</html>
