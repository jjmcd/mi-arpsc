<?php
//    ecrptpc.php
//    $Revision: 1.1 $ - $Date: 2007-11-14 09:34:46-05 $
//
//    Graph FSD-212 data for a county by type of effort
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
$t0 = 0;
$t1 = 0;
$t2 = 0;
$t3 = 0;
$t4 = 0;
while ( $row3 = getRow($r3,$db) )
{
    if ( $row3[9] > 0 )
	{
	    $hours = $row3[2]+$row3[4]+$row3[6]+$row3[8];
	    $i = $i + 1;
	    $x[$i] = $row3[9];
	    $y[$i] = $hours;
	    $t0 = $t0 + $hours;
	    $y1[$i] = $row3[2];
	    $t1 = $t1 + $row3[2];
	    $y2[$i] = $row3[4];
	    $t2 = $t2 + $row3[4];
	    $y3[$i] = $row3[6];
	    $t3 = $t3 + $row3[6];
	    $y4[$i] = $row3[8];
	    $t4 = $t4 + $row3[8];

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

$image = ImageCreate( 380, 220 );
$background_color = ImageColorAllocate($image, 240, 245, 245);
$gray = ImageColorAllocate($image, 240, 255, 255);
$barely = ImageColorAllocate($image, 220, 245, 245 );

$black = ImageColorAllocate( $image, 0, 0, 0);
$dark = ImageColorAllocate( $image, 160, 160, 160);
$line = ImageColorAllocate( $image, 0, 0, 0);
$line1 = ImageColorAllocate( $image, 224, 0, 0);
$line2 = ImageColorAllocate( $image, 0, 192, 0);
$line3 = ImageColorAllocate( $image, 0, 0, 224);
$line4 = ImageColorAllocate( $image, 224, 224, 0);

// Calculate number of 0.01 radian units in each slice
$th1 = 628*$y1[$n]/$y[$n];
$th2 = 628*$y2[$n]/$y[$n];
$th3 = 628*$y3[$n]/$y[$n];
$th4 = 628*$y4[$n]/$y[$n];

// Calculate starting and ending angles in each slice
$ths1 = 0;
$the1 = round($ths1 + $th1);
$ths2 = $the1;
$the2 = round($ths2 + $th2);
$ths3 = $the2;
$the3 = round($ths3 + $th3);
$ths4 = $the3;
$the4 = round($ths4 + $th4);

$ycenter = 110;

// Calculate the slice for drills
$points2[0] = 280;
$points2[1] = $ycenter;
for ( $i=0; $i<=$the1-$ths1; $i+=1 )
  {
    $theta = ($i+$ths1)/100;
    $points2[2*$i+2] = round(280 + 80 * sin($theta));
    $points2[2*$i+3] = round($ycenter + 80 * cos($theta));
  }
$points2[2*$i+2] = 280;
$points2[2*$i+3] = $ycenter;
ImageFilledPolygon($image, $points2, ($the1-$ths1+2), $line1);
ImagePolygon($image, $points2, ($the1-$ths1+2), $black);

// Calculate the slice for Public Service
$points2[0] = 280;
$points2[1] = $ycenter;
for ( $i=0; $i<=$the2-$ths2; $i+=1 )
  {
    $theta = ($i+$ths2)/100;
    $points2[2*$i+2] = round(280 + 80 * sin($theta));
    $points2[2*$i+3] = round($ycenter + 80 * cos($theta));
  }
$points2[2*$i+2] = 280;
$points2[2*$i+3] = $ycenter;
ImageFilledPolygon($image, $points2, ($the2-$ths2+2), $line2);
ImagePolygon($image, $points2, ($the2-$ths2+2), $black);

// Calculate the slice for Emergency
$points2[0] = 280;
$points2[1] = $ycenter;
for ( $i=0; $i<=$the3-$ths3; $i+=1 )
  {
    $theta = ($i+$ths3)/100;
    $points2[2*$i+2] = round(280 + 80 * sin($theta));
    $points2[2*$i+3] = round($ycenter + 80 * cos($theta));
  }
$points2[2*$i+2] = 280;
$points2[2*$i+3] = $ycenter;
ImageFilledPolygon($image, $points2, ($the3-$ths3+2), $line3);
ImagePolygon($image, $points2, ($the3-$ths3+2), $black);

// Calculate the slice for Admin
$points2[0] = 280;
$points2[1] = $ycenter;
for ( $i=0; $i<=$the4-$ths4; $i+=1 )
  {
    $theta = ($i+$ths4)/100;
    $points2[2*$i+2] = round(280 + 80 * sin($theta));
    $points2[2*$i+3] = round($ycenter + 80 * cos($theta));
  }
$points2[2*$i+2] = 280;
$points2[2*$i+3] = $ycenter;
ImageFilledPolygon($image, $points2, ($the4-$ths4+2), $line4);
ImagePolygon($image, $points2, ($the4-$ths4+2), $black);

// Calculate number of 0.01 radian units in each slice
$th1 = 628*$t1/$t0;
$th2 = 628*$t2/$t0;
$th3 = 628*$t3/$t0;
$th4 = 628*$t4/$t0;

// Calculate starting and ending angles in each slice
$ths1 = 0;
$the1 = round($ths1 + $th1);
$ths2 = $the1;
$the2 = round($ths2 + $th2);
$ths3 = $the2;
$the3 = round($ths3 + $th3);
$ths4 = $the3;
$the4 = round($ths4 + $th4);

// Calculate the slice for drills
$points2[0] = 100;
$points2[1] = $ycenter;
for ( $i=0; $i<=$the1-$ths1; $i+=1 )
  {
    $theta = ($i+$ths1)/100;
    $points2[2*$i+2] = round(100 + 80 * sin($theta));
    $points2[2*$i+3] = round($ycenter + 80 * cos($theta));
  }
$points2[2*$i+2] = 100;
$points2[2*$i+3] = $ycenter;
ImageFilledPolygon($image, $points2, ($the1-$ths1+2), $line1);
ImagePolygon($image, $points2, ($the1-$ths1+2), $black);

// Calculate the slice for Public Service
$points2[0] = 100;
$points2[1] = $ycenter;
for ( $i=0; $i<=$the2-$ths2; $i+=1 )
  {
    $theta = ($i+$ths2)/100;
    $points2[2*$i+2] = round(100 + 80 * sin($theta));
    $points2[2*$i+3] = round($ycenter + 80 * cos($theta));
  }
$points2[2*$i+2] = 100;
$points2[2*$i+3] = $ycenter;
ImageFilledPolygon($image, $points2, ($the2-$ths2+2), $line2);
ImagePolygon($image, $points2, ($the2-$ths2+2), $black);

// Calculate the slice for Emergency
$points2[0] = 100;
$points2[1] = $ycenter;
for ( $i=0; $i<=$the3-$ths3; $i+=1 )
  {
    $theta = ($i+$ths3)/100;
    $points2[2*$i+2] = round(100 + 80 * sin($theta));
    $points2[2*$i+3] = round($ycenter + 80 * cos($theta));
  }
$points2[2*$i+2] = 100;
$points2[2*$i+3] = $ycenter;
ImageFilledPolygon($image, $points2, ($the3-$ths3+2), $line3);
ImagePolygon($image, $points2, ($the3-$ths3+2), $black);

// Calculate the slice for Admin
$points2[0] = 100;
$points2[1] = $ycenter;
for ( $i=0; $i<=$the4-$ths4; $i+=1 )
  {
    $theta = ($i+$ths4)/100;
    $points2[2*$i+2] = round(100 + 80 * sin($theta));
    $points2[2*$i+3] = round($ycenter + 80 * cos($theta));
  }
$points2[2*$i+2] = 100;
$points2[2*$i+3] = $ycenter;
ImageFilledPolygon($image, $points2, ($the4-$ths4+2), $line4);
ImagePolygon($image, $points2, ($the4-$ths4+2), $black);

// Label the graph
ImageString($image, 5, 110, 3, 'Effort by Category', $black );
ImageString($image, 3, 60, 200, 'All Periods', $black );
ImageString($image, 3, 230, 200, 'Latest Period', $black );

// Outline the image
ImageLine( $image, 0, 0, 379, 0, $dark);
ImageLine( $image, 379, 0, 379, 219, $dark);
ImageLine( $image, 379, 219, 0, 219, $dark);
ImageLine( $image, 0, 219, 0, 0, $dark);

// Expose the graph
header('Content-type: image/png');
ImagePNG($image);

// echo "</p>\n";
//echo "</table>\n";
//echo "<P>\n";

//echo "  </div>\n";

//sectLeaders($db);
//footer($starttime,$maxdate,
//       "\$Revision: 1.1 $ - \$Date: 2007-11-14 09:34:46-05 $");
//?>
//</div>
//</body>
//</html>
