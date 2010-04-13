<?php
//    countyfreqs.php
//    $Revision: 1.0 $ - $Date: 2010-04-12 09:33:07-04 $
//
//    Create image of state with county frquencies
//

include('includes/session.inc');
include('includes/functions.inc');

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

// Create the background wallpaper
$image = imagecreatefrompng("county_freqs-pl.png");

// Attributes for the frequencies
$white = ImageColorAllocate( $image, 255,255,255 );
$black = ImageColorAllocate( $image, 0,0,0 );
$shadow = ImageColorAllocate( $image, 128, 128, 128 );
$d1color = ImageColorAllocate( $image, 255,0,0 );
$d2color = ImageColorAllocate( $image, 0,128,128 );
$d3color = ImageColorAllocate( $image, 0,0,192 );
$d5color = ImageColorAllocate( $image, 192,128,0 );
$d6color = ImageColorAllocate( $image, 128,192,0 );
$d7color = ImageColorAllocate( $image, 192,0,192 );
$freqfont = 5;

// Get the data
$SQL="SELECT A.`x`,A.`y`,A.`frequency`,B.`district`,A.`valid` " .
  "FROM `county_freqs` A, `arpsc_counties` B " .
  "WHERE A.`county`=B.`countycode`";
$res=getResult($SQL,$db);

// Print the frequencies on the image
while( $row = getRow( $res ) )
  {
    // Only valid data
    if ( $row[4] )
      {
	// Use a separate color for each district
	$fcolor=$black;
	if ( $row[3] == 1 )
	  $fcolor=$d1color;
	if ( $row[3] == 2 )
	  $fcolor=$d2color;
	if ( $row[3] == 3 )
	  $fcolor=$d3color;
	if ( $row[3] == 5 )
	  $fcolor=$d5color;
	if ( $row[3] == 6 )
	  $fcolor=$d6color;
	if ( $row[3] == 7 )
	  $fcolor=$d7color;
	// Colored text on top of highlight/shadow
	ImageString( $image, $freqfont,  $row[0]-2, $row[1]+1, $row[2], $white);
	ImageString( $image, $freqfont,  $row[0]-1, $row[1]+1, $row[2], $white);
	ImageString( $image, $freqfont,  $row[0]+1, $row[1]-1, $row[2], $shadow);
	ImageString( $image, $freqfont,  $row[0]+2, $row[1]-1, $row[2], $shadow);
	ImageString( $image, $freqfont,  $row[0], $row[1], $row[2], $fcolor);
      }
  }

// Finally, actually expose the image
header('Content-type: image/png');
ImagePNG($image);

?>