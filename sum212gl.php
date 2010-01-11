<?php
//    sum212gl.php
//    $Revision: 1.3 $ - $Date: 2007-11-30 12:33:07-05 $
//
//    Generate a graph of 12 month FSD-212 results
//

include('includes/session.inc');

include('includes/functions.inc');

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

// Get the requested period
$period = $_GET['period'];
// Range for data
$p9 = $period;
$p1 = $p9 - 12;

//===========================================================================
// D a t a b a s e   D a t a
//===========================================================================

// Initialize the database query
$q3="SELECT `drillshrs`,`pseshrs`,`eopshrs`,`aresops`,`period` " .
  "FROM `arpsc_ecrept` WHERE `period` BETWEEN " . $p1 . " AND " . $p9 .
  " ORDER BY `period`";
$r3=getResult($q3,$db);
$y1 = array(0,0,0,0,0,0,0,0,0,0,0,0);
$y2 = array(0,0,0,0,0,0,0,0,0,0,0,0);
$y3 = array(0,0,0,0,0,0,0,0,0,0,0,0);
$y4 = array(0,0,0,0,0,0,0,0,0,0,0,0);

$i=0;  // Index into arrays to store results to graph
// Loop through all returned rows
while ( $row3 = getRow($r3,$db) )
  {
    if ( $row3[4] > 0 )
      {
	$hours = $row3[0]+$row3[1]+$row3[2]+$row3[3];
	$i = $row3[4]-$p1;
	$x[$i] = $row3[4];  // Period
	$y1[$i] = $y1[$i]+$hours;   // Total hours
	$y2[$i] = $y2[$i]+$row3[1]; // Public Service
	$y3[$i] = $y3[$i]+$row3[2]; // Emergency
      }
  }

//===========================================================================
// G e n e r a t e   G r a p h
//===========================================================================

// Graph left, top, right, bottom positions
$gl = 35;
$gt = 5;
$gt = 25;
$gr = 330;
$gr = 385;
$gb = 195;
$gb = 180;

$n = $i;        // Number of points
$maxy = 0;      // Initialize max Y value
$minx = $x[1];  // Minimum X
$maxx = $x[$n]; // Maximum X
// Find the maximum Y value
for ( $i=1; $i <= $n; $i++ )
  if ( $y1[$i] > $maxy )
    $maxy = $y1[$i];

// Calculate the position on the graph for each data point
for ( $i=1; $i<=$n; $i++ )
{
  //$yp[$i] = $gb - ($gb-$gt) * $y[$i] / $maxy;
  $yp1[$i] = $gb - ($gb-$gt) * $y1[$i] / $maxy;
  $yp2[$i] = $gb - ($gb-$gt) * $y2[$i] / $maxy;
  $yp3[$i] = $gb - ($gb-$gt) * $y3[$i] / $maxy;
  $yp4[$i] = $gb - ($gb-$gt) * $y4[$i] / $maxy;
  $xp[$i] = $gl + ($gr-$gl) * ( $x[$i]-$minx ) / ( $maxx - $minx );
}

// Allocate the image to display
$image = ImageCreate( 450, 220 );
// Fill the canvas with a barely bluish gray
$background_color = ImageColorAllocate($image, 250, 255, 255);
// Set up a number of colors to be used
$paleblue = ImageColorAllocate($image, 240, 255, 255);
$barely = ImageColorAllocate($image, 220, 245, 245 );
$black = ImageColorAllocate( $image, 0, 0, 0);
$dark = ImageColorAllocate( $image, 160, 160, 160);
$ltgray = ImageColorAllocate( $image, 220, 220, 220);
$line = ImageColorAllocate( $image, 0, 0, 0);
$line1 = ImageColorAllocate( $image, 224, 0, 0);
$line2 = ImageColorAllocate( $image, 0, 192, 0);
$line3 = ImageColorAllocate( $image, 0, 0, 224);
$line4 = ImageColorAllocate( $image, 224, 224, 0);
// Fill the graph area with a pale blue
ImageFilledRectangle($image, $gl, $gt, $gr, $gb, $paleblue);

// Determine how many Y tic marks we want
$ts = 10;
if ( $maxy > 150 )
  $ts = 50;
if ( $maxy > 250 )
  $ts = 100;
if ( $maxy > 1000 )
  $ts = 200;
if ( $maxy > 2500 )
  $ts = 1000;
if ( $maxy > 10000 )
  $ts = 2000;

// Y-axis tic marks
for ( $y=0; $y<$maxy; $y+=$ts )
  {
    $yt = $gb - ($gb-$gt) * $y / $maxy;
    // Tic mark
    ImageLine( $image, $gl-5, $yt, $gl, $yt, $black );
    // Grid line
    ImageLine( $image, $gl, $yt, $gr, $yt, $barely );
    // Annotation
    ImageString($image, 1, 2, $yt-4, round($y), $black);
  }

// Array of month letters, starting to align with period numbers
$months = array( 'D','J','F','M','A','M','J','J','A','S','O','N','D' );
//$months = array( 'D','Jan',' ','Mar',' ','May',' ','Jul',' ','Sep',' ','Nov',' ' );

// X-axis tic marks
for ( $i=1; $i<=$n; $i++ )
  {
    // Tic mark
    ImageLine( $image, $xp[$i], $gb+5, $xp[$i], $gb, $black );
    // Grid line
    ImageLine( $image, $xp[$i], $gb, $xp[$i], $gt, $barely );
    // Annotation
    $mm = $x[$i] % 12;
    ImageString($image, 1, $xp[$i]-2, $gb+8, $months[$mm+1], $black);
  }

// Black box around the graph area
ImageLine( $image, $gl, $gt, $gr, $gt, $black);
ImageLine( $image, $gr, $gt, $gr, $gb, $black);
ImageLine( $image, $gr, $gb, $gl, $gb, $black);
ImageLine( $image, $gl, $gb, $gl, $gt, $black);
// Gray box around the canvas
ImageLine( $image, 0, 0, 449, 0, $dark);
ImageLine( $image, 449, 0, 449, 219, $dark);
ImageLine( $image, 449, 219, 0, 219, $dark);
ImageLine( $image, 0, 219, 0, 0, $dark);

// Draw the actual lines on the graph
for ( $i=1; $i<$n; $i++ )
{

    ImageLine( $image, $xp[$i], $yp1[$i]-1, $xp[$i+1], $yp1[$i+1]-1, $line1 );
    ImageLine( $image, $xp[$i], $yp1[$i]+1, $xp[$i+1], $yp1[$i+1]+1, $line1 );
    ImageLine( $image, $xp[$i], $yp1[$i], $xp[$i+1], $yp1[$i+1], $line1 );
    ImageLine( $image, $xp[$i]-1, $yp1[$i], $xp[$i+1]-1, $yp1[$i+1], $line1 );
    ImageLine( $image, $xp[$i]+1, $yp1[$i], $xp[$i+1]+1, $yp1[$i+1], $line1 );

    ImageLine( $image, $xp[$i], $yp2[$i]-1, $xp[$i+1], $yp2[$i+1]-1, $line2 );
    ImageLine( $image, $xp[$i], $yp2[$i]+1, $xp[$i+1], $yp2[$i+1]+1, $line2 );
    ImageLine( $image, $xp[$i], $yp2[$i], $xp[$i+1], $yp2[$i+1], $line2 );
    ImageLine( $image, $xp[$i]-1, $yp2[$i], $xp[$i+1]-1, $yp2[$i+1], $line2 );
    ImageLine( $image, $xp[$i]+1, $yp2[$i], $xp[$i+1]+1, $yp2[$i+1], $line2 );

    ImageLine( $image, $xp[$i], $yp3[$i]-1, $xp[$i+1], $yp3[$i+1]-1, $line3 );
    ImageLine( $image, $xp[$i], $yp3[$i]+1, $xp[$i+1], $yp3[$i+1]+1, $line3 );
    ImageLine( $image, $xp[$i], $yp3[$i], $xp[$i+1], $yp3[$i+1], $line3 );
    ImageLine( $image, $xp[$i]-1, $yp3[$i], $xp[$i+1]-1, $yp3[$i+1], $line3 );
    ImageLine( $image, $xp[$i]+1, $yp3[$i], $xp[$i+1]+1, $yp3[$i+1], $line3 );

}

// Display the Legend
//ImageString($image, 3, $gr+5, 40,  'Total',  $line);
ImageString($image, 3, $gr+5, 60,  'Total', $line1);
ImageString($image, 3, $gr+5, 80,  'Pub.Svc.',     $line2);
ImageString($image, 3, $gr+5, 100, 'Emerg.',  $line3);
//ImageString($image, 3, $gr+5, 120, 'Admin',  $line4);
ImageString($image, 1, 290, 210, 'sum212gl.php $Revision: 1.0 $', $ltgray );
ImageString($image, 3, $gr/2-20, $gb+20, 'Period', $black );
ImageString($image, 3, $gr/2-60, $gt-20, 'ARES Program Results', $black );

// Finally, actually expose the image
header('Content-type: image/png');
ImagePNG($image);

?>
