<?php
//    index.php
//    $Revision: 1.1 $ - $Date: 2007-11-11 20:06:00-05 $
//
//    index is the opening page of the mi-nts website.  It displays the
//    standard menu, and then only some text introducing the site.
//

include('includes/session.inc');
$title=_('Michigan Section FSD-212');

include('includes/functions.inc');

// Remember the launch time
$starttime = strftime("%A, %B %d %Y, %H:%M");

// Open the database
$db = mysql_connect($host , $dbuser, $dbpassword);
mysql_select_db($DatabaseName,$db);

$period=92;

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


	if ( $keywords == "" )
	    $keywords="ARPSC ARES Michigan 'Amateur Radio Public Service Corps' 'Michigan Section' MI";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
<?php
	echo '  <title>' . $title . '</title>' . "\n";
	echo '  <meta name="KEYWORDS" content="' . $keywords . '" />' . "\n";
?>
  <style type="text/css" media="all">@import "css/arrl3c.css";</style>
  <link rel="alternate" type="application/rss+xml" title="RSS" href="http://www.arrl-mi.org/?q=rss.xml" />
  <link rel="shortcut icon" href="/misc/favicon.ico" type="image/x-icon" />
  <!--<style type="text/css" media="all">@import "css/bluemarine.css";</style>-->
  <script type="text/javascript"> </script>
</head>

<body>
<div id="container">

<?php

echo '  <div id="nonavbar">' . "\n";
echo "      &nbsp;\n";
echo '  </div>' . "\n";


echo '  <div id="main">' . "\n";
echo '  <p>"Michigan Section ARPSC Report for ' . convertDate($usedate) . "\"</p>\n";
?>
<p>
District,Juris,"Monthly Man Hours","Contrib Dollar Value","Total # ARES mbrs",Chg,Net,,"Public Service",,Emergency<br />
,,,,,,Num,"Man Hours",Num,"Man Hours",Num,"Man Hours"<br />
<?php


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

    echo $district . ',' . ',' . round($manhrs) . ',' . round($value) . ',' .
      $aresmem . ',' . $areschg . ',' . $netsess . ',' . round($nethrs). ',' . 
      $psnum . ',' . round($pshrs) . ',' . $emnum . ',' . round($emhrs) . " <br />\n";

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


echo "Total all districts" .  ",," . round($gmanhrs) . ',' . round($gvalue) . ',' .
$garesmem . ',' . $gareschg . ',' . $gnetsess . ',' . round($gnethrs) .',' . 
$gpsnum . ',' . round($gpshrs) .',' .  $gemnum . ',' . round($gemhrs) . "<br />\n";



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
		    echo $district;
		    $olddistrict = $district;
		  }
		echo ',' . $row2[0] . ',' . round($hours) . ',' .  round($value) . ',' . 
		  $row3[0] . ',' . $change . ',' . $row3[1] . ',' . $row3[2] . ',' . $row3[3] . ',' .
		  $row3[4] . ',' . $row3[5] . ',' . $row3[6] . "<br .>\n";

	      }
	    else
	      {
		if ( $row2[0]=='Arenac' )
		  {
		    if ( $district != $olddistrict )
		      {
			echo $district;
			$olddistrict = $district;
		      }
		    echo "," . $row2[0] . ",";
		    echo "w/Ogemaw" . "<br />\n" ;
		  }
		else if ( $row2[0]=='Clare' )
		  {
		    if ( $district != $olddistrict )
		      {
			echo $district;
			$olddistrict = $district;
		      }
		    echo "," . $row2[0] . ",";
		    echo "w/Isabella" . "<br />\n" ;
		  }
		else
		  {
		    if ( $district != $olddistrict )
		      {
			echo $district;
			$olddistrict = $district;
		      }
		    echo "," . $row2[0] . ",";
		    echo '"- - - - N o &nbsp;  R e p o r t - - - -"' . "<br/>\n";
		  }
	      }
	  }
      }
  }

    echo "<p></p>\n";
	echo "\"    Requested: " . $starttime . "Z\"<br />\n";
	echo "\"    Most recent data: " . $maxdate . "E\"<br />\n";
    echo "\"    copyright &copy; 2007, Michigan Section, American Radio Relay League\"\n";

echo "  </div>\n";


    echo "  <div id=\"footer\">\n";
    echo "  </div>\n";

//footer($starttime,$maxdate,
//       "\$Revision: 1.1 $ - \$Date: 2007-11-11 20:06:00-05 $");
?>
</div>
</body>
</html>
