<?php
//    ecrptpc.php
//    $Revision: 1.3 $ - $Date: 2007-11-30 12:02:36-05 $
//
//    Pie chart of effort by type for one county
//
//use 'GD';

include('includes/session.inc');

include('includes/functions.inc');

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

// Get the requested county
$county = $_GET['county'];

//===========================================================================
// D a t a b a s e   D a t a
//===========================================================================

// Initialize the database query
$q3='SELECT `drillshrs`,`pseshrs`,`eopshrs`,`aresops`,`period` ' .
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
    if ( $row3[4] > 0 )
	{
	    $hours = $row3[0]+$row3[1]+$row3[2]+$row3[3];
	    $i = $i + 1;
	    $x[$i] = $row3[4];
	    $y[$i] = $hours;
	    $t0 = $t0 + $hours;
	    $y1[$i] = $row3[0];
	    $t1 = $t1 + $row3[0];
	    $y2[$i] = $row3[1];
	    $t2 = $t2 + $row3[1];
	    $y3[$i] = $row3[2];
	    $t3 = $t3 + $row3[2];
	    $y4[$i] = $row3[3];
	    $t4 = $t4 + $row3[3];

	    $value = $hours * 18.11;
	    $lastperiod = $period-1;

	}
}

//===========================================================================
// G e n e r a t e   G r a p h
//===========================================================================

$n = $i;         // Number of points

// Allocate the image to display
$image = ImageCreate( 380, 220 );
// Set up a number of colors to be used
$background_color = ImageColorAllocate($image, 240, 245, 245);
$gray = ImageColorAllocate($image, 240, 255, 255);
$barely = ImageColorAllocate($image, 220, 245, 245 );
$black = ImageColorAllocate( $image, 0, 0, 0);
$dark = ImageColorAllocate( $image, 160, 160, 160);
$ltgray = ImageColorAllocate( $image, 220, 220, 220);
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

$ycenter = 105;

// --- Latest period pie

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
// Draw the drills slice
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
// Draw the public service slice
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
// Draw the emergency slice
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
// Draw the admin slice
ImageFilledPolygon($image, $points2, ($the4-$ths4+2), $line4);
ImagePolygon($image, $points2, ($the4-$ths4+2), $black);

// --- All periods pie

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
ImageString($image, 3, 60, 192, 'All Periods', $black );
ImageString($image, 3, 230, 192, 'Latest Period', $black );
ImageString($image, 1, 220, 210, 'ecrptpc.php $Revision: 1.3 $', $ltgray );

// Outline the image
ImageLine( $image, 0, 0, 379, 0, $dark);
ImageLine( $image, 379, 0, 379, 219, $dark);
ImageLine( $image, 379, 219, 0, 219, $dark);
ImageLine( $image, 0, 219, 0, 0, $dark);

// Expose the graph
header('Content-type: image/png');
ImagePNG($image);

?>
