<?php
//    ecrpti.php
//    $Revision: 1.4 $ - $Date: 2006-01-16 09:26:36-05 $
//
//    Report FSD 212 data for a single county
//
//use 'GD';

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

//ARESheader($title,"NTS Michigan Section Traffic");

//ARESleftBar( $db );

//echo '  <div id="main">' . "\n";

    $q2="SELECT `countyname` FROM `arpsc_counties` WHERE `countycode`='" .
	$county . "'";
    $countyname = singleResult($q2,$db);
//echo '  <p><h1>FSD-212 Report for ' . $countyname . "</h1></p>\n";

/*?>
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
<?php*/

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
"FROM `arpsc_ecrept` WHERE `county`='" . $county . "' ORDER BY `period`";
$r3=getResult($q3,$db);
$tog = 0;
$i=0;
while ( $row3 = getRow($r3,$db) )
{
    if ( $row3[9] > 0 )
	{
	    $hours = $row3[2]+$row3[4]+$row3[6]+$row3[8];
	    $i = $i + 1;
	    $x[$i] = $row3[9];
	    $y[$i] = $hours;
	    $y1[$i] = $row3[2];
	    $y2[$i] = $row3[4];
	    $y3[$i] = $row3[6];
	    $y4[$i] = $row3[8];

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
	    //echo "\t<tr>\n";
	    $SQL2 = "SELECT lastday FROM periods WHERE periodno = " . $row3[9];
	    $result2 = getResult($SQL2,$db);
	    $myrow2 = getRow($result2,$db);
	    $displaydate=convertDate($myrow2[0]);
	    //echo "\t\t<td " . $s1S . ">" . $displaydate . " </td>\n";
	    //echo "\t\t<td " . $suse . ">" . round($hours) . "</td>\n";
	    //echo "\t\t<td " . $suse . ">$" . round($value) . "</td>\n";
	    //echo "\t\t<td " . $suse . ">" . $row3[0] . " </td>\n";
	    //echo "\t\t<td " . $suse . ">" . $row3[1] . " </td>\n";
	    //echo "\t\t<td " . $suse . ">" . $row3[2] . " </td>\n";
	    //echo "\t\t<td " . $suse . ">" . $row3[3] . " </td>\n";
	    //echo "\t\t<td " . $suse . ">" . $row3[4] . " </td>\n";
	    //echo "\t\t<td " . $suse . ">" . $row3[5] . " </td>\n";
	    //echo "\t\t<td " . $suse . ">" . $row3[6] . " </td>\n";
	    //echo "\t</tr>\n";
	}
}
$n = $i;
$maxy = 0;
$minx = $x[1];
$maxx = $x[$n];
for ( $i=1; $i <= $n; $i++ )
{
  if ( $y[$i] > $maxy )
  {
    $maxy = $y[$i];
  }
}

for ( $i=1; $i<=$n; $i++ )
{
  $yp[$i] = 160 - 150 * $y[$i] / $maxy;
  $yp1[$i] = 160 - 150 * $y1[$i] / $maxy;
  $yp2[$i] = 160 - 150 * $y2[$i] / $maxy;
  $yp3[$i] = 160 - 150 * $y3[$i] / $maxy;
  $yp4[$i] = 160 - 150 * $y4[$i] / $maxy;
  $xp[$i] = 20 + 200 * ( $x[$i]-$minx ) / ( $maxx - $minx );
}

$image = ImageCreate( 270, 170 );
$background_color = ImageColorAllocate($image, 240, 245, 245);
$gray = ImageColorAllocate($image, 240, 255, 255);
$barely = ImageColorAllocate($image, 220, 245, 245 );
ImageFilledRectangle($image, 20, 10, 220, 160, $gray);
$black = ImageColorAllocate( $image, 0, 0, 0);
$dark = ImageColorAllocate( $image, 160, 160, 160);
$line = ImageColorAllocate( $image, 0, 0, 0);
$line1 = ImageColorAllocate( $image, 224, 0, 0);
$line2 = ImageColorAllocate( $image, 0, 192, 0);
$line3 = ImageColorAllocate( $image, 0, 0, 224);
$line4 = ImageColorAllocate( $image, 224, 224, 0);

$ts = 10;
if ( $maxy > 250 )
  $ts = 100;
if ( $maxy > 2500 )
  $ts = 1000;

for ( $y=0; $y<$maxy; $y+=$ts )
  {
    $yt = 160 - 150 * $y / $maxy;
    ImageLine( $image, 15, $yt, 20, $yt, $black );
    ImageLine( $image, 20, $yt, 220, $yt, $barely );
  }
for ( $i=1; $i<=$n; $i++ )
  {
    ImageLine( $image, $xp[$i], 165, $xp[$i], 160, $black );
    ImageLine( $image, $xp[$i], 160, $xp[$i], 10, $barely );
  }
ImageLine( $image, 20, 10, 220, 10, $black);
ImageLine( $image, 220, 10, 220, 160, $black);
ImageLine( $image, 220, 160, 20, 160, $black);
ImageLine( $image, 20, 160, 20, 10, $black);
ImageLine( $image, 0, 0, 269, 0, $dark);
ImageLine( $image, 269, 0, 269, 169, $dark);
ImageLine( $image, 269, 169, 0, 169, $dark);
ImageLine( $image, 0, 170, 0, 0, $dark);


for ( $i=1; $i<$n; $i++ )
{
    ImageLine( $image, $xp[$i], $yp[$i], $xp[$i+1], $yp[$i+1], $line );
    ImageLine( $image, $xp[$i], $yp1[$i], $xp[$i+1], $yp1[$i+1], $line1 );
    ImageLine( $image, $xp[$i], $yp2[$i], $xp[$i+1], $yp2[$i+1], $line2 );
    ImageLine( $image, $xp[$i], $yp3[$i], $xp[$i+1], $yp3[$i+1], $line3 );
    ImageLine( $image, $xp[$i], $yp4[$i], $xp[$i+1], $yp4[$i+1], $line4 );

}
ImageString($image, 3, 225, 40,  'Total',  $line);
ImageString($image, 3, 225, 60,  'Drills', $line1);
ImageString($image, 3, 225, 80,  'PS',     $line2);
ImageString($image, 3, 225, 100, 'Emerg',  $line3);
ImageString($image, 3, 225, 120, 'Admin',  $line4);
ImageString($image, 1, 2, 7, round($maxy), $black);

header('Content-type: image/png');
ImagePNG($image);

// echo "</p>\n";
//echo "</table>\n";
//echo "<P>\n";

//echo "  </div>\n";

//sectLeaders($db);
//footer($starttime,$maxdate,
//       "\$Revision: 1.1 $ - \$Date: 2007-11-07 16:49:01-05 $");
//?>
//</div>
//</body>
//</html>
