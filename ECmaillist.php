<?php
//    ECcontact.php
//    $Revision: 1.1 $ - $Date: 2007-10-22 21:24:26-04 $
//
//    EClist displays the list of current contact information
//    for ECs and DECs
//

function formatNumber ( $number )
{
  if ( strlen($number) != 10 )
    {
      return $number;
    }
  $string = "(" . substr($number,0,3) . ") " . substr($number,3,3) .
    "-" . substr($number,6,4);
  return $string;
}

function contactInfo( $db, $call )
{
  $contacts='';
  $q3="SELECT  contact, validity " .
    "FROM ares_contact_info " .
    "WHERE `type` = 4 AND `call`='" . $call . "'";
  //echo "<p>[" . $q3 . "]</p>\n";
  $r3 = getResult($q3,$db);
  if ( $row3 = getRow($r3,$db) )
    {
      $contacts = $row3[0];
    }
  echo $contacts . ", ";
}

{
  include('includes/session.inc');
  $title=_('Michigan ECs');

  include('includes/functions.inc');

  // Remember the launch time
  $starttime = strftime("%A, %B %d %Y, %H:%M");

  // Open the database
  $db = mysql_connect($host , $dbuser, $dbpassword);
  mysql_select_db($DatabaseName,$db);

  ARESheader($title,"ARES Michigan Section ARPSC");

//  ARESleftBar( $db );
?>
    <div id="main.p">
       <p><h1><center>Emergency Coordinators and District Emergency Coordinators</center></h1></p>

<?php

  // Identify districts
  $q1='SELECT `districtkey`, `district_code`, `arpsc_district`, `deccall` from `arpsc_districts` ' .
     ' WHERE `districtkey`<199 ORDER BY `district_code`';
  $r1=getResult($q1,$db);

  // Loop through districts
  while ( $row1 = getRow($r1,$db) )
    {
      // Pick up name of DEC
      $qdec="SELECT `name` FROM `calldirectory` WHERE `callsign`='" . $row1[3] . "'";
      $decname = '';
      $rdec = getResult($qdec,$db);
      // Display DEC name along with district map
      echo "    <table width=\"100%\">\n      <td id=\"OsRow2\">\n";
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
	  echo "<br />" . $decname . ", " . $row1[3] . "</h2>\n";
	}
      echo "      </td>\n";


      echo "<td id=\"OsRow4\">";
      echo contactInfo($db,$row1[3]);
      echo "</td>\n";


        echo "      <td width=\"97px\">\n        <img src=\"images/D" .
	$row1[0] . ".gif\" width=\"97\" height=\"96\">\n      </td>\n    </table>\n";
      // If the district wasn't NWS, we want to find counties
      if ( $row1[0]<100 )
	{
	  echo "    <table width=100%>\n";
	  //echo "    <ul>\n";
	  $q2="SELECT `countyname`, `countycode`, `eccall` FROM `arpsc_counties` WHERE `district`='" .
	    $row1[0] . "' ORDER BY `countyname`";

	  $r2=getResult($q2,$db);

	  $classcount = 0;
	  while ( $row2 = getRow($r2,$db) )
	    {
	      if ( $classcount == 0 )
	      {
		$classcount = 1;
		$class='id="OsRow3"';
	      }
	      else
	      {
		$classcount = 0;
		$class='id="OsRow4"';
	      }
	      echo "      <tr>\n";
	      // If no EC, display vacant in light font
	      $contacts='';
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
		  // Pick up contact information
		  // $ctx = contactInfo( $row2[2] );
		  /* $q3="SELECT ares_contact_type, contact, validity " . */
		  /*   "FROM ares_contact_info A, ares_contact_type B " . */
		  /*   "WHERE A.type = B.type AND A.call='" . $row2[2] . "'"; */
		  /* $r3 = getResult($q3,$db); */
		  /* while ( $row3 = getRow($r3,$db) ) */
		  /* { */
		  /*   if ( $row3[2]==1 ) */
		  /* 	$contacts = $contacts . $row3[0] . ": <b>" . $row3[1] .  */
		  /* 	  "</b><br>"; */
		  /*   else */
		  /* 	$contacts = $contacts . $row3[0] . ": " . $row3[1] .  */
		  /* 	  "<br>"; */
		  /* } */

		}

	      //echo "      <td " . $class . ">" . $row2[0] . "</td>";
	      //echo "<td " . $class . ">" . $eccall . "</td>\n";
	      //echo "      <td " . $class . ">";
	      echo "<td>" . contactInfo($db,$row2[2]);
	      echo "</td>\n";
	      echo "      </tr>\n";
	    }
	  //echo "    </ul>\n";
	  echo "    </table>\n";
	}
    }

  echo "  </div>\n";
//  sectLeaders($db);
  footer($starttime,$maxdate,
	 "\$Revision: 1.1 $ - \$Date: 2007-10-22 21:24:26-04 $");
}
?>
</div>
</body>
</html>
