<?php
//    EClist.php
//    $Revision: 1.4 $ - $Date: 2007-11-06 08:35:00-05 $
//
//    EClist displays the list of current ECs and DECs
//
{
  include('includes/session.inc');
  $title=_('Michigan Section ECs');

  include('includes/functions.inc');

  // Remember the launch time
  $starttime = strftime("%A, %B %d %Y, %H:%M");

  // Open the database
  $db = mysql_connect($host , $dbuser, $dbpassword);
  mysql_select_db($DatabaseName,$db);

  ARESheader($title,"ARES Michigan Section ARPSC");

  ARESleftBar( $db );
?>
    <div id="main">
       <p><h1><center>Emergency Coordinators and District Emergency Coordinators</center></h1></p>

<?php

  // Identify districts
  $q1='SELECT `districtkey`, `district_code`, `arpsc_district`, `deccall`,`acting` from `arpsc_districts` ' .
     ' WHERE `districtkey`<199 ORDER BY `district_code`';
  $r1=getResult($q1,$db);

  // Loop through districts
  while ( $row1 = getRow($r1,$db) )
    {
      // Pick up name of DEC
      $qdec="SELECT `name` FROM `calldirectory` WHERE `callsign`='" . $row1[3] . "'";
      $decname = '';
      $decacting = '';
      if ( $row1[4]==1 )
	$decacting = "*";
      $rdec = getResult($qdec,$db);
      // Display DEC name along with district map
      echo "    <table width=\"100%\">\n      <td>\n";
      if ( $rowdec = getRow($rdec,$db) )
	{
	  $decname = $rowdec[0];
	}
      echo "        <h2>";
      // Only include word "Districts" for actual districts, not NWS offices
      if ( $row1[0]<100 )
	{
	  echo "District ";
	}
      echo $row1[2];
      if ( $row1[3]=='' )
	{
	  echo ' - vacant</h2>' . "\n";
	}
      else
	{
	  echo "<br />" . $decname . ", " . $row1[3] . $decacting . "</h2>\n";
	}
      echo "      </td>\n      <td width=\"97px\">\n        <img src=\"images/D" .
	$row1[0] . ".gif\" width=\"97\" height=\"96\">\n      </td>\n    </table>\n";
      // If the district wasn't NWS, we want to fing counties
      if ( $row1[0]<100 )
	{
	  echo "    <ul>\n";
	  $q2="SELECT `countyname`, `countycode`, `eccall`, `acting` FROM `arpsc_counties` WHERE `district`='" .
	    $row1[0] . "' ORDER BY `countyname`";

	  $r2=getResult($q2,$db);

	  while ( $row2 = getRow($r2,$db) )
	    {
	      // If no EC, display vacant in light font
	      if ( $row2[2] == '' )
		{
		  $eccall = '<span id="vacant">---vacant---</span>';
		}
	      else
		{
		  $eccall = $row2[2];
		  // Pick up name of EC
		  $qec="SELECT `name` FROM `calldirectory` WHERE `callsign`='" . $row2[2] . "'";
		  $rec = getResult( $qec, $db );
		  $ecname = '';
		  // In case EC missing from calldirectory, display call only, don't
		  // display ',' before call or (blank) name
		  if ( $rowec = getRow($rec,$db) )
		    {
		      $ecname = $rowec[0];
		      $eccall = $ecname . ", " . $eccall;
		    }
		}
	      $acting = '';
	      if ( $row2[3]=='1' )
		$acting = "*";
	      echo "      <b>" . $row2[0] . " - " . $eccall . $acting .  "</b><br />\n";
	    }
	  echo "    </ul>\n";
	}
    }

  echo "* = Acting<br />\n";
  echo "  </div>\n";
  sectLeaders($db);
  footer($starttime,$maxdate,
	 "\$Revision: 1.4 $ - \$Date: 2007-11-06 08:35:00-05 $");
}
?>
</div>
</body>
</html>
