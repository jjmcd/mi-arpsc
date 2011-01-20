<?php
//    index.php
//    $Revision: 1.4 $ - $Date: 2006-01-16 09:26:36-05 $
//
//    index is the opening page of the mi-nts website.  It displays the
//    standard menu, and then only some text introducing the site.
//

include('includes/session.inc');
$title=_('FDS-212');

include('includes/functions.inc');

// Remember the launch time
$starttime = strftime("%A, %B %d %Y, %H:%M");

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

$period=92;

ARESheader($title,"NTS Michigan Section Traffic");

ARESleftBar( $db );
?>
  <div id="main">
  <p><h1>ARES Report for September, 2007</h1></p>
  <table>
  <tr>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff" rowspan="2">District</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff" rowspan="2">Juris</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff" rowspan="2">Monthly Man Hours</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff" rowspan="2">Contrib Dollar Value</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff" rowspan="2">Total # ARES mbrs</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff" rowspan="2">Chg</td>

    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff" colspan="2">Net</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff" colspan="2">Public Service</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff" colspan="2">Emergency</td>
  </tr>
  <tr>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff">Num</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff">Man Hours</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff">Num</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff">Man Hours</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff">Num</td>
    <td style="height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff">Man Hours</td>
  </tr>
<?php

$s0="style=\"height: 12.0pt; width: 74pt; font-family: Arial, sans-serif; font-size: 9.0pt; background: #fff\"";
$s1="style=\"height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #dff\"";
$s1B="style=\"height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 10.0pt; font-weight: bold; background: #dff; border-top: solid black 1px;\"";
$s1S="style=\"height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 8.0pt; background: #dff\"";
$s1L="style=\"height: 12.0pt; text-align: left; font-family: Arial, sans-serif; font-size: 9.0pt; background: #dff\"";
$s2="style=\"height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 9.0pt; background: #8f8\"";
$s2S="style=\"height: 12.0pt; text-align: center; font-family: Arial, sans-serif; font-size: 8.0pt; background: #8f8\"";

//==================================================================================================
// S u m m a r y   L i n e s
//==================================================================================================

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

    $q2="SELECT `countyname`, `countycode` FROM `arpsc_counties` WHERE `district`='" .
	$row1[0] . "' ORDER BY `countyname`";

    $r2=getResult($q2,$db);
    //echo "<tr></tr>\n";
    while ( $row2 = getRow($r2,$db) )
	{
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
    $manhrs = $nethrs + $pshrs + $emhrs;
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

//$q1='SELECT countycode, countyname FROM arpsc_counties ORDER BY countyname';
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
			    echo "\t\t<td " . $s1S . ">" . $row2[0] . " </td>\n";
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
			    if ( $row2[0]=='Arenac' )
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
			    else
				{
				    echo "\t<tr>\n";
			    if ( $district != $olddistrict )
			    {
				echo "\t\t<td " . $s1B . " rowspan=\"" . $lc . "\">" . $district . " </td>\n";
				$olddistrict = $district;
			    }
				    echo "\t\t<td " . $s2S . ">" . $row2[0] . " </td>\n";
				    echo "\t\t<td colspan=\"10\" " . $s2 . ">" . "- - - - N o &nbsp;  R e p o r t - - - -" . "</td>\n";
				    echo "\t</tr>\n";
				}
			}
		}
	}
    //echo "<br>\n";
}

// echo "</p>\n";
echo "</table>\n";
echo "  </div>\n";
sectLeaders($db);
footer($starttime,$maxdate,
       "\$Revision: 1.0 $ - \$Date: 2007-10-02 11:40:01-04 $");
?>
</div>
</body>
</html>
