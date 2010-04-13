<?php
//    arpsc_freqedit3.php
//    $Revision: 1.0 $ - $Date: 2010-04-12 09:49:55-04 $
//
//    Update the database with the supplied frequency
//
  include('includes/session.inc');
//  $title=_('Michigan County Emergency Frequencies');

  include('includes/functions.inc');

  // Open the database
  $db = mysql_connect($host , $dbuser, $dbpassword);
  mysql_select_db($DatabaseName,$db);

  if (isset($_GET['countyfreq']))
    {
      $county = $_GET['countyfreq'];
    }
  else
    {
      if (isset($_POST['countyfreq']))
        $county = $_POST['countyfreq'];
    }
  if (isset($_GET['freq']))
    {
      $freq = $_GET['freq'];
    }
  else
    {
      if (isset($_POST['freq']))
        $freq = $_POST['freq'];
    }
  if (isset($_GET['district']))
    {
      $district = $_GET['district'];
    }
  else
    {
      if (isset($_POST['district']))
        $district = $_POST['district'];
    }

    $validity = 1;
    if ( $freq < "144.00" )
      $validity = 0;
    if ( $freq > "147.99" )
      $validity = 0;
    $SQL="UPDATE `county_freqs` SET `valid`=" . $validity . 
      ", `updated`=now(), " .
      "`frequency`='" . $freq . "' " .
      "WHERE `county`='" . $county . "'";
    $res = getResult( $SQL, $db );
    if ( !$res )
      echo "<p><b>Error!</p></b>\n";
    else
      header("Location: arpsc_freqedit2.php?district=" . $district );

?>