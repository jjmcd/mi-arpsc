<?php
//    ECcontact.php
//    $Revision: 1.1 $ - $Date: 2007-10-22 21:24:26-04 $
//
//    EClist displays the list of current contact information
//    for ECs and DECs
//
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

  echo "    <div id=\"main.p\">\n";
  echo "       <p><h1><center>Emergency Coordinators and District Emergency Coordinators</center></h1></p>\n";

  // Identify districts
  $q1='SELECT `districtkey`, `district_code`, `arpsc_district`, `deccall`' .
     ' FROM `arpsc_districts` ' .
     ' WHERE `districtkey`<199 ORDER BY `district_code`';
  $r1=getResult($q1,$db);

  // Loop through districts
  while ( $row1 = getRow($r1,$db) )
    {
      // Pick up name of DEC
      $qdec="SELECT `name` FROM `calldirectory` WHERE `callsign`='" . 
	$row1[3] . "'";
      $decname = '';
      $rdec = getResult($qdec,$db);
      // Display DEC name along with district map
      //echo "    <table width=\"100%\">\n      <td id=\"OsRow2\">\n";
      //echo "<segmentedlist>\n";
      if ( $rowdec = getRow($rdec,$db) )
	{
	  $decname = $rowdec[0];
	}
      // Only include word "Districts" for actual districts, not NWS offices
      if ( $row1[0]<100 )
	{
	  $title="District ";
	}
      $title=$title .  $row1[2];
      if ( $row1[3]=='' )
	{
	  $holder = 'vacant';
	}
      else
	{
	  $holder = $decname . ", " . $row1[3];
	}
      echo "\n";
      echo "  <section>\n";
      echo "    <title>" . $title . "</title>\n";

      echo "	<para>\n";
      /* echo "	  <figure float=\"0\"><title>" . $title . "</title>\n"; */
      /* echo "	    <mediaobject>\n"; */
      /* echo "	      <imageobject>\n"; */
      /* echo "		<imagedata scale=\"99\" scalefit=\"1\"\n"; */
      /* echo "              fileref=\"images/District" . $row1[2] .  */
      /* 	".gif\" format=\"GIF\"/>\n"; */
      /* echo "	      </imageobject>\n"; */
      /* echo "	      <textobject>\n"; */
      /* echo "		<para>\n"; */
      /* echo "		  " . $title . " Map\n"; */
      /* echo "		</para>\n"; */
      /* echo "	      </textobject>\n"; */
      /* echo "	    </mediaobject>\n"; */
      /* echo "	  </figure>\n"; */

      echo "      <table frame=\"all\">\n";
      echo "        <title>" . $title . " - " . $holder . "</title>\n";
      echo "        <tgroup cols=\"3\">\n";
      echo "          <colspec colnum=\"1\" colname=\"c1\" colwidth=\"3*\" />\n";
      echo "          <colspec colnum=\"2\" colname=\"c2\" colwidth=\"4*\" />\n";
      echo "          <colspec colnum=\"3\" colname=\"c3\" colwidth=\"1*\" />\n";
      echo "          <thead>\n";
      echo "            <row>\n";
      echo "              <entry>County</entry>\n";
      echo "              <entry>EC</entry>\n";
      echo "              <entry>Members</entry>\n";
      echo "            </row>\n";
      echo "          </thead>\n";
      echo "          <tbody>\n";
      // If the district wasn't NWS, we want to find counties
      if ( $row1[0]<100 )
	{
	  $q2="SELECT `countyname`, `countycode`, `eccall` FROM " .
	    "`arpsc_counties` WHERE `district`='" .
	    $row1[0] . "' ORDER BY `countyname`";
	  
	  $r2=getResult($q2,$db);
	  
	  $classcount = 0;
	  while ( $row2 = getRow($r2,$db) )
	    {
	      echo "          <row>\n";
	      // If no EC, display vacant in light font
	      $contacts='';
	      if ( $row2[2] == '' )
		{
		  echo "            <entry>" . $row2[0] . "</entry>\n";
		  echo "            <entry> (vacant) </entry>\n";
	          echo "            <entry> &nbsp; </entry>\n";
		}
	      else
		{
		  $eccall = $row2[2];
		  // Pick up name of EC
		  $qec="SELECT `name` FROM `calldirectory` WHERE `callsign`='" 
		    . $row2[2] . "'";
		  $rec = getResult( $qec, $db );
		  $ecname = '';
		  // In case EC missing from calldirectory, display call only,
		  // don't display ',' before call or (blank) name
		  if ( $rowec = getRow($rec,$db) )
		    {
		      $ecname = $rowec[0];
		      $eccall = $ecname . ", " . $eccall;
		    }
		  echo "            <entry><indexterm><primary>" . 
		    $row2[0] . "</primary></indexterm>" . $row2[0] . 
		    "</entry>\n";
		  echo "            <entry>" . $eccall . "</entry>\n";
		  // Pick up membership information
		  $q3a="SELECT MAX(`period`) FROM `arpsc_ecrept` " .
		    "WHERE `county`='" . $row2[1] . "'";
		  if ( $maxp = singleResult($q3a,$db) )
		    {
		      $q3="SELECT `aresmem` FROM `arpsc_ecrept` " .
			"WHERE `county`='" . $row2[1] . "' AND " .
			"`period`=" . $maxp;
		      $r3 = singleResult($q3,$db);
		      echo "            <entry align=\"center\">" .
			$r3 . "</entry>\n";
		    }
		  else
		    echo "            <entry><para>&nbsp;</para></entry>\n";
		  // Pick up contact information
		  /* $q3="SELECT ares_contact_type, contact, validity " . */
		  /*   "FROM ares_contact_info A, ares_contact_type B " . */
		  /*   "WHERE A.type = B.type AND A.call='" . $row2[2] . "'"; */
		  /* $r3 = getResult($q3,$db); */
		  /* echo "            <entry>\n"; */
		  /* echo "              <itemizedlist spacing='compact'>\n"; */
		  /* $membercount = 0; */
		  /* while ( $row3 = getRow($r3,$db) ) */
		  /*   { */
		  /*     if ( $row3[2] == 1 ) */
		  /* 	{ */
		  /* 	echo "                <listitem><para>" . $row3[0] . */
		  /* 	  ": " . $row3[1] . "</para></listitem>\n"; */
		  /* 	$membercount++; */
		  /* 	} */
		  /*   } */
		  /* if ( $membercount == 0 ) */
		  /*   echo "                <listitem><para>&nbsp;</para></listitem>\n"; */
		  /* echo "              </itemizedlist>\n"; */
		  /* echo "            </entry>\n"; */
		}
	      
	      echo "          </row>\n";
	    }
	  echo "        </tbody>\n";
	  echo "        </tgroup>\n";
	  echo "      </table>\n";
	  echo "    </para>\n";
	  echo "    <para>\n";
	  echo "      For detailed Emergency Coordinator contact information\n";
	  echo "      refer to the RACES SEOC Position Manual.\n";
	  echo "    </para>\n";
	  echo "  </section>\n";
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
